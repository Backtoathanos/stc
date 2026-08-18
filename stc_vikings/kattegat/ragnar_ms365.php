<?php
/**
 * Microsoft 365 analytics API — Microsoft Graph (application credentials).
 */
declare(strict_types=1);

ob_start();

require_once __DIR__ . '/auth_helper.php';
require_once __DIR__ . '/ms365_access.php';

header('Content-Type: application/json; charset=UTF-8');

if (!STCAuthHelper::resumeSessionForApi()) {
	ms365_fail('Session expired. Please sign in again.');
}

if (!stc_ms365_user_allowed()) {
	ms365_fail('Microsoft 365 analytics is available only for Boss and Nausher.');
}

set_time_limit(180);

$defaults = require __DIR__ . '/ms365_graph_config.php';
$dataDir = __DIR__ . '/ms365_data';
if (!is_dir($dataDir)) {
	@mkdir($dataDir, 0700, true);
}

$secretsFile = $dataDir . '/secrets.json';
$tokenFile = $dataDir . '/token.json';
$cacheFile = $dataDir . '/dashboard_cache.json';

$action = '';
if (isset($_POST['action'])) {
	$action = (string) $_POST['action'];
} elseif (isset($_GET['action'])) {
	$action = (string) $_GET['action'];
}

switch ($action) {
	case 'status':
		ms365_ok(ms365_status_payload($defaults, $secretsFile, $cacheFile));
		break;
	case 'save_config':
		ms365_save_config($defaults, $secretsFile, $tokenFile, $cacheFile);
		break;
	case 'dashboard':
		$period = ms365_period($_POST['period'] ?? 'D30');
		$force = !empty($_POST['force']);
		ms365_ok(ms365_dashboard($defaults, $secretsFile, $tokenFile, $cacheFile, $period, $force));
		break;
	default:
		ms365_fail('Unknown action.');
}

function ms365_send(array $payload): void {
	while (ob_get_level() > 0) {
		ob_end_clean();
	}
	echo json_encode($payload, JSON_UNESCAPED_UNICODE);
	exit;
}

function ms365_fail(string $message, array $extra = []): void {
	ms365_send(array_merge(['success' => false, 'message' => $message], $extra));
}

function ms365_ok(array $data = []): void {
	ms365_send(array_merge(['success' => true], $data));
}

function ms365_period($raw): string {
	$raw = strtoupper(trim((string) $raw));
	return in_array($raw, ['D7', 'D30', 'D90'], true) ? $raw : 'D30';
}

function ms365_read_json(string $path): array {
	if (!is_file($path)) {
		return [];
	}
	$raw = @file_get_contents($path);
	if ($raw === false || $raw === '') {
		return [];
	}
	$decoded = json_decode($raw, true);
	return is_array($decoded) ? $decoded : [];
}

function ms365_write_json(string $path, array $data): bool {
	$dir = dirname($path);
	if (!is_dir($dir)) {
		@mkdir($dir, 0700, true);
	}
	$json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
	return $json !== false && @file_put_contents($path, $json, LOCK_EX) !== false;
}

function ms365_credentials(array $defaults, string $secretsFile): array {
	$secrets = ms365_read_json($secretsFile);
	$tenant = trim((string) ($secrets['tenant_id'] ?? $defaults['tenant_id'] ?? ''));
	$client = trim((string) ($secrets['client_id'] ?? $defaults['client_id'] ?? ''));
	$secret = (string) ($secrets['client_secret'] ?? $defaults['client_secret'] ?? '');
	return [
		'tenant_id' => $tenant,
		'client_id' => $client,
		'client_secret' => $secret,
		'configured' => $tenant !== '' && $client !== '' && $secret !== '',
		'secret_set' => $secret !== '',
	];
}

function ms365_mask(string $value): string {
	$len = strlen($value);
	if ($len <= 4) {
		return $len === 0 ? '' : str_repeat('•', $len);
	}
	return str_repeat('•', max(0, $len - 4)) . substr($value, -4);
}

function ms365_status_payload(array $defaults, string $secretsFile, string $cacheFile): array {
	$creds = ms365_credentials($defaults, $secretsFile);
	$cache = ms365_read_json($cacheFile);
	return [
		'configured' => !empty($creds['configured']),
		'tenant_id' => $creds['tenant_id'],
		'client_id' => $creds['client_id'],
		'secret_set' => !empty($creds['secret_set']),
		'secret_hint' => $creds['secret_set'] ? ms365_mask($creds['client_secret']) : '',
		'cached_at' => $cache['fetched_at'] ?? null,
		'cached_period' => $cache['period'] ?? null,
	];
}

function ms365_save_config(array $defaults, string $secretsFile, string $tokenFile, string $cacheFile): void {
	$existing = ms365_read_json($secretsFile);
	$tenant = trim((string) ($_POST['tenant_id'] ?? ''));
	$client = trim((string) ($_POST['client_id'] ?? ''));
	$secret = trim((string) ($_POST['client_secret'] ?? ''));

	if ($tenant === '' || $client === '') {
		ms365_fail('Tenant ID and Client ID are required.');
	}
	if ($secret === '') {
		$secret = (string) ($existing['client_secret'] ?? '');
	}
	if ($secret === '') {
		ms365_fail('Client secret is required the first time you save.');
	}

	$ok = ms365_write_json($secretsFile, [
		'tenant_id' => $tenant,
		'client_id' => $client,
		'client_secret' => $secret,
		'updated_at' => date('c'),
	]);
	if (!$ok) {
		ms365_fail('Could not save credentials. Check folder permissions on kattegat/ms365_data.');
	}

	@unlink($tokenFile);
	@unlink($cacheFile);

	try {
		$token = ms365_get_token($defaults, [
			'tenant_id' => $tenant,
			'client_id' => $client,
			'client_secret' => $secret,
			'configured' => true,
		], $tokenFile);
		if ($token === '') {
			ms365_fail('Saved, but token request failed. Recheck tenant, client ID, and secret.');
		}
		ms365_ok([
			'message' => 'Connected to Microsoft Graph. Loading dashboard…',
			'configured' => true,
			'tenant_id' => $tenant,
			'client_id' => $client,
			'secret_set' => true,
			'secret_hint' => ms365_mask($secret),
		]);
	} catch (Throwable $e) {
		ms365_fail('Saved, but Graph login failed: ' . $e->getMessage());
	}
}

