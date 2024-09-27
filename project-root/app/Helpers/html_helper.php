<?php

function formatAddress($address1 = "", $address2 = "", $city = "", $state = "", $zip = "")
{
    $str = '<span class="d-block">';
    $str .= $address1;

    if ($address2) {
        $str .= '<br />' . $address2;
    }

    $str .= '<br />' . $city . ', ' . $state . ' ' . $zip;
    $str .= '</span>';

    return $str;
}

function stateSelect($name = "", $selectedItem = "")
{
    $service = service('locationService');

    $str = '<select class="form-select" name="' . $name . '" id="' . $name . '">';

    $str .= '<option';
    $str .= $selectedItem == "" ? ' selected' : '';
    $str .= ' value=""';
    $str .= '>Select State</option>';

    foreach ($service->getStates() as $item) {
        $str .= '<option';
        $str .= ' value="' . $item->code . '"';
        $str .= $selectedItem == $item->code ? ' selected' : '';
        $str .= '>' . $item->name . '</option>';
    }

    $str .= '</select>';

    return $str;
}

function brokerSelect($name = "", $selectedItem = "")
{
    $service = service('brokerService');

    $str = '<select class="form-select" name="' . $name . '" id="' . $name . '">';

    $str .= '<option';
    $str .= $selectedItem == "" ? ' selected' : '';
    $str .= ' value=""';
    $str .= '>Select Broker</option>';

    foreach ($service->getAll() as $item) {
        $str .= '<option';
        $str .= ' value="' . $item->broker_id . '"';
        $str .= $selectedItem == $item->broker_id ? ' selected' : '';
        $str .= '>' . $item->name . '</option>';
    }

    $str .= '</select>';

    return $str;
}

function businessEntitySelect($name = "", $selectedItem = "")
{
    $service = service('entityService');

    $str = '<select class="form-select" name="' . $name . '" id="' . $name . '">';

    $str .= '<option';
    $str .= $selectedItem == "" ? ' selected' : '';
    $str .= ' value=""';
    $str .= '>Select Entity</option>';

    foreach ($service->getBusinessEntityTypes() as $item) {
        $str .= '<option';
        $str .= ' value="' . $item->business_entity_type_id . '"';
        $str .= $selectedItem == $item->business_entity_type_id ? ' selected' : '';
        $str .= '>' . $item->name . '</option>';
    }

    $str .= '</select>';

    return $str;
}

function occupancySelect($name = "", $selectedItem = "")
{
    $service = service('occupancyService');

    $str = '<select class="form-select" name="' . $name . '" id="' . $name . '">';

    $str .= '<option';
    $str .= $selectedItem == "" ? ' selected' : '';
    $str .= ' value=""';
    $str .= '>Select Occupancy</option>';

    foreach ($service->getAll() as $item) {
        $str .= '<option';
        $str .= ' value="' . $item->occupancy_id . '"';
        $str .= $selectedItem == $item->occupancy_id ? ' selected' : '';
        $str .= '>' . $item->value . '</option>';
    }

    $str .= '</select>';

    return $str;
}

function constructionSelect($name = "", $selectedItem = "")
{
    $service = service('constructionService');

    $str = '<select class="form-select" name="' . $name . '" id="' . $name . '">';

    $str .= '<option';
    $str .= $selectedItem == "" ? ' selected' : '';
    $str .= ' value=""';
    $str .= '>Select Construction Type</option>';

    foreach ($service->getAll() as $item) {
        $str .= '<option';
        $str .= ' value="' . $item->construction_id  . '"';
        $str .= $selectedItem == $item->construction_id ? ' selected' : '';
        $str .= '>' . $item->name . '</option>';
    }

    $str .= '</select>';

    return $str;
}

