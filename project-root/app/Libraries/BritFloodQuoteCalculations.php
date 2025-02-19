<?php

namespace App\Libraries;

use App\Services\StateRateService;
use App\Services\BritFloodARateService;
use App\Services\BritFloodVRateService;
use App\Services\BritFloodBCXRateService;
use App\Services\FloodZoneService;

class BritFloodQuoteCalculations
{
    protected $floodQuote;
    protected $floodQuoteMetas;
    protected $stateRateService;
    protected $britFloodARateService;
    protected $britFloodBCXRateService;
    protected $britFloodVRateService;
    protected $stateRate;
    protected $floodZoneService;

    public $baseCoverages;
    public $totalCoverages;
    public $taxAmount;
    public $cancelTax;
    public $cancelPremium;
    public $policyType;
    public $bindingAuthority;
    public $finalPremium;
    public $hiscoxPremiumOverride;
    public $hiscoxQuotedPremium;
    public $hiscoxQuotedRate;
    public $renewalAdditionalPremium;
    public $policyFee;
    public $taxRate;
    public $fullPremiumOverride;
    public $totalRenewalPremium;
    public $basePremium;
    public $baseRate;
    public $baseField;
    public $baseFieldEnum;
    public $baseFieldPrefix;
    public $zoneRate;
    public $dwellingValueCredit;
    public $rentDwellingCredit;
    public $lossRentPremium;
    public $lossUseCoverage;
    public $deductibleCredit;
    public $rentDeductibleCredit;
    public $primaryResidentCredit;
    public $rentPrimaryCredit;
    public $lossDebit;
    public $rentLossDebit;
    public $midLevelSurcharge;
    public $rentMidDebit;
    public $replacementCostSurcharge;
    public $rentRCEDebit;
    public $personalPropertyReplacementCost;
    public $rentPropertyReplacementCost;
    public $dwellingReplacementCost;
    public $rentDwellingReplacementCost;
    public $stampFee;
    public $finalCost;
    public $lossUseAdjustmentRate;
    public $additionalPremium;
    public $estimatedContentReplacement;
    public $estimatedBuildingReplacement;
    public $estimatedLossRent;

    protected $bindAuthorityService;

    public function __construct($floodQuote)
    {
        helper(['service']);

        $this->floodQuote = $floodQuote;
        $this->floodQuoteMetas = getFloodQuoteMetas($this->floodQuote->flood_quote_id);
        $this->stateRateService = new StateRateService();
        $this->britFloodARateService = new BritFloodARateService();
        $this->britFloodVRateService = new BritFloodVRateService();
        $this->britFloodBCXRateService = new BritFloodBCXRateService();
        $this->floodZoneService = new FloodZoneService();
        $this->bindAuthorityService = service('bindAuthorityService');

        $this->setBaseValues();
    }

