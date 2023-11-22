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
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>
      STC Trading || Purchase
    </title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- CSS Files -->
    <link href="../assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="../assets/demo/demo.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput-typeahead.css" />
    <style>
        .fade:not(.show) {
          opacity: 10;
        }
      .d-flex {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
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
      <div class="sidebar" data-color="purple" data-background-color="white" data-image="../assets/img/sidebar-1.jpg">
        <div class="logo"><a href="#" class="simple-text logo-normal">
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
            <li class="nav-item active">
              <a class="nav-link" href="./purchase.php">
                <i class="fa fa-sticky-note-o" aria-hidden="true"></i>
                <p>Purchase</p>
              </a>
            </li>
            <li class="nav-item ">
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
              <div class="col-lg-12 col-md-12">
                <div class="card">
                  <div class="card-header card-header-tabs card-header-primary">
                    <div class="nav-tabs-navigation">
                      <div class="nav-tabs-wrapper">
                        <span class="nav-tabs-title">Tasks:</span>
                        <ul class="nav nav-tabs" data-tabs="tabs">
                          <li class="nav-item">
                            <a class="nav-link active" href="#stc-create-purchase" data-toggle="tab">
                              <i class="material-icons">add_circle</i> Create Purchase
                              <div class="ripple-container"></div>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" href="#stc-show-purchase" data-toggle="tab">
                              <i class="material-icons">visibility</i> Show Purchase
                              <div class="ripple-container"></div>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="tab-content">
                      <div class="tab-pane active" id="stc-create-purchase">
                        <div class="row">
                          <div class="col-12">
                            <h2 class="tm-block-title d-inline-block">Create Purchase</h2>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
                            <div class="tm-bg-primary-dark tm-block tm-block-h-auto" >
                              <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Merchant Name
                                    </h5>
                                    <select
                                      class="form-control validate stc-silent-merchant-name"
                                    >
                                    <?php
                                      $date = date("d-m-Y");
                                      $newDate = date('Y-m-d', strtotime($date)); 
                                      include_once("../../MCU/db.php");
                                      $getmerchantqry=mysqli_query($con, "
                                        SELECT 
                                          `stc_merchant_id`, 
                                          `stc_merchant_name` 
                                        FROM `stc_merchant` 
                                        WHERE 
                                          `stc_merchant_specially_known_for` REGEXP 'trading' OR 
                                          `stc_merchant_specially_known_for` REGEXP 'cement' OR 
                                          `stc_merchant_specially_known_for` REGEXP 'bar' OR 
                                          `stc_merchant_specially_known_for` REGEXP 'rod'
                                        ORDER BY `stc_merchant_name` ASC
                                      ");
                                      if(mysqli_num_rows($getmerchantqry)>0){
                                        echo '<option selected>Please Select Merchant</option>';
                                        foreach($getmerchantqry as $merchantdetrow){
                                          echo '<option value="'.$merchantdetrow['stc_merchant_id'].'">'.$merchantdetrow['stc_merchant_name'].'</option>';
                                        }
                                      }else{
                                        echo '<option value="NA">No Merchant Found</option>';
                                      }
                                    ?>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Purchase Refrence No/ Challan No/ Invoice No
                                    </h5>
                                    <input
                                      name="silentpurchaserefno"
                                      type="text"
                                      class="form-control validate silentpurchaserefno"
                                      placeholder="Enter Reference/ Challan/ Invoice No"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Purchase Refrence Date/ Challan Date/ Invoice Date
                                    </h5>
                                    <input
                                      name="silentpurchaserefdate"
                                      type="date"
                                      class="form-control validate silentpurchaserefdate"
                                      placeholder="Enter Reference/ Challan/ Invoice Date"
                                      value="<?php echo $newDate;?>"
                                    />
                                  </div>
                                </div>
                                <!-- product search -->
                                <div class="col-xl-12 col-md-12 col-sm-12">
                                  <table class="table table-hover ">
                                    <thead>
                                      <tr>
                                        <th scope="col" width="100%">Search By Material Name</th>
                                        <th scope="col">Search</th>
                                        <th></th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td>
                                          <input
                                            id="purchaseitemtags"
                                            data-role="tagsinput"
                                            type="text"
                                            placeholder="Material Name"
                                            class="form-control validate stcfilterbyponumber"
                                            required
                                          />
                                        </td>
                                        <td>
                                          <button type="button" name="search" class="btn btn-primary" id="purchaseitemsearch">Search</button>
                                        </td>
                                        <td>
                                          <a style="font-size: 20px;background: yellow;border-radius: 50%;padding: 5px;margin: 0;" href="#" class="upward">
                                            <i class="material-icons">arrow_upward</i>
                                          </a>
                                          <a style="font-size: 20px;background: yellow;border-radius: 50%;padding: 5px;margin: 0; display: none;" href="#" class="downward">
                                            <i class="material-icons">arrow_downward</i>
                                          </a>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                </div>    
                                <!-- search product display -->
                                <div class="row stc-trading-pd-show-ch" style="width: 100%; margin-left: -10px;">
                                </div>
                                <!-- line item table -->
                                <div class="col-xl-12 col-md-12 col-sm-12 silent-challan-show-prod">
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Purchase Remarks
                                    </h5>
                                    <textarea
                                      name="silentpurchaseremarks"
                                      class="form-control validate silentpurchaseremarks"
                                      placeholder="Enter Remarks"
                                    /></textarea>
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                  <div class="mb-3">
                                    <button type="button" name="search" class="form-control btn btn-success" id="stcpurchasesavehit">Save</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane" id="stc-show-purchase">
                        <div class="row">
                          <div class="col-12">
                            <h2 class="tm-block-title d-inline-block">Show Purchase</h2>
                          </div>
                        </div>
                        <div class="row stc-view-product-row">
                          <div class="col-xl-12 col-lg-12 col-md-12">
                            <form action="" class="stc-silent-challan-form">
                                <table class="table table-hover table-bordered table-responsive">
                                  <thead>
                                    <tr>
                                      <th scope="col">From/<br>To</th>
                                      <th scope="col" width="30%">By Purchase Number, Merchant Name</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td>                            
                                        <?php 
                                            $date = date("d-m-Y");
                                            $newDate = date('Y-m-d', strtotime($date)); 
                                            $effectiveDate = date('Y-m-d', strtotime("-3 months", strtotime($date)));
                                        ?>   
                                        <p><input type="date" value="<?php echo $effectiveDate;?>" class="form-control salebegdate"></p>
                                        <p><input type="date" value="<?php echo $newDate;?>" class="form-control saleenddate"></p>
                                      </td>
                                      <td>
                                        <input
                                          id="tags"
                                          data-role="tagsinput"
                                          type="text"
                                          placeholder="Order Number/ Merchant Name"
                                          class="form-control validate stcfilterbysearch"
                                          required
                                        />
                                      </td>
                                      <td>
                                        <button type="button" name="challansearch" class="btn btn-primary" id="purchasesearch">Search</button>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                            </form>
                          </div>
                        </div>
                        <div class="row stc-view-sale-row" style="width: auto;overflow-x: auto; white-space: nowrap;">
                          <div class="col-xl-12 col-lg-12 col-md-12">
                            <form action="" class="stc-view-silent-purchase-row-fetch">
                                <table class="table table-hover ">
                                  <tr><td>Loading....</td></tr>
                                </table>
                            </form>
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
    <!-- purchase section -->
    <script>
      $(document).ready(function(){
        // call items for purchase 
        $('#purchaseitemsearch').click(function(){
          var query = $('#purchaseitemtags').val();
          $.ajax({
            url:"../vanaheim/order_perform.php", 
            method:"POST",
            data:{search_purchase_pd_name_in:query},
            dataType:"JSON",
            success:function(data){
              // console.log(data);
              $('.stc-trading-pd-show-ch').html(data);
            }
          });
        });

        // call product line item from cart
        silent_purchase_cart();
        function silent_purchase_cart(){
          $.ajax({
            url:"../vanaheim/order_perform.php", 
            method:'POST',
            data:{stc_show_purchase_sess:1},
            dataType: 'JSON',
            success:function(data){
              // console.log(data);
                $('.silent-challan-show-prod').html(data["orderTable"]);
            }
          });
        }

        // add to purchase cart
        $('body').delegate('.add_to_silent_purchase_cart','click',function(e){
          e.preventDefault();
          var product_id        = $(this).attr("id");  
          var product_name      = $('#stcpdname'+product_id).val(); 
          var product_unit      = $('#stcpdunit'+product_id).val();  
          var product_quantity  = $('#stcpdqty'+product_id).val();  
          var product_price     = $('#stcpdprice'+product_id).val();
          var product_gst       = $('#stcpdgst'+product_id).val();
          var add_to_purchase_cart  = "addpurchasecart";
          $.ajax({  
            url:"../vanaheim/order_perform.php",  
            method:"POST",
            data:{  
                product_id            :         product_id,
                product_name          :         product_name,  
                product_unit          :         product_unit,
                product_quantity      :         product_quantity,
                product_price         :         product_price,
                product_gst           :         product_gst,
                add_purchase          :         add_to_purchase_cart  
            },  
            success:function(data){
              // console.log(data);
              silent_purchase_cart();
              alert(data);
              // console.log(data);                              
            }  
          }); 
        });  

        // quantity change Sale product session
        $(document).on('click', '.stcqtysilent', function(e){  
          e.preventDefault();
          var product_id = $(this).data("product_id");  
          var quantity = $('#stcqtysc'+product_id).val();   
          var action = "quantity_change"; 
          if(quantity != 0){  
            $.ajax({  
                url:"../vanaheim/order_perform.php",
                method:"POST",  
                data:{
                  product_id:product_id, 
                  quantity:quantity, 
                  purchase_quantity_action:action
                },  
                success:function(data){ 
                  silent_purchase_cart();
                  // console.log(data);
                }  
            });  
          }else{
            alert("You cidk!!! Enter quantity greater than 0.");
          }
        });   
      
        // Rate change Sale product session
        $(document).on('click', '.stcratesilent', function(e){  
          e.preventDefault();
            var product_id = $(this).data("product_id");  
            var price = $('#stcratesc'+product_id).val();   
            // var change_cart = $('#cartprice'+product_id).val();
            var action = "rate_change"; 
            if(price != 0){  
              $.ajax({  
                    url:"../vanaheim/order_perform.php",
                    method:"POST",  
                    data:{
                        product_id:product_id, 
                        price:price, 
                        stc_price_action:action
                    },  
                    success:function(data){ 
                     silent_purchase_cart();
                     // console.log(data);
                    }  
              });  
            }else{
              alert("You cidk!!! Enter price greater than 0.");
            }
        });   

        // delete from cart purchase product session
        $('body').delegate('.stcdelsilentbtn','click',function(e){
          e.preventDefault();
          var product_id = $(this).attr("id");
            if(confirm("Are you sure you want to remove this product?")){   
              $.ajax({  
                url:"../vanaheim/order_perform.php",  
                method:"POST",
                data:{  
                    product_id:product_id,
                    stcdelpurchaselinei:1  
                },  
                success:function(data){  
                  silent_purchase_cart();
                  alert(data);                        
                }  
              });  
            }
        });  

        // save purchase to db
        $(document).on('click', '#stcpurchasesavehit', function(e){
          e.preventDefault();
          var merchant_name      = $('.stc-silent-merchant-name').val();
          var refrence_no   = $('.silentpurchaserefno').val();
          var refrence_date       = $('.silentpurchaserefdate').val();
          var remarks       = $('.silentpurchaseremarks').val();
          $.ajax({  
            url       : "../vanaheim/order_perform.php", 
            method    : "POST",  
            data      : {
              merchant_name:merchant_name,
              refrence_no:refrence_no,
              refrence_date:refrence_date,
              remarks:remarks,
              save_purchase_action : 1
            },
            // dataType: `JSON`,
            success   : function(data){
             // console.log(data);
             var response=data.trim();
             if(response=="Purchase Added Successfully!!!"){
              alert(data);
              window.location.reload();
             }else{
              alert(data);
             }
            }
          });
        });

        // search purchase
        $('body').delegate('#purchasesearch', 'click', function(e){
          e.preventDefault();
          var search=$('.stcfilterbysearch').val();
          var begdate=$('.salebegdate').val();
          var enddate=$('.saleenddate').val();
          $.ajax({
            url       : "../vanaheim/order_perform.php",
            method    : 'POST',
            data      : {
              stc_show_purchase:1,
              begdate:begdate,
              enddate:enddate,
              search:search
            },
            dataType  : 'JSON',
            success   : function(data){
              // console.log(data);
                $('.stc-view-silent-purchase-row-fetch').html(data);
            }
          });
        });

        // call purchase items
        $('body').delegate('.stc_print_purchase_items', 'click', function(e){
          e.preventDefault();
          var purchase_id=$(this).attr('id');
          $.ajax({  
              url     :"../vanaheim/order_perform.php", 
              method  :"POST",  
              data    :{
              purchase_id:purchase_id,
              stc_call_purchase_items:1
            },  
            // dataType: `JSON`,
            success   :function(data){
              // console.log(data);
              // alert(data);
              $('.stc-purchase-show-items').html(data);
              $('.stc-purchase-items-model').modal("show");
            }  
          });
        });

        // calledit modal
        $('body').delegate('.stc_tra_purchase_edit', 'click', function(e){
          e.preventDefault();
          var purchase_id=$(this).attr("id");
          $.ajax({
            url       : "../vanaheim/order_perform.php",
            method    : "POST",
            data      : {
              stc_trading_editp_call:1,
              purchase_id:purchase_id
            },
            dataType  : "JSON",
            success   : function(response_edit){
              // console.log(response_edit);
              $('#stc-purchase-customer-id-edit').val(response_edit['purchase_id']);
              $('.stc-purchase-customer-items-show').html(response_edit['purchase_items']);
              $('.stc-purchase-edit-model').modal('show');
            }
          });
        });

        // add item on edit
        $('body').delegate('.stc-trading-customer-add-btn-edit', 'click', function(e){
          e.preventDefault();
          sale_id=$('#stc-purchase-customer-id-edit').val();
          item_id=$('.stc-trading-customer-add-item-edit').val();
          item_unit=$('.stc-trading-customer-add-unit-edit').val();
          item_qty=$('.stc-trading-customer-add-qty-edit').val();
          item_rate=$('.stc-trading-customer-add-rate-edit').val();
          $.ajax({
            url       : "../vanaheim/order_perform.php",
            method    : "POST",
            data      : {
              stc_trading_edit_padd_item:1,
              sale_id:sale_id,
              item_id:item_id,
              item_unit:item_unit,
              item_qty:item_qty,
              item_rate:item_rate
            },
            success   : function(response_edit){
              // console.log(response_edit);
              var response=response_edit.trim();
              if(response=="Item Added Successfully!!!"){
                alert(response_edit);
                window.location.reload();
              }else{
                alert(response_edit);
              }
            }
          });
        });

        // item update
        $('body').delegate(".stc-customer-update-item-edit", 'click', function(e){
          e.preventDefault();
          var item_id=$(this).attr('id');
          var item_qty=$('#stc-trading-item-qtysc'+item_id).val();
          var item_rate=$('#stc-trading-item-ratesc'+item_id).val();
          var item_gst=$('#stc-trading-item-gstsc'+item_id).val();
          $.ajax({
            url       : "../vanaheim/order_perform.php",
            method    : "POST",
            data      : {
              stc_trading_edit_pupdate:1,
              item_id:item_id,
              item_qty:item_qty,
              item_rate:item_rate,
              item_gst:item_gst
            },
            success   : function(response_edit){
              // console.log(response_edit);
              var response=response_edit.trim();
              if(response=="Item Updated Successfully."){
                alert(response_edit);
                window.location.reload();
              }else{
                alert(response_edit);
              }
            }
          });
        });

        // remove item
        $('body').delegate('.stc-customer-remove-edit', 'click', function(e){
          e.preventDefault();
          $(this).hide();
          var item_id=$(this).attr('id');
          $.ajax({
            url       : "../vanaheim/order_perform.php",
            method    : "POST",
            data      : {
              stc_trading_edit_premove:1,
              item_id:item_id
            },
            success   : function(response_edit){
              // console.log(response_edit);
              var response=response_edit.trim();
              if(response=="Item Removed Successfully."){
                alert(response_edit);
                window.location.reload();
              }else{
                alert(response_edit);
              }
            }
          });
        });

        // toggle payment model
        $('body').delegate('.stc_print_payment', 'click', function(e){
          e.preventDefault();
          var purchase_id = $(this).attr('id');
          var purchase_value = $(this).attr('pay-val');
          $('.stc-purchase-payment-for').val(purchase_id);
          $('.stc-purchase-payment-hidden-value').val(purchase_value);
          $('#stc-purchase-payment-hidden-value').val(purchase_value);
          $('.stc-purchase-payment-model').modal('show');
        });

        // set payment
        $('body').delegate('.stc-purchase-pay-save', 'click', function(e){
          e.preventDefault();
          var purchase_id = $('.stc-purchase-payment-for').val();
          var payment_type = $('.stc-purchase-payment-type').val();
          var payment_amount = $('.stc-purchase-payment-amount').val();
          var payment_remarks = $('.stc-purchase-payment-remarks').val();
          var purchase_amount = $('#stc-purchase-payment-hidden-value').val();
          $.ajax({
            url     : "../vanaheim/order_perform.php", 
            method  : "POST",
            data    : {
              purchase_id:purchase_id,
              payment_type:payment_type,
              payment_amount:payment_amount,
              payment_remarks:payment_remarks,
              purchase_amount:purchase_amount,
              stc_payment_set:1
            },
            success : function(response_payment){
              var response=response_payment.trim();
              if(response=="Payment Done!!!"){
                alert(response_payment);
                $('.stc-purchase-payment-model').modal('hide');
              }else{
                alert(response_payment);
              }
            }
          });
        });
      });
    </script>
  </body>
  </html>
<div class="modal fade bd-example-modal-xl stc-purchase-items-model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Purchase Line Items</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-purchase-show-items">
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" class="stc-hidden-invoice-id">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade bd-example-modal-xl stc-purchase-payment-model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Purchase Payment</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mx-auto">
            <h5>Payment For</h5>
            <input type="text" class="form-control stc-purchase-payment-for" disabled>
            <input type="hidden" id="stc-purchase-payment-hidden-value">
          </div>
          <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mx-auto">
            <h5>Payment Type</h5>
            <select type="text" class="form-control stc-purchase-payment-type"
            ><option value="Cash">Cash</option>
              <option value="Account">Account</option>
            </select>
          </div>
          <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mx-auto">
            <h5>Purchase Amount</h5>
            <input type="number" class="form-control stc-purchase-payment-hidden-value" placeholder="Purchase Amount" disabled>
          </div>
          <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mx-auto">
            <h5>Payment Amount</h5>
            <input type="number" class="form-control stc-purchase-payment-amount" placeholder="Enter Amount">
          </div>
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
            <h5>Payment Remarks</h5>
            <textarea class="form-control stc-purchase-payment-remarks" placeholder="Enter Remarks"
            ></textarea>
          </div>
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
            <button type="submit" class="btn btn-success form-control stc-purchase-pay-save">Save</button>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" class="stc-hidden-invoice-id">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade bd-example-modal-xl stc-purchase-edit-model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Sale</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
            <input type="hidden" id="stc-purchase-customer-id-edit">
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
            <h5>Purchase Items</h5>
            <table class="table table-hover table-bordered table-responsive">
              <thead>
                <tr>
                  <th class="text-center">Items</th>
                  <th class="text-center">Unit</th>
                  <th class="text-center">Qty</th>
                  <th class="text-center">Rate</th>
                  <th class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                    <td class="text-center">
                      <select class="stc-trading-customer-add-item-edit form-control">
                      <?php include_once("../../MCU/db.php");
                        $odin_callpdqry=mysqli_query($con, "
                          SELECT 
                            `stc_trading_product_id`,
                            `stc_trading_product_title`
                          FROM 
                            `stc_trading_product`
                          ORDER BY `stc_trading_product_title` ASC
                        ");
                        foreach($odin_callpdqry as $odin_callpdrow){
                            echo '<option value="'.$odin_callpdrow['stc_trading_product_id'].'" selected>'.$odin_callpdrow['stc_trading_product_title'].'</option>';
                        }
                      ?>
                      </select>
                    </td>
                    <td class="text-center">
                      <select class="form-control stc-trading-customer-add-unit-edit">
                        <option value="BAG">BAG</option>
                        <option value="KG">KG</option>
                        <option value="MT">MT</option>
                        <option value="MTR">MTR</option>
                        <option value="NOS" selected>NOS</option>
                        <option value="PKT">PKT</option>
                        <option value="ROLL">ROLL</option>
                        <option value="SQMT">SQMT</option>
                      </select>
                    </td>
                    <td class="text-center">
                      <input type="number" class="form-control stc-trading-customer-add-qty-edit" placeholder="Enter Quantity">
                    </td>
                    <td class="text-center">
                      <input type="number" class="form-control stc-trading-customer-add-rate-edit" placeholder="Enter Rate">
                    </td>
                    <td class="text-center">
                      <a 
                        href="#" 
                        class="stc-trading-customer-add-btn-edit btn btn-success"
                      ><i class="fa fa-plus-circle"></i></a>
                    </td>
                </tr>
              </tbody>
            </table>
            <table class="table table-hover table-bordered table-responsive">
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th class="text-center">Items</th>
                  <th class="text-center">Unit</th>
                  <th class="text-center">Qty</th>
                  <th class="text-center">Rate</th>
                  <th class="text-center">Amount</th>
                  <th class="text-center">Action</th>
                </tr>
              </thead>
              <tbody class="stc-purchase-customer-items-show">
                <tr>
                    <td colspan="6" class="text-center">No Item Found</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- <div class="modal-footer">
        <input type="hidden" class="stc-hidden-invoice-id">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div> -->
    </div>
  </div>
</div>