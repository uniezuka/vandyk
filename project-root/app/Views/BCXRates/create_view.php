<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<?php
helper('html');
extract($data);
$count = 1;
?>

<?php if (session()->getFlashdata('error') || validation_errors()) : ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
        <?= validation_list_errors() ?>
    </div>
<?php endif; ?>

<div class="col-md-5 col-sm-12">
    <div class="form">
        <form method="post">
            <?= csrf_field() ?>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Description: </label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="description" value="<?= set_value('description') ?>">
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-end">Rate: </label>
                <div class="col-sm-2">
                    <input type="number" step="0.01" class="form-control" name="rate" value="<?= set_value('rate') ?>">
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">&nbsp;</label>
                <div class="col-sm-3">
                    <?= floodFoundationSelect('flood_foundation') ?>
                </div>

                <div class="col-sm-3">
                    <input type="number" class="form-control" id="number_of_floors" placeholder="# of Floors" />
                </div>

                <div class="d-flex align-items-end col-sm-2">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="is_more_than" />
                        <label class="form-check-label">More than</label>
                    </div>
                </div>

                <div class="col-sm-2">
                    <button type="button" class="btn btn-primary" id="addFloodFoundationBtn">+</button>
                </div>
            </div>

            <div id="selectedFloodFoundationsContainer">
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">&nbsp;</label>
                <div class="col-sm-10">
                    <ul id="floodFoundationList" class="list-group">
                    </ul>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Add Rate</button>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {

        var floodFoundationCount = <?= $count ?>;

        $('#addFloodFoundationBtn').click(function() {

            var selectedFloodFoundation = $('#flood_foundation').val();
            var selectedText = $('#flood_foundation').find('option:selected').text();
            var numberOfFloors = $('#number_of_floors').val();
            var isMoreThanChecked = $('#is_more_than').is(':checked');

            if (selectedFloodFoundation !== '' && numberOfFloors !== '') {

                var floodFoundationId = 'floodFoundation_' + floodFoundationCount;
                var numberOfFloorsId = 'numberOfFloors_' + floodFoundationCount;
                var isMoreThanId = 'isMoreThan_' + floodFoundationCount;

                floodFoundationCount++;

                var li = $('<li>')
                    .addClass('list-group-item d-flex justify-content-between align-items-center')
                    .text(selectedText + ' - Floors: ' + numberOfFloors + (isMoreThanChecked ? ' >= (More than)' : ''));

                var removeBtn = $('<button>').addClass('btn btn-danger btn-sm').text('Remove');

                removeBtn.click(function() {
                    li.remove();
                    $('#' + floodFoundationId).remove();
                    $('#' + numberOfFloorsId).remove();
                    $('#' + isMoreThanId).remove();
                });

                li.append(removeBtn);

                $('#floodFoundationList').append(li);

                var hiddenInput = $('<input>').attr({
                    type: 'hidden',
                    name: 'floodFoundations[]',
                    value: selectedFloodFoundation,
                    id: floodFoundationId
                });

                var hiddenInputNumberOfFloors = $('<input>').attr({
                    type: 'hidden',
                    name: 'numberOfFloors[]',
                    value: numberOfFloors,
                    id: numberOfFloorsId
                });

                var hiddenInputIsMoreThan = $('<input>').attr({
                    type: 'hidden',
                    name: 'isMoreThan[]',
                    value: isMoreThanChecked ? 1 : 0,
                    id: isMoreThanId
                });


                $('#selectedFloodFoundationsContainer').append(hiddenInput);
                $('#selectedFloodFoundationsContainer').append(hiddenInputNumberOfFloors);
                $('#selectedFloodFoundationsContainer').append(hiddenInputIsMoreThan);

                $('#flood_foundation').val('');
                $('#number_of_floors').val('');
                $('#is_more_than').prop('checked', false);
            }
        });

        $('.remove-flood-foundation-btn').click(function() {
            var count = $(this).data('flood-foundation-count');
            $(this).closest('li').remove();
            $('#floodFoundation_' + count).remove();
        });
    });
</script>

<?= $this->endSection() ?>