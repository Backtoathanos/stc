<?php  
// Include authentication helper
require_once 'kattegat/auth_helper.php';

// Check authentication using the new hybrid system
STCAuthHelper::checkAuth();$page_code=509;
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
    <title>Virtual Challan - STC</title>
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
  <!-- Modal -->
    <div id="vcmodal" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Update virtual challan</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 col-md-6">
                <label>Current Challan GRN</label>
              </div>
              <div class="col-sm-12 col-md-6">
                <input type="text" class="btn btn-primary ccg-number" disabled>           
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12 col-md-6">
                <label>Change Challan GRN</label>
              </div>
              <div class="col-sm-12 col-md-6">
                <select class="custom-select stc-vc-update-grn">
                  <?php
                    include_once("../MCU/db.php");                        
                    $qry=mysqli_query($con, "SELECT `stc_product_grn_id` FROM `stc_product_grn` ORDER BY `stc_product_grn_id` DESC");
                    foreach($qry as $row){
                        echo '
                          <option value="'.$row['stc_product_grn_id'].'">GRN/'.substr("0000{$row['stc_product_grn_id']}", -5).'</option>
                        ';
                    }
                  ?>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-12 col-md-6">
              </div>
              <div class="col-sm-12 col-md-6">
                <input type="hidden" class="stc-vc-update">
                <button type="button" class="btn btn-success update-challan-hit">Update</button>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <?php include_once("header-nav.php");?>
        <div class="app-main">
            <?php include_once("sidebar-nav.php");?>                   
            <div class="app-main__outer">
                    <div class="app-main__inner"> 
                        <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                            <li class="nav-item">
                                <a role="tab" class="nav-link active" id="tab-1" data-toggle="tab" href="#tab-content-1">
                                    <span>View Virtual Challan</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-content-2">
                                    <span>Create Virtual Challan</span>
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
                                              >View Virtual Challan
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
                                                <th scope="col" width="20%">By Customer</th>
                                                <th scope="col" width="20%">By Challan Number<br>GRN Number</th>
                                                <th scope="col" width="20%">By Site</th>
                                                <th scope="col" width="20%">By Status</th>
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
                                                    id="stc-challan-customer-in"
                                                    class="custom-select form-control stc-select-customer"
                                                    name="stcvendor"
                                                  >
                                                  </select>
                                                </td>
                                                <td>
                                                  <input 
                                                    type="number" 
                                                    id="stc-challan-number-finder" 
                                                    class="form-control"
                                                    placeholder="Challan Number" 
                                                  >                                             
                                                  <input 
                                                    type="number" 
                                                    id="stc-challan-grnno-finder" 
                                                    class="form-control"
                                                    placeholder="GRN Number" 
                                                  >
                                                </td>
                                                <td>
                                                  <input 
                                                    type="text" 
                                                    id="stc-challan-sitename-finder" 
                                                    class="form-control"
                                                    placeholder="Site Name" 
                                                  >
                                                </td>
                                                <td>
                                                  <select 
                                                    class="custom-select form-control stc-challan-status-in"
                                                    >
                                                    <option value="NA">Select Status</option>
                                                    <option value="billed">Billed</option>
                                                    <option value="direct_sale">Direct Sale</option>
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
                                    <div class="card-border mb-3 card card-body border-success">
                                      <form action="" class="stc-view-purchase-order-form">
                                          <table class="table table-hover table-bordered table-dark">
                                            <thead>
                                              <tr>
                                                <th scope="col">Customer Name</th> 
                                                <th scope="col">Challan Date<br>Challan No</th>
                                                <th scope="col">Customer Order Date<br>Customer Order No</th>
                                                <th scope="col">Way Bill No</th>
                                                <th scope="col">Customer Site</th>
                                                <th scope="col">Basic Value<br> Total Value</th>
                                                <th scope="col">Status</th>  
                                                <th scope="col">Refrence</th>                         
                                                <th scope="col">Action</th>
                                              </tr>
                                            </thead>
                                            <tbody class="stc-call-view-challan-row">
                                              <tr>
                                                <td colspan="8" align="center">Search here</td>
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
                                              >Create Virtual Challan
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                  <div class="col-sm-12 col-xl-3 tm-block-col">
                                    <div class="form-group mb-3">
                                      <h5
                                        for="name" 
                                        class="text-uppercase"
                                        >Choose GRN Number
                                      </h5>
                                    </div>
                                  </div>
                                  <div class="col-sm-12 col-xl-6">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <select class="custom-select stc-vc-grn">
                                        <?php
                                          include_once("../MCU/db.php");                        
                                          $qry=mysqli_query($con, "
                                              SELECT `stc_product_grn_id` 
                                              FROM `stc_product_grn` 
                                              ORDER BY `stc_product_grn_id` 
                                              DESC LIMIT 0,50
                                          ");
                                          foreach($qry as $row){
                                            $checkqry=mysqli_query($con, "
                                                SELECT `stc_sale_product_id` 
                                                FROM `stc_sale_product` 
                                                WHERE `stc_sale_product_refr_grn_no`='".$row['stc_product_grn_id']."'
                                            ");
                                            if(empty(mysqli_num_rows($checkqry))){
                                              echo '
                                                <option value="'.$row['stc_product_grn_id'].'">GRN/'.substr("0000{$row['stc_product_grn_id']}", -5).'</option>
                                              ';
                                            }
                                          }
                                        ?>
                                      </select>
                                    </div>
                                  </div>
                                  <div class="col-sm-12 col-xl-3 tm-block-col">
                                    <div class="card-border mb-3 card card-body border-success">
                                        <a href="#" class="btn btn-secondary btn-block text-uppercase stc-vc-grn-hit">
                                         Set <i class="fas fa-thumbs-up"></i>
                                        </a>
                                    </div>
                                  </div>
                                </div>
                                <form action="" class="stc-add-sale-product-form">
                                  <div class="row stc-sale-challan-row">
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="name"
                                          >Challan Number
                                        </h5>
                                        <input
                                          id="gtonumbershow"
                                          name="stcmername"
                                          type="text"
                                          placeholder="Virtual Challan Number"
                                          class="form-control validate"
                                          disabled
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for=""
                                          >Challan Date
                                        </h5>
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
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="name"
                                          >Customer Name
                                        </h5>
                                        <select
                                          id="virtualchallancustomer"
                                          class="custom-select stc-select-customer"
                                          name="stccustomer"
                                        >
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for=""
                                          >Customer Order Number
                                        </h5>
                                        <input
                                          name="virtualchallancustordernumber"
                                          type="text"
                                          class="form-control validate virtualchallancustordernumber"
                                          placeholder="Customer Order Number"
                                          value="VERBAL"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for=""
                                          >Customer Order Date
                                        </h5>
                                        <input
                                          id="datepicke1r"
                                          name="virtualchallancustorderdate"
                                          type="date"
                                          min="2001-01-01" 
                                          max="2050-12-31"
                                          placeholder="Customer Order Date"
                                          class="form-control validate virtualchallancustorderdate"
                                          value=""
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for=""
                                          >Way Bill Number
                                        </h5>
                                        <input
                                          name="virtualchallanwaybillno"
                                          type="text"
                                          class="form-control validate virtualchallanwaybillno"
                                          placeholder="Way Bill No"
                                          value="NA"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for=""
                                          >LR Number
                                        </h5>
                                        <input
                                          name="virtualchallanlrno"
                                          type="text"
                                          class="form-control validate virtualchallanlrno"
                                          placeholder="LR No"
                                          value="NA"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for=""
                                          >Date of Supply
                                        </h5>
                                        <input
                                          name="virtualchallandatesupply"
                                          type="text"
                                          class="form-control validate virtualchallandatesupply"
                                          placeholder="Date of Supply"
                                          value="NA"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for=""
                                          >Place of Supply
                                        </h5>
                                        <input
                                          name="virtualchallanplacesupply"
                                          type="text"
                                          class="form-control validate virtualchallanplacesupply"
                                          placeholder="Place of Supply"
                                          value="NA"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for=""
                                          >Order Refrence
                                        </h5>
                                        <input
                                          name="virtualchallanordrefrence"
                                          type="text"
                                          class="form-control validate virtualchallanordrefrence"
                                          placeholder="Customer Refrence"
                                          value="VERBAL"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for=""
                                          >Site Name
                                        </h5>
                                        <input
                                          name="virtualchallancustsitename"
                                          type="text"
                                          class="form-control validate virtualchallancustsitename"
                                          placeholder="Site Name"
                                          value="NA"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for=""
                                          >Contact Person
                                        </h5>
                                        <input
                                          name="virtualchallancustcontperson"
                                          type="text"
                                          class="form-control validate virtualchallancustcontperson"
                                          placeholder="Contact Person"
                                          value="RAZIULLAH"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for=""
                                          >Contact Number
                                        </h5>
                                        <input
                                          name="virtualchallancustcontnumber"
                                          type="text"
                                          class="form-control validate virtualchallancustcontnumber"
                                          placeholder="Contact Number"
                                          value="9204364798"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for=""
                                          >Address
                                        </h5>
                                        <textarea
                                          class="form-control validate"
                                          rows="2"
                                          id="stcvcshipaddress"
                                          placeholder="Shipping Address"
                                          required
                                        ></textarea>
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for=""
                                          >Terms & Condition
                                        </h5>
                                        <textarea
                                          class="form-control validate"
                                          rows="2"
                                          id="stcvctandc"
                                          placeholder="Terms & Condition"
                                          required
                                        ></textarea>
                                      </div>
                                    </div>

                                    <!-- search product display -->
                                    <div class="col-xl-12 col-md-12 col-sm-12 thishypo">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <div class="row stc-call-view-sale-product-row">
                                        </div>
                                      </div>
                                    </div>

                                    <!-- line item table -->
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success stc-vc-line-items-table">
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
                                          id="stcvcnotes"
                                          placeholder="Notes"
                                          required
                                        ></textarea>
                                      </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <button type="submit" class="btn btn-primary btn-block text-uppercase stcsavevirtualchallan">Save</button>
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
          // call customer for sale
          stc_call_customer();
          function stc_call_customer(){
            $.ajax({
              url       : "kattegat/ragnar_sale.php",
              method    : "post",
              data      : {friday_vendor:1},
              dataType  : 'JSON',
              success   : function(data){
                // console.log(data);
                $('#expire_date').val(data['time']);
                $('.stc-select-customer').html(data['customer']);
              }
            });
          }

          // add to virtual challan items cart from grn
          $('body').delegate('.stc-vc-grn-hit', 'click', function(e){
            e.preventDefault();
            var grn_no=$('.stc-vc-grn').val();
            $.ajax({
              url       : "kattegat/ragnar_sale.php",
              method    : "post",
              data      : {
                act_on_vc_hit:1,
                grn_no:grn_no
              },
              success   : function(responses){
                // console.log(responses);
                alert(responses);
                get_records();
              }
            });
          });

          get_records();
          function get_records(){
            $.ajax({
              url       : "kattegat/ragnar_sale.php",
              method    : "post",
              data      : {
                get_items_cart_from_grn:1
              },
              success   : function(responses){
                // console.log(responses);
                $('.stc-vc-line-items-table').html(responses);
              }
            });
          }

          // delete from virtual challan cart
          $('body').delegate('.stcdelvcbtn', 'click', function(e){
            e.preventDefault();
            var del_vc_id=$(this).attr("id");
            $.ajax({
              url       : "kattegat/ragnar_sale.php",
              method  : "post",
              data    : {
                del_grn_id_vc:1,
                del_vc_id:del_vc_id
              },
              success : function(responses){
                // console.log(responses);
                alert(responses);
                get_records();
              }
            });
          });

          // save virtual challan
          $(document).on('click', '.stcsavevirtualchallan', function(e){
            e.preventDefault();
            var customer_id         = $('#virtualchallancustomer').val();
            var order_invodate      = $('.virtualchallancustorderdate').val();
            var order_invonumber    = $('.virtualchallancustordernumber').val();
            var order_waybillno     = $('.virtualchallanwaybillno').val();
            var order_lrno          = $('.virtualchallanlrno').val();
            var order_supplydate    = $('.virtualchallandatesupply').val();
            var order_supplyplace   = $('.virtualchallanplacesupply').val();
            var order_refrence      = $('.virtualchallanordrefrence').val();
            var order_sitename      = $('.virtualchallancustsitename').val();
            var order_contperson    = $('.virtualchallancustcontperson').val();
            var order_contnumber    = $('.virtualchallancustcontnumber').val();
            var order_shipaddress   = $('#stcvcshipaddress').val();
            var order_stcfc         = $('.stcfc').val();
            var order_stcpf         = $('.stcpf').val();
            var order_notes         = $('#stcvcnotes').val();
            var order_tandc         = $('#stcvctandc').val();   
            var order_grnno         = $('.stc-vc-grn').val();    
            $.ajax({  
              url       : "kattegat/ragnar_sale.php",
              method    : "POST",  
              data      : {
                customer_id:customer_id,
                sale_custorderdate:order_invodate,
                sale_custordernumber:order_invonumber,
                sale_waybillno:order_waybillno,
                sale_lrno:order_lrno,
                sale_supplydate:order_supplydate,
                sale_supplyplace:order_supplyplace,
                sale_refrence:order_refrence,
                sale_sitename:order_sitename,
                sale_contperson:order_contperson,
                sale_contnumber:order_contnumber,
                sale_shipaddress:order_shipaddress,
                sale_stcfc:order_stcfc,
                sale_stcpf:order_stcpf,
                order_notes:order_notes,
                order_tandc:order_tandc,
                order_grnno:order_grnno,
                save_vc_action:1
              },
              // dataType: `JSON`,
              success   : function(data){
               // console.log(data);
                alert(data);
                // $('.stc-add-sale-product-form')[0].reset();
                window.location.reload();
              }
            });
          });
        });
    </script>
    <script>
        $(document).ready(function(){
          var jsbegdate='';
          var jsenddate='';
          var jscustomerid='';
          var jschallannumber='';
          var jschallangrnno='';
          var jsstatus='';
          var jsbegvalue='';
          var jsendvalue='';
          // filter challan
          // by date
          $('body').delegate('#purchaseproddatefilt', 'click', function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jscustomerid=$("#stc-challan-customer-in").val();
            jschallannumber=$("#stc-challan-number-finder").val();
            jschallangrnno=$("#stc-challan-grnno-finder").val();
            jschallansitename=$("#stc-challan-sitename-finder").val();
            jsstatus=$(".stc-challan-status-in").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            stc_filter_virtual_challan(jsbegdate, jsenddate, jscustomerid, jschallannumber, jschallangrnno, jschallansitename, jsstatus,jsbegvalue, jsendvalue);
          });

          // by customer id
          $("#stc-challan-customer-in").change(function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jscustomerid=$("#stc-challan-customer-in").val();
            jschallannumber=$("#stc-challan-number-finder").val();
            jschallangrnno=$("#stc-challan-grnno-finder").val();
            jschallansitename=$("#stc-challan-sitename-finder").val();
            jsstatus=$(".stc-challan-status-in").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            stc_filter_virtual_challan(jsbegdate, jsenddate, jscustomerid, jschallannumber, jschallangrnno, jschallansitename, jsstatus,jsbegvalue, jsendvalue);
          });

          // by challan number
          $("#stc-challan-number-finder").on('keyup', function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jscustomerid=$("#stc-challan-customer-in").val();
            jschallannumber=$("#stc-challan-number-finder").val();
            jschallangrnno=$("#stc-challan-grnno-finder").val();
            jschallansitename=$("#stc-challan-sitename-finder").val();
            jsstatus=$(".stc-challan-status-in").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            stc_filter_virtual_challan(jsbegdate, jsenddate, jscustomerid, jschallannumber, jschallangrnno, jschallansitename, jsstatus,jsbegvalue, jsendvalue);
          });

          // by invoice number
          $("#stc-challan-grnno-finder").on('keyup', function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jscustomerid=$("#stc-challan-customer-in").val();
            jschallannumber=$("#stc-challan-number-finder").val();
            jschallangrnno=$("#stc-challan-grnno-finder").val();
            jschallansitename=$("#stc-challan-sitename-finder").val();
            jsstatus=$(".stc-challan-status-in").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            stc_filter_virtual_challan(jsbegdate, jsenddate, jscustomerid, jschallannumber, jschallangrnno, jschallansitename, jsstatus,jsbegvalue, jsendvalue);
          });

          // by sitenemae number
          $("#stc-challan-sitename-finder").on('keyup', function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jscustomerid=$("#stc-challan-customer-in").val();
            jschallannumber=$("#stc-challan-number-finder").val();
            jschallangrnno=$("#stc-challan-grnno-finder").val();
            jschallansitename=$("#stc-challan-sitename-finder").val();
            jsstatus=$(".stc-challan-status-in").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            stc_filter_virtual_challan(jsbegdate, jsenddate, jscustomerid, jschallannumber, jschallangrnno, jschallansitename, jsstatus,jsbegvalue, jsendvalue);
          });

          // by status
          $(".stc-challan-status-in").change(function(e){
            e.preventDefault();
            jsbegdate=$('.begdate').val();
            jsenddate=$('.enddate').val();
            jscustomerid=$("#stc-challan-customer-in").val();
            jschallannumber=$("#stc-challan-number-finder").val();
            jschallangrnno=$("#stc-challan-grnno-finder").val();
            jschallansitename=$("#stc-challan-sitename-finder").val();
            jsstatus=$(".stc-challan-status-in").val();
            jsbegvalue=$('.begvalueinputsearch').val();
            jsendvalue=$('.endvalueinputsearch').val();
            stc_filter_virtual_challan(jsbegdate, jsenddate, jscustomerid, jschallannumber, jschallangrnno, jschallansitename, jsstatus,jsbegvalue, jsendvalue);
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
              jscustomerid=$("#stc-challan-customer-in").val();
              jschallannumber=$("#stc-challan-number-finder").val();
              jschallangrnno=$("#stc-challan-grnno-finder").val();
              jschallansitename=$("#stc-challan-sitename-finder").val();
              jsstatus=$(".stc-challan-status-in").val();
              jsbegvalue=outbegvalueinputted;
              jsendvalue=outendvalueinputted;
              stc_filter_virtual_challan(jsbegdate, jsenddate, jscustomerid, jschallannumber, jschallangrnno, jschallansitename, jsstatus,jsbegvalue, jsendvalue);
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
              jscustomerid=$("#stc-challan-customer-in").val();
              jschallannumber=$("#stc-challan-number-finder").val();
              jschallangrnno=$("#stc-challan-grnno-finder").val();
              jschallansitename=$("#stc-challan-sitename-finder").val();
              jsstatus=$(".stc-challan-status-in").val();
              jsbegvalue=outbegvalueinputted;
              jsendvalue=outendvalueinputted;
              stc_filter_virtual_challan(jsbegdate, jsenddate, jscustomerid, jschallannumber, jschallangrnno, jschallansitename, jsstatus,jsbegvalue, jsendvalue);
            }
          });

          // filter function
          function stc_filter_virtual_challan(jsbegdate, jsenddate, jscustomerid, jschallannumber, jschallangrnno, jschallansitename, jsstatus,jsbegvalue, jsendvalue){
            $.ajax({
              url     : "kattegat/ragnar_sale.php",
              method  : "post",
              data    : {
                stcvcaction:1,
                phpbegdate:jsbegdate, 
                phpenddate:jsenddate, 
                phpcustomerid:jscustomerid, 
                phpchallannumber:jschallannumber,
                phpchallangrnno:jschallangrnno,
                phpchallansitename:jschallansitename, 
                phpstatus:jsstatus,
                phpbegvalue:jsbegvalue,
                phpendvalue:jsendvalue
              },
              // dataType : 'JSON',
              success : function(data){
                // console.log(data);
                $('.stc-call-view-challan-row').html(data);
              }
            });
          }

          var vc_id;
          var vc_grn;
          var vc_new_grn;
          $('body').delegate('.vcmodal', 'click', function(e){
            e.preventDefault();
            vc_id=$(this).attr("id");
            vc_grn=$(this).attr("grn");
            vc_new_grn=$(".stc-vc-update-grn").val();
            $('.ccg-number').val(vc_grn);
            $('#vcmodal').modal('show');
          });

          $('body').on('click', '.update-challan-hit', function(e){
            e.preventDefault();
            $.ajax({
              url     : "kattegat/ragnar_sale.php",
              method  : "POST",
              data    : {
                update_vc:1,
                vc_id:vc_id,
                vc_grn:vc_grn,
                vc_new_grn:vc_new_grn
              },
              success : function(update){
                // console.log(update);
                alert(update);
              }
            });
          });
        });
    </script>
</body>
</html>