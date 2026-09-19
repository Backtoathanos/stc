<?php
if (!ob_get_level()) {
	ob_start();
}
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}
date_default_timezone_set('Asia/Kolkata');
include __DIR__ . "/../../MCU/obdb.php";

class StcSchoolAdmission extends tesseract
{
	private function json_out($payload, $code = 200)
	{
		http_response_code($code);
		header('Content-Type: application/json; charset=UTF-8');
		echo json_encode($payload);
		exit;
	}

	private function require_school_user()
	{
		if (empty($_SESSION['stc_school_user_id'])) {
			$this->json_out(array('status' => 'reload', 'message' => 'Please login again.'));
		}
	}

	private function esc($value)
	{
		return mysqli_real_escape_string($this->stc_dbs, (string) $value);
	}

	private function ensure_table()
	{
		mysqli_query($this->stc_dbs, "
			CREATE TABLE IF NOT EXISTS `stc_school_admission` (
			  `stc_school_admission_id` int(11) NOT NULL AUTO_INCREMENT,
			  `stc_school_admission_school` varchar(20) NOT NULL DEFAULT '',
			  `stc_school_admission_studid` varchar(64) NOT NULL DEFAULT '',
			  `stc_school_admission_firstname` varchar(120) NOT NULL DEFAULT '',
			  `stc_school_admission_lastname` varchar(120) NOT NULL DEFAULT '',
			  `stc_school_admission_dob` date DEFAULT NULL,
			  `stc_school_admission_gender` varchar(20) NOT NULL DEFAULT '',
			  `stc_school_admission_bloodgroup` varchar(40) NOT NULL DEFAULT '',
			  `stc_school_admission_email` varchar(160) NOT NULL DEFAULT '',
			  `stc_school_admission_contact` varchar(20) NOT NULL DEFAULT '',
			  `stc_school_admission_address` text,
			  `stc_school_admission_religion` varchar(80) NOT NULL DEFAULT '',
			  `stc_school_admission_admissiondate` date DEFAULT NULL,
			  `stc_school_admission_classroomid` int(11) NOT NULL DEFAULT 0,
			  `stc_school_admission_guardianname` varchar(160) NOT NULL DEFAULT '',
			  `stc_school_admission_student_remarks` text,
			  `stc_school_admission_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
			  `stc_school_admission_status` varchar(20) NOT NULL DEFAULT 'pending',
			  `stc_school_admission_final_amount` decimal(12,2) DEFAULT NULL,
			  `stc_school_admission_boss_remarks` text,
			  `stc_school_admission_created_by` int(11) NOT NULL DEFAULT 0,
			  `stc_school_admission_created_date` datetime DEFAULT NULL,
			  `stc_school_admission_accepted_by` int(11) NOT NULL DEFAULT 0,
			  `stc_school_admission_accepted_date` datetime DEFAULT NULL,
			  `stc_school_admission_student_pk` int(11) NOT NULL DEFAULT 0,
			  PRIMARY KEY (`stc_school_admission_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
		");
	}

	private function null_date($value)
	{
		$value = trim((string) $value);
		if ($value === '' || $value === '0000-00-00') {
			return null;
		}
		$ts = strtotime($value);
		return $ts ? date('Y-m-d', $ts) : null;
	}

	private function sql_date($value)
	{
		$d = $this->null_date($value);
		return $d === null ? 'NULL' : "'".$this->esc($d)."'";
	}

	public function save($p)
	{
		$this->require_school_user();
		$this->ensure_table();

		$school = trim((string) ($p['school'] ?? ''));
		$studid = trim((string) ($p['studid'] ?? ''));
		$firstname = trim((string) ($p['firstname'] ?? ''));
		$lastname = trim((string) ($p['lastname'] ?? ''));
		$dob = $this->null_date($p['dob'] ?? '');
		$gender = trim((string) ($p['gender'] ?? ''));
		$bloodgroup = trim((string) ($p['bloodgroup'] ?? ''));
		$email = trim((string) ($p['email'] ?? ''));
		$contact = trim((string) ($p['contact'] ?? ''));
		$address = trim((string) ($p['address'] ?? ''));
		$religion = trim((string) ($p['religion'] ?? ''));
		$admissiondate = $this->null_date($p['admissiondate'] ?? '');
		$classroomid = (int) ($p['classroomid'] ?? 0);
		$guardianname = trim((string) ($p['guardianname'] ?? ''));
		$student_remarks = trim((string) ($p['student_remarks'] ?? ''));
		$amount = (float) ($p['amount'] ?? 0);

		if ($school === '' || $studid === '' || $firstname === '' || $lastname === '' || $contact === '' || $guardianname === '') {
			$this->json_out(array('status' => 'empty', 'message' => 'Please fill school, student ID, name, contact, guardian and admission amount.'));
		}
		if ($amount <= 0) {
			$this->json_out(array('status' => 'empty', 'message' => 'Enter a valid admission amount.'));
		}
		$allowed_schools = array('SGMS', 'SHS', 'SIS', 'SMS');
		if (!in_array($school, $allowed_schools, true)) {
			$this->json_out(array('status' => 'empty', 'message' => 'Select a valid school.'));
		}

		$exists = mysqli_query($this->stc_dbs, "
			SELECT `stc_school_student_id`
			FROM `stc_school_student`
			WHERE TRIM(`stc_school_student_studid`)='".$this->esc($studid)."'
			LIMIT 1
		");
		if ($exists && mysqli_num_rows($exists) > 0) {
			$this->json_out(array('status' => 'duplicate', 'message' => 'This student ID already exists. Use Readmission instead.'));
		}

		$pending = mysqli_query($this->stc_dbs, "
			SELECT `stc_school_admission_id`
			FROM `stc_school_admission`
			WHERE `stc_school_admission_studid`='".$this->esc($studid)."'
			  AND `stc_school_admission_status`='pending'
			LIMIT 1
		");
		if ($pending && mysqli_num_rows($pending) > 0) {
			$this->json_out(array('status' => 'duplicate', 'message' => 'A pending new admission already exists for this student ID.'));
		}

		$created_by = (int) $_SESSION['stc_school_user_id'];
		$now = date('Y-m-d H:i:s');
		$sql = "
			INSERT INTO `stc_school_admission`(
				`stc_school_admission_school`,
				`stc_school_admission_studid`,
				`stc_school_admission_firstname`,
				`stc_school_admission_lastname`,
				`stc_school_admission_dob`,
				`stc_school_admission_gender`,
				`stc_school_admission_bloodgroup`,
				`stc_school_admission_email`,
				`stc_school_admission_contact`,
				`stc_school_admission_address`,
				`stc_school_admission_religion`,
				`stc_school_admission_admissiondate`,
				`stc_school_admission_classroomid`,
				`stc_school_admission_guardianname`,
				`stc_school_admission_student_remarks`,
				`stc_school_admission_amount`,
				`stc_school_admission_status`,
				`stc_school_admission_created_by`,
				`stc_school_admission_created_date`
			) VALUES (
				'".$this->esc($school)."',
				'".$this->esc($studid)."',
				'".$this->esc($firstname)."',
				'".$this->esc($lastname)."',
				".$this->sql_date($dob).",
				'".$this->esc($gender)."',
				'".$this->esc($bloodgroup)."',
				'".$this->esc($email)."',
				'".$this->esc($contact)."',
				'".$this->esc($address)."',
				'".$this->esc($religion)."',
				".$this->sql_date($admissiondate).",
				'".(int) $classroomid."',
				'".$this->esc($guardianname)."',
				'".$this->esc($student_remarks)."',
				'".$this->esc(number_format($amount, 2, '.', ''))."',
				'pending',
				'".$created_by."',
				'".$this->esc($now)."'
			)
		";
		$ok = mysqli_query($this->stc_dbs, $sql);
		if (!$ok) {
			$this->json_out(array('status' => 'wrong', 'message' => 'Could not save admission. Please try again.'));
		}
		$this->json_out(array('status' => 'success', 'message' => 'New admission submitted. Waiting for boss approval.', 'id' => (int) mysqli_insert_id($this->stc_dbs)));
	}

	public function list_rows($search = '')
	{
		$this->require_school_user();
		$this->ensure_table();
		$where = '';
		$search = trim((string) $search);
		if ($search !== '') {
			$s = $this->esc($search);
			$where = " WHERE (
				r.`stc_school_admission_studid` LIKE '%".$s."%'
				OR r.`stc_school_admission_firstname` LIKE '%".$s."%'
				OR r.`stc_school_admission_lastname` LIKE '%".$s."%'
				OR CONCAT(r.`stc_school_admission_firstname`,' ',r.`stc_school_admission_lastname`) LIKE '%".$s."%'
				OR r.`stc_school_admission_school` LIKE '%".$s."%'
				OR r.`stc_school_admission_status` LIKE '%".$s."%'
			)";
		}
		$qry = mysqli_query($this->stc_dbs, "
			SELECT
				r.*,
				c.`stc_school_class_title`,
				u.`stc_school_user_fullName`
			FROM `stc_school_admission` r
			LEFT JOIN `stc_school_class` c ON c.`stc_school_class_id` = r.`stc_school_admission_classroomid`
			LEFT JOIN `stc_school` u ON u.`stc_school_user_id` = r.`stc_school_admission_created_by`
			".$where."
			ORDER BY r.`stc_school_admission_id` DESC
			LIMIT 200
		");
		$rows = array();
		if ($qry) {
			while ($row = mysqli_fetch_assoc($qry)) {
				$rows[] = $this->map_row($row);
			}
		}
		$this->json_out(array('status' => 'success', 'data' => $rows));
	}

	private function map_row($row)
	{
		$status = (string) ($row['stc_school_admission_status'] ?? 'pending');
		$final = $row['stc_school_admission_final_amount'];
		return array(
			'id' => (int) $row['stc_school_admission_id'],
			'school' => (string) $row['stc_school_admission_school'],
			'studid' => (string) $row['stc_school_admission_studid'],
			'name' => trim($row['stc_school_admission_firstname'].' '.$row['stc_school_admission_lastname']),
			'contact' => (string) $row['stc_school_admission_contact'],
			'classroom' => (string) ($row['stc_school_class_title'] ?? ''),
			'amount' => (float) $row['stc_school_admission_amount'],
			'final_amount' => $final === null || $final === '' ? null : (float) $final,
			'status' => $status,
			'created_by' => (string) ($row['stc_school_user_fullName'] ?? ''),
			'created_date' => (string) $row['stc_school_admission_created_date'],
		);
	}

	public function handle()
	{
		if (isset($_POST['stc_admit_save'])) {
			$this->save($_POST);
		}
		if (isset($_POST['stc_admit_list'])) {
			$this->list_rows($_POST['search'] ?? '');
		}
	}
}

$stcAdmit = new StcSchoolAdmission();
$stcAdmit->handle();
