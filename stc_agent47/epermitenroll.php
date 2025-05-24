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
    }
} 
// If session doesn't exist but cookie does
elseif(isset($_COOKIE["stc_agent_remember"])) {
    // Restore session from cookie
    $_SESSION["stc_agent_id"] = $_COOKIE["stc_agent_remember"];
    // Optionally refresh the cookie
    setcookie("stc_agent_remember", $_COOKIE["stc_agent_remember"], time() + $cookie_duration, "/");
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
    <title>E-Permit Enrollment - STC</title>
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
                        <h3>E-Permit Enrollment</h3>
                    </div>
                    <ul class="body-tabs body-tabs-layout tabs-animated body-tabs-animated nav">
                        <li class="nav-item">
                            <a role="tab" class="nav-link show active" id="tab-1" data-toggle="tab" href="#view-req">
                                <span>View E-Permit Enrollment</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane tabs-animation fade active" id="view-req" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="main-card mb-3 card">
                                        <div class="card-body">
                                            <h5 class="card-title">View E-Permit Enrollment</h5>
                                            <form class="needs-validation" novalidate>
                                                <div class="form-row">
                                                    <div class="col-md-12 mb-3">
                                                        <?php
                                                            $showdate=date('Y-m-d');
                                                            if(isset($_GET['search_date'])){
                                                                $showdate=date('Y-m-d', strtotime($_GET['search_date']));
                                                            }
                                                        ?>
                                                            <input type="date" value='<?php echo $showdate;?>' class='datefilter form-control'>
                                                            <a href="javascript:void(0)" class="datefilterbtn btn btn-primary">Find</a>
                                                        </div>
                                                        <div class="col-md-12 mb-3">
                                                        <table class="table table-bordered table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th colspan="15" class="text-center">E-Permit Enrolment Record</th>
                                                                </tr>
                                                                <tr>
                                                                    <?php 

                                                                        $showdate=date('d-m-Y');
                                                                        $shiftAtotal='';
                                                                        $shiftBtotal='';
                                                                        $shiftCtotal='';
                                                                        $shiftEtotal='';
                                                                        $shiftAdata='';
                                                                        $shiftBdata='';
                                                                        $shiftCdata='';
                                                                        $shiftEdata='';
                                                                        $totalplantentry=0;
                                                                        $shifttdata='';
                                                                        include_once("../MCU/db.php");
                                                                        $filter=' AND DATE(`created_date`)="'.date('Y-m-d').'" AND `dep_id`<>0';
                                                                        $filter2=' AND DATE(`created_date`)="'.date('Y-m-d').'"';
                                                                        if(isset($_GET['search_date'])){
                                                                            $filter=' AND DATE(`created_date`)="'.$_GET['search_date'].'" AND `dep_id`<>0';
                                                                            $filter2=' AND DATE(`created_date`)="'.$_GET['search_date'].'"';
                                                                            $showdate=date('d-m-Y', strtotime($_GET['search_date']));
                                                                        }
                                                                        echo '<th class="text-center">Date</th><th class="text-center">'.$showdate.'</th>';
                                                                        $query="SELECT DISTINCT `dep_id`, `shift`, `stc_status_down_list_department_dept` FROM `stc_epermit_enrollment` LEFT JOIN `stc_status_down_list_department` ON `dep_id`=`stc_status_down_list_department_id` LEFT JOIN `stc_cust_project` ON `stc_cust_project_id`=`stc_status_down_list_department_loc_id` LEFT JOIN `stc_cust_project_collaborate` ON `stc_cust_project_collaborate_projectid`=`stc_status_down_list_department_loc_id` WHERE (`stc_cust_project_collaborate_teamid`='".$_SESSION['stc_agent_id']."' OR `stc_cust_project_createdby`='".$_SESSION['stc_agent_id']."') ".$filter." ORDER BY `stc_status_down_list_department_dept` ASC";
                                                                        $sql=mysqli_query($con, $query);
                                                                        $TotalShiftAcounter=0;
                                                                        $TotalShiftBcounter=0;
                                                                        $TotalShiftCcounter=0;
                                                                        $TotalShiftEcounter=0;
                                                                        $Totalepermitcounter=0;
                                                                        $deptepermitcounter='';
                                                                        $departments = array();
                                                                        $departmentsid = array();
                                                                        $Remarks='';
                                                                        $deptnonenrollmentcounter='';
                                                                        if(mysqli_num_rows($sql)>0){
                                                                            foreach($sql as $row){
                                                                                if (!in_array($row['stc_status_down_list_department_dept'], $departments)) {
                                                                                    $departments[] = $row['stc_status_down_list_department_dept'];
                                                                                    $departmentsid[] = $row['dep_id'];
                                                                                }
                                                                            }
                                                                            foreach($departments as $key=>$department) {
                                                                                $RemarksData='';
                                                                                $ShiftAcounter=0;
                                                                                $ShiftBcounter=0;
                                                                                $ShiftCcounter=0;
                                                                                $ShiftEcounter=0;
                                                                                echo '<th rowspan="2" style="width:145px;">' . $department . '</th>';
                                                                                $dept_id=$departmentsid[$key];
                                                                                $sql2=mysqli_query($con, "SELECT `id`, `shift`, `emp_name`, `created_date` FROM `stc_epermit_enrollment` WHERE `dep_id`='".$dept_id."' ".$filter2."");
                                                                                foreach($sql2 as $row2){
                                                                                    if($row2['shift']=="A"){ $totalplantentry++;$ShiftAcounter++; }
                                                                                    if($row2['shift']=="B"){ $totalplantentry++;$ShiftBcounter++; }
                                                                                    if($row2['shift']=="C"){ $totalplantentry++;$ShiftCcounter++; }
                                                                                    if($row2['shift']=="E (General)"){ $totalplantentry++;$ShiftEcounter++; }
                                                                                }
                                                                                $sql3=mysqli_query($con, "SELECT `id`, `totalpermitenr`, `dep_id`, `remarks` FROM `stc_totalpermitenrollment` WHERE `dep_id`='".$dept_id."' ".$filter2."");
                                                                                $Epermitcounter=0;
                                                                                $rema='';
                                                                                if(mysqli_num_rows($sql3)>0){
                                                                                    foreach($sql3 as $row3){
                                                                                        $Epermitcounter+=$row3['totalpermitenr'];
                                                                                        $Totalepermitcounter+=$row3['totalpermitenr'];
                                                                                        $rema=$row3['remarks'];
                                                                                    }
                                                                                    if($Epermitcounter==0){
                                                                                        $deptepermitcounter.='<td style="font-weight:bold">0</td>';
                                                                                    }else{
                                                                                        $deptepermitcounter.='<td style="font-weight:bold">'.$Epermitcounter.'</td>';
                                                                                        $RemarksData.=$rema;
                                                                                    }
                                                                                }else{
                                                                                    $deptepermitcounter.='<td style="font-weight:bold">0</td>';
                                                                                }
                                                                                $Remarks.='<td>'.$RemarksData.'</td>';
                                                                                $TotalShiftAcounter+=$ShiftAcounter;
                                                                                $TotalShiftBcounter+=$ShiftBcounter;
                                                                                $TotalShiftCcounter+=$ShiftCcounter;
                                                                                $TotalShiftEcounter+=$ShiftEcounter;

                                                                                $shiftAdata.='<td>'.$ShiftAcounter.'</td>';
                                                                                $shiftBdata.='<td>'.$ShiftBcounter.'</td>';
                                                                                $shiftCdata.='<td>'.$ShiftCcounter.'</td>';
                                                                                $shiftEdata.='<td>'.$ShiftEcounter.'</td>';
                                                                                $total = $ShiftAcounter + $ShiftBcounter + $ShiftCcounter + $ShiftEcounter;
                                                                                $shifttdata.='<td style="font-weight:bold">'.$total.'</td>';
                                                                                $totalnonenrollment=($total - $Epermitcounter)>=0?$total - $Epermitcounter:0;
                                                                                $deptnonenrollmentcounter.='<td style="font-weight:bold">'.$totalnonenrollment.'</td>';
                                                                            }
                                                                            $shiftAtotal='<td>'.$TotalShiftAcounter.'</td>';
                                                                            $shiftBtotal='<td>'.$TotalShiftBcounter.'</td>';
                                                                            $shiftCtotal='<td>'.$TotalShiftCcounter.'</td>';
                                                                            $shiftEtotal='<td>'.$TotalShiftEcounter.'</td>';
                                                                        }
                                                                    ?>
                                                                </tr>
                                                                <tr>
                                                                    <th class="text-center" style="width:140px;">Shift</th>
                                                                    <th class="text-center" style="width:140px;">Manpower Entry</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>1st (A)</td>
                                                                    <?php echo $shiftAtotal;?>
                                                                    <?php echo $shiftAdata;?>
                                                                </tr>
                                                                <tr>
                                                                    <td>General (E)</td>
                                                                    <?php echo $shiftEtotal;?>
                                                                    <?php echo $shiftEdata;?>
                                                                </tr>
                                                                <tr>
                                                                    <td>2nd (B)</td>
                                                                    <?php echo $shiftBtotal;?>
                                                                    <?php echo $shiftBdata;?>
                                                                </tr>
                                                                <tr>
                                                                    <td>3rd (C)</td>
                                                                    <?php echo $shiftCtotal;?>
                                                                    <?php echo $shiftCdata;?>
                                                                </tr>
                                                                <tr>
                                                                    <td style="font-weight:bold">Total Entry In Plant</td>
                                                                    <td style="font-weight:bold"><?php echo $totalplantentry;?></td>
                                                                    <?php echo $shifttdata;?>
                                                                </tr>
                                                                <tr>
                                                                    <td style="font-weight:bold">Total E-Permit Enrolment</td>
                                                                    <td style="font-weight:bold"><?php echo $Totalepermitcounter;?></td>
                                                                    <?php echo $deptepermitcounter;?>
                                                                </tr>
                                                                <tr>
                                                                    <td style="font-weight:bold">Total Non Enrolment</td>
                                                                    <td style="font-weight:bold"><?php echo $totalplantentry-$Totalepermitcounter;?></td>
                                                                    <?php echo $deptnonenrollmentcounter;?>
                                                                </tr>
                                                                <tr>
                                                                    <td style="font-weight:bold">Remarks</td>
                                                                    <td></td>
                                                                    <?php echo $Remarks;?>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </form> 
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
            $('body').delegate('.datefilterbtn', 'click', function(e){
                var pd_title=$('.datefilter').val();
                if(pd_title!=""){
                    window.location.href="epermitenroll.php?page=epermitenroll&search_date="+pd_title;
                }
            });    
        });
    </script>
</body>
</html>