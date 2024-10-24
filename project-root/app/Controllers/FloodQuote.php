<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Libraries\FloodQuoteCalculations;
use App\Libraries\BritFloodQuoteCalculations;
use App\Libraries\HiscoxApiV2;
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
    protected $bindAuthorityService;
    protected $hiscoxQuoteService;
    protected $constructionService;
    protected $hixcoxAPI;
    protected $floodZoneService;
    protected $floodOccupancyService;

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
        $this->bindAuthorityService = service('bindAuthorityService');
        $this->hiscoxQuoteService = service('hiscoxQuoteService');
        $this->constructionService = service('constructionService');
        $this->floodZoneService = service('floodZoneService');
        $this->floodOccupancyService = service('floodOccupancyService');
        $this->hixcoxAPI = new HiscoxApiV2();
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

    private function cancelHiscoxQuote($prevFloodQuote, $newFloodQuote, $prevFloodQuoteMetas)
    {
        $policyType = $this->getMetaValue($prevFloodQuoteMetas, "policyType");
        $selectedPolicyType = $this->getMetaValue($prevFloodQuoteMetas, "selectedPolicyType");
        $selectedDeductible = $this->getMetaValue($prevFloodQuoteMetas, "selectedDeductible");
        $selectedPolicyIndex = $this->getMetaValue($prevFloodQuoteMetas, "selectedPolicyIndex");
        $hiscoxID = $this->getMetaValue($prevFloodQuoteMetas, "hiscoxID");;
        $entityType = $this->getMetaValue($prevFloodQuoteMetas, "entityType");;
        $isRented = $this->getMetaValue($prevFloodQuoteMetas, "isRented", 0) == "1";
        $isEndorsement = $policyType == "END";
        $hiscoxQuote = null;

        $getHiscoxQuote = $this->hiscoxQuoteService->findHiscoxQuote($prevFloodQuote->flood_quote_id, $hiscoxID);
        if ($getHiscoxQuote) {
            $rawQuotes = $getHiscoxQuote->raw_quotes;
            $hiscoxQuote = json_decode($rawQuotes);
        } else {
            throw new Exception("Flood Quote has no existing Hiscox Quotes");
        }

        $quoteRequestDate = $hiscoxQuote->response->quoteRequestDate;
        $quoteExpiryDate = isset($hiscoxQuote->response->quoteExpiryDate) ? $hiscoxQuote->response->quoteExpiryDate : "";

        $productResponseRequest = HiscoxApiV2::createProductResponseRequest($hiscoxQuote, $prevFloodQuote, $isRented);
        $hiscoxProductResponse = $productResponseRequest["hiscoxProductResponse"];
        $primaryOptions = $hiscoxProductResponse->primary;
        $excessOptions = $hiscoxProductResponse->excess;

        $hiscoxSelectedOption = HiscoxApiV2::getHiscoxSelectedOption($selectedPolicyType, $selectedPolicyIndex, $selectedDeductible, $primaryOptions, $excessOptions, $isEndorsement);
        $hiscoxSelectedOptionIndex = $hiscoxSelectedOption['index'];
        $hiscoxOptions = $hiscoxSelectedOption['options'];

        $this->upsertHiscoxQuote([
            "hiscoxID" => $hiscoxID,
            "flood_quote_id" => $newFloodQuote->flood_quote_id,
            "client_id" => $prevFloodQuote->client_id,
            "quoteExpirationDate" => $quoteExpiryDate,
            "quoteRequestedDate" => $quoteRequestDate,
            "selectedPolicyType" => $selectedPolicyType,
            "selectedDeductible" => (int)$selectedDeductible,
            "selectedPolicyIndex" => (int)$selectedPolicyIndex,
            "rawQuotes" => json_encode($hiscoxQuote, JSON_PRETTY_PRINT),
        ]);

        $this->updateQuoteWithHiscox($hiscoxOptions, [
            "hiscoxID" => $hiscoxID,
            "selectedPolicyType" => $selectedPolicyType,
            "selectedDeductible" => (int)$selectedDeductible,
            "selectedOptionIndex" => (int)$selectedPolicyIndex,
            "floodQuoteId" => $newFloodQuote->flood_quote_id,
            "isRented" => $isRented,
            "isEndorsement" => $isEndorsement,
        ]);

        return true;
    }

    private function endorseHiscoxQuote($floodQuote)
    {
        $floodQuoteMetas = $this->floodQuoteService->getFloodQuoteMetas($floodQuote->flood_quote_id);
        $hasOpprc = $this->getMetaValue($floodQuoteMetas, "hasOpprc");
        $flood_foundation = $this->getMetaValue($floodQuoteMetas, "flood_foundation", "0");
        $isEnclosureFinished = $this->getMetaValue($floodQuoteMetas, "isEnclosureFinished", "0");
        $basement_finished = $this->getMetaValue($floodQuoteMetas, "basement_finished", "0");
        $garage_attached = $this->getMetaValue($floodQuoteMetas, "garage_attached", "0");
        $lfe = (float)$this->getMetaValue($floodQuoteMetas, "lfe", 0);
        $hag = (float)$this->getMetaValue($floodQuoteMetas, "hag", 0);
        $over_water = $this->getMetaValue($floodQuoteMetas, "over_water", "0");
        $endorseDate = $this->getMetaValue($floodQuoteMetas, "endorseDate");
        $prevHiscoxBoundID = $this->getMetaValue($floodQuoteMetas, "prevHiscoxBoundID");
        $isPerson = $floodQuote->entity_type == 0;
        $commercial_occupancy = $this->getMetaValue($floodQuoteMetas, "commercial_occupancy", "0");
        $isPrimaryResidence = $this->getMetaValue($floodQuoteMetas, "isPrimaryResidence", "0");
        $other_occupancy = $this->getMetaValue($floodQuoteMetas, "other_occupancy", "0");
        $occupancyType = HiscoxApiV2::getHiscoxOccupancyType($isPerson, $commercial_occupancy, $isPrimaryResidence, $other_occupancy);
        $construction_type = $this->getMetaValue($floodQuoteMetas, "construction_type", "0");
        $buildingReplacementCost = (int)$this->getMetaValue($floodQuoteMetas, "buildingReplacementCost", 0);
        $contentReplacementCost = (int)$this->getMetaValue($floodQuoteMetas, "contentReplacementCost", 0);
        $covABuilding = (int)$this->getMetaValue($floodQuoteMetas, "covABuilding", 0);
        $covCContent = (int)$this->getMetaValue($floodQuoteMetas, "covCContent", 0);
        $covDLoss = (int)$this->getMetaValue($floodQuoteMetas, "covDLoss", 0);
        $isRented = $this->getMetaValue($floodQuoteMetas, "isRented", 0) == "1";
        $yearLastLoss = $this->getMetaValue($floodQuoteMetas, "yearLastLoss");
        $lastLossValue = $this->getMetaValue($floodQuoteMetas, "lastLossValue", 0);

        $construction = $this->constructionService->findOne($construction_type);
        $constructionType = ($construction) ? $construction->hiscox_name : "";

        $payload = [
            "contentsCostValueType" => $hasOpprc ? "ReplacementCostValue" : "ActualCashValue",
            "foundation" => [
                "foundationType" => HiscoxApiV2::getHiscoxFoundationType($flood_foundation),
                "additionalFoundationType" => HiscoxApiV2::getHiscoxAdditionalFoundationType($isEnclosureFinished, $flood_foundation),
            ],
            "basementType" => HiscoxApiV2::getHiscoxBasementType($basement_finished),
            "attachedGarageType" => HiscoxApiV2::getHiscoxAttachedGarageType($garage_attached),
            "yearBuilt" => (int)$this->getMetaValue($floodQuoteMetas, "yearBuilt", 0),
            "squareFootage" => (int)$this->getMetaValue($floodQuoteMetas, "squareFeet", 0),
            "numberOfStories" => (int)$this->getMetaValue($floodQuoteMetas, "numOfFloors", 0),
            "elevationHeight" => $lfe - $hag,
            "buildingOverWaterType" => HiscoxApiV2::getHiscoxBuildingOverWaterType($over_water),
            "hiscoxId" => $prevHiscoxBoundID,
            "effectiveDate" => $endorseDate,
        ];

        if ($isPerson) {
            $payload["residential"] = [
                "occupancyType" => $occupancyType,
                "constructionType" => ($construction) ? $construction->hiscox_name : "",
                "replacementCostValues" => ["building" => $buildingReplacementCost, "contents" => $contentReplacementCost],
                "limits" => [
                    ["building" => $covABuilding, "contents" => $covCContent],
                    ["building" => $buildingReplacementCost, "contents" => $contentReplacementCost]
                ],
            ];
        } else {
            $payload["commercial"] = [
                "occupancyType" => $occupancyType,
                "constructionType" => ($construction) ? $construction->hiscox_name : ""
            ];

            if ($isRented) {
                $payload["commercial"]["tenanted"] = [
                    "replacementCostValues" => ["improvementsAndBetterments" => $buildingReplacementCost, "contents" => $contentReplacementCost],
                    "limits" => [
                        ["improvementsAndBetterments" => $covABuilding, "contents" => $covCContent],
                        ["improvementsAndBetterments" => $buildingReplacementCost, "contents" => $contentReplacementCost]
                    ],
                    "businessIncomeAndExtraExpenseAnnualValue" => $covDLoss
                ];
            } else {
                $payload["commercial"]["owned"] = [
                    "replacementCostValues" => ["building" => $buildingReplacementCost, "contents" => $contentReplacementCost],
                    "limits" => [
                        ["building" =>  $covABuilding, "contents" => $covCContent],
                        ["building" => $buildingReplacementCost, "contents" => $contentReplacementCost]
                    ],
                    "businessIncomeAndExtraExpenseAnnualValue" => $covDLoss
                ];
            }
        }

        if ($yearLastLoss != "") {
            $requestData["priorLosses"] = [
                ["year" => (int)$yearLastLoss, "value" => (float)$lastLossValue]
            ];
        }

        $hiscox = $this->hixcoxAPI->endorse($payload);
        $hiscoxResponse = $hiscox['response'];

        $errors = $hiscoxResponse->messages->errors;
        $validation = $hiscoxResponse->messages->validation;

        if (count($errors) && count($validation)) {
            $text = "";

            if (count($errors))
                $text .= "Errors: " . print_r($errors);

            if (count($validation))
                $text .= "Validations: " . print_r($validation);

            throw new Exception($text);
        } else {
            $hiscoxID = $hiscoxResponse->response->hiscoxId;
            $quoteRequestDate = $hiscoxResponse->response->quoteRequestDate;
            $quoteExpiryDate = $hiscoxResponse->response->quoteExpiryDate;
            $hiscoxString = json_encode($hiscoxResponse, JSON_PRETTY_PRINT);

            $this->hiscoxQuoteService->addHiscoxId($floodQuote->flood_quote_id, $hiscoxID);
            $this->upsertHiscoxQuote([
                "hiscoxID" => $hiscoxID,
                "flood_quote_id" => $floodQuote->flood_quote_id,
                "client_id" => $floodQuote->client_id,
                "quoteExpirationDate" => $quoteExpiryDate,
                "quoteRequestedDate" => $quoteRequestDate,
                "selectedPolicyType" => -1,
                "selectedDeductible" => -1,
                "selectedPolicyIndex" => -1,
                "rawQuotes" => $hiscoxString,
            ]);
        }

        return true;
    }

    private function renewHiscoxQuote($floodQuote)
    {
        $floodQuoteMetas = $this->floodQuoteService->getFloodQuoteMetas($floodQuote->flood_quote_id);
        $hasOpprc = $this->getMetaValue($floodQuoteMetas, "hasOpprc");
        $flood_foundation = $this->getMetaValue($floodQuoteMetas, "flood_foundation", "0");
        $isEnclosureFinished = $this->getMetaValue($floodQuoteMetas, "isEnclosureFinished", "0");
        $basement_finished = $this->getMetaValue($floodQuoteMetas, "basement_finished", "0");
        $garage_attached = $this->getMetaValue($floodQuoteMetas, "garage_attached", "0");
        $lfe = (float)$this->getMetaValue($floodQuoteMetas, "lfe", 0);
        $hag = (float)$this->getMetaValue($floodQuoteMetas, "hag", 0);
        $over_water = $this->getMetaValue($floodQuoteMetas, "over_water", "0");
        $endorseDate = $this->getMetaValue($floodQuoteMetas, "endorseDate");
        $prevHiscoxBoundID = $this->getMetaValue($floodQuoteMetas, "prevHiscoxBoundID");
        $isPerson = $floodQuote->entity_type == 0;
        $commercial_occupancy = $this->getMetaValue($floodQuoteMetas, "commercial_occupancy", "0");
        $isPrimaryResidence = $this->getMetaValue($floodQuoteMetas, "isPrimaryResidence", "0");
        $other_occupancy = $this->getMetaValue($floodQuoteMetas, "other_occupancy", "0");
        $occupancyType = HiscoxApiV2::getHiscoxOccupancyType($isPerson, $commercial_occupancy, $isPrimaryResidence, $other_occupancy);
        $construction_type = $this->getMetaValue($floodQuoteMetas, "construction_type", "0");
        $buildingReplacementCost = (int)$this->getMetaValue($floodQuoteMetas, "buildingReplacementCost", 0);
        $contentReplacementCost = (int)$this->getMetaValue($floodQuoteMetas, "contentReplacementCost", 0);
        $covABuilding = (int)$this->getMetaValue($floodQuoteMetas, "covABuilding", 0);
        $covCContent = (int)$this->getMetaValue($floodQuoteMetas, "covCContent", 0);
        $covDLoss = (int)$this->getMetaValue($floodQuoteMetas, "covDLoss", 0);
        $isRented = $this->getMetaValue($floodQuoteMetas, "isRented", 0) == "1";
        $yearLastLoss = $this->getMetaValue($floodQuoteMetas, "yearLastLoss");
        $lastLossValue = $this->getMetaValue($floodQuoteMetas, "lastLossValue", 0);

        $construction = $this->constructionService->findOne($construction_type);
        $constructionType = ($construction) ? $construction->hiscox_name : "";

        $payload = [
            "contentsCostValueType" => $hasOpprc ? "ReplacementCostValue" : "ActualCashValue",
            "foundation" => [
                "foundationType" => HiscoxApiV2::getHiscoxFoundationType($flood_foundation),
                "additionalFoundationType" => HiscoxApiV2::getHiscoxAdditionalFoundationType($isEnclosureFinished, $flood_foundation),
            ],
            "basementType" => HiscoxApiV2::getHiscoxBasementType($basement_finished),
            "attachedGarageType" => HiscoxApiV2::getHiscoxAttachedGarageType($garage_attached),
            "yearBuilt" => (int)$this->getMetaValue($floodQuoteMetas, "yearBuilt", 0),
            "squareFootage" => (int)$this->getMetaValue($floodQuoteMetas, "squareFeet", 0),
            "numberOfStories" => (int)$this->getMetaValue($floodQuoteMetas, "numOfFloors", 0),
            "elevationHeight" => $lfe - $hag,
            "buildingOverWaterType" => HiscoxApiV2::getHiscoxBuildingOverWaterType($over_water),
            "hiscoxId" => $prevHiscoxBoundID,
        ];

        if ($isPerson) {
            $payload["residential"] = [
                "occupancyType" => $occupancyType,
                "constructionType" => ($construction) ? $construction->hiscox_name : "",
                "replacementCostValues" => ["building" => $buildingReplacementCost, "contents" => $contentReplacementCost],
                "limits" => [
                    ["building" => $covABuilding, "contents" => $covCContent],
                    ["building" => $buildingReplacementCost, "contents" => $contentReplacementCost]
                ],
            ];
        } else {
            $payload["commercial"] = [
                "occupancyType" => $occupancyType,
                "constructionType" => ($construction) ? $construction->hiscox_name : ""
            ];

            if ($isRented) {
                $payload["commercial"]["tenanted"] = [
                    "replacementCostValues" => ["improvementsAndBetterments" => $buildingReplacementCost, "contents" => $contentReplacementCost],
                    "limits" => [
                        ["improvementsAndBetterments" => $covABuilding, "contents" => $covCContent],
                        ["improvementsAndBetterments" => $buildingReplacementCost, "contents" => $contentReplacementCost]
                    ],
                    "businessIncomeAndExtraExpenseAnnualValue" => $covDLoss
                ];
            } else {
                $payload["commercial"]["owned"] = [
                    "replacementCostValues" => ["building" => $buildingReplacementCost, "contents" => $contentReplacementCost],
                    "limits" => [
                        ["building" =>  $covABuilding, "contents" => $covCContent],
                        ["building" => $buildingReplacementCost, "contents" => $contentReplacementCost]
                    ],
                    "businessIncomeAndExtraExpenseAnnualValue" => $covDLoss
                ];
            }
        }

        if ($yearLastLoss != "") {
            $requestData["priorLosses"] = [
                ["year" => (int)$yearLastLoss, "value" => (float)$lastLossValue]
            ];
        }

        $hiscox = $this->hixcoxAPI->renew($payload);
        $hiscoxResponse = $hiscox['response'];

        $errors = $hiscoxResponse->messages->errors;
        $validation = $hiscoxResponse->messages->validation;

        if (count($errors) && count($validation)) {
            $text = "";

            if (count($errors))
                $text .= "Errors: " . implode($errors);

            if (count($validation))
                $text .= "Validations: " . implode($validation);

            throw new Exception($text);
        } else {
            $hiscoxID = $hiscoxResponse->response->hiscoxId;
            $quoteRequestDate = $hiscoxResponse->response->quoteRequestDate;
            $quoteExpiryDate = $hiscoxResponse->response->quoteExpiryDate;
            $hiscoxString = json_encode($hiscoxResponse, JSON_PRETTY_PRINT);

            $this->hiscoxQuoteService->addHiscoxId($floodQuote->flood_quote_id, $hiscoxID);
            $this->upsertHiscoxQuote([
                "hiscoxID" => $hiscoxID,
                "flood_quote_id" => $floodQuote->flood_quote_id,
                "client_id" => $floodQuote->client_id,
                "quoteExpirationDate" => $quoteExpiryDate,
                "quoteRequestedDate" => $quoteRequestDate,
                "selectedPolicyType" => -1,
                "selectedDeductible" => -1,
                "selectedPolicyIndex" => -1,
                "rawQuotes" => $hiscoxString,
            ]);
        }

        return true;
    }

    private function upsertHiscoxQuote(array $message)
    {
        $upsertMessage = new \stdClass();
        $upsertMessage->hiscoxID = $message["hiscoxID"];
        $upsertMessage->flood_quote_id = $message["flood_quote_id"];
        $upsertMessage->client_id = $message["client_id"];
        $upsertMessage->quoteExpirationDate = $message["quoteExpirationDate"];
        $upsertMessage->quoteRequestedDate = $message["quoteRequestedDate"];
        $upsertMessage->selectedPolicyType = $message["selectedPolicyType"];
        $upsertMessage->selectedDeductible = $message["selectedDeductible"];
        $upsertMessage->selectedPolicyIndex = $message["selectedPolicyIndex"];
        $upsertMessage->rawQuotes = $message["rawQuotes"];
        $this->hiscoxQuoteService->upsert($upsertMessage);
    }

    private function updateQuoteWithHiscox($hiscoxOptions, array $message)
    {
        $hiscox = new \stdClass();
        $hiscox->hiscox_id = $message["hiscoxID"];
        $hiscox->selectedPolicyType = $message["selectedPolicyType"];
        $hiscox->selectedDeductible = $message["selectedDeductible"];
        $hiscox->selectedPolicyIndex = $message["selectedOptionIndex"];
        $hiscox->buildingPremium = $hiscoxOptions->building_premium;

        $hiscox->totalPremium = $hiscoxOptions->building_premium +
            $hiscoxOptions->contents_premium +
            $hiscoxOptions->other_structures_premium +
            $hiscoxOptions->loss_of_use_premium +
            $hiscoxOptions->improvementsAndBettermentsPremium +
            $hiscoxOptions->businessIncomePremium;

        if ($message['isEndorsement'])
            $hiscox->total_premium = $hiscoxOptions->building_premium;

        $hiscox->deductible = $hiscoxOptions->deductible;
        $hiscox->coverageLimits = new \stdClass();
        $hiscox->coverageLimits->building = ($message["isRented"]) ? $hiscoxOptions->improvementsAndBettermentsLimit : $hiscoxOptions->building_coverage_limit;
        $hiscox->coverageLimits->contents = $hiscoxOptions->contents_coverage_limit;
        $hiscox->coverageLimits->otherStructures = $hiscoxOptions->other_structures_coverage_limit;
        $hiscox->coverageLimits->lossOfUse = $hiscoxOptions->loss_of_use_coverage_limit;

        $this->hiscoxQuoteService->updateQuoteWithHiscox($message["floodQuoteId"], $hiscox);
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
            $message->hasLossOccurred = $post['hasLossOccurred'] ?? 0;
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
            $message->hasLossOccurred = $post['hasLossOccurred'] ?? 0;
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
            $message->inForce = $post['inForce'] ?? 0;
            $message->isForRenewal = $post['isForRenewal'] ?? 0;

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

        $floodQuoteMetas = $this->floodQuoteService->getFloodQuoteMetas($id);

        $bind_authority = $this->getMetaValue($floodQuoteMetas, 'bind_authority');
        $bindAuthority = $this->bindAuthorityService->findOne($bind_authority);
        $bindAuthorityText = ($bindAuthority) ? $bindAuthority->reference : "";
        $calculations = null;

        if (!strpos($bindAuthorityText, '230') === false) {
            $calculations = new BritFloodQuoteCalculations($data['flood_quote']);
        } else {
            $calculations = new FloodQuoteCalculations($data['flood_quote']);
        }

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

    public function rate_detail($id = null)
    {
        helper('form');
        $data['title'] = "Quote Rate Details";
        $data['flood_quote'] = $this->floodQuoteService->findOne($id);

        if (!$data['flood_quote']) {
            return redirect()->to('/flood_quotes')->with('error', "Flood Quote not found.");
        }

        $floodQuoteMetas = $this->floodQuoteService->getFloodQuoteMetas($id);
        $bind_authority = $this->getMetaValue($floodQuoteMetas, 'bind_authority');

        $bindAuthority = $this->bindAuthorityService->findOne($bind_authority);
        $bindAuthorityText = ($bindAuthority) ? $bindAuthority->reference : "";

        $calculations = new FloodQuoteCalculations($data['flood_quote']);
        $data['calculations'] = $calculations;

        $boundDeductibleSaving = 0;
        $boundCoverageDiscount = 0;
        $boundPrimaryDiscount = 0;
        $boundLossSurcharge = 0;
        $boundMidLevelSurcharge = 0;
        $boundReplacementCostSurcharge = 0;
        $boundPersonalPropertySurcharge = 0;
        $boundDwellingSurcharge = 0;

        if (strpos($bindAuthorityText, '250') === false) {
            $boundDeductibleSaving = (float)$this->getMetaValue($floodQuoteMetas, "boundDeductibleSaving", 0);
            $boundCoverageDiscount = (float)$this->getMetaValue($floodQuoteMetas, "boundCoverageDiscount", 0);
            $boundPrimaryDiscount = (float)$this->getMetaValue($floodQuoteMetas, "boundPrimaryDiscount", 0);
            $boundLossSurcharge = (float)$this->getMetaValue($floodQuoteMetas, "boundLossSurcharge", 0);
            $boundMidLevelSurcharge = (float)$this->getMetaValue($floodQuoteMetas, "boundMidLevelSurcharge", 0);
            $boundReplacementCostSurcharge = (float)$this->getMetaValue($floodQuoteMetas, "boundReplacementCostSurcharge", 0);
            $boundPersonalPropertySurcharge = (float)$this->getMetaValue($floodQuoteMetas, "boundPersonalPropertySurcharge", 0);
            $boundDwellingSurcharge = (float)$this->getMetaValue($floodQuoteMetas, "boundDwellingSurcharge", 0);
        }

        $data["boundBaseRate"] = (float)$this->getMetaValue($floodQuoteMetas, "boundBaseRate", 0);
        $data["boundBasePremium"] = (float)$this->getMetaValue($floodQuoteMetas, "boundBasePremium", 0);
        $data["boundDeductibleSaving"] = $boundDeductibleSaving;
        $data["boundCoverageDiscount"] = $boundCoverageDiscount;
        $data["boundPrimaryDiscount"] = $boundPrimaryDiscount;
        $data["boundLossSurcharge"] = $boundLossSurcharge;
        $data["boundMidLevelSurcharge"] = $boundMidLevelSurcharge;
        $data["boundReplacementCostSurcharge"] = $boundReplacementCostSurcharge;
        $data["boundPersonalPropertySurcharge"] = $boundPersonalPropertySurcharge;
        $data["boundDwellingSurcharge"] = $boundDwellingSurcharge;
        $data["boundFinalPremium"] = (float)$this->getMetaValue($floodQuoteMetas, "boundFinalPremium", 0);
        $data["boundTaxAmount"] = (float)$this->getMetaValue($floodQuoteMetas, "boundTaxAmount", 0);
        $data["boundPolicyFee"] = (float)$this->getMetaValue($floodQuoteMetas, "boundPolicyFee", 0);
        $data["boundStampFee"] = (float)$this->getMetaValue($floodQuoteMetas, "boundStampFee", 0);
        $data["boundTotalCost"] = (float)$this->getMetaValue($floodQuoteMetas, "boundTotalCost", 0);
        $data["policyType"] = $this->getMetaValue($floodQuoteMetas, "policyType");
        $data["bindAuthorityText"] = $bindAuthorityText;
        $data["isBounded"] = (bool)$this->getMetaValue($floodQuoteMetas, "isBounded", 0);

        return view('FloodQuote/rate_detail_view', ['data' => $data]);
    }

    public function process($id = null, $action = "")
    {
        helper('form');
        $data['title'] = "";
        $data['floodQuote'] = $this->floodQuoteService->findOne($id);

        if (!$data['floodQuote']) {
            return redirect()->to('/flood_quotes')->with('error', "Flood Quote not found.");
        }

        if ($action !== 'cancel' && $action !== 'renew' && $action !== 'endorse') {
            throw new \CodeIgniter\Exceptions\PageNotFoundException();
        }

        $floodQuote = $data['floodQuote'];
        $mortgages = $this->floodQuoteMortgageService->getByFloodQuoteId($floodQuote->flood_quote_id);
        $floodQuoteMetas = $this->floodQuoteService->getFloodQuoteMetas($floodQuote->flood_quote_id);
        $client = $this->clientService->findOne($floodQuote->client_id);
        $bind_authority = $this->getMetaValue($floodQuoteMetas, 'bind_authority');

        $bindAuthority = $this->bindAuthorityService->findOne($bind_authority);
        $bindAuthorityText = ($bindAuthority) ? $bindAuthority->reference : "";

        $mortgage1 = null;
        $mortgage2 = null;
        foreach ($mortgages as $mortgagee) {
            if ($mortgagee->loan_index === '1') {
                $mortgage1 = $mortgagee;
            } elseif ($mortgagee->loan_index === '2') {
                $mortgage2 = $mortgagee;
            }
        }

        $data['client'] = $client;
        $data['floodQuoteMetas'] = $floodQuoteMetas;
        $data['mortgage1'] = $mortgage1;
        $data['mortgage2'] = $mortgage2;
        $data['bindAuthorityText'] = $bindAuthorityText;
        $data['action'] = $action;

        if (!$this->request->is('post')) {
            return view('FloodQuote/process_view', ['data' => $data]);
        }

        $post = $this->request->getPost();

        switch ($action) {
            case "cancel":
                $policyType = "CAN";
                break;

            case "renew":
                $policyType = "REN";
                break;

            case "endorse":
                $policyType = "END";
                break;

            default:
                break;
        }

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
            $message->syndicate1_bind_authority = $post['syndicate1_bind_authority'] ?? 0;
            $message->sydicate1Risk = $post['sydicate1Risk'] ?? "";
            $message->syndicate2_bind_authority = $post['syndicate2_bind_authority'] ?? 0;
            $message->sydicate2Risk = $post['sydicate2Risk'] ?? "";
            $message->syndicate3_bind_authority = $post['syndicate3_bind_authority'] ?? 0;
            $message->sydicate3Risk = $post['sydicate3Risk'] ?? "";
            $message->broker = $post['broker'] ?? 0;
            $message->producer = $post['producer'] ?? 0;
            $message->hasLossOccurred = $post['hasLossOccurred'] ?? 0;
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
            $message->baseRateAdjustment = $post['baseRateAdjustment'] ?? 0;
            $message->has10PercentAdjustment = $post['has10PercentAdjustment'] ?? 0;
            $message->additionalPremium = $post['additionalPremium'] ?? 0;
            $message->renewalAdditionalPremium = $post['renewalAdditionalPremium'] ?? 0;
            $message->renewalPremiumIncrease = $post['renewalPremiumIncrease'] ?? 0;
            $message->proratedDue = $post['proratedDue'] ?? 0;
            $message->hiscoxDwellLimitOverride = $post['hiscoxDwellLimitOverride'] ?? 0;
            $message->hiscoxContentLimitOverride = $post['hiscoxContentLimitOverride'] ?? 0;
            $message->hiscoxLossUseLimitOverride = $post['hiscoxLossUseLimitOverride'] ?? 0;
            $message->hiscoxOtherLimitOverride = $post['hiscoxOtherLimitOverride'] ?? 0;
            $message->cancelPremium = $post['cancelPremium'] ?? 0;
            $message->cancelTax = $post['cancelTax'] ?? 0;
            $message->policyNumber = $post['policyNumber'] ?? "";
            $message->slaNumber = $post['slaNumber'] ?? "";
            $message->endorseDate = $post['endorseDate'] ?? "";
            $message->previousPolicyNumber = $post['previousPolicyNumber'] ?? "";
            $message->policyType = $policyType;
            $message->client_id = $floodQuote->client_id;
            $message->prevHiscoxPremiumOverride = $post['prevHiscoxPremiumOverride'] ?? "";
            $message->prevHiscoxBoundID = $post['prevHiscoxBoundID'] ?? "";
            $message->prevHiscoxQuotedRate = $post['prevHiscoxQuotedRate'] ?? "";
            $message->totalRenewalPremium = $post['totalRenewalPremium'] ?? 0;
            $message->fullPremiumOverride = $post['fullPremiumOverride'] ?? 0;

            $newFloodQuote = $this->floodQuoteService->create($message);

            $mortgageMessage = new \stdClass();
            $mortgageMessage->flood_quote_id = $newFloodQuote->flood_quote_id;
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
            $mortgageMessage->flood_quote_id = $newFloodQuote->flood_quote_id;
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

            if (strpos($bindAuthorityText, "250") !== false) {
                switch ($action) {
                    case "endorse":
                        try {
                            $this->endorseHiscoxQuote($newFloodQuote);
                        } catch (Exception $e) {
                            return redirect()->to('/flood_quote/initial_details/' . $newFloodQuote->flood_quote_id)->with('error', 'Flood Quote was successfully added but was not endorsed to Hiscox');
                        }
                        break;

                    case "renew":
                        try {
                            $this->renewHiscoxQuote($newFloodQuote);
                        } catch (Exception $e) {
                            return redirect()->to('/flood_quote/initial_details/' . $newFloodQuote->flood_quote_id)->with('error', 'Flood Quote was successfully added but was not renewed to Hiscox');
                        }
                        break;

                    case "cancel":
                    default:
                        try {
                            $this->cancelHiscoxQuote($floodQuote, $newFloodQuote, $floodQuoteMetas);
                        } catch (Exception $e) {
                            return redirect()->to('/flood_quote/initial_details/' . $newFloodQuote->flood_quote_id)->with('error', 'Flood Quote was successfully added but no Hiscox Quote');
                        }
                        break;
                }
            }
            return redirect()->to('/flood_quote/initial_details/' . $newFloodQuote->flood_quote_id)->with('message', 'Flood Quote was successfully added.');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function bind($id = null)
    {
        helper('form');
        $data['title'] = "Flood Quote Binding Process";
        $data['floodQuote'] = $this->floodQuoteService->findOne($id);

        if (!$data['floodQuote']) {
            return redirect()->to('/flood_quotes')->with('error', "Flood Quote not found.");
        }

        $floodQuote = $data['floodQuote'];
        $floodQuoteMetas = $this->floodQuoteService->getFloodQuoteMetas($id);
        $bind_authority = $this->getMetaValue($floodQuoteMetas, 'bind_authority');

        $bindAuthority = $this->bindAuthorityService->findOne($bind_authority);
        $bindAuthorityText = ($bindAuthority) ? $bindAuthority->reference : "";

        if (strpos($bindAuthorityText, "250") === true) {
            return redirect()->to('/flood_quotes')->with('error', "Invalid Binding Authority");
        }

        $flood_zone = (int)$this->getMetaValue($floodQuoteMetas, "flood_zone", 0);
        $floodZone = $this->floodZoneService->findOne($flood_zone);

        $flood_occupancy = (int)$this->getMetaValue($floodQuoteMetas, "flood_occupancy", 0);
        $floodOccupancy = $this->floodOccupancyService->findOne($flood_occupancy);
        $mle = (int)$this->getMetaValue($floodQuoteMetas, "mle", 0);

        $data["floodZone"] = ($floodZone) ? $floodZone->name : "";
        $data["bfe"] = $this->getMetaValue($floodQuoteMetas, "bfe");
        $data["lfe"] = $this->getMetaValue($floodQuoteMetas, "lfe");
        $data["floodOccupancy"] = ($floodOccupancy) ? $floodOccupancy->name : "N/A";
        $data["diagramNumber"] = $this->getMetaValue($floodQuoteMetas, "diagramNumber");
        $data["mle"] = ($mle) ? $mle : "N/A";

        $data["bindAuthority"] = $bindAuthority->name;
        $data["policyType"] = $this->getMetaValue($floodQuoteMetas, "policyType");
        $data["propertyAddress"] = $this->getMetaValue($floodQuoteMetas, "propertyAddress");
        $data["propertyCity"] = $this->getMetaValue($floodQuoteMetas, "propertyCity");
        $data["propertyState"] = $this->getMetaValue($floodQuoteMetas, "propertyState");
        $data["propertyZip"] = $this->getMetaValue($floodQuoteMetas, "propertyZip");
        $data["proratedDue"] = $this->getMetaValue($floodQuoteMetas, "proratedDue", 0);
        $data["previousPolicyNumber"] = $this->getMetaValue($floodQuoteMetas, "previousPolicyNumber");
        $data["covABuilding"] = $this->getMetaValue($floodQuoteMetas, "covABuilding", 0);
        $data["covCContent"] = $this->getMetaValue($floodQuoteMetas, "covCContent", 0);
        $data["covDLossUse"] = $this->getMetaValue($floodQuoteMetas, "covDLossUse", 0);

        $calculations = new FloodQuoteCalculations($floodQuote);
        $data['calculations'] = $calculations;

        $policyType = $data["policyType"];

        switch ($policyType) {
            case "END":
            case "CAN":
                $data["policyNumber"] = $this->getMetaValue($floodQuoteMetas, "policyNumber");
                break;

            case "REN":
                $data["policyNumber"] = substr(date("Y"), -2) . "FHI00" . $id;
                break;

            default:
                $data["policyNumber"] = substr(date("Y"), -2) . "FHI00" . $id;
                break;
        }

        if ($this->request->is('post')) {
            $post = $this->request->getPost();
            $inForce = isset($post['inForce']) ? (int)$post['inForce'] : 0;

            $message = new \stdClass();
            $message->flood_quote_id = $id;
            $message->boundBaseRate = $post['boundBaseRate'];
            $message->boundBasePremium = $post['boundBasePremium'];
            $message->boundLossUseRate = $post['boundLossUseRate'];
            $message->boundLossUseCoverage = $post['boundLossUseCoverage'];
            $message->boundLossUsePremium = $post['boundLossUsePremium'];
            $message->boundTotalCoverages = $post['boundTotalCoverages'];
            $message->boundDeductibleSaving = $post['boundDeductibleSaving'];
            $message->boundCoverageDiscount = $post['boundCoverageDiscount'];
            $message->boundPrimaryDiscount = $post['boundPrimaryDiscount'];
            $message->boundLossSurcharge = $post['boundLossSurcharge'];
            $message->boundMidLevelSurcharge = $post['boundMidLevelSurcharge'];
            $message->boundReplacementCostSurcharge = $post['boundReplacementCostSurcharge'];
            $message->boundPersonalPropertySurcharge = $post['boundPersonalPropertySurcharge'];
            $message->boundDwellingSurcharge = $post['boundDwellingSurcharge'];
            $message->boundFinalPremium = $post['boundFinalPremium'];
            $message->boundTaxAmount = $post['boundTaxAmount'];
            $message->boundPolicyFee = $post['boundPolicyFee'];
            $message->boundTotalCost = $post['boundTotalCost'];
            $message->proratedDue = isset($post['proratedDue']) ? $post['proratedDue'] : 0;
            $message->policyNumber = $post['policyNumber'];
            $message->previousPolicyNumber = $post['previousPolicyNumber'];
            $message->inForce = ($policyType == "NEW") ? 0 : $inForce;
            $message->boundDate = $post['boundDate'];
            $message->isBounded = true;
            $message->boundAdditionalPremium = $post['boundAdditionalPremium'];
            $message->boundStampFee = $post['boundStampFee'];

            try {
                $this->floodQuoteService->bind($message);
                return redirect()->to('/flood_quote/choose_sla/' . $id)->with('message', 'Binding was successful.');
            } catch (Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        } else {
            return view('FloodQuote/bind_view', ['data' => $data]);
        }
    }
}
