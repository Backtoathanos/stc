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
                      <a class="nav-link active" id="projects-tab" data-toggle="tab" href="#projects" role="tab" aria-controls="projects" aria-selected="true">Projects</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="collaborations-tab" data-toggle="tab" href="#collaborations" role="tab" aria-controls="collaborations" aria-selected="false">Project Collaborations</a>
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
        var dataTableAct="active";
        getProjects(dataTableAct);      
      
        // get Rack function
        function getProjects() {
            // Initialize DataTable
            let table = $('#example1').DataTable({
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
            table.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        }



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
                        if ( $.fn.DataTable.isDataTable('#example1') ) {
                        $('#example1').DataTable().destroy();
                        }
                        dataTableAct="active";
                        getProjects(dataTableAct);
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