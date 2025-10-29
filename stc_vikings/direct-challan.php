<?php  
// Include authentication helper
require_once 'kattegat/auth_helper.php';

// Check authentication using the new hybrid system
STCAuthHelper::checkAuth();$page_code=506;
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
    <title>Direct Challan - STC</title>
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
                                <a role="tab" class="nav-link active" id="tab-1" data-toggle="tab" href="#tab-content-1">
                                    <span>View Direct Challan</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-content-2">
                                    <span>Create Direct Challan</span>
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
                                              >View Direct Challan
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
                                                <th scope="col" width="20%">By Customer/<br>Merchant</th>
                                                <th scope="col" width="20%">By Challan Number/<br>Invoice Number</th>
                                                <th scope="col" width="20%">By Site</th>
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
                                                  <p><input type="date" value="<?php echo $effectiveDate;?>" class="form-control begdate"></p>
                                                  <p><input type="date" value="<?php echo $newDate;?>" class="form-control enddate"></p>
                                                  <p><a href="#" id="purchaseproddatefilt">
                                                    <i style="font-size: 20px;background: #0c0c1b;border-radius: 50%;padding: 5px;margin: 0;color: #77ffd3;float: right;" class="fa fa-arrow-down"></i>
                                                  </a></p>
                                                </td>
                                                <td>
                                                  <select
                                                    id="stc-challan-customer-in"
                                                    class="custom-select form-control stc-select-customer"
                                                    name="stcvendor"
                                                  >
                                                  </select><br>
                                                  <select
                                                    id="stc-challan-vendor-in"
                                                    class="custom-select form-control stc-select-vendor"
                                                    name="stcvendor"
                                                  >
                                                  </select>
                                                </td>
                                                <td>
                                                  <input 
                                                    type="number" 
                                                    id="stc-challan-number-finder" 
                                                    class="form-control"
                                                    placeholder="Challan Number" 
                                                  >                                             
                                                  <input 
                                                    type="text" 
                                                    id="stc-challan-invoiceno-finder" 
                                                    class="form-control"
                                                    placeholder="Invoice Number" 
                                                  >
                                                </td>
                                                <td>
                                                  <input 
                                                    type="text" 
                                                    id="stc-challan-sitename-finder" 
                                                    class="form-control"
                                                    placeholder="Site Name" 
                                                  >
                                                </td>
                                                <td>
                                                  <select 
                                                    class="custom-select form-control stc-challan-status-in"
                                                    >
                                                    <option value="NA">Select Status</option>
                                                    <option value="billed">Billed</option>
                                                    <option value="direct_sale">Direct Sale</option>
                                                  </select>
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
                                          <table class="table table-hover table-bordered table-dark">
                                            <thead>
                                              <tr>
                                                <th scope="col">Customer Name<br>Merchant Name</th>
                                                <th scope="col" width="10%">DC Date<br>DC No</th>
                                                <th scope="col">Merchant Invoice Date<br>Merchant Invoice No</th>
                                                <th scope="col">Way Bill No</th> 
                                                <th scope="col">Customer Site</th> 
                                                <th scope="col" width="10%">Sold <br>Basic<br>Total</th>
                                                <th scope="col" width="10%">Purchase<br>Basic<br>Total</th>
                                                <th scope="col">Paid Amount</th>
                                                <th scope="col">Status</th>                         
                                                <th scope="col">Action</th>
                                              </tr>
                                            </thead>
                                            <tbody class="stc-call-view-challan-row">
                                              <tr>
                                                <td colspan="8" align="center">Search here</td>
                                              </tr>
                                            </tbody>
                                          </table>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                            </div>
                            <div class="tab-pane tabs-animation fade" id="tab-content-2" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >Create Direct Challan
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <form action="" class="stc-add-direct-challan-form">
                                  <div class="row stc-sale-challan-row">
                                    <div class="col-xl-6 col-md-6 col-sm-12">     
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="name"
                                          >Challan Number
                                        </h5>
                                        <input
                                          id="gtonumbershow"
                                          name="stcmername"
                                          type="text"
                                          placeholder="Direct Challan Number"
                                          class="form-control validate"
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
                                          data-large-mode="true"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="name"
                                          >Customer Name
                                        </h5>
                                        <select
                                          id="stc_customer_sale_product"
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
                                          name="saleordercustordernumber"
                                          type="text"
                                          class="form-control validate saleordercustordernumber"
                                          placeholder="Customer Order Number"
                                          value="VERBAL"
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
                                          id="datepicke1r"
                                          name="saleordercustorderdate"
                                          type="date"
                                          min="2001-01-01" 
                                          max="2050-12-31"
                                          placeholder="Customer Order Date"
                                          class="form-control validate saleordercustorderdate"
                                          value="<?php echo date("Y-m-d");?>"
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
                                          name="stceditsalebillinvonumber"
                                          type="text"
                                          placeholder="Challan/Invoice Number"
                                          class="form-control validate stceditsalebillinvonumber"
                                          data-large-mode="true"
                                          value=""
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
                                          name="stceditsalebillinvodate"
                                          type="date"
                                          min="2001-01-01" 
                                          max="2050-12-31"
                                          placeholder="Challan Date"
                                          class="form-control validate stceditsalebillinvodate"
                                          value="<?php echo date("Y-m-d");?>"
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
                                          name="saleorderwaybillno"
                                          type="text"
                                          class="form-control validate saleorderwaybillno"
                                          placeholder="Way Bill No"
                                          value="NA"
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
                                          name="saleorderlrno"
                                          type="text"
                                          class="form-control validate saleorderlrno"
                                          placeholder="LR No"
                                          value="NA"
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
                                          name="saleorderdatesupply"
                                          type="text"
                                          class="form-control validate saleorderdatesupply"
                                          placeholder="Date of Supply"
                                          value="NA"
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
                                          name="saleorderplacesupply"
                                          type="text"
                                          class="form-control validate saleorderplacesupply"
                                          placeholder="Place of Supply"
                                          value="NA"
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
                                          name="saleorderordrefrence"
                                          type="text"
                                          class="form-control validate saleorderordrefrence"
                                          placeholder="Customer Refrence"
                                          value="NA"
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
                                          name="saleordercustsitename"
                                          type="text"
                                          class="form-control validate saleordercustsitename"
                                          placeholder="Site Name"
                                          value="NA"
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
                                          name="saleordercustcontperson"
                                          type="text"
                                          class="form-control validate saleordercustcontperson"
                                          placeholder="Contact Person"
                                          value="MOHAMMAD RAZIULLAH"
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
                                          name="saleordercustcontnumber"
                                          type="text"
                                          class="form-control validate saleordercustcontnumber"
                                          placeholder="Contact Number"
                                          value="9204364798"
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
                                          id="stcsoshipaddress"
                                          placeholder="Shipping Address"
                                          required
                                        >C/o Majesty, 79 A Block Dhatkidih PO-BistupurJamshedpur-831001, Jharkhand</textarea>
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
                                          id="stcsotandc"
                                          placeholder="Terms & Condition"
                                          required
                                        ></textarea>
                                      </div>
                                    </div>
                                    <!-- product search -->
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
                                                  <a 
                                                    style="
                                                      font-size: 20px;
                                                      background: yellow;
                                                      border-radius: 50%;
                                                      padding: 5px;
                                                      margin: 0;
                                                    " 
                                                    href="#" 
                                                    class="upward"
                                                  ><i class="fas fa-arrow-up"></i>
                                                  </a>
                                                  <a 
                                                    style="
                                                      font-size: 20px;
                                                      background: yellow;
                                                      border-radius: 50%;
                                                      padding: 5px;
                                                      margin: 0;
                                                      display: none;
                                                    " 
                                                    href="#" 
                                                    class="downward"
                                                  ><i class="fas fa-arrow-down"></i>
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
                                    <div class="col-xl-12 col-md-12 col-sm-12 ">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <table class="table table-hover table-bordered" align="centre">
                                          <thead>
                                            <tr>
                                              <th scope="col">#</th>
                                              <th scope="col">Item Code</th>
                                              <th scope="col" style="width: 20%;">Items</th>
                                              <th scope="col">HSN Code</th> 
                                              <th scope="col">Unit</th>
                                              <th scope="col">Qty</th>                         
                                              <th scope="col">Price</th>
                                              <th scope="col" width="10%">Amount</th>
                                              <th scope="col">GST</th>
                                              <th scope="col" width="10%">Amount</th>
                                              <th scope="col">Action</th>
                                            </tr>
                                          </thead>
                                          <tbody class="stc-direct-challan-item-cart">
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
                                          id="stcpdnotes"
                                          placeholder="Notes"
                                          required
                                        ></textarea>
                                      </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <button type="submit" class="btn btn-primary btn-block text-uppercase stcsavedirectchalan">Save</button>
                                      </div>
                                    </div>
                                  </div>
                                </form>
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
              url     : "kattegat/ragnar_sale.php",
              method  : "post",
              data    : {
                stcdirectcaction:1,
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

          // show direct challan items sesson cart
          direct_challan_cart();
          function direct_challan_cart(){
            $.ajax({
              url       : "kattegat/ragnar_sale.php",
              method    : 'POST',
              data      : {stc_show_direct_sale_sess:1},
              dataType  : 'JSON',
              success   : function(data){
                // console.log(data);
                  $('.stc-direct-challan-item-cart').html(data["orderTable"]);
              }
            });
          }

          // add cart to direct challan session
          $('body').delegate('.add_to_direct_cart','click',function(e){
            e.preventDefault();
            var product_id = $(this).attr("id");  
            var product_name = $('#stcpopdname'+product_id).val(); 
            var product_unit = $('#stcpopdunit'+product_id).val();  
            var product_quantity = $('#stcpoqty'+product_id).val();  
            var product_price = $('#stcpdprice'+product_id).val();
            var product_saleperc = $('#stcpdsaleperc'+product_id).val();
            var product_hsncode = $('#stcpopdhsncode'+product_id).val();
            var product_gst = $('#stcpopdgst'+product_id).val();
            var add_to_sale_cart="addsalecart";
            $.ajax({  
              url         : "kattegat/ragnar_sale.php",
              method      : "POST",
              data        : {  
                  product_id:product_id,
                  product_name:product_name,  
                  product_unit:product_unit,
                  product_quantity:product_quantity,
                  product_price:product_price,
                  product_sale_percent:product_saleperc,
                  product_hsncode:product_hsncode,
                  product_gst:product_gst,
                  add_dc:add_to_sale_cart  
              },  
              success     : function(data){
                direct_challan_cart();
                alert(data);
                // console.log(data);                              
              }  
            });      
          });

          // quantity change direct challan session
          $(document).on('click', '.stcqtydc', function(e){  
            e.preventDefault();
              var product_id = $(this).data("product_id");  
              var quantity = $('#stcqtypot'+product_id).val();   
              var action = "quantity_change"; 
              if(quantity != ''){  
                $.ajax({  
                    url         : "kattegat/ragnar_sale.php",
                    method      : "POST",  
                    data        : {
                       product_id:product_id, 
                       quantity:quantity, 
                       sale_quantity_action:action},  
                    success     : function(data){ 
                     direct_challan_cart();
                     // console.log(data);
                    }  
                });  
              } 
          }); 

          // Rate change direct challan session
          $(document).on('click', '.stcratedc', function(e){  
            e.preventDefault();
              var product_id = $(this).data("product_id");  
              var price = $('#stcratepot'+product_id).val();
              var action = "rate_change"; 
              if(price != ''){  
                $.ajax({  
                  url         : "kattegat/ragnar_sale.php",
                  method      : "POST",  
                  data        : {
                    product_id:product_id, 
                    price:price, 
                    stc_price_action:action
                  },  
                  success:function(data){ 
                   direct_challan_cart();
                   // console.log(data);
                  }  
                });  
              }
          });

          // delete from cart purchase product session
          $('body').delegate('.stcdeldcbtn','click',function(e){
            e.preventDefault();
            var product_id = $(this).attr("id");
              if(confirm("Are you sure you want to remove this product?")){   
                $.ajax({  
                  url         : "kattegat/ragnar_sale.php",
                  method      : "POST",
                  data        : {  
                      product_id:product_id,
                      stcdelsalelinei:1  
                  },  
                  success     : function(data){  
                    direct_challan_cart();
                    alert(data);                        
                  }  
                });  
              }         
          });

          // calculate charges to sale order
          $(document).on('click', '.stcdcc', function(e){  
            e.preventDefault();
            var freightcharge=$('.stcfc').val();
            var packingandforwardingcharge=$('.stcpf').val();
            var grand_total=$('#stc_grand_offset_table_value').val();
            $.ajax({  
              url         : "kattegat/ragnar_sale.php",
              method      : "POST",  
              data        : {
                freightcharge:freightcharge,
                packingandforwardingcharge:packingandforwardingcharge,
                grand_total:grand_total,
                dc_do_plus_minus_on_sale:1
              },  
              // dataType: `JSON`,
              success     : function(data){ 
                $('#stc_final_sale_value').html(data);
              }  
            });     
          });

          // save sale order to db
          $(document).on('click', '.stcsavedirectchalan', function(e){
            e.preventDefault();
            var customer_id           =       $('#stc_customer_sale_product').val();
            var order_invodate        =       $('.saleordercustorderdate').val();
            var order_invonumber      =       $('.saleordercustordernumber').val();
            var order_mername         =       $('#stceditsale_dc_merchant_name').val();
            var order_billinvono      =       $('.stceditsalebillinvonumber').val();
            var order_billinvodate    =       $('.stceditsalebillinvodate').val();
            var order_waybillno       =       $('.saleorderwaybillno').val();
            var order_lrno            =       $('.saleorderlrno').val();
            var order_supplydate      =       $('.saleorderdatesupply').val();
            var order_supplyplace     =       $('.saleorderplacesupply').val();
            var order_refrence        =       $('.saleorderordrefrence').val();
            var order_sitename        =       $('.saleordercustsitename').val();
            var order_contperson      =       $('.saleordercustcontperson').val();
            var order_contnumber      =       $('.saleordercustcontnumber').val();
            var order_shipaddress     =       $('#stcsoshipaddress').val();
            var order_stcfc           =       $('.stcfc').val();
            var order_stcpf           =       $('.stcpf').val();
            var order_notes           =       $('#stcpdnotes').val();
            var order_tandc           =       $('#stcsotandc').val();     
            $.ajax({  
              url         : "kattegat/ragnar_sale.php",
              method      : "POST",  
              data        : {
                customer_id             :     customer_id,
                sale_custorderdate      :     order_invodate,
                sale_custordernumber    :     order_invonumber,
                sale_mername            :     order_mername,
                sale_billinvono         :     order_billinvono,
                sale_billinvodate       :     order_billinvodate,
                sale_waybillno          :     order_waybillno,
                sale_lrno               :     order_lrno,
                sale_supplydate         :     order_supplydate,
                sale_supplyplace        :     order_supplyplace,
                sale_refrence           :     order_refrence,
                sale_sitename           :     order_sitename,
                sale_contperson         :     order_contperson,
                sale_contnumber         :     order_contnumber,
                sale_shipaddress        :     order_shipaddress,
                sale_stcfc              :     order_stcfc,
                sale_stcpf              :     order_stcpf,
                order_notes             :     order_notes,
                order_tandc             :     order_tandc,
                save_dc_action          :     1
              },
              // dataType: `JSON`,
              success   : function(data){
               // console.log(data);
                alert(data);
                $('.stc-add-direct-challan-form')[0].reset();
                direct_challan_cart();
                stc_call_direct_challan();
              }
            });
          });
        });
    </script>
    <script>
        $(document).ready(function(){
          var jsbegdate='';
          var jsenddate='';
          var jscustomerid='';
          var jsmerchantid='';
          var jschallannumber='';
          var jschallaninvoiceno='';
          var jsstatus='';
          var jsbegvalue='';
          var jsendvalue='';
          // filter challan
          // by date
          $('body').delegate('#purchaseproddatefilt', 'click', function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jscustomerid=$("#stc-challan-customer-in").val();
            jsmerchantid=$("#stc-challan-vendor-in").val();
            jschallannumber=$("#stc-challan-number-finder").val();
            jschallaninvoiceno=$("#stc-challan-invoiceno-finder").val();
            jschallansitename=$("#stc-challan-sitename-finder").val();
            jsstatus=$(".stc-challan-status-in").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            stc_filter_direct_challan(jsbegdate, jsenddate, jscustomerid, jsmerchantid, jschallannumber, jschallaninvoiceno, jschallansitename, jsstatus, jsbegvalue, jsendvalue);
          });

          // by customer id
          $("#stc-challan-customer-in").change(function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jscustomerid=$("#stc-challan-customer-in").val();
            jsmerchantid=$("#stc-challan-vendor-in").val();
            jschallannumber=$("#stc-challan-number-finder").val();
            jschallaninvoiceno=$("#stc-challan-invoiceno-finder").val();
            jschallansitename=$("#stc-challan-sitename-finder").val();
            jsstatus=$(".stc-challan-status-in").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            stc_filter_direct_challan(jsbegdate, jsenddate, jscustomerid, jsmerchantid, jschallannumber, jschallaninvoiceno, jschallansitename, jsstatus, jsbegvalue, jsendvalue);
          });

          // by Merchant id
          $("#stc-challan-vendor-in").change(function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jscustomerid=$("#stc-challan-customer-in").val();
            jsmerchantid=$("#stc-challan-vendor-in").val();
            jschallannumber=$("#stc-challan-number-finder").val();
            jschallaninvoiceno=$("#stc-challan-invoiceno-finder").val();
            jschallansitename=$("#stc-challan-sitename-finder").val();
            jsstatus=$(".stc-challan-status-in").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            stc_filter_direct_challan(jsbegdate, jsenddate, jscustomerid, jsmerchantid, jschallannumber, jschallaninvoiceno, jschallansitename, jsstatus, jsbegvalue, jsendvalue);
          });

          // by challan number
          $("#stc-challan-number-finder").on('keyup', function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jscustomerid=$("#stc-challan-customer-in").val();
            jsmerchantid=$("#stc-challan-vendor-in").val();
            jschallannumber=$("#stc-challan-number-finder").val();
            jschallaninvoiceno=$("#stc-challan-invoiceno-finder").val();
            jschallansitename=$("#stc-challan-sitename-finder").val();
            jsstatus=$(".stc-challan-status-in").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            stc_filter_direct_challan(jsbegdate, jsenddate, jscustomerid, jsmerchantid, jschallannumber, jschallaninvoiceno, jschallansitename, jsstatus, jsbegvalue, jsendvalue);
          });

          // by invoice number
          $("#stc-challan-invoiceno-finder").on('keyup', function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jscustomerid=$("#stc-challan-customer-in").val();
            jsmerchantid=$("#stc-challan-vendor-in").val();
            jschallannumber=$("#stc-challan-number-finder").val();
            jschallaninvoiceno=$("#stc-challan-invoiceno-finder").val();
            jschallansitename=$("#stc-challan-sitename-finder").val();
            jsstatus=$(".stc-challan-status-in").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            stc_filter_direct_challan(jsbegdate, jsenddate, jscustomerid, jsmerchantid, jschallannumber, jschallaninvoiceno, jschallansitename, jsstatus, jsbegvalue, jsendvalue);
          });

          // by sitenemae number
          $("#stc-challan-sitename-finder").on('keyup', function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jscustomerid=$("#stc-challan-customer-in").val();
            jsmerchantid=$("#stc-challan-vendor-in").val();
            jschallannumber=$("#stc-challan-number-finder").val();
            jschallaninvoiceno=$("#stc-challan-invoiceno-finder").val();
            jschallansitename=$("#stc-challan-sitename-finder").val();
            jsstatus=$(".stc-challan-status-in").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            stc_filter_direct_challan(jsbegdate, jsenddate, jscustomerid, jsmerchantid, jschallannumber, jschallaninvoiceno, jschallansitename, jsstatus, jsbegvalue, jsendvalue);
          });

          // by status
          $(".stc-challan-status-in").change(function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jscustomerid=$("#stc-challan-customer-in").val();
            jsmerchantid=$("#stc-challan-vendor-in").val();
            jschallannumber=$("#stc-challan-number-finder").val();
            jschallaninvoiceno=$("#stc-challan-invoiceno-finder").val();
            jschallansitename=$("#stc-challan-sitename-finder").val();
            jsstatus=$(".stc-challan-status-in").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            stc_filter_direct_challan(jsbegdate, jsenddate, jscustomerid, jsmerchantid, jschallannumber, jschallaninvoiceno, jschallansitename, jsstatus, jsbegvalue, jsendvalue);
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
              jscustomerid=$("#stc-challan-customer-in").val();
              jsmerchantid=$("#stc-challan-vendor-in").val();
              jschallannumber=$("#stc-challan-number-finder").val();
              jschallaninvoiceno=$("#stc-challan-invoiceno-finder").val();
              jschallansitename=$("#stc-challan-sitename-finder").val();
              jsstatus=$(".stc-challan-status-in").val();
              jsbegvalue=outbegvalueinputted;
              jsendvalue=outendvalueinputted;
              stc_filter_direct_challan(jsbegdate, jsenddate, jscustomerid, jsmerchantid, jschallannumber, jschallaninvoiceno, jschallansitename, jsstatus, jsbegvalue, jsendvalue);
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
              jscustomerid=$("#stc-challan-customer-in").val();
              jsmerchantid=$("#stc-challan-vendor-in").val();
              jschallannumber=$("#stc-challan-number-finder").val();
              jschallaninvoiceno=$("#stc-challan-invoiceno-finder").val();
              jschallansitename=$("#stc-challan-sitename-finder").val();
              jsstatus=$(".stc-challan-status-in").val();
              jsbegvalue=outbegvalueinputted;
              jsendvalue=outendvalueinputted;
              stc_filter_direct_challan(jsbegdate, jsenddate, jscustomerid, jsmerchantid, jschallannumber, jschallaninvoiceno, jschallansitename, jsstatus, jsbegvalue, jsendvalue);
            }
          });

          // filter function
          function stc_filter_direct_challan(jsbegdate, jsenddate, jscustomerid, jsmerchantid, jschallannumber, jschallaninvoiceno, jschallansitename, jsstatus, jsbegvalue, jsendvalue){
            $.ajax({
              url     : "kattegat/ragnar_sale.php",
              method  : "post",
              data    : {
                stcdcaction:1,
                phpbegdate:jsbegdate, 
                phpenddate:jsenddate, 
                phpcustomerid:jscustomerid, 
                phpmerchantid:jsmerchantid, 
                phpchallannumber:jschallannumber,
                phpchallaninvoiceno:jschallaninvoiceno,
                phpchallansitename:jschallansitename, 
                phpstatus:jsstatus,
                phpbegvalue:jsbegvalue,
                phpendvalue:jsendvalue
              },
              // dataType : 'JSON',
              success : function(data){
                // console.log(data);
                $('.stc-call-view-challan-row').html(data);
              }
            });
          }
        });
    </script>
</body>
</html>