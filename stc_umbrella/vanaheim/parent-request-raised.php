<?php
/**
 * Parent request — AJAX API + helpers (student check, save, notification mail).
 * Loads `MCU/db.php` for `stc_school_parent_request` and `MCU/obdb.php` for `stc_school_student`.
 * Email bodies: `../templates/parent-request/parent-thanks.php`, `director-notification.php`.
 * Run sql/stc_school_parent_request.sql if table missing.
 *
 * Optional env: STC_SCHOOL_MAIL_FROM, STC_SCHOOL_PARENT_REQUEST_DIRECTOR_EMAIL
 */
date_default_timezone_set('Asia/Kolkata');
include_once __DIR__ . '/../../MCU/obdb.php';

/**
 * @return string digits only
 */
function pr_parent_req_phone_digits($s) {
	return preg_replace('/\D+/', '', (string) $s);
}

function pr_parent_req_text_len($s) {
	if (function_exists('mb_strlen')) {
		return mb_strlen((string) $s, 'UTF-8');
	}
	return strlen((string) $s);
}

function pr_parent_req_hash_equals($known, $user) {
	if (function_exists('hash_equals')) {
		return hash_equals((string) $known, (string) $user);
	}
	$known = (string) $known;
	$user = (string) $user;
	if (strlen($known) !== strlen($user)) {
		return false;
	}
	$result = 0;
	for ($i = 0, $len = strlen($known); $i < $len; $i++) {
		$result |= ord($known[$i]) ^ ord($user[$i]);
	}
	return $result === 0;
}

function pr_parent_req_random_token() {
	if (function_exists('random_bytes')) {
		return bin2hex(random_bytes(32));
	}
	if (function_exists('openssl_random_pseudo_bytes')) {
		return bin2hex(openssl_random_pseudo_bytes(32));
	}
	return sha1(uniqid((string) mt_rand(), true) . microtime(true));
}

/**
 * True if submitted phone matches DB contact (+91 / spaces allowed; same last 10 digits if both long enough).
 */
function pr_parent_req_phones_match($input_phone, $db_contact) {
	$a = pr_parent_req_phone_digits($input_phone);
	$b = pr_parent_req_phone_digits($db_contact);
	if ($a === '' || $b === '') {
		return false;
	}
	if ($a === $b) {
		return true;
	}
	if (strlen($a) >= 10 && strlen($b) >= 10) {
		return (substr($a, -10) === substr($b, -10));
	}
	return false;
}

/**
 * htmlspecialchars wrapper for email templates.
 *
 * @return callable(string): string
 */
function pr_parent_req_h_esc() {
	return function ($s) {
		return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
	};
}

/**
 * Render HTML under stc_umbrella/templates/parent-request/{name}.php (whitelisted filenames only).
 *
 * @param string $basename 'parent-thanks' or 'director-notification'
 */
function pr_parent_req_render_email_template($basename, array $vars) {
	$name = basename((string) $basename);
	$allowed = ['parent-thanks' => true, 'director-notification' => true];
	if (empty($allowed[$name])) {
		return '';
	}
	$dir = realpath(__DIR__ . '/../templates/parent-request');
	if ($dir === false) {
		return '';
	}
	$path = realpath($dir . DIRECTORY_SEPARATOR . $name . '.php');
	if ($path === false || strpos($path, $dir) !== 0 || !is_readable($path)) {
		return '';
	}
	extract($vars, EXTR_SKIP);
	if (!isset($h) || !is_callable($h)) {
		$h = pr_parent_req_h_esc();
	}
	$GLOBALS['_stc_parent_req_mail_embedded'] = true;
	try {
		ob_start();
		include $path;
		return (string) ob_get_clean();
	} finally {
		unset($GLOBALS['_stc_parent_req_mail_embedded']);
	}
}

function pr_parent_req_mail_from_header() {
	$addr = getenv('STC_SCHOOL_MAIL_FROM');
	if (!is_string($addr) || trim($addr) === '') {
		$addr = 'noreply@stcassociate.com';
	}
	return 'STC School <' . trim($addr) . '>';
}

/** Director / office inbox for new parent-request alerts (override via env). */
function pr_parent_req_director_email() {
	$e = getenv('STC_SCHOOL_PARENT_REQUEST_DIRECTOR_EMAIL');
	if (is_string($e) && trim($e) !== '') {
		return trim($e);
	}
	return 'safikulislam283@gmail.com';
}

function pr_parent_req_mail_subject_line($line) {
	if (preg_match('/[^\x20-\x7E]/', (string) $line)) {
		return '=?UTF-8?B?' . base64_encode((string) $line) . '?=';
	}
	return (string) $line;
}

/**
 * Sends parent acknowledgement + director notification (non-blocking for API: logs on failure).
 *
 * @param array $m request_id, parent_name, email, phone, student_name, student_id, subject, message, submitted_at
 */
