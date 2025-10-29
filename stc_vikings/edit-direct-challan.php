<?php  
// Include authentication helper
require_once 'kattegat/auth_helper.php';

// Check authentication using the new hybrid system
STCAuthHelper::checkAuth();$page_code=508;
include("kattegat/role_check.php");
?> 
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Edit Direct Challan - STC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="">
    <meta name="msapplication-tap-highlight" content="no">
    <!-- Latest compiled and minified CSS -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous"> -->
    <link rel = "icon" type = "image/png" href="images/stc_logo_title.png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="./main.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
      .stc-purchase-view-table th{
        text-align: center;
      }

      .stc-purchase-view-table{
        font-size: 10px;
      }

      .stc-purchase-view-table td p{
        font-size: 10px;
      }
      
      .fade:not(.show) {
        opacity: 10;
      }
    </style>
</head>
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <?php include_once("header-nav.php");?>
        <div class="app-main">
            <?php include_once("sidebar-nav.php");?>                   
            <div class="app-main__outer">
                    <div class="app-main__inner"> 
                      <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                          <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                              <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                              <li class="breadcrumb-item"><a href="direct-challan.php">Direct Challan</a></li>
                              <li class="breadcrumb-item active"><a href="#" class="active">Edit Direct Challan</a></li>
                            </ol>
                          </div>
                        </div>
                      </div>
                        <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                            <li class="nav-item">
                                <a role="tab" class="nav-link active" id="tab-2" data-toggle="tab" href="#tab-content-2">
                                    <span>Edit Purchase Order</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane tabs-animation fade show active" id="tab-content-1" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >Direct Challan
                                            </h5>
                                        </div>
                                    </div>
                                </div>                                
                                <div class="row">
                                  <?php
                                    if(isset($_GET['dcid'])){
                                    include "../MCU/db.php";
                                    $challanquery=mysqli_query($con, "
                                      SELECT * FROM `stc_sale_product` 
                                      LEFT JOIN `stc_customer`
                                      ON `stc_sale_product_cust_id`=`stc_customer_id`
                                      WHERE `stc_sale_product_id`='".$_GET['dcid']."'
                                    ");
                                    $getchallaninfo=mysqli_fetch_assoc($challanquery);
                                  ?>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <form action="" class="stc-add-po-product-form">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="name"
                                          >Challan Number
                                        </h5>
                                        <input
                                          id="gtonumbershow"
                                          name="stcmername"
                                          type="text"
                                          placeholder="Sale Order Number"
                                          class="form-control validate"
                                          value="STC/DC/<?php echo substr("0000{$getchallaninfo['stc_sale_product_id']}", -5);?>"
                                          disabled
                                        />
                                      </div>
                                    </div>                                    
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for=""
                                          >Challan Date
                                        </h5>
                                        <input
                                          id="expire_date"
                                          name="expire_date"
                                          type="text"
                                          class="form-control validate"
                                          value="<?php echo $getchallaninfo['stc_sale_product_date'];?>"
                                          data-large-mode="true"
                                          disabled
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="name"
                                          >Customer Name
                                        </h5>
                                        <input
                                          id="#"
                                          name="stcmername"
                                          type="text"
                                          placeholder="Sale Order Number"
                                          class="form-control validate"
                                          value="<?php echo $getchallaninfo['stc_customer_name'];?>"
                                          disabled
                                        />
                                        <select
                                          id="stc_customer_edit_sale_product"
                                          class="custom-select stc-select-customer"
                                          name="stcvendor"
                                        >
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for=""
                                          >Customer Order Number
                                        </h5>
                                        <input
                                          id="stceditcustorderno"
                                          type="text"
                                          class="form-control validate"
                                          placeholder="Customer Order Number"
                                          value="<?php echo $getchallaninfo['stc_sale_product_cust_order_no'];?>"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for=""
                                          >Customer Order Date
                                        </h5>
                                        <input
                                          id="stceditsaleordercustorderdate"
                                          name="saleordercustorderdate"
                                          type="text"
                                          placeholder="Customer Order Date"
                                          class="form-control validate saleordercustorderdate"
                                          value="<?php echo date('d-m-Y', strtotime($getchallaninfo['stc_sale_product_cust_order_date']));?>"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="name"
                                          >Merchant Name
                                        </h5>
                                        <select
                                          id="stceditsale_dc_merchant_name"
                                          class="custom-select stc-select-vendor"
                                          name="stcvendor"
                                        >
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-6">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="invoicenumber"
                                          >Bill Invoice/Challan Number
                                        </h5>
                                        <input
                                          name="stcbillinvonumber"
                                          type="text"
                                          placeholder="Challan/Invoice Number"
                                          class="form-control validate stceditsalebillinvonumber"
                                          data-large-mode="true"
                                          value="<?php echo $getchallaninfo['stc_sale_product_dc_invo_no'];?>"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-6">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="invoicedate"
                                          >Bill Invoice/Challan Date
                                        </h5>
                                        <input
                                          name="stcbillinvodate"
                                          placeholder="Challan Date"
                                          class="form-control validate stceditsalebillinvodate"
                                          value="<?php echo date('d-m-Y', strtotime($getchallaninfo['stc_sale_product_dc_invo_date']));?>"
                                        />
                                      </div>
                                    </div>  
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for=""
                                          >Way Bill No
                                        </h5>
                                        <input
                                          id="stceditsaleorderwaybillno"
                                          type="text"
                                          class="form-control validate"
                                          placeholder="Way Bill No"
                                          value="<?php echo $getchallaninfo['stc_sale_product_way_bill_no'];?>"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for=""
                                          >LR No
                                        </h5>
                                        <input
                                          id="stceditsaleorderlrno"
                                          type="text"
                                          class="form-control validate"
                                          placeholder="LR No"
                                          value="<?php echo $getchallaninfo['stc_sale_product_lr_no'];?>"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for=""
                                          >Date of Supply
                                        </h5>
                                        <input
                                          id="stceditsaleorderdatesupply"
                                          type="text"
                                          class="form-control validate"
                                          placeholder="Date of Supply"
                                          value="<?php echo $getchallaninfo['stc_sale_product_dosupply'];?>"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for=""
                                          >Place of Supply
                                        </h5>
                                        <input
                                          id="stceditsaleorderplacesupply"
                                          type="text"
                                          class="form-control validate"
                                          placeholder="Place of Supply"
                                          value="<?php echo $getchallaninfo['stc_sale_product_posupply'];?>"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for=""
                                          >Order Refrence
                                        </h5>
                                        <input
                                          id="stceditsaleorderfrence"
                                          type="text"
                                          class="form-control validate"
                                          placeholder="Customer Refrence"
                                          value="<?php echo $getchallaninfo['stc_sale_product_refrence'];?>"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for=""
                                          >Site Name
                                        </h5>
                                        <input
                                          id="stceditsaleordercustsitename"
                                          type="text"
                                          class="form-control validate"
                                          placeholder="Site Name"
                                          value="<?php echo $getchallaninfo['stc_sale_product_sitename'];?>"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for=""
                                          >Contact Person
                                        </h5>
                                        <input
                                          id="stceditsaleordercustcontperson"
                                          type="text"
                                          class="form-control validate"
                                          placeholder="Contact Person"
                                          value="<?php echo $getchallaninfo['stc_sale_product_cont_person'];?>"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for=""
                                          >Contact Number
                                        </h5>
                                        <input
                                          id="stceditsaleordercustcontnumber"
                                          type="text"
                                          class="form-control validate"
                                          placeholder="Contact Number"
                                          value="<?php echo $getchallaninfo['stc_sale_product_cont_number'];?>"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for=""
                                          >Address
                                        </h5>
                                        <textarea
                                          class="form-control validate"
                                          rows="2"
                                          id="stceditsoshipaddress"
                                          placeholder="Shipping Address"
                                          required
                                        ><?php echo $getchallaninfo['stc_sale_product_siteaddress'];?></textarea>
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for=""
                                          >Terms & Condition
                                        </h5>
                                        <textarea
                                          class="form-control validate"
                                          rows="2"
                                          id="stceditsotandc"
                                          placeholder="Terms & Condition"
                                          required
                                        ><?php echo $getchallaninfo['stc_sale_product_tandc'];?></textarea>
                                      </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <form action="" class="stc-view-product-form">
                                            <table class="table table-hover table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">By Category</th>
                                                        <th scope="col">By Name</th>
                                                        <th scope="col">By Sub Category</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <div class="card-border mb-3 card card-body border-success">
                                                              <select
                                                                class="custom-select tm-select-accounts call_cat"
                                                                id="editfilterbycat"
                                                                name="stcpdcategory"
                                                              >
                                                              </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="card-border mb-3 card card-body border-success">
                                                              <input
                                                                id="editsearchbystcname"
                                                                name="stcsearchpdname"
                                                                type="text"
                                                                placeholder="Product Name"
                                                                class="form-control validate"
                                                                required
                                                              />
                                                              <input type="hidden" name="search_alo_in">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="card-border mb-3 card card-body border-success">
                                                                <select
                                                                  class="custom-select tm-select-accounts call_sub_cat"
                                                                  name="stcpdsubcategory"
                                                                  id="editfilterbysubcat"
                                                                >
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                          <a style="font-size: 20px;background: yellow;border-radius: 50%;padding: 5px;margin: 0;" href="#" class="editupward">
                                                            <i class="fas fa-arrow-up"></i>
                                                          </a>
                                                          <a style="font-size: 20px;background: yellow;border-radius: 50%;padding: 5px;margin: 0; display: none;" href="#" class="editdownward">
                                                            <i class="fas fa-arrow-down"></i>
                                                          </a>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </form>
                                      </div>
                                    </div>                   
                                    <div class="col-xl-12 col-md-12 col-sm-12 thishypo">
                                      <div class="row stc-call-view-product-row">
                                      </div>
                                    </div>

                                    <!-- line item table -->
                                    <div class="col-xl-12 col-md-12 col-sm-12 flase">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <table class="table table-hover grn_data" align="centre">
                                          <thead>
                                            <tr>
                                              <th scope="col">#</th>
                                              <th scope="col">Item Code</th>
                                              <th scope="col" style="width: 15%;">Items</th>
                                              <th scope="col">HSN Code</th> 
                                              <th scope="col">Unit</th>
                                              <th scope="col" width="10%">Qty</th>                     
                                              <th scope="col">Purchase Price</th>                    
                                              <th scope="col">Soled Price</th>
                                              <th scope="col" width="10%">Amount</th>
                                              <th scope="col">GST</th>
                                              <th scope="col" width="10%">Amount</th>
                                              <th scope="col">Action</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            <?php 
                                              $sl=0;
                                              $total=0;
                                              $totalgst=0;
                                              $lokigetitems=mysqli_query($con, "
                                                SELECT * FROM `stc_sale_product_dc_items`
                                                LEFT JOIN `stc_product`
                                                ON `stc_sale_product_dc_items_product_id`=`stc_product_id`
                                                WHERE `stc_sale_product_dc_items_sale_product_id`='".$_GET['dcid']."'
                                              ");
                                              $dataqty='';
                                              foreach ($lokigetitems as $row) {
                                                $sl++;
                                                $amount=$row['stc_sale_product_dc_items_product_qty'] * $row['stc_sale_product_dc_items_product_sale_rate'];
                                                $gstamount = ($amount * $row["stc_product_gst"])/100;
                                                echo "
                                                    <tr>
                                                      <td>".$sl."</td>
                                                      <td>".$row['stc_product_id']."</td>
                                                      <td>".$row['stc_product_name']."</td>
                                                      <td>".$row['stc_product_hsncode']."</td>
                                                      <td>".$row['stc_product_unit']."</td>
                                                      <td width='12%'>      
                                                        <input 
                                                          type='text' 
                                                          class='form-control stcqtyechallani".$row["stc_sale_product_dc_items_id"]."' 
                                                          placeholder='Quantity' 
                                                          value='".number_format($row['stc_sale_product_dc_items_product_qty'], 2)."'
                                                          style='width:120px;'
                                                        >
                                                        <input 
                                                          type='hidden' 
                                                          class='form-control stcdelqtyechallani".$row["stc_sale_product_dc_items_id"]."' 
                                                          value='".number_format($row['stc_sale_product_dc_items_product_qty'], 2)."'
                                                        >
                                                        <a 
                                                          id='".$row["stc_sale_product_dc_items_id"]."' 
                                                          class='stcchangedchallanqty' 
                                                          href='#'
                                                        >
                                                          <i class='fas fa-arrow-right'></i>
                                                        </a>
                                                      </td>
                                                      <td>
                                                        <input 
                                                          type='text' 
                                                          class='form-control stcpurpriceechalani".$row["stc_sale_product_dc_items_id"]."' 
                                                          placeholder='Quantity' 
                                                          value='".number_format($row['stc_sale_product_dc_items_product_rate'], 2)."'
                                                          style='width:120px;'
                                                        >
                                                        <a 
                                                          id='".$row["stc_sale_product_dc_items_id"]."' 
                                                          class='stcchangedchallanpurprice' 
                                                          href='#'
                                                        >
                                                          <i class='fas fa-arrow-right'></i>
                                                        </a>
                                                      </td>
                                                      <td>
                                                        <input 
                                                          type='text' 
                                                          class='form-control stcpriceechalani".$row["stc_sale_product_dc_items_id"]."' 
                                                          placeholder='Quantity' 
                                                          value='".number_format($row['stc_sale_product_dc_items_product_sale_rate'], 2)."'
                                                          style='width:120px;'
                                                        >
                                                        <a 
                                                          id='".$row["stc_sale_product_dc_items_id"]."' 
                                                          class='stcchangedchallanprice' 
                                                          href='#'
                                                        >
                                                          <i class='fas fa-arrow-right'></i>
                                                        </a>
                                                      </td>
                                                      <td><i class='fas fa-rupee-sign'></i> ".number_format($amount, 2)."</td>
                                                      <td>".$row['stc_product_gst']."%</td>
                                                      <td align='right'><i class='fas fa-rupee-sign'></i> ".number_format($amount + $gstamount, 2)."
                                                      </td>
                                                      <td align='right'>
                                                        <a class='btn btn-danger deltodchallanitems' id='".$row["stc_sale_product_dc_items_id"]."' href='#' style='font-size:40px;text-align:center;color:#fff;'>
                                                          <i class='fa fa-times'></i>
                                                        </a>
                                                      </td>
                                                    </tr>
                                                ";
                                              }
                                              ?>
                                          </tbody>
                                        </table>
                                      </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for=""
                                          >Notes
                                        </h5>
                                        <textarea
                                          class="form-control validate"
                                          rows="2"
                                          id="stceditsonotes"
                                          placeholder="Notes"
                                          required
                                        ><?php echo $getchallaninfo['stc_sale_product_notes'];?></textarea>
                                      </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <button type="submit" class="btn btn-primary btn-block text-uppercase stcsaveeditdirectchallan">Save</button>
                                      </div>
                                    </div>
                                  </form>
                                  <?php

                                  }
                                  ?>
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
    <script>
        $(document).ready(function(){
          // call customer for sale
          stc_call_customer();
          function stc_call_customer(){
            $.ajax({
              url       : "kattegat/ragnar_sale.php",
              method    : "post",
              data      : {friday_vendor:1},
              dataType  : 'JSON',
              success   : function(data){
                // console.log(data);
                $('#expire_date').val(data['time']);
                $('.stc-select-customer').html(data['customer']);
              }
            });
          }

          // call merchant for purchase
          stc_vendor_on_purchase_page();
          function stc_vendor_on_purchase_page(){
            $.ajax({
              url       : "kattegat/ragnar_purchase.php",
              method    : "post",
              data      : {friday_vendor:1},
              dataType  : 'JSON',
              success   : function(data){
                // console.log(data);
                $('#expire_date').val(data['time']);
                $('.stc-select-vendor').html(data['vendor']);
              }
            });
          }

          $('.editupward').on('click', function(e){
            e.preventDefault();
              $('.editdownward').toggle(500);
              $('.editproducttagshow').fadeOut(500);
              $('.editupward').fadeOut(500);
          });

          $('.editdownward').on('click', function(e){
            e.preventDefault();
              $('.editupward').toggle(500);
              $('.thishypo').toggle(500);
              $('.editdownward').fadeOut(500);
          });

          var jsfiltercat;
          var jsfiltersubcat;
          var jsfiltername;
          // filter function
          // filter by cat
          $("#editfilterbycat").change(function(e){
            e.preventDefault();
            $('.stc-call-view-product-row').html("Loading...");
            jsfiltercat = $(this).val();
            jsfiltersubcat = $('#editfilterbysubcat').val();
            jsfiltername = $('#editsearchbystcname').val();
            stc_filter_pro_foredit(jsfiltercat, jsfiltersubcat ,jsfiltername);
          });

          // filter by sub cat
          $("#editfilterbysubcat").change(function(e){
            e.preventDefault();
            $('.stc-call-view-product-row').html("Loading...");
            jsfiltercat = $('#editfilterbycat').val();
            jsfiltersubcat = $(this).val();
            jsfiltername = $('#editsearchbystcname').val();
            stc_filter_pro_foredit(jsfiltercat, jsfiltersubcat ,jsfiltername);
          });

          // filter by name
          $("#editsearchbystcname").on('keyup', function(e){
            e.preventDefault();
            jsfiltercat = $('#editfilterbycat').val();
            jsfiltersubcat = $('#editfilterbysubcat').val();
            jsfiltername = $(this).val();
            var responset='<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12"><a href="#" class="btn btn-primary btn-block text-uppercase mb-3">Atleast type word in text field or search via category or sub category!!!</a></div>';
            var countlen=jsfiltername.length;
            if((+jsfiltername!='') && countlen>=3){
              stc_filter_pro_foredit(jsfiltercat, jsfiltersubcat ,jsfiltername);
            }else{
              $('.stc-call-view-product-row').html(responset);
            }
          });

          // filter function
          function stc_filter_pro_foredit(jsfiltercat, jsfiltersubcat ,jsfiltername){
            $.ajax({
              url     : "kattegat/ragnar_sale.php",
              method  : "post",
              data    : {
                stceditdcsoaction:1,
                phpfiltercatout:jsfiltercat,
                phpfiltersubcatout:jsfiltersubcat,
                phpfilternameout:jsfiltername
              },
              // dataType : 'JSON',
              success : function(data){
                // console.log(data);
                $('.stc-call-view-product-row').html(data);
              }
            });
          }

          // edit add challan items 
          $('body').delegate('.add_to_cartforeditdirectchallan','click',function(e){
            e.preventDefault();
            $.urlParam = function(name){
            var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
              return results[1] || 0;
            }
            $.urlParam('dcid');
            var product_id = $(this).attr("id");  
            var product_soid = $.urlParam('dcid');  
            var product_quantity = $('#stceditchallanqty'+product_id).val();  
            var product_price = $('#stceditchallanprice'+product_id).val(); 
            var product_price_perc = $('#stcpdchallanperc'+product_id).val();    
            var add_to_cartforeditdirectchallan="edit";  
            $.ajax({  
              url       : "kattegat/ragnar_sale.php", 
              method    : "POST",
              data      : {  
                  product_id:product_id,
                  product_soid:product_soid,
                  product_quantity:product_quantity,
                  product_price:product_price,
                  product_price_perc:product_price_perc,
                  edit_direct_challan:add_to_cartforeditdirectchallan  
              },  
              success   : function(data){
                var data=data.trim();
                if(data=="Product added!!!"){
                  alert("Product Added Successfully!!!"); 
                }else{
                  alert(data); 
                }                         
              }  
            });
          });

          // update quantity
          $('body').delegate('.stcchangedchallanqty', 'click', function(e){
            e.preventDefault();
            $.urlParam = function(name){
            var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
              return results[1] || 0;
            }
            var dcid=$.urlParam('dcid');
            var challanid=$(this).attr("id");
            var product_qty = $('.stcqtyechallani'+challanid).val();
            var inward_qty = $('.stcdelqtyechallani'+challanid).val();
            $.ajax({
              url       : "kattegat/ragnar_sale.php", 
              method    : "post",
              data      : {
                updatedirectchallanqty:1,
                challanid:challanid,
                inward_qty:inward_qty,
                dcid:dcid,
                product_qty:product_qty
              },
              // dataType : 'JSON',
              success   : function(data){
                // console.log(data);
                data=data.trim();
                if(data=="success"){
                  alert("Quantity Updated Succefully!!!");
                  window.location.reload();
                }else{
                  alert(data);
                }
              }
            });
          });

          // update price
          $('body').delegate('.stcchangedchallanprice', 'click', function(e){
            e.preventDefault();
            $.urlParam = function(name){
            var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
              return results[1] || 0;
            }
            var dcid=$.urlParam('dcid');
            var soid=$(this).attr("id");
            var product_price = $('.stcpriceechalani'+soid).val();
            $.ajax({
              url       : "kattegat/ragnar_sale.php", 
              method : "post",
              data : {
                updatediectchallanprice:1,
                soid:soid,
                dcid:dcid,
                product_price:product_price
              },
              dataType : 'JSON',
              success : function(data){
                // console.log(data);
                data=data.trim();
                if(data=="Product price updated!!!"){
                  alert("Product Sale Price Updated Succefully!!!");
                  window.location.reload();
                }else{
                  alert(data);
                }
              }
            });
          });

          // update purchase price
          $('body').delegate('.stcchangedchallanpurprice', 'click', function(e){
            e.preventDefault();
            $.urlParam = function(name){
            var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
              return results[1] || 0;
            }
            var dcid=$.urlParam('dcid');
            var soid=$(this).attr("id");
            var product_price = $('.stcpurpriceechalani'+soid).val();
            $.ajax({
              url       : "kattegat/ragnar_sale.php", 
              method : "post",
              data : {
                updatediectchallanpurprice:1,
                soid:soid,
                dcid:dcid,
                product_price:product_price
              },
              // dataType : 'JSON',
              success : function(data){
                console.log(data);
                data=data.trim();
                if(data=="Product purchase price updated!!!"){
                  alert("Product Purchase Price Updated Succefully!!!");
                  window.location.reload();
                }else{
                  alert(data);
                }
              }
            });
          });

          // delete items
          $('body').delegate('.deltodchallanitems', 'click', function(e){
            e.preventDefault();
            $.urlParam = function(name){
            var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
              return results[1] || 0;
            }
            var dcid=$.urlParam('dcid');
            var soid=$(this).attr("id");
            var delqty=$('.stcdelqtyechallani'+soid).val();
            $.ajax({
              url       : "kattegat/ragnar_sale.php", 
              method : "post",
              data : {
                deldchallanli:1,
                delqty:delqty,
                dcid:dcid,
                soid:soid
              },
              dataType : 'JSON'     ,
              success : function(data){
                // console.log(data);
                data=data.trim();
                if(data=="Item removed!!!"){
                  alert("Product Removed Succefully!!!");
                  window.location.reload();
                }else{
                  alert(data);
                }
              }
            });
          });

          // save challan
          $('body').delegate('.stcsaveeditdirectchallan', 'click', function(e){
            e.preventDefault();
            $.urlParam = function(name){
            var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
              return results[1] || 0;
            }
            $.urlParam('dcid');

            var esono               =         $.urlParam('dcid');
            var esomerchantid       =         $('#stc_customer_edit_sale_product').val();
            var esocustorderno      =         $('#stceditcustorderno').val();
            var esomername          =         $('#stceditsale_dc_merchant_name').val();
            var esobillinvono       =         $('.stceditsalebillinvonumber').val();
            var esobillinvodate     =         $('.stceditsalebillinvodate').val();
            var esowaybillno        =         $('#stceditsaleorderwaybillno').val();
            var esowaylrno          =         $('#stceditsaleorderlrno').val();
            var esocustorddate      =         $('#stceditsaleordercustorderdate').val();
            var esodosupply         =         $('#stceditsaleorderdatesupply').val();
            var esoposupply         =         $('#stceditsaleorderplacesupply').val();
            var esoorderrefrence    =         $('#stceditsaleorderfrence').val();
            var esocustsitename     =         $('#stceditsaleordercustsitename').val();
            var esocustcontperson   =         $('#stceditsaleordercustcontperson').val();
            var esocustcontnumber   =         $('#stceditsaleordercustcontnumber').val();
            var esoshipaddres       =         $('#stceditsoshipaddress').val();
            var esotermanc          =         $('#stceditsotandc').val();
            var esontes             =         $('#stceditsonotes').val();
            $.ajax({  
              url         : "kattegat/ragnar_sale.php",   
              method      : "POST",
              data        : {  
                esono                       :   esono,
                esomerchantid               :   esomerchantid,
                esocustorderno              :   esocustorderno, 
                esomername                  :   esomername,  
                esobillinvono               :   esobillinvono,  
                esobillinvodate             :   esobillinvodate,
                esowaybillno                :   esowaybillno,
                esowaylrno                  :   esowaylrno,
                esocustorddate              :   esocustorddate,
                esodosupply                 :   esodosupply,
                esoposupply                 :   esoposupply,
                esoorderrefrence            :   esoorderrefrence,
                esocustsitename             :   esocustsitename,
                esocustcontperson           :   esocustcontperson,
                esocustcontnumber           :   esocustcontnumber,
                esoshipaddres               :   esoshipaddres,
                esotermanc                  :   esotermanc,
                esontes                     :   esontes,
                edit_save_direct_challan    :   1
              },  
              dataType    : "JSON",
              success     : function(data){
                data=data.trim();
                if(data=="Challan updated!!!"){
                  alert("Direct Challan Updated Succefully!!!");
                  window.location.reload();
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