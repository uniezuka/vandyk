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
                    <a type="button" class="btn btn-primary float-end" href="<?= base_url('/bcx_rate/create'); ?>">Create Rate</a>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Description</th>
                                <th scope="col">Rate</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rates as $rate): ?>
                                <tr>
                                    <td><?= $rate->flood_bcx_rate_id ?></td>
                                    <td><strong><?= $rate->description ?></strong></td>
                                    <td><strong><?= $rate->rate ?></strong></td>
                                    <td>
                                        <a href="<?= base_url('/bcx_rate/update/' . $rate->flood_bcx_rate_id); ?>">Update</a>
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