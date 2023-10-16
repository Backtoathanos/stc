<!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <div class="brand-link text-center">
      <!-- <img src="{{ asset('public/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
      <span class="brand-text font-weight-light">STC Associates</span>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ url('public/dist/img/avatar5.png')}}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name}}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="{{ url('/dashboard') }}" class="nav-link @if(Request::segment(1) == 'dashboard') active @endif">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link @if(Request::segment(2) == 'tradingusers') active @elseif(Request::segment(2) == 'electronicsusers') active @endif">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Users
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/users/admin') }}" class="nav-link @if(Request::segment(2) == 'admin') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Super Admin</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/users/electronicsusers') }}" class="nav-link @if(Request::segment(2) == 'electronicsusers') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Electronics</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/users/groceriesusers') }}" class="nav-link @if(Request::segment(2) == 'groceriesusers') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Groceries</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/users/schoolusers') }}" class="nav-link @if(Request::segment(2) == 'schoolusers') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>School</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/users/uadminusers') }}" class="nav-link @if(Request::segment(2) == 'uadminusers') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>User Administration</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/users/tradingusers') }}" class="nav-link @if(Request::segment(2) == 'tradingusers') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Trading</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="{{ url('/logout') }}" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>
                Logout
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>