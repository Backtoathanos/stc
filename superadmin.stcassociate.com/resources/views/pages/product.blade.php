<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>STC Associates | {{!empty($page_title) ? $page_title : ''}}</title>
  @include('layouts.head')
  <link rel="stylesheet" href="{{ asset('public/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
  <style>
    .modal-open .select2-container--open { z-index: 1060; }
    /* Select2 dropdown above modal backdrop / dialog stacking */
    .select2-container--open .select2-dropdown { z-index: 1065 !important; }
    #add-modal.show .modal-dialog,
    #edit-modal.show .modal-dialog { overflow: visible; }
    #add-modal.show .modal-content,
    #edit-modal.show .modal-content { overflow: visible; }
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
            <h1 class="m-0">Product</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="#">Master</a></li>
              <li class="breadcrumb-item active"><a href="#">{{!empty($page_title) ? $page_title : ''}}</a></li>
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
            <div class="col-12">
                <p>@include('layouts._message')</p>
            </div>
        </div>
        <div class="row">
          <div class="col-lg-12 col-12 mb-3"><a href="javascript:void(0)" class="btn btn-block btn-primary btn-md" data-target="#add-modal" data-toggle="modal">Add Product</a></div>
          <div class="col-lg-12 col-12">
            <div class="card">
                <!-- /.card-header -->
                <div class="card-body table-responsive">
                  <table id="example1" class="table table-bordered table-striped table-sm w-100">
                    <thead>
                      <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">Cat ID</th>
                        <th class="text-center">Sub Cat ID</th>
                        <th class="text-center">Rack ID</th>
                        <th class="text-center">Brand ID</th>
                        <th class="text-center">Unit</th>
                        <th class="text-center">HSN</th>
                        <th class="text-center">GST %</th>
                        <th class="text-center">Avail</th>
                        <th class="text-center">Image</th>
                        <th class="text-center">Sale %</th>
                        <th class="text-center">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">Cat ID</th>
                        <th class="text-center">Sub Cat ID</th>
                        <th class="text-center">Rack ID</th>
                        <th class="text-center">Brand ID</th>
                        <th class="text-center">Unit</th>
                        <th class="text-center">HSN</th>
                        <th class="text-center">GST %</th>
                        <th class="text-center">Avail</th>
                        <th class="text-center">Image</th>
                        <th class="text-center">Sale %</th>
                        <th class="text-center">Actions</th>
                      </tr>
                    </tfoot>
                  </table>
                </div>
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

<!-- delete modal -->
<div class="modal fade" id="delete-modal">
  <div class="modal-dialog">
    <div class="modal-content bg-danger">
      <div class="modal-header">
        <h4 class="modal-title">Delete Product</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this product?</p>
        <p class="text-warning"><small>This action cannot be undone.</small></p>
        <input type="hidden" id="delete_id">
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default close-btn" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger delete-product-btn">Delete</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>


