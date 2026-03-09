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
    .suggestion-wrapper{
        position: relative;
    }
    .suggestion-list{
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 1051;
        max-height: 200px;
        overflow-y: auto;
        background: #fff;
        border: 1px solid #ced4da;
        border-top: none;
        list-style: none;
        margin: 0;
        padding: 0;
        display: none;
    }
    .suggestion-list li{
        padding: 8px 12px;
        cursor: pointer;
    }
    .suggestion-list li:hover{
        background: #f4f6f9;
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
            <h1 class="m-0">PPE Tracker</h1>
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
                        <th class="text-center">User</th>
                        <th class="text-center">PPE Type</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Unit</th>
                        <th class="text-center">Issue Date</th>
                        <th class="text-center">Validity</th>
                        <th class="text-center">Remarks</th>
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
                        <th class="text-center">User</th>
                        <th class="text-center">PPE Type</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Unit</th>
                        <th class="text-center">Issue Date</th>
                        <th class="text-center">Validity</th>
                        <th class="text-center">Remarks</th>
                        <th class="text-center">Created By</th>
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
                url: "{{ url('/branch/stc/ppetracker/list') }}",
                type: 'POST',
                data: function(d) {
                    d._token = "{{ csrf_token() }}";
                }
            },
            columns: [
                { data: 'stc_item_tracker_id' },
                { data: 'user_name' },
                { data: 'stc_item_tracker_toppe' },
                { data: 'stc_item_tracker_qty', render: function(data){ return parseFloat(data).toFixed(2); } },
                { data: 'stc_item_tracker_unit' },
                { data: 'stc_item_tracker_issuedate' },
                { data: 'stc_item_tracker_validity' },
                { data: 'stc_item_tracker_remarks' },
                { data: 'stc_item_tracker_createdby' },
                { data: 'stc_item_tracker_created_date' },
                { data: 'actionData' }
            ],
            order: [[0, 'desc']],
            columnDefs: [
                { "targets": [0, 3, 4, 5, 6, 7], "className": "text-center" },
                { "targets": [1, 2, 8, 9, 10], "className": "text-left" },
                { "targets": [10], "orderable": false }
            ],
            dom: 'Bfrtip',
            buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
        });

        table.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        $('body').delegate('.edit-modal-btn','click', function(){
            var edit_id = $(this).attr('id');
            $.ajax({
                type: 'GET',
                url: "{{ url('/branch/stc/ppetracker/get') }}",
                data: { id: edit_id },
                success: function(response) {
                    if(response.success && response.data) {
                        var record = response.data;
                        function toInt(value) {
                            return value === null || value === '' || value === undefined ? 0 : parseInt(value) || 0;
                        }
                        function toFloat(value) {
                            return value === null || value === '' || value === undefined ? 0 : parseFloat(value) || 0;
                        }
                        $('#edit_id').val(record.stc_item_tracker_id);
                        $('#edit-user_name').val(record.stc_item_tracker_user_id || '');
                        $('#edit-toppe').val(record.stc_item_tracker_toppe || '');
                        $('#edit-qty').val(toFloat(record.stc_item_tracker_qty));
                        $('#edit-unit').val(record.stc_item_tracker_unit || '');
                        $('#edit-issuedate').val(record.stc_item_tracker_issuedate ? record.stc_item_tracker_issuedate.split(' ')[0] : '');
                        $('#edit-validity').val(toInt(record.stc_item_tracker_validity));
                        $('#edit-remarks').val(record.stc_item_tracker_remarks || '');
                    } else {
                        swalSuccess('error', response.message || 'Failed to load record');
                    }
                },
                error: function() {
                    swalSuccess('error', 'Failed to load record');
                }
            });
        });

        $('body').delegate('.edit-ppetracker-btn','click', function(){
            var id = $('#edit_id').val();
            function toInt(value) {
                return value === '' || value === null ? 0 : parseInt(value) || 0;
            }
            function toFloat(value) {
                return value === '' || value === null ? 0 : parseFloat(value) || 0;
            }
            $.ajax({
                type: 'POST',
                data: {
                    id: id,
                    user_name: $('#edit-user_name').val() || '',
                    toppe: $('#edit-toppe').val() || '',
                    qty: toFloat($('#edit-qty').val()),
                    unit: $('#edit-unit').val() || '',
                    issuedate: $('#edit-issuedate').val() || null,
                    validity: toInt($('#edit-validity').val()),
                    remarks: $('#edit-remarks').val() || '',
                    _token: "{{ csrf_token() }}"
                },
                url: "{{ url('/branch/stc/ppetracker/edit') }}",
                success: function(response) {
                    if(response.success == true){
                        swalSuccess('success', response.message);
                        $('#edit-modal').modal('hide');
                        table.ajax.reload(null, false);
                    } else {
                        swalSuccess('error', response.message);
                    }
                }
            });
        });

        $('.delete-btn').on('click', function(e){
            e.preventDefault();
            var id = $('#delete_id').val();
            $.ajax({
                type: 'GET',
                data: { id: id },
                url: "{{ url('/branch/stc/ppetracker/delete') }}",
                success: function(response) {
                    if(response.success) {
                        swalSuccess('success', response.message);
                        $('#delete-modal').modal('hide');
                        table.ajax.reload(null, false);
                    } else {
                        swalSuccess('error', response.message);
                    }
                }
            });
        });

        function bindSuggestion(wrapper){
            var input = wrapper.find('.suggestion-input');
            var list = wrapper.find('.suggestion-list');
            input.on('focus keyup', function(){
                var term = $(this).val().toLowerCase();
                var hasVisible = false;
                list.children('li').each(function(){
                    var text = $(this).text().toLowerCase();
                    if(term === '' || text.indexOf(term) !== -1){
                        $(this).show();
                        hasVisible = true;
                    }else{
                        $(this).hide();
                    }
                });
                list.toggle(hasVisible);
            });
            list.on('mousedown', 'li', function(e){
                e.preventDefault();
                input.val($(this).data('value'));
                list.hide();
            });
            $(document).on('click', function(e){
                if(!wrapper.is(e.target) && wrapper.has(e.target).length === 0){
                    list.hide();
                }
            });
        }
        $('.suggestion-wrapper').each(function(){
            bindSuggestion($(this));
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
        <h4 class="modal-title">Delete PPE Tracker</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this record?</p>
        <p class="text-warning"><small>This action cannot be undone.</small></p>
        <input type="hidden" id="delete_id">
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default close-btn" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger delete-btn">Delete</button>
      </div>
    </div>
  </div>
</div>

<!-- edit modal -->
<div class="modal fade" id="edit-modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit PPE Tracker</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group suggestion-wrapper">
                <label for="edit-user_name">User</label>
                <input type="text" class="form-control suggestion-input" id="edit-user_name" name="edit-user_name" placeholder="Type to search or add">
                <ul class="suggestion-list" data-input="edit-user_name">
                  @foreach($userNames as $name)
                    <li data-value="{{ $name }}">{{ $name }}</li>
                  @endforeach
                </ul>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group suggestion-wrapper">
                <label for="edit-toppe">PPE Type</label>
                <input type="text" class="form-control suggestion-input" id="edit-toppe" name="edit-toppe" placeholder="Type to search or add">
                <ul class="suggestion-list" data-input="edit-toppe">
                  @foreach($ppeTypes as $type)
                    <li data-value="{{ $type }}">{{ $type }}</li>
                  @endforeach
                </ul>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit-qty">Quantity</label>
                <input type="number" class="form-control" id="edit-qty" name="edit-qty" placeholder="Enter Quantity" step="0.01" value="0">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group suggestion-wrapper">
                <label for="edit-unit">Unit</label>
                <input type="text" class="form-control suggestion-input" id="edit-unit" name="edit-unit" placeholder="Type to search or add">
                <ul class="suggestion-list" data-input="edit-unit">
                  @foreach($units as $unit)
                    <li data-value="{{ $unit }}">{{ $unit }}</li>
                  @endforeach
                </ul>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit-issuedate">Issue Date</label>
                <input type="date" class="form-control" id="edit-issuedate" name="edit-issuedate" placeholder="Issue Date">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit-validity">Validity (months)</label>
                <input type="number" class="form-control" id="edit-validity" name="edit-validity" placeholder="Validity in months" value="0">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="edit-remarks">Remarks</label>
                <textarea class="form-control" id="edit-remarks" name="edit-remarks" rows="3" placeholder="Enter Remarks"></textarea>
              </div>
            </div>
          </div>
        </div>
        <input type="hidden" id="edit_id">
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default close-btn" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger edit-ppetracker-btn">Save</button>
      </div>
    </div>
  </div>
</div>
