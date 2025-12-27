<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>STC Associates | {{!empty($page_title) ? $page_title : ''}}</title>
  @include('layouts.head')
  <style>
    #example1 thead{
        background: white;
        position: sticky;
        top: 0; /* Don't forget this, required for the stickiness */
    }
    /* #example1 thead th{
        background: white;
        position: sticky;
        top: 0;
        z-index: 10;
    } */
    .sortable-header {
        cursor: pointer;
        user-select: none;
        position: relative;
    }
    .sortable-header:hover {
        background-color: #f8f9fa;
    }
    .sortable-header.active {
        background-color: #e9ecef;
        font-weight: bold;
    }
    .sortable-header i {
        margin-left: 5px;
        font-size: 0.8em;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  
  @include('layouts.nav')

  @include('layouts.aside')
  

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Equipment Details</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="#">Branch</a></li>
              <li class="breadcrumb-item active"><a href="#">{{!empty($page_title) ? $page_title : ''}}</a></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-12">
                <p>@include('layouts._message')</p>
            </div>
        </div>
        <div class="row">          
        <!-- <div class="col-lg-12 col-12"><a href="javascript:void(0)" class="btn btn-block btn-primary btn-md" data-target="#add-modal" data-toggle="modal">Add Product</a></div> -->
          <div class="col-lg-12 col-12">
            <div class="card">
                <div class="card-header p-0 border-bottom-0">
                  <ul class="nav nav-tabs" id="custom-tabs-equipment-tab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="equipment-list-tab" data-toggle="tab" href="#equipment-list" role="tab" aria-controls="equipment-list" aria-selected="true">Equipment List</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="equipment-logs-tab" data-toggle="tab" href="#equipment-logs" role="tab" aria-controls="equipment-logs" aria-selected="false">Equipment Logs</a>
                    </li>
                  </ul>
                </div>
                <div class="card-body">
                  <div class="tab-content" id="custom-tabs-equipment-tabContent">
                    <!-- Equipment List Tab -->
                    <div class="tab-pane fade show active" id="equipment-list" role="tabpanel" aria-labelledby="equipment-list-tab">
                      <table id="example1" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center">Project name</th>
                            <th class="text-center">Department</th>
                            <th class="text-center">Area</th>
                            <th class="text-center">Model No</th>
                            <th class="text-center">Capacity</th>
                            <th class="text-center">Equipment Name</th>
                            <th class="text-center">Unit No</th>
                            <th class="text-center">AHU Filter Qty</th>
                            <th class="text-center">AHU Filter Size</th>
                            <th class="text-center">AHU Filter Type</th>
                            <th class="text-center">AHU Make Name</th>
                            <th class="text-center">AHU V Belt Qty</th>
                            <th class="text-center">AHU V Belt Size</th>
                            <th class="text-center">Bearing Size</th>
                            <th class="text-center">Blower Bearing Size</th>
                            <th class="text-center">Blower Flywheel Size</th>
                            <th class="text-center">Compressor Qty</th>
                            <th class="text-center">Control</th>
                            <th class="text-center">Coupling Size</th>
                            <th class="text-center">Coupling Type</th>
                            <th class="text-center">Current Rating Max</th>
                            <th class="text-center">Delta T</th>
                            <th class="text-center">Delta P</th>
                            <th class="text-center">Each of Capacity</th>
                            <th class="text-center">Equipment Serial No</th>
                            <th class="text-center">Fan Blade Qty</th>
                            <th class="text-center">Fan Blade Size</th>
                            <th class="text-center">Filter Qty</th>
                            <th class="text-center">Filter Size</th>
                            <th class="text-center">Header Size</th>
                            <th class="text-center">Inlet Pressure</th>
                            <th class="text-center">Inlet Temp</th>
                            <th class="text-center">Make Name</th>
                            <th class="text-center">Max Fuse Rating</th>
                            <th class="text-center">Max Load</th>
                            <th class="text-center">Min Fuse Rating</th>
                            <th class="text-center">Min Load</th>
                            <th class="text-center">Motor Bearing Size</th>
                            <th class="text-center">Motor Capacity</th>
                            <th class="text-center">Motor Current Rating</th>
                            <th class="text-center">Motor Make Name</th>
                            <th class="text-center">Motor Pulley Size</th>
                            <th class="text-center">Motor RPM</th>
                            <th class="text-center">Motor Voltage Rating</th>
                            <th class="text-center">Outlet Pressure</th>
                            <th class="text-center">Outlet Temp</th>
                            <th class="text-center">Power Factor</th>
                            <th class="text-center">Pump Head</th>
                            <th class="text-center">Pump Make Name</th>
                            <th class="text-center">Refrigerant type</th>
                            <th class="text-center">Tyre Size</th>
                            <th class="text-center"> V Belt Qty</th>
                            <th class="text-center"> V Belt Size</th>
                            <th class="text-center">Created By</th>
                            <th class="text-center">Created Date</th>
                            <th class="text-center">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                          <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center">Project name</th>
                            <th class="text-center">Department</th>
                            <th class="text-center">Area</th>
                            <th class="text-center">Model No</th>
                            <th class="text-center">Capacity</th>
                            <th class="text-center">Equipment Name</th>
                            <th class="text-center">Unit No</th>
                            <th class="text-center">AHU Filter Qty</th>
                            <th class="text-center">AHU Filter Size</th>
                            <th class="text-center">AHU Filter Type</th>
                            <th class="text-center">AHU Make Name</th>
                            <th class="text-center">AHU V Belt Qty</th>
                            <th class="text-center">AHU V Belt Size</th>
                            <th class="text-center">Bearing Size</th>
                            <th class="text-center">Blower Bearing Size</th>
                            <th class="text-center">Blower Flywheel Size</th>
                            <th class="text-center">Compressor Qty</th>
                            <th class="text-center">Control</th>
                            <th class="text-center">Coupling Size</th>
                            <th class="text-center">Coupling Type</th>
                            <th class="text-center">Current Rating Max</th>
                            <th class="text-center">Delta T</th>
                            <th class="text-center">Delta P</th>
                            <th class="text-center">Each of Capacity</th>
                            <th class="text-center">Equipment Serial No</th>
                            <th class="text-center">Fan Blade Qty</th>
                            <th class="text-center">Fan Blade Size</th>
                            <th class="text-center">Filter Qty</th>
                            <th class="text-center">Filter Size</th>
                            <th class="text-center">Header Size</th>
                            <th class="text-center">Inlet Pressure</th>
                            <th class="text-center">Inlet Temp</th>
                            <th class="text-center">Make Name</th>
                            <th class="text-center">Max Fuse Rating</th>
                            <th class="text-center">Max Load</th>
                            <th class="text-center">Min Fuse Rating</th>
                            <th class="text-center">Min Load</th>
                            <th class="text-center">Motor Bearing Size</th>
                            <th class="text-center">Motor Capacity</th>
                            <th class="text-center">Motor Current Rating</th>
                            <th class="text-center">Motor Make Name</th>
                            <th class="text-center">Motor Pulley Size</th>
                            <th class="text-center">Motor RPM</th>
                            <th class="text-center">Motor Voltage Rating</th>
                            <th class="text-center">Outlet Pressure</th>
                            <th class="text-center">Outlet Temp</th>
                            <th class="text-center">Power Factor</th>
                            <th class="text-center">Pump Head</th>
                            <th class="text-center">Pump Make Name</th>
                            <th class="text-center">Refrigerant type</th>
                            <th class="text-center">Tyre Size</th>
                            <th class="text-center"> V Belt Qty</th>
                            <th class="text-center"> V Belt Size</th>
                            <th class="text-center">Created By</th>
                            <th class="text-center">Created Date</th>
                            <th class="text-center">Action</th>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                    <!-- Equipment Logs Tab -->
                    <div class="tab-pane fade" id="equipment-logs" role="tabpanel" aria-labelledby="equipment-logs-tab">
                      <div class="row mb-3">
                        <div class="col-md-3">
                          <div class="form-group">
                            <label for="log_search">Search:</label>
                            <input type="text" class="form-control" id="log_search" placeholder="Search by Dept Location, Dept Name, Sub Location, SL No, Equipment No, Equipment Type, Area, Model No, Equipment Name, Unit No...">
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="form-group">
                            <label for="log_from_date">From Date:</label>
                            <input type="date" class="form-control" id="log_from_date">
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="form-group">
                            <label for="log_to_date">To Date:</label>
                            <input type="date" class="form-control" id="log_to_date">
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="form-group">
                            <label for="per_page_select">Per Page:</label>
                            <select class="form-control" id="per_page_select">
                              <option value="10">10</option>
                              <option value="25" selected>25</option>
                              <option value="50">50</option>
                              <option value="100">100</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="button" class="btn btn-primary btn-block" id="filter_logs_btn">
                              <i class="fas fa-search"></i> Filter
                            </button>
                          </div>
                        </div>
                      </div>
                      <div id="equipment-logs-container">
                        <div class="text-center p-4">
                          <p class="text-muted">Select filters and click Filter to view equipment logs</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
 
  @include('layouts.footer')

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
  <!-- ./wrapper -->
  @include('layouts.ajax_foot')

  <script>
    $(document).ready(function() {
        function swalSuccess(icon, message) {
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            Toast.fire({
                icon: icon,
                title: message
            });
        }

        // Initialize DataTable
        let table = $('#example1').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            lengthChange: false,
            autoWidth: true,
            // ajax: "{{ url('/branch/stc/std/list') }}",
            ajax: {
                url: "{{ url('/branch/stc/equipment/list') }}",
                type: 'POST', // Use POST for large requests
                data: function(d) {
                    d._token = "{{ csrf_token() }}"; // Add CSRF token for security
                }
            },
            columns: [
                { data: 'id' },
                { data: 'location' },
                { data: 'stc_status_down_list_department_dept' },
                { data: 'area' },
                { data: 'model_no' },
                { data: 'capacity' },
                { data: 'equipment_name' },
                { data: 'unit_no' },
                { data: 'ahu_filter_qty' },
                { data: 'ahu_filter_size' },
                { data: 'ahu_filter_type' },
                { data: 'ahu_make_name' },
                { data: 'ahu_v_belt_qty' },
                { data: 'ahu_v_belt_size' },
                { data: 'bearing_size' },
                { data: 'blower_bearing_size' },
                { data: 'blower_flywheel_size' },
                { data: 'compressor_qty' },
                { data: 'control' },
                { data: 'coupling_size' },
                { data: 'coupling_type' },
                { data: 'current_rating_max' },
                { data: 'delta_t' },
                { data: 'delta_p' },
                { data: 'each_of_capacity' },
                { data: 'equipment_serial_no' },
                { data: 'fan_blade_qty' },
                { data: 'fan_blade_size' },
                { data: 'filter_qty' },
                { data: 'filter_size' },
                { data: 'header_size' },
                { data: 'inlet_pressure' },
                { data: 'inlet_temp' },
                { data: 'make_name' },
                { data: 'max_fuse_rating' },
                { data: 'max_load' },
                { data: 'min_fuse_rating' },
                { data: 'min_load' },
                { data: 'motor_bearing_size' },
                { data: 'motor_capacity' },
                { data: 'motor_current_rating' },
                { data: 'motor_make_name' },
                { data: 'motor_pulley_size' },
                { data: 'motor_rpm' },
                { data: 'motor_voltage_rating' },
                { data: 'outlet_pressure' },
                { data: 'outlet_temp' },
                { data: 'power_factor' },
                { data: 'pump_head' },
                { data: 'pump_make_name' },
                { data: 'refrigerant_type' },
                { data: 'tyre_size' },
                { data: 'v_belt_qty' },
                { data: 'v_belt_size' },
                { data: 'stc_cust_pro_supervisor_fullname' },
                { data: 'created_date' },
                { data: 'actionData' }
            ],
            columnDefs: [
                { "targets": [0, 2, 3, 4], "className": "text-center" },
                { "targets": [1, 5, 6], "className": "text-left" },
                { "targets": [56], "orderable": false }  // Disable ordering on the last column
            ],
            dom: 'Bfrtip',
            buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
        });

        // Add buttons to container
        table.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        // Delete function
        $('.delete-btn').on('click', function(e){
            e.preventDefault();
            var id = $('#delete_id').val();
            $.ajax({
                type: 'GET',
                data: { id: id },
                url: "{{ url('/branch/stc/equipment/delete') }}",
                success: function(response) {
                    if(response.success) {
                        swalSuccess('success', response.message);
                        $('#delete-modal').modal('hide');
                        table.ajax.reload(null, false); // Reload DataTable
                    } else {
                        swalSuccess('error', response.message);
                    }
                }
            });
        });

        // Load equipment list for dropdown
        function loadEquipmentDropdown() {
            $.ajax({
                url: "{{ url('/branch/stc/equipment/list') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    draw: 1,
                    start: 0,
                    length: 1000 // Get all equipment for dropdown
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    var dropdown = $('#equipment_id_filter');
                    dropdown.empty();
                    dropdown.append('<option value="">All Equipment</option>');
                    
                    if(data.aaData && data.aaData.length > 0) {
                        $.each(data.aaData, function(index, item) {
                            var displayText = item.equipment_name + ' (ID: ' + item.id + ')';
                            if(item.location) {
                                displayText += ' - ' + item.location;
                            }
                            dropdown.append('<option value="' + item.id + '">' + displayText + '</option>');
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading equipment list:', error);
                }
            });
        }

        // Pagination and sorting state
        var currentPage = 1;
        var currentSortColumn = 'created_date';
        var currentSortDirection = 'desc';
        var currentPerPage = 25;

        // Load equipment logs
        function loadEquipmentLogs(page, sortColumn, sortDirection, perPage) {
            var equipmentId = $('#equipment_id_filter').val();
            var fromDate = $('#log_from_date').val();
            var toDate = $('#log_to_date').val();
            var search = $('#log_search').val();
            
            // Use provided parameters or current state
            page = page || currentPage;
            sortColumn = sortColumn || currentSortColumn;
            sortDirection = sortDirection || currentSortDirection;
            perPage = perPage || currentPerPage;
            
            // Update current state
            currentPage = page;
            currentSortColumn = sortColumn;
            currentSortDirection = sortDirection;
            currentPerPage = perPage;
            
            var container = $('#equipment-logs-container');
            container.html('<div class="text-center p-4"><i class="fas fa-spinner fa-spin"></i> Loading logs...</div>');
            
            $.ajax({
                url: "{{ url('/branch/stc/equipment/logs') }}",
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    equipment_id: equipmentId,
                    from_date: fromDate,
                    to_date: toDate,
                    search: search,
                    page: page,
                    per_page: perPage,
                    sort_column: sortColumn,
                    sort_direction: sortDirection
                },
                success: function(response) {
                    if(response.success && response.html) {
                        container.html(response.html);
                        
                        // Attach event handlers for pagination
                        $(document).off('click', '.pagination-link').on('click', '.pagination-link', function(e) {
                            e.preventDefault();
                            var page = $(this).data('page');
                            loadEquipmentLogs(page, currentSortColumn, currentSortDirection);
                        });
                        
                        // Attach event handlers for sorting
                        $(document).off('click', '.sortable-header').on('click', '.sortable-header', function(e) {
                            e.preventDefault();
                            var column = $(this).data('sort');
                            var newDirection = 'asc';
                            
                            // Toggle direction if clicking the same column
                            if (currentSortColumn == column && currentSortDirection == 'asc') {
                                newDirection = 'desc';
                            }
                            
                            loadEquipmentLogs(1, column, newDirection); // Reset to page 1 when sorting
                        });
                    } else if(response.success && !response.html) {
                        container.html('<div class="text-center p-4"><p class="text-muted">No logs found for the selected criteria.</p></div>');
                    } else {
                        container.html('<div class="text-center p-4"><p class="text-danger">Error loading logs: ' + (response.message || 'Please try again') + '</p></div>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading equipment logs:', error);
                    var errorMsg = 'Error loading logs';
                    if(xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else {
                        errorMsg += ': ' + error;
                    }
                    container.html('<div class="text-center p-4"><p class="text-danger">' + errorMsg + '</p></div>');
                }
            });
        }

        // Initialize equipment dropdown when logs tab is shown
        $('#equipment-logs-tab').on('shown.bs.tab', function (e) {
            loadEquipmentDropdown();
        });

        // Filter logs button click
        $('#filter_logs_btn').on('click', function() {
            currentPage = 1; // Reset to first page when filtering
            loadEquipmentLogs(1, currentSortColumn, currentSortDirection, $('#per_page_select').val());
        });

        // Per page change
        $('#per_page_select').on('change', function() {
            currentPage = 1; // Reset to first page when changing per page
            loadEquipmentLogs(1, currentSortColumn, currentSortDirection, $(this).val());
        });

        // Search on Enter key
        $('#log_search').on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                e.preventDefault();
                currentPage = 1; // Reset to first page when searching
                loadEquipmentLogs(1, currentSortColumn, currentSortDirection, $('#per_page_select').val());
            }
        });

        // Search on input change (optional - can be removed if too frequent)
        // Uncomment below if you want real-time search as user types
        // var searchTimeout;
        // $('#log_search').on('input', function() {
        //     clearTimeout(searchTimeout);
        //     searchTimeout = setTimeout(function() {
        //         currentPage = 1;
        //         loadEquipmentLogs(1, currentSortColumn, currentSortDirection, $('#per_page_select').val());
        //     }, 500); // Wait 500ms after user stops typing
        // });
    });
  </script>

</body>
</html>
<!-- delete modal -->
<div class="modal fade" id="delete-modal">
  <div class="modal-dialog">
    <div class="modal-content bg-danger">
      <div class="modal-header">
        <h4 class="modal-title">Delete Product</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete these Records?</p>
        <p class="text-warning"><small>This action cannot be undone.</small></p>
        <input type="hidden" id="delete_id">
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default close-btn" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger delete-btn">Delete</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- view modal -->
<div class="modal fade" id="view-modal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">View Sale Purchase</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="card-body">
          <div class="form-group">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-responsive table-stripped">
                        <thead>
                            <tr>
                                <th>Purchase Id/ Date</th>
                                <th>Purchase From</th>
                                <th>Purchase Quantity</th>
                            </tr>
                        </thead>
                        <tbody class="elec-purchase-rec">
                            <tr>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-responsive table-stripped">
                        <thead>
                            <tr>
                                <th>Challan Id/Date</th>
                                <th>Sold to</th>
                                <th>Sold Quantity</th>
                            </tr>
                        </thead>
                        <tbody class="elec-sale-rec">
                            <tr>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
          </div>
        </div>
        <!-- /.card-body -->
        <input type="hidden" id="edit_id">
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default close-btn" data-dismiss="modal">Close</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>