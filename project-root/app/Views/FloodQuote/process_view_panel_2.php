<strong>Rating Info</strong>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Flood Zone:</label>
    <div class="col-sm-5">
        <?= floodZoneSelect('flood_zone', set_value('flood_zone', getMetaValue($floodQuoteMetas, "flood_zone"))) ?>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Building Diagram #:</label>
    <div class="col-sm-3">
        <input type="text" class="form-control" placeholder="" name="diagramNumber" value="<?= set_value('diagramNumber', getMetaValue($floodQuoteMetas, "diagramNumber")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Foundation:</label>
    <div class="col-sm-5">
        <?= floodFoundationSelect('flood_foundation', set_value('flood_foundation', getMetaValue($floodQuoteMetas, "flood_foundation"))) ?>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Building Occupancy:</label>
    <div class="col-sm-5">
        <?= floodOccupancySelect('flood_occupancy', set_value('flood_occupancy', getMetaValue($floodQuoteMetas, "flood_occupancy"))) ?>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Commercial Usage:</label>
    <div class="col-sm-5">
        <?= commercialOccupancySelect('commercial_occupancy', set_value('commercial_occupancy', getMetaValue($floodQuoteMetas, "commercial_occupancy"))) ?>
    </div>
</div>

<strong>Other Occupancy Info:</strong>

<div class="row mb-3">
    <div class="col-sm-12">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="0" name="other_occupancy" <?= set_radio('other_occupancy', '0', getMetaValue($floodQuoteMetas, "other_occupancy", "0") == "0"); ?> />
            <label class="form-check-label">N/A</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="1" name="otherOccupancy" <?= set_radio('other_occupancy', '1', getMetaValue($floodQuoteMetas, "other_occupancy", "0") == "1"); ?> />
            <label class="form-check-label">Seasonal</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="2" name="otherOccupancy" <?= set_radio('other_occupancy', '2', getMetaValue($floodQuoteMetas, "other_occupancy", "0") == "2"); ?> />
            <label class="form-check-label">Tenants Occupy</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="3" name="otherOccupancy" <?= set_radio('other_occupancy', '3', getMetaValue($floodQuoteMetas, "other_occupancy", "0") == "3"); ?> />
            <label class="form-check-label">Vacant</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="4" name="otherOccupancy" <?= set_radio('other_occupancy', '4', getMetaValue($floodQuoteMetas, "other_occupancy", "0") == "4"); ?> />
            <label class="form-check-label">CoC - Coarse Of Construction</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="5" name="otherOccupancy" <?= set_radio('other_occupancy', '5', getMetaValue($floodQuoteMetas, "other_occupancy", "0") == "5"); ?> />
            <label class="form-check-label">Vacant-Renovation</label>
        </div>
    </div>
</div>

<strong>Is Basement Finished?</strong>

<div class="row mb-3">
    <div class="col-sm-12">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="1" name="basement_finished" <?= set_radio('basement_finished', '1', getMetaValue($floodQuoteMetas, "basement_finished", "0") == "1"); ?> />
            <label class="form-check-label">Yes</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="0" name="basement_finished" <?= set_radio('basement_finished', '0', getMetaValue($floodQuoteMetas, "basement_finished", "0") == "0"); ?> />
            <label class="form-check-label">No</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="2" name="basement_finished" <?= set_radio('basement_finished', '2', getMetaValue($floodQuoteMetas, "basement_finished", "0") == "2"); ?> />
            <label class="form-check-label">No Basement</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-start  col-sm-5 col-form-label"><strong>Is Elevation Enclosure Finished?</strong></label>
    <div class="d-flex align-items-end col-sm-7">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="1" name="isEnclosureFinished" <?= set_radio('isEnclosureFinished', '1', getMetaValue($floodQuoteMetas, "isEnclosureFinished", "0") == "1"); ?> />
            <label class="form-check-label">Yes</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="0" name="isEnclosureFinished" <?= set_radio('isEnclosureFinished', '0', getMetaValue($floodQuoteMetas, "isEnclosureFinished", "0") == "0"); ?> />
            <label class="form-check-label">No</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-start col-sm-5 col-form-label"><strong>Attached Garage:</strong></label>
    <div class="d-flex align-items-end col-sm-7">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="0" name="garage_attached" <?= set_radio('garage_attached', '0', getMetaValue($floodQuoteMetas, "garage_attached", "0") == "0"); ?> />
            <label class="form-check-label">None</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="1" name="garage_attached" <?= set_radio('garage_attached', '1', getMetaValue($floodQuoteMetas, "garage_attached", "0") == "1"); ?> />
            <label class="form-check-label">Attached</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="2" name="garage_attached" <?= set_radio('garage_attached', '2', getMetaValue($floodQuoteMetas, "garage_attached", "0") == "2"); ?> />
            <label class="form-check-label">Built-In</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="3" name="garage_attached" <?= set_radio('garage_attached', '3', getMetaValue($floodQuoteMetas, "garage_attached", "0") == "3"); ?> />
            <label class="form-check-label">Detached</label>
        </div>
    </div>
