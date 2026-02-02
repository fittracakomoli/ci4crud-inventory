<?= $this->extend('layouts/template') ?>

<?= $this->section('content') ?>
<div class="card mb-4">
    <div class="card-body">
        <h2 class="mb-2">Selamat datang kembali, Admin!</h2>
        <p>Manajemen barang dan gudang menggunakan CodeIgniter 4.</p>
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