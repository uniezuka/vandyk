<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>
<div class="col-md-6 col-sm-12">
    <div class="login-form">
        <?php if(session()->getFlashdata('error')):?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif;?>
        <form action="<?php echo base_url('/login'); ?>" method="post">
            <?= csrf_field() ?>
            <div class="mb-3 row">
                <label for="inputUsername" class="col-sm-2 col-form-label">Username</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="username" id="inputUsername">
                </div>
            </div>
            <div class="mb-3 row">
                <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-10">
                    <input type="password" name="password" class="form-control" id="inputPassword">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>