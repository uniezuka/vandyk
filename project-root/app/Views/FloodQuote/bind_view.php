<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<?php
helper(['html', 'service']);
extract($data);

$formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
$deductibles = $calculations->getDeductibles();
$bindingProcessText = "";

if (!strpos($bindAuthorityText, '230') === false) {
    $bindingProcessText = "BRIT Binding Process";
} else {
    $bindingProcessText = "QBE/Chubb Binding Process";
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
    <div class="col-5">
        <div class="card">
            <div class="card-body">
                <h5>Insured Name & Mailing Address</h5>
                <p>
                    <?= $floodQuote->first_name . ' ' . $floodQuote->last_name ?><br />
                    <?= $floodQuote->address ?><br />
                    <?= $floodQuote->city . ', ' . $floodQuote->state . ' ' . $floodQuote->zip ?>
                </p>

                <div class="row mb-3">
                    <div class="col-6">Home:</div>
                    <div class="col-6"><?= $floodQuote->home_phone ?></div>

                    <div class="col-6">Cell:</div>
                    <div class="col-6"><?= $floodQuote->cell_phone ?></div>

                    <div class="col-6">Email:</div>
                    <div class="col-6"><?= $floodQuote->email ?></div>

                    <div class="col-6">Building Coverage</div>
                    <div class="col-6"><?= $formatter->formatCurrency($covABuilding, 'USD') ?></div>

                    <div class="col-6">Contents Coverage</div>
                    <div class="col-6"><?= $formatter->formatCurrency($covCContent, 'USD') ?></div>

                    <div class="col-6">Loss of Rent Coverage</div>
                    <div class="col-6"><?= $formatter->formatCurrency($covDLossUse, 'USD') ?></div>

                    <div class="col-6">Building Deductible</div>
                    <div class="col-6"><?= $formatter->formatCurrency($deductibles["building_deductible"], 'USD') ?></div>
                </div>

                <h5>Building Information</h5>

                <div class="row mb-3">
                    <div class="col-6">Flood Zone</div>
                    <div class="col-6"><?= $floodZone ?></div>

                    <div class="col-6">BFE</div>
                    <div class="col-6"><?= $bfe ?></div>

                    <div class="col-6">Lowest Living Floor</div>
                    <div class="col-6"><?= $bfe ?></div>

                    <div class="col-6">Elevation Difference</div>
                    <div class="col-6"><?= $lfe ?></div>

                    <div class="col-6">Occupancy</div>
                    <div class="col-6"><?= $floodOccupancy ?></div>

                    <div class="col-6">Building Diagram #</div>
                    <div class="col-6"><?= $diagramNumber ?></div>

                    <div class="col-6">Properly Vented</div>
                    <div class="col-6"></div>

                    <div class="col-6">Mid Level Entry Elev</div>
                    <div class="col-6"><?= $mle ?></div>
                </div>

                <div class="row mb-3">
                    <div class="col-6">Total Base Premium =</div>
                    <div class="col-6"><?= $formatter->formatCurrency($calculations->basePremium, 'USD') ?></div>

                    <div class="col-6"><?= $calculations->getMetaValue("propertyState") ?> State Tax =</div>
                    <div class="col-6"><?= $formatter->formatCurrency($calculations->taxAmount, 'USD') ?></div>

                    <div class="col-6">Policy Fee =</div>
                    <div class="col-6"><?= $formatter->formatCurrency($calculations->policyFee, 'USD') ?></div>

                    <div class="col-6"><strong>Total Premium =</strong></div>
                    <div class="col-6"><?= $formatter->formatCurrency($calculations->finalCost, 'USD') ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-5">
        <div class="card">
            <div class="card-body">
                <h4><?= $bindingProcessText ?></h4>
                <form method="POST" name="bindForm" id="bindForm">
                    <?= csrf_field() ?>
                    <div class="row mb-3">
                        <div class="col-6">
                            <p>
                                <strong>Location address:</strong><br />
                                <?= $propertyAddress ?><br />
                                <?= $propertyCity . ', ' . $propertyState . ' ' . $propertyZip ?><br />
                            </p>
                        </div>
                    </div>

                    <div class="row mb-1">
                        <label class="d-flex justify-content-end col-sm-6 col-form-label">Binding Details</label>
                        <div class="col-sm-6">
                            <input type="text" readonly class="form-control" value=<?= $policyType ?> />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="d-flex justify-content-end col-sm-6 col-form-label">Base Rate:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="boundBaseRate" value="<?= $calculations->baseRate ?>" />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="d-flex justify-content-end col-sm-6 col-form-label">Base Premium:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="boundBasePremium" value="<?= $calculations->basePremium ?>" />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="d-flex justify-content-end col-sm-6 col-form-label">Loss Use Rate:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="boundLossUseRate" value="<?= $calculations->lossUseAdjustmentRate ?>" />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="d-flex justify-content-end col-sm-6 col-form-label">Loss Use Cov:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="boundLossUseCoverage" value=<?= $calculations->lossUseCoverage ?> />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="d-flex justify-content-end col-sm-6 col-form-label">Loss Use Premium:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="boundLossUsePremium" value=<?= $calculations->lossRentPremium ?> />
                        </div>
                    </div>
                    <div class="row mb-5">
                        <label class="d-flex justify-content-end col-sm-6 col-form-label">Total Coverage:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="boundTotalCoverages" value=<?= $calculations->totalCoverages ?> />
                        </div>
                    </div>

                    <div class="row mb-1">
                        <label class="d-flex justify-content-end col-sm-6 col-form-label">Deductible Saving:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="boundDeductibleSaving" value=<?= $calculations->deductibleCredit ?> />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="d-flex justify-content-end col-sm-6 col-form-label">Coverage Discount:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="boundCoverageDiscount" value=<?= $calculations->dwellingValueCredit ?> />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="d-flex justify-content-end col-sm-6 col-form-label">Primary Discount:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="boundPrimaryDiscount" value=<?= $calculations->primaryResidentCredit ?> />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="d-flex justify-content-end col-sm-6 col-form-label">Loss Surcharge:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="boundLossSurcharge" value=<?= $calculations->lossDebit ?> />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="d-flex justify-content-end col-sm-6 col-form-label">Mid Level Entry Surcharge:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="boundMidLevelSurcharge" value=<?= $calculations->midLevelSurcharge ?> />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="d-flex justify-content-end col-sm-6 col-form-label">Underinsured Surcharge:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="boundReplacementCostSurcharge" value=<?= $calculations->replacementCostSurcharge ?> />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="d-flex justify-content-end col-sm-6 col-form-label">Personal Property Replacement Cost:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="boundPersonalPropertySurcharge" value=<?= $calculations->personalPropertyReplacementCost ?> />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="d-flex justify-content-end col-sm-6 col-form-label">Dwelling Replacement Cost:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="boundDwellingSurcharge" value=<?= $calculations->dwellingReplacementCost ?> />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="d-flex justify-content-end col-sm-6 col-form-label">Additional Premium:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="boundAdditionalPremium" value=<?= $calculations->additionalPremium ?> />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="d-flex justify-content-end col-sm-6 col-form-label">Final Premium:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="boundFinalPremium" value=<?= $calculations->finalPremium ?> />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="d-flex justify-content-end col-sm-6 col-form-label">Final Tax:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="boundTaxAmount" value=<?= $calculations->taxAmount ?> />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="d-flex justify-content-end col-sm-6 col-form-label">Policy Fee:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="boundPolicyFee" value=<?= $calculations->policyFee ?> />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="d-flex justify-content-end col-sm-6 col-form-label">Stamping Fee:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="boundStampFee" value=<?= $calculations->stampFee ?> />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="d-flex justify-content-end col-sm-6 col-form-label">Total Cost:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="boundTotalCost" value=<?= $calculations->finalCost ?> />
                        </div>
                    </div>

                    <?php if ($policyType == "END" || $policyType == "CAN") { ?>
                        <div class="row mb-1">
                            <label class="d-flex justify-content-end col-sm-6 col-form-label">Prorated Endorse Premium Due:</label>
                            <div class="col-sm-6">
                                <input type="text" name="proratedDue" class="form-control" value=<?= $proratedDue ?> />
                            </div>
                        </div>
                    <?php } ?>

                    <div class="row mb-1">
                        <label class="d-flex justify-content-end col-sm-6 col-form-label">Effective Date:</label>
                        <div class="col-sm-6">
                            <input type="date" class="form-control" readonly value="<?= set_value('effectiveDate', date('Y-m-d', strtotime($floodQuote->effectivity_date))) ?>" />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="d-flex justify-content-end col-sm-6 col-form-label">Expiration Date:</label>
                        <div class="col-sm-6">
                            <input type="date" class="form-control" readonly value="<?= set_value('expirationDate', date('Y-m-d', strtotime($floodQuote->expiration_date))) ?>" />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="d-flex justify-content-end col-sm-6 col-form-label">Policy #:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="policyNumber" value="<?= $policyNumber ?>" />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="d-flex justify-content-end col-sm-6 col-form-label">Previous Policy #:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="previousPolicyNumber" value="<?= $previousPolicyNumber ?>" />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="d-flex justify-content-end col-sm-6 col-form-label">&nbsp;</label>
                        <div class="col-sm-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" name="inForce">
                                <label class="form-check-label">Set In Force</label>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="d-flex justify-content-end col-sm-6 col-form-label">Date Bound:</label>
                        <div class="col-sm-6">
                            <input type="date" class="form-control" name="boundDate" required value="<?php echo date('Y-m-d'); ?>" />
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button type="submit" class="btn btn-primary">Bind Quote</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>