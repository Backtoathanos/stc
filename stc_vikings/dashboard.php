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
                                              <i class="fa fa-cubes"></i> GLD Summary
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
                                                <div class="col-md-4">
                                                    <div class="mt-4">
                                                        <canvas id="gldDonutChart" width="400" height="220"></canvas>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
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
                                                    <div class="table-responsive">
                                                    <table class="mb-0 table table-bordered" id="stc-reports-requisition-pending-view">
                                            <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center" width="10%">PR Date &<br>No</th>
                                                <th class="text-center" style="width: 300px;text-align: center;height: 118px;">Site Name</th>
                                                <th class="text-center" style="width: 500px;text-align: center;height: 118px;word-wrap: break-word;white-space: normal;">Item Desc</th>
                                                <th class="text-center" style="width: 40px;text-align: center;height: 118px;word-wrap: break-word;white-space: normal;">Unit</th>
                                                <th class="text-center" style="width: 80px;text-align: center;height: 118px;"><div style="transform: rotate(-90deg); white-space: nowrap; width: 20px;">Proc Apprv Qty</div></th>
                                                <th class="text-center" style="width: 80px;text-align: center;height: 118px;"><div style="transform: rotate(-90deg); white-space: nowrap; width: 20px;">Dispatch Qty</div></th>
                                                <th class="text-center" style="width: 80px;text-align: center;height: 118px;"><div style="transform: rotate(-90deg); white-space: nowrap; width: 20px;">Pending Qty</div></th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Pending Duration</th>
                                                <th class="text-center" style="width: 500px;">Pending Reason</th>
                                            </tr>
                                            </thead>
                                            <tbody class="stc-reports-pending-view">
                                             <?php
                                             include_once("../MCU/db.php");
                                             $optimusprime='';
                                             $slno=0;
                                             $loopcount=0;
                                             $query="
                                                SELECT DISTINCT
                                                   `stc_requisition_combiner_id`,
                                                   DATE(`stc_requisition_combiner_date`) as stc_req_comb_date,
                                                   `stc_cust_super_requisition_list_items`.`stc_cust_super_requisition_list_id` as reqlistid,
                                                   DATE(`stc_cust_super_requisition_list_date`) as stc_req_date,
                                                   `stc_cust_super_requisition_list_items_req_id`,
                                                   `stc_cust_project_title`,
                                                   `stc_cust_super_requisition_list_items_title`,
                                                   `stc_cust_super_requisition_list_items_unit`,
                                                   `stc_cust_super_requisition_list_items_reqqty`,
                                                   `stc_cust_super_requisition_list_items_approved_qty`,
                                                   `stc_cust_super_requisition_items_finalqty`,
                                                   `stc_cust_super_requisition_list_items_status`,
                                                   DATE(`created_date`) as stc_log_date
                                                FROM `stc_cust_super_requisition_list_items`
                                                INNER JOIN `stc_cust_super_requisition_list` 
                                                ON `stc_cust_super_requisition_list_items_req_id`=`stc_cust_super_requisition_list`.`stc_cust_super_requisition_list_id`
                                                INNER JOIN `stc_cust_project` 
                                                ON `stc_cust_super_requisition_list_project_id`=`stc_cust_project_id`
                                                INNER JOIN `stc_requisition_combiner_req` 
                                                ON `stc_requisition_combiner_req_requisition_id`=`stc_cust_super_requisition_list`.`stc_cust_super_requisition_list_id` 
                                                INNER JOIN `stc_requisition_combiner` 
                                                ON `stc_requisition_combiner_id`=`stc_requisition_combiner_req_comb_id` 
                                                INNER JOIN `stc_cust_super_requisition_list_items_log` 
                                                ON `item_id`=`stc_cust_super_requisition_list_items`.`stc_cust_super_requisition_list_id`
                                                WHERE `stc_cust_super_requisition_items_finalqty`!=0 AND `stc_cust_super_requisition_list_items_status`='9' AND `title`='Pending' 
                                                GROUP BY `stc_requisition_combiner_id`
                                                ORDER BY DATE(`stc_cust_super_requisition_list_date`) DESC
                                             ";
                                             $getrequisitionsqry=mysqli_query($con, $query);
                                             if(mysqli_num_rows($getrequisitionsqry)>0){
                                                foreach($getrequisitionsqry as$requisitionrow){
                                                   $loopcount++;
                                                   $slno++;
                                                   $rqitemstts='';
                                                   $stcdispatchedqty=0;
                                                   $stcrecievedqty=0;
                                                   $stcpendingqty=0;
                                                   $rqitemstts='';
                                                       if($requisitionrow['stc_cust_super_requisition_list_items_status']==1){
                                                           $rqitemstts='<span style="background-color: #3498db; color: white; padding: 2px 6px; border-radius: 3px;">Ordered</span>';
                                                       }elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==2){
                                                           $rqitemstts='<span style="background-color: #2ecc71; color: white; padding: 2px 6px; border-radius: 3px;">Approved</span>';
                                                       }elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==3){
                                                           $rqitemstts='<span style="background-color: #27ae60; color: white; padding: 2px 6px; border-radius: 3px;">Accepted</span>';
                                                       }elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==4){
                                                           $rqitemstts='<span style="background-color: #f39c12; color: white; padding: 2px 6px; border-radius: 3px;">Dispatched</span>';
                                                       }elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==5){
                                                           $rqitemstts='<span style="background-color: #16a085; color: white; padding: 2px 6px; border-radius: 3px;">Received</span>';
                                                       }elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==6){
                                                           $rqitemstts='<span style="background-color: #e74c3c; color: white; padding: 2px 6px; border-radius: 3px;">Rejected</span>';
                                                       }elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==7){
                                                           $rqitemstts='<span style="background-color: #95a5a6; color: white; padding: 2px 6px; border-radius: 3px;">Canceled</span>';
                                                       }elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==8){
                                                           $rqitemstts='<span style="background-color: #9b59b6; color: white; padding: 2px 6px; border-radius: 3px;">Returned</span>';
                                                       }elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==9){
                                                           $rqitemstts='<span style="background-color:rgb(255, 47, 47); color: white; padding: 2px 6px; border-radius: 3px;">Pending</span>';
                                                       }else{
                                                           $rqitemstts='<span style="background-color: #34495e; color: white; padding: 2px 6px; border-radius: 3px;">Closed</span>';
                                                       }
                                                   $stcdecqtyqry=mysqli_query($con, "
                                                      SELECT 
                                                         `stc_cust_super_requisition_list_items_rec_recqty`
                                                      FROM `stc_cust_super_requisition_list_items_rec` 
                                                      WHERE 
                                                         `stc_cust_super_requisition_list_items_rec_list_id`='".$requisitionrow['stc_cust_super_requisition_list_items_req_id']."' 
                                                      AND `stc_cust_super_requisition_list_items_rec_list_item_id`='".$requisitionrow['reqlistid']."'  
                                                   ");
                                                   foreach($stcdecqtyqry as $dispatchedrow){
                                                      $stcdispatchedqty+=$dispatchedrow['stc_cust_super_requisition_list_items_rec_recqty'];
                                                   }
                                       
                                                   $stcrecqtyqry=mysqli_query($con, "
                                                      SELECT 
                                                         `stc_cust_super_requisition_rec_items_fr_supervisor_rqitemqty`
                                                      FROM `stc_cust_super_requisition_rec_items_fr_supervisor` 
                                                      WHERE `stc_cust_super_requisition_rec_items_fr_supervisor_rqitemid`='".$requisitionrow['reqlistid']."'  
                                                   ");
                                                   foreach($stcrecqtyqry as $recievedrow){
                                                      $stcrecievedqty+=$recievedrow['stc_cust_super_requisition_rec_items_fr_supervisor_rqitemqty'];
                                                   }
                                       
                                                   $stcconsumedqty=0;
                                                   $stcconsrecqtyqry=mysqli_query($con, "
                                                      SELECT 
                                                         SUM(`stc_cust_super_list_items_consumption_items_qty`) AS consumable_qty
                                                      FROM `stc_cust_super_list_items_consumption_items` 
                                                      WHERE `stc_cust_super_list_items_consumption_items_name`='".$requisitionrow['reqlistid']."'  
                                                   ");
                                                   foreach($stcconsrecqtyqry as $consumedrow){
                                                      $stcconsumedqty+=$consumedrow['consumable_qty'];
                                                   }
                                       
                                                   $challanqry=mysqli_query($con, "
                                                      SELECT DISTINCT
                                                         `stc_sale_product_id`,
                                                         `stc_sale_product_date`
                                                      FROM
                                                         `stc_sale_product`
                                                      INNER JOIN 
                                                         `stc_sale_product_items` 
                                                      ON 
                                                         `stc_sale_product_items_sale_product_id`=`stc_sale_product_id`
                                                      INNER JOIN 
                                                         `stc_cust_super_requisition_list_items` 
                                                      ON 
                                                         `stc_cust_super_requisition_list_items_req_id`=`stc_sale_product_order_id`
                                                      INNER JOIN 
                                                         `stc_cust_super_requisition_list_items_rec` 
                                                      ON 
                                                         `stc_cust_super_requisition_list_items_rec_list_item_id`=`stc_cust_super_requisition_list_id`
                                                      WHERE
                                                         `stc_sale_product_order_id`='".mysqli_real_escape_string($con, $requisitionrow['stc_cust_super_requisition_list_items_req_id'])."'
                                                      AND 
                                                         `stc_cust_super_requisition_list_id`='".mysqli_real_escape_string($con, $requisitionrow['reqlistid'])."'
                                                   ");
                                                   $challaninfo='';
                                                   foreach($challanqry as $challanrow){
                                                      $challaninfo.=
                                                          '<a href="challan-preview.php?pid='.$challanrow['stc_sale_product_id'].'">'.
                                                               date('d-m-Y',strtotime($challanrow['stc_sale_product_date'])).
                                                            '<br>'.
                                                               $challanrow['stc_sale_product_id'].
                                                            '<br>
                                                          </a>
                                                         '
                                                      ;
                                                   }
                                                   $stcpendingqty=$requisitionrow['stc_cust_super_requisition_items_finalqty'] - $stcdispatchedqty;
                                                       
                                                       $cosump_bal_qty = $stcrecievedqty - $stcconsumedqty;
                                                       $stcbalqtymark = '';
                                                       if($cosump_bal_qty>0){
                                                      $stcbalqtymark='
                                                         <p class="form-control" style="
                                                             background: #ffd81a;
                                                             color: red;
                                                         ">
                                                            '.number_format($cosump_bal_qty, 2).'
                                                         </b>
                                                      ';
                                                   }else{
                                                      $stcbalqtymark=number_format($cosump_bal_qty, 2);
                                                   }
                                                   if($stcpendingqty>0){
                                                      $stcpendingqty='
                                                         <p class="form-control" style="
                                                             background: #ffd81a;
                                                             color: red;
                                                         ">
                                                            '.number_format($stcpendingqty, 2).'
                                                         </b>
                                                      ';
                                                      // Calculate Pending Duration
                                                      $pendingduration = '';
                                                      if(!empty($requisitionrow['stc_log_date'])){
                                                         $log_date = new DateTime($requisitionrow['stc_log_date']);
                                                         $today = new DateTime();
                                                         $interval = $log_date->diff($today);
                                                         $months = $interval->y * 12 + $interval->m;
                                                         
                                                         // Calculate total days for more accurate month calculation
                                                         $total_days = $log_date->diff($today)->days;
                                                         $months_precise = floor($total_days / 30);
                                                         
                                                         // Determine color based on months
                                                         $bg_color = '';
                                                         $text_color = '#000000';
                                                         
                                                         if($months_precise >= 3){
                                                            $bg_color = '#ff0000'; // Red for 3+ months
                                                            $text_color = '#ffffff';
                                                         }elseif($months_precise >= 2){
                                                            $bg_color = '#ff8800'; // Orange for 2 months
                                                            $text_color = '#ffffff';
                                                         }elseif($months_precise >= 1){
                                                            $bg_color = '#ffd700'; // Yellow/Gold for 1 month
                                                            $text_color = '#000000';
                                                         }else{
                                                            $bg_color = '#ffffff'; // White for less than 1 month
                                                            $text_color = '#000000';
                                                         }
                                                         
                                                         $pendingduration = '
                                                            <div style="
                                                               background-color: '.$bg_color.';
                                                               color: '.$text_color.';
                                                               padding: 5px 10px;
                                                               border-radius: 3px;
                                                               text-align: center;
                                                               font-weight: bold;
                                                            ">
                                                               '.$months_precise.' Month'.($months_precise != 1 ? 's' : '').' ('.$total_days.' Days)
                                                            </div>
                                                         ';
                                                      }else{
                                                         $pendingduration = '<div style="padding: 5px 10px;">N/A</div>';
                                                      }
                                                      
                                                      $pendingreason='<a href="stc-requisition-combiner-fsale.php?requi_id='.$requisitionrow['stc_requisition_combiner_id'].'" target="__blank">';
                                                      $qry=mysqli_query($con, "
                                                         SELECT `message` FROM `stc_cust_super_requisition_list_items_log` WHERE title='Pending' AND `item_id`='".$requisitionrow['reqlistid']."'
                                                      ");
                                                      foreach($qry as $result){
                                                         $pendingreason.=$result['message'].'<br>';
                                                      }
                                                      $pendingreason.='</a>';
                                                           $optimusprime.='
                                                                   <tr>
                                                                       <td>'.$slno.'</td>
                                                                       <td>
                                                                           '.$requisitionrow['stc_req_date'].'<br>
                                                                           '.$requisitionrow['stc_cust_super_requisition_list_items_req_id'].'
                                                                       </td>
                                                                       <td>'.$requisitionrow['stc_cust_project_title'].'</td>
                                                                       <td>'.$requisitionrow['stc_cust_super_requisition_list_items_title'].'</td>
                                                                       <td>'.$requisitionrow['stc_cust_super_requisition_list_items_unit'].'</td>
                                                                       <td align="right">'.number_format($requisitionrow['stc_cust_super_requisition_items_finalqty'], 2).'</td>
                                                                       <td align="right">'.number_format($stcdispatchedqty, 2).'</td>
                                                                       <td align="right">'.$stcpendingqty.'</td>
                                                                       <td>'.$rqitemstts.'</td>
                                                                       <td>'.$pendingduration.'</td>
                                                                       <td>'.$pendingreason.'</td>
                                                                   </tr>
                                                           ';
                                                   }
                                                           
                                                }
                                             }else{
                                                $optimusprime.='
                                                      <tr>
                                                         <td colspan="11">No requisition found!!!</td>
                                                      </tr>
                                                ';
                                             }
                                             echo $optimusprime;
                                             ?>
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
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script type="text/javascript" src="./assets/scripts/fusioncharts.js"></script>
    <script type="text/javascript" src="./assets/scripts/fusioncharts.charts.js"></script>
    <script type="text/javascript" src="./assets/scripts/themes/fusioncharts.theme.zune.js"></script>
    <script type="text/javascript" src="./assets/scripts/loginopr.js"></script>
    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    <script type="text/javascript" src="./assets/scripts/jarvis.js"></script>
    <script>
        $(document).ready(function(){
            var d = new Date($.now());
            var month=$('.stc-dash-month').val();
            var type=$('.stc-dash-type').val();
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
                                '<td class="text-right"><span class="badge badge-pill badge-info" style="font-size:14px;">₹ ' + parseFloat(item.sale_amount).toLocaleString('en-IN', {minimumFractionDigits:2}) + '</span></td>' +
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
                                '<td class="text-right"><span class="badge badge-pill badge-info" style="font-size:14px;">₹ ' + parseFloat(item.purchase_amount).toLocaleString('en-IN', {minimumFractionDigits:2}) + '</span></td>' +
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
    </script>
</body>
</html>
