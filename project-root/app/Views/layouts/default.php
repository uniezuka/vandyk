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

                            <li class="nav-item">
                                <a class="nav-link" href="<?= base_url('/flood_quotes'); ?>">Flood Policies</a>
                            </li>

                            <?php if (is_admin()) { ?>

                                <li class="nav-item">
                                    <a class="nav-link" href="<?= base_url('/brokers'); ?>">Brokers</a>
                                </li>

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        References
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="<?= base_url('/counties'); ?>">Counties</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('/occupancies'); ?>">Occupancies</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('/constructions'); ?>">Constructions</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('/transaction_types'); ?>">Transaction Types</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('/fire_codes'); ?>">Fire Codes</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('/coverages'); ?>">Coverages</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('/insurers'); ?>">Insurers</a></li>
                                        <li><a class="dropdown-item" href="<?= base_url('/deductibles'); ?>">Deductibles</a></li>
                                    </ul>
                                </li>

                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Settings
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="<?= base_url('/sla_settings'); ?>">SLA Generator Settings</a></li>
                                    </ul>
                                </li>

                        <?php
                            }
                        }
                        ?>
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

<script type="text/javascript">
    function calculateForm(targetFieldName, precision, decimalPlaces, ...expressionParts) {
        let expression = "";

        expressionParts.forEach(part => {
            if (part.indexOf("#") === 0) {
                let fieldValue = jQuery(`[name="${part.substring(1)}"]`).val(); // Fetch the value using jQuery
                expression += fieldValue;
            } else {
                expression += part;
            }
        });

        let result = eval(expression);
        result = Math.round(precision * result) / precision;

        let resultString = result.toString();

        if (decimalPlaces > 0) {
            let decimalPosition = resultString.indexOf(".");

            if (decimalPosition === -1) {
                resultString += ".";
                decimalPosition = resultString.indexOf(".");
            }

            while (resultString.length - 1 - decimalPosition < decimalPlaces) {
                resultString += "0";
            }
        }

        jQuery(`[name="${targetFieldName}"]`).val(resultString);
    }
</script>

</html>