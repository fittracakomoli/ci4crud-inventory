<?= $this->extend('App\Views\layouts\template') ?>

<?= $this->section('content') ?>

<div class="card-header bg-white py-4 px-4 border-bottom-0 d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-3">
        <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary">
            <i class="bi bi-building-fill fs-4"></i>
        </div>
        <div>
            <h3 class="mb-1 fw-bold text-dark">Manajemen Barang</h3>
            <p class="mb-0 text-muted small">Manajemen barang untuk kebutuhan transaksi barang.</p>
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
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#tab-form" role="tab" aria-selected="true">
            <span class="d-block d-sm-none"><i class="fab fa-wpforms"></i></span>
            <span class="d-none d-sm-block">Update</span>
        </a>
    </li>
</ul>

<div class="my-3">
    <input type="text" id="search-input" placeholder="Cari barang ..." class="form-control">
</div>

<div class="tab-content p-3 text-muted">
    <div class="tab-pane active" id="tab-data" role="tabpanel">
        <table class="table table-hover nowrap w-100" id="table_barang">
            <thead>
                <tr>
                    <th class="border-0 small fw-bold text-uppercase">Produk</th>
                    <th class="border-0 small fw-bold text-uppercase">Kategori</th>
                    <th class="border-0 small fw-bold text-uppercase">Stok</th>
                    <th class="border-0 small fw-bold text-uppercase">Harga Satuan</th>
                    <th class="text-center border-0 small fw-bold text-uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <div class="tab-pane" id="tab-form" role="tabpanel">
        <form action="" id="formBarang" enctype="multipart/form-data">
            <input type="hidden" name="id" id="id" value="" />
            <div class="mb-2">
                <label for="nama_barang" class="form-label">Nama Barang<sup class="text-danger fw-bold">*</sup></label>
                <input type="text" class="form-control" id="nama_barang" name="nama_barang" placeholder="Masukkan nama barang" autofocus required />
            </div>
            <div class="mb-2">
                <label for="deskripsi" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
            </div>
            <div class="row mb-2">
                <div class="col">
                    <label for="kategori" class="form-label">Kategori<sup class="text-danger fw-bold">*</sup></label>
                    <select class="form-select" id="kategori" name="kategori" required>
                        <option value="" selected disabled>Pilih Kategori</option>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?= $category['id']; ?>"><?= $category['nama']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col">
                    <label for="harga" class="form-label">Harga<sup class="text-danger fw-bold">*</sup></label>
                    <input type="text" class="form-control" id="harga" name="harga" placeholder="Masukkan harga barang" required />
                </div>
            </div>
            <div class="mb-2">
                <label for="gambar" class="form-label">Gambar</label>
                <input type="file" class="form-control" id="gambar" name="gambar" placeholder="Masukkan gambar barang" />
                <div class="mb-2 w-25">
                    <img src="img/default.png" class="img-thumbnail my-2 img-preview" alt="">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>