function ms365_dashboard(array $defaults, string $secretsFile, string $tokenFile, string $cacheFile, string $period, bool $force): array {
	$creds = ms365_credentials($defaults, $secretsFile);
	if (empty($creds['configured'])) {
		ms365_fail('Microsoft 365 is not connected. Open Settings and add your Azure app credentials.', [
			'configured' => false,
		]);
	}

	if ($force) {
		@unlink($tokenFile);
		@unlink($cacheFile);
	}

	$ttl = (int) ($defaults['cache_ttl_seconds'] ?? 900);
	if (!$force && is_file($cacheFile)) {
		$cache = ms365_read_json($cacheFile);
		$age = isset($cache['fetched_at_ts']) ? (time() - (int) $cache['fetched_at_ts']) : 99999;
		if (($cache['period'] ?? '') === $period && $age >= 0 && $age < $ttl && !empty($cache['data'])) {
			return [
				'configured' => true,
				'from_cache' => true,
				'fetched_at' => $cache['fetched_at'] ?? null,
				'period' => $period,
				'data' => $cache['data'],
			];
		}
	}

	try {
		$token = ms365_get_token($defaults, $creds, $tokenFile);
	} catch (Throwable $e) {
		ms365_fail('Could not sign in to Microsoft Graph: ' . $e->getMessage());
	}
	$warnings = [];
	$skuMap = [];

	$licenses = ms365_collect_licenses($defaults, $token, $warnings, $skuMap);
	$subscriptions = ms365_collect_subscriptions($defaults, $token);
	$users = ms365_collect_users($defaults, $token, $skuMap, $warnings);
	$drives = ms365_collect_drives($defaults, $token, $users, $warnings);
	$onedrive = ms365_collect_report($defaults, $token, "/reports/getOneDriveUsageAccountDetail(period='$period')", 'onedrive', $warnings);
	$mailbox = ms365_collect_report($defaults, $token, "/reports/getMailboxUsageDetail(period='$period')", 'mailbox', $warnings);
	$sharepoint = ms365_collect_report($defaults, $token, "/reports/getSharePointSiteUsageDetail(period='$period')", 'sharepoint', $warnings);
	$teams = ms365_collect_report($defaults, $token, "/reports/getTeamsUserActivityUserDetail(period='$period')", 'teams', $warnings);
	$active = ms365_collect_report($defaults, $token, "/reports/getOffice365ActiveUserDetail(period='$period')", 'active', $warnings);
	$signins = ms365_collect_signins($defaults, $token, $warnings);

	$odByUpn = [];
	foreach ($onedrive['rows'] as $row) {
		$upn = strtolower((string) ($row['userPrincipalName'] ?? ''));
		if ($upn !== '') {
			$odByUpn[$upn] = $row;
		}
	}
	$mbByUpn = [];
	foreach ($mailbox['rows'] as $row) {
		$upn = strtolower((string) ($row['userPrincipalName'] ?? ''));
		if ($upn !== '') {
			$mbByUpn[$upn] = $row;
		}
	}
	$activeByUpn = [];
	foreach ($active['rows'] as $row) {
		$upn = strtolower((string) ($row['userPrincipalName'] ?? ''));
		if ($upn !== '') {
			$activeByUpn[$upn] = $row;
		}
	}

	$mergedUsers = [];
	$licensed = 0;
	$unlicensed = 0;
	$guests = 0;
	$enabled = 0;
	$disabled = 0;
	$storageHot = 0;

	foreach ($users as $user) {
		$upn = strtolower((string) $user['userPrincipalName']);
		$od = $odByUpn[$upn] ?? null;
		$mb = $mbByUpn[$upn] ?? null;
		$act = $activeByUpn[$upn] ?? null;
		$drive = $drives[$user['id']] ?? null;

		if ($drive) {
			$used = (float) $drive['usedBytes'];
			$alloc = (float) $drive['totalBytes'];
		} else {
			$used = $od ? (float) $od['usedBytes'] : 0.0;
			$alloc = $od ? (float) $od['allocatedBytes'] : 0.0;
		}
		$pct = ($alloc > 0) ? round(($used / $alloc) * 100, 1) : 0.0;
		if ($pct >= 80) {
			$storageHot++;
		}

		$lastActivity = $act['lastActivity'] ?? ($od['lastActivity'] ?? ($mb['lastActivity'] ?? ''));
		if ($lastActivity === '' && is_array($drive) && ($drive['lastModified'] ?? '') !== '') {
			$lastActivity = (string) $drive['lastModified'];
		}
		$isActive = $lastActivity !== '' && $lastActivity !== '0001-01-01';

		if ($user['userType'] === 'Guest') {
			$guests++;
		}
		if ($user['licensed']) {
			$licensed++;
		} else {
			$unlicensed++;
		}
		if ($user['accountEnabled']) {
			$enabled++;
		} else {
			$disabled++;
		}

		$mergedUsers[] = [
			'displayName' => $user['displayName'],
			'userPrincipalName' => $user['userPrincipalName'],
			'mail' => $user['mail'],
			'userType' => $user['userType'],
			'accountEnabled' => $user['accountEnabled'],
			'licensed' => $user['licensed'],
			'licenses' => $user['licenses'],
			'jobTitle' => $user['jobTitle'],
			'officeLocation' => $user['officeLocation'],
			'onedriveUsedBytes' => $used,
			'onedriveAllocatedBytes' => $alloc,
			'onedriveUsed' => ms365_bytes_human($used),
			'onedriveAllocated' => ms365_bytes_human($alloc),
			'onedrivePercent' => $pct,
			'onedriveFiles' => $od ? (int) $od['files'] : 0,
			'mailboxUsedBytes' => $mb ? (float) $mb['usedBytes'] : 0.0,
			'mailboxUsed' => $mb ? ms365_bytes_human((float) $mb['usedBytes']) : '—',
			'mailboxItems' => $mb ? (int) $mb['itemCount'] : 0,
			'lastActivity' => $lastActivity,
			'isActive' => $isActive,
		];
	}

	usort($mergedUsers, static function ($a, $b) {
		return $b['onedriveUsedBytes'] <=> $a['onedriveUsedBytes'];
	});

	$topStorage = array_slice($mergedUsers, 0, 10);
	$activeCount = 0;
	$odUsedTotal = 0.0;
	$odAllocTotal = 0.0;
	$odAccounts = 0;
	foreach ($mergedUsers as $u) {
		if (!empty($u['isActive'])) {
			$activeCount++;
		}
		if ($u['onedriveUsedBytes'] > 0 || $u['onedriveAllocatedBytes'] > 0) {
			$odUsedTotal += (float) $u['onedriveUsedBytes'];
			$odAllocTotal += (float) $u['onedriveAllocatedBytes'];
			$odAccounts++;
		}
	}
	if ($odAccounts === 0) {
		$odUsedTotal = (float) $onedrive['totals']['usedBytes'];
		$odAllocTotal = (float) $onedrive['totals']['allocatedBytes'];
		$odAccounts = (int) $onedrive['totals']['accounts'];
	}

	$data = [
		'users' => [
			'total' => count($mergedUsers),
			'licensed' => $licensed,
			'unlicensed' => $unlicensed,
			'active' => $activeCount,
			'inactive' => max(0, count($mergedUsers) - $activeCount),
			'guests' => $guests,
			'enabled' => $enabled,
			'disabled' => $disabled,
			'storageHot' => $storageHot,
		],
		'licenses' => $licenses,
		'subscription' => $subscriptions['primary'],
		'subscriptions' => $subscriptions['all'],
		'storage' => [
			'onedriveUsedBytes' => $odUsedTotal,
			'onedriveAllocatedBytes' => $odAllocTotal,
			'onedriveUsed' => ms365_bytes_human($odUsedTotal),
			'onedriveAllocated' => ms365_bytes_human($odAllocTotal),
			'onedriveAccounts' => $odAccounts,
			'sharepointUsedBytes' => (float) $sharepoint['totals']['usedBytes'],
			'sharepointAllocatedBytes' => (float) $sharepoint['totals']['allocatedBytes'],
			'sharepointUsed' => ms365_bytes_human((float) $sharepoint['totals']['usedBytes']),
			'sharepointAllocated' => ms365_bytes_human((float) $sharepoint['totals']['allocatedBytes']),
			'sharepointSites' => (int) $sharepoint['totals']['sites'],
			'mailboxUsedBytes' => (float) $mailbox['totals']['usedBytes'],
			'mailboxUsed' => ms365_bytes_human((float) $mailbox['totals']['usedBytes']),
			'mailboxCount' => (int) $mailbox['totals']['mailboxes'],
			'mailboxItems' => (int) $mailbox['totals']['items'],
		],
		'teams' => $teams['totals'],
		'sharepointSites' => array_slice($sharepoint['rows'], 0, 25),
		'topStorage' => $topStorage,
		'userList' => $mergedUsers,
		'signIns' => $signins,
		'warnings' => $warnings,
	];

	$stamp = date('c');
	if ($warnings === []) {
		ms365_write_json($cacheFile, [
			'fetched_at' => $stamp,
			'fetched_at_ts' => time(),
			'period' => $period,
			'data' => $data,
		]);
	} else {
		@unlink($cacheFile);
	}

	return [
		'configured' => true,
		'from_cache' => false,
		'fetched_at' => $stamp,
		'period' => $period,
		'data' => $data,
	];
}

