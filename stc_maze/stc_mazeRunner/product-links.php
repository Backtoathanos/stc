<?php
ini_set("session.gc_maxlifetime", 21600);
session_set_cookie_params(21600);
session_start();
if(empty(@$_SESSION['stc_electro_user_id'])){
    header('location:../index.html');
}


if($_SESSION['stc_electro_user_for']>=3 || $_SESSION['stc_electro_user_for']==9){}else{
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
    STC Electronics || Product Links
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
          <li class="nav-item active">
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
                        <li class="nav-item acive">
                          <a class="nav-link " href="#stc-product-links" data-toggle="tab">
                            <i class="material-icons">visibility</i> Product Links By Purchaser
                            <div class="ripple-container"></div>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="#stc-product-links-inventory" data-toggle="tab">
                            <i class="material-icons">visibility</i> Product Links By Inventory All
                            <div class="ripple-container"></div>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link active" href="#stc-product-links-inventory-dues" data-toggle="tab">
                            <i class="material-icons">visibility</i> Product Links By Inventory Dues
                            <div class="ripple-container"></div>
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="tab-pane " id="stc-product-links">
                      <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
                          <div class="tm-bg-primary-dark tm-block tm-block-h-auto" >
                            <div class="row">
                              <div class="col-12">
                                <h2 class="tm-block-title d-inline-block">Product Links By Purchaser</h2>
                              </div>
                            </div>
                            <div class="row stc-view-product-row">
                              <div class="col-xl-12 col-lg-12 col-md-12">
                                <form action="" class="stc-silent-challan-form">
                                    <table class="table table-hover ">
                                      <thead>
                                        <tr>
                                          <th scope="col">From/<br>To</th>
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
                                            <p><input type="date" value="<?php echo $effectiveDate;?>" class="form-control begdate"></p>
                                            <p><input type="date" value="<?php echo $newDate;?>" class="form-control enddate"></p>
                                          </td>
                                          <td>
                                            <a class="btn btn-success searchbycpdnamehit" id="challandatefilt">Search <i class="fa fa-search"></i></a>
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                </form>
                              </div>
                            </div>
                            <div class="row stc-view-sale-row" style="width: auto;overflow-x: auto; white-space: nowrap;">
                              <div class="col-xl-12 col-lg-12 col-md-12">
                                <form action="" class="stc-view-silent-product-links-row-fetch">
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
                    <div class="tab-pane" id="stc-product-links-inventory">
                      <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
                          <div class="tm-bg-primary-dark tm-block tm-block-h-auto" >
                            <div class="row">
                              <div class="col-12">
                                <h2 class="tm-block-title d-inline-block">Product Links By Inventory All</h2>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-12 stc-product-by-inventory">

                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane active" id="stc-product-links-inventory-dues">
                      <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
                          <div class="tm-bg-primary-dark tm-block tm-block-h-auto" >
                            <div class="row">
                              <div class="col-12">
                                <h2 class="tm-block-title d-inline-block">Product Links By Inventory Dues</h2>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-12">
                                <?php
                                  include_once("../../MCU/db.php");
                                  $check_loki=mysqli_query($con, "
                                    SELECT DISTINCT 
                                      `stc_product_id`, 
                                      `stc_product_name`, 
                                      `stc_cat_name`
                                    FROM 
                                      `stc_electronics_inventory`
                                    INNER JOIN 
                                      `stc_product` 
                                    ON 
                                      `stc_product_id`=`stc_electronics_inventory_item_id`
                                    INNER JOIN 
                                      `stc_category` 
                                    ON 
                                      `stc_cat_id`=`stc_product_cat_id`
                                    ORDER BY 
                                      `stc_product_name` 
                                    ASC
                                  ");
                                  $odin='
                                    <table class="table table-hover table-bordered table-responsive">
                                          <thead>
                                            <tr>
                                              <th class="text-center" scope="col">Item ID</th>
                                              <th class="text-center" scope="col">Item Description</th>
                                              <th class="text-center" scope="col">Item Category</th>
                                              <th class="text-center" scope="col">Total PO Qty</th>
                                              <th class="text-center" scope="col">Total GRN Qty</th> 
                                              <th class="text-center" scope="col">Inventory Qty</th>
                                              <th class="text-center" scope="col">Challan Qty</th>
                                              <th class="text-center" scope="col">Electronics Inventory Qty</th>
                                              <th class="text-center" scope="col">Electronics Challan Qty</th>
                                            </tr>
                                          </thead>
                                          <tbody>  
                                    ';
                                  $do_action=mysqli_num_rows($check_loki);
                                  if($do_action == 0){
                                    $odin .= "<tr><td colspan='10' align='center'>No Record Found!!!</td></tr>";
                                  }else{
                                    $counter=0;
                                    foreach ($check_loki as $row) {
                                      $counter++;

                                      $odinallpoqry=mysqli_query($con, "
                                        SELECT
                                          SUM(`stc_purchase_product_items_qty`) as total_po_qty
                                        FROM
                                          `stc_purchase_product_items`
                                        WHERE
                                          `stc_purchase_product_items_product_id` ='".$row['stc_product_id']."'
                                      ");

                                      $total_po_qty= 0;
                                      foreach($odinallpoqry as $odinallporow){
                                        $total_po_qty=$odinallporow['total_po_qty'];
                                      }

                                      $odinallgrnqry=mysqli_query($con, "
                                        SELECT
                                          SUM(`stc_product_grn_items_qty`) as allgrn_qty
                                        FROM
                                          `stc_product_grn_items`
                                        WHERE
                                          `stc_product_grn_items_product_id` ='".$row['stc_product_id']."'
                                      ");

                                      $allgrn_qty= 0;
                                      foreach($odinallgrnqry as $odinallgrnrow){
                                        $allgrn_qty=$odinallgrnrow['allgrn_qty'];
                                      }

                                      $odininvqry=mysqli_query($con, "
                                        SELECT
                                          SUM(`stc_item_inventory_pd_qty`) as inv_qty
                                        FROM
                                          `stc_item_inventory`
                                        WHERE
                                          `stc_item_inventory_pd_id`='".$row['stc_product_id']."'
                                      ");

                                      $inv_qty= 0;
                                      foreach($odininvqry as $odininvrow){
                                        $inv_qty=$odininvrow['inv_qty'];
                                      }

                                      $odinchallanqry=mysqli_query($con, "
                                        SELECT
                                          SUM(`stc_sale_product_items_product_qty`) as challan_qty
                                        FROM
                                          `stc_sale_product_items`
                                        WHERE
                                          `stc_sale_product_items_product_id`='".$row['stc_product_id']."'
                                      ");

                                      $challan_qty= 0;
                                      foreach($odinchallanqry as $odinchallanrow){
                                        $challan_qty=$odinchallanrow['challan_qty'];
                                      }

                                      $odineinvqry=mysqli_query($con, "
                                        SELECT
                                          SUM(`stc_electronics_inventory_item_qty`) as einv_qty
                                        FROM
                                          `stc_electronics_inventory`
                                        WHERE
                                          `stc_electronics_inventory_item_id`='".$row['stc_product_id']."'
                                      ");

                                      $einv_qty= 0;
                                      foreach($odineinvqry as $odineinvrow){
                                        $einv_qty=$odineinvrow['einv_qty'];
                                      }

                                      $odinechallanqry=mysqli_query($con, "
                                        SELECT
                                          SUM(`stc_sale_product_silent_challan_items_product_qty`) as echallan_qty
                                        FROM
                                          `stc_sale_product_silent_challan_items`
                                        WHERE
                                          `stc_sale_product_silent_challan_items_product_id`='".$row['stc_product_id']."'
                                      ");

                                      $echallan_qty= 0;
                                      foreach($odinechallanqry as $odinechallanrow){
                                        $echallan_qty=$odinechallanrow['echallan_qty'];
                                      }
                                      $total_in=$allgrn_qty + $inv_qty + $challan_qty;
                                      $total_out=$einv_qty + $echallan_qty;

                                      $colortr='';
                                      if($total_out>$total_in){
                                        $colortr='style="color: red;background: #bdbdff;font-weight: bold;"';
                                      }elseif($inv_qty>$einv_qty){
                                        $colortr='style="color: red;background: #ffcc00;font-weight: bold;"';
                                      }
                                      if($inv_qty>$einv_qty){
                                        $odin.='
                                          <tr '.$colortr.'>
                                                <td title="Item ID">
                                                  <p>'.$row["stc_product_id"].'</p>
                                                </td>
                                                <td title="Item Description">
                                                  <p>'.$row["stc_product_name"].'</p>
                                                </td>
                                                <td class="text-center" title="Item Category">
                                                  <p>'.$row["stc_cat_name"].'</p>
                                                </td>
                                                <td class="text-right" title="Total PO Quantity">
                                                  <p>'.number_format($total_po_qty, 2).'</p>
                                                </td>
                                                <td class="text-right" title="Total GRN Quantity">
                                                  <p>'.number_format($allgrn_qty, 2).'</p>
                                                </td>
                                                <td class="text-right" title="Inventory Quantity">
                                                  <p>'.number_format($inv_qty, 2).'</p>
                                                </td>
                                                <td class="text-right" title="Challan Quantity">
                                                  <p>'.number_format($challan_qty, 2).'</p>
                                                </td>
                                                <td class="text-right" title="Electronics Inventory Quantity">
                                                  <p>'.number_format($einv_qty, 2).'</p>
                                                </td>
                                                <td class="text-right" title="Electronics Challan Quantity">
                                                  <p>'.number_format($echallan_qty, 2).'</p>
                                                </td>
                                                </tr>           
                                          ';    
                                        }

                                        // if($counter==15){
                                        //  break;
                                        // }    
                                    }
                                  }
                                  $odin.='
                                      </tbody>
                                        </table>
                                  ';
                                  echo $odin;
                                ?>
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
  <script>
    $(document).ready(function(){
      $('body').delegate('#challandatefilt', 'click', function(e){
        e.preventDefault();
        var begdate=$('.begdate').val();
        var enddate=$('.enddate').val();
        $.ajax({
          url      :"../vanaheim/product-links.php",  
          method   :"POST",
          data     :{  
              begdate:begdate,
              enddate:enddate,
              stccallprolinks:1  
          },  
          dataType : 'JSON',
          success  : function(response){
            // console.log(response);
            $('.stc-view-silent-product-links-row-fetch').html(response);
          }
        });
      });

      // call priduct links by inventory
      stc_call_product_links_byinv(0, 15);
      function stc_call_product_links_byinv(begval, endval){        
        $.ajax({
          url      :"../vanaheim/product-links.php",  
          method   :"POST",
          data     :{  
              begval:begval,
              endval:endval,
              stccallprolinksinv:1  
          },  
          // dataType : 'JSON',
          success  : function(response){
            console.log(response);
            $('.stc-product-by-inventory').html(response);
          }
        });
      }

      // paging before search
      $('body').delegate('.begbuttoninvsearch', 'click', function(e){
        e.preventDefault();
        begvalue=$('.begvalueinputsearch').val();
        endvalue=$('.endvalueinputsearch').val();
        if(begvalue==0){
          alert("Seriously!!!");
        }else{
          outbegvalueinputted= (+begvalue) - 15;
          outendvalueinputted= (+endvalue) - 15;
          jsbegvalue=outbegvalueinputted;
          jsendvalue=outendvalueinputted;
          stc_call_product_links_byinv(jsbegvalue, jsendvalue);
        }
      });

      // paging after search
      $('body').delegate('.endbuttoninvsearch', 'click', function(e){
        e.preventDefault();
        begvalue=$('.begvalueinputsearch').val();
        endvalue=$('.endvalueinputsearch').val();
        if(endvalue==0){
          alert("Seriously!!!");
        }else{
          outbegvalueinputted= (+begvalue) + 15;
          outendvalueinputted= (+endvalue) + 15;
          jsbegvalue=outbegvalueinputted;
          jsendvalue=outendvalueinputted;
          stc_call_product_links_byinv(jsbegvalue, jsendvalue);
        }
      });

    });
  </script>
</body>

</html>