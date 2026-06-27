<?php
/**
 * JSON API — School Admin Complaint Register
 *
 * Actions:
 *   crq_list_complaints   — list complaints forwarded to school admin
 *   crq_get_complaint     — get single complaint detail
 *   crq_resolve_complaint — school admin marks as resolved with note
 *
 * Auth: school portal session (stc_school_user_id must be set).
 * DB  : MCU/db.php  (same as the rest of the umbrella portal).
 */
declare(strict_types=1);

date_default_timezone_set('Asia/Kolkata');
ini_set('session.gc_maxlifetime', '21600');
session_set_cookie_params(21600);
session_start();

header('Content-Type: application/json; charset=UTF-8');

/* --- auth guard --- */
if (empty($_SESSION['stc_school_user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Session expired. Please sign in again.']);
    exit;
}

require_once __DIR__ . '/../../MCU/db.php';

if (!isset($con) || !$con instanceof mysqli) {
    echo json_encode(['success' => false, 'message' => 'Database unavailable.']);
    exit;
}

mysqli_set_charset($con, 'utf8mb4');

/* ------------------------------------------------------------------ */
/* Helpers                                                              */
/* ------------------------------------------------------------------ */
function crq_map_row(array $r): array {
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
        'createdate'            => (string) ($r['stc_school_parent_request_createdate']            ?? ''),
        'passed_date'           => (string) ($r['stc_school_parent_request_passed_date']           ?? ''),
        'school_note'           => (string) ($r['stc_school_parent_request_school_note']           ?? ''),
        'school_status'         => (string) ($r['stc_school_parent_request_school_status']         ?? ''),
        'school_resolved_date'  => (string) ($r['stc_school_parent_request_school_resolved_date']  ?? ''),
    ];
}

function crq_preview(string $s, int $max = 120): string {
    if (function_exists('mb_substr') && function_exists('mb_strlen')) {
        $t = mb_substr($s, 0, $max, 'UTF-8');
        return mb_strlen($s, 'UTF-8') > $max ? $t . '…' : $t;
    }
    $t = substr($s, 0, $max);
    return strlen($s) > $max ? $t . '…' : $t;
}

function crq_text_len(string $s): int {
    return function_exists('mb_strlen') ? mb_strlen($s, 'UTF-8') : strlen($s);
}

