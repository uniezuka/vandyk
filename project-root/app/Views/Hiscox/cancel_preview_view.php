<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<?php
helper(['html', 'service']);
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
<div class="row mb-3">
    <div class="col-6">
        <div class="card">
            <div class="card-body">
                <h5>Hiscox Cancellation Preview</h5>

                <div class="row mb-3">
                    Hiscox ID: <?= $prevHiscoxBoundID ?>
                </div>

                <div class="row mb-3">
                    Cancellation Date: <?= $cancellationDate ?>
                </div>

                <div class="row mb-3">
                    Return Premium: <?= $returnPremium ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>