<?php  
session_start(); 
if(isset($_SESSION["stc_agent_sub_id"])){ 
    // if(time()-$_SESSION["login_time_stamp"] >600)   
    // { 
    //     session_unset(); 
    //     session_destroy(); 
    //     header("Location:index.html"); 
    // } 
}else{ 
    header("Location:index.html"); 
} 
?> 
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Account - STC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="">
    <meta name="msapplication-tap-highlight" content="no">
    <!-- Latest compiled and minified CSS -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous"> -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="./main.css" rel="stylesheet">
    <!-- <link href="assets/css/style.css" rel="stylesheet"> -->
    <style>        
        .close-tag-beg{
            display: none;
        }
      
        .fade:not(.show) {
          opacity: 10;
        }
    </style>
</head>
<body>
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <?php include_once("header-nav.php");?>
        <?php include_once("ui-setting.php");?>        
        <div class="app-main">
                <?php include_once("sidebar-nav.php");?>                   
                <div class="app-main__outer">
                    <div class="app-main__inner">
                        <div class="app-page-title">
                        </div>            
                        <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                            <li class="nav-item">
                                <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#create-project">
                                    <span>Accounts</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane tabs-animation fade show active" id="create-project" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body">
                                                <?php
                                                    include_once("../MCU/db.php");
                                                    $user_sql=mysqli_query($con, "
                                                        SELECT
                                                            `stc_cust_pro_supervisor_id`,
                                                            `stc_cust_pro_supervisor_fullname`,
                                                            `stc_cust_pro_supervisor_contact`,
                                                            `stc_cust_pro_supervisor_whatsapp`,
                                                            `stc_cust_pro_supervisor_email`,
                                                            `stc_cust_pro_supervisor_address`
                                                        FROM
                                                            `stc_cust_pro_supervisor`
                                                        WHERE
                                                            `stc_cust_pro_supervisor_id`='".$_SESSION['stc_agent_sub_id']."'
                                                    ");
                                                    $fetch_data=mysqli_fetch_assoc($user_sql);
                                                ?>
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-12">
                                                        <div class="position-relative form-group">
                                                            <label for="name" class=""><b>Name</b></label>
                                                            <input type="text" class="mb-2 form-control" id="name" value="<?php echo $fetch_data['stc_cust_pro_supervisor_fullname']; ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-4">
                                                        <div class="position-relative form-group">
                                                            <label for="contact" class=""><b>Contact</b></label>
                                                            <input type="text" class="mb-2 form-control" id="contact" value="<?php echo $fetch_data['stc_cust_pro_supervisor_contact']; ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-4">
                                                        <div class="position-relative form-group">
                                                            <label for="whatsapp" class=""><b>Whatsapp</b></label>
                                                            <input type="text" class="mb-2 form-control" id="whatsapp" value="<?php echo $fetch_data['stc_cust_pro_supervisor_whatsapp']; ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-4">
                                                        <div class="position-relative form-group">
                                                            <label for="email" class=""><b>Email</b></label>
                                                            <input type="email" class="mb-2 form-control" id="email" value="<?php echo $fetch_data['stc_cust_pro_supervisor_email']; ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-12">                  
                                                        <div class="position-relative form-group">
                                                            <label for="address" class=""><b>Address</b></label>
                                                            <input type="text" class="mb-2 form-control" id="address" value="<?php echo $fetch_data['stc_cust_pro_supervisor_address']; ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-4">
                                                        <div class="position-relative form-group">
                                                            <label for="userid" class=""><b>User Id</b></label>
                                                            <input type="text" class="mb-2 form-control" id="userid" value="<?php echo $fetch_data['stc_cust_pro_supervisor_contact']; ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-4">
                                                        <div class="position-relative form-group">
                                                            <label for="user-password" class=""><b>Password</b></label>
                                                            <input placeholder="Enter your password" type="password" name="stc_cust_pro_refr" class="mb-2 form-control stc-account-password" id="user-password" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-4">
                                                        <div class="position-relative form-group">
                                                            <label for="re-enter-password" class=""><b>Re-enter Password</b></label>
                                                            <input placeholder="Re-enter Your password" type="password" name="re-enter-password" class="mb-2 form-control stc-account-re-passoword" id="re-enter-password" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-12">
                                                        <div class="position-relative form-group">
                                                            <input type="hidden" class="stc-account-id" value="<?php echo $fetch_data['stc_cust_pro_supervisor_id']; ?>">
                                                            <button class="mt-1 btn btn-primary form-control stc-accounts-update-btn">Update</button>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
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
    <!-- <script src="http://maps.google.com/maps/api/js?sensor=true"></script> -->
    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function(){
            $('body').delegate('.search-icon', 'click', function(e){
                e.preventDefault();
                $(this).hide();
                $('.search-icon-2').show();
                // var pd_title=$('.agent-pro-search').val();
                // window.location.href="stc-product.php?pd_name="+pd_title;
            });

            $('body').delegate('.search-icon-2', 'click', function(e){
                var pd_title=$('.agent-pro-search').val();
                if(pd_title!=""){
                    window.location.href="stc-product.php?pd_name="+pd_title;
                }
            });    
        });
    </script>
    <script>
        $(document).ready(function(){

            $('body').delegate('.stc-accounts-update-btn', 'click', function(e){
                e.preventDefault();
                var stc_ac_id = $('.stc-account-id').val();
                var stc_ac_pass = $('.stc-account-password').val();
                var stc_ac_repass = $('.stc-account-re-passoword').val();
                if((stc_ac_pass==stc_ac_repass) && (stc_ac_pass!='' && stc_ac_repass!="")){
                    $.ajax({
                        url         : "nemesis/useroath.php",
                        method      : "POST",
                        data        : {
                            stc_ag_account_update:1,
                            stc_ac_id:stc_ac_id,
                            stc_ac_pass:stc_ac_pass,
                            stc_ac_repass:stc_ac_repass
                        },
                        // dataType    : "JSON",
                        success     : function(res_data){
                            // console.log(res_data);
                            res_data = res_data.trim();
                            if(res_data=="success"){
                                alert("Account Updated.");
                                window.location.reload();
                            }else if(res_data=="error"){
                                alert("Something went wrong, Please try again.");
                            }else if(res_data=="empty"){
                                window.location.reload();
                            }
                        }
                    });
                }else{
                    alert("Invalid Password! Please check.");
                }
            });
        });
    </script>
</body>
</html>