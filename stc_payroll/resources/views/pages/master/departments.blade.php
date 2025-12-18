@extends('layouts.header')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Departments Management</h3>
    <div class="card-tools">
      <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addDepartmentModal">
        <i class="fas fa-plus"></i> Add Department
      </button>
    </div>
  </div>
  <div class="card-body">
    <!-- DataTable -->
    <div class="table-responsive">
      <table id="departmentsTable" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
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
<div class="modal fade" id="viewDepartmentModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">View Department Details</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body" id="viewDepartmentContent">
        <!-- Content will be loaded via AJAX -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editDepartmentModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Department</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="editDepartmentForm">
        <div class="modal-body">
          <input type="hidden" id="editDepartmentId" name="id">
          <div class="form-group">
            <label>Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="editDepartmentName" name="name" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update Department</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addDepartmentModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Department</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="addDepartmentForm">
        <div class="modal-body">
          <div class="form-group">
            <label>Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="addDepartmentName" name="name" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add Department</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#departmentsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "/stc/stc_payroll/public/master/departments/list",
            type: 'GET'
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'created_at', name: 'created_at' },
            { data: 'updated_at', name: 'updated_at' },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    var id = row.id || data;
                    return '<button class="btn btn-info btn-sm view-btn" data-id="' + id + '" title="View"><i class="fas fa-eye"></i></button> ' +
                           '<button class="btn btn-warning btn-sm edit-btn" data-id="' + id + '" title="Edit"><i class="fas fa-edit"></i></button> ' +
                           '<button class="btn btn-danger btn-sm delete-btn" data-id="' + id + '" title="Delete"><i class="fas fa-trash"></i></button>';
                }
            }
        ],
        order: [[0, 'desc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
    });

    // View Department
    $(document).on('click', '.view-btn', function() {
        var id = $(this).data('id');
        $.ajax({
            url: "/stc/stc_payroll/public/master/departments/show/" + id,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    var department = response.data;
                    var html = '<div class="row">' +
                        '<div class="col-md-6 mb-3"><strong>ID:</strong><br><span class="text-muted">' + (department.id || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Name:</strong><br><span class="text-muted">' + (department.name || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Created At:</strong><br><span class="text-muted">' + (department.created_at || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Updated At:</strong><br><span class="text-muted">' + (department.updated_at || 'N/A') + '</span></div>' +
                        '</div>';
                    $('#viewDepartmentContent').html(html);
                    $('#viewDepartmentModal').modal('show');
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load department details'
                });
            }
        });
    });

    // Edit Department - Load data
    $(document).on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $.ajax({
            url: "/stc/stc_payroll/public/master/departments/show/" + id,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    var department = response.data;
                    $('#editDepartmentId').val(department.id);
                    $('#editDepartmentName').val(department.name || '');
                    $('#editDepartmentModal').modal('show');
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load department data'
                });
            }
        });
    });

    // Update Department
    $('#editDepartmentForm').on('submit', function(e) {
        e.preventDefault();
        var id = $('#editDepartmentId').val();
        var formData = $(this).serialize();
        
        $.ajax({
            url: "/stc/stc_payroll/public/master/departments/" + id,
            type: 'POST',
            data: formData + '&_method=PUT',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#editDepartmentModal').modal('hide');
                    $('#editDepartmentForm')[0].reset();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message || 'Department updated successfully',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    table.draw();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to update department'
                    });
                }
            },
            error: function(xhr) {
                var message = 'Failed to update department';
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

    // Add Department
    $('#addDepartmentForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        
        $.ajax({
            url: "/stc/stc_payroll/public/master/departments",
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#addDepartmentModal').modal('hide');
                    $('#addDepartmentForm')[0].reset();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message || 'Department added successfully',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    table.draw();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to add department'
                    });
                }
            },
            error: function(xhr) {
                var message = 'Failed to add department';
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

    // Delete Department
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
                    url: "/stc/stc_payroll/public/master/departments/" + id,
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
                                text: response.message || 'Department deleted successfully',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            table.draw();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Failed to delete department'
                            });
                        }
                    },
                    error: function(xhr) {
                        var message = 'Failed to delete department';
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
