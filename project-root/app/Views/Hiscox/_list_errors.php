<div class="alert alert-<?= $alert ?>">
    <ul>
        <?php foreach ($errors as $error) {
            echo '<li>' . $error . '</li>';
        } ?>
    </ul>
</div>