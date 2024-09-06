<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<?php
helper('html');
extract($data);
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

<div class="modal fade" id="confirmModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reclaim SLA Number</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form role="form" method="post" action="<?= base_url('/sla/reclaim'); ?>">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <?= form_input(['name' => 'sla_policy_id', 'id' => 'slaPolicyId', 'type' => 'hidden', 'value' => '']); ?>
                    <p>You are about to erase SLA# <span id="transactionNumber"></span> and make it available again.</p>
                    <h3>This is Not Reversible</h3>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Reclaim SLA #</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-8">
        <h5>Used SLA Numbers</h5>

        <form class="d-flex" method="get">
            <span class="d-flex form-text me-1 align-items-center">Search by Name or Policy Number</span>
            <input class="d-flex form-control w-auto me-1" name="search" type="search" placeholder="Search" aria-label="Search" value="<?= $search ?>">
            <button class="btn btn-primary" type="submit">Search</button>
        </form>

        <table class="table sla_policies">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">SLA Num</th>
                    <th scope="col">Policy Type</th>
                    <th scope="col">Insured Name</th>
                    <th scope="col">Policy Num</th>
                    <th scope="col">Exp Date</th>
                    <th scope="col">Fire Prem</th>
                    <th scope="col">Other Prem</th>
                    <th scope="col">Total Prem</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($slaPolicies as $policy) : ?>
                    <tr>
                        <td><a href="<?= base_url('/sla/update/' . $policy->sla_policy_id); ?>"><?= $policy->sla_policy_id ?></a></td>
                        <td><a href="<?= base_url('/sla/update/' . $policy->sla_policy_id); ?>"><?= $policy->transaction_number ?></a></td>
                        <td><?= $policy->transaction_name ?></td>
                        <td><?= $policy->insured_name ?></td>
                        <td><?= $policy->policy_number ?></td>
                        <td><?= $policy->expiry_date ?></td>
                        <td><?= $policy->fire_premium ?></td>
                        <td><?= $policy->other_premium ?></td>
                        <td><?= $policy->total_premium ?></td>
                        <td><a href="#" data-bs-toggle="modal" class="erase_button" data-bs-target="#confirmModal" data-transaction_number="<?= $policy->transaction_number ?>" data-sla_policy_id="<?= $policy->sla_policy_id ?>">Erase</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?= $pager_links ?>
    </div>

    <div class="col-1"></div>

    <div class="col-3">
        <h5><?= $currentSLASetting->year ?> Available SLA Numbers</h5>
        <table class="table sla_policies">
            <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col">SLA Num</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($availableSLAPolicies as $policy) : ?>
                    <tr>
                        <td><a href="<?= base_url('/sla/update/' . $policy->sla_policy_id); ?>">Use</a></td>
                        <td><?= $policy->transaction_number ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <h5><?= $currentSLASetting->year - 1 ?> Available SLA Numbers</h5>
        <table class="table sla_policies">
            <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col">SLA Num</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($prevAvailableSLAPolicies as $policy) : ?>
                    <tr>
                        <td><a href="<?= base_url('/sla/update/' . $policy->sla_policy_id); ?>">Use</a></td>
                        <td><?= $policy->transaction_number ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


<script type="text/javascript">
    $(".table.sla_policies .erase_button").click(function() {
        var transaction_number = $(this).data('transaction_number');
        var sla_policy_id = $(this).data('sla_policy_id');
        $(".modal-body #transactionNumber").html(transaction_number);
        $(".modal-body #slaPolicyId").val(sla_policy_id);
    });
</script>

<?= $this->endSection() ?>