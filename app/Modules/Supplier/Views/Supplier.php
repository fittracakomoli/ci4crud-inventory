<?= $this->extend('App\Views\layouts\template') ?>

<?= $this->section('content') ?>


<div class="card-header bg-white py-4 px-4 border-bottom-0 d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-3">
        <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary">
            <i class="bi bi-building-fill fs-4"></i>
        </div>
        <div>
            <h5 class="mb-1 fw-bold text-dark">Manajemen Supplier</h5>
            <p class="mb-0 text-muted small">Manajemen supplier untuk stok sistem inventaris.</p>
        </div>
    </div>
    <button class="btn btn-primary rounded-pill px-4 shadow-sm btn-tambah" data-bs-toggle="modal" data-bs-target="#supplierModal">
        <i class="bi bi-plus-lg me-2"></i>Tambah Supplier
    </button>
</div>

<table class="table table-hover align-middle mb-0" id="table_supplier">
    <thead class="table-light">
        <tr>
            <th class="ps-4 text-uppercase small fw-bold border-0">No</th>
            <th class="text-uppercase small fw-bold border-0">Nama Supplier</th>
            <th class="text-uppercase small fw-bold border-0">Kontak</th>
            <th class="text-uppercase small fw-bold border-0">Alamat</th>
            <th class="text-center text-uppercase small fw-bold border-0">Aksi</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>


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
                                <td class="ps-4 fw-bold text-muted">${index + 1}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-initial rounded-circle bg-warning bg-opacity-10 text-warning fw-bold d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                            ${supplier.nama_supplier.charAt(0).toUpperCase()}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark">${supplier.nama_supplier}</h6>
                                            <small class="text-muted">ID: SUP-${supplier.id}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center text-muted">
                                        <div class="bg-light rounded-circle p-2 me-2">
                                            <i class="bi bi-envelope text-primary"></i>
                                        </div>
                                        <span class="text-dark">${supplier.kontak}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center text-muted">
                                        <i class="bi bi-geo-alt-fill me-2 text-danger"></i>
                                        <span>${supplier.alamat}</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-light text-primary border-0 btn-edit" data-id="${supplier.id}" data-bs-toggle="tooltip" title="Edit">
                                            <i class="bi bi-pencil-square fs-6"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light text-danger border-0 btn-delete" data-id="${supplier.id}" data-bs-toggle="tooltip" title="Hapus">
                                            <i class="bi bi-trash fs-6"></i>
                                        </button>
                                    </div>
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