<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*------------------------------------------For Safety--------------------------------------------*/
/*------------------------------------------------------------------------------------------------*/

class witcher_supervisor extends tesseract{
	// call tbm
	public function stc_call_tbm(){
		$optimusprime='';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_safetytbm` 
			LEFT JOIN `stc_cust_pro_supervisor`
			ON `stc_cust_pro_supervisor_id`=`stc_safetytbm_created_by`
			WHERE `stc_safetytbm_created_by`='".$_SESSION['stc_agent_sub_id']."' 
			ORDER BY DATE(`stc_safetytbm_date`) DESC
		");
		if(mysqli_num_rows($optimusprimequery)>0){
			foreach($optimusprimequery as $optimusprimerow){
				$optimusprimeimgqry=mysqli_query($this->stc_dbs, "
					SELECT `stc_safetytbm_img_location` FROM `stc_safetytbm_img` WHERE `stc_safetytbm_img_tbmid`='".$optimusprimerow['stc_safetytbm_id']."'
				");
				$img_path='';
				foreach($optimusprimeimgqry as $optimusprimeimgrow){
					$img_path=$optimusprimeimgrow['stc_safetytbm_img_location'];
				}
				// <img src="safety_img/'.$img_path.'" style="width: 190px;position: relative;left: 15%;padding: 0;margin: 0;">

				$safety_image='
						<a href="javascript:void(0)" class="btn btn-info stc-safety-tbm-image-show-btn" data-src="safety_img/'.$img_path.'">View</a>
				';
				if($img_path==""){
					$safety_image="
						<form action='#' id='safety-image-upload-form' >
							<input type='file' name='stc-safety-image-path'>
							<input type='hidden' name='stc-safety-tbm-id' value='".$optimusprimerow['stc_safetytbm_id']."'>
							<input type='submit' value='Upload' class='btn btn-success'>
						</form>";
				}

				$action_show='
					<a href="../stc_agent47/safety-tbm-print-preview.php?tbm_no='.$optimusprimerow['stc_safetytbm_id'].'" class="form-control btn btn-success" >View</a>
					<a href="#" class="form-control btn btn-secondary stc-safetytbm-edit" id="'.$optimusprimerow['stc_safetytbm_id'].'">Edit</a>
					<a href="#" class="form-control btn btn-danger stc-safetytbm-delete" id="'.$optimusprimerow['stc_safetytbm_id'].'">Delete</a>
				';

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

	public function stc_hit_tbm_call_no(){
		$optimusprime='';
		$date=date("Y-m-d");
		$optimusprimechecquery=mysqli_query($this->stc_dbs, "
			SELECT `stc_safetytbm_date` FROM `stc_safetytbm` WHERE `stc_safetytbm_date`='".$date."' AND `stc_safetytbm_created_by`='".$_SESSION['stc_agent_sub_id']."' ORDER BY `stc_safetytbm_id` DESC LIMIT 0,1
		");
		$previousentrydate='';
		foreach($optimusprimechecquery as $optimusprimechecrow){
			$previousentrydate=$optimusprimechecrow['stc_safetytbm_date'];
		}
		if(abs($previousentrydate==$date)){
			$optimusprime='same';
		}else{
			$optimusprimequery=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_safetytbm`(
					`stc_safetytbm_date`, 
					`stc_safetytbm_created_by`
				) VALUES (
					'".$date."',
					'".$_SESSION['stc_agent_sub_id']."'
				)
			");
			if($optimusprimequery){
				$optimusprimequery=mysqli_query($this->stc_dbs, "
					SELECT `stc_safetytbm_id` FROM `stc_safetytbm` WHERE `stc_safetytbm_created_by`='".$_SESSION['stc_agent_sub_id']."' ORDER BY `stc_safetytbm_id` DESC LIMIT 0,1
				");
				foreach($optimusprimequery as $optimusprimerow){
					$optimusprime=$optimusprimerow['stc_safetytbm_id'];
				}
			}else{
				$optimusprime="not success";
			}
		}
		return $optimusprime;
	}