<!-- Detail Modal -->
<div class="modal fade" id="barangModal" tabindex="-1" aria-labelledby="barangModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div id="detailModal"></div>
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
            resetForm();
            $('.img-preview').attr('src', 'img/default.png');
        });

        $(document).on('change', '#gambar', function() {
            const gambar = document.querySelector('#gambar');
            const imgPreview = document.querySelector('.img-preview');

            const fileGambar = new FileReader();
            fileGambar.readAsDataURL(gambar.files[0]);

            fileGambar.onload = function(e) {
                imgPreview.src = e.target.result;
            }
        });

        $(document).on('click', '.btn-edit', function() {
            let id = $(this).data('id');

            $.ajax({
                url: baseUrl + '/detail?id=' + id,
                method: 'GET',
                success: function(response) {
                    if (response.status) {
                        let barang = response.data;
                        $('#tab a[href="#tab-form"]').tab('show');
                        $('#id').val(barang.id);
                        $('#nama_barang').val(barang.nama_barang).attr('disabled', false);
                        $('#deskripsi').val(barang.deskripsi).attr('disabled', false);
                        $('#kategori').val(barang.id_kategori).attr('disabled', false);
                        $('#harga').val(barang.harga).attr('disabled', false);
                        // $('#gambar').val(barang.gambar);
                        $('#gambar').show();
                        $('.img-preview').attr('src', 'uploads/' + barang.gambar);
                    } else {
                        alert(response.message);
                    }
                }
            });
        });

        $(document).on('click', '.btn-detail', function() {
            let id = $(this).data('id');

            $.ajax({
                url: baseUrl + '/detail?id=' + id,
                method: 'GET',
                success: function(response) {
                    if (response.status) {
                        let barang = response.data;
                        let modalContent = `
                            <div class="modal-content border-0 shadow-lg">
                                <div class="modal-header bg-light">
                                    <h5 class="modal-title fw-semibold text-dark" id="barangModalLabel">
                                        <i class="bi bi-box-seam me-2"></i>Detail Barang
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                
                                <div class="modal-body p-4">
                                    <div class="row g-4">
                                        <div class="col-md-5">
                                            <div class="card border-0 shadow-sm h-100 d-flex align-items-center justify-content-center bg-light rounded-3 overflow-hidden">
                                                <img src="uploads/${barang.gambar}" 
                                                    alt="${barang.nama_barang}" 
                                                    class="img-fluid" 
                                                    style="max-height: 250px; object-fit: contain;">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-7">
                                            <span class="badge bg-primary bg-opacity-10 text-primary mb-2 px-3 py-2 rounded-pill">
                                                ${barang.kategori}
                                            </span>
                                            
                                            <h3 class="fw-bold text-dark mb-2">${barang.nama_barang}</h3>
                                            
                                            <h4 class="text-success fw-bold mb-3">
                                                Rp. ${formatRupiah(barang.harga)}
                                            </h4>

                                            <div class="mb-3">
                                                <p class="text-secondary mt-1" style="line-height: 1.6;">
                                                    ${barang.deskripsi}
                                                </p>
                                            </div>

                                            <div class="d-flex align-items-center p-3 bg-light rounded-3 border">
                                                <div class="flex-grow-1">
                                                    <small class="text-muted d-block">Stok Tersedia</small>
                                                    <span class="fw-bold text-dark fs-5">${barang.stok} Unit</span>
                                                </div>
                                                <div class="bg-white p-2 rounded-circle shadow-sm text-primary">
                                                    <i class="bi bi-boxes"></i> </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                        $('#detailModal').html(modalContent);
                        $('#barangModal').modal('show');
                    } else {
                        alert(response.message);
                    }
                }
            });
        });

        var table = $('#table_barang').DataTable({
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
                    "data": null,
                    "render": function(data, type, row) {
                        return `
                            <div class="d-flex align-items-center">
                                <div class="position-relative me-3">
                                    <img src="uploads/${row.gambar}" alt="${row.nama_barang}" class="rounded-3 border shadow-sm" width="60" height="60" style="object-fit: cover;">
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark">${row.nama_barang}</h6>
                                    <small class="text-muted">${row.deskripsi}</small>
                                </div>
                            </div>
                        `;
                    }
                },
                {
                    "data": "kategori",
                    "render": function(data, type, row) {
                        return `
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 rounded-pill px-3">
                                ${row.kategori}
                            </span>
                        `;
                    }
                },
                {
                    "data": "stok",
                    "render": function(data, type, row) {
                        return `
                            <div class="d-flex align-items-center">
                                <span class="fw-bold text-success me-2">${row.stok}</span>
                                <small class="text-muted">Unit</small>
                            </div>
                            <div class="progress" style="height: 4px; width: 80px;">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="${row.stok}" aria-valuemin="0" aria-valuemax="100" style="width: ${row.stok}%"></div>
                            </div>
                        `;
                    }
                },
                {
                    "data": "harga",
                    "render": function(data, type, row) {
                        return `Rp. ${formatRupiah(row.harga)}`;
                    }
                },
                {
                    "data": null,
                    "render": function(data, type, row) {
                        return `
                            <div class="text-center">
                                <button class="btn btn-sm btn-outline-info me-1 btn-detail" data-id="${row.id}" data-bs-toggle="modal" data-bs-target="#barangModal">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-primary me-1 btn-edit" data-id="${row.id}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger btn-delete" data-id="${row.id}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ],
            "paging": true,
            "responsive": true,
            "lengthMenu": [ [10, 25, 50], [10, 25, 50] ],
            "searching": false,
        });

        $('#search-input').on('keyup', function() {
            table.search(this.value).draw();
        });

        submitData();
        deleteData();
    });

    function resetForm() {
        $('#formBarang')[0].reset();
    }

    function submitData() {
        $('#formBarang').off('submit').on('submit', function(e) {
            e.preventDefault();

            let url;
            if ($('#id').val()) {
                url = baseUrl + '/update';
            } else {
                url = baseUrl + '/create';
            }

            let formData = new FormData(this);

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

            $.ajax({
                url: url,
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
                            $('#table_barang').DataTable().ajax.reload();
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

        function deleteData() {
            $(document).off('click', '.btn-delete').on('click', '.btn-delete', function() {
                let id = $(this).data('id');

                Swal.fire({
                    title: "Apakah Anda yakin?",
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Sedang Menghapus...',
                            text: 'Mohon tunggu',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        $.ajax({
                            url: baseUrl + '/delete',
                            method: 'POST',
                            data: {
                                id: id
                            },
                            dataType: 'json',
                            success: function(response) {
                                if (response.status) {
                                    Swal.fire({
                                        title: "Terhapus!",
                                        text: response.message,
                                        icon: "success"
                                    }).then(() => {
                                        $('#table_barang').DataTable().ajax.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        title: "Gagal!",
                                        text: response.message,
                                        icon: "error"
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    title: "Error!",
                                    text: "Gagal menghubungi server.",
                                    icon: "error"
                                });
                            }
                        });
                    }
                });
            });
        }

    function formatRupiah(angka) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return rupiah;
    }
</script>

<?= $this->endSection() ?>