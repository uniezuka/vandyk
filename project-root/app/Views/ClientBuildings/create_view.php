<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<?php
helper('html');
$client = $data['client'];

$currentDate = new DateTime();
$year = $currentDate->format("Y");
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
                <div class="col-sm-2">
                    <input type="text" class="form-control" placeholder="Number" name="number" value="<?= set_value('number') ?>" />
                </div>

                <div class="col-sm-10">
                    <input type="text" class="form-control" placeholder="Street" name="street" value="<?= set_value('street') ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-8">
                    <input type="text" class="form-control" placeholder="City" name="city" value="<?= set_value('city') ?>" />
                </div>

                <div class="col-sm-2">
                    <input type="text" class="form-control" name="state" value="<?= set_value('state') ?>" />
                </div>

                <div class="col-sm-2">
                    <input type="text" class="form-control" name="zip_code" value="<?= set_value('zip_code') ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-8">
                    <input type="text" class="form-control" placeholder="County" name="county" value="<?= set_value('county') ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-3">
                    <input type="text" class="form-control" placeholder="Latitude" name="latitude" value="<?= set_value('latitude') ?>" />
                </div>

                <div class="col-sm-3">
                    <input type="text" class="form-control" placeholder="Longitude" name="longitude" value="<?= set_value('longitude') ?>" />
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Building Description:</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" placeholder="Brief Description" name="address" value="<?= set_value('description') ?>" />
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
                        <option selected="selected">Commercial</option>
                        <option>Residential</option>
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
            </div>
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

            // document.getElementById('location-address').innerHTML = place.name + ', ' + address;
            // document.getElementById('buildingLat').value = place.geometry.location.lat();
            // document.getElementById('buildingLat').disabled = false;
            // document.getElementById('buildingLon').value = place.geometry.location.lng();
            // document.getElementById('buildingLon').disabled = false;
            // document.getElementById('placeID').value = place.place_id;
            // document.getElementById('placeID').disabled = false;

            //Added to attempt smaller fields
            // for (var component in componentForm) {
            //     document.getElementById(component).value = '';
            //     document.getElementById(component).disabled = false;

            // }

            // Get each component of the address from the place details,
            // and then fill-in the corresponding field on the form.
            for (var i = 0; i < place.address_components.length; i++) {
                var addressType = place.address_components[i].types[0];
                if (componentForm[addressType]) {
                    var val = place.address_components[i][componentForm[addressType]];
                    document.getElementById(addressType).value = val;
                }
            }
        });

    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBrDUbKjdjCC0yWcq_cLpUejnkSMFZP_6k&libraries=places&callback=initMap" async defer></script>

<?= $this->endSection() ?>