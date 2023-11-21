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
    <title>Challan - STC</title>
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
                    <li class="breadcrumb-item active"><a href="#" class="active" value="">Challan</a></li>
                  </ol>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto animated zoomInDown">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <!-- <label class="btn btn-default text-uppercase"><a href="#" class="btn btn-success btn-block text-uppercase add-combo" value=""> With GST</a></label> -->
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <label class="btn btn-default text-uppercase"><a href="#" class="btn btn-success btn-block text-uppercase add-combo" value=""> <b>+</b> Create New Challan</a></label>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row animated zoomInUp">
        <!-- Create order -->
        <div style="display: none;" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-add">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">Sale Point</h2> <a id="stc_uppp" href="#"><i class="fas fa-arrow-up"></i></a>
              </div>
            </div>
            <div class="row stc-purchase-point-row">
              <div class="col-xl-6 col-md-6 col-sm-12">
                <form action="" class="stc-add-sale-product-form"> 
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >Challan Number
                  </label>
                  <input
                    id="gtonumbershow"
                    name="stcmername"
                    type="text"
                    placeholder="Sale Order Number"
                    class="form-control validate"
                    disabled
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Challan Date
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
                    >Customer Name
                  </label>
                  <select
                    id="stc_customer_sale_product"
                    class="custom-select stc-select-customer"
                    name="stcvendor"
                  >
                  </select>
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Customer Order Number
                  </label>
                  <input
                    name="saleordercustordernumber"
                    type="text"
                    class="form-control validate saleordercustordernumber"
                    placeholder="Customer Order Number"
                    value="Verbal"
                    style="background-color: #186abb;"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Customer Order Date
                  </label>
                  <input
                    id="datepicke1r"
                    name="saleordercustorderdate"
                    type="date"
                    min="2001-01-01" 
                    max="2050-12-31"
                    placeholder="DD/MM/YYYY"
                    class="form-control validate saleordercustorderdate"
                    style="background-color: #186abb;"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Way Bill NO
                  </label>
                  <input
                    name="saleorderwaybillno"
                    type="text"
                    class="form-control validate saleorderwaybillno"
                    placeholder="Way Bill No"
                    style="background-color: #186abb;"
                    value="NA"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >LR No
                  </label>
                  <input
                    name="saleorderlrno"
                    type="text"
                    class="form-control validate saleorderlrno"
                    placeholder="LR No"
                    style="background-color: #186abb;"
                    value="NA"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Date of Supply
                  </label>
                  <input
                    name="saleorderdatesupply"
                    type="text"
                    class="form-control validate saleorderdatesupply"
                    placeholder="Date of Supply"
                    style="background-color: #186abb;"
                    value="NA"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Place of Supply
                  </label>
                  <input
                    name="saleorderplacesupply"
                    type="text"
                    class="form-control validate saleorderplacesupply"
                    placeholder="Place of Supply"
                    style="background-color: #186abb;"
                    value="NA"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Order Refrence
                  </label>
                  <input
                    name="saleorderordrefrence"
                    type="text"
                    class="form-control validate saleorderordrefrence"
                    placeholder="Customer Refrence"
                    style="background-color: #186abb;"
                    value="Verbal"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Site Name
                  </label>
                  <input
                    name="saleordercustsitename"
                    type="text"
                    class="form-control validate saleordercustsitename"
                    placeholder="Site Name"
                    style="background-color: #186abb;"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Contact Person
                  </label>
                  <input
                    name="saleordercustcontperson"
                    type="text"
                    class="form-control validate saleordercustcontperson"
                    placeholder="Contact Person"
                    style="background-color: #186abb;"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Contact Number
                  </label>
                  <input
                    name="saleordercustcontnumber"
                    type="text"
                    class="form-control validate saleordercustcontnumber"
                    placeholder="Contact Number"
                    style="background-color: #186abb;"
                  />
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Address
                  </label>
                  <textarea
                    class="form-control validate"
                    rows="2"
                    id="stcsoshipaddress"
                    placeholder="Shipping Address"
                    required
                  ></textarea>
                </div>
              </div>
              <div class="col-xl-6 col-md-6 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Terms & Condition
                  </label>
                  <textarea
                    class="form-control validate"
                    rows="2"
                    id="stcsotandc"
                    placeholder="Terms & Condition"
                    required
                  ></textarea>
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
              <div class="col-xl-12 col-md-12 col-sm-12 saleflase">
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
                    id="stcpdnotes"
                    placeholder="Notes"
                    required
                  ></textarea>
                </div>
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block text-uppercase stcsavesaleorder">Save</button>
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
                <h2 class="tm-block-title d-inline-block">Challan</h2>
              </div>
            </div>
            <div class="row stc-view-product-row">
              <div class="col-xl-12 col-lg-12 col-md-12">
                <form action="" class="stc-view-product-form">
                    <table class="table table-hover ">
                      <thead>
                        <tr>
                          <th scope="col">From/<br>To</th>
                          <th scope="col" width="30%">By Challan Number, Customer Name, Customer Site, Status</th>
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
                <form action="" class="stc-view-Sale-order-form">
                    <table class="table table-hover ">
                      <tr><td>Loading....</td></tr>
                    </table>
                </form>
              </div>
            </div>

            <div class="row stc-call-view-Merchant-row">
              
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
            url:"asgard/sale_order.php",
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
             $('.stc-view-Sale-order-form').html(data);
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
            url:"asgard/sale_order.php",
            method:"POST",
            data:{search_sale_pd_name_in:query},
            dataType:"json",
            success:function(data){
              // console.log(data);
              // $('#total_records').text(data.length);
              $('.stc-call-view-sale-product-row').html(data);
            }
          });
        }

        $('#challanitemsearch').click(function(){
          var query = $('#challanitemstags').val();
          load_challan_items(query);
        });
      });
    </script>
  </body>
</html>