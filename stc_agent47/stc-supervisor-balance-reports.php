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
    <title>Reports - STC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />
    <meta name="description" content="">
    <meta name="msapplication-tap-highlight" content="no">
    <!-- Latest compiled and minified CSS -->
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous"> -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="./main.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
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
                            <a role="tab" class="nav-link show active" id="tab-1" data-toggle="tab" href="#view-req">
                                <span>View Requisition</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a role="tab" class="nav-link" id="tab-2" data-toggle="tab" href="#view-cons">
                                <span>View Consumption</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane tabs-animation fade show active" id="view-req" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 col-xl-6 col-sm-12"> 
                                    <div class="card mb-3 widget-content">
                                        <button class="mb-2 mr-2 btn btn-success btn-block">
                                            <?php 
                                              $date = date("d-m-Y");
                                              $newDate = date('Y-m-d', strtotime($date)); 
                                              $effectiveDate = date('Y-m-d', strtotime("-1 months", strtotime($date)));
                                            ?>   
                                            <input 
                                                type="date" 
                                                class="form-control stc-init-date"
                                                value="<?php echo $effectiveDate;?>"
                                            >
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6 col-sm-12"> 
                                    <div class="card mb-3 widget-content">
                                        <button class="mb-2 mr-2 btn btn-success btn-block">
                                            <input 
                                                type="date" 
                                                class="form-control stc-end-date"
                                                value="<?php echo $newDate;?>" 
                                            >
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6 col-sm-12"> 
                                    <div class="card mb-3 widget-content">
                                        <select class="form-control btn btn-secondary stc-agents-pending-items-rep-super-select">
                                            <?php 
                                                include_once("../MCU/db.php");
                                                echo '<option value="0" selected>Please select supervisor!!!</option>';
                                                $stcagentspendreportssup=mysqli_query($con, "
                                                    SELECT `stc_cust_pro_supervisor_id`, `stc_cust_pro_supervisor_fullname` 
                                                    FROM `stc_cust_pro_supervisor` 
                                                    LEFT JOIN `stc_cust_pro_supervisor_collaborate` 
                                                    ON `stc_cust_pro_supervisor_collaborate_userid`=`stc_cust_pro_supervisor_id`
                                                    WHERE `stc_cust_pro_supervisor_created_by`='".$_SESSION['stc_agent_id']."'
                                                    OR `stc_cust_pro_supervisor_collaborate_teamid`='".$_SESSION['stc_agent_id']."'
                                                    ORDER BY `stc_cust_pro_supervisor_fullname` ASC
                                                ");
                                                if(mysqli_num_rows($stcagentspendreportssup)>0){
                                                    foreach($stcagentspendreportssup as $pendrepcheckrow){
                                                        echo '<option align="left" value="'.$pendrepcheckrow['stc_cust_pro_supervisor_id'].'">'.$pendrepcheckrow['stc_cust_pro_supervisor_fullname'].'</option>';
                                                    }
                                                }else{
                                                    echo '<option value="0">No supervisor found!!!</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6 col-sm-12"> 
                                    <div class="card mb-3 widget-content">
                                        <select class="form-control btn btn-secondary stc-agents-pending-items-rep-site-select">
                                            <option value="0" selected>Please Select Site</option>                                        
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-10 col-xl-10 col-sm-12"> 
                                    <div class="card mb-3 widget-content">
                                        <button class="mb-2 mr-2 btn btn-success btn-block stc-reportsFind-hit">
                                            <i class="metismenu-icon pe-7s-search"></i> Find
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-2 col-xl-2 col-sm-12 hidden-excel-section"> 
                                    <div class="card mb-3 widget-content">
                                        <button class="mb-2 mr-2 btn btn-success btn-block stc-req-exportexcel-hit" data-type="excel">
                                            <i class="fa fa-file-excel-o"></i> Export Excel
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xl-12 col-sm-12"> 
                                    <div class="card mb-3 widget-content">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">Pending Items Reports</h5>
                                                <div class="table-responsive stc-reports-pending-view">
                                                    <table class="mb-0 table">
                                                        <thead>
                                                        <tr>
                                                            <th>Search...</th>
                                                        </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane tabs-animation fade" id="view-cons" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 col-xl-6 col-sm-12"> 
                                    <div class="card mb-3 widget-content">
                                        <button class="mb-2 mr-2 btn btn-success btn-block">
                                            <?php 
                                              $date = date("d-m-Y");
                                              $newDate = date('Y-m-d', strtotime($date)); 
                                              $effectiveDate = date('Y-m-d', strtotime("-1 months", strtotime($date)));
                                            ?>   
                                            <input 
                                                type="date" 
                                                class="form-control stc-consumpt-init-date"
                                                value="<?php echo $effectiveDate;?>"
                                            >
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6 col-sm-12"> 
                                    <div class="card mb-3 widget-content">
                                        <button class="mb-2 mr-2 btn btn-success btn-block">
                                            <input 
                                                type="date" 
                                                class="form-control stc-consumpt-end-date"
                                                value="<?php echo $newDate;?>" 
                                            >
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6 col-sm-12"> 
                                    <div class="card mb-3 widget-content">
                                        <select class="form-control btn btn-secondary stc-consumpt-agents-pending-items-rep-super-select">
                                            <?php 
                                                echo '<option value="0" selected>Please select supervisor!!!</option>';
                                                $stcagentspendreportssup=mysqli_query($con, "
                                                    SELECT 
                                                        `stc_cust_pro_supervisor_id`,
                                                        `stc_cust_pro_supervisor_fullname` 
                                                    FROM `stc_cust_pro_supervisor` 
                                                    WHERE `stc_cust_pro_supervisor_created_by`='".$_SESSION["stc_agent_id"]."'
                                                    ORDER BY `stc_cust_pro_supervisor_fullname` ASC
                                                ");
                                                if(mysqli_num_rows($stcagentspendreportssup)>0){
                                                    foreach($stcagentspendreportssup as $pendrepcheckrow){
                                                        echo '<option align="left" value="'.$pendrepcheckrow['stc_cust_pro_supervisor_id'].'">'.$pendrepcheckrow['stc_cust_pro_supervisor_fullname'].'</option>';
                                                    }
                                                }else{
                                                    echo '<option value="0">No supervisor found!!!</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6 col-sm-12"> 
                                    <div class="card mb-3 widget-content">
                                        <select class="form-control btn btn-secondary stc-consumpt-agents-pending-items-rep-site-select">
                                            <option value="0" selected>Please Select Site</option>                                        
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xl-12 col-sm-12"> 
                                    <div class="card mb-3 widget-content">
                                        <button class="mb-2 mr-2 btn btn-success btn-block stc-consumptFind-hit">
                                            <i class="metismenu-icon pe-7s-search"></i> Find
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xl-12 col-sm-12"> 
                                    <div class="card mb-3 widget-content">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">Pending Items Reports</h5>
                                                <div class="table-responsive stc-reports-consump-pending-view">
                                                    <table class="mb-0 table">
                                                        <thead>
                                                        <tr>
                                                            <th>Search...</th>
                                                        </tr>
                                                        </thead>
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
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
    <script type="text/javascript" src="./assets/scripts/main.js"></script>
    <script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
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
            $('.hidden-excel-section').hide();
            // on select super visor site call
            $('body').delegate('.stc-agents-pending-items-rep-super-select', 'change', function(e){
                e.preventDefault();
                var jsstcsupid=$(this).val();
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        get_supervisorconsite:1,
                        stcsupid:jsstcsupid
                    },
                    success     : function(getsiteconsupervisor){
                        $('.stc-agents-pending-items-rep-site-select').html(getsiteconsupervisor);
                    }
                });
            });

            // call reports
            $('body').delegate('.stc-reportsFind-hit', 'click', function(e){
                e.preventDefault();
                $('.hidden-excel-section').show();
                var jsbegdate=$('.stc-init-date').val();
                var jsenddate=$('.stc-end-date').val();
                var jssuperid=$('.stc-agents-pending-items-rep-super-select').val();
                var jsprojeid=$('.stc-agents-pending-items-rep-site-select').val();
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        js_pending_reports_req:1,
                        stc_begdate:jsbegdate,
                        stc_enddate:jsenddate,
                        stc_superid:jssuperid,
                        stc_projeid:jsprojeid
                    },
                    success     : function(reportsfindres){
                        $('.stc-reports-pending-view').html(reportsfindres);
                    }
                });
            });

            // create excel
            $('body').delegate('.stc-req-exportexcel-hit', 'click', function(e){
                e.preventDefault();
                $("#stc-reports-pending-view").table2excel({
                    filename: "stc-req.xls"
                });
            });
        });
    </script>
    <script>
        $(document).ready(function(){
            // call site on change supervisor
            $('body').delegate('.stc-consumpt-agents-pending-items-rep-super-select', 'change', function(e){
                e.preventDefault();
                var jsstcsupid=$(this).val();
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        get_supervisorconsite:1,
                        stcsupid:jsstcsupid
                    },
                    success     : function(getsiteconsupervisor){
                        $('.stc-consumpt-agents-pending-items-rep-site-select').html(getsiteconsupervisor);
                    }
                });
            });

            // call consumption byy supervisor & site
            $('body').delegate('.stc-consumptFind-hit', 'click', function(e){
                e.preventDefault();
                var jsbegdate=$('.stc-consumpt-init-date').val();
                var jsenddate=$('.stc-consumpt-end-date').val();
                var jssuperid=$('.stc-consumpt-agents-pending-items-rep-super-select').val();
                var jsprojeid=$('.stc-consumpt-agents-pending-items-rep-site-select').val();
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        js_pending_consump_reports_req:1,
                        stc_begdate:jsbegdate,
                        stc_enddate:jsenddate,
                        stc_superid:jssuperid,
                        stc_projeid:jsprojeid
                    },
                    success     : function(reportsfindres){
                        $('.stc-reports-consump-pending-view').html(reportsfindres);
                    }
                });
            });
        });
    </script>
</body>
</html>