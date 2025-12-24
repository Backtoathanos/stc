@extends('layouts.header')

@section('content')
<!-- Small boxes (Stat box) -->
<div class="row">
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-info">
      <div class="inner">
        <h3>{{ $total_employees ?? 0 }}</h3>
        <p>Total Employees</p>
      </div>
      <div class="icon">
        <i class="ion ion-person-add"></i>
      </div>
      <a href="{{ url('/master/employees') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-success">
      <div class="inner">
        <h3>{{ $active_employees ?? 0 }}</h3>
        <p>Active Employees</p>
      </div>
      <div class="icon">
        <i class="ion ion-checkmark"></i>
      </div>
      <a href="{{ url('/master/employees') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-warning">
      <div class="inner">
        <h3>{{ $total_sites ?? 0 }}</h3>
        <p>Total Sites</p>
      </div>
      <div class="icon">
        <i class="ion ion-location"></i>
      </div>
      <a href="{{ url('/master/sites') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-danger">
      <div class="inner">
        <h3>{{ $total_departments ?? 0 }}</h3>
        <p>Total Departments</p>
      </div>
      <div class="icon">
        <i class="ion ion-bag"></i>
      </div>
      <a href="{{ url('/master/departments') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->
</div>
<!-- /.row -->

<!-- Main row -->
<div class="row">
  <div class="col-12">
    <!-- Date Filter Card -->
    <div class="card">
      <div class="card-body">
        <form id="dateFilterForm" class="form-inline">
          <div class="form-group mr-3">
            <label for="filterMonth" class="mr-2"><strong>Select Month (End Month for 12 months range):</strong></label>
            <input type="month" class="form-control" id="filterMonth" name="month" value="{{ $selected_month ?? date('Y-m') }}">
          </div>
          <div class="form-group mr-3">
            <label for="filterSite" class="mr-2"><strong>Select Site:</strong></label>
            <select class="form-control" id="filterSite" name="site_id">
              <option value="all" {{ ($selected_site ?? 'all') == 'all' ? 'selected' : '' }}>All Sites</option>
              @foreach($sites ?? [] as $site)
                <option value="{{ $site->id }}" {{ ($selected_site ?? 'all') == $site->id ? 'selected' : '' }}>{{ $site->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-filter"></i> Filter
            </button>
            <button type="button" class="btn btn-secondary ml-2" id="resetFilter">
              <i class="fas fa-redo"></i> Reset
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Left col -->
  <section class="col-lg-8 connectedSortable">
    <!-- Bar Chart -->
    <div class="card">
      <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
          <h3 class="card-title mb-0">
            <i class="fas fa-chart-bar mr-1"></i>
            Monthly Attendance Overview
          </h3>
          <div class="form-group mb-0">
            <select class="form-control form-control-sm" id="chartFormat" style="width: auto; display: inline-block;">
              <option value="numbers">Numbers</option>
              <option value="percentage">Percentage</option>
            </select>
          </div>
        </div>
      </div><!-- /.card-header -->
      <div class="card-body">
        <div id="attendanceBarChart" style="height: 400px;"></div>
      </div><!-- /.card-body -->
    </div>
    <!-- /.card -->
  </section>
  <!-- /.Left col -->
  
  <!-- Right col -->
  <section class="col-lg-4 connectedSortable">
    <!-- Donut Chart -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-chart-pie mr-1"></i>
          Attendance vs Working Days
        </h3>
      </div><!-- /.card-header -->
      <div class="card-body">
        <div id="attendanceDonutChart" style="height: 400px;"></div>
      </div><!-- /.card-body -->
    </div>
    <!-- /.card -->
  </section>
  <!-- /.Right col -->
</div>
<!-- /.row (main row) -->
@endsection

@push('scripts')
<!-- Highcharts -->
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>

<script>
var barChart, donutChart;
var chartData = {
    months: [],
    attendance: [],
    workingDays: [],
    attendancePercent: []
};

function loadCharts(months, attendance, workingDays, attendancePercent, totalAttendance, totalAbsent) {
    // Store data for format switching
    chartData.months = months;
    chartData.attendance = attendance;
    chartData.workingDays = workingDays;
    chartData.attendancePercent = attendancePercent;
    
    // Load chart based on current format selection
    var format = $('#chartFormat').val() || 'numbers';
    loadBarChart(format);
    
    
    // Donut Chart - Total Attendance vs Working Days
    if (donutChart) {
        donutChart.destroy();
    }
    
    donutChart = Highcharts.chart('attendanceDonutChart', {
        chart: {
            type: 'pie',
            backgroundColor: '#ffffff'
        },
        title: {
            text: 'Total Attendance Overview'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b><br/>Total: <b>{point.y}</b> days'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        fontSize: '12px'
                    }
                },
                innerSize: '50%',
                showInLegend: true
            }
        },
        series: [{
            name: 'Days',
            colorByPoint: true,
            data: [{
                name: 'Present',
                y: totalAttendance,
                color: '#28a745'
            }, {
                name: 'Absent',
                y: totalAbsent,
                color: '#dc3545'
            }]
        }],
        credits: {
            enabled: false
        }
    });
}

