<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Libraries\HiscoxApiV2;
use App\Libraries\FloodQuoteCalculations;
use Exception;

class Hiscox extends BaseController
{
    protected $floodQuoteService;
    protected $hiscoxQuoteService;
    protected $constructionService;
    protected $floodZoneService;
    protected $floodOccupancyService;
    protected $bindAuthorityService;
    protected $hixcoxAPI;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->floodQuoteService = service('floodQuoteService');
        $this->hiscoxQuoteService = service('hiscoxQuoteService');
        $this->constructionService = service('constructionService');
        $this->floodZoneService = service('floodZoneService');
        $this->floodOccupancyService = service('floodOccupancyService');
        $this->bindAuthorityService = service('bindAuthorityService');
        $this->hixcoxAPI = new HiscoxApiV2();
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

    private function updateQuoteWithHiscox($hiscoxOptions, array $message, $isEndorsement = false)
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

        if ($isEndorsement)
            $hiscox->totalPremium = $hiscoxOptions->building_premium;

        $hiscox->deductible = $hiscoxOptions->deductible;
        $hiscox->coverageLimits = new \stdClass();
        $hiscox->coverageLimits->building = ($message["isRented"]) ? $hiscoxOptions->improvementsAndBettermentsLimit : $hiscoxOptions->building_coverage_limit;
        $hiscox->coverageLimits->contents = $hiscoxOptions->contents_coverage_limit;
        $hiscox->coverageLimits->otherStructures = $hiscoxOptions->other_structures_coverage_limit;
        $hiscox->coverageLimits->lossOfUse = $hiscoxOptions->loss_of_use_coverage_limit;

