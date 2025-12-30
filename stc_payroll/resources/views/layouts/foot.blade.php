  <!-- SweetAlert2 -->
  <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
  <!-- jQuery -->
  <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button)
  </script>
  <!-- Bootstrap 4 -->
  <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <!-- ChartJS -->
  <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
  <!-- Sparkline -->
  <script src="{{ asset('plugins/sparklines/sparkline.js') }}"></script>
  <!-- JQVMap -->
  <script src="{{ asset('plugins/jqvmap/jquery.vmap.min.js') }}"></script>
  <script src="{{ asset('plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
  <!-- jQuery Knob Chart -->
  <script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
  <!-- daterangepicker -->
  <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
  <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
  <!-- Tempusdominus Bootstrap 4 -->
  <script src="{{ asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
  <!-- Summernote -->
  <script src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
  <!-- overlayScrollbars -->
  <script src="{{ asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
  <!-- AdminLTE App -->
  <script src="{{ asset('dist/js/adminlte.js') }}"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="{{ asset('dist/js/demo.js') }}"></script>
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="{{ asset('dist/js/pages/dashboard.js') }}"></script>
  
  <!-- DataTables  & Plugins -->
  <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
  <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
  <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
  <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

  <!-- Custom Helpers -->
  <script src="{{ asset('dist/js/helpers.js') }}"></script>

  <script>
    // Global AJAX setup - automatically include CSRF token in all AJAX requests
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    
    // Handle CSRF token expiration globally
    $(document).ajaxError(function(event, xhr, settings) {
      if (xhr.status === 419 || (xhr.responseJSON && xhr.responseJSON.message && xhr.responseJSON.message.includes('CSRF'))) {
        Swal.fire({
          icon: 'warning',
          title: 'Session Expired',
          html: 'Your session has expired. The page will be refreshed to get a new token.',
          confirmButtonText: 'Refresh Page',
          allowOutsideClick: false
        }).then(function() {
          window.location.reload();
        });
      }
    });
    
    $(function () {
      // Check if company is selected on page load
      var selectedCompanyId = '{{ session("selected_company_id") }}';
      if (!selectedCompanyId || selectedCompanyId === '') {
        // Show company selection modal
        loadCompaniesForModal();
        $('#companySelectionModal').modal({
          backdrop: 'static',
          keyboard: false
        });
      }
      
      // Function to load companies for the modal
      function loadCompaniesForModal() {
        $.ajax({
          url: window.appBaseUrl + '/company/get-user-companies',
          type: 'GET',
          success: function(response) {
            var html = '';
            if (response.success && response.data && response.data.length > 0) {
              response.data.forEach(function(company) {
                html += '<a href="#" class="list-group-item list-group-item-action company-select-item" data-company-id="' + company.id + '" data-company-name="' + company.name + '">';
                html += '<div class="d-flex w-100 justify-content-between">';
                html += '<h6 class="mb-1"><i class="fas fa-building mr-2"></i>' + company.name + '</h6>';
                html += '</div>';
                if (company.code) {
                  html += '<small class="text-muted">Code: ' + company.code + '</small>';
                }
                html += '</a>';
              });
            } else {
              html = '<div class="alert alert-warning">No companies available. Please contact your administrator.</div>';
            }
            $('#companySelectionList').html(html);
          },
          error: function() {
            $('#companySelectionList').html('<div class="alert alert-danger">Error loading companies. Please refresh the page.</div>');
          }
        });
      }
      
      // Handle company selection from modal
      $(document).on('click', '.company-select-item', function(e) {
        e.preventDefault();
        var companyId = $(this).data('company-id');
        var companyName = $(this).data('company-name');
        
        if (!companyId) {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Invalid company selected'
          });
          return;
        }
        
        // Show loading state
        $('#companySelectionList').html('<div class="text-center py-3"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i><p class="mt-2 text-muted">Setting company...</p></div>');
        
        $.ajax({
          url: window.appBaseUrl + '/company/set-company',
          type: 'POST',
          data: {
            company_id: companyId
          },
          success: function(response) {
            if (response.success) {
              Swal.fire({
                icon: 'success',
                title: 'Company Selected',
                text: 'Company "' + companyName + '" has been selected successfully.',
                timer: 1500,
                showConfirmButton: false
              }).then(function() {
                // Close modal and reload page
                $('#companySelectionModal').modal('hide');
                window.location.reload();
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: response.message || 'Failed to select company'
              });
              loadCompaniesForModal();
            }
          },
          error: function(xhr) {
            var message = 'Failed to select company';
            if (xhr.responseJSON && xhr.responseJSON.message) {
              message = xhr.responseJSON.message;
            }
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: message
            });
            loadCompaniesForModal();
          }
        });
      });
      
      if ($("#example1").length) {
        $("#example1").DataTable({
          "responsive": true, 
          "lengthChange": false, 
          "autoWidth": true,
          "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      }
      
      // Company Switcher Functionality
      function loadUserCompanies() {
        $.ajax({
          url: window.appBaseUrl + '/company/get-user-companies',
          type: 'GET',
          success: function(response) {
            if (response.success && response.data && response.data.length > 0) {
              var html = '';
              var selectedCompanyId = '{{ session("selected_company_id") }}';
              
              response.data.forEach(function(company) {
                var isSelected = selectedCompanyId == company.id;
                var activeClass = isSelected ? 'active' : '';
                var checkIcon = isSelected ? '<i class="fas fa-check float-right mt-1"></i>' : '';
                
                html += '<a href="#" class="dropdown-item ' + activeClass + ' company-option" data-company-id="' + company.id + '" data-company-name="' + company.name + '">';
                html += '<i class="fas fa-building mr-2"></i> ' + company.name;
                html += checkIcon;
                html += '</a>';
              });
              
              $('#companyListContainer').html(html);
            } else {
              $('#companyListContainer').html('<div class="dropdown-item-text text-muted">No companies available</div>');
            }
          },
          error: function() {
            $('#companyListContainer').html('<div class="dropdown-item-text text-danger">Error loading companies</div>');
          }
        });
      }
      
      // Load companies when dropdown is opened
      $('#companySwitcherBtn').on('click', function() {
        loadUserCompanies();
      });
      
      // Handle company selection
      $(document).on('click', '.company-option', function(e) {
        e.preventDefault();
        var companyId = $(this).data('company-id');
        var companyName = $(this).data('company-name');
        
        $.ajax({
          url: window.appBaseUrl + '/company/set-company',
          type: 'POST',
          data: {
            company_id: companyId
          },
          beforeSend: function() {
            $('#companySwitcherBtn').html('<i class="fas fa-spinner fa-spin mr-1"></i> Switching...');
          },
          success: function(response) {
            if (response.success) {
              $('#selectedCompanyName').text(companyName);
              Swal.fire({
                icon: 'success',
                title: 'Company Changed',
                text: 'Company switched to ' + companyName,
                timer: 1500,
                showConfirmButton: false
              }).then(function() {
                // Reload page to refresh all data
                window.location.reload();
              });
            } else {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: response.message || 'Failed to switch company'
              });
              loadUserCompanies();
            }
          },
          error: function(xhr) {
            var message = 'Failed to switch company';
            if (xhr.responseJSON && xhr.responseJSON.message) {
              message = xhr.responseJSON.message;
            }
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: message
            });
            loadUserCompanies();
          },
          complete: function() {
            $('#companySwitcherBtn').html('<i class="fas fa-building mr-1"></i> <span id="selectedCompanyName">' + companyName + '</span> <i class="fas fa-chevron-down ml-1"></i>');
          }
        });
      });
      
      // Load companies on page load if dropdown exists
      if ($('#companySwitcherContainer').length) {
        loadUserCompanies();
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
              'action': window.appBaseUrl + '/logout'
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

