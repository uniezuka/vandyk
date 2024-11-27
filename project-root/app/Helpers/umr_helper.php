<?php
function generateUMR($floodQuote, $bindAuthority)
{
    $umrCode = "";
    $bindAuthorityText = ($bindAuthority) ? $bindAuthority->reference : "";
    $effectivityDate = ($floodQuote->effectivity_date != "") ? new DateTime($floodQuote->effectivity_date) : "";

    if (strpos($bindAuthorityText, "VC000070") !== false) {
        if ($effectivityDate > new DateTime("2020-09-01") && $effectivityDate < new DateTime("2021-08-31")) {
            $umrCode = "B1921 VC000070U";
        } elseif ($effectivityDate > new DateTime("2021-09-01") && $effectivityDate < new DateTime("2022-08-31")) {
            $umrCode = "B1921 VC000070V";
        } elseif ($effectivityDate > new DateTime("2022-09-01") && $effectivityDate < new DateTime("2023-08-31")) {
            $umrCode = "B1921 VC000070W";
        } elseif ($effectivityDate > new DateTime("2023-09-01") && $effectivityDate < new DateTime("2024-08-31")) {
            $umrCode = "B1921 VC000070X";
        }
    } elseif (strpos($bindAuthorityText, "VC000230") !== false) {
        if ($effectivityDate > new DateTime("2020-12-01") && $effectivityDate < new DateTime("2021-11-30")) {
            $umrCode = "B1921 VC000230U";
        } elseif ($effectivityDate > new DateTime("2021-12-01") && $effectivityDate < new DateTime("2022-11-30")) {
            $umrCode = "B1921 VC000230V";
        } elseif ($effectivityDate > new DateTime("2022-12-01") && $effectivityDate < new DateTime("2023-12-30")) {
            $umrCode = "B1921 VC000230W";
        } elseif ($effectivityDate > new DateTime("2023-12-01") && $effectivityDate < new DateTime("2024-12-30")) {
            $umrCode = "B1921 VC000230X";
        }
    } elseif (strpos($bindAuthorityText, "VC000250") !== false) {
        if ($effectivityDate > new DateTime("2021-03-01") && $effectivityDate < new DateTime("2022-02-28")) {
            $umrCode = "B1921 VC000250V";
        } elseif ($effectivityDate > new DateTime("2022-03-01") && $effectivityDate < new DateTime("2023-02-28")) {
            $umrCode = "B1921 VC000250W";
        } elseif ($effectivityDate > new DateTime("2023-03-01") && $effectivityDate < new DateTime("2024-02-28")) {
            $umrCode = "B1921 VC000250X";
        }
    } elseif (strpos($bindAuthorityText, "VC000260") !== false) {
        if ($effectivityDate > new DateTime("2022-03-01") && $effectivityDate < new DateTime("2023-02-28")) {
            $umrCode = "B1921 VC000260W";
        } elseif ($effectivityDate > new DateTime("2023-03-01") && $effectivityDate < new DateTime("2024-02-28")) {
            $umrCode = "B1921 VC000260X";
        }
    } else {
        $umrCode = $bindAuthorityText;
    }

    if ($umrCode == "") {
        $umrCode = $bindAuthorityText;
    }

    return $umrCode;
}
