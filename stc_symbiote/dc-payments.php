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
                    <li class="breadcrumb-item active"><a href="#" class="active" value="">DC Invoice Payments</a></li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <!-- Customer payment -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-advance">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">DC Invoice Payments</h2>
                <p style="color: #ff2d2d; font-size: 15px; font-weight: bold;">* Labels fields are automated.  </p>
              </div>
            </div>
          <form action="#" class="stc-add-dcmerchant-po-payment-form"> 
            <div class="row stc-po-payments-point-row">
              <div class="col-xl-6 col-md-6 col-sm-12"> 
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Payment Number
                  </label>
                  <input
                    id="dcpnumbershow"
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
                    name="dcdateshow"
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
                    >Merchant Name
                  </label>
                  <select
                    id="stcdcmer"
                    class="custom-select stc-select-vendor"
                    name="stcdcmer"
                  >
                  </select>
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Merchant Invoice No
                  </label>
                  <select
                    id="invonodcshow"
                    class="custom-select stc-select-order"
                    name="invonodcshow"
                  ><option value="NA">Please Select Merchant First!!!</option>
                  </select>
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >*Merchant Invoice Date
                  </label>
                  <input
                    id="expire_date"
                    name="invodatedcshow"
                    type="text"
                    placeholder="Invoice Date"
                    class="form-control validate invodatedcshow"
                    disabled
                    style="background: #45d44a;font-size: 15px;color: black;font-weight: bold;"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >*Payment CatType
                  </label>
                  <select
                    class="custom-select"
                    id="stcdccatpaymenttype"
                    disabled
                  >
                  <option value="ADVANCE">Advance</option>
                  <option value="REGULAR" selected>Regular</option>
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
                    class="custom-select"
                    id="stcdcpaymenttype"
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
                    id="stcdcamount"
                    name="stcdcamount"
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
                    >*Dues(Total Amount)
                  </label>
                  <input
                    id="dcdueamountshow"
                    name="dcdueamountshow"
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
                    id="stcdctandc"
                    name="stcdctandc"
                    placeholder="Notes"
                    style="background: #45d44a;font-size: 15px;color: black;font-weight: bold;"
                  ></textarea>
                </div>
              </div>
              <div class="col-12" style="display: none;">
                <input type="hidden" name="stcsoupdate">
                <a class="btn btn-primary btn-block text-uppercase stcupdatecustp">Save</a>
              </div>
              <div class="col-12">
                <input type="hidden" name="stcsosave">
                <a class="btn btn-primary btn-block text-uppercase stcsavedcmerp">Save</a>
              </div>
              <!-- <div class="col-6">
                <a class="btn btn-primary btn-block text-uppercase stceditcustp">Edit</a>
              </div> -->
            </div>
          </form>
          </div>
        </div>
      </div>
    </div>
    <?php include "footer.php";?>
  </body>
</html>
