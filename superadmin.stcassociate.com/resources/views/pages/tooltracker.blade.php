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
        top: 0;
    }
    #view-track-modal .modal-body {
        max-height: 70vh;
        overflow-y: auto;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  @include('layouts.nav')
  @include('layouts.aside')

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Tool Tracker</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="#">Branch</a></li>
              <li class="breadcrumb-item active"><a href="#">{{!empty($page_title) ? $page_title : ''}}</a></li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <p>@include('layouts._message')</p>
            </div>
        </div>
        <div class="row">
          <div class="col-lg-12 col-12">
            <div class="card">
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Unique ID</th>
                        <th class="text-center">Item Description</th>
                        <th class="text-center">Machine S/No</th>
                        <th class="text-center">Make</th>
                        <th class="text-center">Tool Type</th>
                        <th class="text-center">Remarks</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Issued By</th>
                        <th class="text-center">Issued Date</th>
                        <th class="text-center">Created Date</th>
                        <th class="text-center">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Unique ID</th>
                        <th class="text-center">Item Description</th>
                        <th class="text-center">Machine S/No</th>
                        <th class="text-center">Make</th>
                        <th class="text-center">Tool Type</th>
                        <th class="text-center">Remarks</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Issued By</th>
                        <th class="text-center">Issued Date</th>
                        <th class="text-center">Created Date</th>
                        <th class="text-center">Action</th>
                      </tr>
                    </tfoot>
                  </table>
                </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  @include('layouts.footer')
