<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<?php
helper('html');
$client = $data['client'];
$broker = $data['broker'];
$buildings = $data['buildings'];

function clientDisplay($client): string
{
    if ($client->entity_type == 1) {
        return $client->first_name . ' ' . $client->last_name . '<br />' . $client->insured2_name;
    } else {
        return $client->business_name . '<br />' . $client->business_name2;
    }
}
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

<div class="row mb-3">
    <div class="col-8">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col col-6">
                        <h4><?= clientDisplay($client) ?></h4>
                    </div>

                    <div class="col col-6 text-end">
                        <p><strong>Broker: <?= $broker->name ?></strong></p>
                        <p>Client Code: <?= $client->client_code ?></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col col-4">
                        <span>Mailing Address:</span>
                        <?= formatAddress($client->address, "", $client->city, $client->state, $client->zip) ?>
                    </div>

                    <div class="col col-4">
                        <p>
                            Cell: <?= $client->cell_phone ?><br />
                            Home Ph: <?= $client->home_phone ?><br />
                            Email: <?= $client->email ?>
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col col-4">
                        <a href="<?= base_url('/client/update/') . $client->client_id; ?>" class="btn btn-primary">Update Client</a>
                    </div>

                    <div class="col col-4">
                        <a href="<?= base_url('/flood_quote/create?client_id=') . $client->client_id; ?>" class="btn btn-primary">New Flood Quote</a>
                    </div>

                    <?php if ($client->is_commercial) { ?>
                        <div class="col col-4">
                            <a href="<?= base_url('/client/' . $client->client_id . '/building/create') ?>" class="btn btn-primary">Add Building Location</a>
                            <span class="d-block">**Hiscox limit is 10 buildings per client quote</span>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-5">
        <div class="card">
            <div class="card-body">
                <h5>Flood Policies/Quotes</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col"></th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4">
                                To be implemented
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php if ($client->is_commercial) { ?>
        <div class="col-5">
            <div class="card">
                <div class="card-body">
                    <h5>Buildings</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($buildings as $building) : ?>
                                <tr>
                                    <td><?= '#' . $building->build_index . ' ' . $building->address . ', ' . $building->city ?></td>
                                    <td>
                                        <a href="<?= base_url('/client/') . $client->client_id . '/building/update/' . $building->client_building_id ?>">Update</a>&nbsp;
                                        <a href="<?= base_url('/client/') . $client->client_id . '/building/delete/' . $building->client_building_id ?>">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<?= $this->endSection() ?>