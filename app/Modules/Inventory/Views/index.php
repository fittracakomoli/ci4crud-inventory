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
                    <div class="btn-group me-2" role="group" aria-label="View Toggle">
                        <button onclick="showData('list')" class="btn btn-outline-primary btn-list">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor">
                                <path d="M280-600v-80h560v80H280Zm0 160v-80h560v80H280Zm0 160v-80h560v80H280ZM160-600q-17 0-28.5-11.5T120-640q0-17 11.5-28.5T160-680q17 0 28.5 11.5T200-640q0 17-11.5 28.5T160-600Zm0 160q-17 0-28.5-11.5T120-480q0-17 11.5-28.5T160-520q17 0 28.5 11.5T200-480q0 17-11.5 28.5T160-440Zm0 160q-17 0-28.5-11.5T120-320q0-17 11.5-28.5T160-360q17 0 28.5 11.5T200-320q0 17-11.5 28.5T160-280Z" />
                            </svg>
                        </button>
                        <button onclick="showData('card')" class="btn btn-outline-primary btn-card">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor">
                                <path d="M120-200v-240h320v240H120Zm400 0v-240h320v240H520Zm-320-80h160v-80H200v80Zm400 0h160v-80H600v80ZM120-520v-240h320v240H120Zm400 0v-240h320v240H520Zm-320-80h160v-80H200v80Zm80 280Zm400 0ZM280-640Z" />
                            </svg>
                        </button>
                    </div>
                    <button type="button" class="btn btn-primary btn-tambah" data-bs-toggle="modal" data-bs-target="#barangModal">
                        Tambah Barang
                    </button>
                </div>
            </div>

            <table id="table_barang" class="table table-hover" style="display:none;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>

            <div id="area_card" class="row" style="display:none;"></div>
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
                        <label for="kategori" class="form-label">Kategori<sup class="text-danger fw-bold">*</sup></label>
                        <select class="form-select" id="kategori" name="kategori" required>
                            <option value="" selected disabled>Pilih Kategori</option>
                            <?php foreach ($categories as $category) : ?>
                                <option value="<?= $category['id']; ?>"><?= $category['nama']; ?></option>
                            <?php endforeach; ?>
                        </select>
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
                        $('#nama_barang').val(barang.nama_barang).attr('disabled', false);
                        $('#deskripsi').val(barang.deskripsi).attr('disabled', false);
                        $('#kategori').val(barang.id_kategori).attr('disabled', false);
                        $('#stok').val(barang.stok).attr('disabled', false);
                        $('#harga').val(barang.harga).attr('disabled', false);
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
                        $('#nama_barang').val(barang.nama_barang).attr('disabled', true);
                        $('#deskripsi').val(barang.deskripsi).attr('disabled', true);
                        $('#kategori').val(barang.id_kategori).attr('disabled', true);
                        $('#stok').val(barang.stok).attr('disabled', true);
                        $('#harga').val(barang.harga).attr('disabled', true);
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

    function showData(viewType = 'list') {
        $.ajax({
            url: baseUrl + '/list',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                let content = '';

                if (response.status) {
                    $.each(response.data, function(index, item) {
                        if (viewType === 'list') {
                            $('.btn-list').addClass('active');
                            $('.btn-card').removeClass('active');
                            content += `
                            <tr>
                                <th scope="row">${index + 1}</th>
                                <td><img src="uploads/${item.gambar}" alt="${item.nama_barang}" width="100" class="img-thumbnail" /></td>
                                <td>${item.nama_barang}</td>
                                <td>${item.kategori}</td>
                                <td class="text-center">${item.stok}</td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info btn-detail" data-id="${item.id}">Detail</button>
                                    <button class="btn btn-sm btn-warning btn-edit" data-id="${item.id}">Edit</button>
                                    <button class="btn btn-sm btn-danger btn-delete" data-id="${item.id}">Hapus</button>
                                </td>
                            </tr>`;
                        } else {
                            $('.btn-card').addClass('active');
                            $('.btn-list').removeClass('active');
                            content += `
                            <div class="col-md-3 mb-4">
                                <div class="card h-100">
                                    <img src="uploads/${item.gambar}" class="card-img-top" alt="${item.nama_barang}" style="height: 200px; object-fit: cover;">
                                    <div class="card-body">
                                        <h5 class="card-title">${item.nama_barang}</h5>
                                        <p class="card-text text-muted">${item.kategori} | Stok: ${item.stok}</p>
                                    </div>
                                    <div class="card-footer bg-transparent border-top-0 text-center">
                                        <button class="btn btn-sm btn-info btn-detail" data-id="${item.id}">Detail</button>
                                        <button class="btn btn-sm btn-warning btn-edit" data-id="${item.id}">Edit</button>
                                        <button class="btn btn-sm btn-danger btn-delete" data-id="${item.id}">Hapus</button>
                                    </div>
                                </div>
                            </div>`;
                        }
                    });
                } else {
                    content = viewType === 'list' ?
                        `<tr><td colspan="6" class="text-center">${response.message}</td></tr>` :
                        `<div class="col-12 text-center"><p>${response.message}</p></div>`;
                }

                if (viewType === 'list') {
                    $('#area_card').hide();
                    $('#table_barang').show();
                    $('#table_barang tbody').html(content);
                } else {
                    $('#table_barang').hide();
                    $('#area_card').show().html(content);
                }
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