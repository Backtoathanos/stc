<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*------------------------------------------For Agents--------------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
class transformers extends tesseract{
	// call location	
	public function stc_call_locdept(){
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT `stc_status_down_list_department_location`
			FROM `stc_cust_pro_attend_supervise`
			INNER JOIN `stc_cust_project` 
			ON `stc_cust_project_id`=`stc_cust_pro_attend_supervise_pro_id` 
			INNER JOIN `stc_status_down_list_department` 
			ON `stc_cust_project_id`=`stc_status_down_list_department_loc_id` 
			WHERE `stc_cust_pro_attend_supervise_super_id`='".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_agent_sub_id'])."' ORDER BY `stc_status_down_list_department_location` ASC
		");
		$optimusprime = "";
		$do_action=mysqli_num_rows($optimusprimequery);
		if($do_action == 0){
			$optimusprime = "empty";
		}else{
			$optimusprime.='<option>Select</option>';	
			foreach ($optimusprimequery as $row) {
				$optimusprime.='<option>'.$row['stc_status_down_list_department_location'].'</option>';		
			}			
		}
		return $optimusprime;
	}
	
	// call location department	
	public function stc_call_deptloc($loca_id){
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT  `stc_status_down_list_department_dept`, `stc_cust_project_id`
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
			$optimusprime = "empty";
		}else{
			foreach ($optimusprimequery as $row) {
				$optimusprime.='<option data-id="'.$row['stc_cust_project_id'].'">'.$row['stc_status_down_list_department_dept'].'</option>';		
			}			
		}
		return $optimusprime;
	}

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
	
	// call sitename
	public function stc_call_jvarities($jvarites){
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT `stc_status_down_list_job_type_id`, `stc_status_down_list_job_type_title`,`stc_status_down_list_job_type_sub_title` FROM `stc_status_down_list_job_type`
			WHERE `stc_status_down_list_job_type_id`='".mysqli_real_escape_string($this->stc_dbs, $jvarites)."'
		");
		if(mysqli_num_rows($optimusprimequery)>0){
			$optimusprime = mysqli_fetch_assoc($optimusprimequery);
		}else{
			$optimusprime = "no";
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
	public function stc_call_area($loca_id, $loca_sub_name){
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT `stc_cpumpd_area` FROM `stc_customer_pump_details` WHERE `stc_cpumpd_sub_location`='".mysqli_real_escape_string($this->stc_dbs, $loca_sub_name)."' AND `stc_cpumpd_location`='".mysqli_real_escape_string($this->stc_dbs, $loca_id)."' ORDER BY `stc_cpumpd_area` ASC
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
		$job_type=implode(',', $job_type);
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT `stc_status_down_list_job_type_id`,`stc_status_down_list_job_type_sub_title` FROM `stc_status_down_list_job_type` WHERE `stc_status_down_list_job_type_title` IN (".$job_type.") ORDER BY `stc_status_down_list_job_type_sub_title` ASC
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
	public function stc_std_save($stc_slocation, $stc_location, $stc_dept, $stc_area, $stc_j_plannning, $reason, $eq_type, $action_status, $creator_details){
		$optimusprime='';
		$date=date("Y-m-d H:i:s");
		$tools_req='';
		if(!empty($stc_tools_req)){
			foreach($stc_tools_req as $stc_tools_row){
				$tools_req.=$stc_tools_row.', ';
			}
		}
		$status="Running";
		if($stc_j_plannning=="BREAKDOWN MAINTENANCE" || $action_status==2){
			$status="Down";
		}
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_status_down_list`(
			    `stc_status_down_list_date`,
			    `stc_status_down_list_plocation`,
			    `stc_status_down_list_location`,
			    `stc_status_down_list_sub_location`,
			    `stc_status_down_list_area`,
			    `stc_status_down_list_jobtype`,
			    `stc_status_down_list_reason`,
			    `stc_status_down_list_equipment_type`,
				`stc_status_down_list_equipment_status`,
				`stc_status_down_list_creator_details`,
			    `stc_status_down_list_status`,
			    `stc_status_down_list_created_by`
			)VALUES(
				'".mysqli_real_escape_string($this->stc_dbs, $date)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_slocation)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_location)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_dept)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_area)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_j_plannning)."',
				'".mysqli_real_escape_string($this->stc_dbs, $reason)."',
				'".mysqli_real_escape_string($this->stc_dbs, $eq_type)."',
				'".$status."',
				'".mysqli_real_escape_string($this->stc_dbs, $creator_details)."',
				'".mysqli_real_escape_string($this->stc_dbs, $action_status)."',
				'".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_agent_sub_id'])."'
			)
		");
		if($optimusprimequery){
			$optimusprime='Status Down List saved. Thankyou!!!';
		}else{
			$optimusprime='Hmmm!!! Something went wrong on status down.';
		}
		return $optimusprime;
	}

	// update std manual
	public function stc_std_updatem($stc_std_id, $stc_slocation, $stc_location, $stc_dept, $stc_area, $stc_eq_number, $stc_eq_type, $stc_j_varities, $stc_j_plannning){
		$optimusprime='';
		$date=date("Y-m-d H:i:s");
		if($stc_j_varities!=''){
			$stc_j_varities = "`stc_status_down_list_varities_id` = '".mysqli_real_escape_string($this->stc_dbs, $stc_j_varities)."',";
		}
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			UPDATE
			    `stc_status_down_list`
			SET
			    `stc_status_down_list_location` = '".mysqli_real_escape_string($this->stc_dbs, $stc_location)."',
			    `stc_status_down_list_plocation` = '".mysqli_real_escape_string($this->stc_dbs, $stc_slocation)."',
			    `stc_status_down_list_sub_location` = '".mysqli_real_escape_string($this->stc_dbs, $stc_dept)."',
			    `stc_status_down_list_area` = '".mysqli_real_escape_string($this->stc_dbs, $stc_area)."',
				".$stc_j_varities."
			    `stc_status_down_list_jobtype` = '".mysqli_real_escape_string($this->stc_dbs, $stc_j_plannning)."',
			    `stc_status_down_list_updated_by` = '".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_agent_sub_id'])."'
			WHERE 
				`stc_status_down_list_id`='".mysqli_real_escape_string($this->stc_dbs, $stc_std_id)."'
		");
		if($optimusprimequery){
			$optimusprime='Status Down List updated. Thankyou!!!';
		}else{
			$optimusprime='Hmmm!!! Something went wrong on status down.';
		}
		return $optimusprime;
	}

	// update std auto
	public function stc_std_update($std_id, $plocation, $location, $dept, $area, $eq_type, $eq_status, $j_plannning, $qty, $capacity, $reasonattribute, $created_by_se, $permit_no, $creator_details, $r_person, $reason, $material_desc, $manpower_req, $target_date, $jobdonedet, $farootcost, $remarks){
		$optimusprime='';
		$date=date("Y-m-d H:i:s");
		$jobdoneact='';
		if(!empty($jobdonedet)){
			$jobdoneact = "`stc_status_down_list_jobdone_details`='".mysqli_real_escape_string($this->stc_dbs, $jobdonedet)."',";
		}
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			UPDATE `stc_status_down_list` 
			SET
				`stc_status_down_list_plocation`='".mysqli_real_escape_string($this->stc_dbs, $plocation)."',
				`stc_status_down_list_sub_location`='".mysqli_real_escape_string($this->stc_dbs, $dept)."',
				`stc_status_down_list_location`='".mysqli_real_escape_string($this->stc_dbs, $location)."',
				`stc_status_down_list_area`='".mysqli_real_escape_string($this->stc_dbs, $area)."',
				`stc_status_down_list_equipment_type`='".mysqli_real_escape_string($this->stc_dbs, $eq_type)."',
				`stc_status_down_list_equipment_status`='".mysqli_real_escape_string($this->stc_dbs, $eq_status)."',
				`stc_status_down_list_jobtype`='".mysqli_real_escape_string($this->stc_dbs, $j_plannning)."',
				`stc_status_down_list_qty`='".mysqli_real_escape_string($this->stc_dbs, $qty)."',
				`stc_status_down_list_capacity`='".mysqli_real_escape_string($this->stc_dbs, $capacity)."',
				`stc_status_down_list_reasonattribute`='".mysqli_real_escape_string($this->stc_dbs, $reasonattribute)."',
				`stc_status_down_list_created_by_select`='".mysqli_real_escape_string($this->stc_dbs, $created_by_se)."',
				`stc_status_down_list_permit_no`='".mysqli_real_escape_string($this->stc_dbs, $permit_no)."',
				`stc_status_down_list_responsive_person`='".mysqli_real_escape_string($this->stc_dbs, $r_person)."',
				`stc_status_down_list_creator_details`='".mysqli_real_escape_string($this->stc_dbs, $creator_details)."',
				`stc_status_down_list_reason`='".mysqli_real_escape_string($this->stc_dbs, $reason)."',
				`stc_status_down_list_material_desc`='".mysqli_real_escape_string($this->stc_dbs, $material_desc)."',
				`stc_status_down_list_manpower_req`='".mysqli_real_escape_string($this->stc_dbs, $manpower_req)."',
				`stc_status_down_list_target_date`='".mysqli_real_escape_string($this->stc_dbs, $target_date)."',
				`stc_status_down_list_failurerootcost`='".mysqli_real_escape_string($this->stc_dbs, $farootcost)."',
				`stc_status_down_list_remarks`='".mysqli_real_escape_string($this->stc_dbs, $remarks)."',
				".$jobdoneact."
				`stc_status_down_list_updated_by`='".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_agent_sub_id'])."'
			WHERE
				`stc_status_down_list_id`='".mysqli_real_escape_string($this->stc_dbs, $std_id)."'
		");
		if($optimusprimequery){
			$optimusprime='Status Down saved. Thankyou!!!';
		}else{
			$optimusprime='Hmmm!!! Something went wrong on status down.';
		}
		return $optimusprime;
	}

	// tools req
	public function stc_std_update_toolsreq($std_id, $tools_req){
		$optimusprime='';
		$date=date("Y-m-d H:i:s");
		$stctools_req='';
		if(!empty($tools_req)){
			$sln=0;
			foreach($tools_req as $stc_tools_row){
				$sln++;
				if($sln>1){
					$stctools_req.=', '.$stc_tools_row;
				}else{
					$stctools_req.=$stc_tools_row;
				}
			}
		}
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			UPDATE `stc_status_down_list` 
			SET
				`stc_status_down_list_tools_req`='".mysqli_real_escape_string($this->stc_dbs, $stctools_req)."',
				`stc_status_down_list_updated_by`='".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_agent_sub_id'])."'
			WHERE
				`stc_status_down_list_id`='".mysqli_real_escape_string($this->stc_dbs, $std_id)."'
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
		$head_hidden1 = '';
		$head_hidden2 = '';
		$head_hidden3 = '';
		if($_SESSION['stc_agent_sub_category']=='Supervisor' || $_SESSION['stc_agent_sub_category']=='Site Incharge'){
			$head_hidden1='				
				<th class="text-center">EQUIPMENT STATUS</th>
				<th class="text-center">JOB CATEGORIES</th>
				<th class="text-center">JOB VARIETIES</th>
				<th class="text-center">MATERIAL DESCRIPTION</th>
				<th class="text-center">TARGET DATE</th>
				<th class="text-center">PENDING REASON</th>
				<th class="text-center">PROGRESS REPORT</th>
			';
			$head_hidden3='		
				<th class="text-center">ACTION</th>
			';		
		}
		if($_SESSION['stc_agent_sub_category']=='Site Incharge'){
			$head_hidden2='
			<th class="text-center">ANY COMMENT</th>
			<th class="text-center">FAILURE ROOT CAUSE</th>
			<th class="text-center">JOB DONE DETAILS</th>
			<th class="text-center">REMARKS BY HOD</th>
			<th class="text-center">TARGET DATE BY HOD</th>
			';
		}
		$optimusprime='
			<table class="table table-bordered">
				<thead>
					<tr>
						<th class="text-center">DATE</th>
						<th class="text-center">LOCATION</th>
						<th class="text-center">DEPARTMENT</th>
						<th class="text-center">AREA</th>
						<th class="text-center">TYPE OF JOB</th>
						<th class="text-center">REASON</th>
						<th class="text-center">EQUIPMENT TYPE</th>
						'.$head_hidden1.'
						<th class="text-center">CREATOR NAME & CONTACT NUMBER</th>
						<th class="text-center">STATUS</th>
						'.$head_hidden2.'
						'.$head_hidden3.'
					</tr>
				</thead>
				<tbody>
		';
		$optimusprimeqry=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT `stc_cust_pro_supervisor_created_by`
			FROM `stc_status_down_list` 
			LEFT JOIN `stc_cust_pro_supervisor`
			ON `stc_cust_pro_supervisor_id`=`stc_status_down_list_created_by` 
			WHERE `stc_status_down_list_location`='".mysqli_real_escape_string($this->stc_dbs, $location_id)."' 
		");
		$manager = "";
		if(mysqli_num_rows($optimusprimeqry)>0){
			$manager = "AND (";
			$num_count = mysqli_num_rows($optimusprimeqry);
			$counter = $num_count;
			$sl=0;
			foreach($optimusprimeqry as $optimusprimerow){
				$sl++;
				if($sl==1){
					$manager .= "`stc_cust_pro_supervisor_created_by`=".$optimusprimerow['stc_cust_pro_supervisor_created_by'];
				}else{
					$manager .= " OR `stc_cust_pro_supervisor_created_by`=".$optimusprimerow['stc_cust_pro_supervisor_created_by'];
				}
			}
			$manager .= ")";
		}

		$query = "
			SELECT 		 	
				`stc_status_down_list_id`,
				`stc_status_down_list_date`,
				`stc_status_down_list_plocation`,
				`stc_cust_project_title`,
				`stc_status_down_list_area`,
				`stc_status_down_list_sub_location`,
				`stc_status_down_list_jobtype`,
				`stc_status_down_list_created_by_select`,
				`stc_status_down_list_creator_details`,
				`stc_status_down_list_equipment_status`,
				`stc_status_down_list_reason`,
				`stc_status_down_list_material_desc`,
				`stc_status_down_list_jobtype`,
				`stc_status_down_list_from_date`,
				`stc_status_down_list_rect_date`,
				`stc_status_down_list_remarks`,
				`stc_status_down_list_responsive_person`,
				`stc_status_down_list_target_date`,
				`stc_status_down_list_permit_no`,
				`stc_status_down_list_jobpending_details`,
				`stc_status_down_list_jobdone_details`,
				`stc_status_down_list_varities_id`,
				`stc_status_down_list_equipment_type`,
				`stc_status_down_list_equipment_number`,
				`stc_status_down_list_status`,
				`stc_status_down_list_updated_by`,
				`stc_status_down_list_updated_date`,
				`stc_cust_pro_supervisor_fullname`,
				`stc_status_down_list_failurerootcost`,
				`stc_status_down_list_fremarks`,
				`stc_status_down_list_ftarget_date`,
				`stc_status_down_list_wipstatus`
			FROM `stc_status_down_list` 
			LEFT JOIN `stc_cust_project` 
			ON `stc_cust_project_id`=`stc_status_down_list_location` 
			LEFT JOIN `stc_cust_pro_supervisor` 
			ON `stc_cust_pro_supervisor_id`=`stc_status_down_list_created_by` 
			WHERE `stc_status_down_list_location`='".mysqli_real_escape_string($this->stc_dbs, $location_id)."' 
			".$manager."
			AND `stc_status_down_list_status`<5
			ORDER BY TIMESTAMP(`stc_status_down_list_date`) DESC
		";
		$optimusprimeqry=mysqli_query($this->stc_dbs, $query);
		if(mysqli_num_rows($optimusprimeqry)>0){
			foreach($optimusprimeqry as $row){

				$list_date=(date('Y', strtotime($row['stc_status_down_list_date']))>1970) ? date('d-m-Y', strtotime($row['stc_status_down_list_date'])) : 'NA';
				$rec_date=(date('Y', strtotime($row['stc_status_down_list_rect_date']))>1970) ? date('d-m-Y', strtotime($row['stc_status_down_list_rect_date'])) : 'NA';
				$tar_date=(date('Y', strtotime($row['stc_status_down_list_target_date']))>1970) ? date('d-m-Y', strtotime($row['stc_status_down_list_target_date'])) : '';

				$status='';
				$statusbgcolor = '';
				if($row['stc_status_down_list_status']==1){
					$status='<b><span style="padding: 5px;margin: 0;width: 100%;color: #000000">PLANNING</span></b>';
					$statusbgcolor = 'style="background-color: #00f9b4;"';
				}elseif($row['stc_status_down_list_status']==2){
					$status='<b><span style="padding: 5px;margin: 0;width: 100%;color: #000000">DOWN</span></b>';
					$statusbgcolor = 'style="background-color: #e91919;"';
				}elseif($row['stc_status_down_list_status']==3){
					$status='<b><span style="padding: 5px;margin: 0;width: 100%;color: #000000">WORK-IN-PROGRESS</span></b>';
					$statusbgcolor = 'style="background-color: #f6f900;"';
				}elseif($row['stc_status_down_list_status']==4){
					$status='<b><span style="padding: 5px;margin: 0;width: 100%;color: #000000">WORK-DONE</span></b>';
					$statusbgcolor = 'style="background-color: #2aef00;"';
				}elseif($row['stc_status_down_list_status']==5){
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
				$viewmrd='';
				$sdl_id=$row['stc_status_down_list_id'];
				$reqcheck_qry=mysqli_query($this->stc_dbs, "
					SELECT
						`stc_cust_super_requisition_list_id`
					FROM
						`stc_cust_super_requisition_list`
					WHERE
						`stc_cust_super_requisition_list_sdlid`='$sdl_id'
				");
				if(mysqli_num_rows($reqcheck_qry)>0){
					$viewmrd='<a href="javascript:void(0)" id="'.$row['stc_status_down_list_id'].'" class="btn bg-success text-white mb-3 stc-std-view-req-show" data-toggle="modal" data-target=".bd-viewreq-std-modal" title="View Requisiton"><i class="pe-7s-look"></i></a>';
				}

				$actionsec='';
				if($_SESSION['stc_agent_sub_category']=="Supervisor" || $_SESSION['stc_agent_sub_category']=="Site Incharge"){
					if($row['stc_status_down_list_status']==1){
						$actionsec='
							<a href="stc-safety.php?page=safety&sdl='.$row['stc_status_down_list_id'].'&status=update" class="btn bg-secondary text-white mb-3" title="Add TBM"><i class="pe-7s-news-paper"></i></a>
							<a href="stc-requisition.php?page=requisition&sdl='.$row['stc_status_down_list_id'].'&status=add" class="btn bg-success text-white mb-3" title="Add Requisiton"><i class="pe-7s-note2"></i></a>
							'.$viewmrd.'
							<a href="javascript:void(0)" class="btn bg-danger text-white mb-3 stc-std-operation-btn" type="update" data-toggle="modal" data-target=".bd-create-std-modal" title="Edit Status Down List" id="'.$row['stc_status_down_list_id'].'"><i class="pe-7s-pen"></i></a>
							<select class="stc-set-to-complete" id="'.$row['stc_status_down_list_id'].'">
								<option status="NA">SELECT STATUS</option>
								<option status="2">DOWN</option>
								<option status="3">WORK-IN-PROGRESS</option>
								<option status="4">WORK-DONE</option>
							</select>
							<a href="#" class="stc-add-to-pending btn btn-danger" style="font-size:10px;margin-top:4px;" title="Add Pending Reason" id="'.$row['stc_status_down_list_id'].'">Add Pending Reason</a>
						';
					}elseif($row['stc_status_down_list_status']==2){
						$actionsec='
							<a href="stc-safety.php?sdl='.$row['stc_status_down_list_id'].'&status=update" class="btn bg-secondary text-white mb-3" title="Add TBM"><i class="pe-7s-news-paper"></i></a>
							<select class="stc-set-to-complete" id="'.$row['stc_status_down_list_id'].'">
								<option status="NA">SELECT STATUS</option>
								<option status="1">PLANNING</option>
								<option status="3">WORK-IN-PROGRESS</option>
								<option status="4">WORK-DONE</option>
							</select>
							'.$viewmrd.'
						';
					}elseif($row['stc_status_down_list_status']==3){
						$actionsec='
							<select class="stc-set-to-complete" id="'.$row['stc_status_down_list_id'].'">
								<option status="NA">SELECT STATUS</option>
								<option status="1">PLANNING</option>
								<option status="2">DOWN</option>
								<option status="4">WORK-DONE</option>
							</select>
							'.$viewmrd.'
							<a href="#" class="stc-add-to-pending btn btn-danger" style="font-size:10px;margin-top:4px;" title="Add Pending Reason" id="'.$row['stc_status_down_list_id'].'">Add Pending Reason</a>
							<a href="#" class="stc-add-to-progressreport btn btn-primary" style="font-size:10px;margin-top:4px;" title="Add progress report" id="'.$row['stc_status_down_list_id'].'">Add Progress Report</a>
						';
					}else{
						$actionsec='#';
					}
				}
				if($_SESSION['stc_agent_sub_category']=="Site Incharge"){
					if($row['stc_status_down_list_status']==4){
						$actionsec='
								<div class="col-sm-12 col-md-12">
									<a href="javascript:void(0)" class="btn bg-danger text-white stc-std-operation-btn" type="update" data-toggle="modal" data-target=".bd-create-std-modal" title="Edit Status Down List" id="'.$row['stc_status_down_list_id'].'"><i class="pe-7s-pen"></i></a>
								</div>
								<div class="col-sm-12 col-md-12">
									<a href="javascript:void(0)" class="btn btn-success update-status-si" data-id="'.$row['stc_status_down_list_id'].'" title="Job close" actiontype="5"><i class="fa fa-thumbs-up"></i></a>
								</div>
								<div class="col-sm-12 col-md-12">
										<a href="javascript:void(0)" class="btn btn-info update-status-si" style="background-color: #00f9b4;" data-id="'.$row['stc_status_down_list_id'].'" title="Job down" actiontype="1"><i class="fa fa-thumbs-down"></i></a>
								</div>
								<div class="col-sm-12 col-md-12">
									'.$viewmrd.'		
								</div>
						';
					}else if($row['stc_status_down_list_status']==3 || $row['stc_status_down_list_status']==2 || $row['stc_status_down_list_status']==1){
						$actionsec='
								<div class="col-sm-12 col-md-12">
									<a href="javascript:void(0)" class="btn bg-danger text-white stc-std-operation-btn" type="update" data-toggle="modal" data-target=".bd-create-std-modal" title="Edit Status Down List" id="'.$row['stc_status_down_list_id'].'"><i class="pe-7s-pen"></i></a>
								</div>
								<div class="col-sm-12 col-md-12">
									'.$viewmrd.'		
								</div>
						';
					}else{
						$actionsec='#';
					}
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
				$eq_type=$row['stc_status_down_list_equipment_type'];
				$eq_number='';
				if(ctype_digit($eq_type)){
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
				}

				$job_type='';
				$job_varities='';
				$stc_call_jobtypeqry=mysqli_query($this->stc_dbs, "
					SELECT
					    `stc_status_down_list_job_type_title`,
					    `stc_status_down_list_job_type_sub_title`
					FROM
					    `stc_status_down_list_job_type`
					WHERE
					    `stc_status_down_list_job_type_id`='".$row['stc_status_down_list_varities_id']."'
				");
				foreach($stc_call_jobtypeqry as $stc_call_jobtyperow){
					$job_type=$stc_call_jobtyperow['stc_status_down_list_job_type_title'];
					$job_varities=$stc_call_jobtyperow['stc_status_down_list_job_type_sub_title'];
				}

				$updator_id=$row['stc_status_down_list_updated_by'];
				$updater_name='';
				$updateqry=mysqli_query($this->stc_dbs, "
					SELECT `stc_cust_pro_supervisor_fullname` FROM stc_cust_pro_supervisor WHERE `stc_cust_pro_supervisor_id`=$updator_id
				");
				if(mysqli_num_rows($updateqry)>0){
					$result=mysqli_fetch_assoc($updateqry);
					$updater_name=$result['stc_cust_pro_supervisor_fullname'];
				}
				
				$hidden1 = '';
				$hidden2 = '';
				$hidden3 = '';
				if($_SESSION['stc_agent_sub_category']=='Supervisor' || $_SESSION['stc_agent_sub_category']=='Site Incharge'){
					$hidden1='	
						'.$eqstatus.'
						<td class="text-center">'.$job_type.'</td>
						<td class="text-center">'.$job_varities.'</td>
						<td>'.$row['stc_status_down_list_material_desc'].'</td>
						<td>'.$tar_date.'</td>
						<td>'.$row['stc_status_down_list_jobpending_details'].'</td>
						<td>'.$row['stc_status_down_list_wipstatus'].'</td>
					';
					$hidden3='	
						<td class="text-center">
							'.$actionsec.'
							<input type="hidden" class="stc-std-permit-no-hidden-call" value="'.$row['stc_status_down_list_permit_no'].'" id="'.$row['stc_cust_project_title'].'">
						</td>
					';
				}
				if($_SESSION['stc_agent_sub_category']=='Site Incharge'){
					$updatedon='';
					if(date('Y', strtotime($row['stc_status_down_list_updated_date']))!=1970){
						$updatedon = date('d-m-Y', strtotime($row['stc_status_down_list_updated_date']));
					}
					$ftargetdate=$row['stc_status_down_list_ftarget_date']=='' ? "" : date('d-m-Y', strtotime($row['stc_status_down_list_ftarget_date']));
					$hidden2='	
						<td>'.$row['stc_status_down_list_remarks'].'</td>
						<td>'.$row['stc_status_down_list_failurerootcost'].'</td>
						<td>'.$row['stc_status_down_list_jobdone_details'].'</td>
						<td>'.$row['stc_status_down_list_fremarks'].'</td>
						<td>'.$ftargetdate.'</td>
					';
				}

				$optimusprime.='
					<tr>
						<td>'.date('d-m-Y', strtotime($row['stc_status_down_list_date'])).'</td>
						<td>'.$row['stc_status_down_list_plocation'].'</td>
						<td>'.$row['stc_status_down_list_sub_location'].'</td>
						<td>'.$row['stc_status_down_list_area'].'</td>
						<td>'.$row['stc_status_down_list_jobtype'].'</td>
						<td>'.$row['stc_status_down_list_reason'].'</td>
						<td>'.$eq_type.'</td>
						'.$hidden1.'
						<td>'.$row['stc_status_down_list_creator_details'].'</td>
						<td class="text-center" '.$statusbgcolor.'>'.$status.'</td>
						'.$hidden2.'
						'.$hidden3.'
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

	public function stc_std_call_perticular($std_id){
		$optimusprime='';
		$optimusprimeqry=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_status_down_list` WHERE `stc_status_down_list_id`='".mysqli_real_escape_string($this->stc_dbs, $std_id)."'
		");
		$optimusprime=mysqli_fetch_assoc($optimusprimeqry);
		return $optimusprime;
	}

	// change status
	public function stc_sdl_status_update($status, $sld_id, $jobdonedetails, $work_permit_no){
		$optimusprime='';
		$date=date("Y-m-d H:i:s");
		if($status==4){
			$optimusprime_cqry=mysqli_query($this->stc_dbs, "
				SELECT 
					`stc_status_down_list_qty`,
					`stc_status_down_list_capacity`,
					`stc_status_down_list_reasonattribute`,
					`stc_status_down_list_permit_no`
				FROM `stc_status_down_list` 
				WHERE `stc_status_down_list_id`='".mysqli_real_escape_string($this->stc_dbs, $sld_id)."' 
			");
			$validated=1;
			foreach($optimusprime_cqry as $optimusprime_crow){
				if(($optimusprime_crow['stc_status_down_list_qty']==0) || ($optimusprime_crow['stc_status_down_list_capacity']=='') || ($optimusprime_crow['stc_status_down_list_reasonattribute']=='')){
					$validated=0;
					break;
				}
			}
			if($validated==1){
				$optimusprime_qry=mysqli_query($this->stc_dbs, "
					UPDATE
						`stc_status_down_list`
					SET
						`stc_status_down_list_rect_date`='".mysqli_real_escape_string($this->stc_dbs, $date)."',
						`stc_status_down_list_equipment_status`='Running',
						`stc_status_down_list_permit_no`='".mysqli_real_escape_string($this->stc_dbs, $work_permit_no)."',
						`stc_status_down_list_status`='".mysqli_real_escape_string($this->stc_dbs, $status)."',
						`stc_status_down_list_jobdone_details`='".mysqli_real_escape_string($this->stc_dbs, $jobdonedetails)."'  
					WHERE
						`stc_status_down_list_id`='".mysqli_real_escape_string($this->stc_dbs, $sld_id)."'
				");
				$optimusprime = 'Status Updated!!!';
			}else{
				$optimusprime = 'Status not updated. Please fill all fields.';
			}
		}else{
			$optimusprime_qry=mysqli_query($this->stc_dbs, "
				UPDATE 
					`stc_status_down_list` 
				SET 
					`stc_status_down_list_status`='".mysqli_real_escape_string($this->stc_dbs, $status)."',
					`stc_status_down_list_jobdone_details`='".mysqli_real_escape_string($this->stc_dbs, $jobdonedetails)."'
				WHERE 
					`stc_status_down_list_id`='".mysqli_real_escape_string($this->stc_dbs, $sld_id)."'
			");
			$optimusprime = 'Status Updated!!!';
		}
		return $optimusprime;
	}

	// save job pending
	public function stc_sdl_jobpending_save($sld_id, $jobpendingdetails){
		$optimusprime='';
		
		$currentDateTime = date("Y-m-d H:i:s"); // Get current date and time in the default format
		// Extract date and time separately
		$date = date("d/m/Y", strtotime($currentDateTime)); // Convert to the desired date format
		$time = date('h:i A', strtotime($currentDateTime)); // Convert to the desired time format
		$newDateTime = $date . ' ' . $time; // Combine date and time
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
		$pendingcall = $pendingcall!='' ? $pendingcall.=", " : $pendingcall;
		$pending=$pendingcall.$newDateTime.' - '.$jobpendingdetails;
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
	
	// save job pending
	public function stc_sdl_progressreport_save($sld_id, $progressreport){
		$optimusprime='';
		$currentDateTime = date("Y-m-d H:i:s"); // Get current date and time in the default format

		// Extract date and time separately
		$date = date("d/m/Y", strtotime($currentDateTime)); // Convert to the desired date format
		$time = date('h:i A', strtotime($currentDateTime)); // Convert to the desired time format

		$newDateTime = $date . ' ' . $time; // Combine date and time
		$optimusprime_qry=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_status_down_list_wipstatus` 
			FROM 
				`stc_status_down_list`
			WHERE 
				`stc_status_down_list_id`='".mysqli_real_escape_string($this->stc_dbs, $sld_id)."'
		");
		$progressreportcall='';
		foreach($optimusprime_qry as $optimusprime_row){
			$progressreportcall=$optimusprime_row['stc_status_down_list_wipstatus'];
		}
		$progressreportcall = $progressreportcall!='' ? $progressreportcall.=", " : $progressreportcall;
		$progressreport=$progressreportcall.$newDateTime.' - '.$progressreport;
		$optimusprime_updateqry=mysqli_query($this->stc_dbs, "
			UPDATE 
				`stc_status_down_list` 
			SET 
				`stc_status_down_list_wipstatus`='".mysqli_real_escape_string($this->stc_dbs, $progressreport)."' 
			WHERE 
				`stc_status_down_list_id`='".mysqli_real_escape_string($this->stc_dbs, $sld_id)."'
		");
		if($optimusprime_updateqry){
			$optimusprime='Progress Report Saved!!!';
		}else{
			$optimusprime='Hmmm!!! Somethig went wrong on updating progress report.';
		}
		return $optimusprime;
	}

	// update for complete or pending via site incharge
	
	public function stc_sdl_workcomplete_update($sld_id, $actiontype){
		$optimusprime='';
		$date=date("Y-m-d H:i:s");
		$actiontype=$actiontype==5 ? 6 : 1; 
		$optimusprime_updateqry=mysqli_query($this->stc_dbs, "
			UPDATE 
				`stc_status_down_list` 
			SET 
				`stc_status_down_list_status`='".mysqli_real_escape_string($this->stc_dbs, $actiontype)."',
				`stc_status_down_list_status_updated_by`='".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_agent_sub_id'])."',
				`stc_status_down_list_status_updated_on`='".mysqli_real_escape_string($this->stc_dbs, $date)."' 
			WHERE 
				`stc_status_down_list_id`='".mysqli_real_escape_string($this->stc_dbs, $sld_id)."'
		");
		if($optimusprime_updateqry){
			$optimusprime='Status updated!!!';
		}else{
			$optimusprime='Hmmm!!! Somethig went wrong on updating pending reason.';
		}
		return $optimusprime;		
	}

	// /call material list
	public function stc_call_std_material($sdl_id){
		$ivar='';
		$slno=0;
		$reqno=0;
		$reqdate='';
		$downlistdate='';
		$sublocation='';
		$ivarquery=mysqli_query($this->stc_dbs, "
		   SELECT DISTINCT
			  `stc_cust_super_requisition_list_items`.`stc_cust_super_requisition_list_id` as reqlistid,
			  DATE(`stc_cust_super_requisition_list_date`) as stc_req_date,
			  `stc_status_down_list_sub_location`,
			  `stc_status_down_list_date`,
			  `stc_cust_super_requisition_list_items_req_id`,
			  `stc_cust_super_requisition_list_items_title`,
			  `stc_cust_super_requisition_list_items_unit`,
			  `stc_cust_super_requisition_list_items_reqqty`,
			  `stc_cust_super_requisition_list_items_approved_qty`,
			  `stc_cust_super_requisition_items_finalqty`,
			  `stc_cust_super_requisition_list_items_status`,
			  `stc_cust_super_requisition_items_priority`,
			  `stc_cust_pro_supervisor_fullname`,
			  `stc_cust_pro_supervisor_contact`,
			  `stc_cust_super_requisition_list_status`
		   FROM `stc_cust_super_requisition_list_items`
		   LEFT JOIN `stc_cust_super_requisition_list` 
		   ON `stc_cust_super_requisition_list_items_req_id`=`stc_cust_super_requisition_list`.`stc_cust_super_requisition_list_id`
		   LEFT JOIN `stc_status_down_list` 
		   ON `stc_status_down_list_id`=`stc_cust_super_requisition_list`.`stc_cust_super_requisition_list_sdlid`
		   LEFT JOIN `stc_cust_pro_supervisor` 
		   ON `stc_cust_pro_supervisor_id`=`stc_cust_super_requisition_list_super_id`
		   WHERE 
			  `stc_cust_super_requisition_list_sdlid`='".mysqli_real_escape_string($this->stc_dbs, $sdl_id)."'
		   ORDER BY DATE(`stc_cust_super_requisition_list_date`) DESC
		");
		if(mysqli_num_rows($ivarquery)>0){
			foreach($ivarquery as$requisitionrow){
				$slno++;
				$rqitemstts='';
				$stcdispatchedqty=0;
				$stcrecievedqty=0;
				$stcpendingqty=0;      
				$downlistdate=date('d-m-Y', strtotime($requisitionrow['stc_status_down_list_date']));
				$reqno=$requisitionrow['reqlistid'];
				$sublocation=$requisitionrow['stc_status_down_list_sub_location'];            
				$reqdate=date('d-m-Y', strtotime($requisitionrow['stc_req_date']));
				$SupName=$requisitionrow['stc_cust_pro_supervisor_fullname'];            
				$SupContact=$requisitionrow['stc_cust_pro_supervisor_contact'];            
				if($requisitionrow['stc_cust_super_requisition_list_items_status']==1){
					$rqitemstts='ALLOW';
				}elseif($requisitionrow['stc_cust_super_requisition_list_items_status']==2){
					$rqitemstts='DIRECT';
				}else{
					$rqitemstts='NOT ALLOW';
				}
				$stcdecqtyqry=mysqli_query($this->stc_dbs, "
					SELECT 
						  `stc_cust_super_requisition_list_items_rec_recqty`
					FROM `stc_cust_super_requisition_list_items_rec` 
					WHERE 
						  `stc_cust_super_requisition_list_items_rec_list_id`='".$requisitionrow['stc_cust_super_requisition_list_items_req_id']."' 
					AND `stc_cust_super_requisition_list_items_rec_list_item_id`='".$requisitionrow['reqlistid']."'  
				");
				foreach($stcdecqtyqry as $dispatchedrow){
					$stcdispatchedqty+=$dispatchedrow['stc_cust_super_requisition_list_items_rec_recqty'];
				}
  
				$stcrecqtyqry=mysqli_query($this->stc_dbs, "
					SELECT 
						  `stc_cust_super_requisition_rec_items_fr_supervisor_rqitemqty`
					FROM `stc_cust_super_requisition_rec_items_fr_supervisor` 
					WHERE `stc_cust_super_requisition_rec_items_fr_supervisor_rqitemid`='".$requisitionrow['reqlistid']."'  
				");
				foreach($stcrecqtyqry as $recievedrow){
					$stcrecievedqty+=$recievedrow['stc_cust_super_requisition_rec_items_fr_supervisor_rqitemqty'];
				}
  
				$stcconsumedqty=0;
				$stcconsrecqtyqry=mysqli_query($this->stc_dbs, "
					SELECT 
						  SUM(`stc_cust_super_list_items_consumption_items_qty`) AS consumable_qty
					FROM `stc_cust_super_list_items_consumption_items` 
					WHERE `stc_cust_super_list_items_consumption_items_name`='".$requisitionrow['reqlistid']."'  
				");
				foreach($stcconsrecqtyqry as $consumedrow){
					$stcconsumedqty+=$consumedrow['consumable_qty'];
				}
  
				$stcpendingqty=$requisitionrow['stc_cust_super_requisition_items_finalqty'] - $stcdispatchedqty;
				if($stcpendingqty>0){
					$stcpendingqty='
						  <p class="form-control" style="
							  background: #ffd81a;
							  color: red;
						  ">
							  '.number_format($stcpendingqty, 2).'
						  </p>
					';
				}else{
					$stcpendingqty=number_format($stcpendingqty, 2);
				}
				$priority=$requisitionrow['stc_cust_super_requisition_items_priority']==2 ? "Urgent" : "Normal";
				$stockqty=$stcrecievedqty - $stcconsumedqty;
				$passmaterial='
					<a href="javascript:void(0)" class="btn btn-primary update-to-proc" id="'.$requisitionrow['reqlistid'].'" reqid="'.$requisitionrow['stc_cust_super_requisition_list_items_req_id'].'"><i class="fa fa-plus"></i></a>
					<a href="javascript:void(0)" class="btn btn-danger remove-to-proc" id="'.$requisitionrow['reqlistid'].'" reqid="'.$requisitionrow['stc_cust_super_requisition_list_items_req_id'].'"><i class="fa fa-trash"></i></a>
				';
				if($requisitionrow['stc_cust_super_requisition_list_status']>1){
					$passmaterial="#";
				}
				if($requisitionrow['stc_cust_super_requisition_list_items_approved_qty']>0){
					$finalqty=number_format($requisitionrow	['stc_cust_super_requisition_list_items_approved_qty'], 2);
					$passmaterial="#";
				}else{
					$finalqty='<input type="number" class="form-control stc-approvqty" id="'.$requisitionrow['reqlistid'].'" 	value="'.$requisitionrow['stc_cust_super_requisition_list_items_reqqty'].'">';
				}
				$ivar.='
						  <tr>
							  <td>'.$slno.'</td>
							  <td>'.$requisitionrow['stc_req_date'].'</td>
							  <td>'.$requisitionrow['stc_cust_super_requisition_list_items_title'].'</td>
							  <td>'.$requisitionrow['stc_cust_super_requisition_list_items_unit'].'</td>
							  <td align="right">'.number_format($requisitionrow['stc_cust_super_requisition_list_items_reqqty'], 2).'</td>
							  <td align="right">'.$finalqty.'</td>
							  <td align="right">'.number_format($requisitionrow['stc_cust_super_requisition_items_finalqty'], 2).'</td>
							  <td align="right">'.number_format($stcdispatchedqty, 2).'</td>
							  <td align="right">'.number_format($stcrecievedqty, 2).'</td>
							  <td align="right">'.$stcpendingqty.'</td>
							  <td align="right">'.number_format($stcconsumedqty, 2).'</td>
							  <td class="text-right">'.number_format($stockqty, 2).'</td>
							  <td>'.$priority.'</td>
							  <td>'.$rqitemstts.'</td>
							  <td class="text-center">'.$passmaterial.'</td>
						  </tr>
				';
			  }
		  }else{
			  $ivar.='
					  <tr>
						  <td colspan="10">No requisition found!!!</td>
					  </tr>
			  ';
		  }
		$out_ivar = array(
		   'data'=> $ivar,
		   'Downlist'=> $sdl_id,
		   'downlistdate'=>$downlistdate,
		   'reqno'=> $reqno,
		   'reqdate'=> $reqdate,
		   'sublocation'=> $sublocation,
		   'SupName'=> $SupName,
		   'SupContact'=>$SupContact,
		);
		return $out_ivar;
	}

	// pass material to procurement
	public function stc_pass_req_material($req_id, $item_id, $appr_qty){
		$ivar='';
		$ivarquery=mysqli_query($this->stc_dbs, "
			SELECT `stc_cust_super_requisition_list_id` 
			FROM `stc_cust_super_requisition_list` 
			WHERE `stc_cust_super_requisition_list_id`='".mysqli_real_escape_string($this->stc_dbs, $req_id)."'
			AND `stc_cust_super_requisition_list_status`>1
		");
		if(mysqli_num_rows($ivarquery)==0){
			$ivaritemquery=mysqli_query($this->stc_dbs, "
				SELECT `stc_cust_super_requisition_list_id` 
				FROM `stc_cust_super_requisition_list_items` 
				WHERE `stc_cust_super_requisition_list_id`='".mysqli_real_escape_string($this->stc_dbs, $item_id)."'
				AND `stc_cust_super_requisition_list_items_reqqty`<='".mysqli_real_escape_string($this->stc_dbs, $appr_qty)."'
			");
			if(mysqli_num_rows($ivaritemquery)>0){
				$ivaritemuquery=mysqli_query($this->stc_dbs, "
					UPDATE `stc_cust_super_requisition_list_items` 
					SET `stc_cust_super_requisition_list_items_approved_qty`='".mysqli_real_escape_string($this->stc_dbs, $appr_qty)."',					`stc_cust_super_requisition_list_items_reqqty`='".mysqli_real_escape_string($this->stc_dbs, $appr_qty)."'  
					WHERE `stc_cust_super_requisition_list_id`='".mysqli_real_escape_string($this->stc_dbs, $item_id)."'
				");
				$ivaritemcquery=mysqli_query($this->stc_dbs, "
					SELECT `stc_cust_super_requisition_list_items_approved_qty` FROM `stc_cust_super_requisition_list_items` 
					WHERE `stc_cust_super_requisition_list_items_req_id`='".mysqli_real_escape_string($this->stc_dbs, $req_id)."'
					AND `stc_cust_super_requisition_list_items_approved_qty`=0
				");
				$ivar="Item added Successfully.";
			}else{
				$ivar="Invalid quantity.";
			}
		}else{
			$ivar="Requisition already passed.";
		}
		return $ivar;
	}
	
	public function stc_sdl_material_requpdate($sdl_id){
		$ivar='';
		$ivaruquery=mysqli_query($this->stc_dbs, "
			UPDATE `stc_cust_super_requisition_list` 
			SET `stc_cust_super_requisition_list_status`='2' 
			WHERE `stc_cust_super_requisition_list_sdlid`='".mysqli_real_escape_string($this->stc_dbs, $sdl_id)."' 
			AND `stc_cust_super_requisition_list_status`='1' 
		");
		return $ivar;
	}
	
	public function stc_sdl_material_reqremove($item_id){
		$ivar='';
		$ivaruquery=mysqli_query($this->stc_dbs, "
			DELETE FROM `stc_cust_super_requisition_list_items` WHERE `stc_cust_super_requisition_list_id`='".mysqli_real_escape_string($this->stc_dbs, $item_id)."'
		");
		return $ivar;
	}
	
}

/*-----------------------------------------------------------------------------------*/
/*---------------------------------For Status Down List------------------------------*/
/*-----------------------------------------------------------------------------------*/
// call location
if(isset($_POST['call_location'])){
	$metabots=new transformers();
	$opmetabots=$metabots->stc_call_locdept();
	echo json_encode($opmetabots);
}

// call departemnt
if(isset($_POST['call_department'])){
	$loca_id=$_POST['loca_id'];
	$metabots=new transformers();
	$opmetabots=$metabots->stc_call_deptloc($loca_id);
	echo json_encode($opmetabots);
}

// call sitename
// if(isset($_POST['call_location'])){
// 	$metabots=new transformers();
// 	$opmetabots=$metabots->stc_call_location();
// 	echo $opmetabots;
// }
if(isset($_POST['stc_std_perticular_jvarities_hit'])){
	$jvarites=$_POST['jvarities'];
	$metabots=new transformers();
	$opmetabots=$metabots->stc_call_jvarities($jvarites);
	echo json_encode($opmetabots);
}

// call department
// if(isset($_POST['call_department'])){
// 	$loca_id=$_POST['loca_id'];
// 	$metabots=new transformers();
// 	$opmetabots=$metabots->stc_call_department($loca_id);
// 	echo $opmetabots;
// }

// call area
if(isset($_POST['call_area'])){
	$loca_id=$_POST['loca_id'];
	$loca_sub_name=$_POST['loca_sub_name'];
	$metabots=new transformers();
	$opmetabots=$metabots->stc_call_area($loca_id, $loca_sub_name);
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
	$stc_slocation=$_POST['stc_slocation'];
	$stc_location=$_POST['stc_location'];
	$stc_dept=$_POST['stc_dept'];
	$stc_area=$_POST['stc_area'];
	$stc_j_plannning=$_POST['stc_j_plannning'];
	$reason=$_POST['reason'];
	$eq_type=$_POST['eq_type'];
	$action_status = $_POST['action_status'];
	$creator_details = $_POST['creator_details'];

	$metabots=new transformers();
	if(empty($_SESSION['stc_agent_sub_id'])){
		$opmetabots="Please login!!!";
	}else{
		$opmetabots=$metabots->stc_std_save($stc_slocation, $stc_location, $stc_dept, $stc_area, $stc_j_plannning, $reason, $eq_type, $action_status, $creator_details);
	}
	echo $opmetabots;
}

// std update manual
if(isset($_POST['stc_std_update_mhit'])){
	$opmetabots='';
	$stc_std_id=$_POST['stc_std_id'];
	$stc_slocation=$_POST['stc_slocation'];
	$stc_location=$_POST['stc_location'];
	$stc_dept=$_POST['stc_dept'];
	$stc_area=$_POST['stc_area'];
	$stc_eq_number=$_POST['stc_eq_number'];
	$stc_eq_type=1;
	$stc_j_varities=$_POST['stc_j_varities'];
	$stc_j_plannning=$_POST['stc_j_plannning'];

	$metabots=new transformers();
	if(empty($_SESSION['stc_agent_sub_id'])){
		$opmetabots="Please login!!!";
	}else{
		$opmetabots=$metabots->stc_std_updatem($stc_std_id, $stc_slocation, $stc_location, $stc_dept, $stc_area, $stc_eq_number, $stc_eq_type, $stc_j_varities, $stc_j_plannning);
	}
	echo $opmetabots;
}

// stc update auto
if(isset($_POST['stc_update_std_hit'])){

	$std_id = $_POST['std_id'];
	$plocation = $_POST['plocation'];
	$location = $_POST['location'];
	$dept = $_POST['dept'];
	$area = $_POST['area'];
	$eq_type = $_POST['eq_type'];
	$eq_status = $_POST['eq_status'];
	$j_plannning = $_POST['j_plannning'];
	$qty = $_POST['qty'];
	$capacity = $_POST['capacity'];
	$reasonattribute = $_POST['reasonattribute'];
	$created_by_se = @$_POST['created_by_se'];
	$permit_no = $_POST['permit_no'];
	$creator_details = @$_POST['creator_details'];
	$r_person = $_POST['r_person'];
	$reason = $_POST['reason'];
	$material_desc = $_POST['material_desc'];
	$manpower_req = $_POST['manpower_req'];
	$target_date = $_POST['target_date'];
	$jobdonedet = @$_POST['jobdonedet'];
	$farootcost = @$_POST['farootcost'];
	$remarks = $_POST['remarks'];

	$metabots=new transformers();
	if(empty($_SESSION['stc_agent_sub_id'])){
		$opmetabots="login";
	}else if($dept=="NA" || $dept=="" || $location=="NA" || $location==""){
		$opmetabots="empty";
	}else{
		$opmetabots=$metabots->stc_std_update($std_id, $plocation, $location, $dept, $area, $eq_type, $eq_status, $j_plannning, $qty, $capacity, $reasonattribute, $created_by_se, $permit_no, $creator_details, $r_person, $reason, $material_desc, $manpower_req, $target_date, $jobdonedet, $farootcost, $remarks);
	}
	echo json_encode($opmetabots);

}

// tool requisition
if(isset($_POST['stc_update_std_toolsreq_hit'])){
	$std_id=$_POST['std_id'];
	$tools_req=$_POST['tools_req'];

	$metabots=new transformers();
	if(empty($_SESSION['stc_agent_sub_id'])){
		$opmetabots="login";
	}else{
		$opmetabots=$metabots->stc_std_update_toolsreq($std_id, $tools_req);
	}
	echo json_encode($opmetabots);
}

// std call
if(isset($_POST['stc_std_perticular_call_hit'])){
	$opmetabots='';
	$std_id=$_POST['std_id'];

	$metabots=new transformers();
	if(empty($_SESSION['stc_agent_sub_id'])){
		$opmetabots="Please login!!!";
	}else{
		$opmetabots=$metabots->stc_std_call_perticular($std_id);
	}
	echo json_encode($opmetabots);
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
	$work_permit_no=@$_POST['work_permit_no'];
	$sdl_status=new transformers();
	$out_sdl_status=$sdl_status->stc_sdl_status_update($status, $sld_id, $jobdonedetails, $work_permit_no);
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

// save progress report
if(isset($_POST['stc_progressreport_save_hit'])){
	$sld_id=$_POST['sdl_id'];
	$progressreport=$_POST['progressreport'];
	$sdl_status=new transformers();
	$out_sdl_status=$sdl_status->stc_sdl_progressreport_save($sld_id, $progressreport);
	echo $out_sdl_status;
}

// update status for job complete or job pending
if(isset($_POST['stc_jobcomplete_update_hit'])){
	$sld_id=$_POST['data_id'];
	$actiontype=$_POST['actiontype'];
	$sdl_status=new transformers();
	$out_sdl_status='';
	if((isset($_SESSION['stc_agent_sub_id'])) && ($_SESSION['stc_agent_sub_id']>0)){
		$out_sdl_status=$sdl_status->stc_sdl_workcomplete_update($sld_id, $actiontype);
	}else{
		$out_sdl_status="logout";
	}
	echo json_encode($out_sdl_status);
}

// call material  sdl
if(isset($_POST['stc_sdl_material_call'])){
	$sdl_id=$_POST['sdl_id'];
	$bjornecustomer=new transformers();   
	$outbjornecustomer=$bjornecustomer->stc_call_std_material($sdl_id);
	echo json_encode($outbjornecustomer);
 }

 // call material  sdl
 if(isset($_POST['stc_req_material_pass'])){
	$req_id=$_POST['req_id'];
	$item_id=$_POST['item_id'];
	$appr_qty=$_POST['appr_qty'];
	$bjornecustomer=new transformers();   
	$outbjornecustomer=$bjornecustomer->stc_pass_req_material($req_id, $item_id, $appr_qty);
	echo json_encode($outbjornecustomer);
}
  
// call material  sdl
if(isset($_POST['stc_sdl_material_requpdate'])){
	$sdl_id=$_POST['sdl_id'];
	$bjornecustomer=new transformers();   
	$outbjornecustomer=$bjornecustomer->stc_sdl_material_requpdate($sdl_id);
	echo json_encode($outbjornecustomer);
}

// call material  sdl
if(isset($_POST['stc_sdl_material_reqremove'])){
	$item_id=$_POST['item_id'];
	$bjornecustomer=new transformers();   
	$outbjornecustomer=$bjornecustomer->stc_sdl_material_reqremove($item_id);
	echo json_encode($outbjornecustomer);
}

?>