function pr_parent_req_send_notification_mails(array $m) {
	$h = pr_parent_req_h_esc();
	$readable = !empty($m['submitted_at'])
		? date('j F Y, g:i A', strtotime((string) $m['submitted_at']))
		: date('j F Y, g:i A');

	$director = pr_parent_req_director_email();

	$parentBody = pr_parent_req_render_email_template('parent-thanks', [
		'h' => $h,
		'parent_name' => (string) ($m['parent_name'] ?? ''),
		'submitted_readable' => $readable,
	]);

	$directorBody = pr_parent_req_render_email_template('director-notification', [
		'h' => $h,
		'request_id' => (int) ($m['request_id'] ?? 0),
		'parent_name' => (string) ($m['parent_name'] ?? ''),
		'email' => (string) ($m['email'] ?? ''),
		'phone' => (string) ($m['phone'] ?? ''),
		'student_name' => (string) ($m['student_name'] ?? ''),
		'student_id' => (string) ($m['student_id'] ?? ''),
		'subject_line' => (string) ($m['subject'] ?? ''),
		'message_body' => (string) ($m['message'] ?? ''),
		'submitted_readable' => $readable,
	]);

	$headersBase = "MIME-Version: 1.0\r\n"
		. "Content-Type: text/html; charset=UTF-8\r\n"
		. 'From: ' . pr_parent_req_mail_from_header() . "\r\n";

	$parentTo = (string) ($m['email'] ?? '');
	if (filter_var($parentTo, FILTER_VALIDATE_EMAIL) && $parentBody !== '') {
		$subj = pr_parent_req_mail_subject_line('STC School — we have received your request');
		$hdr = $headersBase;
		if (filter_var($director, FILTER_VALIDATE_EMAIL)) {
			$hdr .= 'Reply-To: ' . $director . "\r\n";
		}
		if (!@mail($parentTo, $subj, $parentBody, $hdr)) {
			error_log('parent-request-raised: parent acknowledgement mail() failed to ' . $parentTo);
		}
	}

	if ($directorBody !== '' && filter_var($director, FILTER_VALIDATE_EMAIL)) {
		$subj = pr_parent_req_mail_subject_line('STC School — new parent request #' . (int) ($m['request_id'] ?? 0));
		$hdr = $headersBase;
		if (filter_var($parentTo, FILTER_VALIDATE_EMAIL)) {
			$hdr .= 'Reply-To: ' . $parentTo . "\r\n";
		}
		if (!@mail($director, $subj, $directorBody, $hdr)) {
			error_log('parent-request-raised: director notification mail() failed to ' . $director);
		}
	}
}

class PrParentRequestHel extends tesseract {

