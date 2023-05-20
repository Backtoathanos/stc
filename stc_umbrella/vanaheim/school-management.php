<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include "../../MCU/obdb.php";
/*------------------------------------------------------------------------------------------------*/
/*---------------------------------------For School Canteen --------------------------------------*/
/*------------------------------------------------------------------------------------------------*/
class Yggdrasil extends tesseract{
	// stc call request
	public function stc_save_school_teacher($stcschoolmanagementteacherid, $stcschoolmanagementteacherfirstname, $stcschoolmanagementteacherlastname, $stcschoolmanagementteacherdateofbirth, $stcschoolmanagementteachergender, $stcschoolmanagementteacherbloodgroup, $stcschoolmanagementteacheremail, $stcschoolmanagementteachernumber, $stcschoolmanagementteacheraddress, $stcschoolmanagementteacherskills, $stcschoolmanagementteacherreligion, $stcschoolmanagementteacherjoiningdate, $stcschoolmanagementteacherremarks){
		$odin='';
		$date=date("Y-m-d H:i:s");
		$check_qry=mysqli_query($this->stc_dbs, "
			SELECT `stc_school_teacher_id` FROM `stc_school_teacher` WHERE `stc_school_teacher_teachid`='".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementteacherid)."' OR `stc_school_teacher_email`='".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementteacheremail)."' OR `stc_school_teacher_contact` = '".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementteachernumber)."'
		");
		if(mysqli_num_rows($check_qry)>0){
			$odin = "duplicate";
		}else{
			$set_loki=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_school_teacher`(
				    `stc_school_teacher_teachid`,
				    `stc_school_teacher_firstname`,
				    `stc_school_teacher_lastname`,
				    `stc_school_teacher_dob`,
				    `stc_school_teacher_gender`,
				    `stc_school_teacher_bloodgroup`,
				    `stc_school_teacher_email`,
				    `stc_school_teacher_contact`,
				    `stc_school_teacher_address`,
				    `stc_school_teacher_skills`,
				    `stc_school_teacher_religion`,
				    `stc_school_teacher_joindate`,
				    `stc_school_teacher_remarks`,
				    `stc_school_teacher_status`,
				    `stc_school_teacher_createdate`,
				    `stc_school_teacher_createdby`
				)VALUES(
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementteacherid)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementteacherfirstname)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementteacherlastname)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementteacherdateofbirth)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementteachergender)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementteacherbloodgroup)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementteacheremail)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementteachernumber)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementteacheraddress)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementteacherskills)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementteacherreligion)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementteacherjoiningdate)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementteacherremarks)."',
					'1',
					'".mysqli_real_escape_string($this->stc_dbs, $date)."',
					'".$_SESSION['stc_school_user_id']."'
				)
			");
			if($set_loki){
				$fullname=$stcschoolmanagementteacherfirstname. ' ' .$stcschoolmanagementteacherlastname;
				$aboutyou="Teacher with skills ".$stcschoolmanagementteacherskills;
				$password=substr($stcschoolmanagementteacherfirstname, 0, 1).substr($stcschoolmanagementteacherlastname, 0, 1).substr($stcschoolmanagementteachernumber, -2).'#';
				$set_login=mysqli_query($this->stc_dbs, "
					INSERT INTO `stc_school`(
						`stc_school_user_fullName`,
						`stc_school_user_address`,
						`stc_school_user_email`,
						`stc_school_user_contact`,
						`stc_school_user_cityid`,
						`stc_school_user_stateid`,
						`stc_school_user_pincode`,
						`stc_school_user_aboutyou`,
						`stc_school_user_password`,
						`stc_school_user_for`,
						`stc_school_user_status`
					)VALUES(
						'".mysqli_real_escape_string($this->stc_dbs, $fullname)."',
						'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementteacheraddress)."',
						'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementteacheremail)."',
						'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementteachernumber)."',
						'1',
						'1',
						'1',
						'".mysqli_real_escape_string($this->stc_dbs, $aboutyou)."',
						'".mysqli_real_escape_string($this->stc_dbs, $password)."',
						'1',
						'1'
					)
				");
				$odin = "success";
			}else{
				$odin = "wrong";
			}
		}
		return $odin;
	}

	public function stc_save_school_student($stcschoolmanagementstudentid, $stcschoolmanagementstudentfirstname, $stcschoolmanagementstudentlastname, $stcschoolmanagementstudentdateofbirth, $stcschoolmanagementstudentgender, $stcschoolmanagementstudentbloodgroup, $stcschoolmanagementstudentemail, $stcschoolmanagementstudentnumber, $stcschoolmanagementStudentaddress, $stcschoolmanagementstudentreligion, $stcschoolmanagementstudentjoiningdate, $stcschoolmanagementstudentclassroom, $stcschoolmanagementstudentparentguardianfullname, $stcschoolmanagementstudentremarks){
		$odin='';
		$date=date("Y-m-d H:i:s");
		$check_qry=mysqli_query($this->stc_dbs, "
			SELECT `stc_school_student_id` FROM `stc_school_student` 
			WHERE 
				`stc_school_student_studid`='".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementstudentid)."' AND 
				`stc_school_student_firstname`='".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementstudentfirstname)."' AND 
				`stc_school_student_lastname`='".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementstudentlastname)."' AND 
				`stc_school_student_email`='".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementstudentemail)."' AND 
				`stc_school_student_contact`='".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementstudentnumber)."' AND 
				`stc_school_student_guardianname` = '".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementstudentparentguardianfullname)."'
		");
		if(mysqli_num_rows($check_qry)>0){
			$odin = "duplicate";
		}else{
			$set_loki=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_school_student`(
					`stc_school_student_studid`,
					`stc_school_student_firstname`,
					`stc_school_student_lastname`,
					`stc_school_student_dob`,
					`stc_school_student_gender`,
					`stc_school_student_bloodgroup`,
					`stc_school_student_email`,
					`stc_school_student_contact`,
					`stc_school_student_address`,
					`stc_school_student_religion`,
					`stc_school_student_admissiondate`,
					`stc_school_student_classroomid`,
					`stc_school_student_guardianname`,
					`stc_school_student_remarks`,
					`stc_school_student_status`,
					`stc_school_student_createdate`,
					`stc_school_student_createdby`
				)VALUES(
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementstudentid)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementstudentfirstname)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementstudentlastname)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementstudentdateofbirth)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementstudentgender)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementstudentbloodgroup)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementstudentemail)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementstudentnumber)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementStudentaddress)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementstudentreligion)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementstudentjoiningdate)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementstudentclassroom)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementstudentparentguardianfullname)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementstudentremarks)."',
					'1',
					'".mysqli_real_escape_string($this->stc_dbs, $date)."',
					'".$_SESSION['stc_school_user_id']."'
				)
			");
			if($set_loki){
				$odin = "success";
			}else{
				$odin = "wrong";
			}
		}
		return $odin;
	}

	public function stc_save_school_subject($stcschoolmanagementsubjectid, $stcschoolmanagementsubjecttitle, $stcschoolmanagementsubjectdetails){
		$odin='';
		$date=date("Y-m-d H:i:s");
		$check_qry=mysqli_query($this->stc_dbs, "
			SELECT `stc_school_subject_id` FROM `stc_school_subject` 
			WHERE 
				`stc_school_subject_subid`='".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementsubjectid)."' AND 
				`stc_school_subject_title`='".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementsubjecttitle)."'
		");
		if(mysqli_num_rows($check_qry)>0){
			$odin = "duplicate";
		}else{
			$set_loki=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_school_subject`(
				    `stc_school_subject_subid`,
				    `stc_school_subject_title`,
				    `stc_school_subject_syllabusdetails`,
				    `stc_school_subject_status`,
				    `stc_school_subject_createdate`,
				    `stc_school_subject_createdby`
				)VALUES(
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementsubjectid)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementsubjecttitle)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementsubjectdetails)."',
					'1',
					'".mysqli_real_escape_string($this->stc_dbs, $date)."',
					'".$_SESSION['stc_school_user_id']."'
				)
			");
			if($set_loki){
				$odin = "success";
			}else{
				$odin = "wrong";
			}
		}
		return $odin;
	}

	public function stc_save_school_class($stcschoolmanagementclassroomid, $stcschoolmanagementclassroomtitle, $stcschoolmanagementclassroomlocation, $stcschoolmanagementclassroomcapacity){
		$odin='';
		$date=date("Y-m-d H:i:s");
		$check_qry=mysqli_query($this->stc_dbs, "
			SELECT `stc_school_class_id` FROM `stc_school_class` WHERE `stc_school_class_classid`='".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementclassroomid)."' AND `stc_school_class_title`='".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementclassroomtitle)."'
		");
		if(mysqli_num_rows($check_qry)>0){
			$odin = "duplicate";
		}else{
			$set_loki=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_school_class`(
					`stc_school_class_title`,
					`stc_school_class_classid`,
					`stc_school_class_location`,
					`stc_school_class_capacity`,
					`stc_school_class_status`,
					`stc_school_class_createdate`,
					`stc_school_class_createdby`
				)VALUES(
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementclassroomtitle)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementclassroomid)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementclassroomlocation)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementclassroomcapacity)."',
					'1',
					'".mysqli_real_escape_string($this->stc_dbs, $date)."',
					'".$_SESSION['stc_school_user_id']."'
				)
			");
			if($set_loki){
				$odin = "success";
			}else{
				$odin = "wrong";
			}
		}
		return $odin;
	}
	

}

