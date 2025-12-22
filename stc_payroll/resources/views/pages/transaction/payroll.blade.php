@extends('layouts.header')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Payroll</h3>
  </div>
  <div class="card-body">
    <!-- Filters -->
    <div class="row mb-3">
      <div class="col-md-3">
        <div class="form-group">
          <label>
            <input type="checkbox" id="selectAllSites" checked> All
          </label>
          <label for="filterSite">SITE</label>
          <select class="form-control" id="filterSite" name="site_id">
            <option value="all">All Sites</option>
            @foreach($sites as $site)
              <option value="{{ $site->id }}">{{ $site->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="col-md-3">
        <div class="form-group">
          <label for="filterMonth">Month <span class="text-danger">*</span></label>
          <input type="month" id="filterMonth" class="form-control" name="month_year" value="{{ date('Y-m') }}">
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label>&nbsp;</label>
          <button type="button" class="btn btn-primary btn-block" id="submitFilters">
            <i class="fas fa-search"></i> Submit
          </button>
        </div>
      </div>
      <div class="col-md-4 text-right">
        <div class="form-group">
          <label>&nbsp;</label>
          <div>
            <button type="button" class="btn btn-success" id="downloadBtn">
              <i class="fas fa-download"></i> Download
            </button>
          </div>
          <div class="mt-1">
            <a href="#" id="wagesSummaryLink" class="text-primary">Wages Summary</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Contractor and Employer Details -->
    <div class="row mb-3">
      <div class="col-md-12">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title">Contractor and Employer Details</h3>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label><strong>Nature & Address of Contractor:</strong></label>
                  <p>GLOBAL AC SYSTEM<br>502/A Jawaharnagar Road-17 Azadnagar Mango Jsr-832110</p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label><strong>Name & Address of Principal Employer:</strong></label>
                  <p id="principalEmployer">-</p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label><strong>Name & Address of the Establishment:</strong></label>
                  <p>Under Which Contract is carried on<br><span id="establishmentName">-</span></p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label><strong>Name & Location of Work:</strong></label>
                  <p id="workLocation">-</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabs -->
    <ul class="nav nav-tabs" id="payrollTabs" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="payroll-tab" data-toggle="tab" href="#payroll" role="tab">Payroll</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="summary-tab" data-toggle="tab" href="#summary" role="tab">Summary</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="slip-tab" data-toggle="tab" href="#slip" role="tab">Slip</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="bank-tab" data-toggle="tab" href="#bank" role="tab">Bank</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="bank-other-tab" data-toggle="tab" href="#bank-other" role="tab">Bank Other</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="pf-tab" data-toggle="tab" href="#pf" role="tab">PF</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="esic-tab" data-toggle="tab" href="#esic" role="tab">ESIC</a>
      </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="payrollTabContent">
      <!-- Payroll Tab -->
      <div class="tab-pane fade show active" id="payroll" role="tabpanel">
        <div class="mt-3">
          <div class="row mb-2">
            <div class="col-md-6">
              <h4>PAYROLL</h4>
            </div>
          </div>
          
          <div class="table-responsive">
            <table id="payrollTable" class="table table-bordered table-striped table-sm">
              <thead class="thead-light">
                <tr>
                  <th>SL</th>
                  <th>EMPID</th>
                  <th>NAME</th>
                  <th>SITE</th>
                  <th>WORKED</th>
                  <th>RATE</th>
                  <th>BASIC</th>
                  <th>DA</th>
                  <th>HRA</th>
                  <th>OTHER CASH</th>
                  <th>OT HRS</th>
                  <th>OT AMT</th>
                  <th>OTHER ALLOWANCE</th>
                  <th>GROSS</th>
                  <th>PF</th>
                  <th>ESIC</th>
                  <th>PRF TAX</th>
                  <th>ADVANCE</th>
                  <th>DEDUCTION</th>
                  <th>NET AMT</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
              <tfoot>
                <tr class="table-info font-weight-bold">
                  <td colspan="19" class="text-right">TOTAL NET AMOUNT:</td>
                  <td class="text-right" id="payrollTotalNetAmt">0</td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>

      <!-- Summary Tab -->
      <div class="tab-pane fade" id="summary" role="tabpanel">
        <div class="mt-3">
          <div class="row mb-2">
            <div class="col-md-6">
              <h4>SUMMARY</h4>
            </div>
          </div>
          
          <div class="table-responsive">
            <table id="summaryTable" class="table table-bordered table-striped table-sm">
              <thead class="thead-light">
                <tr>
                  <th>SL</th>
                  <th>EMPID</th>
                  <th>NAME</th>
                  <th>WORKED</th>
                  <th>RATE</th>
                  <th>ACTUAL AMT</th>
                  <th>OT HRS</th>
                  <th>OT AMT</th>
                  <th>ALLOWANCE</th>
                  <th>TOTAL</th>
                </tr>
              </thead>
              <tbody id="summaryTableBody">
                <tr>
                  <td colspan="10" class="text-center">Please select month and click Submit to load summary</td>
                </tr>
              </tbody>
              <tfoot>
                <tr class="table-info font-weight-bold">
                  <td colspan="9" class="text-right">TOTAL:</td>
                  <td class="text-right" id="summaryTotal">0</td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
      <!-- Slip Tab -->
      <div class="tab-pane fade" id="slip" role="tabpanel">
        <div class="mt-3">
          <div class="row mb-2">
            <div class="col-md-6">
              <h4>SLIP</h4>
            </div>
          </div>
          
          <div class="table-responsive">
            <table id="slipTable" class="table table-bordered table-striped table-sm">
              <thead class="thead-light">
                <tr>
                  <th>SL</th>
                  <th>EMPID</th>
                  <th>WORKED</th>
                  <th>BASIC</th>
                  <th>DA</th>
                  <th>GROSS</th>
                  <th>ADVANCE</th>
                  <th>DEDUCTION</th>
                  <th>NET AMT</th>
                  <th>VIEW</th>
                  <th>NAME</th>
                </tr>
              </thead>
              <tbody id="slipTableBody">
                <tr>
                  <td colspan="11" class="text-center">Please select month and click Submit to load slip data</td>
                </tr>
              </tbody>
              <tfoot>
                <tr class="table-info font-weight-bold">
                  <td colspan="8" class="text-right">TOTAL:</td>
                  <td class="text-right" id="slipTotalNetAmt">0</td>
                  <td colspan="2"></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
      <!-- Bank Tab -->
      <div class="tab-pane fade" id="bank" role="tabpanel">
        <div class="mt-3">
          <div class="row mb-2">
            <div class="col-md-6">
              <h4>PAYROLL BANK STATEMENT</h4>
            </div>
          </div>
          
          <div class="table-responsive">
            <table id="bankTable" class="table table-bordered table-striped table-sm">
              <thead class="thead-light">
                <tr>
                  <th>SL</th>
                  <th>EMPID</th>
                  <th>NAME</th>
                  <th>BANK</th>
                  <th>IFSC</th>
                  <th>AC/NO</th>
                  <th>NET AMT</th>
                </tr>
              </thead>
              <tbody id="bankTableBody">
                <tr>
                  <td colspan="7" class="text-center">Please select month and click Submit to load bank statement</td>
                </tr>
              </tbody>
              <tfoot>
                <tr class="table-info font-weight-bold">
                  <td colspan="6" class="text-right">TOTAL:</td>
                  <td class="text-right" id="bankTotalNetAmt">0</td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
      <!-- Bank Other Tab -->
      <div class="tab-pane fade" id="bank-other" role="tabpanel">
        <div class="mt-3">
          <div class="row mb-2">
            <div class="col-md-6">
              <h4>SUMMARY BANK STATEMENT</h4>
            </div>
          </div>
          
          <div class="table-responsive">
            <table id="bankOtherTable" class="table table-bordered table-striped table-sm">
              <thead class="thead-light">
                <tr>
                  <th>SL</th>
                  <th>EMPID</th>
                  <th>NAME</th>
                  <th>BANK</th>
                  <th>IFSC</th>
                  <th>AC/NO</th>
                  <th>NET AMT</th>
                </tr>
              </thead>
              <tbody id="bankOtherTableBody">
                <tr>
                  <td colspan="7" class="text-center">Please select month and click Submit to load bank other statement</td>
                </tr>
              </tbody>
              <tfoot>
                <tr class="table-info font-weight-bold">
                  <td colspan="6" class="text-right">TOTAL:</td>
                  <td class="text-right" id="bankOtherTotalNetAmt">0</td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
      <!-- PF Tab -->
      <div class="tab-pane fade" id="pf" role="tabpanel">
        <div class="mt-3">
          <div class="row mb-2">
            <div class="col-md-6">
              <h4>PF REPORT</h4>
            </div>
          </div>
          
          <div class="table-responsive">
            <table id="pfTable" class="table table-bordered table-striped table-sm">
              <thead class="thead-light">
                <tr>
                  <th>SL</th>
                  <th>EMPID</th>
                  <th>UAN</th>
                  <th>NAME</th>
                  <th>EPF WAGES</th>
                  <th>EPS WAGES</th>
                  <th>EDLI WAGES</th>
                  <th>PF</th>
                  <th>EPF AMT</th>
                  <th>PPF AMT</th>
                  <th>NCP DAY</th>
                </tr>
              </thead>
              <tbody id="pfTableBody">
                <tr>
                  <td colspan="11" class="text-center">Please select month and click Submit to load PF report</td>
                </tr>
              </tbody>
              <tfoot>
                <tr class="table-info font-weight-bold">
                  <td colspan="7" class="text-right">TOTAL:</td>
                  <td class="text-right" id="pfTotalPF">0</td>
                  <td class="text-right" id="pfTotalEPFAmt">0</td>
                  <td class="text-right" id="pfTotalPPFAmt">0</td>
                  <td></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
      <!-- ESIC Tab -->
      <div class="tab-pane fade" id="esic" role="tabpanel">
        <div class="mt-3">
          <div class="row mb-2">
            <div class="col-md-6">
              <h4>ESIC REPORT</h4>
            </div>
          </div>
          
          <div class="table-responsive">
            <table id="esicTable" class="table table-bordered table-striped table-sm">
              <thead class="thead-light">
                <tr>
                  <th>SL</th>
                  <th>EMPID</th>
                  <th>ESIC</th>
                  <th>NAME</th>
                  <th>DAYS</th>
                  <th>ESIC_CONT_AMT</th>
                  <th>ESIC AMT</th>
                </tr>
              </thead>
              <tbody id="esicTableBody">
                <tr>
                  <td colspan="7" class="text-center">Please select month and click Submit to load ESIC report</td>
                </tr>
              </tbody>
              <tfoot>
                <tr class="table-info font-weight-bold">
                  <td colspan="6" class="text-right">TOTAL:</td>
                  <td class="text-right" id="esicTotalAmt">0</td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#payrollTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '/stc/stc_payroll/transaction/payroll/list',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: function(d) {
                var monthYear = $('#filterMonth').val();
                var siteId = $('#filterSite').val();
                
                // Month input is already in "YYYY-MM" format
                d.month_year = monthYear || '';
                d.site_id = siteId || 'all';
            }
        },
        columns: [
            { data: 'sl', name: 'sl', orderable: false },
            { data: 'empid', name: 'empid' },
            { data: 'name', name: 'name' },
            { data: 'site', name: 'site' },
            { 
                data: 'worked', 
                name: 'worked',
                render: function(data) {
                    if (typeof data === 'object') {
                        var html = '<div style="font-size: 12px; line-height: 1.8;">';
                        html += '<div style="display: table; width: 100%;">';
                        html += '<div style="display: table-row;"><div style="display: table-cell; text-align: left; width: 30%;">P</div><div style="display: table-cell; text-align: center; width: 10%;">-</div><div style="display: table-cell; text-align: right; width: 60%;">' + data.present + '</div></div>';
                        html += '<div style="display: table-row;"><div style="display: table-cell; text-align: left;">NH</div><div style="display: table-cell; text-align: center;">-</div><div style="display: table-cell; text-align: right;">' + data.nh + '</div></div>';
                        html += '<div style="display: table-row;"><div style="display: table-cell; text-align: left;">L</div><div style="display: table-cell; text-align: center;">-</div><div style="display: table-cell; text-align: right;">' + data.l + '</div></div>';
                        html += '<div style="display: table-row; border-top: 1px solid #ddd; padding-top: 3px; margin-top: 3px;"><div style="display: table-cell; text-align: left; padding-top: 3px;"><strong>Total</strong></div><div style="display: table-cell; text-align: center; padding-top: 3px;">-</div><div style="display: table-cell; text-align: right; padding-top: 3px;"><strong>' + data.total + '</strong></div></div>';
                        html += '</div></div>';
                        return html;
                    }
                    return data;
                }
            },
            { 
                data: 'rate', 
                name: 'rate',
                render: function(data) {
                    if (typeof data === 'object') {
                        var html = '<div style="font-size: 12px; line-height: 1.8;">';
                        html += '<div style="display: table; width: 100%;">';
                        html += '<div style="display: table-row;"><div style="display: table-cell; text-align: left; width: 30%;">B</div><div style="display: table-cell; text-align: center; width: 10%;">-</div><div style="display: table-cell; text-align: right; width: 60%;">' + data.basic + '</div></div>';
                        html += '<div style="display: table-row;"><div style="display: table-cell; text-align: left;">Da</div><div style="display: table-cell; text-align: center;">-</div><div style="display: table-cell; text-align: right;">' + data.da + '</div></div>';
                        html += '<div style="display: table-row; border-top: 1px solid #ddd; padding-top: 3px; margin-top: 3px;"><div style="display: table-cell; text-align: left; padding-top: 3px;"><strong>Total</strong></div><div style="display: table-cell; text-align: center; padding-top: 3px;">-</div><div style="display: table-cell; text-align: right; padding-top: 3px;"><strong>' + data.total + '</strong></div></div>';
                        html += '</div></div>';
                        return html;
                    }
                    return data;
                }
            },
            { data: 'basic', name: 'basic' },
            { data: 'da', name: 'da' },
            { data: 'hra', name: 'hra' },
            { data: 'other_cash', name: 'other_cash' },
            { data: 'ot_hrs', name: 'ot_hrs' },
            { data: 'ot_amt', name: 'ot_amt' },
            { data: 'other_allowance', name: 'other_allowance' },
            { data: 'gross', name: 'gross' },
            { data: 'pf', name: 'pf' },
            { data: 'esic', name: 'esic' },
            { data: 'prf_tax', name: 'prf_tax' },
            { data: 'advance', name: 'advance' },
            { data: 'deduction', name: 'deduction' },
            { data: 'net_amt', name: 'net_amt' }
        ],
        order: [[1, 'asc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        deferLoading: 0
    });

    // Submit filters
    $('#submitFilters').on('click', function() {
        var monthYear = $('#filterMonth').val();
        if (!monthYear) {
            Swal.fire({
                icon: 'warning',
                title: 'Month Required',
                text: 'Please select a month'
            });
            return;
        }
        table.draw();
        // Load summary if summary tab is active
        if ($('#summary-tab').hasClass('active')) {
            loadSummary();
        }
        // Load slip if slip tab is active
        if ($('#slip-tab').hasClass('active')) {
            loadSlip();
        }
        // Load bank if bank tab is active
        if ($('#bank-tab').hasClass('active')) {
            loadBank();
        }
        // Load bank other if bank other tab is active
        if ($('#bank-other-tab').hasClass('active')) {
            loadBankOther();
        }
    });
    
    // Summary table DataTable instance
    var summaryTable = null;
    
    // Load summary data
    function loadSummary() {
        var monthYear = $('#filterMonth').val();
        var siteId = $('#filterSite').val();
        
        if (!monthYear) {
            $('#summaryTableBody').html('<tr><td colspan="10" class="text-center text-danger">Please select a month</td></tr>');
            if ($.fn.DataTable.isDataTable('#summaryTable')) {
                $('#summaryTable').DataTable().destroy();
            }
            summaryTable = null;
            return;
        }
        
        $.ajax({
            url: '/stc/stc_payroll/transaction/payroll/summary',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                month_year: monthYear,
                site_id: siteId || 'all'
            },
            success: function(response) {
                // Destroy existing DataTable if it exists
                if ($.fn.DataTable.isDataTable('#summaryTable')) {
                    $('#summaryTable').DataTable().destroy();
                }
                summaryTable = null;
                
                if (response.success && response.data && response.data.length > 0) {
                    var data = response.data;
                    var html = '';
                    var totalTotal = 0;
                    
                    // Build table rows matching the exact format from screenshot
                    data.forEach(function(row) {
                        html += '<tr>';
                        html += '<td>' + row.sl + '</td>';
                        html += '<td>' + row.empid + '</td>';
                        html += '<td>' + row.name + '</td>';
                        html += '<td class="text-right">' + row.worked + '</td>';
                        html += '<td class="text-right">' + row.rate + '</td>';
                        html += '<td class="text-right">' + row.actual_amt + '</td>';
                        html += '<td class="text-right">' + (row.ot_hrs || '') + '</td>';
                        html += '<td class="text-right">' + (row.ot_amt || '') + '</td>';
                        html += '<td class="text-right">' + (row.allowance || '') + '</td>';
                        var totalVal = parseFloat(row.total.replace(/,/g, '')) || 0;
                        totalTotal += totalVal;
                        html += '<td class="text-right">' + row.total + '</td>';
                        html += '</tr>';
                    });
                    
                    $('#summaryTableBody').html(html);
                    
                    // Update total in footer
                    $('#summaryTotal').text(totalTotal.toLocaleString('en-IN', {maximumFractionDigits: 0}));
                    
                    // Initialize DataTable with sorting enabled
                    summaryTable = $('#summaryTable').DataTable({
                        order: [[1, 'asc']],
                        pageLength: 25,
                        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                        columnDefs: [
                            { orderable: false, targets: 0 } // SL column not sortable
                        ],
                        orderClasses: true,
                        searching: true,
                        paging: true,
                        info: true
                    });
                } else {
                    $('#summaryTableBody').html('<tr><td colspan="10" class="text-center text-danger">No data available</td></tr>');
                }
            },
            error: function(xhr) {
                console.error('Error loading summary:', xhr);
                $('#summaryTableBody').html('<tr><td colspan="10" class="text-center text-danger">Error loading summary data</td></tr>');
                if ($.fn.DataTable.isDataTable('#summaryTable')) {
                    $('#summaryTable').DataTable().destroy();
                }
                summaryTable = null;
            }
        });
    }
    
    // Load summary when summary tab is clicked
    $('#summary-tab').on('click', function() {
        loadSummary();
    });
    
    // Refresh summary button
    $('#refreshSummary').on('click', function() {
        loadSummary();
    });
    
    // Slip table DataTable instance
    var slipTable = null;
    
    // Load slip data
    function loadSlip() {
        var monthYear = $('#filterMonth').val();
        var siteId = $('#filterSite').val();
        
        if (!monthYear) {
            $('#slipTableBody').html('<tr><td colspan="11" class="text-center text-danger">Please select a month</td></tr>');
            if ($.fn.DataTable.isDataTable('#slipTable')) {
                $('#slipTable').DataTable().destroy();
            }
            slipTable = null;
            return;
        }
        
        $.ajax({
            url: '/stc/stc_payroll/transaction/payroll/slip',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                month_year: monthYear,
                site_id: siteId || 'all'
            },
            success: function(response) {
                // Destroy existing DataTable if it exists
                if ($.fn.DataTable.isDataTable('#slipTable')) {
                    $('#slipTable').DataTable().destroy();
                }
                slipTable = null;
                
                if (response.success && response.data && response.data.length > 0) {
                    var data = response.data;
                    var html = '';
                    var totalNetAmt = 0;
                    
                    // Build table rows matching the exact format from screenshot
                    data.forEach(function(row) {
                        html += '<tr>';
                        html += '<td>' + row.sl + '</td>';
                        html += '<td>' + row.empid + '</td>';
                        html += '<td class="text-right">' + row.worked + '</td>';
                        html += '<td class="text-right">' + row.basic + '</td>';
                        html += '<td class="text-right">' + (row.da || '') + '</td>';
                        html += '<td class="text-right">' + row.gross + '</td>';
                        html += '<td class="text-right">' + (row.advance || '') + '</td>';
                        html += '<td class="text-right">' + row.deduction + '</td>';
                        var netAmtVal = parseFloat(row.net_amt.replace(/,/g, '')) || 0;
                        totalNetAmt += netAmtVal;
                        html += '<td class="text-right">' + row.net_amt + '</td>';
                        html += '<td><button type="button" class="btn btn-sm btn-warning view-slip-btn" data-aadhar="' + row.aadhar + '" data-month="' + monthYear + '">View Slip</button></td>';
                        html += '<td>' + row.name + '</td>';
                        html += '</tr>';
                    });
                    
                    $('#slipTableBody').html(html);
                    
                    // Update total in footer
                    $('#slipTotalNetAmt').text(totalNetAmt.toLocaleString('en-IN', {maximumFractionDigits: 0}));
                    
                    // Initialize DataTable with sorting enabled
                    slipTable = $('#slipTable').DataTable({
                        order: [[1, 'asc']],
                        pageLength: 25,
                        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                        columnDefs: [
                            { orderable: false, targets: [0, 9] } // SL and VIEW columns not sortable
                        ],
                        orderClasses: true,
                        searching: true,
                        paging: true,
                        info: true
                    });
                } else {
                    $('#slipTableBody').html('<tr><td colspan="11" class="text-center text-danger">No data available</td></tr>');
                }
            },
            error: function(xhr) {
                console.error('Error loading slip:', xhr);
                $('#slipTableBody').html('<tr><td colspan="11" class="text-center text-danger">Error loading slip data</td></tr>');
                if ($.fn.DataTable.isDataTable('#slipTable')) {
                    $('#slipTable').DataTable().destroy();
                }
                slipTable = null;
            }
        });
    }
    
    // Load slip when slip tab is clicked
    $('#slip-tab').on('click', function() {
        loadSlip();
    });
    
    // Refresh slip button
    $('#refreshSlip').on('click', function() {
        loadSlip();
    });
    
    // View slip button click handler (placeholder for future implementation)
    $(document).on('click', '.view-slip-btn', function() {
        var aadhar = $(this).data('aadhar');
        var month = $(this).data('month');
        // TODO: Implement view slip modal or page
        Swal.fire({
            icon: 'info',
            title: 'View Slip',
            text: 'Slip view functionality will be implemented soon for Aadhar: ' + aadhar + ', Month: ' + month
        });
    });
    
    // Bank table DataTable instance
    var bankTable = null;
    
    // Load bank data
    function loadBank() {
        var monthYear = $('#filterMonth').val();
        var siteId = $('#filterSite').val();
        
        if (!monthYear) {
            $('#bankTableBody').html('<tr><td colspan="7" class="text-center text-danger">Please select a month</td></tr>');
            if ($.fn.DataTable.isDataTable('#bankTable')) {
                $('#bankTable').DataTable().destroy();
            }
            bankTable = null;
            return;
        }
        
        $.ajax({
            url: '/stc/stc_payroll/transaction/payroll/bank',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                month_year: monthYear,
                site_id: siteId || 'all'
            },
            success: function(response) {
                // Destroy existing DataTable if it exists
                if ($.fn.DataTable.isDataTable('#bankTable')) {
                    $('#bankTable').DataTable().destroy();
                }
                bankTable = null;
                
                if (response.success && response.data && response.data.length > 0) {
                    var data = response.data;
                    var html = '';
                    var totalNetAmt = 0;
                    
                    // Build table rows matching the exact format from screenshot
                    data.forEach(function(row) {
                        html += '<tr>';
                        html += '<td>' + row.sl + '</td>';
                        html += '<td>' + row.empid + '</td>';
                        html += '<td>' + row.name + '</td>';
                        html += '<td>' + (row.bank || '') + '</td>';
                        html += '<td>' + (row.ifsc || '') + '</td>';
                        html += '<td>' + (row.ac_no || '') + '</td>';
                        var netAmtVal = parseFloat(row.net_amt.replace(/,/g, '')) || 0;
                        totalNetAmt += netAmtVal;
                        html += '<td class="text-right">' + row.net_amt + '</td>';
                        html += '</tr>';
                    });
                    
                    $('#bankTableBody').html(html);
                    
                    // Update total in footer
                    $('#bankTotalNetAmt').text(totalNetAmt.toLocaleString('en-IN', {maximumFractionDigits: 0}));
                    
                    // Initialize DataTable with sorting enabled
                    bankTable = $('#bankTable').DataTable({
                        order: [[1, 'asc']],
                        pageLength: 25,
                        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                        columnDefs: [
                            { orderable: false, targets: 0 } // SL column not sortable
                        ],
                        orderClasses: true,
                        searching: true,
                        paging: true,
                        info: true
                    });
                } else {
                    $('#bankTableBody').html('<tr><td colspan="7" class="text-center text-danger">No data available</td></tr>');
                }
            },
            error: function(xhr) {
                console.error('Error loading bank:', xhr);
                $('#bankTableBody').html('<tr><td colspan="7" class="text-center text-danger">Error loading bank statement</td></tr>');
                if ($.fn.DataTable.isDataTable('#bankTable')) {
                    $('#bankTable').DataTable().destroy();
                }
                bankTable = null;
            }
        });
    }
    
    // Load bank when bank tab is clicked
    $('#bank-tab').on('click', function() {
        loadBank();
    });
    
    // Refresh bank button
    $('#refreshBank').on('click', function() {
        loadBank();
    });
    
    // Bank Other table DataTable instance
    var bankOtherTable = null;
    
    // Load bank other data
    function loadBankOther() {
        var monthYear = $('#filterMonth').val();
        var siteId = $('#filterSite').val();
        
        if (!monthYear) {
            $('#bankOtherTableBody').html('<tr><td colspan="7" class="text-center text-danger">Please select a month</td></tr>');
            if ($.fn.DataTable.isDataTable('#bankOtherTable')) {
                $('#bankOtherTable').DataTable().destroy();
            }
            bankOtherTable = null;
            return;
        }
        
        $.ajax({
            url: '/stc/stc_payroll/transaction/payroll/bank-other',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                month_year: monthYear,
                site_id: siteId || 'all'
            },
            success: function(response) {
                // Destroy existing DataTable if it exists
                if ($.fn.DataTable.isDataTable('#bankOtherTable')) {
                    $('#bankOtherTable').DataTable().destroy();
                }
                bankOtherTable = null;
                
                if (response.success && response.data && response.data.length > 0) {
                    var data = response.data;
                    var html = '';
                    var totalNetAmt = 0;
                    
                    // Build table rows matching the exact format from screenshot
                    data.forEach(function(row) {
                        html += '<tr>';
                        html += '<td>' + row.sl + '</td>';
                        html += '<td>' + row.empid + '</td>';
                        html += '<td>' + row.name + '</td>';
                        html += '<td>' + (row.bank || '') + '</td>';
                        html += '<td>' + (row.ifsc || '') + '</td>';
                        html += '<td>' + (row.ac_no || '') + '</td>';
                        var netAmtVal = parseFloat(row.net_amt.replace(/,/g, '')) || 0;
                        totalNetAmt += netAmtVal;
                        html += '<td class="text-right">' + row.net_amt + '</td>';
                        html += '</tr>';
                    });
                    
                    $('#bankOtherTableBody').html(html);
                    
                    // Update total in footer
                    $('#bankOtherTotalNetAmt').text(totalNetAmt.toLocaleString('en-IN', {maximumFractionDigits: 0}));
                    
                    // Initialize DataTable with sorting enabled
                    bankOtherTable = $('#bankOtherTable').DataTable({
                        order: [[1, 'asc']],
                        pageLength: 25,
                        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                        columnDefs: [
                            { orderable: false, targets: 0 } // SL column not sortable
                        ],
                        orderClasses: true,
                        searching: true,
                        paging: true,
                        info: true
                    });
                } else {
                    $('#bankOtherTableBody').html('<tr><td colspan="7" class="text-center text-danger">No data available</td></tr>');
                }
            },
            error: function(xhr) {
                console.error('Error loading bank other:', xhr);
                $('#bankOtherTableBody').html('<tr><td colspan="7" class="text-center text-danger">Error loading bank other statement</td></tr>');
                if ($.fn.DataTable.isDataTable('#bankOtherTable')) {
                    $('#bankOtherTable').DataTable().destroy();
                }
                bankOtherTable = null;
            }
        });
    }
    
    // Load bank other when bank other tab is clicked
    $('#bank-other-tab').on('click', function() {
        loadBankOther();
    });
    
    // Refresh bank other button
    $('#refreshBankOther').on('click', function() {
        loadBankOther();
    });
    
    // PF table DataTable instance
    var pfTable = null;
    
    // Load PF data
    function loadPF() {
        var monthYear = $('#filterMonth').val();
        var siteId = $('#filterSite').val();
        
        if (!monthYear) {
            $('#pfTableBody').html('<tr><td colspan="11" class="text-center text-danger">Please select a month</td></tr>');
            if ($.fn.DataTable.isDataTable('#pfTable')) {
                $('#pfTable').DataTable().destroy();
            }
            pfTable = null;
            return;
        }
        
        $.ajax({
            url: '/stc/stc_payroll/transaction/payroll/pf',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                month_year: monthYear,
                site_id: siteId || 'all'
            },
            success: function(response) {
                // Destroy existing DataTable if it exists
                if ($.fn.DataTable.isDataTable('#pfTable')) {
                    $('#pfTable').DataTable().destroy();
                }
                pfTable = null;
                
                if (response.success && response.data && response.data.length > 0) {
                    var data = response.data;
                    var html = '';
                    var totalPF = 0;
                    var totalEPFAmt = 0;
                    var totalPPFAmt = 0;
                    
                    // Build table rows matching the exact format from screenshot
                    data.forEach(function(row) {
                        html += '<tr>';
                        html += '<td>' + row.sl + '</td>';
                        html += '<td>' + row.empid + '</td>';
                        html += '<td>' + (row.uan || '') + '</td>';
                        html += '<td>' + row.name + '</td>';
                        html += '<td class="text-right">' + row.epf_wages + '</td>';
                        html += '<td class="text-right">' + row.eps_wages + '</td>';
                        html += '<td class="text-right">' + row.edli_wages + '</td>';
                        var pfVal = parseFloat(row.pf.replace(/,/g, '')) || 0;
                        totalPF += pfVal;
                        html += '<td class="text-right">' + row.pf + '</td>';
                        var epfAmtVal = parseFloat(row.epf_amt.replace(/,/g, '')) || 0;
                        totalEPFAmt += epfAmtVal;
                        html += '<td class="text-right">' + row.epf_amt + '</td>';
                        var ppfAmtVal = parseFloat(row.ppf_amt.replace(/,/g, '')) || 0;
                        totalPPFAmt += ppfAmtVal;
                        html += '<td class="text-right">' + row.ppf_amt + '</td>';
                        html += '<td class="text-right">' + row.ncp_day + '</td>';
                        html += '</tr>';
                    });
                    
                    $('#pfTableBody').html(html);
                    
                    // Update totals in footer
                    $('#pfTotalPF').text(totalPF.toLocaleString('en-IN', {maximumFractionDigits: 0}));
                    $('#pfTotalEPFAmt').text(totalEPFAmt.toLocaleString('en-IN', {maximumFractionDigits: 0}));
                    $('#pfTotalPPFAmt').text(totalPPFAmt.toLocaleString('en-IN', {maximumFractionDigits: 0}));
                    
                    // Initialize DataTable with sorting enabled
                    pfTable = $('#pfTable').DataTable({
                        order: [[1, 'asc']],
                        pageLength: 25,
                        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                        columnDefs: [
                            { orderable: false, targets: 0 } // SL column not sortable
                        ],
                        orderClasses: true,
                        searching: true,
                        paging: true,
                        info: true
                    });
                } else {
                    $('#pfTableBody').html('<tr><td colspan="11" class="text-center text-danger">No data available</td></tr>');
                }
            },
            error: function(xhr) {
                console.error('Error loading PF:', xhr);
                $('#pfTableBody').html('<tr><td colspan="11" class="text-center text-danger">Error loading PF report</td></tr>');
                if ($.fn.DataTable.isDataTable('#pfTable')) {
                    $('#pfTable').DataTable().destroy();
                }
                pfTable = null;
            }
        });
    }
    
    // Load PF when PF tab is clicked
    $('#pf-tab').on('click', function() {
        loadPF();
    });
    
    // Refresh PF button
    $('#refreshPF').on('click', function() {
        loadPF();
    });
    
    // ESIC table DataTable instance
    var esicTable = null;
    
    // Load ESIC data
    function loadESIC() {
        var monthYear = $('#filterMonth').val();
        var siteId = $('#filterSite').val();
        
        if (!monthYear) {
            $('#esicTableBody').html('<tr><td colspan="7" class="text-center text-danger">Please select a month</td></tr>');
            if ($.fn.DataTable.isDataTable('#esicTable')) {
                $('#esicTable').DataTable().destroy();
            }
            esicTable = null;
            return;
        }
        
        $.ajax({
            url: '/stc/stc_payroll/transaction/payroll/esic',
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                month_year: monthYear,
                site_id: siteId || 'all'
            },
            success: function(response) {
                // Destroy existing DataTable if it exists
                if ($.fn.DataTable.isDataTable('#esicTable')) {
                    $('#esicTable').DataTable().destroy();
                }
                esicTable = null;
                
                if (response.success && response.data && response.data.length > 0) {
                    var data = response.data;
                    var html = '';
                    var totalESICAmt = 0;
                    
                    // Build table rows matching the exact format from screenshot
                    data.forEach(function(row) {
                        html += '<tr>';
                        html += '<td>' + row.sl + '</td>';
                        html += '<td>' + row.empid + '</td>';
                        html += '<td>' + (row.esic || '') + '</td>';
                        html += '<td>' + row.name + '</td>';
                        html += '<td class="text-right">' + row.days + '</td>';
                        html += '<td class="text-right">' + row.esic_cont_amt + '</td>';
                        var esicAmtVal = parseFloat(row.esic_amt.replace(/,/g, '')) || 0;
                        totalESICAmt += esicAmtVal;
                        html += '<td class="text-right">' + row.esic_amt + '</td>';
                        html += '</tr>';
                    });
                    
                    $('#esicTableBody').html(html);
                    
                    // Update total in footer
                    $('#esicTotalAmt').text(totalESICAmt.toLocaleString('en-IN', {maximumFractionDigits: 0}));
                    
                    // Initialize DataTable with proper sorting
                    esicTable = $('#esicTable').DataTable({
                        order: [[1, 'asc']], // Default sort by EMPID
                        pageLength: 25,
                        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                        columnDefs: [
                            { orderable: false, targets: 0 }, // SL column not sortable
                            { type: 'num', targets: [4, 5, 6] } // DAYS, ESIC_CONT_AMT, ESIC AMT - numeric sorting
                        ],
                        orderClasses: true, // Add classes for sort indicators
                        searching: true,
                        paging: true,
                        info: true
                    });
                } else {
                    $('#esicTableBody').html('<tr><td colspan="7" class="text-center text-danger">No data available</td></tr>');
                }
            },
            error: function(xhr) {
                console.error('Error loading ESIC:', xhr);
                $('#esicTableBody').html('<tr><td colspan="7" class="text-center text-danger">Error loading ESIC report</td></tr>');
                if ($.fn.DataTable.isDataTable('#esicTable')) {
                    $('#esicTable').DataTable().destroy();
                }
                esicTable = null;
            }
        });
    }
    
    // Load ESIC when ESIC tab is clicked
    $('#esic-tab').on('click', function() {
        loadESIC();
    });
    
    // Refresh ESIC button
    $('#refreshESIC').on('click', function() {
        loadESIC();
    });

    // Select all sites checkbox
    $('#selectAllSites').on('change', function() {
        if ($(this).is(':checked')) {
            $('#filterSite').val('all').prop('disabled', true);
        } else {
            $('#filterSite').prop('disabled', false);
        }
    });

    // Update site info when site changes
    $('#filterSite').on('change', function() {
        var siteId = $(this).val();
        var siteName = $(this).find('option:selected').text();
        
        if (siteId && siteId !== 'all') {
            $('#principalEmployer').text(siteName);
            $('#establishmentName').text(siteName);
            $('#workLocation').text(siteName + ' (' + siteId + ')');
        } else {
            $('#principalEmployer').text('-');
            $('#establishmentName').text('-');
            $('#workLocation').text('-');
        }
    });
    
    // Add footer callback for main payroll table to show totals
    table.on('draw', function() {
        // Calculate totals from all data (not just current page)
        // Use the table variable directly as it's already the DataTable API instance
        var totalNetAmt = 0;
        
        // Get all data from the server response
        // For server-side processing, we need to get totals from the server response
        // Since we can't easily access all rows in server-side mode, we'll calculate from visible rows
        table.rows({page: 'current'}).every(function() {
            var data = this.data();
            if (data.net_amt) {
                var netAmtVal = parseFloat(String(data.net_amt).replace(/,/g, '')) || 0;
                totalNetAmt += netAmtVal;
            }
        });
        
        // Update footer (this will show total for current page only in server-side mode)
        $('#payrollTotalNetAmt').text(totalNetAmt.toLocaleString('en-IN', {maximumFractionDigits: 0}));
    });
});
</script>
@endpush
