<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*------------------------------------------For Agents--------------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
class transformers extends tesseract{
    // call location department	
	public function stc_call_deptloc($loca_id){
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT  `stc_status_down_list_department_dept`, `stc_status_down_list_department_id`
			FROM `stc_cust_pro_attend_supervise`
			INNER JOIN `stc_cust_project` 
			ON `stc_cust_project_id`=`stc_cust_pro_attend_supervise_pro_id` 
			INNER JOIN `stc_status_down_list_department` 
			ON `stc_cust_project_id`=`stc_status_down_list_department_loc_id` 			
			WHERE `stc_status_down_list_department_location`='".mysqli_real_escape_string($this->stc_dbs, $loca_id)."'
			AND `stc_cust_pro_attend_supervise_super_id`='".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_agent_sub_id'])."'
		");
		$optimusprime = "<option value='NA'>Select</option>";
		$do_action=mysqli_num_rows($optimusprimequery);
		if($do_action == 0){
			$optimusprime = "<option data-id='0'>No Deparmtent Found.</option>";
		}else{
			foreach ($optimusprimequery as $row) {
				$optimusprime.='<option data-id="'.$row['stc_status_down_list_department_id'].'">'.$row['stc_status_down_list_department_dept'].'</option>';		
			}			
		}
		return $optimusprime;
	}
	// call sitename
	public function stc_call_epermitenroll($begdate, $enddate, $shift){
        $shift=$shift!="NA"? '`shift`="'.$shift.'" AND ':'';
        $filter='WHERE '.$shift.'DATE(`created_date`)="'.$enddate.'" AND `stc_status_down_list_department_id` IN ( SELECT DISTINCT `stc_status_down_list_department_id` FROM `stc_cust_pro_attend_supervise` INNER JOIN `stc_status_down_list_department`  ON `stc_cust_pro_attend_supervise_pro_id` = `stc_status_down_list_department_loc_id` WHERE `stc_cust_pro_attend_supervise_super_id`='.$_SESSION['stc_agent_sub_id'].')'; 
        $countPEntry = 0;
        if($_SESSION['stc_agent_sub_category']=='Supervisor' || $_SESSION['stc_agent_sub_category']=='Site Incharge'){
            if($enddate==''){
                $enddate=DATE('Y-m-d');
                $filter='WHERE '.$shift.'DATE(`created_date`)="'.$enddate.'" AND `stc_status_down_list_department_id` IN ( SELECT DISTINCT `stc_status_down_list_department_id` FROM `stc_cust_pro_attend_supervise` INNER JOIN `stc_status_down_list_department`  ON `stc_cust_pro_attend_supervise_pro_id` = `stc_status_down_list_department_loc_id` WHERE `stc_cust_pro_attend_supervise_super_id`='.$_SESSION['stc_agent_sub_id'].')'; 
            }
        }else{
            if($enddate==''){
                $filter='WHERE '.$shift.'DATE(`created_date`)="'.$enddate.'" AND `created_by`="'.$_SESSION['stc_agent_sub_id'].'"';
            }else{
                $filter.=' AND `created_by`="'.$_SESSION['stc_agent_sub_id'].'"';
            }
        }
        $query="
            SELECT `id`, `location`, `stc_status_down_list_department_dept`, `emp_name`, `gpno`, `shift`, `status`, `created_date`, `created_by` FROM `stc_epermit_enrollment` LEFT JOIN `stc_status_down_list_department` ON `dep_id`=`stc_status_down_list_department_id` LEFT JOIN `stc_cust_pro_supervisor` ON `stc_cust_pro_supervisor_id`=`created_by`LEFT JOIN `stc_agents` ON `stc_cust_pro_supervisor_created_by`=`stc_agents_id` ".$filter." ORDER BY `emp_name` ASC
        ";
		$optimusprimequery=mysqli_query($this->stc_dbs, $query);
		$optimusprime='
            <table class="table table-bordered stc-epermitenrollment-result-table">
                <thead>
                    <tr>
                        <th class="text-center"><a href="javascript:void(0)" class="btn btn-primray sort">SL NO<i class="fa fa-sort"></i></a></th>
                        <th class="text-center"><a href="javascript:void(0)" class="btn btn-primray sort">DATE<i class="fa fa-sort"></i></a></th>
                        <th class="text-center"><a href="javascript:void(0)" class="btn btn-primray sort">LOCATION<i class="fa fa-sort"></i></a></th>
                        <th class="text-center"><a href="javascript:void(0)" class="btn btn-primray sort">DEPARTMENT<i class="fa fa-sort"></i></a></th>
                        <th class="text-center"><a href="javascript:void(0)" class="btn btn-primray sort">EMPLOYEE NAME<i class="fa fa-sort"></i></a></th>
                        <th class="text-center"><a href="javascript:void(0)" class="btn btn-primray sort">GP NO<i class="fa fa-sort"></i></a></th>
                        <th class="text-center"><a href="javascript:void(0)" class="btn btn-primray sort">SHIFT<i class="fa fa-sort"></i></a></th>
                        <th class="text-center"><a href="javascript:void(0)" class="btn btn-primray sort">STATUS<i class="fa fa-sort"></i></a></th>
                        <th class="text-center"><a href="javascript:void(0)" class="btn btn-primray sort">ACTION</a></th>
                    </tr>
                </thead>
                <tbody>
        ';
		$do_action=mysqli_num_rows($optimusprimequery);
		if($do_action == 0){
			$optimusprime .= "<tr><td colspan='8' class='text-center'>No records found.</td></tr>";
		}else{
            $slno = 0;
            $todayDate = date('d-m-Y');

            foreach ($optimusprimequery as $row) {
                $slno++;
                if ($todayDate == date('d-m-Y', strtotime($row['created_date']))) {
                    $countPEntry++;
                }
                $status=$row['status']==1?"Active":"Draft";
				$optimusprime .= "
                    <tr>
                        <td class='text-center'>".$slno."</td>
                        <td class='text-center'>".date('d-m-Y', strtotime($row['created_date']))."</td>
                        <td class='text-center'>".$row['location']."</td>
                        <td class='text-center'>".$row['stc_status_down_list_department_dept']."</td>
                        <td class='text-left'>".$row['emp_name']."</td>
                        <td class='text-center'>".$row['gpno']."</td>
                        <td class='text-center'>".$row['shift']."</td>
                        <td class='text-center'>".$status."</td>
                        <td class='text-center'><a href='javascript:void(0)' class='btn btn-danger epremit-deletebtn' id='".$row['id']."'><i class='fa fa-trash'></i></a></td>
                    </tr>";			
			}
		}
        $optimusprime.='
                </tbody>
            </table>
        ';
        $optimusprimearray = array(
            'optimusprime' => $optimusprime,
            'totalpentry' => $countPEntry
        );
		return $optimusprimearray;
	}

    // save permit enrollment
    public function stc_save_epermitenroll($location, $dept, $name, $phno, $email, $uid, $gpno, $shift){
        $optimusprime = "";
        $currentDateTime = date("Y-m-d H:i:s");
        $eightHoursAgo = date("Y-m-d H:i:s", strtotime('-8 hours'));

        // Check if a similar record exists within the last 8 hours
        $duplicateCheckQuery = "SELECT COUNT(*) as count FROM `stc_epermit_enrollment` WHERE `emp_name` = UCASE('".mysqli_real_escape_string($this->stc_dbs, $name)."') AND `gpno` = UCASE('".mysqli_real_escape_string($this->stc_dbs, $gpno)."') AND `created_date` >= '$eightHoursAgo'";
        $duplicateCheckResult = mysqli_query($this->stc_dbs, $duplicateCheckQuery);
        $row = mysqli_fetch_assoc($duplicateCheckResult);
        $duplicateCount = $row['count'];

        if ($duplicateCount > 0) {
            $optimusprime = "Duplicate";
        } else {
            $userid=0;
            // Check user table for phone number or UID
            $userCheckQuery = "SELECT `stc_cust_pro_supervisor_id` FROM `stc_cust_pro_supervisor` WHERE `stc_cust_pro_supervisor_contact` = '".mysqli_real_escape_string($this->stc_dbs, $phno)."' OR `stc_cust_pro_supervisor_uid` = '".mysqli_real_escape_string($this->stc_dbs, $uid)."' OR `stc_cust_pro_supervisor_email` = '".mysqli_real_escape_string($this->stc_dbs, $email)."'";
            $userCheckResult = mysqli_query($this->stc_dbs, $userCheckQuery);

            if (mysqli_num_rows($userCheckResult)==0) {
                $createruserid=$_SESSION['stc_agent_sub_id'];
                $userCheckQuery = "SELECT `stc_cust_pro_supervisor_cust_id`, `stc_cust_pro_supervisor_created_by` FROM `stc_cust_pro_supervisor` WHERE `stc_cust_pro_supervisor_id` = '".mysqli_real_escape_string($this->stc_dbs, $createruserid)."'";
                $userCheckResult = mysqli_query($this->stc_dbs, $userCheckQuery);
                $userRow = mysqli_fetch_assoc($userCheckResult);
                $cust_id=$userRow['stc_cust_pro_supervisor_cust_id'];
                $man_id=$userRow['stc_cust_pro_supervisor_created_by'];
                // Insert into user table if data does not exist
                $insertUserQuery = "INSERT INTO `stc_cust_pro_supervisor`(`stc_cust_pro_supervisor_cust_id`, `stc_cust_pro_supervisor_fullname`, `stc_cust_pro_supervisor_uid`, `stc_cust_pro_supervisor_contact`, `stc_cust_pro_supervisor_whatsapp`, `stc_cust_pro_supervisor_email`, `stc_cust_pro_supervisor_password`, `stc_cust_pro_supervisor_status`, `stc_cust_pro_supervisor_category`, `stc_cust_pro_supervisor_created_by`) VALUES ('".mysqli_real_escape_string($this->stc_dbs, $cust_id)."','".mysqli_real_escape_string($this->stc_dbs, $name)."','".mysqli_real_escape_string($this->stc_dbs, $uid)."','".mysqli_real_escape_string($this->stc_dbs, $phno)."', '".mysqli_real_escape_string($this->stc_dbs, $phno)."', '".mysqli_real_escape_string($this->stc_dbs, $email)."', '123456', '1', 'Operator', '".mysqli_real_escape_string($this->stc_dbs, $man_id)."')";
                $insertUserResult = mysqli_query($this->stc_dbs, $insertUserQuery);
            }else{
                $userRow = mysqli_fetch_assoc($userCheckResult);
                $userid = $userRow['stc_cust_pro_supervisor_id'];
            }
            $getdept=mysqli_query($this->stc_dbs, "SELECT `stc_status_down_list_department_loc_id` FROM `stc_status_down_list_department` WHERE `stc_status_down_list_department_id`='".mysqli_real_escape_string($this->stc_dbs, $dept)."'");
            $getdeptrow = mysqli_fetch_assoc($getdept);
            $loc_id = $getdeptrow['stc_status_down_list_department_loc_id'];
            
            $getlocation=mysqli_query($this->stc_dbs, "SELECT distinct `stc_cust_pro_attend_supervise_id` FROM `stc_cust_pro_attend_supervise` WHERE `stc_cust_pro_attend_supervise_pro_id`='".mysqli_real_escape_string($this->stc_dbs, $loc_id)."' AND `stc_cust_pro_attend_supervise_super_id`='".mysqli_real_escape_string($this->stc_dbs, $userid)."'");
            if(mysqli_num_rows($getlocation)==0){
                $userCheckQuery = "SELECT `stc_cust_pro_supervisor_id` FROM `stc_cust_pro_supervisor` WHERE `stc_cust_pro_supervisor_contact` = '".mysqli_real_escape_string($this->stc_dbs, $phno)."' OR `stc_cust_pro_supervisor_uid` = '".mysqli_real_escape_string($this->stc_dbs, $uid)."'";
                $userCheckResult = mysqli_query($this->stc_dbs, $userCheckQuery);
                $userCheckQueryrow = mysqli_fetch_assoc($userCheckResult);
                $userid = $userCheckQueryrow['stc_cust_pro_supervisor_id'];
                mysqli_query($this->stc_dbs, "INSERT INTO `stc_cust_pro_attend_supervise`(`stc_cust_pro_attend_supervise_pro_id`, `stc_cust_pro_attend_supervise_super_id`, `stc_cust_pro_attend_supervise_status`) VALUES ('".mysqli_real_escape_string($this->stc_dbs, $loc_id)."', '".mysqli_real_escape_string($this->stc_dbs, $userid)."', '1')");
            }

            // No duplicate found, proceed with insertion into epermit_enrollment
            $insertQuery = "INSERT INTO `stc_epermit_enrollment`(`location`, `dep_id`, `emp_id`, `emp_name`, `gpno`, `shift`, `created_date`, `created_by`) VALUES ('".mysqli_real_escape_string($this->stc_dbs, $location)."', '".mysqli_real_escape_string($this->stc_dbs, $dept)."',  '".mysqli_real_escape_string($this->stc_dbs, $userid)."', UCASE('".mysqli_real_escape_string($this->stc_dbs, $name)."'), UCASE('".mysqli_real_escape_string($this->stc_dbs, $gpno)."'), '".mysqli_real_escape_string($this->stc_dbs, $shift)."', '".$currentDateTime."', '".$_SESSION['stc_agent_sub_id']."')";

            $insertResult = mysqli_query($this->stc_dbs, $insertQuery);
            if ($insertResult) {
                $optimusprime = "Success";
            } else {
                $optimusprime = "failed";
            }
        }

        return $optimusprime;
    }

    
    public function stc_save_epermitenroll_multi($location, $dept, $name, $gpno, $user, $shift){
        $optimusprime = "";
        $currentDateTime = date("Y-m-d H:i:s");
        $eightHoursAgo = date("Y-m-d H:i:s", strtotime('-8 hours'));

        // Check if a similar record exists within the last 8 hours
        $duplicateCheckQuery = "SELECT COUNT(*) as count FROM `stc_epermit_enrollment` WHERE `emp_id` = '".mysqli_real_escape_string($this->stc_dbs, $user)."' AND `created_date` >= '$eightHoursAgo'";
        $duplicateCheckResult = mysqli_query($this->stc_dbs, $duplicateCheckQuery);
        $row = mysqli_fetch_assoc($duplicateCheckResult);
        $duplicateCount = $row['count'];

        if ($duplicateCount > 0) {
            $optimusprime = "Duplicate";
        } else {
            // No duplicate found, proceed with insertion into epermit_enrollment
            $insertQuery = "INSERT INTO `stc_epermit_enrollment`(`location`, `dep_id`, `emp_id`, `emp_name`, `gpno`, `shift`, `created_date`, `created_by`) VALUES ('".mysqli_real_escape_string($this->stc_dbs, $location)."', '".mysqli_real_escape_string($this->stc_dbs, $dept)."',  '".mysqli_real_escape_string($this->stc_dbs, $user)."', UCASE('".mysqli_real_escape_string($this->stc_dbs, $name)."'), UCASE('".mysqli_real_escape_string($this->stc_dbs, $gpno)."'), '".mysqli_real_escape_string($this->stc_dbs, $shift)."', '".$currentDateTime."', '".$_SESSION['stc_agent_sub_id']."')";

            $insertResult = mysqli_query($this->stc_dbs, $insertQuery);
            if ($insertResult) {
                $optimusprime = "Success";
            } else {
                $optimusprime = "failed";
            }
        }

        return $optimusprime;
    }

    public function stc_save_totalpermitenr($totalpermitenr, $location, $dept, $epermitno, $remarks){
        $optimusprime = "";
        $currentDateTime = date("Y-m-d H:i:s");
        $qry="
            INSERT INTO `stc_totalpermitenrollment`(`totalpermitenr`, `location`, `dep_id`, `epermitno`, `remarks`, `created_date`, `created_by`) VALUES ('".mysqli_real_escape_string($this->stc_dbs, $totalpermitenr)."', '".mysqli_real_escape_string($this->stc_dbs, $location)."', '".mysqli_real_escape_string($this->stc_dbs, $dept)."', '".mysqli_real_escape_string($this->stc_dbs, $epermitno)."', '".mysqli_real_escape_string($this->stc_dbs, $remarks)."', '".$currentDateTime."', '".$_SESSION['stc_agent_sub_id']."')
        ";
        $optimusprimequery=mysqli_query($this->stc_dbs, $qry);
        if($optimusprimequery){
            $optimusprime="Success";
        }else{
            $optimusprime="Failed";
        }
        return $optimusprime;
    }

    // call total epermit enrollment
	public function stc_call_totalpermitenr(){
        $date=date('Y-m-d');
        $slno = 0;
        $counter=0;
        $epermit='';
        $remarks='';
        $query="
            SELECT `id`, `totalpermitenr`, `epermitno`, `remarks`, `created_date`, `created_by` 
            FROM `stc_totalpermitenrollment`
            WHERE `created_by`='".$_SESSION['stc_agent_sub_id']."' AND DATE(`created_date`)='".$date."'
            ORDER BY `id` ASC
        ";
		$optimusprimequery=mysqli_query($this->stc_dbs, $query);
		$optimusprime='
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">SL NO</th>
                        <th class="text-center">DATE</th>
                        <th class="text-center">TOTAL E-PERMIT ENROLLMENT</th>
                    </tr>
                </thead>
                <tbody>
        ';
		$do_action=mysqli_num_rows($optimusprimequery);
		if($do_action == 0){
			$optimusprime .= "<tr><td colspan='3' class='text-center'>No records found.</td></tr>";
		}else{
            foreach ($optimusprimequery as $row) {
                $slno++;
                $counter+=$row['totalpermitenr'];
                $remarks=$row['remarks'];
                $epermit=$epermit=='' ? $row['epermitno'] : " ,".$row['epermitno'];
				$optimusprime .= "
                    <tr>
                        <td class='text-center'>".$slno."</td>
                        <td class='text-center'>".date('d-m-Y H:i:s a', strtotime($row['created_date']))."</td>
                        <td class='text-center'>".$row['totalpermitenr']."</td>
                    </tr>";			
			}
		}
        $optimusprime.='
                </tbody>
            </table>
        ';
        $optimusprimearr = array(
            'optimusprime' => $optimusprime,
            'epermit' => $epermit,
            'remarks' => $remarks,
            'totalpenrollment' => $counter
        );
		return $optimusprimearr;
	}

    public function stc_delete_epermit_attendance($id){
        $optimusprime='';
        $optimusprime_query=mysqli_query($this->stc_dbs, "DELETE FROM `stc_epermit_enrollment` WHERE `id`='".mysqli_real_escape_string($this->stc_dbs, $id)."'");
        if($optimusprime_query){
            $optimusprime="Record deleted.";
        }else{
            $optimusprime="Something went wrong, please re login and try again.";
        }
        return $optimusprime;
    }
}

