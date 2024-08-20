<strong>Insured Information</strong>

<span class="me-3 form-text">Entity Type</span>
<div class="form-check form-check-inline">
    <input class="form-check-input entity_type" type="radio" value="0" name="entityType" <?= set_radio('entityType', 'Individual', ($floodQuote->entity_type == "0")); ?> />
    <label class="form-check-label">Individual</label>
</div>
<div class="form-check form-check-inline">
    <input class="form-check-input entity_type" type="radio" value="1" name="entityType" <?= set_radio('entityType', 'Business', ($floodQuote->entity_type == "1")); ?> />
    <label class="form-check-label">Business</label>
</div>

<div id="individual">
    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">First name:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" placeholder="First name" name="firstName" value="<?= set_value('firstName', $floodQuote->first_name) ?>" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Last name:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" placeholder="Last name" name="lastName" value="<?= set_value('lastName', $floodQuote->last_name) ?>" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">2nd Insured:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" placeholder="2nd Insured" name="secondInsured" value="<?= set_value('secondInsured', $floodQuote->insured_name_2) ?>" />
        </div>
    </div>
</div>

<div id="business" style="display: none">
    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Business Name:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" placeholder="Business Name" name="companyName" value="<?= set_value('companyName', $floodQuote->company_name) ?>" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Business Name 2:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" placeholder="Business Name 2" name="companyName2" value="<?= set_value('companyName2', $floodQuote->company_name_2) ?>" />
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Mailing Address:</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" placeholder="Mailing Address" id="address" name="address" value="<?= set_value('address', $floodQuote->address) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Mailing City:</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" placeholder="Mailing City" id="city" name="city" value="<?= set_value('city', $floodQuote->city) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Mailing State:</label>
    <div class="col-sm-3">
        <?= stateSelect('state', set_value('state', $floodQuote->state)) ?>
    </div>

    <label class="d-flex justify-content-end col-sm-1 col-form-label">Zip</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="Zip" id="zip" name="zip" value="<?= set_value('zip', $floodQuote->zip) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Insured Cel:</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" placeholder="Insured Cel" name="cellPhone" value="<?= set_value('cellPhone', $floodQuote->cell_phone) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Insured Home Phone:</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" placeholder="Insured Home Phone" name="homePhone" value="<?= set_value('homePhone', $floodQuote->home_phone) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Insured Email:</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" placeholder="Insured Email" name="email" value="<?= set_value('email', $floodQuote->email) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Bill To</label>
    <div class="d-flex align-items-end col-sm-8">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="1" name="billTo" <?= set_radio('billTo', '1', ($floodQuote->bill_to == "1")); ?> />
            <label class="form-check-label">Insured</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="2" name="billTo" <?= set_radio('billTo', '2', ($floodQuote->bill_to == "2")); ?> />
            <label class="form-check-label">1st Mortgage</label>
        </div>
    </div>
</div>

<strong>Property Info</strong>

<div class="row">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">&nbsp;</label>
    <div class="col-sm-8">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" name="hasWaitPeriod" <?= set_checkbox('hasWaitPeriod', '1', getMetaValue($floodQuoteMetas, "hasWaitPeriod", "0") == "1"); ?>>
            <label class="form-check-label">14 day wait</label>
        </div>
    </div>
</div>

