<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<?php 
    helper('html'); 
    $brokers = $data['brokers'];
    $pager_links = $data['pager_links'];
?>

<div class="row">
    <div class="col-7">
        <div class="card">
            <div class="card-body">
                <h5>IAC Brokers</h5>

                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Broker</th>
                            <th scope="col">Address</th>
                            <th scope="col">Contact Info</th>
                            <th scope="col">IIANJ Member</th>
                            <th scope="col">Login ID</th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($brokers as $broker): ?>
                            <tr>
                                <td><?= $broker->broker_id ?></td>
                                <td><strong><?= $broker->name ?></strong></td>
                                <td>
                                    <?= formatAddress($broker->address, $broker->address2, $broker->city, $broker->state, $broker->zip) ?>
                                </td>
                                <td>
                                    <span class="d-block">Ph: <?= $broker->phone ?></span>
                                    <span class="d-block">Fax: <?= $broker->fax ?></span>
                                    <span class="d-block">Email: <?= $broker->email ?></span>
                                </td>
                                <td>
                                    <?= ($broker->iianj_member) ? '<i class="bi bi-check2-square"></i>' : '<i class="bi bi-square"></i>' ?>
                                </td>
                                <td><?= $broker->username ?></td>
                                <td><a href="<?= base_url('/profile'); ?>">Edit</a></td>
                                <td><a href="<?= base_url('/change_password'); ?>">Change Password</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <?= $pager_links ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>