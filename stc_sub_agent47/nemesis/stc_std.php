<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*------------------------------------------For Agents--------------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
class transformers extends tesseract{
	// call sitename
	public function stc_call_location(){
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT `stc_cust_project_id`, `stc_cust_project_title` FROM `stc_cust_pro_attend_supervise`
			INNER JOIN `stc_cust_project` 
			ON `stc_cust_project_id`=`stc_cust_pro_attend_supervise_pro_id` 
			WHERE `stc_cust_pro_attend_supervise_super_id`='".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_agent_sub_id'])."' ORDER BY `stc_cust_project_title` ASC
		");
		$optimusprime='<option value="NA" selected>Select Site</option>';
		$do_action=mysqli_num_rows($optimusprimequery);
		if($do_action == 0){
			$optimusprime = "<option value='NA' selected>No Site Found !!</option>";
		}else{
			foreach ($optimusprimequery as $row) {
				$optimusprime.='
					<option value="'.$row["stc_cust_project_id"].'">'.$row["stc_cust_project_title"].'</option>		               	
		    	';				
			}			
		}
		return $optimusprime;
	}

	// call department
	public function stc_call_department($loca_id){
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT `stc_cpumpd_sub_location` FROM `stc_customer_pump_details` WHERE `stc_cpumpd_location`='".mysqli_real_escape_string($this->stc_dbs, $loca_id)."' ORDER BY `stc_cpumpd_sub_location` ASC
		");
		$optimusprime='<option value="NA" selected>Select Department</option>';
		$do_action=mysqli_num_rows($optimusprimequery);
		if($do_action == 0){
			$optimusprime = "<option value='NA' selected>No Department Found!!</option>";
		}else{
			foreach ($optimusprimequery as $row) {
				$optimusprime.='
					<option value="'.$row["stc_cpumpd_sub_location"].'">'.$row["stc_cpumpd_sub_location"].'</option>		               	
		    	';				
			}			
		}
		return $optimusprime;		
	}

	// call area
	public function stc_call_area($loca_id){
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT `stc_cpumpd_area` FROM `stc_customer_pump_details` WHERE `stc_cpumpd_sub_location`='".mysqli_real_escape_string($this->stc_dbs, $loca_id)."' ORDER BY `stc_cpumpd_area` ASC
		");
		$optimusprime='<option value="NA" selected>Select Area</option>';
		$do_action=mysqli_num_rows($optimusprimequery);
		if($do_action == 0){
			$optimusprime = "<option value='NA' selected>No Area Found!!</option>";
		}else{
			foreach ($optimusprimequery as $row) {
				$optimusprime.='
					<option value="'.$row["stc_cpumpd_area"].'">'.$row["stc_cpumpd_area"].'</option>		               	
		    	';				
			}			
		}
		return $optimusprime;
	}

	// call equipment type
	public function stc_call_eq_type($loca_id, $area_name, $department){
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT `stc_cpumpd_id`,`stc_cpumpd_equipment_type`,`stc_cpumpd_equipment_number` FROM `stc_customer_pump_details` WHERE `stc_cpumpd_location`='".mysqli_real_escape_string($this->stc_dbs, $loca_id)."' 
			AND `stc_cpumpd_area`='".mysqli_real_escape_string($this->stc_dbs, $area_name)."' AND `stc_cpumpd_sub_location`='".mysqli_real_escape_string($this->stc_dbs, $department)."' ORDER BY `stc_cpumpd_equipment_type` ASC
		");
		$optimusprime='<option value="NA" selected>Select Equipment Type</option>';
		$do_action=mysqli_num_rows($optimusprimequery);
		if($do_action == 0){
			$optimusprime = "<option value='NA' selected>No Equipment Type Found!!</option>";
		}else{
			foreach ($optimusprimequery as $row) {
				$optimusprime.='
					<option value="'.$row["stc_cpumpd_id"].'" data-value="'.$row["stc_cpumpd_id"].'" >'.$row["stc_cpumpd_equipment_type"].' #'.$row["stc_cpumpd_equipment_number"].'</option>		               	
		    	';				
			}			
		}
		return $optimusprime;
	}

	// call equipment number
	public function stc_call_eq_number($loca_id, $eq_type_id, $eq_type_nu){
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT `stc_cpumpd_id`,`stc_cpumpd_equipment_number` FROM `stc_customer_pump_details` WHERE `stc_cpumpd_location`='".mysqli_real_escape_string($this->stc_dbs, $loca_id)."' AND `stc_cpumpd_id`='".mysqli_real_escape_string($this->stc_dbs, $eq_type_id)."'
		");
		$optimusprime='<option value="NA" selected>Select Equipment Type</option>';
		$do_action=mysqli_num_rows($optimusprimequery);
		if($do_action == 0){
			$optimusprime = "<option value='NA' selected>No Equipment Type Found!!</option>";
		}else{
			foreach ($optimusprimequery as $row) {
			    if($row['stc_cpumpd_id']==$eq_type_id){
    				$optimusprime.='
    					<option value="'.$row["stc_cpumpd_id"].'" selected>'.$row["stc_cpumpd_equipment_number"].'</option>		               	
    		    	';				    
			    }else{
    				$optimusprime.='
    					<option value="'.$row["stc_cpumpd_id"].'">'.$row["stc_cpumpd_equipment_number"].'</option>		               	
    		    	';				
    			}
			}			
		}
		return $optimusprime;
	}

	// call job variites
	public function stc_call_job_varities($job_type){		
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT `stc_status_down_list_job_type_id`,`stc_status_down_list_job_type_sub_title` FROM `stc_status_down_list_job_type` WHERE `stc_status_down_list_job_type_title`='".mysqli_real_escape_string($this->stc_dbs, $job_type)."' ORDER BY `stc_status_down_list_job_type_sub_title` ASC
		");
		$optimusprime='<option value="NA" selected>Select Job Varieties</option>';
		$do_action=mysqli_num_rows($optimusprimequery);
		if($do_action == 0){
			$optimusprime = "<option value='NA' selected>No Job Varieties Found!!</option>";
		}else{
			foreach ($optimusprimequery as $row) {
    			$optimusprime.='
    				<option value="'.$row["stc_status_down_list_job_type_id"].'" selected>'.$row["stc_status_down_list_job_type_sub_title"].'</option>		               	
    		    ';
			}			
		}
		return $optimusprime;
	}

	// save std
	public function stc_std_save($stc_location, $stc_dept, $stc_area, $stc_eq_type, $stc_eq_number, $stc_eq_status, $stc_j_plannning, $stc_j_type, $stc_j_varities, $stc_created_by_se, $permit_no, $stc_creator_details, $stc_r_person, $stc_reason, $stc_material_desc, $stc_manpower_req, $stc_target_date, $stc_tools_req, $stc_remarks){
		$optimusprime='';
		$date=date("Y-m-d H:i:s");
		$tools_req='';
		foreach($stc_tools_req as $stc_tools_row){
			$tools_req.=$stc_tools_row.', ';
		}
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_status_down_list`(
			    `stc_status_down_list_date`,
			    `stc_status_down_list_location`,
			    `stc_status_down_list_sub_location`,
			    `stc_status_down_list_area`,
			    `stc_status_down_list_equipment_type`,
			    `stc_status_down_list_equipment_number`,
			    `stc_status_down_list_equipment_status`,
			    `stc_status_down_list_jobtype`,
			    `stc_status_down_list_varities_id`,
			    `stc_status_down_list_created_by_select`,
			    `stc_status_down_list_permit_no`,
			    `stc_status_down_list_creator_details`,
			    `stc_status_down_list_responsive_person`,
			    `stc_status_down_list_reason`,
			    `stc_status_down_list_material_desc`,
			    `stc_status_down_list_manpower_req`,
			    `stc_status_down_list_target_date`,
			    `stc_status_down_list_tools_req`,
			    `stc_status_down_list_remarks`,
			    `stc_status_down_list_status`,
			    `stc_status_down_list_created_by`
			)VALUES(
				'".mysqli_real_escape_string($this->stc_dbs, $date)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_dept)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_location)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_area)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_eq_type)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_eq_number)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_eq_status)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_j_plannning)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_j_varities)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_created_by_se)."',
				'".mysqli_real_escape_string($this->stc_dbs, $permit_no)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_creator_details)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_r_person)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_reason)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_material_desc)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_manpower_req)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_target_date)."',
				'".mysqli_real_escape_string($this->stc_dbs, $tools_req)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_remarks)."',
				'1',
				'".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_agent_sub_id'])."'
			)
		");
		if($optimusprimequery){
			$optimusprime='Status Down saved. Thankyou!!!';
		}else{
			$optimusprime='Hmmm!!! Something went wrong on status down.';
		}
		return $optimusprime;
	}

	// status down list
	public function stc_call_status_down_list($location_id){
		$optimusprime='
			<table class="table table-bordered table-responsive">
				<thead>
					<tr>
						<th class="text-center">LOCATION</th>
						<th class="text-center">AREA</th>
						<th class="text-center">JOB TYPE</th>
						<th class="text-center">EQUIPMENT TYPE</th>
						<th class="text-center">EQUIPMENT NO</th>
						<th class="text-center">EQUIPMENT STATUS</th>
						<th class="text-center">MATERIAL DESCRIPTION</th>
						<th class="text-center">TARGET DATE</th>
						<th class="text-center">STATUS</th>
						<th class="text-center">PENDING REASON</th>
						<th class="text-center">REMARKS</th>
						<th class="text-center">ACTION</th>
					</tr>
				</thead>
				<tbody>
		';
		$optimusprimeqry=mysqli_query($this->stc_dbs, "
			SELECT 		 	
				`stc_status_down_list_id`,
				`stc_status_down_list_date`,
				`stc_cust_project_title`,
				`stc_status_down_list_area`,
				`stc_status_down_list_jobtype`,
				`stc_status_down_list_created_by_select`,
                `stc_status_down_list_creator_details`,
				`stc_status_down_list_equipment_status`,
				`stc_status_down_list_reason`,
				`stc_status_down_list_material_desc`,
				`stc_status_down_list_from_date`,
				`stc_status_down_list_rect_date`,
				`stc_status_down_list_remarks`,
				`stc_status_down_list_responsive_person`,
				`stc_status_down_list_target_date`,
				`stc_status_down_list_jobpending_details`,
				`stc_status_down_list_equipment_type`,
				`stc_status_down_list_equipment_number`,
				`stc_status_down_list_status`
			FROM `stc_status_down_list` 
			INNER JOIN `stc_cust_project` 
			ON `stc_cust_project_id`=`stc_status_down_list_location` 
			WHERE `stc_status_down_list_location`='".mysqli_real_escape_string($this->stc_dbs, $location_id)."' AND `stc_status_down_list_status`<>5
			ORDER BY `stc_status_down_list_equipment_status` ASC
		");
		if(mysqli_num_rows($optimusprimeqry)>0){
			foreach($optimusprimeqry as $row){

				$list_date=(date('Y', strtotime($row['stc_status_down_list_date']))>1970) ? date('d-m-Y', strtotime($row['stc_status_down_list_date'])) : 'NA';
				$rec_date=(date('Y', strtotime($row['stc_status_down_list_rect_date']))>1970) ? date('d-m-Y', strtotime($row['stc_status_down_list_rect_date'])) : 'NA';
				$tar_date=(date('Y', strtotime($row['stc_status_down_list_target_date']))>1970) ? date('d-m-Y', strtotime($row['stc_status_down_list_target_date'])) : 'NA';

				$status='';

				if($row['stc_status_down_list_status']==1){
					$status='<b><span style="padding: 5px;margin: 0;width: 100%;color: #000000">PENDING</span></b>';
				}elseif($row['stc_status_down_list_status']==2){
					$status='<b><span style="padding: 5px;margin: 0;width: 100%;color: #000000">WORK-IN-PROGRESS</span></b>';
				}elseif($row['stc_status_down_list_status']==3){
					$status='<b><span style="padding: 5px;margin: 0;width: 100%;color: #000000">WORK-DONE</span></b>';
				}elseif($row['stc_status_down_list_status']==4){
					$status='<b><span style="padding: 5px;margin: 0;width: 100%;color: #000000">WORK-COMPLETE</span></b>';
				}else{
					$status='<b><span style="padding: 5px;margin: 0;width: 100%;color: #000000">CLOSE</span></b>';
				}

				$down='';
				$running='';

				if($row['stc_status_down_list_status']==1 || $row['stc_status_down_list_status']==2){
					$down='
							<td style="background: #f72c2c;border-radius: 5px;"></td>
							<td>'.$list_date.'</td>
					';
					$running='<td></td><td></td>';
				}else{
					$down='<td></td><td></td>';
					$running='
							<td style="background: #5cd25c;border-radius: 5px;"></td>
							<td>'.$list_date.'</td>
					';
				}

				$actionsec='';
				if($row['stc_status_down_list_status']==1){
					$actionsec='
						<select class="stc-set-to-complete" id="'.$row['stc_status_down_list_id'].'">
							<option status="NA">SELECT STATUS</option>
							<option status="2">WORK-IN-PROGRESS</option>
							<option status="3">WORK-DONE</option>
						</select>
						<a href="#" class="stc-add-to-pending btn btn-danger" style="font-size:10px;margin-top:4px;" id="'.$row['stc_status_down_list_id'].'">Add Pending Reason</a>
					';
				}elseif($row['stc_status_down_list_status']==2){
					$actionsec='
						<select class="stc-set-to-complete" id="'.$row['stc_status_down_list_id'].'">
							<option status="NA">SELECT STATUS</option>
							<option status="1">PENDING</option>
							<option status="3">WORK-DONE</option>
						</select>
					';
				}else{
					$actionsec='#';
				}

				$eqstatus='
						<td class="text-center" style="font-weight:bold;background: #5cd25c;border-radius: 5px;">'.$row['stc_status_down_list_equipment_status'].'</td>
				';

				$dperiod='0';

				if($row['stc_status_down_list_equipment_status']=="Down"){
					$eqstatus='
							<td class="text-center" style="font-weight:bold;background: #e91919;border-radius: 5px;">'.$row['stc_status_down_list_equipment_status'].'</td>
					';
				}
				$eq_type='';
				$eq_number='';
				$stc_call_eqtypeqry=mysqli_query($this->stc_dbs, "
					SELECT
					    `stc_cpumpd_equipment_type`
					FROM
					    `stc_customer_pump_details`
					WHERE
					    `stc_cpumpd_id`='".$row['stc_status_down_list_equipment_type']."'
				");
				foreach($stc_call_eqtypeqry as $stc_call_eqtyperow){
					$eq_type=$stc_call_eqtyperow['stc_cpumpd_equipment_type'];
				}

				$stc_call_eqnumberqry=mysqli_query($this->stc_dbs, "
					SELECT
					    `stc_cpumpd_equipment_number`
					FROM
					    `stc_customer_pump_details`
					WHERE
					    `stc_cpumpd_id`='".$row['stc_status_down_list_equipment_number']."'
				");
				foreach($stc_call_eqnumberqry as $stc_call_eqnumberrow){
					$eq_number=$stc_call_eqnumberrow['stc_cpumpd_equipment_number'];
				}

				$optimusprime.='
					<tr>
						<td>'.$row['stc_cust_project_title'].'</td>
						<td>'.$row['stc_status_down_list_area'].'</td>
						<td>'.$row['stc_status_down_list_jobtype'].'</td>
						<td>'.$eq_type.'</td>
						<td class="text-center">'.$eq_number.'</td>
						'.$eqstatus.'
						<td>'.$row['stc_status_down_list_material_desc'].'</td>
						<td>'.$tar_date.'</td>
						<td>'.$status.'</td>
						<td>'.$row['stc_status_down_list_jobpending_details'].'</td>
						<td>'.$row['stc_status_down_list_remarks'].'</td>
						<td class="text-center">'.$actionsec.'</td>
					</tr>
				';
			}
		}else{
			$optimusprime.='
				<tr>
					<td colspan="14">No Record Found.</td>
				</tr>
			';
		}

		$optimusprime.='
				</tbody>
			</table>
		';
		return $optimusprime;
	}

	// change status
	public function stc_sdl_status_update($status, $sld_id, $jobdonedetails){
		$optimusprime='';
		$date=date("Y-m-d H:i:s");
		$optimusprime_qry=mysqli_query($this->stc_dbs, "
			UPDATE 
				`stc_status_down_list` 
			SET 
				`stc_status_down_list_status`='".mysqli_real_escape_string($this->stc_dbs, $status)."',
				`stc_status_down_list_jobdone_details`='".mysqli_real_escape_string($this->stc_dbs, $jobdonedetails)."' 
			WHERE 
				`stc_status_down_list_id`='".mysqli_real_escape_string($this->stc_dbs, $sld_id)."'
		");
		if($optimusprime_qry){
			if($status==3){
				$optimusprime_qry=mysqli_query($this->stc_dbs, "
					UPDATE
						`stc_status_down_list`
					SET
						`stc_status_down_list_rect_date`='".mysqli_real_escape_string($this->stc_dbs, $date)."',
						`stc_status_down_list_equipment_status`='Running'
					WHERE
						`stc_status_down_list_id`='".mysqli_real_escape_string($this->stc_dbs, $sld_id)."'
				");
			}
			$optimusprime='Status Updated!!!';
		}else{
			$optimusprime='Hmmm!!! Somethig went wrong on changing status.';
		}
		return $optimusprime;
	}

	// save job pending
	public function stc_sdl_jobpending_save($sld_id, $jobpendingdetails){
		$optimusprime='';
		$date=date("d/m/Y H:i:s");
		$time = date('h:i A', strtotime($date));
		$newDateTime=date("d/m/Y").' '.$time;
		$optimusprime_qry=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_status_down_list_jobpending_details` 
			FROM 
				`stc_status_down_list`
			WHERE 
				`stc_status_down_list_id`='".mysqli_real_escape_string($this->stc_dbs, $sld_id)."'
		");
		$pendingcall='';
		foreach($optimusprime_qry as $optimusprime_row){
			$pendingcall=$optimusprime_row['stc_status_down_list_jobpending_details'];
		}
		$pending=$pendingcall.', '.$newDateTime.' - '.$jobpendingdetails;
		$optimusprime_updateqry=mysqli_query($this->stc_dbs, "
			UPDATE 
				`stc_status_down_list` 
			SET 
				`stc_status_down_list_jobpending_details`='".mysqli_real_escape_string($this->stc_dbs, $pending)."' 
			WHERE 
				`stc_status_down_list_id`='".mysqli_real_escape_string($this->stc_dbs, $sld_id)."'
		");
		if($optimusprime_updateqry){
			$optimusprime='Pending Reason Saved!!!';
		}else{
			$optimusprime='Hmmm!!! Somethig went wrong on updating pending reason.';
		}
		return $optimusprime;		
	}
}

