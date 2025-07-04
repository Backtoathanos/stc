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
			SELECT `stc_school_teacher_id` 
			FROM `stc_school_teacher` 
			WHERE `stc_school_teacher_teachid`='".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementteacherid)."' 
			OR `stc_school_teacher_email`='".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementteacheremail)."' 
			OR `stc_school_teacher_contact` = '".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementteachernumber)."'
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
					SELECT `stc_school_user_id` 
					FROM `stc_school` 
					WHERE `stc_school_user_contact`='".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementteachernumber)."'
				");
				$userid=0;
				foreach($get_teachid as $get_teachrow){
					$userid=$get_teachrow['stc_school_user_id'];
				}
				$update_teach=mysqli_query($this->stc_dbs, "
					UPDATE `stc_school_teacher` 
					SET `stc_school_teacher_userid`='".mysqli_real_escape_string($this->stc_dbs, $userid)."' 
					WHERE `stc_school_teacher_contact`='".mysqli_real_escape_string($this->stc_dbs, $stcschoolmanagementteachernumber)."'
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
	
	public function stc_save_school_syllabus($stc_subject_id, $stc_title, $stc_chapter, $stc_lesson, $stc_unit, $stc_date){
		$odin='';
		$date=date("Y-m-d H:i:s");
		$check_qry=mysqli_query($this->stc_dbs, "
			SELECT `stc_school_syllabus_id` FROM `stc_school_syllabus` 
			WHERE 
				`stc_school_syllabus_subject_id`='".mysqli_real_escape_string($this->stc_dbs, $stc_subject_id)."' AND 
				`stc_school_syllabus_title`='".mysqli_real_escape_string($this->stc_dbs, $stc_title)."'
		");
		if(mysqli_num_rows($check_qry)>0){
			$odin = "duplicate";
		}else{
			$set_loki=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_school_syllabus`(
				    `stc_school_syllabus_subject_id`,
				    `stc_school_syllabus_title`,
				    `stc_school_syllabus_chapter`,
				    `stc_school_syllabus_lesson`,
				    `stc_school_syllabus_unit`,
				    `stc_school_syllabus_targetdate`,
				    `stc_school_syllabus_status`,
				    `stc_school_syllabus_created_date`,
				    `stc_school_syllabus_created_by`
				)VALUES(
					'".mysqli_real_escape_string($this->stc_dbs, $stc_subject_id)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_title)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_chapter)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_lesson)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_unit)."',
					'".mysqli_real_escape_string($this->stc_dbs, $stc_date)."',
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

	public function stc_save_school_schedule($stcschoolscheduletype, $stcschoolscheduleteacher, $stcschoolschedulesubject, $stcschoolscheduleclass, $stcschoolscheduleday, $stcschoolschedulestarttime, $stcschoolscheduleendtime, $stcschoolscheduleperiod){
		$odin='';
		$date=date("Y-m-d H:i:s");
		$check_qry=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_school_teacher_schedule_id` 
			FROM `stc_school_teacher_schedule` 
			WHERE 
				`stc_school_teacher_schedule_classtype`='".mysqli_real_escape_string($this->stc_dbs, $stcschoolscheduletype)."' AND 
				`stc_school_teacher_schedule_classid`='".mysqli_real_escape_string($this->stc_dbs, $stcschoolscheduleclass)."' AND 
				`stc_school_teacher_schedule_day`='".mysqli_real_escape_string($this->stc_dbs, $stcschoolscheduleday)."' AND 
				`stc_school_teacher_schedule_period`='".mysqli_real_escape_string($this->stc_dbs, $stcschoolscheduleperiod)."'
		");
		if(mysqli_num_rows($check_qry)>0){
			$odin = "duplicate";
		}else{
			$set_loki=mysqli_query($this->stc_dbs, "
				INSERT INTO `stc_school_teacher_schedule`(
					`stc_school_teacher_schedule_classtype`,
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
					'".mysqli_real_escape_string($this->stc_dbs, $stcschoolscheduletype)."',
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

	public function stc_call_teacher_schedule($type){
		$odin='';
		date_default_timezone_set('Asia/Kolkata');
    	$day_array=array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
    	$check_attendanceforsc=mysqli_query($this->stc_dbs, "
    		SELECT `stc_school_teacher_attendance_scheduleid` FROM `stc_school_teacher_attendance` 
    		WHERE `stc_school_teacher_attendance_status`=1
    		AND `stc_school_teacher_attendance_createdby`='".$_SESSION['stc_school_user_id']."'
    	");
    	$att_counter=0;
    	$schedule_id=0;
    	if(mysqli_num_rows($check_attendanceforsc)>0){
    		$att_counter++;
    		foreach($check_attendanceforsc as $check_attendanceforscrow){
    			$schedule_id=$check_attendanceforscrow['stc_school_teacher_attendance_scheduleid'];
    		}
    	}
    	$odinclassqry=mysqli_query($this->stc_dbs, "
    		SELECT `stc_school_teacher_attendance_scheduleid` FROM `stc_school_teacher_attendance` 
    		WHERE `stc_school_teacher_attendance_status`=1
    		AND `stc_school_teacher_attendance_createdby`='".$_SESSION['stc_school_user_id']."'
    	");
    	
    	foreach($day_array as $day){
    		$tr_color='';
    		$counter=0;
    		$periods='';
    		$days=date("l");
			$range=$type>1 ? 3 : 7;
    		for($i=0;$i<$range;$i++){
    			$counter++;
				$type_qry='';
				if($type>0){
					$type_qry="
    				AND `stc_school_teacher_schedule_classtype`='".mysqli_real_escape_string($this->stc_dbs, $type)."'";
				}
    			$query="
    				SELECT
    				  `stc_school_class_title`,
    				  `stc_school_teacher_schedule_id`,
    				  `stc_school_subject_title`,
    				  `stc_school_teacher_schedule_day`,
    				  `stc_school_teacher_schedule_classid`,
    				  `stc_school_teacher_schedule_subjectid`,
    				  `stc_school_teacher_schedule_begtime`,
    				  `stc_school_teacher_schedule_endtime` 
    				FROM `stc_school_teacher_schedule`
    				LEFT JOIN `stc_school_class`
    				ON `stc_school_teacher_schedule_classid`=`stc_school_class_id`
    				LEFT JOIN `stc_school_subject`
    				ON `stc_school_teacher_schedule_subjectid`=`stc_school_subject_id`
    				WHERE `stc_school_teacher_schedule_day`='".$day."'
    				AND `stc_school_teacher_schedule_teacherid`='".$_SESSION['stc_school_teacher_id']."'
    				AND `stc_school_teacher_schedule_period`='".$counter."' ".$type_qry."
    			";
    			$periodquery=mysqli_query($this->stc_dbs, $query);
    			if(mysqli_num_rows($periodquery)>0){
					foreach($periodquery as $period){
						
						$time=date('h:i');
						$time_flag="n";
						if($day==$days){
							$tr_color="style='background:#fdff32;'";
							// $time_flag = ($time > date('h:i', strtotime($period['stc_school_teacher_schedule_begtime']))) && ($time < date('h:i', strtotime($period['stc_school_teacher_schedule_endtime']))) ? "y" : "n";
							if(date('h', strtotime($period['stc_school_teacher_schedule_begtime']))=="12" && date('h', strtotime($period['stc_school_teacher_schedule_endtime']))=="1"){	
								$time_flag = "y";
							}else{	
								if(
									($time > date('h:i', strtotime($period['stc_school_teacher_schedule_begtime'])) && 
									$time < date('h:i', strtotime($period['stc_school_teacher_schedule_endtime'])))
								){
									$time_flag = "y";
								}else{
									$time_flag = "n";
								}
							}
						} 
						if($att_counter>0){
							if($time_flag=="y"){
								$periods.='                                                        
								<td class="text-center">
									<a href="javascript:void(0);" class="stc-school-show-student-default"  data-toggle="modal" data-target="#exampleModal" id="'.$period['stc_school_teacher_schedule_id'].'" class-id="'.$period['stc_school_teacher_schedule_classid'].'" sub-id="'.$period['stc_school_teacher_schedule_subjectid'].'">
										<b>
										Class - '.$period['stc_school_class_title'].'<br>
										'.$period['stc_school_subject_title'].'<br>
										'.date('h:i', strtotime($period['stc_school_teacher_schedule_begtime'])).' - 
										'.date('h:i', strtotime($period['stc_school_teacher_schedule_endtime'])).'
										</b>
									</a>
								</td>
								';
							}else{
								$periods.='
								<td class="text-center">
									<b>
									Class - '.$period['stc_school_class_title'].'<br>
									'.$period['stc_school_subject_title'].'<br>
									'.date('h:i a', strtotime($period['stc_school_teacher_schedule_begtime'])).' - 
									'.date('h:i a', strtotime($period['stc_school_teacher_schedule_endtime'])).' 
									</b>
								</td>
								';
							}
						}else{
							if($time_flag=="y"){
								$periods.='
								<td class="text-center"  style="background: #8cff32;font-weight: bold;">
									<a href="javascript:void(0);" class="stc-school-show-student" data-toggle="modal" data-target="#exampleModal" id="'.$period['stc_school_teacher_schedule_id'].'" class-id="'.$period['stc_school_teacher_schedule_classid'].'" sub-id="'.$period['stc_school_teacher_schedule_subjectid'].'">
										<b>
										Class - '.$period['stc_school_class_title'].'<br>
										'.$period['stc_school_subject_title'].'<br>
										'.date('h:i', strtotime($period['stc_school_teacher_schedule_begtime'])).' - 
										'.date('h:i', strtotime($period['stc_school_teacher_schedule_endtime'])).'
										</b>
									</a>
								</td>
								';
							}else{
								$periods.='
								<td class="text-center">
									<b>
									Class - '.$period['stc_school_class_title'].'<br>
									'.$period['stc_school_subject_title'].'<br>
									'.date('h:i a', strtotime($period['stc_school_teacher_schedule_begtime'])).' - 
									'.date('h:i a', strtotime($period['stc_school_teacher_schedule_endtime'])).' 
									</b>
								</td>
								';
							}
						}
					}
    			}else{
    	      		$periods.='<td class="text-center">NA</td>';
    			}
    		}
    	  
    		$odin .= '
    			<tr '.$tr_color.'>
    				<td>'.$day.'</td>
    				'.$periods.'
    			</tr>    
    		';
    	}
		$att_result = $att_counter>0 ? "y" : "n";
		$odin=array(
			'schedule' => $odin,
			'att_result' => $att_result
		);
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
			FROM `stc_school_student`
			LEFT JOIN `stc_school_teacher_schedule`
			ON `stc_school_student_classroomid`=`stc_school_teacher_schedule_classid`
			LEFT JOIN `stc_school_class`
			ON `stc_school_class_id`=`stc_school_teacher_schedule_classid`
			LEFT JOIN `stc_school_subject`
			ON `stc_school_subject_id`=`stc_school_teacher_schedule_subjectid`
			WHERE `stc_school_teacher_schedule_id`='".mysqli_real_escape_string($this->stc_dbs, $schedule_id)."'
		");
		$headerstr='';
		$recordstr='';
		if(mysqli_num_rows($odin_stuqry)>0){
			foreach($odin_stuqry as $odin_sturow){
				$this->stc_call_school_student_save($odin_sturow['stc_school_student_id'], $odin_sturow['stc_school_subject_id'], $odin_sturow['stc_school_class_id'], 0, 1);
				$headerstr='<tr><th><b>Class</b></th><td class="text-center"><b>'.$odin_sturow['stc_school_class_title'].'</b></td><th><b>Subject</b></th>
				<td class="text-center"><b>'.$odin_sturow['stc_school_subject_title'].'</b></td></tr>';
				$check_rec=mysqli_query($this->stc_dbs, "
					SELECT `stc_school_student_attendance_id` FROM`stc_school_student_attendance` 
					WHERE `stc_school_student_attendance_status`='3' 
					AND `stc_school_student_attendance_stuid`='".$odin_sturow['stc_school_student_id']."'
					AND `stc_school_student_attendance_classid`='".$odin_sturow['stc_school_class_id']."'
				");
				$updatebtn='#';
				$attendance='
						<td style="background:green">
							<label for="p'.$odin_sturow['stc_school_student_id'].'">Present</label>
						</td>
						<td style="background:red">
							<input type="radio" name="stu-attendancecombo'.$odin_sturow['stc_school_student_id'].'" id="a'.$odin_sturow['stc_school_student_id'].'" class="stc-attend-check stc-school-stu-attendance-but'.$odin_sturow['stc_school_student_id'].'" subid="'.$odin_sturow['stc_school_subject_id'].'" classid="'.$odin_sturow['stc_school_class_id'].'" id="'.$odin_sturow['stc_school_student_id'].'" value="0"> 
							<label for="a'.$odin_sturow['stc_school_student_id'].'">Absent</label>
						</td>
				';
				if(mysqli_num_rows($check_rec)==0){	
					$updatebtn='
							<a href="#" class="btn btn-success stc-school-student-att-save" subid="'.$odin_sturow['stc_school_subject_id'].'" classid="'.$odin_sturow['stc_school_class_id'].'" id="'.$odin_sturow['stc_school_student_id'].'">Update</a> 
						';
					$attendance='
						<td style="background:green">
							<input type="radio" name="stu-attendancecombo'.$odin_sturow['stc_school_student_id'].'" id="p'.$odin_sturow['stc_school_student_id'].'" checked class="stc-attend-check stc-school-stu-attendance-but'.$odin_sturow['stc_school_student_id'].'" subid="'.$odin_sturow['stc_school_subject_id'].'" classid="'.$odin_sturow['stc_school_class_id'].'" id="'.$odin_sturow['stc_school_student_id'].'" value="1"> 
							<label for="p'.$odin_sturow['stc_school_student_id'].'">Present</label>
						</td>
						<td style="background:red">
							<input type="radio" name="stu-attendancecombo'.$odin_sturow['stc_school_student_id'].'" id="a'.$odin_sturow['stc_school_student_id'].'" class="stc-attend-check stc-school-stu-attendance-but'.$odin_sturow['stc_school_student_id'].'" subid="'.$odin_sturow['stc_school_subject_id'].'" classid="'.$odin_sturow['stc_school_class_id'].'" id="'.$odin_sturow['stc_school_student_id'].'" value="0"> 
							<label for="a'.$odin_sturow['stc_school_student_id'].'">Absent</label>
						</td>
					';
				}
				$recordstr.='
					<tr>
						<td class="text-center"><b>'.$odin_sturow['stc_school_student_studid'].'</b></td>
						<td class="text-left"><b>'.$odin_sturow['stc_school_student_firstname'].' '.$odin_sturow['stc_school_student_lastname'].'</b></td>
						'.$attendance.'
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

		
		$odin='
			<thead>
				'.$headerstr.'
        		<tr>
        		  <th class="text-center"><b>Student ID</b></th>
        		  <th class="text-center"><b>Student Name</b></th>
        		  <th class="text-center" colspan="2"><b>Attendance</b></th>
        		</tr>
        	</thead>
        	<tbody>
        	  '.$recordstr.'
        	</tbody>
		';
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
		$headerstr='';
		$recordstr='';
		if(mysqli_num_rows($odin_stuqry)>0){
			foreach($odin_stuqry as $odin_sturow){
				$this->stc_call_school_student_save($odin_sturow['stc_school_student_id'], $odin_sturow['stc_school_subject_id'], $odin_sturow['stc_school_class_id'], 0, 1);
				$headerstr='<tr><th><b>Class</b></th><td class="text-center"><b>'.$odin_sturow['stc_school_class_title'].'</b></td><th><b>Subject</b></th>
				<td class="text-center"><b>'.$odin_sturow['stc_school_subject_title'].'</b></td></tr>';
				$check_rec=mysqli_query($this->stc_dbs, "
					SELECT `stc_school_student_attendance_id` FROM`stc_school_student_attendance` 
					WHERE `stc_school_student_attendance_status`='3' 
					AND `stc_school_student_attendance_stuid`='".$odin_sturow['stc_school_student_id']."'
					AND `stc_school_student_attendance_classid`='".$odin_sturow['stc_school_class_id']."'
				");
				$updatebtn='#';
				$attendance='
						<td style="background:green">
							<label for="p'.$odin_sturow['stc_school_student_id'].'">Present</label>
						</td>
						<td style="background:red">
							<input type="radio" name="stu-attendancecombo'.$odin_sturow['stc_school_student_id'].'" id="a'.$odin_sturow['stc_school_student_id'].'" class="stc-attend-check stc-school-stu-attendance-but'.$odin_sturow['stc_school_student_id'].'" subid="'.$odin_sturow['stc_school_subject_id'].'" classid="'.$odin_sturow['stc_school_class_id'].'" id="'.$odin_sturow['stc_school_student_id'].'" value="0"> 
							<label for="a'.$odin_sturow['stc_school_student_id'].'">Absent</label>
						</td>
				';
				if(mysqli_num_rows($check_rec)==0){	
					$updatebtn='
							<a href="#" class="btn btn-success stc-school-student-att-save" subid="'.$odin_sturow['stc_school_subject_id'].'" classid="'.$odin_sturow['stc_school_class_id'].'" id="'.$odin_sturow['stc_school_student_id'].'">Update</a> 
						';
					$attendance='
						<td style="background:green">
							<input type="radio" name="stu-attendancecombo'.$odin_sturow['stc_school_student_id'].'" id="p'.$odin_sturow['stc_school_student_id'].'" checked class="stc-attend-check stc-school-stu-attendance-but'.$odin_sturow['stc_school_student_id'].'" subid="'.$odin_sturow['stc_school_subject_id'].'" classid="'.$odin_sturow['stc_school_class_id'].'" id="'.$odin_sturow['stc_school_student_id'].'" value="1"> 
							<label for="p'.$odin_sturow['stc_school_student_id'].'">Present</label>
						</td>
						<td style="background:red">
							<input type="radio" name="stu-attendancecombo'.$odin_sturow['stc_school_student_id'].'" id="a'.$odin_sturow['stc_school_student_id'].'" class="stc-attend-check stc-school-stu-attendance-but'.$odin_sturow['stc_school_student_id'].'" subid="'.$odin_sturow['stc_school_subject_id'].'" classid="'.$odin_sturow['stc_school_class_id'].'" id="'.$odin_sturow['stc_school_student_id'].'" value="0"> 
							<label for="a'.$odin_sturow['stc_school_student_id'].'">Absent</label>
						</td>
					';
				}
				$recordstr.='
					<tr>
						<td class="text-center"><b>'.$odin_sturow['stc_school_student_studid'].'</b></td>
						<td class="text-left"><b>'.$odin_sturow['stc_school_student_firstname'].' '.$odin_sturow['stc_school_student_lastname'].'</b></td>
						'.$attendance.'
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
		
		$odin='
			<thead>
				'.$headerstr.'
        		<tr>
        		  <th class="text-center"><b>Student ID</b></th>
        		  <th class="text-center"><b>Student Name</b></th>
        		  <th class="text-center" colspan="2"><b>Attendance</b></th>
        		</tr>
        	</thead>
        	<tbody>
        	  '.$recordstr.'
        	</tbody>
		';
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
		$odin_studentattuqry=mysqli_query($this->stc_dbs, "
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
		$hour=date("H");
		$odin_studentgqry=mysqli_query($this->stc_dbs, "
			SELECT `stc_school_student_attendance_id`
			FROM `stc_school_student_attendance`
			WHERE `stc_school_student_attendance_status`='1' 
			AND `stc_school_student_attendance_stuid`='".mysqli_real_escape_string($this->stc_dbs, $stc_stid)."'
			AND `stc_school_student_attendance_classid`='".mysqli_real_escape_string($this->stc_dbs, $stc_stclassid)."'
			AND `stc_school_student_attendance_subid`='".mysqli_real_escape_string($this->stc_dbs, $stc_stsubid)."'
			AND HOUR(`stc_school_student_attendance_createdate`)='".mysqli_real_escape_string($this->stc_dbs, $hour)."'
			AND `stc_school_student_attendance_attendance`='1'
		");
		if(mysqli_num_rows($odin_studentgqry)==0){
			$odin_studentuqry=mysqli_query($this->stc_dbs, "
				UPDATE `stc_school_student_attendance` 
				SET `stc_school_student_attendance_hw`= '".mysqli_real_escape_string($this->stc_dbs, $stc_sthwperc)."', `stc_school_student_attendance_status`='1' 
				WHERE `stc_school_student_attendance_status`='1' 
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
		}else{
			$odin="duplicate";
		}
		return $odin;
	}

	public function stc_call_school_lecturedetails_save($schedule_id, $classtype, $chapter, $lession, $Syllabus, $Unit, $remarks){
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
				`stc_school_lecture_unit`,
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
				'".mysqli_real_escape_string($this->stc_dbs, $Unit)."',
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

	public function stc_call_syllabus_det($schedule_id, $class_id, $sub_id){
		$lecture_details='';
		$syllabus_details='';
		$odinqry=mysqli_query($this->stc_dbs, "
			SELECT
			    `stc_school_lecture_id`,
			    `stc_school_lecture_subid`,
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
				$lecture_details.='
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
			$lecture_details.='
				<tr><td colspan="4">No records found.</td></tr>
			';
		}

		$odinqry=mysqli_query($this->stc_dbs, "
			SELECT
			    `stc_school_syllabus_id`,
			    `stc_school_syllabus_title`,
			    `stc_school_syllabus_chapter`,
			    `stc_school_syllabus_lesson`,
			    `stc_school_syllabus_unit`,
			    Date_FORMAT(`stc_school_syllabus_targetdate`, '%d %M %Y') as stc_school_syllabus_completedate
			FROM
			    `stc_school_syllabus`
			WHERE
			    `stc_school_syllabus_subject_id`='".mysqli_real_escape_string($this->stc_dbs, $sub_id)."'
		");
		$syllabus_details=array();
		if(mysqli_num_rows($odinqry)>0){
			foreach($odinqry as $odinqryrow){
				$syllabus_details[]=$odinqryrow;
			}
		}else{
			$syllabus_details[]=0;
		}
		$odin=array(
			'lecture_details' => $lecture_details,
			'syllabus_details' => $syllabus_details
		);
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
		$bgroup=array( "a_positive" => "A+", "b_positive" => "B+", "o_positive" => "O+", "ab_positive" => "AB+", "a_negative" => "A-", "b_negative" => "B-", "o_negative" => "O-", "ab_negative" => "AB-", "0" => "NA");
		
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
						<td>'.ucfirst(strtolower($row['stc_school_teacher_firstname'])).' '.ucfirst(strtolower($row['stc_school_teacher_lastname'])).'</td>
						<td class="text-center">'.date('d-m-Y', strtotime($row['stc_school_teacher_dob'])).'</td>
						<td>'.$row['stc_school_teacher_gender'].'</td>
						<td class="text-center">'.$bgroup[trim($row['stc_school_teacher_bloodgroup'])].'</td>
						<td>'.strtolower($row['stc_school_teacher_email']).'</td>
						<td>'.$row['stc_school_teacher_contact'].'</td>
						<td>'.$row['stc_school_teacher_address'].'</td>
						<td>'.$row['stc_school_teacher_skills'].'</td>
						<td class="text-center">'.ucfirst(strtolower($row['stc_school_teacher_religion'])).'</td>
						<td class="text-center">'.date('d-m-Y', strtotime($row['stc_school_teacher_joindate'])).'</td>
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
						<td>'.$row['stc_school_student_studid'].'</td>
						<td>'.$row['stc_school_student_firstname'].' '.$row['stc_school_student_lastname'].'</td>
						<td class="text-center">'.date('d-m-Y', strtotime($row['stc_school_student_dob'])).'</td>
						<td>'.$row['stc_school_student_gender'].'</td>
						<td class="text-center">'.$bgroup[trim($row['stc_school_student_bloodgroup'])].'</td>
						<td>'.$row['stc_school_student_email'].'</td>
						<td>'.$row['stc_school_student_contact'].'</td>
						<td>'.$row['stc_school_student_address'].'</td>
						<td class="text-center">'.ucfirst(strtolower($row['stc_school_student_religion'])).'</td>
						<td class="text-center">'.date('d-m-Y', strtotime($row['stc_school_student_admissiondate'])).'</td>
						<td>'.$row['stc_school_class_title'].'</td>
						<td>'.$row['stc_school_student_guardianname'].'</td>
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
						<td>
							<a href="school-management.php?school-management=yes&modal=access&id='.$row['stc_school_subject_id'].'" class="btn btn-success">Add Syllabus</a>
							<a href="school-management.php?school-management=yes&modal=accessview&id='.$row['stc_school_subject_id'].'" class="btn btn-success">View Syllabus</a>
						</td>
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
	    				$teacher_title=str_replace(" ","",$teacher_title);
	    				$teacher_title=str_replace("","",$teacher_title);
	    				array_push($teacher_name, $teacher_title);
	    				$data.='
							<td title="Click here to remove schedule" class="text-center schedule-box box-rep-'.$teacher_title.'">
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

	public function stc_call_student_attendance($class_id, $month){
		// $date=substr($month,5);
		$months=date('m', strtotime($month));
		$year=date('Y', strtotime($month));
		$monthday_arr=array(
			'01' => 31,
			'02' => 28,
			'03' => 31,
			'04' => 30,
			'05' => 31,
			'06' => 30,
			'07' => 31,
			'08' => 31,
			'09' => 30,
			'10' => 31,
			'11' => 30,
			'12' => 31
		);
		$validate=$monthday_arr[$months];
		$monthday='';
		$counter=0;
		for($i=0; $i<$validate;$i++){
			$counter++;
			$monthday.='<th class="text-center long">'.$counter.'</th>';
		}
		$odin='
			<table class="table table-hover table-bordered">
				<thead>
					<th class="text-center headcol">Sl No</th>
					<th class="text-center headcol">Student Id</th>
					<th class="text-center headcol">Student Name</th>
					'.$monthday.'
					<th class="text-center long">Total</th>
				</thead>
				<tbody class="stc-schoolattendance-show">
		';
		$odinattendanceqry=mysqli_query($this->stc_dbs, "
			SELECT
				`stc_school_student_id`,
				`stc_school_student_studid`,
				`stc_school_student_firstname`,
				`stc_school_student_lastname`
			FROM
				`stc_school_student`
			WHERE
				`stc_school_student_classroomid`='".mysqli_real_escape_string($this->stc_dbs, $class_id)."'
    	");
    	if(mysqli_num_rows($odinattendanceqry)>0){
			$slno=0;
			foreach($odinattendanceqry as $odinclassrow){
				$slno++;
				$stu_id=$odinclassrow['stc_school_student_id'];
				$attendance='';
				$att_counter=0;
				$totalattendance=0;
				for($i=0; $i<$validate;$i++){
					$att_counter++;
					$query="
						SELECT
							`stc_school_student_attendance_id`,
							`stc_school_student_attendance_date`,
							`stc_school_student_attendance_stuid`,
							`stc_school_student_attendance_classid`,
							`stc_school_student_attendance_subid`,
							`stc_school_student_attendance_attendance`,
							`stc_school_student_attendance_hw`,
							`stc_school_student_attendance_status`,
							`stc_school_student_attendance_createdate`,
							`stc_school_student_attendance_createdby`
						FROM
							`stc_school_student_attendance`
						WHERE
							`stc_school_student_attendance_stuid`='".mysqli_real_escape_string($this->stc_dbs, $stu_id)."'
						AND 
							MONTH(`stc_school_student_attendance_createdate`)='".mysqli_real_escape_string($this->stc_dbs, $months)."'
						AND 
							YEAR(`stc_school_student_attendance_createdate`)='".mysqli_real_escape_string($this->stc_dbs, $year)."'
						AND 
							DAY(`stc_school_student_attendance_createdate`)='".mysqli_real_escape_string($this->stc_dbs, $att_counter)."'
						ORDER BY `stc_school_student_attendance_id` ASC 
					";
					$odinattendanceqry=mysqli_query($this->stc_dbs, $query);
					if(mysqli_num_rows($odinattendanceqry)>0){
						$presentcounter=mysqli_num_rows($odinattendanceqry);
						$class_minutes=0;
						foreach($odinattendanceqry as $row ){
							if($row['stc_school_student_attendance_status']==1){
								$teacher_id=$row['stc_school_student_attendance_createdby'];
								$query=mysqli_query($this->stc_dbs, "
									SELECT `stc_school_teacher_id` FROM `stc_school_teacher` WHERE `stc_school_teacher_userid`=".$teacher_id."
								");
								$result=mysqli_fetch_assoc($query);
								$teacheruid=$result['stc_school_teacher_id'];
								$classid=$row['stc_school_student_attendance_classid'];
								$subid=$row['stc_school_student_attendance_subid'];
								$query="
									SELECT 
										`stc_school_teacher_schedule_begtime`,
										`stc_school_teacher_schedule_endtime` 
									FROM `stc_school_teacher_schedule` 
									LEFT JOIN `stc_school`
									ON `stc_school_teacher_schedule_teacherid`=`stc_school_user_id` 
									LEFT JOIN `stc_school_teacher`
									ON `stc_school_teacher_userid`=`stc_school_user_id` 
									WHERE `stc_school_teacher_schedule_teacherid`=".$teacheruid."
									AND `stc_school_teacher_schedule_classid`=".$classid."
									AND `stc_school_teacher_schedule_subjectid`=".$subid."
									LIMIT 0,1
								";
								$query=mysqli_query($this->stc_dbs, "
									SELECT 
										MINUTE(TIMEDIFF(`stc_school_teacher_schedule_begtime`, `stc_school_teacher_schedule_endtime`)) as duration
									FROM `stc_school_teacher_schedule` 
									LEFT JOIN `stc_school`
									ON `stc_school_teacher_schedule_teacherid`=`stc_school_user_id` 
									LEFT JOIN `stc_school_teacher`
									ON `stc_school_teacher_userid`=`stc_school_user_id` 
									WHERE `stc_school_teacher_schedule_teacherid`=".$teacheruid."
									AND `stc_school_teacher_schedule_classid`=".$classid."
									AND `stc_school_teacher_schedule_subjectid`=".$subid."
									LIMIT 0,1
								");
								$result = mysqli_fetch_assoc($query);
								$duration = mysqli_num_rows($query)>0 ? $result['duration'] : 0;
								$class_minutes+=$duration;
							}
						}
						$totalattendance+=$class_minutes;
						$attendance.='<td class="text-center long" title="Lecture" style="background-color: #80d049;">'.$class_minutes.'</td>';
					}else{
						$attendance.='<td class="text-center long" style="background-color: #fff7bd">A</td>';
					}
				}
				$tot_time = '<td class="text-center" style="background-color: #ffc07e;">NA</td>';
				if($totalattendance>0){
					$tot_time = '<td class="text-center btn stc-school-student-att-show" id="'.$odinclassrow['stc_school_student_id'].'" style="background-color: #80d049;">'.$totalattendance.' Minutes</td>';
				}
				$odin.='
					<tr>
						<td class="text-center headcol">'.$slno.'</td>
						<td class="headcol">'.$odinclassrow['stc_school_student_studid'].'</td>
						<td class="headcol">'.$odinclassrow['stc_school_student_firstname'].' '.$odinclassrow['stc_school_student_lastname'].'</td>
						'.$attendance.'
						'.$tot_time.'
					</tr>
				';
			}
    	}else{
			$colsapn=$counter + 3;
	    	$odin.="
				<tr>
					<td colspan='".$colsapn."'>No record found.</td>
				</tr>
			";
		}
		$odin.="
				</tbody>
			</table>
		";
		return $odin;
	}

	public function stc_call_questions($class_id, $month){
		// Extract the month and year from the input date
		$months = date('m', strtotime($month));
		$year = date('Y', strtotime($month));
	
		// Array to store the number of days in each month
		$monthday_arr = array(
			'01' => 31,
			'02' => 28, // Note: This does not account for leap years
			'03' => 31,
			'04' => 30,
			'05' => 31,
			'06' => 30,
			'07' => 31,
			'08' => 31,
			'09' => 30,
			'10' => 31,
			'11' => 30,
			'12' => 31
		);
	
		// Validate the number of days in the month
		$validate = $monthday_arr[$months];
		$monthday = '';
		$counter = 0;
	
		// Generate the table headers for each day of the month
		for ($i = 0; $i < $validate; $i++) {
			$counter++;
			$monthday .= '<th class="text-center long">' . $counter . '</th>';
		}
	
		// Start building the HTML table
		$odin = '
			<table class="table table-hover table-bordered">
				<thead>
					<tr>
						<th class="text-center headcol">Sl No</th>
						<th class="text-center headcol">Class</th>
						<th class="text-center headcol">Subject</th>
						<th class="text-center headcol">Chapter</th>
						<th class="text-center headcol">Questions</th>
					</tr>
				</thead>
				<tbody class="stc-schoolattendance-show">
		';
	
		// Query to fetch the questions for the given class and month
		$odinattendanceqry = mysqli_query($this->stc_dbs, "
			SELECT `stc_school_lecture_question_id`, `stc_school_lecture_chapter`, `stc_school_lecture_question_scheduleid`, `stc_school_class_title`, `stc_school_subject_title`, `stc_school_lecture_question_question`, `stc_school_lecture_question_status`, `stc_school_lecture_question_createdate`, `stc_school_lecture_question_createdby` 
			FROM `stc_school_lecture_question` 
			LEFT JOIN `stc_school_class` ON `stc_school_lecture_question_classid`=`stc_school_class_id`
			LEFT JOIN `stc_school_subject` ON `stc_school_lecture_question_subid`=`stc_school_subject_id`
			LEFT JOIN `stc_school_lecture` ON `stc_school_lecture_question_lectureid`=`stc_school_lecture_id`
			WHERE `stc_school_lecture_question_classid` = '" . mysqli_real_escape_string($this->stc_dbs, $class_id) . "' 
			AND MONTH(`stc_school_lecture_question_createdate`) = '" . mysqli_real_escape_string($this->stc_dbs, $months) . "' 
			AND YEAR(`stc_school_lecture_question_createdate`) = '" . mysqli_real_escape_string($this->stc_dbs, $year) . "'
			ORDER BY `stc_school_lecture_question_question` ASC
		");
	
		// Check if there are any records
		if (mysqli_num_rows($odinattendanceqry) > 0) {
			$slno=0;
			// Loop through the results and add them to the table
			while ($row = mysqli_fetch_assoc($odinattendanceqry)) {
				$slno++;
				$odin .= '
					<tr>
						<td class="text-center">' . $slno . '</td>
						<td class="text-center">' . $row['stc_school_class_title'] . '</td>
						<td class="text-center">' . $row['stc_school_subject_title'] . '</td>
						<td class="text-center">' . $row['stc_school_lecture_chapter'] . '</td>
						<td>' . $row['stc_school_lecture_question_question'] . '</td>
					</tr>
				';
			}
		} else {
			// If no records are found, display a message
			$colspan = $counter + 4; // Adjust colspan to include all columns
			$odin .= "
				<tr>
					<td colspan='" . $colspan . "'>No record found.</td>
				</tr>
			";
		}
	
		// Close the table
		$odin .= "
				</tbody>
			</table>
		";
	
		return $odin;
	}

	public function stc_call_pertstudent_details($student_id){
		$odin_gquery=mysqli_query($this->stc_dbs, "
			SELECT 
				`stc_school_student_id`, 
				`stc_school_student_studid`,
				LOWER(`stc_school_student_firstname`) as firstname,
				LOWER(`stc_school_student_lastname`) as lastname,
				`stc_school_student_classroomid`,
				`stc_school_class_title`
			FROM `stc_school_student`
			LEFT JOIN `stc_school_class`
			ON `stc_school_student_classroomid`=`stc_school_class_id`
			WHERE `stc_school_student_id`='".mysqli_real_escape_string($this->stc_dbs, $student_id)."'
		");
		$out='';
		if(mysqli_num_rows($odin_gquery)>0){
			$result=mysqli_fetch_assoc($odin_gquery);
			$studentid=$result['stc_school_student_studid'];
			$name=ucfirst($result['firstname']).' '.ucfirst($result['lastname']);
			$class=$result['stc_school_class_title'];
			$classid=$result['stc_school_student_classroomid'];
			$total_attendance=0;
			$total_class=0;
			$montharray = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
			$attendance='
				<div class="text-center col-xl-4 col-lg-4 col-md-4 col-sm-12 mx-auto">
					<div style="font-size: 25px;font-weight: bold;border-bottom: 1px solid black;padding-top:5px;}">
						<h3>Month</h3>
					</div>
				</div>
				<div class="text-center col-xl-4 col-lg-4 col-md-4 col-sm-12 mx-auto">
					<div style="font-size: 25px;font-weight: bold;border-bottom: 1px solid black;padding-top:5px;}">
						<h3>Total Class</h3>
					</div>
				</div>
				<div class="text-center col-xl-4 col-lg-4 col-md-4 col-sm-12 mx-auto">
					<div style="font-size: 25px;font-weight: bold;border-bottom: 1px solid black;padding-top:5px;}">
						<h3>Attended Class</h3>
					</div>
				</div>
			';
			$counter=0;
			for($i=0;$i<count($montharray);$i++){
				$counter++;
				$query="
					SELECT
						`stc_school_student_attendance_id`,
						`stc_school_student_attendance_date`,
						`stc_school_student_attendance_stuid`,
						`stc_school_student_attendance_classid`,
						`stc_school_student_attendance_subid`,
						`stc_school_student_attendance_attendance`,
						`stc_school_student_attendance_status`
					FROM
						`stc_school_student_attendance`
					WHERE
						`stc_school_student_attendance_stuid`='".$student_id."' 
					AND
						`stc_school_student_attendance_classid`='".$classid."' 
					AND
						MONTH(`stc_school_student_attendance_date`)='".$counter."'
				";
				$odin_gquery=mysqli_query($this->stc_dbs, $query);
				$result=mysqli_num_rows($odin_gquery);

				$odin_gthrquery=mysqli_query($this->stc_dbs, "
					SELECT `stc_school_teacher_schedule_id` FROM `stc_school_teacher_schedule` WHERE `stc_school_teacher_schedule_classid`='".$classid."'
				");
				$hrresult=mysqli_num_rows($odin_gthrquery) * 4;
				if($result>0){
					$attendance.='
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mx-auto">
							'.$montharray[$i].'
						</div>
						<div class="text-center col-xl-4 col-lg-4 col-md-4 col-sm-12 mx-auto">
							<b>'.$hrresult.'</b>
						</div>
						<div class="text-center col-xl-4 col-lg-4 col-md-4 col-sm-12 mx-auto">
							<b>'.$result.'</b>
						</div>
					';
					$total_attendance+=$result;					
				}else{
					$attendance.='
						<div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 mx-auto">
							'.$montharray[$i].'
						</div>
						<div class="text-center col-xl-4 col-lg-4 col-md-4 col-sm-12 mx-auto">
							<b>'.$hrresult.'</b>
						</div>
						<div class="text-center col-xl-4 col-lg-4 col-md-4 col-sm-12 mx-auto">
							<b>0</b>
						</div>
					';
				}
				$total_class+=$hrresult;
			}
			
			$attendance.='
				<div class="text-center col-xl-4 col-lg-4 col-md-4 col-sm-12 mx-auto">
					<div style="font-size: 25px;font-weight: bold;border-top: 1px solid black;padding-top:8px;}">
						Total
					</div>
				</div>
				<div class="text-center col-xl-4 col-lg-4 col-md-4 col-sm-12 mx-auto">
					<div style="font-size: 25px;font-weight: bold;border-top: 1px solid black;padding-top:8px;}">
						<strong>'.$total_class.'</strong>
					</div>
				</div>
				<div class="text-center col-xl-4 col-lg-4 col-md-4 col-sm-12 mx-auto">
					<div style="font-size: 25px;font-weight: bold;border-top: 1px solid black;padding-top:8px;}">
						<strong>'.$total_attendance.'</strong>
					</div>
				</div>
			';
			$status="success";
			$out=array(
				'studentid' => $studentid,
				'name' => $name,
				'class' => $class,
				'attendance' => $attendance,
				'total_attendance' => $total_attendance
			);
		}else{
			$out="NA";
		}
		$odin = array(
			'status' => $status,
			'data'	=> $out
		);
		return $odin;
	}

	public function stc_call_syllabus($syllabus_id){
		$odin='';
		$odinattendanceqry=mysqli_query($this->stc_dbs, "
			SELECT * FROM `stc_school_syllabus`
			WHERE
				`stc_school_syllabus_subject_id`='".mysqli_real_escape_string($this->stc_dbs, $syllabus_id)."'
    	");
    	if(mysqli_num_rows($odinattendanceqry)>0){
			$slno=0;
			foreach($odinattendanceqry as $odinclassrow){
				$slno++;
				$odin.='
					<tr>
						<td class="headcol">'.$odinclassrow['stc_school_syllabus_title'].'</td>
						<td class="headcol">'.$odinclassrow['stc_school_syllabus_chapter'].'</td>
						<td class="headcol">'.$odinclassrow['stc_school_syllabus_lesson'].'</td>
						<td class="headcol">'.$odinclassrow['stc_school_syllabus_unit'].'</td>
						<td class="headcol">'.date('d-m-Y', strtotime($odinclassrow['stc_school_syllabus_targetdate'])).'</td>
					</tr>
				';
			}
    	}else{
	    	$odin.="
				<tr>
					<td colspan='5'>No record found.</td>
				</tr>
			";
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

// save syllabus
if(isset($_POST['stc_add_syllabus_action'])){
	$stc_subject_id =$_POST['stc_subject_id'];
	$stc_title =$_POST['stc_title'];
	$stc_chapter =$_POST['stc_chapter'];
	$stc_lesson =$_POST['stc_lesson'];
	$stc_unit =$_POST['stc_unit'];
	$stc_date =$_POST['stc_date'];
	$valkyrie=new Yggdrasil();
	if(empty($_SESSION['stc_school_user_id'])){
		$out="reload";
	}else if(empty($stc_title)){
		$out="empty";
	}else{
		$lokiheck=$valkyrie->stc_save_school_syllabus($stc_subject_id, $stc_title, $stc_chapter, $stc_lesson, $stc_unit, $stc_date);
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
	$stcschoolscheduletype =$_POST['stcschoolscheduletype'];
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
	}else if($stcschoolscheduletype=="NA" || $stcschoolscheduleteacher=="NA" || $stcschoolschedulesubject=="NA" || $stcschoolscheduleclass=="NA" || $stcschoolscheduleday=="NA"){
		$out="empty";
	}else{		
		$lokiheck=$valkyrie->stc_save_school_schedule($stcschoolscheduletype, $stcschoolscheduleteacher, $stcschoolschedulesubject, $stcschoolscheduleclass, $stcschoolscheduleday, $stcschoolschedulestarttime, $stcschoolscheduleendtime, $stcschoolscheduleperiod);
		$out=$lokiheck;
	}
	echo $out;
}

// call schedule for teacher
if(isset($_POST['stc_teacherschedule_call'])){
	$type=$_POST['type'];
	$valkyrie=new Yggdrasil();
	$lokiheck=$valkyrie->stc_call_teacher_schedule($type);
	echo json_encode($lokiheck);
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
	$student_id=$_POST['student_id'];
	$sub_id=$_POST['sub_id'];
	$attendance=$_POST['attendance'];
	$out='';
	if(empty($_SESSION['stc_school_user_id'])){
		$out="reload";
	}else{
		$valkyrie=new Yggdrasil();
		$out=$valkyrie->stc_call_school_lecturer_end();
		for($i=0;$i<strlen($student_id);$i++){
			if($attendance[$i]==1){
				$valkyrie->stc_call_school_student_save($student_id[$i], $sub_id[$i], $class_id[$i], 0, $attendance[$i]);
			}
		}
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
	$Unit=$_POST['Unit'];
	$remarks=$_POST['remarks'];
	$out='';
	if(empty($_SESSION['stc_school_user_id'])){
		$out="reload";
	}elseif(($classtype=='NA') || (empty($chapter)) || (empty($lession)) || (empty($Syllabus))){
		$out="empty";
	}else{
		$valkyrie=new Yggdrasil();
		$out=$valkyrie->stc_call_school_lecturedetails_save($schedule_id, $classtype, $chapter, $lession, $Syllabus, $Unit, $remarks);
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
	$class_id=$_POST['class_id'];
	$sub_id=$_POST['sub_id'];
	$out='';
	if(empty($_SESSION['stc_school_user_id'])){
		$out="reload";
	}else{
		$valkyrie=new Yggdrasil();
		$out=$valkyrie->stc_call_syllabus_det($schedule_id, $class_id, $sub_id);
	}
	echo json_encode($out);
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

// call student attendance
if(isset($_POST['stc_call_studentattendance'])){
	$class_id=$_POST['class_id'];
	$month=$_POST['month'];
	$out=array();
	if(empty($_SESSION['stc_school_user_id'])){
		$out['reload']="reload";
	}else{
		$valkyrie=new Yggdrasil();
		$out=$valkyrie->stc_call_student_attendance($class_id, $month);
	}
	echo json_encode($out);
}

if(isset($_POST['stc_student_attendance_get'])){
	$student_id=$_POST['student_id'];
	$out=array();
	if(empty($_SESSION['stc_school_user_id'])){
		$out['reload']="reload";
	}else{
		$valkyrie=new Yggdrasil();
		$out=$valkyrie->stc_call_pertstudent_details($student_id);
	}
	echo json_encode($out);
	
}

// call student attendance
if(isset($_POST['stc_call_questions'])){
	$class_id=$_POST['class_id'];
	$month=$_POST['month'];
	$out=array();
	if(empty($_SESSION['stc_school_user_id'])){
		$out['reload']="reload";
	}else{
		$valkyrie=new Yggdrasil();
		$out=$valkyrie->stc_call_questions($class_id, $month);
	}
	echo json_encode($out);
}



// call student attendance
if(isset($_POST['stc_get_syllabus_details'])){
	$syllabus_id=$_POST['syllabus_id'];
	$out = ['status' => false, 'data' => []];
	if(empty($_SESSION['stc_school_user_id'])){
		$out['status']="reload";
	}else{
		$valkyrie=new Yggdrasil();
		$out['status']="success";
		$out['data']=$valkyrie->stc_call_syllabus($syllabus_id);
	}
	echo json_encode($out);
}
?>
