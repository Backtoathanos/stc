  @php
    $assetBase = '/stc/stc_payroll/public';
  @endphp
  <!-- SweetAlert2 -->
  <script src="{{ $assetBase }}/plugins/sweetalert2/sweetalert2.min.js"></script>
  <!-- jQuery -->
  <script src="{{ $assetBase }}/plugins/jquery/jquery.min.js"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="{{ $assetBase }}/plugins/jquery-ui/jquery-ui.min.js"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>
  <!-- Bootstrap 4 -->
  <script src="{{ $assetBase }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- ChartJS -->
  <script src="{{ $assetBase }}/plugins/chart.js/Chart.min.js"></script>
  <!-- Sparkline -->
  <script src="{{ $assetBase }}/plugins/sparklines/sparkline.js"></script>
  <!-- JQVMap -->
  <script src="{{ $assetBase }}/plugins/jqvmap/jquery.vmap.min.js"></script>
  <script src="{{ $assetBase }}/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
  <!-- jQuery Knob Chart -->
  <script src="{{ $assetBase }}/plugins/jquery-knob/jquery.knob.min.js"></script>
  <!-- daterangepicker -->
  <script src="{{ $assetBase }}/plugins/moment/moment.min.js"></script>
  <script src="{{ $assetBase }}/plugins/daterangepicker/daterangepicker.js"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="{{ $assetBase }}/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
  <!-- Summernote -->
  <script src="{{ $assetBase }}/plugins/summernote/summernote-bs4.min.js"></script>
  <!-- overlayScrollbars -->
  <script src="{{ $assetBase }}/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
  <!-- AdminLTE App -->
  <script src="{{ $assetBase }}/dist/js/adminlte.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="{{ $assetBase }}/dist/js/demo.js"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="{{ $assetBase }}/dist/js/pages/dashboard.js"></script>
  
  <!-- DataTables  & Plugins -->
  <script src="{{ $assetBase }}/plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="{{ $assetBase }}/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="{{ $assetBase }}/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="{{ $assetBase }}/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
  <script src="{{ $assetBase }}/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
  <script src="{{ $assetBase }}/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
  <script src="{{ $assetBase }}/plugins/jszip/jszip.min.js"></script>
  <script src="{{ $assetBase }}/plugins/pdfmake/pdfmake.min.js"></script>
  <script src="{{ $assetBase }}/plugins/pdfmake/vfs_fonts.js"></script>
  <script src="{{ $assetBase }}/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
  <script src="{{ $assetBase }}/plugins/datatables-buttons/js/buttons.print.min.js"></script>
  <script src="{{ $assetBase }}/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>

  <!-- Custom Helpers -->
  <script src="{{ $assetBase }}/dist/js/helpers.js"></script>

  <script>
    $(function () {
      if ($("#example1").length) {
        $("#example1").DataTable({
          "responsive": true, 
          "lengthChange": false, 
          "autoWidth": true,
          "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      }
      
      // Logout functionality
      $('#logoutBtn').on('click', function(e) {
        e.preventDefault();
        Swal.fire({
          title: 'Are you sure?',
          text: "You will be logged out of the system",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, logout!'
        }).then((result) => {
          if (result.isConfirmed) {
            // Create a form and submit it
            var form = $('<form>', {
              'method': 'POST',
              'action': '/stc/stc_payroll/logout'
            });
            form.append($('<input>', {
              'type': 'hidden',
              'name': '_token',
              'value': $('meta[name="csrf-token"]').attr('content')
            }));
            $('body').append(form);
            form.submit();
          }
        });
      });
    });
  </script>
  
  @stack('scripts')

