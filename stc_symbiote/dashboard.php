<?php
ini_set("session.gc_maxlifetime", 21600);
session_set_cookie_params(21600);
session_start();
if(empty(@$_SESSION['stc_admin_info_id'])){
    header('location:login.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard - STC</title>
    <link rel = "icon" type = "image/png" href="img/stc_logo_title.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700">
    <!-- https://fonts.google.com/specimen/Roboto -->
    <link rel="stylesheet" href="css/fontawesome.min.css">
    <!-- https://fontawesome.com/ -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- dashbard card css -->
    <link rel="stylesheet" href="css/dashboard-design.css">
    <!-- https://getbootstrap.com/ -->
    <link rel="stylesheet" href="css/templatemo-style.css">    

    <link rel="stylesheet" href="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/infinite.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <!--
	Product Admin CSS Template
	https://templatemo.com/tm-524-product-admin
	-->
</head>

<body id="reportsPage">
    <div class="" id="home">
        <nav class="navbar navbar-expand-xl">
          <div class="container h-100">
            <a class="navbar-brand animated bounceInRight" href="dashboard.php">
              <img style="width: 50%;border-radius: 34px;" src="img/stc_logo.png">
            </a>
            <button class="navbar-toggler ml-auto mr-0" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-bars tm-nav-icon"></i>
            </button>
            <div class="collapse navbar-collapse animated bounceInDown" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto h-100">
                  <li class="nav-item">
                    <a class="nav-link active" href="dashboard.php">
                      <i class="fas fa-tachometer-alt"></i>
                      Dashboard  
                      <span class="sr-only">(current)</span>            
                    </a>
                  </li>                        
                  <li class="nav-item">
                    <a class="nav-link" href="erp.php">
                      <i class="fas fa-cog"></i>
                        ERP 
                          
                    </a>
                  </li>                
                  <li class="nav-item">
                    <a class="nav-link" href="accounts.php">
                      <i class="far fa-user"></i>
                      Accounts
                    </a>
                  </li>
                  <li class="nav-item dropdown">
                  <a href="agent-order.php" class="nav-link dropdown-toggle">
                      <span style="float: left;position: absolute;top: 20px;left: 40px;font-size: 12px;background: #bb5656;padding: 5px;border-radius: 50%;" class="badge"></span>
                      <i class="far fa-bell"></i>
                    </a>
                  </li>
                </ul>
                <ul class="navbar-nav">
                  <li class="nav-item">
                    <a class="nav-link d-block" href="asgard/logout.php">
                      <?php echo @$_SESSION["stc_admin_info_name"];?>, <b>Logout</b>
                    </a>
                  </li>
                </ul>
            </div>
          </div>
        </nav>
        <div class="container">
            <div class="row">
                <div class="col">
                    <p class="text-white mt-5 mb-5">Welcome back, <b><?php echo $_SESSION["stc_admin_info_name"];?></b></p>
                </div>
            </div>

            <!-- row -->
            <div class="row tm-content-row animated bounceInLeft">
                <div class="col-sm-6 col-xl-3 tm-block-col">
                    <div class="card">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body">
                                    <h5 class="font-size-14 ribbon">Total Products -</h5>
                                </div>
                                <div class="avatar-xs">
                                    <span class="avatar-title rounded-circle bg-primary">
                                        <i class="dripicons-box"></i>
                                    </span>
                                </div>
                            </div>
                            <h4 align="right" class="m-0 align-self-center"><span class="toproducts"></span></h4>
                        </div>
                    </div>
                </div>
    
                <div class="col-sm-6 col-xl-3 tm-block-col">
                    <div class="card">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body">
                                    <h5 class="font-size-14 ribbon">In Inventory -</h5>
                                </div>
                                <div class="avatar-xs">
                                    <span class="avatar-title rounded-circle bg-primary">
                                        <i class="dripicons-briefcase"></i>
                                    </span>
                                </div>
                            </div>
                            <h4 align="right" class="m-0 align-self-center"><span class="toinventory"></span></h4>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3 tm-block-col">
                    <div class="card">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body">
                                    <h5 class="font-size-14 ribbon">Total Merchants -</h5>
                                </div>
                                <div class="avatar-xs">
                                    <span class="avatar-title rounded-circle bg-primary">
                                        <i class="dripicons-tags"></i>
                                    </span>
                                </div>
                            </div>
                            <h4 align="right" class="m-0 align-self-center">
                                <span class="tomerchants"></span>
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3 tm-block-col">
                    <div class="card">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body">
                                    <h5 class="font-size-14 ribbon">Total Customers -</h5>
                                </div>
                                <div class="avatar-xs">
                                    <span class="avatar-title rounded-circle bg-primary">
                                        <i class="dripicons-cart"></i>
                                    </span>
                                </div>
                            </div>
                            <h4 align="right" class="m-0 align-self-center"><span class="tocustomers"></span></h4>
                        </div>
                    </div>
                </div>    
            </div>

            <div class="row tm-content-row animated bounceInRight">
                <div class="col-sm-6 col-xl-3 tm-block-col">
                    <div class="card">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body">
                                    <h5 class="font-size-14 ribbon">Total Purchased -</h5>
                                </div>
                                <div class="avatar-xs">
                                    <span class="avatar-title rounded-circle bg-primary">
                                        <i class="dripicons-box"></i>
                                    </span>
                                </div>
                            </div>
                            <h4 align="right" class="m-0 align-self-center">
                                <i class="fa fa-rupee-sign"></i>
                                <span class="topurchased"></span>
                            </h4>
                        </div>
                    </div>
                </div>
    
                <div class="col-sm-6 col-xl-3 tm-block-col">
                    <div class="card">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body">
                                    <h5 class="font-size-14 ribbon">Total Sold -</h5>
                                </div>
                                <div class="avatar-xs">
                                    <span class="avatar-title rounded-circle bg-primary">
                                        <i class="dripicons-briefcase"></i>
                                    </span>
                                </div>
                            </div>
                            <h4 align="right" class="m-0 align-self-center">
                                <i class="fa fa-rupee-sign"></i>
                                <span class="tosoled"></span>
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3 tm-block-col">
                    <div class="card">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body">
                                    <h5 class="font-size-14 ribbon">Merchant's Paid -</h5>
                                </div>
                                <div class="avatar-xs">
                                    <span class="avatar-title rounded-circle bg-primary">
                                        <i class="dripicons-tags"></i>
                                    </span>
                                </div>
                            </div>
                            <h4 align="right" class="m-0 align-self-center">
                                <i class="fa fa-rupee-sign"></i>
                                <span class="tomerpaid"></span>
                            </h4>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3 tm-block-col">
                    <div class="card">
                        <div class="card-body">
                            <div class="media">
                                <div class="media-body">
                                    <h5 class="font-size-14 ribbon">Customer's Paid -</h5>
                                </div>
                                <div class="avatar-xs">
                                    <span class="avatar-title rounded-circle bg-primary">
                                        <i class="dripicons-cart"></i>
                                    </span>
                                </div>
                            </div>
                            <h4 align="right" class="m-0 align-self-center">
                                <i class="fa fa-rupee-sign"></i>
                                <span class="tocustpaid"></span>
                            </h4>
                        </div>
                    </div>
                </div>    
            </div>

            <div class="row tm-content-row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 tm-block-col">
                    <div class="tm-bg-primary-dark tm-block animated tada">
                        <!-- <canvas id="lineChart"></canvas> -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="tm-block-title">Purchased Bar</h3>
                            </div>
                            <div class="panel-body">
                                
                            <div id="purchased-container">FusionCharts will render here</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 tm-block-col">
                    <div class="tm-bg-primary-dark tm-block animated tada">
                        <!-- <canvas id="lineChart"></canvas> -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="tm-block-title">Sold Bar</h3>
                            </div>
                            <div class="panel-body">
                                
                            <div id="soled-container">FusionCharts will render here</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include "footer.php";?>
    
    <script src="stark_tower/js/jarvis-dashboard.js"></script>
    <script src="stark_tower/js/fusioncharts.js"></script>
    <script src="stark_tower/js/fusioncharts.charts.js"></script>
    <script src="stark_tower/js/themes/fusioncharts.theme.zune.js"></script>
</body>

</html>