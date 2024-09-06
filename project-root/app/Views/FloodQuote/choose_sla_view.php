<?= $this->extend('layouts/default', ['data' => $data]) ?>
<?= $this->section('content') ?>

<?php
helper('html');
extract($data);
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

<div class="row">
    <div class="col-5">
        <h4>Bound Policy Info</h4>
        <p>Quote ID: <?= $floodQuote->flood_quote_id ?></p>
        <p>Name: <?= $quoteName ?></p>
        <p>Property: <?= $propertyAddress ?></p>
        <p>Effective Date: <?= $floodQuote->effectivity_date ?></p>
        <br />
        <p><a href="<?= base_url("/flood_quotes") ?>">Flood Page</a> OR <a href="<?= base_url("/client/details/") . $floodQuote->client_id ?>">Client Page</a></p>
    </div>

    <div class="col-5">
        <?php if ($policyType == "NEW" || $policyType == "REN") { ?>
            <h5>Available SLA Numbers</h5>

            <h5><?= $currentSLASetting->year ?> Available SLA Numbers</h5>
            <table class="table sla_policies">
                <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">SLA Num</th>
                        <th scope="col">Policy Type</th>
                        <th scope="col">Insured</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($availableSLAPolicies as $policy) : ?>
                        <tr>
                            <td><a href="<?= base_url("/flood_quote/bind_sla/") . $floodQuote->flood_quote_id . "?transaction_number=" . $policy->transaction_number ?>">Use</a></td>
                            <td><?= $policy->transaction_number ?></td>
                            <td><?= $policy->transaction_name ?></td>
                            <td><?= $policy->insured_name ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h5><?= $currentSLASetting->year - 1 ?> Available SLA Numbers</h5>
            <table class="table sla_policies">
                <thead>
                    <tr>
                        <th scope="col"></th>
                        <th scope="col">SLA Num</th>
                        <th scope="col">Policy Type</th>
                        <th scope="col">Insured</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prevAvailableSLAPolicies as $policy) : ?>
                        <tr>
                            <td><a href="<?= base_url("/flood_quote/bind_sla/") . $floodQuote->flood_quote_id . "?transaction_number=" . $policy->transaction_number ?>">Use</a></td>
                            <td><?= $policy->transaction_number ?></td>
                            <td><?= $policy->transaction_name ?></td>
                            <td><?= $policy->insured_name ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php } else { ?>
            <h5>Cancellations and Endorsements</h5>
            <p><strong>No SLA Needed - Return to <a href="<?= base_url("/flood_quotes") ?>">Flood Page</a> OR <a href="<?= base_url("/client/details/") . $floodQuote->client_id ?>">Client Page</a></strong></a></p>
        <?php } ?>
    </div>
</div>

<?= $this->endSection() ?>