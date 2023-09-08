<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<?php
helper('html');
$client = $data['client'];

$currentDate = new DateTime();
$year = $currentDate->format("Y");

$foundations = $data['foundations'];
$elavatedFoundations = array_filter($foundations, function ($k) {
    return $k->is_elevated;
});

$nonElavatedFoundations = array_filter($foundations, function ($k) {
    return !$k->is_elevated;
})
?>

<?php if (session()->getFlashdata('error') || validation_errors()) : ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
        <?= validation_list_errors() ?>
    </div>
<?php endif; ?>

<div class="col-md-5 col-sm-12">
    <input id="address" type="hidden" value="500 Barnegat Blvd N, Barnegat, NJ 08005" />

    <span class="d-block">Client ID: <?= $client->client_id ?></span>

    <div class="form">
        <form method="post">
            <?= csrf_field() ?>
            <div class="row mb-3">
                <div class="d-flex p-2">
                    <input class="d-flex form-control w-75 me-1" id="searchLocation" name="search" type="search" placeholder="Enter a location" />
                </div>

                <div class="row">
                    <div class="col-1"></div>
                    <div class="col-10">
                        <div id="map" style="height: 350px; width: 100%;"></div>
                    </div>
                    <div class="col-1"></div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-12">
                    <input type="text" id="location-address" class="form-control" name="location-address" readonly />
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="street_number" placeholder="Number" name="streetNumber" value="<?= set_value('streetNumber') ?>" />
                </div>

                <div class="col-sm-10">
                    <input type="text" class="form-control" id="route" placeholder="Street" name="street" value="<?= set_value('street') ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="locality" placeholder="City" name="city" value="<?= set_value('city') ?>" />
                </div>

                <div class="col-sm-2">
                    <input type="text" class="form-control" id="administrative_area_level_1" name="state" value="<?= set_value('state') ?>" />
                </div>

                <div class="col-sm-2">
                    <input type="text" class="form-control" id="postal_code" name="zipCode" value="<?= set_value('zipCode') ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-8">
                    <input type="text" class="form-control" placeholder="County" id="administrative_area_level_2" name="county" value="<?= set_value('county') ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-3">
                    <input type="text" class="form-control" placeholder="Latitude" id="latitude" name="latitude" value="<?= set_value('latitude') ?>" />
                </div>

                <div class="col-sm-3">
                    <input type="text" class="form-control" placeholder="Longitude" id="longitude" name="longitude" value="<?= set_value('longitude') ?>" />
                </div>

                <input name="placeId" type="hidden" id="placeId" placeholder="Google ID" />
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Building Description:</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" placeholder="Brief Description" name="description" value="<?= set_value('description') ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Building Occupancy:</label>
                <div class="col-sm-9">
                    <?= occupancySelect('occupancy', set_value('occupancy')) ?>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Building Purpose:</label>
                <div class="col-sm-9">
                    <select class="form-select" name="purpose">
                        <option <?= (set_value('purpose') == "Commercial" || set_value('purpose') == "") ? 'selected="selected"' : '' ?> value="Commercial">Commercial</option>
                        <option <?= (set_value('purpose') == "Residential") ? 'selected="selected"' : '' ?> value="Residential">Residential</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Construction:</label>
                <div class="col-sm-9">
                    <?= constructionSelect('construction', set_value('construction')) ?>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Square Footage of Building:</label>
                <div class="col-sm-3">
                    <input type="number" min="1" max="20000" class="form-control" placeholder="Sq Ft" name="floorArea" value="<?= set_value('floorArea') ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label"># of Floors:</label>
                <div class="col-sm-3">
                    <input type="number" min="1" max="100" class="form-control" placeholder="Stories" name="floors" value="<?= set_value('floors') ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Year Built:</label>
                <div class="col-sm-3">
                    <input type="number" min="1800" max="<?= $year ?>" class="form-control" placeholder="Year" name="yearBuilt" value="<?= set_value('yearBuilt') ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Prior Loss in Last 3 Years:</label>
                <div class="col-sm-3">
                    <?= form_radio('priorLoss', '1', (set_value('priorLoss') == "1" || set_value('priorLoss') == ""), ['class' => "form-check-input"]); ?>&nbsp;<span>Yes</span>
                    &nbsp;
                    <?= form_radio('priorLoss', '0', set_value('priorLoss') == "0", ['class' => "form-check-input"]); ?>&nbsp;<span>No</span>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Is Building Over Water:</label>
                <div class="col-sm-6">
                    <?= form_radio('overWater', '0', (set_value('overWater') == "0" || set_value('overWater') == ""), ['class' => "form-check-input"]); ?>&nbsp;<span>No</span>
                    &nbsp;
                    <?= form_radio('overWater', '1', set_value('overWater') == "1", ['class' => "form-check-input"]); ?>&nbsp;<span>Partially</span>
                    &nbsp;
                    <?= form_radio('overWater', '2', set_value('overWater') == "2", ['class' => "form-check-input"]); ?>&nbsp;<span>Entirely</span>
                </div>
            </div>

            <div class="row mb-3">
                <strong>Foundation</strong>

                <label class="col-sm-3 col-form-label">Elevated Choices:</label>
                <div class="col-sm-9">
                    <?php foreach ($elavatedFoundations as $foundation) : ?>
                        <?= form_radio('foundationType', $foundation->foundation_id, (set_value('foundationType') == $foundation->foundation_id), ['class' => "form-check-input", 'data-is-elevated' => 'true']); ?>&nbsp;<span><?= $foundation->name ?></span>
                        &nbsp;
                    <?php endforeach; ?>
                </div>

                <label class="col-sm-3 col-form-label">Non-Elevated:</label>
                <div class="col-sm-9">
                    <?php foreach ($nonElavatedFoundations as $foundation) : ?>
                        <?= form_radio('foundationType', $foundation->foundation_id, (set_value('foundationType') == $foundation->foundation_id), ['class' => "form-check-input", 'data-is-elevated' => 'false']); ?>&nbsp;<span><?= $foundation->name ?></span>
                        &nbsp;
                    <?php endforeach; ?>
                </div>
            </div>

            <div id="whenNotElevated" style="display: none;">
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Has Basement?</label>
                    <div class="col-sm-3">
                        <?= form_radio('hasBasement', '1', set_value('hasBasement') == "1", ['class' => "form-check-input"]); ?>&nbsp;<span>Yes</span>
                        &nbsp;
                        <?= form_radio('hasBasement', '0', (set_value('hasBasement') == "0" || set_value('hasBasement') == ""), ['class' => "form-check-input"]); ?>&nbsp;<span>No</span>
                    </div>
                </div>

                <div id="basementCompletion" class="row mb-3" style="display: none;">
                    <label class="col-sm-3 col-form-label">Is Basement Finished?</label>
                    <div class="col-sm-3">
                        <?= form_radio('basementFinished', '1', set_value('basementFinished') == "1", ['class' => "form-check-input"]); ?>&nbsp;<span>Yes</span>
                        &nbsp;
                        <?= form_radio('basementFinished', '0', (set_value('basementFinished') == "0" || set_value('basementFinished') == ""), ['class' => "form-check-input"]); ?>&nbsp;<span>No</span>
                    </div>
                </div>
            </div>

            <div id="whenElevated" style="display: none;">
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Elevation Height:</label>
                    <div class="col-sm-3">
                        <input type="number" placeholder="0" name="elevationHeight" value="<?= set_value('elevationHeight') ?>" />
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Is there a Below floor Enclosure?</label>
                    <div class="col-sm-3">
                        <?= form_radio('hasBelowFloorEnclosure', '1', set_value('hasBelowFloorEnclosure') == "1", ['class' => "form-check-input"]); ?>&nbsp;<span>Yes</span>
                        &nbsp;
                        <?= form_radio('hasBelowFloorEnclosure', '0', (set_value('hasBelowFloorEnclosure') == "0" || set_value('hasBelowFloorEnclosure') == ""), ['class' => "form-check-input"]); ?>&nbsp;<span>No</span>
                    </div>
                </div>

                <div class="row mb-3 withEnclosure">
                    <label class="col-sm-3 col-form-label">Enclosure Type</label>
                    <div class="col-sm-3">
                        <?= form_radio('enclosureType', '1', set_value('enclosureType') == "1", ['class' => "form-check-input"]); ?>&nbsp;<span>Partial</span>
                        &nbsp;
                        <?= form_radio('enclosureType', '0', set_value('enclosureType') == "0", ['class' => "form-check-input"]); ?>&nbsp;<span>Fully</span>
                    </div>
                </div>

                <div class="row mb-3 withEnclosure">
                    <label class="col-sm-3 col-form-label">Enclosure Completion Status</label>
                    <div class="col-sm-3">
                        <?= form_radio('completionStatus', '1', set_value('completionStatus') == "1", ['class' => "form-check-input"]); ?>&nbsp;<span>Finished</span>
                        &nbsp;
                        <?= form_radio('completionStatus', '0', set_value('completionStatus') == "0", ['class' => "form-check-input"]); ?>&nbsp;<span>Unfinished</span>
                    </div>
                </div>

                <div class="row mb-3 withEnclosure">
                    <label class="col-sm-3 col-form-label">Enclosure Has Elevator</label>
                    <div class="col-sm-3">
                        <?= form_radio('hasElevator', '1', set_value('hasElevator') == "1", ['class' => "form-check-input"]); ?>&nbsp;<span>Yes</span>
                        &nbsp;
                        <?= form_radio('hasElevator', '0', set_value('hasElevator') == "0", ['class' => "form-check-input"]); ?>&nbsp;<span>No</span>
                    </div>
                </div>
            </div>

            <div id="valuesForBasementEnclosure" style="display: none;">
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Equipment Value in Basement/Enclosure</label>
                    <div class="col-sm-3">
                        <input type="number" class="form-control" placeholder="Cost" name="equipmentValue" value="<?= set_value('equipmentValue') ?>" />
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Other Personal Property Value in Basement/Enclosure</label>
                    <div class="col-sm-3">
                        <input type="number" class="form-control" placeholder="Cost" name="otherPersonalValue" value="<?= set_value('otherPersonalValue') ?>" />
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Building Replacement Cost:</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" placeholder="Replacement Cost" name="replacementCost" required value="<?= set_value('replacementCost') ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Business Personal Property Value:</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" placeholder="Personal Value" name="personalValue" required value="<?= set_value('personalValue') ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Business Income and Extra Expenses Value:</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" placeholder="Income/Expense Total" name="incomeExpenseTotal" required value="<?= set_value('incomeExpenseTotal') ?>" />
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Add Building</button>
        </form>
    </div>
