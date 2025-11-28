<?php
session_start();

// Set session and cookie parameters
$session_duration = 24 * 60; // 24 minutes (cPanel limit)
$cookie_duration = 7 * 24 * 60 * 60; // 7 days in seconds

// If session exists
if(isset($_SESSION["stc_agent_id"])) {
    // Check if remember cookie exists, if not set it
    if(!isset($_COOKIE["stc_agent_remember"])) {
        setcookie("stc_agent_remember", $_SESSION["stc_agent_id"], time() + $cookie_duration, "/");
        setcookie("stc_agent_name", $_SESSION["stc_agent_name"], time() + $cookie_duration, "/");
        setcookie("stc_agent_role", $_SESSION["stc_agent_role"], time() + $cookie_duration, "/");
    }
} 
// If session doesn't exist but cookie does
elseif(isset($_COOKIE["stc_agent_remember"])) {
    // Restore session from cookie
    $_SESSION["stc_agent_id"] = $_COOKIE["stc_agent_remember"];
    $_SESSION["stc_agent_name"] = $_COOKIE["stc_agent_name"];
    $_SESSION["stc_agent_role"] = $_COOKIE["stc_agent_role"];
    // Optionally refresh the cookie
    setcookie("stc_agent_remember", $_COOKIE["stc_agent_remember"], time() + $cookie_duration, "/");
    setcookie("stc_agent_name", $_COOKIE["stc_agent_name"], time() + $cookie_duration, "/");
    setcookie("stc_agent_role", $_COOKIE["stc_agent_role"], time() + $cookie_duration, "/");
}
// Neither session nor cookie exists
else {
    header("Location: index.html");
    exit();
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
    <title>Project Management - STC</title>
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
        }@import url("https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css");
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
                                    <div>Project Management
                                        <div class="page-title-subheading">You can add your project information here for your experience with us<h3>&#9786;</h3>.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>            
                        <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                            <!-- <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-0" data-toggle="tab" href="#create-project">
                                    <span>Create Project <b>X</b></span>
                                </a>
                            </li> -->
                            <li class="nav-item">
                                <a role="tab" class="nav-link active" id="tab-1" data-toggle="tab" href="#show-project">
                                    <span>Show Project <b>O</b></span>
                                </a>
                            </li>
                            <!-- <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#check-linkup">
                                    <span>Add Project Details <b><i class="fas fa-plus"></i></b></span>
                                </a>
                            </li> -->
                            <?php 
                                if($_SESSION['stc_agent_role']==3){
                            ?>   
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-3" data-toggle="tab" href="#ahu-details">
                                    <span>Add AHU Details <b><i class="fas fa-cogs"></i></b></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-4" data-toggle="tab" href="#pump-details">
                                    <span>Add Pump Details <b><i class="fa fa-industry"></i></b></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-4" data-toggle="tab" href="#job-type">
                                    <span>Add Job Type <b><i class="fa fa-tasks"></i></b></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-5" data-toggle="tab" href="#dept">
                                    <span>Add Department <b><i class="fa fa-tasks"></i></b></span>
                                </a>
                            </li>
                            <?php      
                                }
                            ?>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane tabs-animation fade show active" id="show-project" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card" style="width: auto;overflow-x: auto; white-space: nowrap;">
                                            <div class="card-body"><h5 class="card-title">Show Project</h5>
                                                <!-- <input type="text" class="form-control all_project_search mb-3" id="searchproject" onkeyup="myFunction()" placeholder="Search By Project Name"> -->
                                                <div class="row mb-3 mt-3">
                                                    <div class="col-md-3">
                                                        <button type="button" class="btn mr-2 mb-2 btn-primary form-control stcAdduser" data-toggle="modal" data-target=".bd-addproject-modal-lg"><i class="fas fa-plus-circle"></i> Add Project</button>
                                                    </div>
                                                    <div class="col-md-9">
                                                <input type="text" class="form-control all_project_search mb-3" id="searchproject" onkeyup="myFunction()" placeholder="Search By Project Name">
                                                    </div>                                      
                                                </div>
                                                <div class="row">
                                                    <?php
                                                        $get_project_qry=mysqli_query($con, "
                                                            SELECT DISTINCT
                                                                `stc_cust_project_id`,
                                                                `stc_cust_project_date`,
                                                                `stc_cust_project_title`,
                                                                `stc_cust_project_refr`,
                                                                `stc_cust_project_address`,
                                                                `stc_cust_project_responsive_person`,
                                                                `stc_cust_project_status`
                                                            FROM `stc_cust_project` 
                                                            LEFT JOIN `stc_cust_project_collaborate`
                                                            ON `stc_cust_project_collaborate_projectid`=`stc_cust_project_id`
                                                            WHERE `stc_cust_project_createdby`='".$_SESSION['stc_agent_id']."'
                                                            OR `stc_cust_project_collaborate_teamid`='".$_SESSION['stc_agent_id']."'
                                                            ORDER BY `stc_cust_project_title` ASC
                                                        ");
                                                        
                                                        if($_SESSION['stc_agent_role']==3){
                                                            $get_project_qry=mysqli_query($con, "
                                                                SELECT DISTINCT
                                                                    `stc_cust_project_id`,
                                                                    `stc_cust_project_date`,
                                                                    `stc_cust_project_title`,
                                                                    `stc_cust_project_refr`,
                                                                    `stc_cust_project_address`,
                                                                    `stc_cust_project_responsive_person`,
                                                                    `stc_cust_project_status`
                                                                FROM `stc_cust_project`
                                                                INNER JOIN `stc_agent_requested_customer`
                                                                ON `stc_agent_requested_customer_cust_id`=`stc_cust_project_cust_id` 
                                                                INNER JOIN `stc_agents`
                                                                ON `stc_agent_requested_customer_agent_id`=`stc_agents_id` 
                                                                WHERE `stc_agents_id`='".$_SESSION['stc_agent_id']."'
                                                                ORDER BY `stc_cust_project_title` ASC
                                                            ");
                                                        }
                                                    ?>
                                                    <?php
                                                    if(mysqli_num_rows($get_project_qry)>0){
                                                        foreach($get_project_qry as $get_project_row){
                                                            $status='';
                                                            if($get_project_row['stc_cust_project_status']==1){
                                                                $status="Live";
                                                            }else{
                                                                $status="Closed";
                                                            }
                                                            $indust='';
                                                            if($_SESSION['stc_agent_role']==3){
                                                                $indust='
                                                                    <a href="#" id="'.$get_project_row['stc_cust_project_id'].'" title = "Project AHU Details" class="stc-project-ahu-show-ret" style="font-size: 25px;color: #cc7676;"  data-toggle="modal" data-target=".bd-projectahudetails-modal-lg">
                                                                        <i class="fas fa-cogs"></i>
                                                                    </a>
                                                                    <a href="#" id="'.$get_project_row['stc_cust_project_id'].'" title = "Project Pump Details" class="stc-project-pump-show-ret" style="font-size: 25px;color: #cc7676;"  data-toggle="modal" data-target=".bd-projectpumpdetails-modal-lg">
                                                                        <i class="fa fa-industry"></i>
                                                                    </a>
                                                                ';
                                                            }
                                                            $sharedinfo='Your project.';
                                                            $get_projectc_qry=mysqli_query($con, "
                                                                SELECT `stc_agents_name`
                                                                FROM `stc_cust_project_collaborate`
                                                                LEFT JOIN `stc_agents`
                                                                ON `stc_agents_id`=`stc_cust_project_collaborate_managerid`
                                                                WHERE `stc_cust_project_collaborate_projectid`='".$get_project_row['stc_cust_project_id']."'
                                                                AND `stc_cust_project_collaborate_teamid`='".$_SESSION['stc_agent_id']."'
                                                                AND `stc_cust_project_collaborate_status`=1
                                                            ");
                                                            if(mysqli_num_rows($get_projectc_qry)>0){
                                                                $manager='';
                                                                foreach($get_projectc_qry as $get_projectc_row){
                                                                    $manager=$get_projectc_row['stc_agents_name'];
                                                                }
                                                                $sharedinfo='<b>'.$manager.'</b> shared this Project with you.';
                                                            }
                                                            $blacknwhitestyle='style="-webkit-filter: grayscale(100%);"';
                                                            if($get_project_row['stc_cust_project_status']=='1'){
                                                                $blacknwhitestyle='';
                                                            }
                                                            $project_address = $get_project_row['stc_cust_project_address'];
                                                            if (strlen($project_address) > 40) {
                                                                $first_part = substr($project_address, 0, 40);                                                               
                                                                $project_address= '<span title="' . $project_address . '">'.$first_part.'...</span>';
                                                            }
                                                            $projectname=$get_project_row['stc_cust_project_title'];
                                                            if (strlen($projectname) > 30) {
                                                                $first_part = substr($projectname, 0, 30);                                                           
                                                                $projectname= '<span title="' . $get_project_row['stc_cust_project_title'] . '">'.$first_part.'...</span>';
                                                            }
                                                            echo '<div class="col-md-3 projecttarget" style="margin-bottom:15px;">
                                                                    <div class="card profile-card-3" '.$blacknwhitestyle.'>
                                                                        <div class="background-block">
                                                                            <img src="https://images.pexels.com/photos/459225/pexels-photo-459225.jpeg?auto=compress&cs=tinysrgb&h=650&w=940" alt="profile-sample1" class="background"/>
                                                                        </div>
                                                                        <div class="card-content">
                                                                            <h2>'.$projectname.'<small>'.$get_project_row['stc_cust_project_refr'].'</small></h2>
                                                                            <input type="hidden" id="stc-ag-edittitle'.$get_project_row['stc_cust_project_id'].'" value="'.$get_project_row['stc_cust_project_title'].'">
                                                                            <input type="hidden" id="stc-ag-editrefre'.$get_project_row['stc_cust_project_id'].'" value="'.$get_project_row['stc_cust_project_refr'].'">
                                                                            <input type="hidden" id="stc-ag-editaddress'.$get_project_row['stc_cust_project_id'].'" value="'.$get_project_row['stc_cust_project_address'].'">
                                                                            <h3 style="font-size: 0.75rem;">
                                                                                '.$project_address.'<br>
                                                                                '.$get_project_row['stc_cust_project_responsive_person'].'<br>
                                                                                '.$sharedinfo.'

                                                                            </h3>
                                                                            <div class="icon-block">
                                                                                <a href="#" id="'.$get_project_row['stc_cust_project_id'].'" title = "Edit Project" class="stc-project-edit-ret" style="font-size: 25px;color: #cc7676;" data-toggle="modal" data-target=".bd-projectedit-modal-lg"> <i class="fas fa-edit"></i></a>
                                                                                <a href="#" id="'.$get_project_row['stc_cust_project_id'].'" title = "View Connected Supervisor" class="stc-project-show-consup" style="font-size: 25px;color: #cc7676;" data-toggle="modal" data-target=".bd-connproject-modal-lg"> <i class="fas fa-user"></i></a>
                                                                                <a href="#" id="'.$get_project_row['stc_cust_project_id'].'" title = "View Project Details" class="stc-project-show-ret" style="font-size: 25px;color: #cc7676;" data-toggle="modal" data-target=".bd-projectdetails-modal-lg"> <i class="fas fa-eye"></i></a>
                                                                                <a href="#" id="'.$get_project_row['stc_cust_project_id'].'" title = "Collaborate" class="stc-project-collaborate" style="font-size: 25px;color: #cc7676;" data-toggle="modal" data-target=".bd-collaborate-modal-lg"> <i class="fas fa-handshake-o"></i></a>
                                                                                <a href="#" id="'.$get_project_row['stc_cust_project_id'].'" title = "Add Project Details" class="add-project-details-btn" style="font-size: 25px;color: #cc7676;" data-toggle="modal" data-target=".bd-addprojectdetails-modal-lg"> <i class="fas fa-file"></i></a>
                                                                                '.$indust.'
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            ';
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane tabs-animation fade" id="ahu-details" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">Add AHU Details</h5>
                                                <div class="row">
                                                    <!-- <div class="col-sm-12 col-md-12">
                                                            <label for="exampleSelect" class=""><b>Select AHU Details Type</b></label>
                                                        </div>
                                                    <div class="col-sm-12 col-md-6">
                                                        <div class="position-relative form-group">
                                                            <input type="radio" style="float:left;width: 5%;height: calc(1.2rem + 2px);" class="form-control stc_cust_ahu_type" value="New" name="ahu_type" checked>  New
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6">
                                                        <div class="position-relative form-group">
                                                            <input type="radio" style="float:left;width: 5%;height: calc(1.2rem + 2px);" class="form-control stc_cust_ahu_type" value="Reclaimed" name="ahu_type">  Reclaimed
                                                        </div>
                                                    </div> -->
                                                    <div class="col-sm-12 col-md-12">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>Dept Name</b></label>
                                                            <select class="stc_cust_pro_dept_id form-control" required>
                                                                <option value="NA" selected>Please Select Project</option>
                                                                <?php 
                                                                    include_once("../MCU/db.php");
                                                                    $cityqry=mysqli_query($con, "
                                                                        SELECT 
                                                                            `stc_cust_project_id`,
                                                                            `stc_cust_project_title`
                                                                        FROM `stc_cust_project`
                                                                        INNER JOIN `stc_agent_requested_customer`
                                                                        ON `stc_agent_requested_customer_cust_id`=`stc_cust_project_cust_id` 
                                                                        INNER JOIN `stc_agents`
                                                                        ON `stc_agent_requested_customer_agent_id`=`stc_agents_id` 
                                                                        WHERE `stc_agents_id`='".$_SESSION['stc_agent_id']."'
                                                                        ORDER BY `stc_cust_project_title` ASC
                                                                    ");
                                                                    foreach($cityqry as $custrow){
                                                                        echo '<option value="'.$custrow['stc_cust_project_id'].'">'.$custrow['stc_cust_project_title'].'</option>';
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>Location Name</b></label>
                                                            <input type="text" class="form-control stc_cust_location_name" placeholder="Enter Location Name">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>Sub Location Name</b></label>
                                                            <input type="text" class="form-control stc_cust_sub_location_name" placeholder="Enter Sub Location Name">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>AHU No</b></label>
                                                            <input type="text" class="form-control stc_cust_ahu_no" placeholder="Enter AHU No">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>Designing Temp</b></label>
                                                            <input type="text" class="form-control stc_cust_desgn_temp" placeholder="Enter Designing Temp">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-2">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>Air Size</b></label>
                                                            <input type="text" class="form-control stc_cust_air_size" placeholder="Enter Air SIze">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-2">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>Air Qty</b></label>
                                                            <input type="text" class="form-control stc_cust_air_qty" placeholder="Enter Air Qty">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-2">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>V-Belt Size</b></label>
                                                            <input type="text" class="form-control stc_cust_v_belt_size" placeholder="Enter V - Belt Size">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-2">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>V-Belt Qty</b></label>
                                                            <input type="text" class="form-control stc_cust_v_belt_qty" placeholder="Enter V - Belt Qty">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-2">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>Blower Pulley Size</b></label>
                                                            <input type="text" class="form-control stc_cust_blower_pulley_size" placeholder="Enter Blower Pulley SIze">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-2">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>Motor Pulley Qty</b></label>
                                                            <input type="text" class="form-control stc_cus_motor_pulley_size" placeholder="Enter Motor Pulley SIze">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-2">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>Blower Bearing DE</b></label>
                                                            <input type="text" class="form-control stc_cus_blower_bearing_de" placeholder="Enter Blower Bearing DE">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-2">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>Blower Bearing NDE</b></label>
                                                            <input type="text" class="form-control stc_cus_blower_bearing_nde" placeholder="Enter Blower Bearing NDE">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-2">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>Blower Housing</b></label>
                                                            <input type="text" class="form-control stc_cus_blower_bearing_housing" placeholder="Enter Blower Bearing Housing">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-3">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>Motor Bearing DE</b></label>
                                                            <input type="text" class="form-control stc_cus_motor_bearing_de" placeholder="Enter Motor Bearing DE">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-3">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>Motor Bearing NDE</b></label>
                                                            <input type="text" class="form-control stc_cus_motor_bearing_nde" placeholder="Enter Motor Bearing NDE">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-3">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>Motor Capacity HP</b></label>
                                                            <input type="text" class="form-control stc_cus_motor_capacity_hp" placeholder="Enter Motor Capacity HP">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-3">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>Motor Capacity KW</b></label>
                                                            <input type="text" class="form-control stc_cus_motor_capacity_kw" placeholder="Enter Motor Capacity KW">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-3">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>Motor Capacity AMPS</b></label>
                                                            <input type="text" class="form-control stc_cus_motor_capacity_amps" placeholder="Enter Motor Capacity AMPS">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-3">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>Motor Capacity RPM</b></label>
                                                            <input type="text" class="form-control stc_cus_motor_capacity_rpm" placeholder="Enter Capacity RPM">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-4">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>Motor Weight</b></label>
                                                            <input type="text" class="form-control stc_cus_motor_weight" placeholder="Enter Motor Weight">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-4">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>Frame Size</b></label>
                                                            <input type="text" class="form-control stc_cus_ahu_frame_size" placeholder="Enter Frame SIze">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-4">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>CFM</b></label>
                                                            <input type="text" class="form-control stc_cus_ahu_cfm" placeholder="Enter CFM">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-12">
                                                        <div class="position-relative form-group">
                                                            <input type="submit" class="form-control btn btn-success stc_cus_ahusave" value="Save">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane tabs-animation fade" id="pump-details" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">Add Pump Details</h5>
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-12">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>Location</b></label>
                                                            <select class="stc_cust_pro_location_id form-control" required>
                                                                <option value="NA" selected>Please Select Project</option>
                                                                <?php 
                                                                    include_once("../MCU/db.php");
                                                                    $cityqry=mysqli_query($con, "
                                                                        SELECT 
                                                                            `stc_cust_project_id`,
                                                                            `stc_cust_project_title`
                                                                        FROM `stc_cust_project`
                                                                        INNER JOIN `stc_agent_requested_customer`
                                                                        ON `stc_agent_requested_customer_cust_id`=`stc_cust_project_cust_id` 
                                                                        INNER JOIN `stc_agents`
                                                                        ON `stc_agent_requested_customer_agent_id`=`stc_agents_id` 
                                                                        WHERE `stc_agents_id`='".$_SESSION['stc_agent_id']."'
                                                                        ORDER BY `stc_cust_project_title` ASC

                                                                    ");
                                                                    foreach($cityqry as $custrow){
                                                                        echo '<option value="'.$custrow['stc_cust_project_id'].'">'.$custrow['stc_cust_project_title'].'</option>';
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>Sub Location/Department</b></label>
                                                            <input type="text" class="form-control stc_cust_sub_location" placeholder="Enter Sub Location Name">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>Area</b></label>
                                                            <input type="text" class="form-control stc_cust_area" placeholder="Enter Area">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>Equipment Details</b></label>
                                                            <input type="text" class="stc_cust_equipment_type form-control" placeholder="Enter Equipment Details">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>Equipment Number</b></label>
                                                            <input type="text" class="form-control stc_cust_equipment_number" placeholder="Enter Equipment Number">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>Capacity</b></label>
                                                            <input type="number" class="form-control stc_cus_totaltr" placeholder="Enter Capacity">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>Unit</b></label>
                                                            <input type="text" class="form-control stc_cus_Unit" placeholder="Enter Unit">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-12">
                                                        <div class="position-relative form-group">
                                                            <input type="submit" class="form-control btn btn-success stc_cus_pumpsave" value="Save">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane tabs-animation fade" id="job-type" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">Add Job Type</h5>
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-6">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>Job Type</b></label>
                                                            <select class="stc_cust_job_type form-control" required>
                                                                <option value="NA">Please select job type</option>
                                                                <option>ELECTRICAL</option>
                                                                <option>MECHANICAL</option>
                                                                <option>REFRIGERATION</option>
                                                                <option>CLEANING</option>
                                                                <option>NOISY</option>
                                                                <option>WATER DROPPING</option>
                                                                <option>LUBRICATION</option>
                                                                <option>CHECKING/MAINTENANCE</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>Job Variites</b></label>
                                                            <input type="text" class="form-control stc_job_varities" placeholder="Enter Job Variites Name">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-12">
                                                        <div class="position-relative form-group">
                                                            <input type="submit" class="form-control btn btn-success stc_cus_jtypesave" value="Save">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">Show Job Type</h5>
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-6">
                                                        <input type="text" class="form-control" id="job-type-tableInput"  placeholder="Search by choice">
                                                        <table class="table table-hover table-bordered table-responsive" id="job-type-table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Sl No</th>
                                                                    <th>Job Type</th>
                                                                    <th>Job Varities</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="job-type-show">                                                         
                                                                <tr>
                                                                    <td>Loading..</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane tabs-animation fade" id="dept" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">Add Department</h5>
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                                        <h5 class="card-title">Project Name</h5>
                                                        <div class="position-relative form-group">
                                                            <select class="form-control stc_dept_pro_id">
                                                                <?php
                                                                    $cityqry=mysqli_query($con, "
                                                                        SELECT 
                                                                            `stc_cust_project_id`,
                                                                            `stc_cust_project_title`
                                                                        FROM `stc_cust_project`
                                                                        INNER JOIN `stc_agent_requested_customer`
                                                                        ON `stc_agent_requested_customer_cust_id`=`stc_cust_project_cust_id` 
                                                                        INNER JOIN `stc_agents`
                                                                        ON `stc_agent_requested_customer_agent_id`=`stc_agents_id` 
                                                                        WHERE `stc_agents_id`='".$_SESSION['stc_agent_id']."'
                                                                        ORDER BY `stc_cust_project_title` ASC

                                                                    ");
                                                                    foreach($cityqry as $custrow){
                                                                        echo '<option value="'.$custrow['stc_cust_project_id'].'">'.$custrow['stc_cust_project_title'].'</option>';
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>Location</b></label>
                                                            <input type="text" class="form-control stc_dept_locname" placeholder="Enter Location Name">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-6">
                                                        <div class="position-relative form-group">
                                                            <label for="exampleSelect" class=""><b>Department</b></label>
                                                            <input type="text" class="form-control stc_dept_deptname" placeholder="Enter Department Name">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-12">
                                                        <div class="position-relative form-group">
                                                            <input type="submit" class="form-control btn btn-success stc_cus_dept" value="Save">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">Show Department</h5>
                                                <div class="row">
                                                    <div class="col-sm-12 col-md-12">
                                                        <input type="text" class="form-control" id="department-tableInput"  placeholder="Search by choice">
                                                        <table class="table table-hover table-bordered" id="department-table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Sl No</th>
                                                                    <th>Project</th>
                                                                    <th>Location</th>
                                                                    <th>Department</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="department-show">                                                         
                                                                <tr>
                                                                    <td>Loading..</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
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
        function myFunction() {
          var input = document.getElementById("searchproject");
          var filter = input.value.toLowerCase();
          var nodes = document.getElementsByClassName('projecttarget');

          for (i = 0; i < nodes.length; i++) {
            if (nodes[i].innerText.toLowerCase().includes(filter)) {
              nodes[i].style.display = "block";
            } else {
              nodes[i].style.display = "none";
            }
          }
        }

        $(document).ready(function(e){
            // create project
            $('.create-project-form').on('submit', function(e){
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
                            alert("Project created successfully!!!");
                            $(".create-project-form")[0].reset();
                            window.location.reload();
                        } else if (argument == "no") {
                            alert("Please check & try again!!!&#9786");
                        } else if (argument == "reload") {
                            alert("Do not let any field empty!!!");
                        } else if (argument == "reload") {
                            window.location.reload();
                        } else if (argument == "not") {
                            alert("This project is already in our records!!!");
                        } else {
                            alert("Do not empty any field!!!");
                        }
                    }
                });
            });

            // to add some details 
            $('body').delegate('.stc-ag-save-project-record', 'click', function(e){
                e.preventDefault();
                var stc_pg_id=$('.stc-ag-project-id').val();
                var cust_name=$('.stc-ag-cust-name').val();
                var cust_emailid=$('.stc-ag-cust-emailid').val();
                var cust_cont_no=$('.stc-ag-cont-number').val();
                var purpose=$('.stc-ag-purpose').val();
                var ref_cont=$('.stc-ag-ref-contact').val();
                var job_details=$('.stc-ag-job-details').val();

                var quote_number=$('.stc-ag-quot-number').val();
                var quote_date=$('.stc-ag-quote-date').val();
                var basic_val=$('.stc-ag-basic-value').val();
                var gst_val=$('.stc-ag-gst-value').val();
                var quoted_by=$('.stc-ag-quoted-by').val();
                var mode_of_quotation=$('.stc-ag-mode-of-quotation').val();

                var target_price=$('.stc-ag-target-price').val();
                var status=$('.stc-ag-status').val();
                var remarks=$('.stc-ag-remarks').val();

                var po_number=$('.stc-ag-po-number').val();
                var po_value=$('.stc-ag-po-value').val();

                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        stc_ag_pro_hit:1,
                        stc_pg_id:stc_pg_id,
                        stc_cust_name:cust_name,
                        stc_cust_emailid:cust_emailid,
                        stc_cust_cont_no:cust_cont_no,
                        stc_purpose:purpose,
                        stc_ref_cont:ref_cont,
                        stc_job_details:job_details,
                        stc_quote_number:quote_number,
                        stc_quote_date:quote_date,
                        stc_basic_val:basic_val,
                        stc_gst_val:gst_val,
                        stc_quoted_by:quoted_by,
                        stc_mode_of_quotation:mode_of_quotation,
                        stc_target_price:target_price,
                        stc_status:status,
                        stc_remarks:remarks,
                        stc_po_number:po_number,
                        stc_po_value:po_value
                    },
                    success     : function(response){
                        var res=response.trim();
                        if(res=="Details added successfully."){
                            alert(response);
                            $('.stc-ag-pro-det-form')[0].reset();
                        }else{
                            alert(response);                            
                        }
                    }
                });
            });

            // call modal
            $('body').delegate('.stc-project-show-ret', 'click', function(e){
                e.preventDefault();
                var project_id=$(this).attr("id");
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        stc_ag_rproject_retrive:1,
                        project_id:project_id
                    },
                    dataType    : "JSON",
                    success     : function(res_data){
                        // console.log(res_data);
                        $('#stc-ag-project-id').val(res_data['stc_cust_project_title']);
                        $('#stc-ag-cust-name').val(res_data['stc_cust_project_details_cust_name']);
                        $('#stc-ag-cust-emailid').val(res_data['stc_cust_project_details_email_id']);
                        $('#stc-ag-cont-number').val(res_data['stc_cust_project_details_cont_no']);
                        $('#stc-ag-purpose').val(res_data['stc_cust_project_details_purpose']);
                        $('#stc-ag-ref-contact').val(res_data['stc_cust_project_details_ref_contact']);
                        $('#stc-ag-job-details').val(res_data['stc_cust_project_details_job_details']);
                        $('#stc-ag-quot-number').val(res_data['stc_cust_project_details_quote_number']);
                        $('#stc-ag-quote-date').val(res_data['stc_cust_project_details_quote_date']);
                        $('#stc-ag-basic-value').val(res_data['stc_cust_project_details_basic_val']);
                        $('#stc-ag-gst-value').val(res_data['stc_cust_project_details_gst_val']);
                        $('#stc-ag-quoted-by').val(res_data['stc_cust_project_details_quotation_by']);
                        $('#stc-ag-mode-of-quotation').val(res_data['stc_cust_project_details_mode_of_quote']);
                        $('#stc-ag-target-price').val(res_data['stc_cust_project_details_tar_price']);
                        $('#stc-ag-status').val(res_data['stc_cust_project_details_status']);
                        $('#stc-ag-remarks').val(res_data['stc_cust_project_details_remarks']);
                        $('#stc-ag-po-number').val(res_data['stc_cust_project_details_po_number']);
                        $('#tc-ag-po-value').val(res_data['stc_cust_project_details_po_value']);
                    }
                });
            });
            
            // call modal
            $('body').delegate('.stc-project-show-consup', 'click', function(e){
                e.preventDefault();
                var project_id=$(this).attr("id");
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        stc_ag_rsupervisorproject_retrive:1,
                        project_id:project_id
                    },
                    dataType    : "JSON",
                    success     : function(res_data){
                        // console.log(res_data);
                        $('.stc-ag-connectedsupervisor').html(res_data);
                    }
                });
            });

            // edit project
            $('body').delegate('.stc-project-edit-ret', 'click', function(e){
                e.preventDefault();
                var pro_id=$(this).attr("id");
                var pro_title=$('#stc-ag-edittitle'+pro_id).val();
                var pro_ref=$('#stc-ag-editrefre'+pro_id).val();
                var pro_address=$('#stc-ag-editaddress'+pro_id).val();
                $('#stc-ag-ed-project-id').val(pro_id);
                $('#stc-ag-ed-project-name').val(pro_title);
                $('#stc-ag-ed-project-refr').val(pro_ref);
                $('#stc-ag-ed-job-details').val(pro_address);
            });

            // call ahu modal show
            $('body').delegate('.stc-project-ahu-show-ret', 'click', function(e){
                e.preventDefault();
                var pro_id=$(this).attr("id");
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        stc_ag_rproject_ahu_details_call:1,
                        project_id:pro_id
                    },
                    dataType    : "JSON",
                    success     : function(res_data){
                        // console.log(res_data);
                        $('.bd-projectahudetails-modal-lg').modal("show");
                        $('.stc-project-ahu-details-table').html(res_data);
                    }
                });                
            });
            
            // call pump show
            $('body').delegate('.stc-project-pump-show-ret', 'click', function(e){
                e.preventDefault();
                var pro_id=$(this).attr("id");
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        stc_ag_rproject_pump_details_call:1,
                        project_id:pro_id
                    },
                    dataType    : "JSON",
                    success     : function(res_data){
                        // console.log(res_data);
                        $('.bd-projectpumpdetails-modal-lg').modal("show");
                        $('.stc-project-pump-details-table').html(res_data);
                    }
                });                
            });

            // save edit project details
            $('body').delegate('.stc-ag-ed-project-save', 'click', function(e){
                e.preventDefault();
                var pro_id=$('#stc-ag-ed-project-id').val();
                var pro_title=$('#stc-ag-ed-project-name').val();
                var pro_refr=$('#stc-ag-ed-project-refr').val();
                var pro_details=$('#stc-ag-ed-job-details').val();
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        stc_ag_rproject_edit:1,
                        pro_id:pro_id,
                        pro_title:pro_title,
                        pro_refr:pro_refr,
                        pro_details:pro_details
                    },
                    // dataType    : "JSON",
                    success     : function(res_data){
                        // console.log(res_data);
                        var reponse=res_data.trim();
                        if(reponse=="Details Edited Successfully."){
                            alert(reponse);
                        }else{
                            alert(reponse);
                        }
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function(){

            var ahu_type='';
            $('.stc_cust_ahu_type').click(function(){
                ahu_type = $(this).val(); 
            });

            // stc_cust_ahu_details();
            function stc_cust_ahu_details(){
                var cust_dept_id = $('.stc_cust_pro_dept_id').val();
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        stc_ag_rproject_ahu_details_retrieve:1,
                        cust_dept_id:cust_dept_id
                    },
                    dataType    : "JSON",
                    success     : function(res_data){
                        // console.log(res_data);
                        if(ahu_type=="Reclaimed"){
                            $('.stc_cust_location_name').val(res_data['stc_cahud_sub_location']);
                            $('.stc_cust_sub_location_name').val(res_data['stc_cahud_location']);
                            $('.stc_cust_ahu_no').val(res_data['stc_cahud_ahuno']);
                            $('.stc_cust_desgn_temp').val(res_data['stc_cahud_design_temp']);
                            $('.stc_cust_air_size').val(res_data['stc_cahud_airfilter_size']);
                            $('.stc_cust_air_qty').val(res_data['stc_cahud_airfilter_qty']);
                            $('.stc_cust_v_belt_size').val(res_data['stc_cahud_vbelt_size']);
                            $('.stc_cust_v_belt_qty').val(res_data['stc_cahud_vbelt_qty']);
                            $('.stc_cust_blower_pulley_size').val(res_data['stc_cahud_pulleysize_blower']);
                            $('.stc_cus_motor_pulley_size').val(res_data['stc_cahud_pulleysize_motor']);
                            $('.stc_cus_blower_bearing_de').val(res_data['stc_cahud_blowerbearingsize_de']);
                            $('.stc_cus_blower_bearing_nde').val(res_data['stc_cahud_blowerbearingsize_nde']);
                            $('.stc_cus_blower_bearing_housing').val(res_data['stc_cahud_blowerbearingsize_housing']);
                            $('.stc_cus_motor_bearing_de').val(res_data['stc_cahud_motorbearingsize_de']);
                            $('.stc_cus_motor_bearing_nde').val(res_data['stc_cahud_motorbearingsize_nde']);
                            $('.stc_cus_motor_capacity_hp').val(res_data['stc_cahud_motorcap_hp']);
                            $('.stc_cus_motor_capacity_kw').val(res_data['stc_cahud_motor_kw']);
                            $('.stc_cus_motor_capacity_amps').val(res_data['stc_cahud_motor_amps']);
                            $('.stc_cus_motor_capacity_rpm').val(res_data['stc_cahud_motor_rpm']);
                            $('.stc_cus_motor_weight').val(res_data['stc_cahud_motorwt']);
                            $('.stc_cus_ahu_frame_size').val(res_data['stc_cahud_frame_size']);
                            $('.stc_cus_ahu_cfm').val(res_data['stc_cahud_cfm']);
                        }
                    }
                });
            }

            $('.stc_cust_pro_dept_id').on('change', function(){
                stc_cust_ahu_details();
            });

            // save ahu details
            $('.stc_cus_ahusave').click(function(e){
                e.preventDefault();
                var cust_dept_id = $('.stc_cust_pro_dept_id').val();
                var stc_cust_location_name = $('.stc_cust_location_name').val();
                var stc_cust_sub_location_name = $('.stc_cust_sub_location_name').val();
                var stc_cust_ahu_no = $('.stc_cust_ahu_no').val();
                var stc_cust_desgn_temp = $('.stc_cust_desgn_temp').val();
                var stc_cust_air_size = $('.stc_cust_air_size').val();
                var stc_cust_air_qty = $('.stc_cust_air_qty').val();
                var stc_cust_v_belt_size = $('.stc_cust_v_belt_size').val();
                var stc_cust_v_belt_qty = $('.stc_cust_v_belt_qty').val();
                var stc_cust_blower_pulley_size = $('.stc_cust_blower_pulley_size').val();
                var stc_cus_motor_pulley_size = $('.stc_cus_motor_pulley_size').val();
                var stc_cus_blower_bearing_de = $('.stc_cus_blower_bearing_de').val();
                var stc_cus_blower_bearing_nde = $('.stc_cus_blower_bearing_nde').val();
                var stc_cus_blower_bearing_housing = $('.stc_cus_blower_bearing_housing').val();
                var stc_cus_motor_bearing_de = $('.stc_cus_motor_bearing_de').val();
                var stc_cus_motor_bearing_nde = $('.stc_cus_motor_bearing_nde').val();
                var stc_cus_motor_capacity_hp = $('.stc_cus_motor_capacity_hp').val();
                var stc_cus_motor_capacity_kw = $('.stc_cus_motor_capacity_kw').val();
                var stc_cus_motor_capacity_amps = $('.stc_cus_motor_capacity_amps').val();
                var stc_cus_motor_capacity_rpm = $('.stc_cus_motor_capacity_rpm').val();
                var stc_cus_motor_weight = $('.stc_cus_motor_weight').val();
                var stc_cus_ahu_frame_size = $('.stc_cus_ahu_frame_size').val();
                var stc_cus_ahu_cfm = $('.stc_cus_ahu_cfm').val();
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        stc_ag_rproject_ahu_details_set:1,
                        cust_dept_id:cust_dept_id,
                        stc_cust_location_name:stc_cust_location_name,
                        stc_cust_sub_location_name:stc_cust_sub_location_name,
                        stc_cust_ahu_no:stc_cust_ahu_no,
                        stc_cust_desgn_temp:stc_cust_desgn_temp,
                        stc_cust_air_size:stc_cust_air_size,
                        stc_cust_air_qty:stc_cust_air_qty,
                        stc_cust_v_belt_size:stc_cust_v_belt_size,
                        stc_cust_v_belt_qty:stc_cust_v_belt_qty,
                        stc_cust_blower_pulley_size:stc_cust_blower_pulley_size,
                        stc_cus_motor_pulley_size:stc_cus_motor_pulley_size,
                        stc_cus_blower_bearing_de:stc_cus_blower_bearing_de,
                        stc_cus_blower_bearing_nde:stc_cus_blower_bearing_nde,
                        stc_cus_blower_bearing_housing:stc_cus_blower_bearing_housing,
                        stc_cus_motor_bearing_de:stc_cus_motor_bearing_de,
                        stc_cus_motor_bearing_nde:stc_cus_motor_bearing_nde,
                        stc_cus_motor_capacity_hp:stc_cus_motor_capacity_hp,
                        stc_cus_motor_capacity_kw:stc_cus_motor_capacity_kw,
                        stc_cus_motor_capacity_amps:stc_cus_motor_capacity_amps,
                        stc_cus_motor_capacity_rpm:stc_cus_motor_capacity_rpm,
                        stc_cus_motor_weight:stc_cus_motor_weight,
                        stc_cus_ahu_frame_size:stc_cus_ahu_frame_size,
                        stc_cus_ahu_cfm:stc_cus_ahu_cfm,
                        ahu_type:ahu_type
                    },
                    dataType    : "JSON",
                    success     : function(res_data){
                        // console.log(res_data);
                        var response=res_data.trim();
                        if(response=="Login"){
                            window.location.reload();
                        }else if(response=="dept"){
                            alert("Please select department first!!!");
                        }else if(response=="No"){
                            alert("Please check entered details properly !!!");
                        }else{
                            alert("AHU details saved.");
                            window.location.reload();
                        }
                    }
                });
            });
            
            // save pump details
            $('.stc_cus_pumpsave').click(function(e){
                e.preventDefault();
                var stc_cust_pro_location_id = $('.stc_cust_pro_location_id').val();
                var stc_cust_sub_location = $('.stc_cust_sub_location').val();
                var stc_cust_area = $('.stc_cust_area').val();
                var stc_cust_equipment_type = $('.stc_cust_equipment_type').val();
                var stc_cust_equipment_number = $('.stc_cust_equipment_number').val();
                var stc_cus_totaltr = $('.stc_cus_totaltr').val();
                var stc_cus_Unit = $('.stc_cus_Unit').val();

                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        stc_ag_rproject_pump_details_set:1,
                        stc_cust_pro_location_id:stc_cust_pro_location_id,
                        stc_cust_sub_location:stc_cust_sub_location,
                        stc_cust_area:stc_cust_area,
                        stc_cust_equipment_type:stc_cust_equipment_type,
                        stc_cust_equipment_number:stc_cust_equipment_number,
                        stc_cus_totaltr:stc_cus_totaltr,
                        stc_cus_Unit:stc_cus_Unit
                    },
                    dataType    : "JSON",
                    success     : function(res_data){
                        // console.log(res_data);
                        var response=res_data.trim();
                        if(response=="Login"){
                            window.location.reload();
                        }else if(response=="dept"){
                            alert("Please select department first!!!");
                        }else if(response=="No"){
                            alert("Please check entered details properly !!!");
                        }else{
                            alert("Pump details saved.");
                            window.location.reload();
                        }
                    }
                });
            });

            // save job type            
            $('.stc_cus_jtypesave').click(function(e){
                e.preventDefault();
                var stc_cust_job_type = $('.stc_cust_job_type').val();
                var stc_job_varities = $('.stc_job_varities').val();

                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        stc_ag_rproject_jobtype_set:1,
                        stc_cust_job_type:stc_cust_job_type,
                        stc_job_varities:stc_job_varities
                    },
                    dataType    : "JSON",
                    success     : function(res_data){
                        // console.log(res_data);
                        var response=res_data.trim();
                        if(response=="Login"){
                            stc_cust_job_type();
                        }else if(response=="jtype"){
                            alert("Please select Job type first!!!");
                        }else if(response=="No"){
                            alert("Please check entered details properly !!!");
                        }else if(response=="duplicate"){
                            alert("This record already saved!!!");
                        }else if(response=="yes"){
                            alert("Job Type saved.");
                            window.location.reload();
                        }
                    }
                });
            });

            // delete pump details
            $('body').delegate('.stc-cust-pump-details-del', 'click', function(e){
                e.preventDefault();
                var stc_pump_det_id = $(this).attr("id");

                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        stc_ag_rpump_details_remove:1,
                        stc_pump_det_id:stc_pump_det_id
                    },
                    dataType    : "JSON",
                    success     : function(res_data){
                        // console.log(res_data);
                        var response=res_data.trim();
                        if(response=="Login"){
                            window.location.reload();
                        }else if(response=="No"){
                            alert("Please check details properly !!!");
                        }else if(response=="duplicate"){
                            alert("This record is in used!!!");
                        }else if(response=="yes"){
                            alert("Pump Details Deleted.");
                            window.location.reload();
                        }
                    }
                });
            });

            
            // save job type            
            $('.stc_cus_dept').click(function(e){
                e.preventDefault();
                var stc_locname = $('.stc_dept_locname').val();
                var stc_deptname = $('.stc_dept_deptname').val();
                var stc_dept_pro_id = $('.stc_dept_pro_id').val();

                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        stc_ag_rproject_department:1,
                        stc_locname:stc_locname,
                        stc_deptname:stc_deptname,
                        stc_dept_pro_id:stc_dept_pro_id
                    },
                    dataType    : "JSON",
                    success     : function(res_data){
                        // console.log(res_data);
                        var response=res_data.trim();
                        if(response=="Login"){
                            stc_cust_department();
                        }else if(response=="empty"){
                            alert("Please fill both field!!!");
                        }else if(response=="No"){
                            alert("Please check entered details properly !!!");
                        }else if(response=="duplicate"){
                            alert("This record already saved!!!");
                        }else if(response=="yes"){
                            alert("Department saved.");
                            window.location.reload();
                        }
                    }
                });
            });

            // department show
            stc_cust_department();
            function stc_cust_department(){
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        stc_ag_department_show:1
                    },
                    dataType    : "JSON",
                    success     : function(res_data){
                        // console.log(res_data);
                        $('.department-show').html(res_data);
                    }
                });
            }

            // job type show
            stc_cust_job_type();
            function stc_cust_job_type(){
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        stc_ag_job_type_show:1
                    },
                    dataType    : "JSON",
                    success     : function(res_data){
                        // console.log(res_data);
                        $('.job-type-show').html(res_data);
                    }
                });
            }
            
            $('body').delegate('.dept-edit-btn', 'click', function(e){
                e.preventDefault();
                var id=$(this).attr('id');
                $(this).hide();
                $('.res-hidedl'+id).show();
                $('.res-hidedd'+id).show();
                $('.res-hide'+id).show();
            });

            $('body').delegate('.dept-save-btn', 'click', function(e){
                e.preventDefault();
                var jobtid=$(this).attr('id');
                var loc=$('.res-hidedl'+jobtid).val();
                var dept=$('.res-hidedd'+jobtid).val();
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        stc_ag_department_show_save:1,
                        jobtid:jobtid,
                        loc:loc,
                        dept:dept
                    },
                    dataType    : "JSON",
                    success     : function(res_data){
                        // console.log(res_data);
                        alert(res_data);
                        stc_cust_department();
                    }
                });
            });

            $("#department-tableInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#department-table tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            $('body').delegate('.job-type-edit-btn', 'click', function(e){
                e.preventDefault();
                var id=$(this).attr('id');
                $(this).hide();
                $('.res-hidejt'+id).show();
                $('.res-hidevj'+id).show();
                $('.res-hide'+id).show();
            });

            $('body').delegate('.job-type-save-btn', 'click', function(e){
                e.preventDefault();
                var jobtid=$(this).attr('id');
                var jobtype=$('.res-hidejt'+jobtid).val();
                var jobvarities=$('.res-hidevj'+jobtid).val();
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        stc_ag_job_type_show_save:1,
                        jobtid:jobtid,
                        jobtype:jobtype,
                        jobvarities:jobvarities
                    },
                    dataType    : "JSON",
                    success     : function(res_data){
                        // console.log(res_data);
                        alert(res_data);
                        window.location.reload();
                    }
                });
            });

            $("#job-type-tableInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#job-type-table tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            $("#job-type-tableInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#job-type-table tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
            
            $('body').delegate('.add-project-details-btn', 'click', function(e){
                var value = $(this).attr('id');
                $('.stc-ag-project-id').val(value);

            });
        });
    </script>
    
    <script>
        $(document).ready(function(){
            // call collaborate project
            function stc_cust_project_collaborated(id){
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        stc_collaborate_project_call:1,
                        id:id
                    },
                    dataType    : "JSON",
                    success     : function(res_data){
                        // console.log(res_data);
                        $('.stc-collaborated-managers-details').html(res_data);
                    }
                });
            }

            $('.stc-project-collaboratebtn').on('click', function(){
                var project_id=$('.collaborate-project-id').val();
                var email=$('.stc-project-collaborate-email').val();
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        stc_collaborate_project_save:1,
                        project_id:project_id,
                        email:email
                    },
                    dataType    : "JSON",
                    success     : function(res_data){
                        // console.log(res_data);
                        if(res_data['status']=="success"){
                            alert(res_data['message']);
                            stc_cust_project_collaborated(project_id);
                            $('.stc-project-collaborate-email').val('');
                        }else if(res_data['status']=="invalid"){
                            alert(res_data['message']);
                        }else if(res_data=="empty"){
                            window.location.reload();
                        }
                    }
                });
            });

            $('body').delegate('.stc-project-collaborate-delete', 'click', function(e){
                e.preventDefault();
                var collabId=$(this).data('collab-id');
                var projectId=$(this).data('project-id') || $('.collaborate-project-id').val();
                if(!collabId){
                    return;
                }
                if(!confirm('Remove this collaborator from the project?')){
                    return;
                }
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        stc_collaborate_project_delete:1,
                        collab_id:collabId
                    },
                    dataType    : "JSON",
                    success     : function(res_data){
                        if(res_data=="empty"){
                            window.location.reload();
                            return;
                        }
                        if(res_data['status']=="success"){
                            alert(res_data['message']);
                            if(projectId){
                                stc_cust_project_collaborated(projectId);
                            }
                        }else{
                            alert(res_data['message'] ? res_data['message'] : 'Unable to remove collaborator.');
                        }
                    }
                });
            });

            // save ahu details
            $('.stc-project-collaborate').click(function(e){
                e.preventDefault();
                var id = $(this).attr("id");
                $('.collaborate-project-id').val(id);
                stc_cust_project_collaborated(id);
            });

        });
    </script>
