<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-7">
        <div class="card">
            <div class="card-body">
                <div class="clearfix">
                    <h5>Transcation Types</h5>
                    <button type="button" class="btn btn-primary float-end">NEW</button>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>NEW BUSINESS</td>
                            <td><a href="#">Edit</a>&nbsp;<a href="#">Delete</a></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Additional Premium</td>
                            <td><a href="#">Edit</a>&nbsp;<a href="#">Delete</a></td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Return Premium</td>
                            <td><a href="#">Edit</a>&nbsp;<a href="#">Delete</a></td>
                        </tr>
                    </tbody>
                </table>

                <nav>
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Previous</a>
                        </li>
                        <li class="page-item"><a class="page-link" href="#">1</a></li>
                        <li class="page-item disabled">
                            <a class="page-link" href="#">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>