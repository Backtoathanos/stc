@extends('layouts.header')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Miscellaneous</h3>
  </div>
  <div class="card-body">
    <div class="card border-light">
      <div class="card-body">
        <form id="miscForm">
          <div class="row">
            <div class="col-md-6">
              <div class="d-flex align-items-center justify-content-between p-2 mb-2 bg-light border rounded">
                <div class="font-weight-bold">State</div>
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                  <label class="btn btn-sm btn-outline-primary active">
                    <input type="radio" name="region" id="regionJharkhand" value="jharkhand" autocomplete="off"> Jharkhand
                  </label>
                  <label class="btn btn-sm btn-outline-primary">
                    <input type="radio" name="region" id="regionOdissa" value="odissa" autocomplete="off" checked> Odissa
                  </label>
                </div>
              </div>
              <div class="form-group">
                <label>Month</label>
                <input type="month" class="form-control" id="dateRange" name="month_year" value="{{ date('Y-m', strtotime('first day of last month')) }}">
              </div>
            </div>
            <div class="col-md-6 d-flex align-items-center">
              <div class="form-check mr-3" style="display:none;">
                <input class="form-check-input" type="radio" name="scope" id="scopeSite" value="site" checked>
                <label class="form-check-label" for="scopeSite">SITE</label>
              </div>
              <div class="form-group mb-0" style="min-width:220px;">
                <div class="dropdown" id="filterSiteDropdown">
                  <button class="btn btn-secondary dropdown-toggle form-control text-left" type="button" id="filterSiteBtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span id="filterSiteText">Select</span>
                  </button>
                  <div class="dropdown-menu" aria-labelledby="filterSiteBtn" style="max-height: 300px; overflow-y: auto; width: 100%;">
                    <input type="text" class="form-control form-control-sm m-2" id="filterSiteSearch" placeholder="Search...">
                    <ul class="list-unstyled mb-0" id="filterSiteList" style="max-height: 250px; overflow-y: auto;">
                      @foreach($sites as $site)
                        <li class="px-3 py-2 filter-site-item" data-id="{{ $site->id }}">{{ $site->name }}</li>
                      @endforeach
                    </ul>
                  </div>
                </div>
                <input type="hidden" id="filterSiteValue" value="all">
              </div>
            </div>
          </div>

          <!-- <div class="row mt-3">
            <div class="col-md-6">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="report_type" id="rSelected" value="selected">
                <label class="form-check-label" for="rSelected">Selected</label>
              </div>
            </div>
          </div> -->

          <div class="row mt-2">
            <div class="col-md-6">
              <div class="form-row jh-only">
                <!-- <div class="col-6">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="report_choice" id="rAdvance" value="advance">
                    <label class="form-check-label" for="rAdvance">Advance Register</label>
                  </div>
                </div> -->
                <!-- <div class="col-6">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="report_choice" id="rDamage" value="damage">
                    <label class="form-check-label" for="rDamage">Damage Register</label>
                  </div>
                </div> -->
              </div>

              <div class="form-row mt-2">
                <!-- <div class="col-6">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="report_choice" id="rWorkMan" value="workman">
                    <label class="form-check-label" for="rWorkMan">Work Man Register</label>
                  </div>
                </div> -->
                <div class="col-6">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="report_choice" id="rEmployment" value="employment">
                    <label class="form-check-label" for="rEmployment">Employment Card</label>
                  </div>
                </div>
              </div>

              <div class="form-row mt-2">
                <div class="col-6 od-only">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="report_choice" id="rFine" value="fine">
                    <label class="form-check-label" for="rFine">Fine Register</label>
                  </div>
                </div>
                <!-- <div class="col-6">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="report_choice" id="rService" value="service">
                    <label class="form-check-label" for="rService">Service Certificate</label>
                  </div>
                </div> -->
              </div>

              <div class="form-row mt-2">
                <div class="col-6">
                  <div class="form-check">
                    <input class="form-check-input" type="radio" name="report_choice" id="rOT" value="overtime">
                    <label class="form-check-label" for="rOT">Over Time Register</label>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-6 d-flex align-items-end justify-content-center">
              <button type="button" id="submitMisc" class="btn btn-dark" style="min-width:160px;">Submit</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Reusable XXL Modal -->
