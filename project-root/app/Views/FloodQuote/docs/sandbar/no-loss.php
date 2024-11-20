<?= $this->extend('layouts/print', ['data' => $data]) ?>
<?= $this->section('content') ?>

<?php
$formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
helper('html');
extract($data);
$insuredName = "";
$secondInsuredName = "";

if ($client->entity_type == 1) {
    $insuredName = $client->first_name . ' ' . $client->last_name;
    $secondInsuredName = $client->insured2_name;
} else {
    $insuredName = $client->business_name;
    $secondInsuredName = $client->business_name2;
}

$locationAddress = $floodQuote->address . ' ' . $floodQuote->city . ', ' . $floodQuote->state . ' ' . $floodQuote->zip;
?>

<div class="content-wrapper">
    <div class="row">
        <div class="col-12 logo">
            <img src="<?= base_url('assets/images/sandbarLogo100x270.png'); ?>" alt="Sandbar Flood Logo" alt="logo" class="img-fluid" width="270" height="100">
        </div>
    </div>

    <div class="row">
        <div class="col-12 title">
            No Loss Confirmation
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <p>
                <strong>Client Name:</strong>
                <?= $insuredName ?>
                <br />
                <?= $secondInsuredName ?>
            </p>
            <p><strong>Location address:</strong> <?= $locationAddress ?></p>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-12">
            <p>
                Please acknowledge by signing below that the above property has not sustained any flood damage as a result of recent storms, including from the remnants of Hurricane Ida.
            </p>
            <p>
                By signing below you also confirm that the above property does not currently have any unrepaired water damage.
            </p>
        </div>
    </div>

    <div class="row footer">
        <div class="col-6">
            <div class="signature-line" style="width:99%">X</div>
            <div class="row">
                <div class="col-6">Insured Signature</div>
                <div class="col-6 text-end">Date</div>
            </div>

        </div>
        <div class="col-6">
            <div class="signature-line" style="width:99%">X</div>
            <div class="row">
                <div class="col-6">Insured Signature</div>
                <div class="col-6 text-end">Date</div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>