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
    <title>Silent Challan - STC</title>
    <link rel = "icon" type = "image/png" href="img/stc_logo_title.png">
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Roboto:400,700" />
    <!-- https://fonts.google.com/specimen/Roboto -->
    <link rel="stylesheet" href="css/fontawesome.min.css" />
    <!-- https://fontawesome.com/ -->
    <!-- <link rel="stylesheet" href="stark_tower/jquery-ui-datepicker/jquery-ui.min.css" type="text/css" /> -->
    <!-- http://api.jqueryui.com/datepicker/ -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <!-- https://getbootstrap.com/ -->
    <link rel="stylesheet" href="css/templatemo-style.css">

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/infinite.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput-typeahead.css" />

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

    <div class="container-fluid tm-mt-big tm-mb-big ">
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="page-title-right">
                  <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="erp.php" class="text-uppercase" value="">ERP</a></li>
                    <li class="breadcrumb-item active"><a href="#" class="active" value="">Silent Challan</a></li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
     <!--  <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto animated zoomInDown">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <label class="btn btn-default text-uppercase"><a href="#" class="btn btn-success btn-block text-uppercase add-combo" value=""> With GST</a></label>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <label class="btn btn-default text-uppercase"><a href="#" class="btn btn-success btn-block text-uppercase add-combo" value=""> <b>+</b> Create New Silent Challan</a></label>
              </div>
            </div>
          </div>
        </div>
      </div> -->
      <div class="row animated zoomInUp">
        <!-- Create order -->
        <div style="display: none;" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-add">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">Sale Point</h2> <a id="stc_uppp" href="#"><i class="fas fa-arrow-up"></i></a>
              </div>
            </div>
            <div class="row stc-silent-challan-row">
              <div class="col-xl-6 col-md-6 col-sm-12">
                <form action="" class="stc-add-silent-challan-form"> 
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Silent Challan Number
                  </label>
                  <input
                    id="schallanno"
                    name="schallanno"
                    type="text"
                    placeholder="Silent Challan Number"
                    class="form-control validate"
                    disabled
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Silent Challan Date
                  </label>                  
                  <input 
                    type="date"
                    class="form-control validate silentchallandate"
                    data-large-mode="true"
                  />
                </div>
              </div>
              <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Customer Name
                  </label>
                  <input
                    name="silentchallancustomername"
                    type="text"
                    class="form-control validate silentchallancustomername"
                    placeholder="Customer Name"
                    style="background-color: #186abb;"
                  />
                  </select>
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-6">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Customer Billing Address
                  </label>
                  <textarea
                    class="form-control validate"
                    rows="2"
                    id="silentchallancustomernilladdress"
                    placeholder="Billing Address"
                    required
                  ></textarea>
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-6">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Customer Shipping Address
                  </label>
                  <textarea
                    class="form-control validate"
                    rows="2"
                    id="silentchallancustomershippaddress"
                    placeholder="Shipping Address"
                    required
                  ></textarea>
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Customer Contact number
                  </label>
                  <input
                    name="silentchallancustomercontactnumber"
                    type="text"
                    class="form-control validate silentchallancustomercontactnumber"
                    placeholder="Contact Number"
                    style="background-color: #186abb;"
                    value="NA"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Customer Order On
                  </label>
                  <input
                    name="silentchallancustomerorderon"
                    type="date"
                    min="2001-01-01" 
                    max="2050-12-31"
                    placeholder="Customer Order Date"
                    class="form-control validate silentchallancustomerorderon"
                    style="background-color: #186abb;"
                    value=""
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Customer Delivered On
                  </label>
                  <input
                    id="datepicke1r"
                    name="silentchallancustomerdeliveredon"
                    type="date"
                    min="2001-01-01" 
                    max="2050-12-31"
                    placeholder="Customer Delivered Date"
                    class="form-control validate silentchallancustomerdeliveredon"
                    style="background-color: #186abb;"
                    value=""
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Order By
                  </label>
                  <input
                    name="silentchallanorderby"
                    type="text"
                    class="form-control validate silentchallanorderby"
                    placeholder="Order By"
                    style="background-color: #186abb;"
                    value="NA"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Deliver Through
                  </label>
                  <input
                    name="silentchallandelevredthrough"
                    type="text"
                    class="form-control validate silentchallandelevredthrough"
                    placeholder="Deliver through"
                    style="background-color: #186abb;"
                    value="NA"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Warranty Status
                  </label>
                  <input
                    type="text"
                    class="form-control validate silentchallanwarrantystatus"
                    placeholder="Warranty status"
                    style="background-color: #186abb;"
                    value="NA"
                    required
                  />
                </div>
              </div>
              <!-- product search -->
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
                          id="challanitemstags"
                          data-role="tagsinput"
                          type="text"
                          placeholder="Material Name"
                          class="form-control validate stcfilterbyponumber"
                          required
                        />
                      </td>
                      <td>
                        <button type="button" name="search" class="btn btn-primary" id="challanitemsearch">Search</button>
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
              <!-- search product display -->
              <div class="col-xl-12 col-md-12 col-sm-12 thishypo">
                <div class="row stc-call-view-sale-product-row">
                </div>
              </div>
              <!-- line item table -->
              <div class="col-xl-12 col-md-12 col-sm-12 silent-challan-show-prod">
              </div>
              <div class="col-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Notes
                  </label>
                  <textarea
                    class="form-control validate"
                    rows="2"
                    id="silentchallannotes"
                    placeholder="Notes"
                  ></textarea>
                </div>
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block text-uppercase stcsavesilentchallan">Save</button>
              </div>
            </div>
          </form>
          </div>
        </div>

        <!-- view Challan -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto" >
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">Silent Challan</h2>
              </div>
            </div>
            <div class="row stc-view-product-row">
              <div class="col-xl-12 col-lg-12 col-md-12">
                <form action="" class="stc-silent-challan-form">
                    <table class="table table-hover ">
                      <thead>
                        <tr>
                          <th scope="col">From/<br>To</th>
                          <th scope="col" width="30%">By Challan Number, Customer Name, Customer Contact, Status, Deliver through</th>
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
                            <p><a href="#" id="challandatefilt">
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
                            <button type="button" name="challansearch" class="btn btn-primary" id="challansearch">Search</button>
                          </td>
                          <td>
                            <input
                              id="stcfilterbypdnamec"
                              type="text"
                              placeholder="Product Name"
                              class="form-control validate"
                              required
                            />
                          </td>
                          <td>
                            <a class="btn btn-success searchbycpdnamehit" >Search <i class="fa fa-search"></i></a>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                </form>
              </div>
            </div>
            <div class="row stc-view-sale-row" style="width: auto;overflow-x: auto; white-space: nowrap;">
              <div class="col-xl-12 col-lg-12 col-md-12">
                <form action="" class="stc-view-silent-challan-row-fetch">
                    <table class="table table-hover ">
                      <tr><td>Loading....</td></tr>
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

        $('.close-icon').on('click', function(e){
          e.preventDefault();
            $('.thishypo').fadeOut(500);
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
        function load_data(query, challanbegdate, challanenddate) {
          $.ajax({
            url:"asgard/givesilentchallan.php",
            method:"POST",
            data:{
              stcfilterchallan:query,
              challanbegdate:challanbegdate,
              challanenddate:challanenddate
            },
            dataType:"json",
            success:function(data){
              // console.log(data);
              // $('#total_records').text(data.length);
             $('.stc-view-silent-challan-row-fetch').html(data);
            }
          });
        }

        $('#challansearch').click(function(){
          var query = $('#tags').val();
          var challanbegdate=$('.begdate').val();
          var challanenddate=$('.enddate').val();
          load_data(query, challanbegdate, challanenddate);
        });

        // challan input
        function load_challan_items(query) {
          $.ajax({
            url:"asgard/givesilentchallan.php",
            method:"POST",
            data:{search_sale_pd_name_in:query},
            dataType:"json",
            success:function(data){
              // console.log(data);
              // $('#total_records').text(data.length);
              $('.thishypo').html(data);
            }
          });
        }

        $('#challanitemsearch').click(function(){
          var query = $('#challanitemstags').val();
          load_challan_items(query);
        });
      });
    </script>
    <script>
      $(document).ready(function(){ 
        var todaydate = new Date();
        var day = todaydate.getDate();
        var month = todaydate.getMonth() - 3;
        var nmonth = todaydate.getMonth();
        var year = todaydate.getFullYear();
        var begdateon = year + "/" + month + "/" + day;
        var enddateon = year + "/" + nmonth + "/" + day;

        // Sale Order
        stc_call_sale_orders(begdateon, enddateon);
        function stc_call_sale_orders(begdateon, enddateon){
          var begdate=begdateon;
          var enddate=enddateon; 
          $.ajax({
            url:"asgard/givesilentchallan.php", 
            method:'POST',
            data:{
              stccallss:1,
              begdate:begdate,
              enddate:enddate
            },
            dataType: 'JSON',
            success:function(data){
              // console.log(data);
                $('.stc-view-silent-challan-row-fetch').html(data);
            }
          });     
        }

        // show sale order items sesson cart
        silent_challan_cart();
        function silent_challan_cart(){
          $.ajax({
            url:"asgard/givesilentchallan.php", 
            method:'POST',
            data:{stc_show_sale_sess:1},
            dataType: 'JSON',
            success:function(data){
              // console.log(data);
                $('.silent-challan-show-prod').html(data["orderTable"]);
            }
          });
        }

        // add cart to sale session
        $('body').delegate('.add_to_silent_cart','click',function(e){
         e.preventDefault();
          var product_id = $(this).attr("id");  
          var product_name = $('#stcpopdname'+product_id).val(); 
          var product_unit = $('#stcpopdunit'+product_id).val();  
          var product_quantity = $('#stcpoqty'+product_id).val();  
          var product_price = $('#stcpdprice'+product_id).val();
          var product_saleperc = $('#stcpdsaleperc'+product_id).val();
          var product_hsncode = $('#stcpopdhsncode'+product_id).val();
          var product_gst = $('#stcpopdgst'+product_id).val();
          var product_invent = $('#stcpdinvent'+product_id).val();
          var add_to_sale_cart="addsalecart";
            $.ajax({  
              url:"asgard/givesilentchallan.php",  
              method:"POST",
              data:{  
                  product_id:product_id,
                  product_name:product_name,  
                  product_unit:product_unit,
                  product_quantity:product_quantity,
                  product_price:product_price,
                  product_sale_percent:product_saleperc,
                  product_hsncode:product_hsncode,
                  product_gst:product_gst,
                  add_sale:add_to_sale_cart  
              },  
              success:function(data){
                silent_challan_cart();
                alert(data);
                // console.log(data);                              
              }  
            });  
        });

        // quantity change Sale product session
        $(document).on('click', '.stcqtysilent', function(e){  
          e.preventDefault();
            var product_id = $(this).data("product_id");  
            var quantity = $('#stcqtysc'+product_id).val();   
            // var change_cart = $('#cartprice'+product_id).val();
            var action = "quantity_change"; 
            if(quantity != 0){  
              $.ajax({  
                  url:"asgard/givesilentchallan.php",
                  method:"POST",  
                  data:{
                    product_id:product_id, 
                    quantity:quantity, 
                    sale_quantity_action:action
                  },  
                  success:function(data){ 
                    silent_challan_cart();
                    // console.log(data);
                  }  
              });  
            }else{
              alert("You cidk!!! Enter quantity greater than 0.");
            }
        }); 

        // Rate change Sale product session
        $(document).on('click', '.stcratesilent', function(e){  
          e.preventDefault();
            var product_id = $(this).data("product_id");  
            var price = $('#stcratesc'+product_id).val();   
            // var change_cart = $('#cartprice'+product_id).val();
            var action = "rate_change"; 
            if(price != 0){  
              $.ajax({  
                    url:"asgard/givesilentchallan.php",
                    method:"POST",  
                    data:{
                        product_id:product_id, 
                        price:price, 
                        stc_price_action:action
                    },  
                    success:function(data){ 
                     silent_challan_cart();
                     // console.log(data);
                    }  
              });  
            }else{
              alert("You cidk!!! Enter price greater than 0.");
            }
        }); 

        // delete from cart purchase product session
        $('body').delegate('.stcdelsilentbtn','click',function(e){
          e.preventDefault();
          var product_id = $(this).attr("id");
            if(confirm("Are you sure you want to remove this product?")){   
              $.ajax({  
                url:"asgard/givesilentchallan.php",  
                method:"POST",
                data:{  
                    product_id:product_id,
                    stcdelsalelinei:1  
                },  
                success:function(data){  
                  silent_challan_cart();
                  alert(data);                        
                }  
              });  
            }         
        });

        // calculate charges to sale order
        $(document).on('click', '.stcsalec', function(e){  
          e.preventDefault();
          var freightcharge=$('.stcfc').val();
          var packingandforwardingcharge=$('.stcpf').val();
          var grand_total=$('#stc_grand_offset_table_value').val();
          $.ajax({  
            url:"asgard/givesilentchallan.php", 
            method:"POST",  
            data:{
              freightcharge:freightcharge,
              packingandforwardingcharge:packingandforwardingcharge,
              grand_total:grand_total,
              do_plus_minus_on_sale:1
            },  
            // dataType: `JSON`,
            success:function(data){ 
              $('#stc_final_sale_value').html(data);
            }  
          });     
        });

        // save sale order to db
        $(document).on('click', '.stcsavesilentchallan', function(e){
          e.preventDefault();
          var challandate             = $('.silentchallandate').val();
          var customername            = $('.silentchallancustomername').val();
          var order_billaddress       = $('#silentchallancustomernilladdress').val();
          var order_shipaddress       = $('#silentchallancustomershippaddress').val();
          var order_contnumber        = $('.silentchallancustomercontactnumber').val();
          var order_orderon           = $('.silentchallancustomerorderon').val();
          var order_deliveron         = $('.silentchallancustomerdeliveredon').val();
          var order_orderby           = $('.silentchallanorderby').val();
          var order_deliverthrough    = $('.silentchallandelevredthrough').val();
          var order_warrantystatus    = $('.silentchallanwarrantystatus').val();
          var order_stcfc             = $('.stcfc').val();
          var order_stcpf             = $('.stcpf').val();
          var order_notes             = $('#silentchallannotes').val();
          $.ajax({  
            url:"asgard/givesilentchallan.php",
            method:"POST",  
            data:{
              silent_challandate           : challandate,
              silent_customername          : customername,
              silent_order_billaddress     : order_billaddress,
              silent_order_shipaddress     : order_shipaddress,
              silent_order_contnumber      : order_contnumber,
              silent_order_orderon         : order_orderon,
              silent_order_deliveron       : order_deliveron,
              silent_order_orderby         : order_orderby,
              silent_order_deliverthrough  : order_deliverthrough,
              silent_order_warrantystatus  : order_warrantystatus,
              silent_order_stcfc           : order_stcfc,
              silent_order_stcpf           : order_stcpf,
              silent_order_notes           : order_notes,
              save_sale_action             : 1
            },
            // dataType: `JSON`,
            success:function(data){
             // console.log(data);
              alert(data);
              $('.stc-add-sale-product-form')[0].reset();
              window.location.reload();
              $('.stc-add').fadeOut(500);
              $('.stc-view').toggle(1000);
            }
          });
        });


      });
    </script>
  </body>
</html>