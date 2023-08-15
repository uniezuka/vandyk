<!doctype html>
<html lang="en">

<head>
    <title><?= $data['title'] ?></title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" crossorigin="anonymous">
    <link href="<?= base_url('assets/css/main.css'); ?>" rel="stylesheet">
</head>

<body>
    <header>
        <div class="container-fluid themed-container">
            <img src="<?= base_url('assets/images/iaclogo.jpg'); ?>" />
        </div>
        <nav class="navbar navbar-expand-md">
            <div class="container-fluid">
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url(''); ?>">Home</a>
                        </li>

                        <?php if (is_logged_in()) { ?>

                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('/clients'); ?>">Clients</a>
                        </li>

                        <?php if (is_admin()) { ?>

                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('/brokers'); ?>">Brokers</a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Settings
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?= base_url('/settings/transaction_types'); ?>">Transaction Types</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('/settings/fire_codes'); ?>">Fire Codes</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('/settings/coverage_list'); ?>">Coverage List</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('/settings/insurer_naic_list'); ?>">Insurer NAIC List</a></li>
                            </ul>
                        </li>

                        <?php } ?>

                        <?php } ?>
                    </ul>

                    <div class="justify-content-end">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown dropstart">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <?php if (is_logged_in()) { ?>
                                        <li><a class="dropdown-item" href="<?= base_url('/profile'); ?>">Profile</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('/change_password'); ?>">Change Password</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('/logout'); ?>">Logout</a></li>
                                    <?php } else { ?>
                                        <li><a class="dropdown-item" href="<?= base_url('/login'); ?>">Login</a></li>
                                    <?php } ?>
                                    
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <script src="https://code.jquery.com/jquery-2.2.4.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <div class="container-fluid themed-container">
        <?= $this->renderSection('content') ?>
    </div>
</body>

</html>