<?php  
// Include authentication helper
require_once 'kattegat/auth_helper.php';

// Check authentication using the new hybrid system
STCAuthHelper::checkAuth();$page_code=407;
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

      /* Percentage calculation styling */
      .percentage-input {
          margin-top: 5px;
          border-left: 3px solid #28a745;
      }

      .sale-rate-display {
          margin-top: 5px;
          border-left: 3px solid #007bff;
          font-weight: bold;
      }

      .purchase-rate-input {
          border-left: 3px solid #ffc107;
      }

      .percentage-input:focus {
          border-color: #28a745;
          box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
      }

      .sale-rate-display:focus {
          border-color: #007bff;
          box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
      }

      /* Inline editing percentage styling */
      .img-idpercentageinput {
          margin-top: 5px;
          border-left: 3px solid #28a745;
          background-color: #f8f9fa;
      }

      .img-idpercentageinput:focus {
          border-color: #28a745;
          box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
          background-color: #fff;
      }

      .img-idprateinput {
          border-left: 3px solid #ffc107;
      }

      .img-idrateinput {
          margin-top: 5px;
          border-left: 3px solid #007bff;
          font-weight: bold;
          background-color: #e9ecef;
      }

      .stc-purchase-view-table td.shop-qty-cell-clickable {
          cursor: pointer;
          vertical-align: middle !important;
      }
      .stc-purchase-view-table td.shop-qty-cell-clickable:hover {
          background-color: #f0f7ff;
      }
      #vikings-shop-qty-modal .table td {
          vertical-align: middle;
      }

      /* Item name autocomplete styling */
      .itemname-autocomplete-wrapper {
          position: relative;
      }

      .itemname-autocomplete-list {
          position: absolute;
          top: 100%;
          left: 0;
          right: 0;
          background-color: #fff;
          border: 1px solid #ced4da;
          border-top: none;
          max-height: 200px;
          overflow-y: auto;
          z-index: 1000;
          list-style: none;
          padding: 0;
          margin: 0;
          box-shadow: 0 4px 6px rgba(0,0,0,0.1);
      }

      .itemname-autocomplete-list li {
          padding: 10px 15px;
          cursor: pointer;
          border-bottom: 1px solid #f0f0f0;
          font-size: 14px;
      }

      .itemname-autocomplete-list li:hover {
          background-color: #f8f9fa;
      }

      .itemname-autocomplete-list li:last-child {
          border-bottom: none;
      }

      .itemname-autocomplete-list li strong {
          color: #007bff;
      }

      /* Purchase adhoc search bar: merged input + icon button */
      .stc-poa-searchbar {
          width: 100%;
      }
      .stc-poa-searchbar > .form-control {
          height: 38px;
          line-height: 38px;
          border-top-right-radius: 0;
          border-bottom-right-radius: 0;
      }
      .stc-poa-searchbar > .input-group-btn > .btn.stc-poa-search-btn {
          height: 38px;
          line-height: 38px;
          padding: 0 14px;
          border-top-left-radius: 0;
          border-bottom-left-radius: 0;
      }
      .stc-poa-searchbar > .input-group-btn > .btn.stc-poa-search-btn i {
          font-size: 16px;
      }

      /* Advanced search modal layout fixes */
      .bd-modal-adhoc-advanced-search .stc-adv-header {
          display: flex;
          align-items: flex-start;
          gap: 12px;
      }
      .bd-modal-adhoc-advanced-search .stc-adv-header-icon {
          width: 38px;
          height: 38px;
          border-radius: 10px;
          background: rgba(63,106,216,0.10);
          color: #3f6ad8;
          display: flex;
          align-items: center;
          justify-content: center;
          flex: 0 0 auto;
          margin-top: 2px;
      }
      .bd-modal-adhoc-advanced-search .stc-adv-header-title {
          margin: 0;
          font-size: 18px;
          font-weight: 700;
          color: #1f2d3d;
          line-height: 1.2;
      }
      .bd-modal-adhoc-advanced-search .modal-content {
          border-radius: 10px;
          overflow: hidden;
          border: 0;
          box-shadow: 0 12px 30px rgba(0,0,0,0.2);
      }
      .bd-modal-adhoc-advanced-search .modal-header {
          background: linear-gradient(90deg, #f7fbff, #ffffff);
          border-bottom: 1px solid #eef2f6;
          padding: 14px 16px;
          display: flex;
          align-items: flex-start;
      }
      .bd-modal-adhoc-advanced-search .modal-header .close {
          font-size: 26px;
          opacity: 0.6;
          margin-left: auto;
      }
      .bd-modal-adhoc-advanced-search .modal-header .close:hover {
          opacity: 0.9;
      }
      .bd-modal-adhoc-advanced-search .stc-adv-subtitle {
          margin-top: 4px;
          color: #6c757d;
          font-size: 12px;
      }
      .bd-modal-adhoc-advanced-search .modal-body .form-group {
          margin-bottom: 14px;
      }
      .bd-modal-adhoc-advanced-search .stc-adv-field-label {
          display: block;
          margin: 0;
          font-weight: 600;
          line-height: 1.2;
      }
      .bd-modal-adhoc-advanced-search .modal-body .form-control {
          height: 38px;
      }
      .bd-modal-adhoc-advanced-search .modal-body {
          background: #fbfcfe;
          padding: 16px;
      }
      .bd-modal-adhoc-advanced-search .stc-adv-grid {
          display: flex;
          flex-wrap: wrap;
          margin: -8px;
      }
      .bd-modal-adhoc-advanced-search .stc-adv-col {
          padding: 8px;
          width: 50%;
      }
      @media (max-width: 767px) {
          .bd-modal-adhoc-advanced-search .stc-adv-col { width: 100%; }
      }
      .bd-modal-adhoc-advanced-search .stc-adv-field {
          background: #fff;
          border: 1px solid #eef2f6;
          border-radius: 10px;
          padding: 12px;
      }
      .bd-modal-adhoc-advanced-search .stc-adv-label {
          display: flex;
          align-items: center;
          gap: 8px;
          margin-bottom: 8px;
      }
      .bd-modal-adhoc-advanced-search .stc-adv-label i {
          color: #3f6ad8;
          width: 16px;
          text-align: center;
      }
      .bd-modal-adhoc-advanced-search .stc-adv-actions {
          display: flex;
          gap: 10px;
          justify-content: flex-end;
          padding: 12px 16px;
          border-top: 1px solid #eef2f6;
          background: #fff;
      }
      .bd-modal-adhoc-advanced-search .stc-adv-actions .btn {
          border-radius: 8px;
          padding: 9px 14px;
      }
      .bd-modal-adhoc-advanced-search .stc-adv-clear-inline {
          margin-right: auto;
      }
      .bd-modal-adhoc-advanced-search .stc-adv-clear-inline.btn {
          background: #f5f7fb;
          border-color: #e6ebf3;
      }

      /* Horizontal scroll for wide result tables */
      .stc-table-scroll {
          display: block;
          width: 100%;
          max-width: 100%;
          min-width: 0;
          overflow-x: auto;
          overflow-y: hidden;
          -webkit-overflow-scrolling: touch;
          box-sizing: border-box;
      }
      .stc-table-scroll table {
          min-width: 1200px;
          width: auto;
          table-layout: auto;
      }
      .stc-table-scroll th,
      /* .stc-table-scroll td {
          white-space: nowrap;
      } */
      .stc-view-purchase-order-form {
          max-width: 100%;
          overflow: visible;
      }

      /* Columns dropdown: clean ul/li list */
      #stc-poa-columns-menu {
          min-width: 420px !important;
          max-width: 520px;
          padding: 10px 12px !important;
      }
      #stc-poa-columns-menu-items .stc-poa-col-ul {
          list-style: none;
          padding: 0;
          margin: 0;
          max-height: 260px;
          overflow: auto;
          position: static !important;
      }
      #stc-poa-columns-menu-items .stc-poa-col-li {
          padding: 0;
          margin: 0;
          position: static !important;
          float: none !important;
          clear: both;
          width: 100%;
      }
      #stc-poa-columns-menu-items .stc-poa-col-li:first-child {
          margin-top: 5px;
      }
      #stc-poa-columns-menu-items .stc-poa-col-item {
          display: flex;
          gap: 10px;
          align-items: flex-start;
          padding: 6px 6px;
          margin: 0;
          font-weight: normal;
          cursor: pointer;
          user-select: none;
          position: static !important;
          float: none !important;
          width: 100%;
      }
      #stc-poa-columns-menu-items .stc-poa-col-item:hover {
          background: #f7f7f7;
      }
      #stc-poa-columns-menu-items .stc-poa-col-item input[type="checkbox"] {
          margin: 2px 0 0 0;
          flex: 0 0 auto;
      }
      #stc-poa-columns-menu-items .stc-poa-col-label {
          flex: 1 1 auto;
          min-width: 0;
          white-space: normal;
          word-break: break-word;
          overflow-wrap: anywhere;
          line-height: 1.25;
      }

      .autocomplete-item-name {
          font-weight: 600;
          color: #333;
      }

      .autocomplete-item-details {
          font-size: 12px;
          color: #666;
          margin-top: 3px;
      }

      /* Typeahead dropdown (ul/li) */
      .stc-typeahead-wrap {
          position: relative;
      }
      .stc-typeahead-list {
          position: absolute;
          top: calc(100% + 2px);
          left: 0;
          right: 0;
          z-index: 2000;
          background: #fff;
          border: 1px solid #e6ebf3;
          border-radius: 10px;
          box-shadow: 0 10px 22px rgba(0,0,0,0.12);
          max-height: 240px;
          overflow: auto;
          padding: 6px 0;
          margin: 0;
          list-style: none;
          display: none;
      }
      .stc-typeahead-item {
          padding: 8px 12px;
          cursor: pointer;
          white-space: nowrap;
          overflow: hidden;
          text-overflow: ellipsis;
      }
      .stc-typeahead-item:hover {
          background: #f5f7fb;
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
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                        <form action="" class="stc-add-poadhoc-product-form">
                                            <div class="card-border mb-3 card card-body border-success">
                                                <table class="table table-bordered" id="itemsTable">
                                                    <thead>
                                                        <tr>
                                                            <th>Item Code</th>
                                                            <th>Item Name</th>
                                                            <th>Quantity</br>Unit</th>
                                                            <th>Purchase Rate</br>Profit % / Sale Rate</th>
                                                            <th>Rack</br>Condition</th>
                                                            <th>From (Source/Location)<br>To (Destination/Location)</th>
                                                            <th>Received By</th>
                                                            <th>Remarks</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr class="item-row">
                                                            <td style="width: 100px;">
                                                              <a href="#" class="form-control btn btn-primary product-select" data-toggle="modal" data-target=".stc-agent-req-get-mer-product-show" title="Select Product">
                                                                <i class="fa fa-edit"></i>
                                                              </a>
                                                                <input
                                                                    name="itemcode[]"
                                                                    type="hidden"
                                                                    placeholder="Item Code"
                                                                    class="form-control validate"
                                                                    required
                                                                />
                                                            </td>
                                                            <td style="width: 330px;">
                                                                <div class="itemname-autocomplete-wrapper" style="position: relative;">
                                                                    <textarea
                                                                        name="itemname[]"
                                                                        type="text"
                                                                        placeholder="Item Name"
                                                                        class="form-control validate itemname-input"
                                                                        required
                                                                    ></textarea>
                                                                    <ul class="itemname-autocomplete-list" style="display: none;"></ul>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <input
                                                                    name="quantity[]"
                                                                    type="number"
                                                                    placeholder="Quantity"
                                                                    class="form-control validate"
                                                                    required
                                                                />
                                                                <select 
                                                                    name="unit[]"
                                                                    class="form-control validate"
                                                                  >
                                                                    <option>BAG</option>
                                                                    <option>BOX</option>
                                                                    <option>BUNDLE</option>
                                                                    <option>CASE</option>
                                                                    <option>CFT</option>
                                                                    <option>CuMTR</option>
                                                                    <option>DRUM</option>
                                                                    <option>FEET</option>
                                                                    <option>GM</option>
                                                                    <option>JAR</option>
                                                                    <option>KGS</option>
                                                                    <option>LTR</option>
                                                                    <option>MTR</option>
                                                                    <option>MTS</option>
                                                                    <option selected>NOS</option>
                                                                    <option>PAIR</option>
                                                                    <option>PKT</option>
                                                                    <option>ROLL</option>
                                                                    <option>SET</option>
                                                                    <option>SQFT</option>
                                                                    <option>SQMM</option>
                                                                    <option>SQMT</option>
                                                                    <option>TONS</option>
                                                                  </select>
                                                            </td>
                                                            <td>
                                                                <input
                                                                    name="prate[]"
                                                                    type="number"
                                                                    placeholder="Purchase Rate"
                                                                    class="form-control validate purchase-rate-input"
                                                                    required
                                                                    step="0.01"
                                                                />
                                                                <input
                                                                    name="percentage[]"
                                                                    type="number"
                                                                    placeholder="Profit %"
                                                                    class="form-control validate percentage-input"
                                                                    step="0.01"
                                                                    oninput="calculateSaleRate(this)"
                                                                />
                                                                <input
                                                                    name="rate[]"
                                                                    type="number"
                                                                    placeholder="Sale Rate"
                                                                    class="form-control validate sale-rate-display"
                                                                    value="0"
                                                                    readonly
                                                                    style="background-color: #f8f9fa;"
                                                                />
                                                            </td>
                                                            <td>
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
                                                                <select 
                                                                    name="condition[]"
                                                                    class="form-control validate"
                                                                >
                                                                    <option value="NA">Select</option>
                                                                    <option value="Bad">Bad</option>
                                                                    <option value="Broken">Broken</option>
                                                                    <option value="Good" selected>Good</option>
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
                                                                    <option>DHATKIDIH SHOP NO 2</option>
                                                                    <option>DHATKIDIH STORE</option>
                                                                    <option>DURGAPUR MAIN HOSPITAL</option>
                                                                    <option>KOLKATA STORE</option>
                                                                    <option selected>MANGO 17 NO STORE</option>
                                                                    <option>MANGO SHOP NO-1</option>
                                                                    <option>PARDIH STORE</option>
                                                                    <option>RAMGARH WAREHOUSE</option>
                                                                    <option>SARA INTERNATIONAL SCHOOL</option>
                                                                    <option>SARA GIRLS MISSION SCHOOL</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <select 
                                                                    name="receivedby[]"
                                                                    class="form-control validate"
                                                                  >
                                                                    <option>HASIBUL MONDAL</option>
                                                                    <option>GAUTAM</option>
                                                                    <option>JOYDEEP</option>
                                                                    <option>KASEM</option>
                                                                    <option>MURTUZA</option>
                                                                    <option>MANSOOR</option>
                                                                    <option>MUSHTAK</option>
                                                                    <option>PAL DA</option>
                                                                    <option>RAFIQUE</option>
                                                                    <option>RAJESH MUNSHI</option>
                                                                    <option>RAVI</option>
                                                                    <option>RAZA</option>
                                                                    <option>RAZi</option>
                                                                    <option>SUBHDIP</option>
                                                                    <option>ZUBER</option>
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
                                <div class="row stc-view-product-row">
                                  <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <div class="row mb-3">
                                        <div class="col-4 col-sm-12 text-left">
                                          <div class="btn-group" role="group" aria-label="Quick actions">
                                            <a
                                              class="btn btn-sm btn-outline-secondary"
                                              data-toggle="modal"
                                              data-target=".bd-modal-inventoryshow"
                                              href="javascript:void(0)"
                                              title="View Inventory"
                                              aria-label="View Inventory"
                                              style="font-size: 25px;"
                                            ><i class="fa fa-cubes" aria-hidden="true"></i></a>
                                            <a
                                              class="btn btn-sm btn-outline-success"
                                              data-toggle="modal"
                                              data-target=".bd-modal-ledgershow"
                                              href="javascript:void(0)"
                                              title="View Ledger"
                                              aria-label="View Ledger"
                                              style="font-size: 25px;"
                                            ><i class="fa fa-book" aria-hidden="true"></i></a>
                                            <a
                                              class="btn btn-sm btn-outline-danger"
                                              data-toggle="modal"
                                              data-target=".bd-modal-pendingshow"
                                              href="javascript:void(0)"
                                              title="View Adjustments"
                                              aria-label="View Adjustments"
                                              style="font-size: 25px;"
                                            ><i class="fa fa-sliders" aria-hidden="true"></i></a>
                                            <a
                                              class="btn btn-sm btn-outline-primary reset-items"
                                              href="javascript:void(0)"
                                              title="Reset Items"
                                              aria-label="Reset Items"
                                              style="font-size: 25px;"
                                            ><i class="fa fa-refresh" aria-hidden="true"></i></a>
                                          </div>
                                        </div>

                                        <div class="col-md-3 col-sm-6 mb-2">
                                          <button
                                            type="button"
                                            class="btn btn-primary btn-block"
                                            data-toggle="modal"
                                            data-target=".bd-modal-adhoc-advanced-search"
                                          >
                                            <i class="fa fa-filter" aria-hidden="true"></i> Advanced search
                                          </button>
                                        </div>

                                        <div class="col-md-3 col-sm-6 mb-2">
                                          <div class="dropdown">
                                            <button
                                              class="btn btn-success btn-block dropdown-toggle"
                                              type="button"
                                              id="stc-poa-columns-btn"
                                              data-toggle="dropdown"
                                              aria-haspopup="true"
                                              aria-expanded="false"
                                            >
                                              <i class="fa fa-columns" aria-hidden="true"></i> Columns <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="stc-poa-columns-btn" id="stc-poa-columns-menu">
                                              <li style="padding: 2px 0;"><strong>Show / hide columns</strong></li>
                                              <li role="separator" class="divider"></li>
                                              <li style="padding: 0;">
                                                <div id="stc-poa-columns-menu-items"></div>
                                              </li>
                                            </ul>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="row" style="align-items: center;">
                                        <div class="col-md-3 col-sm-12 mb-2">
                                          <div class="input-group stc-poa-searchbar">
                                            <input
                                              type="text"
                                              class="form-control stc-poa-searchbyitem"
                                              id="stc-poa-searchbyitem"
                                              placeholder="Search here…"
                                            >
                                            <span class="input-group-btn">
                                              <button type="button" class="btn btn-success stc-adhocpo-find stc-poa-search-btn" title="Search" aria-label="Search">
                                                <i class="fa fa-search" aria-hidden="true"></i>
                                              </button>
                                            </span>
                                          </div>
                                        </div>
                                        <div class="col-md-3 col-sm-12 mb-2">
                                          <div class="stc-adv-field">
                                            <input type="text" id="stc-adv-adhoc-name" class="form-control" placeholder="Type source/location name…" aria-labelledby="stc-adv-lbl-adhocname">
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-12 mb-2">
                                          <div class="stc-adv-field">
                                            <input type="text" id="stc-adv-rack" class="form-control" placeholder="Type rack name…" aria-labelledby="stc-adv-lbl-adhocname">
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-12 mb-2">
                                          <div class="stc-adv-field">
                                            <select id="stc-poa-status" class="custom-select form-control stc-po-status-in" aria-labelledby="stc-adv-lbl-status">
                                              <option value="NA">Select Status</option>
                                              <option value="1">Stock</option>
                                              <option value="2">Dispatched</option>
                                              <option value="3">Pending</option>
                                              <option value="4">Approved</option>
                                              <option value="5">Rejected</option>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-12 mb-2">
                                          <div class="stc-adv-field">
                                          <button type="button" class="form-control btn btn-success stc-adhocpo-find stc-poa-search-btn" title="Search" aria-label="Search">
                                                <i class="fa fa-search" aria-hidden="true"></i>
                                              </button>
                                          </div>
                                        </div>
                                      </div>

                                      <!-- Advanced search modal lives near page bottom -->
                                    </div>
                                  </div>
                                </div>
                                <div class="row stc-view-purchase-row" style="overflow-x: auto;">
                                  <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <form action="" class="stc-view-purchase-order-form">
                                        <div class="stc-table-scroll">
                                          <table class="table table-hover table-bordered stc-purchase-view-table" id="stc-poadhoc-results-table">
                                            <thead>
                                              <tr>
                                              <th>
                                                <input type="checkbox" id="selectAllCheckbox" class="form-control" style="width: 20px; height: 20px; margin: 0 auto; display: block;">
                                                <br>Sl No.
                                              </th>
                                              <th>Adhoc_Id</th>
                                              <th>Linked Product</th>
                                              <th>Product ID<br> Product Name</th>
                                              <th>Item Name</th>
                                              <th>Rack</th>
                                              <th>Unit</th>
                                              <th>Quantity</th>
                                              <th>Purchase Rate</br>Profit % / Sale Rate</th>
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
                                              </tr>
                                            </thead>
                                            <tbody class="stc-call-view-poadhoc-row">
                                              <tr><td colspan="8">Search</td></tr>
                                            </tbody>
                                          </table>
                                        </div>
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
    <script type="text/javascript" src="./assets/scripts/sidebar-persist.js"></script>
    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    <script type="text/javascript" src="./assets/scripts/jarvis.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script>
        function escapeHtml(str) {
          str = (str === undefined || str === null) ? '' : String(str);
          return str
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
        }

        $(document).ready(function(){
          const urlParams = new URLSearchParams(window.location.search);

          function stcSyncPurchaseRowWidthPx() {
            var $row = $('.stc-view-purchase-row');
            if (!$row.length) return;

            // Use browser (viewport) width in px.
            var w = window.innerWidth || document.documentElement.clientWidth || $(window).width();
            if (!w || isNaN(w)) return;

            // Keep a tiny safety padding so it never pushes beyond viewport.
            w = Math.max(320, Math.floor(w - 2));

            // Match sidebar-persist.js persistence (localStorage key).
            // If sidebar is closed, we can safely show a bit more table width.
            var sidebarClosed = false;
            try { sidebarClosed = (localStorage.getItem('stc_sidebar_closed') === '1'); } catch (e) {}
            // Fallback: class on the container (what sidebar-persist.js toggles)
            if (!sidebarClosed) {
              sidebarClosed = $('.app-container').hasClass('closed-sidebar');
            }
            if (sidebarClosed) {
              w = w + 200;
            }else{
              w = w - 200;

            }
            // console.log(w);
            // console.log(window.innerWidth);
            w = window.innerWidth - 300;
            // $row.css('width', w + 'px');
            $row.css('width', w + 'px');
          }

          // Run now + on resize
          stcSyncPurchaseRowWidthPx();
          $(window).on('resize', function() {
            stcSyncPurchaseRowWidthPx();
          });

          // Sidebar toggle often changes body classes; observe and resync.
          try {
            var target = document.body;
            var obs = new MutationObserver(function(mutations) {
              for (var i = 0; i < mutations.length; i++) {
                if (mutations[i].attributeName === 'class') {
                  stcSyncPurchaseRowWidthPx();
                  break;
                }
              }
            });
            obs.observe(target, { attributes: true });
          } catch (e) {}
          // Handle input/focus for each search input
          $(document).on('input focus', '.search-input', function () {
              var $container = $(this).closest('.searchable-dropdown');
              var $dropdown = $container.find('.dropdown-options');
              var $hiddenSelect = $container.find('select[name="sourcerack_value[]"]');

              var searchTerm = $(this).val().toUpperCase();

              // Collect options for THIS select
              var options = [];
              $hiddenSelect.find('option').each(function () {
                  options.push({
                      value: $(this).val(),
                      text: $(this).text()
                  });
              });

              // Filter options
              var filteredOptions = options.filter(function (option) {
                  return option.text.toUpperCase().includes(searchTerm) ||
                        option.value.toUpperCase().includes(searchTerm);
              });

              // Build dropdown HTML
              var dropdownHTML = '';
              filteredOptions.forEach(function (option) {
                  dropdownHTML += `<div class="dropdown-item" data-value="${option.value}">${option.text}</div>`;
              });

              // Update dropdown
              $dropdown.html(dropdownHTML).toggle(filteredOptions.length > 0 && searchTerm !== '');
          });

          // Handle click on dropdown items
          $(document).on('click', '.dropdown-item', function () {
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
          $(document).on('click', function (e) {
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

          // If a selector accidentally matches multiple inputs (duplicate IDs in legacy markup),
          // pick the first non-empty value (fallback to the first element).
          function stcPickInputValue(selector, defaultValue) {
            var $els = $(selector);
            var out = '';
            $els.each(function() {
              var v = $.trim($(this).val() || '');
              if (v !== '') { out = v; return false; }
            });
            if (out === '') out = $.trim(($els.first().val() || ''));
            if (out === '') out = (defaultValue === undefined ? '' : defaultValue);
            return out;
          }

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
                              adhoc_id: $('#stc-adv-adhoc-id').val(),
                              product_id: $('#stc-adv-product-id').val(),
                              product_name: $('#stc-adv-product-name').val(),
                              // This field is for source/location (NOT itemdesc)
                              sourcelocation: stcPickInputValue('#stc-adv-adhoc-name', ''),
                              // Keep for backward compatibility (server uses source/destination filters instead)
                              adhoc_name: '',
                              sourcedestination: $('#tc-poa-searchbydourcedestination').val(),
                              // Support both inline (top row) and advanced modal inputs
                              byrack: ($('#stc-adv-rack').val() || $('.tc-poa-searchbyrack').val() || ''),
                              status: (stcPickInputValue('#stc-poa-status', 'NA') || $('.stc-po-status-in').val() || 'NA'),
                              received_by: $('#stc-adv-received-by').val(),
                              remarks: $('#stc-adv-remarks').val(),
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

          // Trigger search on Enter in the main search box
          $('body').delegate('#stc-poa-searchbyitem', 'keydown', function(e) {
            if (e.keyCode === 13) {
              e.preventDefault();
              $('.stc-adhocpo-find').trigger('click');
            }
          });

          // Advanced search apply / clear
          $('body').delegate('.stc-adhocpo-adv-apply', 'click', function(e) {
            e.preventDefault();
            var $advModal = $('.bd-modal-adhoc-advanced-search');
            // Sometimes backdrop gets "stuck" if an Ajax refresh happens mid-hide.
            $advModal.one('hidden.bs.modal', function() {
              $('body').removeClass('modal-open').css('padding-right', '');
              $('.modal-backdrop').remove();
            });
            $advModal.modal('hide');
            currentPage = 1;
            Pagination.loadData(currentPage);
          });
          $('body').delegate('.stc-adhocpo-adv-clear', 'click', function(e) {
            e.preventDefault();
            $('#stc-adv-adhoc-id').val('');
            $('#stc-adv-product-id').val('');
            $('#stc-adv-product-name').val('');
            $('[id="stc-adv-adhoc-name"]').val('');
            $('#stc-adv-rack').val('');
            $('[id="stc-poa-status"]').val('NA');
            $('#stc-adv-received-by').val('');
            $('#stc-adv-remarks').val('');
            $('#tc-poa-searchbydourcedestination').val('');
            $('.tc-poa-searchbyrack').val('');
            $('.stc-po-status-in').val('NA');
          });

          // Safety net: if any modal leaves a stray backdrop, clean it.
          $(document).on('hidden.bs.modal', function() {
            if ($('.modal.in').length === 0) {
              $('body').removeClass('modal-open').css('padding-right', '');
              $('.modal-backdrop').remove();
            }
          });

          // Columns show/hide dropdown
          function stcSetCookie(name, value, days) {
            var expires = '';
            if (days) {
              var date = new Date();
              date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
              expires = '; expires=' + date.toUTCString();
            }
            document.cookie = name + '=' + encodeURIComponent(value || '') + expires + '; path=/';
          }
          function stcGetCookie(name) {
            var nameEQ = name + '=';
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
              var c = ca[i];
              while (c.charAt(0) === ' ') c = c.substring(1, c.length);
              if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
            }
            return null;
          }

          function initPoaColumnChooser() {
            var $table = $('#stc-poadhoc-results-table');
            var $itemsHost = $('#stc-poa-columns-menu-items');
            if (!$table.length || !$itemsHost.length) return;

            // Build only once
            if ($itemsHost.data('built') === 1) return;
            $itemsHost.data('built', 1);

            var cookieKey = 'stc_poadhoc_cols_v1';
            var storedRaw = stcGetCookie(cookieKey);
            var stored = null;
            try { stored = storedRaw ? JSON.parse(storedRaw) : null; } catch (e) { stored = null; }

            function normalizeLabel(s) {
              return $.trim(String(s || '').replace(/\s+/g, ' ').toLowerCase());
            }

            // Default hidden columns (applied only when there is no cookie yet)
            var defaultHidden = {};
            [
              'purchase rate profit % / sale rate',
              'shop',
              'dispatch details',
              'from source (supplier/location) to destination (location)',
              'condition',
              'remarks'
            ].forEach(function(lbl) { defaultHidden[normalizeLabel(lbl)] = true; });

            // Employee-specific column restrictions (hide + remove from chooser)
            var stcEmpId = <?php echo isset($_SESSION['stc_empl_id']) ? (int)$_SESSION['stc_empl_id'] : 0; ?>;
            var stcRestrictRateCol = (stcEmpId === 19 || stcEmpId === 20);

            var $ths = $table.find('thead th');
            var html = '<ul class="stc-poa-col-ul" role="menu">';
            $ths.each(function(i) {
              var idx = i + 1; // nth-child is 1-based

              // Remove Sl No (first column) from chooser
              if (idx === 1) return;

              var $thClone = $(this).clone();
              $thClone.find('input, br').remove();
              var label = $.trim($thClone.text().replace(/\s+/g, ' '));
              if (!label) label = 'Column ' + idx;

              var norm = normalizeLabel(label);
              // For employees 19 & 20, completely skip the rate column in chooser and force-hide it.
              if (stcRestrictRateCol && idx === 9) {
                setPoaColumnVisible(idx, false);
                return;
              }

              var isVisible;
              if (stored && typeof stored[idx] !== 'undefined') {
                isVisible = !!stored[idx];
              } else {
                isVisible = !defaultHidden[norm];
              }
              html += '' +
                '<li class="stc-poa-col-li" role="none">' +
                  '<label class="stc-poa-col-item" role="menuitemcheckbox" aria-checked="' + (isVisible ? 'true' : 'false') + '">' +
                    '<input type="checkbox" class="stc-poa-col-toggle" data-col="' + idx + '" ' + (isVisible ? 'checked' : '') + '>' +
                    '<span class="stc-poa-col-label">' + escapeHtml(label) + '</span>' +
                  '</label>' +
                '</li>';
            });
            html += '</ul>';
            $itemsHost.html(html);

            // Apply initial visibility state (cookie or defaults)
            $itemsHost.find('.stc-poa-col-toggle').each(function() {
              var colIdx = parseInt($(this).data('col'), 10);
              setPoaColumnVisible(colIdx, $(this).is(':checked'));
            });

            // If no cookie existed yet, persist the defaults now
            if (!stored) {
              var toStore = {};
              $itemsHost.find('.stc-poa-col-toggle').each(function() {
                var colIdx = parseInt($(this).data('col'), 10);
                toStore[colIdx] = $(this).is(':checked');
              });
              stcSetCookie(cookieKey, JSON.stringify(toStore), 365);
            }

            // Prevent dropdown close on checkbox click
            $('#stc-poa-columns-menu').on('click', function(ev) {
              ev.stopPropagation();
            });
          }

          function setPoaColumnVisible(colIdx, isVisible) {
            var $table = $('#stc-poadhoc-results-table');
            if (!$table.length) return;
            var displayValue = isVisible ? '' : 'none';
            $table.find('thead th:nth-child(' + colIdx + ')').css('display', displayValue);
            $table.find('tbody tr').each(function() {
              $(this).find('td:nth-child(' + colIdx + ')').css('display', displayValue);
            });
          }

          function applyPoaColumnVisibilityToTable() {
            var $table = $('#stc-poadhoc-results-table');
            if (!$table.length) return;

            var stcEmpId = <?php echo isset($_SESSION['stc_empl_id']) ? (int)$_SESSION['stc_empl_id'] : 0; ?>;
            var stcRestrictRateCol = (stcEmpId === 19 || stcEmpId === 20);

            // Prefer current UI state if chooser exists; fallback to cookie.
            var $toggles = $('#stc-poa-columns-menu-items .stc-poa-col-toggle');
            if ($toggles.length) {
              $toggles.each(function() {
                var colIdx = parseInt($(this).data('col'), 10);
                setPoaColumnVisible(colIdx, $(this).is(':checked'));
              });

              // Employee-specific: even though column is not in chooser, force-hide its TDs too.
              if (stcRestrictRateCol) {
                setPoaColumnVisible(9, false);
              }
              return;
            }

            // Employee-specific: force-hide rate column for 19 & 20.
            if (stcRestrictRateCol) {
              // Rate column is 9th in current table layout (Purchase Rate / Profit % / Sale Rate)
              setPoaColumnVisible(9, false);
            }

            var cookieKey = 'stc_poadhoc_cols_v1';
            var storedRaw = stcGetCookie(cookieKey);
            var stored = null;
            try { stored = storedRaw ? JSON.parse(storedRaw) : null; } catch (e) { stored = null; }
            if (!stored) return;

            Object.keys(stored).forEach(function(k) {
              var colIdx = parseInt(k, 10);
              if (!isNaN(colIdx)) setPoaColumnVisible(colIdx, !!stored[k]);
            });
          }

          function stcUnique(arr) {
            var out = [];
            var seen = {};
            for (var i = 0; i < arr.length; i++) {
              var v = String(arr[i] || '').trim();
              if (!v) continue;
              var key = v.toLowerCase();
              if (seen[key]) continue;
              seen[key] = 1;
              out.push(v);
            }
            return out;
          }

          function stcGetColumnTextValues(colIdx, splitLines) {
            var $table = $('#stc-poadhoc-results-table');
            if (!$table.length) return [];
            var values = [];
            $table.find('tbody tr').each(function() {
              var $td = $(this).find('td:nth-child(' + colIdx + ')');
              if (!$td.length) return;
              var raw = $td.text().replace(/\s+/g, ' ').trim();
              if (!raw) return;
              if (splitLines) {
                // Try to split source/destination cell into parts (source + destination)
                // Keep meaningful chunks by splitting on arrows/bullets and collapsing.
                var parts = raw
                  .replace(/➤/g, '|')
                  .replace(/►/g, '|')
                  .split('|')
                  .map(function(s){ return s.replace(/\s+/g, ' ').trim(); })
                  .filter(Boolean);
                if (parts.length) values = values.concat(parts);
                else values.push(raw);
              } else {
                values.push(raw);
              }
            });
            return stcUnique(values);
          }

          function stcRenderTypeahead($input, $list, allValues) {
            var term = String($input.val() || '').trim().toLowerCase();
            var filtered = allValues.filter(function(v) {
              return !term || v.toLowerCase().indexOf(term) !== -1;
            }).slice(0, 25);

            if (!filtered.length) {
              $list.hide().empty();
              return;
            }

            var html = '';
            filtered.forEach(function(v) {
              html += '<li class="stc-typeahead-item" data-value="' + escapeHtml(v) + '">' + escapeHtml(v) + '</li>';
            });
            $list.html(html).show();
          }

          function stcInitTypeaheads() {
            var $rackInput = $('#stc-poa-by-rack');
            var $rackList = $('#stc-typeahead-rack');
            var $locInput = $('#tc-poa-searchbydourcedestination');
            var $locList = $('#stc-typeahead-location');

            if ($rackInput.length && $rackList.length) {
              var rackValues = stcGetColumnTextValues(6, false); // Rack column
              $rackInput.off('.stcTypeahead').on('focus.stcTypeahead input.stcTypeahead', function() {
                rackValues = stcGetColumnTextValues(6, false);
                stcRenderTypeahead($rackInput, $rackList, rackValues);
              });
              $rackList.off('.stcTypeahead').on('click.stcTypeahead', '.stc-typeahead-item', function() {
                $rackInput.val($(this).data('value'));
                $rackList.hide().empty();
              });
            }

            if ($locInput.length && $locList.length) {
              var locValues = stcGetColumnTextValues(13, true); // Source/Destination column
              $locInput.off('.stcTypeahead').on('focus.stcTypeahead input.stcTypeahead', function() {
                locValues = stcGetColumnTextValues(13, true);
                stcRenderTypeahead($locInput, $locList, locValues);
              });
              $locList.off('.stcTypeahead').on('click.stcTypeahead', '.stc-typeahead-item', function() {
                $locInput.val($(this).data('value'));
                $locList.hide().empty();
              });
            }
          }

          // Build chooser after initial table exists and after each data reload
          initPoaColumnChooser();
          applyPoaColumnVisibilityToTable();
          stcInitTypeaheads();
          var originalLoadData = Pagination.loadData.bind(Pagination);
          Pagination.loadData = function(page) {
            return originalLoadData(page);
          };
          // Rebuild chooser after Ajax updates table body (once headers exist)
          $(document).ajaxComplete(function() {
            initPoaColumnChooser();
            // Re-apply column visibility to newly loaded dynamic <td> cells.
            applyPoaColumnVisibilityToTable();
            // Refresh typeahead sources after table updates.
            stcInitTypeaheads();
          });

          // Hide typeahead lists when clicking outside
          $(document).on('click', function(e) {
            if (!$(e.target).closest('.stc-typeahead-wrap').length) {
              $('.stc-typeahead-list').hide().empty();
            }
          });

          $('body').delegate('.stc-poa-col-toggle', 'change', function() {
            var colIdx = parseInt($(this).data('col'), 10);
            setPoaColumnVisible(colIdx, $(this).is(':checked'));
            $(this).closest('.stc-poa-col-item').attr('aria-checked', $(this).is(':checked') ? 'true' : 'false');

            // Persist to cookie
            var cookieKey = 'stc_poadhoc_cols_v1';
            var storedRaw = stcGetCookie(cookieKey);
            var stored = {};
            try { stored = storedRaw ? JSON.parse(storedRaw) : {}; } catch (e) { stored = {}; }
            stored[colIdx] = $(this).is(':checked');
            stcSetCookie(cookieKey, JSON.stringify(stored), 365);
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

          
          $('body').delegate('.get-dispatch-details', 'click', function(e){
            var poaid=$(this).attr('id');
            $.ajax({
              url       : "kattegat/ragnar_purchase.php",
              method    : "post",
              data      : {
                stc_call_poadhoc_glddetails:1,
                poaid: poaid
              },
              success   : function(data){
                // console.log(data);
                $('.show-glddispatchdetails-table').html(data);
              }
            });
          });

          
          if (urlParams.has('brequist_id')) {
            $('#addRow').hide();
          }
          if(urlParams.has('product_id')){
            $('#itemsTable tbody').find('input[name="itemcode[]"]').val(urlParams.get('product_id'));
          }

          // Item name autocomplete search function
          function searchItemName(inputElement) {
              var searchTerm = $(inputElement).val().trim();
              var autocompleteList = $(inputElement).closest('.itemname-autocomplete-wrapper').find('.itemname-autocomplete-list');
              
              // Hide autocomplete if search term is too short
              if(searchTerm.length < 2) {
                  autocompleteList.hide().empty();
                  return;
              }

              $.ajax({
                  url: "kattegat/ragnar_purchase.php",
                  method: "POST",
                  data: {
                      stc_search_itemname_autocomplete: 1,
                      keyword: searchTerm
                  },
                  dataType: "JSON",
                  success: function(response) {
                      autocompleteList.empty();
                      if(response.success && response.data && response.data.length > 0) {
                          $.each(response.data, function(index, item) {
                              var li = $('<li></li>');
                              var itemName = item.itemname || 'N/A';
                              var productid = item.productid || 'N/A';
                              var unit = item.unit || 'N/A';
                              var rate = item.rate || '0';
                              
                              li.html(
                                  '<div class="autocomplete-item-name">' + itemName + '</div>' +
                                  '<div class="autocomplete-item-details">' + productid + ' / ' + unit + ' / ' + rate + '</div>'
                              );
                              li.data('item', item);
                              li.on('click', function() {
                                  var row = $(inputElement).closest('tr');
                                  var itemData = $(this).data('item');
                                  
                                  // Populate fields
                                  row.find('[name="itemcode[]"]').val(itemData.productid || '');
                                  row.find('[name="itemname[]"]').val(itemData.itemname || '');
                                  row.find('[name="unit[]"]').val(itemData.unit || 'NOS');
                                  row.find('[name="rate[]"]').val(itemData.rate || '0');
                                  row.find('[name="prate[]"]').val(itemData.prate || itemData.rate || '0');
                                  
                                  // Hide autocomplete
                                  autocompleteList.hide();
                                  
                                  // Trigger change event for rate calculation if needed
                                  row.find('[name="prate[]"]').trigger('input');
                              });
                              autocompleteList.append(li);
                          });
                          autocompleteList.show();
                      } else {
                          autocompleteList.hide();
                      }
                  },
                  error: function() {
                      autocompleteList.hide();
                  }
              });
          }

          // Handle input event for itemname textarea (using delegate for dynamically added rows)
          $(document).on('input', '.itemname-input', function() {
              searchItemName(this);
          });

          // Hide autocomplete when clicking outside
          $(document).on('click', function(e) {
              if(!$(e.target).closest('.itemname-autocomplete-wrapper').length) {
                  $('.itemname-autocomplete-list').hide();
              }
          });
          
          // Add new row
          $('#addRow').click(function() {
              var newRow = $('.item-row:first').clone();
              newRow.find('input, textarea').val('');
              newRow.find('.itemname-autocomplete-list').hide().empty();
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
                      prate: row.find('[name="prate[]"]').val(),
                      rate: row.find('[name="rate[]"]').val(),
                      unit: row.find('[name="unit[]"]').val(),
                      rack: row.find('[name="rack[]"]').val(),
                      condition: row.find('[name="condition[]"]').val(),
                      sourcerack: row.find('[name="sourcerack[]"]').val(),
                      destination: row.find('[name="destination[]"]').val(),
                      receivedby: row.find('[name="receivedby[]"]').val(),
                      remarks: row.find('[name="remarks[]"]').val()
                  };
                  
                  // Validate required fields for this row
                  if(itemData.itemname && itemData.quantity && itemData.prate) {
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
              // Get the URL parameters
              var requist_id = 0;

              // Check if brequist_id exists in URL
              if (urlParams.has('brequist_id')) {
                  requist_id = urlParams.get('brequist_id');
                  requist_id = parseInt(requist_id);
              }
              $.ajax({
                  url: "kattegat/ragnar_purchase.php",
                  method: "POST",
                  data: {
                      stc_po_adhoc_save: 1,
                      items: formData,
                      requisition_id: requist_id
                  },
                  dataType: "JSON",
                  success: function(response){
                      if(response === "success") {
                          alert("Purchase Order Adhoc saved successfully.");
                          $(".stc-add-poadhoc-product-form")[0].reset();
                          Pagination.loadData(pagenumber);
                          if (urlParams.has('brequist_id')) {
                            window.location.href = "agent-order.php";
                          }
                      } else {
                          alert(response || "Something went wrong please check and try again.");
                      }
                  },
                  error: function(xhr, status, error) {
                      alert("Error: " + error);
                  }
              });
          });

          

          var jsfiltercat;
          var jsfiltersubcat;
          var jsfiltername;

          // search item on a modal
          $('body').delegate('.stcprosearchhit', 'click', function (e) {
            e.preventDefault();
            jsfiltercat = $('#filterbycat').val();
            jsfiltersubcat = $('#filterbysubcat').val();
            jsfiltername = $('#searchbystcname').val();
            stc_filter_pro_forrequist(jsfiltercat, jsfiltersubcat, jsfiltername);
          });

          // filter function
          function stc_filter_pro_forrequist(jsfiltercat, jsfiltersubcat, jsfiltername) {
            $.ajax({
              url: "kattegat/ragnar_order.php",
              method: "post",
              data: {
                stccallmercaction: 1,
                phpfiltercatout: jsfiltercat,
                phpfiltersubcatout: jsfiltersubcat,
                phpfilternameout: jsfiltername
              },
              // dataType : 'JSON',
              success: function (data) {
                // console.log(data);
                $('.stc-req-product-show').html(data);
                $('.add_to_requist_mer').attr('data-dismiss', 'modal');
              }
            });
          }
          $('body').delegate('.product-select', 'click', function (e) {
            // Remove classes from all previous rows first
            $('.product-select-val').removeClass("product-select-val");
            $('.product-select-name').removeClass("product-select-name");
            
            // Add classes to the current row
            var $row = $(this).closest('tr');
            var $input = $row.find('td:eq(0)').find('input');
            var $textarea = $row.find('td:eq(1)').find('textarea');
            $input.addClass('product-select-val');
            $textarea.addClass('product-select-name');
          });
          // add product for savess
          $('body').delegate('.add_to_requist_mer', 'click', function (e) {
            e.preventDefault();
            var pd_id = $(this).attr("id");
            var name = $(this).attr("pd-name");
            
            // Find the row that has the active product-select-val class
            var $activeInput = $('.product-select-val');
            var $activeTextarea = $('.product-select-name');
            
            if ($activeInput.length && $activeTextarea.length) {
              // Update only the active row's elements
              $activeInput.val(pd_id);
              $activeTextarea.val(name);
              
              // Remove classes after updating
              $activeInput.removeClass("product-select-val");
              $activeTextarea.removeClass("product-select-name");
              
              alert("Product added successfully.");
              $(".close").click();
            } else {
              alert("Please select a row first.");
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
            var item_qty=$(this).closest('tr').find('td:eq(7)').html();
            var item_remarks=$(this).closest('tr').find('td:eq(18)').html();
            var item_id=$(this).attr("id");
            $('#edit-pro-id').remove();
            $('#stcpoadhoceitemname').val(item_name);
            $('#stcpoadhoceitemrack option').filter(function() {
              return $(this).text().trim() === item_rack;
            }).prop('selected', true);
            $('#stcpoadhoceitemunit').val(item_unit);
            $('#stcpoadhoceitemqty').val(item_qty);
            $('#stcpoadhoceitemremarks').val(item_remarks);
            $('#stc-poadhocedit-id').val(item_id);
          });
          
          $('body').delegate('.stc-poadhoc-edititemname', 'click', function(e){
            var adhoc_id=$('#stc-poadhocedit-id').val();
            var adhoc_name=$('#stcpoadhoceitemname').val();
            var adhoc_rack=$('#stcpoadhoceitemrack').val();
            var adhoc_unit=$('#stcpoadhoceitemunit').val();
            var adhoc_qty=$('#stcpoadhoceitemqty').val();
            var adhoc_remarks=$('#stcpoadhoceitemremarks').val();
            $.ajax({
              url     : "kattegat/ragnar_purchase.php",
              method  : "POST",
              data    : {
                stc_po_adhoc_update:1,
                adhoc_id:adhoc_id,
                adhoc_name:adhoc_name,
                adhoc_rack:adhoc_rack,
                adhoc_unit:adhoc_unit,
                adhoc_qty:adhoc_qty,
                adhoc_remarks:adhoc_remarks
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
          
          // cherry pick functionalities
          
          // Open modal and fill values
          $(document).on('click', '.cherry-pick-btn', function() {
            const adhocId = $(this).data('adhoc-id');
            const currentQty = $(this).data('current-qty');
            const rate = $(this).data('rate');
            const unit = $(this).data('unit');
            $('#cherryAdhocId').val(adhocId);
            $('#cherryCurrentQty').val(currentQty);
            $('#cherryQtyToDecrease').val('');
            $('#cherryNewQty').val('');
            $('#cherryUnit').val('');
            $('#cherrypRate').val('');
            $('#cherryRate').val('');
            $('#cherryPickModal').modal('show');
          });
          
          // Handle form submit
          $('#cherryPickForm').on('submit', function(e) {
            e.preventDefault();
            // Collect data
            const adhocId = $('#cherryAdhocId').val();
            const qtyToDecrease = $('#cherryQtyToDecrease').val();
            const newQty = $('#cherryNewQty').val();
            const unit = $('#cherryUnit').val();
            const prate = $('#cherrypRate').val();
            const rate = $('#cherryRate').val();
            var validated=1;
            $('.alert-box').remove();
            if(qtyToDecrease=='' || qtyToDecrease==0){
              validated=0;
              $('#cherryQtyToDecrease').after('<p class="alert-box alert-danger">Please enter quantity to decrease.</p>');
            }
            if(newQty=='' || newQty==0){
              validated=0;
              $('#cherryNewQty').after('<p class="alert-box alert-danger">Please enter new quantity.</p>');
            }
            if(unit==''){
              validated=0;
              $('#cherryUnit').after('<p class="alert-box alert-danger">Please select unit.</p>');
            }
            if(prate=='' || prate==0){
              validated=0;
              $('#cherrypRate').after('<p class="alert-box alert-danger">Please enter purchase rate.</p>');
            }
            if(rate=='' || rate==0){
              validated=0;
              $('#cherryRate').after('<p class="alert-box alert-danger">Please enter rate.</p>');
            }
            if(validated==1){
              $.ajax({
                url     : "kattegat/ragnar_purchase.php",
                method  : "POST",
                data    : {
                  stc_po_adhoc_cherrypic:1,
                  adhoc_id:adhocId,
                  qtyToDecrease:qtyToDecrease,
                  newQty:newQty,
                  unit:unit,
                  prate:prate,
                  rate:rate
                },
                success : function(response_items){
                  var response=response_items.trim();
                  if(response=="success"){
                    alert("Item Name Updated Successfully.");
                    $('#cherryAdhocId').val('');
                    $('#cherryCurrentQty').val('');
                    $('#cherryQtyToDecrease').val('');
                    $('#cherryNewQty').val('');
                    $('#cherryUnit').val('');
                    $('#cherrypRate').val('');
                    $('#cherryRate').val('');
                    Pagination.loadData(pagenumber);
                  }else{
                    alert("Something went wrong please check and try again.");
                  }
                }
              });
            }else{
              return false;
            }
          });
          
          $('body').delegate('.img-inputbtnshow', 'click', function(e){
            $(this).parent().find('.img-idprateinput').toggle();
            $(this).parent().find('.img-idpercentageinput').toggle();
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
          $('body').delegate('.img-inputpratebtn', 'click', function(e){
            var adhoc_id=$(this).attr('id');
            var rate=$(this).parent().find('.img-idprateinput').val();
            $.ajax({
              url     : "kattegat/ragnar_purchase.php",
              method  : "POST",
              data    : {
                stc_po_adhoc_prateupdate:1,
                adhoc_id:adhoc_id,
                rate:rate
              },
              success : function(response_items){
                var response=response_items.trim();
                if(response=="success"){
                  alert("Purchase rate Updated Successfully.");
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
            var prate=$(this).parent().find('.img-idprateinput').val();
            var percentage=$(this).parent().find('.img-idpercentageinput').val();
            $.ajax({
              url     : "kattegat/ragnar_purchase.php",
              method  : "POST",
              data    : {
                stc_po_adhoc_rateupdate:1,
                adhoc_id:adhoc_id,
                rate:rate,
                prate:prate,
                percentage:percentage
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
                        var rate=parseFloat(row.stc_purchase_product_adhoc_prate);
                        var totalamount=parseFloat(rate) * parseFloat(quantity);
                        totalqnty+=parseFloat(row.stc_purchase_product_adhoc_qty);
                        totalrate+=parseFloat(row.stc_purchase_product_adhoc_prate);
                        total+=parseFloat(row.stc_purchase_product_adhoc_qty) * parseFloat(row.stc_purchase_product_adhoc_prate);
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
          
          $('.stc-summary-filter-find').on('click', function () {
            // Get filter values
            var dateFrom = $('.stc-summary-filterdatefrom').val();
            var dateTo = $('.stc-summary-filterdateto').val();
            var siteName = $('.stc-summary-filtersitename').val();

            // Make AJAX request
            $.ajax({
              url: "kattegat/ragnar_purchase.php", // Replace with your API endpoint
              type: 'GET', // or 'POST' depending on your API
              data: {
                stc_get_customer_ledger: 1,
                dateFrom: dateFrom,  // Ensure these are defined before use
                dateTo: dateTo,
                siteName:siteName
              },
              dataType: 'json',
              success: function (response) {
                // Clear existing table rows
                $('.stc-call-view-summaryledger-row').empty();

                if (response && response.length > 0) {
                  var total=0;
                  $.each(response, function (index, item) {
                    // Format date for better readability
                    let formattedDate = new Date(item.stc_cust_super_requisition_list_date).toLocaleDateString('en-GB');

                    var row = `<tr>
                      <td class="text-center">${index + 1}</td>
                      <td><a href="project_expense_details.php?pro_id=${item.stc_cust_project_id}&dateFrom=${dateFrom}&dateTo=${dateTo}" target="_blank">${item.stc_cust_project_title}</a></td>
                      <td class="text-right">${item.total}</td>
                    </tr>
                    `;
                    total+=parseFloat(item.ltotal);
                    $('.stc-call-view-summaryledger-row').append(row);
                  });
                  var outrow = `
                    <tr>
                      <td colspan="2" class="text-right"> Total : </td>
                      <td class="text-right">${Number(total).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                    </tr>`;
                    $('.stc-call-view-summaryledger-row').append(outrow);
                } else {
                  $('.stc-call-view-summaryledger-row').html('<tr><td colspan="10">No data found</td></tr>');
                }
              },
              error: function (xhr, status, error) {
                // console.error('Error fetching data:', error, xhr.responseText);
                $('.stc-call-view-summaryledger-row').html('<tr><td colspan="10">Error loading data</td></tr>');
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

          var _vikShopModalAdhoc = {};
          var _vikShopModalShopNames = [];
          var _vikShopModalCanAdd = false;

          function vikingsTransferChallanUrl(shopRowId) {
            return 'stc-print-preview-transferchallan.php?transfer_id=' + encodeURIComponent(shopRowId);
          }

          function vikingsShopBranchLabel(r) {
            if (!r) { return ''; }
            var b = (r.branch !== undefined && r.branch !== null) ? String(r.branch).trim() : '';
            if (b) { return b; }
            return (r.shopname !== undefined && r.shopname !== null) ? String(r.shopname).trim() : '';
          }

          function vikingsBuildShopNameSelect(selected, className) {
            var cls = className || 'form-control vik-shop-edit-name';
            var $sel = $('<select/>').addClass(cls);
            $sel.append($('<option/>').val('').text('— Select branch —'));
            (_vikShopModalShopNames || []).forEach(function (name) {
              if (!name) { return; }
              var o = $('<option/>').val(name).text(name);
              if (name === selected) { o.prop('selected', true); }
              $sel.append(o);
            });
            if (selected && _vikShopModalShopNames.indexOf(selected) === -1) {
              $sel.append($('<option/>').val(selected).text(selected).prop('selected', true));
            }
            return $sel;
          }

          function vikingsRenderShopViewRow(r, meta) {
            var rack = (r.stc_rack_name && String(r.stc_rack_name).length) ? r.stc_rack_name : ('#' + (r.rack_id || ''));
            var challanUrl = vikingsTransferChallanUrl(r.id);
            var challanHtml = '<a href="' + challanUrl + '" target="_blank" rel="noopener" class="btn btn-xs btn-default" title="Challan preview" style=" font-size: 21px; padding: 0; margin: 0; "><i class="fa fa-print"></i></a>';

            var $tr = $('<tr/>').attr('data-shop-row-id', r.id).attr('data-rack-id', r.rack_id || 0).data('remarks', r.remarks || '');
            $tr.append($('<td/>').text(vikingsShopBranchLabel(r)));
            $tr.append($('<td class="text-right"/>').text(parseFloat(r.qty || 0).toFixed(2)));
            $tr.append($('<td/>').text(meta.unit || '—'));
            $tr.append($('<td/>').text(rack));
            $tr.append($('<td class="text-center"/>').html(challanHtml));
            $tr.append($('<td class="text-center"/>').html('<button type="button" class="btn btn-xs btn-primary vik-shop-row-edit" title="Edit"><i class="fa fa-edit"></i></button>'));
            $tr.append($('<td class="text-center"/>').html('<button type="button" class="btn btn-xs btn-danger vik-shop-row-delete" title="Delete"><i class="fa fa-trash"></i></button>'));
            $tr.append($('<td/>'));
            return $tr;
          }

          function vikingsRenderShopEditRow(r, meta) {
            var $tr = $('<tr/>').addClass('vik-shop-row-editing').attr('data-shop-row-id', r.id).data('remarks', r.remarks || '');
            $tr.append($('<td/>').append(vikingsBuildShopNameSelect(vikingsShopBranchLabel(r))));
            $tr.append($('<td/>').html('<input type="number" class="form-control vik-shop-edit-qty text-right" step="0.01" value="' + parseFloat(r.qty || 0) + '">'));
            $tr.append($('<td/>').text(meta.unit || '—'));
            $tr.append($('<td/>').html('<input type="number" class="form-control vik-shop-edit-rack" value="' + (parseInt(r.rack_id, 10) || 0) + '">'));
            $tr.append($('<td class="text-center text-muted"/>').text('—'));
            $tr.append($('<td colspan="2" class="text-center"/>').html('<button type="button" class="btn btn-xs btn-success vik-shop-row-save" style="margin-right:4px;">Save</button><button type="button" class="btn btn-xs btn-default vik-shop-row-cancel">Cancel</button>'));
            $tr.append($('<td/>'));
            return $tr;
          }

          function vikingsRenderShopNewRow(meta) {
            var $tr = $('<tr/>').addClass('vik-shop-row-editing vik-shop-row-new');
            $tr.append($('<td/>').append(vikingsBuildShopNameSelect('', 'form-control vik-shop-new-name')));
            $tr.append($('<td/>').html('<input type="number" class="form-control vik-shop-new-qty text-right" step="0.01" value="0">'));
            $tr.append($('<td/>').text(meta.unit || '—'));
            $tr.append($('<td/>').html('<input type="number" class="form-control vik-shop-new-rack" value="0">'));
            $tr.append($('<td class="text-center text-muted"/>').text('—'));
            $tr.append($('<td colspan="2" class="text-center"/>').html('<button type="button" class="btn btn-xs btn-success vik-shop-row-save-new" style="margin-right:4px;">Save</button><button type="button" class="btn btn-xs btn-default vik-shop-row-cancel">Cancel</button>'));
            $tr.append($('<td/>'));
            return $tr;
          }

          function loadVikingsShopQtyModal(adhocId) {
            var $tbody = $('#vikings-shop-qty-table-body');
            $tbody.html('<tr><td colspan="8" class="text-center text-muted">Loading…</td></tr>');
            $.ajax({
              url: 'kattegat/ragnar_purchase.php',
              type: 'POST',
              data: { stc_poadhoc_shops_get: 1, adhoc_id: adhocId },
              dataType: 'json',
              success: function (res) {
                if (!res || !res.success) {
                  $tbody.html('<tr><td colspan="8" class="text-center text-danger">' + (res && res.message ? res.message : 'Load failed') + '</td></tr>');
                  return;
                }
                _vikShopModalShopNames = res.shop_names || [];
                _vikShopModalAdhoc = res.adhoc || {};
                var a = _vikShopModalAdhoc;

                $('#vikings-shop-modal-product-name').text(a.product_name || '—');
                $('#vikings-shop-modal-purchased-qty').text(parseFloat(a.purchased_qty || 0).toFixed(2));
                $('#vikings-shop-modal-unit-suffix').text(a.unit ? ' ' + a.unit : '');
                $('#vikings-shop-modal-allocated-qty').text(parseFloat(a.allocated_qty || 0).toFixed(2));
                var bal = parseFloat(a.balanced_qty || 0);
                var $balEl = $('#vikings-shop-modal-balanced-qty');
                $balEl.text(bal.toFixed(2));
                $balEl.removeClass('text-info text-danger text-muted text-warning');
                if (bal < -1e-6) {
                  $balEl.addClass('text-danger');
                } else if (bal > 1e-6) {
                  $balEl.addClass('text-info');
                } else {
                  $balEl.addClass('text-muted');
                }

                _vikShopModalCanAdd = bal > 1e-6;
                var $addTh = $('#vikings-shop-qty-col-add-header');
                if (_vikShopModalCanAdd) {
                  $addTh.html('<i class="fa fa-plus text-success"></i>').attr('title', 'Add branch');
                } else {
                  $addTh.html('<span class="text-muted">—</span>').attr('title', 'No balance left to allocate');
                }

                $tbody.empty();
                $('#vikings-shop-qty-add-row-slot').remove();

                if (!res.data || res.data.length === 0) {
                  var emptyMsg = _vikShopModalCanAdd
                    ? 'No branch rows yet. Use + to add.'
                    : 'No branch rows. No balance available to allocate.';
                  $tbody.append('<tr class="vik-shop-qty-empty-placeholder"><td colspan="8" class="text-center text-muted">' + emptyMsg + '</td></tr>');
                } else {
                  res.data.forEach(function (r) {
                    $tbody.append(vikingsRenderShopViewRow(r, a));
                  });
                }
                var $footer;
                if (_vikShopModalCanAdd) {
                  $footer = $('<tr id="vikings-shop-qty-add-row-slot" class="vik-shop-qty-footer-row"><td colspan="7"></td><td class="text-center"><button type="button" class="btn btn-xs btn-success vik-shop-qty-add-row" title="Add branch"><i class="fa fa-plus"></i></button></td></tr>');
                } else {
                  $footer = $('<tr id="vikings-shop-qty-add-row-slot" class="vik-shop-qty-footer-row"><td colspan="8" class="text-center text-muted small">No balance left to allocate. Reduce quantities in existing rows or increase purchased qty on the PO line.</td></tr>');
                }
                $tbody.append($footer);
              },
              error: function () {
                $tbody.html('<tr><td colspan="8" class="text-center text-danger">Failed to load</td></tr>');
              }
            });
          }

          function openVikingsShopQtyModal(adhocId) {
            $('#vikings_shop_qty_adhoc_id').val(adhocId);
            $('#vikings-shop-qty-modal-title-adhoc').text(adhocId);
            $('#vikings-shop-qty-modal').modal('show');
            loadVikingsShopQtyModal(adhocId);
          }

          $('body').delegate('.shop-qty-cell-clickable', 'click', function (e) {
            e.preventDefault();
            var adhocId = $(this).data('adhoc-id');
            if (adhocId) { openVikingsShopQtyModal(adhocId); }
          });

          $('body').delegate('.input-shop-item', 'click', function(e){
            e.preventDefault();
            var id=$(this).attr('id');
            $('.stc-poadhocshop-id').val(id);
            if (id) { openVikingsShopQtyModal(id); }
          });

          $('body').on('click', '#vikings-shop-qty-modal .vik-shop-row-edit', function () {
            if ($('#vikings-shop-qty-table-body tr.vik-shop-row-editing').length) { return; }
            var $tr = $(this).closest('tr');
            var id = $tr.attr('data-shop-row-id');
            var label = $tr.find('td').eq(0).text();
            var r = {
              id: id,
              branch: label,
              shopname: label,
              qty: parseFloat($tr.find('td').eq(1).text()) || 0,
              rack_id: parseInt($tr.attr('data-rack-id'), 10) || 0,
              remarks: $tr.data('remarks') || ''
            };
            $tr.replaceWith(vikingsRenderShopEditRow(r, _vikShopModalAdhoc));
          });

          $('body').on('click', '#vikings-shop-qty-modal .vik-shop-row-cancel', function () {
            loadVikingsShopQtyModal($('#vikings_shop_qty_adhoc_id').val());
          });

          $('body').on('click', '#vikings-shop-qty-modal .vik-shop-row-save', function () {
            var $tr = $(this).closest('tr');
            var adhocId = $('#vikings_shop_qty_adhoc_id').val();
            $.ajax({
              url: 'kattegat/ragnar_purchase.php',
              type: 'POST',
              dataType: 'json',
              data: {
                stc_poadhoc_shop_modal_update: 1,
                id: $tr.attr('data-shop-row-id'),
                adhoc_id: adhocId,
                branch: $tr.find('.vik-shop-edit-name').val(),
                shopname: $tr.find('.vik-shop-edit-name').val(),
                qty: $tr.find('.vik-shop-edit-qty').val(),
                rack_id: $tr.find('.vik-shop-edit-rack').val(),
                remarks: $tr.data('remarks') || ''
              },
              success: function (res) {
                if (res && res.success) {
                  alert(res.message || 'Updated');
                  loadVikingsShopQtyModal(adhocId);
                  Pagination.loadData(pagenumber);
                } else {
                  alert((res && res.message) ? res.message : 'Update failed');
                }
              },
              error: function () { alert('Update failed'); }
            });
          });

          $('body').on('click', '#vikings-shop-qty-modal .vik-shop-row-save-new', function () {
            var $tr = $(this).closest('tr');
            var adhocId = $('#vikings_shop_qty_adhoc_id').val();
            $.ajax({
              url: 'kattegat/ragnar_purchase.php',
              type: 'POST',
              dataType: 'json',
              data: {
                stc_poadhoc_shop_modal_save: 1,
                adhoc_id: adhocId,
                branch: $tr.find('.vik-shop-new-name').val(),
                shopname: $tr.find('.vik-shop-new-name').val(),
                qty: $tr.find('.vik-shop-new-qty').val(),
                rack_id: $tr.find('.vik-shop-new-rack').val(),
                remarks: ''
              },
              success: function (res) {
                if (res && res.success) {
                  alert(res.message || 'Saved');
                  loadVikingsShopQtyModal(adhocId);
                  Pagination.loadData(pagenumber);
                } else {
                  alert((res && res.message) ? res.message : 'Save failed');
                }
              },
              error: function () { alert('Save failed'); }
            });
          });

          $('body').on('click', '#vikings-shop-qty-modal .vik-shop-row-delete', function () {
            if (!window.confirm('Delete this branch row?')) { return; }
            var $tr = $(this).closest('tr');
            var adhocId = $('#vikings_shop_qty_adhoc_id').val();
            $.ajax({
              url: 'kattegat/ragnar_purchase.php',
              type: 'POST',
              dataType: 'json',
              data: {
                stc_poadhoc_shop_modal_delete: 1,
                id: $tr.attr('data-shop-row-id'),
                adhoc_id: adhocId
              },
              success: function (res) {
                if (res && res.success) {
                  alert(res.message || 'Deleted');
                  loadVikingsShopQtyModal(adhocId);
                  Pagination.loadData(pagenumber);
                } else {
                  alert((res && res.message) ? res.message : 'Delete failed');
                }
              },
              error: function () { alert('Delete failed'); }
            });
          });

          $('body').on('click', '#vikings-shop-qty-modal .vik-shop-qty-add-row', function () {
            if (!_vikShopModalCanAdd) { return; }
            if ($('#vikings-shop-qty-table-body tr.vik-shop-row-editing').length) { return; }
            $('.vik-shop-qty-empty-placeholder').remove();
            $('#vikings-shop-qty-add-row-slot').before(vikingsRenderShopNewRow(_vikShopModalAdhoc));
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
          

          $('body').delegate('.change-adhoc-status', 'click', function(e){
            var id=$(this).attr('data-id');
            var status=$(this).attr('data-status');
            if(confirm("Are you sure want to approve status of this Item?")){
              $.ajax({
                url: "kattegat/ragnar_purchase.php", // Replace with your API endpoint
                type: 'POST', // or 'POST' depending on your API
                data: {
                  stc_changeapprovestatus: 1,
                  id: id,
                  status:status
                },
                dataType: 'json',
                success: function (response) {
                  alert("Item updated successfully.");
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
          var inv_type='warehouse';
          function parseInventoryResponse(response) {
              if (response == null) { return null; }
              if (typeof response === 'object') { return response; }
              try { return JSON.parse(response); } catch (e) { return null; }
          }
          function loadInventories(page = 1, search = '', inv_type = 'warehouse') {
              page = parseInt(page, 10);
              if (isNaN(page) || page < 1) { page = 1; }
              $.ajax({
                   url: "kattegat/ragnar_purchase.php", // Replace with your API endpoint
                  method: 'POST',
                  dataType: 'json',
                  data: {
                      stc_getinventory: 1,
                      page: page,
                      searchKey: search,
                      inv_type: inv_type
                  },
                  success: function(response) {
                      var res = parseInventoryResponse(response);
                      if (!res) { return; }
                      if(inv_type=="warehouse"){
                        $('#dataContainer').html(res.html);
                        $('#paginations').html(res.pagination);
                      } else {
                        var $branchTab = $('.InvTypeBtns').filter(function () {
                          return $(this).attr('type') === inv_type;
                        }).first();
                        var dc = $branchTab.attr('data-dc');
                        var pg = $branchTab.attr('data-pg');
                        if (dc && pg) {
                          $('#' + dc).html(res.html);
                          $('#' + pg).html(res.pagination);
                        }
                      }
                  }
              });
          }

          // Initial Load
          loadInventories();

          // Search
          $(document).on('keyup', '.searchKey', function () {
              const search = $(this).val();
              $('.searchKey').val(search);
              inv_type = $('.InvTypeBtns.active').attr('type') || 'warehouse';
              if (search.length >= 3 || search.length === 0) {
                  loadInventories(1, search, inv_type);
              }
          });

          // Pagination: pass the clicked page (previously always used 1). Resolve search + inv_type from the tab pane that contains this pagination.
          $(document).on('click', '.pagination_link', function (e) {
              e.preventDefault();
              var page = $(this).data('page');
              if (page === undefined || page === null || page === '') {
                  page = parseInt($(this).attr('data-page'), 10);
              }
              var $pane = $(this).closest('.tab-pane');
              var search = '';
              var inv_for_load = 'warehouse';
              if ($pane.length) {
                  search = $pane.find('.searchKey').first().val() || '';
                  var pid = $pane.attr('id');
                  if (pid) {
                      var $btn = $('.InvTypeBtns[href="#' + pid + '"]');
                      if ($btn.length) {
                          inv_for_load = $btn.attr('type') || 'warehouse';
                      }
                  }
              } else {
                  search = $('.searchKey').first().val() || '';
                  inv_for_load = $('.InvTypeBtns.active').attr('type') || 'warehouse';
              }
              inv_type = inv_for_load;
              loadInventories(page, search, inv_for_load);
          });
          $(document).on('click', '.InvTypeBtns', function () {
              inv_type = $(this).attr('type') || 'warehouse';
              var href = $(this).attr('href');
              var search = '';
              if (href && $(href).length) {
                  search = $(href).find('.searchKey').first().val() || '';
              } else {
                  search = $('.searchKey').first().val() || '';
              }
              loadInventories(1, search, inv_type);
          });
          
          $(document).on('click', '.stc-adhocpendingfilter-find', function () {
              const datefrom = $('.stc-pending-filterdatefrom').val();
              const dateto = $('.stc-pending-filterdateto').val();
              $.ajax({
                   url: "kattegat/ragnar_order.php", // Replace with your API endpoint
                  method: 'POST',
                  data: {
                      stc_getpendingadhoc: 1,
                      datefrom: datefrom,
                      dateto: dateto
                  },
                  dataType: 'json',
                  success: function(response) {
                      var response=response.data;
                      var data='';
                      var totalQuantity = 0;
                      for(var i=0;i<=response.length-1;i++){
                        var statusbtn='';
                        totalQuantity += parseFloat(response[i].stc_cust_super_requisition_list_items_rec_recqty || 0) || 0;
                        data+='<tr>';
                        data+='<td>'+(i+1)+'</td>';
                        data+='<td class="text-center">'+response[i].stc_requisition_combiner_req_comb_id+'<br>'+response[i].stc_cust_super_requisition_list_id+'</td>';
                        data+='<td class="text-center">'+response[i].stc_cust_super_requisition_list_items_rec_date+'</td>';
                        data+='<td class="text-center">'+response[i].stc_cust_project_title+'</td>';
                        data+='<td class="text-center">'+response[i].stc_cust_pro_supervisor_fullname+' '+response[i].stc_cust_pro_supervisor_contact+'</td>';
                        data+='<td><input type="number" class="form-control adhocId-UpdatenumberBox" placeholder="Enter Adhoc ID"><button id="' + response[i].stc_cust_super_requisition_list_items_rec_id + '" class="form-control btn btn-success adhocId-UpdatenumberBoxBtn">Update</button>'+response[i].stc_cust_super_requisition_list_items_rec_list_poaid+'</td>';
                        data+='<td>'+response[i].stc_cust_super_requisition_list_items_title+'</td>';
                        data+='<td class="text-center">'+response[i].stc_cust_super_requisition_list_items_unit+'</td>';
                        data+='<td class="text-right">'+response[i].stc_cust_super_requisition_list_items_rec_recqty+'</td>';
                        data+='</tr>';
                      }
                      data+='<tr>';
                      data+='<td colspan="8"><b>Total Quantity:</b> '+totalQuantity.toFixed(2)+'</td>';
                      data+='</tr>';
                      $('.stc-call-view-poadhocpending-row').html(data);
                  }
              });
          });
          $(document).on('click', '.adhocId-UpdatenumberBoxBtn', function () {
            var repid=$(this).attr('id');
            var adhoc_id=$(this).parent().find('.adhocId-UpdatenumberBox').val();
            if(repid>0){
              $.ajax({
                url       : "kattegat/ragnar_order.php",
                method    : 'POST',
                data      : {
                  update_adhoc_id_rec:1,
                  adhoc_id:adhoc_id,
                  repid:repid
                },
                dataType  : 'JSON',
                success:function(req){
                  // console.log(req);
                  if(req=="Success"){
                    alert("Updated successfully!! Please reload modal to see changes.");
                    $('.stc-adhocpendingfilter-find').trigger('click');
                  }else{
                    alert(req);
                  }
                }
              });
            }else{
              alert("Please enter valid quantity.");
            }
          });

          var repid=0;
          var ittAdhocQty = 1;
          $(document).on('click', '.itt-create', function () {
            repid = $(this).attr('id');
            var rem = parseInt($(this).attr('data-remaining'), 10);
            if (isNaN(rem) || rem < 1) {
              var q = parseFloat($(this).attr('data-adhoc-qty'));
              rem = (isNaN(q) || q < 1) ? 1 : Math.floor(q);
            }
            ittAdhocQty = rem;
            if (ittAdhocQty > 500) { ittAdhocQty = 500; }
            $('.itt-qty-badge').text(ittAdhocQty);
            var pname = $(this).attr('data-product-name') || '';
            var psrc = $(this).attr('data-purchase-source') || '';
            $('.itt-itemdescription').val(pname);
            $('.itt-purdetails').val(psrc);

            // Load existing tool rows for this Adhoc ID
            $('.itt-existing-tools-wrap').show();
            $('.itt-existing-tools-body').html('<tr><td colspan="10" class="text-center text-muted">Loading...</td></tr>');
            $('.itt-existing-count').text('0');
            $.ajax({
              url: "kattegat/ragnar_purchase.php",
              method: "POST",
              dataType: "json",
              data: { stc_get_tooldetails_by_poa: 1, poa_id: repid },
              success: function (res) {
                if (res && res.reload) {
                  window.location.reload();
                  return;
                }
                if (!res || res.success !== true) {
                  $('.itt-existing-tools-body').html('<tr><td colspan="10" class="text-center text-danger">Failed to load tools.</td></tr>');
                  return;
                }
                var list = res.data || [];
                $('.itt-existing-count').text(String(list.length));
                if (!list.length) {
                  $('.itt-existing-tools-body').html('<tr><td colspan="10" class="text-center text-muted">No existing tools found for this Adhoc ID.</td></tr>');
                  return;
                }
                var rows = '';
                for (var i = 0; i < list.length; i++) {
                  var it = list[i] || {};
                  var inv = (it.taxinvono ? String(it.taxinvono) : '-');
                  var invDate = (it.taxinvodate ? String(it.taxinvodate) : '-');
                  var created = it.created_date ? String(it.created_date) : '-';
                  rows += '<tr>' +
                    '<td class="text-center">' + (i + 1) + '</td>' +
                    '<td><span class="badge badge-primary">' + escapeHtml(it.unique_id || '-') + '</span><div class="small text-muted">' + escapeHtml(it.itemdescription || '') + '</div></td>' +
                    '<td>' + escapeHtml(it.machinesrno || '-') + '</td>' +
                    '<td>' + escapeHtml(it.make || '-') + '</td>' +
                    '<td>' + escapeHtml(it.tooltype || '-') + '</td>' +
                    '<td>' + escapeHtml(it.warranty || '-') + '</td>' +
                    '<td>' + escapeHtml(inv) + '</td>' +
                    '<td class="text-center">' + escapeHtml(invDate) + '</td>' +
                    '<td>' + escapeHtml(it.remarks || '-') + '</td>' +
                    '<td class="text-center"><div class="small">' + escapeHtml(created) + '</div><div class="small text-muted">' + escapeHtml(it.created_by_name || '') + '</div></td>' +
                  '</tr>';
                }
                $('.itt-existing-tools-body').html(rows);
              },
              error: function () {
                $('.itt-existing-tools-body').html('<tr><td colspan="10" class="text-center text-danger">Failed to load tools.</td></tr>');
              }
            });
          });

          // save dispatch
          $('body').delegate('.itt-save', 'click', function (e) {
            e.preventDefault();
            var itemdescription = $('.itt-itemdescription').val();
            var machineslno = $('.itt-machinesrno').val();
            var make = $('.itt-make').val();
            var type = $('.itt-type').val();
            var warranty = $('.itt-warranty').val();
            var purdetails = $('.itt-purdetails').val();
            var tinnumber = $('.itt-tinnumber').val();
            var tindate = $('.itt-tindate').val();
            var remarks = $('.itt-remarks').val();
            if (itemdescription != '') {
              var data = {
                save_tool_tracker: 1,
                unique: '',
                itemdescription: itemdescription,
                machineslno: machineslno,
                make: make,
                type: type,
                warranty: warranty,
                purdetails: purdetails,
                tinnumber: tinnumber,
                tindate: tindate,
                remarks: remarks,
                repid: repid,
                qty: ittAdhocQty
              };
              $.ajax({
                url       : "kattegat/ragnar_purchase.php",
                method: "POST",
                data: data,
                success: function (response) {
                  var obj_response = response.trim();
                  if (obj_response == "yes") {
                    var msg = (ittAdhocQty > 1)
                      ? ittAdhocQty + " tool records saved with consecutive GTT numbers (same details). You can edit Machine SR No. per row in Tool Tracker if needed."
                      : "Record saved successfully. GTT number assigned automatically. You can edit Machine SR No. in Tool Tracker if needed.";
                    alert(msg);
                    Pagination.loadData(pagenumber);
                  } else if (obj_response == "duplicate") {
                    alert("A GTT number collision occurred. Please try again.");
                  } else if (obj_response == "reload") {
                    window.location.reload();
                  } else if (obj_response == "empty") {
                    alert("Please fill complete details.");
                  } else if (obj_response == "no") {
                    alert("Something went wrong. Record not updated");
                  } else if (obj_response == "complete") {
                    alert("Tool Tracker already has the maximum rows for this ad-hoc quantity. Refresh the list.");
                    Pagination.loadData(pagenumber);
                  }
                }
              });
            } else {
              alert("Please check Required fields.");
            }
          });

          // AJAX click event for updating adhoc ID
          $('body').delegate('.reset-items', 'click', function(e){
              if(confirm("Are you sure want to reset items?")){
                $.ajax({
                    url: "kattegat/ragnar_purchase.php",
                    method: "POST",
                    data: {
                        reset_items: 1
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $(this).prop('disabled', true).text('Updating...');
                    },
                    success: function(response) {
                        if(response === 'reload') {
                            alert('Session expired. Please reload the page.');
                            window.location.reload();
                        } else if(response === 'Success') {
                            alert('Adhoc ID updated successfully');
                            location.reload();
                        } else {
                            alert('Update Failed: ' + response);
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('AJAX Error: Failed to update adhoc ID - ' + error);
                    },
                    complete: function() {
                        $('.update-adhoc-id-btn').prop('disabled', false).text('Update Adhoc ID');
                    }
                });
              }
          });

          
        });

        // Function to calculate sale rate based on purchase rate and percentage
        function calculateSaleRate(percentageInput) {
            var row = percentageInput.closest('tr');
            var purchaseRateInput = row.querySelector('.purchase-rate-input');
            var saleRateInput = row.querySelector('.sale-rate-display');
            
            var purchaseRate = parseFloat(purchaseRateInput.value) || 0;
            var percentage = parseFloat(percentageInput.value) || 0;
            
            if (purchaseRate > 0 && percentage >= 0) {
                var saleRate = purchaseRate + (purchaseRate * percentage / 100);
                saleRateInput.value = saleRate.toFixed(2);
            } else {
                saleRateInput.value = '0.00';
            }
        }

        // Function to calculate sale rate in edit form
        function calculateEditSaleRate() {
            var purchaseRate = parseFloat(document.getElementById('cherrypRate').value) || 0;
            var percentage = parseFloat(document.getElementById('cherryPercentage').value) || 0;
            var saleRateInput = document.getElementById('cherryRate');
            
            if (purchaseRate > 0 && percentage >= 0) {
                var saleRate = purchaseRate + (purchaseRate * percentage / 100);
                saleRateInput.value = saleRate.toFixed(2);
            } else {
                saleRateInput.value = '0.00';
            }
        }

        // Function to calculate sale rate for inline editing
        function calculateInlineSaleRate(inputElement) {
            var row = inputElement.closest('td');
            var purchaseRateInput = row.querySelector('.img-idprateinput');
            var percentageInput = row.querySelector('.img-idpercentageinput');
            var saleRateInput = row.querySelector('.img-idrateinput');
            
            var purchaseRate = parseFloat(purchaseRateInput.value) || 0;
            var percentage = parseFloat(percentageInput.value) || 0;
            
            if (purchaseRate > 0 && percentage >= 0) {
                var saleRate = purchaseRate + (purchaseRate * percentage / 100);
                saleRateInput.value = saleRate.toFixed(2);
            } else {
                saleRateInput.value = '0.00';
            }
        }
        
        // Show/hide dropdown based on checkbox selection (using event delegation for dynamic content)
        $(document).on('change', '.common_selector', function() {
            var checkedBoxes = $('.common_selector:checked');
            
            if (checkedBoxes.length > 0) {
                // Remove any existing fixed dropdown
                $('.fixed-status-dropdown').remove();
                
                // Create fixed dropdown in top right corner
                var dropdownHtml = '<div class="fixed-status-dropdown" style="position: fixed; top: 60px; right: 20px; z-index: 9999; background: white; padding: 15px; border: 1px solid #ddd; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">' +
                    '<h6 style="margin-bottom: 10px;">Bulk Actions</h6>' +
                    '<div class="dropdown" style="display: inline-block;">' +
                    '<button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="bulkStatusDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' +
                    'Change Status' +
                    '</button>' +
                    '<div class="dropdown-menu" aria-labelledby="bulkStatusDropdown">' +
                    '<a class="dropdown-item bulk-status-change" href="javascript:void(0)" data-status="1">Accept All Selected</a>' +
                    '<a class="dropdown-item bulk-status-change" href="javascript:void(0)" data-status="4">Approve All Selected</a>' +
                    '<a class="dropdown-item bulk-status-change" href="javascript:void(0)" data-status="5">Reject All Selected</a>' +
                    '</div>' +
                    '</div>' +
                    '<button class="btn btn-danger btn-sm ml-2" id="closeBulkActions" style="margin-left: 10px;">Close</button>' +
                    '</div>';
                
                $('body').append(dropdownHtml);
            } else {
                // Hide fixed dropdown when no checkboxes are checked
                $('.fixed-status-dropdown').remove();
            }
        });
        
        // Close bulk actions dropdown
        $(document).on('click', '#closeBulkActions', function() {
            $('.fixed-status-dropdown').remove();
        });
        
        // Handle Select All checkbox
        $(document).on('change', '#selectAllCheckbox', function() {
            var isChecked = $(this).is(':checked');
            $('.common_selector').prop('checked', isChecked).trigger('change');
        });
        
        // Handle individual checkbox changes to update Select All state
        $(document).on('change', '.common_selector', function() {
            var totalCheckboxes = $('.common_selector').length;
            var checkedCheckboxes = $('.common_selector:checked').length;
            
            if (checkedCheckboxes === 0) {
                $('#selectAllCheckbox').prop('checked', false).prop('indeterminate', false);
            } else if (checkedCheckboxes === totalCheckboxes) {
                $('#selectAllCheckbox').prop('checked', true).prop('indeterminate', false);
            } else {
                $('#selectAllCheckbox').prop('checked', false).prop('indeterminate', true);
            }
        });
        
        // Handle bulk status change
        $(document).on('click', '.bulk-status-change', function() {
            var status = $(this).data('status');
            var checkedBoxes = $('.common_selector:checked');
            var itemIds = [];
            
            // Collect all checked item IDs
            checkedBoxes.each(function() {
                itemIds.push($(this).val());
            });
            
            if (itemIds.length > 0) {
                var statusText = status == 4 ? 'Approve' : 'Reject';
                if (confirm('Are you sure you want to ' + statusText.toLowerCase() + ' ' + itemIds.length + ' selected items?')) {
                    // Show loading indicator
                    $('.fixed-status-dropdown').html('<div style="text-align: center; padding: 20px;"><i class="fa fa-spinner fa-spin"></i> Updating items...</div>');
                    
                    var completedCount = 0;
                    var totalItems = itemIds.length;
                    var failedItems = [];
                    
                    // Process each item in loop
                    itemIds.forEach(function(itemId, index) {
                        $.ajax({
                            url: "kattegat/ragnar_purchase.php",
                            type: 'POST',
                            data: {
                                stc_changeapprovestatus: 1,
                                id: itemId,
                                status: status
                            },
                            dataType: 'json',
                            success: function (response) {
                                completedCount++;
                                console.log('Item ' + itemId + ' updated successfully');
                                
                                // Check if all items are processed
                                if (completedCount === totalItems) {
                                    // All items processed, refresh data
                                    if (failedItems.length === 0) {
                                        alert('All ' + totalItems + ' items updated successfully!');
                                    } else {
                                        alert('Updated ' + (totalItems - failedItems.length) + ' items successfully. Failed: ' + failedItems.join(', '));
                                    }
                                    
                                    // Remove the fixed dropdown
                                    $('.fixed-status-dropdown').remove();
                                    
                                    // Refresh the data
                                    if (typeof Pagination !== 'undefined' && Pagination.loadData) {
                                        Pagination.loadData(1); // Reload first page
                                    } else {
                                        // Fallback: reload the page
                                        location.reload();
                                    }
                                }
                            },
                            error: function (xhr, status, error) {
                                completedCount++;
                                failedItems.push(itemId);
                                console.log('Failed to update item ' + itemId + ': ' + error);
                                
                                // Check if all items are processed
                                if (completedCount === totalItems) {
                                    if (failedItems.length === 0) {
                                        alert('All ' + totalItems + ' items updated successfully!');
                                    } else {
                                        alert('Updated ' + (totalItems - failedItems.length) + ' items successfully. Failed: ' + failedItems.join(', '));
                                    }
                                    
                                    // Remove the fixed dropdown
                                    $('.fixed-status-dropdown').remove();
                                    
                                    // Refresh the data
                                    if (typeof Pagination !== 'undefined' && Pagination.loadData) {
                                        Pagination.loadData(1); // Reload first page
                                    } else {
                                        // Fallback: reload the page
                                        location.reload();
                                    }
                                }
                            }
                        });
                    });
                }
            }
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
                      >Item Quantity
                    </h5>
                    <input
                      id="stcpoadhoceitemqty"
                      type="text"
                      placeholder="Edit Quantity"
                      class="form-control validate"
                    />
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
                    <h5
                      for=""
                      >Remarks
                    </h5>
                    <textarea class="form-control" id="stcpoadhoceitemremarks"></textarea>
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
                   <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#dispatchDetailsTab">Dispatch Details</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#additionalInfoTab">GLD Dispatch</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <!-- First Tab - Dispatch Details -->
                    <div id="dispatchDetailsTab" class="tab-pane active">
                        <div class="row mt-3">
                            <div class="col-xl-12 col-md-12 col-sm-12">
                                <div class="card-border mb-3 card card-body border-success">
                                    <table class="table table-bordered table-reponsive">
                                        <thead>
                                            <tr>
                                                <th>Sl No</th>
                                                <th>Requisition Number</th>
                                                <th>Date</th>
                                                <th>Project Name</th>
                                                <th>User Name</th>
                                                <th>Dispatched Quantity</th>
                                                <th>Unit</th>
                                            </tr>
                                        </thead>
                                        <tbody class="show-dispatchdetails-table">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Second Tab - Additional Information -->
                    <div id="additionalInfoTab" class="tab-pane fade">
                        <div class="row mt-3">
                            <div class="col-xl-12 col-md-12 col-sm-12">
                                <div class="card-border mb-3 card card-body border-success">
                                    <table class="table table-bordered table-reponsive">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Sl No</th>
                                                <th class="text-center">Date</th>
                                                <th class="text-center">Bill Number</th>
                                                <th class="text-center">Customer Name</th>
                                                <th class="text-center">Qty</th>
                                                <th class="text-center">Unit</th>
                                                <th class="text-center">Rate</th>
                                                <th class="text-center">Total</th>
                                                <th class="text-center">Created BY</th>
                                            </tr>
                                        </thead>
                                        <tbody class="show-glddispatchdetails-table">
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

<!-- Advanced search modal (moved here to avoid overflow/stacking issues) -->
<div class="modal fade bd-modal-adhoc-advanced-search" tabindex="-1" role="dialog" aria-labelledby="stcAdvSearchTitle" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <div class="stc-adv-header">
          <div class="stc-adv-header-icon" aria-hidden="true">
            <i class="fa fa-filter"></i>
          </div>
          <div>
            <p class="stc-adv-header-title" id="stcAdvSearchTitle">Advanced search</p>
            <div class="stc-adv-subtitle">Narrow results using more filters, then apply search.</div>
          </div>
        </div>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <div class="stc-adv-grid">
          <div class="stc-adv-col">
            <div class="stc-adv-field">
              <div class="stc-adv-label">
                <i class="fa fa-hashtag" aria-hidden="true"></i>
                <p class="stc-adv-field-label" id="stc-adv-lbl-adhocid">Adhoc ID</p>
              </div>
              <input type="text" id="stc-adv-adhoc-id" class="form-control" placeholder="Type adhoc id…" aria-labelledby="stc-adv-lbl-adhocid">
            </div>
          </div>

          <div class="stc-adv-col">
            <div class="stc-adv-field">
              <div class="stc-adv-label">
                <i class="fa fa-barcode" aria-hidden="true"></i>
                <p class="stc-adv-field-label" id="stc-adv-lbl-productid">Product ID</p>
              </div>
              <input type="text" id="stc-adv-product-id" class="form-control" placeholder="Type product id…" aria-labelledby="stc-adv-lbl-productid">
            </div>
          </div>

          <div class="stc-adv-col">
            <div class="stc-adv-field">
              <div class="stc-adv-label">
                <i class="fa fa-tag" aria-hidden="true"></i>
                <p class="stc-adv-field-label" id="stc-adv-lbl-productname">Product name</p>
              </div>
              <input type="text" id="stc-adv-product-name" class="form-control" placeholder="Type product name…" aria-labelledby="stc-adv-lbl-productname">
            </div>
          </div>

          <div class="stc-adv-col">
            <div class="stc-adv-field">
              <div class="stc-adv-label">
                <i class="fa fa-pencil" aria-hidden="true"></i>
                <p class="stc-adv-field-label" id="stc-adv-lbl-adhocname">Adhoc name</p>
              </div>
              <input type="text" id="stc-adv-adhoc-name" class="form-control" placeholder="Type adhoc item name…" aria-labelledby="stc-adv-lbl-adhocname">
            </div>
          </div>

          <div class="stc-adv-col">
            <div class="stc-adv-field">
              <div class="stc-adv-label">
                <i class="fa fa-th-large" aria-hidden="true"></i>
                <p class="stc-adv-field-label" id="stc-adv-lbl-rack">Rack</p>
              </div>
              <div class="stc-typeahead-wrap">
                <input type="text" id="stc-poa-by-rack" class="form-control tc-poa-searchbyrack" placeholder="Type rack…" aria-labelledby="stc-adv-lbl-rack" autocomplete="off">
                <ul class="stc-typeahead-list" id="stc-typeahead-rack"></ul>
              </div>
            </div>
          </div>

          <div class="stc-adv-col">
            <div class="stc-adv-field">
              <div class="stc-adv-label">
                <i class="fa fa-map-marker" aria-hidden="true"></i>
                <p class="stc-adv-field-label" id="stc-adv-lbl-location">Source / destination (Location)</p>
              </div>
              <div class="stc-typeahead-wrap">
                <input type="text" id="tc-poa-searchbydourcedestination" class="form-control" placeholder="Type location…" aria-labelledby="stc-adv-lbl-location" autocomplete="off">
                <ul class="stc-typeahead-list" id="stc-typeahead-location"></ul>
              </div>
            </div>
          </div>

          <div class="stc-adv-col">
            <div class="stc-adv-field">
              <div class="stc-adv-label">
                <i class="fa fa-info-circle" aria-hidden="true"></i>
                <p class="stc-adv-field-label" id="stc-adv-lbl-status">Status</p>
              </div>
              <select id="stc-poa-status" class="custom-select form-control stc-po-status-in" aria-labelledby="stc-adv-lbl-status">
                <option value="NA">Select Status</option>
                <option value="1">Stock</option>
                <option value="2">Dispatched</option>
                <option value="3">Pending</option>
                <option value="4">Approved</option>
                <option value="5">Rejected</option>
              </select>
            </div>
          </div>

          <div class="stc-adv-col">
            <div class="stc-adv-field">
              <div class="stc-adv-label">
                <i class="fa fa-user" aria-hidden="true"></i>
                <p class="stc-adv-field-label" id="stc-adv-lbl-receivedby">Received by</p>
              </div>
              <input type="text" id="stc-adv-received-by" class="form-control" placeholder="Type received by…" aria-labelledby="stc-adv-lbl-receivedby">
            </div>
          </div>

          <div class="stc-adv-col">
            <div class="stc-adv-field">
              <div class="stc-adv-label">
                <i class="fa fa-comment" aria-hidden="true"></i>
                <p class="stc-adv-field-label" id="stc-adv-lbl-remarks">Remarks</p>
              </div>
              <input type="text" id="stc-adv-remarks" class="form-control" placeholder="Type remarks…" aria-labelledby="stc-adv-lbl-remarks">
            </div>
          </div>
        </div>
      </div>
      <div class="stc-adv-actions">
        <button type="button" class="btn btn-default stc-adv-clear-inline stc-adhocpo-adv-clear">
          <i class="fa fa-eraser" aria-hidden="true"></i> Clear
        </button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success stc-adhocpo-adv-apply">
          <i class="fa fa-search" aria-hidden="true"></i> Apply & search
        </button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade bd-modal-ledgershow" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Customer Ledger</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#detailedLedger">Detailed Ledger</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#summaryLedger">Summary</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <!-- Detailed Ledger Tab -->
                    <div id="detailedLedger" class="tab-pane active">
                        <div class="row mt-3">
                            <div class="col-xl-12 col-md-12 col-sm-12">
                                <div class="card border-success mb-3 card-body">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="text-center">Date From/To</th>
                                                <th scope="col" class="text-center">Location</th>
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
                                            <tr><td colspan="11">Search</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary Tab -->
                    <div id="summaryLedger" class="tab-pane fade">
                        <div class="row mt-3">
                            <div class="col-xl-12 col-md-12 col-sm-12">
                                <div class="card border-success mb-3 card-body">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="text-center">Date From/To</th>
                                                <th scope="col" class="text-center">Customer</th>
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
                                                    <input type="date" class="form-control stc-summary-filterdatefrom" <?php echo "value='$effectiveDate'";?>>
                                                    <input type="date" class="form-control stc-summary-filterdateto" <?php echo "value='$newDate'";?>>
                                                </td>
                                                <td>
                                                    <select class="form-control stc-summary-filtersitename">
                                                      <option value="2">GLOBAL AC SYSTEM JSR PVT.LTD</option>
                                                      <option value="3">SARA ENTERPRISES</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <a class="btn btn-success stc-summary-filter-find" href="javascript:void(0)">Find</a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-xl-12 col-md-12 col-sm-12">
                                <div class="card border-success mb-3 card-body">
                                    <table class="table table-hover table-bordered stc-summary-view-table">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Sl No.</th>
                                                <th class="text-center">Site Name</th>
                                                <th class="text-center">Total Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody class="stc-call-view-summaryledger-row">
                                            <tr><td colspan="3">Search</td></tr>
                                        </tbody>
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
                    <?php
                    if (!isset($con)) {
                        include_once __DIR__ . '/../MCU/db.php';
                    }
                    $poadhoc_shop_names = [];
                    if (!empty($con)) {
                        $seen = [];
                        $pushPoadhocShopLoc = function ($loc) use (&$seen, &$poadhoc_shop_names) {
                            $loc = trim((string) $loc);
                            if ($loc === '') {
                                return;
                            }
                            $k = strtolower($loc);
                            if (isset($seen[$k])) {
                                return;
                            }
                            $seen[$k] = true;
                            $poadhoc_shop_names[] = $loc;
                        };
                        $q = mysqli_query($con, "SELECT DISTINCT TRIM(`shopname`) AS loc FROM `stc_shop` WHERE `shopname` IS NOT NULL AND TRIM(`shopname`) <> ''");
                        if ($q) {
                            while ($row = mysqli_fetch_assoc($q)) {
                                $pushPoadhocShopLoc($row['loc']);
                            }
                        }
                        $qTu = mysqli_query($con, "SELECT DISTINCT TRIM(`stc_trading_user_location`) AS loc FROM `stc_trading_user` WHERE `stc_trading_user_location` IS NOT NULL AND TRIM(`stc_trading_user_location`) <> ''");
                        if ($qTu) {
                            while ($row = mysqli_fetch_assoc($qTu)) {
                                $pushPoadhocShopLoc($row['loc']);
                            }
                        }
                        natcasesort($poadhoc_shop_names);
                        $poadhoc_shop_names = array_values($poadhoc_shop_names);
                    }
                    ?>
                    <select
                      id="stcpoadhocnameshop"
                      placeholder="Shop Name"
                      class="form-control validate"
                    >
                      <option value="">Select shop name</option>
                      <?php foreach ($poadhoc_shop_names as $poadhoc_sn) : ?>
                      <option value="<?php echo htmlspecialchars($poadhoc_sn, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($poadhoc_sn, ENT_QUOTES, 'UTF-8'); ?></option>
                      <?php endforeach; ?>
                    </select>
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
<div class="modal fade" id="vikings-shop-qty-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width:95%; max-width:1100px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Branch quantities <small class="text-muted">(Adhoc #<span id="vikings-shop-qty-modal-title-adhoc"></span>)</small></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="vikings_shop_qty_adhoc_id">
                <div class="panel panel-default" style="margin-bottom:12px;">
                    <div class="panel-body" style="padding:10px 15px;">
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <strong>Product</strong>
                                <div id="vikings-shop-modal-product-name" class="text-break">—</div>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <strong>Purchased qty</strong>
                                <div><span id="vikings-shop-modal-purchased-qty">0</span><span id="vikings-shop-modal-unit-suffix" class="text-muted"></span></div>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <strong>Allocated</strong>
                                <div class="text-muted" id="vikings-shop-modal-allocated-qty">0</div>
                            </div>
                            <div class="col-md-2 col-sm-4 col-xs-6">
                                <strong>Balanced</strong>
                                <div id="vikings-shop-modal-balanced-qty">0</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-condensed">
                        <thead>
                            <tr>
                                <th>Branch</th>
                                <th class="text-right">Qty</th>
                                <th>Unit</th>
                                <th>Rack</th>
                                <th class="text-center">Challan</th>
                                <th class="text-center">Edit</th>
                                <th class="text-center">Delete</th>
                                <th class="text-center" id="vikings-shop-qty-col-add-header" title="Add branch"><i class="fa fa-plus text-success"></i></th>
                            </tr>
                        </thead>
                        <tbody id="vikings-shop-qty-table-body"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-modal-inventoryshow" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Inventories</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12">
                  <div class="card border-success mb-3 card-body">
                    <h5 class="modal-title" id="exampleModalLongTitle">Warehouses & Branches</h5>
                    <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                        <li class="nav-item">
                            <a role="tab" type="warehouse" class="nav-link InvTypeBtns active" id="tab-modal-1" data-toggle="tab" href="#tab-content-modal-1">
                                <span>Warehouse</span>
                            </a>
                        </li>
                        <?php
                        if (!isset($poadhoc_shop_names)) {
                            $poadhoc_shop_names = [];
                        }
                        $inv_branch_tab_idx = 2;
                        foreach ($poadhoc_shop_names as $poadhoc_inv_sn) :
                            $poadhoc_inv_esc = htmlspecialchars($poadhoc_inv_sn, ENT_QUOTES, 'UTF-8');
                            $inv_tab_id = (int) $inv_branch_tab_idx;
                            $inv_branch_tab_idx++;
                        ?>
                        <li class="nav-item">
                            <a role="tab" type="<?php echo $poadhoc_inv_esc; ?>" class="nav-link InvTypeBtns" id="tab-modal-<?php echo $inv_tab_id; ?>" data-toggle="tab" href="#tab-content-modal-<?php echo $inv_tab_id; ?>" data-dc="dataContainer<?php echo $inv_tab_id; ?>" data-pg="paginations<?php echo $inv_tab_id; ?>">
                                <span><?php echo $poadhoc_inv_esc; ?></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane tabs-animation fade active" id="tab-content-modal-1" role="tabpanel">
                          <input type="text" class="searchKey" placeholder="Search product..." />
                          <div id="dataContainer"></div>
                          <div id="paginations"></div>
                        </div>
                        <?php
                        $inv_branch_tab_idx = 2;
                        foreach ($poadhoc_shop_names as $poadhoc_inv_sn) :
                            $poadhoc_inv_esc = htmlspecialchars($poadhoc_inv_sn, ENT_QUOTES, 'UTF-8');
                            $inv_tab_id = (int) $inv_branch_tab_idx;
                            $inv_branch_tab_idx++;
                        ?>
                        <div class="tab-pane tabs-animation fade" id="tab-content-modal-<?php echo $inv_tab_id; ?>" role="tabpanel">
                          <input type="text" class="searchKey" placeholder="Search product..." />
                          <div id="dataContainer<?php echo $inv_tab_id; ?>"></div>
                          <div id="paginations<?php echo $inv_tab_id; ?>"></div>
                        </div>
                        <?php endforeach; ?>
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

<!-- Cherry Pick Modal -->
<div class="modal fade" id="cherryPickModal" tabindex="-1" role="dialog" aria-labelledby="cherryPickModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="cherryPickForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cherryPickModalLabel">Cherry Pick Adhoc Item</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-12">
              <div class="form-group row">
                <div class="col-sm-4">
                  <label style="margin-left : 20px" for="cherryAdhocId">Adhoc ID : </label>
                </div>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="cherryAdhocId" name="adhoc_id" readonly>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-4">
                  <label style="margin-left : 20px" for="cherryCurrentQty">Current Qty : </label>
                </div>
                <div class="col-sm-8">
                  <input type="number" class="form-control" id="cherryCurrentQty" readonly>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-4">
                  <label style="margin-left : 20px" for="cherryQtyToDecrease">Qty to Decrease : </label>
                </div>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="cherryQtyToDecrease" name="qty_to_decrease" min="1" required>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-4">
                  <label style="margin-left : 20px" for="cherryNewQty">New Qty : </label>
                </div>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="cherryNewQty" name="new_qty" required>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-4">
                  <label style="margin-left : 20px" for="cherryUnit">Unit : </label>
                </div>
                <div class="col-sm-8">
                  <input type="text" class="form-control" id="cherryUnit" name="unit" required>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-4">
                  <label style="margin-left : 20px" for="cherrypRate">Purchase Rate : </label>
                </div>
                <div class="col-sm-8">
                  <input type="number" class="form-control" id="cherrypRate" name="purchase_rate" step="0.01" oninput="calculateEditSaleRate()" required>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-4">
                  <label style="margin-left : 20px" for="cherryPercentage">Profit Percentage : </label>
                </div>
                <div class="col-sm-8">
                  <input type="number" class="form-control" id="cherryPercentage" name="percentage" step="0.01" oninput="calculateEditSaleRate()" placeholder="Enter profit percentage">
                </div>
              </div>
              <div class="form-group row">
                <div class="col-sm-4">
                  <label style="margin-left : 20px" for="cherryRate">Sale Rate : </label>
                </div>
                <div class="col-sm-8">
                  <input type="number" class="form-control" id="cherryRate" name="sale_rate" readonly style="background-color: #f8f9fa;">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Save Cherry Pick</button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- product selection modal -->
<div class="modal fade stc-agent-req-get-mer-product-show" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Requisition Items Selection</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-12">
            <table class="table table-hover ">
              <thead>
                <tr>
                  <th scope="col">By Category</th>
                  <th scope="col">By Name</th>
                  <th scope="col">By Sub Category</th>
                  <th scope="col">Search</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <div class="card-border mb-3 card card-body border-success">
                      <select class="custom-select tm-select-accounts call_cat" id="filterbycat"
                        name="stcpdcategory">
                      </select>
                    </div>
                  </td>
                  <td>
                    <div class="card-border mb-3 card card-body border-success">
                      <input id="searchbystcname" name="stcsearchpdname" type="text" placeholder="Product Name" class="form-control validate" />
                      <input type="hidden" name="search_alo_in">
                    </div>
                  </td>
                  <td>
                    <div class="card-border mb-3 card card-body border-success">
                      <select class="custom-select tm-select-accounts call_sub_cat" id="filterbysubcat" name="stcpdsubcategory">
                      </select>
                    </div>
                  </td>
                  <td>
                    <div class="card-border mb-3 card card-body border-success">
                      <button type="submit" class="form-control btn btn-primary stcprosearchhit">Search <i class="fa fa-search"></i></button>
                    </div>
                  </td>
                  <td>
                    <a style="
                        font-size: 20px;
                        background: yellow;
                        border-radius: 50%;
                        padding: 5px;
                        margin: 0;
                      " href="#" class="upward"><i class="fas fa-arrow-up"></i>
                    </a>
                    <a style="
                        font-size: 20px;
                        background: yellow;
                        border-radius: 50%;
                        padding: 5px;
                        margin: 0;
                        display: none;
                      " href="#" class="downward"><i class="fas fa-arrow-down"></i>
                    </a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-12">
            <div class="row stc-req-product-show">
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

<div class="modal fade bd-modal-pendingshow" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Adhoc Adjustments List</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#detailedLedger">Adhoc Adjustments</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <!-- Detailed Ledger Tab -->
                    <div id="detailedLedger" class="tab-pane active">
                        <div class="row mt-3">
                            <div class="col-xl-12 col-md-12 col-sm-12">
                                <div class="card border-success mb-3 card-body">
                                    <table class="table table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="text-center">Date From/To</th>
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
                                                    <input type="date" class="form-control stc-pending-filterdatefrom" <?php echo "value='$effectiveDate'";?>>
                                                    <input type="date" class="form-control stc-pending-filterdateto" <?php echo "value='$newDate'";?>>
                                                </td>
                                                <td>
                                                    <a class="btn btn-success stc-adhocpendingfilter-find" href="javascript:void(0)">Find</a>
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
                                                <th>Combiner Id <br> Requisition Id</th>
                                                <th>Date</th>
                                                <th>Site Name</th>
                                                <th>Supervisors Name</th>
                                                <th>Adhoc Id</th>
                                                <th>Product Name</th>
                                                <th>Unit</th>
                                                <th>Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody class="stc-call-view-poadhocpending-row">
                                            <tr><td colspan="8">Search</td></tr>
                                        </tbody>
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


<!-- Tools details -->
<div class="modal fade bd-toolstracker-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Tools Track</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12 col-sm-12 col-xl-12">
            <div class="main-card mb-3 card itt-existing-tools-wrap" style="display:none;">
              <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
                <div>
                  <b>Existing Tools Track</b>
                  <div class="small text-muted">Already saved tool rows for this Adhoc ID.</div>
                </div>
                <div>
                  <span class="badge badge-secondary">Total: <span class="itt-existing-count">0</span></span>
                </div>
              </div>
              <div class="card-body" style="padding:0;">
                <div class="table-responsive" style="max-height:260px;overflow:auto;">
                  <table class="table table-sm table-hover table-bordered mb-0">
                    <thead class="thead-light" style="position:sticky;top:0;z-index:1;">
                      <tr>
                        <th class="text-center" style="width:60px;">#</th>
                        <th>Unique ID</th>
                        <th>Machine SR No</th>
                        <th>Make</th>
                        <th>Type</th>
                        <th>Warranty</th>
                        <th>Invoice</th>
                        <th class="text-center">Inv Date</th>
                        <th>Remarks</th>
                        <th class="text-center">Created</th>
                      </tr>
                    </thead>
                    <tbody class="itt-existing-tools-body">
                      <tr><td colspan="10" class="text-center text-muted">Loading...</td></tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="main-card mb-3 card">
              <div class="card-body">
                <div class="row formcontrol">
                  <div class="col-md-4">
                    <h5>Unique Id (GTT)</h5><br>
                    <div class="card mb-3 widget-content">
                      <p class="small text-muted mb-1">Assigned automatically: next free GTT number(s). One Tools Track row per ad-hoc quantity unit.</p>
                      <p class="mb-0"><span class="badge badge-info">Rows to create: <span class="itt-qty-badge">1</span></span></p>
                      <input type="hidden" class="itt-unique-id" value="">
                    </div>
                  </div>
                  <div class="col-md-8">
                    <h5>Item Description</h5>
                    <p class="small text-muted mb-1">Filled from Product Name column (you can edit).</p>
                    <div class="card mb-3 widget-content">
                      <textarea class="form-control itt-itemdescription"
                        placeholder="Enter Item Description"></textarea>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <h5>Machine SR No</h5><br>
                    <div class="card mb-3 widget-content">
                      <input type="text" class="form-control itt-machinesrno" placeholder="Enter Machine SR No"
                        required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <h5>Make</h5><br>
                    <div class="card mb-3 widget-content">
                      <input type="text" class="form-control itt-make" placeholder="Enter Make" required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <h5>Type</h5><br>
                    <div class="card mb-3 widget-content">
                      <input type="text" class="form-control itt-type" placeholder="Enter Type" required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <h5>Warranty</h5><br>
                    <div class="card mb-3 widget-content">
                      <input type="text" class="form-control itt-warranty" placeholder="Enter Warranty" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <h5>Purchase Details</h5>
                    <p class="small text-muted mb-1">Filled from From Source (Supplier / Location) column (you can edit).</p>
                    <div class="card mb-3 widget-content">
                      <input type="text" class="form-control itt-purdetails" placeholder="Enter Purchase Details"
                        required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <h5>Tax Invoice Number</h5><br>
                    <div class="card mb-3 widget-content">
                      <input type="text" class="form-control itt-tinnumber" placeholder="Enter Invoice Number" required>
                    </div>
                  </div>
                  <div class="col-md-3">
                    <h5>Tax Invoice Date</h5><br>
                    <div class="card mb-3 widget-content">
                      <input type="date" class="form-control itt-tindate" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <h5>Remarks</h5><br>
                    <div class="card mb-3 widget-content">
                      <textarea class="form-control itt-remarks" placeholder="Enter remarks"></textarea>
                    </div>
                  </div>
                  <div class="col-md-12">
                    <div class="card mb-3 widget-content">
                      <button class="form-control btn btn-success itt-save">Save</button>
                    </div>
                  </div>
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