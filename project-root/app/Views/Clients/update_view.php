<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<?php
    helper('html');
    $client = $data['client'];
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
            <span class="me-3 form-text">Entity Type</span>
            <div class="form-check form-check-inline">
                <?= form_radio('entityType', '1', (set_value('entityType', $client->entity_type) == "1" || set_value('entityType', $client->entity_type) == ""), ['class' => "form-check-input insured_type"]); ?>
                <label class="form-check-label">Individual</label>
            </div>
            <div class="form-check form-check-inline">
                <?= form_radio('entityType', '2', set_value('entityType', $client->entity_type) == "2", ['class' => "form-check-input insured_type"]); ?>
                <label class="form-check-label">Business</label>
            </div>

            <div id="individual" <?= (set_value('entityType', $client->entity_type) == "1" || set_value('entityType', $client->entity_type) == "") ? '' : 'style="display: none"' ?>>
                <div class="mb-3">
                    <label class="form-label">Insured Name:</label>
                    <div class="row g-3">
                        <div class="col">
                            <input type="text" class="form-control" placeholder="First name" name="firstName" value="<?= set_value('firstName', $client->first_name) ?>" />
                        </div>
                        <div class="col">
                            <input type="text" class="form-control" placeholder="Last name" name="lastName" value="<?= set_value('lastName', $client->last_name) ?>" />
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">2nd Named Insured</label>
                    <input type="text" class="form-control" name="clientName2" value="<?= set_value('clientName2', $client->insured2_name) ?>" />
                </div>
            </div>

            <div id="business" <?= (set_value('entityType', $client->entity_type) == "2") ? '' : 'style="display: none"' ?>>
                <div class="mb-3">
                    <label class="form-label">Business Name:</label>
                    <input type="text" class="form-control" placeholder="Company name" name="companyName" value="<?= set_value('companyName', $client->business_name) ?>" />
                </div>

                <div class="mb-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-8">
                            <input type="text" class="form-control" name="companyName2" value="<?= set_value('companyName2', $client->business_name2) ?>" />
                        </div>
                        <div class="col-auto">
                            <span class="form-text">(optional) </span>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Business Entity: </label>
                    <div class="col-sm-3">
                        <?= businessEntitySelect('businessEntityTypeId', set_value('businessEntityTypeId', $client->business_entity_type_id)) ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label">Doing Business As: </label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Trading Name" name="businessAs" value="<?= set_value('businessAs', $client->business_as) ?>" />
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                        <?= form_checkbox('isCommercial', 'true', (set_value('isCommercial', ($client->is_commercial ? 'true' : '')) == "true" && set_value('entityType', $client->entity_type) == "2"), ['class' => "form-check-input"]); ?>&nbsp;<span>Is Commercial</span>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Mailing Addr:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Street Address" name="address" value="<?= set_value('address', $client->address) ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">&nbsp;</label>
                <div class="col-sm-10">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="City" name="city" value="<?= set_value('city', $client->city) ?>" />
                        </div>
                        <div class="col-md-4">
                            <?= stateSelect('state', set_value('state', $client->state)) ?>
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="form-control" placeholder="Zip" name="zip" value="<?= set_value('zip', $client->zip) ?>" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Cell Phone</label>
                <div class="col-sm-5">
                    <input type="tel" class="form-control" placeholder="XXX-XXX-XXXX" name="cellPhone" value="<?= set_value('cellPhone', $client->cell_phone) ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Home Phone:</label>
                <div class="col-sm-5">
                    <input type="tel" class="form-control" placeholder="XXX-XXX-XXXX" name="homePhone" value="<?= set_value('homePhone', $client->home_phone) ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Email:</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" placeholder="Email" name="email" value="<?= set_value('email', $client->email) ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-2 col-form-label">Client Code:</label>
                <div class="col-sm-10">
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <input type="text" class="form-control" placeholder="Code" name="clientCode" value="<?= set_value('clientCode', $client->client_code) ?>" />
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
                    <?= brokerSelect('brokerId', set_value('brokerId', $client->broker_id)) ?>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update Client</button>
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