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
						'4',
						'1'
					)
				");
				$get_teachid=mysqli_query($this->stc_dbs, "
					SELECT `stc_school_user_id` FROM `stc_school` WHERE `stc_school_user_contact`='".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementteachernumber)."'
				");
				$userid=0;
				foreach($get_teachid as $get_teachrow){
					$userid=$get_teachrow['userid'];
				}
				$update_teach=mysqli_query($this->stc_dbs, "
					UPDATE `stc_school_teacher` SET `stc_school_teacher_userid`='".mysqli_real_escape_string($this->stc_dbs, $userid)."' WHERE `stc_school_teacher_contact`='".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementteachernumber)."'
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
				`stc_school_student_studid`='".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementstudentid)."' OR (
				`stc_school_student_studid`='".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementstudentid)."' AND 
				`stc_school_student_firstname`='".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementstudentfirstname)."' AND 
				`stc_school_student_lastname`='".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementstudentlastname)."' AND 
				`stc_school_student_email`='".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementstudentemail)."' AND 
				`stc_school_student_contact`='".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementstudentnumber)."' AND 
				`stc_school_student_guardianname` = '".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementstudentparentguardianfullname)."')
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
	

	public function stc_save_school_schedule($stcschoolscheduleteacher, $stcschoolschedulesubject, $stcschoolscheduleclass, $stcschoolscheduleday, $stcschoolschedulestarttime, $stcschoolscheduleendtime, $stcschoolscheduleperiod){
		$odin='';
		$date=date("Y-m-d H:i:s");
		$check_qry=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_school_teacher_schedule_id` 
			FROM `stc_school_teacher_schedule` 
			WHERE 
				`stc_school_teacher_schedule_classid`='".mysqli_real_escape_string($this->stc_dbs, $stcschoolscheduleclass)."' AND 
				`stc_school_teacher_schedule_day`='".mysqli_real_escape_string($this->stc_dbs, $stcschoolscheduleday)."' AND 
				`stc_school_teacher_schedule_period`='".mysqli_real_escape_string($this->stc_dbs, $stcschoolscheduleperiod)."'
		");
		if(mysqli_num_rows($check_qry)>0){
			$odin = "duplicate";
		}else{
			$set_loki=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_school_teacher_schedule`(
					`stc_school_teacher_schedule_teacherid`,
					`stc_school_teacher_schedule_classid`,
					`stc_school_teacher_schedule_subjectid`,
					`stc_school_teacher_schedule_day`,
					`stc_school_teacher_schedule_period`,
					`stc_school_teacher_schedule_begtime`,
					`stc_school_teacher_schedule_endtime`,
					`stc_school_teacher_schedule_status`,
					`stc_school_teacher_schedule_createdate`,
					`stc_school_teacher_schedule_createdby`
				)VALUES(
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolscheduleteacher)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolscheduleclass)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolschedulesubject)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolscheduleday)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolscheduleperiod)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolschedulestarttime)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolscheduleendtime)."',
					'0',
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

	public function stc_call_school_student($schedule_id, $class_id){
		$odin='';
		$date=date("Y-m-d H:i:s");
		$begtime=date("H:i:s");
		$odin_teacherattuqry=mysqli_query($this->stc_dbs, "
			UPDATE `stc_school_teacher_attendance` 
			SET `stc_school_teacher_attendance_begtime`= '".mysqli_real_escape_string($this->stc_dbs, $begtime)."', `stc_school_teacher_attendance_status`='2' 
			WHERE `stc_school_teacher_attendance_status`='1' 
			AND `stc_school_teacher_attendance_createdby`='".$_SESSION['stc_school_user_id']."'
		");
		$odin_teacherattaqry=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_school_teacher_attendance`(
			    `stc_school_teacher_attendance_date`,
			    `stc_school_teacher_attendance_scheduleid`,
			    `stc_school_teacher_attendance_classid`,
			    `stc_school_teacher_attendance_begtime`,
			    `stc_school_teacher_attendance_status`,
			    `stc_school_teacher_attendance_createdate`,
			    `stc_school_teacher_attendance_createdby`
			)VALUES(
				'".mysqli_real_escape_string($this->stc_dbs, $date)."',
				'".mysqli_real_escape_string($this->stc_dbs, $schedule_id)."',
				'".mysqli_real_escape_string($this->stc_dbs, $class_id)."',
				'".mysqli_real_escape_string($this->stc_dbs, $begtime)."',
				'1',
				'".mysqli_real_escape_string($this->stc_dbs, $date)."',
				'".$_SESSION['stc_school_user_id']."'
			)
		");
		$odin_stuqry=mysqli_query($this->stc_dbs, "
			SELECT
			    `stc_school_student_id`,
			    `stc_school_student_studid`,
			    `stc_school_class_id`,
			    `stc_school_subject_id`,
			    `stc_school_student_firstname`,
			    `stc_school_student_lastname`,
			    `stc_school_class_title`,
			    `stc_school_subject_title`
			FROM
			    `stc_school_student`
			LEFT JOIN
			    `stc_school_teacher_schedule`
			ON
			    `stc_school_student_classroomid`=`stc_school_teacher_schedule_classid`
			LEFT JOIN
			    `stc_school_class`
			ON
			    `stc_school_class_id`=`stc_school_teacher_schedule_classid`
			LEFT JOIN
			    `stc_school_subject`
			ON
			    `stc_school_subject_id`=`stc_school_teacher_schedule_subjectid`
			WHERE
			    `stc_school_teacher_schedule_id`='".mysqli_real_escape_string($this->stc_dbs, $schedule_id)."'
		");
		if(mysqli_num_rows($odin_stuqry)>0){
			foreach($odin_stuqry as $odin_sturow){
				$check_rec=mysqli_query($this->stc_dbs, "
					SELECT `stc_school_student_attendance_id` FROM`stc_school_student_attendance` 
					WHERE `stc_school_student_attendance_status`='3' 
					AND `stc_school_student_attendance_stuid`='".$odin_sturow['stc_school_student_id']."'
					AND `stc_school_student_attendance_classid`='".$odin_sturow['stc_school_class_id']."'
					AND `stc_school_student_attendance_subid`='".$odin_sturow['stc_school_subject_id']."'
					AND `stc_school_student_attendance_createdby`='".$_SESSION['stc_school_user_id']."'
				");
				$updatebtn='#';
				if(mysqli_num_rows($check_rec)==0){	
					$updatebtn='
							<a href="#" class="btn btn-success stc-school-student-att-save" subid="'.$odin_sturow['stc_school_subject_id'].'" classid="'.$odin_sturow['stc_school_class_id'].'" id="'.$odin_sturow['stc_school_student_id'].'">Update</a> 
						';
				}
				$odin.='
					<tr>
						<td class="text-center"><b>'.$odin_sturow['stc_school_student_studid'].'</b></td>
						<td class="text-left"><b>'.$odin_sturow['stc_school_student_firstname'].' '.$odin_sturow['stc_school_student_lastname'].'</b></td>
						<td class="text-center"><b>'.$odin_sturow['stc_school_class_title'].'</b></td>
						<td class="text-center"><b>'.$odin_sturow['stc_school_subject_title'].'</b></td>
						<td>
							<input type="radio" name="stu-attendancecombo'.$odin_sturow['stc_school_student_id'].'" class="stc-attend-check stc-school-stu-attendance-but'.$odin_sturow['stc_school_student_id'].'" id="p'.$odin_sturow['stc_school_student_id'].'" value="1" checked> 
							<label for="p'.$odin_sturow['stc_school_student_id'].'">Present</label>
						</td>
						<td>
							<input type="radio" name="stu-attendancecombo'.$odin_sturow['stc_school_student_id'].'" class="stc-attend-check stc-school-stu-attendance-but'.$odin_sturow['stc_school_student_id'].'" id="a'.$odin_sturow['stc_school_student_id'].'" value="0"> 
							<label for="a'.$odin_sturow['stc_school_student_id'].'">Absent</label>
						</td>
						<td>
							<input type="number" class="stc-school-stu-attendance-hw'.$odin_sturow['stc_school_student_id'].' form-control" min="0" max="100" id="'.$odin_sturow['stc_school_student_id'].'" placeholder="Type here.."> 
						</td>
						<td>'.$updatebtn.'</td>
					</tr>
				';
			}
		}else{
			$odin='
				<tr>
					<td colspan="5">No data found!!!</td>
				</tr>
			';
		}
		return $odin;
	}

	public function stc_call_school_student_default($schedule_id, $class_id){
		$odin='';
		$odin_stuqry=mysqli_query($this->stc_dbs, "
			SELECT
			    `stc_school_student_id`,
			    `stc_school_student_studid`,
			    `stc_school_class_id`,
			    `stc_school_subject_id`,
			    `stc_school_student_firstname`,
			    `stc_school_student_lastname`,
			    `stc_school_class_title`,
			    `stc_school_subject_title`
			FROM
			    `stc_school_student`
			LEFT JOIN
			    `stc_school_teacher_schedule`
			ON
			    `stc_school_student_classroomid`=`stc_school_teacher_schedule_classid`
			LEFT JOIN
			    `stc_school_class`
			ON
			    `stc_school_class_id`=`stc_school_teacher_schedule_classid`
			LEFT JOIN
			    `stc_school_subject`
			ON
			    `stc_school_subject_id`=`stc_school_teacher_schedule_subjectid`
			WHERE
			    `stc_school_teacher_schedule_id`='".mysqli_real_escape_string($this->stc_dbs, $schedule_id)."'
		");
		if(mysqli_num_rows($odin_stuqry)>0){
			foreach($odin_stuqry as $odin_sturow){
				$check_rec=mysqli_query($this->stc_dbs, "
					SELECT `stc_school_student_attendance_id` FROM`stc_school_student_attendance` 
					WHERE `stc_school_student_attendance_status`='3' 
					AND `stc_school_student_attendance_stuid`='".$odin_sturow['stc_school_student_id']."'
					AND `stc_school_student_attendance_classid`='".$odin_sturow['stc_school_class_id']."'
					AND `stc_school_student_attendance_subid`='".$odin_sturow['stc_school_subject_id']."'
					AND `stc_school_student_attendance_createdby`='".$_SESSION['stc_school_user_id']."'
				");
				$updatebtn='#';
				if(mysqli_num_rows($check_rec)==0){	
					$updatebtn='
							<a href="#" class="btn btn-success stc-school-student-att-save" subid="'.$odin_sturow['stc_school_subject_id'].'" classid="'.$odin_sturow['stc_school_class_id'].'" id="'.$odin_sturow['stc_school_student_id'].'">Update</a> 
						';
				}
				$odin.='
					<tr>
						<td class="text-center"><b>'.$odin_sturow['stc_school_student_studid'].'</b></td>
						<td class="text-left"><b>'.$odin_sturow['stc_school_student_firstname'].' '.$odin_sturow['stc_school_student_lastname'].'</b></td>
						<td class="text-center"><b>'.$odin_sturow['stc_school_class_title'].'</b></td>
						<td class="text-center"><b>'.$odin_sturow['stc_school_subject_title'].'</b></td>
						<td>
							<input type="radio" name="stu-attendancecombo'.$odin_sturow['stc_school_student_id'].'" class="stc-attend-check stc-school-stu-attendance-but'.$odin_sturow['stc_school_student_id'].'" id="p'.$odin_sturow['stc_school_student_id'].'" value="1" checked> 
							<label for="p'.$odin_sturow['stc_school_student_id'].'">Present</label>
						</td>
						<td>
							<input type="radio" name="stu-attendancecombo'.$odin_sturow['stc_school_student_id'].'" class="stc-attend-check stc-school-stu-attendance-but'.$odin_sturow['stc_school_student_id'].'" id="a'.$odin_sturow['stc_school_student_id'].'" value="0"> 
							<label for="a'.$odin_sturow['stc_school_student_id'].'">Absent</label>
						</td>
						<td>
							<input type="number" class="stc-school-stu-attendance-hw'.$odin_sturow['stc_school_student_id'].' form-control" min="0" max="100" id="'.$odin_sturow['stc_school_student_id'].'" placeholder="Type here.."> 
						</td>
						<td>'.$updatebtn.'</td>
					</tr>
				';
			}
		}else{
			$odin='
				<tr>
					<td colspan="5">No data found!!!</td>
				</tr>
			';
		}
		return $odin;
	}

	public function stc_call_school_lecturer_end(){
		$odin='';
		$endtime=date("H:i:s");
		$odin_teacherattuqry=mysqli_query($this->stc_dbs, "
			UPDATE `stc_school_teacher_attendance` 
			SET `stc_school_teacher_attendance_endtime`= '".mysqli_real_escape_string($this->stc_dbs, $endtime)."', `stc_school_teacher_attendance_status`='2' 
			WHERE `stc_school_teacher_attendance_status`='1' 
			AND `stc_school_teacher_attendance_createdby`='".$_SESSION['stc_school_user_id']."'
		");
		$odin_lectureuqry=mysqli_query($this->stc_dbs, "
			UPDATE `stc_school_lecture` 
			SET `stc_school_lecture_status`='2' 
			WHERE `stc_school_lecture_status`='3' 
			AND `stc_school_lecture_createdby`='".$_SESSION['stc_school_user_id']."'
		");
		$odin_teacherattuqry=mysqli_query($this->stc_dbs, "
			UPDATE `stc_school_student_attendance` 
			SET `stc_school_student_attendance_status`='1' 
			WHERE `stc_school_student_attendance_status`='3' 
			AND `stc_school_student_attendance_createdby`='".$_SESSION['stc_school_user_id']."'
		");
		if($odin_teacherattuqry){
			$odin="success";
		}else{
			$odin="failed";
		}
		return $odin;
	}

	public function stc_call_school_student_save($stc_stid, $stc_stsubid, $stc_stclassid, $stc_sthwperc, $stc_stcatt){		
		$odin='';
		$date=date("Y-m-d H:i:s");
		$endtime=date("H:i:s");
		$odin_studentuqry=mysqli_query($this->stc_dbs, "
			UPDATE `stc_school_student_attendance` 
			SET `stc_school_student_attendance_hw`= '".mysqli_real_escape_string($this->stc_dbs, $stc_sthwperc)."', `stc_school_student_attendance_status`='1' 
			WHERE `stc_school_student_attendance_status`='2' 
			AND `stc_school_student_attendance_stuid`='".mysqli_real_escape_string($this->stc_dbs, $stc_stid)."'
			AND `stc_school_student_attendance_classid`='".mysqli_real_escape_string($this->stc_dbs, $stc_stclassid)."'
			AND `stc_school_student_attendance_subid`='".mysqli_real_escape_string($this->stc_dbs, $stc_stsubid)."'
			AND `stc_school_student_attendance_attendance`='1'
		");
		$odin_studentaqry=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_school_student_attendance`(
				`stc_school_student_attendance_date`,
				`stc_school_student_attendance_stuid`,
				`stc_school_student_attendance_classid`,
				`stc_school_student_attendance_subid`,
				`stc_school_student_attendance_attendance`,
				`stc_school_student_attendance_hw`,
				`stc_school_student_attendance_status`,
				`stc_school_student_attendance_createdate`,
				`stc_school_student_attendance_createdby`
			)VALUES(
				'".mysqli_real_escape_string($this->stc_dbs, $date)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_stid)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_stclassid)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_stsubid)."',
				'".mysqli_real_escape_string($this->stc_dbs, $stc_stcatt)."',
				'0',
				'3',
				'".mysqli_real_escape_string($this->stc_dbs, $date)."',
				'".$_SESSION['stc_school_user_id']."'
			)
		");
		if($odin_studentaqry){
			$odin="success";
		}else{
			$odin="failed";
		}
		return $odin;
	}

	public function stc_call_school_lecturedetails_save($schedule_id, $classtype, $chapter, $lession, $Syllabus, $remarks){
		$odin='';
		$date=date("Y-m-d H:i:s");
		$odin_schedulegqry=mysqli_query($this->stc_dbs, "
			SELECT
			    `stc_school_teacher_schedule_classid`,
			    `stc_school_teacher_schedule_subjectid`
			FROM
			    `stc_school_teacher_schedule`
			WHERE
			    `stc_school_teacher_schedule_id`='".mysqli_real_escape_string($this->stc_dbs, $schedule_id)."'
		");
		$classid=0;
		$subid=0;
		foreach($odin_schedulegqry as $odin_schedulegrow){
			$classid=$odin_schedulegrow['stc_school_teacher_schedule_classid'];
			$subid=$odin_schedulegrow['stc_school_teacher_schedule_subjectid'];
		}
		$odin_lectureiqry=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_school_lecture`(
				`stc_school_lecture_scheduleid`,
				`stc_school_lecture_classid`,
				`stc_school_lecture_subid`,
				`stc_school_lecture_classtype`,
				`stc_school_lecture_chapter`,
				`stc_school_lecture_lesson`,
				`stc_school_lecture_syllabus`,
				`stc_school_lecture_remarks`,
				`stc_school_lecture_status`,
				`stc_school_lecture_createdate`,
				`stc_school_lecture_createdby`
			)VALUES(
				'".mysqli_real_escape_string($this->stc_dbs, $schedule_id)."',
				'".mysqli_real_escape_string($this->stc_dbs, $classid)."',
				'".mysqli_real_escape_string($this->stc_dbs, $subid)."',
				'".mysqli_real_escape_string($this->stc_dbs, $classtype)."',
				'".mysqli_real_escape_string($this->stc_dbs, $chapter)."',
				'".mysqli_real_escape_string($this->stc_dbs, $lession)."',
				'".mysqli_real_escape_string($this->stc_dbs, $Syllabus)."',
				'".mysqli_real_escape_string($this->stc_dbs, $remarks)."',
				'2',
				'".mysqli_real_escape_string($this->stc_dbs, $date)."',
				'".$_SESSION['stc_school_user_id']."'
			)
		");
		if($odin_lectureiqry){
			$odin='success';
		}else{
			$odin='failed';
		}
		return $odin;
	}

	public function stc_call_school_lecturedetailsquestion_save($schedule_id, $questions){
		$odin='';
		$date=date("Y-m-d H:i:s");
		$odin_schedulegqry=mysqli_query($this->stc_dbs, "
			SELECT
			    `stc_school_teacher_schedule_classid`,
			    `stc_school_teacher_schedule_subjectid`
			FROM
			    `stc_school_teacher_schedule`
			WHERE
			    `stc_school_teacher_schedule_id`='".mysqli_real_escape_string($this->stc_dbs, $schedule_id)."'
		");
		$classid=0;
		$subid=0;
		foreach($odin_schedulegqry as $odin_schedulegrow){
			$classid=$odin_schedulegrow['stc_school_teacher_schedule_classid'];
			$subid=$odin_schedulegrow['stc_school_teacher_schedule_subjectid'];
		}
		$odin_lecturegqry=mysqli_query($this->stc_dbs, "
			SELECT
			    `stc_school_lecture_id`
			FROM
			    `stc_school_lecture`
			WHERE
			    `stc_school_lecture_status`='2'
			AND 
				`stc_school_lecture_classid`='".$classid."'
			AND 
				`stc_school_lecture_subid`='".$subid."'
			AND 
				`stc_school_lecture_createdby`='".$_SESSION['stc_school_user_id']."'
		");
		$lecture_id=0;
		foreach($odin_lecturegqry as $odin_lecturegrow){
			$lecture_id=$odin_lecturegrow['stc_school_lecture_id'];
		}
		$odin_lectureiqry=mysqli_query($this->stc_dbs, "
			INSERT INTO `stc_school_lecture_question`(
				`stc_school_lecture_question_lectureid`,
				`stc_school_lecture_question_scheduleid`,
				`stc_school_lecture_question_classid`,
				`stc_school_lecture_question_subid`,
				`stc_school_lecture_question_question`,
				`stc_school_lecture_question_status`,
				`stc_school_lecture_question_createdate`,
				`stc_school_lecture_question_createdby`
			)VALUES(
				'".mysqli_real_escape_string($this->stc_dbs, $lecture_id)."',
				'".mysqli_real_escape_string($this->stc_dbs, $schedule_id)."',
				'".mysqli_real_escape_string($this->stc_dbs, $classid)."',
				'".mysqli_real_escape_string($this->stc_dbs, $subid)."',
				'".mysqli_real_escape_string($this->stc_dbs, $questions)."',
				'1',
				'".mysqli_real_escape_string($this->stc_dbs, $date)."',
				'".$_SESSION['stc_school_user_id']."'
			)
		");
		if($odin_lectureiqry){
			$odin='success';
		}else{
			$odin='failed';
		}
		return $odin;
	}

	public function stc_call_syllabus_det($schedule_id){
		$odin='';
		$odinqry=mysqli_query($this->stc_dbs, "
			SELECT
			    `stc_school_lecture_id`,
			    `stc_school_lecture_createdate`,
			    `stc_school_lecture_classtype`,
			    `stc_school_lecture_chapter`,
			    `stc_school_lecture_lesson`,
			    `stc_school_lecture_syllabus`
			FROM
			    `stc_school_lecture`
			WHERE
			    `stc_school_lecture_scheduleid`='".mysqli_real_escape_string($this->stc_dbs, $schedule_id)."'
			ORDER BY 
				DATE(`stc_school_lecture_createdate`) 
			DESC LIMIT 0,5
		");
		if(mysqli_num_rows($odinqry)>0){
			$checked='checked';
			foreach($odinqry as $odinrow){
				$odin.='
					<tr>
						<td for="'.$odinrow['stc_school_lecture_id'].'">
							<input type="radio" name="syllabus_det" '.$checked.' class="stc-syllabus-out" value="'.$odinrow['stc_school_lecture_id'].'" id="'.$odinrow['stc_school_lecture_id'].'">
						</td>
						<td class="text-center">'.date('d-m-Y', strtotime($odinrow['stc_school_lecture_createdate'])).'</td>
						<td class="text-center">'.$odinrow['stc_school_lecture_classtype'].'</td>
						<td class="text-center">'.$odinrow['stc_school_lecture_chapter'].'</td>
						<td class="text-center">'.$odinrow['stc_school_lecture_lesson'].'</td>
						<td class="text-center">'.$odinrow['stc_school_lecture_syllabus'].'</td>
					</tr>
				';
				$checked='';
			}
		}else{
			$odin.='
				<tr><td colspan="4">No records found.</td></tr>
			';
		}
		return $odin;
	}

	public function stc_call_syllabus_quest($question_id){
		$odin='';
		$odinqry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_school_lecture_question_question`
			FROM
				`stc_school_lecture_question`
			WHERE
				`stc_school_lecture_question_lectureid`='".mysqli_real_escape_string($this->stc_dbs, $question_id)."'
			ORDER BY `stc_school_lecture_question_question` ASC
		");
		if(mysqli_num_rows($odinqry)>0){
			$sl=0;
			foreach($odinqry as $odinrow){
				$sl++;
				$odin.='
					<tr>
						<td class="text-center">'.$sl.'</td>
						<td>'.$odinrow['stc_school_lecture_question_question'].'</td>
					</tr>
				';
			}
		}else{
			$odin.='
				<tr><td colspan="4">No records found.</td></tr>
			';
		}
		return $odin;
	}

	public function stc_call_records(){
		$odinteacherqry=mysqli_query($this->stc_dbs, "
			SELECT
			    `stc_school_teacher_userid`,
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
			    `stc_school_user_fullName`
			FROM
			    `stc_school_teacher`
			LEFT JOIN
			    `stc_school`
			ON
			    `stc_school_user_id`=`stc_school_teacher_createdby`
			ORDER BY `stc_school_teacher_firstname` ASC
		");
		$teacher_records='';
		if(mysqli_num_rows($odinteacherqry)>0){
			$slno=0;
			foreach($odinteacherqry as $row){
				$slno++;
				$teacher_records.='
					<tr>
						<td class="text-center">'.$slno.'</td>
						<td>'.$row['stc_school_teacher_firstname'].' '.$row['stc_school_teacher_lastname'].'</td>
						<td>'.date('d-m-Y', strtotime($row['stc_school_teacher_dob'])).'</td>
						<td>'.$row['stc_school_teacher_gender'].'</td>
						<td>'.$row['stc_school_teacher_bloodgroup'].'</td>
						<td>'.$row['stc_school_teacher_email'].'</td>
						<td>'.$row['stc_school_teacher_contact'].'</td>
						<td>'.$row['stc_school_teacher_address'].'</td>
						<td>'.$row['stc_school_teacher_skills'].'</td>
						<td>'.$row['stc_school_teacher_religion'].'</td>
						<td>'.date('d-m-Y', strtotime($row['stc_school_teacher_joindate'])).'</td>
						<td>'.$row['stc_school_teacher_remarks'].'</td>
					</tr>
				';
			}
		}

		$odinstudentqry=mysqli_query($this->stc_dbs, "
			SELECT
			    `stc_school_student_id`,
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
			    `stc_school_student_admissionno`,
			    `stc_school_student_admissiondate`,
			    `stc_school_student_classroomid`,
			    `stc_school_class_title`,
			    `stc_school_student_admissionclass`,
			    `stc_school_student_guardianname`,
			    `stc_school_student_remarks`,
			    `stc_school_student_status`,
			    `stc_school_student_createdate`,
			    `stc_school_user_fullName`
			FROM
			    `stc_school_student`
			LEFT JOIN
			    `stc_school`
			ON
			    `stc_school_user_id`=`stc_school_student_createdby`
			LEFT JOIN
			    `stc_school_class`
			ON
			    `stc_school_class_id`=`stc_school_student_classroomid`
			ORDER BY `stc_school_student_firstname` ASC
		");
		$student_records='';
		if(mysqli_num_rows($odinstudentqry)>0){
			$slno=0;
			foreach($odinstudentqry as $row){
				$slno++;
				$student_records.='
					<tr>
						<td class="text-center">'.$slno.'</td>
						<td>'.$row['stc_school_student_firstname'].' '.$row['stc_school_student_lastname'].'</td>
						<td>'.date('d-m-Y', strtotime($row['stc_school_student_dob'])).'</td>
						<td>'.$row['stc_school_student_gender'].'</td>
						<td>'.$row['stc_school_student_bloodgroup'].'</td>
						<td>'.$row['stc_school_student_email'].'</td>
						<td>'.$row['stc_school_student_contact'].'</td>
						<td>'.$row['stc_school_student_address'].'</td>
						<td>'.$row['stc_school_student_religion'].'</td>
						<td>'.date('d-m-Y', strtotime($row['stc_school_student_admissiondate'])).'</td>
						<td>'.$row['stc_school_class_title'].'</td>
						<td>'.$row['stc_school_student_guardianname'].'</td>
						<td>'.$row['stc_school_student_remarks'].'</td>
						<td>'.$row['stc_school_student_remarks'].'</td>
					</tr>
				';
			}
		}

		$odinsubjectqry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_school_subject_id`,
			    `stc_school_subject_title`,
			    `stc_school_subject_subid`,
			    `stc_school_subject_syllabusdetails`,
			    `stc_school_subject_status`,
			    `stc_school_subject_createdate`,
			    `stc_school_user_fullName`
			FROM
			    `stc_school_subject`
			LEFT JOIN
			    `stc_school`
			ON
			    `stc_school_user_id`=`stc_school_subject_createdby`
			ORDER BY `stc_school_subject_title` ASC
		");
		$subject_records='';
		if(mysqli_num_rows($odinsubjectqry)>0){
			$slno=0;
			foreach($odinsubjectqry as $row){
				$slno++;
				$subject_records.='
					<tr>
						<td class="text-center">'.$row['stc_school_subject_id'].'</td>
						<td>'.$row['stc_school_subject_title'].'</td>
						<td>'.$row['stc_school_subject_syllabusdetails'].'</td>
						<td>'.date('d-m-Y', strtotime($row['stc_school_subject_createdate'])).'</td>
						<td>'.$row['stc_school_user_fullName'].'</td>
					</tr>
				';
			}
		}

		$odinclassqry=mysqli_query($this->stc_dbs, "
			SELECT
			    `stc_school_class_id`,
			    `stc_school_class_title`,
			    `stc_school_class_classid`,
			    `stc_school_class_location`,
			    `stc_school_class_capacity`,
			    `stc_school_class_status`,
			    `stc_school_class_createdate`,
			    `stc_school_user_fullName`
			FROM
			    `stc_school_class`
			LEFT JOIN
			    `stc_school`
			ON
			    `stc_school_user_id`=`stc_school_class_createdby`
			ORDER BY `stc_school_class_title` ASC
		");
		$class_records='';
		if(mysqli_num_rows($odinclassqry)>0){
			$slno=0;
			foreach($odinclassqry as $row){
				$slno++;
				$class_records.='
					<tr>
						<td class="text-center">'.$row['stc_school_class_id'].'</td>
						<td>'.$row['stc_school_class_title'].'
							<input type="text" class="stc-class-title-upd" value="'.$row['stc_school_class_title'].'" style="display:none">
						</td>
						<td>'.$row['stc_school_class_location'].'
							<input type="text" class="stc-class-location-upd" value="'.$row['stc_school_class_location'].'" style="display:none">
						</td>
						<td>'.$row['stc_school_class_capacity'].'
							<input type="text" class="stc-class-capacity-upd" value="'.$row['stc_school_class_capacity'].'" style="display:none">
						</td>
						<td>'.date('d-m-Y', strtotime($row['stc_school_class_createdate'])).'</td>
						<td>'.$row['stc_school_user_fullName'].'</td>
						<td>
							<a href="javascript:void(0)" class=" btn btn-default stc-class-upd-btn-show">Update</a>
							<a href="javascript:void(0)" class=" btn btn-primary stc-class-upd-btn-save" style="display:none">Save</a>
						</td>
					</tr>
				';
			}
		}
		
		$odin['status']="success";
		$odin['response_teacher']=$teacher_records;
		$odin['response_student']=$student_records;
		$odin['response_subject']=$subject_records;
		$odin['response_class']=$class_records;
		return $odin;
	}

	public function stc_call_schedule($day){
		$schedule_records='';
    	$odinclassqry=mysqli_query($this->stc_dbs, "
    		SELECT
			    `stc_school_class_id`,
			    `stc_school_class_title`,
			    `stc_school_class_classid`,
			    `stc_school_class_location`,
			    `stc_school_class_capacity`,
			    `stc_school_class_status`,
			    `stc_school_class_createdate`,
			    `stc_school_class_createdby`
			FROM
			    `stc_school_class`
			ORDER BY `stc_school_class_title` ASC
    	");
    	$teacher_name=array();
    	$schedule_id=array();
    	foreach($odinclassqry as $row){
    		$data='';
    		$class_id=$row['stc_school_class_id'];

	    	$odinscheduleqrycounter=mysqli_query($this->stc_dbs, "
	    		SELECT
				    `stc_school_teacher_schedule_id`,
				    `stc_school_class_title`,
				    `stc_school_subject_title`,
				    `stc_school_teacher_firstname`,
				    `stc_school_teacher_lastname`,
				    hour(`stc_school_teacher_schedule_begtime`) as begtime,
				    hour(`stc_school_teacher_schedule_endtime`) as endtime,
				    `stc_school_teacher_schedule_begtime`,
				    `stc_school_teacher_schedule_endtime`,
	    			`stc_school_teacher_schedule_day`,
	    			`stc_school_teacher_schedule_period`
				FROM
				    `stc_school_teacher_schedule`
				LEFT JOIN 
					`stc_school_class` 
				ON 
					`stc_school_class_id`=`stc_school_teacher_schedule_classid` 
				LEFT JOIN 
					`stc_school_subject` 
				ON 
					`stc_school_subject_id`=`stc_school_teacher_schedule_subjectid` 
				LEFT JOIN 
					`stc_school_teacher` 
				ON 
					`stc_school_teacher_id`=`stc_school_teacher_schedule_teacherid`
				WHERE
				    `stc_school_teacher_schedule_day`='".$day."'
                AND
                	`stc_school_teacher_schedule_classid`='".$class_id."'
                ORDER BY `stc_school_teacher_schedule_period` ASC
	    	");
	    	$routinecounter=0;
	    	$boxcounter=0;
	    	for($i=0;$i<7;$i++){
	    		$routinecounter++;
	    		$tracker=1;
	    		foreach($odinscheduleqrycounter as $odinscheduleqrycounterrow){
	    			if($routinecounter==$odinscheduleqrycounterrow['stc_school_teacher_schedule_period']){
	    				$boxcounter++;
	    				$teacher_title = $odinscheduleqrycounterrow['stc_school_teacher_firstname'].'-'.$odinscheduleqrycounterrow['stc_school_teacher_lastname'];
	    				array_push($teacher_name, $teacher_title);
	    				$data.='
							<td title="Click to remove schedule" class="text-center schedule-box box-rep-'.$teacher_title.'">
								<div class="remove icon"></div>
								<a href="javascript:void(0)" class="stc-remove-schedule-btn" id="'.$odinscheduleqrycounterrow['stc_school_teacher_schedule_id'].'">
									'.$odinscheduleqrycounterrow['stc_school_subject_title'].'<br>
									'.$odinscheduleqrycounterrow['stc_school_teacher_firstname'].' 
									'.$odinscheduleqrycounterrow['stc_school_teacher_lastname'].'<br>
				    				'.date('h:i', strtotime($odinscheduleqrycounterrow['stc_school_teacher_schedule_begtime'])).' - 
				    				'.date('h:i', strtotime($odinscheduleqrycounterrow['stc_school_teacher_schedule_endtime'])).'
			    				</a>
							</td>
						';
						$tracker=1;
						break;
	    			}else{
	    				$tracker=0;
	    			}
	    		}
	    		if($tracker==0){
	    			if($boxcounter<7){
	    				$data.='<td class="schedule-show-na">NA</td>';
	    				$boxcounter++;
	    			}
	    		}
	    	}
	    	$schedule_records.='
					<tr>
						<td class="text-center"><b>'.$row['stc_school_class_title'].'</b></td>
						'.$data.'
					</tr>
			';

		}

		$teacher_name=array_unique($teacher_name);
		$display_teach=array();
		foreach($teacher_name as $teacher_namerow){
			$display_teach[]=$teacher_namerow;
		}
		$schedule_records.='
				<tr>
					<td class="text-center"><b>Teacher : </b></td>
					<td class="text-center" colspan="4">
		';
		for($j=0;$j<count($display_teach);$j++){
			$schedule_records.='
				<b><a href="javascript:void(0)" class="hover-box" title="Click here to hover all schedule from '.str_replace("-"," ",$display_teach[$j]).'" id="'.$display_teach[$j].'">'.ucwords(str_replace("-"," ",$display_teach[$j])).'</a></b>
			';
		}
		$schedule_records.='
					</td>
				</tr>
		';
		$odin['status']="success";
		$odin['response_schedule']=$schedule_records;
		return $odin;
	}
	
	public function stc_remove_schedule($sched_id){
    	$odinclassqry=mysqli_query($this->stc_dbs, "
    		DELETE FROM `stc_school_teacher_schedule` WHERE `stc_school_teacher_schedule_id`='".mysqli_real_escape_string($this->stc_dbs, $sched_id)."'
    	");
    	$status="failed";
    	$message="Schedule not removed.";
    	if($odinclassqry){
	    	$status="success";
	    	$message="Schedule removed.";
    	}
		$odin['status']=$status;
		$odin['message']=$message;
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

// save schedule
if(isset($_POST['save_schduleadd_action'])){
	$stcschoolscheduleteacher =$_POST['stcschoolscheduleteacher'];
	$stcschoolschedulesubject =$_POST['stcschoolschedulesubject'];
	$stcschoolscheduleclass =$_POST['stcschoolscheduleclass'];
	$stcschoolscheduleday = $_POST['stcschoolscheduleday'];
	$stcschoolschedulestarttime =$_POST['stcschoolschedulestarttime'];
	$stcschoolscheduleendtime = $_POST['stcschoolscheduleendtime'];
	$stcschoolscheduleperiod = $_POST['stcschoolscheduleperiod'];

	$valkyrie=new Yggdrasil();
	if(empty($_SESSION['stc_school_user_id'])){
		$out="reload";
	}else if($stcschoolscheduleteacher=="NA" || $stcschoolschedulesubject=="NA" || $stcschoolscheduleclass=="NA" || $stcschoolscheduleday=="NA"){
		$out="empty";
	}else{		
		$lokiheck=$valkyrie->stc_save_school_schedule($stcschoolscheduleteacher, $stcschoolschedulesubject, $stcschoolscheduleclass, $stcschoolscheduleday, $stcschoolschedulestarttime, $stcschoolscheduleendtime, $stcschoolscheduleperiod);
		$out=$lokiheck;
	}
	echo $out;
}

// call student for attendance
if(isset($_POST['stc_call_student'])){
	$schedule_id=$_POST['schedule_id'];
	$class_id=$_POST['class_id'];
	$valkyrie=new Yggdrasil();
	$lokiheck=$valkyrie->stc_call_school_student($schedule_id, $class_id);
	echo $lokiheck;
}

// call student for attendance default
if(isset($_POST['stc_call_student_default'])){
	$schedule_id=$_POST['schedule_id'];
	$class_id=$_POST['class_id'];
	$valkyrie=new Yggdrasil();
	$lokiheck=$valkyrie->stc_call_school_student_default($schedule_id, $class_id);
	echo $lokiheck;
}

// end lecturer from teacher
if(isset($_POST['stc_call_lecture_end'])){
	$class_id=$_POST['class_id'];
	$out='';
	if(empty($_SESSION['stc_school_user_id'])){
		$out="reload";
	}else{
		$valkyrie=new Yggdrasil();
		$out=$valkyrie->stc_call_school_lecturer_end();
	}
	echo $out;
}

// save student attendance
if(isset($_POST['stc_student_save'])){
	$stc_stid=$_POST['stc_stid'];
	$stc_stsubid=$_POST['stc_stsubid'];
	$stc_stclassid=$_POST['stc_stclassid'];
	$stc_sthwperc=$_POST['stc_sthwperc'];
	$stc_stcatt=$_POST['stc_stcatt'];
	$out='';
	if(empty($_SESSION['stc_school_user_id'])){
		$out="reload";
	}else{
		$valkyrie=new Yggdrasil();
		$out=$valkyrie->stc_call_school_student_save($stc_stid, $stc_stsubid, $stc_stclassid, $stc_sthwperc, $stc_stcatt);
	}
	echo $out;
}

// save lecture details
if(isset($_POST['stc_lecturedet_save'])){
	$schedule_id=$_POST['schedule_id'];
	$classtype=$_POST['classtype'];
	$chapter=$_POST['chapter'];
	$lession=$_POST['lession'];
	$Syllabus=$_POST['Syllabus'];
	$remarks=$_POST['remarks'];
	$out='';
	if(empty($_SESSION['stc_school_user_id'])){
		$out="reload";
	}elseif(($classtype=='NA') || (empty($chapter)) || (empty($lession)) || (empty($Syllabus))){
		$out="empty";
	}else{
		$valkyrie=new Yggdrasil();
		$out=$valkyrie->stc_call_school_lecturedetails_save($schedule_id, $classtype, $chapter, $lession, $Syllabus, $remarks);
	}
	echo $out;
}

// save lecture details
if(isset($_POST['stc_lecturedetquestion_save'])){
	$schedule_id=$_POST['schedule_id'];
	$questions=$_POST['questions'];
	$out='';
	if(empty($_SESSION['stc_school_user_id'])){
		$out="reload";
	}elseif(empty($questions)){
		$out="empty";
	}else{
		$valkyrie=new Yggdrasil();
		$out=$valkyrie->stc_call_school_lecturedetailsquestion_save($schedule_id, $questions);
	}
	echo $out;
}

// call syllabus details
if(isset($_POST['stc_syllabusdet_call'])){
	$schedule_id=$_POST['schedule_id'];
	$out='';
	if(empty($_SESSION['stc_school_user_id'])){
		$out="reload";
	}else{
		$valkyrie=new Yggdrasil();
		$out=$valkyrie->stc_call_syllabus_det($schedule_id);
	}
	echo $out;
}

// call syllabus questions
if(isset($_POST['stc_syllabusquest_call'])){
	$question_id=$_POST['question_id'];
	$out='';
	if(empty($_SESSION['stc_school_user_id'])){
		$out="reload";
	}else{
		$valkyrie=new Yggdrasil();
		$out=$valkyrie->stc_call_syllabus_quest($question_id);
	}
	echo $out;
}

// call records
if(isset($_POST['stc_load_record_action'])){
	$out=array();
	if(empty($_SESSION['stc_school_user_id'])){
		$out['reload']="reload";
	}else{
		$valkyrie=new Yggdrasil();
		$out=$valkyrie->stc_call_records();
	}
	echo json_encode($out);
}

// call schedule
if(isset($_POST['stc_load_schedule_action'])){
	$day=$_POST['day'];
	$out=array();
	if(empty($_SESSION['stc_school_user_id'])){
		$out['reload']="reload";
	}else{
		$valkyrie=new Yggdrasil();
		$out=$valkyrie->stc_call_schedule($day);
	}
	echo json_encode($out);
}

// call schedule
if(isset($_POST['stc_remove_schedule_action'])){
	$sched_id=$_POST['sched_id'];
	$out=array();
	if(empty($_SESSION['stc_school_user_id'])){
		$out['reload']="reload";
	}else{
		$valkyrie=new Yggdrasil();
		$out=$valkyrie->stc_remove_schedule($sched_id);
	}
	echo json_encode($out);
}

?>
