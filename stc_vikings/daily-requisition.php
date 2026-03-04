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
      width: 200px;
      max-width: 200px;
      white-space: normal !important;
      overflow-wrap: anywhere;
      word-break: break-word;
    }
    .dr-pr-list li {
      padding: 8px 12px;
      cursor: pointer;
      border-bottom: 1px solid #eee;
    }
    .dr-pr-list li:hover, .dr-pr-list li.dr-pr-selected {
      background: #f0f8ff;
    }
    /* Adhoc Balance modal design */
    #dailyReqBalanceModal .modal-content {
      border-radius: 10px;
      box-shadow: 0 10px 40px rgba(0,0,0,0.12);
    }
    #dailyReqBalanceModal .modal-header {
      background: linear-gradient(135deg, #2c5282 0%, #2b6cb0 100%);
      color: #fff;
      border-radius: 10px 10px 0 0;
      padding: 14px 20px;
    }
    #dailyReqBalanceModal .modal-header .close {
      color: #fff;
      opacity: 0.9;
      text-shadow: none;
    }
    #dailyReqBalanceModal .modal-header .close:hover {
      opacity: 1;
    }
    #dailyReqBalanceModal .table {
      margin-bottom: 0;
    }
    #dailyReqBalanceModal .table thead th {
      background: #f7fafc;
      font-weight: 600;
      color: #2d3748;
      border-bottom: 2px solid #e2e8f0;
      padding: 12px 10px;
      font-size: 13px;
    }
    #dailyReqBalanceModal .table tbody td {
      padding: 12px 10px;
      vertical-align: middle;
    }
    #dailyReqBalanceModal .dr-balance-row:hover {
      background: #f8fafc;
    }
    #dailyReqBalanceModal .dr-balance-cell {
      min-width: 140px;
    }
    #dailyReqBalanceModal .dr-pending-reason-inline {
      max-width: 140px;
      display: inline-block !important;
      margin-right: 6px;
      margin-top: 6px;
    }
    #dailyReqBalanceModal .dr-update-pending-inline {
      margin-top: 6px;
    }
    #dailyReqBalanceModal .dr-rack-input {
      max-width: 100px;
      text-align: center;
    }
    #dailyReqBalanceModal .dr-dispatch-balance-btn {
      font-weight: 600;
    }
    #dailyReqBalanceModal .dr-change-itemcode-btn {
      margin-left: 6px !important;
    }
    #dailyReqBalanceModal .modal-body {
      padding: 20px;
    }
    #dailyReqBalanceModal .table tbody td.py-4 {
      background: #f8fafc;
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
                  <div class="col-md-2 col-sm-6" style="margin-bottom: 6px;">
                    <label style="display:block; position:static; margin:0 0 4px; font-weight:600;">From</label>
                    <input type="date" class="form-control" id="dr-datefrom">
                  </div>
                  <div class="col-md-2 col-sm-6" style="margin-bottom: 6px;">
                    <label style="display:block; position:static; margin:0 0 4px; font-weight:600;">To</label>
                    <input type="date" class="form-control" id="dr-dateto">
                  </div>
                  <div class="col-md-3 col-sm-8" style="margin-bottom: 6px;">
                    <label style="display:block; position:static; margin:0 0 4px; font-weight:600;">PR Name</label>
                    <div class="dr-pr-combobox" style="position:relative;">
                      <input type="text" class="form-control" id="dr-pr-name" placeholder="Type to search PR Name..." autocomplete="off">
                      <ul class="dr-pr-list list-unstyled" id="dr-pr-list" style="display:none; position:absolute; top:100%; left:0; right:0; z-index:999; max-height:200px; overflow-y:auto; background:#fff; border:1px solid #ccc; margin:0; padding:0; box-shadow:0 4px 8px rgba(0,0,0,0.15);"></ul>
                    </div>
                  </div>
                  <div class="col-md-3 col-sm-8" style="margin-bottom: 6px;">
                    <label style="display:block; position:static; margin:0 0 4px; font-weight:600;">Search</label>
                    <input type="text" class="form-control" id="dr-search" placeholder="Project, supervisor, req#, item...">
                  </div>
                  <div class="col-md-2 col-sm-4" style="margin-bottom: 6px;">
                    <label style="display:block; position:static; margin:0 0 4px; font-weight:600;">&nbsp;</label>
                    <button class="btn btn-primary form-control" id="dr-search-btn">Search</button>
                  </div>
                  <?php if(isset($_SESSION['stc_empl_id']) && ((int)$_SESSION['stc_empl_id'] === 20 || (int)$_SESSION['stc_empl_id'] === 1)): ?>
                  <div class="col-md-2 col-sm-4" style="margin-bottom: 6px;">
                    <label style="display:block; position:static; margin:0 0 4px; font-weight:600;">&nbsp;</label>
                    <a class="btn btn-warning form-control" href="verify.php" target="_blank">Verify</a>
                  </div>
                  <?php endif; ?>
                  <div class="col-md-2 col-sm-4" style="margin-bottom: 6px;">
                    <label style="display:block; position:static; margin:0 0 4px; font-weight:600;">&nbsp;</label>
                    <a class="btn btn-warning form-control" id="vd-challan-btn" href="verify-challan.php" target="_blank">Challan</a>
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
                      <th class="text-center dr-col-project">Item Code / Balance Qty / Rack</th>
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
                  <th class="text-center">Item Code</th>
                  <th class="text-center">Item Description</th>
                  <th class="text-center">Requisition Balance Qty</th>
                  <th class="text-center">Adhoc Balance</th>
                  <th class="text-center">Rack</th>
                  <th class="text-center">Adjust Quantity</th>
                  <th class="text-center">Action</th>
                </tr>
              </thead>
              <tbody class="stc-daily-req-balance-body">
                <tr><td colspan="7" class="text-center">Loading...</td></tr>
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

  <!-- Status Change Modal (Pending remarks) -->
  <div class="modal fade" id="statusRemarkModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Remarks for Pending</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <textarea class="form-control" id="statusRemarkInput" placeholder="Enter remarks"></textarea>
          <input type="hidden" id="statusChangeId">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="saveStatusRemark">Save</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
      var prNamesList = [];

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
        var prName = $('#dr-pr-name').val() ? $('#dr-pr-name').val().trim() : '';
        $.ajax({
          url: 'kattegat/ragnar_order.php',
          method: 'POST',
          data: {
            stc_call_daily_requisitions: 1,
            search: search || '',
            page: page || 1,
            limit: perPage,
            datefrom: datefrom,
            dateto: dateto,
            pr_name: prName
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
                var prCell = '<a href="stc-requisition-combiner-fsale.php?requi_id=' + escapeHtml(item.combiner_id) + '" target="_blank"><div><b>' + escapeHtml(item.combiner_reference || '-') + '</b></div>' +
                  '<div style="font-size: 12px; color: #555;">PR# ' + escapeHtml(item.combiner_id || '-') + '</div>' +
                  '<div style="font-size: 12px; color: #555;">' + escapeHtml(item.combiner_date) + '</div></a>';

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
                var itemCode = (parseInt(item.product_id, 10) > 0) ? String(item.product_id) : '-';
                var racks = (item.racks && String(item.racks).trim() !== '') ? String(item.racks) : '-';
                var codeBalRackHtml =
                  '<div><a href="#" class="dr-balance-btn" data-item-id="' + escapeHtml(item.item_id) + '" data-toggle="modal" data-target="#dailyReqBalanceModal" title="View Adhoc Balance"><b>' + escapeHtml(itemCode) + '</b></a></div>' +
                  '<div style="font-size:12px;color:#555;">' + escapeHtml(pendingQty.toFixed(2)) + '/' + escapeHtml(item.unit) + '</div>' +
                  '<div style="font-size:12px;color:#555;">' + escapeHtml(racks) + '</div>';
                if (itemCode === '-') {
                  codeBalRackHtml =
                    '<div class="text-muted">-</div>' +
                    '<div style="font-size:12px;color:#555;">' + escapeHtml(pendingQty.toFixed(2)) + '/' + escapeHtml(item.unit) + '</div>' +
                    '<div style="font-size:12px;color:#555;">' + escapeHtml(racks) + '</div>';
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
                  '<td class="text-center dr-col-project">' + codeBalRackHtml + '</td>' +
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
            prNamesList = response.pr_names || [];
          },
          error: function () {
            $('.stc-daily-requisition-body').html('<tr><td colspan="13" class="text-center">Error loading data.</td></tr>');
          }
        });
      }

      function filterAndShowPrList() {
        var q = $('#dr-pr-name').val().toLowerCase().trim();
        var matches = q === '' ? prNamesList : prNamesList.filter(function (n) { return n.toLowerCase().indexOf(q) > -1; });
        var $ul = $('#dr-pr-list');
        $ul.empty();
        if (matches.length === 0) {
          $ul.append('<li class="text-muted" style="cursor:default;">No matches</li>');
        } else {
          matches.forEach(function (n) {
            $ul.append('<li data-value="' + escapeHtml(n) + '">' + escapeHtml(n) + '</li>');
          });
        }
        $ul.show();
      }

      $('#dr-pr-name').on('focus', function () {
        if (prNamesList.length > 0) filterAndShowPrList();
      }).on('keyup', function (e) {
        if (e.keyCode === 13) {
          $('#dr-pr-list').hide();
          runSearch();
          return;
        }
        filterAndShowPrList();
      });

      $('body').on('click', '#dr-pr-list li[data-value]', function () {
        $(this).closest('.dr-pr-combobox').find('#dr-pr-name').val($(this).data('value'));
        $('#dr-pr-list').hide();
      });

      $(document).on('click', function (e) {
        if (!$(e.target).closest('.dr-pr-combobox').length) {
          $('#dr-pr-list').hide();
        }
      });

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
        $('.stc-daily-req-balance-body').html('<tr><td colspan="7" class="text-center">Loading...</td></tr>');
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
                  'data-item-id="' + escapeHtml(itemId) + '" data-old-product-id="' + escapeHtml(row.product_id) + '" ' +
                  'title="Change Item Code"><i class="fa fa-edit"></i></button>';
                var HideSHow="none";
                var itemUnitNorm = String(row.item_unit || '').toLowerCase().trim();
                var productUnitNorm = String(row.product_unit || '').toLowerCase().trim();
                if(itemUnitNorm != productUnitNorm){
                  btn = '';
                  HideSHow="block";
                }
                
                // if(row.balance_qty!=row.req_balance_qty){
                //   changeBtn = '';
                // }
                var reqQtyLink = '<a href=\"#\" class=\"dr-edit-qtyunit\" ' +
                  'data-item-id=\"' + escapeHtml(itemId) + '\" ' +
                  'data-product-id=\"' + escapeHtml(row.product_id) + '\" ' +
                  'data-qty=\"' + escapeHtml(row.req_balance_qty) + '\" ' +
                  'data-item-unit=\"' + escapeHtml(row.item_unit) + '\" ' +
                  'data-product-unit=\"' + escapeHtml(row.product_unit) + '\">' +
                  '<b>' + escapeHtml(row.req_balance_qty) + '/' + escapeHtml(row.item_unit) + '</b></a>';

                var balQtyNum = parseNumber(row.balance_qty);
                var pendingBtn = '';
                if (balQtyNum <= 0.0001) {
                  pendingBtn =
                    '<div class="dr-pending-inline-wrap" style="margin-top:8px;display:flex;flex-wrap:wrap;align-items:center;gap:6px;">' +
                    '<input type="text" class="form-control input-sm dr-pending-reason-inline" placeholder="Reason for pending...">' +
                    '<button type="button" class="btn btn-warning btn-xs dr-update-pending-inline" data-item-id="' + escapeHtml(itemId) + '"><i class="fa fa-clock-o"></i> Update Pending</button>' +
                    '</div>';
                }
                html += '<tr class="dr-balance-row" data-product-id="' + escapeHtml(row.product_id) + '" data-balance="' + escapeHtml(row.balance_qty) + '" data-unit="' + escapeHtml(row.product_unit) + '">' +
                  '<td class="text-center"><b>' + escapeHtml(row.product_id) + '</b></td>' +
                  '<td>' + escapeHtml(row.product_name) + '<span style="display:' + HideSHow + ';font-size: 12px; color: Red;">* Units not matching please change product or update unit of requisition</span></td>' +
                  '<td class="text-right dr-qtyunit-cell">' + reqQtyLink + '</td>' +
                  '<td class="text-right dr-balance-cell"><b>' + escapeHtml(row.balance_qty) + '/' + escapeHtml(row.product_unit) + '</b>' + pendingBtn + '</td>' +
                  '<td>' + escapeHtml(row.racks || '-') + '</td>' +
                  '<td><input type="number" class="form-control input-sm dr-rack-input" value="' + escapeHtml(row.req_balance_qty) + '"></td>' +
                  '<td class="text-center"><div class="dr-action-btns" style="display:flex;flex-wrap:wrap;justify-content:center;align-items:center;gap:6px;">' + btn + changeBtn + '</div></td>' +
                  '</tr>';
              });
            } else {
              html =
                '<tr><td colspan="7" class="text-center py-4">' +
                '<div class="text-muted mb-3"><i class="fa fa-info-circle"></i> No connected product found.</div>' +
                '<div class="dr-action-btns" style="display:flex;flex-wrap:wrap;justify-content:center;gap:8px;margin-bottom:10px;">' +
                '<button type="button" class="btn btn-primary btn-sm dr-show-itemcode-form" data-item-id="' + escapeHtml(itemId) + '"><i class="fa fa-plus"></i> Add Item Code</button>' +
                '<button type="button" class="btn btn-warning btn-sm dr-update-pending-inline" data-item-id="' + escapeHtml(itemId) + '"><i class="fa fa-clock-o"></i> Update Pending</button>' +
                '</div>' +
                '<div><input type="text" class="form-control dr-pending-reason-inline" placeholder="Enter reason for pending..." style="max-width:280px;margin:0 auto;"></div>' +
                '</td></tr>';
            }
            $('.stc-daily-req-balance-body').html(html);
          },
          error: function () {
            $('.stc-daily-req-balance-body').html('<tr><td colspan="7" class="text-center">Error loading balance.</td></tr>');
          }
        });
      }

      $('body').delegate('.dr-balance-btn', 'click', function (e) {
        if (e && e.preventDefault) e.preventDefault();
        var itemId = $(this).data('item-id');
        loadBalanceModal(itemId);
      });

      $('body').delegate('.dr-update-pending-inline', 'click', function () {
        var itemId = parseInt($(this).data('item-id'), 10) || 0;
        var $cell = $(this).closest('td');
        var remarks = ($cell.find('.dr-pending-reason-inline').val() || '').trim();
        if (remarks === '') {
          showSwal('warning', 'Required', 'Please enter reason for pending status.');
          return;
        }
        if (itemId <= 0) {
          showSwal('error', 'Invalid', 'Invalid requisition item id.');
          return;
        }
        var $btn = $(this);
        $btn.prop('disabled', true);
        $.ajax({
          url: 'kattegat/ragnar_order.php',
          method: 'POST',
          data: { update_requisition_status: 1, id: itemId, status: 9, remarks: remarks },
          dataType: 'json',
          success: function (response) {
            $btn.prop('disabled', false);
            if (response && response.reload) { window.location.reload(); return; }
            if (response && response.success) {
              showSwal('success', 'Updated', response.message || 'Status updated.');
              loadBalanceModal(itemId);
              loadDailyRequisitions($('#dr-search').val() || '', currentPage);
            } else {
              showSwal('error', 'Failed', (response && response.message) ? response.message : 'Failed to update status.');
            }
          },
          error: function () {
            $btn.prop('disabled', false);
            showSwal('error', 'Failed', 'Failed to update status.');
          }
        });
      });

      // Pending status with remarks (modal - used elsewhere if needed)
      $('body').on('click', '.btn-change-status', function () {
        var preq_id = $(this).attr('id');
        $('#statusChangeId').val(preq_id);
        $('#statusRemarkInput').val('');
      });
      $('#saveStatusRemark').on('click', function () {
        var remarks = $('#statusRemarkInput').val();
        var preq_id = parseInt($('#statusChangeId').val(), 10) || 0;
        if (remarks.trim() === '') {
          showSwal('warning', 'Required', 'Please enter remarks for pending status.');
          return;
        }
        if (preq_id <= 0) {
          showSwal('error', 'Invalid', 'Invalid requisition item id.');
          return;
        }

        $('#saveStatusRemark').prop('disabled', true).text('Saving...');
        $.ajax({
          url: 'kattegat/ragnar_order.php',
          method: 'POST',
          data: { update_requisition_status: 1, id: preq_id, status: 9, remarks: remarks },
          dataType: 'json',
          success: function (response) {
            $('#saveStatusRemark').prop('disabled', false).text('Save');
            if (response && response.reload) { window.location.reload(); return; }
            if (response && response.success) {
              $('#statusRemarkModal').modal('hide');
              showSwal('success', 'Updated', response.message || 'Status updated.');
              loadBalanceModal(preq_id);
              loadDailyRequisitions($('#dr-search').val() || '', currentPage);
            } else {
              showSwal('error', 'Failed', (response && response.message) ? response.message : 'Failed to update status.');
            }
          },
          error: function () {
            $('#saveStatusRemark').prop('disabled', false).text('Save');
            showSwal('error', 'Failed', 'Failed to update status.');
          }
        });
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

      $('body').delegate('.dr-edit-qtyunit', 'click', function (e) {
        e.preventDefault();
        var $a = $(this);
        var itemId = parseInt($a.data('item-id'), 10) || 0;
        var productId = parseInt($a.data('product-id'), 10) || 0;
        var qty = String($a.data('qty') || '').trim();
        var itemUnit = String($a.data('item-unit') || '').trim();
        var productUnit = String($a.data('product-unit') || '').trim();
        if (itemId <= 0 || productId <= 0) return;

        // Close any other open editor in modal
        $('#dailyReqBalanceModal .dr-qtyunit-editor').each(function () {
          var $ed = $(this);
          var $tdOld = $ed.closest('td');
          var originalHtml = $tdOld.data('drOriginalHtml');
          if (originalHtml) $tdOld.html(originalHtml);
        });

        var $td = $a.closest('td');
        var original = $td.html();
        $td.data('drOriginalHtml', original);
        var units = ['NOS', 'KG', 'MTR', 'Bag', 'Box', 'Bundle', 'Case', 'Cft', 'CuMtr', 'Drum', 'Feet', 'Gm', 'Jar', 'Kgs', 'Ltr', 'Mtr', 'Mts', 'Pkt', 'Roll', 'Set', 'Sqft', 'Sqmm', 'Sqmt', 'Pair'];
        if (itemUnit) units.push(itemUnit);
        if (productUnit && productUnit.toLowerCase() !== itemUnit.toLowerCase()) units.push(productUnit);
        if (units.length === 0) units = [''];

        var options = '';
        units.forEach(function (u) {
          var sel = (itemUnit && String(u).toLowerCase() === itemUnit.toLowerCase()) ? ' selected' : '';
          options += '<option value="' + escapeHtml(u) + '"' + sel + '>' + escapeHtml(u) + '</option>';
        });

        var editor =
          '<span class="dr-qtyunit-editor">' +
          '<input type="number" step="0.01" min="0" class="form-control input-sm dr-qtyunit-input" ' +
          'style="width:110px;display:inline-block;" value="' + escapeHtml(qty) + '">' +
          '<select class="form-control input-sm dr-qtyunit-unit" style="width:90px;display:inline-block;margin-left:6px;">' +
          options +
          '</select>' +
          '<button type="button" class="btn btn-success btn-sm dr-qtyunit-update" ' +
          'style="margin-left:6px;" data-item-id="' + escapeHtml(itemId) + '" data-product-id="' + escapeHtml(productId) + '">Update</button>' +
          '<button type="button" class="btn btn-default btn-sm dr-qtyunit-cancel" style="margin-left:6px;">Cancel</button>' +
          '</span>';

        $td.html(editor);
        $td.find('.dr-qtyunit-input').focus().select();
      });

      $('body').delegate('.dr-qtyunit-cancel', 'click', function () {
        var $td = $(this).closest('td');
        var original = $td.data('drOriginalHtml') || '';
        $td.html(original);
      });

      $('body').delegate('.dr-qtyunit-update', 'click', function () {
        var $btn = $(this);
        var itemId = parseInt($btn.data('item-id'), 10) || 0;
        var productId = parseInt($btn.data('product-id'), 10) || 0;
        var $td = $btn.closest('td');
        var qtyVal = String($td.find('.dr-qtyunit-input').val() || '').trim();
        var unitVal = String($td.find('.dr-qtyunit-unit').val() || '').trim();
        if (itemId <= 0 || productId <= 0) {
          showSwal('error', 'Invalid', 'Invalid item or product.');
          return;
        }
        if (qtyVal === '' || isNaN(Number(qtyVal)) || Number(qtyVal) < 0) {
          showSwal('warning', 'Invalid', 'Please enter a valid qty (>= 0).');
          return;
        }
        if (unitVal === '') {
          showSwal('warning', 'Invalid', 'Please select unit.');
          return;
        }

        $btn.prop('disabled', true).text('Updating...');
        $.ajax({
          url: 'kattegat/ragnar_order.php',
          method: 'POST',
          data: {
            stc_update_daily_requisition_qty_unit: 1,
            item_id: itemId,
            product_id: productId,
            pending_qty: Number(qtyVal),
            unit: unitVal
          },
          dataType: 'json',
          success: function (response) {
            if (response && response.reload) {
              window.location.reload();
              return;
            }
            if (response && response.success) {
              showSwal('success', 'Updated', response.message || 'Updated.');
              loadBalanceModal(itemId);
            } else {
              showSwal('error', 'Failed', (response && response.message) ? response.message : 'Update failed.');
              $btn.prop('disabled', false).text('Update');
            }
          },
          error: function () {
            showSwal('error', 'Failed', 'Update failed.');
            $btn.prop('disabled', false).text('Update');
          }
        });
      });

      $('body').delegate('.dr-dispatch-balance-btn', 'click', function () {
        var $btn = $(this);
        var itemId = $btn.data('item-id');
        var productId = $btn.data('product-id');
        var $row = $btn.closest('tr');
        var dispatchQty = parseFloat($row.find('.dr-rack-input').val(), 10) || 0;
        if (typeof Swal === 'undefined' || !Swal.fire) {
          if (!confirm('Dispatch ' + dispatchQty + ' from Adhoc?')) return;
          $btn.prop('disabled', true).text('Dispatching...');
          doDispatch();
          return;
        }

        Swal.fire({
          icon: 'warning',
          title: 'Confirm dispatch',
          text: 'Dispatch ' + dispatchQty + ' from Adhoc?',
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
            product_id: productId,
            dispatch_qty: dispatchQty
          },
          dataType: 'json',
          success: function (response) {
            if (response && response.reload) {
              window.location.reload();
              return;
            }
            if (response && response.success) {
              showSwal('success', 'Dispatched', response.message || 'Dispatched.');
              var dispatched = parseFloat(String(response.dispatched_qty || dispatchQty).replace(/,/g, '')) || dispatchQty;
              var curBal = parseFloat($row.data('balance'), 10) || 0;
              var unit = $row.data('unit') || '';
              var newBal = Math.round((curBal - dispatched) * 100) / 100;
              if (newBal <= 0.0001) {
                $row.slideUp(200, function () { $(this).remove(); });
              } else {
                $row.data('balance', newBal);
                $row.find('.dr-balance-cell b').first().replaceWith('<b>' + newBal + '/' + unit + '</b>');
                $row.find('.dr-rack-input').val(newBal);
              }
              $btn.prop('disabled', false).text('Dispatch Balance');
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

