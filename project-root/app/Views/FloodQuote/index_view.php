<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<?php
helper(['html', 'service']);
extract($data);

$bindAuthorityService = service('bindAuthorityService');

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

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
                <div class="clearfix">
                    <h5>Flood Policies</h5>
                    <div class="float-start">
                        <p>Search by Customer <strong>Last Name, First Name, Policy Number or Property Address</strong></p>
                        <form class="form" method="get">
                            <div class="d-flex p-2">
                                <input class="d-flex form-control w-75 me-1" name="search" type="search" placeholder="Search" value="<?= $search ?>">
                                <button class="btn btn-primary" type="submit">Search</button>
                            </div>
                        </form>
                    </div>
                    <a type="button" class="btn btn-primary float-end" href="<?= base_url('/client/create'); ?>">Create New Client</a>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Quote</th>
                            <th scope="col"></th>
                            <th scope="col">Name/Address</th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($flood_quotes as $flood_quote) {
                            $flood_quote_id = $flood_quote->flood_quote_id;
                            $flood_quote_metas = array_filter($metas, function ($meta) use ($flood_quote_id) {
                                return $meta->flood_quote_id == $flood_quote_id;
                            });

                            $isPerson = $flood_quote->entity_type == 0;
                            $policyType = getMetaValue($flood_quote_metas, 'policyType');
                            $hasExcessPolicy = getMetaValue($flood_quote_metas, 'hasExcessPolicy');
                            $propertyAddress = getMetaValue($flood_quote_metas, "propertyAddress");
                            $propertyCity = getMetaValue($flood_quote_metas, "propertyCity");
                            $propertyState = getMetaValue($flood_quote_metas, "propertyState");
                            $propertyZip = getMetaValue($flood_quote_metas, "propertyZip");
                            $isBounded = (int)getMetaValue($flood_quote_metas, "isBounded", 0);
                            $boundDate = getMetaValue($flood_quote_metas, "boundDate");
                            $isQuoteDeclined = (int)getMetaValue($flood_quote_metas, "isQuoteDeclined", 0);
                            $isSandbarQuote = (int)getMetaValue($flood_quote_metas, 'isSandbarQuote', 0);
                            $bind_authority = getMetaValue($flood_quote_metas, 'bind_authority');
                            $hiscoxID = getMetaValue($flood_quote_metas, 'hiscoxID');
                            $prevHiscoxBoundID = getMetaValue($flood_quote_metas, 'prevHiscoxBoundID');
                            $boundHiscoxID = getMetaValue($flood_quote_metas, 'boundHiscoxID');

                            $bindAuthority = $bindAuthorityService->findOne($bind_authority);
                            $bindAuthorityText = ($bindAuthority) ? $bindAuthority->reference : "";

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
                            <tr>
                                <td>
                                    <p><strong><?= $policyType ?></strong></p>
                                    <p>ID: <a href="<?= base_url('/flood_quote/update/') . $flood_quote->flood_quote_id; ?>"><?= $flood_quote->flood_quote_id ?></a></p>
                                    <p>Entered: <?= $flood_quote->date_entered; ?></p>
                                    <?php if ($hasExcessPolicy) { ?>
                                        <p><strong>EXCESS POLICY</strong></p>
                                    <?php } ?>
                                </td>
                                <td>
                                    <p><a class="btn btn-primary btn-sm w-100" href="<?= base_url('/client/update/') . $flood_quote->client_id; ?>">Update Client</a></p>
                                    <p><a class="btn btn-primary btn-sm w-100" href="<?= base_url('/flood_quote/update/') . $flood_quote->flood_quote_id; ?>">Update Rating Info</a>
                                    <p>&nbsp;</p>
                                    <p><a href="">Quote Hiscox Commercial</a></p>
                                </td>
                                <td>
                                    <p>
                                        <strong>
                                            <?php if ($isPerson) { ?>
                                                <?= $flood_quote->first_name ?><br />
                                                <?= $flood_quote->last_name ?><br />
                                                <?= $flood_quote->insured_name_2 ?>
                                            <?php } else { ?>
                                                <?= $flood_quote->company_name ?><br />
                                                <?= $flood_quote->company_name_2 ?><br />
                                            <?php } ?>
                                        </strong>
                                    </p>

                                    <p>
                                        <strong>Address:</strong><br />
                                        <?= $propertyAddress ?><br />
                                        <?= $propertyCity ?>, <?= $propertyState ?>&nbsp;&nbsp;<?= $propertyZip ?>
                                    </p>

                                    <p><a href="#" target="_blank">Nat Flood Data Lookup</a></p>
                                </td>
                                <td>
                                    <?php if ($isBounded) { ?>
                                        <p>
                                            <strong>Bound: <?= $boundDate ?></strong><br />
                                            <strong>Exp: <?= $flood_quote->expiration_date ?></strong><br />
                                        </p>
                                    <?php } ?>

                                    <?php if ($isQuoteDeclined) { ?>
                                        <p>Inactive</p>
                                    <?php } else if ($isBounded) { ?>
                                        <p><a href="<?= base_url('/flood_quote/rate_detail/') . $flood_quote->flood_quote_id; ?>" target="_blank">View Bound Rating</a></p>
                                    <?php } else { ?>
                                        <p><a href="#" target="_blank">View Current Rating</a></p>
                                    <?php } ?>
                                </td>
                                <td>
                                    <p><strong><?= $docsTitle ?></strong></p>
                                    <p><a href="#" class="btn btn-primary btn-sm w-100" target="_blank"><?= $appText ?></a></p>
                                    <p><a href="#" class="btn btn-primary btn-sm w-100" target="_blank"><?= $quoteText ?></a></p>
                                    <p><a href="#" class="btn btn-primary btn-sm w-100" target="_blank"><?= $invoiceText ?></a></p>
                                    <p><a href="#" class="btn btn-primary btn-sm w-100" target="_blank">No Loss Form</a></p>
                                </td>
                                <td>
                                    <?php
                                    if (strpos($bindAuthorityText, "250") !== false && !$isQuoteDeclined) {
                                        if ($hiscoxID == "") {
                                            if ($policyType == "REN" && $prevHiscoxBoundID != "") {
                                                echo "<p><a href=\"#\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Hiscox Start Renewal Quote</a></p>";
                                            } else if ($policyType == "END" && $prevHiscoxBoundID != "") {
                                                echo "<p><a href=\"#\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Hiscox Start Endorsement Quote</a></p>";
                                            } else if ($policyType == "CAN" && $prevHiscoxBoundID != "") {
                                                if ($hiscoxID == "" && $boundHiscoxID != "") {
                                                    echo "<p><a href=\"" . base_url('/flood_quote/hiscox/reinstate/') . $flood_quote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Reinstate</a></p>";
                                                } else {
                                                    echo "<p><a href=\"" . base_url('/flood_quote/hiscox/cancel_preview/') . $flood_quote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Preview/Calculate Hiscox Cancellation</a></p>";
                                                }
                                            } else {
                                                echo "<p><a href=\"" . base_url('/flood_quote/hiscox/create/') . $flood_quote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Start Hiscox Quote</a></p>";
                                            }
                                            echo "<p><a href=\"" . base_url('/flood_quote/hiscox/link/') . $flood_quote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Link Hiscox</a></p>";
                                        } else if ($isBounded) {
                                            echo "<p><a href=\"" . base_url('/flood_quote/hiscox/view/') . $flood_quote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">View Hiscox Quote</a></p>";
                                        } else {
                                            if ($policyType == "REN") {
                                                echo "<p><a href=\"" . base_url('/flood_quote/hiscox/select/') . $flood_quote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Select Hiscox Quote</a></p>";
                                                echo "<p><a href=\"" . base_url('/flood_quote/hiscox/requote/') . $flood_quote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Full Requote Hiscox</a></p>";
                                            } else if ($policyType == "END") {
                                                echo "<p><a href=\"" . base_url('/flood_quote/hiscox/select/') . $flood_quote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Select Hiscox Quote</a></p>";
                                            } else {
                                                echo "<p><a href=\"" . base_url('/flood_quote/hiscox/select/') . $flood_quote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Select Hiscox Quote</a></p>";
                                                echo "<p><a href=\"" . base_url('/flood_quote/hiscox/requote/') . $flood_quote->flood_quote_id . "\" target=\"_blank\" class=\"btn btn-primary btn-sm w-100\">Full Requote Hiscox</a></p>";
                                            }
                                        }
                                    }
                                    ?>
                                </td>
                                <td></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <?= $pager_links ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>