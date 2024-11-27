<?php

namespace App\Libraries;

use App\Services\StateRateService;

class FloodDeclarationCalculations
{
    protected $stateRateService;

    protected $floodQuote;
    protected $floodQuoteMetas;

    public $baseRate = 0;
    public $primaryResidentCredit = 0;
    public $dwellingValueCredit = 0;
    public $lossDebit = 0;
    public $basePremium = 0;
    public $totalCoverages = 0;
    public $finalPremium = 0;
    public $taxRate = 0.05;
    public $midLevelSurcharge = 0;
    public $replacementCostSurcharge = 0;
    public $personalPropertyReplacementCost = 0;
    public $dwellingReplacementCost = 0;
    public $taxAmount = 0;
    public $policyFee = 0;
    public $stampFee = 0;
    public $buildingPremium = 0;
    public $contentPremium = 0;
    public $rentPremium = 0;
    public $discounts = 0;
    public $charges = 0;
    public $lossRentPremium = 0;
    public $deductibleCredit = 0;
    public $finalCost = 0;
    public $properlyVented = "";
    public $estimatedLossRent = 0;
    public $estimatedContentReplacement = 0;
    public $estimatedBuildingReplacement = 0;

    public function __construct($floodQuote)
    {
        helper(['service']);

        $this->floodQuote = $floodQuote;
        $this->floodQuoteMetas = getFloodQuoteMetas($this->floodQuote->flood_quote_id);
        $this->stateRateService = new StateRateService();

        $this->setBaseValues();
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

    private function setBaseValues()
    {
        $covABuilding = (int)$this->getMetaValue("covABuilding", 0);
        $covCContent = (int)$this->getMetaValue("covCContent", 0);
        $covDLossUse = (float)$this->getMetaValue("covDLossUse", 0);
        $flood_foundation_id = (int)$this->getMetaValue("flood_foundation", 0);
        $loss_rent_cov_amount = 0;
        $pers_prop_repl_rate = 0;
        $dwell_repl_cost_rate = 0;

        $state = $this->getMetaValue("propertyState", "NJ");
        $stateRate = $this->stateRateService->getByState($state);

        if ($stateRate) {
            $loss_rent_cov_amount = $stateRate->loss_rent_cov_amount;
            $pers_prop_repl_rate = $stateRate->pers_prop_repl_rate;
            $dwell_repl_cost_rate = $stateRate->dwell_repl_cost_rate;
        }

        $this->baseRate = (int)$this->getMetaValue("boundBaseRate", 0);
        $this->lossRentPremium = $this->baseRate * $loss_rent_cov_amount / 100;
        $this->totalCoverages = $covABuilding + $covCContent + $covDLossUse;
        $this->basePremium = (int)$this->getMetaValue("boundBasePremium", 0);
        $this->buildingPremium = $covABuilding * $this->baseRate / 100;
        $this->contentPremium = $covCContent * $this->baseRate / 100;
        $this->rentPremium = $covDLossUse * $this->baseRate / 100;
        $this->deductibleCredit = (int)$this->getMetaValue("boundDeductibleSaving", 0);
        $this->policyFee = (int)$this->getMetaValue("boundPolicyFee", 0);
        $this->stampFee = (int)$this->getMetaValue("boundStampFee", 0);

        $this->primaryResidentCredit = (int)$this->getMetaValue("boundPrimaryDiscount", 0);
        $this->dwellingValueCredit = (int)$this->getMetaValue("boundCoverageDiscount", 0);
        $this->lossDebit = (int)$this->getMetaValue("boundLossSurcharge", 0);

        $this->midLevelSurcharge = (int)$this->getMetaValue("boundMidLevelSurcharge", 0);
        $this->replacementCostSurcharge = (int)$this->getMetaValue("boundReplacementCostSurcharge", 0);
        $this->personalPropertyReplacementCost = (int)$this->getMetaValue("boundPersonalPropertySurcharge", 0);
        $this->dwellingReplacementCost = (int)$this->getMetaValue("boundDwellingSurcharge", 0);

        $this->finalPremium = (int)$this->getMetaValue("boundFinalPremium", 0);
        $this->taxAmount = (int)$this->getMetaValue("boundTaxAmount", 0);
        $this->discounts = $this->dwellingValueCredit + $this->deductibleCredit + $this->primaryResidentCredit;
        $this->charges = $this->lossDebit + $this->midLevelSurcharge + $this->replacementCostSurcharge + $this->personalPropertyReplacementCost + $this->dwellingReplacementCost;

        $this->finalCost = (int)$this->getMetaValue("boundTotalCost", 0);

        $this->properlyVented = ($flood_foundation_id == 1 || $flood_foundation_id == 2 || $flood_foundation_id == 4) ? "Y" : "N";

        $this->estimatedLossRent = ($this->lossRentPremium) * 1.05;
        $this->estimatedContentReplacement = $this->basePremium * $pers_prop_repl_rate * 1.05;
        $this->estimatedBuildingReplacement = $this->basePremium * $dwell_repl_cost_rate * 1.05;
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
            case '8':
                return  [
                    'building_deductible' => 1000,
                    'content_deductible' => 1000,
                    'rent_deductible' => 1000,
                ];
                break;
            case '9':
                return  [
                    'building_deductible' => 25000,
                    'content_deductible' => 5000,
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
