<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>STC Associates | {{ !empty($page_title) ? $page_title : 'DB Table' }}</title>
  @include('layouts.head')
  <style>
    .content-wrapper.stc-dbt-page .content { padding-top: 8px; padding-bottom: 8px; }
    .stc-dbt-shell { display: flex; gap: 12px; align-items: stretch; min-height: calc(100vh - 70px); }
    .stc-dbt-side {
      width: 240px; flex: 0 0 240px; background: #fff; border: 1px solid #dee2e6; border-radius: 6px;
      display: flex; flex-direction: column; overflow: hidden;
    }
    .stc-dbt-shell.is-side-hidden .stc-dbt-side { display: none; }
    .stc-dbt-side-head {
      display: flex; align-items: center; justify-content: space-between; gap: 8px;
      padding: 10px 12px; border-bottom: 1px solid #e9ecef; background: #f8f9fa;
    }
    .stc-dbt-side-head h6 { margin: 0; font-size: 13px; font-weight: 700; }
    .stc-dbt-icon-btn {
      border: 0; background: transparent; color: #6c757d; width: 28px; height: 28px;
      border-radius: 4px; line-height: 1; cursor: pointer;
    }
    .stc-dbt-icon-btn:hover { background: #e9ecef; color: #212529; }
    .stc-dbt-filter { padding: 8px 10px; border-bottom: 1px solid #e9ecef; }
    .stc-dbt-tables { overflow: auto; flex: 1; padding: 6px 0; }
    .stc-dbt-table-item {
      display: block; width: 100%; text-align: left; border: 0; background: transparent;
      padding: 6px 12px; font-size: 12px; color: #343a40; cursor: pointer; white-space: nowrap;
      overflow: hidden; text-overflow: ellipsis;
    }
    .stc-dbt-table-item i { color: #007bff; margin-right: 6px; }
    .stc-dbt-table-item:hover { background: #e7f1ff; }
    .stc-dbt-table-item.is-active { background: #007bff; color: #fff; }
    .stc-dbt-table-item.is-active i { color: #fff; }
    .stc-dbt-table-item.is-hidden { display: none; }
    .stc-dbt-main { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 12px; }
    .stc-dbt-card { background: #fff; border: 1px solid #dee2e6; border-radius: 6px; overflow: hidden; }
    .stc-dbt-card-head {
      display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap;
      padding: 10px 14px; border-bottom: 1px solid #e9ecef; background: #f8f9fa;
    }
    .stc-dbt-card-head h6 { margin: 0; font-size: 13px; font-weight: 700; }
    .stc-dbt-card-head h6 b { color: #007bff; }
    .stc-dbt-head-left, .stc-dbt-head-right { display: flex; align-items: center; gap: 8px; }
    .stc-dbt-sql-title-show { display: none; }
    .stc-dbt-shell.is-side-hidden .stc-dbt-sql-title-show { display: inline-flex; }
    .stc-dbt-editor { display: flex; min-height: 150px; max-height: 280px; font-family: Consolas, Monaco, monospace; }
    .stc-dbt-gutter {
      margin: 0; padding: 10px 8px 10px 10px; min-width: 36px; text-align: right;
      background: #f4f6fb; color: #adb5bd; font-size: 12px; line-height: 1.55;
      border-right: 1px solid #dee2e6; overflow: hidden; user-select: none;
    }
    .stc-dbt-sql {
      flex: 1; border: 0; resize: vertical; min-height: 150px; padding: 10px 12px;
      font-size: 13px; line-height: 1.55; font-family: Consolas, Monaco, monospace;
      outline: none; background: #fff;
    }
    .stc-dbt-results { display: flex; flex-direction: column; min-height: 240px; flex: 1; }
    .stc-dbt-results.is-fs {
      position: fixed; inset: 0; z-index: 1050; margin: 0; border-radius: 0;
      min-height: 100vh; max-height: none;
    }
    body.stc-dbt-fs { overflow: hidden; }
    .stc-dbt-meta { font-size: 12px; color: #6c757d; font-weight: 600; }
    .stc-dbt-hint { font-size: 12px; color: #adb5bd; }
    .stc-dbt-grid-wrap { overflow: auto; flex: 1; max-height: calc(100vh - 280px); }
    .stc-dbt-results.is-fs .stc-dbt-grid-wrap { max-height: none; }
    .stc-dbt-grid { width: 100%; border-collapse: collapse; font-size: 12px; margin: 0; }
    .stc-dbt-grid th, .stc-dbt-grid td {
      border: 1px solid #e9ecef; padding: 6px 8px; white-space: nowrap; max-width: 280px;
      overflow: hidden; text-overflow: ellipsis; vertical-align: middle;
    }
    .stc-dbt-grid thead th {
      position: sticky; top: 0; z-index: 2; background: #f4f6fb; font-weight: 700; cursor: pointer; user-select: none;
    }
    .stc-dbt-grid thead th.stc-dbt-actions-col { cursor: default; width: 88px; text-align: center; }
    .stc-dbt-grid tbody tr:nth-child(even) { background: #f8f9fa; }
    .stc-dbt-grid tbody tr:hover { background: #e7f1ff; }
    .stc-dbt-null { color: #adb5bd; font-style: italic; }
    .stc-dbt-empty { padding: 28px; text-align: center; color: #adb5bd; font-size: 13px; }
    .stc-dbt-row-actions { display: inline-flex; gap: 4px; }
    .stc-dbt-row-actions button {
      border: 0; background: transparent; color: #6c757d; width: 24px; height: 24px;
      border-radius: 4px; cursor: pointer; line-height: 1;
    }
    .stc-dbt-row-actions button:hover { background: #e7f1ff; color: #007bff; }
    .stc-dbt-row-actions .is-del:hover { background: #f8d7da; color: #dc3545; }
    .stc-dbt-edit-backdrop {
      display: none; position: fixed; inset: 0; z-index: 1060; background: rgba(15,23,42,.55);
      align-items: center; justify-content: center; padding: 16px;
    }
    .stc-dbt-edit-backdrop.is-open { display: flex; }
    .stc-dbt-edit-dialog {
      background: #fff; border-radius: 6px; width: 100%; max-width: 720px; max-height: 90vh;
      overflow: hidden; display: flex; flex-direction: column; box-shadow: 0 20px 50px rgba(0,0,0,.28);
    }
    .stc-dbt-edit-head, .stc-dbt-edit-foot {
      display: flex; align-items: center; justify-content: space-between; gap: 8px;
      padding: 12px 16px; background: #f8f9fa;
    }
    .stc-dbt-edit-head { border-bottom: 1px solid #dee2e6; }
    .stc-dbt-edit-foot { border-top: 1px solid #dee2e6; justify-content: flex-end; }
    .stc-dbt-edit-head h5 { margin: 0; font-size: 15px; font-weight: 700; }
    .stc-dbt-edit-body { padding: 14px 16px; overflow: auto; }
    .stc-dbt-field { margin-bottom: 10px; }
    .stc-dbt-field label { display: block; font-size: 11px; font-weight: 700; color: #6c757d; margin-bottom: 4px; }
    .stc-dbt-field .form-control[readonly] { background: #f4f6fb; }
    .stc-dbt-null-row { display: flex; align-items: flex-start; gap: 8px; }
    .stc-dbt-null-row .form-control { flex: 1; }
    .stc-dbt-null-row textarea.form-control { min-height: 88px; font-family: Consolas, Monaco, monospace; font-size: 12px; }
    .stc-dbt-null-row label { margin: 0; font-weight: 600; color: #6c757d; white-space: nowrap; padding-top: 6px; }
    .stc-dbt-html-hint { display: block; margin: 4px 0 0; font-size: 11px; font-weight: 600; color: #6c757d; }
    .stc-dbt-sort-ind { margin-left: 4px; color: #007bff; }
    @media (max-width: 767px) {
      .stc-dbt-shell { flex-direction: column; }
      .stc-dbt-side { width: 100%; flex-basis: auto; max-height: 220px; }
      .stc-dbt-grid-wrap { max-height: 50vh; }
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  @include('layouts.nav')
  @include('layouts.aside')

  <div class="content-wrapper stc-dbt-page">
    <section class="content">
      <div class="container-fluid">
        <div class="stc-dbt-shell" id="stc-dbt-shell">
          <aside class="stc-dbt-side" id="stc-dbt-side">
            <div class="stc-dbt-side-head">
              <h6>Tables (<span id="stc-dbt-table-count">0</span>)</h6>
              <button type="button" class="stc-dbt-icon-btn" id="stc-dbt-hide-side" title="Hide tables">
                <i class="fas fa-chevron-left"></i>
              </button>
            </div>
            <div class="stc-dbt-filter">
              <input type="text" class="form-control form-control-sm" id="stc-dbt-table-filter" placeholder="Filter tables">
            </div>
            <div class="stc-dbt-tables" id="stc-dbt-tables"></div>
          </aside>
          <div class="stc-dbt-main">
            <div class="stc-dbt-card">
              <div class="stc-dbt-card-head">
                <div class="stc-dbt-head-left">
                  <button type="button" class="stc-dbt-icon-btn stc-dbt-sql-title-show" id="stc-dbt-show-side" title="Show tables">
                    <i class="fas fa-list"></i>
                  </button>
                  <h6>Run SQL query on database <b id="stc-dbt-dbname">{{ $db_name ?? '' }}</b></h6>
                </div>
                <div class="stc-dbt-head-right">
                  <button type="button" class="btn btn-sm btn-default" id="stc-dbt-clear">Clear</button>
                  <button type="button" class="btn btn-sm btn-primary" id="stc-dbt-go">Go</button>
                </div>
              </div>
              <div class="stc-dbt-editor">
                <pre class="stc-dbt-gutter" id="stc-dbt-gutter">1</pre>
                <textarea class="stc-dbt-sql" id="stc-dbt-sql" spellcheck="false" placeholder="SELECT * FROM `table` LIMIT 25;"></textarea>
              </div>
            </div>
            <div class="stc-dbt-card stc-dbt-results" id="stc-dbt-results">
              <div class="stc-dbt-card-head">
                <div class="stc-dbt-head-left">
                  <button type="button" class="stc-dbt-icon-btn" id="stc-dbt-fs" title="Fullscreen">
                    <i class="fas fa-expand"></i>
                  </button>
                  <span class="stc-dbt-meta" id="stc-dbt-meta">0 row(s)</span>
                </div>
                <span class="stc-dbt-hint" id="stc-dbt-hint"></span>
              </div>
              <div class="stc-dbt-grid-wrap" id="stc-dbt-grid-wrap">
                <div class="stc-dbt-empty">Click a table or run a query to browse rows.</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
  @include('layouts.footer')
</div>

<div class="stc-dbt-edit-backdrop" id="stc-dbt-edit">
  <div class="stc-dbt-edit-dialog">
    <div class="stc-dbt-edit-head">
      <h5>Edit row</h5>
      <button type="button" class="stc-dbt-icon-btn" id="stc-dbt-edit-close">&times;</button>
    </div>
    <div class="stc-dbt-edit-body" id="stc-dbt-edit-body"></div>
    <div class="stc-dbt-edit-foot">
      <button type="button" class="btn btn-sm btn-default" id="stc-dbt-edit-cancel">Cancel</button>
      <button type="button" class="btn btn-sm btn-primary" id="stc-dbt-edit-save">Save</button>
    </div>
  </div>
</div>

@include('layouts.ajax_foot')
<script>
$(function(){
  var CAN = { create: 1, edit: 1, del: 1 };
  var SIDE_KEY = 'stc_dbtable_sidebar';
  var CSRF = $('meta[name="csrf-token"]').attr('content');
  var state = {
    columns: [], rows: [], table: null, writable: false,
    pk: [], autoInc: [], meta: {}, sortCol: null, sortDir: 1, fs: false
  };

  $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': CSRF } });

  function toast(msg, err) {
    if (typeof Swal === 'undefined') {
      alert(msg);
      return;
    }
    Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2200 })
      .fire({ icon: err ? 'error' : 'success', title: msg });
  }

  function ident(name) {
    return '`' + String(name).replace(/`/g, '') + '`';
  }

  function syncGutter() {
    var text = $('#stc-dbt-sql').val() || '';
    var n = text.split('\n').length;
    var lines = [];
    for (var i = 1; i <= n; i++) lines.push(i);
    var gutter = document.getElementById('stc-dbt-gutter');
    gutter.textContent = lines.join('\n');
    gutter.scrollTop = document.getElementById('stc-dbt-sql').scrollTop;
  }

  function applySidePref() {
    var hidden = localStorage.getItem(SIDE_KEY) === '0';
    $('#stc-dbt-shell').toggleClass('is-side-hidden', hidden);
  }

  function setSide(show) {
    localStorage.setItem(SIDE_KEY, show ? '1' : '0');
    applySidePref();
  }

  function escapeHtml(v) {
    return String(v == null ? '' : v)
      .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
  }

  function cellTitle(v) { return v === null ? 'NULL' : String(v); }

  function cellHtml(v) {
    if (v === null) return '<em class="stc-dbt-null">NULL</em>';
    var s = String(v);
    return escapeHtml(s.length > 80 ? s.slice(0, 80) + '…' : s);
  }

  function sortValue(v) {
    if (v === null || v === undefined) return null;
    var s = String(v).trim();
    if (/^-?\d+(\.\d+)?$/.test(s)) return parseFloat(s);
    if (/^\d{4}-\d{2}-\d{2}/.test(s)) return s;
    return s.toLowerCase();
  }

  function orderedRows() {
    var rows = state.rows.map(function(r, i){ return { i: i, row: r }; });
    if (state.sortCol == null) return rows;
    var col = state.sortCol, dir = state.sortDir;
    rows.sort(function(a, b){
      var av = sortValue(a.row[col]), bv = sortValue(b.row[col]);
      if (av === null && bv === null) return 0;
      if (av === null) return 1;
      if (bv === null) return -1;
      if (av < bv) return -1 * dir;
      if (av > bv) return 1 * dir;
      return 0;
    });
    return rows;
  }

  function pkOf(row) {
    var out = {};
    (state.pk || []).forEach(function(k){ out[k] = row[k]; });
    return out;
  }

  function renderGrid() {
    var wrap = $('#stc-dbt-grid-wrap');
    if (!state.columns.length) {
      wrap.html('<div class="stc-dbt-empty">No rows returned.</div>');
      return;
    }
    var showAct = state.writable && state.table && (CAN.edit || CAN.create || CAN.del);
    var html = '<table class="stc-dbt-grid"><thead><tr>';
    if (showAct) html += '<th class="stc-dbt-actions-col"> </th>';
    state.columns.forEach(function(col){
      var ind = state.sortCol === col ? ('<span class="stc-dbt-sort-ind">' + (state.sortDir === 1 ? '▲' : '▼') + '</span>') : '';
      html += '<th data-col="' + escapeHtml(col) + '">' + escapeHtml(col) + ind + '</th>';
    });
    html += '</tr></thead><tbody>';
    orderedRows().forEach(function(item){
      html += '<tr data-idx="' + item.i + '">';
      if (showAct) {
        html += '<td><div class="stc-dbt-row-actions">';
        if (CAN.edit) html += '<button type="button" class="stc-dbt-act-edit" title="Edit"><i class="fas fa-pencil-alt"></i></button>';
        if (CAN.create) html += '<button type="button" class="stc-dbt-act-copy" title="Copy"><i class="far fa-copy"></i></button>';
        if (CAN.del) html += '<button type="button" class="stc-dbt-act-del is-del" title="Delete"><i class="fas fa-trash"></i></button>';
        html += '</div></td>';
      }
      state.columns.forEach(function(col){
        var v = item.row[col];
        html += '<td title="' + escapeHtml(cellTitle(v)) + '">' + cellHtml(v) + '</td>';
      });
      html += '</tr>';
    });
    html += '</tbody></table>';
    wrap.html(html);
  }

  function setMeta(data) {
    var n = data.displayed != null ? data.displayed : (data.rows ? data.rows.length : 0);
    var ms = data.elapsed_ms != null ? data.elapsed_ms : 0;
    var txt = n + ' row(s) · ' + ms + ' ms';
    if (data.truncated) txt += ' · truncated at ' + n;
    $('#stc-dbt-meta').text(txt);
    $('#stc-dbt-hint').text(data.table ? data.table : '');
  }

  function applyResult(data) {
    state.columns = data.columns || [];
    state.rows = data.rows || [];
    state.table = data.table || null;
    state.writable = !!data.writable;
    state.pk = data.pk || [];
    state.autoInc = data.auto_inc || [];
    state.meta = data.column_meta || {};
    state.sortCol = null;
    state.sortDir = 1;
    setMeta(data);
    renderGrid();
  }

  function api(url, payload, done) {
    $.ajax({
      url: url,
      method: 'post',
      data: $.extend({ _token: CSRF }, payload),
      dataType: 'json'
    }).done(function(data){
      if (!data || !data.success) {
        toast((data && data.message) ? data.message : 'Request failed', true);
        return;
      }
      done(data);
    }).fail(function(xhr){
      var msg = 'Request failed';
      if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
      else if (xhr.responseText) msg = xhr.responseText.slice(0, 180);
      toast(msg, true);
    });
  }

  function runSql() {
    var sql = $('#stc-dbt-sql').val();
    $('#stc-dbt-go').prop('disabled', true).text('…');
    $.ajax({
      url: "{{ url('/db-table/run') }}",
      method: 'post',
      data: { _token: CSRF, sql: sql },
      dataType: 'json'
    }).done(function(data){
      if (!data || !data.success) {
        toast((data && data.message) ? data.message : 'Request failed', true);
        return;
      }
      applyResult(data);
    }).fail(function(xhr){
      var msg = 'Request failed';
      if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
      else if (xhr.responseText) msg = xhr.responseText.slice(0, 180);
      toast(msg, true);
    }).always(function(){
      $('#stc-dbt-go').prop('disabled', false).text('Go');
    });
  }

  function loadTables() {
    api("{{ url('/db-table/tables') }}", {}, function(data){
      $('#stc-dbt-dbname').text(data.db || '');
      $('#stc-dbt-table-count').text((data.tables || []).length);
      var html = '';
      (data.tables || []).forEach(function(t){
        html += '<button type="button" class="stc-dbt-table-item" data-table="' + escapeHtml(t) + '" title="' + escapeHtml(t) + '"><i class="fas fa-table"></i>' + escapeHtml(t) + '</button>';
      });
      $('#stc-dbt-tables').html(html || '<div class="stc-dbt-empty">No tables.</div>');
    });
  }

  function isTextType(type) {
    var t = String(type || '').toLowerCase();
    return t.indexOf('text') !== -1 || t.indexOf('json') !== -1;
  }

  function openEdit(idx) {
    var row = state.rows[idx];
    if (!row) return;
    var $body = $('#stc-dbt-edit-body').empty();
    $('<input>', { type: 'hidden', id: 'stc-dbt-edit-idx', value: idx }).appendTo($body);
    state.columns.forEach(function(col){
      var meta = state.meta[col] || {};
      var isPk = state.pk.indexOf(col) !== -1;
      var val = row[col];
      var isNull = val === null;
      var multiline = isTextType(meta.type);
      var $field = $('<div class="stc-dbt-field">');
      var $label = $('<label>').text(col + ' ');
      if (meta.type) $label.append($('<span>').css({ fontWeight: 600, color: '#adb5bd' }).text(meta.type));
      var $row = $('<div class="stc-dbt-null-row">');
      var $input = $(multiline ? '<textarea>' : '<input>');
      $input.addClass('form-control form-control-sm stc-dbt-edit-input')
        .attr('data-col', col)
        .prop('readonly', isPk)
        .prop('disabled', isNull);
      if (!multiline) $input.attr('type', 'text');
      $input.val(isNull ? '' : (val == null ? '' : String(val)));
      $row.append($input);
      if (!isPk && meta.null) {
        var $nullLab = $('<label>');
        var $nullBox = $('<input>', { type: 'checkbox', class: 'stc-dbt-edit-null' }).attr('data-col', col).prop('checked', isNull);
        $nullLab.append($nullBox).append(' NULL');
        $row.append($nullLab);
      }
      $field.append($label).append($row);
      if (multiline) {
        $field.append($('<span class="stc-dbt-html-hint">').text('Saved exactly as typed. HTML tags like <br> stay as HTML, they are not stripped.'));
      }
      $body.append($field);
    });
    $('#stc-dbt-edit').addClass('is-open');
  }

  function closeEdit() { $('#stc-dbt-edit').removeClass('is-open'); }

  function collectEditFields() {
    var fields = {};
    $('#stc-dbt-edit-body .stc-dbt-edit-input').each(function(){
      var col = $(this).attr('data-col');
      var $null = $('#stc-dbt-edit-body .stc-dbt-edit-null[data-col="' + col.replace(/"/g, '') + '"]');
      fields[col] = ($null.length && $null.prop('checked')) ? null : $(this).val();
    });
    return fields;
  }

  function toggleFs(on) {
    state.fs = on !== undefined ? on : !state.fs;
    $('#stc-dbt-results').toggleClass('is-fs', state.fs);
    $('body').toggleClass('stc-dbt-fs', state.fs);
    $('#stc-dbt-fs i').attr('class', state.fs ? 'fas fa-compress' : 'fas fa-expand');
    $('#stc-dbt-fs').attr('title', state.fs ? 'Exit fullscreen' : 'Fullscreen');
  }

  $('#stc-dbt-sql').on('input', syncGutter);
  $('#stc-dbt-sql').on('scroll', function(){
    document.getElementById('stc-dbt-gutter').scrollTop = this.scrollTop;
  });
  $('#stc-dbt-sql').on('keydown', function(e){
    if ((e.ctrlKey || e.metaKey) && (e.key === 'Enter' || e.keyCode === 13)) {
      e.preventDefault();
      runSql();
    }
  });
  $('#stc-dbt-go').on('click', runSql);
  $('#stc-dbt-clear').on('click', function(){ $('#stc-dbt-sql').val(''); syncGutter(); });
  $('#stc-dbt-hide-side').on('click', function(){ setSide(false); });
  $('#stc-dbt-show-side').on('click', function(){ setSide(true); });
  $('#stc-dbt-fs').on('click', function(){ toggleFs(); });
  $(document).on('keydown', function(e){
    if (e.key === 'Escape' || e.keyCode === 27) {
      if (state.fs) toggleFs(false);
      closeEdit();
    }
  });
  $('#stc-dbt-table-filter').on('input', function(){
    var q = ($(this).val() || '').toLowerCase();
    $('#stc-dbt-tables .stc-dbt-table-item').each(function(){
      var name = ($(this).attr('data-table') || '').toLowerCase();
      $(this).toggleClass('is-hidden', q !== '' && name.indexOf(q) === -1);
    });
  });
  $(document).on('click', '.stc-dbt-table-item', function(){
    var t = $(this).attr('data-table');
    $('.stc-dbt-table-item').removeClass('is-active');
    $(this).addClass('is-active');
    $('#stc-dbt-sql').val('SELECT * FROM ' + ident(t) + ' LIMIT 25;');
    syncGutter();
    runSql();
  });
  $(document).on('click', '.stc-dbt-grid thead th[data-col]', function(){
    var col = $(this).attr('data-col');
    if (state.sortCol === col) state.sortDir = state.sortDir === 1 ? -1 : 1;
    else { state.sortCol = col; state.sortDir = 1; }
    renderGrid();
  });
  $(document).on('click', '.stc-dbt-act-edit', function(){
    openEdit(parseInt($(this).closest('tr').attr('data-idx'), 10));
  });
  $(document).on('change', '.stc-dbt-edit-null', function(){
    var col = $(this).attr('data-col');
    $('#stc-dbt-edit-body .stc-dbt-edit-input[data-col="' + col.replace(/"/g, '') + '"]').prop('disabled', $(this).prop('checked'));
  });
  $('#stc-dbt-edit-close, #stc-dbt-edit-cancel').on('click', closeEdit);
  $('#stc-dbt-edit-save').on('click', function(){
    var idx = parseInt($('#stc-dbt-edit-idx').val(), 10);
    var row = state.rows[idx];
    if (!row) return;
    api("{{ url('/db-table/update') }}", {
      table: state.table,
      pk: JSON.stringify(pkOf(row)),
      fields: JSON.stringify(collectEditFields())
    }, function(){
      toast('Row updated');
      closeEdit();
      runSql();
    });
  });
  $(document).on('click', '.stc-dbt-act-copy', function(){
    var idx = parseInt($(this).closest('tr').attr('data-idx'), 10);
    var row = state.rows[idx];
    if (!row || !confirm('Copy this row? Auto-increment keys will be omitted.')) return;
    api("{{ url('/db-table/copy') }}", {
      table: state.table,
      fields: JSON.stringify(row)
    }, function(){
      toast('Row copied');
      runSql();
    });
  });
  $(document).on('click', '.stc-dbt-act-del', function(){
    var idx = parseInt($(this).closest('tr').attr('data-idx'), 10);
    var row = state.rows[idx];
    if (!row || !confirm('Delete this row?')) return;
    api("{{ url('/db-table/delete') }}", {
      table: state.table,
      pk: JSON.stringify(pkOf(row))
    }, function(){
      toast('Row deleted');
      runSql();
    });
  });

  applySidePref();
  syncGutter();
  loadTables();
});
</script>
</body>
</html>
