<?= $this->extend('layouts/print', ['data' => $data]) ?>
<?= $this->section('content') ?>

<?php
$formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
helper('html');
extract($data);

$invoiceTitle = "";
$isExcessPolicy = getMetaValue($floodQuoteMetas, "isExcessPolicy", 0);
$billTo = getMetaValue($floodQuoteMetas, "billTo", 1);
$entityType = getMetaValue($floodQuoteMetas, "entityType");
$propertyAddress = getMetaValue($floodQuoteMetas, "propertyAddress");
$propertyCity = getMetaValue($floodQuoteMetas, "propertyCity");
$propertyState = getMetaValue($floodQuoteMetas, "propertyState");
$propertyZip = getMetaValue($floodQuoteMetas, "propertyZip");
$flood_occupancy = getMetaValue($floodQuoteMetas, "flood_occupancy", 0);
$underlyingBuildLimit = getMetaValue($floodQuoteMetas, "underlyingBuildLimit", 0);
$underlyingContentLimit = getMetaValue($floodQuoteMetas, "underlyingContentLimit", 0);

$deductibles = $calculations->getDeductibles();

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
                <?= ($isExcessPolicy) ? "Excess Flood <br />" : "" ?>
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
                            <?= $floodQuote->company_name ?><br>
                            <?= $floodQuote->company_name_2 ?><br>
                        <?php else : ?>
                            <?= $floodQuote->first_name ?> <?= $floodQuote->last_name ?><br>
                            <?= $floodQuote->insured_name_2 ?><br>
                        <?php endif; ?>
                        <?= $floodQuote->address ?><br>
                        <?= $floodQuote->city ?>, <?= $floodQuote->state ?> <?= $floodQuote->zip ?>
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
                            <?= $floodQuote->company_name ?><br>
                            <?= $floodQuote->company_name_2 ?><br>
                        <?php else : ?>
                            <?= $floodQuote->first_name ?> <?= $floodQuote->last_name ?><br>
                            <?= $floodQuote->insured_name_2 ?><br>
                        <?php endif; ?>
                        <?= $floodQuote->address ?><br>
                        <?= $floodQuote->city ?>, <?= $floodQuote->state ?> <?= $floodQuote->zip ?><br>
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
            <div class="col-8">
                Flood
            </div>
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
                    <?php if (!empty($mortgage1->name)) : ?>
                        <?= $mortgage1->name ?><br>
                        <?= $mortgage1->name2 ?><br>
                        <?= $mortgage1->address ?><br>
                        <?= $mortgage1->city ?>, <?= $mortgage1->state ?>&nbsp;&nbsp;<?= $mortgage1->zip ?><br>
                        <?= $mortgage1->phone ?>
                    <?php endif; ?>
                </td>
                <td colspan="2">
                    <strong>Second Mortgage:</strong><br>
                    <?php if (!empty($mortgage2->name)) : ?>
                        <?= $mortgage2->name ?><br>
                        <?= $mortgage2->name2 ?><br>
                        <?= $mortgage2->address ?><br>
                        <?= $mortgage2->city ?>, <?= $mortgage2->state ?>&nbsp;&nbsp;<?= $mortgage2->zip ?><br>
                        <?= $mortgage2->phone ?>
                    <?php endif; ?>
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
                        <th><?= ($isExcessPolicy) ? "Underlying Limits" : "Deductible" ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Building</td>
                        <td><?= $formatter->formatCurrency(getMetaValue($floodQuoteMetas, "covABuilding", 0), 'USD') ?></td>
                        <td>
                            <?php if ($isExcessPolicy) : ?>
                                <?php if ($underlyingBuildLimit) : ?>
                                    <?= $formatter->formatCurrency($underlyingBuildLimit, 'USD') ?>
                                <?php else : ?>
                                    <?php if ($flood_occupancy == 4) : ?>
                                        $500,000
                                    <?php else : ?>
                                        $250,000
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php else : ?>
                                <?= $formatter->formatCurrency($deductibles["building_deductible"], 'USD') ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Contents</td>
                        <td><?= $formatter->formatCurrency(getMetaValue($floodQuoteMetas, "covCContent", 0), 'USD') ?></td>
                        <td>
                            <?php if ($isExcessPolicy) : ?>
                                <?php if ($underlyingContentLimit) : ?>
                                    <?= $formatter->formatCurrency($underlyingContentLimit, 'USD') ?>
                                <?php else : ?>
                                    N/A
                                <?php endif; ?>
                            <?php else : ?>
                                <?= $formatter->formatCurrency($deductibles["content_deductible"], 'USD') ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Loss of Use/Rents</td>
                        <td>
                            <?php
                            $lossUseCov = $calculations->lossUseCoverage;
                            if ($lossUseCov == 0) {
                                echo "N/A";
                            } else {
                                echo $formatter->formatCurrency($lossUseCov, 'USD');
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($lossUseCov == 0 || $lossUseCov == "") {
                                echo "N/A";
                            } else {
                                echo $formatter->formatCurrency($deductibles["rent_deductible"], 'USD');
                            }
                            ?>
                        </td>
                    </tr>
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
                        <td><?= $formatter->formatCurrency($calculations->finalPremium, 'USD') ?></td>
                    </tr>
                    <tr>
                        <td><?= $propertyState ?> Surplus Lines Tax:</td>
                        <td><?= $formatter->formatCurrency($calculations->taxAmount, 'USD') ?></td>
                    </tr>
                    <tr>
                        <td>Policy Fee:</td>
                        <td><?= $formatter->formatCurrency($calculations->policyFee, 'USD') ?></td>
                    </tr>
                    <tr>
                        <td>
                            <?php if ($propertyState == "NY" || $propertyState == "PA" || $propertyState == "TX" || $propertyState == "NC") : ?>
                                <?= $propertyState . " Stamping Fee" ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($propertyState == "NY" || $propertyState == "PA" || $propertyState == "TX" || $propertyState == "NC") : ?>
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