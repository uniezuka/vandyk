<?php

namespace App\Libraries;

use Exception;

class HiscoxApiV2
{
    private $clientID  = '53b1ce1f-437a-40b9-a798-67b86411e5f5';
    private $clientSecret  = '9QH8Q~jWx8tVQWhlIPbIhQLcpQ7WTMOjkvoALbbK';
    private $tenantID = 'dfbcc178-bccf-4595-8f8e-3a3175df90b7';
    private $debug = true;

    private $authorizationUrl = '';
    private $hiscoxBaseUrl = 'https://plus.hiscox.com/floodplus/stg/';
    // private $hiscoxBaseUrl = 'https://plus.hiscox.com/floodplus/prod/';

    private $data = [];

    public function __construct()
    {
        $this->authorizationUrl = 'https://login.microsoftonline.com/' . $this->tenantID . '/oauth2/v2.0/token';

        $this->data = [
            'client_id' => $this->clientID,
            'scope' => 'api://floodplus.hiscox.com/.default',
            'client_secret' => $this->clientSecret,
            'grant_type' => 'client_credentials'
        ];
    }

    private function generateAuthorizationToken()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://login.microsoftonline.com/dfbcc178-bccf-4595-8f8e-3a3175df90b7/oauth2/v2.0/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => http_build_query($this->data),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
            ),
        ));

        $response = curl_exec($curl);
        if ($response === false) {
            throw new Exception(curl_error($curl), curl_errno($curl));
        }

        // echo 'raw: ' . var_export($response, true);

        $json = json_decode($response);
        curl_close($curl);

        $token = $json->access_token;

        return $token;
    }

    private function debug_log($content)
    {
        $rootFolder = $_SERVER['DOCUMENT_ROOT'];
        $filename = $rootFolder . '/debug.log';

        $file = fopen($filename, 'a');

        if ($file === false) {
            exit();
        }

        $timestamp = '[' . date("Y-m-d H:i:s") . '] ';

        fwrite($file, $timestamp . $content);
        fclose($file);
    }

    private function call_hiscox_api($payload, $method)
    {
        $api = "";
        switch ($method) {
            case "NewRequest";
                $api = "/quote";
                break;
            case "Update";
                $api = "/update";
                break;
            case "Bind";
                $api = "/bind";
                break;
            case "Cancel";
                $api = "/cancel";
                break;
            case "Endorse";
                $api = '/endorse/quote';
                break;
            case "Renew";
                $api = '/renew';
                break;
            case "CancelPreview":
                $api = "/cancel/preview";
                break;
            case "Reinstate":
                $api = "/reinstate";
                break;
            case "BindEndorse":
                $api = "/endorse/bind";
                break;

            default:
                throw new Exception("Hiscox API method not found");
        }

        $authToken = $this->generateAuthorizationToken();
        $jsonData = json_encode($payload);

        if ($this->debug) {
            $this->debug_log("Calling " . $method . " API------>" . PHP_EOL);
            $this->debug_log("Request Payload: " . PHP_EOL);
            $this->debug_log(PHP_EOL . json_encode($payload, JSON_PRETTY_PRINT) . PHP_EOL);
        }

        // echo 'authToken: ' . var_export($authToken, true);

        $curl = curl_init($this->hiscoxBaseUrl . $api);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $authToken
        ));

        $response = curl_exec($curl);

        if ($response === false) {
            throw new Exception(curl_error($curl), curl_errno($curl));
        }

        curl_close($curl);

        if ($this->debug) {
            $this->debug_log("Response: " . PHP_EOL);
            $this->debug_log(PHP_EOL . $response . PHP_EOL);
        }

        $responseData = json_decode($response);

        return ['response' => $responseData, 'raw' => $response];
    }

    public function update($payload)
    {
        return $this->call_hiscox_api($payload, "Update");
    }

    public function newRequest($payload)
    {
        return $this->call_hiscox_api($payload, "NewRequest");
    }

    public function getRequest($hiscoxId)
    {
        $authToken = $this->generateAuthorizationToken();

        $curl = curl_init($this->hiscoxBaseUrl . '/quote/' . $hiscoxId);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $authToken
        ));

        $response = curl_exec($curl);

        if ($response === false) {
            throw new Exception(curl_error($curl), curl_errno($curl));
        }

        curl_close($curl);

        if ($this->debug) {
            $this->debug_log("Response: " . PHP_EOL);
            $this->debug_log(PHP_EOL . $response . PHP_EOL);
        }

        $responseData = json_decode($response);

        return ['response' => $responseData, 'raw' => $response];
    }

    public function bind($payload)
    {
        return $this->call_hiscox_api($payload, "Bind");
    }

    public function bindEndorse($payload)
    {
        return $this->call_hiscox_api($payload, "BindEndorse");
    }

    public function cancel($payload)
    {
        return $this->call_hiscox_api($payload, "Cancel");
    }

    public function previewCancel($payload)
    {
        return $this->call_hiscox_api($payload, "CancelPreview");
    }

    public function reinstate($payload)
    {
        return $this->call_hiscox_api($payload, "Reinstate");
    }

    public function endorse($payload)
    {
        return $this->call_hiscox_api($payload, "Endorse");
    }

    public function renew($payload)
    {
        return $this->call_hiscox_api($payload, "Renew");
    }

    private static function findHiscoxDeductibleOption($options, $selectedPolicyIndex, $selectedDeductible, $count, $isEndorsement = false)
    {
        $index = 0;

        foreach ($options as $option) {
            if ($selectedPolicyIndex == (string)$index) {

                if (count($option->deductibles)) {
                    foreach ($option->deductibles as $deductible) {
                        $count++;

                        if ($deductible->deductible == $selectedDeductible) {

                            if ($isEndorsement) {
                                $buildingPremium = $deductible->buildingAdditionalPremium;

                                if (isset($deductible->improvementsAndBettermentsAdditionalPremium)) {
                                    $buildingPremium = $deductible->improvementsAndBettermentsAdditionalPremium;
                                }

                                $contentsPremium = $deductible->includeContentsAdditionalPremium;
                                $otherStructuresPremium = $deductible->otherStructuresAdditionalPremium;
                                $lossOfUsePremium = $deductible->includeLossOfUseAdditionalPremium;
                                $businessIncomePremium = $deductible->includeBusinessIncomeAdditionalPremium;

                                $hiscoxOptions = new \stdClass();
                                $hiscoxOptions->building_premium = ceil($buildingPremium);
                                $hiscoxOptions->contents_premium = ceil($contentsPremium);
                                $hiscoxOptions->other_structures_premium = ceil($otherStructuresPremium);
                                $hiscoxOptions->loss_of_use_premium = ceil($lossOfUsePremium);
                                // $hiscoxOptions->improvementsAndBettermentsPremium = ($deductible->improvementsAndBettermentsPremium) ? ceil($deductible->improvementsAndBettermentsPremium) : 0;
                                $hiscoxOptions->businessIncomePremium = ceil($businessIncomePremium);

                                $hiscoxOptions->building_coverage_limit = (isset($option->buildingLimit)) ? ceil($option->buildingLimit) : 0;
                                $hiscoxOptions->contents_coverage_limit = ceil($option->contentsLimit);
                                $hiscoxOptions->other_structures_coverage_limit = (isset($option->otherStructuresLimit)) ? ceil($option->otherStructuresLimit) : 0;
                                $hiscoxOptions->loss_of_use_coverage_limit = (isset($option->lossOfUseLimit)) ? ceil($option->lossOfUseLimit) : 0;
                                $hiscoxOptions->deductible = ceil($deductible->deductible);
                                $hiscoxOptions->improvementsAndBettermentsLimit = (isset($option->improvementsAndBettermentsLimit)) ? ceil($option->improvementsAndBettermentsLimit) : 0;
                                $hiscoxOptions->businessIncomeAndExtraExpenseAnnualValue = (isset($option->businessIncomeAndExtraExpenseAnnualValue)) ? ceil($option->businessIncomeAndExtraExpenseAnnualValue) : 0;

                                return ['index' => $count, 'policyType' => $option->policyType, 'options' => $hiscoxOptions];
                            } else {
                                $hiscoxOptions = new \stdClass();
                                $hiscoxOptions->building_premium = (isset($deductible->buildingPremium)) ? ceil($deductible->buildingPremium) : 0;
                                $hiscoxOptions->contents_premium = ceil($deductible->contentsPremium);
                                $hiscoxOptions->other_structures_premium = (isset($deductible->otherStructuresPremium)) ? ceil($deductible->otherStructuresPremium) : 0;
                                $hiscoxOptions->loss_of_use_premium = (isset($deductible->lossOfUsePremium)) ? ceil($deductible->lossOfUsePremium) : 0;
                                $hiscoxOptions->improvementsAndBettermentsPremium = (isset($deductible->improvementsAndBettermentsPremium)) ? ceil($deductible->improvementsAndBettermentsPremium) : 0;
                                $hiscoxOptions->businessIncomePremium = (isset($deductible->businessIncomePremium)) ? ceil($deductible->businessIncomePremium) : 0;

                                $hiscoxOptions->building_coverage_limit = (isset($option->buildingLimit)) ? ceil($option->buildingLimit) : 0;
                                $hiscoxOptions->contents_coverage_limit = ceil($option->contentsLimit);
                                $hiscoxOptions->other_structures_coverage_limit = (isset($option->otherStructuresLimit)) ? ceil($option->otherStructuresLimit) : 0;
                                $hiscoxOptions->loss_of_use_coverage_limit = (isset($option->lossOfUseLimit)) ? ceil($option->lossOfUseLimit) : 0;
                                $hiscoxOptions->deductible = ceil($deductible->deductible);
                                $hiscoxOptions->improvementsAndBettermentsLimit = (isset($option->improvementsAndBettermentsLimit)) ? ceil($option->improvementsAndBettermentsLimit) : 0;
                                $hiscoxOptions->businessIncomeAndExtraExpenseAnnualValue = (isset($option->businessIncomeAndExtraExpenseAnnualValue)) ? ceil($option->businessIncomeAndExtraExpenseAnnualValue) : 0;

                                return ['index' => $count, 'policyType' => $option->policyType, 'options' => $hiscoxOptions];
                            }
                        }
                    }
                }
            } else {
                $count += count($option->deductibles);
            }
            $index++;
        }

        return ['index' => 0, 'policyType' => '', 'options' => null];
    }

    public static function getHiscoxSelectedOption($selectedPolicyType, $selectedPolicyIndex, $selectedDeductible, $primaryOptions, $excessOptions, $isEndorsement = false)
    {
        if (count($primaryOptions) || count($excessOptions)) {
            $count = 0;

            if ($selectedPolicyType == "primary") {
                $deductibleOption = self::findHiscoxDeductibleOption($primaryOptions, $selectedPolicyIndex, $selectedDeductible, $count, $isEndorsement);
            } else {
                foreach ($primaryOptions as $option) {
                    $count += count($option->deductibles);
                }

                $deductibleOption = self::findHiscoxDeductibleOption($excessOptions, $selectedPolicyIndex, $selectedDeductible, $count, $isEndorsement);
            }

            return $deductibleOption;
        }

        return ['index' => 0, 'policyType' => '', 'options' => null];
    }

    public static function getHiscoxAdditionalFoundationType($isEnclosureFinished, $foundationType)
    {
        if ($isEnclosureFinished) {
            switch ($foundationType) {
                case '9':
                case '2':
                case '3':
                    return 'FinishedEnclosureFull';
                case '8':
                    return 'FinishedEnclosurePartial';
                case '5':
                case '4':
                case '7':
                case '6':
                    return 'FinishedCrawlspace';
                default:
                    return 'None';
            }
        } else { // no
            switch ($foundationType) {
                case '9':
                case '2':
                case '3':
                    return 'UnfinishedEnclosureFull';
                case '8':
                    return 'UnfinishedEnclosurePartial';
                case '5':
                case '4':
                case '7':
                case '6':
                    return 'UnfinishedCrawlspace';
                default:
                    return 'None';
            }
        }
    }

    public static function getHiscoxFoundationType($foundationType)
    {
        switch ($foundationType) {
            case '9':
            case '3':
            case '1':
            case '8':
            case '2':
                return 'PiersPostsPilings';
            case '12':
                return 'FoundationWall';
            case '11':
                return 'SlabOnFill';
            case '10':
                return 'SlabOnGrade';
            case '5':
            case '4':
            case '7':
            case '6':
                return 'SolidFoundationWalls';
            default:
                return '';
        }
    }

    public static function getHiscoxBuildingOverWaterType($over_water)
    {
        switch ($over_water) {
            case '0':
                return 'No';
            case '1':
                return 'Partially';
            case '2':
                return 'Entirely';
            default:
                return '';
        }
    }

    public static function getHiscoxAttachedGarageType($garage_attached)
    {
        switch ($garage_attached) {
            case '1':
            case '2':
                return 'Finished';
            case '3':
                return 'Unfinished';
            case '0':
                return 'None';
            default:
                return '';
        }
    }

    public static function getHiscoxBasementType($basement_finished)
    {
        switch ($basement_finished) {
            case '1':
                return 'Finished';
            case '0':
                return 'Unfinished';
            case '2':
                return 'None';
            default:
                return '';
        }
    }

    public static function getHiscoxOccupancyType($isPerson, $commercial_occupancy, $isPrimaryResidence, $other_occupancy)
    {
        if ($isPerson) {
            if ($isPrimaryResidence == '1') return 'Primary';
            else if ($isPrimaryResidence == '0') return 'Secondary';
            else {
                switch ($other_occupancy) {
                    case '1':
                        return 'Seasonal';
                    case '2':
                        return 'Tenanted';
                    case '3':
                        return 'Vacant';
                    case '4':
                        return 'CourseOfConstruction';
                    case '5':
                        return 'VacantRenovation';
                    default:
                        return '';
                }
            }
        } else {
            return $commercial_occupancy;
        }
    }

    public static function createCommonHiscoxQuote($item)
    {
        if (isset($item) && isset($item->result)) {

            $hiscoxQuote = new \stdClass();
            $hiscoxQuote->request = new \stdClass();
            $hiscoxQuote->response = new \stdClass();
            $hiscoxQuote->messages = new \stdClass();

            $hiscoxQuote->response->hiscoxId = $item->result->hiscoxId;

            if (isset($item->result->policyTerms[0]->quotes[0]->residential)) {
                $residential = $item->result->policyTerms[0]->quotes[0]->residential;

                $hiscoxQuote->request->contentsCostValueType = $residential->contentsCostValueType;
                $hiscoxQuote->request->foundation = new \stdClass();
                $hiscoxQuote->request->foundation->foundationType = $residential->foundationType;
                $hiscoxQuote->request->foundation->additionalFoundationType = $residential->additionalFoundationType;
                $hiscoxQuote->request->basementType = $residential->basementType;
                $hiscoxQuote->request->yearBuilt = $residential->yearBuilt;
                $hiscoxQuote->request->squareFootage = $residential->squareFootage;
                $hiscoxQuote->request->numberOfStories = $residential->numberOfStories;
                $hiscoxQuote->request->elevationHeight = $residential->elevationHeight;
                $hiscoxQuote->request->buildingOverWaterType = $residential->buildingOverWaterType;
                $hiscoxQuote->request->productType = "Residential";
                $hiscoxQuote->response->quoteRequestDate = $residential->quoteRequestDate;

                $hiscoxQuote->request->residential = new \stdClass();
                $hiscoxQuote->request->residential->occupancyType = $residential->occupancyType;
                $hiscoxQuote->request->residential->constructionType = $residential->constructionType;
                $hiscoxQuote->request->residential->attachedGarageType = $residential->attachedGarageType;

                $hiscoxQuote->response->residential = new \stdClass();
                $hiscoxQuote->response->residential->primary = $residential->primary;
                $hiscoxQuote->response->residential->excess = $residential->excess;

                $hiscoxQuote->request->priorLosses = $residential->priorLosses;
            } else if (isset($item->result->policyTerms[0]->quotes[0]->commercialOwned)) {
                $commercialOwned = $item->result->policyTerms[0]->quotes[0]->commercialOwned;

                $hiscoxQuote->request->contentsCostValueType = $commercialOwned->contentsCostValueType;
                $hiscoxQuote->request->foundation = new \stdClass();
                $hiscoxQuote->request->foundation->foundationType = $commercialOwned->foundationType;
                $hiscoxQuote->request->foundation->additionalFoundationType = $commercialOwned->additionalFoundationType;
                $hiscoxQuote->request->basementType = $commercialOwned->basementType;
                $hiscoxQuote->request->yearBuilt = $commercialOwned->yearBuilt;
                $hiscoxQuote->request->squareFootage = $commercialOwned->squareFootage;
                $hiscoxQuote->request->numberOfStories = $commercialOwned->numberOfStories;
                $hiscoxQuote->request->elevationHeight = $commercialOwned->elevationHeight;
                $hiscoxQuote->request->buildingOverWaterType = $commercialOwned->buildingOverWaterType;
                $hiscoxQuote->request->productType = "Commercial";
                $hiscoxQuote->response->quoteRequestDate = $commercialOwned->quoteRequestDate;

                $hiscoxQuote->request->commercial = new \stdClass();
                $hiscoxQuote->request->commercial->occupancyType = $commercialOwned->occupancyType;
                $hiscoxQuote->request->commercial->constructionType = $commercialOwned->constructionType;

                $hiscoxQuote->response->commercialOwned = new \stdClass();
                $hiscoxQuote->response->commercialOwned->primary = $commercialOwned->primary;
                $hiscoxQuote->response->commercialOwned->excess = $commercialOwned->excess;

                $hiscoxQuote->request->priorLosses = $commercialOwned->priorLosses;
            } else if (isset($item->result->policyTerms[0]->quotes[0]->commercialTenanted)) {
                $commercialTenanted = $item->result->policyTerms[0]->quotes[0]->commercialTenanted;

                $hiscoxQuote->request->contentsCostValueType = $commercialTenanted->contentsCostValueType;
                $hiscoxQuote->request->foundation = new \stdClass();
                $hiscoxQuote->request->foundation->foundationType = $commercialTenanted->foundationType;
                $hiscoxQuote->request->foundation->additionalFoundationType = $commercialTenanted->additionalFoundationType;
                $hiscoxQuote->request->basementType = $commercialTenanted->basementType;
                $hiscoxQuote->request->yearBuilt = $commercialTenanted->yearBuilt;
                $hiscoxQuote->request->squareFootage = $commercialTenanted->squareFootage;
                $hiscoxQuote->request->numberOfStories = $commercialTenanted->numberOfStories;
                $hiscoxQuote->request->elevationHeight = $commercialTenanted->elevationHeight;
                $hiscoxQuote->request->buildingOverWaterType = $commercialTenanted->buildingOverWaterType;
                $hiscoxQuote->request->productType = "Commercial";
                $hiscoxQuote->response->quoteRequestDate = $commercialTenanted->quoteRequestDate;

                $hiscoxQuote->request->commercial = new \stdClass();
                $hiscoxQuote->request->commercial->occupancyType = $commercialTenanted->occupancyType;
                $hiscoxQuote->request->commercial->constructionType = $commercialTenanted->constructionType;

                $hiscoxQuote->response->commercialTenanted = new \stdClass();
                $hiscoxQuote->response->commercialTenanted->primary = $commercialTenanted->primary;
                $hiscoxQuote->response->commercialTenanted->excess = $commercialTenanted->excess;

                $hiscoxQuote->request->priorLosses = $commercialTenanted->priorLosses;
            }

            return $hiscoxQuote;
        } else {
            return null;
        }
    }

    public static function createProductResponseRequest($hiscoxQuote, $floodQuote, $isRented = true)
    {
        $hiscoxProductResponse = null;
        $hiscoxProductRequest = null;
        $purpose = "";

        if ($floodQuote->entity_type == 0) {
            if (!isset($hiscoxQuote->response->residential)) {
                throw new Exception("Flood Quote is Residential while Hiscox Quote is Commercial");
            }

            $hiscoxProductResponse = $hiscoxQuote->response->residential;
            $hiscoxProductRequest = $hiscoxQuote->request->residential;
            $purpose = "Residential";
        } else {
            if (!isset($hiscoxQuote->response->commercial)) {
                throw new Exception("Flood Quote is Commercial while Hiscox Quote is Residential");
            }

            $hiscoxProductRequest = $hiscoxQuote->request->commercial;

            if ($isRented) {
                if (!isset($hiscoxQuote->response->commercialTenanted)) {
                    throw new Exception("Flood Quote is Rented while Hiscox Quote is Commercially Owned");
                }

                $hiscoxProductResponse = $hiscoxQuote->response->commercialTenanted;
                $purpose = "Commercial Tenanted";
            } else {
                if (!isset($hiscoxQuote->response->commercialOwned)) {
                    throw new Exception("Flood Quote is Commercially Owned while Hiscox Quote is Rented");
                }

                $hiscoxProductResponse = $hiscoxQuote->response->commercialOwned;
                $purpose = "Commercial Owned";
            }
        }

        return [
            'hiscoxProductResponse' => $hiscoxProductResponse,
            'hiscoxProductRequest' => $hiscoxProductRequest,
            'purpose' => $purpose,
        ];
    }

    public static function findSelectedDeductible($options, &$hiscoxSelectedOptionIndex, &$hiscoxSelectedPolicyType, &$hiscoxSelectedDeductible)
    {
        if ($hiscoxSelectedOptionIndex != -1) return;

        foreach ($options as $option) {
            $count = 0;

            if (count($option->deductibles)) {
                foreach ($option->deductibles as $deductible) {
                    if (isset($deductible->bind)) {
                        $hiscoxSelectedOptionIndex = $count;
                        $hiscoxSelectedPolicyType = strtolower($option->policyType);
                        $hiscoxSelectedDeductible = $deductible->deductible;
                        return;
                    }
                }
            }

            $count++;
        }
    }
}
