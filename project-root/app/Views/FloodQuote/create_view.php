<?= $this->extend('layouts/default', ['data' => $data]) ?>
<?= $this->section('content') ?>

<?php
helper('html');
$client = $data['client'];
?>

<?php if (session()->getFlashdata('error') || validation_errors()) : ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
        <?= validation_list_errors() ?>
    </div>
<?php endif; ?>

<div class="form">
    <form method="POST" name="createForm" id="createForm">
        <?= csrf_field() ?>
        <div class="row">
            <div class="col-4">
                <div class="card">
                    <div class="card-body">
                        <?php echo view('FloodQuote/create_view_panel_1', $data); ?>
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

        $('#isSameAddress').on('change', function() {
            if ($(this).prop('checked')) {
                $('#propertyAddress').val($('#address').val());
                $('#propertyCity').val($('#city').val());
                $('#propertyZip').val($('#zip').val());
                $('#propertyState').val($('#state').val());
            }
        });

        $('input[name="effectiveDate"]').change(function() {
            var effectiveDate = new Date($(this).val());

            if (!isNaN(effectiveDate.getTime())) {
                var expirationDate = new Date(effectiveDate);
                expirationDate.setFullYear(expirationDate.getFullYear() + 1);

                var formattedDate = expirationDate.toISOString().split('T')[0];

                $('input[name="expirationDate"]').val(formattedDate);
            }
        });

        $('input[name="flfe"]').change(function() {
            calculateForm('elevationDifference', 100, -1, '#flfe', '-', '#bfe');
        });

        $('input[name="bfe"]').change(function() {
            calculateForm('elevationDifference', 100, -1, '#flfe', '-', '#bfe');
        });

        $('input[name="covABuilding"]').change(function() {
            calculateForm('rceRatio', 10000, -1, '#covABuilding', '/', '#buildingReplacementCost');
        });

        $('input[name="buildingReplacementCost"]').change(function() {
            calculateForm('rceRatio', 10000, -1, '#covABuilding', '/', '#buildingReplacementCost');
        });
    });
</script>

<?= $this->endSection() ?>