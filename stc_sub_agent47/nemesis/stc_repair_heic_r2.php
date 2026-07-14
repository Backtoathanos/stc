<?php
/**
 * Repair R2 objects named .jpg but Content-Type/body is HEIC.
 * Processes ONE row per HTTP request (avoids Cloudflare 524).
 * Uses cursor (?before=ID) so non-HEIC .jpg skips advance past forever.
 *
 * Browser (auto-continues):
 *   https://YOUR-DOMAIN/stc_sub_agent47/nemesis/stc_repair_heic_r2.php?token=STC_LOCAL_HEIC_FIX
 *
 * CLI (batches until done):
 *   php stc_repair_heic_r2.php
 *
 * Optional: &limit=1  &dry=1  &before=99999
 */
declare(strict_types=1);

@set_time_limit(90);
@ini_set('max_execution_time', '90');

$CLI = (PHP_SAPI === 'cli');
$tokenOk = isset($_GET['token']) && $_GET['token'] === 'STC_LOCAL_HEIC_FIX';
$dry = !empty($_GET['dry']) || (isset($argv) && in_array('--dry', $argv, true));
$limit = isset($_GET['limit']) ? max(1, min(5, (int) $_GET['limit'])) : ($CLI ? 20 : 1);
$before = isset($_GET['before']) ? (int) $_GET['before'] : 0;

if (!$CLI) {
	if (!$tokenOk) {
		header('Content-Type: text/plain; charset=UTF-8');
		http_response_code(403);
		echo "Forbidden. Add ?token=STC_LOCAL_HEIC_FIX\n";
		exit;
	}
	header('Content-Type: text/html; charset=UTF-8');
	echo '<!doctype html><html><head><meta charset="utf-8"><title>HEIC R2 Repair</title>';
	echo '<style>body{font-family:Consolas,monospace;padding:16px;background:#111;color:#ddd}';
	echo '.ok{color:#7CFC00}.skip{color:#ffa500}.fail{color:#ff6b6b}.done{color:#7ec8e3}</style></head><body>';
	echo '<h3>HEIC → correct .heic key repair</h3><pre>';
	ob_implicit_flush(true);
	while (ob_get_level() > 0) {
		ob_end_flush();
	}
}

require_once __DIR__ . '/../../MCU/db.php';
require_once __DIR__ . '/../../MCU/product_r2_upload.php';

function out(string $msg): void
{
	global $CLI;
	if ($CLI) {
		echo strip_tags($msg) . "\n";
	} else {
		echo $msg . "\n";
		flush();
	}
}

if (!isset($con) || !$con instanceof mysqli) {
	out('DB unavailable');
	exit(1);
}
if (!function_exists('stc_r2_product_upload_configured') || !stc_r2_product_upload_configured()) {
	out('R2 not configured on this server (.env R2_* missing).');
	exit(1);
}
$cfg = stc_mcu_r2_config();
if ($cfg === null) {
	out('R2 config missing.');
	exit(1);
}
if (!function_exists('stc_r2_sigv4_copy_object')) {
	out('Copy helper missing — deploy latest MCU/product_r2_upload.php first.');
	exit(1);
}

function stc_repair_is_heic_bytes(string $head): bool
{
	return strlen($head) >= 12
		&& substr($head, 4, 4) === 'ftyp'
		&& in_array(strtolower(substr($head, 8, 4)), ['heic', 'heif', 'mif1', 'msf1', 'hevx', 'hevc'], true);
}

function stc_repair_http_head_or_range(string $url): array
{
	if (!function_exists('curl_init')) {
		return ['ok' => false, 'code' => 0, 'ctype' => '', 'body' => ''];
	}

	$ch = curl_init($url);
	curl_setopt_array($ch, [
		CURLOPT_NOBODY => true,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_TIMEOUT => 20,
		CURLOPT_HEADER => true,
	]);
	curl_exec($ch);
	$code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$ctype = (string) curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
	curl_close($ch);

	$ch2 = curl_init($url);
	curl_setopt_array($ch2, [
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_TIMEOUT => 20,
		CURLOPT_RANGE => '0-63',
	]);
	$body = curl_exec($ch2);
	$code2 = (int) curl_getinfo($ch2, CURLINFO_HTTP_CODE);
	curl_close($ch2);

	$ok = ($code >= 200 && $code < 300) || ($code2 >= 200 && $code2 < 300);
	return [
		'ok' => $ok,
		'code' => $code ?: $code2,
		'ctype' => $ctype,
		'body' => is_string($body) ? $body : '',
	];
}

function stc_repair_public_url_to_key(string $url): string
{
	$path = parse_url($url, PHP_URL_PATH);
	return ltrim(rawurldecode((string) $path), '/');
}

function stc_repair_next_url(int $before, int $limit, bool $dry): string
{
	$q = '?token=STC_LOCAL_HEIC_FIX&limit=' . $limit;
	if ($before > 0) {
		$q .= '&before=' . $before;
	}
	if ($dry) {
		$q .= '&dry=1';
	}
	return $q;
}

$jpgCond = "
	`stc_safetytbm_img_location` LIKE 'https://%r2.dev/%'
	AND (`stc_safetytbm_img_location` LIKE '%.jpg' OR `stc_safetytbm_img_location` LIKE '%.jpeg')
";

