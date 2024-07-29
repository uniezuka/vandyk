<strong>Rating Info</strong>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Flood Zone:</label>
    <div class="col-sm-5">
        <?= floodZoneSelect('flood_zone', set_value('flood_zone')) ?>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Building Diagram #:</label>
    <div class="col-sm-3">
        <input type="text" class="form-control" placeholder="" name="diagram_num" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Foundation:</label>
    <div class="col-sm-5">
        <?= floodFoundationSelect('flood_foundation', set_value('flood_foundation')) ?>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Building Occupancy:</label>
    <div class="col-sm-5">
        <?= floodOccupancySelect('flood_occupancy', set_value('flood_occupancy')) ?>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Commercial Usage:</label>
    <div class="col-sm-5">
        <?= commercialOccupancySelect('commercial_occupancy', set_value('commercial_occupancy')) ?>
    </div>
</div>

<strong>Other Occupancy Info:</strong>

<div class="row mb-3">
    <div class="col-sm-12">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="0" checked name="otherOccupancy" />
            <label class="form-check-label">N/A</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="1" name="otherOccupancy" />
            <label class="form-check-label">Seasonal</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="2" name="otherOccupancy" />
            <label class="form-check-label">Tenants Occupy</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="3" name="otherOccupancy" />
            <label class="form-check-label">Vacant</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="4" name="otherOccupancy" />
            <label class="form-check-label">CoC - Coarse Of Construction</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="5" name="otherOccupancy" />
            <label class="form-check-label">Vacant-Renovation</label>
        </div>
    </div>
</div>

<strong>Is Basement Finished?</strong>

<div class="row mb-3">
    <div class="col-sm-12">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="1" checked name="basementFinished" />
            <label class="form-check-label">Yes</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="0" name="basementFinished" />
            <label class="form-check-label">No</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="2" name="basementFinished" />
            <label class="form-check-label">No Basement</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-start  col-sm-5 col-form-label"><strong>Is Elevation Enclosure Finished?</strong></label>
    <div class="d-flex align-items-end col-sm-7">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="1" checked name="isEnclosureFinished" />
            <label class="form-check-label">Yes</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="0" name="isEnclosureFinished" />
            <label class="form-check-label">No</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-start col-sm-5 col-form-label"><strong>Attached Garage:</strong></label>
    <div class="d-flex align-items-end col-sm-7">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="0" checked name="garageAttached" />
            <label class="form-check-label">None</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="1" name="garageAttached" />
            <label class="form-check-label">Attached</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="2" name="garageAttached" />
            <label class="form-check-label">Built-In</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="3" name="garageAttached" />
            <label class="form-check-label">Detached</label>
        </div>
    </div>
</div>

<strong>Is Building Over Water:</strong>

<div class="row mb-3">
    <div class="col-sm-12">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="0" checked name="overWater" />
            <label class="form-check-label">No</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="1" name="overWater" />
            <label class="form-check-label">Partially</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="2" name="overWater" />
            <label class="form-check-label">Entirely</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-8 col-form-label">Base Flood Elev:</label>
    <div class="col-sm-2">
        <input type="text" class="form-control" placeholder="" name="bfe" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-8 col-form-label">1st Living Floor Elevation:</label>
    <div class="col-sm-2">
        <input type="text" class="form-control" placeholder="" name="flfe" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-8 col-form-label">Elevation Difference<br />(Effective Rating Elevation):</label>
    <div class="col-sm-2">
        <input type="text" class="form-control" placeholder="" name="elevationDifference" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-8 col-form-label">Lowest Floor Elevation</label>
    <div class="col-sm-2">
        <input type="text" class="form-control" placeholder="" name="lfe" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-8 col-form-label">Next Higher Floor</label>
    <div class="col-sm-2">
        <input type="text" class="form-control" placeholder="" name="nhf" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-8 col-form-label text-end">Lowest Horizontal Structure Member</label>
    <div class="col-sm-2">
        <input type="text" class="form-control" placeholder="" name="lhsm" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-8 col-form-label">LAG</label>
    <div class="col-sm-2">
        <input type="text" class="form-control" placeholder="" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-8 col-form-label">HAG</label>
    <div class="col-sm-2">
        <input type="text" class="form-control" placeholder="" name="hag" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-8 col-form-label">Mid Level Entry Elevation:</label>
    <div class="col-sm-2">
        <input type="text" class="form-control" placeholder="" name="mle" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-8 col-form-label">Sq Ft of Enclosure</label>
    <div class="col-sm-2">
        <input type="text" class="form-control" placeholder="" name="enclosure" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Elev Cert Date</label>
    <div class="col-sm-5">
        <input type="date" class="form-control" placeholder="" name="elevCertDate" />
    </div>
