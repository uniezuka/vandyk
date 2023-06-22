<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<div class="row mb-3">
    <div class="col-8">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col col-6">
                        <h4>113-115 Central Ave Condo Assn<br />c/o Larry Baruffi</h4>
                    </div>

                    <div class="col col-6 text-end">
                        <p><strong>Broker: Heist Insurance Agency</strong></p>
                        <p>Client Code:</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col col-4">
                        <p>
                            Mailing Address:<br />
                            2080 Frederick Court<br />
                            Vineland,&nbsp;NJ&nbsp;&nbsp;&nbsp;08361
                        </p>
                    </div>

                    <div class="col col-4">
                        <p>
                            Cell: <br />
                            Home Ph:856 297 1679<br />
                            Email:
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col col-4">
                        <a href="<?= base_url('/clients/update'); ?>" class="btn btn-primary">Update Client</a>
                    </div>

                    <div class="col col-4">
                        <a href="<?= base_url('/flood_quote/create'); ?>" class="btn btn-primary">New Flood Quote</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
                <h5>Flood Policies/Quotes</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col"></th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <p><strong>NEW</strong>&nbsp; Quote ID: <a href="<?= base_url('/flood_quote/update'); ?>">14696</a>
                                    <br>
                                    Policy #: <br>
                                    Flood&nbsp;Zone: AE
                                </p>
                                <p><strong>Property Address: </strong> <br>
                                    113-115 Central Avenue<br>
                                    Ocean City, NJ</p>
                                <p>Entered: 6/10/2022&nbsp;&nbsp;&nbsp; </p>
                                <p></p>
                                <p>&nbsp;</p>
                                <p><a href="#" target="_blank">Nat Flood Data Lookup</a></p>
                                <p>&nbsp;</p>
                                <p><a href="#"> Quote Hiscox Commercial</a></p>
                            </td>
                            <td>
                                <p><a href="/Flood/editfloodrate.asp?QuoteID=14696" class="actionLink">Edit Quote Info</a></p>
                                <p></p>
                                <p><a href="#">View Current Rating</a></p>
                                <p><strong>Sandbar Docs</strong><br>
                                    <a href="#">Chubb App</a>
                                </p>
                                <p><a href="#">Chubb Quote</a></p>
                                <p><a href="#">Chubb Invoice</a></p>
                                <p><a href="#">No Loss Form</a></p>
                            </td>
                            <td>
                                <p>
                                    <a href="#">View Hiscox Quote</a><br><br>
                                    <a href="#">Full Requote Hiscox</a>
                                </p>
                                <p>

                                </p>
                                <p><strong>Sandbar Docs</strong><br>
                                    <a href="#">Hiscox Quote Doc</a><br><br>
                                    <a href="#">Hiscox App</a><br><br>
                                    <a href="#">Hiscox Invoice</a>
                                </p>
                            </td>
                            <td>
                                <a href="#">Bind Hiscox Policy</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>