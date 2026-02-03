<?= $this->extend('App\Views\layouts\template') ?>

<?= $this->section('content') ?>

<div class="card-header bg-white py-4 px-4 border-bottom-0 d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-3">
        <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary">
            <i class="bi bi-building-fill fs-4"></i>
        </div>
        <div>
            <h3 class="mb-1 fw-bold text-dark">Manajemen Kategori</h3>
            <p class="mb-0 text-muted small">Kelola kategori produk dan informasi terkait.</p>
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
    <?php if (auth()->user()->can('manage.category')) : ?>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#tab-form" role="tab" aria-selected="true">
            <span class="d-block d-sm-none"><i class="fab fa-wpforms"></i></span>
            <span class="d-none d-sm-block">Update</span>
        </a>
    </li>
    <?php endif; ?>
</ul>

<div class="my-3">
    <input type="text" id="search-input" placeholder="Cari kategori ..." class="form-control">
</div>

<div class="tab-content p-3 text-muted">
    <div class="tab-pane active" id="tab-data" role="tabpanel">
        <table class="table table-hover nowrap w-100" id="table_kategori">
            <thead>
                <tr>
                    <th>Nama Kategori</th>
                    <th>Keterangan</th>
                    <?php if (auth()->user()->can('manage.category')) : ?>
                    <th class="text-center" style="width: 20%;">Aksi</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <div class="tab-pane" id="tab-form" role="tabpanel">
        <form action="" id="formKategori" enctype="multipart/form-data">
            <input type="hidden" name="id" id="id" value="" />
            <div class="mb-2">
                <label for="nama" class="form-label">Nama Kategori<sup class="text-danger fw-bold">*</sup></label>
                <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama kategori" autofocus required />
            </div>
            <div class="mb-2">
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
            resetForm();
        });

        $(document).on('click', '.btn-edit', function() {
            let id = $(this).data('id');

            $.ajax({
                url: baseUrl + '/detail?id=' + id,
                method: 'GET',
                success: function(response) {
                    if (response.status) {
                        let category = response.data;
                        $('#tab a[href="#tab-form"]').tab('show');
                        $('#id').val(category.id);
                        $('#nama').val(category.nama);
                        $('#keterangan').val(category.keterangan);
                    } else {
                        alert(response.message);
                    }
                }
            });
        });

        submitData();
        deleteData();

        $('#table_kategori').DataTable({
            "serverSide": true,
            "processing": true,
            "ajax": {
                "url": baseUrl + '/list',
                "type": "GET",
                "data": function(d) {
                    d.search.value = $('#search-input').val();
                }
            },
            "columns": [
                {
                    "data": "nama"
                },
                {
                    "data": "keterangan"
                }
                <?php if (auth()->user()->can('manage.category')) : ?>
                ,
                {
                    "data": null,
                    "render": function(data, type, row) {
                        return `
                            <div class="text-center">
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
                <?php endif; ?>
            ],
            "paging": true,
            "responsive": true,
            "lengthMenu": [ [5, 10, 50, 100], [5, 10, 50, 100] ],
            "searching": false,
        });

        $('#search-input').on('keyup', function() {
            $('#table_kategori').DataTable().ajax.reload();
        });
    });

    function resetForm() {
        $('#formKategori')[0].reset();
    }

    function submitData() {
        $('#formKategori').off('submit').on('submit', function(e) {
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
                            $('#table_kategori').DataTable().ajax.reload();
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
                                    $('#table_kategori').DataTable().ajax.reload();
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
</script>

<?= $this->endSection() ?>