<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-2">
        <div class="card mb-3">
            <div class="card-body">
                <h5>Clients</h5>
                <div class="d-grid gap-2 col-12">
                    <a href="<?= base_url('/clients'); ?>" class="btn btn-primary">Client Search</a>
                    <a href="<?= base_url('/clients/add'); ?>" class="btn btn-primary">Add New Client</a>
                </div>

            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <div class="text-center">
                            <h5>SLA Menu</h5>
                            <p><a href="<?= base_url('/sla'); ?>">SLA Numbers</a></p>
                            <p><a href="<?= base_url('/sla/add'); ?>">Add Endorse/Cancel SLA</a></p>

                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center">
                            <h5>Brokers</h5>
                            <p><a href="<?= base_url('/brokers'); ?>" target="_blank">Broker List</a></p>
                            <p><a href="<?= base_url('/broker/create'); ?>" target="_blank">Add Broker</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5>Incoming Flood Requests</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Customer</th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>5898</td>
                            <td>
                                <p><strong>Doneille Calabrese </strong></p>
                                <p>303 North Harvard Avenue<br>
                                    Ventnor City, NJ </p>
                            </td>
                            <td>
                                <p>Broker:<br>heist</p>
                                <p>Producer<br>Kathleen McMurray</p>
                            </td>
                            <td>
                                <p>Zone: A8</p>
                                <p>Elev: -0.4</p>
                                <p>2023-03-28</p>
                                <p>11:20:37</p>
                            </td>
                            <td><a href="/brokerprocess/FloodQuoteRequestStart.asp?QuoteID=5898">Finalize<br>Quote</a></td>
                            <td>
                                <a href="/brokerprocess/FloodRateIndication.asp?QuoteID=5898" target="_blank">View Chubb Indication_</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>