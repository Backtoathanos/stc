<?php  
session_start(); 
if(isset($_SESSION["stc_agent_id"])){ 
    // if(time()-$_SESSION["login_time_stamp"] >600)   
    // { 
    //     session_unset(); 
    //     session_destroy(); 
    //     header("Location:index.html"); 
    // } 
}else{ 
    header("Location:index.html"); 
} 
include_once("../MCU/db.php");
?> 
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>User Management - STC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="">
    <meta name="msapplication-tap-highlight" content="no">
    <!-- Latest compiled and minified CSS -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous"> -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="./main.css" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!-- <link href="assets/css/style.css" rel="stylesheet"> -->
    <style>        
        .close-tag-beg{
            display: none;
        }

        .fade:not(.show) {
          opacity: 10;
        }

        @import url("https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");
        @import url('https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700');
        @import url('https://fonts.googleapis.com/css?family=Libre+Baskerville:400,700');
        .profile-card-3 h1{float:left; width:100%; color:#232323; margin-bottom:30px; font-size: 14px;}
        .profile-card-3 h1 span{font-family: 'Libre Baskerville', serif; display:block; font-size:45px; text-transform:none; margin-bottom:20px; margin-top:30px; font-weight:700}
        .profile-card-3 h1 a{color:#131313; font-weight:bold;}
        .profile-card-3 {
          font-family: 'Open Sans', Arial, sans-serif;
          position: relative;
          float: left;
          overflow: hidden;
          width: 100%;
          text-align: center;
          height:368px;
          border:none;
        }
        .profile-card-3 .background-block {
            float: left;
            width: 100%;
            height: 200px;
            overflow: hidden;
        }
        .profile-card-3 .background-block .background {
          width:100%;
          vertical-align: top;
          opacity: 0.9;
          -webkit-filter: blur(0.5px);
          filter: blur(0.5px);
           -webkit-transform: scale(1.8);
          transform: scale(2.8);
        }
        .profile-card-3 .card-content {
          width: 100%;
          padding: 15px 25px;
          color:#232323;
          float:left;
          background:#efefef;
          height:50%;
          border-radius:0 0 5px 5px;
          position: relative;
          z-index: 9999;
        }
        .profile-card-3 .card-content::before {
            content: '';
            background: #efefef;
            width: 120%;
            height: 100%;
            left: 11px;
            bottom: 51px;
            position: absolute;
            z-index: -1;
            transform: rotate(-13deg);
        }
        .profile-card-3 .profile {
          border-radius: 50%;
          position: absolute;
          bottom: 50%;
          left: 50%;
          max-width: 100px;
          opacity: 1;
          box-shadow: 3px 3px 20px rgba(0, 0, 0, 0.5);
          border: 2px solid rgba(255, 255, 255, 1);
          -webkit-transform: translate(-50%, 0%);
          transform: translate(-50%, 0%);
          z-index:99999;
        }
        .profile-card-3 h2 {
          margin: 0 0 5px;
          font-weight: 600;
          font-size:15px;
        }
        .profile-card-3 h2 small {
          display: block;
          font-size: 15px;
          margin-top:10px;
        }
        .profile-card-3 i {
          display: inline-block;
            font-size: 16px;
            color: #232323;
            text-align: center;
            border: 1px solid #232323;
            width: 30px;
            height: 30px;
            line-height: 30px;
            border-radius: 50%;
            margin:0 5px;
        }
        .profile-card-3 .icon-block{
            float:left;
            width:100%;
            margin-top:15px;
        }
        .profile-card-3 .icon-block a{
            text-decoration:none;
        }
        .profile-card-3 i:hover {
          background-color:#232323;
          color:#fff;
          text-decoration:none;
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
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div class="page-title-icon">
                                        <i class="pe-7s-display1 icon-gradient bg-premium-dark">
                                        </i>
                                    </div>
                                    <div>User Management
                                        <div class="page-title-subheading">You can add your User information here for your experience with us<h3>&#9786;</h3>.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>            
                        <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                            <li class="nav-item">
                                <a role="tab" class="nav-link active" id="tab-1" data-toggle="tab" href="#show-supervisor">
                                    <span><b><i class="pe-7s-look" style="position: relative;top: 2px"></i></b> Show User</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#alot-budget">
                                    <span><b>O</b> Link Project </span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane tabs-animation fade show active" id="show-supervisor" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h2 class="card-title">Show User</h2>
                                                <div class="row mb-3 mt-3">
                                                    <div class="col-md-3">
                                                        <button type="button" class="btn mr-2 mb-2 btn-primary form-control stcAdduser" data-toggle="modal" data-target=".bd-adduser-modal-lg"><i class="fas fa-plus-circle"></i> Add User</button>
                                                    </div>
                                                    <div class="col-md-9 d-none d-sm-block">
                                                        <input type="text" class="form-control all_project_search" id="searchuser" onkeyup="myFunction()" placeholder="Search User">
                                                    </div>                                      
                                                </div>
                                                <form class="#">
                                                    <div class="row stc-show-userdata">
                                                        <!-- <div class="col-md-12"> -->
                                                            <!-- <table class="table table-responsive">
                                                                <thead>
                                                                    <th>User Id</br>Name</th>
                                                                    <th>Address</th>
                                                                    <th>Contact &</br>Whatsapp Number</th>
                                                                    <th>Email</th>
                                                                    <th>Sitename</th>
                                                                    <th>User Category</th>
                                                                    <th>Action</th>
                                                                </thead>
                                                                <tbody> -->
                                                                    <?php
                                                                        $showsubsupqry=mysqli_query($con, "
                                                                            SELECT `stc_cust_pro_supervisor_id`,
                                                                                `stc_cust_pro_supervisor_fullname`,
                                                                                `stc_cust_pro_supervisor_address`,
                                                                                `stc_cust_pro_supervisor_contact`,
                                                                                `stc_cust_pro_supervisor_whatsapp`,
                                                                                `stc_cust_pro_supervisor_email`,
                                                                                `stc_cust_pro_supervisor_category`
                                                                            FROM `stc_cust_pro_supervisor`
                                                                            INNER JOIN `stc_agents` 
                                                                            ON `stc_cust_pro_supervisor_created_by`=`stc_agents_id` 
                                                                            WHERE `stc_agents_id`='".$_SESSION["stc_agent_id"]."'
                                                                        ");
                                                                        foreach($showsubsupqry as $supsubrow){
                                                                            $sitename='';
                                                                            $sitenameqry=mysqli_query($con, "
                                                                                SELECT `stc_cust_project_title` FROM `stc_cust_pro_attend_supervise`
                                                                                INNER JOIN `stc_cust_pro_supervisor` 
                                                                                ON `stc_cust_pro_attend_supervise_super_id`=`stc_cust_pro_supervisor_id` 
                                                                                INNER JOIN `stc_cust_project` 
                                                                                ON `stc_cust_pro_attend_supervise_pro_id`=`stc_cust_project_id`
                                                                                WHERE `stc_cust_pro_attend_supervise_super_id`='".$supsubrow['stc_cust_pro_supervisor_id']."'
                                                                            ");
                                                                            foreach($sitenameqry as $sitenamerow){
                                                                                $sitename.=$sitenamerow['stc_cust_project_title'].'<br>';
                                                                            }
                                                                            // echo "
                                                                            //     <tr>
                                                                            //         <td>
                                                                            //             STC/S/A/".substr("0000{$supsubrow['stc_cust_pro_supervisor_id']}", -5)."</br>
                                                                            //             ".$supsubrow['stc_cust_pro_supervisor_fullname']."
                                                                            //         </td>
                                                                            //         <td>".$supsubrow['stc_cust_pro_supervisor_address']."</td>
                                                                            //         <td>
                                                                            //             +91-".$supsubrow['stc_cust_pro_supervisor_contact']."</br>
                                                                            //             <a href='http://wa.me/7209581823'>
                                                                            //             +91-".$supsubrow['stc_cust_pro_supervisor_whatsapp']."
                                                                            //             </a>
                                                                            //         </td>
                                                                            //         <td>".$supsubrow['stc_cust_pro_supervisor_email']."</td>
                                                                            //         <td>".$sitename."</td>
                                                                            //         <td>".$supsubrow['stc_cust_pro_supervisor_category']."</td>
                                                                            //         <td><a href='#' class='stc-edit-user-btn' id='".$supsubrow['stc_cust_pro_supervisor_id']."'>Edit User</a></td>
                                                                            //     </tr>
                                                                            // ";
                                                                            $address=$supsubrow['stc_cust_pro_supervisor_address'];
                                                                            if(strlen($address)>33){
                                                                                $address=substr($address, 0,33);
                                                                                $address=$address.'...';
                                                                            }
                                                                            echo '
                                                                                <div class="col-md-3 usertarget" style="margin-bottom:15px;">
                                                                                    <div class="card profile-card-3">
                                                                                        <div class="background-block">
                                                                                            <img src="https://images.pexels.com/photos/459225/pexels-photo-459225.jpeg?auto=compress&cs=tinysrgb&h=650&w=940" alt="profile-sample1" class="background"/>
                                                                                        </div>
                                                                                        <div class="profile-thumb-block">
                                                                                            <img src="../stc_symbiote/img/avatar.png" alt="profile-image" class="profile"/>
                                                                                        </div>
                                                                                        <div class="card-content">
                                                                                            <h2>'.$supsubrow['stc_cust_pro_supervisor_fullname'].'<small>'.$supsubrow['stc_cust_pro_supervisor_category'].'</small></h2>
                                                                                            <h3 style="font-size: 0.75rem;">
                                                                                                '.$supsubrow['stc_cust_pro_supervisor_address'].'<br>
                                                                                                +91-'.$supsubrow['stc_cust_pro_supervisor_contact'].'<br>
                                                                                                +91-'.$supsubrow['stc_cust_pro_supervisor_whatsapp'].'<br>

                                                                                            </h3>
                                                                                            <div class="icon-block">
                                                                                                <a href="#" class="stc-user-edit" title="Edit" data-toggle="modal" data-target=".bd-edituser-modal-lg" id="'.$supsubrow['stc_cust_pro_supervisor_id'].'"><i class="fas fa-edit"></i></a>
                                                                                                <a href="#" class="stc-user-view" title="View" data-toggle="modal" data-target=".bd-showuser-modal-lg" id="'.$supsubrow['stc_cust_pro_supervisor_id'].'"> <i class="fas fa-eye"></i></a>
                                                                                                <a href="#" class="stc-user-collaborate" title="Collaborate"> <i class="fas fa-handshake-o"></i></a>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            ';
                                                                        }
                                                                    ?>
                                                                <!-- </tbody>                                                                   
                                                            </table> -->
                                                        <!-- </div> -->
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane tabs-animation fade" id="alot-budget" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">Link Project</h5>
                                                <form class="stc-project-alot-cust">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="position-relative form-group">
                                                                <label for="exampleSelect" class="">Select Project</label>
                                                                <select name="stc_alot_pro_project" id="#" class="form-control" required>
                                                                    <?php 
                                                                        $proseleqry=mysqli_query($con, "
                                                                            SELECT DISTINCT `stc_cust_project_id`, `stc_cust_project_title` 
                                                                            FROM `stc_cust_project` 
                                                                            INNER JOIN `stc_agent_requested_customer` 
                                                                            ON `stc_agent_requested_customer_cust_id`=`stc_cust_project_cust_id`
                                                                            WHERE `stc_agent_requested_customer_agent_id`='".$_SESSION['stc_agent_id']."'
                                                                        ");
                                                                        foreach($proseleqry as $proselrow){
                                                                            echo '<option value="'.$proselrow['stc_cust_project_id'].'">'.$proselrow['stc_cust_project_title'].'</option>';
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="position-relative form-group">
                                                                <label for="exampleSelect" class="">Select User</label>
                                                                <select name="stc_alot_pro_supervisor" id="exampleSelect" class="form-control" required>
                                                                    <?php 
                                                                        $proseleqry=mysqli_query($con, "
                                                                            SELECT `stc_cust_pro_supervisor_id`, `stc_cust_pro_supervisor_fullname` 
                                                                            FROM `stc_cust_pro_supervisor` 
                                                                            WHERE `stc_cust_pro_supervisor_created_by`='".$_SESSION['stc_agent_id']."'
                                                                            ORDER BY `stc_cust_pro_supervisor_fullname` ASC
                                                                        ");
                                                                        if(mysqli_num_rows($proseleqry)>0){
                                                                            foreach($proseleqry as $proselrow){
                                                                                echo '<option value="'.$proselrow['stc_cust_pro_supervisor_id'].'">'.$proselrow['stc_cust_pro_supervisor_fullname'].'</option>';
                                                                            }
                                                                        }else{
                                                                            echo '<option value="NA">No User Found!!!</option>';
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-12">
                                                            <div class="position-relative form-group">
                                                                <input type="hidden" name="stc_alot_project_action">
                                                                <button class="mt-1 btn btn-warning">Add</button>
                                                            </div>
                                                        </div>
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
        function myFunction() {
          var input = document.getElementById("searchuser");
          var filter = input.value.toLowerCase();
          var nodes = document.getElementsByClassName('usertarget');

          for (i = 0; i < nodes.length; i++) {
            if (nodes[i].innerText.toLowerCase().includes(filter)) {
              nodes[i].style.display = "block";
            } else {
              nodes[i].style.display = "none";
            }
          }
        }
        $(document).ready(function(e){
            $('.create-supervisor-form').on('submit', function(e){
                e.preventDefault();
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : new FormData(this),
                    contentType : false,
                    processData : false,
                    dataType    : "JSON",
                    success     : function(argument) {
                        // console.log(argument);
                        if (argument == "yes") {
                          alert("User created!!!");
                          $(".create-supervisor-form")[0].reset();
                        } else if (argument == "no") {
                          alert("Please check & try again!!!");
                        } else if (argument == "logout") {
                          // alert("Please check & try again!!!&#9786;");
                          window.location.reload();
                        } else if (argument == "not") {
                          alert("This User is already in our records!!!");
                        } else {
                          alert("Do not empty any field!!!");
                        }
                    }
                });
            });

            // call user
            $('.stc-user-edit').on('click', function(){
                var user_id = $(this).attr('id');
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        stc_load_single_user:1,
                        user_id:user_id
                    },
                    dataType    : "JSON",
                    success     : function(argument) {
                        // console.log(argument);
                        if (argument == "empty") {
                          alert("No record found.");
                        } else if (argument == "logout") {
                          // alert("Please check & try again!!!&#9786;");
                          window.location.reload();
                        }

                        $(".stc-edituser-save").attr('id', argument['stc_cust_pro_supervisor_id']);
                        $("#stc_cust_sup_cust").val(argument['stc_cust_pro_supervisor_cust_id']);
                        $("#stc_cust_sup_fullname").val(argument['stc_cust_pro_supervisor_fullname']);
                        $("#stc_cust_sup_contnumber").val(argument['stc_cust_pro_supervisor_contact']);
                        $("#stc_cust_sup_whatsapp").val(argument['stc_cust_pro_supervisor_whatsapp']);
                        $("#stc_cust_sup_email").val(argument['stc_cust_pro_supervisor_email']);
                        $("#stc_cust_sup_address").val(argument['stc_cust_pro_supervisor_address']);
                        $("#stc_cust_sup_pincode").val(argument['stc_cust_pro_supervisor_pincode']);
                        $("#stc_cust_sup_city").val(argument['stc_cust_pro_supervisor_cityid']);
                        $("#stc_cust_sup_state").val(argument['stc_cust_pro_supervisor_state_id']);
                        $("#stc_cust_sup_category").val(argument['stc_cust_pro_supervisor_category']);
                        $("#stc_cust_sup_status").val(argument['stc_cust_pro_supervisor_status']);
                    }
                });
            });

            // update user record
            $('.stc-edituser-save').on('click', function(){
                var user_id = $(this).attr('id');
                var cust_id = $("#stc_cust_sup_cust").val();
                var fullname = $("#stc_cust_sup_fullname").val();
                var contnumber = $("#stc_cust_sup_contnumber").val();
                var whatsapp = $("#stc_cust_sup_whatsapp").val();
                var email = $("#stc_cust_sup_email").val();
                var address = $("#stc_cust_sup_address").val();
                var pincode = $("#stc_cust_sup_pincode").val();
                var city = $("#stc_cust_sup_city").val();
                var state = $("#stc_cust_sup_state").val();
                var category = $("#stc_cust_sup_category").val();
                var status = $("#stc_cust_sup_status").val();
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        stc_update_single_user:1,
                        user_id:user_id,
                        cust_id:cust_id,
                        fullname:fullname,
                        contnumber:contnumber,
                        whatsapp:whatsapp,
                        email:email,
                        address:address,
                        pincode:pincode,
                        city:city,
                        state:state,
                        category:category,
                        status:status
                    },
                    dataType    : "JSON",
                    success     : function(argument) {                        
                        if (argument == "yes") {
                          alert("User updated!!!");
                          window.location.reload();
                        } else if (argument == "no") {
                          alert("Please check & try again!!!");
                        } else if (argument == "logout") {
                          window.location.reload();
                        } else if (argument == "empty") {
                          alert("Do not empty any field!!!");
                        }
                    }
                });
            });

            // call user
            $('.stc-user-view').on('click', function(){
                var user_id = $(this).attr('id');
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        stc_load_single_user:1,
                        user_id:user_id
                    },
                    dataType    : "JSON",
                    success     : function(argument) {
                        // console.log(argument);
                        if (argument == "empty") {
                          alert("No record found.");
                        } else if (argument == "logout") {
                          // alert("Please check & try again!!!&#9786;");
                          window.location.reload();
                        }
                        $("#vstc_cust_sup_cust").val(argument['stc_cust_pro_supervisor_cust_id']);
                        $("#vstc_cust_sup_fullname").val(argument['stc_cust_pro_supervisor_fullname']);
                        $("#vstc_cust_sup_contnumber").val(argument['stc_cust_pro_supervisor_contact']);
                        $("#vstc_cust_sup_whatsapp").val(argument['stc_cust_pro_supervisor_whatsapp']);
                        $("#vstc_cust_sup_email").val(argument['stc_cust_pro_supervisor_email']);
                        $("#vstc_cust_sup_address").val(argument['stc_cust_pro_supervisor_address']);
                        $("#vstc_cust_sup_pincode").val(argument['stc_cust_pro_supervisor_pincode']);
                        $("#vstc_cust_sup_city").val(argument['stc_cust_pro_supervisor_cityid']);
                        $("#vstc_cust_sup_state").val(argument['stc_cust_pro_supervisor_state_id']);
                        $("#vstc_cust_sup_category").val(argument['stc_cust_pro_supervisor_category']);
                        $("#vstc_cust_sup_status").val(argument['stc_cust_pro_supervisor_status']);
                        $("#vstc_cust_sup_sitename").html(argument['Sitename']);
                        console.log(argument['Sitename']);
                    }
                });
            });
            
            $('.stc-project-alot-cust').on('submit', function(e){
                e.preventDefault();
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : new FormData(this),
                    contentType : false,
                    processData : false,
                    dataType    : "JSON",
                    success     : function(argument) {
                        // console.log(argument);
                        if (argument == "yes") {
                          alert("Project alotted!!!");
                          $(".stc-project-alot-cust")[0].reset();
                        } else if (argument == "no") {
                          alert("Please check & try again!!!");
                        } else if (argument == "logout") {
                          // alert("Please check & try again!!!&#9786;");
                          window.location.reload();
                        } else if (argument == "not") {
                          alert("This User is already alotted on that project!!!");
                        } else {
                          alert("Do not empty any field!!!");
                        }
                    }
                });
            });
        });

        $(document).ready(function(){
            $('body').delegate('.ag-show-grid', 'click', function(e){
                var odid=$(this).attr("id");
                $('#togdiv'+odid).toggle(400);
                $.ajax({
                    url : "nemesis/stc_project.php",
                    method : "POST",
                    data : {
                        get_orders_pert:1,
                        odid:odid
                    },
                    success : function(orders){
                        // console.log(orders);
                        $('#togdiv'+odid).html(orders);
                    }
                });
                // alert(odid);
            });

            $('body').delegate('.setforwardaction', 'click', function(e){
                var odid=$(this).attr("id");
                var validate_value=$('#setforwardactionvalue'+odid).val();
                // $('#togdiv'+odid).toggle(400);
                $.ajax({
                    url : "nemesis/stc_project.php",
                    method : "POST",
                    data : {
                        set_for_validate:1,
                        odid:odid,
                        validate_value:validate_value
                    },
                    dataType: "JSON",
                    success : function(action){
                        // console.log(orders);
                        // $('#togdiv'+odid).html(orders);
                        alert(action['action']);
                        $('#togdiv'+odid).html(action['reaction']);

                    }
                });
                // alert(odid+validate_value);
            });

            $('body').delegate('.placerequisition', 'click', function(e){
                var odid=$(this).attr("id");
                $.ajax({
                    url : "nemesis/stc_project.php",
                    method : "POST",
                    data : {
                        place_requisition:1,
                        odid:odid
                    },
                    success : function(orders){
                        // console.log(orders);
                        // $('#togdiv'+odid).html(orders);
                        if(orders == "reload"){
                            alert("Session expired. Reloading....");
                            window.location.reload();
                        }else if(orders == "no"){
                            alert("Cant placed your requisition. Please check remains quantity & enable item first!!!");
                        }else{
                            alert(orders);
                            $('#togdiv'+odid).toggle(400);
                        }                        
                    }
                });
            });

            $('body').delegate('.settoclean', 'click', function(e){
                var odid=$(this).attr("id");
                $.ajax({
                    url : "nemesis/stc_project.php",
                    method : "POST",
                    data : {
                        clean_requisition:1,
                        odid:odid
                    },
                    success : function(orders){
                            alert(orders);
                            $('#togdiv'+odid).toggle(400);
                    }
                });
            });
        });
    </script>
