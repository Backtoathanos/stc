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
    <title>Administration - STC</title>
    <link rel = "icon" type = "image/png" href="img/stc_logo_title.png">
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Roboto:400,700" />
    <!-- https://fonts.google.com/specimen/Roboto -->
    <link rel="stylesheet" href="css/fontawesome.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-usertagsinput/0.8.0/bootstrap-usertagsinput.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-usertagsinput/0.8.0/bootstrap-usertagsinput-typeahead.css" />
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
      .bootstrap-usertagsinput {
       width: 100%;
       color: blue;
      }
      .bootstrap-usertagsinput .tag {
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
                    <li class="breadcrumb-item active"><a href="#" class="active" value="">Administration</a></li>
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
                <label class="btn btn-default text-uppercase"><a href="#" class="btn btn-success btn-block text-uppercase add-combo" value=""> <b>+</b>Create New Admin</a></label>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <!-- Create user -->
        <div style="display: none;" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-add">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">Admin Point</h2> <a id="stc_uppp" href="#"><i class="fas fa-arrow-up"></i></a>
              </div>
            </div>
            <div class="row stc-purchase-point-row">
              <div class="col-xl-6 col-md-6 col-sm-6">
                <form action="" class="stc-add-user-form"> 
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >User Name
                  </label>
                  <input
                    id="stc_user_name"
                    name="stc_user_name"
                    type="text"
                    placeholder="User Name"
                    class="form-control validate"
                  />
                </div>
              </div>
              <div class="col-6">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Phone Number
                  </label>
                  <input
                    id="stc_user_phone_number"
                    name="stc_user_phone_number"
                    type="number"
                    class="form-control validate"
                    data-large-mode="true"
                  />
                </div>
              </div>
              <div class="col-6">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >WhatsApp Number
                  </label>
                  <input
                    id="stc_user_whats_number"
                    name="stc_user_whats_number"
                    type="number"
                    class="form-control validate"
                    data-large-mode="true"
                  />
                </div>
              </div>
              <div class="col-6">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >User Email
                  </label>
                  <input
                    id="stc_user_email"
                    name="stc_user_email"
                    type="email"
                    class="form-control validate"
                    data-large-mode="true"
                  />
                </div>
              </div>
              <div class="col-xl-12 col-md-12 col-sm-12">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >User Address
                  </label>
                  <textarea
                    class="form-control validate"
                    rows="2"
                    id="stc_user_address"
                    placeholder="User Address"
                    required
                  >
                  </textarea>
                </div>
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block text-uppercase stcsaveuser">Save</button>
              </div>
            </div>
          </form>
          </div>
        </div>

        <!-- view user -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto stc-view" >
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto" >
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">Administration</h2>
              </div>
            </div>
            <div class="row stc-view-product-row">
              <div class="col-xl-12 col-lg-12 col-md-12">
                <form action="" class="stc-view-product-form">
                    <table class="table table-hover ">
                      <thead>
                        <tr>
                          <th scope="col">By User Id/ User Name/ Phone Number</th>
                          <th scope="col">search</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>
                            <input
                              id="usertags"
                              data-role="usertagsinput"
                              type="text"
                              placeholder="PO Number/ Merchant/ Status"
                              class="form-control validate stcfilterbyponumber"
                              required
                            />
                          </td>
                          <td>
                            <button type="button" name="search" class="btn btn-primary" id="usersearch">search</button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                </form>
              </div>
            </div>
            <div class="row stc-view-purchase-row" style="overflow-x: auto; white-space: nowrap;">
              <div class="col-xl-12 col-lg-12 col-md-12">
                <form action="" class="stc-call-view-user-row">
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
        // load user
        function load_data(query) {
          $.ajax({
            url:"asgard/stcusercheck.php",
            method:"POST",
            data:{
              search_user_name:query
            },
            dataType:"json",
            success:function(response_user){
              // console.log(data);
             $('.stc-call-view-user-row').html(response_user);
            }
          });
        }

        $('#usersearch').click(function(){
          var query = $('#usertags').val();           
          if(query==''){
            alert("date aur search field khaali mat de!!!");
          }else{
            load_data(query);
          }
        });

        stc_call_users();
        function stc_call_users(){
          $.ajax({
            url     : "asgard/stcusercheck.php",
            method  : "POST",
            data    : {stc_call_users_on_load:1},
            success : function(response_user){
              $('.stc-call-view-user-row').html(response_user);
            }
          });
        }

        // role changer
        $('body').delegate('.stc-user-role-it', 'click', function(e){
          e.preventDefault();
        });

        //save user
        $('body').delegate('.stcsaveuser', 'click', function(e){
          e.preventDefault();
          var user_name=$('#stc_user_name').val();
          var user_address=$('#stc_user_address').val();
          var user_phone_number=$('#stc_user_phone_number').val();
          var user_whats_number=$('#stc_user_whats_number').val();
          var user_email=$('#stc_user_email').val();
          $.ajax({
            url     : "asgard/stcusercheck.php",
            method  : "POST",
            data    : {
              stc_user_hit:1,
              user_name:user_name,
              user_address:user_address,
              user_phone_number:user_phone_number,
              user_whats_number:user_whats_number,
              user_email:user_email
            },
            success : function(response_user){
              var response=response_user.trim();
              if(response=="User added succefully."){
                alert(response);
                stc_call_users();
                $('.stc-view').toggle(1000);
                $('.stc-add').fadeOut(500);
                $('.stc-add-user-form')[0].reset();
              }else{
                alert(response_user);
              }
            }
          });
        });

        // change role
        $('body').delegate('.stc-user-role-it', 'click', function(e){
          e.preventDefault();
          var uid=$(this).attr('id');
          $('.stc-role-for').val(uid);
          $(".role-pre-val").prop('checked', false);
          $.ajax({
              url     : "asgard/stcusercheck.php",
              method  : "POST",
              data    : {
                stc_roles_privilege_get:1,
                uid:uid
              },
              dataType : "JSON",
              success : function(roles_res){
                for(var i=0;i<roles_res.length;i++){
                  $('input[type="checkbox"].role-pre-val[value="' + roles_res[i].stc_user_role_privilege_id + '"]').prop('checked', true);
                }
                $('.res-user-role-Modal').modal('show');
              }
            });
         
        });

        // user role hit
        $('body').delegate('.stcuserroleprehit', 'click', function(e){
          e.preventDefault();
          var uid=$('.stc-role-for').val();
          var role=$('.stc-role-pre-value').val();
          $.ajax({
            url     : "asgard/stcusercheck.php",
            method  : "POST",
            data    : {
              stc_user_role_hit:1,
              uid:uid,
              role:role
            },
            success : function(response_role){
              alert(response_role);
              $('.res-user-role-Modal').modal("hide");
              stc_call_users();
            }
          });
        });

        // user role previllage table hit
        $('body').delegate('.stc-role-privilege-save', 'click', function(e){
          e.preventDefault();
          var user_id=$(".stc-role-for").val();
          var roles_val=[];
          $(".role-pre-val").each(function(){
            if($(this).is(":checked")){
              roles_val.push($(this).val());
            }
          });

          if(roles_val.length == 0){
            alert("Empty roles cannot be acceptable.");
          }else{
            $.ajax({
              url     : "asgard/stcusercheck.php",
              method  : "POST",
              data    : {
                stc_roles_privilege:1,
                user_id:user_id,
                roles_val:roles_val
              },
              success : function(roles_res){
                alert(roles_res);
              }
            });
          }
        });
      });
    </script>
  </body>
