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
            <h1 class="m-0">Manager Users</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="#">User</a></li>
              <li class="breadcrumb-item"><a href="{{url('/users/managerusers')}}">{{!empty($page_title) ? $page_title : ''}}</a></li>
              <li class="breadcrumb-item active"><a href="#">Add</a></li>
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
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{old('name')}}" placeholder="Enter name" required>
                  </div>
                  <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{old('email')}}" placeholder="Enter email" required>
                    <div style="color:red;">{{ $errors->first('email') }}</div>
                  </div>
                  <div class="form-group">
                    <label for="contact">Contact</label>
                    <input type="text" class="form-control" id="contact" name="contact" value="{{old('contact')}}" placeholder="Enter contact" required>
                    <div style="color:red;">{{ $errors->first('contact') }}</div>
                  </div>
                  <div class="form-group">
                    <label for="address">Address</label>
                    <textarea class="form-control" id="address" name="address" placeholder="Enter address" required>{{old('address')}}</textarea>
                  </div>
                  <div class="form-group">
                    <label for="city">City</label>
                    <select class="form-control" id="city" name="city">
                        <option value="NA">Select</option>
                        @foreach($getRecordCity as $city)
                          @if ($city->stc_city_id == old('city'))
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
                          @if ($state->stc_state_id == old('state'))
                              <option value="{{ $state->stc_state_id }}" selected>{{ $state->stc_state_name }}</option>
                          @else
                              <option value="{{ $state->stc_state_id }}">{{ $state->stc_state_name }}</option>
                          @endif
                        @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="pincode">Pincode</label>
                    <input type="text" class="form-control" id="pincode" name="pincode" value="{{old('pincode')}}" placeholder="Enter pincode" required>
                  </div>
                  <div class="form-group">
                    <label for="userid">User Id</label>
                    <input type="text" class="form-control" id="userid" name="userid" value="{{old('userid')}}" placeholder="Enter userid" required>
                  </div>
                  <div class="form-group">
                    <label for="passowrd">Password</label>
                    <input type="password" class="form-control" id="passowrd" name="password" value="{{old('password')}}" placeholder="Password">
                  </div>
                  <div class="form-group">
                    <label for="role">Role</label>
                    <select class="form-control" id="role" name="role">
                        <option {{ (old('role')==1) ? 'selected' : '' }} value="1">Manger</option>
                        <option {{ (old('role')==2) ? 'selected' : '' }} value="2">Procurement</option>
                        <option {{ (old('role')==3) ? 'selected' : '' }} value="3">SDL</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option {{ (old('status')==0) ? 'selected' : '' }} value="0">In-active</option>
                        <option {{ (old('status')==1) ? 'selected' : '' }} value="1">Active</option>
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
