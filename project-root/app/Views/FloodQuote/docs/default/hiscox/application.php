<?= $this->extend('layouts/print', ['data' => $data]) ?>
<?= $this->section('content') ?>

<?php
$formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
helper('html');
extract($data);

$countyService = service('countyService');
$constructionService = service('constructionService');
$floodZoneService = service('floodZoneService');

$invoiceTitle = "";
$isExcessPolicy = (int)getMetaValue($floodQuoteMetas, "isExcessPolicy", 0);
$billTo = (int)getMetaValue($floodQuoteMetas, "billTo", 1);
$entityType = $floodQuote->entity_type;
$propertyAddress = getMetaValue($floodQuoteMetas, "propertyAddress");
$propertyCity = getMetaValue($floodQuoteMetas, "propertyCity");
$propertyState = getMetaValue($floodQuoteMetas, "propertyState");
$propertyZip = getMetaValue($floodQuoteMetas, "propertyZip");
$flood_occupancy = (int)getMetaValue($floodQuoteMetas, "flood_occupancy", 0);
$underlyingBuildLimit = (int)getMetaValue($floodQuoteMetas, "underlyingBuildLimit", 0);
$underlyingContentLimit = (int)getMetaValue($floodQuoteMetas, "underlyingContentLimit", 0);
$hasWaitPeriod = (int)getMetaValue($floodQuoteMetas, "hasWaitPeriod", 0);
$hasClosing = (int)getMetaValue($floodQuoteMetas, "hasClosing", 0);
$isPrimaryResidence = (int)getMetaValue($floodQuoteMetas, "isPrimaryResidence", 0);
$county_id = (int)getMetaValue($floodQuoteMetas, "propertyCounty", 0);
$county = $countyService->findOne($county_id);
$flood_foundation = (int)getMetaValue($floodQuoteMetas, "flood_foundation", 0);
$isProperlyVented = ($flood_foundation == 1 || $flood_foundation == 2 || $flood_foundation == 4) ? "Y" : "N";
$isRented = (int)getMetaValue($floodQuoteMetas, "isRented", 0);
$construction_type = (int)getMetaValue($floodQuoteMetas, "construction_type", 0);
$hasElevatedSinceLastLoss = (int)getMetaValue($floodQuoteMetas, "hasElevatedSinceLastLoss", 0);
$elevCertDate = (getMetaValue($floodQuoteMetas, "elevCertDate") == "") ? "&nbsp;" : date('m/d/Y', getMetaValue($floodQuoteMetas, "elevCertDate"));
$improvementDate = (getMetaValue($floodQuoteMetas, "improvementDate") == "") ? "&nbsp;" : date('m/d/Y', getMetaValue($floodQuoteMetas, "improvementDate"));
$flood_zone = (int)getMetaValue($floodQuoteMetas, 'flood_zone', 0);
$hasDrc = (int)getMetaValue($floodQuoteMetas, "hasDrc", 0);
$hasOpprc = (int)getMetaValue($floodQuoteMetas, "hasOpprc", 0);
$excessBuildingLimit = (int)getMetaValue($floodQuoteMetas, "excessBuildingLimit", 0);
$excessContentLimit = (int)getMetaValue($floodQuoteMetas, "excessContentLimit", 0);

$construction = $constructionService->findOne($construction_type);
$floodZone = $floodZoneService->findOne($flood_zone);

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

<style>
    .signature-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    @media print {
        .form-info {
            font-size: 10px;
        }

        h3 {
            font-size: 22px;
        }

        .table> :not(caption)>*>* {
            padding: .25rem .25rem;
        }
    }
</style>

