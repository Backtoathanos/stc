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
        
        @media (max-width: 991.98px){
           .tabledata-responsvie{
                overflow-x: auto;
                white-space: nowrap;
           }
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
                            <a role="tab" class="nav-link show active" id="tab-1" data-toggle="tab" href="#view-req">
                                <span>View Status Down List</span>
                            </a>
                        </li>
                        <!-- <li class="nav-item">
                            <a role="tab" class="nav-link show" id="tab-2" data-toggle="tab" href="#create-req">
                                <span>Create Status Down List</span>
                            </a>
                        </li> -->
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane tabs-animation fade show active" id="view-req" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12 col-xl-12"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-2 col-md-2">
                                                    <button class="form-control btn bg-success text-white mb-3 stc-std-operation-btn" type="create" data-toggle="modal" data-target=".bd-create-std-modal">Add Status Down List</button>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3">
                                                    <h5 style="position: relative;top: 8px;">Location/Site Name :</h5>
                                                </div>
                                                <div class="col-lg-7 col-md-7">
                                                    <select class="form-control load_site_name_consump" id="stc-agent-sup-std-location-find">
                                                        <option>No Site Found!!!</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-2 col-md-2">
                                                    <a href="#" class="form-control btn btn-success stc-std-list-show-hit">Find</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-xl-12"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <div class="card mb-3 widget-content stc-std-search-result tabledata-responsvie">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane tabs-animation fade show" id="create-req" role="tabpanel">
                            
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
                var operation=$(this).attr('operation');
                var slocation  = $('#stc-agent-sup-std-sublocation').val();
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
                if(operation=="manual"){
                    $.ajax({
                        url         : "nemesis/stc_std.php",
                        method      : "POST",
                        data        : {
                            stc_std_hit:1,
                            stc_slocation:slocation,
                            stc_location:location,
                            stc_dept:dept,
                            stc_area:area,
                            stc_j_plannning:j_plannning
                        },
                        success     : function(response_std){
                            // console.log(response_std);
                            var response=response_std.trim();
                            if(response=="Status Down List saved. Thankyou!!!"){
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
                }else{
                    var std_id=$('#stc-agent-sup-std-hidden-std-id').val();
                    $.ajax({
                        url         : "nemesis/stc_std.php",
                        method      : "POST",
                        data        : {
                            stc_std_update_mhit:1,
                            stc_std_id:std_id,
                            stc_slocation:slocation,
                            stc_location:location,
                            stc_dept:dept,
                            stc_area:area,
                            stc_eq_type:eq_type,
                            stc_eq_number:eq_number,
                            stc_j_varities:j_varities,
                            stc_j_plannning:j_plannning
                        },
                        success     : function(response_std){
                            // console.log(response_std);
                            var response=response_std.trim();
                            if(response=="Status Down List updated. Thankyou!!!"){
                                alert(response_std);
                            }else if(response=="Please login!!!"){
                                alert(response_std);
                                window.location.reload();
                            }else{
                                alert(response_std);
                            }
                        }
                    });
                }
            });

            function load_std_perticular(std_id){
                $.ajax({
                    url         : "nemesis/stc_std.php",
                    method      : "POST",
                    data        : {
                        stc_std_perticular_call_hit:1,
                        std_id:std_id
                    },
                    dataType : 'JSON',
                    success     : function(response_sdl){
                        $('#stc-agent-sup-std-hidden-std-id').val(response_sdl.stc_status_down_list_id);
                        $('#stc-agent-sup-std-sublocation').val(response_sdl.stc_status_down_list_plocation);
                        $('#stc-agent-sup-std-location').val(response_sdl.stc_status_down_list_location);
                        $('#stc-agent-sup-std-sub-location').val(response_sdl.stc_status_down_list_sub_location);
                        $('#stc-agent-sup-std-sub-location').change();
                        $('#stc-agent-sup-std-equipment-status').val(response_sdl.stc_status_down_list_equipment_status);
                        $('#stc-agent-sup-std-job-plannning').val(response_sdl.stc_status_down_list_jobtype);
                        $(".stc-agent-sup-std-qty").val(response_sdl.stc_status_down_list_qty);
                        $(".stc-agent-sup-std-capacity").val(response_sdl.stc_status_down_list_capacity);
                        $("#stc-agent-sup-std-reasonattribite").val(response_sdl.stc_status_down_list_reasonattribute);
                        $('#stc-agent-sup-std-created-by-select').val(response_sdl.stc_status_down_list_created_by_select);
                        $('.stc-agent-sup-std-permit-no').val(response_sdl.stc_status_down_list_permit_no);
                        $('.stc-agent-sup-std-creator-details').val(response_sdl.stc_status_down_list_creator_details);
                        $('.stc-agent-sup-std-responsive-person').val(response_sdl.stc_status_down_list_responsive_person);
                        $('#stc-agent-sup-std-reason').val(response_sdl.stc_status_down_list_reason);
                        $('#stc-agent-sup-std-material-desc').val(response_sdl.stc_status_down_list_material_desc);
                        $('#stc-agent-sup-std-manpower-req').val(response_sdl.stc_status_down_list_manpower_req);
                        $('#stc-agent-sup-std-target-date').val(response_sdl.stc_status_down_list_target_date);
                        $('.stc-agent-sup-std-remarks').val(response_sdl.stc_status_down_list_remarks);
                        $('.stc-std-tools-req-item-show').html(response_sdl.stc_status_down_list_tools_req);
                        $('#stc-agent-sup-std-area').val(response_sdl.stc_status_down_list_area);
                    }
                });
            }

            function stc_update_std(){
                var std_id = $('#stc-agent-sup-std-hidden-std-id').val();
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
                        stc_update_std_hit:1,
                        std_id:std_id,
                        eq_status:eq_status,
                        j_plannning:j_plannning,
                        qty:qty,
                        capacity:capacity,
                        reasonattribute:reasonattribute,
                        j_type:j_type,
                        j_varities:j_varities,
                        created_by_se:created_by_se,
                        permit_no:permit_no,
                        creator_details:creator_details,
                        r_person:r_person,
                        reason:reason,
                        material_desc:material_desc,
                        manpower_req:manpower_req,
                        target_date:target_date,
                        remarks:remarks
                    },
                    dataType : 'JSON',
                    success     : function(response_sdl){
                        if(response_sdl=="login"){
                            window.location.reload();
                        }else{
                            load_std_perticular(std_id)
                        }
                    }
                });
            }

            function stc_update_std_toolsreq(){
                var std_id = $('#stc-agent-sup-std-hidden-std-id').val();
                var tools_req=get_filter('stc-agent-sup-std-tools-req');
                $.ajax({
                    url         : "nemesis/stc_std.php",
                    method      : "POST",
                    data        : {
                        stc_update_std_toolsreq_hit:1,
                        std_id:std_id,
                        tools_req:tools_req
                    },
                    dataType : 'JSON',
                    success     : function(response_sdl){
                        if(response_sdl=="login"){
                            window.location.reload();
                        }else{
                            load_std_perticular(std_id)
                        }
                    }
                });
            }

            $('body').delegate('.stc-std-update-on-focusout', 'focusout', function(e){
                $('.attribute-message-show').remove();
                $(this).after('<p class="text-success attribute-message-show">Record updated.</p>');
                stc_update_std();
                var location_id=$('#stc-agent-sup-std-location-find').val();
                std_list_call(location_id);
            });

            $('body').delegate('.stc-std-update-on-change', 'focusout', function(e){
                $('.attribute-message-show').remove();
                $(this).after('<p class="text-success attribute-message-show">Record updated.</p>');
                stc_update_std();
                var location_id=$('#stc-agent-sup-std-location-find').val();
                std_list_call(location_id);
            });

            $('body').delegate('.stc-agent-sup-std-tools-req-btn', 'click', function(e){
                $('.attribute-message-show').remove();
                $(this).after('<p class="text-success attribute-message-show">Record updated.</p>');
                stc_update_std_toolsreq();
                var location_id=$('#stc-agent-sup-std-location-find').val();
                std_list_call(location_id);
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
                    var permit_no=$(this).parent().find('.stc-std-permit-no-hidden-call').val();  
                    $('.stc-agent-sup-std-upermit-no').val(permit_no);
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

            // show hide tools requisition
            $('.show-tools-req-section').hide();
            $('body').delegate('.tools-req-oper-option', 'click', function(e){
                e.preventDefault();
                var showhideval=$(this).attr('operation');
                if(showhideval=="show"){
                    $('.show-tools-req-section').toggle(500);
                    $(this).removeAttr('operation');
                    $(this).attr('operation', "hide");
                }else{
                    $('.show-tools-req-section').toggle(500);
                    $(this).removeAttr('operation');
                    $(this).attr('operation', "show");
                }
            });

            $('body').delegate('.stc-std-operation-btn', 'click', function(e){
                e.preventDefault();
                var type = $(this).attr('type');
                if(type=="create"){
                    $('.stc-agent-sup-std-save').removeAttr('operation');
                    $('.stc-agent-sup-std-save').attr('operation', 'manual');
                    $('.stc-std-section-hideshow').hide();
                }else{
                    $('.stc-agent-sup-std-save').removeAttr('operation');
                    $('.stc-agent-sup-std-save').attr('operation', 'auto');
                    $('.stc-std-section-hideshow').show();
                    var std_id=$(this).attr("id");
                    $('#stc-agent-sup-std-hidden-std-id').val(std_id);
                    load_std_perticular(std_id);
                }
            });
            
            $('body').delegate('.update-status-si', 'click', function(e){
                e.preventDefault();
                var data_id = $(this).attr('data-id');
                var actiontype = $(this).attr('actiontype');
                $.ajax({
                    url         : "nemesis/stc_std.php",
                    method      : "POST",
                    data        : {
                        stc_jobcomplete_update_hit:1,
                        data_id:data_id,
                        actiontype:actiontype
                    },
                    dataType : 'JSON',
                    success     : function(response_sdl){
                        if(response_sdl=="logout"){
                            widnow.location.reload();
                        }else{
                            alert(response_sdl);
                            var location_id=$('#stc-agent-sup-std-location-find').val();
                            std_list_call(location_id);
                        }
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
<div class="modal fade bd-create-std-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-modal="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Add Status Down List</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 col-xl-6"> 
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <h5>Location :</h5><br>
                                <input type="hidden" id="stc-agent-sup-std-hidden-std-id">
                                <input type="hidden" id="stc-agent-sup-std-hidden-location-id">
                                <select class="btn btn-success form-control text-left" id="stc-agent-sup-std-sublocation">
                                    <option value="NA">Select</option>
                                    <option>TATA Steel - Jamshedpur</option>
                                    <option>TATA Steel - KPO</option>
                                    <option>Others</option>
                                </select> 
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-xl-3"> 
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <h5>Sub Location :</h5><br>
                                <select class="btn btn-success form-control load_site_name_consump text-left" id="stc-agent-sup-std-location"><option>Plese select location first!!!</option>
                                </select> 
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-xl-3"> 
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
                    <div class="col-md-6 col-xl-6 stc-std-section-hideshow"> 
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <h5>Equipment Type :</h5><br>
                                <select class="btn btn-success form-control load_equipment_type_consump text-left" id="stc-agent-sup-std-equipment-type"><option>Please select area first!!!</option>
                                </select> 
                                <!-- <input type="text" class="form-control stc-agent-sup-std-equipment-type" placeholder="Enter Equipment Details"/> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-6 stc-std-section-hideshow"> 
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
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-6 stc-std-section-hideshow"> 
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <h5>Job Varieties:</h5><br>
                                <select class="btn btn-success form-control text-left" id="stc-agent-sup-std-job-varities">
                                    <option value="NA">Please select job type first</option>
                                </select> 
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-6"> 
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <h5>Job Planning (auto save):</h5><br>
                                <select class="btn btn-success form-control text-left" id="stc-agent-sup-std-job-plannning"><option>Preventive Maintenance</option><option>Breakdown Maintenance</option>
                                </select> 
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
                    <div class="col-md-12 col-xl-12 stc-std-section-hideshow"> 
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <p>Below fields are auto save. Please put information and switch to next section your data will be saved automatically.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-6" style="display:none;"> 
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <h5>Equipment Number (auto save):</h5><br>
                                <select class="btn btn-success form-control load_equipment_number_consump text-left" id="stc-agent-sup-std-equipment-number" disabled><option>Please select equipment no first!!!</option>
                                </select> 
                                <!-- <input type="text" class="form-control stc-agent-sup-std-equipment-number" placeholder="Enter Equipment Number"/> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-6 stc-std-section-hideshow"> 
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <h5>Equipment Status (auto save):</h5><br>
                                <select class="btn btn-success form-control text-left stc-std-update-on-change" id="stc-agent-sup-std-equipment-status"><option>Down</option><option>Running</option>
                                </select> 
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-xl-3 stc-std-section-hideshow"> 
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <h5>Quantity (auto save):</h5><br>
                                <input type="number" class="form-control stc-agent-sup-std-qty stc-std-update-on-focusout" placeholder="Enter Quantity">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-xl-3 stc-std-section-hideshow"> 
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <h5>Capacity (auto save):</h5><br>
                                <input type="text" class="form-control stc-agent-sup-std-capacity stc-std-update-on-focusout" placeholder="Enter Capacity">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-xl-4 stc-std-section-hideshow"> 
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <h5>Reason Attribute (auto save):</h5><br>
                                <select class="btn btn-success form-control text-left stc-std-update-on-change" id="stc-agent-sup-std-reasonattribite"><option>GLOBAL</option><option>VOLTAS</option>
                                </select> 
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-xl-4 stc-std-section-hideshow"> 
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <h5>Permit No (auto save):</h5><br>
                                <input type="text" class="form-control stc-agent-sup-std-permit-no stc-std-update-on-focusout" placeholder="Enter Permit No">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-xl-4 stc-std-section-hideshow">  
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <h5>Responsible Person (auto save):</h5><br>
                                <input type="text" class="form-control stc-agent-sup-std-responsive-person stc-std-update-on-focusout" placeholder="Enter Responsive Person">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-6 stc-std-section-hideshow"> 
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <h5>Reason (auto save):</h5><br>
                                <textarea class="form-control stc-std-update-on-focusout" id="stc-agent-sup-std-reason" placeholder="Reason"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-6 stc-std-section-hideshow"> 
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <h5>Material Requisition (auto save):</h5><br>
                                <textarea class="form-control stc-std-update-on-focusout" id="stc-agent-sup-std-material-desc" placeholder="Material Description"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-6 stc-std-section-hideshow"> 
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <h5>Manpower Request (auto save):</h5><br>
                                <input type="text" class="form-control stc-std-update-on-focusout" id="stc-agent-sup-std-manpower-req" placeholder="Manpower Request" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-6 stc-std-section-hideshow"> 
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <h5>Work Completion Target Date (auto save):</h5><br>
                                <input type="date" class="form-control stc-std-update-on-focusout" id="stc-agent-sup-std-target-date" <?php echo date("Y-m-d");?> required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-xl-12 stc-std-section-hideshow"> 
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <h5>Tools Requisitions : <button class="btn btn-primary tools-req-oper-option" operation="show">Show/Hide</button></h5>
                                <p class="stc-std-tools-req-item-show"></p>                                             
                                <div class="show-tools-req-section row">
                                <?php 
                                    $tools_array = array('SIZE SPANNER', 'RING SPANNER', 'T SPANNER', 'TUBE CUTTER', 'SWAGING  TOOL', 'HAMMER 1KG', 'HAMMER  2 KG', 'COPPER PIPE FLARING TOOL', 'REF. CHARGING PIPE', 'ADJUSTABLE SPANNER', 'ALLEN KEY SET MM', 'ALLEN KEY SET INCH', 'TORCH LIGHT', 'RATCHET WRENCH', 'SCREW DRIVER SET', 'DIGITAL CLAMP METER', 'SCREW DRIVER (BIG SIZE)', 'SCREW DRIVER (SMALL SIZE)', 'WIRE CUTTER', 'HP PRESSURE GAUGE', 'LP PRESSURE GAUGE', 'MANIFOLD GAUGE', 'LPG CAN', 'WIRE BRUSH', 'BRAZING GAS TORCH', 'BRAZING GAS TORCH', 'FIN STRAIGHTENER', 'HAND BLOWER(BOSCH)', 'PRESSURE WASHER(SHAKTI)', 'PLIERS', 'NOSE PLIERS', 'VACCUME PUMP', 'CHISEL', 'ROUND FILE TOOL', 'FLAT FILE TOOL', 'ALUMINIUM LADDER ', 'EXTENSION  BOARD', 'BEARING PULLER', 'Chain Block (2 Ton )', 'Welding Machine', 'Grinding Machine. (Bosch Make)', 'Drilling Machine (Ralli Wolf)', 'Pipe Wrench (14", 18", 24", 32")', 'D-Shackle', 'Recovery Machine', 'Empty Cylinder', 'Micron Gauge', 'Vibration Meter', 'Vernier Scale', 'Temp. Gun', 'Tachometer', 'Anemometer', 'Airblowe', 'Vaccume Cleaner');
                                    $sl=0;
                                    foreach($tools_array as $tools_row){
                                        $sl++;
                                        echo '<div class="col-sm-12 col-md-3"> <input type="checkbox" id="for-'.$sl.'" class="stc-agent-sup-std-tools-req" style="position: relative;top: 2px;"  value="'.$tools_row.'"> <label for="for-'.$sl.'">'.$tools_row.'</label></div>';
                                    }
                                ?>
                                    <div class="col-sm-12 col-md-12">
                                        <button class="btn btn-success stc-agent-sup-std-tools-req-btn">Save Tool Requisitions.</button>  
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-xl-12 stc-std-section-hideshow"> 
                        <div class="main-card mb-3 card">
                            <div class="card-body">
                                <h5>Any comment (auto save):</h5><br>
                                <textarea type="text" class="form-control stc-agent-sup-std-remarks stc-std-update-on-focusout" placeholder="Enter Any Comment"></textarea>
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