#<------------------------------------------------------------------------------------------>
#<-----------------------------School School Obj Section----------------------------------->
#<------------------------------------------------------------------------------------------>
// save teacher
if(isset($_POST['save_teacheradd_action'])){
	$out='';
	$stcschoolmanagementteacherid				= $_POST['stcschoolmanagementteacherid'];
	$stcschoolmanagementteacherfirstname		= $_POST['stcschoolmanagementteacherfirstname'];
	$stcschoolmanagementteacherlastname			= $_POST['stcschoolmanagementteacherlastname'];
	$stcschoolmanagementteacherdateofbirth		= $_POST['stcschoolmanagementteacherdateofbirth'];
	$stcschoolmanagementteachergender			= $_POST['stcschoolmanagementteachergender'];
	$stcschoolmanagementteacherbloodgroup		= $_POST['stcschoolmanagementteacherbloodgroup'];
	$stcschoolmanagementteacheremail			= $_POST['stcschoolmanagementteacheremail'];
	$stcschoolmanagementteachernumber			= $_POST['stcschoolmanagementteachernumber'];
	$stcschoolmanagementteacheraddress			= $_POST['stcschoolmanagementteacheraddress'];
	$stcschoolmanagementteacherskills			= $_POST['stcschoolmanagementteacherskills'];
	$stcschoolmanagementteacherreligion			= $_POST['stcschoolmanagementteacherreligion'];
	$stcschoolmanagementteacherjoiningdate		= $_POST['stcschoolmanagementteacherjoiningdate'];
	$stcschoolmanagementteacherremarks			= $_POST['stcschoolmanagementteacherremarks'];

	$valkyrie=new Yggdrasil();
	if(empty($_SESSION['stc_school_user_id'])){
		$out="reload";
	}else if(empty($stcschoolmanagementteacherfirstname) || empty($stcschoolmanagementteacherjoiningdate) || empty($stcschoolmanagementteachernumber) || empty($stcschoolmanagementteacheremail)){
		$out="empty";
	}else{
		$lokiheck=$valkyrie->stc_save_school_teacher($stcschoolmanagementteacherid, $stcschoolmanagementteacherfirstname, $stcschoolmanagementteacherlastname, $stcschoolmanagementteacherdateofbirth, $stcschoolmanagementteachergender, $stcschoolmanagementteacherbloodgroup, $stcschoolmanagementteacheremail, $stcschoolmanagementteachernumber, $stcschoolmanagementteacheraddress, $stcschoolmanagementteacherskills, $stcschoolmanagementteacherreligion, $stcschoolmanagementteacherjoiningdate, $stcschoolmanagementteacherremarks);
		$out=$lokiheck;
	}
	echo $out;
}