<div class="content-wrapper">
    <div class="row">
        <div class="col-6">
            <div class="logo">
                <img src="<?= base_url('assets/images/IACHeaderLogo.gif'); ?>" alt="logo" width="352" height="100">
            </div>
        </div>

        <div class="col-6 text-center">
            <h3 class="mt-3">
                Private Flood Insurance Application
            </h3>
        </div>
    </div>

    <div class="row form-info">
        <div class="col-4"></div>
        <div class="col-8">
            <div class="row">
                <div class="col-3 border border-dark">Effective Date:</div>
                <div class="col-3 border border-dark"><?= date('m/d/Y', strtotime($floodQuote->effectivity_date)) ?></div>
                <div class="col-3 border border-dark">Expiration Date:</div>
                <div class="col-3 border border-dark"><?= date('m/d/Y', strtotime($floodQuote->expiration_date)) ?></div>
            </div>
        </div>
    </div>

    <div class="row form-info">
        <div class="col-4 border border-dark">
            <p>
                <strong>Agency Information:</strong><br />
                <?= $broker->name ?><br>
                <?= $broker->address ?><br>
                <?= $broker->city ?>, <?= $broker->state ?> &nbsp;&nbsp;<?= $broker->zip ?><br>
                <br />
                <?= $broker->phone ?>
            </p>
        </div>

        <div class="col-4 border border-dark">
            <strong>Billing:</strong>

            <div class="row">
                <div class="col-10">Bill Insured</div>
                <div class="col-2 text-center border"><?= ($billTo == 1) ? "X" : "" ?></div>
            </div>

            <div class="row">
                <div class="col-10">Bill 1st Mortgagee</div>
                <div class="col-2 text-center border"><?= ($billTo == 2) ? "X" : "" ?></div>
            </div>

            <div class="row mt-4">
                <div class="col-10">7 Day Waiting Period</div>
                <div class="col-2 text-center border"><?= ($hasWaitPeriod == 1) ? "X" : "" ?></div>
            </div>

            <div class="row">
                <div class="col-10">No Waiting Period</div>
                <div class="col-2 text-center border"><?= ($hasClosing == 1) ? "X" : "" ?></div>
            </div>
        </div>

        <div class="col-4 border border-dark">
            <p>
                <strong>Insured Name &amp; Mailing Address</strong><br />

                <?php if ($entityType == 1): ?>
                    <?= $client->business_name ?><br>
                    <?= $client->business_name2 ?>
                <?php else: ?>
                    <?= $client->first_name ?> <?= $client->last_name ?><br>
                    <?= $client->insured2_name ?>
                <?php endif; ?><br />
                <?= $client->address ?><br />
                <?= $client->city ?>, <?= $client->state ?>&nbsp;<?= $client->zip ?><br />
                <br />
                Home: <?= $client->home_phone ?><br />
                Cell: <?= $client->cell_phone ?><br />
                Email: <?= $client->email ?>
            </p>
        </div>
    </div>

    <div class="row form-info">
        <div class="col-6 border border-dark">
            Is Location same as mailing address:
        </div>

        <div class="col-6 border">
            Is location Insured's Principal Residence: <?= ($isPrimaryResidence == 1) ? "Y" : "N" ?>
        </div>
    </div>

    <div class="row form-info">
        <div class="col-6 border border-dark">
            <div class="row">
                <div class="col-6">Location address:</div>
                <div class="col-6">
                    <?= $propertyAddress ?><br />
                    <?= $propertyCity ?>, <?= $propertyState ?> <?= $propertyZip ?>
                </div>
            </div>
        </div>

        <div class="col-6 border border-dark">
            <div class="row">
                <div class="col-6">County</div>
                <div class="col-6">
                    <?= ($county) ? $county->name : "" ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row form-info">
        <div class="col-4 border border-dark">
            <strong>Building Occupancy:</strong>

            <div class="row">
                <div class="col-10">Single Family Primary</div>
                <div class="col-2 text-center border border-dark"><?= ($flood_occupancy == 1) ? "X" : "&nbsp;" ?></div>
            </div>

            <div class="row">
                <div class="col-10">Single Family Secondary</div>
                <div class="col-2 text-center border border-dark"><?= ($flood_occupancy == 2) ? "X" : "&nbsp;" ?></div>
            </div>

            <div class="row">
                <div class="col-10">2-4 Family</div>
                <div class="col-2 text-center border border-dark"><?= ($flood_occupancy == 3) ? "X" : "&nbsp;" ?></div>
            </div>

            <div class="row">
                <div class="col-10">Low Rise Condo</div>
                <div class="col-2 text-center border border-dark"><?= ($flood_occupancy == 5) ? "X" : "&nbsp;" ?></div>
            </div>

            <div class="row">
                <div class="col-10">High Rise Condo</div>
                <div class="col-2 text-center border"><?= ($flood_occupancy == 6) ? "X" : "&nbsp;" ?></div>
            </div>

            <div class="row">
                <div class="col-10">Non-Residential</div>
                <div class="col-2 text-center border border-dark"><?= ($flood_occupancy == 4) ? "X" : "&nbsp;" ?></div>
            </div>

            <div class="row">
                <div class="col-10">Other Residential</div>
                <div class="col-2 text-center border border-dark"><?= ($flood_occupancy == 7) ? "X" : "&nbsp;" ?></div>
            </div>

            <div class="row mt-4">
                <div class="col-10">Number of Floors</div>
                <div class="col-2 text-center border border-dark"><?= getMetaValue($floodQuoteMetas, "numOfFloors", 0) ?></div>
            </div>
        </div>

        <div class="col-4 border border-dark">
            <strong>Foundation</strong>

            <div class="row">
                <div class="col-10">Open Pilings</div>
                <div class="col-2 text-center border border-dark"><?= ($flood_foundation == 1) ? "X" : "&nbsp;" ?></div>
            </div>

            <div class="row">
                <div class="col-10">Pilings - Partial Enclosure</div>
                <div class="col-2 text-center border border-dark"><?= ($flood_foundation == 1 || $flood_foundation == 8) ? "X" : "&nbsp;" ?></div>
            </div>

            <div class="row">
                <div class="col-10">Pilings - Full Enclosure</div>
                <div class="col-2 text-center border border-dark"><?= ($flood_foundation == 3 || $flood_foundation == 9) ? "X" : "&nbsp;" ?></div>
            </div>

            <div class="row">
                <div class="col-10">Above Grade Crawlspace</div>
                <div class="col-2 text-center border border-dark"><?= ($flood_foundation == 4 || $flood_foundation == 5) ? "X" : "&nbsp;" ?></div>
            </div>

            <div class="row">
                <div class="col-10">Below Grade Crawlspace</div>
                <div class="col-2 text-center border"><?= ($flood_foundation == 6 || $flood_foundation == 7) ? "X" : "&nbsp;" ?></div>
            </div>

            <div class="row">
                <div class="col-10">Basement</div>
                <div class="col-2 text-center border border-dark"><?= ($flood_foundation == 12) ? "X" : "&nbsp;" ?></div>
            </div>

            <div class="row">
                <div class="col-10">Slab</div>
                <div class="col-2 text-center border border-dark"><?= ($flood_foundation == 10 || $flood_foundation == 11) ? "X" : "&nbsp;" ?></div>
            </div>
        </div>

        <div class="col-4 border border-dark">
            <strong>Structural Information</strong>

            <div class="row">
                <div class="col-8">Year Built</div>
                <div class="col-4 text-center border border-dark"><?= getMetaValue($floodQuoteMetas, "yearBuilt") ?></div>
            </div>

            <div class="row">
                <div class="col-8">Construction Type</div>
                <div class="col-4 text-center border border-dark"><?= $calculations->constructionType ?></div>
            </div>

            <div class="row">
                <div class="col-8">Square Footage</div>
                <div class="col-4 text-center border border-dark"><?= getMetaValue($floodQuoteMetas, "squareFeet", 0) ?></div>
            </div>

            <div class="row">
                <div class="col-8">Total Replacement Cost</div>
                <div class="col-4 text-center border border-dark"><?= getMetaValue($floodQuoteMetas, "buildingReplacementCost", 0) ?></div>
            </div>

            <div class="row">
                <div class="col-8">Is the home rented?</div>
                <div class="col-4 text-center border border-dark"><?= $isRented ? "Y" : "N" ?></div>
            </div>

            <div class="row">
                <div class="col-8">Finished Basement/Enclosure</div>
                <div class="col-4 text-center border border-dark">
                    <?php
                    if ($calculations->requiredElevated == 'No' && $calculations->requiredBasement == 'No') {
                        echo "N";
                    } elseif ($calculations->requiredBasement == 'Yes' && $calculations->basementStatus == 'Finished') {
                        echo "Y";
                    } elseif ($calculations->requiredElevated == 'Yes') {
                        echo "Y";
                    } else {
                        echo "N";
                    } ?>
                </div>
            </div>

            <div class="row">
                <div class="col-8">Has home been elevated</div>
                <div class="col-4 text-center border border-dark">N</div>
            </div>
        </div>
    </div>

    <div class="row form-info mt-1">
        <table class="table table-bordered mb-0 border-dark">
            <tr>
                <td colspan="2">
                    <p class="mb-0">
                        First Mortgagee:<br />
                        <?= $mortgage1->name ?><br />
                        <?= $mortgage1->name2 ?><br />
                        <?= $mortgage1->address ?><br />
                        <?= $mortgage1->city ?>, <?= $mortgage1->state ?>&nbsp;&nbsp;<?= $mortgage1->zip ?><br />
                        <?= $mortgage1->phone ?>
                    </p>
                </td>
                <td colspan="2">
                    <p class="mb-0">
                        Second Mortgagee:<br />
                        <?= $mortgage2->name ?><br />
                        <?= $mortgage2->name2 ?><br />
                        <?= $mortgage2->address ?><br />
                        <?= $mortgage2->city ?>, <?= $mortgage2->state ?>&nbsp;&nbsp;<?= $mortgage2->zip ?><br />
                        <?= $mortgage2->phone ?>
                    </p>
                </td>
            </tr>
            <tr>
                <td>Loan #</td>
                <td><?= $mortgage1->loan_number ?></td>
                <td>Loan #</td>
                <td><?= $mortgage2->loan_number ?></td>
            </tr>
        </table>
    </div>

    <div class="row form-info mt-1">
        <table class="table table-bordered mb-0 border-dark">
            <tr>
                <td colspan="2">
                    <strong>Number of losses in the past 5 years</strong>
                </td>
                <td><?= getMetaValue($floodQuoteMetas, "lossesIn10Years", 0) ?></td>
                <td colspan="2">
                    <strong>Loss Total past 5 years:</strong>
                </td>
                <td>
                    <?= $formatter->formatCurrency(getMetaValue($floodQuoteMetas, "totalLossValueIn10Years", 0), 'USD') ?>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    &nbsp;
                </td>
            </tr>
            <tr>
                <td><strong>Date of Elevation Cert</strong></td>
                <td><?= $elevCertDate ?></td>
                <td>
                    <strong>Current Flood Zone</strong>
                </td>
                <td><?= $floodZone->name ?></td>
                <td>
                    <strong>Base Flood Elevation</strong>
                </td>
                <td><?= getMetaValue($floodQuoteMetas, "bfe", 0) ?></td>
            </tr>
            <tr>
                <td>
                    <strong>Building Diagram #</strong>
                </td>
                <td><?= getMetaValue($floodQuoteMetas, "diagramNumber", 0) ?></td>
                <td>
                    <strong>Elevated Home</strong>
                </td>
                <td><?= $calculations->requiredElevated == "Yes" ? "Y" : "N" ?></td>
                <td>
                    <strong>Elevation Difference</strong>
                </td>
                <td><?= $calculations->requiredElevated == "Yes" ? getMetaValue($floodQuoteMetas, "elevationDifference", 0) : "N/A" ?></td>
            </tr>
            <tr>
                <td colspan="2">
                    <strong>Secondary Home Replacement Cost</strong>
                </td>
                <td><?= ($flood_occupancy == 2) ? "Y" : "N/A" ?></td>
                <td colspan="2">
                    <strong>Personal Property Replacement Cost</strong>
                </td>
                <td><?= ($hasOpprc == 1) ? "Y" : "N" ?></td>
            </tr>
        </table>
    </div>

    <div class="row mt-1">
        <div class="border border-dark p-0 border-dark">
            <table class="table table-borderless">
                <thead>
                    <tr>
                        <th class="text-center">Coverage Type</th>
                        <th class="text-center">Amount</th>
                        <th class="text-center">Deductible</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($entityType == 0) { ?>
                        <tr>
                            <td class="text-center">Building</td>
                            <td class="text-center">
                                <?= $formatter->formatCurrency($calculations->dwellingCoverage, 'USD') ?>
                            </td>
                            <td class="text-center">
                                <?= $formatter->formatCurrency($calculations->quoteOptionDeductible, 'USD') ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-center">Contents</td>
                            <td class="text-center">
                                <?= $formatter->formatCurrency($calculations->personalPopertyCoverage, 'USD') ?>
                            </td>
                            <td class="text-center">&nbsp;</td>
                        </tr>

                        <tr>
                            <td class="text-center">Loss of Use/Rents</td>
                            <td class="text-center">
                                <?= $formatter->formatCurrency($calculations->lossOfUseCoverage, 'USD') ?>
                            </td>
                            <td class="text-center">&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="text-center">Other Structures</td>
                            <td class="text-center">
                                <?= $formatter->formatCurrency($calculations->otherStructureCoverage, 'USD') ?>
                            </td>
                            <td class="text-center">&nbsp;</td>
                        </tr>
                        <?php
                    } else {
                        if ($isRented) {
                        ?>
                            <tr>
                                <td class="text-center">Contents</td>
                                <td class="text-center">
                                    <?= $formatter->formatCurrency($calculations->personalPopertyCoverage, 'USD') ?>
                                </td>
                                <td class="text-center">&nbsp;</td>
                            </tr>

                            <tr>
                                <td class="text-center">Improvements</td>
                                <td class="text-center">
                                    <?= $formatter->formatCurrency($calculations->improvementsAndBettermentsLimit, 'USD') ?>
                                </td>
                                <td class="text-center">&nbsp;</td>
                            </tr>

                            <tr>
                                <td class="text-center">BI & Extra Expense</td>
                                <td class="text-center">
                                    <?= $formatter->formatCurrency($calculations->businessIncomeAndExtraExpenseAnnualValue, 'USD') ?>
                                </td>
                                <td class="text-center">&nbsp;</td>
                            </tr>
                        <?php } else { ?>
                            <tr>
                                <td class="text-center">Building</td>
                                <td class="text-center">
                                    <?= $formatter->formatCurrency($calculations->dwellingCoverage, 'USD') ?>
                                </td>
                                <td class="text-center">&nbsp;</td>
                            </tr>

                            <tr>
                                <td class="text-center">Contents</td>
                                <td class="text-center">
                                    <?= $formatter->formatCurrency($calculations->personalPopertyCoverage, 'USD') ?>
                                </td>
                                <td class="text-center">&nbsp;</td>
                            </tr>

                            <tr>
                                <td class="text-center">BI & Extra Expense</td>
                                <td class="text-center">
                                    <?= $formatter->formatCurrency($calculations->businessIncomeAndExtraExpenseAnnualValue, 'USD') ?>
                                </td>
                                <td class="text-center">&nbsp;</td>
                            </tr>
                    <?php
                        }
                    } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-6"></div>
        <div class="col-6">
            <div class="row text-end">
                <div class="col-6">Total Base Premium:</div>
                <div class="col-6"><?= $formatter->formatCurrency($calculations->basePremium, 'USD') ?></div>
            </div>

            <div class="row text-end">
                <div class="col-6"><?= $propertyState ?> Surplus Lines Tax:</div>
                <div class="col-6"><?= $formatter->formatCurrency($calculations->finalTax, 'USD') ?></div>
            </div>

            <div class="row text-end">
                <div class="col-6">Policy Fee:</div>
                <div class="col-6"><?= $formatter->formatCurrency($calculations->policyFee, 'USD') ?></div>
            </div>

            <div class="row text-end">
                <div class="col-6">
                    <?php if ($propertyState == "NY" || $propertyState == "PA" || $propertyState == "TX") : ?>
                        <?= $propertyState . " Stamping Fee:" ?>
                    <?php endif; ?>
                </div>
                <div class="col-6">
                    <?php if ($propertyState == "NY" || $propertyState == "PA" || $propertyState == "TX") : ?>
                        <?= $formatter->formatCurrency($calculations->stampFee, 'USD') ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="row text-end">
                <div class="col-6"><strong>Total Amount Due:</strong></div>
                <div class="col-6">
                    <strong><?= $formatter->formatCurrency($calculations->finalCost, 'USD') ?></strong>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <p>
            The undersigned attests that to the best of their knowledge,<br />
            all of the above information is accurate:
        </p>
    </div>

    <div class="row">
        <div class="col-6 border border-dark pb-5">
            <div class="signature-row">
                <div><strong>Insured Signature:</strong></div>
                <div>Date</div>
            </div>
        </div>

        <div class="col-6 border border-dark pb-5">
            <div class="signature-row">
                <div><strong>Agent Signature:</strong></div>
                <div>Date</div>
            </div>
        </div>
    </div>
