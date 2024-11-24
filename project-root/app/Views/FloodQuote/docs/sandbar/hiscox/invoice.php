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
$underlyingBuildLimit = (int)getMetaValue($floodQuoteMetas, "underlyingBuildLimit", 0);
$underlyingContentLimit = (int)getMetaValue($floodQuoteMetas, "underlyingContentLimit", 0);
$isRented = (int)getMetaValue($floodQuoteMetas, "isRented", 0) == "1";

switch ($policyType) {
    case "REN":
        $invoiceTitle = "RENEWAL Business Invoice";
        break;

    case "END":
        $invoiceTitle = "REVISED Business Invoice";
        break;

    case "CAN":
        $invoiceTitle = "CANCELLATION Business Invoice";
        break;

    default:
        $invoiceTitle = "NEW Business Invoice";
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

<div class="content-wrapper">
    <div class="row">
        <div class="col-6">
            <div class="logo">
                <img src="<?= base_url('assets/images/sandbarLogo100x270.png'); ?>" alt="logo" width="202" height="75">
            </div>
            <div>
                <strong>609-492-4224</strong>
            </div>
        </div>

        <div class="col-6 text-center">
            <h3 class="mt-3">
                <?= $invoiceTitle ?>
            </h3>
            <p>Invoice # : <?= $floodQuote->flood_quote_id ?></p>
        </div>
    </div>

    <hr class="my-2 grey-line">

    <div class="row">
        <div class="col-6">
            <strong>Payor: </strong>
            <blockquote>
                <p>
                    <?php if ($billTo == 1) : ?>
                        <?php if ($entityType == "1") : ?>
                            <?= $client->business_name ?><br>
                            <?= $client->business_name2 ?><br>
                        <?php else : ?>
                            <?= $client->first_name ?> <?= $client->last_name ?><br>
                            <?= $client->insured2_name ?><br>
                        <?php endif; ?>
                        <?= $client->address ?><br>
                        <?= $client->city ?>, <?= $client->state ?> <?= $client->zip ?>
                    <?php else : ?>
                        <?= $mortgage1->name ?><br>
                        <?= $mortgage1->name2 ?><br>
                        <?= $mortgage1->address ?><br>
                        <?= $mortgage1->city ?>, <?= $mortgage1->state ?> <?= $mortgage1->zip ?>
                    <?php endif; ?>
                </p>
            </blockquote>
        </div>

        <div class="col-6">
            <strong>Insured Name &amp; Address (if different):</strong>
            <blockquote>
                <p>
                    <?php if ($billTo == 1) : ?>
                        Same as Payor
                    <?php else : ?>
                        <?php if ($entityType == "1") : ?>
                            <?= $client->business_name ?><br>
                            <?= $client->business_name2 ?><br>
                        <?php else : ?>
                            <?= $client->first_name ?> <?= $client->last_name ?><br>
                            <?= $client->insured2_name ?><br>
                        <?php endif; ?>
                        <?= $client->address ?><br>
                        <?= $client->city ?>, <?= $client->state ?> <?= $client->zip ?>
                    <?php endif; ?>
                </p>
            </blockquote>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-6">
            <strong>Send Checks payable to:</strong>
            <blockquote>
                <p>Insurance Agency Connection<br />
                    Attn: Payment Center<br>
                    12800 Long Beach Blvd.<br>
                    Beach Haven Terrace, NJ 08008<br>
                </p>
            </blockquote>
        </div>
    </div>

    <div class="row">
        <div class="col-6 d-flex align-items-start">
            <div class="col-4 fw-bold text-start">
                <strong>Policy Type:</strong>
            </div>
            <div class="col-8">Flood</div>
        </div>

        <div class="col-6 d-flex align-items-start">
            <div class="col-4 fw-bold text-start">
                <strong>Location address:</strong>
            </div>
            <div class="col-8">
                <div><?= $propertyAddress ?></div>
                <div><?= $propertyCity ?>, <?= $propertyState ?> <?= $propertyZip ?></div>
            </div>
        </div>
    </div>

    <div class="row mb-2">
        <table class="table table-bordered">
            <tr>
                <td colspan="2">
                    <strong>First Mortgage:</strong><br>
                    <?= $mortgage1->name ?><br>
                    <?= $mortgage1->name2 ?><br>
                    <?= $mortgage1->address ?><br>
                    <?= $mortgage1->city ?>, <?= $mortgage1->state ?>&nbsp;&nbsp;<?= $mortgage1->zip ?><br>
                    <?= $mortgage1->phone ?>
                </td>
                <td colspan="2">
                    <strong>Second Mortgage:</strong><br>
                    <?= $mortgage2->name ?><br>
                    <?= $mortgage2->name2 ?><br>
                    <?= $mortgage2->address ?><br>
                    <?= $mortgage2->city ?>, <?= $mortgage2->state ?>&nbsp;&nbsp;<?= $mortgage2->zip ?><br>
                    <?= $mortgage2->phone ?>
                </td>
            </tr>
            <tr>
                <td><strong>Loan #</strong></td>
                <td><?= $mortgage1->loan_number ?></td>
                <td><strong>Loan #</strong></td>
                <td><?= $mortgage2->loan_number ?></td>
            </tr>
            <tr>
                <td colspan="2">
                    <strong>Policy #:</strong> APP-FLD00<?= $floodQuote->flood_quote_id ?>
                </td>
                <td colspan="2">
                    <strong>Effective From:</strong> <?= date('m/d/Y', strtotime($floodQuote->effectivity_date)) ?> to <?= date('m/d/Y', strtotime($floodQuote->expiration_date)) ?>
                </td>
            </tr>
        </table>

        <div class="border border-dark p-0">
            <table class="table table-borderless">
                <thead class="table-secondary">
                    <tr>
                        <th>Coverage Type</th>
                        <th>Limit</th>
                        <th>Deductible</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($entityType == 0) { ?>
                        <tr>
                            <td>Building</td>
                            <td><?= $formatter->formatCurrency($calculations->dwellingCoverage, 'USD') ?></td>
                            <td><?= $formatter->formatCurrency($calculations->quoteOptionDeductible, 'USD') ?></td>
                        </tr>
                        <tr>
                            <td>Contents</td>
                            <td><?= $formatter->formatCurrency($calculations->personalPopertyCoverage, 'USD') ?></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>Loss of Use/Rents</td>
                            <td>
                                <?= $formatter->formatCurrency($calculations->lossOfUseCoverage, 'USD') ?>
                            </td>
                            <td>>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>Other Structures</td>
                            <td><?= $formatter->formatCurrency($calculations->otherStructureCoverage, 'USD') ?></td>
                            <td>&nbsp;</td>
                        </tr>
                        <?php
                    } else {
                        if ($isRented) {
                        ?>
                            <tr>
                                <td>Contents</td>
                                <td><?= $formatter->formatCurrency($calculations->personalPopertyCoverage, 'USD') ?></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>Improvements</td>
                                <td><?= $formatter->formatCurrency($calculations->improvementsAndBettermentsLimit, 'USD') ?></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>BI & Extra Expense</td>
                                <td><?= $formatter->formatCurrency($calculations->businessIncomeAndExtraExpenseAnnualValue, 'USD') ?></td>
                                <td>&nbsp;</td>
                            </tr>
                        <?php } else { ?>
                            <tr>
                                <td>Building</td>
                                <td><?= $formatter->formatCurrency($calculations->dwellingCoverage, 'USD') ?></td>
                                <td><?= $formatter->formatCurrency($calculations->quoteOptionDeductible, 'USD') ?></td>
                            </tr>
                            <tr>
                                <td>Contents</td>
                                <td><?= $formatter->formatCurrency($calculations->personalPopertyCoverage, 'USD') ?></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>BI & Extra Expense</td>
                                <td><?= $formatter->formatCurrency($calculations->businessIncomeAndExtraExpenseAnnualValue, 'USD') ?></td>
                                <td>&nbsp;</td>
                            </tr>
                    <?php
                        }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-6"></div>
        <div class="col-6 d-flex p-0">
            <table class="table table-bordered">
                <thead class="table-secondary">
                    <tr>
                        <th colspan="2">Premium Summary</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Total Policy Premium:</td>
                        <td><?= $formatter->formatCurrency($calculations->basePremium, 'USD') ?></td>
                    </tr>
                    <tr>
                        <td><?= $propertyState ?> Surplus Lines Tax:</td>
                        <td><?= $formatter->formatCurrency($calculations->finalTax, 'USD') ?></td>
                    </tr>
                    <tr>
                        <td>Policy Fee:</td>
                        <td><?= $formatter->formatCurrency($calculations->policyFee, 'USD') ?></td>
                    </tr>
                    <tr>
                        <td>
                            <?php if ($propertyState == "NY" || $propertyState == "PA") : ?>
                                <?= $propertyState . " Stamping Fee" ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($propertyState == "NY" || $propertyState == "PA") : ?>
                                <?= $formatter->formatCurrency($calculations->stampFee, 'USD') ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Total Amount Due:</strong></td>
                        <td><strong><?= $formatter->formatCurrency($calculations->finalCost, 'USD') ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <p><strong>*** Please remit the full policy premium prior to: <?= date('m/d/Y', strtotime($floodQuote->effectivity_date)) ?></strong></p>
        <p>IMPORTANT NOTICE:<br />Payment must be received prior to the date specified to guarantee the effective date shown.<br />Sign all enclosed forms and/or applications.</p>
    </div>

</div>

<?= $this->endSection() ?>