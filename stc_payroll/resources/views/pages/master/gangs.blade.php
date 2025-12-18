@extends('layouts.header')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Gangs Management</h3>
    <div class="card-tools">
      <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addGangModal">
        <i class="fas fa-plus"></i> Add Gang
      </button>
    </div>
  </div>
  <div class="card-body">
    <!-- DataTable -->
    <div class="table-responsive">
      <table id="gangsTable" class="table table-bordered table-striped">
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
<div class="modal fade" id="viewGangModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">View Gang Details</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body" id="viewGangContent">
        <!-- Content will be loaded via AJAX -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editGangModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Gang</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="editGangForm">
        <div class="modal-body">
          <input type="hidden" id="editGangId" name="id">
          <div class="form-group">
            <label>Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="editGangName" name="name" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update Gang</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addGangModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Gang</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="addGangForm">
        <div class="modal-body">
          <div class="form-group">
            <label>Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="addGangName" name="name" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add Gang</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#gangsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "/stc/stc_payroll/public/master/gangs/list",
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

    // View Gang
    $(document).on('click', '.view-btn', function() {
        var id = $(this).data('id');
        $.ajax({
            url: "/stc/stc_payroll/public/master/gangs/show/" + id,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    var gang = response.data;
                    var html = '<div class="row">' +
                        '<div class="col-md-6 mb-3"><strong>ID:</strong><br><span class="text-muted">' + (gang.id || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Name:</strong><br><span class="text-muted">' + (gang.name || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Created At:</strong><br><span class="text-muted">' + (gang.created_at || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Updated At:</strong><br><span class="text-muted">' + (gang.updated_at || 'N/A') + '</span></div>' +
                        '</div>';
                    $('#viewGangContent').html(html);
                    $('#viewGangModal').modal('show');
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load gang details'
                });
            }
        });
    });

    // Edit Gang - Load data
    $(document).on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $.ajax({
            url: "/stc/stc_payroll/public/master/gangs/show/" + id,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    var gang = response.data;
                    $('#editGangId').val(gang.id);
                    $('#editGangName').val(gang.name || '');
                    $('#editGangModal').modal('show');
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load gang data'
                });
            }
        });
    });

    // Update Gang
    $('#editGangForm').on('submit', function(e) {
        e.preventDefault();
        var id = $('#editGangId').val();
        var formData = $(this).serialize();
        
        $.ajax({
            url: "/stc/stc_payroll/public/master/gangs/" + id,
            type: 'POST',
            data: formData + '&_method=PUT',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#editGangModal').modal('hide');
                    $('#editGangForm')[0].reset();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message || 'Gang updated successfully',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    table.draw();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to update gang'
                    });
                }
            },
            error: function(xhr) {
                var message = 'Failed to update gang';
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

    // Add Gang
    $('#addGangForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        
        $.ajax({
            url: "/stc/stc_payroll/public/master/gangs",
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#addGangModal').modal('hide');
                    $('#addGangForm')[0].reset();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message || 'Gang added successfully',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    table.draw();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to add gang'
                    });
                }
            },
            error: function(xhr) {
                var message = 'Failed to add gang';
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

    // Delete Gang
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
                    url: "/stc/stc_payroll/public/master/gangs/" + id,
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
                                text: response.message || 'Gang deleted successfully',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            table.draw();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Failed to delete gang'
                            });
                        }
                    },
                    error: function(xhr) {
                        var message = 'Failed to delete gang';
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
