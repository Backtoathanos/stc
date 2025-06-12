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
    <title>Procurement Management - STC</title>
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
        .fade:not(.show) {
          opacity: 10;
        }    

        .close-tag-beg{
            display: none;
        }
    </style>
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
                                    <div>Procurement Management
                                        <div class="page-title-subheading">This section for combine multiple order & create on single order or requisition.<h3>&#9786;</h3>.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>            
                        <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                            <!-- <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-0" data-toggle="tab" href="#check-order">
                                    <span>Create Order <b><i class="pe-7s-look"></i></b></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link show active" id="tab-1" data-toggle="tab" href="#sup-requis">
                                    <span>Requisition <b><i class="pe-7s-look"></i></b></span>
                                </a>
                            </li> -->
                            
                            <li class="nav-item">
                                <a role="tab" class="nav-link active show" id="tab-0" data-toggle="tab" href="#requisition">
                                    <span>Requisition <b><i class="pe-7s-look"></i></b></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#requisition-cart">
                                    <span>Cart <b><i class="pe-7s-cart"></i></b></span>
                                </a>
                            </li>
                        </ul>
                        <!-- <div class="tab-content"> -->
                            <!-- <div class="tab-pane tabs-animation fade " id="check-order" role="tabpanel">
                                <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                                    <li class="nav-item">
                                        <a role="tab" class="nav-link" id="tab-0" data-toggle="tab" href="#order">
                                            <span>Order <b><i class="pe-7s-sheet"></i></b></span>
                                        </a>
                                    </li>
                                    <li class="nav-item active">
                                        <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#order-cart">
                                            <span>Cart <b><i class="pe-7s-cart"></i></b></span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane tabs-animation fade " id="order" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="main-card mb-3 card">
                                                    <div class="card-body"><h5 class="card-title">Supervisor Order</h5>
                                                        <form class="#">
                                                           <div class="row">
                                                                <div class="col-md-12">
                                                                    <table class="mb-0 table table-hover">
                                                                        <thead>
                                                                            <th>Order No</th>
                                                                            <th>Order Date</th>
                                                                            <th>Requisition By<br> <span>Supervisor ID</span><br><span>Supervisor name</span></th>
                                                                            <th>Requisition For<br> <span>Project name</span><br><span>project address</span></th>
                                                                            <th>Status</th>
                                                                            <th>View</th>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?php
                                                                            $requisbysupforproqry=mysqli_query($con, "
                                                                                SELECT 
                                                                                    `stc_cust_super_requisition_id`,
                                                                                    `stc_cust_super_requisition_date`, 
                                                                                    `stc_cust_project_title`, 
                                                                                    `stc_cust_project_address`, 
                                                                                    `stc_cust_pro_supervisor_id`, 
                                                                                    `stc_cust_pro_supervisor_fullname`,
                                                                                    `stc_cust_super_requisition_status` 
                                                                                FROM `stc_cust_super_requisition`
                                                                                INNER JOIN `stc_cust_pro_supervisor` 
                                                                                ON `stc_cust_pro_supervisor_id`=`stc_cust_super_requisition_super_id`
                                                                                INNER JOIN `stc_cust_project` 
                                                                                ON `stc_cust_project_id`=`stc_cust_super_requisition_project_id`
                                                                                INNER JOIN `stc_agents` 
                                                                                ON `stc_agents_id`=`stc_cust_pro_supervisor_created_by`
                                                                                WHERE `stc_agents_id`='".$_SESSION['stc_agent_id']."'
                                                                                ORDER BY `stc_cust_super_requisition_id` DESC
                                                                            ");
                                                                            if(mysqli_num_rows($requisbysupforproqry)!=0){
                                                                                foreach($requisbysupforproqry as $currrow){
                                                                                    $currentstatus='';
                                                                                    if($currrow['stc_cust_super_requisition_status']==1){
                                                                                        $currentstatus="PROCESS";
                                                                                    }elseif($currrow['stc_cust_super_requisition_status']==0){
                                                                                        $currentstatus="CANCEL";
                                                                                    }else{
                                                                                        $currentstatus="ACCEPTED";
                                                                                    }
                                                                                    echo "
                                                                                        <tr>
                                                                                            <td>STC/O/A/S/".substr("0000{$currrow['stc_cust_super_requisition_id']}", -5)."</td>
                                                                                            <td>".date('d-M-Y', strtotime($currrow['stc_cust_super_requisition_date']))."</td>
                                                                                            <td>STC/A/S/".substr("0000{$currrow['stc_cust_pro_supervisor_id']}", -5)."<br>".$currrow['stc_cust_pro_supervisor_fullname']."</td>
                                                                                            <td>".$currrow['stc_cust_project_title']."<br>".$currrow['stc_cust_project_address']."</td>
                                                                                            <td>".$currentstatus."</td>
                                                                                            <td><a href='#' style='font-size: 25px;font-weight: bold;color: black;' class='ag-show-grid' id='".$currrow['stc_cust_super_requisition_id']."'><i class='fas fa-eye'></i></a></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td colspan='6'>
                                                                                                <div style='display:none;' id='togdiv".$currrow['stc_cust_super_requisition_id']."'>
                                                                                                    Loading...
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                    ";
                                                                                }
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
                                    <div class="tab-pane tabs-animation fade " id="order-cart" role="tabpanel">
                                         order cart empty..
                                    </div>
                                </div>
                            </div> -->
                            <!-- <div class="tab-pane tabs-animation fade show active" id="sup-requis" role="tabpanel"> -->
                                <!-- <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                                    <li class="nav-item">
                                        <a role="tab" class="nav-link active show" id="tab-0" data-toggle="tab" href="#requisition">
                                            <span>Requisition <b><i class="pe-7s-look"></i></b></span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#requisition-cart">
                                            <span>Cart <b><i class="pe-7s-cart"></i></b></span>
                                        </a>
                                    </li>
                                </ul> -->
                                <div class="tab-content">
                                    <div class="tab-pane tabs-animation fade show active" id="requisition" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="main-card mb-3 card">
                                                    <div class="card-body"><h5 class="card-title">Supervisor Requisition</h5>
                                                        <form class="#">
                                                            <div class="row">
                                                                <div class="col-md-11">
                                                                    <input type="text" class="form-control searc-pro-table" placeholder="Search here..">
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <a class="btn btn-success form-control acceptall" title="Accept All" style="font-size: 20px;"><i class="fas fa-check-circle"></i></a>
                                                                </div>
                                                                <div class="col-md-12" style="overflow-x:auto;">
                                                                    <table class="mb-0 table table-hover table-bordered stc-pro-req-table">
                                                                        <thead>
                                                                            <th># <input type="checkbox" class="form-control master-checkbox" title="Select All" style="width:40px;"></th>
                                                                            <th class="text-center">Requisition ID & Date</th>
                                                                            <th class="text-center">Requisition From</th>
                                                                            <th class="text-center">Requisition For</th>
                                                                            <th class="text-center">Approved By</th>
                                                                            <th class="text-center">Item Description</th>
                                                                            <th class="text-center">Unit</th>
                                                                            <th class="text-center">Requis Qty</th>
                                                                            <th class="text-center">Requis Approved Qty</th>
                                                                            <th class="text-center">Requis Remains Qty</th>
                                                                            <th class="text-center">Requisition Status</th>
                                                                            <th class="text-center">Priority</th>
                                                                            <th class="text-center">Type</th>
                                                                            <th class="text-center">Action</th>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php 
                                                                                $requissuperqry=mysqli_query($con, "
                                                                                    SELECT
                                                                                        I.`stc_cust_super_requisition_list_id` as list_item_id,
                                                                                        L.`stc_cust_super_requisition_list_id` as list_id,
                                                                                        L.`stc_cust_super_requisition_list_date`,
                                                                                        `stc_cust_project_title`,
                                                                                        `stc_cust_pro_supervisor_fullname`,
                                                                                        `stc_cust_super_requisition_list_items_title`,
                                                                                        `stc_cust_super_requisition_list_items_unit`,
                                                                                        `stc_cust_super_requisition_list_items_approved_qty`,
                                                                                        `stc_cust_super_requisition_items_finalqty`,
                                                                                        `stc_cust_super_requisition_list_status`,
                                                                                        `stc_cust_super_requisition_items_priority`,
                                                                                        `stc_cust_super_requisition_items_type`,
                                                                                        `stc_agents_name`
                                                                                    FROM `stc_cust_super_requisition_list_items` I
                                                                                    INNER JOIN `stc_cust_super_requisition_list` L
                                                                                    ON `stc_cust_super_requisition_list_items_req_id`=L.`stc_cust_super_requisition_list_id`
                                                                                    INNER JOIN `stc_cust_pro_supervisor`
                                                                                    ON `stc_cust_pro_supervisor_id`=L.`stc_cust_super_requisition_list_super_id`
                                                                                    INNER JOIN `stc_cust_project`
                                                                                    ON `stc_cust_project_id`=L.`stc_cust_super_requisition_list_project_id`
                                                                                    LEFT JOIN `stc_agents` A
                                                                                    ON A.`stc_agents_id`=I.`stc_cust_super_requisition_list_items_acceptby`
                                                                                    WHERE L.`stc_cust_super_requisition_list_status`='2' 
                                                                                    ORDER BY TIMESTAMP(`stc_cust_super_requisition_list_date`) DESC                                                                                   
                                                                                ");
                                                                                if(mysqli_num_rows($requissuperqry)!=0){
                                                                                    foreach($requissuperqry as $requisrow){
                                                                                        $reqstatus='';
                                                                                        if($requisrow['stc_cust_super_requisition_list_status']==1){
                                                                                            $reqstatus="Ordered";
                                                                                        }elseif($requisrow['stc_cust_super_requisition_list_status']==2){
                                                                                            $reqstatus="Accepted";
                                                                                        }elseif($requisrow['stc_cust_super_requisition_list_status']==3){
                                                                                            $reqstatus="Approved";
                                                                                        }

                                                                                        $recqtyoperations=mysqli_query($con, "
                                                                                            SELECT `stc_cust_super_requisition_list_items_rec_recqty` FROM `stc_cust_super_requisition_list_items_rec` 
                                                                                            WHERE `stc_cust_super_requisition_list_items_rec_list_id`='".$requisrow['list_id']."'
                                                                                            AND `stc_cust_super_requisition_list_items_rec_list_item_id`='".$requisrow['list_item_id']."'
                                                                                        ");

                                                                                        $recoprqty=0;
                                                                                        foreach($recqtyoperations as $recoprrow){
                                                                                            $recoprqty+=$recoprrow['stc_cust_super_requisition_list_items_rec_recqty'];
                                                                                        }
                                                                                        $priority=$requisrow['stc_cust_super_requisition_items_priority']==2 ? "Urgent" : "Normal";
                                                                                        $bgcolor=$requisrow['stc_cust_super_requisition_items_priority']==2 ? "style='background:#ff9e9e;'" : "";
                                                                                        echo '
                                                                                            <tr>
                                                                                                <td class="text-center"><input type="checkbox" class="form-control select-req" style="width:40px;"></td>
                                                                                                <td class="text-center">'.$requisrow['list_id'].' <br/> '.date('d-m-Y h:i A', strtotime($requisrow['stc_cust_super_requisition_list_date'])).'</td>
                                                                                                <td class="text-center">'.$requisrow['stc_cust_pro_supervisor_fullname'].'</td>
                                                                                                <td class="text-center">'.$requisrow['stc_cust_project_title'].'</td>
                                                                                                <td class="text-center">'.$requisrow['stc_agents_name'].'</td>
                                                                                                <td>'.$requisrow['stc_cust_super_requisition_list_items_title'].'</td>
                                                                                                <td class="text-center">'.$requisrow['stc_cust_super_requisition_list_items_unit'].'</td>
                                                                                                <td class="text-right">'.number_format($requisrow['stc_cust_super_requisition_list_items_approved_qty'], 2).'</td>
                                                                                                <td>
                                                                                                    <input type="number" class="form-control stc-sup-appr-qty'.$requisrow['list_item_id'].'"
                                                                                                    value="'.$requisrow['stc_cust_super_requisition_list_items_approved_qty'].'">
                                                                                                </td>
                                                                                                <td class="text-right">'.number_format($recoprqty, 2).'</td>
                                                                                                <td class="text-center">'.$reqstatus.'</td>
                                                                                                <td class="text-center" '.$bgcolor.'>'.$priority.'</td>
                                                                                                <td class="text-center">'.$requisrow['stc_cust_super_requisition_items_type'].'</td>
                                                                                                <td class="text-center">
                                                                                                    <a class="btn btn-success form-control add_to_accept_cart" title="Approve" id="'.$requisrow['list_item_id'].'" req-id="'.$requisrow['list_id'].'" style="font-size: 20px;"><i class="fas fa-check-circle"></i></a>
                                                                                                    <a href="#" class="btn btn-danger form-control remove_from_purchase" operat-ic="'.$requisrow['list_item_id'].'" list-id="'.$requisrow['list_id'].'" title="Reject" id="rem_from_accept_cart'.$requisrow['list_item_id'].'" style="font-size: 20px;"><i class="fas fa-ban" ></i></a>
                                                                                                </td>
                                                                                            </tr>
                                                                                        ';
                                                                                    }
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
                                    <div class="tab-pane tabs-animation fade " id="requisition-cart" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="main-card mb-3 card">
                                                <div class="card-body"><h5 class="card-title">Supervisor Requisition</h5>
                                                    <form class="#">
                                                        <div class="row">
                                                            <div class="col-md-12" style="overflow-x:auto;">
                                                                <table class="mb-0 table table-hover table-bordered">
                                                                    <thead>
                                                                        <th class="text-center">Requisition ID</th>
                                                                        <th class="text-center">Requisition From</th>
                                                                        <th class="text-center">Requisition For</th>
                                                                        <th class="text-center">Requisition Items Details </th>
                                                                        <th class="text-center">Action</th>
                                                                    </thead>
                                                                    <tbody class="stc-requisition-cart-approval-reload">
                                                                        
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
                            <!-- </div> -->
                        <!-- </div> -->
                    </div>
                </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
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
        $(document).ready(function(){
            // for call requisition approvl cart
            function call_requisition_appr_cart(){
                $.ajax({
                    url         : "nemesis/stc_procurement.php",
                    method      : "POST",
                    data        : {
                        call_reuisition_appr_cart_call:1
                    },
                    success     : function(requisition_cart){
                        $('.stc-requisition-cart-approval-reload').html(requisition_cart);
                    }
                });
            }
            call_requisition_appr_cart();

            // supervisor requist
            $('body').delegate('.ag-req-show-grid', 'click', function(e){
                e.preventDefault();
                var odid=$(this).attr("id");
                $('#togreqdiv'+odid).toggle(400);
                $.ajax({
                    url : "nemesis/stc_procurement.php",
                    method : "POST",
                    data : {
                        get_req_orders_pert:1,
                        odid:odid
                    },
                    success : function(orders){
                        // console.log(orders);
                        $('#togreqdiv'+odid).html(orders);
                    }
                });            
            });

            $('body').delegate('.add_to_accept_cart', 'click', function(e){
                e.preventDefault();
                $(this).closest("td").html("#");
                $(this).css('color','red');
                var item_id=$(this).attr("id");
                var item_req_id=$(this).attr("req-id");
                var itemqty=$('.stc-sup-appr-qty'+item_id).val();
                var itemstatus=1;
                $.ajax({
                    url : "nemesis/stc_procurement.php",
                    method : "POST",
                    data : {
                        go_for_req_sess:1,
                        item_id:item_id,
                        item_req_id:item_req_id,
                        itemqty:itemqty,
                        itemstatus:itemstatus
                    },
                    success : function(requisition){
                        call_requisition_appr_cart();
                        // console.log(requisition);
                        // alert(requisition);
                    }
                });
            });

            

            var req_id=0;
            var list_id=0;
            // call req list items edit
            $('body').delegate('.remove_from_purchase', 'click', function(e){
                e.preventDefault();
                if(confirm("Are you sure to reject this item?")){
                    req_id=$(this).attr("operat-ic");
                    list_id=$(this).attr("list-id");
                    $('.rejectionmodalbtn').click();
                }
            });
            $('body').delegate('.remove_from_purchase_finally', 'click', function(e){
                e.preventDefault();
                var reason=$('.reason-text').val();
                if(reason.trim()==""){
                    alert("Please enter reason for rejection.");
                    return false;
                }
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        stc_req_edit_item_delete:1,
                        req_id:req_id,
                        list_id:list_id,
                        reason:reason
                    }, 
                    success     : function(response_items){
                        // console.log(response_items);
                        alert("Item Rejected Successfully.");
                        window.location.reload();
                    }
                });
            });

            $('body').delegate('.addtoreqcombinercart', 'click', function(e){
                e.preventDefault();
                $(this).css('color','red');
                var odid=$(this).attr("id");
                $.ajax({
                    url : "nemesis/stc_procurement.php",
                    method : "POST",
                    data : {
                        requisition_combined:1,
                        odid:odid
                    },
                    success : function(orders){
                        // console.log(orders);
                        alert(orders);
                        call_requisition_appr_cart();
                    }
                });
            });

            $('body').delegate('.removreqitems', 'click', function(e){
                e.preventDefault();
                var req_id=$(this).attr("id");
                $.ajax({
                    url : "nemesis/stc_procurement.php",
                    method : "POST",
                    data : {
                        remove_req_sess_act:1,
                        req_id:req_id
                    },
                    success : function(requisition){
                        // console.log(requisition);
                        alert(requisition);
                        call_requisition_appr_cart();
                    }
                });
            });

            $('body').delegate('.stc-proc-place-order-btn', 'click', function(e){
                e.preventDefault();
                var cust_order_refr=$('.stc-proc-place-order-refrenece').val();
                $.ajax({
                    url : "nemesis/stc_procurement.php",
                    method : "POST",
                    data : {
                        place_req_sess_act:1,
                        cust_order_refr:cust_order_refr
                    },
                    success : function(requisition){
                        // console.log(requisition);
                        alert(requisition);
                        window.location.reload();
                    }
                });
            });

            $('body').delegate('.removerequisitionwithitems', 'click', function(e){
                e.preventDefault();
                var req_id=$(this).attr('req-id');
                var req_item_id=$(this).attr('id');
                if(confirm("Are you sure want to delete this requisition?")){
                    $(this).closest('tr').remove();
                    $.ajax({
                        url : "nemesis/stc_procurement.php",
                        method : "POST",
                        data : {
                            remove_requisition:1,
                            req_id:req_id,
                            req_item_id:req_item_id
                        },
                        success : function(requisition){
                            // console.log(requisition);
                        }
                    });
                }
            });

            

            $('.searc-pro-table').keyup(function() {
                // Get the value from the search input
                var searchText = $(this).val().toLowerCase();

                // Loop through each table row
                $('.stc-pro-req-table tbody tr').each(function() {
                    var rowData = $(this).text().toLowerCase();

                    // Show or hide the row based on whether the search text is found
                    if (rowData.indexOf(searchText) === -1) {
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                });
            });
                        
            $('.master-checkbox').click(function() {
                if($(this).prop('checked')){
                    $('.select-req').prop('checked', true);
                }else{
                    $('.select-req').prop('checked', false);
                }
            });
                        
            $('.acceptall').click(function() {
                $('.select-req:checked').each(function() {
                    $(this).closest('tr').find('.add_to_accept_cart').click();
                });
                $('.master-checkbox').prop('checked', false);
                $('.select-req').prop('checked', false);
                call_requisition_appr_cart();
            });

            
        });
    </script>
    <a href="#" data-toggle="modal" data-target="#stc-sup-requisition-rejection-modal" class="rejectionmodalbtn" style="display: none;"></a>
</body>
</html>
<div class="modal fade" id="stc-sup-requisition-rejection-modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Reason for Rejection</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <h4>Reason :</h4>
                    <textarea class="form-control reason-text" placeholder="Enter reason for rejection." rows="5"></textarea></br>
                </div>
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <input type="hidden" class="stc-super-own-req-id-hidd">
                    <button class="btn btn-success remove_from_purchase_finally" href="Javascript:void(0)">Send</button>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>