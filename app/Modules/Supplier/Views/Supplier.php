<?= $this->extend('App\Views\layouts\template') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row">
        <div class="col-md-12 mt-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>Supplier Management</h3>
                    <p>Manage your suppliers for your transaction.</p>
                </div>
                <div class="mb-4 text-end">
                    <button type="button" class="btn btn-primary btn-tambah" data-bs-toggle="modal" data-bs-target="#supplierModal">
                        Tambah Supplier
                </div>
            </div>

            <table class="table table-hover" id="table_supplier">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Nama Supplier</th>
                        <th scope="col">Kontak</th>
                        <th scope="col">Alamat</th>
                        <th scope="col" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Modal -->

<form id="formSupplier">
    <div class="modal fade" id="supplierModal" tabindex="-1" aria-labelledby="supplierModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="supplierModalLabel">Form Tambah Supplier</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id" name="id" value="" />
                    <div class="mb-3">
                        <label for="nama_supplier" class="form-label">Nama Supplier<sup class="text-danger fw-bold">*</sup></label>
                        <input type="text" class="form-control" id="nama_supplier" name="nama_supplier" placeholder="Masukkan nama supplier" autofocus required />
                    </div>
                    <div class="mb-3">
                        <label for="kontak" class="form-label">Kontak<sup class="text-danger fw-bold">*</sup></label>
                        <input type="text" class="form-control" id="kontak" name="kontak" placeholder="Masukkan kontak" autofocus required />
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat</label>
                        <textarea class="form-control" id="alamat" name="alamat" placeholder="Masukkan alamat" rows="3"></textarea>
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
            resetForm();
            $('#supplierModalLabel').text('Form Tambah Supplier');
        });

        $(document).on('click', '.btn-edit', function() {
            let id = $(this).data('id');

            $.ajax({
                url: baseUrl + '/detail?id=' + id,
                method: 'GET',
                success: function(response) {
                    if (response.status) {
                        let supplier = response.data;
                        $('#id').val(supplier.id);
                        $('#nama_supplier').val(supplier.nama_supplier);
                        $('#kontak').val(supplier.kontak);
                        $('#alamat').val(supplier.alamat);
                        $('#supplierModalLabel').text('Form Edit Supplier');
                        $('#supplierModal').modal('show');
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
        $('#formSupplier')[0].reset();
    }

    function showData() {
        $.ajax({
            url: baseUrl + '/list',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    var tbody = '';
                    $.each(response.data, function(index, supplier) {
                        tbody += `
                            <tr>
                                <th scope="row">${index + 1}</th>
                                <td>${supplier.nama_supplier}</td>
                                <td>${supplier.kontak}</td>
                                <td>${supplier.alamat}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary btn-edit" data-id="${supplier.id}">Edit</button>
                                    <button class="btn btn-sm btn-danger btn-delete" data-id="${supplier.id}">Delete</button>
                                </td>
                            </tr>
                        `;
                    });
                } else {
                    tbody = `<tr><td colspan="5" class="text-center">${response.message}</td></tr>`;
                }

                $('#table_supplier tbody').html(tbody);
            }
        });
    }

    function submitData() {
        $('#formSupplier').on('submit', function(e) {
            e.preventDefault();

            if ($('#id').val()) {
                var url = baseUrl + '/update';
            } else {
                var url = baseUrl + '/create';
            }

            var formData = $(this).serialize();

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        $('#supplierModal').modal('hide');
                        resetForm();
                        showData();
                        alert(response.message);
                    } else {
                        alert(response.message);
                    }
                }
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