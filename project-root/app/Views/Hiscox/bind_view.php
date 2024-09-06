<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<?php
helper(['html', 'service']);
extract($data);
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
                <p><?= $floodQuote->first_name . ' ' . $floodQuote->last_name ?></p>
                <p><?= $floodQuote->address ?></p>
                <p><?= $floodQuote->city . ', ' . $floodQuote->state . ' ' . $floodQuote->zip ?></p>

                <div class="row mb-3">
                    <div class="col-6">Home:</div>
                    <div class="col-6"><?= $floodQuote->home_phone ?></div>

                    <div class="col-6">Cell:</div>
                    <div class="col-6"><?= $floodQuote->cell_phone ?></div>

                    <div class="col-6">Email:</div>
                    <div class="col-6"><?= $floodQuote->email ?></div>

                    <div class="col-6">Building Coverage</div>
                    <div class="col-6"><?= $hiscoxQuotedDwellCov ?></div>

                    <div class="col-6">Contents Coverage</div>
                    <div class="col-6"><?= $hiscoxQuotedPersPropCov ?></div>

                    <div class="col-6">Loss of Rent Coverage</div>
                    <div class="col-6"><?= $hiscoxQuotedLossCov ?></div>

                    <div class="col-6">Building Deductible</div>
                    <div class="col-6"></div>
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
            </div>
        </div>
    </div>

    <div class="col-5">
        <div class="card">
            <div class="card-body">
                <h4>HISCOX Binding Process</h4>
                <form method="POST" name="bindForm" id="bindForm">
                    <?= csrf_field() ?>
                    <div class="row mb-3">
                        <div class="col-6">
                            <h5>Binding Authority: Hiscox</h5>
                            <p>PolicyType = <?= $policyType ?></p>
                            <p>
                                <strong>Location address:</strong><br />
                                <?= $propertyAddress ?><br />
                                <?= $propertyCity . ', ' . $propertyState . ' ' . $propertyZip ?><br />
                            </p>
                        </div>

                        <div class="col-6">
                            <p>
                                Hiscox ID: <?= $hiscoxID ?><br />
                                Quoted Hiscox Premium: <?= $hiscoxQuotedPremium ?><br />
                                Hiscox Premium Override: <?= $hiscoxPremiumOverride ?><br />
                                Deductible: <?= $hiscoxQuotedDeductible ?><br />
                                Dwell Cov: <?= $hiscoxQuotedDwellCov ?><br />
                                Personal Prop Cov: <?= $hiscoxQuotedPersPropCov ?><br />
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
                        <label class="d-flex justify-content-end col-sm-6 col-form-label">Base Premium:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="boundBasePremium" value="<?= $calculations->finalPremium ?>" />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="d-flex justify-content-end col-sm-6 col-form-label">Loss Use Cov:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="boundLossUseCoverage" value=<?= $hiscoxQuotedLossCov ?> />
                        </div>
                    </div>


                    <div class="row mb-1">
                        <label class="d-flex justify-content-end col-sm-6 col-form-label">Additional Premium:</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="boundAdditionalPremium" value=<?= $additionalPremium ?> />
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
                                <input type="text" class="form-control" value=<?= $proratedDue ?> />
                            </div>
                        </div>
                    <?php } ?>

                    <div class="row mb-1">
                        <label class="d-flex justify-content-end col-sm-6 col-form-label">Effective Date:</label>
                        <div class="col-sm-6">
                            <input type="date" class="form-control" value="<?= set_value('effectiveDate', date('Y-m-d', strtotime($floodQuote->effectivity_date))) ?>" />
                        </div>
                    </div>
                    <div class="row mb-1">
                        <label class="d-flex justify-content-end col-sm-6 col-form-label">Expiration Date:</label>
                        <div class="col-sm-6">
                            <input type="date" class="form-control" value="<?= set_value('expirationDate', date('Y-m-d', strtotime($floodQuote->expiration_date))) ?>" />
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