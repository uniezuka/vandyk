<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<?php 
    helper('html'); 
    $transactionTypes = $data['transactionTypes'];
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
                <h5>Transaction Types</h5>

                <div class="clearfix">
                    <a type="button" class="btn btn-primary float-end" href="<?= base_url('/transaction_type/create'); ?>">Create New Transaction Type</a>
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
                        <?php foreach ($transactionTypes as $transactionType): ?>
                            <tr>
                                <td><?= $transactionType->transaction_type_id ?></td>
                                <td><strong><?= $transactionType->name ?></strong></td>
                                <td>
                                    <a href="<?= base_url('/transaction_type/update/' . $transactionType->transaction_type_id); ?>">Update</a>
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