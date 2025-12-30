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
              <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
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

<!-- Company Selection Modal -->
<div class="modal fade" id="companySelectionModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="companySelectionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title" id="companySelectionModalLabel">
          <i class="fas fa-building mr-2"></i> Select Company
        </h5>
      </div>
      <div class="modal-body">
        <p class="text-muted mb-3">Please select a company to continue. You must select a company before accessing the system.</p>
        <div id="companySelectionList" class="list-group">
          <div class="text-center py-3">
            <i class="fas fa-spinner fa-spin fa-2x text-muted"></i>
            <p class="mt-2 text-muted">Loading companies...</p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <small class="text-muted mr-auto">You cannot proceed without selecting a company.</small>
      </div>
    </div>
  </div>
</div>

@include('layouts.foot')
</body>
</html>

