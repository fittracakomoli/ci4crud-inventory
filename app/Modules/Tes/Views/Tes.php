<?= $this->extend('App\Views\layouts\template') ?>

<?= $this->section('content') ?>

    <h2 class="mt-4">Data User (Dummy)</h2>

    <div class="my-3">
        <input type="text" id="search-input" placeholder="Cari user ..." class="form-control">
    </div>

    <table class="table table-hover nowrap w-100" id="table_tes">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <!-- <div class="pagination">
        <button id="btn-prev" onclick="changePage('prev')">&laquo; Prev</button>
        
        <span id="pagination-numbers" style="display:flex; gap:5px;"></span>

        <button id="btn-next" onclick="changePage('next')">Next &raquo;</button>

        <span id="pagination-info" style="margin-left: 10px;"></span>
    </div> -->

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
    
    <script>
        var baseUrl = window.location.href;

        // let currentPage = 1;

        $(document).ready(function() {
            // loadData(currentPage);

            var table = $('#table_tes').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": baseUrl + '/get-data',
                    "type": "POST",
                    "data": function(d) {
                        d.search.value = $('#search-input').val();
                    }
                },
                "columns": [
                    { "data": "id" },
                    { "data": "nama" },
                    { "data": "email" },
                    { 
                        "data": "role",
                        "render": function(data, type, row) {
                            if(data === 'admin') {
                                return '<span style="color:red; font-weight:bold">Admin</span>';
                            }
                            return data;
                        }
                    }
                ],
                "paging": true,
                "responsive": true,
                "lengthMenu": [ [10, 25, 50], [10, 25, 50] ],
                "searching": false,
            });

            $('#search-input').on('keyup', function() {
                table.search(this.value).draw();
            });
        });

        // function loadData(page) {
        //     $.ajax({
        //         url: '/tes/get-data',
        //         type: 'GET',
        //         data: { page: page },
        //         dataType: 'json',
        //         success: function(response) {
        //             let rows = '';
        //             if (response.data.length > 0) {
        //                 $.each(response.data, function(key, item) {
        //                     rows += `<tr>
        //                         <td>${item.id}</td>
        //                         <td>${item.nama}</td>
        //                         <td>${item.email}</td>
        //                         <td>${item.role}</td>
        //                     </tr>`;
        //                 });
        //             } else {
        //                 rows = '<tr><td colspan="4">Data Kosong</td></tr>';
        //             }
        //             $('#table-body').html(rows);

        //             currentPage = response.pagination.current_page;
        //             let totalPages = response.pagination.total_pages;

        //             let numbersHtml = '';
        //             let startPage, endPage;

        //             if (totalPages <= 3) {
        //                 startPage = 1;
        //                 endPage = totalPages;
        //             } else {
        //                 if (currentPage <= 1) {
        //                     startPage = 1;
        //                     endPage = 3;
        //                 }
        //                 else if (currentPage >= totalPages) {
        //                     startPage = totalPages - 2;
        //                     endPage = totalPages;
        //                 } 
        //                 else {
        //                     startPage = currentPage - 1;
        //                     endPage = currentPage + 1;
        //                 }
        //             }

        //             for (let i = startPage; i <= endPage; i++) {
        //                 let activeClass = (i === currentPage) ? 'active' : '';
        //                 numbersHtml += `<button class="${activeClass}" onclick="loadData(${i})">${i}</button>`;
        //             }


        //             $('#pagination-numbers').html(numbersHtml);

        //             $('#btn-prev').prop('disabled', !response.pagination.has_prev);
        //             $('#btn-next').prop('disabled', !response.pagination.has_next);

        //             $('#pagination-info').text(`Halaman ${currentPage} dari ${totalPages}`);
        //         },
        //         error: function(xhr, status, error) {
        //             alert('Error: ' + error);
        //         }
        //     });
        // }

        // function changePage(direction) {
        //     if (direction === 'next') {
        //         loadData(currentPage + 1);
        //     } else if (direction === 'prev') {
        //         loadData(currentPage - 1);
        //     }
        // }
    </script>

<?= $this->endSection() ?>