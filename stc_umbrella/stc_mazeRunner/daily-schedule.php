<?php
ini_set("session.gc_maxlifetime", 21600);
session_set_cookie_params(21600);
session_start();
if(empty(@$_SESSION['stc_school_user_id'])){
    header('location:../index.html');
}
if($_SESSION['stc_school_user_for']==2){
    header('location:forbidden.html');
}
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="../assets/img/stc_logo_title.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>
      STC School || School Schedule
    </title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- CSS Files -->
    <link href="../assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
    <style>
        .stc-stuattendance-table{
          width:auto;
          overflow-x: scroll;
        }
        .stc-schoolschedule-show{
          width:auto;
          overflow-x: scroll;
        }
        .fade:not(.show) {
          opacity: 10;
        }
      .d-flex {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
      }

      .mb-3{
        border-block: initial;
        border: 2px solid grey;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        padding: 10px;
        margin: 5px;
      }
      .align-center {
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
      }

      .flex-centerY-centerX {
        justify-content: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
      }

      .page-wrapper {
        height: 100%;
        display: table;
      }

      .page-wrapper .page-inner {
        display: table-cell;
        vertical-align: middle;
      }

      .el-wrapper {
        width: 360px;
        padding: 15px;
        margin: 15px auto;
        background-color: #fff;
      }

      @media (max-width: 991px) {
        .el-wrapper {
          width: 345px;
        }
      }

      @media (max-width: 767px) {
        .el-wrapper {
          width: 290px;
          margin: 30px auto;
        }
      }

      .el-wrapper:hover .h-bg {
        left: 0px;
      }

      .el-wrapper:hover .price {
        left: 20px;
        -webkit-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        -o-transform: translateY(-50%);
        transform: translateY(-50%);
        color: #818181;
      }

      .el-wrapper:hover .add-to-cart {
        left: 50%;
      }

      .el-wrapper:hover .img {
        webkit-filter: blur(7px);
        -o-filter: blur(7px);
        -ms-filter: blur(7px);
        filter: blur(7px);
        filter: progid:DXImageTransform.Microsoft.Blur(pixelradius='7', shadowopacity='0.0');
        opacity: 0.4;
      }

      .el-wrapper:hover .info-inner {
        bottom: 155px;
      }

      .el-wrapper:hover .a-size {
        -webkit-transition-delay: 300ms;
        -o-transition-delay: 300ms;
        transition-delay: 300ms;
        bottom: 50px;
        opacity: 1;
      }

      .el-wrapper .box-down {
        width: 100%;
        height: 60px;
        position: relative;
        overflow: hidden;
      }

      .el-wrapper .box-up {
        width: 100%;
        height: 400px;
        position: relative;
        overflow: hidden;
        text-align: center;
      }

      .el-wrapper .img {
        padding: 20px 0;
        -webkit-transition: all 800ms cubic-bezier(0, 0, 0.18, 1);
        -moz-transition: all 800ms cubic-bezier(0, 0, 0.18, 1);
        -o-transition: all 800ms cubic-bezier(0, 0, 0.18, 1);
        transition: all 800ms cubic-bezier(0, 0, 0.18, 1);
        /* ease-out */
        -webkit-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        -moz-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        -o-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        /* ease-out */
      }

      .h-bg {
        -webkit-transition: all 800ms cubic-bezier(0, 0, 0.18, 1);
        -moz-transition: all 800ms cubic-bezier(0, 0, 0.18, 1);
        -o-transition: all 800ms cubic-bezier(0, 0, 0.18, 1);
        transition: all 800ms cubic-bezier(0, 0, 0.18, 1);
        /* ease-out */
        -webkit-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        -moz-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        -o-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        /* ease-out */
        width: 660px;
        height: 100%;
        background-color: #3f96cd;
        position: absolute;
        left: -659px;
      }

      .h-bg .h-bg-inner {
        width: 50%;
        height: 100%;
        background-color: #464646;
      }

      .info-inner {
        -webkit-transition: all 400ms cubic-bezier(0, 0, 0.18, 1);
        -moz-transition: all 400ms cubic-bezier(0, 0, 0.18, 1);
        -o-transition: all 400ms cubic-bezier(0, 0, 0.18, 1);
        transition: all 400ms cubic-bezier(0, 0, 0.18, 1);
        /* ease-out */
        -webkit-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        -moz-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        -o-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        /* ease-out */
        position: absolute;
        width: 100%;
        bottom: 25px;
      }

      .info-inner .p-name,
      .info-inner .p-company,
       .info-inner .p-qty {
        display: block;
      }

      .info-inner .p-name {
        font-family: 'PT Sans', sans-serif;
        font-size: 18px;
        color: #252525;
      }

      .info-inner .p-company {
        font-family: 'Lato', sans-serif;
        font-size: 12px;
        text-transform: uppercase;
        color: #8c8c8c;
      }

      .info-inner .p-qty {
        font-family: 'Lato', sans-serif;
        font-size: 25px;
        font-weight: bold;
        text-transform: uppercase;
        color: #020822;;
      }

      .a-size {
        -webkit-transition: all 300ms cubic-bezier(0, 0, 0.18, 1);
        -moz-transition: all 300ms cubic-bezier(0, 0, 0.18, 1);
        -o-transition: all 300ms cubic-bezier(0, 0, 0.18, 1);
        transition: all 300ms cubic-bezier(0, 0, 0.18, 1);
        /* ease-out */
        -webkit-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        -moz-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        -o-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        /* ease-out */
        position: absolute;
        width: 100%;
        bottom: -20px;
        font-family: 'PT Sans', sans-serif;
        color: #828282;
        opacity: 0;
      }

      .a-size .size {
        color: #252525;
      }

      .cart {
        display: block;
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        font-family: 'Lato', sans-serif;
        font-weight: 700;
      }

      .cart .price {
        -webkit-transition: all 600ms cubic-bezier(0, 0, 0.18, 1);
        -moz-transition: all 600ms cubic-bezier(0, 0, 0.18, 1);
        -o-transition: all 600ms cubic-bezier(0, 0, 0.18, 1);
        transition: all 600ms cubic-bezier(0, 0, 0.18, 1);
        /* ease-out */
        -webkit-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        -moz-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        -o-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        /* ease-out */
        -webkit-transition-delay: 100ms;
        -o-transition-delay: 100ms;
        transition-delay: 100ms;
        display: block;
        position: absolute;
        top: 50%;
        left: 50%;
        -webkit-transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        -o-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        font-size: 16px;
        color: #252525;
      }

      .cart .add-to-cart {
        -webkit-transition: all 600ms cubic-bezier(0, 0, 0.18, 1);
        -moz-transition: all 600ms cubic-bezier(0, 0, 0.18, 1);
        -o-transition: all 600ms cubic-bezier(0, 0, 0.18, 1);
        transition: all 600ms cubic-bezier(0, 0, 0.18, 1);
        /* ease-out */
        -webkit-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        -moz-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        -o-transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        transition-timing-function: cubic-bezier(0, 0, 0.18, 1);
        /* ease-out */
        -webkit-transition-delay: 100ms;
        -o-transition-delay: 100ms;
        transition-delay: 100ms;
        display: block;
        position: absolute;
        top: 50%;
        left: 110%;
        -webkit-transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        -o-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
      }

      .cart .add-to-cart .txt {
        font-size: 12px;
        color: #fff;
        letter-spacing: 0.045em;
        text-transform: uppercase;
        white-space: nowrap;
      }

      .bootstrap-tagsinput {
       width: 100%;
       color: blue;
      }
      .bootstrap-tagsinput .tag {
         color: black;
       }

      .search-box,.close-icon,.search-wrapper {
        position: relative;
        padding: 10px;
      }
      .search-wrapper {
        width: 500px;
        margin: auto;
        margin-top: 50px;
      }
      .search-box {
        width: 98%;
        border: 1px solid #ccc;
        outline: 0;
        border-radius: 15px;
      }
      .search-box:focus {
        box-shadow: 0 0 15px 5px #b0e0ee;
        border: 2px solid #bebede;
      }
      .close-icon {
        border:1px solid transparent;
        background-color: transparent;
        display: inline-block;
        vertical-align: middle;
        outline: 0;
        cursor: pointer;
      }
      .close-icon:after {
        content: "X";
        display: block;
        width: 15px;
        height: 15px;
        position: absolute;
        background-color: #FA9595;
        z-index:1;
        right: 35px;
        top: 0;
        bottom: 0;
        margin: auto;
        padding: 0px;
        border-radius: 50%;
        text-align: center;
        color: white;
        font-weight: normal;
        font-size: 12px;
        box-shadow: 0 0 2px #E50F0F;
        cursor: pointer;
      }
      .search-box:not(:valid) ~ .close-icon {
        display: none;
      }
      .stc_print_page i{
        color: black;
      }
    </style>
  </head>

  <body class="">
    <div class="wrapper ">
      <?php include_once("bar/sidebar.php");?>
      <div class="main-panel">
        <!-- Navbar -->
        <?php include_once("bar/navbar.php");?>
        <!-- End Navbar -->
        <div class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-12 col-md-12">
                <div class="card">
                  <div class="card-header card-header-tabs card-header-primary">
                    <div class="nav-tabs-navigation">
                      <div class="nav-tabs-wrapper">
                        <span class="nav-tabs-title">Tasks:</span>
                        <ul class="nav nav-tabs" data-tabs="tabs">
                          <li class="nav-item">
                            <a class="nav-link active" href="#stc-create-attendance" data-toggle="tab">
                              <i class="material-icons">add_circle</i> Schedule
                              <div class="ripple-container"></div>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="tab-content">
                      <div class="tab-pane active" id="stc-create-attendance">
                        <div class="row">
                          <div class="col-12">
                            <h2 class="tm-block-title d-inline-block">Daily Schedule</h2>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
                            <div class="tm-bg-primary-dark tm-block tm-block-h-auto" >
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                  <div class="mb-3 stc-schoolschedule-show">
                                    <table class="table table-hover table-bordered">
                                      <thead>
                                        <tr>
                                          <td class="text-center"><b>Day</b></td>
                                          <td class="text-center"><b>1<span style="vertical-align: top;font-size: 11px;">st</span> Period</b></td>
                                          <td class="text-center"><b>2<span style="vertical-align: top;font-size: 11px;">nd</span> Period</b></td>
                                          <td class="text-center"><b>3<span style="vertical-align: top;font-size: 11px;">rd</span> Period</b></td>
                                          <td class="text-center"><b>4<span style="vertical-align: top;font-size: 11px;">th</span> Period</b></td>
                                          <td class="text-center"><b>5<span style="vertical-align: top;font-size: 11px;">th</span> Period</b></td>
                                          <td class="text-center"><b>6<span style="vertical-align: top;font-size: 11px;">th</span> Period</b></td>
                                          <td class="text-center"><b>7<span style="vertical-align: top;font-size: 11px;">th</span> Period</b></td>
                                        </tr>
                                      </thead>
                                      <tbody class="stc-teacher-schedule-show-table">
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
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
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
      $(document).ready(function() {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const value = urlParams.get('daily-schedule');
        if(value=="yes"){
          $('.daily-schedule').addClass('active');
        }
      });
    </script>
    <script>
      $(document).ready(function(){
        $('.close-icon').on('click', function(e){
          e.preventDefault();
            $('.stc-electro-pd-show-ch').fadeOut(500);
        });

        $('.upward').on('click', function(e){
          e.preventDefault();
            $('.downward').toggle(500);
            $('.stc-electro-pd-show-ch').fadeOut(500);
            $('.upward').fadeOut(500);
        });

        $('.downward').on('click', function(e){
          e.preventDefault();
            $('.upward').toggle(500);
            $('.stc-electro-pd-show-ch').toggle(500);
            $('.downward').fadeOut(500);
        });

        $(".ddd").on("click", function(w) {
          w.preventDefault();
          var $button = $(this);
          var $input = $button.closest('.sp-quantity').find("input.quntity-input");
          $input.val((i, v) => Math.max(0, +v + 1 * $button.data('multi')));
        });
      });
    </script>
    <script>
      $(document).ready(function(){
        call_scedule()
        function call_scedule(){
          $.ajax({
            url       : "../vanaheim/school-management.php",
            method    : "POST",  
            data      : {
              stc_teacherschedule_call : 1
            },
            dataType: `JSON`,
            success   : function(response_teacher){
              $('.stc-teacher-schedule-show-table').html(response_teacher.schedule);
              if(response_teacher.att_result=="y"){
                $('.stc-school-show-student-default').click();
              }
            }
          });
        }

        $(document).on('click', '.stc-school-show-student', function(e){
          e.preventDefault();
          var schedule_id=$(this).attr('id');
          var class_id=$(this).attr('class-id');
          var sub_id=$(this).attr('sub-id');
          $.ajax({  
            url       : "../vanaheim/school-management.php",
            method    : "POST",  
            data      : {
              stc_call_student : 1,
              schedule_id:schedule_id,
              class_id:class_id
            },
            // dataType: `JSON`,
            success   : function(response_student){
             // console.log(data);
              $('.stc-school-hidden-schedule-id').val(schedule_id);
              $('.stc-show-student-nested-show').html(response_student);
              $('.stc-school-hidden-scclass-id').val(class_id);
              $('.stc-school-hidden-scsub-id').val(sub_id);
              // $('.stc-school-showstudent-res').modal('show');
              call_syllabus_det();
            }
          });
        });

        $(document).on('click', '.stc-school-show-student-default', function(e){
          e.preventDefault();
          var schedule_id=$(this).attr('id');
          var class_id=$(this).attr('class-id');
          var sub_id=$(this).attr('sub-id');
          $.ajax({  
            url       : "../vanaheim/school-management.php",
            method    : "POST",  
            data      : {
              stc_call_student_default : 1,
              schedule_id:schedule_id,
              class_id:class_id
            },
            // dataType: `JSON`,
            success   : function(response_student){
             // console.log(data);
              $('.stc-school-hidden-schedule-id').val(schedule_id);
              $('.stc-show-student-nested-show').html(response_student);
              $('.stc-school-hidden-scclass-id').val(class_id);
              $('.stc-school-hidden-scsub-id').val(sub_id);
              // $('.stc-school-showstudent-res').modal('show');
              call_syllabus_det();
            }
          });
        });

        $(document).on('click', '.stc-school-exit-period', function(e){
          e.preventDefault();
          var student_id = [];
          var sub_id = [];
          var class_id = [];
          var attendance = [];
          $('.stc-attend-check:checked').each(function () {
            student_id.push($(this).attr('id')); 
            sub_id.push($(this).attr('subid')); 
            class_id.push($(this).attr('classid')); 
            attendance.push($(this).val()); 
          });
          $.ajax({  
            url       : "../vanaheim/school-management.php",
            method    : "POST",  
            data      : {
              stc_call_lecture_end : 1,
              student_id : student_id,
              sub_id : sub_id,
              class_id : class_id,
              attendance : attendance

            },
            // dataType: `JSON`,
            success   : function(response_student){
             // console.log(data);
              var response=response_student.trim();
              if(response=="reload"){
                window.location.reload();
              }else{
                $('.stc-school-showstudent-res').modal('hide');
                window.location.reload();
              }
            }
          });
        });

        // $(document).on('click', '.stc-attend-check', function(e){
        //   var stvalue=$(this).val();
        //   if(stvalue==0){
        //     $(this).parent().parent().find('.stc-school-student-att-save').hide();
        //   }else{
        //     $(this).parent().parent().find('.stc-school-student-att-save').show();
        //   }
        // });
        
        $(document).on('click', '.stc-school-student-att-save', function(e){
          e.preventDefault();
          $(this).hide(500);
          var stc_stid = $(this).attr('id');
          var stc_stclassid = $(this).attr('classid');
          var stc_stsubid = $(this).attr('subid');
          var stc_sthwperc = $('.stc-school-stu-attendance-hw'+stc_stid).val();
          var stc_stcatt = $('.stc-school-stu-attendance-but'+stc_stid + ':checked').val();
          $.ajax({  
            url       : "../vanaheim/school-management.php",
            method    : "POST",  
            data      : {
              stc_student_save : 1,
              stc_stid : stc_stid,
              stc_stsubid : stc_stsubid,
              stc_stclassid : stc_stclassid,
              stc_sthwperc : stc_sthwperc,
              stc_stcatt : stc_stcatt
            },
            // dataType: `JSON`,
            success   : function(response_student){
             // console.log(response_student);
              var response=response_student.trim();
              if(response=="reload"){
                window.location.reload();
              }else if(response=="success"){
                alert("Student record updated!!!");
              }else{
                alert("Something went wrong!!! Please check & try again.");
                $('.stc-school-student-att-save').show(500);
              }
            }
          });
        });
        
        $(document).on('change', '.stc-attend-check', function(e){
          var stc_stid = $(this).attr('id');
          var stc_stclassid = $(this).attr('classid');
          var stc_stsubid = $(this).attr('subid');
          var stc_sthwperc = 0;
          var stc_stcatt = $(this).val();
          if(stc_stcatt>0){
            $.ajax({  
              url       : "../vanaheim/school-management.php",
              method    : "POST",  
              data      : {
                stc_student_save : 1,
                stc_stid : stc_stid,
                stc_stsubid : stc_stsubid,
                stc_stclassid : stc_stclassid,
                stc_sthwperc : stc_sthwperc,
                stc_stcatt : stc_stcatt
              },
              // dataType: `JSON`,
              success   : function(response_student){
              // console.log(response_student);
                var response=response_student.trim();
                if(response=="reload"){
                  window.location.reload();
                }else if(response=="success"){
                  alert("Student attendance updated!!!");
                }else if(response=="duplicate"){
                  alert("Student attendance already updated!!!");
                }else{
                  alert("Something went wrong!!! Please check & try again.");
                  $('.stc-school-student-att-save').show(500);
                }
              }
            });
          }else{            
            alert("Student attendance updated!!!");
          }
        });

        $(document).on('click', '.save-lecture', function(e){
          e.preventDefault();
          var schedule_id=$('.stc-school-hidden-schedule-id').val();
          var classtype=$('#classtype').val();
          var chapter=$('#chapter').val();
          var lession=$('#lession').val();
          var Syllabus=$('#Syllabus').val();
          var Unit=$('#unit-should-be').val();
          var remarks=$('#remarks').val();
          $.ajax({  
            url       : "../vanaheim/school-management.php",
            method    : "POST",  
            data      : {
              stc_lecturedet_save : 1,
              schedule_id : schedule_id,
              classtype : classtype,
              chapter : chapter,
              lession : lession,
              Syllabus : Syllabus,
              Unit : Unit,
              remarks : remarks
            },
            // dataType: `JSON`,
            success   : function(response_student){
             // console.log(response_student);
              call_syllabus_det();
              var response=response_student.trim();
              if(response=="reload"){
                window.location.reload();
              }else if(response=="success"){
                $('#classtype').val('NA');
                $('#chapter').val('');
                $('#lession').val('');
                $('#Syllabus').val('');
                $('#remarks').val('');
                alert("Record updated!!!");
              }else if(response=="empty"){
                alert("Please fill all fields, if you dont have any then write NA.");
              }else{
                alert("Something went wrong!!! Please check & try again.");
              }
            }
          });
        });

        $(document).on('click', '.add-question', function(e){
          e.preventDefault();
          var schedule_id=$('.stc-school-hidden-schedule-id').val();
          var questions=$('#Questions').val();
          $.ajax({  
            url       : "../vanaheim/school-management.php",
            method    : "POST",  
            data      : {
              stc_lecturedetquestion_save : 1,
              schedule_id : schedule_id,
              questions : questions
            },
            // dataType: `JSON`,
            success   : function(response_student){
             // console.log(response_student);
              var response=response_student.trim();
              if(response=="reload"){
                window.location.reload();
              }else if(response=="success"){
                call_syllabus_quest();
                alert("Record updated!!!");
                $('#Questions').val('');
              }else if(response=="empty"){
                alert("Please fill all fields, if you dont have any then write NA.");
              }else{
                alert("Something went wrong!!! Please check & try again.");
              }
            }
          });
        });

        var sy_syllabus = new Array();
        function call_syllabus_det(){
          var schedule_id=$('.stc-school-hidden-schedule-id').val();
          var class_id=$('.stc-school-hidden-scclass-id').val();
          var sub_id=$('.stc-school-hidden-scsub-id').val();
          $.ajax({
            url       : "../vanaheim/school-management.php",
            method    : "POST",  
            data      : {
              stc_syllabusdet_call : 1,
              schedule_id:schedule_id,
              class_id:class_id,
              sub_id:sub_id
            },
            dataType: `JSON`,
            success   : function(response_student){
              $('.stc-show-student-syllabusdet-show').html(response_student.lecture_details);
              var syl_result= response_student.syllabus_details;
              sy_syllabus.push(syl_result);
              var syllabus_output='<option value="NA">Select</option>';
              for(var i=0; i<sy_syllabus[0].length;i++){
                syllabus_output+='<option value="' + sy_syllabus[0][i].stc_school_syllabus_id + '">' + sy_syllabus[0][i].stc_school_syllabus_title + '</option>';
              }
              $('#Syllabus').html(syllabus_output);
              call_syllabus_quest();
            }
          });
        }

        $(document).on('change', '#Syllabus', function(){
          var syll_id = $(this).val();
          var chapter_output = '<option value="NA">Select</option>';
          for(var i=0; i<sy_syllabus[0].length;i++){
            chapter_output+='<option value="' + sy_syllabus[0][i].stc_school_syllabus_chapter + '" syll-id="' + sy_syllabus[0][i].stc_school_syllabus_id + '">' + sy_syllabus[0][i].stc_school_syllabus_chapter + '</option>';
          }
          $('#chapter').html(chapter_output);
        });

        $(document).on('change', '#chapter', function(){
          var syll_id = $(this).val();
          var lession_output = '<option value="NA">Select</option>';
          for(var i=0; i<sy_syllabus[0].length;i++){
            lession_output+='<option value="' + sy_syllabus[0][i].stc_school_syllabus_lession + '" syll-id="' + sy_syllabus[0][i].stc_school_syllabus_id + '">' + sy_syllabus[0][i].stc_school_syllabus_lession + '</option>';
          }
          $('#lession').html(lession_output);
        });

        $(document).on('change', '#lession', function(){
          var syll_id = $(this).val();
          var unit_output = '<option value="NA">Select</option>';
          for(var i=0; i<sy_syllabus[0].length;i++){
            unit_output+='<option value="' + sy_syllabus[0][i].stc_school_syllabus_unit + '" syll-id="' + sy_syllabus[0][i].stc_school_syllabus_id + '">' + sy_syllabus[0][i].stc_school_syllabus_unit + '</option>';
          }
          $('#unit-should-be').html(unit_output);
        });

        $(document).on('change', '#unit-should-be', function(){
          var syll_id = $(this).val();
          var cdate_output = '';
          for(var i=0; i<sy_syllabus[0].length;i++){
            if(syll_id==sy_syllabus[0][i].stc_school_syllabus_id){
              cdate_output=sy_syllabus[0][i].stc_school_syllabus_completedate;
            }
          }
          $('#complete-date').val(cdate_output);
        });

        function call_syllabus_quest(){
          var question_id=$('.stc-syllabus-out:checked').attr("id");
          if(question_id>0){
            $.ajax({
              url       : "../vanaheim/school-management.php",
              method    : "POST",  
              data      : {
                stc_syllabusquest_call : 1,
                question_id:question_id
              },
              // dataType: `JSON`,
              success   : function(response_student){
                $('.stc-show-student-syllabusquest-show').html(response_student);
              }
            });
          }
        }

        $(document).on('click', '.stc-syllabus-out', function(){
          call_syllabus_quest();
        });
      });
    </script>
  </body>
  </html>

