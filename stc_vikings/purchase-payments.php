<?php  
// Include authentication helper
require_once 'kattegat/auth_helper.php';

// Check authentication using the new hybrid system
STCAuthHelper::checkAuth();$page_code=404;
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
    <title>Purchase Payments - STC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="">
    <meta name="msapplication-tap-highlight" content="no">
    <!-- Latest compiled and minified CSS -->
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
                                    <span>View All Payments</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-content-2">
                                    <span>Advance Payment</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-3" data-toggle="tab" href="#tab-content-3">
                                    <span>Regular Payment</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-4" data-toggle="tab" href="#tab-content-4">
                                    <span>DC Payment</span>
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
                                              >Merchant Payments
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row stc-view-product-row">
                                  <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <form action="" class="stc-view-product-form">
                                          <table class="table table-hover ">
                                            <thead>
                                              <tr>
                                                <th scope="col">From/<br>To</th>
                                                <th scope="col" width="30%">By Merchant</th>
                                                <th scope="col" width="30%">By PO Number</th>
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
                                                    id="stc-po-merchant-in"
                                                    class="custom-select form-control stc-select-vendor"
                                                    name="stcvendor"
                                                  >
                                                  </select>
                                                </td>
                                                <td>
                                                  <input 
                                                    type="number" 
                                                    id="stc-po-number-finder-in" 
                                                    class="form-control"
                                                    placeholder="PO Number" 
                                                  >
                                                </td>
                                                <td>
                                                  <select 
                                                    class="custom-select form-control stc-po-status-in"
                                                    >
                                                    <option value="NA">Select Status</option>
                                                    <option value="process">Process</option>
                                                    <option value="accepted">Accepted</option>
                                                    <option value="completed">Completed</option>
                                                    <option value="cancel">Cancel</option>
                                                  </select>
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
                                    <div class="card-border mb-3 card card-body border-success" style="width: 115.9em;overflow-x: auto; white-space: nowrap; font-size:10px;">
                                      <form action="" class="stc-view-purchase-order-form">
                                          <table class="table table-hover table-bordered table-dark stc-purchase-view-table">
                                            <thead>
                                              <tr>
                                                <th scope="col">Merchant Name</th>
                                                <th scope="col">PO No. <br>PO Date</th>
                                                <th scope="col">GRN No.<br>GRN Date</th>
                                                <th scope="col">Invoice No.</br>Invoice Date</th>
                                                <th scope="col">AP No.</br>AP Date</th>
                                                <th scope="col">AP Value</th>
                                                <th scope="col">RP No. </br>RP Date</th>
                                                <th scope="col">RP Value</th>
                                                <th scope="col">Invoice Basic Amount</th>
                                                <th scope="col">Invoice Total Amount</th>
                                                <th scope="col">PO Value</th>
                                                <th scope="col">Paid Amount</th>
                                                <th scope="col">Balance Amount</th>
                                              </tr>
                                            </thead>
                                            <tbody class="stc-call-view-po-row growit">
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
                            <div class="tab-pane tabs-animation fade" id="tab-content-2" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >Advance Payment
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                  <div class="col-xl-6 col-md-6 col-sm-6">
                                    <form action="#" class="stc-add-advance-po-payment-form"> 
                                    <div class="card-border mb-3 card card-body border-success">
                                      <h5
                                        for="name"
                                        >Advance Payment Number
                                      </h5>
                                      <input
                                        id="apnumbershow"
                                        name="apnumbershow"
                                        type="text"
                                        placeholder="Advance Payment Number"
                                        class="form-control validate"
                                        disabled
                                      />
                                    </div>
                                  </div>
                                  <div class="col-xl-6 col-md-6 col-sm-6">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <h5
                                        for=""
                                        >Advance Payment Date
                                      </h5>
                                      <input
                                        id="expire_date"
                                        name="apdateshow"
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
                                        >Merchant Name
                                      </h5>
                                      <select
                                        id="apmerchantshow"
                                        class="custom-select stc-select-vendor"
                                        name="apmerchantshow"
                                      >
                                      </select>
                                    </div>
                                  </div>
                                  <div class="col-xl-6 col-md-6 col-sm-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <h5
                                        for="name"
                                        >Purchase Order No
                                      </h5>
                                      <select
                                        id="apponumbershow"
                                        class="custom-select stc-select-order"
                                        name="apponumbershow"
                                      ><option value="NA">Please Select Merchant First!!!</option>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="col-xl-6 col-md-6 col-sm-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <h5
                                        for="name"
                                        >*Purchase Order Date
                                      </h5>
                                      <input
                                        id="expire_date"
                                        name="appodateshow"
                                        type="text"
                                        placeholder="PO Date"
                                        class="form-control validate appodateshow"
                                        disabled
                                      />
                                    </div>
                                  </div>
                                  <div class="col-xl-6 col-md-6 col-sm-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <h5
                                        for="name"
                                        >Proforma Invoice Number
                                      </h5>
                                      <input
                                        id="stcapinvonumber"
                                        name="stcapinvonumber"
                                        type="text"
                                        placeholder="Invoice Number"
                                        class="form-control validate"
                                      />
                                    </div>
                                  </div>
                                  <div class="col-xl-6 col-md-6 col-sm-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <h5
                                        for="name"
                                        >Proforma Invoice Date
                                      </h5>
                                      <input
                                        id="expire_date"
                                        name="stcapinvodate"
                                        type="text"
                                        placeholder="Invoice Date Like 00/00/0000"
                                        class="form-control validate"
                                      />
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
                                    <div class="card-border mb-3 card card-body border-success">
                                      <h5
                                        for="name"
                                        >Payment Refrence No
                                      </h5>
                                      <input
                                        id="stcaprefnumber"
                                        name="stcaprefnumber"
                                        type="text"
                                        placeholder="Advance Payment Refrence"
                                        class="form-control validate"
                                      />
                                    </div>
                                  </div>
                                  <div class="col-xl-6 col-md-6 col-sm-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <h5
                                        for="name"
                                        >Amount
                                      </h5>
                                      <input
                                        id="stcapamount"
                                        name="stcapamount"
                                        type="text"
                                        placeholder="Amount"
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
                                        id="apdueamountshow"
                                        name="apdueamountshow"
                                        type="text"
                                        placeholder="Due Amount"
                                        class="form-control validate"
                                        disabled
                                      />
                                    </div>
                                  </div>
                                  <div class="col-xl-6 col-md-6 col-sm-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <h5
                                        for="name"
                                        >Charges Basic Amount
                                      </h5>
                                      <input
                                        id="stcapchargesamount"
                                        name="stcapchargesamount"
                                        type="text"
                                        placeholder="Amount"
                                        class="form-control validate"
                                      />
                                    </div>
                                  </div>
                                  <div class="col-xl-6 col-md-6 col-sm-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <h5
                                        for="name"
                                        >Charges GST
                                      </h5>
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
                                  <div class="col-xl-12 col-md-12 col-sm-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <h5
                                        for=""
                                        >Notes
                                      </h5>
                                      <textarea
                                        class="form-control validate"
                                        rows="2"
                                        id="stcaptandc"
                                        name="stcaptandc"
                                        placeholder="Enter Details About This Payment."
                                      ></textarea>
                                    </div>
                                  </div>
                                  <div class="col-xl-12 col-md-12 col-sm-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <input type="hidden" name="stcapsave">
                                      <input type="submit" class="btn btn-primary btn-block text-uppercase" value="Save">
                                    </div>
                                    </form>
                                  </div>
                                </div>
                            </div>
                            <div class="tab-pane tabs-animation fade" id="tab-content-3" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >Regular Payment
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                  <div class="col-xl-6 col-md-6 col-sm-12">
                                    <form action="" class="stc-add-regular-po-payment-form"> 
                                    <div class="card-border mb-3 card card-body border-success">
                                      <h5
                                        for="name"
                                        >Regular Payment Number *
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
                                        for=""
                                        >Regular Payment Date *
                                      </h5>
                                      <input
                                        id="reg_date"
                                        name="apdateshow"
                                        type="text"
                                        value="<?php echo date('l jS \ F Y h:i:s A');?>"
                                        class="form-control validate"
                                        disabled
                                      />
                                    </div>
                                  </div>
                                  <div class="col-xl-12 col-md-12 col-sm-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <h5
                                        for="name"
                                        >Merchant
                                      </h5>
                                      <select
                                        id="stcrp_merchant"
                                        class="custom-select stc-select-vendor"
                                        name="stcrp_merchant"
                                      >
                                      </select>
                                    </div>
                                  </div>
                                  <div class="col-xl-6 col-md-6 col-sm-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <h5
                                        for="name"
                                        >Invoice Number
                                      </h5>
                                      <select
                                        id="stcrp_invonumber"
                                        class="custom-select"
                                        name="stcrp_invonumber"
                                      ><option>Please Select Merchant First</option>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="col-xl-6 col-md-6 col-sm-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <h5
                                        for="name"
                                        >Invoice Date *
                                      </h5>
                                      <input
                                        id="stcrp_invodate"
                                        name="stcrp_invodate"
                                        type="text"
                                        placeholder="Invoice Date"
                                        class="form-control validate"
                                        disabled
                                      />
                                    </div>
                                  </div>
                                  <div class="col-xl-6 col-md-6 col-sm-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <h5
                                        for="name"
                                        >GRN Number *
                                      </h5>
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
                                      />
                                    </div>
                                  </div>
                                  <div class="col-xl-6 col-md-6 col-sm-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <h5
                                        for="name"
                                        >GRN Date *
                                      </h5>
                                      <input
                                        id="stcrp_grndate"
                                        name="stcrp_grndate"
                                        type="text"
                                        placeholder="GRN Date"
                                        class="form-control validate"
                                        disabled
                                      />
                                    </div>
                                  </div>
                                  <div class="col-xl-6 col-md-6 col-sm-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <h5
                                        for="name"
                                        >PO Number *
                                      </h5>
                                      <input
                                        id="stcrp_ponumber"
                                        name="stcrp_ponumber"
                                        type="text"
                                        placeholder="PO Number"
                                        class="form-control validate"
                                        disabled
                                      />
                                    </div>
                                  </div>
                                  <div class="col-xl-6 col-md-6 col-sm-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <h5
                                        for="name"
                                        >PO Date *
                                      </h5>
                                      <input
                                        id="stcrp_podate"
                                        name="stcrp_podate"
                                        type="text"
                                        placeholder="PO Date"
                                        class="form-control validate"
                                        disabled
                                      />
                                    </div>
                                  </div>
                                  <div class="col-xl-6 col-md-6 col-sm-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <h5
                                        for="name"
                                        >Invoice Amount *
                                      </h5>
                                      <input
                                        id="stcrp_grnbscamountincgst"
                                        name="stcrp_grnbscamountincgst"
                                        type="text"
                                        placeholder="Amount Including GST"
                                        class="form-control validate"
                                        disabled
                                      />
                                    </div>
                                  </div>
                                  <div class="col-xl-6 col-md-6 col-sm-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <h5
                                        for="name"
                                        >Total Paid Amount For This PO *
                                      </h5>
                                      <input
                                        id="stcrp_paidamount"
                                        name="stcrp_paidamount"
                                        type="text"
                                        placeholder="Paid Amount"
                                        class="form-control validate"
                                        disabled
                                      />
                                    </div>
                                  </div>
                                  <div class="col-xl-6 col-md-6 col-sm-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <h5
                                        for="name"
                                        >Due Amount *
                                      </h5>
                                      <input
                                        id="stcrp_dueamount"
                                        name="stcrp_dueamount"
                                        type="text"
                                        placeholder="Due Amount"
                                        class="form-control validate"
                                        disabled
                                      />
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
                                    <div class="card-border mb-3 card card-body border-success">
                                      <h5
                                        for="name"
                                        >Payment Refrence No
                                      </h5>
                                      <input
                                        id="stcrp_refrno"
                                        name="stcrp_refrno"
                                        type="text"
                                        placeholder="Refrence"
                                        class="form-control validate"
                                      />
                                    </div>
                                  </div>                
                                  <div class="col-xl-6 col-md-6 col-sm-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <h5
                                        for="name"
                                        >Amount
                                      </h5>
                                      <input
                                        id="stcrp_amount"
                                        name="stcrp_amount"
                                        type="number"
                                        placeholder="Amount"
                                        class="form-control validate"
                                      />
                                    </div>
                                  </div>
                                  <div class="col-xl-12 col-sm-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <h5
                                        for=""
                                        >Notes
                                      </h5>
                                      <textarea
                                        class="form-control validate"
                                        rows="2"
                                        id="stcrp_notes"
                                        name="stcrp_notes"
                                        placeholder="Notes"
                                      ></textarea>
                                    </div>
                                  </div>
                                  <div class="col-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <input type="hidden" name="stcsaverp">
                                      <input type="submit" class="btn btn-primary btn-block text-uppercase" value="Save">
                                    </div>
                                    </form>
                                  </div>
                                </div>
                            </div>
                            <div class="tab-pane tabs-animation fade" id="tab-content-4" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >Direct Challan Payment
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <form action="#" class="stc-add-dcmerchant-po-payment-form"> 
                                  <div class="row stc-po-payments-point-row">
                                    <div class="col-xl-6 col-md-6 col-sm-12"> 
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="name"
                                          >Payment Number
                                        </h5>
                                        <input
                                          id="dcpnumbershow"
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
                                          name="dcdateshow"
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
                                          >Merchant Name
                                        </h5>
                                        <select
                                          id="stcdcmer"
                                          class="custom-select"
                                          name="stcdcmer"
                                        >
                                        <?php 
                                          include_once("../MCU/db.php");
                                          $getmerchantdcqry=mysqli_query($con, "
                                            SELECT DISTINCT
                                                `stc_merchant_id`,
                                                `stc_merchant_name`
                                            FROM
                                                `stc_sale_product`
                                            INNER JOIN 
                                                `stc_merchant` 
                                            ON 
                                                `stc_merchant_id`=`stc_sale_product_dc_merchant`
                                            ORDER BY 
                                                `stc_merchant_name` 
                                            ASC 
                                          ");

                                          foreach($getmerchantdcqry as $getmerchantdcrow){
                                            echo "<option value='".$getmerchantdcrow['stc_merchant_id']."'>".$getmerchantdcrow['stc_merchant_name']."</option>";
                                          }
                                        ?>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="name"
                                          >Merchant Invoice No
                                        </h5>
                                        <select
                                          id="invonodcshow"
                                          class="custom-select stc-select-order"
                                          name="invonodcshow"
                                        ><option value="NA">Please Select Merchant First!!!</option>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="name"
                                          >Merchant Invoice Date *
                                        </h5>
                                        <input
                                          id="expire_date"
                                          name="invodatedcshow"
                                          type="text"
                                          placeholder="Invoice Date"
                                          class="form-control validate invodatedcshow"
                                          disabled
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="name"
                                          >*Payment Category
                                        </h5>
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
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="name"
                                          >Payment Type
                                        </h5>
                                        <select
                                          class="custom-select"
                                          id="stcdcpaymenttype"
                                        >
                                        <option value="RTGS">RTGS</option>
                                        <option value="NEFT" selected>NEFT</option>
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
                                          id="stcdcamount"
                                          name="stcdcamount"
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
                                          >Dues(Total Amount) *
                                        </h5>
                                        <input
                                          id="dcdueamountshow"
                                          name="dcdueamountshow"
                                          type="text"
                                          placeholder="Due Amount"
                                          class="form-control validate"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for=""
                                          >Notes
                                        </h5>
                                        <textarea
                                          class="form-control validate"
                                          rows="2"
                                          id="stcdctandc"
                                          name="stcdctandc"
                                          placeholder="Notes"
                                        ></textarea>
                                      </div>
                                    </div>
                                    <div class="col-12" style="display: none;">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <input type="hidden" name="stcsoupdate">
                                        <a class="btn btn-primary btn-block text-uppercase stcupdatecustp">Save</a>
                                      </div>
                                    </div>
                                    <div class="col-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <input type="hidden" name="stcsosave">
                                        <a class="btn btn-primary btn-block text-uppercase stcsavedcmerp">Save</a>
                                      </div>
                                    </div>
                                  </div>
                                </form>
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
          // call merchant for purchase
          stc_vendor_on_purchase_page();
          function stc_vendor_on_purchase_page(){
            $.ajax({
              url       : "kattegat/ragnar_payments.php",
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

          var jsbegdate='';
          var jsenddate='';
          var jsmerchantid='';
          var jsponumber='';
          var jsstatus='';
          var jsbegvalue='';
          var jsendvalue='';
          // filter po
          // by date
          $('body').delegate('#purchaseproddatefilt', 'click', function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jsmerchantid=$("#stc-po-merchant-in").val();
            jsponumber=$("#stc-po-number-finder-in").val();
            jsstatus=$(".stc-po-status-in").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            stc_filter_purchase_order(jsbegdate, jsenddate, jsmerchantid, jsponumber, jsstatus, jsbegvalue, jsendvalue);
          });

          // by merchant id
          $("#stc-po-merchant-in").change(function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jsmerchantid=$("#stc-po-merchant-in").val();
            jsponumber=$("#stc-po-number-finder-in").val();
            jsstatus=$(".stc-po-status-in").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            stc_filter_purchase_order(jsbegdate, jsenddate, jsmerchantid, jsponumber, jsstatus, jsbegvalue, jsendvalue);
          });

          // by po number
          $("#stc-po-number-finder-in").on('keyup', function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jsmerchantid=$("#stc-po-merchant-in").val();
            jsponumber=$("#stc-po-number-finder-in").val();
            jsstatus=$(".stc-po-status-in").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            stc_filter_purchase_order(jsbegdate, jsenddate, jsmerchantid, jsponumber, jsstatus, jsbegvalue, jsendvalue);
          });

          // by status
          $(".stc-po-status-in").change(function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jsmerchantid=$("#stc-po-merchant-in").val();
            jsponumber=$("#stc-po-number-finder-in").val();
            jsstatus=$(".stc-po-status-in").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            stc_filter_purchase_order(jsbegdate, jsenddate, jsmerchantid, jsponumber, jsstatus, jsbegvalue, jsendvalue);
          });

          // paging before search
          $('body').delegate('.begbuttoninvsearch', 'click', function(e){
            e.preventDefault();
            begvalue=$('.begvalueinputsearch').val();
            endvalue=$('.endvalueinputsearch').val();
            if(endvalue==0){
              alert("Seriously!!!");
            }else{
              outbegvalueinputted= (+begvalue) - 20;
              outendvalueinputted= (+endvalue) - 20;
              jsbegdate=$('.begdate').val();
              jsenddate=$('.enddate').val();
              jsmerchantid=$("#stc-po-merchant-in").val();
              jsponumber=$("#stc-po-number-finder-in").val();
              jsstatus=$(".stc-po-status-in").val();
              jsbegvalue=outbegvalueinputted;
              jsendvalue=outendvalueinputted;
              stc_filter_purchase_order(jsbegdate, jsenddate, jsmerchantid, jsponumber, jsstatus, jsbegvalue, jsendvalue);
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
              jsmerchantid=$("#stc-po-merchant-in").val();
              jsponumber=$("#stc-po-number-finder-in").val();
              jsstatus=$(".stc-po-status-in").val();
              jsbegvalue=outbegvalueinputted;
              jsendvalue=outendvalueinputted;
              stc_filter_purchase_order(jsbegdate, jsenddate, jsmerchantid, jsponumber, jsstatus, jsbegvalue, jsendvalue);
            }
          });

          // filter function
          function stc_filter_purchase_order(jsbegdate, jsenddate, jsmerchantid, jsponumber, jsstatus, jsbegvalue, jsendvalue){
            $.ajax({
              url     : "kattegat/ragnar_payments.php",
              method  : "post",
              data    : {
                stcpurchaseaction:1,
                phpbegdate:jsbegdate, 
                phpenddate:jsenddate, 
                phpmerchantid:jsmerchantid, 
                phpponumber:jsponumber, 
                phpstatus:jsstatus,
                phpbegvalue:jsbegvalue,
                phpendvalue:jsendvalue
              },
              // dataType : 'JSON',
              success : function(data){
                // console.log(data);
                $('.stc-call-view-po-row').html(data);
              }
            });
          }

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
    </script>
    <script>
        $(document).ready(function(){
          // call po on change merchant in advance payment section 
          $('#apmerchantshow').on('change', function(e){
            e.preventDefault();
            var js_merchant_id=$(this).val();
            $.ajax({
              url       : "kattegat/ragnar_payments.php",
              method    : "post",
              data      : {
                call_po_on_choose_merchant:1,
                merchant_id:js_merchant_id
              },
              dataType  : 'JSON',
              success   : function(data){
                // console.log(data);
                $('.stc-select-order').html(data);
              }
            });
          });

          // call date and due amount on po change  
          $('#apponumbershow').on('change', function(e){
            e.preventDefault();
            var js_po_number=$(this).val();
            $.ajax({
              url       : "kattegat/ragnar_payments.php",
              method    : "post",
              data      : {
                call_po_date_on_choose_merchant:1,
                po_number:js_po_number
              },
              dataType  : 'JSON',
              success   : function(data){
                // console.log(data);
                $('.appodateshow').val(data['order_date']);
                $('#apdueamountshow').val(data['due_amount']);
              }
            });
          });

          // on advance payment in db
          $('.stc-add-advance-po-payment-form').on('submit', function(e){
            e.preventDefault();
            $.ajax({
              url         : "kattegat/ragnar_payments.php",
              method      : "post",
              data        : new FormData(this),
              contentType : false,
              processData : false,
              // dataType : 'JSON',
              success     : function (argument) {
                // console.log(argument);
                  alert(argument);
                $('.stc-add-advance-po-payment-form')[0].reset();
              }
            });
          });
        });
    </script>
    <script>
      $(document).ready(function(){
        // call invoice on choosse of merchant
        $('#stcrp_merchant').on('change', function(e){
          e.preventDefault();
          var js_merchant_id=$(this).val();
          $.ajax({
            url         : "kattegat/ragnar_payments.php",
            method      : "post",
            data        : {
              call_invo_on_choose_merchant:1,
              merchant_id:js_merchant_id
            },
            dataType    : 'JSON',
            success     : function(data){
              // console.log(data);
              $('#stcrp_invonumber').html(data);
            }
          });
        });

        // call data on change invoice number in regular payment section
        $('#stcrp_invonumber').on('change', function(e){
          e.preventDefault();
          // alert("js_notes");
          var js_invo_number=$(this).val();
          var js_merchant_id=$('#stcrp_merchant').val();
          $.ajax({
            url         : "kattegat/ragnar_payments.php",
            method      : "post",
            data        : {
              call_rec_for_reg_pay:1,
              merchant_id:js_merchant_id,
              invo_number:js_invo_number
            },
            dataType    : 'JSON',
            success     : function(data){
              // console.log(data);
              $('#stcrp_invodate').val(data['invo_date']);
              $('#stcrp_grnnumber').val(data['hidden_grn_number']);
              $('.stcrp_grnnumber').val(data['grn_number']);
              $('#stcrp_grndate').val(data['grn_date']);
              $('#stcrp_ponumber').val(data['po_number']);
              $('#stcrp_podate').val(data['po_date']);
              $('#stcrp_grnbscamountincgst').val(data['due_gst_amount']);
              $('#stcrp_paidamount').val(data['paid_amount']);
              $('#stcrp_dueamount').val(data['due_amount']);
            }
          });
        });

        // on submit of regular payment on db
        $('.stc-add-regular-po-payment-form').on('submit', function(e){
          e.preventDefault();
          $.ajax({
            url         : "kattegat/ragnar_payments.php",
            method      : "post",
            data        : new FormData(this),
            contentType : false,
            processData : false,
            // dataType : 'JSON',
            success     : function (argument) {
              // console.log(argument);
                alert(argument);
              $('.stc-add-regular-po-payment-form')[0].reset();
            }
          });
        });
        });
    </script>
    <script>
      $(document).ready(function(){
        // call so on change merchant in advance payment section 
        $('#stcdcmer').on('change', function(e){
          e.preventDefault();
          var js_merchant_id=$(this).val();
          $.ajax({
            url         : "kattegat/ragnar_payments.php",
            method      : "post",
            data        : {
              call_dc_on_choose_mer:1,
              dc_merchant_id:js_merchant_id
            },
            dataType    : 'JSON',
            success     : function(data){
              // console.log(data);
              $('#invonodcshow').html(data);
            }
          });
        });

        // call date and due amount on po change  
        $('#invonodcshow').on('change', function(e){
          e.preventDefault();
          var js_dc_number=$(this).val();
          $.ajax({
            url         : "kattegat/ragnar_payments.php",
            method      : "post",
            data        : {
              call_dc_date_on_choose_merchant:1,
              dc_number:js_dc_number
            },
            dataType    : 'JSON',
            success     : function(data){
              // console.log(data);
              $('.invodatedcshow').val(data['order_date']);
              $('#dcdueamountshow').val(data['due_amount']);
              $('#stcdcamount').val(Math.abs(data['due_amount']));              
            }
          });
        });

        // on advance payment in db
        $('.stcsavedcmerp').on('click', function(e){
          e.preventDefault();
          var js_mer=$('#stcdcmer').val();
          var js_invo=$('#invonodcshow').val();
          var js_type=$('#stcdcpaymenttype').val();
          var js_value=$('#stcdcamount').val();
          var js_notes=$('#stcdctandc').val();
          $.ajax({
            url         : "kattegat/ragnar_payments.php",
            method      : "post",
            data        : {
              mer:js_mer,
              invo:js_invo,
              type:js_type,
              value:js_value,
              notes:js_notes,
              on_dc_mer_pay_hit:1
            },
            dataType    : 'JSON',
            success     : function (argument) {
              // console.log(argument);
                alert(argument);
                $('.stc-add-dcmerchant-po-payment-form')[0].reset();
            }
          });
        });
      });
    </script>
</body>
</html>