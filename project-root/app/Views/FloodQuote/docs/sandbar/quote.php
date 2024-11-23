<?= $this->extend('layouts/print', ['data' => $data]) ?>
<?= $this->section('content') ?>

<?php
$formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
helper('html');
extract($data);

$invoiceTitle = "";
$isExcessPolicy = (int)getMetaValue($floodQuoteMetas, "isExcessPolicy", 0);
$billTo = (int)getMetaValue($floodQuoteMetas, "billTo", 1);
$entityType = getMetaValue($floodQuoteMetas, "entityType");
$propertyAddress = getMetaValue($floodQuoteMetas, "propertyAddress");
$propertyCity = getMetaValue($floodQuoteMetas, "propertyCity");
$propertyState = getMetaValue($floodQuoteMetas, "propertyState");
$propertyZip = getMetaValue($floodQuoteMetas, "propertyZip");
$flood_occupancy = (int)getMetaValue($floodQuoteMetas, "flood_occupancy", 0);
$excessBuildingLimit = (int)getMetaValue($floodQuoteMetas, "excessBuildingLimit", 0);
$underlyingContentLimit = (int)getMetaValue($floodQuoteMetas, "underlyingContentLimit", 0);
$flood_foundation = (int)getMetaValue($floodQuoteMetas, "flood_foundation", 0);
$underlyingBuildLimit = (int)getMetaValue($floodQuoteMetas, "underlyingBuildLimit", 0);
$excessContentLimit = (int)getMetaValue($floodQuoteMetas, "excessContentLimit", 0);
$covCContent = (int)getMetaValue($floodQuoteMetas, "covCContent", 0);
$hasOpprc = (int)getMetaValue($floodQuoteMetas, "hasOpprc", 0);
$isPrimaryResidence = (int)getMetaValue($floodQuoteMetas, "isPrimaryResidence", 0);
$isCondo = (int)getMetaValue($floodQuoteMetas, "isCondo", 0);
$hasDrc = (int)getMetaValue($floodQuoteMetas, "hasDrc", 0);

$isProperlyVented = ($flood_foundation == 1 || $flood_foundation == 2 || $flood_foundation == 4) ? "Y" : "N";

$lossUseCov = $calculations->lossUseCoverage;

$deductibles = $calculations->getDeductibles();

$mle = $calculations->getMetaValue("mle", "0");
$mle = ($mle == "0") ? "N/A" : $mle;

switch ($policyType) {
    case "REN":
        $invoiceTitle = "Renewal Invoice";
        break;

    case "END":
        $invoiceTitle = "Endorsement Invoice";
        break;

    default:
        $invoiceTitle = "New Business Invoice";
        break;
}

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
    .table-bordered td,
    .table-bordered th {
        border: 1px solid #000 !important;
    }

    @media print {

        table>thead>tr>th,
        table>tbody>tr>td {
            font-size: 12px;
        }

        .building-info {
            display: flex;
            flex-wrap: wrap;
        }

        .building-info>div {
            flex: 1 1 50%;
            box-sizing: border-box;
        }
    }
</style>

