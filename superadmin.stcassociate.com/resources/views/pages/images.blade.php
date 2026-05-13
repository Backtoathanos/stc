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
            @if (!empty($cloud_s3_adapter_missing))
            <div class="alert alert-danger border-0 shadow-sm">
              <strong>PHP packages missing:</strong> this server does not have the S3/R2 Composer libraries (e.g. <code>League\Flysystem\AwsS3v3\AwsS3Adapter</code>).
              SSH into the server, <code>cd</code> to this Laravel project, then run <code>composer install --no-dev</code>.
              If Composer is not installed on the host, upload the full <code>vendor/</code> folder from a machine where <code>composer install</code> already ran.
            </div>
            @elseif (empty($cloud_upload_ready))
            <div class="alert alert-warning border-0 shadow-sm">
              <strong>Cloud upload (Cloudflare R2):</strong> add credentials to <code>.env</code>:
              <code>R2_ACCESS_KEY_ID</code>, <code>R2_SECRET_ACCESS_KEY</code>, <code>R2_BUCKET</code>,
              <code>R2_ENDPOINT</code> (e.g. <code>https://&lt;account&gt;.r2.cloudflarestorage.com</code> — no bucket path),
              <code>R2_PUBLIC_URL</code> as your <em>public</em> reader base URL (custom domain or R2.dev),
              plus <code>R2_REGION=auto</code> and <code>R2_USE_PATH_STYLE_ENDPOINT=true</code>.
              Pricing: <a href="https://developers.cloudflare.com/r2/pricing/" target="_blank" rel="noopener">Cloudflare R2</a>.
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
                    <div class="d-flex flex-wrap align-items-center mb-2 pb-2 border-bottom">
                      <label for="js-products-image-kind" class="mb-1 mr-2 small font-weight-bold text-muted">Show</label>
                      <select id="js-products-image-kind" class="form-control form-control-sm mb-1 mr-3" style="width:auto;min-width:11rem">
                        <option value="all">All rows</option>
                        <option value="local">Local filename only</option>
                        <option value="cloud">Cloud URL only</option>
                        <option value="empty_only">No image only</option>
                      </select>
                      <div class="custom-control custom-checkbox mb-1 mr-3">
                        <input type="checkbox" class="custom-control-input" id="js-hide-empty-products">
                        <label class="custom-control-label small" for="js-hide-empty-products">Hide rows with no image</label>
                      </div>
                      @if (!empty($cloud_upload_ready))
                      <span class="small text-muted mb-1"><i class="fas fa-info-circle"></i> Bulk pairs direct uploads by <strong>sorted product ID</strong> × files sorted by filename.</span>
                      @endif
                    </div>
                    <div class="d-none mb-2 js-products-bulk-toolbar clearfix">
                      <div class="float-right border rounded px-3 py-2 bg-light shadow-sm">
                        <span class="text-muted small mr-2 js-products-bulk-count">0 selected</span>
                        <span class="text-muted small mr-2">(max {{ (int) ($cloud_migrate_batch_max ?? 100) }} per request)</span>
                        @if (!empty($cloud_upload_ready))
                        <button type="button" class="btn btn-secondary btn-sm js-products-direct-upload mr-1" title="Pick image files from your computer (same count as selection)">
                          <i class="fas fa-folder-open"></i> Upload files → cloud
                        </button>
                        <input type="file" id="js-products-direct-files" class="d-none" accept="image/*" multiple>
                        @endif
                        <button type="button" class="btn btn-warning btn-sm js-bulk-migrate-products">
                          <i class="fas fa-cloud-upload-alt"></i> Upload from server/disk path
                        </button>
                      </div>
                    </div>
                    <div class="table-responsive">
                      <table id="images-products-table" class="table table-bordered table-striped table-hover mb-0" style="width:100%">
                        <thead>
                          <tr>
                            <th class="text-center align-middle" style="width:40px" data-orderable="false">
                              @if (!empty($cloud_upload_ready))
                              <input type="checkbox" id="images-products-select-all" class="js-products-select-all" title="Select all on this page" aria-label="Select all on page">
                              @endif
                            </th>
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
                    <div class="d-flex flex-wrap align-items-center mb-2 pb-2 border-bottom">
                      <label for="js-tbm-image-kind" class="mb-1 mr-2 small font-weight-bold text-muted">Show</label>
                      <select id="js-tbm-image-kind" class="form-control form-control-sm mb-1 mr-3" style="width:auto;min-width:11rem">
                        <option value="all">All rows</option>
                        <option value="local">Local filename only</option>
                        <option value="cloud">Cloud URL only</option>
                        <option value="empty_only">No image only</option>
                      </select>
                      <div class="custom-control custom-checkbox mb-1 mr-3">
                        <input type="checkbox" class="custom-control-input" id="js-hide-empty-tbm">
                        <label class="custom-control-label small" for="js-hide-empty-tbm">Hide rows with no image</label>
                      </div>
                    </div>
                    <div class="d-none mb-2 js-tbm-bulk-toolbar clearfix">
                      <div class="float-right border rounded px-3 py-2 bg-light shadow-sm">
                        <span class="text-muted small mr-2 js-tbm-bulk-count">0 selected</span>
                        <span class="text-muted small mr-2">(max {{ (int) ($cloud_migrate_batch_max ?? 100) }} per request)</span>
                        <button type="button" class="btn btn-warning btn-sm js-bulk-migrate-tbm">
                          <i class="fas fa-cloud-upload-alt"></i> Upload selected to cloud
                        </button>
                      </div>
                    </div>
                    <div class="table-responsive">
                      <table id="images-tbm-table" class="table table-bordered table-striped table-hover mb-0" style="width:100%">
                        <thead>
                          <tr>
                            <th class="text-center align-middle" style="width:40px" data-orderable="false">
                              @if (!empty($cloud_upload_ready))
                              <input type="checkbox" id="images-tbm-select-all" class="js-tbm-select-all" title="Select all on this page" aria-label="Select all on page">
                              @endif
                            </th>
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
    var BATCH_MAX = {{ (int) ($cloud_migrate_batch_max ?? 100) }};

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

    function summarizeBatchFails(results, idField) {
      if (!results || !results.length) return '';
      var fails = [];
      for (var i = 0; i < results.length; i++) {
        if (!results[i].success) fails.push(results[i]);
      }
      if (!fails.length) return '';
      var lines = [];
      var cap = 10;
      for (var j = 0; j < fails.length && j < cap; j++) {
        var r = fails[j];
        var id = r[idField] != null ? r[idField] : '?';
        lines.push('#' + id + ': ' + (r.message || 'Failed'));
      }
      if (fails.length > cap) lines.push('… +' + (fails.length - cap) + ' more');
      return lines.join('\n');
    }

    $(document).on('click', '.js-migrate-product-cloud', function () {
      var btn = $(this);
      var id = btn.data('product-id');
      Swal.fire({
        title: 'Upload to cloud?',
        text: 'The file is sent to your R2 bucket and stc_product_image is replaced with the public URL. Optional: local file can be deleted after upload (see CLOUD_DELETE_LOCAL_AFTER_UPLOAD).',
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
        text: 'The file is sent to your R2 bucket and this stc_safetytbm_img row is updated to the public URL.',
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

    function updateProductsBulkUi() {
      var n = $('#images-products-table tbody .js-product-row-select:checked').length;
      var $bar = $('.js-products-bulk-toolbar');
      var $all = $('#images-products-select-all');
      if (n > 0) {
        $bar.removeClass('d-none');
        $('.js-products-bulk-count').text(n + ' selected');
      } else {
        $bar.addClass('d-none');
        $('.js-products-bulk-count').text('0 selected');
      }
      if ($all.length) {
        var total = $('#images-products-table tbody .js-product-row-select').length;
        $all.prop('checked', total > 0 && n === total);
      }
    }

    function updateTbmBulkUi() {
      var n = $('#images-tbm-table tbody .js-tbm-row-select:checked').length;
      var $bar = $('.js-tbm-bulk-toolbar');
      var $all = $('#images-tbm-select-all');
      if (n > 0) {
        $bar.removeClass('d-none');
        $('.js-tbm-bulk-count').text(n + ' selected');
      } else {
        $bar.addClass('d-none');
        $('.js-tbm-bulk-count').text('0 selected');
      }
      if ($all.length) {
        var total = $('#images-tbm-table tbody .js-tbm-row-select').length;
        $all.prop('checked', total > 0 && n === total);
      }
    }

    $('#images-products-table').on('change', '.js-product-row-select', updateProductsBulkUi);
    $('#images-products-table').on('change', '.js-products-select-all', function () {
      var on = $(this).prop('checked');
      $('#images-products-table tbody .js-product-row-select').prop('checked', on);
      updateProductsBulkUi();
    });

    $('#images-tbm-table').on('change', '.js-tbm-row-select', updateTbmBulkUi);
    $('#images-tbm-table').on('change', '.js-tbm-select-all', function () {
      var on = $(this).prop('checked');
      $('#images-tbm-table tbody .js-tbm-row-select').prop('checked', on);
      updateTbmBulkUi();
    });

    $('.js-bulk-migrate-products').on('click', function () {
      var ids = [];
      $('#images-products-table tbody .js-product-row-select:checked').each(function () {
        if ($(this).attr('data-image-remote') === '1') return;
        ids.push($(this).data('product-id'));
      });
      if (!ids.length) {
        Swal.fire({ icon: 'info', title: 'Nothing to migrate', text: 'Choose rows that still have a local filename (not already a cloud URL), or use “Upload files → cloud” for computer files.' });
        return;
      }
      if (!ids.length) return;
      if (ids.length > BATCH_MAX) {
        Swal.fire({
          icon: 'warning',
          title: 'Too many selected',
          text: 'Shared hosting: upload at most ' + BATCH_MAX + ' images per request. Deselect some rows, upload, then select the next batch. (Adjust CLOUD_MIGRATE_BATCH_MAX in .env if your host allows.)'
        });
        return;
      }
      Swal.fire({
        title: 'Upload ' + ids.length + ' to cloud?',
        text: 'One server request uploads all selected images to R2 (easier on shared hosting than many separate requests).',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Upload',
        cancelButtonText: 'Cancel'
      }).then(function (result) {
        if (!result.value) return;
        var btn = $('.js-bulk-migrate-products');
        btn.prop('disabled', true);
        $.ajax({
          url: "{{ url('/images/products/migrate-cloud-batch') }}",
          method: 'POST',
          dataType: 'json',
          contentType: 'application/json',
          processData: false,
          headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
          data: JSON.stringify({ _token: "{{ csrf_token() }}", product_ids: ids })
        }).done(function (res) {
          $('#images-products-table').DataTable().ajax.reload(null, false);
          var detail = summarizeBatchFails(res.results, 'product_id');
          Swal.fire({
            icon: res.success ? 'success' : 'warning',
            title: res.success ? 'Bulk upload complete' : 'Bulk upload finished with errors',
            html: '<p>' + (res.message || '') + '</p>' + (detail ? '<pre style="text-align:left;font-size:12px;max-height:220px;overflow:auto">' + detail.replace(/</g, '&lt;') + '</pre>' : '')
          });
        }).fail(function (xhr) {
          Swal.fire({ icon: 'error', title: 'Request failed', text: ajaxErrMessage(xhr) });
        }).always(function () {
          btn.prop('disabled', false);
        });
      });
    });

    $('.js-bulk-migrate-tbm').on('click', function () {
      var rows = [];
      $('#images-tbm-table tbody .js-tbm-row-select:checked').each(function () {
        rows.push({
          tbm_id: $(this).data('tbm-id'),
          img_location: $(this).attr('data-img-location')
        });
      });
      if (!rows.length) return;
      if (rows.length > BATCH_MAX) {
        Swal.fire({
          icon: 'warning',
          title: 'Too many selected',
          text: 'Upload at most ' + BATCH_MAX + ' TBM images per request. Deselect some and run again.'
        });
        return;
      }
      Swal.fire({
        title: 'Upload ' + rows.length + ' to cloud?',
        text: 'One server request uploads all selected TBM images.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Upload',
        cancelButtonText: 'Cancel'
      }).then(function (result) {
        if (!result.value) return;
        var btn = $('.js-bulk-migrate-tbm');
        btn.prop('disabled', true);
        $.ajax({
          url: "{{ url('/images/tbm/migrate-cloud-batch') }}",
          method: 'POST',
          dataType: 'json',
          contentType: 'application/json',
          processData: false,
          headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
          data: JSON.stringify({ _token: "{{ csrf_token() }}", tbm_items: rows })
        }).done(function (res) {
          if ($.fn.DataTable.isDataTable('#images-tbm-table')) {
            $('#images-tbm-table').DataTable().ajax.reload(null, false);
          }
          var detail = summarizeBatchFails(res.results, 'tbm_id');
          Swal.fire({
            icon: res.success ? 'success' : 'warning',
            title: res.success ? 'Bulk upload complete' : 'Bulk upload finished with errors',
            html: '<p>' + (res.message || '') + '</p>' + (detail ? '<pre style="text-align:left;font-size:12px;max-height:220px;overflow:auto">' + detail.replace(/</g, '&lt;') + '</pre>' : '')
          });
        }).fail(function (xhr) {
          Swal.fire({ icon: 'error', title: 'Request failed', text: ajaxErrMessage(xhr) });
        }).always(function () {
          btn.prop('disabled', false);
        });
      });
    });

    $('.js-products-direct-upload').on('click', function () {
      var ids = [];
      $('#images-products-table tbody .js-product-row-select:checked').each(function () {
        ids.push(parseInt($(this).data('product-id'), 10));
      });
      if (!ids.length) {
        Swal.fire({ icon: 'warning', title: 'Select products first', text: 'Check one or more rows (empty image, local filename, or cloud URL you want to replace).' });
        return;
      }
      ids.sort(function (a, b) { return a - b; });
      if (ids.length > BATCH_MAX) {
        Swal.fire({ icon: 'warning', title: 'Too many selected', text: 'At most ' + BATCH_MAX + ' images per request.' });
        return;
      }
      $('#js-products-direct-files').data('pending-ids', ids).trigger('click');
    });

    $('#js-products-direct-files').on('change', function () {
      var input = this;
      var ids = $(input).data('pending-ids') || [];
      $(input).removeData('pending-ids');
      var fileList = input.files ? Array.prototype.slice.call(input.files, 0) : [];
      input.value = '';
      if (!ids.length || !fileList.length) return;

      fileList.sort(function (a, b) { return String(a.name).localeCompare(String(b.name), undefined, { sensitivity: 'base', numeric: true }); });

      if (fileList.length !== ids.length) {
        Swal.fire({
          icon: 'warning',
          title: 'Count mismatch',
          html: 'Selected <strong>' + ids.length + '</strong> products but chose <strong>' + fileList.length + '</strong> files. They must match.'
        });
        return;
      }

      var fd = new FormData();
      fd.append('_token', "{{ csrf_token() }}");
      ids.forEach(function (id) { fd.append('product_ids[]', id); });
      fileList.forEach(function (f) { fd.append('files[]', f); });

      Swal.fire({
        title: 'Upload ' + ids.length + ' images?',
        text: 'Files are paired with products using ascending product ID and ascending filename.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Upload',
        cancelButtonText: 'Cancel'
      }).then(function (result) {
        if (!result.value) return;
        var btn = $('.js-products-direct-upload');
        btn.prop('disabled', true);
        $.ajax({
          url: "{{ url('/images/products/upload-cloud-files') }}",
          method: 'POST',
          data: fd,
          processData: false,
          contentType: false,
          dataType: 'json',
          headers: { 'X-Requested-With': 'XMLHttpRequest', Accept: 'application/json', 'X-CSRF-TOKEN': "{{ csrf_token() }}" }
        }).done(function (res) {
          $('#images-products-table').DataTable().ajax.reload(null, false);
          var detail = summarizeBatchFails(res.results, 'product_id');
          Swal.fire({
            icon: res.success ? 'success' : 'warning',
            title: res.success ? 'Upload complete' : 'Finished with errors',
            html: '<p>' + (res.message || '') + '</p>' + (detail ? '<pre style="text-align:left;font-size:12px;max-height:220px;overflow:auto">' + detail.replace(/</g, '&lt;') + '</pre>' : '')
          });
        }).fail(function (xhr) {
          Swal.fire({ icon: 'error', title: 'Request failed', text: ajaxErrMessage(xhr) });
        }).always(function () {
          btn.prop('disabled', false);
        });
      });
    });

    $('#images-products-table').DataTable({
      processing: true,
      serverSide: true,
      responsive: true,
      pageLength: 25,
      ajax: {
        url: "{{ url('/images/products/list') }}",
        data: function (d) {
          d.image_kind = $('#js-products-image-kind').val();
          d.hide_empty = $('#js-hide-empty-products').is(':checked') ? '1' : '0';
        }
      },
      order: [[1, 'desc']],
      columns: [
        { data: 'bulk_select', orderable: false, searchable: false, className: 'text-center align-middle', responsivePriority: 1 },
        { data: 'product_id', className: 'text-center align-middle' },
        { data: 'product_name', className: 'align-middle' },
        { data: 'image_name', className: 'align-middle' },
        { data: 'actionData', orderable: false, searchable: false, className: 'text-center align-middle' }
      ]
    }).on('draw.dt', function () {
      $('.js-products-bulk-toolbar').addClass('d-none');
      $('.js-products-bulk-count').text('0 selected');
      $('#images-products-select-all').prop('checked', false);
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
        ajax: {
          url: "{{ url('/images/tbm/list') }}",
          data: function (d) {
            d.image_kind = $('#js-tbm-image-kind').val();
            d.hide_empty = $('#js-hide-empty-tbm').is(':checked') ? '1' : '0';
          }
        },
        order: [[1, 'desc']],
        columns: [
          { data: 'bulk_select', orderable: false, searchable: false, className: 'text-center align-middle', responsivePriority: 1 },
          { data: 'tbm_id', className: 'text-center align-middle' },
          { data: 'tbm_location', className: 'align-middle' },
          { data: 'img_name', className: 'align-middle' },
          { data: 'actionData', orderable: false, searchable: false, className: 'text-center align-middle' }
        ]
      }).on('draw.dt', function () {
        $('.js-tbm-bulk-toolbar').addClass('d-none');
        $('.js-tbm-bulk-count').text('0 selected');
        $('#images-tbm-select-all').prop('checked', false);
      });
    }

    $('a[data-toggle="pill"][href="#tab-tbm"]').on('shown.bs.tab', function () {
      initTbmTable();
    });

    $('#js-products-image-kind, #js-hide-empty-products').on('change', function () {
      if ($.fn.DataTable.isDataTable('#images-products-table')) {
        $('#images-products-table').DataTable().ajax.reload();
      }
    });
    $('#js-tbm-image-kind, #js-hide-empty-tbm').on('change', function () {
      if ($.fn.DataTable.isDataTable('#images-tbm-table')) {
        $('#images-tbm-table').DataTable().ajax.reload();
      }
    });
  });
</script>
</body>
</html>
