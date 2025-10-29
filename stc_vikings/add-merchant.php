<?php  
// Include authentication helper
require_once 'kattegat/auth_helper.php';

// Check authentication using the new hybrid system
STCAuthHelper::checkAuth();$page_code=304;
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
    <title>Add Merchant - STC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="">
    <meta name="msapplication-tap-highlight" content="no">
    <!-- Latest compiled and minified CSS -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous"> -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
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
                                    <span>Add New Merchant</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-content-2">
                                    <span>View All Merchant</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-3" data-toggle="tab" href="#tab-content-3">
                                    <span>Merchant Items</span>
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
                                              >Add Merchant
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                      <form action="" class="stc-add-merchant-form">
                                        <div class="card-border mb-3 card card-body border-primary">
                                          <h5
                                            for="name"
                                            >Merchant Name
                                          </h5>
                                          <input
                                            id="name"
                                            name="stcmername"
                                            type="text"
                                            placeholder="Enter Merchant Name"
                                            class="form-control validate"
                                            required
                                          />
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="card-border mb-3 card card-body border-primary">
                                          <h5
                                            for="address"
                                            >Merchant Address</h5
                                          >
                                          <textarea
                                            class="form-control validate"
                                            rows="2"
                                            name="stcmeraddress"
                                            placeholder="Enter Merchant Description"
                                            required
                                          ></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card-border mb-3 card card-body border-primary">
                                          <h5
                                            for="city"
                                            >Merchant City</h5
                                          >
                                          <select
                                            class="custom-select tm-select-accounts call_city"
                                            name="stcmercity"
                                          >
                                          </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="card-border mb-3 card card-body border-primary">
                                        <h5
                                          for="state"
                                          >Merchant State</h5
                                        >
                                        <select
                                          class="custom-select tm-select-accounts call_state"
                                          name="stcmerstate"
                                        >
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card-border mb-3 card card-body border-primary">
                                          <h5
                                            for="contactperson"
                                            >Merchant Contact Person
                                          </h5>
                                          <input
                                            id="name"
                                            name="stcmercontperson"
                                            type="text"
                                            placeholder="Enter Merchant Contact Person"
                                            class="form-control validate"
                                            required
                                          />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card-border mb-3 card card-body border-primary">
                                          <h5
                                            for="rack"
                                            >Merchant Contact Number</h5
                                          >
                                          <input
                                            id="name"
                                            name="stcmercontnumber"
                                            type="number"
                                            placeholder="Enter Merchant Contact Number"
                                            class="form-control validate"
                                            required
                                          />
                                          </select>

                                          <input type="hidden" name="stc_add_merchant_hit">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="card-border mb-3 card card-body border-primary">
                                        <h5
                                          for="email"
                                          >Merchant Email</h5
                                        >
                                        <input
                                          id="name"
                                          name="stcmeremail"
                                          type="text"
                                          placeholder="Enter Merchant Email"
                                          class="form-control validate"
                                          required
                                        />
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="card-border mb-3 card card-body border-primary">
                                        <h5
                                          for="knownfor"
                                          >Merchant Specially Known For</h5
                                        >
                                        <input
                                          id="name"
                                          name="stcmerskf"
                                          type="text"
                                          placeholder="Enter Merchant Specially Known For"
                                          class="form-control validate"
                                          required
                                        />
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="card-border mb-3 card card-body border-primary">
                                        <h5
                                          for="knownfor"
                                          >Merchant GSTIN</h5
                                        >
                                        <input
                                          id="name"
                                          name="stcmergstin"
                                          type="text"
                                          placeholder="Enter Merchant GSTIN"
                                          class="form-control validate"
                                          required
                                        />
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card-border mb-3 card card-body border-primary">
                                          <h5
                                            for="knownfor"
                                            >Merchant PAN</h5
                                          >
                                          <input
                                            id="name"
                                            name="stcmerpan"
                                            type="text"
                                            placeholder="Enter Merchant PAN"
                                            class="form-control validate"
                                            required
                                          />
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-12">
                                      <button type="submit" class="btn btn-primary btn-block text-uppercase">Add Merchant Now</button>
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
                                              >View Merchants
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                      <form action="" class="stc-view-Merchant-form">
                                        <table class="table table-hover ">
                                          <thead>
                                            <tr>
                                              <th scope="col">By Name</th>
                                              <th scope="col">By Known For</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            <tr>
                                              <td>
                                                <input
                                                  id="searchbystcmername"
                                                  name="stcmername"
                                                  type="text"
                                                  placeholder="Merchant Name"
                                                  class="form-control validate"
                                                  required
                                                />
                                              </td>
                                              <td>
                                                <input
                                                  id="searchbystcmerskf"
                                                  name="stcmerskf"
                                                  type="text"
                                                  placeholder="Merchant Known FOr"
                                                  class="form-control validate"
                                                  required
                                                />
                                              </td>
                                            </tr>
                                          </tbody>
                                        </table>
                                      </form>
                                    </div>
                                </div>                                
                                <div class="row stc-call-view-Merchant-row">
                                </div>
                            </div>
                            <div class="tab-pane tabs-animation fade" id="tab-content-3" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >Merchant Items
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="name"
                                          >Merchant Name
                                        </h5>
                                        <select
                                          id="stc_get_merchant"
                                          class="custom-select stc-select-vendor"
                                          name="stcvendor"
                                        >
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <button class="form-control btn btn-success stc-get-merchant-item-hit">Find</button>
                                      </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <table class="table table-hover table-bordered table-responsive">
                                          <thead>
                                            <th class="text-center" >Item ID</th>
                                            <th class="text-center" width="25%">Item Description</th>
                                            <th class="text-center" >Total PO Qty</th>
                                            <th class="text-center" >PO Qty</th>
                                            <th class="text-center" >Total GRN Qty</th>
                                            <th class="text-center" >GRN Qty</th>
                                            <th class="text-center" >Inventory Qty</th>
                                            <th class="text-center" >Challan Qty</th>
                                            <th class="text-center" >Electronics Inventory Qty</th>
                                            <th class="text-center" >Electronics Challan Qty</th>
                                          </thead>
                                          <tbody class="stc-show-merchant-item-rec">                                            
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
    <script>
        $(document).ready(function(){
          // add merchant
          $('.stc-add-merchant-form').on('submit',function(e){
            e.preventDefault();
            $.ajax({
              url             : "kattegat/ragnar_merchant.php",
              method          : "post",
              data            : new FormData(this),
              contentType     : false,
              cache           : false,
              processData     : false,
              success         : function(data){
                data=data.trim();  
                if(data=="Merchant added!!"){
                  alert(data);
                  $('.stc-add-merchant-form')[0].reset();
                }else{
                  alert(data);
                }
                
              }
            });
          });

          // state & city call
          stc_vendor_page_on_call();
          function stc_vendor_page_on_call(){
            $.ajax({
              url         : "kattegat/ragnar_merchant.php",
              method      : "post",
              data        : {indialocation:1},
              dataType    : 'JSON',
              success     : function(data){
                // console.log(data);
                $('.call_city').html(data[0]);
                $('.call_state').html(data[1]);
              }
            });
          }

          // merchant search
          var search_mer_byname_var;
          $('#searchbystcmername').on('keyup input', function(){
            search_mer_byname_var=$(this).val();
            $.ajax({
              url       : "kattegat/ragnar_merchant.php",
              method    : "post",
              data      : {search_mer_byname_var_in:search_mer_byname_var},
              dataType  : 'JSON',
              success   : function(data){
                // console.log(data);
                $('.stc-call-view-Merchant-row').html(data);
              }
            });
          });
          
          // merchant search 2
          var search_mer_var_byskf_var; 
          $('#searchbystcmerskf').on('keyup input', function(){
            search_mer_var_byskf_var=$(this).val();
            $.ajax({
              url       : "kattegat/ragnar_merchant.php",
              method    : "post",
              data      : {search_mer_var_byskf_var_in:search_mer_var_byskf_var},
              dataType  : 'JSON',
              success   : function(data){
                // console.log(data);
                $('.stc-call-view-Merchant-row').html(data);
              }
            });
          }); 

          // call merchant
          stc_vendor_on_purchase_page();
          function stc_vendor_on_purchase_page(){
            $.ajax({
              url       : "kattegat/ragnar_purchase.php",
              method    : "post",
              data      : {friday_vendor:1},
              dataType  : 'JSON',
              success   : function(data){
                // console.log(data);
                $('.stc-select-vendor').html(data['vendor']);
              }
            });
          }

          // get item from merchant
          $('body').delegate('.stc-get-merchant-item-hit', 'click', function(e){
            e.preventDefault();
            var mer_id=$('#stc_get_merchant').val();
            $.ajax({
              url       : "kattegat/ragnar_merchant.php",
              method    : "post",
              data      : {
                stc_get_merchant_item:1,
                mer_id:mer_id
              },
              success   : function(data){
                // console.log(data);
                $('.stc-show-merchant-item-rec').html(data);
              }
            });
          });
        });
    </script>
</body>
</html>
