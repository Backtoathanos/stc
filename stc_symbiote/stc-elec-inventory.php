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
    <title>Inventory - STC</title>
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput-typeahead.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">

    <!-- <style> .sp-quantity div { display: inline; }</style> -->
    <style>
      .bootstrap-tagsinput {
       width: 100%;
       color: blue;
      }
      .bootstrap-tagsinput .tag {
         color: black;
       }
    </style>
  </head>

  <body>
   <?php include "header.php";?>

    <div class="container-fluid tm-mt-big tm-mb-big animated fadeInSlow">
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="page-title-right">
                  <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="erp.php" class="text-uppercase" value="">ERP</a></li>
                    <li class="breadcrumb-item active"><a href="#" class="active" value="">Inventory</a></li>
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
                <label class="btn btn-default text-uppercase"><a href="#" class="btn btn-success btn-block text-uppercase add-combo" value=""> <b>+</b> Add Product to STC inventory</a></label>
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
                <h2 class="tm-block-title d-inline-block">STC Electronics Point</h2> <a id="stc_uppp" href="#"><i class="fas fa-arrow-up"></i></a>
              </div>
            </div>
            <div class="row stc-purchase-point-row">
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
                          id="stcelecshowinvtag"
                          type="text"
                          placeholder="Items Name"
                          class="form-control validate stcfilterbyponumber"
                          required
                        />
                      </td>
                      <td>
                        <button type="button" name="search" class="btn btn-primary" id="stcelecinvitemsearch">Search</button>
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
              <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="row stc-call-view-product-row">
                </div>
              </div>

              <!-- line item table -->
              <div class="col-xl-12 col-md-12 col-sm-12 stc-electron-invent-cart-out">
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block text-uppercase stcsaveelecinvhit">Save</button>
              </div>
            </div>
          </form>
          </div>
        </div>
        <!-- view Inventory -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">STC Electronics Inventory</h2>
              </div>
            </div>
            <div class="row stc-view-product-row">
              <div class="col-xl-12 col-lg-12 col-md-12">
                <form action="" class="stc-view-stc-elec-inventory-form">
                    <table class="table table-hover ">
                      <thead>
                        <tr>
                          <th scope="col" width="100%">Search By Material Name</th>
                          <th scope="col">Search</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>
                            <input
                              id="stcelecaddinvtags"
                              data-role="tagsinput"
                              type="text"
                              placeholder="Material Name/ Rack/ Category/ Sub Category"
                              class="form-control validate"
                              required
                            />
                          </td>
                          <td>
                            <button type="button" name="search" class="btn btn-primary" id="stc-elec-item-search-inv">Search</button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                </form>
              </div>
            </div>
            <div class="row stc-view-quote-row">
              <div class="col-xl-12 col-lg-12 col-md-12">
                <form action="" class="stc-elec-inventory-form">
                    <table class="table table-hover">
                      <thead class="text-danger">
                        <h1>
                          <b>
                            <th>#</th>
                            <th>Items</th>
                            <th>Category</th>
                            <th>Type</th>
                            <th>Qty</th>
                            <th>Tax</th>
                            <th>DP</th>
                            <th>MRP</th>
                            <th>Condition</th>
                            <th>SP</th>
                          </b>
                        </h1>
                      </thead>
                      <tbody>
                        <?php
                          include_once("../MCU/db.php");
                          if(empty($_GET['stc_elec_inv_pd_search'])){
                            $stcelectroinvquery=mysqli_query($con, "
                              SELECT * FROM `stc_electronics_inventory`
                              INNER JOIN `stc_product`
                              ON `stc_product_id`=`stc_electronics_inventory_item_id`
                              INNER JOIN `stc_sub_category`
                              ON `stc_sub_cat_id`=`stc_product_sub_cat_id`
                              INNER JOIN `stc_category`
                              ON `stc_cat_id`=`stc_product_cat_id`
                              WHERE `stc_electronics_inventory_item_qty`!=0
                            ");
                            $sl=1;

                            if(mysqli_num_rows($stcelectroinvquery)>0){
                              foreach($stcelectroinvquery as $invrow){
                                $condition='';
                                if($invrow['stc_electronics_inventory_condition']==1){
                                  $condition='GOOD';
                                }elseif($invrow['stc_electronics_inventory_condition']==2){
                                  $condition='BETTER';
                                }elseif($invrow['stc_electronics_inventory_condition']==3){
                                  $condition='BEST';
                                }else{
                                  $condition='NA';
                                }
                                echo "
                                  <tr>
                                    <td>".$sl."</td>
                                    <td>".$invrow['stc_product_name']."</td>
                                    <td>".$invrow['stc_cat_name']."</td>
                                    <td>".$invrow['stc_sub_cat_name']."</td>
                                    <td>
                                        ".number_format($invrow['stc_electronics_inventory_item_qty'], 2)."/
                                        ".$invrow['stc_product_unit']."
                                    </td>
                                    <td>".$invrow['stc_product_gst']."%</td>
                                    <td>".number_format($invrow['stc_electronics_inventory_dp'], 2)."</td>
                                    <td>".number_format($invrow['stc_electronics_inventory_mrp'], 2)."</td>
                                    <td>".$condition."</td>
                                    <td>".number_format($invrow['stc_electronics_inventory_sale_rate'], 2)."</td>
                                  </tr>
                                ";
                                $sl++;
                              }
                            }else{
                              echo "
                                  <tr>
                                    <td colspan='10' align='center'>No record found <b>:(<b></td>
                                  </tr>
                              ";
                            }
                          }else{
                            $stcelectroinvquery=mysqli_query($con, "
                              SELECT * FROM `stc_electronics_inventory`
                              INNER JOIN `stc_product`
                              ON `stc_product_id`=`stc_electronics_inventory_item_id`
                              INNER JOIN `stc_sub_category`
                              ON `stc_sub_cat_id`=`stc_product_sub_cat_id`
                              INNER JOIN `stc_category`
                              ON `stc_cat_id`=`stc_product_cat_id`
                              WHERE (`stc_product_name` REGEXP '".mysqli_real_escape_string($con, $_GET['stc_elec_inv_pd_search'])."'
                              OR `stc_product_desc` REGEXP '".mysqli_real_escape_string($con, $_GET['stc_elec_inv_pd_search'])."')
                              AND `stc_electronics_inventory_item_qty`!=0
                            ");
                            $sl=1;

                            if(mysqli_num_rows($stcelectroinvquery)>0){
                              foreach($stcelectroinvquery as $invrow){
                                $condition='';
                                if($invrow['stc_electronics_inventory_condition']==1){
                                  $condition='GOOD';
                                }elseif($invrow['stc_electronics_inventory_condition']==2){
                                  $condition='BETTER';
                                }elseif($invrow['stc_electronics_inventory_condition']==3){
                                  $condition='BEST';
                                }else{
                                  $condition='NA';
                                }
                                echo "
                                  <tr>
                                    <td>".$sl."</td>
                                    <td>".$invrow['stc_product_name']."</td>
                                    <td>".$invrow['stc_cat_name']."</td>
                                    <td>".$invrow['stc_sub_cat_name']."</td>
                                    <td>
                                        ".number_format($invrow['stc_electronics_inventory_item_qty'], 2)."/
                                        ".$invrow['stc_product_unit']."
                                    </td>
                                    <td>".$invrow['stc_product_gst']."%</td>
                                    <td>".number_format($invrow['stc_electronics_inventory_dp'], 2)."</td>
                                    <td>".number_format($invrow['stc_electronics_inventory_mrp'], 2)."</td>
                                    <td>".$condition."</td>
                                    <td>".number_format($invrow['stc_electronics_inventory_sale_rate'], 2)."</td>
                                  </tr>
                                ";
                                $sl++;
                              }
                            }else{
                              echo "
                                  <tr>
                                    <td colspan='10' align='center'>No record found <b>:(<b></td>
                                  </tr>
                              ";
                            }
                          }

                        ?>                                
                      </tbody>
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
            $('.stc-call-view-product-row').fadeOut(500);
            $('.upward').fadeOut(500);
        });

        $('.downward').on('click', function(e){
          e.preventDefault();
            $('.upward').toggle(500);
            $('.stc-call-view-product-row').toggle(500);
            $('.downward').fadeOut(500);
        });

        $( function() {
          // $( "#expire_date" ).datepicker();
        } );

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

        $('body').delegate('#stc-elec-item-search-inv', 'click', function(e){
          e.preventDefault();
          var text_value=$('#stcelecaddinvtags').val();
          window.location.href="stc-elec-inventory.php?stc_elec_inv_pd_search="+text_value;
        });
       
        // load_item_inv_cart();
        function load_item_inv_cart(query) {
          $.ajax({
            url:"asgard/givesilentchallan.php",
            method:"POST",
            data:{search_items_stc_add:query},
            // dataType:"json",
            success:function(data){
              // console.log(data);
              $('.stc-call-view-product-row').html(data);
            }
          });
        }

        $('#stcelecinvitemsearch').click(function(){
          var query = $('#stcelecshowinvtag').val();
          load_item_inv_cart(query);
          // alert(query);
        });

        load_elec_inv_cart();
        function load_elec_inv_cart() {
          $.ajax({
            url:"asgard/givesilentchallan.php",
            method:"POST",
            data:{call_stc_elec_inv:1},
            dataType:"json",
            success:function(data){
              // console.log(data);
              $('.stc-electron-invent-cart-out').html(data);
            }
          });
        }

        $('body').delegate('.add_to_elec_inv_cart', 'click', function(e){
          e.preventDefault();
          var pd_id=$(this).attr("id");
          var pd_price=$('#stcpdprice'+pd_id).val();
          var pd_dp=$('#stcelecinvdpprice'+pd_id).val();
          var pd_mrp=$('#stcelecinvmrpprice'+pd_id).val();
          var pd_cond=$('#stcpdcondition'+pd_id).val();
          var pd_qty=$('#stcelecinvqty'+pd_id).val();
          $.ajax({
            url     : "asgard/givesilentchallan.php",
            method  : "POST",
            data    : {
              omega_pd_id     :   pd_id,
              omega_pd_price  :   pd_price,
              omega_pd_dp     :   pd_dp,
              omega_pd_mrp    :   pd_mrp,
              omega_pd_cond   :   pd_cond,
              omega_pd_qty    :   pd_qty,
              omega_elec_hit  :   1
            },
            success : function(response){
              alert(response);
              load_elec_inv_cart();
            }
          });
        });

        $('body').delegate('.stcsaveelecinvhit', 'click', function(e){
          e.preventDefault();
          $.ajax({
            url     : "asgard/givesilentchallan.php",
            method  : "POST",
            data    : {
              omega_elec_save  :   1
            },
            dataType:"json",
            success : function(response){
              alert(response);
              load_elec_inv_cart();
            }
          });
        });
        

      });
    </script>
  </body>
</html>