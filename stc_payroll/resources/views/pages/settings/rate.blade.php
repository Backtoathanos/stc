@extends('layouts.header')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Rate</h3>
    <div class="card-tools">
      <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addRateModal">
        <i class="fas fa-plus"></i> Add Rate
      </button>
    </div>
  </div>
  <div class="card-body">
    <!-- DataTable -->
    <div class="table-responsive">
      <table id="ratesTable" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>SITE</th>
            <th>CATEGORY</th>
            <th>BASIC</th>
            <th>DA</th>
            <th>ACTION</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Add Rate Modal -->
<div class="modal fade" id="addRateModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Rate</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="addRateForm">
        <div class="modal-body">
          <div class="form-group">
            <label>Site <span class="text-danger">*</span></label>
            <select class="form-control" id="addSiteId" name="site_id" required>
              <option value="">Select Site</option>
              @foreach($sites as $site)
                <option value="{{ $site->id }}">{{ $site->name }} ({{ $site->id }})</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Category <span class="text-danger">*</span></label>
            <select class="form-control" id="addCategory" name="category" required>
              <option value="">Select Category</option>
              <option value="UN-SKILLED">UN-SKILLED</option>
              <option value="SEMI-SKILLED">SEMI-SKILLED</option>
              <option value="SKILLED">SKILLED</option>
              <option value="HIGH-SKILLED">HIGH-SKILLED</option>
            </select>
          </div>
          <div class="form-group">
            <label>Basic <span class="text-danger">*</span></label>
            <input type="number" step="0.01" class="form-control" id="addBasic" name="basic" required>
          </div>
          <div class="form-group">
            <label>DA <span class="text-danger">*</span></label>
            <input type="number" step="0.01" class="form-control" id="addDa" name="da" value="0" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add Rate</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Rate Modal -->
<div class="modal fade" id="editRateModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Rate</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="editRateForm">
        <div class="modal-body">
          <input type="hidden" id="editRateId" name="id">
          <div class="form-group">
            <label>Site <span class="text-danger">*</span></label>
            <select class="form-control" id="editSiteId" name="site_id" required>
              <option value="">Select Site</option>
              @foreach($sites as $site)
                <option value="{{ $site->id }}">{{ $site->name }} ({{ $site->id }})</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label>Category <span class="text-danger">*</span></label>
            <select class="form-control" id="editCategory" name="category" required>
              <option value="">Select Category</option>
              <option value="UN-SKILLED">UN-SKILLED</option>
              <option value="SEMI-SKILLED">SEMI-SKILLED</option>
              <option value="SKILLED">SKILLED</option>
              <option value="HIGH-SKILLED">HIGH-SKILLED</option>
            </select>
          </div>
          <div class="form-group">
            <label>Basic <span class="text-danger">*</span></label>
            <input type="number" step="0.01" class="form-control" id="editBasic" name="basic" required>
          </div>
          <div class="form-group">
            <label>DA <span class="text-danger">*</span></label>
            <input type="number" step="0.01" class="form-control" id="editDa" name="da" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Update Rate</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // DataTable initialization
    var table = $('#ratesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: window.appBaseUrl + '/settings/rate/list',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        },
        columns: [
            { data: 'site_name', name: 'site_name' },
            { data: 'category', name: 'category' },
            { data: 'basic', name: 'basic' },
            { data: 'da', name: 'da' },
            { 
                data: 'id', 
                name: 'action',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return '<button class="btn btn-primary btn-sm edit-rate-btn" data-id="' + data + '" style="margin-right: 5px;">Edit</button>' +
                           '<button class="btn btn-primary btn-sm process-rate-btn" data-id="' + data + '">Process</button>';
                }
            }
        ],
        order: [[0, 'asc']]
    });

    // Add Rate
    $('#addRateForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        
        $.ajax({
            url: window.appBaseUrl + '/settings/rate',
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message || 'Rate added successfully',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    $('#addRateModal').modal('hide');
                    $('#addRateForm')[0].reset();
                    table.ajax.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to add rate'
                    });
                }
            },
            error: function(xhr) {
                var message = 'Failed to add rate';
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

    // Edit Rate - Load data
    $(document).on('click', '.edit-rate-btn', function() {
        var id = $(this).data('id');
        
        $.ajax({
            url: window.appBaseUrl + '/settings/rate/' + id,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    var rate = response.data;
                    $('#editRateId').val(rate.id);
                    // Site and category are read-only (from employee data)
                    $('#editSiteId').val(rate.site_id).prop('disabled', true);
                    $('#editCategory').val(rate.category).prop('disabled', true);
                    $('#editBasic').val(rate.basic);
                    $('#editDa').val(rate.da);
                    $('#editRateModal').modal('show');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to load rate'
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load rate details'
                });
            }
        });
    });

    // Update Rate
    $('#editRateForm').on('submit', function(e) {
        e.preventDefault();
        var id = $('#editRateId').val();
        // Only send basic and da (site and category are read-only)
        var formData = {
            basic: $('#editBasic').val(),
            da: $('#editDa').val()
        };
        
        $.ajax({
            url: window.appBaseUrl + '/settings/rate/' + id,
            type: 'PUT',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message || 'Rate updated successfully',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    $('#editRateModal').modal('hide');
                    // Re-enable fields for next edit
                    $('#editSiteId').prop('disabled', false);
                    $('#editCategory').prop('disabled', false);
                    table.ajax.reload();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to update rate'
                    });
                }
            },
            error: function(xhr) {
                var message = 'Failed to update rate';
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

    // Process Rate
    $(document).on('click', '.process-rate-btn', function() {
        var id = $(this).data('id');
        
        Swal.fire({
            title: 'Process Rate?',
            text: 'Are you sure you want to process this rate?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Process it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: window.appBaseUrl + '/settings/rate/' + id + '/process',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message || 'Rate processed successfully',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Failed to process rate'
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to process rate'
                        });
                    }
                });
            }
        });
    });
});
</script>
@endpush

