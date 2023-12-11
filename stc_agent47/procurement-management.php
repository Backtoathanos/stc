<?php  
session_start(); 
if(isset($_SESSION["stc_agent_id"]) && $_SESSION["stc_agent_role"]==2){ 
}else{ 
    header('Location: ' . $_SERVER['HTTP_REFERER']);
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
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-0" data-toggle="tab" href="#check-order">
                                    <span>Create Order <b><i class="pe-7s-look"></i></b></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#sup-requis">
                                    <span>Create Requisition <b><i class="pe-7s-look"></i></b></span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane tabs-animation fade " id="check-order" role="tabpanel">
                                <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                                    <li class="nav-item">
                                        <a role="tab" class="nav-link" id="tab-0" data-toggle="tab" href="#order">
                                            <span>Order <b><i class="pe-7s-sheet"></i></b></span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
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
                                                                            }else{}
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
                            </div>
                            <div class="tab-pane tabs-animation fade show active" id="sup-requis" role="tabpanel">
                                <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                                    <li class="nav-item">
                                        <a role="tab" class="nav-link" id="tab-0" data-toggle="tab" href="#requisition">
                                            <span>Requisition <b><i class="pe-7s-look"></i></b></span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#requisition-cart">
                                            <span>Cart <b><i class="pe-7s-cart"></i></b></span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane tabs-animation fade " id="requisition" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="main-card mb-3 card">
                                                    <div class="card-body"><h5 class="card-title">Supervisor Requisition</h5>
                                                        <form class="#">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <table class="mb-0 table table-hover table-responsive">
                                                                        <thead>
                                                                            <th>Requisition ID</th>
                                                                            <th>Requisition Date</th>
                                                                            <th>Requisition From</th>
                                                                            <th>Requisition For</th>
                                                                            <th>Requisition Status</th>
                                                                            <th>View</th>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php 
                                                                                $requissuperqry=mysqli_query($con, "
                                                                                    SELECT 
                                                                                        `stc_cust_super_requisition_list_id`,
                                                                                        `stc_cust_super_requisition_list_date`,
                                                                                        `stc_cust_project_title`,
                                                                                        `stc_cust_pro_supervisor_fullname`,
                                                                                        `stc_cust_super_requisition_list_status`
                                                                                    FROM `stc_cust_super_requisition_list`
                                                                                    INNER JOIN `stc_cust_pro_supervisor` 
                                                                                    ON `stc_cust_pro_supervisor_id`=`stc_cust_super_requisition_list_super_id`
                                                                                    INNER JOIN `stc_cust_project` 
                                                                                    ON `stc_cust_project_id`=`stc_cust_super_requisition_list_project_id`
                                                                                    WHERE `stc_cust_super_requisition_list_status`='2'
                                                                                    ORDER BY `stc_cust_super_requisition_list_id` DESC
                                                                                ");
                                                                                if(mysqli_num_rows($requissuperqry)!=0){
                                                                                    foreach($requissuperqry as $requisrow){
                                                                                        $reqstatus='';
                                                                                        if($requisrow['stc_cust_super_requisition_list_status']==1){
                                                                                            $reqstatus="PROCESS";
                                                                                        }elseif($requisrow['stc_cust_super_requisition_list_status']==2){
                                                                                            $reqstatus="PASSED";
                                                                                        }else{
                                                                                            $reqstatus="ACCEPTED";
                                                                                        }
                                                                                        echo '
                                                                                            <tr>
                                                                                                <td>'.$requisrow['stc_cust_super_requisition_list_id'].'</td>
                                                                                                <td>'.$requisrow['stc_cust_super_requisition_list_date'].'</td>
                                                                                                <td>'.$requisrow['stc_cust_pro_supervisor_fullname'].'</td>
                                                                                                <td>'.$requisrow['stc_cust_project_title'].'</td>
                                                                                                <td>'.$reqstatus.'</td>
                                                                                                <td><a href="#" style="font-size: 25px;font-weight: bold;color: black;" class="ag-req-show-grid" id="'.$requisrow['stc_cust_super_requisition_list_id'].'"><i class="fas fa-eye"></i></a></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                            <td colspan="6">
                                                                                                <div style="display:none;"" id="togreqdiv'.$requisrow['stc_cust_super_requisition_list_id'].'">
                                                                                                    Loading...
                                                                                                </div>
                                                                                            </td>
                                                                                        </tr>
                                                                                        ';
                                                                                    }
                                                                                }else{
                                                                                        echo '
                                                                                            <tr>
                                                                                                <td colspan="6">
                                                                                                    <b>No Requisition Found!!!</b>
                                                                                                </td>
                                                                                            </tr>
                                                                                        ';
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
                                        <table class="mb-0 table table-hover">
                                            <thead>
                                                <th>Requisition ID</th>
                                                <th>Requisition From</th>
                                                <th>Requisition For</th>
                                                <th>Requisition Items Requisition Request Qty & Approved Qty & Unit </th>
                                                <th>Action</th>
                                            </thead>
                                            <tbody class="stc-requisition-cart-approval-reload">
                                                
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
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script type="text/javascript" src="./assets/scripts/loginopr.js"></script>
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
                $(this).css('color','red');
                var item_id=$(this).attr("id");
                var item_req_id=$(this).attr("req-id");
                var itemqty=$('.stc-sup-appr-qty'+item_id).val();
                var itemstatus=$('.stc-sup-items-status'+item_id).val();
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
                        // console.log(requisition);
                        alert(requisition);
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
        });
    </script>
</body>
</html>