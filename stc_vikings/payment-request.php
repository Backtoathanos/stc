<?php  
session_start(); 
if(isset($_SESSION["stc_empl_id"]) && ($_SESSION["stc_empl_role"]>0)){ 
}else{ 
    header("Location:index.html"); 
} 
$page_code=203;
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
    <title>Payment Request - STC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="">
    <meta name="msapplication-tap-highlight" content="no">
    <!-- Latest compiled and minified CSS -->
    <link rel = "icon" type = "image/png" href="images/stc_logo_title.png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="./main.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
      table th{
        text-align: center
      }

      .table{
        font-family: Poppins;
      }
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
                        <div class="row stc-view-balance-row">
                          <div class="col-xl-12 col-lg-12 col-md-12">
                            <div class="card-border mb-3 card card-body border-success">
                              <div class="row">
                                <div class="col-sm-6">
                                  .
                                </div>
                                <div class="col-sm-6">
                                  <a href="#" class="btn btn-light" style="float: right;"><b>Account Balance - </b><span id="stc-balance-badge">0.00</span></a>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                            <li class="nav-item">
                                <a role="tab" class="nav-link active" id="tab-1" data-toggle="tab" href="#tab-content-1">
                                    <span>View Payment Request</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-content-2">
                                    <span>Create Payment Request</span>
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
                                              >Payment Request
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row stc-view-product-row">
                                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                                    <div class="card-border mb-3 card card-body border-success" style="width: 100%;overflow-x: auto; white-space: nowrap; font-size:10px;">
                                      <form action="" class="stc-view-product-form">
                                          <table class="table table-hover table-bordered">
                                            <thead>
                                              <tr>
                                                <th scope="col">From/<br>To</th>
                                                <th scope="col" width="30%">By Merchant</th>
                                                <th scope="col" width="30%">By Reference Number</th>
                                                <th scope="col" width="30%">By Status</th>
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
                                                    id="stc-pay-req-merchant-in"
                                                    class="form-control stc-select-vendor js-example-basic-single"
                                                    name="stcvendor"
                                                  >
                                                  </select>
                                                </td>
                                                <td>
                                                  <input 
                                                    type="number" 
                                                    id="stc-pay-req-refre-number-finder-in" 
                                                    class="form-control"
                                                    placeholder="Reference Number" 
                                                  >
                                                </td>
                                                <td>
                                                  <select 
                                                    class="custom-select form-control stc-pay-req-status-in"
                                                    >
                                                    <option value="NA">Select Status</option>
                                                    <option value="1" selected>Process</option>
                                                    <option value="2">Accepted</option>
                                                    <option value="3">Completed</option>
                                                    <option value="4">Canceled</option>
                                                  </select>
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                                <div class="row stc-view-payment-request-row">
                                  <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="card-border mb-3 card card-body border-success" style="width: 1000px;overflow-x: auto; white-space: nowrap; font-size:10px;">
                                      <form action="" class="stc-view-payment-request-form">
                                          <table class="table table-hover table-bordered stc-purchase-view-table">
                                            <thead>
                                              <tr>
                                                <th scope="col">PR Date</br>PR No.</th>
                                                <th scope="col">Merchant Name</th>
                                                <th scope="col">Purchased For</th>
                                                <th scope="col">Payment Type</th>
                                                <th scope="col">Invoice/PO Type</th>
                                                <th scope="col">Invoice/PO No<br>Invoice/PO Date</th>
                                                <th scope="col">Invoice/PO Amount</th>
                                                <th scope="col">Requested Amount</th>
                                                <th scope="col">Passed Amount</th>
                                                <th scope="col">Paid Amount</th>
                                                <th scope="col">Balance for Pass Amount</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Remarks</th>
                                                <th scope="col">Created By</th>
                                                <th scope="col">Pending Date (in days)</th>
                                                <th scope="col">Payment Amount</th>
                                                <th scope="col">View</th>
                                              </tr>
                                            </thead>
                                            <tbody class="stc-call-view-pay-req-row growit">
                                                <tr>
                                                  <td colspan="13" align="center">Search here</td>
                                                </tr>
                                                <td>
                                                  <button type="button" class="btn btn-primary begbuttoninvsearch" style="float:right;">
                                                    <i class="fas fa-arrow-left"></i>
                                                  </button>
                                                  <input type="hidden" class="begvalueinput" value="0">
                                                  <input type="hidden" class="begvalueinputsearch" value="0">
                                                </td>
                                                <td colspan="12">
                                                  <button type="button" class="btn btn-primary endbuttoninvsearch" >
                                                    <i class="fas fa-arrow-right"></i>
                                                  </button>
                                                  <input type="hidden" class="endvalueinput" value="25">
                                                  <input type="hidden" class="endvalueinputsearch" value="25">
                                                </td>
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
                                              >Payment Request
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="name"
                                              >Payment Request Number *
                                            </h5>
                                            <input
                                              id="gtonumbershow"
                                              name="stcmername"
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
                                              for="name"
                                              >Payment Request Date *
                                            </h5>
                                            <input
                                              id="gtonumbershow"
                                              name="stcmername"
                                              type="date"
                                              placeholder="Payment Number"
                                              class="form-control validate"
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
                                              id="stcpayreqmer"
                                              class="stc-select-vendor js-example-basic-single"
                                              name="stcpayreqmer"
                                            >
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
                                              id="stc-payment-type"
                                              class="custom-select stc-payment-type"
                                              name="stc-payment-type"
                                            ><option value="NA">Please select Payment Type.</option>
                                            <option value="advance">Advance</option>
                                            <option value="regular">Regular</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="name"
                                              >Payment Request Amount
                                            </h5>
                                            <input
                                              id="stc-pay-req-amt"
                                              name="stc-pay-req-amt"
                                              type="text"
                                              placeholder="Amount"
                                              class="form-control validate"
                                            />
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <?php 
                                              $date = date("d-m-Y");
                                              $newDate = date('Y-m-d', strtotime($date)); 
                                              $effectiveDate = date('Y-m-d', strtotime("-1 months", strtotime($date)));
                                            ?>  
                                            <h5
                                              for="name"
                                              >PO/Invoice Date From
                                            </h5>
                                            <input
                                              id="stcpogrnenddate"
                                              name="stcpogrnenddate"
                                              type="date"
                                              class="form-control validate"
                                              value="<?php echo $effectiveDate;?>"
                                            />
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="name"
                                              >PO/Invoice Date To
                                            </h5>
                                            <input
                                              id="stcpoinvenddate"
                                              name="stcpoinvenddate"
                                              type="date"
                                              class="form-control validate"
                                              value="<?php echo $newDate;?>"
                                            />
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                          <a href="#" class="btn btn-success btn-block text-uppercase stc-payment-type-hit">Find & Add</a>      
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                          <div class="row">
                                            <div class="col-xl-8 col-md-8 col-sm-8">
                                              <h5
                                                for="name"
                                                >Invoice No/ PO No
                                              </h5>
                                              <select
                                                id="stc-reference-nos"
                                                class="custom-select stc-reference-nos"
                                                name="stc-reference-nos"
                                              ><option value="NA">Please select Payment Type First.</option>
                                              </select>
                                            </div>
                                            <div class="col-xl-4 col-md-4 col-sm-4">
                                              <h5
                                                for="name"
                                                >.
                                              </h5>
                                              <a href="#" class="btn btn-success btn-block text-uppercase stc-refrenc-add-hit">Add</a> 
                                            </div>
                                          </div>     
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <table class="mb-3 table table-bordered">
                                              <thead>
                                                <tr>
                                                  <th align="center">Sl No</th>
                                                  <th align="center">Invoice No/ PO No</th>
                                                  <th align="center">Invoice Date/ PO Date</th>
                                                  <th align="center">Invoice Amount/PO Amount</th>
                                                  <th align="center">Action</th>
                                                </tr>
                                              </thead>
                                              <tbody class="stc-pay-req-response">
                                                <tr>
                                                  <td>1</td>
                                                  <td>001</td>
                                                  <td>04/05/2021</td>
                                                  <td align="right">12000.00</td>
                                                </tr>
                                                <tr>
                                                  <td>2</td>
                                                  <td>004</td>
                                                  <td>04/06/2021</td>
                                                  <td align="right">12000.00</td>
                                                </tr>
                                                <tr>
                                                  <td colspan="3" align="center"><h4>Total</h4></td>
                                                  <td align="right">24000.00</td>
                                                </tr>
                                              </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="name"
                                              >Payment Request Amount
                                            </h5>
                                            <textarea 
                                              id="stc-req-pay-remarks" 
                                              class="form-control"
                                              placeholder="Remarks" 
                                            ></textarea>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <button type="submit" class="btn btn-primary btn-block text-uppercase stc-payment-req-save">Save</button>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
      $(document).ready(function(){    
        $('.js-example-basic-single').select2();   
        // call live amount
        stc_live_account();
        function stc_live_account(){
          $.ajax({
            url       : "kattegat/ragnar_account.php",
            method    : "POST",
            data      : {
              stc_live_account_call:1
            },
            success   : function(response){
              $('#stc-balance-badge').html(response);
            }
          });
        }

        // call merchant
        call_merchant();
        function call_merchant(){
          $.ajax({
            url       : "kattegat/ragnar_account.php",
            method    : "POST",
            data      : {stc_call_merchant:1},
            success   : function(mer_response){
              // console.log(mer_response);
              $('.stc-select-vendor').html(mer_response);
            }
          });
        }

        // endable payment type on change merchant
        $('body').delegate('.stc-select-vendor', 'change', function(e){
          e.preventDefault();
          $('.stc-payment-type').prop('disabled', false);
        });

        // call payment request refrences
        stc_pay_req_call_refrences();
        function stc_pay_req_call_refrences(){
          $.ajax({
            url       : "kattegat/ragnar_account.php",
            method    : "POST",
            data      : {stc_call_refrence:1},
            success   : function(res_refre){
              $('.stc-pay-req-response').html(res_refre);
            }
          });
        }

        // call refrence on payment type
        $('body').delegate('.stc-payment-type-hit', 'click', function(e){
          e.preventDefault();
          $('.stc-payment-type').prop('disabled', true);
          var begdate=$('#stcpogrnenddate').val();
          var enddate=$('#stcpoinvenddate').val();
          var pay_type=$('.stc-payment-type').val();
          var merchant_id=$('#stcpayreqmer').val();
          $.ajax({
            url       : "kattegat/ragnar_account.php",
            method    : "post",
            data      : {
              pay_type:pay_type,
              merchant_id:merchant_id,
              begdate:begdate,
              enddate:enddate,
              stc_call_reference_type:1
            },
            // dataType  : 'JSON',
            success   : function(data){
              // console.log(data);
              $('.stc-reference-nos').html(data);
              stc_pay_req_call_refrences();
            }
          });
        });

        // add to cart invoice or po
        $('body').delegate('.stc-refrenc-add-hit', 'click', function(e){
          e.preventDefault();
          var pay_type=$('.stc-payment-type').val();
          var refrence=$('.stc-reference-nos').val();
          $.ajax({
            url       : "kattegat/ragnar_account.php",
            method    : "POST",
            data      : {
              stc_refrence_hit:1,
              pay_type:pay_type,
              refrence:refrence
            },
            success   : function(res_cart){
              alert(res_cart);
              stc_pay_req_call_refrences();
            }
          });
        });

        // remove pay req from cart
        $('body').delegate('.stc-remove-pay-req', 'click', function(e){
          e.preventDefault();
          var ref_id=$(this).attr("id");
          $.ajax({
            url       : "kattegat/ragnar_account.php",
            method    : "POST",
            data      : {
              stc_pay_req_delete:1,
              ref_id:ref_id
            },
            success   : function(res_delete){
              alert(res_delete);
              stc_pay_req_call_refrences(); 
            }
          })
        });

        // remove pay req from cart
        $('body').delegate('.stc-pay-req-remove-pay-cart', 'click', function(e){
          e.preventDefault();
          var ref_id=$(this).attr("id");
          $.ajax({
            url       : "kattegat/ragnar_account.php",
            method    : "POST",
            data      : {
              stc_pay_req_remove:1
            },
            success   : function(res_delete){
              alert(res_delete);
              stc_pay_req_call_refrences(); 
            }
          })
        });
        // save pay req 
        $('body').delegate('.stc-payment-req-save', 'click', function(e){
          e.preventDefault();
          var mer_id=$('#stcpayreqmer').val();
          var pay_type=$('#stc-payment-type').val();
          var amount=$('#stc-pay-req-amt').val();
          var remarks=$('#stc-req-pay-remarks').val();
          if(amount==0){
            alert("Seriously??? Are you sure you want to request 0, Because im not gonna take that large amount.");
          }else{
            $.ajax({
              url     : "kattegat/ragnar_account.php",
              method  : "POST",
              data    : {
                stc_pay_req_save:1,
                mer_id:mer_id,
                pay_type:pay_type,
                amount:amount,
                remarks:remarks     
              },
              success : function(save_response){
                var response=save_response.trim();
                if(response=="success"){
                  alert("This Amount of Payment is requested to Boss, Please wait with patience it will approve soon.");
                  window.location.reload();
                }else{
                  alert(response);
                }
              }
            });
          }
        });
      });
    </script>
    <script>
      $(document).ready(function(){
          var jsbegdate='';
          var jsenddate='';
          var jsmerchantid='';
          var jsrefrnumber='';
          var jsstatus='';
          var jsbegvalue='';
          var jsendvalue='';
          // filter payment request
          // by date
          $('body').delegate('#purchaseproddatefilt', 'click', function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jsmerchantid=$("#stc-pay-req-merchant-in").val();
            jsrefrnumber=$("#stc-pay-req-refre-number-finder-in").val();
            jsstatus=$(".stc-pay-req-status-in").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            stc_filter_payment_request(jsbegdate, jsenddate, jsmerchantid, jsrefrnumber, jsstatus, jsbegvalue, jsendvalue);
          });

          // by merchant id
          $("#stc-pay-req-merchant-in").change(function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jsmerchantid=$("#stc-pay-req-merchant-in").val();
            jsrefrnumber=$("#stc-pay-req-refre-number-finder-in").val();
            jsstatus=$(".stc-pay-req-status-in").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            stc_filter_payment_request(jsbegdate, jsenddate, jsmerchantid, jsrefrnumber, jsstatus, jsbegvalue, jsendvalue);
          });

          // by refrence number
          $("#stc-pay-req-refre-number-finder-in").on('keyup', function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jsmerchantid=$("#stc-pay-req-merchant-in").val();
            jsrefrnumber=$("#stc-pay-req-refre-number-finder-in").val();
            jsstatus=$(".stc-pay-req-status-in").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            stc_filter_payment_request(jsbegdate, jsenddate, jsmerchantid, jsrefrnumber, jsstatus, jsbegvalue, jsendvalue);
          });

          // by status
          $(".stc-pay-req-status-in").change(function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jsmerchantid=$("#stc-pay-req-merchant-in").val();
            jsrefrnumber=$("#stc-pay-req-refre-number-finder-in").val();
            jsstatus=$(".stc-pay-req-status-in").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            stc_filter_payment_request(jsbegdate, jsenddate, jsmerchantid, jsrefrnumber, jsstatus, jsbegvalue, jsendvalue);
          });

          // paging before search
          $('body').delegate('.begbuttoninvsearch', 'click', function(e){
            e.preventDefault();
            var query = $('#invtags').val();
            begvalue=$('.begvalueinputsearch').val();
            endvalue=$('.endvalueinputsearch').val();
            if(begvalue==0){
              alert("Seriously!!!");
            }else{
              outbegvalueinputted= (+begvalue) - 20;
              outendvalueinputted= (+endvalue) - 20;
              jsbegdate=$('.begdate').val();
              jsenddate=$('.enddate').val();
              jsmerchantid=$("#stc-pay-req-merchant-in").val();
              jsrefrnumber=$("#stc-pay-req-refre-number-finder-in").val();
              jsstatus=$(".stc-pay-req-status-in").val();
              jsbegvalue=outbegvalueinputted;
              jsendvalue=outendvalueinputted;
              stc_filter_payment_request(jsbegdate, jsenddate, jsmerchantid, jsrefrnumber, jsstatus, jsbegvalue, jsendvalue);
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
              jsmerchantid=$("#stc-pay-req-merchant-in").val();
              jsrefrnumber=$("#stc-pay-req-refre-number-finder-in").val();
              jsstatus=$(".stc-pay-req-status-in").val();
              jsbegvalue=outbegvalueinputted;
              jsendvalue=outendvalueinputted;
              stc_filter_payment_request(jsbegdate, jsenddate, jsmerchantid, jsrefrnumber, jsstatus, jsbegvalue, jsendvalue);
            }
          });

          // filter function
          function stc_filter_payment_request(jsbegdate, jsenddate, jsmerchantid, jsrefrnumber, jsstatus, jsbegvalue, jsendvalue){
            $.ajax({
              url     : "kattegat/ragnar_account.php",
              method  : "post",
              data    : {
                stcpayreqaction:1,
                phpbegdate:jsbegdate, 
                phpenddate:jsenddate, 
                phpmerchantid:jsmerchantid, 
                phprefrnumber:jsrefrnumber, 
                phpstatus:jsstatus,
                phpbegvalue:jsbegvalue,
                phpendvalue:jsendvalue
              },
              // dataType : 'JSON',
              success : function(data){
                // console.log(data);
                $('.stc-call-view-pay-req-row').html(data);
              }
            });
          }

          // stc pay req invoices refrences
          $('body').delegate('.stc-view-pay-req-invoices', 'click', function(e){
            e.preventDefault();
            var pay_req_id=$(this).attr('id');
            $.ajax({
              url       : "kattegat/ragnar_account.php",
              method    : "POST",
              data      : {
                stc_pay_req_call_invoices:1,
                pay_req_id:pay_req_id
              },
              success   : function(response_da){
                $('.stc-pay-req-invoices').html(response_da);
                $('.bd-payreqcart-modal-lg').modal("show");
              }
            });
          });

          // pay payment modal show
          // $('body').delegate('.stc-add-pay-req-pay', 'click', function(e){
          //   e.preventDefault();
          //   var pay_req_id=$(this).attr('id');
          //   var pay_req_amt=$(this).attr('req-val');
          //   $(this).addClass("hide-onsave");
          //   $('.stcpaycall').val(pay_req_amt);
          //   pay_req_amt=pay_req_amt.split(",").join("");
          //   $('.stcpayget').val(pay_req_amt);
          //   $('.stcpayreqid').val(pay_req_id);
          //   $('.bd-payreqpaycart-modal-lg').modal("show");
          // });

          // passsed payment
          $('body').delegate('.stcpayhit', 'click', function(e){
            e.preventDefault();
            var pay_req_id=$(this).attr("id");
            var pay_passed_amt=$('.stcpayget'+pay_req_id).val();
            if(pay_passed_amt==0){
              alert("Boss please provide a valid amount, i think zero or empty field not a valid.");
            }else{
              $.ajax({
                url     : "kattegat/ragnar_account.php",
                method  : "post",
                data    : {
                  stc_req_pay_pass_hit:1,
                  pay_passed_amt:pay_passed_amt,
                  pay_req_id:pay_req_id
                },
                success : function(stc_response_pay){
                  var response=stc_response_pay.trim();
                  if(response=="Payment Passed Successfully. Thankyou Boss.♥♥♥"){

                    $(this).hide();
                    // $('.hide-onsave').removeClass("hide-onsave");
                    alert(stc_response_pay);  
                    $('.stcpayget').val('');
                    $('.bd-payreqpaycart-modal-lg').modal("hide");
                    jsbegdate=$('.begdate').val();
                    jsenddate=$('.enddate').val();
                    jsmerchantid=$("#stc-pay-req-merchant-in").val();
                    jsrefrnumber=$("#stc-pay-req-refre-number-finder-in").val();
                    jsstatus=$(".stc-pay-req-status-in").val();
                    jsbegvalue=$('.begvalueinputsearch').val();
                    jsendvalue=$('.endvalueinputsearch').val();
                    stc_filter_payment_request(jsbegdate, jsenddate, jsmerchantid, jsrefrnumber, jsstatus, jsbegvalue, jsendvalue)
                    // setTimeout(function () {
                      // window.location.reload();

                    // }, 1000);
                  }else{
                    alert(stc_response_pay);  
                  }
                }
              });
            }
          });
      });
    </script>
