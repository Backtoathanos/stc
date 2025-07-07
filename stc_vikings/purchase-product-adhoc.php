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
      .searchable-dropdown {
          position: relative;
          display: inline-block;
          width: 100%;
      }

      .dropdown-options {
          position: absolute;
          background-color: #fff;
          border: 1px solid #ddd;
          width: 100%;
          max-height: 200px;
          overflow-y: auto;
          z-index: 1000;
      }

      .dropdown-item {
          padding: 8px 12px;
          cursor: pointer;
      }

      .dropdown-item:hover {
          background-color: #f5f5f5;
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
                                                <table class="table table-bordered" id="itemsTable">
                                                    <thead>
                                                        <tr>
                                                            <th>Item Code</th>
                                                            <th>Item Name</th>
                                                            <th>Quantity</br>Unit</th>
                                                            <th>Rate</br>Rack</th>
                                                            <th>Condition</th>
                                                            <th>From (Source/Location)<br>To (Destination/Location)</th>
                                                            <th>Remarks</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="item-row">
                                                            <td style="width: 100px;">
                                                                <input
                                                                    name="itemcode[]"
                                                                    type="number"
                                                                    placeholder="Item Code"
                                                                    class="form-control validate"
                                                                    required
                                                                />
                                                            </td>
                                                            <td style="width: 330px;">
                                                                <textarea
                                                                    name="itemname[]"
                                                                    type="text"
                                                                    placeholder="Item Name"
                                                                    class="form-control validate"
                                                                    required
                                                                ></textarea>
                                                            </td>
                                                            <td>
                                                                <input
                                                                    name="quantity[]"
                                                                    type="number"
                                                                    placeholder="Quantity"
                                                                    class="form-control validate"
                                                                    required
                                                                />
                                                                <input
                                                                    name="unit[]"
                                                                    type="text"
                                                                    placeholder="Unit"
                                                                    class="form-control validate"
                                                                />
                                                            </td>
                                                            <td>
                                                                <input
                                                                    name="rate[]"
                                                                    type="number"
                                                                    placeholder="Rate"
                                                                    class="form-control validate"
                                                                    required
                                                                />
                                                                <select 
                                                                    name="rack[]"
                                                                    class="form-control validate"
                                                                >
                                                                    <option value="NA">Select</option>
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
                                                            </td>
                                                            <td>
                                                                <select 
                                                                    name="condition[]"
                                                                    class="form-control validate"
                                                                >
                                                                    <option value="NA">Select</option>
                                                                    <option value="Bad">Bad</option>
                                                                    <option value="Broken">Broken</option>
                                                                    <option value="Good">Good</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <div class="searchable-dropdown">
                                                                    <input 
                                                                        name="sourcerack[]" 
                                                                        type="text" 
                                                                        placeholder="From (Source/Location)" 
                                                                        class="form-control validate search-input"
                                                                        autocomplete="off"
                                                                    />
                                                                    <div class="dropdown-options" style="display: none;"></div>
                                                                    <!-- Hidden select to store the actual value -->
                                                                    <select name="sourcerack_value[]" style="display: none;">
                                                                        <option value="NA">Select</option>
                                                                        <?php
                                                                            $rackqry=mysqli_query($con, "
                                                                                SELECT DISTINCT `stc_purchase_product_adhoc_source` FROM `stc_purchase_product_adhoc` WHERE `stc_purchase_product_adhoc_source`<>''
                                                                            ");
                                                                            foreach($rackqry as $rackqrow){
                                                                                echo '<option value="'.$rackqrow['stc_purchase_product_adhoc_source'].'">'.$rackqrow['stc_purchase_product_adhoc_source'].'</option>';
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                </div>
                                                                          </br>
                                                                <select 
                                                                    class="custom-select form-control"
                                                                    name="destination[]"
                                                                >
                                                                    <option value="NA">Select Destination</option>
                                                                    <option>MANGO 17 NO GODOWN</option>
                                                                    <option>PARDIH GODOWN</option>
                                                                    <option>RAMGARH GODOWN</option>
                                                                    <option>DHATKIDIH GODOWN</option>
                                                                </select>
                                                            </td>
                                                            <td style="width: 330px;">
                                                                <textarea
                                                                    name="remarks[]"
                                                                    type="text"
                                                                    placeholder="Remarks"
                                                                    class="form-control validate"
                                                                ></textarea>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-danger remove-row">Remove</button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <div class="text-right mb-3">
                                                    <button type="button" class="btn btn-success" id="addRow">Add Another Row</button>
                                                </div>
                                                <div class="text-center">
                                                    <button type="submit" class="btn btn-primary stc-poadhoc-save">Save All Items</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
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
                                      <a class="btn btn-success" data-toggle="modal" data-target=".bd-modal-ledgershow" href="javascript:void(0)">View Ledger</a>
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
                                      <form action="" class="stc-view-purchase-order-form" style="overflow-x: auto; width: 100%;">
                                          <table class="table table-hover table-bordered stc-purchase-view-table">
                                            <thead>
                                              <th>Sl No.</th>
                                              <th>Adhoc_Id</th>
                                              <th>Linked Product</th>
                                              <th>Product Name</th>
                                              <th>Item Name</th>
                                              <th>Rack</th>
                                              <th>Unit</th>
                                              <th>Quantity</th>
                                              <th>Rate</th>
                                              <th>Stock</th>
                                              <th>Shop</th>
                                              <th>Dispatch Details</th>
                                              <th>From Source (Supplier/Location)<br>To Destination (Location)</th>
                                              <th>Condition</th>
                                              <th>Received_By</th>
                                              <th>Created_By<br>Created_Date</th>
                                              <th>Updated_By<br>Updated_Date</th>
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
          // Get all options from the hidden select when page loads
          var options = [];
          $('select[name="sourcerack_value[]"] option').each(function() {
              options.push({
                  value: $(this).val(),
                  text: $(this).text()
              });
          });

          // Handle input events
          $('.search-input').on('input focus', function() {
              var searchTerm = $(this).val().toUpperCase();
              var $dropdown = $(this).next('.dropdown-options');
              var $hiddenSelect = $(this).nextAll('select[name="sourcerack_value[]"]');
              
              // Filter options
              var filteredOptions = options.filter(function(option) {
                  return option.text.toUpperCase().includes(searchTerm) || 
                        option.value.toUpperCase().includes(searchTerm);
              });

              // Build dropdown HTML
              var dropdownHTML = '';
              filteredOptions.forEach(function(option) {
                  dropdownHTML += `<div class="dropdown-item" data-value="${option.value}">${option.text}</div>`;
              });

              // Update dropdown
              $dropdown.html(dropdownHTML).show();

              // Hide if empty input
              if (searchTerm === '') {
                  $dropdown.hide();
              }
          });

          // Handle click on dropdown items
          $(document).on('click', '.dropdown-item', function() {
              var value = $(this).data('value');
              var text = $(this).text();
              var $container = $(this).closest('.searchable-dropdown');
              
              // Update visible input field
              $container.find('.search-input').val(text);
              
              // Update hidden select value
              $container.find('select[name="sourcerack_value[]"]').val(value);
              
              // Hide dropdown
              $container.find('.dropdown-options').hide();
          });

          // Hide dropdown when clicking outside
          $(document).on('click', function(e) {
              if (!$(e.target).closest('.searchable-dropdown').length) {
                  $('.dropdown-options').hide();
              }
          });

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

          let pagenumber=0;
          // Pagination Module
          const Pagination = (function() {
              // Configuration
              const config = {
                  pageSize: 10,
                  visiblePages: 5, // Number of visible page buttons (odd number works best)
                  container: '#pagination',
                  tableRowContainer: '.stc-call-view-poadhoc-row',
                  apiEndpoint: 'kattegat/ragnar_purchase.php',
                  btnStyles: {
                      normal: {
                          padding: '5px 10px',
                          margin: '0 2px',
                          background: '#c3ffe3',
                          color: 'black',
                          border: '1px solid #ddd',
                          borderRadius: '3px',
                          cursor: 'pointer'
                      },
                      active: {
                          background: '#4caf50',
                          color: 'white',
                          fontWeight: 'bold'
                      }
                  }
              };

              // State
              let currentPage = 1;
              let totalRecords = 0;
              let isLoading = false;

              // DOM Elements
              const $pagination = $(config.container);
              const $tableRowContainer = $(config.tableRowContainer);

              // Private methods
              function createButton(text, clickHandler, isActive = false) {
                  const styles = isActive ? 
                      {...config.btnStyles.normal, ...config.btnStyles.active} : 
                      config.btnStyles.normal;
                  
                  return $('<a>', {
                      href: 'javascript:void(0)',
                      class: 'pagination-btn',
                      text: text,
                      click: clickHandler
                  }).css(styles);
              }

              function showLoading() {
                  $tableRowContainer.html('<div class="loading-indicator">Loading data...</div>');
              }

              function handleAjaxError() {
                  $tableRowContainer.html('<div class="error-message">Error loading data. Please try again.</div>');
              }

              // Public methods
              return {
                  init: function() {
                      this.loadData(currentPage);
                  },

                  loadData: function(page) {
                      if (isLoading) return;
                      
                      currentPage = page;
                      isLoading = true;
                      pagenumber = page;
                      showLoading();

                      $.ajax({
                          url: config.apiEndpoint,
                          method: "POST",
                          data: {
                              stc_call_poadhoc: 1,
                              itemname: $('#stc-poa-searchbyitem').val(),
                              sourcedestination: $('#tc-poa-searchbydourcedestination').val(),
                              byrack: $('.tc-poa-searchbyrack').val(),
                              status: $('.stc-po-status-in').val(),
                              page: page,
                              pageSize: config.pageSize
                          },
                          dataType: "JSON",
                          success: (data) => {
                              $tableRowContainer.html(data.odin);
                              totalRecords = data.count_num;
                              this.updatePagination();
                          },
                          error: handleAjaxError,
                          complete: () => {
                              isLoading = false;
                          }
                      });
                  },

                  updatePagination: function() {
                      const totalPages = Math.ceil(totalRecords / config.pageSize);
                      $pagination.empty();

                      if (totalPages <= 1) {
                          return; // Don't show pagination for single page
                      }

                      // Calculate page range to display
                      let startPage = Math.max(1, currentPage - Math.floor(config.visiblePages / 2));
                      let endPage = Math.min(totalPages, startPage + config.visiblePages - 1);

                      // Adjust if we're at the end
                      if (endPage - startPage + 1 < config.visiblePages) {
                          startPage = Math.max(1, endPage - config.visiblePages + 1);
                      }

                      // Previous button
                      if (currentPage > 1) {
                          $pagination.append(
                              createButton('« Prev', () => {
                                  this.loadData(currentPage - 1);
                              })
                          );
                      }

                      // First page and ellipsis
                      if (startPage > 1) {
                          $pagination.append(createButton('1', () => this.loadData(1)));
                          if (startPage > 2) {
                              $pagination.append('<span style="margin: 0 5px;">...</span>');
                          }
                      }

                      // Page numbers
                      for (let i = startPage; i <= endPage; i++) {
                          $pagination.append(
                              createButton(i, () => {
                                  this.loadData(i);
                              }, i === currentPage)
                          );
                      }

                      // Last page and ellipsis
                      if (endPage < totalPages) {
                          if (endPage < totalPages - 1) {
                              $pagination.append('<span style="margin: 0 5px;">...</span>');
                          }
                          $pagination.append(
                              createButton(totalPages, () => {
                                  this.loadData(totalPages);
                              })
                          );
                      }

                      // Next button
                      if (currentPage < totalPages) {
                          $pagination.append(
                              createButton('Next »', () => {
                                  this.loadData(currentPage + 1);
                              })
                          );
                      }
                  },

                  refresh: function() {
                      this.loadData(currentPage);
                  }
              };
          })();

          Pagination.init();

          // Example of how to refresh when search criteria changescurrentPage
          // $('#stc-poa-searchbyitem, #tc-poa-searchbydourcedestination, .tc-poa-searchbyrack, .stc-po-status-in').on('change', function() {
          //     Pagination.loadData(1); // Reset to first page when filters change
          // });

          // var browserWidth = $(window).width();

          // // Define the threshold for tablet and mobile (e.g., 992px as a common breakpoint)
          // var tabletMobileBreakpoint = 992;
          // var leftPanelWidth = 400;

          // // Calculate final width based on conditions
          // var formWidth = browserWidth > tabletMobileBreakpoint ? browserWidth - leftPanelWidth : browserWidth;

          // // Set the width of the form
          // $('.stc-view-purchase-order-form').css('width', formWidth + 'px');

          // // Optional: Update the form width on window resize
          // $(window).resize(function () {
          //     browserWidth = $(window).width();
          //     formWidth = browserWidth > tabletMobileBreakpoint ? browserWidth - leftPanelWidth : browserWidth;
          //     $('.stc-view-purchase-order-form').css('width', formWidth + 'px');
          // });

          // Event listener for pagination links
          $('body').delegate('#pagination a', 'click', function(e) {
            e.preventDefault();
            currentPage = parseInt($(this).text());
            Pagination.loadData(currentPage);
          });

          // Event listener for search button
          $('body').delegate('.stc-adhocpo-find', 'click', function(e) {
            e.preventDefault();
            currentPage = 1; // Reset page to 1 on search
            Pagination.loadData(currentPage);
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

          // Add new row
          $('#addRow').click(function() {
              var newRow = $('.item-row:first').clone();
              newRow.find('input, textarea').val('');
              newRow.find('select').prop('selectedIndex', 0);
              $('#itemsTable tbody').append(newRow);
          });
          
          // Remove row
          $('body').delegate('.remove-row', 'click', function() {
              if($('#itemsTable tbody tr').length > 1) {
                  $(this).closest('tr').remove();
              } else {
                  alert("You need to have at least one item row.");
              }
          });
          
          $('body').delegate('.stc-poadhoc-save', 'click', function(e){
              e.preventDefault();
              
              // Collect all form data
              var formData = [];
              var isValid = true;
              
              $('#itemsTable tbody tr').each(function(index) {
                  var row = $(this);
                  var itemData = {
                      itemcode: row.find('[name="itemcode[]"]').val(),
                      itemname: row.find('[name="itemname[]"]').val(),
                      quantity: row.find('[name="quantity[]"]').val(),
                      rate: row.find('[name="rate[]"]').val(),
                      unit: row.find('[name="unit[]"]').val(),
                      rack: row.find('[name="rack[]"]').val(),
                      condition: row.find('[name="condition[]"]').val(),
                      sourcerack: row.find('[name="sourcerack[]"]').val(),
                      destination: row.find('[name="destination[]"]').val(),
                      remarks: row.find('[name="remarks[]"]').val()
                  };
                  
                  // Validate required fields for this row
                  if(itemData.itemname && itemData.quantity && itemData.rate) {
                      formData.push(itemData);
                  } else if(index === 0) {
                      // Only show error for first row if empty
                      isValid = false;
                      alert('Please fill all required fields in at least the first row.');
                      return false; // Exit the each loop
                  }
              });
              
              if(!isValid || formData.length === 0) {
                  return;
              }
              
              $.ajax({
                  url: "kattegat/ragnar_purchase.php",
                  method: "POST",
                  data: {
                      stc_po_adhoc_save: 1,
                      items: formData
                  },
                  dataType: "JSON",
                  success: function(response){
                      if(response === "success") {
                          alert("Purchase Order Adhoc saved successfully.");
                          $(".stc-add-poadhoc-product-form")[0].reset();
                          Pagination.loadData(pagenumber);
                      } else {
                          alert(response || "Something went wrong please check and try again.");
                      }
                  },
                  error: function(xhr, status, error) {
                      alert("Error: " + error);
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
                  Pagination.loadData(pagenumber);
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
                    Pagination.loadData(pagenumber);
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
                  Pagination.loadData(pagenumber);
                }else{
                  alert("Something went wrong please check and try again.");
                }
              }
            });  
          });  
          
          $('body').delegate('.img-inputbtnshow', 'click', function(e){
            $(this).parent().find('.img-idrateinput').toggle();
            $(this).parent().find('.img-inputratebtn').toggle();
          });

          $('body').delegate('.img-inputbtn', 'click', function(e){
            var adhoc_id=$(this).attr('id');
            var img_id=$(this).parent().find('.img-idinput').val();
            $(this).parent().find('.img-idrateinput').toggle();
            $(this).parent().find('.img-inputratebtn').toggle();
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
                  Pagination.loadData(pagenumber);
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
                  Pagination.loadData(pagenumber);
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

          $('.stc-adhocpofilter-find').on('click', function () {
            // Get filter values
            var dateFrom = $('.stc-poa-filterdatefrom').val();
            var dateTo = $('.stc-poa-filterdateto').val();
            var siteName = $('.stc-poa-filtersitename').val();

            // Make AJAX request
            $.ajax({
              url: "kattegat/ragnar_purchase.php", // Replace with your API endpoint
              type: 'GET', // or 'POST' depending on your API
              data: {
                stc_get_ledger: 1,
                dateFrom: dateFrom,  // Ensure these are defined before use
                dateTo: dateTo,
                siteName: siteName
              },
              dataType: 'json',
              success: function (response) {
                // Clear existing table rows
                $('.stc-call-view-poadhocledger-row').empty();

                if (response && response.length > 0) {
                  var total=0;
                  $.each(response, function (index, item) {
                    // Format date for better readability
                    let formattedDate = new Date(item.stc_cust_super_requisition_list_date).toLocaleDateString('en-GB');

                    var row = `<tr>
                      <td>${index + 1}</td>
                      <td>${formattedDate}</td>
                      <td>${item.stc_cust_project_title}</td>
                      <td>${item.stc_cust_pro_supervisor_fullname}</td>
                      <td>${item.stc_purchase_product_adhoc_productid}</td>
                      <td>${item.stc_cust_super_requisition_list_items_title}</td>
                      <td>${item.stc_cust_super_requisition_list_items_unit}</td>
                      <td class="text-right">${item.stc_cust_super_requisition_list_items_rec_recqty}</td>
                      <td class="text-right">${item.stc_purchase_product_adhoc_rate}</td>
                      <td class="text-right">${Number(item.total).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                      <td class="text-right"><a class="btn btn-info showledgertr" href="javascript:void(0)" id="${index + 1}">View</a></td>
                    </tr>
                    <tr class="ledgeritemshow-${index + 1}" style="display:none;">
                      <td></td>
                      <td colspan="2"><input type="number" class="form-control stcledgeritemcodeupdate" Placeholder="Enter item code"></td>
                      <td colspan="2"><input type="number" class="form-control stcledgerquantityupdate" Placeholder="Enter quantity"></td>
                      <td><input type="submit" class="form-control stcledgeritemupdate" value="Save" id="${item.stc_cust_super_requisition_list_items_rec_id}" id2="${item.stc_purchase_product_adhoc_id}"></td>
                    </tr>
                    `;
                    total+=parseFloat(item.total);
                    $('.stc-call-view-poadhocledger-row').append(row);
                  });
                  var outrow = `
                    <tr>
                      <td colspan="9" class="text-right"> Total : </td>
                      <td>${Number(total).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    </tr>`;
                    $('.stc-call-view-poadhocledger-row').append(outrow);
                } else {
                  $('.stc-call-view-poadhocledger-row').html('<tr><td colspan="10">No data found</td></tr>');
                }
              },
              error: function (xhr, status, error) {
                // console.error('Error fetching data:', error, xhr.responseText);
                $('.stc-call-view-poadhocledger-row').html('<tr><td colspan="10">Error loading data</td></tr>');
              }
            });

          });

          $('body').delegate('.showledgertr', 'click', function(e){
            var id=$(this).attr('id');
            $('.ledgeritemshow-'+id).toggle();
          });

          $('body').delegate('.stcledgeritemupdate', 'click', function(e){
            var id=$(this).attr('id');
            var id2=$(this).attr('id2');
            var itemcode=$(this).parent().parent().find('.stcledgeritemcodeupdate').val();
            var quantity=$(this).parent().parent().find('.stcledgerquantityupdate').val();
            var rate=$(this).parent().parent().find('.stcledgerrateupdate').val();
            $(this).closest('tr').hide();
            $.ajax({
              url: "kattegat/ragnar_purchase.php", // Replace with your API endpoint
              type: 'POST', // or 'POST' depending on your API
              data: {
                stc_update_ledger: 1,
                id: id,  // Ensure these are defined before use
                itemcode: itemcode,  // Ensure these are defined before use
                quantity: quantity
              },
              dataType: 'json',
              success: function (response) {
                alert("Item updated successfully.");Pagination.loadData(pagenumber);
              }
            });
          });

          $('body').delegate('.input-shop-item', 'click', function(e){
            var id=$(this).attr('id');
            $('.stc-poadhocshop-id').val(id);
          });

          $('body').delegate('.stc-poadhoc-addtohshop-hit', 'click', function(e){
            var id=$('.stc-poadhocshop-id').val();
            var name=$('#stcpoadhocnameshop').val();
            var quantity=$('#stcpoadhocquantityshop').val();
            $.ajax({
              url: "kattegat/ragnar_purchase.php", // Replace with your API endpoint
              type: 'POST', // or 'POST' depending on your API
              data: {
                stc_addItemshop: 1,
                id: id,  // Ensure these are defined before use
                name: name,  // Ensure these are defined before use
                quantity: quantity
              },
              dataType: 'json',
              success: function (response) {
                alert("Item saved successfully.");
                Pagination.loadData(pagenumber);
              }
            });
          });
          

          $('body').delegate('.remove-shop-item', 'click', function(e){
            var id=$(this).attr('id');
            if(confirm("Are you sure want to remove item from this shop?")){
              $.ajax({
                url: "kattegat/ragnar_purchase.php", // Replace with your API endpoint
                type: 'POST', // or 'POST' depending on your API
                data: {
                  stc_removeItemshop: 1,
                  id: id
                },
                dataType: 'json',
                success: function (response) {
                  alert("Item deleted successfully.");
                  Pagination.loadData(pagenumber);
                }
              });
            }
          });          
          

          $('body').delegate('.update-purchased-lineitems', 'click', function(e){
            var id=$(this).attr('id');
            if(confirm("Are you sure want to change status of this Item?")){
              $.ajax({
                url: "kattegat/ragnar_purchase.php", // Replace with your API endpoint
                type: 'POST', // or 'POST' depending on your API
                data: {
                  stc_changestatus: 1,
                  id: id
                },
                dataType: 'json',
                success: function (response) {
                  alert("Item updated successfully.");
                  Pagination.loadData(pagenumber);
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
                          <th>Requisition Number</th>
                          <th>Date</th>
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
<div class="modal fade bd-modal-ledgershow" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
                    <table class="table table-hover table-bordered">
                      <thead>
                        <tr>
                          <th scope="col" class="text-center">Date From/To</th>
                          <th scope="col" class="text-center">Location </th>
                          <th scope="col" class="text-center">Action</th>
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
                            <input type="date" class="form-control stc-poa-filterdatefrom" <?php echo "value='$effectiveDate'";?>>
                            <input type="date" class="form-control stc-poa-filterdateto" <?php echo "value='$newDate'";?>>
                          </td>
                          <td>
                            <input type="text" class="form-control stc-poa-filtersitename" placeholder="Enter Sitename">
                          </td>
                          <td>
                            <a class="btn btn-success stc-adhocpofilter-find" href="javascript:void(0)">Find</a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                <div class="col-xl-12 col-md-12 col-sm-12">
                  <div class="card border-success mb-3 card-body">
                    <table class="table table-hover table-bordered stc-purchase-view-table">
                      <thead>
                        <tr>
                          <th>Sl No.</th>
                          <th>Date</th>
                          <th>Site Name</th>
                          <th>Supervisors Name</th>
                          <th>Item Code</th>
                          <th>Product Name</th>
                          <th>Unit</th>
                          <th>Quantity</th>
                          <th>Rate</th>
                          <th>Total</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody class="stc-call-view-poadhocledger-row">
                        <tr><td colspan="8">Search</td></tr>
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
<div class="modal fade bd-showadhocshop-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-s ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Item to Shop</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12">
                  <div class="card-border mb-3 card card-body border-success">
                    <h5
                      for="stcpoadhocnameshop"
                      >Shop Name
                    </h5>
                    <input type="hidden" class="stc-poadhocshop-id">
                    <select
                      id="stcpoadhocnameshop"
                      placeholder="Shop Name"
                      class="form-control validate"
                    ><option>Dhatkidih</option><option>Kolkata</option><option>Sehrabazar</option></select>
                  </div>
                </div>
                <div class="col-xl-12 col-md-12 col-sm-12">
                  <div class="card-border mb-3 card card-body border-success">
                    <h5
                      for="stcpoadhocquantityshop"
                      >Quantity
                    </h5>
                    <input
                      id="stcpoadhocquantityshop"
                      type="number"
                      placeholder="Quantity"
                      class="form-control validate"
                    />
                  </div>
                </div>
                <div class="col-xl-12 col-md-12 col-sm-12">
                  <div class="card-border mb-3 card card-body border-success">
                    <button type="button"  data-dismiss="modal" class="btn btn-success stc-poadhoc-addtohshop-hit">Save</button>
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