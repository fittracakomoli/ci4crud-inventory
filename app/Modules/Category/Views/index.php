<?= $this->extend('App\Views\layouts\template') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row">
        <div class="col-md-12 mt-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>Category Management</h3>
                    <p>Manage your categories for your inventory system.</p>
                </div>
                <div class="mb-4 text-end">
                    <button type="button" class="btn btn-primary btn-tambah" data-bs-toggle="modal" data-bs-target="#kategoriModal">
                        Tambah Kategori
                    </button>
                </div>
            </div>

            <table class="table table-hover" id="table_kategori">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Nama Kategori</th>
                        <th scope="col">Keterangan</th>
                        <th scope="col" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

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
        showData();
    });

    function resetForm() {
        $('#formKategori')[0].reset();
    }

    function showData() {
        $.ajax({
            url: baseUrl + '/list',
            method: "GET",
            dataType: "json",
            success: function(response) {
                if (response.status) {
                    var tbody = '';
                    response.data.forEach((category, index) => {
                        tbody += `
                            <tr>
                                <th scope="row">${index + 1}</th>
                                <td>${category.nama}</td>
                                <td>${category.keterangan}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary btn-edit" data-id="${category.id}">Edit</button>
                                    <button class="btn btn-sm btn-danger btn-delete" data-id="${category.id}">Delete</button>
                                </td>
                            </tr>
                        `;
                    });

                } else {
                    tbody = `<tr><td colspan="5" class="text-center">${response.message}</td></tr>`;
                }

                $('#table_kategori tbody').html(tbody);
            },
        });
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