function ms365_get_token(array $defaults, array $creds, string $tokenFile): string {
	$cached = ms365_read_json($tokenFile);
	$skew = (int) ($defaults['token_skew_seconds'] ?? 120);
	if (!empty($cached['access_token']) && !empty($cached['expires_at']) && (int) $cached['expires_at'] > (time() + $skew)) {
		return (string) $cached['access_token'];
	}

	$tenant = rawurlencode($creds['tenant_id']);
	$url = str_replace('{tenant}', $tenant, (string) $defaults['token_url']);
	$body = http_build_query([
		'client_id' => $creds['client_id'],
		'client_secret' => $creds['client_secret'],
		'grant_type' => 'client_credentials',
		'scope' => 'https://graph.microsoft.com/.default',
	]);

	$res = ms365_http('POST', $url, [
		'Content-Type: application/x-www-form-urlencoded',
	], $body, (int) $defaults['http_timeout'], false);

	if ($res['code'] < 200 || $res['code'] >= 300) {
		$err = ms365_extract_error($res['body']);
		throw new RuntimeException($err !== '' ? $err : 'Token request failed HTTP ' . $res['code']);
	}

	$json = json_decode($res['body'], true);
	$token = is_array($json) ? (string) ($json['access_token'] ?? '') : '';
	if ($token === '') {
		throw new RuntimeException('Token response did not include access_token.');
	}
	$expiresIn = is_array($json) ? (int) ($json['expires_in'] ?? 3600) : 3600;
	ms365_write_json($tokenFile, [
		'access_token' => $token,
		'expires_at' => time() + max(60, $expiresIn),
	]);
	return $token;
}

