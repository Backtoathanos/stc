<?php
ini_set("session.gc_maxlifetime", 21600);
session_set_cookie_params(21600);
session_start();
if(empty(@$_SESSION['stc_trading_user_id'])){
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
    STC Trading || Inventory
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
      <div class="logo">
        <a href="#" class="simple-text logo-normal">
          STC Trading
        </a>
      </div>
      <div class="sidebar-wrapper">
        <ul class="nav">
          <li class="nav-item  ">
            <a class="nav-link" href="./dashboard.php">
              <i class="material-icons">dashboard</i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./product.php">
              <i class="material-icons">shop</i>
              <p>Product</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="./purchase.php">
              <i class="fa fa-sticky-note-o" aria-hidden="true"></i>
              <p>Purchase</p>
            </a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" href="./inventory.php">
              <i class="material-icons">content_paste</i>
              <p>Stock</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="./customer.php">
              <i class="material-icons">link</i>
              <p>Customer</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="./sale.php">
              <i class="material-icons">store</i>
              <p>Sale</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="./ledger.php">
              <i class="fa fa-undo" aria-hidden="true"></i>
              <p>Ledger</p>
            </a>
          </li>
          <li class="nav-item ">
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
            <div class="col-lg-12 col-md-12 col-sm-12">
              <div class="card card-stats">
                <div class="card-header card-header-warning card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">content_paste</i>
                  </div>
                  <p class="card-category">Inventory</p>
                  <div class="card-title">
                    <div class="stc-electro-get-items-by">
                      <table class="table table-hover table-bordered">
                        <thead class="text-danger">
                          <h1>
                            <b>
                              <th class="text-center">#</th>
                              <th class="text-center">Item Code</th>
                              <th class="text-center">Items</th>
                              <th class="text-center">Total Purchase Quantity</th>
                              <th class="text-center">Last Purchase Rate</th>
                              <th class="text-center">Stock Quantity</th>
                              <th class="text-center">Total Sale Quantity</th>
                            </b>
                          </h1>
                        </thead>
                        <tbody>
                          <?php
                            include_once("../../MCU/db.php");
                            $totalstock=0;
                            if(empty($_GET['inv_pd_search'])){
                              $stcelectroinvquery=mysqli_query($con, "
                                SELECT
                                  `stc_trading_product_id`,
                                  `stc_trading_product_title`,
                                  `stc_trading_product_details`
                                FROM
                                  `stc_trading_product`
                                ORDER BY
                                  `stc_trading_product_title` 
                                ASC
                              ");
                              $sl=1;
                              if(mysqli_num_rows($stcelectroinvquery)>0){
                                foreach($stcelectroinvquery as $invrow){
                                  $loki_getfrpuritems=mysqli_query($con, "
                                    SELECT 
                                      `stc_trading_purchase_items_qty`,
                                      `stc_trading_purchase_items_price`, 
                                      `stc_trading_purchase_items_unit`,
                                      `stc_trading_purchase_items_gst` 
                                    FROM 
                                      `stc_trading_purchase_items` 
                                    WHERE 
                                      `stc_trading_purchase_items_item_id`='".$invrow["stc_trading_product_id"]."' 
                                  ");
                                  $pquantity=0;
                                  $prate=0;
                                  $punit='';
                                  foreach($loki_getfrpuritems as $loki_getfrpuritemsrow){
                                    $pquantity+=$loki_getfrpuritemsrow['stc_trading_purchase_items_qty'];
                                    $punit=$loki_getfrpuritemsrow['stc_trading_purchase_items_unit'];

                                    $gstamount = ($loki_getfrpuritemsrow['stc_trading_purchase_items_price'] * $loki_getfrpuritemsrow["stc_trading_purchase_items_gst"])/100;
                                    $prate=$loki_getfrpuritemsrow['stc_trading_purchase_items_price'] + $gstamount;
                                  }

                                  $loki_getfrsaleitems=mysqli_query($con, "
                                    SELECT 
                                      `stc_trading_sale_items_item_qty`,
                                      `stc_trading_sale_items_item_price`, 
                                      `stc_trading_sale_items_item_unit`,
                                      `stc_trading_sale_items_item_gst` 
                                    FROM 
                                      `stc_trading_sale_items` 
                                    WHERE 
                                      `stc_trading_sale_items_item_id`='".$invrow["stc_trading_product_id"]."' 
                                  ");

                                  $squantity=0;
                                  $srate=0;
                                  $sunit='';
                                  foreach($loki_getfrsaleitems as $loki_getfrsaleitemsrow){
                                    $squantity+=$loki_getfrsaleitemsrow['stc_trading_sale_items_item_qty'];
                                    $sunit=$loki_getfrsaleitemsrow['stc_trading_sale_items_item_unit'];
                                  }
                                  $inventory= $pquantity - $squantity;
                                  $totalstock+=$inventory * $prate;
                                  echo "
                                    <tr>
                                      <td class='text-center'>".$sl."</td>
                                      <td class='text-center'>".$invrow['stc_trading_product_id']."</td>
                                      <td class='text-center'>".$invrow['stc_trading_product_title']."</td>
                                      <td class='text-right'>".number_format($pquantity, 2).'/'.$punit."</td>
                                      <td class='text-right'>".number_format($prate, 2).'/'.$punit."</td>
                                      <td class='text-right'>".number_format($inventory, 2).'/'.$punit."</td>
                                      <td class='text-right'>".number_format($squantity, 2).'/'.$sunit."</td>
                                    </tr>
                                  ";
                                  $sl++;
                                }
                                echo "
                                  <tr>
                                    <td class='text-right' colspan='5'><h4>Total Stock : </h4></td>
                                    <td class='text-right'><h4><b>".number_format($totalstock, 2)."</b></h4></td>
                                  </tr>
                                ";
                              }else{
                                echo "
                                    <tr>
                                      <td colspan='8' align='center'>No record found <b>:(<b></td>
                                    </tr>
                                ";
                              }
                            }else{
                              $stcelectroinvquery=mysqli_query($con, "
                                SELECT
                                  `stc_trading_product_id`,
                                  `stc_trading_product_title`,
                                  `stc_trading_product_details`
                                FROM
                                  `stc_trading_product`
                                WHERE 
                                  `stc_trading_product_title` REGEXP '".mysqli_real_escape_string($con, $_GET['inv_pd_search'])."'
                                  OR `stc_trading_product_details` REGEXP '".mysqli_real_escape_string($con, $_GET['inv_pd_search'])."'
                                ORDER BY
                                  `stc_trading_product_title` 
                                ASC
                              ");
                              $sl=1;

                              if(mysqli_num_rows($stcelectroinvquery)>0){
                                foreach($stcelectroinvquery as $invrow){
                                  $loki_getfrpuritems=mysqli_query($con, "
                                    SELECT 
                                      `stc_trading_purchase_items_qty`,
                                      `stc_trading_purchase_items_price`, 
                                      `stc_trading_purchase_items_unit`,
                                      `stc_trading_purchase_items_gst` 
                                    FROM 
                                      `stc_trading_purchase_items` 
                                    WHERE 
                                      `stc_trading_purchase_items_item_id`='".$invrow["stc_trading_product_id"]."' 
                                  ");
                                  $pquantity=0;
                                  $prate=0;
                                  $punit='';
                                  foreach($loki_getfrpuritems as $loki_getfrpuritemsrow){
                                    $pquantity+=$loki_getfrpuritemsrow['stc_trading_purchase_items_qty'];
                                    $punit=$loki_getfrpuritemsrow['stc_trading_purchase_items_unit'];

                                    $gstamount = ($loki_getfrpuritemsrow['stc_trading_purchase_items_price'] * $loki_getfrpuritemsrow["stc_trading_purchase_items_gst"])/100;
                                    $prate=$loki_getfrpuritemsrow['stc_trading_purchase_items_price'] + $gstamount;
                                  }

                                  $loki_getfrsaleitems=mysqli_query($con, "
                                    SELECT 
                                      `stc_trading_sale_items_item_qty`,
                                      `stc_trading_sale_items_item_price`, 
                                      `stc_trading_sale_items_item_unit`,
                                      `stc_trading_sale_items_item_gst` 
                                    FROM 
                                      `stc_trading_sale_items` 
                                    WHERE 
                                      `stc_trading_sale_items_item_id`='".$invrow["stc_trading_product_id"]."' 
                                  ");

                                  $squantity=0;
                                  $srate=0;
                                  $sunit='';
                                  foreach($loki_getfrsaleitems as $loki_getfrsaleitemsrow){
                                    $squantity+=$loki_getfrsaleitemsrow['stc_trading_sale_items_item_qty'];
                                    $sunit=$loki_getfrsaleitemsrow['stc_trading_sale_items_item_unit'];
                                  }
                                  $inventory= $pquantity - $squantity;
                                  $totalstock+=$inventory * $prate;
                                  echo "
                                    <tr>
                                      <td class='text-center'>".$sl."</td>
                                      <td class='text-center'>".$invrow['stc_trading_product_id']."</td>
                                      <td class='text-center'>".$invrow['stc_trading_product_title']."</td>
                                      <td class='text-right'>".number_format($pquantity, 2).'/'.$punit."</td>
                                      <td class='text-right'>".number_format($prate, 2).'/'.$punit."</td>
                                      <td class='text-right'>".number_format($inventory, 2).'/'.$punit."</td>
                                      <td class='text-right'>".number_format($squantity, 2).'/'.$sunit."</td>
                                    </tr>
                                  ";
                                  $sl++;
                                }
                                echo "
                                  <tr>
                                    <td class='text-right' colspan='5'><h4>Total Stock : </h4></td>
                                    <td class='text-right'><h4><b>".number_format($totalstock, 2)."</b></h4></td>
                                  </tr>
                                ";
                              }else{
                                echo "
                                    <tr>
                                      <td colspan='8' align='center'>No record found <b>:(<b></td>
                                    </tr>
                                ";
                              }
                            }                              
                          ?>                                  
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <div class="stats">
                    <i class="material-icons text-danger">warning</i>
                    <a href="javascript:;">Be carefull</a>
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