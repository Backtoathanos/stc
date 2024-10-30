<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*------------------------------------------For Agents--------------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
class pirates_project extends tesseract{

	// create project
	public function stc_create_project($pro_cust,$pro_title, $pro_refr,$pro_address, $pro_city, $pro_state, $pro_resperson, $pro_supqty,$pro_begdate, $pro_enddate, $pro_begbudget, $pro_status){
		$blackpearl='';
		$date=date("Y-m-d H:i:s");
		$cptjackcheckqry=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_cust_project` 
			WHERE `stc_cust_project_cust_id`='".mysqli_real_escape_string($this->stc_dbs, $pro_cust)."'
			AND `stc_cust_project_title`='".mysqli_real_escape_string($this->stc_dbs, $pro_title)."'
			AND `stc_cust_project_city_id`='".mysqli_real_escape_string($this->stc_dbs, $pro_city)."'
		");
		if(mysqli_num_rows($cptjackcheckqry)==0){
			$cptjacksparrow=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_cust_project`(
					`stc_cust_project_date`, 
					`stc_cust_project_cust_id`, 
					`stc_cust_project_title`,  
					`stc_cust_project_refr`, 
					`stc_cust_project_address`, 
					`stc_cust_project_city_id`, 
					`stc_cust_project_state_id`, 
					`stc_cust_project_responsive_person`, 
					`stc_cust_project_supervis_qty`, 
					`stc_cust_project_beg_date`, 
					`stc_cust_project_end_date`, 
					`stc_cust_project_beg_budget`, 
					`stc_cust_project_status`, 
					`stc_cust_project_editable_mincount`, 
					`stc_cust_project_editable_maxcount`, 
					`stc_cust_project_createdby`
				) VALUES (
					'".mysqli_real_escape_string($this->stc_dbs, $date)."',
					'".mysqli_real_escape_string($this->stc_dbs, $pro_cust)."',
					'".mysqli_real_escape_string($this->stc_dbs, $pro_title)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $pro_refr)."',
					'".mysqli_real_escape_string($this->stc_dbs, $pro_address)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $pro_city)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $pro_state)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $pro_resperson)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $pro_supqty)."',
					'".mysqli_real_escape_string($this->stc_dbs, $pro_begdate)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $pro_enddate)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $pro_begbudget)."', 
					'".mysqli_real_escape_string($this->stc_dbs, $pro_status)."', 
					'1',
					'5', 
					'".mysqli_real_escape_string($this->stc_dbs, $_SESSION["stc_agent_id"])."'
				)
			");
			if($cptjacksparrow){
				$blackpearl="yes";
			}else{
				$blackpearl="no";
			}
		}else{
			$blackpearl="not";
		}
		return $blackpearl;
	}

	// call project using supervisor
	public function stc_call_linked_projects($supervisor_id){
		$blackpearl='';
		$cptjackcheckqry=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT
				`stc_cust_project_id`,
				`stc_cust_project_title`
			FROM `stc_cust_project` 
			INNER JOIN `stc_cust_pro_attend_supervise` 
			ON `stc_cust_project_id`=`stc_cust_pro_attend_supervise_pro_id` 
			WHERE `stc_cust_pro_attend_supervise_super_id`='".mysqli_real_escape_string($this->stc_dbs, $supervisor_id)."'
		");
		if(mysqli_num_rows($cptjackcheckqry)>0){
			$blackpearl='<option value="0">Please Select Project</option>';
			foreach($cptjackcheckqry as $custtitlerows){
				$blackpearl.="<option value='".$custtitlerows['stc_cust_project_id']."'>".$custtitlerows['stc_cust_project_title']."</option>";
			}
		}else{
			$blackpearl='<option value="0">Please Select Project</option>';
		}
		return $blackpearl;
	}

	// save project
	public function stc_project_details_save($stc_pg_id, $stc_cust_name, $stc_cust_emailid, $stc_cust_cont_no, $stc_purpose, $stc_ref_cont, $stc_job_details, $stc_quote_number, $stc_quote_date, $stc_basic_val, $stc_gst_val, $stc_quoted_by, $stc_mode_of_quotation, $stc_target_price, $stc_status, $stc_remarks, $stc_po_number, $stc_po_value){
		$blackpearl='';
		$date=date("Y-m-d H:i:s");
		$blackpearl_find_in_sea=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_cust_project_details_id` 
			FROM 
				`stc_cust_project_details` 
			WHERE 
				`stc_cust_project_details_pro_title_id`='".$stc_pg_id."'
		");
		if(mysqli_num_rows($blackpearl_find_in_sea)>0){
			$blackpearl="You have already added details on this project.";
		}else{
			$blackpearl_qry=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_cust_project_details`(
					`stc_cust_project_details_date`, 
					`stc_cust_project_details_pro_title_id`, 
					`stc_cust_project_details_cust_name`, 
					`stc_cust_project_details_email_id`, 
					`stc_cust_project_details_cont_no`, 
					`stc_cust_project_details_purpose`, 
					`stc_cust_project_details_ref_contact`, 
					`stc_cust_project_details_job_details`,
					`stc_cust_project_details_quote_number`, 
					`stc_cust_project_details_quote_date`, 
					`stc_cust_project_details_basic_val`, 
					`stc_cust_project_details_gst_val`, 
					`stc_cust_project_details_quotation_by`, 
					`stc_cust_project_details_mode_of_quote`, 
					`stc_cust_project_details_tar_price`, 
					`stc_cust_project_details_status`, 
					`stc_cust_project_details_remarks`, 
					`stc_cust_project_details_po_number`,
					`stc_cust_project_details_po_value`,
					`stc_cust_project_details_created_by`
				) VALUES (
					'".mysqli_real_escape_string($this->stc_dbs, $date)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_pg_id)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cust_name)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cust_emailid)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cust_cont_no)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_purpose)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_ref_cont)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_job_details)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_quote_number)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_quote_date)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_basic_val)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_gst_val)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_quoted_by)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_mode_of_quotation)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_target_price)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_status)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_remarks)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_po_number)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_po_value)."',
					'".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_agent_id'])."'					
				)
			");
			if($blackpearl_qry){
				$blackpearl='Details added successfully.';
			}else{
				$blackpearl='Hmm!!! Something went wrong on adding project details.';
			}
		}
		return $blackpearl;
	}

	// call project detials
	public function stc_call_project_details($project_id){
		$blackpearl='';
		$blackpearl_pd_qry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_cust_project_title`,
				`stc_cust_project_details_cust_name`,
				`stc_cust_project_details_email_id`,
				`stc_cust_project_details_cont_no`,
				`stc_cust_project_details_purpose`,
				`stc_cust_project_details_ref_contact`,
				`stc_cust_project_details_job_details`,
				`stc_cust_project_details_quote_number`,
				`stc_cust_project_details_quote_date`,
				`stc_cust_project_details_basic_val`,
				`stc_cust_project_details_gst_val`,
				`stc_cust_project_details_quotation_by`,
				`stc_cust_project_details_mode_of_quote`,
				`stc_cust_project_details_tar_price`,
				`stc_cust_project_details_status`,
				`stc_cust_project_details_remarks`,
				`stc_cust_project_details_po_number`,
				`stc_cust_project_details_po_value`
			FROM `stc_cust_project_details`
			INNER JOIN `stc_cust_project` 
			ON `stc_cust_project_id` = `stc_cust_project_details_pro_title_id`
			WHERE
				`stc_cust_project_details_pro_title_id`='".mysqli_real_escape_string($this->stc_dbs, $project_id)."'
		");
		$blackpearl=((mysqli_num_rows($blackpearl_pd_qry)>0) ? mysqli_fetch_assoc($blackpearl_pd_qry) : 'NA');
		return $blackpearl;
	}

	// call connected supervisor for project
	public function stc_call_connected_supervisor($project_id){
		$blackpearl='';
		$blackpearl_pd_qry=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT `stc_cust_pro_supervisor_fullname`, `stc_cust_pro_supervisor_category`
			FROM `stc_cust_pro_attend_supervise`
			LEFT JOIN `stc_cust_pro_supervisor`
			ON `stc_cust_pro_supervisor_id` = `stc_cust_pro_attend_supervise_super_id`
			WHERE `stc_cust_pro_attend_supervise_pro_id` = '".mysqli_real_escape_string($this->stc_dbs, $project_id)."'
			ORDER BY `stc_cust_pro_supervisor_fullname` ASC
		");
		if(mysqli_num_rows($blackpearl_pd_qry)>0){
			$slno=0;
			foreach($blackpearl_pd_qry as $blackpearl_pd_row){
				$slno++;
				$blackpearl.='
					<tr>
						<td class="text-center">'.$slno.'</td>
						<td>'.$blackpearl_pd_row['stc_cust_pro_supervisor_fullname'].'</td>
						<td class="text-center">'.$blackpearl_pd_row['stc_cust_pro_supervisor_category'].'</td>
					</tr>
				';
			}
		}else{
			$blackpearl='<tr><td>Supervisor not found.</td></tr>';
		}
		return $blackpearl;
	}

	// call project ahu details
	public function stc_call_project_ahu_details($project_id){
		$blackpearl='';
		$blackpearl_ahuqry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_cahud_id`,
				`stc_cahud_date`,
				`stc_cust_project_title`,
				`stc_cahud_sub_location`,
				`stc_cahud_location`,
				`stc_cahud_ahuno`,
				`stc_cahud_design_temp`,
				`stc_cahud_airfilter_size`,
				`stc_cahud_airfilter_qty`,
				`stc_cahud_vbelt_size`,
				`stc_cahud_vbelt_qty`,
				`stc_cahud_pulleysize_blower`,
				`stc_cahud_pulleysize_motor`,
				`stc_cahud_blowerbearingsize_de`,
				`stc_cahud_blowerbearingsize_nde`,
				`stc_cahud_blowerbearingsize_housing`,
				`stc_cahud_motorbearingsize_de`,
				`stc_cahud_motorbearingsize_nde`,
				`stc_cahud_motorcap_hp`,
				`stc_cahud_motor_kw`,
				`stc_cahud_motor_amps`,
				`stc_cahud_motor_rpm`,
				`stc_cahud_motorwt`,
				`stc_cahud_frame_size`,
				`stc_cahud_cfm`,
				`stc_cahud_createdby`
			FROM
				`stc_customer_ahu_details`
			INNER JOIN 
				`stc_cust_project`
			ON 
				`stc_cust_project_id`=`stc_cahud_dept`
			WHERE
				`stc_cahud_dept`='".mysqli_real_escape_string($this->stc_dbs, $project_id)."'
		");
		if(mysqli_num_rows($blackpearl_ahuqry)>0){
			foreach($blackpearl_ahuqry as $row){
				$blackpearl.='
					<tr>
						<td>'.$row['stc_cust_project_title'].'</td>
						<td>'.$row['stc_cahud_sub_location'].'</td>
						<td>'.$row['stc_cahud_location'].'</td>
						<td>'.$row['stc_cahud_ahuno'].'</td>
						<td>'.$row['stc_cahud_design_temp'].'</td>
						<td>'.$row['stc_cahud_airfilter_size'].'</td>
						<td>'.$row['stc_cahud_airfilter_qty'].'</td>
						<td>'.$row['stc_cahud_vbelt_size'].'</td>
						<td>'.$row['stc_cahud_vbelt_qty'].'</td>
						<td>'.$row['stc_cahud_pulleysize_blower'].'</td>
						<td>'.$row['stc_cahud_pulleysize_motor'].'</td>
						<td>'.$row['stc_cahud_blowerbearingsize_de'].'</td>
						<td>'.$row['stc_cahud_blowerbearingsize_nde'].'</td>
						<td>'.$row['stc_cahud_blowerbearingsize_housing'].'</td>
						<td>'.$row['stc_cahud_motorbearingsize_de'].'</td>
						<td>'.$row['stc_cahud_motorbearingsize_nde'].'</td>
						<td>'.$row['stc_cahud_motorcap_hp'].'</td>
						<td>'.$row['stc_cahud_motor_kw'].'</td>
						<td>'.$row['stc_cahud_motor_amps'].'</td>
						<td>'.$row['stc_cahud_motor_rpm'].'</td>
						<td>'.$row['stc_cahud_motorwt'].'</td>
						<td>'.$row['stc_cahud_frame_size'].'</td>
						<td>'.$row['stc_cahud_cfm'].'</td>
					</tr>
				';
			}

		}else{
			$blackpearl='
				<tr>
					<td colspan="30" class="text-center">No Data Found</td>
				</tr>
			';
		}
		return $blackpearl;
	}
	
	// call project pump details
	public function stc_call_project_pump_details($project_id){
		$blackpearl='';
		$blackpearl_ahuqry=mysqli_query($this->stc_dbs, "
			SELECT
			    `stc_cpumpd_id`,
			    `stc_cpumpd_date`,
			    `stc_cust_project_title`,
			    `stc_cpumpd_sub_location`,
			    `stc_cpumpd_area`,
			    `stc_cpumpd_equipment_type`,
			    `stc_cpumpd_equipment_number`,
			    `stc_cpumpd_totaltr`,
			    `stc_cpumpd_unit`,
			    `stc_cpumpd_createdby`
			FROM
				`stc_customer_pump_details`
			INNER JOIN 
				`stc_cust_project`
			ON 
				`stc_cust_project_id`=`stc_cpumpd_location`
			WHERE
				`stc_cpumpd_location`='".mysqli_real_escape_string($this->stc_dbs, $project_id)."'
		");
		if(mysqli_num_rows($blackpearl_ahuqry)>0){
			foreach($blackpearl_ahuqry as $row){
				$blackpearl.='
					<tr>
						<td class="text-center">'.$row['stc_cust_project_title'].'</td>
						<td>'.$row['stc_cpumpd_sub_location'].'</td>
						<td>'.$row['stc_cpumpd_area'].'</td>
						<td>'.$row['stc_cpumpd_equipment_type'].'</td>
						<td>'.$row['stc_cpumpd_equipment_number'].'</td>
						<td class="text-right">'.$row['stc_cpumpd_totaltr'].'</td>
						<td class="text-center">'.$row['stc_cpumpd_unit'].'</td>
						<td><a href="#" class="stc-cust-pump-details-del" id="'.$row['stc_cpumpd_id'].'">Remove</a></td>
					</tr>
				';
			}

		}else{
			$blackpearl='
				<tr>
					<td colspan="30" class="text-center">No Data Found</td>
				</tr>
			';
		}
		return $blackpearl;
	}

	// edit project details
	public function stc_edit_project_details($pro_id, $pro_title, $pro_refr, $pro_details){		
		$blackpearl='';
		$blackpearl_qry=mysqli_query($this->stc_dbs, "
			UPDATE
				`stc_cust_project`
			SET
				`stc_cust_project_title` = '".mysqli_real_escape_string($this->stc_dbs, $pro_title)."',
				`stc_cust_project_refr` = '".mysqli_real_escape_string($this->stc_dbs, $pro_refr)."',
				`stc_cust_project_address` = '".mysqli_real_escape_string($this->stc_dbs, $pro_details)."'
			WHERE
				`stc_cust_project_id`='".mysqli_real_escape_string($this->stc_dbs, $pro_id)."'
		");
		if($blackpearl_qry){
			$blackpearl='Details Edited Successfully.';
		}else{
			$blackpearl='Hmm!!! Something went wrong on editing project details.';
		}
		return $blackpearl;
	}

	// call ahu details
	public function stc_ahu_details($dept_id){
		$blackpearl='';
		$blackpearl_pd_qry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_cahud_id`,
				`stc_cahud_date`,
				`stc_cahud_dept`,
				`stc_cahud_sub_location`,
				`stc_cahud_location`,
				`stc_cahud_ahuno`,
				`stc_cahud_design_temp`,
				`stc_cahud_airfilter_size`,
				`stc_cahud_airfilter_qty`,
				`stc_cahud_vbelt_size`,
				`stc_cahud_vbelt_qty`,
				`stc_cahud_pulleysize_blower`,
				`stc_cahud_pulleysize_motor`,
				`stc_cahud_blowerbearingsize_de`,
				`stc_cahud_blowerbearingsize_nde`,
				`stc_cahud_blowerbearingsize_housing`,
				`stc_cahud_motorbearingsize_de`,
				`stc_cahud_motorbearingsize_nde`,
				`stc_cahud_motorcap_hp`,
				`stc_cahud_motor_kw`,
				`stc_cahud_motor_amps`,
				`stc_cahud_motor_rpm`,
				`stc_cahud_motorwt`,
				`stc_cahud_frame_size`,
				`stc_cahud_cfm`,
				`stc_cahud_createdby`
			FROM
				`stc_customer_ahu_details`
			WHERE
				`stc_cahud_dept`='".mysqli_real_escape_string($this->stc_dbs, $dept_id)."'
		");
		$blackpearl=((mysqli_num_rows($blackpearl_pd_qry)>0) ? mysqli_fetch_assoc($blackpearl_pd_qry) : 'NA');
		return $blackpearl;
	}

	// save ahu details
	public function stc_ahu_details_set($cust_dept_id, $stc_cust_location_name, $stc_cust_sub_location_name, $stc_cust_ahu_no, $stc_cust_desgn_temp, $stc_cust_air_size, $stc_cust_air_qty, $stc_cust_v_belt_size, $stc_cust_v_belt_qty, $stc_cust_blower_pulley_size, $stc_cus_motor_pulley_size, $stc_cus_blower_bearing_de, $stc_cus_blower_bearing_nde, $stc_cus_blower_bearing_housing, $stc_cus_motor_bearing_de, $stc_cus_motor_bearing_nde, $stc_cus_motor_capacity_hp, $stc_cus_motor_capacity_kw, $stc_cus_motor_capacity_amps, $stc_cus_motor_capacity_rpm, $stc_cus_motor_weight, $stc_cus_ahu_frame_size, $stc_cus_ahu_cfm, $ahu_type){
		$blackpearl='';
		$ahu_id=0;
		$blackpearl_checkahuexistance=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_cahud_id` 
			FROM 
				`stc_customer_ahu_details` 
			WHERE 
				`stc_cahud_dept`='".mysqli_real_escape_string($this->stc_dbs, $cust_dept_id)."'
		");
		foreach($blackpearl_checkahuexistance as $fetch_row){
			$ahu_id=$fetch_row['stc_cahud_id'];
		}
		$date=date("Y-m-d H:i:s");
		$blackpearl_set = mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_customer_ahu_details`(
					`stc_cahud_date`,
					`stc_cahud_dept`,
					`stc_cahud_sub_location`,
					`stc_cahud_location`,
					`stc_cahud_ahuno`,
					`stc_cahud_design_temp`,
					`stc_cahud_airfilter_size`,
					`stc_cahud_airfilter_qty`,
					`stc_cahud_vbelt_size`,
					`stc_cahud_vbelt_qty`,
					`stc_cahud_pulleysize_blower`,
					`stc_cahud_pulleysize_motor`,
					`stc_cahud_blowerbearingsize_de`,
					`stc_cahud_blowerbearingsize_nde`,
					`stc_cahud_blowerbearingsize_housing`,
					`stc_cahud_motorbearingsize_de`,
					`stc_cahud_motorbearingsize_nde`,
					`stc_cahud_motorcap_hp`,
					`stc_cahud_motor_kw`,
					`stc_cahud_motor_amps`,
					`stc_cahud_motor_rpm`,
					`stc_cahud_motorwt`,
					`stc_cahud_frame_size`,
					`stc_cahud_cfm`,
					`stc_cahud_createdby`
				)VALUES(
					'".mysqli_real_escape_string($this->stc_dbs, $date)."',
					'".mysqli_real_escape_string($this->stc_dbs, $cust_dept_id)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cust_location_name)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cust_sub_location_name)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cust_ahu_no)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cust_desgn_temp)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cust_air_size)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cust_air_qty)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cust_v_belt_size)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cust_v_belt_qty)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cust_blower_pulley_size)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cus_motor_pulley_size)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cus_blower_bearing_de)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cus_blower_bearing_nde)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cus_blower_bearing_housing)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cus_motor_bearing_de)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cus_motor_bearing_nde)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cus_motor_capacity_hp)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cus_motor_capacity_kw)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cus_motor_capacity_amps)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cus_motor_capacity_rpm)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cus_motor_weight)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cus_ahu_frame_size)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cus_ahu_cfm)."',
					'".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_agent_id'])."'
				)
		");
		$blackpearl=((mysqli_num_rows($blackpearl_set)>0) ? 'Yes' : 'No');
		return $blackpearl;

	}

	public function stc_pump_details_set($stc_cust_pro_location_id, $stc_cust_sub_location, $stc_cust_area, $stc_cust_equipment_type, $stc_cust_equipment_number, $stc_cus_totaltr, $stc_cus_Unit){
		$blackpearl='';
		$ahu_id=0;
		$blackpearl_checkahuexistance=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_cahud_id` 
			FROM 
				`stc_customer_ahu_details` 
			WHERE 
				`stc_cahud_dept`='".mysqli_real_escape_string($this->stc_dbs, $stc_cust_pro_location_id)."'
		");
		foreach($blackpearl_checkahuexistance as $fetch_row){
			$ahu_id=$fetch_row['stc_cahud_id'];
		}
		// if($ahu_type=="New"){
			$date=date("Y-m-d H:i:s");
			$blackpearl_set = mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_customer_pump_details`(
				    `stc_cpumpd_date`,
				    `stc_cpumpd_location`,
				    `stc_cpumpd_sub_location`,
				    `stc_cpumpd_area`,
				    `stc_cpumpd_equipment_type`,
				    `stc_cpumpd_equipment_number`,
				    `stc_cpumpd_totaltr`,
				    `stc_cpumpd_unit`,
				    `stc_cpumpd_createdby`
				)VALUES(
					'".mysqli_real_escape_string($this->stc_dbs, $date)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cust_pro_location_id)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cust_sub_location)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cust_area)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cust_equipment_type)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cust_equipment_number)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cus_totaltr)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cus_Unit)."',
					'".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_agent_id'])."'
				)
			");
			$blackpearl=($blackpearl_set>0) ? 'Yes' : 'No';
		return $blackpearl;
	}

	// save job type
	public function stc_job_type_set($stc_cust_job_type, $stc_job_varities){
		$blackpearl='';
		$blackpearl_qry=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_status_down_list_job_type` 
			WHERE `stc_status_down_list_job_type_title`='".mysqli_real_escape_string($this->stc_dbs, $stc_cust_job_type)."' 
			AND `stc_status_down_list_job_type_sub_title`='".mysqli_real_escape_string($this->stc_dbs, $stc_job_varities)."'
		");
		if(mysqli_num_rows($blackpearl_qry)>0){
			$blackpearl='duplicate';
		}else{
			$blackpearl_qry_set=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_status_down_list_job_type`(
					`stc_status_down_list_job_type_title`,
					`stc_status_down_list_job_type_sub_title`,
					`stc_status_down_list_job_type_created_by`
				) VALUES(
					'".mysqli_real_escape_string($this->stc_dbs, $stc_cust_job_type)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_job_varities)."',
					'".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_agent_id'])."'
				)
			");
			if($blackpearl_qry_set){
				$blackpearl="yes";
			}else{
				$blackpearl="No";
			}
		}
		return $blackpearl;
	}

	// remove pump details
	public function stc_remove_pump_details($stc_pump_det_id){
		$blackpearl='';
		$blackpearl_checkqry=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_status_down_list` 
			WHERE `stc_status_down_list_equipment_type`='".mysqli_real_escape_string($this->stc_dbs, $stc_pump_det_id)."' OR `stc_status_down_list_equipment_number`='".mysqli_real_escape_string($this->stc_dbs, $stc_pump_det_id)."' 
		");
		if(mysqli_num_rows($blackpearl_checkqry)>0){
			$blackpearl='duplicate';
		}else{
			$blackpearl_qry_set=mysqli_query($this->stc_dbs, "
				DELETE FROM `stc_customer_pump_details` WHERE `stc_cpumpd_id`='".mysqli_real_escape_string($this->stc_dbs, $stc_pump_det_id)."'
			");
			if($blackpearl_qry_set){
				$blackpearl="yes";
			}else{
				$blackpearl="No";
			}
		}
		return $blackpearl;
	}

	// save department
	public function stc_department_set($stc_locname, $stc_deptname, $stc_dept_pro_id){
		$blackpearl='';
		$blackpearl_qry=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_status_down_list_department` 
			WHERE  `stc_status_down_list_department_loc_id`='".mysqli_real_escape_string($this->stc_dbs, $stc_dept_pro_id)."'  
			AND `stc_status_down_list_department_location`='".mysqli_real_escape_string($this->stc_dbs, $stc_locname)."' 
			AND `stc_status_down_list_department_dept`='".mysqli_real_escape_string($this->stc_dbs, $stc_deptname)."'
		");
		if(mysqli_num_rows($blackpearl_qry)>0){
			$blackpearl='duplicate';
		}else{
			$blackpearl_qry_set=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_status_down_list_department`(
					`stc_status_down_list_department_loc_id`,
					`stc_status_down_list_department_location`,
					`stc_status_down_list_department_dept`,
					`stc_status_down_list_department_created_by`
				) VALUES(
					'".mysqli_real_escape_string($this->stc_dbs, $stc_dept_pro_id)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_locname)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_deptname)."',
					'".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_agent_id'])."'
				)
			");
			if($blackpearl_qry_set){
				$blackpearl="yes";
			}else{
				$blackpearl="No";
			}
		}
		return $blackpearl;
	}

	// show job type
	public function stc_job_type_show(){
		$blackpearl='';
		$blackpearl_checkqry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_status_down_list_job_type_id`,
				`stc_status_down_list_job_type_date`,
				`stc_status_down_list_job_type_title`,
				`stc_status_down_list_job_type_sub_title`,
				`stc_status_down_list_job_type_created_by`
			FROM `stc_status_down_list_job_type`	
			ORDER BY `stc_status_down_list_job_type_title` ASC
		");
		if(mysqli_num_rows($blackpearl_checkqry)>0){
			$slno=0;
			foreach($blackpearl_checkqry as $blackpearl_checkrow){
				$slno++;
				$blackpearl.='
					<tr>
						<td>'.$slno.'</td>
						<td>'.$blackpearl_checkrow['stc_status_down_list_job_type_title'].'
							<select class="form-control res-hidejt'.$blackpearl_checkrow['stc_status_down_list_job_type_id'].'" style="display:none;">
                        	    <option>ELECTRICAL</option>
                        	    <option>MECHANICAL</option>
                        	    <option selected>'.$blackpearl_checkrow['stc_status_down_list_job_type_title'].'</option>
                        	    <option>REFRIGERATION</option>
                        	    <option>CLEANING</option>
                        	    <option>NOISY</option>
                        	    <option>WATER DROPPING</option>
                        	</select>
						</td>
						<td>'.$blackpearl_checkrow['stc_status_down_list_job_type_sub_title'].'
							<input type="text" class="form-control res-hidevj'.$blackpearl_checkrow['stc_status_down_list_job_type_id'].'" style="display:none;" value="'.$blackpearl_checkrow['stc_status_down_list_job_type_sub_title'].'">
						</td>
						<td>
							<a href="#" class="form-control req-hide job-type-edit-btn" id="'.$blackpearl_checkrow['stc_status_down_list_job_type_id'].'">Edit</a>
							<a href="#" class="form-control res-hide'.$blackpearl_checkrow['stc_status_down_list_job_type_id'].' job-type-save-btn" style="display:none;" id="'.$blackpearl_checkrow['stc_status_down_list_job_type_id'].'">Save</a>
						</td>
					</tr>
				';
			}
		}else{
			$blackpearl.='
				<tr>
					<td colspan="3">No records found!!!</td>
				</tr>
			';
		}
		return $blackpearl;
	}

	// show department
	public function stc_department_show(){
		$blackpearl='';
		$blackpearl_checkqry=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_status_down_list_department_id`,
				`stc_status_down_list_department_date`,
				`stc_cust_project_title`,
				`stc_status_down_list_department_location`,
				`stc_status_down_list_department_dept`,
				`stc_status_down_list_department_created_by`
			FROM `stc_agent_requested_customer`
			INNER JOIN `stc_cust_project`
			ON `stc_cust_project_cust_id`=`stc_agent_requested_customer_cust_id`
			INNER JOIN `stc_status_down_list_department`
			ON `stc_status_down_list_department_loc_id`=`stc_cust_project_id`
			WHERE `stc_agent_requested_customer_agent_id`='".mysqli_real_escape_string($this->stc_dbs, @$_SESSION['stc_agent_id'])."'
			ORDER BY `stc_status_down_list_department_location` ASC
		");
		if(mysqli_num_rows($blackpearl_checkqry)>0){
			$slno=0;
			foreach($blackpearl_checkqry as $blackpearl_checkrow){
				$slno++;
				$blackpearl.='
					<tr>
						<td>'.$slno.'</td>
						<td>'.$blackpearl_checkrow['stc_cust_project_title'].'
						</td>
						<td>'.$blackpearl_checkrow['stc_status_down_list_department_location'].'
							<input type="text" class="form-control res-hidedl'.$blackpearl_checkrow['stc_status_down_list_department_id'].'" style="display:none;" value="'.$blackpearl_checkrow['stc_status_down_list_department_location'].'">
						</td>
						<td>'.$blackpearl_checkrow['stc_status_down_list_department_dept'].'
							<input type="text" class="form-control res-hidedd'.$blackpearl_checkrow['stc_status_down_list_department_id'].'" style="display:none;" value="'.$blackpearl_checkrow['stc_status_down_list_department_dept'].'">
						</td>
						<td>
							<a href="#" class="form-control req-hide dept-edit-btn" id="'.$blackpearl_checkrow['stc_status_down_list_department_id'].'">Edit</a>
							<a href="#" class="form-control res-hide'.$blackpearl_checkrow['stc_status_down_list_department_id'].' dept-save-btn" style="display:none;" id="'.$blackpearl_checkrow['stc_status_down_list_department_id'].'">Save</a>
						</td>
					</tr>
				';
			}
		}else{
			$blackpearl.='
				<tr>
					<td colspan="3">No records found!!!</td>
				</tr>
			';
		}
		return $blackpearl;
	}

	// save
	public function stc_job_type_show_update($jobtype, $jobvarities, $jobtid){
		$blackpearl='';
		$blackpearl_qry=mysqli_query($this->stc_dbs, "
			UPDATE `stc_status_down_list_job_type` SET `stc_status_down_list_job_type_title`='".mysqli_real_escape_string($this->stc_dbs, $jobtype)."', `stc_status_down_list_job_type_sub_title`='".mysqli_real_escape_string($this->stc_dbs, $jobvarities)."' WHERE `stc_status_down_list_job_type_id`='".mysqli_real_escape_string($this->stc_dbs, $jobtid)."'
		");
		if($blackpearl_qry){
			$blackpearl="Job type updated!!!";
		}else{
			$blackpearl="Hmmm!!! Something went wrong, Job type not updated!!!";
		}
		return $blackpearl;
	}

	// save
	public function stc_department_show_update($loc, $dept, $jobtid){
		$blackpearl='';
		$blackpearl_qry=mysqli_query($this->stc_dbs, "
			UPDATE 
				`stc_status_down_list_department` 
			SET 
				`stc_status_down_list_department_location`='".mysqli_real_escape_string($this->stc_dbs, $loc)."', 
				`stc_status_down_list_department_dept`='".mysqli_real_escape_string($this->stc_dbs, $dept)."' 
			WHERE 
				`stc_status_down_list_department_id`='".mysqli_real_escape_string($this->stc_dbs, $jobtid)."'
		");
		if($blackpearl_qry){
			$blackpearl="Department updated!!!";
		}else{
			$blackpearl="Hmmm!!! Something went wrong, Department not updated!!!";
		}
		return $blackpearl;
	}

	// save procurment 
	public function stc_save_procurement_create($pro_id, $item_name, $service, $unit, $quantity){
		$blackpearl='';
		$date=date("Y-m-d H:i:s");
		$blackpearl_qry=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_cust_procurement_tracker`(
			    `stc_cust_procurement_tracker_date`,
			    `stc_cust_procurement_tracker_project_id`,
			    `stc_cust_procurement_tracker_item_title`,
			    `stc_cust_procurement_tracker_service`,
			    `stc_cust_procurement_tracker_unit`,
			    `stc_cust_procurement_tracker_qty`,
			    `stc_cust_procurement_tracker_created_by`
			) VALUES (
				'".mysqli_real_escape_string($this->stc_dbs, $date)."',
				'".mysqli_real_escape_string($this->stc_dbs, $pro_id)."',
				'".mysqli_real_escape_string($this->stc_dbs, $item_name)."',
				'".mysqli_real_escape_string($this->stc_dbs, $service)."',
				'".mysqli_real_escape_string($this->stc_dbs, $unit)."',
				'".mysqli_real_escape_string($this->stc_dbs, $quantity)."',
				'".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_agent_id'])."'
			)
		");
		if($blackpearl_qry){
			$blackpearl="yes";
		}else{
			$blackpearl="no";
		}
		return $blackpearl;
	}

	// call procurment tracker 
	public function stc_save_procurement_call($by_location, $by_maker, $by_item){
		$blackpearl='';
		$additional_search='';
		if($by_location>0){
			$additional_search.=' AND `stc_cust_procurement_tracker_project_id`="'.mysqli_real_escape_string($this->stc_dbs, $by_location).'"';
		}
		if($by_maker!=''){
			$additional_search.=' AND `stc_cust_procurement_tracker_buyer` REGEXP "'.mysqli_real_escape_string($this->stc_dbs, $by_maker).'"';
		}
		if($by_item!=''){
			$additional_search.=' AND `stc_cust_procurement_tracker_item_title` REGEXP "'.mysqli_real_escape_string($this->stc_dbs, $by_item).'"';
		}
		// $additional_search=$searchbylocation.$searchbymaker.$searchbyitem;
		$blackpearl_query="
			SELECT
			    `stc_cust_procurement_tracker_id`,
			    `stc_cust_procurement_tracker_date`,
			    `stc_cust_project_title`,
			    `stc_cust_procurement_tracker_item_title`,
			    `stc_cust_procurement_tracker_service`,
			    `stc_cust_procurement_tracker_unit`,
			    `stc_cust_procurement_tracker_qty`,
			    `stc_cust_procurement_tracker_po_qnty`,
			    `stc_cust_procurement_tracker_material_readiness_status`,
			    `stc_cust_procurement_tracker_recieved_qnty`,
			    `stc_cust_procurement_tracker_storedin`,
			    `stc_cust_procurement_tracker_buyer`,
			    `stc_cust_procurement_tracker_po_no`,
			    `stc_cust_procurement_tracker_po_date`,
			    `stc_cust_procurement_tracker_basicamt`,
			    `stc_cust_procurement_tracker_gst`,
			    `stc_cust_procurement_tracker_approval_date`,
			    `stc_cust_procurement_tracker_mfg_clearancedate`,
			    `stc_cust_procurement_tracker_mfg_leadtime`,
			    `stc_cust_procurement_tracker_location`,
			    `stc_cust_procurement_tracker_transittime`,
			    `stc_cust_procurement_tracker_deleverytimeplan`,
			    `stc_cust_procurement_tracker_delivered_actual`,
			    `stc_cust_procurement_tracker_transport_charge`,
			    `stc_cust_procurement_tracker_remartks`,
			    `stc_agents_name`
			FROM
			    `stc_cust_procurement_tracker`
			LEFT JOIN 
			    `stc_cust_project`
			ON 
				`stc_cust_procurement_tracker_project_id`=`stc_cust_project_id`
			LEFT JOIN 
			    `stc_agents`
			ON 
				`stc_cust_procurement_tracker_created_by`=`stc_agents_id`
			WHERE
				`stc_cust_procurement_tracker_created_by`='".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_agent_id'])."'
				".$additional_search."
			ORDER BY `stc_cust_procurement_tracker_id` DESC
		";
		$blackpearl_result=mysqli_query($this->stc_dbs, $blackpearl_query);

		if(mysqli_num_rows($blackpearl_result)>0){
			foreach($blackpearl_result as $blackpearl_row){
				$basicamt=$blackpearl_row['stc_cust_procurement_tracker_po_qnty'] * $blackpearl_row['stc_cust_procurement_tracker_basicamt'];
				$total=$basicamt + ($basicamt * $blackpearl_row['stc_cust_procurement_tracker_gst']/100);
				$loc_id=$blackpearl_row['stc_cust_procurement_tracker_location'];
				$cityquery=mysqli_query($this->stc_dbs, "
					SELECT `stc_city_name` FROM `stc_city` WHERE `stc_city_id`=$loc_id
				");
				$cityname='';
				foreach($cityquery as $cityrow){
					$cityname=$cityrow['stc_city_name'];
				}

				$material_readyness_status="No";
				if($blackpearl_row['stc_cust_procurement_tracker_material_readiness_status']==1){
					$material_readyness_status="Yes";
				}

				$pro_id=$blackpearl_row['stc_cust_procurement_tracker_id'];
				$paymentquery=mysqli_query($this->stc_dbs, "
					SELECT
					    `stc_cust_procurement_tracker_payments_id`,
					    `stc_cust_procurement_tracker_payments_tracid`,
					    `stc_cust_procurement_tracker_payments_date`,
					    `stc_cust_procurement_tracker_payments_actual_date`,
					    `stc_cust_procurement_tracker_payments_amount`,
					    `stc_cust_procurement_tracker_payments_type`,
					    `stc_cust_procurement_tracker_payments_created_by`
					FROM
					    `stc_cust_procurement_tracker_payments`
					WHERE
					    `stc_cust_procurement_tracker_payments_tracid`=$pro_id
				");
				$advanceamt='';
				$advancedate='';
				$advanceactdate='';
				$advancecolorchange='';
				$dispatchamt='';
				$dispatchdate='';
				$dispatchactdate='';
				$dispatchcolorchange='';
				$pdcamt='';
				$pdcdate='';
				foreach($paymentquery as $paymentrow){
					if($paymentrow['stc_cust_procurement_tracker_payments_type']=="advance"){
						$advanceamt.=$paymentrow['stc_cust_procurement_tracker_payments_amount'].'<br>';
						$advancedate.=date('d-m-Y', strtotime($paymentrow['stc_cust_procurement_tracker_payments_date'])).'<br>';
						if($paymentrow['stc_cust_procurement_tracker_payments_actual_date']==''){
							$advancecolorchange="red";
							$advanceactdate.='<a href="#" class="btn btn-success form-control acutal-payment-dateset" id="'.$paymentrow['stc_cust_procurement_tracker_payments_id'].'">Paid?</a>';
						}else{
							$advanceactdate.=date('d-m-Y', strtotime($paymentrow['stc_cust_procurement_tracker_payments_actual_date'])).'<br>';
						}
					}elseif($paymentrow['stc_cust_procurement_tracker_payments_type']=="dispatch"){
						$dispatchamt.=$paymentrow['stc_cust_procurement_tracker_payments_amount'].'<br>';
						$dispatchdate.=date('d-m-Y', strtotime($paymentrow['stc_cust_procurement_tracker_payments_date'])).'<br>';
						if($paymentrow['stc_cust_procurement_tracker_payments_actual_date']==''){
							$dispatchcolorchange="red";
							$dispatchactdate.='<a href="#" class="btn btn-success form-control acutal-payment-dateset" id="'.$paymentrow['stc_cust_procurement_tracker_payments_id'].'">Paid?</a>';
						}else{
							$dispatchactdate.=date('d-m-Y', strtotime($paymentrow['stc_cust_procurement_tracker_payments_actual_date'])).'<br>';
						}
					}else{
						$pdcamt.=$paymentrow['stc_cust_procurement_tracker_payments_amount'].'<br>';
						$pdcdate.=date('d-m-Y', strtotime($paymentrow['stc_cust_procurement_tracker_payments_date'])).'<br>';
					}
				}

				$paymentcol="
					<td style='background-color:".$advancecolorchange."'>".$advanceamt."</td>
					<td style='background-color:".$advancecolorchange."'>".$advancedate."</td>
					<td style='background-color:".$advancecolorchange."'>".$advanceactdate."</td>
					<td>".$material_readyness_status."</td>
					<td style='background-color:".$dispatchcolorchange."'>".$dispatchamt."</td>
					<td style='background-color:".$dispatchcolorchange."'>".$dispatchdate."</td>
					<td style='background-color:".$dispatchcolorchange."'>".$dispatchactdate."</td>
					<td>".$pdcamt."</td>
					<td>".$pdcdate."</td>
				";
				$dispatchqry=mysqli_query($this->stc_dbs, "
					SELECT
					    `stc_cust_procurement_tracker_dispatch_challan_no`,
					    `stc_cust_procurement_tracker_dispatch_qty`
					FROM
					    `stc_cust_procurement_tracker_dispatch`
					WHERE
					    `stc_cust_procurement_tracker_dispatch_itemid`='".$blackpearl_row['stc_cust_procurement_tracker_id']."'
				");
				$challan_no='';
				$quantity_show='';
				$quantity_count=0;
				foreach($dispatchqry as $dispatchrow){
					$challan_no.=$dispatchrow['stc_cust_procurement_tracker_dispatch_challan_no'].'<br>';
					$quantity_show.=number_format($dispatchrow['stc_cust_procurement_tracker_dispatch_qty'], 2).'<br>';
					$quantity_count+=$dispatchrow['stc_cust_procurement_tracker_dispatch_qty'];
				}
				$balanceqty=$blackpearl_row['stc_cust_procurement_tracker_recieved_qnty'] - $quantity_count;

				$blackpearl.="
					<tr>
						<td>".$blackpearl_row['stc_cust_procurement_tracker_id']."0</td>
						<td>".$blackpearl_row['stc_cust_procurement_tracker_item_title']."</td>
						<td>".$blackpearl_row['stc_cust_procurement_tracker_service']."</td>
						<td>".$blackpearl_row['stc_cust_procurement_tracker_unit']."</td>
						<td class='text-right'>".number_format($blackpearl_row['stc_cust_procurement_tracker_qty'], 2)."</td>
						<td class='text-right'>".number_format($blackpearl_row['stc_cust_procurement_tracker_po_qnty'], 2)."</td>
						<td>".$blackpearl_row['stc_cust_procurement_tracker_buyer']."</td>
						<td>".$blackpearl_row['stc_cust_procurement_tracker_po_no']."</td>
						<td>".$blackpearl_row['stc_cust_procurement_tracker_po_date']."</td>
						<td class='text-right'>".number_format($basicamt, 2)."</td>
						<td>".$blackpearl_row['stc_cust_procurement_tracker_gst']."%</td>
						<td class='text-right'>".number_format($total, 2)."</td>
						<td>".$blackpearl_row['stc_cust_procurement_tracker_approval_date']."</td>
						<td>".$blackpearl_row['stc_cust_procurement_tracker_mfg_clearancedate']."</td>
						<td>".$blackpearl_row['stc_cust_procurement_tracker_mfg_leadtime']."</td>
						<td>".$cityname."</td>
						<td>".$blackpearl_row['stc_cust_procurement_tracker_transittime']."</td>
						<td>".$blackpearl_row['stc_cust_procurement_tracker_deleverytimeplan']."</td>
						<td>".$blackpearl_row['stc_cust_procurement_tracker_delivered_actual']."</td>
						".$paymentcol."
						<td class='text-right'>".number_format($blackpearl_row['stc_cust_procurement_tracker_transport_charge'], 2)."</td>
						<td class='text-right'>".number_format($blackpearl_row['stc_cust_procurement_tracker_recieved_qnty'], 2)."</td>
						<td>".$blackpearl_row['stc_cust_procurement_tracker_storedin']."</td>
						<td>".$challan_no."</td>
						<td class='text-right'>".$quantity_show."</td>
						<td class='text-right'>".number_format($balanceqty, 2)."</td>
						<td>".$blackpearl_row['stc_cust_procurement_tracker_remartks']."</td>
						<td>
							<a href='#' style='color: #97b7ff; font-size: 25px; padding: 5px; margin: 5px; background: #ffffba; border-radius: 45%;' id='".$blackpearl_row['stc_cust_procurement_tracker_id']."' class='stc-tra-addmod'><i class='fa fa-plus'></i></a>
							<a href='#' style='color: grey; font-size: 25px; padding: 5px; margin: 5px; background: #ffffba; border-radius: 45%;' id='".$blackpearl_row['stc_cust_procurement_tracker_id']."' class='stc-tra-paymod'><i class='fa fa-credit-card'></i></a>
							<a href='#' style='color: #97b7ff; font-size: 25px; padding: 5px; margin: 5px; background: #ffffba; border-radius: 45%;' id='".$blackpearl_row['stc_cust_procurement_tracker_id']."' class='stc-tra-recievemod'><i class='fa fa-clipboard'></i></a>
							<a href='#' style='color: #5f11a7; font-size: 25px; padding: 5px; margin: 5px; background: #ffffba; border-radius: 45%;' id='".$blackpearl_row['stc_cust_procurement_tracker_id']."' class='stc-tra-dispatchmod'><i class='fa fa-truck'></i></a>
							<a href='#' style='color: red; font-size: 25px; padding: 5px; margin: 5px; background: #ffffba; border-radius: 45%;' id='".$blackpearl_row['stc_cust_procurement_tracker_id']."' class='stc-tra-deletemod'><i class='fa fa-trash'></i></a>
						</td>
					</tr>
				";
			}
		}else{
			$blackpearl.="
				<tr>
					<td colspan='7' class='text-center'> No data found!!</td>
				</tr>
			";
		}
		return $blackpearl;
	}

	// delete procurment
	public function stc_save_procurement_delete($proc_id){
		$blackpearl="";
		$blackpearl_query=mysqli_query($this->stc_dbs, "
			DELETE FROM `stc_cust_procurement_tracker` WHERE `stc_cust_procurement_tracker_id` = '".mysqli_real_escape_string($this->stc_dbs, $proc_id)."'
		");
		if($blackpearl_query){
			$blackpearl="yes";
			$blackpearl_query=mysqli_query($this->stc_dbs, "
				DELETE FROM `stc_cust_procurement_tracker_dispatch` WHERE `stc_cust_procurement_tracker_dispatch_itemid` = '".mysqli_real_escape_string($this->stc_dbs, $proc_id)."'
			");$blackpearl_query=mysqli_query($this->stc_dbs, "
				DELETE FROM `stc_cust_procurement_tracker_payments` WHERE `stc_cust_procurement_tracker_payments_tracid` = '".mysqli_real_escape_string($this->stc_dbs, $proc_id)."'
			");
		}else{
			$blackpearl="no";
		}
		return $blackpearl;
	}

	// call perticular procurement tracker
	public function stc_procurement_tracker_perticular_call($proc_id){
		$blackpearl="";
		$blackpearl_query=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_cust_procurement_tracker` WHERE `stc_cust_procurement_tracker_id`='".mysqli_real_escape_string($this->stc_dbs, $proc_id)."'
		");
		$blackpearl=mysqli_fetch_assoc($blackpearl_query);
		return $blackpearl;
	}

	// update perticular procurement tracker
	public function stc_procurement_tracker_update($proc_id, $po_qnty, $buyer, $po_no_id, $po_no_date, $amount, $gst, $approval, $mfgclear, $leadtime, $dealer_loca, $transittime, $plan, $actual, $transport_charge, $mreadiness, $remarks){
		$blackpearl="";
		$blackpearl_qry=mysqli_query($this->stc_dbs, "
			UPDATE
			    `stc_cust_procurement_tracker`
			SET
			    `stc_cust_procurement_tracker_po_qnty` = '".mysqli_real_escape_string($this->stc_dbs, $po_qnty)."',
			    `stc_cust_procurement_tracker_buyer` = '".mysqli_real_escape_string($this->stc_dbs, $buyer)."',
			    `stc_cust_procurement_tracker_po_no` = '".mysqli_real_escape_string($this->stc_dbs, $po_no_id)."',
			    `stc_cust_procurement_tracker_po_date` = '".mysqli_real_escape_string($this->stc_dbs, $po_no_date)."',
			    `stc_cust_procurement_tracker_basicamt` = '".mysqli_real_escape_string($this->stc_dbs, $amount)."',
			    `stc_cust_procurement_tracker_gst` = '".mysqli_real_escape_string($this->stc_dbs, $gst)."',
			    `stc_cust_procurement_tracker_approval_date` = '".mysqli_real_escape_string($this->stc_dbs, $approval)."',
			    `stc_cust_procurement_tracker_mfg_clearancedate` = '".mysqli_real_escape_string($this->stc_dbs, $mfgclear)."',
			    `stc_cust_procurement_tracker_mfg_leadtime` = '".mysqli_real_escape_string($this->stc_dbs, $leadtime)."',
			    `stc_cust_procurement_tracker_location` = '".mysqli_real_escape_string($this->stc_dbs, $dealer_loca)."',
			    `stc_cust_procurement_tracker_transittime` = '".mysqli_real_escape_string($this->stc_dbs, $transittime)."',
			    `stc_cust_procurement_tracker_deleverytimeplan` = '".mysqli_real_escape_string($this->stc_dbs, $plan)."',
			    `stc_cust_procurement_tracker_delivered_actual` = '".mysqli_real_escape_string($this->stc_dbs, $actual)."',
			    `stc_cust_procurement_tracker_transport_charge` = '".mysqli_real_escape_string($this->stc_dbs, $transport_charge)."',
			    `stc_cust_procurement_tracker_material_readiness_status` = '".mysqli_real_escape_string($this->stc_dbs, $mreadiness)."',
			    `stc_cust_procurement_tracker_remartks` = '".mysqli_real_escape_string($this->stc_dbs, $remarks)."'
			WHERE
			    `stc_cust_procurement_tracker_id` = '".mysqli_real_escape_string($this->stc_dbs, $proc_id)."'
		");
		if($blackpearl_qry){
			$blackpearl="yes";
		}else{
			$blackpearl="no";
		}
		return $blackpearl;
	}

	// save payment
	public function stc_procurement_tracker_payment_save($proc_id ,$pay_date ,$pay_type ,$pay_amount){
		$blackpearl='';
		$blackpearl_qry=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_cust_procurement_tracker_payments`(
				`stc_cust_procurement_tracker_payments_tracid`,
				`stc_cust_procurement_tracker_payments_date`,
				`stc_cust_procurement_tracker_payments_amount`,
				`stc_cust_procurement_tracker_payments_type`,
				`stc_cust_procurement_tracker_payments_created_by`
			)VALUES(
				'".mysqli_real_escape_string($this->stc_dbs, $proc_id)."',
				'".mysqli_real_escape_string($this->stc_dbs, date('Y-m-d', strtotime($pay_date)))."',
				'".mysqli_real_escape_string($this->stc_dbs, $pay_amount)."',
				'".mysqli_real_escape_string($this->stc_dbs, $pay_type)."',
				'".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_agent_id'])."'
			)
		");
		if($blackpearl_qry){
			$blackpearl = "yes";
		}else{
			$blackpearl = "no";
		}
		return $blackpearl;
	}

	// save receiving
	public function stc_procurement_tracker_receiving_save($proc_id ,$rec_quantity ,$rec_storein){
		$blackpearl='';
		$blackpearl_qry=mysqli_query($this->stc_dbs, "
			UPDATE 
				`stc_cust_procurement_tracker` 
			SET 
				`stc_cust_procurement_tracker_recieved_qnty`='".mysqli_real_escape_string($this->stc_dbs, $rec_quantity)."',
				`stc_cust_procurement_tracker_storedin`='".mysqli_real_escape_string($this->stc_dbs, $rec_storein)."' 
			WHERE 
				`stc_cust_procurement_tracker_id`='".mysqli_real_escape_string($this->stc_dbs, $proc_id)."'
		");
		if($blackpearl_qry){
			$blackpearl = "yes";
		}else{
			$blackpearl = "no";
		}
		return $blackpearl;
	}

	// save dispatch
	public function stc_procurement_tracker_despatch_save($proc_id ,$dec_quantity ,$des_challanno){
		$blackpearl='';
		$date=date("Y-m-d H:i:s");
		$blackpearl_qry=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_cust_procurement_tracker_dispatch`(
			    `stc_cust_procurement_tracker_dispatch_date`,
			    `stc_cust_procurement_tracker_dispatch_itemid`,
			    `stc_cust_procurement_tracker_dispatch_challan_no`,
			    `stc_cust_procurement_tracker_dispatch_qty`,
			    `stc_cust_procurement_tracker_dispatch_createdby`
			) VALUES (
				'".mysqli_real_escape_string($this->stc_dbs, $date)."',
				'".mysqli_real_escape_string($this->stc_dbs, $proc_id)."',
				'".mysqli_real_escape_string($this->stc_dbs, $des_challanno)."',
				'".mysqli_real_escape_string($this->stc_dbs, $dec_quantity)."',
				'".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_agent_id'])."'
			)
		");
		if($blackpearl_qry){
			$blackpearl = "yes";
		}else{
			$blackpearl = "no";
		}
		return $blackpearl;
	}
	
	// update procurement tracker payment date
	public function stc_procurement_tracker_payment_update($pay_id){
		$blackpearl='';
		$date=date("Y-m-d H:i:s");
		$blackpearl_qry=mysqli_query($this->stc_dbs, "
			UPDATE `stc_cust_procurement_tracker_payments` SET `stc_cust_procurement_tracker_payments_actual_date`='".mysqli_real_escape_string($this->stc_dbs, $date)."' WHERE `stc_cust_procurement_tracker_payments_id`='".mysqli_real_escape_string($this->stc_dbs, $pay_id)."'
		");
		if($blackpearl_qry){
			$blackpearl = "yes";
		}else{
			$blackpearl = "no";
		}
		return $blackpearl;
	}

	// call project collaborated
	public function stc_project_collaborated($id){		
		$blackpearl='';
		$date=date("Y-m-d H:i:s");
		$blackpearl_qry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_cust_project_collaborate_id`,
				`stc_cust_project_collaborate_managerid`,
				`stc_cust_project_collaborate_teamid`,
				`stc_cust_project_collaborate_projectid`,
				`stc_cust_project_collaborate_status`,
				`stc_cust_project_collaborate_created_date`,
				`stc_agents_name`
			FROM `stc_cust_project_collaborate`
			LEFT JOIN `stc_agents`
			ON `stc_cust_project_collaborate_teamid`=`stc_agents_id`
			WHERE
				`stc_cust_project_collaborate_projectid`='".mysqli_real_escape_string($this->stc_dbs, $id)."'
		");
		if(mysqli_num_rows($blackpearl_qry)>0){
			foreach($blackpearl_qry as $blackpearl_row){
				$status=$blackpearl_row['stc_cust_project_collaborate_status']==1 ? "Active" : "in-active";
				$blackpearl.='
					<tr>
						<td class="text-center">'.date('d-m-Y', strtotime($blackpearl_row['stc_cust_project_collaborate_created_date'])).'</td>
						<td>'.$blackpearl_row['stc_agents_name'].'</td>
						<td class="text-center">'.$status.'</td>
						<td class="text-center"><a href="javascript:void(0)" class="btn btn-danger" '.$blackpearl_row['stc_cust_project_collaborate_id'].'><i class="fa fa-remove"></i></a></td>
					<tr>
				';
			}
		}else{
			$blackpearl.='
				<tr>
					<td colspan="4" class="text-center">No record found.</td>
				<tr>
			';
		}
		return $blackpearl;
	}

	// save project collaborate
	public function stc_project_collaborate_save($project_id, $email){
		$blackpearl='';
		$status="success";
		$message="Project collaborated successfully.";
		$date=date("Y-m-d H:i:s");
		$blackpearl_cqry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_agents_id`
			FROM
				`stc_agents`
			WHERE
				`stc_agents_email`='".mysqli_real_escape_string($this->stc_dbs, $email)."'
		");
		if(mysqli_num_rows($blackpearl_cqry)>0){
			$result=mysqli_fetch_assoc($blackpearl_cqry);
			$team_id=$result['stc_agents_id'];
			$blackpearl_iqry=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_cust_project_collaborate`(
					`stc_cust_project_collaborate_managerid`,
					`stc_cust_project_collaborate_teamid`,
					`stc_cust_project_collaborate_projectid`,
					`stc_cust_project_collaborate_status`,
					`stc_cust_project_collaborate_created_date`
				)VALUES(
					'".$_SESSION['stc_agent_id']."',
					'".mysqli_real_escape_string($this->stc_dbs, $team_id)."',
					'".mysqli_real_escape_string($this->stc_dbs, $project_id)."',
					'1',
					'".mysqli_real_escape_string($this->stc_dbs, $date)."'
				)
			");
		}else{
			$status="invalid";
			$message="Invalid email. Manager not found with this email. Please try registered email.";
		}
		$blackpearl_arr=array(
			'status' => $status,
			'message' => $message
		);
		return $blackpearl_arr;
	}

	// save item tracker 
	public function stc_item_tracker_save($user_id, $ppe_type, $qty, $unit, $issue_date, $validity, $remarks){
		$blackpearl='';
		$date=date("Y-m-d H:i:s");
		$blackpearl_qry=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_item_tracker`(
			    `stc_item_tracker_user_id`,
			    `stc_item_tracker_toppe`,
			    `stc_item_tracker_qty`,
			    `stc_item_tracker_unit`,
			    `stc_item_tracker_issuedate`,
			    `stc_item_tracker_validity`,
			    `stc_item_tracker_remarks`,
			    `stc_item_tracker_createdby`,
			    `stc_item_tracker_created_date`
			) VALUES (
				'".mysqli_real_escape_string($this->stc_dbs, $user_id)."',
				'".mysqli_real_escape_string($this->stc_dbs, $ppe_type)."',
				'".mysqli_real_escape_string($this->stc_dbs, $qty)."',
				'".mysqli_real_escape_string($this->stc_dbs, $unit)."',
				'".mysqli_real_escape_string($this->stc_dbs, $issue_date)."',
				'".mysqli_real_escape_string($this->stc_dbs, $validity)."',
				'".mysqli_real_escape_string($this->stc_dbs, $remarks)."',
				'".mysqli_real_escape_string($this->stc_dbs, $_SESSION['stc_agent_id'])."',
				'".$date."'
			)
		");
		if($blackpearl_qry){
			$blackpearl="yes";
		}else{
			$blackpearl="no";
		}
		return $blackpearl;
	}

	// call procurment tracker 
	public function stc_item_tracker_call(){
		$blackpearl='';
		$blackpearl_query="
			SELECT
			    `stc_item_tracker_id`,
			    `stc_item_tracker_user_id`,
			    `stc_cust_pro_supervisor_fullname`,
			    `stc_item_tracker_toppe`,
			    `stc_item_tracker_qty`,
			    `stc_item_tracker_unit`,
			    `stc_item_tracker_issuedate`,
			    `stc_item_tracker_validity`,
			    `stc_item_tracker_remarks`,
			    `stc_item_tracker_createdby`,
			    `stc_item_tracker_created_date`
			FROM `stc_item_tracker`
			LEFT JOIN `stc_cust_pro_supervisor`
			ON `stc_cust_pro_supervisor_id`=`stc_item_tracker_user_id`
		";
		$blackpearl_result=mysqli_query($this->stc_dbs, $blackpearl_query);

		if(mysqli_num_rows($blackpearl_result)>0){
			foreach($blackpearl_result as $blackpearl_row){
				$validity=$blackpearl_row['stc_item_tracker_validity']==1 ? $blackpearl_row['stc_item_tracker_validity'].' month' : $blackpearl_row['stc_item_tracker_validity']." months";
				$validityMonths = $blackpearl_row['stc_item_tracker_validity'];
				$issuedate = new DateTime($blackpearl_row['stc_item_tracker_issuedate']);
				$nextissuedate = $issuedate->add(new DateInterval("P{$validityMonths}M"));
				$nextissuedateFormatted = $nextissuedate->format('d-m-Y');

				$loc_qry=mysqli_query($this->stc_dbs, "
					SELECT DISTINCT `stc_cust_project_title`
					FROM `stc_cust_pro_attend_supervise`
					LEFT JOIN `stc_cust_project`
					ON `stc_cust_pro_attend_supervise_pro_id`=`stc_cust_project_id`
					WHERE `stc_cust_pro_attend_supervise_super_id`='".$blackpearl_row['stc_item_tracker_user_id']."'
				");
				$location="";
				foreach($loc_qry as $loc_row){
					$location=$loc_row['stc_cust_project_title'].'<br>';
				}

				$blackpearl.="
					<tr>
						<td>".$blackpearl_row['stc_item_tracker_id']."</td>
						<td>".$blackpearl_row['stc_cust_pro_supervisor_fullname']."</td>
						<td>".$blackpearl_row['stc_item_tracker_toppe']."</td>
						<td>".$location."</td>
						<td class='text-right'>".number_format($blackpearl_row['stc_item_tracker_qty'], 2)."</td>
						<td class='text-center'>".$blackpearl_row['stc_item_tracker_unit']."</td>
						<td class='text-center'>".date('d-m-Y', strtotime($blackpearl_row['stc_item_tracker_issuedate']))."</td>
						<td class='text-center'>".$validity."</td>
						<td class='text-center'>".$nextissuedateFormatted."</td>
						<td>".$blackpearl_row['stc_item_tracker_remarks']."</td>
					</tr>
				";
				
				// <td class='text-center'><a href='javascript:void(0)' id='".$blackpearl_row['stc_item_tracker_id']."' class='btn btn-primary'><i class='fas fa-edit'></i></a></td>
			}
		}else{
			$blackpearl.="
				<tr>
					<td colspan='7' class='text-center'> No data found!!</td>
				</tr>
			";
		}
		return $blackpearl;
	}
}

class pirates_supervisor extends tesseract{
	// create supervisor
	public function stc_create_supervisor(
			$sup_cust,
			$sup_fullname,
			$sup_cont,
			$sup_whatsapp,
			$sup_email,
			$sup_address,
			$sup_city,
			$sup_state,
			$sup_pincode,
			$sup_password,
			$stc_category,
			$sup_status
		){
		$blackpearl='';
		$cptjacksparrowsupercheck=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_cust_pro_supervisor` 
			WHERE `stc_cust_pro_supervisor_contact`='".mysqli_real_escape_string($this->stc_dbs, $sup_cont)."'
			OR `stc_cust_pro_supervisor_email`='".mysqli_real_escape_string($this->stc_dbs, $sup_email)."'
		");
		if(mysqli_num_rows($cptjacksparrowsupercheck)==0){
			$cptjacksparrowsuper=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_cust_pro_supervisor`(
					`stc_cust_pro_supervisor_cust_id`, 
					`stc_cust_pro_supervisor_fullname`, 
					`stc_cust_pro_supervisor_contact`, 
					`stc_cust_pro_supervisor_whatsapp`, 
					`stc_cust_pro_supervisor_email`, 
					`stc_cust_pro_supervisor_address`, 
					`stc_cust_pro_supervisor_pincode`, 
					`stc_cust_pro_supervisor_cityid`, 
					`stc_cust_pro_supervisor_state_id`, 
					`stc_cust_pro_supervisor_password`, 
					`stc_cust_pro_supervisor_category`, 
					`stc_cust_pro_supervisor_status`, 
					`stc_cust_pro_supervisor_created_by`
				) VALUES (
					'".mysqli_real_escape_string($this->stc_dbs, $sup_cust)."',
					'".mysqli_real_escape_string($this->stc_dbs, $sup_fullname)."',
					'".mysqli_real_escape_string($this->stc_dbs, $sup_cont)."',
					'".mysqli_real_escape_string($this->stc_dbs, $sup_whatsapp)."',
					'".mysqli_real_escape_string($this->stc_dbs, $sup_email)."',
					'".mysqli_real_escape_string($this->stc_dbs, $sup_address)."',
					'".mysqli_real_escape_string($this->stc_dbs, $sup_pincode)."',
					'".mysqli_real_escape_string($this->stc_dbs, $sup_city)."',
					'".mysqli_real_escape_string($this->stc_dbs, $sup_state)."',
					'".mysqli_real_escape_string($this->stc_dbs, $sup_password)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_category)."',
					'".mysqli_real_escape_string($this->stc_dbs, $sup_status)."',
					'".$_SESSION['stc_agent_id']."'
				)
			");
			if($cptjacksparrowsuper){
				$blackpearl="yes";
			}else{
				$blackpearl="no";
			}
		}else{
			$blackpearl="not";
		}
		return $blackpearl;
	}

	// alot supervisor a project/site
	public function stc_alot_project_supervisor(
			$alotp_proid,
			$alotp_supid
		){
		$blackpearl='';
		$date=date("Y-m-d H:i:s");
		$cptjacksparrowalotpcheck=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_cust_pro_attend_supervise` 
			WHERE `stc_cust_pro_attend_supervise_pro_id`='".mysqli_real_escape_string($this->stc_dbs, $alotp_proid)."'
			AND `stc_cust_pro_attend_supervise_super_id`='".mysqli_real_escape_string($this->stc_dbs, $alotp_supid)."'
		");
		if(mysqli_num_rows($cptjacksparrowalotpcheck)==0){
			$cptjacksparrowalotp=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_cust_pro_attend_supervise`(
					`stc_cust_pro_attend_supervise_pro_id`, 
					`stc_cust_pro_attend_supervise_super_id`, 
					`stc_cust_pro_attend_supervise_date`, 
					`stc_cust_pro_attend_supervise_status`
				) VALUES (
					'".mysqli_real_escape_string($this->stc_dbs, $alotp_proid)."',
					'".mysqli_real_escape_string($this->stc_dbs, $alotp_supid)."',
					'".mysqli_real_escape_string($this->stc_dbs, $date)."',
					'1'
				)
			");
			if($cptjacksparrowalotp){
				$blackpearl='yes';
			}else{
				$blackpearl='no';
			}
		}else{
			$blackpearl='not';
		}
		return $blackpearl;
	}

	// call single user
	public function stc_call_singleuser($userid){
		$blackpearl=array();
		$cptjacksparrowalotpcheck=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_cust_pro_supervisor` 
			WHERE `stc_cust_pro_supervisor_id`='".mysqli_real_escape_string($this->stc_dbs, $userid)."'
		");
		if(mysqli_num_rows($cptjacksparrowalotpcheck)>0){
			$blackpearl=mysqli_fetch_assoc($cptjacksparrowalotpcheck);
			$sitenameqry=mysqli_query($this->stc_dbs, "
        	    SELECT DISTINCT `stc_cust_project_title` FROM `stc_cust_pro_attend_supervise`
        	    INNER JOIN `stc_cust_pro_supervisor` 
        	    ON `stc_cust_pro_attend_supervise_super_id`=`stc_cust_pro_supervisor_id` 
        	    INNER JOIN `stc_cust_project` 
        	    ON `stc_cust_pro_attend_supervise_pro_id`=`stc_cust_project_id`
        	    WHERE `stc_cust_pro_attend_supervise_super_id`='".mysqli_real_escape_string($this->stc_dbs, $userid)."'
        	");
        	$sitename='';
        	$slno=0;
        	foreach($sitenameqry as $sitenamerow){
        		$slno++;
        		if($sitenamerow['stc_cust_project_title']!=""){
        	    	$sitename.=$slno." - ".$sitenamerow['stc_cust_project_title'].'<br>';
        		}
        	}
        	$blackpearl["Sitename"]=$sitename;
		}
		return $blackpearl;
	}

	// update single user record 
	public function stc_update_supervisor($sup_user_id, $sup_cust, $sup_fullname, $sup_cont, $sup_whatsapp, $sup_email, $sup_address, $sup_city, $sup_state, $sup_pincode, $stc_category, $sup_status){
		$blackpearl='';
		$cptjacktupdateuser=mysqli_query($this->stc_dbs, "
			UPDATE
		    	`stc_cust_pro_supervisor`
			SET
			    `stc_cust_pro_supervisor_cust_id` 		= '".mysqli_real_escape_string($this->stc_dbs, $sup_cust)."',
			    `stc_cust_pro_supervisor_fullname` 		= '".mysqli_real_escape_string($this->stc_dbs, $sup_fullname)."',
			    `stc_cust_pro_supervisor_address` 		= '".mysqli_real_escape_string($this->stc_dbs, $sup_address)."',
			    `stc_cust_pro_supervisor_pincode` 		= '".mysqli_real_escape_string($this->stc_dbs, $sup_pincode)."',
			    `stc_cust_pro_supervisor_cityid` 		= '".mysqli_real_escape_string($this->stc_dbs, $sup_city)."',
			    `stc_cust_pro_supervisor_state_id` 		= '".mysqli_real_escape_string($this->stc_dbs, $sup_state)."',
			    `stc_cust_pro_supervisor_category` 		= '".mysqli_real_escape_string($this->stc_dbs, $stc_category)."',
			    `stc_cust_pro_supervisor_status` 		= '".mysqli_real_escape_string($this->stc_dbs, $sup_status)."'
			WHERE
			    `stc_cust_pro_supervisor_id` = '".mysqli_real_escape_string($this->stc_dbs, $sup_user_id)."'
		");
		if($cptjacktupdateuser){
			$blackpearl="yes";
		}else{
			$blackpearl="no";
		}
		return $blackpearl;
	}

	// call supervisor order items
	public function stc_ag_order_items_call($odid){
		$sl=1;
		$subcat='';
		$countqty='';
		$pdid=0;
		$transformers='
			<table class="mb-0 table table-hover">
				<thead>
				    <th>Sl No</th>
				    <th width="45%">Material Desc</th>
				    <th>Unit</th>
				    <th>Order Qty</th>
				    <th>STC Inventory Qty</th>
				    <th>Remains Qty</th>
				    <th>Status</th>
				    <th>Action</th>
				</thead>
				<tbody>
		';
		$transformersquery=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_product_id`,
				`stc_product_name`,
				`stc_sub_cat_name`,
				`stc_product_unit`,
                `stc_cust_super_requisition_items_product_qty`,
                `stc_cust_super_requisition_items_status`,
                `stc_cust_super_requisition_items_id`,
                `stc_cust_super_requisition_id`,
                `stc_cust_super_requisition_items_sup_req_id`
			FROM `stc_cust_super_requisition_items`
			INNER JOIN `stc_product`
			ON`stc_cust_super_requisition_items_product_id`=`stc_product_id`
			INNER JOIN `stc_sub_category`
			ON `stc_product_sub_cat_id`=`stc_sub_cat_id`
			INNER JOIN `stc_cust_super_requisition`
			ON `stc_cust_super_requisition_id`=`stc_cust_super_requisition_items_sup_req_id`
			WHERE `stc_cust_super_requisition_items_sup_req_id`='".$odid."'
		");
		if(mysqli_num_rows($transformersquery)>0){
			foreach($transformersquery as $firstrow){
				$pdid=0;
				if($firstrow['stc_sub_cat_name']=="OTHERS"){
					$subcat='';
				}else{
					$subcat=$firstrow['stc_sub_cat_name'];
				}
				$statusitems='';
				$changedstatus='';
				if($firstrow['stc_cust_super_requisition_items_status']==1){
					$statusitems="Allow";
					$changedstatus='<input type="hidden" id="setforwardactionvalue'.$firstrow['stc_cust_super_requisition_items_id'].'" value="0">';
				}else{
					$statusitems="Not Allow";
					$changedstatus='<input type="hidden" id="setforwardactionvalue'.$firstrow['stc_cust_super_requisition_items_id'].'" value="1">';
				}

				$callinventqty=mysqli_query($this->stc_dbs, "
					SELECT `stc_item_inventory_pd_qty` 
					FROM `stc_item_inventory` 
					WHERE `stc_item_inventory_pd_id`='".$firstrow['stc_product_id']."'
				");
				$inventqty=0;
				foreach($callinventqty as $inventrow){
					$inventqty=$inventrow['stc_item_inventory_pd_qty'];
				}

				$recqtyqry=mysqli_query($this->stc_dbs, "
					SELECT 
						`stc_cust_super_requisition_rec_items_product_qty` 
					FROM `stc_cust_super_requisition_rec_items` 
					WHERE 
						`stc_cust_super_requisition_rec_items_sup_req_id`='".$firstrow['stc_cust_super_requisition_items_sup_req_id']."'
					AND 
						`stc_cust_super_requisition_rec_items_product_id`='".$firstrow['stc_product_id']."'
				");
				$recqty=0;
				foreach($recqtyqry as $recrow){
					$recqty+=$recrow['stc_cust_super_requisition_rec_items_product_qty'];
				}
				$reminder=number_format($firstrow['stc_cust_super_requisition_items_product_qty'], 2) - number_format($recqty, 2);
				$transformers.='
					<tr>
						<td>'.$sl.'</td>
						<td>'.$subcat.' '.$firstrow['stc_product_name'].'</td>
						<td>'.$firstrow['stc_product_unit'].'</td>
						<td>'.number_format($firstrow['stc_cust_super_requisition_items_product_qty'], 2).'</td>
						<td>'.number_format($inventqty, 2).'</td>
						<td>'.$reminder.'</td>
						<td>'.$statusitems.'</td>
						<td>'.$changedstatus.'
							<a class="btn btn-alert form-control setforwardaction" id="'.$firstrow['stc_cust_super_requisition_items_id'].'" style="font-size: 20px;"><i class="fas fa-thumbs-up"></i></a>
						</td>
					</tr>
				';
				$sl++;
				$pdid=$firstrow['stc_cust_super_requisition_id'];
			}
		}else{
			$transformers.='
					<tr>
						<td colspan="7"><h4>No Products Found!!!</h6></td>
					</tr>
			';
		}
		$transformers.='
					<tr>
						<td colspan="8"><a class="btn btn-success form-control placeorder" id="'.$pdid.'">Place this Order</a></td>
					</tr>
				</tbody>
			</table>
		';
		return $transformers;
	}

	// call supervisor reuisition items 
	public function stc_ag_req_items_call($odid){
		$sl=1;
		$subcat='';
		$countqty='';
		$pdid=0;
		$transformers='
			<table class="mb-0 table table-hover table-responsive">
				<thead>
				    <th>Sl No</th>
				    <th width="25%">Item Desc</th>
				    <th>Unit</th>
				    <th>Requis Qty</th>
				    <th>Requis Approve Qty</th>
				    <th>Requis Approved Qty</th>
				    <th>Requis Remains Qty</th>
				    <th>Status</th>
				    <th>Action</th>
				</thead>
				<tbody>
		';
		$transformersquery=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_cust_super_requisition_list_items_req_id`,
				`stc_cust_super_requisition_list_id`,
				`stc_cust_super_requisition_list_items_title`,
			    `stc_cust_super_requisition_list_items_unit`,
			    `stc_cust_super_requisition_list_items_reqqty`,
			    `stc_cust_super_requisition_list_items_approved_qty`,
                `stc_cust_super_requisition_items_finalqty`,
			    `stc_cust_super_requisition_list_items_status`
			FROM `stc_cust_super_requisition_list_items` 
			WHERE `stc_cust_super_requisition_list_items_req_id`='".$odid."'
		");
		if(mysqli_num_rows($transformersquery)>0){
			foreach($transformersquery as $firstrow){
				$pdid=0;
				$statusitems='';
				$changedstatus='';

				$reminder=$firstrow['stc_cust_super_requisition_list_items_reqqty'] - $firstrow['stc_cust_super_requisition_list_items_approved_qty'];
				if($reminder!=0){
					$transformers.='
						<tr>
							<td>'.$sl.'</td>
							<td>'.$subcat.' '.$firstrow['stc_cust_super_requisition_list_items_title'].'</td>
							<td>'.$firstrow['stc_cust_super_requisition_list_items_unit'].'</td>
							<td>'.number_format($firstrow['stc_cust_super_requisition_list_items_reqqty'], 2).'</td>
							<td>
								<input type="number" class="form-control stc-sup-appr-qty'.$firstrow['stc_cust_super_requisition_list_id'].'"
								value="'.$reminder.'">
							</td>
							<td>'.number_format($firstrow['stc_cust_super_requisition_list_items_approved_qty'], 2).'</td>
							<td>'.number_format($reminder, 2).'</td>
							<td>
								<select class="form-control stc-sup-items-status'.$firstrow['stc_cust_super_requisition_list_id'].'">
									<option value="1" selected>Allow</option>
									<option value="0">Not Allow</option>
								</select>
							</td>
							<td>'.$changedstatus.'
								<a 
									href="#"
									class="btn btn-alert form-control add_to_accept_cart" 
									atc-ic="'.$firstrow['stc_cust_super_requisition_list_id'].'"
									id="add_to_accept_cart'.$firstrow['stc_cust_super_requisition_list_id'].'" 
									style="font-size: 20px;"><i class="fas fa-thumbs-up"
								></i>
								</a>
								<a 
									href="#"
									class="btn btn-alert form-control rem_from_accept_cart" 
									operat-ic="'.$firstrow['stc_cust_super_requisition_list_id'].'"
									id="rem_from_accept_cart'.$firstrow['stc_cust_super_requisition_list_id'].'" 
									style="font-size: 20px;display:none;"
								><i class="fas fa-trash" ></i>
								</a>								
								<a 
									href="#"
									class="btn btn-alert form-control edit-req-item" 
									id="'.$firstrow['stc_cust_super_requisition_list_id'].'" 
								><i class="fas fa-edit" ></i>
								</a>
							</td>
						</tr>
					';
				}else{
					$transformers.='
						<tr>
							<td>'.$sl.'</td>
							<td>'.$subcat.' '.$firstrow['stc_cust_super_requisition_list_items_title'].'</td>
							<td>'.$firstrow['stc_cust_super_requisition_list_items_unit'].'</td>
							<td>'.number_format($firstrow['stc_cust_super_requisition_list_items_reqqty'], 2).'</td>
							<td>#</td>
							<td>'.number_format($firstrow['stc_cust_super_requisition_list_items_approved_qty'], 2).'</td>
							<td>'.$reminder.'</td>
							<td>
								<select class="form-control stc-sup-items-status'.$firstrow['stc_cust_super_requisition_list_id'].'">
									<option value="1" selected>Allow</option>
									<option value="0">Not Allow</option>
								</select>
							</td>
							<td>#</td>
						</tr>
					';
				}
				$sl++;
				$pdid=$firstrow['stc_cust_super_requisition_list_items_req_id'];
			}
		}else{
			$transformers.='
					<tr>
						<td colspan="7"><h4>No Products Found!!!</h6></td>
					</tr>
			';
		}
		$transformers.='
					<tr>
						<td colspan="8"><a class="btn btn-success form-control placerequisition" id="'.$pdid.'">Place this Requisition</a></td>
					</tr>
				</tbody>
			</table>
		';
		return $transformers;
	}

	// set supervisor order approval per line item accept or not accept
	public function stc_sup_order_validate_items($odid, $validate_value){
		$blackpearl="";
		$cptjacksparrowvalidateqry=mysqli_query($this->stc_dbs, "
			UPDATE `stc_cust_super_requisition_items` 
			SET `stc_cust_super_requisition_items_status`='".$validate_value."'
			WHERE `stc_cust_super_requisition_items_id`='".$odid."'
		");
		if($cptjacksparrowvalidateqry){
			$blackpearl="Status changed!!!";
		}else{
			$blackpearl="Hmm!!!Something went wrong... Please try again later.";
		}
		return $blackpearl;
	}

	// place the  requisition
	public function stc_place_requisition($odid){
		$blackpearl="";
		$requi_id='';
		$pro_id='';
		$pro_title='';
		$pro_address='';
		$super_id='';
		$cust_id='';
		$date=date("Y-m-d H:i:s");
		$cptjackcalldetaqr=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_cust_super_requisition_id`,
				`stc_cust_project_id`, 
			    `stc_cust_project_title`, 
			    `stc_cust_project_address`,
			    `stc_cust_project_cust_id`,
			    `stc_cust_super_requisition_super_id`
			FROM `stc_cust_super_requisition` 
			INNER JOIN `stc_cust_project` 
			ON `stc_cust_project_id`=`stc_cust_super_requisition_project_id`
			WHERE `stc_cust_super_requisition_id`='".$odid."'
		");

		foreach($cptjackcalldetaqr as $cdrow){
			$requi_id=$cdrow['stc_cust_super_requisition_id'];
			$pro_id=$cdrow['stc_cust_project_id'];
			$pro_title=$cdrow['stc_cust_project_title'];
			$pro_address=$cdrow['stc_cust_project_address'];
			$super_id=$cdrow['stc_cust_super_requisition_super_id'];
			$cust_id=$cdrow['stc_cust_project_cust_id'];
		}

		$cptjacksparrowvalidateqry=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_agent_order`(
				`stc_agent_order_date`, 
				`stc_agent_order_agent_id`, 
				`stc_agent_order_cust_id`, 
				`stc_agent_order_cust_site_name`, 
				`stc_agent_order_cust_site_address`, 
				`stc_agent_order_status`, 
				`stc_agent_order_accepted_by`, 
				`stc_agent_order_proj_id`, 
				`stc_agent_order_super_id`, 
				`stc_agent_order_requis_id`
			) VALUES (
				'".$date."',
				'".$_SESSION['stc_agent_id']."',
				'".$cust_id."',
				'".$pro_title."',
				'".$pro_address."',
				'PROCESS',
				'0',
				'".$pro_id."',
				'".$super_id."',
				'".$requi_id."'
			)
		");

		if($cptjacksparrowvalidateqry){
			$blackpearl="yes";
		}else{
			$blackpearl="no";
		}
		// $blackpearl=$pro_id.'>'.$pro_title.'>'.$pro_address.'>'.$super_id;
		return $blackpearl;
	}

	// place the requisition items
	public function stc_place_requisition_items($odid){
		$blackpearl='';
		$optimusprimegetid=mysqli_query($this->stc_dbs, "SELECT MAX(`stc_agent_order_id`) as orderid FROM `stc_agent_order` ORDER BY `stc_agent_order_id` DESC");
		foreach($optimusprimegetid as $opgirow){
			$getorderid=$opgirow['orderid'];
		}

		$itemsqry=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_cust_super_requisition_items_sup_req_id`, 
				`stc_cust_super_requisition_items_product_id`, 
				`stc_cust_super_requisition_items_product_qty`, 
				`stc_cust_super_requisition_items_status` 
			FROM `stc_cust_super_requisition_items` 
			WHERE `stc_cust_super_requisition_items_sup_req_id`='".$odid."'
		");

		foreach($itemsqry as $getpricerow){
			if($getpricerow['stc_cust_super_requisition_items_status']==1){
				$purchaseqry=mysqli_query($this->stc_dbs, "
					SELECT DISTINCT `stc_purchase_product_items_rate` 
					FROM `stc_purchase_product_items` 
					WHERE `stc_purchase_product_items_product_id`='".$getpricerow['stc_cust_super_requisition_items_product_id']."'
					ORDER BY `stc_purchase_product_items_id` DESC LIMIT 1
				");
				$itemprice=0;
				foreach($purchaseqry as $purchaseqryrow){
					$itemprice=$purchaseqryrow['stc_purchase_product_items_rate'];
				}
				$itemprice=$itemprice + ($itemprice * 5/100);
				$goonordert=mysqli_query($this->stc_dbs, "
					INSERT INTO `stc_agent_order_items`(
						`stc_agent_order_items_order_id`, 
						`stc_agent_order_items_pd_id`, 
						`stc_agent_order_items_pd_qty`, 
						`stc_agent_order_items_pd_price`
					) VALUES (
						'".$getorderid."',
						'".$getpricerow['stc_cust_super_requisition_items_product_id']."',
						'".$getpricerow['stc_cust_super_requisition_items_product_qty']."',
						'".$itemprice."'
					)
				");
			}
		}
		$blackpearl="Requisition placed!!!";
		return $blackpearl;
	}

	// check item existance on supervisor order
	public function stc_check_item_existance($odid){
		$blackpearl="";
		$cptjacksparrowexsqry=mysqli_query($this->stc_dbs, "
			SELECT `stc_cust_super_requisition_items_id` 
			FROM `stc_cust_super_requisition_items` 
			WHERE 
				`stc_cust_super_requisition_items_sup_req_id`='".$odid."' 
			AND 
				`stc_cust_super_requisition_items_status`=1
		");
		if(mysqli_num_rows($cptjacksparrowexsqry)>0){
			$blackpearl="yes";
		}else{
			$blackpearl="no";
		}
		return $blackpearl;
	}
	// add to purchase
	public function stc_add_to_purchase($item_id, $itemqty, $itemstatus){
		$blackpearl='';
		$itemstatus = $itemstatus == 1 ? 2 : 0;
		$setapprqry=mysqli_query($this->stc_dbs, "
			UPDATE `stc_cust_super_requisition_list_items` 
			SET 
				`stc_cust_super_requisition_list_items_approved_qty`='".$itemqty."',
				`stc_cust_super_requisition_list_items_status`='".$itemstatus."'
			WHERE `stc_cust_super_requisition_list_id`='".$item_id."'
		");
		$getapprqry=mysqli_query($this->stc_dbs, "
			select `stc_cust_super_requisition_list_items_req_id` 
			from stc_cust_super_requisition_list_items 
			where stc_cust_super_requisition_list_id='".$item_id."'
		");
		$result=mysqli_fetch_assoc($getapprqry);
		$req_id=$result['stc_cust_super_requisition_list_items_req_id'];
		if($setapprqry){
			// check status from item
			$checkquery=mysqli_query($this->stc_dbs, "
				SELECT DISTINCT `stc_cust_super_requisition_list_items_status` 
				FROM `stc_cust_super_requisition_list_items` 
				WHERE `stc_cust_super_requisition_list_items_req_id`='".$req_id."'
			");
			if(mysqli_num_rows($checkquery)==1){
				$result=mysqli_fetch_assoc($checkquery);
				$resultstatus=$result['stc_cust_super_requisition_list_items_status'];
				if($resultstatus==2){
					$setapprqry=mysqli_query($this->stc_dbs, "
						UPDATE `stc_cust_super_requisition_list` SET `stc_cust_super_requisition_list_status`='2' WHERE `stc_cust_super_requisition_list_id`='".$req_id."'
					");
					$setapprqry=mysqli_query($this->stc_dbs, "
						UPDATE `stc_cust_super_requisition_list_items` SET `stc_cust_super_requisition_list_items_status`='1' WHERE `stc_cust_super_requisition_list_id`='".$item_id."'
					");
				}
			}
			$blackpearl="success";
		}else{
			$blackpearl="not";
		}
		return $blackpearl;
	}

	// approved qty
	public function stc_set_req_appr($odid){
		$blackpearl='';
		if(!empty($_SESSION['stc_supervisor_req_sess'])){
			foreach($_SESSION['stc_supervisor_req_sess'] as $reqrow){
				$stcgetreqapprqtyqry=mysqli_query($this->stc_dbs, "
					SELECT `stc_cust_super_requisition_list_items_approved_qty` FROM `stc_cust_super_requisition_list_items` 
					WHERE `stc_cust_super_requisition_list_items_req_id`='".$odid."'
					AND `stc_cust_super_requisition_list_id`='".$reqrow['product_id']."'
				");
				$apprqty=0;
				foreach($stcgetreqapprqtyqry as $apprqtyrow){
					$apprqty+=$apprqtyrow['stc_cust_super_requisition_list_items_approved_qty'];
				}
				if($apprqty==0){
					$setapprqry=mysqli_query($this->stc_dbs, "
						UPDATE `stc_cust_super_requisition_list_items` 
						SET 
							`stc_cust_super_requisition_list_items_approved_qty`='".$reqrow['product_quantity']."',
							`stc_cust_super_requisition_list_items_status`='".$reqrow['itemstatus']."'
						WHERE `stc_cust_super_requisition_list_items_req_id`='".$odid."'
						AND `stc_cust_super_requisition_list_id`='".$reqrow['product_id']."'
					");
					if($setapprqry){
						$againsetapprqry=mysqli_query($this->stc_dbs, "
							UPDATE `stc_cust_super_requisition_list` 
							SET `stc_cust_super_requisition_list_status`='2'
							WHERE `stc_cust_super_requisition_list_id`='".$odid."'
						");
						if($againsetapprqry){
							$blackpearl="Thankyou!!! Your Requisition is Sent.";
							unset($_SESSION["stc_supervisor_req_sess"]);
						}else{
							$blackpearl="Your Requisition is Not Sent. Please Contact STC team:(";
						}
					}
				}else{
					$apprval=$apprqty + $reqrow['product_quantity'];
					$setapprqry=mysqli_query($this->stc_dbs, "
						UPDATE `stc_cust_super_requisition_list_items` 
						SET 
							`stc_cust_super_requisition_list_items_approved_qty`='".$apprval."',
							`stc_cust_super_requisition_list_items_status`='".$reqrow['itemstatus']."'
						WHERE `stc_cust_super_requisition_list_items_req_id`='".$odid."'
						AND `stc_cust_super_requisition_list_id`='".$reqrow['product_id']."'
					");
					if($setapprqry){
						$againsetapprqry=mysqli_query($this->stc_dbs, "
							UPDATE `stc_cust_super_requisition_list` 
							SET `stc_cust_super_requisition_list_status`='2'
							WHERE `stc_cust_super_requisition_list_id`='".$odid."'
						");
						if($againsetapprqry){
							$blackpearl="Thankyou!!! Your Requisition is Sent.";
							unset($_SESSION["stc_supervisor_req_sess"]);
						}else{
							$blackpearl="Your Requisition is Not Sent. Please Contact STC team:(";
						}
					}else{
						$blackpearl="Hmm!!! Something Went Wrong. Please Try Again!!!";
					}
				}
			}
		}else{
			$blackpearl="Dude!!! Atleast add item first!!!";
		}
		return $blackpearl;
	}

	// get supervisors order & requisitions 
	public function stc_get_supervisors_pending_records($stc_begdate, $stc_enddate, $stc_superid, $stc_projeid){
		$optimusprime='';
		$slno=0;
		$getrequisitionsqry=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT
				`stc_cust_super_requisition_list_items`.`stc_cust_super_requisition_list_id` as reqlistid,
				DATE(`stc_cust_super_requisition_list_date`) as stc_req_date,
				`stc_cust_super_requisition_list_items_req_id`,
				`stc_cust_super_requisition_list_items_title`,
			    `stc_cust_super_requisition_list_items_unit`,
			    `stc_cust_super_requisition_list_items_reqqty`,
			    `stc_cust_super_requisition_list_items_approved_qty`,
			    `stc_cust_super_requisition_items_finalqty`,
			    `stc_cust_super_requisition_list_items_status`,
				`stc_cust_super_requisition_items_priority`
			FROM `stc_cust_super_requisition_list_items`
			INNER JOIN `stc_cust_super_requisition_list` 
			ON `stc_cust_super_requisition_list_items_req_id`=`stc_cust_super_requisition_list`.`stc_cust_super_requisition_list_id`
			WHERE 
				`stc_cust_super_requisition_list_super_id`='".mysqli_real_escape_string($this->stc_dbs, $stc_superid)."'
			AND 
				`stc_cust_super_requisition_list_project_id`='".mysqli_real_escape_string($this->stc_dbs, $stc_projeid)."'
			AND (
				DATE(`stc_cust_super_requisition_list_date`) 
			BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $stc_begdate)."'
			AND '".mysqli_real_escape_string($this->stc_dbs, $stc_enddate)."' 
			) ORDER BY DATE(`stc_cust_super_requisition_list_date`) DESC
		");
		if(mysqli_num_rows($getrequisitionsqry)>0){
			foreach($getrequisitionsqry as$requisitionrow){
				$slno++;
				$rqitemstts='';
				$stcdispatchedqty=0;
				$stcrecievedqty=0;
				$stcpendingqty=0;
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
				$bgcolor=$requisitionrow['stc_cust_super_requisition_items_priority']==2 ? "style='background:#ff9e9e;'" : "Normal";
				$stock=$stcrecievedqty - $stcconsumedqty;
				$optimusprime.='
						<tr>
							<td>'.$slno.'</td>
							<td class="text-center">'.date('d-m-Y', strtotime($requisitionrow['stc_req_date'])).'</td>
							<td>'.$requisitionrow['stc_cust_super_requisition_list_items_req_id'].'</td>
							<td>'.$requisitionrow['stc_cust_super_requisition_list_items_title'].'</td>
							<td class="text-center">'.$requisitionrow['stc_cust_super_requisition_list_items_unit'].'</td>
							<td class="text-right">'.number_format($requisitionrow['stc_cust_super_requisition_list_items_reqqty'], 2).'</td>
							<td class="text-right">'.number_format($requisitionrow['stc_cust_super_requisition_list_items_approved_qty'], 2).'</td>
							<td class="text-right">'.number_format($requisitionrow['stc_cust_super_requisition_items_finalqty'], 2).'</td>
							<td class="text-right">'.number_format($stcdispatchedqty, 2).'</td>
							<td class="text-right">'.number_format($stcrecievedqty, 2).'</td>
							<td class="text-right">'.$stcpendingqty.'</td>
							<td class="text-right">'.number_format($stcconsumedqty, 2).'</td>
							<td class="text-right">'.number_format($stock, 2).'</td>
							<td '.$bgcolor.' class="text-center">'.$priority.'</td>
							<td>'.$rqitemstts.'</td>
						</tr>
				';
			}
		}else{
			$optimusprime.='
					<tr>
						<td colspan="10">No requisition found!!!</td>
					</tr>
			';
		}
		return $optimusprime;
	}

	// get supervisors order & requisitions 
	public function stc_get_supervisors_pending_consump_records($stc_begdate, $stc_enddate, $stc_superid, $stc_projeid){
		$optimusprime='';
		$slno=0;
		$optimusprimeqry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_cust_project_title`,
				`stc_cust_super_list_items_consumption_sub_location`,
				`stc_cust_super_list_items_consumption_job_details`,
				`stc_cust_super_requisition_list_items_title`,
				`stc_cust_super_list_items_consumption_items_unit`,
				`stc_cust_super_list_items_consumption_items_qty`
			FROM
				`stc_cust_super_list_items_consumption`
			INNER JOIN 
				`stc_cust_super_list_items_consumption_items` 
			ON 
				`stc_cust_super_list_items_consumption_items_consumpid`=`stc_cust_super_list_items_consumption_id` 
			INNER JOIN 
				`stc_cust_super_requisition_list_items` 
			ON 
				`stc_cust_super_requisition_list_id`=`stc_cust_super_list_items_consumption_items_name`
			INNER JOIN 
				`stc_cust_project` 
			ON 
				`stc_cust_project_id`=`stc_cust_super_list_items_consumption_site_id`
			WHERE (
				DATE(`stc_cust_super_list_items_consumption_date`) BETWEEN '".mysqli_real_escape_string($this->stc_dbs, $stc_begdate)."' 
				AND '".mysqli_real_escape_string($this->stc_dbs, $stc_enddate)."'
			) AND 
				`stc_cust_super_list_items_consumption_super_id`='".mysqli_real_escape_string($this->stc_dbs, $stc_superid)."'
			AND 
				`stc_cust_super_list_items_consumption_site_id`='".mysqli_real_escape_string($this->stc_dbs, $stc_projeid)."'
		");
		if(mysqli_num_rows($optimusprimeqry)>0){
			foreach($optimusprimeqry as $optimusprimerow){
				$slno++;
				$optimusprime.='
					<tr>
						<td>'.$slno.'</td>
						<td>'.$optimusprimerow['stc_cust_project_title'].'</td>
						<td>'.$optimusprimerow['stc_cust_super_list_items_consumption_sub_location'].'</td>
						<td>'.$optimusprimerow['stc_cust_super_list_items_consumption_job_details'].'</td>
						<td>'.$optimusprimerow['stc_cust_super_requisition_list_items_title'].'</td>
						<td align="right">'.number_format($optimusprimerow['stc_cust_super_list_items_consumption_items_qty'], 2).'</td>
						<td>'.$optimusprimerow['stc_cust_super_list_items_consumption_items_unit'].'</td>
					</tr>
				';
			}
		}else{
			$optimusprime.='
					<tr>
						<td>No Record Found!!!</td>
					</tr>
			';
		}
		return $optimusprime;
	}

	public function stc_get_attendance($dept, $month, $year){
		$optimusprime='';
		$optimusprime_query=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT ee.`emp_id`, ee.`emp_name`, dep.`stc_status_down_list_department_dept`, dep.`stc_status_down_list_department_location`  
			FROM `stc_epermit_enrollment` ee 
			LEFT JOIN `stc_status_down_list_department` dep ON ee.`dep_id`=dep.`stc_status_down_list_department_id`
			WHERE MONTH(ee.`created_date`)='" . mysqli_real_escape_string($this->stc_dbs, $month) . "' 
			AND YEAR(ee.`created_date`)='" . mysqli_real_escape_string($this->stc_dbs, $year) . "'
			AND ee.`dep_id`='" . mysqli_real_escape_string($this->stc_dbs, $dept) . "'
			AND ee.`emp_id`<>0
			ORDER BY ee.`emp_name` ASC
		");
		if(mysqli_num_rows($optimusprime_query)>0){
			$slno=0;
			foreach($optimusprime_query as $row){
				$attendance = '';
				$totalp=0;
				$totala=0;
			
				// Get attendance dates
				$query = mysqli_query($this->stc_dbs, "
					SELECT DISTINCT DATE(ee.created_date) as attend_date 
					FROM stc_epermit_enrollment ee 
					WHERE emp_id='" . mysqli_real_escape_string($this->stc_dbs, $row['emp_id']) . "' 
					AND YEAR(ee.created_date)='" . mysqli_real_escape_string($this->stc_dbs, $year) . "' 
					AND MONTH(ee.created_date)='" . mysqli_real_escape_string($this->stc_dbs, $month) . "'
				");
				
				$attend_dates = array();
				if (mysqli_num_rows($query) > 0) {
					while ($row2 = mysqli_fetch_assoc($query)) {
						$attend_dates[] = date('d', strtotime($row2['attend_date']));
					}
				}
			
				$lastDay = date('t', mktime(0, 0, 0, $month, 1, $year));
				for ($i = 1; $i <= $lastDay; $i++) {
					$dayStr = str_pad($i, 2, '0', STR_PAD_LEFT);
			
					if (in_array($dayStr, $attend_dates)) {
						// Get stc_safetytbm_id for the given attend_date
						$tbmQuery = mysqli_query($this->stc_dbs, "
							SELECT stc_safetytbm_id 
							FROM stc_safetytbm 
							WHERE DATE(stc_safetytbm_date) = '" . mysqli_real_escape_string($this->stc_dbs, "$year-$month-$dayStr") . "'
							AND `stc_safetytbm_place`='".$row['stc_status_down_list_department_dept']."'
						");
			
						$imgTag = '';
						if (mysqli_num_rows($tbmQuery) > 0) {
							$tbmRow = mysqli_fetch_assoc($tbmQuery);
							$tbmId = $tbmRow['stc_safetytbm_id'];
			
							// Get image location using stc_safetytbm_id
							$imgQuery = mysqli_query($this->stc_dbs, "
								SELECT stc_safetytbm_img_location FROM stc_safetytbm_img WHERE stc_safetytbm_img_tbmid = '" . mysqli_real_escape_string($this->stc_dbs, $tbmId) . "'
							");
			
							if (mysqli_num_rows($imgQuery) > 0) {
								$imgRow = mysqli_fetch_assoc($imgQuery);
								$imgLocation = $imgRow['stc_safetytbm_img_location'];
								
								// Create an anchor tag with the image
								$imgTag = $imgLocation!='' ? '<a href="../stc_sub_agent47/safety_img/' . $imgLocation . '" target="_blank" title="click to view images"><span style="color:green;font-size: 35px;">P</span></a>' : '<span style="color:green">P</span>';
							}else{
								$imgTag='<span style="color:green">P</span>';
							}
						}else{
							$imgTag='<span style="color:green">P</span>';
						}
			
						$attendance .= '<td>' . $imgTag . '</td>';
						$totalp++;
					} else {
						$attendance .= '<td><span style="color:red">A</span></td>';
						$totala++;
					}
				}
				$slno++;
				$optimusprime .= '<tr><td>' . $slno . '</td><td>' . $row['stc_status_down_list_department_location'] . '</td><td>' . $row['stc_status_down_list_department_dept'] . '</td><td>' . $row['emp_name'] . '</td>' . $attendance . '<td class="text-right"><span style="color:green">' . $totalp . '</span></td><td class="text-right"><span style="color:red">' . $totala . '</span></td></tr>';
				
			}
			
		}else{
			$optimusprime = '<tr><td colspan="10">No data found.</td></tr>';
		}
		return $optimusprime;
	}
	
	public function stc_get_ratings($dept, $month, $year){
		$optimusprime='';
		$optimusprime_query = mysqli_query($this->stc_dbs, "
			SELECT DISTINCT 
				sdl.`stc_status_down_list_department_location`, 
				sdl.`stc_status_down_list_department_dept`, 
				supervise.`stc_cust_pro_attend_supervise_super_id`, 
				supervisor.`stc_cust_pro_supervisor_fullname`
			FROM `stc_cust_employee_rating` rating
			LEFT JOIN `stc_cust_pro_attend_supervise` supervise ON rating.`created_by` = supervise.`stc_cust_pro_attend_supervise_super_id`
			LEFT JOIN `stc_status_down_list_department` sdl ON supervise.`stc_cust_pro_attend_supervise_pro_id` = sdl.`stc_status_down_list_department_loc_id`
			LEFT JOIN `stc_cust_pro_supervisor` supervisor ON supervise.`stc_cust_pro_attend_supervise_super_id` = supervisor.`stc_cust_pro_supervisor_id`
			WHERE sdl.`stc_status_down_list_department_id` = '" . mysqli_real_escape_string($this->stc_dbs, $dept) . "'
		");

		if (mysqli_num_rows($optimusprime_query) > 0) {
			$slno = 0;
			$optimusprime = ''; // Initialize HTML string

			foreach ($optimusprime_query as $row) {
				$attendance = '';
				$totalp = 0;
				$totala = 0;

				$lastDay = date('t', mktime(0, 0, 0, $month, 1, $year)); // Last day of the selected month

				// Loop through each day of the month
				for ($i = 1; $i <= $lastDay; $i++) {
					$dayStr = str_pad($i, 2, '0', STR_PAD_LEFT);
					$dateString = "$year-$month-$dayStr"; // Format date as YYYY-MM-DD

					// Count ratings per day
					$dailyQuery = mysqli_query($this->stc_dbs, "
						SELECT SUM(`point`) AS rating_count
						FROM `stc_cust_employee_rating`
						WHERE `created_by` = '" . mysqli_real_escape_string($this->stc_dbs, $row['stc_cust_pro_attend_supervise_super_id']) . "'
						AND DATE(`created_date`) = '$dateString'
					");

					$dailyResult = mysqli_fetch_assoc($dailyQuery);
					$ratingCount = $dailyResult['rating_count'] ?? 0;

					// Add daily ratings to attendance string
					$attendance .= "<td>$ratingCount</td>";

					// Count total ratings (present) and absences
					$totalp += $ratingCount;
				}
				$totala = 60;

				$slno++;
				// Build the HTML row
				$optimusprime .= "<tr>
					<td>$slno</td>
					<td>{$row['stc_status_down_list_department_location']}</td>
					<td>{$row['stc_status_down_list_department_dept']}</td>
					<td>{$row['stc_cust_pro_supervisor_fullname']}</td>
					$attendance
					<td class='text-right'><span style='color:red'>$totala</span></td>
					<td class='text-right'><span style='color:green'>$totalp</span></td>
				</tr>";
			}			
		}else{
			$optimusprime = '<tr><td colspan="10">No data found.</td></tr>';
		}
		return $optimusprime;
	}

	// call status down list
	public function stc_call_status_down_list($location_id, $search, $status){
		$optimusprime='
			<table class="table table-bordered table-responsive">
				<thead>
					<tr>
						<th class="text-center">DATE</th>
						<th class="text-center">LOCATION</th>
						<th class="text-center">DEPARTMENT</th>
						<th class="text-center">AREA</th>
						<th class="text-center EQUIPMENTTYPE">EQUIPMENT TYPE</th>
						<th class="text-center EQUIPMENTSTATUS">EQUIPMENT STATUS</th>
						<th class="text-center QUANITTY">QUANITTY</th>
						<th class="text-center CAPACITY">CAPACITY</th>
						<th class="text-center REASONATTRIBUTE">REASON ATTRIBUTE</th>
						<th class="text-center TYPEOFJOB">TYPE OF JOB</th>
						<th class="text-center JOBPLANNING">JOB CATEGORY</th>
						<th class="text-center VARIETIESOFJOB">VARIETIES OF JOB</th>
						<th class="text-center PERMITNO">PERMIT NO</th>
						<th class="text-center RESPONSIBLEPERSONNAMEMOBILENO">RESPONSIBLE PERSON NAME & MOBILE NO</th>
						<th class="text-center DOWNREASON">REASON</th>
						<th class="text-center MATERIALSREQ">MATERIALS REQ</th>
						<th class="text-center MANPOWERREQ">MANPOWER REQ</th>
						<th class="text-center TOOLSREQ">TOOLS REQ</th>
						<th class="text-center WORKCOMPLETIONTARGETDATE">WORK COMPLETION TARGET DATE</th>
						<th class="text-center ACTUALWORKCOMPLETIONDATE">ACTUAL WORK COMPLETION DATE</th>
						<th class="text-center USEDMATERIAL">USED MATERIAL</th>
						<th class="text-center STOCKMATERIAL">STOCK MATERIAL</th>
						<th class="text-center DOWNPERIOD">DOWN PERIOD</th>
						<th class="text-center WORKSTATUS">WORK STATUS</th>
						<th class="text-center PENDINGREASON">PENDING REASON</th>
						<th class="text-center PROGRESSREPORT">PROGRESS REPORT</th>
						<th class="text-center JOBDONEDETAILS">JOB DONE DETAILS</th>
						<th class="text-center REMARKS">REMARKS</th>
						<th class="text-center CREATEDDETAILS">CREATOR NAME & CONTACT NUMBER</th>
						<th class="text-center CREATEDBY">CREATED BY</th>
						<th class="text-center DESIGNATION">DESIGNATION</th>
						<th class="text-center UPDATEDBY">UPDATED BY</th>
						<th class="text-center UPDATEDON">UPDATED ON</th>
						<th class="text-center STATUSUPDATEDBY">STATUS UPDATED BY</th>
						<th class="text-center STATUSUPDATEDON">STATUS UPDATED ON</th>
						<th class="text-center ">ACTION</th>
					</tr>
				</thead>
				<tbody>
		';
		$search_field='';
		if(!empty($search)){
			$search_field=" AND 
				(
					`stc_cust_project_title` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR
					`stc_status_down_list_sub_location` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR
					`stc_status_down_list_area` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR
					`stc_status_down_list_reason` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR
					`stc_status_down_list_area` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR
					a.`stc_cpumpd_equipment_type` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR
					a.`stc_cpumpd_equipment_number` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR
					b.`stc_cpumpd_equipment_type` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."' OR
					b.`stc_cpumpd_equipment_number` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $search)."'
				)
			";
		}
		$showbystatus="AND `stc_agents_id`=".$_SESSION['stc_agent_id'];
		if($_SESSION['stc_agent_role']==3){
			$showbystatus='';
		}
		$optimusprimeqry=mysqli_query($this->stc_dbs, "
			SELECT DISTINCT
			    `stc_status_down_list_id`,
			    `stc_status_down_list_date`,
			    `stc_cust_project_title`,
				`stc_status_down_list_plocation`,
			    `stc_status_down_list_sub_location`,
			    `stc_status_down_list_area`,
			    `stc_status_down_list_equipment_type`,
			    `stc_status_down_list_equipment_number`,
			    `stc_status_down_list_equipment_status`,
				`stc_status_down_list_qty`,
				`stc_status_down_list_capacity`,
				`stc_status_down_list_reasonattribute`,
			    `stc_status_down_list_reason`,
			    `stc_status_down_list_manpower_req`,
			    `stc_status_down_list_material_desc`,
			    `stc_status_down_list_from_date`,
			    `stc_status_down_list_rect_date`,
			    `stc_status_down_list_remarks`,
			    `stc_status_down_list_jobdone_details`,
			    `stc_status_down_list_jobpending_details`,
			    `stc_status_down_list_jobtype`,
			    `stc_status_down_list_varities_id`,
			    `stc_status_down_list_created_by_select`,
			    `stc_status_down_list_permit_no`,
			    `stc_status_down_list_creator_details`,
			    `stc_status_down_list_responsive_person`,
			    `stc_status_down_list_target_date`,
			    `stc_status_down_list_tools_req`,
			    `stc_status_down_list_status`,
			    `stc_status_down_list_created_by`,
			    `stc_cust_pro_supervisor_created_by`,
			    `stc_cust_pro_supervisor_fullname`,
			    `stc_cust_pro_supervisor_contact`,
			    `stc_cust_pro_supervisor_category`,
				`stc_status_down_list_updated_by`,
				`stc_status_down_list_updated_date`,
				`stc_status_down_list_status_updated_by`,
				`stc_status_down_list_status_updated_on`,
				`stc_status_down_list_wipstatus`
			FROM `stc_status_down_list` 
			LEFT JOIN `stc_cust_project` 
			ON `stc_cust_project_id`=`stc_status_down_list_location` 
			LEFT JOIN `stc_customer_pump_details` a
			ON `stc_status_down_list_equipment_number`=a.`stc_cpumpd_id` 
			LEFT JOIN `stc_customer_pump_details` b 
			ON `stc_status_down_list_equipment_type`=b.`stc_cpumpd_id` 
			LEFT JOIN `stc_cust_pro_supervisor` 
			ON `stc_status_down_list_created_by`=`stc_cust_pro_supervisor_id` 
			WHERE `stc_status_down_list_plocation`='".mysqli_real_escape_string($this->stc_dbs, $location_id)."'
			AND `stc_status_down_list_status`='".mysqli_real_escape_string($this->stc_dbs, $status)."' ".$search_field."
			ORDER BY TIMESTAMP(`stc_status_down_list_date`) DESC
		");
		if(mysqli_num_rows($optimusprimeqry)>0){
			foreach($optimusprimeqry as $row){

				$list_date=(date('Y', strtotime($row['stc_status_down_list_date']))>1970) ? date('d-m-Y', strtotime($row['stc_status_down_list_date'])) : 'NA';
				$rec_date=(date('Y', strtotime($row['stc_status_down_list_rect_date']))>1970) ? date('d-m-Y', strtotime($row['stc_status_down_list_rect_date'])) : 'NA';
				$tar_date=(date('Y', strtotime($row['stc_status_down_list_target_date']))>1970) ? date('d-m-Y', strtotime($row['stc_status_down_list_target_date'])) : 'NA';

				$updatejobdetails='';
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
					$updatejobdetails='<a href="javascript:void(0)" class="btn btn-primary stc-cust-std-update" id="'.$row['stc_status_down_list_id'].'" message="'.$row['stc_status_down_list_jobdone_details'].'">Update me</a>';
				}elseif($row['stc_status_down_list_status']==5){
					$status='<b><span style="padding: 5px;margin: 0;width: 100%;color: #000000">WORK-COMPLETE</span></b>';
					$updatejobdetails='<a href="javascript:void(0)" class="btn btn-primary stc-cust-std-update" id="'.$row['stc_status_down_list_id'].'" message="'.$row['stc_status_down_list_jobdone_details'].'">Update me</a>';
				}else{
					$status='<b><span style="padding: 5px;margin: 0;width: 100%;color: #000000">CLOSE</span></b>';
				}
				
				$eqstatus='
						<td class="text-center EQUIPMENTSTATUS" style="font-weight:bold;background: #5cd25c;border-radius: 5px;">'.$row['stc_status_down_list_equipment_status'].'</td>
				';

				$dperiod='0';

				if($row['stc_status_down_list_equipment_status']=="Down"){
					$eqstatus='
							<td class="text-center EQUIPMENTSTATUS" style="font-weight:bold;background: #e91919;border-radius: 5px;">'.$row['stc_status_down_list_equipment_status'].'</td>
					';
				}
					
					$today = !empty($row['stc_status_down_list_rect_date']) ? date("Y/m/d", strtotime($row['stc_status_down_list_rect_date'])) : date("Y/m/d") ; 
					$startTimeStamp = strtotime(date('Y-m-d', strtotime($row['stc_status_down_list_date'])));
		            $endTimeStamp = strtotime($today);

		            $timeDiff = abs($endTimeStamp - $startTimeStamp);

		            $dperiod = $timeDiff/86400;

				$actionsec='';
				if($_SESSION['stc_agent_role']==3){
					if(($row['stc_status_down_list_status']==5)){
						$actionsec='
							<a href="#" class="stc-set-to-close" style="font-size:20px" id="'.$row['stc_status_down_list_id'].'"><i class="fas fa-thumbs-up"></i></a>
						';
					}else{
						$actionsec='#';
					}
				}else{
					if(($row['stc_cust_pro_supervisor_created_by']==$_SESSION['stc_agent_id']) || ($row['stc_cust_pro_supervisor_created_by']==18)){
						if($row['stc_status_down_list_status']==5){
							$actionsec='
								<a href="#" class="stc-set-to-close" style="font-size:20px" id="'.$row['stc_status_down_list_id'].'"><i class="fas fa-thumbs-up"></i></a>
							';
						}else{
							$actionsec='#';
						}
					}else{
						$actionsec='#';
					}
				}

				$eq_type='';
				$eq_number='';
				$sup_det='';
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

				$stc_call_supqry=mysqli_query($this->stc_dbs, "
					SELECT
					    `stc_cust_pro_supervisor_fullname`,
					    `stc_cust_pro_supervisor_contact`
					FROM
					    `stc_cust_pro_supervisor`
					WHERE
					    `stc_cust_pro_supervisor_id`='".$row['stc_status_down_list_created_by']."'
				");
				foreach($stc_call_supqry as $stc_call_suprow){
					$sup_det=$stc_call_suprow['stc_cust_pro_supervisor_fullname'].'<br>'.$stc_call_suprow['stc_cust_pro_supervisor_contact'];
				}

				$job_type='';
				$job_varities='';
				if (!empty($row['stc_status_down_list_varities_id'])) {
					$stc_call_jobtypeqry=mysqli_query($this->stc_dbs, "
						SELECT `stc_status_down_list_job_type_title`, `stc_status_down_list_job_type_sub_title` FROM `stc_status_down_list_job_type` WHERE `stc_status_down_list_job_type_id` IN (".$row['stc_status_down_list_varities_id'].")
					");
					$job_type_array = [];
					$job_varities_array = [];
					foreach($stc_call_jobtypeqry as $stc_call_jobtyperow){
						// Add job_type to the array if it's not already there
						if (!in_array($stc_call_jobtyperow['stc_status_down_list_job_type_title'], $job_type_array)) {
							$job_type_array[] = $stc_call_jobtyperow['stc_status_down_list_job_type_title'];
						}
						// Add job_varities to the array if it's not already there
						if (!in_array($stc_call_jobtyperow['stc_status_down_list_job_type_sub_title'], $job_varities_array)) {
							$job_varities_array[] = $stc_call_jobtyperow['stc_status_down_list_job_type_sub_title'];
						}
					}
					$job_type = implode(', ', $job_type_array);
					$job_varities = implode(', ', $job_varities_array);
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
				$supdator_id=$row['stc_status_down_list_status_updated_by'];
				$supdater_name='';
				$supdateqry=mysqli_query($this->stc_dbs, "
					SELECT `stc_cust_pro_supervisor_fullname` FROM stc_cust_pro_supervisor WHERE `stc_cust_pro_supervisor_id`=$supdator_id
				");
				if(mysqli_num_rows($supdateqry)>0){
					$result=mysqli_fetch_assoc($supdateqry);
					$supdater_name=$result['stc_cust_pro_supervisor_fullname'];
				}
				$statusupdatedate=date('d-m-Y', strtotime($row['stc_status_down_list_status_updated_on']));
				if(date('Y', strtotime($row['stc_status_down_list_status_updated_on']))==1970){
					$statusupdatedate='';
				}
				$updated_date=date('d-m-Y', strtotime($row['stc_status_down_list_updated_date']));
				if(date('Y', strtotime($row['stc_status_down_list_updated_date']))==1970){
					$updated_date='';
				}
				$eq_type=$row['stc_status_down_list_equipment_type'];
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

				$optimusprime.='
					<tr>
						<td>'.date('d-m-Y h:i a', strtotime($row['stc_status_down_list_date'])).'</td>
						<td>'.$row['stc_status_down_list_plocation'].'</td>
						<td>'.$row['stc_status_down_list_sub_location'].'</td>
						<td>'.$row['stc_status_down_list_area'].'</td>
						<td class="EQUIPMENTTYPE">'.$eq_type.'</td>
						'.$eqstatus.'
						<td class="QUANITTY">'.$row['stc_status_down_list_qty'].'</td>
						<td class="CAPACITY">'.$row['stc_status_down_list_capacity'].'</td>
						<td class="REASONATTRIBUTE">'.$row['stc_status_down_list_reasonattribute'].'</td>
						<td class="TYPEOFJOB">'.$row['stc_status_down_list_jobtype'].'</td>
						<td class="JOBPLANNING">'.$job_type.'</td>
						<td class="VARIETIESOFJOB">'.$job_varities.'</td>
						<td class="PERMITNO">'.$row['stc_status_down_list_permit_no'].'</td>
						<td class="RESPONSIBLEPERSONNAMEMOBILENO">'.$row['stc_status_down_list_responsive_person'].'</td>
						<td class="DOWNREASON">'.$row['stc_status_down_list_reason'].'</td>
						<td class="MATERIALSREQ">'.$row['stc_status_down_list_material_desc'].'</td>
						<td class="MANPOWERREQ">'.$row['stc_status_down_list_manpower_req'].'</td>
						<td class="TOOLSREQ">'.$row['stc_status_down_list_tools_req'].'</td>
						<td class="WORKCOMPLETIONTARGETDATE">'.$tar_date.'</td>
						<td class="ACTUALWORKCOMPLETIONDATE">'.$rec_date.'</td>
						<td class="USEDMATERIAL">NA</td>
						<td class="STOCKMATERIAL">NA</td>
						<td class="DOWNPERIOD">'.$dperiod.' Days</td>
						<td class="WORKSTATUS text-center" '.$statusbgcolor.'>'.$status.'</td>
						<td class="PENDINGREASON">'.$row['stc_status_down_list_jobpending_details'].'</td>
						<td class="PROGRESSREPORT">'.$row['stc_status_down_list_wipstatus'].'</td>
						<td class="JOBDONEDETAILS">'.$row['stc_status_down_list_jobdone_details'].'<br>'.$updatejobdetails.'
						<td class="REMARKS">'.$row['stc_status_down_list_remarks'].'</td>
						<td class="CREATEDDETAILS">'.$row['stc_status_down_list_creator_details'].'</td>
						<td class="CREATEDBY">'.$row['stc_cust_pro_supervisor_fullname'].'<br>'.$row['stc_cust_pro_supervisor_contact'].'</td>
						<td class="DESIGNATION text-center">'.$row['stc_cust_pro_supervisor_category'].'</td>
						<td class="UPDATEDBY">'.$updater_name.'</td>
						<td class="UPDATEDON">'.$updated_date.'</td>
						<td class="STATUSUPDATEDBY">'.$supdater_name.'</td>
						<td class="STATUSUPDATEDON">'.$statusupdatedate.'</td>
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

	// change job done details
	public function stc_sdl_jobdescription_update($sld_id, $jobdonedetails){
		$optimusprime='';
		$date=date("Y-m-d H:i:s");
		$optimusprime_qry=mysqli_query($this->stc_dbs, "
			UPDATE 
				`stc_status_down_list` 
			SET 
				`stc_status_down_list_jobdone_details`='".mysqli_real_escape_string($this->stc_dbs, $jobdonedetails)."' 
			WHERE 
				`stc_status_down_list_id`='".mysqli_real_escape_string($this->stc_dbs, $sld_id)."'
		");
		if($optimusprime_qry){
			$optimusprime='Job Description Updated!!!';
		}else{
			$optimusprime='Hmmm!!! Somethig went wrong on updating job descriptions.';
		}
		return $optimusprime;
	}

	// change status
	public function stc_sdl_status_update($status, $sld_id){
		$optimusprime='';
		$optimusprime_qry=mysqli_query($this->stc_dbs, "
			UPDATE 
				`stc_status_down_list` 
			SET 
				`stc_status_down_list_status`='".mysqli_real_escape_string($this->stc_dbs, $status)."' 
			WHERE 
				`stc_status_down_list_id`='".mysqli_real_escape_string($this->stc_dbs, $sld_id)."'
		");
		if($optimusprime_qry){
			$optimusprime='Status Updated!!!';
		}else{
			$optimusprime='Hmmm!!! SOmethig went wrong on changing status.';
		}
		return $optimusprime;
	}

	// req item change
	public function stc_change_req_item_call($req_id){
		$odin='';
		$odin_getitemqry=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_cust_super_requisition_list_items_title`
			FROM `stc_cust_super_requisition_list_items` 
			WHERE `stc_cust_super_requisition_list_id`='".mysqli_real_escape_string($this->stc_dbs, $req_id)."'
		");
		foreach($odin_getitemqry as $odin_getitemrow){
			$odin=$odin_getitemrow['stc_cust_super_requisition_list_items_title'];
		}
		return $odin;
	}

	// update rewq line items
	public function stc_change_req_item_update($req_item_id, $req_item_name, $req_item_priority){
		$odin='';
		$odin_requpdateqry=mysqli_query($this->stc_dbs, "
			UPDATE
			    `stc_cust_super_requisition_list_items`
			SET
			    `stc_cust_super_requisition_list_items_title` = '".mysqli_real_escape_string($this->stc_dbs, $req_item_name)."',
			    `stc_cust_super_requisition_items_priority` = '".mysqli_real_escape_string($this->stc_dbs, $req_item_priority)."'
			WHERE
			    `stc_cust_super_requisition_list_id` = '".mysqli_real_escape_string($this->stc_dbs, $req_item_id)."'
		");

		if($odin_requpdateqry){
			$odin='Item Updated Successfully.';
		}else{
			$odin='Hmmm!!! Something went wrong, Item not updated successfully.';
		}
		return $odin;
	}

	// safety
	public function stc_call_tbm($location, $month, $supervise_name){
		$optimusprime='';
		$month_arr = explode('-', date('m-Y', strtotime($month)));
		$month = $month_arr[0];
		$year = $month_arr[1];
		$location=" `stc_safetytbm_loc`='".mysqli_real_escape_string($this->stc_dbs, $location)."' AND";
		if($location=='NA'){
			$location='';
		}
		$supervise_rec="`stc_cust_pro_supervisor_fullname` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $supervise_name)."' AND";
		if($supervise_name==''){
			$supervise_rec='';
		}
		$query="
			SELECT * FROM `stc_safetytbm` 
			LEFT JOIN `stc_cust_pro_supervisor`
			ON `stc_cust_pro_supervisor_id`=`stc_safetytbm_created_by`
			WHERE ".$supervise_rec.$location." (
				MONTH(`stc_safetytbm_date`) = '".mysqli_real_escape_string($this->stc_dbs, $month)."' AND
				YEAR(`stc_safetytbm_date`) = '".mysqli_real_escape_string($this->stc_dbs, $year)."'
			)
			ORDER BY DATE(`stc_safetytbm_date`) DESC
		";
		$optimusprimequery=mysqli_query($this->stc_dbs, $query);
		if(mysqli_num_rows($optimusprimequery)>0){
			$website=$_SERVER['SERVER_NAME'];
			$website = $website=="localhost" ? '' : 'https://stcassociate.com/stc_agent47/';
			foreach($optimusprimequery as $optimusprimerow){
				$optimusprimeimgqry=mysqli_query($this->stc_dbs, "
					SELECT `stc_safetytbm_img_location` FROM `stc_safetytbm_img` WHERE `stc_safetytbm_img_tbmid`='".$optimusprimerow['stc_safetytbm_id']."'
				");
				$img_path='';
				foreach($optimusprimeimgqry as $optimusprimeimgrow){
					$img_path=$optimusprimeimgrow['stc_safetytbm_img_location'];
				}

				$safety_image='
						<img src="https://stcassociate.com/stc_sub_agent47/safety_img/'.$img_path.'" style="width: 190px;position: relative;left: 15%;padding: 0;margin: 0;">
						<a href="javascript:void(0)" class="btn btn-info stc-safety-tbm-image-show-btn">View</a>
				';
				if($img_path==""){
					$safety_image="
						<form action='#' id='safety-image-upload-form' >
							<input type='file' name='stc-safety-image-path'>
							<input type='hidden' name='stc-safety-tbm-id' value='".$optimusprimerow['stc_safetytbm_id']."'>
							<input type='submit' value='Upload' class='btn btn-success'>
						</form>";
				}

				$action_show='';
				if(!empty($_SESSION["stc_agent_id"])){
					$action_show='
						<a href="#" class="form-control btn btn-secondary stc-safetytbm-edit" id="'.$optimusprimerow['stc_safetytbm_id'].'">View</a>
						<a target="_blank" href="'.$website.'safety-tbm-print-preview.php?tbm_no='.$optimusprimerow['stc_safetytbm_id'].'" class="form-control btn btn-danger">Print</a>
					';
					// <img src="https://stcassociate.com/stc_sub_agent47/safety_img/'.$img_path.'" style="width: 150px;position: relative;left: 15%;padding: 0;margin: 0;">
					$safety_image='
							<a href="javascript:void(0)" class="btn btn-info stc-safety-tbm-image-show-btn" data-src="https://stcassociate.com/stc_sub_agent47/safety_img/'.$img_path.'">View</a>
					';
				}

				$optimusprime.='
					<tr>
						<td>'.date('d-m-Y', strtotime($optimusprimerow['stc_safetytbm_date'])).'</td>
						<td>'.date('h:i A', strtotime($optimusprimerow['stc_safetytbm_time'])).'</td>
						<td>'.$optimusprimerow['stc_safetytbm_place'].'</td>
						<td>'.$safety_image.'</td>
						<td>'.$optimusprimerow['stc_cust_pro_supervisor_fullname'].'</td>
						<td>'.$action_show.'
						</td>
					</tr>
				';
			}
		}else{
			$optimusprime.='
				<tr>
					<td colspan="5">No data found</td>
				</tr>
			';
		}
		return $optimusprime;
	}

	// call ppec
	public function stc_call_ppec($month, $supervise_name){
		$optimusprime='';
		$month_arr = explode('-', date('m-Y', strtotime($month)));
		$month = $month_arr[0];
		$year = $month_arr[1];
		$supervise_rec="`stc_cust_pro_supervisor_fullname` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $supervise_name)."'
			AND";
		if($supervise_name==''){
			$supervise_rec='';
		}
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_safetyppec` 
			LEFT JOIN `stc_cust_pro_supervisor`
			ON `stc_cust_pro_supervisor_id`=`stc_safetyppec_createdby`
			WHERE ".$supervise_rec." (
				MONTH(`stc_safetyppec_date`) = '".mysqli_real_escape_string($this->stc_dbs, $month)."' AND
				YEAR(`stc_safetyppec_date`) = '".mysqli_real_escape_string($this->stc_dbs, $year)."'
			)
			ORDER BY DATE(`stc_safetyppec_date`) DESC
		");
		if(mysqli_num_rows($optimusprimequery)>0){
			$website=$_SERVER['SERVER_NAME'];
			$website = $website=="localhost" ? '' : 'https://stcassociate.com/stc_agent47/';
			foreach($optimusprimequery as $optimusprimerow){
				$action_show='
					<a href="'.$website.'stc-ppec-print-preview.php?ppec_no='.$optimusprimerow['stc_safetyppec_id'].'" class="form-control btn btn-success" >View</a>
					<a href="#" class="form-control btn btn-secondary stc-safetyppec-edit" id="'.$optimusprimerow['stc_safetyppec_id'].'">Edit</a>
					<a href="#" class="form-control btn btn-danger stc-safetyppec-delete" id="'.$optimusprimerow['stc_safetyppec_id'].'">Delete</a>
				';

				$optimusprime.='
					<tr>
						<td>'.date('d-m-Y', strtotime($optimusprimerow['stc_safetyppec_date'])).'</td>
						<td>'.$optimusprimerow['stc_safetyppec_wono'].'</td>
						<td>'.$optimusprimerow['stc_safetyppec_sitename'].'</td>
						<td>'.$action_show.'</td>
					</tr>
				';
			}
		}else{
			$optimusprime.='
				<tr>
					<td colspan="5">No data found</td>
				</tr>
			';
		}
		return $optimusprime;
	}

	// call ppem
	public function stc_call_ppem($month, $supervise_name){
		$optimusprime='';
		$month_arr = explode('-', date('m-Y', strtotime($month)));
		$month = $month_arr[0];
		$year = $month_arr[1];
		$supervise_rec="`stc_cust_pro_supervisor_fullname` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $supervise_name)."'
			AND";
		if($supervise_name==''){
			$supervise_rec='';
		}
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_safetyppem` 
			LEFT JOIN `stc_cust_pro_supervisor`
			ON `stc_cust_pro_supervisor_id`=`stc_safetyppem_createdby`
			WHERE ".$supervise_rec." (
				MONTH(`stc_safetyppem_date`) = '".mysqli_real_escape_string($this->stc_dbs, $month)."' AND
				YEAR(`stc_safetyppem_date`) = '".mysqli_real_escape_string($this->stc_dbs, $year)."'
			) 
			ORDER BY DATE(`stc_safetyppem_date`) DESC
		");
		if(mysqli_num_rows($optimusprimequery)>0){
			$website=$_SERVER['SERVER_NAME'];
			$website = $website=="localhost" ? '' : 'https://stcassociate.com/stc_agent47/';
			foreach($optimusprimequery as $optimusprimerow){
				$action_show='
					<a target="_blank" href="'.$website.'safety-ppem-print-preview.php?ppem_no='.$optimusprimerow['stc_safetyppem_id'].'" class="form-control btn btn-success" >View</a>
					<a href="#" class="form-control btn btn-secondary stc-safetyppem-edit" id="'.$optimusprimerow['stc_safetyppem_id'].'">Edit</a>
					<a href="#" class="form-control btn btn-danger stc-safetyppem-delete" id="'.$optimusprimerow['stc_safetyppem_id'].'">Delete</a>
				';

				$optimusprime.='
					<tr>
						<td>'.date('d-m-Y', strtotime($optimusprimerow['stc_safetyppem_date'])).'</td>
						<td>'.$optimusprimerow['stc_safetyppem_site_name'].'</td>
						<td>'.$optimusprimerow['stc_safetyppem_supervisor_name'].'</td>
						<td>'.$action_show.'</td>
					</tr>
				';
			}
		}else{
			$optimusprime.='
				<tr>
					<td colspan="5">No data found</td>
				</tr>
			';
		}
		return $optimusprime;
	}

	// call toollist
	public function stc_call_toolllist($month, $supervise_name){
		$optimusprime='';
		$month_arr = explode('-', date('m-Y', strtotime($month)));
		$month = $month_arr[0];
		$year = $month_arr[1];
		$supervise_rec="`stc_cust_pro_supervisor_fullname` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $supervise_name)."'
			AND";
		if($supervise_name==''){
			$supervise_rec='';
		}
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_safetytoolslist` 
			LEFT JOIN `stc_cust_pro_supervisor`
			ON `stc_cust_pro_supervisor_id`=`stc_safetytoolslist_createdby`
			WHERE ".$supervise_rec." (
				MONTH(`stc_safetytoolslist_date`) = '".mysqli_real_escape_string($this->stc_dbs, $month)."' AND
				YEAR(`stc_safetytoolslist_date`) = '".mysqli_real_escape_string($this->stc_dbs, $year)."'
			) 
			ORDER BY DATE(`stc_safetytoolslist_date`) DESC
		");
		if(mysqli_num_rows($optimusprimequery)>0){
			$website=$_SERVER['SERVER_NAME'];
			$website = $website=="localhost" ? '' : 'https://stcassociate.com/stc_agent47/';
			foreach($optimusprimequery as $optimusprimerow){
				$action_show='
					<a target="_blank" href="'.$website.'safety-ttht-print-preview.php?ttht_no='.$optimusprimerow['stc_safetytoolslist_id'].'" class="form-control btn btn-success" >View</a>
					<a href="#" class="form-control btn btn-secondary stc-safetytoollist-edit" id="'.$optimusprimerow['stc_safetytoolslist_id'].'">Edit</a>
					<a href="#" class="form-control btn btn-danger stc-safetytoollist-delete" id="'.$optimusprimerow['stc_safetytoolslist_id'].'">Delete</a>
				';

				$optimusprime.='
					<tr>
						<td>'.date('d-m-Y', strtotime($optimusprimerow['stc_safetytoolslist_date'])).'</td>
						<td>'.$optimusprimerow['stc_safetytoolslist_wono'].'</td>
						<td>'.$optimusprimerow['stc_safetytoolslist_sitename'].'</td>
						<td>'.$action_show.'</td>
					</tr>
				';
			}
		}else{
			$optimusprime.='
				<tr>
					<td colspan="5">No data found</td>
				</tr>
			';
		}
		return $optimusprime;
	}

	// call vhl
	public function stc_call_vhl($month, $supervise_name){
		$optimusprime='';
		$month_arr = explode('-', date('m-Y', strtotime($month)));
		$month = $month_arr[0];
		$year = $month_arr[1];
		$supervise_rec="`stc_cust_pro_supervisor_fullname` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $supervise_name)."'
			AND";
		if($supervise_name==''){
			$supervise_rec='';
		}
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_safetyvehicle` 
			LEFT JOIN `stc_cust_pro_supervisor`
			ON `stc_cust_pro_supervisor_id`=`stc_safetyvehicle_createdby`
			WHERE ".$supervise_rec." (
				MONTH(`stc_safetyvehicle_date`) = '".mysqli_real_escape_string($this->stc_dbs, $month)."' AND
				YEAR(`stc_safetyvehicle_date`) = '".mysqli_real_escape_string($this->stc_dbs, $year)."'
			) 
			ORDER BY DATE(`stc_safetyvehicle_date`) DESC
		");
		if(mysqli_num_rows($optimusprimequery)>0){
			$website=$_SERVER['SERVER_NAME'];
			$website = $website=="localhost" ? '' : 'https://stcassociate.com/stc_agent47/';
			foreach($optimusprimequery as $optimusprimerow){
				$action_show='
					<a target="_blank" href="'.$website.'safety-vhl-print-preview.php?vhl_no='.$optimusprimerow['stc_safetyvehicle_id'].'" class="form-control btn btn-success" >View</a>
					<a href="#" class="form-control btn btn-secondary stc-safetyvhl-edit" id="'.$optimusprimerow['stc_safetyvehicle_id'].'">Edit</a>
					<a href="#" class="form-control btn btn-danger stc-safetyvhl-delete" id="'.$optimusprimerow['stc_safetyvehicle_id'].'">Delete</a>
				';

				$optimusprime.='
					<tr>
						<td>'.date('d-m-Y', strtotime($optimusprimerow['stc_safetyvehicle_date'])).'</td>
						<td>'.$optimusprimerow['stc_safetyvehicle_desc'].'</td>
						<td>'.$optimusprimerow['stc_safetyvehicle_reg_no'].'</td>
						<td>'.date('d-m-Y', strtotime($optimusprimerow['stc_safetyvehicle_dateofinspection'])).'</td>
						<td>'.$optimusprimerow['stc_safetyvehicle_driversname'].'</td>
						<td>'.$action_show.'
						</td>
					</tr>
				';
			}
		}else{
			$optimusprime.='
				<tr>
					<td colspan="5">No data found</td>
				</tr>
			';
		}
		return $optimusprime;
	}

	// call hotwork
	public function stc_call_hotwork($month, $supervise_name){
		$optimusprime='';
		$month_arr = explode('-', date('m-Y', strtotime($month)));
		$month = $month_arr[0];
		$year = $month_arr[1];
		$supervise_rec="`stc_cust_pro_supervisor_fullname` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $supervise_name)."'
			AND";
		if($supervise_name==''){
			$supervise_rec='';
		}
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_safetyhotwork` 
			LEFT JOIN `stc_cust_pro_supervisor`
			ON `stc_cust_pro_supervisor_id`=`stc_safetyhotwork_createdby`
			WHERE ".$supervise_rec." (
				MONTH(`stc_safetyhotwork_startingdate`) = '".mysqli_real_escape_string($this->stc_dbs, $month)."' AND
				YEAR(`stc_safetyhotwork_startingdate`) = '".mysqli_real_escape_string($this->stc_dbs, $year)."'
			) 
			ORDER BY DATE(`stc_safetyhotwork_startingdate`) DESC
		");
		if(mysqli_num_rows($optimusprimequery)>0){
			$website=$_SERVER['SERVER_NAME'];
			$website = $website=="localhost" ? '' : 'https://stcassociate.com/stc_agent47/';
			foreach($optimusprimequery as $optimusprimerow){
				$action_show='
					<a target="_blank" href="'.$website.'safety-hotwork-print-preview.php?hotwork_no='.$optimusprimerow['stc_safetyhotwork_id'].'" class="form-control btn btn-success" >View</a>
					<a href="#" class="form-control btn btn-secondary stc-safetyhotwork-edit" id="'.$optimusprimerow['stc_safetyhotwork_id'].'">Edit</a>
					<a href="#" class="form-control btn btn-danger stc-safetyhotwork-delete" id="'.$optimusprimerow['stc_safetyhotwork_id'].'">Delete</a>
				';

				$optimusprime.='
					<tr>
						<td>'.date('d-m-Y', strtotime($optimusprimerow['stc_safetyhotwork_startingdate'])).'</td>
						<td>'.$optimusprimerow['stc_safetyhotwork_wono'].'</td>
						<td>'.$optimusprimerow['stc_safetyhotwork_jobssitename'].'</td>
						<td>'.$action_show.'</td>
					</tr>
				';
			}
		}else{
			$optimusprime.='
				<tr>
					<td colspan="5">No data found</td>
				</tr>
			';
		}
		return $optimusprime;
	}

	// call nearmiss
	public function stc_call_nearmiss($month, $supervise_name){
		$optimusprime='';
		$month_arr = explode('-', date('m-Y', strtotime($month)));
		$month = $month_arr[0];
		$year = $month_arr[1];
		$supervise_rec="`stc_cust_pro_supervisor_fullname` REGEXP '".mysqli_real_escape_string($this->stc_dbs, $supervise_name)."'
			AND";
		if($supervise_name==''){
			$supervise_rec='';
		}
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_safetynearmiss` 
			LEFT JOIN `stc_cust_pro_supervisor`
			ON `stc_cust_pro_supervisor_id`=`stc_safetynearmiss_createdby`
			WHERE ".$supervise_rec." (
				MONTH(`stc_safetynearmiss_date`) = '".mysqli_real_escape_string($this->stc_dbs, $month)."' AND
				YEAR(`stc_safetynearmiss_date`) = '".mysqli_real_escape_string($this->stc_dbs, $year)."'
			) 
			ORDER BY DATE(`stc_safetynearmiss_currdate`) DESC
		");
		if(mysqli_num_rows($optimusprimequery)>0){
			foreach($optimusprimequery as $optimusprimerow){
				$optimusprimeimgqry=mysqli_query($this->stc_dbs, "
					SELECT `stc_safetynearmiss_img_location` FROM `stc_safetynearmiss_img` WHERE `stc_safetynearmiss_img_nearmissid`='".$optimusprimerow['stc_safetynearmiss_id']."'
				");
				$img_path='';
				$imgcounter=0;
				$safety_image='';
				foreach($optimusprimeimgqry as $optimusprimeimgrow){
					$imgcounter++;
					$img_path=$optimusprimeimgrow['stc_safetynearmiss_img_location'];

					$safety_image.='
							<img src="../stc_sub_agent47/safety_img/'.$img_path.'" style="width: 190px;position: relative;padding: 0;margin: 0;">
					';
				}
				if($imgcounter<=2){
					$safety_image.="
						<form action='#' id='safety-nearmissimage-upload-form' >
							<input type='file' name='stc-safety-nearmissimage-path'>
							<input type='hidden' name='stc-safety-nearmiss-id' value='".$optimusprimerow['stc_safetynearmiss_id']."'>
							<input type='submit' value='Upload' class='btn btn-success'>
						</form>";
				}
				$website=$_SERVER['SERVER_NAME'];
				$website = $website=="localhost" ? '' : 'https://stcassociate.com/stc_agent47/';
				$action_show='
					<a target="_blank" href="'.$website.'safety-nearmiss-print-preview.php?nearmiss_no='.$optimusprimerow['stc_safetynearmiss_id'].'" class="form-control btn btn-success" >View</a>
					<a href="#" class="form-control btn btn-secondary stc-safetynearmiss-edit" id="'.$optimusprimerow['stc_safetynearmiss_id'].'">Edit</a>
					<a href="#" class="form-control btn btn-danger stc-safetynearmiss-delete" id="'.$optimusprimerow['stc_safetynearmiss_id'].'">Delete</a>
				';

				$optimusprime.='
					<tr>
						<td>'.date('d-m-Y', strtotime($optimusprimerow['stc_safetynearmiss_date'])).'</td>
						<td>'.$optimusprimerow['stc_safetynearmiss_time'].'</td>
						<td>'.$optimusprimerow['stc_safetynearmiss_location'].'</td>
						<td>'.$safety_image.'</td>
						<td>'.$action_show.'</td>
					</tr>
				';
			}
		}else{
			$optimusprime.='
				<tr>
					<td colspan="5">No data found</td>
				</tr>
			';
		}
		return $optimusprime;
	}

	// call user collaborated
	public function stc_user_collaborated($user_id){		
		$blackpearl='';
		$date=date("Y-m-d H:i:s");
		$blackpearl_qry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_cust_pro_supervisor_collaborate_id`,
				`stc_cust_pro_supervisor_collaborate_managerid`,
				`stc_cust_pro_supervisor_collaborate_teamid`,
				`stc_cust_pro_supervisor_collaborate_userid`,
				`stc_cust_pro_supervisor_collaborate_status`,
				`stc_cust_pro_supervisor_collaborate_created_date`,
				`stc_agents_name`
			FROM `stc_cust_pro_supervisor_collaborate`
			LEFT JOIN `stc_agents`
			ON `stc_cust_pro_supervisor_collaborate_teamid`=`stc_agents_id`
			WHERE
				`stc_cust_pro_supervisor_collaborate_userid`='".mysqli_real_escape_string($this->stc_dbs, $user_id)."'
		");
		if(mysqli_num_rows($blackpearl_qry)>0){
			foreach($blackpearl_qry as $blackpearl_row){
				$status=$blackpearl_row['stc_cust_pro_supervisor_collaborate_status']==1 ? "Active" : "in-active";
				$blackpearl.='
					<tr>
						<td class="text-center">'.date('d-m-Y', strtotime($blackpearl_row['stc_cust_pro_supervisor_collaborate_created_date'])).'</td>
						<td>'.$blackpearl_row['stc_agents_name'].'</td>
						<td class="text-center">'.$status.'</td>
						<td class="text-center"><a href="javascript:void(0)" class="btn btn-danger" '.$blackpearl_row['stc_cust_pro_supervisor_collaborate_id'].'><i class="fa fa-remove"></i></a></td>
					<tr>
				';
			}
		}else{
			$blackpearl.='
				<tr>
					<td colspan="4" class="text-center">No record found.</td>
				<tr>
			';
		}
		return $blackpearl;
	}

	// save project collaborate
	public function stc_user_collaborate_save($user_id, $email){
		$blackpearl='';
		$status="success";
		$message="User collaborated successfully.";
		$date=date("Y-m-d H:i:s");
		$blackpearl_cqry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_agents_id`
			FROM
				`stc_agents`
			WHERE
				`stc_agents_email`='".mysqli_real_escape_string($this->stc_dbs, $email)."'
		");
		if(mysqli_num_rows($blackpearl_cqry)>0){
			$result=mysqli_fetch_assoc($blackpearl_cqry);
			$team_id=$result['stc_agents_id'];
			$blackpearl_iqry=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_cust_pro_supervisor_collaborate`(
					`stc_cust_pro_supervisor_collaborate_managerid`,
					`stc_cust_pro_supervisor_collaborate_teamid`,
					`stc_cust_pro_supervisor_collaborate_userid`,
					`stc_cust_pro_supervisor_collaborate_status`,
					`stc_cust_pro_supervisor_collaborate_created_date`
				)VALUES(
					'".$_SESSION['stc_agent_id']."',
					'".mysqli_real_escape_string($this->stc_dbs, $team_id)."',
					'".mysqli_real_escape_string($this->stc_dbs, $user_id)."',
					'1',
					'".mysqli_real_escape_string($this->stc_dbs, $date)."'
				)
			");
		}else{
			$status="invalid";
			$message="Invalid email. Manager not found with this email. Please try registered email.";
		}
		$blackpearl_arr=array(
			'status' => $status,
			'message' => $message
		);
		return $blackpearl_arr;
	}
}

/*---------------------------------------------Project Objects section-------------------------------------------------*/
// call tbm  safety
if(isset($_POST['stc_safety_calltbm'])){
	$location 			= 	$_POST['location'];
	$month 			= 	$_POST['month'];
	$supervise_name	= 	$_POST['supervise_name'];
	$objsearchreq=new pirates_supervisor();
	$opobjsearchreq=$objsearchreq->stc_call_tbm($location, $month, $supervise_name);
	echo $opobjsearchreq;
}

// call ppec  safety
if(isset($_POST['stc_safety_callppec'])){
	$month 			= 	$_POST['month'];
	$supervise_name	= 	$_POST['supervise_name'];
	$objsearchreq=new pirates_supervisor();
	$opobjsearchreq=$objsearchreq->stc_call_ppec($month, $supervise_name);
	echo $opobjsearchreq;
}

// call ppem  safety
if(isset($_POST['stc_safety_callppem'])){
	$month 			= 	$_POST['month'];
	$supervise_name	= 	$_POST['supervise_name'];
	$objsearchreq=new pirates_supervisor();
	$opobjsearchreq=$objsearchreq->stc_call_ppem($month, $supervise_name);
	echo $opobjsearchreq;
}

// call toollist  safety
if(isset($_POST['stc_safety_calltoollist'])){
	$month 			= 	$_POST['month'];
	$supervise_name	= 	$_POST['supervise_name'];
	$objsearchreq=new pirates_supervisor();
	$opobjsearchreq=$objsearchreq->stc_call_toolllist($month, $supervise_name);
	echo $opobjsearchreq;
}

// call vhl  safety
if(isset($_POST['stc_safety_callvhl'])){
	$month 			= 	$_POST['month'];
	$supervise_name	= 	$_POST['supervise_name'];
	$objsearchreq=new pirates_supervisor();
	$opobjsearchreq=$objsearchreq->stc_call_vhl($month, $supervise_name);
	echo $opobjsearchreq;
}

// call hotwork  safety
if(isset($_POST['stc_safety_callhotwork'])){
	$month 			= 	$_POST['month'];
	$supervise_name	= 	$_POST['supervise_name'];
	$objsearchreq=new pirates_supervisor();
	$opobjsearchreq=$objsearchreq->stc_call_hotwork($month, $supervise_name);
	echo $opobjsearchreq;
}

// call nearmiss  safety
if(isset($_POST['stc_safety_callnearmiss'])){
	$month 			= 	$_POST['month'];
	$supervise_name	= 	$_POST['supervise_name'];
	$objsearchreq=new pirates_supervisor();
	$opobjsearchreq=$objsearchreq->stc_call_nearmiss($month, $supervise_name);
	echo $opobjsearchreq;
}

// create project
if(isset($_POST['stc_cust_project_action'])){
	$pro_cust 		= 	$_POST['stc_cust_pro_cust'];
	$pro_title 		=	$_POST['stc_cust_pro_title'];
	$pro_refr		= 	$_POST['stc_cust_pro_refr'];
	$pro_address	=	$_POST['stc_cust_pro_address'];
	$pro_city 		=	$_POST['stc_cust_pro_city'];
	$pro_state 		=	$_POST['stc_cust_pro_state'];
	$pro_resperson	=	$_POST['stc_cust_pro_responperson'];
	$pro_supqty		=	$_POST['stc_cust_pro_supquantity'];
	$pro_begdate	=	date("Y-m-d", strtotime($_POST['stc_cust_pro_begdate']));
	$pro_enddate	=	date("Y-m-d", strtotime($_POST['stc_cust_pro_enddate']));
	$pro_begbudget	=	$_POST['stc_cust_pro_begbudget'];
	$pro_status		=	$_POST['stc_cust_pro_status'];
	$outcome		=	'';
	if(
		empty($pro_title) || 
		empty($pro_address) || 
		empty($pro_city) || 
		empty($pro_state) || 
		empty($pro_resperson) || 
		empty($pro_begdate) || 
		empty($pro_enddate) || 
		empty($pro_begbudget) || 
		empty($pro_status)
	){
		$outcome='empty';
	}elseif(empty($_SESSION['stc_agent_id'])){
		$outcome='reload';
	}else{
		$objcrproj=new pirates_project();
		$opobjcrproj=$objcrproj->stc_create_project(
			$pro_cust,
			$pro_title, 
			$pro_refr,
			$pro_address, 
			$pro_city, 
			$pro_state, 
			$pro_resperson,
			$pro_supqty, 
			$pro_begdate, 
			$pro_enddate, 
			$pro_begbudget, 
			$pro_status
		);
		$outcome = $opobjcrproj;
	}
	echo json_encode($outcome);
}

// save project details
if(isset($_POST['stc_ag_pro_hit'])){
	$stc_pg_id=$_POST['stc_pg_id'];
	$stc_cust_name=$_POST['stc_cust_name'];
	$stc_cust_emailid=$_POST['stc_cust_emailid'];
	$stc_cust_cont_no=$_POST['stc_cust_cont_no'];
	$stc_purpose=$_POST['stc_purpose'];
	$stc_ref_cont=$_POST['stc_ref_cont'];
	$stc_job_details=$_POST['stc_job_details'];
	$stc_quote_number=$_POST['stc_quote_number'];
	$stc_quote_date=$_POST['stc_quote_date'];
	$stc_basic_val=$_POST['stc_basic_val'];
	$stc_gst_val=$_POST['stc_gst_val'];
	$stc_quoted_by=$_POST['stc_quoted_by'];
	$stc_mode_of_quotation=$_POST['stc_mode_of_quotation'];
	$stc_target_price=$_POST['stc_target_price'];
	$stc_status=$_POST['stc_status'];
	$stc_remarks=$_POST['stc_remarks'];
	$stc_po_number=$_POST['stc_po_number'];
	$stc_po_value=$_POST['stc_po_value'];


	$objcrproj=new pirates_project();
	$opobjcrproj=$objcrproj->stc_project_details_save($stc_pg_id, $stc_cust_name, $stc_cust_emailid, $stc_cust_cont_no, $stc_purpose, $stc_ref_cont, $stc_job_details, $stc_quote_number, $stc_quote_date, $stc_basic_val, $stc_gst_val, $stc_quoted_by, $stc_mode_of_quotation, $stc_target_price, $stc_status, $stc_remarks, $stc_po_number, $stc_po_value);
	echo $opobjcrproj;
}

// retrieve project details
if(isset($_POST['stc_ag_rproject_retrive'])){
	$project_id=$_POST['project_id'];
	$objcrproj=new pirates_project();
	$opobjcrproj=$objcrproj->stc_call_project_details($project_id);
	echo json_encode($opobjcrproj);
	// echo $opobjcrproj;
}

// retrieve connected supervisor for project
if(isset($_POST['stc_ag_rsupervisorproject_retrive'])){
	$project_id=$_POST['project_id'];
	$objcrproj=new pirates_project();
	$opobjcrproj=$objcrproj->stc_call_connected_supervisor($project_id);
	echo json_encode($opobjcrproj);
	// echo $opobjcrproj;
}

// call project ahu from table
if(isset($_POST['stc_ag_rproject_ahu_details_call'])){
	$project_id=$_POST['project_id'];
	$objcrproj=new pirates_project();
	$opobjcrproj=$objcrproj->stc_call_project_ahu_details($project_id);
	echo json_encode($opobjcrproj);
	// echo $opobjcrproj;
}

// call pump details
if(isset($_POST['stc_ag_rproject_pump_details_call'])){
	$project_id=$_POST['project_id'];
	$objcrproj=new pirates_project();
	$opobjcrproj=$objcrproj->stc_call_project_pump_details($project_id);
	echo json_encode($opobjcrproj);
	// echo $opobjcrproj;
}

// edit project details
if(isset($_POST['stc_ag_rproject_edit'])){
	$pro_id=$_POST['pro_id'];
	$pro_title=$_POST['pro_title'];
	$pro_refr=$_POST['pro_refr'];
	$pro_details=$_POST['pro_details'];
	$objcrproj=new pirates_project();
	$opobjcrproj=$objcrproj->stc_edit_project_details($pro_id, $pro_title, $pro_refr, $pro_details);
	// echo json_encode($opobjcrproj);
	echo $opobjcrproj;
}

// call dept details
if(isset($_POST['stc_ag_rproject_ahu_details_retrieve'])){
	$dept_id = $_POST['cust_dept_id'];
	$objcrproj=new pirates_project();
	$opobjcrproj=$objcrproj->stc_ahu_details($dept_id);
	echo json_encode($opobjcrproj);
	// echo $opobjcrproj;
}

// save dept details
if(isset($_POST['stc_ag_rproject_ahu_details_set'])){
	$cust_dept_id = $_POST['cust_dept_id'];
	$stc_cust_location_name = $_POST['stc_cust_location_name'];
	$stc_cust_sub_location_name = $_POST['stc_cust_sub_location_name'];
	$stc_cust_ahu_no = $_POST['stc_cust_ahu_no'];
	$stc_cust_desgn_temp = $_POST['stc_cust_desgn_temp'];
	$stc_cust_air_size = $_POST['stc_cust_air_size'];
	$stc_cust_air_qty = $_POST['stc_cust_air_qty'];
	$stc_cust_v_belt_size = $_POST['stc_cust_v_belt_size'];
	$stc_cust_v_belt_qty = $_POST['stc_cust_v_belt_qty'];
	$stc_cust_blower_pulley_size = $_POST['stc_cust_blower_pulley_size'];
	$stc_cus_motor_pulley_size = $_POST['stc_cus_motor_pulley_size'];
	$stc_cus_blower_bearing_de = $_POST['stc_cus_blower_bearing_de'];
	$stc_cus_blower_bearing_nde = $_POST['stc_cus_blower_bearing_nde'];
	$stc_cus_blower_bearing_housing = $_POST['stc_cus_blower_bearing_housing'];
	$stc_cus_motor_bearing_de = $_POST['stc_cus_motor_bearing_de'];
	$stc_cus_motor_bearing_nde = $_POST['stc_cus_motor_bearing_nde'];
	$stc_cus_motor_capacity_hp = $_POST['stc_cus_motor_capacity_hp'];
	$stc_cus_motor_capacity_kw = $_POST['stc_cus_motor_capacity_kw'];
	$stc_cus_motor_capacity_amps = $_POST['stc_cus_motor_capacity_amps'];
	$stc_cus_motor_capacity_rpm = $_POST['stc_cus_motor_capacity_rpm'];
	$stc_cus_motor_weight = $_POST['stc_cus_motor_weight'];
	$stc_cus_ahu_frame_size = $_POST['stc_cus_ahu_frame_size'];
	$stc_cus_ahu_cfm = $_POST['stc_cus_ahu_cfm'];
	$ahu_type = $_POST['ahu_type'];
	$opobjcrproj='';
	$objcrproj=new pirates_project();
	if(empty($_SESSION['stc_agent_id'])){
		$opobjcrproj = 'Login';
	}elseif($cust_dept_id=="NA"){
		$opobjcrproj = 'dept';
	}else{
		$opobjcrproj=$objcrproj->stc_ahu_details_set($cust_dept_id, $stc_cust_location_name, $stc_cust_sub_location_name, $stc_cust_ahu_no, $stc_cust_desgn_temp, $stc_cust_air_size, $stc_cust_air_qty, $stc_cust_v_belt_size, $stc_cust_v_belt_qty, $stc_cust_blower_pulley_size, $stc_cus_motor_pulley_size, $stc_cus_blower_bearing_de, $stc_cus_blower_bearing_nde, $stc_cus_blower_bearing_housing, $stc_cus_motor_bearing_de, $stc_cus_motor_bearing_nde, $stc_cus_motor_capacity_hp, $stc_cus_motor_capacity_kw, $stc_cus_motor_capacity_amps, $stc_cus_motor_capacity_rpm, $stc_cus_motor_weight, $stc_cus_ahu_frame_size, $stc_cus_ahu_cfm, $ahu_type);
	}
	// echo $opobjcrproj;
	echo json_encode($opobjcrproj);
}

// save pump details
if(isset($_POST['stc_ag_rproject_pump_details_set'])){
	$stc_cust_pro_location_id=$_POST['stc_cust_pro_location_id'];
	$stc_cust_sub_location=$_POST['stc_cust_sub_location'];
	$stc_cust_area=$_POST['stc_cust_area'];
	$stc_cust_equipment_type=$_POST['stc_cust_equipment_type'];
	$stc_cust_equipment_number=$_POST['stc_cust_equipment_number'];
	$stc_cus_totaltr=$_POST['stc_cus_totaltr'];
	$stc_cus_Unit=$_POST['stc_cus_Unit'];

	$opobjcrproj='';
	$objcrproj=new pirates_project();
	if(empty($_SESSION['stc_agent_id'])){
		$opobjcrproj = 'Login';
	}elseif($stc_cust_pro_location_id=="NA"){
		$opobjcrproj = 'dept';
	}else{
		$opobjcrproj=$objcrproj->stc_pump_details_set($stc_cust_pro_location_id, $stc_cust_sub_location, $stc_cust_area, $stc_cust_equipment_type, $stc_cust_equipment_number, $stc_cus_totaltr, $stc_cus_Unit);
	}
	// echo $opobjcrproj;
	echo json_encode($opobjcrproj);
}

// save job type
if(isset($_POST['stc_ag_rproject_jobtype_set'])){
	$stc_cust_job_type=$_POST['stc_cust_job_type'];
	$stc_job_varities=$_POST['stc_job_varities'];
	$opobjcrproj='';
	$objcrproj=new pirates_project();
	if(empty($_SESSION['stc_agent_id'])){
		$opobjcrproj = 'Login';
	}elseif($stc_cust_job_type=="NA"){
		$opobjcrproj = 'jtype';
	}else{
		$opobjcrproj=$objcrproj->stc_job_type_set($stc_cust_job_type, $stc_job_varities);
	}
	// echo $opobjcrproj;
	echo json_encode($opobjcrproj);
}

// remove pump details
if(isset($_POST['stc_ag_rpump_details_remove'])){
	$stc_pump_det_id=$_POST['stc_pump_det_id'];

	$objcrproj=new pirates_project();
	$opobjcrproj='';
	if(empty($_SESSION['stc_agent_id'])){
		$opobjcrproj = 'Login';
	}else{
		$opobjcrproj=$objcrproj->stc_remove_pump_details($stc_pump_det_id);
	}
	// echo $opobjcrproj;
	echo json_encode($opobjcrproj);
}

// save job type
if(isset($_POST['stc_ag_rproject_department'])){
	$stc_locname=$_POST['stc_locname'];
	$stc_deptname=$_POST['stc_deptname'];
	$stc_dept_pro_id=$_POST['stc_dept_pro_id'];
	$opobjcrproj='';
	$objcrproj=new pirates_project();
	if(empty($_SESSION['stc_agent_id'])){
		$opobjcrproj = 'Login';
	}elseif($stc_locname=="" || $stc_deptname==""){
		$opobjcrproj = 'empty';
	}else{
		$opobjcrproj=$objcrproj->stc_department_set($stc_locname, $stc_deptname, $stc_dept_pro_id);
	}
	// echo $opobjcrproj;
	echo json_encode($opobjcrproj);
}

// show job type
if(isset($_POST['stc_ag_job_type_show'])){
	$objcrproj=new pirates_project();
	$opobjcrproj='';
	if(empty($_SESSION['stc_agent_id'])){
		$opobjcrproj = 'Login';
	}else{
		$opobjcrproj=$objcrproj->stc_job_type_show();
	}
	// echo $opobjcrproj;
	echo json_encode($opobjcrproj);
}

// show job type
if(isset($_POST['stc_ag_department_show'])){
	$objcrproj=new pirates_project();
	$opobjcrproj='';
	if(empty($_SESSION['stc_agent_id'])){
		$opobjcrproj = 'Login';
	}else{
		$opobjcrproj=$objcrproj->stc_department_show();
	}
	// echo $opobjcrproj;
	echo json_encode($opobjcrproj);
}

// job type update
if(isset($_POST['stc_ag_job_type_show_save'])){
	$jobtype=$_POST['jobtype'];
	$jobvarities=$_POST['jobvarities'];
	$jobtid=$_POST['jobtid'];

	$objcrproj=new pirates_project();
	$opobjcrproj='';
	if(empty($_SESSION['stc_agent_id'])){
		$opobjcrproj = 'Login';
	}else{
		$opobjcrproj=$objcrproj->stc_job_type_show_update($jobtype, $jobvarities, $jobtid);
	}
	// echo $opobjcrproj;
	echo json_encode($opobjcrproj);
}

// department update
if(isset($_POST['stc_ag_department_show_save'])){
	$loc=$_POST['loc'];
	$dept=$_POST['dept'];
	$jobtid=$_POST['jobtid'];

	$objcrproj=new pirates_project();
	$opobjcrproj='';
	if(empty($_SESSION['stc_agent_id'])){
		$opobjcrproj = 'Login';
	}else{
		$opobjcrproj=$objcrproj->stc_department_show_update($loc, $dept, $jobtid);
	}
	// echo $opobjcrproj;
	echo json_encode($opobjcrproj);
}

// call collaborate project
if(isset($_POST['stc_collaborate_project_call'])){
	$id=$_POST['id'];

	$objcrproj=new pirates_project();
	$opobjcrproj='';
	if(empty($_SESSION['stc_agent_id'])){
		$opobjcrproj = 'Login';
	}else{
		$opobjcrproj=$objcrproj->stc_project_collaborated($id);
	}
	// echo $opobjcrproj;
	echo json_encode($opobjcrproj);
}

// call collaborate project
if(isset($_POST['stc_collaborate_project_save'])){
	$project_id=$_POST['project_id'];
	$email=$_POST['email'];

	$objcrproj=new pirates_project();
	$opobjcrproj='';
	if(empty($_SESSION['stc_agent_id'])){
		$opobjcrproj = 'empty';
	}else{
		$opobjcrproj=$objcrproj->stc_project_collaborate_save($project_id, $email);
	}
	// echo $opobjcrproj;
	echo json_encode($opobjcrproj);
}
/*---------------------------------------------Supervisors Objects section-------------------------------------------------*/
// create supervisor
if(isset($_POST['stc_cust_supervisor_action'])){
	$sup_cust 		= 	$_POST['stc_cust_sup_cust'];
	$sup_fullname	=	$_POST['stc_cust_sup_fullname'];
	$sup_cont 		=	$_POST['stc_cust_sup_contnumber'];
	$sup_whatsapp 	=	$_POST['stc_cust_sup_whatsapp'];
	$sup_email 		=	$_POST['stc_cust_sup_email'];
	$sup_address	=	$_POST['stc_cust_sup_address'];
	$sup_city 		=	$_POST['stc_cust_sup_city'];
	$sup_state 		=	$_POST['stc_cust_sup_state'];
	$sup_pincode 	=	$_POST['stc_cust_sup_pincode'];
	$stc_category 	= 	$_POST['stc_cust_sup_category'];
	$sup_status		=	$_POST['stc_cust_sup_status'];
	$outcome		=	'';
	function randomPassword() {
		$alphabet = "abcdefghijklmnopqrstuwvxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$pass = array(); //remember to declare $pass as an array
		$alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < 8; $i++) {
		    $n = rand(0, $alphaLength);
		    $pass[] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
	}
	$from="info@stcassociate.com";
	$website=$_SERVER['SERVER_NAME'];
	if(strpos($website, 'agent')!=''){
		$from="info@agent.stcassociate.com";
	}
	$headers = "MIME-Version: 1.0" . "\r\n"; 
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n"; 
	$headers .= 'From: '.$from;
	$sup_password=randomPassword();
	$maildesc= '
		<!DOCTYPE html>
        <html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
        <head>
          <meta charset="utf-8">
          <meta name="viewport" content="width=device-width,initial-scale=1">
          <meta name="x-apple-disable-message-reformatting">
          <title></title>
          <!--[if mso]>
          <style>
            table {border-collapse:collapse;border-spacing:0;border:none;margin:0;}
            div, td {padding:0;}
            div {margin:0 !important;}
          </style>
          <noscript>
            <xml>
              <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
              </o:OfficeDocumentSettings>
            </xml>
          </noscript>
          <![endif]-->
          <style>
            table, td, div, h1, p {
              font-family: Arial, sans-serif;
            }
            @media screen and (max-width: 530px) {
              .unsub {
                display: block;
                padding: 8px;
                margin-top: 14px;
                border-radius: 6px;
                background-color: #555555;
                text-decoration: none !important;
                font-weight: bold;
              }
              .col-lge {
                max-width: 100% !important;
              }
            }
            @media screen and (min-width: 531px) {
              .col-sml {
                max-width: 27% !important;
              }
              .col-lge {
                max-width: 73% !important;
              }
            }
          </style>
        </head>
        <body style="margin:0;padding:0;word-spacing:normal;background-color:#939297;">
          <div role="article" aria-roledescription="email" lang="en" style="text-size-adjust:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#939297;">
            <table role="presentation" style="width:100%;border:none;border-spacing:0;">
              <tr>
                <td align="center" style="padding:0;">
                  <table role="presentation" style="width:94%;max-width:600px;border:none;border-spacing:0;text-align:left;font-family:Arial,sans-serif;font-size:16px;line-height:22px;color:#363636;">
                    <tr>
                      <td style="padding:40px 30px 30px 30px;text-align:center;font-size:24px;font-weight:bold;">
                        <a href="http://www.example.com/" style="text-decoration:none;"><img src="http://stcassociate.com/stc_symbiote/img/stc_logo.png" width="165" alt="Logo" style="border-radius: 50%;width:80%;max-width:165px;height:auto;border:none;text-decoration:none;color:#ffffff;"></a>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:30px;background-color:#ffffff;">
                        <h1 style="margin-top:0;margin-bottom:16px;font-size:26px;line-height:32px;font-weight:bold;letter-spacing:-0.02em;">Welcome to STC Associates</h1>
                        <p style="margin:0;">You are registered as a Project Supervisor from your sr.Executive - '.$_SESSION['stc_agent_name'].'.
                            Here is a link of your platform <a href="http://subagent.stcassociate.com/" style="color:#e50d70;text-decoration:underline;">Click me</a> wth your<br>
                            User id - <b>'.$sup_cont.'</b><br>
                            Password - <b>'.$sup_password.'</b>
                        </p>
                      </td>
                    </tr>
                    <tr>
                      <td style="padding:0;font-size:24px;line-height:28px;font-weight:bold;">
                        <a href="#" style="text-decoration:none;"><img src="https://library.kissclipart.com/20180904/vzw/kissclipart-supervisor-dibujo-clipart-construction-laborer-cli-c856a67bc49abb0e.jpg" width="600" alt="" style="width:100%;height:auto;display:block;border:none;text-decoration:none;color:#363636;"></a>
                      </td>
                    </tr>
                    <tr>
                        <td style="padding:30px;text-align:center;font-size:12px;background-color:#404040;color:#cccccc;">
                          <p style="margin:0;font-size:14px;line-height:20px;">&reg; STC Associates, Jamshedpur 2021<br><a class="unsub" href="#" style="color:#cccccc;text-decoration:underline;">This is System generated mail. Please do not reply.</a></p>
                        </td>
                    </tr>
                  </table>
                  <!--[if mso]>
                  </td>
                  </tr>
                  <tr>
                    <td style="padding:30px;text-align:center;font-size:12px;background-color:#404040;color:#cccccc;">
                      <p style="margin:0;font-size:14px;line-height:20px;">&reg; STC Associates, Jamshedpur 2021<br><a class="unsub" href="#" style="color:#cccccc;text-decoration:underline;">This is System generated mail. Please do not reply.</a></p>
                    </td>
                   </tr>
                  </table>
                  <![endif]-->
                </td>
              </tr>
            </table>
          </div>
        </body>
        </html> 	
	';
	if(
		empty($sup_cust) || 
		empty($sup_fullname) || 
		empty($sup_cont) ||  
		empty($sup_whatsapp) || 
		empty($sup_email) || 
		empty($sup_address) || 
		empty($sup_city) || 
		empty($sup_state) || 
		empty($sup_pincode) || 
		empty($stc_category) || 
		empty($sup_status)
	){
		$outcome='empty';
	}elseif(empty($_SESSION['stc_agent_id'])){
		$outcome="logout";
	}else{
		$objcrproj=new pirates_supervisor();
		$opobjcrproj=$objcrproj->stc_create_supervisor(
			$sup_cust,
			$sup_fullname,
			$sup_cont,
			$sup_whatsapp,
			$sup_email,
			$sup_address,
			$sup_city,
			$sup_state,
			$sup_pincode,
			$sup_password,
			$stc_category,
			$sup_status
		);
		$outcome = $opobjcrproj;
		if($outcome == "yes"){
			mail($sup_email, "Welcome ".$sup_fullname, $maildesc, $headers);
		}
	}
	// echo $outcome;
	echo json_encode($outcome);
}

// alot project to supervisor
if(isset($_POST['stc_load_single_user'])){
	$userid	= 	$_POST['user_id'];
	$outcome		=	'';

	if( empty($userid) ){
		$outcome='empty';
	}elseif(empty($_SESSION['stc_agent_id'])){
		$outcome="logout";
	}else{
		$objcrproj=new pirates_supervisor();
		$opobjcrproj=$objcrproj->stc_call_singleuser(
			$userid
		);
		$outcome = $opobjcrproj;
	}
	// echo $outcome;
	echo json_encode($outcome);
}

if(isset($_POST['stc_update_single_user'])){
	$sup_user_id 		= 	$_POST['user_id'];
	$sup_cust 		= 	$_POST['cust_id'];
	$sup_fullname	=	$_POST['fullname'];
	$sup_cont 		=	$_POST['contnumber'];
	$sup_whatsapp 	=	$_POST['whatsapp'];
	$sup_email 		=	$_POST['email'];
	$sup_address	=	$_POST['address'];
	$sup_city 		=	$_POST['city'];
	$sup_state 		=	$_POST['state'];
	$sup_pincode 	=	$_POST['pincode'];
	$stc_category 	= 	$_POST['category'];
	$sup_status		=	$_POST['status'];
	$outcome		=	'';
	if(
		empty($sup_user_id) ||
		empty($sup_cust) || 
		empty($sup_fullname) || 
		empty($sup_cont) ||  
		empty($sup_whatsapp) || 
		empty($sup_email) || 
		empty($sup_address) || 
		empty($sup_city) || 
		empty($sup_state) || 
		empty($sup_pincode) || 
		empty($stc_category)
	){
		$outcome='empty';
	}elseif(empty($_SESSION['stc_agent_id'])){
		$outcome="logout";
	}else{
		$objcrproj=new pirates_supervisor();
		$opobjcrproj=$objcrproj->stc_update_supervisor(
			$sup_user_id,
			$sup_cust,
			$sup_fullname,
			$sup_cont,
			$sup_whatsapp,
			$sup_email,
			$sup_address,
			$sup_city,
			$sup_state,
			$sup_pincode,
			$stc_category,
			$sup_status
		);
		$outcome = $opobjcrproj;
	}
	// echo $outcome;
	echo json_encode($outcome);
}

// alot project to supervisor
if(isset($_POST['stc_alot_project_action'])){
	$alotp_proid	= 	$_POST['stc_alot_pro_project'];
	$alotp_supid	=	$_POST['stc_alot_pro_supervisor'];
	$outcome		=	'';

	if( empty($alotp_proid) || empty($alotp_supid) ){
		$outcome='empty';
	}elseif(empty($_SESSION['stc_agent_id'])){
		$outcome="logout";
	}else{
		$objcrproj=new pirates_supervisor();
		$opobjcrproj=$objcrproj->stc_alot_project_supervisor(
			$alotp_proid,
			$alotp_supid
		);
		$outcome = $opobjcrproj;
	}
	// echo $outcome;
	echo json_encode($outcome);
}

// get perticular orders
if(isset($_POST['get_orders_pert'])){
	$odid=$_POST['odid'];
	$megabots=new pirates_supervisor();
	$outmegabots=$megabots->stc_ag_order_items_call($odid);
	echo $outmegabots;
}

// get perticular requisitions
if(isset($_POST['get_req_orders_pert'])){
	$odid=$_POST['odid'];
	$megabots=new pirates_supervisor();
	$outmegabots=$megabots->stc_ag_req_items_call($odid);
	echo $outmegabots;
}

// validate requisition change
if(isset($_POST['set_for_validate'])){
	$odid=$_POST['odid'];
	$validate_value=$_POST['validate_value'];
	$megabots=new pirates_supervisor();
	$outmegabots=$megabots->stc_sup_order_validate_items($odid, $validate_value);
	$totalrecall=new pirates_supervisor();
	$outtotalrecall=$totalrecall->stc_ag_order_items_call($odid);
	$out=array(
		"action" => $outmegabots,
		"reaction" => $outtotalrecall
	);
	echo json_encode($out);
}

// go to table place order 
if(isset($_POST['place_order'])){
	$out='';
	$odid=$_POST['odid'];
	if(empty($odid)){
		$outmegabots="reload";
	}else{
		$megabotspre=new pirates_supervisor();
		$outmegabotspre=$megabotspre->stc_check_item_existance($odid);	
		if($outmegabotspre=="no"){
			$out="no";
		}else{
			$megabots=new pirates_supervisor();
			$outmegabots=$megabots->stc_place_requisition($odid);	
			if($outmegabots=="yes"){
				$megabots=new pirates_supervisor();
				$outmegabots1=$megabots->stc_place_requisition_items($odid);
				$out=$outmegabots1;
			}else{
				$out="Please try again later!!!";
			}	
		}
	}
	echo $out;
}

// go to requistion session
if(isset($_POST['stc_addtopurchase'])){
	$item_id=$_POST['item_id'];
	$itemqty=$_POST['itemqty'];
	$itemstatus=$_POST['itemstatus'];
	$megabotspre=new pirates_supervisor();
	$outmegabotspre=$megabotspre->stc_add_to_purchase($item_id, $itemqty, $itemstatus);
	echo $outmegabotspre;

}


// go to requistion session
if(isset($_POST['go_for_req_sess'])){
	$item_id=$_POST['item_id'];
	$itemqty=$_POST['itemqty'];
	$itemstatus=$_POST['itemstatus'];
	if($itemqty>0){
		if(isset($_SESSION["stc_supervisor_req_sess"])) {  
			$is_available = 0;  
			foreach($_SESSION["stc_supervisor_req_sess"] as $keys => $values) {  
				if($_SESSION["stc_supervisor_req_sess"][$keys]['product_id'] == $_POST["item_id"]) {  
				     $is_available++;  
				     $_SESSION["stc_supervisor_req_sess"][$keys]['product_quantity'] = 
				     $_SESSION["stc_supervisor_req_sess"][$keys]['product_quantity'] + $_POST["itemqty"];  
				     echo "Item Cart Quantity Increased!!!!!!";break;
				}  
			}  
		    if($is_available < 1) {  
		    	$item_array = array(  
		    	     'product_id'             	=>     $_POST["item_id"],  
		    	     'product_quantity'       	=>     $_POST["itemqty"],
		    	     'itemstatus'				=>     $_POST["itemstatus"]  
		    	);  
		    	$_SESSION["stc_supervisor_req_sess"][] = $item_array;  
		    	echo "Item Added to Requisition Cart!!!";
		    }  
		}else{  
			$item_array = array(  
		    	     'product_id'             	=>     $_POST["item_id"],  
		    	     'product_quantity'       	=>     $_POST["itemqty"],
		    	     'itemstatus'				=>     $_POST["itemstatus"]  
			);   
			$_SESSION["stc_supervisor_req_sess"][] = $item_array;  
		     echo "Requisition Cart Created & Item Added to Requisition Cart!!!";
		}  
	}else{
		echo "Invalid Quantity.";
	}
}

// remove requisition item from requisiotn cart
if(isset($_POST['remove_from_req_sess'])){
	$item_id=$_POST['item_id'];
	if(!empty($_SESSION["stc_supervisor_req_sess"])){
		foreach($_SESSION["stc_supervisor_req_sess"] as $keys => $requisitioncrow){
			if($requisitioncrow['product_id']==$item_id){
				unset($_SESSION["stc_supervisor_req_sess"][$keys]);  
			}
		}
	}
}

// go to table requisition 
if(isset($_POST['place_requisition'])){
	$odid=$_POST['odid'];
	$megabots=new pirates_supervisor();
	$outmegabots=$megabots->stc_set_req_appr($odid);
	echo $outmegabots;
}

// call project linked by supervisor
if(isset($_POST['get_supervisorconsite'])){
	$supervisor_id=$_POST['stcsupid'];
	$objloki=new pirates_project();
	$outobjloki=$objloki->stc_call_linked_projects($supervisor_id);
	echo $outobjloki;
}

// get supervisors orders requisitions
if(isset($_POST['js_pending_reports_req'])){
	$stc_begdate=date("Y-m-d", strtotime($_POST['stc_begdate']));
	$stc_enddate=date("Y-m-d", strtotime($_POST['stc_enddate']));
	$stc_superid=$_POST['stc_superid'];
	$stc_projeid=$_POST['stc_projeid'];
	$out='
		<table class="mb-0 table table-bordered table-hover" id="stc-reports-pending-view">
			<thead>
			<tr>
			    <th class="text-center">#</th>
			    <th class="text-center">Date</th>
			    <th class="text-center">PR No</th>
			    <th class="text-center">Item Desc</th>
			    <th class="text-center">Unit</th>
			    <th class="text-center">PR Qty</th>
			    <th class="text-center">GM Apprv Qty</th>
			    <th class="text-center">Proc Apprv Qty</th>
			    <th class="text-center">Dispatch Qty</th>
			    <th class="text-center">Recieved Qty</th>
			    <th class="text-center">Pending Qty</th>
			    <th class="text-center">Consumed Qty</th>
			    <th class="text-center">Stock</th>
			    <th class="text-center">Priority</th>
			    <th class="text-center">Status</th>
			</tr>
			</thead>
			<tbody>
	';
	$start_date = strtotime($stc_begdate);
	$end_date = strtotime($stc_enddate);
	$objloki=new pirates_supervisor();
	if(empty($stc_begdate) || empty($stc_enddate) || $stc_superid==0 || $stc_superid==0){
		$out='
			<tr>
				<td colspan="10">Dont late any fields empty</td>
			</tr>
		';
	}else{
		$opobjloki=$objloki->stc_get_supervisors_pending_records($stc_begdate, $stc_enddate, $stc_superid, $stc_projeid);
		$out.=$opobjloki;
	}

	$out.='
			</tbody>
		</table>
	';
	echo $out;
}

// call consumption by supervisor
if(isset($_POST['js_pending_consump_reports_req'])){
	$stc_begdate=date("Y-m-d", strtotime($_POST['stc_begdate']));
	$stc_enddate=date("Y-m-d", strtotime($_POST['stc_enddate']));
	$stc_superid=$_POST['stc_superid'];
	$stc_projeid=$_POST['stc_projeid'];
	$out='
		<table class="mb-0 table table-bordered table-hover">
			<thead>
				<tr>
					<th>Sl No</th>
					<th>Location/Sitenamne</th>
					<th>Sub Location</th>
					<th>Job Details</th>
					<th>Items Desc</th>
					<th>Item Quantity</th>
					<th>Item Unit</th>
				</tr>
			</thead>
			<tbody>
	';
	$start_date = strtotime($stc_begdate);
	$end_date = strtotime($stc_enddate);
	if((($end_date - $start_date)/60/60/24)<=31){
		$objloki=new pirates_supervisor();
		if(empty($stc_begdate) || empty($stc_enddate) || $stc_superid==0 || $stc_superid==0){
			$out='
				<tr>
					<td colspan="10">Dont late any fields empty</td>
				</tr>
			';
		}else{
			$opobjloki=$objloki->stc_get_supervisors_pending_consump_records($stc_begdate, $stc_enddate, $stc_superid, $stc_projeid);
			$out.=$opobjloki;
		}
	}else{
		$out.='
				<tr>
					<td colspan="10">I can give you result only for 31 days...You need to choose result in between 1 month gap.</td>
				</tr>
		';
	}

	$out.='
			</tbody>
		</table>
	';
	echo $out;
}

// call consumption by supervisor
if(isset($_POST['js_search_attendance'])){
	$dept=$_POST['dept'];
	$month=date('m', strtotime($_POST['date']));
	$year=date('Y', strtotime($_POST['date']));
	$days='';
	if ($month == 2) { // February
		if (($year % 4 == 0 && $year % 100 != 0) || $year % 400 == 0) { // leap year
			$lastDay = 29;
		} else {
			$lastDay = 28;
		}
	} elseif ($month == 4 || $month == 6 || $month == 9 || $month == 11) { // April, June, September, November
		$lastDay = 30;
	} else { // January, March, May, July, August, October, December
		$lastDay = 31;
	}
	
	for ($i = 1; $i <= $lastDay; $i++) {
		$days .= '<th>' . $i . '</th>';
	}
	$out='<thead>
    		<tr>
    		    <th>Sl No</th>
    		    <th>Location</th>
    		    <th>Department</th>
    		    <th>Employee Name</th>
				'.$days.'
				<th>Total Present</th>
				<th>Total Absent</th>
			</thead>
			<tbody>
	';

	$objloki=new pirates_supervisor();
	$opobjloki=$objloki->stc_get_attendance($dept, $month, $year);
	$out.=$opobjloki;

	$out.='
			</tbody>
	';
	echo $out;
}

// call consumption by supervisor
if(isset($_POST['js_search_ratings'])){
	$dept=$_POST['dept'];
	$month=date('m', strtotime($_POST['date']));
	$year=date('Y', strtotime($_POST['date']));
	$days='';
	if ($month == 2) { // February
		if (($year % 4 == 0 && $year % 100 != 0) || $year % 400 == 0) { // leap year
			$lastDay = 29;
		} else {
			$lastDay = 28;
		}
	} elseif ($month == 4 || $month == 6 || $month == 9 || $month == 11) { // April, June, September, November
		$lastDay = 30;
	} else { // January, March, May, July, August, October, December
		$lastDay = 31;
	}
	
	for ($i = 1; $i <= $lastDay; $i++) {
		$days .= '<th>' . $i . '</th>';
	}
	$out='<thead>
    		<tr>
    		    <th>Sl No</th>
    		    <th>Location</th>
    		    <th>Department</th>
    		    <th>Employee Name</th>
				'.$days.'
				<th>Total Rating</th>
				<th>Achieved Rating</th>
			</thead>
			<tbody>
	';

	$objloki=new pirates_supervisor();
	$opobjloki=$objloki->stc_get_ratings($dept, $month, $year);
	$out.=$opobjloki;

	$out.='
			</tbody>
	';
	echo $out;
}

// call std
if(isset($_POST['stc_down_list_hit'])){
	$location_id=$_POST['location_id'];
	$search=$_POST['search'];
	$status=$_POST['status'];
	$metabots=new pirates_supervisor();
	$opmetabots=$metabots->stc_call_status_down_list($location_id, $search, $status);
	echo $opmetabots;
}

// changee job done details
if(isset($_POST['stc_job_description_change_hit'])){
	$sld_id=$_POST['sdl_id'];
	$jobdonedetails=$_POST['jobdonedetails'];
	$sdl_status=new pirates_supervisor();
	$out_sdl_status=$sdl_status->stc_sdl_jobdescription_update($sld_id, $jobdonedetails);
	echo $out_sdl_status;
}

// changee status
if(isset($_POST['stc_status_change_hit'])){
	$sld_id=$_POST['sdl_id'];
	$status=$_POST['status_id'];
	$sdl_status=new pirates_supervisor();
	$out_sdl_status=$sdl_status->stc_sdl_status_update($status, $sld_id);
	echo $out_sdl_status;
}

// call req items for edit
if(isset($_POST['stc_req_edit_item_show'])){
	$req_id=$_POST['req_id'];
	$odin_req=new pirates_supervisor();
	$odin_req_out=$odin_req->stc_change_req_item_call($req_id);
	echo $odin_req_out;
}

// update item
if(isset($_POST['stc_req_edit_item_update'])){
	$req_item_id=$_POST['req_item_id'];
	$req_item_name=$_POST['req_item_name'];
	$req_item_priority=$_POST['req_item_priority'];
	$odin_req=new pirates_supervisor();
	$odin_req_out=$odin_req->stc_change_req_item_update($req_item_id, $req_item_name, $req_item_priority);
	echo $odin_req_out;
}

// send mail to new user for id password
if(isset($_POST['stc_send_mail'])){
	$user_id=$_POST['user_id'];
	$contact=$_POST['contact'];
	$password=$_POST['password'];
	$email=$_POST['email'];
	$fullname=$_POST['fullname'];
	$odin_req_out='';
	if(empty($_SESSION['stc_agent_id'])){
		$odin_req_out='reload';
	}else{
		$from="info@stcassociate.com";
		$website=$_SERVER['SERVER_NAME'];
		if(strpos($website, 'agent')!=''){
			$from="info@agent.stcassociate.com";
		}
		$headers = "MIME-Version: 1.0" . "\r\n"; 
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";  
		$headers .= 'From: '.$from;
		$maildesc= '
			<!DOCTYPE html>
			<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
			<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width,initial-scale=1">
			<meta name="x-apple-disable-message-reformatting">
			<title></title>
			<!--[if mso]>
			<style>
				table {border-collapse:collapse;border-spacing:0;border:none;margin:0;}
				div, td {padding:0;}
				div {margin:0 !important;}
			</style>
			<noscript>
				<xml>
				<o:OfficeDocumentSettings>
					<o:PixelsPerInch>96</o:PixelsPerInch>
				</o:OfficeDocumentSettings>
				</xml>
			</noscript>
			<![endif]-->
			<style>
				table, td, div, h1, p {
				font-family: Arial, sans-serif;
				}
				@media screen and (max-width: 530px) {
				.unsub {
					display: block;
					padding: 8px;
					margin-top: 14px;
					border-radius: 6px;
					background-color: #555555;
					text-decoration: none !important;
					font-weight: bold;
				}
				.col-lge {
					max-width: 100% !important;
				}
				}
				@media screen and (min-width: 531px) {
				.col-sml {
					max-width: 27% !important;
				}
				.col-lge {
					max-width: 73% !important;
				}
				}
			</style>
			</head>
			<body style="margin:0;padding:0;word-spacing:normal;background-color:#939297;">
			<div role="article" aria-roledescription="email" lang="en" style="text-size-adjust:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#939297;">
				<table role="presentation" style="width:100%;border:none;border-spacing:0;">
				<tr>
					<td align="center" style="padding:0;">
					<table role="presentation" style="width:94%;max-width:600px;border:none;border-spacing:0;text-align:left;font-family:Arial,sans-serif;font-size:16px;line-height:22px;color:#363636;">
						<tr>
						<td style="padding:40px 30px 30px 30px;text-align:center;font-size:24px;font-weight:bold;">
							<a href="http://www.example.com/" style="text-decoration:none;"><img src="http://stcassociate.com/stc_symbiote/img/stc_logo.png" width="165" alt="Logo" style="border-radius: 50%;width:80%;max-width:165px;height:auto;border:none;text-decoration:none;color:#ffffff;"></a>
						</td>
						</tr>
						<tr>
						<td style="padding:30px;background-color:#ffffff;">
							<h1 style="margin-top:0;margin-bottom:16px;font-size:26px;line-height:32px;font-weight:bold;letter-spacing:-0.02em;">Welcome to STC Associates</h1>
							<p style="margin:0;">You are registered as a Project Supervisor from your sr.Executive - '.$_SESSION['stc_agent_name'].'.
								Here is a link of your platform <a href="http://subagent.stcassociate.com/" style="color:#e50d70;text-decoration:underline;">Click me</a> wth your<br>
								User id - <b>'.$contact.'</b><br>
								Password - <b>'.$password.'</b>
							</p>
						</td>
						</tr>
						<tr>
						<td style="padding:0;font-size:24px;line-height:28px;font-weight:bold;">
							<a href="#" style="text-decoration:none;"><img src="https://library.kissclipart.com/20180904/vzw/kissclipart-supervisor-dibujo-clipart-construction-laborer-cli-c856a67bc49abb0e.jpg" width="600" alt="" style="width:100%;height:auto;display:block;border:none;text-decoration:none;color:#363636;"></a>
						</td>
						</tr>
						<tr>
							<td style="padding:30px;text-align:center;font-size:12px;background-color:#404040;color:#cccccc;">
							<p style="margin:0;font-size:14px;line-height:20px;">&reg; STC Associates, Jamshedpur 2021<br><a class="unsub" href="#" style="color:#cccccc;text-decoration:underline;">This is System generated mail. Please do not reply.</a></p>
							</td>
						</tr>
					</table>
					<!--[if mso]>
					</td>
					</tr>
					<tr>
						<td style="padding:30px;text-align:center;font-size:12px;background-color:#404040;color:#cccccc;">
						<p style="margin:0;font-size:14px;line-height:20px;">&reg; STC Associates, Jamshedpur 2021<br><a class="unsub" href="#" style="color:#cccccc;text-decoration:underline;">This is System generated mail. Please do not reply.</a></p>
						</td>
					</tr>
					</table>
					<![endif]-->
					</td>
				</tr>
				</table>
			</div>
			</body>
			</html> 	
		';
		if($_SESSION['mail_check_sess']!=$user_id){
			mail($email, "Welcome ".$fullname, $maildesc, $headers);
			$_SESSION['mail_check_sess']=$user_id;
			$odin_req_out="Mail sent.";
		}else{
			$odin_req_out="Mail already sent please check inbox or junk mail.";
		}
		
	}
	echo json_encode($odin_req_out);
}

/*---------------------------------------------Procurement tracker Objects section-------------------------------------------------*/
// add procurmenet
if(isset($_POST['stc-pro-tra-procurement-hit'])){
	$pro_id=$_POST['stc-pro-tra-pro-id'];
	$item_name=$_POST['stc-pro-tra-item-name'];
	$service=$_POST['stc-pro-tra-service'];
	$unit=$_POST['stc-pro-tra-unit'];
	$quantity=$_POST['stc-pro-tra-quantity'];
	$out="";
	if(empty($item_name) || ($service=="NA") || ($pro_id=="NA") || empty($quantity)){
		$out="empty";
	}elseif(empty($_SESSION['stc_agent_id'])){
		$out="logout";
	}else{
		$odin_req=new pirates_project();
		$odin_req_out=$odin_req->stc_save_procurement_create($pro_id, $item_name, $service, $unit, $quantity);
		$out=$odin_req_out;
	}
	echo $out;
}

// call procurment tracker
if(isset($_POST['get_procurment_tracker'])){
	$by_location=$_POST['by_location'];
	$by_maker=$_POST['by_maker'];
	$by_item=$_POST['by_item'];

	$odin_req=new pirates_project();
	$odin_req_out=$odin_req->stc_save_procurement_call($by_location, $by_maker, $by_item);
	echo $odin_req_out;
}

// delete procurment
if(isset($_POST['delete_procurment_tracker'])){
	$proc_id=$_POST['delete_pro_id'];
	$odin_req=new pirates_project();
	$odin_req_out=$odin_req->stc_save_procurement_delete($proc_id);
	echo $odin_req_out;
}

// call perticular procurement
if(isset($_POST['get_procurment_tracker_perticular'])){
	$proc_id=$_POST['add_pro_id'];
	$odin_req=new pirates_project();
	$odin_req_out=$odin_req->stc_procurement_tracker_perticular_call($proc_id);
	echo json_encode($odin_req_out);
}

// update procurement
if(isset($_POST['update_procurment_tracker'])){
	$proc_id=$_POST['pro_id'];
	$po_qnty=$_POST['po_qnty'];
	$buyer=$_POST['buyer'];
	$po_no_id=$_POST['po_no_id'];
	$po_no_date=$_POST['po_no_date'];
	$amount=$_POST['amount'];
	$gst=$_POST['gst'];
	$approval=$_POST['approval'];
	$mfgclear=$_POST['mfgclear'];
	$leadtime=$_POST['leadtime'];
	$dealer_loca=$_POST['dealer_loca'];
	$transittime=$_POST['transittime'];
	$plan=$_POST['plan'];
	$actual=$_POST['actual'];
	$transport_charge=$_POST['transport_charge'];
	$mreadiness=$_POST['mreadiness'];
	$remarks=$_POST['remarks'];

	$odin_req=new pirates_project();
	$odin_req_out=$odin_req->stc_procurement_tracker_update($proc_id, $po_qnty, $buyer, $po_no_id, $po_no_date, $amount, $gst, $approval, $mfgclear, $leadtime, $dealer_loca, $transittime, $plan, $actual, $transport_charge, $mreadiness, $remarks);
	echo $odin_req_out;
}

// save procurment tracker payment
if(isset($_POST['save_procurment_tracker_payment'])){
	$proc_id=$_POST['proc_id'];
	$pay_date=$_POST['pay_date'];
	$pay_type=$_POST['pay_type'];
	$pay_amount=$_POST['pay_amount'];
	$out='';

	if(empty($pay_amount) || empty($pay_date)){
		$out='empty';
	}elseif(empty($_SESSION['stc_agent_id'])){
		$out='reload';
	}else{
		$odin_req=new pirates_project();
		$odin_req_out=$odin_req->stc_procurement_tracker_payment_save($proc_id ,$pay_date ,$pay_type ,$pay_amount);
		$out=$odin_req_out;
	}
	echo $out;
}

// save procurment tracker receiving
if(isset($_POST['save_procurment_tracker_receiving'])){
	$proc_id=$_POST['proc_id'];
	$rec_quantity=$_POST['rec_quantity'];
	$rec_storein=$_POST['rec_storein'];
	$out='';

	if(empty($rec_quantity) || empty($rec_storein)){
		$out='empty';
	}elseif(empty($_SESSION['stc_agent_id'])){
		$out='reload';
	}else{
		$odin_req=new pirates_project();
		$odin_req_out=$odin_req->stc_procurement_tracker_receiving_save($proc_id ,$rec_quantity ,$rec_storein);
		$out=$odin_req_out;
	}
	echo $out;
}

// save procurment tracker dispatch
if(isset($_POST['save_procurment_tracker_dispatch'])){
	$proc_id=$_POST['proc_id'];
	$dec_quantity=$_POST['dec_quantity'];
	$des_challanno=$_POST['des_challanno'];
	$out='';

	if(empty($dec_quantity) || empty($des_challanno)){
		$out='empty';
	}elseif(empty($_SESSION['stc_agent_id'])){
		$out='reload';
	}else{
		$odin_req=new pirates_project();
		$odin_req_out=$odin_req->stc_procurement_tracker_despatch_save($proc_id ,$dec_quantity ,$des_challanno);
		$out=$odin_req_out;
	}
	echo $out;
}

// save procurment tracker payment date
if(isset($_POST['save_procurment_tracker_payment_update'])){
	$pay_id=$_POST['pay_id'];
	$out='';
	if(empty($_SESSION['stc_agent_id'])){
		$out='reload';
	}else{
		$odin_req=new pirates_project();
		$odin_req_out=$odin_req->stc_procurement_tracker_payment_update($pay_id);
		$out=$odin_req_out;
	}
	echo $out;
}

// call collaborate user
if(isset($_POST['stc_collaborate_user_call'])){
	$user_id=$_POST['user_id'];

	$objcrproj=new pirates_supervisor();
	$opobjcrproj='';
	if(empty($_SESSION['stc_agent_id'])){
		$opobjcrproj = 'Login';
	}else{
		$opobjcrproj=$objcrproj->stc_user_collaborated($user_id);
	}
	// echo $opobjcrproj;
	echo json_encode($opobjcrproj);
}

// call collaborate user
if(isset($_POST['stc_collaborate_user_save'])){
	$user_id=$_POST['user_id'];
	$email=$_POST['email'];

	$objcrproj=new pirates_supervisor();
	$opobjcrproj='';
	if(empty($_SESSION['stc_agent_id'])){
		$opobjcrproj = 'empty';
	}else{
		$opobjcrproj=$objcrproj->stc_user_collaborate_save($user_id, $email);
	}
	// echo $opobjcrproj;
	echo json_encode($opobjcrproj);
}

// save procurment tracker payment
if(isset($_POST['save_item_tracker'])){
	$user_id=$_POST['user_id'];
	$ppe_type=$_POST['ppe_type'];
	$qty=$_POST['qty'];
	$unit=$_POST['unit'];
	$issue_date=$_POST['issue_date'];
	$validity=$_POST['validity'];
	$remarks=$_POST['remarks'];
	$out='';

	if($user_id=="NA" || $ppe_type=="NA" || empty($qty)){
		$out='empty';
	}elseif(empty($_SESSION['stc_agent_id'])){
		$out='reload';
	}else{
		$odin_req=new pirates_project();
		$odin_req_out=$odin_req->stc_item_tracker_save($user_id, $ppe_type, $qty, $unit, $issue_date, $validity, $remarks);
		$out=$odin_req_out;
	}
	echo $out;
}

// call procurment tracker
if(isset($_POST['call_item_tracker'])){
	$odin_req=new pirates_project();
	$odin_req_out=$odin_req->stc_item_tracker_call();
	echo $odin_req_out;
}
?>