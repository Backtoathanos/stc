<?php
require_once 'kattegat/auth_helper.php';

STCAuthHelper::checkAuth();
$page_code = 601;
include("kattegat/role_check.php");
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta http-equiv="Content-Language" content="en">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Daily Requisition - STC</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
  <meta name="description" content="">
  <meta name="msapplication-tap-highlight" content="no">
  <link rel="icon" type="image/png" href="images/stc_logo_title.png">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="./main.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
  <style>
    .dr-col-project {
      width: 130px;
      max-width: 150px;
      white-space: normal !important;
      overflow-wrap: anywhere;
      word-break: break-word;
    }
  </style>
</head>

<body>
  <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
    <?php include_once("header-nav.php"); ?>
    <div class="app-main">
      <?php include_once("sidebar-nav.php"); ?>
      <div class="app-main__outer">
        <div class="app-main__inner">
          <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">
              <div class="card-border mb-3 card card-body border-success">
                <h5 align="center">Daily Requisition</h5>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
              <div class="card-border mb-3 card card-body border-success" style="overflow-x: auto; white-space: nowrap;">
                <div class="row" style="margin-bottom: 10px; white-space: normal;">
                  <div class="col-md-3 col-sm-6" style="margin-bottom: 6px;">
                    <label style="display:block; position:static; margin:0 0 4px; font-weight:600;">From</label>
                    <input type="date" class="form-control" id="dr-datefrom">
                  </div>
                  <div class="col-md-3 col-sm-6" style="margin-bottom: 6px;">
                    <label style="display:block; position:static; margin:0 0 4px; font-weight:600;">To</label>
                    <input type="date" class="form-control" id="dr-dateto">
                  </div>
                  <div class="col-md-4 col-sm-8" style="margin-bottom: 6px;">
                    <label style="display:block; position:static; margin:0 0 4px; font-weight:600;">Search</label>
                    <input type="text" class="form-control" id="dr-search" placeholder="Project, supervisor, req#, item...">
                  </div>
                  <div class="col-md-2 col-sm-4" style="margin-bottom: 6px;">
                    <label style="display:block; position:static; margin:0 0 4px; font-weight:600;">&nbsp;</label>
                    <button class="btn btn-primary form-control" id="dr-search-btn">Search</button>
                  </div>
                  <div class="col-md-2 col-sm-4" style="margin-bottom: 6px;">
                    <label style="display:block; position:static; margin:0 0 4px; font-weight:600;">&nbsp;</label>
                    <a class="btn btn-warning form-control" href="verify.php" target="_blank">Verify</a>
                  </div>
                </div>
                <table class="table table-hover table-bordered">
                  <thead>
                    <tr>
                    <th class="text-center">Sl No</th>
                      <th class="text-center dr-col-project">PR Name <br> PR No <br> Date &amp; Time</th>
                      <th class="text-center dr-col-project">Project Name &amp; Managers</th>
                      <th class="text-center dr-col-project">Requisition Sent By &amp; Number &amp; Date &amp; Time</th>
                      <th class="text-center dr-col-project">Item Desc</th>
                      <th class="text-center">Unit</th>
                      <th class="text-center">Req Qty</th>
                      <th class="text-center">Dispatched Qty</th>
                      <th class="text-center">Status</th>
                      <th class="text-center">Item Type</th>
                      <th class="text-center">Action</th>
                      <th class="text-center">Log</th>
                    </tr>
                  </thead>
                  <tbody class="stc-daily-requisition-body">
                    <tr>
                      <td colspan="13" class="text-center">Loading...</td>
                    </tr>
                  </tbody>
                </table>
                <div class="stc-daily-requisition-pagination"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Logs modal -->
  <div class="modal fade" id="dailyReqLogsModal" tabindex="-1" role="dialog" aria-labelledby="dailyReqLogsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="dailyReqLogsModalLabel">Requisition Logs</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body stc-daily-req-logs-body">
          Loading...
        </div>
      </div>
    </div>
  </div>

  <!-- Balance modal -->
  <div class="modal fade" id="dailyReqBalanceModal" tabindex="-1" role="dialog" aria-labelledby="dailyReqBalanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="dailyReqBalanceModalLabel">Adhoc Balance</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div style="overflow-x:auto;">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th class="text-center">Product</th>
                  <th class="text-center">Unit</th>
                  <th class="text-center">Requisition Balance Qty</th>
                  <th class="text-center">Adhoc Balance</th>
                  <th class="text-center">Action</th>
                </tr>
              </thead>
              <tbody class="stc-daily-req-balance-body">
                <tr><td colspan="5" class="text-center">Loading...</td></tr>
              </tbody>
            </table>
          </div>

          <div class="dr-add-itemcode-form" style="display:none;margin-top:10px; white-space: normal;">
            <div class="row" style="white-space: normal;">
              <div class="col-md-6 col-sm-8" style="margin-bottom:6px;">
                <label style="display:block; position:static; margin:0 0 4px; font-weight:600;" id="dr-itemcode-label">Item Code (Product ID)</label>
                <input type="number" min="1" step="1" inputmode="numeric" class="form-control" id="dr-itemcode-input" placeholder="Enter product id (integer only)">
                <input type="hidden" id="dr-itemcode-itemid" value="">
                <input type="hidden" id="dr-itemcode-oldproductid" value="0">
              </div>
              <div class="col-md-3 col-sm-4" style="margin-bottom:6px;">
                <label style="display:block; position:static; margin:0 0 4px; font-weight:600;">&nbsp;</label>
                <button type="button" class="btn btn-success form-control" id="dr-itemcode-save-btn">Save</button>
              </div>
              <div class="col-md-3 col-sm-4" style="margin-bottom:6px;">
                <label style="display:block; position:static; margin:0 0 4px; font-weight:600;">&nbsp;</label>
                <button type="button" class="btn btn-default form-control" id="dr-itemcode-cancel-btn">Cancel</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
  <script type="text/javascript" src="./assets/scripts/loginopr.js"></script>
  <script type="text/javascript" src="./assets/scripts/main.js"></script>
  <script type="text/javascript" src="./assets/scripts/jarvis.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    $(document).ready(function () {
      var currentPage = 1;
      var perPage = 25;

      function showSwal(icon, title, text) {
        if (typeof Swal !== 'undefined' && Swal && Swal.fire) {
          return Swal.fire({
            icon: icon || 'info',
            title: title || '',
            text: text || '',
            confirmButtonText: 'OK'
          });
        }
        alert((title ? title + '\n' : '') + (text || ''));
        return Promise.resolve();
      }

      function escapeHtml(str) {
        if (str === null || str === undefined) return '';
        return String(str)
          .replace(/&/g, '&amp;')
          .replace(/</g, '&lt;')
          .replace(/>/g, '&gt;')
          .replace(/"/g, '&quot;')
          .replace(/'/g, '&#039;');
      }

      function parseNumber(val) {
        if (val === null || val === undefined) return 0;
        // handles "1,234.50" and "1234.50"
        var n = parseFloat(String(val).replace(/,/g, ''));
        return isNaN(n) ? 0 : n;
      }

      function statusBadge(statusCode, statusText) {
        var bg = '#6c757d', color = '#fff';
        switch (parseInt(statusCode, 10)) {
          case 1: bg = '#3498db'; break; // Ordered
          case 2: bg = '#2ecc71'; break; // Approved
          case 3: bg = '#27ae60'; break; // Accepted
          case 4: bg = '#f39c12'; break; // Dispatched
          case 5: bg = '#16a085'; break; // Received
          case 6: bg = '#e74c3c'; break; // Rejected
          case 7: bg = '#95a5a6'; break; // Canceled
          case 8: bg = '#9b59b6'; break; // Returned
          case 9: bg = 'rgb(255, 47, 47)'; break; // Pending
          default: bg = '#34495e';
        }
        return '<span class="badge" style="background-color:' + bg + ';color:' + color + ';padding: 2px 6px;border-radius: 3px;">' + escapeHtml(statusText) + '</span>';
      }

      function renderPagination(totalPages, page) {
        var html = '';
        if (!totalPages || totalPages <= 1) {
          $('.stc-daily-requisition-pagination').html('');
          return;
        }
        for (var i = 1; i <= totalPages; i++) {
          if (i === page) {
            html += '<span class="btn btn-sm btn-success" style="margin:2px;">' + i + '</span>';
          } else {
            html += '<a href="javascript:void(0)" class="btn btn-sm btn-default stc-daily-req-page" style="margin:2px;" data-page="' + i + '">' + i + '</a>';
          }
        }
        $('.stc-daily-requisition-pagination').html(html);
      }

      function loadDailyRequisitions(search, page) {
        $('.stc-daily-requisition-body').html('<tr><td colspan="13" class="text-center">Loading...</td></tr>');
        var datefrom = $('#dr-datefrom').val() || '';
        var dateto = $('#dr-dateto').val() || '';
        $.ajax({
          url: 'kattegat/ragnar_order.php',
          method: 'POST',
          data: {
            stc_call_daily_requisitions: 1,
            search: search || '',
            page: page || 1,
            limit: perPage,
            datefrom: datefrom,
            dateto: dateto
          },
          dataType: 'json',
          success: function (response) {
            if (response && response.reload) {
              window.location.reload();
              return;
            }

            var rows = '';
            if (response && response.data && response.data.length > 0) {
              var slno = (parseInt(response.page || 1, 10) - 1) * perPage;
              response.data.forEach(function (item) {
                slno++;
                var prCell = '<div><b>' + escapeHtml(item.combiner_reference || '-') + '</b></div>' +
                  '<div style="font-size: 12px; color: #555;">PR# ' + escapeHtml(item.combiner_id || '-') + '</div>' +
                  '<div style="font-size: 12px; color: #555;">' + escapeHtml(item.combiner_date) + '</div>';

                var projectAndManager = '<div><b>' + escapeHtml(item.project_name) + '</b></div>' +
                  '<div style="font-size: 12px; color: #555;">' + escapeHtml(item.manager_name) + '</div>';

                var sentByAndNumber = '<div><b>' + escapeHtml(item.sent_by_name) + '</b> <span style="font-size: 12px; color: #555;">(' + escapeHtml(item.sent_by_contact) + ')</span></div>' +
                  '<div style="font-size: 12px; color: #555;">Req# ' + escapeHtml(item.requisition_number) + '</div>' +
                  '<div style="font-size: 12px; color: #555;">' + escapeHtml(item.date_time) + '</div>';

                var logsBtn = '';
                if (parseInt(item.logs_count, 10) > 0) {
                  logsBtn = '<button type="button" class="btn btn-info btn-sm stc-daily-req-log-btn" data-item-id="' + escapeHtml(item.item_id) + '" data-toggle="modal" data-target="#dailyReqLogsModal">View (' + escapeHtml(item.logs_count) + ')</button>';
                } else {
                  logsBtn = '<span class="text-muted">-</span>';
                }

                var reqQty = parseNumber(item.req_qty);
                var dispQty = parseNumber(item.dispatched_qty);
                var pendingQty = reqQty - dispQty;
                var actionHtml = '<button type="button" class="btn btn-primary btn-sm dr-balance-btn" data-item-id="' + escapeHtml(item.item_id) + '" data-toggle="modal" data-target="#dailyReqBalanceModal" title="Edit / View Adhoc Balance"><i class="fa fa-edit"></i></button>';
                if (pendingQty <= 0.0001) {
                  actionHtml = '<span class="text-muted">-</span>';
                }

                rows += '<tr>' +
                  '<td class="text-center">' + escapeHtml(slno) + '</td>' +
                  '<td class="text-center dr-col-project">' + prCell + '</td>' +
                  '<td class="dr-col-project">' + projectAndManager + '</td>' +
                  '<td class="dr-col-project">' + sentByAndNumber + '</td>' +
                  '<td class="dr-col-project">' + escapeHtml(item.item_desc) + '</td>' +
                  '<td class="text-center">' + escapeHtml(item.unit) + '</td>' +
                  '<td class="text-right">' + escapeHtml(item.req_qty) + '</td>' +
                  '<td class="text-right">' + escapeHtml(item.dispatched_qty) + '</td>' +
                  '<td class="text-center">' + statusBadge(item.status_code, item.status_text) + '</td>' +
                  '<td class="text-center">' + escapeHtml(item.item_type) + '</td>' +
                  '<td class="text-center">' + actionHtml + '</td>' +
                  '<td class="text-center">' + logsBtn + '</td>' +
                  '</tr>';
              });
            } else {
              rows = '<tr><td colspan="13" class="text-center">No record found.</td></tr>';
            }
            $('.stc-daily-requisition-body').html(rows);
            renderPagination(parseInt(response.total_pages || 0, 10), parseInt(response.page || 1, 10));
          },
          error: function () {
            $('.stc-daily-requisition-body').html('<tr><td colspan="13" class="text-center">Error loading data.</td></tr>');
          }
        });
      }

      $('body').delegate('.stc-daily-req-page', 'click', function (e) {
        e.preventDefault();
        currentPage = parseInt($(this).data('page'), 10) || 1;
        loadDailyRequisitions($('#dr-search').val() || '', currentPage);
      });

      function runSearch() {
        currentPage = 1;
        loadDailyRequisitions($('#dr-search').val() || '', currentPage);
      }

      $('#dr-search-btn').on('click', function (e) {
        e.preventDefault();
        runSearch();
      });

      // $('#dr-search').on('keydown', function (e) {
      //   if (e.keyCode === 13) {
      //     e.preventDefault();
      //     runSearch();
      //   }
      // });

      // $('#dr-datefrom, #dr-dateto').on('change', function () {
      //   runSearch();
      // });

      $('body').delegate('.stc-daily-req-log-btn', 'click', function () {
        var itemId = $(this).data('item-id');
        $('.stc-daily-req-logs-body').html('Loading...');
        $.ajax({
          url: 'kattegat/ragnar_order.php',
          method: 'POST',
          data: { stc_call_daily_requisition_logs: 1, item_id: itemId },
          dataType: 'json',
          success: function (response) {
            if (response && response.reload) {
              window.location.reload();
              return;
            }
            var html = '';
            if (response && response.data && response.data.length > 0) {
              response.data.forEach(function (log) {
                var safeMessage = escapeHtml(log.message).replace(/&lt;br\s*\/?&gt;/gi, '<br>');
                html += '<div style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 12px 16px; margin: 12px 0; box-shadow: 0 1px 2px rgba(0,0,0,0.05); background-color: #fff;">' +
                  '<div style="display:flex;justify-content:space-between;align-items:center;">' +
                  '<span style="font-weight:600;color:#212121;font-size:16px;">' + escapeHtml(log.title) + '</span>' +
                  '<span style="font-size:12px;color:#757575;">' + escapeHtml(log.created_date) + '</span>' +
                  '</div>' +
                  '<div style="margin-top:4px;font-size:14px;color:#424242;">' + safeMessage + '</div>' +
                  '</div>';
              });
            } else {
              html = '<div class="text-center text-muted">No logs found.</div>';
            }
            $('.stc-daily-req-logs-body').html(html);
          },
          error: function () {
            $('.stc-daily-req-logs-body').html('<div class="text-center text-danger">Error loading logs.</div>');
          }
        });
      });

      function loadBalanceModal(itemId) {
        $('#dr-itemcode-itemid').val(itemId);
        $('#dr-itemcode-input').val('');
        $('#dr-itemcode-oldproductid').val('0');
        $('#dr-itemcode-label').text('Item Code (Product ID)');
        $('.dr-add-itemcode-form').hide();
        $('.stc-daily-req-balance-body').html('<tr><td colspan="5" class="text-center">Loading...</td></tr>');
        $.ajax({
          url: 'kattegat/ragnar_order.php',
          method: 'POST',
          data: { stc_call_daily_requisition_balance: 1, item_id: itemId },
          dataType: 'json',
          success: function (response) {
            if (response && response.reload) {
              window.location.reload();
              return;
            }
            var html = '';
            if (response && response.data && response.data.length > 0) {
              response.data.forEach(function (row) {
                var canDispatch = parseInt(row.can_dispatch, 10) === 1;
                var btn = '<button type="button" class="btn btn-success btn-sm dr-dispatch-balance-btn" ' +
                  'data-item-id="' + escapeHtml(itemId) + '" data-product-id="' + escapeHtml(row.product_id) + '" ' +
                  (canDispatch ? '' : 'disabled') + '>Dispatch Balance</button>';

                var changeBtn = '<button type="button" class="btn btn-warning btn-sm dr-change-itemcode-btn" ' +
                  'style="margin-left:6px;" ' +
                  'data-item-id="' + escapeHtml(itemId) + '" data-old-product-id="' + escapeHtml(row.product_id) + '" ' +
                  'title="Change Item Code"><i class="fa fa-edit"></i></button>';
                html += '<tr>' +
                  '<td>' + escapeHtml(row.product_name) + '</td>' +
                  '<td class="text-center">' + escapeHtml(row.product_unit) + '</td>' +
                  '<td class="text-right"><b>' + escapeHtml(row.req_balance_qty) + '</b></td>' +
                  '<td class="text-right"><b>' + escapeHtml(row.balance_qty) + '</b></td>' +
                  '<td class="text-center">' + btn + changeBtn + '</td>' +
                  '</tr>';
              });
            } else {
              html =
                '<tr><td colspan="5" class="text-center">' +
                'No connected product found. ' +
                '<button type="button" class="btn btn-primary btn-sm dr-show-itemcode-form" data-item-id="' + escapeHtml(itemId) + '">Add Item Code</button>' +
                '</td></tr>';
            }
            $('.stc-daily-req-balance-body').html(html);
          },
          error: function () {
            $('.stc-daily-req-balance-body').html('<tr><td colspan="5" class="text-center">Error loading balance.</td></tr>');
          }
        });
      }

      $('body').delegate('.dr-balance-btn', 'click', function () {
        var itemId = $(this).data('item-id');
        loadBalanceModal(itemId);
      });

      $('body').delegate('.dr-show-itemcode-form', 'click', function () {
        var itemId = $(this).data('item-id');
        $('#dr-itemcode-itemid').val(itemId);
        $('#dr-itemcode-oldproductid').val('0');
        $('#dr-itemcode-label').text('Add Item Code (Product ID)');
        $('.dr-add-itemcode-form').show();
        $('#dr-itemcode-input').focus();
      });

      // Integer-only input guard (still validate on save)
      $('#dr-itemcode-input').on('input', function () {
        var v = $(this).val();
        if (v === '') return;
        // strip decimals and non-digits
        v = String(v).replace(/[^\d]/g, '');
        $(this).val(v);
      });

      $('#dr-itemcode-cancel-btn').on('click', function () {
        $('#dr-itemcode-input').val('');
        $('#dr-itemcode-oldproductid').val('0');
        $('#dr-itemcode-label').text('Item Code (Product ID)');
        $('.dr-add-itemcode-form').hide();
      });

      $('#dr-itemcode-save-btn').on('click', function () {
        var itemId = parseInt($('#dr-itemcode-itemid').val(), 10) || 0;
        var productId = parseInt($('#dr-itemcode-input').val(), 10) || 0;
        var oldProductId = parseInt($('#dr-itemcode-oldproductid').val(), 10) || 0;
        if (itemId <= 0) { showSwal('error', 'Invalid', 'Invalid item id.'); return; }
        if (productId <= 0) { showSwal('warning', 'Invalid', 'Please enter valid item code (integer).'); return; }

        $(this).prop('disabled', true).text('Saving...');
        $.ajax({
          url: 'kattegat/ragnar_order.php',
          method: 'POST',
          data: {
            stc_update_daily_requisition_item_code: 1,
            item_id: itemId,
            product_id: productId,
            old_product_id: oldProductId
          },
          dataType: 'json',
          success: function (response) {
            $('#dr-itemcode-save-btn').prop('disabled', false).text('Save');
            if (response && response.reload) { window.location.reload(); return; }
            if (response && response.success) {
              showSwal('success', 'Updated', response.message || 'Updated.');
              $('.dr-add-itemcode-form').hide();
              $('#dr-itemcode-oldproductid').val('0');
              $('#dr-itemcode-label').text('Item Code (Product ID)');
              // Reload balance table (modal is already open)
              loadBalanceModal(itemId);
              loadDailyRequisitions($('#dr-search').val() || '', currentPage);
            } else {
              showSwal('error', 'Failed', (response && response.message) ? response.message : 'Update failed.');
            }
          },
          error: function () {
            $('#dr-itemcode-save-btn').prop('disabled', false).text('Save');
            showSwal('error', 'Failed', 'Update failed.');
          }
        });
      });

      $('body').delegate('.dr-change-itemcode-btn', 'click', function () {
        var itemId = parseInt($(this).data('item-id'), 10) || 0;
        var oldProductId = parseInt($(this).data('old-product-id'), 10) || 0;
        if (itemId <= 0 || oldProductId <= 0) {
          showSwal('error', 'Invalid', 'Invalid item or product.');
          return;
        }
        $('#dr-itemcode-itemid').val(itemId);
        $('#dr-itemcode-oldproductid').val(oldProductId);
        $('#dr-itemcode-input').val('');
        $('#dr-itemcode-label').text('Change Item Code (Old Product ID: ' + oldProductId + ')');
        $('.dr-add-itemcode-form').show();
        $('#dr-itemcode-input').focus();
      });

      $('body').delegate('.dr-dispatch-balance-btn', 'click', function () {
        var $btn = $(this);
        var itemId = $btn.data('item-id');
        var productId = $btn.data('product-id');
        if (typeof Swal === 'undefined' || !Swal.fire) {
          if (!confirm('Dispatch balance qty from Adhoc?')) return;
          $btn.prop('disabled', true).text('Dispatching...');
          doDispatch();
          return;
        }

        Swal.fire({
          icon: 'warning',
          title: 'Confirm dispatch',
          text: 'Dispatch balance qty from Adhoc?',
          showCancelButton: true,
          confirmButtonText: 'Yes, dispatch',
          cancelButtonText: 'Cancel'
        }).then(function (result) {
          if (!result.isConfirmed) return;
          $btn.prop('disabled', true).text('Dispatching...');
          doDispatch();
        });

        function doDispatch() {
        $.ajax({
          url: 'kattegat/ragnar_order.php',
          method: 'POST',
          data: {
            stc_dispatch_daily_requisition_balance: 1,
            item_id: itemId,
            product_id: productId
          },
          dataType: 'json',
          success: function (response) {
            if (response && response.reload) {
              window.location.reload();
              return;
            }
            if (response && response.success) {
              showSwal('success', 'Dispatched', response.message || 'Dispatched.');
              // Refresh modal + main table
              loadBalanceModal(itemId);
              loadDailyRequisitions($('#dr-search').val() || '', currentPage);
            } else {
              showSwal('error', 'Failed', (response && response.message) ? response.message : 'Dispatch failed.');
              $btn.prop('disabled', false).text('Dispatch Balance');
            }
          },
          error: function () {
            showSwal('error', 'Failed', 'Dispatch failed.');
            $btn.prop('disabled', false).text('Dispatch Balance');
          }
        });
        }
      });

      // Prefill dates to last 7 days (matches backend default)
      var today = new Date();
      var toIso = function (d) {
        var m = d.getMonth() + 1;
        var day = d.getDate();
        return d.getFullYear() + '-' + (m < 10 ? '0' + m : m) + '-' + (day < 10 ? '0' + day : day);
      };
      var from = new Date();
      from.setDate(today.getDate() - 7);
      $('#dr-datefrom').val(toIso(from));
      $('#dr-dateto').val(toIso(today));

      loadDailyRequisitions('', currentPage);
    });
  </script>
</body>

</html>

