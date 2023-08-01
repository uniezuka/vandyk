<?php

function formatAddress($address1 = "", $address2 = "", $city = "", $state = "", $zip = "") {
    $str = '<span class="d-block">';
    $str .= $address1;

    if ($address2) {
        $str .= '<br />' . $address2;
    }

    $str .= '<br />' . $city . ', ' . $state . ' ' . $zip;
    $str .= '</span>';

    return $str;
}

function stateSelect($name = "", $selectedItem = "") {
    $service = service('locationService');

    $str = '<select class="form-select" name="' . $name . '">';

    $str .= '<option';
    $str .= $selectedItem == "" ? ' selected' : '';
    $str .= '></option>';

    foreach ($service->getStates() as $item) {
        $str .= '<option';
        $str .= ' value="' . $item->code . '"';
        $str .= $selectedItem == $item->code ? ' selected' : '';
        $str .= '>' . $item->name . '</option>';
    }

    $str .= '</select>';

    return $str;
}

function brokerSelect($name = "", $selectedItem = "") {
    $service = service('brokerService');

    $str = '<select class="form-select" name="' . $name . '">';

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

function businessEntitySelect($name = "", $selectedItem = "") {
    $service = service('entityService');

    $str = '<select class="form-select" name="' . $name . '">';

    $str .= '<option';
    $str .= $selectedItem == "" ? ' selected' : '';
    $str .= '></option>';

    foreach ($service->getBusinessEntityTypes() as $item) {
        $str .= '<option';
        $str .= ' value="' . $item->business_entity_type_id . '"';
        $str .= $selectedItem == $item->business_entity_type_id ? ' selected' : '';
        $str .= '>' . $item->name . '</option>';
    }

    $str .= '</select>';

    return $str;
}