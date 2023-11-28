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
            <h1 class="m-0">Electronics</h1>
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
                <!-- /.card-header -->
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped table-responsive">
                    <thead>
                      <tr>
                        <th class="text-center">Id</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Category Name</th>
                        <th class="text-center">Rack</th>
                        <th class="text-center">Brand</th>
                        <th class="text-center">HSN Code</th>
                        <th class="text-center">GST</th>
                        <th class="text-center">Purchase Quantity</th>
                        <th class="text-center">Inventory</th>
                        <th class="text-center">Challan Quantity</th>
                        <th class="text-center">E.Inventory</th>
                        <th class="text-center">E.Challan Quantity</th>
                        <th class="text-center">E.Return Quantity</th>
                        <th class="text-center">Unit</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th class="text-center">Id</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Category Name</th>
                        <th class="text-center">Rack</th>
                        <th class="text-center">Brand</th>
                        <th class="text-center">HSN Code</th>
                        <th class="text-center">GST</th>
                        <th class="text-center">Purchase Quantity</th>
                        <th class="text-center">Inventory</th>
                        <th class="text-center">Challan Quantity</th>
                        <th class="text-center">E.Inventory</th>
                        <th class="text-center">E.Challan Quantity</th>
                        <th class="text-center">E.Return Quantity</th>
                        <th class="text-center">Unit</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Actions</th>
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
      getElectronicsProduct(dataTableAct);      
      
      // get Rack function
      function getElectronicsProduct(dataTableAct){
          // DataTable
          $('#example1').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ url('/branch/electronicsbranch/list') }}",
          columns: [
                { data: 'stc_product_id' },
                { data: 'stc_product_name' },
                { data: 'stc_cat_name' },
                { data: 'stc_rack_name' },
                { data: 'stc_brand_title' },
                { data: 'stc_product_hsncode' },
                { data: 'stc_product_gst' },
                { data: 'purchaseqty' },
                { data: 'inventory' },
                { data: 'challanqty' },
                { data: 'stc_electronics_inventory_item_qty' },
                { data: 'echallanqty' },
                { data: 'erschallanqty' },
                { data: 'stc_product_unit' },
                { data: 'stc_product_avail' },
                { data: 'actionData' }
          ],
          columnDefs: [
            { "targets": 0, "className": "text-center", width : '4%'},
            { "targets": 1, "className": "text-left", width : '25%' },
            { "targets": 2, "className": "text-left", },
            { "targets": 3, "className": "text-center", },
            { "targets": 4, "className": "text-center", },
            { "targets": 5, "className": "text-right", },
            { "targets": 6, "className": "text-right", },
            { "targets": 7, "className": "text-right", },
            { "targets": 8, "className": "text-right", },
            { "targets": 9, "className": "text-right", },
            { "targets": 10, "className": "text-right", },
            { orderable: false, targets: 7 },
            { orderable: false, targets: 8 },
            { orderable: false, targets: 9 },
            { orderable: false, targets: 11 },
            { orderable: false, targets: 12 },
            { orderable: false, targets: 15 },
          ]
        });
      }

      // save data from modal
      $('body').delegate('.add-Rack-btn','click', function(){
        var name = $('#add-name').val();
        var status = $('#add-status').val();
        $.ajax({
            type: 'post',
            data: {
                name: name,
                status: status,
                _token: "{{ csrf_token() }}"
            },
            url: "{{ url('/branch/product/create') }}",
            success: function(response) {
              if(response.success==true){
                if ( $.fn.DataTable.isDataTable('#example1') ) {
                  $('#example1').DataTable().destroy();
                }
                dataTableAct="inactive";
                getElectronicsProduct(dataTableAct);
                $('.close-btn').click();
                swalSuccess('success', 'Record saved!');
              }else if(response.success==false){
                swalSuccess('error', response.message);
              }else{
                swalSuccess('error', 'Duplicate record found.');
              }
            }
        });
      });

      // display for edit modal
      $('body').delegate('.edit-modal-btn','click', function(){
        var edit_id = $(this).attr('id');
        var qty = $('#display-quantity'+edit_id).html();
        $('#edit_id').val(edit_id);
        $('#edit-qty').val(qty);
      });

      // save edited data from modal
      $('body').delegate('.edit-product-btn','click', function(){
        var id = $('#edit_id').val();
        var qty = $('#edit-qty').val();
        $.ajax({
            type: 'post',
            data: {
                id: id,
                qty: qty,
                _token: "{{ csrf_token() }}"
            },
            url: "{{ url('/branch/electronicsbranch/edit') }}",
            success: function(response) {
              // console.log(response);
              if(response.success==true){
                $('.close-btn').click();
                swalSuccess('success', 'Record updated.');
              }else{
                swalSuccess('error', response.message);
              }
            }
        });
      });

      // delete function
      $('.delete-product-btn').on('click', function(e){
        e.preventDefault();
        var id = $('#delete_id').val();
        $.ajax({
            type: 'get',
            data: {
                id: id,
            },
            url: "{{ url('/branch/electronicsbranch/delete') }}",
            success: function(response) {
              if(response.success==true){

                swalSuccess('success', 'Record deleted.');
                if ( $.fn.DataTable.isDataTable('#example1') ) {
                  $('#example1').DataTable().destroy();
                }
                dataTableAct="active";
                getElectronicsProduct(dataTableAct);
                $('.close-btn').click();
              }else{
                swalSuccess('error', response.message);
              }
            }
        });
      });

      // save edited data from modal
      $('body').delegate('.view-modal-btn','click', function(){
        var id = $(this).attr('id');
        $.ajax({
            type: 'post',
            data: {
                id: id,
                _token: "{{ csrf_token() }}"
            },
            url: "{{ url('/branch/electronicsbranch/getSP') }}",
            success: function(response) {
              // console.log(response);
              $('.elec-purchase-rec').html(response.purchaserec);
              $('.elec-sale-rec').html(response.salerec);
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
        <button type="button" class="btn btn-danger delete-product-btn">Delete</button>
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
        <h4 class="modal-title">Edit Inventory</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="card-body">
          <div class="form-group">
            <label for="name">Quantity</label>
            <input type="number" class="form-control" id="edit-qty" name="edit-qty" value="" placeholder="Enter qty">
          </div>
        </div>
        <!-- /.card-body -->
        <input type="hidden" id="edit_id">
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default close-btn" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger edit-product-btn">Save</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- add modal -->
<div class="modal fade" id="add-modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Rack</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="card-body">
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="add-name" name="add-name" value="" placeholder="Enter name">
          </div>
          <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" id="add-status" name="add-status">
                <option value="1">Active</option>
                <option value="0">In-active</option>
            </select>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default close-btn" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger add-Rack-btn">Save</button>
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