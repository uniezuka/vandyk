<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<?php
helper(['html', 'service']);

$floodQuote = $data['floodQuote'];
$hiscoxFloodQuote = $data['hiscoxFloodQuote'];
$isRented = $data['isRented'];

if ($hiscoxFloodQuote != null) {
    $productResponseRequest = $data['productResponseRequest'];

    $hiscoxProductResponse = $productResponseRequest["hiscoxProductResponse"];
    $hiscoxProductRequest = $productResponseRequest["hiscoxProductRequest"];
    $purpose = $productResponseRequest["purpose"];

    $hiscoxSelectedOptionIndex = $data['hiscoxSelectedOptionIndex'];
    $hiscoxSelectedPolicyType = $data['hiscoxSelectedPolicyType'];
    $hiscoxSelectedDeductible = $data['hiscoxSelectedDeductible'];

    $yearOfLastLoss = "";
    $lastLossValue = 0;

    if ($hiscoxFloodQuote->request->priorLosses) {
        $yearOfLastLoss = $hiscoxFloodQuote->request->priorLosses[0]->year;
        $lastLossValue = $hiscoxFloodQuote->request->priorLosses[0]->value;
    }

    $primaryOptions = $data['primaryOptions'];
    $excessOptions = $data['excessOptions'];

    $selectedOptionIndex = $data['selectedOptionIndex'];
    $hiscoxOptions = $data['hiscoxOptions'];
}
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
    <div class="col-10">
        <div class="card">
            <div class="card-body">
                <div class="clearfix">
                    <h5>Link Hiscox</h5>
                    <form class="form" method="post">
                        <?= csrf_field() ?>
                        <div class="d-flex p-2">
                            <input class="d-flex form-control w-75 me-1" name="hiscoxID" type="text" placeholder="Hiscox ID" value="<?= set_value('hiscoxID') ?>" />
                            <button class="btn btn-primary" type="submit">Populate Hiscox</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($hiscoxFloodQuote != null) { ?>
    <div class="row mb-3">
        <div class="col-5">
            <div class="card">
                <div class="card-body">
                    <p>Quote Date: <?= $hiscoxFloodQuote->response->quoteRequestDate ?></p>
                    <p>Quote ID: <?= set_value('hiscoxID') ?></p>
                    <p>Contents Cost Value Type: <?= $hiscoxFloodQuote->request->contentsCostValueType ?></p>
                    <p>Occupancy Type: <?= $hiscoxProductRequest->occupancyType ?></p>
                    <p>Purpose: <?= $purpose ?></p>
                    <p>Year Built: <?= $hiscoxFloodQuote->request->yearBuilt ?></p>
                    <p>Construction Type: <?= $hiscoxProductRequest->constructionType ?></p>
                    <p>Number of Stories: <?= $hiscoxFloodQuote->request->numberOfStories ?></p>
                    <p>Square Footage: <?= $hiscoxFloodQuote->request->squareFootage ?></p>
                    <p>Elevation Height: <?= $hiscoxFloodQuote->request->elevationHeight ?></p>
                    <p>Foundation Type: <?= $hiscoxFloodQuote->request->foundation->foundationType ?></p>
                    <p>Basement Type: <?= $hiscoxFloodQuote->request->basementType ?></p>
                    <p>Building Over Water Type: <?= $hiscoxFloodQuote->request->buildingOverWaterType ?></p>
                    <p>Policy Type: <?= $hiscoxSelectedPolicyType ?></p>
                    <p>Year of Last Loss: <?= $yearOfLastLoss  ?></p>
                    <p>Last Loss Value: <?= $lastLossValue ?></p>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="card">
                <div class="card-body">
                    <?php
                    if ($selectedOptionIndex > 0) {
                        echo "<h6>Option # " . $selectedOptionIndex . " Selected for Quoting</h6>";
                    }
                    ?>

                    <h5>Available Quote Options</h5>

                    <?php
                    if (count($primaryOptions) || count($excessOptions)) {
                        $count = 1;

                        foreach ($primaryOptions as $option) {
                            if (isset($option->errors) && count($option->errors)) {
                                echo view('Hiscox/_list_errors', ["alert" => "danger", "errors" => $option->errors]);
                            }

                            if (isset($option->warnings) && count($option->warnings)) {
                                echo view('Hiscox/_list_errors', ["alert" => "warning", "errors" => $option->warnings]);
                            }

                            if (isset($option->deductibles) && count($option->deductibles)) {
                                foreach ($option->deductibles as $deductible) {
                                    echo view('Hiscox/display_quote', [
                                        "count" => $count,
                                        "option" => $option,
                                        "deductible" => $deductible,
                                        "floodQuote" => $floodQuote,
                                        "isRented" => $isRented,
                                        "isSelectable" => false
                                    ]);
                                    $count++;
                                }
                            }
                        }

                        foreach ($excessOptions as $option) {
                            if (isset($option->errors) && count($option->errors)) {
                                echo view('Hiscox/_list_errors', ["alert" => "danger", "errors" => $option->errors]);
                            }

                            if (isset($option->warnings) && count($option->warnings)) {
                                echo view('Hiscox/_list_errors', ["alert" => "warning", "errors" => $option->warnings]);
                            }

                            if (isset($option->deductibles) && count($option->deductibles)) {
                                foreach ($option->deductibles as $deductible) {
                                    echo view('Hiscox/display_quote', [
                                        "count" => $count,
                                        "option" => $option,
                                        "deductible" => $deductible,
                                        "floodQuote" => $floodQuote,
                                        "isRented" => $isRented,
                                        "isSelectable" => false
                                    ]);

                                    $count++;
                                }
                            }
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<?= $this->endSection() ?>