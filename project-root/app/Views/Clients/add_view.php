<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<div class="col-md-5 col-sm-12">
    <div class="form">
        <form>
            <span class="me-3 form-text">Entity Type</span>
            <div class="form-check form-check-inline">

                <input class="form-check-input insured_type" type="radio" value="Individual" checked name="inlineRadioOptions" />
                <label class="form-check-label">Individual</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input insured_type" type="radio" value="Business" name="inlineRadioOptions" />
                <label class="form-check-label">Business</label>
            </div>

            <div id="individual">
                <div class="mb-3">
                    <label class="form-label">Insured Name:</label>
                    <div class="row g-3">
                        <div class="col">
                            <input type="text" class="form-control" placeholder="First name" />
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" placeholder="Last name" />
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">2nd Named Insured</label>
                    <input type="email" class="form-control" />
                </div>
            </div>

            <div id="business" style="display: none">
                <div class="mb-3">
                    <label class="form-label">Business Name:</label>
                    <input type="text" class="form-control" placeholder="Company name" />
                </div>

                <div class="mb-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-8">
                            <input type="text" class="form-control" />
                        </div>
                        <div class="col-auto">
                            <span class="form-text">(optional) </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Mailing Addr:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Street Address" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">&nbsp;</label>
                <div class="col-sm-10">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="City" />
                        </div>
                        <div class="col-md-4">
                            <input type="text" class="form-control" placeholder="State" />
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" placeholder="Zip" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Cell Phone</label>
                <div class="col-sm-5">
                    <input type="tel" class="form-control" placeholder="XXX-XXX-XXXX" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Home Phone:</label>
                <div class="col-sm-5">
                    <input type="tel" class="form-control" placeholder="XXX-XXX-XXXX" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Email:</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" placeholder="Email" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Client Code:</label>
                <div class="col-sm-10">
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <input type="text" class="form-control" placeholder="Code" />
                        </div>
                        <div class="col-auto">
                            <span class="form-text">(Your company client code, for your Reference Only)</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Broker: </label>
                <div class="col-sm-3">
                    <select class="form-select">
                        <option selected="selected" value="">Select Broker</option>
                        <option value="1">The Van Dyk Group</option>
                        <option value="2">The Parker Agency</option>
                        <option value="3">Tri County Agency of Brick, Inc.</option>
                        <option value="4">The Durkin Agency</option>
                        <option value="5">Hardenbergh Insurance Group</option>
                        <option value="6">Risk Reduction Plus Group Inc</option>
                        <option value="7">Heist Insurance Agency</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Add Client</button>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.insured_type').click(function() {
            // var inputValue = $(this).attr("value");
            // $("." + inputValue).toggle();
            $('#individual').toggle();
            $('#business').toggle();
        });
    });
</script>
<?= $this->endSection() ?>