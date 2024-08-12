<strong>Insured Information</strong>

<span class="me-3 form-text">Entity Type</span>
<div class="form-check form-check-inline">
    <input class="form-check-input entity_type" type="radio" value="0" checked name="entityType" <?= set_radio('entityType', 'Individual', true); ?> />
    <label class="form-check-label">Individual</label>
</div>
<div class="form-check form-check-inline">
    <input class="form-check-input entity_type" type="radio" value="1" name="entityType" <?= set_radio('entityType', 'Business') ?> />
    <label class="form-check-label">Business</label>
</div>

<div id="individual">
    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">First name:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" placeholder="First name" name="firstName" value="<?= set_value('firstName', $client->first_name) ?>" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Last name:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" placeholder="Last name" name="lastName" value="<?= set_value('lastName', $client->last_name) ?>" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">2nd Insured:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" placeholder="2nd Insured" name="secondInsured" value="<?= set_value('secondInsured', $client->insured2_name) ?>" />
        </div>
    </div>
</div>

<div id="business" style="display: none">
    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Business Name:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" placeholder="Business Name" name="companyName" value="<?= set_value('companyName', $client->business_name) ?>" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Business Name 2:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" placeholder="Business Name 2" name="companyName2" value="<?= set_value('companyName2', $client->business_name2) ?>" />
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Mailing Address:</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" placeholder="Mailing Address" id="address" name="address" value="<?= set_value('address', $client->address) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Mailing City:</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" placeholder="Mailing City" id="city" name="city" value="<?= set_value('city', $client->city) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Mailing State:</label>
    <div class="col-sm-3">
        <?= stateSelect('state', set_value('state', $client->state)) ?>
    </div>

    <label class="d-flex justify-content-end col-sm-1 col-form-label">Zip</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="Zip" id="zip" name="zip" value="<?= set_value('zip', $client->zip) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Insured Cel:</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" placeholder="Insured Cel" name="cellPhone" value="<?= set_value('cellPhone', $client->cell_phone) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Insured Home Phone:</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" placeholder="Insured Home Phone" name="homePhone" value="<?= set_value('homePhone', $client->home_phone) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Insured Email:</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" placeholder="Insured Email" name="email" value="<?= set_value('email', $client->email) ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Bill To</label>
    <div class="d-flex align-items-end col-sm-8">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="1" checked name="billTo" <?= set_radio('billTo', '1', true); ?> />
            <label class="form-check-label">Insured</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="2" name="billTo" <?= set_radio('billTo', '2'); ?> />
            <label class="form-check-label">1st Mortgage</label>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" name="isSameAddress" id="isSameAddress" <?= set_checkbox('isSameAddress', '1'); ?>>
            <label class="form-check-label">Check box if Mailing & Property Address are same</label>
        </div>
    </div>
</div>

<strong>Property Info</strong>

<div class="row">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">&nbsp;</label>
    <div class="col-sm-8">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="hasWaitPeriod">
            <label class="form-check-label">14 day wait</label>
        </div>
    </div>
</div>

<div class="row">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">&nbsp;</label>
    <div class="col-sm-8">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="hasClosing">
            <label class="form-check-label">Closing/Purchase</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Property Address:</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="propertyAddress" id="propertyAddress" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Property City:</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" name="propertyCity" id="propertyCity" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Property State:</label>
    <div class="col-sm-3">
        <?= stateSelect('propertyState', set_value('propertyState')) ?>
    </div>

    <label class="d-flex justify-content-end col-sm-1 col-form-label">Zip</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="Zip" name="propertyZip" id="propertyZip" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">County:</label>
    <div class="col-sm-5">
        <?= countySelect('propertyCounty', set_value('propertyCounty')) ?>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Number of Floors</label>
    <div class="col-sm-2">
        <input type="text" class="form-control" placeholder="" name="numOfFloors" />
    </div>

    <label class="d-flex justify-content-end col-sm-2 col-form-label">Square Ft</label>
    <div class="col-sm-2">
        <input type="text" class="form-control" placeholder="" name="squareFeet" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Year Built</label>
    <div class="col-sm-3">
        <input type="text" class="form-control" placeholder="" name="yearBuilt" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Construction Type</label>
    <div class="col-sm-5">
        <?= constructionSelect('construction_type', set_value('construction_type')) ?>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-center col-sm-12 col-form-label">Primary Residence</label>
    <div class="col-sm-12 d-flex justify-content-center">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="1" name="isPrimaryResidence" />
            <label class="form-check-label">Yes</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="0" checked name="isPrimaryResidence" />
            <label class="form-check-label">No</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="2" name="isPrimaryResidence" />
            <label class="form-check-label">Other</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Rented</label>
    <div class="d-flex align-items-end col-sm-8">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="1" name="isRented" />
            <label class="form-check-label">Yes</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="0" checked name="isRented" />
            <label class="form-check-label">No</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-sm-12 d-flex justify-content-center align-items-end ">
        <label class="form-label me-1">Condo</label>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" value="1" name="isCondo">
        </div>

        <label class="form-label me-1"># of Units :</label>

        <input type="text" class="form-control d-inline-block w-auto" placeholder="" name="condoUnits" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">RCBAP:</label>
    <div class="d-flex align-items-end col-sm-8">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="1" name="rcbap" />
            <label class="form-check-label">Low Rise</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="2" name="rcbap" />
            <label class="form-check-label">High Rise</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="0" checked name="rcbap" />
            <label class="form-check-label">N/A</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-sm-12 d-flex justify-content-center">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" name="hasBreakAwayWall">
            <label class="form-check-label">BreakAway Wall</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-sm-12 d-flex justify-content-center">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="isLBINorth">
            <label class="form-check-label">LBI North</label>
        </div>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="isLBISouth">
            <label class="form-check-label">LBI South</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Current Company:</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" name="currentCompany" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Current Premium:</label>
    <div class="col-sm-6">
        <input type="text" class="form-control" name="currentPremium" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Exp Date:</label>
    <div class="col-sm-6">
        <input type="date" class="form-control" name="currentExpiryDate" />
    </div>
</div>