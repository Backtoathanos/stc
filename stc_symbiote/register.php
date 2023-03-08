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
    <title>Sale Register - STC</title>
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
    <style>
       .grn-h-out{
        display: none;
       }

       .ap-h-out{
        display: none;
       }

       .rp-h-out{
        display: none;
       }
    </style>
  </head>

  <body>
   <?php include "header.php";?>

    <div class="container tm-mt-big tm-mb-big animated flipInY">
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="page-title-right">
                  <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="erp.php" class="text-uppercase" value="">ERP</a></li>
                    <li class="breadcrumb-item active"><a href="#" class="active" value="">Register</a></li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <label class="btn btn-default text-uppercase"><a href="#" class="btn btn-success btn-block text-uppercase add-advance" value=""> + Purchase Register</a></label>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <label class="btn btn-default text-uppercase"><a href="#" class="btn btn-success btn-block text-uppercase add-regular" value=""> + Sale Register</a></label>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <!-- Purchase Register -->
        <div style="display: none;" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-advance">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12" style="width: 69.3em;overflow-x: auto; white-space: nowrap;">
                <form action="" class="">
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
                        <td><input type="text" placeholder="" class="form-control search-key-pur-rec-text"></td>
                        <td><button class="btn btn-primary btn-block text-uppercase search-key-pur-rec-hit">Find <i class="fa fa-search"></i></button></td>
                      </tr>
                    </tbody>
                  </table>
                </form>
              </div>
            </div>
          </div>
          <!-- Call purchased register -->
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto" style="width: 69.3em;overflow-x: auto; white-space: nowrap;">
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
                <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
                  <h2 class="tm-block-title d-inline-block"><b>Purchase Register</b></h2>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12 call-purchase-reg-records">
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
                          <i class="fas fa-rupee-sign"></i> <span class="tb-p-basic">10000</span> + 
                          <i class="fas fa-rupee-sign"></i> <span class="tb-p-freight">10000</span> + 
                          <i class="fas fa-rupee-sign"></i> <span class="tb-p-pandf">10000</span> + 
                          <i class="fas fa-rupee-sign"></i> <span class="tb-p-others">10000</span><br>
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

        <!-- Sale Register -->
        <div style="display: none;" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-regular">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12" style="width: 69.3em;overflow-x: auto; white-space: nowrap;">
                <form action="" class="">
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
                          <p><input type="date" class="form-control salebegdate"></p>
                          <p><input type="date" class="form-control saleenddate"></p>
                          <p><a href="#" id="saleregdatefilt">
                              <i style="font-size: 20px;background: #0c0c1b;border-radius: 50%;padding: 5px;margin: 0;color: #77ffd3;float: right;" class="fa fa-arrow-down"></i>
                            </a>
                          </p>
                        </td>
                        <td><input type="text" placeholder="" class="form-control search-key-sale-rec-text"></td>
                        <td><button class="btn btn-primary btn-block text-uppercase search-key-sale-rec-hit">Find <i class="fa fa-search"></i></button></td>
                      </tr>
                    </tbody>
                  </table>
                </form>
              </div>
            </div>
          </div>
          <!-- Call Sale register -->
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto" style="width: 69.3em;overflow-x: auto; white-space: nowrap;">
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
                <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
                  <h2 class="tm-block-title d-inline-block"><b>Sale Register</b></h2>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12 call-sale-reg-records">
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
    <?php include "footer.php";?>
    <script>
      $(document).ready(function(){
        $('.add-advance').on('click', function(e){
          e.preventDefault();
          $('.stc-advance').toggle(1000);
          $('.stc-regular').fadeOut(500);
          $('.stc-view').fadeOut(500);
        });

        $('.add-regular').on('click', function(e){
          e.preventDefault();
          $('.stc-regular').toggle(1000);
          $('.stc-advance').fadeOut(500);
          $('.stc-view').fadeOut(500);
          // $('.stc-add-po-product-form')[0].reset();
        });

        $('.stc_uppp').on('click', function(e){
          e.preventDefault();
            $('.stc-regular').hide(500);
            $('.stc-advance').hide(500);
            $('.stc-view').toggle(1000);
        });

        $(".ddd").on("click", function(w) {
          w.preventDefault();
          var $button = $(this);
          var $input = $button.closest('.sp-quantity').find("input.quntity-input");
          $input.val((i, v) => Math.max(0, +v + 1 * $button.data('multi')));
        });

        $('body').delegate('#grn-h-in', 'click', function(e){
          e.preventDefault();
          $('.grn-h-out').toggle(500);
        });

        $('body').delegate('#ap-h-in', 'click', function(e){
          e.preventDefault();
          $('.ap-h-out').toggle(500);
        });

        $('body').delegate('#rp-h-in', 'click', function(e){
          e.preventDefault();
          $('.rp-h-out').toggle(500);
        });
      });

      $(document).ready(function(){           
        $('body').delegate('#purregdatefilt', 'click', function(e){
          e.preventDefault();
          var purbegdate=$('.purbegdate').val();
          var purenddate=$('.purenddate').val();
          $.ajax({
            url: 'asgard/mjolnirregister.php',
            type: 'post',
            data: {
              get_rec_fro_date:1,
              purbegdate:purbegdate,
              purenddate:purenddate
            },
            dataType: "JSON",
            success: function(response){
             // console.log(response);
                // alert(response);
                var basic=response['basic'] + response['bfright'] + response['btandf'] + response['bothers'];
                var gst=(response['bgst']/2) + (response['bfgst']/2) + (response['bpandcgst']/2) + (response['bothergst']/2);
                // var sgst=(response['cgst']/2) + (response['bfgst']/2) + (response['bpandcgst']/2) + (response['bothergst']/2);
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
            url: 'asgard/mjolnirregister.php',
            type: 'post',
            data: {
              key_pay:key_pay,
              get_rec_fro_key:1,
              purbegdate:purbegdate,
              purenddate:purenddate
            },
            dataType: "JSON",
            success: function(response){
              // console.log(response);
              // alert(response);
              var basic=response['basic'] + response['bfright'] + response['btandf'] + response['bothers'];
              var gst=(response['bgst']/2) + (response['bfgst']/2) + (response['bpandcgst']/2) + (response['bothergst']/2);
              // var sgst=(response['cgst']/2) + (response['bfgst']/2) + (response['bpandcgst']/2) + (response['bothergst']/2);
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

        $('body').delegate('#saleregdatefilt', 'click', function(e){
          e.preventDefault();
          var salebegdate=$('.salebegdate').val();
          var saleenddate=$('.saleenddate').val();
          $.ajax({
            url: 'asgard/mjolnirregister.php',
            type: 'post',
            data: {
              get_sale_rec_fro_date:1,
              salebegdate:salebegdate,
              saleenddate:saleenddate
            },
            dataType: "JSON",
            success: function(response){
              // console.log(response);
              // alert(response);
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
              // $('.stc-view-all-payment-form').html(response);
            }
          });
        }); 

        $('body').delegate('.search-key-sale-rec-hit', 'click', function(e){
          e.preventDefault();
          var key_pay=$('.search-key-sale-rec-text').val();
          var salebegdate=$('.salebegdate').val();
          var saleenddate=$('.saleenddate').val();
          $.ajax({
            url: 'asgard/mjolnirregister.php',
            type: 'post',
            data: {
              key_pay:key_pay,
              get_sale_rec_fro_cust:1,
              salebegdate:salebegdate,
              saleenddate:saleenddate
            },
            dataType: "JSON",
            success: function(response){
              // console.log(response);
              // alert(response);
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
              // $('.stc-view-all-payment-form').html(response);
            }
          });
        }); 
      });
    </script>
  </body>
</html>
