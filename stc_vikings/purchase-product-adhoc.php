<?php  
session_start(); 
if(isset($_SESSION["stc_empl_id"]) && ($_SESSION["stc_empl_role"]>0)){ 
}else{ 
    header("Location:index.html"); 
} 
$page_code=407;
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-beta.1/css/select2.min.css" rel="stylesheet" />
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
      .searchable-dropdown {
        position: relative;
        max-width: 300px;
      }

      #dropdown-search {
        margin-bottom: 10px;
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
                                <a role="tab" class="nav-link active" id="tab-2" data-toggle="tab" href="#tab-content-2">
                                    <span>View All Purchase Order Adhoc</span>
                                </a>
                            </li>
                            <?php
                              if(array_search(408, $checkrole_array)>0){
                            ?>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#tab-content-1">
                                    <span>Create New Purchase Order Adhoc</span>
                                </a>
                            </li>
                            <?php
                              }
                            ?>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane tabs-animation fade" id="tab-content-1" role="tabpanel">
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
                                    <div class="col-xl-3 col-md-3 col-sm-12">
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
                                    <div class="col-xl-3 col-md-3 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="quantity"
                                          >Quantity
                                        </h5>
                                        <input
                                          id="quantity"
                                          name="quantity"
                                          type="number"
                                          placeholder="Quantity"
                                          class="form-control validate"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-3 col-md-3 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="rate"
                                          >Rate
                                        </h5>
                                        <input
                                          id="rate"
                                          name="rate"
                                          type="number"
                                          placeholder="Rate"
                                          class="form-control validate"
                                        />
                                      </div>
                                    </div>
                                    <div class="col-xl-3 col-md-3 col-sm-12">
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
                                    <div class="col-xl-2 col-md-2 col-sm-12">
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
                                    <div class="col-xl-5 col-md-5 col-sm-12">
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
                                    <div class="col-xl-5 col-md-5 col-sm-12">
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
                            <div class="tab-pane tabs-animation fade show active" id="tab-content-2" role="tabpanel">
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
                                                    <option value="1">Stock</option>
                                                    <option value="2">Dispatched</option>
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
                                              <th>Product Name</th>
                                              <th>Item Name</th>
                                              <th>Rack</th>
                                              <th>Unit</th>
                                              <th>Quantity</th>
                                              <th>Rate</th>
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
                                              <tr><td colspan="8">Search</td></tr>
                                            </tbody>
                                          </table>
                                          <div id="pagination"></div>
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

          $('#dropdown-search').on('input', function() {
            var searchTerm = $(this).val().toLowerCase();

            // Loop through options and hide those that don't match the search term
            $('#stcpoadhoceitemrack option').each(function() {
              var text = $(this).text().toLowerCase();
              if (text.includes(searchTerm)) {
                $(this).show();
              } else {
                $(this).hide();
              }
            });
          });

          
          var currentPage = 1;
          var pageSize = 10;

          // call merchant for purchase
          function loadTableData(page) {
            $.ajax({
              url: "kattegat/ragnar_purchase.php",
              method: "POST",
              data: {
                stc_call_poadhoc: 1,
                itemname: $('#stc-poa-searchbyitem').val(),
                sourcedestination: $('#tc-poa-searchbydourcedestination').val(),
                byrack: $('.tc-poa-searchbyrack').val(),
                status: $('.stc-po-status-in').val(),
                page: page,
                pageSize: pageSize
              },
              dataType : "JSON",
              success: function(data) {
                $('.stc-call-view-poadhoc-row').html(data.odin);
                // Update pagination controls
                updatePagination(data.count_num);
              }
            });
          }

          function updatePagination(data) {
            var totalPages = Math.ceil(parseInt(data) / pageSize);
            $('#pagination').empty();

            for (var i = 1; i <= totalPages; i++) {
              var pageLink = $(
                '<a href="javascript:void(0)" class="paginationbtn" style="padding: 5px; margin: 0px; background: #c3ffe3; border: 1px solid grey;">' + 
                i + 
                '</a>'
              );

              // Highlight the current page
              if (i === currentPage) {
                pageLink.css({
                  background: "#4caf50", // Highlight background color (green in this case)
                  color: "white"        // Font color
                });
                pageLink.addClass('active');
              }

              pageLink.click(function(e) {
                e.preventDefault();
                // Update currentPage
                currentPage = parseInt($(this).text());

                // Load table data for the selected page
                loadTableData(currentPage);
              });

              $('#pagination').append(pageLink);
            }
          }

          // Initial data load
          // loadTableData(currentPage);
          var browserWidth = $(window).width();

          // Define the threshold for tablet and mobile (e.g., 992px as a common breakpoint)
          var tabletMobileBreakpoint = 992;
          var leftPanelWidth = 400;

          // Calculate final width based on conditions
          var formWidth = browserWidth > tabletMobileBreakpoint ? browserWidth - leftPanelWidth : browserWidth;

          // Set the width of the form
          $('.stc-view-purchase-order-form').css('width', formWidth + 'px');

          // Optional: Update the form width on window resize
          $(window).resize(function () {
              browserWidth = $(window).width();
              formWidth = browserWidth > tabletMobileBreakpoint ? browserWidth - leftPanelWidth : browserWidth;
              $('.stc-view-purchase-order-form').css('width', formWidth + 'px');
          });

          // Event listener for pagination links
          $('body').delegate('#pagination a', 'click', function(e) {
            e.preventDefault();
            currentPage = parseInt($(this).text());
            loadTableData(currentPage);
          });

          // Event listener for search button
          $('body').delegate('.stc-adhocpo-find', 'click', function(e) {
            e.preventDefault();
            currentPage = 1; // Reset page to 1 on search
            loadTableData(currentPage);
          });

          
          $('body').delegate('.get-dispatch-details', 'click', function(e){
            var poaid=$(this).attr('id');
            $.ajax({
              url       : "kattegat/ragnar_purchase.php",
              method    : "post",
              data      : {
                stc_call_poadhoc_details:1,
                poaid: poaid
              },
              success   : function(data){
                // console.log(data);
                $('.show-dispatchdetails-table').html(data);
              }
            });
          });

          // save po adhoc
          $('body').delegate('.stc-poadhoc-save', 'click', function(e){
            e.preventDefault();
            var itemname=$('#itemname').val();
            var quantity=$('#quantity').val();
            var rate=$('#rate').val();
            var unit=$('#unit').val();
            var rack=$('#rack').val();
            var condition=$('#condition').val();
            var source=$('#source').val();
            var destination=$('#destination').val();
            var remarks=$('#remarks').val();
            if(itemname!='' && quantity!=0 && rate!=0){
              $.ajax({
                url     : "kattegat/ragnar_purchase.php",
                method  : "POST",
                data    : {
                  stc_po_adhoc_save:1,
                  itemname:itemname,
                  quantity:quantity,
                  rate:rate,
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
                    $('.paginationbtn.active').click();
                  }else{
                    alert("Something went wrong please check and try again.");
                  }
                }
              });
            }else{
              alert('Please update required fields.');
            }
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
                  $('.paginationbtn.active').click();
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
                    $('.paginationbtn.active').click();
                  }else if(response=="invalid"){
                    alert("Item cannot delete, Either its already sold or some of quantity sold.");
                  }else{
                    alert("Something went wrong please check and try again.");
                  }
                }
              });
            }          
          });  
          
          $('body').delegate('.edit-itemname', 'click', function(e){
            var item_name=$(this).html();
            var item_rack = $(this).closest('tr').find('td:eq(5)').html().trim();
            var item_unit=$(this).closest('tr').find('td:eq(6)').html();
            var item_id=$(this).attr("id");
            $('#edit-pro-id').remove();
            $('#stcpoadhoceitemname').val(item_name);
            $('#stcpoadhoceitemrack option').filter(function() {
              return $(this).text().trim() === item_rack;
            }).prop('selected', true);
            $('#stcpoadhoceitemunit').val(item_unit);
            $('#stc-poadhocedit-id').val(item_id);
          });
          
          $('body').delegate('.stc-poadhoc-edititemname', 'click', function(e){
            var adhoc_id=$('#stc-poadhocedit-id').val();
            var adhoc_name=$('#stcpoadhoceitemname').val();
            var adhoc_rack=$('#stcpoadhoceitemrack').val();
            var adhoc_unit=$('#stcpoadhoceitemunit').val();
            $.ajax({
              url     : "kattegat/ragnar_purchase.php",
              method  : "POST",
              data    : {
                stc_po_adhoc_update:1,
                adhoc_id:adhoc_id,
                adhoc_name:adhoc_name,
                adhoc_rack:adhoc_rack,
                adhoc_unit:adhoc_unit
              },
              success : function(response_items){
                var response=response_items.trim();
                if(response=="success"){
                  alert("Item Name Updated Successfully.");
                  $('#stcpoadhoceitemname').val("");
                  $('.paginationbtn.active').click();
                }else{
                  alert("Something went wrong please check and try again.");
                }
              }
            });  
          });  
          
          $('body').delegate('.img-inputbtn', 'click', function(e){
            var adhoc_id=$(this).attr('id');
            var img_id=$(this).parent().find('.img-idinput').val();
            $.ajax({
              url     : "kattegat/ragnar_purchase.php",
              method  : "POST",
              data    : {
                stc_po_adhoc_imgupdate:1,
                adhoc_id:adhoc_id,
                img_id:img_id
              },
              success : function(response_items){
                var response=response_items.trim();
                if(response=="success"){
                  alert("Item Name Updated Successfully.");
                  $('.paginationbtn.active').click();
                }else{
                  alert("Something went wrong please check and try again.");
                }
              }
            });  
          }); 
          $('body').delegate('.img-inputratebtn', 'click', function(e){
            var adhoc_id=$(this).attr('id');
            var rate=$(this).parent().find('.img-idrateinput').val();
            $.ajax({
              url     : "kattegat/ragnar_purchase.php",
              method  : "POST",
              data    : {
                stc_po_adhoc_rateupdate:1,
                adhoc_id:adhoc_id,
                rate:rate
              },
              success : function(response_items){
                var response=response_items.trim();
                if(response=="success"){
                  alert("Rate Updated Successfully.");
                  $('.paginationbtn.active').click();
                }else{
                  alert("Something went wrong please check and try again.");
                }
              }
            });  
          });   

          $('body').delegate('.show-product-history', 'click', function(e){
            var adhoc_id=$(this).attr('id');
            $.ajax({
              url     : "kattegat/ragnar_purchase.php",
              method  : "POST",
              data    : {
                stc_po_adhoc_getprohistory:1,
                adhoc_id:adhoc_id
              },
              dataType : "JSON",
              success : function(response_items){
                $('.producthistproname').html(response_items['Product_name']);
                $('.producthistprounit').html(response_items['product_unit']);
                var purchased_data = response_items['purchased'];
                if (Array.isArray(purchased_data) && purchased_data.length > 0) {
                    var data = '';
                    var slno=0;
                    var totalqnty=0;
                    var totalrate=0;
                    var total=0;
                    $.each(purchased_data, function(index, row) {
                        slno++;
                        var quantity=parseFloat(row.stc_product_grn_items_qty);
                        var rate=parseFloat(row.stc_product_grn_items_rate);
                        var totalamount=parseFloat(rate) * parseFloat(quantity);
                        totalqnty+=parseFloat(row.stc_product_grn_items_qty);
                        totalrate+=parseFloat(row.stc_product_grn_items_rate);
                        total+=parseFloat(row.stc_product_grn_items_qty) * parseFloat(row.stc_product_grn_items_rate);
                        data += `<tr>
                                    <td>${row.stc_merchant_name}</td>
                                    <td>${row.stc_purchase_product_id}</td>
                                    <td>${row.stc_purchase_product_order_date}</td>
                                    <td>${row.stc_product_grn_id}</td>
                                    <td>${row.stc_product_grn_date}</td>
                                    <td class="text-right">${quantity.toFixed(2) + ' ' + response_items['product_unit']}</td>
                                    <td class="text-right">${rate.toFixed(2)}</td>
                                    <td class="text-right">${totalamount.toFixed(2)}</td>
                                    <td class="text-center">${row.stc_user_name}</td>
                                </tr>`;
                    });
                    data += `<tr>
                                <td class="text-right" colspan="4"><b>Total</b></td>
                                <td class="text-center">${slno}</td>
                                <td class="text-right">${totalqnty.toFixed(2) + ' ' + response_items['product_unit']}</td>
                                <td class="text-center"></td>
                                <td class="text-right">${total.toFixed(2)}</td>
                                <td class="text-center"></td>
                            </tr>`;
                    // Append data to table body
                    $('.projecthistory-purchased').html(data);
                } else {
                    // Show a message or an empty row if no data is found
                    $('.projecthistory-purchased').html('<tr><td colspan="10">No GLD Challan data available</td></tr>');
                }

                var purchasedadhoc_data = response_items['purchasedadhoc'];
                if (Array.isArray(purchasedadhoc_data) && purchasedadhoc_data.length > 0) {
                    var data = '';
                    var slno=0;
                    var totalqnty=0;
                    var totalrate=0;
                    var total=0;
                    $.each(purchasedadhoc_data, function(index, row) {
                        slno++;
                        var quantity=parseFloat(row.stc_purchase_product_adhoc_qty);
                        var rate=parseFloat(row.stc_purchase_product_adhoc_rate);
                        var totalamount=parseFloat(rate) * parseFloat(quantity);
                        totalqnty+=parseFloat(row.stc_purchase_product_adhoc_qty);
                        totalrate+=parseFloat(row.stc_purchase_product_adhoc_rate);
                        total+=parseFloat(row.stc_purchase_product_adhoc_qty) * parseFloat(row.stc_purchase_product_adhoc_rate);
                        data += `<tr>
                                    <td>${row.stc_purchase_product_adhoc_source}</td>
                                    <td>${row.stc_purchase_product_adhoc_destination}</td>
                                    <td class="text-right">${quantity.toFixed(2) + ' ' + response_items['product_unit']}</td>
                                    <td class="text-right">${rate.toFixed(2)}</td>
                                    <td class="text-right">${totalamount.toFixed(2)}</td>
                                    <td>${row.stc_purchase_product_adhoc_recievedby}</td>
                                    <td>${row.stc_purchase_product_adhoc_created_date}</td>
                                    <td class="text-center">${row.stc_user_name}</td>
                                </tr>`;
                    });
                    data += `<tr>
                                <td class="text-right" colspan="1"><b>Total</b></td>
                                <td class="text-center">${slno}</td>
                                <td class="text-right">${totalqnty.toFixed(2) + ' ' + response_items['product_unit']}</td>
                                <td class="text-center"></td>
                                <td class="text-right">${total.toFixed(2)}</td>
                                <td class="text-center"></td>
                            </tr>`;
                    // Append data to table body
                    $('.projecthistory-purchasedadhoc').html(data);
                } else {
                    // Show a message or an empty row if no data is found
                    $('.projecthistory-purchasedadhoc').html('<tr><td colspan="10">No GLD Challan data available</td></tr>');
                }

                var directchallan_data = response_items['directchallan'];
                if (Array.isArray(directchallan_data) && directchallan_data.length > 0) {
                    var data = '';
                    var slno=0;
                    var totalqnty=0;
                    $.each(directchallan_data, function(index, row) {
                        slno++;
                        var quantity=parseFloat(row.stc_cust_super_requisition_list_items_rec_recqty);
                        totalqnty+=parseFloat(row.stc_cust_super_requisition_list_items_rec_recqty);
                        data += `<tr>
                                    <td>${row.stc_customer_name}</td>
                                    <td>${row.stc_cust_project_title}</td>
                                    <td>${row.stc_cust_super_requisition_list_items_rec_list_id}</td>
                                    <td class="text-right">${quantity.toFixed(2) + ' ' + response_items['product_unit']}</td>
                                    <td>${row.stc_cust_super_requisition_list_items_rec_date}</td>
                                </tr>`;
                    });
                    data += `<tr>
                                <td class="text-right" colspan="2"><b>Total</b></td>
                                <td class="text-center">${slno}</td>
                                <td class="text-right">${totalqnty.toFixed(2) + ' ' + response_items['product_unit']}</td>
                                <td class="text-center"></td>
                            </tr>`;
                    // Append data to table body
                    $('.projecthistory-directChallan').html(data);
                } else {
                    // Show a message or an empty row if no data is found
                    $('.projecthistory-directChallan').html('<tr><td colspan="10">No GLD Challan data available</td></tr>');
                }


                var challan_data = response_items['challan'];
                if (Array.isArray(challan_data) && challan_data.length > 0) {
                    var data = '';
                    var slno=0;
                    var totalqnty=0;
                    var totalrate=0;
                    var total=0;
                    $.each(challan_data, function(index, row) {
                        slno++;
                        var quantity=parseFloat(row.stc_sale_product_items_product_qty);
                        var rate=parseFloat(row.stc_sale_product_items_product_sale_rate);
                        totalqnty+=parseFloat(row.stc_sale_product_items_product_qty);
                        total+=quantity * rate;
                        data += `<tr>
                                    <td>${row.stc_customer_name}</td>
                                    <td>${row.stc_cust_project_title}</td>
                                    <td>${row.stc_sale_product_id}</td>
                                    <td class="text-right">${quantity.toFixed(2) + ' ' + response_items['product_unit']}</td>
                                    <td class="text-right">${rate.toFixed(2)}</td>
                                    <td>${total.toFixed(2)}</td>
                                    <td>${row.stc_sale_product_cust_order_date}</td>
                                </tr>`;
                    });
                    data += `<tr>
                                <td class="text-right" colspan="2"><b>Total</b></td>
                                <td class="text-center">${slno}</td>
                                <td class="text-right">${totalqnty.toFixed(2) + ' ' + response_items['product_unit']}</td>
                                <td class="text-center"></td>
                                <td class="text-center">${total}</td>
                            </tr>`;
                    // Append data to table body
                    $('.projecthistory-gstchallan').html(data);
                } else {
                    // Show a message or an empty row if no data is found
                    $('.projecthistory-gstchallan').html('<tr><td colspan="10">No GLD Challan data available</td></tr>');
                }

                // gld chllan data
                var gld_challan = response_items['gld_challan'];
                if (Array.isArray(gld_challan) && gld_challan.length > 0) {
                    var data = '';
                    var slno=0;
                    var totalqnty=0;
                    var total=0;
                    $.each(gld_challan, function(index, row) {
                        slno++;
                        totalqnty+=parseFloat(row.qty);
                        total+=parseFloat(row.qty) * parseFloat(row.rate);
                        data += `<tr>
                                    <td>${row.gld_customer_title}</td>
                                    <td>${row.bill_number}</td>
                                    <td>${row.challan_number}</td>
                                    <td class="text-right">${row.qty + ' ' + response_items['product_unit']}</td>
                                    <td class="text-right">${row.rate}</td>
                                    <td class="text-right">${row.qty * row.rate}</td>
                                    <td class="text-center">${row.created_date}</td>
                                    <td class="text-center">${row.stc_trading_user_name}</td>
                                </tr>`;
                    });
                    data += `<tr>
                                <td class="text-right" colspan="2"><b>Total</b></td>
                                <td class="text-center">${slno}</td>
                                <td class="text-right">${totalqnty.toFixed(2) + ' ' + response_items['product_unit']}</td>
                                <td class="text-right"></td>
                                <td class="text-right">${total.toFixed(2)}</td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                            </tr>`;
                    // Append data to table body
                    $('.projecthistory-gldchallan').html(data);
                } else {
                    // Show a message or an empty row if no data is found
                    $('.projecthistory-gldchallan').html('<tr><td colspan="10">No GLD Challan data available</td></tr>');
                }
              }
            });  
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
<div class="modal fade bd-modal-editproductname" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-s ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Item Name</h5>
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
                      >Item Name
                    </h5>
                    <input type="hidden" id="stc-poadhocedit-id">
                    <input
                      id="stcpoadhoceitemname"
                      type="text"
                      placeholder="Edit Item Name"
                      class="form-control validate"
                    />
                  </div>
                </div>
                <div class="col-xl-12 col-md-12 col-sm-12">
                  <div class="card-border mb-3 card card-body border-success">
                    <h5
                      for=""
                      >Item Rack
                    </h5>
                    <div class="searchable-dropdown">
                      <input type="text" id="dropdown-search" placeholder="Search..." class="form-control" />
                      <select id="stcpoadhoceitemrack" class="form-control validate">
                        <?php
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
                </div>
                <div class="col-xl-12 col-md-12 col-sm-12">
                  <div class="card-border mb-3 card card-body border-success">
                    <h5
                      for=""
                      >Item Unit
                    </h5>
                    <input
                      id="stcpoadhoceitemunit"
                      type="text"
                      placeholder="Edit Unit"
                      class="form-control validate"
                    />
                  </div>
                </div>                
                <div class="col-xl-12 col-md-12 col-sm-12">
                  <div class="card-border mb-3 card card-body border-success">
                    <button type="button"  data-dismiss="modal" class="btn btn-success stc-poadhoc-edititemname">Save</button>
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

<div class="modal fade bd-showadhocdetails-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Dispatch Details</h5>
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
                      >Dispatch Details
                    </h5>
                    <table class="table table-bordered table-reponsive">
                      <thead>
                        <tr>
                          <th>Sl No</th>
                          <th>Date</th>
                          <th>Requisition Number</th>
                          <th>Project Name</th>
                          <th>User Name</th>
                          <th>Dispatched Quantity</th>
                        </tr>
                      </thead>
                      <tbody class="show-dispatchdetails-table">
                      </tbody>
                    </table>
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

<div class="modal fade bd-modal-product-history" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Product History</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12">
                  <div class="card border-success mb-3 card-body">
                    <h2>Product Name - <span class="producthistproname"></span></h2>
                    <p>Unit - <span class="producthistprounit"></span></p>
                  </div>
                </div>
                <div class="col-xl-12 col-md-12 col-sm-12">
                  <div class="card border-success mb-3 card-body">                    
                    <!-- Nav tabs -->
                    <ul class="nav body-tabs body-tabs-layout tabs-animated body-tabs-animated" role="tablist">
                        <!-- Purchased Tabs (Green) -->
                        <li class="nav-item" style="background-color: #00dd00; color: #fff;">
                            <a class="nav-link active" id="purchased-tab" data-toggle="tab" href="#purchased" role="tab" aria-controls="purchased" aria-selected="true" style="color: #fff; display: block; padding: 10px 15px; text-align: center; font-weight: bold;">
                                <span>Purchased</span>
                            </a>
                        </li>
                        <li class="nav-item" style="background-color: #00dd00; color: #fff;">
                            <a class="nav-link" id="purchasedadhoc-tab" data-toggle="tab" href="#purchasedadhoc" role="tab" aria-controls="purchasedadhoc" aria-selected="true" style="color: #fff; display: block; padding: 10px 15px; text-align: center;">
                                <span>Purchased Adhoc</span>
                            </a>
                        </li>

                        <!-- Sold Tabs (Red) -->
                        <li class="nav-item" style="background-color: #ff6060; color: #fff;">
                            <a class="nav-link" id="gstChallan-tab" data-toggle="tab" href="#gstChallan" role="tab" aria-controls="gstChallan" aria-selected="false" style="color: #fff; display: block; padding: 10px 15px; text-align: center;">
                                <span>Invoice Challan</span>
                            </a>
                        </li>
                        <li class="nav-item" style="background-color: #ff6060; color: #fff;">
                            <a class="nav-link" id="directChallan-tab" data-toggle="tab" href="#directChallan" role="tab" aria-controls="directChallan" aria-selected="false" style="color: #fff; display: block; padding: 10px 15px; text-align: center;">
                                <span>Without Invoice Challan</span>
                            </a>
                        </li>
                        <li class="nav-item" style="background-color: #ff6060; color: #fff;">
                            <a class="nav-link" id="gldChallan-tab" data-toggle="tab" href="#gldChallan" role="tab" aria-controls="gldChallan" aria-selected="false" style="color: #fff; display: block; padding: 10px 15px; text-align: center;">
                                <span>GLD Challan</span>
                            </a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content mt-3">
                      <div class="tab-pane fade show active" id="purchased" role="tabpanel" aria-labelledby="purchased-tab">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th class="text-center">Merchant (Vendor)</th>
                              <th class="text-center">PO No</th>
                              <th class="text-center">PO Date</th>
                              <th class="text-center">GRN No</th>
                              <th class="text-center">GRN Date</th>
                              <th class="text-center">Quantity</th>
                              <th class="text-center">Rate</th>
                              <th class="text-center">GST</th>
                              <th class="text-center">Total</th>
                            </tr>
                          </thead>
                          <tbody class="projecthistory-purchased"></tbody>
                        </table>
                      </div>
                      <div class="tab-pane fade" id="purchasedadhoc" role="tabpanel" aria-labelledby="purchasedadhoc-tab">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th class="text-center">Merchant (Vendor)</th>
                              <th class="text-center">Godown</th>
                              <th class="text-center">Quantity</th>
                              <th class="text-center">Rate</th>
                              <th class="text-center">Total</th>
                              <th class="text-center">Recieved By</th>
                              <th class="text-center">Created Date</th>
                              <th class="text-center">Created By</th>
                            </tr>
                          </thead>
                          <tbody class="projecthistory-purchasedadhoc"></tbody>
                        </table>
                      </div>
                      <div class="tab-pane fade" id="gstChallan" role="tabpanel" aria-labelledby="gstChallan-tab">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th class="text-center">Customer</th>
                              <th class="text-center">Sitename</th>
                              <th class="text-center">Challan No</th>
                              <th class="text-center">Quantity</th>
                              <th class="text-center">Rate</th>
                              <th class="text-center">Total</th>
                              <th class="text-center">Created Date</th>
                            </tr>
                          </thead>
                          <tbody class="projecthistory-gstchallan"></tbody>
                        </table>
                      </div>
                      <div class="tab-pane fade" id="directChallan" role="tabpanel" aria-labelledby="directChallan-tab">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th class="text-center">Customer</th>
                              <th class="text-center">Sitename</th>
                              <th class="text-center">Requisition No</th>
                              <th class="text-center">Quantity</th>
                              <th class="text-center">Created Date</th>
                            </tr>
                          </thead>
                          <tbody class="projecthistory-directChallan"></tbody>
                        </table>
                      </div>
                      <div class="tab-pane fade" id="gldChallan" role="tabpanel" aria-labelledby="gldChallan-tab">
                        <table class="table table-bordered">
                          <thead>
                            <tr>
                              <th class="text-center">Customer</th>
                              <th class="text-center">Bill Number</th>
                              <th class="text-center">Challan Number</th>
                              <th class="text-center">Quantity</th>
                              <th class="text-center">Rate</th>
                              <th class="text-center">Total</th>
                              <th class="text-center">Created Date</th>
                              <th class="text-center">Created By</th>
                            </tr>
                          </thead>
                          <tbody class="projecthistory-gldchallan"></tbody>
                        </table>
                      </div>
                    </div>

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