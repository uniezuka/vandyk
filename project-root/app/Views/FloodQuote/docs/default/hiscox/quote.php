<?= $this->extend('layouts/print', ['data' => $data]) ?>
<?= $this->section('content') ?>

<?php
$formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
helper('html');
extract($data);

$invoiceTitle = "";
$isExcessPolicy = (int)getMetaValue($floodQuoteMetas, "isExcessPolicy", 0);
$billTo = (int)getMetaValue($floodQuoteMetas, "billTo", 1);
$entityType = $floodQuote->entity_type;
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
$isRented = (int)getMetaValue($floodQuoteMetas, "isRented", 0) == "1";

$isProperlyVented = ($flood_foundation == 1 || $flood_foundation == 2 || $flood_foundation == 4) ? "Y" : "N";

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
        <img src="<?= base_url('assets/images/IACHeaderLogo.gif'); ?>" alt="logo" class="img-fluid" width="352" height="100">
        <p>
            <strong class="title mt-3">
                Private Market Flood Insurance<br />
                Personal Flood Quote
            </strong>
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
                        <?php if ($entityType == 1): ?>
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
                                <div>Elevation Status: <?= $calculations->requiredElevated ?></div>
                                <div>Basement: <?= ($flood_foundation == 12) ? "Yes" : "No" ?></div>
                                <div>Elevation Difference: <?= ($calculations->requiredElevated == "Yes" ? getMetaValue($floodQuoteMetas, "elevationDifference", 0) : "N/A") ?></div>
                            </div>
                            <div class="col-md-6">
                                <div>Occupancy: <?= $floodOccupancy ? $floodOccupancy->name : "" ?></div>
                                <div># of Stories: <?= getMetaValue($floodQuoteMetas, "numOfFloors", 0) ?></div>
                                <div>Replacement Cost: <?= $calculations->reqDwellTiv ?></div>
                                <div>Finished Enclosure: <?= $calculations->isFinishedEnclosure ?></div>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="row mt-1">
        <?php if ($entityType == 0) { ?>
            <div class="col-6">
                <div class="d-inline-block" style="width: 250px;">
                    <strong>Building Coverage</strong>
                </div>
                <div class="d-inline-block">
                    <strong>
                        <?= $formatter->formatCurrency($calculations->dwellingCoverage, 'USD') ?>
                    </strong>
                </div>
            </div>

            <div class="col-6">
                <div class="d-inline-block" style="width: 250px;">
                    <strong>Building Deductible</strong>
                </div>
                <div class="d-inline-block">
                    <strong>
                        <?= $formatter->formatCurrency($calculations->quoteOptionDeductible, 'USD') ?>
                    </strong>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="d-inline-block" style="width: 250px;">
                        <strong>Contents Coverage</strong>
                    </div>
                    <div class="d-inline-block">
                        <strong>
                            <?= $formatter->formatCurrency($calculations->personalPopertyCoverage, 'USD') ?>
                        </strong>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="d-inline-block" style="width: 250px;">
                        <strong>Loss of Use/Rent Coverage</strong>
                    </div>
                    <div class="d-inline-block">
                        <strong>
                            <?= $formatter->formatCurrency($calculations->lossOfUseCoverage, 'USD') ?>
                        </strong>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="d-inline-block" style="width: 250px;">
                        <strong>Other Structures Coverage</strong>
                    </div>
                    <div class="d-inline-block">
                        <strong>
                            <?= $formatter->formatCurrency($calculations->otherStructureCoverage, 'USD') ?>
                        </strong>
                    </div>
                </div>
            </div>
            <?php
        } else {
            if ($isRented) {
            ?>
                <div class="col-6">
                    <div class="d-inline-block" style="width: 250px;">
                        <strong>Contents Coverage</strong>
                    </div>
                    <div class="d-inline-block">
                        <strong>
                            <?= $formatter->formatCurrency($calculations->personalPopertyCoverage, 'USD') ?>
                        </strong>
                    </div>
                </div>

                <div class="col-6">
                    <div class="d-inline-block" style="width: 250px;">
                        <strong>Deductible</strong>
                    </div>
                    <div class="d-inline-block">
                        <strong>
                            <?= $formatter->formatCurrency($calculations->quoteOptionDeductible, 'USD') ?>
                        </strong>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="d-inline-block" style="width: 250px;">
                            <strong>Improvements</strong>
                        </div>
                        <div class="d-inline-block">
                            <strong>
                                <?= $formatter->formatCurrency($calculations->improvementsAndBettermentsLimit, 'USD') ?>
                            </strong>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="d-inline-block" style="width: 250px;">
                            <strong>BI & Extra Expense</strong>
                        </div>
                        <div class="d-inline-block">
                            <strong>
                                <?= $formatter->formatCurrency($calculations->businessIncomeAndExtraExpenseAnnualValue, 'USD') ?>
                            </strong>
                        </div>
                    </div>
                </div>
            <?php } else { ?>
                <div class="col-6">
                    <div class="d-inline-block" style="width: 250px;">
                        <strong>Building Coverage</strong>
                    </div>
                    <div class="d-inline-block">
                        <strong>
                            <?= $formatter->formatCurrency($calculations->dwellingCoverage, 'USD') ?>
                        </strong>
                    </div>
                </div>

                <div class="col-6">
                    <div class="d-inline-block" style="width: 250px;">
                        <strong>Building Deductible</strong>
                    </div>
                    <div class="d-inline-block">
                        <strong>
                            <?= $formatter->formatCurrency($calculations->quoteOptionDeductible, 'USD') ?>
                        </strong>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="d-inline-block" style="width: 250px;">
                            <strong>Contents Coverage</strong>
                        </div>
                        <div class="d-inline-block">
                            <strong>
                                <?= $formatter->formatCurrency($calculations->personalPopertyCoverage, 'USD') ?>
                            </strong>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="d-inline-block" style="width: 250px;">
                            <strong>BI & Extra Expense</strong>
                        </div>
                        <div class="d-inline-block">
                            <strong>
                                <?= $formatter->formatCurrency($calculations->businessIncomeAndExtraExpenseAnnualValue, 'USD') ?>
                            </strong>
                        </div>
                    </div>
                </div>
        <?php
            }
        } ?>
    </div>

    <div class="row mt-4">
        <div class="col-6"></div>

        <div class="col-6">
            <div class="row">
                <div class="col-6">Total Base Premium=</div>
                <div class="col-6"><?= $formatter->formatCurrency($calculations->basePremium, 'USD') ?></div>
            </div>

            <div class="row">
                <div class="col-6"><?= $propertyState ?> State Tax=</div>
                <div class="col-6"><?= $formatter->formatCurrency($calculations->finalTax, 'USD') ?></div>
            </div>

            <div class="row">
                <div class="col-6">Policy Fee=</div>
                <div class="col-6"><?= $formatter->formatCurrency($calculations->policyFee, 'USD') ?></div>
            </div>

            <div class="row">
                <div class="col-6">
                    <?php if ($propertyState == "NY" || $propertyState == "PA" || $propertyState == "TX" || $propertyState == "NC") : ?>
                        <?= $propertyState . " Stamping Fee" ?>
                    <?php endif; ?>
                </div>
                <div class="col-6">
                    <?php if ($propertyState == "NY" || $propertyState == "PA" || $propertyState == "TX") : ?>
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

    <div class="mt-4">
        <p>**This is a quote only, subject to underwriting approval within 30 days</p>
    </div>
</div>

<?= $this->endSection() ?>