</div>
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

        let table = $('#example1').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            lengthChange: false,
            autoWidth: true,
            ajax: {
                url: "{{ url('/branch/stc/tooltracker/list') }}",
                type: 'POST',
                data: function(d) {
                    d._token = "{{ csrf_token() }}";
                }
            },
            columns: [
                { data: 'id' },
                { data: 'unique_id' },
                { data: 'itemdescription' },
                { data: 'machinesrno' },
                { data: 'make' },
                { data: 'tooltype' },
                { data: 'remarks' },
                { data: 'status_badge', orderable: false, searchable: false },
                { data: 'issuedby' },
                { data: 'issueddate' },
                { data: 'created_date' },
                { data: 'actionData' }
            ],
            order: [[0, 'desc']],
            columnDefs: [
                { "targets": [0, 3, 4, 5, 7, 9, 10], "className": "text-center" },
                { "targets": [1, 2, 6, 8], "className": "text-left" },
                { "targets": [11], "orderable": false }
            ],
            dom: 'Bfrtip',
            buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
        });

        table.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        $('body').delegate('.edit-tool-btn','click', function(){
            var edit_id = $(this).data('id');
            $.ajax({
                type: 'GET',
                url: "{{ url('/branch/stc/tooltracker/get') }}",
                data: { id: edit_id },
                success: function(response) {
                    if(response.success && response.data) {
                        var record = response.data;
                        $('#edit_id').val(record.id);
                        $('#edit-unique_id').val(record.unique_id || '');
                        $('#edit-itemdescription').val(record.itemdescription || '');
                        $('#edit-machinesrno').val(record.machinesrno || '');
                        $('#edit-make').val(record.make || '');
                        $('#edit-tooltype').val(record.tooltype || '');
                        $('#edit-remarks').val(record.remarks || '');
                        $('#edit-tool-modal').modal('show');
                    } else {
                        swalSuccess('error', response.message || 'Failed to load record');
                    }
                },
                error: function() {
                    swalSuccess('error', 'Failed to load record');
                }
            });
        });

        $('body').delegate('.view-track-btn','click', function(){
            var toolsdetails_id = $(this).data('id');
            var unique_id = $(this).data('unique-id');
            $('#view-track-modal .modal-title').text('Track History - ' + (unique_id || 'Tool #' + toolsdetails_id));
            $('#view-track-toolsdetails-id').val(toolsdetails_id);
            $.ajax({
                type: 'GET',
                url: "{{ url('/branch/stc/tooltracker/list-track') }}",
                data: { toolsdetails_id: toolsdetails_id },
                success: function(response) {
                    if(response.success && response.data) {
                        var html = '';
                        if(response.data.length === 0) {
                            html = '<tr><td colspan="10" class="text-center">No track records found.</td></tr>';
                        } else {
                            response.data.forEach(function(row) {
                                html += '<tr>' +
                                    '<td class="text-center"><input type="checkbox" class="view-track-row-check" value="' + row.id + '"></td>' +
                                    '<td class="text-center">' + row.id + '</td>' +
                                    '<td>' + row.issuedby + '</td>' +
                                    '<td>' + row.user_id + '</td>' +
                                    '<td class="text-center">' + row.status + '</td>' +
                                    '<td>' + row.location + '</td>' +
                                    '<td class="text-center">' + row.issueddate + '</td>' +
                                    '<td>' + row.receivedby + '</td>' +
                                    '<td class="text-center">' + row.created_date + '</td>' +
                                    '<td class="text-center">' +
                                        '<a href="javascript:void(0)" class="btn btn-primary btn-xs edit-track-row-btn" data-track-id="' + row.id + '" title="Edit"><i class="fas fa-edit"></i></a> ' +
                                        '<a href="javascript:void(0)" class="btn btn-danger btn-xs delete-track-row-btn" data-track-id="' + row.id + '" title="Delete"><i class="fas fa-trash"></i></a>' +
                                    '</td></tr>';
                            });
                        }
                        $('#view-track-tbody').html(html);
                        $('#view-track-select-all').prop('checked', false);
                        $('.delete-selected-track-btn').toggle(response.data.length > 0);
                        $('#view-track-modal').modal('show');
                    } else {
                        swalSuccess('error', response.message || 'Failed to load track records');
                    }
                },
                error: function() {
                    swalSuccess('error', 'Failed to load track records');
                }
            });
        });

        $('body').delegate('.edit-track-row-btn','click', function(){
            var track_id = $(this).data('track-id');
            $.ajax({
                type: 'GET',
                url: "{{ url('/branch/stc/tooltracker/get-track') }}",
                data: { id: track_id },
                success: function(response) {
                    if(response.success && response.data) {
                        var record = response.data;
                        $('#edit_track_id').val(record.id);
                        $('#edit-track-issuedby').val(record.issuedby || '');
                        $('#edit-track-user_id').val(record.user_id || '');
                        $('#edit-track-status').val(record.status);
                        $('#edit-track-location').val(record.location || '');
                        $('#edit-track-issueddate').val(record.issueddate ? record.issueddate.split(' ')[0] : '');
                        $('#edit-track-receivedby').val(record.receivedby || '');
                        $('#view-track-modal').modal('hide');
                        $('#edit-track-modal').modal('show');
                    } else {
                        swalSuccess('error', response.message || 'Failed to load record');
                    }
                },
                error: function() {
                    swalSuccess('error', 'Failed to load record');
                }
            });
        });

        $('body').delegate('.delete-track-row-btn','click', function(){
            var track_id = $(this).data('track-id');
            var toolsdetails_id = $('#view-track-toolsdetails-id').val();
            $('#delete_track_id').val(track_id);
            $('#delete_track_toolsdetails_id').val(toolsdetails_id);
            $('#view-track-modal').modal('hide');
            $('#delete-track-modal').modal('show');
        });

        $('body').delegate('.edit-tooltracker-btn','click', function(){
            var id = $('#edit_id').val();
            $.ajax({
                type: 'POST',
                data: {
                    id: id,
                    unique_id: $('#edit-unique_id').val() || '',
                    itemdescription: $('#edit-itemdescription').val() || '',
                    machinesrno: $('#edit-machinesrno').val() || '',
                    make: $('#edit-make').val() || '',
                    tooltype: $('#edit-tooltype').val() || '',
                    remarks: $('#edit-remarks').val() || '',
                    _token: "{{ csrf_token() }}"
                },
                url: "{{ url('/branch/stc/tooltracker/edit') }}",
                success: function(response) {
                    if(response.success == true){
                        swalSuccess('success', response.message);
                        $('#edit-tool-modal').modal('hide');
                        table.ajax.reload(null, false);
                    } else {
                        swalSuccess('error', response.message);
                    }
                }
            });
        });

        $('body').delegate('.edit-track-save-btn','click', function(){
            var id = $('#edit_track_id').val();
            $.ajax({
                type: 'POST',
                data: {
                    id: id,
                    issuedby: $('#edit-track-issuedby').val() || '',
                    user_id: $('#edit-track-user_id').val() || '',
                    status: $('#edit-track-status').val() || 0,
                    location: $('#edit-track-location').val() || '',
                    issueddate: $('#edit-track-issueddate').val() || null,
                    receivedby: $('#edit-track-receivedby').val() || '',
                    _token: "{{ csrf_token() }}"
                },
                url: "{{ url('/branch/stc/tooltracker/edit-track') }}",
                success: function(response) {
                    if(response.success == true){
                        swalSuccess('success', response.message);
                        $('#edit-track-modal').modal('hide');
                        table.ajax.reload(null, false);
                        var toolsdetails_id = $('#view-track-toolsdetails-id').val();
                        if(toolsdetails_id) { $('.view-track-btn[data-id="' + toolsdetails_id + '"]').trigger('click'); }
                    } else {
                        swalSuccess('error', response.message);
                    }
                }
            });
        });

        $('body').delegate('.delete-tool-btn','click', function(){
            var id = $(this).data('id');
            $('#delete_tool_id').val(id);
            $('#delete-tool-modal').modal('show');
        });

        $('.delete-tool-btn-confirm').on('click', function(e){
            e.preventDefault();
            var id = $('#delete_tool_id').val();
            $.ajax({
                type: 'GET',
                data: { id: id },
                url: "{{ url('/branch/stc/tooltracker/delete') }}",
                success: function(response) {
                    if(response.success) {
                        swalSuccess('success', response.message);
                        $('#delete-tool-modal').modal('hide');
                        table.ajax.reload(null, false);
                    } else {
                        swalSuccess('error', response.message);
                    }
                }
            });
        });

        $('.delete-track-btn-confirm').on('click', function(e){
            e.preventDefault();
            var id = $('#delete_track_id').val();
            var toolsdetails_id = $('#delete_track_toolsdetails_id').val();
            $.ajax({
                type: 'GET',
                data: { id: id },
                url: "{{ url('/branch/stc/tooltracker/delete-track') }}",
                success: function(response) {
                    if(response.success) {
                        swalSuccess('success', response.message);
                        $('#delete-track-modal').modal('hide');
                        table.ajax.reload(function() {
                            if(toolsdetails_id) $('.view-track-btn[data-id="' + toolsdetails_id + '"]').trigger('click');
                        }, false);
                    } else {
                        swalSuccess('error', response.message);
                    }
                }
            });
        });

        $('#view-track-select-all').on('change', function() {
            $('.view-track-row-check').prop('checked', $(this).prop('checked'));
        });
        $('body').delegate('.view-track-row-check', 'change', function() {
            var total = $('.view-track-row-check').length;
            var checked = $('.view-track-row-check:checked').length;
            $('#view-track-select-all').prop('checked', total > 0 && total === checked);
        });

        $('body').delegate('.delete-selected-track-btn', 'click', function(){
            var ids = [];
            $('.view-track-row-check:checked').each(function() { ids.push($(this).val()); });
            if(ids.length === 0) {
                swalSuccess('warning', 'Please select at least one record to delete.');
                return;
            }
            var toolsdetails_id = $('#view-track-toolsdetails-id').val();
            Swal.fire({
                title: 'Delete Selected?',
                text: 'Are you sure you want to delete ' + ids.length + ' selected track record(s)?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete them'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: "{{ url('/branch/stc/tooltracker/delete-track-bulk') }}",
                        data: { ids: ids, _token: "{{ csrf_token() }}" },
                        success: function(response) {
                            if(response.success) {
                                swalSuccess('success', response.message);
                                $('#view-track-modal').modal('hide');
                                table.ajax.reload(null, false);
                                if(toolsdetails_id) $('.view-track-btn[data-id="' + toolsdetails_id + '"]').trigger('click');
                            } else {
                                swalSuccess('error', response.message);
                            }
                        },
                        error: function() {
                            swalSuccess('error', 'Failed to delete records.');
                        }
                    });
                }
            });
        });
    });
