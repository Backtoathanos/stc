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
    #bulk-delete-collaborations {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        border-radius: 50px;
        padding: 12px 24px;
        font-weight: bold;
    }
    #bulk-delete-collaborations:hover {
        box-shadow: 0 6px 12px rgba(0,0,0,0.4);
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }
    .sortable {
        user-select: none;
    }
    .sortable:hover {
        background-color: #f8f9fa;
    }
    .sort-icon {
        margin-left: 5px;
        font-size: 12px;
        opacity: 0.5;
    }
    .sortable.sort-asc .sort-icon:before {
        content: "\f0de";
        opacity: 1;
        color: #007bff;
    }
    .sortable.sort-desc .sort-icon:before {
        content: "\f0dd";
        opacity: 1;
        color: #007bff;
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
            <h1 class="m-0">Projects</h1>
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
                  <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="projects-tab" data-toggle="tab" href="#projects" role="tab" aria-controls="projects" aria-selected="false">Projects</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="collaborations-tab" data-toggle="tab" href="#collaborations" role="tab" aria-controls="collaborations" aria-selected="false">Project Collaborations</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="departments-tab" data-toggle="tab" href="#departments" role="tab" aria-controls="departments" aria-selected="false">Departments</a>
                    </li>
                  </ul>
                </div>
                <div class="card-body">
                  <div class="tab-content" id="custom-tabs-four-tabContent">
                    <!-- Projects Tab -->
                    <div class="tab-pane fade show active" id="projects" role="tabpanel" aria-labelledby="projects-tab">
                      <table id="example1" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th class="text-center">Id</th>
                            <th class="text-center">Company Name</th>
                            <th class="text-center">Title</th>
                            <th class="text-center">Reference Number</th>
                            <th class="text-center">Address</th>
                            <th class="text-center">City</th>
                            <th class="text-center">State</th>
                            <th class="text-center">Responsive Person</th>
                            <th class="text-center">Supervisor Quantity</th>
                            <th class="text-center">Beg Date</th>
                            <th class="text-center">End Date</th>
                            <th class="text-center">Beg Budget</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Editable Mincount</th>
                            <th class="text-center">Editable Maxcount</th>
                            <th class="text-center">Created By</th>
                            <th class="text-center">Created Date</th>
                            <th class="text-center">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                          <tr>
                            <th class="text-center">Id</th>
                            <th class="text-center">Company Name</th>
                            <th class="text-center">Title</th>
                            <th class="text-center">Reference Number</th>
                            <th class="text-center">Address</th>
                            <th class="text-center">City</th>
                            <th class="text-center">State</th>
                            <th class="text-center">Responsive Person</th>
                            <th class="text-center">Supervisor Quantity</th>
                            <th class="text-center">Beg Date</th>
                            <th class="text-center">End Date</th>
                            <th class="text-center">Beg Budget</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Editable Mincount</th>
                            <th class="text-center">Editable Maxcount</th>
                            <th class="text-center">Created By</th>
                            <th class="text-center">Created Date</th>
                            <th class="text-center">Action</th>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                    <!-- Project Collaborations Tab -->
                    <div class="tab-pane fade" id="collaborations" role="tabpanel" aria-labelledby="collaborations-tab">
                      <div class="row mb-3">
                        <div class="col-md-6">
                          <div class="input-group">
                            <input type="text" class="form-control" id="collaboration-search" placeholder="Search by project, manager, or collaborator...">
                            <div class="input-group-append">
                              <button class="btn btn-primary" type="button" id="search-collaborations-btn">
                                <i class="fas fa-search"></i> Search
                              </button>
                            </div>
                            <div class="input-group-append">
                              <select class="form-control" id="collaboration-per-page" style="max-width: 120px;" title="Rows per page">
                                <option value="25" selected>25 rows</option>
                                <option value="50">50 rows</option>
                                <option value="100">100 rows</option>
                                <option value="500">500 rows</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-8 text-right">
                          <span id="collaboration-info" class="text-muted"></span>
                        </div>
                      </div>
                      <div class="table-responsive">
                        <table id="collaborations-table" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th class="text-center" style="width: 40px;">
                                <input type="checkbox" id="select-all-collaborations" title="Select All">
                              </th>
                              <th class="text-center sortable" data-sort="project_title" style="cursor: pointer;">
                                Project Title <i class="fas fa-sort sort-icon"></i>
                              </th>
                              <th class="text-center sortable" data-sort="manager_name" style="cursor: pointer;">
                                Manager Name <i class="fas fa-sort sort-icon"></i>
                              </th>
                              <th class="text-center sortable" data-sort="collaborator_name" style="cursor: pointer;">
                                Collaborator Name <i class="fas fa-sort sort-icon"></i>
                              </th>
                              <th class="text-center sortable" data-sort="status" style="cursor: pointer;">
                                Status <i class="fas fa-sort sort-icon"></i>
                              </th>
                              <th class="text-center sortable" data-sort="created_date" style="cursor: pointer;">
                                Created Date <i class="fas fa-sort sort-icon"></i>
                              </th>
                              <th class="text-center">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td colspan="7" class="text-center">Loading collaborations...</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <!-- Floating Delete Button -->
                      <button type="button" class="btn btn-danger" id="bulk-delete-collaborations" style="display: none;">
                        <i class="fas fa-trash"></i> Delete Selected (<span id="selected-count">0</span>)
                      </button>
                      <div class="row mt-3">
                        <div class="col-md-12">
                          <nav aria-label="Collaborations pagination">
                            <ul class="pagination justify-content-center" id="collaboration-pagination">
                            </ul>
                          </nav>
                        </div>
                      </div>
                    </div>
                    <!-- Departments Tab -->
                    <div class="tab-pane fade" id="departments" role="tabpanel" aria-labelledby="departments-tab">
                      <div class="row mb-3">
                        <div class="col-md-12 text-right">
                          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-department-modal">
                            <i class="fas fa-plus"></i> Add Department
                          </button>
                        </div>
                      </div>
                      <!-- Fixed Bulk Action Buttons -->
                      <div id="departments-bulk-actions" style="position: fixed; top: 70px; right: 30px; z-index: 1000; background: white; padding: 15px; border-radius: 5px; box-shadow: 0 4px 8px rgba(0,0,0,0.3); display: none;">
                        <div style="display: flex; align-items: center; margin-bottom: 8px;">
                          <select class="form-control form-control-sm" id="bulk-status-change" style="width: 150px; margin-right: 8px;">
                            <option value="">Change Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                          </select>
                          <button type="button" class="btn btn-danger btn-sm" id="bulk-delete-departments" style="margin-right: 8px;">
                            <i class="fas fa-trash"></i> Delete Selected
                          </button>
                          <button type="button" class="btn btn-secondary btn-sm" id="clear-selection">
                            <i class="fas fa-times"></i>
                          </button>
                        </div>
                        <small class="text-muted" id="selected-count" style="display: block; margin-top: 5px;">0 selected</small>
                      </div>
                      <div class="table-responsive">
                        <table id="departments-table" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th class="text-center" style="width: 50px;">
                                <input type="checkbox" id="select-all-departments" title="Select All">
                              </th>
                              <th class="text-center">ID</th>
                              <th class="text-center">Date</th>
                              <th class="text-center">Project Title</th>
                              <th class="text-center">Location</th>
                              <th class="text-center">Department</th>
                              <th class="text-center">Status</th>
                              <th class="text-center">Created By</th>
                              <th class="text-center">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                          </tbody>
                        </table>
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
        function swalSuccess(icon, message){
            var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
            });
            Toast.fire({
            icon: icon,
            title: message
            })
        }
        
        // Initialize Departments DataTable - only when tab is shown
        var departmentsTable = null;
        
        function initDepartmentsTable() {
            if (departmentsTable === null && !$.fn.DataTable.isDataTable('#departments-table')) {
                departmentsTable = $('#departments-table').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    lengthChange: true,
                    pageLength: 25,
                    lengthMenu: [[25, 50, 100, 500], [25, 50, 100, 500]],
                    autoWidth: true,
                    ajax: {
                        url: "{{ url('/branch/stc/departments/list') }}",
                        type: 'GET'
                    },
                    drawCallback: function() {
                        // Reset select all checkbox after reload
                        $('#select-all-departments').prop('checked', false);
                        updateBulkActions();
                    },
                    columns: [
                        { 
                            data: 'stc_status_down_list_department_id',
                            name: 'checkbox',
                            orderable: false,
                            searchable: false,
                            className: 'text-center',
                            render: function(data) {
                                return '<input type="checkbox" class="department-checkbox" value="' + data + '">';
                            }
                        },
                        { data: 'stc_status_down_list_department_id', name: 'stc_status_down_list_department_id', className: 'text-center' },
                        { 
                            data: 'stc_status_down_list_department_date', 
                            name: 'stc_status_down_list_department_date',
                            className: 'text-center',
                            render: function(data) {
                                return data && data !== '-' ? data : '-';
                            }
                        },
                        { 
                            data: 'stc_status_down_list_department_loc_id', 
                            name: 'stc_status_down_list_department_loc_id', 
                            className: 'text-left',
                            title: 'Project Title'
                        },
                        { data: 'stc_status_down_list_department_location', name: 'stc_status_down_list_department_location', className: 'text-center' },
                        { data: 'stc_status_down_list_department_dept', name: 'stc_status_down_list_department_dept', className: 'text-center' },
                        { 
                            data: 'stc_status_down_list_department_status', 
                            name: 'stc_status_down_list_department_status',
                            className: 'text-center',
                            render: function(data) {
                                return data == 1 
                                    ? '<span class="badge badge-success">Active</span>' 
                                    : '<span class="badge badge-danger">Inactive</span>';
                            }
                        },
                        { data: 'stc_status_down_list_department_created_by', name: 'stc_status_down_list_department_created_by', className: 'text-center' },
                        { 
                            data: 'stc_status_down_list_department_id',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'text-center',
                            render: function(data) {
                                return '<a href="javascript:void(0)" class="btn btn-warning btn-sm edit-department" data-id="' + data + '" title="Edit">' +
                                       '<i class="fas fa-edit"></i></a> ' +
                                       '<a href="javascript:void(0)" class="btn btn-danger btn-sm delete-department" data-id="' + data + '" title="Delete">' +
                                       '<i class="fas fa-trash"></i></a>';
                            }
                        }
                    ],
                    order: [[1, 'desc']]
                });
                
                // Handle checkbox selection
                handleDepartmentCheckboxes();
            }
        }
        
        // Handle department checkboxes
        function handleDepartmentCheckboxes() {
            // Select all checkbox
            $(document).on('change', '#select-all-departments', function() {
                var isChecked = $(this).is(':checked');
                $('.department-checkbox').prop('checked', isChecked);
                updateBulkActions();
            });
            
            // Individual checkbox
            $(document).on('change', '.department-checkbox', function() {
                updateBulkActions();
                updateSelectAllCheckbox();
            });
            
            // Clear selection
            $(document).on('click', '#clear-selection', function() {
                $('.department-checkbox, #select-all-departments').prop('checked', false);
                updateBulkActions();
            });
            
            // Bulk status change
            $(document).on('change', '#bulk-status-change', function() {
                var status = $(this).val();
                if(status && status !== '') {
                    var selectedIds = getSelectedDepartmentIds();
                    if(selectedIds.length > 0) {
                        changeBulkStatus(selectedIds, status);
                    } else {
                        swalSuccess('error', 'Please select at least one department');
                        $(this).val('');
                    }
                }
            });
            
            // Bulk delete
            $(document).on('click', '#bulk-delete-departments', function() {
                var selectedIds = getSelectedDepartmentIds();
                if(selectedIds.length > 0) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'You are about to delete ' + selectedIds.length + ' department(s). This action cannot be undone!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete them!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            deleteBulkDepartments(selectedIds);
                        }
                    });
                } else {
                    swalSuccess('error', 'Please select at least one department');
                }
            });
        }
        
        // Get selected department IDs
        function getSelectedDepartmentIds() {
            var ids = [];
            $('.department-checkbox:checked').each(function() {
                ids.push($(this).val());
            });
            return ids;
        }
        
        // Update bulk actions visibility
        function updateBulkActions() {
            var selectedCount = getSelectedDepartmentIds().length;
            if(selectedCount > 0) {
                $('#departments-bulk-actions').show();
                $('#selected-count').text(selectedCount + ' selected');
            } else {
                $('#departments-bulk-actions').hide();
                $('#bulk-status-change').val('');
            }
        }
        
        // Update select all checkbox
        function updateSelectAllCheckbox() {
            var totalCheckboxes = $('.department-checkbox').length;
            var checkedCheckboxes = $('.department-checkbox:checked').length;
            $('#select-all-departments').prop('checked', totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes);
        }
        
        // Change bulk status
        function changeBulkStatus(ids, status) {
            $.ajax({
                url: "{{ url('/branch/stc/departments/bulk-status') }}",
                type: 'POST',
                data: {
                    ids: ids,
                    status: status,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if(response.success) {
                        swalSuccess('success', response.message || 'Status updated successfully');
                        $('.department-checkbox, #select-all-departments').prop('checked', false);
                        updateBulkActions();
                        if(departmentsTable !== null && $.fn.DataTable.isDataTable('#departments-table')) {
                            departmentsTable.ajax.reload(null, false);
                        }
                    } else {
                        swalSuccess('error', response.message || 'Error updating status');
                    }
                },
                error: function() {
                    swalSuccess('error', 'Error updating status');
                }
            });
        }
        
        // Delete bulk departments
        function deleteBulkDepartments(ids) {
            $.ajax({
                url: "{{ url('/branch/stc/departments/bulk-delete') }}",
                type: 'POST',
                data: {
                    ids: ids,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if(response.success) {
                        swalSuccess('success', response.message || 'Departments deleted successfully');
                        $('.department-checkbox, #select-all-departments').prop('checked', false);
                        updateBulkActions();
                        if(departmentsTable !== null && $.fn.DataTable.isDataTable('#departments-table')) {
                            departmentsTable.ajax.reload(null, false);
                        }
                    } else {
                        swalSuccess('error', response.message || 'Error deleting departments');
                    }
                },
                error: function() {
                    swalSuccess('error', 'Error deleting departments');
                }
            });
        }
        
        // Initialize departments table when tab is shown
        $('#departments-tab').on('shown.bs.tab', function (e) {
            initDepartmentsTable();
        });
        
        // Initialize if departments tab is active by default
        if($('#departments-tab').hasClass('active')) {
            initDepartmentsTable();
        }
        
        var isEditMode = false;
        
        // Open add modal
        $('#add-department-modal').on('show.bs.modal', function() {
            if(!isEditMode) {
                $('#department-modal-title').text('Add Department');
                $('#department-form')[0].reset();
                $('#department_id').val('');
            }
        });
        
        // Open add modal button
        $('[data-target="#add-department-modal"]').on('click', function() {
            isEditMode = false;
            $('#department-modal-title').text('Add Department');
            $('#department-form')[0].reset();
            $('#department_id').val('');
        });
        
        // Edit department
        $(document).on('click', '.edit-department', function(e) {
            e.preventDefault();
            e.stopPropagation();
            isEditMode = true;
            var deptId = $(this).data('id');
            console.log('Loading department for edit, ID:', deptId);
            
            // Show loading state
            $('#department-modal-title').text('Loading...');
            $('#add-department-modal').modal('show');
            
            $.ajax({
                url: "{{ url('/branch/stc/departments/get') }}",
                type: 'GET',
                data: { id: deptId },
                success: function(response) {
                    console.log('Department data response:', response);
                    if(response.success && response.data) {
                        var dept = response.data;
                        
                        // Populate form fields
                        $('#department_id').val(dept.stc_status_down_list_department_id || '');
                        $('#department_project_id').val(dept.stc_status_down_list_department_loc_id || '');
                        $('#department_location').val(dept.stc_status_down_list_department_location || '');
                        $('#department_name').val(dept.stc_status_down_list_department_dept || '');
                        $('#department_status').val(dept.stc_status_down_list_department_status || '1');
                        
                        // Update title
                        $('#department-modal-title').text('Edit Department');
                    } else {
                        $('#add-department-modal').modal('hide');
                        swalSuccess('error', response.message || 'Error loading department data');
                    }
                },
                error: function(xhr, status, error) {
                    $('#add-department-modal').modal('hide');
                    swalSuccess('error', 'Error loading department data');
                }
            });
        });
        
        // Reset edit mode when modal is hidden
        $('#add-department-modal').on('hidden.bs.modal', function() {
            isEditMode = false;
        });
        
        // Save department (add/edit)
        $('#department-form').on('submit', function(e) {
            e.preventDefault();
            var formData = {
                id: $('#department_id').val(),
                project_id: $('#department_project_id').val(),
                location: $('#department_location').val(),
                department_name: $('#department_name').val(),
                status: $('#department_status').val(),
                _token: '{{ csrf_token() }}'
            };
            
            $.ajax({
                url: "{{ url('/branch/stc/departments/save') }}",
                type: 'POST',
                data: formData,
                success: function(response) {
                    if(response.success) {
                        swalSuccess('success', response.message || 'Department saved successfully');
                        $('#add-department-modal').modal('hide');
                        if(departmentsTable !== null && $.fn.DataTable.isDataTable('#departments-table')) {
                            departmentsTable.ajax.reload(null, false);
                        }
                    } else {
                        swalSuccess('error', response.message || 'Error saving department');
                    }
                },
                error: function(xhr, status, error) {
                    swalSuccess('error', 'Error saving department');
                }
            });
        });
        
        // Delete department
        $(document).on('click', '.delete-department', function() {
            var deptId = $(this).data('id');
            $('#delete_department_id').val(deptId);
            $('#delete-department-modal').modal('show');
        });
        
        $('#confirm-delete-department').on('click', function() {
            var deptId = $('#delete_department_id').val();
            $.ajax({
                url: "{{ url('/branch/stc/departments/delete') }}",
                type: 'GET',
                data: { id: deptId },
                success: function(response) {
                    if(response.success) {
                        swalSuccess('success', response.message || 'Department deleted successfully');
                        $('#delete-department-modal').modal('hide');
                        if(departmentsTable !== null && $.fn.DataTable.isDataTable('#departments-table')) {
                            departmentsTable.ajax.reload(null, false);
                        }
                    } else {
                        swalSuccess('error', response.message || 'Error deleting department');
                    }
                },
                error: function() {
                    swalSuccess('error', 'Error deleting department');
                }
            });
        });
        
        // Edit project
        var isProjectEditMode = false;
        
        $(document).on('click', '.edit-project', function(e) {
            e.preventDefault();
            e.stopPropagation();
            isProjectEditMode = true;
            var projectId = $(this).data('id');
            
            // Show loading state
            $('#project-modal-title').text('Loading...');
            $('#edit-project-modal').modal('show');
            
            $.ajax({
                url: "{{ url('/branch/stc/projects/get') }}",
                type: 'GET',
                data: { id: projectId },
                success: function(response) {
                    if(response.success && response.data) {
                        var project = response.data;
                        
                        // Populate form fields
                        $('#project_id').val(project.stc_cust_project_id || '');
                        $('#project_customer_id').val(project.stc_cust_project_cust_id || '');
                        $('#project_title').val(project.stc_cust_project_title || '');
                        $('#project_reference_number').val(project.stc_cust_project_refr || '');
                        $('#project_address').val(project.stc_cust_project_address || '');
                        $('#project_city_id').val(project.stc_cust_project_city_id || '');
                        $('#project_state_id').val(project.stc_cust_project_state_id || '');
                        $('#project_responsive_person').val(project.stc_cust_project_responsive_person || '');
                        $('#project_supervisor_qty').val(project.stc_cust_project_supervis_qty || '');
                        $('#project_beg_date').val(project.stc_cust_project_beg_date || '');
                        $('#project_end_date').val(project.stc_cust_project_end_date || '');
                        $('#project_beg_budget').val(project.stc_cust_project_beg_budget || '');
                        $('#project_status').val(project.stc_cust_project_status || '1');
                        $('#project_editable_mincount').val(project.stc_cust_project_editable_mincount || '');
                        $('#project_editable_maxcount').val(project.stc_cust_project_editable_maxcount || '');
                        
                        // Update title
                        $('#project-modal-title').text('Edit Project');
                    } else {
                        $('#edit-project-modal').modal('hide');
                        swalSuccess('error', response.message || 'Error loading project data');
                    }
                },
                error: function(xhr, status, error) {
                    $('#edit-project-modal').modal('hide');
                    swalSuccess('error', 'Error loading project data');
                }
            });
        });
        
        // Open add project modal
        $('#edit-project-modal').on('show.bs.modal', function() {
            if(!isProjectEditMode) {
                $('#project-modal-title').text('Add Project');
                $('#project-form')[0].reset();
                $('#project_id').val('');
            }
        });
        
        // Reset edit mode when modal is hidden
        $('#edit-project-modal').on('hidden.bs.modal', function() {
            isProjectEditMode = false;
        });
        
        // Save project (add/edit)
        $('#project-form').on('submit', function(e) {
            e.preventDefault();
            var formData = {
                id: $('#project_id').val(),
                customer_id: $('#project_customer_id').val(),
                title: $('#project_title').val(),
                reference_number: $('#project_reference_number').val(),
                address: $('#project_address').val(),
                city_id: $('#project_city_id').val(),
                state_id: $('#project_state_id').val(),
                responsive_person: $('#project_responsive_person').val(),
                supervisor_qty: $('#project_supervisor_qty').val(),
                beg_date: $('#project_beg_date').val(),
                end_date: $('#project_end_date').val(),
                beg_budget: $('#project_beg_budget').val(),
                status: $('#project_status').val(),
                editable_mincount: $('#project_editable_mincount').val(),
                editable_maxcount: $('#project_editable_maxcount').val(),
                _token: '{{ csrf_token() }}'
            };
            
            $.ajax({
                url: "{{ url('/branch/stc/projects/save') }}",
                type: 'POST',
                data: formData,
                success: function(response) {
                    if(response.success) {
                        swalSuccess('success', response.message || 'Project saved successfully');
                        $('#edit-project-modal').modal('hide');
                        if(projectsTable !== null && $.fn.DataTable.isDataTable('#example1')) {
                            projectsTable.ajax.reload(null, false);
                        }
                    } else {
                        swalSuccess('error', response.message || 'Error saving project');
                    }
                },
                error: function(xhr, status, error) {
                    swalSuccess('error', 'Error saving project');
                }
            });
        });
        
        // Initialize projects table if projects tab is active by default
        if($('#projects-tab').hasClass('active')) {
            var dataTableAct="active";
            getProjects(dataTableAct);
        }      
      
        // Projects DataTable variable
        var projectsTable = null;
        
        // get Rack function
        function getProjects() {
            // Destroy existing table if it exists
            if ($.fn.DataTable.isDataTable('#example1')) {
                $('#example1').DataTable().destroy();
            }
            
            // Initialize DataTable
            projectsTable = $('#example1').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                lengthChange: false,
                autoWidth: true,
                ajax: "{{ url('/branch/stc/projects/list') }}",
                columns: [
                    { data: 'stc_cust_project_id' },
                    { data: 'stc_customer_name' },
                    { data: 'stc_cust_project_title' },
                    { data: 'stc_cust_project_refr' },
                    { data: 'stc_cust_project_address' },
                    { data: 'stc_city_name' },
                    { data: 'stc_state_name' },
                    { data: 'stc_cust_project_responsive_person' },
                    { data: 'stc_cust_project_supervis_qty' },
                    { data: 'stc_cust_project_beg_date' },
                    { data: 'stc_cust_project_end_date' },
                    { data: 'stc_cust_project_beg_budget' },
                    {
                        data: 'stc_cust_project_status',
                        render: function(data, type, row) {
                            return data === '1' ? 'Active' : 'Inactive';
                        },
                        className: 'text-center'
                    },
                    { data: 'stc_cust_project_editable_mincount' },
                    { data: 'stc_cust_project_editable_maxcount' },
                    { data: 'stc_agents_name' },
                    { data: 'stc_cust_project_date' },
                    { data: 'actionData' }
                ],
                columnDefs: [
                    { "targets": [0, 1, 3, 5, 6, 7, 8, 9, 10, 12, 13, 14, 15, 16], "className": "text-center" },
                    { "targets": [2, 4], "className": "text-left" },
                    { "targets": [11], "className": "text-right" },
                    { "targets": [17], "orderable": false }  // Disable ordering on specific columns
                ],
                dom: 'Bfrtip',
                buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
            });

            // Add buttons to container
            if(projectsTable) {
                projectsTable.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            }
        }
        
        // Initialize projects table only when projects tab is shown
        $('#projects-tab').on('shown.bs.tab', function (e) {
            if(projectsTable === null || !$.fn.DataTable.isDataTable('#example1')) {
                var dataTableAct="active";
                getProjects(dataTableAct);
            }
        });



        var currentCollaborationPage = 1;
        var currentCollaborationSearch = '';
        var currentCollaborationSort = 'project_title';
        var currentCollaborationSortDir = 'asc';
        var currentCollaborationPerPage = 25;

        // Load collaborations when tab is clicked
        $('#collaborations-tab').on('shown.bs.tab', function (e) {
            currentCollaborationPage = 1;
            currentCollaborationSort = 'project_title';
            currentCollaborationSortDir = 'asc';
            currentCollaborationPerPage = parseInt($('#collaboration-per-page').val()) || 25;
            updateSortIcons();
            loadCollaborations();
        });

        // Update sort icons based on current sort
        function updateSortIcons() {
            $('.sortable').removeClass('sort-asc sort-desc');
            $('.sortable .sort-icon').removeClass('fa-sort-up fa-sort-down').addClass('fa-sort');
            
            var activeHeader = $('.sortable[data-sort="' + currentCollaborationSort + '"]');
            if(activeHeader.length) {
                activeHeader.addClass('sort-' + currentCollaborationSortDir);
                activeHeader.find('.sort-icon').removeClass('fa-sort').addClass('fa-sort-' + (currentCollaborationSortDir === 'asc' ? 'up' : 'down'));
            }
        }

        // Update sort icons based on current sort
        function updateSortIcons() {
            $('.sortable').removeClass('sort-asc sort-desc');
            $('.sortable .sort-icon').removeClass('fa-sort-up fa-sort-down').addClass('fa-sort');
            
            var activeHeader = $('.sortable[data-sort="' + currentCollaborationSort + '"]');
            if(activeHeader.length) {
                activeHeader.addClass('sort-' + currentCollaborationSortDir);
                activeHeader.find('.sort-icon').removeClass('fa-sort').addClass('fa-sort-' + (currentCollaborationSortDir === 'asc' ? 'up' : 'down'));
            }
        }

        // Sort column click handler
        $(document).on('click', '.sortable', function() {
            var sortColumn = $(this).data('sort');
            
            // If clicking the same column, toggle direction
            if(currentCollaborationSort === sortColumn) {
                currentCollaborationSortDir = currentCollaborationSortDir === 'asc' ? 'desc' : 'asc';
            } else {
                currentCollaborationSort = sortColumn;
                currentCollaborationSortDir = 'asc';
            }
            
            // Update sort icons
            updateSortIcons();
            
            // Reset to first page and reload
            currentCollaborationPage = 1;
            loadCollaborations();
        });

        // Search button click
        $('#search-collaborations-btn').on('click', function() {
            currentCollaborationPage = 1;
            currentCollaborationSearch = $('#collaboration-search').val();
            loadCollaborations();
        });

        // Search on Enter key
        $('#collaboration-search').on('keypress', function(e) {
            if(e.which == 13) {
                currentCollaborationPage = 1;
                currentCollaborationSearch = $(this).val();
                loadCollaborations();
            }
        });

        // Per page change handler
        $('#collaboration-per-page').on('change', function() {
            currentCollaborationPerPage = parseInt($(this).val());
            currentCollaborationPage = 1;
            loadCollaborations();
        });

        // Load collaborations function
        function loadCollaborations() {
            $.ajax({
                url: "{{ url('/branch/stc/projects/collaborations/list') }}",
                type: 'GET',
                data: {
                    page: currentCollaborationPage,
                    search: currentCollaborationSearch,
                    sort: currentCollaborationSort,
                    sort_dir: currentCollaborationSortDir,
                    per_page: currentCollaborationPerPage
                },
                dataType: 'json',
                success: function(response) {
                    var tbody = $('#collaborations-table tbody');
                    tbody.empty();
                    // Reset select all checkbox
                    $('#select-all-collaborations').prop('checked', false);
                    updateBulkDeleteButton();
                    
                    if(response.success && response.data.length > 0) {
                        $.each(response.data, function(index, item) {
                            var status = item.stc_cust_project_collaborate_status == '1' ? 'Active' : 'Inactive';
                            var statusClass = item.stc_cust_project_collaborate_status == '1' ? 'badge-success' : 'badge-secondary';
                            var row = '<tr>' +
                                '<td class="text-center">' +
                                '<input type="checkbox" class="collaboration-checkbox" value="' + item.stc_cust_project_collaborate_id + '" data-id="' + item.stc_cust_project_collaborate_id + '">' +
                                '</td>' +
                                '<td class="text-center">' + (item.stc_cust_project_title || 'N/A') + '</td>' +
                                '<td class="text-center">' + (item.manager_name || 'N/A') + '</td>' +
                                '<td class="text-center">' + (item.collaborator_name || 'N/A') + '</td>' +
                                '<td class="text-center"><span class="badge ' + statusClass + '">' + status + '</span></td>' +
                                '<td class="text-center">' + (item.stc_cust_project_collaborate_created_date || 'N/A') + '</td>' +
                                '<td class="text-center">' +
                                '<a href="javascript:void(0)" class="btn btn-danger btn-sm remove-collaborator" data-id="' + item.stc_cust_project_collaborate_id + '" title="Remove"><i class="fas fa-trash"></i></a>' +
                                '</td>' +
                                '</tr>';
                            tbody.append(row);
                        });
                        
                        // Update pagination info
                        var pagination = response.pagination;
                        var infoText = 'Showing ' + ((pagination.current_page - 1) * pagination.per_page + 1) + 
                                      ' to ' + Math.min(pagination.current_page * pagination.per_page, pagination.total_records) + 
                                      ' of ' + pagination.total_records + ' entries';
                        $('#collaboration-info').text(infoText);
                        
                        // Build pagination
                        buildCollaborationPagination(pagination);
                        
                        // Update sort icons after loading
                        updateSortIcons();
                    } else {
                        tbody.append('<tr><td colspan="7" class="text-center">No collaborations found.</td></tr>');
                        $('#collaboration-info').text('No records found');
                        $('#collaboration-pagination').empty();
                    }
                },
                error: function() {
                    $('#collaborations-table tbody').html('<tr><td colspan="7" class="text-center text-danger">Error loading collaborations.</td></tr>');
                    $('#collaboration-info').text('');
                    $('#collaboration-pagination').empty();
                }
            });
        }

        // Select All checkbox handler
        $('#select-all-collaborations').on('change', function() {
            $('.collaboration-checkbox').prop('checked', $(this).prop('checked'));
            updateBulkDeleteButton();
        });

        // Individual checkbox handler
        $(document).on('change', '.collaboration-checkbox', function() {
            var totalCheckboxes = $('.collaboration-checkbox').length;
            var checkedCheckboxes = $('.collaboration-checkbox:checked').length;
            $('#select-all-collaborations').prop('checked', totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes);
            updateBulkDeleteButton();
        });

        // Update bulk delete button visibility and count
        function updateBulkDeleteButton() {
            var checkedCount = $('.collaboration-checkbox:checked').length;
            if(checkedCount > 0) {
                $('#bulk-delete-collaborations').show();
                $('#selected-count').text(checkedCount);
            } else {
                $('#bulk-delete-collaborations').hide();
            }
        }

        // Bulk delete handler
        $('#bulk-delete-collaborations').on('click', function() {
            var selectedIds = [];
            $('.collaboration-checkbox:checked').each(function() {
                selectedIds.push($(this).data('id'));
            });

            if(selectedIds.length === 0) {
                swalSuccess('warning', 'Please select at least one collaboration to delete.');
                return;
            }

            if(confirm('Are you sure you want to delete ' + selectedIds.length + ' selected collaboration(s)? This action cannot be undone.')) {
                $.ajax({
                    url: "{{ url('/branch/stc/projects/collaborations/bulk-remove') }}",
                    type: 'POST',
                    data: {
                        ids: selectedIds,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    success: function(response) {
                        if(response.success) {
                            swalSuccess('success', response.message || 'Selected collaborations removed successfully.');
                            // Reset checkboxes
                            $('#select-all-collaborations').prop('checked', false);
                            // Reload collaborations
                            loadCollaborations();
                        } else {
                            swalSuccess('error', response.message || 'Error removing collaborations.');
                        }
                    },
                    error: function() {
                        swalSuccess('error', 'Error removing collaborations. Please try again.');
                    }
                });
            }
        });

        // Build pagination
        function buildCollaborationPagination(pagination) {
            var paginationHtml = '';
            var totalPages = pagination.total_pages;
            var currentPage = pagination.current_page;

            // Previous button
            paginationHtml += '<li class="page-item ' + (currentPage == 1 ? 'disabled' : '') + '">';
            paginationHtml += '<a class="page-link" href="javascript:void(0)" data-page="' + (currentPage - 1) + '">Previous</a>';
            paginationHtml += '</li>';

            // Page numbers
            var startPage = Math.max(1, currentPage - 2);
            var endPage = Math.min(totalPages, currentPage + 2);

            if(startPage > 1) {
                paginationHtml += '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page="1">1</a></li>';
                if(startPage > 2) {
                    paginationHtml += '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
            }

            for(var i = startPage; i <= endPage; i++) {
                paginationHtml += '<li class="page-item ' + (i == currentPage ? 'active' : '') + '">';
                paginationHtml += '<a class="page-link" href="javascript:void(0)" data-page="' + i + '">' + i + '</a>';
                paginationHtml += '</li>';
            }

            if(endPage < totalPages) {
                if(endPage < totalPages - 1) {
                    paginationHtml += '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
                paginationHtml += '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page="' + totalPages + '">' + totalPages + '</a></li>';
            }

            // Next button
            paginationHtml += '<li class="page-item ' + (currentPage == totalPages ? 'disabled' : '') + '">';
            paginationHtml += '<a class="page-link" href="javascript:void(0)" data-page="' + (currentPage + 1) + '">Next</a>';
            paginationHtml += '</li>';

            $('#collaboration-pagination').html(paginationHtml);
        }

        // Pagination click handler
        $(document).on('click', '#collaboration-pagination .page-link', function(e) {
            e.preventDefault();
            var page = $(this).data('page');
            if(page && page != currentCollaborationPage) {
                currentCollaborationPage = page;
                loadCollaborations();
                // Scroll to top of table
                $('html, body').animate({
                    scrollTop: $('#collaborations-table').offset().top - 100
                }, 300);
            }
        });

        // Remove collaborator
        $(document).on('click', '.remove-collaborator', function(e) {
            e.preventDefault();
            var collabId = $(this).data('id');
            if(confirm('Are you sure you want to remove this collaborator?')) {
                $.ajax({
                    url: "{{ url('/branch/stc/projects/collaborations/remove') }}",
                    type: 'GET',
                    data: {
                        id: collabId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if(response.success) {
                            swalSuccess('success', 'Collaborator removed successfully.');
                            // Reset checkboxes
                            $('#select-all-collaborations').prop('checked', false);
                            updateBulkDeleteButton();
                            loadCollaborations();
                        } else {
                            swalSuccess('error', response.message || 'Error removing collaborator.');
                        }
                    },
                    error: function() {
                        swalSuccess('error', 'Error removing collaborator. Please try again.');
                    }
                });
            }
        });

        // delete function
        // Delete project
        $('.delete-btn').on('click', function(e){
            e.preventDefault();
            var id = $('#delete_id').val();
            $.ajax({
                type: 'get',
                data: {
                    id: id,
                },
                url: "{{ url('/branch/stc/projects/delete') }}",
                success: function(response) {
                    if(response.success==true){
                        swalSuccess('success', 'Record deleted.');
                        if(projectsTable !== null && $.fn.DataTable.isDataTable('#example1')) {
                            projectsTable.ajax.reload(null, false);
                        } else {
                            if ( $.fn.DataTable.isDataTable('#example1') ) {
                                $('#example1').DataTable().destroy();
                            }
                            var dataTableAct="active";
                            getProjects(dataTableAct);
                        }
                        $('.close-btn').click();
                    }else{
                        swalSuccess('error', response.message);
                    }
                }
            });
        });
    });
  </script>

</body>
</html>
<!-- Add/Edit Project Modal -->
<div class="modal fade" id="edit-project-modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="project-modal-title">Add Project</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="project-form">
        <div class="modal-body">
          <input type="hidden" id="project_id">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Customer <span class="text-danger">*</span></label>
                <select class="form-control" id="project_customer_id" required>
                  <option value="">-- Select Customer --</option>
                  @if(isset($customers) && count($customers) > 0)
                    @foreach($customers as $customer)
                      <option value="{{ $customer->stc_customer_id }}">{{ $customer->stc_customer_name }}</option>
                    @endforeach
                  @endif
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Title <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="project_title" placeholder="Enter Project Title" required>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Reference Number</label>
                <input type="text" class="form-control" id="project_reference_number" placeholder="Enter Reference Number">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Address</label>
                <input type="text" class="form-control" id="project_address" placeholder="Enter Address">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>City</label>
                <select class="form-control" id="project_city_id">
                  <option value="">-- Select City --</option>
                  @if(isset($cities) && count($cities) > 0)
                    @foreach($cities as $city)
                      <option value="{{ $city->stc_city_id }}">{{ $city->stc_city_name }}</option>
                    @endforeach
                  @endif
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>State</label>
                <select class="form-control" id="project_state_id">
                  <option value="">-- Select State --</option>
                  @if(isset($states) && count($states) > 0)
                    @foreach($states as $state)
                      <option value="{{ $state->stc_state_id }}">{{ $state->stc_state_name }}</option>
                    @endforeach
                  @endif
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Responsive Person</label>
                <input type="text" class="form-control" id="project_responsive_person" placeholder="Enter Responsive Person">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Supervisor Quantity</label>
                <input type="number" class="form-control" id="project_supervisor_qty" placeholder="Enter Supervisor Quantity">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Begin Date</label>
                <input type="date" class="form-control" id="project_beg_date">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>End Date</label>
                <input type="date" class="form-control" id="project_end_date">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Begin Budget</label>
                <input type="number" step="0.01" class="form-control" id="project_beg_budget" placeholder="Enter Budget">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
                <label>Status</label>
                <select class="form-control" id="project_status">
                  <option value="1">Active</option>
                  <option value="0">Inactive</option>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Editable Min Count</label>
                <input type="number" class="form-control" id="project_editable_mincount" placeholder="Enter Min Count">
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Editable Max Count</label>
                <input type="number" class="form-control" id="project_editable_maxcount" placeholder="Enter Max Count">
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Project</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Add/Edit Department Modal -->
<div class="modal fade" id="add-department-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="department-modal-title">Add Department</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="department-form">
        <div class="modal-body">
          <input type="hidden" id="department_id">
          <div class="form-group">
            <label>Project <span class="text-danger">*</span></label>
            <select class="form-control" id="department_project_id" required>
              <option value="">-- Select Project --</option>
              @if(isset($projects) && count($projects) > 0)
                @foreach($projects as $project)
                  <option value="{{ $project->stc_cust_project_id }}">{{ $project->stc_cust_project_title }}</option>
                @endforeach
              @endif
            </select>
          </div>
          <div class="form-group">
            <label>Location <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="department_location" placeholder="Enter Location" required>
          </div>
          <div class="form-group">
            <label>Department Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="department_name" placeholder="Enter Department Name" required>
          </div>
          <div class="form-group">
            <label>Status</label>
            <select class="form-control" id="department_status">
              <option value="1">Active</option>
              <option value="0">Inactive</option>
            </select>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- Delete Department Modal -->
<div class="modal fade" id="delete-department-modal">
  <div class="modal-dialog">
    <div class="modal-content bg-danger">
      <div class="modal-header">
        <h4 class="modal-title">Delete Department</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this department?</p>
        <p class="text-warning"><small>This action cannot be undone.</small></p>
        <input type="hidden" id="delete_department_id">
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirm-delete-department">Delete</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

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