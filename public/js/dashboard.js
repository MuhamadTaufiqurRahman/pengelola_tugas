// Auto-submit form saat select berubah
document.getElementById('statusSelect').addEventListener('change', function () {
    document.getElementById('filterForm').submit();
});

document.getElementById('prioritySelect').addEventListener('change', function () {
    document.getElementById('filterForm').submit();
});
document.getElementById('departementSelect').addEventListener('change', function () {
    document.getElementById('filterForm').submit();
});
document.getElementById('sortSelect').addEventListener('change', function () {
    document.getElementById('filterForm').submit();
});

// Auto-submit filter saat select berubah
document.querySelectorAll('select[name="status"], select[name="deadline_filter"], select[name="sort"]').forEach(
    select => {
        select.addEventListener('change', function () {
            this.form.submit();
        });
    });

$(document).ready(function () {
    var table = $('#tasks-table').DataTable({
        // KONFIGURASI UTAMA:
        "searching": true,      // AKTIFKAN search DataTables
        "ordering": false,      // NONAKTIFKAN sorting DataTables

        "paging": true,         // Pagination aktif
        "info": true,           // Info aktif
        "pageLength": 10,
        "lengthMenu": [10, 25, 50, 100],
        "responsive": true,

        "language": {
            "search": "Search tasks:", // Label search
            "lengthMenu": "Show _MENU_ entries",
            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
            "infoEmpty": "No tasks available",
            "infoFiltered": "(filtered from _MAX_ total tasks)",
            "paginate": {
                "first": "First",
                "last": "Last",
                "next": "Next",
                "previous": "Previous"
            }
        },

        // Column definitions
        "columnDefs": [
            {
                "targets": '_all', // Semua kolom non-sortable
                "orderable": false
            },
            {
                "searchable": false,
                "targets": [0, 7] // Kolom No dan Actions tidak bisa di-search
            }
        ],

        // Draw callback untuk update nomor urut
        "drawCallback": function (settings) {
            var api = this.api();
            var rows = api.rows({ page: 'current' }).nodes();
            var start = api.page.info().start;

            $(rows).each(function (index) {
                var rowNumber = start + index + 1;
                $(this).find('td:eq(0) div').text(rowNumber);
            });
        }
    });

    // OPTIONAL: Pindahkan search box ke posisi yang diinginkan
    setTimeout(function () {
        var searchInput = $('.dataTables_filter input');
        searchInput.attr('placeholder', 'Search in table...');
        searchInput.addClass('px-4 py-2 border rounded-lg');

        // Pindahkan search box ke atas tabel
        $('.dataTables_filter').addClass('mb-4');
    }, 100);
});
