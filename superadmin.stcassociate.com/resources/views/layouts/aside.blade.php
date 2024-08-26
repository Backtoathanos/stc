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
                Admin Dashboard
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link @if (Request::segment(2) == 'electronicsbranch') active @elseif(Request::segment(2) == 'groceriesbranch') active @elseif(Request::segment(2) == 'managerbranch') active @elseif(Request::segment(2) == 'schoolbranch') active @elseif(Request::segment(2) == 'sitebranch') active @elseif(Request::segment(2) == 'adminbranch') active @elseif(Request::segment(2) == 'tradingbranch') active @elseif(Request::segment(2) == 'uadminbranch') active @endif">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Branch
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link @if(Request::segment(2) == 'school') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>School
                  <i class="fas fa-angle-left right"></i></p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ url('/branch/school/monthlyclosing') }}" class="nav-link @if(Request::segment(3) == 'monthlyclosing') active @endif">
                      <i class="far fa-arrow-alt-circle-right nav-icon"></i>
                      <p>Monthly Closing</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ url('/branch/school/fee') }}" class="nav-link @if(Request::segment(3) == 'fee') active @endif">
                      <i class="far fa-arrow-alt-circle-right nav-icon"></i>
                      <p>Fee</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ url('/branch/school/canteen') }}" class="nav-link @if(Request::segment(3) == 'canteen') active @endif">
                      <i class="far fa-arrow-alt-circle-right nav-icon"></i>
                      <p>Canteen</p>
                    </a>
                  </li>
                </ul>
              </li>
              <li class="nav-item">
                <a href="javascript:void(0)" class="nav-link @if(Request::segment(2) == 'stc') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>STC
                  <i class="fas fa-angle-left right"></i></p>
                </a>
                <ul class="nav nav-treeview">
                  <li class="nav-item">
                    <a href="{{ url('/branch/stc/projects') }}" class="nav-link @if(Request::segment(3) == 'projects') active @endif">
                      <i class="far fa-arrow-alt-circle-right nav-icon"></i>
                      <p>Projects</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ url('/branch/stc/requisition') }}" class="nav-link @if(Request::segment(3) == 'requisition') active @endif">
                      <i class="far fa-arrow-alt-circle-right nav-icon"></i>
                      <p>Requisition</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ url('/branch/stc/std') }}" class="nav-link @if(Request::segment(3) == 'std') active @endif">
                      <i class="far fa-arrow-alt-circle-right nav-icon"></i>
                      <p>Status Down List</p>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ url('/branch/stc/canteen') }}" class="nav-link @if(Request::segment(3) == 'canteen') active @endif">
                      <i class="far fa-arrow-alt-circle-right nav-icon"></i>
                      <p>Canteen</p>
                    </a>
                  </li>
                </ul>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a 
              href="javascript:void(0)" 
              class="nav-link 
                @if(Request::segment(2) == 'city') active 
                @elseif(Request::segment(2) == 'state') active 
                @elseif(Request::segment(2) == 'category') active 
                @elseif(Request::segment(2) == 'subcategory') active 
                @elseif(Request::segment(2) == 'brand') active 
                @endif
              ">
              <i class="nav-icon fas fa-key"></i>
              <p>
                Master
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="{{ url('/master/city') }}" class="nav-link @if(Request::segment(2) == 'city') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>City</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/master/state') }}" class="nav-link @if(Request::segment(2) == 'state') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>State</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/master/category') }}" class="nav-link @if(Request::segment(2) == 'category') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Category</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/master/subcategory') }}" class="nav-link @if(Request::segment(2) == 'subcategory') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Sub Category</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/master/brand') }}" class="nav-link @if(Request::segment(2) == 'brand') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Brand</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/master/rack') }}" class="nav-link @if(Request::segment(2) == 'rack') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Rack</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/master/inventory') }}" class="nav-link @if(Request::segment(2) == 'inventory') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Inventory</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/master/product') }}" class="nav-link @if(Request::segment(2) == 'product') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Product</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/master/merchant') }}" class="nav-link @if(Request::segment(2) == 'merchant') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Merchant</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/master/brand') }}" class="nav-link @if(Request::segment(2) == 'customer') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Customer</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="javascript:void(0)" 
              class="nav-link @if(Request::segment(2) == 'tradingusers') active @elseif(Request::segment(2) == 'electronicsusers') active @elseif(Request::segment(2) == 'groceriesusers') active @elseif(Request::segment(2) == 'managerusers') active @elseif(Request::segment(2) == 'schoolusers') active @elseif(Request::segment(2) == 'siteusers') active @elseif(Request::segment(2) == 'admin') active @elseif(Request::segment(2) == 'uadminusers') active @endif ">
              <i class="nav-icon fas fa-user"></i>
              <p>
                Users
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
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
                <a href="{{ url('/users/managerusers') }}" class="nav-link @if(Request::segment(2) == 'managerusers') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manager</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/users/schoolusers') }}" class="nav-link @if(Request::segment(2) == 'schoolusers') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>School</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/users/siteusers') }}" class="nav-link @if(Request::segment(2) == 'siteusers') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Site Users</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/users/admin') }}" class="nav-link @if(Request::segment(2) == 'admin') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Super Admin</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/users/tradingusers') }}" class="nav-link @if(Request::segment(2) == 'tradingusers') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Trading</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ url('/users/uadminusers') }}" class="nav-link @if(Request::segment(2) == 'uadminusers') active @endif">
                  <i class="far fa-circle nav-icon"></i>
                  <p>User Administration</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="https://stcassociate.com/templates/AdminLTE-3.1.0/index.html" class="nav-link">
              <i class="nav-icon fas fa-dashboard"></i>
              <p>
                Template
              </p>
            </a>
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