</script>
</body>
</html>

<!-- view track history modal -->
<div class="modal fade" id="view-track-modal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Track History (stc_tooldetails_track)</h4>
        <div class="d-flex align-items-center">
          <button type="button" class="btn btn-danger btn-sm mr-2 delete-selected-track-btn" style="display:none;">
            <i class="fas fa-trash"></i> Delete Selected
          </button>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      </div>
      <div class="modal-body">
        <input type="hidden" id="view-track-toolsdetails-id">
        <div class="table-responsive">
          <table class="table table-bordered table-sm">
            <thead>
              <tr>
                <th class="text-center" style="width:40px;">
                  <input type="checkbox" id="view-track-select-all" title="Select all">
                </th>
                <th class="text-center">ID</th>
                <th>Issued By</th>
                <th>User ID</th>
                <th class="text-center">Status</th>
                <th>Location</th>
                <th class="text-center">Issued Date</th>
                <th>Received By</th>
                <th class="text-center">Created Date</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            <tbody id="view-track-tbody">
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- delete tool modal -->
<div class="modal fade" id="delete-tool-modal">
  <div class="modal-dialog">
    <div class="modal-content bg-danger">
      <div class="modal-header">
        <h4 class="modal-title">Delete Tool (stc_tooldetails)</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this tool?</p>
        <p class="text-warning"><small>This will also delete all associated tracking records from stc_tooldetails_track. This action cannot be undone.</small></p>
        <input type="hidden" id="delete_tool_id">
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default close-btn" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger delete-tool-btn-confirm">Delete Tool</button>
      </div>
    </div>
  </div>
