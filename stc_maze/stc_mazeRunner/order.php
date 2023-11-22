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
          <li class="nav-item active">
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
            <div class="col-lg-12 col-md-12">
              <div class="card">
                <div class="card-header card-header-tabs card-header-primary">
                  <div class="nav-tabs-navigation">
                    <div class="nav-tabs-wrapper">
                      <span class="nav-tabs-title">Tasks:</span>
                      <ul class="nav nav-tabs" data-tabs="tabs">
                        <li class="nav-item">
                          <a class="nav-link active" href="#stc-create-challan" data-toggle="tab">
                            <i class="material-icons">add_circle</i> Create Challan
                            <div class="ripple-container"></div>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="#stc-show-challan" data-toggle="tab">
                            <i class="material-icons">visibility</i> Show Challan
                            <div class="ripple-container"></div>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="#stc-create-invoice" data-toggle="tab">
                            <i class="material-icons">add_circle</i> Create Invoice
                            <div class="ripple-container"></div>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="#stc-show-invoice" data-toggle="tab">
                            <i class="material-icons">visibility</i> Show Invoice
                            <div class="ripple-container"></div>
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="tab-pane active" id="stc-create-challan">
                      <div class="row">
                        <div class="col-12">
                          <h2 class="tm-block-title d-inline-block">Create Challan</h2>
                        </div>
                      </div>
                      <div class="row stc-silent-challan-row">
                        <div class="col-xl-6 col-md-6 col-sm-12">
                          <form action="" class="stc-add-silent-challan-form form-group"> 
                          <div class="">
                            <label
                              for="name"
                              >Silent Challan Number
                            </label>
                            <input
                              id="schallanno"
                              name="schallanno"
                              type="text"
                              placeholder="Silent Challan Number"
                              class="form-control validate"
                              disabled
                            />
                          </div>
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12">
                          <div class="">
                            <label
                              for=""
                              >Silent Challan Date
                            </label> 
                            <?php 
                                $date = date("d-m-Y");
                                $newDate = date('Y-m-d', strtotime($date)); 
                            ?>                    
                            <input 
                              type="date"
                              class="form-control validate silentchallandate"
                              data-large-mode="true"
                              value="<?php echo $newDate;?>"
                            />
                          </div>
                        </div>
                        <div class="col-xl-12 col-md-12 col-sm-12">
                          <div class="">
                            <label
                              for="name"
                              >Customer Name
                            </label>
                            <input
                              name="silentchallancustomername"
                              type="text"
                              class="form-control validate silentchallancustomername"
                              placeholder="Customer Name"
                              style="background-color: #00468b;color:white;border-radius: 15%;padding: 10px;"
                            />
                            </select>
                          </div>
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-6">
                          <div class="">
                            <label
                              for=""
                              >Customer Billing Address
                            </label>
                            <textarea
                              class="form-control validate"
                              rows="2"
                              id="silentchallancustomernilladdress"
                              placeholder="Billing Address"
                              required
                            ></textarea>
                          </div>
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-6">
                          <div class="">
                            <label
                              for=""
                              >Customer Shipping Address
                            </label>
                            <textarea
                              class="form-control validate"
                              rows="2"
                              id="silentchallancustomershippaddress"
                              placeholder="Shipping Address"
                              required
                            ></textarea>
                          </div>
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12">
                          <div class="">
                            <label
                              for=""
                              >Customer Contact number
                            </label>
                            <input
                              name="silentchallancustomercontactnumber"
                              type="text"
                              class="form-control validate silentchallancustomercontactnumber"
                              placeholder="Contact Number"
                              style="background-color: #00468b;color:white;border-radius: 15%;padding: 10px;"
                              value="NA"
                            />
                          </div>
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12">
                          <div class="">
                            <label
                              for=""
                              >Customer Order On
                            </label>
                            <input
                              name="silentchallancustomerorderon"
                              type="date"
                              min="2001-01-01" 
                              max="2050-12-31"
                              placeholder="Customer Order Date"
                              class="form-control validate silentchallancustomerorderon"
                              style="background-color: #00468b;color:white;border-radius: 15%;padding: 10px;"
                              value="<?php echo $newDate;?>"
                            />
                          </div>
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12">
                          <div class="">
                            <label
                              for=""
                              >Customer Delivered On
                            </label>
                            <input
                              id="datepicke1r"
                              name="silentchallancustomerdeliveredon"
                              type="date"
                              min="2001-01-01" 
                              max="2050-12-31"
                              placeholder="Customer Delivered Date"
                              class="form-control validate silentchallancustomerdeliveredon"
                              style="background-color: #00468b;color:white;border-radius: 15%;padding: 10px;"
                              value="<?php echo $newDate;?>"
                            />
                          </div>
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12">
                          <div class="">
                            <label
                              for=""
                              >Order By
                            </label>
                            <select 
                              name="silentchallanorderby"
                              class="form-control validate silentchallanorderby"
                              style="background-color: #00468b;color:white;border-radius: 15%;padding: 10px;"
                            > <option value="Ashraf Ul Haque">Ashraf Ul Haque</option>
                              <option value="Azeem Equbal">Azeem Equbal</option>
                              <option value="Gautam Chackraborty">GAUTAM CHACKRABORTY</option>
                              <option value="Hasibul Mondal">Hasibul Mondal</option>
                              <option value="Mukhtar Sayeed">Mukhtar Sayeed</option>
                              <option value="Mohammad Raziullah">Mohammad Raziullah</option>
                              <option value="Motiur Rahman">Motiur Rahman</option>
                              <option value="Nafisa Alam">Nafisa Alam</option>
                              <option value="Nausher Khan">Nausher Khan</option>
                              <option value="Raham Gul">Raham Gul</option>
                              <option value="Md Saddam">Md Saddam </option>
                              <option value="Sana Yasin">Sana Yasin</option>
                              <option value="Sk Safikul Islam" selected>Sk Safikul Islam</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12">
                          <div class="">
                            <label
                              for=""
                              >Deliver Through
                            </label>
                            <input
                              name="silentchallandelevredthrough"
                              type="text"
                              class="form-control validate silentchallandelevredthrough"
                              placeholder="Deliver through"
                              style="background-color: #00468b;color:white;border-radius: 15%;padding: 10px;"
                              value="NA"
                            />
                          </div>
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12">
                          <div class="">
                            <label
                              for=""
                              >Warranty Status
                            </label>
                            <input
                              type="text"
                              class="form-control validate silentchallanwarrantystatus"
                              placeholder="Warranty status"
                              style="background-color: #00468b;color:white;border-radius: 15%;padding: 10px;"
                              value="NA"
                              required
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
                                    id="challanitemstags"
                                    data-role="tagsinput"
                                    type="text"
                                    placeholder="Material Name"
                                    class="form-control validate stcfilterbyponumber"
                                    required
                                  />
                                </td>
                                <td>
                                  <button type="button" name="search" class="btn btn-primary" id="challanitemsearch">Search</button>
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
                        <div class="row stc-electro-pd-show-ch" style="width: 100%; margin-left: -10px;">
                        </div>
                        <!-- line item table -->
                        <div class="col-xl-12 col-md-12 col-sm-12 silent-challan-show-prod">
                        </div>
                        <div class="col-12">
                          <div class="">
                            <label
                              for=""
                              >Notes
                            </label>
                            <textarea
                              class="form-control validate"
                              rows="2"
                              id="silentchallannotes"
                              placeholder="Notes"
                            ></textarea>
                          </div>
                        </div>
                        <div class="col-12">
                          <button type="submit" class="btn btn-primary btn-block text-uppercase stcsavesilentchallan">Save</button>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane" id="stc-show-challan">
                      <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
                          <div class="tm-bg-primary-dark tm-block tm-block-h-auto" >
                            <div class="row">
                              <div class="col-12">
                                <h2 class="tm-block-title d-inline-block">Challan</h2>
                              </div>
                            </div>
                            <div class="row stc-view-product-row">
                              <div class="col-xl-12 col-lg-12 col-md-12">
                                <form action="" class="stc-silent-challan-form">
                                    <table class="table table-hover ">
                                      <thead>
                                        <tr>
                                          <th scope="col">From/<br>To</th>
                                          <th scope="col" width="30%">By Challan Number, Customer Name, Customer Site, Status</th>
                                          <th scope="col">Search</th>
                                          <th scope="col" colspan="2">By Products</th>
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
                                            <p><input type="date" value="<?php echo $effectiveDate;?>" class="form-control challanbegdate"></p>
                                            <p><input type="date" value="<?php echo $newDate;?>" class="form-control challanenddate"></p>
                                            <p><a href="#" id="challandatefilt">
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
                                            <button type="button" name="challansearch" class="btn btn-primary" id="challansearch">Search</button>
                                          </td>
                                          <td>
                                            <input
                                              id="stcfilterbypdnamec"
                                              type="text"
                                              placeholder="Product Name"
                                              class="form-control validate"
                                              required
                                            />
                                          </td>
                                          <td>
                                            <a class="btn btn-success searchbycpdnamehit" >Search <i class="fa fa-search"></i></a>
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                </form>
                              </div>
                            </div>
                            <div class="row stc-view-sale-row" style="width: auto;overflow-x: auto; white-space: nowrap;">
                              <div class="col-xl-12 col-lg-12 col-md-12">
                                <form action="" class="stc-view-silent-challan-row-fetch">
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
                    <div class="tab-pane" id="stc-create-invoice">   
                      <div class="row">
                        <div class="col-12">
                          <h2 class="tm-block-title d-inline-block">Create Silent Invoice</h2>
                        </div>
                      </div>                   
                      <div class="row stc-purchase-point-row">
                        <div class="col-xl-6 col-md-6 col-sm-12">
                          <form action="" class="stc-add-sale-product-form"> 
                          <div class="form-group mb-3">
                            <label
                              for="name"
                              >Sale Order Number
                            </label>
                            <input
                              id="gtonumbershow"
                              name="stcmername"
                              type="text"
                              placeholder="Sale Order Number"
                              class="form-control validate"
                              disabled
                            />
                          </div>
                        </div>
                        <div class="col-xl-6 col-md-6 col-sm-12">
                          <div class="form-group mb-3">
                            <label
                              for=""
                              >Sale Order Date
                            </label>
                            <input
                              id="datepicke1r"
                              name="stcinvodate"
                              type="date"
                              min="2001-01-01" 
                              max="2050-12-31"
                              placeholder="Challan Date"
                              class="form-control validate stcinvodate"
                              value="<?php echo $newDate;?>"
                            />
                          </div>
                        </div>
                        <!-- search product display -->
                        <div class="col-xl-12 col-md-12 col-sm-12">
                          <div class="row stc-call-view-invoice-silent-invoice-row">
                          </div>
                        </div>

                        <!-- line item table -->
                        <div class="col-xl-12 col-md-12 col-sm-12 show_silent_challan_for_invo">Loading...
                        </div>
                        <div class="col-4">
                          <div class="form-group mb-3">
                            <label
                              for=""
                              >Notes
                            </label>
                            <textarea
                              class="form-control validate"
                              rows="2"
                              id="stcinvonotes"
                              placeholder="Notes"
                              required
                            ></textarea>
                          </div>
                        </div>
                        <div class="col-12">
                          <a type="submit" class="btn btn-primary btn-block text-uppercase stcsavesilentinvo">Save</a>
                        </div>
                          </form>
                      </div>
                    </div>
                    <div class="tab-pane" id="stc-show-invoice">
                      <div class="row">
                        <div class="row">
                          <div class="col-12">
                            <h2 class="tm-block-title d-inline-block">Silent Invoice/ Retail Invoice</h2>
                          </div>
                        </div>
                        <div class="row stc-view-product-row">
                          <div class="col-xl-12 col-lg-12 col-md-12">
                            <form action="" class="stc-view-product-form">
                                <table class="table table-hover ">
                                  <thead>
                                    <tr>
                                      <th scope="col">From/<br>To</th>
                                      <th scope="col">Rows <br>Like 0 to 10/ 11 to 20</th>
                                      <th scope="col" width="40%">By Invoice Number, ChallanNumber, Customer Name, Agent Name, Status</th>
                                      <th scope="col">Search</th>
                                      <th scope="col">Total</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td>
                                        <?php
                                            $date = date("d-m-Y");
                                            $newDate = date('Y-m-d', strtotime($date)); 
                                            $effectiveDate = date('Y-m-d', strtotime("-1 months", strtotime($date)));
                                        ?>   
                                        <p><input type="date" value="<?php echo $effectiveDate;?>" class="form-control retbegdate"></p>
                                        <p><input type="date" value="<?php echo $newDate;?>" class="form-control retenddate"></p>
                                        <p><a href="#" id="saleproddatefilt">
                                          <i style="font-size: 20px;background: #0c0c1b;border-radius: 50%;padding: 5px;margin: 0;color: #77ffd3;float: right;" class="fa fa-arrow-down"></i>
                                        </a></p>
                                      </td>
                                      <td>
                                        <input type="number" placeholder="Begining Value" value="0" class="form-control begdigit"><br>
                                        <input type="number" placeholder="Ending Value" value="10" class="form-control enddigit">
                                      </td>
                                      <td>
                                        <input
                                          id="stcfilterbypdnamedc"
                                          type="text"
                                          placeholder="Enter Challan Number, Invoice Number, Customer Name, Customer Site, Status"
                                          class="form-control validate"
                                          required
                                        />
                                      </td>
                                      <td>
                                        <button type="button" name="invoicesearch" class="btn btn-primary" id="invoicesearch">Search</button>
                                      </td>
                                      <td>
                                        <h5>
                                          <span class="silent_grand_total"></span>
                                        </h5>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                            </form>
                          </div>
                        </div>
                        <div class="row stc-view-sale-row" style="width: auto;overflow-x: auto; white-space: nowrap;">
                          <div class="col-xl-12 col-lg-12 col-md-12">
                            <form action="" class="stc-view-silent-invoice-form">
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
  
  <!--   Core JS Files   -->
  <script src="../assets/js/core/jquery.min.js"></script>
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap-material-design.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
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
      var month = todaydate.getMonth() - 3;
      var nmonth = todaydate.getMonth();
      var year = todaydate.getFullYear();
      var begdateon = year + "/" + month + "/" + day;
      var enddateon = year + "/" + nmonth + "/" + day;

      // Sale Order
      stc_call_sale_orders(begdateon, enddateon);
      function stc_call_sale_orders(begdateon, enddateon){
        var begdate=begdateon;
        var enddate=enddateon; 
        $.ajax({
          url:"../vanaheim/order_perform.php", 
          method:'POST',
          data:{
            stccallss:1,
            begdate:begdate,
            enddate:enddate
          },
          dataType: 'JSON',
          success:function(data){
            // console.log(data);
              $('.stc-view-silent-challan-row-fetch').html(data);
          }
        });     
      }

      $('#challandatefilt').click(function(e){
        e.preventDefault();
          var begdateon=$('.challanbegdate').val();
          var enddateon=$('.challanenddate').val();
          stc_call_sale_orders(begdateon, enddateon);
      });

      function load_data(query, challanbegdate, challanenddate) {
          $.ajax({
            url:"../vanaheim/order_perform.php", 
            method:"POST",
            data:{
              stcfilterchallan:query,
              challanbegdate:challanbegdate,
              challanenddate:challanenddate
            },
            dataType:"json",
            success:function(data){
              // console.log(data);
              // $('#total_records').text(data.length);
             $('.stc-view-silent-challan-row-fetch').html(data);
            }
          });
      }

      $('#challansearch').click(function(){
          var query = $('#tags').val();
          var challanbegdate=$('.challanbegdate').val();
          var challanenddate=$('.challanenddate').val();
          load_data(query, challanbegdate, challanenddate);
      });

      // challan input
      function load_challan_items(query) {
          $.ajax({
            url:"../vanaheim/order_perform.php", 
            method:"POST",
            data:{search_sale_pd_name_in:query},
            dataType:"json",
            success:function(data){
              // console.log(data);
              // $('#total_records').text(data.length);
              $('.stc-electro-pd-show-ch').html(data);
            }
          });
      }

      $('#challanitemsearch').click(function(){
          var query = $('#challanitemstags').val();
          load_challan_items(query);
      });
    });
  </script>
  <script>
    $(document).ready(function(){   
      // show sale order items sesson cart
      silent_challan_cart();
      function silent_challan_cart(){
        $.ajax({
          url:"../vanaheim/order_perform.php", 
          method:'POST',
          data:{stc_show_sale_sess:1},
          dataType: 'JSON',
          success:function(data){
            // console.log(data);
              $('.silent-challan-show-prod').html(data["orderTable"]);
          }
        });
      }
      
      // add cart to sale session
      $('body').delegate('.add_to_silent_cart','click',function(e){
       e.preventDefault();
        var product_id        = $(this).attr("id");  
        var product_name      = $('#stcpopdname'+product_id).val(); 
        var product_unit      = $('#stcpopdunit'+product_id).val();  
        var product_quantity  = $('#stcpoqty'+product_id).val();  
        var product_price     = $('#stcpdprice'+product_id).val();
        var product_saleperc  = $('#stcpdsaleperc'+product_id).val();
        var product_hsncode   = $('#stcpopdhsncode'+product_id).val();
        var product_gst       = $('#stcpopdgst'+product_id).val();
        var product_invent    = $('#stcpdinvent'+product_id).val();
        var add_to_sale_cart  = "addsalecart";
        $.ajax({  
          url:"../vanaheim/order_perform.php",  
          method:"POST",
          data:{  
              product_id            :         product_id,
              product_name          :         product_name,  
              product_unit          :         product_unit,
              product_quantity      :         product_quantity,
              product_price         :         product_price,
              product_sale_percent  :         product_saleperc,
              product_hsncode       :         product_hsncode,
              product_gst           :         product_gst,
              add_sale              :         add_to_sale_cart  
          },  
          success:function(data){
            silent_challan_cart();
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
          // var change_cart = $('#cartprice'+product_id).val();
          var action = "quantity_change"; 
          if(quantity != 0){  
            $.ajax({  
                url:"../vanaheim/order_perform.php",
                method:"POST",  
                data:{
                  product_id:product_id, 
                  quantity:quantity, 
                  sale_quantity_action:action
                },  
                success:function(data){ 
                  silent_challan_cart();
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
                   silent_challan_cart();
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
                  stcdelsalelinei:1  
              },  
              success:function(data){  
                silent_challan_cart();
                alert(data);                        
              }  
            });  
          }
      });         
      
      // calculate charges to sale order
      $(document).on('click', '.stcsalec', function(e){  
        e.preventDefault();
        var freightcharge=$('.stcfc').val();
        var packingandforwardingcharge=$('.stcpf').val();
        var grand_total=$('#stc_grand_offset_table_value').val();
        $.ajax({  
          url:"../vanaheim/order_perform.php", 
          method:"POST",  
          data:{
            freightcharge:freightcharge,
            packingandforwardingcharge:packingandforwardingcharge,
            grand_total:grand_total,
            do_plus_minus_on_sale:1
          },  
          // dataType: `JSON`,
          success:function(data){ 
            $('#stc_final_sale_value').html(data);
          }  
        }); 
      });    
      
      // save sale order to db
      $(document).on('click', '.stcsavesilentchallan', function(e){
        e.preventDefault();
        var challandate             = $('.silentchallandate').val();
        var customername            = $('.silentchallancustomername').val();
        var order_billaddress       = $('#silentchallancustomernilladdress').val();
        var order_shipaddress       = $('#silentchallancustomershippaddress').val();
        var order_contnumber        = $('.silentchallancustomercontactnumber').val();
        var order_orderon           = $('.silentchallancustomerorderon').val();
        var order_deliveron         = $('.silentchallancustomerdeliveredon').val();
        var order_orderby           = $('.silentchallanorderby').val();
        var order_deliverthrough    = $('.silentchallandelevredthrough').val();
        var order_warrantystatus    = $('.silentchallanwarrantystatus').val();
        var order_stcfc             = $('.stcfc').val();
        var order_stcpf             = $('.stcpf').val();
        var order_notes             = $('#silentchallannotes').val();
        $.ajax({  
          url:"../vanaheim/order_perform.php",
          method:"POST",  
          data:{
            silent_challandate           : challandate,
            silent_customername          : customername,
            silent_order_billaddress     : order_billaddress,
            silent_order_shipaddress     : order_shipaddress,
            silent_order_contnumber      : order_contnumber,
            silent_order_orderon         : order_orderon,
            silent_order_deliveron       : order_deliveron,
            silent_order_orderby         : order_orderby,
            silent_order_deliverthrough  : order_deliverthrough,
            silent_order_warrantystatus  : order_warrantystatus,
            silent_order_stcfc           : order_stcfc,
            silent_order_stcpf           : order_stcpf,
            silent_order_notes           : order_notes,
            save_sale_action             : 1
          },
          // dataType: `JSON`,
          success:function(data){
           // console.log(data);
            alert(data);
            $('.stc-add-sale-product-form')[0].reset();
            window.location.reload();
            $('.stc-add').fadeOut(500);
            $('.stc-view').toggle(1000);
          }
        });
      });
    });
  </script>
  <script>
    $(document).ready(function(){
      var todaydate = new Date();
      var day = todaydate.getDate();
      var month = todaydate.getMonth() - 3;
      var nmonth = todaydate.getMonth();
      var year = todaydate.getFullYear();
      var begdateon = year + "/" + month + "/" + day;
      var enddateon = year + "/" + nmonth + "/" + day;
      load_this_invoices(begdateon, enddateon);
      function load_this_invoices(begdateon, enddateon){
        var loadchallanbegdate=begdateon;
        var loadchallanenddate=enddateon;
        $.ajax({
          url      :"../vanaheim/order_perform.php",
          method   : 'post',
          data     : {
            load_invoices:1,
            loadchallanbegdate:loadchallanbegdate,
            loadchallanenddate:loadchallanenddate
          },
          dataType : 'JSON',
          success  : function(invoices){
            // console.log(invoices);
            $('.stc-view-silent-invoice-form').html(invoices['bills_all']);
            $('.silent_grand_total').html(invoices['value_total']);
          }
        });
      }

      $(document).on('click', '#saleproddatefilt', function(e){
        e.preventDefault();
        var begdate=$('.retbegdate').val();
        var enddate=$('.retenddate').val();
        load_this_invoices(begdate, enddate);
      });

      function load_data(query, directchallanbegdate, directchallanenddate, begno, endno) {
        $.ajax({
          url      :"../vanaheim/order_perform.php",
          method:"POST",
          data:{
            stcfilterinvoice:query,
            directchallanbegdate:directchallanbegdate,
            directchallanenddate:directchallanenddate,
            begno:begno,
            endno:endno
          },
          dataType:"json",
          success:function(data){
            // console.log(data);
            // $('#total_records').text(data.length);
            $('.stc-view-silent-invoice-form').html(data['bills_all']);
            $('.silent_grand_total').html(data['value_total']);
          }
        });
      }

      $('#invoicesearch').click(function(){
        var query = $('#stcfilterbypdnamedc').val();
        var directchallanbegdate=$('.retbegdate').val();
        var directchallanenddate=$('.retenddate').val();
        var begno=$('.begdigit').val();
        var endno=$('.enddigit').val();
        if(query=='' || directchallanbegdate=='' || directchallanenddate==''){
          alert("Please do not let any filed empty!!!");
        }else{
          load_data(query, directchallanbegdate, directchallanenddate, begno, endno);
        }
      });

      load_challan_do_invo();
      // load challan on chnage cust
      function load_challan_do_invo(){
        $.ajax({
          url:"../vanaheim/order_perform.php",
          method : "post",
          data : {
            call_po_on_choose_customer:1
          },
          // dataType : 'JSON',
          success : function(data){
            // console.log(data);
            $('.stc-call-view-invoice-silent-invoice-row').html(data);
          }
        });
      }

      // load invoices session
      show_silent_invo_cart();
      function show_silent_invo_cart() {
        $.ajax({  
          url:"../vanaheim/order_perform.php", 
          method:"POST",
          data:{  
              comon_invo_sess:1  
          },  
          // dataType: 'JSON',
          success:function(data){
            // console.log(data);    
            // $('.show_silent_challan_for_invo').html(data['invoTable']); 
            $('.show_silent_challan_for_invo').html(data);                    
          }  
        });
      }

      // add to invoices session
      $(document).on('click', '.add_to_silent_invo_cart', function(e){  
        e.preventDefault();
        var challan_id = $(this).attr("id");
        $.ajax({  
          url     :"../vanaheim/order_perform.php", 
          method  :"POST",  
          data    :{
            invo_challan_id:challan_id,
            add_invo_sess_action:1
          },  
          // dataType: `JSON`,
          success :function(data){ 
           // console.log(data);
           alert(data);
            show_silent_invo_cart();
          }  
        });  
      });   

      // delete from invoices session
      $('body').delegate('.stcdelsilentinvobtn','click',function(e){
        e.preventDefault();
        var product_id = $(this).attr("id");
        if(confirm("Are you sure you want to remove this product?")){   
          $.ajax({  
            url     :"../vanaheim/order_perform.php", 
            method  :"POST",
            data    :{  
                product_id:product_id,
                stcdelinvolinei:1  
            },  
            success :function(data){  
              show_silent_invo_cart();
              alert(data);                        
            }  
          });  
        }         
      });

      // save invoice
      $(document).on('click', '.stcsavesilentinvo', function(e){
        e.preventDefault();
        var stcinvodate=$('.stcinvodate').val();    
        var stcinvonotes=$('#stcinvonotes').val();
        $.ajax({  
            url     :"../vanaheim/order_perform.php", 
            method  :"POST",  
            data    :{
            stcinvodate:stcinvodate,
            stcinvonotes:stcinvonotes,
            save_invo_action:1
          },  
          // dataType: `JSON`,
          success   :function(data){ 
              alert(data);
              load_challan_do_invo();
              show_silent_invo_cart();
              $('.stc-add-sale-product-form')[0].reset();
          }  
        });
      });

      // payment update model
      $('body').delegate('.stc-pay-invoice', 'click', function(e){
        e.preventDefault();
        var invo_id=$(this).attr("id");
        $('.stc-hidden-invoice-id').val(invo_id);
        $('.stc-update-invoice-payment-model').modal("show");
      });

      // payment update
      $('body').delegate('#stc-payment-update-go', 'click', function(e){
        e.preventDefault();
        var order_id=$('.stc-hidden-invoice-id').val();
        var pay_type=$('.stc-payment-type').val();
        var pay_amount=$('.stc-payment-val').val();
        var notes=$('.stc-payment-notes').val();
        $.ajax({  
            url     :"../vanaheim/order_perform.php", 
            method  :"POST",  
            data    :{
            order_id:order_id,
            pay_type:pay_type,
            pay_amount:pay_amount,
            notes:notes,
            save_payment_update:1
          },  
          // dataType: `JSON`,
          success   :function(data){
              var response=data.trim();
              if(response=="Payment updated succesfully."){
                alert(response);
                load_this_invoices(begdateon, enddateon);
                $('.stc-update-invoice-payment-model').modal("hide");
              }else{
                alert(data);
              } 
          }  
        });
      });
    });
  </script>
</body>

</html>
<div class="modal fade bd-example-modal-xl stc-update-invoice-payment-model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
                  <option value="Account">Account</option>
                  <option value="Advance">Advance</option>
                  <option value="Cash">Cash</option>
                </select>
              </div>
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <h5>
                  Payment Amount
                </h5>
                <input type="number" class="form-control stc-payment-val" placeholder="Enter Amount">
              </div>
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <h5>
                  Notes
                </h5>
                <textarea class="form-control stc-payment-notes" placeholder="Enter Notes"></textarea>
              </div>
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <button type="button" class="form-control btn btn-success" id="stc-payment-update-go">Save</button>
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