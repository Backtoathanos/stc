<?php
ini_set("session.gc_maxlifetime", 21600);
session_set_cookie_params(21600);
session_start();
if(empty(@$_SESSION['stc_electro_user_id'])){
    header('location:../index.html');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="icon" type="image/png" href="../assets/img/stc_logo_title.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    STC Electronics || Dashboard
  </title>
  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <!-- CSS Files -->
  <link href="../assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="../assets/demo/demo.css" rel="stylesheet" />
</head>

<body class="">
  <div class="wrapper ">
    <div class="sidebar" data-color="purple" data-background-color="white" data-image="../assets/img/sidebar-1.jpg">
      <div class="logo"><a href="#" class="simple-text logo-normal">
          STC Electronics
        </a></div>
      <div class="sidebar-wrapper">
        <ul class="nav">
          <li class="nav-item active  ">
            <a class="nav-link" href="./dashboard.php">
              <i class="material-icons">dashboard</i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="./inventory.php">
              <i class="material-icons">content_paste</i>
              <p>Inventory</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="./order-request.php">
              <i class="fa fa-sticky-note-o" aria-hidden="true"></i>
              <p>Request By</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="./order.php">
              <i class="material-icons">store</i>
              <p>Create/ Show Order</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="./product-links.php">
              <i class="material-icons">link</i>
              <p>Product Links</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="./purchase-sale.php">
              <i class="material-icons">shop</i>
              <p>Daily Purchase/Sale</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="./return-order.php">
              <i class="fa fa-undo" aria-hidden="true"></i>
              <p>Return Invoice/Challan</p>
            </a>
          </li>
            <li class="nav-item">
              <a class="nav-link" href="./daily-expenses.php">
                <i class="fa fa-money" aria-hidden="true"></i>
                <p>Daily Expenses</p>
              </a>
            </li>
        </ul>
      </div>
    </div>
    <div class="main-panel">
      <!-- Navbar -->
      <?php include_once("bar/navbar.php");?>
      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-warning card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">content_copy</i>
                  </div>
                  <p class="card-category">Total Customer</p>
                  <h3 class="card-title">
                    <?php 
                      include_once("../../MCU/db.php");
                      $date = date("d-m-Y");
                      $newDate = date('Y-m-d', strtotime($date)); 
                      $effectiveDate = date('Y-m-d', strtotime("-1 months", strtotime($date)));
                      $newyear=date('Y', strtotime($newDate)); 

                      // ---------------------------------count contact---------------------
                      $count_cust=mysqli_query($con, "
                        SELECT DISTINCT `stc_sale_product_silent_challan_contact_no` FROM `stc_sale_product_silent_challan`
                      ");
                      $count_customer=mysqli_num_rows($count_cust);

                      //----------------------------------calculate total over month--------
                      $calculate_total=mysqli_query($con, "
                        SELECT
                            `stc_sale_product_silent_challan_items_product_qty`,
                            `stc_sale_product_silent_challan_items_product_sale_rate`,
                            `stc_product_gst`
                        FROM
                            `stc_sale_product_silent_challan_items`
                        INNER JOIN 
                            `stc_product` 
                        ON 
                            `stc_product_id` = `stc_sale_product_silent_challan_items_product_id`
                        INNER JOIN 
                            `stc_sale_product_silent_challan` 
                        ON 
                            `stc_sale_product_silent_challan_items_order_id` = `stc_sale_product_silent_challan_id`
                        WHERE 
                            DATE(`stc_sale_product_silent_challan_date`) 
                            BETWEEN '".mysqli_real_escape_string($con, $effectiveDate)."' AND '".mysqli_real_escape_string($con, $newDate)."' 
                      ");
                      $total_amountovmonth=0;
                      foreach($calculate_total as$cal_row){
                        $amount=$cal_row['stc_sale_product_silent_challan_items_product_qty'] * $cal_row['stc_sale_product_silent_challan_items_product_sale_rate'];
                        $gst=$amount * $cal_row['stc_product_gst']/100;
                        $total_amountovmonth+=$amount + $gst;
                      }
                      echo number_format($count_customer);

                    ?>
                    <small></small>
                  </h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">local_offer</i> Carry On 
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-success card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">store</i>
                  </div>
                  <p class="card-category">Revenue</p>
                  <p class="card-title">₹ <?php echo number_format($total_amountovmonth, 2);?></p>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">date_range</i> Last 1 Month
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-danger card-header-icon">
                  <div class="card-icon">
                    <i class="fa fa-user-secret" aria-hidden="true"></i>
                  </div>
                  <p class="card-category">Total Agent</p>
                  <h3 class="card-title">
                    <?php
                      $agent_countqry ="
                        SELECT DISTINCT 
                          `stc_sale_product_silent_challan_order_by` 
                        FROM 
                          `stc_sale_product_silent_challan` 
                      ";
                      $agent_countresult = mysqli_query($con ,$agent_countqry);
                      echo $count_agent=mysqli_num_rows($agent_countresult);
                    ?>
                  </h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">local_offer</i> Tracked from STC Electronics
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-header card-header-info card-header-icon">
                  <div class="card-icon">
                    <i class="fa fa-instagram"></i>
                  </div>
                  <p class="card-category">Followers</p>
                  <h3 class="card-title">+
                    <?php
                      $response = @file_get_contents( "https://www.instagram.com/stc_electronics/?__a=1" );

                      if ( $response !== false ) {
                        $data = json_decode( $response, true );
                        if ( $data !== null ) {
                          $full_name = $data['graphql']['user']['full_name'];
                          $follower  = $data['graphql']['user']['edge_followed_by']['count'];
                          echo "{$follower} followers.";
                        }
                      } else {
                        echo 'Username not found.';
                      }
                    ?>
                  </h3>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">update</i> Just Updated
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 col-sm-12">
              <div class="card card-chart">
                <div class="card-header">
                    <h2 class="text-center">Analytics Reports From <?php echo $newyear;?></h2>
                <?php
                    $agent_qry ="
                      SELECT DISTINCT 
                        `stc_sale_product_silent_challan_order_by` 
                      FROM 
                        `stc_sale_product_silent_challan` 
                      INNER JOIN 
                        `stc_sale_product_silent_invoice`
                      ON 
                        `stc_sale_product_silent_invoice_challan_id` = `stc_sale_product_silent_challan_id`
                      WHERE 
                        YEAR(`stc_sale_product_silent_invoice_date`) BETWEEN '".$newyear."' AND '".$newyear."' 
                      ORDER BY 
                        `stc_sale_product_silent_challan_order_by` 
                      ASC
                    ";
                    $agent_qryresult = mysqli_query($con ,$agent_qry);
                    $chart_data="";
                    foreach ($agent_qryresult as $agent_row) {    
                      $amount_qry=mysqli_query($con, "
                        SELECT 
                            `stc_sale_product_silent_challan_items_product_qty`, 
                            `stc_sale_product_silent_challan_items_product_sale_rate`, 
                            `stc_product_gst`
                        FROM
                            `stc_sale_product_silent_challan`
                        INNER JOIN 
                            `stc_sale_product_silent_invoice` ON `stc_sale_product_silent_invoice_challan_id` = `stc_sale_product_silent_challan_id`
                        INNER JOIN 
                            `stc_sale_product_silent_challan_items` ON `stc_sale_product_silent_challan_id` = `stc_sale_product_silent_challan_items_order_id`
                        INNER JOIN 
                            `stc_product` ON `stc_sale_product_silent_challan_items_product_id` = `stc_product_id`
                        WHERE
                            `stc_sale_product_silent_challan_order_by` = '".$agent_row['stc_sale_product_silent_challan_order_by']."'
                        AND 
                            YEAR(`stc_sale_product_silent_invoice_date`) BETWEEN '".$newyear."' AND '".$newyear."'
                      ");  
                      $total_amount=0;
                      foreach($amount_qry as $amount_row){
                        $amount=$amount_row['stc_sale_product_silent_challan_items_product_qty'] * $amount_row['stc_sale_product_silent_challan_items_product_sale_rate'];
                        $gst=$amount * $amount_row['stc_product_gst']/100;
                        $total_amount+=$amount + $gst;
                      }    
    
                      $agent_name[]  = $agent_row['stc_sale_product_silent_challan_order_by'];
                      $sales[] = $total_amount;
                    }

                ?>
                <div style="width:100%;hieght:20%;text-align:center">
                    <div>Sales </div>
                    <canvas  id="chartjs_bar"></canvas> 
                </div> 
                </div>
                <div class="card-body">
                  <h4 class="card-title">Daily Sales</h4>
                  <p class="card-category">
                    <span class="text-success"><i class="fa fa-long-arrow-up"></i>  </span> </p>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons">access_time</i> updated a minutes ago
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!--   Core JS Files   -->
  <script src="../assets/js/core/jquery.min.js"></script>
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap-material-design.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <!-- Plugin for the momentJs  -->
  <script src="../assets/js/plugins/moment.min.js"></script>
  <!--  Plugin for Sweet Alert -->
  <script src="../assets/js/plugins/sweetalert2.js"></script>
  <!-- Forms Validations Plugin -->
  <script src="../assets/js/plugins/jquery.validate.min.js"></script>
  <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
  <script src="../assets/js/plugins/jquery.bootstrap-wizard.js"></script>
  <!--  Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
  <script src="../assets/js/plugins/bootstrap-selectpicker.js"></script>
  <!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
  <script src="../assets/js/plugins/bootstrap-datetimepicker.min.js"></script>
  <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
  <script src="../assets/js/plugins/jquery.dataTables.min.js"></script>
  <!--  Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
  <script src="../assets/js/plugins/bootstrap-tagsinput.js"></script>
  <!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
  <script src="../assets/js/plugins/jasny-bootstrap.min.js"></script>
  <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
  <script src="../assets/js/plugins/fullcalendar.min.js"></script>
  <!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
  <script src="../assets/js/plugins/jquery-jvectormap.js"></script>
  <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
  <script src="../assets/js/plugins/nouislider.min.js"></script>
  <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
  <!-- Library for adding dinamically elements -->
  <script src="../assets/js/plugins/arrive.min.js"></script>
  <!--  Google Maps Plugin    -->
  <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
  <!-- Chartist JS -->
  <script src="../assets/js/plugins/chartist.min.js"></script>
  <!--  Notifications Plugin    -->
  <script src="../assets/js/plugins/bootstrap-notify.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/material-dashboard.js?v=2.1.2" type="text/javascript"></script>
  <!-- Material Dashboard DEMO methods, don't include it in your project! -->
  <script src="../assets/demo/demo.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
  <script type="text/javascript">
    var ctx = document.getElementById("chartjs_bar").getContext('2d');
              var myChart = new Chart(ctx, {
                  type: 'bar',
                  data: {
                      labels:<?php echo json_encode($agent_name); ?>,
                      datasets: [{
                          backgroundColor: [
                             "#00FFFF",
                              "#ff407b",
                              "#25d5f2",
                              "#ffc750",
                              "#2ec551",
                              "#7040fa",
                              "#ff004e",
                              "#FF00FF",
                              "#FFA500",
                              "#A52A2A",
                              "#008000"
                          ],
                          data:<?php echo json_encode($sales); ?>,
                      }]
                  },
                  options: {
                         legend: {
                      display: true,
                      position: 'bottom',
  
                      labels: {
                          fontColor: '#71748d',
                          fontFamily: 'Circular Std Book',
                          fontSize: 14,
                      }
                  },
  
  
              }
              });
  </script>
  <script>
    $(document).ready(function() {
      $().ready(function() {
        $sidebar = $('.sidebar');

        $sidebar_img_container = $sidebar.find('.sidebar-background');

        $full_page = $('.full-page');

        $sidebar_responsive = $('body > .navbar-collapse');

        window_width = $(window).width();

        fixed_plugin_open = $('.sidebar .sidebar-wrapper .nav li.active a p').html();

        if (window_width > 767 && fixed_plugin_open == 'Dashboard') {
          if ($('.fixed-plugin .dropdown').hasClass('show-dropdown')) {
            $('.fixed-plugin .dropdown').addClass('open');
          }

        }

        $('.fixed-plugin a').click(function(event) {
          // Alex if we click on switch, stop propagation of the event, so the dropdown will not be hide, otherwise we set the  section active
          if ($(this).hasClass('switch-trigger')) {
            if (event.stopPropagation) {
              event.stopPropagation();
            } else if (window.event) {
              window.event.cancelBubble = true;
            }
          }
        });

        $('.fixed-plugin .active-color span').click(function() {
          $full_page_background = $('.full-page-background');

          $(this).siblings().removeClass('active');
          $(this).addClass('active');

          var new_color = $(this).data('color');

          if ($sidebar.length != 0) {
            $sidebar.attr('data-color', new_color);
          }

          if ($full_page.length != 0) {
            $full_page.attr('filter-color', new_color);
          }

          if ($sidebar_responsive.length != 0) {
            $sidebar_responsive.attr('data-color', new_color);
          }
        });

        $('.fixed-plugin .background-color .badge').click(function() {
          $(this).siblings().removeClass('active');
          $(this).addClass('active');

          var new_color = $(this).data('background-color');

          if ($sidebar.length != 0) {
            $sidebar.attr('data-background-color', new_color);
          }
        });

        $('.fixed-plugin .img-holder').click(function() {
          $full_page_background = $('.full-page-background');

          $(this).parent('li').siblings().removeClass('active');
          $(this).parent('li').addClass('active');


          var new_image = $(this).find("img").attr('src');

          if ($sidebar_img_container.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
            $sidebar_img_container.fadeOut('fast', function() {
              $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
              $sidebar_img_container.fadeIn('fast');
            });
          }

          if ($full_page_background.length != 0 && $('.switch-sidebar-image input:checked').length != 0) {
            var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

            $full_page_background.fadeOut('fast', function() {
              $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
              $full_page_background.fadeIn('fast');
            });
          }

          if ($('.switch-sidebar-image input:checked').length == 0) {
            var new_image = $('.fixed-plugin li.active .img-holder').find("img").attr('src');
            var new_image_full_page = $('.fixed-plugin li.active .img-holder').find('img').data('src');

            $sidebar_img_container.css('background-image', 'url("' + new_image + '")');
            $full_page_background.css('background-image', 'url("' + new_image_full_page + '")');
          }

          if ($sidebar_responsive.length != 0) {
            $sidebar_responsive.css('background-image', 'url("' + new_image + '")');
          }
        });

        $('.switch-sidebar-image input').change(function() {
          $full_page_background = $('.full-page-background');

          $input = $(this);

          if ($input.is(':checked')) {
            if ($sidebar_img_container.length != 0) {
              $sidebar_img_container.fadeIn('fast');
              $sidebar.attr('data-image', '#');
            }

            if ($full_page_background.length != 0) {
              $full_page_background.fadeIn('fast');
              $full_page.attr('data-image', '#');
            }

            background_image = true;
          } else {
            if ($sidebar_img_container.length != 0) {
              $sidebar.removeAttr('data-image');
              $sidebar_img_container.fadeOut('fast');
            }

            if ($full_page_background.length != 0) {
              $full_page.removeAttr('data-image', '#');
              $full_page_background.fadeOut('fast');
            }

            background_image = false;
          }
        });

        $('.switch-sidebar-mini input').change(function() {
          $body = $('body');

          $input = $(this);

          if (md.misc.sidebar_mini_active == true) {
            $('body').removeClass('sidebar-mini');
            md.misc.sidebar_mini_active = false;

            $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();

          } else {

            $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar('destroy');

            setTimeout(function() {
              $('body').addClass('sidebar-mini');

              md.misc.sidebar_mini_active = true;
            }, 300);
          }

          // we simulate the window Resize so the charts will get updated in realtime.
          var simulateWindowResize = setInterval(function() {
            window.dispatchEvent(new Event('resize'));
          }, 180);

          // we stop the simulation of Window Resize after the animations are completed
          setTimeout(function() {
            clearInterval(simulateWindowResize);
          }, 1000);

        });
      });
    });
  </script>
  <script>
    $(document).ready(function() {
      // Javascript method's body can be found in assets/js/demos.js
      md.initDashboardPageCharts();

    });
  </script>

  <script>
    $(document).ready(function(e){
      $('body').delegate('.stc-elec-pd-search-hit', 'click', function(e){
        e.preventDefault();
        var text_value=$('.stc-elec-pd-search-val').val();
        window.location.href="inventory.php?inv_pd_search="+text_value;
      });
    });
  </script>
</body>

</html>