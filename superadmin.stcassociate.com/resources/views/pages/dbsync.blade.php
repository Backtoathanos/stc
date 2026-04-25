<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>STC Associates | {{ !empty($page_title) ? $page_title : '' }}</title>
  @include('layouts.head')
  <style>
    .dbsync-pre { white-space: pre-wrap; }
    .dbsync-table-wrap { overflow-x: auto; }
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
            <h1 class="m-0">DB Sync</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">DB Sync</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12"><p>@include('layouts._message')</p></div>
        </div>

        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Sync server tables to local</h3>
          </div>
          <div class="card-body">
            <div class="alert alert-warning">
              This will <b>truncate local tables</b> and re-copy data from the <b>server</b> connection.
            </div>
            <form method="POST" action="{{ url('/db-sync/sync') }}">
              @csrf
              <button type="submit" class="btn btn-danger" onclick="return confirm('This will TRUNCATE local tables. Continue?')">
                Sync Now
              </button>
            </form>
          </div>
        </div>

        <div class="card card-secondary">
          <div class="card-header">
            <h3 class="card-title">Run SQL on server (to fix missing columns)</h3>
          </div>
          <div class="card-body">
            @if(!empty($queryResult))
              <div class="alert {{ !empty($queryResult['ok']) ? 'alert-success' : 'alert-danger' }}">
                <div><b>{{ $queryResult['message'] ?? '' }}</b></div>
              </div>
            @endif

            <form method="POST" action="{{ url('/db-sync/run-server-query') }}">
              @csrf
              <div class="form-group">
                <label>SQL (Allowed: ALTER TABLE / CREATE TABLE / CREATE INDEX / DROP INDEX)</label>
                <textarea class="form-control" name="sql" rows="6" placeholder="ALTER TABLE ...">{{ !empty($queryResult['sql']) ? $queryResult['sql'] : '' }}</textarea>
              </div>
              <button type="submit" class="btn btn-primary" onclick="return confirm('Run this query on SERVER database?')">Run on Server</button>
            </form>
          </div>
        </div>

        @if(!empty($report))
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">Last sync report</h3>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-4"><b>Started</b>: {{ $report['started_at'] ?? '' }}</div>
                <div class="col-md-4"><b>Ended</b>: {{ $report['ended_at'] ?? '' }}</div>
                <div class="col-md-4"><b>Duration</b>: {{ $report['duration_ms'] ?? '' }} ms</div>
              </div>

              @if(!empty($report['errors']))
                <div class="alert alert-danger mt-3">
                  <b>Errors</b>
                  <div class="dbsync-pre">{{ implode("\n", (array)$report['errors']) }}</div>
                </div>
              @endif

              <div class="dbsync-table-wrap mt-3">
                <table class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Table</th>
                      <th>Status</th>
                      <th class="text-right">Rows copied</th>
                      <th class="text-right">Time (ms)</th>
                      <th>Reason</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach(($report['tables'] ?? []) as $t)
                      <tr>
                        <td>{{ $t['table'] ?? '' }}</td>
                        <td>{{ $t['status'] ?? '' }}</td>
                        <td class="text-right">{{ $t['rows_copied'] ?? 0 }}</td>
                        <td class="text-right">{{ $t['ms'] ?? 0 }}</td>
                        <td>{{ $t['reason'] ?? '' }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div class="card card-warning">
            <div class="card-header">
              <h3 class="card-title">Column differences</h3>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <h5>Missing on local (exists on server)</h5>
                  <div class="dbsync-table-wrap">
                    <table class="table table-bordered table-sm">
                      <thead><tr><th>Table</th><th>Column</th></tr></thead>
                      <tbody>
                        @forelse(($report['missing_columns_on_local'] ?? []) as $r)
                          <tr><td>{{ $r['table'] }}</td><td>{{ $r['column'] }}</td></tr>
                        @empty
                          <tr><td colspan="2" class="text-muted">No missing columns on local.</td></tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="col-md-6">
                  <h5>Missing on server (exists on local)</h5>
                  <div class="dbsync-table-wrap">
                    <table class="table table-bordered table-sm">
                      <thead><tr><th>Table</th><th>Column</th></tr></thead>
                      <tbody>
                        @forelse(($report['missing_columns_on_server'] ?? []) as $r)
                          <tr><td>{{ $r['table'] }}</td><td>{{ $r['column'] }}</td></tr>
                        @empty
                          <tr><td colspan="2" class="text-muted">No missing columns on server.</td></tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        @endif

      </div>
    </section>
  </div>

  @include('layouts.footer')
  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

@include('layouts.foot')
</body>
</html>