</div>

<strong>Is Building Over Water:</strong>

<div class="row mb-3">
    <div class="col-sm-12">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="0" name="over_water" <?= set_radio('over_water', '0', getMetaValue($floodQuoteMetas, "over_water", "0") == "0"); ?> />
            <label class="form-check-label">No</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="1" name="over_water" <?= set_radio('over_water', '1', getMetaValue($floodQuoteMetas, "over_water", "1") == "1"); ?> />
            <label class="form-check-label">Partially</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="2" name="over_water" <?= set_radio('over_water', '2', getMetaValue($floodQuoteMetas, "over_water", "2") == "2"); ?> />
            <label class="form-check-label">Entirely</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-8 col-form-label">Base Flood Elev:</label>
    <div class="col-sm-2">
        <input type="text" class="form-control" placeholder="" name="bfe" value="<?= set_value('bfe', getMetaValue($floodQuoteMetas, "bfe")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-8 col-form-label">1st Living Floor Elevation:</label>
    <div class="col-sm-2">
        <input type="text" class="form-control" placeholder="" name="flfe" value="<?= set_value('flfe', getMetaValue($floodQuoteMetas, "flfe")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-8 col-form-label">Elevation Difference<br />(Effective Rating Elevation):</label>
    <div class="col-sm-2">
        <input type="text" class="form-control" placeholder="" name="elevationDifference" value="<?= set_value('elevationDifference', getMetaValue($floodQuoteMetas, "elevationDifference")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-8 col-form-label">Lowest Floor Elevation</label>
    <div class="col-sm-2">
        <input type="text" class="form-control" placeholder="" name="lfe" value="<?= set_value('lfe', getMetaValue($floodQuoteMetas, "lfe")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-8 col-form-label">Next Higher Floor</label>
    <div class="col-sm-2">
        <input type="text" class="form-control" placeholder="" name="nhf" value="<?= set_value('nhf', getMetaValue($floodQuoteMetas, "nhf")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-8 col-form-label text-end">Lowest Horizontal Structure Member</label>
    <div class="col-sm-2">
        <input type="text" class="form-control" placeholder="" name="lhsm" value="<?= set_value('lhsm', getMetaValue($floodQuoteMetas, "lhsm")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-8 col-form-label">LAG</label>
    <div class="col-sm-2">
        <input type="text" class="form-control" placeholder="" name="lag" value="<?= set_value('lag', getMetaValue($floodQuoteMetas, "lag")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-8 col-form-label">HAG</label>
    <div class="col-sm-2">
        <input type="text" class="form-control" placeholder="" name="hag" value="<?= set_value('hag', getMetaValue($floodQuoteMetas, "hag")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-8 col-form-label">Mid Level Entry Elevation:</label>
    <div class="col-sm-2">
        <input type="text" class="form-control" placeholder="" name="mle" value="<?= set_value('mle', getMetaValue($floodQuoteMetas, "mle")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-8 col-form-label">Sq Ft of Enclosure</label>
    <div class="col-sm-2">
        <input type="text" class="form-control" placeholder="" name="enclosure" value="<?= set_value('enclosure', getMetaValue($floodQuoteMetas, "enclosure")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Elev Cert Date</label>
    <div class="col-sm-5">
        <input type="date" class="form-control" placeholder="" name="elevCertDate" value="<?= set_value('elevCertDate', getMetaValue($floodQuoteMetas, "elevCertDate")) ?>" />
    </div>
