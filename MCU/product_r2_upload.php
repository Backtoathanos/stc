<?php

/**
 * Upload product images to Cloudflare R2 from legacy PHP (MCU).
 * Uses the same R2_* env vars as Laravel superadmin (filesystems.disks.s3).
 *
 * Loads getenv(); if unset, parses ../superadmin.stcassociate.com/.env when readable.
 *
 * @return array{ok:bool, public_url?:string, object_key?:string, error?:string}
 */

if (!function_exists('stc_mcu_r2_load_env_file')) {
    function stc_mcu_r2_load_env_file(string $path): void
    {
        $lines = @file($path, FILE_IGNORE_NEW_LINES);
        if ($lines === false) {
            return;
        }

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '' || (isset($line[0]) && $line[0] === '#')) {
                continue;
            }

            $eq = strpos($line, '=');
            if ($eq === false) {
                continue;
            }

            $k = trim(substr($line, 0, $eq));
            $v = trim(substr($line, $eq + 1));
            if ($k === '') {
                continue;
            }

            if (strlen($v) >= 2) {
                $q = $v[0];
                if (($q === '"' && substr($v, -1) === '"') || ($q === "'" && substr($v, -1) === "'")) {
                    $v = substr($v, 1, -1);
                }
            }

            if (getenv($k) !== false && getenv($k) !== '') {
                continue;
            }
            putenv($k . '=' . $v);
            $_ENV[$k] = $v;
        }
    }
}

if (!function_exists('stc_mcu_r2_config')) {
    /**
     * @return array{key:string, secret:string, region:string, bucket:string, endpoint:string, use_path_style:bool, public_base:string}|null
     */
    function stc_mcu_r2_config(): ?array
    {
        static $cached = null;
        static $boot = false;

        if ($cached !== null) {
            return $cached ?: null;
        }

        if (!$boot) {
            $boot = true;
            $root = dirname(__DIR__);
            $envPath = $root . DIRECTORY_SEPARATOR . 'superadmin.stcassociate.com' . DIRECTORY_SEPARATOR . '.env';
            if (is_readable($envPath)) {
                stc_mcu_r2_load_env_file($envPath);
            }
        }

        $key = (string) (getenv('R2_ACCESS_KEY_ID') ?: getenv('AWS_ACCESS_KEY_ID') ?: '');
        $secret = (string) (getenv('R2_SECRET_ACCESS_KEY') ?: getenv('AWS_SECRET_ACCESS_KEY') ?: '');
        $bucket = (string) (getenv('R2_BUCKET') ?: getenv('AWS_BUCKET') ?: '');
        $endpoint = (string) (getenv('R2_ENDPOINT') ?: getenv('AWS_ENDPOINT') ?: '');
        $publicBase = (string) (getenv('R2_PUBLIC_URL') ?: getenv('AWS_URL') ?: '');
        $region = (string) (getenv('R2_REGION') ?: getenv('AWS_DEFAULT_REGION') ?: 'auto');

        $pathStyleRaw = getenv('R2_USE_PATH_STYLE_ENDPOINT');
        if ($pathStyleRaw === false || $pathStyleRaw === '') {
            $pathStyleRaw = getenv('AWS_USE_PATH_STYLE_ENDPOINT');
        }
        $usePathStyle = filter_var($pathStyleRaw, FILTER_VALIDATE_BOOLEAN);
        if ($pathStyleRaw === false || $pathStyleRaw === '') {
            $usePathStyle = true;
        }

        if ($key === '' || $secret === '' || $bucket === '' || $endpoint === '' || $publicBase === '') {
            $cached = [];

            return null;
        }

        $cached = [
            'key' => $key,
            'secret' => $secret,
            'region' => $region !== '' ? $region : 'auto',
            'bucket' => $bucket,
            'endpoint' => $endpoint,
            'use_path_style' => $usePathStyle,
            'public_base' => rtrim($publicBase, '/'),
        ];

        return $cached;
    }
}

if (!function_exists('stc_r2_product_upload_configured')) {
    function stc_r2_product_upload_configured(): bool
    {
        return stc_mcu_r2_config() !== null;
    }
}

if (!function_exists('stc_r2_public_url_from_object_key')) {
    function stc_r2_public_url_from_object_key(string $publicBase, string $objectKey): string
    {
        $segments = explode('/', str_replace('\\', '/', $objectKey));

        return rtrim($publicBase, '/') . '/' . implode('/', array_map('rawurlencode', $segments));
    }
}

