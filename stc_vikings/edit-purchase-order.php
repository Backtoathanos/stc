<?php  
// Include authentication helper
require_once 'kattegat/auth_helper.php';

// Check authentication using the new hybrid system
STCAuthHelper::checkAuth();$page_code=403;
include("kattegat/role_check.php");

// if(($_GET['epstatus']=="granted")){
    // header("Location:forbidden.html"); 
// }else{
// }
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
    <title>Edit Purchase Product - STC</title>
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
                              <li class="breadcrumb-item"><a href="purchase-product.php" class="" value="">Purchase Order</a></li>
                              <li class="breadcrumb-item active"><a href="#" class="active" value="">Edit Purchase Order</a></li>
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
                                              >Purchase Order
                                            </h5>
                                        </div>
                                    </div>
                                </div>                                
                                <div class="row">
                                  <?php
                                    include "../MCU/db.php";
                                    if(isset($_GET['epid'])){
                                      $editpoquery=mysqli_query($con, "
                                      SELECT * FROM `stc_purchase_product`
                                      LEFT JOIN `stc_merchant`
                                      ON `stc_purchase_product_merchant_id`=`stc_merchant_id`
                                      WHERE `stc_purchase_product_id`='".$_GET['epid']."'
                                    ");
                                    $lokigetorder=mysqli_fetch_assoc($editpoquery);
                                    $str_length = 5;
                                    // hardcoded left padding if number < $str_length
                                    $ponumber = substr("0000{$lokigetorder['stc_purchase_product_id']}", -$str_length);
                                  ?>
                                    <div class="col-xl-6 col-md-6 col-sm-6">
                                      <form action="" class="stc-add-po-product-form">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="name"
                                          >Order Number
                                        </h5>
                                        <input
                                          id="ponumbershow"
                                          name="ponumbershow"
                                          type="text"
                                          placeholder="Purchase Order Number"
                                          class="form-control validate"
                                          value="STC/<?php echo $ponumber;?>"
                                          disabled
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-6">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for=""
                                          >Order Date
                                        </h5>
                                        <input
                                          type="text"
                                          class="form-control validate"
                                          data-large-mode="true"
                                          value="<?php echo $lokigetorder['stc_purchase_product_order_date'];?>"
                                          disabled
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="name"
                                          >Merchant Name
                                        </h5>
                                        <input
                                          id="stc_view_merchant_name"
                                          name="stcmername"
                                          type="text"
                                          placeholder="Merchant Name"
                                          class="form-control validate"
                                          value="<?php echo $lokigetorder['stc_merchant_name'];?>"
                                          disabled
                                        />
                                        <select
                                          id="stc_mer_edit_purchase_product"
                                          class="custom-select stc-select-vendor"
                                          name="stcvendor"
                                        ></select>
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
                                              <th scope="col">Items Desc</th>
                                              <th scope="col">HSN Code</th> 
                                              <th scope="col">Unit</th>
                                              <th scope="col">Change Qty</th>
                                              <th scope="col">Price</th>
                                              <th scope="col" width="10%">Total</th>
                                              <th scope="col">GST</th>
                                              <th scope="col" width="10%">Amount</th>
                                              <th scope="col">Actions</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                              <?php 
                                              $sl=0;
                                              $total=0;
                                              $totalgst=0;
                                              $totalbasic=0;
                                              $totalincgst=0;
                                              $lokigetitems=mysqli_query($con, "
                                                SELECT * FROM `stc_purchase_product_items`
                                                LEFT JOIN `stc_product`
                                                ON `stc_purchase_product_items_product_id`=`stc_product_id`
                                                WHERE `stc_purchase_product_items_order_id`='".$_GET['epid']."'
                                              ");
                                              $dataqty='';
                                              foreach ($lokigetitems as $row) {
                                                $sl++;
                                                $amount=$row['stc_purchase_product_items_qty'] * $row['stc_purchase_product_items_rate'];
                                                $gstamount = ($amount * $row["stc_product_gst"])/100;
                                                echo "
                                                    <tr>
                                                      <td>".$sl."</td>
                                                      <td>".$row['stc_product_id']."</td>
                                                      <td>".$row['stc_product_name']."</td>
                                                      <td>".$row['stc_product_hsncode']."</td>
                                                      <td>".$row['stc_product_unit']."</td>
                                                      <td>      
                                                        <input 
                                                          type='text' 
                                                          class='form-control stcqtyepoi".$row["stc_purchase_product_items_id"]."' 
                                                          placeholder='Quantity' 
                                                          value='".number_format($row['stc_purchase_product_items_qty'], 2)."'
                                                          style='width:120px;'
                                                        >
                                                        <a 
                                                          id='".$row["stc_purchase_product_items_id"]."' 
                                                          class='stcchangepoqty' 
                                                          href='#'
                                                        >
                                                          <i class='fas fa-arrow-right'></i>
                                                        </a>
                                                      </td>
                                                      <td>
                                                        <input 
                                                          type='text' 
                                                          class='form-control stcpriceepoi".$row["stc_purchase_product_items_id"]."' 
                                                          placeholder='Quantity' 
                                                          value='".number_format($row['stc_purchase_product_items_rate'], 2)."'
                                                          style='width:120px;'
                                                        >
                                                        <a 
                                                          id='".$row["stc_purchase_product_items_id"]."' 
                                                          class='stcchangepoprice' 
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
                                                        <a class='btn btn-danger deltopoitems' id='".$row["stc_purchase_product_items_id"]."' href='#' style='font-size:40px;text-align:center;color:#fff;'>
                                                          <i class='fa fa-times'></i>
                                                        </a>
                                                      </td>
                                                    </tr>
                                                ";
                                                $totalbasic+=$amount;
                                                $totalincgst+=$gstamount;
                                              }

                                              $total=$totalbasic + $totalincgst;
                                              echo "
                                                <tr>
                                                  <td colspan='9' align='right'>Total Basic : </td>
                                                  <td align='right'><i class='fas fa-rupee-sign'></i> ".number_format($totalbasic, 2)."</td>
                                                </tr>
                                                <tr>
                                                  <td colspan='9' align='right'>Total GST (Tax) : </td>
                                                  <td align='right'><i class='fas fa-rupee-sign'></i> ".number_format($totalincgst, 2)."</td>
                                                </tr>
                                                <tr>
                                                  <td colspan='9' align='right'>Grand Total : </td>
                                                  <td align='right'><i class='fas fa-rupee-sign'></i> ".number_format($total, 2)."</td>
                                                </tr>
                                              ";
                                              ?>
                                          </tbody>
                                        </table>
                                      </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for=""
                                          >Terms & Conditions
                                        </h5>
                                        <textarea
                                          class="form-control validate"
                                          rows="6"
                                          id="stceditpdtandc"
                                          placeholder="Terms & Conditions"
                                          required
                                        ><?php echo $lokigetorder['stc_purchase_product_notes'];?></textarea>
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="name"
                                          >Purchasing for
                                        </h5>
                                        <input
                                          type="text" 
                                          class="form-control edit-gto-number" 
                                          placeholder="Customer GTO Number" 
                                          value="<?php echo htmlentities($lokigetorder['stc_purchase_product_purchased_for']);?>"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="name"
                                          >Requisition No
                                        </h5>
                                        <input
                                          type="number" 
                                          class="form-control edit-requis-number" 
                                          placeholder="Requisition Number" 
                                          value="<?php echo $lokigetorder['stc_purchase_product_req_no'];?>"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <button type="submit" class="btn btn-primary btn-block text-uppercase stcsaveeditpo">Save</button>
                                      </div>
                                    </div>
                                    <div class="col-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <a href="#" class="btn btn-primary btn-block text-uppercase stcchangestatuspo">Change Status</a>
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
              url     : "kattegat/ragnar_purchase.php",
              method  : "post",
              data    : {
                stceditpoaction:1,
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

          // add to edit po line items 
          $('body').delegate('.add_to_cartforedit','click',function(e){
            e.preventDefault();
            $.urlParam = function(name){
            var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
              return results[1] || 0;
            }
            $.urlParam('epid');
            // console.log($.urlParam('epid'));

            var product_id = $(this).attr("id");  
            var product_poid = $.urlParam('epid');  
            var product_quantity = $('#stceditpoqty'+product_id).val();  
            var product_price = $('#stceditpdprice'+product_id).val();
            var add_to_cartforedit="edit";      

            $.ajax({  
              url       :  "kattegat/ragnar_purchase.php",  
              method    : "POST",
              data      : {  
                  product_id:product_id,
                  product_poid:product_poid,
                  product_quantity:product_quantity,
                  product_price:product_price,
                  edit_po:add_to_cartforedit  
              },  
              success   : function(data){
                // show_editpocart();
                alert(data);
                // console.log(data);     
                // window.location.reload();                          
              }  
            });
          });

          // update quantity
          $('body').delegate('.stcchangepoqty', 'click', function(e){
            e.preventDefault();
            var poid=$(this).attr("id");
            var product_qty = $('.stcqtyepoi'+poid).val();
            $.ajax({
              url       : "kattegat/ragnar_purchase.php",
              method    : "post",
              data      : {
                updatepdqty:1,
                poid:poid,
                product_qty:product_qty
              },
              dataType  : 'JSON',
              success   : function(data){
                // console.log(data);
                alert(data);
                // window.location.reload();
              }
            });
          });

          // update price
          $('body').delegate('.stcchangepoprice', 'click', function(e){
            e.preventDefault();
            var poid=$(this).attr("id");
            var product_price = $('.stcpriceepoi'+poid).val();
            $.ajax({
              url       : "kattegat/ragnar_purchase.php",
              method    : "post",
              data      : {
                updatepdprice:1,
                poid:poid,
                product_price:product_price
              },
              dataType  : 'JSON',
              success   : function(data){
                // console.log(data);
                alert(data);
                // window.location.reload();
              }
            });
          });

          // delete items
          $('body').delegate('.deltopoitems', 'click', function(e){
            e.preventDefault();
            var poid=$(this).attr("id");
            $.ajax({
              url       : "kattegat/ragnar_purchase.php",
              method    : "post",
              data      : {
                delpoli:1,
                poid:poid
              },
              dataType  : 'JSON',
              success   : function(data){
                // console.log(data);
                alert(data);
                // window.location.reload();
              }
            });
          });

          // save po
          $('body').delegate('.stcsaveeditpo', 'click', function(e){
            e.preventDefault();
            $.urlParam = function(name){
            var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
              return results[1] || 0;
            }
            $.urlParam('epid');

            var epono=$.urlParam('epid');
            var epomerchantid=$('#stc_mer_edit_purchase_product').val();
            var epotermanc=$('#stceditpdtandc').val();
            var gto_number=$('.edit-gto-number').val();
            var req_number=$('.edit-requis-number').val();
            $.ajax({  
              url       : "kattegat/ragnar_purchase.php", 
              method    : "POST",
              data      : {  
                  product_id:epono,
                  product_merchant:epomerchantid,
                  product_tandc:epotermanc,
                  gto_number:gto_number,
                  req_number:req_number,
                  saveedit_po:1  
              },
              dataType  : "JSON",
              success   : function(data){
                alert(data);
                // console.log(data);    
                window.location.reload();                          
              }  
            });    
          });

          // change status po
          $('body').delegate('.stcchangestatuspo', 'click', function(e){
            e.preventDefault();
            $.urlParam = function(name){
            var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
              return results[1] || 0;
            }
            $.urlParam('epid');
            var epono=$.urlParam('epid');
            $.ajax({  
              url       : "kattegat/ragnar_purchase.php", 
              method    : "POST",
              data      : {  
                  epono:epono,
                  changestatusedit_po:1  
              },
              // dataType  : "JSON",
              success   : function(data){
                data=data.trim();
                if(data=="Purchase order status changed."){
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
</body>
</html>