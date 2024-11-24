<?php

namespace App\Libraries;

use App\Services\HiscoxQuoteService;
use App\Services\StateRateService;
use Exception;

use function PHPUnit\Framework\isNull;

class HiscoxCalculations
{
    protected $floodQuote;
    protected $floodQuoteMetas;
    protected $hixcoxAPI;
    protected $hiscoxQuoteService;
    protected $stateRateService;

    public $hiscoxID;
    public $dwellingCoverage = 0;
    public $personalPopertyCoverage = 0;
    public $otherStructureCoverage = 0;
    public $lossOfUseCoverage = 0;
    public $improvementsAndBettermentsLimit = 0;
    public $businessIncomeAndExtraExpenseAnnualValue = 0;
    public $quoteOptionPremium = 0;
    public $quoteOptionDeductible = 0;
    public $basePremium = 0;
    public $baseRate = 0;
    public $deductible;
    public $basePremiumAdjustment = 0;
    public $stampFee = 0;
    public $finalTax = 0;
    public $finalCost = 0;
    public $policyFee;
    public $requiredElevated = "";
    public $requiredBasement = "";
    public $basementStatus = "";
    public $reqDwellTiv = 0;
    public $isFinishedEnclosure = "";
    public $constructionType = "";

    public function __construct($floodQuote)
    {
        helper(['service']);

        $this->floodQuote = $floodQuote;
        $this->floodQuoteMetas = getFloodQuoteMetas($this->floodQuote->flood_quote_id);
        $this->hixcoxAPI = new HiscoxApiV2();
        $this->hiscoxQuoteService = new HiscoxQuoteService();
        $this->stateRateService = new StateRateService();

        $this->setBaseValues();
    }

