<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<?php 
    helper('html'); 
    $fireCodes = $data['fireCodes'];
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
                <h5>Fire Codes</h5>

                <div class="clearfix">
                    <a type="button" class="btn btn-primary float-end" href="<?= base_url('/fire_code/create'); ?>">Create New Fire Code</a>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($fireCodes as $fireCode): ?>
                            <tr>
                                <td><?= $fireCode->fire_code_id ?></td>
                                <td><strong><?= $fireCode->name ?></strong></td>
                                <td>
                                    <a href="<?= base_url('/fire_code/update/' . $fireCode->fire_code_id); ?>">Update</a>
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