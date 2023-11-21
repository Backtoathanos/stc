<?php
ini_set("session.gc_maxlifetime", 21600);
session_set_cookie_params(21600);
session_start();
if(!empty(@$_SESSION['stc_admin_info_id'])){
    header('location:dashboard.php');
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Login Page - STC</title>
    <link rel = "icon" type = "image/png" href="img/stc_logo_title.png">
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Roboto:400,700"
    />
    <!-- https://fonts.google.com/specimen/Open+Sans -->
    <link rel="stylesheet" href="css/fontawesome.min.css" />
    <!-- https://fontawesome.com/ -->
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <!-- https://getbootstrap.com/ -->
    <link rel="stylesheet" href="css/templatemo-style.css">
    <link rel="stylesheet" href="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/infinite.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
  </head>

  <body>
    <div>
    </div>

    <div class="container tm-mt-big tm-mb-big">
      <div class="row">
        <div class="col-12 mx-auto tm-login-col">
          <div class="tm-bg-primary-dark tm-block tm-block-h-auto">
            <div class="row">
              <div class="col-12 text-center">
                <h2 class="tm-block-title mb-4">Welcome to Dashboard, Login </h2>
              </div>
            </div>
            <div class="row mt-2">
              <div class="col-12">
                <form class="stc-login-form">
                  <div class="form-group">
                    <label for="username">Username</label>
                    <input
                      name="stcusername"
                      type="text"
                      class="form-control validate"
                      id="username"
                      value=""
                      required
                    />
                  </div>
                  <div class="form-group mt-3">
                    <label for="password">Password</label>
                    <input
                      name="stcuserpassword"
                      type="password"
                      class="form-control validate"
                      id="password"
                      value=""
                      required
                    />
                  </div>
                  <div class="form-group mt-4">
                    <button
                      type="submit"
                      class="btn btn-primary btn-block text-uppercase"
                    >
                      Login
                    </button>
                    <input type="hidden" name="stc_login_check">
                  </div>
                  <!-- <button class="mt-5 btn btn-primary btn-block text-uppercase">
                    Forgot your password?
                  </button> -->
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php include "footer.php";?>
  </body>
</html>