<!-- edit modal -->
<div class="modal fade" id="edit-modal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Product</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="card-body">
          <div class="form-group">
            <label for="edit-name">Product name</label>
            <input type="text" class="form-control" id="edit-name" name="edit-name" placeholder="Product name">
          </div>
          <div class="form-group">
            <label for="edit-desc">Description</label>
            <textarea class="form-control" id="edit-desc" name="edit-desc" rows="2" placeholder="Product description"></textarea>
          </div>
          <div class="form-group">
            <label for="edit-cat_id">Category</label>
            <select class="form-control" id="edit-cat_id" name="edit-cat_id" style="width:100%;"></select>
          </div>
          <div class="form-group">
            <label for="edit-sub_cat_id">Sub category</label>
            <select class="form-control" id="edit-sub_cat_id" name="edit-sub_cat_id" style="width:100%;"></select>
          </div>
          <div class="form-group">
            <label for="edit-rack_id">Rack</label>
            <select class="form-control" id="edit-rack_id" name="edit-rack_id" style="width:100%;"></select>
          </div>
          <div class="form-group">
            <label for="edit-brand_id">Brand</label>
            <select class="form-control" id="edit-brand_id" name="edit-brand_id" style="width:100%;"></select>
          </div>
          <div class="form-group">
            <label for="edit-hsncode">HSN code</label>
            <input type="text" class="form-control" id="edit-hsncode" name="edit-hsncode">
          </div>
          <div class="form-group">
            <label for="edit-gst">GST (%)</label>
            <input type="number" step="0.01" class="form-control" id="edit-gst" name="edit-gst" placeholder="e.g. 18">
          </div>
          <div class="form-group">
            <label for="edit-sale_percentage">Sale discount (%)</label>
            <input type="number" step="0.01" class="form-control" id="edit-sale_percentage" name="edit-sale_percentage" placeholder="e.g. 10">
          </div>
          <div class="form-group">
            <label for="edit-unit">Unit</label>
            <input type="text" class="form-control" id="edit-unit" name="edit-unit" placeholder="e.g. PCS">
          </div>
          <div class="form-group">
            <label for="edit-image">Image URL / path</label>
            <input type="text" class="form-control" id="edit-image" name="edit-image" placeholder="https://… or relative path">
          </div>
          <div class="form-group">
            <label for="edit-status">Status</label>
            <select class="form-control" id="edit-status" name="edit-status">
                <option value="1">Active</option>
                <option value="0">In-active</option>
            </select>
          </div>
        </div>
        <!-- /.card-body -->
        <input type="hidden" id="edit_id">
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default close-btn" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger edit-product-btn">Save</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- add modal -->
<div class="modal fade" id="add-modal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Product</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="card-body">
          <div class="form-group">
            <label for="add-name">Product name</label>
            <input type="text" class="form-control" id="add-name" name="add-name" placeholder="Product name">
          </div>
          <div class="form-group">
            <label for="add-desc">Description</label>
            <textarea class="form-control" id="add-desc" name="add-desc" rows="2" placeholder="Product description (optional; defaults to name if empty)"></textarea>
          </div>
          <div class="form-group">
            <label for="add-cat_id">Category</label>
            <select class="form-control" id="add-cat_id" name="add-cat_id" style="width:100%;"></select>
          </div>
          <div class="form-group">
            <label for="add-sub_cat_id">Sub category</label>
            <select class="form-control" id="add-sub_cat_id" name="add-sub_cat_id" style="width:100%;"></select>
          </div>
          <div class="form-group">
            <label for="add-rack_id">Rack</label>
            <select class="form-control" id="add-rack_id" name="add-rack_id" style="width:100%;"></select>
          </div>
          <div class="form-group">
            <label for="add-brand_id">Brand</label>
            <select class="form-control" id="add-brand_id" name="add-brand_id" style="width:100%;"></select>
          </div>
          <div class="form-group">
            <label for="add-hsncode">HSN code</label>
            <input type="text" class="form-control" id="add-hsncode" name="add-hsncode">
          </div>
          <div class="form-group">
            <label for="add-gst">GST (%)</label>
            <input type="number" step="0.01" class="form-control" id="add-gst" name="add-gst" placeholder="e.g. 18">
          </div>
          <div class="form-group">
            <label for="add-sale_percentage">Sale discount (%)</label>
            <input type="number" step="0.01" class="form-control" id="add-sale_percentage" name="add-sale_percentage" placeholder="e.g. 10">
          </div>
          <div class="form-group">
            <label for="add-unit">Unit</label>
            <input type="text" class="form-control" id="add-unit" name="add-unit" placeholder="e.g. PCS">
          </div>
          <div class="form-group">
            <label for="add-image">Image URL / path</label>
            <input type="text" class="form-control" id="add-image" name="add-image" placeholder="https://… or relative path">
          </div>
          <div class="form-group">
            <label for="add-status">Status</label>
            <select class="form-control" id="add-status" name="add-status">
                <option value="1">Active</option>
                <option value="0">In-active</option>
            </select>
          </div>
        </div>
        <!-- /.card-body -->
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default close-btn" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger add-product-btn">Save</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

  @include('layouts.ajax_foot')
  <script src="{{ asset('public/plugins/select2/js/select2.full.min.js') }}"></script>

  <script>
    $(document).ready(function() {
      /* Bootstrap modal keeps focus inside the dialog and breaks Select2 search/dropdown clicks */
      if ($.fn.modal && $.fn.modal.Constructor && $.fn.modal.Constructor.prototype) {
        $.fn.modal.Constructor.prototype._enforceFocus = $.noop;
      }

      var select2Base = {
        theme: 'bootstrap4',
        width: '100%',
        allowClear: true,
        selectOnClose: false,
        dropdownAutoWidth: false
      };

      var optUrls = {
        categories: "{{ url('/master/product/options/categories') }}",
        subcategories: "{{ url('/master/product/options/subcategories') }}",
        racks: "{{ url('/master/product/options/racks') }}",
        brands: "{{ url('/master/product/options/brands') }}"
      };

      function dropdownParentEl(modal) {
        var $c = modal.find('.modal-content').first();
        return $c.length ? $c : modal;
      }

      function destroySel($el) {
        if ($el.length && $el.hasClass('select2-hidden-accessible')) {
          $el.select2('destroy');
        }
      }

      function initCatSelect($el, modal, prefillId, prefillText) {
        destroySel($el);
        $el.empty();
        if (prefillId !== '' && prefillId !== null && typeof prefillId !== 'undefined') {
          $el.append(new Option(prefillText || ('#' + prefillId), String(prefillId), true, true));
        }
        var parent = dropdownParentEl(modal);
        $el.select2($.extend({}, select2Base, {
          dropdownParent: parent,
          placeholder: 'Search category…',
          ajax: {
            url: optUrls.categories,
            type: 'GET',
            dataType: 'json',
            delay: 200,
            cache: false,
            data: function (params) { return { q: params.term || '' }; },
            processResults: function (data) {
              return { results: (data && data.results) ? data.results : [] };
            }
          },
          minimumInputLength: 0
        }));
      }

      function initSubSelect($el, modal, $catEl, prefillId, prefillText) {
        destroySel($el);
        $el.empty();
        if (prefillId !== '' && prefillId !== null && typeof prefillId !== 'undefined') {
          $el.append(new Option(prefillText || ('#' + prefillId), String(prefillId), true, true));
        }
        var parent = dropdownParentEl(modal);
        $el.select2($.extend({}, select2Base, {
          dropdownParent: parent,
          placeholder: 'Search sub category…',
          ajax: {
            url: optUrls.subcategories,
            type: 'GET',
            dataType: 'json',
            delay: 200,
            cache: false,
            data: function (params) {
              return { q: params.term || '', category_id: $catEl.val() || '' };
            },
            processResults: function (data) {
              return { results: (data && data.results) ? data.results : [] };
            }
          },
          minimumInputLength: 0
        }));
      }

      function initRackSelect($el, modal, prefillId, prefillText) {
        destroySel($el);
        $el.empty();
        if (prefillId !== '' && prefillId !== null && typeof prefillId !== 'undefined') {
          $el.append(new Option(prefillText || ('#' + prefillId), String(prefillId), true, true));
        }
        var parent = dropdownParentEl(modal);
        $el.select2($.extend({}, select2Base, {
          dropdownParent: parent,
          placeholder: 'Search rack…',
          ajax: {
            url: optUrls.racks,
            type: 'GET',
            dataType: 'json',
            delay: 200,
            cache: false,
            data: function (params) { return { q: params.term || '' }; },
            processResults: function (data) {
              return { results: (data && data.results) ? data.results : [] };
            }
          },
          minimumInputLength: 0
        }));
      }

      function initBrandSelect($el, modal, $catEl, prefillId, prefillText) {
        destroySel($el);
        $el.empty();
        if (prefillId !== '' && prefillId !== null && typeof prefillId !== 'undefined') {
          $el.append(new Option(prefillText || ('#' + prefillId), String(prefillId), true, true));
        }
        var parent = dropdownParentEl(modal);
        $el.select2($.extend({}, select2Base, {
          dropdownParent: parent,
          placeholder: 'Search brand…',
          ajax: {
            url: optUrls.brands,
            type: 'GET',
            dataType: 'json',
            delay: 200,
            cache: false,
            data: function (params) {
              return { q: params.term || '', category_id: $catEl.val() || '' };
            },
            processResults: function (data) {
              return { results: (data && data.results) ? data.results : [] };
            }
          },
          minimumInputLength: 0
        }));
      }

      function initAddProductSelect2() {
        var modal = $('#add-modal');
        initCatSelect($('#add-cat_id'), modal, null, null);
        initSubSelect($('#add-sub_cat_id'), modal, $('#add-cat_id'), null, null);
        initRackSelect($('#add-rack_id'), modal, null, null);
        initBrandSelect($('#add-brand_id'), modal, $('#add-cat_id'), null, null);
      }

      function initEditProductSelect2FromPayload(p) {
        var modal = $('#edit-modal');
        initCatSelect($('#edit-cat_id'), modal, p.cat_id, p.cat_label || '');
        initSubSelect($('#edit-sub_cat_id'), modal, $('#edit-cat_id'), p.sub_cat_id, p.sub_cat_label || '');
        initRackSelect($('#edit-rack_id'), modal, p.rack_id, p.rack_label || '');
        initBrandSelect($('#edit-brand_id'), modal, $('#edit-cat_id'), p.brand_id, p.brand_label || '');
      }

      $('#add-modal').on('shown.bs.modal', function () {
        initAddProductSelect2();
      });

      $(document).on('change', '#add-cat_id', function () {
        var $sub = $('#add-sub_cat_id');
        var $brand = $('#add-brand_id');
        if ($sub.hasClass('select2-hidden-accessible')) {
          $sub.val(null).trigger('change.select2');
        }
        if ($brand.hasClass('select2-hidden-accessible')) {
          $brand.val(null).trigger('change.select2');
        }
      });

      $(document).on('change', '#edit-cat_id', function () {
        var $sub = $('#edit-sub_cat_id');
        var $brand = $('#edit-brand_id');
        if ($sub.hasClass('select2-hidden-accessible')) {
          $sub.val(null).trigger('change.select2');
        }
        if ($brand.hasClass('select2-hidden-accessible')) {
          $brand.val(null).trigger('change.select2');
        }
      });

      $('#edit-modal').on('shown.bs.modal', function (e) {
        var btn = $(e.relatedTarget);
        if (!btn || !btn.hasClass('edit-modal-btn')) {
          return;
        }
        var raw = btn.attr('data-product');
        if (!raw) return;
        var p;
        try {
          p = JSON.parse(raw);
        } catch (err) {
          swalSuccess('error', 'Could not load product for editing.');
          return;
        }
        $('#edit_id').val(p.id);
        $('#edit-name').val(p.name || '');
        $('#edit-desc').val(p.desc || '');
        $('#edit-hsncode').val(p.hsncode || '');
        $('#edit-gst').val(p.gst === '' || p.gst === null ? '' : p.gst);
        $('#edit-sale_percentage').val(p.sale_percentage === '' || p.sale_percentage === null ? '' : p.sale_percentage);
        $('#edit-unit').val(p.unit || '');
        $('#edit-image').val(p.image || '');
        $('#edit-status').val(String(p.status));
        initEditProductSelect2FromPayload(p);
      });

      $('#edit-modal').on('hidden.bs.modal', function () {
        destroySel($('#edit-cat_id'));
        destroySel($('#edit-sub_cat_id'));
        destroySel($('#edit-rack_id'));
        destroySel($('#edit-brand_id'));
        $('#edit-cat_id, #edit-sub_cat_id, #edit-rack_id, #edit-brand_id').empty();
      });

      function swalSuccess(icon, message){
        var Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000
        });
        Toast.fire({
          icon: icon,
          title: message
        })
      }

      function ajaxErrorMessage(xhr) {
        if (xhr.responseJSON && xhr.responseJSON.message) {
          return xhr.responseJSON.message;
        }
        if (xhr.responseJSON && xhr.responseJSON.errors) {
          var e = xhr.responseJSON.errors;
          return Object.keys(e).map(function(k){ return e[k].join(' '); }).join(' ');
        }
        return 'Request failed.';
      }

      var dataTableAct="active";
      getProductTable(dataTableAct);

      function getProductTable(dataTableAct){
          $('#example1').DataTable({
          processing: true,
          serverSide: true,
          scrollX: true,
          autoWidth: false,
          pageLength: 25,
          lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
          ajax: {
            url: "{{ url('/master/product/list') }}",
            dataSrc: 'data'
          },
          columns: [
                { data: 'stc_product_id' },
                { data: 'stc_product_name' },
                { data: 'stc_product_desc' },
                { data: 'stc_product_cat_id' },
                { data: 'stc_product_sub_cat_id' },
                { data: 'stc_product_rack_id' },
                { data: 'stc_product_brand_id' },
                { data: 'stc_product_unit' },
                { data: 'stc_product_hsncode' },
                { data: 'stc_product_gst' },
                { data: 'stc_product_avail' },
                { data: 'stc_product_image' },
                { data: 'stc_product_sale_percentage' },
                { data: 'actionData' }
          ],
          columnDefs: [
            { targets: 0, className: 'text-center', width: '3%' },
            { targets: 1, className: 'text-left' },
            { targets: 2, className: 'text-left' },
            { targets: [3, 4, 5, 6], className: 'text-center' },
            { targets: 7, className: 'text-center' },
            { targets: 8, className: 'text-right' },
            { targets: 9, className: 'text-right' },
            { targets: 10, className: 'text-center' },
            { targets: 11, className: 'text-left' },
            { targets: 12, className: 'text-center' },
            { orderable: false, targets: 13 },
          ]
        });
      }

      function collectProductPayload(prefix) {
        return {
          name: $('#' + prefix + '-name').val(),
          desc: $('#' + prefix + '-desc').val(),
          cat_id: $('#' + prefix + '-cat_id').val(),
          sub_cat_id: $('#' + prefix + '-sub_cat_id').val() || null,
          rack_id: $('#' + prefix + '-rack_id').val() || null,
          brand_id: $('#' + prefix + '-brand_id').val() || null,
          hsncode: $('#' + prefix + '-hsncode').val(),
          gst: $('#' + prefix + '-gst').val(),
          sale_percentage: $('#' + prefix + '-sale_percentage').val(),
          unit: $('#' + prefix + '-unit').val(),
          image: $('#' + prefix + '-image').val(),
          status: $('#' + prefix + '-status').val(),
          _token: "{{ csrf_token() }}"
        };
      }

      $('body').delegate('.add-product-btn','click', function(){
        $.ajax({
            type: 'post',
            data: collectProductPayload('add'),
            url: "{{ url('/master/product/create') }}",
            success: function(response) {
              if(response.success==true){
                if ( $.fn.DataTable.isDataTable('#example1') ) {
                  $('#example1').DataTable().destroy();
                }
                dataTableAct="inactive";
                getProductTable(dataTableAct);
                $('.close-btn').click();
                swalSuccess('success', 'Record saved!');
              }else if(response.success==false){
                swalSuccess('error', response.message);
              }else{
                swalSuccess('error', 'Duplicate record found.');
              }
            },
            error: function(xhr) {
              swalSuccess('error', ajaxErrorMessage(xhr));
            }
        });
      });

      $('body').delegate('.edit-product-btn','click', function(){
        var data = collectProductPayload('edit');
        data.id = $('#edit_id').val();
        $.ajax({
            type: 'post',
            data: data,
            url: "{{ url('/master/product/edit') }}",
            success: function(response) {
              if(response.success==true){
                if ( $.fn.DataTable.isDataTable('#example1') ) {
                  $('#example1').DataTable().destroy();
                }
                dataTableAct="active";
                getProductTable(dataTableAct);
                $('.close-btn').click();
                swalSuccess('success', 'Record updated.');
              }else{
                swalSuccess('error', response.message);
              }
            },
            error: function(xhr) {
              swalSuccess('error', ajaxErrorMessage(xhr));
            }
        });
      });

      $('.delete-product-btn').on('click', function(e){
        e.preventDefault();
        var id = $('#delete_id').val();
        $.ajax({
            type: 'get',
            data: {
                id: id,
            },
            url: "{{ url('/master/product/delete') }}",
            success: function(response) {
              if(response.success==true){

                swalSuccess('success', 'Record deleted.');
                if ( $.fn.DataTable.isDataTable('#example1') ) {
                  $('#example1').DataTable().destroy();
                }
                dataTableAct="active";
                getProductTable(dataTableAct);
                $('.close-btn').click();
              }else{
                swalSuccess('error', response.message);
              }
            }
        });
      });
    });
  </script>

</body>
</html>
