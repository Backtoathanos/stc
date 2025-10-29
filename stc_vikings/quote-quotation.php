<?php  
// Include authentication helper
require_once 'kattegat/auth_helper.php';

// Check authentication using the new hybrid system
STCAuthHelper::checkAuth();$page_code=501;
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
    <title>Quotation - STC</title>
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
                                    <span>View Quotations</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-content-2">
                                    <span>Create Quotation</span>
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
                                              >View Quotations
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row stc-view-quote-row">
                                  <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <form action="" class="stc-view-quote-form">
                                          <table class="table table-hover ">
                                            <tr>
                                              <td>
                                                Loading.....
                                              </td>
                                            </tr>
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
                                              >Create Quoation
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                  <div class="col-xl-12 col-md-12 col-sm-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                      <form action="" class="stc-add-quotation-form">
                                        <div class="row stc-quotation-row">
                                          <div class="col-xl-6 col-md-6 col-sm-12"> 
                                            <div class="card-border mb-3 card card-body border-success">
                                              <h5
                                                for="number"
                                                >Quotation Number
                                              </h5>
                                              <input
                                                id="gtonumbershow"
                                                name="stcquotemername"
                                                type="text"
                                                placeholder="Quotation Order Number"
                                                class="form-control validate"
                                                disabled
                                              />
                                            </div>
                                          </div>
                                          <div class="col-xl-6 col-md-6 col-sm-12">
                                            <div class="card-border mb-3 card card-body border-success">
                                              <h5
                                                for=""
                                                >Quotation Date
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
                                                >Quotation Merchant Name : <span id="stc_vendor_quotation_edit"></span>
                                              </h5>
                                              <select
                                                id="stc_vendor_quotation"
                                                class="custom-select stc-select-customer"
                                                name="stcquotevendor"
                                              >
                                              </select>
                                            </div>
                                          </div>
                                          <div class="col-xl-12 col-md-12 col-sm-12">
                                            <div class="card-border mb-3 card card-body border-success">
                                              <div class="col-12">
                                                <h2 class="tm-block-title d-inline-block">Products</h2>
                                              </div>
                                            </div>
                                          </div>              
                                          <div class="col-xl-12 col-lg-12 col-md-12">
                                            <div class="card-border mb-3 card card-body border-success">
                                              <form action="" class="stc-view-product-form">
                                                  <table class="table table-hover ">
                                                      <thead>
                                                          <tr>
                                                              <th scope="col">By Category</th>
                                                              <th scope="col">By Name</th>
                                                              <th scope="col">By Sub Category</th>
                                                          </tr>
                                                      </thead>
                                                      <tbody>
                                                          <tr>
                                                              <td>
                                                                  <div class="card-border mb-3 card card-body border-success">
                                                                    <select
                                                                      class="custom-select tm-select-accounts call_cat stcprosearchsame"
                                                                      id="filterbycat"
                                                                      name="stcpdcategory"
                                                                    >
                                                                    </select>
                                                                  </div>
                                                              </td>
                                                              <td>
                                                                  <div class="card-border mb-3 card card-body border-success">
                                                                    <input
                                                                      id="searchbystcname"
                                                                      name="stcsearchpdname"
                                                                      type="text"
                                                                      placeholder="Product Name"
                                                                      class="form-control validate stcprosearchsame"
                                                                      required
                                                                    />
                                                                    <input type="hidden" name="search_alo_in">
                                                                  </div>
                                                              </td>
                                                              <td>
                                                                  <div class="card-border mb-3 card card-body border-success">
                                                                      <select
                                                                        class="custom-select tm-select-accounts call_sub_cat stcprosearchsame"
                                                                        id="filterbysubcat"
                                                                        name="stcpdsubcategory"
                                                                      >
                                                                      </select>
                                                                  </div>
                                                              </td>
                                                          </tr>
                                                      </tbody>
                                                  </table>
                                              </form>
                                            </div>
                                          </div>
                                          <div class="col-xl-12 col-lg-12 col-md-12">
                                            <div class="row stc-call-view-product-row">
                                              
                                            </div>
                                          </div>

                                          <!-- line item table -->                                    
                                          <div class="col-xl-12 col-lg-12 col-md-12">
                                            <div class="card-border mb-3 card card-body border-success quotelineitem">
                                              
                                            </div>
                                          </div>
                                          <div class="col-xl-12 col-lg-12 col-md-12">
                                            <div class="card-border mb-3 card card-body border-success">
                                              <h5
                                                for=""
                                                >Terms & Conditions
                                              </h5>
                                              <textarea
                                                class="form-control validate"
                                                rows="2"
                                                id="stcquotetandc"
                                                placeholder="Terms & Conditions"
                                                required
                                              ></textarea>
                                            </div>
                                          </div>
                                          <div class="col-xl-12 col-lg-12 col-md-12">
                                            <div class="card-border mb-3 card card-body border-success">
                                              <h5
                                                for=""
                                                >Notes
                                              </h5>
                                              <textarea
                                                class="form-control validate"
                                                rows="2"
                                                id="stcquotenotes"
                                                placeholder="Enter a Notes/Remarks"
                                                required
                                              ></textarea>
                                            </div>
                                          </div>
                                          <div class="col-xl-12 col-lg-12 col-md-12">
                                            <div class="card-border mb-3 card card-body border-success">
                                              <button type="submit" class="btn btn-primary btn-block text-uppercase stcsavequote">Save</button>
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
          stc_call_customer();
          function stc_call_customer(){
            $.ajax({
              url       : "kattegat/ragnar_quotation.php",
              method    : "post",
              data      : {friday_vendor:1},
              dataType  : 'JSON',
              success   : function(data){
                // console.log(data);
                $('#expire_date').val(data['time']);
                $('.stc-select-customer').html(data['vendor']);
              }
            });
          }

          $('.upward').on('click', function(e){
            e.preventDefault();
              $('.downward').toggle(500);
              $('.thishypo').fadeOut(500);
              $('.upward').fadeOut(500);
          });

          $('.downward').on('click', function(e){
            e.preventDefault();
              $('.upward').toggle(500);
              $('.thishypo').toggle(500);
              $('.downward').fadeOut(500);
          });

          var jsfiltercat;
          var jsfiltersubcat;
          var jsfiltername;
          // filter function
          // filter by cat
          $("#filterbycat").change(function(e){
            e.preventDefault();
            $('.stc-call-view-product-row').html("Loading...");
            jsfiltercat = $(this).val();
            jsfiltersubcat = $('#filterbysubcat').val();
            jsfiltername = $('#searchbystcname').val();
            stc_filter_pro_foredit(jsfiltercat, jsfiltersubcat ,jsfiltername);
          });

          // filter by sub cat
          $("#filterbysubcat").change(function(e){
            e.preventDefault();
            $('.stc-call-view-product-row').html("Loading...");
            jsfiltercat = $('#filterbycat').val();
            jsfiltersubcat = $(this).val();
            jsfiltername = $('#searchbystcname').val();
            stc_filter_pro_foredit(jsfiltercat, jsfiltersubcat ,jsfiltername);
          });

          // filter by name
          $("#searchbystcname").on('keyup', function(e){
            e.preventDefault();
            jsfiltercat = $('#filterbycat').val();
            jsfiltersubcat = $('#filterbysubcat').val();
            jsfiltername = $(this).val();
            var responset='<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12"><a href="#" class="btn btn-primary btn-block text-uppercase mb-3">Atleast type word in text field or search via category or sub category!!!</a></div>';
            var countlen=jsfiltername.length;
            if((+jsfiltername!='') && countlen>=3){
              stc_filter_pro_foredit(jsfiltercat, jsfiltersubcat ,jsfiltername);
            }else{
              $('.stc-call-view-product-row').html(responset);
            }
          });

          // filter function
          function stc_filter_pro_foredit(jsfiltercat, jsfiltersubcat ,jsfiltername){
            $.ajax({
              url     : "kattegat/ragnar_quotation.php",
              method  : "post",
              data    : {
                stcaction:1,
                phpfiltercatout:jsfiltercat,
                phpfiltersubcatout:jsfiltersubcat,
                phpfilternameout:jsfiltername
              },
              // dataType : 'JSON',
              success : function(data){
                // console.log(data);
                $('.stc-call-view-product-row').html(data);
              }
            });
          }


          // add cart to Quotation session
          $('body').delegate('.add_to_cart_for_vendor_quote','click',function(e){
            e.preventDefault();
            var product_id = $(this).attr("id");  
            var product_name = $('#stcquotepdname'+product_id).val();
            var product_unit = $('#stcquotepdunit'+product_id).val();
            var product_quantity = $('#stcquoteqty'+product_id).val();
            var product_rate = $('#stcquoterate'+product_id).val();
            var product_hsncode = $('#stcquotepdhsncode'+product_id).val();
            var product_gst = $('#stcquotepdgst'+product_id).val();
            var add_to_cart_for_quote="add";
            $.ajax({
              url     : "kattegat/ragnar_quotation.php",
              method  :"POST",
              data    :{
                  product_id:product_id,
                  product_name:product_name,  
                  product_unit:product_unit,
                  product_quantity:product_quantity,
                  product_rate:product_rate,
                  product_hsncode:product_hsncode,
                  product_gst:product_gst,
                  add_quote:add_to_cart_for_quote  
              },  
              success :function(data){
                show_quote_cart();
                alert(data);
                // console.log(data);                              
              }  
            });      
          });

          // show cart on product purchase add sectin page
          show_quote_cart();
          function show_quote_cart(){
              $.ajax({
                url     : "kattegat/ragnar_quotation.php",
                method  : 'POST',
                data    : {stc_show_quote_sess:1},
                dataType: 'JSON',
                success : function(data){
                  // console.log(data);
                    $('.quotelineitem').html(data["orderTable"]);
                }
              });
          }

          // quantity change Quotation session
          $(document).on('click', '.stcqtyquotehit', function(e){
            e.preventDefault();
            var product_id = $(this).data("product_id");  
            var quantity = $('#stcqtyquotet'+product_id).val();   
            var action = "quantity_change"; 
            if(quantity != 0){  
              $.ajax({  
                url     : "kattegat/ragnar_quotation.php",
                method  : "POST",  
                data    : {
                   product_id:product_id, 
                   quantity:quantity, 
                   quantity_action:action},  
                success : function(data){ 
                  alert(data);
                  show_quote_cart();
                 // console.log(data);
                }  
              });  
            }else{
              alert("Invalid Quantity!!!");
            }
          });

          // price change Quotation session
          $(document).on('click', '.stcratequotehit', function(e){
            e.preventDefault();
            var product_id = $(this).data("product_id");  
            var rate = $('#stcratequotet'+product_id).val();   
            var action = "rate_change"; 
            if(rate != 0){  
              $.ajax({  
                url     : "kattegat/ragnar_quotation.php",
                method  : "POST",  
                data    : {
                   product_id:product_id, 
                   rate:rate, 
                   rate_action:action},  
                success : function(data){ 
                  alert(data);
                  show_quote_cart();
                 // console.log(data);
                }  
              });  
            }else{
              alert("Invalid Rate!!!");
            }
          });

          // delete from cart Vendor Quotation session
          $('body').delegate('.stcdelquotebtn','click',function(e){
              e.preventDefault();
              var product_id = $(this).attr("id");
              if(confirm("Are you sure you want to remove this product?")){   
                $.ajax({  
                  url     : "kattegat/ragnar_quotation.php", 
                  method  : "POST",
                  data    : {  
                      product_id:product_id,
                      stcdelvendorquotelinei:1  
                  },  
                  success : function(data){  
                    // console.log(data);
                    show_quote_cart();
                    alert(data);                        
                  }  
                });  
              }         
          });

          // onsave quote
          $('body').delegate('.stcsavequote', 'click',function(e){
              e.preventDefault();
              var merchantid=$('#stc_vendor_quotation').val();
              var quote_termandc=$('#stcquotetandc').val();
              var quote_notes=$('#stcquotenotes').val();
              $.ajax({  
                url       : "kattegat/ragnar_quotation.php", 
                method    : "POST",  
                data      : {
                  stcprosavevendquote:1,
                  vmerchantid:merchantid,
                  vtermandc:quote_termandc,
                  vquotenotes:quote_notes
                },
                dataType  : 'JSON',
                success   : function(data){ 
                  // console.log(data);
                  if(data['objloki']=="Success"){
                    alert("Your Quotation is Saved!!!");
                    $('.stc-add-quotation-form')[0].reset();
                  }else{
                    alert("Please Check Form & Try Again!!");
                  }
                }  
              });  
          });

          // call product quote section
          stc_call_quote();
          function stc_call_quote(){
              $.ajax({
                url       : "kattegat/ragnar_quotation.php", 
                method    : 'POST',
                data      : {stccallquotations:1},
                dataType  : 'JSON',
                success   : function(data){
                  // console.log(data);
                    $('.stc-view-quote-form').html(data);
                }
              });     
          }  
        });
    </script>
</body>
</html>