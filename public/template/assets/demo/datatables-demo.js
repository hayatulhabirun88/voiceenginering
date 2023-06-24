// Call the dataTables jQuery plugin
$(document).ready(function() {
  $('#dataTable').DataTable(
    {
      "pageLength": 20 // Panjang halaman default adalah 10
    }
  );
});