function ms365_graph_json(array $defaults, string $token, string $path, array $extraHeaders = [], string $method = 'GET', $body = null): array {
	$url = (strpos($path, 'https://') === 0) ? $path : rtrim((string) $defaults['graph_base'], '/') . $path;
	$headers = array_merge([
		'Authorization: Bearer ' . $token,
		'Accept: application/json',
	], $extraHeaders);
	$payload = null;
	if ($body !== null) {
		$headers[] = 'Content-Type: application/json';
		$payload = is_array($body) ? json_encode($body) : (string) $body;
	}
	$res = ms365_http($method, $url, $headers, $payload, (int) $defaults['http_timeout'], false);
	if ($res['code'] < 200 || $res['code'] >= 300) {
		throw new RuntimeException(ms365_extract_error($res['body']) ?: ('Graph HTTP ' . $res['code']));
	}
	$json = json_decode($res['body'], true);
	return is_array($json) ? $json : [];
}

function ms365_graph_paged(array $defaults, string $token, string $path, array $extraHeaders = []): array {
	$items = [];
	$url = $path;
	$guard = 0;
	while ($url !== '' && $guard < 40) {
		$json = ms365_graph_json($defaults, $token, $url, $extraHeaders);
		if (!empty($json['value']) && is_array($json['value'])) {
			foreach ($json['value'] as $row) {
				$items[] = $row;
			}
		}
		$next = (string) ($json['@odata.nextLink'] ?? '');
		$url = $next;
		$guard++;
	}
	return $items;
}

function ms365_download_report_file(array $defaults, string $url): string {
	$follow = ms365_http('GET', $url, ['Accept: text/csv, */*'], null, (int) $defaults['http_timeout'], false);
	if ($follow['code'] < 200 || $follow['code'] >= 300) {
		throw new RuntimeException('Report download failed HTTP ' . $follow['code']);
	}
	return $follow['body'];
}

function ms365_graph_csv(array $defaults, string $token, string $path): array {
	$url = rtrim((string) $defaults['graph_base'], '/') . $path;
	$res = ms365_http('GET', $url, [
		'Authorization: Bearer ' . $token,
		'Accept: text/csv, application/json, */*',
	], null, (int) $defaults['http_timeout'], false);

	$body = $res['body'];
	if ($res['code'] >= 300 && $res['code'] < 400) {
		$loc = ms365_header_value($res['headers'], 'Location');
		if ($loc === '') {
			throw new RuntimeException('Report redirect had no Location header.');
		}
		$body = ms365_download_report_file($defaults, $loc);
	} elseif ($res['code'] < 200 || $res['code'] >= 300) {
		throw new RuntimeException(ms365_extract_error($body) ?: ('Report HTTP ' . $res['code']));
	} else {
		$trim = ltrim($body);
		if ($trim !== '' && ($trim[0] === '{' || $trim[0] === '[')) {
			$json = json_decode($body, true);
			if (isset($json['error'])) {
				throw new RuntimeException(ms365_extract_error($body));
			}
			$loc = '';
			if (is_array($json) && isset($json['value']) && is_string($json['value']) && strpos($json['value'], 'http') === 0) {
				$loc = $json['value'];
			}
			if ($loc !== '') {
				$body = ms365_download_report_file($defaults, $loc);
			}
		}
	}

	return ms365_parse_csv($body);
}

function ms365_collect_licenses(array $defaults, string $token, array &$warnings, array &$skuMap): array {
	try {
		$json = ms365_graph_json($defaults, $token, '/subscribedSkus');
	} catch (Throwable $e) {
		$warnings[] = 'Licenses: ' . $e->getMessage();
		return [];
	}

	$list = [];
	foreach (($json['value'] ?? []) as $sku) {
		$skuId = strtolower((string) ($sku['skuId'] ?? ''));
		$part = (string) ($sku['skuPartNumber'] ?? '');
		$name = ms365_sku_name($part);
		$prepaid = $sku['prepaidUnits'] ?? [];
		$purchased = (int) ($prepaid['enabled'] ?? 0) + (int) ($prepaid['warning'] ?? 0) + (int) ($prepaid['suspended'] ?? 0);
		$assigned = (int) ($sku['consumedUnits'] ?? 0);
		$available = max(0, $purchased - $assigned);
		$skuMap[$skuId] = $name;
		$list[] = [
			'skuId' => $skuId,
			'skuPartNumber' => $part,
			'name' => $name,
			'purchased' => $purchased,
			'assigned' => $assigned,
			'available' => $available,
		];
	}
	usort($list, static function ($a, $b) {
		return $b['assigned'] <=> $a['assigned'];
	});
	return $list;
}

