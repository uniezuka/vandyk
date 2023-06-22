<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>
<p class="fst-italic">Enter these values exactly as you would want them shown on a Dec Page</p>
<div class="col-md-6 col-sm-12">
    <div class="login-form">
        <form>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Broker Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Address 1</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Address 2</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">City</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">State</label>
                <div class="col-sm-1">
                    <select class="form-select">
                        <option selected>NJ</option>
                        <option value="1">CA</option>
                        <option value="2">TX</option>
                        <option value="3">MI</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Zip</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Phone</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Fax</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control">
                </div>
            </div>

            <h5>Portal Information</h5>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Username</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Greetings</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-3">
                    <input type="password" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Is IIANJ Member</label>
                <div class="col-sm-3">
                    <input class="form-check-input" type="checkbox">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>