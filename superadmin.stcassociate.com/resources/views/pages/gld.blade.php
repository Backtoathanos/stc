<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>STC Associates | {{ !empty($page_title) ? $page_title : '' }}</title>
  @include('layouts.head')
  <style>
    .gld-hero {
      background: linear-gradient(135deg, #1e3c72 0%, #2a5298 40%, #7e8ba3 100%);
      color: #fff;
      border-radius: 0.5rem;
      padding: 1.35rem 1.5rem;
      margin-bottom: 1rem;
      box-shadow: 0 0.35rem 1rem rgba(30, 60, 114, 0.25);
    }
    .gld-hero h1 { font-weight: 600; letter-spacing: 0.02em; margin: 0; font-size: 1.45rem; }
    .gld-hero p { margin: 0.35rem 0 0; opacity: 0.92; font-size: 0.9rem; }
    .gld-tabs-card { border: none; box-shadow: 0 0.25rem 0.75rem rgba(0,0,0,0.06); border-radius: 0.5rem; overflow: hidden; }
    .gld-tabs-card .nav-tabs { border-bottom: 1px solid rgba(0,0,0,0.06); padding: 0 0.75rem; background: #f8f9fc; }
    .gld-tabs-card .nav-link { border: none; border-radius: 0; padding: 0.85rem 1.25rem; font-weight: 600; color: #5c6b7a; }
    .gld-tabs-card .nav-link.active {
      color: #1e3c72;
      border-bottom: 3px solid #2a5298 !important;
      background: transparent;
    }
    #gld-table thead th {
      font-size: 0.78rem;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      color: #495057;
      white-space: nowrap;
      background: #f8f9fc;
    }
    #gld-table_wrapper .dataTables_filter input {
      border-radius: 2rem;
      padding: 0.35rem 1rem;
      border: 1px solid #dee2e6;
    }
    .modal-label { font-weight: 600; color: #495057; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.03em; }
    .view-kv dt { font-weight: 600; color: #6c757d; font-size: 0.82rem; }
    .view-kv dd { margin-bottom: 0.5rem; }
    #gld-table { font-size: 0.875rem; }
    .gld-adhoc-cell { line-height: 1.35; max-width: 220px; }
    .gld-adhoc-cell small { font-size: 0.72rem; }
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
            <h1 class="m-0 text-secondary">GLD</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="#">Branch</a></li>
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
          </div>
        </div>

        <div class="gld-hero">
          <h1><i class="fas fa-file-invoice mr-2"></i> Gold layer distribution</h1>
          <p>Manage GLD challans with linked product, customer, PO adhoc and requisition context.</p>
        </div>

        <div class="card gld-tabs-card">
          <div class="card-header p-0 border-0">
            <ul class="nav nav-tabs" id="gld-main-tabs" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="tab-gld-challan-link" data-toggle="tab" href="#tab-gld-challan" role="tab" aria-controls="tab-gld-challan" aria-selected="true">
                  <i class="fas fa-table mr-1"></i> GLD Challan
                </a>
              </li>
            </ul>
          </div>
          <div class="card-body tab-content pt-3">
            <div class="tab-pane fade show active" id="tab-gld-challan" role="tabpanel">
              <div class="row align-items-end mb-3">
                <div class="col-sm-6 col-md-auto mb-2 mb-md-0">
                  <label class="small text-muted mb-1 d-block font-weight-bold" for="gld-filter-created-by">Created by</label>
                  <select id="gld-filter-created-by" class="form-control form-control-sm border-secondary" style="min-width: 210px;">
                    <option value="">All creators</option>
                    @foreach($gld_filter_creators ?? [] as $fc)
                      <option value="{{ $fc->created_by }}">{{ $fc->stc_trading_user_name ?: ('User #' . $fc->created_by) }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-sm-6 col-md-auto mb-2 mb-md-0">
                  <label class="small text-muted mb-1 d-block font-weight-bold" for="gld-filter-rack">Rack</label>
                  <select id="gld-filter-rack" class="form-control form-control-sm border-secondary" style="min-width: 210px;">
                    <option value="">All racks</option>
                    @foreach($gld_filter_racks ?? [] as $rk)
                      <option value="{{ $rk->stc_rack_id }}">{{ $rk->stc_rack_name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-auto ml-md-auto mb-2 mb-md-0">
                  <button type="button" class="btn btn-outline-secondary btn-sm" id="gld-filter-clear" title="Clear filters">
                    <i class="fas fa-times"></i> Clear filters
                  </button>
                </div>
              </div>
              <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <div class="mb-2 mb-md-0">
                  <span class="text-muted small"><i class="fas fa-info-circle"></i> Search applies across challan, bill, names and amounts. Created by and Rack narrow results together (AND).</span>
                </div>
                <button type="button" class="btn btn-primary shadow-sm" id="gld-btn-create" data-toggle="modal" data-target="#gld-modal-form">
                  <i class="fas fa-plus-circle mr-1"></i> Create challan
                </button>
              </div>
              <div class="table-responsive border rounded">
                <table id="gld-table" class="table table-hover table-striped table-bordered mb-0" style="width:100%">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Product</th>
                      <th>P details</th>
                      <th>PO Adhoc</th>
                      <th>Customer</th>
                      <th>Requisition</th>
                      <th>Challan #</th>
                      <th>Bill #</th>
                      <th>PO product / rack</th>
                      <th>Qty</th>
                      <th>Rate</th>
                      <th>Discount</th>
                      <th>Paid</th>
                      <th>Pay status</th>
                      <th>Agent</th>
                      <th>Status</th>
                      <th>Created</th>
                      <th>Created by</th>
                      <th class="text-center">Actions</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  @include('layouts.footer')
</div>
@include('layouts.ajax_foot')

<!-- View modal -->
<div class="modal fade" id="gld-modal-view" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-light border-bottom">
        <h5 class="modal-title font-weight-bold"><i class="fas fa-eye text-primary mr-2"></i> Challan details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body" id="gld-view-body"></div>
      <div class="modal-footer bg-light">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Create / Edit modal -->
<div class="modal fade" id="gld-modal-form" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
    <div class="modal-content border-0 shadow">
      <div class="modal-header text-white" style="background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);">
        <h5 class="modal-title font-weight-bold" id="gld-modal-form-title"><i class="fas fa-edit mr-2"></i> Edit challan</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <form id="gld-form">
        @csrf
        <input type="hidden" name="id" id="gld-f-id" value="">
        <div class="modal-body">
          <div class="form-row">
            <div class="form-group col-md-6">
              <label class="modal-label" for="gld-f-product_id">Product</label>
              <div class="mb-1">
                <span id="gld-span-product-name" class="d-block text-truncate border rounded px-2 py-1 bg-light small" title="">—</span>
              </div>
              <input type="number" min="1" step="1" class="form-control" id="gld-f-product_id" name="product_id" inputmode="numeric" placeholder="Product ID" required>
            </div>
            <div class="form-group col-md-6">
              <label class="modal-label" for="gld-f-adhoc_id">PO Adhoc</label>
              <div class="mb-1">
                <span id="gld-span-adhoc-label" class="d-block text-truncate border rounded px-2 py-1 bg-light small" title="">—</span>
              </div>
              <input type="number" min="0" step="1" class="form-control" id="gld-f-adhoc_id" name="adhoc_id" inputmode="numeric" placeholder="Adhoc ID (0 = none)" value="0">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-12">
              <label class="modal-label" for="gld-f-pdetails">P details</label>
              <input type="text" class="form-control" id="gld-f-pdetails" name="pdetails" maxlength="255" autocomplete="off" placeholder="e.g. IDU - 123456 & ODU - 123456 / Sl No - 123456">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label class="modal-label" for="gld-f-cust_id">Customer</label>
              <select class="form-control" id="gld-f-cust_id" name="cust_id" required>
                <option value="">Select customer</option>
                @foreach($customers as $c)
                  <option value="{{ $c->gld_customer_id }}">{{ $c->gld_customer_title }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-md-6">
              <label class="modal-label" for="gld-f-requisition_id">Requisition</label>
              <select class="form-control" id="gld-f-requisition_id" name="requisition_id">
                <option value="0">— None —</option>
                @foreach($requisitions as $r)
                  <option value="{{ $r->stc_cust_super_requisition_list_id }}">#{{ $r->stc_cust_super_requisition_list_id }} @if($r->stc_cust_super_requisition_list_sdlid) (SDL {{ $r->stc_cust_super_requisition_list_sdlid }}) @endif</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label class="modal-label" for="gld-f-challan_number">Challan number</label>
              <input type="text" class="form-control" id="gld-f-challan_number" name="challan_number" autocomplete="off">
            </div>
            <div class="form-group col-md-6">
              <label class="modal-label" for="gld-f-bill_number">Bill number</label>
              <input type="text" class="form-control" id="gld-f-bill_number" name="bill_number" autocomplete="off">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-4">
              <label class="modal-label" for="gld-f-qty">Qty</label>
              <input type="number" step="0.01" class="form-control" id="gld-f-qty" name="qty" required>
            </div>
            <div class="form-group col-md-4">
              <label class="modal-label" for="gld-f-rate">Rate</label>
              <input type="number" step="0.01" class="form-control" id="gld-f-rate" name="rate">
            </div>
            <div class="form-group col-md-4">
              <label class="modal-label" for="gld-f-discount">Discount</label>
              <input type="number" step="0.01" class="form-control" id="gld-f-discount" name="discount">
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-4">
              <label class="modal-label" for="gld-f-paid_amount">Paid amount</label>
              <input type="number" step="0.01" class="form-control" id="gld-f-paid_amount" name="paid_amount">
            </div>
            <div class="form-group col-md-4">
              <label class="modal-label" for="gld-f-payment_status">Payment status</label>
              <select class="form-control" id="gld-f-payment_status" name="payment_status">
                <option value="">— Select —</option>
                <option value="1">Credit</option>
                <option value="2">Cash</option>
                <option value="3">Bank transfer</option>
                <option value="4">UPI</option>
              </select>
            </div>
            <div class="form-group col-md-4">
              <label class="modal-label" for="gld-f-status">Record status</label>
              <select class="form-control" id="gld-f-status" name="status">
                <option value="0">0 — Pending / draft</option>
                <option value="1">1 — Challan stage</option>
                <option value="2">2 — Intermediate</option>
                <option value="3">3 — Billed / closed</option>
              </select>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <label class="modal-label" for="gld-f-agent_id">Agent</label>
              <select class="form-control" id="gld-f-agent_id" name="agent_id">
                <option value="0">— None —</option>
                @foreach($tradingUsers as $u)
                  <option value="{{ $u->stc_trading_user_id }}">{{ $u->stc_trading_user_name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-md-6" id="gld-wrap-created">
              <label class="modal-label" for="gld-f-created_by">Created by (trading user)</label>
              <select class="form-control" id="gld-f-created_by" name="created_by">
                @foreach($tradingUsers as $u)
                  <option value="{{ $u->stc_trading_user_id }}">{{ $u->stc_trading_user_name }}</option>
                @endforeach
              </select>
              <small class="text-muted">Shown only when creating a new challan.</small>
            </div>
          </div>
          <div class="form-group mb-0" id="gld-wrap-created-date" style="display:none;">
            <label class="modal-label">Created date (read-only)</label>
            <input type="text" class="form-control" id="gld-f-created_date_ro" readonly disabled>
          </div>
        </div>
        <div class="modal-footer bg-light">
          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" id="gld-form-submit"><i class="fas fa-save mr-1"></i> Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  window.GLD_LOOKUP = {
    products: @json($gld_product_names ?? []),
    adhocs: @json($gld_adhoc_labels ?? [])
  };
</script>
<script>
$(function () {
  function swalToast(icon, title) {
    var Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3200
    });
    Toast.fire({ icon: icon, title: title });
  }

  var table = $('#gld-table').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    pageLength: 15,
    lengthMenu: [[15, 25, 50, 100], [15, 25, 50, 100]],
    order: [[0, 'desc']],
    ajax: {
      url: "{{ url('/branch/stc/gld/list') }}",
      type: 'POST',
      data: function (d) {
        d._token = "{{ csrf_token() }}";
        d.filter_created_by = $('#gld-filter-created-by').val() || '';
        d.filter_rack_id = $('#gld-filter-rack').val() || '';
      }
    },
    columns: [
      { data: 'id', width: '52px' },
      { data: 'product_label', orderable: true, searchable: false },
      { data: 'pdetails', orderable: true, searchable: true },
      { data: 'adhoc_label', orderable: true, searchable: false },
      { data: 'customer_label', orderable: true, searchable: false },
      { data: 'requisition_label', orderable: true, searchable: false },
      { data: 'challan_number' },
      { data: 'bill_number' },
      { data: 'adhoc_product_rack', orderable: true, searchable: false },
      { data: 'qty' },
      { data: 'rate' },
      { data: 'discount' },
      { data: 'paid_amount' },
      { data: 'payment_status_label' },
      { data: 'agent_label', orderable: true, searchable: false },
      { data: 'status_badge', orderable: true, searchable: false },
      { data: 'created_date_fmt' },
      { data: 'creator_label', orderable: true, searchable: false },
      { data: 'actionData', orderable: false, searchable: false, className: 'text-center' }
    ],
    columnDefs: [
      { targets: [0, 6, 7, 9, 10, 11, 12, 16], className: 'align-middle text-center' },
      { targets: [1, 2, 3, 4, 5, 8, 14, 15, 17, 18], className: 'align-middle text-center', render: function (d) { return d; } },
      { targets: [13], className: 'align-middle' }
    ],
    dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
      "<'row'<'col-sm-12'tr>>" +
      "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    language: {
      search: '_INPUT_',
      searchPlaceholder: 'Search records…'
    }
  });

  function gldLoadRackOptions(createdBy, after) {
    $.get("{{ url('/branch/stc/gld/filter-racks') }}", { created_by: createdBy == null ? '' : createdBy }, function (res) {
      if (!res || !res.success) {
        if (after) {
          after();
        }
        return;
      }
      var $rack = $('#gld-filter-rack');
      var keep = $rack.val();
      $rack.empty();
      $rack.append($('<option></option>').val('').text('All racks'));
      $.each(res.data || [], function (_, rk) {
        $rack.append(
          $('<option></option>').val(rk.stc_rack_id).text(rk.stc_rack_name)
        );
      });
      if (keep && $rack.find('option[value="' + String(keep) + '"]').length) {
        $rack.val(keep);
      } else {
        $rack.val('');
      }
      if (after) {
        after();
      }
    }).fail(function () {
      if (after) {
        after();
      }
    });
  }

  $('#gld-filter-rack').on('change', function () {
    table.ajax.reload();
  });

  $('#gld-filter-created-by').on('change', function () {
    gldLoadRackOptions($(this).val(), function () {
      table.ajax.reload();
    });
  });

  $('#gld-filter-clear').on('click', function () {
    $('#gld-filter-created-by').val('');
    gldLoadRackOptions('', function () {
      table.ajax.reload();
    });
  });

  function lookupProductLabel(idNum) {
    var k = String(idNum);
    var m = GLD_LOOKUP.products || {};
    return m[k] != null ? m[k] : '(not in cached list)';
  }

  function lookupAdhocLabel(idNum) {
    var k = String(idNum);
    var m = GLD_LOOKUP.adhocs || {};
    return m[k] != null ? m[k] : ('#' + idNum + ' (not in cached list)');
  }

  function refreshGldProductSpan() {
    var raw = $('#gld-f-product_id').val();
    var id = parseInt(raw, 10);
    var $span = $('#gld-span-product-name');
    if (raw === '' || raw === null || isNaN(id) || id < 1) {
      $span.text('—').attr('title', '');
      return;
    }
    var t = lookupProductLabel(id);
    $span.text(t).attr('title', t);
  }

  function refreshGldAdhocSpan() {
    var raw = $('#gld-f-adhoc_id').val();
    var $span = $('#gld-span-adhoc-label');
    if (raw === '' || raw === null) {
      $span.text('—').attr('title', '');
      return;
    }
    var id = parseInt(raw, 10);
    if (isNaN(id)) {
      $span.text('—').attr('title', '');
      return;
    }
    if (id === 0) {
      $span.text('None').attr('title', 'No PO adhoc line');
      return;
    }
    var t = lookupAdhocLabel(id);
    $span.text(t).attr('title', t);
  }

  $('#gld-f-product_id').on('input change blur', refreshGldProductSpan);
  $('#gld-f-adhoc_id').on('input change blur', refreshGldAdhocSpan);

  function resetForm() {
    $('#gld-f-payment_status .gld-legacy-opt').remove();
    $('#gld-form')[0].reset();
    $('#gld-f-id').val('');
    $('#gld-f-product_id').val('');
    $('#gld-f-adhoc_id').val('0');
    $('#gld-f-pdetails').val('');
    $('#gld-span-product-name').text('—').attr('title', '');
    $('#gld-span-adhoc-label').text('—').attr('title', '');
    $('#gld-modal-form-title').html('<i class="fas fa-plus-circle mr-2"></i> New challan');
    $('#gld-wrap-created').show();
    $('#gld-f-created_by').prop('required', true);
    $('#gld-wrap-created-date').hide();
    refreshGldProductSpan();
    refreshGldAdhocSpan();
  }

  function fillForm(d) {
    $('#gld-f-id').val(d.id);
    $('#gld-f-product_id').val(d.product_id != null && d.product_id !== '' ? String(d.product_id) : '');
    $('#gld-f-adhoc_id').val(d.adhoc_id != null && d.adhoc_id !== '' ? String(d.adhoc_id) : '0');
    $('#gld-f-pdetails').val(d.pdetails || '');
    if (d.stc_product_name) {
      $('#gld-span-product-name').text(d.stc_product_name).attr('title', d.stc_product_name);
    } else {
      refreshGldProductSpan();
    }
    if (d.adhoc_id && parseInt(d.adhoc_id, 10) > 0 && d.adhoc_itemdesc) {
      var adhocShow = '#' + d.adhoc_id + ' — ' + d.adhoc_itemdesc;
      $('#gld-span-adhoc-label').text(adhocShow).attr('title', adhocShow);
    } else {
      refreshGldAdhocSpan();
    }
    $('#gld-f-cust_id').val(String(d.cust_id));
    $('#gld-f-requisition_id').val(String(d.requisition_id || 0));
    $('#gld-f-challan_number').val(d.challan_number || '');
    $('#gld-f-bill_number').val(d.bill_number || '');
    $('#gld-f-qty').val(d.qty);
    $('#gld-f-rate').val(d.rate);
    $('#gld-f-discount').val(d.discount);
    $('#gld-f-paid_amount').val(d.paid_amount);
    var $ps = $('#gld-f-payment_status');
    $ps.find('.gld-legacy-opt').remove();
    var psVal = d.payment_status != null ? String(d.payment_status) : '';
    $ps.val(psVal);
    if (psVal && $ps.val() !== psVal) {
      $ps.append($('<option class="gld-legacy-opt" />').val(psVal).text(psVal + ' (other)'));
      $ps.val(psVal);
    }
    $('#gld-f-status').val(String(d.status != null ? d.status : 0));
    $('#gld-f-agent_id').val(String(d.agent_id || 0));
    if (d.created_date) {
      $('#gld-f-created_date_ro').val(d.created_date);
      $('#gld-wrap-created-date').show();
    } else {
      $('#gld-wrap-created-date').hide();
    }
  }

  function renderViewHtml(d) {
    var projHtml = d.project_title ? ' · Project: ' + $('<div/>').text(d.project_title).html() : '';
    var sdlHtml = d.req_sdlid ? ' · SDL: ' + $('<div/>').text(d.req_sdlid).html() : '';
    var rows = [
      ['ID', d.id],
      ['Product', (d.stc_product_name || '—') + ' <span class="text-muted">(product_id: ' + d.product_id + ')</span>'],
      ['P details', d.pdetails ? $('<div/>').text(d.pdetails).html() : '—'],
      ['PO Adhoc', (d.adhoc_itemdesc || '—') + ' <span class="text-muted">(adhoc_id: ' + d.adhoc_id + ')</span>'],
      ['Customer', (d.gld_customer_title || '—') + ' <span class="text-muted">(cust_id: ' + d.cust_id + ')</span>'],
      ['Requisition', 'ID: ' + (d.requisition_id || '0') + sdlHtml + projHtml],
      ['Challan #', d.challan_number || '—'],
      ['Bill #', d.bill_number || '—'],
      ['PO product (adhoc line)', (function () {
        var name = d.adhoc_line_product_name || d.adhoc_itemdesc || '—';
        var nid = $('<div/>').text(name).html();
        var rack = d.adhoc_rack_name ? $('<div/>').text(d.adhoc_rack_name).html() : '—';
        return nid + '<br><small class="text-muted">Product ID: ' + (d.adhoc_line_product_id != null ? d.adhoc_line_product_id : '—')
          + ' · Adhoc ID: ' + (d.adhoc_id != null ? d.adhoc_id : '—')
          + '<br>Rack: ' + rack + '</small>';
      })()],
      ['Qty', d.qty],
      ['Rate', d.rate],
      ['Discount', d.discount],
      ['Paid amount', d.paid_amount],
      ['Payment status', d.payment_status_label || '—'],
      ['Agent', (d.agent_name || '—') + (d.agent_id ? ' <span class="text-muted">(id: ' + d.agent_id + ')</span>' : '')],
      ['Status code', d.status],
      ['Created date', d.created_date || '—'],
      ['Created by', (d.creator_name || '—') + ' <span class="text-muted">(user id: ' + d.created_by + ')</span>']
    ];
    var html = '<dl class="row view-kv mb-0">';
    rows.forEach(function (pair) {
      html += '<dt class="col-sm-4">' + pair[0] + '</dt><dd class="col-sm-8">' + pair[1] + '</dd>';
    });
    html += '</dl>';
    return html;
  }

  $('#gld-modal-form').on('show.bs.modal', function (e) {
    var $trg = $(e.relatedTarget);
    if ($trg.length && $trg.is('#gld-btn-create')) {
      resetForm();
    }
  });

  $('body').on('click', '.gld-view-btn', function () {
    var id = $(this).data('id');
    $.get("{{ url('/branch/stc/gld/get') }}", { id: id }, function (res) {
      if (res.success && res.data) {
        $('#gld-view-body').html(renderViewHtml(res.data));
        $('#gld-modal-view').modal('show');
      } else {
        swalToast('error', res.message || 'Could not load record.');
      }
    });
  });

  $('body').on('click', '.gld-edit-btn', function () {
    var id = $(this).data('id');
    $.get("{{ url('/branch/stc/gld/get') }}", { id: id }, function (res) {
      if (res.success && res.data) {
        $('#gld-modal-form-title').html('<i class="fas fa-edit mr-2"></i> Edit challan #' + id);
        $('#gld-wrap-created').hide();
        $('#gld-f-created_by').prop('required', false);
        fillForm(res.data);
        $('#gld-modal-form').modal('show');
      } else {
        swalToast('error', res.message || 'Could not load record.');
      }
    });
  });

  $('body').on('click', '.gld-delete-btn', function () {
    var id = $(this).data('id');
    Swal.fire({
      title: 'Delete this challan?',
      text: 'You will not be able to undo this action.',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Yes, delete',
      cancelButtonText: 'Cancel'
    }).then(function (result) {
      if (!result.isConfirmed) return;
      $.ajax({
        type: 'GET',
        url: "{{ url('/branch/stc/gld/delete') }}",
        data: { id: id },
        success: function (response) {
          if (response.success) {
            Swal.fire({ icon: 'success', title: response.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 2800 });
            table.ajax.reload(null, false);
          } else {
            swalToast('error', response.message || 'Delete failed.');
          }
        },
        error: function () {
          swalToast('error', 'Request failed.');
        }
      });
    });
  });

  $('#gld-form').on('submit', function (e) {
    e.preventDefault();
    var id = $('#gld-f-id').val();
    var url = id
      ? "{{ url('/branch/stc/gld/edit') }}"
      : "{{ url('/branch/stc/gld/store') }}";
    var payload = $(this).serializeArray();
    $.ajax({
      type: 'POST',
      url: url,
      data: $.param(payload),
      success: function (response) {
        if (response.success) {
          $('#gld-modal-form').modal('hide');
          Swal.fire({ icon: 'success', title: response.message, toast: true, position: 'top-end', showConfirmButton: false, timer: 2800 });
          table.ajax.reload(null, false);
        } else {
          swalToast('error', response.message || 'Save failed.');
        }
      },
      error: function (xhr) {
        var msg = 'Save failed.';
        if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
        else if (xhr.responseJSON && xhr.responseJSON.errors) {
          msg = Object.values(xhr.responseJSON.errors).flat().join(' ');
        }
        swalToast('error', msg);
      }
    });
  });

  $('#gld-modal-form').on('hidden.bs.modal', function () {
    resetForm();
  });
});
</script>
</body>
</html>