        $this->hiscoxQuoteService->updateQuoteWithHiscox($message["floodQuoteId"], $hiscox);
    }

    private function getMetaValue($floodQuoteMetas, $meta_key, $default = '')
    {
        foreach ($floodQuoteMetas as $meta) {
            if ($meta->meta_key === $meta_key) {
                if ($meta->meta_value == "" && $default != "")
                    return $default;
                else
                    return $meta->meta_value;
            }
        }
        return $default;
    }

    private function bindHiscox($policyType, $floodQuote)
    {
        $floodQuoteMetas = $this->floodQuoteService->getFloodQuoteMetas($floodQuote->flood_quote_id);
        $hiscoxID = $this->getMetaValue($floodQuoteMetas, "hiscoxID");
        $policyNumber = $this->getMetaValue($floodQuoteMetas, "policyNumber");
        $boundFinalPremium = (float)$this->getMetaValue($floodQuoteMetas, "boundFinalPremium", 0);
        $selectedPolicyType = $this->getMetaValue($floodQuoteMetas, "selectedPolicyType");
        $isPerson = $floodQuote->entity_type == 0;
        $covDLossUse = (float)$this->getMetaValue($floodQuoteMetas, "covDLossUse", 0);
        $hiscoxQuotedDwellCov = (int)$this->getMetaValue($floodQuoteMetas, "hiscoxQuotedDwellCov", 0);
        $hiscoxQuotedPersPropCov = (int)$this->getMetaValue($floodQuoteMetas, "hiscoxQuotedPersPropCov", 0);
        $hiscoxQuotedDeductible = (int)$this->getMetaValue($floodQuoteMetas, "hiscoxQuotedDeductible", 0);
        $isRented = $this->getMetaValue($floodQuoteMetas, "isRented", 0) == "1";
        $covABuilding = (int)$this->getMetaValue($floodQuoteMetas, "covABuilding", 0);
        $covCContent = (int)$this->getMetaValue($floodQuoteMetas, "covCContent", 0);
        $prevHiscoxBoundID = $this->getMetaValue($floodQuoteMetas, "prevHiscoxBoundID");
        $endorseDate = $this->getMetaValue($floodQuoteMetas, "endorseDate");

        switch ($policyType) {
            case "CAN":
                $payload = [
                    "hiscoxId" => $prevHiscoxBoundID,
                    "cancellationDate" => $endorseDate,
                    "premiumCalculation" => "ShortRate",
                    "priorTerm" => true
                ];

                $hiscox = $this->hixcoxAPI->cancel($payload);
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

                    $cancelReturnPremium = $hiscoxResponse->response->returnPremium;

                    $hiscoxMessage = new \stdClass();
                    $hiscoxMessage->boundDate = $hiscoxResponse->response->cancellationDate;;
                    $hiscoxMessage->boundReference = $hiscoxResponse->response->hiscoxId;
                    $hiscoxMessage->hiscoxIssuedDate = $hiscoxResponse->response->cancellationDate;
                    $hiscoxMessage->boundHiscoxID = $hiscoxResponse->response->hiscoxId;

                    $this->hiscoxQuoteService->bindCancelHiscox($floodQuote->flood_quote_id, $hiscoxMessage);
                }

                break;

            default:
                if ($policyType == 'END') {
                    $payload = [
                        "hiscoxId" => $hiscoxID,
                        "bindingReference" => $policyNumber,
                        "chargedPremium" => $boundFinalPremium,
                    ];
                } else {
                    $payload = [
                        "hiscoxId" => $hiscoxID,
                        "policyType" => ($selectedPolicyType == "primary") ? "Primary" : "Excess",
                        "bindingReference" => $policyNumber,
                        "effectiveDate" => $floodQuote->effectivity_date,
                        "chargedPremium" => $boundFinalPremium,
                    ];
                }

                if ($policyType == 'REN') {
                    $payload["effectiveDate"] = null;
                }

                if ($isPerson) {
                    $payload["namedInsured"] = $floodQuote->first_name . " " . $floodQuote->last_name;
                    $payload["residential"] = [
                        "includeLossOfUse" => ($covDLossUse > 0),
                        "includeContents" => true,
                        "buildingLimit" => $hiscoxQuotedDwellCov,
                        "contentsLimit" => $hiscoxQuotedPersPropCov,
                        "deductible" => $hiscoxQuotedDeductible,
                    ];
                } else {
                    $payload["namedInsured"] = $floodQuote->company_name;

                    $payload["commercial"] = [
                        "includeBusinessIncomeAndExtraExpense" => true,
                        "includeContents" => true,
                        "deductible" => $hiscoxQuotedDeductible,
                    ];

                    if ($isRented) {
                        $payload["commercial"]["tenanted"] =  [
                            "improvementsAndBettermentsLimit" => $covABuilding,
                            "contentsLimit" => $covCContent
                        ];
                    } else {
                        $payload["commercial"]["owned"] = [
                            "buildingLimit" => $covABuilding,
                            "contentsLimit" => $covCContent
                        ];
                    }
                }

                if ($policyType == 'END') {
                    $hiscox = $this->hixcoxAPI->bindEndorse($payload);
                } else {
                    $hiscox = $this->hixcoxAPI->bind($payload);
                }

                $hiscoxResponse = $hiscox['response'];

                $errors = $hiscoxResponse->messages->errors;
                $validation = $hiscoxResponse->messages->validation;

                if (count($errors) || count($validation)) {
                    $text = "";

                    if (count($errors))
                        $text .= "Errors: " . implode("; ", $errors);

                    if (count($validation))
                        $text .= "Validations: " . implode("; ", $validation);

                    throw new Exception($text);
                } else {
                    $hiscoxMessage = new \stdClass();
                    $hiscoxMessage->boundDate = $hiscoxResponse->response->effectiveDate;
                    $hiscoxMessage->boundReference = $hiscoxResponse->response->bindingReference;
                    $hiscoxMessage->boundHiscoxID = $hiscoxID;

                    $this->hiscoxQuoteService->bindQuoteWithHiscox($floodQuote->flood_quote_id, $hiscoxMessage);
                }

                break;
        }

        return $hiscox['response'];
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

        $this->upsertHiscoxQuote([
            "hiscoxID" => $hiscoxID,
            "flood_quote_id" => $id,
            "client_id" => $data['floodQuote']->client_id,
            "quoteExpirationDate" => null,
            "quoteRequestedDate" => $hiscoxFloodQuote->response->quoteRequestDate,
            "selectedPolicyType" => $hiscoxSelectedPolicyType,
            "selectedDeductible" => $hiscoxSelectedDeductible,
            "selectedPolicyIndex" => $hiscoxSelectedOptionIndex,
            "rawQuotes" => json_encode($hiscoxFloodQuote, JSON_PRETTY_PRINT),
        ]);

        $this->hiscoxQuoteService->addHiscoxId($id, $hiscoxID);

        $message = new \stdClass();
        $message->flood_quote_id = $id;
        $message->selectedPolicyType = $hiscoxSelectedPolicyType;
        $message->selectedDeductible = $hiscoxSelectedDeductible;
        $message->selectedPolicyIndex = $hiscoxSelectedOptionIndex;
        $this->hiscoxQuoteService->updateSelectedHiscoxQuote($message);

        if ($hiscoxSelectedDeductible != -1) {
            $this->updateQuoteWithHiscox($selectedOption['options'], [
                "hiscoxID" => $hiscoxID,
                "selectedPolicyType" => $hiscoxSelectedPolicyType,
                "selectedDeductible" => (int)$hiscoxSelectedDeductible,
                "selectedOptionIndex" => (int)$hiscoxSelectedOptionIndex,
                "floodQuoteId" => $id,
                "isRented" => $isRented,
            ]);
        }

        return view('Hiscox/link_view', ['data' => $data]);
    }

    public function create($id = null)
    {
        helper('form');
        $data['title'] = "Start Hiscox Quote";
        $data['floodQuote'] = $this->floodQuoteService->findOne($id);
        $data['hiscoxFloodQuote'] = null;
        $hiscoxID = null;

        if (!$data['floodQuote']) {
            return redirect()->to('/flood_quotes')->with('error', "Flood Quote not found.");
        }

        $post = $this->request->getPost();
        $hiscoxID = $post['hiscoxID'] ?? "";
        $hiscoxID = trim($hiscoxID);

        $floodQuote = $data['floodQuote'];
        $floodQuoteMetas = $this->floodQuoteService->getFloodQuoteMetas($id);

        $hiscoxID = $this->getMetaValue($floodQuoteMetas, "hiscoxID", $hiscoxID);
        $propertyAddress = $this->getMetaValue($floodQuoteMetas, "propertyAddress");
        $propertyCity = $this->getMetaValue($floodQuoteMetas, "propertyCity");
        $propertyState = $this->getMetaValue($floodQuoteMetas, "propertyState");
        $propertyZip = $this->getMetaValue($floodQuoteMetas, "propertyZip");

        $quoteRequestDate = '';
        $quoteExpiryDate = '';
        $productType = '';

        $validations = [];
        $underwriterDecisions = [];
        $errors = [];

        $primaryOptions = [];
        $excessOptions = [];

        $hiscoxSelectedOptionIndex = 0;

        $address = $propertyAddress . ' ' . $propertyCity . ' ' . $propertyState . $propertyZip;

        $contentsCostValueType = '';
        $purpose = '';
        $yearBuilt = 0;
        $constructionType = '';
        $numberOfStories = 0;
        $squareFootage = 0;
        $elevationHeight = 0;
        $foundationType = '';
        $basementType = '';
        $buildingOverWaterType = '';
        $policyType = '';
        $yearOfLastLoss = '';
        $lastLossValue = '';
        $isRented = $this->getMetaValue($floodQuoteMetas, "isRented", 0) == "1";
        $isPerson = $floodQuote->entity_type == 0;
        $construction_type = $this->getMetaValue($floodQuoteMetas, "construction_type", "0");
        $commercial_occupancy = $this->getMetaValue($floodQuoteMetas, "commercial_occupancy", "0");
        $isPrimaryResidence = $this->getMetaValue($floodQuoteMetas, "isPrimaryResidence", "0");
        $other_occupancy = $this->getMetaValue($floodQuoteMetas, "other_occupancy", "0");
        $occupancyType = HiscoxApiV2::getHiscoxOccupancyType($isPerson, $commercial_occupancy, $isPrimaryResidence, $other_occupancy);

        $construction = $this->constructionService->findOne($construction_type);
        $constructionType = ($construction) ? $construction->hiscox_name : "";

        if ($hiscoxID == "") {
            $hasOpprc = $this->getMetaValue($floodQuoteMetas, "hasOpprc", 0);
            $flood_foundation = $this->getMetaValue($floodQuoteMetas, "flood_foundation", "0");
            $isEnclosureFinished = $this->getMetaValue($floodQuoteMetas, "isEnclosureFinished", "0");
            $basement_finished = $this->getMetaValue($floodQuoteMetas, "basement_finished", "0");
            $garage_attached = $this->getMetaValue($floodQuoteMetas, "garage_attached", "0");
            $over_water = $this->getMetaValue($floodQuoteMetas, "over_water", "0");
            $latitude = (float)$this->getMetaValue($floodQuoteMetas, "latitude", "0");
            $longitude = (float)$this->getMetaValue($floodQuoteMetas, "longitude", "0");
            $lfe = (float)$this->getMetaValue($floodQuoteMetas, "lfe", 0);
            $hag = (float)$this->getMetaValue($floodQuoteMetas, "hag", 0);
            $buildingReplacementCost = (int)$this->getMetaValue($floodQuoteMetas, "buildingReplacementCost", 0);
            $contentReplacementCost = (int)$this->getMetaValue($floodQuoteMetas, "contentReplacementCost", 0);
            $covABuilding = (int)$this->getMetaValue($floodQuoteMetas, "covABuilding", 0);
            $covCContent = (int)$this->getMetaValue($floodQuoteMetas, "covCContent", 0);
            $covDLoss = (int)$this->getMetaValue($floodQuoteMetas, "covDLoss", 0);
            $yearLastLoss = $this->getMetaValue($floodQuoteMetas, "yearLastLoss");
            $lastLossValue = $this->getMetaValue($floodQuoteMetas, "lastLossValue", 0);

            $requestData = [
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
                "productType" => ($isPerson) ? "Residential" : "Commercial",
                "location" => [
                    "addressLine1" => $propertyAddress,
                    "county" => $propertyCity,
                    "stateCode" => $propertyState,
                    "zip" => $propertyZip,
                    "latitude" => $latitude,
                    "longitude" => $longitude,
                ],
            ];

            if ($isPerson) {
                $requestData["residential"] = [
                    "occupancyType" => $occupancyType,
                    "constructionType" => ($construction) ? $construction->hiscox_name : "",
                    "replacementCostValues" => ["building" => $buildingReplacementCost, "contents" => $contentReplacementCost],
                    "limits" => [
                        ["building" => $covABuilding, "contents" => $covCContent],
                        ["building" => $buildingReplacementCost, "contents" => $contentReplacementCost]
                    ],
                ];
            } else {
                $requestData["commercial"] = [
                    "occupancyType" => $occupancyType,
                    "constructionType" => ($construction) ? $construction->hiscox_name : ""
                ];

                if ($isRented) {
                    $requestData["commercial"]["tenanted"] = [
                        "replacementCostValues" => ["improvementsAndBetterments" => $buildingReplacementCost, "contents" => $contentReplacementCost],
                        "limits" => [
                            ["improvementsAndBetterments" => $covABuilding, "contents" => $covCContent],
                            ["improvementsAndBetterments" => $buildingReplacementCost, "contents" => $contentReplacementCost]
                        ],
                        "businessIncomeAndExtraExpenseAnnualValue" => $covDLoss
                    ];
                } else {
                    $requestData["commercial"]["owned"] = [
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

            $hiscox = $this->hixcoxAPI->newRequest($requestData);
            $hiscoxResponse = $hiscox['response'];
            $hiscoxQuote = $hiscoxResponse;

            $validations = $hiscoxQuote->messages->validation;
            $underwriterDecisions = $hiscoxQuote->messages->underwriterDecisions;
            $errors = $hiscoxQuote->messages->errors;

            if (isset($hiscoxQuote->response)) {
                $hiscoxID = $hiscoxQuote->response->hiscoxId;
                $quoteRequestDate = $hiscoxQuote->response->quoteRequestDate;
                $quoteExpiryDate = $hiscoxQuote->response->quoteExpiryDate;
                $productType = $hiscoxQuote->request->productType;

                $productResponseRequest = HiscoxApiV2::createProductResponseRequest($hiscoxQuote, $data['floodQuote'], $isRented);

                $hiscoxProductResponse = $productResponseRequest["hiscoxProductResponse"];
                $purpose = $productResponseRequest["purpose"];

                $primaryOptions = $hiscoxProductResponse->primary;
                $excessOptions = $hiscoxProductResponse->excess;

                $contentsCostValueType = $hiscoxQuote->request->contentsCostValueType;
                $yearBuilt = $hiscoxQuote->request->yearBuilt;
                $numberOfStories = $hiscoxQuote->request->numberOfStories;
                $squareFootage = $hiscoxQuote->request->squareFootage;
                $elevationHeight = $hiscoxQuote->request->elevationHeight;
                $foundationType = $hiscoxQuote->request->foundation->foundationType;
                $basementType = $hiscoxQuote->request->basementType;
                $buildingOverWaterType = $hiscoxQuote->request->buildingOverWaterType;
                $policyType = '';

                if (isset($hiscoxQuote->request->priorLosses) && count($hiscoxQuote->request->priorLosses) > 0) {
                    $yearOfLastLoss = $hiscoxQuote->request->priorLosses[0]->year;
                    $lastLossValue = $hiscoxQuote->request->priorLosses[0]->value;
                }

                $this->upsertHiscoxQuote([
                    "hiscoxID" => $hiscoxID,
                    "flood_quote_id" => $id,
                    "client_id" => $data['floodQuote']->client_id,
                    "quoteExpirationDate" => $quoteExpiryDate,
                    "quoteRequestedDate" => $hiscoxQuote->response->quoteRequestDate,
                    "selectedPolicyType" => "-1",
                    "selectedDeductible" => -1,
                    "selectedPolicyIndex" => -1,
                    "rawQuotes" => json_encode($hiscoxQuote, JSON_PRETTY_PRINT),
                ]);

                $this->hiscoxQuoteService->addHiscoxId($id, $hiscoxID);
            }
        } else {
            $getHiscoxQuote = $this->hiscoxQuoteService->findHiscoxQuote($id, $hiscoxID);

            if ($getHiscoxQuote) {
                $rawQuotes = $getHiscoxQuote->raw_quotes;
                $hiscoxQuote = json_decode($rawQuotes);
            } else {
                $getHiscoxQuote = $this->hixcoxAPI->getRequest($hiscoxID);
                $rawQuotes = $getHiscoxQuote["raw"];
            }

            $hiscoxQuote = json_decode($rawQuotes);

            if (isset($hiscoxQuote->response)) {
                $quoteRequestDate = $hiscoxQuote->response->quoteRequestDate;
                $quoteExpiryDate = $hiscoxQuote->response->quoteExpiryDate;
                $productType = $hiscoxQuote->request->productType;

                $postPolicyType = (isset($_POST['policyType'])) ? $_POST['policyType'] : -1;
                $postDeductible = (isset($_POST['deductible'])) ? $_POST['deductible'] : -1;
                $postPolicyIndex =  (isset($_POST['policyIndex'])) ? $_POST['policyIndex'] : -1;

                $productResponseRequest = HiscoxApiV2::createProductResponseRequest($hiscoxQuote, $data['floodQuote'], $isRented);

                $hiscoxProductResponse = $productResponseRequest["hiscoxProductResponse"];
                $purpose = $productResponseRequest["purpose"];

                $primaryOptions = $hiscoxProductResponse->primary;
                $excessOptions = $hiscoxProductResponse->excess;

                $contentsCostValueType = $hiscoxQuote->request->contentsCostValueType;
                $yearBuilt = $hiscoxQuote->request->yearBuilt;
                $numberOfStories = $hiscoxQuote->request->numberOfStories;
                $squareFootage = $hiscoxQuote->request->squareFootage;
                $elevationHeight = $hiscoxQuote->request->elevationHeight;
                $foundationType = $hiscoxQuote->request->foundation->foundationType;
                $basementType = $hiscoxQuote->request->basementType;
                $buildingOverWaterType = $hiscoxQuote->request->buildingOverWaterType;
                $policyType = $postPolicyType;

                if (isset($hiscoxQuote->request->priorLosses) && count($hiscoxQuote->request->priorLosses) > 0) {
                    $yearOfLastLoss = $hiscoxQuote->request->priorLosses[0]->year;
                    $lastLossValue = $hiscoxQuote->request->priorLosses[0]->value;
                }

                $hiscoxSelectedOption = HiscoxApiV2::getHiscoxSelectedOption($postPolicyType, $postPolicyIndex, $postDeductible, $primaryOptions, $excessOptions);
                $hiscoxSelectedOptionIndex = $hiscoxSelectedOption['index'];
                $hiscoxOptions = $hiscoxSelectedOption['options'];

                $this->upsertHiscoxQuote([
                    "hiscoxID" => $hiscoxID,
                    "flood_quote_id" => $id,
                    "client_id" => $data['floodQuote']->client_id,
                    "quoteExpirationDate" => null,
                    "quoteRequestedDate" => $hiscoxQuote->response->quoteRequestDate,
                    "selectedPolicyType" => $postPolicyType,
                    "selectedDeductible" => (int)$postDeductible,
                    "selectedPolicyIndex" => (int)$postPolicyIndex,
                    "rawQuotes" => json_encode($hiscoxQuote, JSON_PRETTY_PRINT),
                ]);

                if ($postDeductible != -1) {
                    $this->updateQuoteWithHiscox($hiscoxOptions, [
                        "hiscoxID" => $hiscoxID,
                        "selectedPolicyType" => $postPolicyType,
                        "selectedDeductible" => (int)$postDeductible,
                        "selectedOptionIndex" => (int)$postPolicyIndex,
                        "floodQuoteId" => $id,
                        "isRented" => $isRented,
                    ]);
                }
            }
        }

        $data["quoteRequestDate"] = $quoteRequestDate;
        $data["hiscoxID"] = $hiscoxID;
        $data["quoteExpiryDate"] = $quoteExpiryDate;
        $data["address"] = $address;
        $data["contentsCostValueType"] = $contentsCostValueType;
        $data["occupancyType"] = $occupancyType;
        $data["purpose"] = $purpose;
        $data["yearBuilt"] = $yearBuilt;
        $data["constructionType"] = $constructionType;
        $data["numberOfStories"] = $numberOfStories;
        $data["squareFootage"] = $squareFootage;
        $data["elevationHeight"] = $elevationHeight;
        $data["foundationType"] = $foundationType;
        $data["basementType"] = $basementType;
        $data["buildingOverWaterType"] = $buildingOverWaterType;
        $data["policyType"] = $policyType;
        $data["yearOfLastLoss"] = $yearOfLastLoss;
        $data["lastLossValue"] = $lastLossValue;
        $data["isRented"] = $isRented;

        $data["validations"] = $validations;
        $data["underwriterDecisions"] = $underwriterDecisions;
        $data["errors"] = $errors;

        $data["primaryOptions"] = $primaryOptions;
        $data["excessOptions"] = $excessOptions;

        $data["hiscoxSelectedOptionIndex"] = $hiscoxSelectedOptionIndex;

        return view('Hiscox/create_view', ['data' => $data]);
    }

    public function select($id = null)
    {
        helper('form');
        $data['title'] = "Select Hiscox Quote";
        $data['floodQuote'] = $this->floodQuoteService->findOne($id);
        $data['hiscoxFloodQuote'] = null;

        if (!$data['floodQuote']) {
            return redirect()->to('/flood_quotes')->with('error', "Flood Quote not found.");
        }

        $floodQuote = $data['floodQuote'];
        $floodQuoteMetas = $this->floodQuoteService->getFloodQuoteMetas($id);
        $hiscoxID = $this->getMetaValue($floodQuoteMetas, "hiscoxID");
        $isRented = $this->getMetaValue($floodQuoteMetas, "isRented", 0) == "1";
        $policyType = $this->getMetaValue($floodQuoteMetas, "policyType");

        $getHiscoxQuote = $this->hiscoxQuoteService->findHiscoxQuote($id, $hiscoxID);

        if ($getHiscoxQuote) {
            $rawQuotes = $getHiscoxQuote->raw_quotes;
            $hiscoxQuote = json_decode($rawQuotes);
        } else {
            return redirect()->to('/flood_quotes')->with('error', "Flood Quote has no existing Hiscox Quotes.");
        }

        $quoteRequestDate = $hiscoxQuote->response->quoteRequestDate;
        $quoteExpiryDate = isset($hiscoxQuote->response->quoteExpiryDate) ? $hiscoxQuote->response->quoteExpiryDate : "";

        $selectedPolicyType = $getHiscoxQuote->selected_policy_type;
        $selectedDeductible = $getHiscoxQuote->selected_deductible;
        $selectedPolicyIndex = $getHiscoxQuote->selected_policy_index;

        $validations = isset($hiscoxQuote->messages->validation) ? $hiscoxQuote->messages->validation : [];
        $underwriterDecisions = isset($hiscoxQuote->messages->underwriterDecisions) ? $hiscoxQuote->messages->underwriterDecisions : [];
        $errors = isset($hiscoxQuote->messages->errors) ? $hiscoxQuote->messages->errors : [];

        $productResponseRequest = HiscoxApiV2::createProductResponseRequest($hiscoxQuote, $floodQuote, $isRented);

        $hiscoxProductResponse = $productResponseRequest["hiscoxProductResponse"];
        $purpose = $productResponseRequest["purpose"];

        $primaryOptions = $hiscoxProductResponse->primary;
        $excessOptions = $hiscoxProductResponse->excess;

        $yearOfLastLoss = '';
        $lastLossValue = '';

        $contentsCostValueType = $hiscoxQuote->request->contentsCostValueType;
        $yearBuilt = $hiscoxQuote->request->yearBuilt;
        $numberOfStories = $hiscoxQuote->request->numberOfStories;
        $squareFootage = $hiscoxQuote->request->squareFootage;
        $elevationHeight = $hiscoxQuote->request->elevationHeight;
        $foundationType = $hiscoxQuote->request->foundation->foundationType;
        $basementType = $hiscoxQuote->request->basementType;
        $buildingOverWaterType = $hiscoxQuote->request->buildingOverWaterType;

        if (isset($hiscoxQuote->request->priorLosses) && count($hiscoxQuote->request->priorLosses) > 0) {
            $yearOfLastLoss = $hiscoxQuote->request->priorLosses[0]->year;
            $lastLossValue = $hiscoxQuote->request->priorLosses[0]->value;
        }

        $isEndorsement = $policyType == "END";

        if ($this->request->is('post')) {
            $post = $this->request->getPost();

            $postPolicyType = $post['policyType'] ?? "";
            $postDeductible =  $post['deductible'] ?? "";
            $postPolicyIndex = $post['policyIndex'] ?? "";

            $selectedPolicyType = $postPolicyType;
            $selectedDeductible = $postDeductible;
            $selectedPolicyIndex = $postPolicyIndex;

            $hiscoxSelectedOption = HiscoxApiV2::getHiscoxSelectedOption($postPolicyType, $postPolicyIndex, $postDeductible, $primaryOptions, $excessOptions, $isEndorsement);
            $hiscoxSelectedOptionIndex = $hiscoxSelectedOption['index'];
            $hiscoxOptions = $hiscoxSelectedOption['options'];

            $this->upsertHiscoxQuote([
                "hiscoxID" => $hiscoxID,
                "flood_quote_id" => $id,
                "client_id" => $floodQuote->client_id,
                "quoteExpirationDate" => $quoteExpiryDate,
                "quoteRequestedDate" => $quoteRequestDate,
                "selectedPolicyType" => $postPolicyType,
                "selectedDeductible" => (int)$postDeductible,
                "selectedPolicyIndex" => (int)$postPolicyIndex,
                "rawQuotes" => json_encode($hiscoxQuote, JSON_PRETTY_PRINT),
            ]);

            $this->updateQuoteWithHiscox($hiscoxOptions, [
                "hiscoxID" => $hiscoxID,
                "selectedPolicyType" => $postPolicyType,
                "selectedDeductible" => (int)$postDeductible,
                "selectedOptionIndex" => (int)$postPolicyIndex,
                "floodQuoteId" => $id,
                "isRented" => $isRented,
            ], $isEndorsement);
        }

        $hiscoxSelectedOption = HiscoxApiV2::getHiscoxSelectedOption($selectedPolicyType, $selectedPolicyIndex, $selectedDeductible, $primaryOptions, $excessOptions);
        $hiscoxSelectedOptionIndex = $hiscoxSelectedOption['index'];
        $hiscoxOptions = $hiscoxSelectedOption['options'];

        $propertyAddress = $this->getMetaValue($floodQuoteMetas, "propertyAddress");
        $propertyCity = $this->getMetaValue($floodQuoteMetas, "propertyCity");
        $propertyState = $this->getMetaValue($floodQuoteMetas, "propertyState");
        $propertyZip = $this->getMetaValue($floodQuoteMetas, "propertyZip");

        $address = $propertyAddress . ' ' . $propertyCity . ' ' . $propertyState . $propertyZip;

        $isRented = $this->getMetaValue($floodQuoteMetas, "isRented", 0) == "1";
        $isPerson = $floodQuote->entity_type == 0;
        $construction_type = $this->getMetaValue($floodQuoteMetas, "construction_type", "0");
        $commercial_occupancy = $this->getMetaValue($floodQuoteMetas, "commercial_occupancy", "0");
        $isPrimaryResidence = $this->getMetaValue($floodQuoteMetas, "isPrimaryResidence", "0");
        $other_occupancy = $this->getMetaValue($floodQuoteMetas, "other_occupancy", "0");
        $occupancyType = HiscoxApiV2::getHiscoxOccupancyType($isPerson, $commercial_occupancy, $isPrimaryResidence, $other_occupancy);

        $construction = $this->constructionService->findOne($construction_type);
        $constructionType = ($construction) ? $construction->hiscox_name : "";

        $data["quoteRequestDate"] = $quoteRequestDate;
        $data["hiscoxID"] = $hiscoxID;
        $data["quoteExpiryDate"] = $quoteExpiryDate;
        $data["address"] = $address;
        $data["contentsCostValueType"] = $contentsCostValueType;
        $data["occupancyType"] = $occupancyType;
        $data["purpose"] = $purpose;
        $data["yearBuilt"] = $yearBuilt;
        $data["constructionType"] = $constructionType;
        $data["numberOfStories"] = $numberOfStories;
        $data["squareFootage"] = $squareFootage;
        $data["elevationHeight"] = $elevationHeight;
        $data["foundationType"] = $foundationType;
        $data["basementType"] = $basementType;
        $data["buildingOverWaterType"] = $buildingOverWaterType;
        $data["policyType"] = $selectedPolicyType;
        $data["yearOfLastLoss"] = $yearOfLastLoss;
        $data["lastLossValue"] = $lastLossValue;
        $data["isRented"] = $isRented;

        $data["validations"] = $validations;
        $data["underwriterDecisions"] = $underwriterDecisions;
        $data["errors"] = $errors;

        $data["primaryOptions"] = $primaryOptions;
        $data["excessOptions"] = $excessOptions;

        $data["hiscoxSelectedOptionIndex"] = $hiscoxSelectedOptionIndex;
        $data["isEndorsement"] = $isEndorsement;

        return view('Hiscox/select_view', ['data' => $data]);
    }

    public function requote($id = null)
    {
        helper('form');
        $data['title'] = "Requote Hiscox Quote";
        $data['floodQuote'] = $this->floodQuoteService->findOne($id);
        $data['hiscoxFloodQuote'] = null;

        if (!$data['floodQuote']) {
            return redirect()->to('/flood_quotes')->with('error', "Flood Quote not found.");
        }

        $floodQuote = $data['floodQuote'];
        $floodQuoteMetas = $this->floodQuoteService->getFloodQuoteMetas($id);
        $hiscoxID = $this->getMetaValue($floodQuoteMetas, "hiscoxID");

        if ($hiscoxID == "") {
            return redirect()->to('/flood_quotes')->with('error', "Missing Hiscox ID.");
        }

        $isRented = $this->getMetaValue($floodQuoteMetas, "isRented", 0) == "1";
        $policyType = $this->getMetaValue($floodQuoteMetas, "policyType");

        $propertyAddress = $this->getMetaValue($floodQuoteMetas, "propertyAddress");
        $propertyCity = $this->getMetaValue($floodQuoteMetas, "propertyCity");
        $propertyState = $this->getMetaValue($floodQuoteMetas, "propertyState");
        $propertyZip = $this->getMetaValue($floodQuoteMetas, "propertyZip");

        $quoteRequestDate = '';
        $quoteExpiryDate = '';
        $productType = '';

        $validations = [];
        $underwriterDecisions = [];
        $errors = [];

        $primaryOptions = [];
        $excessOptions = [];

        $hiscoxSelectedOptionIndex = 0;

        $address = $propertyAddress . ' ' . $propertyCity . ' ' . $propertyState . $propertyZip;

        $contentsCostValueType = '';
        $purpose = '';
        $yearBuilt = 0;
        $constructionType = '';
        $numberOfStories = 0;
        $squareFootage = 0;
        $elevationHeight = 0;
        $foundationType = '';
        $basementType = '';
        $buildingOverWaterType = '';
        $policyType = '';
        $yearOfLastLoss = '';
        $lastLossValue = '';
        $isRented = $this->getMetaValue($floodQuoteMetas, "isRented", 0) == "1";
        $isPerson = $floodQuote->entity_type == 0;
        $construction_type = $this->getMetaValue($floodQuoteMetas, "construction_type", "0");
        $commercial_occupancy = $this->getMetaValue($floodQuoteMetas, "commercial_occupancy", "0");
        $isPrimaryResidence = $this->getMetaValue($floodQuoteMetas, "isPrimaryResidence", "0");
        $other_occupancy = $this->getMetaValue($floodQuoteMetas, "other_occupancy", "0");
        $occupancyType = HiscoxApiV2::getHiscoxOccupancyType($isPerson, $commercial_occupancy, $isPrimaryResidence, $other_occupancy);

        $construction = $this->constructionService->findOne($construction_type);
        $constructionType = ($construction) ? $construction->hiscox_name : "";

        if (!$this->request->is('post')) {
            $hasOpprc = $this->getMetaValue($floodQuoteMetas, "hasOpprc", 0);
            $flood_foundation = $this->getMetaValue($floodQuoteMetas, "flood_foundation", "0");
            $isEnclosureFinished = $this->getMetaValue($floodQuoteMetas, "isEnclosureFinished", "0");
            $basement_finished = $this->getMetaValue($floodQuoteMetas, "basement_finished", "0");
            $garage_attached = $this->getMetaValue($floodQuoteMetas, "garage_attached", "0");
            $over_water = $this->getMetaValue($floodQuoteMetas, "over_water", "0");
            $latitude = (float)$this->getMetaValue($floodQuoteMetas, "latitude", "0");
            $longitude = (float)$this->getMetaValue($floodQuoteMetas, "longitude", "0");
            $lfe = (float)$this->getMetaValue($floodQuoteMetas, "lfe", 0);
            $hag = (float)$this->getMetaValue($floodQuoteMetas, "hag", 0);
            $buildingReplacementCost = (int)$this->getMetaValue($floodQuoteMetas, "buildingReplacementCost", 0);
            $contentReplacementCost = (int)$this->getMetaValue($floodQuoteMetas, "contentReplacementCost", 0);
            $covABuilding = (int)$this->getMetaValue($floodQuoteMetas, "covABuilding", 0);
            $covCContent = (int)$this->getMetaValue($floodQuoteMetas, "covCContent", 0);
            $covDLoss = (int)$this->getMetaValue($floodQuoteMetas, "covDLoss", 0);
            $yearLastLoss = $this->getMetaValue($floodQuoteMetas, "yearLastLoss");
            $lastLossValue = $this->getMetaValue($floodQuoteMetas, "lastLossValue", 0);

            $requestData = [
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
                "productType" => ($isPerson) ? "Residential" : "Commercial",
                "location" => [
                    "addressLine1" => $propertyAddress,
                    "county" => $propertyCity,
                    "stateCode" => $propertyState,
                    "zip" => $propertyZip,
                    "latitude" => $latitude,
                    "longitude" => $longitude,
                ],
                "hiscoxId" => $hiscoxID
            ];

            if ($isPerson) {
                $requestData["residential"] = [
                    "occupancyType" => $occupancyType,
                    "constructionType" => ($construction) ? $construction->hiscox_name : "",
                    "replacementCostValues" => ["building" => $buildingReplacementCost, "contents" => $contentReplacementCost],
                    "limits" => [
                        ["building" => $covABuilding, "contents" => $covCContent],
                        ["building" => $buildingReplacementCost, "contents" => $contentReplacementCost]
                    ],
                ];
            } else {
                $requestData["commercial"] = [
                    "occupancyType" => $occupancyType,
                    "constructionType" => ($construction) ? $construction->hiscox_name : ""
                ];

                if ($isRented) {
                    $requestData["commercial"]["tenanted"] = [
                        "replacementCostValues" => ["improvementsAndBetterments" => $buildingReplacementCost, "contents" => $contentReplacementCost],
                        "limits" => [
                            ["improvementsAndBetterments" => $covABuilding, "contents" => $covCContent],
                            ["improvementsAndBetterments" => $buildingReplacementCost, "contents" => $contentReplacementCost]
                        ],
                        "businessIncomeAndExtraExpenseAnnualValue" => $covDLoss
                    ];
                } else {
                    $requestData["commercial"]["owned"] = [
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

            $hiscox = $this->hixcoxAPI->update($requestData);
            $hiscoxResponse = $hiscox['response'];
            $hiscoxQuote = $hiscoxResponse;

            $validations = $hiscoxQuote->messages->validation;
            $underwriterDecisions = $hiscoxQuote->messages->underwriterDecisions;
            $errors = $hiscoxQuote->messages->errors;

            if (isset($hiscoxQuote->response)) {
                $hiscoxID = $hiscoxQuote->response->hiscoxId;
                $quoteRequestDate = $hiscoxQuote->response->quoteRequestDate;
                $quoteExpiryDate = $hiscoxQuote->response->quoteExpiryDate;

                $productResponseRequest = HiscoxApiV2::createProductResponseRequest($hiscoxQuote, $data['floodQuote'], $isRented);

                $hiscoxProductResponse = $productResponseRequest["hiscoxProductResponse"];
                $purpose = $productResponseRequest["purpose"];

                $primaryOptions = $hiscoxProductResponse->primary;
                $excessOptions = $hiscoxProductResponse->excess;

                $contentsCostValueType = $hiscoxQuote->request->contentsCostValueType;
                $yearBuilt = $hiscoxQuote->request->yearBuilt;
                $numberOfStories = $hiscoxQuote->request->numberOfStories;
                $squareFootage = $hiscoxQuote->request->squareFootage;
                $elevationHeight = $hiscoxQuote->request->elevationHeight;
                $foundationType = $hiscoxQuote->request->foundation->foundationType;
                $basementType = $hiscoxQuote->request->basementType;
                $buildingOverWaterType = $hiscoxQuote->request->buildingOverWaterType;

                if (isset($hiscoxQuote->request->priorLosses) && count($hiscoxQuote->request->priorLosses) > 0) {
                    $yearOfLastLoss = $hiscoxQuote->request->priorLosses[0]->year;
                    $lastLossValue = $hiscoxQuote->request->priorLosses[0]->value;
                }

                $this->upsertHiscoxQuote([
                    "hiscoxID" => $hiscoxID,
                    "flood_quote_id" => $id,
                    "client_id" => $data['floodQuote']->client_id,
                    "quoteExpirationDate" => $quoteExpiryDate,
                    "quoteRequestedDate" => $hiscoxQuote->response->quoteRequestDate,
                    "selectedPolicyType" => "-1",
                    "selectedDeductible" => -1,
                    "selectedPolicyIndex" => -1,
                    "rawQuotes" => json_encode($hiscoxQuote, JSON_PRETTY_PRINT),
                ]);

                $message = new \stdClass();
                $message->flood_quote_id = $id;
                $message->selectedPolicyType = "-1";
                $message->selectedDeductible = -1;
                $message->selectedPolicyIndex = -1;
                $this->hiscoxQuoteService->updateSelectedHiscoxQuote($message);
            }
        } else {
            $getHiscoxQuote = $this->hiscoxQuoteService->findHiscoxQuote($id, $hiscoxID);

            if ($getHiscoxQuote) {
                $rawQuotes = $getHiscoxQuote->raw_quotes;
                $hiscoxQuote = json_decode($rawQuotes);
            } else {
                $getHiscoxQuote = $this->hixcoxAPI->getRequest($hiscoxID);
                $rawQuotes = $getHiscoxQuote["raw"];
            }

            $hiscoxQuote = json_decode($rawQuotes);

            $post = $this->request->getPost();

            $postPolicyType = $post['policyType'] ?? "";
            $postDeductible =  $post['deductible'] ?? "";
            $postPolicyIndex = $post['policyIndex'] ?? "";

            $isEndorsement = $policyType == "END";

            $quoteRequestDate = $hiscoxQuote->response->quoteRequestDate;
            $quoteExpiryDate = $hiscoxQuote->response->quoteExpiryDate;

            $productResponseRequest = HiscoxApiV2::createProductResponseRequest($hiscoxQuote, $data['floodQuote'], $isRented);

            $hiscoxProductResponse = $productResponseRequest["hiscoxProductResponse"];
            $purpose = $productResponseRequest["purpose"];

            $primaryOptions = $hiscoxProductResponse->primary;
            $excessOptions = $hiscoxProductResponse->excess;

            $hiscoxSelectedOption = HiscoxApiV2::getHiscoxSelectedOption($postPolicyType, $postPolicyIndex, $postDeductible, $primaryOptions, $excessOptions, $isEndorsement);
            $hiscoxSelectedOptionIndex = $hiscoxSelectedOption['index'];
            $hiscoxOptions = $hiscoxSelectedOption['options'];

            $contentsCostValueType = $hiscoxQuote->request->contentsCostValueType;
            $yearBuilt = $hiscoxQuote->request->yearBuilt;
            $numberOfStories = $hiscoxQuote->request->numberOfStories;
            $squareFootage = $hiscoxQuote->request->squareFootage;
            $elevationHeight = $hiscoxQuote->request->elevationHeight;
            $foundationType = $hiscoxQuote->request->foundation->foundationType;
            $basementType = $hiscoxQuote->request->basementType;
            $buildingOverWaterType = $hiscoxQuote->request->buildingOverWaterType;
            $policyType = $postPolicyType;

            if (isset($hiscoxQuote->request->priorLosses) && count($hiscoxQuote->request->priorLosses) > 0) {
                $yearOfLastLoss = $hiscoxQuote->request->priorLosses[0]->year;
                $lastLossValue = $hiscoxQuote->request->priorLosses[0]->value;
            }

            $this->upsertHiscoxQuote([
                "hiscoxID" => $hiscoxID,
                "flood_quote_id" => $id,
                "client_id" => $floodQuote->client_id,
                "quoteExpirationDate" => $quoteExpiryDate,
                "quoteRequestedDate" => $quoteRequestDate,
                "selectedPolicyType" => $postPolicyType,
                "selectedDeductible" => (int)$postDeductible,
                "selectedPolicyIndex" => (int)$postPolicyIndex,
                "rawQuotes" => json_encode($hiscoxQuote, JSON_PRETTY_PRINT),
            ]);

            $this->updateQuoteWithHiscox($hiscoxOptions, [
                "hiscoxID" => $hiscoxID,
                "selectedPolicyType" => $postPolicyType,
                "selectedDeductible" => (int)$postDeductible,
                "selectedOptionIndex" => (int)$postPolicyIndex,
                "floodQuoteId" => $id,
                "isRented" => $isRented,
            ], $isEndorsement);
        }

        $data["quoteRequestDate"] = $quoteRequestDate;
        $data["hiscoxID"] = $hiscoxID;
        $data["quoteExpiryDate"] = $quoteExpiryDate;
        $data["address"] = $address;
        $data["contentsCostValueType"] = $contentsCostValueType;
        $data["occupancyType"] = $occupancyType;
        $data["purpose"] = $purpose;
        $data["yearBuilt"] = $yearBuilt;
        $data["constructionType"] = $constructionType;
        $data["numberOfStories"] = $numberOfStories;
        $data["squareFootage"] = $squareFootage;
        $data["elevationHeight"] = $elevationHeight;
        $data["foundationType"] = $foundationType;
        $data["basementType"] = $basementType;
        $data["buildingOverWaterType"] = $buildingOverWaterType;
        $data["policyType"] = $policyType;
        $data["yearOfLastLoss"] = $yearOfLastLoss;
        $data["lastLossValue"] = $lastLossValue;
        $data["isRented"] = $isRented;

        $data["validations"] = $validations;
        $data["underwriterDecisions"] = $underwriterDecisions;
        $data["errors"] = $errors;

        $data["primaryOptions"] = $primaryOptions;
        $data["excessOptions"] = $excessOptions;

        $data["hiscoxSelectedOptionIndex"] = $hiscoxSelectedOptionIndex;

        return view('Hiscox/requote_view', ['data' => $data]);
    }

    public function bind($id = null)
    {
        helper('form');
        $data['title'] = "HISCOX Binding Process";
        $data['floodQuote'] = $this->floodQuoteService->findOne($id);
        $data['hiscoxFloodQuote'] = null;

        if (!$data['floodQuote']) {
            return redirect()->to('/flood_quotes')->with('error', "Flood Quote not found.");
        }

        $floodQuote = $data['floodQuote'];
        $floodQuoteMetas = $this->floodQuoteService->getFloodQuoteMetas($id);
        $hiscoxID = $this->getMetaValue($floodQuoteMetas, "hiscoxID");
        $bind_authority = $this->getMetaValue($floodQuoteMetas, 'bind_authority');

        $bindAuthority = $this->bindAuthorityService->findOne($bind_authority);
        $bindAuthorityText = ($bindAuthority) ? $bindAuthority->reference : "";

        if (strpos($bindAuthorityText, "250") === false) {
            return redirect()->to('/flood_quotes')->with('error', "Invalid Binding Authority. Hiscox Only!");
        }

        $flood_zone = (int)$this->getMetaValue($floodQuoteMetas, "flood_zone", 0);
        $floodZone = $this->floodZoneService->findOne($flood_zone);

        $flood_occupancy = (int)$this->getMetaValue($floodQuoteMetas, "flood_occupancy", 0);
        $floodOccupancy = $this->floodOccupancyService->findOne($flood_occupancy);
        $mle = (int)$this->getMetaValue($floodQuoteMetas, "mle", 0);

        $data["hiscoxQuotedDwellCov"] = $this->getMetaValue($floodQuoteMetas, "hiscoxQuotedDwellCov");
        $data["hiscoxQuotedPersPropCov"] = $this->getMetaValue($floodQuoteMetas, "hiscoxQuotedPersPropCov");
        $data["hiscoxQuotedLossCov"] = $this->getMetaValue($floodQuoteMetas, "hiscoxQuotedLossCov");
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
        $data["hiscoxID"] = $hiscoxID;
        $data["hiscoxQuotedPremium"] = $this->getMetaValue($floodQuoteMetas, "hiscoxQuotedPremium");
        $data["hiscoxPremiumOverride"] = $this->getMetaValue($floodQuoteMetas, "hiscoxPremiumOverride", 0);
        $data["hiscoxQuotedDeductible"] = $this->getMetaValue($floodQuoteMetas, "hiscoxQuotedDeductible", 0);
        $data["additionalPremium"] = $this->getMetaValue($floodQuoteMetas, "additionalPremium", 0);
        $data["proratedDue"] = $this->getMetaValue($floodQuoteMetas, "proratedDue", 0);
        $data["previousPolicyNumber"] = $this->getMetaValue($floodQuoteMetas, "previousPolicyNumber");

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

        // Bind to vandyk before to hiscox
        if ($this->request->is('post')) {
            $inForce = isset($post['inForce']) ? (int)$post['inForce'] : 0;

            $post = $this->request->getPost();
            $message = new \stdClass();
            $message->boundFinalPremium = $post['boundFinalPremium'];
            $message->flood_quote_id = $id;
            $message->boundBasePremium = $post['boundBasePremium'];
            $message->boundLossUseCoverage = $post['boundLossUseCoverage'];
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
                $this->bindHiscox($policyType, $floodQuote);
                return redirect()->to('/flood_quote/choose_sla/' . $id)->with('message', 'Binding was successful.');
            } catch (Exception $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        } else {
            return view('Hiscox/bind_view', ['data' => $data]);
        }
    }

    public function view($id = null)
    {
        helper('form');
        $data['title'] = "View Hiscox Quote";
        $data['floodQuote'] = $this->floodQuoteService->findOne($id);
        $data['hiscoxFloodQuote'] = null;

        if (!$data['floodQuote']) {
            return redirect()->to('/flood_quotes')->with('error', "Flood Quote not found.");
        }

        $floodQuote = $data['floodQuote'];
        $floodQuoteMetas = $this->floodQuoteService->getFloodQuoteMetas($id);
        $hiscoxID = $this->getMetaValue($floodQuoteMetas, "hiscoxID");
        $isRented = $this->getMetaValue($floodQuoteMetas, "isRented", 0) == "1";
        $policyType = $this->getMetaValue($floodQuoteMetas, "policyType");

        $getHiscoxQuote = $this->hiscoxQuoteService->findHiscoxQuote($id, $hiscoxID);

        if ($getHiscoxQuote) {
            $rawQuotes = $getHiscoxQuote->raw_quotes;
            $hiscoxQuote = json_decode($rawQuotes);
        } else {
            return redirect()->to('/flood_quotes')->with('error', "Flood Quote has no existing Hiscox Quotes.");
        }

        $quoteRequestDate = $hiscoxQuote->response->quoteRequestDate;
        $quoteExpiryDate = $hiscoxQuote->response->quoteExpiryDate;

        $selectedPolicyType = $getHiscoxQuote->selected_policy_type;
        $selectedDeductible = $getHiscoxQuote->selected_deductible;
        $selectedPolicyIndex = $getHiscoxQuote->selected_policy_index;

        $validations = $hiscoxQuote->messages->validation;
        $underwriterDecisions = $hiscoxQuote->messages->underwriterDecisions;
        $errors = $hiscoxQuote->messages->errors;

        $productResponseRequest = HiscoxApiV2::createProductResponseRequest($hiscoxQuote, $floodQuote, $isRented);

        $hiscoxProductResponse = $productResponseRequest["hiscoxProductResponse"];
        $purpose = $productResponseRequest["purpose"];

        $primaryOptions = $hiscoxProductResponse->primary;
        $excessOptions = $hiscoxProductResponse->excess;

        $yearOfLastLoss = '';
        $lastLossValue = '';

        $contentsCostValueType = $hiscoxQuote->request->contentsCostValueType;
        $yearBuilt = $hiscoxQuote->request->yearBuilt;
        $numberOfStories = $hiscoxQuote->request->numberOfStories;
        $squareFootage = $hiscoxQuote->request->squareFootage;
        $elevationHeight = $hiscoxQuote->request->elevationHeight;
        $foundationType = $hiscoxQuote->request->foundation->foundationType;
        $basementType = $hiscoxQuote->request->basementType;
        $buildingOverWaterType = $hiscoxQuote->request->buildingOverWaterType;

        if (isset($hiscoxQuote->request->priorLosses) && count($hiscoxQuote->request->priorLosses) > 0) {
            $yearOfLastLoss = $hiscoxQuote->request->priorLosses[0]->year;
            $lastLossValue = $hiscoxQuote->request->priorLosses[0]->value;
        }

        $isEndorsement = $policyType == "END";

        $hiscoxSelectedOption = HiscoxApiV2::getHiscoxSelectedOption($selectedPolicyType, $selectedPolicyIndex, $selectedDeductible, $primaryOptions, $excessOptions, $isEndorsement);
        $hiscoxSelectedOptionIndex = $hiscoxSelectedOption['index'];
        $hiscoxOptions = $hiscoxSelectedOption['options'];

        $propertyAddress = $this->getMetaValue($floodQuoteMetas, "propertyAddress");
        $propertyCity = $this->getMetaValue($floodQuoteMetas, "propertyCity");
        $propertyState = $this->getMetaValue($floodQuoteMetas, "propertyState");
        $propertyZip = $this->getMetaValue($floodQuoteMetas, "propertyZip");

        $address = $propertyAddress . ' ' . $propertyCity . ' ' . $propertyState . $propertyZip;

        $isRented = $this->getMetaValue($floodQuoteMetas, "isRented", 0) == "1";
        $isPerson = $floodQuote->entity_type == 0;
        $construction_type = $this->getMetaValue($floodQuoteMetas, "construction_type", "0");
        $commercial_occupancy = $this->getMetaValue($floodQuoteMetas, "commercial_occupancy", "0");
        $isPrimaryResidence = $this->getMetaValue($floodQuoteMetas, "isPrimaryResidence", "0");
        $other_occupancy = $this->getMetaValue($floodQuoteMetas, "other_occupancy", "0");
        $occupancyType = HiscoxApiV2::getHiscoxOccupancyType($isPerson, $commercial_occupancy, $isPrimaryResidence, $other_occupancy);

        $construction = $this->constructionService->findOne($construction_type);
        $constructionType = ($construction) ? $construction->hiscox_name : "";

        $data["quoteRequestDate"] = $quoteRequestDate;
        $data["hiscoxID"] = $hiscoxID;
        $data["quoteExpiryDate"] = $quoteExpiryDate;
        $data["address"] = $address;
        $data["contentsCostValueType"] = $contentsCostValueType;
        $data["occupancyType"] = $occupancyType;
        $data["purpose"] = $purpose;
        $data["yearBuilt"] = $yearBuilt;
        $data["constructionType"] = $constructionType;
        $data["numberOfStories"] = $numberOfStories;
        $data["squareFootage"] = $squareFootage;
        $data["elevationHeight"] = $elevationHeight;
        $data["foundationType"] = $foundationType;
        $data["basementType"] = $basementType;
        $data["buildingOverWaterType"] = $buildingOverWaterType;
        $data["policyType"] = $selectedPolicyType;
        $data["yearOfLastLoss"] = $yearOfLastLoss;
        $data["lastLossValue"] = $lastLossValue;
        $data["isRented"] = $isRented;

        $data["validations"] = $validations;
        $data["underwriterDecisions"] = $underwriterDecisions;
        $data["errors"] = $errors;

        $data["primaryOptions"] = $primaryOptions;
        $data["excessOptions"] = $excessOptions;

        $data["hiscoxSelectedOptionIndex"] = $hiscoxSelectedOptionIndex;
        $data["isEndorsement"] = $isEndorsement;

        return view('Hiscox/view_view', ['data' => $data]);
    }

    public function cancel_preview($id = null)
    {
        helper('form');
        $data['title'] = "View Hiscox Cancel Preview";
        $data['floodQuote'] = $this->floodQuoteService->findOne($id);
        $data['hiscoxFloodQuote'] = null;

        if (!$data['floodQuote']) {
            return redirect()->to('/flood_quotes')->with('error', "Flood Quote not found.");
        }

        $floodQuote = $data['floodQuote'];
        $floodQuoteMetas = $this->floodQuoteService->getFloodQuoteMetas($floodQuote->flood_quote_id);
        $hiscoxID = $this->getMetaValue($floodQuoteMetas, "hiscoxID");
        $prevHiscoxBoundID = $this->getMetaValue($floodQuoteMetas, "prevHiscoxBoundID");
        $endorseDate = $this->getMetaValue($floodQuoteMetas, "endorseDate");

        $payload = [
            "hiscoxId" => $prevHiscoxBoundID,
            "cancellationDate" => $endorseDate,
            "premiumCalculation" => "ShortRate",
        ];

        $hiscox = $this->hixcoxAPI->previewCancel($payload);
        $hiscoxResponse = $hiscox['response'];

        $validation = $hiscoxResponse->messages->validation;
        $underwriterDecisions = $hiscoxResponse->messages->underwriterDecisions;
        $errors = $hiscoxResponse->messages->errors;

        if (count($errors) || count($validation)) {
            $text = "";

            if (count($errors))
                $text .= "Errors: " . implode("; ", $errors);

            if (count($validation))
                $text .= "Validations: " . implode("; ", $validation);

            session()->setFlashdata('error', $text);
        }

        $cancellationDate = isset($hiscoxResponse->response) ?
            $hiscoxResponse->response->cancellationDate : "";
        $returnPremium = isset($hiscoxResponse->response) ?
            $hiscoxResponse->response->returnPremium : 0;

        $hiscox = new \stdClass();
        $hiscox->cancelPremium = $returnPremium * -1;

        $this->floodQuoteService->cancelQuoteWithHiscox($hiscox, $id);

        $data['prevHiscoxBoundID'] = $prevHiscoxBoundID;
        $data['cancellationDate'] = $cancellationDate;
        $data['returnPremium'] = $returnPremium;

        return view('Hiscox/cancel_preview_view', ['data' => $data]);
    }

    public function reinstate($id = null)
    {
        helper('form');
        $data['title'] = "Reinstate Hiscox Quote";
        $data['floodQuote'] = $this->floodQuoteService->findOne($id);
        $data['hiscoxFloodQuote'] = null;

        if (!$data['floodQuote']) {
            return redirect()->to('/flood_quotes')->with('error', "Flood Quote not found.");
        }

        $floodQuote = $data['floodQuote'];
        $floodQuoteMetas = $this->floodQuoteService->getFloodQuoteMetas($floodQuote->flood_quote_id);
        $hiscoxID = $this->getMetaValue($floodQuoteMetas, "hiscoxID");
        $prevHiscoxBoundID = $this->getMetaValue($floodQuoteMetas, "prevHiscoxBoundID");

        $payload = [
            "hiscoxId" => $prevHiscoxBoundID,
        ];

        $hiscox = $this->hixcoxAPI->reinstate($payload);
        $hiscoxResponse = $hiscox['response'];

        $validation = $hiscoxResponse->messages->validation;
        $underwriterDecisions = $hiscoxResponse->messages->underwriterDecisions;
        $errors = $hiscoxResponse->messages->errors;

        if (count($errors) || count($validation)) {
            $text = "";

            if (count($errors))
                $text .= "Errors: " . implode("; ", $errors);

            if (count($validation))
                $text .= "Validations: " . implode("; ", $validation);

            session()->setFlashdata('error', $text);
        } else {
            $this->hiscoxQuoteService->reinstate($floodQuote->flood_quote_id, $hiscoxResponse->response->reinstatementDate, $prevHiscoxBoundID);

            return redirect()->to('/client/details/' . $floodQuote->client_id)->with('message', 'Flood Quote was successfully reinstated.');
        }

        return view('Hiscox/reinstate_view', ['data' => $data]);
    }
}
