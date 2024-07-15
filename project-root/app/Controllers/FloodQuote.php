<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
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
            $message->entityType = $post['entityType'] ?? null;
            $message->firstName = $post['firstName'] ?? null;
            $message->lastName = $post['lastName'] ?? null;
            $message->secondInsured = $post['secondInsured'] ?? null;
            $message->companyName = $post['companyName'] ?? null;
            $message->companyName2 = $post['companyName2'] ?? null;
            $message->address = $post['address'] ?? null;
            $message->city = $post['city'] ?? null;
            $message->state = $post['state'] ?? null;
            $message->zip = $post['zip'] ?? null;
            $message->cellPhone = $post['cellPhone'] ?? null;
            $message->homePhone = $post['homePhone'] ?? null;
            $message->email = $post['email'] ?? null;
            $message->billTo = $post['billTo'] ?? null;
            $message->propertyAddress = $post['propertyAddress'] ?? null;
            $message->propertyCity = $post['propertyCity'] ?? null;
            $message->propertyState = $post['propertyState'] ?? null;
            $message->propertyZip = $post['propertyZip'] ?? null;
            $message->propertyCounty = $post['propertyCounty'] ?? null;
            $message->numOfFloors = $post['numOfFloors'] ?? null;
            $message->squareFeet = $post['squareFeet'] ?? null;
            $message->yearBuilt = $post['yearBuilt'] ?? null;
            $message->construction_type = $post['construction_type'] ?? null;
            $message->isPrimaryResidence = $post['isPrimaryResidence'] ?? null;
            $message->isRented = $post['isRented'] ?? null;
            $message->condoUnits = $post['condoUnits'] ?? null;
            $message->rcbap = $post['rcbap'] ?? null;
            $message->premium = $post['premium'] ?? null;
            $message->expiryDate = $post['expiryDate'] ?? null;
            $message->flood_zone = $post['flood_zone'] ?? null;
            $message->diagram_num = $post['diagram_num'] ?? null;
            $message->flood_foundation = $post['flood_foundation'] ?? null;
            $message->flood_occupancy = $post['flood_occupancy'] ?? null;
            $message->otherOccupancy = $post['otherOccupancy'] ?? null;
            $message->basementFinished = $post['basementFinished'] ?? null;
            $message->isEnclosureFinished = $post['isEnclosureFinished'] ?? null;
            $message->garageAttached = $post['garageAttached'] ?? null;
            $message->overWater = $post['overWater'] ?? null;
            $message->bfe = $post['bfe'] ?? null;
            $message->flfe = $post['flfe'] ?? null;
            $message->elevationDifference = $post['elevationDifference'] ?? null;
            $message->lfe = $post['lfe'] ?? null;
            $message->nhf = $post['nhf'] ?? null;
            $message->lhsm = $post['lhsm'] ?? null;
            $message->hag = $post['hag'] ?? null;
            $message->mle = $post['mle'] ?? null;
            $message->enclosure = $post['enclosure'] ?? null;
            $message->elevCertDate = $post['elevCertDate'] ?? null;
            $message->improvementDate = $post['improvementDate'] ?? null;
            $message->covABuilding = $post['covABuilding'] ?? null;
            $message->covCContent = $post['covCContent'] ?? null;
            $message->covDLoss = $post['covDLoss'] ?? null;
            $message->buildingReplacementCost = $post['buildingReplacementCost'] ?? null;
            $message->contentReplacementCost = $post['contentReplacementCost'] ?? null;
            $message->rceRation = $post['rceRation'] ?? null;
            $message->underinsuredRate = $post['underinsuredRate'] ?? null;
            $message->deductible = $post['deductible'] ?? null;
            $message->has_opprc = $post['has_opprc'] ?? null;
            $message->has_drc = $post['has_drc'] ?? null;
            $message->bindAuthority = $post['bindAuthority'] ?? null;
            $message->hiscox_id = $post['hiscox_id'] ?? null;
            $message->syndicate1BindAuthority = $post['syndicate1BindAuthority'] ?? null;
            $message->sydicate1Risk = $post['sydicate1Risk'] ?? null;
            $message->syndicate2BindAuthority = $post['syndicate2BindAuthority'] ?? null;
            $message->sydicate2Risk = $post['sydicate2Risk'] ?? null;
            $message->syndicate3BindAuthority = $post['syndicate3BindAuthority'] ?? null;
            $message->sydicate3Risk = $post['sydicate3Risk'] ?? null;
            $message->broker = $post['broker'] ?? null;
            $message->producer = $post['producer'] ?? null;
            $message->lossOccured = $post['lossOccured'] ?? null;
            $message->yearLastLoss = $post['yearLastLoss'] ?? null;
            $message->lastLossValue = $post['lastLossValue'] ?? null;
            $message->lossesIn10Years = $post['lossesIn10Years'] ?? null;
            $message->lastLossValueIn10Years = $post['lastLossValueIn10Years'] ?? null;
            $message->sandyLossAmount = $post['sandyLossAmount'] ?? null;
            $message->elevatedSinceLastLoss = $post['elevatedSinceLastLoss'] ?? null;
            $message->effectiveDate = $post['effectiveDate'] ?? null;
            $message->expirationDate = $post['expirationDate'] ?? null;
            $message->reason = $post['reason'] ?? null;
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

            return redirect()->to('/client/details/' . $client_id)->with('message', 'Flood Quote was successfully added.');
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
        $data['title'] = "Initial Rating Details";
        helper('form');

        $data['flood_quote'] = $this->floodQuoteService->findOne($id);

        if (!$data['flood_quote']) {
            return redirect()->to('/flood_quotes')->with('error', "Flood Quote not found.");
        }

        return view('FloodQuote/initial_details', ['data' => $data]);
    }
}