</body>
</html>

<div class="modal fade bd-addproject-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Project</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="main-card mb-3 card">
                            <div class="card-body"><h5 class="card-title">Create Project</h5>
                                <form class="create-project-form">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12">
                                            <div class="position-relative form-group">
                                                <label for="exampleSelect" class=""><b>Customer name</b></label>
                                                <select name="stc_cust_pro_cust" class="form-control" required>
                                                    <?php 
                                                        $cityqry=mysqli_query($con, "
                                                            SELECT `stc_customer_id`,`stc_customer_name` FROM `stc_customer`
                                                            INNER JOIN `stc_agent_requested_customer`
                                                            ON `stc_customer_id`=`stc_agent_requested_customer_cust_id`
                                                            WHERE `stc_agent_requested_customer_agent_id`='".$_SESSION['stc_agent_id']."'

                                                        ");
                                                        foreach($cityqry as $custrow){
                                                            echo '<option value="'.$custrow['stc_customer_id'].'">'.$custrow['stc_customer_name'].'</option>';
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <div class="position-relative form-group">
                                                <label for="exampleEmail" class=""><b>Project title/ Site name</b></label>
                                                <input placeholder="Enter your project name" type="text" name="stc_cust_pro_title" class="mb-2 form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-12">
                                            <div class="position-relative form-group">
                                                <label for="exampleEmail" class=""><b>Project Reference No</b></label>
                                                <input placeholder="Enter your project reference. Ex:- GTO/123/21-22 or PWOG/123/21-22" type="text" name="stc_cust_pro_refr" class="mb-2 form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="exampleSelect" class=""><b>Type</b></label>
                                                <select name="stc_cust_pro_type" class="form-control" required>
                                                    <option value="">Please Select Type</option>
                                                    <option value="project">Project</option>
                                                    <option value="service">Service</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>                                                        
                                    <div class="position-relative form-group">
                                        <label for="exampleText" class=""><b>Project address</b></label>
                                        <textarea id="exampleText" class="form-control" name="stc_cust_pro_address" placeholder="Enter your project address" required></textarea>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="exampleSelect" class=""><b>City</b></label>
                                                <select name="stc_cust_pro_city" class="form-control" required>
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
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="exampleSelect" class=""><b>State</b></label>
                                                <select name="stc_cust_pro_state" class="form-control" required>
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
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="exampleEmail" class="">Responsible person</b></label>
                                                <input placeholder="Enter your responsible person name" name="stc_cust_pro_responperson" type="text" class="mb-2 form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="exampleSelect" class=""><b>Supervisor Quantity</b></label>
                                                <select id="exampleSelect" name="stc_cust_pro_supquantity" class="form-control" required>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="exampleSelect" class=""><b>Project begining date</b></label>
                                                <input type="date" placeholder="Please select project beg date" name="stc_cust_pro_begdate" class="mb-2 form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="exampleSelect" class=""><b>Project ending date</b></label>
                                                <input type="date" placeholder="Please select project beg date" name="stc_cust_pro_enddate" class="mb-2 form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">                                                        
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="exampleEmail" class="">Begining budget</b></label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-rupee-sign"></i></span>
                                                    </div>
                                                    <input placeholder="Enter project begining budget" type="text" name="stc_cust_pro_begbudget" class="form-control"  required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="exampleSelect" class=""><b>Status</b></label>
                                                <select name="stc_cust_pro_status" class="form-control" required>
                                                    <option value="1">Active</option>
                                                    <option value="0">off</option>
                                                </select>
                                                <input type="hidden" name="stc_cust_project_action">
                                            </div>
                                        </div>
                                    </div>
                                                    
                                    <button class="mt-1 btn btn-primary form-control">Save</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-addprojectdetails-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Project Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="stc-ag-pro-det-form">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xl-12">
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xl-12">
                                            <h5 class="card-title" align="center">Add Project Details</h5>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xl-12">
                                            <h5 class="card-title">Project Name</h5>
                                            <div class="position-relative form-group">
                                                <select class="form-control stc-ag-project-id" disabled>
                                                    <?php
                                                        $pro_qry=mysqli_query($con, "
                                                            SELECT 
                                                                `stc_cust_project_id`,
                                                                `stc_cust_project_title`
                                                            FROM `stc_cust_project` 
                                                            WHERE `stc_cust_project_createdby`='".$_SESSION["stc_agent_id"]."'
                                                        ");
                                                        if(mysqli_num_rows($pro_qry)>0){
                                                            echo "<option>Please Select Project!!!</option>";
                                                            foreach($pro_qry as $prorow){
                                                                echo "<option value='".$prorow['stc_cust_project_id']."'>".$prorow['stc_cust_project_title']."</option>";
                                                            }
                                                        }else{
                                                            echo "<option>No Project Found!!!</option>";
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xl-6">
                                            <h5 class="card-title">Customer Name</h5>
                                            <div class="position-relative form-group">
                                                <input type="text" class="form-control stc-ag-cust-name" placeholder="Enter Customer Name">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xl-6">
                                            <h5 class="card-title">Customer Email Id</h5>
                                            <div class="position-relative form-group">
                                                <input type="text" class="form-control stc-ag-cust-emailid" placeholder="Enter Email Id">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xl-4">
                                            <h5 class="card-title">Customer Contact Number</h5>
                                            <div class="position-relative form-group">
                                                <input type="number" class="form-control stc-ag-cont-number" placeholder="Enter Customer Contact Number">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xl-4">
                                            <h5 class="card-title">Purpose</h5>
                                            <div class="position-relative form-group">
                                                <input type="text" class="form-control stc-ag-purpose" placeholder="Enter Purpose">
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xl-4">
                                            <h5 class="card-title">Ref Contact</h5>
                                            <div class="position-relative form-group">
                                                <input type="text" class="form-control stc-ag-ref-contact" placeholder="Enter Reference contact">
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xl-12">
                                            <h5 class="card-title">Job Details</h5>
                                            <div class="position-relative form-group">
                                                <textarea class="form-control stc-ag-job-details" placeholder="Enter Job Details"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xl-12">
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xl-12">
                                            <h5 class="card-title" align="center">Quotation Details</h5>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xl-6">
                                            <h5 class="card-title">Quotation Number</h5>
                                            <div class="position-relative form-group">
                                                <input type="text" class="form-control stc-ag-quot-number" placeholder="Enter Quotation Number">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xl-6">
                                            <h5 class="card-title">Quotation Date</h5>
                                            <div class="position-relative form-group">
                                                <input type="date" class="form-control stc-ag-quote-date" placeholder="Enter Quotation Date">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xl-6">
                                            <h5 class="card-title">Basic Value</h5>
                                            <div class="position-relative form-group">
                                                <input type="number" class="form-control stc-ag-basic-value" placeholder="Enter Basic Value">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xl-6">
                                            <h5 class="card-title">GST Value</h5>
                                            <div class="position-relative form-group">
                                                <input type="number" class="form-control stc-ag-gst-value" placeholder="Enter GST Value">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xl-6">
                                            <h5 class="card-title">Quotation By</h5>
                                            <div class="position-relative form-group">
                                                <input type="text" class="form-control stc-ag-quoted-by" placeholder="Enter Quotation By">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xl-6">
                                            <h5 class="card-title">Mode of Quotation</h5>
                                            <div class="position-relative form-group">
                                                <input type="text" class="form-control stc-ag-mode-of-quotation" placeholder="Enter Mode of Quotation">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xl-12">
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xl-12">
                                            <h5 class="card-title" align="center">Customer Feedback</h5>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xl-6">
                                            <h5 class="card-title">Target Price</h5>
                                            <div class="position-relative form-group">
                                                <input type="number" class="form-control stc-ag-target-price" placeholder="Enter Target Price">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xl-6">
                                            <h5 class="card-title">Status</h5>
                                            <div class="position-relative form-group">
                                                <input type="text" class="form-control stc-ag-status" placeholder="Enter Status">
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xl-12">
                                            <h5 class="card-title">Remarks</h5>
                                            <div class="position-relative form-group">
                                                <textarea class="form-control stc-ag-remarks" placeholder="Enter Remarks">
                                                </textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xl-12">
                            <div class="main-card mb-3 card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xl-12">
                                            <h5 class="card-title" align="center">Order Details</h5>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xl-6">
                                            <h5 class="card-title">PO Number</h5>
                                            <div class="position-relative form-group">
                                                <input type="text" class="form-control stc-ag-po-number" placeholder="Enter PO Number">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xl-6">
                                            <h5 class="card-title">PO Value</h5>
                                            <div class="position-relative form-group">
                                                <input type="number" class="form-control stc-ag-po-value" placeholder="Enter PO Value">
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-sm-12 col-xl-12">
                                            <div class="position-relative form-group">
                                                <button type="submit" class="form-control btn btn-primary stc-ag-save-project-record">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-projectdetails-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Project Details Show</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xl-12">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title" align="center">Project Details</h5>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">Project Name</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" disabled id="stc-ag-project-id">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Customer Name</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" disabled id="stc-ag-cust-name" placeholder="Enter Customer Name">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Customer Email Id</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" disabled id="stc-ag-cust-emailid" placeholder="Enter Email Id">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Customer Contact Number</h5>
                                        <div class="position-relative form-group">
                                            <input type="number" class="form-control" disabled id="stc-ag-cont-number" placeholder="Enter Customer Contact Number">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Purpose</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" disabled id="stc-ag-purpose" placeholder="Enter Purpose">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Ref Contact</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" disabled id="stc-ag-ref-contact" placeholder="Enter Reference contact">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">Job Details</h5>
                                        <div class="position-relative form-group">
                                            <textarea class="form-control" disabled id="stc-ag-job-details" placeholder="Enter Job Details"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xl-12">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title" align="center">Quotation Details</h5>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Quotation Number</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" disabled id="stc-ag-quot-number" placeholder="Enter Quotation Number">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Quotation Date</h5>
                                        <div class="position-relative form-group">
                                            <input type="date" class="form-control" disabled id="stc-ag-quote-date" placeholder="Enter Quotation Date">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Basic Value</h5>
                                        <div class="position-relative form-group">
                                            <input type="number" class="form-control" disabled id="stc-ag-basic-value" placeholder="Enter Basic Value">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">GST Value</h5>
                                        <div class="position-relative form-group">
                                            <input type="number" class="form-control" disabled id="stc-ag-gst-value" placeholder="Enter GST Value">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Quotation By</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" disabled id="stc-ag-quoted-by" placeholder="Enter Quotation By">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Mode of Quotation</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" disabled id="stc-ag-mode-of-quotation" placeholder="Enter Mode of Quotation">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xl-12">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title" align="center">Customer Feedback</h5>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Target Price</h5>
                                        <div class="position-relative form-group">
                                            <input type="number" class="form-control" disabled id="stc-ag-target-price" placeholder="Enter Target Price">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Status</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" disabled id="stc-ag-status" placeholder="Enter Status">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">Remarks</h5>
                                        <div class="position-relative form-group">
                                            <textarea class="form-control" disabled id="stc-ag-remarks" placeholder="Enter Remarks">
                                            </textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xl-12">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title" align="center">Order Details</h5>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">PO Number</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" disabled id="stc-ag-po-number" placeholder="Enter PO Number">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">PO Value</h5>
                                        <div class="position-relative form-group">
                                            <input type="number" class="form-control" disabled id="stc-ag-po-value" placeholder="Enter PO Value">
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-12 col-sm-12 col-xl-12">
                                        <div class="position-relative form-group">
                                            <button type="submit" class="form-control btn btn-primary stc-ag-edit-save-project-record">Edit</button>
                                            <button type="submit" class="form-control btn btn-primary stc-ag-edit-save-project-record" style="display:none;">Save</button>
                                        </div>
                                    </div> -->
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
<div class="modal fade bd-connproject-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Supervisors connected with project</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xl-12">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <div class="position-relative form-group">
                                            <table class="table table-hover table-bordered">
                                                <thead>
                                                    <tr>
                                                        <td class="text-center">Sl No</td>
                                                        <td class="text-center">Users Name</td>
                                                        <td class="text-center">Category</td>
                                                    </tr>
                                                </thead>
                                                <tbody class="stc-ag-connectedsupervisor">
                                                </tbody>
                                            </table>
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
<div class="modal fade bd-projectedit-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Project Details Edit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xl-12">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title" align="center">Project Details Edit</h5>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Project Name</h5>
                                        <div class="position-relative form-group">
                                            <input type="hidden" id="stc-ag-ed-project-id">
                                            <input type="text" class="form-control" id="stc-ag-ed-project-name" Placeholder="Enter Project Name">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Project Reference No</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" id="stc-ag-ed-project-refr" Placeholder="Enter Project Reference No">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">Project Address</h5>
                                        <div class="position-relative form-group">
                                            <textarea class="form-control" id="stc-ag-ed-job-details" placeholder="Enter Project Address"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <div class="position-relative form-group">
                                            <button class="btn btn-primary stc-ag-ed-project-save">Save</button>
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
<div class="modal fade bd-projectahudetails-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Project AHU Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xl-12">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title" align="center">Project AHU Details</h5>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12" style="width: auto;overflow-x: auto; white-space: nowrap;">
                                        <table class="table table-bordered table-responsive">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Dept</th>
                                                    <th class="text-center">Location</th>
                                                    <th class="text-center">Sub Location</th>
                                                    <th class="text-center">AHU NO</th>
                                                    <th class="text-center">Design Temprature</th>
                                                    <th class="text-center">Air Filter Size</th>
                                                    <th class="text-center">Air Filter Qty</th>
                                                    <th class="text-center">V - Belt Size</th>
                                                    <th class="text-center">V - Belt Qty</th>
                                                    <th class="text-center">Pulley Blower</th>
                                                    <th class="text-center">Pulley Motor</th>
                                                    <th class="text-center">Blower Bearing Size DE</th>
                                                    <th class="text-center">Blower Bearing Size NDE</th>
                                                    <th class="text-center">Blower Bearing Size Housing</th>
                                                    <th class="text-center">Motor Bearing Size DE</th>
                                                    <th class="text-center">Motor Bearing Size NDE</th>
                                                    <th class="text-center">Motor Cap. HP</th>
                                                    <th class="text-center">Motor KW</th>
                                                    <th class="text-center">Motor AMPS</th>
                                                    <th class="text-center">Motor RPM</th>
                                                    <th class="text-center">Motor Weight</th>
                                                    <th class="text-center">Frame Size</th>
                                                    <th class="text-center">CFM</th>
                                                </tr>
                                            </thead>
                                            <tbody class="stc-project-ahu-details-table">
                                                
                                            </tbody>
                                        </table>
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
<div class="modal fade bd-projectpumpdetails-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Project Pump Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xl-12">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title" align="center">Project Pump Details</h5>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12" style="width: auto;overflow-x: auto; white-space: nowrap;">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Location</th>
                                                    <th class="text-center">Sub Location</th>
                                                    <th class="text-center">Area</th>
                                                    <th class="text-center">Equipment Type</th>
                                                    <th class="text-center">Equipment Number</th>
                                                    <th class="text-center">Total Values</th>
                                                    <th class="text-center">Unit</th>
                                                    <th class="text-center">ACTION</th>
                                                </tr>
                                            </thead>
                                            <tbody class="stc-project-pump-details-table">
                                                
                                            </tbody>
                                        </table>
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
<div class="modal fade bd-collaborate-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Project Collaborate with others</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xl-12">
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title" for="collaborate-email">Email</h5>
                                        <div class="position-relative form-group">
                                            <input type="email" id="collaborate-email" class="form-control stc-project-collaborate-email" placeholder="Enter email address.">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <div class="position-relative form-group">
                                            <input type="hidden" class="collaborate-project-id">
                                            <a href="javascript:void(0)" class="form-control btn btn-primary stc-project-collaboratebtn">Collaborate</a>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12" style="width: auto;overflow-x: auto; white-space: nowrap;">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Date</th>
                                                    <th class="text-center">Managers</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="stc-collaborated-managers-details">
                                                
                                            </tbody>
                                        </table>
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