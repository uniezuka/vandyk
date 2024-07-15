<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<?php
helper('html');
$clients = $data['clients'];
$pager_links = $data['pager_links'];
$search = $data['search'];
$non_commercial_only = $data['non_commercial_only'];
$commercial_only = $data['commercial_only'];

function get_client_name($client)
{
    if ($client->entity_type == 1) {
        return $client->first_name . ' ' . $client->last_name;
    } else {
        return $client->business_name;
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

<div class="row">
    <div class="col-7">
        <div class="card">
            <div class="card-body">
                <div class="clearfix">
                    <h5>Clients</h5>
                    <div class="float-start">
                        <p>Search by Customer <strong>Last Name, First Name, Client Code or Mailing Address</strong></p>
                        <form class="form" method="get">
                            <div class="d-flex p-2">
                                <input class="d-flex form-control w-75 me-1" name="search" type="search" placeholder="Search" value="<?= $search ?>">
                                <button class="btn btn-primary" type="submit">Search</button>
                            </div>

                            <div class="d-block p-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="nonCommercialOnly" name="nonCommercialOnly" value="true" <?= $non_commercial_only ? ' checked' : '' ?>>
                                    <label class="form-check-label" for="nonCommercial">Non-commercial Clients Only</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="commercialOnly" name="commercialOnly" value="true" <?= $commercial_only ? ' checked' : '' ?>>
                                    <label class="form-check-label" for="commercial">Commercial Clients Only</label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <a type="button" class="btn btn-primary float-end" href="<?= base_url('/client/create'); ?>">Create New Client</a>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Address</th>
                            <th scope="col">Client Code</th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clients as $client) : ?>
                            <tr>
                                <td><?= $client->client_id ?></td>
                                <td><strong><?= get_client_name($client) ?></strong></td>
                                <td>
                                    <?= formatAddress($client->address, "", $client->city, $client->state, $client->zip) ?>
                                </td>
                                <td><?= $client->client_code ?></td>
                                <td><a href="<?= base_url('/client/details/') . $client->client_id; ?>" class="actionLink">View</a></td>
                                <td>
                                    <p>
                                        <a href="<?= base_url('/flood_quote/create?client_id=') . $client->client_id; ?>" class="actionLink">New Flood</a>
                                    </p>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <?= $pager_links ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#nonCommercialOnly').change(function() {
            if (this.checked) $("#commercialOnly").prop("checked", false);
        });

        $('#commercialOnly').change(function() {
            if (this.checked) $("#nonCommercialOnly").prop("checked", false);
        });
    });
</script>
<?= $this->endSection() ?>