</div>

<div class="row">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">&nbsp;</label>
    <div class="col-sm-8">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" name="hasNoElevCert" <?= set_checkbox('hasNoElevCert', '1', getMetaValue($floodQuoteMetas, "hasNoElevCert", "0") == "1"); ?>>
            <label class="form-check-label">No Elev Cert</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Substantial Improvement Date</label>
    <div class="col-sm-5">
        <input type="date" class="form-control" placeholder="" name="improvementDate" value="<?= set_value('improvementDate', getMetaValue($floodQuoteMetas, "improvementDate")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-5 col-form-label">CovA Building:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="covABuilding" value="<?= set_value('covABuilding', getMetaValue($floodQuoteMetas, "covABuilding")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-5 col-form-label">CovC Content:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="covCContent" value="<?= set_value('covCContent', getMetaValue($floodQuoteMetas, "covCContent")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-5 col-form-label">CovD Loss of Use:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="covDLoss" value="<?= set_value('covDLoss', getMetaValue($floodQuoteMetas, "covDLoss")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-5 col-form-label">Building Replacement Cost:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="buildingReplacementCost" value="<?= set_value('buildingReplacementCost', getMetaValue($floodQuoteMetas, "buildingReplacementCost")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-5 col-form-label">Content Replacement Cost:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="contentReplacementCost" value="<?= set_value('contentReplacementCost', getMetaValue($floodQuoteMetas, "contentReplacementCost")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-5 col-form-label">RCE Ratio:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="rceRatio" value="<?= set_value('rceRatio', getMetaValue($floodQuoteMetas, "rceRatio")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-5 col-form-label">Underinsured Rate</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="underInsuredRate" value="<?= set_value('underInsuredRate', getMetaValue($floodQuoteMetas, "underInsuredRate")) ?>" />
    </div>
    <span class="col-sm-3">(ex. 0.01)</span>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-5 col-form-label">Deductible:</label>
    <div class="col-sm-6">
        <?= deductibleSelect('deductible', set_value('deductible', getMetaValue($floodQuoteMetas, "deductible"))) ?>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-6 col-form-label text-end">Optional Personal Property Replacement Cost:</label>
    <div class="d-flex align-items-end col-sm-6">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="1" name="hasOpprc" <?= set_checkbox('hasOpprc', '1', getMetaValue($floodQuoteMetas, "hasOpprc", "0") == "1"); ?> />
            <label class="form-check-label">Yes</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="0" name="hasOpprc" <?= set_checkbox('hasOpprc', '0', getMetaValue($floodQuoteMetas, "hasOpprc", "0") == "0"); ?> />
            <label class="form-check-label">No</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-6 col-form-label text-end">Dwelling Replacement Cost(Secondary Home):</label>
    <div class="d-flex align-items-end col-sm-6">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="1" name="hasDrc" <?= set_checkbox('hasDrc', '1', getMetaValue($floodQuoteMetas, "hasDrc", "0") == "1"); ?> />
            <label class="form-check-label">Yes</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="0" checked name="hasDrc" <?= set_checkbox('hasDrc', '0', getMetaValue($floodQuoteMetas, "hasDrc", "0") == "0"); ?> />
            <label class="form-check-label">No</label>
        </div>
    </div>
</div>

<h5>Premium Adjustments</h5>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-5 col-form-label">Base Rate % Adjustment:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="baseRateAdjustment" value="<?= set_value('baseRateAdjustment', getMetaValue($floodQuoteMetas, "baseRateAdjustment", 0)) ?>" />
    </div>
    <span class="col-sm-3">(ex. 0.01)</span>
</div>

