<?= $this->extend('App\Views\layouts\template') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row">
        <div class="col-md-12 mt-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>Inventory Management</h3>
                    <p>Manage your inventory items here.</p>
                </div>
                <div class="mb-4 text-end">
                    <button type="button" class="btn btn-primary btn-tambah" data-bs-toggle="modal" data-bs-target="#barangModal">
                        Tambah Barang
                    </button>
                </div>
            </div>

            <table class="table table-hover" id="table_barang">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Gambar</th>
                        <th scope="col">Nama Barang</th>
                        <th scope="col">Stok</th>
                        <th scope="col" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Modal -->

<form id="formBarang" enctype="multipart/form-data">
    <div class="modal fade" id="barangModal" tabindex="-1" aria-labelledby="barangModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="barangModalLabel">Form Tambah Barang</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="id" value="" />
                    <div class="mb-3">
                        <label for="nama_barang" class="form-label">Nama Barang<sup class="text-danger fw-bold">*</sup></label>
                        <input type="text" class="form-control" id="nama_barang" name="nama_barang" placeholder="Masukkan nama barang" autofocus required />
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="stok" class="form-label">Stok<sup class="text-danger fw-bold">*</sup></label>
                        <input type="text" class="form-control" id="stok" name="stok" placeholder="Masukkan stok barang" required />
                    </div>
                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga<sup class="text-danger fw-bold">*</sup></label>
                        <input type="text" class="form-control" id="harga" name="harga" placeholder="Masukkan harga barang" required />
                    </div>
                    <div class="mb-3">
                        <label for="gambar" class="form-label">Gambar</label>
                        <input type="file" class="form-control" id="gambar" name="gambar" placeholder="Masukkan gambar barang" />
                        <div class="w-full mb-3">
                            <img src="img/default.png" class="img-thumbnail my-2 img-preview" alt="">
                        </div>
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
            $('#barangModalLabel').text('Form Tambah Barang');
            $('.modal-footer').show();
            $('#gambar').show();
            $('#id').val('');
            $('.img-preview').attr('src', '');
            resetForm();
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
                        $('#formBarang')[0].reset();
                        $('#id').val(barang.id);
                        $('#nama_barang').val(barang.nama_barang);
                        $('#deskripsi').val(barang.deskripsi);
                        $('#stok').val(barang.stok);
                        $('#harga').val(barang.harga);
                        // $('#gambar').val(barang.gambar);
                        $('#gambar').show();
                        $('.img-preview').attr('src', 'uploads/' + barang.gambar);
                        $('#barangModalLabel').text('Form Edit Barang');
                        $('.modal-footer').show();
                        $('#barangModal').modal('show');
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
                        $('#formBarang')[0].reset();
                        $('#id').val(barang.id);
                        $('#nama_barang').val(barang.nama_barang);
                        $('#deskripsi').val(barang.deskripsi);
                        $('#stok').val(barang.stok);
                        $('#harga').val(barang.harga);
                        // $('#gambar').val(barang.gambar);
                        $('#gambar').hide();
                        $('.img-preview').attr('src', 'uploads/' + barang.gambar);
                        $('#barangModalLabel').text('Detail Barang');
                        $('.modal-footer').hide();
                        $('#barangModal').modal('show');
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

    function showData() {
        $.ajax({
            url: baseUrl + '/list',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.status) {
                    var tbody = '';
                    $.each(response.data, function(index, item) {
                        tbody += `
                            <tr>;
                                <th scope="row">${index + 1}</th>;
                                <td><img src="uploads/${item.gambar}" alt="${item.nama_barang}" width="200" /></td>;
                                <td>${item.nama_barang}</td>;
                                <td>${item.stok}</td>;
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info btn-detail" data-id="${item.id}">Detail</button>
                                    <button class="btn btn-sm btn-warning btn-edit" data-id="${item.id}">Edit</button>
                                    <button class="btn btn-sm btn-danger btn-delete" data-id="${item.id}">Hapus</button>
                                </td>;
                            </tr>
                        `;
                    });
                } else {
                    tbody = `<tr><td colspan="5" class="text-center">${response.message}</td></tr>`;
                }

                $('#table_barang tbody').html(tbody);
            }
        });
    }

    function resetForm() {
        $('#formBarang')[0].reset();
    }

    function submitData() {
        $('#formBarang').on('submit', function(e) {
            e.preventDefault();

            if ($('#id').val()) {
                var url = baseUrl + '/update';
            } else {
                var url = baseUrl + '/create';
            }

            let formData = new FormData(this);

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status) {
                        $('#barangModal').modal('hide');
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