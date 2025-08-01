<?php  
session_start(); 
if(isset($_SESSION["stc_empl_id"]) && ($_SESSION["stc_empl_role"]>0)){ 
}else{ 
    header("Location:index.html"); 
} 
$page_code=601;
include("kattegat/role_check.php");
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta http-equiv="Content-Language" content="en">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Agent Order - STC</title>
  <meta name="viewport"
    content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
  <meta name="description" content="">
  <meta name="msapplication-tap-highlight" content="no">
  <!-- Latest compiled and minified CSS -->
  <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous"> -->
  <link rel="icon" type="image/png" href="images/stc_logo_title.png">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link href="./main.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
  <style>
    .checkbox-group label {
      float: left;
      position: relative;
      display: inline-flex;
    }

    .fade:not(.show) {
      opacity: 10;
    }
  </style>
</head>

<body>
  <div class="spinner" style="display: none;position: fixed;top: 50%; left: 50%;z-index: 999;">
    <img src="https://cdn.pixabay.com/animation/2023/08/11/21/18/21-18-05-265_256.gif" alt="Loading...">
  </div>
  <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
    <?php include_once("header-nav.php");?>
    <div class="app-main">
      <?php include_once("sidebar-nav.php");?>
      <div class="app-main__outer">
        <div class="app-main__inner">
          <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
            <!-- <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab-content-1">
                                    <span>Check Order</span>
                                </a>
                            </li> -->
            <li class="nav-item">
              <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-content-2">
                <span>Check Requisition</span>
              </a>
            </li>
            <li class="nav-item">
              <a role="tab" class="nav-link" id="tab-5" data-toggle="tab" href="#tab-content-5">
                <span>Check Requisition By List</span>
              </a>
            </li>
            <li class="nav-item">
              <a role="tab" class="nav-link active" id="tab-3" data-toggle="tab" href="#tab-content-3">
                <span>Process Requisition</span>
              </a>
            </li>
            <li class="nav-item">
              <a role="tab" class="nav-link" id="tab-4" data-toggle="tab" href="#tab-content-4">
                <span>Requisitions Item Track </span>
              </a>
            </li>
            <li class="nav-item">
              <a role="tab" class="nav-link" id="tab-6" data-toggle="tab" href="#tab-content-6">
                <span>PPE Track </span>
              </a>
            </li>
            <li class="nav-item">
              <a role="tab" class="nav-link" id="tab-7" data-toggle="tab" href="#tab-content-7">
                <span>Tools Track </span>
              </a>
            </li>
            <li class="nav-item">
              <a role="tab" class="nav-link" id="tab-8" data-toggle="tab" href="#tab-content-8">
                <span>Order Material List </span>
              </a>
            </li>
            <li class="nav-item">
              <a role="tab" class="nav-link" id="tab-9" data-toggle="tab" href="#tab-content-9">
                <span>B2C Orders</span>
              </a>
            </li>
            <li class="nav-item">
              <a role="tab" class="nav-link" id="tab-10" data-toggle="tab" href="#tab-content-10">
                <span>GLD Requisitions</span>
              </a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane tabs-animation fade" id="tab-content-1" role="tabpanel">
              <div class="row">
                <div class="col-md-12">
                  <div class="card-border mb-3 card card-body border-success">
                    <h5 for="description" align="center">Check Order
                    </h5>
                  </div>
                </div>
              </div>
              <div class="row">
                <div style="display: none;" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-add">
                  <form action="" class="stc-add-po-product-form">
                    <div class="row stc-agent-order-row">
                      <div class="col-xl-12 col-md-12 col-sm-12">
                        <a id="stc_uppp" href="#"
                          style="font-size: 25px;border-radius: 100%;background: yellow;padding: 10px;color: black;position: relative;left: 48%;"><i
                            class="fas fa-arrow-up"></i></a>
                      </div>
                      <div class="col-xl-6 col-md-6 col-sm-12">
                        <div class="card-border mb-3 card card-body border-success">
                          <h5 for="name">Order Number
                          </h5>
                          <input id="order-challan" name="stcmername" type="text" placeholder="Purchase Order Number"
                            class="form-control validate" disabled />
                          <input type="hidden" id="ag-challan-order">
                        </div>
                      </div>
                      <div class="col-xl-6 col-md-6 col-sm-12">
                        <div class="card-border mb-3 card card-body border-success">
                          <h5 for="">Order Date
                          </h5>
                          <input id="ag-order-date" name="ag-order-date" type="text" class="form-control validate"
                            data-large-mode="true" disabled />
                        </div>
                      </div>
                      <div class="col-xl-12 col-md-12 col-sm-12">
                        <div class="card-border mb-3 card card-body border-success">
                          <h5 for="name">Customer Name
                          </h5>
                          <input id="cust_name" name="cust_name" type="text" class="form-control validate"
                            data-large-mode="true" disabled />
                        </div>
                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="page-title-right">
                          <div class="card-border mb-3 card card-body border-success">
                            <h5>Customer Agent Id</h5>
                            <input id="ag-info" name="ag-info" type="text" class="form-control validate"
                              data-large-mode="true" />
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="page-title-right">
                          <div class="card-border mb-3 card card-body border-success">
                            <h5>Refrence</h5>
                            <input id="ag-refrence" name="ag-refrence" type="text" class="form-control validate"
                              data-large-mode="true" disabled />
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="page-title-right">
                          <div class="card-border mb-3 card card-body border-success">
                            <h5>Site</h5>
                            <input id="site-name" name="site-name" type="text" class="form-control validate"
                              data-large-mode="true" disabled />
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="page-title-right">
                          <div class="card-border mb-3 card card-body border-success">
                            <h5>Site Address</h5>
                            <textarea class="form-control" id="site-address" name="site-address" rows="1"
                              disabled></textarea>
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="page-title-right">
                          <div class="card-border mb-3 card card-body border-success">
                            <h5>Way Bill No</h5>
                            <input id="ag-way-bill-no" name="ag-way-bill-no" type="text" class="form-control validate"
                              data-large-mode="true" placeholder="Enter Way Boll No" value="NA" />
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="page-title-right">
                          <div class="card-border mb-3 card card-body border-success">
                            <h5>LR No</h5>
                            <input id="ag-lr-no" name="ag-lr-no" type="text" class="form-control validate"
                              data-large-mode="true" placeholder="Enter LR No" value="NA" />
                          </div>
                        </div>
                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="card-border mb-3 card card-body border-success">
                          <h5 for="">Notes
                          </h5>
                          <textarea class="form-control validate" rows="2" id="ag-notes" placeholder="Notes"></textarea>
                        </div>
                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="card-border mb-3 card card-body border-success">
                          <h5 for="">Terms & Conditions
                          </h5>
                          <textarea class="form-control validate" rows="2" id="ag-tandc"
                            placeholder="Terms & Conditions"></textarea>
                          <input type="hidden" id="stc-proj-id">
                          <input type="hidden" id="stc-super-id">
                          <input type="hidden" id="stc-requis-id">
                        </div>
                      </div>

                      <!-- line item table -->
                      <div class="col-xl-12 col-md-12 col-sm-12 ag-order-items"
                        style="width: auto;overflow-x: auto; white-space: nowrap;">
                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="card-border mb-3 card card-body border-success">
                          <button class="btn btn-primary form-control set-to-cleaned">Cleaned</button>
                        </div>
                      </div>
                      <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                        <div class="card-border mb-3 card card-body border-success">
                          <button class="btn btn-primary form-control save-ag-challan">Save Challan</button>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
                  <div class="card-border mb-3 card card-body border-success"
                    style="overflow-x: auto; white-space: nowrap;">
                    <div class="row stc-view-product-row">
                      <div class="col-xl-12 col-lg-12 col-md-12">
                        <form action="" class="stc-view-product-form">
                          <table class="table table-hover ">
                            <thead>
                              <tr>
                                <th scope="col">From/<br>To</th>
                                <th scope="col" width="20%">By Customer</th>
                                <th scope="col" width="20%">By Order Number</th>
                                <th scope="col" width="20%">By Agents</th>
                                <th scope="col" width="20%">By Status</th>
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
                                  <p><input type="date" value="<?php echo $effectiveDate;?>"
                                      class="form-control begdate"></p>
                                  <p><input type="date" value="<?php echo $newDate;?>" class="form-control enddate"></p>
                                  <p><a href="#" id="purchaseproddatefilt">
                                      <i style="font-size: 20px;background: #0c0c1b;border-radius: 50%;padding: 5px;margin: 0;color: #77ffd3;float: right;"
                                        class="fa fa-arrow-down"></i>
                                    </a></p>
                                </td>
                                <td>
                                  <select id="stc-order-customer-in"
                                    class="custom-select form-control stc-select-customer" name="stcvendor">
                                  </select>
                                </td>
                                <td>
                                  <input type="number" id="stc-order-number-finder" class="form-control"
                                    placeholder="Order Number">
                                </td>
                                <td>
                                  <input type="text" id="stc-order-agents-finder" class="form-control"
                                    placeholder="By Agent">
                                </td>
                                <td>
                                  <select class="custom-select form-control stc-order-status-in">
                                    <option value="NA">Select Status</option>
                                    <option value="PROCESS">PROCESS</option>
                                    <option value="CLEANED">CLEANED</option>
                                  </select>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </form>
                      </div>
                    </div>
                    <div class="row stc-view-purchase-row">
                      <div class="col-xl-12 col-lg-12 col-md-12">
                        <form action="" class="stc-view-ag-order-form">
                          <table class="table table-hover ">
                            <tr>
                              <td>
                                Loading.....
                              </td>
                            </tr>
                          </table>
                        </form>
                      </div>
                    </div>

                    <div class="row stc-call-view-Merchant-row">
                      <span id="total_records"></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane tabs-animation fade" id="tab-content-2" role="tabpanel">
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                  <div class="card-border mb-3 card card-body border-success">
                    <h5 for="description" align="center">Check Requisition
                    </h5>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                  <div class="card-border mb-3 card card-body border-success"
                    style="overflow-x: auto; white-space: nowrap;">
                    <div class="row stc-view-product-row">
                      <div class="col-xl-12 col-lg-12 col-md-12">
                        <form action="" class="stc-view-product-form">
                          <table class="table table-hover ">
                            <thead>
                              <tr>
                                <th scope="col">From/<br>To</th>
                                <th scope="col" width="20%">By Customer</th>
                                <th scope="col" width="20%">By Sitename</th>
                                <th scope="col" width="20%">By Requisition Number</th>
                                <th scope="col" width="20%">By Req. Material Name</th>
                                <th scope="col" width="20%"></th>
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
                                  <p><input type="date" value="<?php echo $effectiveDate;?>"
                                      class="form-control reqbegdate"></p>
                                  <p><input type="date" value="<?php echo $newDate;?>" class="form-control reqenddate">
                                  </p>
                                </td>
                                <td>
                                  <select id="stc-requisition-customer-in"
                                    class="custom-select form-control stc-select-customer" name="stcvendor">
                                  </select>
                                </td>
                                <td>
                                  <input type="text" id="stc-requisition-sitename-finder" class="form-control"
                                    placeholder="By Sitename">
                                <td>
                                  <input type="number" id="stc-requisition-number-finder" class="form-control"
                                    placeholder="Requisition Number">
                                </td>
                                </td>
                                <td>
                                  <input type="text" id="stc-requisition-material-in"
                                    class="form-control stc-requisition-material-in"
                                    placeholder="By Requisition Material Name">
                                </td>
                                <td>
                                  <a href="#" id="aorfilterbtn" class="btn btn-primary">
                                    Find
                                  </a>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </form>
                      </div>
                    </div>
                    <div class="row stc-view-purchase-row">
                      <div class="col-xl-12 col-lg-12 col-md-12">
                        <form action="" class="stc-view-ag-requisition-form">
                          <table class="table table-hover ">
                            <tr>
                              <td>
                                Loading.....
                              </td>
                            </tr>
                          </table>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane tabs-animation fade" id="tab-content-5" role="tabpanel">
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                  <div class="card-border mb-3 card card-body border-success">
                    <h5 for="description" align="center">Check Requisition By List
                    </h5>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                  <div class="card-border mb-3 card card-body border-success"
                    style="overflow-x: auto; white-space: nowrap;">
                    <div class="row stc-view-product-row">
                      <div class="col-xl-12 col-lg-12 col-md-12">
                        <form action="" class="stc-view-product-form">
                          <table class="table table-hover ">
                            <thead>
                              <tr>
                                <th scope="col">From/<br>To</th>
                                <th scope="col" width="20%">Customer</th>
                                <th scope="col" width="20%">Requisition Number</th>
                                <th scope="col" width="20%">Sitename</th>
                                <th scope="col" width="20%">Material Type</th>
                                <th scope="col" width="20%">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>
                                  <?php
                                                          $date = date("d-m-Y");
                                                          $newDate = date('Y-m-d', strtotime($date)); 
                                                          $effectiveDate = date('Y-m-d', strtotime("-15 days", strtotime($date)));
                                                        ?>
                                  <p><input type="date" value="<?php echo $effectiveDate;?>"
                                      class="form-control reqlistbegdate"></p>
                                  <p><input type="date" value="<?php echo $newDate;?>"
                                      class="form-control reqlistenddate"></p>
                                </td>
                                <td>
                                  <select id="stc-requisitionlist-customer-in"
                                    class="custom-select form-control stc-select-customer" name="stcvendor">
                                  </select>
                                </td>
                                <td>
                                  <input type="number" id="stc-requisitionlist-number-finder" class="form-control"
                                    placeholder="Requisition Number">
                                </td>
                                <td>
                                  <input type="text" id="stc-requisitionlist-sitename-finder" class="form-control"
                                    placeholder="By Sitename">
                                </td>
                                <td>
                                  <select class="form-control" id="stc-requisitionlist-materialtype">
                                    <option value="NA">SELECT</option>
                                    <option value="Consumable">CONSUMABLE</option>
                                    <option value="PPE">PPE</option>
                                    <option value="Supply">SUPPPLY</option>
                                    <option value="Tools & Tackles">TOOLS & TACKLES</option>
                                  </select>
                                </td>
                                <td>
                                  <a href="javascript:void(0)"
                                    class="btn btn-success form-control stc-req-list-find">Find</a>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </form>
                      </div>
                    </div>
                    <div class="row stc-view-purchase-row">
                      <div class="col-xl-12 col-lg-12 col-md-12">
                        <form action="" class="stc-view-ag-requisitionbylist-form"
                          style="overflow-x:auto;width: 1520px;">
                          <table class="table table-hover ">
                            <tr>
                              <td>
                                Search here.....
                              </td>
                            </tr>
                          </table>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane tabs-animation fade show active" id="tab-content-3" role="tabpanel">
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                  <div class="card-border mb-3 card card-body border-success">
                    <h5 for="description" align="center">Process Requisition
                    </h5>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                  <div class="card-border mb-3 card card-body border-success"
                    style="overflow-x: auto; white-space: nowrap;">
                    <div class="row">
                      <div class="col-xl-12 col-lg-12 col-md-12">
                        <form action="" class="stc-view-product-form">
                          <table class="table table-hover ">
                            <thead>
                              <tr>
                                <th scope="col">From/<br>To</th>
                                <th></th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>
                                  <?php
                                                          $date = date("d-m-Y");
                                                          $newDate = date('Y-m-d', strtotime($date)); 
                                                          $effectiveDate = date('Y-m-d', strtotime("-2 days", strtotime($date)));
                                                        ?>
                                  <p><input type="date" value="<?php echo $effectiveDate;?>"
                                      class="form-control sitebegdate"></p>
                                  <p><input type="date" value="<?php echo $newDate;?>" class="form-control siteenddate">
                                  </p>
                                </td>
                                <td>
                                  <p><a href="#" id="sitename_date_filter" class="form-control btn btn-primary">
                                      Find
                                    </a></p>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </form>
                      </div>
                    </div>
                    <div class="row stc-call-sitename">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane tabs-animation fade" id="tab-content-4" role="tabpanel">
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                  <div class="card-border mb-3 card card-body border-success">
                    <h5 for="description" align="center">Requisitions Item Track
                    </h5>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                  <div class="card-border mb-3 card card-body border-success"
                    style="overflow-x: auto; white-space: nowrap;">
                    <div class="row">
                      <div class="col-xl-12 col-lg-12 col-md-12">
                        <form action="" class="stc-view-product-form">
                          <table class="table table-hover table-bordered">
                            <thead>
                              <tr>
                                <th class="text-center" scope="col">From/<br>To</th>
                                <th class="text-center">Type</th>
                                <th class="text-center">Action</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>
                                  <?php
                                                          $date = date("d-m-Y");
                                                          $newDate = date('Y-m-d', strtotime($date)); 
                                                          $effectiveDate = date('Y-m-d', strtotime("-15 days", strtotime($date)));
                                                        ?>
                                  <p><input type="date" value="<?php echo $effectiveDate;?>"
                                      class="form-control ritbegdate"></p>
                                  <p><input type="date" value="<?php echo $newDate;?>" class="form-control ritenddate">
                                  </p>
                                </td>
                                <td>
                                  <select class="form-control rittypeselect">
                                    <option value="NA">Please Select Item Type</option>
                                    <option value="TOOLS & TACKLES">TOOLS & TACKLES</option>
                                    <option value="CONSUMABLE">CONSUMABLE</option>
                                    <option value="SUPPPLY">SUPPPLY</option>
                                    <option value="PPE">PPE</option>
                                  </select>
                                </td>
                                <td>
                                  <p><a href="#" id="rit_date_filter" class="form-control btn btn-primary">
                                      Find
                                    </a></p>
                                </td>
                              </tr>
                              <tr>
                                <td colspan="2">
                                  <h4>Search With Item Description : </h4>
                                </td>
                              </tr>
                              <tr>
                                <td colspan="2">
                                  <input class="form-control" type="text" placeholder="Search Item Here"
                                    id="stc-req-item-track-input" onkeyup="myFunction()">
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </form>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-xl-1 col-lg-1 col-md-1"></div>
                      <div class="col-xl-10 col-lg-10 col-md-10 stc-call-rit-items"></div>
                      <div class="col-xl-1 col-lg-1 col-md-1"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane tabs-animation fade" id="tab-content-6" role="tabpanel">
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                  <div class="card-border mb-3 card card-body border-success"
                    style="overflow-x: auto; white-space: nowrap;">
                    <div class="row">
                      <div class="col-xl-12 col-lg-12 col-md-12">
                        <div class="card-border mb-3 card card-body border-success">
                          <h5 for="description" align="center">PPE Track
                          </h5>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-2">
                        <a href="javascript:void(0)" class="btn btn-primary form-control" data-toggle="modal"
                          data-target=".bd-ppetracker-modal-lg">Add PPE Tracker</a>
                      </div>
                      <div class="col-md-6">
                        <input type="text" id="searchInputppe" class="form-control" placeholder="Type to search...">
                      </div>
                      <div class="col-md-2">
                        <a href="javascript:void(0)" id="searchInputppebtn"
                          class="btn btn-success form-control">Find</a>
                      </div>
                      <div class="col-md-2">
                        <a href="javascript:void(0)" class="btn btn-success form-control" data-toggle="modal"
                          data-target=".bd-ppetrackersummary-modal-lg">Show PPE Summary</a>
                      </div>
                      <div class="col-md-12">
                        <table class="table table-stripped table-bordered table-hover">
                          <thead>
                            <tr>
                              <th class="text-center">ID</th>
                              <th class="text-center">Employee Name</th>
                              <th class="text-center">Type of PPE</th>
                              <th class="text-center">Quantity</th>
                              <th class="text-center">Unit</th>
                              <th class="text-center">Date of Issue</th>
                              <th class="text-center">Duration</th>
                              <th class="text-center">Next Issue Date</th>
                              <th class="text-center">Remarks</th>
                              <!-- <th class="text-center">Action</th> -->
                            </tr>
                          </thead>
                          <tbody class="item-tracker-show"></tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane tabs-animation fade" id="tab-content-7" role="tabpanel">
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                  <div class="card-border mb-3 card card-body border-success" style="overflow-x: auto; ">
                    <div class="row">
                      <div class="col-xl-12 col-lg-12 col-md-12">
                        <div class="card-border mb-3 card card-body border-success">
                          <h5 for="description" align="center">Tools Track
                          </h5>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-2">
                        <a href="javascript:void(0)" class="btn btn-primary form-control itt-create" data-toggle="modal"
                          data-target=".bd-toolstracker-modal-lg">Add Tools</a>
                      </div>
                      <div class="col-md-8">
                        <input type="text" id="itt-toolssearchInput" class="form-control"
                          placeholder="Type to search...">
                      </div>
                      <div class="col-md-2">
                        <a href="javascript:void(0)" id="itt-toolssearchInputbtn"
                          class="btn btn-success form-control">Find</a>

                      </div>
                      <div class="col-md-12">
                        <table class="table table-stripped table-bordered table-hover">
                          <thead>
                            <tr>
                              <th class="text-center">SL NO</th>
                              <th class="text-center">UNIQUE ID</th>
                              <th class="text-center">ITEM DESCRIPTION</th>
                              <th class="text-center">MACHINE SR.NO</th>
                              <th class="text-center">MAKE</th>
                              <th class="text-center">TYPE</th>
                              <th class="text-center">PURCHASE DETAILS</th>
                              <th class="text-center">WARRANTY</th>
                              <th class="text-center">TAX INVOICE NO.</th>
                              <th class="text-center">TAX INVOICE DATE.</th>
                              <th class="text-center">REMAKRS</th>
                              <th class="text-center">CREATED DATE</th>
                              <th class="text-center">CREATED BY</th>
                              <th class="text-center">ACTION</th>
                            </tr>
                          </thead>
                          <tbody class="tools-tracker-show"></tbody>
                        </table>
                        <div class="paginationtts"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>            
            <div class="tab-pane tabs-animation fade" id="tab-content-8" role="tabpanel">
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                  <div class="card-border mb-3 card card-body border-success">
                    <h5 for="description" align="center">Advance Order Material List
                    </h5>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                  <div class="card-border mb-3 card card-body border-success" style="overflow-x: auto; white-space: nowrap;">
                    <div class="row">
                      <div class="col-xl-12 col-lg-12 col-md-12">
                        <input type="text" id="searchInputaom" class="form-control" placeholder="Type to search...">
                        <table class="table table-hover table-bordered">
                          <thead>
                            <tr>
                              <th class="text-center">Slno</th>
                              <th class="text-center">Product Name</th>
                              <th class="text-center">Remaining Quantity</th>
                              <th class="text-center">Unit</th>
                            </tr>
                          </thead>
                          <tbody class="stc-call-advanceorderlist">
                            <tr>
                              <td>Loading...</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>           
            <div class="tab-pane tabs-animation fade" id="tab-content-9" role="tabpanel">
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                  <div class="card-border mb-3 card card-body border-success">
                    <h5 for="description" align="center">B2C Orders
                    </h5>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                  <div class="card-border mb-3 card card-body border-success"
                    style="overflow-x: auto; white-space: nowrap;">
                    <div class="row">
                      <div class="col-xl-12 col-lg-12 col-md-12">
                        <table class="table table-hover table-bordered">
                          <thead>
                            <tr>
                              <th scope="col">Search</th>
                              <th></th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>
                                <div class="search-box">
                                    <input type="text" id="searchInput" class="form-control" placeholder="Search by Product name, User email, name or phone number">
                                </div>
                              </td>
                              <td><button id="searchButton" class="btn btn-primary" >Search</button></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <div class="col-xl-12 col-lg-12 col-md-12">
                        <table class="table table-hover table-bordered">
                            <thead>
                              <tr>
                                <th class="text-center">Slno</th>
                                <th class="text-center">Firstname</th>
                                <th class="text-center">Lastname</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Contact</th>
                                <th class="text-center">Address</th>
                                <th class="text-center">Item Code</th>
                                <th class="text-center">Product Name</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Rate</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Order Date</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                              </tr>
                            </thead>
                            <tbody class="stc-call-b2corders">
                              <tr>
                                <td>Loading...</td>
                              </tr>
                            </tbody>
                          </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane tabs-animation fade" id="tab-content-10" role="tabpanel">
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                  <div class="card-border mb-3 card card-body border-success">
                    <h5 align="center">GLD Requisitions</h5>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                  <div class="card-border mb-3 card card-body border-success" style="overflow-x: auto; white-space: nowrap;">
                    <div class="row mb-2">
                      <div class="col-md-4">
                        <input type="text" id="gld-requisition-search" class="form-control" placeholder="Search by name, unit, remarks...">
                      </div>
                      <div class="col-md-2">
                        <button class="btn btn-primary" id="gld-requisition-search-btn">Search</button>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <!-- Header Row -->
                        <div class="row gld-requisition-header" style="background-color: #f8f9fa;border: 1px solid #dee2e6;margin-bottom: 0;position: relative;left: 15px;width: 100%;">
                          <div class="col-md-1 text-center" style="padding: 10px; border-right: 1px solid #dee2e6;"><strong>Branch</strong></div>
                          <div class="col-md-1 text-center" style="padding: 10px; border-right: 1px solid #dee2e6;"><strong>Product ID(SKU)</strong></div>
                          <div class="col-md-1 text-center" style="padding: 10px; border-right: 1px solid #dee2e6;"><strong>Product Name</strong></div>
                          <div class="col-md-1 text-center" style="padding: 10px; border-right: 1px solid #dee2e6;"><strong>Requested Quantity</strong></div>
                          <div class="col-md-1 text-center" style="padding: 10px; border-right: 1px solid #dee2e6;"><strong>Unit</strong></div>
                          <div class="col-md-2 text-center" style="padding: 10px; border-right: 1px solid #dee2e6;"><strong>Remarks</strong></div>
                          <div class="col-md-1 text-center" style="padding: 10px; border-right: 1px solid #dee2e6;"><strong>Dispatch Status</strong></div>
                          <div class="col-md-1 text-center" style="padding: 10px; border-right: 1px solid #dee2e6;"><strong>Buy Status</strong></div>
                          <div class="col-md-2 text-center" style="padding: 10px; border-right: 1px solid #dee2e6;"><strong>Buy Remarks</strong></div>
                          <div class="col-md-1 text-center" style="padding: 10px;"><strong>Action</strong></div>
                        </div>
                        
                        <!-- Data Container -->
                        <div class="gld-requisition-table-body" style="border: 1px solid #dee2e6; border-top: none;">
                          <div class="row" style="border-bottom: 1px solid #dee2e6; margin: 0;">
                            <div class="col-md-12 text-center" style="padding: 20px;">
                              Loading...
                            </div>
                          </div>
                        </div>
                        
                        <div class="gld-requisition-pagination"></div>
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
  <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
  <script type="text/javascript" src="./assets/scripts/loginopr.js"></script>
  <script type="text/javascript" src="./assets/scripts/main.js"></script>
  <script type="text/javascript" src="./assets/scripts/jarvis.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script>
    $(document).ready(function () {
      $('.add-combo').on('click', function (e) {
        e.preventDefault();
        $('.stc-add').toggle(1000);
        $('.stc-view').fadeOut(500);
        // $('.stc-add-po-product-form')[0].reset();
      });

      $('#stc_uppp').on('click', function (e) {
        e.preventDefault();
        $('.stc-view').toggle(1000);
        $('.stc-add').fadeOut(500);
      });

      $('.upward').on('click', function (e) {
        e.preventDefault();
        $('.downward').toggle(500);
        $('.thishypo').fadeOut(500);
        $('.upward').fadeOut(500);
      });

      $('.downward').on('click', function (e) {
        e.preventDefault();
        $('.upward').toggle(500);
        $('.thishypo').toggle(500);
        $('.downward').fadeOut(500);
      });
    });
    $(document).ready(function () {
      $('.add-combo').on('click', function (e) {
        e.preventDefault();
        $('.stc-req-add').toggle(1000);
        $('.stc-req-view').fadeOut(500);
        // $('.stc-add-po-product-form')[0].reset();
      });

      $('#stc_req_uppp').on('click', function (e) {
        e.preventDefault();
        $('.stc-req-view').toggle(1000);
        $('.stc-req-add').fadeOut(500);
      });

      $('.upward').on('click', function (e) {
        e.preventDefault();
        $('.downward').toggle(500);
        $('.thishypo').fadeOut(500);
        $('.upward').fadeOut(500);
      });

      $('.downward').on('click', function (e) {
        e.preventDefault();
        $('.upward').toggle(500);
        $('.thishypo').toggle(500);
        $('.downward').fadeOut(500);
      });
    });
  </script>
  <script>
    $(document).ready(function () {
      // call customer for sale
      stc_call_customer();
      function stc_call_customer() {
        $.ajax({
          url: "kattegat/ragnar_order.php",
          method: "post",
          data: { friday_customer: 1 },
          dataType: 'JSON',
          success: function (data) {
            // console.log(data);
            $('#expire_date').val(data['time']);
            $('.stc-select-customer').html(data['customer']);
          }
        });
      }

      // call order on page load
      stc_call_ag_order();
      function stc_call_ag_order() {
        $.ajax({
          url: "kattegat/ragnar_order.php",
          method: "post",
          data: { callorder: 1 },
          dataType: 'JSON',
          success: function (data) {
            // console.log(data);
            $('.stc-view-ag-order-form').html(data);
          }
        });
      }

      var jsbegdate = '';
      var jsenddate = '';
      var jscustomerid = '';
      var jsordernumber = '';
      var jsstatus = '';
      var jsordbegvalue = '';
      var jsordendvalue = '';
      // filter challan
      // by date
      $('body').delegate('#purchaseproddatefilt', 'click', function (e) {
        e.preventDefault();
        jsbegdate = $('.begdate').val();
        jsenddate = $('.enddate').val();
        jscustomerid = $("#stc-order-customer-in").val();
        jsordernumber = $("#stc-order-number-finder").val();
        jsorderagents = $("#stc-order-agents-finder").val();
        jsstatus = $(".stc-order-status-in").val();
        jsordbegvalue = $('.orbegvalueinputsearch').val();
        jsordendvalue = $('.orendvalueinputsearch').val();
        stc_filter_customer_order(jsbegdate, jsenddate, jscustomerid, jsordernumber, jsorderagents, jsstatus, jsordbegvalue, jsordendvalue);
      });

      // by customer id
      $("#stc-order-customer-in").change(function (e) {
        e.preventDefault();
        jsbegdate = $('.begdate').val();
        jsenddate = $('.enddate').val();
        jscustomerid = $("#stc-order-customer-in").val();
        jsordernumber = $("#stc-order-number-finder").val();
        jsorderagents = $("#stc-order-agents-finder").val();
        jsstatus = $(".stc-order-status-in").val();
        jsordbegvalue = $('.orbegvalueinputsearch').val();
        jsordendvalue = $('.orendvalueinputsearch').val();
        stc_filter_customer_order(jsbegdate, jsenddate, jscustomerid, jsordernumber, jsorderagents, jsstatus, jsordbegvalue, jsordendvalue);
      });

      // by challan number
      $("#stc-order-number-finder").on('keyup', function (e) {
        e.preventDefault();
        jsbegdate = $('.begdate').val();
        jsenddate = $('.enddate').val();
        jscustomerid = $("#stc-order-customer-in").val();
        jsordernumber = $("#stc-order-number-finder").val();
        jsorderagents = $("#stc-order-agents-finder").val();
        jsstatus = $(".stc-order-status-in").val();
        jsordbegvalue = $('.orbegvalueinputsearch').val();
        jsordendvalue = $('.orendvalueinputsearch').val();
        stc_filter_customer_order(jsbegdate, jsenddate, jscustomerid, jsordernumber, jsorderagents, jsstatus, jsordbegvalue, jsordendvalue);
      });

      // by challan number
      $("#stc-order-agents-finder").on('keyup', function (e) {
        e.preventDefault();
        jsbegdate = $('.begdate').val();
        jsenddate = $('.enddate').val();
        jscustomerid = $("#stc-order-customer-in").val();
        jsordernumber = $("#stc-order-number-finder").val();
        jsorderagents = $("#stc-order-agents-finder").val();
        jsstatus = $(".stc-order-status-in").val();
        jsordbegvalue = $('.orbegvalueinputsearch').val();
        jsordendvalue = $('.orendvalueinputsearch').val();
        stc_filter_customer_order(jsbegdate, jsenddate, jscustomerid, jsordernumber, jsorderagents, jsstatus, jsordbegvalue, jsordendvalue);
      });

      // by status
      $(".stc-order-status-in").change(function (e) {
        e.preventDefault();
        jsbegdate = $('.begdate').val();
        jsenddate = $('.enddate').val();
        jscustomerid = $("#stc-order-customer-in").val();
        jsordernumber = $("#stc-order-number-finder").val();
        jsorderagents = $("#stc-order-agents-finder").val();
        jsstatus = $(".stc-order-status-in").val();
        jsordbegvalue = $('.orbegvalueinputsearch').val();
        jsordendvalue = $('.orendvalueinputsearch').val();
        stc_filter_customer_order(jsbegdate, jsenddate, jscustomerid, jsordernumber, jsorderagents, jsstatus, jsordbegvalue, jsordendvalue);
      });

      // paging before search
      $('body').delegate('.ordbegbuttoninvsearch', 'click', function (e) {
        e.preventDefault();
        jsordbegvalue = $('.orbegvalueinputsearch').val();
        jsordendvalue = $('.orendvalueinputsearch').val();
        if (jsordbegvalue == 0) {
          alert("Seriously!!!");
        } else {
          outbegvalueinputted = (+jsordbegvalue) - 20;
          outendvalueinputted = (+jsordendvalue) - 20;
          jsbegdate = $('.begdate').val();
          jsenddate = $('.enddate').val();
          jscustomerid = $("#stc-order-customer-in").val();
          jsordernumber = $("#stc-order-number-finder").val();
          jsorderagents = $("#stc-order-agents-finder").val();
          jsstatus = $(".stc-order-status-in").val();
          jsordbegvalue = outbegvalueinputted;
          jsordendvalue = outendvalueinputted;
          stc_filter_customer_order(jsbegdate, jsenddate, jscustomerid, jsordernumber, jsorderagents, jsstatus, jsordbegvalue, jsordendvalue);
        }
      });

      // paging after search
      $('body').delegate('.ordendbuttoninvsearch', 'click', function (e) {
        e.preventDefault();
        jsordbegvalue = $('.orbegvalueinputsearch').val();
        jsordendvalue = $('.orendvalueinputsearch').val();
        if (jsordendvalue == 0) {
          alert("Seriously!!!");
        } else {
          outbegvalueinputted = (+jsordbegvalue) + 20;
          outendvalueinputted = (+jsordendvalue) + 20;
          jsbegdate = $('.begdate').val();
          jsenddate = $('.enddate').val();
          jscustomerid = $("#stc-order-customer-in").val();
          jsordernumber = $("#stc-order-number-finder").val();
          jsorderagents = $("#stc-order-agents-finder").val();
          jsstatus = $(".stc-order-status-in").val();
          jsordbegvalue = outbegvalueinputted;
          jsordendvalue = outendvalueinputted;
          stc_filter_customer_order(jsbegdate, jsenddate, jscustomerid, jsordernumber, jsorderagents, jsstatus, jsordbegvalue, jsordendvalue);
        }
      });

      // filter function
      function stc_filter_customer_order(jsbegdate, jsenddate, jscustomerid, jsordernumber, jsorderagents, jsstatus, jsordbegvalue, jsordendvalue) {
        $.ajax({
          url: "kattegat/ragnar_order.php",
          method: "post",
          data: {
            stcorderaction: 1,
            phpbegdate: jsbegdate,
            phpenddate: jsenddate,
            phpcustomerid: jscustomerid,
            phpordernumber: jsordernumber,
            phporderagents: jsorderagents,
            phpstatus: jsstatus,
            phpordbegvalue: jsordbegvalue,
            phpordendvalue: jsordendvalue
          },
          // dataType : 'JSON',
          success: function (data) {
            // console.log(data);
            $('.stc-view-ag-order-form').html(data);
          }
        });
      }

      // show orders
      $('body').delegate('.stc_view_order', 'click', function (e) {
        e.preventDefault();
        $('.stc-view').fadeOut(500);
        $('.stc-add').toggle(1000);
        var stc_order_id = $(this).attr("id");
        $.ajax({
          url: "kattegat/ragnar_order.php",
          method: 'POST',
          data: {
            call_order_sub: 1,
            stc_order_id: stc_order_id
          },
          dataType: 'JSON',
          success: function (data) {
            // console.log(data);
            $('#order-challan').val('STC/A/' + data['order_number']);
            $('#ag-challan-order').val(data['real_order_number']);
            $('#ag-order-date').val(data['order_date']);
            $('#cust_name').val(data['customer_name']);
            $('#ag-info').val('STC/AT/' + data['agentname']);
            $('#site-name').val(data['sitename']);
            $('#ag-refrence').val(data['sitename']);
            $('#site-address').val(data['siteaddress']);
            $('.ag-order-items').html(data['line_items']);
            $('#stc-proj-id').val(data['project_id']);
            $('#stc-super-id').val(data['supervisor_id']);
            $('#stc-requis-id').val(data['requis_id']);
          }
        });
      });

      // go to cart sess
      $('body').delegate('.stcagordgochcart', 'click', function (e) {
        e.preventDefault();
        var stc_pd_id = $(this).attr("id");
        var stc_rem_ord_qty = $('.disp-qty' + stc_pd_id).val();
        var stc_inv_ord_qty = $('.inv-qty' + stc_pd_id).val();
        var stc_pd_price = $('.pd-price' + stc_pd_id).val();
        $.ajax({
          url: "kattegat/ragnar_order.php",
          method: 'POST',
          data: {
            gooritemoncart: 1,
            stc_pd_id: stc_pd_id,
            stc_inv_ord_qty: stc_inv_ord_qty,
            stc_pd_price: stc_pd_price,
            stc_rem_ord_qty: stc_rem_ord_qty
          },
          // dataType : 'JSON',
          success: function (data) {
            // console.log(data);
            alert(data);
          }
        });
      });

      // save challan
      $('body').delegate('.save-ag-challan', 'click', function (e) {
        e.preventDefault();
        var stc_ch_id = $('#ag-challan-order').val();
        var stc_ch_notes = $('#ag-notes').val();
        var stc_ch_tandc = $('#ag-tandc').val();
        var stc_ch_waybilno = $('#ag-way-bill-no').val();
        var stc_ch_lrno = $('#ag-lr-no').val();
        var stc_invqty = $('#inv-qty').val();
        var proj_id = $('#stc-proj-id').val();
        var super_id = $('#stc-super-id').val();
        var requise_id = $('#stc-requis-id').val();
        $.ajax({
          url: "kattegat/ragnar_order.php",
          method: 'POST',
          data: {
            go_to_challan: 1,
            stc_ch_id: stc_ch_id,
            stc_ch_notes: stc_ch_notes,
            stc_ch_tandc: stc_ch_tandc,
            stc_ch_waybilno: stc_ch_waybilno,
            stc_ch_lrno: stc_ch_lrno,
            stc_invqty: stc_invqty,
            proj_id: proj_id,
            super_id: super_id,
            requise_id: requise_id
          },
          dataType: 'JSON',
          success: function (response) {
            // console.log(response);
            alert(response);
            stc_call_ag_order();
            $('.stc-add').fadeOut(1000);
            $('.stc-view').toggle(500);
            // window.location.reload();
          }
        });
      });

      // set to cleaned
      $('body').delegate('.set-to-cleaned', 'click', function (e) {
        e.preventDefault();
        var stc_order_no = $('#ag-challan-order').val();
        $.ajax({
          url: "kattegat/ragnar_order.php",
          method: 'POST',
          data: {
            set_to_clean: 1,
            stc_order_no: stc_order_no
          },
          dataType: 'JSON',
          success: function (response) {
            // console.log(response);
            alert(response);
            stc_call_ag_order();
            $('.stc-add').fadeOut(1000);
            $('.stc-view').toggle(500);
          }
        });
      });

      // add to cart on po
      $('body').delegate('.stcagordgopocart', 'click', function (e) {
        e.preventDefault();
        var product_id = $(this).attr("id");
        var stc_rem_ord_qty = $('.disp-qty' + product_id).val();
        var add_to_sale_cart = "addsalecart";
        $.ajax({
          url: "kattegat/ragnar_order.php",
          method: "POST",
          data: {
            product_id: product_id,
            stc_rem_ord_qty: stc_rem_ord_qty,
            add_po_fr_ag: 1
          },
          success: function (data) {
            alert(data);
            // console.log(data);                              
          }
        });
      });
    });
  </script>
  <script>
    $(document).ready(function () {

      // stc_call_merchant();
      // call merchant
      function stc_call_merchant() {
        $.ajax({
          url: "kattegat/ragnar_order.php",
          method: "post",
          data: {
            stc_call_merchant: 1
          },
          success: function (response_merchant) {
            // console.log(response_merchant);
            $('.stc-call-merchant').html(response_merchant);
          }
        });
      }

      // call requisition on page load
      stc_call_ag_requisition();
      function stc_call_ag_requisition() {
        $.ajax({
          url: "kattegat/ragnar_order.php",
          method: "post",
          data: { callrequisition: 1 },
          dataType: 'JSON',
          success: function (data) {
            // console.log(data);
            $('.stc-view-ag-requisition-form').html(data);
          }
        });
      }
      var jsreqbegdate = '';
      var jsreqenddate = '';
      var jsreqcustomerid = '';
      var jsreqnumber = '';
      var jsreqsitename = '';
      var jsreqmaterial = '';
      var jsbegvalue = '';
      var jsendvalue = '';
      // filter challan
      // by date
      $('body').delegate('#aorfilterbtn', 'click', function (e) {
        e.preventDefault();
        jsreqbegdate = $('.reqbegdate').val();
        jsreqenddate = $('.reqenddate').val();
        jsreqcustomerid = $("#stc-requisition-customer-in").val();
        jsreqnumber = $("#stc-requisition-number-finder").val();
        jsreqsitename = $("#stc-requisition-sitename-finder").val();
        jsreqmaterial = $(".stc-requisition-material-in").val();
        jsbegvalue = $('.begvalueinputsearch').val();
        jsendvalue = $('.endvalueinputsearch').val();
        stc_filter_customer_requisition(jsreqbegdate, jsreqenddate, jsreqcustomerid, jsreqnumber, jsreqsitename, jsreqmaterial, jsbegvalue, jsendvalue);
      });

      // paging before search
      $('body').delegate('.begbuttoninvsearch', 'click', function (e) {
        e.preventDefault();
        begvalue = $('.begvalueinputsearch').val();
        endvalue = $('.endvalueinputsearch').val();
        if (endvalue == 0) {
          alert("Invalid pagination.");
        } else {
          outbegvalueinputted = (+begvalue) - 20;
          outendvalueinputted = (+endvalue) - 20;
          jsreqbegdate = $('.reqbegdate').val();
          jsreqenddate = $('.reqenddate').val();
          jsreqcustomerid = $("#stc-requisition-customer-in").val();
          jsreqnumber = $("#stc-requisition-number-finder").val();
          jsreqsitename = $("#stc-requisition-sitename-finder").val();
          jsreqmaterial = $(".stc-requisition-material-in").val();
          jsbegvalue = outbegvalueinputted;
          jsendvalue = outendvalueinputted;
          stc_filter_customer_requisition(jsreqbegdate, jsreqenddate, jsreqcustomerid, jsreqnumber, jsreqsitename, jsreqmaterial, jsbegvalue, jsendvalue);
        }
      });

      // paging after search
      $('body').delegate('.endbuttoninvsearch', 'click', function (e) {
        e.preventDefault();
        begvalue = $('.begvalueinputsearch').val();
        endvalue = $('.endvalueinputsearch').val();
        if (endvalue == 0) {
          alert("Invalid pagination.");
        } else {
          outbegvalueinputted = (+begvalue) + 20;
          outendvalueinputted = (+endvalue) + 20;
          jsreqbegdate = $('.reqbegdate').val();
          jsreqenddate = $('.reqenddate').val();
          jsreqcustomerid = $("#stc-requisition-customer-in").val();
          jsreqnumber = $("#stc-requisition-number-finder").val();
          jsreqsitename = $("#stc-requisition-sitename-finder").val();
          jsreqmaterial = $(".stc-requisition-material-in").val();
          jsbegvalue = outbegvalueinputted;
          jsendvalue = outendvalueinputted;
          stc_filter_customer_requisition(jsreqbegdate, jsreqenddate, jsreqcustomerid, jsreqnumber, jsreqsitename, jsreqmaterial, jsbegvalue, jsendvalue);
        }
      });

      // filter function
      function stc_filter_customer_requisition(jsreqbegdate, jsreqenddate, jsreqcustomerid, jsreqnumber, jsreqsitename, jsreqmaterial, jsbegvalue, jsendvalue) {
        $.ajax({
          url: "kattegat/ragnar_order.php",
          method: "post",
          data: {
            stcreqaction: 1,
            phpreqbegdate: jsreqbegdate,
            phpreqenddate: jsreqenddate,
            phpreqcustomerid: jsreqcustomerid,
            phpreqnumber: jsreqnumber,
            phpreqsitename: jsreqsitename,
            phpreqmaterials: jsreqmaterial,
            phpbegvalue: jsbegvalue,
            phpendvalue: jsendvalue
          },
          // dataType : 'JSON',
          success: function (data) {
            // console.log(data);
            $('.stc-view-ag-requisition-form').html(data);
          }
        });
      }

      // select merchant for purchase 
      $('body').delegate('.stc-call-for-select-merchant-req', 'click', function () {
        var req_comb_id = $(this).attr("id");
        // $('#stc-req-list-rep').val(req_comb_id);
        $.ajax({
          url: "kattegat/ragnar_order.php",
          method: "post",
          data: {
            stc_call_req_items_for_merchant: 1,
            stc_req_comb_id: req_comb_id
          },
          success: function (response_merchandise_data) {
            $('.stc-req-item-show').html(response_merchandise_data);
            // stc_call_merchant();
          }
        });
        $('.stc-call-for-select-merchant-res').modal("show");
      });

      // show merchanise modal
      $('body').delegate('.stc-agent-req-get-mer-product', 'click', function (e) {
        e.preventDefault();
        var req_id = $(this).attr("id");
        $('.stc-agent-req-get-mer-product-show').modal('show');
        $('.stc-req-id-store').val(req_id);
      });

      // material name change requistion
      $('body').delegate('.stc-update-requis-material-name-hit', 'focusout', function (e) {
        e.preventDefault();
        var material_id = $(this).attr("id");
        var material_name = $(this).val();

        if (window.confirm("Are you sure, You want to change item name to " + material_name)) {
          $.ajax({
            url: "kattegat/ragnar_order.php",
            method: "post",
            data: {
              stc_update_requisition_items_hit: 1,
              material_id: material_id,
              material_name: material_name
            },
            success: function (response_material_name) {
              alert(response_material_name);
            }
          });
        }
      });

      var jsfiltercat;
      var jsfiltersubcat;
      var jsfiltername;

      // search item on a modal
      $('body').delegate('.stcprosearchhit', 'click', function (e) {
        e.preventDefault();
        jsfiltercat = $('#filterbycat').val();
        jsfiltersubcat = $('#filterbysubcat').val();
        jsfiltername = $('#searchbystcname').val();
        stc_filter_pro_forrequist(jsfiltercat, jsfiltersubcat, jsfiltername);
      });

      // filter function
      function stc_filter_pro_forrequist(jsfiltercat, jsfiltersubcat, jsfiltername) {
        $.ajax({
          url: "kattegat/ragnar_order.php",
          method: "post",
          data: {
            stccallmercaction: 1,
            phpfiltercatout: jsfiltercat,
            phpfiltersubcatout: jsfiltersubcat,
            phpfilternameout: jsfiltername
          },
          // dataType : 'JSON',
          success: function (data) {
            // console.log(data);
            $('.stc-req-product-show').html(data);
          }
        });
      }

      // add product for savess
      $('body').delegate('.add_to_requist_mer', 'click', function (e) {
        e.preventDefault();
        var pd_id = $(this).attr("id");
        var pd_name = $(this).attr("pd-name");
        var req_it_id = $('.stc-req-id-store').val();
        $.ajax({
          url: "kattegat/ragnar_order.php",
          method: "post",
          data: {
            stc_call_pd_purchase_from: 1,
            pd_id: pd_id
          },
          success: function (response_requisition) {
            $('.stc-req-product-id-show-tag' + req_it_id).val(pd_id);
            $('.stc-req-product-show-tag' + req_it_id).html(pd_name + ' / ' + response_requisition);
            alert("Product added successfully.");
            $(".stc-agent-req-get-mer-product-show").modal("hide");
          }
        });
      });

      // add product to db
      $('body').delegate('.stc-req-merchandise-save', 'click', function (e) {
        e.preventDefault();
        var req_id = $(this).attr("id");
        var stc_pd_id = $('.stc-req-product-id-show-tag' + req_id).val();
        var stc_merchant_id = $('#stc-req-mer' + req_id).val();
        $.ajax({
          url: "kattegat/ragnar_order.php",
          method: "post",
          data: {
            stc_requst_merchandise_hit: 1,
            stc_req_id: req_id,
            stc_pd_id: stc_pd_id,
            stc_merchant_id: stc_merchant_id
          },
          success: function (response_requisition) {
            alert(response_requisition);
          }
        });
      });

      // change unit & quantity 
      $('body').delegate('.stc-sup-req-something-change-hit', 'click', function (e) {
        e.preventDefault();
        var req_item_id = $(this).attr('id');
        var req_item_type = $('.stc-sup-req-type-change' + req_item_id).val();
        var req_item_unit = $('.stc-sup-req-unit-change' + req_item_id).val();
        var req_item_qty = $('.stc-sup-req-qty-change' + req_item_id).val();
        if (window.confirm("Are you sure, You Changed the Item Title Before Changing the Item Type, Quantity & Unit???")) {
          $.ajax({
            url: "kattegat/ragnar_order.php",
            method: "POST",
            data: {
              stc_req_item_change_hit: 1,
              req_item_id: req_item_id,
              req_item_type: req_item_type,
              req_item_unit: req_item_unit,
              req_item_qty: req_item_qty
            },
            success: function (response_change) {
              alert(response_change);
            }
          });
        }
      });
    });
  </script>
  <script>
    $(document).ready(function () {
      $("input[type=checkbox]").css({ 'display': '' });
      var sitebegdate = $('.sitebegdate').val();
      var siteenddate = $('.siteenddate').val();
      stc_call_sitename(sitebegdate, siteenddate);
      // call site with in 2 days
      function stc_call_sitename(sitebegdate, siteenddate) {
        $.ajax({
          url: "kattegat/ragnar_order.php",
          method: "POST",
          data: {
            stc_call_sitename: 1,
            sitebegdate: sitebegdate,
            siteenddate: siteenddate
          },
          success: function (response_sitename) {
            // console.log(response_sitename);
            $('.stc-call-sitename').html(response_sitename);
          }
        });
      }

      // get sitename
      $('body').delegate('#sitename_date_filter', 'click', function (e) {
        e.preventDefault();
        var sitebegdate = $('.sitebegdate').val();
        var siteenddate = $('.siteenddate').val();
        stc_call_sitename(sitebegdate, siteenddate);
      });

      // check result
      $('body').delegate("#stc-check-box-site-submit", 'click', function (e) {
        e.preventDefault();
        var selectedLanguage = new Array();
        $('input[name="stc-check-box-site-view"]:checked').each(function () {
          selectedLanguage.push(this.value);
        });
        if (selectedLanguage.length > 0) {
          $.ajax({
            url: "kattegat/ragnar_order.php",
            method: "POST",
            data: {
              stc_check_box_view: 1,
              selectedLanguage: selectedLanguage
            },
            success: function (response_view) {
              var response = response_view.trim();
              if (response == "Site selected!!!") {
                window.open("get-requisition.php");
              } else {
                alert(response_view);
              }
            }
          });
        } else {
          alert("Please add site first.");
        }
      });
    });
  </script>
  <script>
    function myFunction() {
      var input, filter, table, tr, td, i;
      input = document.getElementById("stc-req-item-track-input");
      filter = input.value.toUpperCase();
      table = document.getElementById("stc-req-item-track-table");
      tr = table.getElementsByTagName("tr");
      for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[2];
        if (td) {
          txtValue = td.textContent || td.innerText;
          if (txtValue.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          } else {
            tr[i].style.display = "none";
          }
        }
      }
    }
    $(document).ready(function () {
      $('body').delegate('#rit_date_filter', 'click', function (e) {
        e.preventDefault();
        var js_rit_begdate = $('.ritbegdate').val();
        var js_rit_enddate = $('.ritenddate').val();
        var js_rit_type = $('.rittypeselect').val();
        $.ajax({
          url: "kattegat/ragnar_order.php",
          method: "POST",
          data: {
            stc_call_rit_items_req: 1,
            rit_begdate: js_rit_begdate,
            rit_enddate: js_rit_enddate,
            js_rit_type: js_rit_type
          },
          dataType: "JSON",
          success: function (response_rit) {
            // console.log(response_rit);
            $('.stc-call-rit-items').focus();
            $('.stc-call-rit-items').html(response_rit);
          }
        });
      });
    });
  </script>
  <script>
    $(document).ready(function () {
      $('body').delegate('.stc-req-list-find', 'click', function () {
        var req_begdate = $('.reqlistbegdate').val();
        var req_enddate = $('.reqlistenddate').val();
        var req_customer = $('#stc-requisitionlist-customer-in').val();
        var req_reqnumber = $('#stc-requisitionlist-number-finder').val();
        var req_sitenmae = $('#stc-requisitionlist-sitename-finder').val();
        var req_materialtype = $('#stc-requisitionlist-materialtype').val();
        var validation = 1;
        var page = 1;
        $(this).attr('disabled');
        $('.stc-view-ag-requisitionbylist-form').html("<p style='color:red;'>Working on it, Please wait...</p>");
        $('.req-alert-text').remove();

        if (req_begdate == '') {
          $('.reqenddate').closest('td').find('p:eq(1)').after("<p class='req-alert-text' style='color:red;'>Date required</p>");
          validation = 0;
        }

        // if(req_materialtype=='NA'){
        //   $('#stc-requisitionlist-materialtype').after("<p class='req-alert-text' style='color:red;'>Material type required</p>");
        //   validation=0;
        // }
        if (validation == 1) {
          $('.spinner').show();
          $.ajax({
            url: "kattegat/ragnar_order.php",
            method: "POST",
            data: {
              stc_rquisition_bylist_find: 1,
              req_begdate: req_begdate,
              req_enddate: req_enddate,
              req_customer: req_customer,
              req_reqnumber: req_reqnumber,
              req_sitenmae: req_sitenmae,
              req_materialtype: req_materialtype,
              page: page
            },
            success: function (response) {
              $('.stc-view-ag-requisitionbylist-form').html(response);
              $('.stc-req-list-find').removeAttr('disabled');
            },
            complete: function () {
              // Hide the spinner after the AJAX request completes
              $('.spinner').hide();
            }
          });
        } else {
          alert("Please fill required fields.");
        }
      });

      $('body').delegate('.page-link', 'click', function (e) {
        e.preventDefault();
        var req_begdate = $('.reqlistbegdate').val();
        var req_enddate = $('.reqlistenddate').val();
        var req_customer = $('#stc-requisitionlist-customer-in').val();
        var req_reqnumber = $('#stc-requisitionlist-number-finder').val();
        var req_sitenmae = $('#stc-requisitionlist-sitename-finder').val();
        var req_materialtype = $('#stc-requisitionlist-materialtype').val();
        var page = $(this).data('page'); // Get the selected page number
        $('.spinner').show();
        // Repeat the AJAX request with the selected page
        $.ajax({
          url: "kattegat/ragnar_order.php",
          method: "POST",
          data: {
            stc_rquisition_bylist_find: 1,
            req_begdate: req_begdate,
            req_enddate: req_enddate,
            req_customer: req_customer,
            req_reqnumber: req_reqnumber,
            req_sitenmae: req_sitenmae,
            req_materialtype: req_materialtype,
            page: page
          },
          success: function (response) {
            $('.stc-view-ag-requisitionbylist-form').html(response);
          },
          complete: function () {
            // Hide the spinner after the AJAX request completes
            $('.spinner').hide();
          }
        });
      });


      $('body').delegate('.stc-req-item-static-search', "keyup", function () {
        var value = $(this).val().toLowerCase();
        $(".stc-reqbyitem-table tbody tr").filter(function () {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
      });

      $('body').delegate('.poadhocitem', 'change', function (e) {
        e.preventDefault();
        var qty = $('#poadhocitem option:selected').attr('qty');
        var unit = $('#poadhocitem option:selected').attr('unit');
        var rack = $('#poadhocitem option:selected').attr('rack');
        $('.stcbalancedqty').val(qty);
        $('.stcbalancedqtyunit').val(unit);
        $('.stcbalancedqtyrack').val(rack);
      });

      var repid2 = 0;
      var repitemid2 = 0;
      $('body').delegate('.req-product-Modal-cash-close', 'click', function (e) {
        e.preventDefault();
        repid2 = $(this).attr("id");
        repitemid2 = $(this).attr("list-id");
        orderqty = $(this).attr("orderqty");
        $('#stcdispatchedqty').val('');
        // $('.res-product-Modal-cash-close').modal("show");
        $('#stc-req-list-id-rep2').val(repid2);
        $('#stc-req-list-item-id-rep2').val(repitemid2);
        $('#stc-req-list-item-id-orderqty').remove();
        $('#stc-req-list-item-id-rep2').after('<input type="hidden" id="stc-req-list-item-id-orderqty" value="' + orderqty + '">');
      });

      // save dispatch quantity
      $('body').delegate('.stcdispatchedbtn', 'click', function (e) {
        e.preventDefault();
        var req_id = $('#stc-req-list-id-rep2').val();
        var req_item_id = $('#stc-req-list-item-id-rep2').val();
        var tools_id = $('#poadhoctools').val();
        var dispatch_qnty = parseFloat($('#stcdispatchedqty').val());
        var orderqty = parseFloat($('#stc-req-list-item-id-orderqty').val());
        var poadhocitem = $('#poadhocitem').val();
        var balanced_qty = parseFloat($('#stcbalancedqty').val());
        if ((dispatch_qnty > orderqty)) {
          alert("Invalid quantity.");
        } else if ((dispatch_qnty > balanced_qty)) {
          alert("Invalid quantity.");
        } else {
          // if(orderqty!="0.00"){
          $.ajax({
            url: "kattegat/ragnar_order.php",
            method: 'POST',
            data: {
              stc_dispatch_hit: 1,
              stc_req_id: req_id,
              stc_tools_id: tools_id,
              stc_req_item_id: req_item_id,
              stc_dispatch_qty: dispatch_qnty,
              poadhocitem: poadhocitem
            },
            success: function (response_dis) {
              var response = response_dis.trim();
              if (response == "Item dispatched successfully.") {
                alert(response_dis);
                $('#' + req_id).hide();
                $('#stcdispatchedqty').val('');
                $('#poadhocitem').val("NA");
              } else {
                alert(response_dis);
              }
            }
          });
          // }else{
          //   alert("Item not found.");
          // }
        }
      });


      // Cache the select element for better performance
      var $select = $('#poadhocitem');

      // Cache the input element
      var $searchInput = $('#searchInput');

      // Get all options
      var $options = $select.find('option');

      // Handle input keyup event
      $searchInput.on('keyup', function () {
        var filter = $searchInput.val().toUpperCase();

        // Clear the select options
        $select.empty();

        // Loop through all options
        $options.each(function () {
          var text = $(this).text().toUpperCase();

          // Show/hide options based on the search filter
          if (text.indexOf(filter) > -1) {
            $select.append($(this).clone()); // Append matching options
          }
        });
        $('.poadhocitem').change();
      });


      // Cache the select element for better performance
      var $select2 = $('#poadhoctools');

      // Cache the input element
      var $searchInput2 = $('#searchInput2');

      // Get all options
      var $options2 = $select2.find('option');

      // Handle input keyup event
      $searchInput2.on('keyup', function () {
        var filter = $searchInput2.val().toUpperCase();

        // Clear the select options
        $select2.empty();

        // Loop through all options
        $options2.each(function () {
          var text = $(this).text().toUpperCase();

          // Show/hide options based on the search filter
          if (text.indexOf(filter) > -1) {
            $select2.append($(this).clone()); // Append matching options
          }
        });
        $('.poadhoctools').change();
      });
    });
  </script>
  <script>
    $(document).ready(function () {
      stc_call_ppesummary();
      function stc_call_ppesummary() {
        $.ajax({
          url: "kattegat/ragnar_order.php",
          method: "post",
          data: { ppesummary: 1 },
          dataType: 'JSON',
          success: function (response) {
            // console.log(data);
            var data = '';
            var legguardcounter = 0, safetygogglescounter = 0, safetyjacketcounter = 0, safetyshoescounter = 0, safetyhelmetcounter = 0, handglovescounter = 0, earplugcounter = 0;
            for (var i = 0; i < response.length; i++) {
              if (response[i].stc_item_tracker_toppe == "Leg Guard") { legguardcounter++; }
              if (response[i].stc_item_tracker_toppe == "Safety Goggles") { safetygogglescounter++; }
              if (response[i].stc_item_tracker_toppe == "Safety Jacket") { safetyjacketcounter++; }
              if (response[i].stc_item_tracker_toppe == "Safety Shoes") { safetyshoescounter++; }
              if (response[i].stc_item_tracker_toppe == "Safety Helmet") { safetyhelmetcounter++; }
              if (response[i].stc_item_tracker_toppe == "Hand Gloves") { handglovescounter++; }
              if (response[i].stc_item_tracker_toppe == "Ear Plug") { earplugcounter++; }
              data += '<tr><td>' + response[i].stc_item_tracker_user_id + '</td><td>' + response[i].stc_item_tracker_toppe + '</td><td>' + response[i].stc_item_tracker_qty + '</td><td>' + response[i].stc_item_tracker_unit + '</td><td>' + response[i].stc_item_tracker_issuedate + '</td><td>' + response[i].expiration_date + '</td><td>' + response[i].stc_item_tracker_remarks + '</td></tr>';
            }
            $('#ppesummary').html(data);
            $('.legguardsummary').html(legguardcounter);
            $('.safetygogglessummary').html(safetygogglescounter);
            $('.safetyjacketsummary').html(safetyjacketcounter);
            $('.safetyshoessummary').html(safetyshoescounter);
            $('.safetyhelmetsummary').html(safetyhelmetcounter);
            $('.handglovessummary').html(handglovescounter);
            $('.earplugsummary').html(earplugcounter);
          }
        });
      }
      item_tracker_call('', 1);
      function item_tracker_call(searchTerm, page = 1) {
        $.ajax({
          url: "kattegat/ragnar_order.php",
          method: "POST",
          data: {
            call_item_tracker: 1,
            searchTerm: searchTerm,
            page: page
          },
          success: function (response) {
            $('.item-tracker-show').html(response);
          }
        });
      }

      $('body').delegate('#searchInputppebtn', 'click', function (e) {
        var searchTerm = $('#searchInputppe').val().toLowerCase();
        if (searchTerm != '') {
          item_tracker_call(searchTerm);
        }
      });

      $('body').delegate('.page-link2', 'click', function (e) {
        e.preventDefault();
        var page = $(this).data('page');
        var searchTerm = $('#searchInputppe').val().toLowerCase();
        item_tracker_call(searchTerm, page);
      });


      // save dispatch
      $('body').delegate('.it-save', 'click', function (e) {
        e.preventDefault();
        $(this).prop('disabled', true);
        var user_id = $('.it-emp-name').val();
        var ppe_type = getCheckedValues('it-ppe-type');//$('.it-ppe-type').val();
        var qty = $('.it-qty').val();
        var unit = getCheckedAttributesunit('it-ppe-type');//$('.it-unit').val();
        var issue_date = $('.it-issue-date').val();
        var validity = getCheckedAttributes('it-ppe-type');//$('.it-validity').val();
        var remarks = $('.it-remarks').val();
        if (ppe_type != '') {
          $.ajax({
            url: "kattegat/ragnar_order.php",
            method: "POST",
            data: {
              save_item_tracker: 1,
              user_id: user_id,
              ppe_type: ppe_type,
              qty: qty,
              unit: unit,
              issue_date: issue_date,
              validity: validity,
              remarks: remarks
            },
            success: function (response) {
              var obj_response = response.trim();
              if (obj_response == "yes") {
                alert("Record updated successfully!!!");
                $('.it-emp-name').val('');
                $('.it-ppe-type:checked').prop('checked', false);
                $(".it-save").prop('disabled', false);
                item_tracker_call();
              } else if (obj_response == "reload") {
                window.location.reload();
              } else if (obj_response == "empty") {
                alert("Please fill complete details.");
              } else if (obj_response == "no") {
                alert("Something went wrong. Record not updated");
              }
            }
          });
        } else {
          alert("Please check Type of PPE.");
        }
      });

      function getCheckedValues(class_name) {
        var filter = [];
        $('.' + class_name + ':checked').each(function () {
          filter.push($(this).val());
        });
        return filter;
      }

      function getCheckedAttributes(class_name) {
        var filter = [];
        $('.' + class_name + ':checked').each(function () {
          filter.push($(this).attr('validity'));
        });
        return filter;
      }

      function getCheckedAttributesunit(class_name) {
        var filter = [];
        $('.' + class_name + ':checked').each(function () {
          filter.push($(this).attr('unit'));
        });
        return filter;
      }

      function resetFormFields() {
        $('.formcontrol').find('input, textarea').val('');
        $('.formcontrol').find('input:checked').prop('checked', false);
      }
      call_tools_tracker('');
      // Search button click event
      $('body').delegate('#itt-toolssearchInputbtn', 'click', function (e) {
        var searchTerm = $('#itt-toolssearchInput').val().toLowerCase();
        if (searchTerm != '') {
          call_tools_tracker(searchTerm);
        }
      });

      // Pagination link click event
      $('body').delegate('.page-link3', 'click', function (e) {
        e.preventDefault();
        var page = $(this).data('page');
        var searchTerm = $('#itt-toolssearchInput').val().toLowerCase();
        call_tools_tracker(searchTerm, page);
      });

      // call tools tracker
      function call_tools_tracker(search, page = 1) {
        $('.spinner').show();
        $.ajax({
          url: "kattegat/ragnar_order.php",
          method: "POST",
          data: {
            call_tools_tracker: 1,
            search: search,
            page: page
          },
          dataType: "JSON",
          success: function (response) {
            var data = '';
            // Check if response data is valid
            if (response.data && response.data.length > 0) {
              var slno = (page - 1) * 10;
              response.data.forEach(function (item, index) {
                slno++;
                var color='#ff8f8f';
                if(item.status=='Active'){
                  color='#adffad';
                }
                data += '<tr style="background-color:' + color + '"><td>' + slno + '</td><td>' + item.unique_id + '</td><td>' + item.itemdescription + '</td><td>' + item.machinesrno + '</td><td>' + item.make + '</td><td>' + item.tooltype + '</td><td>' + item.purchase_details + '</td><td>' + item.warranty + '</td><td>' + item.taxinvono + '</td><td>' + item.taxinvodate + '</td><td>' + item.remarks + '</td><td>' + item.created_dates + '</td><td>' + item.stc_user_name + '</td><td><a href="javascript:void(0)" class="btn btn-primary itt-edit-toolsdetails" id="' + item.id + '" data-toggle="modal" data-target=".bd-toolstracker-modal-lg"><i class="fa fa-edit"></i></a><a href="javascript:void(0)" class="btn btn-primary itt-toolstracking" id="' + item.id + '" data-toggle="modal" data-target=".bd-toolstrackertracker-modal-lg"><i class="fa fa-plus"></i></a><a href="javascript:void(0)" class="btn btn-primary itt-toolstrackingshow" id="' + item.id + '" data-toggle="modal" data-target=".bd-toolstrackertrackershow-modal-lg"><i class="fa fa-shipping-fast"></i></a></td></tr>';
              });
            } else {
              data = "<td colspan='14'>No data found.</td>";
            }
            $('.tools-tracker-show').html(data);

            // Pagination links
            var pagination = '';
            for (var i = 1; i <= response.total_pages; i++) {
              if (i == page) {
                pagination += '<a href="javascript:void(0)" class="page-link3" style="background: #00ff5a; color: black;padding: 10px;" data-page="' + i + '">' + i + '</a> ';
              } else {
                pagination += '<a href="javascript:void(0)" class="page-link3" data-page="' + i + '">' + i + '</a> ';
              }
            }
            $('.paginationtts').html(pagination);
          },
          complete: function () {
            // Hide the spinner after the AJAX request completes
            $('.spinner').hide();
          }
        });
      }

      // for edit tools tracker
      $('body').delegate('.itt-edit-toolsdetails', 'click', function (e) {
        $('.itt-save').removeAttr("operation");
        $('.itt-save').attr("operation", "yes");
        var ittid = $(this).attr('id');
        var unique = $(this).closest('tr').find('td:eq(1)').html();
        var itemdescription = $(this).closest('tr').find('td:eq(2)').html();
        var machineslno = $(this).closest('tr').find('td:eq(3)').html();
        var make = $(this).closest('tr').find('td:eq(4)').html();
        var type = $(this).closest('tr').find('td:eq(5)').html();
        var purdetails = $(this).closest('tr').find('td:eq(6)').html();
        var warranty = $(this).closest('tr').find('td:eq(7)').html();
        var tinnumber = $(this).closest('tr').find('td:eq(8)').html();
        var tindate = $(this).closest('tr').find('td:eq(9)').html();
        var remarks = $(this).closest('tr').find('td:eq(10)').html();
        $('#itt-id').remove();
        $('.itt-unique-id').before('<input type="hidden" id="itt-id" value="' + ittid + '">');
        $('.itt-unique-id').val(unique);
        $('.itt-itemdescription').val(itemdescription);
        $('.itt-machinesrno').val(machineslno);
        $('.itt-make').val(make);
        $('.itt-type').val(type);
        $('.itt-warranty').val(warranty);
        $('.itt-purdetails').val(purdetails);
        $('.itt-tinnumber').val(tinnumber);
        $('.itt-tindate').val(tindate);
        $('.itt-remarks').val(remarks);
      });

      // create tools tracker 
      $('body').delegate('.itt-create', 'click', function (e) {
        $('.itt-save').removeAttr("operation");
        $('.itt-save').attr("operation", "no");
        resetFormFields();
      });

      // save dispatch
      $('body').delegate('.itt-save', 'click', function (e) {
        e.preventDefault();
        var itt_id = 0;
        var unique = $('.itt-unique-id').val();
        var itemdescription = $('.itt-itemdescription').val();
        var machineslno = $('.itt-machinesrno').val();
        var make = $('.itt-make').val();
        var type = $('.itt-type').val();
        var warranty = $('.itt-warranty').val();
        var purdetails = $('.itt-purdetails').val();
        var tinnumber = $('.itt-tinnumber').val();
        var tindate = $('.itt-tindate').val();
        var remarks = $('.itt-remarks').val();
        if (unique != '' && itemdescription != '') {
          var operation = $(this).attr('operation'); // Get the operation type
          if (operation == "yes") {
            itt_id = $('#itt-id').val();
          }
          var data = {
            save_tool_tracker: 1,
            unique: unique,
            itemdescription: itemdescription,
            machineslno: machineslno,
            make: make,
            type: type,
            warranty: warranty,
            purdetails: purdetails,
            tinnumber: tinnumber,
            tindate: tindate,
            remarks: remarks,
            operation: operation,
            itt_id: itt_id
          };
          $.ajax({
            url: "kattegat/ragnar_order.php",
            method: "POST",
            data: data,
            success: function (response) {
              var obj_response = response.trim();
              if (obj_response == "yes") {
                alert("Record updated successfully!!!");
                if (operation == "no") {
                  resetFormFields();
                }
                call_tools_tracker();
              } else if (obj_response == "duplicate") {
                alert("This tool is already in records.");
              } else if (obj_response == "reload") {
                window.location.reload();
              } else if (obj_response == "empty") {
                alert("Please fill complete details.");
              } else if (obj_response == "no") {
                alert("Something went wrong. Record not updated");
              }
            }
          });
        } else {
          alert("Please check Type of PPE.");
        }
      });

      // for edit tools tracker
      $('body').delegate('.itt-toolstracking', 'click', function (e) {
        $('#ittt-id').remove();
        var ittid = $(this).attr('id');
        $('.ittt-unique-idtracking').before('<input type="hidden" id="ittt-id" value="' + ittid + '">');
        var unique = $(this).closest('tr').find('td:eq(1)').html();
        var itemdescription = $(this).closest('tr').find('td:eq(2)').html();
        $('.ittt-unique-idtracking').val(unique);
        $('.ittt-itemdescriptiontracking').val(itemdescription);
      });

      // call tools tracker
      $('body').delegate('.itt-toolstrackingshow', 'click', function (e) {
        var itt_id = $(this).attr('id');
        $.ajax({
          url: "kattegat/ragnar_order.php",
          method: "POST",
          data: {
            call_tool_trackertrack: 1,
            itt_id: itt_id
          },
          dataType: "JSON",
          success: function (response) {
            var data = '';
            // Check if response is valid
            if (response.length > 0) {
              // Loop through the JSON data
              var slno = 0;
              for (var i = 0; i < response.length; i++) {
                slno++;
                data += '<tr><td>' + response[i].unique_id + '</td><td>' + response[i].itemdescription + '</td><td>' + response[i].issuedby + '</td><td>' + response[i].issueddate + '</td><td>' + response[i].receivedby + '</td><td>' + response[i].location + '</td><td>' + response[i].handoverto + '</td><td>' + response[i].name + '</td></tr>';
              }
            } else {
              data = "<td>No data found.</td>";
            }
            $('.itt-showtrackingdetails').html(data);
          }
        });
      });

      // save dispatch
      $('body').delegate('.ittt-save', 'click', function (e) {
        e.preventDefault();
        var itt_id = $('#ittt-id').val();
        var issuedby = $('.ittt-issuedby').val();
        var location = $('.ittt-location').val();
        var date = $('.ittt-date').val();
        var receivedby = $('.ittt-receivedby').val();
        var handoverto = $('.ittt-handoverto').val();
        if (issuedby != '' && location != '') {
          var data = {
            save_tool_trackertrack: 1,
            issuedby: issuedby,
            location: location,
            date: date,
            receivedby: receivedby,
            handoverto: handoverto,
            itt_id: itt_id
          };
          $.ajax({
            url: "kattegat/ragnar_order.php",
            method: "POST",
            data: data,
            success: function (response) {
              var obj_response = response.trim();
              if (obj_response == "yes") {
                alert("Record updated successfully!!!");
              } else if (obj_response == "duplicate") {
                alert("This tool is already in records.");
              } else if (obj_response == "reload") {
                window.location.reload();
              } else if (obj_response == "notfound") {
                alert("Siteuser not found.");
              } else if (obj_response == "empty") {
                alert("Please fill complete details.");
              } else if (obj_response == "no") {
                alert("Something went wrong. Record not updated");
              }
            }
          });
        } else {
          alert("Please enter user phone number and location.");
        }
      });

    });
  </script>
  <script>
    $(document).ready(function () {
      stc_call_advanceorderlist();
      function stc_call_advanceorderlist(){
        $.ajax({
          url: "kattegat/ragnar_order.php",
          method: "post",
          data: { stc_call_advanceorderlist: 1 },
          dataType: 'JSON',
          success: function (response) {
            var data = '';
            var slno = 1;
            for (var i = 0; i < response.length; i++) {
              var color="#ff6d6d";
              if(response[i].remaining_qty>0){
                color="#78f37c";
              }
              data += '<tr><td>' + slno + '</td><td>' + response[i].stc_product_name + '</td><td class="text-right" style="background-color:' + color + '">' + response[i].remaining_qty + '</td><td class="text-center">' + response[i].stc_product_unit + '</td></tr>';
              slno++;
            }
            $('.stc-call-advanceorderlist').html(data);
          }
        });
      }

      // Function to filter table rows
      function filterTable(searchQuery) {
          $('.stc-call-advanceorderlist tr').each(function () {
              const productName = $(this).find('td:nth-child(2)').text().toLowerCase(); // Get product name from the second column
              if (productName.includes(searchQuery.toLowerCase())) {
                  $(this).show(); // Show the row if it matches the search query
              } else {
                  $(this).hide(); // Hide the row if it doesn't match
              }
          });
      }

      // Trigger search on input change
      $('#searchInputaom').on('input', function () {
          const searchQuery = $(this).val(); // Get the search query
          filterTable(searchQuery); // Filter the table
      });


      let currentPage = 1;
      const perPage = 10; // Items per page

      function stc_call_b2corders(searchQuery = '', page = 1) {
          $.ajax({
              url: "kattegat/ragnar_order.php",
              method: "post",
              data: {
                  stc_call_B2COrders: 1,
                  search: searchQuery,
                  page: page,
                  perPage: perPage
              },
              dataType: 'JSON',
              success: function (response) {
                  var data = '';
                  var slno = (page - 1) * perPage + 1; // Calculate serial number
                  for (var i = 0; i < response.data.length; i++) {
                      var total = response.data[i].total;
                      var status = response.data[i].status == 1 ? 'Pending' : response.data[i].status == 2 ? 'Confirmed' : response.data[i].status == 3 ? 'Dispatched' : 'Delivered';
                      var action_btn = '';
                      if (response.data[i].status == 1) {
                          action_btn = '<a href="javascript:void(0)" class="orderaction-handler" id="' + response.data[i].id + '" status="2" title="Confirm order." style="font-size: 30px;"><i class="fa fa-thumbs-up"></i></a>';
                      }
                      if (response.data[i].status == 2) {
                          action_btn = '<a href="javascript:void(0)" class="orderaction-handler" id="' + response.data[i].id + '" status="3" title="Dispatch order." style="font-size: 30px;"><i class="fa fa-truck"></i></a>';
                      }
                      if (response.data[i].status == 4) {
                          action_btn = 'Please buy item.';
                      }
                      data += '<tr><td>' + slno + '</td><td>' + response.data[i].first_name + '</td><td>' + response.data[i].last_name + '</td><td class="text-center">' + response.data[i].email + '</td><td class="text-center">' + response.data[i].phone_number + '</td><td>' + response.data[i].street_address + '</td><td class="text-center">' + response.data[i].stc_product_id + '</td><td>' + response.data[i].stc_product_name + '</td><td class="text-right">' + response.data[i].quantity + '</td><td class="text-right">' + response.data[i].rate + '</td><td class="text-right">' + total + '</td><td class="text-center">' + response.data[i].created_at + '</td><td class="text-center">' + status + '</td><td class="text-center">' + action_btn + '</td></tr>';
                      slno++;
                  }
                  $('.stc-call-b2corders').html(data);

                  // Update pagination controls
                  const totalPages = Math.ceil(response.total / perPage);
                  $('#pageInfo').text(`Page ${response.page} of ${totalPages}`);
                  $('#prevPage').prop('disabled', response.page === 1);
                  $('#nextPage').prop('disabled', response.page === totalPages);
              }
          });
      }

      // Search button click event
      $('#searchButton').on('click', function () {
          const searchQuery = $('#searchInput').val();
          currentPage = 1; // Reset to first page on new search
          stc_call_b2corders(searchQuery, currentPage);
      });

      // Pagination button click events
      $('#prevPage').on('click', function () {
          if (currentPage > 1) {
              currentPage--;
              stc_call_b2corders($('#searchInput').val(), currentPage);
          }
      });

      $('#nextPage').on('click', function () {
          const searchQuery = $('#searchInput').val();
          currentPage++;
          stc_call_b2corders(searchQuery, currentPage);
      });

      // Initial load
      stc_call_b2corders();
      $('body').delegate('.orderaction-handler', 'click', function () {
        var id = $(this).attr('id');
        var status = $(this).attr('status');
        if(confirm("Are you sure?")){
          $.ajax({
            url: "kattegat/ragnar_order.php",
            method: "post",
            data: {stc_action_order:1, id: id, status:status },
            dataType: 'JSON',
            success: function (response) {
              if (response == "success") {
                if(status=="2"){alert("Order Confirmed");}
                if(status=="3"){alert("Order Dispatched");}
                stc_call_b2corders();
              } else if (response == "no") {
                alert("Something went wrong. Order not confirmed");
              }
            }
          });
        }
      });
    });
  </script>
  <script>
$(document).ready(function () {
  // GLD Requisitions Tab Logic
  function loadGLDRequisitions(page = 1, search = '') {
    $('.gld-requisition-table-body').html('<div class="row" style="border-bottom: 1px solid #dee2e6; margin: 0;"><div class="col-md-12 text-center" style="padding: 20px;">Loading...</div></div>');
    $.ajax({
      url: 'kattegat/ragnar_order.php',
      method: 'POST',
      data: {
        stc_call_gld_requisitions: 1,
        page: page,
        search: search,
        limit: 10
      },
      dataType: 'json',
      success: function (response) {
        var rows = '';
        if (response.records && response.records.length > 0) {
          response.records.forEach(function (item) {
            var actionButton = '';
            
            // Add remarks button for all items
            var remarksButton = '<a href="#" class="btn btn-info btn-sm gld-remarks-btn" data-toggle="modal" data-target="#gld-remarks-modal" data-id="' + item.id + '" title="Add Remarks"><i class="fa fa-comment"></i> Remarks</a>';
            // Create status badge with color
            var statusBadge = '';
            switch(parseInt(item.status)) {
              case 1:
                statusBadge = '<span class="badge badge-warning" style="background-color: #ffc107; color: #000; padding: 5px 10px; border-radius: 15px; font-size: 12px;"><i class="fa fa-clock-o"></i> ' + item.status_text + '</span>';
                break;
              case 2:
                statusBadge = '<span class="badge badge-info" style="background-color: #17a2b8; color: #fff; padding: 5px 10px; border-radius: 15px; font-size: 12px;"><i class="fa fa-check-circle"></i> ' + item.status_text + '</span>';
                break;
              case 3:
                statusBadge = '<span class="badge badge-primary" style="background-color: #007bff; color: #fff; padding: 5px 10px; border-radius: 15px; font-size: 12px;"><i class="fa fa-shipping-fast"></i> ' + item.status_text + '</span>';
                break;
              case 4:
                statusBadge = '<span class="badge badge-success" style="background-color: #28a745; color: #fff; padding: 5px 10px; border-radius: 15px; font-size: 12px;"><i class="fa fa-check-double"></i> ' + item.status_text + '</span>';
                break;
              default:
                statusBadge = '<span class="badge badge-secondary" style="background-color: #6c757d; color: #fff; padding: 5px 10px; border-radius: 15px; font-size: 12px;"><i class="fa fa-question-circle"></i> ' + item.status_text + '</span>';
            }
            var buyStatusBadge = '';
            switch(parseInt(item.buy_status)) {
              case 1:
                buyStatusBadge = '<span class="badge badge-warning" style="background-color: #ffc107; color: #000; padding: 5px 10px; border-radius: 15px; font-size: 12px;"><i class="fa fa-clock-o"></i> ' + item.buyStatus_text + '</span>';
                break;
              case 2:
                buyStatusBadge = '<span class="badge badge-info" style="background-color: #17a2b8; color: #fff; padding: 5px 10px; border-radius: 15px; font-size: 12px;"><i class="fa fa-check-circle"></i> ' + item.buyStatus_text + '</span>';
                break;
              case 3:
                buyStatusBadge = '<span class="badge badge-primary" style="background-color: #007bff; color: #fff; padding: 5px 10px; border-radius: 15px; font-size: 12px;"><i class="fa fa-shipping-fast"></i> ' + item.buyStatus_text + '</span>';
                break;
              case 4:
                buyStatusBadge = '<span class="badge badge-success" style="background-color: #28a745; color: #fff; padding: 5px 10px; border-radius: 15px; font-size: 12px;"><i class="fa fa-check-double"></i> ' + item.buyStatus_text + '</span>';
                break;
              default:
              buyStatusBadge = '<span class="badge badge-secondary" style="background-color: #6c757d; color: #fff; padding: 5px 10px; border-radius: 15px; font-size: 12px;"><i class="fa fa-question-circle"></i> ' + item.buyStatus_text + '</span>';
            }
            if (item.buy_status == 1) {
              actionButton = `
              <a href="#" class="btn btn-success btn-sm gld-purchase-btn" data-id="${item.id}" title="Purchased"><i class="fa fa-check"></i> Purchased?</a>
              `;
            } else if (item.buy_status == 2) {
              actionButton = `
              <a href="purchase-product-adhoc.php?page=purchase&subpage=purchaseorderadhoc&brequist_id=${item.id}&product_id=${item.product_id}" class="btn btn-success btn-sm" title="Recieved"><i class="fa fa-check"></i> Recieved?</a>
              `;

            } else{
              remarksButton='';
              if (item.status == 1) {
                actionButton = '<a href="#" class="btn btn-success btn-sm gld-approve-btn" data-id="' + item.id + '" title="Approve"><i class="fa fa-check"></i> Approve</a>';
              } else if (item.status == 2) {
                actionButton = '<a href="#" class="btn btn-primary btn-sm gld-dispatch-btn" data-id="' + item.id + '" title="Dispatch"><i class="fa fa-shipping-fast"></i> Dispatch</a>';
              } else {
                actionButton = '<span class="text-muted">No Action</span>';
              }
            }
            
            rows += '<div class="row" style="border-bottom: 1px solid #dee2e6; margin: 0;">' +
              '<div class="col-md-1 text-center" style="padding: 10px; border-right: 1px solid #dee2e6;">' + item.stc_trading_user_location + '</div>' +
              '<div class="col-md-1 text-center" style="padding: 10px; border-right: 1px solid #dee2e6;">' + item.product_id + '</div>' +
              '<div class="col-md-1 text-center" style="padding: 10px; border-right: 1px solid #dee2e6; word-wrap: break-word; white-space: normal; max-height: 100px; overflow-y: auto;">' + item.name + '</div>' +
              '<div class="col-md-1 text-right" style="padding: 10px; border-right: 1px solid #dee2e6;">' + item.quantity + '</div>' +
              '<div class="col-md-1 text-center" style="padding: 10px; border-right: 1px solid #dee2e6;">' + item.unit + '</div>' +
              '<div class="col-md-2 text-left" style="padding: 10px; border-right: 1px solid #dee2e6; word-wrap: break-word; white-space: normal; max-height: 100px; overflow-y: auto;">' + (item.remarks || '') + '</div>' +
              '<div class="col-md-1 text-center" style="padding: 10px; border-right: 1px solid #dee2e6;">' + statusBadge + '</div>' +
              '<div class="col-md-1 text-center" style="padding: 10px; border-right: 1px solid #dee2e6;">' + buyStatusBadge + '</div>' +
              '<div class="col-md-2 text-left" style="padding: 10px; border-right: 1px solid #dee2e6; word-wrap: break-word; white-space: normal; max-height: 100px; overflow-y: auto;">' + (item.buy_remarks || '') + '</div>' +
              '<div class="col-md-1 text-center" style="padding: 10px;">' + actionButton + '<br>' + remarksButton + '</div>' +
              '</div>';
          });
        } else {
          rows = '<div class="row" style="border-bottom: 1px solid #dee2e6; margin: 0;"><div class="col-md-12 text-center" style="padding: 20px;">No requisitions found.</div></div>';
        }
        $('.gld-requisition-table-body').html(rows);
        // Pagination
        var total = response.total || 0;
        var perPage = 25;
        var totalPages = Math.ceil(total / perPage);
        var pagination = '';
        if (totalPages > 0) {
          for (var i = 1; i <= totalPages; i++) {
            if (i == page) {
              pagination += '<span class="btn btn-sm btn-success mx-1">' + i + '</span>';
            } else {
              pagination += '<a href="#" class="btn btn-sm btn-outline-primary mx-1 gld-requisition-page-link" data-page="' + i + '">' + i + '</a>';
            }
          }
        }
        $('.gld-requisition-pagination').html(pagination);
      },
      error: function () {
        $('.gld-requisition-table-body').html('<tr><td colspan="7">Error loading data.</td></tr>');
      }
    });
  }
  // Load on tab show
  $('a#tab-10').on('shown.bs.tab', function () {
    loadGLDRequisitions(1, '');
  });
  // Also load if page is loaded with this tab active
  if ($('a#tab-10').hasClass('active')) {
    loadGLDRequisitions(1, '');
  }
  // Search
  $('#gld-requisition-search-btn').on('click', function () {
    var search = $('#gld-requisition-search').val();
    loadGLDRequisitions(1, search);
  });
  $('#gld-requisition-search').on('keyup', function (e) {
    if (e.keyCode === 13) {
      var search = $(this).val();
      loadGLDRequisitions(1, search);
    }
  });
  // Pagination
  $('body').on('click', '.gld-requisition-page-link', function (e) {
    e.preventDefault();
    var page = $(this).data('page');
    var search = $('#gld-requisition-search').val();
    loadGLDRequisitions(page, search);
  });
  
  // Approve button click
  $('body').on('click', '.gld-approve-btn', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    if (confirm('Are you sure you want to approve this requisition?')) {
      updateGLDRequisitionStatus(id, 2);
    }
  });
  
  // Dispatch button click
  $('body').on('click', '.gld-dispatch-btn', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    if (confirm('Are you sure you want to dispatch this requisition?')) {
      updateGLDRequisitionStatus(id, 3);
      var currentPage = $('.gld-requisition-page-link.btn-success').text() || 1;
      var search = $('#gld-requisition-search').val();
      loadGLDRequisitions(currentPage, search);
    }
  });
  
  // Remarks button click
  $('body').on('click', '.gld-remarks-btn', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    $('#gld-remarks-id').val(id);
  });
  
  // Function to update status
  function updateGLDRequisitionStatus(id, status) {
    $.ajax({
      url: 'kattegat/ragnar_order.php',
      method: 'POST',
      data: {
        stc_update_gld_requisition_status: 1,
        id: id,
        status: status
      },
      dataType: 'json',
      success: function (response) {
        if (response.success) {
          alert(response.message);
          // Reload the current page to show updated data
          var currentPage = $('.gld-requisition-page-link.btn-success').text() || 1;
          var search = $('#gld-requisition-search').val();
          loadGLDRequisitions(currentPage, search);
        } else {
          alert(response.message);
        }
      },
      error: function () {
        alert('Error updating status. Please try again.');
      }
    });
  }
  
  // Function to save remarks
  function saveGLDRequisitionRemarks(id, remarks) {
    $.ajax({
      url: 'kattegat/ragnar_order.php',
      method: 'POST',
      data: {
        stc_update_gld_requisition_remarks: 1,
        id: id,
        remarks: remarks
      },
      dataType: 'json',
      success: function (response) {
        if (response.success) {
          alert(response.message);
          // Reload the current page to show updated data
          var currentPage = $('.gld-requisition-page-link.btn-success').text() || 1;
          var search = $('#gld-requisition-search').val();
          $('#gld-remarks-text').val('');
          loadGLDRequisitions(currentPage, search);
        } else {
          alert(response.message);
        }
      },
      error: function () {
        alert('Error saving remarks. Please try again.');
      }
    });
  }
  
  // Save remarks button click
  $('#gld-save-remarks-btn').on('click', function (e) {
    e.preventDefault();
    var id = $('#gld-remarks-id').val();
    var remarks = $('#gld-remarks-text').val();
    if (remarks.trim() === '') {
      alert('Please enter some remarks.');
      return;
    }
    saveGLDRequisitionRemarks(id, remarks);
  });

  
  $('body').on('click', '.gld-purchase-btn', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    if (confirm('Are you sure you want to change status for this requisition to Purchased?')) {
      updateGLDRequisitionBStatus(id, 2);
      var currentPage = $('.gld-requisition-page-link.btn-success').text() || 1;
      var search = $('#gld-requisition-search').val();
      loadGLDRequisitions(currentPage, search);
    }
  });
  
  // Function to update status
  function updateGLDRequisitionBStatus(id, status) {
    $.ajax({
      url: 'kattegat/ragnar_order.php',
      method: 'POST',
      data: {
        stc_update_gld_requisition_bstatus: 1,
        id: id,
        status: status
      },
      dataType: 'json',
      success: function (response) {
        if (response.success=="true") {
          alert(response.message);
          // Reload the current page to show updated data
          var currentPage = $('.gld-requisition-page-link.btn-success').text() || 1;
          var search = $('#gld-requisition-search').val();
          loadGLDRequisitions(currentPage, search);
        } else {
          alert(response.message);
        }
      },
      error: function () {
        alert('Error updating status. Please try again.');
      }
    });
  }
});
</script>
</body>