<div class="content-wrapper">
    <div class="text-center mt-4">
        <img src="<?= base_url('assets/images/sandbarLogo100x270.png'); ?>" alt="logo" class="img-fluid">
        <p>
            <strong class="title mt-3">Private Market Flood Quote</strong><br />
            Provided by Insurance Agency Connection<br />
            <?= ($isExcessPolicy) ? "Excess Quote" : "" ?>
        </p>
        <p class="text-end fw-bold my-0">
            Quote #: FLD000<?= $floodQuote->flood_quote_id ?>
        </p>
    </div>

    <div class="row mt-1">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <td>
                        <div class="fw-bold">Agency Information:</div>
                        <?= $broker->name ?><br>
                        <?= $broker->address ?><br>
                        <?= $broker->city ?>, <?= $broker->state ?> &nbsp;&nbsp;<?= $broker->zip ?><br>
                        <?= $broker->phone ?>
                    </td>
                    <td>
                        <div class="fw-bold">Insured Name & Mailing Address</div>
                        <?php if ($entityType == "1"): ?>
                            <?= $client->business_name ?><br>
                            <?= $client->business_name2 ?>
                        <?php else: ?>
                            <?= $client->first_name ?> <?= $client->last_name ?><br>
                            <?= $client->insured2_name ?>
                        <?php endif; ?><br />
                        <?= $client->address ?><br />
                        <?= $client->city ?>, <?= $client->state ?>&nbsp;<?= $client->zip ?><br />
                        <br />
                        Home: <?= $client->home_phone ?><br />
                        Cell: <?= $client->cell_phone ?><br />
                        Email: <?= $client->email ?>
                    </td>
                    <td>
                        <div class="fw-bold">Location Address:</div>
                        <?= $propertyAddress ?><br />
                        <?= $propertyCity ?>, <?= $propertyState ?>&nbsp;&nbsp;<?= $propertyZip ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <div class="fw-bold">Building Information</div>
                        <div class="row building-info">
                            <div class="col-md-6">
                                <div>Flood Zone: <?= $floodZone ?></div>
                                <div>BFE: <?= getMetaValue($floodQuoteMetas, "bfe", 0) ?></div>
                                <div>Lowest Living Floor: <?= getMetaValue($floodQuoteMetas, "lfe", 0) ?></div>
                                <div>Elevation Difference: <?= getMetaValue($floodQuoteMetas, "elevationDifference", 0) ?></div>
                            </div>
                            <div class="col-md-6">
                                <div>Occupancy: <?= $floodOccupancy ? $floodOccupancy->name : "" ?></div>
                                <div>Building Diagram #: <?= getMetaValue($floodQuoteMetas, "diagramNumber") ?></div>
                                <div>Properly Vented: <?= $isProperlyVented ?></div>
                                <div>Mid Level Entry Elev: <?= $mle ?></div>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="row mt-1">
        <div class="col-6">
            <div class="d-inline-block" style="width: 250px;">
                <strong>Building Coverage</strong>
            </div>
            <div class="d-inline-block">
                <strong>
                    <?php if ($isExcessPolicy == 1): ?>
                        <?php if ($excessBuildingLimit > 0): ?>
                            <?= $formatter->formatCurrency($excessBuildingLimit, 'USD') ?>
                        <?php else: ?>
                            <?= $formatter->formatCurrency(getMetaValue($floodQuoteMetas, "covABuilding", 0), 'USD') ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <?= $formatter->formatCurrency(getMetaValue($floodQuoteMetas, "covABuilding", 0), 'USD') ?>
                    <?php endif; ?>
                </strong>
            </div>
        </div>

        <div class="col-6">
            <div class="d-inline-block" style="width: 250px;">
                <strong>
                    <?php if ($isExcessPolicy == 1): ?>
                        Underlying Limits
                    <?php else: ?>
                        Building Deductible
                    <?php endif; ?>
                </strong>
            </div>
            <div class="d-inline-block">
                <strong>
                    <?php if ($isExcessPolicy == 1): ?>
                        <?php if ($underlyingBuildLimit > 0): ?>
                            <?= $formatter->formatCurrency($underlyingBuildLimit, 'USD') ?>
                        <?php else: ?>
                            <?php if ($flood_occupancy == 4): ?>
                                <?= $formatter->formatCurrency(500000, 'USD') ?>
                            <?php else: ?>
                                <?= $formatter->formatCurrency(250000, 'USD') ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <?= $formatter->formatCurrency($deductibles["building_deductible"], 'USD') ?>
                    <?php endif; ?>
                </strong>
            </div>
        </div>

        <div class="col-6">
            <div class="d-inline-block" style="width: 250px;">
                <strong>Contents Coverage</strong>
            </div>
            <div class="d-inline-block">
                <strong>
                    <?php if ($isExcessPolicy == 1): ?>
                        <?php if ($excessContentLimit > 0): ?>
                            <?= $formatter->formatCurrency($excessContentLimit, 'USD') ?>
                        <?php endif; ?>
                    <?php else: ?>
                        <?php if ($covCContent > 0): ?>
                            <?= $formatter->formatCurrency($covCContent, 'USD') ?>
                        <?php else: ?>
                            "N/A"
                        <?php endif; ?>
                    <?php endif; ?>
                </strong>
            </div>
        </div>

        <div class="col-6">
            <div class="d-inline-block" style="width: 250px;">
                <strong>
                    <?php if ($isExcessPolicy == 1): ?>
                        Underlying Limits
                    <?php else: ?>
                        Contents Deductible
                    <?php endif; ?>
                </strong>
            </div>
            <div class="d-inline-block">
                <strong>
                    <?php if ($isExcessPolicy == 1): ?>
                        <?php if ($underlyingContentLimit > 0): ?>
                            <?= $formatter->formatCurrency($underlyingContentLimit, 'USD') ?>
                        <?php else: ?>
                            "N/A"
                        <?php endif; ?>
                    <?php else: ?>
                        <?= $formatter->formatCurrency($deductibles["content_deductible"], 'USD') ?>
                    <?php endif; ?>
                </strong>
            </div>
        </div>

        <div class="col-6">
            <div class="d-inline-block" style="width: 250px;">
                <strong>
                    <?php if ($isExcessPolicy == 1): ?>
                        Business Interruption
                    <?php else: ?>
                        Loss of Use/Rent Coverage
                    <?php endif; ?>
                </strong>
            </div>
            <div class="d-inline-block">
                <strong>
                    <?php if ($isExcessPolicy != 1): ?>
                        <?php if ($lossUseCov == 0): ?>
                            N/A
                        <?php else: ?>
                            <?= $formatter->formatCurrency($lossUseCov, 'USD') ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </strong>
            </div>
        </div>

        <div class="col-6">
            <div class="d-inline-block" style="width: 250px;">
                <strong>
                    <?php if ($isExcessPolicy != 1): ?>
                        Loss of Use/Rent Deductible
                    <?php endif; ?>
                </strong>
            </div>
            <div class="d-inline-block">
                <strong>
                    <?php if ($isExcessPolicy != 1): ?>
                        <?php if ($lossUseCov == 0): ?>
                            N/A
                        <?php else: ?>
                            <?= $formatter->formatCurrency($deductibles["rent_deductible"], 'USD') ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </strong>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-6"></div>

        <div class="col-6">
            <div class="row">
                <div class="col-6">Total Base Premium=</div>
                <div class="col-6"><?= $formatter->formatCurrency($calculations->finalPremium, 'USD') ?></div>
            </div>

            <div class="row">
                <div class="col-6"><?= $propertyState ?> State Tax=</div>
                <div class="col-6"><?= $formatter->formatCurrency($calculations->taxAmount, 'USD') ?></div>
            </div>

            <div class="row">
                <div class="col-6">Policy Fee=</div>
                <div class="col-6"><?= $formatter->formatCurrency($calculations->policyFee, 'USD') ?></div>
            </div>

            <div class="row">
                <div class="col-6">
                    <?php if ($propertyState == "NY" || $propertyState == "PA" || $propertyState == "TX" || $propertyState == "NC") : ?>
                        <?= $propertyStat . " Stamping Fee" ?>
                    <?php endif; ?>
                </div>
                <div class="col-6">
                    <?php if ($propertyState == "NY" || $propertyState == "PA" || $propertyState == "TX" || $propertyState == "NC") : ?>
                        <?= $formatter->formatCurrency($calculations->stampFee, 'USD') ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row">
                <div class="col-6"><strong>Total Premium=</strong></div>
                <div class="col-6">
                    <strong><?= $formatter->formatCurrency($calculations->finalCost, 'USD') ?></strong>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-6">
            <?php if ($isExcessPolicy == 1): ?>
                <p>**The Company reserves the right to reject applicants, add a Named Storm Exclusion, or modify premiums at any time prior to agreeing to bind coverage.<br>
                    **This proposal is subject to underwriting approval, only valid within 30 days
                </p>
                <p>**Minimum Earned Premium on Excess Lines: Greater of $500 or 50% of Gross Written Premium<br>
                    **Excess premiums listed are inclusive of all required NJ taxes and fees, as well as service fees</p>
            <?php else: ?>
                Optional additional coverages:

                <div class="row">
                    <div class="col-6">Replacement Cost Contents</div>
                    <div class="col-6">
                        <?php if ($covCContent == 0): ?>
                            N/A
                        <?php elseif ($hasOpprc != 1): ?>
                            <?= $formatter->formatCurrency($calculations->estimatedContentReplacement, 'USD') ?>
                        <?php else: ?>
                            Included
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">Replacement Cost Building</div>
                    <div class="col-6">
                        <?php if ($isPrimaryResidence == 1 || $isCondo == 1): ?>
                            Included
                        <?php elseif ($hasDrc == 1): ?>
                            <?= $formatter->formatCurrency($calculations->estimatedBuildingReplacement, 'USD') ?>
                        <?php else: ?>
                            Included
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">Loss of Use/Rents ($20,000)</div>
                    <div class="col-6">
                        <?php if ($lossUseCov != 0): ?>
                            Included
                        <?php else: ?>
                            <?= $formatter->formatCurrency($calculations->estimatedLossRent, 'USD') ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-4">
        <p>**This is a quote only, subject to underwriting approval within 30 days</p>
    </div>
</div>

<?= $this->endSection() ?>