    private function setBaseValues()
    {
        $covABuilding = (int)$this->getMetaValue("covABuilding", 0);
        $covCContent = (int)$this->getMetaValue("covCContent", 0);
        $elevationDifference = (float)$this->getMetaValue("elevationDifference", 0);
        $flood_zone = $this->getMetaValue("flood_zone");
        $baseRateAdjustment = (float)$this->getMetaValue("baseRateAdjustment", 0);
        $covDLossUse = (float)$this->getMetaValue("covDLossUse", 0);
        $has10PercentAdjustment = (bool)$this->getMetaValue("has10PercentAdjustment", false);
        $tenPercentAdjustment = 0;

        $floodZone = $this->floodZoneService->findOne($flood_zone);

        $state = $this->getMetaValue("propertyState", "NJ");
        $stateRate = $this->stateRateService->getByState($state);

        $floodRates = $this->getFloodRates();
        $floodARate = $floodRates['arate'];
        $floodVRate = $floodRates['vrate'];
        $floodBCXRate = $floodRates['bcxrate'];

        $this->baseCoverages = $covABuilding + $covCContent;
        $this->taxAmount = 0;
        $this->cancelTax = (float)$this->getMetaValue("endTax", 0);
        $this->cancelPremium = (float)$this->getMetaValue("cancelPremium", 0);
        $this->policyType = $this->getMetaValue("policyType");
        $this->hiscoxPremiumOverride = (float)$this->getMetaValue("hiscoxPremiumOverride", 0);
        $this->hiscoxQuotedPremium = (float)$this->getMetaValue("hiscoxQuotedPremium", 0);
        $this->hiscoxQuotedRate = (float)$this->getMetaValue("hiscoxQuotedRate", 0);
        $this->renewalAdditionalPremium = (float)$this->getMetaValue("renewalAdditionalPremium", 0);
        $this->policyFee = 0;
        $this->taxRate = 0.05;
        $this->basePremium = 0;
        $this->baseRate = 0;
        $this->baseField = "";
        $this->baseFieldEnum = "";
        $this->baseFieldPrefix = "";
        $this->zoneRate = "";
        $this->fullPremiumOverride = (float)$this->getMetaValue("fullPremiumOverride", 0);
        $this->totalRenewalPremium = (float)$this->getMetaValue("totalRenewalPremium", 0);
        $this->dwellingValueCredit = 0;
        $this->rentDwellingCredit = 0;
        $this->lossRentPremium = 0;
        $this->lossUseCoverage = 0;
        $this->deductibleCredit = 0;
        $this->rentDeductibleCredit = 0;
        $this->primaryResidentCredit = 0;
        $this->rentPrimaryCredit = 0;
        $this->lossDebit = 0;
        $this->rentLossDebit = 0;
        $this->midLevelSurcharge = 0;
        $this->rentMidDebit = 0;
        $this->replacementCostSurcharge = 0;
        $this->rentRCEDebit = 0;
        $this->personalPropertyReplacementCost = 0;
        $this->rentPropertyReplacementCost = 0;
        $this->dwellingReplacementCost = 0;
        $this->rentDwellingReplacementCost = 0;
        $this->stampFee = 0;
        $this->finalCost = 0;
        $this->lossUseAdjustmentRate = 0;
        $this->additionalPremium = 0;
        $this->estimatedContentReplacement = 0;
        $this->estimatedBuildingReplacement = 0;
        $this->estimatedLossRent = 0;

        $bind_authority = $this->getMetaValue('bind_authority');
        $bindAuthority = $this->bindAuthorityService->findOne($bind_authority);
        $this->bindingAuthority = ($bindAuthority) ? $bindAuthority->reference : "";

        if ($elevationDifference >= -0.5 && $elevationDifference < 0.5) {
            $this->baseFieldEnum = "0";
        } else if ($elevationDifference < -0.5) {
            $this->baseFieldEnum = "-1";
        } else if ($elevationDifference >= 0.5 && $elevationDifference < 1.5) {
            $this->baseFieldEnum = "1";
        } else if ($elevationDifference >= 1.5 && $elevationDifference < 2.5) {
            $this->baseFieldEnum = "2";
        } else if ($elevationDifference >= 2.5 && $elevationDifference < 3.5) {
            $this->baseFieldEnum = "3";
        } else {
            $this->baseFieldEnum = "4";
        }

        if ($covABuilding > 0 && $covCContent == 0) {
            $this->baseFieldPrefix = "dwl";
        } else if ($covABuilding > 0 && $covCContent > 0) {
            $this->baseFieldPrefix = "both";
        } else if ($covABuilding == 0 && $covCContent > 0) {
            $this->baseFieldPrefix = "cont";
        } else {
            $this->baseFieldPrefix = "dwl";
        }

        $this->baseField = $this->baseFieldPrefix . $this->baseFieldEnum;

        if (strpos($floodZone->name, "A") !== false) {
            $this->zoneRate = "A";
            $rates = (array) $floodARate;
            $this->baseRate = $rates[$this->baseField];
        } else if (strpos($floodZone->name, "V") !== false) {
            $this->zoneRate = "V";
            $rates = (array) $floodVRate;
            $this->baseRate = $rates[$this->baseField];
        } else {
            $this->zoneRate = "BCX";
            $rates = (array) $floodBCXRate;
            $this->baseRate = $rates[$this->baseField];
        }

        $this->baseRate = $this->baseRate + $baseRateAdjustment;

        if ($stateRate) {
            $this->policyFee = $stateRate->policy_fee;
            $this->taxRate = $stateRate->tax_rate;
        }

        if ($this->bindingAuthority == "B1921 VC000250T") {
            $this->basePremium = $this->hiscoxQuotedPremium;
            $this->baseRate = $this->hiscoxQuotedRate;
        } else {
            $this->basePremium = $this->baseCoverages * $this->baseRate / 100;
            $this->basePremium = ceil($this->basePremium);
        }

        if ($has10PercentAdjustment) {
            $tenPercentAdjustment = $this->basePremium * 0.1;
        }

        if ($stateRate) {
            if ($this->baseRate < 0.5) {
                $this->lossUseAdjustmentRate = $stateRate->loss_rent_over_5k_rate;
            } else if ($this->baseRate >= 0.5) {
                $this->lossUseAdjustmentRate = $this->baseRate;
            } else {
                $this->lossUseAdjustmentRate = 0.01;
            }

            $this->estimatedContentReplacement = ceil($this->basePremium * $stateRate->pers_prop_repl_rate) * 1.05;
            $this->estimatedBuildingReplacement = ceil($this->basePremium * $stateRate->dwell_repl_cost_rate) * 1.05;
        }

        if ($covDLossUse == 0) {
            $this->lossRentPremium = 0;
            $this->lossUseCoverage = 0;
        } else if ($covDLossUse > 0 && $covDLossUse <= 5000) {
            $this->lossUseCoverage = 5000;

            if ($stateRate) {
                $this->lossRentPremium = $stateRate->loss_rent_5k_fee;
            }
        } else if ($covDLossUse > 5000 && $covDLossUse <= 20000) {
            $this->lossUseCoverage = $covDLossUse;
            $this->lossRentPremium = $this->lossUseAdjustmentRate * $covDLossUse / 100;
            $this->lossRentPremium = ceil($this->lossRentPremium);

            if ($this->lossRentPremium < 100) {
                $this->lossRentPremium = 100;
                $this->lossUseCoverage = 20000;
            }
        } else if ($covDLossUse > 20000 && $covDLossUse <= 50000) {
            $this->lossRentPremium = $this->lossUseAdjustmentRate * $covDLossUse / 100;
            $this->lossUseCoverage = $covDLossUse;
            $this->lossRentPremium = ceil($this->lossRentPremium);
        } else {
            $this->lossRentPremium = 0.01 * $covDLossUse;
            $this->lossUseCoverage = $covDLossUse;
            $this->lossRentPremium = ceil($this->lossRentPremium);
        }

        $this->totalCoverages = $covABuilding + $covCContent + $this->lossUseCoverage;

        if ($stateRate) {
            $this->calculateDwellingValues($stateRate);
            $this->calculatePrimaryResidentCredits($stateRate);
            $this->calculateLossDebits($stateRate);
            $this->calculateMidLevelDebit($stateRate);
            $this->calculatePersonalPropertyReplacementCost($stateRate);
            $this->calculateDwellingReplacementCost($stateRate);
        }
        $this->calculateRCESurcharge();

        $this->estimatedLossRent = ceil(20000 * $this->lossUseAdjustmentRate / 100);

        if ($this->policyType == 'CAN') {
            $this->taxAmount = $this->cancelTax;
            $this->finalPremium = $this->cancelPremium;
            $this->policyFee = 0;
        } else {
            if (strpos($this->bindingAuthority, "250") !== false) {
                $this->finalPremium = ($this->hiscoxPremiumOverride > 0) ?
                    $this->hiscoxPremiumOverride : $this->hiscoxQuotedPremium + $this->renewalAdditionalPremium;

                if ($this->policyType == 'END') {
                    $this->policyFee = 0;
                }
            } else {
                $additionalPremium = (float)$this->getMetaValue("additionalPremium", 0);

                if ($this->fullPremiumOverride > 0) {
                    $this->finalPremium = $this->fullPremiumOverride;
                } else if ($this->totalRenewalPremium > 0) {
                    $computedAdditionalPremium = $this->totalRenewalPremium * $additionalPremium * 0.01;
                    $this->additionalPremium = $computedAdditionalPremium;
                    $this->finalPremium = $this->totalRenewalPremium + $computedAdditionalPremium;
                } else {
                    $totalCosts = $this->basePremium
                        - $tenPercentAdjustment
                        - $this->dwellingValueCredit
                        - $this->deductibleCredit
                        - $this->primaryResidentCredit
                        + $this->lossDebit
                        + $this->midLevelSurcharge
                        + $this->replacementCostSurcharge
                        + $this->personalPropertyReplacementCost
                        + $this->dwellingReplacementCost
                        + $this->lossRentPremium;

                    $computedAdditionalPremium = $totalCosts * $additionalPremium * 0.01;
                    $this->additionalPremium = $computedAdditionalPremium;

                    $this->finalPremium = $totalCosts + $computedAdditionalPremium + $this->renewalAdditionalPremium;
                }
            }

            $this->taxAmount = $this->finalPremium * $this->taxRate;
        }

        if ($stateRate) {
            $this->calculateStampFee($stateRate);
        }

        $this->finalCost = $this->finalPremium + $this->taxAmount + $this->policyFee + $this->stampFee;
    }