</div>

<div class="content-wrapper">
    <div class="row">
        <div class="col-6">
            <div class="logo">
                <img src="<?= base_url('assets/images/IACHeaderLogo.gif'); ?>" alt="logo" width="352" height="100">
            </div>
        </div>

        <div class="col-6 text-center">
            <h3 class="mt-3">
                <?= ($isExcessPolicy) ? "Excess" : "Private" ?> Flood Insurance Application
            </h3>
        </div>
    </div>
    <div class="row">
        <p>
            By initialing and signing below, the insured understands and/or agrees with each of the following statements in regards to this policy compared to a National Flood Insurance Program (NFIP) policy
            <br />
            <br />
            _________ Insured understands that this is a Private Flood Insurance Policy in lieu of a NFIP Policy <br />
            <br />
            <br />
            _________ Insured has been offered and has declined a National Flood Insurance Policy <br />
            <br />
            <br />
            _________ Any current or future grandfathering under the NFIP is unavailable under this policy at this time <br />
            <br />
            <br />
            _________ This policy may not be cancelled except under the same conditions as a NFIP policy <br />
            <br />
            <br />
            _________ Insured has chosen the Building Coverage amount, coverage is available up to $1,000,000 <br />
            <br />
            <br />
            _________ Coverage on this policy begins on the first living floor. There is no coverage below the specified first floor<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; for either Building or Contents except as specifically named in the policy documents <br />
            <br />
            <br />
            _________ Insured agrees to Electronic delivery of all policy documents (email required) <br />
            <br />
            <br />
            <br />
            <br />
            Insured Signature __________________________________________ Date______________ <br />
            <br />
            <br />
            <br />
            Insured Signature __________________________________________ Date______________<br />
        </p>
    </div>
