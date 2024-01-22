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
            <h1 class="m-0">Site User</h1>
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
                <div class="card-body"  style="overflow-x: auto;">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th class="text-center">Id</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Project</th>
                        <th class="text-center">Location</th>
                        <th class="text-center">Deparatment</th>
                        <th class="text-center">Type Of Job</th>
                        <th class="text-center">Created By</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th class="text-center">Id</th>
                        <th class="text-center">Date</th>
                        <th class="text-center">Project</th>
                        <th class="text-center">Location</th>
                        <th class="text-center">Deparatment</th>
                        <th class="text-center">Type Of Job</th>
                        <th class="text-center">Created By</th>
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
      getstatusdownlist(dataTableAct);      
      
      // get Rack function
      function getstatusdownlist(dataTableAct){
          // DataTable
          $('#example1').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ url('/branch/sitebranch/list') }}",
          columns: [
                { data: 'stc_status_down_list_id' },
                { data: 'stc_status_down_list_date' },
                { data: 'stc_cust_project_title' },
                { data: 'stc_status_down_list_plocation' },
                { data: 'stc_status_down_list_sub_location' },
                { data: 'stc_status_down_list_jobtype' },
                { data: 'stc_cust_pro_supervisor_fullname' },
                { data: 'stc_status_down_list_status' },
                { data: 'actionData' }
          ],
          columnDefs: [
            { "targets": 0, "className": "text-center", width : '4%'},
            // { "targets": 1, "className": "text-left", width : '25%' },
            { "targets": [1,3,4,5,6,7], "className": "text-center", },
            { orderable: false, targets: 8 },
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
                getstatusdownlist(dataTableAct);
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
      $('.delete-sdl-btn').on('click', function(e){
        e.preventDefault();
        var id = $('#delete_id').val();
        $.ajax({
            type: 'get',
            data: {
                id: id,
            },
            url: "{{ url('/branch/sitebranch/delete') }}",
            success: function(response) {
              if(response.success==true){

                swalSuccess('success', 'Record deleted.');
                if ( $.fn.DataTable.isDataTable('#example1') ) {
                  $('#example1').DataTable().destroy();
                }
                dataTableAct="active";
                getstatusdownlist(dataTableAct);
                $('.close-btn').click();
              }else{
                swalSuccess('error', response.message);
              }
            }
        });
      });

    //   onclick project & supervisor connected show
    
      $('body').delegate('.show-project-btn','click', function(){
        var id = $(this).attr('id');
        var project=$(this).html();
        $.ajax({
            type: 'get',
            data: {
                id: id,
                _token: "{{ csrf_token() }}"
            },
            url: "{{ url('/branch/sitebranch/getusers') }}",
            success: function(response) {
                // console.log(response);
                $('.siteusers-connected-rec').html(response.siteusers);
                $('.show-project-name').html(project);
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
        <h4 class="modal-title">Delete Status Down List</h4>
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
        <button type="button" class="btn btn-danger delete-sdl-btn">Delete</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- Show project modal -->
<div class="modal fade" id="show-project-modal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">View Project Users</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="card-body">
          <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="text-center">Project name : <span class='show-project-name'></span></h3>
                </div>
                <div class="col-md-12">
                    <table class="table table-bordered table-stripped">
                        <thead>
                            <tr>
                                <th class="text-center">User Id</th>
                                <th class="text-center">User name</th>
                                <th class="text-center">User Contact</th>
                                <th class="text-center">Category</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="siteusers-connected-rec">
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
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default close-btn" data-dismiss="modal">Close</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>