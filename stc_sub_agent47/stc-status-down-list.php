<?php  
session_start(); 
if(isset($_SESSION["stc_agent_sub_id"])){ 
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
    <title>Status Down List - STC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="">
    <meta name="msapplication-tap-highlight" content="no">
    <!-- Latest compiled and minified CSS -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="./main.css" rel="stylesheet">
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
        <div class="app-main">
            <?php include_once("sidebar-nav.php");?>
            <div class="app-main__outer">
                <div class="app-main__inner">
                    <div class="app-page-title">
                        <h3>My Status Down List</h3>
                    </div>
                    <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                        <li class="nav-item">
                            <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#view-req">
                                <span>View Status Down List</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a role="tab" class="nav-link show active" id="tab-2" data-toggle="tab" href="#create-req">
                                <span>Create Status Down List</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane tabs-animation fade" id="view-req" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12 col-xl-12"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5>Location/Site Name :</h5><br>
                                            <div class="card mb-3 widget-content bg-midnight-bloom">
                                                <select class="btn btn-success form-control load_site_name_consump" id="stc-agent-sup-std-location-find"><option>No Site Found!!!</option>
                                                </select> 
                                            </div>
                                            <div class="card mb-3 widget-content">
                                                <a href="#" class="form-control btn btn-success stc-std-list-show-hit">Find</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-xl-12"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <div class="card mb-3 widget-content stc-std-search-result">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane tabs-animation fade show active" id="create-req" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 col-xl-6"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5>Location :</h5><br>
                                            <input type="hidden" id="stc-agent-sup-std-hidden-location-id">
                                            <select class="btn btn-success form-control load_site_name_consump text-left" id="stc-agent-sup-std-location"><option>Plese select location first!!!</option>
                                            </select> 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5>Department :</h5><br>
                                            <select class="btn btn-success form-control stc-agent-sup-std-sub-location text-left" id="stc-agent-sup-std-sub-location"><option>Please select location first!!!</option>
                                            </select> 
                                            <!-- <input type="text" class="form-control stc-agent-sup-std-sub-location" placeholder="Enter Location"/> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5>Area :</h5><br>
                                            <select class="btn btn-success form-control stc-agent-sup-std-area text-left" id="stc-agent-sup-std-area"><option>Please select department first!!!</option>
                                            </select> 
                                            <!-- <input type="text" class="form-control stc-agent-sup-std-area" placeholder="Enter Area"/> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5>Equipment Type :</h5><br>
                                            <select class="btn btn-success form-control load_equipment_type_consump text-left" id="stc-agent-sup-std-equipment-type"><option>Please select area first!!!</option>
                                            </select> 
                                            <!-- <input type="text" class="form-control stc-agent-sup-std-equipment-type" placeholder="Enter Equipment Details"/> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6" style="display:none;"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5>Equipment Number :</h5><br>
                                            <select class="btn btn-success form-control load_equipment_number_consump text-left" id="stc-agent-sup-std-equipment-number" disabled><option>Please select equipment no first!!!</option>
                                            </select> 
                                            <!-- <input type="text" class="form-control stc-agent-sup-std-equipment-number" placeholder="Enter Equipment Number"/> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5>Equipment Status :</h5><br>
                                            <select class="btn btn-success form-control text-left" id="stc-agent-sup-std-equipment-status"><option>Down</option><option>Running</option>
                                            </select> 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5>Job Planning :</h5><br>
                                            <select class="btn btn-success form-control text-left" id="stc-agent-sup-std-job-plannning"><option>Preventive Maintanance</option><option>Breakdown Maintanance</option>
                                            </select> 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xl-3"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5>Quantity :</h5><br>
                                            <input type="number" class="form-control stc-agent-sup-std-qty" placeholder="Enter Quantity">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-xl-3"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5>Capacity :</h5><br>
                                            <input type="number" class="form-control stc-agent-sup-std-capacity" placeholder="Enter Capacity">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5>Reason Attribute to GLOBAL or VOLTAS :</h5><br>
                                            <select class="btn btn-success form-control text-left" id="stc-agent-sup-std-reasonattribite"><option>GLOBAL</option><option>VOLTAS</option>
                                            </select> 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5>Job Type :</h5><br>
                                            <label for="ELECTRICAL">
                                                <input type="checkbox" style="position: relative;top: 2px;" id="ELECTRICAL" class="stc-agent-sup-std-job-type" value="'ELECTRICAL'"> ELECTRICAL
                                            </label>
                                            <label for="MECHANICAL">
                                            <input type="checkbox" style="position: relative;top: 2px;" id="MECHANICAL" class="stc-agent-sup-std-job-type" value="'MECHANICAL'"> MECHANICAL
                                            </label>
                                            <label for="REFRIGERATION">
                                            <input type="checkbox" style="position: relative;top: 2px;" id="REFRIGERATION" class="stc-agent-sup-std-job-type" value="'REFRIGERATION'"> REFRIGERATION
                                            </label>
                                            <label for="CLEANING">
                                            <input type="checkbox" style="position: relative;top: 2px;" id="CLEANING" class="stc-agent-sup-std-job-type" value="'CLEANING'"> CLEANING
                                            </label>
                                            <label for="NOISY">
                                            <input type="checkbox" style="position: relative;top: 2px;" id="NOISY" class="stc-agent-sup-std-job-type" value="'NOISY'"> NOISY
                                            </label>
                                            <label for="WATER DROPPING">
                                            <input type="checkbox" style="position: relative;top: 2px;" id="WATER DROPPING" class="stc-agent-sup-std-job-type" value="'WATER DROPPING'"> WATER DROPPING
                                            </label>
                                            <label for="LUBRICATION">
                                            <input type="checkbox" style="position: relative;top: 2px;" id="LUBRICATION" class="stc-agent-sup-std-job-type" value="'LUBRICATION'"> LUBRICATION
                                            </label>
                                            <label for="CHECKING/MAINTENANCE">
                                            <input type="checkbox" style="position: relative;top: 2px;" id="CHECKING/MAINTENANCE" class="stc-agent-sup-std-job-type" value="'CHECKING/MAINTENANCE'"> CHECKING/MAINTENANCE
                                            </label>
                                            <!-- <select class="btn btn-success form-control text-left" id="stc-agent-sup-std-job-type">
                                                <option value="NA">Please select job type</option>
                                                <option>ELECTRICAL</option>
                                                <option>MECHANICAL</option>
                                                <option>REFRIGERATION</option>
                                                <option>CLEANING</option>
                                                <option>NOISY</option>
                                                <option>WATER DROPPING</option>
                                                <option>LUBRICATION</option>
                                                <option>CHECKING/MAINTENANCE</option>
                                            </select>  -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5>Job Varieties :</h5><br>
                                            <select class="btn btn-success form-control text-left" id="stc-agent-sup-std-job-varities">
                                                <option value="NA">Please select job type first</option>
                                            </select> 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5>Created By :</h5><br>
                                            <select class="btn btn-success form-control text-left" id="stc-agent-sup-std-created-by-select">
                                                <option>CLIENT</option>
                                                <option>ELECTRICIAN</option>
                                                <option>INCHARGE</option>
                                                <option>OPERATOR</option>
                                                <option>OTHER</option>
                                                <option>SUPERVISOR</option>
                                                <option>TECHNICIAN</option>
                                            </select> 
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5>Creator Name & Mobile NO :</h5><br>
                                            <input type="text" class="form-control stc-agent-sup-std-creator-details" placeholder="Enter Creator Name & Mobile NO">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5>Permit No :</h5><br>
                                            <input type="text" class="form-control stc-agent-sup-std-permit-no" placeholder="Enter Permit No">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5>Responsible Person :</h5><br>
                                            <input type="text" class="form-control stc-agent-sup-std-responsive-person" placeholder="Enter Responsive Person">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5>Reason :</h5><br>
                                            <textarea class="form-control" id="stc-agent-sup-std-reason" placeholder="Reason"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5>Material Requisition :</h5><br>
                                            <textarea class="form-control" id="stc-agent-sup-std-material-desc" placeholder="Material Description"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5>Manpower Request :</h5><br>
                                            <input type="text" class="form-control" id="stc-agent-sup-std-manpower-req" placeholder="Manpower Request" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5>Work Completion Target Date :</h5><br>
                                            <input type="date" class="form-control" id="stc-agent-sup-std-target-date" <?php echo date("Y-m-d");?> required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xl-12"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5>Tools Requisitions :</h5><br>
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='SIZE SPANNER'> SIZE SPANNER
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='RING SPANNER'> RING SPANNER
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='T SPANNER'> T SPANNER
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='TUBE CUTTER'> TUBE CUTTER
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='SWAGING  TOOL'> SWAGING  TOOL
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='HAMMER 1KG'> HAMMER 1KG
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='HAMMER  2 KG'> HAMMER  2 KG
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='COPPER PIPE FLARING TOOL'> COPPER PIPE FLARING TOOL
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='REF. CHARGING PIPE'> REF. CHARGING PIPE
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='ADJUSTABLE SPANNER'> ADJUSTABLE SPANNER
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='ALLEN KEY SET MM'> ALLEN KEY SET MM
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='ALLEN KEY SET INCH'> ALLEN KEY SET INCH
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='TORCH LIGHT'> TORCH LIGHT
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='RATCHET WRENCH'> RATCHET WRENCH
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='SCREW DRIVER SET'> SCREW DRIVER SET
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='DIGITAL CLAMP METER'> DIGITAL CLAMP METER
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='SCREW DRIVER (BIG SIZE)'> SCREW DRIVER (BIG SIZE)
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='SCREW DRIVER (SMALL SIZE)'> SCREW DRIVER (SMALL SIZE)
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='WIRE CUTTER'> WIRE CUTTER
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='HP PRESSURE GAUGE'> HP PRESSURE GAUGE
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='LP PRESSURE GAUGE'> LP PRESSURE GAUGE
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='MANIFOLD GAUGE'> MANIFOLD GAUGE
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='LPG CAN'> LPG CAN
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='WIRE BRUSH'> WIRE BRUSH
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='BRAZING GAS TORCH'> BRAZING GAS TORCH
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='BRAZING GAS TORCH'> BRAZING GAS TORCH
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='FIN STRAIGHTENER'> FIN STRAIGHTENER
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='HAND BLOWER(BOSCH)'> HAND BLOWER(BOSCH)
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='PRESSURE WASHER(SHAKTI)'> PRESSURE WASHER(SHAKTI)
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='PLIERS'> PLIERS
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='NOSE PLIERS'> NOSE PLIERS
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='VACCUME PUMP'> VACCUME PUMP
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='CHISEL'> CHISEL
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='ROUND FILE TOOL'> ROUND FILE TOOL
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='FLAT FILE TOOL'> FLAT FILE TOOL
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='ALUMINIUM LADDER '> ALUMINIUM LADDER 
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='EXTENSION  BOARD'> EXTENSION  BOARD
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='BEARING PULLER'> BEARING PULLER
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='Chain Block (2 Ton )'> Chain Block (2 Ton )
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='Welding Machine'> Welding Machine
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='Grinding Machine. (Bosch Make)'> Grinding Machine. (Bosch Make)
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='Drilling Machine (Ralli Wolf)'> Drilling Machine (Ralli Wolf)
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='Pipe Wrench (14", 18", 24", 32")'> Pipe Wrench (14", 18", 24", 32")
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='D-Shackle'> D-Shackle
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='Recovery Machine'> Recovery Machine
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='Empty Cylinder'> Empty Cylinder
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='Micron Gauge'> Micron Gauge
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='Vibration Meter'> Vibration Meter
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='Vernier Scale'> Vernier Scale
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='Temp. Gun'> Temp. Gun
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='Tachometer'> Tachometer
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='Anemometer'> Anemometer
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='Airblowe'> Airblowe
                                            <input type="checkbox" class="stc-agent-sup-std-tools-req"  value='Vaccume Cleaner'> Vaccume Cleaner
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xl-12"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5>Any comment :</h5><br>
                                            <textarea type="text" class="form-control stc-agent-sup-std-remarks" placeholder="Enter Any Comment"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xl-12"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <a href="#" class="form-control btn btn-success stc-agent-sup-std-save">Save</a>
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
        $(document).ready(function(){
            $('body').delegate('.search-icon', 'click', function(e){
                e.preventDefault();
                $(this).hide();
                $('.search-icon-2').show();
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
            // call site name vaia supervisor
            call_location();
            function call_location(){
                $.ajax({
                    url     : "nemesis/stc_std.php",
                    method  : "POST",
                    data    : {call_location:1},
                    success : function(response){
                        // console.log(response);
                        $('.load_site_name_consump').html(response);
                    }
                });
            }

             // call equipment type on select location
            $('body').delegate('.load_site_name_consump', 'change', function(e){
                e.preventDefault();
                var loca_id = $(this).val();
                $('#stc-agent-sup-std-hidden-location-id').val(loca_id);
                $.ajax({
                    url     : "nemesis/stc_std.php",
                    method  : "POST",
                    data    : {call_department:1,loca_id:loca_id},
                    success : function(response){
                        // console.log(response);
                        $('.stc-agent-sup-std-sub-location').html(response);
                    }
                });
            });

             // call sub location department type on select location
            $('body').delegate('#stc-agent-sup-std-sub-location', 'change', function(e){
                e.preventDefault();
                var loca_sub_name = $(this).val();
                var loca_id = $('#stc-agent-sup-std-hidden-location-id').val();
                $.ajax({
                    url     : "nemesis/stc_std.php",
                    method  : "POST",
                    data    : {call_area:1,loca_sub_name:loca_sub_name, loca_id:loca_id},
                    success : function(response){
                        // console.log(response);
                        $('.stc-agent-sup-std-area').html(response);
                    }
                });
            });
            
            // call area on select department
            $('body').delegate('.stc-agent-sup-std-area', 'change', function(e){
                e.preventDefault();
                var area_name = $(this).val();
                var loca_id = $('#stc-agent-sup-std-location').val();
                var department = $('#stc-agent-sup-std-sub-location').val();
                $.ajax({
                    url     : "nemesis/stc_std.php",
                    method  : "POST",
                    data    : {call_eq_type:1,loca_id:loca_id,area_name:area_name, department:department},
                    success : function(response){
                        // console.log(response);
                        $('#stc-agent-sup-std-equipment-type').html(response);
                    }
                });
            });

            // call equipment type on select location
            $('body').delegate('.load_equipment_type_consump', 'change', function(e){
                e.preventDefault();
                var eq_type_id = $(this).val();
                var eq_type_nu = $(this).attr("data-value");
                var loca_id = $('#stc-agent-sup-std-location').val();
                $.ajax({
                    url     : "nemesis/stc_std.php",
                    method  : "POST",
                    data    : {call_eq_number:1,loca_id:loca_id,eq_type_id:eq_type_id,eq_type_nu:eq_type_nu},
                    success : function(response){
                        // console.log(response);
                        $('#stc-agent-sup-std-equipment-number').html(response);
                    }
                });
            });

            // call equipment type on select location
            $('body').delegate('.stc-agent-sup-std-job-type', 'change', function(e){
                e.preventDefault();
                // var job_type = $(this).val();
                var job_type = [];
                $('.stc-agent-sup-std-job-type:checked').each(function(){
                   job_type.push($(this).val()); 
                });
                $.ajax({
                    url     : "nemesis/stc_std.php",
                    method  : "POST",
                    data    : {call_j_varities:1, job_type:job_type },
                    success : function(response){
                        // console.log(response);
                        $('#stc-agent-sup-std-job-varities').html(response);
                    }
                });
            });

            // save status down list
            $('body').delegate('.stc-agent-sup-std-save', 'click', function(e){
                e.preventDefault();
                var location = $('.stc-agent-sup-std-sub-location option:selected').text();
                var dept = $('#stc-agent-sup-std-location').val();
                var area = $('.stc-agent-sup-std-area option:selected').text();
                var eq_type = $('#stc-agent-sup-std-equipment-type').val();
                var eq_number = $('#stc-agent-sup-std-equipment-number').val();
                var eq_status = $('#stc-agent-sup-std-equipment-status').val();
                var j_plannning = $('#stc-agent-sup-std-job-plannning').val();
                var qty=$(".stc-agent-sup-std-qty").val();
                var capacity=$(".stc-agent-sup-std-capacity").val();
                var reasonattribute=$("#stc-agent-sup-std-reasonattribite").val();
                var j_type = $('.stc-agent-sup-std-job-type').val();
                var j_varities = $('#stc-agent-sup-std-job-varities').val();
                var created_by_se= $('#stc-agent-sup-std-created-by-select').val();
                var permit_no = $('.stc-agent-sup-std-permit-no').val();
                var creator_details= $('.stc-agent-sup-std-creator-details').val();
                var r_person = $('.stc-agent-sup-std-responsive-person').val();
                var reason = $('#stc-agent-sup-std-reason').val();
                var material_desc = $('#stc-agent-sup-std-material-desc').val();
                var manpower_req = $('#stc-agent-sup-std-manpower-req').val();
                var target_date = $('#stc-agent-sup-std-target-date').val();
                var tools_req=get_filter('stc-agent-sup-std-tools-req');
                var remarks = $('.stc-agent-sup-std-remarks').val();
                $.ajax({
                    url         : "nemesis/stc_std.php",
                    method      : "POST",
                    data        : {
                        stc_std_hit:1,
                        stc_location:location,
                        stc_dept:dept,
                        stc_area:area,
                        stc_eq_type:eq_type,
                        stc_eq_number:eq_number,
                        stc_eq_status:eq_status,
                        stc_j_type:j_type,
                        stc_j_plannning:j_plannning,
                        stc_qty:qty,
                        stc_capacity:capacity,
                        stc_reasonattribute:reasonattribute,
                        stc_j_varities:j_varities,
                        stc_created_by_se:created_by_se,
                        permit_no:permit_no,
                        stc_creator_details:creator_details,
                        stc_r_person:r_person,
                        stc_reason:reason,
                        stc_material_desc:material_desc,
                        stc_manpower_req:manpower_req,
                        stc_target_date:target_date,
                        stc_tools_req:tools_req,
                        stc_remarks:remarks
                    },
                    success     : function(response_std){
                        // console.log(response_std);
                        var response=response_std.trim();
                        if(response=="Status Down saved. Thankyou!!!"){
                            alert(response_std);
                            window.location.reload();
                        }else if(response=="Please login!!!"){
                            alert(response_std);
                            window.location.reload();
                        }else{
                            alert(response_std);
                        }
                    }
                });
            });

            // filter tbm checkbox
            function get_filter(class_name){
                var filter = [];
                $('.' + class_name + ':checked').each(function(){
                   filter.push($(this).val()); 
                });
                return filter;
            }

            function std_list_call(location_id){
                $.ajax({
                    url         : "nemesis/stc_std.php",
                    method      : "POST",
                    data        : {
                        stc_down_list_hit:1,
                        location_id:location_id
                    },
                    success     : function(response_sdl){
                        // console.log(response_sdl);
                        $('.stc-std-search-result').html(response_sdl);
                    }
                });
            }
            // call status down list
            $('body').delegate('.stc-std-list-show-hit', 'click', function(e){
                e.preventDefault();
                var location_id=$('#stc-agent-sup-std-location-find').val();
                std_list_call(location_id);
            });


            var sdl_id='';
            var status_id='';
            // change status
            $('body').delegate('.stc-set-to-complete', 'change', function(e){
                e.preventDefault();
                sdl_id=$(this).attr("id");
                status_id=$('option:selected', this).attr("status");
                var jobdonedetails='';
                if(status_id=="NA"){
                }else if(status_id==3){
                    $('.bd-std-jobdonedetails-modal-lg').modal('show');
                    sdl_id=$(this).attr("id");
                    status_id='3';                    
                }else{
                    $.ajax({
                        url         : "nemesis/stc_std.php",
                        method      : "POST",
                        data        : {
                            stc_status_change_hit:1,
                            status_id:status_id,
                            jobdonedetails:jobdonedetails,
                            sdl_id:sdl_id
                        },
                        success     : function(response_sdl){
                            alert(response_sdl);
                            var location_id=$('#stc-agent-sup-std-location-find').val();
                            std_list_call(location_id);
                        }
                    });
                }
            });

            // jobdone details save
            $('body').delegate('.stc-cust-ag-jobdonedetails-save', 'click', function(e){
                e.preventDefault();
                var jobdonedetails=$(".stc-cust-ag-jobdonedetails").val();
                var work_permit_no=$(".stc-agent-sup-std-upermit-no").val();
                if(jobdonedetails==""){
                    alert("Please provide job done details.");
                }else{
                    $.ajax({
                        url         : "nemesis/stc_std.php",
                        method      : "POST",
                        data        : {
                            stc_status_change_hit:1,
                            status_id:status_id,
                            jobdonedetails:jobdonedetails,
                            work_permit_no:work_permit_no,
                            sdl_id:sdl_id
                        },
                        success     : function(response_sdl){
                            alert(response_sdl);
                            $('.bd-std-jobdonedetails-modal-lg').modal('hide');
                            $(".stc-cust-ag-jobdonedetails").val('');
                            var location_id=$('#stc-agent-sup-std-location-find').val();
                            std_list_call(location_id);
                        }
                    });
                }
            });


            var pendsdl_id='';
            // call modal pending
            $('body').delegate('.stc-add-to-pending', 'click', function(e){
                e.preventDefault();
                pendsdl_id=$(this).attr("id");
                $('.bd-std-jobpendingdetails-modal-lg').modal('show');
            });

            // save pending modal show
            $('body').delegate('.stc-cust-ag-jobpendingdetails-save', 'click', function(e){
                e.preventDefault();
                var jobpendingdetails=$(".stc-cust-ag-jobpendingdetails").val();
                $.ajax({
                    url         : "nemesis/stc_std.php",
                    method      : "POST",
                    data        : {
                        stc_jobpending_save_hit:1,
                        jobpendingdetails:jobpendingdetails,
                        sdl_id:pendsdl_id
                    },
                    success     : function(response_sdl){
                        alert(response_sdl);
                        $('.bd-std-jobpendingdetails-modal-lg').modal('hide');
                        $(".stc-cust-ag-jobpendingdetails").val('');
                    }
                });
            });
        });
    </script>
</body>
</html>
<div class="modal fade bd-std-jobdonedetails-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Job Done Details</h5>
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
                                        <h5 class="card-title" align="center">Permit No :</h5>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <input type="text" class="form-control stc-agent-sup-std-upermit-no" placeholder="Enter Permit No">
                                    </div><br>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title" align="center">Job Done Details</h5>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <textarea class="form-control stc-cust-ag-jobdonedetails" placeholder="Enter Job Done Details"></textarea>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <button class="form-control btn btn-success stc-cust-ag-jobdonedetails-save">Save</button>
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
<div class="modal fade bd-std-jobpendingdetails-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Job Pending Details</h5>
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
                                        <h5 class="card-title" align="center">Job Pending Details</h5>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <textarea class="form-control stc-cust-ag-jobpendingdetails" placeholder="Enter Job Pending Details"></textarea>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <button class="form-control btn btn-success stc-cust-ag-jobpendingdetails-save">Save</button>
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