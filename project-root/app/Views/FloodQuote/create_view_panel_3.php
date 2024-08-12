<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Broker</label>
    <div class="col-sm-8">
        <?= brokerSelect('broker', set_value('broker')) ?>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Producer:</label>
    <div class="col-sm-8">
        <?= producerSelect('producer', set_value('producer')) ?>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-start col-sm-4 col-form-label"><strong>Has Loss occurred in Previous 10 Years:</strong></label>
    <div class="d-flex align-items-end col-sm-8">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="1" name="hasLossOccured" />
            <label class="form-check-label">Yes</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="0" checked name="hasLossOccured" />
            <label class="form-check-label">No</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Year of Last Loss:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="Year" name="yearLastLoss" value="<?= set_value('yearLastLoss') ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Value of Last Loss:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="Amount" name="lastLossValue" value="<?= set_value('lastLossValue') ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label"># of Losses last 10 Yrs:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="lossesIn10Years" value="<?= set_value('lossesIn10Years') ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Total Value of Losses</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="totalLossValueIn10Years" value="<?= set_value('lastLossValueIn10Years') ?>" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Sandy Loss Amount:</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="sandyLossAmount" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Has Home been elevated since last loss:</label>
    <div class="d-flex align-items-end col-sm-8">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="1" name="hasElevatedSinceLastLoss" />
            <label class="form-check-label">Yes</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" value="0" checked name="hasElevatedSinceLastLoss" />
            <label class="form-check-label">No</label>
        </div>
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Effective Date</label>
    <div class="col-sm-5">
        <input type="date" class="form-control" placeholder="" name="effectiveDate" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Expiration Date</label>
    <div class="col-sm-5">
        <input type="date" class="form-control" placeholder="" name="expirationDate" />
    </div>
</div>

<strong>Mortgagee Info</strong>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Mortgagee Name</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="mortgagee1Name" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Mortgagee Name 2</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="mortgagee1Name2" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Address</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="mortgagee1Address" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">City</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="mortgagee1City" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">State:</label>
    <div class="col-sm-3">
        <?= stateSelect('mortgagee1State', set_value('mortgagee1State')) ?>
    </div>

    <label class="d-flex justify-content-end col-sm-2 col-form-label">Zipcode</label>
    <div class="col-sm-3">
        <input type="text" class="form-control" placeholder="Zip" name="mortgagee1Zip" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Phone</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="mortgagee1Phone" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Loan #</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="mortgagee1LoanNumber" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label"><strong>2nd Mortgagee Name</strong></label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="mortgagee2Name" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">2nd Mort Name2</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="mortgagee2Name2" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Address</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="mortgagee2Address" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">City</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="mortgagee2City" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">State:</label>
    <div class="col-sm-3">
        <?= stateSelect('mortgagee2State', set_value('mortgagee2State')) ?>
    </div>

    <label class="d-flex justify-content-end col-sm-2 col-form-label">Zipcode</label>
    <div class="col-sm-3">
        <input type="text" class="form-control" placeholder="Zip" name="mortgagee2Zip" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Phone</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="mortgagee2Phone" />
    </div>
</div>

<div class="row mb-3">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">Loan #</label>
    <div class="col-sm-4">
        <input type="text" class="form-control" placeholder="" name="mortgagee2LoanNumber" />
    </div>
</div>

<strong>Notes</strong>

<div class="row mb-3">
    <div class="col-sm-12">
        <textarea id="textarea" class="form-control" rows="3" name="reason"></textarea>
    </div>
</div>

<div class="row">
    <label class="d-flex justify-content-end col-sm-4 col-form-label">&nbsp;</label>
    <div class="col-sm-8">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="isQuoteApproved" value="1">
            <label class="form-check-label">Quote Approved</label>
        </div>
    </div>
</div>

<div class="d-grid gap-2 d-md-flex justify-content-md-end">
    <button type="submit" class="btn btn-primary">Insert Quote</button>
</div>