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
            <h1 class="m-0">T&amp;T Data</h1>
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
                <div class="card-header">
                  <button type="button" class="btn btn-success btn-sm" id="add-tandt-btn"><i class="fas fa-plus"></i> Add</button>
                </div>
                <div class="card-body">
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Department</th>
                        <th>Equipment</th>
                        <th>Model No</th>
                        <th>Serial/THM No</th>
                        <th>Make</th>
                        <th>Range</th>
                        <th>Issued Date</th>
                        <th>Due Date</th>
                        <th>Left Day</th>
                        <th>Status</th>
                        <th>Certificate</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody></tbody>
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

<div class="modal fade" id="edit-modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="tandt-modal-title">T&amp;T Data</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <form id="tandt-form" enctype="multipart/form-data">
          <input type="hidden" id="edit_id" name="id">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Department Name</label>
                <input type="text" class="form-control" id="department_name" name="department_name">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Equipment Name</label>
                <input type="text" class="form-control" id="title" name="title" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Model No</label>
                <input type="text" class="form-control" id="model_no" name="model_no">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Serial/THM No</label>
                <input type="text" class="form-control" id="serial_thm_no" name="serial_thm_no">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Make Name</label>
                <input type="text" class="form-control" id="make_name" name="make_name">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Range</label>
                <input type="text" class="form-control" id="range_value" name="range_value">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Calibration Issued Date</label>
                <input type="date" class="form-control" id="calibration_date" name="calibration_date" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Calibration Due Date</label>
                <input type="date" class="form-control" id="calibration_due" name="calibration_due" required>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label>Certificate</label>
                <input type="file" class="form-control" id="certificate" name="certificate">
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="save-tandt-btn">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="delete-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Delete record</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">Delete this T&amp;T record?</div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger delete-btn">Delete</button>
      </div>
      <input type="hidden" id="delete_id">
    </div>
  </div>
</div>

<script>
    $(document).ready(function() {
        function swalSuccess(icon, message) {
            Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000 })
                .fire({ icon: icon, title: message });
        }

        let table = $('#example1').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth: false,
            ajax: {
                url: "{{ url('/branch/stc/tandtdata/list') }}",
                type: 'POST',
                data: function(d) { d._token = "{{ csrf_token() }}"; }
            },
            columns: [
                { data: 'id' },
                { data: 'department_name' },
                { data: 'title' },
                { data: 'model_no' },
                { data: 'serial_thm_no' },
                { data: 'make_name' },
                { data: 'range_value' },
                { data: 'calibration_date' },
                { data: 'calibration_due' },
                { data: 'left_day' },
                { data: 'cal_status' },
                { data: 'docs' },
                { data: 'actionData' }
            ],
            order: [[0, 'desc']],
            columnDefs: [
                { targets: [11, 12], orderable: false },
                { targets: [0, 7, 8, 9, 10, 11, 12], className: 'text-center' }
            ],
            dom: 'Bfrtip',
            buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
        });
        table.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

        function resetForm() {
            $('#tandt-form')[0].reset();
            $('#edit_id').val('');
            $('#tandt-modal-title').text('T&T Data - Add');
        }

        $('#add-tandt-btn').on('click', function(){
            resetForm();
            $('#edit-modal').modal('show');
        });

        $('body').on('click', '.edit-modal-btn', function(){
            var id = $(this).data('id');
            $.get("{{ url('/branch/stc/tandtdata/get') }}", { id: id }, function(res){
                if(!res.success || !res.data){
                    swalSuccess('error', res.message || 'Failed to load record');
                    return;
                }
                var r = res.data;
                $('#edit_id').val(r.id);
                $('#department_name').val(r.department_name || '');
                $('#title').val(r.title || '');
                $('#model_no').val(r.model_no || '');
                $('#serial_thm_no').val(r.serial_thm_no || '');
                $('#make_name').val(r.make_name || '');
                $('#range_value').val(r.range_value || '');
                $('#calibration_date').val(r.calibration_date || '');
                $('#calibration_due').val(r.calibration_due || '');
                $('#certificate').val('');
                $('#tandt-modal-title').text('T&T Data - Edit');
                $('#edit-modal').modal('show');
            });
        });

        $('#save-tandt-btn').on('click', function(){
            var id = $('#edit_id').val();
            var formData = new FormData($('#tandt-form')[0]);
            formData.append('_token', "{{ csrf_token() }}");
            $.ajax({
                url: id ? "{{ url('/branch/stc/tandtdata/edit') }}" : "{{ url('/branch/stc/tandtdata/store') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(res){
                    if(res.success){
                        swalSuccess('success', res.message);
                        $('#edit-modal').modal('hide');
                        table.ajax.reload(null, false);
                    } else {
                        swalSuccess('error', res.message || 'Save failed');
                    }
                },
                error: function(){ swalSuccess('error', 'Save failed'); }
            });
        });

        $('.delete-btn').on('click', function(){
            $.get("{{ url('/branch/stc/tandtdata/delete') }}", { id: $('#delete_id').val() }, function(res){
                if(res.success){
                    swalSuccess('success', res.message);
                    $('#delete-modal').modal('hide');
                    table.ajax.reload(null, false);
                } else {
                    swalSuccess('error', res.message || 'Delete failed');
                }
            });
        });
    });
</script>
</body>
</html>
