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
    <button class="btn btn-primary rounded-pill px-4 shadow-sm btn-tambah" data-bs-toggle="modal" data-bs-target="#kategoriModal">
        <i class="bi bi-plus-lg me-2"></i>Tambah Kategori
    </button>
</div>

<table class="table table-striped py-4" id="table_kategori">
    <thead>
        <tr>
            <th style="width: 5%;">No</th>
            <th>Nama Kategori</th>
            <th>Keterangan</th>
            <th class="text-center" style="width: 20%;">Aksi</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<!-- Create Modal -->

<form id="formKategori">
    <div class="modal fade" id="kategoriModal" tabindex="-1" aria-labelledby="kategoriModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="kategoriModalLabel">Form Tambah Kategori</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id" value="" />
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Kategori<sup class="text-danger fw-bold">*</sup></label>
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama kategori" autofocus required />
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
        $('.btn-tambah').click(function() {
            $('#kategoriModalLabel').text('Form Tambah Kategori');
            $('#id').val('');
            resetForm();
        });

        $('#table_kategori').DataTable({
            "ajax": {
                "url": baseUrl + '/list',
                "type": "GET"
            },
            "columns": [{
                    "data": null,
                    "render": function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                },
                {
                    "data": "nama"
                },
                {
                    "data": "keterangan"
                },
                {
                    "data": null,
                    "render": function(data, type, row) {
                        return `
                            <div class="text-center">
                                <button class="btn btn-sm btn-outline-primary me-1 rounded-pill btn-edit" data-id="${row.id}">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-outline-danger rounded-pill btn-delete" data-id="${row.id}">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </div>
                        `;
                    }
                }
            ],
            paging: true,
            searching: true,
            ordering: true,
            info: false,
            "lengthMenu": [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "All"]
            ],
        });

        $(document).on('click', '.btn-edit', function() {
            let id = $(this).data('id');

            $.ajax({
                url: baseUrl + '/detail?id=' + id,
                method: 'GET',
                success: function(response) {
                    if (response.status) {
                        let category = response.data;
                        $('#id').val(category.id);
                        $('#nama').val(category.nama);
                        $('#keterangan').val(category.keterangan);
                        $('#kategoriModalLabel').text('Form Edit Kategori');
                        $('#kategoriModal').modal('show');
                    } else {
                        alert(response.message);
                    }
                }
            });
        });

        submitData();
        deleteData();
    });

    function resetForm() {
        $('#formKategori')[0].reset();
    }

    function submitData() {
        $('#formKategori').submit(function(e) {
            e.preventDefault();

            if ($('#id').val()) {
                var url = baseUrl + '/update';
            } else {
                var url = baseUrl + '/create';
            }

            let formData = $(this).serialize();

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        $('#kategoriModal').modal('hide');
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

    function deleteData() {
        $(document).on('click', '.btn-delete', function() {
            let id = $(this).data('id');
            if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                $.ajax({
                    url: baseUrl + '/delete',
                    method: 'POST',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            showData();
                            alert(response.message);
                        } else {
                            alert(response.message);
                        }
                    }
                });
            }
        })
    }
</script>

<?= $this->endSection() ?>