/*-----------------------------------------------------------------------------------*/
/*---------------------------------For Status Down List------------------------------*/
/*-----------------------------------------------------------------------------------*/
// call sitename
if(isset($_POST['call_location'])){
	$metabots=new transformers();
	$opmetabots=$metabots->stc_call_location();
	echo $opmetabots;
}

// call department
if(isset($_POST['call_department'])){
	$loca_id=$_POST['loca_id'];
	$metabots=new transformers();
	$opmetabots=$metabots->stc_call_department($loca_id);
	echo $opmetabots;
}

// call area
if(isset($_POST['call_area'])){
	$loca_id=$_POST['loca_id'];
	$metabots=new transformers();
	$opmetabots=$metabots->stc_call_area($loca_id);
	echo $opmetabots;
}

// call equipment type
if(isset($_POST['call_eq_type'])){
	$loca_id=$_POST['loca_id'];
	$area_name=$_POST['area_name'];
	$department=$_POST['department'];
	$metabots=new transformers();
	$opmetabots=$metabots->stc_call_eq_type($loca_id, $area_name, $department);
	echo $opmetabots;
}

// call equipment number
if(isset($_POST['call_eq_number'])){
	$loca_id=$_POST['loca_id'];
	$eq_type_id=$_POST['eq_type_id'];
	$eq_type_nu=$_POST['eq_type_nu'];
	$metabots=new transformers();
	$opmetabots=$metabots->stc_call_eq_number($loca_id, $eq_type_id, $eq_type_nu);
	echo $opmetabots;
}

