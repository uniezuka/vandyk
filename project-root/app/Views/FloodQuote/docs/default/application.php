<?= $this->extend('layouts/print', ['data' => $data]) ?>
<?= $this->section('content') ?>

<?php
$formatter = new NumberFormatter('en_US', NumberFormatter::CURRENCY);
helper('html');
extract($data);
?>

<div class="container">
    <div class="text-center my-4">
        <img src="<?= base_url('assets/images/IACHeaderLogo.gif'); ?>" alt="logo" class="img-fluid" width="352" height="100">
        <h1 class="mt-3">Private Flood Insurance Application</h1>
    </div>
</div>

<?= $this->endSection() ?>