<?= $this->extend('App\Views\layouts\template') ?>

<?= $this->section('content') ?>


<div class="card-header bg-white py-4 px-4 border-bottom-0 d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-3">
        <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary">
            <i class="bi bi-building-fill fs-4"></i>
        </div>
        <div>
            <h5 class="mb-1 fw-bold text-dark">Manajemen Transaksi</h5>
            <p class="mb-0 text-muted small">Mengelola transaksi keluar masuk barang.</p>
        </div>
    </div>
    <button class="btn btn-primary rounded-pill px-4 shadow-sm btn-tambah" data-bs-toggle="modal" data-bs-target="#transaksiModal">
        <i class="bi bi-plus-lg me-2"></i>Tambah Transaksi
    </button>
</div>

<table class="table table-hover align-middle mb-0" id="table_transaksi">
    <thead class="table-light">
        <tr>
            <th class="ps-4">Invoice ID</th>
            <th>Nama Barang</th>
            <th>Jenis</th>
            <th>Dari/Ke</th>
            <th class="text-center">Jumlah</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>


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
                                <td class="ps-4"><code class="text-primary fw-bold">${transaction.invoice}</code></td>
                                <td>
                                    <div class="fw-bold text-dark">${transaction.nama_barang}</div>
                                    <div class="small text-muted">${transaction.keterangan}</div>
                                </td>
                                <td>
                                    <span class="badge rounded-pill border bg-success-subtle text-success border-success px-3 ${transaction.jenis === 'masuk' ? 'bg-success-subtle text-success border-success' : 'bg-danger-subtle text-danger border-danger'}">${transaction.jenis === 'masuk' ? 'Masuk' : 'Keluar'}</span>
                                </td>
                                <td class="text-muted small">${transaction.jenis === 'masuk' ? transaction.nama_supplier : transaction.nama_divisi}</td>
                                <td class="text-center"><span class="badge bg-secondary rounded-circle">${transaction.jumlah}</span></td>
                                <td>
                                    <div class="small fw-semibold text-dark">${new Date(transaction.created_at).toLocaleString('id-ID')}</div>
                                </td>
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