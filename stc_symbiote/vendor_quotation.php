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
    <title>Merchant Quotation - STC</title>
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

    <!-- <style> .sp-quantity div { display: inline; }</style> -->
  </head>

  <body>
   <?php include "header.php";?>

    <div class="container-fluid tm-mt-big tm-mb-big animated bounceInRight">
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="page-title-right">
                  <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="erp.php" class="text-uppercase" value="">ERP</a></li>
                    <li class="breadcrumb-item active"><a href="#" class="active" value="">Merchant Quotation</a></li>
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
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <label class="btn btn-default text-uppercase"><a href="#" class="btn btn-success btn-block text-uppercase add-combo" value=""> <b>+</b> Create New Quotation</a></label>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <!-- Create order -->
        <div style="display: none;" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-add">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">Quotation</h2> <a id="stc_uppp" href="#"><i class="fas fa-arrow-up"></i></a>
              </div>
            </div>
            <div class="row stc-quotation-row">
              <div class="col-xl-6 col-md-6 col-sm-12">
                <form action="" class="stc-add-quotation-form"> 
                <div class="form-group mb-3">
                  <label
                    for="number"
                    >Quotation Number
                  </label>
                  <input
                    id="gtonumbershow"
                    name="stcquotemername"
                    type="text"
                    placeholder="Quotation Order Number"
                    class="form-control validate"
                    disabled
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Quotation Date
                  </label>
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
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Quotation Merchant Name : <span id="stc_vendor_quotation_edit"></span>
                  </label>
                  <select
                    id="stc_vendor_quotation"
                    class="custom-select stc-select-quote-merchant"
                    name="stcquotevendor"
                  >
                  </select>
                </div>
              </div>
              <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="col-12">
                  <h2 class="tm-block-title d-inline-block">Products</h2>
                </div>
                <input
                  id="searchbystcquotepdname"
                  name="stcquotepdname"
                  type="text"
                  placeholder="Product Name"
                  class="form-control validate"
                />
              </div>              
              <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="row stc-call-view-quote-product-row">
                </div>
              </div>

              <!-- line item table -->
              <div class="col-xl-12 col-md-12 col-sm-12 quotelineitem">
              </div>
              <div class="col-xl-12 col-md-12 col-sm-12 editquotelineitem">
              </div>
              <div class="col-6">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Terms & Conditions
                  </label>
                  <textarea
                    class="form-control validate"
                    rows="2"
                    id="stcquotetandc"
                    placeholder="Terms & Conditions"
                    required
                  ></textarea>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Notes
                  </label>
                  <textarea
                    class="form-control validate"
                    rows="2"
                    id="stcquotenotes"
                    placeholder="Enter a Notes/Remarks"
                    required
                  ></textarea>
                </div>
              </div>
              <div class="col-12">
                <!-- <button type="submit" class="btn btn-primary btn-block text-uppercase">For Quotation</button> -->
                <button type="submit" class="btn btn-primary btn-block text-uppercase stcsavequote">Save</button>
              </div>
            </div>
          </form>
          </div>
        </div>

        <!-- view Quotations -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">Merchant Quotations</h2>
              </div>
            </div>
            <div class="row stc-view-quote-row">
              <div class="col-xl-12 col-lg-12 col-md-12">
                <form action="" class="stc-view-quote-form">
                    <table class="table table-hover ">
                      <tr>
                        <td>
                          Loading.....
                        </td>
                      </tr>
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
        $('.add-combo').on('click', function(e){
          e.preventDefault();
          $('.stc-add').toggle(1000);
          $('.stc-view').fadeOut(500);
          // $('.stc-add-po-product-form')[0].reset();
        });

        $('#stc_uppp').on('click', function(e){
          e.preventDefault();
            $('.stc-view').toggle(1000);
            $('.stc-add').fadeOut(500);
        });

        $( function() {
          // $( "#expire_date" ).datepicker();
        } );

        $(".ddd").on("click", function(w) {
          w.preventDefault();
          var $button = $(this);
          var $input = $button.closest('.sp-quantity').find("input.quntity-input");
          $input.val((i, v) => Math.max(0, +v + 1 * $button.data('multi')));
        });
      });
    </script>
  </body>
</html>
