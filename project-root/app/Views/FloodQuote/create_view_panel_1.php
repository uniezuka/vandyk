<strong>Insured Information</strong>

<form>
    <span class="me-3 form-text">Entity Type</span>
    <div class="form-check form-check-inline">
        <input class="form-check-input insured_type" type="radio" value="Individual" checked name="inlineRadioOptions" />
        <label class="form-check-label">Individual</label>
    </div>
    <div class="form-check form-check-inline">
        <input class="form-check-input insured_type" type="radio" value="Business" name="inlineRadioOptions" />
        <label class="form-check-label">Business</label>
    </div>

    <div id="individual">
        <div class="row mb-3">
            <label class="d-flex justify-content-end col-sm-4 col-form-label">First name:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" placeholder="First name" />
            </div>
        </div>

        <div class="row mb-3">
            <label class="d-flex justify-content-end col-sm-4 col-form-label">Last name:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" placeholder="Last name" />
            </div>
        </div>

        <div class="row mb-3">
            <label class="d-flex justify-content-end col-sm-4 col-form-label">2nd Insured:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" placeholder="2nd Insured" />
            </div>
        </div>
    </div>

    <div id="business" style="display: none">
        <div class="row mb-3">
            <label class="d-flex justify-content-end col-sm-4 col-form-label">Business Name:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" placeholder="Business Name" />
            </div>
        </div>

        <div class="row mb-3">
            <label class="d-flex justify-content-end col-sm-4 col-form-label">Business Name 2:</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" placeholder="Business Name 2" />
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Mailing Address:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" placeholder="Mailing Address" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Mailing City:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" placeholder="Mailing City" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Mailing State:</label>
        <div class="col-sm-3">
            <select class="form-select" aria-label="Default select example">
                <option value="">State</option>
                <option value="AK">AK</option>
                <option value="AL">AL</option>
                <option value="AR">AR</option>
                <option value="AZ">AZ</option>
                <option value="CA">CA</option>
                <option value="CO">CO</option>
                <option value="CT">CT</option>
                <option value="DC">DC</option>
                <option value="DE">DE</option>
                <option value="FL">FL</option>
                <option value="GA">GA</option>
                <option value="HI">HI</option>
                <option value="IA">IA</option>
                <option value="ID">ID</option>
                <option value="IL">IL</option>
                <option value="IN">IN</option>
                <option value="KS">KS</option>
                <option value="KY">KY</option>
                <option value="LA">LA</option>
                <option value="MA">MA</option>
                <option value="MD" selected="selected">MD</option>
                <option value="ME">ME</option>
                <option value="MI">MI</option>
                <option value="MN">MN</option>
                <option value="MO">MO</option>
                <option value="MS">MS</option>
                <option value="MT">MT</option>
                <option value="NC">NC</option>
                <option value="ND">ND</option>
                <option value="NE">NE</option>
                <option value="NH">NH</option>
                <option value="NJ">NJ</option>
                <option value="NM">NM</option>
                <option value="NV">NV</option>
                <option value="NY">NY</option>
                <option value="OH">OH</option>
                <option value="OK">OK</option>
                <option value="OR">OR</option>
                <option value="PA">PA</option>
                <option value="PR">PR</option>
                <option value="RI">RI</option>
                <option value="SC">SC</option>
                <option value="SD">SD</option>
                <option value="TN">TN</option>
                <option value="TX">TX</option>
                <option value="UT">UT</option>
                <option value="VA">VA</option>
                <option value="VT">VT</option>
                <option value="WA">WA</option>
                <option value="WI">WI</option>
                <option value="WV">WV</option>
                <option value="WY">WY</option>
            </select>
        </div>

        <label class="d-flex justify-content-end col-sm-1 col-form-label">Zip</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" placeholder="Zip" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Insured Cel:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" placeholder="Insured Cel" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Insured Home Phone:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" placeholder="Insured Home Phone" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Insured Email:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" placeholder="Insured Email" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Bill To</label>
        <div class="d-flex align-items-end col-sm-8">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" value="Insured" checked name="billToRadioOptions" />
                <label class="form-check-label">Insured</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" value="1st Mortgage" name="billToRadioOptions" />
                <label class="form-check-label">1st Mortgage</label>
            </div>
        </div>
    </div>
</form>

<div class="row">
    <div class="col-sm-12">
        <div class="form-check">
            <input class="form-check-input" type="checkbox">
            <label class="form-check-label">Check box if Mailing & Property Address are same</label>
        </div>
    </div>
</div>

<strong>Property Info</strong>

