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
            <h1 class="m-0">Status Down List</h1>
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
                        <th class="text-center">ID</th>
                        <th class="text-center">Created Date</th>
                        <th class="text-center">Location</th>
                        <th class="text-center">Project name</th>
                        <th class="text-center">Equipment Number</th>
                        <th class="text-center">Equipment Status</th>
                        <th class="text-center">Manpower Req</th>
                        <th class="text-center">Sub Location</th>
                        <th class="text-center">Area</th>
                        <th class="text-center">Equipment Type</th>
                        <th class="text-center">Reason</th>
                        <th class="text-center">Material Desc</th>
                        <th class="text-center">From Date</th>
                        <th class="text-center">Rect Date</th>
                        <th class="text-center">Tools Req</th>
                        <th class="text-center">Remarks</th>
                        <th class="text-center">Job Type</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Capacity</th>
                        <th class="text-center">Reason Attribute</th>
                        <th class="text-center">Varities Id</th>
                        <th class="text-center">Created_by_select</th>
                        <th class="text-center">Permit No</th>
                        <th class="text-center">Creator Details</th>
                        <th class="text-center">Responsive Person</th>
                        <th class="text-center">Target Date</th>
                        <th class="text-center">Jobdone Details</th>
                        <th class="text-center">Jobpending Details</th>
                        <th class="text-center">Failure Root Cost</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Fremarks</th>
                        <th class="text-center">Ftarget Date</th>
                        <th class="text-center">Wipstatus</th>
                        <th class="text-center">Created By</th>
                        <th class="text-center">Updated By</th>
                        <th class="text-center">Updated Date</th>
                        <th class="text-center">Status Updated By</th>
                        <th class="text-center">Status Updated On</th>
                        <th class="text-center">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Created Date</th>
                        <th class="text-center">Location</th>
                        <th class="text-center">Project name</th>
                        <th class="text-center">Equipment Number</th>
                        <th class="text-center">Equipment Status</th>
                        <th class="text-center">Manpower Req</th>
                        <th class="text-center">Sub Location</th>
                        <th class="text-center">Area</th>
                        <th class="text-center">Equipment Type</th>
                        <th class="text-center">Reason</th>
                        <th class="text-center">Material Desc</th>
                        <th class="text-center">From Date</th>
                        <th class="text-center">Rect Date</th>
                        <th class="text-center">Tools Req</th>
                        <th class="text-center">Remarks</th>
                        <th class="text-center">Job Type</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center">Capacity</th>
                        <th class="text-center">Reason Attribute</th>
                        <th class="text-center">Varities Id</th>
                        <th class="text-center">Created_by_select</th>
                        <th class="text-center">Permit No</th>
                        <th class="text-center">Creator Details</th>
                        <th class="text-center">Responsive Person</th>
                        <th class="text-center">Target Date</th>
                        <th class="text-center">Jobdone Details</th>
                        <th class="text-center">Jobpending Details</th>
                        <th class="text-center">Failure Root Cost</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Fremarks</th>
                        <th class="text-center">Ftarget Date</th>
                        <th class="text-center">Wipstatus</th>
                        <th class="text-center">Created By</th>
                        <th class="text-center">Updated By</th>
                        <th class="text-center">Updated Date</th>
                        <th class="text-center">Status Updated By</th>
                        <th class="text-center">Status Updated On</th>
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
            // ajax: "{{ url('/branch/stc/std/list') }}",
            ajax: {
                url: "{{ url('/branch/stc/std/list') }}",
                type: 'POST', // Use POST for large requests
                data: function(d) {
                    d._token = "{{ csrf_token() }}"; // Add CSRF token for security
                }
            },
            columns: [
                { data: 'stc_status_down_list_id' },
                { data: 'stc_status_down_list_date' },
                { data: 'stc_status_down_list_plocation' },
                { data: 'stc_status_down_list_location' },
                { data: 'stc_status_down_list_equipment_number' },
                { data: 'stc_status_down_list_equipment_status' },
                { data: 'stc_status_down_list_manpower_req' },
                { data: 'stc_status_down_list_sub_location' },
                { data: 'stc_status_down_list_area' },
                { data: 'stc_status_down_list_equipment_type' },
                { data: 'stc_status_down_list_reason' },
                { data: 'stc_status_down_list_material_desc' },
                { data: 'stc_status_down_list_from_date' },
                { data: 'stc_status_down_list_rect_date' },
                { data: 'stc_status_down_list_tools_req' },
                { data: 'stc_status_down_list_remarks' },
                { data: 'stc_status_down_list_jobtype' },
                { data: 'stc_status_down_list_qty' },
                { data: 'stc_status_down_list_capacity' },
                { data: 'stc_status_down_list_reasonattribute' },
                { data: 'stc_status_down_list_varities_id' },
                { data: 'stc_status_down_list_created_by_select' },
                { data: 'stc_status_down_list_permit_no' },
                { data: 'stc_status_down_list_creator_details' },
                { data: 'stc_status_down_list_responsive_person' },
                { data: 'stc_status_down_list_target_date' },
                { data: 'stc_status_down_list_jobdone_details' },
                { data: 'stc_status_down_list_jobpending_details' },
                { data: 'stc_status_down_list_failurerootcost' },
                {
                    data: 'stc_status_down_list_status',
                        render: function(data, type, row) {
                            var output='Closed';
                            if(data==1){
                                output="Planning";
                            }else if(data==2){
                                output="Pending";
                            }else if(data==3){
                                output="Work-in-Progress";
                            }else if(data==4){
                                output="Work-Done";
                            }else if(data==5){
                                output="Work Complete";
                            }
                            return output;
                        },
                        className: 'text-center'
                    },
                { data: 'stc_status_down_list_fremarks' },
                { data: 'stc_status_down_list_ftarget_date' },
                { data: 'stc_status_down_list_wipstatus' },
                { data: 'stc_status_down_list_created_by' },
                { data: 'stc_status_down_list_updated_by' },
                { data: 'stc_status_down_list_updated_date' },
                { data: 'stc_status_down_list_status_updated_by' },
                { data: 'stc_status_down_list_status_updated_on' },
                { data: 'actionData' }
            ],
            columnDefs: [
                { "targets": [0, 2, 3, 4], "className": "text-center" },
                { "targets": [1, 5, 6], "className": "text-left" },
                { "targets": [4,5,6,7,8,9,10,11], "orderable": false }  // Disable ordering on the last column
            ],
            dom: 'Bfrtip',
            buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
        });

        // Add buttons to container
        table.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        // Delete function
        $('.delete-btn').on('click', function(e){
            e.preventDefault();
            var id = $('#delete_id').val();
            $.ajax({
                type: 'GET',
                data: { id: id },
                url: "{{ url('/branch/stc/std/delete') }}",
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