<div class="modal fade stc-school-showstudent-res" data-backdrop="static" data-keyboard="false" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Student Attendance</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <button type="button" class="btn btn-danger stc-school-exit-period">Exit</button>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-lg-12 col-md-12">
            <div class="card">
              <div class="card-header card-header-tabs card-header-primary">
                <div class="nav-tabs-navigation">
                  <div class="nav-tabs-wrapper">
                    <span class="nav-tabs-title">Tasks:</span>
                    <ul class="nav nav-tabs" data-tabs="tabs">
                      <li class="nav-item resp-1">
                        <a class="nav-link active" href="#stc-show-attendance" data-toggle="tab">
                          <i class="material-icons">add_circle</i> Attendance
                          <div class="ripple-container"></div>
                        </a>
                      </li>
                      <li class="nav-item resp-2">
                        <a class="nav-link" href="#stc-show-lecture" data-toggle="tab">
                          <i class="material-icons">add_circle</i> Lecture
                          <div class="ripple-container"></div>
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="tab-content">
                  <div class="tab-pane active" id="stc-show-attendance">
                    <div class="row">
                      <div class="col-12">
                        <h2 class="tm-block-title d-inline-block">Attendance</h2>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
                        <input type="hidden" class="stc-school-hidden-schedule-id">
                        <input type="hidden" class="stc-school-hidden-scclass-id">
                        <input type="hidden" class="stc-school-hidden-scsub-id">
                        <!-- <h5>School Attendance</h5> -->
                        <div class="mb-3 stc-stuattendance-table">
                          <table class="table table-hover table-bordered">
                            <thead>
                              <tr>
                                <th class="text-center"><b>Student ID</b></th>
                                <th class="text-center"><b>Student Name</b></th>
                                <th class="text-center"><b>Class</b></th>
                                <th class="text-center"><b>Subject</b></th>
                                <th class="text-center" colspan="2"><b>Attendance</b></th>
                              </tr>
                            </thead>
                            <tbody class="stc-show-student-nested-show">
                              
                            </tbody>
                          </table>
                        </div>
                      </div>   
                    </div>
                  </div>
                  <div class="tab-pane" id="stc-show-lecture">
                    <div class="row">
                      <div class="col-12">
                        <h2 class="tm-block-title d-inline-block">Lecture</h2>
                      </div>
                    </div>
                    <div class="row"> 
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mx-auto">
                        <div class="mb-3">
                          <h5 for="classtype">Class Type</h5>
                          <span class="bmd-form-group">
                            <select name="stcschoolmanagementteacherid" id="classtype" type="text" class="form-control validate classtype" >
                              <option value="NA">Select</option>
                              <option value="Syllabus">Syllabus Class</option>
                              <option value="Revised">Revised Class</option>
                              <option value="Doubt">Doubt Class</option>
                            </select>
                          </span>
                        </div>
                      </div>          
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mx-auto">
                        <div class="mb-3">
                          <h5 for="syllabus">Syllabus</h5>
                          <span class="bmd-form-group">
                            <select name="stcschoolmanagementteacherid" id="Syllabus" type="text" class="form-control validate Syllabus" >
                              <option value="NA">Select</option>
                            </select>
                          </span>
                        </div>
                      </div>        
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mx-auto">
                        <div class="mb-3">
                          <h5 for="chapter">Chapter</h5>
                          <span class="bmd-form-group">
                            <select name="stcschoolmanagementteacherid" id="chapter" type="text" class="form-control validate chapter" >
                              <option value="NA">Select</option>
                            </select>
                          </span>
                        </div>
                      </div>          
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mx-auto">
                        <div class="mb-3">
                          <h5 for="lession">Lession</h5>
                          <span class="bmd-form-group">
                            <select name="stcschoolmanagementteacherid" id="lession" type="text" class="form-control validate lession" >
                              <option value="NA">Select</option>
                            </select>
                          </span>
                        </div>
                      </div>         
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mx-auto">
                        <div class="mb-3">
                          <h5 for="lession">Unit</h5>
                          <span class="bmd-form-group">
                            <select name="stcschoolmanagementteacherid" id="unit-should-be" type="text" class="form-control validate unit-should-be" >
                              <option value="NA">Select</option>
                            </select>
                          </span>
                        </div>
                      </div>           
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mx-auto">
                        <div class="mb-3">
                          <h5 for="lession">Complete date</h5>
                          <span class="bmd-form-group">
                            <input type="text" class="form-control" id="complete-date" placeholder="Type Here.." disabled>
                          </span>
                        </div>
                      </div>
                      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
                        <div class="mb-3">
                          <h5 for="syllabus">Remarks</h5>
                          <span class="bmd-form-group">
                            <textarea class="form-control" id="remarks" placeholder="Type Here.."></textarea>
                          </span>
                        </div>
                      </div>        
                      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
                        <div class="mb-3">
                          <span class="bmd-form-group">
                            <button class="btn btn-success form-control save-lecture">Save</button>
                          </span>
                        </div>
                      </div>
                      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
                        <div class="mb-3">
                          <table class="table table-hover table-bordered table-responsive">
                            <thead>
                              <tr>
                                <th class="text-center">Select</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Class Type</th>
                                <th class="text-center">Syllabus</th>
                                <th class="text-center">Chapter</th>
                                <th class="text-center">Lession</th>
                              </tr>
                            </thead>
                            <tbody class="stc-show-student-syllabusdet-show">
                              
                            </tbody>
                          </table>
                        </div>
                      </div>         
                      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
                        <div class="mb-3">
                          <h5 for="syllabus">Questions</h5>
                          <span class="bmd-form-group">
                            <input type="text" class="form-control" id="Questions" placeholder="Type Here..">
                          </span>
                        </div>
                      </div>
                      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
                        <div class="mb-3">
                          <span class="bmd-form-group">
                            <button class="btn btn-success form-control add-question">Add</button>
                          </span>
                        </div>
                      </div>
                      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
                        <div class="mb-3">
                          <table class="table table-hover table-bordered table-responsive">
                            <thead>
                              <tr>
                                <th class="text-center">Sl No</th>
                                <th class="text-center">Questions</th>
                              </tr>
                            </thead>
                            <tbody class="stc-show-student-syllabusquest-show">
                              
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
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger stc-school-exit-period">Exit</button>
      </div>
    </div>
  </div>
</div>