function loadBarChart(format) {
    if (barChart) {
        barChart.destroy();
    }
    
    if (format === 'percentage') {
        // Percentage Chart
        barChart = Highcharts.chart('attendanceBarChart', {
            chart: {
                type: 'column',
                backgroundColor: '#ffffff'
            },
            title: {
                text: 'Monthly Attendance Percentage'
            },
            xAxis: {
                categories: chartData.months,
                title: {
                    text: 'Month'
                },
                labels: {
                    rotation: -45,
                    style: {
                        fontSize: '10px'
                    },
                    useHTML: true
                }
            },
            yAxis: {
                min: 0,
                max: 100,
                title: {
                    text: 'Percentage (%)'
                }
            },
            tooltip: {
                pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}%</b><br/>'
            },
            plotOptions: {
                column: {
                    dataLabels: {
                        enabled: true,
                        format: '{y}%'
                    }
                }
            },
            series: [{
                name: 'Attendance %',
                data: chartData.attendancePercent,
                color: '#28a745'
            }],
            credits: {
                enabled: false
            }
        });
    } else {
        // Numbers Chart
        barChart = Highcharts.chart('attendanceBarChart', {
            chart: {
                type: 'column',
                backgroundColor: '#ffffff'
            },
            title: {
                text: 'Monthly Attendance vs Working Days (Numbers)'
            },
            xAxis: {
                categories: chartData.months,
                title: {
                    text: 'Month'
                },
                labels: {
                    rotation: -45,
                    style: {
                        fontSize: '10px'
                    },
                    useHTML: true
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Days'
                }
            },
            tooltip: {
                shared: true,
                pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>'
            },
            plotOptions: {
                column: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            series: [{
                name: 'Attendance',
                data: chartData.attendance,
                color: '#28a745'
            }, {
                name: 'Working Days',
                data: chartData.workingDays,
                color: '#007bff'
            }],
            credits: {
                enabled: false
            }
        });
    }
}

$(document).ready(function() {
    // Prepare data from PHP
    var months = @json($attendance_data['months'] ?? []);
    var attendance = @json($attendance_data['attendance'] ?? []);
    var workingDays = @json($attendance_data['working_days'] ?? []);
    var attendancePercent = @json($attendance_data['attendance_percent'] ?? []);
    var workingDaysInMonth = @json($attendance_data['working_days_in_month'] ?? []);
    var employeeCount = @json($attendance_data['employee_count'] ?? []);
    var totalAttendance = @json($attendance_data['total_attendance'] ?? 0);
    var totalWorkingDays = @json($attendance_data['total_working_days'] ?? 0);
    var totalAbsent = @json($attendance_data['total_absent'] ?? 0);
    
    // Format month labels with breakdown: "Month<br/>X days - Y emp"
    var formattedMonths = months.map(function(month, index) {
        var days = workingDaysInMonth[index] || 0;
        var emp = employeeCount[index] || 0;
        return month + '<br/>' + days + ' days - ' + emp + ' emp';
    });
    
    // Load charts initially
    loadCharts(formattedMonths, attendance, workingDays, attendancePercent, totalAttendance, totalAbsent);
    
    // Handle form submission
    $('#dateFilterForm').on('submit', function(e) {
        e.preventDefault();
        var month = $('#filterMonth').val();
        var siteId = $('#filterSite').val();
        
        // Show loading
        $('#attendanceBarChart').html('<div class="text-center p-5"><i class="fas fa-spinner fa-spin fa-3x"></i><br/><p>Loading...</p></div>');
        $('#attendanceDonutChart').html('<div class="text-center p-5"><i class="fas fa-spinner fa-spin fa-3x"></i><br/><p>Loading...</p></div>');
        
        // Fetch new data
        $.ajax({
            url: window.appBaseUrl + '/dashboard/attendance-data',
            type: 'GET',
            data: { 
                month: month,
                site_id: siteId
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    var data = response.data;
                    
                    // Format month labels with breakdown: "Month<br/>X days - Y emp"
                    var formattedMonths = data.months.map(function(month, index) {
                        var days = data.working_days_in_month[index] || 0;
                        var emp = data.employee_count[index] || 0;
                        return month + '<br/>' + days + ' days - ' + emp + ' emp';
                    });
                    
                    loadCharts(
                        formattedMonths,
                        data.attendance,
                        data.working_days,
                        data.attendance_percent,
                        data.total_attendance,
                        data.total_absent
                    );
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load attendance data'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load attendance data'
                });
            }
        });
    });
    
    // Handle reset button
    $('#resetFilter').on('click', function() {
        $('#filterMonth').val('{{ date("Y-m") }}');
        $('#filterSite').val('all');
        $('#dateFilterForm').submit();
    });
    
    // Handle chart format change
    $('#chartFormat').on('change', function() {
        var format = $(this).val();
        if (chartData.months && chartData.months.length > 0) {
            loadBarChart(format);
        }
    });
});
</script>
@endpush

