$(document).ready(function() {
    $('#datatablesSimple').DataTable({
        "pageLength": 10,
        "lengthMenu": [10, 25, 50, 100],
        "columnDefs": [
            { "orderable": false, "targets": -1 } // "Actions" column is the last column
        ]
    });
});