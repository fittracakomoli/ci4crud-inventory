<?= $this->extend('App\Views\layouts\template') ?>

<?= $this->section('content') ?>


<div class="card-header bg-white py-4 px-4 border-bottom-0 d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-3">
        <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary">
            <i class="bi bi-building-fill fs-4"></i>
        </div>
        <div>
            <h5 class="mb-1 fw-bold text-dark">Manajemen Divisi</h5>
            <p class="mb-0 text-muted small">Manajemen divisi untuk kebutuhan transaksi barang.</p>
        </div>
    </div>
    <button class="btn btn-primary rounded-pill px-4 shadow-sm btn-tambah" data-bs-toggle="modal" data-bs-target="#divisiModal">
        <i class="bi bi-plus-lg me-2"></i>Tambah Divisi
    </button>
</div>

<table class="table table-hover align-middle mb-0" id="table_divisi">
    <thead class="table-light">
        <tr>
            <th class="ps-4 text-uppercase small fw-bold border-0">No</th>
            <th class="text-uppercase small fw-bold border-0">Nama Divisi</th>
            <th class="text-uppercase small fw-bold border-0">Penanggung Jawab (PIC)</th>
            <th class="text-center text-uppercase small fw-bold border-0">Aksi</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>


<!-- Create Modal -->

<form id="formDivisi">
    <div class="modal fade" id="divisiModal" tabindex="-1" aria-labelledby="divisiModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="divisiModalLabel">Form Tambah Divisi</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id" name="id" value="" />
                    <div class="mb-3">
                        <label for="nama_divisi" class="form-label">Nama Divisi<sup class="text-danger fw-bold">*</sup></label>
                        <input type="text" class="form-control" id="nama_divisi" name="nama_divisi" placeholder="Masukkan nama divisi" autofocus required />
                    </div>
                    <div class="mb-3">
                        <label for="pj" class="form-label">Penanggung Jawab</label>
                        <input type="text" class="form-control" id="pj" name="pj" placeholder="Masukkan nama penanggung jawab" autofocus required />
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
            $('#divisiModalLabel').text('Form Tambah Divisi');
        });

        $(document).on('click', '.btn-edit', function() {
            let id = $(this).data('id');

            $.ajax({
                url: baseUrl + '/detail?id=' + id,
                method: 'GET',
                success: function(response) {
                    if (response.status) {
                        let division = response.data;
                        $('#id').val(division.id);
                        $('#nama_divisi').val(division.nama_divisi);
                        $('#pj').val(division.pj);
                        $('#divisiModalLabel').text('Form Edit Divisi');
                        $('#divisiModal').modal('show');
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
        $('#formDivisi')[0].reset();
    }

    function showData() {
        $.ajax({
            url: baseUrl + '/list',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    var tbody = '';
                    $.each(response.data, function(index, division) {
                        tbody += `
                            <tr>
                                <td class="ps-4 fw-bold text-muted">${index + 1}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark">${division.nama_divisi}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center p-2 rounded-3 border border-light bg-light bg-opacity-50" style="max-width: 250px;">
                                        <div class="avatar-circle bg-white shadow-sm rounded-circle d-flex align-items-center justify-content-center me-3 text-primary fw-bold border" style="width: 40px; height: 40px;">
                                            ${division.pj.charAt(0).toUpperCase()}
                                        </div>
                                        <div class="lh-1">
                                            <h6 class="mb-1 text-dark fs-6">${division.pj}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group rounded-pill" role="group">
                                        <button type="button" class="btn btn-sm btn-light text-primary border-0 btn-edit" data-id="${division.id}" data-bs-toggle="tooltip" title="Edit">
                                            <i class="bi bi-pencil-square fs-6"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light text-danger border-0 btn-delete" data-id="${division.id}" data-bs-toggle="tooltip" title="Hapus">
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

                $('#table_divisi tbody').html(tbody);
            }
        });
    }

    function submitData() {
        $('#formDivisi').on('submit', function(e) {
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
                        $('#divisiModal').modal('hide');
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