function ms365_collect_subscriptions(array $defaults, string $token): array {
	$empty = ['primary' => null, 'all' => []];
	$json = [];
	try {
		$json = ms365_graph_json($defaults, $token, '/directory/subscriptions');
	} catch (Throwable $e) {
		try {
			$json = ms365_graph_json($defaults, $token, str_replace('/v1.0', '/beta', (string) $defaults['graph_base']) . '/directory/subscriptions');
		} catch (Throwable $e2) {
			return $empty;
		}
	}

	$free = [
		'FLOW_FREE' => true,
		'POWER_BI_STANDARD' => true,
		'POWERAPPS_VIRAL' => true,
		'TEAMS_EXPLORATORY' => true,
		'WINDOWS_STORE' => true,
		'MICROSOFT_BUSINESS_CENTER' => true,
	];
	$list = [];
	foreach (($json['value'] ?? []) as $row) {
		$part = (string) ($row['skuPartNumber'] ?? '');
		$created = (string) ($row['createdDateTime'] ?? '');
		$expiry = (string) ($row['nextLifecycleDateTime'] ?? '');
		$startTs = $created !== '' ? strtotime($created) : false;
		$endTs = $expiry !== '' ? strtotime($expiry) : false;
		$now = time();
		$daysLeft = ($endTs !== false) ? (int) ceil(($endTs - $now) / 86400) : null;
		$elapsedPct = 0.0;
		if ($startTs !== false && $endTs !== false && $endTs > $startTs) {
			$elapsedPct = min(100, max(0, (($now - $startTs) / ($endTs - $startTs)) * 100));
		}
		$item = [
			'skuPartNumber' => $part,
			'name' => ms365_sku_name($part),
			'status' => (string) ($row['status'] ?? ''),
			'isTrial' => !empty($row['isTrial']),
			'totalLicenses' => (int) ($row['totalLicenses'] ?? 0),
			'purchaseDate' => $startTs !== false ? date('j M Y', $startTs) : '',
			'expiryDate' => $endTs !== false ? date('j M Y', $endTs) : '',
			'daysLeft' => $daysLeft,
			'elapsedPercent' => round($elapsedPct, 1),
			'isFree' => isset($free[$part]),
		];
		$list[] = $item;
	}

	$paid = array_values(array_filter($list, static function ($s) {
		return empty($s['isFree']) && ($s['expiryDate'] !== '' || ($s['totalLicenses'] ?? 0) > 0);
	}));
	$pickFrom = $paid !== [] ? $paid : $list;
	usort($pickFrom, static function ($a, $b) {
		$ae = ($a['status'] === 'Enabled' || $a['status'] === 'Warning') ? 1 : 0;
		$be = ($b['status'] === 'Enabled' || $b['status'] === 'Warning') ? 1 : 0;
		if ($ae !== $be) {
			return $be <=> $ae;
		}
		return ($b['totalLicenses'] ?? 0) <=> ($a['totalLicenses'] ?? 0);
	});

	return [
		'primary' => $pickFrom[0] ?? null,
		'all' => $list,
	];
}

function ms365_collect_users(array $defaults, string $token, array $skuMap, array &$warnings): array {
	try {
		$raw = ms365_graph_paged(
			$defaults,
			$token,
			'/users?$select=id,displayName,mail,userPrincipalName,accountEnabled,assignedLicenses,jobTitle,officeLocation,userType&$top=999',
			['ConsistencyLevel: eventual']
		);
	} catch (Throwable $e) {
		$warnings[] = 'Users: ' . $e->getMessage();
		return [];
	}

	$out = [];
	foreach ($raw as $u) {
		$names = [];
		foreach (($u['assignedLicenses'] ?? []) as $lic) {
			$sid = strtolower((string) ($lic['skuId'] ?? ''));
			if ($sid !== '' && isset($skuMap[$sid])) {
				$names[] = $skuMap[$sid];
			}
		}
		$names = array_values(array_unique($names));
		$out[] = [
			'id' => (string) ($u['id'] ?? ''),
			'displayName' => (string) ($u['displayName'] ?? ''),
			'userPrincipalName' => (string) ($u['userPrincipalName'] ?? ''),
			'mail' => (string) ($u['mail'] ?? ''),
			'userType' => (string) ($u['userType'] ?? 'Member'),
			'accountEnabled' => !empty($u['accountEnabled']),
			'licensed' => $names !== [],
			'licenses' => $names,
			'jobTitle' => (string) ($u['jobTitle'] ?? ''),
			'officeLocation' => (string) ($u['officeLocation'] ?? ''),
		];
	}
	return $out;
}