</div>

<?php if ($propertyState == "NJ") : ?>
    <div class="content-wrapper">
        <div class="row text-center">
            <h1>NON-ADMITTED CARRIER FORM</h1>
            <p>
                The undersigned applicant has been advised by the undersigned originating insurance producer and understands that an insurance policy written by a surplus lines insurer is not
                subject to the filing or approval requirements of the New Jersey Department of Banking and Insurance. Such a policy may contain conditions, limitations, exclusions and different
                terms that a policy issued by an insurer granted a Certificate of Authority by the New Jersey Department of Banking and Insurance.
            </p>
            <p>&nbsp;</p>
            <p>
                ___________________________________<br />
                Applicants Signature
            </p>
            <p>&nbsp;</p>
            <p>
                <?php if ($entityType == 1): ?>
                    <?= $client->business_name ?><br>
                    <?= $client->business_name2 ?>
                <?php else: ?>
                    <?= $client->first_name ?> <?= $client->last_name ?><br>
                    <?= $client->insured2_name ?>
                <?php endif; ?><br />
                Applicant’s Name (Print or Type)
            </p>
            <p>&nbsp;</p>
            <p>
                ___________________________________<br />
                Date of Applicant’s Signature
            </p>
            <p>&nbsp;</p>
            <p>
                ___________________________________<br />
                Producers Signature
            </p>
            <p>&nbsp;</p>
            <p>
                ___________________________________<br />
                Producers Name (Print or Type)
            </p>
            <p>&nbsp;</p>
            <p>
                ___________________________________<br />
                Date of Producer Signature
            </p>
            <p>&nbsp;</p>
            <p>
                ___________________________________<br />
                New Jersey Producers License Reference Number
            </p>
        </div>
    </div>