    private function calculateStampFee($stateRate)
    {
        if ($stateRate->state == "PA") {
            $this->stampFee = 20;
        } else {
            $this->stampFee = $this->finalPremium * $stateRate->stamping_fee;
        }
    }

    private function calculateDwellingReplacementCost($stateRate)
    {
        $hasDrc = (int)$this->getMetaValue("hasDrc", 0);
        $isPrimaryResidence = (int)$this->getMetaValue("isPrimaryResidence", 0);
        $isCondo = (int)$this->getMetaValue("isCondo", 0);

        if ($hasDrc && !$isPrimaryResidence && !$isCondo) {
            $this->dwellingReplacementCost = $this->basePremium * $stateRate->dwell_repl_cost_rate;
            $this->dwellingReplacementCost = ceil($this->dwellingReplacementCost);
            $this->rentDwellingReplacementCost = $this->lossRentPremium * $stateRate->dwell_repl_cost_rate;
            $this->rentDwellingReplacementCost = ceil($this->rentDwellingReplacementCost);
        }
    }

    private function calculatePersonalPropertyReplacementCost($stateRate)
    {
        $hasOpprc = (int)$this->getMetaValue("hasOpprc", 0);

        if ($hasOpprc) {
            $this->personalPropertyReplacementCost = $this->basePremium * $stateRate->pers_prop_repl_rate;
            $this->personalPropertyReplacementCost = ceil($this->personalPropertyReplacementCost);
            $this->rentPropertyReplacementCost = $this->lossRentPremium * $stateRate->pers_prop_repl_rate;
            $this->rentPropertyReplacementCost = ceil($this->rentPropertyReplacementCost);
        }
    }

