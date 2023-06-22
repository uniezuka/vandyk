<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>
<div class="col-md-6 col-sm-12">
    <div class="login-form">
        <form>
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">New Password</label>
                <div class="col-sm-5">
                    <input type="password" class="form-control">
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Re-enter New Password</label>
                <div class="col-sm-5">
                    <input type="password" class="form-control">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>