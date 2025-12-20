@extends('layouts.header')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">User Management</h3>
    <div class="card-tools">
      @if(($permissions['edit'] ?? false))
      <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addUserModal">
        <i class="fas fa-plus"></i> Add User
      </button>
      @endif
    </div>
  </div>
  <div class="card-body">
    <!-- DataTable -->
    <div class="table-responsive">
      <table id="usersTable" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>Created At</th>
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
<div class="modal fade" id="viewUserModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">View User Details</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body" id="viewUserContent">
        <!-- Content will be loaded via AJAX -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit User</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="editUserForm">
        <div class="modal-body">
          <input type="hidden" id="editUserId" name="id">
          <div class="form-group">
            <label>Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="editUserName" name="name" required>
          </div>
          <div class="form-group">
            <label>Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="editUserEmail" name="email" required>
          </div>
          <div class="form-group">
            <label>Password <small class="text-muted">(Leave blank to keep current password)</small></label>
            <input type="password" class="form-control" id="editUserPassword" name="password" placeholder="Enter new password">
          </div>
          <div class="form-group">
            <label>Status <span class="text-danger">*</span></label>
            <select class="form-control" id="editUserStatus" name="status" required>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update User</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New User</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="addUserForm">
        <div class="modal-body">
          <div class="form-group">
            <label>Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="addUserName" name="name" required>
          </div>
          <div class="form-group">
            <label>Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control" id="addUserEmail" name="email" required>
          </div>
          <div class="form-group">
            <label>Password <span class="text-danger">*</span></label>
            <input type="password" class="form-control" id="addUserPassword" name="password" required minlength="6">
            <small class="text-muted">Minimum 6 characters</small>
          </div>
          <div class="form-group">
            <label>Status <span class="text-danger">*</span></label>
            <select class="form-control" id="addUserStatus" name="status" required>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add User</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Permissions Modal -->
