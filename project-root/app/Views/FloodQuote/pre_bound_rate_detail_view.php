<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<?php
helper(['html', 'service']);
extract($data);

$formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
$floodOccupancyService = service('floodOccupancyService');

$flood_occupancy_id = getMetaValue($floodQuoteMetas, "flood_occupancy");
$floodOccupancy = $floodOccupancyService->findOne($flood_occupancy_id);

$deductibles = $defaultCalculations->getDeductibles();

$mle = getMetaValue($floodQuoteMetas, "mle", "0");
$mle = ($mle == "0") ? "N/A" : $mle;

$flood_foundation = getMetaValue($floodQuoteMetas, "flood_foundation", 0);
$isProperlyVented = ($flood_foundation == 1 || $flood_foundation == 2 || $flood_foundation == 4) ? "Y" : "N";

function getMetaValue($floodQuoteMetas, $meta_key, $default = '')
{
    foreach ($floodQuoteMetas as $meta) {
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

<div class="row mb-3">
    <div class="col-12">
        <a href="<?= base_url('/flood_quote/update/') . $flood_quote->flood_quote_id ?>" class="btn btn-primary">Edit Quote</a>
        <a href="<?= base_url('/flood_quote/process/') . $flood_quote->flood_quote_id . '/requote'; ?>" class="btn btn-primary">Re-Quote</a>
        <a href="<?= base_url() ?>" class="btn btn-primary">Main Flood Page</a>
        <a href="<?= base_url("/client/details/") . $flood_quote->client_id ?>" class="btn btn-primary">Client Page</a>
    </div>
</div>

<div class="row">
    <div class="col-4">
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
                <div class="row mb-1">
                    <div class="d-flex col-sm-5">Flood Zone:</div>
                    <div class="col-sm-7"><?= getMetaValue($floodQuoteMetas, "flood_zone") ?></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5">BFE:</div>
                    <div class="col-sm-7"><?= getMetaValue($floodQuoteMetas, "bfe") ?></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5">Lowest Living Floor:</div>
                    <div class="col-sm-7"><?= getMetaValue($floodQuoteMetas, "lfe") ?></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5">Effective Rating Elevation:</div>
                    <div class="col-sm-7"><?= getMetaValue($floodQuoteMetas, "elevationDifference") ?></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5">Occupancy:</div>
                    <div class="col-sm-7"><?= $floodOccupancy ? $floodOccupancy->name : "" ?></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5">Building Diagram #:</div>
                    <div class="col-sm-7"><?= getMetaValue($floodQuoteMetas, "diagramNumber") ?></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5">Properly Vented:</div>
                    <div class="col-sm-7"><?= $isProperlyVented ?></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5">Mid Level Entry Elev:</div>
                    <div class="col-sm-7"><?= $mle ?></div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <strong>Location Address</strong>
                <br />
                <div class="row mb-3">
                    <div class="col-sm-12"><?= getMetaValue($floodQuoteMetas, "propertyAddress") ?></div>
                    <div class="col-sm-12"><?= getMetaValue($floodQuoteMetas, "propertyCity") . ", " . getMetaValue($floodQuoteMetas, "propertyState") . " " .  getMetaValue($floodQuoteMetas, "propertyZip") ?></div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="row mb-1">
                    <div class="d-flex col-sm-5"><strong>Building Coverage:</strong></div>
                    <div class="col-sm-7 text-end"><strong><?= $formatter->formatCurrency(getMetaValue($floodQuoteMetas, "covABuilding", 0), 'USD') ?></strong></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5"><strong>Contents Coverage:</strong></div>
                    <div class="col-sm-7 text-end"><strong><?= $formatter->formatCurrency(getMetaValue($floodQuoteMetas, "covCContent", 0), 'USD') ?></strong></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5"><strong>Loss of Rent Coverage:</strong></div>
                    <div class="col-sm-7 text-end"><strong><?= $formatter->formatCurrency(getMetaValue($floodQuoteMetas, "covDLoss", 0), 'USD') ?></strong></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5"><strong>Building Deductible:</strong></div>
                    <div class="col-sm-7 text-end"><strong><?= $formatter->formatCurrency($deductibles["building_deductible"], 'USD') ?></strong></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5"><strong>Contents Deductible:</strong></div>
                    <div class="col-sm-7 text-end"><strong><?= $formatter->formatCurrency($deductibles["content_deductible"], 'USD') ?></strong></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5"><strong>Loss of Rent Deductible:</strong></div>
                    <div class="col-sm-7 text-end"><strong><?= $formatter->formatCurrency($deductibles["rent_deductible"], 'USD') ?></strong></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-4">
        <div class="card mb-3">
            <div class="card-body">
                <strong>Chubb/QBE Rating Details</strong>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5 justify-content-end"><strong>Base Rate:</strong></div>
                    <div class="col-sm-7"><strong><?= $defaultCalculations->baseRate ?></strong></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5 justify-content-end"><strong>Base Premium:</strong></div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($defaultCalculations->basePremium, 'USD') ?></strong></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5 justify-content-end">Deductible Saving:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($defaultCalculations->deductibleCredit, 'USD') ?></strong></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5 justify-content-end">Coverage Discount:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($defaultCalculations->dwellingValueCredit, 'USD') ?></strong></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5 justify-content-end">Primary Discount:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($defaultCalculations->primaryResidentCredit, 'USD') ?></strong></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5 justify-content-end">Loss Surcharge:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($defaultCalculations->lossDebit, 'USD') ?></strong></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5 justify-content-end">Mid Level Entry Surcharge:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($defaultCalculations->midLevelSurcharge, 'USD') ?></strong></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5 justify-content-end">Underinsured Surcharge:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($defaultCalculations->replacementCostSurcharge, 'USD') ?></strong></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5 justify-content-end">Personal Property Replacement Cost:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($defaultCalculations->personalPropertyReplacementCost, 'USD') ?></strong></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5 justify-content-end">Dwelling Replacement Cost:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($defaultCalculations->dwellingReplacementCost, 'USD') ?></strong></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5 justify-content-end">Loss Rent Premium:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($defaultCalculations->lossRentPremium, 'USD') ?></strong></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5 justify-content-end">Additional Premium:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($defaultCalculations->additionalPremium, 'USD') ?></strong></div>
                </div>
                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end"><strong>Final Premium:</strong></div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($defaultCalculations->finalPremium, 'USD') ?></strong></div>
                </div>

                <div class="row mb-1">
                    <div class="d-flex col-sm-5 justify-content-end">Final Tax:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($defaultCalculations->taxAmount, 'USD') ?></strong></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5 justify-content-end">Stamping Fee:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($defaultCalculations->additionalPremium, 'USD') ?></strong></div>
                </div>
                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end">Policy Fee:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($defaultCalculations->stampFee, 'USD') ?></strong></div>
                </div>

                <div class="row mb-1">
                    <div class="d-flex col-sm-5 justify-content-end"><strong>Total Cost:</strong></div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($defaultCalculations->finalCost, 'USD') ?></strong></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-4">
        <div class="card mb-3">
            <div class="card-body">
                <strong>BRIT Rating Details</strong>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5 justify-content-end"><strong>Base Rate:</strong></div>
                    <div class="col-sm-7"><strong><?= $britCalculations->baseRate ?></strong></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5 justify-content-end"><strong>Base Premium:</strong></div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($britCalculations->basePremium, 'USD') ?></strong></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5 justify-content-end">Deductible Saving:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($britCalculations->deductibleCredit, 'USD') ?></strong></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5 justify-content-end">Coverage Discount:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($britCalculations->dwellingValueCredit, 'USD') ?></strong></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5 justify-content-end">Primary Discount:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($britCalculations->primaryResidentCredit, 'USD') ?></strong></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5 justify-content-end">Loss Surcharge:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($britCalculations->lossDebit, 'USD') ?></strong></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5 justify-content-end">Mid Level Entry Surcharge:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($britCalculations->midLevelSurcharge, 'USD') ?></strong></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5 justify-content-end">Underinsured Surcharge:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($britCalculations->replacementCostSurcharge, 'USD') ?></strong></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5 justify-content-end">Personal Property Replacement Cost:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($britCalculations->personalPropertyReplacementCost, 'USD') ?></strong></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5 justify-content-end">Dwelling Replacement Cost:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($britCalculations->dwellingReplacementCost, 'USD') ?></strong></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5 justify-content-end">Loss Rent Premium:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($britCalculations->lossRentPremium, 'USD') ?></strong></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5 justify-content-end">Additional Premium:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($britCalculations->additionalPremium, 'USD') ?></strong></div>
                </div>
                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end"><strong>Final Premium:</strong></div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($britCalculations->finalPremium, 'USD') ?></strong></div>
                </div>

                <div class="row mb-1">
                    <div class="d-flex col-sm-5 justify-content-end">Final Tax:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($britCalculations->taxAmount, 'USD') ?></strong></div>
                </div>
                <div class="row mb-1">
                    <div class="d-flex col-sm-5 justify-content-end">Stamping Fee:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($britCalculations->additionalPremium, 'USD') ?></strong></div>
                </div>
                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end">Policy Fee:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($britCalculations->stampFee, 'USD') ?></strong></div>
                </div>

                <div class="row mb-1">
                    <div class="d-flex col-sm-5 justify-content-end"><strong>Total Cost:</strong></div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($britCalculations->finalCost, 'USD') ?></strong></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>