<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<div class="modal fade" id="confirmModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reclaim SLA Number</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>You are about to erase SLA# 22-00912 and make it available again.</p>
                <h3>This is Not Reversible</h3>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary">Reclaim SLA #</button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <h5>Used SLA Numbers</h5>

        <form class="d-flex">
            <span class="d-flex form-text me-1 align-items-center">Search by Name or Policy Number</span>
            <input class="d-flex form-control w-auto me-1" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-primary" type="submit">Search</button>
        </form>

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">SLA Num</th>
                    <th scope="col">Policy Type</th>
                    <th scope="col">Insured Name</th>
                    <th scope="col">Policy Num</th>
                    <th scope="col">Exp Date</th>
                    <th scope="col">Fire Prem</th>
                    <th scope="col">Other Prem</th>
                    <th scope="col">Total Prem</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><a href="<?= base_url('/sla/edit'); ?>">10486</a></td>
                    <td><a href="<?= base_url('/sla/edit'); ?>">23-00363</a></td>
                    <td>5</td>
                    <td>Eric &amp; Marissa Bluestone</td>
                    <td>21FHI0016911</td>
                    <td>3/25/2024</td>
                    <td>0</td>
                    <td>1500</td>
                    <td>1500</td>
                    <td><a href="#" data-bs-toggle="modal" data-bs-target="#confirmModal">Erase</a></td>
                </tr>
                <tr>
                    <td><a href="<?= base_url('/sla/edit'); ?>">10486</a></td>
                    <td><a href="<?= base_url('/sla/edit'); ?>">23-00363</a></td>
                    <td>5</td>
                    <td>Eric &amp; Marissa Bluestone</td>
                    <td>21FHI0016911</td>
                    <td>3/25/2024</td>
                    <td>0</td>
                    <td>1500</td>
                    <td>1500</td>
                    <td><a href="#" data-bs-toggle="modal" data-bs-target="#confirmModal">Erase</a></td>
                </tr>
                <tr>
                    <td><a href="<?= base_url('/sla/edit'); ?>">10486</a></td>
                    <td><a href="<?= base_url('/sla/edit'); ?>">23-00363</a></td>
                    <td>5</td>
                    <td>Eric &amp; Marissa Bluestone</td>
                    <td>21FHI0016911</td>
                    <td>3/25/2024</td>
                    <td>0</td>
                    <td>1500</td>
                    <td>1500</td>
                    <td><a href="#" data-bs-toggle="modal" data-bs-target="#confirmModal">Erase</a></td>
                </tr>
                <tr>
                    <td><a href="<?= base_url('/sla/edit'); ?>">10486</a></td>
                    <td><a href="<?= base_url('/sla/edit'); ?>">23-00363</a></td>
                    <td>5</td>
                    <td>Eric &amp; Marissa Bluestone</td>
                    <td>21FHI0016911</td>
                    <td>3/25/2024</td>
                    <td>0</td>
                    <td>1500</td>
                    <td>1500</td>
                    <td><a href="#" data-bs-toggle="modal" data-bs-target="#confirmModal">Erase</a></td>
                </tr>
                <tr>
                    <td><a href="<?= base_url('/sla/edit'); ?>">10486</a></td>
                    <td><a href="<?= base_url('/sla/edit'); ?>">23-00363</a></td>
                    <td>5</td>
                    <td>Eric &amp; Marissa Bluestone</td>
                    <td>21FHI0016911</td>
                    <td>3/25/2024</td>
                    <td>0</td>
                    <td>1500</td>
                    <td>1500</td>
                    <td><a href="#" data-bs-toggle="modal" data-bs-target="#confirmModal">Erase</a></td>
                </tr>
                <tr>
                    <td><a href="<?= base_url('/sla/edit'); ?>">10486</a></td>
                    <td><a href="<?= base_url('/sla/edit'); ?>">23-00363</a></td>
                    <td>5</td>
                    <td>Eric &amp; Marissa Bluestone</td>
                    <td>21FHI0016911</td>
                    <td>3/25/2024</td>
                    <td>0</td>
                    <td>1500</td>
                    <td>1500</td>
                    <td><a href="#" data-bs-toggle="modal" data-bs-target="#confirmModal">Erase</a></td>
                </tr>
                <tr>
                    <td><a href="<?= base_url('/sla/edit'); ?>">10486</a></td>
                    <td><a href="<?= base_url('/sla/edit'); ?>">23-00363</a></td>
                    <td>5</td>
                    <td>Eric &amp; Marissa Bluestone</td>
                    <td>21FHI0016911</td>
                    <td>3/25/2024</td>
                    <td>0</td>
                    <td>1500</td>
                    <td>1500</td>
                    <td><a href="#" data-bs-toggle="modal" data-bs-target="#confirmModal">Erase</a></td>
                </tr>
                <tr>
                    <td><a href="<?= base_url('/sla/edit'); ?>">10486</a></td>
                    <td><a href="<?= base_url('/sla/edit'); ?>">23-00363</a></td>
                    <td>5</td>
                    <td>Eric &amp; Marissa Bluestone</td>
                    <td>21FHI0016911</td>
                    <td>3/25/2024</td>
                    <td>0</td>
                    <td>1500</td>
                    <td>1500</td>
                    <td><a href="#" data-bs-toggle="modal" data-bs-target="#confirmModal">Erase</a></td>
                </tr>
                <tr>
                    <td><a href="<?= base_url('/sla/edit'); ?>">10486</a></td>
                    <td><a href="<?= base_url('/sla/edit'); ?>">23-00363</a></td>
                    <td>5</td>
                    <td>Eric &amp; Marissa Bluestone</td>
                    <td>21FHI0016911</td>
                    <td>3/25/2024</td>
                    <td>0</td>
                    <td>1500</td>
                    <td>1500</td>
                    <td><a href="#" data-bs-toggle="modal" data-bs-target="#confirmModal">Erase</a></td>
                </tr>
                <tr>
                    <td><a href="<?= base_url('/sla/edit'); ?>">10486</a></td>
                    <td><a href="<?= base_url('/sla/edit'); ?>">23-00363</a></td>
                    <td>5</td>
                    <td>Eric &amp; Marissa Bluestone</td>
                    <td>21FHI0016911</td>
                    <td>3/25/2024</td>
                    <td>0</td>
                    <td>1500</td>
                    <td>1500</td>
                    <td><a href="#" data-bs-toggle="modal" data-bs-target="#confirmModal">Erase</a></td>
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

    <div class="col-1"></div>

    <div class="col-5">
        <h5>2023 Available SLA Numbers</h5>
    </div>
</div>

<?= $this->endSection() ?>