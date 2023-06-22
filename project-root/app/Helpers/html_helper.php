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