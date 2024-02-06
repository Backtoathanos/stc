<?php  
session_start(); 
if(isset($_SESSION["stc_empl_id"]) && ($_SESSION["stc_empl_role"]>0)){ 
}else{ 
    header("Location:index.html"); 
} 
$page_code=401;
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
    <title>Purchase Product - STC</title>
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
        font-size: 15px;
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
                        <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-content-2">
                                    <span>View All Purchase Order Adhoc</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link active" id="tab-1" data-toggle="tab" href="#tab-content-1">
                                    <span>Create New Purchase Order Adhoc</span>
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
                                              >Purchase Order Adhoc
                                            </h5>
                                        </div>
                                    </div>
                                </div>                                
                                <div class="row">
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <form action="" class="stc-add-poadhoc-product-form">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="itemname"
                                          >Item Name
                                        </h5>
                                        <textarea
                                          id="itemname"
                                          name="itemname"
                                          type="text"
                                          placeholder="Item Name"
                                          class="form-control validate"
                                        ></textarea>
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-6">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="quantity"
                                          >Quantity
                                        </h5>
                                        <input
                                          id="quantity"
                                          name="quantity"
                                          type="text"
                                          placeholder="Quantity"
                                          class="form-control validate"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-6">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="unit"
                                          >Unit
                                        </h5>
                                        <input
                                          id="unit"
                                          name="unit"
                                          type="text"
                                          placeholder="Unit"
                                          class="form-control validate"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="rack"
                                          >Rack
                                        </h5>
                                        <select 
                                          id="rack"
                                          name="rack"
                                          class="form-control validate"
                                        ><option value="NA">Select</option>
                                          <?php
                                            include_once("../MCU/db.php");
                                            $rackqry=mysqli_query($con, "
                                              SELECT `stc_rack_id`, `stc_rack_name` FROM `stc_rack` ORDER BY `stc_rack_name` ASC
                                            ");
                                            foreach($rackqry as $rackqrow){
                                              echo '<option value="'.$rackqrow['stc_rack_id'].'">'.$rackqrow['stc_rack_name'].'</option>';
                                            }
                                          ?>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="condition"
                                          >Condition
                                        </h5>
                                        <select 
                                          id="condition"
                                          name="condition"
                                          class="form-control validate"
                                        ><option value="NA">Select</option>
                                        <option value="Bad">Bad</option>
                                        <option value="Broken">Broken</option>
                                        <option value="Good">Good</option>
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-6">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="source"
                                          >From (Source/Location)
                                        </h5>
                                        <input
                                          id="source"
                                          name="sourcerack"
                                          type="text"
                                          placeholder="From (Source/Location)"
                                          class="form-control validate"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-6">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="destination"
                                          >To (Destination/Location)
                                        </h5>
                                        <input
                                          id="destination"
                                          name="destination"
                                          type="text"
                                          placeholder="To (Destination/Location)"
                                          class="form-control validate"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="remarks"
                                          >Remarks
                                        </h5>
                                        <textarea
                                          id="remarks"
                                          name="remarks"
                                          type="text"
                                          placeholder="Remarks"
                                          class="form-control validate"
                                        ></textarea>
                                      </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <button class="btn btn-primary stc-poadhoc-save">Save</button>
                                      </div>
                                    </div>
                                  </form>
                                </div>
                            </div>
                            <div class="tab-pane tabs-animation fade" id="tab-content-2" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >View Purchase Order Adhoc
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row stc-view-product-row">
                                  <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <form action="" class="stc-view-product-form">
                                          <table class="table table-hover table-bordered">
                                            <thead>
                                              <tr>
                                                <th scope="col" class="text-center">By Item Desc</th>
                                                <th scope="col" class="text-center">By Source/Destination (Location) </th>
                                                <th scope="col" class="text-center">By Rack</th>
                                                <th scope="col" class="text-center">By Status</th>
                                                <th scope="col" class="text-center">Action</th>
                                              </tr>
                                            </thead>
                                            <tbody>
                                              <tr>
                                                <td>
                                                  <input 
                                                    type="text"
                                                    class="form-control stc-poa-searchbyitem"
                                                    id="stc-poa-searchbyitem"
                                                    placeholder="Item name" 
                                                  >
                                                </td>
                                                <td>
                                                  <input 
                                                    type="text" 
                                                    id="tc-poa-searchbydourcedestination" 
                                                    class="form-control"
                                                    placeholder="Source/destination (Location)" 
                                                  >
                                                </td>
                                                <td>
                                                  <input 
                                                    type="text" 
                                                    class="form-control tc-poa-searchbyrack"
                                                    placeholder="Rack" 
                                                  >
                                                </td>
                                                <td>
                                                  <select 
                                                    class="custom-select form-control stc-po-status-in"
                                                    >
                                                    <option value="NA">Select Status</option>
                                                    <option value="0">Stock</option>
                                                    <option value="1">Dispatched</option>
                                                  </select>
                                                </td>
                                                <td>
                                                  <a class="btn btn-success stc-adhocpo-find" href="javascript:void(0)">Find</a>
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
                                      <form action="" class="stc-view-purchase-order-form" style="overflow-x:auto;">
                                          <table class="table table-hover table-bordered stc-purchase-view-table">
                                            <thead>
                                              <th>Sl No.</th>
                                              <th>Date</th>
                                              <th>Linked Product</th>
                                              <th>Item Name</th>
                                              <th>Rack</th>
                                              <th>Unit</th>
                                              <th>Quantity</th>
                                              <th>Stock</th>
                                              <th>Dispatched Details</th>
                                              <th>From Source (Supplier/Location)</th>
                                              <th>To Destination (Location)</th>
                                              <th>Condition</th>
                                              <th>Payment Term</th>
                                              <th>Received By</th>
                                              <th>Created By</th>
                                              <th>Created Date</th>
                                              <th>Updated By</th>
                                              <th>Updated Date</th>
                                              <th>Status</th>
                                              <th>Remarks</th>
                                              <th>Action</th>
                                            </thead>
                                            <tbody class="stc-call-view-poadhoc-row">
                                              <tr>
                                                <td>1</td>
                                                <td>01-02-2024</td>
                                                <td>Channel</td>
                                                <td>Channel 75X40</td>
                                                <td>A1</td>
                                                <td>Kgs</td>
                                                <td>10.00</td>
                                                <td>10.00</td>
                                                <td><a class="btn btn-success" title="Dispatched details"><i class="fa fa-table"></i></a></td>
                                                <td>Global</td>
                                                <td>Ranchi</td>
                                                <td>Good</td>
                                                <td>NA</td>
                                                <td>Dalim</td>
                                                <td>Ramapudey</td>
                                                <td>Hasibul Mondal</td>
                                                <td>01-02-2024</td>
                                                <td>Shahnawaz Ahmad</td>
                                                <td>01-02-2024</td>
                                                <td>Active</td>
                                                <td>
                                                  <a class="btn btn-primary" title="Payment details"><i class="fa fa-credit-card"></i></a>
                                                  <a class="btn btn-success" title="Recieved By"><i class="fa fa-file"></i></a>
                                                  <a class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a>
                                                </td>
                                              </tr>
                                              <tr>
                                                <td>2</td>
                                                <td>01-01-2024</td>
                                                <td>Angle</td>
                                                <td>Angle 75X40</td>
                                                <td>A2</td>
                                                <td>Nos</td>
                                                <td>10.00</td>
                                                <td>10.00</td>
                                                <td><a class="btn btn-success" title="Dispatched details"><i class="fa fa-table"></i></a></td>
                                                <td>Global</td>
                                                <td>Dhatkidih</td>
                                                <td>Good</td>
                                                <td>NA</td>
                                                <td>Rafique</td>
                                                <td>Ramapudey</td>
                                                <td>Hasibul Mondal</td>
                                                <td>04-01-2024</td>
                                                <td>Shahnawaz Ahmad</td>
                                                <td>01-02-2024</td>
                                                <td>Active</td>
                                                <td>
                                                  <a class="btn btn-primary" title="Payment details"><i class="fa fa-credit-card"></i></a>
                                                  <a class="btn btn-success" title="Recieved By"><i class="fa fa-file"></i></a>
                                                  <a class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a>
                                                </td>
                                              </tr>
                                              <tr>
                                                <td>3</td>
                                                <td>01-02-2024</td>
                                                <td>MS Pipe</td>
                                                <td>MS Pipe 50mm</td>
                                                <td>B33</td>
                                                <td>Nos</td>
                                                <td>10.00</td>
                                                <td>10.00</td>
                                                <td><a class="btn btn-success" title="Dispatched details"><i class="fa fa-table"></i></a></td>
                                                <td>MTMH</td>
                                                <td>Jajpur</td>
                                                <td>Good</td>
                                                <td>NA</td>
                                                <td>Pushpendu</td>
                                                <td>Ramapudey</td>
                                                <td>Hasibul Mondal</td>
                                                <td>01-02-2024</td>
                                                <td>Shahnawaz Ahmad</td>
                                                <td>01-02-2024</td>
                                                <td>Active</td>
                                                <td>
                                                  <a class="btn btn-primary" title="Payment details"><i class="fa fa-credit-card"></i></a>
                                                  <a class="btn btn-success" title="Recieved By"><i class="fa fa-file"></i></a>
                                                  <a class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a>
                                                </td>
                                              </tr>
                                              <tr>
                                                <td>4</td>
                                                <td>10-01-2024</td>
                                                <td>Nipple 3 X 4</td>
                                                <td>Nipple</td>
                                                <td>A1</td>
                                                <td>Pcs</td>
                                                <td>10.00</td>
                                                <td>10.00</td>
                                                <td><a class="btn btn-success" title="Dispatched details"><i class="fa fa-table"></i></a></td>
                                                <td>Sara</td>
                                                <td>Hindalco</td>
                                                <td>Good</td>
                                                <td>NA</td>
                                                <td>Safikul</td>
                                                <td>Ramapudey</td>
                                                <td>Hasibul Mondal</td>
                                                <td>01-02-2024</td>
                                                <td>Shahnawaz Ahmad</td>
                                                <td>01-02-2024</td>
                                                <td>Active</td>
                                                <td>
                                                  <a class="btn btn-primary" title="Payment details"><i class="fa fa-credit-card"></i></a>
                                                  <a class="btn btn-success" title="Recieved By"><i class="fa fa-file"></i></a>
                                                  <a class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a>
                                                </td>
                                              </tr>
                                              <tr>
                                                <td>5</td>
                                                <td>01-02-2024</td>
                                                <td>Float Valve</td>
                                                <td>Float Valve</td>
                                                <td>C87</td>
                                                <td>Kgs</td>
                                                <td>10.00</td>
                                                <td>10.00</td>
                                                <td><a class="btn btn-success" title="Dispatched details"><i class="fa fa-table"></i></a></td>
                                                <td>S.S Enterprises</td>
                                                <td>All</td>
                                                <td>Good</td>
                                                <td>Paid 15,000.00 to owner at 01-02-2024</td>
                                                <td>Owner</td>
                                                <td>Ramapudey</td>
                                                <td>Hasibul Mondal</td>
                                                <td>01-02-2024</td>
                                                <td>Hasibul Mondal</td>
                                                <td>01-02-2024</td>
                                                <td>Active</td>
                                                <td>
                                                  <a class="btn btn-primary" title="Payment details"><i class="fa fa-credit-card"></i></a>
                                                  <a class="btn btn-success" title="Recieved By"><i class="fa fa-file"></i></a>
                                                  <a class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i></a>
                                                </td>
                                              </tr>
                                              <!-- <tr>
                                                <td colspan="19" align="center">Search here</td>
                                              </tr> -->
                                              <tr>
                                              <td colspan="3">
                                                <button type="button" class="btn btn-primary begbuttoninvsearch">
                                                  <i class="fas fa-arrow-left"></i>
                                                </button>
                                                <input type="hidden" class="begvalueinput" value="0">
                                                <input type="hidden" class="begvalueinputsearch" value="0">
                                              
                                                <button type="button" class="btn btn-primary endbuttoninvsearch" style="float:right;">
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
          $('body').delegate('.stc-adhocpo-find', 'click', function(e){
            e.preventDefault();
            var itemname=$('#stc-poa-searchbyitem').val();
            var sourcedestination=$('#tc-poa-searchbydourcedestination').val();
            var byrack=$('.tc-poa-searchbyrack').val();
            var status=$('.stc-po-status-in').val();
            stc_call_poadhoc(itemname, sourcedestination, byrack, status); 
          });

          // call merchant for purchase
          // stc_call_poadhoc('', '', '', '');
          function stc_call_poadhoc(itemname, sourcedestination, byrack, status){
            $.ajax({
              url       : "kattegat/ragnar_purchase.php",
              method    : "post",
              data      : {
                stc_call_poadhoc:1,
                itemname: itemname, 
                sourcedestination: sourcedestination, 
                byrack: byrack, 
                status: status
              },
              success   : function(data){
                // console.log(data);
                $('.stc-call-view-poadhoc-row').html(data);
              }
            });
          }

          // save po adhoc
          $('body').delegate('.stc-poadhoc-save', 'click', function(e){
            e.preventDefault();
            var itemname=$('#itemname').val();
            var quantity=$('#quantity').val();
            var unit=$('#unit').val();
            var rack=$('#rack').val();
            var condition=$('#condition').val();
            var source=$('#source').val();
            var destination=$('#destination').val();
            var remarks=$('#remarks').val();
            $.ajax({
              url     : "kattegat/ragnar_purchase.php",
              method  : "POST",
              data    : {
                stc_po_adhoc_save:1,
                itemname:itemname,
                quantity:quantity,
                unit:unit,
                rack:rack,
                condition:condition,
                source:source,
                destination:destination,
                remarks:remarks
              },
              dataType : "JSON",
              success : function(response_items){
                var response=response_items;
                if(response=="success"){
                  alert("Purchase Order Adhoc saved successfully.");
                  $(".stc-add-poadhoc-product-form")[0].reset();
                  stc_call_poadhoc();
                }else{
                  alert("Something went wrong please check and try again.");
                }
              }
            });
          });
          
          // add recieving modal
          $('body').delegate('.add-receiving', 'click', function(e){
            var adhoc_id=$(this).attr("id");
            $('.stc-poadhoc-id').val(adhoc_id);
            $('#stcpoadhocreceivedby').val("");
          });
          
          $('body').delegate('.stc-poadhoc-received-hit', 'click', function(e){
            var adhoc_id=$('.stc-poadhoc-id').val();
            var receiving=$('#stcpoadhocreceivedby').val();
            $.ajax({
              url     : "kattegat/ragnar_purchase.php",
              method  : "POST",
              data    : {
                stc_po_adhocrec_save:1,
                adhoc_id:adhoc_id,
                receiving:receiving
              },
              success : function(response_items){
                var response=response_items.trim();
                if(response=="success"){
                  alert("Purchase Order Adhoc receiving saved successfully.");
                  $('#stcpoadhocreceivedby').val("");
                  stc_call_poadhoc();
                }else{
                  alert("Something went wrong please check and try again.");
                }
              }
            });
          });   
          
          $('body').delegate('.remove-products', 'click', function(e){
            var adhoc_id=$(this).attr("id");
            if(confirm("Are you sure want to delete this product?")){
              $.ajax({
                url     : "kattegat/ragnar_purchase.php",
                method  : "POST",
                data    : {
                  stc_po_adhoc_delete:1,
                  adhoc_id:adhoc_id
                },
                success : function(response_items){
                  var response=response_items.trim();
                  if(response=="success"){
                    alert("Purchase Order Adhoc deleted successfully.");
                    $('#stcpoadhocreceivedby').val("");
                    stc_call_poadhoc();
                  }else if(response=="invalid"){
                    alert("Item cannot delete, Either its already sold or some of quantity sold.");
                  }else{
                    alert("Something went wrong please check and try again.");
                  }
                }
              });
            }          
          });   
        });
    </script>
</body>
</html>
<div class="modal fade receiving-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-s ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Receiving</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12">
                  <div class="card-border mb-3 card card-body border-success">
                    <h5
                      for=""
                      >Received By
                    </h5>
                    <input type="hidden" class="stc-poadhoc-id">
                    <input
                      id="stcpoadhocreceivedby"
                      type="text"
                      placeholder="Received By"
                      class="form-control validate"
                    />
                  </div>
                </div>
                <div class="col-xl-12 col-md-12 col-sm-12">
                  <div class="card-border mb-3 card card-body border-success">
                    <button type="button"  data-dismiss="modal" class="btn btn-success stc-poadhoc-received-hit">Save</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <div class="row">
                <div class="col-xl-6 col-md-6 col-sm-6">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>