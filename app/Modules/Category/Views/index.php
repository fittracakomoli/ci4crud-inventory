<?= $this->extend('App\Views\layouts\template') ?>

<?= $this->section('content') ?>

<div class="container">
    <div class="row">
        <div class="col-md-12 mt-4">
            <h3><?= $title ?></h3>
            <p>Manage category for your inventory system.</p>

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
                                    <button class="btn btn-sm btn-primary">Edit</button>
                                    <button class="btn btn-sm btn-danger">Delete</button>
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
</script>

<?= $this->endSection() ?>