    private function calculateRCESurcharge()
    {
        $rceRatio = (float)$this->getMetaValue("rceRatio", 0);
        $underInsuredRate = (float)$this->getMetaValue("underInsuredRate", 0);

        if ($rceRatio < 0.5) {
            $this->replacementCostSurcharge = $this->basePremium * $underInsuredRate;
            $this->replacementCostSurcharge = ceil($this->replacementCostSurcharge);
            $this->rentRCEDebit = $this->lossRentPremium * $underInsuredRate;
            $this->rentRCEDebit = ceil($this->rentRCEDebit);
        }
    }

    private function calculateMidLevelDebit($stateRate)
    {
        $mle = (int)$this->getMetaValue("mle", 0);
        $bfe = (int)$this->getMetaValue("bfe", 0);
        $midLevBFE = ($mle == 0) ? 99 : $mle - $bfe;

        if ($midLevBFE < 0.5) {
            $this->midLevelSurcharge = $this->basePremium * $stateRate->mid_below_bfe_deb;
            $this->midLevelSurcharge = ceil($this->midLevelSurcharge);
            $this->rentMidDebit = $this->lossRentPremium * $stateRate->mid_below_bfe_deb;
            $this->rentMidDebit = ceil($this->rentMidDebit);
        }
    }

    private function calculateLossDebits($stateRate)
    {
        $lossesIn10Years = $this->getMetaValue("lossesIn10Years", 0);

        if ($lossesIn10Years == 1) {
            $this->lossDebit = $this->basePremium * $stateRate->one_loss_deb;
            $this->lossDebit = ceil($this->lossDebit);
            $this->rentLossDebit = $this->lossRentPremium * $stateRate->one_loss_deb;
            $this->rentLossDebit = ceil($this->rentLossDebit);
        } else if ($lossesIn10Years > 1) {
            $this->lossDebit = $this->basePremium * 1;
        }
    }

