@extends('layouts.header')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Calendar - Working Days Management</h3>
    <div class="card-tools">
      <div class="form-group mb-0">
        <label for="yearSelect" class="mr-2">Year:</label>
        <select id="yearSelect" class="form-control form-control-sm" style="display: inline-block; width: auto;">
          @for($y = date('Y') - 5; $y <= date('Y') + 5; $y++)
            <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
          @endfor
        </select>
      </div>
    </div>
  </div>
  <div class="card-body">
    <div id="calendarContainer" class="row">
      <!-- Calendar will be generated here -->
    </div>
  </div>
</div>

<!-- Date Leave Management Modal -->
<div class="modal fade" id="dateLeaveModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Manage Leave Types - <span id="modalDateText"></span></h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Add Leave Type Form -->
        <div class="card mb-3">
          <div class="card-header">
            <h6 class="mb-0">Add Leave Type</h6>
          </div>
          <div class="card-body">
            <form id="addLeaveForm">
              <input type="hidden" id="selectedDate" name="date">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Leave Type <span class="text-danger">*</span></label>
                    <select class="form-control" id="leaveType" name="leave_type" required>
                      <option value="">Select Leave Type</option>
                      <option value="NH">NH - National Holiday</option>
                      <option value="CL">CL - Casual Leave</option>
                      <option value="SL">SL - Sick Leave</option>
                      <option value="PL">PL - Privilege Leave</option>
                      <option value="EL">EL - Earned Leave</option>
                      <option value="ML">ML - Maternity Leave</option>
                      <option value="LWP">LWP - Leave Without Pay</option>
                      <option value="WO">WO - Weekly Off</option>
                      <option value="HD">HD - Half Day</option>
                      <option value="OD">OD - On Duty</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Description</label>
                    <input type="text" class="form-control" id="leaveDescription" name="description" placeholder="Optional description">
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Leave Type
              </button>
            </form>
          </div>
        </div>

        <!-- Existing Leave Types Table -->
        <div class="card">
          <div class="card-header">
            <h6 class="mb-0">Existing Leave Types for this Date</h6>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered table-striped" id="leaveTypesTable">
                <thead>
                  <tr>
                    <th>Leave Type</th>
                    <th>Description</th>
                    <th>Added On</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody id="leaveTypesTableBody">
                  <!-- Will be populated via AJAX -->
                </tbody>
              </table>
            </div>
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
<style>
.calendar-month {
  margin-bottom: 30px;
}

.calendar-month-title {
  font-size: 18px;
  font-weight: bold;
  margin-bottom: 10px;
  text-align: center;
  color: #495057;
}

.calendar-grid {
  display: grid;
  grid-template-columns: repeat(7, 1fr);
  gap: 2px;
  background-color: #dee2e6;
  border: 1px solid #dee2e6;
}

.calendar-day-header {
  background-color: #343a40;
  color: white;
  padding: 8px;
  text-align: center;
  font-weight: bold;
  font-size: 12px;
}

.calendar-day {
  background-color: white;
  padding: 8px;
  min-height: 60px;
  cursor: pointer;
  transition: background-color 0.2s;
  position: relative;
}

.calendar-day:hover {
  background-color: #e9ecef;
}

.calendar-day.other-month {
  background-color: #f8f9fa;
  color: #adb5bd;
}

.calendar-day.today {
  background-color: #fff3cd;
  font-weight: bold;
}

.calendar-day.has-leave {
  background-color: #d4edda;
  border: 2px solid #28a745;
}

.calendar-day-number {
  font-size: 14px;
  font-weight: bold;
}

.calendar-day-leaves {
  font-size: 10px;
  margin-top: 4px;
  color: #28a745;
  font-weight: bold;
}

.leave-badge {
  display: inline-block;
  padding: 2px 6px;
  margin: 1px;
  background-color: #28a745;
  color: white;
  border-radius: 3px;
  font-size: 9px;
}
</style>

