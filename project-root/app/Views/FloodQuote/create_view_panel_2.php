<strong>Rating Info</strong>

<form>
    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Flood Zone:</label>
        <div class="col-sm-5">
            <select class="form-select" aria-label="Default select example">
                <option value="">Select Flood Zone</option>
                <option value="A">A</option>
                <option value="A1">A1</option>
                <option value="A10">A10</option>
                <option value="A11">A11</option>
                <option value="A12">A12</option>
                <option value="A13">A13</option>
                <option value="A14">A14</option>
                <option value="A15">A15</option>
                <option value="A16">A16</option>
                <option value="A17">A17</option>
                <option value="A18">A18</option>
                <option value="A19">A19</option>
                <option value="A2">A2</option>
                <option value="A21">A21</option>
                <option value="A22">A22</option>
                <option value="A23">A23</option>
                <option value="A24">A24</option>
                <option value="A25">A25</option>
                <option value="A26">A26</option>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Building Diagram #:</label>
        <div class="col-sm-3">
            <input type="text" class="form-control" placeholder="" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Foundation:</label>
        <div class="col-sm-5">
            <select class="form-select" aria-label="Default select example">
                <option value="">Select Foundation</option>
                <option value="12">Basement</option>
                <option value="5">Crawlspace - Non-Vented</option>
                <option value="4">Crawlspace - Vented </option>
                <option value="9">Pilings - Full Enclosure(VE)</option>
                <option value="3">Pilings - Non-Vented (A)</option>
                <option value="1">Pilings - Open/No Encl (A/VE)</option>
                <option value="8">Pilings - Partial Enclosure (VE)</option>
                <option value="2">Pilings - Vented (A)</option>
                <option value="11">Raised Slab</option>
                <option value="10">Slab</option>
                <option value="7">Subgrade CS - Non-Vented </option>
                <option value="6">Subgrade CS - Vented </option>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Building Occupancy:</label>
        <div class="col-sm-5">
            <select class="form-select" aria-label="Default select example">
                <option value="">Select Occupancy</option>
                <option value="1">Single Family Primary</option>
                <option value="2">Single Family Secondary</option>
                <option value="3">2-4 Family</option>
                <option value="4">Non-Residential</option>
                <option value="5">Low Rise Condo</option>
                <option value="6">High Rise Condo</option>
                <option value="7">Other Residential</option>
            </select>
        </div>
    </div>

    <strong>Other Occupancy Info:</strong>

    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" value="N/A" checked name="occupancyInfoOptions" />
                <label class="form-check-label">N/A</label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" value="Seasonal" checked name="occupancyInfoOptions" />
                <label class="form-check-label">Seasonal</label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" value="Tenants Occupy" checked name="occupancyInfoOptions" />
                <label class="form-check-label">Tenants Occupy</label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" value="Vacant" checked name="occupancyInfoOptions" />
                <label class="form-check-label">Vacant</label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" value="CoC - Coarse Of Construction" checked name="occupancyInfoOptions" />
                <label class="form-check-label">CoC - Coarse Of Construction</label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" value="Vacant-Renovation" checked name="occupancyInfoOptions" />
                <label class="form-check-label">Vacant-Renovation</label>
            </div>
        </div>
    </div>

    <strong>Is Basement Finished?</strong>

    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" value="Yes" checked name="basementOptions" />
                <label class="form-check-label">Yes</label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" value="No" checked name="basementOptions" />
                <label class="form-check-label">No</label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" value="No Basement" checked name="basementOptions" />
                <label class="form-check-label">No Basement</label>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-start  col-sm-5 col-form-label"><strong>Is Elevation Enclosure Finished?</strong></label>
        <div class="d-flex align-items-end col-sm-7">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" value="Yes" checked name="elevationOptions" />
                <label class="form-check-label">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" value="No" name="elevationOptions" />
                <label class="form-check-label">No</label>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-start col-sm-5 col-form-label"><strong>Attached Garage:</strong></label>
        <div class="d-flex align-items-end col-sm-7">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" value="Yes" checked name="garageOptions" />
                <label class="form-check-label">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" value="No" name="garageOptions" />
                <label class="form-check-label">No</label>
            </div>
        </div>
    </div>

    <strong>Is Building Over Water:</strong>

    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" value="No" checked name="overWaterOptions" />
                <label class="form-check-label">No</label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" value="Partially" checked name="overWaterOptions" />
                <label class="form-check-label">Partially</label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" value="Entirely" checked name="overWaterOptions" />
                <label class="form-check-label">Entirely</label>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Base Flood Elev:</label>
        <div class="col-sm-2">
            <input type="text" class="form-control" placeholder="" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">1st Living Floor Elevation:</label>
        <div class="col-sm-2">
            <input type="text" class="form-control" placeholder="" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Elevation Difference<br />(Effective Rating Elevation):</label>
        <div class="col-sm-2">
            <input type="text" class="form-control" placeholder="" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Lowest Floor Elevation</label>
        <div class="col-sm-2">
            <input type="text" class="form-control" placeholder="" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Next Higher Floor</label>
        <div class="col-sm-2">
            <input type="text" class="form-control" placeholder="" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label text-end">Lowest Horizontal Structure Member</label>
        <div class="col-sm-2">
            <input type="text" class="form-control" placeholder="" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">LAG</label>
        <div class="col-sm-2">
            <input type="text" class="form-control" placeholder="" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">HAG</label>
        <div class="col-sm-2">
            <input type="text" class="form-control" placeholder="" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Mid Level Entry Elevation:</label>
        <div class="col-sm-2">
            <input type="text" class="form-control" placeholder="" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Sq Ft of Enclosure</label>
        <div class="col-sm-2">
            <input type="text" class="form-control" placeholder="" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Elev Cert Date</label>
        <div class="col-sm-5">
            <input type="date" class="form-control" placeholder="" />
        </div>
    </div>

    <div class="row">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">&nbsp;</label>
        <div class="col-sm-8">
            <div class="form-check">
                <input class="form-check-input" type="checkbox">
                <label class="form-check-label">No Elev Cert</label>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Substantial Improvement Date</label>
        <div class="col-sm-5">
            <input type="date" class="form-control" placeholder="" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">CovA Building:</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" placeholder="" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">CovC Content:</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" placeholder="" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">CovD Loss of Use:</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" placeholder="" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Total Replacement Cost:</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" placeholder="" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">RCE Ratio:</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" placeholder="" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Underinsured Rate</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" placeholder="" />
        </div>
        <span class="col-sm-4">(ex. 0.01)</span>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Deductible:</label>
        <div class="col-sm-6">
            <select class="form-select" aria-label="Default select example">
                <option value="4">No Change $1500/$1500</option>
                <option value="1">$2500/$2500 AV Zone Only</option>
                <option value="2">$5000/$5000</option>
                <option value="3">$10000/10000 AV Zone Only</option>
                <option value="4">$1500/$1500</option>
                <option value="5">$2000 (Canopius)</option>
                <option value="6">$25,000</option>
                <option value="7">$5,000 Build - $10,000 Content</option>
                <option value="8">$1000 (Hiscox Only)</option>
                <option value="9">$25K Build - $5K Content</option>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label text-end">Optional Personal Property Replacement Cost:</label>
        <div class="d-flex align-items-end col-sm-8">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" value="Yes" checked name="optPropRepCostRadioOptions" />
                <label class="form-check-label">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" value="No" name="optPropRepCostRadioOptions" />
                <label class="form-check-label">No</label>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label text-end">Dwelling Replacement Cost(Secondary Home):</label>
        <div class="d-flex align-items-end col-sm-8">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" value="Yes" checked name="optPropRepCostRadioOptions" />
                <label class="form-check-label">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" value="No" name="optPropRepCostRadioOptions" />
                <label class="form-check-label">No</label>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Binding Auth</label>
        <div class="col-sm-5">
            <select class="form-select" aria-label="Default select example">
                <option value="">Select Bind Auth</option>
                <option value="B1921 VC000070V">Chubb</option>
                <option value="B1921 VC000240V">Canopius</option>
                <option value="B1921 VC000230U">Brit</option>
                <option value="B1921 VC000250V">Hiscox</option>
                <option value="B1921 VC000260W">QBE</option>
            </select>
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-4 col-form-label">Hiscox QuoteID:</label>
        <div class="col-sm-5">
            <input type="text" class="form-control" placeholder="" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-3 col-form-label">Syndicate 1</label>
        <div class="col-sm-3">
            <select class="form-select" aria-label="Default select example">
                <option value="">N/A</option>
                <option value="Chubb">Chubb</option>
                <option value="Canopius">Canopius</option>
                <option value="Brit">Brit</option>
                <option value="Hiscox">Hiscox</option>
                <option value="QBE">QBE</option>
            </select>
        </div>

        <label class="d-flex justify-content-end col-sm-3 col-form-label">Risk %</label>
        <div class="col-sm-3">
            <input type="text" class="form-control" placeholder="" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-3 col-form-label">Syndicate 2</label>
        <div class="col-sm-3">
            <select class="form-select" aria-label="Default select example">
                <option value="">N/A</option>
                <option value="Chubb">Chubb</option>
                <option value="Canopius">Canopius</option>
                <option value="Brit">Brit</option>
                <option value="Hiscox">Hiscox</option>
                <option value="QBE">QBE</option>
            </select>
        </div>

        <label class="d-flex justify-content-end col-sm-3 col-form-label">Risk %</label>
        <div class="col-sm-3">
            <input type="text" class="form-control" placeholder="" />
        </div>
    </div>

    <div class="row mb-3">
        <label class="d-flex justify-content-end col-sm-3 col-form-label">Syndicate 3</label>
        <div class="col-sm-3">
            <select class="form-select" aria-label="Default select example">
                <option value="">N/A</option>
                <option value="Chubb">Chubb</option>
                <option value="Canopius">Canopius</option>
                <option value="Brit">Brit</option>
                <option value="Hiscox">Hiscox</option>
                <option value="QBE">QBE</option>
            </select>
        </div>

        <label class="d-flex justify-content-end col-sm-3 col-form-label">Risk %</label>
        <div class="col-sm-3">
            <input type="text" class="form-control" placeholder="" />
        </div>
    </div>
</form>