<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>
<div class="card mb-4">
    <div class="card-body">
        <h2 class="mb-2">Selamat datang kembali, Admin!</h2>
        <p>Manajemen barang dan gudang menggunakan CodeIgniter 4.</p>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Barang</h5>
                <p class="card-text display-4 text-barang"></p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Kategori</h5>
                <p class="card-text display-4 text-kategori"></p>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Supplier</h5>
                <p class="card-text display-4 text-supplier"></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Divisi</h5>
                <p class="card-text display-4 text-divisi"></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Transaksi</h5>
                <p class="card-text display-4 text-transaksi"></p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script>
    var baseUrl = window.location.href;

    fetch(baseUrl + '/inventory/count')
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                document.querySelector('.text-barang').textContent = data.total_items;
            }
        })
        .catch(error => console.error('Error fetching total items:', error));

    fetch(baseUrl + '/category/count')
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                document.querySelector('.text-kategori').textContent = data.total_categories;
            }
        })
        .catch(error => console.error('Error fetching total categories:', error));

    fetch(baseUrl + '/supplier/count')
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                document.querySelector('.text-supplier').textContent = data.total_suppliers;
            }
        })
        .catch(error => console.error('Error fetching total suppliers:', error));

    fetch(baseUrl + '/division/count')
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                document.querySelector('.text-divisi').textContent = data.total_divisions;
            }
        })
        .catch(error => console.error('Error fetching total divisions:', error));

    fetch(baseUrl + '/transaction/count')
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                document.querySelector('.text-transaksi').textContent = data.total_transactions;
            }
        })
        .catch(error => console.error('Error fetching total transactions:', error));
</script>

<?= $this->endSection() ?>