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
                                                                <label for="exampleEmail" class="">Project Name</label>
                                                                <select name="stc-pro-tra-pro-id" class="form-control" required>
                                                                    <?php 
                                                                        include_once("../MCU/db.php");
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
                                                        <div class="col-sm-12 col-md-12">
                                                            <div class="position-relative form-group">
                                                                <label for="exampleEmail" class="">Item Name</label>
                                                                <textarea class="mb-2 form-control" name="stc-pro-tra-item-name" placeholder="Enter Item Name" required></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-4">
                                                            <div class="position-relative form-group">
                                                                <label for="exampleEmail" class="">Service</label>
                                                                <select name="stc-pro-tra-service" class="form-control" required>
                                                                    <option>HVAC</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-4">
                                                            <div class="position-relative form-group">
                                                                <label for="exampleEmail" class="">Unit</label>
                                                                <select name="stc-pro-tra-unit" class="form-control" required>
                                                                    <option>LOT</option>
                                                                    <option>NOS</option>
                                                                    <option>RMT</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-12 col-md-4">
                                                            <div class="position-relative form-group">
                                                                <label for="exampleEmail" class="">Drawing Quantity</label>
                                                                <input type="number" class="mb-2 form-control" name="stc-pro-tra-quantity" placeholder="Enter Drawing Quantity" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="stc-pro-tra-procurement-hit">
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
                                                            <table class="table table-stripped table-bordered table-hover">
                                                                <thead>
                                                                    <th class="text-center">Procurment Tracker Id</br>Procurment Tracker Date</th>
                                                                    <th class="text-center">Project Name</th>
                                                                    <th class="text-center">Item Name</th>
                                                                    <th class="text-center">Service</th>
                                                                    <th class="text-center">Uom</th>
                                                                    <th class="text-center">Drawing Quantity</th>
                                                                    <th class="text-center">Buyer / Maker</th>
                                                                    <th class="text-center">PO/WO NO</th>
                                                                    <th class="text-center">PO DATE</th>
                                                                    <th class="text-center"> PO BASIC VALUE</th>
                                                                    <th class="text-center"> GST </th>
                                                                    <th class="text-center"> PO AMOUNT </th>
                                                                    <th class="text-center">TDS APPROVAL</th>
                                                                    <th class="text-center">Mfg Clearance (Client)</th>
                                                                    <th class="text-center">Mfg lead time</th>
                                                                    <th class="text-center">OEM / DELEAR location</th>
                                                                    <th class="text-center">Transit time</th>
                                                                    <th class="text-center">PLAN</th>
                                                                    <th class="text-center">Actual/ Forecasted</th>
                                                                    <th class="text-center"> ADVANCE PAYMENT </th>
                                                                    <th class="text-center">DATE</th>
                                                                    <th class="text-center"> BEFORE DISPTACH </th>
                                                                    <th class="text-center">DATE</th>
                                                                    <th class="text-center">PDC</th>
                                                                    <th class="text-center">DURATION IN DAYS</th>
                                                                    <th class="text-center">TRANSPORATION CHARGE</th>
                                                                    <th class="text-center">Remarks</th>
                                                                    <th class="text-center">Action</th>
                                                                </thead>
                                                                <tbody class="procurment-tracker-data-field">

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
        $(document).ready(function(e){

            var begdate="";
            var enddate="";
            // for create procurement  tracker
            $('.create-procurment-tracker').on('submit', function(e){
                e.preventDefault();
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : new FormData(this),
                    contentType : false,
                    processData : false,
                    // dataType    : "JSON",
                    success     : function(response) {
                        // console.log(response);
                        var argument=response.trim();
                        if (argument == "yes") {
                          alert("Procurement Tracker Created!!!");
                          $(".create-procurment-tracker")[0].reset();
                          procurement_tracker_call(begdate, enddate);
                        } else if (argument == "no") {
                          alert("Hmmm!!! Something went wrong, Procurment Tracker not created!!!");
                        } else if (argument == "logout") {
                          window.location.reload();
                        } else if(argument=="empty"){
                          alert("Do not empty any field!!!");
                        }
                    }
                });
            });

            // for procuremrnt tracker
            procurement_tracker_call(begdate, enddate);
            function procurement_tracker_call(begdate, enddate){
                $.ajax({
                    url : "nemesis/stc_project.php",
                    method : "POST",
                    data : {
                        get_procurment_tracker:1,
                        begdate:begdate,
                        enddate:enddate
                    },
                    success : function(response){
                        // console.log(response);
                        $('.procurment-tracker-data-field').html(response);
                    }
                });
            }

            // for update procurement tracker
            $('body').delegate('.stc-tra-addmod', 'click', function(e){
                e.preventDefault();
                var add_pro_id=$(this).attr('id');
                $('#stc-hidden-procurement-tracker-id').val(add_pro_id);
                stc_perticular_procurment_tracker_call(add_pro_id);
                procurement_tracker_call(begdate, enddate);
                $('.bd-procurementdetails-modal-lg').modal('show');
            });

            // for delete procurement tracker 
            $('body').delegate('.stc-tra-deletemod', 'click', function(e){
                e.preventDefault();
                var delete_pro_id=$(this).attr('id');
                $.ajax({
                    url : "nemesis/stc_project.php",
                    method : "POST",
                    data : {
                        delete_procurment_tracker:1,
                        delete_pro_id:delete_pro_id
                    },
                    success : function(response){
                        // console.log(response);
                        var obj_response=response.trim();
                        if(obj_response=="yes"){
                            alert("Record deleted successfully!!!");
                            procurement_tracker_call(begdate, enddate);
                        }else if(obj_response=="no"){
                            alert("Something went wrong. Record not deleted");
                        }
                    }
                });
            });

            // call perticular data
            function stc_perticular_procurment_tracker_call(add_pro_id){
                $.ajax({
                    url : "nemesis/stc_project.php",
                    method : "POST",
                    data : {
                        get_procurment_tracker_perticular:1,
                        add_pro_id:add_pro_id
                    },
                    dataType : "JSON",
                    success : function(response){
                        // console.log(response);
                        $('#stc-pro-tra-buyer-maker').val(response['stc_cust_procurement_tracker_buyer']);
                        $('#stc-pro-tra-wo-no-po-no').val(response['stc_cust_procurement_tracker_po_no']);
                        $('#stc-pro-tra-po-date').val(response['stc_cust_procurement_tracker_po_date']);
                        $('#stc-pro-tra-po-basic-value').val(response['stc_cust_procurement_tracker_basicamt']);
                        $('#stc-pro-tra-gst').val(response['stc_cust_procurement_tracker_gst']);
                        $('#stc-pro-tra-tds-approval').val(response['stc_cust_procurement_tracker_approval_date']);
                        $('#stc-pro-tra-mfg-clearance-approval').val(response['stc_cust_procurement_tracker_mfg_clearancedate']);
                        $('#stc-pro-tra-mfg-lead-time').val(response['stc_cust_procurement_tracker_mfg_leadtime']);
                        $('#stc-pro-tra-oem-dealer-location').val(response['stc_cust_procurement_tracker_location']);
                        $('#stc-pro-tra-transit-time').val(response['stc_cust_procurement_tracker_transittime']);
                        $('#stc-pro-tra-plan').val(response['stc_cust_procurement_tracker_deleverytimeplan']);
                        $('#stc-pro-tra-actual-forecasted').val(response['stc_cust_procurement_tracker_delivered_actual']);
                        $('#stc-pro-tra-transportaion-charge').val(response['stc_cust_procurement_tracker_transport_charge']);
                        $('#stc-pro-tra-remarks').val(response['stc_cust_procurement_tracker_remartks']);
                    }
                });
            }

            // update on change
            $('body').delegate('.stc-pro-tra-update-field', 'focusout', function(e){
                e.preventDefault();
                stc_perticular_procurment_tracker_update();
                $('.record-popup').remove();
                $(this).after("<span class='record-popup'>Record saved.</span>");
            });

            // update perticular data
            function stc_perticular_procurment_tracker_update(){
                var pro_id=$('#stc-hidden-procurement-tracker-id').val();

                var buyer=$('#stc-pro-tra-buyer-maker').val();
                var po_no_id=$('#stc-pro-tra-wo-no-po-no').val();
                var po_no_date=$('#stc-pro-tra-po-date').val();
                var amount=$('#stc-pro-tra-po-basic-value').val();
                var gst=$('#stc-pro-tra-gst').val();
                var approval=$('#stc-pro-tra-tds-approval').val();
                var mfgclear=$('#stc-pro-tra-mfg-clearance-approval').val();
                var leadtime=$('#stc-pro-tra-mfg-lead-time').val();
                var dealer_loca=$('#stc-pro-tra-oem-dealer-location').val();
                var transittime=$('#stc-pro-tra-transit-time').val();
                var plan=$('#stc-pro-tra-plan').val();
                var actual=$('#stc-pro-tra-actual-forecasted').val();
                var remarks=$('#stc-pro-tra-remarks').val();
                var transport_charge=$('#stc-pro-tra-transportaion-charge').val();

                $.ajax({
                    url : "nemesis/stc_project.php",
                    method : "POST",
                    data : {
                        update_procurment_tracker:1,
                        pro_id:pro_id,
                        buyer:buyer,
                        po_no_id:po_no_id,
                        po_no_date:po_no_date,
                        amount:amount,
                        gst:gst,
                        approval:approval,
                        mfgclear:mfgclear,
                        leadtime:leadtime,
                        dealer_loca:dealer_loca,
                        transittime:transittime,
                        plan:plan,
                        actual:actual,
                        transport_charge:transport_charge,
                        remarks:remarks
                    },
                    success : function(response){
                        // console.log(response);
                        response=response.trim();
                        if(response=="no"){
                            alert("Hmmm something went wrong, Record not saved");
                        }else{
                            var pro_id = $('#stc-hidden-procurement-tracker-id').val();
                            stc_perticular_procurment_tracker_call(pro_id);   
                        }
                    }
                });
            }

        });

        
    </script>
