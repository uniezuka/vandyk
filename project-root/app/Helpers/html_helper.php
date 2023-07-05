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

function stateSelect($name = "", $selectedState = "") {
    $locationService = service('locationService');

    $str = '<select class="form-select" name="' . $name . '">';

    $str .= '<option';
    $str .= $selectedState == "" ? ' selected' : '';
    $str .= '></option>';

    foreach ($locationService->getStates() as $item) {
        $str .= '<option';
        $str .= ' value="' . $item->code . '"';
        $str .= $selectedState == $item->code ? ' selected' : '';
        $str .= '>' . $item->name . '</option>';
    }

    $str .= '</select>';

    return $str;
}