<div class="modal fade" id="miscModal" tabindex="-1" role="dialog" aria-labelledby="miscModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="miscModalLabel">Report</h5>
        <button type="button" class="btn btn-sm btn-primary mr-2" id="miscPrintBtn">Print</button>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="miscModalBody" style="min-height:200px; padding:0;">
        <div id="miscModalHtml" style="padding: 1rem;"><!-- non-pdf content injected dynamically --></div>
        <iframe id="miscPdfFrame" src="" style="display:none; width:100%; height: calc(100vh - 140px); border: none;"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function () {
  var listUrl = "{{ url('/reports/misc/list') }}";
  var finePreviewUrl = "{{ url('/reports/misc/fine-preview') }}";
  var overtimePreviewUrl = "{{ url('/reports/misc/overtime-preview') }}";
  var employmentCardPreviewUrl = "{{ url('/reports/misc/employment-card-preview') }}";
  var selectedCompanyName = "{{ session('selected_company_name') }}";

  // searchable dropdown behavior
  var $filterBtn = $('#filterSiteBtn');
  var $filterText = $('#filterSiteText');
  var $search = $('#filterSiteSearch');
  var $list = $('#filterSiteList');
  var $hidden = $('#filterSiteValue');

  // filter list as user types
  $search.on('keyup', function () {
    var q = $(this).val().toLowerCase();
    $list.children().each(function () {
      var txt = $(this).text().toLowerCase();
      $(this).toggle(txt.indexOf(q) !== -1);
    });
  });

  // click to select
  $list.on('click', '.filter-site-item', function () {
    var id = $(this).data('id');
    var txt = $(this).text();
    $hidden.val(id);
    $filterText.text(txt);
    // close dropdown
    $(this).closest('.dropdown-menu').removeClass('show');
  });

  // scope radio toggles dropdown enabled state
  $('input[name="scope"]').on('change', function () {
    if ($(this).val() === 'all') {
      $hidden.val('all');
      $filterBtn.prop('disabled', true);
      $filterText.text('Select');
    } else {
      $filterBtn.prop('disabled', false);
    }
  });

  // initialize state
  if ($('input[name="scope"]:checked').val() === 'all') {
    $filterBtn.prop('disabled', true);
  }

  function applyRegionVisibility() {
    var region = $('input[name="region"]:checked').val() || 'jharkhand';
    var isOdissa = region === 'odissa';

    $('.jh-only').toggle(!isOdissa);
    $('.od-only').toggle(isOdissa);

    if (isOdissa) {
      var $checked = $('input[name="report_choice"]:checked');
      if ($checked.length && ['advance', 'damage'].indexOf($checked.val()) !== -1) {
        $checked.prop('checked', false);
      }
    } else {
      // Non-Odissa: Fine Register is Odissa-specific
      var $checked2 = $('input[name="report_choice"]:checked');
      if ($checked2.length && $checked2.val() === 'fine') {
        $checked2.prop('checked', false);
      }
    }
  }

  $(document).on('change', 'input[name="region"]', applyRegionVisibility);
  applyRegionVisibility();

  // Print the iframe preview (same pattern as payroll wage-slip modal)
  $(document).on('click', '#miscPrintBtn', function () {
    var iframe = document.getElementById('miscPdfFrame');
    if (iframe && iframe.style.display !== 'none' && iframe.contentWindow) {
      iframe.contentWindow.focus();
      iframe.contentWindow.print();
      return;
    }
    window.print();
  });

  // Cleanup on modal close
  $('#miscModal').on('hidden.bs.modal', function () {
    $('#miscPdfFrame').attr('src', '').hide();
    $('#miscModalHtml').show().empty();
  });

  // Submit button click handler
  $(document).on('click', '#submitMisc', function (e) {
    e.preventDefault();
    var scope = $('input[name="scope"]:checked').val();
    var siteId = $hidden.val() || 'all';
    var reportChoice = $('input[name="report_choice"]:checked').val() || '';
    var dateRange = $('#dateRange').val();
    

    // month validation: not empty
    if (!dateRange) {
      Swal.fire({ title: 'Validation', text: 'Please select a month.', icon: 'warning' });
      return;
    }

    // month cannot be greater than current month
    var parts = dateRange.split('-');
    var selYear = parseInt(parts[0], 10);
    var selMonth = parseInt(parts[1], 10);
    var today = new Date();
    var curYear = today.getFullYear();
    var curMonth = today.getMonth() + 1;
    if (selYear > curYear || (selYear === curYear && selMonth > curMonth)) {
      Swal.fire({ title: 'Validation', text: 'Selected month cannot be in the future.', icon: 'warning' });
      return;
    }

    // if site scope active, ensure site selected
    if (scope === 'site' && (!siteId || siteId === 'all')) {
      Swal.fire({ title: 'Validation', text: 'Please select a site.', icon: 'warning' });
      return;
    }

    // ensure a register option is selected
    if (!reportChoice) {
      Swal.fire({ title: 'Validation', text: 'Please choose a register option.', icon: 'warning' });
      return;
    }

    var payload = {
      site_id: siteId,
      scope: scope,
      report_choice: reportChoice,
      date_range: dateRange,
      _token: '{{ csrf_token() }}'
    };

    // Fine Register: open preview page inside iframe (like other pdf previews)
    if (reportChoice === 'fine') {
      var url = finePreviewUrl + '?month_year=' + encodeURIComponent(dateRange);
      if (siteId && siteId !== 'all') {
        url += '&site_id=' + encodeURIComponent(siteId);
      }
      $('#miscModalLabel').text('Fine Register - ' + dateRange);
      $('#miscModalHtml').hide().empty();
      $('#miscPdfFrame').show().attr('src', url);
      $('#miscModal').modal('show');
      return;
    }

    // Over Time Register (Odissa): open preview page inside iframe (landscape print)
    if (reportChoice === 'overtime') {
      var url2 = overtimePreviewUrl + '?month_year=' + encodeURIComponent(dateRange);
      if (siteId && siteId !== 'all') {
        url2 += '&site_id=' + encodeURIComponent(siteId);
      }
      $('#miscModalLabel').text('Over Time Register - ' + dateRange);
      $('#miscModalHtml').hide().empty();
      $('#miscPdfFrame').show().attr('src', url2);
      $('#miscModal').modal('show');
      return;
    }

    // Employment Card: open preview page inside iframe (portrait, 2 cards per page)
    if (reportChoice === 'employment') {
      var url3 = employmentCardPreviewUrl + '?month_year=' + encodeURIComponent(dateRange);
      if (siteId && siteId !== 'all') {
        url3 += '&site_id=' + encodeURIComponent(siteId);
      }
      $('#miscModalLabel').text('Employment Card - ' + dateRange);
      $('#miscModalHtml').hide().empty();
      $('#miscPdfFrame').show().attr('src', url3);
      $('#miscModal').modal('show');
      return;
    }

    // other reports: fetch data and show modal
    $.post(listUrl, payload)
      .done(function (res) {
        var rows = Array.isArray(res.data) ? res.data : [];
        var titleMap = {
          'advance': 'Advance Register',
          'damage': 'Damage Register',
          'workman': 'Work Man Register',
          'employment': 'Employment Card',
          'fine': 'Fine Register',
          'service': 'Service Certificate',
          'overtime': 'Over Time Register'
        };
        var title = titleMap[reportChoice] || 'Report';

        var html = '<div class="table-responsive"><table class="table table-sm table-bordered"><thead><tr><th>SL</th><th>EMPID</th><th>NAME</th><th>SITE</th></tr></thead><tbody>';
        rows.forEach(function (r, idx) {
          html += '<tr>' +
                  '<td>' + (idx + 1) + '</td>' +
                  '<td>' + (r.empid || '') + '</td>' +
                  '<td>' + (r.name || '') + '</td>' +
                  '<td>' + (r.site || '') + '</td>' +
                  '</tr>';
        });
        html += '</tbody></table></div>';

        $('#miscModalLabel').text(title + ' - ' + dateRange);
        $('#miscPdfFrame').hide().attr('src', '');
        $('#miscModalHtml').show().html(html);
        $('#miscModal').modal('show');
      })
      .fail(function (xhr) {
        Swal.fire({ title: 'Error', text: 'Failed to load data', icon: 'error' });
      });
  });
});
</script>

<style>
  /* Make modal as large as possible (Bootstrap 4 / AdminLTE friendly) */
  #miscModal .modal-dialog {
    max-width: 98vw;
    width: 98vw;
    margin: 0.5rem auto;
  }
  #miscModal .modal-content {
    height: calc(100vh - 1rem);
  }
  #miscModal .modal-body {
    overflow: hidden;
  }
  .misc-underline { text-decoration: underline; }
  .misc-register-table th, .misc-register-table td { vertical-align: middle; }
</style>
@endpush
