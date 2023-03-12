<?php  
session_start(); 
if(isset($_SESSION["stc_agent_id"])){ 
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
    <title>Procurement Tracker - STC</title>
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
                                    <div>Procurement tracker
                                        <div class="page-title-subheading">You can add your supervisor information here for your experience with us<h3>&#9786;</h3>.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>            
                        <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                            <li class="nav-item">
                                <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#create-project">
                                    <span>Create Procuremnet Tracker <b>X</b></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#show-supervisor">
                                    <span>Show Procurement Tracker<b><i class="pe-7s-look"></i></b></span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane tabs-animation fade show active" id="create-project" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">Create Procurement Tracker</h5>
                                                <form class="create-procurment-tracker">
                                                    <div class="row">
                                                        <div class="col-sm-12 col-md-12">
                                                            <div class="position-relative form-group">
                                                                <label for="exampleSelect" class="">Customer name</label>
                                                                <select name="stc_cust_sup_cust" class="form-control" required>
                                                                    <?php 
                                                                        include_once("../MCU/db.php");
                                                                        $cityqry=mysqli_query($con, "
                                                                            SELECT DISTINCT `stc_customer_id`, `stc_customer_name` FROM `stc_customer`
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
                                                        <div class="col-sm-12 col-md-6">
                                                            <div class="position-relative form-group">
                                                                <label for="exampleEmail" class="">Project Name</label>
                                                                <select name="stc_alot_pro_project" id="#" class="form-control" required>
                                                                    <?php 
                                                                        $proseleqry=mysqli_query($con, "
                                                                            SELECT DISTINCT `stc_cust_project_id`, `stc_cust_project_title` 
                                                                            FROM `stc_cust_project` 
                                                                            INNER JOIN `stc_agent_requested_customer` 
                                                                            ON `stc_agent_requested_customer_cust_id`=`stc_cust_project_cust_id`
                                                                            WHERE `stc_agent_requested_customer_agent_id`='".$_SESSION['stc_agent_id']."'
                                                                            ORDER BY `stc_cust_project_title` ASC
                                                                        ");
                                                                        foreach($proseleqry as $proselrow){
                                                                            echo '<option value="'.$proselrow['stc_cust_project_id'].'">'.$proselrow['stc_cust_project_title'].'</option>';
                                                                        }
                                                                    ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-6">
                                                            <div class="position-relative form-group">
                                                                <label for="exampleEmail" class="">Item Name</label>
                                                                <input type="text" class="mb-2 form-control" id="stc-pro-tra-item-name" placeholder="Enter Item Name" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-4">
                                                            <div class="position-relative form-group">
                                                                <label for="exampleEmail" class="">Service</label>
                                                                <select name="stc_alot_pro_proj" id="#" class="form-control" required>
                                                                    <option>HVAC</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-4">
                                                            <div class="position-relative form-group">
                                                                <label for="exampleEmail" class="">Unit</label>
                                                                <select name="stc_alot_pro_project" id="#" class="form-control" required>
                                                                    <option>LOT</option>
                                                                    <option>NOS</option>
                                                                    <option>RMT</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-4">
                                                            <div class="position-relative form-group">
                                                                <label for="exampleEmail" class="">Drawing Quantity</label>
                                                                <input type="number" class="mb-2 form-control" name="stc_pro_tra_drawing_quantity" placeholder="Enter Drawing Quantity" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-6">
                                                            <div class="position-relative form-group">
                                                                <label for="exampleEmail" class="">Buyer / Maker</label>
                                                                <input type="text" class="mb-2 form-control" id="stc-pro-tra-buyer-maker" placeholder="Enter Buyer / Maker Name" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-6">
                                                            <div class="position-relative form-group">
                                                                <label for="exampleEmail" class="">PO No. / WO No.</label>
                                                                <input type="text" class="mb-2 form-control" id="stc-pro-tra-wo-no-po-no" placeholder="Enter PO No. / WO No." required>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-6">
                                                            <div class="position-relative form-group">
                                                                <label for="exampleEmail" class="">PO Date</label>
                                                                <input type="date" class="mb-2 form-control" id="stc-pro-tra-po-date" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-6">
                                                            <div class="position-relative form-group">
                                                                <label for="exampleEmail" class="">PO Basic Value</label>
                                                                <input type="number" class="mb-2 form-control" id="stc-pro-tra-po-basic-value" placeholder="Please enter amount" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-6">
                                                            <div class="position-relative form-group">
                                                                <label for="exampleEmail" class="">GST %</label>
                                                                <select name="stc_alot_pro_project" id="#" class="form-control" required>
                                                                    <option>5%</option>
                                                                    <option>12%</option>
                                                                    <option>18%</option>
                                                                    <option>28%</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-6">
                                                            <div class="position-relative form-group">
                                                                <label for="exampleEmail" class="">PO Amount</label>
                                                                <input type="number" class="mb-2 form-control" id="stc-pro-tra-po-amount" placeholder="Please enter amount" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-6">
                                                            <div class="position-relative form-group">
                                                                <label for="exampleEmail" class="">TDS Approval</label>
                                                                <input type="date" class="mb-2 form-control" id="stc-pro-tra-tds-approval" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-6">
                                                            <div class="position-relative form-group">
                                                                <label for="exampleEmail" class="">Mfg Clearance Client</label>
                                                                <input type="date" class="mb-2 form-control" id="stc-pro-tra-mfg-clearance-approval" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-6">
                                                            <div class="position-relative form-group">
                                                                <label for="exampleEmail" class="">Mfg Lead Time</label>
                                                                <input type="number" class="mb-2 form-control" id="stc-pro-tra-mfg-lead-time" placeholder="Enter mfg lead time" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-6">
                                                            <div class="position-relative form-group">
                                                                <label for="exampleEmail" class="">OEM / Dealer Location</label>
                                                                <input type="text" class="mb-2 form-control" id="stc-pro-tra-oem-dealer-location" placeholder="City n ame" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-6">
                                                            <div class="position-relative form-group">
                                                                <label for="exampleEmail" class="">Transit Time</label>
                                                                <input type="number" class="mb-2 form-control" id="stc-pro-tra-transit-time" placeholder="Enter transit time" required>
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