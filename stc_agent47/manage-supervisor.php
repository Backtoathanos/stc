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
?> 
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Supervisor Management - STC</title>
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
                                    <div>Supervisor Management
                                        <div class="page-title-subheading">You can add your supervisor information here for your experience with us<h3>&#9786;</h3>.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>            
                        <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                            <li class="nav-item">
                                <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#create-project">
                                    <span>Create Supervisor <b>X</b></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#show-supervisor">
                                    <span>Show Supervisor<b><i class="pe-7s-look"></i></b></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#alot-budget">
                                    <span>Alot Project <b>O</b></span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane tabs-animation fade show active" id="create-project" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">Create Supervisor</h5>
                                                <form class="create-supervisor-form">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-md-12">
                                                            <div class="position-relative form-group">
                                                                <label for="exampleSelect" class="">Customer name</label>
                                                                <select name="stc_cust_sup_cust" class="form-control" required>
                                                                    <?php 
                                                                        include_once("../MCU/db.php");
                                                                        $cityqry=mysqli_query($con, "
                                                                            SELECT `stc_customer_id`, `stc_customer_name` FROM `stc_customer`
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
                                                                <label for="exampleEmail" class="">Supervisor fullname</label>
                                                                <input placeholder="Enter your supervisor name" type="text" name="stc_cust_sup_fullname" class="mb-2 form-control" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-md-6">
                                                            <div class="position-relative form-group">
                                                                <label for="exampleEmail" class="">Supervisor contact</label>
                                                                <input placeholder="Enter your supervisor contact number" type="number" name="stc_cust_sup_contnumber" class="mb-2 form-control" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-md-6">
                                                            <div class="position-relative form-group">
                                                                <label for="exampleEmail" class="">Supervisor whatsapp</label>
                                                                <input placeholder="Enter your supervisor Whatsapp" type="number" name="stc_cust_sup_whatsapp" class="mb-2 form-control" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6 col-md-6">
                                                            <div class="position-relative form-group">
                                                                <label for="exampleEmail" class="">Supervisor email</label>
                                                                <input placeholder="Enter your supervisor E-mail" type="text" name="stc_cust_sup_email" class="mb-2 form-control" required>
                                                            </div>
                                                        </div>
                                                    </div>                                                        
                                                    <div class="position-relative form-group">
                                                        <label for="exampleText" class="">Supervisor address</label>
                                                        <textarea id="exampleText" class="form-control" name="stc_cust_sup_address" placeholder="Enter your supervisor address" required></textarea>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6 col-md-6">
                                                            <div class="position-relative form-group">
                                                                <label for="exampleEmail" class="">Supervisor pincode</label>
                                                                <input placeholder="Enter your supervisor pincode" type="text" name="stc_cust_sup_pincode" class="mb-2 form-control" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
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
                                                        <div class="col-md-6">
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
                                                                <label for="exampleSelect" class="">Status</label>
                                                                <select name="stc_cust_sup_status" class="form-control" required>
                                                                    <option value="1">Active</option>
                                                                    <option value="0">off</option>
                                                                </select>
                                                                <input type="hidden" name="stc_cust_supervisor_action">
                                                            </div>
                                                        </div>
                                                    </div>                                                    
                                                    <button class="mt-1 btn btn-primary">Add</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane tabs-animation fade" id="show-supervisor" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">Show Supervisor</h5>
                                                <form class="#">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <table class="table table-responsive">
                                                                <thead>
                                                                    <th>Supervisor Id</br>Name</th>
                                                                    <th>Address</th>
                                                                    <th>Contact &</br>Whatsapp Number</th>
                                                                    <th>Email</th>
                                                                    <th>Sitename</th>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                        $showsubsupqry=mysqli_query($con, "
                                                                            SELECT `stc_cust_pro_supervisor_id`,
                                                                                `stc_cust_pro_supervisor_fullname`,
                                                                                `stc_cust_pro_supervisor_address`,
                                                                                `stc_cust_pro_supervisor_contact`,
                                                                                `stc_cust_pro_supervisor_whatsapp`,
                                                                                `stc_cust_pro_supervisor_email`
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
                                                                            echo "
                                                                                <tr>
                                                                                    <td>
                                                                                        STC/S/A/".substr("0000{$supsubrow['stc_cust_pro_supervisor_id']}", -5)."</br>
                                                                                        ".$supsubrow['stc_cust_pro_supervisor_fullname']."
                                                                                    </td>
                                                                                    <td>".$supsubrow['stc_cust_pro_supervisor_address']."</td>
                                                                                    <td>
                                                                                        +91-".$supsubrow['stc_cust_pro_supervisor_contact']."</br>
                                                                                        <a href='http://wa.me/7209581823'>
                                                                                        +91-".$supsubrow['stc_cust_pro_supervisor_whatsapp']."
                                                                                        </a>
                                                                                    </td>
                                                                                    <td>".$supsubrow['stc_cust_pro_supervisor_email']."</td>
                                                                                    <td>".$sitename."</td>
                                                                                </tr>
                                                                            ";
                                                                        }
                                                                    ?>
                                                                </tbody>                                                                   
                                                            </table>
                                                        </div>
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
                                            <div class="card-body"><h5 class="card-title">Alot Budget</h5>
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
                                                                <label for="exampleSelect" class="">Select Supervisor</label>
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
                                                                            echo '<option value="NA">No Supervisor Found!!!</option>';
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
                          alert("Supervisor created!!!");
                          $(".create-supervisor-form")[0].reset();
                        } else if (argument == "no") {
                          alert("Please check & try again!!!");
                        } else if (argument == "logout") {
                          // alert("Please check & try again!!!&#9786;");
                          window.location.reload();
                        } else if (argument == "not") {
                          alert("This Supervisor is already in our records!!!");
                        } else {
                          alert("Do not empty any field!!!");
                        }
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
                          alert("This Supervisor is already alotted on that project!!!");
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