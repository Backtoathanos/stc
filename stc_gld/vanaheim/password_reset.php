<?php
/**
 * STC GLD — password reset (secure flow).
 * Token is NEVER returned in HTTP responses. It is emailed only to the account's registered email.
 *
 * JSON: { "action": "request", "user": "email or phone on file" }
 *       { "action": "confirm", "token": "...", "new_password": "...", "confirm_password": "..." }
 *
 * Optional env: SetEnv STC_GLD_PUBLIC_URL "http://localhost/stc/stc_gld" (Apache) for local links in email.
 */
session_start();
date_default_timezone_set('Asia/Kolkata');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../../MCU/obdb.php';

/** Same outward message whether the account exists — reduces account enumeration. */
const RESET_REQUEST_PUBLIC_MESSAGE = 'If an account matches what you entered, a password reset link was sent to the email address on file. Check your inbox and spam folder.';

function json_out($data, $code = 200)
{
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}

function gld_public_base_url()
{
    $fromEnv = getenv('STC_GLD_PUBLIC_URL');
    if (is_string($fromEnv) && $fromEnv !== '') {
        return rtrim($fromEnv, '/');
    }
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
    if ($host === 'localhost' || $host === '127.0.0.1' || strpos($host, 'localhost:') === 0) {
        return 'http://localhost/stc/stc_gld';
    }
    return 'https://stcassociate.com/stc_gld';
}

function send_reset_email($toEmail, $displayName, $resetUrl)
{
    $toEmail = trim($toEmail);
    if ($toEmail === '' || !filter_var($toEmail, FILTER_VALIDATE_EMAIL)) {
        return false;
    }

    $subject = 'STC GLD — reset your password';
    $safeName = htmlspecialchars($displayName !== '' ? $displayName : 'User', ENT_QUOTES, 'UTF-8');
    $safeUrl = htmlspecialchars($resetUrl, ENT_QUOTES, 'UTF-8');

    $body = '<!DOCTYPE html><html><body style="font-family:Arial,sans-serif;line-height:1.5">'
        . '<p>Hello ' . $safeName . ',</p>'
        . '<p>Someone requested a password reset for your STC GLD account. Use the link below within <strong>1 hour</strong>. '
        . 'If you did not request this, you can ignore this email.</p>'
        . '<p><a href="' . $safeUrl . '" style="display:inline-block;padding:10px 16px;background:#7b1fa2;color:#fff;text-decoration:none;border-radius:4px">Reset password</a></p>'
        . '<p style="word-break:break-all;font-size:12px;color:#666">Or copy this URL:<br>' . $safeUrl . '</p>'
        . '<p>— STC GLD</p>'
        . '</body></html>';

    $headers = "MIME-Version: 1.0\r\n"
        . "Content-type:text/html;charset=UTF-8\r\n"
        . "From: STC GLD <noreply@stcassociate.com>\r\n"
        . "Reply-To: support@stcassociate.com\r\n";

    return @mail($toEmail, $subject, $body, $headers);
}

$raw = file_get_contents('php://input');
$input = json_decode($raw, true);
if (!is_array($input)) {
    $input = [];
}

$action = isset($input['action']) ? trim((string) $input['action']) : '';

$db = new tesseract();
$conn = $db->stc_dbs;

if (!$conn || mysqli_connect_errno()) {
    json_out(['success' => false, 'message' => 'Database unavailable.'], 500);
}

