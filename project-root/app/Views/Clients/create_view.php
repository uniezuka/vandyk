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
            <span class="me-3 form-text">Entity Type</span>
            <div class="form-check form-check-inline">
                <?= form_radio('entityType', '1', (set_value('entityType') == "1" || set_value('entityType') == ""), ['class' => "form-check-input insured_type"]); ?>
                <label class="form-check-label">Individual</label>
            </div>
            <div class="form-check form-check-inline">
                <?= form_radio('entityType', '2', set_value('entityType') == "2", ['class' => "form-check-input insured_type"]); ?>
                <label class="form-check-label">Business</label>
            </div>

            <div id="individual" <?= (set_value('entityType') == "1" || set_value('entityType') == "") ? '' : 'style="display: none"' ?>>
                <div class="mb-3">
                    <label class="form-label">Insured Name:</label>
                    <div class="row g-3">
                        <div class="col">
                            <input type="text" class="form-control" placeholder="First name" name="firstName" value="<?= set_value('firstName') ?>" />
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" placeholder="Last name" name="lastName" value="<?= set_value('lastName') ?>" />
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">2nd Named Insured</label>
                    <input type="text" class="form-control" name="clientName2" value="<?= set_value('clientName2') ?>" />
                </div>
            </div>

            <div id="business" <?= (set_value('entityType') == "2") ? '' : 'style="display: none"' ?>>
                <div class="mb-3">
                    <label class="form-label">Business Name:</label>
                    <input type="text" class="form-control" placeholder="Company name" name="companyName" value="<?= set_value('companyName') ?>" />
                </div>

                <div class="mb-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-8">
                            <input type="text" class="form-control" name="companyName2" value="<?= set_value('companyName2') ?>" />
                        </div>
                        <div class="col-auto">
                            <span class="form-text">(optional) </span>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Business Entity: </label>
                    <div class="col-sm-3">
                        <?= businessEntitySelect('businessEntityTypeId', set_value('businessEntityTypeId')) ?>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Mailing Addr:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Street Address" name="address" value="<?= set_value('address') ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">&nbsp;</label>
                <div class="col-sm-10">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="City" name="city" value="<?= set_value('city') ?>" />
                        </div>
                        <div class="col-md-4">
                            <?= stateSelect('state', set_value('state')) ?>
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" placeholder="Zip" name="zip" value="<?= set_value('zip') ?>" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Cell Phone</label>
                <div class="col-sm-5">
                    <input type="tel" class="form-control" placeholder="XXX-XXX-XXXX" name="cellPhone" value="<?= set_value('cellPhone') ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Home Phone:</label>
                <div class="col-sm-5">
                    <input type="tel" class="form-control" placeholder="XXX-XXX-XXXX" name="homePhone" value="<?= set_value('homePhone') ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Email:</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" placeholder="Email" name="email" value="<?= set_value('email') ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Client Code:</label>
                <div class="col-sm-10">
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <input type="text" class="form-control" placeholder="Code" name="clientCode" value="<?= set_value('clientCode') ?>" />
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
                    <?= brokerSelect('brokerId', set_value('brokerId')) ?>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Add Client</button>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.insured_type').click(function() {
            $('#individual').toggle();
            $('#business').toggle();
        });
    });
</script>
<?= $this->endSection() ?>