</html>

<div class="modal fade bd-example-modal-lg res-user-role-Modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add Product</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
            <div class="row">
              <div class="col-10">
                <select class="form-control stc-role-pre-value">
                  <option value="1">View</option>
                  <option value="2">Create</option>
                  <option value="3">Edit</option>
                  <option value="4">Accounts</option>
                  <option value="5">Boss</option>
                  <option value="6">All</option>
                </select>
              </div>
              <div class="col-2">
                <input type="hidden" class="stc-role-for">
                <a href="#" class="form-control btn btn-primary stcuserroleprehit">Update <i class="fa fa-search"></i></a>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">            
            <ul class="vertical-nav-menu">
                <li>
                    <a href="dashboard.php" class="mm-active">
                        <i class="metismenu-icon pe-7s-home"></i>
                        Home
                    </a>
                </li>                    
                <!-- Accounts -->
                <li>
                    <a href="#" class="mm-active">
                        <i class="metismenu-icon pe-7s-server"></i>
                        Accounts
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul>
                        <li>
                            <a href="deposit.php">
                                <i class="metismenu-icon"></i>
                                Deposit
                            </a>
                            <br>
                            View<input type="checkbox" class="role-pre-val" value="201">
                            Create<input type="checkbox" class="role-pre-val" value="202">
                        </li>
                        <li>
                            <a href="payment-request.php">
                                <i class="metismenu-icon"></i>
                                Payment Request
                            </a>
                            <br>
                            View<input type="checkbox" class="role-pre-val" value="203">
                            Create<input type="checkbox" class="role-pre-val" value="204">
                            Process<input type="checkbox" class="role-pre-val" value="205">
                        </li>
                        <li>
                            <a href="payment-process.php">
                                <i class="metismenu-icon"></i>
                                Payment Process
                            </a>
                            <br>
                            View<input type="checkbox" class="role-pre-val" value="206">
                            Create<input type="checkbox" class="role-pre-val" value="207">
                        </li>
                        <li>
                            <a href="payment-adjustment.php">
                                <i class="metismenu-icon"></i>
                                Adjustments
                            </a>
                            <br>
                            View<input type="checkbox" class="role-pre-val" value="208">
                            Create<input type="checkbox" class="role-pre-val" value="209">
                        </li>
                        <li>
                            <a href="na.php">
                                <i class="metismenu-icon"></i>
                                Account Register
                            </a>
                            <br>
                            View<input type="checkbox" class="role-pre-val" value="210">
                        </li>
                    </ul>
                </li>
                <!-- Master -->
                <li>
                    <a href="#" class="mm-active">
                        <i class="metismenu-icon pe-7s-diamond"></i>
                        Master
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul>
                        <li>
                            <a href="add-product.php">
                                <i class="metismenu-icon"></i>
                                Add Product
                            </a>
                            <br>
                            View<input type="checkbox" class="role-pre-val" value="301">
                            Create<input type="checkbox" class="role-pre-val" value="302">
                            Edit<input type="checkbox" class="role-pre-val" value="303">
                        </li>
                        <li>
                            <a href="add-merchant.php">
                                <i class="metismenu-icon"></i>
                                Add Merchant
                            </a>
                            <br>
                            View<input type="checkbox" class="role-pre-val" value="304">
                            Create<input type="checkbox" class="role-pre-val" value="305">
                        </li>
                        <li>
                            <a href="add-customer.php">
                                <i class="metismenu-icon"></i>
                                Add Customer
                            </a>
                            <br>
                            View<input type="checkbox" class="role-pre-val" value="306">
                            Create<input type="checkbox" class="role-pre-val" value="307">
                        </li>
                        <li>
                            <a href="add-product-suppports.php">
                                <i class="metismenu-icon"></i>
                                Add Product Supports
                            </a>
                            <br>
                            Create<input type="checkbox" class="role-pre-val" value="308">
                        </li>
                        <li>
                            <a href="update-product-image.php">
                                <i class="metismenu-icon"></i>
                                Update Product Image
                            </a>
                            <br>
                            Edit<input type="checkbox" class="role-pre-val" value="309">
                        </li>
                    </ul>
                </li>
                <!-- Purchase -->
                <li>
                    <a href="#" class="mm-active">
                        <i class="metismenu-icon pe-7s-cart"></i>
                        Purchase
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul>
                        <li>
                            <a href="purchase-product.php">
                                <i class="metismenu-icon"></i>
                                Purchase Order
                            </a>
                            <br>
                            View<input type="checkbox" class="role-pre-val" value="401">
                            Create<input type="checkbox" class="role-pre-val" value="402">
                            Edit<input type="checkbox" class="role-pre-val" value="403">
                        </li>
                        <li>
                            <a href="purchase-product.php">
                                <i class="metismenu-icon"></i>
                                Purchase Order Adhoc
                            </a>
                            <br>
                            View<input type="checkbox" class="role-pre-val" value="407">
                            Create<input type="checkbox" class="role-pre-val" value="408">
                            Edit<input type="checkbox" class="role-pre-val" value="409">
                        </li>
                        <li>
                            <a href="purchase-payments.php">
                                <i class="metismenu-icon"></i>
                                Purchase Order Payments
                            </a>
                            <br>
                            View<input type="checkbox" class="role-pre-val" value="404">
                            Create<input type="checkbox" class="role-pre-val" value="405">
                        </li>
                        <li>
                            <a href="inventory.php">
                                <i class="metismenu-icon"></i>
                                Inventory
                            </a>
                            <br>
                            View<input type="checkbox" class="role-pre-val" value="406">
                        </li>
                    </ul>
                </li>
                <!-- Sale -->
                <li>
                    <a href="#" class="mm-active">
                        <i class="metismenu-icon pe-7s-credit"></i>
                        Sale
                        <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
                    </a>
                    <ul>
                        <li>
                            <a href="quote-quotation.php">
                                <i class="metismenu-icon"></i>
                                Quotation
                            </a>
                            <br>
                            View<input type="checkbox" class="role-pre-val" value="501">
                            Create<input type="checkbox" class="role-pre-val" value="502">
                        </li>
                        <li>
                            <a href="standard-challan.php">
                                <i class="metismenu-icon"></i>
                                Standard Challan
                            </a>
                            <br>
                            View<input type="checkbox" class="role-pre-val" value="503">
                            Create<input type="checkbox" class="role-pre-val" value="504">
                            Edit<input type="checkbox" class="role-pre-val" value="505">
                        </li>
                        <li>
                            <a href="direct-challan.php">
                                <i class="metismenu-icon"></i>
                                Direct Challan
                            </a>
                            <br>
                            View<input type="checkbox" class="role-pre-val" value="506">
                            Create<input type="checkbox" class="role-pre-val" value="507">
                            Edit<input type="checkbox" class="role-pre-val" value="508">
                        </li>
                        <li>
                            <a href="virtual-challan.php">
                                <i class="metismenu-icon"></i>
                                Virtual Challan
                            </a>
                            <br>
                            View<input type="checkbox" class="role-pre-val" value="509">
                            Create<input type="checkbox" class="role-pre-val" value="510">
                            Edit<input type="checkbox" class="role-pre-val" value="511">
                        </li>
                        <li>
                            <a href="adjust-tax-invoice.php">
                                <i class="metismenu-icon"></i>
                                Adjust Tax Invoice
                            </a>
                            <br>
                            View<input type="checkbox" class="role-pre-val" value="512">
                            Create<input type="checkbox" class="role-pre-val" value="513">
                            Edit<input type="checkbox" class="role-pre-val" value="514">
                        </li>
                        <li>
                            <a href="additional-tax-invoice.php">
                                <i class="metismenu-icon"></i>
                                Additional Tax Invoice
                            </a>
                            <br>
                            View<input type="checkbox" class="role-pre-val" value="516">
                            Create<input type="checkbox" class="role-pre-val" value="517">
                            Edit<input type="checkbox" class="role-pre-val" value="518">
                        </li>
                        <li>
                            <a href="invoice-payment.php">
                                <i class="metismenu-icon"></i>
                                Invoice Payments
                            </a>
                            <br>
                            View<input type="checkbox" class="role-pre-val" value="519">
                            Create<input type="checkbox" class="role-pre-val" value="520">
                            Edit<input type="checkbox" class="role-pre-val" value="521">
                        </li>
                        <li>
                            <a href="sale-purchase-register.php">
                                <i class="metismenu-icon"></i>
                                Sale Purchase Register
                            </a>
                            <br>
                            View<input type="checkbox" class="role-pre-val" value="522">
                        </li>
                    </ul>
                </li>
                <!-- Reports -->
                <li>
                    <a href="reports.php" class="mm-active">
                        <i class="metismenu-icon pe-7s-note2"></i>
                        Reports
                    </a>
                </li>
                <!-- Reports -->
                <li>
                    <a href="reports.php" class="mm-active">
                        <i class="metismenu-icon pe-7s-note2"></i>
                        Orders & requisitions
                    </a>
                    <br>
                    View<input type="checkbox" class="role-pre-val" value="601">
                    Create<input type="checkbox" class="role-pre-val" value="602">
                </li>
            </ul>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" style="float: left;" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary stc-role-privilege-save" style="float: right;">Save</button>
      </div>
    </div>
  </div>
</div>