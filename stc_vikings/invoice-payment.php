<?php  
// Include authentication helper
require_once 'kattegat/auth_helper.php';

// Check authentication using the new hybrid system
STCAuthHelper::checkAuth();$page_code=519;
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
    <title>Customers Payment - STC</title>
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
                                    <span>View Customer Payment</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-content-2">
                                    <span>Create Customer Payment</span>
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
                                            >View Customer Payment
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                      <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                                        <li class="nav-item">
                                          <a role="tab" class="nav-link active" id="tab-nest-1" data-toggle="tab" href="#tab-nest-content-1">
                                            <span>View Adjust Invoice Payment</span>
                                          </a>
                                        </li>
                                        <li class="nav-item">
                                          <a role="tab" class="nav-link" id="tab-nest-2" data-toggle="tab" href="#tab-nest-content-2">
                                            <span>View Regular Invoice Payment</span>
                                          </a>
                                        </li>
                                      </ul>
                                        <div class="tab-content">
                                          <div class="tab-pane tabs-animation fade show active" id="tab-nest-content-1" role="tabpanel">
                                            <div class="row stc-view-product-row">
                                              <div class="col-xl-12 col-lg-12 col-md-12">
                                                <div class="card-border mb-3 card card-body border-success">
                                                  <form action="" class="stc-view-product-form">
                                                      <table class="table table-hover ">
                                                        <thead>
                                                          <tr>
                                                            <th scope="col">From/<br>To</th>
                                                            <th scope="col" width="50%">By Customer</th>
                                                            <th scope="col" width="40%">By Invoice Number</th>
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
                                                                id="stc-invoice-number-finder" 
                                                                class="form-control"
                                                                placeholder="Invoice Number" 
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
                                                            <th scope="col" style="width: 20%;">Customer Name</th>
                                                            <th scope="col">Invoice No.<br>Invoice Date</th>
                                                            <th scope="col">Payment No.<br>Payment Date</th>
                                                            <th scope="col">Payment Category.</th> 
                                                            <th scope="col">Payment Type.</th>
                                                            <th scope="col">Payment Value</th>
                                                            <th scope="col">Notes</th>
                                                          </tr>
                                                        </thead>
                                                        <tbody class="stc-call-view-invoicepayment-row">
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
                                          <div class="tab-pane tabs-animation fade" id="tab-nest-content-2" role="tabpanel">b
                                          </div>
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
                                              >Create Customer Payment
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                      <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                                        <li class="nav-item">
                                          <a role="tab" class="nav-link active" id="tab-nest-11" data-toggle="tab" href="#tab-nest-content-11">
                                            <span>Create Adjust Invoice Payment</span>
                                          </a>
                                        </li>
                                        <li class="nav-item">
                                          <a role="tab" class="nav-link" id="tab-nest-22" data-toggle="tab" href="#tab-nest-content-22">
                                            <span>Create Regular Invoice Payment</span>
                                          </a>
                                        </li>
                                      </ul>
                                        <div class="tab-content">
                                          <div class="tab-pane tabs-animation fade show active" id="tab-nest-content-11" role="tabpanel">
                                            <div class="row">
                                              <div class="col-xl-12 col-lg-12 col-md-12">
                                                  <div class="card-border mb-3 card card-body border-success">
                                                    <form action="#" class="stc-add-customer-so-payment-form"> 
                                                      <div class="row stc-po-payments-point-row">
                                                        <div class="col-xl-6 col-md-6 col-sm-12"> 
                                                          <div class="card-border mb-3 card card-body border-success">
                                                            <h5
                                                              for="name"
                                                              >Payment Number
                                                            </h5>
                                                            <input
                                                              id="custnumbershow"
                                                              name="custnumbershow"
                                                              type="text"
                                                              placeholder="Payment Number"
                                                              class="form-control validate"
                                                              disabled
                                                            />
                                                          </div>
                                                        </div>
                                                        <div class="col-xl-6 col-md-6 col-sm-12">
                                                          <div class="card-border mb-3 card card-body border-success">
                                                            <h5
                                                              for=""
                                                              >Payment Date
                                                            </h5>
                                                            <input
                                                              id="expire_date"
                                                              name="custdateshow"
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
                                                              >Customer Name
                                                            </h5>
                                                            <select
                                                              id="stccust"
                                                              class="custom-select stc-select-customer"
                                                              name="stccust"
                                                            >
                                                            </select>
                                                          </div>
                                                        </div>
                                                        <div class="col-xl-6 col-md-6 col-sm-12">
                                                          <div class="card-border mb-3 card card-body border-success">
                                                            <h5
                                                              for="name"
                                                              >Sale Order No/ Invoice No
                                                            </h5>
                                                            <select
                                                              id="custsoshow"
                                                              class="custom-select stc-select-order"
                                                              name="custsoshow"
                                                            ><option value="NA">Please Select Customer First!!!</option>
                                                            </select>
                                                          </div>
                                                        </div>
                                                        <div class="col-xl-6 col-md-6 col-sm-12">
                                                          <div class="card-border mb-3 card card-body border-success">
                                                            <h5
                                                              for="name"
                                                              >*Sale Order Date
                                                            </h5>
                                                            <input
                                                              id="expire_date"
                                                              name="custsodateshow"
                                                              type="text"
                                                              placeholder="PO Date"
                                                              class="form-control validate custsodateshow"
                                                              disabled
                                                            />
                                                          </div>
                                                        </div>
                                                        <div class="col-xl-6 col-md-6 col-sm-12">
                                                          <div class="card-border mb-3 card card-body border-success">
                                                            <h5
                                                              for="name"
                                                              >Payment CatType
                                                            </h5>
                                                            <select
                                                              class="custom-select stc-select-payment-type"
                                                              id="stcsocatpaymenttype"
                                                            >
                                                            <option value="ADVANCE">Advance</option>
                                                            <option value="REGULAR">Regular</option>
                                                            </select>
                                                          </div>
                                                        </div>
                                                        <div class="col-xl-6 col-md-6 col-sm-12">
                                                          <div class="card-border mb-3 card card-body border-success">
                                                            <h5
                                                              for="name"
                                                              >Payment Type
                                                            </h5>
                                                            <select
                                                              class="custom-select stc-select-payment-type"
                                                              id="stcsopaymenttype"
                                                            >
                                                            <option value="RTGS">RTGS</option>
                                                            <option value="NEFT">NEFT</option>
                                                            <option value="IMPS">IMPS</option>
                                                            <option value="CHEQUE">CHEQUE</option>
                                                            <option value="CASH">CASH</option>
                                                            </select>
                                                          </div>
                                                        </div>
                                                        <div class="col-xl-6 col-md-6 col-sm-12">
                                                          <div class="card-border mb-3 card card-body border-success">
                                                            <h5
                                                              for="name"
                                                              >Amount
                                                            </h5>
                                                            <input
                                                              id="stcsoamount"
                                                              name="stcsoamount"
                                                              type="text"
                                                              placeholder="Enter Amount"
                                                              class="form-control validate"
                                                            />
                                                          </div>
                                                        </div>
                                                        <div class="col-xl-6 col-md-6 col-sm-12">
                                                          <div class="card-border mb-3 card card-body border-success">
                                                            <h5
                                                              for="name"
                                                              >*Basic Amount
                                                            </h5>
                                                            <input
                                                              id="sodueamountshow"
                                                              name="sodueamountshow"
                                                              type="text"
                                                              placeholder="Due Amount"
                                                              class="form-control validate"
                                                            />
                                                          </div>
                                                        </div>
                                                        <div class="col-sm-12">
                                                          <div class="card-border mb-3 card card-body border-success">
                                                            <h5
                                                              for=""
                                                              >Notes
                                                            </h5>
                                                            <textarea
                                                              class="form-control validate"
                                                              rows="2"
                                                              id="stcsotandc"
                                                              name="stcsotandc"
                                                              placeholder="Notes"
                                                            ></textarea>
                                                          </div>
                                                        </div>
                                                        <div class="col-6" style="display: none;">
                                                          <div class="card-border mb-3 card card-body border-success">
                                                            <input type="hidden" name="stcsoupdate">
                                                            <a class="btn btn-primary btn-block text-uppercase stcupdatecustp">Save</a>
                                                          </div>
                                                        </div>
                                                        <div class="col-6">
                                                          <div class="card-border mb-3 card card-body border-success">
                                                            <input type="hidden" name="stcsosave">
                                                            <a class="btn btn-primary btn-block text-uppercase stcsavecustp">Save</a>
                                                          </div>
                                                        </div>
                                                        <div class="col-6">
                                                          <div class="card-border mb-3 card card-body border-success">
                                                            <a class="btn btn-primary btn-block text-uppercase stceditcustp">Edit</a>
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </form>
                                                  </div>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="tab-pane tabs-animation fade" id="tab-nest-content-22" role="tabpanel">b
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
      $(document).ready(function(){        
          // call customer for sale
          stc_call_customer();
          function stc_call_customer(){
            $.ajax({
              url       : "kattegat/ragnar_payments.php",
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

          // call po on change merchant in advance payment section 
          $('#stccust').on('change', function(e){
            e.preventDefault();
            var js_customer_id=$(this).val();
            $.ajax({
              url       : "kattegat/ragnar_payments.php",
              method    : "post",
              data      : {
                call_so_on_choose_cust:1,
                customer_id:js_customer_id
              },
              dataType  : 'JSON',
              success   : function(data){
                // console.log(data);
                $('#custsoshow').html(data);
              }
            });
          });

          // call date and due amount on po change  
          $('#custsoshow').on('change', function(e){
            e.preventDefault();
            var js_so_number=$(this).val();
            $.ajax({
              url       : "kattegat/ragnar_payments.php",
              method    : "post",
              data      : {
                call_so_date_on_choose_customer:1,
                so_number:js_so_number
              },
              dataType  : 'JSON',
              success   : function(data){
                // console.log(data);
                $('.custsodateshow').val(data['order_date']);
                $('#sodueamountshow').val(data['due_amount']);
              }
            });
          });

          // on advance payment in db
          $('.stcsavecustp').on('click', function(e){
            e.preventDefault();
            var js_cust=$('#stccust').val();
            var js_invo=$('#custsoshow').val();
            var js_cattype=$('#stcsocatpaymenttype').val();
            var js_type=$('#stcsopaymenttype').val();
            var js_value=$('#stcsoamount').val();
            var js_notes=$('#stcsotandc').val();
            $.ajax({
              url       : "kattegat/ragnar_payments.php",
              method    : "post",
              data      : {
                cust:js_cust,
                invo:js_invo,
                cattype:js_cattype,
                type:js_type,
                value:js_value,
                notes:js_notes,
                on_cust_pay_hit:1
              },
              dataType  : 'JSON',
              success   : function (argument) {
                // console.log(argument);
                argument=argument.trim();
                if(argument=="Booyeah!!! Customer payment saved."){
                  alert(argument);
                  $('.stc-add-customer-so-payment-form')[0].reset();
                }else{
                  alert(argument);
                }
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
          var jsinvoicenumber='';
          // filter challan
          // by date
          $('body').delegate('#purchaseproddatefilt', 'click', function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jscustomerid=$("#stc-challan-customer-in").val();
            jsinvoicenumber=$("#stc-invoice-number-finder").val();
            stc_filter_direct_challan(jsbegdate, jsenddate, jscustomerid, jsinvoicenumber);
          });

          // by customer id
          $("#stc-challan-customer-in").change(function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jscustomerid=$("#stc-challan-customer-in").val();
            jsinvoicenumber=$("#stc-invoice-number-finder").val();
            stc_filter_direct_challan(jsbegdate, jsenddate, jscustomerid, jsinvoicenumber);
          });

          // by invoice number
          $("#stc-invoice-number-finder").on('keyup', function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jscustomerid=$("#stc-challan-customer-in").val();
            jsinvoicenumber=$("#stc-invoice-number-finder").val();
            stc_filter_direct_challan(jsbegdate, jsenddate, jscustomerid, jsinvoicenumber);
          });

          // filter function
          function stc_filter_direct_challan(jsbegdate, jsenddate, jscustomerid, jsinvoicenumber){
            $.ajax({
              url     : "kattegat/ragnar_payments.php",
              method  : "post",
              data    : {
                stcadjustinvoaction:1,
                phpbegdate:jsbegdate, 
                phpenddate:jsenddate, 
                phpcustomerid:jscustomerid, 
                phpinvoicenumber:jsinvoicenumber
              },
              // dataType : 'JSON',
              success : function(data){
                // console.log(data);
                $('.stc-call-view-invoicepayment-row').html(data);
              }
            });
          }
        });
    </script>
</body>
</html>