<script>
$(document).ready(function() {
    var currentYear = new Date().getFullYear();
    var leavesData = {};
    var baseUrl = '/stc/stc_payroll';

    // Initialize calendar
    function initCalendar() {
        currentYear = parseInt($('#yearSelect').val());
        loadLeaves();
    }

    // Load leaves for the selected year
    function loadLeaves() {
        $.ajax({
            url: baseUrl + '/calendar/leaves',
            type: 'GET',
            data: { year: currentYear },
            success: function(response) {
                if (response.success) {
                    leavesData = response.data;
                    renderCalendar();
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load calendar data'
                });
            }
        });
    }

    // Render calendar with 3 columns (4 months per column)
    function renderCalendar() {
        var months = ['January', 'February', 'March', 'April', 'May', 'June', 
                     'July', 'August', 'September', 'October', 'November', 'December'];
        var html = '';

        // Create 3 columns, each with 4 months
        for (var col = 0; col < 3; col++) {
            html += '<div class="col-md-4">';
            for (var m = col * 4; m < (col + 1) * 4 && m < 12; m++) {
                html += renderMonth(m, months[m]);
            }
            html += '</div>';
        }

        $('#calendarContainer').html(html);
    }

    // Render a single month
    function renderMonth(monthIndex, monthName) {
        var firstDay = new Date(currentYear, monthIndex, 1).getDay();
        var daysInMonth = new Date(currentYear, monthIndex + 1, 0).getDate();
        var today = new Date();
        var isCurrentMonth = today.getFullYear() == currentYear && today.getMonth() == monthIndex;

        var html = '<div class="calendar-month">';
        html += '<div class="calendar-month-title">' + monthName + ' ' + currentYear + '</div>';
        html += '<div class="calendar-grid">';

        // Day headers
        var dayHeaders = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        dayHeaders.forEach(function(day) {
            html += '<div class="calendar-day-header">' + day + '</div>';
        });

        // Empty cells for days before month starts
        for (var i = 0; i < firstDay; i++) {
            html += '<div class="calendar-day other-month"></div>';
        }

        // Days of the month
        for (var day = 1; day <= daysInMonth; day++) {
            var dateStr = currentYear + '-' + String(monthIndex + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0');
            var dateObj = new Date(currentYear, monthIndex, day);
            var isToday = isCurrentMonth && day == today.getDate();
            var hasLeave = leavesData[dateStr] && leavesData[dateStr].length > 0;
            var leaveTypes = hasLeave ? leavesData[dateStr].map(function(l) { return l.leave_type; }).join(', ') : '';

            var classes = 'calendar-day';
            if (isToday) classes += ' today';
            if (hasLeave) classes += ' has-leave';

            html += '<div class="' + classes + '" data-date="' + dateStr + '">';
            html += '<div class="calendar-day-number">' + day + '</div>';
            if (hasLeave) {
                html += '<div class="calendar-day-leaves">';
                leavesData[dateStr].forEach(function(leave) {
                    html += '<span class="leave-badge">' + leave.leave_type + '</span>';
                });
                html += '</div>';
            }
            html += '</div>';
        }

        html += '</div></div>';
        return html;
    }

    // Year change handler
    $('#yearSelect').on('change', function() {
        initCalendar();
    });

    // Date click handler
    $(document).on('click', '.calendar-day:not(.other-month)', function() {
        var date = $(this).data('date');
        if (!date) return;

        $('#selectedDate').val(date);
        var dateObj = new Date(date);
        var options = { year: 'numeric', month: 'long', day: 'numeric' };
        $('#modalDateText').text(dateObj.toLocaleDateString('en-US', options));
        
        loadDateLeaves(date);
        $('#dateLeaveModal').modal('show');
    });

    // Load leaves for a specific date
    function loadDateLeaves(date) {
        $.ajax({
            url: baseUrl + '/calendar/leaves/' + date,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    renderLeaveTypesTable(response.data);
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load leave types'
                });
            }
        });
    }

    // Render leave types table
    function renderLeaveTypesTable(leaves) {
        var tbody = $('#leaveTypesTableBody');
        tbody.empty();

        if (leaves.length === 0) {
            tbody.html('<tr><td colspan="4" class="text-center text-muted">No leave types added for this date</td></tr>');
            return;
        }

        leaves.forEach(function(leave) {
            var row = '<tr>' +
                '<td><strong>' + leave.leave_type + '</strong></td>' +
                '<td>' + (leave.description || '-') + '</td>' +
                '<td>' + leave.created_at + '</td>' +
                '<td>' +
                '<button class="btn btn-danger btn-sm remove-leave-btn" data-id="' + leave.id + '" title="Remove">' +
                '<i class="fas fa-trash"></i> Remove' +
                '</button>' +
                '</td>' +
                '</tr>';
            tbody.append(row);
        });
    }

    // Add leave type form submission
    $('#addLeaveForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: baseUrl + '/calendar/leaves',
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message || 'Leave type added successfully',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    $('#addLeaveForm')[0].reset();
                    var date = $('#selectedDate').val();
                    loadDateLeaves(date);
                    loadLeaves(); // Reload calendar to show updated leaves
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to add leave type'
                    });
                }
            },
            error: function(xhr) {
                var message = 'Failed to add leave type';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors;
                    message = Object.values(errors).flat().join('<br>');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: message
                });
            }
        });
    });

    // Remove leave type
    $(document).on('click', '.remove-leave-btn', function() {
        var id = $(this).data('id');
        var date = $('#selectedDate').val();

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, remove it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: baseUrl + '/calendar/leaves/' + id,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Removed!',
                                text: response.message || 'Leave type removed successfully',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            loadDateLeaves(date);
                            loadLeaves(); // Reload calendar to show updated leaves
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Failed to remove leave type'
                            });
                        }
                    },
                    error: function(xhr) {
                        var message = 'Failed to remove leave type';
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

    // Initialize on page load
    initCalendar();
});
</script>
@endpush

