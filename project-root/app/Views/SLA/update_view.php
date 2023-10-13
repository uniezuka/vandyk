<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<?php
helper('html');
$sla_policy = $data['sla_policy'];
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

<div class="form">
    <form method="post">
        <?= csrf_field() ?>
        <p>Update SLA# <?= $sla_policy->transaction_number ?></p>
        <div class="col-md-6 col-sm-12">
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Transaction Type: </label>
                <div class="col-sm-3">
                    <?= transactionTypeSelect('transactionTypeId', set_value('transactionTypeId', $sla_policy->transaction_type_id)) ?>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Insured Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="insuredName" required="required" value="<?= set_value('insuredName', $sla_policy->insured_name) ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Policy Num:</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="policyNumber" required="required" value="<?= set_value('policyNumber', $sla_policy->policy_number) ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Eff Date:</label>
                <div class="col-sm-3">
                    <input type="date" class="form-control" name="effectivityDate" required="required" value="<?= set_value('effectivityDate', $sla_policy->effectivity_date) ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Exp Date:</label>
                <div class="col-sm-3">
                    <input type="date" class="form-control" name="expiryDate" required="required" value="<?= set_value('expiryDate', $sla_policy->expiry_date) ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Fire Prem:</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="firePremium" value="<?= set_value('firePremium', $sla_policy->fire_premium) ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Other Prem:</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="otherPremium" required="required" value="<?= set_value('otherPremium', $sla_policy->other_premium) ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Total Prem:</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="totalPremium" value="<?= set_value('totalPremium', $sla_policy->total_premium) ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">County:</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="county" required="required" value="<?= set_value('county', $sla_policy->county) ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Risk Location:</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="location" required="required" value="<?= set_value('location', $sla_policy->location) ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Location Zip:</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="zip" required="required" value="<?= set_value('zip', $sla_policy->zip) ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Fire Code: </label>
                <div class="col-sm-3">
                    <?= fireCodeSelect('fireCodeId', set_value('fireCodeId', $sla_policy->fire_code_id)) ?>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Coverage: </label>
                <div class="col-sm-3">
                    <?= coverageSelect('coverageId', set_value('coverageId', $sla_policy->coverage_id)) ?>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Insurer NAIC: </label>
                <div class="col-sm-3">
                    <?= insurerSelect('insurerId', set_value('insurerId', $sla_policy->insurer_id)) ?>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Transaction Date:</label>
                <div class="col-sm-3">
                    <input type="date" class="form-control" name="transactionDate" required="required" value="<?= set_value('transactionDate', $sla_policy->transaction_date) ?>">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>