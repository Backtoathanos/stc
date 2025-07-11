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
    <title>Order Management - STC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="">
    <meta name="msapplication-tap-highlight" content="no">
    <!-- Latest compiled and minified CSS -->
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
                                    <div>Order Management
                                        <div class="page-title-subheading">This sections shows you your supervisor orders & requirements here.<h3>&#9786;</h3>.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>            
                        <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                            <!-- <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-0" data-toggle="tab" href="#check-order">
                                    <span>Check Order <b><i class="pe-7s-look"></i></b></span>
                                </a>
                            </li> -->
                            <li class="nav-item">
                                <a role="tab" class="nav-link active" id="tab-1" data-toggle="tab" href="#sup-requis">
                                    <span>Check Requisition <b><i class="pe-7s-look"></i></b></span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane tabs-animation fade " id="check-order" role="tabpanel">
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
                            <div class="tab-pane tabs-animation fade show active" id="sup-requis" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">Supervisor Requisition</h5>
                                                <form class="#">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <input type="text" id="myInput" placeholder="Search here" class="form-control">
                                                        </div>
                                                        <div class="col-md-12" style="overflow-x:auto;">
                                                            <table class="mb-0 table table-hover table-bordered" id="stc-requis-table">
                                                                <thead>
                                                                    <th class="text-center">Sl No</th>
                                                                    <th class="text-center">Requisition No & Date</th>
                                                                    <th class="text-center">Requisition For</th>
                                                                    <th class="text-center">Requisition From</th>
                                                                    <th class="text-center">Item Desc</th>
                                                                    <th class="text-center">Unit</th>
                                                                    <th class="text-center">Quantity</th>
                                                                    <th class="text-center">Approve Quantity</th>
                                                                    <th class="text-center">Remains Quantity</th>
                                                                    <th class="text-center">Status</th>
                                                                    <th class="text-center">Priority</th>
                                                                    <th class="text-center">Type</th>
                                                                    <th class="text-center">ADD</th>
                                                                    <th class="text-center">REJECT</th>
                                                                </thead>
                                                                <tbody>
                                                                <?php 
                                                                        $reqstatus='';
                                                                        $requissuperqry=mysqli_query($con, "
                                                                            SELECT DISTINCT
                                                                                `stc_cust_super_requisition_list`.`stc_cust_super_requisition_list_id` as list_id,
                                                                                `stc_cust_super_requisition_list_date`,
                                                                                `stc_cust_project_title`,
                                                                                `stc_cust_pro_supervisor_fullname`,
                                                                                `stc_cust_super_requisition_list_status`,
                                                                                `stc_cust_super_requisition_list_items_req_id`,
                                                                                `stc_cust_super_requisition_list_items`.`stc_cust_super_requisition_list_id` as item_list_id,
                                                                                `stc_cust_super_requisition_list_items_title`,
                                                                                `stc_cust_super_requisition_list_items_unit`,
                                                                                `stc_cust_super_requisition_list_items_reqqty`,
                                                                                `stc_cust_super_requisition_list_items_approved_qty`,
                                                                                `stc_cust_super_requisition_list_items_status`,
                                                                                `stc_cust_super_requisition_items_finalqty`,
                                                                                `stc_cust_super_requisition_items_priority`,
                                                                                `stc_cust_super_requisition_items_type`
                                                                            FROM `stc_cust_super_requisition_list_items`
                                                                            LEFT JOIN `stc_cust_super_requisition_list` 
                                                                            ON `stc_cust_super_requisition_list`.`stc_cust_super_requisition_list_id`=`stc_cust_super_requisition_list_items_req_id`
                                                                            LEFT JOIN `stc_cust_pro_supervisor` 
                                                                            ON `stc_cust_pro_supervisor_id`=`stc_cust_super_requisition_list_super_id`
                                                                            LEFT JOIN `stc_cust_project` 
                                                                            ON `stc_cust_project_id`=`stc_cust_super_requisition_list_project_id`
                                                                            LEFT JOIN `stc_cust_project_collaborate` 
                                                                            ON `stc_cust_project_id`=`stc_cust_project_collaborate_projectid`
                                                                            LEFT JOIN `stc_cust_pro_supervisor_collaborate` 
                                                                            ON `stc_cust_pro_supervisor_id`=`stc_cust_pro_supervisor_collaborate_userid`
                                                                            WHERE (
                                                                                `stc_cust_project_createdby`='".$_SESSION['stc_agent_id']."' OR 
                                                                                `stc_cust_project_collaborate_teamid`='".$_SESSION['stc_agent_id']."'
                                                                            ) AND (
                                                                                `stc_cust_pro_supervisor_created_by`='".$_SESSION['stc_agent_id']."' OR 
                                                                                `stc_cust_pro_supervisor_collaborate_teamid`='".$_SESSION['stc_agent_id']."'
                                                                            ) AND stc_cust_super_requisition_list_status<3 AND `stc_cust_super_requisition_list_items_approved_qty`=0
                                                                            ORDER BY DATE(`stc_cust_super_requisition_list_date`) DESC
                                                                        ");
                                                                        $sl=0;
                                                                        if(mysqli_num_rows($requissuperqry)!=0){
                                                                            foreach($requissuperqry as $requisrow){
                                                                                $actionstatus="#";
                                                                                $deletstatus="#";
                                                                                if($requisrow['stc_cust_super_requisition_list_status']==1){
                                                                                    $actionstatus='
                                                                                        <a href="#" class="btn btn-primary add_to_purchase" atc-ic="'.$requisrow['item_list_id'].'"id="add_to_accept_cart'.$requisrow['item_list_id'].'" title="Approve" style="font-size: 35px;color: black;"><i class="fas fa-plus-circle"></i></a>
                                                                                    ';
                                                                                    $deletstatus='
                                                                                        <a href="#" class="btn btn-danger remove_from_purchase" operat-ic="'.$requisrow['item_list_id'].'" list-id="'.$requisrow['list_id'].'" title="Reject" id="rem_from_accept_cart'.$requisrow['item_list_id'].'" style="font-size: 35px;color: black;"><i class="fas fa-ban" ></i></a>
                                                                                    ';
                                                                                }elseif($requisrow['stc_cust_super_requisition_list_status']==2){
                                                                                    // $actionstatus='<a href="#" class="btn btn-danger remove_from_purchase" operat-ic="'.$requisrow['item_list_id'].'"id="rem_from_accept_cart'.$requisrow['item_list_id'].'" style="font-size: 35px;color: black;"><i class="fas fa-trash" ></i></a>';
                                                                                }
                                                                                $changedstatus='';
                                                                                $pdid=0;
                                                                                $reminder=$requisrow['stc_cust_super_requisition_list_items_reqqty'] - $requisrow['stc_cust_super_requisition_list_items_approved_qty'];
                                                                                $selected="selected";
                                                                                $unselected="";
                                                                                $status_selected=$requisrow['stc_cust_super_requisition_list_items_status']==1 ? $selected : $unselected;
                                                                                $trid="stc-req-tr-".$requisrow['item_list_id'];
                                                                                $priority=$requisrow['stc_cust_super_requisition_items_priority']==2 ? "Urgent" : "Normal";
                                                                                $style=$requisrow['stc_cust_super_requisition_items_priority']==2 ? 'style="background:#ffa5a5;color:black"' : "";
                                                                                if($requisrow['stc_cust_super_requisition_list_items_status']==1){
                                                                                    $sl++;
                                                                                    $status=$requisrow['stc_cust_super_requisition_list_items_status']==1? '<span style="background-color: #3498db; color: white; padding: 2px 6px; border-radius: 3px;">Ordered</span>' : '<span style="background-color: #2ecc71; color: white; padding: 2px 6px; border-radius: 3px;">Approved</span>';
                                                                                    echo '
                                                                                        <tr id="'.$trid.'" class="tr-search-fromhere" '.$style.'>
                                                                                            <td class="text-center">'.$sl.'</td>
                                                                                            <td>'.$requisrow['list_id'].' <br> '.date('d-m-Y h:i a', strtotime($requisrow['stc_cust_super_requisition_list_date'])).'</td>
                                                                                            <td>'.$requisrow['stc_cust_project_title'].'</td>
                                                                                            <td>'.$requisrow['stc_cust_pro_supervisor_fullname'].'
                                                                                            </td>
                                                                                            <td>'.$requisrow['stc_cust_super_requisition_list_items_title'].'</td>
                                                                                            <td class="text-center">'.$requisrow['stc_cust_super_requisition_list_items_unit'].'</td>
                                                                                            <td class="text-right">
                                                                                                '.number_format($requisrow['stc_cust_super_requisition_list_items_reqqty'], 2).'
                                                                                                <input type="hidden" class="stc-sup-req-qty'.$requisrow['item_list_id'].'" value="'.$requisrow['stc_cust_super_requisition_list_items_reqqty'].'">
                                                                                            </td>
                                                                                            <td>
                                                                                                <input type="number" class="form-control stc-sup-appr-qty'.$requisrow['item_list_id'].'" style="width: 60px;padding: 4px;" value="'.$requisrow['stc_cust_super_requisition_list_items_approved_qty'].'">
                                                                                            </td>
                                                                                            <td class="text-right">'.number_format($requisrow['stc_cust_super_requisition_list_items_reqqty'], 2).'</td>
                                                                                            <td>'.$status.'</td>
                                                                                            <td class="text-center">'.$priority.'</td>
                                                                                            <td>'.$requisrow['stc_cust_super_requisition_items_type'].'</td>
                                                                                            <td class="text-center">'.$actionstatus.'</td>
                                                                                            <td class="text-center">'.$deletstatus.'</td>
                                                                                        </tr>
                                                                                    ';
                                                                                }
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
                        </div>
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
        // function myFunction() {
        //     var input, filter, table, tr, td, i, txtValue;
        //     input = document.getElementById("myInput");
        //     filter = input.value.toUpperCase();
        //     table = document.getElementById("stc-requis-table");
        //     tr = table.getElementsByTagName("tr");
        //     for (i = 0; i < tr.length; i++) {
        //         td = tr[i].getElementsByTagName("td")[0];
        //         if (td) {
        //             txtValue = td.textContent || td.innerText;
        //             if (txtValue.toUpperCase().indexOf(filter) > -1) {
        //                 tr[i].style.display = "";
        //             } else {
        //                 tr[i].style.display = "none";
        //             }
        //         }       
        //     }
        // }
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

        $("#myInput").on("keyup", function(e) {
            e.preventDefault();
            var value = $(this).val().toLowerCase();
            $("#stc-requis-table .tr-search-fromhere").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });

    </script>
    <script>
        $(document).ready(function(){
            // supervisor order
            $('body').delegate('.ag-show-grid', 'click', function(e){
                e.preventDefault();
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

            // change order item status
            $('body').delegate('.setforwardaction', 'click', function(e){
                e.preventDefault();
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

            // place order
            $('body').delegate('.placeorder', 'click', function(e){
                e.preventDefault();
                var odid=$(this).attr("id");
                $.ajax({
                    url : "nemesis/stc_project.php",
                    method : "POST",
                    data : {
                        place_order:1,
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

            // set to clean
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

            /*--------------------------Requistion Section-------------------------*/
            // add to purchase
            $('body').delegate('.add_to_purchase', 'click', function(e){
                e.preventDefault();
                if(confirm("Are you sure to proceed this item?")){
                    var item_id=$(this).attr("atc-ic");
                    var itemqty=$('.stc-sup-appr-qty'+item_id).val();
                    var itemreqqty=$('.stc-sup-req-qty'+item_id).val();
                    var itemstatus=1;
                    if((parseFloat(itemqty) > 0) && (parseFloat(itemreqqty) >= parseFloat(itemqty))){
                        // $(this).css('display','none');
                        $.ajax({
                            url : "nemesis/stc_project.php",
                            method : "POST",
                            data : {
                                stc_addtopurchase:1,
                                item_id:item_id,
                                itemqty:itemqty,
                                itemstatus:itemstatus
                            },
                            success : function(requisition){
                                // console.log(requisition);
                                if(requisition.trim()=="success"){
                                    alert("Your requisition is sent. Thankyou and be patience from procurement approval.");
                                    $('#stc-req-tr-'+item_id).toggle('500');
                                    // $('#stc-req-tr-'+item_id).remove();
                                }else{
                                    alert("Something went wrong. Please check and try again.");
                                }
                            }
                        });
                    }else{
                        alert("Invalid quantity.");
                    }
                }
            });

            // supervisor requist
            $('body').delegate('.ag-req-show-grid', 'click', function(e){
                e.preventDefault();
                var odid=$(this).attr("id");
                $('#togreqdiv'+odid).toggle(400);
                $.ajax({
                    url : "nemesis/stc_project.php",
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

            // add to cart per line items
            $('body').delegate('.add_to_accept_cart', 'click', function(e){
                e.preventDefault();
                // $(this).css('color','red');
                $(this).css('display','none');
                var item_id=$(this).attr("atc-ic");
                var itemqty=$('.stc-sup-appr-qty'+item_id).val();
                var itemstatus=$('.stc-sup-items-status'+item_id).val();
                $.ajax({
                    url : "nemesis/stc_project.php",
                    method : "POST",
                    data : {
                        go_for_req_sess:1,
                        item_id:item_id,
                        itemqty:itemqty,
                        itemstatus:itemstatus
                    },
                    success : function(requisition){
                        // console.log(requisition);
                        alert(requisition);
                        $('#rem_from_accept_cart'+item_id).css('display','block');
                    }
                });
            });


            // remove from cart per line items
            $('body').delegate('.rem_from_accept_cart', 'click', function(e){
                e.preventDefault();
                $(this).css('display','none');
                var item_id=$(this).attr("operat-ic");
                $.ajax({
                    url : "nemesis/stc_project.php",
                    method : "POST",
                    data : {
                        remove_from_req_sess:1,
                        item_id:item_id
                    },
                    success : function(requisition){
                        // console.log(requisition);
                        // alert(requisition);
                        $('#add_to_accept_cart'+item_id).css('display','block');
                    }
                });
            });

            // placed this requisiton 
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
                        if(orders == "reload"){
                            alert("Session expired. Reloading....");
                            window.location.reload();
                        }else if(orders == "no"){
                            alert("Cant placed your requisition. Please check remains quantity & enable item first!!!");
                        }else{
                            alert(orders);
                            $('#togreqdiv'+odid).toggle(400);
                        }                        
                    }
                });
            });

            // call req list items edit
            $('body').delegate('.edit-req-item', 'click', function(e){
                e.preventDefault();
                var req_id=$(this).attr("id");
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        stc_req_edit_item_show:1,
                        req_id:req_id
                    }, 
                    success     : function(response_items){
                        // console.log(response_items);
                        $(".stc-super-own-name-text").val(response_items);
                        $('.stc-super-own-req-id-hidd').val(req_id);
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

            // update  req list items edit
            $('body').delegate('.stc-super-own-edit-btn', 'click', function(e){
                e.preventDefault();
                var req_item_id=$('.stc-super-own-req-id-hidd').val();
                var req_item_name=$('.stc-super-own-name-text').val();
                var req_item_priority=$('.stc-sup-priority').val();
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        stc_req_edit_item_update:1,
                        req_item_id:req_item_id,
                        req_item_name:req_item_name,
                        req_item_priority:req_item_priority
                    }, 
                    success     : function(response_items){
                        // console.log(response_items);
                        response_items=response_items.trim();
                        if(response_items=="Item Updated Successfully."){
                            alert(response_items);
                            window.location.reload();
                        }else{
                            alert(response_items);
                        }

                    }
                });
            });
        });
    </script>
    <a href="#" data-toggle="modal" data-target="#stc-sup-requisition-rejection-modal" class="rejectionmodalbtn" style="display: none;"></a>
</body>
</html>
<div class="modal fade" id="stc-sup-requisition-item-edit-modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Requisition Item Change</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-6">
                    <h4>Item Name :</h4>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6">
                   <input type="text" class="form-control stc-super-own-name-text">
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6">
                    <h4>Item Priority :</h4>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6">
                <select class="form-control stc-sup-priority">
                    <option value="1">Normal</option>
                </select>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6">
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6">
                    <input type="hidden" class="stc-super-own-req-id-hidd">
                    <button class="btn btn-success stc-super-own-edit-btn" href="#">Save</button>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
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