mysqli_query($conn, "
CREATE TABLE IF NOT EXISTS `stc_gld_password_reset` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `stc_trading_user_id` int unsigned NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `user_id` (`stc_trading_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
");

if ($action === 'request') {
    $user = isset($input['user']) ? trim((string) $input['user']) : '';
    if ($user === '') {
        json_out(['success' => false, 'message' => 'Enter email or contact number.']);
    }

    $stmt = mysqli_prepare(
        $conn,
        'SELECT `stc_trading_user_id`, `stc_trading_user_email`, `stc_trading_user_name`
         FROM `stc_trading_user`
         WHERE `stc_trading_user_email` = ? OR `stc_trading_user_cont` = ?
         LIMIT 1'
    );
    mysqli_stmt_bind_param($stmt, 'ss', $user, $user);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    if (!$row) {
        json_out(['success' => true, 'message' => RESET_REQUEST_PUBLIC_MESSAGE]);
    }

    $email = isset($row['stc_trading_user_email']) ? trim((string) $row['stc_trading_user_email']) : '';
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        error_log('STC GLD password_reset: account has no valid email (user_id ' . (int) $row['stc_trading_user_id'] . ')');
        json_out(['success' => true, 'message' => RESET_REQUEST_PUBLIC_MESSAGE]);
    }

    $uid = (int) $row['stc_trading_user_id'];
    $displayName = isset($row['stc_trading_user_name']) ? (string) $row['stc_trading_user_name'] : '';

    mysqli_query($conn, 'DELETE FROM `stc_gld_password_reset` WHERE `stc_trading_user_id` = ' . $uid);

    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', time() + 3600);
    $stmt = mysqli_prepare(
        $conn,
        'INSERT INTO `stc_gld_password_reset` (`stc_trading_user_id`, `token`, `expires_at`) VALUES (?,?,?)'
    );
    mysqli_stmt_bind_param($stmt, 'iss', $uid, $token, $expires);
    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        error_log('STC GLD password_reset: failed to store token for user ' . $uid);
        json_out(['success' => true, 'message' => RESET_REQUEST_PUBLIC_MESSAGE]);
    }
    mysqli_stmt_close($stmt);

    $base = gld_public_base_url();
    $resetUrl = $base . '/reset-password?token=' . rawurlencode($token);

    if (!send_reset_email($email, $displayName, $resetUrl)) {
        error_log('STC GLD password_reset: mail() failed for user ' . $uid . ' to ' . $email);
        mysqli_query($conn, 'DELETE FROM `stc_gld_password_reset` WHERE `stc_trading_user_id` = ' . $uid);
    }

    json_out(['success' => true, 'message' => RESET_REQUEST_PUBLIC_MESSAGE]);
}

if ($action === 'confirm') {
    $token = isset($input['token']) ? trim((string) $input['token']) : '';
    $pw = isset($input['new_password']) ? (string) $input['new_password'] : '';
    $pw2 = isset($input['confirm_password']) ? (string) $input['confirm_password'] : '';

    if (strlen($token) < 32) {
        json_out(['success' => false, 'message' => 'Invalid reset link. Request a new one from the login page.']);
    }
    if ($pw === '' || $pw !== $pw2) {
        json_out(['success' => false, 'message' => 'Passwords do not match or are empty.']);
    }
    if (strlen($pw) < 4) {
        json_out(['success' => false, 'message' => 'Password must be at least 4 characters.']);
    }

    $stmt = mysqli_prepare(
        $conn,
        'SELECT `stc_trading_user_id` FROM `stc_gld_password_reset`
         WHERE `token` = ? AND `expires_at` > NOW()
         LIMIT 1'
    );
    mysqli_stmt_bind_param($stmt, 's', $token);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($res);
    mysqli_stmt_close($stmt);

    if (!$row) {
        json_out(['success' => false, 'message' => 'This reset link is invalid or expired. Request a new one.']);
    }

    $uid = (int) $row['stc_trading_user_id'];
    $stmt = mysqli_prepare(
        $conn,
        'UPDATE `stc_trading_user` SET `stc_trading_user_password` = ? WHERE `stc_trading_user_id` = ?'
    );
    mysqli_stmt_bind_param($stmt, 'si', $pw, $uid);
    if (!mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        json_out(['success' => false, 'message' => 'Could not update password.'], 500);
    }
    mysqli_stmt_close($stmt);

    mysqli_query($conn, 'DELETE FROM `stc_gld_password_reset` WHERE `stc_trading_user_id` = ' . $uid);

    json_out(['success' => true, 'message' => 'Password updated. You can sign in now.']);
}

json_out(['success' => false, 'message' => 'Unknown action.'], 400);
