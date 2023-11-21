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
    <title>Add Merchant - STC</title>
    <link rel = "icon" type = "image/png" href="img/stc_logo_title.png">
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Roboto:400,700" />
    <!-- https://fonts.google.com/specimen/Roboto -->
    <link rel="stylesheet" href="css/fontawesome.min.css" />
    <!-- https://fontawesome.com/ -->
    <link rel="stylesheet" href="jquery-ui-datepicker/jquery-ui.min.css" type="text/css" />
    <!-- http://api.jqueryui.com/datepicker/ -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <!-- https://getbootstrap.com/ -->
    <link rel="stylesheet" href="css/templatemo-style.css">
    <link rel="stylesheet" href="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/infinite.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
  </head>

  <body>
   <?php include "header.php";?>

    <div class="container tm-mt-big tm-mb-big">
      <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                <div class="page-title-right">
                  <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="erp.php" class="text-uppercase" value="">ERP</a></li>
                    <li class="breadcrumb-item active"><a href="#" class="active" value="">Customer Management</a></li>
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
                <label class="btn btn-default text-uppercase"><a href="#" class="btn btn-success btn-block text-uppercase add-customer-call" value=""> Add NEW CUSTOMER</a></label>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <label class="btn btn-default text-uppercase"><a href="#" class="btn btn-success btn-block text-uppercase view-customer-call" value=""> View All CUSTOMERS</a></label>
              </div>
            </div>
            <div class="row">
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <label class="btn btn-default text-uppercase"><a href="#" class="btn btn-success btn-block text-uppercase add-user-details-call" value=""> Add User details</a></label>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                <label class="btn btn-default text-uppercase"><a href="#" class="btn btn-success btn-block text-uppercase set-customer-user-call" value=""> Set Customer to user</a></label>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <!-- add Merchant -->
        <div style="display: none;" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto add-customer-response">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">Add Customer</h2>
              </div>
            </div>
            <div class="row stc-edit-Customer-row">
              <div class="col-xl-6 col-lg-6 col-md-12">
                <form action="" class="add-customer-response-customer-form">
                  <div class="form-group mb-3">
                    <label
                      for="name"
                      >Customer Name
                    </label>
                    <input
                      rel="name"
                      name="stccustname"
                      type="text"
                      placeholder="Customer Name"
                      class="form-control validate"
                      required
                    />
                  </div>
                  <div class="form-group mb-3">
                    <label
                      for="address"
                      >Customer Address</label
                    >
                    <textarea
                      class="form-control validate"
                      rows="2"
                      name="stccustaddress"
                      placeholder="Customer Address"
                      required
                    ></textarea>
                  </div>
                  <div class="form-group mb-3">
                    <label
                      for="city"
                      >Customer City</label
                    >
                    <select
                      class="custom-select tm-select-accounts call_city"
                      name="stcmercity"
                    >
                    </select>
                  </div>
                  <div class="form-group mb-3">
                    <label
                      for="contactperson"
                      >Contact Person
                    </label>
                    <input
                      rel="name"
                      name="stccustcontperson"
                      type="text"
                      placeholder="Customer Contact Person"
                      class="form-control validate"
                      required
                    />
                  </div>                  
                  <div class="form-group mb-3">
                    <label
                      for="rack"
                      >Customer Contact Number</label
                    >
                    <input
                      rel="name"
                      name="stccustcontnumber"
                      type="number"
                      placeholder="Customer Phone Number"
                      class="form-control validate"
                      required
                    />
                    </select>

                    <input type="hidden" name="stc_add_customer_hit">
                  </div>
                  <div class="form-group mb-3">
                    <label
                      for="knownfor"
                      >Customer PAN</label
                    >
                    <input
                      rel="name"
                      name="stccustpan"
                      type="text"
                      placeholder="Customer PAN"
                      class="form-control validate"
                      required
                    />
                  </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-12 mx-auto mb-4">
                <div class="tm-product-img-dummy mx-auto">
                  <i
                    class="fas fa-cloud-upload-alt tm-upload-icon"
                    onclick="document.getElementById('fileInput').click();"
                  ></i>
                </div>
                <div class="custom-file mt-3 mb-3">
                  <input id="fileInput" type="file" name="stccustimage" style="display:none;" />
                  <input
                    type="button"
                    class="btn btn-primary btn-block mx-auto"
                    value="UPLOAD CUSTOMER IMAGE"
                    onclick="document.getElementById('fileInput').click();"
                  />
                </div>
                <div class="form-group mb-3">
                  <label
                    for="state"
                    >Customer State</label
                  >
                  <select
                    class="custom-select tm-select-accounts call_state"
                    name="stccuststate"
                  >
                  </select>
                </div>
                <div class="form-group mb-3">
                  <label
                    for="email"
                    >Customer Email</label
                  >
                  <input
                    rel="name"
                    name="stccustemail"
                    type="email"
                    placeholder="Customer Email"
                    class="form-control validate"
                    required
                  />
                </div>
                <div class="form-group mb-3">
                  <label
                    for="knownfor"
                    >Customer Specially Known For</label
                  >
                  <input
                    rel="name"
                    name="stccustskf"
                    type="text"
                    placeholder="Customer Specially Known For"
                    class="form-control validate"
                    required
                  />
                </div>
                <div class="form-group mb-3">
                  <label
                    for="knownfor"
                    >Customer GSTIN</label
                  >
                  <input
                    rel="name"
                    name="stccustgstin"
                    type="text"
                    placeholder="Customer GSTIN"
                    class="form-control validate"
                    required
                  />
                </div>
              </div>
              
              </div>
              <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block text-uppercase">Add Customer Now</button>
              </div>
            </form>
            </div>
          </div>
        </div>

        <!-- view Merchant -->
        <div style="display: none;" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto view-customer-response">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">View Merchants</h2>
              </div>
            </div>
            <div class="row view-customer-response-Merchant-row">
              <div class="col-xl-12 col-lg-12 col-md-12">
                <form action="" class="view-customer-response-Merchant-form">
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
                              id="searchbystccustname"
                              name="stccustname"
                              type="text"
                              placeholder="Customer Name"
                              class="form-control validate"
                              required
                            />
                          </td>
                          <td>
                            <input
                              id="searchbystccustskf"
                              name="stcmerskf"
                              type="text"
                              placeholder="Customer Known FOr"
                              class="form-control validate"
                              required
                            />
                          </td>
                          <!-- <td>28 March 2019</td> -->
                          <!-- <td></td> -->
                        </tr>
                      </tbody>
                    </table>
                </form>
              </div>
            </div>

            <div class="row stc-call-view-Customer-row">
              
            </div>
          </div>
        </div>

        <!-- Agent Details -->
        <div style="display: none;" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto add-user-details-response">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">Register User</h2>
              </div>
            </div>
            <div class="row">
              <div class="col-xl-6 col-lg-6 col-md-12">
                <form action="" class="add-user-details-response-form">
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >User name
                  </label>
                  <input
                    id="stccustusername"
                    name="stccustusername"
                    type="text"
                    placeholder="Enter User Name"
                    class="form-control validate"
                    required
                  />
                </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-12">
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >User Email
                  </label>
                  <input
                    id="stccustuseremail"
                    name="stccustuseremail"
                    type="text"
                    placeholder="Enter User Email"
                    class="form-control validate"
                    required
                  />
                </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-12">
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >User Contact
                  </label>
                  <input
                    id="stccustusercontact"
                    name="stccustusercontact"
                    type="text"
                    placeholder="Enter User Contact"
                    class="form-control validate"
                    required
                  />
                </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-12">
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >User Pincode
                  </label>
                  <input
                    id="stccustuserpincode"
                    name="stccustuserpincode"
                    type="text"
                    placeholder="Enter User Pincode"
                    class="form-control validate"
                    required
                  />
                </div>
              </div>
              <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="form-group mb-3">
                  <label
                    for="name"
                    >User Address
                  </label>
                  <textarea
                    id="stccustuseraddress"
                    name="stccustuseraddress"
                    placeholder="Enter User Address"
                    class="form-control validate"
                    row="2"
                    required
                  ></textarea>
                </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-12">
                <div class="form-group mb-3">
                  <label
                    for="city"
                    >User City</label
                  >
                  <select
                    class="custom-select tm-select-accounts call_city"
                    name="stccustusercity"
                  >
                  </select>
                </div>
              </div>
              <div class="col-xl-6 col-lg-6 col-md-12">
                <div class="form-group mb-3">
                  <label
                    for="state"
                    >User State</label
                  >
                  <select
                    class="custom-select tm-select-accounts call_state"
                    name="stccustuserstate"
                  >
                  </select>
                </div>
              </div>
              <div class="col-12">
                <input type="hidden" name="stc_add_user_hit">
                <button type="submit" class="btn btn-primary btn-block text-uppercase">Add User Now</button>
              </div>
                </form>
            </div>
          </div>
        </div>

        <!-- Customer to user -->
        <div style="display: block;" class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto set-customer-user-response">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12">
                <h2 class="tm-block-title d-inline-block">Set Customer to User</h2>
              </div>
            </div>
            <div class="row">
              <div class="col-sm-6">
                <select
                    class="custom-select tm-select-accounts"
                    id="stcuserselection"
                >
                <?php
                  include_once("../MCU/db.php");
                  $user_query=mysqli_query($con, "SELECT * FROM `stc_agents` ORDER BY `stc_agents_name` ASC");
                  if(mysqli_num_rows($user_query)>0){
                    foreach($user_query as $userrow){
                      echo '<option value="'.$userrow['stc_agents_id'].'">'.$userrow['stc_agents_name'].'</option>';
                    }
                  }else{
                    echo '<option>No User Found!!!</option>';
                  }
                ?>                  
                </select>
              </div>    
              <div class="col-sm-6">
                <div class="col-sm-6">
                 <select
                    class="custom-select tm-select-accounts"
                    id="stccustselection"
                >
                <?php
                  include_once("../MCU/db.php");
                  $cust_query=mysqli_query($con, "SELECT * FROM `stc_customer` ORDER BY `stc_customer_name` ASC");
                  if(mysqli_num_rows($cust_query)>0){
                    foreach($cust_query as $userrow){
                      echo '<option value="'.$userrow['stc_customer_id'].'">'.$userrow['stc_customer_name'].'</option>';
                    }
                  }else{
                    echo '<option>No User Found!!!</option>';
                  }
                ?>                  
                </select>
              </div>    
              </div>   
              <div class="col-sm-12">
                <a type="submit" class="btn btn-primary btn-block text-uppercase settocustuser">Set</a>                
              </div>             
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php include "footer.php";?> 
    <script>
      $(document).ready(function(){
        $('.add-customer-call').on('click', function(e){
          e.preventDefault();
          $('.add-customer-response').toggle(1000);
          $('.view-customer-response').fadeOut(500);
          $('.add-user-details-response').fadeOut(500);
          $('.set-customer-user-response').fadeOut(500);
        });

        $('.view-customer-call').on('click', function(e){
          e.preventDefault();
          $('.add-customer-response').fadeOut(500);
          $('.view-customer-response').toggle(1000);
          $('.add-user-details-response').fadeOut(500);
          $('.set-customer-user-response').fadeOut(500);
        });

        $('.add-user-details-call').on('click', function(e){
          e.preventDefault();
          $('.add-customer-response').fadeOut(500);
          $('.view-customer-response').fadeOut(500);
          $('.add-user-details-response').toggle(1000);
          $('.set-customer-user-response').fadeOut(500);
        });

        $('.set-customer-user-call').on('click', function(e){
          e.preventDefault();
          $('.add-customer-response').fadeOut(500);
          $('.view-customer-response').fadeOut(500);
          $('.add-user-details-response').fadeOut(500);
          $('.set-customer-user-response').toggle(1000);
        });
      });

      $(document).ready(function(){
        $('.add-user-details-response-form').on("submit", function(e){
          e.preventDefault();
          $.ajax({
            url           : "asgard/mjolnir.php",
            method        : "post",
            data          : new FormData(this),
            contentType   : false,
            processData   : false,
            dataType      : 'JSON',
            success       : function (argument) {
              // console.log(argument);
                alert(argument);
              $('.add-user-details-response-form')[0].reset();
            }            
          });
        });

        $('body').delegate('.settocustuser', 'click', function(e){
          e.preventDefault();
          var userid=$('#stcuserselection').val();
          var custid=$('#stccustselection').val();
          $.ajax({
            url           : "asgard/mjolnir.php",
            method        : "post",
            data          : {
              userid:userid,
              custid:custid,
              stc_set_user_customer_hit:1
            },
            // dataType      : 'JSON',
            success       : function (argument) {
              console.log(argument);
                alert(argument);
            }            
          });
        });
      });
    </script>
  </body>
</html>