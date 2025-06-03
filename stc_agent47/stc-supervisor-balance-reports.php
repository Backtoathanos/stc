<?php
session_start();

// Set session and cookie parameters
$session_duration = 24 * 60; // 24 minutes (cPanel limit)
$cookie_duration = 7 * 24 * 60 * 60; // 7 days in seconds

// If session exists
if(isset($_SESSION["stc_agent_id"])) {
    // Check if remember cookie exists, if not set it
    if(!isset($_COOKIE["stc_agent_remember"])) {
        setcookie("stc_agent_remember", $_SESSION["stc_agent_id"], time() + $cookie_duration, "/");
        setcookie("stc_agent_name", $_SESSION["stc_agent_name"], time() + $cookie_duration, "/");
        setcookie("stc_agent_role", $_SESSION["stc_agent_role"], time() + $cookie_duration, "/");
    }
} 
// If session doesn't exist but cookie does
elseif(isset($_COOKIE["stc_agent_remember"])) {
    // Restore session from cookie
    $_SESSION["stc_agent_id"] = $_COOKIE["stc_agent_remember"];
    $_SESSION["stc_agent_name"] = $_COOKIE["stc_agent_name"];
    $_SESSION["stc_agent_role"] = $_COOKIE["stc_agent_role"];
    // Optionally refresh the cookie
    setcookie("stc_agent_remember", $_COOKIE["stc_agent_remember"], time() + $cookie_duration, "/");
    setcookie("stc_agent_name", $_COOKIE["stc_agent_name"], time() + $cookie_duration, "/");
    setcookie("stc_agent_role", $_COOKIE["stc_agent_role"], time() + $cookie_duration, "/");
}
// Neither session nor cookie exists
else {
    header("Location: index.html");
    exit();
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
                        <li class="nav-item">
                            <a role="tab" class="nav-link" id="tab-3" data-toggle="tab" href="#view-atten">
                                <span>Attendance(from E-Permit Enrollment)</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a role="tab" class="nav-link" id="tab-4" data-toggle="tab" href="#ratings">
                                <span>Ratings</span>
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
                                                <div class="stc-reports-pending-view">
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
                        <div class="tab-pane tabs-animation fade" id="view-atten" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 col-xl-6 col-sm-12"> 
                                    <div class="card mb-3 widget-content">
                                        <button class="mb-2 mr-2 btn btn-success btn-block">
                                            <input 
                                                type="month" 
                                                class="form-control attendance-date-select"
                                                value="<?php echo date("Y-m");?>" 
                                            >
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6 col-sm-12"> 
                                    <div class="card mb-3 widget-content">
                                        <button class="mb-2 mr-2 btn btn-success btn-block">
                                            <select class="form-control btn btn-secondary attendance-dept-select">
                                                <?php
                                                    include_once("../MCU/db.php");
                                                    $query="
                                                        SELECT DISTINCT `stc_status_down_list_department_id`, `stc_status_down_list_department_loc_id`, `stc_status_down_list_department_dept`
                                                        FROM `stc_status_down_list_department`
                                                        LEFT JOIN `stc_cust_project_collaborate` 
                                                        ON `stc_cust_project_collaborate_projectid` = `stc_status_down_list_department_loc_id`
                                                        WHERE (`stc_cust_project_collaborate_teamid` = '".$_SESSION['stc_agent_id']."' 
                                                        OR `stc_cust_project_collaborate_managerid` = '".$_SESSION['stc_agent_id']."') 
                                                        ORDER BY `stc_status_down_list_department_dept` ASC
                                                    ";
                                                    $dept_qry=mysqli_query($con, $query);
                                                    foreach($dept_qry as $dept_row){
                                                        echo '<option value="'.$dept_row['stc_status_down_list_department_id'].'">'.$dept_row['stc_status_down_list_department_dept'].'</option>';
                                                    }
                                                ?>                  
                                            </select>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xl-12 col-sm-12"> 
                                    <div class="card mb-3 widget-content">
                                        <button class="mb-2 mr-2 btn btn-success btn-block stc-attendance-hit">
                                            <i class="metismenu-icon pe-7s-search"></i> Find
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xl-12 col-sm-12"> 
                                    <div class="card mb-3 widget-content">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">Pending Items Reports</h5>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered display-attendance">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl No</th>
                                                                <th>Location</th>
                                                                <th>Department</th>
                                                                <th>Employee Name</th>
                                                                <th>1</th><th>2</th><th>3</th><th>4</th><th>5</th>
                                                                <th>6</th><th>7</th><th>8</th><th>9</th><th>10</th>
                                                                <th>11</th><th>12</th><th>13</th><th>14</th><th>15</th>
                                                                <th>16</th><th>17</th><th>18</th><th>19</th><th>20</th>
                                                                <th>21</th><th>22</th><th>23</th><th>24</th><th>25</th>
                                                                <th>26</th><th>27</th><th>28</th><th>29</th><th>30</th>
                                                                <th>31</th>
                                                                <th>Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane tabs-animation fade" id="ratings" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 col-xl-6 col-sm-12"> 
                                    <div class="card mb-3 widget-content">
                                        <button class="mb-2 mr-2 btn btn-success btn-block">
                                            <input 
                                                type="month" 
                                                class="form-control ratings-attendance-date-select"
                                                value="<?php echo date("Y-m");?>" 
                                            >
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xl-6 col-sm-12"> 
                                    <div class="card mb-3 widget-content">
                                        <button class="mb-2 mr-2 btn btn-success btn-block">
                                            <select class="form-control btn btn-secondary ratings-attendance-dept-select">
                                                <?php
                                                    include_once("../MCU/db.php");
                                                    $query="
                                                        SELECT DISTINCT `stc_status_down_list_department_id`, `stc_status_down_list_department_loc_id`, `stc_status_down_list_department_dept`
                                                        FROM `stc_status_down_list_department`
                                                        LEFT JOIN `stc_cust_project_collaborate` 
                                                        ON `stc_cust_project_collaborate_projectid` = `stc_status_down_list_department_loc_id`
                                                        WHERE (`stc_cust_project_collaborate_teamid` = '".$_SESSION['stc_agent_id']."' 
                                                        OR `stc_cust_project_collaborate_managerid` = '".$_SESSION['stc_agent_id']."') 
                                                        ORDER BY `stc_status_down_list_department_dept` ASC
                                                    ";
                                                    $dept_qry=mysqli_query($con, $query);
                                                    foreach($dept_qry as $dept_row){
                                                        echo '<option value="'.$dept_row['stc_status_down_list_department_id'].'">'.$dept_row['stc_status_down_list_department_dept'].'</option>';
                                                    }
                                                ?>                  
                                            </select>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xl-12 col-sm-12"> 
                                    <div class="card mb-3 widget-content">
                                        <button class="mb-2 mr-2 btn btn-success btn-block stc-ratings-hit">
                                            <i class="metismenu-icon pe-7s-search"></i> Find
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xl-12 col-sm-12"> 
                                    <div class="card mb-3 widget-content">
                                        <div class="main-card mb-3 card">
                                            <div class="card-body"><h5 class="card-title">Pending Items Reports</h5>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered ratings-display-attendance">
                                                        <thead>
                                                            <tr>
                                                                <th>Sl No</th>
                                                                <th>Location</th>
                                                                <th>Department</th>
                                                                <th>Employee Name</th>
                                                                <th>1</th><th>2</th><th>3</th><th>4</th><th>5</th>
                                                                <th>6</th><th>7</th><th>8</th><th>9</th><th>10</th>
                                                                <th>11</th><th>12</th><th>13</th><th>14</th><th>15</th>
                                                                <th>16</th><th>17</th><th>18</th><th>19</th><th>20</th>
                                                                <th>21</th><th>22</th><th>23</th><th>24</th><th>25</th>
                                                                <th>26</th><th>27</th><th>28</th><th>29</th><th>30</th>
                                                                <th>31</th>
                                                                <th>Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
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
        $(document).ready(function(){
            var url = document.location.href;
            var qs = url.substring(url.indexOf('?') + 1).split('&');
            for(var i = 0, result = {}; i < qs.length; i++){
                qs[i] = qs[i].split('=');
                result[qs[i][0]] = decodeURIComponent(qs[i][1]);
            }
            $("."+result.page).addClass("mm-active");
            $("."+result.subpage).addClass("mm-active");
            if(result.page==undefined){
                $(".home").addClass("mm-active");
            }
        });
    </script>
    <script>
        $(document).ready(function(){
            $(window).bind("scroll", function() {
                var offset = $(this).scrollTop();
                if(offset>500){
                    $("#stc-reports-pending-view thead").css('position', 'sticky');
                    $("#stc-reports-pending-view thead").css('background', 'white');
                    $("#stc-reports-pending-view thead").css('top', '8%');
                }else{
                    $("#stc-reports-pending-view thead").css('position', 'relative');
                    $("#stc-reports-pending-view thead").removeProp('background');
                    $("#stc-reports-pending-view thead").removeProp('top');
                }
            });

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

            // call consumption byy supervisor & site
            $('body').delegate('.stc-attendance-hit', 'click', function(e){
                e.preventDefault();
                var dept=$('.attendance-dept-select').val();
                var date=$('.attendance-date-select').val();
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        js_search_attendance:1,
                        dept:dept,
                        date:date
                    },
                    success     : function(reportsfindres){
                        $('.display-attendance').html(reportsfindres);
                    }
                });
            });  

            // call consumption byy supervisor & site
            $('body').delegate('.stc-ratings-hit', 'click', function(e){
                e.preventDefault();
                var dept=$('.ratings-attendance-dept-select').val();
                var date=$('.ratings-attendance-date-select').val();
                $.ajax({
                    url         : "nemesis/stc_project.php",
                    method      : "POST",
                    data        : {
                        js_search_ratings:1,
                        dept:dept,
                        date:date
                    },
                    success     : function(reportsfindres){
                        $('.ratings-display-attendance').html(reportsfindres);
                    }
                });
            });
        });
    </script>
</body>
</html>