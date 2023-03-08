<?php
ini_set("session.gc_maxlifetime", 21600);
session_set_cookie_params(21600);
session_start();
if(empty(@$_SESSION['stc_admin_info_id'])){
    header('location:login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Edit Purchase Product - STC</title>
    <link rel = "icon" type = "image/png" href="img/stc_logo_title.png">
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Roboto:400,700" />
    <!-- https://fonts.google.com/specimen/Roboto -->
    <link rel="stylesheet" href="css/fontawesome.min.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput-typeahead.css" />
    <!-- https://fontawesome.com/ -->
    <!-- <link rel="stylesheet" href="stark_tower/jquery-ui-datepicker/jquery-ui.min.css" type="text/css" /> -->
    <!-- http://api.jqueryui.com/datepicker/ -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <!-- https://getbootstrap.com/ -->
    <link rel="stylesheet" href="css/templatemo-style.css">

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/infinite.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">

    <!-- <style> .sp-quantity div { display: inline; }</style> -->
    <!--
  Merchant Admin CSS Template
  https://templatemo.com/tm-524-Merchant-admin
  -->
  <style>
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
  </style>
  </head>

  <body>
   <?php include "header.php";?>

    <div class="container-fluid tm-mt-big tm-mb-big">
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="page-title-right">
                  <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="erp.php" class="text-uppercase" value="">ERP</a></li>
                    <li class="breadcrumb-item"><a href="purchase-product.php" class="" value="">Purchase Product</a></li>
                    <li class="breadcrumb-item active"><a href="#" class="active" value="">Edit Purchase Product</a></li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <!-- Create order -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-add">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">Edit Purchase Order</h2>
              </div>
            </div>
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
            <div class="row stc-edit-purchase-point-row">
              <div class="col-xl-6 col-md-6 col-sm-12">
                <form action="" class="stc-edit-purchse-product-form"> 
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Order Number
                  </label>
                  <input
                    id="ponumbershow"
                    name="ponumbershow"
                    type="text"
                    placeholder="Purchase Order Number"
                    class="form-control validate"
                    style="background-color: #186abb;"
                    value="STC/<?php echo $ponumber;?>"
                    disabled
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Order Date
                  </label>
                  <input
                    type="text"
                    class="form-control validate"
                    data-large-mode="true"
                    style="background-color: #186abb;"
                    value="<?php echo $lokigetorder['stc_purchase_product_order_date'];?>"
                  />
                </div>
              </div>
              <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Merchant Name
                  </label>
                  <input
                    id="stc_view_merchant_name"
                    name="stcmername"
                    type="text"
                    placeholder="Merchant Name"
                    class="form-control validate"
                    style="background-color: #186abb;"
                    value="<?php echo $lokigetorder['stc_merchant_name'];?>"
                    disabled
                  />

                  <select
                    id="stc_mer_edit_purchase_product"
                    class="custom-select stc-select-vendor"
                    name="stcvendor"
                  >
                  </select>
                </div>
              </div>
              <div class="col-xl-12 col-md-12 col-sm-12">
                <!-- <div class="col-12">
                  <h2 class="tm-block-title d-inline-block">Products</h2>
                </div>
                <input
                  id="searchbystceditpopdname"
                  name="stcpopdname"
                  type="text"
                  placeholder="Product Name"
                  class="form-control validate"
                /> -->
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
                          id="potags"
                          data-role="tagsinput"
                          type="text"
                          placeholder="Material Name"
                          class="form-control validate stcfilterbyponumber"
                          required
                        />
                      </td>
                      <td>
                        <button type="button" name="search" class="btn btn-primary" id="posearch">Search</button>
                      </td>
                      <td>
                        <a style="font-size: 20px;background: yellow;border-radius: 50%;padding: 5px;margin: 0;" href="#" class="upward">
                          <i class="fas fa-arrow-up"></i>
                        </a>
                        <a style="font-size: 20px;background: yellow;border-radius: 50%;padding: 5px;margin: 0; display: none;" href="#" class="downward">
                          <i class="fas fa-arrow-down"></i>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>              
              <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="row stc-call-edit-po-product-row">
                </div>
              </div>         

              <!-- line item table -->
              <div class="col-xl-12 col-md-12 col-sm-12 editPOitemscallforedit" style="width: 75em;overflow-x: auto; white-space: nowrap;">
                <table class="table table-hover grn_data" align="centre">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col" style="width:20%;">Items Desc</th>
                      <th scope="col">HSN Code</th> 
                      <th scope="col">Unit</th>
                      <th scope="col">Change Qty</th>
                      <th scope="col">Price</th>
                      <th scope="col">Total</th>
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
                              <td>".$row['stc_product_name']."</td>
                              <td>".$row['stc_product_hsncode']."</td>
                              <td>".$row['stc_product_unit']."</td>
                              <td width='12%'>      
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
                      }
                      ?>
                  </tbody>
                </table>
              </div>

              <div class="col-6">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Terms & Conditions
                  </label>
                  <textarea
                    class="form-control validate"
                    rows="6"
                    id="stceditpdtandc"
                    placeholder="Terms & Conditions"
                    required
                  ><?php echo $lokigetorder['stc_purchase_product_notes'];?></textarea>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >GTO No.
                  </label>
                  <input 
                    type="number" 
                    class="form-control edit-gto-number" 
                    placeholder="Customer GTO Number" 
                    value="<?php echo $lokigetorder['stc_purchase_product_purchased_for'];?>"
                  >
                </div>
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block text-uppercase stcsaveeditpo">Save</button>
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
    <?php include "footer.php";?>
    <script>
      $(document).ready(function(){
        $('.upward').on('click', function(e){
          e.preventDefault();
            $('.downward').toggle(500);
            $('.stc-call-edit-po-product-row').fadeOut(500);
            $('.upward').fadeOut(500);
        });

        $('.downward').on('click', function(e){
          e.preventDefault();
            $('.upward').toggle(500);
            $('.stc-call-edit-po-product-row').toggle(500);
            $('.downward').fadeOut(500);
        });
      });
    </script>
    <script>
      $(document).ready(function(){
        // load po items
        function load_po_items(query) {
          $.ajax({
            url:"asgard/purchase_product.php",
            method:"POST",
            data:{search_edit_po_pd_name_in:query},
            dataType:"json",
            success:function(data){
              // console.log(data);
              // $('#total_records').text(data.length);
              $('.stc-call-edit-po-product-row').html(data);
            }
          });
        }

        $('#posearch').click(function(){
          var query = $('#potags').val();
          load_po_items(query);
        });

      });
    </script>
  </body>
</html>
