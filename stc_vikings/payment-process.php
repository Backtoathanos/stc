<?php  
// Include authentication helper
require_once 'kattegat/auth_helper.php';

// Check authentication using the new hybrid system
STCAuthHelper::checkAuth();$page_code=206;
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
    <title>Payment Process - STC</title>
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

      @page { size: landscape; }

      @media print {
      .hidden-print { 
        display: none;
          }
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
                      <div class="row stc-view-balance-row hidden-print">
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
                                    <span>Payment Process</span>
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
                                              >Payment Process
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row stc-view-product-row hidden-print">
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
                                                    class="custom-select form-control stc-select-vendor js-example-basic-single" 
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
                                                    <option value="2" selected>Accepted</option>
                                                    <option value="3">Completed</option>
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
                                  <div class="col-xl-12 col-lg-12 col-md-12 hidden-print">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <a href="#" class="btn btn-success print-payproces">Print</a>
                                    </div>
                                  </div>
                                  <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="card-border mb-3 card card-body border-success" style="width: 100%;overflow-x: auto; white-space: nowrap; font-size:10px;">
                                      <form action="" class="stc-view-payment-request-form">
                                          <table class="table table-hover table-bordered stc-payment-view-table">
                                            <thead>
                                              <tr>
                                                <th scope="col">PR Date</br>PR No.</th>
                                                <th scope="col">Merchant Name</th>
                                                <th scope="col">Payment With</th>
                                                <th scope="col">Invoice/PO No <br> Invoice/PO Date</th> 
                                                <th scope="col">Invoice/PO Amount</th>
                                                <th scope="col">Passed Amount</th>
                                                <th scope="col">Paid Amount</th>
                                                <th scope="col">Balance for Paid Amount</th>
                                                <th scope="col">Remarks</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Pay</th>
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
        $('.print-payproces').click(function(){
            Popup($('.stc-payment-view-table')[0].outerHTML);
            function Popup(data){
                window.print();
                return true;
            }            
        });

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
                $('.stc-select-vendor').html(mer_response);
              }
            });
          }

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
                stcpayproaction:1,
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

          // pay payment modal show
          $('body').delegate('.stc-add-pay-res-pay', 'click', function(e){
            e.preventDefault();
            var pay_req_id=$(this).attr('id');
            var pay_req_amt=$(this).attr('req-val');
            $('.stcpaycall').val(pay_req_amt);
            $('.stcpayreqid').val(pay_req_id);
            $('.bd-payrespaycart-modal-lg').modal("show");
          });

          // passsed payment
          $('body').delegate('.stcpayhit', 'click', function(e){
            var pay_passed_amt=$('.stcpayget').val();
            var pay_req_id=$('.stcpayreqid').val();
            if(pay_passed_amt==0){
              alert("Seriously??? Are you sure you want to pay 0? Because i can't accept that large amount.");
            }else{
              $.ajax({
                url     : "kattegat/ragnar_account.php",
                method  : "post",
                data    : {
                  stc_res_pay_paid_hit:1,
                  pay_passed_amt:pay_passed_amt,
                  pay_req_id:pay_req_id
                },
                success : function(stc_response_pay){
                  var response=stc_response_pay.trim();
                  if(response=="Payment done successfully!!!"){
                    alert(stc_response_pay);  
                    $('.stcpayget').val('');
                    $('.bd-payrespaycart-modal-lg').modal("hide");
                    setTimeout(function () {
                      window.location.reload();
                    }, 1000);
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
<div class="modal fade bd-payrespaycart-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
                    <h5>Payment To Paid :</h5>
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