<!-- GLD Requisition Remarks Modal -->
<div class="modal fade" id="gld-remarks-modal" tabindex="-1" role="dialog" aria-labelledby="gldRemarksModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="gldRemarksModalLabel">Add/Edit Remarks</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="gld-remarks-form">
          <input type="hidden" id="gld-remarks-id" name="requisition_id">
          <div class="form-group">
            <p for="gld-remarks-text" style="display: block; font-weight: bold; margin-bottom: 8px;">Remarks:</p>
            <textarea class="form-control" id="gld-remarks-text" name="remarks" rows="5" placeholder="Enter your remarks here..."></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="gld-save-remarks-btn">Save Remarks</button>
      </div>
    </div>
  </div>
</div>

</html>
<div class="modal fade bd-example-modal-xl stc-call-for-select-merchant-res" tabindex="-1" role="dialog"
  aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Requisition Items Merchandise</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
            <div class="row">
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="card-border mb-3 card card-body border-success stc-req-item-show" style="overflow-x:auto;">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- product selection modal -->
<div class="modal fade bd-example-modal-xl stc-agent-req-get-mer-product-show" tabindex="-1" role="dialog"
  aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Requisition Items Selection</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div><input type="hidden" class="stc-req-id-store">
      <div class="modal-body">
        <div class="row">
          <div class="col-12">
            <table class="table table-hover ">
              <thead>
                <tr>
                  <th scope="col">By Category</th>
                  <th scope="col">By Name</th>
                  <th scope="col">By Sub Category</th>
                  <th scope="col">Search</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <div class="card-border mb-3 card card-body border-success">
                      <select class="custom-select tm-select-accounts call_cat stcprosearchsame" id="filterbycat"
                        name="stcpdcategory">
                      </select>
                    </div>
                  </td>
                  <td>
                    <div class="card-border mb-3 card card-body border-success">
                      <input id="searchbystcname" name="stcsearchpdname" type="text" placeholder="Product Name"
                        class="form-control validate stcprosearchsame" />
                      <input type="hidden" name="search_alo_in">
                    </div>
                  </td>
                  <td>
                    <div class="card-border mb-3 card card-body border-success">
                      <select class="custom-select tm-select-accounts call_sub_cat stcprosearchsame" id="filterbysubcat"
                        name="stcpdsubcategory">
                      </select>
                    </div>
                  </td>
                  <td>
                    <div class="card-border mb-3 card card-body border-success">
                      <button type="submit" class="form-control btn btn-primary stcprosearchhit">Search <i
                          class="fa fa-search"></i></button>
                    </div>
                  </td>
                  <td>
                    <a style="
                        font-size: 20px;
                        background: yellow;
                        border-radius: 50%;
                        padding: 5px;
                        margin: 0;
                      " href="#" class="upward"><i class="fas fa-arrow-up"></i>
                    </a>
                    <a style="
                        font-size: 20px;
                        background: yellow;
                        border-radius: 50%;
                        padding: 5px;
                        margin: 0;
                        display: none;
                      " href="#" class="downward"><i class="fas fa-arrow-down"></i>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-12">
            <div class="row stc-req-product-show">
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- dispatch direct modal -->
<div class="modal fade bd-example-modal-lg res-product-Modal-cash-close" tabindex="-1" role="dialog"
  aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Dispatch Product</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
            <div class="row">
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <input type="hidden" id="stc-req-list-item-id-rep2" value="0" ;>
                <input type="hidden" id="stc-req-list-id-rep2" value="0" ;>
              </div>
            </div>
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="card-border mb-3 card card-body border-success">
                  <h5 for="poadhocitem">
                    Item
                  </h5>
                  <input type="text" id="searchInput" class="form-control" placeholder="Search item">
                  <select class="form-control poadhocitem" id="poadhocitem">
                    <?php 
                      include_once("../MCU/db.php");
                      $sqlqry=mysqli_query($con, "
                        SELECT `stc_purchase_product_adhoc_id`, `stc_purchase_product_adhoc_itemdesc`, `stc_purchase_product_adhoc_qty`, `stc_purchase_product_adhoc_unit`, `stc_rack_name` 
                        FROM `stc_purchase_product_adhoc`
                        LEFT JOIN `stc_rack` ON `stc_purchase_product_adhoc_rackid`=`stc_rack_id`
                        WHERE `stc_purchase_product_adhoc_status`=1
                      ");
                      if(mysqli_num_rows($sqlqry)>0){
                        echo '<option value="NA">Select</option>';
                        foreach($sqlqry as $sqlrow){
                          $poadid=$sqlrow['stc_purchase_product_adhoc_id'];
                          $checsql=mysqli_query($con, "
                            SELECT SUM(`stc_cust_super_requisition_list_items_rec_recqty`) as recqty
                            FROM `stc_cust_super_requisition_list_items_rec`
                            WHERE `stc_cust_super_requisition_list_items_rec_list_poaid`='".$poadid."'
                          ");
                          $result=mysqli_num_rows($checsql)>0 ? mysqli_fetch_assoc($checsql) : 0;
                          $rec_qty=$result!=0 ? $result['recqty'] : 0;
                          
                          $deliveredgld=0;
                          $sql_qry=mysqli_query($con, "
                            SELECT `qty` FROM `gld_challan` WHERE `adhoc_id`='".$sqlrow['stc_purchase_product_adhoc_id']."'
                          ");
                          if(mysqli_num_rows($sql_qry)>0){
                            foreach($sql_qry as $sql_row){
                              $deliveredgld+=$sql_row['qty'];
                            }
                          }
                          $balanced_qty=$sqlrow['stc_purchase_product_adhoc_qty'] - ($rec_qty + $deliveredgld);
                          if($balanced_qty>0){
                            echo '<option value="'.$sqlrow['stc_purchase_product_adhoc_id'].'" qty="'.$balanced_qty.'" rack="'.$sqlrow['stc_rack_name'].'" unit="'.$sqlrow['stc_purchase_product_adhoc_unit'].'">'.$sqlrow['stc_purchase_product_adhoc_itemdesc'].'</option>';
                          }
                        }
                      }else{
                        echo '<option value="NA">No record found.</option>';
                      }
                    ?>
                  </select>
                </div>
              </div>
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="card-border mb-3 card card-body border-success">
                  <h5 for="poadhoctools">
                    Tools
                  </h5>
                  <input type="text" id="searchInput2" class="form-control" placeholder="Search item">
                  <select class="form-control poadhoctools" id="poadhoctools">
                    <?php 
                      $sqlqry=mysqli_query($con, "
                        SELECT `id`, `unique_id`, `itemdescription` FROM `stc_tooldetails`
                      ");
                      if(mysqli_num_rows($sqlqry)>0){
                        echo '<option value="NA">Select</option>';
                        foreach($sqlqry as $sqlrow){
                          echo '<option value="'.$sqlrow['id'].'">'.$sqlrow['unique_id'].' : '.$sqlrow['itemdescription'].'</option>';
                        }
                      }else{
                        echo '<option value="NA">No record found.</option>';
                      }
                    ?>
                  </select>
                </div>
              </div>

              <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                <div class="card-border mb-3 card card-body border-success">
                  <h5>
                    Balanced Quantity
                  </h5>
                  <input id="stcbalancedqty" name="stcbalancedqty" type="text" placeholder="Balanced Quantity"
                    class="form-control validate stcbalancedqty" disabled />
                </div>
              </div>
              <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                <div class="card-border mb-3 card card-body border-success">
                  <h5>
                    Unit
                  </h5>
                  <input id="stcbalancedqtyunit" name="stcbalancedqtyunit" type="text" placeholder="Unit"
                    class="form-control validate stcbalancedqtyunit" disabled />
                </div>
              </div>
              <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">
                <div class="card-border mb-3 card card-body border-success">
                  <h5>
                    Rack
                  </h5>
                  <input id="stcbalancedqtyrack" name="stcbalancedqtyrack" type="text" placeholder="Rack"
                    class="form-control validate stcbalancedqtyrack" disabled />
                </div>
              </div>
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="card-border mb-3 card card-body border-success">
                  <h5>
                    Dispatched Quantity
                  </h5>
                  <input id="stcdispatchedqty" name="stcdispatchedqty" type="text"
                    placeholder="Enter Dispatched Quantity" class="form-control validate stcdispatchedqty" />
                </div>
              </div>
              <div class="col-12">
                <div class="card-border mb-3 card card-body border-success">
                  <button type="submit" class="form-control btn btn-success stcdispatchedbtn">Save</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- ppe track -->
<div class="modal fade bd-ppetracker-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">PPE Tracker</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xl-12">
            <div class="main-card mb-3 card">
              <div class="card-body">
                <div class="row formcontrol">
                  <div class="col-md-6">
                    <h5>Employee Name</h5><br>
                    <div class="card mb-3 widget-content">
                      <input type="text" class="form-control it-emp-name" placeholder="Enter employee name" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <h5>Type of PPE</h5><br>
                    <div class="card mb-3 widget-content">
                      <div class="checkbox-group">
                        <label><input type="checkbox" class="it-ppe-type" unit="Pair" validity="12"
                            value="Safety Shoes"> Safety Shoes </label>
                        <label><input type="checkbox" class="it-ppe-type" unit="Nos" validity="12"
                            value="Safety Jacket"> Safety Jacket </label>
                        <label><input type="checkbox" class="it-ppe-type" unit="Nos" validity="12" value="Safety Belt">
                          Safety Belt </label>
                        <label><input type="checkbox" class="it-ppe-type" unit="Nos" validity="60"
                            value="Safety Helmet"> Safety Helmet </label>
                        <label><input type="checkbox" class="it-ppe-type" unit="Nos" validity="3" value="Hand Gloves">
                          Hand Gloves </label>
                        <label><input type="checkbox" class="it-ppe-type" unit="Nos" validity="6" value="Leg Guard"> Leg
                          Guard </label>
                        <label><input type="checkbox" class="it-ppe-type" unit="Nos" validity="6"
                            value="Safety Goggles"> Safety Goggles </label>
                        <label><input type="checkbox" class="it-ppe-type" unit="Nos" validity="1" value="Ear Plug"> Ear
                          Plug </label>
                        <label><input type="checkbox" class="it-ppe-type" unit="Nos" validity="1" value="Nose Mask">
                          Nose Mask </label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6" style="display:none;">
                    <h5>Quantity</h5><br>
                    <div class="card mb-3 widget-content">
                      <input type="number" class="form-control it-qty" placeholder="Enter quantity" value="1">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <h5>Date of Issue</h5><br>
                    <div class="card mb-3 widget-content">
                      <input type="date" class="form-control it-issue-date" value="<?php echo date('Y-m-d'); ?>"
                        required>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <h5>Remarks</h5><br>
                    <div class="card mb-3 widget-content">
                      <textarea class="form-control it-remarks" placeholder="Enter remarks"></textarea>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="card mb-3 widget-content">
                      <button class="form-control btn btn-success it-save">Save</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- ppe track -->
<div class="modal fade bd-ppetrackersummary-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">PPE Tracker</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12 mb-4">
            <div class="row">
              <div class="col-md-3">
                <div class="card text-center p-3">
                  <img src="../stc_agent47/assets/images/safety_img/safety_legguard.png"
                    style="position: relative; left: 35%; width: 21%; height: 47px;">
                  <h5>Leg Guards</h5>
                  <span class="legguardsummary display-6">0</span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card text-center p-3">
                  <img src="../stc_agent47/assets/images/safety_img/safety_safetygoggles.png"
                    style="position: relative; left: 35%; width: 21%; height: 47px;">
                  <h5>Safety Goggles</h5>
                  <span class="safetygogglessummary display-6">0</span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card text-center p-3">
                  <img src="../stc_agent47/assets/images/safety_img/safety_hvjacket.png"
                    style="position: relative; left: 35%; width: 21%; height: 47px;">
                  <h5>Safety Jackets</h5>
                  <span class="safetyjacketsummary display-6">0</span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card text-center p-3">
                  <img src="../stc_agent47/assets/images/safety_img/safety_shoe.png"
                    style="position: relative; left: 35%; width: 21%; height: 47px;">
                  <h5>Safety Shoes</h5>
                  <span class="safetyshoessummary display-6">0</span>
                </div>
              </div>
            </div>
            <div class="row mt-3">
              <div class="col-md-3">
                <div class="card text-center p-3">
                  <img src="../stc_agent47/assets/images/safety_img/safety_helmet.png"
                    style="position: relative; left: 35%; width: 21%; height: 47px;">
                  <h5>Safety Helmets</h5>
                  <span class="safetyhelmetsummary display-6">0</span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card text-center p-3">
                  <img src="../stc_agent47/assets/images/safety_img/safety_leathergloves.png"
                    style="position: relative; left: 35%; width: 21%; height: 47px;">
                  <h5>Hand Gloves</h5>
                  <span class="handglovessummary display-6">0</span>
                </div>
              </div>
              <div class="col-md-3">
                <div class="card text-center p-3">
                  <img src="../stc_agent47/assets/images/safety_img/safety_earplug.png"
                    style="position: relative; left: 35%; width: 21%; height: 47px;">
                  <h5>Ear Plugs</h5>
                  <span class="earplugsummary display-6">0</span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-12 col-sm-12 col-xl-12">
            <div class="main-card mb-3 card">
              <div class="card-body">
                <div class="row formcontrol">
                  <div class="col-md-12">
                    <div class="card mb-3 widget-content">
                      <table class="table table-bordered table-hover">
                        <thead>
                          <tr>
                            <th class="text-center">Employee Name</th>
                            <th class="text-center">Item</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-center">Unit</th>
                            <th class="text-center">Issue Date</th>
                            <th class="text-center">Validity</th>
                            <th class="text-center">Remarks</th>
                          </tr>
                        </thead>
                        <tbody id="ppesummary">
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
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Tools details -->
<div class="modal fade bd-toolstracker-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Tools Track</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xl-12">
            <div class="main-card mb-3 card">
              <div class="card-body">
                <div class="row formcontrol">
                  <div class="col-md-4">
                    <h5>Unique Id</h5><br>
                    <div class="card mb-3 widget-content">
                      <input type="text" class="form-control itt-unique-id" placeholder="Enter unique id" value="GTT/"
                        required>
                    </div>
                  </div>
                  <div class="col-md-8">
                    <h5>Item Description</h5><br>
                    <div class="card mb-3 widget-content">
                      <textarea class="form-control itt-itemdescription"
                        placeholder="Enter Item Description"></textarea>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <h5>Machine SR No</h5><br>
                    <div class="card mb-3 widget-content">
                      <input type="text" class="form-control itt-machinesrno" placeholder="Enter Machine SR No"
                        required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <h5>Make</h5><br>
                    <div class="card mb-3 widget-content">
                      <input type="text" class="form-control itt-make" placeholder="Enter Make" required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <h5>Type</h5><br>
                    <div class="card mb-3 widget-content">
                      <input type="text" class="form-control itt-type" placeholder="Enter Type" required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <h5>Warranty</h5><br>
                    <div class="card mb-3 widget-content">
                      <input type="text" class="form-control itt-warranty" placeholder="Enter Warranty" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <h5>Purchase Details</h5><br>
                    <div class="card mb-3 widget-content">
                      <input type="text" class="form-control itt-purdetails" placeholder="Enter Purchase Details"
                        required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <h5>Tax Invoice Number</h5><br>
                    <div class="card mb-3 widget-content">
                      <input type="text" class="form-control itt-tinnumber" placeholder="Enter Invoice Number" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <h5>Tax Invoice Date</h5><br>
                    <div class="card mb-3 widget-content">
                      <input type="date" class="form-control itt-tindate" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <h5>Remarks</h5><br>
                    <div class="card mb-3 widget-content">
                      <textarea class="form-control itt-remarks" placeholder="Enter remarks"></textarea>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="card mb-3 widget-content">
                      <button class="form-control btn btn-success itt-save">Save</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Tools details tracking -->
<div class="modal fade bd-toolstrackertracker-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Tools Track</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xl-12">
            <div class="main-card mb-3 card">
              <div class="card-body">
                <div class="row formcontrol">
                  <div class="col-md-4">
                    <h5>Unique Id</h5><br>
                    <div class="card mb-3 widget-content">
                      <input type="text" class="form-control ittt-unique-idtracking" placeholder="Enter unique id"
                        disabled>
                    </div>
                  </div>
                  <div class="col-md-8">
                    <h5>Item Description</h5><br>
                    <div class="card mb-3 widget-content">
                      <textarea class="form-control ittt-itemdescriptiontracking" placeholder="Enter Item Description"
                        disabled></textarea>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <h5>Issued By</h5><br>
                    <div class="card mb-3 widget-content">
                      <input type="number" class="form-control ittt-issuedby" placeholder="Enter users phone number">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <h5>Location</h5><br>
                    <div class="card mb-3 widget-content">
                      <input type="text" class="form-control ittt-location" placeholder="Enter Location">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <h5>Date</h5><br>
                    <div class="card mb-3 widget-content">
                      <input type="date" class="form-control ittt-date" placeholder="Enter Date">
                    </div>
                  </div>
                  <div class="col-md-3">
                    <h5>Received By</h5><br>
                    <div class="card mb-3 widget-content">
                      <input type="text" class="form-control ittt-receivedby" placeholder="Enter Received By">
                    </div>
                  </div>
                  <div class="col-md-3" style="display:none;">
                    <h5>Handover To</h5><br>
                    <div class="card mb-3 widget-content">
                      <input type="text" class="form-control ittt-handoverto" placeholder="Enter Handover To">
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="card mb-3 widget-content">
                      <button class="form-control btn btn-success ittt-save">Save</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Tools details tracking show -->
<div class="modal fade bd-toolstrackertrackershow-modal-lg" tabindex="-1" role="dialog"
  aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Tools Track</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xl-12">
            <div class="main-card mb-3 card">
              <div class="card-body">
                <div class="row form control">
                  <div class="col-md-12">
                    <div class="card mb-3 widget-content">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th class="text-center">UNIQUE ID</th>
                            <th class="text-center">ITEM DESCRIPTION</th>
                            <th class="text-center">ISSUED BY</th>
                            <th class="text-center">DATE</th>
                            <th class="text-center">RECEIVED BY</th>
                            <th class="text-center">LOCATION</th>
                            <th class="text-center">HANDOVER TO</th>
                            <th class="text-center">CREATED BY</th>
                          </tr>
                        </thead>
                        <tbody class="itt-showtrackingdetails"></tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>