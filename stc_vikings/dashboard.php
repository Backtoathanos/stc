<?php  
session_start(); 
if(isset($_SESSION["stc_empl_id"]) && ($_SESSION["stc_empl_role"]>0)){ 
}else{ 
    header("Location:index.html"); 
} 
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
                                              <div class="col-md-6">
                                                <div class="alert alert-info mb-0"><b>Total Purchase:</b> <span class="gld-total-purchase">--</span></div>
                                              </div>
                                              <div class="col-md-6">
                                                <div class="alert alert-primary mb-0"><b>Total Sale:</b> <span class="gld-total-sale">--</span></div>
                                              </div>
                                                <div class="col-md-6">
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
                                                <div class="col-md-6">
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
                                        <div class="col-md-12 col-xl-12">
                                            <div class="card mb-3 border-success shadow-sm">
                                                <div class="card-body p-4">
                                                    <h5 class="card-title font-weight-bold mb-4 text-dark text-center">GLD Profit Analyser Sheet Breakup</h5>
                                                    <div class="table-responsive" style="height: 500px; overflow-y: auto;">
                                                        <table class="table table-bordered table-hover">
                                                            <thead class="thead-dark">
                                                                <tr>
                                                                    <th>Material Desc</th>
                                                                    <th>Material Purchased Cost with Tax</th>
                                                                    <th>Sale With Tax</th>
                                                                    <th>Balance Quantity</th>
                                                                    <th>Profit Margin (₹)</th>
                                                                    <th>Total Profit Margin (₹)</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php                                                                
                                                                    include_once("../MCU/db.php");
                                                                    $query=mysqli_query($con, "SELECT stc_purchase_product_adhoc_id, stc_purchase_product_adhoc_itemdesc, stc_purchase_product_adhoc_qty, stc_purchase_product_adhoc_rate, stc_product_sale_percentage FROM stc_purchase_product_adhoc LEFT JOIN `stc_product` ON `stc_product_id`=`stc_purchase_product_adhoc_productid` WHERE stc_purchase_product_adhoc_status=1 ORDER BY stc_purchase_product_adhoc_itemdesc ASC");
                                                                    $purchaseamount=0;
                                                                    $totalcount=0;
                                                                    $totalpurchaseamount=0;
                                                                    $totalsoldamount=0;
                                                                    $totalprofitmargin=0;
                                                                    $gtotalprofitmargin=0;
                                                                    foreach($query as $row){
                                                                        $totalcount++;
                                                                        $purchaseamount = $row['stc_purchase_product_adhoc_qty'] * $row['stc_purchase_product_adhoc_rate'];
                                                                        $qty= $row['stc_purchase_product_adhoc_qty'];
                                                                        $query=mysqli_query($con, "SELECT sum(`stc_cust_super_requisition_list_items_rec_recqty`) as qty FROM `stc_cust_super_requisition_list_items_rec` WHERE stc_cust_super_requisition_list_items_rec_list_poaid='".$row['stc_purchase_product_adhoc_id']."'");
                                                                        $result=mysqli_fetch_array($query);
                                                                        $soldqty = $result['qty'];

                                                                        $salerate= $row['stc_purchase_product_adhoc_rate'] + ($row['stc_purchase_product_adhoc_rate'] * $row['stc_product_sale_percentage'] / 100);
                                                                        $soldamount = $soldqty * $salerate;

                                                                        $query=mysqli_query($con, "SELECT SUM(`qty`) as qty, avg(rate) as rate  FROM `gld_challan` WHERE adhoc_id='".$row['stc_purchase_product_adhoc_id']."' GROUP BY adhoc_id");
                                                                        $soldgldqty = 0;
                                                                        $soldgldrate = 0;
                                                                        if(mysqli_num_rows($query)>0){
                                                                            $result=mysqli_fetch_array($query);
                                                                            $soldgldqty = $result['qty'];
                                                                            $soldgldrate = $result['rate'];
                                                                            $soldamount += $soldgldqty * $soldgldrate;

                                                                        }

                                                                        $qty = $qty - $soldqty - $soldgldqty;

                                                                        $purchasedqtyamount = ($soldqty + $soldgldqty) * $row['stc_purchase_product_adhoc_rate'];
                                                                        $profitmargin = $purchasedqtyamount - $soldamount;
                                                                        $totalprofitmargin = $profitmargin * $qty;
                                                                        if($purchaseamount>0 && $soldamount>0){
                                                                            $totalpurchaseamount += $purchaseamount;
                                                                            $totalsoldamount += $soldamount;
                                                                            $gtotalprofitmargin += $profitmargin;
                                                                            echo '
                                                                                <tr>
                                                                                    <td>'.$row['stc_purchase_product_adhoc_itemdesc'].'</td>
                                                                                    <td class="text-right">'.number_format($purchaseamount, 2).'</td>
                                                                                    <td class="text-right">'.number_format($soldamount, 2).'</td>
                                                                                    <td class="text-right">'.number_format($qty, 2).'</td>
                                                                                    <td class="text-right">'.number_format($profitmargin, 2).'</td>
                                                                                    <td class="text-right">'.number_format($totalprofitmargin, 2).'</td>
                                                                                </tr>
                                                                            ';
                                                                        }
                                                                    }
                                                                            echo '
                                                                                <tr>
                                                                                    <td>'.$totalcount.'</td>
                                                                                    <td class="text-right">'.number_format($totalpurchaseamount, 2).'</td>
                                                                                    <td class="text-right">'.number_format($totalsoldamount, 2).'</td>
                                                                                    <td class="text-right"></td>
                                                                                    <td class="text-right">'.number_format($gtotalprofitmargin, 2).'</td>
                                                                                    <td class="text-right">'.number_format($totalprofitmargin, 2).'</td>
                                                                                </tr>
                                                                            ';
                                                                ?>
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
