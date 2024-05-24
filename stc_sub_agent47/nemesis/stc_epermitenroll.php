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
	public function stc_call_epermitenroll($begdate, $enddate){
        $filter='WHERE DATE(`created_date`)="'.$enddate.'" AND `stc_status_down_list_department_id` IN ( SELECT DISTINCT `stc_status_down_list_department_id` FROM `stc_cust_pro_attend_supervise` INNER JOIN `stc_status_down_list_department`  ON `stc_cust_pro_attend_supervise_pro_id` = `stc_status_down_list_department_loc_id` WHERE `stc_cust_pro_attend_supervise_super_id`='.$_SESSION['stc_agent_sub_id'].')'; 
        $countPEntry = 0;
        if($_SESSION['stc_agent_sub_category']=='Supervisor' || $_SESSION['stc_agent_sub_category']=='Site Incharge'){
            if($enddate==''){
                $enddate=DATE('Y-m-d');
                $filter='WHERE DATE(`created_date`)="'.$enddate.'" AND `stc_status_down_list_department_id` IN ( SELECT DISTINCT `stc_status_down_list_department_id` FROM `stc_cust_pro_attend_supervise` INNER JOIN `stc_status_down_list_department`  ON `stc_cust_pro_attend_supervise_pro_id` = `stc_status_down_list_department_loc_id` WHERE `stc_cust_pro_attend_supervise_super_id`='.$_SESSION['stc_agent_sub_id'].')'; 
            }
        }else{
            if($enddate==''){
                $filter='WHERE DATE(`created_date`)="'.$enddate.'" AND `created_by`="'.$_SESSION['stc_agent_sub_id'].'"';
            }else{
                $filter.=' AND `created_by`="'.$_SESSION['stc_agent_sub_id'].'"';
            }
        }
        $query="
            SELECT `id`, `location`, `stc_status_down_list_department_dept`, `emp_name`, `gpno`, `shift`, `created_date`, `created_by` FROM `stc_epermit_enrollment` LEFT JOIN `stc_status_down_list_department` ON `dep_id`=`stc_status_down_list_department_id` LEFT JOIN `stc_cust_pro_supervisor` ON `stc_cust_pro_supervisor_id`=`created_by`LEFT JOIN `stc_agents` ON `stc_cust_pro_supervisor_created_by`=`stc_agents_id` ".$filter." ORDER BY `emp_name` ASC
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
				$optimusprime .= "
                    <tr>
                        <td class='text-center'>".$slno."</td>
                        <td class='text-center'>".date('d-m-Y', strtotime($row['created_date']))."</td>
                        <td class='text-center'>".$row['location']."</td>
                        <td class='text-center'>".$row['stc_status_down_list_department_dept']."</td>
                        <td class='text-left'>".$row['emp_name']."</td>
                        <td class='text-center'>".$row['gpno']."</td>
                        <td class='text-center'>".$row['shift']."</td>
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
		return $query;
	}

    // save permit enrollment
    public function stc_save_epermitenroll($location, $dept, $name, $gpno, $shift){
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
            // No duplicate found, proceed with insertion
            $insertQuery = "INSERT INTO `stc_epermit_enrollment`(`location`, `dep_id`, `emp_name`, `gpno`, `shift`, `created_date`, `created_by`) VALUES ('".mysqli_real_escape_string($this->stc_dbs, $location)."', '".mysqli_real_escape_string($this->stc_dbs, $dept)."', UCASE('".mysqli_real_escape_string($this->stc_dbs, $name)."'), UCASE('".mysqli_real_escape_string($this->stc_dbs, $gpno)."'), '".mysqli_real_escape_string($this->stc_dbs, $shift)."', '".$currentDateTime."', '".$_SESSION['stc_agent_sub_id']."')";

            $insertResult = mysqli_query($this->stc_dbs, $insertQuery);
            if ($insertResult) {
                $optimusprime = "Success";
            } else {
                $optimusprime = "failed";
            }
        }

        return $optimusprime;

    }

    public function stc_save_totalpermitenr($totalpermitenr, $location, $dept, $remarks){
        $optimusprime = "";
        $currentDateTime = date("Y-m-d H:i:s");
        $qry="
            INSERT INTO `stc_totalpermitenrollment`(`totalpermitenr`, `location`, `dep_id`, `remarks`, `created_date`, `created_by`) VALUES ('".mysqli_real_escape_string($this->stc_dbs, $totalpermitenr)."', '".mysqli_real_escape_string($this->stc_dbs, $location)."', '".mysqli_real_escape_string($this->stc_dbs, $dept)."', '".mysqli_real_escape_string($this->stc_dbs, $remarks)."', '".$currentDateTime."', '".$_SESSION['stc_agent_sub_id']."')
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
        $remarks='';
        $query="
            SELECT `id`, `totalpermitenr`, `remarks`, `created_date`, `created_by` 
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
            'remarks' => $remarks,
            'totalpenrollment' => $counter
        );
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
	$metabots=new transformers();
	$opmetabots=$metabots->stc_call_epermitenroll($begdate, $enddate);
	echo json_encode($opmetabots);
}

// save permit enrollment
if(isset($_POST['save_permitenr'])){
    $location=$_POST['location'];
    $dept=$_POST['dept'];
    $name=$_POST['name'];
    $gpno=$_POST['gpno'];
    $shift=$_POST['shift'];
    $out='';
    if($dept=="NA" || $name=='' || $gpno==''){
        $out="empty";
    }else if(!isset($_SESSION['stc_agent_sub_id'])){
        $out="login";
    }else{
        $metabots=new transformers();
        $opmetabots=$metabots->stc_save_epermitenroll($location, $dept, $name, $gpno, $shift);
        $out=$opmetabots;
    }
    echo json_encode($out);
}

// save permit enrollment
if(isset($_POST['save_totalpermitenr'])){
    $totalpermitenr=$_POST['totalpermitenr'];
    $location=$_POST['location'];
    $dept=$_POST['dept'];
    $remarks=$_POST['remarks'];
    $out='';
    if($totalpermitenr=="NA"){
        $out="empty";
    }else if(!isset($_SESSION['stc_agent_sub_id'])){
        $out="login";
    }else{
        $metabots=new transformers();
        $opmetabots=$metabots->stc_save_totalpermitenr($totalpermitenr, $location, $dept, $remarks);
        $out=$opmetabots;
    }
    echo json_encode($out);
}


if(isset($_POST['show_totalepermitenroll'])){
	$metabots=new transformers();
	$opmetabots=$metabots->stc_call_totalpermitenr();
	echo json_encode($opmetabots);
}
?>