<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>STC Payroll | {{!empty($page_title) ? $page_title : 'Dashboard'}}</title>
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
            <h1 class="m-0">{{!empty($page_title) ? $page_title : 'Dashboard'}}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/stc/stc_payroll/">Home</a></li>
              @if(!empty($breadcrumb))
                @foreach($breadcrumb as $item)
                  <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}">
                    @if(!$loop->last)
                      <a href="{{ $item['url'] ?? '#' }}">{{ $item['title'] }}</a>
                    @else
                      {{ $item['title'] }}
                    @endif
                  </li>
                @endforeach
              @endif
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        @yield('content')
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
  <footer class="main-footer">
    <div class="row">
        <div class="col-md-12">
            <div class="copyright text-center">© Copyright {{ date('Y')}} STC Associates</div>
        </div>
    </div>
  </footer>

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

