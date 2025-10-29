<?php  
// Include authentication helper
require_once 'kattegat/auth_helper.php';

// Check authentication using the new hybrid system
STCAuthHelper::checkAuth();?> 
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Accounts - STC</title>
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
                                    <span>Accounts</span>
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
                                              >Accounts
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                      <div class="card-border mb-3 card card-body border-success">
                                        <h2 class="tm-block-title">Change Avatar</h2>
                                        <div class="tm-avatar-container">
                                          <img
                                            src="../stc_symbiote/img/avatar.png"
                                            alt="Avatar"
                                            class="tm-avatar img-fluid mb-4"
                                          />
                                          <a href="#" class="tm-avatar-delete-link">
                                            <i class="far fa-trash-alt tm-product-delete-icon"></i>
                                          </a>
                                        </div>
                                        <button class="btn btn-primary btn-block text-uppercase">
                                          Upload New Photo
                                        </button>
                                      </div>
                                    </div>
                                    <div class="col-md-8">
                                      <div class="card-border mb-3 card card-body border-success">                                 
                                        <form action="" class="stc-update-user-form row">
                                          <div class="form-group col-lg-12">
                                            <h5 for="name">Name</h5>
                                            <input
                                              id="uname"
                                              name="uname"
                                              type="text"
                                              class="form-control validate"
                                            />
                                          </div>
                                          <div class="form-group col-lg-12">
                                            <h5 for="name">Address</h5>
                                            <textarea
                                              id="uaddress"
                                              name="uaddress"
                                              class="form-control validate"
                                              rows="2"
                                            ></textarea>
                                          </div>
                                          <div class="form-group col-lg-6">
                                            <h5 for="uphone">Phone</h5>
                                            <input
                                              id="uphone"
                                              name="uphone"
                                              type="text"
                                              class="form-control validate"
                                            />
                                          </div>
                                          <div class="form-group col-lg-6">
                                            <h5 for="email">WhatsApp</h5>
                                            <input
                                              id="uwhats"
                                              name="uwhats"
                                              type="text"
                                              class="form-control validate"
                                            />
                                          </div>
                                          <div class="form-group col-lg-6">
                                            <h5 for="email">Email</h5>
                                            <input
                                              id="uemail"
                                              name="uemail"
                                              type="text"
                                              class="form-control validate"
                                            />
                                          </div>
                                          <div class="form-group col-lg-6">
                                            <h5 for="phone">User Id</h5>
                                            <input
                                              id="uid"
                                              name="uid"
                                              type="tel"
                                              class="form-control validate"
                                            />
                                          </div>
                                          <div class="form-group col-lg-6">
                                            <h5 for="password">Password</h5>
                                            <input
                                              id="upassword"
                                              name="upassword"
                                              type="password"
                                              class="form-control validate"
                                            />
                                          </div>
                                          <div class="form-group col-lg-6">
                                            <h5 for="password2">Re-enter Password</h5>
                                            <input
                                              id="upasswordagain"
                                              name="upasswordagain"
                                              type="password"
                                              class="form-control validate"
                                            />
                                          </div>
                                          <div class="col-12">
                                            <input type="hidden" name="user_update">
                                            <button
                                              type="submit"
                                              class="btn btn-primary btn-block text-uppercase"
                                            >
                                              Update Your Account
                                            </button>
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
    <script>
        $(document).ready(function(){
          load_user();
          function load_user(){
            $.ajax({
              url       : "kattegat/useroath.php",
              method    : "POST",
              data      : {load_u:1},
              dataType  : "JSON",
              success   : function(user){
                // console.log(user);
                $('#uname').val(user['stc_user_name']);
                $('#uaddress').val(user['stc_user_address']);
                $('#uphone').val(user['stc_user_phone']);
                $('#uwhats').val(user['stc_user_phone_again']);
                $('#uemail').val(user['stc_user_email']);
                $('#uid').val(user['stc_user_userid']);
              }
            });
          }

          $('.stc-update-user-form').on('submit', function(e){
            e.preventDefault();
            $.ajax({
              url         : "kattegat/useroath.php",
              method      : "post",
              data        : new FormData(this),
              contentType : false,
              processData : false,
              // dataType : 'JSON',
              success     : function (argument) {
                // console.log(argument);
                  alert(argument);
                  window.location.reload;
              }
            }); 
          });
        });
    </script>
</body>
</html>
