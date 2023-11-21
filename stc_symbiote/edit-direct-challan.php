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
    <title>Challan - STC</title>
    <link rel = "icon" type = "image/png" href="img/stc_logo_title.png">
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Roboto:400,700" />
    <!-- https://fonts.google.com/specimen/Roboto -->
    <link rel="stylesheet" href="css/fontawesome.min.css" />
    <!-- https://fontawesome.com/ -->
    <!-- <link rel="stylesheet" href="stark_tower/jquery-ui-datepicker/jquery-ui.min.css" type="text/css" /> -->
    <!-- http://api.jqueryui.com/datepicker/ -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <!-- https://getbootstrap.com/ -->
    <link rel="stylesheet" href="css/templatemo-style.css">

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/infinite.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput-typeahead.css" />
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
                    <li class="breadcrumb-item active"><a href="challan.php" class="active" value="">Challan</a></li>
                    <li class="breadcrumb-item active"><a href="#" class="active" value="">Edit Direct Challan</a></li>
                  </ol>
                </div>
              </div>
            </div>
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
        <!-- Create order -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-add">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">Direct Challan</h2>
              </div>
            </div>
            <div class="row stc-purchase-point-row">
              <div class="col-xl-6 col-md-6 col-sm-12">
                <form action="" class="stc-edit-sale-product-form"> 
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Challan Number
                  </label>
                  <input
                    id="gtonumbershow"
                    name="stcmername"
                    type="text"
                    placeholder="Sale Order Number"
                    class="form-control validate"
                    style="background-color: #f9f9f9;color: black;font-weight: bold;"
                    value="STC/DC/<?php echo substr("0000{$getchallaninfo['stc_sale_product_id']}", -5);?>"
                    disabled
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Challan Date
                  </label>
                  <input
                    id="expire_date"
                    name="expire_date"
                    type="text"
                    class="form-control validate"
                    style="background-color: #f9f9f9;color: black;font-weight: bold;"
                    value="<?php echo $getchallaninfo['stc_sale_product_date'];?>"
                    data-large-mode="true"
                    disabled
                  />
                </div>
              </div>
              <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Customer Name
                  </label>
                  <input
                    id="#"
                    name="stcmername"
                    type="text"
                    placeholder="Sale Order Number"
                    class="form-control validate"
                    style="background-color: #f9f9f9;color: black;font-weight: bold;"
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
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Customer Order Number
                  </label>
                  <input
                    id="stceditcustorderno"
                    type="text"
                    class="form-control validate"
                    placeholder="Customer Order Number"
                    style="background-color: #186abb;"
                    value="<?php echo $getchallaninfo['stc_sale_product_cust_order_no'];?>"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Customer Order Date
                  </label>
                  <input
                    id="stceditsaleordercustorderdate"
                    name="saleordercustorderdate"
                    type="text"
                    placeholder="Customer Order Date"
                    class="form-control validate saleordercustorderdate"
                    style="background-color: #186abb;"
                    value="<?php echo date('d-m-Y', strtotime($getchallaninfo['stc_sale_product_cust_order_date']));?>"
                  />
                </div>
              </div>
              <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Merchant Name
                  </label>
                  <select
                    id="stceditsale_dc_merchant_name"
                    class="custom-select stc-select-vendor"
                    name="stcvendor"
                  >
                  </select>
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-6">
                <div class="form-group mb-3">
                  <label
                    for="invoicenumber"
                    >Bill Invoice/Challan Number
                  </label>
                  <input
                    name="stcbillinvonumber"
                    type="text"
                    placeholder="Challan/Invoice Number"
                    class="form-control validate stceditsalebillinvonumber"
                    data-large-mode="true"
                    style="background-color: #186abb;"
                    value="<?php echo $getchallaninfo['stc_sale_product_dc_invo_no'];?>"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-6">
                <div class="form-group mb-3">
                  <label
                    for="invoicedate"
                    >Bill Invoice/Challan Date
                  </label>
                  <input
                    name="stcbillinvodate"
                    placeholder="Challan Date"
                    class="form-control validate stceditsalebillinvodate"
                    style="background-color: #186abb;"
                    value="<?php echo date('d-m-Y', strtotime($getchallaninfo['stc_sale_product_dc_invo_date']));?>"
                  />
                </div>
              </div>  
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Way Bill No
                  </label>
                  <input
                    id="stceditsaleorderwaybillno"
                    type="text"
                    class="form-control validate"
                    placeholder="Way Bill No"
                    style="background-color: #186abb;"
                    value="<?php echo $getchallaninfo['stc_sale_product_way_bill_no'];?>"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >LR No
                  </label>
                  <input
                    id="stceditsaleorderlrno"
                    type="text"
                    class="form-control validate"
                    placeholder="LR No"
                    style="background-color: #186abb;"
                    value="<?php echo $getchallaninfo['stc_sale_product_lr_no'];?>"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Date of Supply
                  </label>
                  <input
                    id="stceditsaleorderdatesupply"
                    type="text"
                    class="form-control validate"
                    placeholder="Date of Supply"
                    style="background-color: #186abb;"
                    value="<?php echo $getchallaninfo['stc_sale_product_dosupply'];?>"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Place of Supply
                  </label>
                  <input
                    id="stceditsaleorderplacesupply"
                    type="text"
                    class="form-control validate"
                    placeholder="Place of Supply"
                    style="background-color: #186abb;"
                    value="<?php echo $getchallaninfo['stc_sale_product_posupply'];?>"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Order Refrence
                  </label>
                  <input
                    id="stceditsaleorderfrence"
                    type="text"
                    class="form-control validate"
                    placeholder="Customer Refrence"
                    style="background-color: #186abb;"
                    value="<?php echo $getchallaninfo['stc_sale_product_refrence'];?>"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Site Name
                  </label>
                  <input
                    id="stceditsaleordercustsitename"
                    type="text"
                    class="form-control validate"
                    placeholder="Site Name"
                    style="background-color: #186abb;"
                    value="<?php echo $getchallaninfo['stc_sale_product_sitename'];?>"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Contact Person
                  </label>
                  <input
                    id="stceditsaleordercustcontperson"
                    type="text"
                    class="form-control validate"
                    placeholder="Contact Person"
                    style="background-color: #186abb;"
                    value="<?php echo $getchallaninfo['stc_sale_product_cont_person'];?>"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Contact Number
                  </label>
                  <input
                    id="stceditsaleordercustcontnumber"
                    type="text"
                    class="form-control validate"
                    placeholder="Contact Number"
                    style="background-color: #186abb;"
                    value="<?php echo $getchallaninfo['stc_sale_product_cont_number'];?>"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Address
                  </label>
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
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Terms & Condition
                  </label>
                  <textarea
                    class="form-control validate"
                    rows="2"
                    id="stceditsotandc"
                    placeholder="Terms & Condition"
                    required
                  ><?php echo $getchallaninfo['stc_sale_product_tandc'];?></textarea>
                </div>
              </div>

              <!-- product search -->
              <div class="col-xl-12 col-md-12 col-sm-12">
                <!-- <div class="col-12">
                  <h2 class="tm-block-title d-inline-block">Products</h2>
                </div>
                <input
                  id="searchbystceditchallanpdname"
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
                          id="echallantags"
                          data-role="tagsinput"
                          type="text"
                          placeholder="Material Name"
                          class="form-control validate stcfilterbyponumber"
                          required
                        />
                      </td>
                      <td>
                        <button type="button" name="search" class="btn btn-primary" id="echallansearch">Search</button>
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
              <!-- search product display -->
              <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="row stc-call-view-edit-challan-product-row">
                </div>
              </div>

              <!-- line item table -->
              <div class="col-xl-12 col-md-12 col-sm-12 ">
                <table class="table table-hover" align="centre">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col" style="width: 20%;">Items</th>
                      <th scope="col">HSN Code</th> 
                      <th scope="col">Unit</th>
                      <th scope="col">Qty</th>                     
                      <th scope="col">Purchase Price</th>                    
                      <th scope="col">Soled Price</th>
                      <th scope="col">Amount</th>
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
              <div class="col-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Notes
                  </label>
                  <textarea
                    class="form-control validate"
                    rows="2"
                    id="stceditsonotes"
                    placeholder="Notes"
                    required
                  ><?php echo $getchallaninfo['stc_sale_product_notes'];?></textarea>
                </div>
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block text-uppercase stcsaveeditdirectchallan">Save</button>
              </div>
            </div>
          </form>
          </div>
        </div>
        <?php
          }
        ?>
      </div>
    </div>
    <?php include "footer.php";?>
    <script>
      $(document).ready(function(){
        $('.upward').on('click', function(e){
          e.preventDefault();
            $('.downward').toggle(500);
            $('.stc-call-view-edit-challan-product-row').fadeOut(500);
            $('.upward').fadeOut(500);
        });

        $('.downward').on('click', function(e){
          e.preventDefault();
            $('.upward').toggle(500);
            $('.stc-call-view-edit-challan-product-row').toggle(500);
            $('.downward').fadeOut(500);
        });
        
        // challan input
        function load_edit_direct_challan_items(query) {
          $.ajax({
            url:"asgard/exp_dir_challan.php",
            method:"POST",
            data:{search_edit_sale_pd_name_in:query},
            dataType:"json",
            success:function(data){
              // console.log(data);
              // $('#total_records').text(data.length);
              $('.stc-call-view-edit-challan-product-row').html(data);
            }
          });
        }

        $('#echallansearch').click(function(){
          var query = $('#echallantags').val();
          load_edit_direct_challan_items(query);
        });
      });
    </script>
  </body>
</html>
