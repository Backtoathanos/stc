<?php
require_once 'kattegat/auth_helper.php';

STCAuthHelper::checkAuth();
$page_code = 601;
include 'kattegat/role_check.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
  <title>Parent / Guardian complaints — STC</title>
  <link rel="icon" type="image/png" href="images/stc_logo_title.png">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="./main.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
  <style>
    .prq-table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    .prq-badge-new { background: #337ab7; }
    .prq-badge-read { background: #f0ad4e; }
    .prq-badge-closed { background: #5cb85c; }
    .prq-msg-cell { max-width: 260px; white-space: normal; word-break: break-word; }

    /* Review modal — clean layout (no label tags; captions are p.span-style) */
    #prqModal .modal-dialog { max-width: 720px; }
    #prqModal .modal-content {
      border: none;
      border-radius: 12px;
      box-shadow: 0 22px 50px rgba(15, 23, 42, 0.2);
      overflow: hidden;
      font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
    }
    #prqModal .modal-header {
      padding: 16px 20px;
      background: linear-gradient(135deg, #1e3a5f 0%, #2563eb 100%);
      color: #fff;
      border-bottom: none;
    }
    #prqModal .modal-header .close {
      color: #fff;
      opacity: 0.92;
      text-shadow: none;
      font-weight: 300;
      margin-top: 2px;
    }
    #prqModal .modal-header .modal-title {
      font-size: 1.15rem;
      font-weight: 600;
      margin: 0;
      letter-spacing: 0.01em;
    }
    #prqModal .modal-body {
      padding: 18px 20px 22px;
      background: #fafbfc;
    }
    #prqModal .modal-footer {
      padding: 12px 20px 16px;
      background: #f1f5f9;
      border-top: 1px solid #e2e8f0;
      text-align: right;
    }
    #prqModal .prq-status-strip {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 16px;
      padding: 10px 14px;
      background: #fff;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
    }
    #prqModal .prq-status-strip .prq-caption {
      margin: 0;
      font-size: 12px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.06em;
      color: #64748b;
    }
    #prqModal .prq-detail-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px 18px;
    }
    @media (max-width: 600px) {
      #prqModal .prq-detail-grid {
        grid-template-columns: 1fr;
      }
    }
    #prqModal .prq-detail-item { min-width: 0; }
    #prqModal .prq-detail-item.prq-span-2 {
      grid-column: 1 / -1;
    }
    #prqModal .prq-caption {
      display: block;
      margin: 0 0 6px;
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: #64748b;
    }
    #prqModal .prq-detail-box {
      margin: 0;
      padding: 11px 14px;
      background: #fff;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      font-size: 14px;
      line-height: 1.5;
      color: #1e293b;
      white-space: pre-wrap;
      word-break: break-word;
      box-shadow: 0 1px 0 rgba(15, 23, 42, 0.04);
    }
    #prqModal .prq-detail-box.prq-message-box {
      max-height: 200px;
      overflow-y: auto;
    }
    #prqModal .prq-detail-box.prq-action-preview {
      max-height: 160px;
      overflow-y: auto;
      background: #f0fdf4;
      border-color: #bbf7d0;
    }
    #prqModal .prq-action-panel {
      margin-top: 18px;
      padding-top: 16px;
      border-top: 1px solid #e2e8f0;
    }
    #prqModal .prq-textarea {
      border-radius: 8px;
      border-color: #cbd5e1;
      resize: vertical;
      min-height: 100px;
    }
    #prqModal .prq-textarea:focus {
      border-color: #2563eb;
      box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15);
    }
    #prqModal .prq-hint {
      margin: 8px 0 12px;
      font-size: 12px;
      color: #64748b;
    }
    #prqModal #prq-btn-close {
      border-radius: 8px;
      font-weight: 600;
      padding-left: 16px;
      padding-right: 16px;
    }
    #prqModal #prq-closed-note {
      border-radius: 10px;
      border: none;
      margin-bottom: 0;
      margin-top: 12px;
    }

    /* Theme (Bootstrap 4+ in main.css) + BS3 modal.js */
    #prqModal.modal {
      z-index: 20050 !important;
    }
    body.modal-open .modal-backdrop {
      z-index: 20040 !important;
    }
    #prqModal.modal.fade.in {
      opacity: 1 !important;
    }
    #prqModal.modal.in,
    #prqModal.modal.in.show {
      display: flex !important;
      align-items: center;
      justify-content: center;
    }
    #prqModal.modal.fade.in .modal-dialog {
      transform: none !important;
      -webkit-transform: none !important;
    }
  </style>