	// delete tbm
	public function stc_delete_tbm($tbm_id){
		$optimusprime='';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			DELETE FROM `stc_safetytbm` WHERE `stc_safetytbm_id`='".mysqli_real_escape_string($this->stc_dbs, $tbm_id)."'
		");
		if($optimusprimequery){
			$optimusprimequery=mysqli_query($this->stc_dbs, "
				DELETE FROM `stc_safetytbm_gateentry` WHERE `stc_safetytbm_gateentry_tbmid`='".mysqli_real_escape_string($this->stc_dbs, $tbm_id)."'
			");
			$optimusprimequery=mysqli_query($this->stc_dbs, "
				DELETE FROM `stc_safetytbm_responsibilities` WHERE `stc_safetytbm_responsibilities_tbmid`='".mysqli_real_escape_string($this->stc_dbs, $tbm_id)."'
			");
			$optimusprimequery=mysqli_query($this->stc_dbs, "
				DELETE FROM `stc_safetytbm_img` WHERE `stc_safetytbm_img_tbmid`='".mysqli_real_escape_string($this->stc_dbs, $tbm_id)."'
			");
			$optimusprimequery=mysqli_query($this->stc_dbs, "
				DELETE FROM `stc_safetytbm_dailyfitppe_checklist` WHERE `stc_safetytbm_checklist_tbmid`='".mysqli_real_escape_string($this->stc_dbs, $tbm_id)."'
			");
			$optimusprime="success";
		}else{
			$optimusprime="not success";
		}
		return $optimusprime;
	}

	// call tbm with feilds
	public function stc_call_tbm_fields($stc_tbm_no){
		$optimusprime=array();
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_safetytbm` WHERE `stc_safetytbm_id`='".$stc_tbm_no."' ORDER BY `stc_safetytbm_id` DESC
		");
		foreach($optimusprimequery as $optimusprimerow){
			$optimusprime['tbm']=$optimusprimerow;
		}

		$optimusprimegateentryquery=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_safetytbm_gateentry` WHERE `stc_safetytbm_gateentry_tbmid`='".$stc_tbm_no."' ORDER BY `stc_safetytbm_gateentry_tbmid` DESC
		");
		foreach($optimusprimegateentryquery as $optimusprimegateentryqueryrow){
			$optimusprime['tbm_gateentry'][]=$optimusprimegateentryqueryrow;
		}

		$optimusprimeresponsibilityquery=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_safetytbm_responsibilities` WHERE `stc_safetytbm_responsibilities_tbmid`='".$stc_tbm_no."' ORDER BY `stc_safetytbm_responsibilities_tbmid` DESC
		");
		foreach($optimusprimeresponsibilityquery as $optimusprimeresponsibilityqueryrow){
			$optimusprime['tbm_responsibility'][]=$optimusprimeresponsibilityqueryrow;
		}

		$optimusprimeppechecklistquery=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_safetytbm_dailyfitppe_checklist` WHERE `stc_safetytbm_checklist_tbmid`='".$stc_tbm_no."' ORDER BY `stc_safetytbm_checklist_tbmid` DESC
		");
		foreach($optimusprimeppechecklistquery as $optimusprimeppechecklistrow){
			$optimusprime['tbm_ppe_checklist'][]=$optimusprimeppechecklistrow;
		}
		return $optimusprime;
	}

	// update tbm
	public function stc_update_tbm($stc_tbm_no, $stc_date, $stc_time, $stc_place, $stc_agendaofmeeting, $stc_pointtone, $stc_pointtwo, $stc_pointthree, $stc_pointfour, $stc_pointfive, $stc_pointsix, $stc_suggesionsio, $stc_entryname, $stc_desgination, $stc_gatepass){
		$optimusprime='';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			UPDATE
			    `stc_safetytbm`
			SET
			    `stc_safetytbm_date` 			= '".mysqli_real_escape_string($this->stc_dbs, $stc_date)."',
			    `stc_safetytbm_time` 			= '".mysqli_real_escape_string($this->stc_dbs, $stc_time)."',
			    `stc_safetytbm_place` 			= '".mysqli_real_escape_string($this->stc_dbs, $stc_place)."',
			    `stc_safetytbm_agendaofmeet` 	= '".mysqli_real_escape_string($this->stc_dbs, $stc_agendaofmeeting)."',
			    `stc_safetytbm_ptone` 			= '".mysqli_real_escape_string($this->stc_dbs, $stc_pointtone)."',
			    `stc_safetytbm_pttwo` 			= '".mysqli_real_escape_string($this->stc_dbs, $stc_pointtwo)."',
			    `stc_safetytbm_ptthree` 		= '".mysqli_real_escape_string($this->stc_dbs, $stc_pointthree)."',
			    `stc_safetytbm_ptfour` 			= '".mysqli_real_escape_string($this->stc_dbs, $stc_pointfour)."',
			    `stc_safetytbm_ptfive` 			= '".mysqli_real_escape_string($this->stc_dbs, $stc_pointfive)."',
			    `stc_safetytbm_ptsix` 			= '".mysqli_real_escape_string($this->stc_dbs, $stc_pointsix)."',
			    `stc_safetytbm_entry_name` 		= '".mysqli_real_escape_string($this->stc_dbs, $stc_entryname)."',
			    `stc_safetytbm_designation` 	= '".mysqli_real_escape_string($this->stc_dbs, $stc_desgination)."',
			    `stc_safetytbm_gatepass_no` 	= '".mysqli_real_escape_string($this->stc_dbs, $stc_gatepass)."',
			    `stc_safetytbm_remarks` 		= '".mysqli_real_escape_string($this->stc_dbs, $stc_suggesionsio)."'
			WHERE
			    `stc_safetytbm_id`='".mysqli_real_escape_string($this->stc_dbs, $stc_tbm_no)."'
		");
		if($optimusprimequery){
			$optimusprime="success";
		}else{
			$optimusprime="not success";
		}
		return $optimusprime;
	}

	// tbm gate entry save
	public function stc_save_tbm_gateentry($stc_tbm_no, $stc_work_permit_no, $stc_shift, $stc_time, $stc_supervisor_name){
		$optimusprime='';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_safetytbm_gateentry`(
				`stc_safetytbm_gateentry_tbmid`,
			    `stc_safetytbm_gateentry_workpermitno`,
			    `stc_safetytbm_gateentry_shift`,
			    `stc_safetytbm_gateentry_time`,
			    `stc_safetytbm_gateentry_supeng_name`
			) VALUES (
				'".mysqli_real_escape_string($this->stc_dbs, $stc_tbm_no)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_work_permit_no)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_shift)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_time)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_supervisor_name)."'
			)
		");
		if($optimusprimequery){
			$optimusprime="success";
		}else{
			$optimusprime="not success";
		}
		return $optimusprime;
	}

	// save responsibility
	public function stc_save_tbm_responsibility($stc_tbm_no, $stc_res_date, $stc_res_responsibility, $stc_res_item){
		$optimusprime='';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_safetytbm_responsibilities`(
			    `stc_safetytbm_responsibilities_tbmid`,
			    `stc_safetytbm_responsibilities_item`,
			    `stc_safetytbm_responsibilities_responsibilities`,
			    `stc_safetytbm_responsibilities_targetdate`
			) VALUES (
				'".mysqli_real_escape_string($this->stc_dbs, $stc_tbm_no)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_res_item)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_res_responsibility)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_res_date)."'
			)
		");
		if($optimusprimequery){
			$optimusprime="success";
		}else{
			$optimusprime="not success";
		}
		return $optimusprime;
	}

	// save image
	public function stc_save_tbm_image($tbm_id, $stcsafetyimages){
		$optimusprime='';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_safetytbm_img`(
			    `stc_safetytbm_img_tbmid`,
			    `stc_safetytbm_img_location`
			) VALUES (
				'".mysqli_real_escape_string($this->stc_dbs, $tbm_id)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stcsafetyimages)."'
			)
		");
		if($optimusprimequery){
			$optimusprime="success";
		}else{
			$optimusprime="not success";
		}
		return $optimusprime;
	}

	// save ppe checklist
	public function stc_save_tbm_ppe_checklist($stc_tbm_no, $stc_emp_name, $stc_filter){
		$optimusprime='';
		$insertqry='';
		$insertval='';
		foreach($stc_filter as $key => $values){
			if($values == "Hardhat"){
				$insertqry .='`stc_safetytbm_checklist_hardhat`,';
				$insertval .='1,';
			}
			if($values == "Safety Goggle"){
				$insertqry .='`stc_safetytbm_checklist_SafetyGoggle`,';
				$insertval .='1,';
			}
			if($values == "Nose Mask"){
				$insertqry .='`stc_safetytbm_checklist_NoseMask`,';
				$insertval .='1,';
			}
			if($values == "Hand Gloves"){
				$insertqry .='`stc_safetytbm_checklist_HandGloves`,';
				$insertval .='1,';
			}
			if($values == "FR-Jacket/Trouser"){
				$insertqry .='`stc_safetytbm_checklist_FR_Jacket_Trouser`,';
				$insertval .='1,';
			}
			if($values == "Safety Shoes"){
				$insertqry .='`stc_safetytbm_checklist_SafetyShoes`,';
				$insertval .='1,';
			}
			if($values == "Earplug"){
				$insertqry .='`stc_safetytbm_checklist_earplug`,';
				$insertval .='1,';
			}
			if($values == "Leg Guard"){
				$insertqry .='`stc_safetytbm_checklist_legguard`,';
				$insertval .='1,';
			}
			if($values == "Physically fit for duty"){
				$insertqry .='`stc_safetytbm_checklist_PhysicallyfitforDuty`,';
				$insertval .='1,';
			}
		}
		$optimusprime_qry="
			INSERT INTO `stc_safetytbm_dailyfitppe_checklist`(
				`stc_safetytbm_checklist_tbmid`,
			    `stc_safetytbm_checklist_empname`,
			    ".$insertqry."
			    `stc_safetytbm_checklist_created_by`
			) VALUES (
				'".mysqli_real_escape_string($this->stc_dbs, $stc_tbm_no)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_emp_name)."',
				".$insertval."
				'".$_SESSION['stc_agent_sub_id']."'
			)
		";
		$optimusprime_res=mysqli_query($this->stc_dbs, $optimusprime_qry);
		if($optimusprime_res){
			$optimusprime="success";
		}else{
			$optimusprime="not success";
		}
		return $optimusprime;
	}

}

class witcher_vhl extends tesseract{
	// calll created vhl no
	public function stc_hit_vhl_call_no(){
		$optimusprime='';
		$date=date("Y-m-d");
		$optimusprimechecquery=mysqli_query($this->stc_dbs, "
			SELECT `stc_safetyvehicle_date` FROM `stc_safetyvehicle` WHERE `stc_safetyvehicle_date`='".$date."' AND `stc_safetyvehicle_createdby`='".$_SESSION['stc_agent_sub_id']."' ORDER BY `stc_safetyvehicle_id` DESC LIMIT 0,1
		");
		$previousentrydate='';
		foreach($optimusprimechecquery as $optimusprimechecrow){
			$previousentrydate=$optimusprimechecrow['stc_safetyvehicle_date'];
		}
		if(abs($previousentrydate==$date)){
			$optimusprime='same';
		}else{
			$optimusprimequery=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_safetyvehicle`(
					`stc_safetyvehicle_date`, 
					`stc_safetyvehicle_createdby`
				) VALUES (
					'".$date."',
					'".$_SESSION['stc_agent_sub_id']."'
				)
			");
			if($optimusprimequery){
				$optimusprimequery=mysqli_query($this->stc_dbs, "
					SELECT `stc_safetyvehicle_id` FROM `stc_safetyvehicle` WHERE `stc_safetyvehicle_createdby`='".$_SESSION['stc_agent_sub_id']."' ORDER BY `stc_safetyvehicle_id` DESC LIMIT 0,1
				");
				foreach($optimusprimequery as $optimusprimerow){
					$optimusprime=$optimusprimerow['stc_safetyvehicle_id'];
				}
			}else{
				$optimusprime="not success";
			}
		}
		return $optimusprime;
	}

	// call vhl
	public function stc_call_vhl(){
		$optimusprime='';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_safetyvehicle` 
			LEFT JOIN `stc_cust_pro_supervisor`
			ON `stc_cust_pro_supervisor_id`=`stc_safetyvehicle_createdby`
			WHERE `stc_safetyvehicle_createdby`='".$_SESSION['stc_agent_sub_id']."' 
			ORDER BY DATE(`stc_safetyvehicle_date`) DESC
		");
		if(mysqli_num_rows($optimusprimequery)>0){
			foreach($optimusprimequery as $optimusprimerow){
				$action_show='
					<a href="../stc_agent47/safety-tbm-print-preview.php?tbm_no='.$optimusprimerow['stc_safetyvehicle_id'].'" class="form-control btn btn-success" >View</a>
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

	// delete vhl
	public function stc_delete_vhl($vhl_id){
		$optimusprime='';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			DELETE FROM `stc_safetyvehicle` WHERE `stc_safetyvehicle_id`='".mysqli_real_escape_string($this->stc_dbs, $vhl_id)."'
		");
		if($optimusprimequery){
			$optimusprime="success";
		}else{
			$optimusprime="not success";
		}
		return $optimusprime;
	}

	// call tbm with feilds
	public function stc_call_vhl_fields($stc_vhl_no){
		$optimusprime='';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_safetyvehicle` WHERE `stc_safetyvehicle_id`='".$stc_vhl_no."' ORDER BY `stc_safetyvehicle_id` DESC
		");
		foreach($optimusprimequery as $optimusprimerow){
			$optimusprime=$optimusprimerow;
		}
		return $optimusprime;
	}

	// call vhl
	public function stc_update_vhl($stc_vhl_no, $stc_description, $stc_reg_name, $stc_dateofinspection, $stc_driversname, $stc_undertaking_vehic, $stc_signature, $stc_faultsreported, $stc_ol, $stc_bfl, $stc_wl, $stc_ws, $stc_as, $stc_sb, $stc_pb, $stc_fb, $stc_pb1, $stc_cgs, $stc_mc, $stc_dl, $stc_stg, $stc_lc, $stc_dashp, $stc_horn, $stc_alarm, $stc_hyds, $stc_spart, $stc_towbar, $stc_equip, $stc_fk, $stc_other){		
		$optimusprime='';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			UPDATE
			    `stc_safetyvehicle`
			SET
			    `stc_safetyvehicle_desc`= '".mysqli_real_escape_string($this->stc_dbs, $stc_description)."',
			    `stc_safetyvehicle_reg_no`= '".mysqli_real_escape_string($this->stc_dbs, $stc_reg_name)."',
			    `stc_safetyvehicle_dateofinspection`= '".mysqli_real_escape_string($this->stc_dbs, $stc_dateofinspection)."',
			    `stc_safetyvehicle_driversname`= '".mysqli_real_escape_string($this->stc_dbs, $stc_driversname)."',
			    `stc_safetyvehicle_personundertaking`= '".mysqli_real_escape_string($this->stc_dbs, $stc_undertaking_vehic)."',
			    `stc_safetyvehicle_signature`= '".mysqli_real_escape_string($this->stc_dbs, $stc_signature)."',
			    `stc_safetyvehicle_faultsreported`= '".mysqli_real_escape_string($this->stc_dbs, $stc_faultsreported)."',
			    `stc_safetyvehicle_oil_level`= '".mysqli_real_escape_string($this->stc_dbs, $stc_ol)."',
			    `stc_safetyvehicle_brakefluidlevel`= '".mysqli_real_escape_string($this->stc_dbs, $stc_bfl)."',
				`stc_safetyvehicle_waterlevel`= '".mysqli_real_escape_string($this->stc_dbs, $stc_wl)."',
				`stc_safetyvehicle_windscreen`= '".mysqli_real_escape_string($this->stc_dbs, $stc_ws)."',
				`stc_safetyvehicle_adjustseat`= '".mysqli_real_escape_string($this->stc_dbs, $stc_as)."',
				`stc_safetyvehicle_seatbelts`= '".mysqli_real_escape_string($this->stc_dbs, $stc_sb)."',
				`stc_safetyvehicle_parking_brake`= '".mysqli_real_escape_string($this->stc_dbs, $stc_pb)."',
				`stc_safetyvehicle_footbrake`= '".mysqli_real_escape_string($this->stc_dbs, $stc_fb)."',
				`stc_safetyvehicle_passengerbrake`= '".mysqli_real_escape_string($this->stc_dbs, $stc_pb1)."',
				`stc_safetyvehicle_clutchgearshift`= '".mysqli_real_escape_string($this->stc_dbs, $stc_cgs)."',
				`stc_safetyvehicle_mirrorsclean`= '".mysqli_real_escape_string($this->stc_dbs, $stc_mc)."',
				`stc_safetyvehicle_doorlock`= '".mysqli_real_escape_string($this->stc_dbs, $stc_dl)."',
				`stc_safetyvehicle_steering`= '".mysqli_real_escape_string($this->stc_dbs, $stc_stg)."',
				`stc_safetyvehicle_lightsclearance`= '".mysqli_real_escape_string($this->stc_dbs, $stc_lc)."',
				`stc_safetyvehicle_dashcontrolpanel`= '".mysqli_real_escape_string($this->stc_dbs, $stc_dashp)."',
				`stc_safetyvehicle_horn`= '".mysqli_real_escape_string($this->stc_dbs, $stc_horn)."',
				`stc_safetyvehicle_alarm`= '".mysqli_real_escape_string($this->stc_dbs, $stc_alarm)."',
				`stc_safetyvehicle_hydraulicsystem`= '".mysqli_real_escape_string($this->stc_dbs, $stc_hyds)."',
				`stc_safetyvehicle_sparetyre`= '".mysqli_real_escape_string($this->stc_dbs, $stc_spart)."',
				`stc_safetyvehicle_towbar`= '".mysqli_real_escape_string($this->stc_dbs, $stc_towbar)."',
				`stc_safetyvehicle_equipment`= '".mysqli_real_escape_string($this->stc_dbs, $stc_equip)."',
				`stc_safetyvehicle_firstaidkit`= '".mysqli_real_escape_string($this->stc_dbs, $stc_fk)."',
				`stc_safetyvehicle_otherremakrs`= '".mysqli_real_escape_string($this->stc_dbs, $stc_other)."'
			WHERE
			    `stc_safetyvehicle_id`='".mysqli_real_escape_string($this->stc_dbs, $stc_vhl_no)."'
		");
		if($optimusprimequery){
			$optimusprime="success";
		}else{
			$optimusprime="not success";
		}
		return $optimusprime;
	}
}

class witcher_ppem extends tesseract{
	// calll created ppem no
	public function stc_hit_ppem_call_no(){
		$optimusprime='';
		$date=date("Y-m-d");
		$optimusprimechecquery=mysqli_query($this->stc_dbs, "
			SELECT `stc_safetyppem_date` FROM `stc_safetyppem` WHERE `stc_safetyppem_date`='".$date."' AND `stc_safetyppem_createdby`='".$_SESSION['stc_agent_sub_id']."' ORDER BY `stc_safetyppem_id` DESC LIMIT 0,1
		");
		$previousentrydate='';
		foreach($optimusprimechecquery as $optimusprimechecrow){
			$previousentrydate=$optimusprimechecrow['stc_safetyppem_date'];
		}
		if(abs($previousentrydate==$date)){
			$optimusprime='same';
		}else{
			$optimusprimequery=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_safetyppem`(
					`stc_safetyppem_date`, 
					`stc_safetyppem_createdby`
				) VALUES (
					'".$date."',
					'".$_SESSION['stc_agent_sub_id']."'
				)
			");
			if($optimusprimequery){
				$optimusprimequery=mysqli_query($this->stc_dbs, "
					SELECT `stc_safetyppem_id` FROM `stc_safetyppemstc_safetyvehicle` WHERE `stc_safetyppem_createdby`='".$_SESSION['stc_agent_sub_id']."' ORDER BY `stc_safetyppem_id` DESC LIMIT 0,1
				");
				foreach($optimusprimequery as $optimusprimerow){
					$optimusprime=$optimusprimerow['stc_safetyppem_id'];
				}
			}else{
				$optimusprime="not success";
			}
		}
		return $optimusprime;
	}

	// call ppem
	public function stc_call_ppem(){
		$optimusprime='';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_safetyppem` 
			LEFT JOIN `stc_cust_pro_supervisor`
			ON `stc_cust_pro_supervisor_id`=`stc_safetyppem_createdby`
			WHERE `stc_safetyppem_createdby`='".$_SESSION['stc_agent_sub_id']."' 
			ORDER BY DATE(`stc_safetyppem_date`) DESC
		");
		if(mysqli_num_rows($optimusprimequery)>0){
			foreach($optimusprimequery as $optimusprimerow){
				$action_show='
					<a href="../stc_agent47/safety-ppem-print-preview.php?ppem_no='.$optimusprimerow['stc_safetyppem_id'].'" class="form-control btn btn-success" >View</a>
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

	// delete ppem
	public function stc_delete_ppem($ppem_id){
		$optimusprime='';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			DELETE FROM `stc_safetyppem` WHERE `stc_safetyppem_id`='".mysqli_real_escape_string($this->stc_dbs, $ppem_id)."'
		");
		if($optimusprimequery){
			$optimusprimequery=mysqli_query($this->stc_dbs, "
				DELETE FROM `stc_safetyppem_ppes` WHERE `stc_safetyppem_ppes_ppem_id`='".mysqli_real_escape_string($this->stc_dbs, $ppem_id)."'
			");
			$optimusprime="success";
		}else{
			$optimusprime="not success";
		}
		return $optimusprime;
	}

	// call ppem
	public function stc_call_ppem_fields($stc_ppem_no){
		$optimusprime='';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_safetyppem` WHERE `stc_safetyppem_id`='".$stc_ppem_no."' ORDER BY `stc_safetyppem_id` DESC
		");
		foreach($optimusprimequery as $optimusprimerow){
			$optimusprime=$optimusprimerow;
		}
		return $optimusprime;
	}

	// call ppem
	public function stc_update_ppem($stc_ppem_no, $stc_date, $stc_sitename, $stc_supervisorname){
		$optimusprime='';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			UPDATE
			    `stc_safetyppem`
			SET
			    `stc_safetyppem_date`= '".mysqli_real_escape_string($this->stc_dbs, $stc_date)."',
			    `stc_safetyppem_site_name`= '".mysqli_real_escape_string($this->stc_dbs, $stc_sitename)."',
			    `stc_safetyppem_supervisor_name`= '".mysqli_real_escape_string($this->stc_dbs, $stc_supervisorname)."'
			WHERE
			    `stc_safetyppem_id`='".mysqli_real_escape_string($this->stc_dbs, $stc_ppem_no)."'
		");
		if($optimusprimequery){
			$optimusprime="success";
		}else{
			$optimusprime="not success";
		}
		return $optimusprime;
	}

	// save ppem
	public function stc_save_ppem_ppe($stc_ppem_no, $stc_sitentry, $stc_filter){
		$optimusprime='';
		$insertqry='';
		$insertval='';
		foreach($stc_filter as $key => $values){
			if($values == "Helmet"){
				$insertqry .='`stc_safetyppem_ppes_helmet`,';
				$insertval .='1,';
			}
			if($values == "Shoe"){
				$insertqry .='`stc_safetyppem_ppes_shoes`,';
				$insertval .='1,';
			}
			if($values == "HV jacket"){
				$insertqry .='`stc_safetyppem_ppes_hvjacket`,';
				$insertval .='1,';
			}
			if($values == "safety googles"){
				$insertqry .='`stc_safetyppem_ppes_safetygoggles`,';
				$insertval .='1,';
			}
			if($values == "F R Jacket"){
				$insertqry .='`stc_safetyppem_ppes_frjacket`,';
				$insertval .='1,';
			}
			if($values == "Ear plug"){
				$insertqry .='`stc_safetyppem_ppes_earplug`,';
				$insertval .='1,';
			}
			if($values == "Cotten gloves"){
				$insertqry .='`stc_safetyppem_ppes_cottongloves`,';
				$insertval .='1,';
			}
			if($values == "Mask"){
				$insertqry .='`stc_safetyppem_ppes_nosemask`,';
				$insertval .='1,';
			}
			if($values == "Face Shield 1"){
				$insertqry .='`stc_safetyppem_ppes_faceshieldcovid19`,';
				$insertval .='1,';
			}
			if($values == "Apron"){
				$insertqry .='`stc_safetyppem_ppes_apron`,';
				$insertval .='1,';
			}
			if($values == "Face Shield"){
				$insertqry .='`stc_safetyppem_ppes_faceshield`,';
				$insertval .='1,';
			}
			if($values == "Cutting Gooles"){
				$insertqry .='`stc_safetyppem_ppes_cuttinggoogles`,';
				$insertval .='1,';
			}
			if($values == "Leather Gloves"){
				$insertqry .='`stc_safetyppem_ppes_leathergloves`,';
				$insertval .='1,';
			}
			if($values == "Leg Guard"){
				$insertqry .='`stc_safetyppem_ppes_legguard`,';
				$insertval .='1,';
			}
			if($values == "Hand Sleeve"){
				$insertqry .='`stc_safetyppem_ppes_handsleeve`,';
				$insertval .='1,';
			}
			if($values == "Safety Harness"){
				$insertqry .='`stc_safetyppem_ppes_safetyharness`,';
				$insertval .='1,';
			}
			if($values == "Rubber Gloves"){
				$insertqry .='`stc_safetyppem_ppes_rubbergloves`,';
				$insertval .='1,';
			}
			if($values == "Lubrication Gloves"){
				$insertqry .='`stc_safetyppem_ppes_lubricationgloves`,';
				$insertval .='1,';
			}
			if($values == "Ear Muff"){
				$insertqry .='`stc_safetyppem_ppes_earmuff`,';
				$insertval .='1,';
			}
			if($values == "Sin Guard"){
				$insertqry .='`stc_safetyppem_ppes_singuard`,';
				$insertval .='1,';
			}
		}
		$optimusprime_qry="
			INSERT INTO `stc_safetyppem_ppes`(
				`stc_safetyppem_ppes_ppem_id`,
			    `stc_safetyppem_ppes_siteentry`,
			    ".$insertqry."
			    `stc_safetyppem_ppes_created_by`
			) VALUES (
				'".mysqli_real_escape_string($this->stc_dbs, $stc_ppem_no)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_sitentry)."',
				".$insertval."
				'".$_SESSION['stc_agent_sub_id']."'
			)
		";
		$optimusprime_res=mysqli_query($this->stc_dbs, $optimusprime_qry);
		if($optimusprime_res){
			$optimusprime="success";
		}else{
			$optimusprime="not success";
		}
		return $optimusprime;
	}
}


class witcher_nearmiss extends tesseract{
	// calll created nearmiss no
	public function stc_hit_nearmiss_call_no(){
		$optimusprime='';
		$date=date("Y-m-d");
		$optimusprimechecquery=mysqli_query($this->stc_dbs, "
			SELECT `stc_safetynearmiss_date` FROM `stc_safetynearmiss` WHERE `stc_safetynearmiss_date`='".$date."' AND `stc_safetynearmiss_createdby`='".$_SESSION['stc_agent_sub_id']."' ORDER BY `stc_safetynearmiss_id` DESC LIMIT 0,1
		");
		$previousentrydate='';
		foreach($optimusprimechecquery as $optimusprimechecrow){
			$previousentrydate=$optimusprimechecrow['stc_safetyppem_date'];
		}
		if(abs($previousentrydate==$date)){
			$optimusprime='same';
		}else{
			$optimusprimequery=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_safetynearmiss`(
					`stc_safetynearmiss_date`, 
					`stc_safetynearmiss_createdby`
				) VALUES (
					'".$date."',
					'".$_SESSION['stc_agent_sub_id']."'
				)
			");
			if($optimusprimequery){
				$optimusprimequery=mysqli_query($this->stc_dbs, "
					SELECT `stc_safetynearmiss_id` FROM `stc_safetynearmiss` WHERE `stc_safetynearmiss_createdby`='".$_SESSION['stc_agent_sub_id']."' ORDER BY `stc_safetynearmiss_id` DESC LIMIT 0,1
				");
				foreach($optimusprimequery as $optimusprimerow){
					$optimusprime=$optimusprimerow['stc_safetynearmiss_id'];
				}
			}else{
				$optimusprime="not success";
			}
		}
		return $optimusprime;
	}

	// call nearmiss
	public function stc_call_nearmiss(){
		$optimusprime='';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_safetynearmiss` 
			LEFT JOIN `stc_cust_pro_supervisor`
			ON `stc_cust_pro_supervisor_id`=`stc_safetynearmiss_createdby`
			WHERE `stc_safetynearmiss_createdby`='".$_SESSION['stc_agent_sub_id']."' 
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
							<img src="safety_img/'.$img_path.'" style="width: 190px;position: relative;padding: 0;margin: 0;">
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
				$action_show='
					<a href="../stc_agent47/safety-tbm-print-preview.php?tbm_no='.$optimusprimerow['stc_safetynearmiss_id'].'" class="form-control btn btn-success" >View</a>
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

	// delete nearmiss
	public function stc_delete_nearmiss($nearmiss_id){
		$optimusprime='';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			DELETE FROM `stc_safetynearmiss` WHERE `stc_safetynearmiss_id`='".mysqli_real_escape_string($this->stc_dbs, $nearmiss_id)."'
		");
		if($optimusprimequery){
			$optimusprime="success";
		}else{
			$optimusprime="not success";
		}
		return $optimusprime;
	}

	// call nearmiss
	public function stc_call_nearmiss_fields($stc_nearmiss_no){
		$optimusprime='';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_safetynearmiss` WHERE `stc_safetynearmiss_id`='".$stc_nearmiss_no."' ORDER BY `stc_safetynearmiss_id` DESC
		");
		foreach($optimusprimequery as $optimusprimerow){
			$optimusprime=$optimusprimerow;
		}
		return $optimusprime;
	}

	// update nearmiss
	public function stc_update_nearmiss($stc_nearmiss_no, $stc_orgunitoccur, $stc_incidentdate, $stc_incidenttime, $stc_incidentlocation, $stc_placeofincident, $stc_possibleconsq, $stc_incidenetdesc, $stc_primarycause, $stc_empname, $stc_gpno, $stc_vendor, $stc_actiontaken, $stc_currdate, $stc_namesign){
		$optimusprime='';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			UPDATE
			    `stc_safetynearmiss`
			SET
			    `stc_safetynearmiss_orgunitoccur`= '".mysqli_real_escape_string($this->stc_dbs, $stc_orgunitoccur)."',
			    `stc_safetynearmiss_incidenet_date`= '".mysqli_real_escape_string($this->stc_dbs, $stc_incidentdate)."',
			    `stc_safetynearmiss_time`= '".mysqli_real_escape_string($this->stc_dbs, $stc_incidenttime)."',
			    `stc_safetynearmiss_location`= '".mysqli_real_escape_string($this->stc_dbs, $stc_incidentlocation)."',
			    `stc_safetynearmiss_placeofincident`= '".mysqli_real_escape_string($this->stc_dbs, $stc_placeofincident)."',
			    `stc_safetynearmiss_possibleconsq`= '".mysqli_real_escape_string($this->stc_dbs, $stc_possibleconsq)."',
			    `stc_safetynearmiss_incidenetdesc`= '".mysqli_real_escape_string($this->stc_dbs, $stc_incidenetdesc)."',
			    `stc_safetynearmiss_primarycause`= '".mysqli_real_escape_string($this->stc_dbs, $stc_primarycause)."',
			    `stc_safetynearmiss_empname`= '".mysqli_real_escape_string($this->stc_dbs, $stc_empname)."',
			    `stc_safetynearmiss_gpno`= '".mysqli_real_escape_string($this->stc_dbs, $stc_gpno)."',
			    `stc_safetynearmiss_vendor`= '".mysqli_real_escape_string($this->stc_dbs, $stc_vendor)."',
			    `stc_safetynearmiss_actiontaken`= '".mysqli_real_escape_string($this->stc_dbs, $stc_actiontaken)."',
			    `stc_safetynearmiss_currdate`= '".mysqli_real_escape_string($this->stc_dbs, $stc_currdate)."',
			    `stc_safetynearmiss_namesign`= '".mysqli_real_escape_string($this->stc_dbs, $stc_namesign)."'

			WHERE
			    `stc_safetynearmiss_id`='".mysqli_real_escape_string($this->stc_dbs, $stc_nearmiss_no)."'
		");
		if($optimusprimequery){
			$optimusprime="success";
		}else{
			$optimusprime="not success";
		}
		return $optimusprime;
	}

	// save image	
	public function stc_save_nearmiss_image($nearmiss_id, $stcsafetyimages){
		$optimusprime='';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_safetynearmiss_img`(
			    `stc_safetynearmiss_img_nearmissid`,
			    `stc_safetynearmiss_img_location`
			) VALUES (
				'".mysqli_real_escape_string($this->stc_dbs, $nearmiss_id)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stcsafetyimages)."'
			)
		");
		if($optimusprimequery){
			$optimusprime="success";
		}else{
			$optimusprime="not success";
		}
		return $optimusprime;
	}
}

class witcher_hotwork extends tesseract{
	// calll created nearmiss no
	public function stc_hit_hotwork_call_no(){
		$optimusprime='';
		$date=date("Y-m-d");
		$optimusprimechecquery=mysqli_query($this->stc_dbs, "
			SELECT `stc_safetyhotwork_date` FROM `stc_safetyhotwork` WHERE `stc_safetyhotwork_date`='".$date."' AND `stc_safetyhotwork_createdby`='".$_SESSION['stc_agent_sub_id']."' ORDER BY `stc_safetyhotwork_id` DESC LIMIT 0,1
		");
		$previousentrydate='';
		foreach($optimusprimechecquery as $optimusprimechecrow){
			$previousentrydate=$optimusprimechecrow['stc_safetyhotwork_date'];
		}
		if(abs($previousentrydate==$date)){
			$optimusprime='same';
		}else{
			$optimusprimequery=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_safetyhotwork`(
					`stc_safetyhotwork_date`, 
					`stc_safetyhotwork_createdby`
				) VALUES (
					'".$date."',
					'".$_SESSION['stc_agent_sub_id']."'
				)
			");
			if($optimusprimequery){
				$optimusprimequery=mysqli_query($this->stc_dbs, "
					SELECT `stc_safetyhotwork_id` FROM `stc_safetyhotwork` WHERE `stc_safetyhotwork_createdby`='".$_SESSION['stc_agent_sub_id']."' ORDER BY `stc_safetyhotwork_id` DESC LIMIT 0,1
				");
				foreach($optimusprimequery as $optimusprimerow){
					$optimusprime=$optimusprimerow['stc_safetynearmiss_id'];
				}
			}else{
				$optimusprime="not success";
			}
		}
		return $optimusprime;
	}
	
	// call hotwork
	public function stc_call_hotwork(){
		$optimusprime='';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_safetyhotwork` 
			LEFT JOIN `stc_cust_pro_supervisor`
			ON `stc_cust_pro_supervisor_id`=`stc_safetyhotwork_createdby`
			WHERE `stc_safetyhotwork_createdby`='".$_SESSION['stc_agent_sub_id']."' 
			ORDER BY DATE(`stc_safetyhotwork_startingdate`) DESC
		");
		if(mysqli_num_rows($optimusprimequery)>0){
			foreach($optimusprimequery as $optimusprimerow){
				$action_show='
					<a href="../stc_agent47/safety-tbm-print-preview.php?tbm_no='.$optimusprimerow['stc_safetyhotwork_id'].'" class="form-control btn btn-success" >View</a>
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

	// delete hot work
	public function stc_delete_hotwork($hotwork_id){
		$optimusprime='';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			DELETE FROM `stc_safetyhotwork` WHERE `stc_safetyhotwork_id`='".mysqli_real_escape_string($this->stc_dbs, $hotwork_id)."'
		");
		if($optimusprimequery){
			$optimusprime="success";
		}else{
			$optimusprime="not success";
		}
		return $optimusprime;
	}

	// update hotwork
	public function stc_update_hotwork($stc_hotwork_no, $stc_wono, $stc_sitename, $stc_starting, $stc_make, $stc_slno, $stc_capaei, $stc_oosigwc, $stc_vacawp, $stc_rwp, $stc_tspt, $stc_cfmtec, $stc_geagde, $stc_cfmteh, $stc_eh, $stc_wmccanlc, $stc_wfr, $stc_cfeaftws, $stc_scle, $stc_ctte, $stc_wcopraipcwad, $stc_cltp, $stc_wianep, $stc_socuaptvs, $stc_recoslaecpfbutcw, $stc_aowbcbh, $stc_acwoseow, $stc_acpptpth){
		$optimusprime='';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			UPDATE
			    `stc_safetyhotwork`
			SET
			    `stc_safetyhotwork_wono`='".mysqli_real_escape_string($this->stc_dbs, $stc_wono)."',
				`stc_safetyhotwork_jobssitename`='".mysqli_real_escape_string($this->stc_dbs, $stc_sitename)."',
				`stc_safetyhotwork_startingdate`='".mysqli_real_escape_string($this->stc_dbs, $stc_starting)."',
				`stc_safetyhotwork_make`='".mysqli_real_escape_string($this->stc_dbs, $stc_make)."',
				`stc_safetyhotwork_slno`='".mysqli_real_escape_string($this->stc_dbs, $stc_slno)."',
				`stc_safetyhotwork_capaei`='".mysqli_real_escape_string($this->stc_dbs, $stc_capaei)."',
				`stc_safetyhotwork_oosigwc`='".mysqli_real_escape_string($this->stc_dbs, $stc_oosigwc)."',
				`stc_safetyhotwork_vacawp`='".mysqli_real_escape_string($this->stc_dbs, $stc_vacawp)."',
				`stc_safetyhotwork_rwp`='".mysqli_real_escape_string($this->stc_dbs, $stc_rwp)."',
				`stc_safetyhotwork_tspt`='".mysqli_real_escape_string($this->stc_dbs, $stc_tspt)."',
				`stc_safetyhotwork_cfmtec`='".mysqli_real_escape_string($this->stc_dbs, $stc_cfmtec)."',
				`stc_safetyhotwork_geagde`='".mysqli_real_escape_string($this->stc_dbs, $stc_geagde)."',
				`stc_safetyhotwork_cfmteh`='".mysqli_real_escape_string($this->stc_dbs, $stc_cfmteh)."',
				`stc_safetyhotwork_eh`='".mysqli_real_escape_string($this->stc_dbs, $stc_eh)."',
				`stc_safetyhotwork_wmccanlc`='".mysqli_real_escape_string($this->stc_dbs, $stc_wmccanlc)."',
				`stc_safetyhotwork_wfr`='".mysqli_real_escape_string($this->stc_dbs, $stc_wfr)."',
				`stc_safetyhotwork_cfeaftws`='".mysqli_real_escape_string($this->stc_dbs, $stc_cfeaftws)."',
				`stc_safetyhotwork_scle`='".mysqli_real_escape_string($this->stc_dbs, $stc_scle)."',
				`stc_safetyhotwork_ctte`='".mysqli_real_escape_string($this->stc_dbs, $stc_ctte)."',
				`stc_safetyhotwork_wcopraipcwad`='".mysqli_real_escape_string($this->stc_dbs, $stc_wcopraipcwad)."',
				`stc_safetyhotwork_cltp`='".mysqli_real_escape_string($this->stc_dbs, $stc_cltp)."',
				`stc_safetyhotwork_wianep`='".mysqli_real_escape_string($this->stc_dbs, $stc_wianep)."',
				`stc_safetyhotwork_socuaptvs`='".mysqli_real_escape_string($this->stc_dbs, $stc_socuaptvs)."',
				`stc_safetyhotwork_recoslaecpfbutcw`='".mysqli_real_escape_string($this->stc_dbs, $stc_recoslaecpfbutcw)."',
				`stc_safetyhotwork_aowbcbh`='".mysqli_real_escape_string($this->stc_dbs, $stc_aowbcbh)."',
				`stc_safetyhotwork_acwoseow`='".mysqli_real_escape_string($this->stc_dbs, $stc_acwoseow)."',
				`stc_safetyhotwork_acpptpth`='".mysqli_real_escape_string($this->stc_dbs, $stc_acpptpth)."'

			WHERE
			    `stc_safetyhotwork_id`='".mysqli_real_escape_string($this->stc_dbs, $stc_hotwork_no)."'
		");
		if($optimusprimequery){
			$optimusprime="success";
		}else{
			$optimusprime="not success";
		}
		return $optimusprime;
	}

	// call hotwork
	public function stc_call_hotwork_fields($stc_hotwork_no){
		$optimusprime='';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_safetyhotwork` WHERE `stc_safetyhotwork_id`='".$stc_hotwork_no."' ORDER BY `stc_safetyhotwork_id` DESC
		");
		foreach($optimusprimequery as $optimusprimerow){
			$optimusprime=$optimusprimerow;
		}
		return $optimusprime;
	}
}

class witcher_ppec extends tesseract{

	// calll created ppec no
	public function stc_hit_ppec_call_no(){
		$optimusprime='';
		$date=date("Y-m-d");
		$optimusprimechecquery=mysqli_query($this->stc_dbs, "
			SELECT `stc_safetyppec_date` FROM `stc_safetyppec` WHERE `stc_safetyppec_date`='".$date."' AND `stc_safetyppec_createdby`='".$_SESSION['stc_agent_sub_id']."' ORDER BY `stc_safetyppec_id` DESC LIMIT 0,1
		");
		$previousentrydate='';
		foreach($optimusprimechecquery as $optimusprimechecrow){
			$previousentrydate=$optimusprimechecrow['stc_safetyppec_date'];
		}
		if(abs($previousentrydate==$date)){
			$optimusprime='same';
		}else{
			$optimusprimequery=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_safetyppec`(
					`stc_safetyppec_date`, 
					`stc_safetyppec_createdby`
				) VALUES (
					'".$date."',
					'".$_SESSION['stc_agent_sub_id']."'
				)
			");
			if($optimusprimequery){
				$optimusprimequery=mysqli_query($this->stc_dbs, "
					SELECT `stc_safetyppec_id` FROM `stc_safetyppec` WHERE `stc_safetyppec_createdby`='".$_SESSION['stc_agent_sub_id']."' ORDER BY `stc_safetyppec_id` DESC LIMIT 0,1
				");
				foreach($optimusprimequery as $optimusprimerow){
					$optimusprime=$optimusprimerow['stc_safetyppec_id'];
				}
			}else{
				$optimusprime="not success";
			}
		}
		return $optimusprime;
	}

	// call ppec
	public function stc_call_ppec(){
		$optimusprime='';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_safetyppec` 
			LEFT JOIN `stc_cust_pro_supervisor`
			ON `stc_cust_pro_supervisor_id`=`stc_safetyppec_createdby`
			WHERE `stc_safetyppec_createdby`='".$_SESSION['stc_agent_sub_id']."' 
			ORDER BY DATE(`stc_safetyppec_date`) DESC
		");
		if(mysqli_num_rows($optimusprimequery)>0){
			foreach($optimusprimequery as $optimusprimerow){
				$action_show='
					<a href="../stc_agent47/stc-ppec-print-preview.php?ppec_no='.$optimusprimerow['stc_safetyppec_id'].'" class="form-control btn btn-success" >View</a>
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

	// delete ppec
	public function stc_delete_ppec($ppec_id){
		$optimusprime='';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			DELETE FROM `stc_safetyppec` WHERE `stc_safetyppec_id`='".mysqli_real_escape_string($this->stc_dbs, $ppec_id)."'
		");
		if($optimusprimequery){
			$optimusprimequery=mysqli_query($this->stc_dbs, "
				DELETE FROM `stc_safetyppec_ppes` WHERE `stc_safetyppec_ppes_ppec_id`='".mysqli_real_escape_string($this->stc_dbs, $ppec_id)."'
			");
			$optimusprime="success";
		}else{
			$optimusprime="not success";
		}
		return $optimusprime;
	}

	// call ppec
	public function stc_call_ppec_fields($stc_ppec_no){
		$optimusprime=array();
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_safetyppec` WHERE `stc_safetyppec_id`='".$stc_ppec_no."' ORDER BY `stc_safetyppec_id` DESC
		");
		foreach($optimusprimequery as $optimusprimerow){
			$optimusprime['ppec']=$optimusprimerow;
		}
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_safetyppec_ppes` WHERE `stc_safetyppec_ppes_ppec_id`='".$stc_ppec_no."' ORDER BY `stc_safetyppec_ppes_id` DESC
		");
		foreach($optimusprimequery as $optimusprimerow){
			$optimusprime['ppecppess'][]=$optimusprimerow;
		}
		return $optimusprime;
	}

	// update ppec
	public function stc_update_ppec($stc_ppec_no, $stc_ppec_wono, $stc_ppec_sitename, $stc_ppec_check, $stc_ppec_sitesup, $stc_ppec_safsup){
		$optimusprime='';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			UPDATE
			    `stc_safetyppec`
			SET
				`stc_safetyppec_wono`='".mysqli_real_escape_string($this->stc_dbs, $stc_ppec_wono)."',
				`stc_safetyppec_sitename`='".mysqli_real_escape_string($this->stc_dbs, $stc_ppec_sitename)."',
				`stc_safetyppec_check`='".mysqli_real_escape_string($this->stc_dbs, $stc_ppec_check)."',
				`stc_safetyppec_sitesupervisor`='".mysqli_real_escape_string($this->stc_dbs, $stc_ppec_sitesup)."',
				`stc_safetyppec_safetysupervisor`='".mysqli_real_escape_string($this->stc_dbs, $stc_ppec_safsup)."'

			WHERE
			    `stc_safetyppec_id`='".mysqli_real_escape_string($this->stc_dbs, $stc_ppec_no)."'
		");
		if($optimusprimequery){
			$optimusprime="success";
		}else{
			$optimusprime="not success";
		}
		return $optimusprime;
	}

	// save ppec
	public function stc_save_ppec_ppe($stc_ppec_no, $stc_workmen, $stc_filter){
		$optimusprime='';
		$insertqry='';
		$insertval='';
		foreach($stc_filter as $key => $values){
			if($values == "Helmet"){
				$insertqry .='`stc_safetyppec_ppes_helmet`,';
				$insertval .='1,';
			}
			if($values == "Shoe"){
				$insertqry .='`stc_safetyppec_ppes_shoes`,';
				$insertval .='1,';
			}
			if($values == "HV jacket"){
				$insertqry .='`stc_safetyppec_ppes_hvjacket`,';
				$insertval .='1,';
			}
			if($values == "safety googles"){
				$insertqry .='`stc_safetyppec_ppes_safetygoggles`,';
				$insertval .='1,';
			}
			if($values == "F R Jacket"){
				$insertqry .='`stc_safetyppec_ppes_frjacket`,';
				$insertval .='1,';
			}
			if($values == "Ear plug"){
				$insertqry .='`stc_safetyppec_ppes_earplug`,';
				$insertval .='1,';
			}
			if($values == "Cotten gloves"){
				$insertqry .='`stc_safetyppec_ppes_cottongloves`,';
				$insertval .='1,';
			}
			if($values == "Mask"){
				$insertqry .='`stc_safetyppec_ppes_nosemask`,';
				$insertval .='1,';
			}
			if($values == "Face Shield 1"){
				$insertqry .='`stc_safetyppec_ppes_faceshieldcovid19`,';
				$insertval .='1,';
			}
			if($values == "Apron"){
				$insertqry .='`stc_safetyppec_ppes_apron`,';
				$insertval .='1,';
			}
			if($values == "Face Shield"){
				$insertqry .='`stc_safetyppec_ppes_faceshield`,';
				$insertval .='1,';
			}
			if($values == "Cutting Gooles"){
				$insertqry .='`stc_safetyppec_ppes_cuttinggoogles`,';
				$insertval .='1,';
			}
			if($values == "Leather Gloves"){
				$insertqry .='`stc_safetyppec_ppes_leathergloves`,';
				$insertval .='1,';
			}
			if($values == "Leg Guard"){
				$insertqry .='`stc_safetyppec_ppes_legguard`,';
				$insertval .='1,';
			}
			if($values == "Hand Sleeve"){
				$insertqry .='`stc_safetyppec_ppes_handsleeve`,';
				$insertval .='1,';
			}
			if($values == "Safety Harness"){
				$insertqry .='`stc_safetyppec_ppes_safetyharness`,';
				$insertval .='1,';
			}
			if($values == "Rubber Gloves"){
				$insertqry .='`stc_safetyppec_ppes_rubbergloves`,';
				$insertval .='1,';
			}
			if($values == "Lubrication Gloves"){
				$insertqry .='`stc_safetyppec_ppes_lubricationgloves`,';
				$insertval .='1,';
			}
			if($values == "Ear Muff"){
				$insertqry .='`stc_safetyppec_ppes_earmuff`,';
				$insertval .='1,';
			}
			if($values == "Sin Guard"){
				$insertqry .='`stc_safetyppec_ppes_singuard`,';
				$insertval .='1,';
			}
		}
		$optimusprime_qry="
			INSERT INTO `stc_safetyppec_ppes`(
				`stc_safetyppec_ppes_ppec_id`,
			    `stc_safetyppec_ppes_workmen`,
			    ".$insertqry."
			    `stc_safetyppec_ppes_created_by`
			) VALUES (
				'".mysqli_real_escape_string($this->stc_dbs, $stc_ppec_no)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_workmen)."',
				".$insertval."
				'".$_SESSION['stc_agent_sub_id']."'
			)
		";
		$optimusprime_res=mysqli_query($this->stc_dbs, $optimusprime_qry);
		if($optimusprime_res){
			$optimusprime="success";
		}else{
			$optimusprime="not success";
		}
		return $optimusprime;
	}
}

class witcher_toollist extends tesseract{

	// calll created toollist no
	public function stc_hit_toollist_call_no(){
		$optimusprime='';
		$date=date("Y-m-d");
		$optimusprimechecquery=mysqli_query($this->stc_dbs, "
			SELECT `stc_safetytoolslist_date` FROM `stc_safetytoolslist` WHERE `stc_safetytoolslist_date`='".$date."' AND `stc_safetytoolslist_createdby`='".$_SESSION['stc_agent_sub_id']."' ORDER BY `stc_safetytoolslist_id` DESC LIMIT 0,1
		");
		$previousentrydate='';
		foreach($optimusprimechecquery as $optimusprimechecrow){
			$previousentrydate=$optimusprimechecrow['stc_safetytoolslist_date'];
		}
		if(abs($previousentrydate==$date)){
			$optimusprime='same';
		}else{
			$optimusprimequery=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_safetytoolslist`(
					`stc_safetytoolslist_date`, 
					`stc_safetytoolslist_createdby`
				) VALUES (
					'".$date."',
					'".$_SESSION['stc_agent_sub_id']."'
				)
			");
			if($optimusprimequery){
				$optimusprimequery=mysqli_query($this->stc_dbs, "
					SELECT `stc_safetytoolslist_id` FROM `stc_safetytoolslist` WHERE `stc_safetytoolslist_createdby`='".$_SESSION['stc_agent_sub_id']."' ORDER BY `stc_safetytoolslist_id` DESC LIMIT 0,1
				");
				foreach($optimusprimequery as $optimusprimerow){
					$optimusprime=$optimusprimerow['stc_safetytoolslist_id'];
				}
			}else{
				$optimusprime="not success";
			}
		}
		return $optimusprime;
	}

	// call toollist
	public function stc_call_toollist(){
		$optimusprime='';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_safetytoolslist` 
			LEFT JOIN `stc_cust_pro_supervisor`
			ON `stc_cust_pro_supervisor_id`=`stc_safetytoolslist_createdby`
			WHERE `stc_safetytoolslist_createdby`='".$_SESSION['stc_agent_sub_id']."' 
			ORDER BY DATE(`stc_safetytoolslist_date`) DESC
		");
		if(mysqli_num_rows($optimusprimequery)>0){
			foreach($optimusprimequery as $optimusprimerow){
				$action_show='
					<a href="../stc_agent47/safety-tbm-print-preview.php?tbm_no='.$optimusprimerow['stc_safetytoolslist_id'].'" class="form-control btn btn-success" >View</a>
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

	// delete toollist
	public function stc_delete_toollist($toollist_id){
		$optimusprime='';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			DELETE FROM `stc_safetytoolslist` WHERE `stc_safetytoolslist_id`='".mysqli_real_escape_string($this->stc_dbs, $toollist_id)."'
		");
		if($optimusprimequery){
			$optimusprimequery=mysqli_query($this->stc_dbs, "
				DELETE FROM `stc_safetytoolslistitem` WHERE `stc_safetytoolslistitem_tlid`='".mysqli_real_escape_string($this->stc_dbs, $toollist_id)."'
			");
			$optimusprime="success";
		}else{
			$optimusprime="not success";
		}
		return $optimusprime;
	}

	// call toollist
	public function stc_call_toollist_fields($stc_toollist_no){
		$optimusprime=array();
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_safetytoolslist` WHERE `stc_safetytoolslist_id`='".$stc_toollist_no."' ORDER BY `stc_safetytoolslist_id` DESC
		");
		foreach($optimusprimequery as $optimusprimerow){
			$optimusprime['toollist']=$optimusprimerow;
		}
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_safetytoolslistitem` WHERE `stc_safetytoolslistitem_tlid`='".$stc_toollist_no."' ORDER BY `stc_safetytoolslistitem_id` DESC
		");
		foreach($optimusprimequery as $optimusprimerow){
			$optimusprime['toollistlist'][]=$optimusprimerow;
		}
		return $optimusprime;
	}

	// update toollist
	public function stc_update_toollist($stc_toollist_no, $stc_toollistdate, $stc_toollistwono, $stc_toollistsitename, $stc_toollistsuptech){
		$optimusprime='';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			UPDATE
			    `stc_safetytoolslist`
			SET
				`stc_safetytoolslist_date`='".mysqli_real_escape_string($this->stc_dbs, $stc_toollistdate)."',
				`stc_safetytoolslist_wono`='".mysqli_real_escape_string($this->stc_dbs, $stc_toollistwono)."',
				`stc_safetytoolslist_sitename`='".mysqli_real_escape_string($this->stc_dbs, $stc_toollistsitename)."',
				`stc_safetytoolslist_supervisor`='".mysqli_real_escape_string($this->stc_dbs, $stc_toollistsuptech)."'

			WHERE
			    `stc_safetytoolslist_id`='".mysqli_real_escape_string($this->stc_dbs, $stc_toollist_no)."'
		");
		if($optimusprimequery){
			$optimusprime="success";
		}else{
			$optimusprime="not success";
		}
		return $optimusprime;
	}

	public function stc_save_tooollisttools($stc_toollist_no, $stc_toolddesc, $stc_toolqty, $stc_toolinuse, $stc_toolinrepair, $stc_tooldamaged){
		$optimusprime='';
		$optimusprime_qry="
			INSERT INTO `stc_safetytoolslistitem`(
				`stc_safetytoolslistitem_tlid`,
				`stc_safetytoolslistitem_tooldesc`,
				`stc_safetytoolslistitem_qty`,
				`stc_safetytoolslistitem_inuse`,
				`stc_safetytoolslistitem_repair`,
				`stc_safetytoolslistitem_damage`
			) VALUES (
				'".mysqli_real_escape_string($this->stc_dbs, $stc_toollist_no)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_toolddesc)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_toolqty)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_toolinuse)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_toolinrepair)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_tooldamaged)."'
			)
		";
		$optimusprime_res=mysqli_query($this->stc_dbs, $optimusprime_qry);
		if($optimusprime_res){
			$optimusprime="success";
		}else{
			$optimusprime="not success";
		}
		return $optimusprime;
	}
	
}
/*-----------------------------------------------------------------------------------*/
/*-------------------------------------For Safety------------------------------------*/
/*-----------------------------------------------------------------------------------*/
// add id to tbm
if(isset($_POST['stc_safety_addtbm'])){
	$objsearchreq=new witcher_supervisor();
	$opobjsearchreq=$objsearchreq->stc_hit_tbm_call_no();
	echo $opobjsearchreq;
}

// call tbm 
if(isset($_POST['stc_safety_calltbm'])){
	$objsearchreq=new witcher_supervisor();
	$opobjsearchreq=$objsearchreq->stc_call_tbm();
	echo $opobjsearchreq;
}

// call fields for tbm
if(isset($_POST['stc_safety_calltbmfields'])){
	$stc_tbm_no=$_POST['stc_tbm_no'];
	$objsearchreq=new witcher_supervisor();
	$opobjsearchreq=$objsearchreq->stc_call_tbm_fields($stc_tbm_no);
	echo json_encode($opobjsearchreq);
}

// delete tbm 
if(isset($_POST['stc_safety_deletetbm'])){
	$tbm_id=$_POST['tbm_id'];
	$objsearchreq=new witcher_supervisor();
	$opobjsearchreq=$objsearchreq->stc_delete_tbm($tbm_id);
	echo $opobjsearchreq;
}

// update save for tbm
if(isset($_POST['stc_safety_updatetbm'])){
	$stc_tbm_no=$_POST['stc_tbm_no'];
	$stc_date=$_POST['stc_date'];
	$stc_time=$_POST['stc_time'];
	$stc_place=$_POST['stc_place'];
	$stc_agendaofmeeting=$_POST['stc_agendaofmeeting'];
	$stc_pointtone=$_POST['stc_pointtone'];
	$stc_pointtwo=$_POST['stc_pointtwo'];
	$stc_pointthree=$_POST['stc_pointthree'];
	$stc_pointfour=$_POST['stc_pointfour'];
	$stc_pointfive=$_POST['stc_pointfive'];
	$stc_pointsix=$_POST['stc_pointsix'];
	$stc_suggesionsio=$_POST['stc_suggesionsio'];
	$stc_entryname=$_POST['stc_entryname'];
	$stc_desgination=$_POST['stc_desgination'];
	$stc_gatepass=$_POST['stc_gatepass'];
	$objsearchreq=new witcher_supervisor();
	$opobjsearchreq=$objsearchreq->stc_update_tbm($stc_tbm_no, $stc_date, $stc_time, $stc_place, $stc_agendaofmeeting, $stc_pointtone, $stc_pointtwo, $stc_pointthree, $stc_pointfour, $stc_pointfive, $stc_pointsix, $stc_suggesionsio, $stc_entryname, $stc_desgination, $stc_gatepass);
	echo $opobjsearchreq;
}

// save gate entry
if(isset($_POST['stc_safety_savetbmgateentry'])){
	$stc_tbm_no=$_POST['stc_tbm_no'];
	$stc_work_permit_no=$_POST['stc_work_permit_no'];
	$stc_shift=$_POST['stc_shift'];
	$stc_time=$_POST['stc_time'];
	$stc_supervisor_name=$_POST['stc_supervisor_name'];
	$objsearchreq=new witcher_supervisor();
	$opobjsearchreq=$objsearchreq->stc_save_tbm_gateentry($stc_tbm_no, $stc_work_permit_no, $stc_shift, $stc_time, $stc_supervisor_name);
	echo $opobjsearchreq;
}

// save responsibility
if(isset($_POST['stc_safety_savetbmresponsibility'])){
	$stc_tbm_no=$_POST['stc_tbm_no'];
	$stc_res_date=$_POST['stc_res_date'];
	$stc_res_responsibility=$_POST['stc_res_responsibility'];
	$stc_res_item=$_POST['stc_res_item'];
	$objsearchreq=new witcher_supervisor();
	$opobjsearchreq=$objsearchreq->stc_save_tbm_responsibility($stc_tbm_no, $stc_res_date, $stc_res_responsibility, $stc_res_item);
	echo $opobjsearchreq;
}

// save image
if(isset($_POST['stc-safety-tbm-id'])){
	$tbm_id=$_POST['stc-safety-tbm-id'];
	$stcsafetyimages=$_FILES['stc-safety-image-path']['name'];
	$stcsafetytmpname=$_FILES['stc-safety-image-path']['tmp_name'];
	$objsearchreq=new witcher_supervisor();
	$opobjsearchreq=$objsearchreq->stc_save_tbm_image($tbm_id, $stcsafetyimages);
	if($opobjsearchreq=="success"){
		move_uploaded_file($stcsafetytmpname, "../safety_img/".$stcsafetyimages);
	}
	echo $opobjsearchreq;
}

// save ppe checklist
if(isset($_POST['stc_safety_savetbmppechecklist'])){
	$stc_tbm_no=$_POST['stc_tbm_no'];
	$stc_emp_name=$_POST['stc_emp_name'];
	$stc_filter=$_POST['stc_filter'];
	$objsearchreq=new witcher_supervisor();
	$opobjsearchreq=$objsearchreq->stc_save_tbm_ppe_checklist($stc_tbm_no, $stc_emp_name, $stc_filter);
	echo $opobjsearchreq;
}

/*-------------------------------------For vhl vehicle checklist------------------------------------*/
// add id to tbm
if(isset($_POST['stc_safety_addvhl'])){
	$objsearchreq=new witcher_vhl();
	$opobjsearchreq=$objsearchreq->stc_hit_vhl_call_no();
	echo $opobjsearchreq;
}

// call tbm 
if(isset($_POST['stc_safety_callvhl'])){
	$objsearchreq=new witcher_vhl();
	$opobjsearchreq=$objsearchreq->stc_call_vhl();
	echo $opobjsearchreq;
}

// delete vhl
if(isset($_POST['stc_safety_deletevhl'])){
	$vhl_id=$_POST['vhl_id'];
	$objsearchreq=new witcher_vhl();
	$opobjsearchreq=$objsearchreq->stc_delete_vhl($vhl_id);
	echo $opobjsearchreq;
}

// call fields for vhl
if(isset($_POST['stc_safety_callvhlfields'])){
	$stc_vhl_no=$_POST['stc_vhl_no'];
	$objsearchreq=new witcher_vhl();
	$opobjsearchreq=$objsearchreq->stc_call_vhl_fields($stc_vhl_no);
	echo json_encode($opobjsearchreq);
	// echo $opobjsearchreq;
}

// update save for tbm
if(isset($_POST['stc_safety_updatevhl'])){
	$stc_vhl_no=$_POST['stc_vhl_no'];
	$stc_description=$_POST['stc_description'];
	$stc_reg_name=$_POST['stc_reg_name'];
	$stc_dateofinspection=$_POST['stc_dateofinspection'];
	$stc_driversname=$_POST['stc_driversname'];
	$stc_undertaking_vehic=$_POST['stc_undertaking_vehic'];
	$stc_signature=$_POST['stc_signature'];
	$stc_faultsreported=$_POST['stc_faultsreported'];
	$stc_ol=$_POST['stc_ol'];
	$stc_bfl=$_POST['stc_bfl'];
	$stc_wl=$_POST['stc_wl'];
	$stc_ws=$_POST['stc_ws'];
	$stc_as=$_POST['stc_as'];
	$stc_sb=$_POST['stc_sb'];
	$stc_pb=$_POST['stc_pb'];
	$stc_fb=$_POST['stc_fb'];
	$stc_pb1=$_POST['stc_pb1'];
	$stc_cgs=$_POST['stc_cgs'];
	$stc_mc=$_POST['stc_mc'];
	$stc_dl=$_POST['stc_dl'];
	$stc_stg=$_POST['stc_stg'];
	$stc_lc=$_POST['stc_lc'];
	$stc_dashp=$_POST['stc_dashp'];
	$stc_horn=$_POST['stc_horn'];
	$stc_alarm=$_POST['stc_alarm'];
	$stc_hyds=$_POST['stc_hyds'];
	$stc_spart=$_POST['stc_spart'];
	$stc_towbar=$_POST['stc_towbar'];
	$stc_equip=$_POST['stc_equip'];
	$stc_fk=$_POST['stc_fk'];
	$stc_other=$_POST['stc_other'];

	$objsearchreq=new witcher_vhl();
	$opobjsearchreq=$objsearchreq->stc_update_vhl($stc_vhl_no, $stc_description, $stc_reg_name, $stc_dateofinspection, $stc_driversname, $stc_undertaking_vehic, $stc_signature, $stc_faultsreported, $stc_ol, $stc_bfl, $stc_wl, $stc_ws, $stc_as, $stc_sb, $stc_pb, $stc_fb, $stc_pb1, $stc_cgs, $stc_mc, $stc_dl, $stc_stg, $stc_lc, $stc_dashp, $stc_horn, $stc_alarm, $stc_hyds, $stc_spart, $stc_towbar, $stc_equip, $stc_fk, $stc_other);
	echo $opobjsearchreq;
}

/*-------------------------------------For ppem------------------------------------*/
// add id to ppem
if(isset($_POST['stc_safety_addppem'])){
	$objsearchreq=new witcher_ppem();
	if(empty($_SESSION['stc_agent_sub_id'])){
		$opobjsearchreq="reload";
	}else{
		$opobjsearchreq=$objsearchreq->stc_hit_ppem_call_no();
	}
	echo $opobjsearchreq;
}

// call ppem 
if(isset($_POST['stc_safety_callppem'])){
	$objsearchreq=new witcher_ppem();
	$opobjsearchreq=$objsearchreq->stc_call_ppem();
	echo $opobjsearchreq;
}

// delete ppem
if(isset($_POST['stc_safety_deleteppem'])){
	$ppem_id=$_POST['ppem_id'];
	$objsearchreq=new witcher_ppem();
	$opobjsearchreq=$objsearchreq->stc_delete_ppem($ppem_id);
	echo $opobjsearchreq;
}

// call fields for ppem
if(isset($_POST['stc_safety_callppemfields'])){
	$stc_ppem_no=$_POST['stc_ppem_no'];
	$objsearchreq=new witcher_ppem();
	$opobjsearchreq=$objsearchreq->stc_call_ppem_fields($stc_ppem_no);
	echo json_encode($opobjsearchreq);
	// echo $opobjsearchreq;
}

// update save for ppem
if(isset($_POST['stc_safety_updateppem'])){
	$stc_ppem_no=$_POST['stc_ppem_no'];
	$stc_date=$_POST['stc_date'];
	$stc_sitename=$_POST['stc_sitename'];
	$stc_supervisorname=$_POST['stc_supervisorname'];

	$objsearchreq=new witcher_ppem();
	$opobjsearchreq=$objsearchreq->stc_update_ppem($stc_ppem_no, $stc_date, $stc_sitename, $stc_supervisorname);
	echo $opobjsearchreq;
}

// save ppe checklist
if(isset($_POST['stc_safety_saveppemppe'])){
	$stc_ppem_no=$_POST['stc_ppem_no'];
	$stc_sitentry=$_POST['stc_sitentry'];
	$stc_filter=$_POST['stc_filter'];
	$objsearchreq=new witcher_ppem();
	$opobjsearchreq=$objsearchreq->stc_save_ppem_ppe($stc_ppem_no, $stc_sitentry, $stc_filter);
	echo $opobjsearchreq;
}
/*-------------------------------------For nnear misss------------------------------------*/
// add id to nearmiss
if(isset($_POST['stc_safety_addnearmiss'])){
	$objsearchreq=new witcher_nearmiss();
	if(empty($_SESSION['stc_agent_sub_id'])){
		$opobjsearchreq="reload";
	}else{
		$opobjsearchreq=$objsearchreq->stc_hit_nearmiss_call_no();
	}
	echo $opobjsearchreq;
}

// call nearmiss 
if(isset($_POST['stc_safety_callnearmiss'])){
	$objsearchreq=new witcher_nearmiss();
	$opobjsearchreq=$objsearchreq->stc_call_nearmiss();
	echo $opobjsearchreq;
}

// delete nearmiss
if(isset($_POST['stc_safety_deletenearmiss'])){
	$nearmiss_id=$_POST['nearmiss_id'];
	$objsearchreq=new witcher_nearmiss();
	$opobjsearchreq=$objsearchreq->stc_delete_nearmiss($nearmiss_id);
	echo $opobjsearchreq;
}

// call fields for nearmiss
if(isset($_POST['stc_safety_callnearmissfields'])){
	$stc_nearmiss_no=$_POST['stc_nearmiss_no'];
	$objsearchreq=new witcher_nearmiss();
	$opobjsearchreq=$objsearchreq->stc_call_nearmiss_fields($stc_nearmiss_no);
	echo json_encode($opobjsearchreq);
	// echo $opobjsearchreq;
}

// update save for nearmiss
if(isset($_POST['stc_safety_updatenearmiss'])){
	$stc_nearmiss_no=$_POST['stc_nearmiss_no'];
	$stc_orgunitoccur=$_POST['stc_orgunitoccur'];
	$stc_incidentdate=$_POST['stc_incidentdate'];
	$stc_incidenttime=$_POST['stc_incidenttime'];
	$stc_incidentlocation=$_POST['stc_incidentlocation'];
	$stc_placeofincident=@$_POST['stc_placeofincident'];
	$stc_possibleconsq=@$_POST['stc_possibleconsq'];
	$stc_incidenetdesc=$_POST['stc_incidenetdesc'];
	$stc_primarycause=$_POST['stc_primarycause'];
	$stc_empname=$_POST['stc_empname'];
	$stc_gpno=$_POST['stc_gpno'];
	$stc_vendor=$_POST['stc_vendor'];
	$stc_actiontaken=$_POST['stc_actiontaken'];
	$stc_currdate=$_POST['stc_currdate'];
	$stc_namesign=$_POST['stc_namesign'];

	$objsearchreq=new witcher_nearmiss();
	$opobjsearchreq=$objsearchreq->stc_update_nearmiss($stc_nearmiss_no, $stc_orgunitoccur, $stc_incidentdate, $stc_incidenttime, $stc_incidentlocation, $stc_placeofincident, $stc_possibleconsq, $stc_incidenetdesc, $stc_primarycause, $stc_empname, $stc_gpno, $stc_vendor, $stc_actiontaken, $stc_currdate, $stc_namesign);
	echo $opobjsearchreq;
}

// save image
if(isset($_POST['stc-safety-nearmiss-id'])){
	$nearmiss_id=$_POST['stc-safety-nearmiss-id'];
	$stcsafetyimages=$_FILES['stc-safety-nearmissimage-path']['name'];
	$stcsafetytmpname=$_FILES['stc-safety-nearmissimage-path']['tmp_name'];
	$objsearchreq=new witcher_nearmiss();
	$opobjsearchreq=$objsearchreq->stc_save_nearmiss_image($nearmiss_id, $stcsafetyimages);
	if($opobjsearchreq=="success"){
		move_uploaded_file($stcsafetytmpname, "../safety_img/".$stcsafetyimages);
	}
	echo $opobjsearchreq;
}
/*-------------------------------------For hot work------------------------------------*/
// add id to hot work
if(isset($_POST['stc_safety_addhotwork'])){
	$objsearchreq=new witcher_hotwork();
	if(empty($_SESSION['stc_agent_sub_id'])){
		$opobjsearchreq="reload";
	}else{
		$opobjsearchreq=$objsearchreq->stc_hit_hotwork_call_no();
	}
	echo $opobjsearchreq;
}

// call hot work 
if(isset($_POST['stc_safety_callhotwork'])){
	$objsearchreq=new witcher_hotwork();
	$opobjsearchreq=$objsearchreq->stc_call_hotwork();
	echo $opobjsearchreq;
}

// delete hot work
if(isset($_POST['stc_safety_deletehotwork'])){
	$hotwork_id=$_POST['hotwork_id'];
	$objsearchreq=new witcher_hotwork();
	$opobjsearchreq=$objsearchreq->stc_delete_hotwork($hotwork_id);
	echo $opobjsearchreq;
}

// update save for nearmiss
if(isset($_POST['stc_safety_updatehotwork'])){
	$stc_hotwork_no=$_POST['stc_hotwork_no'];
	$stc_wono=$_POST['stc_wono'];
	$stc_sitename=$_POST['stc_sitename'];
	$stc_starting=$_POST['stc_starting'];
	$stc_make=$_POST['stc_make'];
	$stc_slno=$_POST['stc_slno'];
	$stc_capaei=$_POST['stc_capaei'];
	$stc_oosigwc=$_POST['stc_oosigwc'];
	$stc_vacawp=$_POST['stc_vacawp'];
	$stc_rwp=$_POST['stc_rwp'];
	$stc_tspt=$_POST['stc_tspt'];
	$stc_cfmtec=$_POST['stc_cfmtec'];
	$stc_geagde=$_POST['stc_geagde'];
	$stc_cfmteh=$_POST['stc_cfmteh'];
	$stc_eh=$_POST['stc_eh'];
	$stc_wmccanlc=$_POST['stc_wmccanlc'];
	$stc_wfr=$_POST['stc_wfr'];
	$stc_cfeaftws=$_POST['stc_cfeaftws'];
	$stc_scle=$_POST['stc_scle'];
	$stc_ctte=$_POST['stc_ctte'];
	$stc_wcopraipcwad=$_POST['stc_wcopraipcwad'];
	$stc_cltp=$_POST['stc_cltp'];
	$stc_wianep=$_POST['stc_wianep'];
	$stc_socuaptvs=$_POST['stc_socuaptvs'];
	$stc_recoslaecpfbutcw=$_POST['stc_recoslaecpfbutcw'];
	$stc_aowbcbh=$_POST['stc_aowbcbh'];
	$stc_acwoseow=$_POST['stc_acwoseow'];
	$stc_acpptpth=$_POST['stc_acpptpth'];

	$objsearchreq=new witcher_hotwork();
	$opobjsearchreq=$objsearchreq->stc_update_hotwork($stc_hotwork_no, $stc_wono, $stc_sitename, $stc_starting, $stc_make, $stc_slno, $stc_capaei, $stc_oosigwc, $stc_vacawp, $stc_rwp, $stc_tspt, $stc_cfmtec, $stc_geagde, $stc_cfmteh, $stc_eh, $stc_wmccanlc, $stc_wfr, $stc_cfeaftws, $stc_scle, $stc_ctte, $stc_wcopraipcwad, $stc_cltp, $stc_wianep, $stc_socuaptvs, $stc_recoslaecpfbutcw, $stc_aowbcbh, $stc_acwoseow, $stc_acpptpth);
	echo $opobjsearchreq;
}

// call fields for nearmiss
if(isset($_POST['stc_safety_callhotworkfields'])){
	$stc_hotwork_no=$_POST['stc_hotwork_no'];
	$objsearchreq=new witcher_hotwork();
	$opobjsearchreq=$objsearchreq->stc_call_hotwork_fields($stc_hotwork_no);
	echo json_encode($opobjsearchreq);
	// echo $opobjsearchreq;
}

/*-------------------------------------For ppeck------------------------------------*/
// add id to ppec
if(isset($_POST['stc_safety_addppec'])){
	$objsearchreq=new witcher_ppec();
	if(empty($_SESSION['stc_agent_sub_id'])){
		$opobjsearchreq="reload";
	}else{
		$opobjsearchreq=$objsearchreq->stc_hit_ppec_call_no();
	}
	echo $opobjsearchreq;
}

// call ppec
if(isset($_POST['stc_safety_callppec'])){
	$objsearchreq=new witcher_ppec();
	$opobjsearchreq=$objsearchreq->stc_call_ppec();
	echo $opobjsearchreq;
}

// delete ppec
if(isset($_POST['stc_safety_deleteppec'])){
	$ppec_id=$_POST['ppec_id'];
	$objsearchreq=new witcher_ppec();
	$opobjsearchreq=$objsearchreq->stc_delete_ppec($ppec_id);
	echo $opobjsearchreq;
}

// call fields for ppec
if(isset($_POST['stc_safety_callppecfields'])){
	$stc_ppec_no=$_POST['stc_ppec_no'];
	$objsearchreq=new witcher_ppec();
	$opobjsearchreq=$objsearchreq->stc_call_ppec_fields($stc_ppec_no);
	echo json_encode($opobjsearchreq);
	// echo $opobjsearchreq;
}

// update save for ppec
if(isset($_POST['stc_safety_updateppec'])){
	$stc_ppec_no=$_POST['stc_ppec_no'];
	$stc_ppec_wono=$_POST['stc_ppec_wono'];
	$stc_ppec_sitename=$_POST['stc_ppec_sitename'];
	$stc_ppec_check=$_POST['stc_ppec_check'];
	$stc_ppec_sitesup=$_POST['stc_ppec_sitesup'];
	$stc_ppec_safsup=$_POST['stc_ppec_safsup'];

	$objsearchreq=new witcher_ppec();
	$opobjsearchreq=$objsearchreq->stc_update_ppec($stc_ppec_no, $stc_ppec_wono, $stc_ppec_sitename, $stc_ppec_check, $stc_ppec_sitesup, $stc_ppec_safsup);
	echo $opobjsearchreq;
}

// save ppe checklist
if(isset($_POST['stc_safety_saveppecppe'])){
	$stc_ppec_no=$_POST['stc_ppec_no'];
	$stc_workmen=$_POST['stc_workmen'];
	$stc_filter=$_POST['stc_filter'];
	$objsearchreq=new witcher_ppec();
	$opobjsearchreq=$objsearchreq->stc_save_ppec_ppe($stc_ppec_no, $stc_workmen, $stc_filter);
	echo $opobjsearchreq;
}

/*-------------------------------------For ppeck------------------------------------*/
// add id to toollist
if(isset($_POST['stc_safety_addtoollist'])){
	$objsearchreq=new witcher_toollist();
	if(empty($_SESSION['stc_agent_sub_id'])){
		$opobjsearchreq="reload";
	}else{
		$opobjsearchreq=$objsearchreq->stc_hit_toollist_call_no();
	}
	echo $opobjsearchreq;
}

// call toollist
if(isset($_POST['stc_safety_calltoollist'])){
	$objsearchreq=new witcher_toollist();
	$opobjsearchreq=$objsearchreq->stc_call_toollist();
	echo $opobjsearchreq;
}

// delete toollist
if(isset($_POST['stc_safety_deletetoollist'])){
	$toollist_id=$_POST['toollist_id'];
	$objsearchreq=new witcher_toollist();
	$opobjsearchreq=$objsearchreq->stc_delete_toollist($toollist_id);
	echo $opobjsearchreq;
}

// call fields for toollist
if(isset($_POST['stc_safety_calltoollistfields'])){
	$stc_toollist_no=$_POST['stc_toollist_no'];
	$objsearchreq=new witcher_toollist();
	$opobjsearchreq=$objsearchreq->stc_call_toollist_fields($stc_toollist_no);
	echo json_encode($opobjsearchreq);
	// echo $opobjsearchreq;
}

// update save for toollist
if(isset($_POST['stc_safety_updatetoollist'])){
	$stc_toollist_no=$_POST['stc_toollist_no'];
	$stc_toollistdate=$_POST['stc_toollistdate'];
	$stc_toollistwono=$_POST['stc_toollistwono'];
	$stc_toollistsitename=$_POST['stc_toollistsitename'];
	$stc_toollistsuptech=$_POST['stc_toollistsuptech'];

	$objsearchreq=new witcher_toollist();
	$opobjsearchreq=$objsearchreq->stc_update_toollist($stc_toollist_no, $stc_toollistdate, $stc_toollistwono, $stc_toollistsitename, $stc_toollistsuptech);
	echo $opobjsearchreq;
}

// save toolllist tools
if(isset($_POST['stc_safety_savetoolliste'])){
	$stc_toollist_no=$_POST['stc_toollist_no'];
	$stc_toolddesc=$_POST['stc_toolddesc'];
	$stc_toolqty=$_POST['stc_toolqty'];
	$stc_toolinuse=$_POST['stc_toolinuse'];
	$stc_toolinrepair=$_POST['stc_toolinrepair'];
	$stc_tooldamaged=$_POST['stc_tooldamaged'];

	$objsearchreq=new witcher_toollist();
	$opobjsearchreq=$objsearchreq->stc_save_tooollisttools($stc_toollist_no, $stc_toolddesc, $stc_toolqty, $stc_toolinuse, $stc_toolinrepair, $stc_tooldamaged);
	echo $opobjsearchreq;
}

?>