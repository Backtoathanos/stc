<?php
ini_set("session.gc_maxlifetime", 21600);
session_set_cookie_params(21600);
session_start();
if(empty(@$_SESSION['stc_electro_user_id'])){
    header('location:../index.html');
}


if($_SESSION['stc_electro_user_for']>=3 || $_SESSION['stc_electro_user_for']==9){}else{
    header('location:forbidden.html');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="icon" type="image/png" href="../assets/img/stc_logo_title.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    STC Electronics || Return Order
  </title>
  <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
  <!-- CSS Files -->
  <link href="../assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="../assets/demo/demo.css" rel="stylesheet" />
  <style>
      .fade:not(.show) {
        opacity: 10;
      }
  </style>
</head>

<body class="">
  <div class="wrapper ">
    <div class="sidebar" data-color="purple" data-background-color="white" data-image="../assets/img/sidebar-1.jpg">
      <div class="logo"><a href="#" class="simple-text logo-normal">
          STC Electronics
        </a></div>
      <div class="sidebar-wrapper">
        <ul class="nav">
          <li class="nav-item ">
            <a class="nav-link" href="./dashboard.php">
              <i class="material-icons">dashboard</i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="./inventory.php">
              <i class="material-icons">content_paste</i>
              <p>Inventory</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="./order-request.php">
              <i class="fa fa-sticky-note-o" aria-hidden="true"></i>
              <p>Request By</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="./order.php">
              <i class="material-icons">store</i>
              <p>Create/ Show Order</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="./product-links.php">
              <i class="material-icons">link</i>
              <p>Product Links</p>
            </a>
          </li>
          <li class="nav-item ">
            <a class="nav-link" href="./purchase-sale.php">
              <i class="material-icons">shop</i>
              <p>Daily Purchase/Sale</p>
            </a>
          </li>
          <li class="nav-item active ">
            <a class="nav-link" href="./return-order.php">
              <i class="fa fa-undo" aria-hidden="true"></i>
              <p>Return Invoice/Challan</p>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./daily-expenses.php">
              <i class="fa fa-money" aria-hidden="true"></i>
              <p>Daily Expenses</p>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="main-panel">
      <!-- Navbar -->
      <?php include_once("bar/navbar.php");?>
      <!-- End Navbar -->
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-12 col-md-12">
              <div class="card">
                <div class="card-header card-header-tabs card-header-primary">
                  <div class="nav-tabs-navigation">
                    <div class="nav-tabs-wrapper">
                      <span class="nav-tabs-title">Tasks:</span>
                      <ul class="nav nav-tabs" data-tabs="tabs">
                        <li class="nav-item">
                          <a class="nav-link active" href="#stc-create-Return" data-toggle="tab">
                            <i class="material-icons">add_circle</i> Create Return
                            <div class="ripple-container"></div>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" href="#stc-show-Return" data-toggle="tab">
                            <i class="material-icons">visibility</i> Show Return
                            <div class="ripple-container"></div>
                          </a>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="tab-content">
                    <div class="tab-pane active" id="stc-create-Return">
                      <div class="row">
                        <div class="col-12">
                          <h2 class="tm-block-title d-inline-block">Create Return</h2>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
                          <div class="tm-bg-primary-dark tm-block tm-block-h-auto" >
                            <div class="row">
                              <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="mb-3">
                                  <h5
                                    for="name"
                                    >Invoice Number
                                  </h5>
                                  <input
                                    name="silentReturninvonumber"
                                    type="number"
                                    class="form-control validate silentReturninvonumber"
                                    placeholder="Invoice Number. Ex:- 3 Or 18 Or 198"
                                    required
                                  />
                                </div>
                              </div>
                              <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="mb-3">
                                  <h5
                                    for="name"
                                    >Invoice Date
                                  </h5>
                                  <input
                                    name="silentReturninvodate"
                                    type="date"
                                    class="form-control validate silentReturninvodate"
                                    placeholder="Date"
                                    required
                                    disabled
                                  />
                                </div>
                              </div>
                              <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="mb-3">
                                  <button
                                    type="submit"
                                    class="form-control validate btn btn-success silentReturninvofind"
                                    required
                                  />Find</button>
                                </div>
                              </div>
                              <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="mb-3">
                                  <h5
                                    for="name"
                                    >Challan Number
                                  </h5>
                                  <input
                                    name="silentReturnchallnumber"
                                    type="number"
                                    class="form-control validate silentReturnchallnumber"
                                    placeholder="Challan Number"
                                    disabled
                                  />
                                </div>
                              </div>
                              <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="mb-3">
                                  <h5
                                    for="name"
                                    >Challan Date
                                  </h5>
                                  <input
                                    name="silentReturnchalldate"
                                    type="date"
                                    class="form-control validate silentReturnchalldate"
                                    placeholder="Challan Date"
                                    disabled
                                  />
                                </div>
                              </div>
                              <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="mb-3">
                                  <h5
                                    for="name"
                                    >Customer Name
                                  </h5>
                                  <input
                                    name="silentReturnpartyname"
                                    type="text"
                                    class="form-control validate silentReturnpartyname"
                                    placeholder="Customer/Party Name"
                                    disabled
                                  />
                                </div>
                              </div>
                              <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="mb-3">
                                  <h5
                                    for="name"
                                    >Contact Number
                                  </h5>
                                  <input
                                    name="silentReturncontnumber"
                                    type="text"
                                    class="form-control validate silentReturncontnumber"
                                    placeholder="10 Digit Contact Number"
                                    maxlength="10"
                                    disabled
                                  />
                                </div>
                              </div>                              
                              <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="row">
                                  <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="mb-3">
                                      <table class="table table-hover table-responsive table-bordered mb-3">
                                        <thead>
                                          <th class="text-center">Item Id</th>
                                          <th class="text-center" width="60%">Item Description</th>
                                          <th class="text-center">Item Invoice Quantity</th>
                                          <th class="text-center">Item Return Quantity</th>
                                          <th class="text-center">Action</th>
                                        </thead>
                                        <tbody>
                                          <tr>
                                            <td>
                                              <input type="number" class="form-control stc-or-invoice-item-id">
                                            </td>
                                            <td>
                                              <select class="form-control stc-or-invoice-item-desc">
                                                <option>Please Add Invoice Number to see the Items</option>
                                              </select>
                                            </td>
                                            <td>
                                              <input type="number" class="form-control stc-or-invoice-item-qty">
                                            </td>
                                            <td>
                                              <input type="number" min="1" class="form-control stc-or-invoice-item-return-qty">
                                            </td>
                                            <td>
                                              <button type="submit" class="form-control btn btn-info stc-or-invoice-item-hit">Add
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                </div>                                    
                              </div>                             
                              <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="row">
                                  <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="mb-3">
                                      <table class="table table-hover table-responsive table-bordered mb-3">
                                        <thead>
                                          <th class="text-center">Challan No</th>
                                          <th class="text-center">Item Id</th>
                                          <th class="text-center" width="60%">Item Description</th>
                                          <th class="text-center">Item Invoice Quantity</th>
                                          <th class="text-center">Item Return Quantity</th>
                                          <th class="text-center">Action</th>
                                        </thead>
                                        <tbody class="stc-response-items">
                                          <tr>
                                            <td colspan="6" class="text-center">
                                              Cart Empty
                                            </td>
                                          </tr>
                                        </tbody>
                                      </table>
                                    </div>
                                  </div>
                                </div>                                    
                              </div>
                              <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="mb-3">
                                  <h5
                                    for="name"
                                    >Remarks
                                  </h5>
                                  <textarea
                                    name="silentReturnremarks"
                                    type="text"
                                    class="form-control validate silentReturnremarks"
                                    placeholder="Enter Remarks"
                                  /></textarea>
                                </div>
                              </div>
                              <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="mb-3">
                                  <button type="button" name="search" class="form-control btn btn-success" id="stcReturnsavehit">Save</button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane" id="stc-show-Return">
                      <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
                          <div class="tm-bg-primary-dark tm-block tm-block-h-auto" >
                            <div class="row">
                              <div class="col-12">
                                <h2 class="tm-block-title d-inline-block">Show Purchase</h2>
                              </div>
                            </div>
                            <div class="row stc-view-product-row">
                              <div class="col-xl-12 col-lg-12 col-md-12">
                                <form action="" class="stc-silent-challan-form">
                                    <table class="table table-hover table-responsive">
                                      <thead>
                                        <tr>
                                          <th scope="col">From/<br>To</th>
                                          <th scope="col" width="30%">By Purchase Number, Party Name, Invoice No, Item Name</th>
                                          <th scope="col">Search</th>
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
                                            <p><input type="date" value="<?php echo $effectiveDate;?>" class="form-control begdate"></p>
                                            <p><input type="date" value="<?php echo $newDate;?>" class="form-control enddate"></p>
                                            <p><a href="#" id="returndatefilt">
                                                <i style="font-size: 20px;background: #0c0c1b;border-radius: 50%;padding: 5px;margin: 0;color: #77ffd3;float: right;" class="fa fa-arrow-down"></i>
                                              </a>
                                            </p>
                                          </td>
                                          <td>
                                            <input
                                              id="tags"
                                              data-role="tagsinput"
                                              type="text"
                                              placeholder="Challan Number/ Customer/Status"
                                              class="form-control validate stcfilterbyponumber"
                                              required
                                            />
                                          </td>
                                          <td>
                                            <button type="button" name="challansearch" class="btn btn-primary" id="returnsearch">Search</button>
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                </form>
                              </div>
                            </div>
                            <div class="row stc-view-sale-row" style="width: auto;overflow-x: auto; white-space: nowrap;">
                              <div class="col-xl-12 col-lg-12 col-md-12">
                                <form action="">
                                    <table class="table table-hover table-responsive table-bordered">
                                      <thead>
                                        <tr>
                                          <th>Return Id/<br>Return Date</th>
                                          <th>Invoice Id/<br>Invoice Date</th>
                                          <th>Challan Id/<br>Challan Date</th>
                                          <th>Customer Name</th>
                                          <th>Contact Number</th>
                                          <th>Customer Address</th>
                                          <th>Remarks</th>
                                          <th>Created By</th>
                                          <th>Action</th>
                                        </tr>
                                      </thead>
                                      <tbody class="stc-view-return-row-fetch">
                                        <tr><td>Loading....</td></tr>
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
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!--   Core JS Files   -->
  <script src="../assets/js/core/jquery.min.js"></script>
  <script src="../assets/js/core/popper.min.js"></script>
  <script src="../assets/js/core/bootstrap-material-design.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <script src="../assets/demo/demo.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script>
    $(document).ready(function(e){
      $('body').delegate('.stc-elec-pd-search-hit', 'click', function(e){
        e.preventDefault();
        var text_value=$('.stc-elec-pd-search-val').val();
        window.location.href="inventory.php?inv_pd_search="+text_value;
      });
    });
  </script>
  <script>
    $(document).ready(function(){
      $('body').delegate('.silentReturninvofind', 'click', function(e){
        e.preventDefault();
        var invoice_no=$('.silentReturninvonumber').val();
        $.ajax({
          url       : "../vanaheim/order_perform.php",
          method    : "POST",
          data      : {
            stc_get_rec_from_invo:1,
            invoice_no:invoice_no
          },
          dataType  : "JSON",
          success   : function(response_invoice){
            // console.log(response_invoice);
            $('.silentReturninvodate').val(response_invoice['invo-date']);
            $('.silentReturnchallnumber').val(response_invoice['challan-no']);
            $('.silentReturnchalldate').val(response_invoice['challan-date']);
            $('.silentReturnpartyname').val(response_invoice['cust-name']);
            $('.silentReturncontnumber').val(response_invoice['cont-number']);
            $('.stc-or-invoice-item-desc').html(response_invoice['item-name']);
          }
        });
      });

      // change item call qnty
      $('body').delegate('.stc-or-invoice-item-desc', 'change', function(e){
        e.preventDefault();
        var item_id=$(this).val();
        var challan_id=$('.silentReturnchallnumber').val();
        $.ajax({
          url       : "../vanaheim/order_perform.php",
          method    : "POST",
          data      : {
            stc_get_rec_from_pd:1,
            item_id:item_id,
            challan_id:challan_id
          },
          dataType  : "JSON",
          success   : function(response_product){
            // console.log(response_product);
            $('.stc-or-invoice-item-id').val(item_id);
            $('.stc-or-invoice-item-qty').val(response_product);
          }
        });
      });

      // call items
      stc_call_items();
      function stc_call_items(){
        $.ajax({
          url       : "../vanaheim/order_perform.php",
          method    : "POST",
          data      : {
            stc_call_items:1
          },
          // dataType  : "JSON",
          success   : function(response_items){
            // console.log(response_items);
            $('.stc-response-items').html(response_items);
          }
        });
      }

      // add item to cart
      $('body').delegate('.stc-or-invoice-item-hit', 'click', function(e){
        e.preventDefault();
        var challan_id=$('.silentReturnchallnumber').val();
        var item_id=$('.stc-or-invoice-item-id').val();
        var pd_qty=$('.stc-or-invoice-item-qty').val();
        var pd_ret_qty=$('.stc-or-invoice-item-return-qty').val();
        if(pd_ret_qty>pd_qty){
          alert("Incorrect Quantity!!!");
        }else{
          $.ajax({
            url       : "../vanaheim/order_perform.php",
            method    : "POST",
            data      : {
              stc_set_return_items_cart:1,
              item_id:item_id,
              challan_id:challan_id,
              pd_qty:pd_qty,
              pd_ret_qty:pd_ret_qty
            },
            // dataType  : "JSON",
            success   : function(response_product){
              // console.log(response_product);
              alert(response_product);
              stc_call_items();            
            }
          });
        }
      });

      // remove items
      $('body').delegate('.stc-remove-items', 'click', function(e){
        e.preventDefault();
        var item_id=$(this).attr("id");
        $.ajax({
          url     : "../vanaheim/order_perform.php",
          method  : "POST",
          data    : {
            stc_remove_item_hit:1,
            item_id:item_id
          },
          success : function(response_remove){
            alert(response_remove);
            stc_call_items();
          }
        });
      });

      // save return
      $('body').delegate('#stcReturnsavehit', 'click', function(e){
        e.preventDefault();
        var challan_id=$('.silentReturnchallnumber').val();
        var remarks=$('.silentReturnremarks').val();
        $.ajax({
          url     : "../vanaheim/order_perform.php",
          method  : "POST",
          data    : {
            stc_save_return:1,
            challan_id:challan_id,
            remarks:remarks
          },
          success : function(response_return){
            var response=response_return.trim();
            if(response=="Booyeah!!! Return Done Successfully."){
              alert(response_return);
              window.location.reload();
            }else{
              alert(response_return);
            }
          }
        });
      });
    });
  </script>
  <script>
    $(document).ready(function(){
      var todaydate = new Date();
      var day = todaydate.getDate();
      var month = todaydate.getMonth();
      var nmonth = todaydate.getMonth() + 1;
      var year = todaydate.getFullYear();
      var begdateon = year + "/" + month + "/" + day;
      var enddateon = year + "/" + nmonth + "/" + day;
      // purchase
      stc_call_return(begdateon, enddateon);
      function stc_call_return(begdateon, enddateon){
        var begdate=begdateon;
        var enddate=enddateon; 
        $.ajax({
          url       : "../vanaheim/order_perform.php",
          method    : 'POST',
          data      : {
            stccallreturn:1,
            begdate:begdate,
            enddate:enddate
          },
          dataType: 'JSON',
          success   : function(data){
            // console.log(data);
              $('.stc-view-return-row-fetch').html(data);
          }
        });     
      }

      // using date filter
      $('body').delegate('#returndatefilt', 'click', function(e){
        e.preventDefault();
        stc_call_return(begdateon, enddateon);
      });

      function load_return(query, returnbegdate, returnenddate) {
          $.ajax({
            url       : "../vanaheim/order_perform.php",
            method    : "POST",
            data      : {
              stcfilterreturn:1,
              query:query,
              returnbegdate:returnbegdate,
              returnenddate:returnenddate
            },
            dataType:"json",
            success   : function(data){
              // console.log(data);
             $('.stc-view-return-row-fetch').html(data);
            }
          });
      }

      // using search filter
      $('#returnsearch').click(function(){
          var query = $('#tags').val();
          var returnbegdate=$('.begdate').val();
          var returnenddate=$('.enddate').val();
          load_return(query, returnbegdate, returnenddate);
      });

      // show return model & call items
      $('body').delegate('.stc-show-return-items', 'click', function(e){
        e.preventDefault();
        var return_id=$(this).attr("id");
        $.ajax({
          url       : "../vanaheim/order_perform.php",
          method    : "POST",
          data      : {
            stcreturnitemscall:1,
            return_id:return_id
          },
          dataType:"json",
          success   : function(data){
            // console.log(data);
           $('.stc-return-show-table').html(data);
           $('.stc-return-invoice-item-model').modal('show');
          }
        });
      });
    });
  </script>
</body>
</html>
<div class="modal fade bd-example-modal-xl stc-return-invoice-item-model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Show Return Items</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
            <table class="table table-hover table-responsive table-bordered">
              <thead>
                <tr>
                  <td>Item Id</td>
                  <td>Item Description</td>
                  <td>Item Category</td>
                  <td>Item Quantity</td>
                </tr>
              </thead>
              <tbody class="stc-return-show-table">                
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" class="stc-hidden-invoice-id">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>