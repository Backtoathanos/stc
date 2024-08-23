<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*------------------------------------------For Agents--------------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
class prime extends tesseract{
    // update tbm
	public function stc_update_tbm($stc_tbm_no, $stc_agendaofmeeting, $stc_pointtone, $stc_pointtwo, $stc_pointthree, $stc_pointfour, $stc_pointfive, $stc_pointsix, $stc_suggesionsio, $stc_gatepass){
		$optimusprime='';
		$optimusprimequery=mysqli_query($this->stc_dbs, "
			UPDATE
			    `stc_safetytbm`
			SET
			    `stc_safetytbm_agendaofmeet` 	= '".mysqli_real_escape_string($this->stc_dbs, $stc_agendaofmeeting)."',
			    `stc_safetytbm_ptone` 			= '".mysqli_real_escape_string($this->stc_dbs, $stc_pointtone)."',
			    `stc_safetytbm_pttwo` 			= '".mysqli_real_escape_string($this->stc_dbs, $stc_pointtwo)."',
			    `stc_safetytbm_ptthree` 		= '".mysqli_real_escape_string($this->stc_dbs, $stc_pointthree)."',
			    `stc_safetytbm_ptfour` 			= '".mysqli_real_escape_string($this->stc_dbs, $stc_pointfour)."',
			    `stc_safetytbm_ptfive` 			= '".mysqli_real_escape_string($this->stc_dbs, $stc_pointfive)."',
			    `stc_safetytbm_ptsix` 			= '".mysqli_real_escape_string($this->stc_dbs, $stc_pointsix)."',
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
}

// update save for tbm
if(isset($_POST['stc_safety_updatetbm'])){
	$stc_tbm_no=$_POST['stc_tbm_no'];
	$stc_agendaofmeeting=$_POST['stc_agendaofmeeting'];
	$stc_pointtone=$_POST['stc_pointtone'];
	$stc_pointtwo=$_POST['stc_pointtwo'];
	$stc_pointthree=$_POST['stc_pointthree'];
	$stc_pointfour=$_POST['stc_pointfour'];
	$stc_pointfive=$_POST['stc_pointfive'];
	$stc_pointsix=$_POST['stc_pointsix'];
	$stc_suggesionsio=$_POST['stc_suggesionsio'];
	$stc_gatepass=$_POST['stc_gatepass'];
	$objsearchreq=new prime();
	$opobjsearchreq=$objsearchreq->stc_update_tbm($stc_tbm_no, $stc_agendaofmeeting, $stc_pointtone, $stc_pointtwo, $stc_pointthree, $stc_pointfour, $stc_pointfive, $stc_pointsix, $stc_suggesionsio, $stc_gatepass);
	echo $opobjsearchreq;
}

// call fields for tbm
if(isset($_POST['stc_safety_calltbmfields'])){
	$stc_tbm_no=$_POST['stc_tbm_no'];
	$objsearchreq=new prime();
	$opobjsearchreq=$objsearchreq->stc_call_tbm_fields($stc_tbm_no);
	echo json_encode($opobjsearchreq);
}
?>