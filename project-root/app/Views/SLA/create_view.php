<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<?php
helper('html');
$currentSLASetting = $data['currentSLASetting'];
$currentSLAPrefix = $currentSLASetting->prefix;
?>
<div class="form">
    <form method="post">
        <?= csrf_field() ?>
        <p>Endorsement/Cancelation SLA# <strong><?= $currentSLAPrefix ?></strong> <input type="text" name="transactionNumber" class="form-text me-sm-2 w-auto" value="<?= set_value('transactionNumber') ?>"></p>
        <p><strong>*** Only enter everything after <?= $currentSLAPrefix ?> for the above SLA number - ie. <?= $currentSLAPrefix ?>00143</strong></p>
        <div class="col-md-6 col-sm-12">
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Transaction Type: </label>
                <div class="col-sm-3">
                    <?= transactionTypeSelect('transactionTypeId', set_value('transactionTypeId')) ?>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Insured Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="insuredName" required="required" value="<?= set_value('insuredName') ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Policy Num:</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="policyNumber" required="required" value="<?= set_value('policyNumber') ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Eff Date:</label>
                <div class="col-sm-3">
                    <input type="date" class="form-control" name="effectivityDate" required="required" value="<?= set_value('effectivityDate') ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Exp Date:</label>
                <div class="col-sm-3">
                    <input type="date" class="form-control" name="expiryDate" required="required" value="<?= set_value('expiryDate') ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Fire Prem:</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="firePremium" value="<?= set_value('firePremium') ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Other Prem:</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="otherPremium" required="required" value="<?= set_value('otherPremium') ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Total Prem:</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="totalPremium" value="<?= set_value('totalPremium') ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">County:</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="county" required="required" value="<?= set_value('county') ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Risk Location:</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="location" required="required" value="<?= set_value('location') ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Location Zip:</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="zip" required="required" value="<?= set_value('zip') ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Fire Code: </label>
                <div class="col-sm-3">
                    <?= fireCodeSelect('fireCodeId', set_value('fireCodeId')) ?>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Coverage: </label>
                <div class="col-sm-3">
                    <?= coverageSelect('coverageId', set_value('coverageId')) ?>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Insurer NAIC: </label>
                <div class="col-sm-3">
                    <?= insurerSelect('insurerId', set_value('insurerId')) ?>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Transaction Date:</label>
                <div class="col-sm-3">
                    <input type="date" class="form-control" name="transactionDate" required="required" value="<?= set_value('transactionDate') ?>">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>