function ms365_collect_drives(array $defaults, string $token, array $users, array &$warnings): array {
	$map = [];
	$got = 0;
	$forbidden = false;
	$chunks = array_chunk($users, 20);

	foreach ($chunks as $chunk) {
		$requests = [];
		$idByReq = [];
		foreach ($chunk as $i => $user) {
			$uid = (string) ($user['id'] ?? '');
			if ($uid === '') {
				continue;
			}
			$reqId = (string) ($i + 1);
			$idByReq[$reqId] = $uid;
			$requests[] = [
				'id' => $reqId,
				'method' => 'GET',
				'url' => '/users/' . $uid . '/drive?$select=id,quota,lastModifiedDateTime',
			];
		}
		if ($requests === []) {
			continue;
		}

		try {
			$json = ms365_graph_json($defaults, $token, '/$batch', [], 'POST', ['requests' => $requests]);
		} catch (Throwable $e) {
			$warnings[] = 'OneDrive quota: ' . $e->getMessage();
			break;
		}

		foreach (($json['responses'] ?? []) as $resp) {
			$reqId = (string) ($resp['id'] ?? '');
			$uid = $idByReq[$reqId] ?? '';
			if ($uid === '') {
				continue;
			}
			$status = (int) ($resp['status'] ?? 0);
			if ($status === 403) {
				$forbidden = true;
				continue;
			}
			if ($status < 200 || $status >= 300) {
				continue;
			}
			$body = $resp['body'] ?? [];
			$quota = is_array($body) ? ($body['quota'] ?? []) : [];
			$map[$uid] = [
				'usedBytes' => (float) ($quota['used'] ?? 0),
				'totalBytes' => (float) ($quota['total'] ?? 0),
				'lastModified' => is_array($body) ? (string) ($body['lastModifiedDateTime'] ?? '') : '',
			];
			$got++;
		}
	}

		if ($forbidden && $got === 0) {
			$warnings[] = 'OneDrive live usage (the same GB as Admin Center) needs Files.Read.All — Application permission — then Grant admin consent. Usage reports lag 24–48 hours and often show 0 B.';
		} elseif ($got === 0 && $users !== []) {
			foreach ($users as $user) {
				$uid = (string) ($user['id'] ?? '');
				if ($uid === '') {
					continue;
				}
				try {
					$drive = ms365_graph_json($defaults, $token, '/users/' . rawurlencode($uid) . '/drive?$select=id,quota,lastModifiedDateTime');
				} catch (Throwable $e) {
					$msg = $e->getMessage();
					if (stripos($msg, 'Access denied') !== false || stripos($msg, '403') !== false || stripos($msg, 'Insufficient') !== false || stripos($msg, 'Invalid permission') !== false) {
						$warnings[] = 'OneDrive live usage needs Files.Read.All (Application) + Grant admin consent, then click Refresh.';
						break;
					}
					continue;
				}
				$quota = $drive['quota'] ?? [];
				$map[$uid] = [
					'usedBytes' => (float) ($quota['used'] ?? 0),
					'totalBytes' => (float) ($quota['total'] ?? 0),
					'lastModified' => (string) ($drive['lastModifiedDateTime'] ?? ''),
				];
				$got++;
				if ($got >= 40) {
					break;
				}
			}
		}

	return $map;
}

function ms365_collect_report(array $defaults, string $token, string $path, string $kind, array &$warnings): array {
	$empty = [
		'rows' => [],
		'totals' => ms365_empty_totals($kind),
	];
	try {
		$rows = ms365_graph_csv($defaults, $token, $path);
	} catch (Throwable $e) {
		$warnings[] = ucfirst($kind) . ': ' . $e->getMessage();
		return $empty;
	}

	$parsed = [];
	$totals = ms365_empty_totals($kind);

	foreach ($rows as $row) {
		if ($kind === 'onedrive') {
			$used = (float) ms365_col($row, ['Storage Used (Byte)', 'Storage Used (Bytes)']);
			$alloc = (float) ms365_col($row, ['Storage Allocated (Byte)', 'Storage Allocated (Bytes)']);
			$parsed[] = [
				'displayName' => ms365_col($row, ['Display Name']),
				'userPrincipalName' => ms365_col($row, ['Owner Principal Name', 'User Principal Name']),
				'usedBytes' => $used,
				'allocatedBytes' => $alloc,
				'files' => (int) ms365_col($row, ['File Count', 'Files']),
				'lastActivity' => ms365_col($row, ['Last Activity Date']),
			];
			$totals['usedBytes'] += $used;
			$totals['allocatedBytes'] += $alloc;
			$totals['accounts']++;
		} elseif ($kind === 'mailbox') {
			$used = (float) ms365_col($row, ['Storage Used (Byte)', 'Storage Used (Bytes)']);
			$parsed[] = [
				'displayName' => ms365_col($row, ['Display Name']),
				'userPrincipalName' => ms365_col($row, ['User Principal Name']),
				'usedBytes' => $used,
				'itemCount' => (int) ms365_col($row, ['Item Count']),
				'deletedBytes' => (float) ms365_col($row, ['Deleted Item Size (Byte)', 'Deleted Item Size (Bytes)']),
				'lastActivity' => ms365_col($row, ['Last Activity Date']),
			];
			$totals['usedBytes'] += $used;
			$totals['items'] += (int) ms365_col($row, ['Item Count']);
			$totals['mailboxes']++;
		} elseif ($kind === 'sharepoint') {
			$used = (float) ms365_col($row, ['Storage Used (Byte)', 'Storage Used (Bytes)']);
			$alloc = (float) ms365_col($row, ['Storage Allocated (Byte)', 'Storage Allocated (Bytes)']);
			$parsed[] = [
				'siteUrl' => ms365_col($row, ['Site URL']),
				'owner' => ms365_col($row, ['Owner Display Name', 'Site Owner']),
				'usedBytes' => $used,
				'allocatedBytes' => $alloc,
				'used' => ms365_bytes_human($used),
				'allocated' => ms365_bytes_human($alloc),
				'files' => (int) ms365_col($row, ['File Count']),
				'lastActivity' => ms365_col($row, ['Last Activity Date']),
			];
			$totals['usedBytes'] += $used;
			$totals['allocatedBytes'] += $alloc;
			$totals['sites']++;
		} elseif ($kind === 'teams') {
			$teamChat = (int) ms365_col($row, ['Team Chat Message Count']);
			$privateChat = (int) ms365_col($row, ['Private Chat Message Count']);
			$calls = (int) ms365_col($row, ['Call Count']);
			$meetings = (int) ms365_col($row, ['Meeting Count']);
			$totals['messages'] += $teamChat + $privateChat;
			$totals['calls'] += $calls;
			$totals['meetings'] += $meetings;
			$totals['users']++;
		} elseif ($kind === 'active') {
			$ex = ms365_col($row, ['Exchange Last Activity Date']);
			$od = ms365_col($row, ['OneDrive Last Activity Date']);
			$sp = ms365_col($row, ['SharePoint Last Activity Date']);
			$tm = ms365_col($row, ['Teams Last Activity Date']);
			$last = ms365_latest_date([$ex, $od, $sp, $tm, ms365_col($row, ['Last Activity Date'])]);
			$parsed[] = [
				'displayName' => ms365_col($row, ['Display Name']),
				'userPrincipalName' => ms365_col($row, ['User Principal Name']),
				'lastActivity' => $last,
				'products' => ms365_col($row, ['Assigned Products']),
			];
		}
	}

	if ($kind === 'sharepoint') {
		usort($parsed, static function ($a, $b) {
			return $b['usedBytes'] <=> $a['usedBytes'];
		});
	}

	return ['rows' => $parsed, 'totals' => $totals];
}

