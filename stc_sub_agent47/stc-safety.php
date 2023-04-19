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
    <title>Safety - STC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="">
    <meta name="msapplication-tap-highlight" content="no">
    <!-- Latest compiled and minified CSS -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="./main.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
    <style>
      .fade:not(.show) {
        opacity: 10;
      }
    </style>
</head>
<body>
    <!-- ****************************************** -->
    <div class="modal fade" id="stc-sup-requisition-rece-modal" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">Reciever</h4>
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                  <p>Item Recieved</p>
                  <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        Enter Quantity Recieved 
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                       <input type="number" class="form-control stc-super-own-qnty-rec-text">
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <button class="btn btn-success stc-super-own-qnty-rec-btn" href="#">Save</button>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ****************************************** -->
    <div class="app-container app-theme-white body-tabs-shadow fixed-sidebar fixed-header">
        <?php include_once("header-nav.php");?>
        <div class="app-main">
            <?php include_once("sidebar-nav.php");?>
            <div class="app-main__outer">
                    <div class="app-main__inner">
                        <div class="app-page-title">
                            <div class="page-title-wrapper">
                                <div class="page-title-heading">
                                    <div>Safety</div>
                                </div>
                            </div>
                        </div>            
                        <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                            <li class="nav-item">
                                <a role="tab" class="nav-link active" id="tab-0" data-toggle="tab" href="#safetytab_0">
                                    <span>TBM</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-1" data-toggle="tab" href="#safetytab_1">
                                    <span>PPEC</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#safetytab_2">
                                    <span>PPEM</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-3" data-toggle="tab" href="#safetytab_3">
                                    <span>T&T Hand ToolList</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-4" data-toggle="tab" href="#safetytab_4">
                                    <span>T&T Equipment</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-5" data-toggle="tab" href="#safetytab_5">
                                    <span>T&T Power Tools & Callibaration</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-6" data-toggle="tab" href="#safetytab_6">
                                    <span>VHL - Vehicle Checklist</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-7" data-toggle="tab" href="#safetytab_7">
                                    <span>AUD 08 Hot Work</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-8" data-toggle="tab" href="#safetytab_8">
                                    <span>Near Miss reporting</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a role="tab" class="nav-link" id="tab-9" data-toggle="tab" href="#safetytab_9">
                                    <span>CAPA</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane tabs-animation fade show active" id="safetytab_0" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">Tool Box Meeting</h5>
                                                <a href="#" class="form-control btn btn-success add-tbt-box-meeting-modal">Add Tool Box Meeting</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-xl-12"> 
                                        <div class="main-card mb-3 card">
                                            <div class="card-body">
                                                <table class="mb-0 table table-hover table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">Date.</th>
                                                            <th class="text-center">Time.</th>
                                                            <th class="text-center">Place.</th>
                                                            <th width="20%" class="text-center">Image.</th>
                                                            <th class="text-center">Created By.</th>
                                                            <th width="10%" class="text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="stc-safety-tbm-res-table">
                                                        <tr>
                                                            <td>Loading...</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane tabs-animation fade" id="safetytab_1" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">Personal protective equipment checklist comes here</h5>
                                                blah blah blah...
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane tabs-animation fade" id="safetytab_2" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">Personal protective equipment mapping comes here</h5>
                                                blah blah blah...
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane tabs-animation fade" id="safetytab_3" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">Tools & Tackles Hand ToolList comes here</h5>
                                                blah blah blah...
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane tabs-animation fade" id="safetytab_4" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">Tools & Tasckles Equipment comes here</h5>
                                                blah blah blah...
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane tabs-animation fade" id="safetytab_5" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">Tools & Tackles Power Tools & Callibaration comes here</h5>
                                                blah blah blah...
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane tabs-animation fade" id="safetytab_6" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">VHL Vehicle Checklist</h5>
                                                <a href="#" class="form-control btn btn-success add-vhl-vehicle-checklist-modal">Add VHL Vehicle Checklist</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-xl-12"> 
                                        <div class="main-card mb-3 card">
                                            <div class="card-body">
                                                <table class="mb-0 table table-hover table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">Date.</th>
                                                            <th class="text-center">Vehicle Description</th>
                                                            <th class="text-center">Vehicle Registration No.</th>
                                                            <th width="20%" class="text-center">Date of Inspection.</th>
                                                            <th class="text-center">Drivers Name.</th>
                                                            <th width="10%" class="text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="stc-safety-vhl-res-table">
                                                        <tr>
                                                            <td>Loading...</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane tabs-animation fade" id="safetytab_7" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">AUD 08 Hot Work comes here</h5>
                                                blah blah blah...
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane tabs-animation fade" id="safetytab_8" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">Near Miss Reporting comes here</h5>
                                                blah blah blah...
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane tabs-animation fade" id="safetytab_9" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">Corrective & Preventing Action comes here</h5>
                                                blah blah blah...
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
            
            // call tbm
            call_tbm();
            function call_tbm(){
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_calltbm:1},
                    success     : function(response_tbm){
                        $('.stc-safety-tbm-res-table').html(response_tbm);
                    }
                });
            }

            function call_tbm_fields(){
                var stc_tbm_no=$('.stc-tbm-no').val();
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_calltbmfields:1,stc_tbm_no:stc_tbm_no},
                    dataType    : "JSON",
                    success     : function(response_tbm){
                        // console.log(response_tbm);
                        $('#stc-tbtm-date').val(response_tbm.tbm.stc_safetytbm_date);
                        $('#stc-tbtm-time').val(response_tbm.tbm.stc_safetytbm_time);
                        $('#stc-tbtm-place').val(response_tbm.tbm.stc_safetytbm_place);
                        $('#stc-tbtm-agendaofmeet').val(response_tbm.tbm.stc_safetytbm_agendaofmeet);
                        $('#stc-tbtm-pointtone').val(response_tbm.tbm.stc_safetytbm_ptone);
                        $('#stc-tbtm-pointtwo').val(response_tbm.tbm.stc_safetytbm_pttwo);
                        $('#stc-tbtm-pointthree').val(response_tbm.tbm.stc_safetytbm_ptthree);
                        $('#stc-tbtm-pointfour').val(response_tbm.tbm.stc_safetytbm_ptfour);
                        $('#stc-tbtm-pointfive').val(response_tbm.tbm.stc_safetytbm_ptfive);
                        $('#stc-tbtm-pointsix').val(response_tbm.tbm.stc_safetytbm_ptsix);
                        $('#stc-tbtm-suggestionsio').val(response_tbm.tbm.stc_safetytbm_remarks);
                        $('#stc-tbtm-entryname').val(response_tbm.tbm.stc_safetytbm_entry_name);
                        $('#stc-tbtm-designation').val(response_tbm.tbm.stc_safetytbm_designation);
                        $('#stc-tbtm-gatepassno').val(response_tbm.tbm.stc_safetytbm_gatepass_no);
                        var tbm_entry=response_tbm.tbm_gateentry;
                        var tbm_entry_out='';
                        if(tbm_entry.length>0){
                            var sl=0;
                            for(var i=0; i<tbm_entry.length; i++){
                                sl++;
                                tbm_entry_out+='<tr><td>' + sl + '</td><td>' + tbm_entry[i].stc_safetytbm_gateentry_workpermitno + '</td><td>' + tbm_entry[i].stc_safetytbm_gateentry_shift + '</td><td>' + tbm_entry[i].stc_safetytbm_gateentry_time + '</td><td>' + tbm_entry[i].stc_safetytbm_gateentry_supeng_name + '</td></tr>';
                            }
                        }else{
                            tbm_entry_out+='<tr><td>Empty record</td></td>';
                        }
                        $('.stc-safety-gentry-show-table').html(tbm_entry_out);

                        var tbm_responsibilities=response_tbm.tbm_responsibility;
                        var tbm_responsibilities_out='';
                        if(tbm_responsibilities.length>0){
                            var sl=0;
                            for(var i=0; i<tbm_responsibilities.length; i++){
                                sl++;
                                tbm_responsibilities_out+='<tr><td>' + sl + '</td><td>' + tbm_responsibilities[i].stc_safetytbm_responsibilities_item + '</td><td>' + tbm_responsibilities[i].stc_safetytbm_responsibilities_responsibilities + '</td><td>' + tbm_responsibilities[i].stc_safetytbm_responsibilities_targetdate + '</td></tr>';
                            }
                        }else{
                            tbm_responsibilities_out+='<tr><td>Empty record</td></td>';
                        }
                        $('.stc-tbtm-res-show-table').html(tbm_responsibilities_out);

                        var tbm_ppe_checklist=response_tbm.tbm_ppe_checklist;
                        var tbm_ppe_checklist_out='';
                        if(tbm_ppe_checklist.length>0){
                            var sl=0;
                            for(var i=0; i<tbm_ppe_checklist.length; i++){                                
                                sl++;
                                var hardhat='X';
                                var SafetyGoggle='X';
                                var NoseMask='X';
                                var HandGloves='X';
                                var FR_Jacket_Trouser='X';
                                var SafetyShoes='X';
                                var earplug='X';
                                var legguard='X';
                                var PhysicallyfitforDuty='X';
                                if(tbm_ppe_checklist[i].stc_safetytbm_checklist_hardhat==1){
                                    hardhat='<i class="fa fa-check" aria-hidden="true"></i>';
                                }if(tbm_ppe_checklist[i].stc_safetytbm_checklist_SafetyGoggle==1){
                                    SafetyGoggle='<i class="fa fa-check" aria-hidden="true"></i>';
                                }if(tbm_ppe_checklist[i].stc_safetytbm_checklist_NoseMask==1){
                                    NoseMask='<i class="fa fa-check" aria-hidden="true"></i>';
                                }if(tbm_ppe_checklist[i].stc_safetytbm_checklist_HandGloves==1){
                                    HandGloves='<i class="fa fa-check" aria-hidden="true"></i>';
                                }if(tbm_ppe_checklist[i].stc_safetytbm_checklist_FR_Jacket_Trouser==1){
                                    FR_Jacket_Trouser='<i class="fa fa-check" aria-hidden="true"></i>';
                                }if(tbm_ppe_checklist[i].stc_safetytbm_checklist_SafetyShoes==1){
                                    SafetyShoes='<i class="fa fa-check" aria-hidden="true"></i>';
                                }if(tbm_ppe_checklist[i].stc_safetytbm_checklist_earplug==1){
                                    earplug='<i class="fa fa-check" aria-hidden="true"></i>';
                                }if(tbm_ppe_checklist[i].stc_safetytbm_checklist_legguard==1){
                                    legguard='<i class="fa fa-check" aria-hidden="true"></i>';
                                }if(tbm_ppe_checklist[i].stc_safetytbm_checklist_PhysicallyfitforDuty==1){
                                    PhysicallyfitforDuty='<i class="fa fa-check" aria-hidden="true"></i>';
                                }
                                tbm_ppe_checklist_out+='<tr><td>' + sl + '</td><td>' + tbm_ppe_checklist[i].stc_safetytbm_checklist_empname + '</td><td class="text-center">' + hardhat + '</td><td class="text-center">' + SafetyGoggle + '</td><td class="text-center">' + NoseMask + '</td><td class="text-center">' + HandGloves + '</td><td class="text-center">' + FR_Jacket_Trouser + '</td><td class="text-center">' + SafetyShoes + '</td><td class="text-center">' + earplug + '</td><td class="text-center">' + legguard + '</td><td class="text-center">' + PhysicallyfitforDuty + '</td><td class="text-center"></td></tr>';
                            }
                        }else{
                            tbm_ppe_checklist_out+='<tr><td>Empty record</td></td>';
                        }
                        $('.stc-tbtm-ppe-checklist-show-table').html(tbm_ppe_checklist_out);
                    }
                });
            }

            // $('.bd-tbt-box-meeting-modal-lg').modal('show');
            $('body').delegate('.add-tbt-box-meeting-modal', 'click', function(e){
                e.preventDefault();
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_addtbm:1},
                    success     : function(response_tbm){
                        var response=response_tbm.trim();
                        if(response=="same"){
                            alert("Can not add new tool box meeting at same day, you can only edit & delete on same day.");
                        }else{
                            call_tbm();
                            $('.bd-tbt-box-meeting-modal-lg').modal('show');
                            $('.stc-tbm-no').val(response);
                            call_tbm_fields();
                        }
                    }
                });
            });

            // delete tbm row
            $('body').delegate('.stc-safetytbm-delete', 'click', function(e){
                e.preventDefault();
                var tbm_id=$(this).attr("id");
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_deletetbm:1,tbm_id:tbm_id},
                    success     : function(response_tbm){
                        var response=response_tbm.trim();
                        if(response=="success"){
                            call_tbm();
                        }else{
                            alert("Something went wrong, please check and try again.");
                        }
                    }
                });
            });

            // update
            $('body').delegate('.stc-safetytbm-edit', 'click', function(e){
                e.preventDefault();
                var tbm_id=$(this).attr("id");
                $('.stc-tbm-no').val(tbm_id);
                $('.bd-tbt-box-meeting-modal-lg').modal('show');
                call_tbm_fields();
            });

            // save tbm
            function save_tbm(){
                var stc_tbm_no=$('.stc-tbm-no').val();
                var stc_date=$('#stc-tbtm-date').val();
                var stc_time=$('#stc-tbtm-time').val();
                var stc_place=$('#stc-tbtm-place').val();
                var stc_agendaofmeeting=$('#stc-tbtm-agendaofmeet').val();
                var stc_pointtone=$('#stc-tbtm-pointtone').val();
                var stc_pointtwo=$('#stc-tbtm-pointtwo').val();
                var stc_pointthree=$('#stc-tbtm-pointthree').val();
                var stc_pointfour=$('#stc-tbtm-pointfour').val();
                var stc_pointfive=$('#stc-tbtm-pointfive').val();
                var stc_pointsix=$('#stc-tbtm-pointsix').val();
                var stc_suggesionsio=$('#stc-tbtm-suggestionsio').val();
                var stc_entryname=$('#stc-tbtm-entryname').val();
                var stc_desgination=$('#stc-tbtm-designation').val();
                var stc_gatepass=$('#stc-tbtm-gatepassno').val();
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {
                        stc_safety_updatetbm:1,
                        stc_tbm_no:stc_tbm_no,
                        stc_date:stc_date,
                        stc_time:stc_time,
                        stc_place:stc_place,
                        stc_agendaofmeeting:stc_agendaofmeeting,
                        stc_pointtone:stc_pointtone,
                        stc_pointtwo:stc_pointtwo,
                        stc_pointthree:stc_pointthree,
                        stc_pointfour:stc_pointfour,
                        stc_pointfive:stc_pointfive,
                        stc_pointsix:stc_pointsix,
                        stc_suggesionsio:stc_suggesionsio,
                        stc_entryname:stc_entryname,
                        stc_desgination:stc_desgination,
                        stc_gatepass:stc_gatepass
                    },
                    success     : function(response_tbm){
                        // console.log(response_tbm);
                        var response=response_tbm.trim();
                        if(response=="success"){

                        }else{
                            alert("Something went wrong, please check and try again.");
                        }
                    }
                });
            }

            $('body').delegate('#stc-tbtm-date', 'focusout', function(e){
                e.preventDefault();
                save_tbm();
                $('.saved-popup').remove();
                $(this).after('<p class="saved-popup text-success">Record Saved</p>');
            });

            $('body').delegate('#stc-tbtm-time', 'focusout', function(e){
                e.preventDefault();
                save_tbm();
                $('.saved-popup').remove();
                $(this).after('<p class="saved-popup text-success">Record Saved</p>');
            });

            $('body').delegate('#stc-tbtm-place', 'focusout', function(e){
                e.preventDefault();
                save_tbm();
                $('.saved-popup').remove();
                $(this).after('<p class="saved-popup text-success">Record Saved</p>');
            });

            $('body').delegate('#stc-tbtm-agendaofmeet', 'focusout', function(e){
                e.preventDefault();
                save_tbm();
                $('.saved-popup').remove();
                $(this).after('<p class="saved-popup text-success">Record Saved</p>');
            });

            $('body').delegate('#stc-tbtm-pointtone', 'focusout', function(e){
                e.preventDefault();
                save_tbm();
                $('.saved-popup').remove();
                $(this).after('<p class="saved-popup text-success">Record Saved</p>');
            });

            $('body').delegate('#stc-tbtm-pointtwo', 'focusout', function(e){
                e.preventDefault();
                save_tbm();
                $('.saved-popup').remove();
                $(this).after('<p class="saved-popup text-success">Record Saved</p>');
            });

            $('body').delegate('#stc-tbtm-pointthree', 'focusout', function(e){
                e.preventDefault();
                save_tbm();
                $('.saved-popup').remove();
                $(this).after('<p class="saved-popup text-success">Record Saved</p>');
            });

            $('body').delegate('#stc-tbtm-pointfour', 'focusout', function(e){
                e.preventDefault();
                save_tbm();
                $('.saved-popup').remove();
                $(this).after('<p class="saved-popup text-success">Record Saved</p>');
            });

            $('body').delegate('#stc-tbtm-pointfive', 'focusout', function(e){
                e.preventDefault();
                save_tbm();
                $('.saved-popup').remove();
                $(this).after('<p class="saved-popup text-success">Record Saved</p>');
            });

            $('body').delegate('#stc-tbtm-pointsix', 'focusout', function(e){
                e.preventDefault();
                save_tbm();
                $('.saved-popup').remove();
                $(this).after('<p class="saved-popup text-success">Record Saved</p>');
            });

            $('body').delegate('#stc-tbtm-suggestionsio', 'focusout', function(e){
                e.preventDefault();
                save_tbm();
                $('.saved-popup').remove();
                $(this).after('<p class="saved-popup text-success">Record Saved</p>');
            });

            $('body').delegate('#stc-tbtm-designation', 'focusout', function(e){
                e.preventDefault();
                save_tbm();
                $('.saved-popup').remove();
                $(this).after('<p class="saved-popup text-success">Record Saved</p>');
            });

            $('body').delegate('#stc-tbtm-entryname', 'focusout', function(e){
                e.preventDefault();
                save_tbm();
                $('.saved-popup').remove();
                $(this).after('<p class="saved-popup text-success">Record Saved</p>');
            });

            $('body').delegate('#stc-tbtm-gatepassno', 'focusout', function(e){
                e.preventDefault();
                save_tbm();
                $('.saved-popup').remove();
                $(this).after('<p class="saved-popup text-success">Record Saved</p>');
            });

            // save entry time
            $('body').delegate('.stc-tbtm-gentryadd', 'click', function(e){
                e.preventDefault();
                var stc_tbm_no=$('.stc-tbm-no').val();
                var work_permit_no=$('#stc-tbtm-gentryworkpermit-no').val();
                var shift=$('#stc-tbtm-gentryshift').val();
                var time=$('#stc-tbtm-gentrytime').val();
                var supervisor_name=$('#stc-tbtm-gentrysupengname').val();
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {
                        stc_safety_savetbmgateentry:1,
                        stc_tbm_no:stc_tbm_no,
                        stc_work_permit_no:work_permit_no,
                        stc_shift:shift,
                        stc_time:time,
                        stc_supervisor_name:supervisor_name
                    },
                    success     : function(response_tbm){
                        console.log(response_tbm);
                        var response=response_tbm.trim();
                        if(response=="success"){
                            alert("Record added.");
                            call_tbm_fields();
                        }else{
                            alert("Something went wrong, please check and try again.");
                        }
                    }
                });
            });

            // save responsibility time
            $('body').delegate('.stc-tbtm-res-add', 'click', function(e){
                e.preventDefault();
                var stc_tbm_no=$('.stc-tbm-no').val();
                var tbm_res_date=$('#stc-tbtm-res-targetdate').val();
                var tbm_res_responsibility=$('#stc-tbtm-res-responsiblity').val();
                var tbm_res_item=$('#stc-tbtm-res-item').val();
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {
                        stc_safety_savetbmresponsibility:1,
                        stc_tbm_no:stc_tbm_no,
                        stc_res_date:tbm_res_date,
                        stc_res_responsibility:tbm_res_responsibility,
                        stc_res_item:tbm_res_item
                    },
                    success     : function(response_tbm){
                        // console.log(response_tbm);
                        var response=response_tbm.trim();
                        if(response=="success"){
                            alert("Record added.");
                            call_tbm_fields();
                        }else{
                            alert("Something went wrong, please check and try again.");
                        }
                    }
                });
            });

            // image upload
            $(document).on('submit', '#safety-image-upload-form', function() {
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "post",
                    data        : new FormData(this),
                    contentType : false,
                    cache       : false,
                    processData : false,
                    success     : function(data){
                        // console.log(data);
                        data=data.trim();
                        if(data=="success"){
                            alert("Image added!!");
                            call_tbm();
                            $('#safety-image-upload-form')[0].reset();
                        }
                    }
                });
                return false;
            });

            // checklist save
            $('body').delegate('.stc-tbtm-ppe-checklistadd', 'click', function(e){
                e.preventDefault();
                var stc_tbm_no=$('.stc-tbm-no').val();
                var emp_name=$('#stc-tbtm-ppe-checklistempname').val();
                var filter = get_filter('checklistcb');
                 $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {
                        stc_safety_savetbmppechecklist:1,
                        stc_tbm_no:stc_tbm_no,
                        stc_emp_name:emp_name,
                        stc_filter:filter
                    },
                    success     : function(response_tbm){
                        // console.log(response_tbm);
                        var response=response_tbm.trim();
                        if(response=="success"){
                            alert("Record added.");
                            call_tbm_fields();
                            $('.stc-tbm-no').val('');
                            $('#stc-tbtm-ppe-checklistempname').val('');
                            $('.checklistcb').prop('checked', false);
                        }else{
                            alert("Something went wrong, please check and try again.");
                        }
                    }
                });
                // console.log(filter);
            });

            // filter tbm checkbox
            function get_filter(class_name){
                var filter = [];
                $('.' + class_name + ':checked').each(function(){
                   filter.push($(this).val()); 
                });
                return filter;
            }

        });
    </script>
    <script>
        $(document).ready(function(){            
            // call vhl
            call_vhl();
            function call_vhl(){
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_callvhl:1},
                    success     : function(response_tbm){
                        $('.stc-safety-vhl-res-table').html(response_tbm);
                    }
                });
            }

            // delete vhl            
            $('body').delegate('.stc-safetyvhl-delete', 'click', function(e){
                e.preventDefault();
                var vhl_id=$(this).attr("id");
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_deletevhl:1,vhl_id:vhl_id},
                    success     : function(response_tbm){
                        var response=response_tbm.trim();
                        if(response=="success"){
                            alert("Record Removed!!!");
                            call_vhl();
                        }else{
                            alert("Something went wrong, please check and try again.");
                        }
                    }
                });
            });

            function call_vhl_fields(){
                var stc_vhl_no=$('.stc-vhl-no').val();
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_callvhlfields:1,stc_vhl_no:stc_vhl_no},
                    dataType    : "JSON",
                    success     : function(response_vhl){
                        // console.log(response_vhl);
                        $('#stc-vhl-vehicle-description').val(response_vhl.stc_safetyvehicle_desc);
                        $('#stc-vhl-reg-name').val(response_vhl.stc_safetyvehicle_reg_no);
                        $('#stc-vhl-dateofinspection').val(response_vhl.stc_safetyvehicle_dateofinspection);
                        $('#stc-vhl-driversname').val(response_vhl.stc_safetyvehicle_driversname);
                        $('#stc-vhl-person-undertaking-vehinspection').val(response_vhl.stc_safetyvehicle_personundertaking);
                        $('#stc-vhl-signature').val(response_vhl.stc_safetyvehicle_signature);
                        $('#stc-vhl-vehiclefaults-reported').val(response_vhl.stc_safetyvehicle_faultsreported);
                        $('#stc-vhl-ol').val(response_vhl.stc_safetyvehicle_oil_level);
                        $('#stc-vhl-bfl').val(response_vhl.stc_safetyvehicle_brakefluidlevel);
                        $('#stc-vhl-wl').val(response_vhl.stc_safetyvehicle_waterlevel);
                        $('#stc-vhl-ws').val(response_vhl.stc_safetyvehicle_windscreen);
                        $('#stc-vhl-as').val(response_vhl.stc_safetyvehicle_adjustseat);
                        $('#stc-vhl-sb').val(response_vhl.stc_safetyvehicle_seatbelts);
                        $('#stc-vhl-pb').val(response_vhl.stc_safetyvehicle_parking_brake);
                        $('#stc-vhl-fb').val(response_vhl.stc_safetyvehicle_footbrake);
                        $('#stc-vhl-pb1').val(response_vhl.stc_safetyvehicle_passengerbrake);
                        $('#stc-vhl-cgs').val(response_vhl.stc_safetyvehicle_clutchgearshift);
                        $('#stc-vhl-mc').val(response_vhl.stc_safetyvehicle_mirrorsclean);
                        $('#stc-vhl-dl').val(response_vhl.stc_safetyvehicle_doorlock);
                        $('#stc-vhl-stg').val(response_vhl.stc_safetyvehicle_steering);
                        $('#stc-vhl-lc').val(response_vhl.stc_safetyvehicle_lightsclearance);
                        $('#stc-vhl-dashp').val(response_vhl.stc_safetyvehicle_dashcontrolpanel);
                        $('#stc-vhl-horn').val(response_vhl.stc_safetyvehicle_horn);
                        $('#stc-vhl-alarm').val(response_vhl.stc_safetyvehicle_alarm);
                        $('#stc-vhl-hyds').val(response_vhl.stc_safetyvehicle_hydraulicsystem);
                        $('#stc-vhl-spart').val(response_vhl.stc_safetyvehicle_sparetyre);
                        $('#stc-vhl-towbar').val(response_vhl.stc_safetyvehicle_towbar);
                        $('#stc-vhl-equip').val(response_vhl.stc_safetyvehicle_equipment);
                        $('#stc-vhl-fk').val(response_vhl.stc_safetyvehicle_firstaidkit);
                    }
                });
            }

            // save data
            $('body').delegate('.add-vhl-vehicle-checklist-modal', 'click', function(e){
                e.preventDefault();
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_addvhl:1},
                    success     : function(response_tbm){
                        // console.log(response_tbm);
                        var response=response_tbm.trim();
                        if(response=="same"){
                            alert("Can not add new tool box meeting at same day, you can only edit & delete on same day.");
                        }else{
                            call_vhl();
                            $('.bd-vhl-vehicle-checklist-modal-lg').modal('show');
                            $('.stc-vhl-no').val(response);
                            $("input[type=text]").val('');
                            $("select").val(0);
                            $("textarea").val('');
                            call_vhl_fields();
                        }
                    }
                });
            });

            // save vhl
            function save_vhl(){
                var stc_vhl_no=$('.stc-vhl-no').val();
                var stc_description=$('#stc-vhl-vehicle-description').val();
                var stc_reg_name=$('#stc-vhl-reg-name').val();
                var stc_dateofinspection=$('#stc-vhl-dateofinspection').val();
                var stc_driversname=$('#stc-vhl-driversname').val();
                var stc_ol=$('#stc-vhl-ol').val();
                var stc_bfl=$('#stc-vhl-bfl').val();
                var stc_wl=$('#stc-vhl-wl').val();
                var stc_ws=$('#stc-vhl-ws').val();
                var stc_as=$('#stc-vhl-as').val();
                var stc_sb=$('#stc-vhl-sb').val();
                var stc_pb=$('#stc-vhl-pb').val();
                var stc_fb=$('#stc-vhl-fb').val();
                var stc_pb1=$('#stc-vhl-pb1').val();
                var stc_cgs=$('#stc-vhl-cgs').val();
                var stc_mc=$('#stc-vhl-mc').val();
                var stc_dl=$('#stc-vhl-dl').val();
                var stc_stg=$('#stc-vhl-stg').val();
                var stc_lc=$('#stc-vhl-lc').val();
                var stc_dashp=$('#stc-vhl-dashp').val();
                var stc_horn=$('#stc-vhl-horn').val();
                var stc_alarm=$('#stc-vhl-alarm').val();
                var stc_hyds=$('#stc-vhl-hyds').val();
                var stc_spart=$('#stc-vhl-spart').val();
                var stc_towbar=$('#stc-vhl-towbar').val();
                var stc_equip=$('#stc-vhl-equip').val();
                var stc_fk=$('#stc-vhl-fk').val();
                var stc_undertaking_vehic =$('#stc-vhl-person-undertaking-vehinspection').val();
                var stc_signature =$('#stc-vhl-signature').val();
                var stc_faultsreported =$('#stc-vhl-vehiclefaults-reported').val();
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {
                        stc_safety_updatevhl:1,
                        stc_vhl_no:stc_vhl_no,
                        stc_description:stc_description,
                        stc_reg_name:stc_reg_name,
                        stc_dateofinspection:stc_dateofinspection,
                        stc_driversname:stc_driversname,
                        stc_undertaking_vehic:stc_undertaking_vehic,
                        stc_signature:stc_signature,
                        stc_faultsreported:stc_faultsreported,
                        stc_ol:stc_ol,
                        stc_bfl:stc_bfl,
                        stc_wl:stc_wl,
                        stc_ws:stc_ws,
                        stc_as:stc_as,
                        stc_sb:stc_sb,
                        stc_pb:stc_pb,
                        stc_fb:stc_fb,
                        stc_pb1:stc_pb1,
                        stc_cgs:stc_cgs,
                        stc_mc:stc_mc,
                        stc_dl:stc_dl,
                        stc_stg:stc_stg,
                        stc_lc:stc_lc,
                        stc_dashp:stc_dashp,
                        stc_horn:stc_horn,
                        stc_alarm:stc_alarm,
                        stc_hyds:stc_hyds,
                        stc_spart:stc_spart,
                        stc_towbar:stc_towbar,
                        stc_equip:stc_equip,
                        stc_fk:stc_fk
                    },
                    success     : function(response_tbm){
                        // console.log(response_tbm);
                        var response=response_tbm.trim();
                        if(response=="success"){

                        }else{
                            alert("Something went wrong, please check and try again.");
                        }
                    }
                });
            }

            // update
            $('body').delegate('.stc-safetyvhl-edit', 'click', function(e){
                e.preventDefault();
                var vhl_id=$(this).attr("id");
                $('.stc-vhl-no').val(vhl_id);
                $('.bd-vhl-vehicle-checklist-modal-lg').modal('show');
                call_vhl()
                call_vhl_fields();
            });

            // update vhl
            $('body').delegate('.stc-vhl-fields', 'focusout', function(e){
                e.preventDefault();
                save_vhl();
                $('.saved-popup').remove();
                $(this).after('<p class="saved-popup text-success">Record Saved</p>');
            });

            // update vhl list
            $('body').delegate('.stc-vhl-drop-fields', 'change', function(){
                save_vhl();
                $('.saved-popup').remove();
                $(this).after('<p class="saved-popup text-success">Record Saved</p>');
            });
        });
    </script>
