@extends('layouts.header')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Sites Management</h3>
    <div class="card-tools">
      @if(($permissions['edit'] ?? false))
      <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addSiteModal">
        <i class="fas fa-plus"></i> Add Site
      </button>
      @endif
    </div>
  </div>
  <div class="card-body">
    <!-- DataTable -->
    <div class="table-responsive">
      <table id="sitesTable" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Under Contract</th>
            <th>Nature of Work</th>
            <th>Work Order No</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="viewSiteModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">View Site Details</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body" id="viewSiteContent">
        <!-- Content will be loaded via AJAX -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editSiteModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Site</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="editSiteForm">
        <div class="modal-body">
          <input type="hidden" id="editSiteId" name="id">
          <div class="form-group">
            <label>Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="editSiteName" name="name" required>
          </div>
          <div class="form-group">
            <label>Under Contract</label>
            <input type="text" class="form-control" id="editSiteUnderContract" name="under_contract">
          </div>
          <div class="form-group">
            <label>Nature of Work</label>
            <input type="text" class="form-control" id="editSiteNatureOfWork" name="natureofwork">
          </div>
          <div class="form-group">
            <label>Work Order No</label>
            <input type="text" class="form-control" id="editSiteWorkOrderNo" name="workorderno">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update Site</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addSiteModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Site</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="addSiteForm">
        <div class="modal-body">
          <div class="form-group">
            <label>Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="addSiteName" name="name" required>
          </div>
          <div class="form-group">
            <label>Under Contract</label>
            <input type="text" class="form-control" id="addSiteUnderContract" name="under_contract">
          </div>
          <div class="form-group">
            <label>Nature of Work</label>
            <input type="text" class="form-control" id="addSiteNatureOfWork" name="natureofwork">
          </div>
          <div class="form-group">
            <label>Work Order No</label>
            <input type="text" class="form-control" id="addSiteWorkOrderNo" name="workorderno">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add Site</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#sitesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: window.appBaseUrl + "/master/sites/list",
            type: 'GET'
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'under_contract', name: 'under_contract' },
            { data: 'natureofwork', name: 'natureofwork' },
            { data: 'workorderno', name: 'workorderno' },
            { data: 'created_at', name: 'created_at' },
            { data: 'updated_at', name: 'updated_at' },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    var id = row.id || data;
                    var viewBtn = @json($permissions['view'] ?? false) ? 
                        '<button class="btn btn-info btn-sm view-btn" data-id="' + id + '" title="View"><i class="fas fa-eye"></i></button> ' : '';
                    var editBtn = @json($permissions['edit'] ?? false) ? 
                        '<button class="btn btn-warning btn-sm edit-btn" data-id="' + id + '" title="Edit"><i class="fas fa-edit"></i></button> ' : 
                        '<button class="btn btn-warning btn-sm" disabled title="No permission"><i class="fas fa-edit"></i></button> ';
                    var deleteBtn = @json($permissions['delete'] ?? false) ? 
                        '<button class="btn btn-danger btn-sm delete-btn" data-id="' + id + '" title="Delete"><i class="fas fa-trash"></i></button>' : 
                        '<button class="btn btn-danger btn-sm" disabled title="No permission"><i class="fas fa-trash"></i></button>';
                    return viewBtn + editBtn + deleteBtn;
                }
            }
        ],
        order: [[0, 'desc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
    });

    // View Site
    $(document).on('click', '.view-btn', function() {
        var id = $(this).data('id');
        $.ajax({
            url: "/stc/stc_payroll/master/sites/show/" + id,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    var site = response.data;
                    var html = '<div class="row">' +
                        '<div class="col-md-6 mb-3"><strong>ID:</strong><br><span class="text-muted">' + (site.id || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Name:</strong><br><span class="text-muted">' + (site.name || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Under Contract:</strong><br><span class="text-muted">' + (site.under_contract || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Nature of Work:</strong><br><span class="text-muted">' + (site.natureofwork || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Work Order No:</strong><br><span class="text-muted">' + (site.workorderno || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Created At:</strong><br><span class="text-muted">' + (site.created_at || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Updated At:</strong><br><span class="text-muted">' + (site.updated_at || 'N/A') + '</span></div>' +
                        '</div>';
                    $('#viewSiteContent').html(html);
                    $('#viewSiteModal').modal('show');
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load site details'
                });
            }
        });
    });

    // Edit Site - Load data
    $(document).on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $.ajax({
            url: "/stc/stc_payroll/master/sites/show/" + id,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    var site = response.data;
                    $('#editSiteId').val(site.id);
                    $('#editSiteName').val(site.name || '');
                    $('#editSiteUnderContract').val(site.under_contract || '');
                    $('#editSiteNatureOfWork').val(site.natureofwork || '');
                    $('#editSiteWorkOrderNo').val(site.workorderno || '');
                    $('#editSiteModal').modal('show');
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load site data'
                });
            }
        });
    });

    // Update Site
    $('#editSiteForm').on('submit', function(e) {
        e.preventDefault();
        var id = $('#editSiteId').val();
        var formData = $(this).serialize();
        
        $.ajax({
            url: "/stc/stc_payroll/master/sites/" + id,
            type: 'POST',
            data: formData + '&_method=PUT',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#editSiteModal').modal('hide');
                    $('#editSiteForm')[0].reset();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message || 'Site updated successfully',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    table.draw();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to update site'
                    });
                }
            },
            error: function(xhr) {
                var message = 'Failed to update site';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors;
                    message = Object.values(errors).flat().join('<br>');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: message
                });
            }
        });
    });

    // Add Site
    $('#addSiteForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        
        $.ajax({
            url: "/stc/stc_payroll/master/sites",
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#addSiteModal').modal('hide');
                    $('#addSiteForm')[0].reset();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message || 'Site added successfully',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    table.draw();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to add site'
                    });
                }
            },
            error: function(xhr) {
                var message = 'Failed to add site';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors;
                    message = Object.values(errors).flat().join('<br>');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: message
                });
            }
        });
    });

    // Delete Site
    $(document).on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/stc/stc_payroll/master/sites/" + id,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message || 'Site deleted successfully',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            table.draw();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Failed to delete site'
                            });
                        }
                    },
                    error: function(xhr) {
                        var message = 'Failed to delete site';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: message
                        });
                    }
                });
            }
        });
    });
});
</script>
@endpush
