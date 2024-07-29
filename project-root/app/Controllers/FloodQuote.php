<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Libraries\FloodQuoteCalculations;
use Exception;

class FloodQuote extends BaseController
{
    protected $pager;
    protected $floodQuoteService;
    protected $floodQuoteMortgageService;
    protected $clientService;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->pager = service('pager');
        $this->floodQuoteService = service('floodQuoteService');
        $this->clientService = service('clientService');
        $this->floodQuoteMortgageService = service('floodQuoteMortgageService');
    }

    public function index()
    {
        helper('form');
        $page  = (int) ($this->request->getGet('page') ?? 1);
        $search = $this->request->getGet('search') ?? "";
        $search = trim($search);

        if ($search)
            $quotes = $this->floodQuoteService->search($page, $search);
        else
            $quotes = $this->floodQuoteService->getPaged($page);

        $pager_links = $this->pager->makeLinks($page, $quotes->limit, $quotes->total, 'bootstrap_full');

        $data['flood_quotes'] = $quotes->data;
        $data['title'] = "Flood Quotes";
        $data['pager_links'] = $pager_links;
        $data['search'] = $this->request->getGet('search') ?? "";
        return view('FloodQuote/index_view', ['data' => $data]);
    }

    public function create()
    {
        $data['title'] = "Create Flood Quote";
        helper('form');
        $client_id = $this->request->getGet('client_id') ?? "";
        $client = $this->clientService->findOne($client_id);
        $data['client'] = $client;

        if (!$this->request->is('post')) {
            return view('FloodQuote/create_view', ['data' => $data]);
        }

        $post = $this->request->getPost();

        // if ($this->validateData($post, [])) {
        try {
            $message = new \stdClass();
            $message->entityType = $post['entityType'] ?? "";
            $message->firstName = $post['firstName'] ?? "";
            $message->lastName = $post['lastName'] ?? "";
            $message->secondInsured = $post['secondInsured'] ?? "";
            $message->companyName = $post['companyName'] ?? "";
            $message->companyName2 = $post['companyName2'] ?? "";
            $message->address = $post['address'] ?? "";
            $message->city = $post['city'] ?? "";
            $message->state = $post['state'] ?? "";
            $message->zip = $post['zip'] ?? "";
            $message->cellPhone = $post['cellPhone'] ?? "";
            $message->homePhone = $post['homePhone'] ?? "";
            $message->email = $post['email'] ?? "";
            $message->billTo = $post['billTo'] ?? "";
            $message->propertyAddress = $post['propertyAddress'] ?? "";
            $message->propertyCity = $post['propertyCity'] ?? "";
            $message->propertyState = $post['propertyState'] ?? "";
            $message->propertyZip = $post['propertyZip'] ?? "";
            $message->propertyCounty = $post['propertyCounty'] ?? "";
            $message->numOfFloors = $post['numOfFloors'] ?? 0;
            $message->squareFeet = $post['squareFeet'] ?? 0;
            $message->yearBuilt = $post['yearBuilt'] ?? 0;
            $message->construction_type = $post['construction_type'] ?? 0;
            $message->isPrimaryResidence = $post['isPrimaryResidence'] ?? 0;
            $message->isRented = $post['isRented'] ?? 0;
            $message->condoUnits = $post['condoUnits'] ?? 0;
            $message->rcbap = $post['rcbap'] ?? 0;
            $message->premium = $post['premium'] ?? 0;
            $message->expiryDate = $post['expiryDate'] ?? 0;
            $message->flood_zone = $post['flood_zone'] ?? 0;
            $message->diagram_num = $post['diagram_num'] ?? "";
            $message->flood_foundation = $post['flood_foundation'] ?? 0;
            $message->flood_occupancy = $post['flood_occupancy'] ?? 0;
            $message->otherOccupancy = $post['otherOccupancy'] ?? 0;
            $message->basementFinished = $post['basementFinished'] ?? 0;
            $message->isEnclosureFinished = $post['isEnclosureFinished'] ?? 0;
            $message->garageAttached = $post['garageAttached'] ?? 0;
            $message->overWater = $post['overWater'] ?? 0;
            $message->bfe = $post['bfe'] ?? 0;
            $message->flfe = $post['flfe'] ?? 0;
            $message->elevationDifference = $post['elevationDifference'] ?? 0;
            $message->lfe = $post['lfe'] ?? 0;
            $message->nhf = $post['nhf'] ?? 0;
            $message->lhsm = $post['lhsm'] ?? 0;
            $message->hag = $post['hag'] ?? 0;
            $message->mle = $post['mle'] ?? 0;
            $message->enclosure = $post['enclosure'] ?? 0;
            $message->elevCertDate = $post['elevCertDate'] ?? 0;
            $message->improvementDate = $post['improvementDate'] ?? 0;
            $message->covABuilding = $post['covABuilding'] ?? 0;
            $message->covCContent = $post['covCContent'] ?? 0;
            $message->covDLoss = $post['covDLoss'] ?? 0;
            $message->buildingReplacementCost = $post['buildingReplacementCost'] ?? 0;
            $message->contentReplacementCost = $post['contentReplacementCost'] ?? 0;
            $message->rceRatio = $post['rceRatio'] ?? 0;
            $message->underInsuredRate = $post['underInsuredRate'] ?? 0;
            $message->deductible_id = $post['deductible'] ?? 0;
            $message->has_opprc = $post['has_opprc'] ?? 0;
            $message->has_drc = $post['has_drc'] ?? 0;
            $message->bindAuthority = $post['bindAuthority'] ?? 0;
            $message->hiscox_id = $post['hiscox_id'] ?? "";
            $message->syndicate1BindAuthority = $post['syndicate1BindAuthority'] ?? 0;
            $message->sydicate1Risk = $post['sydicate1Risk'] ?? "";
            $message->syndicate2BindAuthority = $post['syndicate2BindAuthority'] ?? 0;
            $message->sydicate2Risk = $post['sydicate2Risk'] ?? "";
            $message->syndicate3BindAuthority = $post['syndicate3BindAuthority'] ?? 0;
            $message->sydicate3Risk = $post['sydicate3Risk'] ?? "";
            $message->broker = $post['broker'] ?? 0;
            $message->producer = $post['producer'] ?? 0;
            $message->lossOccured = $post['lossOccured'] ?? 0;
            $message->yearLastLoss = $post['yearLastLoss'] ?? 0;
            $message->lastLossValue = $post['lastLossValue'] ?? 0;
            $message->lossesIn10Years = $post['lossesIn10Years'] ?? 0;
            $message->lastLossValueIn10Years = $post['lastLossValueIn10Years'] ?? 0;
            $message->sandyLossAmount = $post['sandyLossAmount'] ?? 0;
            $message->elevatedSinceLastLoss = $post['elevatedSinceLastLoss'] ?? 0;
            $message->effectiveDate = $post['effectiveDate'] ?? 0;
            $message->expirationDate = $post['expirationDate'] ?? 0;
            $message->reason = $post['reason'] ?? "";
            $message->isCondo = $post['isCondo'] ?? 0;
            $message->client_id = $client_id;

            $flood_quote = $this->floodQuoteService->create($message);

            $mortgageMessage = new \stdClass();
            $mortgageMessage->flood_quote_id = $flood_quote->flood_quote_id;
            $mortgageMessage->loan_index = 1;
            $mortgageMessage->name = $post['mortgagee1Name'] ?? null;
            $mortgageMessage->name2 = $post['mortgagee1Name2'] ?? null;
            $mortgageMessage->address = $post['mortgagee1Address'] ?? null;
            $mortgageMessage->city = $post['mortgagee1City'] ?? null;
            $mortgageMessage->state = $post['mortgagee1State'] ?? null;
            $mortgageMessage->zip = $post['mortgagee1Zip'] ?? null;
            $mortgageMessage->phone = $post['mortgagee1Phone'] ?? null;
            $mortgageMessage->loan_number = $post['mortgagee1LoanNumber'] ?? null;
            $this->floodQuoteMortgageService->create($mortgageMessage);

            $mortgageMessage = new \stdClass();
            $mortgageMessage->flood_quote_id = $flood_quote->flood_quote_id;
            $mortgageMessage->loan_index = 2;
            $mortgageMessage->name = $post['mortgagee2Name'] ?? null;
            $mortgageMessage->name2 = $post['mortgagee2Name2'] ?? null;
            $mortgageMessage->address = $post['mortgagee2Address'] ?? null;
            $mortgageMessage->city = $post['mortgagee2City'] ?? null;
            $mortgageMessage->state = $post['mortgagee2State'] ?? null;
            $mortgageMessage->zip = $post['mortgagee2Zip'] ?? null;
            $mortgageMessage->phone = $post['mortgagee2Phone'] ?? null;
            $mortgageMessage->loan_number = $post['mortgagee2LoanNumber'] ?? null;
            $this->floodQuoteMortgageService->create($mortgageMessage);

            return redirect()->to('/flood_quote/initial_details/' . $flood_quote->flood_quote_id)->with('message', 'Flood Quote was successfully added.');
            //return view('FloodQuote/create_view', ['data' => $data]);
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
        // } else {
        //     return view('FloodQuote/create_view', ['data' => $data]);
        // }
    }

    public function update()
    {
        helper('form');
        $data['title'] = "Update Flood Quote";
        return view('FloodQuote/update_view', ['data' => $data]);
    }

    public function intialDetails($id = null)
    {
        helper('form');
        $data['title'] = "Initial Rating Details";
        $data['flood_quote'] = $this->floodQuoteService->findOne($id);

        if (!$data['flood_quote']) {
            return redirect()->to('/flood_quotes')->with('error', "Flood Quote not found.");
        }

        $calculations = new FloodQuoteCalculations($data['flood_quote']);
        $data['calculations'] = $calculations;

        return view('FloodQuote/initial_details', ['data' => $data]);
    }
}
