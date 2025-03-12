import $ from 'jquery';
import DataTable from 'datatables.net-dt';
import 'datatables.net-dt/css/dataTables.dataTables.css';
import 'datatables.net-responsive';

$(document).ready(function() {
    $('#userTable').DataTable({
        "paging": true,
        "searching": true,
        "responsive": true,
        "autoWidth": false,
        "lengthMenu": [5, 10, 20, 25, 50, 100],
        "order": [[0, "desc"]],
    });
});