function ms365_empty_totals(string $kind): array {
	if ($kind === 'onedrive') {
		return ['usedBytes' => 0.0, 'allocatedBytes' => 0.0, 'accounts' => 0];
	}
	if ($kind === 'mailbox') {
		return ['usedBytes' => 0.0, 'items' => 0, 'mailboxes' => 0];
	}
	if ($kind === 'sharepoint') {
		return ['usedBytes' => 0.0, 'allocatedBytes' => 0.0, 'sites' => 0];
	}
	if ($kind === 'teams') {
		return ['messages' => 0, 'calls' => 0, 'meetings' => 0, 'users' => 0];
	}
	return [];
}

function ms365_collect_signins(array $defaults, string $token, array &$warnings): array {
	try {
		$json = ms365_graph_json(
			$defaults,
			$token,
			'/auditLogs/signIns?$top=40&$orderby=createdDateTime%20desc&$select=createdDateTime,userDisplayName,userPrincipalName,ipAddress,status,location,clientAppUsed,deviceDetail,appDisplayName'
		);
	} catch (Throwable $e) {
		$msg = $e->getMessage();
		if (stripos($msg, 'Premium') !== false || stripos($msg, 'NonPremium') !== false) {
			$warnings[] = 'Sign-ins: Entra ID Premium (P1/P2) is required for audit log sign-ins. Users, licenses, and usage reports do not need it.';
		} else {
			$warnings[] = 'Sign-ins: ' . $msg;
		}
		return ['success' => 0, 'failed' => 0, 'recent' => []];
	}

	$recent = [];
	$ok = 0;
	$fail = 0;
	foreach (($json['value'] ?? []) as $row) {
		$errorCode = (int) ($row['status']['errorCode'] ?? 0);
		$success = $errorCode === 0;
		if ($success) {
			$ok++;
		} else {
			$fail++;
		}
		$loc = $row['location'] ?? [];
		$device = $row['deviceDetail'] ?? [];
		$recent[] = [
			'when' => (string) ($row['createdDateTime'] ?? ''),
			'user' => (string) ($row['userDisplayName'] ?? ''),
			'upn' => (string) ($row['userPrincipalName'] ?? ''),
			'status' => $success ? 'Success' : 'Failed',
			'success' => $success,
			'ip' => (string) ($row['ipAddress'] ?? ''),
			'city' => (string) ($loc['city'] ?? ''),
			'country' => (string) ($loc['countryOrRegion'] ?? ''),
			'app' => (string) ($row['appDisplayName'] ?? ''),
			'browser' => (string) ($device['browser'] ?? ''),
			'os' => (string) ($device['operatingSystem'] ?? ''),
		];
	}
	return ['success' => $ok, 'failed' => $fail, 'recent' => $recent];
}

function ms365_http(string $method, string $url, array $headers, $body, int $timeout, bool $follow): array {
	if (function_exists('curl_init')) {
		return ms365_http_curl($method, $url, $headers, $body, $timeout, $follow);
	}
	return ms365_http_stream($method, $url, $headers, $body, $timeout, $follow);
}

function ms365_cafile(): string {
	$cafile = __DIR__ . '/certs/cacert.pem';
	return is_file($cafile) ? $cafile : '';
}

function ms365_http_curl(string $method, string $url, array $headers, $body, int $timeout, bool $follow): array {
	$ch = curl_init($url);
	$opts = [
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_HEADER => true,
		CURLOPT_HTTPHEADER => $headers,
		CURLOPT_TIMEOUT => $timeout,
		CURLOPT_CONNECTTIMEOUT => 20,
		CURLOPT_SSL_VERIFYPEER => true,
		CURLOPT_FOLLOWLOCATION => $follow,
		CURLOPT_CUSTOMREQUEST => $method,
	];
	$cafile = ms365_cafile();
	if ($cafile !== '') {
		$opts[CURLOPT_CAINFO] = $cafile;
	}
	if ($body !== null) {
		$opts[CURLOPT_POSTFIELDS] = $body;
	}
	curl_setopt_array($ch, $opts);
	$raw = curl_exec($ch);
	if ($raw === false) {
		$err = curl_error($ch);
		curl_close($ch);
		throw new RuntimeException('HTTP error: ' . $err);
	}
	$code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$headerSize = (int) curl_getinfo($ch, CURLINFO_HEADER_SIZE);
	curl_close($ch);
	return [
		'code' => $code,
		'headers' => substr($raw, 0, $headerSize),
		'body' => substr($raw, $headerSize),
	];
}