/*-----------------------------------------------------------------------------------*/
/*---------------------------------For Order items-----------------------------------*/
/*-----------------------------------------------------------------------------------*/
// call departemnt
if(isset($_POST['call_department'])){
	$loca_id=$_POST['loca_id'];
	$metabots=new transformers();
	$opmetabots=$metabots->stc_call_deptloc($loca_id);
	echo json_encode($opmetabots);
}

// call sitename
if(isset($_POST['show_epermitenroll'])){
    $begdate=date('Y-m-d', strtotime($_POST['begdate']));
    $enddate=date('Y-m-d', strtotime($_POST['enddate']));
    $shift=$_POST['shift'];
	$metabots=new transformers();
	$opmetabots=$metabots->stc_call_epermitenroll($begdate, $enddate, $shift);
	echo json_encode($opmetabots);
}

// save permit enrollment
if(isset($_POST['save_permitenr'])){
    $location=$_POST['location'];
    $dept=$_POST['dept'];
    $name=$_POST['name'];
    $phno=$_POST['phno'];
    $email=$_POST['email'];
    $uid=$_POST['uid'];
    $gpno=$_POST['gpno'];
    $shift=$_POST['shift'];
    $out='';
    if(!isset($_SESSION['stc_agent_sub_id'])){
        $out="login";
    }else{
        $metabots=new transformers();
        $opmetabots=$metabots->stc_save_epermitenroll($location, $dept, $name, $phno, $email, $uid, $gpno, $shift);
        $out=$opmetabots;
    }
    echo json_encode($out);
}

