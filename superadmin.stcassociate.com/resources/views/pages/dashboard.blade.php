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

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
              <div class="inner">
                <h3>{{ number_format($counts['products']) }}</h3>
                <p>Products <small>({{ number_format($counts['products_active']) }} active)</small></p>
              </div>
              <div class="icon"><i class="ion ion-bag"></i></div>
              <a href="{{ url('/master/product') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{ number_format($counts['merchants']) }}</h3>
                <p>Merchants</p>
              </div>
              <div class="icon"><i class="ion ion-ios-briefcase"></i></div>
              <a href="{{ url('/master/merchant') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{ number_format($counts['projects']) }}</h3>
                <p>Projects</p>
              </div>
              <div class="icon"><i class="ion ion-ios-location"></i></div>
              <a href="{{ url('/branch/stc/projects') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{ number_format($counts['requisitions']) }}</h3>
                <p>Requisitions</p>
              </div>
              <div class="icon"><i class="ion ion-clipboard"></i></div>
              <a href="{{ url('/branch/stc/requisitions') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
              <div class="inner">
                <h3>{{ number_format($counts['poadhoc']) }}</h3>
                <p>PO Adhoc</p>
              </div>
              <div class="icon"><i class="ion ion-android-cart"></i></div>
              <a href="{{ url('/branch/stc/poadhoc') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-secondary">
              <div class="inner">
                <h3>{{ number_format($counts['equipment']) }}</h3>
                <p>Equipment</p>
              </div>
              <div class="icon"><i class="ion ion-settings"></i></div>
              <a href="{{ url('/branch/stc/equipment') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-olive">
              <div class="inner">
                <h3>{{ number_format($counts['users']) }}</h3>
                <p>Users</p>
              </div>
              <div class="icon"><i class="ion ion-person-stalker"></i></div>
              <a href="{{ url('/users') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-lg-3 col-6">
            <div class="small-box bg-maroon">
              <div class="inner">
                <h3>{{ number_format($counts['std']) }}</h3>
                <p>Status Down List</p>
              </div>
              <div class="icon"><i class="ion ion-alert-circled"></i></div>
              <a href="{{ url('/branch/stc/std') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>

        <div class="row">
          <section class="col-lg-7 connectedSortable">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-area mr-1"></i> Requisitions (last 12 months)</h3>
              </div>
              <div class="card-body">
                <canvas id="req-month-chart" style="min-height: 280px; height: 280px; max-height: 280px; max-width: 100%;"></canvas>
              </div>
            </div>

            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-list mr-1"></i> Recent requisitions</h3>
                <div class="card-tools">
                  <a href="{{ url('/branch/stc/requisitions') }}" class="btn btn-sm btn-primary">View all</a>
                </div>
              </div>
              <div class="card-body p-0">
                <table class="table table-striped table-sm mb-0">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Project</th>
                      <th>Supervisor</th>
                      <th>Status</th>
                      <th>Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($recent_requisitions as $row)
                      @php
                        $statusMap = [1 => ['Process', 'warning'], 2 => ['Passed', 'info'], 3 => ['Procurement', 'primary'], 4 => ['Completed', 'success']];
                        $st = $statusMap[(int) $row->status] ?? ['Unknown', 'secondary'];
                      @endphp
                      <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->project ?: '—' }}</td>
                        <td>{{ $row->supervisor ?: '—' }}</td>
                        <td><span class="badge badge-{{ $st[1] }}">{{ $st[0] }}</span></td>
                        <td>{{ $row->date ? \Carbon\Carbon::parse($row->date)->format('d M Y H:i') : '—' }}</td>
                      </tr>
                    @empty
                      <tr><td colspan="5" class="text-center text-muted">No requisitions found</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </section>

          <section class="col-lg-5 connectedSortable">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i> Requisition status</h3>
              </div>
              <div class="card-body">
                <canvas id="req-status-chart" style="min-height: 240px; height: 240px; max-height: 240px; max-width: 100%;"></canvas>
              </div>
            </div>

            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-users mr-1"></i> Users by type</h3>
              </div>
              <div class="card-body p-0">
                <table class="table table-sm mb-0">
                  <tbody>
                    @foreach($user_counts as $u)
                      <tr>
                        <td><a href="{{ $u['url'] }}">{{ $u['label'] }}</a></td>
                        <td class="text-right font-weight-bold">{{ number_format($u['count']) }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>

            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-boxes mr-1"></i> PO Adhoc status</h3>
              </div>
              <div class="card-body p-0">
                <table class="table table-sm mb-0">
                  <tbody>
                    @foreach($adhoc_status as $s)
                      <tr>
                        <td>{{ $s['label'] }}</td>
                        <td class="text-right font-weight-bold">{{ number_format($s['count']) }}</td>
                      </tr>
                    @endforeach
                    <tr>
                      <td>Tools</td>
                      <td class="text-right"><a href="{{ url('/branch/stc/tooltracker') }}">{{ number_format($counts['tools']) }}</a></td>
                    </tr>
                    <tr>
                      <td>GLD challans</td>
                      <td class="text-right"><a href="{{ url('/branch/stc/gld') }}">{{ number_format($counts['gld']) }}</a></td>
                    </tr>
                    <tr>
                      <td>Inventory rows</td>
                      <td class="text-right"><a href="{{ url('/master/inventory') }}">{{ number_format($counts['inventory']) }}</a></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </section>
        </div>
      </div>
    </section>
  </div>

  @include('layouts.footer')

  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>
@include('layouts.foot')
<script>
  (function () {
    var monthLabels = @json($monthly_requisitions['labels']);
    var monthValues = @json($monthly_requisitions['values']);
    var statusLabels = @json(array_column($req_status, 'label'));
    var statusValues = @json(array_column($req_status, 'count'));

    var monthCanvas = document.getElementById('req-month-chart');
    if (monthCanvas && typeof Chart !== 'undefined') {
      new Chart(monthCanvas.getContext('2d'), {
        type: 'line',
        data: {
          labels: monthLabels,
          datasets: [{
            label: 'Requisitions',
            data: monthValues,
            backgroundColor: 'rgba(60,141,188,0.2)',
            borderColor: 'rgba(60,141,188,1)',
            pointRadius: 3,
            fill: true
          }]
        },
        options: {
          maintainAspectRatio: false,
          legend: { display: false },
          scales: {
            yAxes: [{ ticks: { beginAtZero: true } }]
          }
        }
      });
    }

    var statusCanvas = document.getElementById('req-status-chart');
    if (statusCanvas && typeof Chart !== 'undefined') {
      new Chart(statusCanvas.getContext('2d'), {
        type: 'doughnut',
        data: {
          labels: statusLabels,
          datasets: [{
            data: statusValues,
            backgroundColor: ['#ffc107', '#17a2b8', '#007bff', '#28a745']
          }]
        },
        options: {
          maintainAspectRatio: false,
          legend: { position: 'bottom' }
        }
      });
    }
  })();
</script>
</body>
</html>
