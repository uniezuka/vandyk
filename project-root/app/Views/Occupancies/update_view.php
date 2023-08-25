<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<?php
    helper('html');
    $occupancy = $data['occupancy'];
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

<div class="col-md-5 col-sm-12">
    <div class="form">
        <form method="post">
            <?= csrf_field() ?>
            
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Label: </label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="label" value="<?= set_value('name', $occupancy->label) ?>">
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Value: </label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="value" value="<?= set_value('value', $occupancy->value) ?>">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update Occupancy</button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>