// save permit enrollment
if(isset($_POST['save_permitenr_multi'])){
    $location=$_POST['location'];
    $dept=$_POST['dept'];
    $name=$_POST['name'];
    $gpno=$_POST['gpno'];
    $user=$_POST['user'];
    $shift=$_POST['shift'];
    $out='';
    if(!isset($_SESSION['stc_agent_sub_id'])){
        $out="login";
    }else{
        $metabots=new transformers();
        $opmetabots=$metabots->stc_save_epermitenroll_multi($location, $dept, $name, $gpno, $user, $shift);
        $out=$opmetabots;
    }
    echo json_encode($out);
}

// save permit enrollment
if(isset($_POST['save_totalpermitenr'])){
    $totalpermitenr=$_POST['totalpermitenr'];
    $location=$_POST['location'];
    $dept=$_POST['dept'];
    $epermitno=$_POST['epermitno'];
    $remarks=$_POST['remarks'];
    $out='';
    if($totalpermitenr=="NA"){
        $out="empty";
    }else if(!isset($_SESSION['stc_agent_sub_id'])){
        $out="login";
    }else{
        $metabots=new transformers();
        $opmetabots=$metabots->stc_save_totalpermitenr($totalpermitenr, $location, $dept, $epermitno, $remarks);
        $out=$opmetabots;
    }
    echo json_encode($out);
}


if(isset($_POST['show_totalepermitenroll'])){
	$metabots=new transformers();
	$opmetabots=$metabots->stc_call_totalpermitenr();
	echo json_encode($opmetabots);
}

if(isset($_POST['stc_epermit_delete'])){
    $id=$_POST['id'];
	$metabots=new transformers();
	$opmetabots=$metabots->stc_delete_epermit_attendance($id);
	echo json_encode($opmetabots);
}
?>