<div class="modal fade" id="permissionsModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Manage Permissions - <span id="permissionsUserName"></span></h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="permissionsForm">
        <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
          <input type="hidden" id="permissionsUserId" name="user_id">
          <div id="permissionsContent">
            <!-- Permissions will be loaded here -->
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Permissions</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    var table = $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "/stc/stc_payroll/admin/users/list",
            type: 'GET'
        },
        columns: [
            { data: 'id', name: 'id' },
            { 
                data: 'name', 
                name: 'name',
                render: function(data, type, row) {
                    var isRoot = row.is_root || false;
                    if (isRoot) {
                        return data + ' <span class="badge badge-danger">ROOT</span>';
                    }
                    return data;
                }
            },
            { data: 'email', name: 'email' },
            { 
                data: 'status', 
                name: 'status',
                render: function(data, type, row) {
                    var status = data || 'active';
                    var badgeClass = status === 'active' ? 'badge-success' : 'badge-danger';
                    return '<span class="badge ' + badgeClass + '">' + status.toUpperCase() + '</span>';
                }
            },
            { data: 'created_at', name: 'created_at' },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    var id = row.id || data;
                    var isRoot = row.is_root || false;
                    var status = row.status || 'active';
                    var currentUserId = @json(auth()->id());
                    var isOwnRow = (id == currentUserId);
                    var editBtn = '';
                    var deleteBtn = '';
                    var statusBtn = '';
                    var permissionsBtn = '';
                    
                    var hasView = @json($permissions['view'] ?? false);
                    var hasEdit = @json($permissions['edit'] ?? false);
                    var hasDelete = @json($permissions['delete'] ?? false);
                    
                    var viewBtn = hasView ? 
                        '<button class="btn btn-info btn-sm view-btn" data-id="' + id + '" title="View"><i class="fas fa-eye"></i></button> ' : '';
                    
                    if (!isRoot) {
                        // Edit button - users can't edit their own row
                        if (hasEdit && !isOwnRow) {
                            editBtn = '<button class="btn btn-warning btn-sm edit-btn" data-id="' + id + '" title="Edit"><i class="fas fa-edit"></i></button> ';
                        } else if (isOwnRow) {
                            editBtn = '<button class="btn btn-warning btn-sm" disabled title="You cannot edit your own account"><i class="fas fa-edit"></i></button> ';
                        } else {
                            editBtn = '<button class="btn btn-warning btn-sm" disabled title="No permission"><i class="fas fa-edit"></i></button> ';
                        }
                        
                        // Delete button - users can't delete their own row
                        if (hasDelete && !isOwnRow) {
                            deleteBtn = '<button class="btn btn-danger btn-sm delete-btn" data-id="' + id + '" title="Delete"><i class="fas fa-trash"></i></button> ';
                        } else if (isOwnRow) {
                            deleteBtn = '<button class="btn btn-danger btn-sm" disabled title="You cannot delete your own account"><i class="fas fa-trash"></i></button> ';
                        } else {
                            deleteBtn = '<button class="btn btn-danger btn-sm" disabled title="No permission"><i class="fas fa-trash"></i></button> ';
                        }
                        
                        // Status button - users can't change their own status
                        if (!isOwnRow) {
                            var statusIcon = status === 'active' ? 'fa-toggle-on' : 'fa-toggle-off';
                            var statusClass = status === 'active' ? 'btn-success' : 'btn-secondary';
                            statusBtn = '<button class="btn ' + statusClass + ' btn-sm toggle-status-btn" data-id="' + id + '" data-status="' + status + '" title="Toggle Status"><i class="fas ' + statusIcon + '"></i></button> ';
                        } else {
                            statusBtn = '<button class="btn btn-success btn-sm" disabled title="You cannot change your own status"><i class="fas fa-toggle-on"></i></button> ';
                        }
                        
                        // Permissions button - users can't change their own permissions
                        if (!isOwnRow) {
                            permissionsBtn = '<button class="btn btn-primary btn-sm permissions-btn" data-id="' + id + '" title="Permissions"><i class="fas fa-key"></i></button>';
                        } else {
                            permissionsBtn = '<button class="btn btn-primary btn-sm" disabled title="You cannot change your own permissions"><i class="fas fa-key"></i></button>';
                        }
                    } else {
                        editBtn = '<button class="btn btn-warning btn-sm" disabled title="Root user cannot be edited"><i class="fas fa-edit"></i></button> ';
                        deleteBtn = '<button class="btn btn-danger btn-sm" disabled title="Root user cannot be deleted"><i class="fas fa-trash"></i></button> ';
                        statusBtn = '<button class="btn btn-success btn-sm" disabled title="Root user status cannot be changed"><i class="fas fa-toggle-on"></i></button> ';
                        permissionsBtn = '<button class="btn btn-primary btn-sm" disabled title="Root user has all permissions"><i class="fas fa-key"></i></button>';
                    }
                    
                    return viewBtn + editBtn + deleteBtn + statusBtn + permissionsBtn;
                }
            }
        ],
        order: [[0, 'desc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
    });

    // View User
    $(document).on('click', '.view-btn', function() {
        var id = $(this).data('id');
        $.ajax({
            url: "/stc/stc_payroll/admin/users/show/" + id,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    var user = response.data;
                    var rootBadge = user.is_root ? ' <span class="badge badge-danger">ROOT USER</span>' : '';
                    var html = '<div class="row">' +
                               '<div class="col-md-12">' +
                               '<table class="table table-bordered">' +
                               '<tr><th>ID</th><td>' + user.id + '</td></tr>' +
                               '<tr><th>Name</th><td>' + user.name + rootBadge + '</td></tr>' +
                               '<tr><th>Email</th><td>' + user.email + '</td></tr>' +
                               '<tr><th>Status</th><td><span class="badge ' + (user.status === 'active' ? 'badge-success' : 'badge-danger') + '">' + (user.status || 'active').toUpperCase() + '</span></td></tr>' +
                               '<tr><th>Created At</th><td>' + user.created_at + '</td></tr>' +
                               '</table>' +
                               (user.is_root ? '<div class="alert alert-warning mt-3"><i class="fas fa-shield-alt"></i> This is a root user and cannot be modified or deleted.</div>' : '') +
                               '</div>' +
                               '</div>';
                    $('#viewUserContent').html(html);
                    $('#viewUserModal').modal('show');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to load user details'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load user details'
                });
            }
        });
    });

    // Edit User - Load Data
    $(document).on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        var currentUserId = @json(auth()->id());
        
        // Prevent editing own account
        if (id == currentUserId) {
            Swal.fire({
                icon: 'warning',
                title: 'Access Denied',
                text: 'You cannot edit your own account'
            });
            return;
        }
        
        $.ajax({
            url: "/stc/stc_payroll/admin/users/show/" + id,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    var user = response.data;
                    
                    // Prevent editing root user
                    if (user.is_root) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Access Denied',
                            text: 'Root user cannot be modified'
                        });
                        return;
                    }
                    
                    $('#editUserId').val(user.id);
                    $('#editUserName').val(user.name);
                    $('#editUserEmail').val(user.email);
                    $('#editUserStatus').val(user.status || 'active');
                    $('#editUserPassword').val('');
                    $('#editUserModal').modal('show');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to load user details'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load user details'
                });
            }
        });
    });

    // Update User
    $('#editUserForm').on('submit', function(e) {
        e.preventDefault();
        var id = $('#editUserId').val();
        
        // Double check - prevent root user modification
        $.ajax({
            url: "/stc/stc_payroll/admin/users/show/" + id,
            type: 'GET',
            success: function(checkResponse) {
                if (checkResponse.success && checkResponse.data.is_root) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Access Denied',
                        text: 'Root user cannot be modified'
                    });
                    $('#editUserModal').modal('hide');
                    return;
                }
                
                // Proceed with update
                var formData = {
                    name: $('#editUserName').val(),
                    email: $('#editUserEmail').val(),
                    status: $('#editUserStatus').val(),
                    _token: $('meta[name="csrf-token"]').attr('content')
                };
                
                var password = $('#editUserPassword').val();
                if (password) {
                    formData.password = password;
                }
                
                $.ajax({
                    url: "/stc/stc_payroll/admin/users/" + id,
                    type: 'PUT',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message || 'User updated successfully'
                            });
                            $('#editUserModal').modal('hide');
                            table.ajax.reload();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Failed to update user'
                            });
                        }
                    },
                    error: function(xhr) {
                        var message = 'Failed to update user';
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

    // Add User
    $('#addUserForm').on('submit', function(e) {
        e.preventDefault();
        var formData = {
            name: $('#addUserName').val(),
            email: $('#addUserEmail').val(),
            password: $('#addUserPassword').val(),
            status: $('#addUserStatus').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        
        $.ajax({
            url: "/stc/stc_payroll/admin/users",
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message || 'User created successfully'
                    });
                    $('#addUserForm')[0].reset();
                    $('#addUserModal').modal('hide');
                    table.ajax.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to create user'
                    });
                }
            },
            error: function(xhr) {
                var message = 'Failed to create user';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = Object.values(xhr.responseJSON.errors).flat();
                    message = errors.join(', ');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message
                });
            }
        });
    });

    // Delete User
    $(document).on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        var currentUserId = @json(auth()->id());
        
        // Prevent deleting own account
        if (id == currentUserId) {
            Swal.fire({
                icon: 'warning',
                title: 'Access Denied',
                text: 'You cannot delete your own account'
            });
            return;
        }
        
        // Check if root user before showing confirmation
        $.ajax({
            url: "/stc/stc_payroll/admin/users/show/" + id,
            type: 'GET',
            success: function(checkResponse) {
                if (checkResponse.success && checkResponse.data.is_root) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Access Denied',
                        text: 'Root user cannot be deleted'
                    });
                    return;
                }
                
                // Proceed with delete confirmation
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "/stc/stc_payroll/admin/users/" + id,
                            type: 'DELETE',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Deleted!',
                                        text: response.message || 'User deleted successfully'
                                    });
                                    table.ajax.reload();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.message || 'Failed to delete user'
                                    });
                                }
                            },
                            error: function(xhr) {
                                var message = 'Failed to delete user';
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
            }
        });
    });

    // Toggle User Status
    $(document).on('click', '.toggle-status-btn', function() {
        var id = $(this).data('id');
        var currentUserId = @json(auth()->id());
        
        // Prevent changing own status
        if (id == currentUserId) {
            Swal.fire({
                icon: 'warning',
                title: 'Access Denied',
                text: 'You cannot change your own status'
            });
            return;
        }
        
        var currentStatus = $(this).data('status');
        var newStatus = currentStatus === 'active' ? 'inactive' : 'active';
        var statusText = newStatus === 'active' ? 'activate' : 'deactivate';
        
        Swal.fire({
            title: 'Are you sure?',
            text: 'Do you want to ' + statusText + ' this user?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, ' + statusText + ' it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/stc/stc_payroll/admin/users/" + id + "/toggle-status",
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Status Updated!',
                                text: response.message || 'User status updated successfully'
                            });
                            table.ajax.reload();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Failed to update user status'
                            });
                        }
                    },
                    error: function(xhr) {
                        var message = 'Failed to update user status';
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

    // Load Permissions
    $(document).on('click', '.permissions-btn', function() {
        var id = $(this).data('id');
        var currentUserId = @json(auth()->id());
        
        // Prevent changing own permissions
        if (id == currentUserId) {
            Swal.fire({
                icon: 'warning',
                title: 'Access Denied',
                text: 'You cannot modify your own permissions'
            });
            return;
        }
        
        $.ajax({
            url: "/stc/stc_payroll/admin/users/" + id + "/permissions",
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    var data = response.data;
                    $('#permissionsUserId').val(data.user_id);
                    $('#permissionsUserName').text(data.user_name);
                    
                    // Build permissions HTML
                    var html = '<ul class="list-unstyled">';
                    for (var section in data.permissions) {
                        html += '<li class="mb-3">';
                        html += '<div class="form-check">';
                        html += '<input class="form-check-input section-checkbox" type="checkbox" id="section_' + section.replace(/\s+/g, '_') + '" data-section="' + section + '">';
                        html += '<label class="form-check-label font-weight-bold" for="section_' + section.replace(/\s+/g, '_') + '">' + section + '</label>';
                        html += '</div>';
                        html += '<ul class="list-unstyled ml-4 mt-2">';
                        
                        for (var page in data.permissions[section]) {
                            html += '<li class="mb-2">';
                            html += '<div class="form-check">';
                            html += '<input class="form-check-input page-checkbox" type="checkbox" id="page_' + section.replace(/\s+/g, '_') + '_' + page.replace(/\s+/g, '_') + '" data-section="' + section + '" data-page="' + page + '">';
                            html += '<label class="form-check-label font-weight-semibold" for="page_' + section.replace(/\s+/g, '_') + '_' + page.replace(/\s+/g, '_') + '">' + page + '</label>';
                            html += '</div>';
                            html += '<ul class="list-unstyled ml-4 mt-1">';
                            
                            data.permissions[section][page].forEach(function(perm) {
                                var isChecked = data.user_permissions.indexOf(perm.id) !== -1 ? 'checked' : '';
                                html += '<li class="mb-1">';
                                html += '<div class="form-check">';
                                html += '<input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]" value="' + perm.id + '" id="perm_' + perm.id + '" data-section="' + section + '" data-page="' + page + '" ' + isChecked + '>';
                                html += '<label class="form-check-label" for="perm_' + perm.id + '">' + perm.operation.charAt(0).toUpperCase() + perm.operation.slice(1) + '</label>';
                                html += '</div>';
                                html += '</li>';
                            });
                            
                            html += '</ul>';
                            html += '</li>';
                        }
                        
                        html += '</ul>';
                        html += '</li>';
                    }
                    html += '</ul>';
                    
                    $('#permissionsContent').html(html);
                    
                    // Update section and page checkboxes based on permissions
                    updateSectionAndPageCheckboxes(data.user_permissions);
                    
                    // Add event listeners for section/page checkboxes
                    $('.section-checkbox').on('change', function() {
                        var section = $(this).data('section');
                        var isChecked = $(this).is(':checked');
                        $('.page-checkbox[data-section="' + section + '"]').prop('checked', isChecked);
                        $('.permission-checkbox[data-section="' + section + '"]').prop('checked', isChecked);
                    });
                    
                    $('.page-checkbox').on('change', function() {
                        var section = $(this).data('section');
                        var page = $(this).data('page');
                        var isChecked = $(this).is(':checked');
                        $('.permission-checkbox[data-section="' + section + '"][data-page="' + page + '"]').prop('checked', isChecked);
                        
                        // Update section checkbox
                        var allPageChecked = $('.page-checkbox[data-section="' + section + '"]').length === $('.page-checkbox[data-section="' + section + '"]:checked').length;
                        $('#section_' + section.replace(/\s+/g, '_')).prop('checked', allPageChecked);
                    });
                    
                    $('.permission-checkbox').on('change', function() {
                        var section = $(this).data('section');
                        var page = $(this).data('page');
                        
                        // Update page checkbox
                        var allPermChecked = $('.permission-checkbox[data-section="' + section + '"][data-page="' + page + '"]').length === $('.permission-checkbox[data-section="' + section + '"][data-page="' + page + '"]:checked').length;
                        $('#page_' + section.replace(/\s+/g, '_') + '_' + page.replace(/\s+/g, '_')).prop('checked', allPermChecked);
                        
                        // Update section checkbox
                        var allPageChecked = $('.page-checkbox[data-section="' + section + '"]').length === $('.page-checkbox[data-section="' + section + '"]:checked').length;
                        $('#section_' + section.replace(/\s+/g, '_')).prop('checked', allPageChecked);
                    });
                    
                    $('#permissionsModal').modal('show');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to load permissions'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load permissions'
                });
            }
        });
    });
    
    function updateSectionAndPageCheckboxes(userPermissions) {
        // Update page checkboxes
        $('.page-checkbox').each(function() {
            var section = $(this).data('section');
            var page = $(this).data('page');
            var pagePerms = $('.permission-checkbox[data-section="' + section + '"][data-page="' + page + '"]');
            var checkedPerms = pagePerms.filter(':checked');
            if (pagePerms.length > 0 && pagePerms.length === checkedPerms.length) {
                $(this).prop('checked', true);
            }
        });
        
        // Update section checkboxes
        $('.section-checkbox').each(function() {
            var section = $(this).data('section');
            var sectionPages = $('.page-checkbox[data-section="' + section + '"]');
            var checkedPages = sectionPages.filter(':checked');
            if (sectionPages.length > 0 && sectionPages.length === checkedPages.length) {
                $(this).prop('checked', true);
            }
        });
    }
    
    // Save Permissions
    $('#permissionsForm').on('submit', function(e) {
        e.preventDefault();
        var userId = $('#permissionsUserId').val();
        var formData = {
            permissions: $('.permission-checkbox:checked').map(function() {
                return $(this).val();
            }).get(),
            _token: $('meta[name="csrf-token"]').attr('content')
        };
        
        $.ajax({
            url: "/stc/stc_payroll/admin/users/" + userId + "/permissions",
            type: 'POST',
            data: formData,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message || 'Permissions updated successfully'
                    });
                    $('#permissionsModal').modal('hide');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to update permissions'
                    });
                }
            },
            error: function(xhr) {
                var message = 'Failed to update permissions';
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
    });
});
</script>
@endpush

