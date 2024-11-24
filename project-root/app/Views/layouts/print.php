<!doctype html>
<html lang="en">

<head>
    <title><?= $data['title'] ?></title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css" crossorigin="anonymous">

    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 16px;
            line-height: 1.5;
            background-color: none;
        }

        blockquote {
            margin: 1em 40px;
        }

        table>thead>tr>th,
        table>tbody>tr>td {
            padding: 5px;
            text-align: left;
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
            margin-bottom: 20px;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 24px;
            margin-bottom: 30px;
        }

        .footer {
            margin-top: 20px;
        }

        .grey-line {
            border-top: 5px solid #B8B8B8;
        }

        @media print {
            * {
                background: none !important;
                color: #000 !important;
            }

            @page {
                size: A4;
                margin: 0;
            }

            body {
                margin: 0;
                padding: 0;
                background-color: none;
                font-size: 14px;
            }

            .container {
                margin-left: auto;
                margin-right: auto;
                padding: 0;
                width: 100%;
                max-width: 100%;
            }

            .content-wrapper {
                margin: 0;
                padding: 5mm;
                page-break-inside: avoid;
                width: 100%;
                box-sizing: border-box;
                page-break-after: always;
            }

            .no-print {
                display: none !important;
            }

            .logo {
                margin-bottom: 0;
            }

            table {
                border-collapse: collapse;
                width: 100%;
            }

            table>thead>tr>th,
            table>tbody>tr>td {
                padding: 5px;
                font-size: 10px;
            }

            .title {
                font-size: 20px;
            }

            .header,
            .footer,
            .content-wrapper {
                page-break-inside: avoid;
            }

            .border .table {
                margin-bottom: 0;
            }

            .content-wrapper:last-child {
                page-break-after: auto;
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