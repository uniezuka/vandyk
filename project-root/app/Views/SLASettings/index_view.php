<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<?php 
    helper('html'); 
    $sla_settings = $data['sla_settings'];
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
                <h5>SLA Number Generator</h5>

                <div class="clearfix">
                    <a type="button" class="btn btn-primary float-end" href="<?= base_url('/sla_setting/create'); ?>">Create New SLA Setting</a>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Year</th>
                            <th scope="col">Prefix</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sla_settings as $sla_setting): ?>
                            <tr>
                                <td><strong><?= $sla_setting->year ?></strong></td>
                                <td><strong><?= $sla_setting->prefix ?></strong></td>
                                <td>
                                    <a href="<?= base_url('/sla_setting/update/' . $sla_setting->sla_setting_id); ?>">Update</a>&nbsp;
                                    <?php if (!$sla_setting->is_current) : ?>
                                        <a href="<?= base_url('/sla_setting/set_current/' . $sla_setting->sla_setting_id); ?>">Make Current</a>
                                    <?php else: ?>
                                        <span>Current</span>
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