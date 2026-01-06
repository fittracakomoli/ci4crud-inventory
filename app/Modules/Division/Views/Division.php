<?= $this->extend('App\Views\layouts\template') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row">
        <div class="col-md-12 mt-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>Division Management</h3>
                    <p>Manage your divisions for your transaction.</p>
                </div>
                <div class="mb-4 text-end">
                    <button type="button" class="btn btn-primary btn-tambah" data-bs-toggle="modal" data-bs-target="#divisiModal">
                        Tambah Divisi
                </div>
            </div>

            <table class="table table-hover" id="table_divisi">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Nama Divisi</th>
                        <th scope="col">Penanggung Jawab</th>
                        <th scope="col" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

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
                                <th scope="row">${index + 1}</th>
                                <td>${division.nama_divisi}</td>
                                <td>${division.pj}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary btn-edit" data-id="${division.id}">Edit</button>
                                    <button class="btn btn-sm btn-danger btn-delete" data-id="${division.id}">Delete</button>
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