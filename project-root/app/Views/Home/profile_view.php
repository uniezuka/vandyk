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

<p class="fst-italic">Enter these values exactly as you would want them shown on a Dec Page</p>

<div class="col-md-6 col-sm-12">
    <div class="login-form">
        <form method="post">
            <?= csrf_field() ?>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Broker Name</label>
                <div class="col-sm-10">
                    <input type="text" required class="form-control" name="name" value="<?= set_value('name') ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Address 1</label>
                <div class="col-sm-10">
                    <input type="text" required class="form-control" name="address" value="<?= set_value('address') ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Address 2</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="address2" value="<?= set_value('address2') ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">City</label>
                <div class="col-sm-3">
                    <input type="text" required class="form-control" name="city" value="<?= set_value('city') ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">State</label>
                <div class="col-sm-3">
                    <?= stateSelect('state', set_value('state')) ?>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Zip</label>
                <div class="col-sm-3">
                    <input type="text" required class="form-control" name="zip" value="<?= set_value('zip') ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Phone</label>
                <div class="col-sm-3">
                    <input type="text" required class="form-control" name="phone" value="<?= set_value('phone') ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Fax</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control" name="fax" value="<?= set_value('fax') ?>">
                </div>
            </div>

            <h5>Portal Information</h5>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Username</label>
                <div class="col-sm-3">
                    <input type="text" disabled class="form-control" name="username" value="<?= set_value('username') ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Greetings</label>
                <div class="col-sm-3">
                    <input type="text" required class="form-control" name="greetings" value="<?= set_value('greetings') ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-3">
                    <input type="text" disabled class="form-control" name="email" value="<?= set_value('email') ?>">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>