    private function calculatePrimaryResidentCredits($stateRate)
    {
        $isPrimaryResidence = (int)$this->getMetaValue("isPrimaryResidence", 0);

        if ($isPrimaryResidence) {
            $this->primaryResidentCredit = $this->basePremium * $stateRate->prim_res_cred;
            $this->primaryResidentCredit = floor($this->primaryResidentCredit);
            $this->rentPrimaryCredit = $this->lossRentPremium * $stateRate->prim_res_cred;
            $this->rentPrimaryCredit = floor($this->rentPrimaryCredit);
        }
    }

    private function calculateDwellingValues($stateRate)
    {
        $covABuilding = (int)$this->getMetaValue("covABuilding", 0);
        $deductible_id = $this->getMetaValue("deductible_id");
        $bcx500kCovCred = $stateRate->bcx_500k_cov_cred;
        $bcx300kCovCred = $stateRate->bcx_300k_cov_cred;
        $bcx5kDeductCred = $stateRate->bcx_5k_deduct_cred;
        $av2500DeductCred = $stateRate->av_2500_deduct_cred;
        $av5kDeductCred = $stateRate->av_5k_deduct_cred;
        $av10kDeductCred = $stateRate->av_10k_deduct_cred;
        $av500kCovCred = $stateRate->av_500k_cov_cred;
        $av300kCovCred = $stateRate->av_300k_cov_cred;

        if ($this->zoneRate == "BCX") {
            if ($covABuilding > 500000) {
                $this->dwellingValueCredit = $this->basePremium * $bcx500kCovCred;
                $this->dwellingValueCredit = floor($this->dwellingValueCredit);
                $this->rentDwellingCredit = $this->lossRentPremium * $bcx500kCovCred;
                $this->rentDwellingCredit = floor($this->rentDwellingCredit);
            } else if ($covABuilding >= 300000) {
                $this->dwellingValueCredit = $this->basePremium * $bcx300kCovCred;
                $this->dwellingValueCredit = floor($this->dwellingValueCredit);
                $this->rentDwellingCredit = $this->lossRentPremium * $bcx300kCovCred;
                $this->rentDwellingCredit = floor($this->rentDwellingCredit);
            } else {
                $this->dwellingValueCredit = 0;
                $this->rentDwellingCredit = 0;
            }

            if ($deductible_id == 2) {
                $this->deductibleCredit = $this->basePremium * $bcx5kDeductCred;
                $this->rentDeductibleCredit = $this->lossRentPremium * $bcx5kDeductCred;
            } else {
                $this->deductibleCredit = 0;
                $this->rentDeductibleCredit = 0;
            }
        }

        if ($this->zoneRate == "A" || $this->zoneRate == "V") {
            switch ($deductible_id) {
                case 1:
                    $this->deductibleCredit = $this->basePremium * $av2500DeductCred;
                    $this->rentDeductibleCredit = $this->lossRentPremium * $av2500DeductCred;
                    break;
                case 2:
                case 7:
                    $this->deductibleCredit = $this->basePremium * $av5kDeductCred;
                    $this->rentDeductibleCredit = $this->lossRentPremium * $av5kDeductCred;
                    break;
                case 3:
                case 6:
                    $this->deductibleCredit = $this->basePremium * $av10kDeductCred;
                    $this->rentDeductibleCredit = $this->lossRentPremium * $av10kDeductCred;
                    break;
                default:
                    $this->deductibleCredit = 0;
                    $this->rentDeductibleCredit = 0;
                    break;
            }

            if ($covABuilding > 500000) {
                $this->dwellingValueCredit = $this->basePremium * $av500kCovCred;
                $this->rentDwellingCredit = $this->lossRentPremium * $av500kCovCred;
            } else if ($covABuilding >= 300000) {
                $this->dwellingValueCredit = $this->basePremium * $av300kCovCred;
                $this->rentDwellingCredit = $this->lossRentPremium * $av300kCovCred;
            } else {
                $this->dwellingValueCredit = 0;
                $this->rentDwellingCredit = 0;
            }
        }
    }

