<?= $this->extend('layouts/print', ['data' => $data]) ?>
<?= $this->section('content') ?>

<?php
$formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
helper(['html', 'umr']);
extract($data);

$bindAuthorityService = service('bindAuthorityService');
$countyService = service('countyService');
$constructionService = service('constructionService');
$floodZoneService = service('floodZoneService');

$entityType = $floodQuote->entity_type;
$previousPolicyNumber = getMetaValue($floodQuoteMetas, "previousPolicyNumber");
$bind_authority = (int)getMetaValue($floodQuoteMetas, 'bind_authority', 0);
$slaNumber = getMetaValue($floodQuoteMetas, 'slaNumber');
$endorseDate = getMetaValue($floodQuoteMetas, 'endorseDate');
$propertyAddress = getMetaValue($floodQuoteMetas, "propertyAddress");
$propertyCity = getMetaValue($floodQuoteMetas, "propertyCity");
$propertyState = getMetaValue($floodQuoteMetas, "propertyState");
$propertyZip = getMetaValue($floodQuoteMetas, "propertyZip");
$county_id = (int)getMetaValue($floodQuoteMetas, "propertyCounty", 0);
$flood_occupancy = (int)getMetaValue($floodQuoteMetas, "flood_occupancy", 0);
$isPrimaryResidence = (int)getMetaValue($floodQuoteMetas, "isPrimaryResidence", 0);
$construction_type = (int)getMetaValue($floodQuoteMetas, "construction_type", 0);
$flood_zone = (int)getMetaValue($floodQuoteMetas, 'flood_zone', 0);
$billTo = (int)getMetaValue($floodQuoteMetas, "billTo", 1);
$isRented = (int)getMetaValue($floodQuoteMetas, "isRented", 0);
$improvementDate = (getMetaValue($floodQuoteMetas, "improvementDate") == "") ? "" : date('m/d/Y', strtotime(getMetaValue($floodQuoteMetas, "improvementDate")));
$isCondo = (int)getMetaValue($floodQuoteMetas, "isCondo", 0);
$cancelPremium = (int)getMetaValue($floodQuoteMetas, "cancelPremium", 0);
$cancelTax = (int)getMetaValue($floodQuoteMetas, "cancelTax", 0);
$boundLossUseCoverage = (int)getMetaValue($floodQuoteMetas, "boundLossUseCoverage", 0);
$isExcessPolicy = (int)getMetaValue($floodQuoteMetas, "isExcessPolicy", 0);
$underlyingCompanyName = getMetaValue($floodQuoteMetas, "underlyingCompanyName");
$underlyingPolicyNumber = getMetaValue($floodQuoteMetas, "underlyingPolicyNumber");
$excessBuildingLimit = (int)getMetaValue($floodQuoteMetas, "excessBuildingLimit", 0);
$excessContentLimit = (int)getMetaValue($floodQuoteMetas, "excessContentLimit", 0);
$underlyingBuildLimit = (int)getMetaValue($floodQuoteMetas, "underlyingBuildLimit", 0);
$underlyingContentLimit = (int)getMetaValue($floodQuoteMetas, "underlyingContentLimit", 0);
$covCContent = (int)getMetaValue($floodQuoteMetas, "covCContent", 0);
$covDLossUse = (float)getMetaValue($floodQuoteMetas, "covDLossUse", 0);
$deductible_id = (int)getMetaValue($floodQuoteMetas, "deductible_id", 0);
$deductible = 0;
$hiscoxQuotedDeductible = (int)getMetaValue($floodQuoteMetas, "hiscoxQuotedDeductible", 0);
$hasOpprc = (int)getMetaValue($floodQuoteMetas, "hasOpprc", 0);
$yearLastLoss = getMetaValue($floodQuoteMetas, "yearLastLoss");
$boundFinalPremium = (float)getMetaValue($floodQuoteMetas, "boundFinalPremium", 0);
$boundTaxAmount = (float)getMetaValue($floodQuoteMetas, "boundTaxAmount", 0);
$boundPolicyFee = (float)getMetaValue($floodQuoteMetas, "boundPolicyFee", 0);
$boundStampFee = (float)getMetaValue($floodQuoteMetas, "boundStampFee", 0);
$boundTotalCost = (float)getMetaValue($floodQuoteMetas, "boundTotalCost", 0);
$cancelPremium = (float)getMetaValue($floodQuoteMetas, "cancelPremium", 0);
$cancelTax = (float)getMetaValue($floodQuoteMetas, "cancelTax", 0);
$proratedDue = (float)getMetaValue($floodQuoteMetas, "proratedDue", 0);
$hiscoxID = getMetaValue($floodQuoteMetas, "hiscoxID");
$boundLossUseCoverage = (float)getMetaValue($floodQuoteMetas, "boundLossUseCoverage", 0);
$policyNumber = getMetaValue($floodQuoteMetas, "policyNumber");

