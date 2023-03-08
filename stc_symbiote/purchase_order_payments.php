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
    <title>Purchase Order Payments - STC</title>
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

    <div class="container-fluid tm-mt-big tm-mb-big animated flipInY">
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="page-title-right">
                  <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="erp.php" class="text-uppercase" value="">ERP</a></li>
                    <li class="breadcrumb-item active"><a href="#" class="active" value="">Purchase Order Payment</a></li>
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
                <label class="btn btn-default text-uppercase"><a href="#" class="btn btn-success btn-block text-uppercase add-advance" value=""> + Advance Payment</a></label>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <label class="btn btn-default text-uppercase"><a href="#" class="btn btn-success btn-block text-uppercase add-regular" value=""> + Regular Payment</a></label>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <!-- advance payment -->
        <div style="display: none;" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-advance">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">PO Payments Advance</h2> <a class="stc_uppp" href="#"><i class="fas fa-arrow-up"></i></a>
                <p style="color: #ff2d2d; font-size: 15px; font-weight: bold;">* Labels fields are automated.  </p>
              </div>
            </div>
          <form action="#" class="stc-add-advance-po-payment-form"> 
            <div class="row stc-po-payments-point-row">
              <div class="col-xl-6 col-md-6 col-sm-12"> 
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Advance Payment Number
                  </label>
                  <input
                    id="apnumbershow"
                    name="apnumbershow"
                    type="text"
                    placeholder="Advance Payment Number"
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
                    >Advance Payment Date
                  </label>
                  <input
                    id="expire_date"
                    name="apdateshow"
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
                    id="apmerchantshow"
                    class="custom-select stc-select-vendor"
                    name="apmerchantshow"
                  >
                  </select>
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Purchase Order No
                  </label>
                  <select
                    id="apponumbershow"
                    class="custom-select stc-select-order"
                    name="apponumbershow"
                  ><option value="NA">Please Select Merchant First!!!</option>
                  </select>
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >*Purchase Order Date
                  </label>
                  <input
                    id="expire_date"
                    name="appodateshow"
                    type="text"
                    placeholder="PO Date"
                    class="form-control validate appodateshow"
                    disabled
                    style="background: #45d44a;font-size: 15px;color: black;font-weight: bold;"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Proforma Invoice Number
                  </label>
                  <input
                    id="stcapinvonumber"
                    name="stcapinvonumber"
                    type="text"
                    placeholder="Invoice Number"
                    class="form-control validate"
                    style="background: #45d44a;font-size: 15px;color: black;font-weight: bold;"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Proforma Invoice Date
                  </label>
                  <input
                    id="expire_date"
                    name="stcapinvodate"
                    type="text"
                    placeholder="Invoice Date Like 00/00/0000"
                    class="form-control validate"
                    style="background: #45d44a;font-size: 15px;color: black;font-weight: bold;"
                  />
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
                    name="stcappaymenttype"
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
                    >Payment Refrence No
                  </label>
                  <input
                    id="stcaprefnumber"
                    name="stcaprefnumber"
                    type="text"
                    placeholder="Advance Payment Refrence"
                    class="form-control validate"                    
                    style="background: #45d44a;font-size: 15px;color: black;font-weight: bold;"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Amount
                  </label>
                  <input
                    id="stcapamount"
                    name="stcapamount"
                    type="text"
                    placeholder="Amount"
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
                    id="apdueamountshow"
                    name="apdueamountshow"
                    type="text"
                    placeholder="Due Amount"
                    class="form-control validate"
                    style="background: #45d44a;font-size: 15px;color: black;font-weight: bold;"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Charges Basic Amount
                  </label>
                  <input
                    id="stcapchargesamount"
                    name="stcapchargesamount"
                    type="text"
                    placeholder="Amount"
                    class="form-control validate"
                    style="background: #45d44a;font-size: 15px;color: black;font-weight: bold;"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Charges GST
                  </label>
                  <select
                    class="custom-select stc-select-payment-type"
                    name="stcapchargesgst"
                  >
                  <option value="28">28%</option>
                  <option value="18">18%</option>
                  <option value="12">12%</option>
                  <option value="5">5%</option>
                  <option value="0">0%</option>
                  </select>
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
                    id="stcaptandc"
                    name="stcaptandc"
                    placeholder="Notes"
                    style="background: #45d44a;font-size: 15px;color: black;font-weight: bold;"
                  ></textarea>
                </div>
              </div>
              <div class="col-12">
                <input type="hidden" name="stcapsave">
                <input type="submit" class="btn btn-primary btn-block text-uppercase" value="Save">
              </div>
            </div>
          </form>
          </div>
          <!-- call advance payment -->
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto" style="width: 69.3em;overflow-x: auto; white-space: nowrap;">
            <div class="row">
              <div class="col-sm-12 call-advance-payment-records">
                
              </div>
            </div>
          </div>
        </div>

        <!-- regular payment -->
        <div style="display: none;" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-regular">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">PO Payments Regular</h2> <a class="stc_uppp" href="#"><i class="fas fa-arrow-up"></i></a>
                <p style="color: #ff2d2d; font-size: 15px; font-weight: bold;">* Labels fields are automated.  </p>
              </div>
            </div>
            <form action="" class="stc-add-regular-po-payment-form"> 
              <div class="row stc-po-payments-point-row">
                <div class="col-xl-6 col-md-6 col-sm-12">
                  <div class="form-group mb-3">
                    <label
                      for="name"
                      >Regular Payment Number *
                    </label>
                    <input
                      id="gtonumbershow"
                      name="stcmername"
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
                      >Regular Payment Date *
                    </label>
                    <input
                      id="reg_date"
                      name="apdateshow"
                      type="text"
                      value="<?php echo date('l jS \ F Y h:i:s A');?>"
                      class="form-control validate"
                      disabled
                      style="background: #45d44a;font-size: 15px;color: black;font-weight: bold;"
                    />
                  </div>
                </div>
                <div class="col-xl-12 col-md-12 col-sm-12">
                  <div class="form-group mb-3">
                    <label
                      for="name"
                      >Merchant
                    </label>
                    <select
                      id="stcrp_merchant"
                      class="custom-select stc-select-vendor"
                      name="stcrp_merchant"
                    >
                    </select>
                  </div>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-12">
                  <div class="form-group mb-3">
                    <label
                      for="name"
                      >Invoice Number
                    </label>
                    <select
                      id="stcrp_invonumber"
                      class="custom-select"
                      name="stcrp_invonumber"
                    ><option>Please Select Merchant First</option>
                    </select>
                  </div>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-12">
                  <div class="form-group mb-3">
                    <label
                      for="name"
                      >Invoice Date *
                    </label>
                    <input
                      id="stcrp_invodate"
                      name="stcrp_invodate"
                      type="text"
                      placeholder="Invoice Date"
                      class="form-control validate"
                      disabled
                      style="background: #45d44a;font-size: 15px;color: black;font-weight: bold;"
                    />
                  </div>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-12">
                  <div class="form-group mb-3">
                    <label
                      for="name"
                      >GRN Number *
                    </label>
                    <input
                      id="stcrp_grnnumber"
                      name="stcrp_grnnumber"
                      type="hidden"
                      placeholder="GRN Number"
                      class="form-control validate"
                    />
                    <input
                      type="text"
                      placeholder="GRN Number"
                      class="form-control validate stcrp_grnnumber"
                      disabled
                      style="background: #45d44a;font-size: 15px;color: black;font-weight: bold;"
                    />
                  </div>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-12">
                  <div class="form-group mb-3">
                    <label
                      for="name"
                      >GRN Date *
                    </label>
                    <input
                      id="stcrp_grndate"
                      name="stcrp_grndate"
                      type="text"
                      placeholder="GRN Date"
                      class="form-control validate"
                      disabled
                      style="background: #45d44a;font-size: 15px;color: black;font-weight: bold;"
                    />
                  </div>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-12">
                  <div class="form-group mb-3">
                    <label
                      for="name"
                      >PO Number *
                    </label>
                    <input
                      id="stcrp_ponumber"
                      name="stcrp_ponumber"
                      type="text"
                      placeholder="GRN Number"
                      class="form-control validate"
                      disabled
                      style="background: #45d44a;font-size: 15px;color: black;font-weight: bold;"
                    />
                  </div>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-12">
                  <div class="form-group mb-3">
                    <label
                      for="name"
                      >PO Date *
                    </label>
                    <input
                      id="stcrp_podate"
                      name="stcrp_podate"
                      type="text"
                      placeholder="GRN Date"
                      class="form-control validate"
                      disabled
                      style="background: #45d44a;font-size: 15px;color: black;font-weight: bold;"
                    />
                  </div>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-12">
                  <div class="form-group mb-3">
                    <label
                      for="name"
                      >Invoice Basic Amount *
                    </label>
                    <input
                      id="stcrp_grnbscamount"
                      name="stcrp_grnbscamount"
                      type="text"
                      placeholder="Basic Amount"
                      class="form-control validate"
                      disabled
                      style="background: #45d44a;font-size: 15px;color: black;font-weight: bold;"
                    />
                  </div>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-12">
                  <div class="form-group mb-3">
                    <label
                      for="name"
                      >Due Basic Amount Include GST *
                    </label>
                    <input
                      id="stcrp_grnbscamountincgst"
                      name="stcrp_grnbscamountincgst"
                      type="text"
                      placeholder="Amount Including GST"
                      class="form-control validate"
                      disabled
                      style="background: #45d44a;font-size: 15px;color: black;font-weight: bold;"
                    />
                  </div>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-12">
                  <div class="form-group mb-3">
                    <label
                      for="name"
                      >Paid Amount *
                    </label>
                    <input
                      id="stcrp_paidamount"
                      name="stcrp_paidamount"
                      type="text"
                      placeholder="Paid Amount"
                      class="form-control validate"
                      disabled
                      style="background: #45d44a;font-size: 15px;color: black;font-weight: bold;"
                    />
                  </div>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-12">
                  <div class="form-group mb-3">
                    <label
                      for="name"
                      >Due Amount *
                    </label>
                    <input
                      id="stcrp_dueamount"
                      name="stcrp_dueamount"
                      type="text"
                      placeholder="Due Amount"
                      class="form-control validate"
                      disabled
                      style="background: #45d44a;font-size: 15px;color: black;font-weight: bold;"
                    />
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
                      name="stcrp_paymenttype"
                    ><option value="NA">Please Select Payment Type</option>
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
                      >Payment Refrence No
                    </label>
                    <input
                      id="stcrp_refrno"
                      name="stcrp_refrno"
                      type="text"
                      placeholder="Refrence"
                      class="form-control validate"
                      style="background: #45d44a;font-size: 15px;color: black;font-weight: bold;"
                    />
                  </div>
                </div>                
                <div class="col-xl-6 col-md-6 col-sm-12">
                  <div class="form-group mb-3">
                    <label
                      for="name"
                      >Amount
                    </label>
                    <input
                      id="stcrp_amount"
                      name="stcrp_amount"
                      type="text"
                      placeholder="Amount"
                      class="form-control validate"
                      style="background: #45d44a;font-size: 15px;color: black;font-weight: bold;"
                    />
                  </div>
                </div>
                <div class="col-xl-6 col-sm-12">
                  <div class="form-group mb-3">
                    <label
                      for=""
                      >Notes
                    </label>
                    <textarea
                      class="form-control validate"
                      rows="2"
                      id="stcrp_notes"
                      name="stcrp_notes"
                      placeholder="Notes"
                      style="background: #45d44a;font-size: 15px;color: black;font-weight: bold;"
                    ></textarea>
                  </div>
                </div>
                <div class="col-12">
                  <input type="hidden" name="stcsaverp">
                  <input type="submit" class="btn btn-primary btn-block text-uppercase" value="Save">
                </div>
              </div>
            </form>
          </div>

          <div class="tm-bg-primary-dark tm-block tm-block-h-auto" style="width: 69.3em;overflow-x: auto; white-space: nowrap;">
            <div class="row">
              <div class="col-sm-12 call-regular-payment-records">
                
              </div>
            </div>
          </div>
        </div>

        <!-- view All Payments against purchase Orders -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">Merchant Payments</h2>
              </div>
            </div>
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12" style="width: 69.3em;overflow-x: auto; white-space: nowrap;">
                <form action="" class="">
                  <table class="table table-hover ">
                    <thead>
                      <tr>
                        <th scope="col">From/<br>To</th>
                        <th scope="col" colspan="2">Search By Merchant Name, PO Number</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>
                            <?php 
                                $date = date("d-m-Y");
                                $newDate = date('Y-m-d', strtotime($date)); 
                                $effectiveDate = date('Y-m-d', strtotime("-3 months", strtotime($date)));
                            ?>   
                          <p><input type="date" value="<?php echo $effectiveDate;?>"  class="form-control begdate"></p>
                          <p><input type="date" value="<?php echo $newDate;?>"  class="form-control enddate"></p>
                          <p><a href="#" id="payprocdatefilt">
                              <i style="font-size: 20px;background: #0c0c1b;border-radius: 50%;padding: 5px;margin: 0;color: #77ffd3;float: right;" class="fa fa-arrow-down"></i>
                            </a>
                          </p>
                        </td>
                        <td><input type="text" placeholder="" class="form-control key-rec-pay"></td>
                        <td><button class="btn btn-primary btn-block text-uppercase find-rec-pay">Find <i class="fa fa-search"></i></button></td>
                      </tr>
                    </tbody>
                  </table>
                </form>
              </div>
            </div>
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12" style="width: 69.3em;overflow-x: auto; white-space: nowrap;">
                <form action="" class="stc-view-all-payment-form">
                  <table class="table table-hover ">
                      <tr>
                        <td>Loading..</td>
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
        var key_pay=$('.key-rec-pay').val();
        $('body').delegate('.find-rec-pay', 'click', function(e){
          e.preventDefault();
          var key_pay=$('.key-rec-pay').val(); 
          var popaybegdate=$('.begdate').val();
          var popayenddate=$('.enddate').val();
            $.ajax({
              url: 'asgard/payment_process.php',
              type: 'post',
              data: {
                key_pay:key_pay,
                get_rec_fro_key:1,
                popaybegdate:popaybegdate,
                popayenddate:popayenddate
              },
              success: function(response){
                // console.log(response);
                // alert(response);
                $('.stc-view-all-payment-form').html(response);
              }
            });
        });
      });
    </script>
  </body>
</html>