<form>
    <div class="row">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">&nbsp;</label>
        <div class="col-sm-8">
            <div class="form-check">
                <input class="form-check-input" type="checkbox">
                <label class="form-check-label">14 day wait</label>
            </div>
        </div>
    </div>

    <div class="row">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">&nbsp;</label>
        <div class="col-sm-8">
            <div class="form-check">
                <input class="form-check-input" type="checkbox">
                <label class="form-check-label">Closing/Purchase</label>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Property Address:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Property City:</label>
        <div class="col-sm-8">
            <input type="text" class="form-control" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Property State:</label>
        <div class="col-sm-3">
            <select class="form-select" aria-label="Default select example">
                <option value="">State</option>
                <option value="AK">AK</option>
                <option value="AL">AL</option>
                <option value="AR">AR</option>
                <option value="AZ">AZ</option>
                <option value="CA">CA</option>
                <option value="CO">CO</option>
                <option value="CT">CT</option>
                <option value="DC">DC</option>
                <option value="DE">DE</option>
                <option value="FL">FL</option>
                <option value="GA">GA</option>
                <option value="HI">HI</option>
                <option value="IA">IA</option>
                <option value="ID">ID</option>
                <option value="IL">IL</option>
                <option value="IN">IN</option>
                <option value="KS">KS</option>
                <option value="KY">KY</option>
                <option value="LA">LA</option>
                <option value="MA">MA</option>
                <option value="MD" selected="selected">MD</option>
                <option value="ME">ME</option>
                <option value="MI">MI</option>
                <option value="MN">MN</option>
                <option value="MO">MO</option>
                <option value="MS">MS</option>
                <option value="MT">MT</option>
                <option value="NC">NC</option>
                <option value="ND">ND</option>
                <option value="NE">NE</option>
                <option value="NH">NH</option>
                <option value="NJ">NJ</option>
                <option value="NM">NM</option>
                <option value="NV">NV</option>
                <option value="NY">NY</option>
                <option value="OH">OH</option>
                <option value="OK">OK</option>
                <option value="OR">OR</option>
                <option value="PA">PA</option>
                <option value="PR">PR</option>
                <option value="RI">RI</option>
                <option value="SC">SC</option>
                <option value="SD">SD</option>
                <option value="TN">TN</option>
                <option value="TX">TX</option>
                <option value="UT">UT</option>
                <option value="VA">VA</option>
                <option value="VT">VT</option>
                <option value="WA">WA</option>
                <option value="WI">WI</option>
                <option value="WV">WV</option>
                <option value="WY">WY</option>
            </select>
        </div>

        <label class="d-flex justify-content-end col-sm-1 col-form-label">Zip</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" placeholder="Zip" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">County:</label>
        <div class="col-sm-5">
            <select class="form-select" aria-label="Default select example">
                <option value="">County</option>
                <option value="Atlantic">Atlantic</option>
                <option value="Bergen">Bergen</option>
                <option value="Burlington">Burlington</option>
                <option value="Camden">Camden</option>
                <option value="Cape May">Cape May</option>
                <option value="Essex">Essex</option>
                <option value="Gloucester">Gloucester</option>
                <option value="Hudson">Hudson</option>
                <option value="Hunterdon">Hunterdon</option>
                <option value="Mercer">Mercer</option>
                <option value="Middlesex">Middlesex</option>
                <option value="Monmouth">Monmouth</option>
                <option value="Morris">Morris</option>
                <option value="Ocean">Ocean</option>
                <option value="Passaic">Passaic</option>
                <option value="Salem">Salem</option>
                <option value="Somerset">Somerset</option>
                <option value="Sussex">Sussex</option>
                <option value="Union">Union</option>
                <option value="Warren">Warren</option>
                <option value="Fairfield">Fairfield</option>
                <option value="Hartford">Hartford</option>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Number of Floors</label>
        <div class="col-sm-2">
            <input type="text" class="form-control" placeholder="" />
        </div>

        <label class="d-flex justify-content-end col-sm-2 col-form-label">Square Ft</label>
        <div class="col-sm-2">
            <input type="text" class="form-control" placeholder="" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Year Built</label>
        <div class="col-sm-3">
            <input type="text" class="form-control" placeholder="" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Construction Type</label>
        <div class="col-sm-5">
            <select class="form-select" aria-label="Default select example">
                <option value="">Select Type</option>
                <option value="Frame">Frame</option>
                <option value="Brick Vaneer">Brick Vaneer</option>
                <option value="Joisted Masonry">Joisted Masonry</option>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-center col-sm-12 col-form-label">Primary Residence</label>
        <div class="col-sm-12 d-flex justify-content-center ">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" value="Yes" checked name="primaryResidenceRadioOptions" />
                <label class="form-check-label">Yes</label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" value="No" checked name="primaryResidenceRadioOptions" />
                <label class="form-check-label">No</label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" value="Other" checked name="primaryResidenceRadioOptions" />
                <label class="form-check-label">Other</label>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Rented</label>
        <div class="d-flex align-items-end col-sm-8">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" value="Insured" checked name="rentedRadioOptions" />
                <label class="form-check-label">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" value="1st Mortgage" name="rentedRadioOptions" />
                <label class="form-check-label">No</label>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-sm-12 d-flex justify-content-center align-items-end ">
            <label class="form-label me-1">Condo</label>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox">
            </div>

            <label class="form-label me-1"># of Units :</label>

            <input type="text" class="form-control d-inline-block w-auto" placeholder="" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">RCBAP:</label>
        <div class="d-flex align-items-end col-sm-8">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" value="Low Rise" checked name="RCBAPOptions" />
                <label class="form-check-label">Low Rise</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" value="High Rise" name="RCBAPOptions" />
                <label class="form-check-label">High Rise</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" value="N/A" name="RCBAPOptions" />
                <label class="form-check-label">N/A</label>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-sm-12 d-flex justify-content-center ">
            <div class="form-check">
                <input class="form-check-input" type="checkbox">
                <label class="form-check-label">BreakAway Wall</label>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-sm-12 d-flex justify-content-center ">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox">
                <label class="form-check-label">LBI North</label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox">
                <label class="form-check-label">LBI South</label>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Current Company::</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Current Premium:</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Exp Date:</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" />
        </div>
    </div>
</form>