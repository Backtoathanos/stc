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
    <title>Sale Order Payments - STC</title>
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

       #grn-h-in .fa-arrow-right{

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
                    <li class="breadcrumb-item active"><a href="#" class="active" value="">Sale Order Payment</a></li>
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
                <label class="btn btn-default text-uppercase"><a href="#" class="btn btn-success btn-block text-uppercase add-advance" value=""> + Create Payment</a></label>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <!-- Customer payment -->
        <div style="display: none;" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-advance">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">SO Customer Payments</h2> <a class="stc_uppp" href="#"><i class="fas fa-arrow-up"></i></a>
                <p style="color: #ff2d2d; font-size: 15px; font-weight: bold;">* Labels fields are automated.  </p>
              </div>
            </div>
          <form action="#" class="stc-add-customer-so-payment-form"> 
            <div class="row stc-po-payments-point-row">
              <div class="col-xl-6 col-md-6 col-sm-12"> 
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Payment Number
                  </label>
                  <input
                    id="custnumbershow"
                    name="custnumbershow"
                    type="text"
                    placeholder="Payment Number"
                    class="form-control validate"
                    disabled
                    style="background: #45d44a;font-size: 15px;color: black;font-weight: bold;"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Payment Date
                  </label>
                  <input
                    id="expire_date"
                    name="custdateshow"
                    type="text"
                    class="form-control validate"
                    data-large-mode="true"
                    disabled
                    style="background: #45d44a;font-size: 15px;color: black;font-weight: bold;"
                  />
                </div>
              </div>
              <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Customer Name
                  </label>
                  <select
                    id="stccust"
                    class="custom-select stc-select-customer"
                    name="stccust"
                  >
                  </select>
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Sale Order No/ Invoice No
                  </label>
                  <select
                    id="custsoshow"
                    class="custom-select stc-select-order"
                    name="custsoshow"
                  ><option value="NA">Please Select Customer First!!!</option>
                  </select>
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >*Sale Order Date
                  </label>
                  <input
                    id="expire_date"
                    name="custsodateshow"
                    type="text"
                    placeholder="PO Date"
                    class="form-control validate custsodateshow"
                    disabled
                    style="background: #45d44a;font-size: 15px;color: black;font-weight: bold;"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Payment CatType
                  </label>
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
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Payment Type
                  </label>
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
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Amount
                  </label>
                  <input
                    id="stcsoamount"
                    name="stcsoamount"
                    type="text"
                    placeholder="Enter Amount"
                    class="form-control validate"
                    style="background: #45d44a;font-size: 15px;color: black;font-weight: bold;"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >*Basic Amount
                  </label>
                  <input
                    id="sodueamountshow"
                    name="sodueamountshow"
                    type="text"
                    placeholder="Due Amount"
                    class="form-control validate"
                    style="background: #45d44a;font-size: 15px;color: black;font-weight: bold;"
                  />
                </div>
              </div>
              <div class="col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Notes
                  </label>
                  <textarea
                    class="form-control validate"
                    rows="2"
                    id="stcsotandc"
                    name="stcsotandc"
                    placeholder="Notes"
                    style="background: #45d44a;font-size: 15px;color: black;font-weight: bold;"
                  ></textarea>
                </div>
              </div>
              <div class="col-6" style="display: none;">
                <input type="hidden" name="stcsoupdate">
                <a class="btn btn-primary btn-block text-uppercase stcupdatecustp">Save</a>
              </div>
              <div class="col-6">
                <input type="hidden" name="stcsosave">
                <a class="btn btn-primary btn-block text-uppercase stcsavecustp">Save</a>
              </div>
              <div class="col-6">
                <a class="btn btn-primary btn-block text-uppercase stceditcustp">Edit</a>
              </div>
            </div>
          </form>
          </div>
        </div>

        <!-- view All Payments against Sale Orders -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">Customer Payments</h2>
              </div>
            </div>
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12" style="width: 69.3em;overflow-x: auto; white-space: nowrap;">
                <form action="" class="">
                  <table class="table table-hover ">
                    <thead>
                      <tr>
                        <th scope="col" colspan="2">Search By Customer Name, Sale Order Number</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><input type="text" placeholder="" class="form-control key-rec-cust-pay"></td>
                        <td><button class="btn btn-primary btn-block text-uppercase find-rec-cust-pay">Find <i class="fa fa-search"></i></button></td>
                      </tr>
                    </tbody>
                  </table>
                </form>
              </div>
            </div>
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12" style="width: 69.3em;overflow-x: auto; white-space: nowrap;">
                <form action="" class="stc-view-cust-payment-form">
                  <table class="table table-hover ">
                    <thead>
                      <tr>
                        <th scope="col">Sale Order No.</th>
                        <th scope="col">Sale Order Date</th>
                        <th scope="col" style="width: 20%;">Customer Name</th>
                        <th scope="col">Payment Date.</th> 
                        <th scope="col">Payment Category.</th> 
                        <th scope="col">Payment Type.</th>
                        <th scope="col">Payment Value</th>
                        <th scope="col">Notes.</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>Loading..</td>
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
    <?php include "footer.php";?>
    <script>
      $(document).ready(function(){
        $('.add-advance').on('click', function(e){
          e.preventDefault();
          $('.stc-advance').toggle(1000);
          $('.stc-regular').fadeOut(500);
          $('.stc-view').fadeOut(500);
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
      });

      $(document).ready(function(){    
        var key_pay=$('.key-rec-pay').val(); 
        // $(window).scroll(function(){                
        //   var position = $(window).scrollTop();
        //   var bottom = $(document).height() - $(window).height();
        //   if(key_pay==''){
        //     if( position == bottom ){
        //       var row = Number($('#stc_pay_curr_row').val());
        //       var allcount = Number($('#stc_pay_all_row').val());
        //       var rowperpage = 10;
        //       row = row + rowperpage;
        //       if(row <= allcount){
        //         $('#stc_pay_curr_row').val(row);
        //         $.ajax({
        //           url: 'asgard/payment_process.php',
        //           type: 'post',
        //           data: {
        //             row:row,
        //             go_through:1
        //           },
        //           success: function(response){
        //             // console.log(response);
        //             $(".growit:last").after(response).show().fadeIn("slow");
        //           }
        //         });
        //       }
        //     }
        //   }else{
        //     if( position == bottom ){
        //       var row = Number($('#stc_pay_curr_row').val());
        //       var allcount = Number($('#stc_pay_all_row').val());
        //       var rowperpage = 10;
        //       row = row + rowperpage;
        //       if(row <= allcount){
        //         $('#stc_pay_curr_row').val(row);
        //         $.ajax({
        //           url: 'asgard/payment_process.php',
        //           type: 'post',
        //           data: {
        //             row:row,
        //             key_pay:key_pay,
        //             go_through_again:1
        //           },
        //           success: function(response){
        //             // console.log(response);
        //             $(".growit:last").after(response).show().fadeIn("slow");
        //           }
        //         });
        //       }
        //     }
        //   }
        // });

        load_customer_payments();
        function load_customer_payments(){
          $.ajax({
            url: 'asgard/payment_process.php',
            type: 'post',
            data: {
              load_cust_pay:1
            },
            dataType: 'JSON',
            success: function(response){
              // console.log(response);
              // alert(response);
              $('.stc-view-cust-payment-form').html(response);
            }
          });
        }

        $('body').delegate('.find-rec-cust-pay', 'click', function(e){
          e.preventDefault();
          var key_pay=$('.key-rec-cust-pay').val(); 
            $.ajax({
              url: 'asgard/payment_process.php',
              type: 'post',
              data: {
                key_pay:key_pay,
                get_rec_from_cust_key:1
              },
              success: function(response){
                console.log(response);
                // alert(response);
                $('.stc-view-cust-payment-form').html(response);
              }
            });
        });
      });
    </script>
  </body>
</html>
