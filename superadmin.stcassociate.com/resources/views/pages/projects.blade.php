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
            <h1 class="m-0">Projects</h1>
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
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th class="text-center">Id</th>
                        <th class="text-center">Company Name</th>
                        <th class="text-center">Title</th>
                        <th class="text-center">Reference Number</th>
                        <th class="text-center">Address</th>
                        <th class="text-center">City</th>
                        <th class="text-center">State</th>
                        <th class="text-center">Responsive Person</th>
                        <th class="text-center">Supervisor Quantity</th>
                        <th class="text-center">Beg Date</th>
                        <th class="text-center">End Date</th>
                        <th class="text-center">Beg Budget</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Editable Mincount</th>
                        <th class="text-center">Editable Maxcount</th>
                        <th class="text-center">Created By</th>
                        <th class="text-center">Created Date</th>
                        <th class="text-center">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th class="text-center">Id</th>
                        <th class="text-center">Company Name</th>
                        <th class="text-center">Title</th>
                        <th class="text-center">Reference Number</th>
                        <th class="text-center">Address</th>
                        <th class="text-center">City</th>
                        <th class="text-center">State</th>
                        <th class="text-center">Responsive Person</th>
                        <th class="text-center">Supervisor Quantity</th>
                        <th class="text-center">Beg Date</th>
                        <th class="text-center">End Date</th>
                        <th class="text-center">Beg Budget</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Editable Mincount</th>
                        <th class="text-center">Editable Maxcount</th>
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
        getProjects(dataTableAct);      
      
        // get Rack function
        function getProjects() {
            // Initialize DataTable
            let table = $('#example1').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                lengthChange: false,
                autoWidth: true,
                ajax: "{{ url('/branch/stc/projects/list') }}",
                columns: [
                    { data: 'stc_cust_project_id' },
                    { data: 'stc_customer_name' },
                    { data: 'stc_cust_project_title' },
                    { data: 'stc_cust_project_refr' },
                    { data: 'stc_cust_project_address' },
                    { data: 'stc_city_name' },
                    { data: 'stc_state_name' },
                    { data: 'stc_cust_project_responsive_person' },
                    { data: 'stc_cust_project_supervis_qty' },
                    { data: 'stc_cust_project_beg_date' },
                    { data: 'stc_cust_project_end_date' },
                    { data: 'stc_cust_project_beg_budget' },
                    {
                        data: 'stc_cust_project_status',
                        render: function(data, type, row) {
                            return data === '1' ? 'Active' : 'Inactive';
                        },
                        className: 'text-center'
                    },
                    { data: 'stc_cust_project_editable_mincount' },
                    { data: 'stc_cust_project_editable_maxcount' },
                    { data: 'stc_agents_name' },
                    { data: 'stc_cust_project_date' },
                    { data: 'actionData' }
                ],
                columnDefs: [
                    { "targets": [0, 1, 3, 5, 6, 7, 8, 9, 10, 12, 13, 14, 15, 16], "className": "text-center" },
                    { "targets": [2, 4], "className": "text-left" },
                    { "targets": [11], "className": "text-right" },
                    { "targets": [17], "orderable": false }  // Disable ordering on specific columns
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
                url: "{{ url('/branch/stc/projects/delete') }}",
                success: function(response) {
                    if(response.success==true){

                        swalSuccess('success', 'Record deleted.');
                        if ( $.fn.DataTable.isDataTable('#example1') ) {
                        $('#example1').DataTable().destroy();
                        }
                        dataTableAct="active";
                        getProjects(dataTableAct);
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