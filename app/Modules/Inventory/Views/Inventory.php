<?= $this->extend('App\Views\layouts\template') ?>

<?= $this->section('content') ?>


<div class="card-header bg-white py-8 px-4 border-bottom-0 d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-3">
        <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary">
            <i class="bi bi-building-fill fs-4"></i>
        </div>
        <div>
            <h3 class="mb-1 fw-bold text-dark">Manajemen Barang</h3>
            <p class="mb-0 text-muted small">Manajemen barang untuk kebutuhan transaksi barang.</p>
        </div>
    </div>
    <div>
        <div class="btn-group me-2" role="group" aria-label="View Toggle">
            <button onclick="showData('list')" class="btn btn-outline-primary btn-list">
                <i class="bi bi-list-ol"></i>
            </button>
            <button onclick="showData('card')" class="btn btn-outline-primary btn-card">
                <i class="bi bi-grid"></i>
            </button>
        </div>
        <button class="btn btn-primary rounded-pill px-4 shadow-sm btn-tambah" data-bs-toggle="modal" data-bs-target="#barangModal">
            <i class="bi bi-plus-lg me-2"></i>Tambah Barang
        </button>
    </div>
</div>

<table class="table table-hover align-middle mb-0" id="table_barang">
    <thead class="table-light">
        <tr>
            <th class="ps-4 py-3 border-0 small fw-bold text-uppercase">Produk</th>
            <th class="border-0 small fw-bold text-uppercase">Kategori</th>
            <th class="border-0 small fw-bold text-uppercase">Stok</th>
            <th class="border-0 small fw-bold text-uppercase">Harga Satuan</th>
            <th class="text-center border-0 small fw-bold text-uppercase">Aksi</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<div id="area_card" class="row" style="display:none;"></div>


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
            activateFields();
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

    function activateFields() {
        $('#nama_barang').attr('disabled', false);
        $('#deskripsi').attr('disabled', false);
        $('#kategori').attr('disabled', false);
        $('#harga').attr('disabled', false);
    }

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
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="position-relative me-3">
                                            <img src="uploads/${item.gambar}" alt="${item.nama_barang}" class="rounded-3 border shadow-sm" width="60" height="60" style="object-fit: cover;">
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold text-dark">${item.nama_barang}</h6>
                                            <small class="text-muted">${item.deskripsi}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 rounded-pill px-3">
                                        ${item.kategori}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="fw-bold text-success me-2">${item.stok}</span>
                                        <small class="text-muted">Unit</small>
                                    </div>
                                    <div class="progress" style="height: 4px; width: 80px;">
                                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="${item.stok}" aria-valuemin="0" aria-valuemax="100" style="width: ${item.stok}%"></div>
                                    </div>
                                </td>
                                <td class="fw-semibold text-dark">Rp. ${formatRupiah(item.harga)}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-light btn-detail text-info rounded-start-pill ps-3" title="Detail" data-id="${item.id}">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light btn-edit text-primary" title="Edit" data-id="${item.id}">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light btn-delete text-danger rounded-end-pill pe-3" title="Hapus" data-id="${item.id}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>`;
                        } else {
                            $('.btn-card').addClass('active');
                            $('.btn-list').removeClass('active');
                            content += `
                            <div class="col-md-6 col-lg-3">
                                <div class="card product-card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                                    <img src="uploads/${item.gambar}" alt="${item.nama_barang}" class="card-img" style="height: 200px; object-fit: cover;">
                                    <div class="card-img-overlay">
                                        <span class="badge bg-primary bg-opacity-75 text-white rounded-pill stock-badge px-3 py-2 shadow-sm">
                                            Stok: ${item.stok}
                                        </span>
                                    </div>
                                    <div class="card-body d-flex flex-column pt-4">
                                        <small class="text-muted mb-1">${item.kategori}</small>
                                        <h5 class="card-title fw-bold text-dark">${item.nama_barang}</h5>
                                        <div class="mt-auto d-flex justify-content-between align-items-center">
                                            <h6 class="fw-bold text-primary mb-0">Rp ${formatRupiah(item.harga)}</h6>
                                        </div>
                                    </div>
                                    <div class="card-footer bg-white border-top-0 d-flex justify-content-between py-3">
                                        <button class="btn btn-outline-primary rounded-pill w-100 me-2 btn-detail" data-id="${item.id}">
                                            Detail
                                        </button>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-light border rounded-circle me-1 text-secondary btn-edit" data-bs-toggle="tooltip" title="Edit" data-id="${item.id}">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <button type="button" class="btn btn-light border rounded-circle text-danger btn-delete" data-bs-toggle="tooltip" title="Hapus" data-id="${item.id}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
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