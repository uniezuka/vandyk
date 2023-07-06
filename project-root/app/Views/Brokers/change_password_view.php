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

<?php if (session()->getFlashdata('message')) : ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('message') ?>
    </div>
<?php endif; ?>

<div class="col-md-6 col-sm-12">
    <div class="login-form">
        <form method="post">
            <?= csrf_field() ?>
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">New Password</label>
                <div class="col-sm-5">
                    <input type="password" required class="form-control" name="newPassword" value="<?= set_value('newPassword') ?>">
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Re-enter New Password</label>
                <div class="col-sm-5">
                    <input type="password" required class="form-control" name="reEnterPassword" value="<?= set_value('reEnterPassword') ?>">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>