function transactionTypeSelect($name = "", $selectedItem = "")
{
    $service = service('transactionTypeService');

    $str = '<select class="form-select" name="' . $name . '" id="' . $name . '">';

    $str .= '<option';
    $str .= $selectedItem == "" ? ' selected' : '';
    $str .= ' value=""';
    $str .= '>Select Transaction Type</option>';

    foreach ($service->getAll() as $item) {
        $str .= '<option';
        $str .= ' value="' . $item->transaction_type_id  . '"';
        $str .= $selectedItem == $item->transaction_type_id ? ' selected' : '';
        $str .= '>' . $item->name . '</option>';
    }

    $str .= '</select>';

    return $str;
}

function fireCodeSelect($name = "", $selectedItem = "")
{
    $service = service('fireCodeService');

    $str = '<select class="form-select" name="' . $name . '" id="' . $name . '">';

    $str .= '<option';
    $str .= $selectedItem == "" ? ' selected' : '';
    $str .= ' value=""';
    $str .= '>Select Fire Code</option>';

    foreach ($service->getAll() as $item) {
        $str .= '<option';
        $str .= ' value="' . $item->fire_code_id  . '"';
        $str .= $selectedItem == $item->fire_code_id ? ' selected' : '';
        $str .= '>' . $item->name . '</option>';
    }

    $str .= '</select>';

    return $str;
}

function coverageSelect($name = "", $selectedItem = "")
{
    $service = service('coverageService');

    $str = '<select class="form-select" name="' . $name . '" id="' . $name . '">';

    $str .= '<option';
    $str .= $selectedItem == "" ? ' selected' : '';
    $str .= ' value=""';
    $str .= '>Select Coverage</option>';

    foreach ($service->getAll() as $item) {
        $str .= '<option';
        $str .= ' value="' . $item->coverage_id  . '"';
        $str .= $selectedItem == $item->coverage_id ? ' selected' : '';
        $str .= '>' . $item->name . '</option>';
    }

    $str .= '</select>';

    return $str;
}

function insurerSelect($name = "", $selectedItem = "")
{
    $service = service('insurerService');

    $str = '<select class="form-select" name="' . $name . '" id="' . $name . '">';

    $str .= '<option';
    $str .= $selectedItem == "" ? ' selected' : '';
    $str .= ' value=""';
    $str .= '>Select Insurer</option>';

    foreach ($service->getAllActive() as $item) {
        $str .= '<option';
        $str .= ' value="' . $item->insurer_id  . '"';
        $str .= $selectedItem == $item->insurer_id ? ' selected' : '';
        $str .= '>' . $item->name . '</option>';
    }

    $str .= '</select>';

    return $str;
}

function countySelect($name = "", $selectedItem = "")
{
    $service = service('countyService');

    $str = '<select class="form-select" name="' . $name . '" id="' . $name . '">';

    $str .= '<option';
    $str .= $selectedItem == "" ? ' selected' : '';
    $str .= ' value=""';
    $str .= '>Select County</option>';

    foreach ($service->getAll() as $item) {
        $str .= '<option';
        $str .= ' value="' . $item->county_id . '"';
        $str .= $selectedItem == $item->county_id ? ' selected' : '';
        $str .= '>' . $item->name . '</option>';
    }

    $str .= '</select>';

    return $str;
}

function floodZoneSelect($name = "", $selectedItem = "")
{
    $service = service('floodZoneService');

    $str = '<select class="form-select" name="' . $name . '" id="' . $name . '">';

    $str .= '<option';
    $str .= $selectedItem == "" ? ' selected' : '';
    $str .= ' value=""';
    $str .= '>Select Flood Zone</option>';

    foreach ($service->getAll() as $item) {
        $str .= '<option';
        $str .= ' value="' . $item->flood_zone_id . '"';
        $str .= $selectedItem == $item->flood_zone_id ? ' selected' : '';
        $str .= '>' . $item->name . '</option>';
    }

    $str .= '</select>';

    return $str;
}

