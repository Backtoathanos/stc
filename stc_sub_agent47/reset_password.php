<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password - STC</title>
    <!-- Font Icon -->
    <link rel="stylesheet" href="assets/fonts/material-icon/css/material-design-iconic-font.min.css">
    <!-- Main css -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php 
        if(isset($_GET['token'])){
            $token = $_GET['token'];
            include_once("../MCU/db.php");
            // Check if the token is valid and not expired
            $query = "SELECT `stc_cust_pro_supervisor_id` FROM stc_cust_pro_supervisor WHERE stc_cust_pro_supervisor_token = '".mysqli_real_escape_string($con, $token)."'";
            $stmt = mysqli_query($con, $query);
            if(mysqli_num_rows($stmt)>0){
                $result=mysqli_fetch_assoc($stmt);
                $sup_id=$result['stc_cust_pro_supervisor_id'];
    ?>
    <div class="main">

        <!-- Sign in  Form -->
        <section class="sign-in">
            <div class="container">
                <div class="signin-content">
                    <div class="signin-form">
                        <h2 style="text-align: center; font-size:50px">STC Associate</h2>
                        <h2 style="text-align: center;">Forgot password?</h2>
                        <p>Please enter your phone number. You will receive a link to create a new password via email</p><br>
                        <form method="POST" class="register-form" id="login-form">
                            <div class="form-group">
                                <label for="your_pass"><i class="zmdi zmdi-lock"></i></label>
                                <input type="password" name="agent_pass" id="your_pass" placeholder="Enter new Password"/>
                            </div>
                            <div class="form-group">
                                <label for="your_pass"><i class="zmdi zmdi-lock"></i></label>
                                <input type="password" name="agent_pass1" id="your_pass1" placeholder="Re-enter new Password"/>
                            </div>
                            <div class="form-group form-button">
                                <input type="hidden" name="agent_resetpassword">
                                <input type="hidden" name="agent_id" value="<?php echo $sup_id;?>">
                                <input type="submit" id="signin" class="form-submit" value="Reset password"/>&nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="index.html" style="color: #7e7e7e;font-size:15px;">Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <?php
            }else{
                header("location:index.html");
            }
        }else{
            header("location:index.html");
        }
    ?>

    <!-- JS -->
    <script src="assets/scripts/jquery.min.js"></script>
    <script src="assets/scripts/loginop.js"></script>
    <script>
        $(document).ready(function(){
            $('body').delegate('.signup-image-link', 'click', function(e){
                e.preventDefault();
                // alert("Hogaya!!");
                $('.sign-in').fadeOut(500);
                $('.sign-up').toggle(500);
            });
            $('body').delegate('.signin-image-link', 'click', function(e){
                e.preventDefault();
                // alert("Hogaya!!");
                $('.sign-up').fadeOut(500);
                $('.sign-in').toggle(500);
            });

            $('#login-form').on('submit', function(e){
                e.preventDefault();
                $.ajax({
                    url         : "nemesis/useroath.php",
                    method      : "POST",
                    data        : new FormData(this),
                    contentType : false,
                    processData : false,
                    success     : function(argument) {
                        // console.log(argument);
                        argument=argument.trim();
                        $('.mssgbox').remove();
                        if(argument=="success"){
                            window.location.href="dashboard.php";    
                        }else if(argument=="unmatch"){
                            $('#signin').parent().parent().find('.form-group:eq(1)').after('<p class="mssgbox" style="color: red;">Password not matched.</p>');
                        }else if(argument=="required"){
                            $('#signin').parent().parent().find('.form-group:eq(1)').after('<p class="mssgbox" style="color: red;">Both fields are required.</p>');
                        }else{
                            $('#signin').parent().parent().find('.form-group:eq(1)').after('<p class="mssgbox" style="color: red;">Something went wrong. Please check with your manager.</p>');
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>