<div class="row">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">&nbsp;</label>
    <div class="col-sm-8">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" name="hasClosing" <?= set_checkbox('hasClosing', '1', getMetaValue($floodQuoteMetas, "hasClosing", "0") == "1"); ?>>
            <label class="form-check-label">Closing/Purchase</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Property Address:</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="propertyAddress" id="propertyAddress" value="<?= set_value('propertyAddress', getMetaValue($floodQuoteMetas, "propertyAddress")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Property City:</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="propertyCity" id="propertyCity" value="<?= set_value('propertyCity', getMetaValue($floodQuoteMetas, "propertyCity")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Property State:</label>
    <div class="col-sm-3">
        <?= stateSelect('propertyState', set_value('propertyState', getMetaValue($floodQuoteMetas, "propertyState"))) ?>
    </div>

    <label class="d-flex justify-content-end col-sm-1 col-form-label">Zip</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="Zip" name="propertyZip" id="propertyZip" value="<?= set_value('propertyZip', getMetaValue($floodQuoteMetas, "propertyZip")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">County:</label>
    <div class="col-sm-5">
        <?= countySelect('propertyCounty', set_value('propertyCounty', getMetaValue($floodQuoteMetas, "propertyCounty"))) ?>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Number of Floors</label>
    <div class="col-sm-2">
        <input type="text" class="form-control" placeholder="" name="numOfFloors" value="<?= set_value('numOfFloors', getMetaValue($floodQuoteMetas, "numOfFloors")) ?>" />
    </div>

    <label class="d-flex justify-content-end col-sm-2 col-form-label">Square Ft</label>
    <div class="col-sm-2">
        <input type="text" class="form-control" placeholder="" name="squareFeet" value="<?= set_value('squareFeet', getMetaValue($floodQuoteMetas, "squareFeet")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Year Built</label>
    <div class="col-sm-3">
        <input type="text" class="form-control" placeholder="" name="yearBuilt" value="<?= set_value('yearBuilt', getMetaValue($floodQuoteMetas, "yearBuilt")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Construction Type</label>
    <div class="col-sm-5">
        <?= constructionSelect('construction_type', set_value('construction_type', getMetaValue($floodQuoteMetas, "construction_type"))) ?>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-center col-sm-12 col-form-label">Primary Residence</label>
    <div class="col-sm-12 d-flex justify-content-center">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="1" name="isPrimaryResidence" <?= set_radio('isPrimaryResidence', '1', getMetaValue($floodQuoteMetas, "isPrimaryResidence", "0") == "1"); ?> />
            <label class="form-check-label">Yes</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="0" name="isPrimaryResidence" <?= set_radio('isPrimaryResidence', '0', getMetaValue($floodQuoteMetas, "isPrimaryResidence", "0") == "0"); ?> />
            <label class="form-check-label">No</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="2" name="isPrimaryResidence" <?= set_radio('isPrimaryResidence', '2', getMetaValue($floodQuoteMetas, "isPrimaryResidence", "0") == "2"); ?> />
            <label class="form-check-label">Other</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Rented</label>
    <div class="d-flex align-items-end col-sm-8">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="1" name="isRented" <?= set_radio('isRented', '1', getMetaValue($floodQuoteMetas, "isRented", "0") == "1"); ?> />
            <label class="form-check-label">Yes</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="0" name="isRented" <?= set_radio('isRented', '0', getMetaValue($floodQuoteMetas, "isRented", "0") == "0"); ?> />
            <label class="form-check-label">No</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-sm-12 d-flex justify-content-center align-items-end ">
        <label class="form-label me-1">Condo</label>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" value="1" name="isCondo" <?= set_checkbox('isCondo', '1', getMetaValue($floodQuoteMetas, "isCondo", "0") == "1"); ?> />
        </div>

        <label class="form-label me-1"># of Units :</label>

        <input type="text" class="form-control d-inline-block w-auto" placeholder="" name="condoUnits" value="<?= set_value('condoUnits', getMetaValue($floodQuoteMetas, "condoUnits")) ?>" />
    </div>
</div>

<div class=" row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">RCBAP:</label>
    <div class="d-flex align-items-end col-sm-8">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="1" name="rcbap" <?= set_radio('rcbap', '1', getMetaValue($floodQuoteMetas, "rcbap", "0") == "1"); ?> />
            <label class="form-check-label">Low Rise</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="2" name="rcbap" <?= set_radio('rcbap', '2', getMetaValue($floodQuoteMetas, "rcbap", "0") == "2"); ?> />
            <label class="form-check-label">High Rise</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="0" name="rcbap" <?= set_radio('rcbap', '0', getMetaValue($floodQuoteMetas, "rcbap", "0") == "0"); ?> />
            <label class="form-check-label">N/A</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-sm-12 d-flex justify-content-center">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" name="hasBreakAwayWall" <?= set_checkbox('hasBreakAwayWall', '1', getMetaValue($floodQuoteMetas, "hasBreakAwayWall", "0") == "1"); ?>>
            <label class="form-check-label">BreakAway Wall</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-sm-12 d-flex justify-content-center">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" value="1" name="isLBINorth" <?= set_checkbox('isLBINorth', '1', getMetaValue($floodQuoteMetas, "isLBINorth", "0") == "1"); ?> />
            <label class="form-check-label">LBI North</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" value="1" name="isLBISouth" <?= set_checkbox('isLBISouth', '1', getMetaValue($floodQuoteMetas, "isLBISouth", "0") == "1"); ?> />
            <label class="form-check-label">LBI South</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Current Company:</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" name="currentCompanyName" value="<?= set_value('currentCompanyName', getMetaValue($floodQuoteMetas, "currentCompanyName")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Current Premium:</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" name="currentPremium" value="<?= set_value('currentPremium', getMetaValue($floodQuoteMetas, "currentPremium")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Exp Date:</label>
    <div class="col-sm-6">
        <input type="date" class="form-control" name="currentExpiryDate" value="<?= set_value('currentExpiryDate', getMetaValue($floodQuoteMetas, "currentExpiryDate")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <div class="col-sm-12 d-flex justify-content-center">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" name="isExcessPolicy" <?= set_checkbox('isExcessPolicy', '1', getMetaValue($floodQuoteMetas, "isExcessPolicy", "0") == "1"); ?>>
            <label class="form-check-label">EXCESS Policy</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Underlying Company:</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" name="underlyingCompanyName" value="<?= set_value('underlyingCompanyName', getMetaValue($floodQuoteMetas, "underlyingCompanyName")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Underlying PolicyNumber:</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" name="underlyingPolicyNumber" value="<?= set_value('underlyingPolicyNumber', getMetaValue($floodQuoteMetas, "underlyingPolicyNumber")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Excess Building Limit:</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" name="excessBuildingLimit" value="<?= set_value('excessBuildingLimit', getMetaValue($floodQuoteMetas, "excessBuildingLimit", 0)) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Excess Content Limit:</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" name="excessContentLimit" value="<?= set_value('excessContentLimit', getMetaValue($floodQuoteMetas, "excessContentLimit", 0)) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Underlying Build Limit:</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" name="underlyingBuildLimit" value="<?= set_value('underlyingBuildLimit', getMetaValue($floodQuoteMetas, "underlyingBuildLimit", 0)) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Underlying Content Limit:</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" name="underlyingContentLimit" value="<?= set_value('underlyingContentLimit', getMetaValue($floodQuoteMetas, "underlyingContentLimit", 0)) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Underlying Build Deductible:</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" name="underlyingBuildDeductible" value="<?= set_value('underlyingBuildDeductible', getMetaValue($floodQuoteMetas, "underlyingBuildDeductible", 0)) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Underlying Build Deductible:</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" name="underlyingBuildDeductible" value="<?= set_value('underlyingBuildDeductible', getMetaValue($floodQuoteMetas, "underlyingBuildDeductible", 0)) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Underlying Content Deductible:</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" name="underlyingContentDeductible" value="<?= set_value('underlyingContentDeductible', getMetaValue($floodQuoteMetas, "underlyingContentDeductible", 0)) ?>" />
    </div>
</div>

<?php if ((getMetaValue($floodQuoteMetas, "policyType")) != "NEW") { ?>
    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Previous Quote ID:</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" name="mainPolicyID" value="<?= set_value('mainPolicyID', getMetaValue($floodQuoteMetas, "mainPolicyID")) ?>" />
        </div>
    </div>
<?php } ?>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Latitude:</label>
    <div class="col-sm-6"><?= getMetaValue($floodQuoteMetas, "latitude", 0) ?></div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Longitude:</label>
    <div class="col-sm-6"><?= getMetaValue($floodQuoteMetas, "longitude", 0) ?></div>
</div>

<strong>Storm Surge Detail</strong>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Cat 1:</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" name="category1WaterDepthFeet" value="<?= set_value('category1WaterDepthFeet', getMetaValue($floodQuoteMetas, "category1WaterDepthFeet")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Cat 2:</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" name="category2WaterDepthFeet" value="<?= set_value('category2WaterDepthFeet', getMetaValue($floodQuoteMetas, "category2WaterDepthFeet")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Cat 3:</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" name="category3WaterDepthFeet" value="<?= set_value('category3WaterDepthFeet', getMetaValue($floodQuoteMetas, "category3WaterDepthFeet")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Cat 4:</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" name="category4WaterDepthFeet" value="<?= set_value('category4WaterDepthFeet', getMetaValue($floodQuoteMetas, "category4WaterDepthFeet")) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Cat 5:</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" name="category5WaterDepthFeet" value="<?= set_value('category5WaterDepthFeet', getMetaValue($floodQuoteMetas, "category5WaterDepthFeet")) ?>" />
    </div>
    <div class="col-sm-12 d-flex justify-content-center"><a href="#">Refresh Surge Data</a></div>
</div>