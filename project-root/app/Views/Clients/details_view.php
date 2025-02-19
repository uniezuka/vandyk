<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<?php
helper(['html', 'service']);
extract($data);

$bindAuthorityService = service('bindAuthorityService');
$floodZoneService = service('floodZoneService');

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
            if ($meta->meta_value == "" && $default != "")
                return $default;
            else
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
    <div class="col-8">
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
                            $isExcessPolicy = (int)getMetaValue($flood_quote_metas, 'isExcessPolicy', 0);
                            $isBounded = (int)getMetaValue($flood_quote_metas, 'isBounded', 0);
                            $boundDate = getMetaValue($flood_quote_metas, 'boundDate');
                            $inForce = (int)getMetaValue($flood_quote_metas, 'inForce', 0);
                            $isQuoteDeclined = (int)getMetaValue($flood_quote_metas, 'isQuoteDeclined', 0);
                            $isSandbarQuote = (int)getMetaValue($flood_quote_metas, 'isSandbarQuote', 0);
                            $bind_authority = getMetaValue($flood_quote_metas, 'bind_authority');
                            $hiscoxID = getMetaValue($flood_quote_metas, 'hiscoxID');
                            $prevHiscoxBoundID = getMetaValue($flood_quote_metas, 'prevHiscoxBoundID');
                            $boundHiscoxID = getMetaValue($flood_quote_metas, 'boundHiscoxID');
                            $flood_occupancy = (int)getMetaValue($flood_quote_metas, 'flood_occupancy', 0);
                            $isCondo = (int)getMetaValue($flood_quote_metas, 'isCondo', 0);

                            $bindAuthority = $bindAuthorityService->findOne($bind_authority);
                            $bindAuthorityText = ($bindAuthority) ? $bindAuthority->reference : "";

                            $floodZone = $floodZoneService->findOne($flood_zone);
                        ?>
                            <tr>
                                <td>
                                    <p>
                                        <strong><?= $policyType ?></strong> Quote ID: <a href="<?= base_url('/flood_quote/update/') . $floodQuote->flood_quote_id; ?>"><?= $floodQuote->flood_quote_id; ?></a>
                                        <br />
                                        Policy # <?= $policyNumber ?>
                                        <br />
                                        Flood Zone: <?= ($floodZone) ? $floodZone->name : "" ?>
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
                                    <?php if ($isExcessPolicy) { ?>
                                        <p><strong>EXCESS POLICY</strong></p>
                                    <?php } ?>
                                </td>

                                <td>
                                    <p><a href="<?= base_url('/flood_quote/update/') . $floodQuote->flood_quote_id; ?>" class="btn btn-primary btn-sm w-100">Edit Quote Info</a></p>

                                    <?php if ($isBounded) { ?>
                                        <p>
                                            <strong>Bound: <?= $boundDate ?></strong><br />
                                            <strong>Expiration: <?= $floodQuote->expiration_date ?></strong><br />
                                            <strong>In Force: </strong><?= ($inForce) ? "Yes" : "No" ?>
                                        </p>
                                    <?php } ?>

                                    <p>
                                        <?php if ($isQuoteDeclined) { ?>
                                            Inactive
                                        <?php } else if ($isBounded) { ?>
                                            <a href="<?= base_url('/flood_quote/rate_detail/') . $floodQuote->flood_quote_id; ?>" class="btn btn-primary btn-sm w-100" target="_blank">View Bound Rating</a>
                                        <?php } else { ?>
                                            <a href="<?= base_url('/flood_quote/pre_bound_rate_detail/') . $floodQuote->flood_quote_id; ?>" class="btn btn-primary btn-sm w-100" target="_blank">View Pre Bound Rates</a>
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
                                    <p><a href="<?= base_url('/flood_quote/docs/') . $floodQuote->flood_quote_id . '/application'; ?>" class="btn btn-primary btn-sm w-100" target="_blank"><?= $appText ?></a></p>
                                    <p><a href="<?= base_url('/flood_quote/docs/') . $floodQuote->flood_quote_id . '/quote'; ?>" class="btn btn-primary btn-sm w-100" target="_blank"><?= $quoteText ?></a></p>
                                    <p><a href="<?= base_url('/flood_quote/docs/') . $floodQuote->flood_quote_id . '/invoice'; ?>" class="btn btn-primary btn-sm w-100" target="_blank"><?= $invoiceText ?></a></p>
                                    <p><a href="<?= base_url('/flood_quote/docs/') . $floodQuote->flood_quote_id . '/no-loss'; ?>" class="btn btn-primary btn-sm w-100" target="_blank">No Loss Form</a></p>
                                    <!-- UPLOADED DOCS HERE -->
                                </td>

                                <td>
                                    <?php

                                    if (strpos($bindAuthorityText, "250") !== false) {
                                        if ($hiscoxID == "") {
                                            if ($policyType == "REN" && $prevHiscoxBoundID != "") {
                                                echo "<p><a href=\"#\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Hiscox Start Renewal Quote</a></p>";
                                            } else if ($policyType == "END" && $prevHiscoxBoundID != "") {
                                                echo "<p><a href=\"#\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Hiscox Start Endorsement Quote</a></p>";
                                            } else if ($policyType == "CAN" && $prevHiscoxBoundID != "") {
                                                if ($hiscoxID == "" && $boundHiscoxID != "") {
                                                    echo "<p><a href=\"" . base_url('/flood_quote/hiscox/reinstate/') . $floodQuote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Reinstate</a></p>";
                                                } else {
                                                    echo "<p><a href=\"" . base_url('/flood_quote/hiscox/cancel_preview/') . $floodQuote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Preview/Calculate Hiscox Cancellation</a></p>";
                                                }
                                            } else {
                                                echo "<p><a href=\"" . base_url('/flood_quote/hiscox/create/') . $floodQuote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Start Hiscox Quote</a></p>";
                                            }
                                            echo "<p><a href=\"" . base_url('/flood_quote/hiscox/link/') . $floodQuote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Link Hiscox</a></p>";
                                        } else if ($isBounded) {
                                            echo "<p><a href=\"" . base_url('/flood_quote/hiscox/view/') . $floodQuote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">View Hiscox Quote</a></p>";
                                        } else {
                                            if ($policyType == "REN") {
                                                echo "<p><a href=\"" . base_url('/flood_quote/hiscox/select/') . $floodQuote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Select Hiscox Quote</a></p>";
                                                echo "<p><a href=\"" . base_url('/flood_quote/hiscox/requote/') . $floodQuote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Full Requote Hiscox</a></p>";
                                            } else if ($policyType == "END") {
                                                echo "<p><a href=\"" . base_url('/flood_quote/hiscox/select/') . $floodQuote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Select Hiscox Quote</a></p>";
                                            } else {
                                                echo "<p><a href=\"" . base_url('/flood_quote/hiscox/select/') . $floodQuote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Select Hiscox Quote</a></p>";
                                                echo "<p><a href=\"" . base_url('/flood_quote/hiscox/requote/') . $floodQuote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Full Requote Hiscox</a></p>";
                                            }
                                        }
                                    }
                                    ?>
                                </td>

                                <td>
                                    <?php
                                    if ($isQuoteDeclined) {
                                        echo "<p>Inactive</p>";
                                    } else if ($isBounded) {
                                        $docType = $isSandbarQuote ? "<p><strong>Sandbar Docs</strong></p>" : "<p><strong>IAC Docs</strong></p>";

                                        if (strpos($bindAuthorityText, "250") !== false) {
                                            echo $docType;

                                            if ($isExcessPolicy) {
                                                echo "<p><a href=\"" . base_url('/flood_quote/policy/') . $floodQuote->flood_quote_id . "/excess\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Hiscox Excess Dec Page</a></p>";
                                            } else {
                                                echo "<p><a href=\"" . base_url('/flood_quote/policy/') . $floodQuote->flood_quote_id . "/dec\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Hiscox Dec Page</a></p>";
                                            }

                                            echo "<p><a href=\"" . base_url('/flood_quote/policy/') . $floodQuote->flood_quote_id . "/full\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Full Hiscox Policy</a></p>";
                                        } else {
                                            echo $docType;

                                            if ($isExcessPolicy) {
                                                echo "<p><a href=\"" . base_url('/flood_quote/policy/') . $floodQuote->flood_quote_id . "/excess\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Excess Dec Page</a></p>";
                                            } else {
                                                echo "<p><a href=\"" . base_url('/flood_quote/policy/') . $floodQuote->flood_quote_id . "/dec\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Dec Page</a></p>";
                                            }

                                            if ($flood_occupancy == 4) {
                                                echo "<p><a href=\"" . base_url('/flood_quote/policy/') . $floodQuote->flood_quote_id . "/general\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">General Policy</a></p>";
                                            } else if ($isCondo) {
                                                echo "<p><a href=\"" . base_url('/flood_quote/policy/') . $floodQuote->flood_quote_id . "/condo\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Condo Policy</a></p>";
                                            } else if (strpos($bindAuthorityText, "230") !== false) {
                                                echo "<p><a href=\"" . base_url('/flood_quote/policy/') . $floodQuote->flood_quote_id . "/full\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Full Brit Policy</a></p>";
                                            } else {
                                                echo "<p><a href=\"" . base_url('/flood_quote/policy/') . $floodQuote->flood_quote_id . "/full\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Full Policy</a></p>";
                                                if ($propertyState == "CT") {
                                                    echo "<p><a href=\"" . base_url('/flood_quote/policy/') . $floodQuote->flood_quote_id . "/form\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Broker CT SL-8 Form</a></p>";
                                                }
                                            }
                                        }
                                    } else if ($policyType == "CAN" && $prevHiscoxBoundID != "") {
                                        echo "<p><a href=\"" . base_url('/flood_quote/hiscox/bind/') . $floodQuote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Cancel Hiscox Policy</a></p>";
                                    } else {
                                        if (strpos($bindAuthorityText, "70") !== false) {
                                            echo "<p><a href=\"" . base_url('/flood_quote/bind/') . $floodQuote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Bind Chubb Policy</a></p>";
                                        } else if (strpos($bindAuthorityText, "260") !== false) {
                                            echo "<p><a href=\"" . base_url('/flood_quote/bind/') . $floodQuote->flood_quote_id . "\"_blank\" class=\"btn btn-primary btn-sm w-100\">Bind QBE Policy</a></p>";
                                        } else if (strpos($bindAuthorityText, "230") !== false) {
                                            echo "<p><a href=\"" . base_url('/flood_quote/bind/') . $floodQuote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Bind Brit Policy</a></p>";
                                        } else if (strpos($bindAuthorityText, "250") !== false && $hiscoxID != "") {
                                            echo "<p><a href=\"" . base_url('/flood_quote/hiscox/bind/') . $floodQuote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Bind Hiscox Policy</a></p>";
                                        } else if ($bindAuthorityText == "") {
                                            echo "<p><a href=\"" . base_url('/flood_quote/bind/') . $floodQuote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Bind Chubb Policy</a></p>";
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