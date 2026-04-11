<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>STC Associates | {{!empty($page_title) ? $page_title : ''}}</title>
  @include('layouts.head')
  <style>
    .suggestion-wrapper { position: relative; }
    .suggestion-list {
      position: absolute;
      top: 100%;
      left: 0;
      right: 0;
      z-index: 1051;
      max-height: 220px;
      overflow-y: auto;
      background: #fff;
      border: 1px solid #ced4da;
      border-top: none;
      list-style: none;
      margin: 0;
      padding: 0;
      display: none;
    }
    .suggestion-list li {
      padding: 8px 12px;
      cursor: pointer;
    }
    .suggestion-list li:hover { background: #f4f6f9; }
  </style>
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
            <h1 class="m-0">Trading Users</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="#">User</a></li>
              <li class="breadcrumb-item"><a href="{{url('/users/tradingusers')}}">{{!empty($page_title) ? $page_title : ''}}</a></li>
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
                    <textarea class="form-control" id="address" name="address" value="{{old('address')}}" placeholder="Enter address" required></textarea>
                  </div>
                  <div class="form-group">
                    <label for="city">City</label>
                    <select class="form-control" id="city" name="city">
                        <option value="NA">Select</option>
                        @foreach($getRecordCity as $city)
                          <option value="{{$city->stc_city_id}}">{{$city->stc_city_name}}</option>
                        @endforeach
                    </select>
                  </div>
                  @php
                    $tradingLocationValAdd = old('branch', '');
                    $tradingBranchListAdd = $tradingBranchLocations ?? [];
                    if ($tradingLocationValAdd !== '' && $tradingLocationValAdd !== null && !in_array((string) $tradingLocationValAdd, $tradingBranchListAdd, true)) {
                      $tradingBranchListAdd = array_values(array_merge([(string) $tradingLocationValAdd], $tradingBranchListAdd));
                    }
                    natcasesort($tradingBranchListAdd);
                    $tradingBranchListAdd = array_values($tradingBranchListAdd);
                  @endphp
                  <div class="form-group suggestion-wrapper" id="trading-branch-location-wrapper">
                    <label for="branch-input">Branch / location</label>
                    <input
                      type="text"
                      class="form-control suggestion-input"
                      id="branch-input"
                      name="branch"
                      autocomplete="off"
                      placeholder="Type to search or enter a branch name"
                      value="{{ $tradingLocationValAdd }}"
                    />
                    <ul class="suggestion-list">
                      @foreach ($tradingBranchListAdd as $bloc)
                        <li data-value="{{ e($bloc) }}">{{ $bloc }}</li>
                      @endforeach
                    </ul>
                    <small class="form-text text-muted">Choose from list or type a name; should match branches used in shop allocations.</small>
                  </div>
                  <div class="form-group">
                    <label for="state">State</label>
                    <select class="form-control" id="state" name="state">
                        <option value="NA">Select</option>
                        @foreach($getRecordState as $state)
                          <option value="{{$state->stc_state_id}}">{{$state->stc_state_name}}</option>
                        @endforeach
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="pincode">Pincode</label>
                    <input type="number" class="form-control" id="pincode" name="pincode" value="{{old('pincode')}}" placeholder="Enter pincode" required>
                  </div>
                  <div class="form-group">
                    <label for="passowrd">Password</label>
                    <input type="password" class="form-control" id="passowrd" name="password" value="{{old('password')}}" placeholder="Password" required>
                  </div>
                  <div class="form-group">
                    <label for="status">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option {{ (old('status')==1) ? 'selected' : '' }} value="1">Active</option>
                        <option {{ (old('status')==0) ? 'selected' : '' }} value="0">In-active</option>
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
  <script>
    $(function () {
      function bindTradingBranchSuggestion(wrapper) {
        var input = wrapper.find('.suggestion-input');
        var list = wrapper.find('.suggestion-list');
        input.on('focus keyup', function () {
          var term = $(this).val().toLowerCase();
          var hasVisible = false;
          list.children('li').each(function () {
            var text = $(this).text().toLowerCase();
            if (term === '' || text.indexOf(term) !== -1) {
              $(this).show();
              hasVisible = true;
            } else {
              $(this).hide();
            }
          });
          list.toggle(hasVisible);
        });
        list.on('mousedown', 'li', function (e) {
          e.preventDefault();
          input.val($(this).data('value'));
          list.hide();
        });
        $(document).on('click.tradingBranchSuggest', function (e) {
          if (!wrapper.is(e.target) && wrapper.has(e.target).length === 0) {
            list.hide();
          }
        });
      }
      bindTradingBranchSuggestion($('#trading-branch-location-wrapper'));
    });
  </script>
</body>
</html>