    private function getFloodRates()
    {
        $flood_foundation_id = (int)$this->getMetaValue("flood_foundation", 0);
        $numOfFloors = (int)$this->getMetaValue("numOfFloors", 0);
        $zip = $this->floodQuote->zip;
        $state_code = $this->floodQuote->state;
        $county_id = (int)$this->getMetaValue("propertyCounty", 0);

        $floodRates = [
            'arate' => null,
            'bcxrate' => null,
            'vrate' => null
        ];

        $floodVRates = $this->britFloodVRateService->getBritFloodVRateByFoundation($flood_foundation_id);
        if (count($floodVRates)) {
            $floodRates['vrate'] = $floodVRates[0];
        }

        $floodARates = $this->britFloodARateService->getBritFloodARateByFoundation($flood_foundation_id, $zip, $state_code, $county_id);
        if (count($floodARates)) {
            $floodRates['arate'] = $floodARates[0];
        }

        $floodBCXRates = $this->britFloodBCXRateService->getBritFloodBCXRateByFoundation($flood_foundation_id, $numOfFloors);
        if (count($floodBCXRates)) {
            $floodRates['bcxrate'] = $floodBCXRates[0];
        }

        return $floodRates;
    }

    public function getMetaValue($meta_key, $default = '')
    {
        foreach ($this->floodQuoteMetas as $meta) {
            if ($meta->meta_key === $meta_key) {
                if ($meta->meta_value == "" && $default != "")
                    return $default;
                else
                    return $meta->meta_value;
            }
        }
        return $default;
    }

    public function getDeductibles()
    {
        $deductible_id = $this->getMetaValue("deductible_id");

        switch ($deductible_id) {
            case '1':
                return  [
                    'building_deductible' => 2500,
                    'content_deductible' => 2500,
                    'rent_deductible' => 1000,
                ];
                break;
            case '2':
                return  [
                    'building_deductible' => 5000,
                    'content_deductible' => 5000,
                    'rent_deductible' => 1000,
                ];
                break;
            case '3':
                return  [
                    'building_deductible' => 10000,
                    'content_deductible' => 10000,
                    'rent_deductible' => 1000,
                ];
                break;
            case '5':
                return  [
                    'building_deductible' => 2000,
                    'content_deductible' => 2000,
                    'rent_deductible' => 1000,
                ];
                break;
            case '6':
                return  [
                    'building_deductible' => 25000,
                    'content_deductible' => 25000,
                    'rent_deductible' => 1000,
                ];
                break;
            case '7':
                return  [
                    'building_deductible' => 5000,
                    'content_deductible' => 10000,
                    'rent_deductible' => 1000,
                ];
                break;
            case '9':
                return  [
                    'building_deductible' => 25000,
                    'content_deductible' => 25000,
                    'rent_deductible' => 1000,
                ];
                break;
            default:
                return  [
                    'building_deductible' => 1500,
                    'content_deductible' => 1500,
                    'rent_deductible' => 1000,
                ];
                break;
        }
    }
}
