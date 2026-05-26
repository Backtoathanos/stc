<?php
/**
 * Public parent request form — no login required.
 * Submit logic lives in `vanaheim/parent-request-raised.php` (AJAX).
 */
date_default_timezone_set('Asia/Kolkata');
session_start();

function parent_req_random_token() {
	if (function_exists('random_bytes')) {
		return bin2hex(random_bytes(32));
	}
	if (function_exists('openssl_random_pseudo_bytes')) {
		return bin2hex(openssl_random_pseudo_bytes(32));
	}
	return sha1(uniqid((string) mt_rand(), true) . microtime(true));
}

if (empty($_SESSION['parent_req_csrf']) || !is_string($_SESSION['parent_req_csrf'])) {
	$_SESSION['parent_req_csrf'] = parent_req_random_token();
}

$csrf_safe = htmlspecialchars((string) ($_SESSION['parent_req_csrf'] ?? ''), ENT_QUOTES, 'UTF-8');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" href="assets/img/stc_logo_title.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<meta content="width=device-width, initial-scale=1.0, viewport-fit=cover" name="viewport" />
	<title>STC School — Parent / Guardian Request</title>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
	<link href="assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
	<style>
		.parent-request-page .card { max-width: 720px; margin: 2rem auto; }
		.parent-request-page .badge-public { letter-spacing: 0.06em; }
		.parent-request-page .navbar-wrapper { flex-wrap: wrap; align-items: center; gap: 0.5rem; }

		/* Mobile / small tablet: sidebar is not an off-canvas drawer on this public page */
		@media (max-width: 991.98px) {
			.parent-request-page { overflow-x: hidden; -webkit-text-size-adjust: 100%; }
			.parent-request-page .wrapper {
				height: auto;
				min-height: 100vh;
				display: flex;
				flex-direction: column;
			}
			.parent-request-page .sidebar {
				position: relative;
				inset: auto;
				width: 100%;
				height: auto;
				flex-shrink: 0;
				z-index: 3;
				box-shadow: 0 2px 10px rgba(0, 0, 0, 0.07);
				transform: none !important;
				-webkit-transform: none !important;
			}
			.parent-request-page .sidebar .logo {
				padding: 10px 14px;
				text-align: center;
				background-color: white;
			}
			.parent-request-page .sidebar .logo .simple-text {
				white-space: normal;
				line-height: 1.35;
				font-size: 1rem;
				background-color:white;
			}
			.parent-request-page .sidebar .sidebar-wrapper {
				width: 100%;
				height: auto;
				overflow: visible;
				padding-bottom: 0;
			}
			.parent-request-page .sidebar .nav {
				display: flex;
				flex-direction: row;
				margin: 0;
			}
			.parent-request-page .sidebar .nav-item {
				flex: 1 1 50%;
				text-align: center;
				border-top: 1px solid rgba(0, 0, 0, 0.06);
			}
			.parent-request-page .sidebar .nav-item + .nav-item {
				border-left: 1px solid rgba(0, 0, 0, 0.06);
			}
			.parent-request-page .sidebar .nav-link {
				display: flex;
				flex-direction: column;
				align-items: center;
				justify-content: center;
				float: none;
				min-height: 56px;
				padding: 8px 6px !important;
				margin: 0 !important;
				border-radius: 0 !important;
			}
			.parent-request-page .sidebar .nav i.material-icons {
				margin: 0 0 2px !important;
				line-height: 1 !important;
				font-size: 22px !important;
				width: auto !important;
			}
			.parent-request-page .sidebar .nav p {
				margin: 0 !important;
				font-size: 11px !important;
				line-height: 1.2;
				white-space: normal !important;
			}

			.parent-request-page .main-panel {
				float: none;
				width: 100% !important;
				flex: 1 1 auto;
				transition: none !important;
				transform: none !important;
				-webkit-transform: none !important;
			}
			.parent-request-page .main-panel > .navbar {
				position: relative;
				padding: 12px 10px;
				margin-bottom: 0;
			}
			.parent-request-page .main-panel > .navbar.navbar-transparent {
				background-color: #fafafa !important;
				border-bottom: 1px solid #e9ecef;
				padding-top: 12px;
			}
			.parent-request-page .navbar-wrapper {
				width: 100%;
				display: flex;
			}
			.parent-request-page .navbar-brand {
				float: none;
				padding: 0;
				margin: 0;
				font-size: 1.1rem;
				line-height: 1.35;
				white-space: normal !important;
				flex: 1 1 auto;
				min-width: 0;
			}

			.parent-request-page .main-panel > .content {
				margin-top: 0 !important;
				padding: 12px 0 92px !important;
				min-height: 0 !important;
			}
			.parent-request-page .footer {
				position: relative;
				padding: 12px 10px;
				margin-top: auto;
				font-size: 0.8125rem;
			}

			.parent-request-page .mx-4 {
				margin-left: 12px !important;
				margin-right: 12px !important;
			}
			.parent-request-page .parent-request-intro {
				font-size: 0.9375rem;
				line-height: 1.5;
			}
			.parent-request-page .card {
				margin-left: auto;
				margin-right: auto;
				margin-top: 0.5rem;
			}

			/* iOS Safari: ≥16px on inputs avoids focus zoom */
			.parent-request-page .form-control { font-size: 16px; }
			.parent-request-page .form-group label {
				font-weight: 500;
				font-size: 14px;
			}
			.parent-request-page .form-group small.form-text {
				font-size: 12px !important;
			}

			.parent-request-page .card-body .btn,
			.parent-request-page .card-body button[type="submit"] {
				min-height: 44px;
				padding-left: 1rem;
				padding-right: 1rem;
				touch-action: manipulation;
			}
			.parent-request-page textarea.form-control {
				min-height: 148px;
			}
			.parent-request-page #parent-request-success-panel .alert,
			.parent-request-page #parent-request-error-panel .alert {
				padding: 14px;
			}
		}

		@media (max-width: 575.98px) {
			.parent-request-page .px-4 { padding-left: 12px !important; padding-right: 12px !important; }
			.parent-request-page .mx-4 { margin-left: 10px !important; margin-right: 10px !important; }
			.parent-request-page .card .card-body,
			.parent-request-page .card .card-header { padding-left: 14px !important; padding-right: 14px !important; }
			.parent-request-page #parent-request-form .btn,
			.parent-request-page #parent-request-form button[type="submit"] {
				display: block;
				width: 100%;
				margin-right: 0 !important;
				margin-bottom: 10px;
			}
			.parent-request-page #parent-request-success-panel .btn {
				display: block;
				width: 100%;
				margin-bottom: 8px !important;
			}
		}
	</style>
