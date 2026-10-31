<?php
if (!ob_get_level()) {
	ob_start();
}
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}
date_default_timezone_set('Asia/Kolkata');
include __DIR__ . "/../../MCU/obdb.php";

class StcSchoolReadmission extends tesseract
{
	public static $last_status_options = array(
		'Promoted',
		'Failed',
		'Repeater',
		'Left',
		'TC Issued',
		'Dropout',
		'Inactive',
		'Other',
	);

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

	private function ensure_marks_percent_column()
	{
		$q = mysqli_query($this->stc_dbs, "SHOW COLUMNS FROM `stc_school_readmission` LIKE 'stc_school_readmission_marks_percent'");
		if ($q && mysqli_num_rows($q) === 0) {
			mysqli_query($this->stc_dbs, "ALTER TABLE `stc_school_readmission` ADD `stc_school_readmission_marks_percent` DECIMAL(6,2) DEFAULT NULL");
		}
	}

	private function parse_marks_percent($value)
	{
		$raw = trim((string) $value);
		if ($raw === '' || !is_numeric($raw)) {
			return null;
		}
		$n = round((float) $raw, 2);
		if ($n < 0) {
			$n = 0;
		}
		if ($n > 100) {
			$n = 100;
		}
		return $n;
	}

	private function sql_marks($value)
	{
		$n = $this->parse_marks_percent($value);
		return $n === null ? 'NULL' : "'".$this->esc(number_format($n, 2, '.', ''))."'";
	}

