<?php
ini_set("session.gc_maxlifetime", 21600);
session_set_cookie_params(21600);
session_start();
if(empty(@$_SESSION['stc_groceries_user_id'])){
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
      STC Groceries || Customer
    </title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <!--     Fonts and icons     -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- CSS Files -->
    <link href="../assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link href="../assets/demo/demo.css" rel="stylesheet" />
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" crossorigin="anonymous"> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput-typeahead.css" /> -->
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
            STC Groceries
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
            <li class="nav-item ">
              <a class="nav-link" href="./product.php">
                <i class="material-icons">shop</i>
                <p>Product</p>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="./merchant.php">
                <i class="material-icons">shop</i>
                <p>Merchant</p>
              </a>
            </li>
            <li class="nav-item ">
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
            <li class="nav-item active">
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
                <p>Daily Expense</p>
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
                            <a class="nav-link active" href="#stc-create-customer" data-toggle="tab">
                              <i class="material-icons">add_circle</i> Create Customer
                              <div class="ripple-container"></div>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" href="#stc-show-customer" data-toggle="tab">
                              <i class="material-icons">visibility</i> Show Customer
                              <div class="ripple-container"></div>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="tab-content">
                      <div class="tab-pane active" id="stc-create-customer">
                        <div class="row">
                          <div class="col-12">
                            <h2 class="tm-block-title d-inline-block">Create Customer</h2>
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
                                      >Customer Name
                                    </h5>
                                    <input
                                      name="silentcustomername"
                                      type="text"
                                      class="form-control validate silentcustomername"
                                      placeholder="Enter Customer Name. eg:- Adam (company Adam)"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Customer Address
                                    </h5>
                                    <textarea
                                      name="silentcustomeraddress"
                                      type="text"
                                      class="form-control validate silentcustomeraddress"
                                      placeholder="Enter Customer Address"
                                    /></textarea>
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Contact No
                                    </h5>
                                    <input
                                      name="silentcustomercontactno"
                                      type="text"
                                      class="form-control validate silentcustomercontactno"
                                      placeholder="Enter Customer's 10-Digit Contact Number"
                                      maxlength="10"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Pin Code
                                    </h5>
                                    <input
                                      name="silentcustomerpincode"
                                      type="text"
                                      class="form-control validate silentcustomerpincode"
                                      placeholder="Enter Customer Pincode"
                                      maxlength="6"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >City
                                    </h5>
                                    <select
                                      name="silentcustomercity"
                                      type="text"
                                      class="form-control validate silentcustomercity"
                                    />
                                      <?php 
                                        include_once("../../MCU/db.php");
                                        $city_qry=mysqli_query($con, "
                                          SELECT * FROM `stc_city` ORDER BY `stc_city_name` ASC
                                        ");
                                        foreach($city_qry as $city_row){
                                          if($city_row['stc_city_id']==65){
                                            echo '<option value="'.$city_row['stc_city_id'].'" selected>'.$city_row['stc_city_name'].'</option>';
                                          }else{
                                            echo '<option value="'.$city_row['stc_city_id'].'">'.$city_row['stc_city_name'].'</option>';
                                          }
                                        }
                                      ?>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >State
                                    </h5>
                                    <select
                                      name="silentcustomerstate"
                                      type="text"
                                      class="form-control validate silentcustomerstate"
                                    />
                                      <?php 
                                        $state_qry=mysqli_query($con, "
                                          SELECT * FROM `stc_state` ORDER BY `stc_state_name` ASC
                                        ");
                                        foreach($state_qry as $state_row){
                                          if($state_row['stc_state_id']==16){
                                            echo '<option value="'.$state_row['stc_state_id'].'" selected>'.$state_row['stc_state_name'].'</option>';
                                          }else{
                                            echo '<option value="'.$state_row['stc_state_id'].'">'.$state_row['stc_state_name'].'</option>';
                                          }
                                        }
                                      ?>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Customer Type
                                    </h5>
                                    <select
                                      name="silentcustomertype"
                                      type="text"
                                      class="form-control validate silentcustomertype"
                                    /><option value="1">Retailer</option>
                                      <option value="2">Contractor</option>
                                      <option value="3">Walk in</option>
                                      <option value="4">Defaulter</option>
                                      <option value="5">Refered</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Remarks
                                    </h5>
                                    <textarea
                                      name="silentcustomerremarks"
                                      type="text"
                                      class="form-control validate silentcustomerremarks"
                                      placeholder="Enter Remarks"
                                    /></textarea>
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                  <div class="mb-3">
                                    <button type="button" name="search" class="form-control btn btn-success" id="stccustomersavehit">Save</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane" id="stc-show-customer">
                        <div class="row">
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
                            <div class="tm-bg-primary-dark tm-block tm-block-h-auto" >
                              <div class="row">
                                <div class="col-12">
                                  <h2 class="tm-block-title d-inline-block">Show Customer</h2>
                                </div>
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                  <form action="" class="stc-silent-challan-form">
                                      <table class="table table-hover table-bordered table-responsive">
                                        <thead>
                                          <tr>
                                            <th scope="col">By Customer Name, Customer Contact Number/ Address/ Type</th>
                                            <th scope="col">Search</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <td>
                                              <input
                                                id="tags"
                                                type="text"
                                                placeholder="Enter Customer Name/ Customer Contact Number/ Address/ Type"
                                                title="Enter Customer Name, Customer Contact Number/ Address/ Type"
                                                class="form-control validate stcfilterbysearch"
                                                required
                                              />
                                            </td>
                                            <td>
                                              <button type="button" name="customersearch" class="btn btn-primary" id="customersearch">Search</button>
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                  </form>
                                </div>
                              </div>
                              <div class="row stc-view-sale-row" style="width: auto;overflow-x: auto; white-space: nowrap;">
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                  <form action="" class="stc-view-silent-customer-row-fetch">
                                      <table class="table table-hover table-bordered table-responsive">
                                        <thead>
                                          <tr>
                                            <th class="text-center" scope="col" width="20%"><b>Customer Name</b></th>
                                            <th class="text-center" scope="col"><b>Customer Address</b></th>
                                            <th class="text-center" scope="col"><b>Customer City/<br> Customer State/<br> Customer Pin Code</b></th>
                                            <th class="text-center" scope="col"><b>Customer Contact</b></th>
                                            <th class="text-center" scope="col"><b>Customer Type</b></th>
                                            <th class="text-center" scope="col"><b>Remarks</b></th>
                                            <th class="text-center" scope="col"><b>Created By</b></th>
                                            <th class="text-center" scope="col"><b>Action</b></th>
                                          </tr>
                                        </thead>
                                        <tbody class="stc-silent-show-customer">
                                          <tr><td colspan="7" class="text-center">Search Customer Here</td></tr>
                                        </tbody>
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
  <!-- customer section -->
  <script>
    $(document).ready(function(){
      
      // save customer to db
      $('body').delegate('#stccustomersavehit', 'click', function(e){
        e.preventDefault();
        var silentcustomername        = $('.silentcustomername').val();
        var silentcustomeraddress     = $('.silentcustomeraddress').val();
        var silentcustomercontactno   = $('.silentcustomercontactno').val();
        var silentcustomerpincode     = $('.silentcustomerpincode').val();
        var silentcustomercity        = $('.silentcustomercity').val();
        var silentcustomerstate       = $('.silentcustomerstate').val();
        var silentcustomertype        = $('.silentcustomertype').val();
        var silentcustomerremarks     = $('.silentcustomerremarks').val();
        $.ajax({  
          url       : "../vanaheim/customer-done.php",
          method    : "POST",  
          data      : {
            silentcustomername:silentcustomername,
            silentcustomeraddress:silentcustomeraddress,
            silentcustomercontactno:silentcustomercontactno,
            silentcustomerpincode:silentcustomerpincode,
            silentcustomercity:silentcustomercity,
            silentcustomerstate:silentcustomerstate,
            silentcustomertype:silentcustomertype,
            silentcustomerremarks:silentcustomerremarks,
            save_customer_action : 1
          },
          // dataType: `JSON`,
          success   : function(data){
           // console.log(data);
           var response=data.trim();
           if(response=="Customer Added Successfully!!!"){
            alert(data);
            window.location.reload();
           }else{
            alert(data);
           }
          }
        });
      });

      // show customer
      $('body').delegate('#customersearch', 'click', function(e){
        e.preventDefault();
        var search=$('#tags').val();
        $.ajax({
          url       : "../vanaheim/customer-done.php",
          method    : 'POST',
          data      : {
              stc_show_customer:1,
              search:search
          },
          dataType  : 'JSON',
          success   : function(data){
            // console.log(data);
              $('.stc-silent-show-customer').html(data);
          }
        });
      });

      // call modal
      $('body').delegate('.stc-tra-customer-edit-modal-req', 'click', function(e){
        e.preventDefault();
        var cust_id=$(this).attr("id");
        $.ajax({
          url       : "../vanaheim/customer-done.php",
          method    : "POST",
          data      : {
            stc_tra_cust_call:1,
            cust_id:cust_id
          },
          dataType  : "JSON",
          success   : function(response_modal){
            // console.log(response_modal);
            $('.stc-tra-edit-custid').val(response_modal['cust_id']);
            $('.stc-tra-edit-cust-title').val(response_modal['title']);
            $('.stc-tra-edit-cust-address').val(response_modal['address']);
            $('.stc-tra-edit-cust-cont-no').val(response_modal['cont_no']);
            $('.stc-tra-customer-edit-modal-res').modal('show');
          }
        });
      });


      // save edit modal
      $('body').delegate('#stc-tra-edit-save', 'click', function(e){
        e.preventDefault();
        var cust_id=$('.stc-tra-edit-custid').val();
        var cust_title=$('.stc-tra-edit-cust-title').val();
        var cust_address=$('.stc-tra-edit-cust-address').val();
        var cust_cont_no=$('.stc-tra-edit-cust-cont-no').val();
        $.ajax({
          url       : "../vanaheim/customer-done.php",
          method    : "POST",
          data      : {
            stc_tra_cust_save:1,
            cust_id:cust_id,
            cust_title:cust_title,
            cust_address:cust_address,
            cust_cont_no:cust_cont_no
          },
          dataType  : "JSON",
          success   : function(response_modal){
            // console.log(response_modal);
            var response=response_modal.trim();
            if(response=="Customer Edited Successfully!!!"){
                window.location.reload();
              alert(response_modal);
              $('.stc-tra-customer-edit-modal-res').modal('hide');
            }else{
              alert(response_modal);
            }
          }
        });
      });
    });
  </script>
  </body>
  </html>

<div class="modal fade bd-example-modal-lg stc-tra-customer-edit-modal-res" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Customer</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <h5>
                  Customer Name
                </h5>
                <input type="text" class="form-control stc-tra-edit-cust-title" placeholder="Enter Customer Name" disabled>
              </div>
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <h5>
                  Customer Address
                </h5>
                <textarea class="form-control stc-tra-edit-cust-address" placeholder="Enter Customer Address"></textarea>
              </div>
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <h5>
                  Customer Contact Number
                </h5>
                <input type="text" class="form-control stc-tra-edit-cust-cont-no" maxlength="10" placeholder="Enter Contact Number">
              </div>
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <input type="hidden" class="stc-tra-edit-custid">
                <button type="button" class="form-control btn btn-success" id="stc-tra-edit-save">Save</button>
              </div>
            </div>
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