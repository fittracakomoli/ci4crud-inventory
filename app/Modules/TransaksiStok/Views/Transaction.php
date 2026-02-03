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
</div>

<ul id="tab" class="nav nav-tabs nav-justified" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#tab-data" role="tab" aria-selected="false">
            <span class="d-block d-sm-none"><i class="fas fa-table"></i></span>
            <span class="d-none d-sm-block">Data</span>
        </a>
    </li>
    <?php if (auth()->user()->can('manage.transaction')) : ?>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#tab-form" role="tab" aria-selected="true">
            <span class="d-block d-sm-none"><i class="fab fa-wpforms"></i></span>
            <span class="d-none d-sm-block">Update</span>
        </a>
    </li>
    <?php endif; ?>
</ul>

<div class="mb-3">
    <input type="text" id="search-input" placeholder="Cari transaksi ..." class="form-control">
</div>

<div class="tab-content p-3 text-muted">
    <div class="tab-pane active" id="tab-data" role="tabpanel">
        <table class="table table-hover nowrap w-100" id="table_transaksi">
            <thead>
                <tr>
                    <th class="ps-4">Invoice ID</th>
                    <th>Nama Barang</th>
                    <th class="text-center">Jenis</th>
                    <th>Dari/Ke</th>
                    <th class="text-center">Jumlah</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <div class="tab-pane" id="tab-form" role="tabpanel">
        <form action="" id="formTransaksi" enctype="multipart/form-data">
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
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>

<script>
    var baseUrl = window.location.href;

    $(document).ready(function() {
        $('#tab a').on('click', function(e) {
            e.preventDefault();
            $(this).tab('show');
            $('#stok').addClass('d-none');
            $('#supplier').addClass('d-none');
            $('#divisi').addClass('d-none');
            resetForm();
        });

        $('#table_transaksi').DataTable({
            "serverSide": true,
            "processing": true,
            "ajax": {
                "url": baseUrl + '/list',
                "type": "GET",
                "data": function(d) {
                    d.search.value = $('#search-input').val();
                }
            },
            "columns": [{
                    "data": "invoice",
                    "render": function(data, type, row) {
                        return `<code class="text-primary fw-bold">${row.invoice}</code>`;
                    }
                },
                {
                    "data": "nama_barang",
                    "render": function(data, type, row) {
                        return `
                        <div class="fw-bold text-dark">${row.nama_barang}</div>
                        <div class="small text-muted">${row.keterangan}</div>
                    `;
                    }
                },
                {
                    "data": "jenis",
                    "render": function(data, type, row) {
                        if (row.jenis === 'masuk') {
                            return `
                            <div class="text-center">
                                <span class="badge rounded-pill border bg-success-subtle text-success border-success px-3">Masuk</span>
                            </div>`;
                        } else {
                            return `<div class="text-center">
                                <span class="badge rounded-pill border bg-danger-subtle text-danger border-danger px-3">Keluar</span>
                            </div>`;
                        }
                    }
                },
                {
                    "data": null,
                    "render": function(data, type, row) {
                        return row.jenis === 'masuk' ? row.nama_supplier : row.nama_divisi;
                    }
                },
                {
                    "data": "jumlah",
                    "render": function(data, type, row) {
                        return `
                        <div class="text-center">
                            <span class="badge bg-secondary rounded-circle">${row.jumlah}</span>
                        
                        </div>`;
                    }
                },
                {
                    "data": "created_at",
                    "render": function(data, type, row) {
                        var date = new Date(row.created_at);
                        return `<div class="small fw-semibold text-dark">${date.toLocaleString('id-ID')}</div>`;
                    }
                }
            ],
            "paging": true,
            "responsive": true,
            "lengthMenu": [ [5, 10, 50, 100], [5, 10, 50, 100] ],
            "searching": false,
        });

        $('#search-input').on('keyup', function() {
            $('#table_transaksi').DataTable().ajax.reload();
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
    });

    function resetForm() {
        $('#formTransaksi')[0].reset();
    }

    function submitData() {
        $('#formTransaksi').on('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Sedang Memproses',
                text: 'Mohon tunggu sebentar...',
                icon: 'info',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            var formData = new FormData(this);

            $.ajax({
                url: baseUrl + '/save',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message,
                            confirmButtonText: 'Oke'
                        }).then(() => {
                            $('#tab a[href="#tab-data"]').tab('show');
                            resetForm();
                            $('#table_transaksi').DataTable().ajax.reload();
                        });

                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.message,
                            confirmButtonText: 'Tutup'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Gagal menghubungi server. Silakan coba lagi.',
                        confirmButtonText: 'Tutup'
                    });
                }
            });
        });
    }
</script>

<?= $this->endSection() ?>