// call job varities
if(isset($_POST['call_j_varities'])){
	$job_type=$_POST['job_type'];
	$metabots=new transformers();
	$opmetabots=$metabots->stc_call_job_varities($job_type);
	echo $opmetabots;
}
// std save
if(isset($_POST['stc_std_hit'])){
	$opmetabots='';
	$stc_location=$_POST['stc_location'];
	$stc_dept=$_POST['stc_dept'];
	$stc_area=$_POST['stc_area'];
	$stc_eq_type=$_POST['stc_eq_type'];
	$stc_eq_number=$_POST['stc_eq_number'];
	$stc_eq_status=$_POST['stc_eq_status'];
	$stc_j_plannning=$_POST['stc_j_plannning'];
	$stc_j_type=$_POST['stc_j_type'];
	$stc_j_varities=$_POST['stc_j_varities'];
	$stc_created_by_se=$_POST['stc_created_by_se'];
	$permit_no=$_POST['permit_no'];
    $stc_creator_details=$_POST['stc_creator_details'];
	$stc_r_person=$_POST['stc_r_person'];
	$stc_reason=$_POST['stc_reason'];
	$stc_material_desc=$_POST['stc_material_desc'];
	$stc_manpower_req=$_POST['stc_manpower_req'];
	$stc_target_date=$_POST['stc_target_date'];
	$stc_tools_req=$_POST['stc_tools_req'];
	$stc_remarks=$_POST['stc_remarks'];

	$metabots=new transformers();
	if(($stc_dept=="NA") || ($stc_eq_type=="NA") || ($stc_eq_number=="NA") || ($stc_j_type=="NA") || ($stc_j_varities=="NA") || empty($stc_location) || empty($stc_area) || empty($stc_creator_details) || empty($stc_r_person) || empty($stc_reason) || empty($stc_material_desc) || empty($stc_manpower_req) || empty($stc_target_date)){
		$opmetabots="Please fill all the field choose date, if no data available then fill with NA.";
	}elseif(empty($_SESSION['stc_agent_sub_id'])){
		$opmetabots="Please login!!!";
	}else{
		$opmetabots=$metabots->stc_std_save($stc_location, $stc_dept, $stc_area, $stc_eq_type, $stc_eq_number, $stc_eq_status, $stc_j_plannning, $stc_j_type, $stc_j_varities, $stc_created_by_se, $permit_no, $stc_creator_details, $stc_r_person, $stc_reason, $stc_material_desc, $stc_manpower_req, $stc_target_date, $stc_tools_req, $stc_remarks);
	}
	echo $opmetabots;
}

// call std
if(isset($_POST['stc_down_list_hit'])){
	$location_id=$_POST['location_id'];
	$metabots=new transformers();
	$opmetabots=$metabots->stc_call_status_down_list($location_id);
	echo $opmetabots;
}

// changee status
if(isset($_POST['stc_status_change_hit'])){
	$sld_id=$_POST['sdl_id'];
	$status=$_POST['status_id'];
	$jobdonedetails=$_POST['jobdonedetails'];
	$sdl_status=new transformers();
	$out_sdl_status=$sdl_status->stc_sdl_status_update($status, $sld_id, $jobdonedetails);
	echo $out_sdl_status;
}

// save job pending
if(isset($_POST['stc_jobpending_save_hit'])){
	$sld_id=$_POST['sdl_id'];
	$jobpendingdetails=$_POST['jobpendingdetails'];
	$sdl_status=new transformers();
	$out_sdl_status=$sdl_status->stc_sdl_jobpending_save($sld_id, $jobpendingdetails);
	echo $out_sdl_status;
}
?>