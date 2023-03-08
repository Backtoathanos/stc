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
                <div class="col-sm-12 col-md-12 col-lg-8 col-xl-8 tm-block-col">
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

                <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4 tm-block-col">
                    <div class="tm-bg-primary-dark tm-block animated fadeInRight">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3>Bar Series</h3>
                            </div>
                            <div class="panel-body">
                                <div id="chart2"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4 tm-block-col">
                    <div class="tm-bg-primary-dark tm-block animated fadeInLeft">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3>Bar Series</h3>
                            </div>
                            <div class="panel-body">
                                <div id="chart2"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-12 col-lg-8 col-xl-8 tm-block-col">
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

                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6 tm-block-col">
                    <div class="tm-bg-primary-dark tm-block tm-block-taller">
                        <h2 class="tm-block-title">Code Information</h2>
                        <div id="pieChartContainer">
                            <canvas id="pieChart" class="chartjs-render-monitor" width="200" height="200"></canvas>
                        </div>                        
                    </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6 tm-block-col">
                    <div class="tm-bg-primary-dark tm-block tm-block-taller tm-block-overflow">
                        <h2 class="tm-block-title">Notification List</h2>
                        <div class="tm-notification-items">
                            <div class="media tm-notification-item">
                                <div class="tm-gray-circle"><img src="img/notification-01.jpg" alt="Avatar Image" class="rounded-circle"></div>
                                <div class="media-body">
                                    <p class="mb-2"><b>Jessica</b> and <b>6 others</b> sent you new <a href="#"
                                            class="tm-notification-link">product updates</a>. Check new orders.</p>
                                    <span class="tm-small tm-text-color-secondary">6h ago.</span>
                                </div>
                            </div>
                            <div class="media tm-notification-item">
                                <div class="tm-gray-circle"><img src="img/notification-02.jpg" alt="Avatar Image" class="rounded-circle"></div>
                                <div class="media-body">
                                    <p class="mb-2"><b>Oliver Too</b> and <b>6 others</b> sent you existing <a href="#"
                                            class="tm-notification-link">product updates</a>. Read more reports.</p>
                                    <span class="tm-small tm-text-color-secondary">6h ago.</span>
                                </div>
                            </div>
                            <div class="media tm-notification-item">
                                <div class="tm-gray-circle"><img src="img/notification-03.jpg" alt="Avatar Image" class="rounded-circle"></div>
                                <div class="media-body">
                                    <p class="mb-2"><b>Victoria</b> and <b>6 others</b> sent you <a href="#"
                                            class="tm-notification-link">order updates</a>. Read order information.</p>
                                    <span class="tm-small tm-text-color-secondary">6h ago.</span>
                                </div>
                            </div>
                            <div class="media tm-notification-item">
                                <div class="tm-gray-circle"><img src="img/notification-01.jpg" alt="Avatar Image" class="rounded-circle"></div>
                                <div class="media-body">
                                    <p class="mb-2"><b>Laura Cute</b> and <b>6 others</b> sent you <a href="#"
                                            class="tm-notification-link">product records</a>.</p>
                                    <span class="tm-small tm-text-color-secondary">6h ago.</span>
                                </div>
                            </div>
                            <div class="media tm-notification-item">
                                <div class="tm-gray-circle"><img src="img/notification-02.jpg" alt="Avatar Image" class="rounded-circle"></div>
                                <div class="media-body">
                                    <p class="mb-2"><b>Samantha</b> and <b>6 others</b> sent you <a href="#"
                                            class="tm-notification-link">order stuffs</a>.</p>
                                    <span class="tm-small tm-text-color-secondary">6h ago.</span>
                                </div>
                            </div>
                            <div class="media tm-notification-item">
                                <div class="tm-gray-circle"><img src="img/notification-03.jpg" alt="Avatar Image" class="rounded-circle"></div>
                                <div class="media-body">
                                    <p class="mb-2"><b>Sophie</b> and <b>6 others</b> sent you <a href="#"
                                            class="tm-notification-link">product updates</a>.</p>
                                    <span class="tm-small tm-text-color-secondary">6h ago.</span>
                                </div>
                            </div>
                            <div class="media tm-notification-item">
                                <div class="tm-gray-circle"><img src="img/notification-01.jpg" alt="Avatar Image" class="rounded-circle"></div>
                                <div class="media-body">
                                    <p class="mb-2"><b>Lily A</b> and <b>6 others</b> sent you <a href="#"
                                            class="tm-notification-link">product updates</a>.</p>
                                    <span class="tm-small tm-text-color-secondary">6h ago.</span>
                                </div>
                            </div>
                            <div class="media tm-notification-item">
                                <div class="tm-gray-circle"><img src="img/notification-02.jpg" alt="Avatar Image" class="rounded-circle"></div>
                                <div class="media-body">
                                    <p class="mb-2"><b>Amara</b> and <b>6 others</b> sent you <a href="#"
                                            class="tm-notification-link">product updates</a>.</p>
                                    <span class="tm-small tm-text-color-secondary">6h ago.</span>
                                </div>
                            </div>
                            <div class="media tm-notification-item">
                                <div class="tm-gray-circle"><img src="img/notification-03.jpg" alt="Avatar Image" class="rounded-circle"></div>
                                <div class="media-body">
                                    <p class="mb-2"><b>Cinthela</b> and <b>6 others</b> sent you <a href="#"
                                            class="tm-notification-link">product updates</a>.</p>
                                    <span class="tm-small tm-text-color-secondary">6h ago.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 tm-block-col">
                    <div class="tm-bg-primary-dark tm-block tm-block-taller tm-block-scroll">
                        <h2 class="tm-block-title">Orders List</h2>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">ORDER NO.</th>
                                    <th scope="col">STATUS</th>
                                    <th scope="col">OPERATORS</th>
                                    <th scope="col">LOCATION</th>
                                    <th scope="col">DISTANCE</th>
                                    <th scope="col">START DATE</th>
                                    <th scope="col">EST DELIVERY DUE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th scope="row"><b>#122349</b></th>
                                    <td>
                                        <div class="tm-status-circle moving">
                                        </div>Moving
                                    </td>
                                    <td><b>Oliver Trag</b></td>
                                    <td><b>London, UK</b></td>
                                    <td><b>485 km</b></td>
                                    <td>16:00, 12 NOV 2018</td>
                                    <td>08:00, 18 NOV 2018</td>
                                </tr>
                                <tr>
                                    <th scope="row"><b>#122348</b></th>
                                    <td>
                                        <div class="tm-status-circle pending">
                                        </div>Pending
                                    </td>
                                    <td><b>Jacob Miller</b></td>
                                    <td><b>London, UK</b></td>
                                    <td><b>360 km</b></td>
                                    <td>11:00, 10 NOV 2018</td>
                                    <td>04:00, 14 NOV 2018</td>
                                </tr>
                                <tr>
                                    <th scope="row"><b>#122347</b></th>
                                    <td>
                                        <div class="tm-status-circle cancelled">
                                        </div>Cancelled
                                    </td>
                                    <td><b>George Wilson</b></td>
                                    <td><b>London, UK</b></td>
                                    <td><b>340 km</b></td>
                                    <td>12:00, 22 NOV 2018</td>
                                    <td>06:00, 28 NOV 2018</td>
                                </tr>
                                <tr>
                                    <th scope="row"><b>#122346</b></th>
                                    <td>
                                        <div class="tm-status-circle moving">
                                        </div>Moving
                                    </td>
                                    <td><b>William Aung</b></td>
                                    <td><b>London, UK</b></td>
                                    <td><b>218 km</b></td>
                                    <td>15:00, 10 NOV 2018</td>
                                    <td>09:00, 14 NOV 2018</td>
                                </tr>
                                <tr>
                                    <th scope="row"><b>#122345</b></th>
                                    <td>
                                        <div class="tm-status-circle pending">
                                        </div>Pending
                                    </td>
                                    <td><b>Harry Ryan</b></td>
                                    <td><b>London, UK</b></td>
                                    <td><b>280 km</b></td>
                                    <td>15:00, 11 NOV 2018</td>
                                    <td>09:00, 17 NOV 2018</td>
                                </tr>
                                <tr>
                                    <th scope="row"><b>#122344</b></th>
                                    <td>
                                        <div class="tm-status-circle pending">
                                        </div>Pending
                                    </td>
                                    <td><b>Michael Jones</b></td>
                                    <td><b>London, UK</b></td>
                                    <td><b>218 km</b></td>
                                    <td>18:00, 12 OCT 2018</td>
                                    <td>06:00, 18 OCT 2018</td>
                                </tr>
                                <tr>
                                    <th scope="row"><b>#122343</b></th>
                                    <td>
                                        <div class="tm-status-circle moving">
                                        </div>Moving
                                    </td>
                                    <td><b>Timmy Davis</b></td>
                                    <td><b>London, UK</b></td>
                                    <td><b>218 km</b></td>
                                    <td>12:00, 10 OCT 2018</td>
                                    <td>08:00, 18 OCT 2018</td>
                                </tr>
                                <tr>
                                    <th scope="row"><b>#122342</b></th>
                                    <td>
                                        <div class="tm-status-circle moving">
                                        </div>Moving
                                    </td>
                                    <td><b>Oscar Phyo</b></td>
                                    <td><b>London, UK</b></td>
                                    <td><b>420 km</b></td>
                                    <td>15:30, 06 OCT 2018</td>
                                    <td>09:30, 16 OCT 2018</td>
                                </tr>
                                <tr>
                                    <th scope="row"><b>#122341</b></th>
                                    <td>
                                        <div class="tm-status-circle moving">
                                        </div>Moving
                                    </td>
                                    <td><b>Charlie Brown</b></td>
                                    <td><b>London, UK</b></td>
                                    <td><b>300 km</b></td>
                                    <td>11:00, 10 OCT 2018</td>
                                    <td>03:00, 14 OCT 2018</td>
                                </tr>
                                <tr>
                                    <th scope="row"><b>#122340</b></th>
                                    <td>
                                        <div class="tm-status-circle cancelled">
                                        </div>Cancelled
                                    </td>
                                    <td><b>Wilson Cookies</b></td>
                                    <td><b>London, UK</b></td>
                                    <td><b>218 km</b></td>
                                    <td>17:30, 12 OCT 2018</td>
                                    <td>08:30, 22 OCT 2018</td>
                                </tr>
                                <tr>
                                    <th scope="row"><b>#122339</b></th>
                                    <td>
                                        <div class="tm-status-circle moving">
                                        </div>Moving
                                    </td>
                                    <td><b>Richard Clamon</b></td>
                                    <td><b>London, UK</b></td>
                                    <td><b>150 km</b></td>
                                    <td>15:00, 12 OCT 2018</td>
                                    <td>09:20, 26 OCT 2018</td>
                                </tr>
                            </tbody>
                        </table>
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