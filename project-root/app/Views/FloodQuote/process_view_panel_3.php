<?php
$policyNumber = "";

if ($action == "cancel") {
    $actionText = "Cancel";
    $buttonText = "Submit Cancellation";

    $policyNumber = getMetaValue($floodQuoteMetas, "policyNumber");
} else if ($action == "endorse") {
    $actionText = "Endorse";
    $buttonText = "Submit Endorsement";

    $policyNumber = getMetaValue($floodQuoteMetas, "policyNumber");
}
?>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Broker</label>
    <div class="col-sm-8">
        <?= brokerSelect('broker', set_value('broker', getMetaValue($floodQuoteMetas, "broker"))) ?>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Producer:</label>
    <div class="col-sm-8">
        <?= producerSelect('producer', set_value('producer', getMetaValue($floodQuoteMetas, "producer"))) ?>
    </div>
</div>

<div class="row">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">&nbsp;</label>
    <div class="col-sm-8">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" name="isSandbarQuote" <?= set_checkbox('isSandbarQuote', '1', getMetaValue($floodQuoteMetas, "isSandbarQuote", "0") == "1"); ?>>
            <label class="form-check-label">SANDBAR QUOTE</label>
        </div>
    </div>
</div>


<div class="row mb-3">
    <label class="d-flex justify-content-start col-sm-4 col-form-label"><strong>Has Loss occurred in Previous 10 Years:</strong></label>
    <div class="d-flex align-items-end col-sm-8">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="1" name="hasLossOccurred" <?= set_radio('hasLossOccurred', '1', getMetaValue($floodQuoteMetas, "hasLossOccurred", "0") == "1"); ?> />
            <label class="form-check-label">Yes</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="0" name="hasLossOccurred" <?= set_radio('hasLossOccurred', '0', getMetaValue($floodQuoteMetas, "hasLossOccurred", "0") == "0"); ?> />
            <label class="form-check-label">No</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Year of Last Loss:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="Year" name="yearLastLoss" value="<?= set_value('yearLastLoss', getMetaValue($floodQuoteMetas, "yearLastLoss")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Value of Last Loss:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="Amount" name="lastLossValue" value="<?= set_value('lastLossValue', getMetaValue($floodQuoteMetas, "lastLossValue")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label"># of Losses last 10 Yrs:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="lossesIn10Years" value="<?= set_value('lossesIn10Years', getMetaValue($floodQuoteMetas, "lossesIn10Years")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Total Value of Losses</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="totalLossValueIn10Years" value="<?= set_value('lastLossValueIn10Years', getMetaValue($floodQuoteMetas, "lossesIn10Years")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Sandy Loss Amount:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="sandyLossAmount" value="<?= set_value('sandyLossAmount', getMetaValue($floodQuoteMetas, "sandyLossAmount")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Has Home been elevated since last loss:</label>
    <div class="d-flex align-items-end col-sm-8">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="1" name="hasElevatedSinceLastLoss" <?= set_radio('hasElevatedSinceLastLoss', '1', getMetaValue($floodQuoteMetas, "over_water", "0") == "1"); ?> />
            <label class="form-check-label">Yes</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="0" name="hasElevatedSinceLastLoss" <?= set_radio('hasElevatedSinceLastLoss', '0', getMetaValue($floodQuoteMetas, "over_water", "0") == "0"); ?> />
            <label class="form-check-label">No</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Effective Date</label>
    <div class="col-sm-5">
        <input type="date" class="form-control" placeholder="" name="effectiveDate" value="<?= set_value('effectiveDate', date('Y-m-d', strtotime($floodQuote->effectivity_date))) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Expiration Date</label>
    <div class="col-sm-5">
        <input type="date" class="form-control" placeholder="" name="expirationDate" value="<?= set_value('expirationDate', date('Y-m-d', strtotime($floodQuote->expiration_date))) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Policy #:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="policyNumber" value="<?= set_value('policyNumber', $policyNumber) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">SLA #:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="slaNumber" value="<?= set_value('slaNumber', getMetaValue($floodQuoteMetas, "slaNumber")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Previous Policy #:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="previousPolicyNumber" value="<?= set_value('previousPolicyNumber', getMetaValue($floodQuoteMetas, "previousPolicyNumber")) ?>" />
    </div>
</div>

<strong>Mortgagee Info</strong>
<input type="hidden" name="mortgagee1Id" value="<?= $mortgage1->flood_quote_mortgage_id ?>" />
<input type="hidden" name="mortgagee2Id" value="<?= $mortgage2->flood_quote_mortgage_id ?>" />

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Mortgagee Name</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="mortgagee1Name" value="<?= set_value('mortgagee1Name', $mortgage1->name) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Mortgagee Name 2</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="mortgagee1Name2" value="<?= set_value('mortgagee1Name2', $mortgage1->name2) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Address</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="mortgagee1Address" value="<?= set_value('mortgagee1Address', $mortgage1->address) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">City</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="mortgagee1City" value="<?= set_value('mortgagee1City', $mortgage1->city) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">State:</label>
    <div class="col-sm-3">
        <?= stateSelect('mortgagee1State', set_value('mortgagee1State', $mortgage1->state)) ?>
    </div>

    <label class="d-flex justify-content-end col-sm-2 col-form-label">Zipcode</label>
    <div class="col-sm-3">
        <input type="text" class="form-control" placeholder="Zip" name="mortgagee1Zip" value="<?= set_value('mortgagee1Zip', $mortgage1->zip) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Phone</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="mortgagee1Phone" value="<?= set_value('mortgagee1Phone', $mortgage1->phone) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Loan #</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="mortgagee1LoanNumber" value="<?= set_value('mortgagee1LoanNumber', $mortgage1->loan_number) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label"><strong>2nd Mortgagee Name</strong></label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="mortgagee2Name" value="<?= set_value('mortgagee2Name', $mortgage2->name) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">2nd Mort Name2</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="mortgagee2Name2" value="<?= set_value('mortgagee2Name2', $mortgage2->name2) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Address</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="mortgagee2Address" value="<?= set_value('mortgagee2Address', $mortgage2->address) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">City</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="mortgagee2City" value="<?= set_value('mortgagee2City', $mortgage2->city) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">State:</label>
    <div class="col-sm-3">
        <?= stateSelect('mortgagee2State', set_value('mortgagee2State', $mortgage2->state)) ?>
    </div>

    <label class="d-flex justify-content-end col-sm-2 col-form-label">Zipcode</label>
    <div class="col-sm-3">
        <input type="text" class="form-control" placeholder="Zip" name="mortgagee2Zip" value="<?= set_value('mortgagee2Zip', $mortgage2->zip) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Phone</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="mortgagee2Phone" value="<?= set_value('mortgagee2Phone', $mortgage2->phone) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Loan #</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="mortgagee2LoanNumber" value="<?= set_value('mortgagee2LoanNumber', $mortgage2->loan_number) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Hiscox Dwell Limit Override</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="hiscoxDwellLimitOverride" value="<?= set_value('hiscoxDwellLimitOverride', getMetaValue($floodQuoteMetas, "hiscoxDwellLimitOverride")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Hiscox Content Limit Override</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="hiscoxContentLimitOverride" value="<?= set_value('hiscoxContentLimitOverride', getMetaValue($floodQuoteMetas, "hiscoxContentLimitOverride")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Hiscox LossUse Limit Override</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="hiscoxLossUseLimitOverride" value="<?= set_value('hiscoxLossUseLimitOverride', getMetaValue($floodQuoteMetas, "hiscoxLossUseLimitOverride")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Hiscox Other Limit Override</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="hiscoxOtherLimitOverride" value="<?= set_value('hiscoxOtherLimitOverride', getMetaValue($floodQuoteMetas, "hiscoxOtherLimitOverride")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label"><?= $actionText ?> Premium:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="cancelPremium" value="<?= set_value('cancelPremium', getMetaValue($floodQuoteMetas, "cancelPremium", 0)) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label"><?= $actionText ?> Tax:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="cancelTax" value="<?= set_value('cancelTax', getMetaValue($floodQuoteMetas, "cancelTax", 0)) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Prorated <?= $actionText ?> Total Due:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="proratedDue" value="<?= set_value('proratedDue', getMetaValue($floodQuoteMetas, "proratedDue", 0)) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label"><?= $actionText ?> Effective Date:</label>
    <div class="col-sm-4">
        <input type="date" class="form-control" required placeholder="" name="endorseDate" value="<?= set_value('endorseDate', getMetaValue($floodQuoteMetas, "endorseDate")) ?>" />
    </div>
</div>

<strong>Notes</strong>

<div class="row mb-3">
    <div class="col-sm-12">
        <textarea id="textarea" class="form-control" rows="3" name="reason" value="<?= set_value('reason', getMetaValue($floodQuoteMetas, "reason")) ?>"></textarea>
    </div>
</div>

<div class="d-grid gap-2 d-md-flex justify-content-md-end">
    <button type="submit" class="btn btn-primary"><?= $buttonText ?></button>
</div>