<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<?php
helper(['html', 'service']);
extract($data);

$bindAuthorityService = service('bindAuthorityService');

function clientDisplay($client): string
{
    if ($client->entity_type == 1) {
        return $client->first_name . ' ' . $client->last_name . '<br />' . $client->insured2_name;
    } else {
        return $client->business_name . '<br />' . $client->business_name2;
    }
}

function getMetaValue($metas, $meta_key, $default = '')
{
    foreach ($metas as $meta) {
        if ($meta->meta_key === $meta_key) {
            return $meta->meta_value;
        }
    }
    return $default;
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

<div class="row mb-3">
    <div class="col-8">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col col-6">
                        <h4><?= clientDisplay($client) ?></h4>
                    </div>

                    <div class="col col-6 text-end">
                        <p><strong>Broker: <?= $broker->name ?></strong></p>
                        <p>Client Code: <?= $client->client_code ?></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col col-4">
                        <span>Mailing Address:</span>
                        <?= formatAddress($client->address, "", $client->city, $client->state, $client->zip) ?>
                    </div>

                    <div class="col col-4">
                        <p>
                            Cell: <?= $client->cell_phone ?><br />
                            Home Ph: <?= $client->home_phone ?><br />
                            Email: <?= $client->email ?>
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col col-4">
                        <a href="<?= base_url('/client/update/') . $client->client_id; ?>" class="btn btn-primary">Update Client</a>
                    </div>

                    <div class="col col-4">
                        <a href="<?= base_url('/flood_quote/create?client_id=') . $client->client_id; ?>" class="btn btn-primary">New Flood Quote</a>
                    </div>

                    <?php if ($client->is_commercial) { ?>
                        <div class="col col-4">
                            <a href="<?= base_url('/client/' . $client->client_id . '/building/create') ?>" class="btn btn-primary">Add Building Location</a>
                            <span class="d-block">**Hiscox limit is 10 buildings per client quote</span>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-5">
        <div class="card">
            <div class="card-body">
                <h5>Flood Policies/Quotes</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col"></th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($floodQuotes as $floodQuote) {
                            $flood_quote_id = $floodQuote->flood_quote_id;
                            $flood_quote_metas = array_filter($metas, function ($meta) use ($flood_quote_id) {
                                return $meta->flood_quote_id == $flood_quote_id;
                            });

                            $policyType = getMetaValue($flood_quote_metas, 'policyType');
                            $policyNumber = getMetaValue($flood_quote_metas, 'policyNumber');
                            $flood_zone = getMetaValue($flood_quote_metas, 'flood_zone');
                            $propertyAddress = getMetaValue($flood_quote_metas, 'propertyAddress');
                            $propertyCity = getMetaValue($flood_quote_metas, 'propertyCity');
                            $propertyState = getMetaValue($flood_quote_metas, 'propertyState');
                            $hasExcessPolicy = (int)getMetaValue($flood_quote_metas, 'hasExcessPolicy', 0);
                            $isBounded = (int)getMetaValue($flood_quote_metas, 'isBounded', 0);
                            $boundDate = getMetaValue($flood_quote_metas, 'boundDate');
                            $expirationDate = getMetaValue($flood_quote_metas, 'expirationDate');
                            $inForce = (int)getMetaValue($flood_quote_metas, 'inForce', 0);
                            $isPolicyDeclined = (int)getMetaValue($flood_quote_metas, 'isPolicyDeclined', 0);
                            $isSandbarQuote = (int)getMetaValue($flood_quote_metas, 'isSandbarQuote', 0);
                            $bind_authority = getMetaValue($flood_quote_metas, 'bind_authority');
                            $hiscoxID = getMetaValue($flood_quote_metas, 'hiscoxID');
                            $hiscoxPreviousBoundID = getMetaValue($flood_quote_metas, 'hiscoxPreviousBoundID');
                            $flood_occupancy = (int)getMetaValue($flood_quote_metas, 'flood_occupancy', 0);
                            $isCondo = (int)getMetaValue($flood_quote_metas, 'isCondo', 0);

                            $bindAuthority = $bindAuthorityService->findOne($bind_authority);
                            $bindAuthorityText = ($bindAuthority) ? $bindAuthority->reference : "";
                        ?>
                            <tr>
                                <td>
                                    <p>
                                        <strong><?= $policyType ?></strong> Quote ID: <a href="<?= base_url('/flood_quote/update/') . $floodQuote->flood_quote_id; ?>"><?= $floodQuote->flood_quote_id; ?></a>
                                        <br />
                                        Policy # <?= $policyNumber ?>
                                        <br />
                                        Flood Zone: <?= $flood_zone ?>
                                    </p>
                                    <p>
                                        <strong>Property Address: </strong>
                                        <br />
                                        <?= $propertyAddress ?>
                                        <br />
                                        <?= $propertyCity ?>, <?= $propertyState ?>
                                    </p>
                                    <p>
                                        Entered: <?= $floodQuote->date_entered ?>
                                    </p>
                                    <?php if ($hasExcessPolicy) { ?>
                                        <p><strong>EXCESS POLICY</strong></p>
                                    <?php } ?>
                                </td>

                                <td>
                                    <p><a href="<?= base_url('/flood_quote/update/') . $floodQuote->flood_quote_id; ?>" class="btn btn-primary btn-sm">Edit Quote Info</a></p>

                                    <?php if ($isBounded) { ?>
                                        <p>
                                            <strong>Bound: <?= $boundDate ?></strong><br />
                                            <strong>Exp: <?= $expirationDate ?></strong><br />
                                            <strong>In Force: </strong><?= ($inForce) ? "Yes" : "No" ?>
                                        </p>
                                    <?php } ?>

                                    <p>
                                        <?php if ($isPolicyDeclined) { ?>
                                            Inactive
                                        <?php } else if ($isBounded) { ?>
                                            <a href="#" class="btn btn-primary btn-sm" target="_blank">View Bound Rating</a>
                                        <?php } else { ?>
                                            <a href="#" class="btn btn-primary btn-sm" target="_blank">View Current Rating</a>
                                        <?php } ?>
                                    </p>

                                    <?php
                                    $appText = "";
                                    $quoteText = "";
                                    $invoiceText = "";
                                    $docsTitle = ($isSandbarQuote) ? "Sandbar Docs" : "IAC Docs";

                                    if (strpos($bindAuthorityText, "230") !== false) {
                                        $appText = "Brit App";
                                        $quoteText = "Brit Quote";
                                        $invoiceText = "Brit Invoice";
                                    } else if (strpos($bindAuthorityText, "240") !== false) {
                                        $appText = "Canopius App";
                                        $quoteText = "Canopius Quote";
                                        $invoiceText = "Canopius Invoice";
                                    } else if (strpos($bindAuthorityText, "260") !== false) {
                                        $appText = "QBE App";
                                        $quoteText = "QBE Quote";
                                        $invoiceText = "QBE Invoice";
                                    } else if (strpos($bindAuthorityText, "250") !== false) {
                                        $appText = "Hiscox App";
                                        $quoteText = "Hiscox Quote Doc";
                                        $invoiceText = "Hiscox Invoice";
                                    } else {
                                        $appText = "Chubb App";
                                        $quoteText = "Chubb Quote";
                                        $invoiceText = "Chubb Invoice";
                                    }
                                    ?>
                                    <p><strong><?= $docsTitle ?></strong></p>
                                    <p><a href="#" class="btn btn-primary btn-sm" target="_blank"><?= $appText ?></a></p>
                                    <p><a href="#" class="btn btn-primary btn-sm" target="_blank"><?= $quoteText ?></a></p>
                                    <p><a href="#" class="btn btn-primary btn-sm" target="_blank"><?= $invoiceText ?></a></p>
                                    <p><a href="#" class="btn btn-primary btn-sm" target="_blank">No Loss Form</a></p>
                                    <!-- UPLOADED DOCS HERE -->
                                </td>

                                <td>
                                    <?php

                                    if (strpos($bindAuthorityText, "250") !== false) {
                                        if ($hiscoxID == "") {
                                            if ($policyType == "REN" && $hiscoxPreviousBoundID != "") {
                                                echo "<p><a href=\"#\" target=\"_blank\" class=\"btn btn-primary btn-sm\">Hiscox Start Renewal Quote</a></p>";
                                            } else if ($policyType == "END" && $hiscoxPreviousBoundID != "") {
                                                echo "<p><a href=\"#\" target=\"_blank\" class=\"btn btn-primary btn-sm\">Hiscox Start Endorsement Quote</a></p>";
                                            } else if ($policyType == "CAN" && $hiscoxPreviousBoundID != "") {
                                                if ($hiscoxID == "" && $hiscoxPreviousBoundID != "") {
                                                    echo "<p><a href=\"#\" target=\"_blank\" class=\"btn btn-primary btn-sm\">Reinstate</a></p>";
                                                } else {
                                                    echo "<p><a href=\"#\" target=\"_blank\" class=\"btn btn-primary btn-sm\">Preview/Calculate Hiscox Cancellation</a></p>";
                                                }
                                            } else {
                                                echo "<p><a href=\"" . base_url('/flood_quote/hiscox/create/') . $floodQuote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm\">Start Hiscox Quote</a></p>";
                                            }
                                            echo "<p><a href=\"" . base_url('/flood_quote/hiscox/link/') . $floodQuote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm\">Link Hiscox</a></p>";
                                        } else if ($isBounded) {
                                            echo "<p><a href=\"#\" target=\"_blank\" class=\"btn btn-primary btn-sm\">View Hiscox Quote</a></p>";
                                        } else {
                                            if ($policyType == "REN") {
                                                echo "<p><a href=\"" . base_url('/flood_quote/hiscox/select/') . $floodQuote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm\">Select Hiscox Quote</a></p>";
                                                echo "<p><a href=\"" . base_url('/flood_quote/hiscox/requote/') . $floodQuote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm\">Full Requote Hiscox</a></p>";
                                            } else if ($policyType == "END") {
                                                echo "<p><a href=\"" . base_url('/flood_quote/hiscox/select/') . $floodQuote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm\">Select Hiscox Quote</a></p>";
                                            } else {
                                                echo "<p><a href=\"" . base_url('/flood_quote/hiscox/select/') . $floodQuote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm\">Select Hiscox Quote</a></p>";
                                                echo "<p><a href=\"" . base_url('/flood_quote/hiscox/requote/') . $floodQuote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm\">Full Requote Hiscox</a></p>";
                                            }
                                        }
                                    }
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    if ($isPolicyDeclined) {
                                        echo "<p>Inactive</p>";
                                    } else if ($isBounded) {
                                        if (strpos($bindAuthorityText, "250") !== false) {
                                            if ($isSandbarQuote) {
                                                echo "<p><strong>Sandbar Docs</strong></p>";

                                                if ($hasExcessPolicy) {
                                                    echo "<p><a href=\"#\" target=\"_blank\" class=\"btn btn-primary btn-sm\">Hiscox Excess Dec Page</a></p>";
                                                } else {
                                                    echo "<p><a href=\"#\" target=\"_blank\" class=\"btn btn-primary btn-sm\">Hiscox Dec Page</a></p>";
                                                }
                                            } else {
                                                echo "<p><a href=\"#\" target=\"_blank\" class=\"btn btn-primary btn-sm\">Hiscox Dec Page</a></p>";
                                            }
                                        } else {
                                            if ($isSandbarQuote) {
                                                echo "<p><strong>Sandbar Docs</strong></p>";

                                                if ($hasExcessPolicy) {
                                                    echo "<p><a href=\"#\" target=\"_blank\" class=\"btn btn-primary btn-sm\">Excess Dec Page</a></p>";
                                                } else {
                                                    echo "<p><a href=\"#\" target=\"_blank\" class=\"btn btn-primary btn-sm\">Dec Page</a></p>";
                                                }
                                            } else {
                                                echo "<p><strong>IAC Docs</strong></p>";

                                                if ($hasExcessPolicy) {
                                                    echo "<p><a href=\"#\" target=\"_blank\" class=\"btn btn-primary btn-sm\">Excess Dec Page</a></p>";
                                                } else {
                                                    echo "<p><a href=\"#\" target=\"_blank\" class=\"btn btn-primary btn-sm\">Dec Page</a></p>";
                                                }
                                            }

                                            if ($flood_occupancy == 4) {
                                                echo "<p><a href=\"#\" target=\"_blank\" class=\"btn btn-primary btn-sm\">General Policy</a></p>";
                                            } else if ($isCondo) {
                                                echo "<p><a href=\"#\" target=\"_blank\" class=\"btn btn-primary btn-sm\">Condo Policy</a></p>";
                                            } else if (strpos($bindAuthorityText, "230") !== false) {
                                                echo "<p><a href=\"#\" target=\"_blank\" class=\"btn btn-primary btn-sm\">Full Brit Policy</a></p>";
                                            } else {
                                                if ($propertyState == "CT") {
                                                    echo "<p><a href=\"#\" target=\"_blank\" class=\"btn btn-primary btn-sm\">Broker CT SL-8 Form</a></p>";
                                                }
                                            }
                                        }
                                    } else if ($policyType == "CAN" && $hiscoxPreviousBoundID != "") {
                                        echo "<p><a href=\"#\" target=\"_blank\" class=\"btn btn-primary btn-sm\">Cancel Hiscox Policy</a></p>";
                                    } else {
                                        if (strpos($bindAuthorityText, "70") !== false) {
                                            echo "<p><a href=\"#\" target=\"_blank\" class=\"btn btn-primary btn-sm\">Bind Chubb Policy</a></p>";
                                        } else if (strpos($bindAuthorityText, "260") !== false) {
                                            echo "<p><a href=\"#\" target=\"_blank\" class=\"btn btn-primary btn-sm\">Bind QBE Policy</a></p>";
                                        } else if (strpos($bindAuthorityText, "230") !== false) {
                                            echo "<p><a href=\"#\" target=\"_blank\" class=\"btn btn-primary btn-sm\">Bind Brit Policy</a></p>";
                                        } else if (strpos($bindAuthorityText, "240") !== false) {
                                            echo "<p><a href=\"#\" target=\"_blank\" class=\"btn btn-primary btn-sm\">Bind Canop Policy</a></p>";
                                        } else if (strpos($bindAuthorityText, "250") !== false && $hiscoxID != "") {
                                            echo "<p><a href=\"" . base_url('/flood_quote/hiscox/bind/') . $floodQuote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm\">Bind Hiscox Policy</a></p>";
                                        } else if ($bindAuthorityText == "") {
                                            echo "<p><a href=\"#\" target=\"_blank\" class=\"btn btn-primary btn-sm\">Bind Chubb Policy</a></p>";
                                        }
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php if ($client->is_commercial) { ?>
        <div class="col-5">
            <div class="card">
                <div class="card-body">
                    <h5>Buildings</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($buildings as $building) : ?>
                                <tr>
                                    <td><?= '#' . $building->build_index . ' ' . $building->address . ', ' . $building->city ?></td>
                                    <td>
                                        <a href="<?= base_url('/client/') . $client->client_id . '/building/update/' . $building->client_building_id ?>">Update</a>&nbsp;
                                        <a href="<?= base_url('/client/') . $client->client_id . '/building/delete/' . $building->client_building_id ?>">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<?= $this->endSection() ?>