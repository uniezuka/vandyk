<?= $this->extend('layouts/default', ['data' => $data]) ?>
<?= $this->section('content') ?>

<?php
helper('html');
$client = $data['client'];
$floodQuote = $data['floodQuote'];
$floodQuoteMetas = $data['floodQuoteMetas'];

$mortgage1 = $data['mortgage1'];
$mortgage2 = $data['mortgage2'];

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

<div class="form">
    <form method="POST" name="updateForm" id="updateForm">
        <?= csrf_field() ?>

        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <?php echo view('FloodQuote/update_view_panel_1', $data); ?>
                    </div>
                </div>
            </div>

            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <?php echo view('FloodQuote/update_view_panel_2'); ?>
                    </div>
                </div>
            </div>

            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <?php echo view('FloodQuote/update_view_panel_3'); ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.entity_type').click(function() {
            // var inputValue = $(this).attr("value");
            // $("." + inputValue).toggle();
            $('#individual').toggle();
            $('#business').toggle();
        });
    });
</script>

<?= $this->endSection() ?>