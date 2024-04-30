<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*------------------------------------------For Agents--------------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
class transformers extends tesseract{
	// call sitename
	public function stc_call_epermitenroll($begdate, $enddate){
        $date_filter='AND (DATE(`created_date`) BETWEEN "'.$begdate.'" AND "'. $enddate.'" )';
        if($begdate==''){
            $date_filter='';
        }
        $query="
            SELECT `id`, `location`, `stc_status_down_list_department_dept`, `emp_name`, `gpno`, `shift`, `created_date`, `created_by` 
            FROM `stc_epermit_enrollment`
            LEFT JOIN `stc_status_down_list_department`
            ON `dep_id`=`stc_status_down_list_department_loc_id` 
            WHERE `created_by`='".$_SESSION['stc_agent_sub_id']."'
            ".$date_filter."
            ORDER BY `id` DESC
        ";
		$optimusprimequery=mysqli_query($this->stc_dbs, $query);
		$optimusprime='
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">SL NO</th>
                        <th class="text-center">DATE</th>
                        <th class="text-center">LOCATION</th>
                        <th class="text-center">DEPARTMENT</th>
                        <th class="text-center">EMPLOYEE NAME</th>
                        <th class="text-center">GP NO</th>
                        <th class="text-center">SHIFT</th>
                    </tr>
                </thead>
                <tbody>
        ';
		$do_action=mysqli_num_rows($optimusprimequery);
		if($do_action == 0){
			$optimusprime .= "<tr><td colspan='8' class='text-center'>No records found.</td></tr>";
		}else{
            $slno=0;
			foreach ($optimusprimequery as $row) {
                $slno++;
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
		return $optimusprime;
	}

    // save permit enrollment
    public function stc_save_epermitenroll($location, $dept, $name, $gpno, $shift){
        $optimusprime = "";
        $optimusprimequery=mysqli_query($this->stc_dbs, "
            INSERT INTO `stc_epermit_enrollment`(`location`, `dep_id`, `emp_name`, `gpno`, `shift`, `created_date`, `created_by`) VALUES ('".mysqli_real_escape_string($this->stc_dbs, $location)."', '".mysqli_real_escape_string($this->stc_dbs, $dept)."', '".mysqli_real_escape_string($this->stc_dbs, $name)."', '".mysqli_real_escape_string($this->stc_dbs, $gpno)."', '".mysqli_real_escape_string($this->stc_dbs, $shift)."', NOW(), '".$_SESSION['stc_agent_sub_id']."')
		");
        if($optimusprimequery){
            $optimusprime="Success";
        }else{
            $optimusprime="Failed";
        }
        return $optimusprime;
    }
}

/*-----------------------------------------------------------------------------------*/
/*---------------------------------For Order items-----------------------------------*/
/*-----------------------------------------------------------------------------------*/
// call sitename
if(isset($_POST['show_epermitenroll'])){
    $begdate=date('Y-m-d', strtotime($_POST['begdate']));
    $enddate=date('Y-m-d', strtotime($_POST['enddate']));
	$metabots=new transformers();
	$opmetabots=$metabots->stc_call_epermitenroll($begdate, $enddate);
	echo $opmetabots;
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
?>