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
      @media (min-width: 992px) {
        .stc-req-filter-toolbar .row {
          display: flex;
          flex-wrap: wrap;
          align-items: flex-end;
        }
        .stc-req-filter-toolbar .stc-req-field { margin-bottom: 0; }
        .stc-req-filter-btns { text-align: right; }
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
                                                Select site (required), then filter by status. Date filter not needed.
                                            </p>
                                            <div class="stc-req-filter-toolbar">
                                                <div class="row">
                                                    <div class="col-md-5 col-sm-6 col-xs-12 stc-req-field">
                                                        <label class="stc-req-lbl" for="stc-rep-project-filter">Site / Project <span class="text-danger">*</span></label>
                                                        <select class="form-control" id="stc-rep-project-filter" required>
                                                            <option value="">Loading…</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-5 col-sm-6 col-xs-12 stc-req-field">
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
                                                    <div class="col-md-2 col-xs-12 stc-req-filter-btns">
                                                        <button type="button" class="btn btn-primary btn-sm stc-rep-search form-control"><i class="fa fa-search"></i> Search</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body stc-reports-result" style="overflow-x:auto;">
                                            <p class="text-muted text-center">Select a site, then choose status and click Search.</p>
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

        function loadReportResults(page){
            page = parseInt(page, 10) || 1;
            if(!canSearch()){
                $('.stc-reports-result').html('<div class="alert alert-warning text-center">Please select a site / project first.</div>');
                return;
            }
            $('.stc-reports-result').html("Loading...");
            $.ajax({
                url     : "nemesis/stc_agcart.php",
                method  : "POST",
                data    : {
                    call_searched_requisition_report: 1,
                    supreq_project_id: $('#stc-rep-project-filter').val(),
                    supreq_status: $('#stc-rep-status-filter').val(),
                    supreq_page: page,
                    supreq_per_page: 25
                },
                success : function(html){
                    $('.stc-reports-result').html(
                        '<div class="mb-2"><input type="text" class="form-control stc-rep-quick-search" placeholder="Quick search in results…"></div>' + html
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

        $('#stc-rep-status-filter').on('change', function(){
            if(canSearch()){
                loadReportResults(1);
            }
        });

        $('#stc-rep-project-filter').on('change', function(){
            if(canSearch()){
                loadReportResults(1);
            } else {
                $('.stc-reports-result').html('<p class="text-muted text-center">Select a site, then choose status and click Search.</p>');
            }
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
            $('.stc-reports-result > table > tbody tr').each(function(){
                $(this).toggle($(this).text().toLowerCase().indexOf(searchText) !== -1);
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
