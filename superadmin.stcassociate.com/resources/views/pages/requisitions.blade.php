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
            <h1 class="m-0">Requisitions, Items & Dispatched</h1>
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
        <div class="card card-primary card-outline">
          <div class="card-body">
            <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="requisitions-tab" data-toggle="pill" href="#requisitions" role="tab" aria-controls="requisitions" aria-selected="true">Requisitions</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="requisitions-items-tab" data-toggle="pill" href="#requisitions-items" role="tab" aria-controls="requisitions-items" aria-selected="false">Requisitions Items</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="requisitions-itemsdis-tab" data-toggle="pill" href="#requisitions-itemsdis" role="tab" aria-controls="requisitions-itemsdis" aria-selected="false">Dispatched</a>
              </li>
            </ul>
            <div class="tab-content" id="custom-content-below-tabContent">
              <div class="tab-pane fade active show" id="requisitions" role="tabpanel" aria-labelledby="requisitions-tab">
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
                                <th class="text-center">SDL ID</th>
                                <th class="text-center">Project Name</th>
                                <th class="text-center">Created By (Supervisor Name)</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Approved By</th>
                                <th class="text-center">Created Date</th>
                                <th class="text-center">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                              <tr>
                                <th class="text-center">Id</th>
                                <th class="text-center">SDL ID</th>
                                <th class="text-center">Project Name</th>
                                <th class="text-center">Created By (Supervisor Name)</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Approved By</th>
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
              <div class="tab-pane" id="requisitions-items" role="tabpanel" aria-labelledby="requisitions-items-tab">
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
                          <table id="req-items" class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th class="text-center">Id</th>
                                <th class="text-center">Req ID</th>
                                <th class="text-center">Item Desc</th>
                                <th class="text-center">Unit</th>
                                <th class="text-center">Req Quantity</th>
                                <th class="text-center">Approved Quantity</th>
                                <th class="text-center">Final Quanity</th>
                                <th class="text-center">Priority</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                              <tr>
                                <th class="text-center">Id</th>
                                <th class="text-center">Req ID</th>
                                <th class="text-center">Item Desc</th>
                                <th class="text-center">Unit</th>
                                <th class="text-center">Req Quantity</th>
                                <th class="text-center">Approved Quantity</th>
                                <th class="text-center">Final Quanity</th>
                                <th class="text-center">Priority</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                              </tr>
                            </tfoot>
                          </table>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane" id="requisitions-itemsdis" role="tabpanel" aria-labelledby="requisitions-itemsdis-tab">
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
                          <table id="req-itemsdis" class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th class="text-center">Id</th>
                                <th class="text-center">Req ID</th>
                                <th class="text-center">Req Item ID</th>
                                <th class="text-center">Product ID</th>
                                <th class="text-center">Purchase ID</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Dispatched Date</th>
                                <th class="text-center">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                              <tr>
                                <th class="text-center">Id</th>
                                <th class="text-center">Req ID</th>
                                <th class="text-center">Req Item ID</th>
                                <th class="text-center">Product ID</th>
                                <th class="text-center">Purchase ID</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Dispatched Date</th>
                                <th class="text-center">Action</th>
                              </tr>
                            </tfoot>
                          </table>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- /.card -->
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
        getRequisitions(dataTableAct); 
        getRequisitionsItems(dataTableAct); 
        getRequisitionsItemsDis(dataTableAct);      
      
        // get Rack function
        function getRequisitions() {
            // Initialize DataTable
            let table = $('#example1').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                lengthChange: true,
                autoWidth: true,
                ajax: "{{ url('/branch/stc/requisitions/list') }}",
                columns: [
                    { data: 'stc_cust_super_requisition_list_id' },
                    { data: 'stc_cust_super_requisition_list_sdlid' },
                    { data: 'stc_cust_project_title' },
                    { data: 'stc_cust_pro_supervisor_fullname' },
                    {
                        data: 'stc_cust_super_requisition_list_status',
                        render: function(data, type, row) {
                          var output='';
                          if(data=="1"){
                            output='Process';
                          }else if(data=="2"){
                            output='Passed';
                          }else if(data=="3"){
                            output='Procurement';
                          }else if(data=="4"){
                            output='Completed';
                          }
                          return output;
                        },
                        className: 'text-center'
                    },
                    { data: 'stc_cust_super_requisition_list_approved_by' },
                    { data: 'stc_cust_super_requisition_list_date' },
                    { data: 'actionData' }
                ],
                columnDefs: [
                    { "targets": [0, 1, 3, 4, 5, 6, 7], "className": "text-center" },
                    { "targets": [2, 4], "className": "text-left" },
                    { "targets": [7], "orderable": false }  // Disable ordering on specific columns
                ],
                dom: 'Bfrtip',
                buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
            });

            // Add buttons to container
            table.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        }

        // delete function
        $('.delete-btn').on('click', function(e){
            e.preventDefault();
            var id = $('#delete_id').val();
            $.ajax({
                type: 'get',
                data: {
                    id: id,
                },
                url: "{{ url('/branch/stc/requisitions/delete') }}",
                success: function(response) {
                    if(response.success==true){

                        swalSuccess('success', 'Record deleted.');
                        if ( $.fn.DataTable.isDataTable('#example1') ) {
                        $('#example1').DataTable().destroy();
                        }
                        dataTableAct="active";
                        getRequisitions(dataTableAct);
                        $('.close-btn').click();
                    }else{
                        swalSuccess('error', response.message);
                    }
                }
            });
        });
        
        // get Rack function
        function getRequisitionsItems() {
            // Initialize DataTable
            let table = $('#req-items').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                lengthChange: true,
                autoWidth: false,
                ajax: "{{ url('/branch/stc/requisitions/itemlist') }}",
                columns: [
                  { data: 'stc_cust_super_requisition_list_id' }, 
                  { data: 'stc_cust_super_requisition_list_items_req_id' }, 
                  { data: 'stc_cust_super_requisition_list_items_title' }, 
                  { data: 'stc_cust_super_requisition_list_items_unit' }, 
                  { data: 'stc_cust_super_requisition_list_items_reqqty' }, 
                  { data: 'stc_cust_super_requisition_list_items_approved_qty' }, 
                  { data: 'stc_cust_super_requisition_items_finalqty' }, 
                  {
                      data: 'stc_cust_super_requisition_items_priority',
                      render: function(data, type, row) {
                        var output='';
                        if(data=="1"){
                          output='Normal';
                        }else {
                          output='Urgent';
                        }
                        return output;
                      },
                      className: 'text-center'
                  },
                  {
                      data: 'stc_cust_super_requisition_list_items_status',
                      render: function(data, type, row) {
                        var output='';
                        if(data=="1"){
                          output='Allow';
                        }else {
                          output='Not Allowed';
                        }
                        return output;
                      },
                      className: 'text-center'
                  },
                  { data: 'actionData' }
                ],
                columnDefs: [
                    { "targets": [0, 1, 3, 7, 9], "className": "text-center" },
                    { "targets": [2], "className": "text-left" }, 
                    { "targets": [4, 5, 6], "className": "text-right" },
                    { "targets": [9], "orderable": false }  // Disable ordering on specific columns
                ],
                dom: 'Bfrtip',
                buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
            });

            // Add buttons to container
            table.buttons().container().appendTo('#req-items_wrapper .col-md-6:eq(0)');
        }
        // delete function
        $('.delete-req-itembtn').on('click', function(e){
            e.preventDefault();
            var id = $('#deletereqitem_id').val();
            $.ajax({
                type: 'get',
                data: {
                    id: id,
                },
                url: "{{ url('/branch/stc/requisitions/itemdelete') }}",
                success: function(response) {
                    if(response.success==true){

                        swalSuccess('success', 'Record deleted.');
                        if ( $.fn.DataTable.isDataTable('#req-items') ) {
                        $('#req-items').DataTable().destroy();
                        }
                        dataTableAct="active";
                        getRequisitionsItems(dataTableAct);
                        $('.close-btn').click();
                    }else{
                        swalSuccess('error', response.message);
                    }
                }
            });
        });
        
        // get Rack function
        function getRequisitionsItemsDis() {
            // Initialize DataTable
            let table = $('#req-itemsdis').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                lengthChange: true,
                autoWidth: false,
                ajax: "{{ url('/branch/stc/requisitions/itemdislist') }}",
                columns: [
                  { data: 'stc_cust_super_requisition_list_items_rec_id' }, 
                  { data: 'stc_cust_super_requisition_list_items_rec_list_id' }, 
                  { data: 'stc_cust_super_requisition_list_items_rec_list_item_id' }, 
                  { data: 'stc_cust_super_requisition_list_items_rec_list_pd_id' }, 
                  { data: 'stc_cust_super_requisition_list_items_rec_list_poaid' }, 
                  { data: 'stc_cust_super_requisition_list_items_rec_recqty' }, 
                  { data: 'stc_cust_super_requisition_list_items_rec_date' },
                  { data: 'actionData' }
                ],
                columnDefs: [
                    { "targets": [0, 1, 2, 3, 4, 6, 7], "className": "text-center" },
                    { "targets": [5], "className": "text-right" },
                    { "targets": [7], "orderable": false }  // Disable ordering on specific columns
                ],
                dom: 'Bfrtip',
                buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
            });

            // Add buttons to container
            table.buttons().container().appendTo('#req-itemsdis_wrapper .col-md-6:eq(0)');
        }

        // delete function
        $('.delete-req-itemdispbtn').on('click', function(e){
            e.preventDefault();
            var id = $('#deletereqitemdis_id').val();
            $.ajax({
                type: 'get',
                data: {
                    id: id,
                },
                url: "{{ url('/branch/stc/requisitions/itemdisdelete') }}",
                success: function(response) {
                    if(response.success==true){

                        swalSuccess('success', 'Record deleted.');
                        if ( $.fn.DataTable.isDataTable('#req-itemsdis') ) {
                          $('#req-itemsdis').DataTable().destroy();
                        }
                        dataTableAct="active";
                        getRequisitionsItemsDis(dataTableAct);
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
        <button type="button" class="btn btn-danger delete-btn">Delete</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- delete modal -->
<div class="modal fade" id="delete-modal-item">
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
        <input type="hidden" id="deletereqitem_id">
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default close-btn" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger delete-req-itembtn">Delete</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- delete modal -->
<div class="modal fade" id="delete-modal-itemrec">
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
        <input type="hidden" id="deletereqitemdis_id">
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default close-btn" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger delete-req-itemdispbtn">Delete</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>