switch ($deductible_id) {
    case 1:
        $deductible = 2500;
        break;
    case 2:
        $deductible = 5000;
        break;
    case 3:
        $deductible = 10000;
        break;
    case 4:
        $deductible = 1500;
        break;
    case 5:
        $deductible = 2000;
        break;
    case 6:
        $deductible = 25000;
        break;
    case 7:
        $deductible = 5000;
        break;
    case 8:
        $deductible = 1000;
        break;
    default:
        break;
}

$county = $countyService->findOne($county_id);
$bindAuthority = $bindAuthorityService->findOne($bind_authority);
$construction = $constructionService->findOne($construction_type);
$floodZone = $floodZoneService->findOne($flood_zone);
$bindAuthorityText = ($bindAuthority) ? $bindAuthority->reference : "";

function getMetaValue($metas, $meta_key, $default = '')
{
    foreach ($metas as $meta) {
        if ($meta->meta_key === $meta_key) {
            if ($meta->meta_value == "" && $default != "")
                return $default;
            else
                return $meta->meta_value;
        }
    }
    return $default;
}
?>

<style>
    .signature-date {
        display: flex;
        align-items: flex-end;
        height: 100%;
    }

    .date-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-left: 20px;
    }

    .row.align-items-center {
        display: flex;
        align-items: center;
    }

    .bottom-row {
        font-size: 11px;
    }

    @media print {

        body,
        html {
            height: 100%;
        }

        .content-wrapper {
            display: flex;
            flex-direction: column;
            height: 100vh;
            box-sizing: border-box;
        }

        .broker-info {
            font-size: 10px;
        }

        .form-info {
            font-size: 10px;
        }

        h3 {
            font-size: 22px;
        }

        .bottom-row {
            margin-top: auto;
        }
    }
</style>