    private function setBaseValues()
    {
        $policyType = $this->getMetaValue("policyType");
        $isRented = (int)$this->getMetaValue("isRented", 0) == 1;
        $hiscoxDwellLimitOverride = (int)$this->getMetaValue("hiscoxDwellLimitOverride", 0);
        $hiscoxContentLimitOverride = (int)$this->getMetaValue("hiscoxContentLimitOverride", 0);
        $hiscoxOtherLimitOverride = (int)$this->getMetaValue("hiscoxOtherLimitOverride", 0);
        $hiscoxLossUseLimitOverride = (int)$this->getMetaValue("hiscoxLossUseLimitOverride", 0);
        $covDLossUse = (float)$this->getMetaValue("covDLossUse", 0);
        $additionalPremium = (int)$this->getMetaValue("additionalPremium", 0);
        $hiscoxPremiumOverride = (int)$this->getMetaValue("hiscoxPremiumOverride", 0);
        $propertyState = $this->getMetaValue("propertyState", "NJ");
        $entityType = $this->floodQuote->entity_type;

        $stateRate = $this->stateRateService->getByState($propertyState);

        $this->policyFee = $stateRate->policy_fee;

        $isEndorsement = $policyType == "END";

        if ($policyType == "CAN") {
            $prevHiscoxBoundID = $this->getMetaValue("prevHiscoxBoundID");
            $this->hiscoxID = $prevHiscoxBoundID;
        } else {
            $this->hiscoxID = $this->getMetaValue("hiscoxID");
        }

        $selectedPolicyType = $this->getMetaValue("selectedPolicyType");
        $selectedDeductible = $this->getMetaValue("selectedDeductible");
        $selectedPolicyIndex = $this->getMetaValue("selectedPolicyIndex");

        $getHiscoxQuote = $this->hiscoxQuoteService->findHiscoxQuote($this->floodQuote->flood_quote_id, $this->hiscoxID);
        if ($getHiscoxQuote) {
            $rawQuotes = $getHiscoxQuote->raw_quotes;
            $hiscoxQuote = json_decode($rawQuotes);
        } else {
            throw new Exception("Flood Quote has no existing Hiscox Quotes");
        }

        if (isset($hiscoxQuote->request) && !isNull($hiscoxQuote->request)) {
            $this->requiredElevated = $hiscoxQuote->request->elevationHeight == 0 ? "No" : "Yes";
            $this->requiredBasement = $hiscoxQuote->request->basementType == "None" ? "No" : "Yes";
            $this->basementStatus = $hiscoxQuote->request->basementType == "Finished" ? "Finished" : "";
        }

        $productResponseRequest = HiscoxApiV2::createProductResponseRequest($hiscoxQuote, $this->floodQuote, $isRented);
        $hiscoxProductResponse = $productResponseRequest["hiscoxProductResponse"];
        $primaryOptions = $hiscoxProductResponse->primary;
        $excessOptions = $hiscoxProductResponse->excess;

        $hiscoxSelectedOption = HiscoxApiV2::getHiscoxSelectedOption($selectedPolicyType, $selectedPolicyIndex, $selectedDeductible, $primaryOptions, $excessOptions, $isEndorsement);
        $hiscoxSelectedOptionIndex = $hiscoxSelectedOption['index'];
        $hiscoxOptions = $hiscoxSelectedOption['options'];

        $this->dwellingCoverage = ($hiscoxDwellLimitOverride == 0)
            ? $hiscoxOptions->building_coverage_limit
            : $hiscoxDwellLimitOverride;

        $this->personalPopertyCoverage = ($hiscoxContentLimitOverride == 0)
            ? $hiscoxOptions->contents_coverage_limit
            : $hiscoxContentLimitOverride;

        $this->otherStructureCoverage = ($hiscoxOtherLimitOverride == 0)
            ? $hiscoxOptions->other_structures_coverage_limit
            : $hiscoxOtherLimitOverride;

        if ($entityType == 0) {
            $hiscoxProductResponse = $hiscoxQuote->response->residential;
            $hiscoxProductRequest = $hiscoxQuote->request->residential;

            if (isset($hiscoxProductRequest->replacementCostValues) && !isNull($hiscoxProductRequest->replacementCostValues))
                $this->reqDwellTiv = $hiscoxProductRequest->replacementCostValues->building;

            if (isset($hiscoxProductRequest) && !isNull($hiscoxProductRequest))
                $this->constructionType = $hiscoxProductRequest->constructionType;
        } else {
            if ($isRented) {
                $hiscoxProductResponse = $hiscoxQuote->response->commercialTenanted;
                $hiscoxProductRequest = $hiscoxQuote->request->commercial->tenanted;

                if (isset($hiscoxProductRequest->replacementCostValues) && !isNull($hiscoxProductRequest->replacementCostValues))
                    $this->reqDwellTiv = $hiscoxProductRequest->replacementCostValues->improvementsAndBetterments;
            } else {
                $hiscoxProductResponse = $hiscoxQuote->response->commercialOwned;
                $hiscoxProductRequest = $hiscoxQuote->request->commercial->owned;

                if (isset($hiscoxProductRequest->replacementCostValues) && !isNull($hiscoxProductRequest->replacementCostValues))
                    $this->reqDwellTiv = $hiscoxProductRequest->replacementCostValues->building;
            }

            if (isset($hiscoxProductRequest) && !isNull($hiscoxProductRequest))
                $this->constructionType = $hiscoxProductRequest->constructionType;
        }

        $this->isFinishedEnclosure = (strpos($hiscoxQuote->request->foundation->additionalFoundationType, 'FinishedEnclosure') === 0) ? "Yes" : "No";

        if ($hiscoxLossUseLimitOverride) {
            $this->lossOfUseCoverage = $hiscoxOptions->loss_of_use_coverage_limit;

            if ($covDLossUse == 0) {
                $this->lossOfUseCoverage = 0;
            }
        } else {
            $this->lossOfUseCoverage = $hiscoxLossUseLimitOverride;
        }

        $this->improvementsAndBettermentsLimit = $hiscoxOptions->improvementsAndBettermentsLimit;
        $this->businessIncomeAndExtraExpenseAnnualValue = $hiscoxOptions->businessIncomeAndExtraExpenseAnnualValue;

        $total_premium = $hiscoxOptions->building_premium +
            $hiscoxOptions->contents_premium +
            $hiscoxOptions->other_structures_premium +
            $hiscoxOptions->loss_of_use_premium +
            $hiscoxOptions->improvementsAndBettermentsPremium +
            $hiscoxOptions->businessIncomePremium;

        $this->quoteOptionPremium = $total_premium;
        $this->quoteOptionDeductible = $hiscoxOptions->deductible;

        $this->basePremium = $total_premium;
        $this->baseRate = 0;
        $this->deductible = $hiscoxOptions->deductible;
        $this->basePremiumAdjustment = $additionalPremium / 100;

        if ($hiscoxPremiumOverride == 0) {
            if ($this->basePremiumAdjustment == 0) {
                $this->basePremium = $total_premium;
            } else {
                $premiumAdjustment = $this->quoteOptionPremium * $this->basePremiumAdjustment;
                $this->basePremium = $this->basePremium + $premiumAdjustment;
            }
        } elseif ($hiscoxPremiumOverride > 0) {
            $this->basePremium = $hiscoxPremiumOverride;
        } else {
            $this->basePremium  = $total_premium;
        }

        if ($propertyState == "PA") {
            $this->stampFee = 20;
        } else {
            $this->stampFee =  $this->basePremium * $stateRate->stamping_fee;
            $this->stampFee = round($this->stampFee, 2);
        }

        $this->finalTax =  $this->basePremium * $stateRate->tax_rate;
        $this->finalTax = round($this->finalTax, 2);

        $this->finalCost =  $this->basePremium + $this->finalTax + $this->stampFee +  $stateRate->policy_fee;
        $this->finalCost = round($this->finalCost, 2);
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
}
