<?php

namespace App\Controllers;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Libraries\HiscoxApiV2;

class Hiscox extends BaseController
{
    protected $floodQuoteService;
    protected $hiscoxQuoteService;
    protected $constructionService;
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
                return $meta->meta_value;
            }
        }
        return $default;
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
            if (isset($_SESSION["hiscoxQuote"])) {
                $hiscoxQuote = $_SESSION["hiscoxQuote"];
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
            }

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
        $hiscoxID = null;

        if (!$data['floodQuote']) {
            return redirect()->to('/flood_quotes')->with('error', "Flood Quote not found.");
        }

        $floodQuote = $data['floodQuote'];
        $floodQuoteMetas = $this->floodQuoteService->getFloodQuoteMetas($id);
        $hiscoxID = $this->getMetaValue($floodQuoteMetas, "hiscoxID", $hiscoxID);
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

        if ($this->request->is('post')) {
            $post = $this->request->getPost();

            $postPolicyType = $post['policyType'] ?? "";
            $postDeductible =  $post['deductible'] ?? "";
            $postPolicyIndex = $post['policyIndex'] ?? "";

            $selectedPolicyType = $postPolicyType;
            $selectedDeductible = $postDeductible;
            $selectedPolicyIndex = $postPolicyIndex;

            $isEndorsement = $policyType == "END";

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
            ]);
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

        return view('Hiscox/select_view', ['data' => $data]);
    }
}
