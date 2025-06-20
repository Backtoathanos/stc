<?php  
session_start(); 
if(isset($_SESSION["stc_empl_id"]) && ($_SESSION["stc_empl_role"]>0)){ 
}else{ 
    header("Location:index.html"); 
} 
?> 
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Language" content="en">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Summary - STC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="">
    <meta name="msapplication-tap-highlight" content="no">
    <link rel = "icon" type = "image/png" href="images/stc_logo_title.png">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="./main.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        /* Existing styles... */
        
        /* New workflow styles */
        .workflow-container {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            padding: 20px 0;
            margin-bottom: 30px;
        }
        
        .workflow-step {
            display: flex;
            align-items: center;
        }
        
        .workflow-box {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px 20px;
            margin: 0 5px;
            min-width: 120px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .workflow-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .workflow-box.active {
            background: #4caf50;
            color: white;
            border-color: #4caf50;
        }
        
        .workflow-box .step-title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
        }
        
        .workflow-box .step-subtitle {
            font-size: 12px;
            color: #666;
        }
        
        .workflow-box.active .step-subtitle {
            color: #e0e0e0;
        }
        
        .workflow-arrow {
            font-size: 20px;
            color: #999;
            margin: 0 5px;
        }
        
        @media (max-width: 768px) {
            .workflow-container {
                flex-direction: column;
                align-items: stretch;
            }
            
            .workflow-step {
                margin-bottom: 10px;
            }
            
            .workflow-arrow {
                transform: rotate(90deg);
                margin: 5px 0;
            }
        }
        #header-fixed {
            position: fixed;
            top: 14%;
            display: none;
            background-color: white;
        }

        tr:nth-child(even) {
          background-color: #AC92EC;
        }
        .stc-purchase-view-table th{
          text-align: center;
        }

        .stc-purchase-view-table{
          font-size: 10px;
        }

        .stc-purchase-view-table td p{
          font-size: 10px;
        }
        
        .fade:not(.show) {
          opacity: 10;
        }

        .message-alert{
            color : red;
        }        

        .stc-datatable-filter-ul li{
            display: none;
        }
        .stc-datatable-filter-ul{
            background: #fbfbfb;
            position: fixed;
            top:10%;
            left:88%;
            z-index: 90;
            width: 195px;
            list-style-type: none;
        }

        .stc-datatable-filter{
            z-index: 90;
        }

        @media print {

            #stc-show-std-detailspre-table ,.print-btn-sdl, .body-tabs, .header-user-info, .desctitlesdl, .print-preview-hide, .sl-hide{ 
                display: none; 
            }

            #stc-show-std-details-table{
                width:100%;
                position:relative;
                left:-60px;
            }

            .card{
                border : 0px solid white;
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
                    <!-- Workflow Visualization -->
                    <div class="workflow-container">
                        <div class="workflow-step">
                            <div class="workflow-box active" data-target="#dcp-tag">
                                <div class="step-title">DCP</div>
                                <div class="step-subtitle">Defective Corrective Preventive</div>
                            </div>
                        </div>
                        
                        <div class="workflow-arrow">→</div>
                        
                        <div class="workflow-step">
                            <div class="workflow-box" data-target="#tbm-tag">
                                <div class="step-title">TBM</div>
                                <div class="step-subtitle">Tools Box Meetings</div>
                            </div>
                        </div>
                        
                        <div class="workflow-arrow">→</div>
                        
                        <div class="workflow-step">
                            <div class="workflow-box" data-target="#requisition-tag">
                                <div class="step-title">Requisitions</div>
                                <div class="step-subtitle">Purchase Requests</div>
                            </div>
                        </div>
                        
                        <div class="workflow-arrow">→</div>
                        
                        <div class="workflow-step">
                            <div class="workflow-box">
                                <div class="step-title">Dispatches</div>
                                <div class="step-subtitle">Material Dispatch & Receipts</div>
                            </div>
                        </div>
                        
                        <div class="workflow-arrow">→</div>
                        
                        <div class="workflow-step">
                            <div class="workflow-box">
                                <div class="step-title">Receivings</div>
                                <div class="step-subtitle">Material Receivings</div>
                            </div>
                        </div>
                        
                        <div class="workflow-arrow">→</div>
                        
                        <div class="workflow-step">
                            <div class="workflow-box">
                                <div class="step-title">Consumptions</div>
                                <div class="step-subtitle">Consumed Materials</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Rest of your content -->
                    <div class="app-page-title">
                        <div id="dcp-tag" class="tab-content">
                            <div class="row desctitlesdl">
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="description" align="center"
                                          >Status Down List
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-md-2 col-xl-2 col-sm-12" style="display:none;"> 
                                    <div class="card-border mb-3 card card-body border-success">
                                        <select class="form-control stc-std-load-status">
                                            <option value="1">DOWN</option>
                                            <option value="2">WORK-IN-PROGRESS</option>
                                            <option value="3">WORK-DONE</option>
                                            <option value="4">WORK-COMPLETE</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xl-12 col-sm-12"> 
                                    <div class="card-border mb-3 card card-body">
                                        <!-- <a href="javascript:void(0)" class="btn btn-success form-control print-btn-sdl">Print</a> -->
                                        <div class="stc-show-std-details">
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr>
                                                        <th class="text-center">SL NO</th>
                                                        <th class="text-center">DATE</th>
                                                        <th class="text-center">LOCATION</th>
                                                        <th class="text-center">EQUIPMENT DETAILS</th>
                                                        <th class="text-center">QTY</th>
                                                        <th class="text-center">CAPACITY</th>
                                                        <th class="text-center">DOWN REASON</th>
                                                        <th class="text-center">STATUS</th>
                                                        <th class="text-center">DELAY(DAYS)</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td colspan="15">
                                                        <h3>Loading.....</h3>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="stc-show-std-detailsprint" style="display:none;"></div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="tbm-tag" class="tab-content">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="main-card mb-3 card">
                                        <div class="card-body"><h5 class="card-title">Tool Box Meeting</h5>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="position-relative form-group">
                                                        <select class="form-control safety-filter-by-location">
                                                            <option value="NA">Select</option>
                                                            <?php 
                                                                include_once("../MCU/db.php");
                                                                $query=mysqli_query($con, "SELECT DISTINCT `stc_safetytbm_loc` FROM `stc_safetytbm` WHERE `stc_safetytbm_loc`<>'' ORDER BY `stc_safetytbm_loc` ASC");
                                                                if(mysqli_num_rows($query)>0){
                                                                    foreach($query as $row){
                                                                        echo '<option>'.$row['stc_safetytbm_loc'].'</option>';
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="position-relative form-group">
                                                        <input type="month" class="form-control safety-filter-by-month" value="<?php echo date("Y-m");?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="position-relative form-group">
                                                        <input type="text" class="form-control safety-filter-by-supervisorname" placeholder="Enter Supervisor Name">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="position-relative form-group">
                                                        <button type="text" class="form-control btn btn-primary safety-filter-by-search">Search</button>
                                                    </div>
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
                    </div>
                </div>  
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script type="text/javascript" src="./assets/scripts/loginopr.js"></script>
    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    <script type="text/javascript" src="./assets/scripts/jarvis.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="./assets/scripts/jquery.table2excel.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/floatthead/2.2.3/jquery.floatThead.min.js"></script>
    <script>
        $(document).ready(function(){
            // Initially hide all tab content except the active one
            $('.tab-content').hide();
            $('#dcp-tag').show(); // show default active tab

            // On box click
            $('.workflow-box[data-target]').click(function(){
                // Remove 'active' from all boxes
                $('.workflow-box').removeClass('active');

                // Add 'active' to the clicked box
                $(this).addClass('active');

                // Hide all tab content
                $('.tab-content').hide();

                // Show the targeted tab
                var target = $(this).data('target');
                $(target).show();
            });
        });
    </script>
    <script>
        // Optional: Add interactivity to workflow steps
        $(document).ready(function() {
            $('.workflow-box').click(function() {
                // Remove active class from all boxes
                $('.workflow-box').removeClass('active');
                // Add active class to clicked box
                $(this).addClass('active');
                
                // Here you could add code to load content for the selected step
                // For example: loadStepContent($(this).find('.step-title').text());
            });
        });
    </script>
    <!-- dcp  -->
    <script>
        $(document).ready(function(){
            $('body').delegate('.print-btn-sdl', 'click', function(){
                $('.jobdonedet-print').show();
                $('.jobdonedet-view').hide();
                Popup($('#stc-show-std-details-table')[0].innerHTML);
                function Popup(data){
                    window.print();
                    return true;
                }
            });


            $('.hidden-project-excel-section').hide();
            var screenwidth=$(window).width();
            var finalwidth=screenwidth - (screenwidth * 0.20);
            var percent=finalwidth/screenwidth * 100;
            // $('.stc-show-std-details').width(finalwidth);
            
            $('body').delegate('.std-filter-department', 'change', function(){
                var values = $(this).val();
                $('.message-alert').remove();
                if(values=="NA"){
                    alert("Please select location first.");
                    $('.std-filter-location').after('<p class="message-alert">Please select Location first.</p>');
                }
            });

            $('body').delegate('.std-filter-location', 'change', function(){
                var location = $(this).val();
                $('.message-alert').remove();
                if(location!="NA"){
                    $.ajax({
                        url     : "kattegat/ragnar_reports.php",
                        method  : "POST",
                        data    : {
                        Stc_std_department:1,
                        location:location
                        },
                        success : function(data){
                            // console.log(data);
                            $('.std-filter-department').html(data);
                        }
                    });
                }else{
                    $('.std-filter-department').html('<option value="NA">SELECT LOCATION FIRST.</option>');
                }
            });

            var filter = 0;
            var pagination=0;
            var type='';
            stc_call_std(filter, type, pagination);
            function stc_call_std(filter, type, pagination){
                var datefrom    =   $(".std-filter-date-from").val();
                var dateto      =   $(".std-filter-date-to").val();
                var location    =   $(".std-filter-location").val();
                var department  =   $(".std-filter-department").val();
                var typeofjob   =   $(".std-filter-typeofjob").val();
                var status      =   $(".std-filter-status").val();
                var filter      =   filter;
                var validated   =   1;
                var message     =   "";
                if((datefrom!='' && dateto=='') || (dateto!='' && datefrom=='')){
                    message ="please fill both date.";
                    validated=0;
                }                
                if(filter>0){
                    $('.filter-message-box').remove();
                    if(datefrom =="" && dateto == "" && location == "NA" && department == "NA" && typeofjob == "NA" && status == "NA"){
                        validated=0;
                        message="Minimum 1 field required for search!!!";
                        $(".std-filter-date-from").after("<p class='filter-message-box' style='color:red'>Select From date.</p>");
                        $(".std-filter-date-to").after("<p class='filter-message-box' style='color:red'>Select To date.</p>");
                        $(".std-filter-location").after("<p class='filter-message-box' style='color:red'>Select Location.</p>");
                        $(".std-filter-department").after("<p class='filter-message-box' style='color:red'>Select Department.</p>");
                        $(".std-filter-typeofjob").after("<p class='filter-message-box' style='color:red'>Select Type of Job.</p>");
                        $(".std-filter-status").after("<p class='filter-message-box' style='color:red'>Select Status.</p>");
                    }
                }
                if(validated==1){
                    $('.btn-close').click();
                    $('.modal-backdrop.fade.show').removeClass('modal-backdrop fade show');
                    $.ajax({
                        url     : "kattegat/ragnar_reports.php",
                        method  : "POST",
                        data    : {
                            Stc_std_details:1,
                            datefrom:datefrom,
                            dateto:dateto,
                            location:location,
                            department:department,
                            typeofjob:typeofjob,
                            status:status,
                            pagination:pagination,
                            filter:filter,
                            type:type
                        },
                        success : function(data){
                            if(type==''){
                                $('.stc-show-std-details').html(data);
                            }else{
                                $('.stc-show-std-detailsprint').html(data);
                                $('.stc-sdl-exportexcel-hit2').click();
                            }
                        }
                    });
                }else{    
                    alert(message);
                }
            }
            
            $('body').delegate('tr', 'click', function(e){
                $('tr').css('background-color', '');
                $(this).css('background-color', 'rgb(221 219 255)');
            });

            $('body').delegate('.show-jobdonedetails', 'click', function(e){
                e.preventDefault();
                var jobdonedet = $(this).attr("data");
                var reportlabel=$(this).attr("label");
                $('.title-barreport').html(reportlabel);
                $('.jobdonedet_para').html(jobdonedet);
                $('.show-jobdonedetailsmodal').modal('show');
            });

            // find std 
            $('body').delegate('.std-filter-find-btn', 'click', function(e){
                e.preventDefault();
                var filter = 1;
                var pagination=0;
                var type='';
                $('.stc-show-std-details').html("Please wait...");
                stc_call_std(filter, type, pagination);
            });
            
            // find std with pagination
            $('body').delegate('.std-filter-find-paginationprev', 'click', function(e){
                e.preventDefault();
                var filter = 0;
                var datefrom    =   $(".std-filter-date-from").val();
                var dateto      =   $(".std-filter-date-to").val();
                var location    =   $(".std-filter-location").val();
                var department  =   $(".std-filter-department").val();
                var typeofjob   =   $(".std-filter-typeofjob").val();
                var status      =   $(".std-filter-status").val();
                if(datefrom =="" && dateto == "" && location == "NA" && department == "NA" && typeofjob == "NA" && status == "NA"){
                    filter=0;
                }else{
                    filter=1;
                }
                var pagination = $(this).attr("data");
                pagination=pagination!=0 ? pagination-20 : pagination;
                $('.std-filter-find-pagination').remove();
                $('.stc-show-std-details').html("Please wait...");
                var type='';
                stc_call_std(filter, type, pagination);
            });
            // find std with pagination
            $('body').delegate('.std-filter-find-pagination', 'click', function(e){
                e.preventDefault();
                var filter = 0;
                var datefrom    =   $(".std-filter-date-from").val();
                var dateto      =   $(".std-filter-date-to").val();
                var location    =   $(".std-filter-location").val();
                var department  =   $(".std-filter-department").val();
                var typeofjob   =   $(".std-filter-typeofjob").val();
                var status      =   $(".std-filter-status").val();
                if(datefrom =="" && dateto == "" && location == "NA" && department == "NA" && typeofjob == "NA" && status == "NA"){
                    filter=0;
                }else{
                    filter=1;
                }
                var pagination = $(this).attr("data");
                $('.std-filter-find-pagination').remove();
                $('.stc-show-std-details').html("Please wait...");
                var type='';
                stc_call_std(filter, type, pagination);
            });
            
            $('body').delegate('.stc-edit-report', 'click', function(e){
                e.preventDefault();
                var sdl_id=$(this).attr('id');
                $('.sdl-hidden-reports').val(sdl_id);
                $('.update-sdlreportmodal').modal();
            });

            $('body').delegate('.sdl-update-reportsdl-save', 'click', function(e){
                var sdl_id=$('.sdl-hidden-reports').val();
                var target_date=$('.std-sdl-target-date').val();
                var remarks=$('.stc-sdl-remarks').val();
                var validated=1;
                $('.message-alert').remove();

                // if(target_date==""){
                //     $('.std-sdl-target-date').after('<p class="message-alert">Select target date.</p>');
                //     validated=0;
                // }

                if(remarks==""){
                    $('.stc-sdl-remarks').after('<p class="message-alert">Enter remarks.</p>');
                    validated=0;
                }
                
                if(validated==1){
                    $.ajax({
                        url     : "kattegat/ragnar_reports.php",
                        method  : "POST",
                        data    : {
                            stc_sdl_update:1,
                            sdl_id:sdl_id,
                            target_date:target_date,
                            remarks:remarks
                        },
                        success : function(data){
                            if(data.trim()=="updated"){
                                alert("Record updated.");
                                $('.sdlurbtn-close').click();
                                stc_call_std(0, 0);
                            }
                        }
                    });
                }else{
                    alert("Empty fields not acceptable.");
                }
            });

            // data table filter
            $('body').delegate('.stc-datatable-filter', 'change', function(e){
                e.preventDefault();
                var value = $(this).val();
                if($(this).prop('checked')==true){
                    // $('.'+value).hide();
                    $('#stc-show-std-details-table td:nth-child('+value+'),th:nth-child('+value+')').hide();
                    $('#stc-show-std-details-table td:nth-child('+value+')').addClass('excel-hide');
                    $('#stc-show-std-details-table th:nth-child('+value+')').addClass('excel-hide');
                }else{
                    // $('.'+value).show();
                    $('#stc-show-std-details-table td:nth-child('+value+'),th:nth-child('+value+')').show();
                    $('#stc-show-std-details-table td:nth-child('+value+')').addClass('excel-hide');
                    $('#stc-show-std-details-table th:nth-child('+value+')').addClass('excel-hide');
                }
            });

            $('body').delegate('.data-fields-display', 'click', function(e){
                e.preventDefault();                
                $('.stc-datatable-filter-ul li').toggle(200);
            });

            $('body').delegate('.filter-span', 'click', function(e){
                e.preventDefault();
                $(this).parent().find('input').click();
            });
            
            // create excel
            $('body').delegate('.stc-sdl-exportexcel-hit2', 'click', function(e){
                e.preventDefault();
                $('.jobdonedet-view').remove();
                $('.excel-hide').remove();
                var table = $(this).prev('#stc-show-std-details-table'); 
                $("#stc-show-std-details-table").table2excel({
                    name: "Status Down List Reports",
                    exclude_inputs: false, 
                    preserveColors: true ,
                    exclude_links: false, 
                    filename: "stc-sdl-reports-" + new Date().toString().replace(/[\-\:\.]/g, "") + ".xls",
                    columns: [0, 1, 2]
                });
                $('.std-filter-find-btn').click();
            });
            
            $('body').delegate('.stc-sdl-exportexcel-hit', 'click', function(e){
                e.preventDefault();                
                var filter = 1;
                var pagination=0;
                var type='print';
                $('.stc-show-std-details').html("Please wait...");
                stc_call_std(filter, type, pagination);
            });

            $('body').delegate('.stc-sdl-material-show-req', 'click', function(e){
                var sdl_id=$(this).attr("id");
                $.ajax({
                    url     : "kattegat/ragnar_reports.php",
                    method  : "POST",
                    data    : {
                        stc_sdl_material_call:1,
                        sdl_id:sdl_id
                    },
                    dataType : "JSON",
                    success : function(data){
                        $('.show-material-list-sdl').html(data.data);
                        $('.downlistno').html('<b>SDL Number : '+data.Downlist+'</b>');
                        $('.downlistdate').html('<b>SDL Date : '+data.downlistdate+'</b>');
                        $('.reqnumber').html('<b>Requisition Number : '+data.reqno+'</b>');
                        $('.reqdate').html('<b>Requisition Date : '+data.reqdate+'</b>');
                        $('.sublocation').html('<b>'+data.sublocation+'</b>');
                        $('.SupName').html('<b>Supervisor Name : '+data.SupName+'</b>');
                        $('.SupContact').html('<b>Supervisor Contact : '+data.SupContact+'</b>');
                    }
                });
            });
        });
    </script>
    <!-- tbm -->
     <script>
        $(document).ready(function(e){
            var location = '';
            var month = '';
            var supervise_name = '';
            $('body').delegate('.safety-filter-by-search', 'click', function() {
                location = $('.safety-filter-by-location').val();
                month = $('.safety-filter-by-month').val();
                supervise_name=$('.safety-filter-by-supervisorname').val();
                // call tbm
                call_tbm(location, month, supervise_name);
            });
            
            function call_tbm(location, month, supervise_name){
                $.ajax({
                    url         : "../stc_agent47/nemesis/stc_project.php",
                    method      : "POST",
                    data        : {stc_safety_calltbm:1, location:location, month:month, supervise_name:supervise_name},
                    success     : function(response_tbm){
                        // console.log(response_tbm);
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
                    url         : "../stc_agent47/nemesis/stc_safety.php",
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
                        if(tbm_entry!=undefined){
                            if(tbm_entry.length>0){
                                var sl=0;
                                for(var i=0; i<tbm_entry.length; i++){
                                    sl++;
                                    tbm_entry_out+='<tr><td>' + sl + '</td><td>' + tbm_entry[i].stc_safetytbm_gateentry_workpermitno + '</td><td>' + tbm_entry[i].stc_safetytbm_gateentry_shift + '</td><td>' + tbm_entry[i].stc_safetytbm_gateentry_time + '</td><td>' + tbm_entry[i].stc_safetytbm_gateentry_supeng_name + '</td></tr>';
                                }
                            }else{
                                tbm_entry_out+='<tr><td>Empty record</td></td>';
                            }
                        }else{
                            tbm_entry_out+='<tr><td>Empty record</td></td>';
                        }
                        $('.stc-safety-gentry-show-table').html(tbm_entry_out);

                        var tbm_responsibilities=response_tbm.tbm_responsibility;
                        var tbm_responsibilities_out='';
                        if(tbm_responsibilities!=undefined){
                            if(tbm_responsibilities.length>0){
                                var sl=0;
                                for(var i=0; i<tbm_responsibilities.length; i++){
                                    sl++;
                                    tbm_responsibilities_out+='<tr><td>' + sl + '</td><td>' + tbm_responsibilities[i].stc_safetytbm_responsibilities_item + '</td><td>' + tbm_responsibilities[i].stc_safetytbm_responsibilities_responsibilities + '</td><td>' + tbm_responsibilities[i].stc_safetytbm_responsibilities_targetdate + '</td></tr>';
                                }
                            }else{
                                tbm_responsibilities_out+='<tr><td>Empty record</td></td>';
                            }
                        }else{
                            tbm_responsibilities_out+='<tr><td>Empty record</td></td>';
                        }
                        $('.stc-tbtm-res-show-table').html(tbm_responsibilities_out);

                        var tbm_ppe_checklist=response_tbm.tbm_ppe_checklist;
                        var tbm_ppe_checklist_out='';                        
                        if(tbm_ppe_checklist!=undefined){
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
                        }else{
                            tbm_ppe_checklist_out+='<tr><td>Empty record</td></td>';
                        }
                        $('.stc-tbtm-ppe-checklist-show-table').html(tbm_ppe_checklist_out);
                    }
                });
            }
            
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
<!-- search filter for status down list -->
<div class="modal fade bd-stdfilter-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Status Down List Filter</h5>
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
                                    <div class="col-md-4 col-sm-12 col-xl-4 mb-4">
                                        <h5 class="card-title">From</h5>
                                        <div class="position-relative form-group">
                                            <input type="date" class="form-control std-filter-date-from" placeholder="search here">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4 mb-4">
                                        <h5 class="card-title">To</h5>
                                        <div class="position-relative form-group">
                                            <input type="date" class="form-control std-filter-date-to" placeholder="search here">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4 mb-4">
                                        <h5 class="card-title">Location</h5>
                                        <div class="position-relative form-group">
                                            <select class="form-control std-filter-location">
                                                <option value="NA">SELECT</option>
                                                <?php 
                                                    include_once("../MCU/db.php");
                                                    $dept_qry=mysqli_query($con, "
                                                        SELECT DISTINCT `stc_status_down_list_plocation`
                                                        FROM `stc_status_down_list`
                                                        WHERE `stc_status_down_list_plocation`<>''
                                                        ORDER BY `stc_status_down_list_plocation` ASC
                                                    ");
                                                    foreach($dept_qry as $dept_row){
                                                        echo '<option>'.$dept_row['stc_status_down_list_plocation'].'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4 mb-4">
                                        <h5 class="card-title">Department</h5>
                                        <div class="position-relative form-group">
                                            <select class="form-control std-filter-department">
                                                <option value="NA">SELECT LOCATION FIRST</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4 mb-4">
                                        <h5 class="card-title">Type Of Job</h5>
                                        <div class="position-relative form-group">
                                            <select class="form-control std-filter-typeofjob">
                                                <option value="NA">SELECT</option>
                                                <option>BREAKDOWN MAINTENANCE</option>
                                                <option>CALL ATTEND</option>
                                                <option>DAILY JOB ACTIVITY</option>
                                                <option>PREVENTIVE MAINTENANCE</option>
                                                <option>WINTER OVERHAULING</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-12 col-xl-4 mb-4">
                                        <h5 class="card-title">STATUS</h5>
                                        <div class="position-relative form-group">
                                            <select class="form-control std-filter-status">
                                                <option value="NA">SELECT</option>
                                                <option value="1">PLANNING</option>
                                                <option value="2">DOWN</option>
                                                <option value="3">WORK-IN-PROGRESS</option>
                                                <option value="4">WORK DONE</option>
                                                <option value="5">WORK COMPLETE</option>
                                                <option value="6">CLOSE</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xl-12 mb-4">
                                        <div class="position-relative form-group">
                                            <button type="submit" class="form-control btn btn-success std-filter-find-btn">Find</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>