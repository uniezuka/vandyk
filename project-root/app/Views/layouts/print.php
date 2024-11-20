<!doctype html>
<html lang="en">

<head>
    <title><?= $data['title'] ?></title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" crossorigin="anonymous">
    <link href="<?= base_url('assets/css/main.css'); ?>" rel="stylesheet">

    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 16px;
            line-height: 1.5;
        }

        .content-wrapper {
            margin: 20px;
        }

        .signature-line {
            width: 200px;
            border-bottom: 1px solid #000;
            margin-top: 40px;
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 24px;
            margin-bottom: 30px;
        }

        .footer {
            margin-top: 50px;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .container {
                margin-left: auto;
                margin-right: auto;
                padding: 0;
            }

            .content-wrapper {
                margin: 0;
                padding: 10mm;
                page-break-inside: avoid;
            }

            .no-print {
                display: none !important;
            }

            table {
                border-collapse: collapse;
                width: 100%;
            }

            th,
            td {
                border: 1px solid #000;
                padding: 8px;
                text-align: left;
            }

            * {
                background: none !important;
                color: #000 !important;
            }
        }
    </style>
</head>

<body>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <div class="container">
        <?= $this->renderSection('content') ?>
    </div>
</body>

</html>