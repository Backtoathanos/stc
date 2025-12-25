@extends('layouts.header')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Attendance Management</h3>
    <div class="card-tools">
      <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#importAttendanceModal">
        <i class="fas fa-file-import"></i> Import Attendance
      </button>
    </div>
  </div>
  <div class="card-body">
    <!-- Filters -->
    <div class="row mb-3">
      <div class="col-md-3">
        <label>Month & Year: <span class="text-danger">*</span></label>
        <input type="month" id="filterMonthYear" class="form-control form-control-sm" value="">
      </div>
      <div class="col-md-3">
        <label>Site: <span class="text-danger">*</span></label>
        <select id="filterSite" class="form-control form-control-sm">
          <option value="">Select Site</option>
          @foreach($sites as $site)
            <option value="{{ $site->id }}">{{ $site->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label>&nbsp;</label>
        <button type="button" class="btn btn-secondary btn-sm btn-block" id="resetFilters">
          <i class="fas fa-redo"></i> Reset Filters
        </button>
      </div>
    </div>
    
    <!-- Statistics -->
    <div class="row mb-3">
      <div class="col-md-12">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-chart-bar"></i> Statistics</h3>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-2">
                <div class="info-box">
                  <span class="info-box-icon bg-info"><i class="far fa-calendar"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Working Days</span>
                    <span class="info-box-number" id="statWorkingDays">0</span>
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                <div class="info-box">
                  <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Total Present</span>
                    <span class="info-box-number" id="statTotalPresent">0</span>
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                <div class="info-box">
                  <span class="info-box-icon bg-danger"><i class="fas fa-times-circle"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Total Absent</span>
                    <span class="info-box-number" id="statTotalAbsent">0</span>
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                <div class="info-box">
                  <span class="info-box-icon bg-warning"><i class="fas fa-calendar-times"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Total OT</span>
                    <span class="info-box-number" id="statTotalOT">0</span>
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                <div class="info-box">
                  <span class="info-box-icon bg-primary"><i class="fas fa-calendar-day"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">No. of Sundays</span>
                    <span class="info-box-number" id="statTotalSundays">0</span>
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                <div class="info-box">
                  <span class="info-box-icon bg-success"><i class="fas fa-calculator"></i></span>
                  <div class="info-box-content">
                    <button type="button" class="btn btn-success btn-block" id="processAttendanceBtn" style="margin-top: 10px;">
                      <i class="fas fa-cog fa-spin d-none" id="processSpinner"></i>
                      <i class="fas fa-play-circle" id="processIcon"></i> Process Attendance
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- DataTable -->
    <div class="table-responsive">
      <table id="attendancesTable" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Site Name</th>
            <th>Aadhar</th>
            <th>Employee Name</th>
            <th>Month/Year</th>
            <th>Working Days</th>
            <th>No. of Sundays</th>
            <th>Present</th>
            <th>Absent</th>
            <th>OT (Hours)</th>
            <th>Created At</th>
            <th>Updated At</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Import Attendance Modal -->
<div class="modal fade" id="importAttendanceModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl" role="document" style="max-width: 95%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Import Attendance</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="importAttendanceForm" enctype="multipart/form-data">
        <div class="modal-body">
          <div id="attendanceUploadSection">
            <div class="row mb-3">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Type <span class="text-danger">*</span></label>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="type" id="typeAttendance" value="attendance" checked required>
                    <label class="form-check-label" for="typeAttendance">
                      Attendance
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="type" id="typeOT" value="ot" required>
                    <label class="form-check-label" for="typeOT">
                      OT (Overtime)
                    </label>
                  </div>
                  <small class="form-text text-muted">Select whether to import Attendance or OT data</small>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="attendanceMonthYear">Month & Year <span class="text-danger">*</span></label>
                  <input type="month" class="form-control" id="attendanceMonthYear" name="month_year" value="{{ date('Y-m') }}" required>
                  <small class="form-text text-muted">Select the month and year for this data</small>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="attendanceFile">Select Excel File <span class="text-danger">*</span></label>
              <input type="file" class="form-control-file" id="attendanceFile" name="file" accept=".xlsx,.xls" required>
              <small class="form-text text-muted">Please upload an Excel file (.xlsx or .xls format) with columns: Aadhar, Employee Name, and days 1-30</small>
            </div>
            <div class="alert alert-info" id="importInfo">
              <i class="fas fa-info-circle"></i> Make sure your Excel file matches the sample format. 
              <span id="importInfoText">For Attendance: <strong>P</strong> = Present, <strong>O</strong> = Off/Holiday, <strong>A</strong> = Absent</span>
            </div>
          </div>
          
          <div id="attendancePreviewSection" style="display: none;">
            <div class="alert alert-warning">
              <i class="fas fa-exclamation-triangle"></i> Please review the data below. You can remove rows you don't want to import.
            </div>
            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
              <table class="table table-bordered table-striped table-sm" id="attendancePreviewTable">
                <thead class="thead-light" style="position: sticky; top: 0; z-index: 10;">
                  <tr id="attendancePreviewHeaders"></tr>
                </thead>
                <tbody id="attendancePreviewBody"></tbody>
              </table>
            </div>
            <div class="mt-2">
              <strong>Total Rows:</strong> <span id="attendanceTotalRows">0</span> | 
              <strong>Rows to Import:</strong> <span id="attendanceRowsToImport">0</span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-success" id="downloadAttendanceSample">
            <i class="fas fa-download"></i> Download Sample Excel
          </button>
          <button type="button" class="btn btn-info" id="attendanceBackToUpload" style="display: none;">
            <i class="fas fa-arrow-left"></i> Back to Upload
          </button>
          <button type="submit" class="btn btn-primary" id="attendanceUploadBtn">
            <i class="fas fa-upload"></i> Upload & Preview
          </button>
          <button type="button" class="btn btn-success" id="attendanceSaveBtn" style="display: none;">
            <i class="fas fa-save"></i> Save & Import
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- View Attendance Details Modal -->
<div class="modal fade" id="viewAttendanceModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl" role="document" style="max-width: 95%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Attendance & OT Details</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="viewAttendanceContent">
          <div class="text-center">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
            <p>Loading...</p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#attendancesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: window.appBaseUrl + "/transaction/attendance/list",
            type: 'GET',
            data: function(d) {
                d.month_year = $('#filterMonthYear').val();
                d.site_id = $('#filterSite').val();
            },
            dataSrc: function(json) {
                // Update statistics
                if (json.statistics) {
                    $('#statWorkingDays').text(json.statistics.working_days || 0);
                    $('#statTotalPresent').text(json.statistics.total_present || 0);
                    $('#statTotalAbsent').text(json.statistics.total_absent || 0);
                    $('#statTotalOT').text(json.statistics.total_ot || 0);
                    $('#statTotalSundays').text(json.statistics.total_sundays || 0);
                }
                return json.data;
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'site_name', name: 'site_name' },
            { data: 'aadhar', name: 'aadhar' },
            { data: 'employee_name', name: 'employee_name' },
            { data: 'month_year', name: 'month_year' },
            { data: 'working_days', name: 'working_days' },
            { data: 'sundays', name: 'sundays' },
            { data: 'present', name: 'present' },
            { data: 'absent', name: 'absent' },
            { 
                data: 'ot', 
                name: 'ot',
                render: function(data, type, row) {
                    return data + ' hrs';
                }
            },
            { data: 'created_at', name: 'created_at' },
            { data: 'updated_at', name: 'updated_at' },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return '<button class="btn btn-info btn-sm view-attendance-btn" data-aadhar="' + row.aadhar + '" data-month-year="' + row.month_year + '" title="View Details"><i class="fas fa-eye"></i></button> ' +
                           '<button class="btn btn-danger btn-sm delete-attendance-btn" data-id="' + row.id + '" title="Delete"><i class="fas fa-trash"></i></button>';
                }
            }
        ],
        order: [[4, 'desc'], [3, 'asc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        // Don't load data on initial page load
        deferLoading: 0
    });

    var attendancePreviewData = [];

    // Filter change handler - only load data when both filters are selected
    $('#filterMonthYear, #filterSite').on('change', function() {
        var monthYear = $('#filterMonthYear').val();
        var siteId = $('#filterSite').val();
        
        if (monthYear && siteId) {
            table.draw();
        } else {
            // Clear table if filters are not complete
            table.clear().draw();
            // Reset statistics
            $('#statWorkingDays').text('0');
            $('#statTotalPresent').text('0');
            $('#statTotalAbsent').text('0');
            $('#statTotalOT').text('0');
            $('#statTotalSundays').text('0');
        }
    });

    // Reset filters
    $('#resetFilters').on('click', function() {
        $('#filterMonthYear').val('');
        $('#filterSite').val('');
        table.clear().draw();
        // Reset statistics
        $('#statWorkingDays').text('0');
        $('#statTotalPresent').text('0');
        $('#statTotalAbsent').text('0');
        $('#statTotalOT').text('0');
        $('#statTotalSundays').text('0');
    });

    // Download sample
    $('#downloadAttendanceSample').on('click', function() {
        var type = $('input[name="type"]:checked').val();
        window.location.href = window.appBaseUrl + 'transaction/attendance/export-sample?type=' + type;
    });
    
    // Update info text based on type
    $('input[name="type"]').on('change', function() {
        var type = $(this).val();
        if (type === 'ot') {
            $('#importInfoText').html('For OT: Enter hours worked (0-8) for each day. <strong>0</strong> = No work, <strong>1-8</strong> = Hours worked (max 8 hours)');
        } else {
            $('#importInfoText').html('For Attendance: <strong>P</strong> = Present, <strong>O</strong> = Off/Holiday, <strong>A</strong> = Absent');
        }
    });

    // Upload & Preview
    $('#importAttendanceForm').on('submit', function(e) {
        e.preventDefault();
        
        if ($('#attendanceUploadSection').is(':visible')) {
            // First submit: Upload and preview
            var formData = new FormData();
            formData.append('file', $('#attendanceFile')[0].files[0]);
            
            var type = $('input[name="type"]:checked').val();
            var formDataWithType = new FormData();
            formDataWithType.append('file', $('#attendanceFile')[0].files[0]);
            formDataWithType.append('type', type);
            
            $.ajax({
                url: window.appBaseUrl + 'transaction/attendance/import-preview',
                type: 'POST',
                data: formDataWithType,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function() {
                    $('#attendanceUploadBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
                },
                success: function(response) {
                    if (response.success) {
                        attendancePreviewData = response.data;
                        displayAttendancePreview(response.data, response.headers);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'Failed to process file'
                        });
                    }
                },
                error: function(xhr) {
                    var message = 'Failed to process file';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: message
                    });
                },
                complete: function() {
                    $('#attendanceUploadBtn').prop('disabled', false).html('<i class="fas fa-upload"></i> Upload & Preview');
                }
            });
        }
    });

    // Display preview
    function displayAttendancePreview(data, headers) {
        var thead = $('#attendancePreviewHeaders');
        var tbody = $('#attendancePreviewBody');
        
        thead.empty();
        tbody.empty();
        
        // Add headers
        headers.forEach(function(header) {
            thead.append('<th>' + header + '</th>');
        });
        thead.append('<th>Action</th>');
        
        // Add rows
        data.forEach(function(row, index) {
            var tr = $('<tr data-index="' + index + '"></tr>');
            headers.forEach(function(header) {
                var value = row[header.toLowerCase()] || '';
                tr.append('<td>' + value + '</td>');
            });
            tr.append('<td><button type="button" class="btn btn-danger btn-sm remove-row-btn" data-index="' + index + '"><i class="fas fa-times"></i></button></td>');
            tbody.append(tr);
        });
        
        $('#attendanceTotalRows').text(data.length);
        $('#attendanceRowsToImport').text(data.length);
        
        // Show preview section, hide upload section
        $('#attendanceUploadSection').hide();
        $('#attendancePreviewSection').show();
        $('#attendanceUploadBtn').hide();
        $('#attendanceSaveBtn').show();
        $('#attendanceBackToUpload').show();
    }

    // Remove row from preview
    $(document).on('click', '.remove-row-btn', function() {
        var index = $(this).data('index');
        attendancePreviewData.splice(index, 1);
        
        // Rebuild preview
        var headers = [];
        $('#attendancePreviewHeaders th').each(function() {
            if ($(this).text() !== 'Action') {
                headers.push($(this).text());
            }
        });
        
        displayAttendancePreview(attendancePreviewData, headers);
    });

    // Back to upload
    $('#attendanceBackToUpload').on('click', function() {
        $('#attendanceUploadSection').show();
        $('#attendancePreviewSection').hide();
        $('#attendanceUploadBtn').show();
        $('#attendanceSaveBtn').hide();
        $('#attendanceBackToUpload').hide();
        attendancePreviewData = [];
    });

    // Save & Import
    $('#attendanceSaveBtn').on('click', function() {
        var monthYear = $('#attendanceMonthYear').val();
        var type = $('input[name="type"]:checked').val();
        
        if (!monthYear) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Please select month and year'
            });
            return;
        }
        
        $.ajax({
            url: window.appBaseUrl + '/transaction/attendance/import',
            type: 'POST',
            data: {
                data: attendancePreviewData,
                month_year: monthYear,
                type: type,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function() {
                $('#attendanceSaveBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Importing...');
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message || 'Attendance imported successfully',
                        timer: 3000,
                        showConfirmButton: false
                    }).then(function() {
                        $('#importAttendanceModal').modal('hide');
                        $('#importAttendanceForm')[0].reset();
                        $('#attendanceUploadSection').show();
                        $('#attendancePreviewSection').hide();
                        $('#attendanceUploadBtn').show();
                        $('#attendanceSaveBtn').hide();
                        $('#attendanceBackToUpload').hide();
                        attendancePreviewData = [];
                        table.ajax.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to import attendance'
                    });
                }
            },
            error: function(xhr) {
                var message = 'Failed to import attendance';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: message
                });
            },
            complete: function() {
                $('#attendanceSaveBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Save & Import');
            }
        });
    });

    // View attendance details
    $(document).on('click', '.view-attendance-btn', function() {
        var aadhar = $(this).data('aadhar');
        var monthYear = $(this).data('month-year');
        
        $('#viewAttendanceModal').modal('show');
        $('#viewAttendanceContent').html('<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><p>Loading...</p></div>');
        
        $.ajax({
            url: window.appBaseUrl + '/transaction/attendance/view-details',
            type: 'GET',
            data: {
                aadhar: aadhar,
                month_year: monthYear
            },
            success: function(response) {
                if (response.success) {
                    var data = response.data;
                    var html = '<div class="table-responsive" style="max-height: 600px; overflow-y: auto;">';
                    html += '<table class="table table-bordered table-striped table-sm">';
                    html += '<thead class="teext-center" style="position: sticky; top: 0; z-index: 10;">';
                    html += '<tr>';
                    html += '<th>Aadhar</th>';
                    html += '<th>Name</th>';
                    
                    // Add day headers (1-31)
                    for (var day = 1; day <= 31; day++) {
                        html += '<th style="min-width: 35px;">' + day + '</th>';
                    }
                    
                    html += '<th>Total Present</th>';
                    html += '<th>Total Absent</th>';
                    html += '<th>Total OT (Hours)</th>';
                    html += '</tr>';
                    html += '</thead>';
                    html += '<tbody>';
                    html += '<tr>';
                    html += '<td><strong>' + data.aadhar + '</strong></td>';
                    html += '<td><strong>' + data.employee_name + '</strong></td>';
                    
                    // Add day values
                    for (var day = 1; day <= 31; day++) {
                        var dayValue = data.days[day] || '';
                        var cellClass = '';
                        var cellStyle = '';
                        if (dayValue.startsWith('P')) {
                            cellClass = 'bg-success text-white';
                        } else if (dayValue.startsWith('A')) {
                            cellClass = 'bg-danger text-white';
                        } else if (dayValue.startsWith('O')) {
                            cellClass = 'bg-warning';
                        }
                        if (dayValue === '') {
                            cellStyle = 'background-color: #f8f9fa;';
                        }
                        html += '<td class="' + cellClass + '" style="' + cellStyle + ' text-align: center; font-weight: bold;">' + dayValue + '</td>';
                    }
                    
                    html += '<td><strong class="text-success">' + data.total_present + '</strong></td>';
                    html += '<td><strong class="text-danger">' + data.total_absent + '</strong></td>';
                    html += '<td><strong class="text-primary">' + data.total_ot_hours + ' hrs</strong></td>';
                    html += '</tr>';
                    html += '</tbody>';
                    html += '</table>';
                    html += '</div>';
                    
                    $('#viewAttendanceContent').html(html);
                } else {
                    $('#viewAttendanceContent').html('<div class="alert alert-danger">' + (response.message || 'Failed to load details') + '</div>');
                }
            },
            error: function(xhr) {
                var message = 'Failed to load attendance details';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                $('#viewAttendanceContent').html('<div class="alert alert-danger">' + message + '</div>');
            }
        });
    });

    // Delete attendance
    $(document).on('click', '.delete-attendance-btn', function() {
        var id = $(this).data('id');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: window.appBaseUrl + '/transaction/attendance/' + id,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message || 'Attendance record deleted successfully',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            table.draw();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Failed to delete attendance record'
                            });
                        }
                    },
                    error: function(xhr) {
                        var message = 'Failed to delete attendance record';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: message
                        });
                    }
                });
            }
        });
    });

    // Reset modal on close
    $('#importAttendanceModal').on('hidden.bs.modal', function() {
        $('#importAttendanceForm')[0].reset();
        $('#attendanceUploadSection').show();
        $('#attendancePreviewSection').hide();
        $('#attendanceUploadBtn').show();
        $('#attendanceSaveBtn').hide();
        $('#attendanceBackToUpload').hide();
        attendancePreviewData = [];
        $('#attendanceMonthYear').val('{{ date('Y-m') }}');
    });

    // Process Attendance
    $('#processAttendanceBtn').on('click', function() {
        var monthYear = $('#filterMonthYear').val();
        
        if (!monthYear) {
            Swal.fire({
                icon: 'warning',
                title: 'Filter Required',
                text: 'Please select Month & Year before processing attendance'
            });
            return;
        }
        
        // Show confirmation
        Swal.fire({
            title: 'Process Attendance?',
            html: 'This will calculate payroll for all employees in all sites for:<br><strong>Month:</strong> ' + monthYear,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Process it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading state
                var $btn = $('#processAttendanceBtn');
                var $spinner = $('#processSpinner');
                var $icon = $('#processIcon');
                
                $btn.prop('disabled', true);
                $spinner.removeClass('d-none');
                $icon.addClass('d-none');
                $btn.html('<i class="fas fa-cog fa-spin"></i> Processing...');
                
                // Make AJAX call
                $.ajax({
                    url: window.appBaseUrl + '/transaction/attendance/process',
                    type: 'POST',
                    data: {
                        month_year: monthYear
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            var message = 'Successfully processed ' + response.processed + ' employee(s)';
                            
                            if (response.failed && response.failed.length > 0) {
                                // Show failed records in modal
                                showFailedRecordsModal(response.failed, response.processed);
                            } else {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Processing Complete!',
                                    text: message,
                                    confirmButtonText: 'OK'
                                });
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Processing Failed',
                                text: response.message || 'Failed to process attendance'
                            });
                        }
                    },
                    error: function(xhr) {
                        var message = 'Failed to process attendance';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: message
                        });
                    },
                    complete: function() {
                        // Reset button state
                        $btn.prop('disabled', false);
                        $spinner.addClass('d-none');
                        $icon.removeClass('d-none');
                        $btn.html('<i class="fas fa-play-circle" id="processIcon"></i> Process Attendance');
                    }
                });
            }
        });
    });

    // Function to show failed records modal
    function showFailedRecordsModal(failedRecords, processedCount) {
        var html = '<div class="alert alert-success mb-3">Successfully processed <strong>' + processedCount + '</strong> employee(s)</div>';
        html += '<div class="alert alert-warning mb-3"><strong>' + failedRecords.length + '</strong> employee(s) failed to process:</div>';
        html += '<div class="table-responsive" style="max-height: 400px; overflow-y: auto;">';
        html += '<table class="table table-bordered table-sm">';
        html += '<thead class="thead-light"><tr><th>Employee Name</th><th>Aadhar</th><th>Site</th><th>Reason</th></tr></thead>';
        html += '<tbody>';
        
        failedRecords.forEach(function(record) {
            html += '<tr>';
            html += '<td>' + (record.employee_name || 'N/A') + '</td>';
            html += '<td>' + (record.aadhar || 'N/A') + '</td>';
            html += '<td>' + (record.site_name || 'N/A') + '</td>';
            html += '<td><span class="text-danger">' + (record.reason || 'Unknown error') + '</span></td>';
            html += '</tr>';
        });
        
        html += '</tbody></table></div>';
        
        Swal.fire({
            icon: 'warning',
            title: 'Processing Complete with Errors',
            html: html,
            width: '800px',
            confirmButtonText: 'OK'
        });
    }
});
</script>
@endpush

