<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<?php
helper(['html', 'service']);
extract($data);

$formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
$floodOccupancyService = service('floodOccupancyService');

$flood_occupancy_id = $calculations->getMetaValue("flood_occupancy");
$floodOccupancy = $floodOccupancyService->findOne($flood_occupancy_id);

$deductibles = $calculations->getDeductibles();

$mle = $calculations->getMetaValue("mle", "0");
$mle = ($mle == "0") ? "N/A" : $mle;

$flood_foundation = $calculations->getMetaValue("flood_foundation", 0);
$isProperlyVented = ($flood_foundation == 1 || $flood_foundation == 2 || $flood_foundation == 4) ? "Y" : "N";

$canRenew = true;

if ($policyType == "CAN")
{
    
}

?>

<?php if (session()->getFlashdata('error') || validation_errors()) : ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
        <?= validation_list_errors() ?>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-3">
        <div class="card mb-3">
            <div class="card-body">
                <strong>Insured Name &amp; Mailing Address</strong>
                <br />
                <br />
                <span><?= $flood_quote->first_name . " " . $flood_quote->last_name ?></span><br />
                <span><?= $flood_quote->address ?></span><br />
                <span><?= $flood_quote->city . ", " . $flood_quote->state . " " . $flood_quote->zip ?></span>
                <br />
                <br />
                <span>Home: <?= $flood_quote->home_phone ?></span><br />
                <span>Cell: <?= $flood_quote->cell_phone ?></span><br />
                <span>Email: <?= $flood_quote->email ?></span><br />
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <strong>Building Information</strong>
                <br />
                <br />
                <div class="row mb-3">
                    <div class="d-flex col-sm-5">Flood Zone:</div>
                    <div class="col-sm-7"><?= $calculations->getMetaValue("flood_zone") ?></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5">BFE:</div>
                    <div class="col-sm-7"><?= $calculations->getMetaValue("bfe") ?></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5">Lowest Living Floor:</div>
                    <div class="col-sm-7"><?= $calculations->getMetaValue("lfe") ?></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5">Effective Rating Elevation:</div>
                    <div class="col-sm-7"><?= $calculations->getMetaValue("elevationDifference") ?></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5">Occupancy:</div>
                    <div class="col-sm-7"><?= $floodOccupancy ? $floodOccupancy->name : "" ?></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5">Building Diagram #:</div>
                    <div class="col-sm-7"><?= $calculations->getMetaValue("diagram_num") ?></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5">Properly Vented:</div>
                    <div class="col-sm-7"><?= $isProperlyVented ?></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5">Mid Level Entry Elev:</div>
                    <div class="col-sm-7"><?= $mle ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-3">
        <div class="card mb-3">
            <div class="card-body">
                <strong>Location Address</strong>
                <br />
                <div class="row mb-3">
                    <div class="col-sm-12"><?= $calculations->getMetaValue("propertyAddress") ?></div>
                    <div class="col-sm-12"><?= $calculations->getMetaValue("propertyCity") . ", " . $calculations->getMetaValue("propertyState") . " " .  $calculations->getMetaValue("propertyZip") ?></div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="d-flex col-sm-5"><strong>Building Coverage:</strong></div>
                    <div class="col-sm-7 text-end"><strong><?= $formatter->formatCurrency($calculations->getMetaValue("covABuilding", 0), 'USD') ?></strong></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5"><strong>Contents Coverage:</strong></div>
                    <div class="col-sm-7 text-end"><strong><?= $formatter->formatCurrency($calculations->getMetaValue("covCContent", 0), 'USD') ?></strong></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5"><strong>Loss of Rent Coverage:</strong></div>
                    <div class="col-sm-7 text-end"><strong><?= $formatter->formatCurrency($calculations->getMetaValue("covDLoss", 0), 'USD') ?></strong></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5"><strong>Building Deductible:</strong></div>
                    <div class="col-sm-7 text-end"><strong><?= $formatter->formatCurrency($deductibles["building_deductible"], 'USD') ?></strong></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5"><strong>Contents Deductible:</strong></div>
                    <div class="col-sm-7 text-end"><strong><?= $formatter->formatCurrency($deductibles["content_deductible"], 'USD') ?></strong></div>
                </div>

                <div class="row mb-4">
                    <div class="d-flex col-sm-5"><strong>Loss of Rent Deductible:</strong></div>
                    <div class="col-sm-7 text-end"><strong><?= $formatter->formatCurrency($deductibles["rent_deductible"], 'USD') ?></strong></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-3">
        <div class="card mb-3">
            <div class="card-body">
                <strong>Bound Rating Details</strong>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end"><strong>Base Rate:</strong></div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($boundBaseRate, 'USD') ?></strong></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end"><strong>Base Premium:</strong></div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($boundBasePremium, 'USD') ?></strong></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end">Deductible Saving:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($boundDeductibleSaving, 'USD') ?></strong></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end">Coverage Discount:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($boundCoverageDiscount, 'USD') ?></strong></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end">Primary Discount:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($boundPrimaryDiscount, 'USD') ?></strong></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end">Loss Surcharge:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($boundLossSurcharge, 'USD') ?></strong></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end">Mid Level Entry Surcharge:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($boundMidLevelSurcharge, 'USD') ?></strong></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end">Underinsured Surcharge:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($boundReplacementCostSurcharge, 'USD') ?></strong></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end">Personal Property Replacement Cost:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($boundPersonalPropertySurcharge, 'USD') ?></strong></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end">Dwelling Replacement Cost:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($boundDwellSurcharge, 'USD') ?></strong></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end"><strong>Final Premium:</strong></div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($boundFinalPremium, 'USD') ?></strong></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end">Final Tax:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($boundTaxAmount, 'USD') ?></strong></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end">Policy Fee:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($boundPolicyFee, 'USD') ?></strong></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end">Stamping Fee:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($boundStampFee, 'USD') ?></strong></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end"><strong>Total Cost:</strong></div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($boundTotalCost, 'USD') ?></strong></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-3">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row mb-3">
                    <?php if ($policyType == "CAN") { ?>
                        <a href="#" onclick="alert('Could not Endorse a Canceled Quote!'); return false;" class="btn btn-info mb-3" role="button">Endorse</a>
                    <?php } else { ?>
                        <a href="<?= base_url('/flood_quote/process/') . $flood_quote->flood_quote_id . '/endorse'; ?>" class="btn btn-info mb-3" role="button">Endorse</a>
                    <?php } ?>
                    <a href="<?= base_url('/flood_quote/process/') . $flood_quote->flood_quote_id . '/renew'; ?>" class="btn btn-info mb-3" role="button">Renew</a>
                    <a href="<?= base_url('/flood_quote/process/') . $flood_quote->flood_quote_id . '/cancel'; ?>" class="btn btn-info mb-3" role="button">Cancel</a>
                    <a href="<?= base_url('/client/details/') . $flood_quote->client_id; ?>" class="btn btn-info mb-3" role="button">Client Page</a>
                    <a href="<?= base_url(); ?>" class="btn btn-info" role="button">Main Flood Page</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>