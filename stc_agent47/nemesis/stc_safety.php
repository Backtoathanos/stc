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

	public function stc_update_tandtdata($toolData) {
		$result = '';
	
		$insertQuery = mysqli_query($this->stc_dbs, "
			INSERT INTO `toolstackelsdata` (
				`title`,
				`calibration_date`,
				`calibration_due`,
				`status`,
				`docs`,
				`created_by`
			) VALUES (
				'" . mysqli_real_escape_string($this->stc_dbs, $toolData['toolName']) . "',
				'" . mysqli_real_escape_string($this->stc_dbs, $toolData['calibrationDate']) . "',
				'" . mysqli_real_escape_string($this->stc_dbs, $toolData['calibrationDue']) . "',
				'" . mysqli_real_escape_string($this->stc_dbs, $toolData['status']) . "',
				'" . mysqli_real_escape_string($this->stc_dbs, $toolData['certificatePath']) . "',
				'" . mysqli_real_escape_string($this->stc_dbs, $toolData['created_by']) . "'
			)
		");
	
		if ($insertQuery) {
			$result = "success";
		} else {
			$result = "not success: " . mysqli_error($this->stc_dbs);
		}
	
		return $result;
	}

	public function stc_call_tandtdata($search){
		$optimusprime='';
		$query = "
			SELECT * FROM `toolstackelsdata` WHERE `status`='1'
		";
		if($search!=''){
			$query .= ' AND `title` REGEXP "'.mysqli_real_escape_string($this->stc_dbs, $search).'"';
		}
		$query .= " ORDER BY `id` DESC";
		$optimusprimequery=mysqli_query($this->stc_dbs, $query);
		$data = [];
		if (mysqli_num_rows($optimusprimequery) > 0) {
			$i = 1; // Counter for serial number
			while ($row = mysqli_fetch_assoc($optimusprimequery)) {
				$row['calibration_date'] = date('d-m-Y', strtotime($row['calibration_date']));
				$row['calibration_due'] = date('d-m-Y', strtotime($row['calibration_due']));
				$data[] = $row;
			}
			$optimusprime=$data;
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

if (isset($_POST['tandtdataaction'])) {
	$calibrationDate = $_POST['calibrationDate'];
	$calibrationDue = $_POST['calibrationDue'];

	// Convert the dates to timestamps for comparison
	$calibrationDateTimestamp = strtotime($calibrationDate);
	$calibrationDueTimestamp = strtotime($calibrationDue);

	// Validate that calibrationDue is not less than calibrationDate
	if ($calibrationDueTimestamp < $calibrationDateTimestamp) {
		echo json_encode(['status' => false, 'message' => 'calibration Due cannot be less than calibration Date.']);
		exit;
	}

    $toolData = [
        'toolName' => $_POST['toolName'],
        'calibrationDate' => $_POST['calibrationDate'],
        'calibrationDue' => $_POST['calibrationDue'],
        'status' => 1, // Example status
        'created_by' => $_SESSION['stc_agent_id'], // Example user ID
        'certificatePath' => '' // Placeholder for file path
    ];

    if (isset($_FILES['certificate']) && $_FILES['certificate']['error'] === UPLOAD_ERR_OK) {
		$uploadDir = '../docs/'; // Absolute path to the directory
	
		// Check if the directory exists, create if not
		if (!is_dir($uploadDir)) {
			mkdir($uploadDir, 0777, true); // Create directory if it doesn't exist
		}
	
		$fileTmpPath = $_FILES['certificate']['tmp_name'];
		$fileName = $_FILES['certificate']['name'];
	
		// Replace spaces in the filename with hyphens
		$fileName = str_replace(' ', '-', $fileName);
	
		$newFileName = uniqid() . '-' . $fileName;
		$destination = $uploadDir . $newFileName;
	
		// Move the uploaded file to the destination
		if (move_uploaded_file($fileTmpPath, $destination)) {
			$toolData['certificatePath'] = $newFileName; // Save the path for database storage
		} else {
			echo json_encode(['status' => false, 'message' => 'Failed to upload file']);
			exit;
		}
	} else {
		echo json_encode(['status' => false, 'message' => 'No file uploaded or upload error']);
		exit;
	}

    // Insert data into database
    $objsearchreq = new prime();
    $result = $objsearchreq->stc_update_tandtdata($toolData);

    // Return response
    if ($result === "success") {
        echo json_encode([
            'status' => true,
            'data' => [
                'id' => mysqli_insert_id($objsearchreq->stc_dbs),
                'title' => $toolData['toolName'],
                'calibration_date' => $toolData['calibrationDate'],
                'calibration_due' => $toolData['calibrationDue'],
                'docs' => $toolData['certificatePath']
            ]
        ]);
    } else {
        echo json_encode(['status' => false, 'message' => 'Failed to save record.']);
    }
}

// call fields for tbm
if(isset($_POST['fetchToolsData'])){
	$search=$_POST['search'];
	$objsearchreq=new prime();
	$opobjsearchreq=$objsearchreq->stc_call_tandtdata($search);
	echo json_encode($opobjsearchreq);
}
?>