$countRs = mysqli_query($con, "SELECT COUNT(*) AS c FROM `stc_safetytbm_img` WHERE {$jpgCond}");
$pendingLeft = $countRs ? (int) (mysqli_fetch_assoc($countRs)['c'] ?? 0) : 0;

out("Pending .jpg R2 URLs in DB: {$pendingLeft}");
out('Batch limit: ' . $limit . ($dry ? ' (DRY RUN)' : '') . ($before > 0 ? " before={$before}" : ''));
out(str_repeat('-', 60));

$beforeSql = $before > 0 ? " AND `stc_safetytbm_img_id` < " . (int) $before : '';
$sql = "
	SELECT `stc_safetytbm_img_id` AS rid, `stc_safetytbm_img_location` AS url
	FROM `stc_safetytbm_img`
	WHERE {$jpgCond}
	{$beforeSql}
	ORDER BY `stc_safetytbm_img_id` DESC
	LIMIT " . (int) $limit . "
";
$rs = mysqli_query($con, $sql);
if (!$rs) {
	out('Query failed: ' . mysqli_error($con));
	exit(1);
}

$rows = [];
while ($row = mysqli_fetch_assoc($rs)) {
	$rows[] = $row;
}

if ($rows === []) {
	out('<span class="done">Nothing left to scan (cursor exhausted). Pending .jpg that were not HEIC still use .jpg keys — OK.</span>');
	if (!$CLI) {
		echo '</pre><p class="done">Scan complete.</p></body></html>';
	}
	exit(0);
}

$fixed = 0;
$skipped = 0;
$failed = 0;
$lastId = $before;

foreach ($rows as $row) {
	$url = (string) $row['url'];
	$rid = (int) $row['rid'];
	$lastId = $rid;
	out("Check #{$rid}");
	out('  ' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8'));

	$head = stc_repair_http_head_or_range($url);
	if (!$head['ok']) {
		out("  <span class=\"skip\">SKIP: cannot read object (HTTP {$head['code']})</span>");
		$skipped++;
		continue;
	}

	$isHeic = stripos($head['ctype'], 'heic') !== false
		|| stripos($head['ctype'], 'heif') !== false
		|| stc_repair_is_heic_bytes($head['body']);

	if (!$isHeic) {
		out("  <span class=\"skip\">SKIP: not HEIC (ctype=" . htmlspecialchars($head['ctype'], ENT_QUOTES, 'UTF-8') . ')</span>');
		$skipped++;
		continue;
	}

	$oldKey = stc_repair_public_url_to_key($url);
	$newKey = (string) preg_replace('/\.jpe?g$/i', '.heic', $oldKey);
	if ($newKey === '' || $newKey === $oldKey) {
		out('  <span class="skip">SKIP: bad key</span>');
		$skipped++;
		continue;
	}

	out("  Detected HEIC. Copy {$oldKey} → {$newKey}");

	if ($dry) {
		out('  <span class="ok">DRY: would copy + update DB</span>');
		$fixed++;
		continue;
	}

	$copy = stc_r2_sigv4_copy_object($cfg, $oldKey, $newKey, 'image/heic');
	if (!$copy['ok']) {
		out('  <span class="fail">FAIL: R2 copy ' . htmlspecialchars($copy['error'] ?? ('HTTP ' . $copy['http']), ENT_QUOTES, 'UTF-8') . '</span>');
		$failed++;
		continue;
	}

	$newUrl = stc_r2_public_url_from_object_key($cfg['public_base'], $newKey);
	$esc = mysqli_real_escape_string($con, $newUrl);
	$ok = mysqli_query($con, "
		UPDATE `stc_safetytbm_img`
		SET `stc_safetytbm_img_location`='{$esc}'
		WHERE `stc_safetytbm_img_id`='{$rid}'
		LIMIT 1
	");
	if (!$ok) {
		out('  <span class="fail">FAIL: DB ' . htmlspecialchars(mysqli_error($con), ENT_QUOTES, 'UTF-8') . '</span>');
		$failed++;
		continue;
	}

	out('  <span class="ok">FIXED → ' . htmlspecialchars($newUrl, ENT_QUOTES, 'UTF-8') . '</span>');
	$fixed++;
}

$countRs2 = mysqli_query($con, "SELECT COUNT(*) AS c FROM `stc_safetytbm_img` WHERE {$jpgCond}");
$remainingJpg = $countRs2 ? (int) (mysqli_fetch_assoc($countRs2)['c'] ?? 0) : 0;

out(str_repeat('-', 60));
out("Batch: fixed={$fixed} skipped={$skipped} failed={$failed}");
out("Still .jpg URLs in DB: {$remainingJpg}");

if ($CLI) {
	// Keep walking until cursor empty (CLI can loop more aggressively)
	exit(0);
}

echo '</pre>';
$next = stc_repair_next_url($lastId, $limit, $dry);
if ($failed > 0) {
	echo '<p class="fail">Some failed. <a href="' . htmlspecialchars($next, ENT_QUOTES, 'UTF-8') . '">Continue / retry next</a></p>';
} else {
	echo '<p class="done">Continuing in 1.2s… (cursor before=' . (int) $lastId . ')</p>';
	echo '<meta http-equiv="refresh" content="1.2;url=' . htmlspecialchars($next, ENT_QUOTES, 'UTF-8') . '">';
	echo '<p><a href="' . htmlspecialchars($next, ENT_QUOTES, 'UTF-8') . '">Continue now</a></p>';
}
echo '</body></html>';
