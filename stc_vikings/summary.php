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
                            <div class="workflow-box" data-target="#dispatches-tag">
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
                            <div class="row desctitlesdl" style="position:absolute;overflow: auto;">
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
                                                        <th class="text-center">LOCATION/PROJECT</th>
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
                        <div id="requisition-tag" class="tab-content">
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="description" align="center"
                                          >Material Requisition Details
                                        </h5>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="stc-mrd-from"
                                          >From
                                        </h5>
                                        <input 
                                            type="date"
                                            id="stc-mrd-from"
                                            class="custom-select stc-mrd-from"
                                            value="<?php echo date('Y-m-d', strtotime('- 15 days', strtotime(date('d-m-Y'))));?>"
                                            name="stc-mrd-from"
                                        >
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="stc-mrd-to"
                                          >To
                                        </h5>
                                        <input 
                                            type="date"
                                            id="stc-mrd-to"
                                            class="custom-select stc-mrd-to"
                                            value="<?php echo date('Y-m-d');?>"
                                            name="stc-mrd-to"
                                        >
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="stc-mrd-tojob"
                                          >Type of Job
                                        </h5>
                                        <select
                                          id="stc-mrd-tojob"
                                          class="custom-select stc-mrd-tojob"
                                          name="stc-mrd-tojob"
                                        ><option value="NA">Select</option>
                                        <option value="1">Project</option>
                                        <option value="2">Service</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-md-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="stc-mrd-customer"
                                          >Customer Name
                                        </h5>
                                        <select
                                          id="stc-mrd-customer"
                                          class="custom-select stc-select-customer"
                                          name="stc-mrd-customer"
                                        ><option value="NA">Please Select Customer</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-md-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="stc-mrd-location"
                                          >Location
                                        </h5>
                                        <select
                                          id="stc-mrd-location"
                                          class="custom-select stc-mrd-lcoation"
                                          name="stc-mrd-location"
                                        ><option value="NA">Please Select Customer First</option>
                                        </select>
                                    </div>
                                </div>       
                                <div class="col-xl-6 col-lg-6 col-md-6 col-md-12 department-section">
                                    <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="stc-mrd-dept"
                                          >Department
                                        </h5>
                                        <select
                                          id="stc-mrd-dept"
                                          class="custom-select stc-mrd-dept"
                                          name="stc-mrd-dept"
                                        ><option value="NA">Please Select Location First</option>
                                        </select>
                                    </div>
                                </div>            
                                <div class="col-xl-6 col-lg-6 col-md-6 col-md-12">
                                    <div class="card-border mb-3 card card-body border-success">
                                        <h5
                                          for="stc-mrd-tomaterial"
                                          >Type of Material
                                        </h5>
                                        <select
                                          id="stc-mrd-tomaterial"
                                          class="custom-select stc-mrd-tomaterial"
                                          name="stc-mrd-tomaterial"
                                        ><option value="NA">Select</option>
                                        <option>Tools</option>
                                        <option>PPE</option>
                                        <option>Consumable</option>
                                        <option>Supply</option>
                                        <option>Storeset</option>
                                        </select>
                                    </div>
                                </div>                         
                                <div class="col-md-10 col-xl-10 col-sm-12"> 
                                    <div class="card-border mb-3 card card-body border-success">
                                        <button class="mb-2 mr-2 btn btn-success btn-block stc-mrd-hit">
                                            <i class="metismenu-icon pe-7s-search"></i> Find
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-2 col-xl-2 col-sm-12 hidden-project-excel-section"> 
                                    <div class="card-border mb-3 card card-body border-success">
                                        <button class="mb-2 mr-2 btn btn-success btn-block stc-mrd-exportexcel-hit" data-type="excel">
                                            <i class="fa fa-file-excel-o"></i> Export Excel
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xl-12 col-sm-12"> 
                                    <div class="card-border mb-3 card card-body border-success">
                                      <table class="mb-0 table table-bordered" id="stc-reports-mrd-view">
                                        <thead>
                                        <tr>
                                            <th class="text-center">Project Name</th>
                                            <th class="text-center">Req No.</th>
                                            <th class="text-center">Date</th>
                                            <th class="text-center">Item Description</th>
                                            <th class="text-center">Unit</th>
                                            <th class="text-center">Supervisor Request. Qty</th>
                                            <th class="text-center">Manager Appr. Qty</th>
                                            <th class="text-center">E-Proc Appr. Qty</th>
                                            <th class="text-center">Priority</th>
                                        </tr>
                                        </thead>
                                        <tbody class="stc-reports-mrd-view">
                                          <tr>
                                            <td colspan="2">
                                              <button type="button" class="btn btn-primary projbegbuttoninvsearch" style="float:right;">
                                                <i class="fas fa-arrow-left"></i>
                                              </button>
                                              <input type="hidden" class="projbegvalueinputsearch" value="0">
                                            </td>
                                            <td colspan="11">
                                              <button type="button" class="btn btn-primary projendbuttoninvsearch">
                                                <i class="fas fa-arrow-right"></i>
                                              </button>
                                              <input type="hidden" class="projendvalueinputsearch" value="30">
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
                    url         : "kattegat/ragnar_summary.php",
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
        });
    </script>
    <!-- mrd detailsfor requisitions -->
     
    <!-- mrd -->    
    <script>
        $(document).ready(function(){
            // call customer
            call_customer();
            function call_customer(){
              $.ajax({
                url     : "kattegat/ragnar_reports.php",
                method  : "POST",
                data    : {Stc_customer_reporsts_call_customer:1}, 
                success : function(data){
                  $('.stc-select-customer').html(data);
                }
              });
            }
            $('body').delegate('#stc-mrd-tojob', 'change', function(e){
                e.preventDefault();
                var tojob = $(this).val();
                if(tojob == "1"){
                    $('.department-section').hide();
                }else{
                   $('.department-section').show();
                }
            });

            // call location for mrd
            $('body').delegate('#stc-mrd-customer', 'change', function(e){
                e.preventDefault();
                var customer_id = $(this).val();
                var tojob = $('#stc-mrd-tojob').val();
                $.ajax({
                    url     : "kattegat/ragnar_summary.php",
                    method  : "post",
                    data    : {
                        stc_mrd_call_location:1,
                        customer_id:customer_id,
                        tojob:tojob
                    },
                    success : function(response){
                        // console.log(response);
                        $('#stc-mrd-location').html(response);
                    }
                });
            });

            // call department for mrd
            $('body').delegate('#stc-mrd-location', 'change', function(e){
                e.preventDefault();
                var location = $(this).val();
                $.ajax({
                    url     : "kattegat/ragnar_reports.php",
                    method  : "post",
                    data    : {
                        stc_mrd_call_dept:1,
                        location:location
                    },
                    success : function(response){
                        // console.log(response);
                        $('#stc-mrd-dept').html(response);
                    }
                });
            });

            // call find for mrd
            $('body').delegate('.stc-mrd-hit', 'click', function (e) {
                e.preventDefault();
                var page = 1;
                get_mrd(page);
            });

            function get_mrd(page){
                var from = $("#stc-mrd-from").val();
                var to = $("#stc-mrd-to").val();
                var tojob = $("#stc-mrd-tojob").val();
                var customer = $("#stc-mrd-customer").val();
                var location = $("#stc-mrd-location").val();
                var dept = $("#stc-mrd-dept option:selected").text();
                var pro_id = $("#stc-mrd-dept").val();
                var tomaterial = $("#stc-mrd-tomaterial").val();
                var page = page || 1; // Current page, default to 1
                var limit = 25; // Number of records per page

                if (from !== "" && to !== "") {
                    $.ajax({
                        url: "kattegat/ragnar_summary.php",
                        method: "post",
                        data: {
                            stc_mrd_call_mrd: 1,
                            from: from,
                            to: to,
                            tojob: tojob,
                            customer: customer,
                            location: location,
                            dept: dept,
                            pro_id: pro_id,
                            tomaterial: tomaterial,
                            page: page,
                            limit: limit
                        },
                        dataType: "JSON",
                        success: function (response) {
                            $('.stc-reports-mrd-view').html(response);
                        }
                    });
                } else {
                    alert("Please select a date.");
                }
            }

            $('body').delegate('.stc-mrd-page', 'click', function (e) {
                e.preventDefault();
                var page = $(this).data('page');
                get_mrd(page);
                // $('.stc-mrd-hit').data('page', page).trigger('click'); // Trigger the main search with updated page
            });


            $('body').delegate('.showmrd-details', 'click', function(e){
                var reqid=$(this).attr('reqnumber');
                var reqdate=$(this).attr('reqdate');
                var reqraisedby=$(this).attr('reqraisedby');
                var reqraisedfrom=$(this).attr('reqraisedfrom');
                var itemdesc=$(this).attr('itemdesc');
                var itemqty=$(this).attr('itemqty');
                var itemunit=$(this).attr('itemunit');
                var itempriority=$(this).attr('itempriority');
                $('.reqnumbershow').val(reqid);
                $('.reqdateshow').val(reqdate);
                $('.reqraisedbyshow').val(reqraisedby);
                $('.reqraisedfromshow').val(reqraisedfrom);
                $('.itemdescshow').val(itemdesc);
                $('.itemqtyshow').val(itemqty);
                $('.itemunitshow').val(itemunit);
                $('.itempriorityshow').val(itempriority);
            });
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
<div class="modal fade bd-example-modal-xl stc-mrdmodal-res" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Material Requisition Details</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xl-12">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xl-6 mb-4">
                                <h5 class="card-title">Requisition Number</h5>
                                <div class="position-relative form-group">
                                    <input type="text" class="reqnumbershow" disabled>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xl-6 mb-4">
                                <h5 class="card-title">Requisition Date</h5>
                                <div class="position-relative form-group">
                                    <input type="text" class="reqdateshow" disabled>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xl-12 mb-4">
                                <h5 class="card-title">Requisition Raised By (User Name)</h5>
                                <div class="position-relative form-group">
                                    <input type="text" class="reqraisedbyshow" disabled>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xl-12 mb-4">
                                <h5 class="card-title">Requisition Raised From (Location)</h5>
                                <div class="position-relative form-group">
                                    <input type="text" class="reqraisedfromshow" disabled>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xl-12 mb-4">
                                <h5 class="card-title">Item Description</h5>
                                <div class="position-relative form-group">
                                    <input type="text" class="itemdescshow" disabled>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xl-6 mb-4">
                                <h5 class="card-title">Item Quantity</h5>
                                <div class="position-relative form-group">
                                    <input type="text" class="itemqtyshow" disabled>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xl-6 mb-4">
                                <h5 class="card-title">Item Unit</h5>
                                <div class="position-relative form-group">
                                    <input type="text" class="itemunitshow" disabled>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xl-12 mb-4">
                                <h5 class="card-title">Priority</h5>
                                <div class="position-relative form-group">
                                    <input type="text" class="itempriorityshow" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>