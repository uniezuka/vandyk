<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-4">
        <div class="card">
            <div class="card-body">
                <?php echo view('FloodQuote/create_view_panel_1'); ?>
            </div>
        </div>
    </div>

    <div class="col-4">
        <div class="card">
            <div class="card-body">
                <?php echo view('FloodQuote/create_view_panel_2'); ?>
            </div>
        </div>
    </div>

    <div class="col-4">
        <div class="card">
            <div class="card-body">
                <?php echo view('FloodQuote/create_view_panel_3'); ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.insured_type').click(function() {
            // var inputValue = $(this).attr("value");
            // $("." + inputValue).toggle();
            $('#individual').toggle();
            $('#business').toggle();
        });
    });
</script>

<?= $this->endSection() ?>