if (!function_exists('stc_r2_uri_encode_object_key_path')) {
    /** Path-style request path: /bucket/segment1/segment2… */
    function stc_r2_uri_encode_object_key_path(string $bucket, string $objectKey): string
    {
        $segments = array_merge([$bucket], explode('/', str_replace('\\', '/', $objectKey)));

        return '/' . implode('/', array_map('rawurlencode', $segments));
    }
}

if (!function_exists('stc_r2_sigv4_put_object')) {
    /**
     * PUT object via SigV4 (S3-compatible).
     *
     * @param array{key:string, secret:string, region:string, bucket:string, endpoint:string, use_path_style:bool, public_base:string} $cfg
     * @return array{ok:bool, http:int, error?:string}
     */
    function stc_r2_sigv4_put_object(array $cfg, string $objectKey, string $localPath): array
    {
        if (!is_readable($localPath)) {
            return ['ok' => false, 'http' => 0, 'error' => 'Local file not readable.'];
        }

        $size = filesize($localPath);
        if ($size === false) {
            return ['ok' => false, 'http' => 0, 'error' => 'Could not read file size.'];
        }

        $parsed = parse_url($cfg['endpoint']);
        if (!is_array($parsed) || empty($parsed['host'])) {
            return ['ok' => false, 'http' => 0, 'error' => 'Invalid R2_ENDPOINT URL.'];
        }

        $scheme = isset($parsed['scheme']) ? $parsed['scheme'] : 'https';
        $host = $parsed['host'];
        $payloadHash = strtolower(hash_file('sha256', $localPath));

        $amzDate = gmdate('Ymd\THis\Z');
        $dateStamp = gmdate('Ymd');

        $canonicalUri = stc_r2_uri_encode_object_key_path($cfg['bucket'], $objectKey);
        $canonicalQueryString = '';

        $canonicalHeaders = 'host:' . $host . "\n"
            . 'x-amz-content-sha256:' . $payloadHash . "\n"
            . 'x-amz-date:' . $amzDate . "\n";
        $signedHeaders = 'host;x-amz-content-sha256;x-amz-date';

        $canonicalRequest = "PUT\n"
            . $canonicalUri . "\n"
            . $canonicalQueryString . "\n"
            . $canonicalHeaders . "\n"
            . $signedHeaders . "\n"
            . $payloadHash;

        $credentialScope = $dateStamp . '/' . $cfg['region'] . '/s3/aws4_request';
        $stringToSign = "AWS4-HMAC-SHA256\n"
            . $amzDate . "\n"
            . $credentialScope . "\n"
            . hash('sha256', $canonicalRequest);

        $kSecret = 'AWS4' . $cfg['secret'];
        $kDate = hash_hmac('sha256', $dateStamp, $kSecret, true);
        $kRegion = hash_hmac('sha256', $cfg['region'], $kDate, true);
        $kService = hash_hmac('sha256', 's3', $kRegion, true);
        $kSigning = hash_hmac('sha256', 'aws4_request', $kService, true);
        $signature = hash_hmac('sha256', $stringToSign, $kSigning);

        $authorization = 'AWS4-HMAC-SHA256 Credential=' . $cfg['key'] . '/' . $credentialScope
            . ', SignedHeaders=' . $signedHeaders . ', Signature=' . $signature;

        $url = $scheme . '://' . $host . $canonicalUri;

        $headers = [
            'Host: ' . $host,
            'x-amz-date: ' . $amzDate,
            'x-amz-content-sha256: ' . $payloadHash,
            'Authorization: ' . $authorization,
        ];

        if (function_exists('curl_init')) {
            $fh = fopen($localPath, 'rb');
            if ($fh === false) {
                return ['ok' => false, 'http' => 0, 'error' => 'Could not open file for upload.'];
            }

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_PUT, true);
            curl_setopt($ch, CURLOPT_INFILE, $fh);
            curl_setopt($ch, CURLOPT_INFILESIZE, $size);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            curl_exec($ch);
            $http = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            fclose($fh);

            if ($http >= 200 && $http < 300) {
                return ['ok' => true, 'http' => $http];
            }

            return ['ok' => false, 'http' => $http, 'error' => 'R2 upload failed (HTTP ' . $http . ').'];
        }

        $body = file_get_contents($localPath);
        if ($body === false) {
            return ['ok' => false, 'http' => 0, 'error' => 'Could not read file for upload.'];
        }

        $streamHeaders = implode("\r\n", array_merge($headers, [
            'Content-Length: ' . $size,
            'Content-Type: application/octet-stream',
        ]));

        $context = stream_context_create([
            'http' => [
                'method' => 'PUT',
                'header' => $streamHeaders,
                'content' => $body,
                'ignore_errors' => true,
                'timeout' => 120,
            ],
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true,
            ],
        ]);

        @file_get_contents($url, false, $context);
        $http = 0;
        if (!empty($http_response_header) && is_array($http_response_header)) {
            if (preg_match('#HTTP/\S+\s+(\d+)#', $http_response_header[0], $m)) {
                $http = (int) $m[1];
            }
        }

        if ($http >= 200 && $http < 300) {
            return ['ok' => true, 'http' => $http];
        }

        return ['ok' => false, 'http' => $http, 'error' => 'R2 upload failed (HTTP ' . $http . ').'];
    }
}

