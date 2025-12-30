@extends('layouts.header')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Companies Management</h3>
    <div class="card-tools">
      @if(($permissions['edit'] ?? false))
      <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addCompanyModal">
        <i class="fas fa-plus"></i> Add Company
      </button>
      @endif
    </div>
  </div>
  <div class="card-body">
    <!-- DataTable -->
    <div class="table-responsive">
      <table id="companiesTable" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Code</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Status</th>
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
<div class="modal fade" id="viewCompanyModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">View Company Details</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body" id="viewCompanyContent">
        <!-- Content will be loaded via AJAX -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editCompanyModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Company</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="editCompanyForm">
        <div class="modal-body">
          <input type="hidden" id="editCompanyId" name="id">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Company Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="editCompanyName" name="name" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Company Code</label>
                <input type="text" class="form-control" id="editCompanyCode" name="code" placeholder="Optional unique code">
                <small class="form-text text-muted">Optional unique identifier for the company</small>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" id="editCompanyEmail" name="email">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Phone</label>
                <input type="text" class="form-control" id="editCompanyPhone" name="phone">
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Address</label>
            <textarea class="form-control" id="editCompanyAddress" name="address" rows="3"></textarea>
          </div>
          <div class="form-group">
            <label>Status <span class="text-danger">*</span></label>
            <select class="form-control" id="editCompanyStatus" name="status" required>
              <option value="ACTIVE">Active</option>
              <option value="INACTIVE">Inactive</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update Company</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addCompanyModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Company</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="addCompanyForm">
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Company Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="addCompanyName" name="name" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Company Code</label>
                <input type="text" class="form-control" id="addCompanyCode" name="code" placeholder="Optional unique code">
                <small class="form-text text-muted">Optional unique identifier for the company</small>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" id="addCompanyEmail" name="email">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Phone</label>
                <input type="text" class="form-control" id="addCompanyPhone" name="phone">
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Address</label>
            <textarea class="form-control" id="addCompanyAddress" name="address" rows="3"></textarea>
          </div>
          <div class="form-group">
            <label>Status <span class="text-danger">*</span></label>
            <select class="form-control" id="addCompanyStatus" name="status" required>
              <option value="ACTIVE" selected>Active</option>
              <option value="INACTIVE">Inactive</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add Company</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#companiesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: window.appBaseUrl + "/master/companies/list",
            type: 'GET'
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'code', name: 'code' },
            { data: 'email', name: 'email' },
            { data: 'phone', name: 'phone' },
            { 
                data: 'status', 
                name: 'status',
                render: function(data, type, row) {
                    var badgeClass = data === 'ACTIVE' ? 'badge-success' : 'badge-danger';
                    return '<span class="badge ' + badgeClass + '">' + data + '</span>';
                }
            },
            { data: 'created_at', name: 'created_at' },
            { data: 'updated_at', name: 'updated_at' },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    var id = row.id || data;
                    var viewBtn = '<button class="btn btn-info btn-sm view-btn" data-id="' + id + '" title="View"><i class="fas fa-eye"></i></button> ';
                    var editBtn = (@json($permissions['edit'] ?? false) ? 
                        '<button class="btn btn-warning btn-sm edit-btn" data-id="' + id + '" title="Edit"><i class="fas fa-edit"></i></button> ' : 
                        '<button class="btn btn-warning btn-sm" disabled title="No permission"><i class="fas fa-edit"></i></button> ');
                    var deleteBtn = (@json($permissions['edit'] ?? false) ? 
                        '<button class="btn btn-danger btn-sm delete-btn" data-id="' + id + '" title="Delete"><i class="fas fa-trash"></i></button>' : 
                        '<button class="btn btn-danger btn-sm" disabled title="No permission"><i class="fas fa-trash"></i></button>');
                    return viewBtn + editBtn + deleteBtn;
                }
            }
        ],
        order: [[1, 'asc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
    });

    // View Company
    $(document).on('click', '.view-btn', function() {
        var id = $(this).data('id');
        $.ajax({
            url: window.appBaseUrl + "/master/companies/show/" + id,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    var company = response.data;
                    var html = '<div class="row">' +
                        '<div class="col-md-6 mb-3"><strong>ID:</strong><br><span class="text-muted">' + (company.id || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Company Name:</strong><br><span class="text-muted">' + (company.name || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Company Code:</strong><br><span class="text-muted">' + (company.code || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Status:</strong><br><span class="badge badge-' + (company.status === 'ACTIVE' ? 'success' : 'danger') + '">' + company.status + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Email:</strong><br><span class="text-muted">' + (company.email || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Phone:</strong><br><span class="text-muted">' + (company.phone || 'N/A') + '</span></div>' +
                        '<div class="col-md-12 mb-3"><strong>Address:</strong><br><span class="text-muted">' + (company.address || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Created At:</strong><br><span class="text-muted">' + (company.created_at || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Updated At:</strong><br><span class="text-muted">' + (company.updated_at || 'N/A') + '</span></div>' +
                        '</div>';
                    $('#viewCompanyContent').html(html);
                    $('#viewCompanyModal').modal('show');
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load company details'
                });
            }
        });
    });

    // Edit Company - Load data
    $(document).on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        $.ajax({
            url: window.appBaseUrl + "/master/companies/show/" + id,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    var company = response.data;
                    $('#editCompanyId').val(company.id);
                    $('#editCompanyName').val(company.name || '');
                    $('#editCompanyCode').val(company.code || '');
                    $('#editCompanyEmail').val(company.email || '');
                    $('#editCompanyPhone').val(company.phone || '');
                    $('#editCompanyAddress').val(company.address || '');
                    $('#editCompanyStatus').val(company.status || 'ACTIVE');
                    $('#editCompanyModal').modal('show');
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load company data'
                });
            }
        });
    });

    // Update Company
    $('#editCompanyForm').on('submit', function(e) {
        e.preventDefault();
        var id = $('#editCompanyId').val();
        var formData = $(this).serialize();
        
        $.ajax({
            url: window.appBaseUrl + "/master/companies/" + id,
            type: 'POST',
            data: formData + '&_method=PUT',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#editCompanyModal').modal('hide');
                    $('#editCompanyForm')[0].reset();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message || 'Company updated successfully',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    table.draw();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to update company'
                    });
                }
            },
            error: function(xhr) {
                var message = 'Failed to update company';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = Object.values(xhr.responseJSON.errors).flat();
                    message = errors.join('<br>');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: message
                });
            }
        });
    });

    // Add Company
    $('#addCompanyForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        
        $.ajax({
            url: window.appBaseUrl + "/master/companies",
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#addCompanyModal').modal('hide');
                    $('#addCompanyForm')[0].reset();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message || 'Company added successfully',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    table.draw();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to add company'
                    });
                }
            },
            error: function(xhr) {
                var message = 'Failed to add company';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = Object.values(xhr.responseJSON.errors).flat();
                    message = errors.join('<br>');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: message
                });
            }
        });
    });

    // Delete Company
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
                    url: window.appBaseUrl + "/master/companies/" + id,
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
                                text: response.message || 'Company deleted successfully',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            table.draw();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Failed to delete company'
                            });
                        }
                    },
                    error: function(xhr) {
                        var message = 'Failed to delete company';
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

