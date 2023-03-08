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
    <title>Accounts - STC</title>
    <link rel = "icon" type = "image/png" href="img/stc_logo_title.png">
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Roboto:400,700"
    />
    <!-- https://fonts.google.com/specimen/Roboto -->
    <link rel="stylesheet" href="css/fontawesome.min.css" />
    <!-- https://fontawesome.com/ -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <!-- https://getbootstrap.com/ -->
    <link rel="stylesheet" href="css/templatemo-style.css">
    <link rel="stylesheet" href="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/infinite.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <!--
	Product Admin CSS Template
	https://templatemo.com/tm-524-product-admin
	-->
  </head>

  <body id="reportsPage">
    <div class="" id="home">
      <nav class="navbar navbar-expand-xl">
          <div class="container h-100">
            <a class="navbar-brand animated bounceInRight" href="dashboard.php">
              <img style="width: 50%;border-radius: 34px;" src="img/stc_logo.png">
            </a>
            <button class="navbar-toggler ml-auto mr-0" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="fas fa-bars tm-nav-icon"></i>
            </button>
            <div class="collapse navbar-collapse animated bounceInDown" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto h-100">
                  <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">
                      <i class="fas fa-tachometer-alt"></i>
                      Dashboard  
                      <span class="sr-only">(current)</span>            
                    </a>
                  </li>                        
                  <li class="nav-item">
                    <a class="nav-link" href="erp.php">
                      <i class="fas fa-cog"></i>
                        ERP 
                          
                    </a>
                  </li>                
                  <li class="nav-item">
                    <a class="nav-link active" href="accounts.php">
                      <i class="far fa-user"></i>
                      Accounts
                    </a>
                  </li>
                  <li class="nav-item dropdown">
                  <a href="agent-order.php" class="nav-link dropdown-toggle">
                      <span style="float: left;position: absolute;top: 20px;left: 40px;font-size: 12px;background: #bb5656;padding: 5px;border-radius: 50%;" class="badge"></span>
                      <i class="far fa-bell"></i>
                    </a>
                  </li>
                </ul>
                <ul class="navbar-nav">
                  <li class="nav-item">
                    <a class="nav-link d-block" href="asgard/logout.php">
                      <?php echo @$_SESSION["stc_admin_info_name"];?>, <b>Logout</b>
                    </a>
                  </li>
                </ul>
            </div>
          </div>
        </nav>
      <div class="container mt-5">
        <div class="row">
          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 mx-auto">
            <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
              <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                  <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                      <li class="breadcrumb-item"><a href="dashboard.php">Dashboard</a></li>
                      <li class="breadcrumb-item"><a href="erp.php" class="text-uppercase" value="">ERP</a></li>
                      <li class="breadcrumb-item active"><a href="#" class="active" value="">Account</a></li>
                    </ol>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- row -->
        <div class="row tm-content-row">
          <div class="tm-block-col tm-col-avatar">
            <div class="tm-bg-primary-dark tm-block tm-block-avatar">
              <h2 class="tm-block-title">Change Avatar</h2>
              <div class="tm-avatar-container">
                <img
                  src="img/avatar.png"
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
          <div class="tm-block-col tm-col-account-settings">
            <div class="tm-bg-primary-dark tm-block tm-block-settings">
              <h2 class="tm-block-title">Account Settings</h2>
              <form action="" class="stc-update-user-form row">
                <div class="form-group col-lg-12">
                  <label for="name">Name</label>
                  <input
                    id="uname"
                    name="uname"
                    type="text"
                    class="form-control validate"
                  />
                </div>
                <div class="form-group col-lg-12">
                  <label for="name">Address</label>
                  <textarea
                    id="uaddress"
                    name="uaddress"
                    class="form-control validate"
                    rows="2"
                  ></textarea>
                </div>
                <div class="form-group col-lg-6">
                  <label for="uphone">Phone</label>
                  <input
                    id="uphone"
                    name="uphone"
                    type="text"
                    class="form-control validate"
                  />
                </div>
                <div class="form-group col-lg-6">
                  <label for="email">WhatsApp</label>
                  <input
                    id="uwhats"
                    name="uwhats"
                    type="text"
                    class="form-control validate"
                  />
                </div>
                <div class="form-group col-lg-6">
                  <label for="email">Email</label>
                  <input
                    id="uemail"
                    name="uemail"
                    type="text"
                    class="form-control validate"
                  />
                </div>
                <div class="form-group col-lg-6">
                  <label for="phone">User Id</label>
                  <input
                    id="uid"
                    name="uid"
                    type="tel"
                    class="form-control validate"
                  />
                </div>
                <div class="form-group col-lg-6">
                  <label for="password">Password</label>
                  <input
                    id="upassword"
                    name="upassword"
                    type="password"
                    class="form-control validate"
                  />
                </div>
                <div class="form-group col-lg-6">
                  <label for="password2">Re-enter Password</label>
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
      <?php include "footer.php";?>
    <script>
      $(document).ready(function(){
        load_user();
        function load_user(){
          $.ajax({
            url       : "asgard/stcusercheck.php",
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
            url : "asgard/stcusercheck.php",
            method : "post",
            data : new FormData(this),
            contentType : false,
            processData : false,
            // dataType : 'JSON',
            success : function (argument) {
              // console.log(argument);
              alert(argument);
              // if(argument="success"){
                window.location.reload;
              // }else{
              //   alert(argument);
              // }
              // $('.stc-update-user-form')[0].reset();
            }
          }); 
        });
      });
    </script>
  </body>
</html>
