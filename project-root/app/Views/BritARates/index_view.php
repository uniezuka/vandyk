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
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5>Rates</h5>

                <div class="clearfix">
                    <a type="button" class="btn btn-primary float-end" href="<?= base_url('/brit_a_rate/create'); ?>">Create Rate</a>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Description</th>
                                <th scope="col">Zip</th>
                                <th scope="col">State</th>
                                <th scope="col">County</th>
                                <th scope="col">Dwl4</th>
                                <th scope="col">Cont4</th>
                                <th scope="col">Both4</th>
                                <th scope="col">Dwl3</th>
                                <th scope="col">Cont3</th>
                                <th scope="col">Both3</th>
                                <th scope="col">Dwl2</th>
                                <th scope="col">Cont2</th>
                                <th scope="col">Both2</th>
                                <th scope="col">Dwl1</th>
                                <th scope="col">Cont1</th>
                                <th scope="col">Both1</th>
                                <th scope="col">Dwl0</th>
                                <th scope="col">Cont0</th>
                                <th scope="col">Both0</th>
                                <th scope="col">Dwl-1</th>
                                <th scope="col">Cont-1</th>
                                <th scope="col">Both-1</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rates as $rate): ?>
                                <tr>
                                    <td><?= $rate->brit_flood_a_rate_id ?></td>
                                    <td><strong><?= $rate->description ?></strong></td>
                                    <td><strong><?= $rate->zip ?></strong></td>
                                    <td><strong><?= $rate->state_code ?></strong></td>
                                    <td><strong><?= $rate->county_name ?></strong></td>
                                    <td><strong><?= $rate->dwl4 ?></strong></td>
                                    <td><strong><?= $rate->cont4 ?></strong></td>
                                    <td><strong><?= $rate->both4 ?></strong></td>
                                    <td><strong><?= $rate->dwl3 ?></strong></td>
                                    <td><strong><?= $rate->cont3 ?></strong></td>
                                    <td><strong><?= $rate->both3 ?></strong></td>
                                    <td><strong><?= $rate->dwl2 ?></strong></td>
                                    <td><strong><?= $rate->cont2 ?></strong></td>
                                    <td><strong><?= $rate->both2 ?></strong></td>
                                    <td><strong><?= $rate->dwl1 ?></strong></td>
                                    <td><strong><?= $rate->cont1 ?></strong></td>
                                    <td><strong><?= $rate->both1 ?></strong></td>
                                    <td><strong><?= $rate->dwl0 ?></strong></td>
                                    <td><strong><?= $rate->cont0 ?></strong></td>
                                    <td><strong><?= $rate->both0 ?></strong></td>
                                    <td><strong><?= $rate->{'dwl-1'} ?></strong></td>
                                    <td><strong><?= $rate->{'cont-1'} ?></strong></td>
                                    <td><strong><?= $rate->{'both-1'} ?></strong></td>
                                    <td>
                                        <a href="<?= base_url('/brit_a_rate/update/' . $rate->brit_flood_a_rate_id); ?>">Update</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>