@php
  $baseUrl = '/stc/stc_payroll';
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
          @php
            $user = auth()->user();
            $isRoot = $user && $user->email === 'root@stcassociate.com';
            $hasSitesView = $user && ($user->hasPermission('master.sites.view') || $isRoot);
            $hasDepartmentsView = $user && ($user->hasPermission('master.departments.view') || $isRoot);
            $hasDesignationsView = $user && ($user->hasPermission('master.designations.view') || $isRoot);
            $hasGangsView = $user && ($user->hasPermission('master.gangs.view') || $isRoot);
            $hasEmployeesView = $user && ($user->hasPermission('master.employees.view') || $isRoot);
            $hasMasterAccess = $hasSitesView || $hasDepartmentsView || $hasDesignationsView || $hasGangsView || $hasEmployeesView;
            $hasPayrollView = $user && ($user->hasPermission('transaction.payroll.view') || $isRoot);
            $hasReportsView = $user && ($user->hasPermission('reports.employee.view') || $isRoot);
          @endphp
          @if($hasMasterAccess)
          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link @if(Request::segment(1) == 'master') active @endif">
              <i class="nav-icon fas fa-key"></i>
              <p>
                Master
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @if($hasSitesView)
              <li class="nav-item">
                <a href="{{ $baseUrl }}/master/sites" class="nav-link @if(Request::segment(2) == 'sites') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sites</p>
                </a>
              </li>
              @endif
              @if($hasDepartmentsView)
              <li class="nav-item">
                <a href="{{ $baseUrl }}/master/departments" class="nav-link @if(Request::segment(2) == 'departments') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Departments</p>
                </a>
              </li>
              @endif
              @if($hasDesignationsView)
              <li class="nav-item">
                <a href="{{ $baseUrl }}/master/designations" class="nav-link @if(Request::segment(2) == 'designations') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Designations</p>
                </a>
              </li>
              @endif
              @if($hasGangsView)
              <li class="nav-item">
                <a href="{{ $baseUrl }}/master/gangs" class="nav-link @if(Request::segment(2) == 'gangs') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Gangs</p>
                </a>
              </li>
              @endif
              @if($hasEmployeesView)
              <li class="nav-item">
                <a href="{{ $baseUrl }}/master/employees" class="nav-link @if(Request::segment(2) == 'employees') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Employees</p>
                </a>
              </li>
              @endif
            </ul>
          </li>
          @endif
          @if($hasPayrollView)
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
              <li class="nav-item">
                <a href="{{ $baseUrl }}/transaction/attendance" class="nav-link @if(Request::segment(2) == 'attendance') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Attendance</p>
                </a>
              </li>
            </ul>
          </li>
          @endif
          @if($hasReportsView)
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
          @endif
          @php
            $user = auth()->user();
            $hasAdminAccess = $user && ($user->hasPermission('admin.users.view') || $user->email === 'root@stcassociate.com');
          @endphp
          @if($hasAdminAccess)
          <li class="nav-item">
            <a href="{{ $baseUrl }}/admin/users" class="nav-link @if(Request::segment(1) == 'admin') active @endif">
              <i class="nav-icon fas fa-user-shield"></i>
              <p>Admin</p>
            </a>
          </li>
          @endif
          <li class="nav-item">
            <a href="{{ $baseUrl }}/calendar" class="nav-link @if(Request::segment(1) == 'calendar') active @endif">
              <i class="nav-icon fas fa-calendar"></i>
              <p>Calendar</p>
            </a>
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

