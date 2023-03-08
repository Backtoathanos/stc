<?php  
session_start(); 
if(isset($_SESSION["stc_empl_id"]) && ($_SESSION["stc_empl_role"]>0)){ 
}else{ 
    header("Location:index.html"); 
} 
$page_code=306;
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
    <title>Add Customer - STC</title>
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
                                    <span>Add New Customer</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#tab-content-2">
                                    <span>View All Customers</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-3" data-toggle="tab" href="#tab-content-3">
                                    <span>Add Agent Details</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-4" data-toggle="tab" href="#tab-content-4">
                                    <span>Set Agent to Customer</span>
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
                                              >Add Customer
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                      <form action="" class="add-customer-response-customer-form">
                                      <div class="card-border mb-3 card card-body border-primary">
                                        <h5
                                          for="name"
                                          >Customer Name
                                        </h5>
                                        <input
                                          rel="name"
                                          name="stccustname"
                                          type="text"
                                          placeholder="Customer Name"
                                          class="form-control validate"
                                          required
                                        />
                                      </div>
                                    </div>
                                    <div class="col-md-12">
                                      <div class="card-border mb-3 card card-body border-primary">
                                        <h5
                                          for="address"
                                          >Customer Address</h5
                                        >
                                        <textarea
                                          class="form-control validate"
                                          rows="2"
                                          name="stccustaddress"
                                          placeholder="Customer Address"
                                          required
                                        ></textarea>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="card-border mb-3 card card-body border-primary">
                                        <h5
                                          for="city"
                                          >Customer City</h5
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
                                          >Customer State</h5
                                        >
                                        <select
                                          class="custom-select tm-select-accounts call_state"
                                          name="stccuststate"
                                        >
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="card-border mb-3 card card-body border-primary">
                                        <h5
                                          for="contactperson"
                                          >Contact Person
                                        </h5>
                                        <input
                                          rel="name"
                                          name="stccustcontperson"
                                          type="text"
                                          placeholder="Customer Contact Person"
                                          class="form-control validate"
                                          required
                                        />
                                      </div>
                                    </div>
                                    <div class="col-md-6">
                                      <div class="card-border mb-3 card card-body border-primary">
                                        <h5
                                          for="rack"
                                          >Customer Contact Number</h5
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
                                    </div>
                                    <div class="col-md-6">
                                      <div class="card-border mb-3 card card-body border-primary">
                                        <h5
                                          for="email"
                                          >Customer Email</h5
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
                                    </div>
                                    <div class="col-md-6">
                                      <div class="card-border mb-3 card card-body border-primary">
                                        <h5
                                          for="knownfor"
                                          >Customer Specially Known For</h5
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
                                    </div>
                                    <div class="col-md-6">
                                      <div class="card-border mb-3 card card-body border-primary">
                                        <h5
                                          for="knownfor"
                                          >Customer GSTIN</h5
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
                                    <div class="col-md-6">
                                        <div class="card-border mb-3 card card-body border-primary">
                                          <h5
                                            for="knownfor"
                                            >Customer PAN</h5
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

                                    <div class="col-12">
                                      <button type="submit" class="btn btn-primary btn-block text-uppercase">Add Customer Now</button>
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
                                              >View Customers
                                            </h5>
                                        </div>
                                    </div>
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
                            <div class="tab-pane tabs-animation fade" id="tab-content-3" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >Register Customer User
                                            </h5>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-12">
                                      <form action="" class="add-user-details-response-form">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="name"
                                          >User name
                                        </h5>
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
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="name"
                                          >User Email
                                        </h5>
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
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="name"
                                          >User Contact
                                        </h5>
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
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="name"
                                          >User Pincode
                                        </h5>
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
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="name"
                                          >User Address
                                        </h5>
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
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="city"
                                          >User City</h5
                                        >
                                        <select
                                          class="custom-select tm-select-accounts call_city"
                                          name="stccustusercity"
                                        >
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="state"
                                          >User State</h5
                                        >
                                        <select
                                          class="custom-select tm-select-accounts call_state"
                                          name="stccustuserstate"
                                        >
                                        </select>
                                      </div>
                                    </div>
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="state"
                                          >User Role</h5
                                        >
                                        <select
                                          class="custom-select tm-select-accounts"
                                          name="stccustuserrole"
                                        ><option value="1">Manager</option>
                                        <option value="2">Procurement</option>
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
                            <div class="tab-pane tabs-animation fade" id="tab-content-4" role="tabpanel">
                                <div class="row">
                                    <div class="col-xl-12 col-lg-12 col-md-12">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h5
                                              for="description" align="center"
                                              >Set Customer to User
                                            </h5>
                                        </div>
                                    </div>
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
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script type="text/javascript" src="./assets/scripts/loginopr.js"></script>
    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    <script type="text/javascript" src="./assets/scripts/jarvis.js"></script>
    <script>
        $(document).ready(function(){
          // state & city call
          stc_vendor_page_on_call();
          function stc_vendor_page_on_call(){
            $.ajax({
              url         : "kattegat/ragnar_customer.php",
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

          // add customer
          $('.add-customer-response-customer-form').on('submit', function(e){
            e.preventDefault();
            $.ajax({
              url           : "kattegat/ragnar_customer.php",
              method        : "post",
              data          : new FormData(this),
              contentType   : false,
              cache         : false,
              processData   : false,
              success       : function(data){
                // console.log(data);
                data=data.trim();
                if(data=="Customer added!!!"){
                  alert(data);
                  $('.stc-add-merchant-form')[0].reset();
                }else{
                  alert(data);
                }
              }
            });
          });

          // customer search
          var search_cust_byname_var;
          $('#searchbystccustname').on('keyup input', function(){
            search_cust_byname_var=$(this).val();
            $.ajax({
              url           : "kattegat/ragnar_customer.php",
              method        : "post",
              data          : {search_cust_byname_var_in:search_cust_byname_var},
              dataType      : 'JSON',
              success       : function(data){
                // console.log(data);
                $('.stc-call-view-Customer-row').html(data);
              }
            });
          });
          
          // Customer search 2
          var search_cust_var_byskf_var;  
          $('#searchbystccustskf').on('keyup input', function(){
            search_cust_var_byskf_var=$(this).val();
            $.ajax({
              url       : "kattegat/ragnar_customer.php",
              method    : "post",
              data      : {search_cust_var_byskf_var_in:search_cust_var_byskf_var},
              dataType  : 'JSON',
              success   : function(data){
                // console.log(data);
                $('.stc-call-view-Customer-row').html(data);
              }
            });
          }); 

          // add user details of customer agents
          $('.add-user-details-response-form').on("submit", function(e){
            e.preventDefault();
            $.ajax({
              url           : "kattegat/ragnar_customer.php",
              method        : "post",
              data          : new FormData(this),
              contentType   : false,
              processData   : false,
              dataType      : 'JSON',
              success       : function (argument) {
                // console.log(argument);
                argument=argument.trim();
                if(argument=="User registered successfully."){
                  alert(argument);
                  $('.add-user-details-response-form')[0].reset();
                }else{
                  alert(argument);
                }
              }            
            });
          });

          // set user to customer js
          $('body').delegate('.settocustuser', 'click', function(e){
            e.preventDefault();
            var userid=$('#stcuserselection').val();
            var custid=$('#stccustselection').val();
            $.ajax({
              url           : "kattegat/ragnar_customer.php",
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
