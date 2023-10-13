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

function occupancySelect($name = "", $selectedItem = "") {
    $service = service('occupancyService');

    $str = '<select class="form-select" name="' . $name . '">';

    $str .= '<option';
    $str .= $selectedItem == "" ? ' selected' : '';
    $str .= '></option>';

    foreach ($service->getAll() as $item) {
        $str .= '<option';
        $str .= ' value="' . $item->occupancy_id . '"';
        $str .= $selectedItem == $item->occupancy_id ? ' selected' : '';
        $str .= '>' . $item->value . '</option>';
    }

    $str .= '</select>';

    return $str;
}

function constructionSelect($name = "", $selectedItem = "") {
    $service = service('constructionService');

    $str = '<select class="form-select" name="' . $name . '">';

    $str .= '<option';
    $str .= $selectedItem == "" ? ' selected' : '';
    $str .= '></option>';

    foreach ($service->getAll() as $item) {
        $str .= '<option';
        $str .= ' value="' . $item->construction_id  . '"';
        $str .= $selectedItem == $item->construction_id ? ' selected' : '';
        $str .= '>' . $item->name . '</option>';
    }

    $str .= '</select>';

    return $str;
}

function transactionTypeSelect($name = "", $selectedItem = "") {
    $service = service('transactionTypeService');

    $str = '<select class="form-select" name="' . $name . '">';

    $str .= '<option';
    $str .= $selectedItem == "" ? ' selected' : '';
    $str .= '></option>';

    foreach ($service->getAll() as $item) {
        $str .= '<option';
        $str .= ' value="' . $item->transaction_type_id  . '"';
        $str .= $selectedItem == $item->transaction_type_id ? ' selected' : '';
        $str .= '>' . $item->name . '</option>';
    }

    $str .= '</select>';

    return $str;
}

function fireCodeSelect($name = "", $selectedItem = "") {
    $service = service('fireCodeService');

    $str = '<select class="form-select" name="' . $name . '">';

    $str .= '<option';
    $str .= $selectedItem == "" ? ' selected' : '';
    $str .= '></option>';

    foreach ($service->getAll() as $item) {
        $str .= '<option';
        $str .= ' value="' . $item->fire_code_id  . '"';
        $str .= $selectedItem == $item->fire_code_id ? ' selected' : '';
        $str .= '>' . $item->name . '</option>';
    }

    $str .= '</select>';

    return $str;
}

function coverageSelect($name = "", $selectedItem = "") {
    $service = service('coverageService');

    $str = '<select class="form-select" name="' . $name . '">';

    $str .= '<option';
    $str .= $selectedItem == "" ? ' selected' : '';
    $str .= '></option>';

    foreach ($service->getAll() as $item) {
        $str .= '<option';
        $str .= ' value="' . $item->coverage_id  . '"';
        $str .= $selectedItem == $item->coverage_id ? ' selected' : '';
        $str .= '>' . $item->name . '</option>';
    }

    $str .= '</select>';

    return $str;
}

function insurerSelect($name = "", $selectedItem = "") {
    $service = service('insurerService');

    $str = '<select class="form-select" name="' . $name . '">';

    $str .= '<option';
    $str .= $selectedItem == "" ? ' selected' : '';
    $str .= '></option>';

    foreach ($service->getAllActive() as $item) {
        $str .= '<option';
        $str .= ' value="' . $item->insurer_id  . '"';
        $str .= $selectedItem == $item->insurer_id ? ' selected' : '';
        $str .= '>' . $item->name . '</option>';
    }

    $str .= '</select>';

    return $str;
}