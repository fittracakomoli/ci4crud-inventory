<?= $this->extend('App\Views\layouts\template') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row">
        <div class="col-md-12 mt-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>Transaction Management</h3>
                    <p>Manage your inventory for entering and exiting stock.</p>
                </div>
                <div class="mb-4 text-end">
                    <button type="button" class="btn btn-primary btn-tambah" data-bs-toggle="modal" data-bs-target="#transaksiModal">
                        Tambah Transaksi
                    </button>
                </div>
            </div>

            <table class="table table-hover" id="table_transaksi">
                <thead>
                    <tr>
                        <th scope="col">Invoice ID</th>
                        <th scope="col">Nama Barang</th>
                        <th scope="col">Jenis</th>
                        <th scope="col">Dari/Ke</th>
                        <th scope="col">Jumlah</th>
                        <th scope="col">Keterangan</th>
                        <th scope="col">Tanggal</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Modal -->

<form id="formTransaksi">
    <div class="modal fade" id="transaksiModal" tabindex="-1" aria-labelledby="transaksiModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="transaksiModalLabel">Form Tambah Transaksi</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="id_barang" class="form-label">Barang<sup class="text-danger fw-bold">*</sup></label>
                        <select class="form-select" id="id_barang" name="id_barang" required>
                            <option value="" selected disabled>Pilih Barang</option>
                            <?php foreach ($barangs as $barang) : ?>
                                <option value="<?= $barang['id']; ?>"><?= $barang['nama_barang']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3 d-none" id="stok">
                        <label for="sisa_stok" class="form-label">Sisa Stok</label>
                        <input type="text" class="form-control" id="sisa_stok" disabled />
                    </div>
                    <div class="mb-3">
                        <label for="jenis" class="form-label">Jenis<sup class="text-danger fw-bold">*</sup></label>
                        <select class="form-select" id="jenis" name="jenis" required>
                            <option value="" selected disabled>Pilih Jenis</option>
                            <option value="masuk">Masuk</option>
                            <option value="keluar">Keluar</option>
                        </select>
                    </div>
                    <div class="mb-3 d-none" id="supplier">
                        <label for="id_supplier" class="form-label">Supplier<sup class="text-danger fw-bold">*</sup></label>
                        <select class="form-select" id="id_supplier" name="id_supplier">
                            <option value="" selected disabled>Pilih Supplier</option>
                            <?php foreach ($suppliers as $supplier) : ?>
                                <option value="<?= $supplier['id']; ?>"><?= $supplier['nama_supplier']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3 d-none" id="divisi">
                        <label for="id_divisi" class="form-label">Divisi<sup class="text-danger fw-bold">*</sup></label>
                        <select class="form-select" id="id_divisi" name="id_divisi">
                            <option value="" selected disabled>Pilih Divisi</option>
                            <?php foreach ($divisis as $divisi) : ?>
                                <option value="<?= $divisi['id']; ?>"><?= $divisi['nama_divisi']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah<sup class="text-danger fw-bold">*</sup></label>
                        <input type="text" class="form-control" id="jumlah" name="jumlah" placeholder="Masukkan jumlah" autofocus required />
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Create Modal -->

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script>
    var baseUrl = window.location.href;

    $(document).ready(function() {
        $('.btn-tambah').on('click', function() {
            $('#stok').addClass('d-none');
            $('#supplier').addClass('d-none');
            $('#divisi').addClass('d-none');
            resetForm();
        });

        $('#id_barang').on('change', function() {
            var id_barang = $(this).val();

            $.ajax({
                url: baseUrl + '/detail',
                method: "POST",
                data: {
                    id: id_barang
                },
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        $('#stok').removeClass('d-none');
                        $('#sisa_stok').val(response.data.stok);
                    } else {
                        $('#sisa_stok').val('');
                        alert(response.message);
                    }
                },
            });
        });

        $('#jenis').on('change', function() {
            var jenis = $(this).val();

            if (jenis === 'masuk') {
                $('#supplier').removeClass('d-none');
                $('#supplier').attr('required', true);
                $('#divisi').addClass('d-none');
                $('#divisi').attr('required', false);
                $('#id_divisi').val('');
            } else if (jenis === 'keluar') {
                $('#divisi').removeClass('d-none');
                $('#divisi').attr('required', true);
                $('#supplier').addClass('d-none');
                $('#supplier').attr('required', false);
                $('#id_supplier').val('');
            }
        });

        submitData();
        showData();
    });

    function resetForm() {
        $('#formTransaksi')[0].reset();
    }

    function showData() {
        $.ajax({
            url: baseUrl + '/list',
            method: "GET",
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    var tbody = '';
                    response.data.forEach((transaction) => {
                        tbody += `
                            <tr>
                                <td>${transaction.invoice}</td>
                                <td>${transaction.nama_barang}</td>
                                <td><span class="badge bg-${transaction.jenis === 'masuk' ? 'success' : 'danger'}">${transaction.jenis === 'masuk' ? 'Masuk' : 'Keluar'}</span></td>
                                <td>${transaction.jenis === 'masuk' ? transaction.nama_supplier : transaction.nama_divisi}</td>
                                <td>${transaction.jumlah}</td>
                                <td>${transaction.keterangan}</td>
                                <td>${new Date(transaction.created_at).toLocaleString('id-ID')}</td>
                            </tr>
                        `;
                    });

                } else {
                    tbody = `<tr><td colspan="7" class="text-center">${response.message}</td></tr>`;
                }

                $('#table_transaksi tbody').html(tbody);
            },
        });
    }

    function submitData() {
        $('#formTransaksi').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: baseUrl + '/save',
                method: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function(response) {
                    if (response.status) {
                        $('#transaksiModal').modal('hide');
                        showData();
                        resetForm();
                        alert(response.message);
                    } else {
                        alert(response.message);
                    }
                },
            });
        });
    }
</script>

<?= $this->endSection() ?>