<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<?php
helper(['html', 'service']);
$flood_quote = $data['flood_quote'];
$calculations = $data['calculations'];

$formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
$floodOccupancyService = service('floodOccupancyService');

$flood_occupancy_id = $calculations->getMetaValue("flood_occupancy");
$floodOccupancy = $floodOccupancyService->findOne($flood_occupancy_id);

$deductibles = $calculations->getDeductibles();
?>

<?php if (session()->getFlashdata('error') || validation_errors()) : ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
        <?= validation_list_errors() ?>
    </div>
<?php endif; ?>

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
                    <div class="col-sm-7"><?= $calculations->getMetaValue("flfe") ?></div>
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
                    <div class="col-sm-7"><?= $calculations->getMetaValue("diagramNumber") ?></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5">Mid Level Entry Elev:</div>
                    <div class="col-sm-7"><?= $calculations->getMetaValue("mle", "N/A") ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-4">
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

                <div class="row mb-3">
                    <div class="d-flex col-sm-5">Total Base Premium =</div>
                    <div class="col-sm-7 text-end"><?= $formatter->formatCurrency($calculations->basePremium, 'USD') ?></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5"><?= $calculations->getMetaValue("propertyState") ?> State Tax =</div>
                    <div class="col-sm-7 text-end"><?= $formatter->formatCurrency($calculations->taxAmount, 'USD') ?></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5">Policy Fee =</div>
                    <div class="col-sm-7 text-end"><?= $formatter->formatCurrency($calculations->policyFee, 'USD') ?></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5">Stamping Fee =</div>
                    <div class="col-sm-7 text-end"><?= $formatter->formatCurrency($calculations->stampFee, 'USD') ?></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5"><strong>Total Premium =</strong></div>
                    <div class="col-sm-7 text-end"><?= $formatter->formatCurrency($calculations->finalCost, 'USD') ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-4">
        <div class="card mb-3">
            <div class="card-body">
                <strong>Initial Rating Details</strong>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end"><strong>Base Rate:</strong></div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($calculations->baseRate, 'USD') ?></strong></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end"><strong>Base Premium:</strong></div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($calculations->basePremium, 'USD') ?></strong></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end">Deductible Saving:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($calculations->deductibleCredit, 'USD') ?></strong></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end">Coverage Discount:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($calculations->dwellingValueCredit, 'USD') ?></strong></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end">Primary Discount:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($calculations->primaryResidentCredit, 'USD') ?></strong></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end">Loss Surcharge:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($calculations->lossDebit, 'USD') ?></strong></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end">Mid Level Entry Surcharge:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($calculations->midLevelSurcharge, 'USD') ?></strong></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end">Underinsured Surcharge:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($calculations->replacementCostSurcharge, 'USD') ?></strong></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end">Personal Property Replacement Cost:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($calculations->personalPropertyReplacementCost, 'USD') ?></strong></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end">Dwelling Replacement Cost:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($calculations->dwellingReplacementCost, 'USD') ?></strong></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end"><strong>Final Premium:</strong></div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($calculations->finalPremium, 'USD') ?></strong></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end">Final Tax:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($calculations->taxAmount, 'USD') ?></strong></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end">Policy Fee:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($calculations->policyFee, 'USD') ?></strong></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end">Stamping Fee:</div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($calculations->stampFee, 'USD') ?></strong></div>
                </div>

                <div class="row mb-3">
                    <div class="d-flex col-sm-5 justify-content-end"><strong>Total Cost:</strong></div>
                    <div class="col-sm-7"><strong><?= $formatter->formatCurrency($calculations->finalCost, 'USD') ?></strong></div>
                </div>
                <div class="row mb-3">
                    <ul>
                        <li>
                            <a href="<?= base_url('/flood_quote/update/') . $flood_quote->flood_quote_id; ?>">Edit Rate Info</a>
                        </li>
                        <li>
                            <a href="#">App</a>
                        </li>
                        <li>
                            <a href="<?= base_url('/flood_quotes'); ?>">Quotes Page</a>
                        </li>
                        <li>
                            <a href="<?= base_url('/client/details/') . $flood_quote->client_id; ?>">Client Page</a>
                        </li>
                        <li>
                            <a href="<?= base_url(); ?>">Main Flood Page</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>