	private function ensure_table()
	{
		mysqli_query($this->stc_dbs, "
			CREATE TABLE IF NOT EXISTS `stc_school_readmission` (
			  `stc_school_readmission_id` int(11) NOT NULL AUTO_INCREMENT,
			  `stc_school_readmission_school` varchar(20) NOT NULL DEFAULT '',
			  `stc_school_readmission_student_pk` int(11) NOT NULL DEFAULT 0,
			  `stc_school_readmission_studid` varchar(64) NOT NULL DEFAULT '',
			  `stc_school_readmission_firstname` varchar(120) NOT NULL DEFAULT '',
			  `stc_school_readmission_lastname` varchar(120) NOT NULL DEFAULT '',
			  `stc_school_readmission_dob` date DEFAULT NULL,
			  `stc_school_readmission_gender` varchar(20) NOT NULL DEFAULT '',
			  `stc_school_readmission_bloodgroup` varchar(40) NOT NULL DEFAULT '',
			  `stc_school_readmission_email` varchar(160) NOT NULL DEFAULT '',
			  `stc_school_readmission_contact` varchar(20) NOT NULL DEFAULT '',
			  `stc_school_readmission_address` text,
			  `stc_school_readmission_religion` varchar(80) NOT NULL DEFAULT '',
			  `stc_school_readmission_admissiondate` date DEFAULT NULL,
			  `stc_school_readmission_classroomid` int(11) NOT NULL DEFAULT 0,
			  `stc_school_readmission_guardianname` varchar(160) NOT NULL DEFAULT '',
			  `stc_school_readmission_student_remarks` text,
			  `stc_school_readmission_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
			  `stc_school_readmission_last_status` varchar(80) NOT NULL DEFAULT '',
			  `stc_school_readmission_last_remarks` text,
			  `stc_school_readmission_status` varchar(20) NOT NULL DEFAULT 'pending',
			  `stc_school_readmission_final_amount` decimal(12,2) DEFAULT NULL,
			  `stc_school_readmission_boss_remarks` text,
			  `stc_school_readmission_created_by` int(11) NOT NULL DEFAULT 0,
			  `stc_school_readmission_created_date` datetime DEFAULT NULL,
			  `stc_school_readmission_accepted_by` int(11) NOT NULL DEFAULT 0,
			  `stc_school_readmission_accepted_date` datetime DEFAULT NULL,
			  PRIMARY KEY (`stc_school_readmission_id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
		");
		$this->ensure_marks_percent_column();
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

	private function student_payload($row)
	{
		return array(
			'student_pk' => (int) $row['stc_school_student_id'],
			'studid' => (string) $row['stc_school_student_studid'],
			'firstname' => (string) $row['stc_school_student_firstname'],
			'lastname' => (string) $row['stc_school_student_lastname'],
			'dob' => $this->null_date($row['stc_school_student_dob']),
			'gender' => (string) $row['stc_school_student_gender'],
			'bloodgroup' => (string) $row['stc_school_student_bloodgroup'],
			'email' => (string) $row['stc_school_student_email'],
			'contact' => (string) $row['stc_school_student_contact'],
			'address' => (string) $row['stc_school_student_address'],
			'religion' => (string) $row['stc_school_student_religion'],
			'admissiondate' => $this->null_date($row['stc_school_student_admissiondate']),
			'classroomid' => (int) $row['stc_school_student_classroomid'],
			'guardianname' => (string) $row['stc_school_student_guardianname'],
			'remarks' => (string) $row['stc_school_student_remarks'],
			'classroom' => (string) ($row['stc_school_class_title'] ?? ''),
		);
	}

	private function student_select_sql()
	{
		return "
			SELECT
				s.`stc_school_student_id`,
				s.`stc_school_student_studid`,
				s.`stc_school_student_firstname`,
				s.`stc_school_student_lastname`,
				s.`stc_school_student_dob`,
				s.`stc_school_student_gender`,
				s.`stc_school_student_bloodgroup`,
				s.`stc_school_student_email`,
				s.`stc_school_student_contact`,
				s.`stc_school_student_address`,
				s.`stc_school_student_religion`,
				s.`stc_school_student_admissiondate`,
				s.`stc_school_student_classroomid`,
				s.`stc_school_student_guardianname`,
				s.`stc_school_student_remarks`,
				c.`stc_school_class_title`
			FROM `stc_school_student` s
			LEFT JOIN `stc_school_class` c ON c.`stc_school_class_id` = s.`stc_school_student_classroomid`
		";
	}

	public function lookup_student($studid)
	{
		$this->require_school_user();
		$studid = trim((string) $studid);
		if ($studid === '') {
			$this->json_out(array('status' => 'empty', 'message' => 'Enter student ID.'));
		}
		$res = mysqli_query($this->stc_dbs, $this->student_select_sql()."
			WHERE TRIM(s.`stc_school_student_studid`) = '".$this->esc($studid)."'
			LIMIT 1
		");
		$row = ($res && mysqli_num_rows($res) > 0) ? mysqli_fetch_assoc($res) : null;
		if (!$row) {
			$this->json_out(array('status' => 'notfound', 'message' => 'No student found with this ID. You can still fill details manually.'));
		}
		$this->json_out(array(
			'status' => 'success',
			'data' => $this->student_payload($row),
		));
	}

	public function search_students($q, $school = '')
	{
		$this->require_school_user();
		$q = trim((string) $q);
		$school = strtoupper(trim((string) $school));
		$allowed_schools = array('SGMS', 'SHS', 'SIS', 'SMS');
		$where = array('1=1');
		if (in_array($school, $allowed_schools, true)) {
			$where[] = "s.`stc_school_student_studid` LIKE '".$this->esc($school)."%'";
		}
		if ($q !== '') {
			$like = '%'.$this->esc($q).'%';
			$where[] = "(
				s.`stc_school_student_studid` LIKE '".$like."'
				OR s.`stc_school_student_firstname` LIKE '".$like."'
				OR s.`stc_school_student_lastname` LIKE '".$like."'
				OR CONCAT(s.`stc_school_student_firstname`, ' ', s.`stc_school_student_lastname`) LIKE '".$like."'
			)";
		}
		$res = mysqli_query($this->stc_dbs, $this->student_select_sql()."
			WHERE ".implode(' AND ', $where)."
			ORDER BY s.`stc_school_student_studid` ASC
			LIMIT 20
		");
		$rows = array();
		if ($res) {
			while ($row = mysqli_fetch_assoc($res)) {
				$rows[] = $this->student_payload($row);
			}
		}
		$this->json_out(array('status' => 'success', 'data' => $rows));
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
		$last_status = trim((string) ($p['last_status'] ?? ''));
		$last_remarks = trim((string) ($p['last_remarks'] ?? ''));
		$marks_percent = $this->parse_marks_percent($p['marks_percent'] ?? '');
		$student_pk = (int) ($p['student_pk'] ?? 0);
		if ($marks_percent === null) {
			$this->json_out(array('status' => 'empty', 'message' => 'Enter last marks %.'));
		}

		if ($school === '' || $studid === '' || $firstname === '' || $lastname === '' || $contact === '' || $guardianname === '' || $last_status === '') {
			$this->json_out(array('status' => 'empty', 'message' => 'Please fill school, student ID, name, contact, guardian, last status and admission amount.'));
		}
		if ($amount <= 0) {
			$this->json_out(array('status' => 'empty', 'message' => 'Enter a valid admission amount.'));
		}
		$allowed_schools = array('SGMS', 'SHS', 'SIS', 'SMS');
		if (preg_match('/^(SGMS|SHS|SIS|SMS)/i', $studid, $school_match)) {
			$school = strtoupper($school_match[1]);
		}
		if (!in_array($school, $allowed_schools, true)) {
			$this->json_out(array('status' => 'empty', 'message' => 'Select a valid school.'));
		}

		if ($student_pk <= 0) {
			$chk = mysqli_query($this->stc_dbs, "
				SELECT `stc_school_student_id`
				FROM `stc_school_student`
				WHERE TRIM(`stc_school_student_studid`)='".$this->esc($studid)."'
				LIMIT 1
			");
			if ($chk && ($found = mysqli_fetch_assoc($chk))) {
				$student_pk = (int) $found['stc_school_student_id'];
			}
		}

		$pending = mysqli_query($this->stc_dbs, "
			SELECT `stc_school_readmission_id`
			FROM `stc_school_readmission`
			WHERE `stc_school_readmission_studid`='".$this->esc($studid)."'
			  AND `stc_school_readmission_status`='pending'
			LIMIT 1
		");
		if ($pending && mysqli_num_rows($pending) > 0) {
			$this->json_out(array('status' => 'duplicate', 'message' => 'A pending readmission already exists for this student ID.'));
		}

		$created_by = (int) $_SESSION['stc_school_user_id'];
		$now = date('Y-m-d H:i:s');
		$sql = "
			INSERT INTO `stc_school_readmission`(
				`stc_school_readmission_school`,
				`stc_school_readmission_student_pk`,
				`stc_school_readmission_studid`,
				`stc_school_readmission_firstname`,
				`stc_school_readmission_lastname`,
				`stc_school_readmission_dob`,
				`stc_school_readmission_gender`,
				`stc_school_readmission_bloodgroup`,
				`stc_school_readmission_email`,
				`stc_school_readmission_contact`,
				`stc_school_readmission_address`,
				`stc_school_readmission_religion`,
				`stc_school_readmission_admissiondate`,
				`stc_school_readmission_classroomid`,
				`stc_school_readmission_guardianname`,
				`stc_school_readmission_student_remarks`,
				`stc_school_readmission_amount`,
				`stc_school_readmission_last_status`,
				`stc_school_readmission_last_remarks`,
				`stc_school_readmission_marks_percent`,
				`stc_school_readmission_status`,
				`stc_school_readmission_created_by`,
				`stc_school_readmission_created_date`
			) VALUES (
				'".$this->esc($school)."',
				'".(int) $student_pk."',
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
				'".$this->esc($last_status)."',
				'".$this->esc($last_remarks)."',
				".$this->sql_marks($marks_percent).",
				'pending',
				'".$created_by."',
				'".$this->esc($now)."'
			)
		";
		$ok = mysqli_query($this->stc_dbs, $sql);
		if (!$ok) {
			$this->json_out(array('status' => 'wrong', 'message' => 'Could not save readmission. Please try again.'));
		}
		$this->json_out(array('status' => 'success', 'message' => 'Readmission submitted. Waiting for director approval.', 'id' => (int) mysqli_insert_id($this->stc_dbs)));
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
				r.`stc_school_readmission_studid` LIKE '%".$s."%'
				OR r.`stc_school_readmission_firstname` LIKE '%".$s."%'
				OR r.`stc_school_readmission_lastname` LIKE '%".$s."%'
				OR CONCAT(r.`stc_school_readmission_firstname`,' ',r.`stc_school_readmission_lastname`) LIKE '%".$s."%'
				OR r.`stc_school_readmission_school` LIKE '%".$s."%'
				OR r.`stc_school_readmission_last_status` LIKE '%".$s."%'
				OR r.`stc_school_readmission_status` LIKE '%".$s."%'
			)";
		}
		$qry = mysqli_query($this->stc_dbs, "
			SELECT
				r.*,
				c.`stc_school_class_title`,
				u.`stc_school_user_fullName`
			FROM `stc_school_readmission` r
			LEFT JOIN `stc_school_class` c ON c.`stc_school_class_id` = r.`stc_school_readmission_classroomid`
			LEFT JOIN `stc_school` u ON u.`stc_school_user_id` = r.`stc_school_readmission_created_by`
			".$where."
			ORDER BY r.`stc_school_readmission_id` DESC
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
		$status = (string) ($row['stc_school_readmission_status'] ?? 'pending');
		$final = $row['stc_school_readmission_final_amount'];
		return array(
			'id' => (int) $row['stc_school_readmission_id'],
			'school' => preg_match('/^(SGMS|SHS|SIS|SMS)/i', (string) $row['stc_school_readmission_studid'], $school_match)
				? strtoupper($school_match[1])
				: (string) $row['stc_school_readmission_school'],
			'studid' => (string) $row['stc_school_readmission_studid'],
			'name' => trim($row['stc_school_readmission_firstname'].' '.$row['stc_school_readmission_lastname']),
			'contact' => (string) $row['stc_school_readmission_contact'],
			'classroom' => (string) ($row['stc_school_class_title'] ?? ''),
			'amount' => (float) $row['stc_school_readmission_amount'],
			'final_amount' => $final === null || $final === '' ? null : (float) $final,
			'last_status' => (string) $row['stc_school_readmission_last_status'],
			'last_remarks' => (string) $row['stc_school_readmission_last_remarks'],
			'marks_percent' => ($row['stc_school_readmission_marks_percent'] ?? '') === '' || $row['stc_school_readmission_marks_percent'] === null
				? null
				: (float) $row['stc_school_readmission_marks_percent'],
			'status' => $status,
			'created_by' => (string) ($row['stc_school_user_fullName'] ?? ''),
			'created_date' => (string) $row['stc_school_readmission_created_date'],
		);
	}

	public function handle()
	{
		if (isset($_POST['stc_readmit_lookup'])) {
			$this->lookup_student($_POST['studid'] ?? '');
		}
		if (isset($_POST['stc_readmit_search'])) {
			$this->search_students($_POST['q'] ?? '', $_POST['school'] ?? '');
		}
		if (isset($_POST['stc_readmit_save'])) {
			$this->save($_POST);
		}
		if (isset($_POST['stc_readmit_list'])) {
			$this->list_rows($_POST['search'] ?? '');
		}
	}
}

$stcReadmit = new StcSchoolReadmission();
$stcReadmit->handle();
