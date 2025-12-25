@extends('layouts.header')

@section('content')
<div class="row">
  <!-- Company Card -->
  <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
    <div class="card h-100 shadow-sm" style="cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
      <div class="card-body text-center">
        <div class="mb-3">
          <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background-color: #d4edda;">
            <i class="fas fa-briefcase fa-2x" style="color: #28a745;"></i>
          </div>
        </div>
        <h5 class="card-title font-weight-bold mb-2" style="text-align: center; width: 100%;">Company</h5>
        <p class="card-text text-muted small">View and update company details</p>
      </div>
    </div>
  </div>

  <!-- Payroll Parameter Card -->
  <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
    <a href="{{ url('/settings/payroll-parameter') }}" style="text-decoration: none; color: inherit;">
      <div class="card h-100 shadow-sm" style="cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
        <div class="card-body text-center">
          <div class="mb-3">
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background-color: #e2d9f3;">
              <i class="fas fa-sliders-h fa-2x" style="color: #6f42c1;"></i>
            </div>
          </div>
          <h5 class="card-title font-weight-bold mb-2" style="text-align: center; width: 100%;">Payroll Parameter</h5>
          <p class="card-text text-muted small">Set Payroll Parameter, Used to generate payroll</p>
        </div>
      </div>
    </a>
  </div>

  <!-- NH Card -->
  <!-- <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
    <div class="card h-100 shadow-sm" style="cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
      <div class="card-body text-center">
        <div class="mb-3">
          <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background-color: #ffeaa7;">
            <i class="fas fa-bullseye fa-2x" style="color: #f39c12;"></i>
          </div>
        </div>
        <h5 class="card-title font-weight-bold mb-2" style="text-align: center; width: 100%;">NH</h5>
        <p class="card-text text-muted small">Set NH as per Site</p>
      </div>
    </div>
  </div> -->

  <!-- Tax Slave Card -->
  <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
    <div class="card h-100 shadow-sm" style="cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
      <div class="card-body text-center">
        <div class="mb-3">
          <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background-color: #ffeaa7;">
            <i class="fas fa-rupee-sign fa-2x" style="color: #f39c12;"></i>
          </div>
        </div>
        <h5 class="card-title font-weight-bold mb-2" style="text-align: center; width: 100%;">Tax Slave</h5>
        <p class="card-text text-muted small">Set Tax Slave as per Site</p>
      </div>
    </div>
  </div>

  <!-- Other Allowance Card -->
  <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
    <div class="card h-100 shadow-sm" style="cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
      <div class="card-body text-center">
        <div class="mb-3">
          <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background-color: #e9ecef;">
            <i class="fas fa-rupee-sign fa-2x" style="color: #6c757d;"></i>
          </div>
        </div>
        <h5 class="card-title font-weight-bold mb-2" style="text-align: center; width: 100%;">Other Allowance</h5>
        <p class="card-text text-muted small">Applicable in MR Site Wise</p>
      </div>
    </div>
  </div>

  <!-- Rate Card -->
  <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
    <a href="{{ url('/settings/rate') }}" style="text-decoration: none; color: inherit;">
      <div class="card h-100 shadow-sm" style="cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
        <div class="card-body text-center">
          <div class="mb-3">
            <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background-color: #fff3cd;">
              <i class="fas fa-dollar-sign fa-2x" style="color: #ffc107;"></i>
            </div>
          </div>
          <h5 class="card-title font-weight-bold mb-2" style="text-align: center; width: 100%;">Rate</h5>
          <p class="card-text text-muted small">Update rate category wise & site wise</p>
        </div>
      </div>
    </a>
  </div>

  <!-- Site Config Card -->
  <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
    <div class="card h-100 shadow-sm" style="cursor: pointer; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
      <div class="card-body text-center">
        <div class="mb-3">
          <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background-color: #fff3cd;">
            <i class="fas fa-cog fa-2x" style="color: #ffc107;"></i>
          </div>
        </div>
        <h5 class="card-title font-weight-bold mb-2" style="text-align: center; width: 100%;">Site Config</h5>
        <p class="card-text text-muted small">Site Wise enable/disable configration</p>
      </div>
    </div>
  </div>
</div>
@endsection
