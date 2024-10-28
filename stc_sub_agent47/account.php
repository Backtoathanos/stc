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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
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
                                <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#updateprofiletab">
                                    <span>Accounts Info</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#passwordtab">
                                    <span>Password</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#updateratingtab">
                                    <span>Rating</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane tabs-animation fade show active" id="updateprofiletab" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body">
                                                <?php
                                                    include_once("../MCU/db.php");
                                                    $user_sql=mysqli_query($con, "
                                                        SELECT
                                                            `stc_cust_pro_supervisor_id`,
                                                            `stc_cust_pro_supervisor_image`,
                                                            `stc_cust_pro_supervisor_fullname`,
                                                            `stc_cust_pro_supervisor_contact`,
                                                            `stc_cust_pro_supervisor_whatsapp`,
                                                            `stc_cust_pro_supervisor_email`,
                                                            `stc_cust_pro_supervisor_address`,
                                                            `stc_cust_pro_supervisor_uid`,
                                                            `stc_cust_pro_supervisor_pincode`,
                                                            `stc_cust_pro_supervisor_category`,
                                                            `stc_cust_pro_supervisor_cityid`,
                                                            `stc_cust_pro_supervisor_state_id`
                                                        FROM
                                                            `stc_cust_pro_supervisor`
                                                        WHERE
                                                            `stc_cust_pro_supervisor_id`='".$_SESSION['stc_agent_sub_id']."'
                                                    ");
                                                    $fetch_data=mysqli_fetch_assoc($user_sql);
                                                ?>
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-6">
                                                        <div class="position-relative form-group">
                                                            <label for="name" class=""><b>Name</b></label>
                                                            <input type="text" class="mb-2 form-control" id="name" value="<?php echo $fetch_data['stc_cust_pro_supervisor_fullname']; ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-3">
                                                        <div class="position-relative form-group">
                                                            <label for="contact" class=""><b>Contact</b></label>
                                                            <input type="text" class="mb-2 form-control" id="contact" value="<?php echo $fetch_data['stc_cust_pro_supervisor_contact']; ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-3">
                                                        <div class="position-relative form-group">
                                                            <label for="whatsapp" class=""><b>Whatsapp</b></label>
                                                            <input type="text" class="mb-2 form-control" id="whatsapp" value="<?php echo $fetch_data['stc_cust_pro_supervisor_whatsapp']; ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6">
                                                        <div class="position-relative form-group">
                                                            <label for="email" class=""><b>Email</b></label>
                                                            <input type="email" class="mb-2 form-control" id="email" value="<?php echo $fetch_data['stc_cust_pro_supervisor_email']; ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6">            
                                                        <div class="position-relative form-group">
                                                            <label for="uid" class=""><b>UID</b></label>
                                                            <input type="text" class="mb-2 form-control" id="uid" value="<?php echo $fetch_data['stc_cust_pro_supervisor_uid']; ?>" <?php echo $fetch_data['stc_cust_pro_supervisor_uid']!=""?'disabled':''; ?> placeholder="Enter UID(Aadhar Card Number)">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6">         
                                                        <div class="position-relative form-group">
                                                            <label for="position" class=""><b>Position</b></label>
                                                            <input type="text" class="mb-2 form-control" id="position" value="<?php echo $fetch_data['stc_cust_pro_supervisor_category']; ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6">  
                                                        <div class="position-relative form-group">
                                                            <label for="userid" class=""><b>User Id</b></label>
                                                            <input type="text" class="mb-2 form-control" id="userid" value="<?php echo $fetch_data['stc_cust_pro_supervisor_contact']; ?>" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-12">                  
                                                        <div class="position-relative form-group">
                                                            <label for="address" class=""><b>Address</b></label>
                                                            <textarea class="mb-2 form-control" id="address" ><?php echo $fetch_data['stc_cust_pro_supervisor_address']; ?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-4">            
                                                        <div class="position-relative form-group">
                                                            <label for="pincode" class=""><b>Pincode</b></label>
                                                            <input type="text" class="mb-2 form-control" id="pincode" value="<?php echo $fetch_data['stc_cust_pro_supervisor_pincode']; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-4">            
                                                        <div class="position-relative form-group">
                                                            <label for="city" class=""><b>City</b></label>
                                                            <select class="form-control" id="updatecity">
                                                                <?php 
                                                                    $sql=mysqli_query($con, "SELECT stc_city_id, stc_city_name FROM `stc_city` ORDER BY `stc_city_name` ASC");
                                                                    foreach($sql as $row){
                                                                        if($fetch_data['stc_cust_pro_supervisor_cityid']==$row['stc_city_id']){
                                                                            echo '<option value="'.$row['stc_city_id'].'" selected>'.$row['stc_city_name'].'</option>';
                                                                        }else{
                                                                            echo '<option value="'.$row['stc_city_id'].'">'.$row['stc_city_name'].'</option>';
                                                                        }
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-4">            
                                                        <div class="position-relative form-group">
                                                            <label for="address" class=""><b>State</b></label>
                                                            <select class="form-control" id="updatestate">
                                                                <?php 
                                                                    $sql=mysqli_query($con, "SELECT stc_state_id, stc_state_name FROM `stc_state` ORDER BY `stc_state_name` ASC");
                                                                    foreach($sql as $row){
                                                                        if($fetch_data['stc_cust_pro_supervisor_state_id']==$row['stc_state_id']){
                                                                            echo '<option value="'.$row['stc_state_id'].'" selected>'.$row['stc_state_name'].'</option>';
                                                                        }else{
                                                                            echo '<option value="'.$row['stc_state_id'].'">'.$row['stc_state_name'].'</option>';
                                                                        }
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-12">
                                                        <div class="position-relative form-group">
                                                            <input type="hidden" class="stc-account-id" value="<?php echo $fetch_data['stc_cust_pro_supervisor_id']; ?>">
                                                            <button class="mt-1 btn btn-primary form-control stc-accountsdetails-update-btn">Update</button>
                                                        </div>
                                                    </div>
                                                </div> 
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card-border mb-3 card card-body border-success">
                                            <h2 class="tm-block-title">Change Avatar</h2>
                                            <div class="tm-avatar-container" style="position: relative;">
                                                <img src="<?php echo $fetch_data['stc_cust_pro_supervisor_image']==''?'../stc_symbiote/img/avatar.png':'assets/images/user_images/'.$fetch_data['stc_cust_pro_supervisor_image']; ?>" alt="Avatar" id="avatar-image" class="tm-avatar img-fluid mb-4" style="position:relative; width:100%;height: 393px;">
                                                <a href="#" class="tm-avatar-delete-link" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); display:none;">
                                                    <i class="fa fa-trash-alt tm-product-delete-icon" style="font-size: 20px; color: red;"></i>
                                                </a>
                                            </div>
                                            <input type="file" id="avatarInput" style="display:none;">
                                            <button id="uploadNewPhoto" class="btn btn-primary btn-block text-uppercase">Upload New Photo</button>

                                            <!-- Button to confirm cropping, hidden initially -->
                                            <button id="cropImage" class="btn btn-success btn-block text-uppercase mt-3" style="display:none;">Set Cropped Image</button>

                                            <!-- Button to save the image in DB, hidden initially -->
                                            <button id="updateImage" class="btn btn-info btn-block text-uppercase mt-3" style="display:none;">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane tabs-animation fade" id="passwordtab" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body">
                                                <div class="row justify-content-center">
                                                    <div class="col-sm-12 col-md-4">
                                                        <div class="position-relative form-group">
                                                            <label for="old-password" class=""><b>Old Password</b></label>
                                                            <input placeholder="Enter your password" type="password" name="stc-account-oldpassword" class="mb-2 form-control stc-account-oldpassword" id="old-password" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row justify-content-center">
                                                    <div class="col-sm-12 col-md-4">
                                                        <div class="position-relative form-group">
                                                            <label for="user-password" class=""><b>Password</b></label>
                                                            <input placeholder="Enter your password" type="password" name="stc-account-password" class="mb-2 form-control stc-account-password" id="user-password" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row justify-content-center">
                                                    <div class="col-sm-12 col-md-4">
                                                        <div class="position-relative form-group">
                                                            <label for="re-enter-password" class=""><b>Re-enter Password</b></label>
                                                            <input placeholder="Re-enter Your password" type="password" name="re-enter-password" class="mb-2 form-control stc-account-re-passoword" id="re-enter-password" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row justify-content-center">
                                                    <div class="col-sm-4 col-md-4">
                                                        <div class="position-relative form-group">
                                                            <input type="hidden" class="stc-account-id" value="<?php echo $fetch_data['stc_cust_pro_supervisor_id']; ?>">
                                                            <button class="mb-2 btn btn-primary form-control stc-accounts-update-btn">Update</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane tabs-animation fade" id="updateratingtab" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">                                        
                                        <div class="main-card mb-3 card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-12">
                                                        <div class="position-relative form-group">
                                                            <div class="row">
                                                                <!-- Continue for other cards -->
                                                            <!-- <table class="table table-bordered">
                                                                <thead>
                                                                    <th class="text-center">Slno</th>
                                                                    <th class="text-center">Type</th>
                                                                    <th class="text-center">Rating</th>
                                                                </thead>
                                                                <tbody> -->
                                                                    <?php 
                                                                        $cattend=0;
                                                                        $dja=0;
                                                                        $pm=0;
                                                                        $bm=0;
                                                                        $attendance=0;
                                                                        $tbm=0;
                                                                        $sql=mysqli_query($con, "SELECT `type`, `point` FROM `stc_cust_employee_rating` WHERE `created_by`='".$_SESSION['stc_agent_sub_id']."'");
                                                                        if(mysqli_num_rows($sql)>0){
                                                                            foreach($sql as $row){
                                                                                if(ucwords(strtolower($row['type']))=='Call Attend'){
                                                                                    $cattend+=$row['point'];
                                                                                }else if(ucwords(strtolower($row['type']))=='Daily Job Activity'){
                                                                                    $dja+=$row['point'];
                                                                                }else if(ucwords(strtolower($row['type']))=='Preventive Maintenance'){
                                                                                    $pm+=$row['point'];
                                                                                }else if(ucwords(strtolower($row['type']))=='Breakdown Maintenance'){
                                                                                    $bm+=$row['point'];
                                                                                }else if(ucwords(strtolower($row['type']))=='Attendance'){
                                                                                    $attendance+=$row['point'];
                                                                                }else if($row['type']=='TBM'){
                                                                                    $tbm+=$row['point'];
                                                                                }
                                                                            }
                                                                        }
                                                                        $type_arr=array(
                                                                            'Call Attend' => $cattend,
                                                                            'Daily Job Activity' => $dja,
                                                                            'Preventive Maintenance' => $pm,
                                                                            'Breakdown Maintenance' => $bm,
                                                                            'Attendance' => $attendance,
                                                                            'TBM' => $tbm
                                                                        );
                                                                        $slno=0;
                                                                        $total=0;
                                                                        $color_arr = array(
                                                                            'Call Attend' => '#ffcccc',         // Light red
                                                                            'Daily Job Activity' => '#cceeff',  // Light blue
                                                                            'Preventive Maintenance' => '#ccffcc', // Light green
                                                                            'Breakdown Maintenance' => '#ffffcc',  // Light yellow
                                                                            'Attendance' => '#ffccff',          // Light pink
                                                                            'TBM' => '#ffcc99'                  // Light orange
                                                                        );
                                                                        
                                                                        foreach($type_arr as $key=>$row){
                                                                            $slno++;
                                                                            echo '
                                                                                <div class="col-md-4"><div class="card text-center mb-4" style="background-color:'.$color_arr[$key].'"><div class="card-body"><h5 class="card-title">'.$key.'</h5><p class="card-text">'.$row.'</p></div></div></div>
                                                                            ';
                                                                            $total+=$row;
                                                                        }
                                                                        echo '
                                                                            <div class="col-md-12"><div class="card text-center mb-4" style="background-color:#d3d6ff"><div class="card-body"><h5 class="card-title">Total</h5><p class="card-text" style=" font-size: 45px; font-weight: bold;">'.$total.'</p></div></div></div>
                                                                        ';
                                                                    ?>
                                                                <!-- </tbody>
                                                            </table> -->
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
    </div>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script type="text/javascript" src="./assets/scripts/loginopr.js"></script>
    <!-- <script src="http://maps.google.com/maps/api/js?sensor=true"></script> -->
    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                var stc_old_password = $('.stc-account-oldpassword').val();
                var stc_ac_pass = $('.stc-account-password').val();
                var stc_ac_repass = $('.stc-account-re-passoword').val();
                if((stc_ac_pass==stc_ac_repass) && (stc_ac_pass!='' && stc_ac_repass!="" && stc_old_password!="")){
                    $.ajax({
                        url         : "nemesis/useroath.php",
                        method      : "POST",
                        data        : {
                            stc_ag_account_update:1,
                            stc_ac_id:stc_ac_id,
                            stc_old_password:stc_old_password,
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
                            }else if(res_data=="empty"){
                                window.location.reload();
                            }else{
                                alert("Something went wrong, Please try again.");
                            }
                        }
                    });
                }else{
                    alert("Invalid Password! Please check.");
                }
            });

            var cropper;
            var avatar = $('#avatar-image'); // Image element

            // Trigger file input when clicking on 'Upload New Photo' button
            $('#uploadNewPhoto').on('click', function () {
                $('#avatarInput').click();
            });

            // When an image is selected, initialize the cropper
            $('#avatarInput').on('change', function (e) {
                var files = e.target.files;
                var done = function (url) {
                    avatar.attr('src', url);
                    // Initialize the cropper
                    cropper = new Cropper(avatar[0], {
                        aspectRatio: 1, // You can set the aspect ratio (e.g., 1 for square)
                        viewMode: 2,
                    });

                    // Hide the "Upload New Photo" button and show the "Crop Image" button
                    $('#uploadNewPhoto').hide();
                    $('#cropImage').show();
                };

                if (files && files.length > 0) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        done(reader.result);
                    };
                    reader.readAsDataURL(files[0]);
                }
            });

            // Set Cropped Image on button click
            $('#cropImage').on('click', function () {
                if (cropper) {
                    var croppedCanvas = cropper.getCroppedCanvas({
                        width: 200, // Set desired output image width
                        height: 200, // Set desired output image height
                    });
                    var croppedImage = croppedCanvas.toDataURL(); // Convert canvas to base64 image

                    // Display the cropped image in the avatar img tag
                    avatar.attr('src', croppedImage);

                    // Destroy the cropper to prevent further cropping
                    cropper.destroy();
                    cropper = null;

                    // Hide the "Crop Image" button and show the "Update" button and delete icon
                    $('#cropImage').hide();
                    $('#updateImage').show();
                    $('.tm-avatar-delete-link').show(); // Show the delete icon
                }
            });

            // Delete the current image and revert to default avatar
            $('.tm-avatar-delete-link').on('click', function (e) {
                e.preventDefault();
                avatar.attr('src', '../stc_symbiote/img/avatar.png'); // Set to default avatar image
                $('.tm-avatar-delete-link').hide(); // Hide the delete icon
                $('#updateImage').hide(); // Hide the update button
                $('#uploadNewPhoto').show(); // Show the upload button again
            });

            // Save the cropped image to the database
            $('#updateImage').on('click', function () {
                var croppedImage = avatar.attr('src'); // Get the current cropped image data

                // Ajax request to save the image to the database
                $.ajax({
                    url         : "nemesis/useroath.php",
                    method: 'POST',
                    data: { upload_image:1, avatar: croppedImage },
                    success: function (response) {
                        alert('Image updated successfully');
                        window.location.reload();
                    },
                    error: function () {
                        alert('Error updating image');
                    }
                });
            });

            $('body').delegate('.stc-accountsdetails-update-btn', 'click', function(e){
                e.preventDefault();
                var stc_ac_id = $('.stc-account-id').val();
                var uid = $('#uid').val();
                var address = $('#address').val();
                var pincode = $('#pincode').val();
                var city = $('#updatecity').val();
                var state = $('#updatestate').val();
                if(uid!=''){
                    $.ajax({
                        url         : "nemesis/useroath.php",
                        method      : "POST",
                        data        : {
                            stc_ag_accountdetails_update:1,
                            stc_ac_id:stc_ac_id,
                            uid:uid,
                            address:address,
                            pincode:pincode,
                            city:city,
                            state:state
                        },
                        // dataType    : "JSON",
                        success     : function(res_data){
                            // console.log(res_data);
                            res_data = res_data.trim();
                            if(res_data=="success"){
                                alert("Account Updated.");
                                window.location.reload();
                            }else if(res_data=="empty"){
                                window.location.reload();
                            }else{
                                alert(res_data);
                            }
                        }
                    });
                }else{
                    alert("Please enter all details.");
                }
            });
        });
    </script>
</body>
</html>