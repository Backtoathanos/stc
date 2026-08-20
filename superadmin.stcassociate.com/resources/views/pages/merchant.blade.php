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
    .select2-container--open .select2-dropdown { z-index: 1065 !important; }
    #add-modal.show .modal-dialog,
    #edit-modal.show .modal-dialog { overflow: visible; }
    #add-modal.show .modal-content,
    #edit-modal.show .modal-content { overflow: visible; }
    #rename-source-modal { z-index: 1070; }
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
            <h1 class="m-0">Merchant</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="#">Master</a></li>
              <li class="breadcrumb-item active"><a href="#">{{!empty($page_title) ? $page_title : ''}}</a></li>
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
            </div>
        </div>
        <div class="row">
          <div class="col-lg-12 col-12 mb-3">
            <div class="row">
              <div class="col-md-4 mb-2 mb-md-0">
                <a href="javascript:void(0)" class="btn btn-block btn-warning btn-md" data-target="#adhoc-sources-modal" data-toggle="modal">Adhoc Sources</a>
              </div>
              <div class="col-md-8">
                <a href="javascript:void(0)" class="btn btn-block btn-primary btn-md" data-target="#add-modal" data-toggle="modal">Add Merchant</a>
              </div>
            </div>
          </div>
          <div class="col-lg-12 col-12">
            <div class="card">
                <div class="card-body table-responsive">
                  <table id="example1" class="table table-bordered table-striped table-sm w-100">
                    <thead>
                      <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Category</th>
                        <th class="text-center">City</th>
                        <th class="text-center">State</th>
                        <th class="text-center">Contact</th>
                        <th class="text-center">Phone</th>
                        <th class="text-center">GSTIN</th>
                        <th class="text-center">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th class="text-center">ID</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Category</th>
                        <th class="text-center">City</th>
                        <th class="text-center">State</th>
                        <th class="text-center">Contact</th>
                        <th class="text-center">Phone</th>
                        <th class="text-center">GSTIN</th>
                        <th class="text-center">Actions</th>
                      </tr>
                    </tfoot>
                  </table>
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

<div class="modal fade" id="delete-modal">
  <div class="modal-dialog">
    <div class="modal-content bg-danger">
      <div class="modal-header">
        <h4 class="modal-title">Delete Merchant</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this merchant?</p>
        <p class="text-warning"><small>This action cannot be undone.</small></p>
        <input type="hidden" id="delete_id">
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default close-btn" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger delete-merchant-btn">Delete</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="edit-modal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Merchant</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="card-body">
          @include('pages.partials.merchant-form-fields', ['prefix' => 'edit'])
        </div>
        <input type="hidden" id="edit_id">
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default close-btn" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger edit-merchant-btn">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="adhoc-sources-modal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Adhoc Sources</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="text-muted mb-2">Distinct adhoc source names that are not already in the merchant master. Matching merchant names are skipped. Rename updates every matching adhoc row.</p>
        <div class="d-flex align-items-center justify-content-between flex-wrap mb-2">
          <span class="text-muted" id="adhoc-selected-count">0 selected</span>
          <div>
            <button type="button" class="btn btn-default btn-sm" id="adhoc-clear-selected-btn">Clear</button>
            <button type="button" class="btn btn-primary btn-sm" id="rename-selected-btn" disabled>Rename selected</button>
          </div>
        </div>
        <div class="table-responsive">
          <table id="adhoc-sources-table" class="table table-bordered table-striped table-sm w-100">
            <thead>
              <tr>
                <th class="text-center"><input type="checkbox" class="adhoc-source-check-all" title="Select page"></th>
                <th class="text-center">Source name</th>
                <th class="text-center">Similar merchant</th>
                <th class="text-center">Used</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="rename-source-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Rename Source</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group" id="rename-old-single-wrap">
          <label>Current name</label>
          <input type="text" class="form-control" id="rename-old-source" readonly>
        </div>
        <div class="form-group" id="rename-old-multi-wrap" style="display:none;">
          <label>Selected names</label>
          <div id="rename-old-sources-list" class="border rounded p-2 bg-light" style="max-height:160px;overflow:auto;"></div>
        </div>
        <input type="hidden" id="rename-old-sources-json">
        <div class="form-group mb-0">
          <label for="rename-new-source">New name</label>
          <input type="text" class="form-control" id="rename-new-source" placeholder="Enter new source name">
          <small class="form-text text-muted" id="rename-source-hint"></small>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger rename-source-save-btn">Rename all</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="add-modal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Merchant</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="card-body">
          @include('pages.partials.merchant-form-fields', ['prefix' => 'add'])
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default close-btn" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger add-merchant-btn">Save</button>
      </div>
    </div>
  </div>
