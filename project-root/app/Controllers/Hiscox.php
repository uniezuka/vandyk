<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Libraries\HiscoxApiV2;
use Exception;

class Hiscox extends BaseController
{
    protected $floodQuoteService;
    protected $hiscoxQuoteService;
    protected $hixcoxAPI;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->floodQuoteService = service('floodQuoteService');
        $this->hiscoxQuoteService = service('hiscoxQuoteService');
        $this->hixcoxAPI = new HiscoxApiV2();
    }

    public function link($id = null)
    {
        helper('form');
        $data['title'] = "Link Hiscox";
        $data['floodQuote'] = $this->floodQuoteService->findOne($id);
        $data['hiscoxFloodQuote'] = null;

        if (!$data['floodQuote']) {
            return redirect()->to('/flood_quotes')->with('error', "Flood Quote not found.");
        }

        $isRented = $this->floodQuoteService->getFloodQuoteMetaValue($id, "isRented") == "1";
        $data['isRented'] = $isRented;

        if (!$this->request->is('post')) {
            return view('Hiscox/link_view', ['data' => $data]);
        }

        $post = $this->request->getPost();
        $hiscoxID = $post['hiscoxID'] ?? "";
        $hiscoxID = trim($hiscoxID);

        if ($hiscoxID == "") {
            return view('Hiscox/link_view', ['data' => $data]);
        }

        $getHiscoxQuote = $this->hixcoxAPI->getRequest($hiscoxID);
        $hiscoxFloodQuote = HiscoxApiV2::createCommonHiscoxQuote($getHiscoxQuote["response"]);
        $productResponseRequest = HiscoxApiV2::createProductResponseRequest($hiscoxFloodQuote, $data['floodQuote'], $isRented);

        $primaryOptions = $productResponseRequest["hiscoxProductResponse"]->primary;
        $excessOptions = $productResponseRequest["hiscoxProductResponse"]->excess;

        $hiscoxSelectedOptionIndex = -1;
        $hiscoxSelectedPolicyType = "-1";
        $hiscoxSelectedDeductible = -1;

        $data['hiscoxFloodQuote'] = $hiscoxFloodQuote;
        $data['productResponseRequest'] = $productResponseRequest;
        $data['primaryOptions'] = $primaryOptions;
        $data['excessOptions'] = $excessOptions;

        HiscoxApiV2::findSelectedDeductible($primaryOptions, $hiscoxSelectedOptionIndex, $hiscoxSelectedPolicyType, $hiscoxSelectedDeductible);
        HiscoxApiV2::findSelectedDeductible($excessOptions, $hiscoxSelectedOptionIndex, $hiscoxSelectedPolicyType, $hiscoxSelectedDeductible);

        $data['hiscoxSelectedOptionIndex'] = $hiscoxSelectedOptionIndex;
        $data['hiscoxSelectedPolicyType'] = $hiscoxSelectedPolicyType;
        $data['hiscoxSelectedDeductible'] = $hiscoxSelectedDeductible;

        $selectedOption = HiscoxApiV2::getHiscoxSelectedOption($hiscoxSelectedPolicyType, $hiscoxSelectedOptionIndex, $hiscoxSelectedDeductible, $primaryOptions, $excessOptions);
        $data['selectedOptionIndex'] = $selectedOption['index'];
        $data['hiscoxOptions'] = $selectedOption['options'];

        $message = new \stdClass();
        $message->hiscoxID = $hiscoxID;
        $message->flood_quote_id = $id;
        $message->client_id = $data['floodQuote']->client_id;
        $message->quoteExpirationDate = NULL;
        $message->quoteRequestedDate = $hiscoxFloodQuote->response->quoteRequestDate;
        $message->selectedPolicyType = $hiscoxSelectedPolicyType;
        $message->selectedDeductible = $hiscoxSelectedDeductible;
        $message->selectedPolicyIndex = $hiscoxSelectedOptionIndex;
        $message->rawQuotes = json_encode($hiscoxFloodQuote, JSON_PRETTY_PRINT);
        $this->hiscoxQuoteService->upsert($message);

        $this->hiscoxQuoteService->addHiscoxId($id, $hiscoxID);

        $message = new \stdClass();
        $message->flood_quote_id = $id;
        $message->selectedPolicyType = $hiscoxSelectedPolicyType;
        $message->selectedDeductible = $hiscoxSelectedDeductible;
        $message->selectedPolicyIndex = $hiscoxSelectedOptionIndex;
        $this->hiscoxQuoteService->updateSelectedHiscoxQuote($message);

        $hiscoxOptions = $selectedOption['options'];
        $hiscox = new \stdClass();
        $hiscox->hiscox_id = $hiscoxID;
        $hiscox->selectedPolicyType = $hiscoxSelectedPolicyType;
        $hiscox->selectedDeductible = $hiscoxSelectedDeductible;
        $hiscox->selectedPolicyIndex = $hiscoxSelectedOptionIndex;
        $hiscox->buildingPremium = $hiscoxOptions->building_premium;

        $hiscox->totalPremium = $hiscoxOptions->building_premium +
            $hiscoxOptions->contents_premium +
            $hiscoxOptions->other_structures_premium +
            $hiscoxOptions->loss_of_use_premium +
            $hiscoxOptions->improvementsAndBettermentsPremium +
            $hiscoxOptions->businessIncomePremium;

        $hiscox->deductible = $hiscoxOptions->deductible;
        $hiscox->coverageLimits = new \stdClass();
        $hiscox->coverageLimits->building = ($isRented) ? $hiscoxOptions->improvementsAndBettermentsLimit : $hiscoxOptions->building_coverage_limit;
        $hiscox->coverageLimits->contents = $hiscoxOptions->contents_coverage_limit;
        $hiscox->coverageLimits->otherStructures = $hiscoxOptions->other_structures_coverage_limit;
        $hiscox->coverageLimits->lossOfUse = $hiscoxOptions->loss_of_use_coverage_limit;

        if ($hiscoxSelectedDeductible != -1) {
            $this->hiscoxQuoteService->updateQuoteWithHiscox($id, $hiscox);
        }

        return view('Hiscox/link_view', ['data' => $data]);
    }

    public function create($id = null)
    {
        helper('form');
        $data['title'] = "Start Hiscox Quote";
        $data['floodQuote'] = $this->floodQuoteService->findOne($id);
        $data['hiscoxFloodQuote'] = null;

        if (!$data['floodQuote']) {
            return redirect()->to('/flood_quotes')->with('error', "Flood Quote not found.");
        }

        if (!$this->request->is('post')) {
            return view('Hiscox/create_view', ['data' => $data]);
        }

        return view('Hiscox/create_view', ['data' => $data]);
    }
}