</body>
</html>

<div class="modal fade bd-procurementdetails-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
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
                                    <input type="hidden" id="stc-hidden-procurement-tracker-id">
                                    <div class="col-sm-12 col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="exampleEmail" class="">Buyer / Maker</label>
                                            <input type="text" class="mb-2 form-control stc-pro-tra-update-field" id="stc-pro-tra-buyer-maker" placeholder="Enter Buyer / Maker Name" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="exampleEmail" class="">PO No. / WO No.</label>
                                            <input type="text" class="mb-2 form-control stc-pro-tra-update-field" id="stc-pro-tra-wo-no-po-no" placeholder="Enter PO No. / WO No." required>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="exampleEmail" class="">PO Date</label>
                                            <input type="date" class="mb-2 form-control stc-pro-tra-update-field" id="stc-pro-tra-po-date" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="exampleEmail" class="">PO Basic Value</label>
                                            <input type="number" class="mb-2 form-control stc-pro-tra-update-field" id="stc-pro-tra-po-basic-value" placeholder="Please enter amount" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="exampleEmail" class="">GST %</label>
                                            <select id="stc-pro-tra-gst" id="#" class="form-control stc-pro-tra-update-field" required>
                                                <option value="5">5%</option>
                                                <option value="12">12%</option>
                                                <option value="18">18%</option>
                                                <option value="28">28%</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="exampleEmail" class="">TDS Approval Date</label>
                                            <input type="date" class="mb-2 form-control stc-pro-tra-update-field" id="stc-pro-tra-tds-approval" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="exampleEmail" class="">Mfg Clearance Client</label>
                                            <input type="date" class="mb-2 form-control stc-pro-tra-update-field" id="stc-pro-tra-mfg-clearance-approval" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="exampleEmail" class="">Mfg Lead Time</label>
                                            <input type="number" class="mb-2 form-control stc-pro-tra-update-field" id="stc-pro-tra-mfg-lead-time" placeholder="Enter mfg lead time" required>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-4">
                                        <div class="position-relative form-group stc-pro-tra-update-field">
                                            <label for="exampleEmail" class="">OEM / Dealer Location</label>
                                            <select id="stc-pro-tra-oem-dealer-location" id="stc-pro-tra-location" class="form-control stc-pro-tra-update-field" required>
                                                <?php
                                                    $procityqry=mysqli_query($con, "
                                                        SELECT DISTINCT `stc_city_id`, `stc_city_name` 
                                                        FROM `stc_city` 
                                                        ORDER BY `stc_city_name` ASC
                                                    ");
                                                    foreach($procityqry as $procityrow){
                                                        echo '<option value="'.$procityrow['stc_city_id'].'">'.$procityrow['stc_city_name'].'</option>';
                                                    }

                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-4">
                                        <div class="position-relative form-group">
                                            <label for="exampleEmail" class="">Transit Time</label>
                                            <input type="number" class="mb-2 form-control stc-pro-tra-update-field" id="stc-pro-tra-transit-time" placeholder="Enter transit time" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-4">
                                        <div class="position-relative form-group">
                                            <label for="exampleEmail" class="">Transportation Charge</label>
                                            <input type="number" class="mb-2 form-control stc-pro-tra-update-field" id="stc-pro-tra-transportaion-charge" placeholder="Enter Transportation Charge" required>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-12">
                                                    <label for="exampleEmail" class="text-center">DELIVERY TIME AS PER CLERANCE / ADV PAYMENT / PO </label>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="exampleEmail" class="">PLAN</label>
                                                    <input type="date" class="mb-2 form-control stc-pro-tra-update-field" id="stc-pro-tra-plan" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="exampleEmail" class="">Actual/ Forecasted</label>
                                                    <input type="date" class="mb-2 form-control stc-pro-tra-update-field" id="stc-pro-tra-actual-forecasted" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12">
                                        <div class="position-relative form-group">
                                            <label for="exampleEmail" class="">Remarks</label>
                                            <textarea class="mb-2 form-control stc-pro-tra-update-field" id="stc-pro-tra-remarks" placeholder="Enter Remarks" required></textarea>
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