</div>

<script type="text/javascript">
    var geocoder;
    var map;
    var marker;

    var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'short_name',
        country: 'long_name',
        postal_code: 'short_name',
        sublocality_level_1: 'short_name',
        administrative_area_level_2: 'short_name',
        administrative_area_level_3: 'short_name'
    };

    function initMap() {
        geocoder = new google.maps.Geocoder();
        var latlng = new google.maps.LatLng(39.5873284, -74.225578);
        var mapOptions = {
            zoom: 10,
            center: {
                lat: 39.5873284,
                lng: -74.225578
            },
            mapTypeId: 'hybrid'
        }

        map = new google.maps.Map(document.getElementById('map'), mapOptions);

        var input = document.getElementById('searchLocation');

        var autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.bindTo('bounds', map);

        var address = document.getElementById('address').value;

        geocoder.geocode({
            'address': address
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location);
                if (marker) {
                    marker.setMap(null);
                    if (infowindow) infowindow.close();
                }

                marker = new google.maps.Marker({
                    map: map,
                    draggable: true,
                    position: results[0].geometry.location
                });

                google.maps.event.addListenerOnce(map, 'idle', function() {
                    google.maps.event.trigger(map, 'resize');
                });

                google.maps.event.addListener(marker, 'dragend', function() {
                    geocodePosition(marker.getPosition());
                });

                google.maps.event.trigger(marker, 'click');
            } else {
                alert('Geocode was not successful for the following reason: ' + status);
            }
        });

        autocomplete.addListener('place_changed', function() {
            marker.setVisible(false);
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                window.alert("No details available for input: '" + place.name + "'");
                return;
            }

            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(19);
            }
            marker.setPosition(place.geometry.location);
            marker.setVisible(true);

            var address = '';
            if (place.address_components) {
                address = [
                    (place.address_components[0] && place.address_components[0].short_name || ''),
                    (place.address_components[1] && place.address_components[1].short_name || ''),
                    (place.address_components[2] && place.address_components[2].short_name || ''),
                    (place.address_components[3] && place.address_components[3].short_name || ''),
                    (place.address_components[4] && place.address_components[4].short_name || ''),
                    (place.address_components[5] && place.address_components[5].short_name || '')
                ].join(' ');
            }

            document.getElementById('location-address').value = place.name + ', ' + address;
            document.getElementById('latitude').value = place.geometry.location.lat();
            document.getElementById('latitude').disabled = false;
            document.getElementById('longitude').value = place.geometry.location.lng();
            document.getElementById('longitude').disabled = false;
            document.getElementById('placeId').value = place.place_id;

            for (var component in componentForm) {
                if (document.getElementById(component)) {
                    document.getElementById(component).value = '';
                    document.getElementById(component).disabled = false;
                }
            }

            for (var i = 0; i < place.address_components.length; i++) {
                var addressType = place.address_components[i].types[0];
                if (componentForm[addressType]) {
                    var val = place.address_components[i][componentForm[addressType]];

                    if (document.getElementById(addressType)) {
                        document.getElementById(addressType).value = val;
                    }
                }
            }
        });
    }

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

    function geocodePosition(pos) {
        geocoder.geocode({
            latLng: pos
        }, function(responses) {
            if (responses && responses.length > 0) {
                marker.formatted_address = responses[0].formatted_address;
                marker.locationLat = responses[0].geometry.location.lat();
                marker.locationLon = responses[0].geometry.location.lng();
                marker.place_id = responses[0].place_id;
                marker.url = responses[0].url;
                marker.name = responses[0].name;
            } else {
                marker.formatted_address = 'Cannot determine address at this location.';
            }

            document.getElementById('location-address').innerHTML = marker.formatted_address;
            document.getElementById('latitude').value = marker.locationLat;
            document.getElementById('latitude').disabled = false;
            document.getElementById('longitude').value = marker.locationLon;
            document.getElementById('longitude').disabled = false;
            document.getElementById('placeId').value = marker.place_id;

            for (var component in componentForm) {
                if (document.getElementById(component)) {
                    document.getElementById(component).value = '';
                    document.getElementById(component).disabled = false;
                }
            }

            for (var i = 0; i < responses[0].address_components.length; i++) {
                var addressType = responses[0].address_components[i].types[0];
                if (componentForm[addressType]) {
                    var val = responses[0].address_components[i][componentForm[addressType]];

                    if (document.getElementById(addressType)) {
                        document.getElementById(addressType).value = val;
                    }
                }
            }
        });
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBrDUbKjdjCC0yWcq_cLpUejnkSMFZP_6k&libraries=places&callback=initMap" async defer></script>

<?= $this->endSection() ?>