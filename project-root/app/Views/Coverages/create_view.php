<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<?php
    helper('html');
?>

<?php if (session()->getFlashdata('error') || validation_errors()) : ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
        <?= validation_list_errors() ?>
    </div>
<?php endif; ?>

<div class="col-md-5 col-sm-12">
    <div class="form">
        <form method="post">
            <?= csrf_field() ?>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Code: </label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="code" value="<?= set_value('code') ?>">
                </div>
            </div>
            
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Name: </label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" value="<?= set_value('name') ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label"></label>
                <div class="d-flex align-items-end col-sm-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="hasFirePremium" type="checkbox" value="true" <?= set_checkbox('hasFirePremium', 'true') ?>>
                        <label class="form-check-label">Has Fire Premium</label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Add Coverage</button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>