</head>
<body class="parent-request-page bg-light">
	<div class="wrapper">
		<div class="sidebar" data-color="azure" data-background-color="white">
			<div class="logo"><span class="simple-text logo-normal">STC School</span></div>
			<div class="sidebar-wrapper"><ul class="nav">
				<li class="nav-item active"><a class="nav-link" href="parent-request.php"><i class="material-icons">mail_outline</i><p>Parent request</p></a></li>
			</ul></div>
		</div>
		<div class="main-panel">
			<nav class="navbar navbar-transparent navbar-absolute">
				<div class="container-fluid">
					<div class="navbar-wrapper">
						<a class="navbar-brand" href="javascript:;">Parent / guardian request form</a>
						<span class="badge badge-info badge-public ml-2">PUBLIC</span>
					</div>
				</div>
			</nav>
			<div class="content">
				<div class="container-fluid">
				<div class="row">
				<div class="col-lg-12">
					<p class="parent-request-intro text-muted px-4">Use this form to send a concern or question to the school office. Staff will reply using the email or phone you provide — you do not need a login.</p>

					<div id="parent-request-success-panel" class="alert alert-success mx-4 px-4 d-none" role="alert">
						<strong>Thank you.</strong> Your request has been submitted. School staff may contact you on the phone or email you shared.
						<hr class="mb-2">
						<a href="parent-request.php" class="btn btn-sm btn-default">Submit another request</a>
						<a href="index.html" class="btn btn-sm btn-default">Staff login</a>
					</div>

					<div id="parent-request-error-panel" class="alert alert-danger mx-4 px-4 d-none" role="alert">
						<strong>Please fix:</strong>
						<ul id="parent-request-error-list" class="mb-0 pl-3"></ul>
					</div>

					<div id="parent-request-form-wrap">
					<div class="card mx-4">
						<div class="card-header card-header-primary">
							<h4 class="card-title">Raise a request</h4>
						</div>
						<div class="card-body">
							<form id="parent-request-form" action="#" method="post" autocomplete="off" novalidate>
								<input type="hidden" name="csrf_token" value="<?php echo $csrf_safe; ?>" />

								<div class="form-group"><label>Name (parent / guardian) *</label>
									<input class="form-control" type="text" name="parent_name" required maxlength="160" placeholder="Full name" autocomplete="name"></div>
								<div class="form-group"><label>Email</label>
									<input class="form-control" type="email" name="email" maxlength="190" placeholder="you@example.com (optional)" autocomplete="email"></div>
								<div class="form-group"><label>Mobile / Phone *</label>
									<small class="form-text text-muted d-block mb-1">Must match the contact number saved for this learner in school records (+91 spacing is ignored).</small>
									<input class="form-control" type="tel" name="phone" required maxlength="40" placeholder="Contact number on file for the student" autocomplete="tel"></div>
								<div class="form-group"><label>Student full name</label>
									<input class="form-control" type="text" name="student_name" maxlength="160" autocomplete="off"></div>
								<div class="form-group"><label>Student admission / ID number *</label>
									<input class="form-control" type="text" name="student_id" required maxlength="64" placeholder="As on admit card / school ID" autocomplete="off"></div>
								<div class="form-group"><label>Subject / Topic *</label>
									<select class="form-control" name="request_subject" required>
										<option value="">Select subject / topic</option>
										<option value="Academic Related">Academic Related</option>
										<option value="Accommodation Related">Accommodation Related</option>
										<option value="Behaviour Based">Behaviour Based</option>
										<option value="Miscellaneous">Miscellaneous</option>
									</select></div>
								<div class="form-group"><label>Details *</label>
									<textarea class="form-control" name="message" rows="6" maxlength="6000" placeholder="Explain your query so staff can respond." autocomplete="off"></textarea></div>

								<button type="submit" class="btn btn-primary">Submit request</button>
								<a href="index.html" class="btn btn-default">Back to login</a>
							</form>
						</div>
					</div>
					</div>

				</div>
				</div>
				</div>
			</div>
			<footer class="footer">
				<div class="container-fluid text-center copyright text-muted">&copy;
					<script>document.write(new Date().getFullYear())</script> STC School
				</div>
			</footer>
		</div>
	</div>
	<script src="assets/js/core/jquery.min.js"></script>
	<script src="assets/js/core/popper.min.js"></script>
	<script src="assets/js/core/bootstrap-material-design.min.js"></script>
	<script>
	(function ($) {
		var saveUrl = 'vanaheim/parent-request-raised.php';
		var $form = $('#parent-request-form');
		var $errs = $('#parent-request-error-panel');
		var $errList = $('#parent-request-error-list');
		var $ok = $('#parent-request-success-panel');
		var $wrap = $('#parent-request-form-wrap');

		function showErrors(messages) {
			$errList.empty();
			(messages || []).forEach(function (m) {
				$errList.append($('<li>').text(String(m)));
			});
			$errs.removeClass('d-none');
			$('html, body').animate({ scrollTop: Math.max($errs.offset().top - 72, 0) }, 200);
		}

		function clearErrors() {
			$errs.addClass('d-none');
			$errList.empty();
		}

		$form.on('submit', function (e) {
			e.preventDefault();
			clearErrors();

			var $btn = $form.find('button[type="submit"]');
			var busy = !!$btn.data('busy');
			if (busy) return;

			$btn.data('busy', true).prop('disabled', true);

			$.ajax({
				url: saveUrl,
				method: 'POST',
				data: $form.serialize() + '&save_parent_request_action=1',
				dataType: 'json'
			})
				.done(function (data) {
					if (!data || data.success !== true) {
						var errs = (data && data.errors && data.errors.length) ? data.errors : ['Something went wrong. Please try again.'];
						showErrors(errs);
						return;
					}
					var tok = typeof data.csrf === 'string' ? data.csrf : '';
					if (tok) {
						$form.find('input[name="csrf_token"]').val(tok);
					}
					$wrap.hide();
					clearErrors();
					$ok.removeClass('d-none');
					$('html, body').animate({ scrollTop: Math.max($ok.offset().top - 72, 0) }, 200);
				})
				.fail(function (_xhr, _st, err) {
					showErrors([
						(err && String(err).trim())
							? String(err).trim()
							: 'Could not reach the server. Check your connection and try again.'
					]);
				})
				.always(function () {
					$btn.data('busy', false).prop('disabled', false);
				});
		});
	})(jQuery);
	</script>
</body>
</html>
