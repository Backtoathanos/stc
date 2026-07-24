<?php  
// Include authentication helper
require_once 'kattegat/auth_helper.php';

// Check authentication using the new hybrid system
STCAuthHelper::checkAuth(); 
// $page_code=101;
// include("kattegat/role_check.php");
?> 
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Dashboard - STC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="">
    <meta name="msapplication-tap-highlight" content="no">
    <!-- Latest compiled and minified CSS -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous"> -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="./main.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        .loader {
          border: 16px solid #f3f3f3;
          border-radius: 50%;
          border-top: 16px solid #3498db;
          width: 10px;
          height: 10px;
          -webkit-animation: spin 2s linear infinite; /* Safari */
          animation: spin 2s linear infinite;
        }

        /* Custom full-screen overlay for Accept Return (avoids clipped Bootstrap backdrop) */
        #stcAcceptReturnOverlay {
          display: none;
          position: fixed;
          inset: 0;
          z-index: 100000;
          background: rgba(15, 23, 42, 0.55);
          align-items: center;
          justify-content: center;
          padding: 16px;
          box-sizing: border-box;
        }
        #stcAcceptReturnOverlay.is-open {
          display: flex;
        }
        #stcAcceptReturnOverlay .stc-overlay-dialog {
          background: #fff;
          border-radius: 8px;
          width: 100%;
          max-width: 480px;
          box-shadow: 0 20px 50px rgba(0, 0, 0, 0.28);
          overflow: hidden;
        }
        #stcAcceptReturnOverlay .stc-overlay-header {
          display: flex;
          align-items: center;
          justify-content: space-between;
          padding: 14px 18px;
          background: #f8f9fa;
          border-bottom: 1px solid #dee2e6;
        }
        #stcAcceptReturnOverlay .stc-overlay-header h5 {
          margin: 0;
          font-size: 15px;
          font-weight: 700;
        }
        #stcAcceptReturnOverlay .stc-overlay-close {
          border: 0;
          background: transparent;
          font-size: 22px;
          line-height: 1;
          color: #666;
          cursor: pointer;
          padding: 0 4px;
        }
        #stcAcceptReturnOverlay .stc-overlay-body {
          padding: 16px 20px;
        }
        #stcAcceptReturnOverlay .stc-overlay-footer {
          display: flex;
          justify-content: flex-end;
          gap: 8px;
          padding: 12px 18px;
          background: #f8f9fa;
          border-top: 1px solid #dee2e6;
        }

        /* Adhoc details overlay (Return List eye button) */
        #stcReturnAdhocOverlay {
          display: none;
          position: fixed;
          inset: 0;
          z-index: 100000;
          background: rgba(15, 23, 42, 0.55);
          align-items: center;
          justify-content: center;
          padding: 16px;
          box-sizing: border-box;
        }
        #stcReturnAdhocOverlay.is-open { display: flex; }
        #stcReturnAdhocOverlay .stc-overlay-dialog {
          background: #fff;
          border-radius: 8px;
          width: 100%;
          max-width: 980px;
          max-height: 90vh;
          box-shadow: 0 20px 50px rgba(0, 0, 0, 0.28);
          overflow: hidden;
          display: flex;
          flex-direction: column;
        }
        #stcReturnAdhocOverlay .stc-overlay-header {
          display: flex;
          align-items: center;
          justify-content: space-between;
          padding: 14px 18px;
          background: #f8f9fa;
          border-bottom: 1px solid #dee2e6;
          flex-shrink: 0;
        }
        #stcReturnAdhocOverlay .stc-overlay-header h5 {
          margin: 0;
          font-size: 15px;
          font-weight: 700;
        }
        #stcReturnAdhocOverlay .stc-overlay-close {
          border: 0;
          background: transparent;
          font-size: 22px;
          line-height: 1;
          color: #666;
          cursor: pointer;
          padding: 0 4px;
        }
        #stcReturnAdhocOverlay .stc-overlay-body {
          padding: 16px 20px;
          overflow: auto;
        }
        #stcReturnAdhocOverlay .stc-overlay-footer {
          display: flex;
          justify-content: flex-end;
          gap: 8px;
          padding: 12px 18px;
          background: #f8f9fa;
          border-top: 1px solid #dee2e6;
          flex-shrink: 0;
        }
        .stc-return-adhoc-eye {
          margin-left: 6px;
          color: #3f6ad8;
          cursor: pointer;
          font-size: 14px;
          vertical-align: middle;
        }
        .stc-return-adhoc-eye:hover { color: #1d4ed8; }

        /* Safari */
        @-webkit-keyframes spin {
          0% { -webkit-transform: rotate(0deg); }
          100% { -webkit-transform: rotate(360deg); }
        }

        @keyframes spin {
          0% { transform: rotate(0deg); }
          100% { transform: rotate(360deg); }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                                <div class="card-border mb-3 card card-body border-success">
                                    <div class="row">
                                        <div class="col-md-3 col-xl-3">
                                            <div class="card mb-3 widget-content bg-midnight-bloom">
                                                <div class="widget-content-wrapper text-white">
                                                    <div class="widget-content-left">
                                                        <div class="widget-heading">Total Products</div>
                                                        <div class="widget-subheading"><span class="toproducts"><div class="loader"></div></span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-xl-3">
                                            <div class="card mb-3 widget-content bg-arielle-smile">
                                                <div class="widget-content-wrapper text-white">
                                                    <div class="widget-content-left">
                                                        <div class="widget-heading">In Inventory</div>
                                                        <div class="widget-subheading"><span class="toinventory"><div class="loader"></div></span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-xl-3">
                                            <div class="card mb-3 widget-content bg-love-kiss">
                                                <div class="widget-content-wrapper text-white">
                                                    <div class="widget-content-left">
                                                        <div class="widget-heading">Total Merchants</div>
                                                        <div class="widget-subheading"><span class="tomerchants"><div class="loader"></div></span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-xl-3">
                                            <div class="card mb-3 widget-content bg-vicious-stance">
                                                <div class="widget-content-wrapper text-white">
                                                    <div class="widget-content-left">
                                                        <div class="widget-heading">Total Customers</div>
                                                        <div class="widget-subheading"><span class="tocustomers"><div class="loader"></div></span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-3 col-xl-3">
                                            <div class="card mb-3 widget-content bg-mixed-hopes">
                                                <div class="widget-content-wrapper text-white">
                                                    <div class="widget-content-left">
                                                        <div class="widget-heading">Total Purchased</div>
                                                        <div class="widget-subheading">
                                                            <i class="fa fa-rupee-sign"></i>
                                                            <span class="topurchased">
                                                                <div class="loader"></div>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-xl-3">
                                            <div class="card mb-3 widget-content bg-strong-bliss">
                                                <div class="widget-content-wrapper text-white">
                                                    <div class="widget-content-left">
                                                        <div class="widget-heading">Total Sold</div>
                                                        <div class="widget-subheading">
                                                            <i class="fa fa-rupee-sign"></i>
                                                            <span class="tosoled">
                                                                <div class="loader"></div>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-xl-3">
                                            <div class="card mb-3 widget-content bg-amy-crisp">
                                                <div class="widget-content-wrapper text-white">
                                                    <div class="widget-content-left">
                                                        <div class="widget-heading">Total Merchants Paid</div>
                                                        <div class="widget-subheading">
                                                            <i class="fa fa-rupee-sign"></i>
                                                            <span class="tomerpaid">
                                                                <div class="loader"></div>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-xl-3">
                                            <div class="card mb-3 widget-content bg-night-fade">
                                                <div class="widget-content-wrapper text-white">
                                                    <div class="widget-content-left">
                                                        <div class="widget-heading">Total Customers Paid</div>
                                                        <div class="widget-subheading">
                                                            <i class="fa fa-rupee-sign"></i>
                                                            <span class="tocustpaid">
                                                                <div class="loader"></div>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div><?php 
                                if($_SESSION["stc_empl_role"]>5){
                                    echo '
                                    <div class="row">
                                        <div class="col-md-12 col-xl-12">
                                            <div class="card mb-4 border-success shadow-sm">
                                                <div class="card-body p-4" style="font-size: 15px;">
                                                    <div class="row mb-4">
                                                        <!-- Month Selector Card -->

                                                        <!-- Report Type Selector Card -->
                                                        <div class="col-md-6">
                                                            <div class="card border-0 shadow-sm" style="background: linear-gradient(-20deg, #d4eeff 0%, #fdb8b5 100%); border-radius: 12px;">
                                                                <div class="card-body py-3">
                                                                    <div class="d-flex align-items-center justify-content-between">
                                                                        <h5 class="mb-0 font-weight-bold text-dark">Select Type:</h5>
                                                                        <select 
                                                                            class="form-select stc-dash-type border-0 shadow-sm" 
                                                                            style="background: rgba(255,255,255,0.8); border-radius: 8px; padding: 8px; width: 50%; cursor: pointer;"
                                                                        >
                                                                            <option value="NA">Select Type</option>
                                                                            <option selected value="M">📅 Monthly</option>
                                                                            <option value="Y">📊 Yearly</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-3 mb-md-0">
                                                            <div class="card border-0 shadow-sm" style="background: linear-gradient(-20deg, #e3f2fd 0%, #ffcdd2 100%); border-radius: 12px;">
                                                                <div class="card-body py-3">
                                                                    <div class="d-flex align-items-center justify-content-between">
                                                                        <h5 class="mb-0 font-weight-bold text-dark">Select Month:</h5>
                                                                        <input 
                                                                            type="month" 
                                                                            class="form-control stc-dash-month border-0 shadow-sm" 
                                                                            style="background: rgba(255,255,255,0.8); border-radius: 8px; padding: 8px; width: 50%; cursor: pointer;"
                                                                            value="'.date('Y-m').'"
                                                                        />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Summary Cards Row -->
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="card border-0" style="background: linear-gradient(-20deg, #d4eeff 0%, #ad9aff 100%);">
                                                                <div class="card-body py-3">
                                                                    <div class="d-flex align-items-center justify-content-between">
                                                                        <h5 class="mb-0 font-weight-bold text-center text-dark">STC TRADING</h5>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Purchase Summary -->
                                                        <div class="col-md-4 mb-3">
                                                            <div class="card h-200 border-0 shadow-sm bg-primary text-white">
                                                                <div class="card-body">
                                                                    <h5 class="card-title font-weight-bold mb-4">Purchase Summary</h5>
                                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                                        <span>Total Purchase:</span>
                                                                        <span class="font-weight-bold">
                                                                            <i class="fas fa-rupee-sign mr-1"></i>
                                                                            <span class="totrapurchase">
                                                                                <div class="spinner-border spinner-border-sm" role="status"></div>
                                                                            </span>
                                                                        </span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                                        <span>Vendor Payment:</span>
                                                                        <span class="font-weight-bold">
                                                                            <i class="fas fa-rupee-sign mr-1"></i>
                                                                            <span class="totrampaid">
                                                                                <div class="spinner-border spinner-border-sm" role="status"></div>
                                                                            </span>
                                                                        </span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <span>Vendor Dues:</span>
                                                                        <span class="font-weight-bold">
                                                                            <i class="fas fa-rupee-sign mr-1"></i>
                                                                            <span class="totramdues">
                                                                                <div class="spinner-border spinner-border-sm" role="status"></div>
                                                                            </span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Sales Summary -->
                                                        <div class="col-md-4 mb-3">
                                                            <div class="card h-200 border-0 shadow-sm bg-success text-white">
                                                                <div class="card-body">
                                                                    <h5 class="card-title font-weight-bold mb-4">Sales Summary</h5>
                                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                                        <span>Total Sale:</span>
                                                                        <span class="font-weight-bold">
                                                                            <i class="fas fa-rupee-sign mr-1"></i>
                                                                            <span class="totrasale">
                                                                                <div class="spinner-border spinner-border-sm" role="status"></div>
                                                                            </span>
                                                                        </span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                                        <span>Received Payments:</span>
                                                                        <span class="font-weight-bold">
                                                                            <i class="fas fa-rupee-sign mr-1"></i>
                                                                            <span class="totracpaid">
                                                                                <div class="spinner-border spinner-border-sm" role="status"></div>
                                                                            </span>
                                                                        </span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <span>Dues:</span>
                                                                        <span class="font-weight-bold">
                                                                            <i class="fas fa-rupee-sign mr-1"></i>
                                                                            <span class="totrasdues">
                                                                                <div class="spinner-border spinner-border-sm" role="status"></div>
                                                                            </span>
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Expenses Summary -->
                                                        <div class="col-md-4 mb-3">
                                                            <div class="card h-200 border-0 shadow-sm bg-info text-white">
                                                                <div class="card-body">
                                                                    <h5 class="card-title font-weight-bold mb-4">Expenses Summary</h5>
                                                                    <div class="d-flex justify-content-between align-items-center">
                                                                        <span>Total Expenses:</span>
                                                                        <span class="font-weight-bold">
                                                                            <i class="fas fa-rupee-sign mr-1"></i>
                                                                            <span class="totraexpenses">
                                                                                <div class="spinner-border spinner-border-sm" role="status"></div>
                                                                            </span>
                                                                        </span>
                                                                    </div>
                                                                    <!-- Optional: Add more expense metrics here if needed -->
                                                                    <div class="mt-4 pt-2" style="border-top: 1px solid rgba(255,255,255,0.2);">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
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
                                            <h5 class="card-title font-weight-bold mb-4 text-dark">
                                              <i class="fa fa-cubes"></i> GLD Summary <a href="gld_summary.php" target="_blank" class="btn btn-sm btn-primary" style="font-size: 12px;">Click here to view GLD Detailed Summary</a>
                                            </h5>
                                            <div class="row mb-3">
                                              <div class="col-md-4">
                                                <div class="alert alert-info mb-0"><b>Total Purchase:</b> <span class="gld-total-purchase">--</span></div>
                                              </div>
                                              <div class="col-md-4">
                                                <div class="alert alert-primary mb-0"><b>Total Sale:</b> <span class="gld-total-sale">--</span></div>
                                              </div>
                                              <div class="col-md-4">
                                                <div class="alert alert-primary mb-0"><b>Total Expenses:</b> <span class="gld-total-expenses">--</span></div>
                                              </div>
                                                <div class="col-md-4">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-hover mb-0" id="gld-summary-table2">
                                                            <thead class="thead-dark">
                                                            <tr>
                                                                <th>Branch/Location</th>
                                                                <th>Amount (₹)</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <!-- Data will be injected here -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-hover mb-0" id="gld-summary-table">
                                                            <thead class="thead-dark">
                                                            <tr>
                                                                <th>Branch/Location</th>
                                                                <th>Amount (₹)</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <!-- Data will be injected here -->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-hover mb-0" id="gld-summary-table3">
                                                            <thead class="thead-dark">
                                                            <tr>
                                                                <th>Branch/Location</th>
                                                                <th>Amount (₹)</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <!-- Data will be injected here -->
                                                            </tbody>
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

                                    <style>
                                        .card {
                                            transition: all 0.3s ease;
                                            border-radius: 10px;
                                        }
                                        .card:hover {
                                            transform: translateY(-3px);
                                            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
                                        }
                                        .stc-dash-month {
                                            transition: all 0.3s ease;
                                        }
                                        .stc-dash-month:focus {
                                            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25) !important;
                                        }
                                            .h-200 {
                                                height: 200px !important;
                                            }
                                        .bg-primary { background-color: #4e73df !important; }
                                        .bg-success { background-color: #1cc88a !important; }
                                        .bg-info { background-color: #36b9cc !important; }
                                    </style>
                                    ';
                                    ?>

                                    <div class="row mt-4">
                                        <div class="col-md-12 col-xl-6">
                                            <div class="card mb-3 border-success shadow-sm">
                                                <div class="card-body p-4">
                                                    <h5 class="card-title font-weight-bold mb-4 text-dark text-center">GLD Profit Summary</h5>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-hover">
                                                            <thead class="thead-dark">
                                                                <tr>
                                                                    <th class="text-center">Material Purchased Cost with Tax</th>
                                                                    <th class="text-center">Sale With Tax</th>
                                                                    <th class="text-center">Total Profit Margin (₹)</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="stc-gld-profit-analyser-sheet-breakup">
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            ?>
                        <!-- <div class="row">
                            <div class="col-md-12 col-xl-12 col-lg-12 col-sm-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="tm-block-title">Purchased Bar</h3>
                                    </div>
                                    <div class="panel-body">
                                        
                                    <div id="purchased-container">FusionCharts will render here</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-xl-12 col-lg-12 col-sm-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="tm-block-title">Sold Bar</h3>
                                    </div>
                                    <div class="panel-body">
                                        
                                    <div id="soled-container">FusionCharts will render here</div>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        

                        <div class="row mt-4">
                            <div class="col-md-12 col-xl-12">
                                <div class="card mb-3 border-success shadow-sm">
                                    <div class="card-body p-4">
                                        <h5 class="card-title font-weight-bold mb-4 text-dark text-center">Pending List</h5>

                                        <!-- Search bar -->
                                        <div class="row mb-3 align-items-end" id="stc-pending-search-bar">
                                            <div class="col-md-3 mb-2">
                                                <span style="font-size:11px;font-weight:600;color:#555;margin-bottom:3px;">Site Name</span>
                                                <input type="text" id="stc-search-site" class="form-control form-control-sm" placeholder="Search site name...">
                                            </div>
                                            <div class="col-md-3 mb-2">
                                                <span style="font-size:11px;font-weight:600;color:#555;margin-bottom:3px;">Item Description</span>
                                                <input type="text" id="stc-search-item" class="form-control form-control-sm" placeholder="Search item description...">
                                            </div>
                                            <div class="col-md-3 mb-2">
                                                <span style="font-size:11px;font-weight:600;color:#555;margin-bottom:3px;">Pending Reason</span>
                                                <input type="text" id="stc-search-reason" class="form-control form-control-sm" placeholder="Search pending reason...">
                                            </div>
                                            <div class="col-md-3 mb-2 d-flex" style="gap:6px;">
                                                <button id="stc-pending-search-btn" class="btn btn-primary btn-sm" style="min-width:72px;">
                                                    <i class="fa fa-search"></i> Search
                                                </button>
                                                <button id="stc-pending-reset-btn" class="btn btn-outline-secondary btn-sm" style="min-width:64px;">
                                                    Reset
                                                </button>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="mb-0 table table-bordered" id="stc-reports-requisition-pending-view">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" style="width:40px;">
                                                        <input type="checkbox" id="stc-pending-select-all" title="Select all" style="cursor:pointer;width:15px;height:15px;">
                                                    </th>
                                                    <th class="text-center stc-sortable" data-col="req_date" width="10%" style="cursor:pointer;white-space:nowrap;">
                                                        PR Date&nbsp;<span class="stc-sort-icon" style="font-size:11px;color:#aaa;">&#8645;</span><br>
                                                        <span style="font-size:10px;font-weight:normal;color:#777;">&amp; No</span>
                                                    </th>
                                                    <th class="text-center stc-sortable" data-col="site_name" style="width:300px;text-align:center;height:118px;cursor:pointer;">
                                                        Site Name&nbsp;<span class="stc-sort-icon" style="font-size:11px;color:#aaa;">&#8645;</span>
                                                    </th>
                                                    <th class="text-center stc-sortable" data-col="item_desc" style="width:500px;text-align:center;height:118px;word-wrap:break-word;white-space:normal;cursor:pointer;">
                                                        Item Desc&nbsp;<span class="stc-sort-icon" style="font-size:11px;color:#aaa;">&#8645;</span>
                                                    </th>
                                                    <th class="text-center" style="width:40px;text-align:center;height:118px;word-wrap:break-word;white-space:normal;">Unit</th>
                                                    <th class="text-center" style="width:80px;text-align:center;height:118px;"><div style="transform:rotate(-90deg);white-space:nowrap;width:20px;">Proc Apprv Qty</div></th>
                                                    <th class="text-center" style="width:80px;text-align:center;height:118px;"><div style="transform:rotate(-90deg);white-space:nowrap;width:20px;">Dispatch Qty</div></th>
                                                    <th class="text-center" style="width:80px;text-align:center;height:118px;"><div style="transform:rotate(-90deg);white-space:nowrap;width:20px;">Pending Qty</div></th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center stc-sortable" data-col="duration" style="width:200px;text-align:center;cursor:pointer;">
                                                        Pending Duration&nbsp;<span class="stc-sort-icon" style="font-size:11px;color:#aaa;">&#8645;</span>
                                                    </th>
                                                    <th class="text-center" style="width:500px;">Pending Reason</th>
                                                </tr>
                                                </thead>
                                                <tbody class="stc-reports-pending-view">
                                                <!-- pending list rows injected via AJAX by stcLoadPendingList() -->
                                                <tr id="stc-pending-loader">
                                                    <td colspan="11" class="text-center" style="padding:25px; color:#888;">
                                                        <i>Loading pending list&hellip;</i>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div id="stc-pending-pagination"></div>

                                        <!-- Bulk Action Bar (shown when rows are checked) -->
                                        <div id="stc-bulk-action-bar" style="display:none;position:sticky;bottom:0;background:#fff;border-top:2px solid #dee2e6;padding:10px 14px;z-index:100;align-items:center;gap:10px;flex-wrap:wrap;box-shadow:0 -2px 8px rgba(0,0,0,0.08);">
                                            <span id="stc-bulk-selected-count" style="font-size:13px;font-weight:600;color:#555;">0 item(s) selected</span>
                                            <div style="position:relative;display:inline-block;margin-left:8px;">
                                                <button type="button" id="stc-fix-btn" style="background:#dc3545;color:#fff;border:none;border-radius:4px;padding:5px 12px;font-size:13px;cursor:pointer;display:inline-flex;align-items:center;gap:5px;">
                                                    <i class="fa fa-pencil-square-o"></i> Status <i class="fa fa-caret-down" style="margin-left:2px;"></i>
                                                </button>
                                            </div>
                                            <button type="button" id="stc-bulk-pending-btn" style="background:#f0ad4e;color:#fff;border:none;border-radius:4px;padding:5px 12px;font-size:13px;cursor:pointer;display:inline-flex;align-items:center;gap:5px;margin-left:4px;">
                                                <i class="fa fa-clock-o"></i> Pending
                                            </button>
                                        </div>
                                        <!-- Custom Status Dropdown (moved to body via JS, opens upward) -->
                                        <div id="stc-status-dropdown-menu" style="display:none;position:fixed;top:auto;background:#fff;border:1px solid #dee2e6;border-radius:4px;box-shadow:0 4px 12px rgba(0,0,0,0.15);z-index:99999;min-width:140px;overflow:hidden;">
                                            <a href="#" class="stc-bulk-fix-action" data-status="6" data-label="Reject" style="display:block;padding:9px 16px;font-size:13px;color:#333;text-decoration:none;border-bottom:1px solid #f0f0f0;transition:background .15s;">
                                                <i class="fa fa-times-circle" style="color:#dc3545;margin-right:7px;"></i> Reject
                                            </a>
                                            <a href="#" class="stc-bulk-fix-action" data-status="10" data-label="Close" style="display:block;padding:9px 16px;font-size:13px;color:#333;text-decoration:none;transition:background .15s;">
                                                <i class="fa fa-lock" style="color:#6c757d;margin-right:7px;"></i> Close
                                            </a>
                                        </div>
                                        <style>
                                            #stc-status-dropdown-menu a:hover { background:#f8f9fa; color:#000; }
                                        </style>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12 col-xl-12">
                                <div class="card mb-3 border-purple shadow-sm" style="border-color:#9b59b6 !important;">
                                    <div class="card-body p-4">
                                        <h5 class="card-title font-weight-bold mb-4 text-dark text-center">Return List</h5>

                                        <div class="row mb-3 align-items-end" id="stc-return-search-bar">
                                            <div class="col-md-3 mb-2">
                                                <span style="font-size:11px;font-weight:600;color:#555;margin-bottom:3px;">Site Name</span>
                                                <input type="text" id="stc-return-search-site" class="form-control form-control-sm" placeholder="Search site name...">
                                            </div>
                                            <div class="col-md-3 mb-2">
                                                <span style="font-size:11px;font-weight:600;color:#555;margin-bottom:3px;">Item Description</span>
                                                <input type="text" id="stc-return-search-item" class="form-control form-control-sm" placeholder="Search item description...">
                                            </div>
                                            <div class="col-md-3 mb-2">
                                                <span style="font-size:11px;font-weight:600;color:#555;margin-bottom:3px;">Return Reason</span>
                                                <input type="text" id="stc-return-search-reason" class="form-control form-control-sm" placeholder="Search return reason...">
                                            </div>
                                            <div class="col-md-3 mb-2 d-flex" style="gap:6px;">
                                                <button id="stc-return-search-btn" class="btn btn-primary btn-sm" style="min-width:72px;">
                                                    <i class="fa fa-search"></i> Search
                                                </button>
                                                <button id="stc-return-reset-btn" class="btn btn-outline-secondary btn-sm" style="min-width:64px;">
                                                    Reset
                                                </button>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="mb-0 table table-bordered" id="stc-reports-requisition-return-view">
                                                <thead>
                                                <tr>
                                                    <th class="text-center" style="width:40px;">
                                                        <input type="checkbox" id="stc-return-select-all" title="Select all" style="cursor:pointer;width:15px;height:15px;">
                                                    </th>
                                                    <th class="text-center stc-return-sortable" data-col="req_date" width="10%" style="cursor:pointer;white-space:nowrap;">
                                                        PR Date&nbsp;<span class="stc-return-sort-icon" style="font-size:11px;color:#aaa;">&#8645;</span><br>
                                                        <span style="font-size:10px;font-weight:normal;color:#777;">&amp; No</span>
                                                    </th>
                                                    <th class="text-center stc-return-sortable" data-col="site_name" style="width:300px;text-align:center;cursor:pointer;">
                                                        Site Name&nbsp;<span class="stc-return-sort-icon" style="font-size:11px;color:#aaa;">&#8645;</span>
                                                    </th>
                                                    <th class="text-center stc-return-sortable" data-col="item_desc" style="width:500px;text-align:center;word-wrap:break-word;white-space:normal;cursor:pointer;">
                                                        Item Desc&nbsp;<span class="stc-return-sort-icon" style="font-size:11px;color:#aaa;">&#8645;</span>
                                                    </th>
                                                    <th class="text-center" style="width:40px;">Unit</th>
                                                    <th class="text-center" style="width:80px;"><div style="transform:rotate(-90deg);white-space:nowrap;width:20px;">Proc Apprv Qty</div></th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center stc-return-sortable" data-col="duration" style="width:200px;text-align:center;cursor:pointer;">
                                                        Return Duration&nbsp;<span class="stc-return-sort-icon" style="font-size:11px;color:#aaa;">&#8645;</span>
                                                    </th>
                                                    <th class="text-center" style="width:500px;">Return Reason</th>
                                                    <th class="text-center" style="width:110px;">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody class="stc-reports-return-view">
                                                <tr id="stc-return-loader">
                                                    <td colspan="10" class="text-center" style="padding:25px; color:#888;">
                                                        <i>Loading return list&hellip;</i>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div id="stc-return-pagination"></div>

                                        <!-- Bulk Action Bar (shown when return rows are checked) -->
                                        <div id="stc-return-bulk-action-bar" style="display:none;position:sticky;bottom:0;background:#fff;border-top:2px solid #dee2e6;padding:10px 14px;z-index:100;align-items:center;gap:10px;flex-wrap:wrap;box-shadow:0 -2px 8px rgba(0,0,0,0.08);">
                                            <span id="stc-return-bulk-selected-count" style="font-size:13px;font-weight:600;color:#555;">0 item(s) selected</span>
                                            <button type="button" id="stc-return-bulk-accept-btn" style="background:#27ae60;color:#fff;border:none;border-radius:4px;padding:5px 12px;font-size:13px;cursor:pointer;display:inline-flex;align-items:center;gap:5px;margin-left:8px;">
                                                <i class="fa fa-check"></i> Accept Selected
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>

    <!-- Pending Note Modal -->
    <div class="modal fade" id="stcPendingNoteModal" tabindex="-1" role="dialog" aria-labelledby="stcPendingNoteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background:#f8f9fa;border-bottom:1px solid #dee2e6;">
                    <h5 class="modal-title" id="stcPendingNoteModalLabel" style="font-size:15px;font-weight:700;">
                        <i class="fa fa-clock-o" style="color:#f0ad4e;"></i> Add Pending Note
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding:16px 20px;">
                    <p id="stc-pending-note-info" style="font-size:12px;color:#888;margin-bottom:12px;padding:6px 10px;background:#fff8e1;border-left:3px solid #f0ad4e;border-radius:3px;"></p>
                    <div style="margin-bottom:4px;">
                        <span style="font-size:13px;font-weight:600;color:#333;display:block;margin-bottom:6px;">Pending Message <span style="color:#e74c3c;">*</span></span>
                        <textarea id="stc-pending-note-msg" class="form-control" rows="4" placeholder="Enter reason or note for pending status..." style="font-size:13px;resize:vertical;width:100%;box-sizing:border-box;"></textarea>
                        <span id="stc-pending-note-err" style="display:none;color:#e74c3c;font-size:12px;margin-top:4px;">Please enter a message.</span>
                    </div>
                </div>
                <div class="modal-footer" style="background:#f8f9fa;">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning btn-sm" id="stc-pending-note-submit">
                        <i class="fa fa-save"></i> Update Pending
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Accept Return Overlay (custom full-screen; not Bootstrap modal) -->
    <div id="stcAcceptReturnOverlay" role="dialog" aria-modal="true" aria-labelledby="stcAcceptReturnOverlayLabel">
        <div class="stc-overlay-dialog">
            <div class="stc-overlay-header">
                <h5 id="stcAcceptReturnOverlayLabel">
                    <i class="fa fa-check-circle" style="color:#27ae60;"></i> Accept Return
                </h5>
                <button type="button" class="stc-overlay-close stc-accept-return-cancel" aria-label="Close">&times;</button>
            </div>
            <div class="stc-overlay-body">
                <input type="hidden" id="stc-accept-return-item-id" value="">
                <input type="hidden" id="stc-accept-return-mode" value="single">
                <p id="stc-accept-return-info" style="font-size:12px;color:#888;margin-bottom:12px;padding:6px 10px;background:#f3e5f5;border-left:3px solid #9b59b6;border-radius:3px;"></p>
                <div style="margin-bottom:4px;">
                    <span style="font-size:13px;font-weight:600;color:#333;display:block;margin-bottom:6px;">Description / Note <span style="color:#e74c3c;">*</span></span>
                    <textarea id="stc-accept-return-msg" class="form-control" rows="4" placeholder="Enter acceptance note / description..." style="font-size:13px;resize:vertical;width:100%;box-sizing:border-box;"></textarea>
                    <span id="stc-accept-return-err" style="display:none;color:#e74c3c;font-size:12px;margin-top:4px;">Please enter a description.</span>
                </div>
            </div>
            <div class="stc-overlay-footer">
                <button type="button" class="btn btn-secondary btn-sm stc-accept-return-cancel">Cancel</button>
                <button type="button" class="btn btn-success btn-sm" id="stc-accept-return-submit">
                    <i class="fa fa-check"></i> Accept Return
                </button>
            </div>
        </div>
    </div>

    <!-- Return Item Adhoc Details Overlay -->
    <div id="stcReturnAdhocOverlay" role="dialog" aria-modal="true" aria-labelledby="stcReturnAdhocOverlayLabel">
        <div class="stc-overlay-dialog">
            <div class="stc-overlay-header">
                <h5 id="stcReturnAdhocOverlayLabel">
                    <i class="fa fa-eye" style="color:#3f6ad8;"></i> Adhoc Product Details
                </h5>
                <button type="button" class="stc-overlay-close stc-return-adhoc-close" aria-label="Close">&times;</button>
            </div>
            <div class="stc-overlay-body">
                <p id="stc-return-adhoc-info" style="font-size:12px;color:#555;margin-bottom:12px;padding:6px 10px;background:#eef2ff;border-left:3px solid #3f6ad8;border-radius:3px;"></p>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0" id="stc-return-adhoc-table">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Adhoc ID</th>
                                <th>Item Desc</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Stock</th>
                                <th class="text-center">Unit</th>
                                <th class="text-right">P.Rate</th>
                                <th>Source</th>
                                <th>Destination</th>
                                <th>Rack</th>
                                <th class="text-center">Status</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody id="stc-return-adhoc-tbody">
                            <tr><td colspan="12" class="text-center text-muted">Loading…</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="stc-overlay-footer">
                <button type="button" class="btn btn-secondary btn-sm stc-return-adhoc-close">Close</button>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="assets/vendor/bootstrap/js/popper.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.js"></script>
    <script type="text/javascript" src="./assets/scripts/fusioncharts.js"></script>
    <script type="text/javascript" src="./assets/scripts/fusioncharts.charts.js"></script>
    <script type="text/javascript" src="./assets/scripts/themes/fusioncharts.theme.zune.js"></script>
    <script type="text/javascript" src="./assets/scripts/loginopr.js"></script>
    <script type="text/javascript" src="./assets/scripts/sidebar-persist.js"></script>
    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    <script type="text/javascript" src="./assets/scripts/jarvis.js"></script>
    <script src="./assets/scripts/jquery.table2excel.js"></script>
    <script>
        $(document).ready(function(){
            var d = new Date($.now());
            var month=$('.stc-dash-month').val();
            var type=$('.stc-dash-type').val();
            // Keep pending note modal on body; accept-return uses custom overlay
            $('#stcPendingNoteModal, #stcAcceptReturnOverlay, #stcReturnAdhocOverlay').appendTo('body');
            $('body').delegate('.stc-dash-month', 'change', function() {
                month=$(this).val();
                type=$('.stc-dash-type').val();
                stc_dashboard_reload(month, 'post');
            });
            $('body').delegate('.stc-dash-type', 'change', function() {
                type=$(this).val();
                month=$('.stc-dash-month').val();
                stc_dashboard_reload(month, 'post');
            });

            stc_dashboard_reload(month, 'preload');
            var gldDonutChartInstance = null;
            var gldBarChartInstance = null;
            function stc_dashboard_reload(month, preload){
                $.ajax({
                    url         : "kattegat/ragnar_lothbrok.php",
                    method      : "post",
                    data        : {dashboard:1, month:month, type:type, preload:preload},
                    dataType    : 'JSON',
                    success     : function(data){
                        // console.log(data);
                        $('.stc-dash-month').val(data[12]);
                        $('.toproducts').html(data[0]);
                        $('.toinventory').html(data[1]);
                        $('.tomerchants').html(data[2]);
                        $('.tocustomers').html(data[3]);
                        $('.topurchased').html(data[4]);
                        $('.tosoled').html(data[5]);
                        $('.tomerpaid').html(data[6]);
                        $('.tocustpaid').html(data[7]);
                        $('.toelecpurchase').html(data[8]['elecpurchase']);
                        $('.toelecsale').html(data[8]['elecsale']);
                        $('.toelecmpaid').html(data[8]['elecmpaid']);
                        $('.toeleccpaid').html(data[8]['eleccpaid']);
                        $('.toelecmdues').html(data[8]['elecpdues']);
                        $('.toelecsdues').html(data[8]['elecsdues']);
                        $('.toelecexpenses').html(data[8]['elecsexpense']);

                        $('.totrapurchase').html(data[9]['trapurchase']);
                        $('.totrasale').html(data[9]['trasale']);
                        $('.totrampaid').html(data[9]['trampaid']);
                        $('.totracpaid').html(data[9]['tracpaid']);
                        $('.totramdues').html(data[9]['trapdues']);
                        $('.totrasdues').html(data[9]['trasdues']);
                        $('.totraexpenses').html(data[9]['traexpense']);

                        $('.togrospurchase').html(data[10]['grospurchase']);
                        $('.togrossale').html(data[10]['grossale']);
                        $('.togrosmpaid').html(data[10]['grosmpaid']);
                        $('.togroscpaid').html(data[10]['groscpaid']);
                        $('.togrosmdues').html(data[10]['grospdues']);
                        $('.togrossdues').html(data[10]['grossdues']);
                        $('.togrosexpenses').html(data[10]['grosexpense']);
                        // GLD summary
                        var gld = data[11] || {};
                        $('.gld-total-purchase').text(gld.total_purchase !== undefined ? parseFloat(gld.total_purchase).toLocaleString('en-IN', {minimumFractionDigits:2}) : '--');
                        $('.gld-total-sale').text(gld.total_sale !== undefined ? parseFloat(gld.total_sale).toLocaleString('en-IN', {minimumFractionDigits:2}) : '--');
                        var gldRows = '';var gldRows2 = '';
                        var donutLabels = [];
                        var donutData = [];
                        if(Array.isArray(gld.sub_locations_sale) && gld.sub_locations_sale.length > 0) {
                            var total=0;
                          $.each(gld.sub_locations_sale, function(i, item) {
                                total += parseFloat(item.sale_amount);
                                gldRows += '<tr>' +
                                '<td>' + item.sale_location + '</td>' +
                                '<td class="text-right"><span class="badge badge-pill badge-info js-gld-sale-breakdown" style="font-size:14px;cursor:pointer;" data-location="'+ String(item.sale_location).replace(/"/g,'&quot;') +'" data-month="'+ String(month).replace(/"/g,'&quot;') +'" data-type="'+ String(type).replace(/"/g,'&quot;') +'">₹ ' + parseFloat(item.sale_amount).toLocaleString('en-IN', {minimumFractionDigits:2}) + '</span></td>' +
                                '</tr>';
                                donutLabels.push(item.sale_location);
                                donutData.push(item.sale_amount);
                          });
                          gldRows += '<tr><td><b>Total:</b></td><td class="text-right"><span class="badge badge-pill badge-success" style="font-size:14px;">₹ ' + parseFloat(total).toLocaleString('en-IN', {minimumFractionDigits:2}) + '</span></td></tr>';
                        } else {
                          gldRows = '<tr><td colspan="2" class="text-center text-muted">No data found for this period.</td></tr>';
                        }
                        $('#gld-summary-table tbody').html(gldRows);
                        if(Array.isArray(gld.sub_locations_purchase) && gld.sub_locations_purchase.length > 0) {
                            var total=0;
                          $.each(gld.sub_locations_purchase, function(i, item) {
                                total += parseFloat(item.purchase_amount);
                                gldRows2 += '<tr>' +
                                '<td>' + item.purchase_location + '</td>' +
                                '<td class="text-right"><span class="badge badge-pill badge-info js-gld-purchase-breakdown" style="font-size:14px;cursor:pointer;" data-location="'+ String(item.purchase_location).replace(/"/g,'&quot;') +'" data-month="'+ String(month).replace(/"/g,'&quot;') +'" data-type="'+ String(type).replace(/"/g,'&quot;') +'">₹ ' + parseFloat(item.purchase_amount).toLocaleString('en-IN', {minimumFractionDigits:2}) + '</span></td>' +
                                '</tr>';
                          });
                          gldRows2 += '<tr><td><b>Total:</b></td><td class="text-right"><span class="badge badge-pill badge-success" style="font-size:14px;">₹ ' + parseFloat(total).toLocaleString('en-IN', {minimumFractionDigits:2}) + '</span></td></tr>';
                        } else {
                          gldRows2 = '<tr><td colspan="2" class="text-center text-muted">No data found for this period.</td></tr>';
                        }
                        $('#gld-summary-table2 tbody').html(gldRows2);
                        $('.stc-gld-profit-analyser-sheet-breakup').html(data[13]);
                        // Render Donut Chart
                        var ctxDonut = document.getElementById('gldDonutChart').getContext('2d');
                        if(gldDonutChartInstance) { gldDonutChartInstance.destroy(); }
                        if(donutLabels.length > 0) {
                          gldDonutChartInstance = new Chart(ctxDonut, {
                            type: 'doughnut',
                            data: {
                              labels: donutLabels,
                              datasets: [{
                                data: donutData,
                                backgroundColor: [
                                  '#42a5f5', '#66bb6a', '#ffa726', '#ab47bc', '#ec407a', '#ff7043', '#26a69a', '#d4e157', '#8d6e63', '#789262'
                                ],
                                borderWidth: 2
                              }]
                            },
                            options: {
                              responsive: true,
                              plugins: {
                                legend: { position: 'bottom' },
                                title: { display: true, text: 'GLD Branch-wise Distribution' }
                              }
                            }
                          });
                        } else {
                          ctxDonut.clearRect(0, 0, 400, 220);
                        }
                        // Render Bar Chart
                        var ctxBar = document.getElementById('gldBarChart').getContext('2d');
                        if(gldBarChartInstance) { gldBarChartInstance.destroy(); }
                        if(donutLabels.length > 0) {
                          gldBarChartInstance = new Chart(ctxBar, {
                            type: 'bar',
                            data: {
                              labels: donutLabels,
                              datasets: [{
                                label: 'Amount (₹)',
                                data: donutData,
                                backgroundColor: [
                                  '#42a5f5', '#66bb6a', '#ffa726', '#ab47bc', '#ec407a', '#ff7043', '#26a69a', '#d4e157', '#8d6e63', '#789262'
                                ],
                                borderWidth: 2
                              }]
                            },
                            options: {
                              responsive: true,
                              plugins: {
                                legend: { display: false },
                                title: { display: true, text: 'GLD Branch-wise Bar Chart' }
                              },
                              scales: {
                                y: {
                                  beginAtZero: true,
                                  ticks: {
                                    callback: function(value) { return '₹ ' + value.toLocaleString('en-IN'); }
                                  }
                                }
                              }
                            }
                          });
                        } else {
                          ctxBar.clearRect(0, 0, 400, 220);
                        }
                    }
                });
            }


            // stc_purchasedCharts();
            function stc_purchasedCharts() {
                $.ajax({
                    url         : "kattegat/ragnar_lothbrok.php",
                    type        : 'POST',
                    data        : {dashboardpurchasedcharts:1},
                    // dataType : 'JSON',
                    success     : function(data) {
                        // console.log(data);
                        chartData = data;
                        var chartProperties = {
                            "caption": "Yearly Purchased Bar "+d.getFullYear(),
                            "xAxisName": "Months",
                            "yAxisName": "Purchased Amounts",
                            "rotatevalues": "1",
                            "theme": "zune"
                        };

                        apiChart = new FusionCharts({
                            type: 'column2d',
                            renderAt: 'purchased-container',
                            width: '650',
                            height: '350',
                            dataFormat: 'json',
                            dataSource: {
                                "chart": chartProperties,
                                "data": chartData
                            }
                        });
                        apiChart.render();
                    }
                });
            };

            // for sold
            // stc_solddCharts();
            function stc_solddCharts() {
                $.ajax({
                    url         : "kattegat/ragnar_lothbrok.php",
                    type        : 'POST',
                    data        : {dashboardsoledcharts:1},
                    // dataType : 'JSON',
                    success     : function(data) {
                        console.log(data);
                        chartData = data;
                        var chartProperties = {
                            "caption": "Yearly Soled Bar "+d.getFullYear(),
                            "xAxisName": "Months",
                            "yAxisName": "Soled Amounts",
                            "rotatevalues": "1",
                            "theme": "zune"
                        };

                        apiChart = new FusionCharts({
                            type: 'column2d',
                            renderAt: 'soled-container',
                            width: '650',
                            height: '350',
                            dataFormat: 'json',
                            dataSource: {
                                "chart": chartProperties,
                                "data": chartData
                            }
                        });
                        apiChart.render();
                    }
                });
            };

            // Fetch inventory summary on load and on month change
            function fetchInventorySummary(month, year) {
              $('#inventory-summary-loader').show();
              $('#inventory-summary-table tbody').html('<tr><td colspan="4" class="text-center"><div class="spinner-border spinner-border-sm text-warning" role="status"></div> Loading...</td></tr>');
              $.ajax({
                url: 'kattegat/ragnar_lothbrok.php',
                method: 'POST',
                data: {dashboard_inventory_summary: 1, month: month, year: year},
                dataType: 'json',
                success: function(data) {
                  $('#inventory-summary-loader').hide();
                  var rows = '';
                  if(data.length === 0) {
                    rows = '<tr><td colspan="4" class="text-center text-muted">No data found for this period.</td></tr>';
                  } else {
                    $.each(data, function(i, item) {
                      rows += '<tr>' +
                        '<td>' + item.product_name + '</td>' +
                        '<td>' + item.unit + '</td>' +
                        '<td><span class="badge badge-pill badge-warning" style="font-size:14px;">₹ ' + parseFloat(item.purchased).toLocaleString('en-IN', {minimumFractionDigits:2}) + '</span></td>' +
                        '<td><span class="badge badge-pill badge-dark" style="font-size:14px;">₹ ' + parseFloat(item.sold).toLocaleString('en-IN', {minimumFractionDigits:2}) + '</span></td>' +
                      '</tr>';
                    });
                  }
                  $('#inventory-summary-table tbody').html(rows);
                },
                error: function() {
                  $('#inventory-summary-loader').hide();
                  $('#inventory-summary-table tbody').html('<tr><td colspan="4" class="text-center text-danger">Error loading data.</td></tr>');
                }
              });
            }
            // Initial load
            fetchInventorySummary(month, d.getFullYear());
            // On month change
            // $('body').on('change', '.stc-dash-month', function() {
            //   month = $(this).val();
            //   fetchInventorySummary(month, d.getFullYear());
            // });
        });
        
        // =====================================================================
        // PENDING LIST — AJAX loader with pagination, search, sort (no page reload)
        // =====================================================================
        var stcPendingCurrentPage = 1;
        var stcPendingSortCol     = 'req_date';
        var stcPendingSortDir     = 'DESC';

        function stcLoadPendingList(page){
            stcPendingCurrentPage = page;
            var $tbody = $('.stc-reports-pending-view');
            var $pag   = $('#stc-pending-pagination');

            $tbody.html('<tr><td colspan="11" class="text-center" style="padding:25px;color:#888;"><i>Loading&hellip;</i></td></tr>');
            $pag.html('');

            $.ajax({
                url     : 'kattegat/ragnar_lothbrok.php',
                method  : 'POST',
                data    : {
                    pending_list  : 1,
                    page          : page,
                    per_page      : 15,
                    search_site   : $('#stc-search-site').val().trim(),
                    search_item   : $('#stc-search-item').val().trim(),
                    search_reason : $('#stc-search-reason').val().trim(),
                    sort_col      : stcPendingSortCol,
                    sort_dir      : stcPendingSortDir
                },
                dataType: 'json',
                success : function(data){
                    if(!data || !data.success){
                        $tbody.html('<tr><td colspan="11" class="text-center text-danger">Failed to load pending list.</td></tr>');
                        return;
                    }
                    if(data.rows.length === 0){
                        $tbody.html('<tr><td colspan="11" class="text-center" style="padding:20px;">No requisition found!!!</td></tr>');
                        return;
                    }

                    var html = '';
                    $.each(data.rows, function(i, r){
                        // Pending qty cell
                        var pendingQtyHtml = '';
                        if(parseFloat(r.pending_qty) > 0){
                            pendingQtyHtml = '<p class="form-control" style="background:#ffd81a;color:red;">' +
                                parseFloat(r.pending_qty).toFixed(2) + '</p>';
                        } else {
                            pendingQtyHtml = parseFloat(r.pending_qty).toFixed(2);
                        }

                        // Status cell
                        var statusHtml = '<span style="background-color:rgb(255,47,47);color:white;padding:2px 6px;border-radius:3px;">Pending</span>';
                        if(r.status == 9){
                            statusHtml += ' <select class="form-control status-dropdown" data-item-id="' + r.req_list_id + '" data-old-status="9" style="font-size:12px;padding:2px 5px;min-width:100px;display:inline-block;width:auto;">' +
                                '<option value="9" selected>Select Status</option>' +
                                '<option value="6">Reject</option>' +
                                '<option value="10">Close</option>' +
                                '</select>';
                        }

                        // Duration cell — show badge whenever log date exists (days >= 0)
                        var durHtml = '<div style="padding:5px 10px;text-align:center;color:#aaa;">N/A</div>';
                        if(r.days_pending >= 0 && r.dur_bg !== undefined && r.dur_bg !== ''){
                            var mLabel = r.months_pending + ' Month' + (r.months_pending !== 1 ? 's' : '') + ' (' + r.days_pending + ' Day' + (r.days_pending !== 1 ? 's' : '') + ')';
                            durHtml = '<div style="background-color:' + r.dur_bg + ';color:' + r.dur_color + ';padding:5px 10px;border-radius:3px;text-align:center;font-weight:bold;">' + mLabel + '</div>';
                        }

                        // Pending reason cell
                        var uniqueId  = 'pending-reason-' + r.combiner_id + '-' + r.req_list_id;
                        var reasonHtml = '<div class="pending-reason-container" id="' + uniqueId + '">' +
                            '<span class="pending-reason-short"><a href="stc-requisition-combiner-fsale.php?requi_id=' + r.combiner_id + '" target="__blank" class="pending-reason-link">' + (r.reason_truncated || '') + '</a></span>' +
                            '<span class="pending-reason-full" style="display:none;">' + (r.reason_full || '') + '</span>' +
                            ' <a href="#" class="pending-read-more" style="color:#007bff;text-decoration:underline;cursor:pointer;">...read more</a>' +
                            '</div>';

                        html += '<tr data-item-id="' + r.req_list_id + '">' +
                            '<td class="text-center" style="vertical-align:middle;">' +
                                '<input type="checkbox" class="stc-pending-row-cb" data-item-id="' + r.req_list_id + '" style="cursor:pointer;width:15px;height:15px;">' +
                                '<div style="font-size:10px;color:#999;margin-top:2px;">' + r.slno + '</div>' +
                            '</td>' +
                            '<td>' + (r.req_date || '') + '<br>' + (r.req_id || '') + '</td>' +
                            '<td>' + (r.project_title || '') + '</td>' +
                            '<td>' + (r.item_title || '') + '</td>' +
                            '<td>' + (r.unit || '') + '</td>' +
                            '<td align="right">' + r.final_qty + '</td>' +
                            '<td align="right">' + r.dispatched_qty + '</td>' +
                            '<td align="right">' + pendingQtyHtml + '</td>' +
                            '<td>' + statusHtml + '</td>' +
                            '<td style="font-size:10px;">' + durHtml + '</td>' +
                            '<td>' + reasonHtml + '</td>' +
                            '</tr>';
                    });
                    $tbody.html(html);

                    // Build pagination controls
                    var pagHtml = '';
                    if(data.total_pages > 1){
                        var from = (data.page - 1) * data.per_page + 1;
                        var to   = Math.min(data.page * data.per_page, data.total);
                        pagHtml += '<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;padding:10px 4px;gap:8px;">';
                        pagHtml += '<div style="font-size:12px;color:#555;">Showing <strong>' + from + '</strong> &ndash; <strong>' + to + '</strong> of <strong>' + data.total + '</strong> items</div>';
                        pagHtml += '<nav><ul class="pagination pagination-sm mb-0" style="flex-wrap:wrap;">';

                        // Prev
                        if(data.page > 1){
                            pagHtml += '<li class="page-item"><a class="page-link stc-pending-page-btn" data-page="' + (data.page - 1) + '" href="#">&laquo; Prev</a></li>';
                        } else {
                            pagHtml += '<li class="page-item disabled"><span class="page-link">&laquo; Prev</span></li>';
                        }
                        // First page + ellipsis
                        var pgRange = 2;
                        var pgStart = Math.max(1, data.page - pgRange);
                        var pgEnd   = Math.min(data.total_pages, data.page + pgRange);
                        if(pgStart > 1){
                            pagHtml += '<li class="page-item"><a class="page-link stc-pending-page-btn" data-page="1" href="#">1</a></li>';
                            if(pgStart > 2) pagHtml += '<li class="page-item disabled"><span class="page-link">&hellip;</span></li>';
                        }
                        for(var pg = pgStart; pg <= pgEnd; pg++){
                            if(pg === data.page){
                                pagHtml += '<li class="page-item active"><span class="page-link">' + pg + '</span></li>';
                            } else {
                                pagHtml += '<li class="page-item"><a class="page-link stc-pending-page-btn" data-page="' + pg + '" href="#">' + pg + '</a></li>';
                            }
                        }
                        // Last page + ellipsis
                        if(pgEnd < data.total_pages){
                            if(pgEnd < data.total_pages - 1) pagHtml += '<li class="page-item disabled"><span class="page-link">&hellip;</span></li>';
                            pagHtml += '<li class="page-item"><a class="page-link stc-pending-page-btn" data-page="' + data.total_pages + '" href="#">' + data.total_pages + '</a></li>';
                        }
                        // Next
                        if(data.page < data.total_pages){
                            pagHtml += '<li class="page-item"><a class="page-link stc-pending-page-btn" data-page="' + (data.page + 1) + '" href="#">Next &raquo;</a></li>';
                        } else {
                            pagHtml += '<li class="page-item disabled"><span class="page-link">Next &raquo;</span></li>';
                        }
                        pagHtml += '</ul></nav></div>';
                    }
                    $pag.html(pagHtml);
                },
                error: function(){
                    $tbody.html('<tr><td colspan="11" class="text-center text-danger" style="padding:20px;">Error loading pending list. Please refresh the page.</td></tr>');
                }
            });
        }

        // Load page 1 on ready
        stcLoadPendingList(1);

        // Pagination button click (delegated — works after AJAX inject)
        $(document).on('click', '.stc-pending-page-btn', function(e){
            e.preventDefault();
            var pg = parseInt($(this).data('page'), 10);
            if(pg && pg !== stcPendingCurrentPage){
                stcLoadPendingList(pg);
                var $tbl = $('#stc-reports-requisition-pending-view');
                if($tbl.length){ $('html,body').animate({ scrollTop: $tbl.offset().top - 80 }, 300); }
            }
        });

        // Search button
        $('#stc-pending-search-btn').on('click', function(){
            stcLoadPendingList(1);
        });

        // Reset button — clear fields + reset sort
        $('#stc-pending-reset-btn').on('click', function(){
            $('#stc-search-site, #stc-search-item, #stc-search-reason').val('');
            stcPendingSortCol = 'req_date';
            stcPendingSortDir = 'DESC';
            $('.stc-sort-icon').html('&#8645;').css('color','#aaa');
            stcLoadPendingList(1);
        });

        // Enter key in any search field triggers search
        $('#stc-search-site, #stc-search-item, #stc-search-reason').on('keypress', function(e){
            if(e.which === 13){ stcLoadPendingList(1); }
        });

        // Sortable column header click
        $(document).on('click', '.stc-sortable', function(){
            var col = $(this).data('col');
            if(stcPendingSortCol === col){
                stcPendingSortDir = stcPendingSortDir === 'ASC' ? 'DESC' : 'ASC';
            } else {
                stcPendingSortCol = col;
                stcPendingSortDir = 'ASC';
            }
            // Reset all icons then set active
            $('.stc-sort-icon').html('&#8645;').css('color','#aaa');
            var icon = stcPendingSortDir === 'ASC' ? '&#8593;' : '&#8595;';
            $(this).find('.stc-sort-icon').html(icon).css('color','#333');
            stcLoadPendingList(1);
        });
        // =====================================================================

        // =====================================================================
        // RETURN LIST — AJAX loader with pagination, search, sort
        // =====================================================================
        var stcReturnCurrentPage = 1;
        var stcReturnSortCol     = 'req_date';
        var stcReturnSortDir     = 'DESC';
        var stcReturnBulkIds     = [];

        function stcUpdateReturnBulkBar(){
            var checked = $('.stc-return-row-cb:checked').length;
            if(checked > 0){
                $('#stc-return-bulk-selected-count').text(checked + ' item(s) selected');
                $('#stc-return-bulk-action-bar').css('display','flex');
            } else {
                $('#stc-return-bulk-action-bar').css('display','none');
                $('#stc-return-select-all').prop('checked', false).prop('indeterminate', false);
            }
            var total = $('.stc-return-row-cb').length;
            if(total > 0){
                if(checked === 0){
                    $('#stc-return-select-all').prop('checked', false).prop('indeterminate', false);
                } else if(checked === total){
                    $('#stc-return-select-all').prop('checked', true).prop('indeterminate', false);
                } else {
                    $('#stc-return-select-all').prop('checked', false).prop('indeterminate', true);
                }
            }
        }

        function stcLoadReturnList(page){
            stcReturnCurrentPage = page;
            var $tbody = $('.stc-reports-return-view');
            var $pag   = $('#stc-return-pagination');

            $tbody.html('<tr><td colspan="10" class="text-center" style="padding:25px;color:#888;"><i>Loading&hellip;</i></td></tr>');
            $pag.html('');
            $('#stc-return-bulk-action-bar').css('display','none');
            $('#stc-return-select-all').prop('checked', false).prop('indeterminate', false);

            $.ajax({
                url     : 'kattegat/ragnar_lothbrok.php',
                method  : 'POST',
                data    : {
                    return_list   : 1,
                    page          : page,
                    per_page      : 15,
                    search_site   : $('#stc-return-search-site').val().trim(),
                    search_item   : $('#stc-return-search-item').val().trim(),
                    search_reason : $('#stc-return-search-reason').val().trim(),
                    sort_col      : stcReturnSortCol,
                    sort_dir      : stcReturnSortDir
                },
                dataType: 'json',
                success : function(data){
                    if(!data || !data.success){
                        $tbody.html('<tr><td colspan="10" class="text-center text-danger">Failed to load return list.</td></tr>');
                        return;
                    }
                    if(!data.rows || data.rows.length === 0){
                        $tbody.html('<tr><td colspan="10" class="text-center" style="padding:20px;">No returned requisition found!!!</td></tr>');
                        return;
                    }

                    var html = '';
                    $.each(data.rows, function(i, r){
                        var statusHtml = '<span style="background-color:#9b59b6;color:white;padding:2px 6px;border-radius:3px;">Returned</span>';

                        var durHtml = '<div style="padding:5px 10px;text-align:center;color:#aaa;">N/A</div>';
                        if(r.days_returned >= 0 && r.dur_bg !== undefined && r.dur_bg !== ''){
                            var mLabel = r.months_returned + ' Month' + (r.months_returned !== 1 ? 's' : '') + ' (' + r.days_returned + ' Day' + (r.days_returned !== 1 ? 's' : '') + ')';
                            durHtml = '<div style="background-color:' + r.dur_bg + ';color:' + r.dur_color + ';padding:5px 10px;border-radius:3px;text-align:center;font-weight:bold;">' + mLabel + '</div>';
                        }

                        var uniqueId = 'return-reason-' + r.req_list_id;
                        var reasonLink = r.combiner_id
                            ? '<a href="stc-requisition-combiner-fsale.php?requi_id=' + r.combiner_id + '" target="__blank" class="return-reason-link">' + (r.reason_truncated || '') + '</a>'
                            : (r.reason_truncated || '');
                        var reasonHtml = '<div class="pending-reason-container" id="' + uniqueId + '">' +
                            '<span class="pending-reason-short">' + reasonLink + '</span>' +
                            '<span class="pending-reason-full" style="display:none;">' + (r.reason_full || '') + '</span>' +
                            (r.reason_full ? ' <a href="#" class="pending-read-more" style="color:#007bff;text-decoration:underline;cursor:pointer;">...read more</a>' : '') +
                            '</div>';

                        var actionHtml = '<button type="button" class="btn btn-success btn-sm stc-accept-return-btn" ' +
                            'data-item-id="' + r.req_list_id + '" ' +
                            'data-item-title="' + String(r.item_title || '').replace(/"/g, '&quot;') + '" ' +
                            'data-site="' + String(r.project_title || '').replace(/"/g, '&quot;') + '" ' +
                            'data-req="' + (r.req_id || '') + '" ' +
                            'style="font-size:12px;padding:3px 10px;" title="Accept this return">' +
                            '<i class="fa fa-check"></i> Accept</button>';

                        var eyeHtml = '';
                        if(parseInt(r.product_id, 10) > 0){
                            eyeHtml = ' <a href="#" class="stc-return-adhoc-eye" title="View adhoc details" data-product-id="' + r.product_id + '" data-item-title="' + String(r.item_title || '').replace(/"/g, '&quot;') + '"><i class="fa fa-eye"></i></a>';
                        }

                        html += '<tr data-item-id="' + r.req_list_id + '">' +
                            '<td class="text-center" style="vertical-align:middle;">' +
                                '<input type="checkbox" class="stc-return-row-cb" data-item-id="' + r.req_list_id + '" style="cursor:pointer;width:15px;height:15px;">' +
                                '<div style="font-size:10px;color:#999;margin-top:2px;">' + r.slno + '</div>' +
                            '</td>' +
                            '<td>' + (r.req_date || '') + '<br>' + (r.req_id || '') + '</td>' +
                            '<td>' + (r.project_title || '') + '</td>' +
                            '<td>' + (r.item_title || '') + eyeHtml + '</td>' +
                            '<td>' + (r.unit || '') + '</td>' +
                            '<td align="right">' + r.final_qty + '</td>' +
                            '<td class="text-center">' + statusHtml + '</td>' +
                            '<td style="font-size:10px;">' + durHtml + '</td>' +
                            '<td>' + reasonHtml + '</td>' +
                            '<td class="text-center" style="vertical-align:middle;">' + actionHtml + '</td>' +
                            '</tr>';
                    });
                    $tbody.html(html);
                    stcUpdateReturnBulkBar();

                    var pagHtml = '';
                    if(data.total_pages > 1){
                        var from = (data.page - 1) * data.per_page + 1;
                        var to   = Math.min(data.page * data.per_page, data.total);
                        pagHtml += '<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;padding:10px 4px;gap:8px;">';
                        pagHtml += '<div style="font-size:12px;color:#555;">Showing <strong>' + from + '</strong> &ndash; <strong>' + to + '</strong> of <strong>' + data.total + '</strong> items</div>';
                        pagHtml += '<nav><ul class="pagination pagination-sm mb-0" style="flex-wrap:wrap;">';
                        if(data.page > 1){
                            pagHtml += '<li class="page-item"><a class="page-link stc-return-page-btn" data-page="' + (data.page - 1) + '" href="#">&laquo; Prev</a></li>';
                        } else {
                            pagHtml += '<li class="page-item disabled"><span class="page-link">&laquo; Prev</span></li>';
                        }
                        var pgRange = 2;
                        var pgStart = Math.max(1, data.page - pgRange);
                        var pgEnd   = Math.min(data.total_pages, data.page + pgRange);
                        if(pgStart > 1){
                            pagHtml += '<li class="page-item"><a class="page-link stc-return-page-btn" data-page="1" href="#">1</a></li>';
                            if(pgStart > 2) pagHtml += '<li class="page-item disabled"><span class="page-link">&hellip;</span></li>';
                        }
                        for(var pg = pgStart; pg <= pgEnd; pg++){
                            if(pg === data.page){
                                pagHtml += '<li class="page-item active"><span class="page-link">' + pg + '</span></li>';
                            } else {
                                pagHtml += '<li class="page-item"><a class="page-link stc-return-page-btn" data-page="' + pg + '" href="#">' + pg + '</a></li>';
                            }
                        }
                        if(pgEnd < data.total_pages){
                            if(pgEnd < data.total_pages - 1) pagHtml += '<li class="page-item disabled"><span class="page-link">&hellip;</span></li>';
                            pagHtml += '<li class="page-item"><a class="page-link stc-return-page-btn" data-page="' + data.total_pages + '" href="#">' + data.total_pages + '</a></li>';
                        }
                        if(data.page < data.total_pages){
                            pagHtml += '<li class="page-item"><a class="page-link stc-return-page-btn" data-page="' + (data.page + 1) + '" href="#">Next &raquo;</a></li>';
                        } else {
                            pagHtml += '<li class="page-item disabled"><span class="page-link">Next &raquo;</span></li>';
                        }
                        pagHtml += '</ul></nav></div>';
                    } else if(data.total > 0){
                        pagHtml = '<div style="font-size:12px;color:#555;padding:10px 4px;">Showing <strong>' + data.total + '</strong> item' + (data.total !== 1 ? 's' : '') + '</div>';
                    }
                    $pag.html(pagHtml);
                },
                error: function(){
                    $tbody.html('<tr><td colspan="10" class="text-center text-danger" style="padding:20px;">Error loading return list. Please refresh the page.</td></tr>');
                }
            });
        }

        stcLoadReturnList(1);

        $(document).on('click', '.stc-return-page-btn', function(e){
            e.preventDefault();
            var pg = parseInt($(this).data('page'), 10);
            if(pg && pg !== stcReturnCurrentPage){
                stcLoadReturnList(pg);
                var $tbl = $('#stc-reports-requisition-return-view');
                if($tbl.length){ $('html,body').animate({ scrollTop: $tbl.offset().top - 80 }, 300); }
            }
        });

        $('#stc-return-search-btn').on('click', function(){
            stcLoadReturnList(1);
        });

        $('#stc-return-reset-btn').on('click', function(){
            $('#stc-return-search-site, #stc-return-search-item, #stc-return-search-reason').val('');
            stcReturnSortCol = 'req_date';
            stcReturnSortDir = 'DESC';
            $('.stc-return-sort-icon').html('&#8645;').css('color','#aaa');
            stcLoadReturnList(1);
        });

        $('#stc-return-search-site, #stc-return-search-item, #stc-return-search-reason').on('keypress', function(e){
            if(e.which === 13){ stcLoadReturnList(1); }
        });

        $(document).on('click', '.stc-return-sortable', function(){
            var col = $(this).data('col');
            if(stcReturnSortCol === col){
                stcReturnSortDir = stcReturnSortDir === 'ASC' ? 'DESC' : 'ASC';
            } else {
                stcReturnSortCol = col;
                stcReturnSortDir = 'ASC';
            }
            $('.stc-return-sort-icon').html('&#8645;').css('color','#aaa');
            var icon = stcReturnSortDir === 'ASC' ? '&#8593;' : '&#8595;';
            $(this).find('.stc-return-sort-icon').html(icon).css('color','#333');
            stcLoadReturnList(1);
        });

        // Open Accept Return overlay
        function stcCloseAcceptReturnOverlay(){
            $('#stcAcceptReturnOverlay').removeClass('is-open');
            $('body').css('overflow', '');
            stcReturnBulkIds = [];
            $('#stc-accept-return-mode').val('single');
            $('#stc-accept-return-item-id').val('');
        }
        function stcOpenAcceptReturnOverlay(){
            $('#stcAcceptReturnOverlay').appendTo('body').addClass('is-open');
            $('body').css('overflow', 'hidden');
            setTimeout(function(){ $('#stc-accept-return-msg').focus(); }, 50);
        }

        // Select all / row checkboxes
        $(document).on('change', '#stc-return-select-all', function(){
            var checked = $(this).is(':checked');
            $('.stc-return-row-cb').prop('checked', checked).each(function(){
                var $row = $(this).closest('tr');
                $row.css('background-color', checked ? '#f3e5f5' : '');
            });
            stcUpdateReturnBulkBar();
        });

        $(document).on('change', '.stc-return-row-cb', function(){
            var $row = $(this).closest('tr');
            $row.css('background-color', $(this).is(':checked') ? '#f3e5f5' : '');
            stcUpdateReturnBulkBar();
        });

        // Bulk Accept Selected
        $(document).on('click', '#stc-return-bulk-accept-btn', function(e){
            e.preventDefault();
            stcReturnBulkIds = [];
            $('.stc-return-row-cb:checked').each(function(){
                var id = parseInt($(this).data('item-id'), 10);
                if(id > 0) stcReturnBulkIds.push(id);
            });
            if(stcReturnBulkIds.length === 0){
                alert('Please select at least one item.');
                return;
            }
            $('#stc-accept-return-mode').val('bulk');
            $('#stc-accept-return-item-id').val('');
            $('#stc-accept-return-msg').val('');
            $('#stc-accept-return-err').hide();
            $('#stc-accept-return-info').html(
                'Accepting <strong>' + stcReturnBulkIds.length + '</strong> selected return item(s).<br>Enter one note that will apply to all.'
            );
            stcOpenAcceptReturnOverlay();
        });

        $(document).on('click', '.stc-accept-return-btn', function(e){
            e.preventDefault();
            var itemId = $(this).data('item-id');
            var site   = $(this).data('site') || '';
            var item   = $(this).data('item-title') || '';
            var req    = $(this).data('req') || '';
            stcReturnBulkIds = [];
            $('#stc-accept-return-mode').val('single');
            $('#stc-accept-return-item-id').val(itemId);
            $('#stc-accept-return-msg').val('');
            $('#stc-accept-return-err').hide();
            $('#stc-accept-return-info').html(
                'PR <strong>#' + req + '</strong> &mdash; ' +
                '<strong>' + $('<div/>').text(site).html() + '</strong><br>' +
                $('<div/>').text(item).html()
            );
            stcOpenAcceptReturnOverlay();
        });

        $(document).on('click', '.stc-accept-return-cancel', function(e){
            e.preventDefault();
            stcCloseAcceptReturnOverlay();
        });

        // Click outside dialog closes overlay
        $(document).on('click', '#stcAcceptReturnOverlay', function(e){
            if(e.target === this){ stcCloseAcceptReturnOverlay(); }
        });

        // Submit Accept Return (single or bulk)
        $('#stc-accept-return-submit').on('click', function(){
            var mode = $('#stc-accept-return-mode').val() || 'single';
            var note = $.trim($('#stc-accept-return-msg').val());
            if(!note){
                $('#stc-accept-return-err').show();
                return;
            }
            $('#stc-accept-return-err').hide();
            var $btn = $(this);
            if($btn.data('busy')){ return; }
            $btn.data('busy', 1).prop('disabled', true);
            var prev = $btn.html();
            $btn.html('<i class="fa fa-spinner fa-spin"></i> Saving…');

            var postData;
            if(mode === 'bulk'){
                if(!stcReturnBulkIds.length){
                    alert('No items selected.');
                    $btn.data('busy', 0).prop('disabled', false).html(prev);
                    return;
                }
                postData = {
                    bulk_accept_return: 1,
                    item_ids: stcReturnBulkIds,
                    description: note
                };
            } else {
                var itemId = parseInt($('#stc-accept-return-item-id').val(), 10) || 0;
                postData = {
                    accept_return_item: 1,
                    item_id: itemId,
                    description: note
                };
            }

            $.ajax({
                url     : 'kattegat/ragnar_lothbrok.php',
                method  : 'POST',
                data    : postData,
                dataType: 'json',
                success : function(res){
                    if(res && res.success){
                        stcCloseAcceptReturnOverlay();
                        stcLoadReturnList(stcReturnCurrentPage);
                    } else {
                        alert((res && res.message) ? res.message : 'Failed to accept return.');
                    }
                },
                error   : function(){
                    alert('Failed to accept return. Please try again.');
                },
                complete: function(){
                    $btn.data('busy', 0).prop('disabled', false).html(prev);
                }
            });
        });

        // Eye → Adhoc product details overlay
        function stcCloseReturnAdhocOverlay(){
            $('#stcReturnAdhocOverlay').removeClass('is-open');
            $('body').css('overflow', '');
        }
        function stcOpenReturnAdhocOverlay(){
            $('#stcReturnAdhocOverlay').appendTo('body').addClass('is-open');
            $('body').css('overflow', 'hidden');
        }

        $(document).on('click', '.stc-return-adhoc-eye', function(e){
            e.preventDefault();
            e.stopPropagation();
            var productId = parseInt($(this).data('product-id'), 10) || 0;
            var title = $(this).data('item-title') || '';
            if(productId <= 0){
                alert('Product ID not found for this item.');
                return;
            }
            $('#stc-return-adhoc-info').html(
                'Looking up adhoc for product ID: <strong>' + productId + '</strong>' +
                (title ? ' &mdash; ' + $('<div/>').text(title).html() : '')
            );
            $('#stc-return-adhoc-tbody').html('<tr><td colspan="12" class="text-center text-muted"><i class="fa fa-spinner fa-spin"></i> Loading…</td></tr>');
            stcOpenReturnAdhocOverlay();
            $.ajax({
                url     : 'kattegat/ragnar_lothbrok.php',
                method  : 'POST',
                data    : { return_item_adhoc_details: 1, product_id: productId },
                dataType: 'json',
                success : function(res){
                    if(!res || !res.success){
                        $('#stc-return-adhoc-tbody').html('<tr><td colspan="12" class="text-center text-danger">' + ((res && res.message) ? res.message : 'Failed to load adhoc details.') + '</td></tr>');
                        return;
                    }
                    var label = 'Product ID: <strong>' + (res.product_id || productId) + '</strong>';
                    if(res.product_name){
                        label += ' &mdash; ' + $('<div/>').text(res.product_name).html();
                    } else if(title){
                        label += ' &mdash; ' + $('<div/>').text(title).html();
                    }
                    $('#stc-return-adhoc-info').html(
                        label + ' &nbsp; <span class="text-muted">(' + (res.total || 0) + ' found)</span>'
                    );
                    if(!res.rows || !res.rows.length){
                        $('#stc-return-adhoc-tbody').html('<tr><td colspan="12" class="text-center text-muted">No matching adhoc product found for this product ID.</td></tr>');
                        return;
                    }
                    var html = '';
                    $.each(res.rows, function(i, r){
                        var statusBg = '#6c757d';
                        if(r.status_code == 1) statusBg = '#3f6ad8';
                        else if(r.status_code == 2) statusBg = '#16aaff';
                        else if(r.status_code == 3) statusBg = '#f7b924';
                        else if(r.status_code == 4) statusBg = '#3ac47d';
                        else if(r.status_code == 5) statusBg = '#d92550';
                        html += '<tr>' +
                            '<td class="text-center">' + (i + 1) + '</td>' +
                            '<td class="text-center">' + r.adhoc_id + '</td>' +
                            '<td>' + $('<div/>').text(r.item_desc || '').html() + '</td>' +
                            '<td class="text-right">' + r.qty + '</td>' +
                            '<td class="text-right">' + r.stock + '</td>' +
                            '<td class="text-center">' + $('<div/>').text(r.unit || '').html() + '</td>' +
                            '<td class="text-right">' + r.prate + '</td>' +
                            '<td>' + $('<div/>').text(r.source || '').html() + '</td>' +
                            '<td>' + $('<div/>').text(r.destination || '').html() + '</td>' +
                            '<td>' + $('<div/>').text(r.rack_name || '').html() + '</td>' +
                            '<td class="text-center"><span style="background:' + statusBg + ';color:#fff;padding:2px 6px;border-radius:3px;font-size:11px;">' + $('<div/>').text(r.status || '').html() + '</span></td>' +
                            '<td style="font-size:11px;">' + $('<div/>').text(r.created_date || '').html() + '<br><span class="text-muted">' + $('<div/>').text(r.created_by || '').html() + '</span></td>' +
                            '</tr>';
                    });
                    $('#stc-return-adhoc-tbody').html(html);
                },
                error: function(){
                    $('#stc-return-adhoc-tbody').html('<tr><td colspan="12" class="text-center text-danger">Error loading adhoc details.</td></tr>');
                }
            });
        });

        $(document).on('click', '.stc-return-adhoc-close', function(e){
            e.preventDefault();
            stcCloseReturnAdhocOverlay();
        });
        $(document).on('click', '#stcReturnAdhocOverlay', function(e){
            if(e.target === this){ stcCloseReturnAdhocOverlay(); }
        });
        // =====================================================================

        // Toggle pending reason — short text hides when expanded so full message shows,
        // then the "read less" link always appears at the very end after the message.
        $(document).on('click', '.pending-read-more', function(e) {
            e.preventDefault();
            var $container    = $(this).closest('.pending-reason-container');
            var $link         = $(this);
            var $shortSpan    = $container.find('.pending-reason-short');
            var $fullSpan     = $container.find('.pending-reason-full');

            if($fullSpan.is(':visible')) {
                $fullSpan.hide();
                $shortSpan.show();
                $link.text('...read more');
            } else {
                $shortSpan.hide();
                $fullSpan.show();
                $link.text('read less');
            }
        });
        
        // Handle status dropdown change
        $(document).on('change', '.status-dropdown', function() {
            var $dropdown = $(this);
            var itemId = $dropdown.data('item-id');
            var newStatus = $dropdown.val();
            var oldStatus = $dropdown.data('old-status') || '9';
            
            // Don't proceed if status hasn't actually changed
            if(newStatus == oldStatus) {
                return;
            }
            
            // Only allow Reject (6) or Close (10)
            if(newStatus != '6' && newStatus != '10') {
                alert('Please select either Reject or Close');
                $dropdown.val(oldStatus);
                return;
            }
            
            // Confirm action
            var action = (newStatus == '6') ? 'reject' : 'close';
            var reason = prompt('Please enter a reason for ' + action + 'ing this item:');
            
            if(reason === null) {
                // User cancelled, revert dropdown
                $dropdown.val(oldStatus);
                return;
            }
            
            if(reason.trim() === '') {
                alert('Reason is required');
                $dropdown.val(oldStatus);
                return;
            }
            
            // Disable dropdown during request
            $dropdown.prop('disabled', true);
            
            // Make AJAX request
            $.ajax({
                url: 'kattegat/ragnar_lothbrok.php',
                method: 'POST',
                data: {
                    update_pending_requisition_status: 1,
                    item_id: itemId,
                    status: newStatus,
                    reason: reason
                },
                dataType: 'json',
                success: function(response) {
                    if(response.success) {
                        // Update the dropdown to show status badge instead
                        var statusText = (newStatus == '6') ? 'Rejected' : 'Closed';
                        var statusColor = (newStatus == '6') ? '#e74c3c' : '#34495e';
                        var statusBadge = '<span style="background-color: ' + statusColor + '; color: white; padding: 2px 6px; border-radius: 3px;">' + statusText + '</span>';
                        $dropdown.replaceWith(statusBadge);
                        
                        // Show success message
                        alert('Status updated successfully!');
                        
                        // Optionally reload the page to reflect changes
                        // location.reload();
                    } else {
                        alert('Error: ' + response.message);
                        $dropdown.val(oldStatus);
                        $dropdown.prop('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Error updating status: ' + error);
                    $dropdown.val(oldStatus);
                    $dropdown.prop('disabled', false);
                }
            });
        });

        // =====================================================================
        // Bulk checkbox feature for pending table
        // =====================================================================

        // Helper: update action bar visibility + count
        function stcUpdateBulkBar(){
            var checked = $('.stc-pending-row-cb:checked').length;
            if(checked > 0){
                $('#stc-bulk-selected-count').text(checked + ' item(s) selected');
                $('#stc-bulk-action-bar').css('display','flex');
            } else {
                $('#stc-bulk-action-bar').css('display','none');
                $('#stc-pending-select-all').prop('checked', false).prop('indeterminate', false);
            }
            // Sync select-all state
            var total = $('.stc-pending-row-cb').length;
            if(total > 0){
                if(checked === 0){
                    $('#stc-pending-select-all').prop('checked', false).prop('indeterminate', false);
                } else if(checked === total){
                    $('#stc-pending-select-all').prop('checked', true).prop('indeterminate', false);
                } else {
                    $('#stc-pending-select-all').prop('checked', false).prop('indeterminate', true);
                }
            }
        }

        // After AJAX loads rows, re-bind select-all state (rows replaced)
        // We patch stcLoadPendingList via a post-load hook using MutationObserver
        var _stcPendingObserver = null;
        function stcWatchPendingTbody(){
            var tbody = document.querySelector('.stc-reports-pending-view');
            if(!tbody) return;
            if(_stcPendingObserver) _stcPendingObserver.disconnect();
            _stcPendingObserver = new MutationObserver(function(){
                stcUpdateBulkBar();
            });
            _stcPendingObserver.observe(tbody, { childList: true });
        }
        stcWatchPendingTbody();

        // Select all checkbox
        $(document).on('change', '#stc-pending-select-all', function(){
            var checked = $(this).is(':checked');
            $('.stc-pending-row-cb').prop('checked', checked);
            stcUpdateBulkBar();
        });

        // Individual row checkbox
        $(document).on('change', '.stc-pending-row-cb', function(){
            stcUpdateBulkBar();
        });

        // Highlight row on check
        $(document).on('change', '.stc-pending-row-cb', function(){
            var $row = $(this).closest('tr');
            if($(this).is(':checked')){
                $row.css('background-color','#fff8e1');
            } else {
                $row.css('background-color','');
            }
        });

        // Custom Status dropdown toggle — appended to body to escape overflow clipping
        var $stcStatusMenu = $('#stc-status-dropdown-menu').appendTo('body');

        $(document).on('click', '#stc-fix-btn', function(e){
            e.stopPropagation();
            var rect = this.getBoundingClientRect();
            var menuH = $stcStatusMenu.outerHeight(true) || 80;
            // Open upward so it never goes below the viewport (bar is sticky-bottom)
            $stcStatusMenu.css({
                top  : 'auto',
                bottom: (window.innerHeight - rect.top + 4) + 'px',
                left : rect.left + 'px'
            });
            $stcStatusMenu.toggle();
        });

        // Close dropdown when clicking outside
        $(document).on('click', function(e){
            if(!$(e.target).closest('#stc-fix-btn, #stc-status-dropdown-menu').length){
                $stcStatusMenu.hide();
            }
        });

        // Status dropdown option click (Reject / Close) — bulk
        $(document).on('click', '.stc-bulk-fix-action', function(e){
            e.preventDefault();
            $stcStatusMenu.hide();
            var status = $(this).data('status');
            var label  = $(this).data('label');
            var ids    = [];
            $('.stc-pending-row-cb:checked').each(function(){
                ids.push($(this).data('item-id'));
            });
            if(ids.length === 0){ alert('No items selected.'); return; }

            if(!confirm('Are you sure you want to ' + label + ' ' + ids.length + ' selected item(s)?')){ return; }

            var reason = prompt('Enter a reason for ' + label + ':');
            if(reason === null){ return; }
            if(reason.trim() === ''){ alert('Reason is required.'); return; }

            $('#stc-fix-btn').prop('disabled', true).css('opacity','0.65').html('<i class="fa fa-spinner fa-spin"></i> Updating...');

            $.ajax({
                url    : 'kattegat/ragnar_lothbrok.php',
                method : 'POST',
                data   : {
                    bulk_update_pending_status : 1,
                    item_ids : ids,
                    status   : status,
                    reason   : reason.trim()
                },
                dataType: 'json',
                success : function(res){
                    $('#stc-fix-btn').prop('disabled', false).css('opacity','1').html('<i class="fa fa-pencil-square-o"></i> Status <i class="fa fa-caret-down" style="margin-left:2px;"></i>');
                    if(res.success){
                        alert(res.message || 'Updated successfully.');
                        stcLoadPendingList(stcPendingCurrentPage);
                    } else {
                        alert('Error: ' + (res.message || 'Update failed.'));
                    }
                },
                error: function(){
                    $('#stc-fix-btn').prop('disabled', false).css('opacity','1').html('<i class="fa fa-pencil-square-o"></i> Status <i class="fa fa-caret-down" style="margin-left:2px;"></i>');
                    alert('Network error. Please try again.');
                }
            });
        });

        // Pending button — open pending note modal
        $(document).on('click', '#stc-bulk-pending-btn', function(){
            var ids = [];
            $('.stc-pending-row-cb:checked').each(function(){ ids.push($(this).data('item-id')); });
            if(ids.length === 0){ alert('No items selected.'); return; }

            $('#stc-pending-note-info').text(ids.length + ' item(s) selected. The note will be logged for each selected product.');
            $('#stc-pending-note-msg').val('');
            $('#stc-pending-note-err').hide();
            $('#stcPendingNoteModal').appendTo('body').modal('show');
        });

        // Pending note submit
        $('#stc-pending-note-submit').on('click', function(){
            var msg = $('#stc-pending-note-msg').val().trim();
            if(!msg){ $('#stc-pending-note-err').show(); return; }
            $('#stc-pending-note-err').hide();

            var ids = [];
            $('.stc-pending-row-cb:checked').each(function(){ ids.push($(this).data('item-id')); });
            if(ids.length === 0){ $('#stcPendingNoteModal').modal('hide'); return; }

            var $btn = $(this);
            $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');

            $.ajax({
                url    : 'kattegat/ragnar_lothbrok.php',
                method : 'POST',
                data   : {
                    bulk_add_pending_note : 1,
                    item_ids : ids,
                    message  : msg
                },
                dataType: 'json',
                success : function(res){
                    $btn.prop('disabled', false).html('<i class="fa fa-save"></i> Update Pending');
                    if(res.success){
                        $('#stcPendingNoteModal').modal('hide');
                        alert(res.message || 'Pending notes added successfully.');
                        stcLoadPendingList(stcPendingCurrentPage);
                    } else {
                        alert('Error: ' + (res.message || 'Failed to update.'));
                    }
                },
                error: function(){
                    $btn.prop('disabled', false).html('<i class="fa fa-save"></i> Update Pending');
                    alert('Network error. Please try again.');
                }
            });
        });

        // =====================================================================

		// GLD purchase location -> product breakup modal
		$(document).on('click', '.js-gld-purchase-breakdown', function(){
			var $el = $(this);
			var location = $el.data('location') || '';
			var month = $el.data('month') || '';
			var type = $el.data('type') || 'NA';

			$('#gldPurchaseBreakupModalLabel').text('GLD Purchase Breakup - ' + location);
			$('#gld-purchase-breakup-tbody').html('<tr><td colspan="7" class="text-center text-muted">Loading...</td></tr>');
			$('#gld-purchase-breakup-total').text('₹ --');
			$('#gldPurchaseBreakupModal').modal('show');

			$.ajax({
				url: 'kattegat/ragnar_lothbrok.php',
				method: 'POST',
				dataType: 'json',
				data: {
					gld_purchase_breakdown: 1,
					location: location,
					month: month,
					type: type
				},
				success: function(resp){
					if(!resp || resp.success !== true){
						$('#gld-purchase-breakup-tbody').html('<tr><td colspan="7" class="text-center text-danger">Failed to load data.</td></tr>');
						return;
					}
					var rows = resp.rows || [];
					if(!Array.isArray(rows) || rows.length === 0){
						$('#gld-purchase-breakup-tbody').html('<tr><td colspan="7" class="text-center text-muted">No data found.</td></tr>');
						$('#gld-purchase-breakup-total').text('₹ 0.00');
						return;
					}
					var html = '';
					var sl = 0;
					$.each(rows, function(i, r){
						sl++;
						var pid = (r && r.product_id !== undefined && r.product_id !== null) ? r.product_id : '';
						var pname = (r && r.product_name !== undefined && r.product_name !== null) ? r.product_name : '';
						var cat = (r && r.category !== undefined && r.category !== null) ? r.category : '';
						var qty = (r && r.purchase_qty !== undefined && r.purchase_qty !== null) ? r.purchase_qty : 0;
						var rate = (r && r.rate !== undefined && r.rate !== null) ? r.rate : 0;
						var total = (r && r.total !== undefined && r.total !== null) ? r.total : 0;
						html += '<tr>' +
							'<td class="text-center">'+ sl +'</td>' +
							'<td>'+ pid +'</td>' +
							'<td>'+ pname +'</td>' +
							'<td>'+ cat +'</td>' +
							'<td class="text-right">'+ parseFloat(qty).toLocaleString('en-IN', {minimumFractionDigits:2}) +'</td>' +
							'<td class="text-right">₹ '+ parseFloat(rate).toLocaleString('en-IN', {minimumFractionDigits:2}) +'</td>' +
							'<td class="text-right">₹ '+ parseFloat(total).toLocaleString('en-IN', {minimumFractionDigits:2}) +'</td>' +
						'</tr>';
					});
					$('#gld-purchase-breakup-tbody').html(html);
					var gtotal = (resp && resp.total !== undefined && resp.total !== null) ? resp.total : 0;
					$('#gld-purchase-breakup-total').text('₹ ' + parseFloat(gtotal).toLocaleString('en-IN', {minimumFractionDigits:2}));
				},
				error: function(){
					$('#gld-purchase-breakup-tbody').html('<tr><td colspan="7" class="text-center text-danger">Error loading data.</td></tr>');
				}
			});
		});

		// GLD sale location -> product breakup modal
		$(document).on('click', '.js-gld-sale-breakdown', function(){
			var $el = $(this);
			var location = $el.data('location') || '';
			var month = $el.data('month') || '';
			var type = $el.data('type') || 'NA';

			$('#gldSaleBreakupModalLabel').text('GLD Sale Breakup - ' + location);
			$('#gld-sale-breakup-tbody').html('<tr><td colspan="7" class="text-center text-muted">Loading...</td></tr>');
			$('#gld-sale-breakup-total').text('₹ --');
			$('#gldSaleBreakupModal').modal('show');

			$.ajax({
				url: 'kattegat/ragnar_lothbrok.php',
				method: 'POST',
				dataType: 'json',
				data: {
					gld_sale_breakdown: 1,
					location: location,
					month: month,
					type: type
				},
				success: function(resp){
					if(!resp || resp.success !== true){
						$('#gld-sale-breakup-tbody').html('<tr><td colspan="7" class="text-center text-danger">Failed to load data.</td></tr>');
						return;
					}
					var rows = resp.rows || [];
					if(!Array.isArray(rows) || rows.length === 0){
						$('#gld-sale-breakup-tbody').html('<tr><td colspan="7" class="text-center text-muted">No data found.</td></tr>');
						$('#gld-sale-breakup-total').text('₹ 0.00');
						return;
					}
					var html = '';
					var sl = 0;
					$.each(rows, function(i, r){
						sl++;
						var pid = (r && r.product_id !== undefined && r.product_id !== null) ? r.product_id : '';
						var pname = (r && r.product_name !== undefined && r.product_name !== null) ? r.product_name : '';
						var cat = (r && r.category !== undefined && r.category !== null) ? r.category : '';
						var qty = (r && r.sold_qty !== undefined && r.sold_qty !== null) ? r.sold_qty : 0;
						var rate = (r && r.rate !== undefined && r.rate !== null) ? r.rate : 0;
						var total = (r && r.total !== undefined && r.total !== null) ? r.total : 0;
						html += '<tr>' +
							'<td class="text-center">'+ sl +'</td>' +
							'<td>'+ pid +'</td>' +
							'<td>'+ pname +'</td>' +
							'<td>'+ cat +'</td>' +
							'<td class="text-right">'+ parseFloat(qty).toLocaleString('en-IN', {minimumFractionDigits:2}) +'</td>' +
							'<td class="text-right">₹ '+ parseFloat(rate).toLocaleString('en-IN', {minimumFractionDigits:2}) +'</td>' +
							'<td class="text-right">₹ '+ parseFloat(total).toLocaleString('en-IN', {minimumFractionDigits:2}) +'</td>' +
						'</tr>';
					});
					$('#gld-sale-breakup-tbody').html(html);
					var gtotal = (resp && resp.total !== undefined && resp.total !== null) ? resp.total : 0;
					$('#gld-sale-breakup-total').text('₹ ' + parseFloat(gtotal).toLocaleString('en-IN', {minimumFractionDigits:2}));
				},
				error: function(){
					$('#gld-sale-breakup-tbody').html('<tr><td colspan="7" class="text-center text-danger">Error loading data.</td></tr>');
				}
			});
		});

		function buildGldProfitBreakupRowsHtml(rows, slBase){
			var html = '';
			var gldTT = {
				sl: 'Serial number on this page only (pagination offset). Not calculated from other columns.',
				src: 'Line origin: REQ = requisition dispatch, GLD = GLD sale row. Reference only.',
				adhoc: 'Purchase adhoc / PO line ID this row is tied to. Reference only.',
				pid: 'Product master ID from stc_product. Reference only.',
				pname: 'Display name from product (and subcategory when used). Not a formula column.',
				unit: 'Unit of measure from product master (stc_product_unit). Reference only; does not change maths.',
				qty: 'Quantity on this line. Profit = Sale ₹ × Qty (API Sale ₹ margin multiplied by quantity again).',
				prate: 'Purchase rate per unit from adhoc (stc_purchase_product_adhoc_prate). Basic = Prate × Qty.',
				basic: 'Basic = Prate × Qty. Purchase/stock value for the line before sale margin and GST.',
				gst: 'GST % from product (stc_product_gst). Applied on taxable base (Basic + Sale ₹), not on Prate alone.',
				gstAmt: 'GST ₹ = (Basic + Sale ₹) × (GST % ÷ 100). Tax is on Basic plus the sale-margin amount.',
				salepct: 'Sale margin % from product (stc_product_sale_percentage). Sale ₹ = Basic × (Sale % ÷ 100); Profit column shows Sale ₹ × Qty.',
				saleAmt: 'Sale ₹ = Basic × (Sale % ÷ 100). Profit cell = this Sale ₹ × Qty.',
				total: 'Line total = Basic + Sale ₹ + GST ₹. Sum of purchase subtotal, margin rupees, and GST rupees.',
				listRate: 'List/sell rate per unit from adhoc (stc_purchase_product_adhoc_rate). Reference only; Profit = Sale ₹ × Qty.',
				profit: 'Profit = Sale ₹ × Qty (same as API profit/Sale ₹ figure multiplied by quantity again). Equals Prate × Qty² × (Sale % ÷ 100).'
			};
			var gldTTe = {};
			$.each(gldTT, function(k, v){ gldTTe[k] = $('<div/>').text(v).html(); });
			$.each(rows, function(i, r){
				var sl = slBase + i + 1;
				var src = (r && r.source !== undefined && r.source !== null) ? r.source : '';
				var aid = (r && r.adhoc_id !== undefined && r.adhoc_id !== null) ? r.adhoc_id : '';
				var pid = (r && r.product_id !== undefined && r.product_id !== null) ? r.product_id : '';
				var pname = (r && r.product_name !== undefined && r.product_name !== null) ? $('<div/>').text(r.product_name).html() : '';
				var unit = (r && r.product_unit !== undefined && r.product_unit !== null) ? $('<div/>').text(r.product_unit).html() : '—';
				var qty = (r && r.qty !== undefined && r.qty !== null) ? r.qty : 0;
				var prate = (r && r.prate !== undefined && r.prate !== null) ? r.prate : 0;
				var basic = (r && r.basic !== undefined && r.basic !== null) ? r.basic : 0;
				var gst = (r && r.gst_pct !== undefined && r.gst_pct !== null) ? r.gst_pct : 0;
				var gstAmt = (r && r.gst_amount !== undefined && r.gst_amount !== null) ? r.gst_amount : 0;
				var salepct = (r && r.sale_pct !== undefined && r.sale_pct !== null) ? r.sale_pct : 0;
				var saleAmt = (r && r.sale_amount !== undefined && r.sale_amount !== null) ? r.sale_amount : 0;
				var total = (r && r.total !== undefined && r.total !== null) ? r.total : 0;
				var listRate = (r && r.rate !== undefined && r.rate !== null) ? r.rate : 0;
				var saleRupeeLine = parseFloat(saleAmt);
				var qtyNum = parseFloat(qty);
				var pVal = saleRupeeLine * qtyNum;
				var profitClr = pVal > 0 ? 'text-success' : 'text-danger';
				var profitSign = pVal > 0 ? '+' : (pVal < 0 ? '-' : '');
				var profitAmt = Math.abs(pVal).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2});
				html += '<tr>' +
					'<td class="text-center text-muted" style="cursor:help" title="'+ gldTTe.sl +'">'+ sl +'</td>' +
					'<td class="text-center" style="cursor:help" title="'+ gldTTe.src +'"><span class="badge badge-secondary">'+ $('<div/>').text(src).html() +'</span></td>' +
					'<td class="text-center" style="cursor:help" title="'+ gldTTe.adhoc +'">'+ aid +'</td>' +
					'<td class="text-center" style="cursor:help" title="'+ gldTTe.pid +'">'+ pid +'</td>' +
					'<td class="text-left" style="cursor:help" title="'+ gldTTe.pname +'">'+ pname +'</td>' +
					'<td class="text-center" style="cursor:help" title="'+ gldTTe.unit +'">'+ unit +'</td>' +
					'<td class="text-right" style="cursor:help" title="'+ gldTTe.qty +'">'+ parseFloat(qty).toLocaleString('en-IN', {minimumFractionDigits:2}) +'</td>' +
					'<td class="text-right text-info" style="cursor:help" title="'+ gldTTe.prate +'">₹ '+ parseFloat(prate).toLocaleString('en-IN', {minimumFractionDigits:2}) +'</td>' +
					'<td class="text-right" style="cursor:help" title="'+ gldTTe.basic +'">₹ '+ parseFloat(basic).toLocaleString('en-IN', {minimumFractionDigits:2}) +'</td>' +
					'<td class="text-right" style="cursor:help" title="'+ gldTTe.gst +'">'+ parseFloat(gst).toLocaleString('en-IN', {minimumFractionDigits:2}) +'%</td>' +
					'<td class="text-right" style="cursor:help" title="'+ gldTTe.gstAmt +'">₹ '+ parseFloat(gstAmt).toLocaleString('en-IN', {minimumFractionDigits:2}) +'</td>' +
					'<td class="text-right" style="cursor:help" title="'+ gldTTe.salepct +'">'+ parseFloat(salepct).toLocaleString('en-IN', {minimumFractionDigits:2}) +'%</td>' +
					'<td class="text-right" style="cursor:help" title="'+ gldTTe.saleAmt +'">₹ '+ parseFloat(saleAmt).toLocaleString('en-IN', {minimumFractionDigits:2}) +'</td>' +
					'<td class="text-right font-weight-bold text-primary" style="cursor:help" title="'+ gldTTe.total +'">₹ '+ parseFloat(total).toLocaleString('en-IN', {minimumFractionDigits:2}) +'</td>' +
					'<td class="text-right text-success" style="cursor:help" title="'+ gldTTe.listRate +'">₹ '+ parseFloat(listRate).toLocaleString('en-IN', {minimumFractionDigits:2}) +'</td>' +
					'<td class="text-right font-weight-bold '+ profitClr +'" style="cursor:help" title="'+ gldTTe.profit +'">'+ profitSign +'₹ '+ profitAmt +'</td>' +
				'</tr>';
			});
			return html;
		}

		function renderGldProfitBreakup(resp){
			if(!resp || resp.success !== true){
				$('#gld-profit-breakup-tbody').html('<tr><td colspan="16" class="text-center text-danger">Failed to load data.</td></tr>');
				$('#gld-profit-breakup-pager-wrap').hide();
				return;
			}
			var rows = resp.rows || [];
			var totalRows = (resp.total_rows !== undefined && resp.total_rows !== null) ? parseInt(resp.total_rows, 10) : 0;
			var page = (resp.page !== undefined && resp.page !== null) ? parseInt(resp.page, 10) : 1;
			var perPage = (resp.per_page !== undefined && resp.per_page !== null) ? parseInt(resp.per_page, 10) : 25;
			var totalPages = (resp.total_pages !== undefined && resp.total_pages !== null) ? parseInt(resp.total_pages, 10) : 0;

			var $modal = $('#gldProfitBreakupModal');
			var ctx = $modal.data('profitCtx') || {};
			ctx.page = page;
			$modal.data('profitCtx', ctx);

			var tSale = (resp.total_sale !== undefined && resp.total_sale !== null) ? resp.total_sale : 0;
			var tProf = (resp.total_profit !== undefined && resp.total_profit !== null) ? resp.total_profit : 0;
			$('#gld-profit-breakup-total').text('₹ ' + parseFloat(tSale).toLocaleString('en-IN', {minimumFractionDigits:2}));
			var tProfN = parseFloat(tProf);
			var tProfSign = tProfN > 0 ? '+' : (tProfN < 0 ? '-' : '');
			var tProfAmt = Math.abs(tProfN).toLocaleString('en-IN', {minimumFractionDigits:2, maximumFractionDigits:2});
			$('#gld-profit-breakup-profit').text(tProfSign + '₹ ' + tProfAmt);
			var $profBadge = $('#gld-profit-breakup-profit').parent('.badge');
			$profBadge.removeClass('badge-success badge-danger badge-secondary').addClass(tProfN > 0 ? 'badge-success' : 'badge-danger');

			if(!Array.isArray(rows) || rows.length === 0){
				$('#gld-profit-breakup-tbody').html('<tr><td colspan="16" class="text-center text-muted">No data found.</td></tr>');
				$('#gld-profit-breakup-pager-wrap').hide();
				return;
			}

			var slBase = (page - 1) * perPage;
			var html = buildGldProfitBreakupRowsHtml(rows, slBase);
			$('#gld-profit-breakup-tbody').html(html);

			if(totalPages > 1){
				$('#gld-profit-breakup-pager-wrap').show();
				$('#gld-profit-breakup-pageinfo').text('Page ' + page + ' of ' + totalPages + ' · ' + totalRows + ' rows');
				$('#gld-profit-breakup-prev').prop('disabled', page <= 1);
				$('#gld-profit-breakup-next').prop('disabled', page >= totalPages);
			} else {
				$('#gld-profit-breakup-pager-wrap').hide();
			}
		}

		function loadGldProfitBreakup(page){
			var ctx = $('#gldProfitBreakupModal').data('profitCtx');
			if(!ctx){
				return;
			}
			page = page || 1;
			ctx.page = page;
			$('#gldProfitBreakupModal').data('profitCtx', ctx);
			$('#gld-profit-breakup-tbody').html('<tr><td colspan="16" class="text-center text-muted">Loading...</td></tr>');
			$.ajax({
				url: 'kattegat/ragnar_lothbrok.php',
				method: 'POST',
				dataType: 'json',
				data: {
					gld_profit_breakdown: 1,
					month: ctx.month,
					type: ctx.type,
					date_from: ctx.df,
					date_to: ctx.dt,
					page: page,
					per_page: 25
				},
				success: renderGldProfitBreakup,
				error: function(){
					$('#gld-profit-breakup-tbody').html('<tr><td colspan="16" class="text-center text-danger">Error loading data.</td></tr>');
					$('#gld-profit-breakup-pager-wrap').hide();
				}
			});
		}

		$(document).on('click', '.js-gld-profit-breakdown', function(){
			var $el = $(this);
			var month = $el.data('month') || '';
			var type = $el.data('type') || 'NA';
			var df = $el.data('date-from') || '';
			var dt = $el.data('date-to') || '';

			$('#gldProfitBreakupModal').data('profitCtx', { month: month, type: type, df: df, dt: dt, page: 1 });
			$('#gldProfitBreakupModalLabel').text('GLD Profit Breakup');
			$('#gld-profit-breakup-total').text('₹ --');
			$('#gld-profit-breakup-profit').text('₹ --');
			$('#gld-profit-breakup-profit').parent('.badge').removeClass('badge-success badge-danger').addClass('badge-secondary');
			$('#gldProfitBreakupModal').modal('show');
			loadGldProfitBreakup(1);
		});

		$(document).on('click', '#gld-profit-breakup-prev', function(){
			var ctx = $('#gldProfitBreakupModal').data('profitCtx');
			if(!ctx || ctx.page <= 1){
				return;
			}
			loadGldProfitBreakup(ctx.page - 1);
		});

		$(document).on('click', '#gld-profit-breakup-next', function(){
			var ctx = $('#gldProfitBreakupModal').data('profitCtx');
			if(!ctx){
				return;
			}
			loadGldProfitBreakup(ctx.page + 1);
		});

		function fetchAllGldProfitBreakupRows(ctx, onDone){
			var perPage = 100;
			var acc = [];
			function loadPage(p){
				$.ajax({
					url: 'kattegat/ragnar_lothbrok.php',
					method: 'POST',
					dataType: 'json',
					data: {
						gld_profit_breakdown: 1,
						month: ctx.month,
						type: ctx.type,
						date_from: ctx.df,
						date_to: ctx.dt,
						page: p,
						per_page: perPage
					},
					success: function(resp){
						if(!resp || resp.success !== true){
							onDone(null);
							return;
						}
						var chunk = resp.rows || [];
						$.each(chunk, function(i, r){ acc.push(r); });
						var totalPages = parseInt(resp.total_pages, 10) || 1;
						if(p >= totalPages){
							onDone(acc);
						} else {
							loadPage(p + 1);
						}
					},
					error: function(){ onDone(null); }
				});
			}
			loadPage(1);
		}

		$(document).on('click', '#gld-profit-breakup-export-excel', function(){
			var ctx = $('#gldProfitBreakupModal').data('profitCtx');
			if(!ctx){
				return;
			}
			var $tbody = $('#gld-profit-breakup-tbody');
			if($tbody.find('td[colspan]').length){
				alert('Load profit breakup data before exporting.');
				return;
			}
			var $btn = $(this);
			var prevHtml = $btn.html();
			$btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Exporting…');
			fetchAllGldProfitBreakupRows(ctx, function(allRows){
				$btn.prop('disabled', false).html(prevHtml);
				if(allRows === null){
					alert('Could not load data for export.');
					return;
				}
				if(!allRows.length){
					alert('No rows to export.');
					return;
				}
				var safeMonth = (ctx.month || 'export').toString().replace(/[^\w\-]/g, '_');
				var fname = 'gld-profit-breakup-' + safeMonth + '.xls';
				var rowHtml = buildGldProfitBreakupRowsHtml(allRows, 0);
				var $orig = $('#gld-profit-breakup-table');
				var $clone = $('<table/>').append($orig.find('thead').first().clone()).append($('<tbody/>').html(rowHtml));
				$clone.hide().appendTo('body');
				$clone.table2excel({ filename: fname });
				$clone.remove();
			});
		});
    </script>

	<!-- GLD Purchase Breakup Modal -->
	<div class="modal fade" id="gldPurchaseBreakupModal" tabindex="-1" role="dialog" aria-labelledby="gldPurchaseBreakupModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="gldPurchaseBreakupModalLabel">GLD Purchase Breakup</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="table-responsive">
						<table class="table table-bordered table-hover mb-0">
							<thead>
								<tr>
									<th class="text-center">Sl No</th>
									<th class="text-center">Product ID</th>
									<th class="text-center">Product Name</th>
									<th class="text-center">Category</th>
									<th class="text-center">Purchase Qty</th>
									<th class="text-center">Rate</th>
									<th class="text-center">Total</th>
								</tr>
							</thead>
							<tbody id="gld-purchase-breakup-tbody">
								<tr><td colspan="7" class="text-center text-muted">Click an amount to load breakup.</td></tr>
							</tbody>
						</table>
					</div>
					<div class="d-flex justify-content-end mt-3">
						<span class="badge badge-pill badge-success" style="font-size:14px;">Grand Total: <span id="gld-purchase-breakup-total">₹ --</span></span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- GLD Sale Breakup Modal -->
	<div class="modal fade" id="gldSaleBreakupModal" tabindex="-1" role="dialog" aria-labelledby="gldSaleBreakupModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="gldSaleBreakupModalLabel">GLD Sale Breakup</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="table-responsive">
						<table class="table table-bordered table-hover mb-0">
							<thead>
								<tr>
									<th class="text-center">Sl No</th>
									<th class="text-center">Product ID</th>
									<th class="text-center">Product Name</th>
									<th class="text-center">Category</th>
									<th class="text-center">Sold Qty</th>
									<th class="text-center">Rate</th>
									<th class="text-center">Total</th>
								</tr>
							</thead>
							<tbody id="gld-sale-breakup-tbody">
								<tr><td colspan="7" class="text-center text-muted">Click an amount to load breakup.</td></tr>
							</tbody>
						</table>
					</div>
					<div class="d-flex justify-content-end mt-3">
						<span class="badge badge-pill badge-success" style="font-size:14px;">Grand Total: <span id="gld-sale-breakup-total">₹ --</span></span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- GLD Profit Breakup Modal -->
	<div class="modal fade" id="gldProfitBreakupModal" tabindex="-1" role="dialog" aria-labelledby="gldProfitBreakupModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="gldProfitBreakupModalLabel">GLD Profit Breakup</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p class="small text-muted mb-2">Read each row left → right: product, unit &amp; qty, then <strong class="text-info">Prate</strong> and <strong>Basic</strong> (= Prate×Qty). Then GST % &amp; GST ₹, Sale % &amp; Sale ₹ (= Basic × Sale % ÷ 100). <strong class="text-primary">Line total</strong> (= Basic + Sale ₹ + GST ₹). <strong class="text-success">List rate</strong> is quoted sell ₹/unit. <strong>Profit</strong> = <strong>Sale ₹ × Qty</strong>.</p>
					<div class="d-flex justify-content-end mb-2">
						<button type="button" class="btn btn-sm btn-success" id="gld-profit-breakup-export-excel"><i class="fa fa-file-excel-o"></i> Export Excel</button>
					</div>
					<div class="table-responsive">
						<table id="gld-profit-breakup-table" class="table table-bordered table-hover table-sm mb-0">
							<thead class="thead-light">
								<tr>
									<th class="text-center" style="cursor:help" title="Serial number on this page only (pagination offset). Not calculated from other columns.">#</th>
									<th class="text-center" style="cursor:help" title="Line origin: REQ = requisition dispatch, GLD = GLD sale row. Reference only.">Src</th>
									<th class="text-center" style="cursor:help" title="Purchase adhoc / PO line ID this row is tied to. Reference only.">Adhoc</th>
									<th class="text-center" style="cursor:help" title="Product master ID from stc_product. Reference only.">PID</th>
									<th class="text-center" style="cursor:help" title="Display name from product (and subcategory when used). Not a formula column.">Product</th>
									<th class="text-center" style="cursor:help" title="Unit of measure from product master (stc_product_unit). Reference only; does not change maths.">Unit</th>
									<th class="text-center" style="cursor:help" title="Quantity on this line. Profit shown = Sale ₹ × Qty.">Qty</th>
									<th class="text-center" style="cursor:help" title="Purchase rate per unit from adhoc (stc_purchase_product_adhoc_prate). Basic = Prate × Qty.">Prate</th>
									<th class="text-center" style="cursor:help" title="Basic = Prate × Qty. Purchase/stock value for the line before sale margin and GST.">Basic</th>
									<th class="text-center" style="cursor:help" title="GST % from product (stc_product_gst). Applied on taxable base (Basic + Sale ₹), not on Prate alone.">GST %</th>
									<th class="text-center" style="cursor:help" title="GST ₹ = (Basic + Sale ₹) × (GST % ÷ 100). Tax is on Basic plus the sale-margin amount.">GST ₹</th>
									<th class="text-center" style="cursor:help" title="Sale margin % from product (stc_product_sale_percentage). Sale ₹ on Basic; Profit = Sale ₹ × Qty.">Sale %</th>
									<th class="text-center" style="cursor:help" title="Sale ₹ = Basic × (Sale % ÷ 100). Profit column multiplies this by Qty again.">Sale ₹</th>
									<th class="text-center" style="cursor:help" title="Line total = Basic + Sale ₹ + GST ₹. Sum of purchase subtotal, margin rupees, and GST rupees.">Line total</th>
									<th class="text-center" style="cursor:help" title="List/sell rate per unit from adhoc (stc_purchase_product_adhoc_rate). Reference only; Profit = Sale ₹ × Qty.">List ₹</th>
									<th class="text-center" style="cursor:help" title="Profit = Sale ₹ × Qty (= API profit margin × Qty).">Profit</th>
								</tr>
							</thead>
							<tbody id="gld-profit-breakup-tbody">
								<tr><td colspan="16" class="text-center text-muted">Click total profit to load breakup.</td></tr>
							</tbody>
						</table>
					</div>
					<div class="d-flex justify-content-between align-items-center flex-wrap mt-2 mb-1" id="gld-profit-breakup-pager-wrap" style="display:none;">
						<button type="button" class="btn btn-sm btn-outline-secondary" id="gld-profit-breakup-prev">&laquo; Prev</button>
						<span class="small text-muted" id="gld-profit-breakup-pageinfo"></span>
						<button type="button" class="btn btn-sm btn-outline-secondary" id="gld-profit-breakup-next">Next &raquo;</button>
					</div>
					<div class="d-flex justify-content-end flex-wrap mt-3">
						<span class="badge badge-pill badge-info mr-2 mb-1" style="font-size:14px;">Sale total: <span id="gld-profit-breakup-total">₹ --</span></span>
						<span class="badge badge-pill badge-secondary mb-1" style="font-size:14px;">Profit total: <span id="gld-profit-breakup-profit">₹ --</span></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