</body>
</html>
<div class="modal fade bd-tbt-box-meeting-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">TBT Box Meeting</h5>
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
                                        <h5 class="card-title" align="center">TBT Box Meeting</h5>
                                        <label>* fields are automatic saved when you switch</label>
                                        <input type="hidden" class="stc-tbm-no">
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Date *</h5>
                                        <div class="position-relative form-group">
                                            <input type="date" class="form-control" id="stc-tbtm-date">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Time *</h5>
                                        <div class="position-relative form-group">
                                            <input type="time" class="form-control" id="stc-tbtm-time">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Place *</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" id="stc-tbtm-place" placeholder="Enter Place">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xl-3">
                                        <h5 class="card-title">Work permit no</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" id="stc-tbtm-gentryworkpermit-no" placeholder="Enter Work permit no">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-12 col-xl-2">
                                        <h5 class="card-title">Shift</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" id="stc-tbtm-gentryshift" placeholder="Enter Shift">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-12 col-xl-2">
                                        <h5 class="card-title">Time</h5>
                                        <div class="position-relative form-group">
                                            <input type="time" class="form-control" id="stc-tbtm-gentrytime">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xl-3">
                                        <h5 class="card-title">Supervisor/Engineers Name</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" id="stc-tbtm-gentrysupengname" placeholder="Enter Supervisor/Engineers Name">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-12 col-xl-2">
                                        <h5 class="card-title">.</h5>
                                        <div class="position-relative form-group">
                                            <a href="#" class="btn btn-success form-control stc-tbtm-gentryadd">Add</a>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <table class="table table-bordered table-responsive">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Sl no.</th>
                                                    <th class="text-center">Work permit no.</th>
                                                    <th class="text-center">Shift</th>
                                                    <th class="text-center">Time</th>
                                                    <th class="text-center">Supervisor /Engineer name</th>
                                                    <th class="text-center">Signature</th>
                                                </tr>
                                            </thead>
                                            <tbody class="stc-safety-gentry-show-table">
                                                <tr>
                                                    <td colspan="6">Empty record</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">Agenda of the meeting/ बैठक की कॅरिय सूचि : *</h5>
                                        <div class="position-relative form-group">
                                            <textarea class="form-control" id="stc-tbtm-agendaofmeet" placeholder="Enter agenda of the meeting"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">List of topics: (Strike out (X) the topic that has not been discussed./ विषय की सूचि : ( जिस विषय की चर्चा नहीं हुई है उसे काट ( X ) दें ! : *</h5>
                                    </div>                                    
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">1. Review and discuss the topic of the previous meeting/ पिछले मीटिंग के विषय का रिव्यु करें तथा उसकी चर्चा करें :</h5>
                                        <div class="position-relative form-group">
                                            <textarea class="form-control" id="stc-tbtm-pointtone" placeholder="Enter text"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">2. Ask the employee about the near miss incident or accident of the past day and note down/ कर्मचारी से बीतें दिन के नियर मिस घटना या दुर्घटना के बारे में पूछें तथा नोट करें : *</h5>
                                        <div class="position-relative form-group">
                                            <textarea class="form-control" id="stc-tbtm-pointtwo" placeholder="Enter text"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">3. Inform the employee about Green Strip, Red Strip, Orange Strip and Safety Alert Circular: Inform them about hazards and safe working conditions/ कर्मचारी को ग्रीन स्ट्रिप , रेड स्ट्रिप , ऑरेंज स्ट्रिप और सेफ्टी अलर्ट सकयुरलर की जानकारी दें :
                                        उन्हें खतरे तथा कार्यानुसार सुरक्षित स्थिति के बारे में बतायें : *</h5>
                                        <div class="position-relative form-group">
                                            <textarea class="form-control" id="stc-tbtm-pointthree" placeholder="Enter text"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">4. Give information about the SOP which is related to that day's work and note down/ SOP जो उस दिन के कार्य से सम्बंधित हो उसके बारे में जानकारी दे तथा नोट करें : *</h5>
                                        <div class="position-relative form-group">
                                            <textarea class="form-control" id="stc-tbtm-pointfour" placeholder="Enter text"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">5. Remind employees of their personal responsibilities: proper PPE, housekeeping, tools and tackles, power equipment condition, 6 direction hazards, special requirements like work permit, no drinking, Safe behavior, Team work spirit, No dangerous architecture etc./ कर्मचारी को उनके व्यक्तिगत जिम्मेदारियां की याद दिलाये : उचित पीपीई , हाउसकीपिंग , टूल्स एंड टाकल्स , बिजली उपकरण की स्थिति, ६ दिशा के खतरे, विशेष ज़रूरत जैसे वर्क परमिट , मधपान निषेद ,सुरक्छित वयवहार ,टीम वर्क की भावना , कोई खतरनाक वास्तु इत्यादि : *</h5>
                                        <div class="position-relative form-group">
                                            <textarea class="form-control" id="stc-tbtm-pointfive" placeholder="Enter text"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">6. Share security written messages with employees/ सुरक्षा लिखित सन्देश कर्मचारी के साथ साझा करें : *</h5>
                                        <div class="position-relative form-group">
                                            <textarea class="form-control" id="stc-tbtm-pointsix" placeholder="Enter text"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">7. Note the views raised by the employee and supervisor and fix responsibilities/ कर्मचारी तथा सुपरवाइजर दुवारा उठाये गए विचार को नोट करे तथा जिम्मेदारियां तय करें : *</h5>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Item</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" id="stc-tbtm-res-item" placeholder="Enter Item">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xl-3">
                                        <h5 class="card-title">Responsibility</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" id="stc-tbtm-res-responsiblity" placeholder="Enter Responsibility">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xl-3">
                                        <h5 class="card-title">Target Date</h5>
                                        <div class="position-relative form-group">
                                            <input type="date" class="form-control" id="stc-tbtm-res-targetdate">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-12 col-xl-2">
                                        <h5 class="card-title">.</h5>
                                        <div class="position-relative form-group">
                                            <a href="#" class="btn btn-success form-control stc-tbtm-res-add">Add</a>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <table class="table table-bordered table-responsive">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Sl no.</th>
                                                    <th class="text-center">Item</th>
                                                    <th class="text-center">Responsibility</th>
                                                    <th class="text-center">Target Date</th>
                                                </tr>
                                            </thead>
                                            <tbody class="stc-tbtm-res-show-table">
                                                <tr>
                                                    <td colspan="6">Empty record</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">Daily PPE and Fitness Checklist</h5>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xl-3">
                                        <h5 class="card-title">Employee's Name</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" id="stc-tbtm-ppe-checklistempname" placeholder="Enter Employees Name">
                                        </div>
                                    </div>                                    
                                    <div class="col-md-7 col-sm-12 col-xl-7">
                                        <div class="position-relative form-group">
                                            <input type="checkbox" class="checklistcb" value="Hardhat"> Helmet
                                            <input type="checkbox" class="checklistcb" value="Nose Mask"> Nose Mask
                                            <input type="checkbox" class="checklistcb" value="Safety Goggle"> Safety Goggle
                                            <input type="checkbox" class="checklistcb" value="Hand Gloves"> Hand Gloves
                                            <input type="checkbox" class="checklistcb" value="FR-Jacket/Trouser"> FR-Jacket/Trouser
                                            <input type="checkbox" class="checklistcb" value="Safety Shoes"> Safety Shoes
                                            <input type="checkbox" class="checklistcb" value="Earplug"> Earplug
                                            <input type="checkbox" class="checklistcb" value="Leg Guard"> Leg Guard
                                            <input type="checkbox" class="checklistcb" value="Physically fit for duty"> Physically fit for duty
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-12 col-xl-2">
                                        <a href="#" class="form-control stc-tbtm-ppe-checklistadd btn btn-success">Add</a>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <table class="table table-bordered table-responsive">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">Sl no.</th>
                                                    <th class="text-center">Employee’s Name</th>
                                                    <th class="text-center">Helmet</th>
                                                    <th class="text-center">Safety Goggle</th>
                                                    <th class="text-center">Nose Mask</th>
                                                    <th class="text-center">Hand Gloves</th>
                                                    <th class="text-center">FR-Jacket/Trouser</th>
                                                    <th class="text-center">Safety Shoes</th>
                                                    <th class="text-center">Earplug</th>
                                                    <th class="text-center">Leg Guard</th>
                                                    <th class="text-center">Physically fit for duty</th>
                                                    <th class="text-center">Emp. Signature</th>
                                                </tr>
                                            </thead>
                                            <tbody class="stc-tbtm-ppe-checklist-show-table">
                                                <tr>
                                                    <td colspan="6">Empty record</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">Any Suggestions for SIO/IO: *</h5>
                                        <div class="position-relative form-group">
                                            <textarea class="form-control" id="stc-tbtm-suggestionsio" placeholder="Enter text"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Name. *</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" id="stc-tbtm-entryname" placeholder="Enter Name.">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Designation *</h5>
                                        <div class="position-relative form-group">
                                            <select class="form-control" id="stc-tbtm-designation">
                                                <option>Site Incharge</option>
                                                <option>Manager</option>
                                                <option selected>Supervisor</option>
                                                <option>Technician</option>
                                                <option>Safety Supervisor</option>
                                                <option>Coordinator</option>
                                            </select>
                                            <!-- <input type="text" class="form-control" id="stc-tbtm-designation" placeholder="Enter Designation"> -->
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">GP/P No. *</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" id="stc-tbtm-gatepassno" placeholder="Enter GP/P No.">
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
<div class="modal fade bd-vhl-vehicle-checklist-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">VHL Vehicle Checklist</h5>
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
                                        <h5 class="card-title" align="center">VEHICLE INSPECTION</h5>
                                        <label>* fields are automatic saved when you switch</label>
                                        <input type="hidden" class="stc-vhl-no">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">Vehicle description</h5>
                                        <div class="position-relative form-group">
                                            <textarea class="form-control stc-vhl-fields" id="stc-vhl-vehicle-description" placeholder="Enter Vehicle description"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Vehicle Registration number</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control stc-vhl-fields" id="stc-vhl-reg-name" placeholder="Enter Vehicle Registration number">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Date of inspection</h5>
                                        <div class="position-relative form-group">
                                            <input type="date" class="form-control stc-vhl-fields" id="stc-vhl-dateofinspection">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Driver’s name</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control stc-vhl-fields" id="stc-vhl-driversname" placeholder="Enter Driver's Name">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <p>If you dont have a data then move to next field *</p>
                                        <div class="position-relative form-group">
                                            <table class="table table-hover table-bordered ">
                                                <thead>
                                                    <tr>
                                                        <td class="card-title">What should I check before operating the vehicle</td>
                                                        <td class="card-title">Yes / No</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Oil level</td>
                                                        <td>
                                                            <select class="form-select stc-vhl-drop-fields" id="stc-vhl-ol">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Brake fluid level</td>
                                                        <td>
                                                            <select class="form-select stc-vhl-drop-fields" id="stc-vhl-bfl">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Water level</td>
                                                        <td>
                                                            <select class="form-select stc-vhl-drop-fields" id="stc-vhl-wl">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Windscreen washer level</td>
                                                        <td>
                                                            <select class="form-select stc-vhl-drop-fields" id="stc-vhl-ws">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Adjust seat and controls</td>
                                                        <td>
                                                            <select class="form-select stc-vhl-drop-fields" id="stc-vhl-as">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Seat belts – check for operation (all)</td>
                                                        <td>
                                                            <select class="form-select stc-vhl-drop-fields" id="stc-vhl-sb">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Parking brake – hold against slight acceleration</td>
                                                        <td>
                                                            <select class="form-select stc-vhl-drop-fields" id="stc-vhl-pb">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Foot brake – holds, stops vehicle smoothly</td>
                                                        <td>
                                                            <select class="form-select stc-vhl-drop-fields" id="stc-vhl-fb">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Passenger brake for Driving lessons</td>
                                                        <td>
                                                            <select class="form-select stc-vhl-drop-fields" id="stc-vhl-pb1">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Clutch and gearshift – shifts smoothly without jumping or jerking</td>
                                                        <td>
                                                            <select class="form-select stc-vhl-drop-fields" id="stc-vhl-cgs">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Mirrors clean and adjusted</td>
                                                        <td>
                                                            <select class="form-select stc-vhl-drop-fields" id="stc-vhl-mc">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Doors and door locks operate correctly</td>
                                                        <td>
                                                            <select class="form-select stc-vhl-drop-fields" id="stc-vhl-dl">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Steering – moves smoothly</td>
                                                        <td>
                                                            <select class="form-select stc-vhl-drop-fields" id="stc-vhl-stg">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Lights – clearance, headlights, tail, license plate, brake, indicator turn signals & alarm.</td>
                                                        <td>
                                                            <select class="form-select stc-vhl-drop-fields" id="stc-vhl-lc">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Dash control panel – all lights and gauges are operational</td>
                                                        <td>
                                                            <select class="form-select stc-vhl-drop-fields" id="stc-vhl-dashp">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Horn</td>
                                                        <td>
                                                            <select class="form-select stc-vhl-drop-fields" id="stc-vhl-horn">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Vehicle reverse alarm</td>
                                                        <td>
                                                            <select class="form-select stc-vhl-drop-fields" id="stc-vhl-alarm">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Hydraulic systems – no evidence of leaks and systems operate smoothly</td>
                                                        <td>
                                                            <select class="form-select stc-vhl-drop-fields" id="stc-vhl-hyds">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Check spare tyre</td>
                                                        <td>
                                                            <select class="form-select stc-vhl-drop-fields" id="stc-vhl-spart">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Check tow bar (where fitted) </td>
                                                        <td>
                                                            <select class="form-select stc-vhl-drop-fields" id="stc-vhl-towbar">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Emergency equipment</td>
                                                        <td>
                                                            <select class="form-select stc-vhl-drop-fields" id="stc-vhl-equip">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>First aid kit</td>
                                                        <td>
                                                            <select class="form-select stc-vhl-drop-fields" id="stc-vhl-fk">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-5 col-sm-12 col-xl-5">
                                        <h5 class="card-title">Name of Person undertaking vehicle inspection.</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control stc-vhl-fields" id="stc-vhl-person-undertaking-vehinspection" placeholder="Enter Name of Person undertaking vehicle inspection.">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xl-3">
                                        <h5 class="card-title">Signature</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control stc-vhl-fields" id="stc-vhl-signature" placeholder="Enter Signature">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Vehicle faults to be reported immediately</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control stc-vhl-fields" id="stc-vhl-vehiclefaults-reported" placeholder="Enter Vehicle faults to be reported immediately">
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