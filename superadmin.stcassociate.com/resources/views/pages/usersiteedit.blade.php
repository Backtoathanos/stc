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
  

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Site Users</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="#">User</a></li>
              <li class="breadcrumb-item"><a href="{{url('/users/siteusers')}}">{{!empty($page_title) ? $page_title : ''}}</a></li>
              <li class="breadcrumb-item active"><a href="#">Edit</a></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-12 col-12">
          <div class="card card-primary">
              <!-- /.card-header -->
              <!-- form start -->
               <form method="POST" action="" method="POST">
                    {{csrf_field()}}
                <div class="card-body">
                  <div class="form-group">
                    <label for="customer">Customer</label>
                    <select class="form-control" id="customer" name="customer">
                        <option {{ (old('customer')==2) ? 'selected' : '' }} value="2">GLOBAL AC SYSTEM JSR PVT LTD</option>
                        <option {{ (old('customer')==3) ? 'selected' : '' }} value="3">SARA Enterprises</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{old('name', $getRecord->stc_cust_pro_supervisor_fullname)}}" placeholder="Enter name" required>
                  </div>
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{old('email', $getRecord->stc_cust_pro_supervisor_email)}}" placeholder="Enter email" required>
                    <div style="color:red;">{{ $errors->first('email') }}</div>
                  </div>
                  <div class="form-group">
                    <label for="contact">Contact</label>
                    <input type="text" class="form-control" id="contact" name="contact" value="{{old('contact', $getRecord->stc_cust_pro_supervisor_contact)}}" placeholder="Enter contact" required>
                    <div style="color:red;">{{ $errors->first('contact') }}</div>
                  </div>
                  <div class="form-group">
                    <label for="whatsapp">Whatsapp</label>
                    <input type="text" class="form-control" id="whatsapp" name="whatsapp" value="{{old('whatsapp', $getRecord->stc_cust_pro_supervisor_whatsapp)}}" placeholder="Enter whatsapp" required>
                    <div style="color:red;">{{ $errors->first('whatsapp') }}</div>
                  </div>
                  <div class="form-group">
                    <label for="address">Address</label>
                    <textarea class="form-control" id="address" name="address" placeholder="Enter address" required>{{old('address', $getRecord->stc_cust_pro_supervisor_address)}}</textarea>
                  </div>
                  <div class="form-group">
                    <label for="city">City</label>
                    <select class="form-control" id="city" name="city">
                        <option value="NA">Select</option>
                        @foreach($getRecordCity as $city)
                          @if ($city->stc_city_id == old('city', $getRecord->stc_cust_pro_supervisor_cityid))
                              <option value="{{ $city->stc_city_id }}" selected>{{ $city->stc_city_name }}</option>
                          @else
                              <option value="{{ $city->stc_city_id }}">{{ $city->stc_city_name }}</option>
                          @endif
                        @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="state">State</label>
                    <select class="form-control" id="state" name="state">
                        <option value="NA">Select</option>
                        @foreach($getRecordState as $state)
                          @if ($state->stc_state_id == old('state', $getRecord->stc_cust_pro_supervisor_state_id))
                              <option value="{{ $state->stc_state_id }}" selected>{{ $state->stc_state_name }}</option>
                          @else
                              <option value="{{ $state->stc_state_id }}">{{ $state->stc_state_name }}</option>
                          @endif
                        @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="pincode">Pincode</label>
                    <input type="text" class="form-control" id="pincode" name="pincode" value="{{old('pincode', $getRecord->stc_cust_pro_supervisor_pincode)}}" placeholder="Enter pincode" required>
                  </div>
                  <div class="form-group">
                    <label for="passowrd">Password</label>
                    <input type="password" class="form-control" id="passowrd" name="password" value="{{old('password')}}" placeholder="Password">
                  </div>
                  <div class="form-group">
                    <label for="category">Category</label>
                    <select class="form-control" id="category" name="category">
                        <option {{ (old('category', $getRecord->stc_cust_pro_supervisor_category)=="Coordinator") ? 'selected' : '' }} >Coordinator</option>
                        <option {{ (old('category', $getRecord->stc_cust_pro_supervisor_category)=="Electrician") ? 'selected' : '' }} >Electrician</option>
                        <option {{ (old('category', $getRecord->stc_cust_pro_supervisor_category)=="Operator") ? 'selected' : '' }} >Operator</option>
                        <option {{ (old('category', $getRecord->stc_cust_pro_supervisor_category)=="Helper") ? 'selected' : '' }} >Helper</option>
                        <option {{ (old('category', $getRecord->stc_cust_pro_supervisor_category)=="Safety Supervisor") ? 'selected' : '' }} >Safety Supervisor</option>
                        <option {{ (old('category', $getRecord->stc_cust_pro_supervisor_category)=="Site Incharge") ? 'selected' : '' }} >Site Incharge</option>
                        <option {{ (old('category', $getRecord->stc_cust_pro_supervisor_category)=="Supervisor") ? 'selected' : '' }} >Supervisor</option>
                        <option {{ (old('category', $getRecord->stc_cust_pro_supervisor_category)=="Technician") ? 'selected' : '' }} >Technician</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option {{ (old('status', $getRecord->stc_cust_pro_supervisor_status)==0) ? 'selected' : '' }} value="0">In-active</option>
                        <option {{ (old('status', $getRecord->stc_cust_pro_supervisor_status)==1) ? 'selected' : '' }} value="1">Active</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="manager">Managers</label>
                    <select class="form-control" id="manager" name="manager">
                        @foreach($getRecordManger as $value)
                            @if($value->stc_agents_id==old('manager', $getRecord->stc_cust_pro_supervisor_created_by))
                                <option value="{{$value->stc_agents_id}}" selected>{{$value->stc_agents_name}}</option>
                            @else 
                                <option value="{{$value->stc_agents_id}}">{{$value->stc_agents_name}}</option>
                            @endif
                        @endforeach
                    </select>
                  </div>
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
 
  @include('layouts.footer')

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
  <!-- ./wrapper -->
  @include('layouts.foot')
</body>
</html>