	/**
	 * @return array{ok:bool,message:string}
	 */
	public function validate_student_phone($student_id, $phone) {
		$student_id = trim((string) $student_id);
		$phone = trim((string) $phone);

		if ($student_id === '') {
			return ['ok' => false, 'message' => 'Please enter the student admission / ID number on file with the school.'];
		}
		if (strlen($student_id) > 64) {
			return ['ok' => false, 'message' => 'Student admission / ID is too long.'];
		}
		if ($phone === '') {
			return ['ok' => false, 'message' => 'Please enter the mobile / phone number.'];
		}
		if (strlen($phone) > 40) {
			return ['ok' => false, 'message' => 'Phone number is too long.'];
		}

		$stmt = mysqli_prepare($this->stc_dbs, '
			SELECT `stc_school_student_contact`
			FROM `stc_school_student`
			WHERE TRIM(`stc_school_student_studid`) = ?
			  AND `stc_school_student_status` = \'1\'
			LIMIT 1
		');
		if (!$stmt) {
			return ['ok' => false, 'message' => 'Could not verify student details. Please try again later.'];
		}
		mysqli_stmt_bind_param($stmt, 's', $student_id);
		if (!mysqli_stmt_execute($stmt)) {
			mysqli_stmt_close($stmt);
			return ['ok' => false, 'message' => 'Could not verify student details. Please try again later.'];
		}
		$student_contact = null;
		mysqli_stmt_bind_result($stmt, $student_contact);
		$row = mysqli_stmt_fetch($stmt) ? ['stc_school_student_contact' => $student_contact] : null;
		mysqli_stmt_close($stmt);

		if (!$row) {
			return ['ok' => false, 'message' => 'We could not find an active student with this admission / ID number. Please check the ID or call the school office.'];
		}

		if (!pr_parent_req_phones_match($phone, (string) $row['stc_school_student_contact'])) {
			return ['ok' => false, 'message' => 'The mobile / phone number does not match our records for this admission / ID. Use the contact number linked to this student profile, or call the office.'];
		}

		return ['ok' => true, 'message' => 'success'];
	}

	/**
	 * Full save pipeline: validates fields, student+phone match, duplicate day, INSERT.
	 *
	 * @return array{ok:bool,messages:string[],mail_payload?:array<string,mixed>}
	 */
	public function save_parent_request(mysqli $con, array $p) {
		$errors = [];
		$parent_name = isset($p['parent_name']) ? trim((string) $p['parent_name']) : '';
		$email = isset($p['email']) ? trim((string) $p['email']) : '';
		$phone = isset($p['phone']) ? trim((string) $p['phone']) : '';
		$student_name = isset($p['student_name']) ? trim((string) $p['student_name']) : '';
		$student_id = isset($p['student_id']) ? trim((string) $p['student_id']) : '';
		$subject = isset($p['subject']) ? trim((string) $p['subject']) : '';
		$message = isset($p['message']) ? trim((string) $p['message']) : '';
		$allowed_subjects = [
			'Academic Related' => true,
			'Accommodation Related' => true,
			'Behaviour Based' => true,
			'Miscellaneous' => true,
		];

		if ($parent_name === '' || pr_parent_req_text_len($parent_name) > 160) {
			$errors[] = 'Please enter parent / guardian name (max 160 characters).';
		}
		if ($email !== '' && (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 190)) {
			$errors[] = 'Please enter a valid email address, or leave email blank.';
		}
		if ($phone === '' || strlen($phone) > 40) {
			$errors[] = 'Please enter contact number.';
		}
		if ($subject === '' || empty($allowed_subjects[$subject])) {
			$errors[] = 'Please select a valid topic for your request.';
		}
		if ($message === '' || pr_parent_req_text_len($message) > 6000) {
			$errors[] = 'Please describe your request (maximum 6000 characters).';
		}
		if (pr_parent_req_text_len($student_name) > 160 || strlen($student_id) > 64) {
			$errors[] = 'Student details are too long.';
		}

		if (empty($errors)) {
			$stud_chk = $this->validate_student_phone($student_id, $phone);
			if (!$stud_chk['ok']) {
				$errors[] = $stud_chk['message'];
			}
		}

		if (!empty($errors)) {
			return ['ok' => false, 'messages' => $errors];
		}

		$student_name_db = $student_name;
		$student_id_db = $student_id;
		$now = date('Y-m-d H:i:s');
		$today_ymd = date('Y-m-d');

		if (!$con) {
			return ['ok' => false, 'messages' => ['Service temporarily unavailable — please try again later.']];
		}

		$contact_for_dup = ($email !== '') ? strtolower(trim($email)) : trim($phone);
		$contact_column = ($email !== '')
			? 'LOWER(TRIM(`stc_school_parent_request_email`))'
			: 'TRIM(`stc_school_parent_request_phone`)';
		$study_id_trim = trim($student_id_db);
		$dup_stmt = mysqli_prepare($con, '
			SELECT COUNT(*) AS c
			FROM `stc_school_parent_request`
			WHERE ' . $contact_column . ' = ?
			  AND DATE(`stc_school_parent_request_createdate`) = ?
			  AND TRIM(`stc_school_parent_request_student_id`) = ?
		');
		if ($dup_stmt) {
			mysqli_stmt_bind_param($dup_stmt, 'sss',
				$contact_for_dup,
				$today_ymd,
				$study_id_trim
			);
			if (mysqli_stmt_execute($dup_stmt)) {
				$dup_count = 0;
				mysqli_stmt_bind_result($dup_stmt, $dup_count);
				if (mysqli_stmt_fetch($dup_stmt) && (int) $dup_count > 0) {
					$contact_label = ($email !== '') ? 'email' : 'phone number';
					$dup_msg = ($study_id_trim === '')
						? 'You have already submitted a request today using this ' . $contact_label . ' (same calendar day). You can submit again tomorrow, or call the school office if it is urgent.'
						: 'You have already submitted a request today using this ' . $contact_label . ' and the same student admission / ID number. You can submit again tomorrow, call the office if urgent, or use a different student ID only if sending for another learner.';
					mysqli_stmt_close($dup_stmt);
					return ['ok' => false, 'messages' => [$dup_msg]];
				}
			}
			mysqli_stmt_close($dup_stmt);
		}

		$stmt = mysqli_prepare($con, '
			INSERT INTO `stc_school_parent_request` (
				`stc_school_parent_request_parent_name`,
				`stc_school_parent_request_email`,
				`stc_school_parent_request_phone`,
				`stc_school_parent_request_student_name`,
				`stc_school_parent_request_student_id`,
				`stc_school_parent_request_subject`,
				`stc_school_parent_request_message`,
				`stc_school_parent_request_status`,
				`stc_school_parent_request_createdate`
			) VALUES (?, ?, ?, ?, ?, ?, ?, \'new\', ?)
		');
		if (!$stmt) {
			$db_note = htmlspecialchars((string) mysqli_error($con), ENT_QUOTES, 'UTF-8');
			return ['ok' => false, 'messages' => ['Could not prepare request save.'.($db_note !== '' ? ' ('.substr($db_note, 0, 220).') ' : '').' Run sql/stc_school_parent_request.sql if the table is missing.']];
		}

		mysqli_stmt_bind_param($stmt, 'ssssssss',
			$parent_name,
			$email,
			$phone,
			$student_name_db,
			$student_id_db,
			$subject,
			$message,
			$now
		);

		if (!mysqli_stmt_execute($stmt)) {
			$db_note = htmlspecialchars((string) mysqli_stmt_error($stmt), ENT_QUOTES, 'UTF-8');
			if ($db_note === '') {
				$db_note = htmlspecialchars((string) mysqli_error($con), ENT_QUOTES, 'UTF-8');
			}
			mysqli_stmt_close($stmt);
			if (strpos((string) $db_note, "doesn't exist") !== false || stripos((string) $db_note, 'Unknown table') !== false) {
				return ['ok' => false, 'messages' => ['The requests table does not exist on this database yet. ICT must run: sql/stc_school_parent_request.sql']];
			}
			return ['ok' => false, 'messages' => ['Could not save your request. '.($db_note !== '' ? '('.substr($db_note, 0, 220).') ' : '').'Please try again or contact IT.']];
		}
		if (mysqli_stmt_affected_rows($stmt) !== 1) {
			mysqli_stmt_close($stmt);
			return ['ok' => false, 'messages' => ['Save did not create a row. Check that sql/stc_school_parent_request.sql was applied on the same database defined in MCU/db.php.']];
		}
		mysqli_stmt_close($stmt);

		$request_id = (int) mysqli_insert_id($con);

		return [
			'ok' => true,
			'messages' => [],
			'mail_payload' => [
				'request_id' => $request_id,
				'parent_name' => $parent_name,
				'email' => $email,
				'phone' => $phone,
				'student_name' => $student_name_db,
				'student_id' => $student_id_db,
				'subject' => $subject,
				'message' => $message,
				'submitted_at' => $now,
			],
		];
	}
}

#<------------------------------------------------------------------------------------------>
#<----------------------------------- AJAX: save ------------------------------------------>
#<------------------------------------------------------------------------------------------>

if (isset($_POST['save_parent_request_action'])) {
	session_start();

	header('Content-Type: application/json; charset=UTF-8');

	$response = ['success' => false, 'errors' => [], 'csrf' => null];

	$csrf = isset($_POST['csrf_token']) ? (string) $_POST['csrf_token'] : '';
	if (!pr_parent_req_hash_equals($_SESSION['parent_req_csrf'] ?? '', $csrf)) {
		$response['errors'][] = 'Session expired — please reload the page and try again.';
		echo json_encode($response);
		exit;
	}

	include_once __DIR__ . '/../../MCU/db.php';

	if (!isset($con) || !$con) {
		$response['errors'][] = 'Service temporarily unavailable — please try again later.';
		echo json_encode($response);
		exit;
	}

	$payload = [
		'parent_name' => isset($_POST['parent_name']) ? trim((string) $_POST['parent_name']) : '',
		'email' => isset($_POST['email']) ? trim((string) $_POST['email']) : '',
		'phone' => isset($_POST['phone']) ? trim((string) $_POST['phone']) : '',
		'student_name' => isset($_POST['student_name']) ? trim((string) $_POST['student_name']) : '',
		'student_id' => isset($_POST['student_id']) ? trim((string) $_POST['student_id']) : '',
		'subject' => isset($_POST['request_subject']) ? trim((string) $_POST['request_subject']) : '',
		'message' => isset($_POST['message']) ? trim((string) $_POST['message']) : '',
	];

	$hel = new PrParentRequestHel();
	$result = $hel->save_parent_request($con, $payload);

	if (!$result['ok']) {
		$response['errors'] = array_values(array_filter((array) ($result['messages'] ?? []), function ($m) {
			return ($m !== null && trim((string) $m) !== '');
		}));
		if ($response['errors'] === []) {
			$response['errors'][] = 'Your request could not be saved.';
		}
		echo json_encode($response);
		exit;
	}

	if (!empty($result['mail_payload']) && is_array($result['mail_payload'])) {
		pr_parent_req_send_notification_mails($result['mail_payload']);
	}

	$_SESSION['parent_req_csrf'] = pr_parent_req_random_token();
	$response['success'] = true;
	$response['csrf'] = $_SESSION['parent_req_csrf'];
	echo json_encode($response);
	exit;
}