<div class="row">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">&nbsp;</label>
    <div class="col-sm-8">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" name="has10PercentAdjustment" <?= set_checkbox('has10PercentAdjustment', '1', getMetaValue($floodQuoteMetas, "has10PercentAdjustment", "0") == "1"); ?>>
            <label class="form-check-label">10% Adjust of Premium</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-5 col-form-label">Additional Premium %:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="additionalPremium" value="<?= set_value('additionalPremium', getMetaValue($floodQuoteMetas, "additionalPremium", 0)) ?>" />
    </div>
    <span class="col-sm-3">(Use Whole # ex. 3)</span>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-5 col-form-label">Additional Renewal Premium:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="renewalAdditionalPremium" value="<?= set_value('renewalAdditionalPremium', getMetaValue($floodQuoteMetas, "renewalAdditionalPremium", 0)) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-5 col-form-label">Renewal Prem Increase %:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="renewalPremiumIncrease" value="<?= set_value('renewalPremiumIncrease', getMetaValue($floodQuoteMetas, "renewalPremiumIncrease", 0)) ?>" />
    </div>
    <span class="col-sm-3">(Use Whole # ex. 3)</span>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Binding Auth</label>
    <div class="col-sm-5">
        <?= activeBindAuthoritySelect('bind_authority', set_value('bind_authority', getMetaValue($floodQuoteMetas, "bind_authority"))) ?>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-3 col-form-label">Syndicate 1</label>
    <div class="col-sm-3">
        <?= activeBindAuthoritySelect('syndicate1_bind_authority', set_value('syndicate1_bind_authority', getMetaValue($floodQuoteMetas, "syndicate1_bind_authority"))) ?>
    </div>

    <label class="d-flex justify-content-end col-sm-3 col-form-label">Risk %</label>
    <div class="col-sm-3">
        <input type="text" class="form-control" placeholder="" name="sydicate1Risk" value="<?= set_value('sydicate1Risk', getMetaValue($floodQuoteMetas, "sydicate1Risk")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-3 col-form-label">Syndicate 2</label>
    <div class="col-sm-3">
        <?= activeBindAuthoritySelect('syndicate2_bind_authority', set_value('syndicate2_bind_authority', getMetaValue($floodQuoteMetas, "syndicate2_bind_authority"))) ?>
    </div>

    <label class="d-flex justify-content-end col-sm-3 col-form-label">Risk %</label>
    <div class="col-sm-3">
        <input type="text" class="form-control" placeholder="" name="sydicate2Risk" value="<?= set_value('sydicate2Risk', getMetaValue($floodQuoteMetas, "sydicate2Risk")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-3 col-form-label">Syndicate 3</label>
    <div class="col-sm-3">
        <?= activeBindAuthoritySelect('syndicate3_bind_authority', set_value('syndicate3_bind_authority', getMetaValue($floodQuoteMetas, "syndicate3_bind_authority"))) ?>
    </div>

    <label class="d-flex justify-content-end col-sm-3 col-form-label">Risk %</label>
    <div class="col-sm-3">
        <input type="text" class="form-control" placeholder="" name="sydicate3Risk" value="<?= set_value('sydicate3Risk', getMetaValue($floodQuoteMetas, "sydicate3Risk")) ?>" />
    </div>
</div>

<?php if (strpos($bindAuthorityText, "250") !== false) { ?>
    <h5>Prev Policy Hiscox Values</h5>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Prem Override:</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" name="prevHiscoxPremiumOverride" value="<?= getMetaValue($floodQuoteMetas, "hiscoxPremiumOverride") ?>" readonly />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Previous Hiscox ID:</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" name="prevHiscoxBoundID" value="<?= getMetaValue($floodQuoteMetas, "hiscoxID") ?>" readonly />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Previous Rate:</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" name="prevhiscoxQuotedRate" value="<?= getMetaValue($floodQuoteMetas, "hiscoxQuotedRate") ?>" readonly />
        </div>
    </div>
<?php } else { ?>
    <input type="hidden" class="form-control" name="prevHiscoxPremiumOverride" value="<?= getMetaValue($floodQuoteMetas, "hiscoxPremiumOverride") ?>" />
    <input type="hidden" class="form-control" name="prevHiscoxBoundID" value="<?= getMetaValue($floodQuoteMetas, "hiscoxID") ?>" />
    <input type="hidden" class="form-control" name="prevHiscoxQuotedRate" value="<?= getMetaValue($floodQuoteMetas, "hiscoxQuotedRate") ?>" />
<?php } ?>