if (!function_exists('stc_r2_upload_product_image_from_path')) {
    /**
     * Upload a local file (usually PHP upload tmp) to R2 under products/.
     *
     * @param string $absoluteLocalPath readable path
     * @param int $productId product id for object key (0 if unknown — uses "vikings" prefix only)
     */
    function stc_r2_upload_product_image_from_path(string $absoluteLocalPath, int $productId = 0): array
    {
        $cfg = stc_mcu_r2_config();
        if ($cfg === null) {
            return ['ok' => false, 'error' => 'R2 is not configured (missing R2_* in environment or superadmin .env).'];
        }

        $ext = strtolower(pathinfo($absoluteLocalPath, PATHINFO_EXTENSION));
        if ($ext === '') {
            $ext = 'jpg';
        }
        if (!preg_match('/^[a-z0-9]{1,8}$/', $ext)) {
            $ext = 'jpg';
        }

        $uniq = substr(str_replace('.', '', uniqid('', true)), 0, 24);
        $prefix = $productId > 0 ? (string) $productId : 'vikings';
        $objectKey = 'products/' . $prefix . '_' . date('YmdHis') . '_' . $uniq . '.' . $ext;

        $put = stc_r2_sigv4_put_object($cfg, $objectKey, $absoluteLocalPath);
        if (!$put['ok']) {
            return ['ok' => false, 'error' => $put['error'] ?? ('Upload failed HTTP ' . ($put['http'] ?? 0))];
        }

        $publicUrl = stc_r2_public_url_from_object_key($cfg['public_base'], $objectKey);

        return ['ok' => true, 'public_url' => $publicUrl, 'object_key' => $objectKey];
    }
}

if (!function_exists('stc_r2_upload_safety_image_from_path')) {
    /**
     * Upload TBM / near-miss / CAPA images to R2 (same bucket as products).
     *
     * Object keys align with superadmin migrate prefixes: tbm/, nearmiss/, capa/.
     *
     * @param string $folderOneOf One of: tbm, nearmiss, capa, dso
     * @param int    $entityId    Row id for key naming (0 → literal "subagent")
     * @param string $tag         Optional key segment, e.g. before | after for CAPA photos
     *
     * @return array{ok:bool, public_url?:string, object_key?:string, error?:string}
     */
    function stc_r2_upload_safety_image_from_path(string $absoluteLocalPath, string $folderOneOf, int $entityId = 0, string $tag = ''): array
    {
        $allowed = ['tbm', 'nearmiss', 'capa', 'dso'];
        if (!in_array($folderOneOf, $allowed, true)) {
            return ['ok' => false, 'error' => 'Invalid safety folder (use tbm, nearmiss, capa, or dso).'];
        }

        $cfg = stc_mcu_r2_config();
        if ($cfg === null) {
            return ['ok' => false, 'error' => 'R2 is not configured (missing R2_* in environment or superadmin .env).'];
        }

        $ext = strtolower(pathinfo($absoluteLocalPath, PATHINFO_EXTENSION));
        if ($ext === '') {
            $ext = 'jpg';
        }
        if (!preg_match('/^[a-z0-9]{1,8}$/', $ext)) {
            $ext = 'jpg';
        }

        $tag = preg_replace('/[^a-z0-9_-]/i', '', $tag);
        $uniq = substr(str_replace('.', '', uniqid('', true)), 0, 24);
        $baseId = $entityId > 0 ? (string) $entityId : 'subagent';
        $slug = $baseId . ($tag !== '' ? '_' . $tag : '');
        $objectKey = $folderOneOf . '/' . $slug . '_' . date('YmdHis') . '_' . $uniq . '.' . $ext;

        $put = stc_r2_sigv4_put_object($cfg, $objectKey, $absoluteLocalPath);
        if (!$put['ok']) {
            return ['ok' => false, 'error' => $put['error'] ?? ('Upload failed HTTP ' . ($put['http'] ?? 0))];
        }

        $publicUrl = stc_r2_public_url_from_object_key($cfg['public_base'], $objectKey);

        return ['ok' => true, 'public_url' => $publicUrl, 'object_key' => $objectKey];
    }
}
