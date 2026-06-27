<?php
ini_set("session.gc_maxlifetime", 21600);
session_set_cookie_params(21600);
session_start();
require_once __DIR__ . '/includes/school_session_defaults.php';
if (empty(@$_SESSION['stc_school_user_id'])) {
    header('location:../index.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <link rel="icon" type="image/png" href="../assets/img/stc_logo_title.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>STC School || Complaint Register</title>
  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <link href="../assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
  <style>
    /* ---- status badges ---- */
    .crq-badge { display:inline-block; padding:3px 10px; border-radius:12px; font-size:12px; font-weight:600; color:#fff; }
    .crq-badge-pending  { background:#e67e22; }
    .crq-badge-resolved { background:#27ae60; }

    /* ---- table card ---- */
    .crq-table-wrap { overflow-x:auto; -webkit-overflow-scrolling:touch; }

    .crq-card-head {
      padding: 1.1rem 1.35rem;
      background: linear-gradient(135deg,#1e3a5f 0%,#2563eb 100%);
      color:#fff; border-radius:12px 12px 0 0;
      display:flex; align-items:center; justify-content:space-between;
    }
    .crq-card-head h5 { margin:0; font-size:1rem; font-weight:700; }
    .crq-card-head .crq-subtitle { font-size:.8rem; opacity:.82; margin:3px 0 0; }

    .crq-table { width:100%; border-collapse:collapse; margin:0; }
    .crq-table thead th {
      background:linear-gradient(180deg,#5c6bc0 0%,#3949ab 96%);
      color:#fff; padding:10px 12px; font-size:.8rem; font-weight:500;
      border:none; white-space:nowrap;
    }
    .crq-table thead th:first-child { border-radius:0; }
    .crq-table tbody tr { border-bottom:1px solid #e8ecf4; transition:background .12s; }
    .crq-table tbody tr:hover { background:#f5f7ff; }
    .crq-table tbody td { padding:10px 12px; font-size:.875rem; color:#374151; vertical-align:middle; }
    .crq-msg-cell { max-width:200px; white-space:normal; word-break:break-word; }

    /* ---- Resolve Modal ---- */
    #crqModal .modal-dialog { max-width:740px; }
    #crqModal .modal-content {
      border:none; border-radius:12px;
      box-shadow:0 22px 50px rgba(15,23,42,.22); overflow:hidden;
      font-family:system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif;
    }
    #crqModal .modal-header {
      padding:16px 20px;
      background:linear-gradient(135deg,#1e3a5f 0%,#2563eb 100%);
      color:#fff; border-bottom:none;
    }
    #crqModal .modal-header .close { color:#fff; opacity:.9; text-shadow:none; font-weight:300; margin-top:2px; }
    #crqModal .modal-header .modal-title { font-size:1.1rem; font-weight:700; margin:0; }
    #crqModal .modal-body { padding:18px 22px 20px; background:#fafbfc; }
    #crqModal .modal-footer { padding:12px 20px 16px; background:#f1f5f9; border-top:1px solid #e2e8f0; }

    #crqModal .crq-detail-grid { display:grid; grid-template-columns:1fr 1fr; gap:14px 18px; }
    @media(max-width:600px){ #crqModal .crq-detail-grid { grid-template-columns:1fr; } }
    #crqModal .crq-detail-item { min-width:0; }
    #crqModal .crq-detail-item.crq-span-2 { grid-column:1/-1; }
    #crqModal .crq-caption {
      display:block; margin:0 0 5px; font-size:11px; font-weight:700;
      text-transform:uppercase; letter-spacing:.05em; color:#64748b;
    }
    #crqModal .crq-box {
      margin:0; padding:10px 14px; background:#fff; border:1px solid #e2e8f0;
      border-radius:8px; font-size:14px; line-height:1.5; color:#1e293b;
      white-space:pre-wrap; word-break:break-word;
    }
    #crqModal .crq-box.crq-msg-box { max-height:180px; overflow-y:auto; }

    #crqModal .crq-resolve-panel {
      margin-top:18px; padding:16px; border-top:2px solid #e2e8f0;
      background:#f0fdf4; border-radius:0 0 10px 10px;
    }
    #crqModal .crq-resolve-panel .crq-caption { color:#065f46; }
    #crqModal .crq-resolve-textarea {
      border-radius:8px; border:1px solid #a7f3d0; resize:vertical; min-height:100px;
      background:#fff;
    }
    #crqModal .crq-resolve-textarea:focus { border-color:#059669; box-shadow:0 0 0 2px rgba(5,150,105,.15); }
    #crqModal .crq-hint { font-size:12px; color:#374151; margin:8px 0 12px; }

    #crqModal .crq-resolved-note {
      background:#dcfce7; border:1px solid #86efac; border-radius:10px;
      padding:12px 16px; margin-top:14px; font-size:14px; color:#166534;
    }
    #crqModal .crq-prev-note-box {
      margin:0; padding:10px 14px; background:#fef9c3; border:1px solid #fde047;
      border-radius:8px; font-size:14px; line-height:1.5; color:#1e293b;
      max-height:160px; overflow-y:auto; white-space:pre-wrap; word-break:break-word;
    }

    .mb-3 { border:2px solid grey; border-radius:10px; padding:10px; margin:5px; }
    .fade:not(.show) { opacity:10; }
  </style>
</head>

<body class="">
  <div class="wrapper">
    <?php include_once("bar/sidebar.php"); ?>
    <div class="main-panel">
      <?php include_once("bar/navbar.php"); ?>
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-12 col-md-12">
              <div class="card" style="border-radius:12px; overflow:hidden; box-shadow:0 8px 30px rgba(22,40,90,.1);">
                <div class="crq-card-head">
                  <div>
                    <h5><i class="material-icons" style="vertical-align:middle; font-size:1.2rem; margin-right:6px;">report_problem</i>Complaint Register</h5>
                    <p class="crq-subtitle">Complaints forwarded by Director — respond and resolve below</p>
                  </div>
                  <button type="button" class="btn btn-sm" id="crq-refresh"
                    style="background:rgba(255,255,255,.18); color:#fff; border:1px solid rgba(255,255,255,.35); border-radius:8px;">
                    <i class="fa fa-refresh"></i> Refresh
                  </button>
                </div>
                <div class="card-body p-0">
                  <div class="crq-table-wrap">
                    <table class="crq-table">
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
                          <th>Complaint Status</th>
                          <th>School Admin Status</th>
                          <th style="min-width:100px;">Action</th>
                        </tr>
                      </thead>
                      <tbody id="crq-tbody">
                        <tr><td colspan="11" class="text-center text-muted" style="padding:24px;">Loading…</td></tr>
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
  </div>

  <!-- Resolve Modal -->
  <div class="modal fade" id="crqModal" tabindex="-1" role="dialog" aria-labelledby="crqModalTitle">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <p class="modal-title" id="crqModalTitle">Complaint #<span id="crq-modal-id">—</span></p>
        </div>
        <div class="modal-body">
          <div class="crq-detail-grid">
            <div class="crq-detail-item">
              <p class="crq-caption">Parent / Guardian</p>
              <p class="crq-box" id="crq-modal-parent"></p>
            </div>
            <div class="crq-detail-item">
              <p class="crq-caption">Submitted</p>
              <p class="crq-box" id="crq-modal-date"></p>
            </div>
            <div class="crq-detail-item">
              <p class="crq-caption">Email</p>
              <p class="crq-box" id="crq-modal-email"></p>
            </div>
            <div class="crq-detail-item">
              <p class="crq-caption">Phone</p>
              <p class="crq-box" id="crq-modal-phone"></p>
            </div>
            <div class="crq-detail-item">
              <p class="crq-caption">Student Name</p>
              <p class="crq-box" id="crq-modal-stuname"></p>
            </div>
            <div class="crq-detail-item">
              <p class="crq-caption">Admission / ID</p>
              <p class="crq-box" id="crq-modal-stuid"></p>
            </div>
            <div class="crq-detail-item crq-span-2">
              <p class="crq-caption">Subject</p>
              <p class="crq-box" id="crq-modal-subject"></p>
            </div>
            <div class="crq-detail-item crq-span-2">
              <p class="crq-caption">Parent's Complaint Message</p>
              <p class="crq-box crq-msg-box" id="crq-modal-message"></p>
            </div>
          </div>

          <!-- Already resolved notice -->
          <div id="crq-resolved-note" class="crq-resolved-note" style="display:none;">
            <strong><i class="fa fa-check-circle"></i> Already Resolved.</strong>
            Your recorded resolution is shown below.
          </div>
          <div id="crq-prev-note-wrap" style="display:none; margin-top:10px;">
            <p class="crq-caption">Your recorded resolution</p>
            <p class="crq-prev-note-box" id="crq-modal-prev-note"></p>
            <p class="crq-caption" style="margin-top:8px;">Resolved on</p>
            <p class="crq-box" id="crq-modal-resolved-date" style="max-width:280px;"></p>
          </div>

          <!-- Resolve action panel (hidden once resolved) -->
          <div id="crq-resolve-panel" class="crq-resolve-panel">
            <p class="crq-caption" style="font-size:13px; margin-bottom:10px;">
              <i class="fa fa-pencil-square-o"></i> Write your resolution / response
            </p>
            <p class="crq-caption">Resolution Note <span style="color:#dc2626;">*</span></p>
            <textarea class="form-control crq-resolve-textarea" id="crq-resolve-note" rows="5"
              maxlength="8000" placeholder="Describe what action was taken, how the issue was resolved, or your official response to this complaint."></textarea>
            <p class="crq-hint">This will be recorded as the school admin's resolution and visible to the director.</p>
            <button type="button" class="btn btn-success" id="crq-btn-resolve">
              <i class="fa fa-check"></i> Mark as Resolved
            </button>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Core JS -->
  <script src="../assets/js/core/jquery.min.js"></script>
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap-material-design.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/sweetalert2.js"></script>
  <script src="../assets/js/material-dashboard.js?v=2.1.2" type="text/javascript"></script>
  <script>
    $(document).ready(function () {
      $('.complaint-register').addClass('active');
    });
  </script>
  <script>
  (function ($) {
    var apiUrl = '../vanaheim/complaint-register-api.php';
    var currentId = 0;

    /* ---- modal show/hide compat ---- */
    $('#crqModal').on('shown.bs.modal', function () { $(this).addClass('show'); });
    $('#crqModal').on('hidden.bs.modal', function () { $(this).removeClass('show'); });

    function esc(s) {
      return $('<div>').text(s == null ? '' : String(s)).html();
    }

    function statusBadge(st) {
      if (st === 'new')    return '<span class="crq-badge" style="background:#337ab7;">new</span>';
      if (st === 'read')   return '<span class="crq-badge" style="background:#f0ad4e;">read</span>';
      if (st === 'closed') return '<span class="crq-badge" style="background:#5cb85c;">closed</span>';
      return '<span class="crq-badge" style="background:#9e9e9e;">' + esc(st) + '</span>';
    }

    function schoolBadge(school_status) {
      if (school_status === 'resolved') {
        return '<span class="crq-badge crq-badge-resolved"><i class="fa fa-check-circle"></i> Resolved</span>';
      }
      return '<span class="crq-badge crq-badge-pending"><i class="fa fa-clock-o"></i> Pending</span>';
    }

    function loadList() {
      $('#crq-tbody').html('<tr><td colspan="11" class="text-center text-muted" style="padding:24px;">Loading…</td></tr>');
      $.ajax({
        url: apiUrl,
        method: 'POST',
        data: { crq_list_complaints: 1 },
        dataType: 'json'
      }).done(function (res) {
        if (!res || res.success !== true) {
          var msg = (res && res.message) ? res.message : 'Could not load list.';
          $('#crq-tbody').html('<tr><td colspan="11" class="text-center text-danger" style="padding:18px;">' + esc(msg) + '</td></tr>');
          return;
        }
        var rows = res.rows || [];
        if (rows.length === 0) {
          $('#crq-tbody').html('<tr><td colspan="11" class="text-center text-muted" style="padding:24px;">No complaints have been forwarded to school admin yet.</td></tr>');
          return;
        }
        var html = '';
        rows.forEach(function (r) {
          html += '<tr>';
          html += '<td>' + esc(r.id) + '</td>';
          html += '<td style="white-space:nowrap;">' + esc(r.createdate) + '</td>';
          html += '<td>' + esc(r.parent_name) + '</td>';
          html += '<td>' + esc(r.email) + '</td>';
          html += '<td>' + esc(r.phone) + '</td>';
          html += '<td>' + esc(r.student_id) + '</td>';
          html += '<td>' + esc(r.subject) + '</td>';
          html += '<td class="crq-msg-cell">' + esc(r.message_preview || '') + '</td>';
          html += '<td>' + statusBadge(r.status) + '</td>';
          html += '<td>' + schoolBadge(r.school_status) + '</td>';
          html += '<td><button type="button" class="btn btn-info btn-sm crq-open" data-id="' + esc(r.id) + '" style="border-radius:6px;font-size:12px;">';
          if (r.school_status === 'resolved') {
            html += '<i class="fa fa-eye"></i> View';
          } else {
            html += '<i class="fa fa-pencil"></i> Resolve';
          }
          html += '</button></td>';
          html += '</tr>';
        });
        $('#crq-tbody').html(html);
      }).fail(function () {
        $('#crq-tbody').html('<tr><td colspan="11" class="text-center text-danger" style="padding:18px;">Network error. Check connection.</td></tr>');
      });
    }

    function openModal(id) {
      currentId = parseInt(id, 10) || 0;
      $('#crq-resolve-note').val('');
      $('#crq-resolved-note').hide();
      $('#crq-prev-note-wrap').hide();
      $('#crq-resolve-panel').show();

      $.ajax({
        url: apiUrl,
        method: 'POST',
        data: { crq_get_complaint: 1, id: currentId },
        dataType: 'json'
      }).done(function (res) {
        if (!res || res.success !== true || !res.row) {
          Swal.fire('Error', (res && res.message) ? res.message : 'Could not load record.', 'error');
          return;
        }
        var row = res.row;
        $('#crq-modal-id').text(row.id);
        $('#crq-modal-parent').text(row.parent_name || '');
        $('#crq-modal-date').text(row.createdate || '');
        $('#crq-modal-email').text(row.email || '');
        $('#crq-modal-phone').text(row.phone || '');
        $('#crq-modal-stuname').text(row.student_name || '—');
        $('#crq-modal-stuid').text(row.student_id || '—');
        $('#crq-modal-subject').text(row.subject || '');
        $('#crq-modal-message').text(row.message || '');

        if (row.school_status === 'resolved') {
          $('#crq-resolve-panel').hide();
          $('#crq-resolved-note').show();
          $('#crq-prev-note-wrap').show();
          $('#crq-modal-prev-note').text(row.school_note || '');
          $('#crq-modal-resolved-date').text(row.school_resolved_date || '');
        } else {
          $('#crq-resolve-note').val(row.school_note || '');
        }

        $('#crqModal').modal('show');
      }).fail(function () {
        Swal.fire('Error', 'Network error.', 'error');
      });
    }

    $('#crq-tbody').on('click', '.crq-open', function () {
      openModal($(this).data('id'));
    });

    $('#crq-refresh').on('click', function () { loadList(); });

    $('#crq-btn-resolve').on('click', function () {
      var note = ($('#crq-resolve-note').val() || '').trim();
      if (note.length === 0) {
        Swal.fire('Required', 'Please enter a resolution note before marking as resolved.', 'warning');
        return;
      }
      var $btn = $(this);
      $btn.prop('disabled', true);
      $.ajax({
        url: apiUrl,
        method: 'POST',
        data: { crq_resolve_complaint: 1, id: currentId, school_note: note },
        dataType: 'json'
      }).done(function (res) {
        $btn.prop('disabled', false);
        if (!res || res.success !== true) {
          Swal.fire('Error', (res && res.message) ? res.message : 'Could not save.', 'error');
          return;
        }
        Swal.fire('Resolved!', 'Your resolution has been recorded. The director will be able to see it.', 'success');
        $('#crqModal').modal('hide');
        loadList();
      }).fail(function () {
        $btn.prop('disabled', false);
        Swal.fire('Error', 'Network error.', 'error');
      });
    });

    /* Bootstrap 3/4 modal backdrop cleanup */
    $(document).on('hidden.bs.modal', '#crqModal', function () {
      setTimeout(function () {
        if ($('.modal.in:visible, .modal.show:visible').length === 0) {
          $('body').removeClass('modal-open').css({'padding-right':'','overflow':''});
          $('.modal-backdrop').remove();
        }
      }, 10);
    });

    $(function () { loadList(); });
  })(jQuery);
  </script>
</body>
</html>
