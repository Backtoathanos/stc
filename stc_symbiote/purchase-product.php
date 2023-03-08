<?php
ini_set("session.gc_maxlifetime", 21600);
session_set_cookie_params(21600);
session_start();
if(empty(@$_SESSION['stc_admin_info_id'])){
    header('location:login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Purchase Product - STC</title>
    <link rel = "icon" type = "image/png" href="img/stc_logo_title.png">
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Roboto:400,700" />
    <!-- https://fonts.google.com/specimen/Roboto -->
    <link rel="stylesheet" href="css/fontawesome.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput-typeahead.css" />
    <!-- https://fontawesome.com/ -->
    <!-- <link rel="stylesheet" href="stark_tower/jquery-ui-datepicker/jquery-ui.min.css" type="text/css" /> -->
    <!-- http://api.jqueryui.com/datepicker/ -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <!-- https://getbootstrap.com/ -->
    <link rel="stylesheet" href="css/templatemo-style.css">

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/infinite.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <style>
      .bootstrap-tagsinput {
       width: 100%;
       color: blue;
      }
      .bootstrap-tagsinput .tag {
         color: black;
      }

      .search-box,.close-icon,.search-wrapper {
        position: relative;
        padding: 10px;
      }
      .search-wrapper {
        width: 500px;
        margin: auto;
        margin-top: 50px;
      }
      .search-box {
        width: 98%;
        border: 1px solid #ccc;
        outline: 0;
        border-radius: 15px;
      }
      .search-box:focus {
        box-shadow: 0 0 15px 5px #b0e0ee;
        border: 2px solid #bebede;
      }
      .close-icon {
        border:1px solid transparent;
        background-color: transparent;
        display: inline-block;
        vertical-align: middle;
        outline: 0;
        cursor: pointer;
      }
      .close-icon:after {
        content: "X";
        display: block;
        width: 15px;
        height: 15px;
        position: absolute;
        background-color: #FA9595;
        z-index:1;
        right: 35px;
        top: 0;
        bottom: 0;
        margin: auto;
        padding: 0px;
        border-radius: 50%;
        text-align: center;
        color: white;
        font-weight: normal;
        font-size: 12px;
        box-shadow: 0 0 2px #E50F0F;
        cursor: pointer;
      }
      .search-box:not(:valid) ~ .close-icon {
        display: none;
      }
    </style>
  </head>

  <body>
   <?php include "header.php";?>

    <div class="container-fluid tm-mt-big tm-mb-big animated flip">
      <div class="row ">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="page-title-right">
                  <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="erp.php" class="text-uppercase" value="">ERP</a></li>
                    <li class="breadcrumb-item active"><a href="#" class="active" value="">Purchase Product</a></li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <label class="btn btn-default text-uppercase"><a href="#" class="btn btn-success btn-block text-uppercase add-combo" value=""> <b>+</b>Create New Purchase order</a></label>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <!-- Create order -->
        <div style="display: none;" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-add">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">Purchase Point</h2> <a id="stc_uppp" href="#"><i class="fas fa-arrow-up"></i></a>
              </div>
            </div>
            <div class="row stc-purchase-point-row">
              <div class="col-xl-6 col-md-6 col-sm-6">
                <form action="" class="stc-add-po-product-form"> 
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Order Number
                  </label>
                  <input
                    id="gtonumbershow"
                    name="stcmername"
                    type="text"
                    placeholder="Purchase Order Number"
                    class="form-control validate"
                    disabled
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-6">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Order Date
                  </label>
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
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Merchant Name
                  </label> : <p id="stc_view_merchant_name"></p>
                  <select
                    id="stc_vendor_purchase_product"
                    class="custom-select stc-select-vendor"
                    name="stcvendor"
                  >
                  </select>
                </div>
              </div>
              <div class="col-xl-12 col-md-12 col-sm-12">
                <table class="table table-hover ">
                  <thead>
                    <tr>
                      <th scope="col" width="100%">Search By Material Name</th>
                      <th scope="col">Search</th>
                      <th></th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <input
                          id="potags"
                          data-role="tagsinput"
                          type="text"
                          placeholder="Material Name"
                          class="form-control validate stcfilterbyponumber"
                          required
                        />
                      </td>
                      <td>
                        <button type="button" name="search" class="btn btn-primary" id="posearch">Search</button>
                      </td>
                      <td>
                        <a style="font-size: 20px;background: yellow;border-radius: 50%;padding: 5px;margin: 0;" href="#" class="upward">
                          <i class="fas fa-arrow-up"></i>
                        </a>
                        <a style="font-size: 20px;background: yellow;border-radius: 50%;padding: 5px;margin: 0; display: none;" href="#" class="downward">
                          <i class="fas fa-arrow-down"></i>
                        </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>              
              <div class="col-xl-12 col-md-12 col-sm-12 thishypo">
                <div class="row stc-call-view-po-product-row">
                </div>
              </div>

              <!-- line item table -->
              <div class="col-xl-12 col-md-12 col-sm-12 flase">
              </div>
              <div class="col-6">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Terms & Conditions
                  </label>
                  <textarea
                    class="form-control validate"
                    rows="2"
                    id="stcpdtandc"
                    placeholder="Terms & Conditions"
                    required
                  >
                    1. GST : ,
                    2. Payment : NA,
                    3. Packing & Forwarding : NA,
                    4. Freight : NA,
                    5. Warranty/Guarantee : ,
                    6. Delivery Time : ,
                    7. Certificates : ,
                    Others
                    Delivery Address : Global AC System JSR PVT.LTD. C/O Majesty, 
                    79 A Block Dhatkidih PO-Bistupur, Jamshedpur-831001, Jharkhand

                  </textarea>
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-6">
                <form action="" class="stc-add-po-product-form"> 
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Purchasing for
                  </label>
                  <input
                    id="gtonumberinst"
                    name="gtonumberinst"
                    type="number"
                    placeholder="GTO/WOG Number"
                    class="form-control validate"
                  />
                </div>
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block text-uppercase stcsaveandppp">Save</button>
              </div>
            </div>
          </form>
          </div>
        </div>

        <!-- view Purchase Orders -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto" style="overflow-x: auto; white-space: nowrap;">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">Purchase Orders</h2>
              </div>
            </div>
            <div class="row stc-view-product-row">
              <div class="col-xl-12 col-lg-12 col-md-12">
                <form action="" class="stc-view-product-form">
                    <table class="table table-hover ">
                      <thead>
                        <tr>
                          <th scope="col">From/<br>To</th>
                          <th scope="col" width="30%">By PO Number/ Merchant/ Status</th>
                          <th scope="col">Search</th>
                          <th scope="col" colspan="2">By Products</th>
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
                            <p><a href="#" id="purchaseproddatefilt">
                              <i style="font-size: 20px;background: #0c0c1b;border-radius: 50%;padding: 5px;margin: 0;color: #77ffd3;float: right;" class="fa fa-arrow-down"></i>
                            </a></p>
                          </td>
                          <td>
                            <input
                              id="tags"
                              data-role="tagsinput"
                              type="text"
                              placeholder="PO Number/ Merchant/ Status"
                              class="form-control validate stcfilterbyponumber"
                              required
                            />
                          </td>
                          <td>
                            <button type="button" name="search" class="btn btn-primary" id="search">Search</button>
                          </td>
                          <td>
                            <input
                              id="stcfilterbypdname"
                              type="text"
                              placeholder="Product Name"
                              class="form-control validate"
                              required
                            />
                          </td>
                          <td>
                            <a class="btn btn-success searchbypdnamehit" >Search <i class="fa fa-search"></i></a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                </form>
              </div>
            </div>
            <div class="row stc-view-purchase-row">
              <div class="col-xl-12 col-lg-12 col-md-12">
                <form action="" class="stc-view-purchase-order-form">
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
      </div>
    </div>
    <?php include "footer.php";?>
    <script>
      $(document).ready(function(){
        $('.add-combo').on('click', function(e){
          e.preventDefault();
          $('.stc-add').toggle(1000);
          $('.stc-view').fadeOut(500);
          // $('.stc-add-po-product-form')[0].reset();
        });

        $('#stc_uppp').on('click', function(e){
          e.preventDefault();
            $('.stc-view').toggle(1000);
            $('.stc-add').fadeOut(500);
        });

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

        $(".ddd").on("click", function(w) {
          w.preventDefault();
          var $button = $(this);
          var $input = $button.closest('.sp-quantity').find("input.quntity-input");
          $input.val((i, v) => Math.max(0, +v + 1 * $button.data('multi')));
        });
      });
    </script>
    <script>
      $(document).ready(function(){
        // load po       
        function load_data(query, begdate, enddate) {
          $.ajax({
            url:"asgard/purchase_product.php",
            method:"POST",
            data:{
              stcfilterpo:query,
              begdate:begdate,
              enddate:enddate
            },
            dataType:"json",
            success:function(data){
              // console.log(data);
              // $('#total_records').text(data.length);
             $('.stc-view-purchase-order-form').html(data);
            }
          });
        }

        $('#search').click(function(){
          var query = $('#tags').val();
          var begdate=$('.begdate').val();
          var enddate=$('.enddate').val();            
          if(begdate=='' || enddate=='' || query==''){
            alert("date aur search field khaali mat de!!!");
          }else{
            load_data(query, begdate, enddate);
          }
        });

        // load po items
        function load_po_items(query) {
          $.ajax({
            url:"asgard/purchase_product.php",
            method:"POST",
            data:{search_po_pd_name_in:query},
            dataType:"json",
            success:function(data){
              // console.log(data);
              $('.stc-call-view-po-product-row').html(data);
            }
          });
        }

        $('#posearch').click(function(){
          var query = $('#potags').val();
          load_po_items(query);
        });
      });
    </script>
  </body>
</html>
