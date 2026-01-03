<?= $this->extend('App\Views\layouts\template') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row">
        <div class="col">
            <h1><?= $title ?></h1>
            <p>Welcome to the Inventory Management System.</p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>