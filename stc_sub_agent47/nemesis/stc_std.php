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
	public function stc_std_save($stc_slocation, $stc_location, $stc_dept, $stc_area, $stc_j_plannning, $reason, $action_status){
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
				`stc_status_down_list_equipment_status`,
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
				'".$status."',
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
	public function stc_std_update($std_id, $plocation, $location, $dept, $area, $eq_type, $eq_status, $j_plannning, $qty, $capacity, $reasonattribute, $created_by_se, $permit_no, $creator_details, $r_person, $reason, $material_desc, $manpower_req, $target_date, $jobdonedet, $remarks){
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
		if($_SESSION['stc_agent_sub_category']=='Supervisor' || $_SESSION['stc_agent_sub_category']=='Site Incharge'){
			$head_hidden1='				
				<th class="text-center">EQUIPMENT TYPE</th>
				<th class="text-center">EQUIPMENT STATUS</th>
				<th class="text-center">JOB CATEGORIES</th>
				<th class="text-center">JOB VARIETIES</th>
				<th class="text-center">MATERIAL DESCRIPTION</th>
				<th class="text-center">TARGET DATE</th>
			';
		}
		if($_SESSION['stc_agent_sub_category']=='Site Incharge'){
			$head_hidden2='
			<th class="text-center">PENDING REASON</th>
			<th class="text-center">REMARKS</th>
			<th class="text-center">JOB DONE DETAILS</th>
			<th class="text-center">REMARKS BY BOSS</th>
			<th class="text-center">TARGET DATE BY BOSS</th>
			';
		}
		$optimusprime='
			<table class="table table-bordered table-responsive">
				<thead>
					<tr>
						<th class="text-center">DATE</th>
						<th class="text-center">LOCATION</th>
						<th class="text-center">DEPARTMENT</th>
						<th class="text-center">AREA</th>
						<th class="text-center">TYPE OF JOB</th>
						<th class="text-center">REASON</th>
						'.$head_hidden1.'
						<th class="text-center">STATUS</th>
						'.$head_hidden2.'
						<th class="text-center">ACTION</th>
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
				`stc_cust_pro_supervisor_fullname`
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

				$actionsec='';
				if($_SESSION['stc_agent_sub_category']=="Supervisor" || $_SESSION['stc_agent_sub_category']=="Site Incharge"){
					if($row['stc_status_down_list_status']==1){
						$actionsec='
							<a href="stc-requisition.php?sdl='.$row['stc_status_down_list_id'].'&status=add" class="btn bg-success text-white mb-3" placeholder="Add Requisiton"><i class="pe-7s-note2"></i></a>
							<a href="javascript:void(0)" class="btn bg-danger text-white mb-3 stc-std-operation-btn" type="update" data-toggle="modal" data-target=".bd-create-std-modal" placeholder="Edit Status Down List" id="'.$row['stc_status_down_list_id'].'"><i class="pe-7s-pen"></i></a>
							<select class="stc-set-to-complete" id="'.$row['stc_status_down_list_id'].'">
								<option status="NA">SELECT STATUS</option>
								<option status="2">DOWN</option>
								<option status="3">WORK-IN-PROGRESS</option>
								<option status="4">WORK-DONE</option>
							</select>
							<a href="#" class="stc-add-to-pending btn btn-danger" style="font-size:10px;margin-top:4px;" placeholder="Add Pending Reason" id="'.$row['stc_status_down_list_id'].'">Add Pending Reason</a>
						';
					}elseif($row['stc_status_down_list_status']==2){
						$actionsec='
							<select class="stc-set-to-complete" id="'.$row['stc_status_down_list_id'].'">
								<option status="NA">SELECT STATUS</option>
								<option status="1">PLANNING</option>
								<option status="3">WORK-IN-PROGRESS</option>
								<option status="4">WORK-DONE</option>
							</select>
						';
					}elseif($row['stc_status_down_list_status']==3){
						$actionsec='
							<select class="stc-set-to-complete" id="'.$row['stc_status_down_list_id'].'">
								<option status="NA">SELECT STATUS</option>
								<option status="1">PLANNING</option>
								<option status="2">DOWN</option>
								<option status="4">WORK-DONE</option>
							</select>
						';
					}else{
						$actionsec='#';
					}
				}
				if($_SESSION['stc_agent_sub_category']=="Site Incharge"){
					if($row['stc_status_down_list_status']==4){
						$actionsec='
								<div class="col-sm-12 col-md-12">
									<a href="javascript:void(0)" class="btn bg-danger text-white stc-std-operation-btn" type="update" data-toggle="modal" data-target=".bd-create-std-modal" id="'.$row['stc_status_down_list_id'].'"><i class="pe-7s-pen"></i></a>
								</div>
								<div class="col-sm-12 col-md-12">
									<a href="javascript:void(0)" class="btn btn-success update-status-si" data-id="'.$row['stc_status_down_list_id'].'" actiontype="5"><i class="fa fa-thumbs-up"></i></a>
								</div>
								<div class="col-sm-12 col-md-12">
										<a href="javascript:void(0)" class="btn btn-info update-status-si" style="background-color: #00f9b4;" data-id="'.$row['stc_status_down_list_id'].'" actiontype="1"><i class="fa fa-thumbs-down"></i></a>
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
				if($_SESSION['stc_agent_sub_category']=='Supervisor' || $_SESSION['stc_agent_sub_category']=='Site Incharge'){
					$hidden1='	
						<td>'.$eq_type.'</td>
						'.$eqstatus.'
						<td class="text-center">'.$job_type.'</td>
						<td class="text-center">'.$job_varities.'</td>
						<td>'.$row['stc_status_down_list_material_desc'].'</td>
						<td>'.$tar_date.'</td>
					';
				}
				if($_SESSION['stc_agent_sub_category']=='Site Incharge'){
					$updatedon='';
					if(date('Y', strtotime($row['stc_status_down_list_updated_date']))!=1970){
						$updatedon = date('d-m-Y', strtotime($row['stc_status_down_list_updated_date']));
					}
					$ftargetdate=$row['stc_status_down_list_ftarget_date']=='' ? "" : date('d-m-Y', strtotime($row['stc_status_down_list_ftarget_date']));
					$hidden2='	
						<td>'.$row['stc_status_down_list_jobpending_details'].'</td>
						<td>'.$row['stc_status_down_list_remarks'].'</td>
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
						'.$hidden1.'
						<td class="text-center" '.$statusbgcolor.'>'.$status.'</td>
						'.$hidden2.'
						<td class="text-center">
							'.$actionsec.'
							<input type="hidden" class="stc-std-permit-no-hidden-call" value="'.$row['stc_status_down_list_permit_no'].'" id="'.$row['stc_cust_project_title'].'">
						</td>
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
							`stc_status_down_list_status`='6',
							`stc_status_down_list_rect_date`='".mysqli_real_escape_string($this->stc_dbs, $date)."',
							`stc_status_down_list_equipment_status`='Running',
							`stc_status_down_list_permit_no`='".mysqli_real_escape_string($this->stc_dbs, $work_permit_no)."'  
						WHERE
							`stc_status_down_list_id`='".mysqli_real_escape_string($this->stc_dbs, $sld_id)."'
					");
					$optimusprime = 'Status Updated!!!';
				}else{
					$optimusprime = 'Status not updated. Please fill all fields.';
				}
			}else{
				$optimusprime = 'Status Updated!!!';
			}
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

	// update for complete or pending via site incharge
	
	public function stc_sdl_workcomplete_update($sld_id, $actiontype){
		$optimusprime='';
		$date=date("Y-m-d H:i:s");
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
	$action_status = $_POST['action_status'];

	$metabots=new transformers();
	if(empty($_SESSION['stc_agent_sub_id'])){
		$opmetabots="Please login!!!";
	}else{
		$opmetabots=$metabots->stc_std_save($stc_slocation, $stc_location, $stc_dept, $stc_area, $stc_j_plannning, $reason, $action_status);
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
	$remarks = $_POST['remarks'];

	$metabots=new transformers();
	if(empty($_SESSION['stc_agent_sub_id'])){
		$opmetabots="login";
	}else if($dept=="NA" || $dept=="" || $location=="NA" || $location==""){
		$opmetabots="empty";
	}else{
		$opmetabots=$metabots->stc_std_update($std_id, $plocation, $location, $dept, $area, $eq_type, $eq_status, $j_plannning, $qty, $capacity, $reasonattribute, $created_by_se, $permit_no, $creator_details, $r_person, $reason, $material_desc, $manpower_req, $target_date, $jobdonedet, $remarks);
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
?>