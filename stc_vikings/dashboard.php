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
                                        <div class="col-md-3 col-xl-3">
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
                                        </div>
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
                                                    <!-- Month Selector Card -->
                                                    <div class="row mb-4">
                                                        <div class="col-md-12">
                                                            <div class="card border-0" style="background: linear-gradient(-20deg, #d4eeff 0%, #fdb8b5 100%);">
                                                                <div class="card-body py-3">
                                                                    <div class="d-flex align-items-center justify-content-between">
                                                                        <h5 class="mb-0 font-weight-bold text-dark">Select Month:</h5>
                                                                        <select class="form-control stc-dash-month w-50 border-0 shadow-sm" style="background: rgba(255,255,255,0.7);">
                                                                            <option value="1">January</option>
                                                                            <option value="2">February</option>
                                                                            <option value="3">March</option>
                                                                            <option value="4">April</option>
                                                                            <option value="5">May</option>
                                                                            <option value="6">June</option>
                                                                            <option value="7">July</option>
                                                                            <option value="8">August</option>
                                                                            <option value="9">September</option>
                                                                            <option value="10">October</option>
                                                                            <option value="11">November</option>
                                                                            <option value="12">December</option>
                                                                        </select>
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
                                                                        <small class="text-white-50">Updated monthly</small>
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
                                            </div>
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
                                }
                        //     echo '
                        // <div class="row">
                        //     <div class="col-md-12 col-xl-12">
                        //         <div class="card-border mb-3 card card-body border-success" style="font-size: 15px;">
                        //             <div class="row">
                        //                 <div class="col-md-12 col-xl-12">
                        //                     <div class="card mb-3 widget-content bg-midnight-bloom" style="background-image: linear-gradient(-20deg, #d4eeff 0%, #fdb8b5 100%) !important;">
                        //                         <div class="widget-heading">Month : </div>
                        //                         <div class="widget-subheading">
                        //                             <select class="form-control stc-dash-month">
                        //                                 <option value="1">January</option>
                        //                                 <option value="2">February</option>
                        //                                 <option value="3">March</option>
                        //                                 <option value="4">April</option>
                        //                                 <option value="5">May</option>
                        //                                 <option value="6">June</option>
                        //                                 <option value="7">July</option>
                        //                                 <option value="8">August</option>
                        //                                 <option value="9">September</option>
                        //                                 <option value="10">October</option>
                        //                                 <option value="11">November</option>
                        //                                 <option value="12">December</option>
                        //                             </select>
                        //                         </div>
                        //                     </div>
                        //                 </div>
                        //                 <div class="col-md-6 col-xl-6">
                        //                     <div class="card mb-3 widget-content bg-midnight-bloom">
                        //                         <div class="widget-content-wrapper text-white">
                        //                             <div class="widget-content-left">
                        //                                 <div class="widget-heading">STC Electronics Summary
                        //                                 </div>
                        //                                 <div class="widget-subheading">Total Purchase &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        //                                     <i class="fa fa-rupee-sign"></i>
                        //                                     <span class="toelecpurchase">
                        //                                         <div class="loader"></div>
                        //                                     </span>
                        //                                 </div>
                        //                                 <div class="widget-subheading"> Total Sale &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        //                                     <i class="fa fa-rupee-sign"></i>
                        //                                     <span class="toelecsale">
                        //                                         <div class="loader"></div>
                        //                                     </span>
                        //                                 </div>
                        //                                 <div class="widget-subheading"> Total Expenses &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        //                                     <i class="fa fa-rupee-sign"></i>
                        //                                     <span class="toelecexpenses">
                        //                                         <div class="loader"></div>
                        //                                     </span>
                        //                                 </div>
                        //                                 <div class="widget-subheading">Party Paid &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        //                                     <i class="fa fa-rupee-sign"></i>
                        //                                     <span class="toeleccpaid">
                        //                                         <div class="loader"></div>
                        //                                     </span>
                        //                                 </div>
                        //                                 <div class="widget-subheading">Party Dues &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        //                                     <i class="fa fa-rupee-sign"></i>
                        //                                     <span class="toelecsdues">
                        //                                         <div class="loader"></div>
                        //                                     </span>
                        //                                 </div>
                        //                             </div>
                        //                         </div>
                        //                     </div>
                        //                 </div>
                        //                 <div class="col-md-6 col-xl-6">
                        //                     <div class="card mb-3 widget-content bg-midnight-bloom">
                        //                         <div class="widget-content-wrapper text-white">
                        //                             <div class="widget-content-left">
                        //                                 <div class="widget-heading">STC Electronics Summary</div>
                        //                                 <div class="widget-subheading">Purchase Paid &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        //                                     <i class="fa fa-rupee-sign"></i>
                        //                                     <span class="toelecmpaid">
                        //                                         <div class="loader"></div>
                        //                                     </span>
                        //                                 </div>
                        //                                 <div class="widget-subheading">Purchase Dues &nbsp; &nbsp;&nbsp; &nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        //                                     <i class="fa fa-rupee-sign"></i>
                        //                                     <span class="toelecmdues">
                        //                                         <div class="loader"></div>
                        //                                     </span>
                        //                                 </div>
                        //                             </div>
                        //                         </div>
                        //                     </div>
                        //                 </div>
                        //                 <div class="col-md-6 col-xl-6">
                        //                     <div class="card mb-3 widget-content bg-mixed-hopes">
                        //                         <div class="widget-content-wrapper text-white">
                        //                             <div class="widget-content-left">
                        //                                 <div class="widget-heading">STC Trading Summary</div>
                        //                                 <div class="widget-subheading">Total Purchase &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        //                                     <i class="fa fa-rupee-sign"></i>
                        //                                     <span class="totrapurchase">
                        //                                         <div class="loader"></div>
                        //                                     </span>
                        //                                 </div>
                        //                                 <div class="widget-subheading"> Total Sale &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        //                                     <i class="fa fa-rupee-sign"></i>
                        //                                     <span class="totrasale">
                        //                                         <div class="loader"></div>
                        //                                     </span>
                        //                                 </div>
                        //                                 <div class="widget-subheading"> Total Expenses &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        //                                     <i class="fa fa-rupee-sign"></i>
                        //                                     <span class="totraexpenses">
                        //                                         <div class="loader"></div>
                        //                                     </span>
                        //                                 </div>
                        //                                 <div class="widget-subheading">Party Paid &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        //                                     <i class="fa fa-rupee-sign"></i>
                        //                                     <span class="totracpaid">
                        //                                         <div class="loader"></div>
                        //                                     </span>
                        //                                 </div>
                        //                                 <div class="widget-subheading">Party Dues &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        //                                     <i class="fa fa-rupee-sign"></i>
                        //                                     <span class="totrasdues">
                        //                                         <div class="loader"></div>
                        //                                     </span>
                        //                                 </div>
                        //                             </div>
                        //                         </div>
                        //                     </div>
                        //                 </div>
                        //                 <div class="col-md-6 col-xl-6">
                        //                     <div class="card mb-3 widget-content bg-mixed-hopes">
                        //                         <div class="widget-content-wrapper text-white">
                        //                             <div class="widget-content-left">
                        //                                 <div class="widget-heading">STC Trading Summary</div>
                        //                                 <div class="widget-subheading">Purchase Paid &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        //                                     <i class="fa fa-rupee-sign"></i>
                        //                                     <span class="totrampaid">
                        //                                         <div class="loader"></div>
                        //                                     </span>
                        //                                 </div>
                        //                                 <div class="widget-subheading">Purchase Dues &nbsp; &nbsp;&nbsp; &nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        //                                     <i class="fa fa-rupee-sign"></i>
                        //                                     <span class="totramdues">
                        //                                         <div class="loader"></div>
                        //                                     </span>
                        //                                 </div>
                        //                             </div>
                        //                         </div>
                        //                     </div>
                        //                 </div>
                        //                 <div class="col-md-6 col-xl-6">
                        //                     <div class="card mb-3 widget-content bg-midnight-bloom">
                        //                         <div class="widget-content-wrapper text-white">
                        //                             <div class="widget-content-left">
                        //                                 <div class="widget-heading">STC Groceries Summary</div>
                        //                                 <div class="widget-subheading">Total Purchase &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        //                                     <i class="fa fa-rupee-sign"></i>
                        //                                     <span class="togrospurchase">
                        //                                         <div class="loader"></div>
                        //                                     </span>
                        //                                 </div>
                        //                                 <div class="widget-subheading"> Total Sale &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        //                                     <i class="fa fa-rupee-sign"></i>
                        //                                     <span class="togrossale">
                        //                                         <div class="loader"></div>
                        //                                     </span>
                        //                                 </div>
                        //                                 <div class="widget-subheading"> Total Expenses &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        //                                     <i class="fa fa-rupee-sign"></i>
                        //                                     <span class="togrosexpenses">
                        //                                         <div class="loader"></div>
                        //                                     </span>
                        //                                 </div>
                        //                                 <div class="widget-subheading">Party Paid &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        //                                     <i class="fa fa-rupee-sign"></i>
                        //                                     <span class="togroscpaid">
                        //                                         <div class="loader"></div>
                        //                                     </span>
                        //                                 </div>
                        //                                 <div class="widget-subheading">Party Dues &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        //                                     <i class="fa fa-rupee-sign"></i>
                        //                                     <span class="togrossdues">
                        //                                         <div class="loader"></div>
                        //                                     </span>
                        //                                 </div>
                        //                             </div>
                        //                         </div>
                        //                     </div>
                        //                 </div>
                        //                 <div class="col-md-6 col-xl-6">
                        //                     <div class="card mb-3 widget-content bg-midnight-bloom">
                        //                         <div class="widget-content-wrapper text-white">
                        //                             <div class="widget-content-left">
                        //                                 <div class="widget-heading">STC Groceries Summary</div>
                        //                                 <div class="widget-subheading">Purchase Paid &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        //                                     <i class="fa fa-rupee-sign"></i>
                        //                                     <span class="togrosmpaid">
                        //                                         <div class="loader"></div>
                        //                                     </span>
                        //                                 </div>
                        //                                 <div class="widget-subheading">Purchase Dues &nbsp; &nbsp;&nbsp; &nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        //                                     <i class="fa fa-rupee-sign"></i>
                        //                                     <span class="togrosmdues">
                        //                                         <div class="loader"></div>
                        //                                     </span>
                        //                                 </div>
                        //                             </div>
                        //                         </div>
                        //                     </div>
                        //                 </div>
                        //             </div>
                        //         </div>
                        //     </div>
                        // </div>
                        //     ';
                        //         }
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
            $('body').delegate('.stc-dash-month', 'change', function() {
                month=$(this).val();
                stc_dashboard_reload(month);
            });

            stc_dashboard_reload(month);
            function stc_dashboard_reload(month){
                $.ajax({
                    url         : "kattegat/ragnar_lothbrok.php",
                    method      : "post",
                    data        : {dashboard:1, month:month},
                    dataType    : 'JSON',
                    success     : function(data){
                        // console.log(data);
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
                        var gldRows = '';
                        if(Array.isArray(gld.locations) && gld.locations.length > 0) {
                          $.each(gld.locations, function(i, item) {
                            gldRows += '<tr>' +
                              '<td>' + item.location + '</td>' +
                              '<td><span class="badge badge-pill badge-info" style="font-size:14px;">₹ ' + parseFloat(item.amount).toLocaleString('en-IN', {minimumFractionDigits:2}) + '</span></td>' +
                              '</tr>';
                          });
                        } else {
                          gldRows = '<tr><td colspan="2" class="text-center text-muted">No data found for this period.</td></tr>';
                        }
                        $('#gld-summary-table tbody').html(gldRows);
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
            $('body').on('change', '.stc-dash-month', function() {
              month = $(this).val();
              fetchInventorySummary(month, d.getFullYear());
            });
        });
    </script>
</body>
</html>
