<?php  
// Include authentication helper
require_once 'kattegat/auth_helper.php';

// Check authentication using the new hybrid system
STCAuthHelper::checkAuth();$page_code=512;
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
    <title>Adjustment Tax Invoice - STC</title>
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
      @media print{
        .close-tag{
          /*visibility: hidden; */
          display: none !important;
        }
      }
    </style>
</head>
<body>
  <div class="modal fade" id="invo-modal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content" style="width: 1350px;left: -270px;">
        <div class="modal-header">
          <h4 class="modal-title">Edit Invoice Challan</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-12 col-md-12 invo-details-out">
                                
            </div>                
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>        
    </div>
  </div>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header ">
        <?php include_once("header-nav.php");?>
        <div class="app-main">
            <?php include_once("sidebar-nav.php");?>                   
            <div class="app-main__outer">
                    <div class="app-main__inner"> 
                        <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav close-tag">
                            <li class="nav-item">
                                <a role="tab" class="nav-link active" id="tab-1" data-toggle="tab" href="#tab-content-1">
                                    <span>View Adjustment Tax Invoice</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-content-2">
                                    <span>Create Adjustment Tax Invoice</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-3" data-toggle="tab" href="#tab-content-3">
                                    <span>Billed Annexture</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane tabs-animation fade  active close-tag" id="tab-content-1" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >View Adjustment Tax Invoice
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
                                                <th scope="col" width="20%">By Customer</th>
                                                <th scope="col" width="20%">By Invoice ID</th>
                                                <th scope="col" width="20%">Invoice Number</th>
                                                <th scope="col" width="20%">By Site</th>
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
                                                  </select>
                                                </td>
                                                <td>
                                                  <input 
                                                    type="number" 
                                                    id="stc-invoice-id-finder" 
                                                    class="form-control"
                                                    placeholder="Invoice ID" 
                                                  >
                                                </td>
                                                <td>
                                                  <input 
                                                    type="text" 
                                                    id="stc-invoice-number-finder" 
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
                                                <th scope="col" width="15%">Customer Name</th> 
                                                <th scope="col">Invoice Date<br>Invoice No</th>
                                                <th scope="col">Invoice Id</th>
                                                <th scope="col">Challan Number</th>
                                                <th scope="col">Way Bill No</th> 
                                                <th scope="col">Sitename</th>
                                                <th scope="col">Challan Basic</th>                                 
                                                <th scope="col">Invoice Basic Value<br>Total Value</th>                               
                                                <th scope="col">Sale Order Keys</th>
                                                <th scope="col">Line Items</th>
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
                            <div class="tab-pane tabs-animation fade close-tag" id="tab-content-2" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >Create Adjustment Tax Invoice
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                    $date = date("d-m-Y");
                                    $newDate = date('Y-m-d', strtotime($date)); 
                                    $effectiveDate = date('Y-m-d', strtotime("-1 months", strtotime($date)));
                                ?> 
                                <form action="" class="additional-sale-order-create-response-sale-product-form">
                                  <div class="row stc-sale-challan-row">
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="name"
                                          >Sale Order Number
                                        </h5>
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
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for=""
                                          >Sale Order Date
                                        </h5>
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
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="name"
                                          >Customer Name
                                        </h5>
                                        <select
                                          id="stc_customer_sale_product_invoice"
                                          class="custom-select stc-select-customer"
                                        >
                                        </select>
                                      </div>
                                    </div> 
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="name"
                                          >Customer Site/Reference
                                        </h5>
                                        <select
                                          id="stc_customer_sale_site_refr"
                                          class="custom-select stc_customer_sale_site_refr"
                                        ><option value='0' selected>Please select customer first</option>
                                        </select>
                                      </div>
                                    </div> 
                                    <!-- search product display -->
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <div class="row stc-call-view-invoice-sale-product-row">
                                        </div>
                                      </div>
                                    </div>

                                    <!-- line item table -->
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success invoflase">
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
                                          id="stcinvonotes"
                                          placeholder="Notes"
                                          required
                                        ></textarea>
                                      </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <a type="submit" class="btn btn-primary btn-block text-uppercase stcsaveinvo">Save</a>
                                      </div>
                                    </div>
                                  </div>
                                </form>
                            </div>
                            <div class="tab-pane tabs-animation fade show" id="tab-content-3" role="tabpanel">
                                <div class="row close-tag">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >Call Billed Annexture
                                            </h5>
                                        </div>
                                    </div>
                                </div>  
                                <form action="" class="additional-sale-order-create-response-sale-product-form close-tag">
                                  <div class="row stc-sale-challan-row">
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="name"
                                          >Bill Annexture From
                                        </h5>
                                        <input
                                          id="datepicke1r"
                                          name="stcannexbegdate"
                                          type="date"
                                          min="2001-01-01" 
                                          max="2050-12-31"
                                          placeholder="Annexture Date"
                                          class="form-control validate stcannexbegdate"
                                          value="<?php echo $effectiveDate;?>"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for=""
                                          >Bill Annexture To
                                        </h5>
                                        <input
                                          id="datepicke1r"
                                          name="stcannexenddate"
                                          type="date"
                                          min="2001-01-01" 
                                          max="2050-12-31"
                                          placeholder="Annexture Date"
                                          class="form-control validate stcannexenddate"
                                          value="<?php echo $newDate;?>"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <a type="submit" class="btn btn-primary btn-block text-uppercase stcannexfindsite">Find Site</a>
                                      </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="name"
                                          >Customer Site/Reference
                                        </h5>
                                        <select
                                          id="stc-annexture-sitename"
                                          class="custom-select stc-annexture-sitename"
                                        ><option value='NA' selected>Please select Site first</option>
                                        </select>
                                      </div>
                                    </div> 
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <a type="submit" class="btn btn-primary btn-block text-uppercase stcannexturegetdetails">Find</a>
                                      </div>
                                    </div>
                                  </div>
                                </form>
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12 close-tag">
                                        <div class="card-border mb-3 card card-body border-success">
                                          <button id="printAnnexture" class="btn btn-info"><i class="fas fa-print"></i> Print</button>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                          <h3 class="text-center">
                                            <b>STC Associates Invoice Annexture</b>
                                          </h4>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                          <p class="text-center site-show">
                                          </p>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success stc-annexture-data-show">                                            
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
        $(document).ready(function(){
          // call customer for sale
          stc_call_customer();
          function stc_call_customer(){
            $.ajax({
              url       : "kattegat/ragnar_invoice.php",
              method    : "post",
              data      : {friday_customer:1},
              dataType  : 'JSON',
              success   : function(data){
                // console.log(data);
                $('#expire_date').val(data['time']);
                $('.stc-select-customer').html(data['customer']);
              }
            });
          }

          // load invoices session
          show_invo_cart();
          function show_invo_cart() {
            $.ajax({  
              url       : "kattegat/ragnar_invoice.php", 
              method    : "POST",
              data      : {  
                  comon_invo_sess:1  
              },  
              // dataType: 'JSON',
              success   : function(data){
                $('.invoflase').html(data);                    
              }  
            });
          }

          // load challan on change cust
          $('#stc_customer_sale_product_invoice').on('change', function(e){
            e.preventDefault();
            var js_cust_id=$(this).val();
            $.ajax({
              url       : "kattegat/ragnar_invoice.php", 
              method    : "post",
              data      : {
                call_site_on_choose_customer:1,
                customer_id:js_cust_id
              },
              // dataType : 'JSON',
              success   : function(data){
                // console.log(data);
                $('.stc_customer_sale_site_refr').html(data);
                // $('.stc-call-view-invoice-sale-product-row').html(data);
              }
            });
          });

          // load challan on change site
          $('#stc_customer_sale_site_refr').on('change', function(e){
            e.preventDefault();
            var js_sitename=$(this).val();
            var js_custid=$('#stc_customer_sale_product_invoice').val();
            $.ajax({
              url       : "kattegat/ragnar_invoice.php", 
              method    : "post",
              data      : {
                call_challan_on_choose_customer_site:1,
                js_sitename:js_sitename,
                js_custid:js_custid
              },
              // dataType : 'JSON',
              success   : function(data){
                // console.log(data);
                $('.stc-call-view-invoice-sale-product-row').html(data);
              }
            });
          });

          // add to invoices session
          $(document).on('click', '.add_to_invo_cart', function(e){  
            e.preventDefault();
            var challan_id = $(this).attr("id");
            $.ajax({  
              url       : "kattegat/ragnar_invoice.php", 
              method    : "POST",  
              data      : {
                invo_challan_id:challan_id,
                add_invo_sess_action:1
              },  
              // dataType: `JSON`,
              success   : function(data){ 
               // console.log(data);
                alert(data);
                show_invo_cart();
              }  
            });  
          }); 

          // delete from invoices session
          $('body').delegate('.stcdelinvobtn','click',function(e){
            e.preventDefault();
            var product_id = $(this).attr("id");
              if(confirm("Are you sure you want to remove this product?")){   
                $.ajax({  
                  url       : "kattegat/ragnar_invoice.php", 
                  method    : "POST",
                  data      : {  
                      product_id:product_id,
                      stcdelinvolinei:1  
                  },  
                  success   : function(data){  
                    show_invo_cart();
                    alert(data);                        
                  }  
                });  
              }         
          });

          // save invoice
          $(document).on('click', '.stcsaveinvo', function(e){
            e.preventDefault();
            var stcinvodate=$('.stcinvodate').val();    
            var cust_id=$('#stc_customer_sale_product_invoice').val();
            var stcinvonotes=$('#stcinvonotes').val();
            $.ajax({  
              url       : "kattegat/ragnar_invoice.php", 
              method    : "POST",  
              data      : {
                stcinvodate:stcinvodate,
                invo_cust_id:cust_id,
                stcinvonotes:stcinvonotes,
                save_invo_action:1
              },  
              // dataType: `JSON`,
              success   : function(data){ 
                // console.log(data);
                  alert(data);
                  show_invo_cart();
                  // $('.additional-sale-order-create-response-sale-product-form')[0].reset();
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
          var jsinvoiceid='';
          var jschallaninvoiceno='';
          var jsbegvalue='';
          var jsendvalue='';
          // filter challan
          // by date
          $('body').delegate('#purchaseproddatefilt', 'click', function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jscustomerid=$("#stc-challan-customer-in").val();
            jsinvoiceid=$("#stc-invoice-id-finder").val();
            jschallaninvoiceno=$("#stc-invoice-number-finder").val();
            jschallansitename=$("#stc-challan-sitename-finder").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            stc_filter_adjust_tax_invoice(jsbegdate, jsenddate, jscustomerid, jsinvoiceid, jschallaninvoiceno, jschallansitename, jsbegvalue, jsendvalue);
          });

          // by customer id
          $("#stc-challan-customer-in").change(function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jscustomerid=$("#stc-challan-customer-in").val();
            jsinvoiceid=$("#stc-invoice-id-finder").val();
            jschallaninvoiceno=$("#stc-invoice-number-finder").val();
            jschallansitename=$("#stc-challan-sitename-finder").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            stc_filter_adjust_tax_invoice(jsbegdate, jsenddate, jscustomerid, jsinvoiceid, jschallaninvoiceno, jschallansitename, jsbegvalue, jsendvalue);
          });

          // by invoice id
          $("#stc-invoice-id-finder").on('keyup', function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jscustomerid=$("#stc-challan-customer-in").val();
            jsinvoiceid=$("#stc-invoice-id-finder").val();
            jschallaninvoiceno=$("#stc-invoice-number-finder").val();
            jschallansitename=$("#stc-challan-sitename-finder").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            stc_filter_adjust_tax_invoice(jsbegdate, jsenddate, jscustomerid, jsinvoiceid, jschallaninvoiceno, jschallansitename, jsbegvalue, jsendvalue);
          });

          // by invoice number
          $("#stc-invoice-number-finder").on('keyup', function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jscustomerid=$("#stc-challan-customer-in").val();
            jsinvoiceid=$("#stc-invoice-id-finder").val();
            jschallaninvoiceno=$("#stc-invoice-number-finder").val();
            jschallansitename=$("#stc-challan-sitename-finder").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            stc_filter_adjust_tax_invoice(jsbegdate, jsenddate, jscustomerid, jsinvoiceid, jschallaninvoiceno, jschallansitename, jsbegvalue, jsendvalue);
          });

          // by sitenemae number
          $("#stc-challan-sitename-finder").on('keyup', function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jscustomerid=$("#stc-challan-customer-in").val();
            jsinvoiceid=$("#stc-invoice-id-finder").val();
            jschallaninvoiceno=$("#stc-invoice-number-finder").val();
            jschallansitename=$("#stc-challan-sitename-finder").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            stc_filter_adjust_tax_invoice(jsbegdate, jsenddate, jscustomerid, jsinvoiceid, jschallaninvoiceno, jschallansitename, jsbegvalue, jsendvalue);
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
              outbegvalueinputted= (+begvalue) - 10;
              outendvalueinputted= (+endvalue) - 10;
              jsbegdate=$('.begdate').val();
              jsenddate=$('.enddate').val();
              jscustomerid=$("#stc-challan-customer-in").val();
              jsinvoiceid=$("#stc-invoice-id-finder").val();
              jschallaninvoiceno=$("#stc-invoice-number-finder").val();
              jschallansitename=$("#stc-challan-sitename-finder").val();
              jsbegvalue=outbegvalueinputted;
              jsendvalue=outendvalueinputted;
              stc_filter_adjust_tax_invoice(jsbegdate, jsenddate, jscustomerid, jsinvoiceid, jschallaninvoiceno, jschallansitename, jsbegvalue, jsendvalue);
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
              outbegvalueinputted= (+begvalue) + 10;
              outendvalueinputted= (+endvalue) + 10;
              jsbegdate=$('.begdate').val();
              jsenddate=$('.enddate').val();
              jscustomerid=$("#stc-challan-customer-in").val();
              jsinvoiceid=$("#stc-invoice-id-finder").val();
              jschallaninvoiceno=$("#stc-invoice-number-finder").val();
              jschallansitename=$("#stc-challan-sitename-finder").val();
              jsbegvalue=outbegvalueinputted;
              jsendvalue=outendvalueinputted;
              stc_filter_adjust_tax_invoice(jsbegdate, jsenddate, jscustomerid, jsinvoiceid, jschallaninvoiceno, jschallansitename, jsbegvalue, jsendvalue);
            }
          });

          // filter function
          function stc_filter_adjust_tax_invoice(jsbegdate, jsenddate, jscustomerid, jsinvoiceid, jschallaninvoiceno, jschallansitename){
            $.ajax({
              url     : "kattegat/ragnar_invoice.php",
              method  : "post",
              data    : {
                stcadjusttaxinvoaction:1,
                phpbegdate:jsbegdate, 
                phpenddate:jsenddate, 
                phpcustomerid:jscustomerid, 
                phpinvoiceid:jsinvoiceid,
                phpchallaninvoiceno:jschallaninvoiceno,
                phpchallansitename:jschallansitename,
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

          // update invoice
          $('body').delegate('.invo-change-btn-hit', 'click', function(e){
            e.preventDefault();
            var invo_challan_id=$(this).attr("id");
            var invo_change_id=$('.invo-change-btn-vaue'+invo_challan_id).val();
            var invo_id=$('#chall-id'+invo_challan_id).val();
            $.ajax({
              url       : "kattegat/ragnar_invoice.php",
              method    : "POST",
              data      : {
                invo_reset_hit:1,
                invo_id:invo_id,
                invo_change_id:invo_change_id,
                invo_challan_id:invo_challan_id
              },
              success   : function(response){
                // console.log(response);
                alert(response);
              }
            });
          });

          // save updated invoice challan
          $('body').delegate('.stc_edit_invo', 'click', function(e){
            e.preventDefault();
            var invoid=$(this).attr("id");
            $.ajax({
              url       : "kattegat/ragnar_invoice.php",
              method    : 'post',
              data      : {
                loadpertinvo:1,
                invoid:invoid
              },
              // dataType : 'JSON',
              success   : function(invoices){
                // console.log(invoices);
                $('.invo-details-out').html(invoices);
              }
            })
            $("#invo-modal").modal("show");
          });
        });
    </script>
    <script>
      $(document).ready(function(){
        // call annexture sitenam
        $('body').delegate('.stcannexfindsite', 'click', function(e){
          e.preventDefault();
          var begdate=$('.stcannexbegdate').val();
          var enddate=$('.stcannexenddate').val();
          $.ajax({
            url       : "kattegat/ragnar_invoice.php",
            method    : "post",
            data      : {
              friday_call_sitename:1,
              begdate:begdate,
              enddate:enddate
            },
            // dataType  : 'JSON',
            success   : function(data){
              // console.log(data);
              $('.stc-annexture-sitename').html(data);
            }
          });
        });

        // call data using annexture sitename and date
        $('body').delegate('.stcannexturegetdetails', 'click', function(e){
          e.preventDefault();
          var begdate=$('.stcannexbegdate').val();
          var enddate=$('.stcannexenddate').val();
          var sitename=$('.stc-annexture-sitename').val();
          $.ajax({
            url       : "kattegat/ragnar_invoice.php",
            method    : "post",
            data      : {
              friday_call_annexture:1,
              begdate:begdate,
              enddate:enddate,
              sitename:sitename
            },
            // dataType  : 'JSON',
            success   : function(data){
              // console.log(data);
              $('.stc-annexture-data-show').html(data);
              $('.site-show').html('Site Reference : '+sitename);
            }
          });
        });

        // print
        $('#printAnnexture').click(function(){
            Popup($('.stc-annexture-data-show')[0].outerHTML);
            function Popup(data){
                window.print();
                return true;
            }            
        });
      });
    </script>
</body>
</html>