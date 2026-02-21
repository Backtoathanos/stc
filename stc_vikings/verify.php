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
  <title>Verify Dispatch - STC</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
  <meta name="description" content="">
  <meta name="msapplication-tap-highlight" content="no">
  <link rel="icon" type="image/png" href="images/stc_logo_title.png">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="./main.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
  <style>
    .vd-col-narrow {
      width: 70px;
      max-width: 70px;
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
                <h5 align="center">Verify Dispatch (Dispatched → Accepted)</h5>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
              <div class="card-border mb-3 card card-body border-success" style="overflow-x: auto; white-space: nowrap;">
                <div class="row" style="margin-bottom: 10px; white-space: normal;">
                  <div class="col-md-3 col-sm-6" style="margin-bottom: 6px;">
                    <label style="display:block; position:static; margin:0 0 4px; font-weight:600;">Date</label>
                    <input type="date" class="form-control" id="vd-date">
                  </div>
                  <div class="col-md-5 col-sm-8" style="margin-bottom: 6px;">
                    <label style="display:block; position:static; margin:0 0 4px; font-weight:600;">Search</label>
                    <input type="text" class="form-control" id="vd-search" placeholder="PR, project, supervisor, req#, item...">
                  </div>
                  <div class="col-md-2 col-sm-4" style="margin-bottom: 6px;">
                    <label style="display:block; position:static; margin:0 0 4px; font-weight:600;">&nbsp;</label>
                    <button class="btn btn-primary form-control" id="vd-search-btn">Search</button>
                  </div>
                  <div class="col-md-2 col-sm-4" style="margin-bottom: 6px;">
                    <label style="display:block; position:static; margin:0 0 4px; font-weight:600;">&nbsp;</label>
                    <a class="btn btn-warning form-control" id="vd-challan-btn" href="verify-challan.php" target="_blank">Challan</a>
                  </div>
                </div>

                <table class="table table-hover table-bordered">
                  <thead>
                    <tr>
                      <th class="text-center">Sl No</th>
                      <th class="text-center vd-col-narrow">PR Name<br>PR No<br>Date &amp; Time</th>
                      <th class="text-center vd-col-narrow">Project<br>Manager</th>
                      <th class="text-center vd-col-narrow">Sent By<br>Req#</th>
                      <th class="text-center vd-col-narrow">Item Desc</th>
                      <th class="text-center">Unit</th>
                      <th class="text-center">Req Qty</th>
                      <th class="text-center">Dispatched Qty</th>
                      <th class="text-center">Rack</th>
                      <th class="text-center">Status</th>
                      <th class="text-center">Item Type</th>
                      <th class="text-center">Action</th>
                      <th class="text-center">Log</th>
                    </tr>
                  </thead>
                  <tbody class="vd-body">
                    <tr><td colspan="13" class="text-center">Loading...</td></tr>
                  </tbody>
                </table>

                <div class="vd-pagination"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Logs modal -->
  <div class="modal fade" id="vdLogsModal" tabindex="-1" role="dialog" aria-labelledby="vdLogsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="vdLogsModalLabel">Dispatch Logs</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body vd-logs-body">Loading...</div>
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

      function escapeHtml(str) {
        if (str === null || str === undefined) return '';
        return String(str)
          .replace(/&/g, '&amp;')
          .replace(/</g, '&lt;')
          .replace(/>/g, '&gt;')
          .replace(/"/g, '&quot;')
          .replace(/'/g, '&#039;');
      }

      function showSwal(icon, title, text) {
        if (typeof Swal !== 'undefined' && Swal && Swal.fire) {
          return Swal.fire({ icon: icon || 'info', title: title || '', text: text || '', confirmButtonText: 'OK' });
        }
        alert((title ? title + '\n' : '') + (text || ''));
        return Promise.resolve();
      }

      function statusBadge(statusCode, statusText) {
        var bg = '#6c757d', color = '#fff';
        switch (parseInt(statusCode, 10)) {
          case 1: bg = '#3498db'; break;
          case 2: bg = '#2ecc71'; break;
          case 3: bg = '#27ae60'; break;
          case 4: bg = '#f39c12'; break;
          case 5: bg = '#16a085'; break;
          case 6: bg = '#e74c3c'; break;
          case 7: bg = '#95a5a6'; break;
          case 8: bg = '#9b59b6'; break;
          case 9: bg = 'rgb(255, 47, 47)'; break;
          default: bg = '#34495e';
        }
        return '<span class="badge" style="background-color:' + bg + ';color:' + color + ';padding: 2px 6px;border-radius: 3px;">' + escapeHtml(statusText) + '</span>';
      }

      function renderPagination(totalPages, page) {
        var html = '';
        if (!totalPages || totalPages <= 1) {
          $('.vd-pagination').html('');
          return;
        }
        for (var i = 1; i <= totalPages; i++) {
          if (i === page) {
            html += '<span class="btn btn-sm btn-success" style="margin:2px;">' + i + '</span>';
          } else {
            html += '<a href="javascript:void(0)" class="btn btn-sm btn-default vd-page" style="margin:2px;" data-page="' + i + '">' + i + '</a>';
          }
        }
        $('.vd-pagination').html(html);
      }

      function loadVerify(search, page) {
        var date = $('#vd-date').val() || '';
        $('.vd-body').html('<tr><td colspan="13" class="text-center">Loading...</td></tr>');
        $.ajax({
          url: 'kattegat/ragnar_order.php',
          method: 'POST',
          data: {
            stc_call_verify_dispatch: 1,
            search: search || '',
            date: date,
            page: page || 1,
            limit: perPage
          },
          dataType: 'json',
          success: function (response) {
            if (response && response.reload) { window.location.reload(); return; }
            var rows = '';
            if (response && response.data && response.data.length > 0) {
              var slno = (parseInt(response.page || 1, 10) - 1) * perPage;
              response.data.forEach(function (item) {
                slno++;
                var prCell = '<div><b>' + escapeHtml(item.combiner_reference || '-') + '</b></div>' +
                  '<div style="font-size: 12px; color: #555;">PR# ' + escapeHtml(item.combiner_id || '-') + '</div>' +
                  '<div style="font-size: 12px; color: #555;">' + escapeHtml(item.dispatched_date_time) + '</div>';

                var projectAndManager = '<div><b>' + escapeHtml(item.project_name) + '</b></div>' +
                  '<div style="font-size: 12px; color: #555;">' + escapeHtml(item.manager_name) + '</div>';

                var sentByAndNumber = '<div><b>' + escapeHtml(item.sent_by_name) + '</b> <span style="font-size: 12px; color: #555;">(' + escapeHtml(item.sent_by_contact) + ')</span></div>' +
                  '<div style="font-size: 12px; color: #555;">Req# ' + escapeHtml(item.requisition_number) + '</div>';

                var logsBtn = '<span class="text-muted">-</span>';
                if (parseInt(item.logs_count, 10) > 0) {
                  logsBtn = '<button type="button" class="btn btn-info btn-sm vd-log-btn" data-item-id="' + escapeHtml(item.item_id) + '" data-toggle="modal" data-target="#vdLogsModal">View (' + escapeHtml(item.logs_count) + ')</button>';
                }

                var acceptBtn = '<button type="button" class="btn btn-success btn-sm vd-accept-btn" data-item-id="' + escapeHtml(item.item_id) + '">Accept</button>';
                if (parseInt(item.is_accepted, 10) === 1) {
                  acceptBtn = '<span class="badge badge-success" style="background:#28a745;color:#fff;padding:6px 10px;">Accepted</span>';
                }

                rows += '<tr>' +
                  '<td class="text-center">' + escapeHtml(slno) + '</td>' +
                  '<td class="text-center vd-col-narrow">' + prCell + '</td>' +
                  '<td class="vd-col-narrow">' + projectAndManager + '</td>' +
                  '<td class="vd-col-narrow">' + sentByAndNumber + '</td>' +
                  '<td class="vd-col-narrow">' + escapeHtml(item.item_desc) + '</td>' +
                  '<td class="text-center">' + escapeHtml(item.unit) + '</td>' +
                  '<td class="text-right">' + escapeHtml(item.req_qty) + '</td>' +
                  '<td class="text-right"><b>' + escapeHtml(item.dispatched_qty) + '</b></td>' +
                  '<td class="text-center">' + escapeHtml(item.racks) + '</td>' +
                  '<td class="text-center">' + statusBadge(item.status_code, item.status_text) + '</td>' +
                  '<td class="text-center">' + escapeHtml(item.item_type) + '</td>' +
                  '<td class="text-center">' + acceptBtn + '</td>' +
                  '<td class="text-center">' + logsBtn + '</td>' +
                  '</tr>';
              });
            } else {
              rows = '<tr><td colspan="13" class="text-center">No dispatched items found for this date.</td></tr>';
            }
            $('.vd-body').html(rows);
            renderPagination(parseInt(response.total_pages || 0, 10), parseInt(response.page || 1, 10));
          },
          error: function () {
            $('.vd-body').html('<tr><td colspan="13" class="text-center">Error loading data.</td></tr>');
          }
        });
      }

      function runSearch() {
        currentPage = 1;
        loadVerify($('#vd-search').val() || '', currentPage);
      }

      $('#vd-search-btn').on('click', function (e) { e.preventDefault(); runSearch(); });
      $('#vd-search').on('keydown', function (e) { if (e.keyCode === 13) { e.preventDefault(); runSearch(); } });
      $('#vd-date').on('change', function () { runSearch(); });
      $('#vd-date').on('change', function () {
        var d = $('#vd-date').val() || '';
        $('#vd-challan-btn').attr('href', 'verify-challan.php?date=' + encodeURIComponent(d));
      });
      $('body').delegate('.vd-page', 'click', function () {
        currentPage = parseInt($(this).data('page'), 10) || 1;
        loadVerify($('#vd-search').val() || '', currentPage);
      });

      $('body').delegate('.vd-accept-btn', 'click', function () {
        var itemId = $(this).data('item-id');
        var date = $('#vd-date').val() || '';
        Swal.fire({
          icon: 'warning',
          title: 'Accept dispatch?',
          text: 'This will mark item as accepted for printing.',
          showCancelButton: true,
          confirmButtonText: 'Yes, accept',
          cancelButtonText: 'Cancel'
        }).then(function (result) {
          if (!result.isConfirmed) return;
          Swal.fire({ title: 'Saving...', allowOutsideClick: false, didOpen: function () { Swal.showLoading(); } });
          $.ajax({
            url: 'kattegat/ragnar_order.php',
            method: 'POST',
            data: { stc_accept_verify_dispatch: 1, item_id: itemId, date: date },
            dataType: 'json',
            success: function (response) {
              if (response && response.reload) { window.location.reload(); return; }
              if (response && response.success) {
                Swal.fire({ icon: 'success', title: 'Accepted', text: response.message || 'Accepted.' });
                loadVerify($('#vd-search').val() || '', currentPage);
              } else {
                Swal.fire({ icon: 'error', title: 'Failed', text: (response && response.message) ? response.message : 'Accept failed.' });
              }
            },
            error: function () {
              Swal.fire({ icon: 'error', title: 'Failed', text: 'Accept failed.' });
            }
          });
        });
      });

      $('body').delegate('.vd-log-btn', 'click', function () {
        var itemId = $(this).data('item-id');
        $('.vd-logs-body').html('Loading...');
        $.ajax({
          url: 'kattegat/ragnar_order.php',
          method: 'POST',
          data: { stc_call_daily_requisition_logs: 1, item_id: itemId },
          dataType: 'json',
          success: function (response) {
            if (response && response.reload) { window.location.reload(); return; }
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
            $('.vd-logs-body').html(html);
          },
          error: function () {
            $('.vd-logs-body').html('<div class="text-center text-danger">Error loading logs.</div>');
          }
        });
      });

      // Default date: today
      var today = new Date();
      var toIso = function (d) {
        var m = d.getMonth() + 1;
        var day = d.getDate();
        return d.getFullYear() + '-' + (m < 10 ? '0' + m : m) + '-' + (day < 10 ? '0' + day : day);
      };
      $('#vd-date').val(toIso(today));
      $('#vd-challan-btn').attr('href', 'verify-challan.php?date=' + encodeURIComponent(toIso(today)));
      loadVerify('', currentPage);
    });
  </script>
</body>

</html>