function ms365_http_stream(string $method, string $url, array $headers, $body, int $timeout, bool $follow): array {
	if (!ini_get('allow_url_fopen')) {
		throw new RuntimeException('PHP cURL is unavailable and allow_url_fopen is off. Enable extension=curl in php.ini and restart Apache.');
	}

	$headerLines = $headers;
	$hasContentType = false;
	foreach ($headerLines as $h) {
		if (stripos($h, 'Content-Type:') === 0) {
			$hasContentType = true;
			break;
		}
	}
	if ($body !== null && !$hasContentType) {
		$headerLines[] = 'Content-Type: application/x-www-form-urlencoded';
	}

	$http = [
		'method' => $method,
		'header' => implode("\r\n", $headerLines),
		'timeout' => $timeout,
		'ignore_errors' => true,
		'follow_location' => $follow ? 1 : 0,
		'max_redirects' => $follow ? 5 : 0,
	];
	if ($body !== null) {
		$http['content'] = $body;
	}

	$ssl = [
		'verify_peer' => true,
		'verify_peer_name' => true,
	];
	$cafile = ms365_cafile();
	if ($cafile !== '') {
		$ssl['cafile'] = $cafile;
	}

	$ctx = stream_context_create(['http' => $http, 'ssl' => $ssl]);
	$raw = @file_get_contents($url, false, $ctx);
	if ($raw === false) {
		$err = error_get_last();
		throw new RuntimeException('HTTP error: ' . (string) ($err['message'] ?? 'stream request failed'));
	}

	$responseHeaders = isset($http_response_header) && is_array($http_response_header) ? $http_response_header : [];
	$code = 0;
	$headerBlob = '';
	foreach ($responseHeaders as $line) {
		$headerBlob .= $line . "\r\n";
		if (preg_match('/^HTTP\/\S+\s+(\d+)/', $line, $m)) {
			$code = (int) $m[1];
		}
	}

	return [
		'code' => $code,
		'headers' => $headerBlob,
		'body' => $raw,
	];
}

function ms365_header_value(string $headers, string $name): string {
	if (preg_match('/^' . preg_quote($name, '/') . ':\s*(.+)$/mi', $headers, $m)) {
		return trim($m[1]);
	}
	return '';
}

function ms365_extract_error(string $body): string {
	$json = json_decode($body, true);
	if (is_array($json)) {
		if (isset($json['error_description'])) {
			return (string) $json['error_description'];
		}
		if (isset($json['error']['message'])) {
			return (string) $json['error']['message'];
		}
		if (isset($json['error']) && is_string($json['error'])) {
			return (string) $json['error'];
		}
	}
	$trim = trim(strip_tags($body));
	return strlen($trim) > 300 ? substr($trim, 0, 300) . '…' : $trim;
}

function ms365_parse_csv(string $raw): array {
	$raw = preg_replace('/^\xEF\xBB\xBF/', '', $raw);
	if ($raw === null || trim($raw) === '') {
		return [];
	}
	$fp = fopen('php://temp', 'r+');
	if ($fp === false) {
		return [];
	}
	fwrite($fp, $raw);
	rewind($fp);
	$headers = fgetcsv($fp);
	if (!is_array($headers)) {
		fclose($fp);
		return [];
	}
	$headers = array_map(static function ($h) {
		return trim((string) $h);
	}, $headers);
	$rows = [];
	while (($cols = fgetcsv($fp)) !== false) {
		if ($cols === [null] || $cols === false) {
			continue;
		}
		$assoc = [];
		foreach ($headers as $i => $h) {
			if ($h === '') {
				continue;
			}
			$assoc[$h] = isset($cols[$i]) ? (string) $cols[$i] : '';
		}
		if ($assoc !== []) {
			$rows[] = $assoc;
		}
	}
	fclose($fp);
	return $rows;
}

function ms365_col(array $row, array $names): string {
	foreach ($names as $name) {
		foreach ($row as $key => $val) {
			if (strcasecmp(trim((string) $key), $name) === 0) {
				return trim((string) $val);
			}
		}
	}
	return '';
}

function ms365_latest_date(array $dates): string {
	$best = '';
	$bestTs = 0;
	foreach ($dates as $d) {
		$d = trim((string) $d);
		if ($d === '' || $d === '0001-01-01') {
			continue;
		}
		$ts = strtotime($d);
		if ($ts !== false && $ts > $bestTs) {
			$bestTs = $ts;
			$best = $d;
		}
	}
	return $best;
}

function ms365_bytes_human($bytes): string {
	$bytes = (float) $bytes;
	if ($bytes <= 0) {
		return '0 B';
	}
	$units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
	$i = 0;
	while ($bytes >= 1024 && $i < count($units) - 1) {
		$bytes /= 1024;
		$i++;
	}
	$prec = $i >= 3 ? 2 : ($i >= 2 ? 1 : 0);
	return number_format($bytes, $prec) . ' ' . $units[$i];
}

function ms365_sku_name(string $part): string {
	$map = [
		'O365_BUSINESS_ESSENTIALS' => 'Microsoft 365 Business Basic',
		'O365_BUSINESS_PREMIUM' => 'Microsoft 365 Business Standard',
		'SPB' => 'Microsoft 365 Business Premium',
		'SPE_E3' => 'Microsoft 365 E3',
		'SPE_E5' => 'Microsoft 365 E5',
		'ENTERPRISEPACK' => 'Office 365 E3',
		'ENTERPRISEPREMIUM' => 'Office 365 E5',
		'EXCHANGESTANDARD' => 'Exchange Online (Plan 1)',
		'EXCHANGEENTERPRISE' => 'Exchange Online (Plan 2)',
		'FLOW_FREE' => 'Microsoft Power Automate Free',
		'POWER_BI_STANDARD' => 'Power BI (Free)',
		'POWERAPPS_VIRAL' => 'Power Apps Trial',
		'TEAMS_EXPLORATORY' => 'Microsoft Teams Exploratory',
		'MICROSOFT_BUSINESS_CENTER' => 'Microsoft Business Center',
		'WINDOWS_STORE' => 'Windows Store for Business',
		'AAD_PREMIUM' => 'Azure AD Premium P1',
		'AAD_PREMIUM_P2' => 'Azure AD Premium P2',
	];
	return $map[$part] ?? ($part !== '' ? $part : 'Unknown license');
}
