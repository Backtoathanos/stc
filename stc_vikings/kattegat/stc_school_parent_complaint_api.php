<?php
/**
 * JSON API — school parent/guardian complaints (`stc_school_parent_request`).
 * Same DB as MCU/db.php (e.g. stc_associate_go).
 *
 * Actions handled:
 *   stc_list_parent_complaints      — list all (director view)
 *   stc_get_parent_complaint        — single row detail
 *   stc_mark_parent_complaint_read  — mark new→read
 *   stc_close_parent_complaint      — director marks closed + action text
 *   stc_pass_to_school_admin        — director forwards to school admin
 */
declare(strict_types=1);

require_once __DIR__ . '/auth_helper.php';

header('Content-Type: application/json; charset=UTF-8');

if (!STCAuthHelper::resumeSessionForApi()) {
	echo json_encode(['success' => false, 'message' => 'Session expired. Please sign in again.']);
	exit;
}

require_once __DIR__ . '/../../MCU/db.php';

if (!isset($con) || !$con instanceof mysqli) {
	echo json_encode(['success' => false, 'message' => 'Database unavailable.']);
	exit;
}

mysqli_set_charset($con, 'utf8mb4');

/**
 * @return array<string, mixed>
 */
function stc_prc_map_row_full(array $r): array {
	return [
		'id'                    => (int)    ($r['stc_school_parent_request_id']                   ?? 0),
		'parent_name'           => (string) ($r['stc_school_parent_request_parent_name']           ?? ''),
		'email'                 => (string) ($r['stc_school_parent_request_email']                 ?? ''),
		'phone'                 => (string) ($r['stc_school_parent_request_phone']                 ?? ''),
		'student_name'          => (string) ($r['stc_school_parent_request_student_name']          ?? ''),
		'student_id'            => (string) ($r['stc_school_parent_request_student_id']            ?? ''),
		'subject'               => (string) ($r['stc_school_parent_request_subject']               ?? ''),
		'message'               => (string) ($r['stc_school_parent_request_message']               ?? ''),
		'status'                => (string) ($r['stc_school_parent_request_status']                ?? 'new'),
		'action_taken'          => (string) ($r['stc_school_parent_request_action_taken']          ?? ''),
		'createdate'            => (string) ($r['stc_school_parent_request_createdate']            ?? ''),
		'passed_to_school'      => (int)    ($r['stc_school_parent_request_passed_to_school']      ?? 0),
		'passed_date'           => (string) ($r['stc_school_parent_request_passed_date']           ?? ''),
		'school_note'           => (string) ($r['stc_school_parent_request_school_note']           ?? ''),
		'school_status'         => (string) ($r['stc_school_parent_request_school_status']         ?? ''),
		'school_resolved_date'  => (string) ($r['stc_school_parent_request_school_resolved_date']  ?? ''),
	];
}

function stc_prc_message_preview(string $msg, int $max = 120): string {
	if (function_exists('mb_substr') && function_exists('mb_strlen')) {
		$t = mb_substr($msg, 0, $max, 'UTF-8');
		if (mb_strlen($msg, 'UTF-8') > $max) {
			$t .= '…';
		}
		return $t;
	}
	$t = substr($msg, 0, $max);
	return strlen($msg) > $max ? $t . '…' : $t;
}

function stc_prc_text_len(string $msg): int {
	if (function_exists('mb_strlen')) {
		return mb_strlen($msg, 'UTF-8');
	}
	return strlen($msg);
}