</div>

<div class="row">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">&nbsp;</label>
    <div class="col-sm-8">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="hasNoElevCert">
            <label class="form-check-label">No Elev Cert</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Substantial Improvement Date</label>
    <div class="col-sm-5">
        <input type="date" class="form-control" placeholder="" name="improvementDate" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-5 col-form-label">CovA Building:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="covABuilding" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-5 col-form-label">CovC Content:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="covCContent" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-5 col-form-label">CovD Loss of Use:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="covDLoss" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-5 col-form-label">Building Replacement Cost:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="buildingReplacementCost" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-5 col-form-label">Content Replacement Cost:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="contentReplacementCost" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-5 col-form-label">RCE Ratio:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="rceRatio" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-5 col-form-label">Underinsured Rate</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="underInsuredRate" />
    </div>
    <span class="col-sm-3">(ex. 0.01)</span>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-5 col-form-label">Deductible:</label>
    <div class="col-sm-6">
        <?= deductibleSelect('deductible', set_value('deductible')) ?>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-6 col-form-label text-end">Optional Personal Property Replacement Cost:</label>
    <div class="d-flex align-items-end col-sm-6">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="1" name="has_opprc" />
            <label class="form-check-label">Yes</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="0" checked name="has_opprc" />
            <label class="form-check-label">No</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-6 col-form-label text-end">Dwelling Replacement Cost(Secondary Home):</label>
    <div class="d-flex align-items-end col-sm-6">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="1" name="has_drc" />
            <label class="form-check-label">Yes</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="0" checked name="has_drc" />
            <label class="form-check-label">No</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Binding Auth</label>
    <div class="col-sm-5">
        <?= activeBindAuthoritySelect('bindAuthority', set_value('bindAuthority')) ?>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Hiscox QuoteID:</label>
    <div class="col-sm-5">
        <input type="text" class="form-control" placeholder="" name="hiscox_id" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-3 col-form-label">Syndicate 1</label>
    <div class="col-sm-3">
        <?= activeBindAuthoritySelect('syndicate1BindAuthority', set_value('syndicate1BindAuthority')) ?>
    </div>

    <label class="d-flex justify-content-end col-sm-3 col-form-label">Risk %</label>
    <div class="col-sm-3">
        <input type="text" class="form-control" placeholder="" name="sydicate1Risk" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-3 col-form-label">Syndicate 2</label>
    <div class="col-sm-3">
        <?= activeBindAuthoritySelect('syndicate2BindAuthority', set_value('syndicate2BindAuthority')) ?>
    </div>

    <label class="d-flex justify-content-end col-sm-3 col-form-label">Risk %</label>
    <div class="col-sm-3">
        <input type="text" class="form-control" placeholder="" name="sydicate2Risk" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-3 col-form-label">Syndicate 3</label>
    <div class="col-sm-3">
        <?= activeBindAuthoritySelect('syndicate3BindAuthority', set_value('syndicate2BindAuthority')) ?>
    </div>

    <label class="d-flex justify-content-end col-sm-3 col-form-label">Risk %</label>
    <div class="col-sm-3">
        <input type="text" class="form-control" placeholder="" name="sydicate3Risk" />
    </div>
</div>