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

				$safety_image='
						<img src="safety_img/'.$img_path.'" style="width: 190px;position: relative;left: 15%;padding: 0;margin: 0;">
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
			if($values == "Nose Mask"){
				$insertqry .='`stc_safetytbm_checklist_SafetyGoggle`,';
				$insertval .='1,';
			}
			if($values == "Safety Goggle"){
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

// save gate entry
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
?>