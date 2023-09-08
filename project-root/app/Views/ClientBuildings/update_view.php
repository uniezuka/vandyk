<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<?php
helper('html');
$client = $data['client'];
$building = $data['building'];

$currentDate = new DateTime();
$year = $currentDate->format("Y");

$foundations = $data['foundations'];
$elavatedFoundations = array_filter($foundations, function ($k) {
    return $k->is_elevated;
});

$nonElavatedFoundations = array_filter($foundations, function ($k) {
    return !$k->is_elevated;
});

$mortgage1 = null;
$mortgage2 = null;

$mortgages = $building->mortgages;
if (count($building->mortgages)) {
    $mortgage1 = $building->mortgages[0];
    $mortgage2 = $building->mortgages[1];
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

<div class="row">
    <div class="col-md-5 col-sm-12">
        <span class="d-block">Client ID: <?= $client->client_id ?> <br />Building ID: <?= $building->client_building_id ?></span>
        <span class="d-block fw-bold">Building - <?= $building->address ?></span>
    </div>
</div>

<div class="row">
    <div class="col-md-5 col-sm-12">
        <div class="form">
            <form method="post">
                <?= csrf_field() ?>

                <div class="row mb-3">
                    <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="Address" id="address" name="address" value="<?= set_value('address', $building->address) ?>" />
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="locality" placeholder="City" name="city" value="<?= set_value('city', $building->city) ?>" />
                    </div>

                    <div class="col-sm-2">
                        <?= stateSelect('state', set_value('state', $building->state)) ?>
                    </div>

                    <div class="col-sm-2">
                        <input type="text" class="form-control" id="postal_code" name="zipCode" value="<?= set_value('zipCode', $building->zip) ?>" />
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-8">
                        <input type="text" class="form-control" placeholder="County" id="county" name="county" value="<?= set_value('county', $building->county) ?>" />
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <input type="text" class="form-control" placeholder="Latitude" id="latitude" name="latitude" value="<?= set_value('latitude', $building->latitude) ?>" />
                    </div>

                    <div class="col-sm-3">
                        <input type="text" class="form-control" placeholder="Longitude" id="longitude" name="longitude" value="<?= set_value('longitude', $building->longitude) ?>" />
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Building Description:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" placeholder="Brief Description" name="description" value="<?= set_value('description', $building->description) ?>" />
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Building Occupancy:</label>
                    <div class="col-sm-9">
                        <?= occupancySelect('occupancy', set_value('occupancy', $building->occupancy)) ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Building Purpose:</label>
                    <div class="col-sm-9">
                        <select class="form-select" name="purpose">
                            <option <?= (set_value('purpose', $building->purpose) == "Commercial" || set_value('purpose', $building->purpose) == "") ? 'selected="selected"' : '' ?> value="Commercial">Commercial</option>
                            <option <?= (set_value('purpose', $building->purpose) == "Residential") ? 'selected="selected"' : '' ?> value="Residential">Residential</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Construction:</label>
                    <div class="col-sm-9">
                        <?= constructionSelect('construction', set_value('construction', $building->construction)) ?>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Square Footage of Building:</label>
                    <div class="col-sm-3">
                        <input type="number" min="1" max="20000" class="form-control" placeholder="Sq Ft" name="floorArea" value="<?= set_value('floorArea', $building->floor_area) ?>" />
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label"># of Floors:</label>
                    <div class="col-sm-3">
                        <input type="number" min="1" max="100" class="form-control" placeholder="Stories" name="floors" value="<?= set_value('floors', $building->no_of_floors) ?>" />
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Year Built:</label>
                    <div class="col-sm-3">
                        <input type="number" min="1800" max="<?= $year ?>" class="form-control" placeholder="Year" name="yearBuilt" value="<?= set_value('yearBuilt', $building->year_built) ?>" />
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Prior Loss in Last 3 Years:</label>
                    <div class="col-sm-3">
                        <?= form_radio('priorLoss', '1', (set_value('priorLoss', $building->prior_losses_3years) == "1" || set_value('priorLoss', $building->prior_losses_3years) == ""), ['class' => "form-check-input"]); ?>&nbsp;<span>Yes</span>
                        &nbsp;
                        <?= form_radio('priorLoss', '0', set_value('priorLoss', $building->prior_losses_3years) == "0", ['class' => "form-check-input"]); ?>&nbsp;<span>No</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Is Building Over Water:</label>
                    <div class="col-sm-6">
                        <?= form_radio('overWater', '0', (set_value('overWater', $building->building_over_water) == "0" || set_value('overWater', $building->building_over_water) == ""), ['class' => "form-check-input"]); ?>&nbsp;<span>No</span>
                        &nbsp;
                        <?= form_radio('overWater', '1', set_value('overWater', $building->building_over_water) == "1", ['class' => "form-check-input"]); ?>&nbsp;<span>Partially</span>
                        &nbsp;
                        <?= form_radio('overWater', '2', set_value('overWater', $building->building_over_water) == "2", ['class' => "form-check-input"]); ?>&nbsp;<span>Entirely</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <strong>Foundation</strong>

                    <label class="col-sm-3 col-form-label">Elevated Choices:</label>
                    <div class="col-sm-9">
                        <?php foreach ($elavatedFoundations as $foundation) : ?>
                            <?= form_radio('foundationType', $foundation->foundation_id, (set_value('foundationType', $building->foundation_type) == $foundation->foundation_id), ['class' => "form-check-input", 'data-is-elevated' => 'true']); ?>&nbsp;<span><?= $foundation->name ?></span>
                            &nbsp;
                        <?php endforeach; ?>
                    </div>

                    <label class="col-sm-3 col-form-label">Non-Elevated:</label>
                    <div class="col-sm-9">
                        <?php foreach ($nonElavatedFoundations as $foundation) : ?>
                            <?= form_radio('foundationType', $foundation->foundation_id, (set_value('foundationType', $building->foundation_type) == $foundation->foundation_id), ['class' => "form-check-input", 'data-is-elevated' => 'false']); ?>&nbsp;<span><?= $foundation->name ?></span>
                            &nbsp;
                        <?php endforeach; ?>
                    </div>
                </div>

                <div id="whenNotElevated" style="display: none;">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Has Basement?</label>
                        <div class="col-sm-3">
                            <?= form_radio('hasBasement', '1', set_value('hasBasement', $building->has_basement) == "1", ['class' => "form-check-input"]); ?>&nbsp;<span>Yes</span>
                            &nbsp;
                            <?= form_radio('hasBasement', '0', (set_value('hasBasement', $building->has_basement) == "0" || set_value('hasBasement', $building->has_basement) == ""), ['class' => "form-check-input"]); ?>&nbsp;<span>No</span>
                        </div>
                    </div>

                    <div id="basementCompletion" class="row mb-3" style="display: none;">
                        <label class="col-sm-3 col-form-label">Is Basement Finished?</label>
                        <div class="col-sm-3">
                            <?= form_radio('basementFinished', '1', set_value('basementFinished', $building->basement_completion_status) == "1", ['class' => "form-check-input"]); ?>&nbsp;<span>Yes</span>
                            &nbsp;
                            <?= form_radio('basementFinished', '0', (set_value('basementFinished', $building->basement_completion_status) == "0" || set_value('basementFinished') == ""), ['class' => "form-check-input"]); ?>&nbsp;<span>No</span>
                        </div>
                    </div>
                </div>

                <div id="whenElevated" style="display: none;">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Elevation Height:</label>
                        <div class="col-sm-3">
                            <input type="number" placeholder="0" name="elevationHeight" value="<?= set_value('elevationHeight', $building->elevation_height) ?>" />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Is there a Below floor Enclosure?</label>
                        <div class="col-sm-3">
                            <?= form_radio('hasBelowFloorEnclosure', '1', set_value('hasBelowFloorEnclosure', $building->has_below_floor_enclosure) == "1", ['class' => "form-check-input"]); ?>&nbsp;<span>Yes</span>
                            &nbsp;
                            <?= form_radio('hasBelowFloorEnclosure', '0', (set_value('hasBelowFloorEnclosure', $building->has_below_floor_enclosure) == "0" || set_value('hasBelowFloorEnclosure', $building->has_below_floor_enclosure) == ""), ['class' => "form-check-input"]); ?>&nbsp;<span>No</span>
                        </div>
                    </div>

                    <div class="row mb-3 withEnclosure">
                        <label class="col-sm-3 col-form-label">Enclosure Type</label>
                        <div class="col-sm-3">
                            <?= form_radio('enclosureType', '1', set_value('enclosureType', $building->below_floor_enclosure_type) == "1", ['class' => "form-check-input"]); ?>&nbsp;<span>Partial</span>
                            &nbsp;
                            <?= form_radio('enclosureType', '0', set_value('enclosureType', $building->below_floor_enclosure_type) == "0", ['class' => "form-check-input"]); ?>&nbsp;<span>Fully</span>
                        </div>
                    </div>

                    <div class="row mb-3 withEnclosure">
                        <label class="col-sm-3 col-form-label">Enclosure Completion Status</label>
                        <div class="col-sm-3">
                            <?= form_radio('completionStatus', '1', set_value('completionStatus', $building->below_floor_enclosure_completion_status) == "1", ['class' => "form-check-input"]); ?>&nbsp;<span>Finished</span>
                            &nbsp;
                            <?= form_radio('completionStatus', '0', set_value('completionStatus', $building->below_floor_enclosure_completion_status) == "0", ['class' => "form-check-input"]); ?>&nbsp;<span>Unfinished</span>
                        </div>
                    </div>

                    <div class="row mb-3 withEnclosure">
                        <label class="col-sm-3 col-form-label">Enclosure Has Elevator</label>
                        <div class="col-sm-3">
                            <?= form_radio('hasElevator', '1', set_value('hasElevator', $building->enclosure_has_elevator) == "1", ['class' => "form-check-input"]); ?>&nbsp;<span>Yes</span>
                            &nbsp;
                            <?= form_radio('hasElevator', '0', set_value('hasElevator', $building->enclosure_has_elevator) == "0", ['class' => "form-check-input"]); ?>&nbsp;<span>No</span>
                        </div>
                    </div>
                </div>

                <div id="valuesForBasementEnclosure" style="display: none;">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Equipment Value in Basement/Enclosure</label>
                        <div class="col-sm-3">
                            <input type="number" class="form-control" placeholder="Cost" name="equipmentValue" value="<?= set_value('equipmentValue', $building->bpp_equipment_or_machinery) ?>" />
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Other Personal Property Value in Basement/Enclosure</label>
                        <div class="col-sm-3">
                            <input type="number" class="form-control" placeholder="Cost" name="otherPersonalValue" value="<?= set_value('otherPersonalValue', $building->bpp_other) ?>" />
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Building Replacement Cost:</label>
                    <div class="col-sm-3">
                        <input type="number" class="form-control" placeholder="Replacement Cost" name="replacementCost" required value="<?= set_value('replacementCost', $building->rcv_tiv) ?>" />
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Business Personal Property Value:</label>
                    <div class="col-sm-3">
                        <input type="number" class="form-control" placeholder="Personal Value" name="personalValue" required value="<?= set_value('personalValue', $building->personal_property_tiv) ?>" />
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Business Income and Extra Expenses Value:</label>
                    <div class="col-sm-3">
                        <input type="number" class="form-control" placeholder="Income/Expense Total" name="incomeExpenseTotal" required value="<?= set_value('incomeExpenseTotal', $building->income_and_extra_expense_tiv) ?>" />
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Update Building</button>
            </form>
        </div>
    </div>

    <div class="col-md-5 col-sm-12">
        <div class="form">
            <form method="post" action="<?= base_url('/client/' . $client->client_id . '/building/' . $building->client_building_id. '/mortgage-update') ?>">
                <?= csrf_field() ?>

                <div class="row mb-3">
                    <h6>1st Mortgagee</h6>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Name:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="mortgage1Name" value="<?= ($mortgage1 == null) ? "" : $mortgage1->name ?>" />
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Name2:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="mortgage1Name2" value="<?= ($mortgage1 == null) ? "" : $mortgage1->name2 ?>" />
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Address:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="mortgage1Address" value="<?= ($mortgage1 == null) ? "" : $mortgage1->address ?>" />
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">City:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="mortgage1City" value="<?= ($mortgage1 == null) ? "" : $mortgage1->city ?>" />
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">State:</label>
                    <div class="col-sm-3">
                        <?= stateSelect('mortgage1State', ($mortgage1 == null) ? "" : $mortgage1->state) ?>
                    </div>

                    <label class="col-sm-1 col-form-label">Zip:</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="mortgage1Zip" value="<?= ($mortgage1 == null) ? "" : $mortgage1->zip ?>" />
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Phone:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="mortgage1Phone" value="<?= ($mortgage1 == null) ? "" : $mortgage1->phone ?>" />
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Loan #:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="mortgage1Loan" value="<?= ($mortgage1 == null) ? "" : $mortgage1->loan_number ?>" />
                    </div>
                </div>

                <div class="row mb-3">
                    <h6>2nd Mortgagee</h6>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Name:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="mortgage2Name" value="<?= ($mortgage2 == null) ? "" : $mortgage2->name ?>" />
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Name2:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="mortgage2Name2" value="<?= ($mortgage2 == null) ? "" : $mortgage2->name2 ?>" />
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Address:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="mortgage2Address" value="<?= ($mortgage2 == null) ? "" : $mortgage2->address ?>" />
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">City:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="mortgage2City" value="<?= ($mortgage2 == null) ? "" : $mortgage2->city ?>" />
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">State:</label>
                    <div class="col-sm-3">
                        <?= stateSelect('mortgage2State', ($mortgage2 == null) ? "" : $mortgage2->state) ?>
                    </div>

                    <label class="col-sm-1 col-form-label">Zip:</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="mortgage2Zip" value="<?= ($mortgage2 == null) ? "" : $mortgage2->zip ?>" />
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Phone:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="mortgage2Phone" value="<?= ($mortgage2 == null) ? "" : $mortgage2->phone ?>" />
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Loan #:</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="mortgage2Loan" value="<?= ($mortgage2 == null) ? "" : $mortgage2->loan_number ?>" />
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Update Mortgage</button>
            </form>
        </div>
    </div>

    <div class="col-md-2 col-sm-12">
        <p>Flood Zone: <?= $building->flood_zone ?></p>
        <p>BFE: <?= $building->water_surface_elevation ?></p>
        <p>HAG: <?= $building->property_elevation ?></p>
        <p>Cat1 Surge: <?= $building->category1_water_depth ?></p>
        <p>Cat2 Surge: <?= $building->category2_water_depth ?></p>
        <p>Cat3 Surge: <?= $building->category3_water_depth ?></p>
        <p>Cat4 Surge: <?= $building->category4_water_depth ?></p>
        <p>Cat5 Surge: <?= $building->category5_water_depth ?></p>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        function toggleElevate(isElavated) {
            $('#whenNotElevated').hide();
            $('#whenElevated').hide();

            isElavated ? $('#whenElevated').show() : $('#whenNotElevated').show();
        }

        function toggleHasBasement(hasBasement) {
            $('#basementCompletion').hide();
            $('#valuesForBasementEnclosure').hide();

            var hasSelectedBasementFinished = $('input[name="basementFinished"]:checked');
            if (!hasSelectedBasementFinished) {
                $('input[name="basementFinished"][value="0"]').prop('checked', true);
            }
            if (hasBasement === '1') {
                $('#basementCompletion').show();
                $('#valuesForBasementEnclosure').show();
            }
        }

        function toggleHasBelowFloorEnclosure(hasBelowFloorEnclosure) {
            $('.withEnclosure').hide();

            if (hasBelowFloorEnclosure === '1') {
                $('.withEnclosure').show();
                $('#valuesForBasementEnclosure').show();
            }
        }

        $('input[name="foundationType"]').click(function() {
            var isElavated = $(this).data('is-elevated');

            toggleElevate(isElavated);
        });

        $('input[name="hasBasement"]').click(function() {
            var val = $(this).val();

            toggleHasBasement(val);
        });

        $('.withEnclosure').hide();

        $('input[name="hasBelowFloorEnclosure"]').click(function() {
            var val = $(this).val();

            toggleHasBelowFloorEnclosure(val);
        });

        $('input[type="number"]').on('keydown', function(event) {
            if (event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || event.keyCode == 13 || event.keyCode == 190 || event.keyCode == 110 || event.keyCode == 189 || event.keyCode == 109 ||
                // Allow: Ctrl+A
                (event.keyCode == 65 && event.ctrlKey === true) ||
                // Allow: home, end, left, right
                (event.keyCode >= 35 && event.keyCode <= 39) ||
                // Allow: numbers from the top row (0-9) and numeric keypad (0-9)
                ((event.keyCode >= 48 && event.keyCode <= 57) || (event.keyCode >= 96 && event.keyCode <= 105))) {
                // Let it happen, don't do anything
                return;
            } else {
                // Prevent any other keypress
                event.preventDefault();
            }
        });

        var elevate = $('input[name="foundationType"]:checked').data('is-elevated');
        var hasBasement = $('input[name="hasBasement"]:checked').val();
        var hasBelowFloorEnclosure = $('input[name="hasBelowFloorEnclosure"]:checked').val();

        if (elevate !== undefined) {
            toggleElevate(elevate);
            toggleHasBasement(hasBasement);
            toggleHasBelowFloorEnclosure(hasBelowFloorEnclosure);
        }
    });
</script>

<?= $this->endSection() ?>