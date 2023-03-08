<?php
ini_set("session.gc_maxlifetime", 21600);
session_set_cookie_params(21600);
session_start();
if(empty(@$_SESSION['stc_electro_user_id'])){
    header('location:../index.html');
}


if($_SESSION['stc_electro_user_for']>=2 || $_SESSION['stc_electro_user_for']==9){}else{
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
      STC Electronics || Order
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
        <div class="logo"><a href="http://www.creative-tim.com" class="simple-text logo-normal">
            STC Electronics
          </a>
        </div>
        <div class="sidebar-wrapper">
          <ul class="nav">
            <li class="nav-item ">
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
            <li class="nav-item active">
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
              <div class="col-lg-12 col-md-12">
                <div class="card">
                  <div class="card-header card-header-tabs card-header-primary">
                    <div class="nav-tabs-navigation">
                      <div class="nav-tabs-wrapper">
                        <span class="nav-tabs-title">Tasks:</span>
                        <ul class="nav nav-tabs" data-tabs="tabs">
                          <li class="nav-item">
                            <a class="nav-link" href="#stc-create-purchase" data-toggle="tab">
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
                          <li class="nav-item">
                            <a class="nav-link" href="#stc-create-sale" data-toggle="tab">
                              <i class="material-icons">add_circle</i> Create Sale
                              <div class="ripple-container"></div>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link active" href="#stc-show-sale" data-toggle="tab">
                              <i class="material-icons">visibility</i> Show Sale
                              <div class="ripple-container"></div>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="tab-content">
                      <div class="tab-pane" id="stc-create-purchase">
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
                                      >Party Name
                                    </h5>
                                    <input
                                      name="silentpurchasepartyname"
                                      type="text"
                                      class="form-control validate silentpurchasepartyname"
                                      placeholder="Enter Vendor/Party Name"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Invoice/Challan/Refrence No
                                    </h5>
                                    <input
                                      name="silentpurchaserefrno"
                                      type="text"
                                      class="form-control validate silentpurchaserefrno"
                                      placeholder="Enter Invoice/Challan/Refrence No"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Invoice/Challan/Refrence Date
                                    </h5>
                                    <input
                                      name="silentpurchaserefrdate"
                                      type="date"
                                      class="form-control validate silentpurchaserefrdate"
                                      placeholder="Enter Invoice/Challan/Refrence Date"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Material Details
                                    </h5>
                                    <input
                                      name="silentpurchasematerialdetails"
                                      type="text"
                                      class="form-control validate silentpurchasematerialdetails"
                                      placeholder="Enter Material Details"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-4 col-md-3 col-lg-3">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Material Unit
                                    </h5>
                                    <select
                                      name="silentpurchasematerialunit"
                                      type="text"
                                      class="form-control validate silentpurchasematerialunit"
                                    /><option value="Nos">Nos</option>
                                      <option value="Set">Set</option>
                                      <option value="Bag">Bag</option>
                                      <option value="Kgs">Kgs</option>
                                      <option value="Sqmt">Sqmt</option>
                                      <option value="Mtr">Mtr</option>
                                      <option value="Mt">Mt</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-sm-4 col-md-3 col-lg-3">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Material Tax
                                    </h5>
                                    <select
                                      name="silentpurchasematerialtax"
                                      type="text"
                                      class="form-control validate silentpurchasematerialtax"
                                    /><option value="5">5%</option>
                                      <option value="12">12%</option>
                                      <option value="18" selected>18%</option>
                                      <option value="28">28%</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-sm-4 col-md-6 col-lg-6">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Material Quantity
                                    </h5>
                                    <input
                                      name="silentpurchasematerialqty"
                                      type="number"
                                      class="form-control validate silentpurchasematerialqty"
                                      placeholder="Enter Material Quantity"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-4 col-md-6 col-lg-6">
                                  <div class="mb-3">
                                    <h5
                                      for="number"
                                      >Material Basic Rate
                                    </h5>
                                    <input
                                      name="silentpurchasematerialrate"
                                      type="number"
                                      class="form-control validate silentpurchasematerialrate"
                                      placeholder="Enter Material Rate"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Action
                                    </h5><button type="button" name="search" class="form-control btn btn-primary" id="stcpurchasematerialadd">Add</button>
                                  </div>
                                </div>
                                <!-- line item table -->
                                <div class="col-xl-12 col-md-12 col-sm-12 silent-purchase-show-prod">
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Remarks
                                    </h5>
                                    <textarea
                                      name="silentpurchaseremarks"
                                      type="text"
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
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
                            <div class="tm-bg-primary-dark tm-block tm-block-h-auto" >
                              <div class="row">
                                <div class="col-12">
                                  <h2 class="tm-block-title d-inline-block">Show Purchase</h2>
                                </div>
                              </div>
                              <div class="row stc-view-product-row">
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                  <form action="" class="stc-silent-challan-form">
                                      <table class="table table-hover table-responsive">
                                        <thead>
                                          <tr>
                                            <th scope="col">From/<br>To</th>
                                            <th scope="col" width="30%">By Purchase Number, Party Name, Invoice No, Item Name</th>
                                            <th scope="col">Search</th>
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
                                              <p><input type="date" value="<?php echo $effectiveDate;?>" class="form-control pbegdate"></p>
                                              <p><input type="date" value="<?php echo $newDate;?>" class="form-control penddate"></p>
                                              <p><a href="#" id="purchasedatefilt">
                                                  <i style="font-size: 20px;background: #0c0c1b;border-radius: 50%;padding: 5px;margin: 0;color: #77ffd3;float: right;" class="fa fa-arrow-down"></i>
                                                </a>
                                              </p>
                                            </td>
                                            <td>
                                              <input
                                                id="tags"
                                                data-role="tagsinput"
                                                type="text"
                                                placeholder="Challan Number/ Customer/Status"
                                                class="form-control validate stcfilterbyponumber"
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
                      <div class="tab-pane" id="stc-create-sale">
                        <div class="row">
                          <div class="col-12">
                            <h2 class="tm-block-title d-inline-block">Create Sale</h2>
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
                                      >Party Name
                                    </h5>
                                    <input
                                      name="silentsalepartyname"
                                      type="text"
                                      class="form-control validate silentsalepartyname"
                                      placeholder="Enter Customer/Party Name"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Invoice/Challan/Refrence No
                                    </h5>
                                    <input
                                      name="silentsalerefrno"
                                      type="text"
                                      class="form-control validate silentsalerefrno"
                                      placeholder="Enter Invoice/Challan/Refrence No"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Invoice/Challan/Refrence Date
                                    </h5>
                                    <input
                                      name="silentsalerefrdate"
                                      type="date"
                                      class="form-control validate silentsalerefrdate"
                                      placeholder="Enter Invoice/Challan/Refrence Date"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Material Details
                                    </h5>
                                    <input
                                      name="silentsalematerialdetails"
                                      type="text"
                                      class="form-control validate silentsalematerialdetails"
                                      placeholder="Enter Material Details"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-4 col-md-3 col-lg-3">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Material Unit
                                    </h5>
                                    <select
                                      name="silentsalematerialunit"
                                      type="text"
                                      class="form-control validate silentsalematerialunit"
                                    /><option value="Nos">Nos</option>
                                      <option value="Set">Set</option>
                                      <option value="Bag">Bag</option>
                                      <option value="Kgs">Kgs</option>
                                      <option value="Sqmt">Sqmt</option>
                                      <option value="Mtr">Mtr</option>
                                      <option value="Mt">Mt</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-sm-4 col-md-3 col-lg-3">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Material Tax
                                    </h5>
                                    <select
                                      name="silentsalematerialtax"
                                      type="text"
                                      class="form-control validate silentsalematerialtax"
                                    /><option value="5">5%</option>
                                      <option value="12">12%</option>
                                      <option value="18" selected>18%</option>
                                      <option value="28">28%</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="col-sm-4 col-md-6 col-lg-6">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Material Quantity
                                    </h5>
                                    <input
                                      name="silentsalematerialqty"
                                      type="number"
                                      class="form-control validate silentsalematerialqty"
                                      placeholder="Enter Material Quantity"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-4 col-md-6 col-lg-6">
                                  <div class="mb-3">
                                    <h5
                                      for="number"
                                      >Material Basic Rate
                                    </h5>
                                    <input
                                      name="silentsalematerialrate"
                                      type="number"
                                      class="form-control validate silentsalematerialrate"
                                      placeholder="Enter Material Rate"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Action
                                    </h5><button type="button" name="search" class="form-control btn btn-primary" id="stcsalematerialadd">Add</button>
                                  </div>
                                </div>
                                <!-- line item table -->
                                <div class="col-xl-12 col-md-12 col-sm-12 silent-sale-show-prod">
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Order By
                                    </h5>
                                    <input
                                      name="silentsaleorderby"
                                      type="text"
                                      class="form-control validate silentsaleorderby"
                                      placeholder="Enter Order Person"
                                    />
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                  <div class="mb-3">
                                    <h5
                                      for="name"
                                      >Remarks
                                    </h5>
                                    <textarea
                                      name="silentsaleremarks"
                                      type="text"
                                      class="form-control validate silentsaleremarks"
                                      placeholder="Enter Remarks"
                                    /></textarea>
                                  </div>
                                </div>
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                  <div class="mb-3">
                                    <button type="button" name="search" class="form-control btn btn-success" id="stcsalesavehit">Save</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="tab-pane active" id="stc-show-sale">
                        <div class="row">
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
                            <div class="tm-bg-primary-dark tm-block tm-block-h-auto" >
                              <div class="row">
                                <div class="col-12">
                                  <h2 class="tm-block-title d-inline-block">Show Sale</h2>
                                </div>
                              </div>
                              <div class="row stc-view-sale-row">
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                  <form action="" class="stc-silent-sale-form">
                                      <table class="table table-hover table-responsive">
                                        <thead>
                                          <tr>
                                            <th scope="col">From/<br>To</th>
                                            <th scope="col" width="30%">By Sale Number, Customer Name, Invoice No, Item Name</th>
                                            <th scope="col">Search</th>
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
                                              <p><a href="#" id="saledatefilt">
                                                  <i style="font-size: 20px;background: #0c0c1b;border-radius: 50%;padding: 5px;margin: 0;color: #77ffd3;float: right;" class="fa fa-arrow-down"></i>
                                                </a>
                                              </p>
                                            </td>
                                            <td>
                                              <input
                                                id="saletags"
                                                data-role="tagsinput"
                                                type="text"
                                                placeholder="Sale Number/ Customer/Status"
                                                class="form-control validate stcfilterbysale"
                                                required
                                              />
                                            </td>
                                            <td>
                                              <button type="button" name="challansearch" class="btn btn-primary" id="salesearch">Search</button>
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                  </form>
                                </div>
                              </div>
                              <div class="row stc-view-sale-row" style="width: auto;overflow-x: auto; white-space: nowrap;">
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                  <form action="" class="stc-view-silent-sale-row-fetch">
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
        </div>
      </div>
    </div>
    
    <!--   Core JS Files   -->
    <script src="../assets/js/core/jquery.min.js"></script>
    <script src="../assets/js/core/popper.min.js"></script>
    <script src="../assets/js/core/bootstrap-material-design.min.js"></script>
    <script src="../assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
    <script src="../assets/demo/demo.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
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
    <script>
      $(document).ready(function(){
        var todaydate = new Date();
        var day = todaydate.getDate();
        var month = todaydate.getMonth();
        var nmonth = todaydate.getMonth() + 1;
        var year = todaydate.getFullYear();
        var begdateon = year + "/" + month + "/" + day;
        var enddateon = year + "/" + nmonth + "/" + day;
        // purchase
        stc_call_purchase(begdateon, enddateon);
        function stc_call_purchase(begdateon, enddateon){
          var begdate=begdateon;
          var enddate=enddateon; 
          $.ajax({
            url       : "../vanaheim/sale-purchase-done.php", 
            method    : 'POST',
            data      : {
              stccallss:1,
              begdate:begdate,
              enddate:enddate
            },
            dataType: 'JSON',
            success   : function(data){
              // console.log(data);
                $('.stc-view-silent-purchase-row-fetch').html(data);
            }
          });     
        }

        // using date filter
        $('body').delegate('#purchasedatefilt', 'click', function(e){
          e.preventDefault();
          begdateon=$('.pbegdate').val();
          enddateon=$('.penddate').val();
          stc_call_purchase(begdateon, enddateon);
        });

        function load_purchase(query, purchasebegdate, purchaseenddate) {
            $.ajax({
              url       : "../vanaheim/sale-purchase-done.php",
              method    : "POST",
              data      : {
                stcfilterpurchase:query,
                purchasebegdate:purchasebegdate,
                purchaseenddate:purchaseenddate
              },
              dataType:"json",
              success   : function(data){
                // console.log(data);
               $('.stc-view-silent-purchase-row-fetch').html(data);
              }
            });
        }

        // using search filter
        $('#purchasesearch').click(function(){
            var query = $('#tags').val();
            var purchasebegdate=$('.pbegdate').val();
            var purchaseenddate=$('.penddate').val();
            load_purchase(query, purchasebegdate, purchaseenddate);
        });

        // stcpaymentmodel
        $('body').delegate('.stc-purchase-pay-hit', 'click', function(e){
          e.preventDefault();
          var line_item_id=$(this).attr("id");
          $('.stc-update-purchase-payment-model').modal("show");
          $('.stc-hidden-line-item-id').val(line_item_id);
        });

        // update payment
        $('body').delegate('#stc-payment-update-go', 'click', function(e){
          e.preventDefault();
          var item_id=$('.stc-hidden-line-item-id').val();
          var pay_val=$('.stc-payment-val').val();
          var pay_type=$('.stc-payment-type').val();
          $.ajax({
            url     : "../vanaheim/sale-purchase-done.php",
            method  : "POST",  
            data    : {
              stc_update_payment_hit:1,
              item_id:item_id,
              pay_val:pay_val,
              pay_type:pay_type
            },
            // dataType : "JSON",
            success : function(response_payment){
              var response=response_payment.trim();
              if(response=="Payment updated succesfully."){
                alert(response);
                $('.stc-update-purchase-payment-model').modal("hide");
                stc_call_purchase(begdateon, enddateon);
              }else{
                alert(response);
              }
            }
          });        
        });
      });
    </script>
    <!-- purchase section -->
    <script>
      $(document).ready(function(){   
        // show purchase items sesson cart
        silent_purchase_call();
        function silent_purchase_call(){
          $.ajax({
            url:"../vanaheim/sale-purchase-done.php", 
            method:'POST',
            data:{stc_show_purchase_sess:1},
            dataType: 'JSON',
            success:function(data){
              // console.log(data);
                $('.silent-purchase-show-prod').html(data["orderTable"]);
            }
          });
        }
        
        // add cart to sale session
        $('body').delegate('#stcpurchasematerialadd', 'click', function(e){
         e.preventDefault(); 
          var product_name      = $('.silentpurchasematerialdetails').val(); 
          var product_unit      = $('.silentpurchasematerialunit').val(); 
          var product_tax       = $('.silentpurchasematerialtax').val(); 
          var product_qty       = $('.silentpurchasematerialqty').val(); 
          var product_rate      = $('.silentpurchasematerialrate').val(); 
          $.ajax({  
            url:"../vanaheim/sale-purchase-done.php",  
            method:"POST",
            data:{  
                product_name  : product_name,
                product_unit  : product_unit,
                product_tax   : product_tax,
                product_qty   : product_qty,
                product_rate  : product_rate,
                add_purchase  : 1  
            },  
            success:function(data){
              silent_purchase_call();
              alert(data);
            }  
          }); 
        });  

        // delete from cart purchase product session
        $('body').delegate('.stcdelsilentbtn','click',function(e){
          e.preventDefault();
          var product_id = $(this).attr("id");
            if(confirm("Are you sure you want to remove this product?")){   
              $.ajax({  
                url     :"../vanaheim/sale-purchase-done.php", 
                method  :"POST",
                data    :{  
                    product_id:product_id,
                    stcdelpurchaselinei:1  
                },  
                success :function(data){  
                  silent_purchase_call();
                  alert(data);                        
                }  
              });  
            }
        }); 
        
        // save sale order to db
        $(document).on('click', '#stcpurchasesavehit', function(e){
          e.preventDefault();
          var purchasepartyname     = $('.silentpurchasepartyname').val();
          var purchaserefrno        = $('.silentpurchaserefrno').val();
          var purchaserefrdate      = $('.silentpurchaserefrdate').val();
          var purchaseremarks       = $('.silentpurchaseremarks').val();
          $.ajax({  
            url       : "../vanaheim/sale-purchase-done.php",
            method    : "POST",  
            data      : {
              purchasepartyname:purchasepartyname,
              purchaserefrno:purchaserefrno,
              purchaserefrdate:purchaserefrdate,
              purchaseremarks:purchaseremarks,
              save_purchase_action : 1
            },
            // dataType: `JSON`,
            success   : function(data){
             // console.log(data);
             var response=data.trim();
             if(response=="Purchase Item Done!!!"){
              alert(data);
              window.location.reload();
             }else{
              alert(data);
             }
            }
          });
        });
      });
    </script>
    <!-- sale section -->
    <script>
      $(document).ready(function(){   
        // show purchase items sesson cart
        silent_sale_call();
        function silent_sale_call(){
          $.ajax({
            url:"../vanaheim/sale-purchase-done.php", 
            method:'POST',
            data:{stc_show_sale_sess:1},
            dataType: 'JSON',
            success:function(data){
              // console.log(data);
                $('.silent-sale-show-prod').html(data["orderTable"]);
            }
          });
        }
        
        // add cart to sale session
        $('body').delegate('#stcsalematerialadd', 'click', function(e){
         e.preventDefault(); 
          var product_name      = $('.silentsalematerialdetails').val(); 
          var product_unit      = $('.silentsalematerialunit').val(); 
          var product_tax       = $('.silentsalematerialtax').val(); 
          var product_qty       = $('.silentsalematerialqty').val(); 
          var product_rate      = $('.silentsalematerialrate').val(); 
          $.ajax({  
            url:"../vanaheim/sale-purchase-done.php",  
            method:"POST",
            data:{  
                product_name  : product_name,
                product_unit  : product_unit,
                product_tax   : product_tax,
                product_qty   : product_qty,
                product_rate  : product_rate,
                add_sale  : 1  
            },  
            success:function(data){
              silent_sale_call();
              alert(data);
            }  
          }); 
        });  

        // delete from cart purchase product session
        $('body').delegate('.stcdelsilentsalebtn','click',function(e){
          e.preventDefault();
          var product_id = $(this).attr("id");
            if(confirm("Are you sure you want to remove this product?")){   
              $.ajax({  
                url     :"../vanaheim/sale-purchase-done.php", 
                method  :"POST",
                data    :{  
                    product_id:product_id,
                    stcdelsalelinei:1  
                },  
                success :function(data){  
                  silent_sale_call();
                  alert(data);                        
                }  
              });  
            }
        }); 
        
        // save sale order to db
        $(document).on('click', '#stcsalesavehit', function(e){
          e.preventDefault();
          var salepartyname     = $('.silentsalepartyname').val();
          var salerefrno        = $('.silentsalerefrno').val();
          var salerefrdate      = $('.silentsalerefrdate').val();
          var saleorderby       = $('.silentsaleorderby').val();
          var saleremarks       = $('.silentsaleremarks').val();
          $.ajax({  
            url       : "../vanaheim/sale-purchase-done.php",
            method    : "POST",  
            data      : {
              salepartyname:salepartyname,
              salerefrno:salerefrno,
              salerefrdate:salerefrdate,
              saleorderby:saleorderby,
              saleremarks:saleremarks,
              save_sale_action : 1
            },
            // dataType: `JSON`,
            success   : function(data){
             // console.log(data);
             var response=data.trim();
             if(response=="Sale Item Done!!!"){
              alert(data);
              window.location.reload();
             }else{
              alert(data);
             }
            }
          });
        });
      });
    </script>
    <script>
      $(document).ready(function(){
        var todaydate = new Date();
        var day = todaydate.getDate();
        var month = todaydate.getMonth();
        var nmonth = todaydate.getMonth() + 1;
        var year = todaydate.getFullYear();
        var begdateon = year + "/" + month + "/" + day;
        var enddateon = year + "/" + nmonth + "/" + day;
        // sale
        stc_call_sale(begdateon, enddateon);
        function stc_call_sale(begdateon, enddateon){
          var begdate=begdateon;
          var enddate=enddateon; 
          $.ajax({
            url       : "../vanaheim/sale-purchase-done.php", 
            method    : 'POST',
            data      : {
              stccallsale:1,
              begdate:begdate,
              enddate:enddate
            },
            dataType: 'JSON',
            success   : function(data){
              // console.log(data);
                $('.stc-view-silent-sale-row-fetch').html(data);
            }
          });     
        }

        // using datefilter
        $('body').delegate('#saledatefilt', 'click', function(e){
          e.preventDefault();
          stc_call_sale(begdateon, enddateon);
        });

        function load_sale(query, salebegdate, saleenddate) {
            $.ajax({
              url       : "../vanaheim/sale-purchase-done.php",
              method    : "POST",
              data      : {
                stcfiltersale:query,
                salebegdate:salebegdate,
                saleenddate:saleenddate
              },
              dataType: 'JSON',
              success   : function(data){
                // console.log(data);
               $('.stc-view-silent-sale-row-fetch').html(data);
              }
            });
        }

        // using search filter
        $('#salesearch').click(function(){
            var query = $('#saletags').val();
            var salebegdate=$('.salebegdate').val();
            var saleenddate=$('.saleenddate').val();
            load_sale(query, salebegdate, saleenddate);
        });

        // stcpaymentmodel
        $('body').delegate('.stc-sale-pay-hit', 'click', function(e){
          e.preventDefault();
          var line_item_id=$(this).attr("id");
          $('.stc-hidden-sale-line-item-id').val(line_item_id);
          $('.stc-update-sale-payment-model').modal("show");
        });

        // update payment
        $('body').delegate('#stc-sale-payment-update-go', 'click', function(e){
          e.preventDefault();
          var item_id=$('.stc-hidden-sale-line-item-id').val();
          var pay_val=$('.stc-sale-payment-val').val();
          var pay_type=$('.stc-sale-payment-type').val();
          $.ajax({
            url     : "../vanaheim/sale-purchase-done.php",
            method  : "POST",  
            data    : {
              stc_sale_update_payment_hit:1,
              item_id:item_id,
              pay_val:pay_val,
              pay_type:pay_type
            },
            // dataType : "JSON",
            success : function(response_payment){
              var response=response_payment.trim();
              if(response=="Payment updated succesfully."){
                alert(response);
                $('.stc-update-sale-payment-model').modal("hide");
                stc_call_sale(begdateon, enddateon);
              }else{
                alert(response);
              }
            }
          });        
        });
      });
    </script>
  </body>
  </html>
  <div class="modal fade bd-example-modal-xl stc-update-purchase-payment-model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Payment Update</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                  <h5>
                    Payment Type
                  </h5>
                  <select class="form-control stc-payment-type">
                    <option value="1">Account</option>
                    <option value="2">Advance</option>
                    <option value="3">Cash</option>
                  </select>
                </div>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                  <h5>
                    Payment Amount
                  </h5>
                  <input type="number" class="form-control stc-payment-val" placeholder="Enter Amount">
                </div>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                  <button type="button" class="form-control btn btn-success" id="stc-payment-update-go">Save</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" class="stc-hidden-line-item-id">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade bd-example-modal-xl stc-update-sale-payment-model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Payment Update</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                  <h5>
                    Payment Type
                  </h5>
                  <select class="form-control stc-sale-payment-type">
                    <option value="1">Account</option>
                    <option value="2">Advance</option>
                    <option value="3">Cash</option>
                  </select>
                </div>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                  <h5>
                    Payment Amount
                  </h5>
                  <input type="number" class="form-control stc-sale-payment-val" placeholder="Enter Amount">
                </div>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                  <button type="button" class="form-control btn btn-success" id="stc-sale-payment-update-go">Save</button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" class="stc-hidden-sale-line-item-id">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>