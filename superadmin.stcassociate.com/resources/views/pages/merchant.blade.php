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
    .dup-token { display:inline-block; padding:1px 6px; margin:0 2px 2px 0; border-radius:10px; font-size:11px; background:#eef2f7; }
    .dup-token.is-overlap { background:#d4edda; color:#155724; }
    .dup-keep-row { background:#f4fbf6; }
    .dup-group-card { border:1px solid #dee2e6; }
    .dup-group-card .table td { vertical-align: middle; }
    .dup-adhoc-uses-btn { cursor: pointer; }
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
          <div class="col-lg-12 col-12">
            <div class="card card-primary card-outline card-outline-tabs">
              <div class="card-header p-0 border-bottom-0">
                <ul class="nav nav-tabs" id="merchant-tab-nav" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active" id="tab-merchants-link" data-toggle="pill" href="#tab-merchants" role="tab">Merchants</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="tab-duplicates-link" data-toggle="pill" href="#tab-duplicates" role="tab">Duplicates</a>
                  </li>
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane fade show active" id="tab-merchants" role="tabpanel">
                    <div class="row mb-3">
                      <div class="col-md-4 mb-2 mb-md-0">
                        <a href="javascript:void(0)" class="btn btn-block btn-warning btn-md" data-target="#adhoc-sources-modal" data-toggle="modal">Adhoc Sources</a>
                      </div>
                      <div class="col-md-4 mb-2 mb-md-0">
                        <a href="javascript:void(0)" class="btn btn-block btn-success btn-md" data-target="#import-modal" data-toggle="modal">Upload Excel</a>
                      </div>
                      <div class="col-md-4">
                        <a href="javascript:void(0)" class="btn btn-block btn-primary btn-md" data-target="#add-modal" data-toggle="modal">Add Merchant</a>
                      </div>
                    </div>
                    <div class="table-responsive">
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
                        <tbody></tbody>
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
                  <div class="tab-pane fade" id="tab-duplicates" role="tabpanel">
                    <p class="text-muted mb-2">GSTIN matches first. Different GSTINs are never grouped. <strong>Adhoc / PO / Trading</strong> counts are clickable. Sync opens a dropdown to pick the merchant to merge into: empty fields copy, known-for is concatenated, and PO/trading IDs plus adhoc source names are moved. Delete only removes the extra merchant.</p>
                    <div class="form-inline flex-wrap mb-3">
                      <label class="mr-2 mb-2">Min name %</label>
                      <select id="dup-min-pct" class="form-control form-control-sm mr-2 mb-2" style="width:auto;">
                        <option value="40">40%</option>
                        <option value="50" selected>50%</option>
                        <option value="60">60%</option>
                        <option value="70">70%</option>
                      </select>
                      <input type="text" id="dup-search" class="form-control form-control-sm mr-2 mb-2" placeholder="Search name / GSTIN" style="min-width:220px;">
                      <button type="button" class="btn btn-sm btn-primary mb-2" id="dup-reload-btn">Find duplicates</button>
                      <span class="text-muted ml-2 mb-2" id="dup-count-label"></span>
                    </div>
                    <div id="dup-loading" class="text-muted d-none">Scanning merchants…</div>
                    <div id="dup-empty" class="alert alert-light border d-none">No duplicate groups found.</div>
                    <div id="dup-groups"></div>
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
        <p class="text-muted mb-2">Distinct adhoc source names that are not already in the merchant master. Matching merchant names are skipped. Rename updates every matching adhoc row. Insert selected creates merchant records using the source name only.</p>
        <div class="d-flex align-items-center justify-content-between flex-wrap mb-2">
          <span class="text-muted" id="adhoc-selected-count">0 selected</span>
          <div>
            <button type="button" class="btn btn-default btn-sm" id="adhoc-clear-selected-btn">Clear</button>
            <button type="button" class="btn btn-success btn-sm" id="insert-selected-btn" disabled>Insert selected</button>
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

<div class="modal fade" id="dup-adhoc-uses-modal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><span id="dup-uses-type-label">Adhoc uses</span> — <span id="dup-adhoc-uses-title"></span></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="text-muted mb-2" id="dup-adhoc-uses-summary"></p>
        <div id="dup-adhoc-uses-loading" class="text-muted">Loading…</div>
        <div class="table-responsive" id="dup-adhoc-uses-table-wrap" style="display:none;">
          <table class="table table-bordered table-striped table-sm mb-0">
            <thead>
              <tr id="dup-uses-head">
                <th>ID</th>
                <th>Item</th>
                <th class="text-right">Qty</th>
                <th>Unit</th>
                <th class="text-right">Rate</th>
                <th>Destination</th>
                <th>Received by</th>
                <th>Status</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody id="dup-adhoc-uses-body"></tbody>
          </table>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="dup-sync-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Sync merchant</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="dup-sync-from-id">
        <div class="form-group">
          <label>From</label>
          <input type="text" class="form-control" id="dup-sync-from-label" readonly>
        </div>
        <div class="form-group">
          <label for="dup-sync-keep-id">Sync into</label>
          <select class="form-control" id="dup-sync-keep-id"></select>
          <small class="form-text text-muted">Empty fields copy to this merchant. PO and trading IDs move here. Known-for is concatenated.</small>
        </div>
        <div class="form-group mb-0">
          <label>Known for after sync</label>
          <textarea class="form-control" id="dup-sync-known-preview" rows="3" readonly></textarea>
        </div>
        <p class="text-muted small mt-2 mb-0" id="dup-sync-move-hint"></p>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success" id="dup-sync-confirm-btn">Sync</button>
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

<div class="modal fade" id="import-modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Upload Merchant Excel</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="text-muted mb-2">Match Excel by <strong>Vendor Name</strong> first, then by <strong>GSTIN</strong> if the name is not found. Only empty merchant fields are filled from Excel. Existing values are not overwritten.</p>
        <p class="mb-3">
          <a href="{{ url('/master/merchant/import/template') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-download"></i> Download template
          </a>
        </p>
        <div class="form-group">
          <label for="merchant-excel-file">Excel file (.xlsx, .xls, .csv)</label>
          <input type="file" class="form-control-file" id="merchant-excel-file" accept=".xlsx,.xls,.csv">
        </div>
        <div class="small text-muted mb-2">
          Columns: Vendor Name, Address, City, State, Contact Person, Contact No., E.Mail, PAN, GSTIN.
          Match is by vendor/merchant name, then GSTIN. Empty Excel cells and NA are ignored. Filled database fields are left as they are.
        </div>
        <div id="merchant-import-result" class="d-none">
          <div class="alert alert-info mb-2" id="merchant-import-summary"></div>
          <pre id="merchant-import-errors" class="bg-light border rounded p-2 small" style="max-height:220px;overflow:auto;display:none;"></pre>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" id="merchant-import-btn">Upload</button>
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

      var dupGroupsCache = [];
      var dupLoaded = false;
      var dupMembersByGroup = {};

      function escHtml(v) {
        return $('<div>').text(v == null ? '' : String(v)).html();
      }

      function dashText(v) {
        var t = $.trim(v || '');
        return t === '' ? '—' : escHtml(t);
      }

      function tokenChips(tokens, overlap) {
        tokens = tokens || [];
        overlap = overlap || [];
        var hit = {};
        overlap.forEach(function(t){ hit[String(t).toUpperCase()] = true; });
        if (!tokens.length) return '<span class="text-muted">—</span>';
        return tokens.map(function(t){
          var cls = hit[String(t).toUpperCase()] ? ' dup-token is-overlap' : ' dup-token';
          return '<span class="' + cls + '">' + escHtml(t) + '</span>';
        }).join('');
      }

      function matchBadge(type, pct) {
        var cls = 'badge badge-secondary';
        if (String(type).indexOf('GSTIN') === 0) cls = 'badge badge-success';
        else if (type === 'Name') cls = 'badge badge-info';
        else if (type === 'Keep') cls = 'badge badge-primary';
        var extra = (type !== 'Keep' && pct) ? (' ' + pct + '%') : '';
        return '<span class="' + cls + '">' + escHtml(type) + extra + '</span>';
      }

      function usesBadge(count, type, id, name) {
        count = parseInt(count, 10) || 0;
        if (count < 1) {
          return '<span class="text-muted">0</span>';
        }
        return '<a href="javascript:void(0)" class="badge badge-primary dup-uses-btn" data-type="' + type + '" data-id="' + id + '" data-name="' + escHtml(name) + '">' + count + '</a>';
      }

      function groupKeepId($card) {
        var v = $card.find('.dup-keep-radio:checked').val();
        return parseInt(v, 10) || 0;
      }

      function renderDupGroups(groups) {
        var q = $.trim($('#dup-search').val() || '').toLowerCase();
        var html = '';
        var shown = 0;
        dupMembersByGroup = {};
        (groups || []).forEach(function(g, gi) {
          var members = g.members || [];
          var blob = members.map(function(m){ return (m.name || '') + ' ' + (m.gstin || ''); }).join(' ').toLowerCase();
          if (q && blob.indexOf(q) === -1) return;
          shown++;
          var slim = members.map(function(x){
            return {
              id: x.id,
              name: x.name,
              gstin: x.gstin || '',
              known_for: x.known_for || '',
              adhoc_uses: x.adhoc_uses || 0,
              po_uses: x.po_uses || 0,
              trading_uses: x.trading_uses || 0
            };
          });
          dupMembersByGroup[gi] = slim;
          html += '<div class="card dup-group-card mb-3" data-group="' + gi + '">';
          html += '<div class="card-header py-2 d-flex justify-content-between flex-wrap">';
          html += '<strong>Group of ' + members.length + '</strong>';
          html += '<span>' + matchBadge(g.match_type, 0) + (g.gstin ? (' <code>' + escHtml(g.gstin) + '</code>') : '') + '</span>';
          html += '</div><div class="card-body p-0"><div class="table-responsive"><table class="table table-sm mb-0">';
          html += '<thead><tr><th class="text-center">Keep</th><th>ID</th><th>Name / tokens</th><th>Match</th><th>City</th><th>Phone</th><th>GSTIN</th><th class="text-center">Adhoc</th><th class="text-center">PO</th><th class="text-center">Trading</th><th class="text-center">Actions</th></tr></thead><tbody>';
          members.forEach(function(m) {
            var rowCls = m.is_keep ? ' dup-keep-row' : '';
            html += '<tr class="' + rowCls + '" data-id="' + m.id + '">';
            html += '<td class="text-center"><input type="radio" class="dup-keep-radio" name="dup-keep-' + gi + '" value="' + m.id + '"' + (m.is_keep ? ' checked' : '') + '></td>';
            html += '<td>' + escHtml(m.id) + '</td>';
            html += '<td><div>' + escHtml(m.name) + '</div><div class="mt-1">' + tokenChips(m.tokens, m.overlap) + '</div></td>';
            html += '<td>' + matchBadge(m.match_type, m.match_pct) + '</td>';
            html += '<td>' + dashText(m.city) + '</td>';
            html += '<td>' + dashText(m.phone) + '</td>';
            html += '<td>' + dashText(m.gstin) + '</td>';
            html += '<td class="text-center">' + usesBadge(m.adhoc_uses, 'adhoc', m.id, m.name) + '</td>';
            html += '<td class="text-center">' + usesBadge(m.po_uses, 'po', m.id, m.name) + '</td>';
            html += '<td class="text-center">' + usesBadge(m.trading_uses, 'trading', m.id, m.name) + '</td>';
            html += '<td class="text-center text-nowrap">';
            html += '<button type="button" class="btn btn-success btn-sm dup-sync-btn mr-1" data-from="' + m.id + '">Sync</button>';
            if (!m.is_keep) {
              html += '<button type="button" class="btn btn-danger btn-sm dup-delete-btn" data-from="' + m.id + '">Delete</button>';
            }
            html += '</td></tr>';
          });
          html += '</tbody></table></div></div></div>';
        });
        $('#dup-groups').html(html);
        $('#dup-empty').toggleClass('d-none', shown !== 0);
        $('#dup-count-label').text(shown + ' group(s)' + (q ? ' matching search' : ''));
      }

      function loadDuplicates(force) {
        if (dupLoaded && !force) {
          renderDupGroups(dupGroupsCache);
          return;
        }
        $('#dup-loading').removeClass('d-none');
        $('#dup-empty').addClass('d-none');
        $('#dup-groups').empty();
        $('#dup-count-label').text('');
        $.ajax({
          type: 'get',
          url: "{{ url('/master/merchant/duplicates/list') }}",
          data: { min_pct: $('#dup-min-pct').val() || 50 },
          success: function(response) {
            $('#dup-loading').addClass('d-none');
            dupGroupsCache = (response && response.groups) ? response.groups : [];
            dupLoaded = true;
            renderDupGroups(dupGroupsCache);
          },
          error: function(xhr) {
            $('#dup-loading').addClass('d-none');
            swalSuccess('error', ajaxErrorMessage(xhr));
          }
        });
      }

      function afterDupChange() {
        dupLoaded = false;
        loadDuplicates(true);
        reloadTable();
        reloadAdhocSourcesTable();
      }

      $('a[data-toggle="pill"][href="#tab-duplicates"]').on('shown.bs.tab', function () {
        loadDuplicates(false);
      });

      $('a[data-toggle="pill"][href="#tab-merchants"]').on('shown.bs.tab', function () {
        if ($.fn.DataTable.isDataTable('#example1')) {
          $('#example1').DataTable().columns.adjust();
        }
      });

      $('#dup-reload-btn').on('click', function(){
        loadDuplicates(true);
      });

      $('#dup-min-pct').on('change', function(){
        loadDuplicates(true);
      });

      $('#dup-search').on('keyup', function(){
        renderDupGroups(dupGroupsCache);
      });

      $('body').delegate('.dup-keep-radio', 'change', function(){
        var $card = $(this).closest('.dup-group-card');
        var keepId = groupKeepId($card);
        $card.find('tbody tr').each(function(){
          var id = parseInt($(this).attr('data-id'), 10);
          var isKeep = id === keepId;
          $(this).toggleClass('dup-keep-row', isKeep);
          var $act = $(this).find('td:last');
          var html = '<button type="button" class="btn btn-success btn-sm dup-sync-btn mr-1" data-from="' + id + '">Sync</button>';
          if (!isKeep) {
            html += ' <button type="button" class="btn btn-danger btn-sm dup-delete-btn" data-from="' + id + '">Delete</button>';
          }
          $act.html(html);
        });
      });

      $('body').delegate('.dup-uses-btn', 'click', function(){
        var id = parseInt($(this).attr('data-id'), 10);
        var name = $(this).attr('data-name') || '';
        var type = $(this).attr('data-type') || 'adhoc';
        if (!id) return;
        var labels = { adhoc: 'Adhoc uses', po: 'PO uses', trading: 'Trading uses' };
        $('#dup-uses-type-label').text(labels[type] || 'Uses');
        $('#dup-adhoc-uses-title').text(name || ('#' + id));
        $('#dup-adhoc-uses-summary').text('');
        $('#dup-adhoc-uses-body').empty();
        $('#dup-adhoc-uses-table-wrap').hide();
        $('#dup-adhoc-uses-loading').show().text('Loading…');
        $('#dup-adhoc-uses-modal').modal('show');
        $.ajax({
          type: 'get',
          url: "{{ url('/master/merchant/duplicates/adhoc-uses') }}",
          data: { id: id, type: type },
          success: function(response) {
            $('#dup-adhoc-uses-loading').hide();
            var rows = (response && response.rows) ? response.rows : [];
            var total = (response && response.total) ? response.total : 0;
            var shown = (response && response.shown) ? response.shown : rows.length;
            var kind = (response && response.type) ? response.type : type;
            var summary = total + ' row(s).';
            if (shown < total) {
              summary += ' Showing first ' + shown + '.';
            }
            $('#dup-adhoc-uses-summary').text(summary);
            if (!rows.length) {
              $('#dup-adhoc-uses-loading').show().text('No rows found.');
              return;
            }
            var head = '';
            var body = '';
            if (kind === 'po') {
              head = '<th>ID</th><th>Date</th><th class="text-right">Value</th><th>Status</th><th>Req no</th>';
              rows.forEach(function(r) {
                body += '<tr><td>' + escHtml(r.id) + '</td><td>' + dashText(r.date) + '</td><td class="text-right">' + escHtml(r.value) + '</td><td>' + dashText(r.status) + '</td><td>' + dashText(r.ref) + '</td></tr>';
              });
            } else if (kind === 'trading') {
              head = '<th>ID</th><th>Date</th><th>Reference</th><th>Ref date</th><th>Remarks</th>';
              rows.forEach(function(r) {
                body += '<tr><td>' + escHtml(r.id) + '</td><td>' + dashText(r.date) + '</td><td>' + dashText(r.ref) + '</td><td>' + dashText(r.ref_date) + '</td><td>' + dashText(r.remarks) + '</td></tr>';
              });
            } else {
              head = '<th>ID</th><th>Item</th><th class="text-right">Qty</th><th>Unit</th><th class="text-right">Rate</th><th>Destination</th><th>Received by</th><th>Status</th><th>Date</th>';
              rows.forEach(function(r) {
                body += '<tr>';
                body += '<td>' + escHtml(r.id) + '</td>';
                body += '<td>' + dashText(r.item) + '</td>';
                body += '<td class="text-right">' + escHtml(r.qty) + '</td>';
                body += '<td>' + dashText(r.unit) + '</td>';
                body += '<td class="text-right">' + escHtml(r.rate) + '</td>';
                body += '<td>' + dashText(r.destination) + '</td>';
                body += '<td>' + dashText(r.received_by) + '</td>';
                body += '<td>' + dashText(r.status) + '</td>';
                body += '<td>' + dashText(r.date) + '</td>';
                body += '</tr>';
              });
            }
            $('#dup-uses-head').html(head);
            $('#dup-adhoc-uses-body').html(body);
            $('#dup-adhoc-uses-table-wrap').show();
          },
          error: function(xhr) {
            $('#dup-adhoc-uses-loading').text(ajaxErrorMessage(xhr));
          }
        });
      });

      function parseGroupMembers($card) {
        var gi = $card.attr('data-group');
        var cached = dupMembersByGroup[gi] || dupMembersByGroup[String(gi)];
        return Array.isArray(cached) ? cached : [];
      }

      function previewKnownFor(keepVal, fromVal) {
        var a = $.trim(keepVal || '');
        var b = $.trim(fromVal || '');
        if (!b || b.toUpperCase() === 'NA' || b === '-') return a;
        if (!a) return b;
        if (a.toLowerCase().indexOf(b.toLowerCase()) !== -1) return a;
        return a + ' | ' + b;
      }

      function refreshSyncPreview(members) {
        var fromId = parseInt($('#dup-sync-from-id').val(), 10);
        var keepId = parseInt($('#dup-sync-keep-id').val(), 10);
        var from = null;
        var keep = null;
        members.forEach(function(m) {
          if (m.id === fromId) from = m;
          if (m.id === keepId) keep = m;
        });
        $('#dup-sync-known-preview').val(previewKnownFor(keep ? keep.known_for : '', from ? from.known_for : ''));
        var hint = '';
        if (from) {
          hint = 'Will move Adhoc ' + (from.adhoc_uses || 0) + ', PO ' + (from.po_uses || 0) + ', Trading ' + (from.trading_uses || 0) + '.';
        }
        $('#dup-sync-move-hint').text(hint);
      }

      var dupSyncMembers = [];

      $('body').delegate('.dup-sync-btn', 'click', function(){
        var $card = $(this).closest('.dup-group-card');
        var fromId = parseInt($(this).attr('data-from'), 10);
        var members = parseGroupMembers($card);
        if (!fromId || !members.length) {
          swalSuccess('error', 'Could not load group members.');
          return;
        }
        dupSyncMembers = members;
        var from = null;
        members.forEach(function(m){ if (m.id === fromId) from = m; });
        if (!from) {
          swalSuccess('error', 'Merchant not found in this group.');
          return;
        }
        $('#dup-sync-from-id').val(fromId);
        $('#dup-sync-from-label').val('#' + from.id + ' — ' + from.name);
        var opts = '';
        var keepDefault = groupKeepId($card);
        members.forEach(function(m){
          if (m.id === fromId) return;
          var sel = (m.id === keepDefault) ? ' selected' : '';
          opts += '<option value="' + m.id + '"' + sel + '>#' + m.id + ' — ' + escHtml(m.name) + '</option>';
        });
        if (!opts) {
          swalSuccess('error', 'No other merchant in this group to sync into.');
          return;
        }
        $('#dup-sync-keep-id').html(opts);
        refreshSyncPreview(members);
        $('#dup-sync-modal').modal('show');
      });

      $('#dup-sync-keep-id').on('change', function(){
        refreshSyncPreview(dupSyncMembers);
      });

      $('#dup-sync-confirm-btn').on('click', function(){
        var keepId = parseInt($('#dup-sync-keep-id').val(), 10);
        var fromId = parseInt($('#dup-sync-from-id').val(), 10);
        if (!keepId || !fromId || keepId === fromId) {
          swalSuccess('error', 'Choose a different merchant to sync into.');
          return;
        }
        var $btn = $(this);
        $btn.prop('disabled', true).text('Syncing…');
        $.ajax({
          type: 'post',
          url: "{{ url('/master/merchant/duplicates/sync') }}",
          data: { keep_id: keepId, from_id: fromId, _token: "{{ csrf_token() }}" },
          success: function(response) {
            $btn.prop('disabled', false).text('Sync');
            if (response.success) {
              $('#dup-sync-modal').modal('hide');
              swalSuccess('success', response.message || 'Synced.');
              afterDupChange();
            } else {
              swalSuccess('error', response.message || 'Sync failed.');
            }
          },
          error: function(xhr) {
            $btn.prop('disabled', false).text('Sync');
            swalSuccess('error', ajaxErrorMessage(xhr));
          }
        });
      });

      $('body').delegate('.dup-delete-btn', 'click', function(){
        var $card = $(this).closest('.dup-group-card');
        var keepId = groupKeepId($card);
        var fromId = parseInt($(this).attr('data-from'), 10);
        if (!keepId || !fromId) return;
        Swal.fire({
          title: 'Delete duplicate?',
          text: 'Empty fields will copy to Keep, adhoc sources will be renamed, then this merchant will be deleted.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Delete',
          confirmButtonColor: '#dc3545'
        }).then(function(result) {
          if (!result.value && !result.isConfirmed) return;
          $.ajax({
            type: 'post',
            url: "{{ url('/master/merchant/duplicates/delete') }}",
            data: { keep_id: keepId, delete_id: fromId, _token: "{{ csrf_token() }}" },
            success: function(response) {
              if (response.success) {
                swalSuccess('success', response.message || 'Deleted.');
                afterDupChange();
              } else {
                swalSuccess('error', response.message || 'Delete failed.');
              }
            },
            error: function(xhr) {
              swalSuccess('error', ajaxErrorMessage(xhr));
            }
          });
        });
      });

      $('#import-modal').on('show.bs.modal', function () {
        $('#merchant-excel-file').val('');
        $('#merchant-import-result').addClass('d-none');
        $('#merchant-import-errors').hide().text('');
        $('#merchant-import-btn').prop('disabled', false).text('Upload');
      });

      $('#merchant-import-btn').on('click', function () {
        var fileInput = document.getElementById('merchant-excel-file');
        if (!fileInput || !fileInput.files || !fileInput.files[0]) {
          swalSuccess('error', 'Please choose an Excel file.');
          return;
        }
        var fd = new FormData();
        fd.append('file', fileInput.files[0]);
        fd.append('_token', "{{ csrf_token() }}");
        var $btn = $(this);
        $btn.prop('disabled', true).text('Uploading…');
        $.ajax({
          type: 'post',
          url: "{{ url('/master/merchant/import') }}",
          data: fd,
          processData: false,
          contentType: false,
          success: function (response) {
            $btn.prop('disabled', false).text('Upload');
            $('#merchant-import-result').removeClass('d-none');
            $('#merchant-import-summary').text(response.message || 'Import finished.');
            if (response.errors && response.errors.length) {
              $('#merchant-import-errors').show().text(response.errors.join('\n'));
            } else {
              $('#merchant-import-errors').hide().text('');
            }
            if (response.success) {
              reloadTable();
              swalSuccess('success', response.message || 'Import finished.');
            } else {
              swalSuccess('error', response.message || 'Import failed.');
            }
          },
          error: function (xhr) {
            $btn.prop('disabled', false).text('Upload');
            swalSuccess('error', ajaxErrorMessage(xhr));
          }
        });
      });

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
        $('#rename-selected-btn, #insert-selected-btn').prop('disabled', n < 1);
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

      function insertAdhocSourcesAsMerchants(names) {
        names = names || [];
        if (!names.length) {
          swalSuccess('error', 'Select at least one source.');
          return;
        }
        $.ajax({
          type: 'post',
          data: {
            names: names,
            _token: "{{ csrf_token() }}"
          },
          url: "{{ url('/master/merchant/adhoc-sources/insert') }}",
          success: function(response) {
            if (response.success == true) {
              selectedAdhocSources = {};
              reloadAdhocSourcesTable();
              reloadTable();
              swalSuccess('success', response.message || 'Merchants inserted.');
            } else {
              swalSuccess('error', response.message || 'Insert failed.');
            }
          },
          error: function(xhr) {
            swalSuccess('error', ajaxErrorMessage(xhr));
          }
        });
      }

      $('#insert-selected-btn').on('click', function(){
        insertAdhocSourcesAsMerchants(selectedAdhocList());
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
