<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<?php 
    helper('html'); 
    $insurers = $data['insurers'];
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
                <h5>Insurers</h5>

                <div class="clearfix">
                    <a type="button" class="btn btn-primary float-end" href="<?= base_url('/insurer/create'); ?>">Create New Insurer</a>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">NAIC</th>
                            <th scope="col">Name</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($insurers as $insurer): ?>
                            <tr>
                                <td><?= $insurer->insurer_id ?></td>
                                <td><strong><?= $insurer->naic ?></strong></td>
                                <td><strong><?= $insurer->name ?></strong></td>
                                <td>
                                    <a href="<?= base_url('/insurer/update/' . $insurer->insurer_id); ?>">Update</a>&nbsp;
                                    <?php if ($insurer->is_active) : ?>
                                        <a href="<?= base_url('/insurer/deactivate/' . $insurer->insurer_id); ?>">Deactivate</a>
                                    <?php else : ?>
                                        <a href="<?= base_url('/insurer/activate/' . $insurer->insurer_id); ?>">Activate</a>
                                    <?php endif; ?>
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