@php
  $baseUrl = '/stc/stc_payroll/public';
@endphp
<!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <div class="brand-link text-center">
      <span class="brand-text font-weight-light">STC Payroll</span>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="{{ $baseUrl }}" class="nav-link @if(Request::segment(1) == '' || Request::segment(1) == 'home') active @endif">
              <i class="nav-icon fas fa-home"></i>
              <p>Home</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link @if(Request::segment(1) == 'master') active @endif">
              <i class="nav-icon fas fa-key"></i>
              <p>
                Master
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ $baseUrl }}/master/sites" class="nav-link @if(Request::segment(2) == 'sites') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sites</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ $baseUrl }}/master/departments" class="nav-link @if(Request::segment(2) == 'departments') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Departments</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ $baseUrl }}/master/designations" class="nav-link @if(Request::segment(2) == 'designations') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Designations</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ $baseUrl }}/master/gangs" class="nav-link @if(Request::segment(2) == 'gangs') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Gangs</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ $baseUrl }}/master/employees" class="nav-link @if(Request::segment(2) == 'employees') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Employees</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link @if(Request::segment(1) == 'transaction') active @endif">
              <i class="nav-icon fas fa-file-invoice"></i>
              <p>
                Transaction
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ $baseUrl }}/transaction/payroll" class="nav-link @if(Request::segment(2) == 'payroll') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Payroll</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link @if(Request::segment(1) == 'reports') active @endif">
              <i class="nav-icon fas fa-chart-bar"></i>
              <p>
                Reports
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ $baseUrl }}/reports/employee" class="nav-link @if(Request::segment(2) == 'employee') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Employee Reports</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="{{ $baseUrl }}/settings" class="nav-link @if(Request::segment(1) == 'settings') active @endif">
              <i class="nav-icon fas fa-cog"></i>
              <p>Settings</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

