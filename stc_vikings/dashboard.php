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
                                <div class="card-border mb-3 card card-body border-success" style="font-size: 15px;">
                                    <div class="row">
                                        <div class="col-md-6 col-xl-6">
                                            <div class="card mb-3 widget-content bg-midnight-bloom">
                                                <div class="widget-content-wrapper text-white">
                                                    <div class="widget-content-left">
                                                        <div class="widget-heading">STC Electronics Purchase Summary</div>
                                                        <div class="widget-subheading">Total Purchase &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <i class="fa fa-rupee-sign"></i>
                                                            <span class="toelecpurchase">
                                                                <div class="loader"></div>
                                                            </span>
                                                        </div>
                                                        <div class="widget-subheading">Purchase Paid &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <i class="fa fa-rupee-sign"></i>
                                                            <span class="toelecmpaid">
                                                                <div class="loader"></div>
                                                            </span>
                                                        </div>
                                                        <div class="widget-subheading">Purchase Dues &nbsp; &nbsp;&nbsp; &nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <i class="fa fa-rupee-sign"></i>
                                                            <span class="toelecmdues">
                                                                <div class="loader"></div>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xl-6">
                                            <div class="card mb-3 widget-content bg-midnight-bloom">
                                                <div class="widget-content-wrapper text-white">
                                                    <div class="widget-content-left">
                                                        <div class="widget-heading">STC Electronics Sale Summary</div>
                                                        <div class="widget-subheading"> Total Sold &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <i class="fa fa-rupee-sign"></i>
                                                            <span class="toelecsale">
                                                                <div class="loader"></div>
                                                            </span>
                                                        </div>
                                                        <div class="widget-subheading">Customer Paid &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <i class="fa fa-rupee-sign"></i>
                                                            <span class="toeleccpaid">
                                                                <div class="loader"></div>
                                                            </span>
                                                        </div>
                                                        <div class="widget-subheading">Sale Dues &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <i class="fa fa-rupee-sign"></i>
                                                            <span class="toelecsdues">
                                                                <div class="loader"></div>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xl-12">
                                            <div class="card mb-3 widget-content bg-midnight-bloom">
                                                <div class="widget-content-wrapper text-white">
                                                    <div class="widget-content-left">
                                                        <div class="widget-heading">STC Electronics Expenses</div>
                                                        <i class="fa fa-rupee-sign"></i>
                                                        <span class="toelecexpenses">
                                                            <div class="loader"></div>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xl-6">
                                            <div class="card mb-3 widget-content bg-mixed-hopes">
                                                <div class="widget-content-wrapper text-white">
                                                    <div class="widget-content-left">
                                                        <div class="widget-heading">STC Trading Purchase Summary</div>
                                                        <div class="widget-subheading">Total Purchase &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <i class="fa fa-rupee-sign"></i>
                                                            <span class="totrapurchase">
                                                                <div class="loader"></div>
                                                            </span>
                                                        </div>
                                                        <div class="widget-subheading">Purchase Paid &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <i class="fa fa-rupee-sign"></i>
                                                            <span class="totrampaid">
                                                                <div class="loader"></div>
                                                            </span>
                                                        </div>
                                                        <div class="widget-subheading">Purchase Dues &nbsp; &nbsp;&nbsp; &nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <i class="fa fa-rupee-sign"></i>
                                                            <span class="totramdues">
                                                                <div class="loader"></div>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xl-6">
                                            <div class="card mb-3 widget-content bg-mixed-hopes">
                                                <div class="widget-content-wrapper text-white">
                                                    <div class="widget-content-left">
                                                        <div class="widget-heading">STC Trading Sale Summary</div>
                                                        <div class="widget-subheading"> Total Sold &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <i class="fa fa-rupee-sign"></i>
                                                            <span class="totrasale">
                                                                <div class="loader"></div>
                                                            </span>
                                                        </div>
                                                        <div class="widget-subheading">Customer Paid &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <i class="fa fa-rupee-sign"></i>
                                                            <span class="totracpaid">
                                                                <div class="loader"></div>
                                                            </span>
                                                        </div>
                                                        <div class="widget-subheading">Sale Dues &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <i class="fa fa-rupee-sign"></i>
                                                            <span class="totrasdues">
                                                                <div class="loader"></div>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xl-12">
                                            <div class="card mb-3 widget-content bg-mixed-hopes">
                                                <div class="widget-content-wrapper text-white">
                                                    <div class="widget-content-left">
                                                        <div class="widget-heading">STC Trading Expenses</div>
                                                        <i class="fa fa-rupee-sign"></i>
                                                        <span class="totraexpenses">
                                                            <div class="loader"></div>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xl-6">
                                            <div class="card mb-3 widget-content bg-midnight-bloom">
                                                <div class="widget-content-wrapper text-white">
                                                    <div class="widget-content-left">
                                                        <div class="widget-heading">STC Groceries Purchase Summary</div>
                                                        <div class="widget-subheading">Total Purchase &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <i class="fa fa-rupee-sign"></i>
                                                            <span class="togrospurchase">
                                                                <div class="loader"></div>
                                                            </span>
                                                        </div>
                                                        <div class="widget-subheading">Purchase Paid &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <i class="fa fa-rupee-sign"></i>
                                                            <span class="togrosmpaid">
                                                                <div class="loader"></div>
                                                            </span>
                                                        </div>
                                                        <div class="widget-subheading">Purchase Dues &nbsp; &nbsp;&nbsp; &nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <i class="fa fa-rupee-sign"></i>
                                                            <span class="togrosmdues">
                                                                <div class="loader"></div>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-xl-6">
                                            <div class="card mb-3 widget-content bg-midnight-bloom">
                                                <div class="widget-content-wrapper text-white">
                                                    <div class="widget-content-left">
                                                        <div class="widget-heading">STC Groceries Sale Summary</div>
                                                        <div class="widget-subheading"> Total Sold &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <i class="fa fa-rupee-sign"></i>
                                                            <span class="togrossale">
                                                                <div class="loader"></div>
                                                            </span>
                                                        </div>
                                                        <div class="widget-subheading">Customer Paid &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <i class="fa fa-rupee-sign"></i>
                                                            <span class="togroscpaid">
                                                                <div class="loader"></div>
                                                            </span>
                                                        </div>
                                                        <div class="widget-subheading">Sale Dues &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            <i class="fa fa-rupee-sign"></i>
                                                            <span class="togrossdues">
                                                                <div class="loader"></div>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-xl-12">
                                            <div class="card mb-3 widget-content bg-midnight-bloom">
                                                <div class="widget-content-wrapper text-white">
                                                    <div class="widget-content-left">
                                                        <div class="widget-heading">STC Groceries Expenses</div>
                                                        <i class="fa fa-rupee-sign"></i>
                                                        <span class="togrosexpenses">
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
                            ';
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
            
            stc_dashboard_reload();
            function stc_dashboard_reload(){
                $.ajax({
                    url         : "kattegat/ragnar_lothbrok.php",
                    method      : "post",
                    data        : {dashboard:1},
                    dataType    : 'JSON',
                    success     : function(data){
                        console.log(data);
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
                        // $('.toelecmpaid').html(data[8]);
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
        });
    </script>
</body>
</html>
