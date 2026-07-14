<?php
/**
 * One-time local repair: R2 objects named .jpg but body is HEIC.
 * Re-uploads as .heic key + updates DB URL.
 *
 * Usage (logged-in Site/Admin session OR CLI):
 *   php stc_repair_heic_r2.php
 *   http://localhost/stc/stc_sub_agent47/nemesis/stc_repair_heic_r2.php?token=STC_LOCAL_HEIC_FIX
 *
 * Remove this file after repair if you want.
 */
declare(strict_types=1);

$CLI = (PHP_SAPI === 'cli');
$tokenOk = isset($_GET['token']) && $_GET['token'] === 'STC_LOCAL_HEIC_FIX';

if (!$CLI) {
	header('Content-Type: text/plain; charset=UTF-8');
	if (!$tokenOk) {
		http_response_code(403);
		echo "Forbidden. Add ?token=STC_LOCAL_HEIC_FIX\n";
		exit;
	}
}

require_once __DIR__ . '/../../MCU/db.php';
require_once __DIR__ . '/../../MCU/product_r2_upload.php';

if (!isset($con) || !$con instanceof mysqli) {
	fwrite(STDERR, "DB unavailable\n");
	exit(1);
}

if (!function_exists('stc_r2_product_upload_configured') || !stc_r2_product_upload_configured()) {
	echo "R2 not configured.\n";
	exit(1);
}

$cfg = stc_mcu_r2_config();
if ($cfg === null) {
	echo "R2 config missing.\n";
	exit(1);
}

function stc_repair_is_heic_bytes(string $pathOrUrlHead): bool
{
	return strlen($pathOrUrlHead) >= 12
		&& substr($pathOrUrlHead, 4, 4) === 'ftyp'
		&& in_array(strtolower(substr($pathOrUrlHead, 8, 4)), ['heic', 'heif', 'mif1', 'msf1', 'hevx', 'hevc'], true);
}

function stc_repair_http_get(string $url, int $maxBytes = 0): array
{
	if (function_exists('curl_init')) {
		$ch = curl_init($url);
		curl_setopt_array($ch, [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_TIMEOUT => 180,
			CURLOPT_HTTPHEADER => ['Accept: */*'],
		]);
		if ($maxBytes > 0) {
			curl_setopt($ch, CURLOPT_RANGE, '0-' . ($maxBytes - 1));
		}
		$body = curl_exec($ch);
		$code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$ctype = (string) curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
		curl_close($ch);
		return ['ok' => $body !== false && $code >= 200 && $code < 300, 'body' => (string) $body, 'code' => $code, 'ctype' => $ctype];
	}
	$ctx = stream_context_create(['http' => ['timeout' => 180, 'ignore_errors' => true]]);
	$body = @file_get_contents($url, false, $ctx);
	$code = 0;
	$ctype = '';
	if (!empty($http_response_header[0]) && preg_match('/\s(\d{3})\s/', $http_response_header[0], $m)) {
		$code = (int) $m[1];
	}
	foreach ($http_response_header ?? [] as $h) {
		if (stripos($h, 'Content-Type:') === 0) {
			$ctype = trim(substr($h, 13));
		}
	}
	return ['ok' => $body !== false && $code >= 200 && $code < 300, 'body' => (string) $body, 'code' => $code, 'ctype' => $ctype];
}

$sql = "
	SELECT `stc_safetytbm_img_id` AS rid, `stc_safetytbm_img_location` AS url
	FROM `stc_safetytbm_img`
	WHERE `stc_safetytbm_img_location` LIKE 'https://%r2.dev/%'
	  AND `stc_safetytbm_img_location` LIKE '%.jpg'
	ORDER BY `stc_safetytbm_img_id` DESC
	LIMIT 500
";

$rs = mysqli_query($con, $sql);
if (!$rs) {
	echo 'Query failed: ' . mysqli_error($con) . "\n";
	exit(1);
}

$fixed = 0;
$skipped = 0;
$failed = 0;

while ($row = mysqli_fetch_assoc($rs)) {
	$url = (string) $row['url'];
	$rid = (int) $row['rid'];
	echo "Check #{$rid}: {$url}\n";

	$head = stc_repair_http_get($url, 64);
	if (!$head['ok']) {
		echo "  SKIP: cannot HEAD/GET ({$head['code']})\n";
		$skipped++;
		continue;
	}

	$isHeic = stripos($head['ctype'], 'heic') !== false
		|| stripos($head['ctype'], 'heif') !== false
		|| stc_repair_is_heic_bytes($head['body']);

	if (!$isHeic) {
		echo "  SKIP: not HEIC (ctype={$head['ctype']})\n";
		$skipped++;
		continue;
	}

	$full = stc_repair_http_get($url, 0);
	if (!$full['ok'] || $full['body'] === '') {
		echo "  FAIL: download\n";
		$failed++;
		continue;
	}

	$tmp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'stc_heic_fix_' . $rid . '.heic';
	if (@file_put_contents($tmp, $full['body']) === false) {
		echo "  FAIL: write temp\n";
		$failed++;
		continue;
	}

	$path = parse_url($url, PHP_URL_PATH);
	$oldKey = ltrim(rawurldecode((string) $path), '/');
	// public URL path is object key directly for this R2 public bucket
	if ($oldKey === '') {
		@unlink($tmp);
		echo "  FAIL: bad key\n";
		$failed++;
		continue;
	}
	$newKey = preg_replace('/\.jpe?g$/i', '.heic', $oldKey);
	if ($newKey === $oldKey) {
		@unlink($tmp);
		echo "  SKIP: key already not jpg\n";
		$skipped++;
		continue;
	}

	$put = stc_r2_sigv4_put_object($cfg, $newKey, $tmp, 'image/heic');
	@unlink($tmp);
	if (!$put['ok']) {
		echo '  FAIL: put ' . ($put['error'] ?? ('HTTP ' . $put['http'])) . "\n";
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
		echo '  FAIL: DB update ' . mysqli_error($con) . "\n";
		$failed++;
		continue;
	}

	echo "  FIXED → {$newUrl}\n";
	$fixed++;
}

echo "\nDone. fixed={$fixed} skipped={$skipped} failed={$failed}\n";
echo "Note: old .jpg objects still exist on R2 (safe to delete later from Cloudflare).\n";
