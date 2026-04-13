<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>STC Associates | {{ !empty($page_title) ? $page_title : '' }}</title>
  @include('layouts.head')
  <style>
    .usershub-table-wrap { overflow-x: auto; }
    .nav-usershub .nav-link { font-size: 0.9rem; }
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
            <h1 class="m-0">Users</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">Users</li>
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

        <div class="card card-primary card-outline card-tabs">
          <div class="card-header p-0 pt-1 border-bottom-0">
            <ul class="nav nav-tabs nav-usershub" id="usershub-tabs" role="tablist">
              <li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#tab-electronics" role="tab">Electronics</a></li>
              <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-groceries" role="tab">Groceries</a></li>
              <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-manager" role="tab">Manager</a></li>
              <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-school" role="tab">School</a></li>
              <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-site" role="tab">Site</a></li>
              <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-trading" role="tab">Trading</a></li>
              <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#tab-uadmin" role="tab">User admin</a></li>
            </ul>
          </div>
          <div class="card-body">
            <div class="tab-content" id="usershub-tab-content">

              <div class="tab-pane fade show active" id="tab-electronics" role="tabpanel">
                <button type="button" class="btn btn-primary mb-3 btn-create-electronics">Create electronics user</button>
                <div class="usershub-table-wrap">
                  <table id="dt-electronics" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th class="text-center">Id</th><th>Name</th><th>Email</th><th>Contact</th><th>Address</th>
                        <th>City</th><th>State</th><th>Pincode</th><th>About</th><th>Password</th><th>Status</th><th>Role</th><th class="text-center">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($electronicsRecords as $value)
                      <tr>
                        <td class="text-center">{{ $value->stc_electronics_user_id }}</td>
                        <td>{{ $value->stc_electronics_user_fullName }}</td>
                        <td>{{ $value->stc_electronics_user_email }}</td>
                        <td>{{ $value->stc_electronics_user_contact }}</td>
                        <td>{{ $value->stc_electronics_user_address }}</td>
                        <td>{{ $value->stc_city_name }}</td>
                        <td>{{ $value->stc_state_name }}</td>
                        <td>{{ $value->stc_electronics_user_pincode }}</td>
                        <td>{{ $value->stc_electronics_user_aboutyou }}</td>
                        <td>{{ $value->stc_electronics_user_password }}</td>
                        <td class="text-center">{{ ($value->stc_electronics_user_status == 1) ? 'Active' : 'In-active' }}</td>
                        <td class="text-center">
                          @if($value->stc_electronics_user_for == 1) User
                          @elseif($value->stc_electronics_user_for == 2) Receptionist
                          @else Admin @endif
                        </td>
                        <td class="text-center text-nowrap">
                          <button type="button" class="btn btn-sm btn-info btn-edit-hub" data-type="electronics" data-id="{{ $value->stc_electronics_user_id }}"><i class="fas fa-edit"></i></button>
                          <button type="button" class="btn btn-sm btn-danger btn-delete-hub" data-type="electronics" data-id="{{ $value->stc_electronics_user_id }}"><i class="fas fa-trash"></i></button>
                          @php
                            $server = (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'localhost') ? 'http://localhost/stc' : 'https://stcassociate.com';
                            $enc = openssl_encrypt((string)$value->stc_electronics_user_id, 'AES-128-CTR', 'stc_associate_go', 0, '1234567891011121');
                          @endphp
                          <a href="{{ $server }}/usercredentials.php?user_id={{ urlencode($enc) }}&user_type=electronics" target="_blank" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-right"></i></a>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="tab-pane fade" id="tab-groceries" role="tabpanel">
                <button type="button" class="btn btn-primary mb-3 btn-create-groceries">Create groceries user</button>
                <div class="usershub-table-wrap">
                  <table id="dt-groceries" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th class="text-center">Id</th><th>Name</th><th>Email</th><th>Contact</th><th>Address</th>
                        <th>City</th><th>State</th><th>Pincode</th><th>Type</th><th>Password</th><th>Status</th><th class="text-center">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($groceriesRecords as $value)
                      <tr>
                        <td class="text-center">{{ $value->stc_groceries_user_id }}</td>
                        <td>{{ $value->stc_groceries_user_name }}</td>
                        <td>{{ $value->stc_groceries_user_email }}</td>
                        <td>{{ $value->stc_groceries_user_cont }}</td>
                        <td>{{ $value->stc_groceries_user_address }}</td>
                        <td>{{ $value->stc_city_name }}</td>
                        <td>{{ $value->stc_state_name }}</td>
                        <td>{{ $value->stc_groceries_user_pincode }}</td>
                        <td class="text-center">{{ ($value->stc_groceries_user_is_user == 1) ? 'User' : 'Checkit' }}</td>
                        <td>{{ $value->stc_groceries_user_password }}</td>
                        <td class="text-center">{{ ($value->stc_groceries_user_status == 1) ? 'Active' : 'In-active' }}</td>
                        <td class="text-center text-nowrap">
                          <button type="button" class="btn btn-sm btn-info btn-edit-hub" data-type="groceries" data-id="{{ $value->stc_groceries_user_id }}"><i class="fas fa-edit"></i></button>
                          <button type="button" class="btn btn-sm btn-danger btn-delete-hub" data-type="groceries" data-id="{{ $value->stc_groceries_user_id }}"><i class="fas fa-trash"></i></button>
                          @php
                            $server = (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'localhost') ? 'http://localhost/stc' : 'https://stcassociate.com';
                            $enc = openssl_encrypt((string)$value->stc_groceries_user_id, 'AES-128-CTR', 'stc_associate_go', 0, '1234567891011121');
                          @endphp
                          <a href="{{ $server }}/usercredentials.php?user_id={{ urlencode($enc) }}&user_type=groceries" target="_blank" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-right"></i></a>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="tab-pane fade" id="tab-manager" role="tabpanel">
                <button type="button" class="btn btn-primary mb-3 btn-create-manager">Create manager user</button>
                <div class="usershub-table-wrap">
                  <table id="dt-manager" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th class="text-center">Id</th><th>Name</th><th>Email</th><th>Contact</th><th>Address</th>
                        <th>City</th><th>State</th><th>Pincode</th><th>User Id</th><th>Password</th><th>Status</th><th>Role</th><th class="text-center">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($managerRecords as $value)
                      <tr>
                        <td class="text-center">{{ $value->stc_agents_id }}</td>
                        <td>{{ $value->stc_agents_name }}</td>
                        <td>{{ $value->stc_agents_email }}</td>
                        <td>{{ $value->stc_agents_contact }}</td>
                        <td>{{ $value->stc_agents_address }}</td>
                        <td>{{ $value->stc_city_name }}</td>
                        <td>{{ $value->stc_state_name }}</td>
                        <td>{{ $value->stc_agents_pincode }}</td>
                        <td>{{ $value->stc_agents_userid }}</td>
                        <td>{{ $value->stc_agents_pass }}</td>
                        <td class="text-center">{{ ($value->stc_agents_status == 1) ? 'Active' : 'In-active' }}</td>
                        <td class="text-center">
                          @if($value->stc_agents_role == 1) Manager
                          @elseif($value->stc_agents_role == 2) Procurement
                          @else SDL @endif
                        </td>
                        <td class="text-center text-nowrap">
                          <button type="button" class="btn btn-sm btn-info btn-edit-hub" data-type="manager" data-id="{{ $value->stc_agents_id }}"><i class="fas fa-edit"></i></button>
                          <button type="button" class="btn btn-sm btn-danger btn-delete-hub" data-type="manager" data-id="{{ $value->stc_agents_id }}"><i class="fas fa-trash"></i></button>
                          @php
                            $server = (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'localhost') ? 'http://localhost/stc' : 'https://stcassociate.com';
                            $enc = openssl_encrypt((string)$value->stc_agents_id, 'AES-128-CTR', 'stc_associate_go', 0, '1234567891011121');
                          @endphp
                          <a href="{{ $server }}/usercredentials.php?user_id={{ urlencode($enc) }}&user_type=manager" target="_blank" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-right"></i></a>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="tab-pane fade" id="tab-school" role="tabpanel">
                <button type="button" class="btn btn-primary mb-3 btn-create-school">Create school user</button>
                <div class="usershub-table-wrap">
                  <table id="dt-school" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th class="text-center">Id</th><th>Name</th><th>Email</th><th>Contact</th><th>Address</th>
                        <th>City</th><th>State</th><th>Pincode</th><th>About</th><th>Password</th><th>Status</th><th>Role</th><th class="text-center">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($schoolRecords as $value)
                      <tr>
                        <td class="text-center">{{ $value->stc_school_user_id }}</td>
                        <td>{{ $value->stc_school_user_fullName }}</td>
                        <td>{{ $value->stc_school_user_email }}</td>
                        <td>{{ $value->stc_school_user_contact }}</td>
                        <td>{{ $value->stc_school_user_address }}</td>
                        <td>{{ $value->stc_city_name }}</td>
                        <td>{{ $value->stc_state_name }}</td>
                        <td>{{ $value->stc_school_user_pincode }}</td>
                        <td>{{ $value->stc_school_user_aboutyou }}</td>
                        <td>{{ $value->stc_school_user_password }}</td>
                        <td class="text-center">{{ ($value->stc_school_user_status == 1) ? 'Active' : 'In-active' }}</td>
                        <td class="text-center">
                          @if($value->stc_school_user_for == 1) User
                          @elseif($value->stc_school_user_for == 2) Receptionist
                          @elseif($value->stc_school_user_for == 4) Teacher
                          @else TIC @endif
                        </td>
                        <td class="text-center text-nowrap">
                          <button type="button" class="btn btn-sm btn-info btn-edit-hub" data-type="school" data-id="{{ $value->stc_school_user_id }}"><i class="fas fa-edit"></i></button>
                          <button type="button" class="btn btn-sm btn-danger btn-delete-hub" data-type="school" data-id="{{ $value->stc_school_user_id }}"><i class="fas fa-trash"></i></button>
                          @php
                            $server = (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'localhost') ? 'http://localhost/stc' : 'https://stcassociate.com';
                            $enc = openssl_encrypt((string)$value->stc_school_user_id, 'AES-128-CTR', 'stc_associate_go', 0, '1234567891011121');
                          @endphp
                          <a href="{{ $server }}/usercredentials.php?user_id={{ urlencode($enc) }}&user_type=school" target="_blank" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-right"></i></a>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="tab-pane fade" id="tab-site" role="tabpanel">
                <button type="button" class="btn btn-primary mb-3 btn-create-site">Create site user</button>
                <div class="usershub-table-wrap">
                  <table id="dt-site" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th class="text-center">Id</th><th>Name</th><th>Email</th><th>Contact</th><th>Whatsapp</th><th>UID</th>
                        <th>Address</th><th>City</th><th>State</th><th>Pincode</th><th>Password</th><th>Status</th><th>Category</th><th>Token</th><th>Created by</th><th class="text-center">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($siteRecords as $value)
                      <tr>
                        <td class="text-center">{{ $value->stc_cust_pro_supervisor_id }}</td>
                        <td>{{ $value->stc_cust_pro_supervisor_fullname }}</td>
                        <td>{{ $value->stc_cust_pro_supervisor_email }}</td>
                        <td>{{ $value->stc_cust_pro_supervisor_contact }}</td>
                        <td>{{ $value->stc_cust_pro_supervisor_whatsapp }}</td>
                        <td>{{ $value->stc_cust_pro_supervisor_uid }}</td>
                        <td>{{ $value->stc_cust_pro_supervisor_address }}</td>
                        <td>{{ $value->stc_city_name }}</td>
                        <td>{{ $value->stc_state_name }}</td>
                        <td>{{ $value->stc_cust_pro_supervisor_pincode }}</td>
                        <td>{{ $value->stc_cust_pro_supervisor_password }}</td>
                        <td class="text-center">{{ ($value->stc_cust_pro_supervisor_status == 1) ? 'Active' : 'In-active' }}</td>
                        <td>{{ $value->stc_cust_pro_supervisor_category }}</td>
                        <td>{{ $value->stc_cust_pro_supervisor_token }}</td>
                        <td>{{ $value->stc_agents_name }}</td>
                        <td class="text-center text-nowrap">
                          <button type="button" class="btn btn-sm btn-info btn-edit-hub" data-type="site" data-id="{{ $value->stc_cust_pro_supervisor_id }}"><i class="fas fa-edit"></i></button>
                          <button type="button" class="btn btn-sm btn-danger btn-delete-hub" data-type="site" data-id="{{ $value->stc_cust_pro_supervisor_id }}"><i class="fas fa-trash"></i></button>
                          @php
                            $server = (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'localhost') ? 'http://localhost/stc' : 'https://stcassociate.com';
                            $enc = openssl_encrypt((string)$value->stc_cust_pro_supervisor_id, 'AES-128-CTR', 'stc_associate_go', 0, '1234567891011121');
                          @endphp
                          <a href="{{ $server }}/usercredentials.php?user_id={{ urlencode($enc) }}&user_type=siteuser" target="_blank" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-right"></i></a>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="tab-pane fade" id="tab-trading" role="tabpanel">
                <button type="button" class="btn btn-primary mb-3 btn-create-trading">Create trading user</button>
                <div class="usershub-table-wrap">
                  <table id="dt-trading" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th class="text-center">Id</th><th>Name</th><th>Email</th><th>Contact</th><th>Address</th>
                        <th>City</th><th>State</th><th>Pincode</th><th>Password</th><th>Status</th><th>Location</th><th class="text-center">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($tradingRecords as $value)
                      <tr>
                        <td class="text-center">{{ $value->stc_trading_user_id }}</td>
                        <td>{{ $value->stc_trading_user_name }}</td>
                        <td>{{ $value->stc_trading_user_email }}</td>
                        <td>{{ $value->stc_trading_user_cont }}</td>
                        <td>{{ $value->stc_trading_user_address }}</td>
                        <td>{{ $value->stc_city_name }}</td>
                        <td>{{ $value->stc_state_name }}</td>
                        <td>{{ $value->stc_trading_user_pincode }}</td>
                        <td>{{ $value->stc_trading_user_password }}</td>
                        <td class="text-center">{{ ($value->stc_trading_user_status == 1) ? 'Active' : 'In-active' }}</td>
                        <td>{{ $value->stc_trading_user_location ?? '' }}</td>
                        <td class="text-center text-nowrap">
                          <button type="button" class="btn btn-sm btn-info btn-edit-hub" data-type="trading" data-id="{{ $value->stc_trading_user_id }}"><i class="fas fa-edit"></i></button>
                          <button type="button" class="btn btn-sm btn-danger btn-delete-hub" data-type="trading" data-id="{{ $value->stc_trading_user_id }}"><i class="fas fa-trash"></i></button>
                          @php
                            $server = (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'localhost') ? 'http://localhost/stc' : 'https://stcassociate.com';
                            $enc = openssl_encrypt((string)$value->stc_trading_user_id, 'AES-128-CTR', 'stc_associate_go', 0, '1234567891011121');
                          @endphp
                          <a href="{{ $server }}/usercredentials.php?user_id={{ urlencode($enc) }}&user_type=trading" target="_blank" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-right"></i></a>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="tab-pane fade" id="tab-uadmin" role="tabpanel">
                <button type="button" class="btn btn-primary mb-3 btn-create-uadmin">Create user admin</button>
                <div class="usershub-table-wrap">
                  <table id="dt-uadmin" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th class="text-center">Id</th><th>Name</th><th>Email</th><th>Contact</th><th>Address</th>
                        <th>City</th><th>State</th><th>User Id</th><th>Password</th><th>Status</th><th>Role</th><th class="text-center">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($uadminRecords as $value)
                      <tr>
                        <td class="text-center">{{ $value->stc_user_id }}</td>
                        <td>{{ $value->stc_user_name }}</td>
                        <td>{{ $value->stc_user_email }}</td>
                        <td>
                          @if($value->stc_user_phone == $value->stc_user_phone_again) {{ $value->stc_user_phone }}
                          @else {{ $value->stc_user_phone }} / {{ $value->stc_user_phone_again }} @endif
                        </td>
                        <td>{{ $value->stc_user_address }}</td>
                        <td>{{ $value->stc_city_name }}</td>
                        <td>{{ $value->stc_state_name }}</td>
                        <td>{{ $value->stc_user_userid }}</td>
                        <td>{{ $value->stc_user_password }}</td>
                        <td class="text-center">{{ ($value->stc_user_status == 1) ? 'Active' : 'In-active' }}</td>
                        <td class="text-center">
                          @if($value->stc_user_role == 2) Report Access for school
                          @elseif($value->stc_user_role == 1) User
                          @elseif($value->stc_user_role == 9) Nausher
                          @elseif($value->stc_user_role == 6) Boss
                          @else Accountant @endif
                        </td>
                        <td class="text-center text-nowrap">
                          <button type="button" class="btn btn-sm btn-info btn-edit-hub" data-type="uadmin" data-id="{{ $value->stc_user_id }}"><i class="fas fa-edit"></i></button>
                          <button type="button" class="btn btn-sm btn-danger btn-delete-hub" data-type="uadmin" data-id="{{ $value->stc_user_id }}"><i class="fas fa-trash"></i></button>
                          @php
                            $server = (isset($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'localhost') ? 'http://localhost/stc' : 'https://stcassociate.com';
                            $enc = openssl_encrypt((string)$value->stc_user_id, 'AES-128-CTR', 'stc_associate_go', 0, '1234567891011121');
                          @endphp
                          <a href="{{ $server }}/usercredentials.php?user_id={{ urlencode($enc) }}&user_type=uadmin" target="_blank" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-right"></i></a>
                        </td>
                      </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  @include('layouts.footer')
  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

@include('pages.partials.usershub-modals')

@include('layouts.foot')
<script>
(function () {
  var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  var headers = {
    'X-CSRF-TOKEN': csrf,
    'Accept': 'application/json',
    'X-Requested-With': 'XMLHttpRequest'
  };

  var routes = {
    electronics: { create: @json(url('/users/electronicsusers/create')), edit: function (id) { return @json(url('/users/electronicsusers/edit')) + '/' + id; }, del: function (id) { return @json(url('/users/electronicsusers/delete')) + '/' + id; }, json: function (id) { return @json(url('/users/hub/record/electronics')) + '/' + id; } },
    trading: { create: @json(url('/users/tradingusers/create')), edit: function (id) { return @json(url('/users/tradingusers/edit')) + '/' + id; }, del: function (id) { return @json(url('/users/tradingusers/delete')) + '/' + id; }, json: function (id) { return @json(url('/users/hub/record/trading')) + '/' + id; } },
    groceries: { create: @json(url('/users/groceriesusers/create')), edit: function (id) { return @json(url('/users/groceriesusers/edit')) + '/' + id; }, del: function (id) { return @json(url('/users/groceriesusers/delete')) + '/' + id; }, json: function (id) { return @json(url('/users/hub/record/groceries')) + '/' + id; } },
    manager: { create: @json(url('/users/managerusers/create')), edit: function (id) { return @json(url('/users/managerusers/edit')) + '/' + id; }, del: function (id) { return @json(url('/users/managerusers/delete')) + '/' + id; }, json: function (id) { return @json(url('/users/hub/record/manager')) + '/' + id; } },
    school: { create: @json(url('/users/schoolusers/create')), edit: function (id) { return @json(url('/users/schoolusers/edit')) + '/' + id; }, del: function (id) { return @json(url('/users/schoolusers/delete')) + '/' + id; }, json: function (id) { return @json(url('/users/hub/record/school')) + '/' + id; } },
    site: { create: @json(url('/users/siteusers/create')), edit: function (id) { return @json(url('/users/siteusers/edit')) + '/' + id; }, del: function (id) { return @json(url('/users/siteusers/delete')) + '/' + id; }, json: function (id) { return @json(url('/users/hub/record/site')) + '/' + id; } },
    uadmin: { create: @json(url('/users/uadminusers/create')), edit: function (id) { return @json(url('/users/uadminusers/edit')) + '/' + id; }, del: function (id) { return @json(url('/users/uadminusers/delete')) + '/' + id; }, json: function (id) { return @json(url('/users/hub/record/uadmin')) + '/' + id; } }
  };

  function hideErrors(id) {
    var el = document.getElementById(id);
    if (el) { el.classList.add('d-none'); el.textContent = ''; }
  }
  function showErrors(id, data) {
    var el = document.getElementById(id);
    if (!el) return;
    var parts = [];
    if (data.errors) {
      Object.keys(data.errors).forEach(function (k) {
        (data.errors[k] || []).forEach(function (m) { parts.push(m); });
      });
    } else if (data.message) {
      parts.push(data.message);
    }
    el.textContent = parts.join(' ');
    el.classList.remove('d-none');
  }

  function bindAjaxForm(formId, errorBoxId) {
    var form = document.getElementById(formId);
    if (!form) return;
    form.addEventListener('submit', function (e) {
      e.preventDefault();
      hideErrors(errorBoxId);
      var fd = new FormData(form);
      fetch(form.getAttribute('action'), { method: 'POST', body: fd, headers: headers, credentials: 'same-origin' })
        .then(function (r) {
          return r.text().then(function (t) {
            var j = {};
            try { j = JSON.parse(t); } catch (e) { j = { message: t ? t.substring(0, 300) : 'Invalid response' }; }
            return { ok: r.ok, status: r.status, body: j };
          });
        })
        .then(function (res) {
          if (res.ok && res.body.success) {
            $(form).closest('.modal').modal('hide');
            window.location.reload();
            return;
          }
          showErrors(errorBoxId, res.body || {});
        })
        .catch(function () {
          showErrors(errorBoxId, { message: 'Request failed.' });
        });
    });
  }

  bindAjaxForm('form-electronics', 'form-electronics-errors');
  bindAjaxForm('form-trading', 'form-trading-errors');
  bindAjaxForm('form-groceries', 'form-groceries-errors');
  bindAjaxForm('form-manager', 'form-manager-errors');
  bindAjaxForm('form-school', 'form-school-errors');
  bindAjaxForm('form-site', 'form-site-errors');
  bindAjaxForm('form-uadmin', 'form-uadmin-errors');

  function clearElectronics() {
    ['el-name','el-email','el-contact','el-address','el-pincode','el-password','el-abtuser'].forEach(function (id) {
      var n = document.getElementById(id); if (n) n.value = '';
    });
    document.getElementById('el-role').value = '3';
    document.getElementById('el-status').value = '1';
  }
  function fillElectronics(d) {
    document.getElementById('el-name').value = d.stc_electronics_user_fullName || '';
    document.getElementById('el-email').value = d.stc_electronics_user_email || '';
    document.getElementById('el-contact').value = d.stc_electronics_user_contact || '';
    document.getElementById('el-address').value = d.stc_electronics_user_address || '';
    document.getElementById('el-city').value = String(d.stc_electronics_user_cityid || '');
    document.getElementById('el-state').value = String(d.stc_electronics_user_stateid || '');
    document.getElementById('el-pincode').value = d.stc_electronics_user_pincode || '';
    document.getElementById('el-password').value = '';
    document.getElementById('el-abtuser').value = d.stc_electronics_user_aboutyou || '';
    document.getElementById('el-role').value = String(d.stc_electronics_user_for || '1');
    document.getElementById('el-status').value = String(d.stc_electronics_user_status != null ? d.stc_electronics_user_status : '1');
  }

  document.querySelector('.btn-create-electronics').addEventListener('click', function () {
    hideErrors('form-electronics-errors');
    clearElectronics();
    document.getElementById('form-electronics').setAttribute('action', routes.electronics.create);
    $('#modal-electronics').modal('show');
  });

  function clearTrading() {
    ['tr-name','tr-email','tr-contact','tr-address','tr-pincode','tr-password','tr-branch'].forEach(function (id) {
      var n = document.getElementById(id); if (n) n.value = '';
    });
    document.getElementById('tr-status').value = '1';
  }
  function fillTrading(d) {
    document.getElementById('tr-name').value = d.stc_trading_user_name || '';
    document.getElementById('tr-email').value = d.stc_trading_user_email || '';
    document.getElementById('tr-contact').value = d.stc_trading_user_cont || '';
    document.getElementById('tr-address').value = d.stc_trading_user_address || '';
    document.getElementById('tr-city').value = String(d.stc_trading_user_city_id || '');
    document.getElementById('tr-state').value = String(d.stc_trading_user_state_id || '');
    document.getElementById('tr-branch').value = d.stc_trading_user_location || '';
    document.getElementById('tr-pincode').value = d.stc_trading_user_pincode || '';
    document.getElementById('tr-password').value = '';
    document.getElementById('tr-status').value = String(d.stc_trading_user_status != null ? d.stc_trading_user_status : '1');
  }
  document.querySelector('.btn-create-trading').addEventListener('click', function () {
    hideErrors('form-trading-errors');
    clearTrading();
    document.getElementById('form-trading').setAttribute('action', routes.trading.create);
    $('#modal-trading').modal('show');
  });

  function clearGroceries() {
    ['gr-name','gr-email','gr-contact','gr-address','gr-pincode','gr-password'].forEach(function (id) {
      var n = document.getElementById(id); if (n) n.value = '';
    });
    document.getElementById('gr-usertype').value = '1';
    document.getElementById('gr-status').value = '1';
  }
  function fillGroceries(d) {
    document.getElementById('gr-name').value = d.stc_groceries_user_name || '';
    document.getElementById('gr-email').value = d.stc_groceries_user_email || '';
    document.getElementById('gr-contact').value = d.stc_groceries_user_cont || '';
    document.getElementById('gr-address').value = d.stc_groceries_user_address || '';
    document.getElementById('gr-city').value = String(d.stc_groceries_user_city_id || '');
    document.getElementById('gr-state').value = String(d.stc_groceries_user_state_id || '');
    document.getElementById('gr-pincode').value = d.stc_groceries_user_pincode || '';
    document.getElementById('gr-password').value = '';
    document.getElementById('gr-usertype').value = String(d.stc_groceries_user_is_user != null ? d.stc_groceries_user_is_user : '1');
    document.getElementById('gr-status').value = String(d.stc_groceries_user_status != null ? d.stc_groceries_user_status : '1');
  }
  document.querySelector('.btn-create-groceries').addEventListener('click', function () {
    hideErrors('form-groceries-errors');
    clearGroceries();
    document.getElementById('form-groceries').setAttribute('action', routes.groceries.create);
    $('#modal-groceries').modal('show');
  });

  function clearManager() {
    ['mg-name','mg-email','mg-contact','mg-address','mg-pincode','mg-userid','mg-password'].forEach(function (id) {
      var n = document.getElementById(id); if (n) n.value = '';
    });
    document.getElementById('mg-role').value = '1';
    document.getElementById('mg-status').value = '1';
  }
  function fillManager(d) {
    document.getElementById('mg-name').value = d.stc_agents_name || '';
    document.getElementById('mg-email').value = d.stc_agents_email || '';
    document.getElementById('mg-contact').value = d.stc_agents_contact || '';
    document.getElementById('mg-address').value = d.stc_agents_address || '';
    document.getElementById('mg-city').value = String(d.stc_agents_city_id || '');
    document.getElementById('mg-state').value = String(d.stc_agents_state_id || '');
    document.getElementById('mg-pincode').value = d.stc_agents_pincode || '';
    document.getElementById('mg-userid').value = d.stc_agents_userid || '';
    document.getElementById('mg-password').value = '';
    document.getElementById('mg-role').value = String(d.stc_agents_role || '1');
    document.getElementById('mg-status').value = String(d.stc_agents_status != null ? d.stc_agents_status : '1');
  }
  document.querySelector('.btn-create-manager').addEventListener('click', function () {
    hideErrors('form-manager-errors');
    clearManager();
    document.getElementById('form-manager').setAttribute('action', routes.manager.create);
    $('#modal-manager').modal('show');
  });

  function clearSchool() {
    ['sc-name','sc-email','sc-contact','sc-address','sc-pincode','sc-password','sc-abtuser'].forEach(function (id) {
      var n = document.getElementById(id); if (n) n.value = '';
    });
    document.getElementById('sc-role').value = '4';
    document.getElementById('sc-status').value = '1';
  }
  function fillSchool(d) {
    document.getElementById('sc-name').value = d.stc_school_user_fullName || '';
    document.getElementById('sc-email').value = d.stc_school_user_email || '';
    document.getElementById('sc-contact').value = d.stc_school_user_contact || '';
    document.getElementById('sc-address').value = d.stc_school_user_address || '';
    document.getElementById('sc-city').value = String(d.stc_school_user_cityid || '');
    document.getElementById('sc-state').value = String(d.stc_school_user_stateid || '');
    document.getElementById('sc-pincode').value = d.stc_school_user_pincode || '';
    document.getElementById('sc-password').value = '';
    document.getElementById('sc-abtuser').value = d.stc_school_user_aboutyou || '';
    document.getElementById('sc-role').value = String(d.stc_school_user_for || '1');
    document.getElementById('sc-status').value = String(d.stc_school_user_status != null ? d.stc_school_user_status : '1');
  }
  document.querySelector('.btn-create-school').addEventListener('click', function () {
    hideErrors('form-school-errors');
    clearSchool();
    document.getElementById('form-school').setAttribute('action', routes.school.create);
    $('#modal-school').modal('show');
  });

  function clearSite() {
    ['si-name','si-email','si-contact','si-whatsapp','si-address','si-pincode','si-password'].forEach(function (id) {
      var n = document.getElementById(id); if (n) n.value = '';
    });
    document.getElementById('si-customer').value = '2';
    document.getElementById('si-status').value = '1';
    document.getElementById('si-category').selectedIndex = 0;
  }
  function fillSite(d) {
    document.getElementById('si-customer').value = String(d.stc_cust_pro_supervisor_cust_id || '2');
    document.getElementById('si-name').value = d.stc_cust_pro_supervisor_fullname || '';
    document.getElementById('si-email').value = d.stc_cust_pro_supervisor_email || '';
    document.getElementById('si-contact').value = d.stc_cust_pro_supervisor_contact || '';
    document.getElementById('si-whatsapp').value = d.stc_cust_pro_supervisor_whatsapp || '';
    document.getElementById('si-address').value = d.stc_cust_pro_supervisor_address || '';
    document.getElementById('si-city').value = String(d.stc_cust_pro_supervisor_cityid || '');
    document.getElementById('si-state').value = String(d.stc_cust_pro_supervisor_state_id || '');
    document.getElementById('si-pincode').value = d.stc_cust_pro_supervisor_pincode || '';
    document.getElementById('si-password').value = '';
    var cat = d.stc_cust_pro_supervisor_category || 'Coordinator';
    var sel = document.getElementById('si-category');
    for (var i = 0; i < sel.options.length; i++) {
      if (sel.options[i].value === cat || sel.options[i].text === cat) { sel.selectedIndex = i; break; }
    }
    document.getElementById('si-status').value = String(d.stc_cust_pro_supervisor_status != null ? d.stc_cust_pro_supervisor_status : '1');
    document.getElementById('si-manager').value = String(d.stc_cust_pro_supervisor_created_by || '');
  }
  document.querySelector('.btn-create-site').addEventListener('click', function () {
    hideErrors('form-site-errors');
    clearSite();
    document.getElementById('form-site').setAttribute('action', routes.site.create);
    $('#modal-site').modal('show');
  });

  function clearUadmin() {
    ['ua-name','ua-email','ua-contact','ua-contact2','ua-address','ua-userid','ua-password'].forEach(function (id) {
      var n = document.getElementById(id); if (n) n.value = '';
    });
    document.getElementById('ua-role').value = '1';
    document.getElementById('ua-status').value = '1';
  }
  function fillUadmin(d) {
    document.getElementById('ua-name').value = d.stc_user_name || '';
    document.getElementById('ua-email').value = d.stc_user_email || '';
    document.getElementById('ua-contact').value = d.stc_user_phone || '';
    document.getElementById('ua-contact2').value = d.stc_user_phone_again || '';
    document.getElementById('ua-address').value = d.stc_user_address || '';
    document.getElementById('ua-city').value = String(d.stc_user_city_id || '');
    document.getElementById('ua-state').value = String(d.stc_user_state_id || '');
    document.getElementById('ua-userid').value = d.stc_user_userid || '';
    document.getElementById('ua-password').value = '';
    document.getElementById('ua-role').value = String(d.stc_user_role != null ? d.stc_user_role : '1');
    document.getElementById('ua-status').value = String(d.stc_user_status != null ? d.stc_user_status : '1');
  }
  document.querySelector('.btn-create-uadmin').addEventListener('click', function () {
    hideErrors('form-uadmin-errors');
    clearUadmin();
    document.getElementById('form-uadmin').setAttribute('action', routes.uadmin.create);
    $('#modal-uadmin').modal('show');
  });

  document.querySelectorAll('.btn-edit-hub').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var type = btn.getAttribute('data-type');
      var id = btn.getAttribute('data-id');
      var r = routes[type];
      if (!r) return;
      fetch(r.json(id), { headers: headers, credentials: 'same-origin' })
        .then(function (res) { return res.json(); })
        .then(function (d) {
          if (type === 'electronics') {
            hideErrors('form-electronics-errors');
            fillElectronics(d);
            document.getElementById('form-electronics').setAttribute('action', r.edit(id));
            $('#modal-electronics').modal('show');
          } else if (type === 'trading') {
            hideErrors('form-trading-errors');
            fillTrading(d);
            document.getElementById('form-trading').setAttribute('action', r.edit(id));
            $('#modal-trading').modal('show');
          } else if (type === 'groceries') {
            hideErrors('form-groceries-errors');
            fillGroceries(d);
            document.getElementById('form-groceries').setAttribute('action', r.edit(id));
            $('#modal-groceries').modal('show');
          } else if (type === 'manager') {
            hideErrors('form-manager-errors');
            fillManager(d);
            document.getElementById('form-manager').setAttribute('action', r.edit(id));
            $('#modal-manager').modal('show');
          } else if (type === 'school') {
            hideErrors('form-school-errors');
            fillSchool(d);
            document.getElementById('form-school').setAttribute('action', r.edit(id));
            $('#modal-school').modal('show');
          } else if (type === 'site') {
            hideErrors('form-site-errors');
            fillSite(d);
            document.getElementById('form-site').setAttribute('action', r.edit(id));
            $('#modal-site').modal('show');
          } else if (type === 'uadmin') {
            hideErrors('form-uadmin-errors');
            fillUadmin(d);
            document.getElementById('form-uadmin').setAttribute('action', r.edit(id));
            $('#modal-uadmin').modal('show');
          }
        });
    });
  });

  document.querySelectorAll('.btn-delete-hub').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var type = btn.getAttribute('data-type');
      var id = btn.getAttribute('data-id');
      var r = routes[type];
      if (!r || !confirm('Delete this user?')) return;
      var fd = new FormData();
      fd.append('_token', csrf);
      fetch(r.del(id), { method: 'POST', body: fd, headers: headers, credentials: 'same-origin' })
        .then(function (res) { return res.text().then(function (t) { try { return JSON.parse(t); } catch (e) { return {}; } }); })
        .then(function (body) {
          if (body.success) window.location.reload();
          else alert(body.message || 'Delete failed');
        })
        .catch(function () { alert('Delete failed'); });
    });
  });

  var dtOpts = {
    responsive: true,
    lengthChange: false,
    autoWidth: true,
    pageLength: 25,
    buttons: ['copy', 'csv', 'excel', 'pdf', 'print', 'colvis']
  };
  ['#dt-electronics', '#dt-groceries', '#dt-manager', '#dt-school', '#dt-site', '#dt-trading', '#dt-uadmin'].forEach(function (sel) {
    if ($(sel).length) {
      $(sel).DataTable(dtOpts).buttons().container().appendTo(sel + '_wrapper .col-md-6:eq(0)');
    }
  });

  $('a[data-toggle="pill"]').on('shown.bs.tab', function () {
    $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
  });
})();
</script>
</body>
</html>
