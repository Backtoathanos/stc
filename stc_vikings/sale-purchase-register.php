<?php  
session_start(); 
if(isset($_SESSION["stc_empl_id"]) && ($_SESSION["stc_empl_role"]>0)){ 
}else{ 
    header("Location:index.html"); 
} 
$page_code=522;
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
    <title>Sale Purchase Register - STC</title>
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
                                    <span>Purchase Register</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-content-2">
                                    <span>Sale Register</span>
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
                                            >Purchase Register
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <table class="table table-hover ">
                                              <thead>
                                                <tr>
                                                  <th scope="col">From/<br>To</th>
                                                  <th scope="col" colspan="2">Search By Merchant Name</th>
                                                </tr>
                                              </thead>
                                              <tbody>
                                                <tr>
                                                  <td>
                                                    <p><input type="date" class="form-control purbegdate"></p>
                                                    <p><input type="date" class="form-control purenddate"></p>
                                                    <p><a href="#" id="purregdatefilt">
                                                        <i style="font-size: 20px;background: #0c0c1b;border-radius: 50%;padding: 5px;margin: 0;color: #77ffd3;float: right;" class="fa fa-arrow-down"></i>
                                                      </a>
                                                    </p>
                                                  </td>
                                                  <td>
                                                      <select
                                                        id="stc_vendor_purchase_product"
                                                        class="custom-select stc-select-vendor search-key-pur-rec-text"
                                                        name="stcvendor"
                                                      >
                                                      </select>
                                                  </td>
                                                  <td><button class="btn btn-primary btn-block text-uppercase search-key-pur-rec-hit">Find <i class="fa fa-search"></i></button></td>
                                                </tr>
                                              </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <table class="table table-hover ">
                                              <thead>
                                                <tr>
                                                  <th scope="col" width="50%">Label</th>
                                                  <th scope="col"></th>
                                                  <th scope="col">Value</th>
                                                </tr>
                                              </thead>
                                              <tbody>
                                                <tr>
                                                  <td><h3><b>Basic Amount</b></h3></td>
                                                  <td><h3><b>:</b></h3></td>
                                                  <td align="right">
                                                    <i class="fas fa-rupee-sign"></i> <span class="tb-p-basic"></span> + 
                                                    <i class="fas fa-rupee-sign"></i> <span class="tb-p-freight"></span> + 
                                                    <i class="fas fa-rupee-sign"></i> <span class="tb-p-pandf"></span> + 
                                                    <i class="fas fa-rupee-sign"></i> <span class="tb-p-others"></span><br>
                                                    <i class="fas fa-rupee-sign"></i> <span class="tb-basic"></span>
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <td><h3><b>CGST Amount</b></h3></td>
                                                  <td><h3><b>:</b></h3></td>
                                                  <td align="right"><i class="fas fa-rupee-sign"></i> <span class="tb-cgst"></span></td>
                                                </tr>
                                                <tr>
                                                  <td><h3><b>SGST Amount</b></h3></td>
                                                  <td><h3><b>:</b></h3></td>
                                                  <td align="right"><i class="fas fa-rupee-sign"></i> <span class="tb-sgst"></span></td>
                                                </tr>
                                                <tr>
                                                  <td><h3><b>IGST Amount</b></h3></td>
                                                  <td><h3><b>:</b></h3></td>
                                                  <td align="right"><i class="fas fa-rupee-sign"></i> <span class="tb-igst"></span></td>
                                                </tr>
                                                <tr>
                                                  <td><h3><b>Total Amount</b></h3></td>
                                                  <td><h3><b>:</b></h3></td>
                                                  <td align="right"><i class="fas fa-rupee-sign"></i> <span class="tb-total"></span></td>
                                                </tr>
                                              </tbody>
                                            </table>
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
                                            >Sale Register
                                            </h5>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <table class="table table-hover ">
                                              <thead>
                                                <tr>
                                                  <th scope="col">From/<br>To</th>
                                                  <th scope="col" colspan="2">Search By Customer Name</th>
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
                                                    <p><input type="date" value="<?php echo $effectiveDate;?>" class="form-control salebegdate"></p>
                                                    <p><input type="date" value="<?php echo $newDate;?>" class="form-control saleenddate"></p>
                                                    <p><a href="#" id="saleregdatefilt">
                                                        <i style="font-size: 20px;background: #0c0c1b;border-radius: 50%;padding: 5px;margin: 0;color: #77ffd3;float: right;" class="fa fa-arrow-down"></i>
                                                      </a>
                                                    </p>
                                                  </td>
                                                  <td>
                                                      <select
                                                        id="stc_vendor_purchase_product"
                                                        class="custom-select stc-select-customer search-key-sale-rec-text"
                                                        name="stcvendor"
                                                      >
                                                      </select>
                                                  </td>
                                                  <td><button class="btn btn-primary btn-block text-uppercase search-key-sale-rec-hit">Find <i class="fa fa-search"></i></button></td>
                                                </tr>
                                              </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <table class="table table-hover ">
                                              <thead>
                                                <tr>
                                                  <th scope="col" width="50%">Label</th>
                                                  <th scope="col"></th>
                                                  <th scope="col">Value</th>
                                                </tr>
                                              </thead>
                                              <tbody>
                                                <tr>
                                                  <td><h3><b>Basic Amount</b></h3></td>
                                                  <td><h3><b>:</b></h3></td>
                                                  <td align="right">
                                                    <i class="fas fa-rupee-sign"></i> <span class="sb-basic"></span>
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <td><h3><b>CGST Amount</b></h3></td>
                                                  <td><h3><b>:</b></h3></td>
                                                  <td align="right"><i class="fas fa-rupee-sign"></i> <span class="sb-cgst"></span></td>
                                                </tr>
                                                <tr>
                                                  <td><h3><b>SGST Amount</b></h3></td>
                                                  <td><h3><b>:</b></h3></td>
                                                  <td align="right"><i class="fas fa-rupee-sign"></i> <span class="sb-sgst"></span></td>
                                                </tr>
                                                <tr>
                                                  <td><h3><b>IGST Amount</b></h3></td>
                                                  <td><h3><b>:</b></h3></td>
                                                  <td align="right"><i class="fas fa-rupee-sign"></i> <span class="sb-igst"></span></td>
                                                </tr>
                                                <tr>
                                                  <td><h3><b>Total Amount</b></h3></td>
                                                  <td><h3><b>:</b></h3></td>
                                                  <td align="right"><i class="fas fa-rupee-sign"></i> <span class="sb-total"></span></td>
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
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script type="text/javascript" src="./assets/scripts/loginopr.js"></script>
    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    <script type="text/javascript" src="./assets/scripts/jarvis.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script>
      $(document).ready(function(){    
          // call vendor
          stc_call_merchants();
          function stc_call_merchants(){
            $.ajax({
              url       : "kattegat/ragnar_spregister.php",
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

          // purchase register with a date
          $('body').delegate('#purregdatefilt', 'click', function(e){
            e.preventDefault();
            var purbegdate=$('.purbegdate').val();
            var purenddate=$('.purenddate').val();
            $.ajax({
              url       : "kattegat/ragnar_spregister.php",
              type      : 'post',
              data      : {
                get_rec_fro_date:1,
                purbegdate:purbegdate,
                purenddate:purenddate
              },
              dataType  : "JSON",
              success   : function(response){
               // console.log(response);
                  var basic=response['basic'] + response['bfright'] + response['btandf'] + response['bothers'];
                  var gst=(response['bgst']/2) + (response['bfgst']/2) + (response['bpandcgst']/2) + (response['bothergst']/2);
                  var igst=response['bigst'] + response['bfigst'] + response['bpandcigst']  + response['botherigst'];
                  var total=basic + (gst * 2) + igst;
                  igst=igst.toFixed(2);
                  total=total.toFixed(2);
                  $('.tb-p-basic').html(response['basic']);
                  $('.tb-p-freight').html(response['bfright']);
                  $('.tb-p-pandf').html(response['btandf']);
                  $('.tb-p-others').html(response['bothers']);
                  $('.tb-basic').html(response['basic']);
                  $('.tb-basic').html(basic);
                  $('.tb-cgst').html(gst);
                  $('.tb-sgst').html(gst);
                  $('.tb-igst').html(igst);
                  $('.tb-total').html(total);
              }
            });
          }); 

          // purchase register with merchant
          $('body').delegate('.search-key-pur-rec-hit', 'click', function(e){
            e.preventDefault();
            var key_pay=$('.search-key-pur-rec-text').val();
            var purbegdate=$('.purbegdate').val();
            var purenddate=$('.purenddate').val();
            $.ajax({
              url       : "kattegat/ragnar_spregister.php",
              type      : 'post',
              data      : {
                key_pay:key_pay,
                get_rec_fro_key:1,
                purbegdate:purbegdate,
                purenddate:purenddate
              },
              dataType  : "JSON",
              success   : function(response){
                // console.log(response);
                var basic=response['basic'] + response['bfright'] + response['btandf'] + response['bothers'];
                var gst=(response['bgst']/2) + (response['bfgst']/2) + (response['bpandcgst']/2) + (response['bothergst']/2);
                var igst=response['bigst'] + response['bfigst'] + response['bpandcigst']  + response['botherigst'];
                var total=basic + (gst * 2) + igst;
                igst=igst.toFixed(2);
                total=total.toFixed(2);
                $('.tb-p-basic').html(response['basic']);
                $('.tb-p-freight').html(response['bfright']);
                $('.tb-p-pandf').html(response['btandf']);
                $('.tb-p-others').html(response['bothers']);
                $('.tb-basic').html(response['basic']);
                $('.tb-basic').html(basic);
                $('.tb-cgst').html(gst);
                $('.tb-sgst').html(gst);
                $('.tb-igst').html(igst);
                $('.tb-total').html(total);
              }
            });
          });
      });
    </script>

    <script>
      $(document).ready(function(){        
          // call customer for sale
          stc_call_customer();
          function stc_call_customer(){
            $.ajax({
              url       : "kattegat/ragnar_spregister.php",
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

          // sale filter by date
          $('body').delegate('#saleregdatefilt', 'click', function(e){
            e.preventDefault();
            var salebegdate=$('.salebegdate').val();
            var saleenddate=$('.saleenddate').val();
            $.ajax({
              url       : "kattegat/ragnar_spregister.php",
              type      : 'post',
              data      : {
                get_sale_rec_fro_date:1,
                salebegdate:salebegdate,
                saleenddate:saleenddate
              },
              dataType  : "JSON",
              success   : function(response){
                // console.log(response);
                var basic=response['salebasic'];
                var gst=(response['salegst']/2);
                var igst=response['saleigst'];
                var total=basic + (gst * 2) + igst;
                total=total.toFixed(2);
                $('.sb-basic').html(basic);
                $('.sb-cgst').html(gst);
                $('.sb-sgst').html(gst);
                $('.sb-igst').html(igst);
                $('.sb-total').html(total);
              }
            });
          }); 

          // sale filter by date & customer
          $('body').delegate('.search-key-sale-rec-hit', 'click', function(e){
            e.preventDefault();
            var key_pay=$('.search-key-sale-rec-text').val();
            var salebegdate=$('.salebegdate').val();
            var saleenddate=$('.saleenddate').val();
            $.ajax({
              url       : "kattegat/ragnar_spregister.php",
              type      : 'post',
              data      : {
                key_pay:key_pay,
                get_sale_rec_fro_cust:1,
                salebegdate:salebegdate,
                saleenddate:saleenddate
              },
              dataType  : "JSON",
              success   : function(response){
                // console.log(response);
                var basic=response['salebasic'];
                var gst=(response['salegst']/2);
                var igst=response['saleigst'];
                var total=basic + igst + (gst * 2);
                total=total.toFixed(2);
                $('.sb-basic').html(basic);
                $('.sb-cgst').html(gst);
                $('.sb-sgst').html(gst);
                $('.sb-igst').html(igst);
                $('.sb-total').html(total);
              }
            });
          }); 
      });
    </script>
</body>
</html>