</div>

<!-- delete track modal -->
<div class="modal fade" id="delete-track-modal">
  <div class="modal-dialog">
    <div class="modal-content bg-warning">
      <div class="modal-header">
        <h4 class="modal-title">Delete Track Record (stc_tooldetails_track)</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this tracking record?</p>
        <p class="text-warning"><small>The tool will remain, only this tracking history entry will be removed.</small></p>
        <input type="hidden" id="delete_track_id">
        <input type="hidden" id="delete_track_toolsdetails_id">
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default close-btn" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-warning delete-track-btn-confirm">Delete Track</button>
      </div>
    </div>
  </div>
</div>

<!-- edit tool modal (stc_tooldetails) -->
<div class="modal fade" id="edit-tool-modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Tool (stc_tooldetails)</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit-unique_id">Unique ID</label>
                <input type="text" class="form-control" id="edit-unique_id" name="edit-unique_id" placeholder="Enter Unique ID">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit-itemdescription">Item Description</label>
                <input type="text" class="form-control" id="edit-itemdescription" name="edit-itemdescription" placeholder="Enter Item Description">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit-machinesrno">Machine S/No</label>
                <input type="text" class="form-control" id="edit-machinesrno" name="edit-machinesrno" placeholder="Enter Machine S/No">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit-make">Make</label>
                <input type="text" class="form-control" id="edit-make" name="edit-make" placeholder="Enter Make">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit-tooltype">Tool Type</label>
                <input type="text" class="form-control" id="edit-tooltype" name="edit-tooltype" placeholder="Enter Tool Type">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit-remarks">Remarks</label>
                <input type="text" class="form-control" id="edit-remarks" name="edit-remarks" placeholder="Enter Remarks">
              </div>
            </div>
          </div>
        </div>
        <input type="hidden" id="edit_id">
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default close-btn" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger edit-tooltracker-btn">Save</button>
      </div>
    </div>
  </div>
</div>

<!-- edit track modal (stc_tooldetails_track) -->
<div class="modal fade" id="edit-track-modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Track Record (stc_tooldetails_track)</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit-track-issuedby">Issued By</label>
                <input type="text" class="form-control" id="edit-track-issuedby" name="edit-track-issuedby" placeholder="Enter Issued By">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit-track-user_id">User ID</label>
                <input type="text" class="form-control" id="edit-track-user_id" name="edit-track-user_id" placeholder="Enter User ID">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit-track-status">Status</label>
                <select class="form-control" id="edit-track-status" name="edit-track-status">
                  <option value="0">Issued</option>
                  <option value="1">Accepted</option>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit-track-location">Location</label>
                <input type="text" class="form-control" id="edit-track-location" name="edit-track-location" placeholder="Enter Location">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit-track-issueddate">Issued Date</label>
                <input type="date" class="form-control" id="edit-track-issueddate" name="edit-track-issueddate" placeholder="Issued Date">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit-track-receivedby">Received By</label>
                <input type="text" class="form-control" id="edit-track-receivedby" name="edit-track-receivedby" placeholder="Enter Received By">
              </div>
            </div>
          </div>
        </div>
        <input type="hidden" id="edit_track_id">
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default close-btn" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-info edit-track-save-btn">Save</button>
      </div>
    </div>
  </div>
</div>
