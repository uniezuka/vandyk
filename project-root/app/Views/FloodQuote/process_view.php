<?= $this->extend('layouts/default', ['data' => $data]) ?>
<?= $this->section('content') ?>

<?php
helper('html');
extract($data);

function getMetaValue($floodQuoteMetas, $meta_key, $default = '')
{
    foreach ($floodQuoteMetas as $meta) {
        if ($meta->meta_key === $meta_key) {
            return $meta->meta_value;
        }
    }
    return $default;
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

<div class="form">
    <form method="POST" name="updateForm" id="updateForm">
        <?= csrf_field() ?>

        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <?php echo view('FloodQuote/process_view_panel_1', $data); ?>
                    </div>
                </div>
            </div>

            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <?php echo view('FloodQuote/process_view_panel_2'); ?>
                    </div>
                </div>
            </div>

            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <?php echo view('FloodQuote/process_view_panel_3'); ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?= $this->endSection() ?>