<div class="content-wrapper">
    <div class="row">
        <div class="col-6">
            <div class="logo mb-1">
                <img src="<?= base_url('assets/images/IACHeaderLogo.gif'); ?>" alt="logo" width="352" height="100">
            </div>
            <p><strong>Private Market Flood Insurance</strong></p>
            <p class="broker-info">
                <strong>Broker:</strong><br />
                <?= $broker->name ?><br />
                <?= $broker->address ?><br />
                <?= $broker->city ?>, <?= $broker->state ?>&nbsp;&nbsp; <?= $broker->zip ?><br />
                <?= $broker->phone ?>
            </p>
        </div>

        <div class="col-6">
            <table class="table table-borderless">
                <tr>
                    <td class="p-1">Policy Number</td>
                    <td class="p-1"><?= $policyNumber ?></td>
                    <td class="p-1">&nbsp;</td>
                </tr>
                <tr>
                    <td class="p-1">Previous Number:</td>
                    <td class="p-1"><?= $previousPolicyNumber ?></td>
                    <td class="p-1">&nbsp;</td>
                </tr>
                <tr>
                    <td class="p-1">UMR No.</td>
                    <td colspan="2" class="p-1"><?= generateUMR($floodQuote, $bindAuthority) ?></td>
                </tr>
                <tr>
                    <td class="p-1">SLA#:</td>
                    <td class="p-1" colspan="2"><?= $slaNumber ?></td>
                </tr>
                <tr>
                    <td class="p-1" colspan="2">Cover Holder Phone Number</td>
                    <td class="p-1">609-492-3162</td>
                </tr>
                <tr>
                    <td class="p-1">Policy Effective Date:</td>
                    <td class="p-1"><?= date('m/d/Y', strtotime($floodQuote->effectivity_date)) ?></td>
                    <td class="p-1">12:01 AM</td>
                </tr>
                <tr>
                    <td class="p-1">Policy Expiration Date:</td>
                    <td class="p-1"><?= date('m/d/Y', strtotime($floodQuote->expiration_date)) ?></td>
                    <td class="p-1">12:01 AM</td>
                </tr>
                <tr>
                    <td class="p-1" colspan="3"><strong>To report a Claim, please contact your Broker</strong></td>
                </tr>
                <tr>
                    <td class="p-1" colspan="3">
                        <?php if ($policyType == "END"): ?>
                            Endorsement Effective - <?= date('m/d/Y', strtotime($endorseDate)) ?>
                        <?php elseif ($policyType == "CAN"): ?>
                            Cancellation Effective - <?= date('m/d/Y', strtotime($endorseDate)) ?>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row text-center border-top border-bottom border-dark">
        <h2 class="m-0">
            <?php if ($policyType == "END"): ?>
                REVISED
            <?php elseif ($policyType == "CAN"): ?>
                CANCELLATION
            <?php endif; ?>
            FLOOD INSURANCE DECLARATION PAGE
        </h2>
        <span>
            Insurance is effective with certain UNDERWRITERS AT LLOYD'S, LONDON: 100%<br />
            Cover Holder: Insurance Agency Connection, Barnegat, NJ 08005
        </span>
    </div>
    <div class="row">
        <div class="col-6">
            <strong>Insured Name &amp; Mailing Address</strong><br />
            <?php if ($entityType == 1): ?>
                <?= $client->business_name ?><br>
                <?= $client->business_name2 ?>
            <?php else: ?>
                <?= $client->first_name ?> <?= $client->first_name ?><br>
                <?= $client->insured2_name ?>
            <?php endif; ?><br>
            <?= $client->address ?><br>
            <?= $client->city ?>,&nbsp;<?= $client->state ?>&nbsp;<?= $client->zip ?>
        </div>

        <div class="col-6">
            Property Location:<br />
            <?= $propertyAddress ?><br />
            <?= $propertyCity ?>,&nbsp;<?= $propertyState ?>&nbsp;&nbsp;<?= $propertyZip ?><br />
            County:&nbsp;&nbsp;<?= ($county) ? $county->name : "" ?>
        </div>
    </div>

    <div class="row border-top border-dark">
        <?php if (!$isExcessPolicy) : ?>
            <div class="col-3">Building Occupancy:</div>
            <div class="col-3"><?= $floodOccupancy ? $floodOccupancy->name : "" ?></div>
            <div class="col-3">Year of Construction:</div>
            <div class="col-3"><?= getMetaValue($floodQuoteMetas, "yearBuilt") ?></div>
            <div class="col-3">Primary Home:</div>
            <div class="col-3"><?= ($isPrimaryResidence == 1) ? "Y" : "N" ?></div>
            <div class="col-3">Construction Type:</div>
            <div class="col-3"><?= $construction->name ?></div>
            <div class="col-3">Building Type:</div>
            <div class="col-3">
                <?= getMetaValue($floodQuoteMetas, "numOfFloors") ?>&nbsp;Story
                <?php if ((int)getMetaValue($floodQuoteMetas, "rcbap", 0) == 1): ?>
                    - RCBAP Low Rise
                <?php elseif ((int)getMetaValue($floodQuoteMetas, "rcbap", 0) == 2): ?>
                    - RCBAP High Rise
                <?php endif; ?>
            </div>
            <div class="col-3">Square Footage:</div>
            <div class="col-3"><?= getMetaValue($floodQuoteMetas, "squareFeet", 0) ?></div>
            <div class="col-3">Flood Zone:</div>
            <div class="col-3"><?= $floodZone->name ?></div>
            <div class="col-3">Total Replacement Cost:</div>
            <div class="col-3"><?= $formatter->formatCurrency(getMetaValue($floodQuoteMetas, "buildingReplacementCost", 0), 'USD') ?></div>
            <div class="col-3">Payor:</div>
            <div class="col-3">
                <?php
                if ($billTo == 1) {
                    echo "Insured";
                } elseif ($billTo == 2) {
                    echo "Mortgagee";
                }
                ?>
            </div>
            <div class="col-3">Is the home rented?</div>
            <div class="col-3"><?= $isRented ? "Y" : "N" ?></div>
            <div class="col-3">Base Flood Elevation:</div>
            <div class="col-3"><?= getMetaValue($floodQuoteMetas, "bfe", 0) ?></div>
            <div class="col-3">Substantial Improvement Date:</div>
            <div class="col-3"><?= ($improvementDate == "") ? "&nbsp" : $improvementDate ?></div>
            <div class="col-3">Effective Rating Elevation:</div>
            <div class="col-3"><?= getMetaValue($floodQuoteMetas, "elevationDifference", 0) ?></div>
            <div class="col-3">Enclosure Sq Ft:</div>
            <div class="col-3"><?= getMetaValue($floodQuoteMetas, "squareFeet", 0) ?></div>
            <div class="col-3">First Living Floor Elevation:</div>
            <div class="col-3"><?= getMetaValue($floodQuoteMetas, "flfe", 0) ?></div>
            <?php if ($isCondo): ?>
                <div class="col-3">Condo Units:</div>
                <div class="col-3"><?= getMetaValue($floodQuoteMetas, "condoUnits", 0) ?></div>
            <?php endif; ?>
        <?php else : ?>
            <div class="col-3"><strong>Primary Insurer: </strong></div>
            <div class="col-3"><?= $underlyingCompanyName ?></div>
            <div class="col-3">Policy #:</div>
            <div class="col-3"><?= $underlyingPolicyNumber ?></div>

            <div class="col-3">&nbsp;</div>
            <div class="col-3">&nbsp;</div>
            <div class="col-3">Building Limit:</div>
            <div class="col-3"><?= $formatter->formatCurrency($excessBuildingLimit, 'USD') ?></div>

            <div class="col-3">&nbsp;</div>
            <div class="col-3">&nbsp;</div>
            <div class="col-3">Contents Limit:</div>
            <div class="col-3"><?= $formatter->formatCurrency($excessContentLimit, 'USD') ?></div>

            <div class="col-3">&nbsp;</div>
            <div class="col-3">&nbsp;</div>
            <div class="col-3">Deductibles:</div>
            <div class="col-3"><?= $formatter->formatCurrency($underlyingBuildLimit, 'USD') ?> / <?= $formatter->formatCurrency($underlyingContentLimit, 'USD') ?></div>

            <div class="col-3">Flood Zone:</div>
            <div class="col-3"><?= $floodZone->name ?></div>
            <?php if ($isCondo): ?>
                <div class="col-3">Condo Units:</div>
                <div class="col-3"><?= getMetaValue($floodQuoteMetas, "condoUnits", 0) ?></div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <div class="row border border-dark">
        <div class="col-12">
            <div class="row justify-content-end">
                <div class="col-3 text-center">
                    <strong>Coverage Type</strong><br />

                    <?php if ($entityType == 0) : ?>
                        Building<br />
                        Contents<br />
                        Loss of Use/Rents<br />
                        Other Structures
                    <?php else : ?>
                        <?php if ($isRented == 0) : ?>
                            Contents<br />
                            Improvements<br />
                            BI & Extra Expense
                        <?php else : ?>
                            Building<br />
                            Contents<br />
                            BI & Extra Expense
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="col-3 text-center">
                    <strong>Amount</strong><br />
                    <?php if ($entityType == 0) : ?>
                        <?php
                        if ($isExcessPolicy == 1) {
                            echo $formatter->formatCurrency(getMetaValue($floodQuoteMetas, "covABuilding", 0), 'USD');
                        } else {
                            echo $formatter->formatCurrency($calculations->dwellingCoverage, 'USD');
                        }
                        ?><br />
                        <?php
                        if ($isExcessPolicy == 1) {
                            echo $formatter->formatCurrency($covCContent, 'USD');
                        } else {
                            echo $formatter->formatCurrency($calculations->personalPopertyCoverage, 'USD');
                        }
                        ?><br />
                        <?php
                        if ($isExcessPolicy == 1) {
                            echo $formatter->formatCurrency($covDLossUse, 'USD');
                        } else {
                            echo $formatter->formatCurrency($calculations->lossOfUseCoverage, 'USD');
                        }
                        ?><br />

                        <?php
                        if ($isExcessPolicy == 1) {
                            echo "N/A";
                        } else {
                            echo $formatter->formatCurrency($calculations->otherStructureCoverage, 'USD');
                        }
                        ?>
                    <?php else : ?>
                        <?php if ($isRented == 0) : ?>
                            <?php
                            if ($isExcessPolicy == 1) {
                                echo $formatter->formatCurrency($covCContent, 'USD');
                            } else {
                                echo $formatter->formatCurrency($calculations->personalPopertyCoverage, 'USD');
                            }
                            ?><br />
                            <?= $formatter->formatCurrency($calculations->improvementsAndBettermentsLimit, 'USD') ?><br />
                            <?= $formatter->formatCurrency($calculations->businessIncomeAndExtraExpenseAnnualValue, 'USD') ?>
                        <?php else : ?>
                            <?php
                            if ($isExcessPolicy == 1) {
                                echo $formatter->formatCurrency(getMetaValue($floodQuoteMetas, "covABuilding", 0), 'USD');
                            } else {
                                echo $formatter->formatCurrency($calculations->dwellingCoverage, 'USD');
                            }
                            ?><br />
                            <?php
                            if ($isExcessPolicy == 1) {
                                echo $formatter->formatCurrency($covCContent, 'USD');
                            } else {
                                echo $formatter->formatCurrency($calculations->personalPopertyCoverage, 'USD');
                            }
                            ?><br />
                            <?= $formatter->formatCurrency($calculations->businessIncomeAndExtraExpenseAnnualValue, 'USD') ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="col-3 text-center">
                    <strong>
                        <?= $isExcessPolicy == 1 ? "Excess Over" : "Deductible" ?>
                    </strong><br />

                    <?php if ($entityType == 0) : ?>
                        <?php
                        if ($isExcessPolicy == 1) {
                            echo ($flood_occupancy == 4) ? $formatter->formatCurrency(500000, 'USD') : $formatter->formatCurrency(250000, 'USD');
                        } else {
                            if ($policyType == "CAN") {
                                echo $formatter->formatCurrency($deductible, 'USD');
                            } else {
                                echo $formatter->formatCurrency($hiscoxQuotedDeductible, 'USD');
                            }
                        }
                        ?>
                    <?php else : ?>
                        <?php if ($isRented) : ?>
                            <?php
                            if ($isExcessPolicy == 1) {
                                echo ($flood_occupancy == 4) ? $formatter->formatCurrency(500000, 'USD') : $formatter->formatCurrency(250000, 'USD');
                            } else {
                                if ($policyType == "CAN") {
                                    echo $formatter->formatCurrency($deductible, 'USD');
                                } else {
                                    echo $formatter->formatCurrency($hiscoxQuotedDeductible, 'USD');
                                }
                            }
                            ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php if ($isExcessPolicy == 0) : ?>
            <div class="col-6 border-top border-end  border-dark">
                <table class="table table-borderless m-0">
                    <tr>
                        <td class="p-0">Primary Discount</td>
                        <td class="p-0 text-end"><?= ($isPrimaryResidence) ? "Included" : "N/A" ?></td>
                    </tr>
                    <tr>
                        <td class="p-0">Contents RC Coverage</td>
                        <td class="p-0 text-end"><?= ($hasOpprc) ? "Included" : "N/A" ?></td>
                    </tr>
                    <tr>
                        <td class="p-0">Prior Loss Adjustment</td>
                        <td class="p-0 text-end"><?= ($yearLastLoss == "") ? "N/A" : "Y" ?></td>
                    </tr>
                </table>
            </div>
        <?php else: ?>
            <div class="col-6">&nbsp;</div>
        <?php endif; ?>

        <div class="col-6">
            <table class="table table-borderless m-0">
                <tr>
                    <td class="p-1">Total Base Premium:</td>
                    <td class="p-1 text-end">
                        <?= $formatter->formatCurrency($boundFinalPremium, 'USD') ?>
                    </td>
                </tr>

                <tr>
                    <td class="p-1"><?= $propertyState ?> Surplus Lines Tax:</td>
                    <td class="p-1 text-end">
                        <?= $formatter->formatCurrency($boundTaxAmount, 'USD') ?>
                    </td>
                </tr>

                <tr>
                    <td class="p-1">Policy Fee:</td>
                    <td class="p-1 text-end">
                        <?=
                        ($policyType == "END")
                            ? $formatter->formatCurrency(0, 'USD')
                            : $formatter->formatCurrency($boundPolicyFee, 'USD')
                        ?>
                    </td>
                </tr>

                <tr>
                    <td class="p-1">
                        <?php if ($propertyState == "NY" || $propertyState == "PA" || $propertyState == "TX") : ?>
                            <?= $propertyState . " Stamping Fee:" ?>
                        <?php endif; ?>
                    </td>
                    <td class="p-1 text-end">
                        <?php if ($propertyState == "NY" || $propertyState == "PA" || $propertyState == "TX") : ?>
                            <?= $formatter->formatCurrency($boundStampFee, 'USD') ?>
                        <?php endif; ?>
                    </td>
                </tr>

                <tr>
                    <td class="p-1"><strong>Grand Total:</strong></td>
                    <td class="p-1 text-end">
                        <?= $formatter->formatCurrency($boundTotalCost, 'USD') ?>
                    </td>
                </tr>
                <tr>
                    <td class="p-1 text-end" colspan="2">
                        <?php if ($policyType == "END" && $cancelPremium != 0): ?>
                            Endorsement Premium: <?= $formatter->formatCurrency($cancelPremium, 'USD') ?>
                            Endorsement Tax: <?= $formatter->formatCurrency($cancelTax, 'USD') ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td class="p-1 text-end" colspan="2">
                        <strong>
                            <?php if ($policyType == "END" && $cancelPremium != 0): ?>
                                Total Endorsement Due: <?= $formatter->formatCurrency($proratedDue, 'USD') ?>
                            <?php endif; ?>
                        </strong>
                    </td>
                </tr>
                <tr>
                    <td class="p-1 text-end" colspan="2"><strong>THIS IS NOT A BILL</strong></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
        <table class="table table-borderless mb-0">
            <tr>
                <td colspan="2" class="border-start border-end border-dark">
                    <p class="mb-0">
                        First Mortgagee:<br />
                        <?= $mortgage1->name ?><br />
                        <?= $mortgage1->name2 ?><br />
                        <?= $mortgage1->address ?><br />
                        <?= $mortgage1->city ?>, <?= $mortgage1->state ?>&nbsp;&nbsp;<?= $mortgage1->zip ?><br />
                        <?= $mortgage1->phone ?>
                    </p>
                </td>
                <td colspan="2" class="border-end border-dark">
                    <p class="mb-0">
                        Second Mortgagee:<br />
                        <?= $mortgage2->name ?><br />
                        <?= $mortgage2->name2 ?><br />
                        <?= $mortgage2->address ?><br />
                        <?= $mortgage2->city ?>, <?= $mortgage2->state ?>&nbsp;&nbsp;<?= $mortgage2->zip ?><br />
                        <?= $mortgage2->phone ?>
                    </p>
                </td>
            </tr>
            <tr>
                <td class="border-start border-bottom border-dark">Loan #</td>
                <td class="border-bottom border-end border-dark"><?= $mortgage1->loan_number ?></td>
                <td class="border-bottom border-dark">Loan #</td>
                <td class="border-bottom border-end border-dark"><?= $mortgage2->loan_number ?></td>
            </tr>
        </table>
    </div>

    <div class="row align-items-center">
        <div class="col-6">
            Service of Suit may be made upon:<br />
            Lloyd's America, Inc.<br />
            Attention: Legal department <br />
            280 Park Avenue, East Tower, 25th Floor<br>
            New York, NY 10017
        </div>
        <div class="col-6 signature-date">
            <img src="<?= base_url('assets/images/jrwsig.jpg'); ?>" name="signbox" width="244" height="70" />
            <div class="date-container">
                <div class="border-bottom border-dark"><?= date('m/d/Y', strtotime($floodQuote->bound_date)) ?></div>
                <div>Date</div>
            </div>
        </div>
    </div>
