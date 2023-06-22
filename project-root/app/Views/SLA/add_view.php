<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>
<p>Endorsement/Cancelation SLA# <strong>1640-</strong> <input type="text" class="form-text me-sm-2 w-auto"></p>
<p><strong>*** Only enter everything after 1640- for the above SLA number - ie. 22-00143</strong></p>
<div class="col-md-6 col-sm-12">
    <div class="form">
        <form>
            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Transaction Type: </label>
                <div class="col-sm-3">
                    <select class="form-select">
                        <option value="">Select Type</option>
                        <option value="1">NEW BUSINESS</option>
                        <option value="2">Additional Premium</option>
                        <option value="3">Return Premium</option>
                        <option value="4">Cancellation</option>
                        <option value="5">Renewal</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Insured Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Policy Num:</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Eff Date:</label>
                <div class="col-sm-3">
                    <input type="date" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Exp Date:</label>
                <div class="col-sm-3">
                    <input type="date" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Fire Prem:</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Other Prem:</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Total Prem:</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">County:</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Risk Location:</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Location Zip:</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Fire Code: </label>
                <div class="col-sm-3">
                    <select class="form-select">
                        <option value="">Select Fire Code</option>
                        <option value="1">NEW BUSINESS</option>
                        <option value="2">Additional Premium</option>
                        <option value="3">Return Premium</option>
                        <option value="4">Cancellation</option>
                        <option value="5">Renewal</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Coverage: </label>
                <div class="col-sm-3">
                    <select class="form-select">
                        <option value="">Select Coverage</option>
                        <option value="1">NEW BUSINESS</option>
                        <option value="2">Additional Premium</option>
                        <option value="3">Return Premium</option>
                        <option value="4">Cancellation</option>
                        <option value="5">Renewal</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Insurer NAIC: </label>
                <div class="col-sm-3">
                    <select class="form-select">
                        <option value="">Select Insurer NAIC</option>
                        <option value="1">NEW BUSINESS</option>
                        <option value="2">Additional Premium</option>
                        <option value="3">Return Premium</option>
                        <option value="4">Cancellation</option>
                        <option value="5">Renewal</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Transaction Date:</label>
                <div class="col-sm-3">
                    <input type="date" class="form-control">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
<?= $this->endSection() ?>