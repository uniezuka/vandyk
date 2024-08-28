<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<?php
helper(['html', 'service']);

$quoteRequestDate = $data["quoteRequestDate"];
$hiscoxID = $data["hiscoxID"];
$quoteExpiryDate = $data["quoteExpiryDate"];
$address = $data["address"];
$contentsCostValueType = $data["contentsCostValueType"];
$occupancyType = $data["occupancyType"];
$purpose = $data["purpose"];
$yearBuilt = $data["yearBuilt"];
$construction_type = $data["constructionType"];
$numberOfStories = $data["numberOfStories"];
$squareFootage = $data["squareFootage"];
$elevationHeight = $data["elevationHeight"];
$foundationType = $data["foundationType"];
$basementType = $data["basementType"];
$buildingOverWaterType = $data["buildingOverWaterType"];
$policyType = $data["policyType"];
$yearOfLastLoss = $data["yearOfLastLoss"];
$lastLossValue = $data["lastLossValue"];

$validations = $data["validations"];
$underwriterDecisions = $data["underwriterDecisions"];
$errors = $data["errors"];

$primaryOptions = $data["primaryOptions"];
$excessOptions = $data["excessOptions"];

$floodQuote = $data['floodQuote'];
$isRented = $data['isRented'];

$hiscoxSelectedOptionIndex = $data['hiscoxSelectedOptionIndex'];
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
                <p>Quote Date: <?= $quoteRequestDate ?></p>
                <p>Quote ID: <?= $hiscoxID ?></p>
                <p>Quote Expiry: <?= $quoteExpiryDate ?></p>
                <p><strong>Address: <?= $address ?></strong></p>
                <p>Contents Cost Value Type: <?= $contentsCostValueType ?></p>
                <p>Occupancy Type: <?= $occupancyType ?></p>
                <p>Purpose: <?= $purpose ?></p>
                <p>Year Built: <?= $yearBuilt ?></p>
                <p>Construction Type: <?= $construction_type ?></p>
                <p>Number of Stories: <?= $numberOfStories ?></p>
                <p>Square Footage: <?= $squareFootage ?></p>
                <p>Elevation Height: <?= $elevationHeight ?></p>
                <p>Foundation Type: <?= $foundationType ?></p>
                <p>Basement Type: <?= $basementType ?></p>
                <p>Building Over Water Type: <?= $buildingOverWaterType ?></p>
                <p>Policy Type: <?= $policyType ?></p>
                <p>Year of Last Loss: <?= $yearOfLastLoss ?></p>
                <p>Last Loss Value: <?= $lastLossValue ?></p>
            </div>
        </div>
    </div>

    <div class="col-5">
        <div class="card">
            <div class="card-body">
                <?php if ($hiscoxSelectedOptionIndex > 0) { ?>
                    <h6>Option # <?= $hiscoxSelectedOptionIndex ?> Selected for Quoting</h6>
                <?php } else { ?>
                    <h5>Select an Option to Quote from Available Quote Options Below</h5>
                <?php } ?>

                <form method="post" id="hiscoxForm">
                    <input type="hidden" name="hiscoxID" id="hiscoxID" value="<?= $hiscoxID ?>" />
                    <input type="hidden" name="update" id="update" value="update" />

                    <?= csrf_field() ?>

                    <?php
                    if (isset($validations) && count($validations)) {
                        echo view('Hiscox/_list_errors', ["alert" => "danger", "errors" => $validations]);
                    }
                    if (isset($underwriterDecisions) && count($underwriterDecisions)) {
                        echo view('Hiscox/_list_errors', ["alert" => "warning", "errors" => $underwriterDecisions]);
                    }
                    if (isset($errors) && count($errors)) {
                        echo view('Hiscox/_list_errors', ["alert" => "danger", "errors" => $errors]);
                    }

                    if (count($primaryOptions) || count($excessOptions)) {
                        $count = 1;

                        $index = 0;

                        foreach ($primaryOptions as $option) {
                            if (isset($option->deductibles) && count($option->deductibles)) {
                                if (isset($option->errors) && count($option->errors)) {
                                    echo view('Hiscox/_list_errors', ["alert" => "danger", "errors" => $option->errors]);
                                }

                                if (isset($option->warnings) && count($option->warnings)) {
                                    echo view('Hiscox/_list_errors', ["alert" => "warning", "errors" => $option->warnings]);
                                }

                                foreach ($option->deductibles as $deductible) {
                                    echo view('Hiscox/display_quote', [
                                        "count" => $count,
                                        "option" => $option,
                                        "deductible" => $deductible,
                                        "floodQuote" => $floodQuote,
                                        "isRented" => $isRented,
                                        "isSelectable" => true,
                                        "quoteExpiryDate" => $quoteExpiryDate,
                                        "index" => $index,
                                        "policy_type" => "primary",
                                        "deductible" => $deductible,
                                    ]);
                                    $count++;
                                }
                            }

                            $index++;
                        }

                        $index = 0;
                        foreach ($excessOptions as $option) {
                            if (isset($option->deductibles) && count($option->deductibles)) {
                                if (isset($option->errors) && count($option->errors)) {
                                    echo view('Hiscox/_list_errors', ["alert" => "danger", "errors" => $option->errors]);
                                }

                                if (isset($option->warnings) && count($option->warnings)) {
                                    echo view('Hiscox/_list_errors', ["alert" => "warning", "errors" => $option->warnings]);
                                }

                                foreach ($option->deductibles as $deductible) {
                                    echo view('Hiscox/display_quote', [
                                        "count" => $count,
                                        "option" => $option,
                                        "deductible" => $deductible,
                                        "floodQuote" => $floodQuote,
                                        "isRented" => $isRented,
                                        "isSelectable" => true,
                                        "quoteExpiryDate" => $quoteExpiryDate,
                                        "index" => $index,
                                        "policy_type" => "excess",
                                        "deductible" => $deductible,
                                    ]);

                                    $count++;
                                }
                            }
                            $index++;
                        }
                    }
                    ?>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var buttons = document.querySelectorAll('.quoteButton');

    function addPostData(formId, data) {
        const form = document.getElementById(formId);

        Object.keys(data).forEach(key => {
            const input = document.createElement("input");
            input.type = "hidden";
            input.name = key;
            input.value = data[key];
            form.appendChild(input);
        });
    }

    function handleBindButtonClick() {
        var policyType = event.target.getAttribute('data-policy-type');
        var deductible = event.target.getAttribute('data-deductible');
        var policyIndex = event.target.getAttribute('data-policy-index');
        var quoteExpiryDate = event.target.getAttribute('data-quote-expiration');

        var form = document.getElementById('hiscoxForm');
        const additionalData = {
            "policyType": policyType,
            "deductible": deductible,
            "policyIndex": policyIndex,
            "quoteExpiryDate": quoteExpiryDate
        };
        addPostData("hiscoxForm", additionalData);

        form.submit();
    }

    buttons.forEach(function(button) {
        button.addEventListener('click', handleBindButtonClick);
    });
</script>
<?= $this->endSection() ?>