</div>

<div class="content-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="logo mb-1">
                <img src="<?= base_url('assets/images/IACHeaderLogo.gif'); ?>" alt="logo" width="352" height="100">
            </div>
        </div>
    </div>

    <div class="row text-center border-top border-dark">
        <h2 class="m-0">
            <?= $isExcessPolicy == 1 ? "EXCESS FLOOD" : "FLOOD INSURANCE" ?> DECLARATIONS PAGE (continued)
        </h2>
    </div>

    <div class="row">
        <div class="col-12">
            <p><strong><em>This policy meets the definition of private flood insurance contained in 42 U.S.C. 4012a(b)(7) and the corresponding regulation.</em></strong></p>
            <p>The following applicable forms and endorsements may be included with this policy:</p>
            <p>
                <?php if ($isExcessPolicy == 1) {
                    echo "Excess Flood Wording, as attached <br>";
                } ?>
                Hiscox Homeowners FloodPlus Form(s), As Attached<br />
                LSW1135b Lloyd's Privacy Policy Statement (in respect of personal lines business only)<br />
                LMA 3100 Sanction Limitation and Exclusion Clause<br />
                LMA 9063 New Jersey Surplus Lines Notice<br />
                LMA 9064 New Jersey Surplus Lines Disclosure Notice<br />
                NMA 1998 amended - Service of Suit Clause (USA)<br />
                <?php if ($boundLossUseCoverage > 0) {
                    echo "Loss of Use Extension";
                } ?></p>
        </div>
    </div>

    <div class="row bottom-row">
        <div class="col-12">
            This policy is written by a surplus lines insurer and is not subject to the filing or approval requirements of the New Jersey Department of Banking and Insurance. Such a policy may contain conditions, limitations, exclusions, and different terms than a policy issued by an insurer granted a Certificate of Authority by the New Jersey Department of Banking and Insurance. The insurer has been approved by the Department as an eligible surplus lines insurer, but the policy is not covered by the New Jersey Insurance Guaranty Fund, and only a policy of medical malpractice liability insurance as defined in N.J.S.A. 17:30D‐3d or a policy of property insurance covering owner‐occupied dwellings of less than four dwelling units are covered by the New Jersey Surplus Lines Guaranty Fund. (N.J.A.C. 11:1‐33App.)<br />
            <?php echo $hiscoxID; ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>