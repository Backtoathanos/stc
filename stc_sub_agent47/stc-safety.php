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
                                            <div class="card-body"><h5 class="card-title">VHL Vehicle Checklist comes here</h5>
                                                blah blah blah...
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
                                            <input type="checkbox" class="checklistcb" value="Hardhat"> Hardhat
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
                                                    <th class="text-center">Hardhat</th>
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