<?= $this->extend('App\Views\layouts\template') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row">
        <div class="col-md-12 mt-4">
            <div>
                <div>
                    <h3>Inventory Management</h3>
                </div>
                <div>
                    <p>Manage your inventory items here.</p>
                </div>
            </div>

            <div class="my-4 text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#barangModal">
                    Tambah Barang
                </button>
            </div>

            <table class="table table-hover table-striped" id="table_barang">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Gambar</th>
                        <th scope="col">Nama Barang</th>
                        <th scope="col">Stok</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Modal -->

<form id="formBarang">
    <div class="modal fade" id="barangModal" tabindex="-1" aria-labelledby="barangModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="barangModalLabel">Form Tambah Barang</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_barang" class="form-label">Nama Barang<sup class="text-danger fw-bold">*</sup></label>
                        <input type="text" class="form-control" id="nama_barang" name="nama_barang" placeholder="Masukkan nama barang" autofocus />
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="stok" class="form-label">Stok<sup class="text-danger fw-bold">*</sup></label>
                        <input type="text" class="form-control" id="stok" name="stok" placeholder="Masukkan stok barang" />
                    </div>
                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga<sup class="text-danger fw-bold">*</sup></label>
                        <input type="text" class="form-control" id="harga" name="harga" placeholder="Masukkan harga barang" />
                    </div>
                    <div class="mb-3">
                        <label for="gambar" class="form-label">Gambar<sup class="text-danger fw-bold">*</sup></label>
                        <input type="text" class="form-control" id="gambar" name="gambar" placeholder="Masukkan gambar barang" />
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
                                <td><img src="${item.gambar}" alt="${item.nama_barang}" width="50" /></td>;
                                <td>${item.nama_barang}</td>;
                                <td>${item.stok}</td>;
                                <td>
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
</script>

<?= $this->endSection() ?>