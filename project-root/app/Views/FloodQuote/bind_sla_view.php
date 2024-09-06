<?= $this->extend('layouts/default', ['data' => $data]) ?>
<?= $this->section('content') ?>

<?php
$formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
helper('html');
extract($data);
?>

<?php if (session()->getFlashdata('error') || validation_errors()) : ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
        <?= validation_list_errors() ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('message')) : ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('message') ?>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-5">
        <form method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="slaPolicyId" value="<?= $slaPolicyId ?>" />

            <div class="row mb-3">
                <label class="d-flex justify-content-end col-sm-4 col-form-label">SLA #:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="slaNumber" value="<?= set_value('slaNumber', $transactionNumber) ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="d-flex justify-content-end col-sm-4 col-form-label">Transaction Type:</label>
                <div class="col-sm-8">
                    <?= transactionTypeSelect('transactionTypeId', set_value('transactionTypeId', $policyTypeNumber)) ?>
                </div>
            </div>

            <div class="row mb-3">
                <label class="d-flex justify-content-end col-sm-4 col-form-label">Insured Name:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="insuredName" value="<?= set_value('insuredName', $quoteName) ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="d-flex justify-content-end col-sm-4 col-form-label">Policy Number:</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="policyNumber" value="<?= set_value('policyNumber', $policyNumber) ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="d-flex justify-content-end col-sm-4 col-form-label">Effectivity Date:</label>
                <div class="col-sm-4">
                    <input type="date" class="form-control" name="effectivityDate" value="<?= set_value('policyNumber', date('Y-m-d', strtotime($floodQuote->effectivity_date))) ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="d-flex justify-content-end col-sm-4 col-form-label">Expiration Date:</label>
                <div class="col-sm-4">
                    <input type="date" class="form-control" name="expiryDate" value="<?= set_value('expiryDate', date('Y-m-d', strtotime($floodQuote->expiration_date))) ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="d-flex justify-content-end col-sm-4 col-form-label">Fire Premium:</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="firePremium" value="<?= set_value('firePremium', 0) ?>" />
                </div>
                <label class="d-flex justify-content-end col-sm-2 col-form-label">Fire Tax:</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="fireTax" value="<?= set_value('fireTax', 0) ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="d-flex justify-content-end col-sm-4 col-form-label">Other Premium:</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="otherPremium" value="<?= set_value('otherPremium', $boundFinalPremium) ?>" />
                </div>
                <label class="d-flex justify-content-end col-sm-2 col-form-label">Reg Tax:</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="regTax" value="<?= set_value('regTax', 0) ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="d-flex justify-content-end col-sm-4 col-form-label">Total Premium:</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="totalPremium" value="<?= set_value('totalPremium', $boundFinalPremium) ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="d-flex justify-content-end col-sm-4 col-form-label">County:</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="county" value="<?= set_value('county') ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="d-flex justify-content-end col-sm-4 col-form-label">Risk Location:</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="location" value="<?= set_value('location', $propertyCity) ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="d-flex justify-content-end col-sm-4 col-form-label">Location Zip:</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="zip" value="<?= set_value('zip', $propertyZip) ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="d-flex justify-content-end col-sm-4 col-form-label">Fire Code:</label>
                <div class="col-sm-8">
                    <?= fireCodeSelect('fireCodeId') ?>
                </div>
            </div>

            <div class="row mb-3">
                <label class="d-flex justify-content-end col-sm-4 col-form-label">Coverage:</label>
                <div class="col-sm-8">
                    <?= coverageSelect('coverageId') ?>
                </div>
            </div>

            <div class="row mb-3">
                <label class="d-flex justify-content-end col-sm-4 col-form-label">Insurer NAIC:</label>
                <div class="col-sm-8">
                    <?= insurerSelect('insurerId') ?>
                </div>
            </div>

            <div class="row mb-3">
                <label class="d-flex justify-content-end col-sm-4 col-form-label">Transaction Date:</label>
                <div class="col-sm-4">
                    <input type="date" class="form-control" name="transactionDate" value="<?= set_value('transactionDate', date('Y-m-d', strtotime($transactionDate))) ?>" />
                </div>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
    <div class="col-5">
        <h4>Bound Policy Info</h4>
        <p>Quote ID: <?= $floodQuote->flood_quote_id ?></p>
        <p>Name: <?= $quoteName ?></p>
        <p>Property: <?= $propertyAddress ?></p>
        <p>Effective Date: <?= $floodQuote->effectivity_date ?></p>
        <table class="table table-striped">
            <tbody>
                <tr>
                    <td>Total Base Premium:</td>
                    <td align="right"><?= $formatter->formatCurrency($boundFinalPremium, 'USD') ?></td>
                </tr>
                <tr>
                    <td>Bound <?= $propertyState ?> Surplus Lines Tax:</td>
                    <td align="right"><?= $formatter->formatCurrency($boundTaxAmount, 'USD') ?></td>
                </tr>
                <tr>
                    <td>Bound Policy Fee:</td>
                    <td align="right"><?= $formatter->formatCurrency($boundPolicyFee, 'USD') ?></td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td><strong>Bound Grand Total:</strong></td>
                    <td align="right"><?= $formatter->formatCurrency($boundTotalCost, 'USD') ?></td>
                </tr>
            </tfoot>
        </table>
        <p><a href="<?= base_url("/flood_quotes") ?>">Flood Page</a> OR <a href="<?= base_url("/client/details/") . $floodQuote->client_id ?>">Client Page</a></p>
    </div>
</div>

<?= $this->endSection() ?>