function floodFoundationSelect($name = "", $selectedItem = "")
{
    $service = service('floodFoundationService');

    $str = '<select class="form-select" name="' . $name . '" id="' . $name . '">';

    $str .= '<option';
    $str .= $selectedItem == "" ? ' selected' : '';
    $str .= ' value=""';
    $str .= '>Select Foundation</option>';

    foreach ($service->getAll() as $item) {
        $str .= '<option';
        $str .= ' value="' . $item->flood_foundation_id . '"';
        $str .= $selectedItem == $item->flood_foundation_id ? ' selected' : '';
        $str .= '>' . $item->name . '</option>';
    }

    $str .= '</select>';

    return $str;
}

function floodOccupancySelect($name = "", $selectedItem = "")
{
    $service = service('floodOccupancyService');

    $str = '<select class="form-select" name="' . $name . '" id="' . $name . '">';

    $str .= '<option';
    $str .= $selectedItem == "" ? ' selected' : '';
    $str .= ' value=""';
    $str .= '>Select Occupancy</option>';

    foreach ($service->getAll() as $item) {
        $str .= '<option';
        $str .= ' value="' . $item->flood_occupancy_id . '"';
        $str .= $selectedItem == $item->flood_occupancy_id ? ' selected' : '';
        $str .= '>' . $item->name . '</option>';
    }

    $str .= '</select>';

    return $str;
}

function deductibleSelect($name = "", $selectedItem = "")
{
    $service = service('deductibleService');

    $str = '<select class="form-select" name="' . $name . '" id="' . $name . '">';

    $str .= '<option';
    $str .= $selectedItem == "" ? ' selected' : '';
    $str .= ' value=""';
    $str .= '>Select Deductible</option>';

    foreach ($service->getAll() as $item) {
        $str .= '<option';
        $str .= ' value="' . $item->deductible_id . '"';
        $str .= $selectedItem == $item->deductible_id ? ' selected' : '';
        $str .= '>' . $item->name . '</option>';
    }

    $str .= '</select>';

    return $str;
}

function activeBindAuthoritySelect($name = "", $selectedItem = "")
{
    $service = service('bindAuthorityService');

    $str = '<select class="form-select" name="' . $name . '" id="' . $name . '">';

    $str .= '<option';
    $str .= $selectedItem == "" ? ' selected' : '';
    $str .= ' value=""';
    $str .= '>Select Bind Auth</option>';

    foreach ($service->getActive() as $item) {
        $str .= '<option';
        $str .= ' value="' . $item->bind_authority_id . '"';
        $str .= $selectedItem == $item->bind_authority_id ? ' selected' : '';
        $str .= '>' . $item->reference . ' - ' . $item->name . '</option>';
    }

    $str .= '</select>';

    return $str;
}

function producerSelect($name = "", $selectedItem = "")
{
    $service = service('producerService');

    $str = '<select class="form-select" name="' . $name . '" id="' . $name . '">';

    $str .= '<option';
    $str .= $selectedItem == "" ? ' selected' : '';
    $str .= ' value=""';
    $str .= '>Select Producer</option>';

    foreach ($service->getAll() as $item) {
        $str .= '<option';
        $str .= ' value="' . $item->producer_id . '"';
        $str .= $selectedItem == $item->producer_id ? ' selected' : '';
        $str .= '>' . $item->last_name . ", " . $item->first_name . '</option>';
    }

    $str .= '</select>';

    return $str;
}

function commercialOccupancySelect($name = "", $selectedItem = "")
{
    $service = service('commercialOccupancyService');

    $str = '<select class="form-select" name="' . $name . '" id="' . $name . '">';

    $str .= '<option';
    $str .= $selectedItem == "" ? ' selected' : '';
    $str .= ' value=""';
    $str .= '>Select Usage</option>';

    foreach ($service->getAll() as $item) {
        $str .= '<option';
        $str .= ' value="' . $item->commercial_occupancy_id . '"';
        $str .= $selectedItem == $item->commercial_occupancy_id ? ' selected' : '';
        $str .= '>' . $item->name . '</option>';
    }

    $str .= '</select>';

    return $str;
}
