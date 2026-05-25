<?php
/**
 * HTML email — notification to director / office.
 * Loaded by `parent-request-raised.php` with real data (global embed flag).
 * Loaded directly in the browser shows sample preview data instead of notices.
 */

if (empty($GLOBALS['_stc_parent_req_mail_embedded'])) {
	if (!headers_sent()) {
		header('Content-Type: text/html; charset=UTF-8');
	}
	date_default_timezone_set('Asia/Kolkata');
	$h = function ($s) {
		return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8');
	};
	$request_id = 10042;
	$parent_name = 'Rajesh Kumar (sample)';
	$email = 'parent.example@samplemail.test';
	$phone = '+91 98765 43210';
	$student_name = 'Priya Kumar';
	$student_id = 'STC/2025/0891';
	$subject_line = 'School bus pickup time change';
	$message_body = "Good morning,\n\nWe must adjust the pickup time on weekdays from next Monday. Earlier bus slot would help if available.\n\nThank you.\nRajesh Kumar";
	$submitted_readable = date('j F Y, g:i A');

	echo '<!-- Preview: dummy data -->'
		. '<div style="background:#fff3cd;color:#856404;padding:10px 16px;text-align:center;font-family:system-ui,sans-serif;font-size:14px;border-bottom:1px solid #ffc107;">'
		. 'Preview · sample director alert (open this URL for layout check only).</div>';
}

/** @var callable $h */

$telRaw = isset($phone) ? preg_replace('/\D+/', '', (string) $phone) : '';
$snempty = isset($student_name) && (string) $student_name !== '';
$sidempty = isset($student_id) && (string) $student_id !== '';
$sn = $snempty ? $h($student_name) : '<span style="color:#999;">—</span>';
$sid = $sidempty ? $h($student_id) : '<span style="color:#999;">—</span>';
$msgHtml = nl2br($h(isset($message_body) ? (string) $message_body : ''));
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0;padding:0;background:#f4f6f8;font-family:'Segoe UI',Roboto,Helvetica,Arial,sans-serif;line-height:1.5;color:#333;">
	<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f4f6f8;padding:24px 12px;">
		<tr>
			<td align="center">
				<table role="presentation" width="100%" style="max-width:640px;background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.06);">
					<tr>
						<td style="background:#263238;padding:20px 24px;">
							<h1 style="margin:0;font-size:18px;font-weight:600;color:#eceff1;">New parent / guardian request</h1>
							<p style="margin:6px 0 0;font-size:12px;color:#b0bec5;">Reference #<?php echo $h((string) (isset($request_id) ? $request_id : '')); ?> · <?php echo $h((string) (isset($submitted_readable) ? $submitted_readable : '')); ?></p>
						</td>
					</tr>
					<tr>
						<td style="padding:20px 24px 8px;">
							<p style="margin:0 0 16px;font-size:14px;color:#555;">The following enquiry was submitted via the public parent request form:</p>
							<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;font-size:14px;">
								<tr style="background:#fafafa;">
									<td style="padding:10px 14px;border:1px solid #e0e0e0;width:36%;font-weight:600;color:#424242;">Parent / Guardian</td>
									<td style="padding:10px 14px;border:1px solid #e0e0e0;color:#212121;"><?php echo $h((string) (isset($parent_name) ? $parent_name : '')); ?></td>
								</tr>
								<tr>
									<td style="padding:10px 14px;border:1px solid #e0e0e0;font-weight:600;color:#424242;">Email</td>
									<td style="padding:10px 14px;border:1px solid #e0e0e0;"><a href="mailto:<?php echo $h((string) (isset($email) ? $email : '')); ?>" style="color:#1565c0;"><?php echo $h((string) (isset($email) ? $email : '')); ?></a></td>
								</tr>
								<tr style="background:#fafafa;">
									<td style="padding:10px 14px;border:1px solid #e0e0e0;font-weight:600;color:#424242;">Phone</td>
									<td style="padding:10px 14px;border:1px solid #e0e0e0;"><?php
									if ($telRaw !== ''): ?>
									<a href="tel:<?php echo $h($telRaw); ?>" style="color:#1565c0;"><?php echo $h((string) (isset($phone) ? $phone : '')); ?></a>
									<?php else: ?>
									<?php echo $h((string) (isset($phone) ? $phone : '')); ?>
									<?php endif; ?></td>
								</tr>
								<tr>
									<td style="padding:10px 14px;border:1px solid #e0e0e0;font-weight:600;color:#424242;">Student name</td>
									<td style="padding:10px 14px;border:1px solid #e0e0e0;"><?php echo $sn; ?></td>
								</tr>
								<tr style="background:#fafafa;">
									<td style="padding:10px 14px;border:1px solid #e0e0e0;font-weight:600;color:#424242;">Admission / ID</td>
									<td style="padding:10px 14px;border:1px solid #e0e0e0;"><?php echo $sid; ?></td>
								</tr>
								<tr>
									<td style="padding:10px 14px;border:1px solid #e0e0e0;font-weight:600;color:#424242;vertical-align:top;">Subject</td>
									<td style="padding:10px 14px;border:1px solid #e0e0e0;"><?php echo $h((string) (isset($subject_line) ? $subject_line : '')); ?></td>
								</tr>
								<tr style="background:#fafafa;">
									<td colspan="2" style="padding:10px 14px;border:1px solid #e0e0e0;font-weight:600;color:#424242;">Details</td>
								</tr>
								<tr>
									<td colspan="2" style="padding:14px;border:1px solid #e0e0e0;border-top:none;font-size:14px;color:#333;vertical-align:top;"><?php echo $msgHtml; ?></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td style="padding:16px 24px 22px;border-top:1px solid #eee;font-size:11px;color:#9e9e9e;">
							Internal notification · STC School system
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>