</head>
<body>
  <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
    <?php include_once 'header-nav.php'; ?>
    <div class="app-main">
      <?php include_once 'sidebar-nav.php'; ?>
      <div class="app-main__outer">
        <div class="app-main__inner">
          <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">
              <div class="card-border mb-3 card card-body border-success">
                <h5 class="text-center mb-0">Parent / guardian complaints</h5>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xl-12">
              <div class="card-border mb-3 card card-body border-primary">
                <div class="clearfix" style="margin-bottom: 12px;">
                  <button type="button" class="btn btn-primary btn-sm pull-right" id="prq-refresh">
                    <i class="fa fa-refresh"></i> Refresh
                  </button>
                  <span class="small text-muted">Newest first · up to 500 rows</span>
                </div>
                <div class="prq-table-wrap">
                  <table class="table table-striped table-bordered table-hover" style="margin-bottom: 0;">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Parent</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Student ID</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th style="min-width: 100px;">Action</th>
                      </tr>
                    </thead>
                    <tbody id="prq-tbody">
                      <tr><td colspan="10" class="text-center text-muted">Loading…</td></tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="prqModal" tabindex="-1" role="dialog" aria-labelledby="prqModalTitle">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <p class="modal-title" id="prqModalTitle">Complaint #<span id="prq-modal-id">—</span></p>
        </div>
        <div class="modal-body">
          <div class="prq-status-strip">
            <p class="prq-caption">Status</p>
            <span id="prq-modal-status">—</span>
          </div>
          <div class="prq-detail-grid">
            <div class="prq-detail-item">
              <p class="prq-caption">Parent / guardian</p>
              <p class="prq-detail-box" id="prq-modal-parent"></p>
            </div>
            <div class="prq-detail-item">
              <p class="prq-caption">Submitted</p>
              <p class="prq-detail-box" id="prq-modal-date"></p>
            </div>
            <div class="prq-detail-item">
              <p class="prq-caption">Email</p>
              <p class="prq-detail-box" id="prq-modal-email"></p>
            </div>
            <div class="prq-detail-item">
              <p class="prq-caption">Phone</p>
              <p class="prq-detail-box" id="prq-modal-phone"></p>
            </div>
            <div class="prq-detail-item">
              <p class="prq-caption">Student name</p>
              <p class="prq-detail-box" id="prq-modal-stuname"></p>
            </div>
            <div class="prq-detail-item">
              <p class="prq-caption">Admission / ID</p>
              <p class="prq-detail-box" id="prq-modal-stuid"></p>
            </div>
            <div class="prq-detail-item prq-span-2">
              <p class="prq-caption">Subject</p>
              <p class="prq-detail-box" id="prq-modal-subject"></p>
            </div>
            <div class="prq-detail-item prq-span-2">
              <p class="prq-caption">Message</p>
              <p class="prq-detail-box prq-message-box" id="prq-modal-message"></p>
            </div>
          </div>
          <div id="prq-action-block" class="prq-action-panel">
            <p class="prq-caption">Action taken <span class="text-danger">*</span></p>
            <textarea class="form-control prq-textarea" id="prq-action-taken" rows="4" maxlength="8000" placeholder="Describe what the school did or will do (required to mark closed)."></textarea>
            <p class="prq-hint">Marking closed saves this text and sets status to <strong>closed</strong>.</p>
            <button type="button" class="btn btn-success" id="prq-btn-close"><i class="fa fa-check"></i> Mark as closed</button>
          </div>
          <div id="prq-closed-note" class="alert alert-success" style="display:none; margin-top:12px;">
            This request is already <strong>closed</strong>. Recorded action is shown below.
          </div>
          <div style="margin-top:12px; display:none;" id="prq-prev-action-wrap">
            <p class="prq-caption">Recorded action</p>
            <p class="prq-detail-box prq-action-preview" id="prq-modal-prev-action"></p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
  <script type="text/javascript" src="./assets/scripts/loginopr.js"></script>
  <script type="text/javascript" src="./assets/scripts/sidebar-persist.js"></script>
  <script type="text/javascript" src="./assets/scripts/main.js"></script>
  <script type="text/javascript" src="./assets/scripts/jarvis.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
  (function ($) {
    var apiUrl = 'kattegat/stc_school_parent_complaint_api.php';
    var currentId = 0;
    var currentStatus = '';

    $('#prqModal').on('shown.bs.modal', function () {
      $(this).addClass('show');
    });
    $('#prqModal').on('hidden.bs.modal', function () {
      $(this).removeClass('show');
    });

    function esc(s) {
      return $('<div>').text(s == null ? '' : String(s)).html();
    }

    function statusLabel(st) {
      var c = 'default';
      var cls = '';
      if (st === 'new') { c = 'primary'; cls = 'prq-badge-new'; }
      else if (st === 'read') { c = 'warning'; cls = 'prq-badge-read'; }
      else if (st === 'closed') { c = 'success'; cls = 'prq-badge-closed'; }
      return '<span class="label label-' + c + ' ' + cls + '">' + esc(st) + '</span>';
    }

    function loadList() {
      $('#prq-tbody').html('<tr><td colspan="10" class="text-center text-muted">Loading…</td></tr>');
      $.ajax({
        url: apiUrl,
        method: 'POST',
        data: { stc_list_parent_complaints: 1 },
        dataType: 'json'
      }).done(function (res) {
        if (!res || res.success !== true) {
          var msg = (res && res.message) ? res.message : 'Could not load list.';
          $('#prq-tbody').html('<tr><td colspan="10" class="text-center text-danger">' + esc(msg) + '</td></tr>');
          return;
        }
        var rows = res.rows || [];
        if (rows.length === 0) {
          $('#prq-tbody').html('<tr><td colspan="10" class="text-center text-muted">No complaints found.</td></tr>');
          return;
        }
        var html = '';
        rows.forEach(function (r) {
          html += '<tr>';
          html += '<td>' + esc(r.id) + '</td>';
          html += '<td>' + esc(r.createdate) + '</td>';
          html += '<td>' + esc(r.parent_name) + '</td>';
          html += '<td>' + esc(r.email) + '</td>';
          html += '<td>' + esc(r.phone) + '</td>';
          html += '<td>' + esc(r.student_id) + '</td>';
          html += '<td>' + esc(r.subject) + '</td>';
          html += '<td class="prq-msg-cell">' + esc(r.message_preview || '') + '</td>';
          html += '<td>' + statusLabel(r.status) + '</td>';
          html += '<td><button type="button" class="btn btn-info btn-xs prq-open" data-id="' + esc(r.id) + '"><i class="fa fa-pencil"></i> Review</button></td>';
          html += '</tr>';
        });
        $('#prq-tbody').html(html);
      }).fail(function () {
        $('#prq-tbody').html('<tr><td colspan="10" class="text-center text-danger">Network error.</td></tr>');
      });
    }

    function openModal(id) {
      currentId = parseInt(id, 10) || 0;
      $('#prq-action-taken').val('');
      $('#prq-prev-action-wrap').hide();
      $('#prq-modal-prev-action').text('');
      $('#prq-closed-note').hide();
      $('#prq-action-block').show();
      $.ajax({
        url: apiUrl,
        method: 'POST',
        data: { stc_get_parent_complaint: 1, id: currentId },
        dataType: 'json'
      }).done(function (res) {
        if (!res || res.success !== true || !res.row) {
          Swal.fire('Error', (res && res.message) ? res.message : 'Could not load record.', 'error');
          return;
        }
        var row = res.row;
        currentStatus = row.status || '';
        $('#prq-modal-id').text(row.id);
        $('#prq-modal-status').html(statusLabel(row.status));
        $('#prq-modal-parent').text(row.parent_name || '');
        $('#prq-modal-date').text(row.createdate || '');
        $('#prq-modal-email').text(row.email || '');
        $('#prq-modal-phone').text(row.phone || '');
        $('#prq-modal-stuname').text(row.student_name || '—');
        $('#prq-modal-stuid').text(row.student_id || '—');
        $('#prq-modal-subject').text(row.subject || '');
        $('#prq-modal-message').text(row.message || '');
        if (row.action_taken) {
          $('#prq-prev-action-wrap').show();
          $('#prq-modal-prev-action').text(row.action_taken);
        }
        if (row.status === 'closed') {
          $('#prq-action-block').hide();
          $('#prq-closed-note').show();
          $('#prq-action-taken').val(row.action_taken || '');
        } else {
          $('#prq-action-taken').val(row.action_taken || '');
        }
        $('#prqModal').modal('show');
      }).fail(function () {
        Swal.fire('Error', 'Network error.', 'error');
      });
    }

    $('#prq-tbody').on('click', '.prq-open', function () {
      var id = $(this).data('id');
      $.ajax({
        url: apiUrl,
        method: 'POST',
        data: { stc_mark_parent_complaint_read: 1, id: id },
        dataType: 'json'
      }).always(function () {
        openModal(id);
        loadList();
      });
    });

    $('#prq-refresh').on('click', function () {
      loadList();
    });

    $('#prq-btn-close').on('click', function () {
      var txt = ($('#prq-action-taken').val() || '').trim();
      if (txt.length === 0) {
        Swal.fire('Required', 'Please enter action taken before marking closed.', 'warning');
        return;
      }
      var $btn = $(this);
      $btn.prop('disabled', true);
      $.ajax({
        url: apiUrl,
        method: 'POST',
        data: { stc_close_parent_complaint: 1, id: currentId, action_taken: txt },
        dataType: 'json'
      }).done(function (res) {
        $btn.prop('disabled', false);
        if (!res || res.success !== true) {
          Swal.fire('Error', (res && res.message) ? res.message : 'Update failed.', 'error');
          return;
        }
        Swal.fire('Done', res.message || 'Marked as closed.', 'success');
        $('#prqModal').modal('hide');
        loadList();
      }).fail(function () {
        $btn.prop('disabled', false);
        Swal.fire('Error', 'Network error.', 'error');
      });
    });

    $(function () {
      loadList();
    });
  })(jQuery);
  </script>
</body>
</html>
