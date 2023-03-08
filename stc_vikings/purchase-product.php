<?php  
session_start(); 
if(isset($_SESSION["stc_empl_id"]) && ($_SESSION["stc_empl_role"]>0)){ 
}else{ 
    header("Location:index.html"); 
} 
$page_code=401;
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
    <title>Purchase Product - STC</title>
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
                        <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                            <li class="nav-item">
                                <a role="tab" class="nav-link active" id="tab-2" data-toggle="tab" href="#tab-content-2">
                                    <span>View All Purchase Order</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab-content-1">
                                    <span>Create New Purchase Order</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-3" data-toggle="tab" href="#tab-content-3">
                                    <span>GRN(Goods Reciept & Notes)</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane tabs-animation fade" id="tab-content-1" role="tabpanel">
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
                                    <div class="col-xl-6 col-md-6 col-sm-6">
                                      <form action="" class="stc-add-po-product-form">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="name"
                                          >Order Number
                                        </h5>
                                        <input
                                          id="gtonumbershow"
                                          name="stcmername"
                                          type="text"
                                          placeholder="Purchase Order Number"
                                          class="form-control validate"
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
                                          id="expire_date"
                                          name="expire_date"
                                          type="text"
                                          class="form-control validate"
                                          data-large-mode="true"
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
                                        <select
                                          id="stc_vendor_purchase_product"
                                          class="custom-select stc-select-vendor"
                                          name="stcvendor"
                                        >
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <form action="" class="stc-view-product-form">
                                            <table class="table table-hover ">
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
                                                                class="custom-select tm-select-accounts call_cat stcprosearchsame"
                                                                id="filterbycat"
                                                                name="stcpdcategory"
                                                              >
                                                              </select>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="card-border mb-3 card card-body border-success">
                                                              <input
                                                                id="searchbystcname"
                                                                name="stcsearchpdname"
                                                                type="text"
                                                                placeholder="Product Name"
                                                                class="form-control validate stcprosearchsame"
                                                                required
                                                              />
                                                              <input type="hidden" name="search_alo_in">
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="card-border mb-3 card card-body border-success">
                                                                <select
                                                                  class="custom-select tm-select-accounts call_sub_cat stcprosearchsame"
                                                                  id="filterbysubcat"
                                                                  name="stcpdsubcategory"
                                                                >
                                                                </select>
                                                            </div>
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
                                        <div class="stc-show-purchase-order-cart">
                                        </div>
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
                                          rows="10"
                                          id="stcpdtandc"
                                          placeholder="Terms & Conditions"
                                          required
                                        >1. GST : , &#13;&#10;2. Payment : NA, &#13;&#10;3. Packing & Forwarding : NA, &#13;&#10;4. Freight : NA, &#13;&#10;5. Warranty/Guarantee : , &#13;&#10;6. Delivery Time : , &#13;&#10;7. Certificates : , &#13;&#10;Others &#13;&#10;Delivery Address : Global AC System JSR PVT.LTD. C/O Majesty,  &#13;&#10;79 A Block Dhatkidih PO-Bistupur, Jamshedpur-831001, Jharkhand
                                        </textarea>
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="name"
                                          >Purchasing for
                                        </h5>
                                        <input
                                          id="gtonumberinst"
                                          name="gtonumberinst"
                                          type="text"
                                          placeholder="GTO/WOG Number"
                                          class="form-control validate"
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
                                          id="reqnoinst"
                                          name="reqnoinst"
                                          type="number"
                                          placeholder="Requisition Number"
                                          class="form-control validate"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <button type="submit" class="btn btn-primary btn-block text-uppercase stcsaveandppp">Save</button>
                                      </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <div class="tab-pane tabs-animation fade show active" id="tab-content-2" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >View Purchase Orders
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row stc-view-product-row">
                                  <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <form action="" class="stc-view-product-form">
                                          <table class="table table-hover ">
                                            <thead>
                                              <tr>
                                                <th scope="col">From/<br>To</th>
                                                <th scope="col" width="30%">By Merchant</th>
                                                <th scope="col" width="15%">By PO Number</th>
                                                <th scope="col" width="15%">By GRN Number</th>
                                                <th scope="col" width="15%">By Invoice Number</th>
                                                <th scope="col" width="15%">By Status</th>
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
                                                  <p><input type="date" value="<?php echo $effectiveDate;?>" class="form-control begdate"></p>
                                                  <p><input type="date" value="<?php echo $newDate;?>" class="form-control enddate"></p>
                                                  <p><a href="#" id="purchaseproddatefilt">
                                                    <i style="font-size: 20px;background: #0c0c1b;border-radius: 50%;padding: 5px;margin: 0;color: #77ffd3;float: right;" class="fa fa-arrow-down"></i>
                                                  </a></p>
                                                </td>
                                                <td>
                                                  <select
                                                    id="stc-po-merchant-in"
                                                    class="custom-select form-control stc-select-vendor"
                                                    name="stcvendor"
                                                  ></select>
                                                </td>
                                                <td>
                                                  <input 
                                                    type="number" 
                                                    id="stc-po-number-finder-in" 
                                                    class="form-control"
                                                    placeholder="PO Number" 
                                                  >
                                                </td>
                                                <td>
                                                  <input 
                                                    type="number" 
                                                    id="stc-grn-number-finder-in" 
                                                    class="form-control"
                                                    placeholder="GRN Number" 
                                                  >
                                                </td>
                                                <td>
                                                  <input 
                                                    type="text" 
                                                    id="stc-invoice-number-finder-in" 
                                                    class="form-control"
                                                    placeholder="Invoice Number" 
                                                  >
                                                </td>
                                                <td>
                                                  <select 
                                                    class="custom-select form-control stc-po-status-in"
                                                    >
                                                    <option value="NA">Select Status</option>
                                                    <option value="accepted">Accepted</option>
                                                    <option value="canceled">Cancel</option>
                                                    <option value="completed">Completed</option>
                                                    <option value="granted">Granted</option>
                                                    <option value="process">Process</option>
                                                  </select>
                                                </td>
                                              </tr>
                                              <tr>
                                                <td colspan="6">By Product</td>
                                              </tr>
                                              <tr>
                                                <td colspan="6">
                                                  <input 
                                                    type="text" 
                                                    id="stc-product-name-finder-in" 
                                                    class="form-control"
                                                    placeholder="Product Name" 
                                                  ><button class="mb-2 mr-2 btn btn-info form-control stc-product-finder-hit">Search</button>
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                                <div class="row stc-view-purchase-row">
                                  <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <form action="" class="stc-view-purchase-order-form">
                                          <table class="table table-hover table-bordered table-dark stc-purchase-view-table">
                                            <thead>
                                              <th width="20%">Merchant Name</th>
                                              <th>PO Date<br>PO No.</th>
                                              <th>GRN Date<br>GRN No.</th>
                                              <th>Invoice Date<br>Invoice No.</th>
                                              <th>PO Basic<br>PO Total</th>
                                              <th>Payment Types</th>
                                              <th>Requisition Date<br>Reference GTO No</th>
                                              <th>Status</th>
                                              <th>Action</th>
                                            </thead>
                                            <tbody class="stc-call-view-po-row">
                                              <tr>
                                                <td colspan="8" align="center">Search here</td>
                                              </tr>
                                              <tr>
                                              <td colspan="4">
                                                <button type="button" class="btn btn-primary begbuttoninvsearch">
                                                  <i class="fas fa-arrow-left"></i>
                                                </button>
                                                <input type="hidden" class="begvalueinput" value="0">
                                                <input type="hidden" class="begvalueinputsearch" value="0">
                                              </td>
                                              <td colspan="4">
                                                <button type="button" class="btn btn-primary endbuttoninvsearch" style="float:right;">
                                                  <i class="fas fa-arrow-right"></i>
                                                </button>
                                                <input type="hidden" class="endvalueinput" value="20">
                                                <input type="hidden" class="endvalueinputsearch" value="20">
                                              </td>
                                            </tr>
                                            </tbody>
                                          </table>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                            </div>
                            <div class="tab-pane tabs-animation fade" id="tab-content-3" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >Goods Reciept & Notes
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                  <div class="col-xl-12 col-md-12 col-sm-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <h5
                                        for="name"
                                        >Merchant Name
                                      </h5>
                                      <select
                                        id="stc-venodr-forGRN"
                                        class="custom-select stc-select-vendor"
                                        name="stcvendor"
                                      >
                                      </select>
                                    </div>
                                  </div>
                                  <div class="col-xl-6 col-md-6 col-sm-6">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <h5
                                        for="name"
                                        >PO No
                                      </h5>
                                      <select
                                        id="stc-po-forGRN"
                                        class="custom-select stc-po-forGRN"
                                        name="stcvendor"
                                      ><option value="NA"> Please Select Merchant Name</option>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="col-xl-6 col-md-6 col-sm-6">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <h5
                                        for="name"
                                        >PO Date
                                      </h5>
                                      <input
                                        type="text"
                                        class="form-control validate stc-po-date"
                                        data-large-mode="true"
                                        placeholder="Purchase order Date"
                                        disabled
                                      />
                                    </div>
                                  </div>
                                  <div class="col-xl-6 col-md-6 col-sm-6">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <h5
                                        for="name"
                                        >Invoice/Challan Number
                                      </h5>
                                      <input
                                        type="text"
                                        placeholder="Challan/Invoice Number"
                                        class="form-control validate stc-invo-number"
                                        data-large-mode="true"
                                        value=""
                                      />
                                    </div>
                                  </div>
                                  <div class="col-xl-6 col-md-6 col-sm-6">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <h5
                                        for="name"
                                        >Invoice/Challan Date
                                      </h5>
                                      <input
                                        type="date"
                                        min="2001-01-01" 
                                        max="2050-12-31"
                                        placeholder="Invoice/Challan Date"
                                        class="form-control validate stc-invo-date"
                                        value=""
                                      />
                                    </div>
                                  </div>
                                  <div class="col-xl-6 col-md-6 col-sm-6">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <button type="submit" class="mb-2 mr-2 btn btn-alternate text-uppercase stc-invo-check-product-show">Find</button>
                                    </div>
                                  </div>
                                  <div class="col-xl-6 col-md-6 col-sm-6">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <button type="submit" class="mb-2 mr-2 btn btn-warning text-uppercase stc-show-grn-items-cart">Show GRN</button>
                                    </div>
                                  </div>
                                  <div class="col-xl-12 col-md-12 col-sm-12">
                                    <div class="card-border mb-3 card card-body border-success stc-call-grn-show">
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

          $('.upward').on('click', function(e){
              e.preventDefault();
              $('.downward').toggle(500);
              $('.thishypo').fadeOut(500);
              $('.upward').fadeOut(500);
          });

          $('.downward').on('click', function(e){
            e.preventDefault();
            $('.upward').toggle(500);
            $('.thishypo').toggle(500);
            $('.downward').fadeOut(500);
          });

          var jsfiltercat;
          var jsfiltersubcat;
          var jsfiltername;
          // filter function
          // filter by cat
          $("#filterbycat").change(function(e){
            e.preventDefault();
            $('.stc-call-view-product-row').html("Loading...");
            jsfiltercat = $(this).val();
            jsfiltersubcat = $('#filterbysubcat').val();
            jsfiltername = $('#searchbystcname').val();
            stc_filter_pro_foredit(jsfiltercat, jsfiltersubcat ,jsfiltername);
          });

          // filter by sub cat
          $("#filterbysubcat").change(function(e){
            e.preventDefault();
            $('.stc-call-view-product-row').html("Loading...");
            jsfiltercat = $('#filterbycat').val();
            jsfiltersubcat = $(this).val();
            jsfiltername = $('#searchbystcname').val();
            stc_filter_pro_foredit(jsfiltercat, jsfiltersubcat ,jsfiltername);
          });

          // filter by name
          $("#searchbystcname").on('keyup', function(e){
            e.preventDefault();
            jsfiltercat = $('#filterbycat').val();
            jsfiltersubcat = $('#filterbysubcat').val();
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
                stcaction:1,
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

          // add to purchase order cart 
          $('body').delegate('.add_to_cart','click',function(e){
            e.preventDefault();
              var product_id = $(this).attr("id");  
              var product_name = $('#stcpopdname'+product_id).val(); 
              var product_unit = $('#stcpopdunit'+product_id).val();  
              var product_quantity = $('#stcpoqty'+product_id).val();  
              var product_price = $('#stcpdprice'+product_id).val();
              var product_hsncode = $('#stcpopdhsncode'+product_id).val();
              var product_gst = $('#stcpopdgst'+product_id).val();
              var add_to_cart="add";
              $.ajax({
                url     : "kattegat/ragnar_purchase.php", 
                method  : "POST",
                data    : {  
                    product_id:product_id,
                    product_name:product_name,  
                    product_unit:product_unit,
                    product_quantity:product_quantity,
                    product_price:product_price,
                    product_hsncode:product_hsncode,
                    product_gst:product_gst,
                    add_po:add_to_cart  
                },  
                success : function(data){
                  show_cart();
                  alert(data);
                  // console.log(data);                              
                }  
              });      
          });

          // show cart on product purchase add sectin page
          show_cart();
          function show_cart(){
            $.ajax({
              url      : "kattegat/ragnar_purchase.php", 
              method   : 'POST',
              data     : {stc_show_po_sess:1},
              dataType : 'JSON',
              success  : function(data){
                // console.log(data);
                  $('.stc-show-purchase-order-cart').html(data["orderTable"]);
              }
            });
          }

          // quantity change purchase product session
          $(document).on('click', '.stcqtypo', function(e){  
              e.preventDefault();
              var product_id = $(this).data("product_id");  
              var quantity = $('#stcqtypot'+product_id).val();   
              // var change_cart = $('#cartprice'+product_id).val();
              var action = "quantity_change"; 
              if(quantity != ''){  
                $.ajax({  
                  url     : "kattegat/ragnar_purchase.php",
                  method  : "POST",  
                  data    : {
                     product_id:product_id, 
                     quantity:quantity, 
                     quantity_action:action},  
                  success : function(data){ 
                    show_cart();
                     // console.log(data);
                  }  
                });  
              } 
          });  

          // Rate change purchase product session
          $(document).on('click', '.stcratepo', function(e){  
              e.preventDefault();
              var product_id = $(this).data("product_id");  
              var price = $('#stcratepot'+product_id).val();   
              // var change_cart = $('#cartprice'+product_id).val();
              var action = "quantity_change"; 
              if(price != ''){  
                $.ajax({  
                  url       : "kattegat/ragnar_purchase.php",
                  method    : "POST",  
                  data      : {
                      product_id:product_id, 
                      price:price, 
                      price_action:action},  
                  success   : function(data){ 
                    show_cart();
                     // console.log(data);
                  }  
                });  
              }
          });  

          // delete from cart purchase product session
          $('body').delegate('.stcdelpobtn','click',function(e){
              e.preventDefault();
              var product_id = $(this).attr("id");
              if(confirm("Are you sure you want to remove this product?")){   
                $.ajax({  
                  url       : "kattegat/ragnar_purchase.php",  
                  method    : "POST",
                  data      : {  
                      product_id:product_id,
                      stcdelpolinei:1  
                  },  
                  success   : function(data){  
                    show_cart();
                    alert(data);                        
                  }  
                });  
              }         
          });

          // onsave po
          $('body').delegate('.stcsaveandppp', 'click',function(e){
              e.preventDefault();
              var merchantid=$('#stc_vendor_purchase_product').val();
              var basicvalue=$('#stcbasicvalue').val();
              var termsandcond=$('#stcpdtandc').val();
              var gtonumberinst=$('#gtonumberinst').val();
              var reqnoinst=$('#reqnoinst').val();
              $.ajax({  
                url         : "kattegat/ragnar_purchase.php",
                method      : "POST",  
                data        : {
                  stcprosavepo:1,
                  merchantid:merchantid,
                  basicvalue:basicvalue,
                  termsandcond:termsandcond,
                  gtonumberinst:gtonumberinst,
                  reqnoinst:reqnoinst
                },
                dataType    : 'JSON',
                success     : function(data){ 
                  // console.log(data);
                  // stc_call_product_purchase();
                  var posresponse=data['objloki'];
                  var argument=posresponse.trim();
                  if(argument=="Success"){
                    alert("Your order is saved!!!");
                    // window.location.reload(500);
                    show_cart();
                    $('.stc-add-po-product-form')[0].reset();
                  }else{
                    alert("Please do not let any fields empty or atleast add item to create a PO!!!");
                  }
                }  
              });  
          });

          var jsbegdate='';
          var jsenddate='';
          var jsmerchantid='';
          var jsponumber='';
          var jsgrnnumber='';
          var jsinvonumber='';
          var jsstatus='';
          var jsbegvalue='';
          var jsendvalue='';
          // filter po
          // by date
          $('body').delegate('#purchaseproddatefilt', 'click', function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jsmerchantid=$("#stc-po-merchant-in").val();
            jsponumber=$("#stc-po-number-finder-in").val();
            jsgrnnumber=$("#stc-grn-number-finder-in").val();
            jsinvonumber=$("#stc-invoice-number-finder-in").val();
            jsstatus=$(".stc-po-status-in").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            stc_filter_purchase_order(jsbegdate, jsenddate, jsmerchantid, jsponumber, jsgrnnumber, jsinvonumber, jsstatus, jsbegvalue, jsendvalue);
          });

          // by merchant id
          $("#stc-po-merchant-in").change(function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jsmerchantid=$("#stc-po-merchant-in").val();
            jsponumber=$("#stc-po-number-finder-in").val();
            jsgrnnumber=$("#stc-grn-number-finder-in").val();
            jsinvonumber=$("#stc-invoice-number-finder-in").val();
            jsstatus=$(".stc-po-status-in").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            stc_filter_purchase_order(jsbegdate, jsenddate, jsmerchantid, jsponumber, jsgrnnumber, jsinvonumber, jsstatus, jsbegvalue, jsendvalue);
          });

          // by po number
          $("#stc-po-number-finder-in").on('keyup', function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jsmerchantid=$("#stc-po-merchant-in").val();
            jsponumber=$("#stc-po-number-finder-in").val();
            jsgrnnumber=$("#stc-grn-number-finder-in").val();
            jsinvonumber=$("#stc-invoice-number-finder-in").val();
            jsstatus=$(".stc-po-status-in").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            stc_filter_purchase_order(jsbegdate, jsenddate, jsmerchantid, jsponumber, jsgrnnumber, jsinvonumber, jsstatus, jsbegvalue, jsendvalue);
          });

          // by grn number
          $("#stc-grn-number-finder-in").on('keyup', function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jsmerchantid=$("#stc-po-merchant-in").val();
            jsponumber=$("#stc-po-number-finder-in").val();
            jsgrnnumber=$("#stc-grn-number-finder-in").val();
            jsinvonumber=$("#stc-invoice-number-finder-in").val();
            jsstatus=$(".stc-po-status-in").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            stc_filter_purchase_order(jsbegdate, jsenddate, jsmerchantid, jsponumber, jsgrnnumber, jsinvonumber, jsstatus, jsbegvalue, jsendvalue);
          });

          // by invoice number
          $("#stc-invoice-number-finder-in").on('keyup', function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jsmerchantid=$("#stc-po-merchant-in").val();
            jsponumber=$("#stc-po-number-finder-in").val();
            jsgrnnumber=$("#stc-grn-number-finder-in").val();
            jsinvonumber=$("#stc-invoice-number-finder-in").val();
            jsstatus=$(".stc-po-status-in").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            stc_filter_purchase_order(jsbegdate, jsenddate, jsmerchantid, jsponumber, jsgrnnumber, jsinvonumber, jsstatus, jsbegvalue, jsendvalue);
          });

          // by status
          $(".stc-po-status-in").change(function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jsmerchantid=$("#stc-po-merchant-in").val();
            jsponumber=$("#stc-po-number-finder-in").val();
            jsgrnnumber=$("#stc-grn-number-finder-in").val();
            jsinvonumber=$("#stc-invoice-number-finder-in").val();
            jsstatus=$(".stc-po-status-in").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            stc_filter_purchase_order(jsbegdate, jsenddate, jsmerchantid, jsponumber, jsgrnnumber, jsinvonumber, jsstatus, jsbegvalue, jsendvalue);
          });

          // paging before search
          $('body').delegate('.begbuttoninvsearch', 'click', function(e){
            e.preventDefault();
            var query = $('#invtags').val();
            begvalue=$('.begvalueinputsearch').val();
            endvalue=$('.endvalueinputsearch').val();
            if(endvalue==0){
              alert("Seriously!!!");
            }else{
              outbegvalueinputted= (+begvalue) - 20;
              outendvalueinputted= (+endvalue) - 20;
              jsbegdate=$('.begdate').val();
              jsenddate=$('.enddate').val();
              jsmerchantid=$("#stc-po-merchant-in").val();
              jsponumber=$("#stc-po-number-finder-in").val();
              jsgrnnumber=$("#stc-grn-number-finder-in").val();
              jsinvonumber=$("#stc-invoice-number-finder-in").val();
              jsstatus=$(".stc-po-status-in").val();
              jsbegvalue=outbegvalueinputted;
              jsendvalue=outendvalueinputted;
              stc_filter_purchase_order(jsbegdate, jsenddate, jsmerchantid, jsponumber, jsgrnnumber, jsinvonumber, jsstatus, jsbegvalue, jsendvalue);
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
              outbegvalueinputted= (+begvalue) + 20;
              outendvalueinputted= (+endvalue) + 20;
              jsbegdate=$('.begdate').val();
              jsenddate=$('.enddate').val();
              jsmerchantid=$("#stc-po-merchant-in").val();
              jsponumber=$("#stc-po-number-finder-in").val();
              jsgrnnumber=$("#stc-grn-number-finder-in").val();
              jsinvonumber=$("#stc-invoice-number-finder-in").val();
              jsstatus=$(".stc-po-status-in").val();
              jsbegvalue=outbegvalueinputted;
              jsendvalue=outendvalueinputted;
              stc_filter_purchase_order(jsbegdate, jsenddate, jsmerchantid, jsponumber, jsgrnnumber, jsinvonumber, jsstatus, jsbegvalue, jsendvalue);
            }
          });

          // filter function
          function stc_filter_purchase_order(jsbegdate, jsenddate, jsmerchantid, jsponumber, jsgrnnumber, jsinvonumber, jsstatus, jsbegvalue, jsendvalue){
            $.ajax({
              url     : "kattegat/ragnar_purchase.php",
              method  : "post",
              data    : {
                stcpurchaseaction:1,
                phpbegdate:jsbegdate, 
                phpenddate:jsenddate, 
                phpmerchantid:jsmerchantid, 
                phpponumber:jsponumber, 
                phpgrnnumber:jsgrnnumber,
                phpinvonumber:jsinvonumber,
                phpstatus:jsstatus,
                phpbegvalue:jsbegvalue,
                phpendvalue:jsendvalue
              },
              // dataType : 'JSON',
              success : function(data){
                // console.log(data);
                $('.stc-call-view-po-row').html(data);
              }
            });
          }

          // call item via product name
          $('body').delegate('.stc-product-finder-hit','click',function(e){
            e.preventDefault();
            search_po_pd_name=$('#stc-product-name-finder-in').val();
            if(search_po_pd_name==''){
              alert("Please Enter Product Name!!!");
            }else{
              $.ajax({
                url       : "kattegat/ragnar_purchase.php",
                method    : "post",
                data      : {
                  search_po_pd_name_in_po:search_po_pd_name
                },
                dataType  : 'JSON',
                success   : function(data){
                  // console.log(data);
                  $('.stc-call-view-po-row').html(data);
                   // $(this)[0].reset();
                }
              });
            }
          });

          // call product function
          function stc_call_product_for_grant(po_id){
            $.ajax({
              url     : "kattegat/ragnar_purchase.php",
              method  : "POST",
              data    : {
                stc_call_items_for_grant:1,
                po_id:po_id
              },
              success : function(response_items){
                $('.stc-callg-po-items').html(response_items);
              }
            });
          }

          // call products for grant permission
          $('body').delegate('.stc-grant-product-purchase-trigger', 'click', function(e){
            e.preventDefault();
            $(this).css('color', '#2EFF2E');
            $(".bd-poitem-modal-lg").modal("show");
            var po_id=$(this).attr("id");
            $('.stc-po-items-pono-show').val(po_id);
            stc_call_product_for_grant(po_id);
          });

          // change product quantity for grant the permit
          $('body').delegate('.stcgrantchangepoqty', 'click', function(e){
            e.preventDefault();
            var po_id=$('.stc-po-items-pono-show').val();
            var po_items_id=$(this).attr("id");
            var po_items_qty=$('.stcgrantqtyepoi'+po_items_id).val();
            $.ajax({
              url     : "kattegat/ragnar_purchase.php",
              method  : "POST",
              data    : {
                stc_change_items_for_grant:1,
                po_items_id:po_items_id,
                po_items_qty:po_items_qty
              },
              success : function(response_items){
                response_items=response_items.trim();
                if(response_items=="Purchase order quantity updated."){
                  alert(response_items);
                  stc_call_product_for_grant(po_id);
                }else{
                  alert(response_items);
                }
              }
            });
          });

          // delete product for grant the permit
          $('body').delegate('.deltogpoitems', 'click', function(e){
            e.preventDefault();
            var po_id=$('.stc-po-items-pono-show').val();
            var po_items_id=$(this).attr("id");
            if (confirm("Are you sure?")) {
              $.ajax({
                url     : "kattegat/ragnar_purchase.php",
                method  : "POST",
                data    : {
                  stc_delete_items_for_grant:1,
                  po_items_id:po_items_id
                },
                success : function(response_items){
                  response_items=response_items.trim();
                  if(response_items=="Purchase order item deleted."){
                    alert(response_items);
                    stc_call_product_for_grant(po_id);
                  }else{
                    alert(response_items);
                  }
                }
              });
            }
          });

          // change po status to grant
          $('body').delegate('.stc-po-granted-hit', 'click', function(e){
            e.preventDefault();
            var po_id=$('.stc-po-items-pono-show').val();
            $.ajax({
              url     : "kattegat/ragnar_purchase.php",
              method  : "POST",
              data    : {
                stc_change_po_to_grant:1,
                po_id:po_id
              },
              success : function(response_items){
                response_items=response_items.trim();
                if(response_items=="Purchase order status changed."){
                  alert(response_items);
                  $(".bd-poitem-modal-lg").modal("hide");
                }else{
                  alert(response_items);
                }
              }
            });
          });

          // change po status to cancel
          $('body').delegate('.stc-po-cancel-hit', 'click', function(e){
            e.preventDefault();
            var po_id=$('.stc-po-items-pono-show').val();
            $.ajax({
              url     : "kattegat/ragnar_purchase.php",
              method  : "POST",
              data    : {
                stc_change_po_to_cancel:1,
                po_id:po_id
              },
              success : function(response_items){
                var response=response_items.trim();
                if(response=="Purchase order status changed."){
                  alert(response_items);
                  $(".bd-poitem-modal-lg").modal("hide");
                }else{
                  alert(response_items);
                }
              }
            });
          });
        });
    </script>
    <script>
        $(document).ready(function(){
          $('#stc-po-forGRN').prop( "disabled", true);
          // call grn on perticular
          $('body').delegate('.stc-call-grn-via-grn-no', 'click', function(e){
            e.preventDefault();
            var poid=$(this).attr("id");
            var stc_grn_edit_product_id = $(this).attr("id");
            $.ajax({
                url       : "kattegat/ragnar_purchase.php",
                method    :'POST',
                data      :{
                  stc_grn_view_for_edit:1,
                  stc_grn_edit_pid:stc_grn_edit_product_id
                },
                dataType  : 'JSON',
                success:function(data){
                  // console.log(data);
                  $('.stc-grn-id-show').val(data['grn_number']);
                  $('#stcgrnnumbershow').val('GRN/'+data['grn_number']);
                  $('#grn_date_show').val(data['grn_date']);
                  $('#show_grn_po_no').val('STC/'+data['po_number']);
                  $('#show_grn_po_date').val(data['po_date']);
                  $('#show_merchant_name').val(data['merchant_name']);
                  $('#show_grn_invonumber').val(data['chalan_number']);
                  $('#show_grn_invdate').val(data['challan_date']);
                  $('.grn_show_items_div').html(data['line_items_grn']);
                  $('.bd-grn-modal-lg').modal("show");
                }
            });
          });

          // call po using vendor
          $('body').delegate('#stc-venodr-forGRN', 'change', function(e){
            e.preventDefault();
            var vendor_id=$(this).val();
            $.ajax({
                url       : "kattegat/ragnar_purchase.php",
                method    :'POST',
                data      :{
                  stc_call_po_from_grn_act:1,
                  vendor_id:vendor_id
                },
                dataType  : 'JSON',
                success:function(data){
                  // console.log(data);
                  $('.stc-po-forGRN').html(data);
                  $('#stc-po-forGRN').prop( "disabled", false);
                }
            });
          });

          // call po date using po id
          $('body').delegate('#stc-po-forGRN', 'change', function(e){
            e.preventDefault();
            var po_id=$(this).val();
            $.ajax({
                url       : "kattegat/ragnar_purchase.php",
                method    :'POST',
                data      :{
                  stc_call_po_date_from_grn_act:1,
                  po_id:po_id
                },
                dataType  : 'JSON',
                success:function(data){
                  // console.log(data);
                  $('.stc-po-date').val(data);
                }
            });
          });

          // check invoice existance
          $('body').delegate('.stc-invo-check-product-show', 'click', function(e){
            e.preventDefault();
            var invoice_no=$('.stc-invo-number').val();
            var invoice_date=$('.stc-invo-date').val();
            var vendor_id=$("#stc-venodr-forGRN").val();
            var po_no=$('#stc-po-forGRN').val();
            if(
                (invoice_no.length)!=0 && 
                invoice_no!="NA" && 
                vendor_id!="NA" && 
                po_no!="NA" && 
                (invoice_date.length)!=0 && 
                (vendor_id.length)!=0
              ){
              $('#stc-venodr-forGRN').prop( "disabled", true);
              $.ajax({
                  url       : "kattegat/ragnar_purchase.php",
                  method    : 'POST',
                  data      : {
                    stc_find_invo_toshow_items:1,
                    invoice_no:invoice_no,
                    invoice_date:invoice_date,
                    vendor_id:vendor_id,
                    po_no:po_no
                  },
                  // dataType  : 'JSON',
                  success:function(data){
                    $('.stc-call-grn-show').html(data);
                  }
              });
            }else{
              alert("Please Select Merchant And PO to Check Invoice Existance!!!");
            }
          });

          // show_grn_cart();
          function show_grn_cart() {
            $.ajax({  
              url       : "kattegat/ragnar_purchase.php", 
              method    : "POST",
              data      : {  
                  comon_grn_sess:1  
              },  
              dataType  : 'JSON',
              success:function(data){
                // console.log(data);    
                $('.stc-grn-cart-items').html(data['orderTable']);                    
              }  
            });
          }

          // show grn items cart
          $('body').delegate('.stc-show-grn-items-cart', 'click', function(e){
            e.preventDefault();
            show_grn_cart();
            $('.bd-grncart-modal-lg').modal("show");
          });

          // add to grn cart
          // For Doing GRN Product Show
          $('body').delegate('.addtogrn', 'click', function(e){
            e.preventDefault();
            var product_id = $(this).attr("id");
            var product_qty = $('.stcqtygrnt'+product_id).val();
            var product_price = $('.stcpricegrnt'+product_id).val();
            var pro_order_id = $('.stcorderid'+product_id).val();
            if(product_qty == 0){
              alert("Invalid Quantity!!!");
            }else{
              $.ajax({  
                url       : "kattegat/ragnar_purchase.php", 
                method    : "POST",  
                data      : {
                  purchase_pro_id:product_id,
                  product_qty:product_qty,
                  product_price:product_price,
                  product_order_id:pro_order_id,
                  add_grn_sess_action:1
                },  
                // dataType: `JSON`,
                success   : function(data){ 
                 show_grn_cart();
                 // console.log(data);
                 alert(data);
                }  
              }); 
            } 
          }); 

          $('body').delegate('.stcchangegrnsessqtybtn', 'click', function(e){
            e.preventDefault();
            var product_id = $(this).attr("id");
            var product_qty = $('.stcchangegrnsessqty'+product_id).val();
            $.ajax({  
              url       : "kattegat/ragnar_purchase.php", 
              method    : "POST",  
              data      : {
                purchase_pro_id:product_id,
                product_qty:product_qty,
                change_grn_sess_qty_action:1
              },  
              // dataType: `JSON`,
              success   : function(data){ 
               show_grn_cart();
               // console.log(data);
               alert(data);
              }  
            });  
          }); 

          $('body').delegate('.stcdelgrnitems', 'click', function(e){
            e.preventDefault();
            var itemsid=$(this).attr("id");
            $.ajax({  
              url       : "kattegat/ragnar_purchase.php", 
              method    : "POST",  
              data      : {
                itemsid:itemsid,
                stc_grn_items_del_cart_hit:1
              },  
              // dataType: `JSON`,
              success   : function(data){ 
               show_grn_cart();
               // console.log(data);
               alert(data);
              }  
            });  
          });

          // charges packing & forwarding & freight
          $('body').delegate('.stcgrnfcpo', 'click', function(e){
            e.preventDefault();
            var freightcharge=$('.stcfc').val();
            var packingandforwardingcharge=$('.stcpf').val();
            var othercharge=$('.stcoc').val();
            var grand_total=$('#stc_grand_offset_table_value').val();
            $.ajax({  
              url       : "kattegat/ragnar_purchase.php", 
              method    : "POST",  
              data      : {
                freightcharge:freightcharge,
                packingandforwardingcharge:packingandforwardingcharge,
                othercharge:othercharge,
                grand_total:grand_total,
                do_plus_minus_on_grn:1
              },  
              // dataType: `JSON`,
              success  : function(data){ 
                $('#stc_final_grn_value').html(data);
              }  
            });     
          });

          // save grn to db
          $('body').delegate('.stcsavegrn', 'click', function(e){
            e.preventDefault();
            var order_id =$('#stc-po-forGRN').val();
            var order_invodate = $('.stc-invo-date').val();
            var order_invonumber = $('.stc-invo-number').val();
            var order_stcfc = $('.stcfc').val();
            var order_stcpf = $('.stcpf').val();
            var order_stcoc = $('.stcoc').val();
            var notesgrn = $('.notesgrn').val();
            if(
                (order_invonumber.length)!=0 && 
                order_invonumber!="NA" && 
                order_id!="NA" && 
                (order_invodate.length)!=0
              ){
              $.ajax({  
                url       : "kattegat/ragnar_purchase.php",
                method    : "POST",  
                data      : {
                  grn_order_id:order_id,
                  grn_invodate:order_invodate,
                  grn_invonumber:order_invonumber,
                  grn_stcfc:order_stcfc,
                  grn_stcpf:order_stcpf,
                  grn_scoc:order_stcoc,
                  grn_notes:notesgrn,
                  save_grn_action:1
                },  
                // dataType: `JSON`,
                success   : function(data){ 
                  // console.log(data);
                  alert(data);
                  window.location.reload();
                }  
              });
            }else{
              alert("Please Fill All Field Before Save GRN!!!");
            }  
          });

          // set to electronics inventory
          $('body').delegate('.stc-stocking-send-hit', 'click', function(e){
            e.preventDefault();
            var refr_id=$('.stc-grn-id-show').val();
            $.ajax({  
              url       : "kattegat/ragnar_purchase.php", 
              method    : "POST",  
              data      : {
                stc_stocking_send_hit:1,
                refr_id:refr_id
              },  
              success  : function(data){ 
                alert(data);
              }  
            });
          });
        });
    </script>