</body>
</html>
<div class="modal fade bd-adduser-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="main-card mb-3 card">
                            <div class="card-body"><h5 class="card-title">Create User</h5>
                                <form class="create-supervisor-form">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            <div class="position-relative form-group">
                                                <label for="exampleSelect" class="">Customer name</label>
                                                <select name="stc_cust_sup_cust" class="form-control" required>
                                                    <?php 
                                                        include_once("../MCU/db.php");
                                                        $cityqry=mysqli_query($con, "
                                                            SELECT distinct `stc_customer_id`, `stc_customer_name` FROM `stc_customer`
                                                            INNER JOIN `stc_agent_requested_customer` 
                                                            ON `stc_agent_requested_customer_cust_id`=`stc_customer_id` 
                                                            ORDER BY `stc_customer_name` ASC

                                                        ");
                                                        foreach($cityqry as $custrow){
                                                            echo '<option value="'.$custrow['stc_customer_id'].'">'.$custrow['stc_customer_name'].'</option>';
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="exampleEmail" class="">User full name</label>
                                                <input placeholder="Enter your User name" type="text" name="stc_cust_sup_fullname" class="mb-2 form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="exampleEmail" class="">User contact</label>
                                                <input placeholder="Enter your User contact number" type="number" name="stc_cust_sup_contnumber" class="mb-2 form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="exampleEmail" class="">User whatsapp</label>
                                                <input placeholder="Enter your User Whatsapp" type="number" name="stc_cust_sup_whatsapp" class="mb-2 form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="exampleEmail" class="">User email</label>
                                                <input placeholder="Enter your User E-mail" type="text" name="stc_cust_sup_email" class="mb-2 form-control" required>
                                            </div>
                                        </div>
                                    </div>                                                        
                                    <div class="position-relative form-group">
                                        <label for="exampleText" class="">User address</label>
                                        <textarea id="exampleText" class="form-control" name="stc_cust_sup_address" placeholder="Enter your User address" required></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="position-relative form-group">
                                                <label for="exampleEmail" class="">User pincode</label>
                                                <input placeholder="Enter your User pincode" type="text" name="stc_cust_sup_pincode" class="mb-2 form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="position-relative form-group">
                                                <label for="exampleSelect" class="">City</label>
                                                <select name="stc_cust_sup_city" class="form-control" required>
                                                    <?php 
                                                        $cityqry=mysqli_query($con, "
                                                            SELECT * FROM `stc_city` ORDER BY `stc_city_name` ASC
                                                        ");
                                                        foreach($cityqry as $cityrow){
                                                            echo '<option value="'.$cityrow['stc_city_id'].'">'.$cityrow['stc_city_name'].'</option>';
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="position-relative form-group">
                                                <label for="exampleSelect" class="">State</label>
                                                <select name="stc_cust_sup_state" class="form-control" required>
                                                    <?php 
                                                        include_once("../MCU/db.php");
                                                        $cityqry=mysqli_query($con, "
                                                            SELECT * FROM `stc_state` ORDER BY `stc_state_name` ASC
                                                        ");
                                                        foreach($cityqry as $cityrow){
                                                            echo '<option value="'.$cityrow['stc_state_id'].'">'.$cityrow['stc_state_name'].'</option>';
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>                                                                
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="usercat" class="">User Category</label>
                                                <select class="form-control" id="usercat" name="stc_cust_sup_category">
                                                    <option>Coordinator</option>
                                                    <option>Electrician</option>
                                                    <option>Operator</option>
                                                    <option>Helper</option>
                                                    <option>Safety Supervisor</option>
                                                    <option>Site Incharge</option>
                                                    <option selected>Supervisor</option>
                                                    <option>Technician</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="exampleSelect" class="">Status</label>
                                                <select name="stc_cust_sup_status" class="form-control" required>
                                                    <option value="1">Active</option>
                                                    <option value="0">off</option>
                                                </select>
                                                <input type="hidden" name="stc_cust_supervisor_action">
                                            </div>
                                        </div>
                                    </div>                                                    
                                    <button class="mt-1 btn btn-primary form-control">Add</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade bd-edituser-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="main-card mb-3 card">
                            <div class="card-body"><h5 class="card-title">Create User</h5>
                                <div class="row">
                                    <div class="col-sm-12 col-md-12">
                                        <div class="position-relative form-group">
                                            <label for="stc_cust_sup_cust" class="">Customer name</label>
                                            <select id="stc_cust_sup_cust" class="form-control" required>
                                                <?php 
                                                    $cityqry=mysqli_query($con, "
                                                        SELECT distinct `stc_customer_id`, `stc_customer_name` FROM `stc_customer`
                                                        INNER JOIN `stc_agent_requested_customer` 
                                                        ON `stc_agent_requested_customer_cust_id`=`stc_customer_id` 
                                                        ORDER BY `stc_customer_name` ASC

                                                    ");
                                                    foreach($cityqry as $custrow){
                                                        echo '<option value="'.$custrow['stc_customer_id'].'">'.$custrow['stc_customer_name'].'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="stc_cust_sup_fullname" class="">User full name</label>
                                            <input placeholder="Enter your User name" type="text" id="stc_cust_sup_fullname" class="mb-2 form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="stc_cust_sup_contnumber" class="">User contact</label>
                                            <input placeholder="Enter your User contact number" type="number" id="stc_cust_sup_contnumber" class="mb-2 form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="stc_cust_sup_whatsapp" class="">User whatsapp</label>
                                            <input placeholder="Enter your User Whatsapp" type="number" id="stc_cust_sup_whatsapp" class="mb-2 form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="stc_cust_sup_email" class="">User email</label>
                                            <input placeholder="Enter your User E-mail" type="text" id="stc_cust_sup_email" class="mb-2 form-control" required>
                                        </div>
                                    </div>
                                </div>                                                        
                                <div class="position-relative form-group">
                                    <label for="stc_cust_sup_address" class="">User address</label>
                                    <textarea class="form-control" id="stc_cust_sup_address" placeholder="Enter your User address" required></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="position-relative form-group">
                                            <label for="stc_cust_sup_pincode" class="">User pincode</label>
                                            <input placeholder="Enter your User pincode" type="text" id="stc_cust_sup_pincode" class="mb-2 form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="position-relative form-group">
                                            <label for="stc_cust_sup_city" class="">City</label>
                                            <select id="stc_cust_sup_city" class="form-control" required>
                                                <?php 
                                                    $cityqry=mysqli_query($con, "
                                                        SELECT * FROM `stc_city` ORDER BY `stc_city_name` ASC
                                                    ");
                                                    foreach($cityqry as $cityrow){
                                                        echo '<option value="'.$cityrow['stc_city_id'].'">'.$cityrow['stc_city_name'].'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="position-relative form-group">
                                            <label for="stc_cust_sup_state" class="">State</label>
                                            <select id="stc_cust_sup_state" class="form-control" required>
                                                <?php 
                                                    $cityqry=mysqli_query($con, "
                                                        SELECT * FROM `stc_state` ORDER BY `stc_state_name` ASC
                                                    ");
                                                    foreach($cityqry as $cityrow){
                                                        echo '<option value="'.$cityrow['stc_state_id'].'">'.$cityrow['stc_state_name'].'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>                                                                
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="stc_cust_sup_category" class="">User Category</label>
                                            <select class="form-control" id="stc_cust_sup_category">
                                                <option>Coordinator</option>
                                                <option>Electrician</option>
                                                <option>Operator</option>
                                                <option>Helper</option>
                                                <option>Safety Supervisor</option>
                                                <option>Site Incharge</option>
                                                <option selected>Supervisor</option>
                                                <option>Technician</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="stc_cust_sup_status" class="">Status</label>
                                            <select id="stc_cust_sup_status" class="form-control" required>
                                                <option value="1">Active</option>
                                                <option value="0">off</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>                                                    
                                <button class="mt-1 btn btn-primary form-control stc-edituser-save">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-btn" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-showuser-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Show User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="main-card mb-3 card">
                            <div class="card-body"><h5 class="card-title">Create User</h5>
                                <div class="row">
                                    <div class="col-sm-12 col-md-12">
                                        <div class="position-relative form-group">
                                            <label for="vstc_cust_sup_cust" class="">Customer name</label>
                                            <select id="vstc_cust_sup_cust" class="form-control" disabled>
                                                <?php 
                                                    $cityqry=mysqli_query($con, "
                                                        SELECT distinct `stc_customer_id`, `stc_customer_name` FROM `stc_customer`
                                                        INNER JOIN `stc_agent_requested_customer` 
                                                        ON `stc_agent_requested_customer_cust_id`=`stc_customer_id` 
                                                        ORDER BY `stc_customer_name` ASC

                                                    ");
                                                    foreach($cityqry as $custrow){
                                                        echo '<option value="'.$custrow['stc_customer_id'].'">'.$custrow['stc_customer_name'].'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="vstc_cust_sup_fullname" class="">User full name</label>
                                            <input placeholder="Enter your User name" type="text" id="vstc_cust_sup_fullname" disabled class="mb-2 form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="vstc_cust_sup_contnumber" class="">User contact</label>
                                            <input placeholder="Enter your User contact number" type="number" id="vstc_cust_sup_contnumber" disabled class="mb-2 form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="vstc_cust_sup_whatsapp" class="">User whatsapp</label>
                                            <input placeholder="Enter your User Whatsapp" type="number" id="vstc_cust_sup_whatsapp" disabled class="mb-2 form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="vstc_cust_sup_email" class="">User email</label>
                                            <input placeholder="Enter your User E-mail" type="text" id="vstc_cust_sup_email" disabled class="mb-2 form-control" required>
                                        </div>
                                    </div>
                                </div>                                                        
                                <div class="position-relative form-group">
                                    <label for="vstc_cust_sup_address" class="">User address</label>
                                    <textarea class="form-control" id="vstc_cust_sup_address" disabled placeholder="Enter your User address" required></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="position-relative form-group">
                                            <label for="vstc_cust_sup_pincode" class="">User pincode</label>
                                            <input placeholder="Enter your User pincode" type="text" disabled id="vstc_cust_sup_pincode" class="mb-2 form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="position-relative form-group">
                                            <label for="vstc_cust_sup_city" class="">City</label>
                                            <select id="vstc_cust_sup_city" class="form-control" disabled required>
                                                <?php 
                                                    $cityqry=mysqli_query($con, "
                                                        SELECT * FROM `stc_city` ORDER BY `stc_city_name` ASC
                                                    ");
                                                    foreach($cityqry as $cityrow){
                                                        echo '<option value="'.$cityrow['stc_city_id'].'">'.$cityrow['stc_city_name'].'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="position-relative form-group">
                                            <label for="vstc_cust_sup_state" class="">State</label>
                                            <select id="vstc_cust_sup_state" class="form-control" disabled required>
                                                <?php 
                                                    $cityqry=mysqli_query($con, "
                                                        SELECT * FROM `stc_state` ORDER BY `stc_state_name` ASC
                                                    ");
                                                    foreach($cityqry as $cityrow){
                                                        echo '<option value="'.$cityrow['stc_state_id'].'">'.$cityrow['stc_state_name'].'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>                                                                
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="vstc_cust_sup_category" class="">User Category</label>
                                            <select class="form-control" disabled id="vstc_cust_sup_category">
                                                <option>Coordinator</option>
                                                <option>Electrician</option>
                                                <option>Operator</option>
                                                <option>Helper</option>
                                                <option>Safety Supervisor</option>
                                                <option>Site Incharge</option>
                                                <option selected>Supervisor</option>
                                                <option>Technician</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="vstc_cust_sup_status" class="">Status</label>
                                            <select id="vstc_cust_sup_status" disabled class="form-control" required>
                                                <option value="1">Active</option>
                                                <option value="0">off</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label for="vstc_cust_sup_sitename" class="">Sitename</label>
                                            <div id="vstc_cust_sup_sitename" style="background: #e9ecef; padding: 15px; border-radius: 0.25rem; border: 1px solid #ced4da;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>