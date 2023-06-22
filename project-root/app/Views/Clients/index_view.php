<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-7">
        <div class="card">
            <div class="card-body">
                <div class="clearfix">
                    <h5>Clients</h5>
                    <div class="float-start">
                        <p>Search by Customer <strong>Last Name, First Name, Client Code or Mailing Address</strong></p>
                        <form class="d-flex">
                            <input class="d-flex form-control w-75 me-1" type="search" placeholder="Search">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </form>
                    </div>
                    <a type="button" class="btn btn-primary float-end" href="<?= base_url('/clients/add'); ?>">Add New Client</a>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Address</th>
                            <th scope="col">Client Code</th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>6814</td>
                            <td></td>
                            <td>
                                12800 Long Beach Blvd<br>
                                Beach Haven Terrace, NJ&nbsp;&nbsp; 08008
                            </td>
                            <td></td>
                            <td><a href="<?= base_url('/clients/details'); ?>" class="actionLink">View</a>
                            </td>
                            <td>
                                <p><a href="/Flood/StartFloodRateClient.asp?ClientID=6814" class="actionLink">New Flood</a></p>
                            </td>
                        </tr>

                        <tr>
                            <td>3237</td>
                            <td><strong>Testa DaForms<br></strong></td>
                            <td>333 bill st<br>
                                Carthage, NJ&nbsp;&nbsp; 28327</td>
                            <td>TESTS001</td>
                            <td><a href="<?= base_url('/clients/details'); ?>" class="actionLink">View</a>
                            </td>
                            <td>
                                <p><a href="<?= base_url('/flood_quote/create'); ?>" class="actionLink">New Flood</a></p>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <nav>
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>