</body>
</html>
<div class="modal fade bd-payreqcart-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Reference Invoice/PO</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12">
                  <div class="card-border mb-3 card card-body border-success " style="width: 100%;overflow-x: auto; white-space: nowrap; font-size:10px;">
                    <table class="mb-3 table table-bordered">
                      <thead>
                        <tr>
                          <th>Sl No</th>
                          <th>Reference No</th>
                          <th>Reference Date</th>
                          <th>Reference Amount</th>
                          <th>Reference Type</th>
                        </tr>
                      </thead>
                      <tbody class="stc-pay-req-invoices">                        
                      </tbody>
                    </table>
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

<div class="modal fade bd-payreqpaycart-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Reference Payment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12">
                  <div class="card-border mb-3 card card-body border-success">
                    <h5>Requested for Payments :</h5>
                    <input type="hidden" class="stcpayreqid">
                    <input type="text" class="form-control stcpaycall" placeholder="Enter Amount!!!" disabled>
                  </div>                  
                </div>
                <div class="col-xl-12 col-md-12 col-sm-12">
                  <div class="card-border mb-3 card card-body border-success">
                    <h5>Payment To Pass :</h5>
                    <input type="number" class="form-control stcpayget" placeholder="Enter Amount!!!">
                  </div>
                </div>
                <div class="col-xl-12 col-md-12 col-sm-12">
                  <div class="card-border mb-3 card card-body border-success">
                    <button class="btn btn-success stcpayhit">Save</button>
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