</div>

  @include('layouts.ajax_foot')
  <script src="{{ asset('public/plugins/select2/js/select2.full.min.js') }}"></script>

  <script>
    $(document).ready(function() {
      if ($.fn.modal && $.fn.modal.Constructor && $.fn.modal.Constructor.prototype) {
        $.fn.modal.Constructor.prototype._enforceFocus = $.noop;
      }

      var defaultCityId = "{{ (int) $default_city_id }}";
      var defaultStateId = "{{ (int) $default_state_id }}";

      function dropdownParentEl(modal) {
        var $c = modal.find('.modal-content').first();
        return $c.length ? $c : modal;
      }

      function initMerchantSelect2(modal) {
        modal.find('select.merchant-select2').each(function() {
          var $el = $(this);
          if ($el.hasClass('select2-hidden-accessible')) {
            $el.select2('destroy');
          }
          $el.select2({
            theme: 'bootstrap4',
            width: '100%',
            allowClear: true,
            placeholder: $el.find('option:first').text() || 'Select',
            dropdownParent: dropdownParentEl(modal)
          });
        });
      }

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
        });
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

      function reloadTable() {
        if ($.fn.DataTable.isDataTable('#example1')) {
          $('#example1').DataTable().destroy();
        }
        getMerchantTable();
      }

      function getMerchantTable(){
        $('#example1').DataTable({
          processing: true,
          serverSide: true,
          scrollX: true,
          autoWidth: false,
          pageLength: 25,
          order: [[0, 'desc']],
          lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
          ajax: {
            url: "{{ url('/master/merchant/list') }}",
            dataSrc: 'data'
          },
          columns: [
            { data: 'stc_merchant_id' },
            { data: 'stc_merchant_name' },
            { data: 'stc_merchant_category' },
            { data: 'stc_merchant_city_id' },
            { data: 'stc_merchant_state_id' },
            { data: 'stc_merchant_contact_person' },
            { data: 'stc_merchant_phone' },
            { data: 'stc_merchant_gstin' },
            { data: 'actionData' }
          ],
          columnDefs: [
            { targets: 0, className: 'text-center', width: '4%' },
            { targets: 1, className: 'text-left' },
            { targets: [2, 3, 4], className: 'text-center' },
            { targets: 5, className: 'text-left' },
            { targets: [6, 7], className: 'text-center' },
            { orderable: false, targets: 8, className: 'text-center' }
          ]
        });
      }

      function collectMerchantPayload(prefix) {
        return {
          name: $('#' + prefix + '-name').val(),
          address: $('#' + prefix + '-address').val(),
          city_id: $('#' + prefix + '-city_id').val(),
          state_id: $('#' + prefix + '-state_id').val(),
          category: $('#' + prefix + '-category').val(),
          contact_person: $('#' + prefix + '-contact_person').val(),
          phone: $('#' + prefix + '-phone').val(),
          email: $('#' + prefix + '-email').val(),
          known_for: $('#' + prefix + '-known_for').val(),
          gstin: $('#' + prefix + '-gstin').val(),
          pan: $('#' + prefix + '-pan').val(),
          image: $('#' + prefix + '-image').val(),
          _token: "{{ csrf_token() }}"
        };
      }

      function resetAddForm() {
        $('#add-name, #add-address, #add-contact_person, #add-phone, #add-email, #add-known_for, #add-gstin, #add-pan, #add-image').val('');
        $('#add-category').val('');
        $('#add-city_id').val(defaultCityId).trigger('change');
        $('#add-state_id').val(defaultStateId).trigger('change');
      }

      getMerchantTable();

      function reloadAdhocSourcesTable() {
        if ($.fn.DataTable.isDataTable('#adhoc-sources-table')) {
          $('#adhoc-sources-table').DataTable().ajax.reload(null, false);
        }
      }

      var selectedAdhocSources = {};

      function selectedAdhocList() {
        return Object.keys(selectedAdhocSources);
      }

      function selectedAdhocUsage() {
        var total = 0;
        $.each(selectedAdhocSources, function(name, meta) {
          var count = (meta && typeof meta === 'object') ? meta.count : meta;
          total += parseInt(count, 10) || 0;
        });
        return total;
      }

      function updateAdhocSelectedUi() {
        var n = selectedAdhocList().length;
        $('#adhoc-selected-count').text(n + ' selected');
        $('#rename-selected-btn').prop('disabled', n < 1);
        var $pageChecks = $('#adhoc-sources-table .adhoc-source-check');
        var pageCount = $pageChecks.length;
        var pageChecked = $pageChecks.filter(':checked').length;
        var $all = $('#adhoc-sources-table .adhoc-source-check-all, .dataTables_scrollHead .adhoc-source-check-all');
        $all.prop('checked', pageCount > 0 && pageChecked === pageCount);
        $all.prop('indeterminate', pageChecked > 0 && pageChecked < pageCount);
      }

      function parseSourcePayload($el) {
        try {
          return JSON.parse($el.attr('data-source'));
        } catch (err) {
          return null;
        }
      }

      function restoreAdhocChecks() {
        $('#adhoc-sources-table .adhoc-source-check').each(function() {
          var p = parseSourcePayload($(this));
          $(this).prop('checked', !!(p && selectedAdhocSources.hasOwnProperty(p.source)));
        });
        updateAdhocSelectedUi();
      }

      function openRenameModal(items) {
        items = items || [];
        if (!items.length) {
          swalSuccess('error', 'Select at least one source.');
          return;
        }
        var names = items.map(function(item){ return item.source; });
        var usage = 0;
        items.forEach(function(item){ usage += parseInt(item.count, 10) || 0; });
        $('#rename-old-sources-json').val(JSON.stringify(names));
        if (items.length === 1) {
          $('#rename-old-single-wrap').show();
          $('#rename-old-multi-wrap').hide();
          $('#rename-old-source').val(items[0].source);
          $('#rename-new-source').val(items[0].similar || items[0].source);
        } else {
          $('#rename-old-single-wrap').hide();
          $('#rename-old-multi-wrap').show();
          var html = items.map(function(item){
            return '<div>' + $('<div>').text(item.source + ' (' + item.count + ')').html() + '</div>';
          }).join('');
          $('#rename-old-sources-list').html(html);
          $('#rename-old-source').val('');
          var suggested = '';
          items.forEach(function(item){
            if (!suggested && item.similar) suggested = item.similar;
          });
          $('#rename-new-source').val(suggested || items[0].source);
        }
        $('#rename-source-hint').text(usage + ' adhoc record(s) will be renamed.');
        $('#rename-source-modal').modal('show');
      }

      function getAdhocSourcesTable(){
        $('#adhoc-sources-table').DataTable({
          processing: true,
          serverSide: true,
          scrollX: true,
          autoWidth: false,
          pageLength: 25,
          order: [[1, 'asc']],
          lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
          ajax: {
            url: "{{ url('/master/merchant/adhoc-sources/list') }}",
            dataSrc: 'data'
          },
          columns: [
            { data: 'select' },
            { data: 'source_name' },
            { data: 'similar_merchant' },
            { data: 'usage_count' },
            { data: 'actionData' }
          ],
          columnDefs: [
            { orderable: false, targets: 0, className: 'text-center', width: '4%' },
            { targets: 1, className: 'text-left' },
            { orderable: false, targets: 2, className: 'text-left' },
            { targets: 3, className: 'text-center', width: '10%' },
            { orderable: false, targets: 4, className: 'text-center', width: '12%' }
          ],
          drawCallback: function() {
            restoreAdhocChecks();
          }
        });
      }

      $('#adhoc-sources-modal').on('shown.bs.modal', function () {
        if ($.fn.DataTable.isDataTable('#adhoc-sources-table')) {
          $('#adhoc-sources-table').DataTable().columns.adjust().ajax.reload(null, false);
        } else {
          getAdhocSourcesTable();
        }
      });

      $('#adhoc-sources-modal').on('hidden.bs.modal', function () {
        selectedAdhocSources = {};
        updateAdhocSelectedUi();
      });

      $('body').delegate('.adhoc-source-check', 'change', function(){
        var p = parseSourcePayload($(this));
        if (!p || !p.source) return;
        if (this.checked) {
          selectedAdhocSources[p.source] = { count: p.count || 0, similar: p.similar || '' };
        } else {
          delete selectedAdhocSources[p.source];
        }
        updateAdhocSelectedUi();
      });

      $('body').delegate('.adhoc-source-check-all', 'change', function(){
        var checked = this.checked;
        $('#adhoc-sources-table .adhoc-source-check').each(function() {
          var p = parseSourcePayload($(this));
          if (!p || !p.source) return;
          $(this).prop('checked', checked);
          if (checked) {
            selectedAdhocSources[p.source] = { count: p.count || 0, similar: p.similar || '' };
          } else {
            delete selectedAdhocSources[p.source];
          }
        });
        updateAdhocSelectedUi();
      });

      $('#adhoc-clear-selected-btn').on('click', function(){
        selectedAdhocSources = {};
        restoreAdhocChecks();
      });

      $('#rename-selected-btn').on('click', function(){
        var items = selectedAdhocList().map(function(name){
          var meta = selectedAdhocSources[name] || {};
          if (typeof meta !== 'object') {
            meta = { count: meta, similar: '' };
          }
          return { source: name, count: meta.count || 0, similar: meta.similar || '' };
        });
        openRenameModal(items);
      });

      $('body').delegate('.rename-source-btn', 'click', function(){
        var p = parseSourcePayload($(this));
        if (!p) {
          swalSuccess('error', 'Could not load source name.');
          return;
        }
        openRenameModal([p]);
      });

      $('#rename-source-modal').on('hidden.bs.modal', function () {
        if ($('#adhoc-sources-modal').hasClass('show')) {
          $('body').addClass('modal-open');
        }
      });

      $('.rename-source-save-btn').on('click', function(){
        var newSource = $.trim($('#rename-new-source').val() || '');
        var oldSources = [];
        try {
          oldSources = JSON.parse($('#rename-old-sources-json').val() || '[]');
        } catch (err) {
          oldSources = [];
        }
        if (!oldSources.length) {
          var single = $.trim($('#rename-old-source').val() || '');
          if (single) oldSources = [single];
        }
        if (!newSource) {
          swalSuccess('error', 'Enter a new source name.');
          return;
        }
        $.ajax({
          type: 'post',
          data: {
            old_sources: oldSources,
            new_source: newSource,
            _token: "{{ csrf_token() }}"
          },
          url: "{{ url('/master/merchant/adhoc-sources/rename') }}",
          success: function(response) {
            if (response.success == true) {
              $('#rename-source-modal').modal('hide');
              selectedAdhocSources = {};
              reloadAdhocSourcesTable();
              swalSuccess('success', response.message || 'Renamed.');
            } else {
              swalSuccess('error', response.message || 'Rename failed.');
            }
          },
          error: function(xhr) {
            swalSuccess('error', ajaxErrorMessage(xhr));
          }
        });
      });

      $('#add-modal').on('show.bs.modal', function () {
        $('#add-name, #add-address, #add-contact_person, #add-phone, #add-email, #add-known_for, #add-gstin, #add-pan, #add-image').val('');
        $('#add-category').val('');
      });

      $('#add-modal').on('shown.bs.modal', function () {
        initMerchantSelect2($(this));
        $('#add-city_id').val(defaultCityId).trigger('change');
        $('#add-state_id').val(defaultStateId).trigger('change');
      });

      $('#edit-modal').on('shown.bs.modal', function (e) {
        var btn = $(e.relatedTarget);
        if (!btn || !btn.hasClass('edit-modal-btn')) {
          return;
        }
        var raw = btn.attr('data-merchant');
        if (!raw) return;
        var p;
        try {
          p = JSON.parse(raw);
        } catch (err) {
          swalSuccess('error', 'Could not load merchant for editing.');
          return;
        }
        $('#edit_id').val(p.id);
        $('#edit-name').val(p.name || '');
        $('#edit-address').val(p.address || '');
        $('#edit-category').val(p.category || '');
        $('#edit-contact_person').val(p.contact_person || '');
        $('#edit-phone').val(p.phone || '');
        $('#edit-email').val(p.email || '');
        $('#edit-known_for').val(p.known_for || '');
        $('#edit-gstin').val(p.gstin || '');
        $('#edit-pan').val(p.pan || '');
        $('#edit-image').val(p.image || '');
        $('#edit-city_id').val(p.city_id ? String(p.city_id) : '');
        $('#edit-state_id').val(p.state_id ? String(p.state_id) : '');
        initMerchantSelect2($(this));
      });

      $('body').delegate('.add-merchant-btn','click', function(){
        $.ajax({
            type: 'post',
            data: collectMerchantPayload('add'),
            url: "{{ url('/master/merchant/create') }}",
            success: function(response) {
              if(response.success==true){
                reloadTable();
                resetAddForm();
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

      $('body').delegate('.edit-merchant-btn','click', function(){
        var data = collectMerchantPayload('edit');
        data.id = $('#edit_id').val();
        $.ajax({
            type: 'post',
            data: data,
            url: "{{ url('/master/merchant/edit') }}",
            success: function(response) {
              if(response.success==true){
                reloadTable();
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

      $('.delete-merchant-btn').on('click', function(e){
        e.preventDefault();
        var id = $('#delete_id').val();
        $.ajax({
            type: 'get',
            data: { id: id },
            url: "{{ url('/master/merchant/delete') }}",
            success: function(response) {
              if(response.success==true){
                swalSuccess('success', 'Record deleted.');
                reloadTable();
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
