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
                                            <div class="card-body"><h5 class="card-title">Personal protective equipment checklist</h5>
                                                <a href="#" class="form-control btn btn-success add-ppec-modal">Add Personal protective equipment checklist</a>
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
                                                            <th class="text-center">Site Name</th>
                                                            <th class="text-center">Supervisor Name</th>
                                                            <th width="10%" class="text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="stc-safety-ppec-res-table">
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
                            <div class="tab-pane tabs-animation fade" id="safetytab_2" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">PERSONAL PROTECTIVE EQUIPMENT MAPPING</h5>
                                                <a href="#" class="form-control btn btn-success add-ppem-modal">Add PERSONAL PROTECTIVE EQUIPMENT MAPPING</a>
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
                                                            <th class="text-center">Site Name</th>
                                                            <th class="text-center">Supervisor Name</th>
                                                            <th width="10%" class="text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="stc-safety-ppem-res-table">
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
                            <div class="tab-pane tabs-animation fade" id="safetytab_3" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">Tools & Tackles Hand ToolList</h5>
                                                <a href="#" class="form-control btn btn-success add-toolist-modal">Add Tools & Tackles Hand ToolList</a>
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
                                                            <th class="text-center">Site Name</th>
                                                            <th class="text-center">Supervisor Name</th>
                                                            <th width="10%" class="text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="stc-safety-toollist-res-table">
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
                            <div class="tab-pane tabs-animation fade" id="safetytab_4" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">Tools & Tasckles Equipment comes here</h5>
                                                <a href="#" class="form-control btn btn-success add-toolelist-modal">Add Tools & Tackles Equipment</a>
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
                                                            <th class="text-center">Site Name</th>
                                                            <th class="text-center">Supervisor Name</th>
                                                            <th width="10%" class="text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="stc-safety-toolelist-res-table">
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
                                            <div class="card-body"><h5 class="card-title">Hot Work</h5>
                                                <a href="#" class="form-control btn btn-success add-hotwork-modal">Add Hot Work</a>
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
                                                            <th class="text-center">WO No.</th>
                                                            <th class="text-center">Sitename.</th>
                                                            <th width="10%" class="text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="stc-safety-hotwork-res-table">
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
                            <div class="tab-pane tabs-animation fade" id="safetytab_8" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">Near Miss Reporting</h5>
                                                <a href="#" class="form-control btn btn-success add-near-miss-reporting-modal">Add Near Miss Reporting</a>
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
                                                            <th class="text-center">Image.</th>
                                                            <th width="10%" class="text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="stc-safety-nearmissreporting-res-table">
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
                            <div class="tab-pane tabs-animation fade" id="safetytab_9" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">Collective & Prevenive Action Report</h5>
                                                <a href="#" class="form-control btn btn-success add-capa-reporting-modal">Add Collective & Prevenive Action Report</a>
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
                                                            <th class="text-center">Sitename.</th>
                                                            <th class="text-center">Branch.</th>
                                                            <th class="text-center">Place.</th>
                                                            <th class="text-center">Created By.</th>
                                                            <th width="10%" class="text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="stc-safety-capa-res-table">
                                                        <tr>
                                                            <td>Search Here</td>
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
            
            $('body').delegate('.stc-tbm-gentrysupengname', 'change', function(e){
                var value = $(this).val();
                $('#stc-tbtm-gentrysupengname').val(value);
            });
            
            $('body').delegate('.stc-tbm-checklistitem', 'change', function(e){
                var value = $(this).val();
                $('#stc-tbtm-ppe-checklistempname').val(value);
                $('.checklistcb').prop('checked', true);
            });

            $('body').delegate('.stc-tbm-resresponsibility', 'change', function(e){
                var value = $(this).val();
                $('#stc-tbtm-res-responsiblity').val(value);
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

            // add image for tbm
            $('body').delegate('.stc-safety-tbm-image-show-btn', 'click', function() {
                var img_src=$(this).attr("data-src");
                $(this).after('<img src="' + img_src + '" style="width: 150px;position: relative;left: 15%;padding: 0;margin: 0;">');
                $(this).hide();
            });

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
                        // $('#stc-tbtm-location').val(response_tbm.tbm.stc_safetytbm_loc);
                        $('#stc-tbtm-location').val(response_tbm.tbm.stc_safetytbm_loc_id);
                        if(response_tbm.tbm.stc_safetytbm_loc_id=='0'){$('#stc-tbtm-location').val("NA");}
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
                        if (typeof tbm_entry !== 'undefined' && tbm_entry !== null && tbm_entry.length > 0) {
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
                        if (typeof tbm_responsibilities !== 'undefined' && tbm_responsibilities !== null && tbm_responsibilities.length > 0) {
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
                        if (typeof tbm_ppe_checklist !== 'undefined' && tbm_ppe_checklist !== null && tbm_ppe_checklist.length > 0) {
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

            
            var url = document.location.href;
            var qs = url.substring(url.indexOf('?') + 1).split('&');
            for(var i = 0, result = {}; i < qs.length; i++){
                qs[i] = qs[i].split('=');
                result[qs[i][0]] = decodeURIComponent(qs[i][1]);
            }
            
            var sdlno=result.sdl==undefined ? "0" : result.sdl;

            // $('.bd-tbt-box-meeting-modal-lg').modal('show');
            $('body').delegate('.add-tbt-box-meeting-modal', 'click', function(e){
                e.preventDefault();
                if(confirm("TBM will create automatically. Are you sure want to create TBM? ")){
                    $.ajax({
                        url         : "nemesis/stc_safety.php",
                        method      : "POST",
                        data        : {stc_safety_addtbm:1, sdlno:sdlno},
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
                }
            });

            // delete tbm row
            $('body').delegate('.stc-safetytbm-delete', 'click', function(e){
                e.preventDefault();
                var tbm_id=$(this).attr("id");
                if(confirm("Are you sure want to delete this TBM? ")){
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
                }
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
                var stc_loc=$('#stc-tbtm-location option:selected').text();
                var stc_loc_id=$('#stc-tbtm-location').val();
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
                        stc_loc:stc_loc,
                        stc_loc_id:stc_loc_id,
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
                        stc_gatepass:stc_gatepass,
                        sdlno:sdlno
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

            
            $('body').delegate('.stc-tbtm-textfields', 'focusout', function(e){
                e.preventDefault();
                var stc_loc_id=$('#stc-tbtm-location').val();
                if(stc_loc_id!="NA"){
                    save_tbm();
                    $('.saved-popup').remove();
                    $(this).after('<p class="saved-popup text-success">Record Saved</p>');
                    setTimeout(function() {
                        $('.saved-popup').toggle(700);
                    }, 1000);
                }else{
                    $('.saved-popup').remove();
                    alert("Select Location");
                    $('#stc-tbtm-location').after('<p class="saved-popup text-danger">Select Location</p>');
                    setTimeout(function() {
                        $('.saved-popup').toggle(700);
                    }, 5000);
                }
            });
            
            $('body').delegate('.stc-tbtm-dropdownfields', 'change', function(e){
                e.preventDefault();
                var stc_loc_id=$('#stc-tbtm-location').val();
                if(stc_loc_id!="NA"){
                    save_tbm();
                    $('.saved-popup').remove();
                    $(this).after('<p class="saved-popup text-success">Record Saved</p>');
                    setTimeout(function() {
                        $('.saved-popup').toggle(700);
                    }, 1000);
                }else{
                    $('.saved-popup').remove();
                    alert("Select Location");
                    $('#stc-tbtm-location').after('<p class="saved-popup text-danger">Select Location</p>');
                    setTimeout(function() {
                        $('.saved-popup').toggle(700);
                    }, 5000);
                }
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
                        // console.log(response_tbm);
                        var response=response_tbm.trim();
                        if(response=="success"){
                            alert("Record added.");
                            call_tbm_fields();
                            $('#stc-tbtm-gentryworkpermit-no').val('');
                            $('#stc-tbtm-gentryshift').val('');
                            $('#stc-tbtm-gentrytime').val('');
                            $('#stc-tbtm-gentrysupengname').val('');
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
                            $('#stc-tbtm-res-targetdate').val('');
                            $('#stc-tbtm-res-responsiblity').val('');
                            $('#stc-tbtm-res-item').val('');
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

            $('body').delegate('.checklistcb', 'click', function(e) {
                var checkbox = $(this);
                if($(this).val()!="Physically fit for duty"){
                
                    // If checkbox is unchecked
                    if(!checkbox.prop('checked')) {
                        // Append the select box below the checkbox
                        checkbox.after('<select class="form-control uncheckedselect"><option value="Damage">Damage</option><option selected value="NA">NA</option><option value="Not Issued">Not Issued</option><option value="Not wear">Not wear</option></select>');

                        // Optionally, append a label to indicate what the select box is for
                        $(checkbox).parent().find('.uncheckedselect').before('<label class="uncheckedselectlabel">Please specify reason:</label><br>');
                    }else{
                        $(checkbox).parent().find('.uncheckedselect').remove();
                        $(checkbox).parent().find('.uncheckedselectlabel').remove();
                    }
                }
            });

            $('body').delegate('.uncheckedselect', 'change', function(e) {
                $(this).parent().find('.uncheckedssize').remove();
                if(($(this).val()=="Damage" || $(this).val()=="Not Issued") && ($(this).parent().find('.checklistcb').val()=="Safety Shoes" || $(this).parent().find('.checklistcb').val()=="FR-Jacket/Trouser")){
                    $(this).after('<input type="text" class="form-control uncheckedssize">');
                }
            });

            // checklist save
            $('body').delegate('.stc-tbtm-ppe-checklistadd', 'click', function(e){
                e.preventDefault();
                var stc_tbm_no=$('.stc-tbm-no').val();
                var stc_tbm_location=$('#stc-tbtm-location').val();
                var emp_name=$.trim($(this).closest('tr').find('td:eq(0)').text());
                var filter = get_ppefilter($(this), 'checklistcb');
                var uncheckedppe = [], uncheckedppereason = [], uncheckedppesize = []; 
                if(stc_tbm_location=="NA"){
                    $('.saved-popup').remove();
                    alert("Select Location");
                    $('#stc-tbtm-location').after('<p class="saved-popup text-danger">Select Location</p>');
                    setTimeout(function() {
                        $('.saved-popup').toggle(700);
                    }, 5000);
                }else{
                    $(this).closest('tr').find('.checklistcb').each(function(){
                        if(!$(this).prop('checked')){ 
                            if($(this).parent().find('.uncheckedselect').val()=="Damage" || $(this).parent().find('.uncheckedselect').val()=="Not Issued"){
                                uncheckedppe.push($(this).val()); 
                                uncheckedppereason.push($(this).parent().find('.uncheckedselect').val());
                                if($(this).val()=="Safety Shoes" || $(this).val()=="FR-Jacket/Trouser"){
                                    uncheckedppesize.push($(this).parent().find('.uncheckedssize').val());
                                }else{
                                    uncheckedppesize.push(' ');
                                }
                            }
                        } 
                    });
                    if(filter.length>0){
                        var validation=0;
                        $('.attendance-ppe-table tr').each(function(){
                            var checkempname = $.trim($(this).find('td:eq(1)').text());
                            if(checkempname === emp_name){
                                validation = 1;
                                return false;
                            }
                        });
                        if(validation==0){
                            if(confirm("Are you sure?")){
                                $(this).closest('tr').remove();
                                $.ajax({
                                    url         : "nemesis/stc_safety.php",
                                    method      : "POST",
                                    data        : {
                                        stc_safety_savetbmppechecklist:1,
                                        stc_tbm_no:stc_tbm_no,
                                        stc_emp_name:emp_name,
                                        stc_filter:filter,
                                        stc_uncheckedppe:uncheckedppe,
                                        stc_uncheckedppereason:uncheckedppereason,
                                        stc_uncheckedppesize:uncheckedppesize
                                    },
                                    success     : function(response_tbm){
                                        // console.log(response_tbm);
                                        var response=response_tbm.trim();
                                        if(response=="success"){
                                            alert("Record added.");
                                            call_tbm_fields();
                                            $('#stc-tbtm-ppe-checklistempname').val('');
                                        }else{
                                            alert("Something went wrong, please check and try again.");
                                        }
                                    }
                                });
                            }
                        }else{
                            $(this).closest('tr').remove();
                            alert("Employee already exist.");
                        }
                    }else{
                        alert("Please check ppe!!!");
                    }
                }
            });

            // filter tbm checkbox
            function get_ppefilter($this, class_name){
                var filter = [];
                $this.closest('tr').find('.' + class_name + ':checked').each(function(){
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
                        $('#stc-vhl-other-remarks').val(response_vhl.stc_safetyvehicle_otherremakrs);
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
                            $(".bd-vhl-vehicle-checklist-modal-lg input[type=text]").val('');
                            $(".bd-vhl-vehicle-checklist-modal-lg select").val(0);
                            $(".bd-vhl-vehicle-checklist-modal-lg textarea").val('');
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
                var stc_other=$('#stc-vhl-other-remarks').val();
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
                        stc_other:stc_other,
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
                call_vhl();
                call_vhl_fields();
            });

            // update vhl
            $('body').delegate('.stc-vhl-fields', 'focusout', function(e){
                e.preventDefault();
                save_vhl();
                call_vhl();
                $('.saved-popup').remove();
                $(this).after('<p class="saved-popup text-success">Record Saved</p>');
            });

            // update vhl list
            $('body').delegate('.stc-vhl-drop-fields', 'change', function(){
                save_vhl();
                call_vhl();
                $('.saved-popup').remove();
                $(this).after('<p class="saved-popup text-success">Record Saved</p>');
            });
        });
    </script>
    <script>
        $(document).ready(function(){  
            // call ppem
            call_ppem();
            function call_ppem(){
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_callppem:1},
                    success     : function(response_tbm){
                        $('.stc-safety-ppem-res-table').html(response_tbm);
                    }
                });
            }

            // delete ppem            
            $('body').delegate('.stc-safetyppem-delete', 'click', function(e){
                e.preventDefault();
                var ppem_id=$(this).attr("id");
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_deleteppem:1,ppem_id:ppem_id},
                    success     : function(response_tbm){
                        var response=response_tbm.trim();
                        if(response=="success"){
                            alert("Record Removed!!!");
                            call_ppem();
                        }else{
                            alert("Something went wrong, please check and try again.");
                        }
                    }
                });
            });

            function call_ppem_fields(){
                var stc_ppem_no=$('.stc-ppem-no').val();
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_callppemfields:1,stc_ppem_no:stc_ppem_no},
                    dataType    : "JSON",
                    success     : function(response_vhl){
                        // console.log(response_vhl);
                        $('#stc-ppem-date').val(response_vhl.stc_safetyppem_date);
                        $('#stc-ppem-site-name').val(response_vhl.stc_safetyppem_site_name);
                        $('#stc-ppem-supervisorname').val(response_vhl.stc_safetyppem_supervisor_name);
                    }
                });
            }

            // save data
            $('body').delegate('.add-ppem-modal', 'click', function(e){
                e.preventDefault();
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_addppem:1},
                    success     : function(response_tbm){
                        // console.log(response_tbm);
                        var response=response_tbm.trim();
                        if(response=="same"){
                            alert("Can not add new ppem at same day, you can only edit & delete on same day.");
                        }else if(response=="reload"){
                            window.location.reload();
                        }else{
                            call_ppem();
                            $('.bd-ppem-modal-lg').modal('show');
                            $('.stc-ppem-no').val(response);
                            $(".bd-ppem-modal-lg input[type=text]").val('');
                            $(".bd-ppem-modal-lg select").val(0);
                            $(".bd-ppem-modal-lg textarea").val('');
                            // call_ppem_fields();
                        }
                    }
                });
            });
            
            // save ppem
            function save_ppem(){
                var stc_ppem_no=$('.stc-ppem-no').val();
                var stc_date=$('#stc-ppem-date').val();
                var stc_sitename=$('#stc-ppem-site-name').val();
                var stc_supervisorname=$('#stc-ppem-supervisorname').val();
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {
                        stc_safety_updateppem:1,
                        stc_ppem_no:stc_ppem_no,
                        stc_date:stc_date,
                        stc_sitename:stc_sitename,
                        stc_supervisorname:stc_supervisorname
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
            $('body').delegate('.stc-safetyppem-edit', 'click', function(e){
                e.preventDefault();
                var ppem_id=$(this).attr("id");
                $('.stc-ppem-no').val(ppem_id);
                $('.bd-ppem-modal-lg').modal('show');
                call_ppem();
                call_ppem_fields();
            });

            // update ppem list
            $('body').delegate('.stc-ppem-fields', 'focusout', function(){
                save_ppem();
                call_ppem();
                $('.saved-popup').remove();
                $(this).after('<p class="saved-popup text-success">Record Saved</p>');
            });

            // checklist save
            $('body').delegate('.stc-ppem-ppe-add', 'click', function(e){
                e.preventDefault();
                var stc_ppem_no=$('.stc-ppem-no').val();
                var sitentry=$('#stc-ppem-ppe-siteentry').val();
                var filter = get_filter('checklistppecb');
                if(sitentry!=''){
                     $.ajax({
                        url         : "nemesis/stc_safety.php",
                        method      : "POST",
                        data        : {
                            stc_safety_saveppemppe:1,
                            stc_ppem_no:stc_ppem_no,
                            stc_sitentry:sitentry,
                            stc_filter:filter
                        },
                        success     : function(response_tbm){
                            // console.log(response_tbm);
                            var response=response_tbm.trim();
                            if(response=="success"){
                                alert("Record added.");
                                call_ppem_fields();
                                $('#stc-ppem-ppe-siteentry').val('');
                                $('.checklistppecb').prop('checked', false);
                            }else{
                                alert("Something went wrong, please check and try again.");
                            }
                        }
                    });
                }else{
                    alert("Please enter employees name!!!");
                }
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
            // call nearmiss
            call_nearmiss();
            function call_nearmiss(){
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_callnearmiss:1},
                    success     : function(response_tbm){
                        $('.stc-safety-nearmissreporting-res-table').html(response_tbm);
                    }
                });
            }

            // delete nearmiss            
            $('body').delegate('.stc-safetynearmiss-delete', 'click', function(e){
                e.preventDefault();
                var nearmiss_id=$(this).attr("id");
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_deletenearmiss:1,nearmiss_id:nearmiss_id},
                    success     : function(response_tbm){
                        var response=response_tbm.trim();
                        if(response=="success"){
                            alert("Record Removed!!!");
                            call_nearmiss();
                        }else{
                            alert("Something went wrong, please check and try again.");
                        }
                    }
                });
            });

            function call_nearmiss_fields(){
                var stc_nearmiss_no=$('.stc-nearmiss-no').val();
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_callnearmissfields:1,stc_nearmiss_no:stc_nearmiss_no},
                    dataType    : "JSON",
                    success     : function(response_vhl){
                        // console.log(response_vhl);
                        $('#stc-nearmiss-orgunitoccur').val(response_vhl.stc_safetynearmiss_orgunitoccur);
                        $('#stc-nearmiss-incident-date').val(response_vhl.stc_safetynearmiss_incidenet_date);
                        $('#stc-nearmiss-incident-time').val(response_vhl.stc_safetynearmiss_time);
                        $('#stc-nearmiss-incident-location').val(response_vhl.stc_safetynearmiss_location);
                        $('#stc-nearmiss-incidentdesc').val(response_vhl.stc_safetynearmiss_incidenetdesc);
                        $('#stc-nearmiss-primarycause').val(response_vhl.stc_safetynearmiss_primarycause);
                        $('#stc-nearmiss-nameofemployee').val(response_vhl.stc_safetynearmiss_empname);
                        $('#stc-nearmiss-gpno').val(response_vhl.stc_safetynearmiss_gpno);
                        $('#stc-nearmiss-vendor').val(response_vhl.stc_safetynearmiss_vendor);
                        $('#stc-nearmiss-actiontaken').val(response_vhl.stc_safetynearmiss_actiontaken);
                        $('#stc-nearmiss-currdate').val(response_vhl.stc_safetynearmiss_currdate);
                        $('#stc-nearmiss-namesignature').val(response_vhl.stc_safetynearmiss_namesign);
                    }
                });
            }

            // save data
            $('body').delegate('.add-near-miss-reporting-modal', 'click', function(e){
                e.preventDefault();
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_addnearmiss:1},
                    success     : function(response_tbm){
                        // console.log(response_tbm);
                        var response=response_tbm.trim();
                        if(response=="same"){
                            alert("Can not add new near miss at same day, you can only edit & delete on same day.");
                        }else if(response=="reload"){
                            window.location.reload();
                        }else{
                            call_nearmiss();
                            $('.bd-nearmissreporting-modal-lg').modal('show');
                            $('.stc-ppem-no').val(response);
                            $(".bd-nearmissreporting-modal-lg input[type=text]").val('');
                            $(".bd-nearmissreporting-modal-lg select").val(0);
                            $(".bd-nearmissreporting-modal-lg textarea").val('');
                            // call_ppem_fields();
                        }
                    }
                });
            });

            // save nearmiss
            function save_nearmiss(){
                var stc_nearmiss_no=$('.stc-nearmiss-no').val();
                var stc_orgunitoccur=$('#stc-nearmiss-orgunitoccur').val();
                var stc_incidentdate=$('#stc-nearmiss-incident-date').val();
                var stc_incidenttime=$('#stc-nearmiss-incident-time').val();
                var stc_incidentlocation=$('#stc-nearmiss-incident-location').val();
                var stc_placeofincident=$('.stc-nearmiss-incident-place:checked').val();
                var stc_possibleconsq=$('.stc-nearmiss-possible-cons:checked').val();
                var stc_incidenetdesc=$('#stc-nearmiss-incidentdesc').val();
                var stc_primarycause=$('#stc-nearmiss-primarycause').val();
                var stc_empname=$('#stc-nearmiss-nameofemployee').val();
                var stc_gpno=$('#stc-nearmiss-gpno').val();
                var stc_vendor=$('#stc-nearmiss-vendor').val();
                var stc_actiontaken=$('#stc-nearmiss-actiontaken').val();
                var stc_currdate=$('#stc-nearmiss-currdate').val();
                var stc_namesign=$('#stc-nearmiss-namesignature').val();

                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {
                        stc_safety_updatenearmiss:1,
                        stc_nearmiss_no:stc_nearmiss_no,
                        stc_orgunitoccur:stc_orgunitoccur,
                        stc_incidentdate:stc_incidentdate,
                        stc_incidenttime:stc_incidenttime,
                        stc_incidentlocation:stc_incidentlocation,
                        stc_placeofincident:stc_placeofincident,
                        stc_possibleconsq:stc_possibleconsq,
                        stc_incidenetdesc:stc_incidenetdesc,
                        stc_primarycause:stc_primarycause,
                        stc_empname:stc_empname,
                        stc_gpno:stc_gpno,
                        stc_vendor:stc_vendor,
                        stc_actiontaken:stc_actiontaken,
                        stc_currdate:stc_currdate,
                        stc_namesign:stc_namesign
                    },
                    success     : function(response_tbm){
                        console.log(response_tbm);
                        var response=response_tbm.trim();
                        if(response=="success"){

                        }else{
                            alert("Something went wrong, please check and try again.");
                        }
                    }
                });
            }

            // update
            $('body').delegate('.stc-safetynearmiss-edit', 'click', function(e){
                e.preventDefault();
                var nearmiss_id=$(this).attr("id");
                $('.stc-nearmiss-no').val(nearmiss_id);
                $('.bd-nearmissreporting-modal-lg').modal('show');
                call_nearmiss();
                call_nearmiss_fields();
            });

            // update nearmiss list
            $('body').delegate('.stc-nearmiss-fields', 'focusout', function(){
                save_nearmiss();
                call_nearmiss();
                $('.saved-popup').remove();
                $(this).after('<p class="saved-popup text-success">Record Saved</p>');
            });

            // update nearmiss list
            $('body').delegate('.stc-nearmiss-fields-checked', 'click', function(){
                save_nearmiss();
                call_nearmiss();
                $('.saved-popup').remove();
                $(this).after('<p class="saved-popup text-success">Record Saved</p>');
            });

            // image upload
            $(document).on('submit', '#safety-nearmissimage-upload-form', function() {
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
                            call_nearmiss();
                            $('#safety-image-upload-form')[0].reset();
                        }
                    }
                });
                return false;
            });            
        });
    </script>
    <script>
        $(document).ready(function(){    
            // call hot work
            call_hotwork();
            function call_hotwork(){
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_callhotwork:1},
                    success     : function(response_tbm){
                        $('.stc-safety-hotwork-res-table').html(response_tbm);
                    }
                });
            }       

            // delete ppem            
            $('body').delegate('.stc-safetyhotwork-delete', 'click', function(e){
                e.preventDefault();
                var hotwork_id=$(this).attr("id");
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_deletehotwork:1,hotwork_id:hotwork_id},
                    success     : function(response_tbm){
                        var response=response_tbm.trim();
                        if(response=="success"){
                            alert("Record Removed!!!");
                            call_hotwork();
                        }else{
                            alert("Something went wrong, please check and try again.");
                        }
                    }
                });
            });  

            // save data
            $('body').delegate('.add-hotwork-modal', 'click', function(e){
                e.preventDefault();
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_addhotwork:1},
                    success     : function(response_tbm){
                        // console.log(response_tbm);
                        var response=response_tbm.trim();
                        if(response=="same"){
                            alert("Can not add new hot work at same day, you can only edit & delete on same day.");
                        }else if(response=="reload"){
                            window.location.reload();
                        }else{
                            call_hotwork();
                            $('.bd-hotwork-modal-lg').modal('show');
                            $('.stc-hotwork-no').val(response);
                            $(".bd-hotwork-modal-lg input[type=text]").val('');
                            $(".bd-hotwork-modal-lg select").val(0);
                            $(".bd-hotwork-modal-lg textarea").val('');
                            // call_ppem_fields();
                        }
                    }
                });
            });

            function call_hotwork_fields(){
                var stc_hotwork_no=$('.stc-hotwork-no').val();
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_callhotworkfields:1,stc_hotwork_no:stc_hotwork_no},
                    dataType    : "JSON",
                    success     : function(response_vhl){
                        // console.log(response_vhl);
                        $('#stc-nearmiss-orgunitoccur').val(response_vhl.stc_safetynearmiss_orgunitoccur);
                        $('#stc-hotwork-wono').val(response_vhl.stc_safetyhotwork_wono);
                        $('#stc-hotwork-sitename').val(response_vhl.stc_safetyhotwork_jobssitename);
                        $('#stc-hotwork-starting-date').val(response_vhl.stc_safetyhotwork_startingdate);
                        $('#stc-hotwork-make').val(response_vhl.stc_safetyhotwork_make);
                        $('#stc-hotwork-slno').val(response_vhl.stc_safetyhotwork_slno);
                        $('#stc-hotwork-capaei').val(response_vhl.stc_safetyhotwork_capaei);
                        $('#stc-hotwork-oosigwc').val(response_vhl.stc_safetyhotwork_oosigwc);
                        $('#stc-hotwork-vacawp').val(response_vhl.stc_safetyhotwork_vacawp);
                        $('#stc-hotwork-rwp').val(response_vhl.stc_safetyhotwork_rwp);
                        $('#stc-hotwork-tspt').val(response_vhl.stc_safetyhotwork_tspt);
                        $('#stc-hotwork-cfmtec').val(response_vhl.stc_safetyhotwork_cfmtec);
                        $('#stc-hotwork-geagde').val(response_vhl.stc_safetyhotwork_geagde);
                        $('#stc-hotwork-cfmteh').val(response_vhl.stc_safetyhotwork_cfmteh);
                        $('#stc-hotwork-eh').val(response_vhl.stc_safetyhotwork_eh);
                        $('#stc-hotwork-wmccanlc').val(response_vhl.stc_safetyhotwork_wmccanlc);
                        $('#stc-hotwork-wfr').val(response_vhl.stc_safetyhotwork_wfr);
                        $('#stc-hotwork-cfeaftws').val(response_vhl.stc_safetyhotwork_cfeaftws);
                        $('#stc-hotwork-scle').val(response_vhl.stc_safetyhotwork_scle);
                        $('#stc-hotwork-ctte').val(response_vhl.stc_safetyhotwork_ctte);
                        $('#stc-hotwork-wcopraipcwad').val(response_vhl.stc_safetyhotwork_wcopraipcwad);
                        $('#stc-hotwork-cltp').val(response_vhl.stc_safetyhotwork_cltp);
                        $('#stc-hotwork-wianep').val(response_vhl.stc_safetyhotwork_wianep);
                        $('#stc-hotwork-socuaptvs').val(response_vhl.stc_safetyhotwork_socuaptvs);
                        $('#stc-hotwork-recoslaecpfbutcw').val(response_vhl.stc_safetyhotwork_recoslaecpfbutcw);
                        $('#stc-hotwork-aowbcbh').val(response_vhl.stc_safetyhotwork_aowbcbh);
                        $('#stc-hotwork-acwoseow').val(response_vhl.stc_safetyhotwork_acwoseow);
                        $('#stc-hotwork-acpptpth').val(response_vhl.stc_safetyhotwork_acpptpth);
                    }
                });
            }

            // save hotwork
            function save_hotwork(){
                var stc_hotwork_no=$('.stc-hotwork-no').val();
                var stc_wono= $('#stc-hotwork-wono').val();
                var stc_sitename= $('#stc-hotwork-sitename').val();
                var stc_starting= $('#stc-hotwork-starting-date').val();
                var stc_make= $('#stc-hotwork-make').val();
                var stc_slno= $('#stc-hotwork-slno').val();
                var stc_capaei= $('#stc-hotwork-capaei').val();
                var stc_oosigwc= $('#stc-hotwork-oosigwc').val();
                var stc_vacawp= $('#stc-hotwork-vacawp').val();
                var stc_rwp= $('#stc-hotwork-rwp').val();
                var stc_tspt= $('#stc-hotwork-tspt').val();
                var stc_cfmtec= $('#stc-hotwork-cfmtec').val();
                var stc_geagde= $('#stc-hotwork-geagde').val();
                var stc_cfmteh= $('#stc-hotwork-cfmteh').val();
                var stc_eh= $('#stc-hotwork-eh').val();
                var stc_wmccanlc= $('#stc-hotwork-wmccanlc').val();
                var stc_wfr= $('#stc-hotwork-wfr').val();
                var stc_cfeaftws= $('#stc-hotwork-cfeaftws').val();
                var stc_scle= $('#stc-hotwork-scle').val();
                var stc_ctte= $('#stc-hotwork-ctte').val();
                var stc_wcopraipcwad= $('#stc-hotwork-wcopraipcwad').val();
                var stc_cltp= $('#stc-hotwork-cltp').val();
                var stc_wianep= $('#stc-hotwork-wianep').val();
                var stc_socuaptvs= $('#stc-hotwork-socuaptvs').val();
                var stc_recoslaecpfbutcw= $('#stc-hotwork-recoslaecpfbutcw').val();
                var stc_aowbcbh= $('#stc-hotwork-aowbcbh').val();
                var stc_acwoseow= $('#stc-hotwork-acwoseow').val();
                var stc_acpptpth= $('#stc-hotwork-acpptpth').val();

                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {
                        stc_safety_updatehotwork:1,
                        stc_hotwork_no:stc_hotwork_no,
                        stc_wono:stc_wono,
                        stc_sitename:stc_sitename,
                        stc_starting:stc_starting,
                        stc_make:stc_make,
                        stc_slno:stc_slno,
                        stc_capaei:stc_capaei,
                        stc_oosigwc:stc_oosigwc,
                        stc_vacawp:stc_vacawp,
                        stc_rwp:stc_rwp,
                        stc_tspt:stc_tspt,
                        stc_cfmtec:stc_cfmtec,
                        stc_geagde:stc_geagde,
                        stc_cfmteh:stc_cfmteh,
                        stc_eh:stc_eh,
                        stc_wmccanlc:stc_wmccanlc,
                        stc_wfr:stc_wfr,
                        stc_cfeaftws:stc_cfeaftws,
                        stc_scle:stc_scle,
                        stc_ctte:stc_ctte,
                        stc_wcopraipcwad:stc_wcopraipcwad,
                        stc_cltp:stc_cltp,
                        stc_wianep:stc_wianep,
                        stc_socuaptvs:stc_socuaptvs,
                        stc_recoslaecpfbutcw:stc_recoslaecpfbutcw,
                        stc_aowbcbh:stc_aowbcbh,
                        stc_acwoseow:stc_acwoseow,
                        stc_acpptpth:stc_acpptpth
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
            $('body').delegate('.stc-safetyhotwork-edit', 'click', function(e){
                e.preventDefault();
                var hotwork_id=$(this).attr("id");
                $('.stc-hotwork-no').val(hotwork_id);
                $('.bd-hotwork-modal-lg').modal('show');
                call_hotwork();
                call_hotwork_fields();
            });

            // update hotwork list
            $('body').delegate('.stc-hotwork-fields', 'focusout', function(){
                save_hotwork();
                call_hotwork();
                $('.saved-popup').remove();
                $(this).after('<p class="saved-popup text-success">Record Saved</p>');
            });

            $('body').delegate('.stc-hotwork-drop-fields', 'change', function(){
                save_hotwork();
                call_hotwork();
                $('.saved-popup').remove();
                $(this).after('<p class="saved-popup text-success">Record Saved</p>');
            });
        });
    </script>
    <script>
        $(document).ready(function(){    
            // call ppec
            call_ppec();
            function call_ppec(){
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_callppec:1},
                    success     : function(response_tbm){
                        $('.stc-safety-ppec-res-table').html(response_tbm);
                    }
                });
            }       

            // delete ppec            
            $('body').delegate('.stc-safetyppec-delete', 'click', function(e){
                e.preventDefault();
                var ppec_id=$(this).attr("id");
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_deleteppec:1,ppec_id:ppec_id},
                    success     : function(response_tbm){
                        var response=response_tbm.trim();
                        if(response=="success"){
                            alert("Record Removed!!!");
                            call_ppec();
                        }else{
                            alert("Something went wrong, please check and try again.");
                        }
                    }
                });
            });  

            function call_ppec_fields(){
                var stc_ppec_no=$('.stc-ppec-no').val();
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_callppecfields:1,stc_ppec_no:stc_ppec_no},
                    dataType    : "JSON",
                    success     : function(response_vhl){
                        // console.log(response_vhl);
                        $('#stc-ppec-wono').val(response_vhl.ppec.stc_safetyppec_wono);
                        $('#stc-ppec-sitename').val(response_vhl.ppec.stc_safetyppec_sitename);
                        $('#stc-ppec-check').val(response_vhl.ppec.stc_safetyppec_check);
                        $('#stc-ppec-sitesup').val(response_vhl.ppec.stc_safetyppec_sitesupervisor);
                        $('#stc-ppec-safetysup').val(response_vhl.ppec.stc_safetyppec_safetysupervisor);

                        var ppecppes=response_vhl.ppecppess;
                            
                        
                        var ppecppes_entry_out='<tr><td colspan="22" class="text-center">Empty record</td></td>';
                        if(ppecppes.length>0){
                            ppecppes_entry_out='';
                            var sl=0;
                            for(var i=0; i<ppecppes.length; i++){
                                sl++;
                                var helmet='X';
                                var shoes='X';
                                var hvjacket='X';
                                var safetygoggles='X';
                                var frjacket='X';
                                var earplug='X';
                                var cottongloves='X';
                                var nosemask='X';
                                var faceshieldcovid19='X';
                                var apron='X';
                                var faceshield='X';
                                var cuttinggoogles='X';
                                var leathergloves='X';
                                var legguard='X';
                                var handsleeve='X';
                                var safetyharness='X';
                                var rubbergloves='X';
                                var lubricationgloves='X';
                                var earmuff='X';
                                var singuard='X';
                                if(ppecppes[i].stc_safetyppec_ppes_helmet==1){
                                    hardhat='<i class="fa fa-check" aria-hidden="true"></i>';
                                }
                                if(ppecppes[i].stc_safetyppec_ppes_helmet==1){
                                    helmet='<i class="fa fa-check" aria-hidden="true"></i>';
                                }
                                if(ppecppes[i].stc_safetyppec_ppes_shoes==1){
                                    shoes='<i class="fa fa-check" aria-hidden="true"></i>';
                                }
                                if(ppecppes[i].stc_safetyppec_ppes_hvjacket==1){
                                    hvjacket='<i class="fa fa-check" aria-hidden="true"></i>';
                                }
                                if(ppecppes[i].stc_safetyppec_ppes_safetygoggles==1){
                                    safetygoggles='<i class="fa fa-check" aria-hidden="true"></i>';
                                }
                                if(ppecppes[i].stc_safetyppec_ppes_frjacket==1){
                                    frjacket='<i class="fa fa-check" aria-hidden="true"></i>';
                                }
                                if(ppecppes[i].stc_safetyppec_ppes_earplug==1){
                                    earplug='<i class="fa fa-check" aria-hidden="true"></i>';
                                }
                                if(ppecppes[i].stc_safetyppec_ppes_cottongloves==1){
                                    cottongloves='<i class="fa fa-check" aria-hidden="true"></i>';
                                }
                                if(ppecppes[i].stc_safetyppec_ppes_nosemask==1){
                                    nosemask='<i class="fa fa-check" aria-hidden="true"></i>';
                                }
                                if(ppecppes[i].stc_safetyppec_ppes_faceshieldcovid19==1){
                                    faceshieldcovid19='<i class="fa fa-check" aria-hidden="true"></i>';
                                }
                                if(ppecppes[i].stc_safetyppec_ppes_apron==1){
                                    apron='<i class="fa fa-check" aria-hidden="true"></i>';
                                }
                                if(ppecppes[i].stc_safetyppec_ppes_faceshield==1){
                                    faceshield='<i class="fa fa-check" aria-hidden="true"></i>';
                                }
                                if(ppecppes[i].stc_safetyppec_ppes_cuttinggoogles==1){
                                    cuttinggoogles='<i class="fa fa-check" aria-hidden="true"></i>';
                                }
                                if(ppecppes[i].stc_safetyppec_ppes_leathergloves==1){
                                    leathergloves='<i class="fa fa-check" aria-hidden="true"></i>';
                                }
                                if(ppecppes[i].stc_safetyppec_ppes_legguard==1){
                                    legguard='<i class="fa fa-check" aria-hidden="true"></i>';
                                }
                                if(ppecppes[i].stc_safetyppec_ppes_handsleeve==1){
                                    handsleeve='<i class="fa fa-check" aria-hidden="true"></i>';
                                }
                                if(ppecppes[i].stc_safetyppec_ppes_safetyharness==1){
                                    safetyharness='<i class="fa fa-check" aria-hidden="true"></i>';
                                }
                                if(ppecppes[i].stc_safetyppec_ppes_rubbergloves==1){
                                    rubbergloves='<i class="fa fa-check" aria-hidden="true"></i>';
                                }
                                if(ppecppes[i].stc_safetyppec_ppes_lubricationgloves==1){
                                    lubricationgloves='<i class="fa fa-check" aria-hidden="true"></i>';
                                }
                                if(ppecppes[i].stc_safetyppec_ppes_earmuff==1){
                                    earmuff='<i class="fa fa-check" aria-hidden="true"></i>';
                                }
                                if(ppecppes[i].stc_safetyppec_ppes_singuard==1){
                                    singuard='<i class="fa fa-check" aria-hidden="true"></i>';
                                }
                                ppecppes_entry_out+='<tr><td>' + ppecppes[i].stc_safetyppec_ppes_workmen + '</td><td>' + helmet + '</td><td>' + shoes + '</td><td>' + hvjacket + '</td><td>' + safetygoggles + '</td><td>' + frjacket + '</td><td>' + earplug + '</td><td>' + cottongloves + '</td><td>' + nosemask + '</td><td>' + faceshieldcovid19 + '</td><td>' + apron + '</td><td>' + faceshield + '</td><td>' + cuttinggoogles + '</td><td>' + leathergloves + '</td><td>' + legguard + '</td><td>' + handsleeve + '</td><td>' + safetyharness + '</td><td>' + rubbergloves + '</td><td>' + lubricationgloves + '</td><td>' + earmuff + '</td><td>' + singuard + '</td></tr>';
                            }
                        }
                        $('.stc-ppec-ppes-res').html(ppecppes_entry_out);
                    }
                });
            }

            // save data
            $('body').delegate('.add-ppec-modal', 'click', function(e){
                e.preventDefault();
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_addppec:1},
                    success     : function(response_tbm){
                        // console.log(response_tbm);
                        var response=response_tbm.trim();
                        if(response=="same"){
                            alert("Can not add new ppec at same day, you can only edit & delete on same day.");
                        }else if(response=="reload"){
                            window.location.reload();
                        }else{
                            call_ppec();
                            $('.bd-ppec-modal-lg').modal('show');
                            $('.stc-ppec-no').val(response);
                            $(".bd-ppec-modal-lg input[type=text]").val('');
                            $(".bd-ppec-modal-lg select").val(0);
                            $(".bd-ppec-modal-lg textarea").val('');
                            // call_ppem_fields();
                        }
                    }
                });
            });
            
            // save ppec
            function save_ppec(){
                var stc_ppec_no=$('.stc-ppec-no').val();
                var stc_ppec_wono=$('#stc-ppec-wono').val();
                var stc_ppec_sitename=$('#stc-ppec-sitename').val();
                var stc_ppec_check=$('#stc-ppec-check').val();
                var stc_ppec_sitesup=$('#stc-ppec-sitesup').val();
                var stc_ppec_safsup=$('#stc-ppec-safetysup').val();

                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {
                        stc_safety_updateppec:1,
                        stc_ppec_no:stc_ppec_no,
                        stc_ppec_wono:stc_ppec_wono,
                        stc_ppec_sitename:stc_ppec_sitename,
                        stc_ppec_check:stc_ppec_check,
                        stc_ppec_sitesup:stc_ppec_sitesup,
                        stc_ppec_safsup:stc_ppec_safsup
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
            $('body').delegate('.stc-safetyppec-edit', 'click', function(e){
                e.preventDefault();
                var ppec_id=$(this).attr("id");
                $('.stc-ppec-no').val(ppec_id);
                $('.bd-ppec-modal-lg').modal('show');
                call_ppec();
                call_ppec_fields();
            });

            // update ppec list
            $('body').delegate('.stc-ppec-fields', 'focusout', function(){
                save_ppec();
                call_ppec();
                $('.saved-popup').remove();
                $(this).after('<p class="saved-popup text-success">Record Saved</p>');
            });

            $('body').delegate('.stc-ppec-drop-fields', 'change', function(){
                save_ppec();
                call_ppec();
                $('.saved-popup').remove();
                $(this).after('<p class="saved-popup text-success">Record Saved</p>');
            });

            // checklist save
            $('body').delegate('.stc-ppec-ppe-add', 'click', function(e){
                e.preventDefault();
                var stc_ppec_no=$('.stc-ppec-no').val();
                var workmen=$('#stc-ppec-ppe-workmen').val();
                var filter = get_filter('checklistppecb2');
                if(workmen!=''){
                     $.ajax({
                        url         : "nemesis/stc_safety.php",
                        method      : "POST",
                        data        : {
                            stc_safety_saveppecppe:1,
                            stc_ppec_no:stc_ppec_no,
                            stc_workmen:workmen,
                            stc_filter:filter
                        },
                        success     : function(response_tbm){
                            // console.log(response_tbm);
                            var response=response_tbm.trim();
                            if(response=="success"){
                                alert("Record added.");
                                call_ppec_fields();
                                $('#stc-ppec-ppe-workmen').val('');
                                $('.checklistppecb2').prop('checked', false);
                            }else{
                                alert("Something went wrong, please check and try again.");
                            }
                        }
                    });
                }else{
                    alert("Please enter name of workmen!!!");
                }
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
            // call toollist
            call_toollist();
            function call_toollist(){
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_calltoollist:1},
                    success     : function(response_tbm){
                        $('.stc-safety-toollist-res-table').html(response_tbm);
                    }
                });
            }

            // save data
            $('body').delegate('.add-toolist-modal', 'click', function(e){
                e.preventDefault();
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_addtoollist:1},
                    success     : function(response_tbm){
                        // console.log(response_tbm);
                        var response=response_tbm.trim();
                        if(response=="same"){
                            alert("Can not add new ppec at same day, you can only edit & delete on same day.");
                        }else if(response=="reload"){
                            window.location.reload();
                        }else{
                            call_toollist();s
                            $('.bd-ppec-modal-lg').modal('show');
                            $('.stc-toollist-no').val(response);
                            $(".bd-toollist-modal-lg input[type=text]").val('');
                            $(".bd-toollist-modal-lg select").val(0);
                            $(".bd-toollist-modal-lg textarea").val('');
                            // call_toollist_fields();
                        }
                    }
                });
            });
            // call toollist
            call_toolelist();
            function call_toolelist(){
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_calltoolelist:1},
                    success     : function(response_tbm){
                        $('.stc-safety-toolelist-res-table').html(response_tbm);
                    }
                });
            }

            // save data
            $('body').delegate('.add-toolelist-modal', 'click', function(e){
                e.preventDefault();
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_addtoolelist:1},
                    success     : function(response_tbm){
                        // console.log(response_tbm);
                        var response=response_tbm.trim();
                        if(response=="same"){
                            alert("Can not add new T&T Equipment at same day, you can only edit & delete on same day.");
                        }else if(response=="reload"){
                            window.location.reload();
                        }else{
                            call_toollist();
                            $('.bd-toolelist-modal-lg').modal('show');
                            $('.stc-toolelist-no').val(response);
                            $(".bd-toolelist-modal-lg input[type=text]").val('');
                            $(".bd-toolelist-modal-lg select").val(0);
                            $(".bd-toolelist-modal-lg textarea").val('');
                            // call_toollist_fields();
                        }
                    }
                });
            });

            // delete toollist            
            $('body').delegate('.stc-safetytoollist-delete', 'click', function(e){
                e.preventDefault();
                var toollist_id=$(this).attr("id");
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_deletetoollist:1,toollist_id:toollist_id},
                    success     : function(response_tbm){
                        var response=response_tbm.trim();
                        if(response=="success"){
                            alert("Record Removed!!!");
                            call_toollist();
                        }else{
                            alert("Something went wrong, please check and try again.");
                        }
                    }
                });
            });  

            // delete toolelist            
            $('body').delegate('.stc-safetytoolelist-delete', 'click', function(e){
                e.preventDefault();
                var toolelist_id=$(this).attr("id");
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_deletetoolelist:1,toolelist_id:toolelist_id},
                    success     : function(response_tbm){
                        var response=response_tbm.trim();
                        if(response=="success"){
                            alert("Record Removed!!!");
                            call_toolelist();
                        }else{
                            alert("Something went wrong, please check and try again.");
                        }
                    }
                });
            });  

            function call_toolelist_fields(){
                var stc_toolelist_no=$('.stc-toolelist-no').val();
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_calltoolelistfields:1,stc_toolelist_no:stc_toolelist_no},
                    dataType    : "JSON",
                    success     : function(response_vhl){
                        // console.log(response_vhl);
                        $('#stc-toolelist-wono').val(response_vhl.toollist.work_orderno);
                        $('#stc-toolelist-sitename').val(response_vhl.toollist.sitename);
                        $('#stc-toolelist-suptech').val(response_vhl.toollist.siteincharge);

                        var toolelistlist=response_vhl.toollistlist;
                            
                        
                        var toolelistlist_entry_out='<tr><td colspan="22" class="text-center">Empty record</td></td>';
                        if(toolelistlist.length>0){
                            toolelistlist_entry_out='';
                            var sl=0;
                            for(var i=0; i<toolelistlist.length; i++){
                                sl++;
                                toolelistlist_entry_out+='<tr><td>' + sl + '</td><td>' + toolelistlist[i].name + '</td><td class="text-right">' + toolelistlist[i].serial_no + '</td><td class="text-right">' + toolelistlist[i].safe_to_work + '</td><td class="text-right">' + toolelistlist[i].sup_engg_name + '</td><td class="text-right">' + toolelistlist[i].sign + '</td><td class="text-right">' + toolelistlist[i].ddate + '</td></tr>';
                            }
                        }
                        $('.stc-toolelist-list-res-table').html(toolelistlist_entry_out);
                    }
                });
            }

            // save toolelist
            function save_toolelist(){
                var stc_toolelist_no=$('.stc-toolelist-no').val();
                var stc_toolelistwono=$('#stc-toolelist-wono').val();
                var stc_toolelistsitename=$('#stc-toolelist-sitename').val();
                var stc_toolelistsuptech=$('#stc-toolelist-suptech').val();

                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {
                        stc_safety_updatetoolelist:1,
                        stc_toollist_no:stc_toolelist_no,
                        stc_toollistwono:stc_toolelistwono,
                        stc_toollistsitename:stc_toolelistsitename,
                        stc_toollistsuptech:stc_toolelistsuptech
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
            $('body').delegate('.stc-safetytoolelist-edit', 'click', function(e){
                e.preventDefault();
                var toolelist_id=$(this).attr("id");
                $('.stc-toolelist-no').val(toolelist_id);
                $('.bd-toolelist-modal-lg').modal('show');
                call_toolelist();
                call_toolelist_fields();
            });

            // update ppec list
            $('body').delegate('.stc-toolelist-fields', 'focusout', function(){
                save_toolelist();
                call_toolelist();
                $('.saved-popup').remove();
                $(this).after('<p class="saved-popup text-success">Record Saved</p>');
            });

            // checklist save
            $('body').delegate('#stc-toolelist-tooladdbtn', 'click', function(e){
                e.preventDefault();
                var stc_toollist_no=$('.stc-toolelist-no').val();
                var tooldesc=$('#stc-toolelist-tooldesc').val();
                var serial_no=$('#stc-toolelist-serial_no').val();
                var safetowork=$('#stc-toolelist-safetowork').val();
                var suprname=$('#stc-toolelist-suprname').val();
                var tooldate=$('#stc-toolelist-tooldate').val();
                if(tooldesc!=''){
                     $.ajax({
                        url         : "nemesis/stc_safety.php",
                        method      : "POST",
                        data        : {
                            stc_safety_savetooleliste:1,
                            stc_toollist_no:stc_toollist_no,
                            stc_tooldesc:tooldesc,
                            stc_serial_no:serial_no,
                            stc_safetowork:safetowork,
                            stc_suprname:suprname,
                            stc_tooldate:tooldate
                        },
                        success     : function(response_tbm){
                            // console.log(response_tbm);
                            var response=response_tbm.trim();
                            if(response=="success"){
                                alert("Record added.");
                                call_toolelist_fields();
                                $('#stc-toolelist-tooldesc').val('');
                                $('#stc-toolelist-serial_no').val('');
                                $('#stc-toolelist-safetowork').val('');
                                $('#stc-toolelist-suprname').val('');
                                $('#stc-toolelist-tooldate').val('');
                            }else{
                                alert("Something went wrong, please check and try again.");
                            }
                        }
                    });
                }else{
                    alert("Please enter name of workmen!!!");
                }
            });

            function call_toollist_fields(){
                var stc_toollist_no=$('.stc-toollist-no').val();
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_calltoollistfields:1,stc_toollist_no:stc_toollist_no},
                    dataType    : "JSON",
                    success     : function(response_vhl){
                        // console.log(response_vhl);
                        $('#stc-toollist-date').val(response_vhl.toollist.stc_safetytoolslist_date);
                        $('#stc-toollist-wono').val(response_vhl.toollist.stc_safetytoolslist_wono);
                        $('#stc-toollist-sitename').val(response_vhl.toollist.stc_safetytoolslist_sitename);
                        $('#stc-toollist-suptech').val(response_vhl.toollist.stc_safetytoolslist_supervisor);

                        var toollistlist=response_vhl.toollistlist;
                            
                        
                        var toollistlist_entry_out='<tr><td colspan="22" class="text-center">Empty record</td></td>';
                        if(toollistlist.length>0){
                            toollistlist_entry_out='';
                            var sl=0;
                            for(var i=0; i<toollistlist.length; i++){
                                sl++;
                                toollistlist_entry_out+='<tr><td>' + sl + '</td><td>' + toollistlist[i].stc_safetytoolslistitem_tooldesc + '</td><td class="text-right">' + toollistlist[i].stc_safetytoolslistitem_qty + '</td><td class="text-right">' + toollistlist[i].stc_safetytoolslistitem_inuse + '</td><td class="text-right">' + toollistlist[i].stc_safetytoolslistitem_repair + '</td><td class="text-right">' + toollistlist[i].stc_safetytoolslistitem_damage + '</td></tr>';
                            }
                        }
                        $('.stc-toollist-list-res-table').html(toollistlist_entry_out);
                    }
                });
            }

            // save toollist
            function save_toollist(){
                var stc_toollist_no=$('.stc-toollist-no').val();
                var stc_toollistdate=$('#stc-toollist-date').val();
                var stc_toollistwono=$('#stc-toollist-wono').val();
                var stc_toollistsitename=$('#stc-toollist-sitename').val();
                var stc_toollistsuptech=$('#stc-toollist-suptech').val();

                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {
                        stc_safety_updatetoollist:1,
                        stc_toollist_no:stc_toollist_no,
                        stc_toollistdate:stc_toollistdate,
                        stc_toollistwono:stc_toollistwono,
                        stc_toollistsitename:stc_toollistsitename,
                        stc_toollistsuptech:stc_toollistsuptech
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
            $('body').delegate('.stc-safetytoollist-edit', 'click', function(e){
                e.preventDefault();
                var toollist_id=$(this).attr("id");
                $('.stc-toollist-no').val(toollist_id);
                $('.bd-toollist-modal-lg').modal('show');
                call_toollist();
                call_toollist_fields();
            });

            // update ppec list
            $('body').delegate('.stc-toollist-fields', 'focusout', function(){
                save_toollist();
                call_toollist();
                $('.saved-popup').remove();
                $(this).after('<p class="saved-popup text-success">Record Saved</p>');
            });


            // checklist save
            $('body').delegate('#stc-toollist-tooladdbtn', 'click', function(e){
                e.preventDefault();
                var stc_toollist_no=$('.stc-toollist-no').val();
                var toolddesc=$('#stc-toollist-tooldesc').val();
                var toolqty=$('#stc-toollist-toolquantity').val();
                var toolinuse=$('#stc-toollist-toolinuse').val();
                var toolinrepair=$('#stc-toollist-toolinrepair').val();
                var tooldamaged=$('#stc-toollist-tooldamaged').val();
                if(toolddesc!=''){
                     $.ajax({
                        url         : "nemesis/stc_safety.php",
                        method      : "POST",
                        data        : {
                            stc_safety_savetoolliste:1,
                            stc_toollist_no:stc_toollist_no,
                            stc_toolddesc:toolddesc,
                            stc_toolqty:toolqty,
                            stc_toolinuse:toolinuse,
                            stc_toolinrepair:toolinrepair,
                            stc_tooldamaged:tooldamaged
                        },
                        success     : function(response_tbm){
                            // console.log(response_tbm);
                            var response=response_tbm.trim();
                            if(response=="success"){
                                alert("Record added.");
                                call_toollist_fields();
                                $('#stc-toollist-tooldesc').val('');
                                $('#stc-toollist-toolquantity').val('');
                                $('#stc-toollist-toolinuse').val('');
                                $('#stc-toollist-toolinrepair').val('');
                                $('#stc-toollist-tooldamaged').val('');
                            }else{
                                alert("Something went wrong, please check and try again.");
                            }
                        }
                    });
                }else{
                    alert("Please enter name of workmen!!!");
                }
                // console.log(filter);
            });

        });
    </script>
    <script>
        $(document).ready(function(){  
            // call nearmiss
            call_capa();
            function call_capa(){
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_callcapa:1},
                    success     : function(response_tbm){
                        $('.stc-safety-capa-res-table').html(response_tbm);
                    }
                });
            }

            // delete nearmiss            
            $('body').delegate('.stc-safetycapa-delete', 'click', function(e){
                e.preventDefault();
                var capa_id=$(this).attr("id");
                if(confirm("Are you sure want to remove?")){
                    $.ajax({
                        url         : "nemesis/stc_safety.php",
                        method      : "POST",
                        data        : {stc_safety_deletecapa:1,capa_id:capa_id},
                        success     : function(response_tbm){
                            var response=response_tbm.trim();
                            if(response=="success"){
                                alert("Record Removed!!!");
                                call_capa();
                            }else{
                                alert("Something went wrong, please check and try again.");
                            }
                        }
                    });
                }
            });

            function call_capa_fields(){
                var stc_capa_no=$('.stc-capa-no').val();
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_callcapafields:1,stc_capa_no:stc_capa_no},
                    dataType    : "JSON",
                    success     : function(response_vhl){
                        // console.log(response_vhl);
                        $('#stc-capa-location').val(response_vhl.loc_id);
                        $('#stc-capa-sitename').val(response_vhl.sitename);
                        $('#stc-capa-branch').val(response_vhl.branch);
                        $('#stc-capa-place').val(response_vhl.place);
                        $('#stc-capa-date').val(response_vhl.capa_date);
                        $('#stc-capa-persionobserved').val(response_vhl.person_observed);
                        $('#stc-capa-designation').val(response_vhl.designation_observed);
                        $('#stc-capa-nclocation').val(response_vhl.nclocation);
                        $('#stc-capa-obsdate').val(response_vhl.observe_date);
                        $('#stc-capa-tgtdate').val(response_vhl.tgtdate);
                        $('#stc-capa-severity').val(response_vhl.severity);
                        $('#stc-capa-nonconformanceobserved').val(response_vhl.nonconformanceobserved);
                        $('#stc-capa-res_personname').val(response_vhl.res_personname);
                        $('#stc-capa-res_persondesignation').val(response_vhl.res_persondesignation);
                        $('#stc-capa-res_personname2').val(response_vhl.res_personname2);
                        $('#stc-capa-res_persondesignation2').val(response_vhl.res_persondesignation2);
                        $('#stc-capa-res_personname3').val(response_vhl.res_personname3);
                        $('#stc-capa-res_persondesignation3').val(response_vhl.res_persondesignation3);
                        $('#stc-capa-rootcause').val(response_vhl.rootcause);
                        $('#stc-capa-corrective').val(response_vhl.corrective);
                        $('#stc-capa-preventive').val(response_vhl.preventive);
                        $('#stc-capa-compliancebysupengdate').val(response_vhl.compliancebysupengdate);
                        $('#stc-capa-compliancebysupengname').val(response_vhl.compliancebysupengname);
                        $('#stc-capa-reviewedbysodate').val(response_vhl.reviewedbysodate);
                        $('#stc-capa-reviewedbysoname').val(response_vhl.reviewedbysoname);
                        $('#stc-capa-reviewedbydirdate').val(response_vhl.reviewedbydirdate);
                        $('#stc-capa-reviewedbydirname').val(response_vhl.reviewedbydirname);
                    }
                });
            }

            // save data
            $('body').delegate('.add-capa-reporting-modal', 'click', function(e){
                e.preventDefault();
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {stc_safety_addcapa:1},
                    success     : function(response_tbm){
                        // console.log(response_tbm);
                        var response=response_tbm.trim();
                        if(response=="same"){
                            alert("Can not add new capa at same day, you can only edit & delete on same day.");
                        }else if(response=="reload"){
                            window.location.reload();
                        }else{
                            call_capa();
                            $('.bd-capa-modal-lg').modal('show');
                            $('.stc-capa-no').val(response);
                            $(".bd-capa-modal-lg input[type=text]").val('');
                            $(".bd-capa-modal-lg select").val(0);
                            $(".bd-capa-modal-lg textarea").val('');
                            call_capa_fields();
                        }
                    }
                });
            });

            // save capa
            function save_capa(){
                var stc_capa_no=$('.stc-capa-no').val();
                var location=$('#stc-capa-location').val();
                var sitename=$('#stc-capa-sitename').val();
                var place=$('#stc-capa-place').val();
                var branch=$('#stc-capa-branch').val();
                var date=$('#stc-capa-date').val();
                var persionobserved=$('#stc-capa-persionobserved').val();
                var designation=$('#stc-capa-designation').val();
                var nclocation=$('#stc-capa-nclocation').val();
                var obsdate=$('#stc-capa-obsdate').val();
                var tgtdate=$('#stc-capa-tgtdate').val();
                var severity=$('#stc-capa-severity').val();
                var nonconformanceobserved=$('#stc-capa-nonconformanceobserved').val();
                var res_personname=$('#stc-capa-res_personname').val();
                var res_persondesignation=$('#stc-capa-res_persondesignation').val();
                var res_personname2=$('#stc-capa-res_personname2').val();
                var res_persondesignation2=$('#stc-capa-res_persondesignation2').val();
                var res_personname3=$('#stc-capa-res_personname3').val();
                var res_persondesignation3=$('#stc-capa-res_persondesignation3').val();
                var rootcause=$('#stc-capa-rootcause').val();
                var corrective=$('#stc-capa-corrective').val();
                var preventive=$('#stc-capa-preventive').val();
                var compliancebysupengdate=$('#stc-capa-compliancebysupengdate').val();
                var compliancebysupengname=$('#stc-capa-compliancebysupengname').val();
                var reviewedbysodate=$('#stc-capa-reviewedbysodate').val();
                var reviewedbysoname=$('#stc-capa-reviewedbysoname').val();
                var reviewedbydirdate=$('#stc-capa-reviewedbydirdate').val();
                var reviewedbydirname=$('#stc-capa-reviewedbydirname').val();
                
                $.ajax({
                    url         : "nemesis/stc_safety.php",
                    method      : "POST",
                    data        : {
                        stc_safety_updatecapa:1,
                        stc_capa_no:stc_capa_no,
                        location:location,
                        sitename:sitename,
                        place:place,
                        branch:branch,
                        date:date,
                        persionobserved:persionobserved,
                        designation:designation,
                        nclocation:nclocation,
                        obsdate:obsdate,
                        tgtdate:tgtdate,
                        severity:severity,
                        nonconformanceobserved:nonconformanceobserved,
                        res_personname:res_personname,
                        res_persondesignation:res_persondesignation,
                        res_personname2:res_personname2,
                        res_persondesignation2:res_persondesignation2,
                        res_personname3:res_personname3,
                        res_persondesignation3:res_persondesignation3,
                        rootcause:rootcause,
                        corrective:corrective,
                        preventive:preventive,
                        compliancebysupengdate:compliancebysupengdate,
                        compliancebysupengname:compliancebysupengname,
                        reviewedbysodate:reviewedbysodate,
                        reviewedbysoname:reviewedbysoname,
                        reviewedbydirdate:reviewedbydirdate,
                        reviewedbydirname:reviewedbydirname
                    },
                    success     : function(response_tbm){
                        // console.log(response_tbm);
                        var response=response_tbm.trim();
                    }
                });
            }

            // update
            $('body').delegate('.stc-safetycapa-edit', 'click', function(e){
                e.preventDefault();
                var capa_id=$(this).attr("id");
                $('.stc-capa-no').val(capa_id);
                $('.bd-capa-modal-lg').modal('show');
                call_capa_fields();
            });

            // update capa list
            $('body').delegate('.stc-capa-fields', 'focusout', function(){
                save_capa();
                call_capa();
                $('.saved-popup').remove();
                $(this).after('<p class="saved-popup text-success">Record Saved</p>');
            });
            // update capa list
            $('body').delegate('.stc-capa-dropdownfields', 'change', function(){
                save_capa();
                call_capa();
                $('.saved-popup').remove();
                $(this).after('<p class="saved-popup text-success">Record Saved</p>');
            });

            // image upload
            $(document).on('submit', '#safety-capaimagebefore-upload-form', function() {
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
                            call_nearmiss();
                            $('#safety-capaimagebefore-upload-form')[0].reset();
                        }
                    }
                });
                return false;
            });      

            // image upload
            $(document).on('submit', '#safety-capaimageafter-upload-form', function() {
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
                            call_nearmiss();
                            $('#safety-capaimageafter-upload-form')[0].reset();
                        }
                    }
                });
                return false;
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
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">Location *</h5>
                                        <div class="position-relative form-group">
                                            <select class="form-control stc-tbtm-dropdownfields" id="stc-tbtm-location">
                                                <option value="NA">Select</option>
                                                <?php
                                                    include_once("../MCU/db.php");
                                                    $dept_qry = mysqli_query($con, "
                                                        SELECT DISTINCT `stc_status_down_list_department_loc_id`, 
                                                                        `stc_status_down_list_department_location`, 
                                                                        `stc_status_down_list_department_dept`, 
                                                                        `stc_cust_project_id`
                                                        FROM `stc_cust_pro_attend_supervise`
                                                        INNER JOIN `stc_cust_project` 
                                                        ON `stc_cust_project_id` = `stc_cust_pro_attend_supervise_pro_id` 
                                                        INNER JOIN `stc_status_down_list_department` 
                                                        ON `stc_cust_project_id` = `stc_status_down_list_department_loc_id` 			
                                                        WHERE `stc_cust_pro_attend_supervise_super_id` = '".mysqli_real_escape_string($con, $_SESSION['stc_agent_sub_id'])."'
                                                        ORDER BY `stc_status_down_list_department_dept` ASC
                                                    ");

                                                    $place = '';
                                                    $locarray = array();
                                                    $locidarray = array();

                                                    // Iterate over the department query results
                                                    foreach($dept_qry as $dept_row) {
                                                        $loc = $dept_row['stc_status_down_list_department_location']; // Correct field name to match your SELECT clause
                                                        if (!in_array($loc, $locarray)) {
                                                            $locarray[] = $loc;
                                                            $locidarray[] = $dept_row['stc_status_down_list_department_loc_id'];
                                                        }
                                                        $place .= '<option value="'.$dept_row['stc_status_down_list_department_dept'].'">'.$dept_row['stc_status_down_list_department_dept'].'</option>';
                                                    }

                                                    // Output unique department locations
                                                    foreach($locarray as $key=>$row) {                                                        
                                                        echo '<option value="'.$locidarray[$key].'">'.$row.'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Date *</h5>
                                        <div class="position-relative form-group">
                                            <input type="date" class="form-control stc-tbtm-textfields" id="stc-tbtm-date" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Time *</h5>
                                        <div class="position-relative form-group">
                                            <input type="time" class="form-control stc-tbtm-textfields" id="stc-tbtm-time">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Place *</h5>
                                        <div class="position-relative form-group">
                                            <select class="form-control stc-tbtm-dropdownfields" id="stc-tbtm-place">
                                                <?php echo $place;?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <div class="row">
                                            <div class="col-md-3 col-sm-12 col-xl-3">
                                                <h5 class="card-title">Work permit no</h5>
                                                <div class="position-relative form-group">
                                                    <input type="text" class="form-control" id="stc-tbtm-gentryworkpermit-no" placeholder="Enter Work permit no">
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-sm-12 col-xl-2">
                                                <h5 class="card-title">Shift</h5>
                                                <div class="position-relative form-group">
                                                    <select class="form-control" id="stc-tbtm-gentryshift">
                                                        <option>A</option>
                                                        <option>B</option>
                                                        <option>C</option>
                                                        <option selected>E(General)</option>
                                                    </select>
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
                                                    <?php 
                                                        if($_SESSION['stc_agent_sub_category'] == "Safety Supervisor"){
                                                            ?><input type="text" class="form-control" id="stc-tbtm-gentrysupengname" placeholder="Enter Supervisor/Engineers Name"><?php 
                                                        }else{
                                                            ?><input type="text" class="form-control" id="stc-tbtm-gentrysupengname" disabled placeholder="Enter Supervisor/Engineers Name" value="<?php echo $_SESSION['stc_agent_sub_name']; ?>"><?php 
                                                        }
                                                    ?>
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
                                                        </tr>
                                                    </thead>
                                                    <tbody class="stc-safety-gentry-show-table">
                                                        <tr>
                                                            <td colspan="5">Empty record</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">Agenda of the meeting/ बैठक की कॅरिय सूचि : *</h5>
                                        <div class="position-relative form-group">
                                            <textarea class="form-control stc-tbtm-textfields" id="stc-tbtm-agendaofmeet" placeholder="Enter agenda of the meeting"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">List of topics: (Strike out (X) the topic that has not been discussed./ विषय की सूचि : ( जिस विषय की चर्चा नहीं हुई है उसे काट ( X ) दें ! : *</h5>
                                    </div>                                    
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">1. Review and discuss the topic of the previous meeting/ पिछले मीटिंग के विषय का रिव्यु करें तथा उसकी चर्चा करें :</h5>
                                        <div class="position-relative form-group">
                                            <textarea class="form-control stc-tbtm-textfields" id="stc-tbtm-pointtone" placeholder="Enter text"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">2. Ask the employee about the near miss incident or accident of the past day and note down/ कर्मचारी से बीतें दिन के नियर मिस घटना या दुर्घटना के बारे में पूछें तथा नोट करें : *</h5>
                                        <div class="position-relative form-group">
                                            <textarea class="form-control stc-tbtm-textfields" id="stc-tbtm-pointtwo" placeholder="Enter text"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">3. Inform the employee about Green Strip, Red Strip, Orange Strip and Safety Alert Circular: Inform them about hazards and safe working conditions/ कर्मचारी को ग्रीन स्ट्रिप , रेड स्ट्रिप , ऑरेंज स्ट्रिप और सेफ्टी अलर्ट सकयुरलर की जानकारी दें :
                                        उन्हें खतरे तथा कार्यानुसार सुरक्षित स्थिति के बारे में बतायें : *</h5>
                                        <div class="position-relative form-group">
                                            <textarea class="form-control stc-tbtm-textfields" id="stc-tbtm-pointthree" placeholder="Enter text"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">4. Give information about the SOP which is related to that day's work and note down/ SOP जो उस दिन के कार्य से सम्बंधित हो उसके बारे में जानकारी दे तथा नोट करें : *</h5>
                                        <div class="position-relative form-group">
                                            <textarea class="form-control stc-tbtm-textfields" id="stc-tbtm-pointfour" placeholder="Enter text"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">5. Remind employees of their personal responsibilities: proper PPE, housekeeping, tools and tackles, power equipment condition, 6 direction hazards, special requirements like work permit, no drinking, Safe behavior, Team work spirit, No dangerous architecture etc./ कर्मचारी को उनके व्यक्तिगत जिम्मेदारियां की याद दिलाये : उचित पीपीई , हाउसकीपिंग , टूल्स एंड टाकल्स , बिजली उपकरण की स्थिति, ६ दिशा के खतरे, विशेष ज़रूरत जैसे वर्क परमिट , मधपान निषेद ,सुरक्छित वयवहार ,टीम वर्क की भावना , कोई खतरनाक वास्तु इत्यादि : *</h5>
                                        <div class="position-relative form-group">
                                            <textarea class="form-control stc-tbtm-textfields" id="stc-tbtm-pointfive" placeholder="Enter text"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">6. Share security written messages with employees/ सुरक्षा लिखित सन्देश कर्मचारी के साथ साझा करें : *</h5>
                                        <div class="position-relative form-group">
                                            <textarea class="form-control stc-tbtm-textfields" id="stc-tbtm-pointsix" placeholder="Enter text"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">7. Note the views raised by the employee and supervisor and fix responsibilities/ कर्मचारी तथा सुपरवाइजर दुवारा उठाये गए विचार को नोट करे तथा जिम्मेदारियां तय करें : *</h5>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Item</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control stc-tbtm-textfields" id="stc-tbtm-res-item" placeholder="Enter Item">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xl-3">
                                        <h5 class="card-title">Responsibility</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" id="stc-tbtm-res-responsiblity" placeholder="Enter Responsibility">
                                            <select class="form-control stc-tbm-resresponsibility">
                                                <?php 
                                                    $result=mysqli_query($con, "
                                                        SELECT DISTINCT `stc_safetytbm_responsibilities_responsibilities`
                                                        FROM `stc_safetytbm_responsibilities` 
                                                        LEFT JOIN `stc_safetytbm` 
                                                        ON `stc_safetytbm_id`=`stc_safetytbm_responsibilities_tbmid`
                                                        WHERE `stc_safetytbm_created_by`='".$_SESSION['stc_agent_sub_id']."'
                                                        AND `stc_safetytbm_responsibilities_responsibilities`<>''
                                                        ORDER BY `stc_safetytbm_responsibilities_responsibilities` ASC
                                                    ");
                                                    if(mysqli_num_rows($result)>0){
                                                        foreach($result as $resultrow){
                                                            echo '<option>'.$resultrow['stc_safetytbm_responsibilities_responsibilities'].'</option>';
                                                        }
                                                    }
                                                ?>                                                        
                                            </select>
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
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">Employee's Name</h5>
                                        <div class="position-relative form-group">
                                            <table class="table table-bordered table-responsive">
                                                <thead>
                                                    <tr>
                                                        <th>Employee Name</th>
                                                        <th>Helmet</th>
                                                        <th>Nose Mask</th>
                                                        <th>Safety Goggle</th>
                                                        <th>Hand Gloves</th>
                                                        <th>FR-Jacket/Trouser</th>
                                                        <th>Safety Shoes</th>
                                                        <th>Earplug</th>
                                                        <th>Leg Guard</th>
                                                        <th>Physically fit for duty</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                        $date=date('Y-m-d');
                                                        $result=mysqli_query($con, "SELECT `id`, `location`, `stc_status_down_list_department_dept`, `emp_name`, `gpno`, `shift`, `created_date`, `created_by` FROM `stc_epermit_enrollment` LEFT JOIN `stc_status_down_list_department` ON `dep_id`=`stc_status_down_list_department_id` LEFT JOIN `stc_cust_pro_supervisor` ON `stc_cust_pro_supervisor_id`=`created_by`LEFT JOIN `stc_agents` ON `stc_cust_pro_supervisor_created_by`=`stc_agents_id` WHERE DATE(`created_date`)='".$date."' AND `stc_status_down_list_department_id` IN ( SELECT DISTINCT `stc_status_down_list_department_id` FROM `stc_cust_pro_attend_supervise` INNER JOIN `stc_status_down_list_department` ON `stc_cust_pro_attend_supervise_pro_id` = `stc_status_down_list_department_loc_id` WHERE `stc_cust_pro_attend_supervise_super_id`='".$_SESSION['stc_agent_sub_id']."') ORDER BY `emp_name` ASC");

                                                        if(mysqli_num_rows($result)>0){
                                                            $i = 1;
                                                            while($resultrow=mysqli_fetch_assoc($result)){
                                                                echo '<tr>';
                                                                echo '<td>'.$resultrow['emp_name'].'</td>';
                                                                echo '<td class="checktd text-center"><input type="checkbox" style="height: 30px; width: 20px;" checked class="checklistcb" id="cb'.$i.'h" value="Safety Helmet"></td>';
                                                                echo '<td class="checktd text-center"><input type="checkbox" style="height: 30px; width: 20px;" checked class="checklistcb" id="cb'.$i.'n" value="Nose Mask"></td>';
                                                                echo '<td class="checktd text-center"><input type="checkbox" style="height: 30px; width: 20px;" checked class="checklistcb" id="cb'.$i.'s" value="Safety Goggle"></td>';
                                                                echo '<td class="checktd text-center"><input type="checkbox" style="height: 30px; width: 20px;" checked class="checklistcb" id="cb'.$i.'hg" value="Hand Gloves"></td>';
                                                                echo '<td class="checktd text-center"><input type="checkbox" style="height: 30px; width: 20px;" checked class="checklistcb" id="cb'.$i.'fj" value="FR-Jacket/Trouser"></td>';
                                                                echo '<td class="checktd text-center"><input type="checkbox" style="height: 30px; width: 20px;" checked class="checklistcb" id="cb'.$i.'ss" value="Safety Shoes"></td>';
                                                                echo '<td class="checktd text-center"><input type="checkbox" style="height: 30px; width: 20px;" checked class="checklistcb" id="cb'.$i.'e" value="Earplug"></td>';
                                                                echo '<td class="checktd text-center"><input type="checkbox" style="height: 30px; width: 20px;" checked class="checklistcb" id="cb'.$i.'lg" value="Leg Guard"></td>';
                                                                echo '<td class="checktd text-center"><input type="checkbox" style="height: 30px; width: 20px;" checked class="checklistcb" id="cb'.$i.'p" value="Physically fit for duty"></td>';
                                                                echo '<td><a href="#" class="form-control stc-tbtm-ppe-checklistadd btn btn-success">Add</a></td>';
                                                                echo '</tr>';
                                                                $i++;
                                                            }
                                                        }
                                                    ?>                                                        
                                                </tbody>
                                            </table>
                                        </div>
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
                                            <tbody class="stc-tbtm-ppe-checklist-show-table attendance-ppe-table">
                                                <tr>
                                                    <td colspan="6">Empty record</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">Any Suggestions for SIO/IO: *</h5>
                                        <div class="position-relative form-group">
                                            <textarea class="form-control stc-tbtm-textfields" id="stc-tbtm-suggestionsio" placeholder="Enter text"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4" style="display:none;">
                                        <h5 class="card-title">Name. *</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control stc-tbtm-textfields" id="stc-tbtm-entryname" placeholder="Enter Name.">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4" style="display:none;">
                                        <h5 class="card-title">Designation *</h5>
                                        <div class="position-relative form-group">
                                            <select class="form-control stc-tbtm-dropdownfields" id="stc-tbtm-designation">
                                                <!-- <option>Site Incharge</option>
                                                <option>Manager</option>
                                                <option selected>Supervisor</option>
                                                <option>Technician</option>
                                                <option>Safety Supervisor</option>
                                                <option>Coordinator</option> -->
                                                <?php echo '<option>'.$_SESSION['stc_agent_sub_category'].'</option>'; ?>
                                            </select>
                                            <!-- <input type="text" class="form-control" id="stc-tbtm-designation" placeholder="Enter Designation"> -->
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">GP/SP No. *</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control stc-tbtm-textfields" id="stc-tbtm-gatepassno" placeholder="Enter GP/SP No.">
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
                                                            <select class="form-control stc-vhl-drop-fields" id="stc-vhl-ol">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Brake fluid level</td>
                                                        <td>
                                                            <select class="form-control stc-vhl-drop-fields" id="stc-vhl-bfl">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Water level</td>
                                                        <td>
                                                            <select class="form-control stc-vhl-drop-fields" id="stc-vhl-wl">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Windscreen washer level</td>
                                                        <td>
                                                            <select class="form-control stc-vhl-drop-fields" id="stc-vhl-ws">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Adjust seat and controls</td>
                                                        <td>
                                                            <select class="form-control stc-vhl-drop-fields" id="stc-vhl-as">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Seat belts – check for operation (all)</td>
                                                        <td>
                                                            <select class="form-control stc-vhl-drop-fields" id="stc-vhl-sb">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Parking brake – hold against slight acceleration</td>
                                                        <td>
                                                            <select class="form-control stc-vhl-drop-fields" id="stc-vhl-pb">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Foot brake – holds, stops vehicle smoothly</td>
                                                        <td>
                                                            <select class="form-control stc-vhl-drop-fields" id="stc-vhl-fb">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Passenger brake for Driving lessons</td>
                                                        <td>
                                                            <select class="form-control stc-vhl-drop-fields" id="stc-vhl-pb1">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Clutch and gearshift – shifts smoothly without jumping or jerking</td>
                                                        <td>
                                                            <select class="form-control stc-vhl-drop-fields" id="stc-vhl-cgs">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Mirrors clean and adjusted</td>
                                                        <td>
                                                            <select class="form-control stc-vhl-drop-fields" id="stc-vhl-mc">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Doors and door locks operate correctly</td>
                                                        <td>
                                                            <select class="form-control stc-vhl-drop-fields" id="stc-vhl-dl">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Steering – moves smoothly</td>
                                                        <td>
                                                            <select class="form-control stc-vhl-drop-fields" id="stc-vhl-stg">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Lights – clearance, headlights, tail, license plate, brake, indicator turn signals & alarm.</td>
                                                        <td>
                                                            <select class="form-control stc-vhl-drop-fields" id="stc-vhl-lc">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Dash control panel – all lights and gauges are operational</td>
                                                        <td>
                                                            <select class="form-control stc-vhl-drop-fields" id="stc-vhl-dashp">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Horn</td>
                                                        <td>
                                                            <select class="form-control stc-vhl-drop-fields" id="stc-vhl-horn">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Vehicle reverse alarm</td>
                                                        <td>
                                                            <select class="form-control stc-vhl-drop-fields" id="stc-vhl-alarm">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Hydraulic systems – no evidence of leaks and systems operate smoothly</td>
                                                        <td>
                                                            <select class="form-control stc-vhl-drop-fields" id="stc-vhl-hyds">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Check spare tyre</td>
                                                        <td>
                                                            <select class="form-control stc-vhl-drop-fields" id="stc-vhl-spart">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Check tow bar (where fitted) </td>
                                                        <td>
                                                            <select class="form-control stc-vhl-drop-fields" id="stc-vhl-towbar">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Emergency equipment</td>
                                                        <td>
                                                            <select class="form-control stc-vhl-drop-fields" id="stc-vhl-equip">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>First aid kit</td>
                                                        <td>
                                                            <select class="form-control stc-vhl-drop-fields" id="stc-vhl-fk">
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
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">Others</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control stc-vhl-fields" id="stc-vhl-other-remarks" placeholder="Enter other vehicle inspection.">
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
<div class="modal fade bd-ppem-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">PPEM</h5>
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
                                        <h5 class="card-title" align="center">PERSONAL PROTECTIVE EQUIPMENT MAPPING</h5>
                                        <label>* fields are automatic saved when you switch</label>
                                        <input type="hidden" class="stc-ppem-no">
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Date</h5>
                                        <div class="position-relative form-group">
                                            <input type="date" class="form-control stc-ppem-fields" id="stc-ppem-date">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Site Name</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control stc-ppem-fields" id="stc-ppem-site-name" placeholder="Enter Site Name">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Supervisor name</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control stc-ppem-fields" id="stc-ppem-supervisorname" placeholder="Enter Supervisor Name">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Site Entry</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" id="stc-ppem-ppe-siteentry" placeholder="Enter Site Entry">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Site Entry</h5>
                                        <div class="position-relative form-group">
                                            <input type="checkbox" class="checklistppecb" value="Helmet"> Helmet
                                            <input type="checkbox" class="checklistppecb" value="Shoe"> Shoe
                                            <input type="checkbox" class="checklistppecb" value="HV jacket"> H V jacket
                                            <input type="checkbox" class="checklistppecb" value="safety googles"> safety googles
                                            <input type="checkbox" class="checklistppecb" value="F R Jacket"> F R Jacket
                                            <input type="checkbox" class="checklistppecb" value="Ear plug"> Ear Plug <br>
                                            <input type="checkbox" class="checklistppecb" value="Cotten gloves"> Cotten gloves
                                            <input type="checkbox" class="checklistppecb" value="Mask"> Mask
                                            <input type="checkbox" class="checklistppecb" value="Face Shield 1"> Face Shield 1
                                            <input type="checkbox" class="checklistppecb" value="Apron"> Apron
                                            <input type="checkbox" class="checklistppecb" value="Face Shield"> Face Shield
                                            <input type="checkbox" class="checklistppecb" value="Cutting Gooles"> Cutting Googles
                                            <input type="checkbox" class="checklistppecb" value="Leather Gloves"> Leather Gloves
                                            <input type="checkbox" class="checklistppecb" value="Leg Guard"> Leg Guard
                                            <input type="checkbox" class="checklistppecb" value="Hand Sleeve"> Hand Sleeve
                                            <input type="checkbox" class="checklistppecb" value="Safety Harness"> Safety Harness
                                            <input type="checkbox" class="checklistppecb" value="Rubber Gloves"> Rubber Gloves
                                            <input type="checkbox" class="checklistppecb" value="Lubrication Gloves"> Lubrication Gloves
                                            <input type="checkbox" class="checklistppecb" value="Ear Muff"> Ear puff
                                            <input type="checkbox" class="checklistppecb" value="Sin Guard"> Sin Guard
                                        </div>
                                    </div>                                    
                                    <div class="col-md-2 col-sm-12 col-xl-2">
                                        <a href="#" class="form-control stc-ppem-ppe-add btn btn-success">Add</a>
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
<div class="modal fade bd-nearmissreporting-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Near Miss Reporting</h5>
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
                                        <h5 class="card-title" align="center">Near Miss Reporting</h5>
                                        <label>* fields are automatic saved when you switch</label>
                                        <input type="hidden" class="stc-nearmiss-no">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">Organization unit of occurrence</h5>
                                        <div class="position-relative form-group">                                            
                                            <textarea class="form-control stc-nearmiss-fields" id="stc-nearmiss-orgunitoccur" placeholder="Enter Organization unit of occurrence"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xl-3">
                                        <h5 class="card-title">Incident date</h5>
                                        <div class="position-relative form-group">
                                            <input type="date" class="form-control stc-nearmiss-fields" id="stc-nearmiss-incident-date" placeholder="Enter Incident date">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xl-3">
                                        <h5 class="card-title">Incident time</h5>
                                        <div class="position-relative form-group">
                                            <input type="time" class="form-control stc-nearmiss-fields" id="stc-nearmiss-incident-time">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xl-3">
                                        <h5 class="card-title">Incident Location</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control stc-nearmiss-fields" id="stc-nearmiss-incident-location" placeholder="Enter Incident Location">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Place of incident (Please tick):</h5>
                                        <div class="position-relative form-group">
                                            <input type="radio" class="stc-nearmiss-fields-checked stc-nearmiss-incident-place" name="stc-nearmiss-incident-place" value="Main Road"> Main Road <br>
                                            <input type="radio" class="stc-nearmiss-fields-checked stc-nearmiss-incident-place" name="stc-nearmiss-incident-place" value="Other Road"> Other Road <br>
                                            <input type="radio" class="stc-nearmiss-fields-checked stc-nearmiss-incident-place" name="stc-nearmiss-incident-place" value="Mines"> Mines <br>
                                            <input type="radio" class="stc-nearmiss-fields-checked stc-nearmiss-incident-place" name="stc-nearmiss-incident-place" value="Work Place"> Work Place <br>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Possible consequence (Please tick):</h5>
                                        <div class="position-relative form-group">
                                            <input type="radio" class="stc-nearmiss-fields-checked stc-nearmiss-possible-cons" name="stc-nearmiss-possible-cons" value="Derailment"> Derailment <br>
                                            <input type="radio" class="stc-nearmiss-fields-checked stc-nearmiss-possible-cons" name="stc-nearmiss-possible-cons" value="Equipment property damage"> Equipment property damage <br>
                                            <input type="radio" class="stc-nearmiss-fields-checked stc-nearmiss-possible-cons" name="stc-nearmiss-possible-cons" value="First Aid"> First Aid <br>
                                            <input type="radio" class="stc-nearmiss-fields-checked stc-nearmiss-possible-cons" name="stc-nearmiss-possible-cons" value="Fatality"> Fatality <br>
                                            <input type="radio" class="stc-nearmiss-fields-checked stc-nearmiss-possible-cons" name="stc-nearmiss-possible-cons" value="Foreign body"> Foreign body <br>
                                            <input type="radio" class="stc-nearmiss-fields-checked stc-nearmiss-possible-cons" name="stc-nearmiss-possible-cons" value="fire"> fire <br>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Brief Incident description</h5>
                                        <div class="position-relative form-group">                                            
                                            <textarea class="form-control stc-nearmiss-fields" id="stc-nearmiss-incidentdesc" placeholder="Enter Brief Incident description"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Primary cause</h5>
                                        <div class="position-relative form-group">                                            
                                            <textarea class="form-control stc-nearmiss-fields" id="stc-nearmiss-primarycause" placeholder="Enter Primary cause"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xl-3">
                                        <h5 class="card-title">Name of the employee/ contact partner:</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control stc-nearmiss-fields" id="stc-nearmiss-nameofemployee" placeholder="Enter Name of the employee/ contact partner">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xl-3">
                                        <h5 class="card-title">P No./ G P No.</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control stc-nearmiss-fields" id="stc-nearmiss-gpno" placeholder="Enter P No./ G P No.">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xl-3">
                                        <h5 class="card-title">Department / Vendor:</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control stc-nearmiss-fields" id="stc-nearmiss-vendor" placeholder="Enter Department / Vendor">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">Action Taken</h5>
                                        <div class="position-relative form-group">                                            
                                            <textarea class="form-control stc-nearmiss-fields" id="stc-nearmiss-actiontaken" placeholder="Enter Action Taken"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xl-3">
                                        <h5 class="card-title">Date</h5>
                                        <div class="position-relative form-group">
                                            <input type="date" class="form-control stc-nearmiss-fields" id="stc-nearmiss-currdate">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xl-3">
                                        <h5 class="card-title">Name & Signature</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control stc-nearmiss-fields" id="stc-nearmiss-namesignature" placeholder="Enter Name & Signature">
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
<div class="modal fade bd-capa-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">CAPA</h5>
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
                                        <h5 class="card-title" align="center">CAPA</h5>
                                        <label>* fields are automatic saved when you switch</label>
                                        <input type="hidden" class="stc-capa-no">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">Location *</h5>
                                        <div class="position-relative form-group">
                                            <select class="form-control stc-capa-dropdownfields" id="stc-capa-location">
                                                <option value="NA">Select</option>
                                                <?php
                                                    include_once("../MCU/db.php");
                                                    $dept_qry = mysqli_query($con, "
                                                        SELECT DISTINCT `stc_status_down_list_department_loc_id`, 
                                                                        `stc_status_down_list_department_location`, 
                                                                        `stc_status_down_list_department_dept`, 
                                                                        `stc_cust_project_id`
                                                        FROM `stc_cust_pro_attend_supervise`
                                                        INNER JOIN `stc_cust_project` 
                                                        ON `stc_cust_project_id` = `stc_cust_pro_attend_supervise_pro_id` 
                                                        INNER JOIN `stc_status_down_list_department` 
                                                        ON `stc_cust_project_id` = `stc_status_down_list_department_loc_id` 			
                                                        WHERE `stc_cust_pro_attend_supervise_super_id` = '".mysqli_real_escape_string($con, $_SESSION['stc_agent_sub_id'])."'
                                                        ORDER BY `stc_status_down_list_department_dept` ASC
                                                    ");

                                                    $place = '';
                                                    $locarray = array();
                                                    $locidarray = array();

                                                    // Iterate over the department query results
                                                    foreach($dept_qry as $dept_row) {
                                                        $loc = $dept_row['stc_status_down_list_department_location']; // Correct field name to match your SELECT clause
                                                        if (!in_array($loc, $locarray)) {
                                                            $locarray[] = $loc;
                                                            $locidarray[] = $dept_row['stc_status_down_list_department_loc_id'];
                                                        }
                                                        $place .= '<option value="'.$dept_row['stc_status_down_list_department_dept'].'">'.$dept_row['stc_status_down_list_department_dept'].'</option>';
                                                    }

                                                    // Output unique department locations
                                                    foreach($locarray as $key=>$row) {                                                        
                                                        echo '<option value="'.$locidarray[$key].'">'.$row.'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Sitename *</h5>
                                        <div class="position-relative form-group">
                                            <select class="form-control stc-capa-dropdownfields" id="stc-capa-sitename">
                                                <?php echo $place;?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Place *</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control stc-capa-fields" id="stc-capa-place" placeholder="Enter Place">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Branch *</h5>
                                        <div class="position-relative form-group">
                                        <input type="text" class="form-control stc-capa-fields" id="stc-capa-branch" placeholder="Enter Branch">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Date *</h5>
                                        <div class="position-relative form-group">
                                        <input type="date" class="form-control stc-capa-fields" id="stc-capa-date" placeholder="Enter Date">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">NAME OF PERSON OBSERVED *</h5>
                                        <div class="position-relative form-group">
                                        <input type="text" class="form-control stc-capa-fields" id="stc-capa-persionobserved" placeholder="Enter NAME OF PERSON OBSERVED">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">DESIGNATION *</h5>
                                        <div class="position-relative form-group">
                                        <input type="text" class="form-control stc-capa-fields" id="stc-capa-designation" placeholder="Enter DESIGNATION">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">N.C. LOCATION *</h5>
                                        <div class="position-relative form-group">
                                        <input type="text" class="form-control stc-capa-fields" id="stc-capa-nclocation" placeholder="Enter N.C. LOCATION	">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">OBSERVATION DATE	 *</h5>
                                        <div class="position-relative form-group">
                                        <input type="date" class="form-control stc-capa-fields" id="stc-capa-obsdate" placeholder="Enter OBSERVATION DATE	">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">TGT DATE COMPLIANCE *</h5>
                                        <div class="position-relative form-group">
                                        <input type="date" class="form-control stc-capa-fields" id="stc-capa-tgtdate" placeholder="Enter TGT DATE COMPLIANCE">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Severity *</h5>
                                        <div class="position-relative form-group">
                                        <input type="text" class="form-control stc-capa-fields" id="stc-capa-severity" placeholder="Enter Severity">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">DESCRIPTION OF NON-CONFORMANCE OBSERVED *</h5>
                                        <div class="position-relative form-group">
                                        <textarea class="form-control stc-capa-fields" id="stc-capa-nonconformanceobserved"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">RESPONSIBLE PERSON NAME *</h5>
                                        <div class="position-relative form-group">
                                        <input type="text" class="form-control stc-capa-fields" id="stc-capa-res_personname" placeholder="Enter RESPONSIBLE PERSON NAME">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">RESPONSIBLE PERSON DESIGNATION *</h5>
                                        <div class="position-relative form-group">
                                        <input type="text" class="form-control stc-capa-fields" id="stc-capa-res_persondesignation" placeholder="Enter RESPONSIBLE PERSON DESIGNATION">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">RESPONSIBLE PERSON NAME *</h5>
                                        <div class="position-relative form-group">
                                        <input type="text" class="form-control stc-capa-fields" id="stc-capa-res_personname2" placeholder="Enter RESPONSIBLE PERSON NAME">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">RESPONSIBLE PERSON DESIGNATION *</h5>
                                        <div class="position-relative form-group">
                                        <input type="text" class="form-control stc-capa-fields" id="stc-capa-res_persondesignation2" placeholder="Enter RESPONSIBLE PERSON DESIGNATION">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">RESPONSIBLE PERSON NAME *</h5>
                                        <div class="position-relative form-group">
                                        <input type="text" class="form-control stc-capa-fields" id="stc-capa-res_personname3" placeholder="Enter RESPONSIBLE PERSON NAME">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">RESPONSIBLE PERSON DESIGNATION *</h5>
                                        <div class="position-relative form-group">
                                        <input type="text" class="form-control stc-capa-fields" id="stc-capa-res_persondesignation3" placeholder="Enter RESPONSIBLE PERSON DESIGNATION">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">ROOT CAUSE ANALYSIS *</h5>
                                        <div class="position-relative form-group">
                                        <textarea class="form-control stc-capa-fields" id="stc-capa-rootcause" placeholder="Enter ROOT CAUSE ANALYSIS"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">CORRECTIVE ACTION *</h5>
                                        <div class="position-relative form-group">
                                        <textarea class="form-control stc-capa-fields" id="stc-capa-corrective" placeholder="Enter CORRECTIVE ACTION"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">PREVENTIVE ACTION *</h5>
                                        <div class="position-relative form-group">
                                        <textarea class="form-control stc-capa-fields" id="stc-capa-preventive" placeholder="Enter PREVENTIVE ACTION"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">COMPLIANCE BY (SUP/ENG) DATE *</h5>
                                        <div class="position-relative form-group">
                                        <input type="date" class="form-control stc-capa-fields" id="stc-capa-compliancebysupengdate" placeholder="Enter COMPLIANCE BY (SUP/ENG) DATE">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">COMPLIANCE BY (SUP/ENG) NAME *</h5>
                                        <div class="position-relative form-group">
                                        <input type="text" class="form-control stc-capa-fields" id="stc-capa-compliancebysupengname" placeholder="Enter COMPLIANCE BY (SUP/ENG) NAME">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">REVIEWED BY (SAFETY OFFICER) DATE *</h5>
                                        <div class="position-relative form-group">
                                        <input type="date" class="form-control stc-capa-fields" id="stc-capa-reviewedbysodate" placeholder="Enter REVIEWED BY (SAFETY OFFICER) DATE">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">REVIEWED BY (SAFETY OFFICER) NAME *</h5>
                                        <div class="position-relative form-group">
                                        <input type="text" class="form-control stc-capa-fields" id="stc-capa-reviewedbysoname" placeholder="Enter REVIEWED BY (SAFETY OFFICER) NAME">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">APPROVED BY (Director) NAME	DATE *</h5>
                                        <div class="position-relative form-group">
                                        <input type="text" class="form-control stc-capa-fields" id="stc-capa-reviewedbydirname" placeholder="Enter APPROVED BY (Director) NAME	DATE">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">APPROVED BY (Director) NAME *</h5>
                                        <div class="position-relative form-group">
                                        <input type="date" class="form-control stc-capa-fields" id="stc-capa-reviewedbydirdate" placeholder="Enter APPROVED BY (Director) NAME">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Before Image*</h5>
                                        <div class="position-relative form-group">
                                            <form id="safety-capaimagebefore-upload-form">
                                                <input type="hidden" class="stc-capa-no" name="stc-capabefore-no">
                                                <input type="file" class="form-control" name="before-image">
                                                <input type="submit" class="btn btn-primary" value="Upload">
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">After Image*</h5>
                                        <div class="position-relative form-group">
                                            <form id="safety-capaimageafter-upload-form">
                                                <input type="hidden" class="stc-capa-no" name="stc-capaafter-no">
                                                <input type="file" class="form-control" name="after-image">
                                                <input type="submit" class="btn btn-primary" value="Upload">
                                            </form>
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
<div class="modal fade bd-hotwork-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Hot Work</h5>
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
                                        <h5 class="card-title" align="center">Hot Work</h5>
                                        <label>* fields are automatic saved when you switch</label>
                                        <input type="hidden" class="stc-hotwork-no">
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h5 class="card-title">Work Order No</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control stc-hotwork-fields" id="stc-hotwork-wono" placeholder="Enter Work Order No">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Job Sitename</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control stc-hotwork-fields" id="stc-hotwork-sitename" placeholder="Enter Job sitename">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Starting date</h5>
                                        <div class="position-relative form-group">
                                            <input type="date" class="form-control stc-hotwork-fields" id="stc-hotwork-starting-date" placeholder="Enter Starting date">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Electric welding machine Make</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control stc-hotwork-fields" id="stc-hotwork-make" placeholder="Enter Electric welding machine Make">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Electric welding machine Sl No</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control stc-hotwork-fields" id="stc-hotwork-slno" placeholder="Enter Electric welding machine Sl No">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <p>If you dont have a data then move to next field *</p>
                                        <div class="position-relative form-group">
                                            <table class="table table-hover table-bordered ">
                                                <thead>
                                                    <tr>
                                                        <td class="card-title">Equipments</td>
                                                        <td class="card-title">Yes / No</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>CONNECTIONS ARE PROPER AND EFFECTIVELY INSULATED</td>
                                                        <td>
                                                            <select class="form-control stc-hotwork-drop-fields" id="stc-hotwork-capaei">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>ON /OFF SWITCH IN GOOD WORKING CONDITION?</td>
                                                        <td>
                                                            <select class="form-control stc-hotwork-drop-fields" id="stc-hotwork-oosigwc">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>VOLTMETER/AMMETER CONNECTED & WORKING PROPERLY?</td>
                                                        <td>
                                                            <select class="form-control stc-hotwork-drop-fields" id="stc-hotwork-vacawp">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>REGULATOR WORKING PROPERLY</td>
                                                        <td>
                                                            <select class="form-control stc-hotwork-drop-fields" id="stc-hotwork-rwp">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>THREE / SINGLE PHASE TRANSFORMER</td>
                                                        <td>
                                                            <select class="form-control stc-hotwork-drop-fields" id="stc-hotwork-tspt">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>CABLE    (FROM MACHINE TO EARTH CLAMP</td>
                                                        <td>
                                                            <select class="form-control stc-hotwork-drop-fields" id="stc-hotwork-cfmtec">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>GOOD EARTHING AND GROUNDING. (DOUBLE EARTHING</td>
                                                        <td>
                                                            <select class="form-control stc-hotwork-drop-fields" id="stc-hotwork-geagde">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>CABLE (FROM MACHINE TO ELECTRODE HOLDER)</td>
                                                        <td>
                                                            <select class="form-control stc-hotwork-drop-fields" id="stc-hotwork-cfmteh">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>ELECTRODE HOLDER</td>
                                                        <td>
                                                            <select class="form-control stc-hotwork-drop-fields" id="stc-hotwork-eh">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>WELDING MACHIN COMPLETELY COVERD AND NO LOOSE CONNECTIONS</td>
                                                        <td>
                                                            <select class="form-control stc-hotwork-drop-fields" id="stc-hotwork-wmccanlc">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>WHEELS FREELY ROTATING</td>
                                                        <td>
                                                            <select class="form-control stc-hotwork-drop-fields" id="stc-hotwork-wfr">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>COMPATIBLE FIRE EXTINGUSHER AVAILABLE FOR THE WELDING SET</td>
                                                        <td>
                                                            <select class="form-control stc-hotwork-drop-fields" id="stc-hotwork-cfeaftws">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>SUPPLY CABLE’S LENGTH EXCEEDING 5M</td>
                                                        <td>
                                                            <select class="form-control stc-hotwork-drop-fields" id="stc-hotwork-scle">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>CONNECTION TAKEN THROUGH ELCB’s?</td>
                                                        <td>
                                                            <select class="form-control stc-hotwork-drop-fields" id="stc-hotwork-ctte">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>WELDING CABLE OF PROPER RATING AND IN PROPER CONDITION WITHOUT ANY DAMAGES?</td>
                                                        <td>
                                                            <select class="form-control stc-hotwork-drop-fields" id="stc-hotwork-wcopraipcwad">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>CONNECTING LUGS TIGHTENED PROPERLY?</td>
                                                        <td>
                                                            <select class="form-control stc-hotwork-drop-fields" id="stc-hotwork-cltp">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>WELL INSULATED AND NO EXPOSED PARTS?</td>
                                                        <td>
                                                            <select class="form-control stc-hotwork-drop-fields" id="stc-hotwork-wianep">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>SIZE OF CABLE USED ARE PROPORTIONAL TO VOLTAGE SUPPLY</td>
                                                        <td>
                                                            <select class="form-control stc-hotwork-drop-fields" id="stc-hotwork-socuaptvs">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>RETURN EARTH CABLE OF SUFFICIENT LENGTH? ARE ELECTRICAL CONDUCTORS PROHIBITED FROM BEING USED TO COMPLETE WOR</td>
                                                        <td>
                                                            <select class="form-control stc-hotwork-drop-fields" id="stc-hotwork-recoslaecpfbutcw">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>ANY OVERLOADING, WHERE BY CABLES BECOME HOT?</td>
                                                        <td>
                                                            <select class="form-control stc-hotwork-drop-fields" id="stc-hotwork-aowbcbh">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>ANY CONTACT WITH OIL / SHARP EDGES OR WATER?</td>
                                                        <td>
                                                            <select class="form-control stc-hotwork-drop-fields" id="stc-hotwork-acwoseow">
                                                                <option value="0">Please select</option>
                                                                <option value="1">Yes</option>
                                                                <option value="2">No</option>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>ANY CABLES PROPERLY PLACED TO PREVENT TRIPPING HAZARDS?</td>
                                                        <td>
                                                            <select class="form-control stc-hotwork-drop-fields" id="stc-hotwork-acpptpth">
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
<div class="modal fade bd-ppec-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">PPEC</h5>
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
                                        <h5 class="card-title" align="center">PPEC</h5>
                                        <label>* fields are automatic saved when you switch</label>
                                        <input type="hidden" class="stc-ppec-no">
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Work Order No</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control stc-ppec-fields" id="stc-ppec-wono" placeholder="Enter Work Order No">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Job Sitename</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control stc-ppec-fields" id="stc-ppec-sitename" placeholder="Enter Job sitename">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Check</h5>
                                        <div class="position-relative form-group">
                                            <select class="form-control stc-ppec-drop-fields" id="stc-ppec-check">
                                                <option value="">Please select</option>
                                                <option value="OK">OK</option>
                                                <option value="NOT OK">NOT OK</option>
                                                <option value="NOT APPLICABLE">NOT APPLICABLE</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Site Supervisor</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control stc-ppec-fields" id="stc-ppec-sitesup" placeholder="Enter Site Supervisor">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Safety Supervisor/ Officer</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control stc-ppec-fields" id="stc-ppec-safetysup" placeholder="Enter Safety Supervisor/ Officer">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Name of Workmen</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" id="stc-ppec-ppe-workmen" placeholder="Enter Name of Workmen">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Site Entry</h5>
                                        <div class="position-relative form-group">
                                            <input type="checkbox" class="checklistppecb2" value="Helmet"> Helmet
                                            <input type="checkbox" class="checklistppecb2" value="Shoe"> Shoe
                                            <input type="checkbox" class="checklistppecb2" value="HV jacket"> H V jacket
                                            <input type="checkbox" class="checklistppecb2" value="safety googles"> safety googles
                                            <input type="checkbox" class="checklistppecb2" value="F R Jacket"> F R Jacket
                                            <input type="checkbox" class="checklistppecb2" value="Ear plug"> Ear Plug <br>
                                            <input type="checkbox" class="checklistppecb2" value="Cotten gloves"> Cotten gloves
                                            <input type="checkbox" class="checklistppecb2" value="Mask"> Mask
                                            <input type="checkbox" class="checklistppecb2" value="Face Shield 1"> Face Shield 1
                                            <input type="checkbox" class="checklistppecb2" value="Apron"> Apron
                                            <input type="checkbox" class="checklistppecb2" value="Face Shield"> Face Shield
                                            <input type="checkbox" class="checklistppecb2" value="Cutting Gooles"> Cutting Googles
                                            <input type="checkbox" class="checklistppecb2" value="Leather Gloves"> Leather Gloves
                                            <input type="checkbox" class="checklistppecb2" value="Leg Guard"> Leg Guard
                                            <input type="checkbox" class="checklistppecb2" value="Hand Sleeve"> Hand Sleeve
                                            <input type="checkbox" class="checklistppecb2" value="Safety Harness"> Safety Harness
                                            <input type="checkbox" class="checklistppecb2" value="Rubber Gloves"> Rubber Gloves
                                            <input type="checkbox" class="checklistppecb2" value="Lubrication Gloves"> Lubrication Gloves
                                            <input type="checkbox" class="checklistppecb2" value="Ear Muff"> Ear puff
                                            <input type="checkbox" class="checklistppecb2" value="Sin Guard"> Sin Guard
                                        </div>
                                    </div>                                    
                                    <div class="col-md-2 col-sm-12 col-xl-2">
                                        <a href="#" class="form-control stc-ppec-ppe-add btn btn-success">Add</a>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <div class="position-relative form-group">
                                            <table class="table table-hover table-bordered table-responsive">
                                                <thead>
                                                    <tr>
                                                        <td class="card-title">Name of Workmen</td>
                                                        <td class="card-title">Helmet</td>
                                                        <td class="card-title">Shoe</td>
                                                        <td class="card-title">H V jacket</td>
                                                        <td class="card-title">safety googles</td>
                                                        <td class="card-title">F R Jacket</td>
                                                        <td class="card-title">Ear Plug</td>
                                                        <td class="card-title">Cotten gloves</td>
                                                        <td class="card-title">Mask</td>
                                                        <td class="card-title">Face Shield 1</td>
                                                        <td class="card-title">Apron</td>
                                                        <td class="card-title">Face Shield</td>
                                                        <td class="card-title">Cutting Googles</td>
                                                        <td class="card-title">Leather Gloves</td>
                                                        <td class="card-title">Leg Guard</td>
                                                        <td class="card-title">Hand Sleeve</td>
                                                        <td class="card-title">Safety Harness</td>
                                                        <td class="card-title">Rubber Gloves</td>
                                                        <td class="card-title">Lubrication Gloves</td>
                                                        <td class="card-title">Ear puff</td>
                                                        <td class="card-title">Sin Guard</td>

                                                    </tr>
                                                </thead>
                                                <tbody class="stc-ppec-ppes-res"></tbody>
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
<div class="modal fade bd-toollist-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">TOOLS & TACKLES HAND TOOLLIST</h5>
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
                                        <h5 class="card-title" align="center">TOOLS & TACKLES HAND TOOLLIST</h5>
                                        <label>* fields are automatic saved when you switch</label>
                                        <input type="hidden" class="stc-toollist-no">
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Date</h5>
                                        <div class="position-relative form-group">
                                            <input type="date" class="form-control stc-toollist-fields" id="stc-toollist-date" placeholder="Enter Date">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Work Permit No</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control stc-toollist-fields" id="stc-toollist-wono" placeholder="Enter Work Permit No">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Job Sitename</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control stc-toollist-fields" id="stc-toollist-sitename" placeholder="Enter Job sitename">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">SUPERVISOR & TECHNICIAN</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control stc-toollist-fields" id="stc-toollist-suptech" placeholder="Enter SUPERVISOR & TECHNICIAN">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <h3 class="card-title text-center">Hand Tool List Items</h3>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Tool Description</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" id="stc-toollist-tooldesc" placeholder="Enter tool description">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xl-3">
                                        <h5 class="card-title">Quantity</h5>
                                        <div class="position-relative form-group">
                                            <input type="number" class="form-control" id="stc-toollist-toolquantity" placeholder="Enter quantity">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xl-3">
                                        <h5 class="card-title">In use</h5>
                                        <div class="position-relative form-group">
                                            <input type="number" class="form-control" id="stc-toollist-toolinuse" placeholder="Enter in use">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xl-3">
                                        <h5 class="card-title">Repair</h5>
                                        <div class="position-relative form-group">
                                            <input type="number" class="form-control" id="stc-toollist-toolinrepair" placeholder="Enter Repair">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xl-3">
                                        <h5 class="card-title">Damaged</h5>
                                        <div class="position-relative form-group">
                                            <input type="number" class="form-control" id="stc-toollist-tooldamaged" placeholder="Enter Damaged">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Add</h5>
                                        <div class="position-relative form-group">
                                            <a href="javascript:void(0)" class="form-control btn btn-success" id="stc-toollist-tooladdbtn">Add</a>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <div class="position-relative form-group">
                                            <table class="table table-hover table-bordered table-responsive">
                                                <thead>
                                                    <tr>
                                                        <td class="card-title">Item No</td>
                                                        <td class="card-title">Tool Description</td>
                                                        <td class="card-title">Quantity</td>
                                                        <td class="card-title">In use</td>
                                                        <td class="card-title">Repair</td>
                                                        <td class="card-title">Damaged</td>
                                                    </tr>
                                                </thead>
                                                <tbody class="stc-toollist-list-res-table">

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

<div class="modal fade bd-toolelist-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">TOOLS & TACKLES Equipment</h5>
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
                                        <label>* fields are automatic saved when you switch</label>
                                        <input type="hidden" class="stc-toolelist-no">
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Work Order No</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control stc-toolelist-fields" id="stc-toolelist-wono" placeholder="Enter Work Permit No">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Job Sitename</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control stc-toolelist-fields" id="stc-toolelist-sitename" placeholder="Enter Job sitename">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Safety Supervisor Signature</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control stc-toolelist-fields" id="stc-toolelist-suptech" placeholder="Enter Safety Supervisor Signature">
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <h5 class="card-title">Desc of Tool name</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" id="stc-toolelist-tooldesc" placeholder="Enter Desc of Tool name">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xl-3">
                                        <h5 class="card-title">Serial No</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" id="stc-toolelist-serial_no" placeholder="Enter Serial No">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xl-3">
                                        <h5 class="card-title">Safe To Work</h5>
                                        <div class="position-relative form-group">
                                            <select class="form-control" id="stc-toolelist-safetowork">
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xl-3">
                                        <h5 class="card-title">Supr/Engg Name</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" id="stc-toolelist-suprname" placeholder="Enter Supr/Engg Name">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xl-3">
                                        <h5 class="card-title">Sign</h5>
                                        <div class="position-relative form-group">
                                            <input type="text" class="form-control" id="stc-toolelist-toolsign" placeholder="Enter Sign">
                                        </div>
                                    </div>
                                    <div class="col-md-3 col-sm-12 col-xl-3">
                                        <h5 class="card-title">Date</h5>
                                        <div class="position-relative form-group">
                                            <input type="date" class="form-control" id="stc-toolelist-tooldate" placeholder="Enter Date">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4">
                                        <h5 class="card-title">Add</h5>
                                        <div class="position-relative form-group">
                                            <a href="javascript:void(0)" class="form-control btn btn-success" id="stc-toolelist-tooladdbtn">Add</a>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <div class="position-relative form-group">
                                            <table class="table table-hover table-bordered table-responsive">
                                                <thead>
                                                    <tr>
                                                        <td class="card-title">Item No</td>
                                                        <td class="card-title">Tool Description</td>
                                                        <td class="card-title">Serial No</td>
                                                        <td class="card-title">Safe To Work</td>
                                                        <td class="card-title">Supr/Engg Name</td>
                                                        <td class="card-title">Sign</td>
                                                        <td class="card-title">Date</td>
                                                    </tr>
                                                </thead>
                                                <tbody class="stc-toolelist-list-res-table">

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