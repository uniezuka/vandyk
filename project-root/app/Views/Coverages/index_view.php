<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<?php 
    helper('html'); 
    $coverages = $data['coverages'];
    $pager_links = $data['pager_links'];
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
                <h5>Coverages</h5>

                <div class="clearfix">
                    <a type="button" class="btn btn-primary float-end" href="<?= base_url('/coverage/create'); ?>">Create New Coverage</a>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Code</th>
                            <th scope="col">Name</th>
                            <th scope="col">Has Fire Premium</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($coverages as $coverage): ?>
                            <tr>
                                <td><?= $coverage->coverage_id ?></td>
                                <td><strong><?= $coverage->code ?></strong></td>
                                <td><strong><?= $coverage->name ?></strong></td>
                                <td>
                                    <?= ($coverage->has_fire_premium) ? '<i class="bi bi-check2-square"></i>' : '<i class="bi bi-square"></i>' ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('/coverage/update/' . $coverage->coverage_id); ?>">Update</a>
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

<?= $this->endSection() ?>