// save student
if(isset($_POST['save_studentadd_action'])){
	$out='';
	$stcschoolmanagementstudentid				= $_POST['stcschoolmanagementstudentid'];
	$stcschoolmanagementstudentfirstname		= $_POST['stcschoolmanagementstudentfirstname'];
	$stcschoolmanagementstudentlastname			= $_POST['stcschoolmanagementstudentlastname'];
	$stcschoolmanagementstudentdateofbirth		= $_POST['stcschoolmanagementstudentdateofbirth'];
	$stcschoolmanagementstudentgender			= $_POST['stcschoolmanagementstudentgender'];
	$stcschoolmanagementstudentbloodgroup		= $_POST['stcschoolmanagementstudentbloodgroup'];
	$stcschoolmanagementstudentemail			= $_POST['stcschoolmanagementstudentemail'];
	$stcschoolmanagementstudentnumber			= $_POST['stcschoolmanagementstudentnumber'];
	$stcschoolmanagementStudentaddress			= $_POST['stcschoolmanagementStudentaddress'];
	$stcschoolmanagementstudentreligion			= $_POST['stcschoolmanagementstudentreligion'];
	$stcschoolmanagementstudentjoiningdate		= $_POST['stcschoolmanagementstudentjoiningdate'];
	$stcschoolmanagementstudentclassroom		= $_POST['stcschoolmanagementstudentclassroom'];
	$stcschoolmanagementstudentparentguardianfullname			= $_POST['stcschoolmanagementstudentparentguardianfullname'];
	$stcschoolmanagementstudentremarks			= $_POST['stcschoolmanagementstudentremarks'];

	$valkyrie=new Yggdrasil();
	if(empty($_SESSION['stc_school_user_id'])){
		$out="reload";
	}else if(empty($stcschoolmanagementstudentid) || empty($stcschoolmanagementstudentfirstname) || empty($stcschoolmanagementstudentlastname) || empty($stcschoolmanagementstudentemail) || empty($stcschoolmanagementstudentnumber) || empty($stcschoolmanagementstudentparentguardianfullname)){
		$out="empty";
	}else{
		$lokiheck=$valkyrie->stc_save_school_student($stcschoolmanagementstudentid, $stcschoolmanagementstudentfirstname, $stcschoolmanagementstudentlastname, $stcschoolmanagementstudentdateofbirth, $stcschoolmanagementstudentgender, $stcschoolmanagementstudentbloodgroup, $stcschoolmanagementstudentemail, $stcschoolmanagementstudentnumber, $stcschoolmanagementStudentaddress, $stcschoolmanagementstudentreligion, $stcschoolmanagementstudentjoiningdate, $stcschoolmanagementstudentclassroom, $stcschoolmanagementstudentparentguardianfullname, $stcschoolmanagementstudentremarks);
		$out=$lokiheck;
	}
	echo $out;
}

