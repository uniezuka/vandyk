<?php
$isPerson = $floodQuote->entity_type == 0;
?>

<div class="card border-success mb-3" style="min-width: 98%;">
    <div class="card-body">
        <div class="row">
            <div class="col-6">
                <strong>Option #<?= $count ?></strong>
                <br />
                <strong><?= $option->policyType ?></strong>
                <?php
                if ($isPerson) {
                    $buildingPremium = $isEndorsement ? $deductible->buildingAdditionalPremium : $deductible->buildingPremium;
                    $contentsPremium = $isEndorsement ? $deductible->includeContentsAdditionalPremium : $deductible->contentsPremium;
                    $otherStructuresPremium = $isEndorsement ? $deductible->otherStructuresAdditionalPremium : $deductible->otherStructuresPremium;
                    $lossOfUsePremium = $isEndorsement ? $deductible->includeLossOfUseAdditionalPremium : $deductible->lossOfUsePremium;

                    $buildingPremium = ceil($buildingPremium);
                    $contentsPremium = ceil($contentsPremium);
                    $otherStructuresPremium = ceil($otherStructuresPremium);
                    $lossOfUsePremium = ceil($lossOfUsePremium);
                    $totalPremium = $buildingPremium + $contentsPremium + $otherStructuresPremium + $lossOfUsePremium;
                ?>
                    <br /><br /><strong>Building Premium: </strong><?= number_format($buildingPremium) ?>
                    <br /><strong>Contents Premium: </strong><?= number_format($contentsPremium) ?>
                    <br /><strong>Other Structures Premium: </strong><?= number_format($otherStructuresPremium) ?>
                    <br /><strong>Deductible: </strong><?= number_format($deductible->deductible) ?>
                    <br /><strong>Loss Of Use Premium: </strong><?= number_format($lossOfUsePremium) ?>
                    <br /><br /><strong>Total Premium: </strong><?= number_format($totalPremium) ?>
                    <?php
                } else {
                    if ($isRented) {
                        $improvementsAndBettermentsPremium = $isEndorsement ? $deductible->improvementsAndBettermentsAdditionalPremium : $deductible->improvementsAndBettermentsPremium;
                        $businessIncomePremium = $isEndorsement ? $deductible->includeBusinessIncomeAdditionalPremium : $deductible->businessIncomePremium;
                        $contentsPremium = $isEndorsement ? $deductible->includeContentsAdditionalPremium : $deductible->contentsPremium;

                        $improvementsAndBettermentsPremium = ceil($improvementsAndBettermentsPremium);
                        $contentsPremium = ceil($contentsPremium);
                        $businessIncomePremium = ceil($businessIncomePremium);
                        $totalPremium = $improvementsAndBettermentsPremium + $contentsPremium + $businessIncomePremium;
                    ?>
                        <br /><br /><strong>Improvements Premium: </strong><?= number_format($improvementsAndBettermentsPremium) ?>
                        <br /><strong>Contents Premium: </strong><?= number_format($contentsPremium) ?>
                        <br /><strong>Deductible: </strong><?= number_format($deductible->deductible) ?>
                        <br /><strong>Business Income Premium: </strong><?= number_format($businessIncomePremium) ?>
                        <br /><br /><strong>Total Premium: </strong><?= number_format($totalPremium) ?>
                    <?php
                    } else {
                        $buildingPremium = $isEndorsement ? $deductible->buildingAdditionalPremium : $deductible->buildingPremium;
                        $contentsPremium = $isEndorsement ? $deductible->includeContentsAdditionalPremium : $deductible->contentsPremium;
                        $businessIncomePremium = $isEndorsement ? $deductible->includeBusinessIncomeAdditionalPremium : $deductible->businessIncomePremium;

                        $buildingPremium = ceil($buildingPremium);
                        $contentsPremium = ceil($contentsPremium);
                        $businessIncomePremium = ceil($businessIncomePremium);
                        $totalPremium = $buildingPremium + $contentsPremium + $businessIncomePremium;
                    ?>
                        <br /><br /><strong>Building Premium: </strong><?= number_format($buildingPremium) ?>
                        <br /><strong>Contents Premium: </strong><?= number_format($contentsPremium) ?>
                        <br /><strong>Deductible: </strong><?= number_format($deductible->deductible) ?>
                        <br /><strong>Business Income Premium: </strong><?= number_format($businessIncomePremium) ?>
                        <br /><br /><strong>Total Premium: </strong><?= number_format($totalPremium) ?>
                <?php
                    }
                }
                ?>
            </div>
            <div class="col-6">
                <div class="card border-primary mb-3" style="min-width: 100%; background-color: #B6F0F9;">
                    <div class="card-body">
                        <strong>Coverage Limits</strong>
                        <br />
                        <?php if ($floodQuote->entity_type == 0) { ?>
                            Building: <?= number_format($option->buildingLimit, 2) ?>
                            <br />
                            Contents: <?= number_format($option->contentsLimit, 2) ?>
                            <br />
                            Other Structures: <?= number_format($option->otherStructuresLimit, 2) ?>
                            <br />
                            Loss of Use: <?= number_format($option->lossOfUseLimit, 2) ?>
                            <br />
                            <?php
                        } else {
                            if ($isRented) {
                            ?>
                                Improvements: <?= number_format($option->improvementsAndBettermentsLimit, 2) ?>
                                <br />
                                Contents: <?= number_format($option->contentsLimit, 2) ?>
                                <br />
                                BI Expense: <?= number_format($option->businessIncomeAndExtraExpenseAnnualValue, 2) ?>
                            <?php
                            } else {
                            ?>
                                Building: <?= number_format($option->buildingLimit, 2) ?>
                                <br />
                                Contents: <?= number_format($option->contentsLimit, 2) ?>
                                <br />
                                BI Expense: <?= number_format($option->businessIncomeAndExtraExpenseAnnualValue, 2) ?>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>

            <?php if ($isSelectable) { ?>
                <button class="quoteButton btn btn-primary" type="button" data-quote-expiration="<?= $quoteExpiryDate ?>" data-policy-index="<?= $index ?>" data-policy-type="<?= $policy_type ?>" data-deductible="<?= $deductible->deductible ?>">
                    Quote this Option
                </button>
            <?php } ?>
        </div>
    </div>
</div>