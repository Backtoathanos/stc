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
      <a href="/stc/stc_payroll/master/employees" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
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
      <a href="/stc/stc_payroll/master/employees" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
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
      <a href="/stc/stc_payroll/master/sites" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
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
      <a href="/stc/stc_payroll/master/departments" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->
</div>
<!-- /.row -->

<!-- Main row -->
<div class="row">
  <!-- Left col -->
  <section class="col-lg-12 connectedSortable">
    <!-- Custom tabs (Charts with tabs)-->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-chart-pie mr-1"></i>
          Employee Overview
        </h3>
      </div><!-- /.card-header -->
      <div class="card-body">
        <div class="tab-content p-0">
          <!-- Welcome Message -->
          <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;">
            <div class="d-flex align-items-center justify-content-center" style="height: 100%;">
              <div class="text-center">
                <h2>Welcome to STC Payroll System</h2>
                <p class="text-muted">Manage your payroll efficiently</p>
              </div>
            </div>
          </div>
        </div>
      </div><!-- /.card-body -->
    </div>
    <!-- /.card -->
  </section>
  <!-- /.Left col -->
</div>
<!-- /.row (main row) -->
@endsection