/* ------------------------------------------------------------------ */
/* LIST — all complaints forwarded to school admin                      */
/* ------------------------------------------------------------------ */
if (isset($_POST['crq_list_complaints'])) {
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
            `stc_school_parent_request_createdate`,
            COALESCE(`stc_school_parent_request_passed_date`, \'\')           AS `stc_school_parent_request_passed_date`,
            COALESCE(`stc_school_parent_request_school_note`, \'\')           AS `stc_school_parent_request_school_note`,
            COALESCE(`stc_school_parent_request_school_status`, \'\')         AS `stc_school_parent_request_school_status`,
            COALESCE(`stc_school_parent_request_school_resolved_date`, \'\')  AS `stc_school_parent_request_school_resolved_date`
        FROM `stc_school_parent_request`
        WHERE COALESCE(`stc_school_parent_request_passed_to_school`, 0) = 1
        ORDER BY `stc_school_parent_request_createdate` DESC
        LIMIT 500
    ';

    $rs = mysqli_query($con, $sql);
    if (!$rs) {
        $err = mysqli_error($con);
        if (stripos($err, 'Unknown column') !== false || stripos($err, "doesn't exist") !== false) {
            echo json_encode([
                'success' => false,
                'message' => 'Column missing. Run stc_umbrella/sql/stc_parent_complaint_school_admin_alter.sql on this database.',
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Could not load complaints.']);
        }
        exit;
    }

    $rows = [];
    while ($r = mysqli_fetch_assoc($rs)) {
        $mapped = crq_map_row($r);
        $mapped['message_preview'] = crq_preview($mapped['message']);
        unset($mapped['message']);
        $rows[] = $mapped;
    }
    mysqli_free_result($rs);
    echo json_encode(['success' => true, 'rows' => $rows]);
    exit;
}

/* ------------------------------------------------------------------ */
/* GET SINGLE                                                           */
/* ------------------------------------------------------------------ */
if (isset($_POST['crq_get_complaint'])) {
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
            `stc_school_parent_request_createdate`,
            COALESCE(`stc_school_parent_request_passed_date`, \'\')           AS `stc_school_parent_request_passed_date`,
            COALESCE(`stc_school_parent_request_school_note`, \'\')           AS `stc_school_parent_request_school_note`,
            COALESCE(`stc_school_parent_request_school_status`, \'\')         AS `stc_school_parent_request_school_status`,
            COALESCE(`stc_school_parent_request_school_resolved_date`, \'\')  AS `stc_school_parent_request_school_resolved_date`
        FROM `stc_school_parent_request`
        WHERE `stc_school_parent_request_id` = ?
          AND COALESCE(`stc_school_parent_request_passed_to_school`, 0) = 1
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
        $f_id = $f_pname = $f_email = $f_phone = $f_stuname = $f_stuid =
        $f_subj = $f_msg = $f_status = $f_date = $f_pdate =
        $f_snote = $f_sstatus = $f_srdate = null;
        mysqli_stmt_bind_result($stmt,
            $f_id, $f_pname, $f_email, $f_phone, $f_stuname, $f_stuid,
            $f_subj, $f_msg, $f_status, $f_date, $f_pdate,
            $f_snote, $f_sstatus, $f_srdate
        );
        if (mysqli_stmt_fetch($stmt)) {
            $r = [
                'stc_school_parent_request_id'                  => $f_id,
                'stc_school_parent_request_parent_name'          => $f_pname,
                'stc_school_parent_request_email'                => $f_email,
                'stc_school_parent_request_phone'                => $f_phone,
                'stc_school_parent_request_student_name'         => $f_stuname,
                'stc_school_parent_request_student_id'           => $f_stuid,
                'stc_school_parent_request_subject'              => $f_subj,
                'stc_school_parent_request_message'              => $f_msg,
                'stc_school_parent_request_status'               => $f_status,
                'stc_school_parent_request_createdate'           => $f_date,
                'stc_school_parent_request_passed_date'          => $f_pdate,
                'stc_school_parent_request_school_note'          => $f_snote,
                'stc_school_parent_request_school_status'        => $f_sstatus,
                'stc_school_parent_request_school_resolved_date' => $f_srdate,
            ];
        }
    }
    mysqli_stmt_close($stmt);

    if (!$r) {
        echo json_encode(['success' => false, 'message' => 'Record not found or not forwarded to school admin.']);
        exit;
    }
    echo json_encode(['success' => true, 'row' => crq_map_row($r)]);
    exit;
}

/* ------------------------------------------------------------------ */
/* RESOLVE — school admin submits resolution note                       */
/* ------------------------------------------------------------------ */
if (isset($_POST['crq_resolve_complaint'])) {
    $id   = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $note = isset($_POST['school_note']) ? trim((string) $_POST['school_note']) : '';

    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid id.']);
        exit;
    }
    if ($note === '' || crq_text_len($note) > 8000) {
        echo json_encode(['success' => false, 'message' => 'Please enter a resolution note (1–8000 characters).']);
        exit;
    }

    $now = date('Y-m-d H:i:s');

    $stmt = mysqli_prepare($con, '
        UPDATE `stc_school_parent_request`
        SET
            `stc_school_parent_request_school_note`          = ?,
            `stc_school_parent_request_school_status`        = \'resolved\',
            `stc_school_parent_request_school_resolved_date` = ?
        WHERE `stc_school_parent_request_id` = ?
          AND COALESCE(`stc_school_parent_request_passed_to_school`, 0) = 1
          AND COALESCE(`stc_school_parent_request_school_status`, \'\') <> \'resolved\'
    ');

    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Update prepare failed.', 'detail' => mysqli_error($con)]);
        exit;
    }

    mysqli_stmt_bind_param($stmt, 'ssi', $note, $now, $id);

    if (!mysqli_stmt_execute($stmt)) {
        $err = mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
        echo json_encode(['success' => false, 'message' => 'Could not save resolution.', 'detail' => $err]);
        exit;
    }

    $affected = mysqli_stmt_affected_rows($stmt);
    mysqli_stmt_close($stmt);

    if ($affected !== 1) {
        echo json_encode(['success' => false, 'message' => 'Nothing updated — record missing, not forwarded, or already resolved.']);
        exit;
    }

    echo json_encode(['success' => true, 'message' => 'Resolution recorded. Director will be notified.']);
    exit;
}

echo json_encode(['success' => false, 'message' => 'No valid action.']);