// save subject
if(isset($_POST['save_subjectadd_action'])){
	$stcschoolmanagementsubjectid =$_POST['stcschoolmanagementsubjectid'];
	$stcschoolmanagementsubjecttitle =$_POST['stcschoolmanagementsubjecttitle'];
	$stcschoolmanagementsubjectdetails =$_POST['stcschoolmanagementsubjectdetails'];
	$valkyrie=new Yggdrasil();
	if(empty($_SESSION['stc_school_user_id'])){
		$out="reload";
	}else if(empty($stcschoolmanagementsubjecttitle)){
		$out="empty";
	}else{
		$lokiheck=$valkyrie->stc_save_school_subject($stcschoolmanagementsubjectid, $stcschoolmanagementsubjecttitle, $stcschoolmanagementsubjectdetails);
		$out=$lokiheck;
	}
	echo $out;
}

// save class
if(isset($_POST['save_classadd_action'])){
	$stcschoolmanagementclassroomid =$_POST['stcschoolmanagementclassroomid'];
	$stcschoolmanagementclassroomtitle =$_POST['stcschoolmanagementclassroomtitle'];
	$stcschoolmanagementclassroomlocation =$_POST['stcschoolmanagementclassroomlocation'];
	$stcschoolmanagementclassroomcapacity = $_POST['stcschoolmanagementclassroomcapacity'];

	$valkyrie=new Yggdrasil();
	if(empty($_SESSION['stc_school_user_id'])){
		$out="reload";
	}else if(empty($stcschoolmanagementclassroomtitle)){
		$out="empty";
	}else{
		$lokiheck=$valkyrie->stc_save_school_class($stcschoolmanagementclassroomid, $stcschoolmanagementclassroomtitle, $stcschoolmanagementclassroomlocation, $stcschoolmanagementclassroomcapacity);
		$out=$lokiheck;
	}
	echo $out;
}

?>