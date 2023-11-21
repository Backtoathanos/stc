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
    <title>Electronics User - STC</title>
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
                    <li class="breadcrumb-item active"><a href="#" class="active" value="">Electronics User</a></li>
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
                <label class="btn btn-default text-uppercase"><a href="#" class="btn btn-success btn-block text-uppercase add-combo" value=""> <b>+</b>Create New User</a></label>
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
                <h2 class="tm-block-title d-inline-block">Electronics User Point</h2> <a id="stc_uppp" href="#"><i class="fas fa-arrow-up"></i></a>
              </div>
            </div>
            <div class="row stc-purchase-point-row">
              <div class="col-xl-12 col-md-12 col-sm-12">
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
                    placeholder="Enter Email"
                    class="form-control validate"
                    data-large-mode="true"
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
                    placeholder="Enter Phone Number"
                    class="form-control validate"
                    data-large-mode="true"
                  />
                </div>
              </div>
              <div class="col-6">
                <div class="form-group mb-3">
                  <label
                    for="state"
                    >User City</label
                  >
                  <select
                    class="custom-select tm-select-accounts call_city"
                    name="stcmercity"
                  >
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group mb-3">
                  <label
                    for="state"
                    >User State</label
                  >
                  <select
                    class="custom-select tm-select-accounts call_state"
                    name="stcmerstate"
                  >
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >Pincode
                  </label>
                  <input
                    id="stc_user_pincode"
                    name="stc_user_pincode"
                    type="number"
                    placeholder="Enter Pincode"
                    class="form-control validate"
                    data-large-mode="true"
                  />
                </div>
              </div>
              <div class="col-6">
                <div class="form-group mb-3">
                  <label
                    for=""
                    >About User
                  </label>
                  <input
                    id="stc_user_aboutuser"
                    name="stc_user_aboutuser"
                    placeholder="Enter About User"
                    type="number"
                    class="form-control validate"
                    data-large-mode="true"
                  />
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
            <div class="row stc-view-purchase-row" style="width: 79.3em;overflow-x: auto; white-space: nowrap;">
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
              search_euser_name:query
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
            data    : {stc_call_eusers_on_load:1},
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
          var user_phone=$('#stc_user_phone_number').val();
          var user_email=$('#stc_user_email').val();
          var user_city=$('.call_city').val();
          var user_state=$('.call_state').val();
          var user_pincode=$('#stc_user_pincode').val();
          var user_about=$('#stc_user_aboutuser').val();
          $.ajax({
            url     : "asgard/stcusercheck.php",
            method  : "POST",
            data    : {
              stc_euser_hit:1,
              user_name:user_name,
              user_address:user_address,
              user_phone:user_phone,
              user_email:user_email,
              user_city:user_city,
              user_state:user_state,
              user_pincode:user_pincode,
              user_about:user_about
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
          $('.res-user-role-Modal').modal('show');
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
              stc_euser_role_hit:1,
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
                  <option value="1">Super Junior</option>
                  <option value="2">Junior</option>
                  <option value="3">Senior</option>
                </select>
              </div>
              <div class="col-2">
                <input type="hidden" class="stc-role-for">
                <a href="#" class="form-control btn btn-primary stcuserroleprehit">Update <i class="fa fa-search"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" style="float: left;" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>