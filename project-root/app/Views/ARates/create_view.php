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
                <label class="col-sm-2 col-form-label text-end">Dwl4: </label>
                <div class="col-sm-2">
                    <input type="number" step="0.01" class="form-control" name="dwl4" value="<?= set_value('dwl4') ?>">
                </div>

                <label class="col-sm-2 col-form-label text-end">Cont4: </label>
                <div class="col-sm-2">
                    <input type="number" step="0.01" class="form-control" name="cont4" value="<?= set_value('cont4') ?>">
                </div>

                <label class="col-sm-2 col-form-label text-end">Both4: </label>
                <div class="col-sm-2">
                    <input type="number" step="0.01" class="form-control" name="both4" value="<?= set_value('both4') ?>">
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-end">Dwl3: </label>
                <div class="col-sm-2">
                    <input type="number" step="0.01" class="form-control" name="dwl3" value="<?= set_value('dwl3') ?>">
                </div>

                <label class="col-sm-2 col-form-label text-end">Cont3: </label>
                <div class="col-sm-2">
                    <input type="number" step="0.01" class="form-control" name="cont3" value="<?= set_value('cont3') ?>">
                </div>

                <label class="col-sm-2 col-form-label text-end">Both3: </label>
                <div class="col-sm-2">
                    <input type="number" step="0.01" class="form-control" name="both3" value="<?= set_value('both3') ?>">
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-end">Dwl2: </label>
                <div class="col-sm-2">
                    <input type="number" step="0.01" class="form-control" name="dwl2" value="<?= set_value('dwl2') ?>">
                </div>

                <label class="col-sm-2 col-form-label text-end">Cont2: </label>
                <div class="col-sm-2">
                    <input type="number" step="0.01" class="form-control" name="cont2" value="<?= set_value('cont2') ?>">
                </div>

                <label class="col-sm-2 col-form-label text-end">Both2: </label>
                <div class="col-sm-2">
                    <input type="number" step="0.01" class="form-control" name="both2" value="<?= set_value('both2') ?>">
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-end">Dwl1: </label>
                <div class="col-sm-2">
                    <input type="number" step="0.01" class="form-control" name="dwl1" value="<?= set_value('dwl1') ?>">
                </div>

                <label class="col-sm-2 col-form-label text-end">Cont1: </label>
                <div class="col-sm-2">
                    <input type="number" step="0.01" class="form-control" name="cont1" value="<?= set_value('cont1') ?>">
                </div>

                <label class="col-sm-2 col-form-label text-end">Both1: </label>
                <div class="col-sm-2">
                    <input type="number" step="0.01" class="form-control" name="both1" value="<?= set_value('both1') ?>">
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-end">Dwl0: </label>
                <div class="col-sm-2">
                    <input type="number" step="0.01" class="form-control" name="dwl0" value="<?= set_value('dwl0') ?>">
                </div>

                <label class="col-sm-2 col-form-label text-end">Cont0: </label>
                <div class="col-sm-2">
                    <input type="number" step="0.01" class="form-control" name="cont0" value="<?= set_value('cont0') ?>">
                </div>

                <label class="col-sm-2 col-form-label text-end">Both0: </label>
                <div class="col-sm-2">
                    <input type="number" step="0.01" class="form-control" name="both0" value="<?= set_value('both0') ?>">
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label text-end">Dwl-1: </label>
                <div class="col-sm-2">
                    <input type="number" step="0.01" class="form-control" name="dwl-1" value="<?= set_value('dwl-1') ?>">
                </div>

                <label class="col-sm-2 col-form-label text-end">Cont-1: </label>
                <div class="col-sm-2">
                    <input type="number" step="0.01" class="form-control" name="cont-1" value="<?= set_value('cont-1') ?>">
                </div>

                <label class="col-sm-2 col-form-label text-end">Both-1: </label>
                <div class="col-sm-2">
                    <input type="number" step="0.01" class="form-control" name="both-1" value="<?= set_value('both-1') ?>">
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">&nbsp;</label>
                <div class="col-sm-8">
                    <?= floodFoundationSelect('flood_foundation') ?>
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

            if (selectedFloodFoundation !== '') {

                var floodFoundationId = 'floodFoundation_' + floodFoundationCount;

                floodFoundationCount++;

                var li = $('<li>')
                    .addClass('list-group-item d-flex justify-content-between align-items-center')
                    .text(selectedText);

                var removeBtn = $('<button>').addClass('btn btn-danger btn-sm').text('Remove');

                removeBtn.click(function() {
                    li.remove();
                    $('#' + floodFoundationId).remove();
                });

                li.append(removeBtn);

                $('#floodFoundationList').append(li);

                var hiddenInput = $('<input>').attr({
                    type: 'hidden',
                    name: 'floodFoundations[]',
                    value: selectedFloodFoundation,
                    id: floodFoundationId
                });

                $('#selectedFloodFoundationsContainer').append(hiddenInput);

                $('#flood_foundation').val('');
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