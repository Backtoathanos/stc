<?php  
// Include authentication helper
require_once 'kattegat/auth_helper.php';
// Check authentication using the new hybrid system
STCAuthHelper::checkAuth(); 
?> 
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>GLD Summary - STC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="">
    <meta name="msapplication-tap-highlight" content="no">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="./main.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .card { border-radius: 10px; }
        .stc-dash-month { transition: all 0.3s ease; }
        .stc-dash-month:focus { box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25) !important; }
        .h-200 { height: 200px !important; }
    </style>
</head>
<body>
<div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
    <?php include_once("header-nav.php");?>
    <div class="app-main">
        <?php include_once("sidebar-nav.php");?>                   
        <div class="app-main__outer">
            <div class="app-main__inner">

                <div class="row">
                    <div class="col-md-12 col-xl-12">
                        <div class="card mb-4 border-success shadow-sm">
                            <div class="card-body p-4" style="font-size: 15px;">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm" style="background: linear-gradient(-20deg, #d4eeff 0%, #fdb8b5 100%); border-radius: 12px;">
                                            <div class="card-body py-3">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h5 class="mb-0 font-weight-bold text-dark">Select Period:</h5>
                                                    <select class="form-select stc-dash-type border-0 shadow-sm"
                                                            style="background: rgba(255,255,255,0.8); border-radius: 8px; padding: 8px; width: 50%; cursor: pointer;">
                                                        <option selected value="A">∞ All time</option>
                                                        <option value="R">📅 Date range</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <div class="card border-0 shadow-sm" style="background: linear-gradient(-20deg, #e3f2fd 0%, #ffcdd2 100%); border-radius: 12px;">
                                            <div class="card-body py-3">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h5 class="mb-0 font-weight-bold text-dark" id="stc-gld-period-label">Date range:</h5>
                                                    <div class="d-flex align-items-center justify-content-end" style="gap: 8px; width: 50%;">
                                                        <input type="date"
                                                               class="form-control stc-gld-from border-0 shadow-sm"
                                                               style="background: rgba(255,255,255,0.8); border-radius: 8px; padding: 8px; cursor: pointer;"
                                                               value="<?php echo date('Y-m-01');?>"/>
                                                        <input type="date"
                                                               class="form-control stc-gld-to border-0 shadow-sm"
                                                               style="background: rgba(255,255,255,0.8); border-radius: 8px; padding: 8px; cursor: pointer;"
                                                               value="<?php echo date('Y-m-d');?>"/>
                                                    </div>
                                                </div>
                                                <div class="text-muted mt-2" id="stc-gld-range-hint" style="font-size: 12px; display:none;">
                                                    Tip: pick From/To dates then data will update.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- GLD Summary Cards Section -->
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="card border-0 shadow-sm" style="background: linear-gradient(-20deg, #e0f7fa 0%, #80deea 100%);">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center justify-content-between flex-wrap">
                                                    <h5 class="card-title font-weight-bold mb-3 text-dark" style="margin:0;">
                                                        <i class="fa fa-cubes"></i> GLD Summary
                                                    </h5>
                                                    <a href="dashboard.php" class="btn btn-sm btn-outline-primary" style="font-size: 12px;">Back to dashboard</a>
                                                </div>

                                                <div class="row mb-3 mt-3">
                                                    <div class="col-md-4">
                                                        <div class="alert alert-info mb-0"><b>Total Purchase:</b> <span class="gld-total-purchase">--</span></div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="alert alert-primary mb-0"><b>Total Stock:</b> <span class="gld-total-stock">--</span></div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="alert alert-primary mb-0"><b>Total Sale:</b> <span class="gld-total-sale">--</span></div>
                                                    </div>

                                                    <div class="col-md-4 mt-3">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-hover mb-0" id="gld-summary-table2">
                                                                <thead class="thead-dark">
                                                                <tr><th>Branch/Location</th><th>Amount (₹)</th></tr>
                                                                </thead>
                                                                <tbody></tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 mt-3">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-hover mb-0" id="gld-summary-table3">
                                                                <thead class="thead-dark">
                                                                <tr><th>Branch/Location</th><th>Amount (₹)</th></tr>
                                                                </thead>
                                                                <tbody></tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 mt-3">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-hover mb-0" id="gld-summary-table">
                                                                <thead class="thead-dark">
                                                                <tr><th>Branch/Location</th><th>Amount (₹)</th></tr>
                                                                </thead>
                                                                <tbody></tbody>
                                                            </table>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="mt-4">
                                                            <canvas id="gldDonutChart" width="400" height="220"></canvas>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="mt-4">
                                                            <canvas id="gldBarChart" width="400" height="220"></canvas>
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
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script src="assets/vendor/bootstrap/js/popper.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="./assets/scripts/loginopr.js"></script>
<script type="text/javascript" src="./assets/scripts/sidebar-persist.js"></script>
<script type="text/javascript" src="./assets/scripts/main.js"></script>
<script type="text/javascript" src="./assets/scripts/jarvis.js"></script>
<script>
$(document).ready(function(){
    var type = $('.stc-dash-type').val();
    var dateFrom = $('.stc-gld-from').val();
    var dateTo = $('.stc-gld-to').val();
    var monthStr = (new Date().getFullYear()) + '-' + String(new Date().getMonth() + 1).padStart(2, '0');

    var gldDonutChartInstance = null;
    var gldBarChartInstance = null;

    function stc_reload_gld(month, preload){
        $.ajax({
            url: "kattegat/ragnar_lothbrok.php",
            method: "post",
            data: {
                dashboard: 1,
                // month kept for backward compatibility on backend; ignored for A/R.
                month: month,
                type: type,
                preload: preload,
                date_from: dateFrom,
                date_to: dateTo
            },
            dataType: 'JSON',
            success: function(data){
                // keep ranges as-is; backend may still return normalized month for dashboard

                var gld = (data && data[11]) ? data[11] : {};
                $('.gld-total-purchase').text(gld.total_purchase !== undefined ? parseFloat(gld.total_purchase).toLocaleString('en-IN', {minimumFractionDigits:2}) : '--');
                $('.gld-total-sale').text(gld.total_sale !== undefined ? parseFloat(gld.total_sale).toLocaleString('en-IN', {minimumFractionDigits:2}) : '--');
                $('.gld-total-stock').text(gld.total_stock !== undefined ? parseFloat(gld.total_stock).toLocaleString('en-IN', {minimumFractionDigits:2}) : '--');

                var gldRowsSale = '';
                var gldRowsPurchase = '';
                var donutLabels = [];
                var donutData = [];

                if(Array.isArray(gld.sub_locations_sale) && gld.sub_locations_sale.length > 0) {
                    var totalSale=0;
                    $.each(gld.sub_locations_sale, function(i, item) {
                        totalSale += parseFloat(item.sale_amount);
                        gldRowsSale += '<tr><td>' + item.sale_location + '</td><td class="text-right">₹ ' + parseFloat(item.sale_amount).toLocaleString('en-IN', {minimumFractionDigits:2}) + '</td></tr>';
                        donutLabels.push(item.sale_location);
                        donutData.push(item.sale_amount);
                    });
                    gldRowsSale += '<tr><td><b>Total:</b></td><td class="text-right"><b>₹ ' + parseFloat(totalSale).toLocaleString('en-IN', {minimumFractionDigits:2}) + '</b></td></tr>';
                } else {
                    gldRowsSale = '<tr><td colspan="2" class="text-center text-muted">No data found for this period.</td></tr>';
                }
                $('#gld-summary-table tbody').html(gldRowsSale);

                if(Array.isArray(gld.sub_locations_purchase) && gld.sub_locations_purchase.length > 0) {
                    var totalPurchase=0;
                    $.each(gld.sub_locations_purchase, function(i, item) {
                        totalPurchase += parseFloat(item.purchase_amount);
                        gldRowsPurchase += '<tr><td>' + item.purchase_location + '</td><td class="text-right">₹ ' + parseFloat(item.purchase_amount).toLocaleString('en-IN', {minimumFractionDigits:2}) + '</td></tr>';
                    });
                    gldRowsPurchase += '<tr><td><b>Total:</b></td><td class="text-right"><b>₹ ' + parseFloat(totalPurchase).toLocaleString('en-IN', {minimumFractionDigits:2}) + '</b></td></tr>';
                } else {
                    gldRowsPurchase = '<tr><td colspan="2" class="text-center text-muted">No data found for this period.</td></tr>';
                }
                $('#gld-summary-table2 tbody').html(gldRowsPurchase);

                // Expenses breakdown table (if provided by backend)
                // Stock breakup table
                var gldRowsStock = '';
                if(Array.isArray(gld.sub_locations_stock) && gld.sub_locations_stock.length > 0) {
                    var totalStock=0;
                    $.each(gld.sub_locations_stock, function(i, item) {
                        totalStock += parseFloat(item.stock_amount);
                        gldRowsStock += '<tr><td>' + item.stock_location + '</td><td class="text-right">₹ ' + parseFloat(item.stock_amount).toLocaleString('en-IN', {minimumFractionDigits:2}) + '</td></tr>';
                    });
                    gldRowsStock += '<tr><td><b>Total:</b></td><td class="text-right"><b>₹ ' + parseFloat(totalStock).toLocaleString('en-IN', {minimumFractionDigits:2}) + '</b></td></tr>';
                } else {
                    gldRowsStock = '<tr><td colspan="2" class="text-center text-muted">No data found for this period.</td></tr>';
                }
                $('#gld-summary-table3 tbody').html(gldRowsStock);

                // Profit table snippet from backend (same index as dashboard)
                if (data && data[13] !== undefined) {
                    $('.stc-gld-profit-analyser-sheet-breakup').html(data[13]);
                }

                // Charts
                var donutEl = document.getElementById('gldDonutChart');
                var barEl = document.getElementById('gldBarChart');
                if (!donutEl || !barEl) return;

                var ctxDonut = donutEl.getContext('2d');
                if(gldDonutChartInstance) { gldDonutChartInstance.destroy(); }
                if(donutLabels.length > 0) {
                    gldDonutChartInstance = new Chart(ctxDonut, {
                        type: 'doughnut',
                        data: { labels: donutLabels, datasets: [{ data: donutData, backgroundColor: ['#42a5f5','#66bb6a','#ffa726','#ab47bc','#ec407a','#ff7043','#26a69a','#d4e157','#8d6e63','#789262'], borderWidth: 2 }] },
                        options: { responsive: true, plugins: { legend: { position: 'bottom' }, title: { display: true, text: 'GLD Branch-wise Distribution' } } }
                    });
                } else {
                    ctxDonut.clearRect(0, 0, donutEl.width, donutEl.height);
                }

                var ctxBar = barEl.getContext('2d');
                if(gldBarChartInstance) { gldBarChartInstance.destroy(); }
                if(donutLabels.length > 0) {
                    gldBarChartInstance = new Chart(ctxBar, {
                        type: 'bar',
                        data: { labels: donutLabels, datasets: [{ label: 'Amount (₹)', data: donutData, backgroundColor: ['#42a5f5','#66bb6a','#ffa726','#ab47bc','#ec407a','#ff7043','#26a69a','#d4e157','#8d6e63','#789262'], borderWidth: 2 }] },
                        options: { responsive: true, plugins: { legend: { display: false }, title: { display: true, text: 'GLD Branch-wise Bar Chart' } }, scales: { y: { beginAtZero: true, ticks: { callback: function(value) { return '₹ ' + value.toLocaleString('en-IN'); } } } } }
                    });
                } else {
                    ctxBar.clearRect(0, 0, barEl.width, barEl.height);
                }
            }
        });
    }

    $('body').on('change', '.stc-dash-type', function() {
        type = $(this).val();
        // Show/hide range hint based on type
        if (type === 'R') {
            $('#stc-gld-range-hint').show();
        } else {
            $('#stc-gld-range-hint').hide();
        }
        // reload after changing period type
        stc_reload_gld(monthStr, 'post');
    });

    $('body').on('change', '.stc-gld-from, .stc-gld-to', function() {
        dateFrom = $('.stc-gld-from').val();
        dateTo = $('.stc-gld-to').val();
        if ($('.stc-dash-type').val() === 'R') {
            stc_reload_gld(monthStr, 'post');
        }
    });

    // Default: all time
    $('#stc-gld-range-hint').hide();
    stc_reload_gld(monthStr, 'preload');
});
</script>
</body>
</html>

