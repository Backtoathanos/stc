<?php  
session_start(); 
if(isset($_SESSION["stc_empl_id"]) && ($_SESSION["stc_empl_role"]>0)){ 
}else{ 
    header("Location:index.html"); 
} 
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
    <title>Reports - STC</title>
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
        #header-fixed {
            position: fixed;
            top: 14%;
            display: none;
            background-color: white;
        }

        tr:nth-child(even) {
          background-color: #AC92EC;
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

        .message-alert{
            color : red;
        }        

        .stc-datatable-filter-ul li{
            display: none;
        }
        .stc-datatable-filter-ul{
            background: #fbfbfb;
            position: fixed;
            top:10%;
            left:88%;
            z-index: 90;
            width: 195px;
            list-style-type: none;
        }

        .stc-datatable-filter{
            z-index: 90;
        }

        @media print {

            #stc-show-std-detailspre-table ,.print-btn-sdl, .body-tabs, .header-user-info, .desctitlesdl, .print-preview-hide, .sl-hide{ 
                display: none; 
            }

            #stc-show-std-details-table{
                width:100%;
                position:relative;
                left:-60px;
            }

            .card{
                border : 0px solid white;
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
                        <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                            <?php 
                                if($_SESSION["stc_empl_role"]==1 || $_SESSION["stc_empl_role"]>5){
                            echo '
                            <li class="nav-item">
                                <a role="tab" class="nav-link active" id="tab-1" data-toggle="tab" href="#tab-content-1">
                                    <span>Merchant Ledger</span>
                                </a>
                            </li>
                            <!-- <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-content-2">
                                    <span>Customer Ledger</span>
                                </a>
                            </li> -->
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-3" data-toggle="tab" href="#tab-content-3">
                                    <span>Customer Agent Requisitions</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-4" data-toggle="tab" href="#tab-content-4">
                                    <span>Customer Agent Project</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-12" data-toggle="tab" href="#tab-content-12">
                                    <span>Material Requisition Details</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-10" data-toggle="tab" href="#tab-content-10">
                                    <span>Status Down List</span>
                                </a>
                            </li>';
                                }
                                if($_SESSION["stc_empl_role"]>5){
                            echo '
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-5" data-toggle="tab" href="#tab-content-5">
                                    <span>STC Electronics</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-6" data-toggle="tab" href="#tab-content-6">
                                    <span>STC Trading</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-9" data-toggle="tab" href="#tab-content-9">
                                    <span>STC Groceries</span>
                                </a>
                            </li>';
                                }
                                if($_SESSION["stc_empl_role"]>1){
                            echo '
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-7" data-toggle="tab" href="#tab-content-7">
                                    <span>School Canteen</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-8" data-toggle="tab" href="#tab-content-8">
                                    <span>School Fee</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-11" data-toggle="tab" href="#tab-content-11">
                                    <span>School Attendance</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-13" data-toggle="tab" href="#tab-content-13">
                                    <span>E-Permit</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-14" data-toggle="tab" href="#tab-content-14">
                                    <span>GLD</span>
                                </a>
                            </li>
                            ';
                                }
                            ?>
                        </ul>
                        <div class="tab-content">
                            <!-- merchant ledger -->
                            <div class="tab-pane tabs-animation fade show active" id="tab-content-1" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >View Merchant Ledger
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <div class="card-border mb-3 card card-body border-success">
                                          <?php 
                                              $date = date("d-m-Y");
                                              $newDate = date('Y-m-d', strtotime($date)); 
                                              $effectiveDate = date('Y-m-d', strtotime("-3 months", strtotime($date)));
                                            ?>   
                                          <p><input type="date" value="<?php echo $effectiveDate;?>" class="form-control merbegdate"></p>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <div class="card-border mb-3 card card-body border-success">
                                          <p><input type="date" value="<?php echo $newDate;?>" class="form-control merenddate"></p>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="name"
                                              >Merchant Name
                                            </h5>
                                            <select
                                              id="stc-select-merchant-to-ledger"
                                              class="custom-select stc-select-merchant"
                                              name="stccustomer"
                                            ><option>Please Select Merchant First</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-10 col-xl-10 col-sm-12"> 
                                        <div class="card-border mb-3 card card-body border-success">
                                            <button class="mb-2 mr-2 btn btn-success btn-block stc-reportsmerFind-hit">
                                                <i class="metismenu-icon pe-7s-search"></i> Find
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xl-2 col-sm-12 hidden-ledger-excel-section"> 
                                        <div class="card-border mb-3 card card-body border-success">
                                            <button class="mb-2 mr-2 btn btn-success btn-block stc-ledger-exportexcel-hit" data-type="excel">
                                                <i class="fa fa-file-excel-o"></i> Export Excel
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xl-12 col-sm-12"> 
                                        <div class="card-border mb-3 card card-body border-success">
                                          <table class="mb-0 table table-bordered" id="stc-reports-ledger-pending-view">
                                            <thead>
                                            <tr>
                                                <th class="text-center">PO ID<br>PO Date</th>
                                                <th class="text-center">GRN ID/DC Id/<br>GRN Date/ DC Date<br>Payment Id<br>Payment Date</th>
                                                <th class="text-center">Invoice No<br>Invoice Date</th>
                                                <th class="text-center">Invoice Basic Amount<br>Total Amount</th>
                                                <th class="text-center">Paid Amount</th>
                                            </tr>
                                            </thead>
                                            <tbody class="stc-reports-merchant-ledger-view">
                                              <tr>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="tab-pane tabs-animation fade" id="tab-content-2" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >View Customer Ledger
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <!-- customer agent requisition -->
                            <div class="tab-pane tabs-animation fade" id="tab-content-3" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >View Customer Agent Requisitions
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <div class="card-border mb-3 card card-body border-success">
                                          <?php 
                                              $date = date("d-m-Y");
                                              $year = date("Y");
                                              $month = date("m");
                                              $day = 1;
                                              $combinedtodate=$day.'-'.$month.'-'.$year;
                                              $newDate = date('Y-m-d', strtotime($date)); 
                                              // $effectiveDate = date('Y-m-d', strtotime("-1 months", strtotime($date)));
                                              $effectiveDate = date('Y-m-d', strtotime($combinedtodate));
                                            ?>   
                                          <p><input type="date" value="<?php echo $effectiveDate;?>" class="form-control begdate"></p>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <div class="card-border mb-3 card card-body border-success">
                                          <p><input type="date" value="<?php echo $newDate;?>" class="form-control enddate"></p>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="name"
                                              >Customer Name
                                            </h5>
                                            <select
                                              id="stc-on-call-customer-customer"
                                              class="custom-select stc-select-customer"
                                              name="stccustomer"
                                            ><option>Please Select Customer First</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="name"
                                              >Agent Name
                                            </h5>
                                            <select
                                              id="stc-on-call-agents-customer"
                                              class="custom-select stc-select-agent"
                                              name="stccustomer"
                                            ><option>Please Select Customer First</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="name"
                                              >Project Name
                                            </h5>
                                            <select
                                              id="stc-on-call-project-customer"
                                              class="custom-select stc-select-project"
                                              name="stccustomer"
                                            ><option>Please Select Agent First</option>
                                            </select>
                                        </div>
                                    </div>                                    
                                    <div class="col-md-8 col-xl-8 col-sm-12"> 
                                        <div class="card-border mb-3 card card-body border-success">
                                            <button class="mb-2 mr-2 btn btn-success btn-block stc-reportsFind-hit">
                                                <i class="metismenu-icon pe-7s-search"></i> Find
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xl-2 col-sm-12 "> 
                                        <div class="card-border mb-3 card card-body border-success">
                                            <button class="mb-2 mr-2 btn btn-success btn-block stc-requisition-pending-hit">
                                                <i class="fa fa-clock-o"></i> Find Pending
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xl-2 col-sm-12 hidden-requisition-excel-section"> 
                                        <div class="card-border mb-3 card card-body border-success">
                                            <button class="mb-2 mr-2 btn btn-success btn-block stc-requisition-exportexcel-hit" data-type="excel">
                                                <i class="fa fa-file-excel-o"></i> Export Excel
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xl-12 col-sm-12"> 
                                        <div class="card-border mb-3 card card-body border-success"><div style="width: 1000px;overflow-x: auto; white-space: nowrap;">
                                          <table class="mb-0 table table-bordered" id="stc-reports-requisition-pending-view">
                                            <thead>
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th class="text-center" width="10%">Comb PR Date &<br>No</th>
                                                <th class="text-center" width="10%">PR Date &<br>No</th>
                                                <th class="text-center" width="10%">Challan Date &<br>No</th>
                                                <th class="text-center">Item Desc</th>
                                                <th class="text-center">Unit</th>
                                                <th class="text-center">PR Qty</th>
                                                <th class="text-center">GM Apprv Qty</th>
                                                <th class="text-center">Proc Apprv Qty</th>
                                                <th class="text-center">Dispatch Qty</th>
                                                <th class="text-center">Pending Qty</th>
                                                <th class="text-center">Recieved Qty</th>
                                                <th class="text-center">Consumption Qty</th>
                                                <th class="text-center">Balance Qty</th>
                                                <th class="text-center">Status</th>
                                            </tr>
                                            </thead>
                                            <tbody class="stc-reports-pending-view">
                                              <tr>
                                                <td colspan="2">
                                                  <button type="button" class="btn btn-primary begbuttoninvsearch" style="float:right;">
                                                    <i class="fas fa-arrow-left"></i>
                                                  </button>
                                                  <input type="hidden" class="begvalueinput" value="0">
                                                  <input type="hidden" class="begvalueinputsearch" value="0">
                                                </td>
                                                <td colspan="11">
                                                  <button type="button" class="btn btn-primary endbuttoninvsearch">
                                                    <i class="fas fa-arrow-right"></i>
                                                  </button>
                                                  <input type="hidden" class="endvalueinput" value="30">
                                                  <input type="hidden" class="endvalueinputsearch" value="30">
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- material requisition details -->
                            <div class="tab-pane tabs-animation fade" id="tab-content-12" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >View Material Requisition Details
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="stc-mrd-from"
                                              >From
                                            </h5>
                                            <input 
                                                type="date"
                                                id="stc-mrd-from"
                                                class="custom-select stc-mrd-from"
                                                value="<?php echo date('Y-m-d', strtotime('- 15 days', strtotime(date('d-m-Y'))));?>"
                                                name="stc-mrd-from"
                                            >
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="stc-mrd-to"
                                              >To
                                            </h5>
                                            <input 
                                                type="date"
                                                id="stc-mrd-to"
                                                class="custom-select stc-mrd-to"
                                                value="<?php echo date('Y-m-d');?>"
                                                name="stc-mrd-to"
                                            >
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="stc-mrd-tojob"
                                              >Type of Job
                                            </h5>
                                            <select
                                              id="stc-mrd-tojob"
                                              class="custom-select stc-mrd-tojob"
                                              name="stc-mrd-tojob"
                                            ><option value="NA">Select</option>
                                            <option value="1">Project</option>
                                            <option value="2">Service</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="stc-mrd-customer"
                                              >Customer Name
                                            </h5>
                                            <select
                                              id="stc-mrd-customer"
                                              class="custom-select stc-select-customer"
                                              name="stc-mrd-customer"
                                            ><option value="NA">Please Select Customer</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="stc-mrd-location"
                                              >Location
                                            </h5>
                                            <select
                                              id="stc-mrd-location"
                                              class="custom-select stc-mrd-lcoation"
                                              name="stc-mrd-location"
                                            ><option value="NA">Please Select Customer First</option>
                                            </select>
                                        </div>
                                    </div>       
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="stc-mrd-dept"
                                              >Department
                                            </h5>
                                            <select
                                              id="stc-mrd-dept"
                                              class="custom-select stc-mrd-dept"
                                              name="stc-mrd-dept"
                                            ><option value="NA">Please Select Location First</option>
                                            </select>
                                        </div>
                                    </div>            
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="stc-mrd-tomaterial"
                                              >Type of Material
                                            </h5>
                                            <select
                                              id="stc-mrd-tomaterial"
                                              class="custom-select stc-mrd-tomaterial"
                                              name="stc-mrd-tomaterial"
                                            ><option value="NA">Select</option>
                                            <option>Tools</option>
                                            <option>PPE</option>
                                            <option>Consumable</option>
                                            <option>Supply</option>
                                            <option>Storeset</option>
                                            </select>
                                        </div>
                                    </div>                         
                                    <div class="col-md-10 col-xl-10 col-sm-12"> 
                                        <div class="card-border mb-3 card card-body border-success">
                                            <button class="mb-2 mr-2 btn btn-success btn-block stc-mrd-hit">
                                                <i class="metismenu-icon pe-7s-search"></i> Find
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xl-2 col-sm-12 hidden-project-excel-section"> 
                                        <div class="card-border mb-3 card card-body border-success">
                                            <button class="mb-2 mr-2 btn btn-success btn-block stc-mrd-exportexcel-hit" data-type="excel">
                                                <i class="fa fa-file-excel-o"></i> Export Excel
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xl-12 col-sm-12"> 
                                        <div class="card-border mb-3 card card-body border-success">
                                          <table class="mb-0 table table-bordered" id="stc-reports-mrd-view">
                                            <thead>
                                            <tr>
                                                <th class="text-center">P.No</th>
                                                <th class="text-center">Req No.</th>
                                                <th class="text-center">SL No.</th>
                                                <th class="text-center">Date</th>
                                                <th class="text-center">Item Description</th>
                                                <th class="text-center">Unit</th>
                                                <th class="text-center">Qty</th>
                                                <th class="text-center">Manager Appr. Qty</th>
                                                <th class="text-center">E-Proc Appr. Qty</th>
                                                <th class="text-center">Dispatch Quantity</th>
                                                <!-- <th>Dispatch Date & Time</th> -->
                                                <th class="text-center">Recieved Date</th>
                                                <th class="text-center">Pending Qty</th>
                                                <th class="text-center">Consumable Qty</th>
                                                <th class="text-center">Stock Qty</th>
                                                <th class="text-center">Priority</th>
                                            </tr>
                                            </thead>
                                            <tbody class="stc-reports-mrd-view">
                                              <tr>
                                                <td colspan="2">
                                                  <button type="button" class="btn btn-primary projbegbuttoninvsearch" style="float:right;">
                                                    <i class="fas fa-arrow-left"></i>
                                                  </button>
                                                  <input type="hidden" class="projbegvalueinputsearch" value="0">
                                                </td>
                                                <td colspan="11">
                                                  <button type="button" class="btn btn-primary projendbuttoninvsearch">
                                                    <i class="fas fa-arrow-right"></i>
                                                  </button>
                                                  <input type="hidden" class="projendvalueinputsearch" value="30">
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- customer agent project -->
                            <div class="tab-pane tabs-animation fade" id="tab-content-4" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >View Customer Agent Project Details
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="name"
                                              >Customer Name
                                            </h5>
                                            <select
                                              id="stc-on-call-customer-proj"
                                              class="custom-select stc-select-customer"
                                              name="stccustomer"
                                            ><option>Please Select Customer First</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="name"
                                              >Agent Name
                                            </h5>
                                            <select
                                              id="stc-on-call-agents-proj"
                                              class="custom-select stc-on-call-agents-proj"
                                              name="stccustomer"
                                            ><option>Please Select Customer First</option>
                                            </select>
                                        </div>
                                    </div>                                   
                                    <div class="col-md-10 col-xl-10 col-sm-12"> 
                                        <div class="card-border mb-3 card card-body border-success">
                                            <button class="mb-2 mr-2 btn btn-success btn-block stc-projFind-hit">
                                                <i class="metismenu-icon pe-7s-search"></i> Find
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xl-2 col-sm-12 hidden-project-excel-section"> 
                                        <div class="card-border mb-3 card card-body border-success">
                                            <button class="mb-2 mr-2 btn btn-success btn-block stc-project-exportexcel-hit" data-type="excel">
                                                <i class="fa fa-file-excel-o"></i> Export Excel
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xl-12 col-sm-12"> 
                                        <div class="card-border mb-3 card card-body border-success">
                                          <table class="mb-0 table table-bordered" id="stc-reports-project-pending-view">
                                            <thead>
                                            <tr>
                                                <th>Project ID<br>Project Date</th>
                                                <th>Project Title</th>
                                                <th>Project Address</th>
                                                <th>Responsive Person</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody class="stc-reports-project-view">
                                              <tr>
                                                <td colspan="2">
                                                  <button type="button" class="btn btn-primary projbegbuttoninvsearch" style="float:right;">
                                                    <i class="fas fa-arrow-left"></i>
                                                  </button>
                                                  <input type="hidden" class="projbegvalueinputsearch" value="0">
                                                </td>
                                                <td colspan="11">
                                                  <button type="button" class="btn btn-primary projendbuttoninvsearch">
                                                    <i class="fas fa-arrow-right"></i>
                                                  </button>
                                                  <input type="hidden" class="projendvalueinputsearch" value="30">
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- stc electronics -->
                            <div class="tab-pane tabs-animation fade" id="tab-content-5" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >View STC Electronics Purchase & Sale
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <div class="card-border mb-3 card card-body border-success">
                                          <?php 
                                              $date = date("d-m-Y");
                                              $year = date("Y");
                                              $month = date("m");
                                              $day = 1;
                                              $combinedtodate=$day.'-'.$month.'-'.$year;
                                              $newDate = date('Y-m-d', strtotime($date)); 
                                              // $effectiveDate = date('Y-m-d', strtotime("-1 months", strtotime($date)));
                                              $effectiveDate = date('Y-m-d', strtotime($combinedtodate));
                                            ?>   
                                          <p><input type="date" value="<?php echo $effectiveDate;?>" class="form-control stcebegdate"></p>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <div class="card-border mb-3 card card-body border-success">
                                          <p><input type="date" value="<?php echo $newDate;?>" class="form-control stceenddate"></p>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="name"
                                              >Category
                                            </h5>
                                            <select
                                              id="stc-on-call-agents-proj"
                                              class="custom-select stc-on-call-stce-cat"
                                              name="stccustomer"
                                            >   
                                                <option value="1">Purchase</option>
                                                <option value="2">Sale</option>
                                            </select>
                                        </div>
                                    </div>                                   
                                    <div class="col-md-10 col-xl-10 col-sm-12"> 
                                        <div class="card-border mb-3 card card-body border-success">
                                            <button class="mb-2 mr-2 btn btn-success btn-block stce-purchasesale-hit">
                                                <i class="metismenu-icon pe-7s-search"></i> Find
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xl-2 col-sm-12 hidden-electronics-excel-section"> 
                                        <div class="card-border mb-3 card card-body border-success">
                                            <button class="mb-2 mr-2 btn btn-success btn-block stc-electronics-exportexcel-hit" data-type="excel">
                                                <i class="fa fa-file-excel-o"></i> Export Excel
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xl-12 col-sm-12"> 
                                        <div class="card-border mb-3 card card-body border-success stc-reports-purchasesale-view">
                                          <table class="mb-0 table table-bordered" id="stc-reports-electronics-pending-view">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Party Name</th>
                                                    <th scope="col">Material Details</th>
                                                    <th scope="col">Invoice/ <br>Challan No</th>
                                                    <th scope="col">Invoice/ <br>Challan Date</th>
                                                    <th scope="col">Material Quantity</th>
                                                    <th scope="col">Material Rate</th>
                                                    <th scope="col">Tax</th>
                                                    <th scope="col">Amount</th>
                                                    <th scope="col">Payment Mode</th>
                                                    <th scope="col">Payment Date</th> 
                                                    <th scope="col">Due Amount</th> 
                                                    <th scope="col">Remarks</th> 
                                                    <th scope="col">Order By</th> 
                                                </tr>
                                            </thead>
                                            <tbody class="">
                                              <tr>
                                                <td colspan="2">
                                                  <button type="button" class="btn btn-primary stcsndpbegbuttoninvsearch" style="float:right;">
                                                    <i class="fas fa-arrow-left"></i>
                                                  </button>
                                                  <input type="hidden" class="stcsndpbegvalueinputsearch" value="0">
                                                </td>
                                                <td colspan="11">
                                                  <button type="button" class="btn btn-primary stcsndpendbuttoninvsearch">
                                                    <i class="fas fa-arrow-right"></i>
                                                  </button>
                                                  <input type="hidden" class="stcsndpendvalueinputsearch" value="30">
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- stc trading -->
                            <div class="tab-pane tabs-animation fade" id="tab-content-6" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >View STC Trading Purchase & Sale
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <div class="card-border mb-3 card card-body border-success">
                                          <?php 
                                              $date = date("d-m-Y");
                                              $year = date("Y");
                                              $month = date("m");
                                              $day = 1;
                                              $combinedtodate=$day.'-'.$month.'-'.$year;
                                              $newDate = date('Y-m-d', strtotime($date)); 
                                              // $effectiveDate = date('Y-m-d', strtotime("-1 months", strtotime($date)));
                                              $effectiveDate = date('Y-m-d', strtotime($combinedtodate));
                                            ?>   
                                          <p><input type="date" value="<?php echo $effectiveDate;?>" class="form-control stctbegdate"></p>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <div class="card-border mb-3 card card-body border-success">
                                          <p><input type="date" value="<?php echo $newDate;?>" class="form-control stctenddate"></p>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="name"
                                              >Category
                                            </h5>
                                            <select
                                              class="custom-select stc-on-call-stct-cat"
                                              name="stccategory"
                                            >   
                                                <option value="1">Purchase</option>
                                                <option value="2">Sale</option>
                                            </select>
                                        </div>
                                    </div>                                   
                                    <div class="col-md-10 col-xl-10 col-sm-12"> 
                                        <div class="card-border mb-3 card card-body border-success">
                                            <button class="mb-2 mr-2 btn btn-success btn-block stct-purchasesale-hit">
                                                <i class="metismenu-icon pe-7s-search"></i> Find
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xl-2 col-sm-12 hidden-trading-excel-section"> 
                                        <div class="card-border mb-3 card card-body border-success">
                                            <button class="mb-2 mr-2 btn btn-success btn-block stc-trading-exportexcel-hit" data-type="excel">
                                                <i class="fa fa-file-excel-o"></i> Export Excel
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xl-12 col-sm-12"> 
                                        <div class="card-border mb-3 card card-body border-success stc-reports-trading-purchasesale-view">
                                          <table class="mb-0 table table-bordered ">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Party Name</th>
                                                    <th scope="col">Material Details</th>
                                                    <th scope="col">Invoice/ <br>Challan No</th>
                                                    <th scope="col">Invoice/ <br>Challan Date</th>
                                                    <th scope="col">Material Quantity</th>
                                                    <th scope="col">Material Rate</th>
                                                    <th scope="col">Tax</th>
                                                    <th scope="col">Amount</th>
                                                    <th scope="col">Payment Mode</th>
                                                    <th scope="col">Payment Date</th> 
                                                    <th scope="col">Due Amount</th> 
                                                    <th scope="col">Remarks</th> 
                                                    <th scope="col">Order By</th> 
                                                </tr>
                                            </thead>
                                            <tbody class="">
                                              <tr>
                                                <td colspan="2">
                                                  <button type="button" class="btn btn-primary stcsndpbegbuttoninvsearch" style="float:right;">
                                                    <i class="fas fa-arrow-left"></i>
                                                  </button>
                                                  <input type="hidden" class="stcsndpbegvalueinputsearch" value="0">
                                                </td>
                                                <td colspan="11">
                                                  <button type="button" class="btn btn-primary stcsndpendbuttoninvsearch">
                                                    <i class="fas fa-arrow-right"></i>
                                                  </button>
                                                  <input type="hidden" class="stcsndpendvalueinputsearch" value="30">
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- school canteen -->
                            <div class="tab-pane tabs-animation fade" id="tab-content-7" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >View STC School Canteen
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <div class="card-border mb-3 card card-body border-success">
                                          <?php 
                                              $date = date("d-m-Y");
                                              $year = date("Y");
                                              $month = date("m");
                                              $day = 1;
                                              $combinedtodate=$day.'-'.$month.'-'.$year;
                                              $newDate = date('Y-m-d', strtotime($date)); 
                                              // $effectiveDate = date('Y-m-d', strtotime("-1 months", strtotime($date)));
                                              $effectiveDate = date('Y-m-d', strtotime($combinedtodate));
                                            ?>   
                                          <p><input type="date" value="<?php echo $effectiveDate;?>" class="form-control stcsbegdate"></p>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <div class="card-border mb-3 card card-body border-success">
                                          <p><input type="date" value="<?php echo $newDate;?>" class="form-control stcsenddate"></p>
                                        </div>
                                    </div>                            
                                    <div class="col-md-10 col-xl-10 col-sm-12"> 
                                        <div class="card-border mb-3 card card-body border-success">
                                            <button class="mb-2 mr-2 btn btn-success btn-block stcs-schoolcant-hit">
                                                <i class="metismenu-icon pe-7s-search"></i> Find
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xl-2 col-sm-12 hidden-canteen-excel-section"> 
                                        <div class="card-border mb-3 card card-body border-success">
                                            <button class="mb-2 mr-2 btn btn-success btn-block stc-canteen-exportexcel-hit" data-type="excel">
                                                <i class="fa fa-file-excel-o"></i> Export Excel
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xl-12 col-sm-12"> 
                                        <div class="card-border mb-3 card card-body border-success">
                                          <table class="mb-0 table table-bordered table-hover table-striped" id="stc-reports-canteen-pending-view">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Date</th>
                                                    <th class="text-center">Student</th>
                                                    <th class="text-center">Teacher</th>
                                                    <th class="text-center">Staff</th>
                                                    <th class="text-center">Guest</th>
                                                    <th class="text-center">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody class="stc-reports-school-canteen-view">
                                              <tr>
                                                <td colspan="2">
                                                  <button type="button" class="btn btn-primary stcsndpbegbuttoninvsearch" style="float:right;">
                                                    <i class="fas fa-arrow-left"></i>
                                                  </button>
                                                  <input type="hidden" class="stcsndpbegvalueinputsearch" value="0">
                                                </td>
                                                <td colspan="11">
                                                  <button type="button" class="btn btn-primary stcsndpendbuttoninvsearch">
                                                    <i class="fas fa-arrow-right"></i>
                                                  </button>
                                                  <input type="hidden" class="stcsndpendvalueinputsearch" value="30">
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- school fee -->
                            <div class="tab-pane tabs-animation fade" id="tab-content-8" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >View STC School Fee
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <div class="card-border mb-3 card card-body border-success">
                                          <?php 
                                              $date = date("d-m-Y");
                                              $year = date("Y");
                                              $month = date("m");
                                              $day = 1;
                                              $combinedtodate=$day.'-'.$month.'-'.$year;
                                              $newDate = date('Y-m-d', strtotime($date)); 
                                              // $effectiveDate = date('Y-m-d', strtotime("-1 months", strtotime($date)));
                                              $effectiveDate = date('Y-m-d', strtotime($combinedtodate));
                                            ?>   
                                          <p><input type="date" value="<?php echo $effectiveDate;?>" class="form-control stcsfbegdate"></p>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <div class="card-border mb-3 card card-body border-success">
                                          <p><input type="date" value="<?php echo $newDate;?>" class="form-control stcsfenddate"></p>
                                        </div>
                                    </div>     
                                    <div class="col-md-12 col-xl-12 col-sm-12"> 
                                        <div class="card-border mb-3 card card-body border-success">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-12">
                                                    <div class="row">
                                                        <div class="col-md-3 col-xl-3 col-sm-3">
                                                            <div class="card-border mb-3 card-body border-success">
                                                                <input type="checkbox" class="material-icons form-control common_selector stcsfchool" value="SGMS">
                                                                <label style="position:relative;">SGMS</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xl-3 col-sm-3">
                                                            <div class="card-border mb-3 card-body border-success">
                                                                <input type="checkbox" class="material-icons form-control common_selector stcsfchool" value="SHS">
                                                                <label style="position:relative;">SHS</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xl-3 col-sm-3">
                                                            <div class="card-border mb-3 card-body border-success">
                                                                <input type="checkbox" class="material-icons form-control common_selector stcsfchool" value="SIS">
                                                                <label style="position:relative;">SIS</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 col-xl-3 col-sm-3">
                                                            <div class="card-border mb-3 card-body border-success">
                                                                <input type="checkbox" class="material-icons form-control common_selector stcsfchool" value="SMS">
                                                                <label style="position:relative;">SMS</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                       
                                    <div class="col-md-10 col-xl-10 col-sm-12"> 
                                        <div class="card-border mb-3 card card-body border-success">
                                            <button class="mb-2 mr-2 btn btn-success btn-block stcs-schoolfee-hit">
                                                <i class="metismenu-icon pe-7s-search"></i> Find
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xl-2 col-sm-12 hidden-fee-excel-section"> 
                                        <div class="card-border mb-3 card card-body border-success">
                                            <button class="mb-2 mr-2 btn btn-success btn-block stc-fee-exportexcel-hit" data-type="excel">
                                                <i class="fa fa-file-excel-o"></i> Export Excel
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xl-12 col-sm-12"> 
                                        <div class="card-border mb-3 card card-body border-success"><div style="width: 1000px;overflow-x: auto; white-space: nowrap;">
                                          <table class="mb-0 table table-bordered table-hover table-striped" id="stc-school-name-table">
                                            <thead>
                                                <tr>
                                                    <th class="text-center"><b>School</b></th>
                                                    <th class="text-center"><b>Date</b></th>
                                                    <th class="text-center"><b>Monthly Fee</b></th>
                                                    <th class="text-center"><b>Admission Fee</b></th>
                                                    <th class="text-center"><b>Books</b></th>
                                                    <th class="text-center"><b>Transportation</b></th>
                                                    <th class="text-center"><b>Donation</b></th>
                                                    <th class="text-center"><b>Day Boarding</b></th>
                                                    <th class="text-center"><b>NEET</b></th>
                                                    <th class="text-center"><b>Others</b></th>
                                                    <th class="text-center"><b>Cashback</b></th>
                                                    <th class="text-center"><b>D Staff Salary</b></th>
                                                    <th class="text-center"><b>Teachers Salary</b></th>
                                                    <th class="text-center"><b>Vehicle Fuels</b></th>
                                                    <th class="text-center"><b>Vehicle Maintenance</b></th>
                                                    <th class="text-center"><b>Electricity</b></th>
                                                    <th class="text-center"><b>Canteen</b></th>
                                                    <th class="text-center"><b>Other Expenses</b></th>
                                                    <th class="text-center"><b>Total</b></th>
                                                    <th class="text-center"><b>Remarks</b></th>
                                                    <th class="text-center"><b>Created By</b></th>
                                                </tr>
                                            </thead>
                                            <tbody class="stc-reports-school-fee-view">
                                              <tr>
                                                <td colspan="2">
                                                  <button type="button" class="btn btn-primary stcsndpbegbuttoninvsearch" style="float:right;">
                                                    <i class="fas fa-arrow-left"></i>
                                                  </button>
                                                  <input type="hidden" class="stcsndpbegvalueinputsearch" value="0">
                                                </td>
                                                <td colspan="11">
                                                  <button type="button" class="btn btn-primary stcsndpendbuttoninvsearch">
                                                    <i class="fas fa-arrow-right"></i>
                                                  </button>
                                                  <input type="hidden" class="stcsndpendvalueinputsearch" value="30">
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- stc groceries -->
                            <div class="tab-pane tabs-animation fade" id="tab-content-9" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >View STC Groceries Purchase & Sale
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <div class="card-border mb-3 card card-body border-success">
                                          <?php 
                                              $date = date("d-m-Y");
                                              $year = date("Y");
                                              $month = date("m");
                                              $day = 1;
                                              $combinedtodate=$day.'-'.$month.'-'.$year;
                                              $newDate = date('Y-m-d', strtotime($date)); 
                                              // $effectiveDate = date('Y-m-d', strtotime("-1 months", strtotime($date)));
                                              $effectiveDate = date('Y-m-d', strtotime($combinedtodate));
                                            ?>   
                                          <p><input type="date" value="<?php echo $effectiveDate;?>" class="form-control stcgbegdate"></p>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <div class="card-border mb-3 card card-body border-success">
                                          <p><input type="date" value="<?php echo $newDate;?>" class="form-control stcgenddate"></p>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="name"
                                              >Category
                                            </h5>
                                            <select
                                              class="custom-select stc-on-call-stcg-cat"
                                              name="stccategory"
                                            >   
                                                <option value="1">Purchase</option>
                                                <option value="2">Sale</option>
                                            </select>
                                        </div>
                                    </div>                                   
                                    <div class="col-md-10 col-xl-10 col-sm-12"> 
                                        <div class="card-border mb-3 card card-body border-success">
                                            <button class="mb-2 mr-2 btn btn-success btn-block stcg-purchasesale-hit">
                                                <i class="metismenu-icon pe-7s-search"></i> Find
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xl-2 col-sm-12 hidden-groceries-excel-section"> 
                                        <div class="card-border mb-3 card card-body border-success">
                                            <button class="mb-2 mr-2 btn btn-success btn-block stc-groceries-exportexcel-hit" data-type="excel">
                                                <i class="fa fa-file-excel-o"></i> Export Excel
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xl-12 col-sm-12"> 
                                        <div class="card-border mb-3 card card-body border-success stc-reports-groceries-purchasesale-view">
                                          <table class="mb-0 table table-bordered ">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Party Name</th>
                                                    <th scope="col">Material Details</th>
                                                    <th scope="col">Invoice/ <br>Challan No</th>
                                                    <th scope="col">Invoice/ <br>Challan Date</th>
                                                    <th scope="col">Material Quantity</th>
                                                    <th scope="col">Material Rate</th>
                                                    <th scope="col">Tax</th>
                                                    <th scope="col">Amount</th>
                                                    <th scope="col">Payment Mode</th>
                                                    <th scope="col">Payment Date</th> 
                                                    <th scope="col">Due Amount</th> 
                                                    <th scope="col">Remarks</th> 
                                                    <th scope="col">Order By</th> 
                                                </tr>
                                            </thead>
                                            <tbody class="">
                                              <tr>
                                                <td colspan="2">
                                                  <button type="button" class="btn btn-primary stcsndpbegbuttoninvsearch" style="float:right;">
                                                    <i class="fas fa-arrow-left"></i>
                                                  </button>
                                                  <input type="hidden" class="stcsndpbegvalueinputsearch" value="0">
                                                </td>
                                                <td colspan="11">
                                                  <button type="button" class="btn btn-primary stcsndpendbuttoninvsearch">
                                                    <i class="fas fa-arrow-right"></i>
                                                  </button>
                                                  <input type="hidden" class="stcsndpendvalueinputsearch" value="30">
                                                </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- status down list -->
                            <div class="tab-pane tabs-animation fade" id="tab-content-10" role="tabpanel">
                                <div class="row desctitlesdl">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >View Status Down List
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2 col-xl-2 col-sm-12" style="display:none;"> 
                                        <div class="card-border mb-3 card card-body border-success">
                                            <select class="form-control stc-std-load-status">
                                                <option value="1">DOWN</option>
                                                <option value="2">WORK-IN-PROGRESS</option>
                                                <option value="3">WORK-DONE</option>
                                                <option value="4">WORK-COMPLETE</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xl-12 col-sm-12"> 
                                        <div class="card-border mb-3 card card-body">
                                            <!-- <a href="javascript:void(0)" class="btn btn-success form-control print-btn-sdl">Print</a> -->
                                            <div class="stc-show-std-details">
                                                <table class="table table-bordered">
                                                    <thead>
                                                    <tr>
                                                            <th class="text-center">SL NO</th>
                                                            <th class="text-center">DATE</th>
                                                            <th class="text-center">LOCATION</th>
                                                            <th class="text-center">EQUIPMENT DETAILS</th>
                                                            <th class="text-center">QTY</th>
                                                            <th class="text-center">CAPACITY</th>
                                                            <th class="text-center">DOWN REASON</th>
                                                            <th class="text-center">STATUS</th>
                                                            <th class="text-center">DELAY(DAYS)</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td colspan="15">
                                                            <h3>Loading.....</h3>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="stc-show-std-detailsprint" style="display:none;"></div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- school attendance -->
                            <div class="tab-pane tabs-animation fade" id="tab-content-11" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >School Attendance
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">                                  
                                    <div class="col-md-12 col-xl-12 col-sm-12"> 
                                        <div class="card-border mb-3 card card-body border-success">
                                            <input type="month" class="form-control stc-school-month" value="<?php echo date('Y-m') ?>">
                                        </div>
                                    </div>                                   
                                    <div class="col-md-12 col-xl-12 col-sm-12"> 
                                        <div class="card-border mb-3 card card-body border-success">
                                            <a href="#" class="form-control btn btn-primary stc-school-att-find">Find</a>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xl-12 col-sm-12"> 
                                        <div class="card-border mb-3 card card-body border-success " ><div class="stc-show-school-attendance" style="width: auto;overflow-x: auto; white-space: nowrap;">
                                            <table class="table table-bordered table-responsive">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">Sl No</th>
                                                        <th class="text-center">Teacher Name</th>
                                                        <th class="text-center">1</th>
                                                        <th class="text-center">2</th>
                                                        <th class="text-center">3</th>
                                                        <th class="text-center">4</th>
                                                        <th class="text-center">5</th>
                                                        <th class="text-center">6</th>
                                                        <th class="text-center">7</th>
                                                        <th class="text-center">8</th>
                                                        <th class="text-center">9</th>
                                                        <th class="text-center">10</th>
                                                        <th class="text-center">11</th>
                                                        <th class="text-center">12</th>
                                                        <th class="text-center">13</th>
                                                        <th class="text-center">14</th>
                                                        <th class="text-center">15</th>
                                                        <th class="text-center">16</th>
                                                        <th class="text-center">17</th>
                                                        <th class="text-center">18</th>
                                                        <th class="text-center">19</th>
                                                        <th class="text-center">20</th>
                                                        <th class="text-center">21</th>
                                                        <th class="text-center">22</th>
                                                        <th class="text-center">23</th>
                                                        <th class="text-center">24</th>
                                                        <th class="text-center">25</th>
                                                        <th class="text-center">26</th>
                                                        <th class="text-center">27</th>
                                                        <th class="text-center">28</th>
                                                        <th class="text-center">29</th>
                                                        <th class="text-center">30</th>
                                                        <th class="text-center">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                  <tr>
                                                    <td colspan="15">
                                                        <h3>Search here.....</h3>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                            </table></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- E-Permit -->
                            <div class="tab-pane tabs-animation fade" id="tab-content-13" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >View E-Permit Attendance
                                            </h5>
                                        </div>
                                    </div>
                                </div><div class="row">                                  
                                    <div class="col-md-12 col-xl-12 col-sm-12"> 
                                        <div class="card-border mb-3 card card-body border-success">
                                            <input type="month" class="form-control stc-epermit-month" value="<?php echo date('Y-m') ?>">
                                        </div>
                                    </div>                                   
                                    <div class="col-md-12 col-xl-12 col-sm-12"> 
                                        <div class="card-border mb-3 card card-body border-success">
                                            <a href="#" class="form-control btn btn-primary stc-epermit-att-find">Find</a>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xl-12 col-sm-12"> 
                                        <div class="card-border mb-3 card card-body border-success">
                                          <table class="mb-0 table table-bordered" id="stc-reports-epermit-view">
                                            <thead>
                                            <tr>
                                                <th class="text-center">SL No.</th>
                                                <th class="text-center">Date</th>
                                                <th class="text-center">Total Entry In Plant</th>
                                                <th class="text-center">E-Permit Enrollment</th>
                                                <th class="text-center">Non Enrollment</th>
                                                <th class="text-center">Remarks</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody class="stc-reports-epermit-view">
                                            </tbody>
                                          </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- stc trading -->
                            <div class="tab-pane tabs-animation fade" id="tab-content-14" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >View GLD Sale
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-6">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <div class="row">
                                                <div class="col-md-3 col-xl-3 col-sm-3">
                                                    <div class="card-border mb-3 card-body border-success">
                                                        <input type="radio" class="material-icons form-control stc-gld-sale" value="0">
                                                        <label style="position:relative;">All</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-xl-3 col-sm-3">
                                                    <div class="card-border mb-3 card-body border-success">
                                                        <input type="radio" class="material-icons form-control stc-gld-sale" value="1">
                                                        <label style="position:relative;">Dhatkidih</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 col-xl-3 col-sm-3">
                                                    <div class="card-border mb-3 card-body border-success">
                                                        <input type="radio" class="material-icons form-control stc-gld-sale" value="2">
                                                        <label style="position:relative;">Sehrabazar</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <div class="card-border mb-3 card card-body border-success">
                                          <?php 
                                              $date = date("d-m-Y");
                                              $year = date("Y");
                                              $month = date("m");
                                              $day = 1;
                                              $combinedtodate=$day.'-'.$month.'-'.$year;
                                              $newDate = date('Y-m-d', strtotime($date)); 
                                              // $effectiveDate = date('Y-m-d', strtotime("-1 months", strtotime($date)));
                                              $effectiveDate = date('Y-m-d', strtotime($combinedtodate));
                                            ?>   
                                          <p><input type="date" value="<?php echo $effectiveDate;?>" class="form-control stcgldbegdate"></p>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6">
                                        <div class="card-border mb-3 card card-body border-success">
                                          <p><input type="date" value="<?php echo $newDate;?>" class="form-control stcgldenddate"></p>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12" style="display:none;">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="searchgld"
                                              >Search
                                            </h5>
                                            <input type="text" id="searchgld" class="form-control stcgldsearch" placeholder="Search">
                                        </div>
                                    </div>                                   
                                    <div class="col-md-10 col-xl-10 col-sm-12"> 
                                        <div class="card-border mb-3 card card-body border-success">
                                            <button class="mb-2 mr-2 btn btn-success btn-block stcgld-purchasesale-hit">
                                                <i class="metismenu-icon pe-7s-search"></i> Find
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-xl-2 col-sm-12 hidden-gld-excel-section"> 
                                        <div class="card-border mb-3 card card-body border-success">
                                            <button class="mb-2 mr-2 btn btn-success btn-block stc-gld-exportexcel-hit" data-type="excel">
                                                <i class="fa fa-file-excel-o"></i> Export Excel
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xl-12 col-sm-12"> 
                                        <div class="card-border mb-3 card card-body border-success stc-reports-gld-purchasesale-view">
                                          <table class="mb-0 table table-bordered ">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Party Name</th>
                                                    <th scope="col">Challan No</th>
                                                    <th scope="col">Challan Date</th>
                                                    <th scope="col">Material Details</th>
                                                    <th scope="col">Material Quantity</th>
                                                    <th scope="col">Material Rate</th>
                                                    <th scope="col">Amount</th>
                                                    <th scope="col">Due Amount</th> 
                                                    <th scope="col">Remarks</th> 
                                                    <th scope="col">Order By</th> 
                                                </tr>
                                            </thead>
                                            <tbody class="">
                                              <tr>
                                                <td colspan="2">
                                                  <button type="button" class="btn btn-primary stcsndpbegbuttoninvsearch" style="float:right;">
                                                    <i class="fas fa-arrow-left"></i>
                                                  </button>
                                                  <input type="hidden" class="stcsndpbegvalueinputsearch" value="0">
                                                </td>
                                                <td colspan="11">
                                                  <button type="button" class="btn btn-primary stcsndpendbuttoninvsearch">
                                                    <i class="fas fa-arrow-right"></i>
                                                  </button>
                                                  <input type="hidden" class="stcsndpendvalueinputsearch" value="30">
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
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script type="text/javascript" src="./assets/scripts/loginopr.js"></script>
    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    <script type="text/javascript" src="./assets/scripts/jarvis.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="./assets/scripts/jquery.table2excel.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/floatthead/2.2.3/jquery.floatThead.min.js"></script>
    <script>
        $(document).ready(function(){
            $('.hidden-ledger-excel-section').hide();
            // call merchants
            call_merchant();
            function call_merchant(){
                $.ajax({
                    url     : "kattegat/ragnar_reports.php",
                    method  : "POST",
                    data    : {Stc_merchant_reporsts_call_merchant:1}, 
                    success : function(data){
                        // console.log(data);
                        $('.stc-select-merchant').html(data);
                    }
                });
            }

            // call reports
            $('body').delegate('.stc-reportsmerFind-hit', 'click', function(e){
                e.preventDefault();
                $('.hidden-ledger-excel-section').show();
                var begdate=$('.merbegdate').val();
                var enddate=$('.merenddate').val();
                var merchantid=$('#stc-select-merchant-to-ledger').val();
                $.ajax({
                    url     : "kattegat/ragnar_reports.php",
                    method  : "POST",
                    data    : {
                        Stc_call_reports_on_merchants:1,
                        begdate:begdate,
                        enddate:enddate,
                        merchantid:merchantid
                    }, 
                    success : function(data){
                    // console.log(data);
                    $('.stc-reports-merchant-ledger-view').html(data);
                    }
                });
            });

            // create excel
            $('body').delegate('.stc-ledger-exportexcel-hit', 'click', function(e){
                e.preventDefault();
                $("#stc-reports-ledger-pending-view").table2excel({
                    filename: "stc-ledger-reports.xls"
                });
            });
        });
    </script>
    <script>
        $(document).ready(function(){
            $('.hidden-requisition-excel-section').hide();
            // call customer
            call_customer();
            function call_customer(){
              $.ajax({
                url     : "kattegat/ragnar_reports.php",
                method  : "POST",
                data    : {Stc_customer_reporsts_call_customer:1}, 
                success : function(data){
                  $('.stc-select-customer').html(data);
                }
              });
            }

            // on change call agents
            $('body').delegate('#stc-on-call-customer-customer', 'change', function(e){
              e.preventDefault();
              var custid=$(this).val();
              $.ajax({
                url     : "kattegat/ragnar_reports.php",
                method  : "POST",
                data    : {
                  Stc_on_change_customer_agent_call:1,
                  custid:custid
                },
                success : function(data){
                  $('.stc-select-agent').html(data);
                }
              });
            });

            // on change call project
            $('body').delegate('#stc-on-call-agents-customer', 'change', function(e){
              e.preventDefault();
              var agentid=$(this).val();
              var custid=$('#stc-on-call-customer-customer').val();
              $.ajax({
                url     : "kattegat/ragnar_reports.php",
                method  : "POST",
                data    : {
                  Stc_on_change_agent_project_call:1,
                  agentid:agentid,
                  custid:custid
                },
                success : function(data){
                  $('.stc-select-project').html(data);
                }
              });
            });

            var jsbegvalue;
            var jsendvalue;
            // find report on click
            $('body').delegate('.stc-reportsFind-hit', 'click', function(e){
                e.preventDefault();
                $('.hidden-requisition-excel-section').show();
                var jsbegdate=$('.begdate').val();
                var jsenddate=$('.enddate').val();
                var jscustid=$('.stc-select-customer').val();
                var jsagentid=$('.stc-select-agent').val();
                var jsprojeid=$('.stc-select-project').val();
                var count_id=0;
                $.ajax({
                    url         : "kattegat/ragnar_reports.php",
                    method      : "POST",
                    data        : {
                        stc_requisition_reports_req:1,
                        stc_begdate:jsbegdate,
                        stc_enddate:jsenddate,
                        stc_custid:jscustid,
                        stc_agentid:jsagentid,
                        stc_projeid:jsprojeid,
                        stc_count_id:count_id
                    },
                    success     : function(reportsfindres){
                        $('.stc-reports-pending-view').html(reportsfindres);
                    }
                });
            });
            
            // show more pagination
            $('body').delegate('.show_more_requisition', 'click', function(e){
                e.preventDefault();
                var count_id=$(this).attr("data-val");
                $('.show_more_requisition_row').remove();
                $('.hidden-requisition-excel-section').show();
                var jsbegdate=$('.begdate').val();
                var jsenddate=$('.enddate').val();
                var jscustid=$('.stc-select-customer').val();
                var jsagentid=$('.stc-select-agent').val();
                var jsprojeid=$('.stc-select-project').val();
                $.ajax({
                    url         : "kattegat/ragnar_reports.php",
                    method      : "POST",
                    data        : {
                        stc_requisition_reports_req:1,
                        stc_begdate:jsbegdate,
                        stc_enddate:jsenddate,
                        stc_custid:jscustid,
                        stc_agentid:jsagentid,
                        stc_projeid:jsprojeid,
                        stc_count_id:count_id
                    },
                    success     : function(reportsfindres){
                        $('.stc-reports-pending-view').append(reportsfindres);
                    }
                });
            });

            // find pending report on click
            $('body').delegate('.stc-requisition-pending-hit', 'click', function(e){
                e.preventDefault();
                $('.hidden-requisition-excel-section').show();
                var jsbegdate=$('.begdate').val();
                var jsenddate=$('.enddate').val();
                var jscustid=$('.stc-select-customer').val();
                var jsagentid=$('.stc-select-agent').val();
                var jsprojeid=$('.stc-select-project').val();
                $.ajax({
                    url         : "kattegat/ragnar_reports.php",
                    method      : "POST",
                    data        : {
                        stc_pending_reports_req:1,
                        stc_begdate:jsbegdate,
                        stc_enddate:jsenddate,
                        stc_custid:jscustid,
                        stc_agentid:jsagentid,
                        stc_projeid:jsprojeid
                    },
                    success     : function(reportsfindres){
                        $('.stc-reports-pending-view').html(reportsfindres);
                    }
                });
            });

            // create excel
            $('body').delegate('.stc-requisition-exportexcel-hit', 'click', function(e){
                e.preventDefault();
                $("#stc-reports-requisition-pending-view").table2excel({
                    filename: "stc-requisition-reports.xls"
                });
            });
        });
    </script>
    <script>        
        $(document).ready(function() {
            $(window).bind("scroll", function() {
                var offset = $(this).scrollTop();
                if(offset>800){
                    $("#stc-reports-mrd-view thead").css('position', 'sticky');
                    $("#stc-reports-mrd-view thead").css('background', 'white');
                    $("#stc-reports-mrd-view thead").css('top', '12%');
                    
                    $("#stc-show-std-details-table thead").css('position', 'sticky');
                    $("#stc-show-std-details-table thead").css('background', 'white');
                    $("#stc-show-std-details-table thead").css('top', '8%');
                    $('.stc-datatable-filter-ul').show();
                }else{
                    $("#stc-reports-mrd-view thead").css('position', 'relative');
                    $("#stc-reports-mrd-view thead").removeProp('background');
                    $("#stc-reports-mrd-view thead").removeProp('top');

                    
                    $("#stc-show-std-details-table thead").css('position', 'relative');
                    $("#stc-show-std-details-table thead").removeProp('background');
                    $("#stc-show-std-details-table thead").removeProp('top');
                    $('.stc-datatable-filter-ul').hide();
                }
            });
        });

        $(document).ready(function(){
            $('body').delegate('.print-btn-sdl', 'click', function(){
                $('.jobdonedet-print').show();
                $('.jobdonedet-view').hide();
                Popup($('#stc-show-std-details-table')[0].innerHTML);
                function Popup(data){
                    window.print();
                    return true;
                }
            });


            $('.hidden-project-excel-section').hide();
            var screenwidth=$(window).width();
            var finalwidth=screenwidth - (screenwidth * 0.20);
            var percent=finalwidth/screenwidth * 100;
            // $('.stc-show-std-details').width(finalwidth);
            
            $('body').delegate('.std-filter-department', 'change', function(){
                var values = $(this).val();
                $('.message-alert').remove();
                if(values=="NA"){
                    alert("Please select location first.");
                    $('.std-filter-location').after('<p class="message-alert">Please select Location first.</p>');
                }
            });

            $('body').delegate('.std-filter-location', 'change', function(){
                var location = $(this).val();
                $('.message-alert').remove();
                if(location!="NA"){
                    $.ajax({
                        url     : "kattegat/ragnar_reports.php",
                        method  : "POST",
                        data    : {
                        Stc_std_department:1,
                        location:location
                        },
                        success : function(data){
                            // console.log(data);
                            $('.std-filter-department').html(data);
                        }
                    });
                }else{
                    $('.std-filter-department').html('<option value="NA">SELECT LOCATION FIRST.</option>');
                }
            });

            var filter = 0;
            var pagination=0;
            var type='';
            stc_call_std(filter, type, pagination);
            function stc_call_std(filter, type, pagination){
                var datefrom    =   $(".std-filter-date-from").val();
                var dateto      =   $(".std-filter-date-to").val();
                var location    =   $(".std-filter-location").val();
                var department  =   $(".std-filter-department").val();
                var typeofjob   =   $(".std-filter-typeofjob").val();
                var status      =   $(".std-filter-status").val();
                var filter      =   filter;
                var validated   =   1;
                var message     =   "";
                if((datefrom!='' && dateto=='') || (dateto!='' && datefrom=='')){
                    message ="please fill both date.";
                    validated=0;
                }                
                if(filter>0){
                    $('.filter-message-box').remove();
                    if(datefrom =="" && dateto == "" && location == "NA" && department == "NA" && typeofjob == "NA" && status == "NA"){
                        validated=0;
                        message="Minimum 1 field required for search!!!";
                        $(".std-filter-date-from").after("<p class='filter-message-box' style='color:red'>Select From date.</p>");
                        $(".std-filter-date-to").after("<p class='filter-message-box' style='color:red'>Select To date.</p>");
                        $(".std-filter-location").after("<p class='filter-message-box' style='color:red'>Select Location.</p>");
                        $(".std-filter-department").after("<p class='filter-message-box' style='color:red'>Select Department.</p>");
                        $(".std-filter-typeofjob").after("<p class='filter-message-box' style='color:red'>Select Type of Job.</p>");
                        $(".std-filter-status").after("<p class='filter-message-box' style='color:red'>Select Status.</p>");
                    }
                }
                if(validated==1){
                    $('.btn-close').click();
                    $('.modal-backdrop.fade.show').removeClass('modal-backdrop fade show');
                    $.ajax({
                        url     : "kattegat/ragnar_reports.php",
                        method  : "POST",
                        data    : {
                            Stc_std_details:1,
                            datefrom:datefrom,
                            dateto:dateto,
                            location:location,
                            department:department,
                            typeofjob:typeofjob,
                            status:status,
                            pagination:pagination,
                            filter:filter,
                            type:type
                        },
                        success : function(data){
                            if(type==''){
                                $('.stc-show-std-details').html(data);
                            }else{
                                $('.stc-show-std-detailsprint').html(data);
                                $('.stc-sdl-exportexcel-hit2').click();
                            }
                        }
                    });
                }else{    
                    alert(message);
                }
            }
            
            $('body').delegate('tr', 'click', function(e){
                $('tr').css('background-color', '');
                $(this).css('background-color', 'rgb(221 219 255)');
            });

            $('body').delegate('.show-jobdonedetails', 'click', function(e){
                e.preventDefault();
                var jobdonedet = $(this).attr("data");
                var reportlabel=$(this).attr("label");
                $('.title-barreport').html(reportlabel);
                $('.jobdonedet_para').html(jobdonedet);
                $('.show-jobdonedetailsmodal').modal('show');
            });

            // find std 
            $('body').delegate('.std-filter-find-btn', 'click', function(e){
                e.preventDefault();
                var filter = 1;
                var pagination=0;
                var type='';
                $('.stc-show-std-details').html("Please wait...");
                stc_call_std(filter, type, pagination);
            });
            
            // find std with pagination
            $('body').delegate('.std-filter-find-paginationprev', 'click', function(e){
                e.preventDefault();
                var filter = 0;
                var datefrom    =   $(".std-filter-date-from").val();
                var dateto      =   $(".std-filter-date-to").val();
                var location    =   $(".std-filter-location").val();
                var department  =   $(".std-filter-department").val();
                var typeofjob   =   $(".std-filter-typeofjob").val();
                var status      =   $(".std-filter-status").val();
                if(datefrom =="" && dateto == "" && location == "NA" && department == "NA" && typeofjob == "NA" && status == "NA"){
                    filter=0;
                }else{
                    filter=1;
                }
                var pagination = $(this).attr("data");
                pagination=pagination!=0 ? pagination-20 : pagination;
                $('.std-filter-find-pagination').remove();
                $('.stc-show-std-details').html("Please wait...");
                var type='';
                stc_call_std(filter, type, pagination);
            });
            // find std with pagination
            $('body').delegate('.std-filter-find-pagination', 'click', function(e){
                e.preventDefault();
                var filter = 0;
                var datefrom    =   $(".std-filter-date-from").val();
                var dateto      =   $(".std-filter-date-to").val();
                var location    =   $(".std-filter-location").val();
                var department  =   $(".std-filter-department").val();
                var typeofjob   =   $(".std-filter-typeofjob").val();
                var status      =   $(".std-filter-status").val();
                if(datefrom =="" && dateto == "" && location == "NA" && department == "NA" && typeofjob == "NA" && status == "NA"){
                    filter=0;
                }else{
                    filter=1;
                }
                var pagination = $(this).attr("data");
                $('.std-filter-find-pagination').remove();
                $('.stc-show-std-details').html("Please wait...");
                var type='';
                stc_call_std(filter, type, pagination);
            });
            
            $('body').delegate('.stc-edit-report', 'click', function(e){
                e.preventDefault();
                var sdl_id=$(this).attr('id');
                $('.sdl-hidden-reports').val(sdl_id);
                $('.update-sdlreportmodal').modal();
            });

            $('body').delegate('.sdl-update-reportsdl-save', 'click', function(e){
                var sdl_id=$('.sdl-hidden-reports').val();
                var target_date=$('.std-sdl-target-date').val();
                var remarks=$('.stc-sdl-remarks').val();
                var validated=1;
                $('.message-alert').remove();

                // if(target_date==""){
                //     $('.std-sdl-target-date').after('<p class="message-alert">Select target date.</p>');
                //     validated=0;
                // }

                if(remarks==""){
                    $('.stc-sdl-remarks').after('<p class="message-alert">Enter remarks.</p>');
                    validated=0;
                }
                
                if(validated==1){
                    $.ajax({
                        url     : "kattegat/ragnar_reports.php",
                        method  : "POST",
                        data    : {
                            stc_sdl_update:1,
                            sdl_id:sdl_id,
                            target_date:target_date,
                            remarks:remarks
                        },
                        success : function(data){
                            if(data.trim()=="updated"){
                                alert("Record updated.");
                                $('.sdlurbtn-close').click();
                                stc_call_std(0, 0);
                            }
                        }
                    });
                }else{
                    alert("Empty fields not acceptable.");
                }
            });

            // data table filter
            $('body').delegate('.stc-datatable-filter', 'change', function(e){
                e.preventDefault();
                var value = $(this).val();
                if($(this).prop('checked')==true){
                    // $('.'+value).hide();
                    $('#stc-show-std-details-table td:nth-child('+value+'),th:nth-child('+value+')').hide();
                    $('#stc-show-std-details-table td:nth-child('+value+')').addClass('excel-hide');
                    $('#stc-show-std-details-table th:nth-child('+value+')').addClass('excel-hide');
                }else{
                    // $('.'+value).show();
                    $('#stc-show-std-details-table td:nth-child('+value+'),th:nth-child('+value+')').show();
                    $('#stc-show-std-details-table td:nth-child('+value+')').addClass('excel-hide');
                    $('#stc-show-std-details-table th:nth-child('+value+')').addClass('excel-hide');
                }
            });

            $('body').delegate('.data-fields-display', 'click', function(e){
                e.preventDefault();                
                $('.stc-datatable-filter-ul li').toggle(200);
            });

            $('body').delegate('.filter-span', 'click', function(e){
                e.preventDefault();
                $(this).parent().find('input').click();
            });
            
            // create excel
            $('body').delegate('.stc-sdl-exportexcel-hit2', 'click', function(e){
                e.preventDefault();
                $('.jobdonedet-view').remove();
                $('.excel-hide').remove();
                var table = $(this).prev('#stc-show-std-details-table'); 
                $("#stc-show-std-details-table").table2excel({
                    name: "Status Down List Reports",
                    exclude_inputs: false, 
                    preserveColors: true ,
                    exclude_links: false, 
                    filename: "stc-sdl-reports-" + new Date().toString().replace(/[\-\:\.]/g, "") + ".xls",
                    columns: [0, 1, 2]
                });
                $('.std-filter-find-btn').click();
            });
            
            $('body').delegate('.stc-sdl-exportexcel-hit', 'click', function(e){
                e.preventDefault();                
                var filter = 1;
                var pagination=0;
                var type='print';
                $('.stc-show-std-details').html("Please wait...");
                stc_call_std(filter, type, pagination);
            });

            $('body').delegate('.stc-sdl-material-show-req', 'click', function(e){
                var sdl_id=$(this).attr("id");
                $.ajax({
                    url     : "kattegat/ragnar_reports.php",
                    method  : "POST",
                    data    : {
                        stc_sdl_material_call:1,
                        sdl_id:sdl_id
                    },
                    dataType : "JSON",
                    success : function(data){
                        $('.show-material-list-sdl').html(data.data);
                        $('.downlistno').html('<b>SDL Number : '+data.Downlist+'</b>');
                        $('.downlistdate').html('<b>SDL Date : '+data.downlistdate+'</b>');
                        $('.reqnumber').html('<b>Requisition Number : '+data.reqno+'</b>');
                        $('.reqdate').html('<b>Requisition Date : '+data.reqdate+'</b>');
                        $('.sublocation').html('<b>'+data.sublocation+'</b>');
                        $('.SupName').html('<b>Supervisor Name : '+data.SupName+'</b>');
                        $('.SupContact').html('<b>Supervisor Contact : '+data.SupContact+'</b>');
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function(){
            // on change call agents
            $('body').delegate('#stc-on-call-customer-proj', 'change', function(e){
              e.preventDefault();
              var custid=$(this).val();
              $.ajax({
                url     : "kattegat/ragnar_reports.php",
                method  : "POST",
                data    : {
                  Stc_on_change_customer_agent_call:1,
                  custid:custid
                },
                success : function(data){
                  $('.stc-on-call-agents-proj').html(data);
                }
              });
            });

            var jsbegvalue;
            var jsendvalue;
            // find report on click
            $('body').delegate('.stc-projFind-hit', 'click', function(e){
                e.preventDefault();
                $('.hidden-project-excel-section').show();
                var jscustid=$('#stc-on-call-customer-proj').val();
                var jsagentid=$('.stc-on-call-agents-proj').val();
                $.ajax({
                    url         : "kattegat/ragnar_reports.php",
                    method      : "POST",
                    data        : {
                        stc_proj_reports_req:1,
                        stc_custid:jscustid,
                        stc_agentid:jsagentid
                    },
                    success     : function(reportsfindres){
                        $('.stc-reports-project-view').html(reportsfindres);
                    }
                });
            });

            // call modal
            $('body').delegate('.stc-project-show-ret', 'click', function(e){
                e.preventDefault();
                var project_id=$(this).attr("id");
                $.ajax({
                    url         : "kattegat/ragnar_reports.php",
                    method      : "POST",
                    data        : {
                        stc_ag_rproject_retrive:1,
                        project_id:project_id
                    },
                    dataType    : "JSON",
                    success     : function(res_data){
                        // console.log(res_data);
                        $('#stc-ag-project-id').val(res_data['stc_cust_project_title']);
                        $('#stc-ag-cust-name').val(res_data['stc_cust_project_details_cust_name']);
                        $('#stc-ag-cust-emailid').val(res_data['stc_cust_project_details_email_id']);
                        $('#stc-ag-cont-number').val(res_data['stc_cust_project_details_cont_no']);
                        $('#stc-ag-purpose').val(res_data['stc_cust_project_details_purpose']);
                        $('#stc-ag-ref-contact').val(res_data['stc_cust_project_details_ref_contact']);
                        $('#stc-ag-job-details').val(res_data['stc_cust_project_details_job_details']);
                        $('#stc-ag-quot-number').val(res_data['stc_cust_project_details_quote_number']);
                        $('#stc-ag-quote-date').val(res_data['stc_cust_project_details_quote_date']);
                        $('#stc-ag-basic-value').val(res_data['stc_cust_project_details_basic_val']);
                        $('#stc-ag-gst-value').val(res_data['stc_cust_project_details_gst_val']);
                        $('#stc-ag-quoted-by').val(res_data['stc_cust_project_details_quotation_by']);
                        $('#stc-ag-mode-of-quotation').val(res_data['stc_cust_project_details_mode_of_quote']);
                        $('#stc-ag-target-price').val(res_data['stc_cust_project_details_tar_price']);
                        $('#stc-ag-status').val(res_data['stc_cust_project_details_status']);
                        $('#stc-ag-remarks').val(res_data['stc_cust_project_details_remarks']);
                        $('#stc-ag-po-number').val(res_data['stc_cust_project_details_po_number']);
                        $('#tc-ag-po-value').val(res_data['stc_cust_project_details_po_value']);
                        $('.bd-projectdetails-modal-lg').modal("show");
                    }
                });
            });

            // create excel
            $('body').delegate('.stc-project-exportexcel-hit', 'click', function(e){
                e.preventDefault();
                $("#stc-reports-project-pending-view").table2excel({
                    filename: "stc-project-reports.xls"
                });
            });
        });
    </script>
    <script>
        $(document).ready(function(){
            $('.hidden-electronics-excel-section').hide();
            // call purchas sale
            $('body').delegate('.stce-purchasesale-hit', 'click', function(e){
                e.preventDefault();
                $('.hidden-electronics-excel-section').show();
                var beg_date=$('.stcebegdate').val();
                var end_date=$('.stceenddate').val();
                var cat_id=$('.stc-on-call-stce-cat').val();
                $.ajax({
                    url     : "kattegat/ragnar_reports.php",
                    method  : "post",
                    data    : {
                        stc_find_purchase_sale_reports:1,
                        beg_date:beg_date,
                        end_date:end_date,
                        cat_id:cat_id
                    },
                    success : function(response_sandp){
                        // console.log(response_sandp);
                        $('.stc-reports-purchasesale-view').html(response_sandp);
                    }
                });
            });

            // create excel
            $('body').delegate('.stc-electronics-exportexcel-hit', 'click', function(e){
                e.preventDefault();
                $("#stc-reports-electronics-pending-view").table2excel({
                    filename: "stc-electronics-reports.xls"
                });
            });
        });
    </script>
    <script>
        $(document).ready(function(){
            $('.hidden-trading-excel-section').hide();
            // call purchase sale
            $('body').delegate('.stct-purchasesale-hit', 'click', function(e){
                e.preventDefault();
                $('.hidden-trading-excel-section').show();
                var beg_date=$('.stctbegdate').val();
                var end_date=$('.stctenddate').val();
                var cat_id=$('.stc-on-call-stct-cat').val();
                $.ajax({
                    url     : "kattegat/ragnar_reports.php",
                    method  : "post",
                    data    : {
                        stc_find_trading_purchase_sale_reports:1,
                        beg_date:beg_date,
                        end_date:end_date,
                        cat_id:cat_id
                    },
                    success : function(response_sandp){
                        // console.log(response_sandp);
                        $('.stc-reports-trading-purchasesale-view').html(response_sandp);
                    }
                });
            });

            // create excel
            $('body').delegate('.stc-trading-exportexcel-hit', 'click', function(e){
                e.preventDefault();
                $("#stc-reports-trading-pending-view").table2excel({
                    filename: "stc-trading-reports.xls"
                });
            });
        });
    </script>
    <script>
        $(document).ready(function(){
            $('.hidden-groceries-excel-section').hide();
            // call purchase sale
            $('body').delegate('.stcg-purchasesale-hit', 'click', function(e){
                e.preventDefault();
                $('.hidden-trading-excel-section').show();
                var beg_date=$('.stcgbegdate').val();
                var end_date=$('.stcgenddate').val();
                var cat_id=$('.stc-on-call-stcg-cat').val();
                $.ajax({
                    url     : "kattegat/ragnar_reports.php",
                    method  : "post",
                    data    : {
                        stc_find_groceries_purchase_sale_reports:1,
                        beg_date:beg_date,
                        end_date:end_date,
                        cat_id:cat_id
                    },
                    success : function(response_sandp){
                        // console.log(response_sandp);
                        $('.stc-reports-groceries-purchasesale-view').html(response_sandp);
                    }
                });
            });

            // create excel
            $('body').delegate('.stc-trading-exportexcel-hit', 'click', function(e){
                e.preventDefault();
                $("#stc-reports-trading-pending-view").table2excel({
                    filename: "stc-trading-reports.xls"
                });
            });
        });
    </script>
    <script>
        $(document).ready(function(){
            $('.hidden-canteen-excel-section').hide();
            // call school canteen
            $('body').delegate('.stcs-schoolcant-hit', 'click', function(e){
                e.preventDefault();
                $('.hidden-canteen-excel-section').show();
                var beg_date=$('.stcsbegdate').val();
                var end_date=$('.stcsenddate').val();
                $.ajax({
                    url     : "kattegat/ragnar_reports.php",
                    method  : "post",
                    data    : {
                        stc_find_school_canteen_reports:1,
                        beg_date:beg_date,
                        end_date:end_date
                    },
                    success : function(response_sandp){
                        // console.log(response_sandp);
                        $('.stc-reports-school-canteen-view').html(response_sandp);
                    }
                });
            });

            // show inside
            $('body').delegate('.stc-school-showdeep-req', 'click', function(e){
                e.preventDefault();
                var req_date=$(this).attr("id");
                $.ajax({  
                    url     : "kattegat/ragnar_reports.php",
                    method    : "POST",  
                    data      : {
                        stc_call_by_date : 1,
                        req_date:req_date
                    },
                    // dataType: `JSON`,
                    success   : function(response_canteen){
                        // console.log(data);
                        $('.stc-school-showdeep-res').modal("show");
                        $('.stc-show-canteen-nested-show').html(response_canteen);
                    }
                });
            });

            // create excel
            $('body').delegate('.stc-canteen-exportexcel-hit', 'click', function(e){
                e.preventDefault();
                $("#stc-reports-canteen-pending-view").table2excel({
                    filename: "stc-canteen-reports.xls"
                });
            });
        });
    </script>
    <script>
        $(document).ready(function(){
            var filter_school = {};
            $('.hidden-fee-excel-section').hide();
            
            //school checklbox filter
            function get_filter(class_name){
                var filter = [];
                $('.' + class_name + ':checked').each(function(){
                   filter.push($(this).val()); 
                });
                return filter;
            }
            //set school to cookies            
            $('body').delegate('.common_selector', 'click', function(){
               // e.preventDefault();
                if($(this).hasClass('stcsfchool')){
                    filter_school['school'] = get_filter('stcsfchool');
                }
                
            });
            // call school canteen
            $('body').delegate('.stcs-schoolfee-hit', 'click', function(e){
                // e.preventDefault();
                $('.hidden-fee-excel-section').show();
                var beg_date=$('.stcsfbegdate').val();
                var end_date=$('.stcsfenddate').val();
                var school_n=filter_school['school'];
                $.ajax({
                    url     : "kattegat/ragnar_reports.php",
                    method  : "post",
                    data    : {
                        stc_find_school_fee_reports:1,
                        beg_date:beg_date,
                        end_date:end_date,
                        school_name:school_n
                    },
                    success : function(response_sandp){
                        // console.log(response_sandp);
                        $('.stc-reports-school-fee-view').html(response_sandp);
                    }
                });
                
            });

            // create excel
            $('body').delegate('.stc-fee-exportexcel-hit', 'click', function(e){
                e.preventDefault();
                $("#stc-school-name-table").table2excel({
                    filename: "stc-fee-reports.xls"
                });
            });

            // call attendance
            $('body').delegate('.stc-school-att-find', 'click', function(e){
                e.preventDefault();
                var stc_school_month = $('.stc-school-month').val();
                $.ajax({
                    url     : "kattegat/ragnar_reports.php",
                    method  : "post",
                    data    : {
                        stc_find_school_attendance:1,
                        stc_school_month:stc_school_month
                    },
                    success : function(response_sandp){
                        // console.log(response_sandp);
                        $('.stc-show-school-attendance').html(response_sandp);
                    }
                });
            });

            // call attendance
            $('body').delegate('.stc-epermit-att-find', 'click', function(e){
                e.preventDefault();
                var stc_epermit_month = $('.stc-epermit-month').val();
                $.ajax({
                    url     : "kattegat/ragnar_reports.php",
                    method  : "post",
                    data    : {
                        stc_find_epermit_attendance:1,
                        stc_epermit_month:stc_epermit_month
                    },
                    success : function(response_sandp){
                        // console.log(response_sandp);
                        $('.stc-reports-epermit-view').html(response_sandp);
                    }
                });
            });

            // call attendance
            $('body').delegate('.stc-epermit-att-show', 'click', function(e){
                var stc_epermit_month = $(this).attr('date');
                $.ajax({
                    url     : "kattegat/ragnar_reports.php",
                    method  : "post",
                    data    : {
                        stc_find_epermit_attendance_details:1,
                        stc_epermit_month:stc_epermit_month
                    },
                    success : function(response_sandp){
                        // console.log(response_sandp);
                        $('.show-attendance-epermitdetails').html(response_sandp);
                    }
                });
            });
            
        });
    </script>
    <!-- mrd -->    
    <script>
        $(document).ready(function(){

            // call location for mrd
            $('body').delegate('#stc-mrd-customer', 'change', function(e){
                e.preventDefault();
                var customer_id = $(this).val();
                $.ajax({
                    url     : "kattegat/ragnar_reports.php",
                    method  : "post",
                    data    : {
                        stc_mrd_call_location:1,
                        customer_id:customer_id
                    },
                    success : function(response){
                        // console.log(response);
                        $('#stc-mrd-location').html(response);
                    }
                });
            });

            // call department for mrd
            $('body').delegate('#stc-mrd-location', 'change', function(e){
                e.preventDefault();
                var location = $(this).val();
                $.ajax({
                    url     : "kattegat/ragnar_reports.php",
                    method  : "post",
                    data    : {
                        stc_mrd_call_dept:1,
                        location:location
                    },
                    success : function(response){
                        // console.log(response);
                        $('#stc-mrd-dept').html(response);
                    }
                });
            });

            // call find for mrd
            $('body').delegate('.stc-mrd-hit', 'click', function (e) {
                e.preventDefault();
                var page = 1;
                get_mrd(page);
            });

            function get_mrd(page){
                var from = $("#stc-mrd-from").val();
                var to = $("#stc-mrd-to").val();
                var tojob = $("#stc-mrd-tojob").val();
                var customer = $("#stc-mrd-customer").val();
                var location = $("#stc-mrd-location").val();
                var dept = $("#stc-mrd-dept option:selected").text();
                var pro_id = $("#stc-mrd-dept").val();
                var tomaterial = $("#stc-mrd-tomaterial").val();
                var page = page || 1; // Current page, default to 1
                var limit = 10; // Number of records per page

                if (from !== "" && to !== "") {
                    $.ajax({
                        url: "kattegat/ragnar_reports.php",
                        method: "post",
                        data: {
                            stc_mrd_call_mrd: 1,
                            from: from,
                            to: to,
                            tojob: tojob,
                            customer: customer,
                            location: location,
                            dept: dept,
                            pro_id: pro_id,
                            tomaterial: tomaterial,
                            page: page,
                            limit: limit
                        },
                        dataType: "JSON",
                        success: function (response) {
                            $('.stc-reports-mrd-view').html(response);
                        }
                    });
                } else {
                    alert("Please select a date.");
                }
            }

            $('body').delegate('.stc-mrd-page', 'click', function (e) {
                e.preventDefault();
                var page = $(this).data('page');
                get_mrd(page);
                // $('.stc-mrd-hit').data('page', page).trigger('click'); // Trigger the main search with updated page
            });


            $('body').delegate('.showmrd-details', 'click', function(e){
                var reqid=$(this).attr('reqnumber');
                var reqdate=$(this).attr('reqdate');
                var reqraisedby=$(this).attr('reqraisedby');
                var reqraisedfrom=$(this).attr('reqraisedfrom');
                var itemdesc=$(this).attr('itemdesc');
                var itemqty=$(this).attr('itemqty');
                var itemunit=$(this).attr('itemunit');
                var itempriority=$(this).attr('itempriority');
                $('.reqnumbershow').val(reqid);
                $('.reqdateshow').val(reqdate);
                $('.reqraisedbyshow').val(reqraisedby);
                $('.reqraisedfromshow').val(reqraisedfrom);
                $('.itemdescshow').val(itemdesc);
                $('.itemqtyshow').val(itemqty);
                $('.itemunitshow').val(itemunit);
                $('.itempriorityshow').val(itempriority);
            });

            var filter_school = {};
            $('.hidden-fee-excel-section').hide();
            
            //school checklbox filter
            function get_filter(class_name){
                var filter = [];
                $('.' + class_name + ':checked').each(function(){
                   filter.push($(this).val()); 
                });
                return filter;
            }
            //set school to cookies            
            $('body').delegate('.common_selector', 'click', function(){
               // e.preventDefault();
                if($(this).hasClass('stcsfchool')){
                    filter_school['school'] = get_filter('stcsfchool');
                }
                
            });
            // call school canteen
            $('body').delegate('.stcs-schoolfee-hit', 'click', function(e){
                // e.preventDefault();
                $('.hidden-fee-excel-section').show();
                var beg_date=$('.stcsfbegdate').val();
                var end_date=$('.stcsfenddate').val();
                var school_n=filter_school['school'];
                $.ajax({
                    url     : "kattegat/ragnar_reports.php",
                    method  : "post",
                    data    : {
                        stc_find_school_fee_reports:1,
                        beg_date:beg_date,
                        end_date:end_date,
                        school_name:school_n
                    },
                    success : function(response_sandp){
                        // console.log(response_sandp);
                        $('.stc-reports-school-fee-view').html(response_sandp);
                    }
                });
                
            });

            // create excel
            $('body').delegate('.stc-fee-exportexcel-hit', 'click', function(e){
                e.preventDefault();
                $("#stc-school-name-table").table2excel({
                    filename: "stc-fee-reports.xls"
                });
            });

            // call attendance
            $('body').delegate('.stc-school-att-find', 'click', function(e){
                e.preventDefault();
                var stc_school_month = $('.stc-school-month').val();
                $.ajax({
                    url     : "kattegat/ragnar_reports.php",
                    method  : "post",
                    data    : {
                        stc_find_school_attendance:1,
                        stc_school_month:stc_school_month
                    },
                    success : function(response_sandp){
                        // console.log(response_sandp);
                        $('.stc-show-school-attendance').html(response_sandp);
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function(){
            $('.hidden-gld-excel-section').hide();
            // call purchase sale
            $('body').delegate('.stcgld-purchasesale-hit', 'click', function(e){
                e.preventDefault();
                $('.hidden-gld-excel-section').show();
                var beg_date=$('.stcgldbegdate').val();
                var end_date=$('.stcgldenddate').val();
                var stcgldsearch=$('.stcgldsearch').val();
                var branch =$('.stc-gld-sale:checked').val();
                $.ajax({
                    url     : "kattegat/ragnar_reports.php",
                    method  : "post",
                    data    : {
                        stc_find_gld_purchase_sale_reports:1,
                        beg_date:beg_date,
                        end_date:end_date,
                        stcgldsearch:stcgldsearch,
                        branch:branch
                    },
                    success : function(response_sandp){
                        // console.log(response_sandp);
                        $('.stc-reports-gld-purchasesale-view').html(response_sandp);
                    }
                });
            });

            // create excel
            $('body').delegate('.stc-gld-exportexcel-hit', 'click', function(e){
                e.preventDefault();
                $("#stc-reports-gld-pending-view").table2excel({
                    filename: "stc-gld-reports.xls"
                });
            });
        });
    </script>
</body>
</html>
<div class="modal fade bd-projectdetails-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Project Details Show</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xl-12">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title" align="center">Project Details</h5>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">Project Name</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" disabled id="stc-ag-project-id">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Customer Name</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" disabled id="stc-ag-cust-name" placeholder="Enter Customer Name">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Customer Email Id</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" disabled id="stc-ag-cust-emailid" placeholder="Enter Email Id">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Customer Contact Number</h5>
                                        <div class="position-relative form-group">
                                            <input type="number" class="form-control" disabled id="stc-ag-cont-number" placeholder="Enter Customer Contact Number">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Purpose</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" disabled id="stc-ag-purpose" placeholder="Enter Purpose">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Ref Contact</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" disabled id="stc-ag-ref-contact" placeholder="Enter Reference contact">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">Job Details</h5>
                                        <div class="position-relative form-group">
                                            <textarea class="form-control" disabled id="stc-ag-job-details" placeholder="Enter Job Details"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xl-12">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title" align="center">Quotation Details</h5>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Quotation Number</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" disabled id="stc-ag-quot-number" placeholder="Enter Quotation Number">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Quotation Date</h5>
                                        <div class="position-relative form-group">
                                            <input type="date" class="form-control" disabled id="stc-ag-quote-date" placeholder="Enter Quotation Date">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Basic Value</h5>
                                        <div class="position-relative form-group">
                                            <input type="number" class="form-control" disabled id="stc-ag-basic-value" placeholder="Enter Basic Value">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">GST Value</h5>
                                        <div class="position-relative form-group">
                                            <input type="number" class="form-control" disabled id="stc-ag-gst-value" placeholder="Enter GST Value">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Quotation By</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" disabled id="stc-ag-quoted-by" placeholder="Enter Quotation By">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Mode of Quotation</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" disabled id="stc-ag-mode-of-quotation" placeholder="Enter Mode of Quotation">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xl-12">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title" align="center">Customer Feedback</h5>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Target Price</h5>
                                        <div class="position-relative form-group">
                                            <input type="number" class="form-control" disabled id="stc-ag-target-price" placeholder="Enter Target Price">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Status</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" disabled id="stc-ag-status" placeholder="Enter Status">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">Remarks</h5>
                                        <div class="position-relative form-group">
                                            <textarea class="form-control" disabled id="stc-ag-remarks" placeholder="Enter Remarks">
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xl-12">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title" align="center">Order Details</h5>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">PO Number</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" disabled id="stc-ag-po-number" placeholder="Enter PO Number">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">PO Value</h5>
                                        <div class="position-relative form-group">
                                            <input type="number" class="form-control" disabled id="stc-ag-po-value" placeholder="Enter PO Value">
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-12 col-sm-12 col-xl-12">
                                        <div class="position-relative form-group">
                                            <button type="submit" class="form-control btn btn-primary stc-ag-edit-save-project-record">Edit</button>
                                            <button type="submit" class="form-control btn btn-primary stc-ag-edit-save-project-record" style="display:none;">Save</button>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
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
<!-- canteen report modal -->
<div class="modal fade bd-example-modal-xl stc-school-showdeep-res" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">School Canteen</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
            <h5>School Canteen</h5>
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th class="text-center">Time</th>
                  <th class="text-center">Student</th>
                  <th class="text-center">Teacher</th>
                  <th class="text-center">Staff</th>
                  <th class="text-center">Guest</th>
                </tr>
              </thead>
              <tbody class="stc-show-canteen-nested-show">
                
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- search filter for status down list -->
<div class="modal fade bd-stdfilter-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Status Down List Filter</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xl-12">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 col-sm-12 col-xl-4 mb-4">
                                        <h5 class="card-title">From</h5>
                                        <div class="position-relative form-group">
                                            <input type="date" class="form-control std-filter-date-from" placeholder="search here">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4 mb-4">
                                        <h5 class="card-title">To</h5>
                                        <div class="position-relative form-group">
                                            <input type="date" class="form-control std-filter-date-to" placeholder="search here">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4 mb-4">
                                        <h5 class="card-title">Location</h5>
                                        <div class="position-relative form-group">
                                            <select class="form-control std-filter-location">
                                                <option value="NA">SELECT</option>
                                                <?php 
                                                    include_once("../MCU/db.php");
                                                    $dept_qry=mysqli_query($con, "
                                                        SELECT DISTINCT `stc_status_down_list_plocation`
                                                        FROM `stc_status_down_list`
                                                        WHERE `stc_status_down_list_plocation`<>''
                                                        ORDER BY `stc_status_down_list_plocation` ASC
                                                    ");
                                                    foreach($dept_qry as $dept_row){
                                                        echo '<option>'.$dept_row['stc_status_down_list_plocation'].'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4 mb-4">
                                        <h5 class="card-title">Department</h5>
                                        <div class="position-relative form-group">
                                            <select class="form-control std-filter-department">
                                                <option value="NA">SELECT LOCATION FIRST</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4 mb-4">
                                        <h5 class="card-title">Type Of Job</h5>
                                        <div class="position-relative form-group">
                                            <select class="form-control std-filter-typeofjob">
                                                <option value="NA">SELECT</option>
                                                <option>BREAKDOWN MAINTENANCE</option>
                                                <option>CALL ATTEND</option>
                                                <option>DAILY JOB ACTIVITY</option>
                                                <option>PREVENTIVE MAINTENANCE</option>
                                                <option>WINTER OVERHAULING</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4 mb-4">
                                        <h5 class="card-title">STATUS</h5>
                                        <div class="position-relative form-group">
                                            <select class="form-control std-filter-status">
                                                <option value="NA">SELECT</option>
                                                <option value="1">PLANNING</option>
                                                <option value="2">DOWN</option>
                                                <option value="3">WORK-IN-PROGRESS</option>
                                                <option value="4">WORK DONE</option>
                                                <option value="5">WORK COMPLETE</option>
                                                <option value="6">CLOSE</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12 mb-4">
                                        <div class="position-relative form-group">
                                            <button type="submit" class="form-control btn btn-success std-filter-find-btn">Find</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade show-jobdonedetailsmodal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title title-barreport">Job Done Details</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
            <p class="jobdonedet_para"></p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade update-sdlreportmodal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Update Status Down List</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xl-12">
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xl-12 mb-4">
                                    <h5 class="card-title">Target Date</h5>
                                    <div class="position-relative form-group">
                                        <input type="date" class="form-control std-sdl-target-date">
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xl-12 mb-4">
                                    <h5 class="card-title">Remarks</h5>
                                    <div class="position-relative form-group">
                                        <textarea class="form-control stc-sdl-remarks" placeholder="Enter remarks"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xl-12 mb-4">
                                    <div class="position-relative form-group">
                                        <input type="hidden" class="sdl-hidden-reports">
                                        <a href="javascript:void(0)" class="form-control btn btn-success sdl-update-reportsdl-save">Save<a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                                
                </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default sdlurbtn-close" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade bd-stdmaterial-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Status Down List Material</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xl-12">
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 col-sm-12 col-xl-3 mb-4">
                                    <p class="card-title">Status Down List</p>
                                    <span class="downlistno"></span><br>
                                    <span class="downlistdate"></span>
                                </div>
                                <div class="col-md-3 col-sm-12 col-xl-3 mb-4">
                                    <p class="card-title">Material Requisition</p>
                                    <span class="reqnumber"></span><br>
                                    <span class="reqdate"></span>
                                </div>
                                <div class="col-md-3 col-sm-12 col-xl-3 mb-4">
                                    <p class="card-title">Department</p>
                                    <span class="sublocation"></span>
                                </div>
                                <div class="col-md-3 col-sm-12 col-xl-3 mb-4">
                                    <p class="card-title">Supervisor Details</p>
                                    <span class="SupName"></span>
                                    <span class="SupContact"></span>
                                </div>
                                

                                <div class="col-md-12 col-sm-12 col-xl-12 mb-4">
                                    <h5 class="card-title">Material List</h5>
                                    <div class="position-relative form-group" style="overflow-x:auto;">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Date</th>
                                                    <th>Item Desc</th>
                                                    <th>Unit</th>
                                                    <th>Purchase Requisition</th>
                                                    <th>Manager Approve</th>
                                                    <th>Proc Apprv</th>
                                                    <th>Dispatch</th>
                                                    <th>Recieved</th>
                                                    <th>Pending</th>
                                                    <th>Consumed</th>
                                                    <th>Priority</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="show-material-list-sdl">
                                                
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
      <div class="modal-footer">
        <button type="button" class="btn btn-default sdlurbtn-close" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade bd-example-modal-xl stc-mrdmodal-res" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Material Requisition Details</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xl-12">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xl-6 mb-4">
                                <h5 class="card-title">Requisition Number</h5>
                                <div class="position-relative form-group">
                                    <input type="text" class="reqnumbershow" disabled>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xl-6 mb-4">
                                <h5 class="card-title">Requisition Date</h5>
                                <div class="position-relative form-group">
                                    <input type="text" class="reqdateshow" disabled>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xl-12 mb-4">
                                <h5 class="card-title">Requisition Raised By (User Name)</h5>
                                <div class="position-relative form-group">
                                    <input type="text" class="reqraisedbyshow" disabled>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xl-12 mb-4">
                                <h5 class="card-title">Requisition Raised From (Location)</h5>
                                <div class="position-relative form-group">
                                    <input type="text" class="reqraisedfromshow" disabled>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xl-12 mb-4">
                                <h5 class="card-title">Item Description</h5>
                                <div class="position-relative form-group">
                                    <input type="text" class="itemdescshow" disabled>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xl-6 mb-4">
                                <h5 class="card-title">Item Quantity</h5>
                                <div class="position-relative form-group">
                                    <input type="text" class="itemqtyshow" disabled>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xl-6 mb-4">
                                <h5 class="card-title">Item Unit</h5>
                                <div class="position-relative form-group">
                                    <input type="text" class="itemunitshow" disabled>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xl-12 mb-4">
                                <h5 class="card-title">Priority</h5>
                                <div class="position-relative form-group">
                                    <input type="text" class="itempriorityshow" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade bd-epermitfilter-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">E-Permit Attendance</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xl-12">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xl-12 mb-4">
                                
                                <table class="table table-bordered table-hover show-attendance-epermitdetails">
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>