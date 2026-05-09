<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>STC Associates | {{ !empty($page_title) ? $page_title : '' }}</title>
  @include('layouts.head')
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
            <h1 class="m-0">{{ !empty($page_title) ? $page_title : '' }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Dashboard</a></li>
              <li class="breadcrumb-item active">{{ !empty($page_title) ? $page_title : '' }}</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <p>@include('layouts._message')</p>
            @if (empty($cloud_upload_ready))
            <div class="alert alert-warning border-0 shadow-sm">
              <strong>Cloud upload:</strong> add R2 or S3 credentials to <code>.env</code>
              (<code>AWS_ACCESS_KEY_ID</code>, <code>AWS_SECRET_ACCESS_KEY</code>, <code>AWS_BUCKET</code>,
              <code>AWS_ENDPOINT</code> for R2, <code>AWS_URL</code> as your <em>public</em> base URL,
              <code>AWS_DEFAULT_REGION=auto</code>, <code>AWS_USE_PATH_STYLE_ENDPOINT=true</code> for Cloudflare R2).
              Free tier: <a href="https://developers.cloudflare.com/r2/pricing/" target="_blank" rel="noopener">Cloudflare R2</a>
              (S3-compatible, generous free storage).
            </div>
            @endif
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="card card-primary card-outline card-outline-tabs">
              <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="images-tab-nav" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="tab-products-link" data-toggle="pill" href="#tab-products" role="tab" aria-controls="tab-products" aria-selected="true">Products</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="tab-tbm-link" data-toggle="pill" href="#tab-tbm" role="tab" aria-controls="tab-tbm" aria-selected="false">TBM</a>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="images-tab-content">
                  <div class="tab-pane fade show active" id="tab-products" role="tabpanel" aria-labelledby="tab-products-link">
                    <div class="table-responsive">
                      <table id="images-products-table" class="table table-bordered table-striped table-hover mb-0" style="width:100%">
                        <thead>
                          <tr>
                            <th class="text-center">Product ID</th>
                            <th>Product name</th>
                            <th>Image name</th>
                            <th class="text-center">Action</th>
                          </tr>
                        </thead>
                        <tbody></tbody>
                      </table>
                    </div>
                  </div>
                  <div class="tab-pane fade" id="tab-tbm" role="tabpanel" aria-labelledby="tab-tbm-link">
                    <div class="table-responsive">
                      <table id="images-tbm-table" class="table table-bordered table-striped table-hover mb-0" style="width:100%">
                        <thead>
                          <tr>
                            <th class="text-center">TBM ID</th>
                            <th>TBM location</th>
                            <th>TBM image name</th>
                            <th class="text-center">Action</th>
                          </tr>
                        </thead>
                        <tbody></tbody>
                      </table>
                    </div>
                  </div>
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
@include('layouts.ajax_foot')

<script>
  $(function () {
    function swalToast(icon, title) {
      var Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3800 });
      Toast.fire({ icon: icon, title: title });
    }

    function ajaxErrMessage(xhr) {
      if (!xhr || !xhr.responseJSON) return 'Request failed';
      var j = xhr.responseJSON;
      if (j.message) return j.message;
      if (j.errors) {
        var parts = [];
        for (var k in j.errors) {
          if (Object.prototype.hasOwnProperty.call(j.errors, k)) {
            var arr = j.errors[k];
            for (var i = 0; i < arr.length; i++) parts.push(arr[i]);
          }
        }
        return parts.join(' ') || 'Validation error';
      }
      return 'Request failed';
    }

    $(document).on('click', '.js-migrate-product-cloud', function () {
      var btn = $(this);
      var id = btn.data('product-id');
      Swal.fire({
        title: 'Upload to cloud?',
        text: 'The file is sent to your R2/S3 bucket and stc_product_image is replaced with the public URL. Optional: local file can be deleted after upload (see CLOUD_DELETE_LOCAL_AFTER_UPLOAD).',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Upload',
        cancelButtonText: 'Cancel'
      }).then(function (result) {
        if (!result.value) return;
        btn.prop('disabled', true);
        $.ajax({
          url: "{{ url('/images/products/migrate-cloud') }}",
          method: 'POST',
          dataType: 'json',
          headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' },
          data: { _token: "{{ csrf_token() }}", product_id: id }
        }).done(function (res) {
          if (res.success) {
            swalToast('success', res.message || 'Uploaded');
            $('#images-products-table').DataTable().ajax.reload(null, false);
          } else {
            swalToast('error', res.message || 'Failed');
            btn.prop('disabled', false);
          }
        }).fail(function (xhr) {
          swalToast('error', ajaxErrMessage(xhr));
          btn.prop('disabled', false);
        });
      });
    });

    $(document).on('click', '.js-migrate-tbm-cloud', function () {
      var btn = $(this);
      var tbmId = btn.data('tbm-id');
      var loc = btn.attr('data-img-location');
      Swal.fire({
        title: 'Upload to cloud?',
        text: 'The file is sent to your R2/S3 bucket and this stc_safetytbm_img row is updated to the public URL.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Upload',
        cancelButtonText: 'Cancel'
      }).then(function (result) {
        if (!result.value) return;
        btn.prop('disabled', true);
        $.ajax({
          url: "{{ url('/images/tbm/migrate-cloud') }}",
          method: 'POST',
          dataType: 'json',
          headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json' },
          data: { _token: "{{ csrf_token() }}", tbm_id: tbmId, img_location: loc }
        }).done(function (res) {
          if (res.success) {
            swalToast('success', res.message || 'Uploaded');
            if ($.fn.DataTable.isDataTable('#images-tbm-table')) {
              $('#images-tbm-table').DataTable().ajax.reload(null, false);
            }
          } else {
            swalToast('error', res.message || 'Failed');
            btn.prop('disabled', false);
          }
        }).fail(function (xhr) {
          swalToast('error', ajaxErrMessage(xhr));
          btn.prop('disabled', false);
        });
      });
    });

    $('#images-products-table').DataTable({
      processing: true,
      serverSide: true,
      responsive: true,
      pageLength: 25,
      ajax: "{{ url('/images/products/list') }}",
      order: [[0, 'desc']],
      columns: [
        { data: 'product_id', className: 'text-center align-middle' },
        { data: 'product_name', className: 'align-middle' },
        { data: 'image_name', className: 'align-middle' },
        { data: 'actionData', orderable: false, searchable: false, className: 'text-center align-middle' }
      ]
    });

    var tbmTableInitialized = false;
    function initTbmTable() {
      if (tbmTableInitialized) return;
      tbmTableInitialized = true;
      $('#images-tbm-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        pageLength: 25,
        ajax: "{{ url('/images/tbm/list') }}",
        order: [[0, 'desc']],
        columns: [
          { data: 'tbm_id', className: 'text-center align-middle' },
          { data: 'tbm_location', className: 'align-middle' },
          { data: 'img_name', className: 'align-middle' },
          { data: 'actionData', orderable: false, searchable: false, className: 'text-center align-middle' }
        ]
      });
    }

    $('a[data-toggle="pill"][href="#tab-tbm"]').on('shown.bs.tab', function () {
      initTbmTable();
    });
  });
</script>
</body>
</html>
