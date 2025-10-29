<?php  
// Include authentication helper
require_once 'kattegat/auth_helper.php';

// Check authentication using the new hybrid system
STCAuthHelper::checkAuth();$page_code=208;
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
    <title>Payment Adjustment - STC</title>
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
                    <div class="row stc-view-product-row">
                        <div class="col-xl-12 col-lg-12 col-md-12">
                          <div class="card-border mb-3 card card-body border-success">
                            <div class="row">
                              <div class="col-sm-6">
                                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modal-adjustment">
                                  New Adjustment
                                </button>
                              </div>
                              <div class="col-sm-6">
                                <a href="#" class="btn btn-light" style="float: right;"><b>Account Balance - </b><span id="stc-balance-badge">0.00</span></a>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12">
                          <div class="card-border mb-3 card card-body border-success">
                            <table class="table table-hover ">
                              <thead>
                                <tr>
                                  <th scope="col">From/<br>To</th>
                                  <th scope="col" width="30%">By Bank Name</th>
                                  <th scope="col" width="30%">By Depositor</th>
                                  <th scope="col" width="30%">By Reference</th>
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
                                    <input 
                                      type="text" 
                                      id="stc-deposit-bank-name" 
                                      class="form-control"
                                      placeholder="Bank Name" 
                                    >
                                  </td>
                                  <td>
                                    <input 
                                      type="text" 
                                      id="stc-deposit-depositor" 
                                      class="form-control"
                                      placeholder="Depositor Name" 
                                    >
                                  </td>
                                  <td>
                                    <input 
                                      type="text" 
                                      id="stc-deposit-refrences"  
                                      class="form-control"
                                      placeholder="Any Refrences" 
                                    >
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            <table class="table table-hover table-bordered table-dark">
                              <thead>
                                <tr>
                                  <th scope="col">Bank Name</th> 
                                  <th scope="col">Deposit Date<br>Deposit No</th>
                                  <th scope="col">Depositor Name</th>
                                  <th scope="col">Depositor Reference</th>
                                  <th scope="col">Depositor Amount</th>
                                  <th scope="col">Depositor Remarks</th>
                                </tr>
                              </thead>
                              <tbody class="stc-call-view-deposit-row">
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

        var jsbegdate='';
        var jsenddate='';
        var jsbankname='';
        var jsdepositor='';
        var jsrefrence='';
        var jsbegvalue='';
        var jsendvalue='';
        // filter challan
        // by date
        $('body').delegate('#purchaseproddatefilt', 'click', function(e){
          e.preventDefault();
          jsbegdate=$('.begdate').val();
          jsenddate=$('.enddate').val();
          jsbankname=$("#stc-deposit-bank-name").val();
          jsdepositor=$("#stc-deposit-depositor").val();
          jsrefrence=$("#stc-deposit-refrences").val();
          jsbegvalue=$('.begvalueinputsearch').val();
          jsendvalue=$('.endvalueinputsearch').val();
          stc_filter_deposit(jsbegdate, jsenddate, jsbankname, jsdepositor, jsrefrence, jsbegvalue, jsendvalue);
        });

        // by deposit bank name
        $("#stc-deposit-bank-name").on('keyup', function(e){
          e.preventDefault();
          jsbegdate=$('.begdate').val();
          jsenddate=$('.enddate').val();
          jsbankname=$("#stc-deposit-bank-name").val();
          jsdepositor=$("#stc-deposit-depositor").val();
          jsrefrence=$("#stc-deposit-refrences").val();
          jsbegvalue=$('.begvalueinputsearch').val();
          jsendvalue=$('.endvalueinputsearch').val();
          stc_filter_deposit(jsbegdate, jsenddate, jsbankname, jsdepositor, jsrefrence, jsbegvalue, jsendvalue);
        });

        // by depositor
        $("#stc-deposit-depositor").on('keyup', function(e){
          e.preventDefault();
          jsbegdate=$('.begdate').val();
          jsenddate=$('.enddate').val();
          jsbankname=$("#stc-deposit-bank-name").val();
          jsdepositor=$("#stc-deposit-depositor").val();
          jsrefrence=$("#stc-deposit-refrences").val();
          jsbegvalue=$('.begvalueinputsearch').val();
          jsendvalue=$('.endvalueinputsearch').val();
          stc_filter_deposit(jsbegdate, jsenddate, jsbankname, jsdepositor, jsrefrence, jsbegvalue, jsendvalue);
        });

        // by refrences
        $("#stc-deposit-refrences").on('keyup', function(e){
          e.preventDefault();
          jsbegdate=$('.begdate').val();
          jsenddate=$('.enddate').val();
          jsbankname=$("#stc-deposit-bank-name").val();
          jsdepositor=$("#stc-deposit-depositor").val();
          jsrefrence=$("#stc-deposit-refrences").val();
          jsbegvalue=$('.begvalueinputsearch').val();
          jsendvalue=$('.endvalueinputsearch').val();
          stc_filter_deposit(jsbegdate, jsenddate, jsbankname, jsdepositor, jsrefrence, jsbegvalue, jsendvalue);
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
            jsbankname=$("#stc-deposit-bank-name").val();
            jsdepositor=$("#stc-deposit-depositor").val();
            jsrefrence=$("#stc-deposit-refrences").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            jsbegvalue=outbegvalueinputted;
            jsendvalue=outendvalueinputted;
            stc_filter_deposit(jsbegdate, jsenddate, jsbankname, jsdepositor, jsrefrence, jsbegvalue, jsendvalue);
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
            jsbankname=$("#stc-deposit-bank-name").val();
            jsdepositor=$("#stc-deposit-depositor").val();
            jsrefrence=$("#stc-deposit-refrences").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            jsbegvalue=outbegvalueinputted;
            jsendvalue=outendvalueinputted;
            stc_filter_deposit(jsbegdate, jsenddate, jsbankname, jsdepositor, jsrefrence, jsbegvalue, jsendvalue);
          }
        });

        // filter function
        function stc_filter_deposit(jsbegdate, jsenddate, jsbankname, jsdepositor, jsrefrence, jsbegvalue, jsendvalue){
          $.ajax({
            url     : "kattegat/ragnar_account.php",
            method  : "post",
            data    : {
              stcdepositaction:1,
              phpbegdate:jsbegdate, 
              phpenddate:jsenddate, 
              phpjsbankname:jsbankname, 
              phpjsdepositor:jsdepositor,
              phpjsrefrence:jsrefrence,
              phpbegvalue:jsbegvalue,
              phpendvalue:jsendvalue
            },
            // dataType : 'JSON',
            success : function(data){
              // console.log(data);
              $('.stc-call-view-deposit-row').html(data);
            }
          });
        }

        // save deposit
        $('body').delegate('.stc-adjustment-save', 'click', function(e){
          e.preventDefault();
          var adjust_date=$('.stc-adjustment-date').val();
          var adjust_adj_to=$('.stc-adjustment-to').val();
          var adjust_refr=$('.stc-adjustment-refrence').val();
          var adjust_amount=$('.stc-adjustment-amount').val();
          var adjust_remarks=$('.stc-adjustment-remarks').val();
          if(adjust_amount==0){
            alert("Seriously??? Are you sure you want to Adjust 0? Because i can't accept that large amount.");
          }else{
            $.ajax({
              url       : "kattegat/ragnar_account.php",
              method    : "POST",
              data      : {
                stc_adjustment_hit:1,
                adjust_date:adjust_date,
                adjust_adj_to:adjust_adj_to,
                adjust_refr:adjust_refr,
                adjust_amount:adjust_amount,
                adjust_remarks:adjust_remarks
              },
              success   : function(response_adjust){
                var response=response_adjust.trim();
                if(response=="Payment Adjusted Successfully!!!"){
                  alert(response);
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
</body>
</html>
<!-- modals -->
<div class="modal fade modal-adjustment" id="modal-adjustment" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Payment Adjustment</h4>
        <button type="button" class="close" data-dismiss="modal" aria-h5="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Payment Adjustment</h3>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form class="pms-adjustment-form">
            <div class="card-body">
              <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-6">
                  <div class="card-border mb-3 card card-body border-success">
                    <h5>
                      Adjustment Id
                    </h5>
                    <input 
                      type="number" 
                      class="form-control stc-adjustment-id"
                      placeholder="Adjustment Id" 
                      disabled 
                    >
                  </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6">
                  <div class="card-border mb-3 card card-body border-success">
                    <h5>
                      Adjustment Date
                    </h5>
                    <input 
                      type="date" 
                      class="form-control stc-adjustment-date"
                      value="<?php echo date('Y-m-d'); ?>" 
                    >
                  </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-12">
                  <div class="card-border mb-3 card card-body border-success">
                    <h5>
                      Adjustment to
                    </h5>
                    <textarea 
                      type="text" 
                      class="form-control stc-adjustment-to"
                      placeholder="Transfered to e.g :- Bank Charges, Invoice Payment etc" 
                    ></textarea>
                  </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6">
                  <div class="card-border mb-3 card card-body border-success">
                    <h5>
                      Adjustment Reference No
                    </h5>
                    <input 
                      type="text" 
                      class="form-control stc-adjustment-refrence"
                      placeholder="Adjustment Reference" 
                    >
                  </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6">
                  <div class="card-border mb-3 card card-body border-success">
                    <h5>
                      Adjustment Amount
                    </h5>
                    <input 
                      type="number" 
                      class="form-control stc-adjustment-amount"
                      placeholder="Enter Adjustment Amount" 
                    >
                  </div>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-12">
                  <div class="card-border mb-3 card card-body border-success">
                    <h5>
                      Remarks
                    </h5>
                    <textarea  
                      class="form-control stc-adjustment-remarks"
                      placeholder="Remarks" 
                    >NA</textarea>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary stc-adjustment-save">Save</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->