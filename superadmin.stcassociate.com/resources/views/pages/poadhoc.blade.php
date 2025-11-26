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
  

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">PO Adhoc</h1>
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
          <div class="col-lg-12 col-12">
            <div class="card">
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Product ID</th>
                        <th class="text-center">Item Description</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Purchase Rate</th>
                        <th class="text-center">Rate</th>
                        <th class="text-center">Unit</th>
                        <th class="text-center">Rack ID</th>
                        <th class="text-center">Condition</th>
                        <th class="text-center">Source</th>
                        <th class="text-center">Destination</th>
                        <th class="text-center">Received By</th>
                        <th class="text-center">Cherry Pick By</th>
                        <th class="text-center">Qty To Decrease</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Remarks</th>
                        <th class="text-center">Created By</th>
                        <th class="text-center">Created Date</th>
                        <th class="text-center">Updated By</th>
                        <th class="text-center">Updated Date</th>
                        <th class="text-center">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Product ID</th>
                        <th class="text-center">Item Description</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-center">Purchase Rate</th>
                        <th class="text-center">Rate</th>
                        <th class="text-center">Unit</th>
                        <th class="text-center">Rack ID</th>
                        <th class="text-center">Condition</th>
                        <th class="text-center">Source</th>
                        <th class="text-center">Destination</th>
                        <th class="text-center">Received By</th>
                        <th class="text-center">Cherry Pick By</th>
                        <th class="text-center">Qty To Decrease</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Remarks</th>
                        <th class="text-center">Created By</th>
                        <th class="text-center">Created Date</th>
                        <th class="text-center">Updated By</th>
                        <th class="text-center">Updated Date</th>
                        <th class="text-center">Action</th>
                      </tr>
                    </tfoot>
                  </table>
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

        // Initialize DataTable
        let table = $('#example1').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            lengthChange: false,
            autoWidth: true,
            ajax: {
                url: "{{ url('/branch/stc/poadhoc/list') }}",
                type: 'POST',
                data: function(d) {
                    d._token = "{{ csrf_token() }}";
                }
            },
            columns: [
                { data: 'stc_purchase_product_adhoc_id' },
                { data: 'stc_purchase_product_adhoc_productid' },
                { data: 'stc_purchase_product_adhoc_itemdesc' },
                { data: 'stc_purchase_product_adhoc_qty', render: function(data){ return parseFloat(data).toFixed(2); } },
                { data: 'stc_purchase_product_adhoc_prate', render: function(data){ return parseFloat(data).toFixed(2); } },
                { data: 'stc_purchase_product_adhoc_rate', render: function(data){ return parseFloat(data).toFixed(2); } },
                { data: 'stc_purchase_product_adhoc_unit' },
                { data: 'stc_purchase_product_adhoc_rackid' },
                { data: 'stc_purchase_product_adhoc_condition' },
                { data: 'stc_purchase_product_adhoc_source' },
                { data: 'stc_purchase_product_adhoc_destination' },
                { data: 'stc_purchase_product_adhoc_recievedby' },
                { data: 'stc_purchase_product_adhoc_cherrypickby' },
                { data: 'stc_purchase_product_adhoc_qtytodecrease' },
                { data: 'stc_purchase_product_adhoc_status_badge', orderable: false, searchable: false },
                { data: 'stc_purchase_product_adhoc_remarks' },
                { data: 'stc_purchase_product_adhoc_created_by' },
                { data: 'stc_purchase_product_adhoc_created_date' },
                { data: 'stc_purchase_product_adhoc_updated_by' },
                { data: 'stc_purchase_product_adhoc_updated_date' },
                { data: 'actionData' }
            ],
            order: [[0, 'desc']],
            columnDefs: [
                { "targets": [0, 6, 7, 13], "className": "text-center" },
                { "targets": [3, 4, 5], "className": "text-right" },
                { "targets": [1, 2, 8, 9, 10, 11, 12, 14, 15, 16, 17, 18, 19], "className": "text-left" },
                { "targets": [20], "orderable": false }
            ],
            dom: 'Bfrtip',
            buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
        });

        // Add buttons to container
        table.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        // Display for edit modal
        $('body').delegate('.edit-modal-btn','click', function(){
            var edit_id = $(this).attr('id');
            $.ajax({
                type: 'GET',
                url: "{{ url('/branch/stc/poadhoc/get') }}",
                data: {
                    id: edit_id
                },
                success: function(response) {
                    if(response.success && response.data) {
                        var record = response.data;
                        // Helper function to convert null/empty to 0 for integer fields
                        function toInt(value) {
                            return value === null || value === '' || value === undefined ? 0 : parseInt(value) || 0;
                        }
                        // Helper function to convert null/empty to 0 for float fields
                        function toFloat(value) {
                            return value === null || value === '' || value === undefined ? 0 : parseFloat(value) || 0;
                        }
                        $('#edit_id').val(record.stc_purchase_product_adhoc_id);
                        $('#edit-productid').val(toInt(record.stc_purchase_product_adhoc_productid));
                        $('#edit-itemdesc').val(record.stc_purchase_product_adhoc_itemdesc || '');
                        $('#edit-qty').val(toFloat(record.stc_purchase_product_adhoc_qty));
                        $('#edit-prate').val(toFloat(record.stc_purchase_product_adhoc_prate));
                        $('#edit-rate').val(toFloat(record.stc_purchase_product_adhoc_rate));
                        $('#edit-unit').val(record.stc_purchase_product_adhoc_unit || '');
                        $('#edit-rackid').val(toInt(record.stc_purchase_product_adhoc_rackid));
                        $('#edit-condition').val(record.stc_purchase_product_adhoc_condition || '');
                        $('#edit-source').val(record.stc_purchase_product_adhoc_source || '');
                        $('#edit-destination').val(record.stc_purchase_product_adhoc_destination || '');
                        $('#edit-recievedby').val(record.stc_purchase_product_adhoc_recievedby || '');
                        $('#edit-cherrypickby').val(toInt(record.stc_purchase_product_adhoc_cherrypickby));
                        $('#edit-qtytodecrease').val(toFloat(record.stc_purchase_product_adhoc_qtytodecrease));
                        $('#edit-status').val(record.stc_purchase_product_adhoc_status || '');
                        $('#edit-remarks').val(record.stc_purchase_product_adhoc_remarks || '');
                    } else {
                        swalSuccess('error', response.message || 'Failed to load record');
                    }
                },
                error: function() {
                    swalSuccess('error', 'Failed to load record');
                }
            });
        });

        // Save edited data from modal
        $('body').delegate('.edit-poadhoc-btn','click', function(){
            var id = $('#edit_id').val();
            // Helper function to convert empty string to 0 for integer fields
            function toInt(value) {
                return value === '' || value === null ? 0 : parseInt(value) || 0;
            }
            // Helper function to convert empty string to 0 for float fields
            function toFloat(value) {
                return value === '' || value === null ? 0 : parseFloat(value) || 0;
            }
            $.ajax({
                type: 'POST',
                data: {
                    id: id,
                    productid: toInt($('#edit-productid').val()),
                    itemdesc: $('#edit-itemdesc').val() || '',
                    qty: toFloat($('#edit-qty').val()),
                    prate: toFloat($('#edit-prate').val()),
                    rate: toFloat($('#edit-rate').val()),
                    unit: $('#edit-unit').val() || '',
                    rackid: toInt($('#edit-rackid').val()),
                    condition: $('#edit-condition').val() || '',
                    source: $('#edit-source').val() || '',
                    destination: $('#edit-destination').val() || '',
                    recievedby: $('#edit-recievedby').val() || '',
                    cherrypickby: toInt($('#edit-cherrypickby').val()),
                    qtytodecrease: toFloat($('#edit-qtytodecrease').val()),
                    status: $('#edit-status').val() || '',
                    remarks: $('#edit-remarks').val() || '',
                    _token: "{{ csrf_token() }}"
                },
                url: "{{ url('/branch/stc/poadhoc/edit') }}",
                success: function(response) {
                    if(response.success == true){
                        swalSuccess('success', response.message);
                        $('#edit-modal').modal('hide');
                        table.ajax.reload(null, false); // Reload DataTable
                    } else {
                        swalSuccess('error', response.message);
                    }
                }
            });
        });

        // Delete function
        $('.delete-btn').on('click', function(e){
            e.preventDefault();
            var id = $('#delete_id').val();
            $.ajax({
                type: 'GET',
                data: { id: id },
                url: "{{ url('/branch/stc/poadhoc/delete') }}",
                success: function(response) {
                    if(response.success) {
                        swalSuccess('success', response.message);
                        $('#delete-modal').modal('hide');
                        table.ajax.reload(null, false); // Reload DataTable
                    } else {
                        swalSuccess('error', response.message);
                    }
                }
            });
        });

        // suggestion list handlers
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
        <h4 class="modal-title">Delete PO Adhoc</h4>
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
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- edit modal -->
<div class="modal fade" id="edit-modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit PO Adhoc</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit-productid">Product ID</label>
                <input type="number" class="form-control" id="edit-productid" name="edit-productid" placeholder="Enter Product ID" value="0">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit-itemdesc">Item Description</label>
                <input type="text" class="form-control" id="edit-itemdesc" name="edit-itemdesc" placeholder="Enter Item Description">
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
              <div class="form-group">
                <label for="edit-prate">Purchase Rate</label>
                <input type="number" class="form-control" id="edit-prate" name="edit-prate" placeholder="Enter Purchase Rate" step="0.01" value="0">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit-rate">Rate</label>
                <input type="number" class="form-control" id="edit-rate" name="edit-rate" placeholder="Enter Rate" step="0.01" value="0">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit-unit">Unit</label>
                <input type="text" class="form-control" id="edit-unit" name="edit-unit" placeholder="Enter Unit">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit-rackid">Rack ID</label>
                <input type="number" class="form-control" id="edit-rackid" name="edit-rackid" placeholder="Enter Rack ID" value="0">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit-condition">Condition</label>
                <input type="text" class="form-control" id="edit-condition" name="edit-condition" placeholder="Enter Condition">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group suggestion-wrapper">
                <label for="edit-source">Source</label>
                <input type="text" class="form-control suggestion-input" id="edit-source" name="edit-source" placeholder="Type to search or add">
                <ul class="suggestion-list" data-input="edit-source">
                  @foreach($sources as $source)
                    <li data-value="{{ $source }}">{{ $source }}</li>
                  @endforeach
                </ul>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group suggestion-wrapper">
                <label for="edit-destination">Destination</label>
                <input type="text" class="form-control suggestion-input" id="edit-destination" name="edit-destination" placeholder="Type to search or add">
                <ul class="suggestion-list" data-input="edit-destination">
                  @foreach($destinations as $destination)
                    <li data-value="{{ $destination }}">{{ $destination }}</li>
                  @endforeach
                </ul>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group suggestion-wrapper">
                <label for="edit-recievedby">Received By</label>
                <input type="text" class="form-control suggestion-input" id="edit-recievedby" name="edit-recievedby" placeholder="Type to search or add">
                <ul class="suggestion-list" data-input="edit-recievedby">
                  @foreach($receivedByList as $received)
                    <li data-value="{{ $received }}">{{ $received }}</li>
                  @endforeach
                </ul>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit-cherrypickby">Cherry Pick By</label>
                <input type="number" class="form-control" id="edit-cherrypickby" name="edit-cherrypickby" placeholder="Enter Cherry Pick By" value="0">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit-qtytodecrease">Qty To Decrease</label>
                <input type="number" class="form-control" id="edit-qtytodecrease" name="edit-qtytodecrease" placeholder="Enter Qty To Decrease" step="0.01" value="0">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="edit-status">Status</label>
                @php
                  $statusOptions = [
                    1 => 'Stock',
                    2 => 'Dispatched',
                    3 => 'Pending',
                    4 => 'Approved',
                    5 => 'Rejected'
                  ];
                @endphp
                <select class="form-control" id="edit-status" name="edit-status">
                  <option value="">Select Status</option>
                  @foreach($statusOptions as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                  @endforeach
                </select>
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
        <!-- /.card-body -->
        <input type="hidden" id="edit_id">
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default close-btn" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger edit-poadhoc-btn">Save</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

