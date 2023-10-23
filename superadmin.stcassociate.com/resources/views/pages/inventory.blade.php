<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>STC Associates | {{!empty($page_title) ? $page_title : ''}}</title>
  @include('layouts.head')
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
            <h1 class="m-0">Inventory</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="#">Master</a></li>
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
                        <th class="text-center">Id</th>
                        <th class="text-center">Product Name</th>
                        <th class="text-center">Category</th>
                        <th class="text-center">Inventory Qty</th>
                        <th class="text-center">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th class="text-center">Id</th>
                        <th class="text-center">Product Name</th>
                        <th class="text-center">Category</th>
                        <th class="text-center">Inventory Qty</th>
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
      getRack(dataTableAct);      
      
      // get Rack function
      function getRack(dataTableAct){
          // DataTable
          $('#example1').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ url('/master/inventory/list') }}",
            columns: [
                { data: 'stc_product_id' },
                { data: 'stc_product_name' },
                { data: 'stc_cat_name' },
                { data: 'stc_item_inventory_pd_qty' },
                { data: 'actionData' },
            ],
            columnDefs: [
              { "targets": 0, "className": "text-center", width : '4%'},
              { "targets": 1, "className": "text-left", width : '60%' },
              { "targets": 2, "className": "text-left", },
              { "targets": 3, "className": "text-right", },
              { "targets": 4, "className": "text-center", },
              { orderable: false, targets: 4 },
            ],
            responsive: true, 
            lengthChange: true, 
            autoWidth: false,
            buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
          }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      }

      // display for edit modal
      $('body').delegate('.edit-modal-btn','click', function(){
        var edit_id = $(this).attr('id');
        var quantity = $('#display-quantity'+edit_id).html();
        $('#edit_id').val(edit_id);
        $('#edit-quantity').val(quantity);
      });

      // save edited data from modal
      $('body').delegate('.edit-inventory-btn','click', function(){
        var id = $('#edit_id').val();
        var quantity = $('#edit-quantity').val();
        $.ajax({
            type: 'post',
            data: {
                id: id,
                quantity: quantity,
                _token: "{{ csrf_token() }}"
            },
            url: "{{ url('/master/inventory/edit') }}",
            success: function(response) {
              // console.log(response);
              if(response.success==true){
                if ( $.fn.DataTable.isDataTable('#example1') ) {
                  $('#example1').DataTable().destroy();
                }
                dataTableAct="active";
                getRack(dataTableAct);
                $('.close-btn').click();
                swalSuccess('success', 'Record updated.');
              }else{
                swalSuccess('error', response.message);
              }
            }
        });
      });

      // delete function
      $('.delete-inventory-btn').on('click', function(e){
        e.preventDefault();
        var id = $('#delete_id').val();
        $.ajax({
            type: 'get',
            data: {
                id: id,
            },
            url: "{{ url('/master/inventory/delete') }}",
            success: function(response) {
              if(response.success==true){

                swalSuccess('success', 'Record deleted.');
                if ( $.fn.DataTable.isDataTable('#example1') ) {
                  $('#example1').DataTable().destroy();
                }
                dataTableAct="active";
                getRack(dataTableAct);
                $('.close-btn').click();
              }else{
                swalSuccess('error', response.message);
              }
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
        <h4 class="modal-title">Delete Inventory</h4>
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
        <button type="button" class="btn btn-danger delete-inventory-btn">Delete</button>
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
            <input type="number" class="form-control" id="edit-quantity" name="edit-quantity" value="" placeholder="Enter quantity">
          </div>
        </div>
        <!-- /.card-body -->
        <input type="hidden" id="edit_id">
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default close-btn" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger edit-inventory-btn">Save</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>