</body>
</html>
<div class="modal fade bd-grn-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">GRN Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-xl-6 col-md-6 col-sm-6">
                  <div class="card-border mb-3 card card-body border-success">
                    <h5
                      for=""
                      >GRN No
                    </h5>
                    <input type="hidden" class="stc-grn-id-show">
                    <input
                      id="stcgrnnumbershow"
                      type="text"
                      placeholder="GRN Number"
                      class="form-control validate"
                      Value=""
                      disabled
                    />
                  </div>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-6">
                  <div class="card-border mb-3 card card-body border-success">
                    <h5
                      for=""
                      >GRN Date
                    </h5>
                    <input
                      id="grn_date_show"
                      type="text"
                      class="form-control validate"
                      data-large-mode="true"
                      disabled
                    />
                  </div>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-6">
                  <div class="card-border mb-3 card card-body border-success">
                    <h5
                      for=""
                      >PO No
                    </h5>
                    <input
                      id="show_grn_po_no"
                      type="text"
                      placeholder="Purchase order Number"
                      class="form-control validate"
                      disabled
                    />
                  </div>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-6">
                  <div class="card-border mb-3 card card-body border-success">
                    <h5
                      for=""
                      >PO Date
                    </h5>
                    <input
                      id="show_grn_po_date"
                      type="text"
                      class="form-control validate"
                      data-large-mode="true"
                      disabled
                    />
                  </div>
                </div>
                <div class="col-xl-12 col-md-12 col-sm-12">
                  <div class="card-border mb-3 card card-body border-success">
                    <h5
                      for=""
                      >Merchant Name
                    </h5>
                    <input
                      id="show_merchant_name"
                      type="text"
                      placeholder="Merchant Name"
                      class="form-control validate"
                      disabled
                    />
                  </div>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-6">
                  <div class="card-border mb-3 card card-body border-success">
                    <h5
                      for=""
                      >Invoice/Challan Number
                    </h5>
                    <input
                      id="show_grn_invonumber"
                      type="text"
                      placeholder="Challan/Invoice Number"
                      class="form-control validate stcinvonumber"
                      data-large-mode="true"
                      value=""
                      disabled
                    />
                  </div>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-6">
                  <div class="card-border mb-3 card card-body border-success">
                    <h5
                      for=""
                      >Invoice/Challan Date
                    </h5>
                    <input
                      id="show_grn_invdate"
                      name="stcinvodate"
                      type="date"
                      placeholder="Challan Date"
                      class="form-control validate stcinvodate"
                      value=""
                      disabled
                    />
                  </div>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-12">
                  <h2 class="tm-block-title d-inline-block">Products</h2> 
                </div>

                <!-- line item table -->
                <div class="col-xl-12 col-md-12 col-sm-12 grn_show_items_div">
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <div class="row">
                <div class="col-xl-6 col-md-6 col-sm-6">
                  <button type="button" class="btn btn-success stc-stocking-send-hit">Stocking</button>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-6">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-grncart-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">GRN Cart Items</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12">
                  <div class="card-border mb-3 card card-body border-success stc-grn-cart-items">
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
<div class="modal fade bd-poitem-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Purchase Order Items for Grant</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12">
                  <div class="card-border mb-3 card card-body border-success">
                      <table class="table table-hover table-bordered" align="centre">
                        <thead>
                          <tr>
                            <th class='text-center' scope="col">#</th>
                            <th class='text-center' scope="col">Item Code</th>
                            <th class='text-center' scope="col">Items Desc</th>
                            <th class='text-center' scope="col">Unit</th>
                            <th class='text-center' scope="col">Change Qty</th>
                            <th class='text-center' scope="col">Price</th>
                            <th class='text-center' scope="col" width="10%">Total</th>
                            <th class='text-center' scope="col">GST</th>
                            <th class='text-center' scope="col" width="10%">Amount</th>
                            <th class='text-center' scope="col">Actions</th>
                          </tr>
                        </thead>
                        <tbody class="stc-callg-po-items">
                        </tbody>
                      </table>
                  </div>                  
                </div>
              </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" class="stc-po-items-pono-show">
                <button type="button" class="btn btn-primary stc-po-granted-hit">Approved</button>
                <button type="button" class="btn btn-danger stc-po-cancel-hit">Cancel PO</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>