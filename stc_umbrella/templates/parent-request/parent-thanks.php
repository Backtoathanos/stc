<?php
/**
 * HTML email — acknowledgement to parent / guardian.
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
	$parent_name = 'Rajesh Kumar (sample)';
	$submitted_readable = date('j F Y, g:i A');

	echo '<!-- Preview: dummy data -->'
		. '<div style="background:#fff3cd;color:#856404;padding:10px 16px;text-align:center;font-family:system-ui,sans-serif;font-size:14px;border-bottom:1px solid #ffc107;">'
		. 'Preview · sample acknowledgement (open this URL for layout check only).</div>';
}

/** @var callable $h */

$greet = $h(isset($parent_name) && $parent_name !== '' ? $parent_name : 'Parent / Guardian');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0;padding:0;background:#f4f6f8;font-family:'Segoe UI',Roboto,Helvetica,Arial,sans-serif;line-height:1.6;color:#333;">
	<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f4f6f8;padding:24px 12px;">
		<tr>
			<td align="center">
				<table role="presentation" width="100%" style="max-width:560px;background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,0.06);">
					<tr>
						<td style="background:linear-gradient(135deg,#1565c0 0%,#0d47a1 100%);padding:24px 28px;">
							<h1 style="margin:0;font-size:20px;font-weight:600;color:#ffffff;letter-spacing:0.02em;">STC School</h1>
							<p style="margin:8px 0 0;font-size:13px;color:#bbdefb;">Parent / guardian enquiries</p>
						</td>
					</tr>
					<tr>
						<td style="padding:28px 28px 8px;">
							<p style="margin:0 0 16px;font-size:16px;color:#1a1a1a;">Dear <?php echo $greet; ?>,</p>
							<p style="margin:0 0 16px;font-size:15px;color:#444;">Thank you, dear parents, for your valuable feedback shared on the school portal. We sincerely appreciate your trust in SARA Group, and the school will review the points raised and revert soon.</p>
							<p style="margin:24px 0 0;font-size:15px;color:#333;">With kind regards,<br><strong style="color:#1565c0;">STC School administration</strong></p>
						</td>
					</tr>
					<tr>
						<td style="padding:16px 28px 24px;border-top:1px solid #eee;font-size:11px;color:#999;">
							This is an automated message. Please do not reply directly to this email unless you have been asked to do so.
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>