/* ------------------------------------------------------------------ */
/* LIST                                                                 */
/* ------------------------------------------------------------------ */
if (isset($_POST['stc_list_parent_complaints'])) {
	$rows = [];
	$sql = '
		SELECT
			`stc_school_parent_request_id`,
			`stc_school_parent_request_parent_name`,
			`stc_school_parent_request_email`,
			`stc_school_parent_request_phone`,
			`stc_school_parent_request_student_name`,
			`stc_school_parent_request_student_id`,
			`stc_school_parent_request_subject`,
			`stc_school_parent_request_message`,
			`stc_school_parent_request_status`,
			`stc_school_parent_request_action_taken`,
			`stc_school_parent_request_createdate`,
			COALESCE(`stc_school_parent_request_passed_to_school`, 0) AS `stc_school_parent_request_passed_to_school`,
			COALESCE(`stc_school_parent_request_passed_date`, \'\') AS `stc_school_parent_request_passed_date`,
			COALESCE(`stc_school_parent_request_school_note`, \'\') AS `stc_school_parent_request_school_note`,
			COALESCE(`stc_school_parent_request_school_status`, \'\') AS `stc_school_parent_request_school_status`,
			COALESCE(`stc_school_parent_request_school_resolved_date`, \'\') AS `stc_school_parent_request_school_resolved_date`
		FROM `stc_school_parent_request`
		ORDER BY `stc_school_parent_request_createdate` DESC
		LIMIT 500
	';
	if ($rs = mysqli_query($con, $sql)) {
		while ($r = mysqli_fetch_assoc($rs)) {
			$full = stc_prc_map_row_full($r);
			$full['message_preview'] = stc_prc_message_preview($full['message']);
			$full['action_preview']  = stc_prc_message_preview($full['action_taken'], 80);
			$full['school_note_preview'] = stc_prc_message_preview($full['school_note'], 80);
			unset($full['message']);
			$rows[] = $full;
		}
		mysqli_free_result($rs);
		echo json_encode(['success' => true, 'rows' => $rows]);
		exit;
	}
	$err = mysqli_error($con);
	if ($err !== '' && (stripos($err, 'Unknown column') !== false || stripos($err, "doesn't exist") !== false)) {
		echo json_encode([
			'success' => false,
			'message' => 'Table or column missing. Run sql/stc_school_parent_request.sql and stc_umbrella/sql/stc_parent_complaint_school_admin_alter.sql.',
		]);
		exit;
	}
	echo json_encode(['success' => false, 'message' => 'Could not load complaints.']);
	exit;
}

/* ------------------------------------------------------------------ */
/* GET SINGLE                                                           */
/* ------------------------------------------------------------------ */
if (isset($_POST['stc_get_parent_complaint'])) {
	$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
	if ($id <= 0) {
		echo json_encode(['success' => false, 'message' => 'Invalid id.']);
		exit;
	}
	$stmt = mysqli_prepare($con, '
		SELECT
			`stc_school_parent_request_id`,
			`stc_school_parent_request_parent_name`,
			`stc_school_parent_request_email`,
			`stc_school_parent_request_phone`,
			`stc_school_parent_request_student_name`,
			`stc_school_parent_request_student_id`,
			`stc_school_parent_request_subject`,
			`stc_school_parent_request_message`,
			`stc_school_parent_request_status`,
			`stc_school_parent_request_action_taken`,
			`stc_school_parent_request_createdate`,
			COALESCE(`stc_school_parent_request_passed_to_school`, 0) AS `stc_school_parent_request_passed_to_school`,
			COALESCE(`stc_school_parent_request_passed_date`, \'\') AS `stc_school_parent_request_passed_date`,
			COALESCE(`stc_school_parent_request_school_note`, \'\') AS `stc_school_parent_request_school_note`,
			COALESCE(`stc_school_parent_request_school_status`, \'\') AS `stc_school_parent_request_school_status`,
			COALESCE(`stc_school_parent_request_school_resolved_date`, \'\') AS `stc_school_parent_request_school_resolved_date`
		FROM `stc_school_parent_request`
		WHERE `stc_school_parent_request_id` = ?
		LIMIT 1
	');
	if (!$stmt) {
		echo json_encode(['success' => false, 'message' => 'Query prepare failed.', 'detail' => mysqli_error($con)]);
		exit;
	}
	mysqli_stmt_bind_param($stmt, 'i', $id);
	if (!mysqli_stmt_execute($stmt)) {
		mysqli_stmt_close($stmt);
		echo json_encode(['success' => false, 'message' => 'Could not load record.']);
		exit;
	}
	$r = null;
	if (function_exists('mysqli_stmt_get_result')) {
		$res = mysqli_stmt_get_result($stmt);
		$r   = $res ? mysqli_fetch_assoc($res) : null;
	} else {
		$row_id = $parent_name = $email = $phone = $student_name = $student_id =
		$subject = $message = $status = $action_taken = $createdate =
		$passed_to_school = $passed_date = $school_note = $school_status = $school_resolved_date = null;
		mysqli_stmt_bind_result(
			$stmt,
			$row_id, $parent_name, $email, $phone, $student_name, $student_id,
			$subject, $message, $status, $action_taken, $createdate,
			$passed_to_school, $passed_date, $school_note, $school_status, $school_resolved_date
		);
		if (mysqli_stmt_fetch($stmt)) {
			$r = [
				'stc_school_parent_request_id'                  => $row_id,
				'stc_school_parent_request_parent_name'          => $parent_name,
				'stc_school_parent_request_email'                => $email,
				'stc_school_parent_request_phone'                => $phone,
				'stc_school_parent_request_student_name'         => $student_name,
				'stc_school_parent_request_student_id'           => $student_id,
				'stc_school_parent_request_subject'              => $subject,
				'stc_school_parent_request_message'              => $message,
				'stc_school_parent_request_status'               => $status,
				'stc_school_parent_request_action_taken'         => $action_taken,
				'stc_school_parent_request_createdate'           => $createdate,
				'stc_school_parent_request_passed_to_school'     => $passed_to_school,
				'stc_school_parent_request_passed_date'          => $passed_date,
				'stc_school_parent_request_school_note'          => $school_note,
				'stc_school_parent_request_school_status'        => $school_status,
				'stc_school_parent_request_school_resolved_date' => $school_resolved_date,
			];
		}
	}
	mysqli_stmt_close($stmt);
	if (!$r) {
		echo json_encode(['success' => false, 'message' => 'Record not found.']);
		exit;
	}
	echo json_encode(['success' => true, 'row' => stc_prc_map_row_full($r)]);
	exit;
}

/* ------------------------------------------------------------------ */
/* MARK READ                                                            */
/* ------------------------------------------------------------------ */
if (isset($_POST['stc_mark_parent_complaint_read'])) {
	$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
	if ($id <= 0) {
		echo json_encode(['success' => false, 'message' => 'Invalid id.']);
		exit;
	}
	$stmt = mysqli_prepare($con, '
		UPDATE `stc_school_parent_request`
		SET `stc_school_parent_request_status` = \'read\'
		WHERE `stc_school_parent_request_id` = ?
		  AND `stc_school_parent_request_status` = \'new\'
	');
	if (!$stmt) {
		echo json_encode(['success' => false, 'message' => 'Update prepare failed.', 'detail' => mysqli_error($con)]);
		exit;
	}
	mysqli_stmt_bind_param($stmt, 'i', $id);
	if (!mysqli_stmt_execute($stmt)) {
		$err = mysqli_stmt_error($stmt);
		mysqli_stmt_close($stmt);
		echo json_encode(['success' => false, 'message' => 'Could not update status.', 'detail' => $err]);
		exit;
	}
	mysqli_stmt_close($stmt);
	echo json_encode(['success' => true, 'message' => 'Marked as read.']);
	exit;
}

/* ------------------------------------------------------------------ */
/* CLOSE (director marks done)                                          */
/* ------------------------------------------------------------------ */
if (isset($_POST['stc_close_parent_complaint'])) {
	$id     = isset($_POST['id']) ? (int) $_POST['id'] : 0;
	$action = isset($_POST['action_taken']) ? trim((string) $_POST['action_taken']) : '';
	if ($id <= 0) {
		echo json_encode(['success' => false, 'message' => 'Invalid id.']);
		exit;
	}
	if ($action === '' || stc_prc_text_len($action) > 8000) {
		echo json_encode(['success' => false, 'message' => 'Please enter action taken (1–8000 characters).']);
		exit;
	}
	$stmt = mysqli_prepare($con, '
		UPDATE `stc_school_parent_request`
		SET `stc_school_parent_request_action_taken` = ?,
			`stc_school_parent_request_status` = \'closed\'
		WHERE `stc_school_parent_request_id` = ?
		  AND `stc_school_parent_request_status` <> \'closed\'
	');
	if (!$stmt) {
		echo json_encode(['success' => false, 'message' => 'Update prepare failed.', 'detail' => mysqli_error($con)]);
		exit;
	}
	mysqli_stmt_bind_param($stmt, 'si', $action, $id);
	if (!mysqli_stmt_execute($stmt)) {
		$err = mysqli_stmt_error($stmt);
		mysqli_stmt_close($stmt);
		echo json_encode(['success' => false, 'message' => 'Could not save.', 'detail' => $err]);
		exit;
	}
	$affected = mysqli_stmt_affected_rows($stmt);
	mysqli_stmt_close($stmt);
	if ($affected !== 1) {
		echo json_encode(['success' => false, 'message' => 'Nothing updated — request missing or already closed.']);
		exit;
	}
	echo json_encode(['success' => true, 'message' => 'Marked as closed.']);
	exit;
}

/* ------------------------------------------------------------------ */
/* PASS TO SCHOOL ADMIN (director forwards complaint)                  */
/* ------------------------------------------------------------------ */
if (isset($_POST['stc_pass_to_school_admin'])) {
	$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
	if ($id <= 0) {
		echo json_encode(['success' => false, 'message' => 'Invalid id.']);
		exit;
	}
	$now = date('Y-m-d H:i:s');
	$stmt = mysqli_prepare($con, '
		UPDATE `stc_school_parent_request`
		SET `stc_school_parent_request_passed_to_school` = 1,
		    `stc_school_parent_request_passed_date` = ?
		WHERE `stc_school_parent_request_id` = ?
		  AND COALESCE(`stc_school_parent_request_passed_to_school`, 0) = 0
	');
	if (!$stmt) {
		echo json_encode(['success' => false, 'message' => 'Prepare failed.', 'detail' => mysqli_error($con)]);
		exit;
	}
	mysqli_stmt_bind_param($stmt, 'si', $now, $id);
	if (!mysqli_stmt_execute($stmt)) {
		$err = mysqli_stmt_error($stmt);
		mysqli_stmt_close($stmt);
		echo json_encode(['success' => false, 'message' => 'Could not pass complaint.', 'detail' => $err]);
		exit;
	}
	$affected = mysqli_stmt_affected_rows($stmt);
	mysqli_stmt_close($stmt);
	if ($affected !== 1) {
		echo json_encode(['success' => false, 'message' => 'Already passed to school admin or record not found.']);
		exit;
	}
	echo json_encode(['success' => true, 'message' => 'Complaint forwarded to school admin.']);
	exit;
}

echo json_encode(['success' => false, 'message' => 'No valid action.']);
