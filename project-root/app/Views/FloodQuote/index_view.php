<?= $this->extend('layouts/default', ['data' => $data]) ?>

<?= $this->section('content') ?>

<?php
helper(['html', 'service']);
$flood_quotes = $data['flood_quotes'];
$pager_links = $data['pager_links'];

function getMetaValue($metas, $meta_key)
{
    foreach ($metas as $meta) {
        if ($meta->meta_key === $meta_key) {
            return $meta->meta_value;
        }
    }
    return '';
}
?>

<?php if (session()->getFlashdata('error') || validation_errors()) : ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
        <?= validation_list_errors() ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('message')) : ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('message') ?>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-10">
        <div class="card">
            <div class="card-body">
                <h5>Flood Policies</h5>

                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Quote</th>
                            <th scope="col"></th>
                            <th scope="col">Name/Address</th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $ids = array_map(function ($flood_quote) {
                            return $flood_quote->flood_quote_id;
                        }, $flood_quotes);

                        $metas = getBatchedFloodQuoteMetas($ids);

                        foreach ($flood_quotes as $flood_quote) {
                            $flood_quote_id = $flood_quote->flood_quote_id;
                            $flood_quote_metas = array_filter($metas, function ($meta) use ($flood_quote_id) {
                                return $meta->flood_quote_id == $flood_quote_id;
                            });

                            $policy_type = getMetaValue($flood_quote_metas, 'policy_type');
                            $date_entered = getMetaValue($flood_quote_metas, 'date_entered');
                            $has_excess_policy = getMetaValue($flood_quote_metas, 'has_excess_policy');
                        ?>
                            <tr>
                                <td>
                                    <p>ID: <a href=""><?= $flood_quote->flood_quote_id ?></a></p>
                                    <p>Entered: <?= $date_entered ?></p>
                                    <?php if ($has_excess_policy) { ?>
                                        <p><strong>EXCESS POLICY</strong></p>
                                    <?php } ?>
                                </td>
                                <td>
                                    <p><a class="btn btn-primary btn-sm" href="<?= base_url('/client/update/') . $flood_quote->client_id; ?>">Update Client</a></p>
                                    <p><a class="btn btn-primary btn-sm" href="">Update Rating Info</a>
                                    <p>&nbsp;</p>
                                    <p><a href="">Quote Hiscox Commercial</a></p>
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <?= $pager_links ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>