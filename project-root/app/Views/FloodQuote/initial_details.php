<?= $this->extend('layouts/default', ['data' => $data]) ?>
<?= $this->section('content') ?>

<?php
helper('html');
$flood_quote = $data['flood_quote'];
?>

<?php if (session()->getFlashdata('error') || validation_errors()) : ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
        <?= validation_list_errors() ?>
    </div>
<?php endif; ?>


<?= csrf_field() ?>
<div class="row">
    <div class="col-4">
        <div class="card">
            <div class="card-body">
                <strong>Insured Name &amp; Mailing Address</strong>

                <span>1</span>
                <span>1</span>
                <span>1</span>
            </div>
        </div>
    </div>

    <div class="col-4">
        <div class="card">
            <div class="card-body">

            </div>
        </div>
    </div>

    <div class="col-4">
        <div class="card">
            <div class="card-body">

            </div>
        </div>
    </div>
</div>