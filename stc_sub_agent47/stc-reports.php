<?php
require_once __DIR__ . '/includes/agent_auth.php';
if (($_SESSION['stc_agent_sub_category'] ?? '') !== 'Site Incharge') {
    header('Location: dashboard.php');
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Reports - STC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="./main.css" rel="stylesheet">
    <style>
      .stc-req-filter-toolbar {
        padding: 0 0 14px;
        margin: 0 0 4px;
        border-bottom: 1px solid #e5e8eb;
      }
      .stc-req-filter-toolbar .stc-req-lbl {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #444;
        margin-bottom: 4px;
      }
      .stc-req-filter-toolbar .form-control {
        height: 34px;
        font-size: 13px;
        border-radius: 4px;
      }
      .stc-req-filter-toolbar .stc-req-field { margin-bottom: 10px; }
      .stc-req-dates-row { display: flex; flex-direction: column; }
      .stc-req-dates-row .stc-req-dates-col:not(:last-child) { margin-bottom: 8px; }
      .stc-req-dates-row .stc-req-lbl-sub {
        display: block;
        font-size: 11px;
        font-weight: 600;
        color: #666;
        margin-bottom: 3px;
      }
      .stc-rep-btn-row {
        display: flex;
        align-items: center;
        gap: 8px;
      }
      .stc-rep-btn-row .stc-rep-search { flex: 1; min-width: 0; }
      .stc-rep-btn-row .stc-rep-export-excel {
        flex: 0 0 42px;
        height: 34px;
        padding: 0;
        line-height: 34px;
        font-size: 18px;
      }
      @media (min-width: 992px) {
        .stc-req-filter-toolbar .row {
          display: flex;
          flex-wrap: wrap;
          align-items: flex-end;
        }
        .stc-req-filter-toolbar .stc-req-field { margin-bottom: 0; }
        .stc-req-filter-btns { text-align: right; }
        .stc-rep-btn-row { justify-content: flex-end; }
      }
      .nav-tabs .nav-link.active { font-weight: 600; }
    </style>
</head>
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <?php include_once("header-nav.php");?>
        <div class="app-main">
            <?php include_once("sidebar-nav.php");?>
            <div class="app-main__outer">
                <div class="app-main__inner">
                    <div class="app-page-title">
                        <h3>Reports</h3>
                    </div>

                    <ul class="nav nav-tabs mb-3" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#tab-requisition" role="tab">Requisition</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-requisition" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12 col-xl-12">
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5 class="card-title">Requisition Report</h5>
                                            <p class="text-muted" style="font-size:13px;margin-bottom:12px;">
                                                Select site, date range and status — then click Search.
                                            </p>
                                            <?php
                                              $date = date("d-m-Y");
                                              $newDate = date('Y-m-d', strtotime($date));
                                              $effectiveDate = date('Y-m-d', strtotime("-30 days", strtotime($date)));
                                            ?>
                                            <div class="stc-req-filter-toolbar">
                                                <div class="row">
                                                    <div class="col-md-3 col-sm-12 col-xs-12 stc-req-field">
                                                        <span class="stc-req-lbl">Date range</span>
                                                        <div class="stc-req-dates-row">
                                                            <div class="stc-req-dates-col">
                                                                <label class="stc-req-lbl-sub" for="stc-rep-beg-date">From</label>
                                                                <input type="date" class="form-control" id="stc-rep-beg-date" value="<?php echo $effectiveDate;?>">
                                                            </div>
                                                            <div class="stc-req-dates-col">
                                                                <label class="stc-req-lbl-sub" for="stc-rep-end-date">To</label>
                                                                <input type="date" class="form-control" id="stc-rep-end-date" value="<?php echo $newDate;?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 col-sm-6 col-xs-12 stc-req-field">
                                                        <label class="stc-req-lbl" for="stc-rep-project-filter">Site / Project <span class="text-danger">*</span></label>
                                                        <select class="form-control" id="stc-rep-project-filter" required>
                                                            <option value="">Loading…</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3 col-sm-6 col-xs-12 stc-req-field">
                                                        <label class="stc-req-lbl" for="stc-rep-status-filter">Status</label>
                                                        <select class="form-control" id="stc-rep-status-filter">
                                                            <option value="">All statuses</option>
                                                            <option value="1">Ordered</option>
                                                            <option value="2">Approved</option>
                                                            <option value="3">Accepted</option>
                                                            <option value="4">Dispatched</option>
                                                            <option value="5">Received</option>
                                                            <option value="6">Rejected</option>
                                                            <option value="7">Canceled</option>
                                                            <option value="8">Returned</option>
                                                            <option value="9">Pending</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3 col-xs-12 stc-req-filter-btns stc-req-field">
                                                        <div class="stc-rep-btn-row">
                                                            <button type="button" class="btn btn-primary btn-sm stc-rep-search form-control"><i class="fa fa-search"></i> Search</button>
                                                            <button type="button" class="btn btn-success btn-sm stc-rep-export-excel" title="Export all searched results to Excel" disabled><i class="fa fa-file-excel-o"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body stc-reports-result" style="overflow-x:auto;">
                                            <p class="text-muted text-center">Set filters and click Search.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-log-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Items Log</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="items-log-display"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="./assets/scripts/loginopr.js"></script>
    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    <script type="text/javascript" src="./assets/scripts/jquery.table2excel.js"></script>
    <script>
    $(document).ready(function(){
        function loadRepProjectFilter(){
            $.ajax({
                url     : "nemesis/stc_agcart.php",
                method  : "POST",
                data    : { load_cust_req_filter: 1, require_pick: 1 },
                success : function(html){
                    $('#stc-rep-project-filter').html(html);
                },
                error   : function(){
                    $('#stc-rep-project-filter').html('<option value="">Select Site</option>');
                }
            });
        }
        loadRepProjectFilter();

        function canSearch(){
            var project = $.trim($('#stc-rep-project-filter').val());
            return project !== '' && project !== 'NA';
        }

        function setExportEnabled(on){
            $('.stc-rep-export-excel').prop('disabled', !on);
        }
        setExportEnabled(false);

        function loadReportResults(page){
            page = parseInt(page, 10) || 1;
            setExportEnabled(false);
            if(!canSearch()){
                $('.stc-reports-result').html('<div class="alert alert-warning text-center">Please select a site / project first.</div>');
                return;
            }
            var fromDate = $.trim($('#stc-rep-beg-date').val());
            var toDate = $.trim($('#stc-rep-end-date').val());
            if(!fromDate || !toDate){
                $('.stc-reports-result').html('<div class="alert alert-warning text-center">Please select From and To date.</div>');
                return;
            }
            $('.stc-reports-result').html("Loading...");
            $.ajax({
                url     : "nemesis/stc_agcart.php",
                method  : "POST",
                data    : {
                    call_searched_requisition_report: 1,
                    supreqfromdate: fromDate,
                    supreqtodate: toDate,
                    supreq_project_id: $('#stc-rep-project-filter').val(),
                    supreq_status: $('#stc-rep-status-filter').val(),
                    supreq_page: page,
                    supreq_per_page: 25
                },
                success : function(html){
                    $('.stc-reports-result').html(
                        '<div class="mb-2"><input type="text" class="form-control stc-rep-quick-search" placeholder="Quick search in results…"></div>' + html
                    );
                    setExportEnabled(
                        $('#stc-rep-requisition-table').length > 0 &&
                        $('#stc-rep-requisition-table tbody tr td[colspan]').length === 0
                    );
                },
                error   : function(){
                    $('.stc-reports-result').html('<div class="alert alert-danger">Failed to load report.</div>');
                }
            });
        }

        $('body').delegate('.stc-rep-search', 'click', function(e){
            e.preventDefault();
            loadReportResults(1);
        });

        $('body').delegate('.stc-rep-page-btn', 'click', function(e){
            e.preventDefault();
            var $li = $(this).closest('li');
            if($li.hasClass('disabled') || $li.hasClass('active')){ return; }
            var p = parseInt($(this).attr('data-page'), 10);
            if(isNaN(p) || p < 1){ return; }
            loadReportResults(p);
        });

        $('body').delegate('.stc-rep-quick-search', 'keyup', function(){
            var searchText = $(this).val().toLowerCase();
            $('#stc-rep-requisition-table tbody tr').each(function(){
                $(this).toggle($(this).text().toLowerCase().indexOf(searchText) !== -1);
            });
        });

        $('body').delegate('.stc-rep-export-excel', 'click', function(e){
            e.preventDefault();
            if(!canSearch() || !$.fn.table2excel){ return; }
            var fromDate = $.trim($('#stc-rep-beg-date').val());
            var toDate = $.trim($('#stc-rep-end-date').val());
            if(!fromDate || !toDate){
                alert('Please select From and To date.');
                return;
            }
            var $btn = $(this);
            if($btn.data('busy')){ return; }
            $btn.data('busy', 1).prop('disabled', true);
            var prevHtml = $btn.html();
            $btn.html('<i class="fa fa-spinner fa-spin"></i>');
            $.ajax({
                url     : "nemesis/stc_agcart.php",
                method  : "POST",
                data    : {
                    call_searched_requisition_report_excel: 1,
                    supreqfromdate: fromDate,
                    supreqtodate: toDate,
                    supreq_project_id: $('#stc-rep-project-filter').val(),
                    supreq_status: $('#stc-rep-status-filter').val()
                },
                success : function(html){
                    var $wrap = $('<div id="stc-rep-excel-tmp" style="display:none;"></div>').appendTo('body');
                    $wrap.html(html);
                    var $table = $wrap.find('#stc-rep-requisition-export-table');
                    if(!$table.length || $table.find('tbody tr td[colspan]').length){
                        alert('No results to export.');
                        $wrap.remove();
                        return;
                    }
                    var siteName = $.trim($('#stc-rep-project-filter option:selected').text()) || 'site';
                    siteName = siteName.replace(/[^\w\-]+/g, '_').substring(0, 40);
                    var stamp = new Date().toISOString().slice(0, 10);
                    $table.table2excel({
                        exclude: '.noExl',
                        name: 'Requisition Report',
                        filename: 'stc-requisition-report-' + siteName + '-' + stamp + '.xls',
                        fileext: '.xls',
                        exclude_links: true,
                        exclude_inputs: true
                    });
                    $wrap.remove();
                },
                error   : function(){
                    alert('Failed to export Excel. Please try again.');
                },
                complete: function(){
                    $btn.data('busy', 0).prop('disabled', false).html(prevHtml);
                }
            });
        });

        $('body').delegate('.stc-sup-requisition-viewlog-modal-btn', 'click', function(e){
            e.preventDefault();
            var data = $(this).parent().html();
            $('.items-log-display').html(data);
            $('.items-log-display').find('div').show();
            $('.items-log-display').find('a').remove();
        });
    });
    </script>
</body>
</html>
