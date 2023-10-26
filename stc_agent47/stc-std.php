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
    <title>Status Down List - STC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="">
    <meta name="msapplication-tap-highlight" content="no">
    <!-- Latest compiled and minified CSS -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous"> -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="./main.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        .stc-std-search-result{
/*            width: 1000px;*/
        }
        .fade:not(.show) {
          opacity: 10;
        }

        .tabledata-responsvie{
                overflow-x: auto;
                white-space: nowrap;
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
                    <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                        <li class="nav-item">
                        <li class="nav-item">
                            <a role="tab" class="nav-link show active" id="tab-2" data-toggle="tab" href="#view-sdl">
                                <span>View Status Down List</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane tabs-animation fade show active" id="view-sdl" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12 col-xl-12"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5>Location/Site Name :</h5><br>
                                            <div class="card mb-3 widget-content">
                                                <select class="btn btn-success form-control " id="stc-agent-sup-std-location-find">
                                                    <option value="NA">Select</option>
                                                    <option>TATA Steel - Jamshedpur</option>
                                                    <option>TATA Steel - KPO</option>
                                                    <option>Others</option>
                                                </select> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8 col-xl-8">                                     
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5>Search by choice :</h5><br>
                                            <input type="text" class="form-control stc-agent-sup-search-field" placeholder="Search by choice">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xl-4"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5>Work Status :</h5><br>
                                            <select class="form-control stc-agent-sup-status">
                                                <option value="1">DOWN</option>
                                                <option value="2">WORK-IN-PROGRESS</option>
                                                <option value="3">WORK DONE</option>
                                                <option value="4">WORK COMPLETE</option>
                                                <option value="5">CLOSED</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xl-12"> 
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
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
                                            <div class="card mb-3 widget-content stc-std-search-result tabledata-responsvie" >
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
    </div>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    <script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
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
            var screenwidth=$(window).width();
            var finalwidth=screenwidth - (screenwidth * 0.20);
            var percent=finalwidth/screenwidth * 100;
            $('.stc-std-search-result').width(finalwidth);

            // call status down list
            $('body').delegate('.stc-std-list-show-hit', 'click', function(e){
                e.preventDefault();
                var location_id=$('#stc-agent-sup-std-location-find').val();
                var search=$('.stc-agent-sup-search-field').val();
                var status=$('.stc-agent-sup-status').val();
                if(location_id!="NA"){
                    $.ajax({
                        url         : "nemesis/stc_project.php",
                        method      : "POST",
                        data        : {
                            stc_down_list_hit:1,
                            location_id:location_id,
                            search:search,
                            status:status
                        },
                        success     : function(response_sdl){
                            // console.log(response_sdl);
                            $('.stc-std-search-result').html(response_sdl);
                        }
                    });
                }else{
                    alert("Please select location/sitename");
                }
            });

            // change status
            $('body').delegate('.stc-set-to-complete', 'click', function(e){
                e.preventDefault();
                var sdl_id=$(this).attr("id");
                var status_id='5';
                $(this).hide();
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        stc_status_change_hit:1,
                        status_id:status_id,
                        sdl_id:sdl_id
                    },
                    success     : function(response_sdl){
                        alert(response_sdl);
                    }
                });
            });

            // change status
            $('body').delegate('.stc-set-to-close', 'click', function(e){
                e.preventDefault();
                var sdl_id=$(this).attr("id");
                var status_id='5';
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        stc_status_change_hit:1,
                        status_id:status_id,
                        sdl_id:sdl_id
                    },
                    success     : function(response_sdl){
                        alert(response_sdl);
                    }
                });
            });

            // show jobdone modal
            $('body').delegate('.stc-cust-std-update', 'click', function(e){
                e.preventDefault();
                var sdl_id=$(this).attr("id");
                var sdl_message=$(this).attr("message");
                $('#stc-cust-std-hidden-stdid').val(sdl_id);
                $('.stc-cust-ag-jobdonedetails').val(sdl_message);
                $('.bd-std-jobdonedetails-modal-lg').modal('show');
            });

            // jobdone details save
            $('body').delegate('.stc-cust-ag-jobdonedetails-save', 'click', function(e){
                e.preventDefault();
                var jobdonedetails=$(".stc-cust-ag-jobdonedetails").val();
                var sdl_id=$('#stc-cust-std-hidden-stdid').val();
                if(jobdonedetails==""){
                    alert("Please provide job done details.");
                }else{
                    $.ajax({
                        url         : "nemesis/stc_project.php",
                        method      : "POST",
                        data        : {
                            stc_job_description_change_hit:1,
                            jobdonedetails:jobdonedetails,
                            sdl_id:sdl_id
                        },
                        success     : function(response_sdl){
                            alert(response_sdl);
                            $(".stc-cust-ag-jobdonedetails").val('');
                            $('.bd-std-jobdonedetails-modal-lg').modal('hide');
                        }
                    });
                }
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
                                        <h5 class="card-title" align="center">Job Done Details</h5>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <textarea class="form-control stc-cust-ag-jobdonedetails" placeholder="Enter Job Done Details"></textarea>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12">
                                        <input type="hidden" id="stc-cust-std-hidden-stdid">
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