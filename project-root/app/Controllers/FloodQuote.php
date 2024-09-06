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
    protected $slaPolicyService;
    protected $slaSettingService;
    protected $insurerService;

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
        $this->slaPolicyService = service('slaPolicyService');
        $this->slaSettingService = service('slaSettingService');
        $this->insurerService = service('insurerService');
    }

    private function getMetaValue($floodQuoteMetas, $meta_key, $default = '')
    {
        foreach ($floodQuoteMetas as $meta) {
            if ($meta->meta_key === $meta_key) {
                return $meta->meta_value;
            }
        }
        return $default;
    }

    private function incrementNumber($reference)
    {
        list($prefix, $number) = explode('-', $reference);
        $newNumber = str_pad((int)$number + 1, 5, '0', STR_PAD_LEFT);
        return $prefix . '-' . $newNumber;
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

        $ids = array_map(function ($flood_quote) {
            return $flood_quote->flood_quote_id;
        }, $data['flood_quotes']);

        $data['metas'] = $this->floodQuoteService->getBatchedFloodQuoteMetas($ids);

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
            $message->expiryDate = $post['expiryDate'] ?? "";
            $message->flood_zone = $post['flood_zone'] ?? 0;
            $message->diagramNumber = $post['diagramNumber'] ?? "";
            $message->flood_foundation = $post['flood_foundation'] ?? 0;
            $message->flood_occupancy = $post['flood_occupancy'] ?? 0;
            $message->other_occupancy = $post['other_occupancy'] ?? 0;
            $message->basement_finished = $post['basement_finished'] ?? 0;
            $message->isEnclosureFinished = $post['isEnclosureFinished'] ?? 0;
            $message->garage_attached = $post['garage_attached'] ?? 0;
            $message->over_water = $post['over_water'] ?? 0;
            $message->bfe = $post['bfe'] ?? 0;
            $message->flfe = $post['flfe'] ?? 0;
            $message->elevationDifference = $post['elevationDifference'] ?? 0;
            $message->lfe = $post['lfe'] ?? 0;
            $message->nhf = $post['nhf'] ?? 0;
            $message->lhsm = $post['lhsm'] ?? 0;
            $message->lag = $post['lag'] ?? 0;
            $message->hag = $post['hag'] ?? 0;
            $message->mle = $post['mle'] ?? 0;
            $message->enclosure = $post['enclosure'] ?? 0;
            $message->elevCertDate = $post['elevCertDate'] ?? "";
            $message->improvementDate = $post['improvementDate'] ?? "";
            $message->covABuilding = $post['covABuilding'] ?? 0;
            $message->covCContent = $post['covCContent'] ?? 0;
            $message->covDLoss = $post['covDLoss'] ?? 0;
            $message->buildingReplacementCost = $post['buildingReplacementCost'] ?? 0;
            $message->contentReplacementCost = $post['contentReplacementCost'] ?? 0;
            $message->rceRatio = $post['rceRatio'] ?? 0;
            $message->underInsuredRate = $post['underInsuredRate'] ?? 0;
            $message->deductible_id = $post['deductible'] ?? 0;
            $message->hasOpprc = $post['hasOpprc'] ?? 0;
            $message->hasDrc = $post['hasDrc'] ?? 0;
            $message->bind_authority = $post['bind_authority'] ?? 0;
            $message->hiscoxID = $post['hiscoxID'] ?? "";
            $message->syndicate1_bind_authority = $post['syndicate1_bind_authority'] ?? 0;
            $message->sydicate1Risk = $post['sydicate1Risk'] ?? "";
            $message->syndicate2_bind_authority = $post['syndicate2_bind_authority'] ?? 0;
            $message->sydicate2Risk = $post['sydicate2Risk'] ?? "";
            $message->syndicate3_bind_authority = $post['syndicate3_bind_authority'] ?? 0;
            $message->sydicate3Risk = $post['sydicate3Risk'] ?? "";
            $message->broker = $post['broker'] ?? 0;
            $message->producer = $post['producer'] ?? 0;
            $message->hasLossOccured = $post['hasLossOccured'] ?? 0;
            $message->yearLastLoss = $post['yearLastLoss'] ?? 0;
            $message->lastLossValue = $post['lastLossValue'] ?? 0;
            $message->lossesIn10Years = $post['lossesIn10Years'] ?? 0;
            $message->totalLossValueIn10Years = $post['totalLossValueIn10Years'] ?? 0;
            $message->sandyLossAmount = $post['sandyLossAmount'] ?? 0;
            $message->hasElevatedSinceLastLoss = $post['hasElevatedSinceLastLoss'] ?? 0;
            $message->effectiveDate = $post['effectiveDate'] ?? "";
            $message->expirationDate = $post['expirationDate'] ?? "";
            $message->reason = $post['reason'] ?? "";
            $message->isCondo = $post['isCondo'] ?? 0;
            $message->isSameAddress = $post['isSameAddress'] ?? 0;
            $message->hasWaitPeriod = $post['hasWaitPeriod'] ?? 0;
            $message->hasClosing = $post['hasClosing'] ?? 0;
            $message->hasBreakAwayWall = $post['hasBreakAwayWall'] ?? 0;
            $message->currentCompany = $post['currentCompany'] ?? "";
            $message->currentPremium = $post['currentPremium'] ?? "";
            $message->currentExpiryDate = $post['currentExpiryDate'] ?? "";
            $message->isQuoteApproved = $post['isQuoteApproved'] ?? 0;
            $message->policyType = "NEW";
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

    public function update($id = null)
    {
        helper('form');
        $data['title'] = "Update Flood Quote";
        $data['floodQuote'] = $this->floodQuoteService->findOne($id);

        if (!$data['floodQuote']) {
            return redirect()->to('/flood_quotes')->with('error', "Flood Quote not found.");
        }

        $client = $this->clientService->findOne($data['floodQuote']->client_id);
        $data['client'] = $client;
        $data['floodQuoteMetas'] = $this->floodQuoteService->getFloodQuoteMetas($data['floodQuote']->flood_quote_id);
        $mortgages = $this->floodQuoteMortgageService->getByFloodQuoteId($data['floodQuote']->flood_quote_id);

        $mortgage1 = null;
        $mortgage2 = null;
        foreach ($mortgages as $mortgagee) {
            if ($mortgagee->loan_index === '1') {
                $mortgage1 = $mortgagee;
            } elseif ($mortgagee->loan_index === '2') {
                $mortgage2 = $mortgagee;
            }
        }

        $data['mortgage1'] = $mortgage1;
        $data['mortgage2'] = $mortgage2;

        if (!$this->request->is('post')) {
            return view('FloodQuote/update_view', ['data' => $data]);
        }

        $post = $this->request->getPost();

        // if ($this->validateData($post, [])) {
        try {
            $message = new \stdClass();
            $message->flood_quote_id = $data['floodQuote']->flood_quote_id;
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
            $message->expiryDate = $post['expiryDate'] ?? "";
            $message->flood_zone = $post['flood_zone'] ?? 0;
            $message->diagramNumber = $post['diagramNumber'] ?? "";
            $message->flood_foundation = $post['flood_foundation'] ?? 0;
            $message->flood_occupancy = $post['flood_occupancy'] ?? 0;
            $message->other_occupancy = $post['other_occupancy'] ?? 0;
            $message->basement_finished = $post['basement_finished'] ?? 0;
            $message->isEnclosureFinished = $post['isEnclosureFinished'] ?? 0;
            $message->garage_attached = $post['garage_attached'] ?? 0;
            $message->over_water = $post['over_water'] ?? 0;
            $message->bfe = $post['bfe'] ?? 0;
            $message->flfe = $post['flfe'] ?? 0;
            $message->elevationDifference = $post['elevationDifference'] ?? 0;
            $message->lfe = $post['lfe'] ?? 0;
            $message->nhf = $post['nhf'] ?? 0;
            $message->lhsm = $post['lhsm'] ?? 0;
            $message->lag = $post['lag'] ?? 0;
            $message->hag = $post['hag'] ?? 0;
            $message->mle = $post['mle'] ?? 0;
            $message->enclosure = $post['enclosure'] ?? 0;
            $message->elevCertDate = $post['elevCertDate'] ?? "";
            $message->improvementDate = $post['improvementDate'] ?? "";
            $message->covABuilding = $post['covABuilding'] ?? 0;
            $message->covCContent = $post['covCContent'] ?? 0;
            $message->covDLoss = $post['covDLoss'] ?? 0;
            $message->buildingReplacementCost = $post['buildingReplacementCost'] ?? 0;
            $message->contentReplacementCost = $post['contentReplacementCost'] ?? 0;
            $message->rceRatio = $post['rceRatio'] ?? 0;
            $message->underInsuredRate = $post['underInsuredRate'] ?? 0;
            $message->deductible_id = $post['deductible'] ?? 0;
            $message->hasOpprc = $post['hasOpprc'] ?? 0;
            $message->hasDrc = $post['hasDrc'] ?? 0;
            $message->bind_authority = $post['bind_authority'] ?? 0;
            $message->hiscoxID = $post['hiscoxID'] ?? "";
            $message->syndicate1_bind_authority = $post['syndicate1_bind_authority'] ?? 0;
            $message->sydicate1Risk = $post['sydicate1Risk'] ?? "";
            $message->syndicate2_bind_authority = $post['syndicate2_bind_authority'] ?? 0;
            $message->sydicate2Risk = $post['sydicate2Risk'] ?? "";
            $message->syndicate3_bind_authority = $post['syndicate3_bind_authority'] ?? 0;
            $message->sydicate3Risk = $post['sydicate3Risk'] ?? "";
            $message->broker = $post['broker'] ?? 0;
            $message->producer = $post['producer'] ?? 0;
            $message->hasLossOccured = $post['hasLossOccured'] ?? 0;
            $message->yearLastLoss = $post['yearLastLoss'] ?? 0;
            $message->lastLossValue = $post['lastLossValue'] ?? 0;
            $message->lossesIn10Years = $post['lossesIn10Years'] ?? 0;
            $message->totalLossValueIn10Years = $post['totalLossValueIn10Years'] ?? 0;
            $message->sandyLossAmount = $post['sandyLossAmount'] ?? 0;
            $message->hasElevatedSinceLastLoss = $post['hasElevatedSinceLastLoss'] ?? 0;
            $message->effectiveDate = $post['effectiveDate'] ?? "";
            $message->expirationDate = $post['expirationDate'] ?? "";
            $message->reason = $post['reason'] ?? "";
            $message->isCondo = $post['isCondo'] ?? 0;
            $message->isSameAddress = $post['isSameAddress'] ?? 0;
            $message->hasWaitPeriod = $post['hasWaitPeriod'] ?? 0;
            $message->hasClosing = $post['hasClosing'] ?? 0;
            $message->hasBreakAwayWall = $post['hasBreakAwayWall'] ?? 0;
            $message->currentCompany = $post['currentCompany'] ?? "";
            $message->currentPremium = $post['currentPremium'] ?? "";
            $message->currentExpiryDate = $post['currentExpiryDate'] ?? "";
            $message->isQuoteApproved = $post['isQuoteApproved'] ?? 0;
            $message->isQuoteDeclined = $post['isQuoteDeclined'] ?? 0;
            $message->baseRateAdjustment = $post['baseRateAdjustment'] ?? 0;
            $message->has10PercentAdjustment = $post['has10PercentAdjustment'] ?? 0;
            $message->additionalPremium = $post['additionalPremium'] ?? 0;
            $message->hiscoxPremiumOverride = $post['hiscoxPremiumOverride'] ?? 0;
            $message->renewalAdditionalPremium = $post['renewalAdditionalPremium'] ?? 0;
            $message->renewalPremiumIncrease = $post['renewalPremiumIncrease'] ?? 0;
            $message->fullPremiumOverride = $post['fullPremiumOverride'] ?? 0;
            $message->proratedDue = $post['proratedDue'] ?? 0;
            $message->hiscoxDwellLimitOverride = $post['hiscoxDwellLimitOverride'] ?? 0;
            $message->hiscoxContentLimitOverride = $post['hiscoxContentLimitOverride'] ?? 0;
            $message->hiscoxLossUseLimitOverride = $post['hiscoxLossUseLimitOverride'] ?? 0;
            $message->hiscoxOtherLimitOverride = $post['hiscoxOtherLimitOverride'] ?? 0;
            $message->cancelPremium = $post['cancelPremium'] ?? 0;
            $message->cancelTax = $post['cancelTax'] ?? 0;
            $message->endorseDate = $post['endorseDate'] ?? "";
            $message->previousPolicyNumber = $post['previousPolicyNumber'] ?? "";
            $message->isBounded = $post['isBounded'] ?? 0;

            $this->floodQuoteService->update($message);

            $mortgageMessage = new \stdClass();
            $mortgageMessage->flood_quote_mortgage_id = $post['mortgagee1Id'];
            $mortgageMessage->flood_quote_id = $data['floodQuote']->flood_quote_id;
            $mortgageMessage->loan_index = 1;
            $mortgageMessage->name = $post['mortgagee1Name'] ?? null;
            $mortgageMessage->name2 = $post['mortgagee1Name2'] ?? null;
            $mortgageMessage->address = $post['mortgagee1Address'] ?? null;
            $mortgageMessage->city = $post['mortgagee1City'] ?? null;
            $mortgageMessage->state = $post['mortgagee1State'] ?? null;
            $mortgageMessage->zip = $post['mortgagee1Zip'] ?? null;
            $mortgageMessage->phone = $post['mortgagee1Phone'] ?? null;
            $mortgageMessage->loan_number = $post['mortgagee1LoanNumber'] ?? null;
            $this->floodQuoteMortgageService->update($mortgageMessage);

            $mortgageMessage = new \stdClass();
            $mortgageMessage->flood_quote_mortgage_id = $post['mortgagee2Id'];
            $mortgageMessage->flood_quote_id = $data['floodQuote']->flood_quote_id;
            $mortgageMessage->loan_index = 2;
            $mortgageMessage->name = $post['mortgagee2Name'] ?? null;
            $mortgageMessage->name2 = $post['mortgagee2Name2'] ?? null;
            $mortgageMessage->address = $post['mortgagee2Address'] ?? null;
            $mortgageMessage->city = $post['mortgagee2City'] ?? null;
            $mortgageMessage->state = $post['mortgagee2State'] ?? null;
            $mortgageMessage->zip = $post['mortgagee2Zip'] ?? null;
            $mortgageMessage->phone = $post['mortgagee2Phone'] ?? null;
            $mortgageMessage->loan_number = $post['mortgagee2LoanNumber'] ?? null;
            $this->floodQuoteMortgageService->update($mortgageMessage);

            return redirect()->to('/client/details/' . $data['floodQuote']->client_id)->with('message', 'Flood Quote was successfully updated.');
            // return view('FloodQuote/update_view', ['data' => $data]);
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function initial_details($id = null)
    {
        helper('form');
        $data['title'] = "Initial Rating Details";
        $data['flood_quote'] = $this->floodQuoteService->findOne($id);

        if (!$data['flood_quote']) {
            return redirect()->to('/flood_quotes')->with('error', "Flood Quote not found.");
        }

        $calculations = new FloodQuoteCalculations($data['flood_quote']);
        $data['calculations'] = $calculations;

        return view('FloodQuote/initial_details_view', ['data' => $data]);
    }

    public function choose_sla($id = null)
    {
        $minAvailablePolicies = 5;

        helper('form');
        $data['title'] = "Choose SLA Number";
        $data['floodQuote'] = $this->floodQuoteService->findOne($id);

        if (!$data['floodQuote']) {
            return redirect()->to('/flood_quotes')->with('error', "Flood Quote not found.");
        }

        $floodQuote = $data['floodQuote'];
        $isPerson = $floodQuote->entity_type == 0;
        $floodQuoteMetas = $this->floodQuoteService->getFloodQuoteMetas($id);
        $currentSLASetting = $this->slaSettingService->getCurrent();
        $prevYear = $currentSLASetting->year - 1;
        $availableSLAPolicies = $this->slaPolicyService->getAvailableSLAPolicies($minAvailablePolicies, $currentSLASetting->prefix);
        $prevSLASetting = $this->slaSettingService->getByYear($prevYear);

        $latestPolicy = $this->slaPolicyService->getLatestPolicy($currentSLASetting->prefix);

        if (count($availableSLAPolicies) < $minAvailablePolicies) {
            $lastTransactionNumber = count($availableSLAPolicies) == 0
                ? (($latestPolicy)
                    ? $latestPolicy->transaction_number
                    : $currentSLASetting->prefix . "-00000")
                : $availableSLAPolicies[count($availableSLAPolicies) - 1]->transaction_number;

            for ($i = count($availableSLAPolicies); $i < $minAvailablePolicies; $i++) {
                $newTransactionNumber = $this->incrementNumber($lastTransactionNumber);

                $newPolicy = (object) [
                    'transaction_name' => '',
                    'insured_name' => '',
                    'transaction_number' => $newTransactionNumber
                ];

                $availableSLAPolicies[] = $newPolicy;
                $lastTransactionNumber = $newTransactionNumber;
            }
        }

        $data["propertyAddress"] = $this->getMetaValue($floodQuoteMetas, "propertyAddress");
        $data["quoteName"] = $isPerson
            ? $floodQuote->first_name . " " . $floodQuote->last_name
            : $floodQuote->company_name;
        $data["policyType"] = $this->getMetaValue($floodQuoteMetas, "policyType");
        $data['currentSLASetting'] = $currentSLASetting;
        $data['prevSLASetting'] = $prevSLASetting;
        $data['prevAvailableSLAPolicies'] = ($data['prevSLASetting']) ?
            $this->slaPolicyService->getAvailableSLAPolicies($minAvailablePolicies, $data['prevSLASetting']->prefix) : [];
        $data['availableSLAPolicies'] = $availableSLAPolicies;

        return view('FloodQuote/choose_sla_view', ['data' => $data]);
    }

    public function bind_sla($id = null)
    {
        helper('form');
        $data['title'] = "Bind SLA Number";
        $data['floodQuote'] = $this->floodQuoteService->findOne($id);

        if (!$data['floodQuote']) {
            return redirect()->to('/flood_quotes')->with('error', "Flood Quote not found.");
        }

        $floodQuote = $data['floodQuote'];
        $floodQuoteMetas = $this->floodQuoteService->getFloodQuoteMetas($id);
        $isPerson = $floodQuote->entity_type == 0;
        $policyType = $this->getMetaValue($floodQuoteMetas, "policyType");

        $transactionNumber = $this->request->getGet('transaction_number') ?? "";
        $transactionNumber = trim($transactionNumber);
        $policyTypeNumber = 0;
        $slaPolicy = null;

        if ($transactionNumber != "")
            $slaPolicy = $this->slaPolicyService->findByTransactionNumber($transactionNumber);

        // TODO ???
        switch ($policyType) {
            case "NEW":
                $policyTypeNumber = 1;
                break;

            case "CAN":
                $policyTypeNumber = 4;
                break;

            case "REN":
                $policyTypeNumber = 5;
                break;

            default:
                break;
        }

        $data["propertyAddress"] = $this->getMetaValue($floodQuoteMetas, "propertyAddress");
        $data["quoteName"] = $isPerson
            ? $floodQuote->first_name . " " . $floodQuote->last_name
            : $floodQuote->company_name;
        $data["boundFinalPremium"] = (float)$this->getMetaValue($floodQuoteMetas, "boundFinalPremium", 0);
        $data["propertyCity"] = $this->getMetaValue($floodQuoteMetas, "propertyCity");
        $data["propertyState"] = $this->getMetaValue($floodQuoteMetas, "propertyState");
        $data["boundTaxAmount"] = (float)$this->getMetaValue($floodQuoteMetas, "boundTaxAmount", 0);
        $data["boundPolicyFee"] = (float)$this->getMetaValue($floodQuoteMetas, "boundPolicyFee", 0);
        $data["boundTotalCost"] = (float)$this->getMetaValue($floodQuoteMetas, "boundTotalCost", 0);
        $data["transactionNumber"] = $transactionNumber;
        $data["policyNumber"] = $this->getMetaValue($floodQuoteMetas, "policyNumber");
        $data["propertyZip"] = $this->getMetaValue($floodQuoteMetas, "propertyZip");
        $data["transactionDate"] = ($policyType == "REN" || $policyType == "NEW")
            ? $floodQuote->effectivity_date
            : $this->getMetaValue($floodQuoteMetas, "boundDate");
        $data["policyTypeNumber"] = $policyTypeNumber;
        $data["slaPolicyId"] = ($slaPolicy) ? $slaPolicy->sla_policy_id : "";

        if (!$this->request->is('post')) {
            return view('FloodQuote/bind_sla_view', ['data' => $data]);
        }

        $post = $this->request->getPost([
            'transactionTypeId',
            'insuredName',
            'policyNumber',
            'effectivityDate',
            'expiryDate',
            'insurerId',
            'firePremium',
            'otherPremium',
            'totalPremium',
            'county',
            'fireCodeId',
            'coverageId',
            'transactionDate',
            'location',
            'zip',
            'fireTax',
            'regTax',
            'slaNumber',
            'slaPolicyId',
        ]);

        $insurer = $this->insurerService->findOne($post['insurerId']);
        $post['insurerNAIC'] = ($insurer) ? $insurer->naic : "";

        $post['transactionNumber'] = $post['slaNumber'];
        $post['sla_policy_id'] = $post['slaPolicyId'];

        try {
            if ($post['sla_policy_id']) {
                $this->slaPolicyService->update((object) $post);
            } else {
                $this->slaPolicyService->create((object) $post);
            }

            $this->floodQuoteService->updateSLANumber($post['slaNumber'], $id);
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->to('/flood_quotes')->with('message', 'SLA successfully binded to a Quote!');
    }
}