<?php endif; ?>

<?php if ($propertyState == "NY") : ?>
    <div class="content-wrapper">
        <div class="row text-center">
            <p>
                DLW Enterprises Inc<br />
                12800 Long Beach Blvd<br />
                Beach Haven Terrace, NJ 08008
            </p>
            <p>
                <strong>NOTICE OF EXCESS LINE PLACEMENT </strong>
                <br />
                <strong>Date: <?= date('m/d/Y', strtotime($floodQuote->effectivity_date)) ?></strong>
            </p>
        </div>

        <div class="row">
            <div class="col-6 border border-dark">
                <?php if ($entityType == 1): ?>
                    <?= $client->business_name ?><br>
                    <?= $client->business_name2 ?>
                <?php else: ?>
                    <?= $client->first_name ?> <?= $client->last_name ?><br>
                    <?= $client->insured2_name ?>
                <?php endif; ?><br />
                <?= $client->address ?><br />
                <?= $client->city ?>, <?= $client->state ?>&nbsp;<?= $client->zip ?>
            </div>
        </div>
        <div class="row">
            <p>
                <strong>Consistent with the requirements of the New York Insurance Law and Regulation 41 </strong>
                <strong>
                    <u>
                        <?php if ($entityType == 1): ?>
                            <?= $client->business_name ?><br>
                            <?= $client->business_name2 ?>
                        <?php else: ?>
                            <?= $client->first_name ?> <?= $client->last_name ?><br>
                            <?= $client->insured2_name ?>
                        <?php endif; ?>
                    </u>
                </strong>
                <strong> is hereby advised that all or a portion of the required coverages have been placed by <u>DLW Enterprises Inc</u> with insurers not authorized to do an insurance business in New York and which are not subject to supervision by this State. Placements with unauthorized insurers can only be made under one of the following circumstances:</strong>
            </p>

            <ol class="ms-4">
                <li><strong>A diligent effort was first made to place the required insurance with companies authorized in New York to write coverages of the kind requested; or</strong></li>
                <li><strong>NO diligent effort was required because i) the coverage qualifies as an &ldquo;Export List&rdquo; risk, or ii) the insured qualifies as an &ldquo;Exempt Commercial Purchaser.&rdquo; </strong></li>
            </ol>
            <p><strong>Policies issued by such unauthorized insurers may not be subject to all of the regulations of the Superintendent of Financial Services pertaining to policy forms. In the event of insolvency of the unauthorized insurers, losses will not be covered by any New York State security fund. </strong></p>
            <p class="text-center">
                <strong><u>TOTAL COST FORM (TAX ALLOCATED PREMIUM TRANSACTION)</u></strong><br />
                <strong>[Applies only to policies with effective dates of on or before July 20, 2011 with risks located both inside and outside New York.]</strong>
            </p>
            <p><strong>In consideration of your placing my insurance as described in the policy referenced below, I agree to pay the total cost below which includes all premiums, inspection charges(1) and a service fee that includes taxes, stamping fees, and (if indicated) a fee(1) for compensation in addition to commissions received, and other expenses(1). </strong></p>
            <p><strong>I further understand and agree that all fees, inspection charges and other expenses denoted by (1) are fully earned from the inception date of the policy and are non-refundable regardless of whether said policy is cancelled. Any policy changes which generate additional premium are subject to additional tax and stamping fee charges. The excess line tax and stamping fees denoted by (2) below are only charged against the portion of premium and other taxable charges, where applicable, (Insurer policy or inspection fees) for the portion of the insured risk located in New York.</strong></p>
            <div class="col">
                <strong>Re: Policy No. FLD000<?= $floodQuote->flood_quote_id ?></strong>
            </div>
            <div class="col">
                <strong>Insurer - Lloyd&rsquo;s Underwriters </strong>
            </div>

            <table class="table table-borderless mb-3">
                <tbody>
                    <tr>
                        <td class="p-0"><strong>Policy Premium (2)   </strong></td>
                        <td class="p-0"><strong><?= $formatter->formatCurrency($calculations->finalPremium, 'USD') ?></strong></td>
                    </tr>
                    <tr>
                        <td class="p-0"><strong><u>Insurer Imposed Charges</u></strong><strong>:</strong></td>
                        <td class="p-0">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="p-0"><strong>Policy Fees (1) (2) </strong></td>
                        <td class="p-0">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="p-0"><strong>Inspection Fees (1) (2) </strong></td>
                        <td class="p-0">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="p-0"><strong><u>Services Fee Charges:</u></strong></td>
                        <td class="p-0">&nbsp;</td>
                    </tr>
                    <tr>
                        <td class="p-0"><strong>Excess Line Tax (3.6%) (2) </strong></td>
                        <td class="p-0"><?= $formatter->formatCurrency($calculations->taxAmount, 'USD') ?></td>
                    </tr>
                    <tr>
                        <td class="p-0"><strong>Stamping Fee (2) </strong></td>
                        <td class="p-0">
                            <?php if (in_array($propertyState, ["NY", "PA", "TX"])): ?>
                                <?= $formatter->formatCurrency($calculations->stampFee, 'USD') ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="p-0"><strong>Broker Fee (1)</strong></td>
                        <td class="p-0"><?= $formatter->formatCurrency($calculations->policyFee, 'USD') ?></td>
                    </tr>
                    <tr>
                        <td class="p-0"><strong>Inspection Fee (1)</strong></td>
                        <td class="p-0">$</td>
                    </tr>
                    <tr>
                        <td class="p-0"><strong>Other Expenses (specify) (1)  ______________________________</strong></td>
                        <td class="p-0"><strong>$ ________________</strong></td>
                    </tr>
                    <tr>
                        <td class="p-0"><strong>Total Policy Cost</strong></td>
                        <td class="p-0"><strong><u><?= $formatter->formatCurrency($calculations->finalCost, 'USD') ?></u></strong></td>
                    </tr>
                </tbody>
            </table>

            <p>
                <strong>_____________________________________</strong><br />
                <strong>(Signature of Insured) </strong>
            </p>
            <p class="mb-0">
                <strong>(1) = Fully earned  (2)= Taxes and stamping fees are calculated on the portion of the risk located in N.Y. only</strong><br>
                NYSID Form:NELP/2011
            </p>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>