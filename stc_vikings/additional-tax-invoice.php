<?php  
session_start(); 
if(isset($_SESSION["stc_empl_id"]) && ($_SESSION["stc_empl_role"]>0)){ 
}else{ 
    header("Location:index.html"); 
} 
$page_code=516;
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
    <title>Additional Tax Invoice - STC</title>
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
                                <a role="tab" class="nav-link active" id="tab-1" data-toggle="tab" href="#tab-content-1">
                                    <span>View Additional Tax Invoice</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-content-2">
                                    <span>Create Additional Tax Invoice</span>
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
                                              >View Additional Tax Invoice
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
                                              >Adjustment Tax Invoice
                                            </h5>
                                        </div>
                                    </div>
                                </div>
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
                                          name="stcaddinvodate"
                                          type="date"
                                          min="2001-01-01" 
                                          max="2050-12-31"
                                          placeholder="Challan Date"
                                          class="form-control validate stcaddinvodate"
                                          value=""
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
                                          id="stc_customer_addiional_sale_order_invo"
                                          class="custom-select stc-select-customer stc_customer_sale_product_add_invoice"
                                        ></select>
                                      </div>
                                    </div> 
                                    <!-- search product display -->
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <div class="row stc-call-view-add-invoice-sale-product-row">
                                        </div>
                                      </div>
                                    </div>

                                    <!-- line item table -->
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success additionalinvoflase">
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
                                          id="stcaddinvonotes"
                                          placeholder="Notes"
                                          required
                                        ></textarea>
                                      </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <a type="submit" class="btn btn-primary btn-block text-uppercase stcsaveaddinvo">Save</a>
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

          // additional invoice call customer
          $('#stc_customer_addiional_sale_order_invo').on('change', function(e){
            e.preventDefault();
            var js_cust_id=$(this).val();
            $.ajax({
              url       : "kattegat/ragnar_invoice.php",
              method    : "post",
              data      : {
                call_challan_on_choose_customer_addinvo:1,
                customer_id:js_cust_id
              },
              // dataType : 'JSON',
              success   : function(data){
                // console.log(data);
                $('.stc-call-view-add-invoice-sale-product-row').html(data);
              }
            });
          });

          // load invoices session
          show_additional_invo_cart();
          function show_additional_invo_cart() {
            $.ajax({  
              url       : "kattegat/ragnar_invoice.php", 
              method    : "POST",
              data      : {  
                  comon_additional_invo_sess:1  
              },  
              // dataType: 'JSON',
              success   : function(data){
                // console.log(data);    
                // $('.invoflase').html(data['invoTable']); 
                $('.additionalinvoflase').html(data);                    
              }  
            });
          }

          // additional invoice add to invoices session
          $(document).on('click', '.add_to_add_invo_cart', function(e){  
            e.preventDefault();
            var challan_id = $(this).attr("id");
            $.ajax({  
              url       : "kattegat/ragnar_invoice.php",
              method    : "POST",  
              data      : {
                invo_challan_id:challan_id,
                add_add_invo_sess_action:1
              },  
              // dataType: `JSON`,
              success   : function(data){ 
               // console.log(data);
               alert(data);
                show_additional_invo_cart();
              }  
            });  
          }); 

          // delete from invoices session
          $('body').delegate('.stcdeladdinvobtn','click',function(e){
            e.preventDefault();
            var product_id = $(this).attr("id");
              if(confirm("Are you sure you want to remove this product?")){   
                $.ajax({  
                  url       : "kattegat/ragnar_invoice.php",
                  method    : "POST",
                  data      : {  
                      product_id:product_id,
                      stcdeladdinvolinei:1  
                  },  
                  success   : function(data){  
                    show_additional_invo_cart();
                    alert(data);                        
                  }  
                });  
              }         
          });

          // save additional invoices
          $(document).on('click', '.stcsaveaddinvo', function(e){
            e.preventDefault();
            var stcinvodate=$('.stcaddinvodate').val();    
            var cust_id=$('.stc_customer_sale_product_add_invoice').val();
            var stcinvonotes=$('#stcaddinvonotes').val();
              $.ajax({  
                url       : "kattegat/ragnar_invoice.php",
                method    : "POST",  
                data      : {
                  stcinvodate:stcinvodate,
                  invo_cust_id:cust_id,
                  stcinvonotes:stcinvonotes,
                  save_add_invo_action:1
                },  
                // dataType: `JSON`,
                success   : function(data){ 
                    alert(data);
                    show_additional_invo_cart();
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
            stc_filter_additional_tax_invoice(jsbegdate, jsenddate, jscustomerid, jsinvoiceid, jschallaninvoiceno, jschallansitename, jsbegvalue, jsendvalue);
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
            stc_filter_additional_tax_invoice(jsbegdate, jsenddate, jscustomerid, jsinvoiceid, jschallaninvoiceno, jschallansitename, jsbegvalue, jsendvalue);
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
            stc_filter_additional_tax_invoice(jsbegdate, jsenddate, jscustomerid, jsinvoiceid, jschallaninvoiceno, jschallansitename, jsbegvalue, jsendvalue);
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
            stc_filter_additional_tax_invoice(jsbegdate, jsenddate, jscustomerid, jsinvoiceid, jschallaninvoiceno, jschallansitename, jsbegvalue, jsendvalue);
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
            stc_filter_additional_tax_invoice(jsbegdate, jsenddate, jscustomerid, jsinvoiceid, jschallaninvoiceno, jschallansitename, jsbegvalue, jsendvalue);
          });

          // paging before search
          $('body').delegate('.begbuttoninvsearch', 'click', function(e){
            e.preventDefault();
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
              stc_filter_additional_tax_invoice(jsbegdate, jsenddate, jscustomerid, jsinvoiceid, jschallaninvoiceno, jschallansitename, jsbegvalue, jsendvalue);
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
              stc_filter_additional_tax_invoice(jsbegdate, jsenddate, jscustomerid, jsinvoiceid, jschallaninvoiceno, jschallansitename, jsbegvalue, jsendvalue);
            }
          });

          // filter function
          function stc_filter_additional_tax_invoice(jsbegdate, jsenddate, jscustomerid, jsinvoiceid, jschallaninvoiceno, jschallansitename, jsbegvalue, jsendvalue){
            $.ajax({
              url     : "kattegat/ragnar_invoice.php",
              method  : "post",
              data    : {
                stcadditionaltaxinvoaction:1,
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
        });
    </script>
</body>
</html>