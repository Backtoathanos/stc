<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MediaImagesController extends Controller
{
    private function cloudDisk(): string
    {
        return (string) env('CLOUD_UPLOAD_DISK', env('FILESYSTEM_CLOUD', 's3'));
    }

    private function isRemoteUrl(string $value): bool
    {
        return (bool) preg_match('#^https?://#i', trim($value));
    }

    private function cloudUploadConfigured(): bool
    {
        $disk = $this->cloudDisk();
        $cfg = config('filesystems.disks.' . $disk);

        return is_array($cfg)
            && ($cfg['driver'] ?? '') === 's3'
            && ! empty($cfg['key'])
            && ! empty($cfg['secret'])
            && ! empty($cfg['bucket']);
    }

    private function flysystemS3AdapterAvailable(): bool
    {
        return class_exists(\League\Flysystem\AwsS3v3\AwsS3Adapter::class);
    }

    /** Credentials set + Composer packages present (league/flysystem-aws-s3-v3). */
    private function cloudMigrateReady(): bool
    {
        return $this->cloudUploadConfigured() && $this->flysystemS3AdapterAvailable();
    }

    private function publicBaseUrlForDisk(): ?string
    {
        $disk = $this->cloudDisk();
        $url = config('filesystems.disks.' . $disk . '.url');

        return $url ? rtrim((string) $url, '/') : null;
    }

    private function defaultLocalProductImageDir(): string
    {
        $override = env('LOCAL_PRODUCT_IMAGE_DIR');
        if (is_string($override) && $override !== '') {
            return rtrim($override, "\\/");
        }

        return dirname(base_path()) . DIRECTORY_SEPARATOR . 'stc_symbiote' . DIRECTORY_SEPARATOR . 'stc_product_image';
    }

    private function defaultLocalTbmImageDir(): string
    {
        $override = env('LOCAL_TBM_IMAGE_DIR');
        if (is_string($override) && $override !== '') {
            return rtrim($override, "\\/");
        }

        return dirname(base_path()) . DIRECTORY_SEPARATOR . 'stc_sub_agent47' . DIRECTORY_SEPARATOR . 'safety_img';
    }

    private function productImageBaseUrl(): string
    {
        return rtrim((string) env('STC_PRODUCT_IMAGE_URL', 'https://stcassociate.com/stc_symbiote/stc_product_image'), '/');
    }

    private function tbmImageBaseUrl(): string
    {
        return rtrim((string) env('STC_TBM_IMAGE_URL', 'https://stcassociate.com/stc_sub_agent47/safety_img'), '/');
    }

    private function tbmPrintUrl(int $tbmId): string
    {
        $base = rtrim((string) env('STC_TBM_PRINT_BASE', 'https://stcassociate.com/stc_agent47/safety-tbm-print-preview.php'), '/');

        return $base . '?tbm_no=' . $tbmId;
    }

    /**
     * @return array{0: string|null, 1: bool} [path, isTemporary]
     */
    private function resolveReadableProductFile(string $filename): array
    {
        $base = basename(str_replace('\\', '/', $filename));
        if ($base === '' || $base === '.' || $base === '..') {
            return [null, false];
        }

        $dir = $this->defaultLocalProductImageDir();
        $local = $dir . DIRECTORY_SEPARATOR . $base;
        if (is_readable($local)) {
            return [$local, false];
        }

        if (! filter_var(env('CLOUD_FETCH_IF_LOCAL_MISSING', false), FILTER_VALIDATE_BOOLEAN)) {
            return [null, false];
        }

        $url = $this->productImageBaseUrl() . '/' . rawurlencode($base);
        $tmp = tempnam(sys_get_temp_dir(), 'stc_pd_');
        if ($tmp === false) {
            return [null, false];
        }

        $data = @file_get_contents($url);
        if ($data === false || $data === '') {
            @unlink($tmp);

            return [null, false];
        }

        if (file_put_contents($tmp, $data) === false) {
            @unlink($tmp);

            return [null, false];
        }

        return [$tmp, true];
    }

    /**
     * @return array{0: string|null, 1: bool} [path, isTemporary]
     */
    private function resolveReadableTbmFile(string $filename): array
    {
        $base = basename(str_replace('\\', '/', $filename));
        if ($base === '' || $base === '.' || $base === '..') {
            return [null, false];
        }

        $dir = $this->defaultLocalTbmImageDir();
        $local = $dir . DIRECTORY_SEPARATOR . $base;
        if (is_readable($local)) {
            return [$local, false];
        }

        if (! filter_var(env('CLOUD_FETCH_IF_LOCAL_MISSING', false), FILTER_VALIDATE_BOOLEAN)) {
            return [null, false];
        }

        $url = $this->tbmImageBaseUrl() . '/' . rawurlencode($base);
        $tmp = tempnam(sys_get_temp_dir(), 'stc_tbm_');
        if ($tmp === false) {
            return [null, false];
        }

        $data = @file_get_contents($url);
        if ($data === false || $data === '') {
            @unlink($tmp);

            return [null, false];
        }

        if (file_put_contents($tmp, $data) === false) {
            @unlink($tmp);

            return [null, false];
        }

        return [$tmp, true];
    }

    private function deleteLocalUnderRoot(string $absolutePath, string $rootDir): void
    {
        if (! filter_var(env('CLOUD_DELETE_LOCAL_AFTER_UPLOAD', false), FILTER_VALIDATE_BOOLEAN)) {
            return;
        }

        $root = realpath($rootDir);
        $file = realpath($absolutePath);
        if ($root === false || $file === false || ! is_file($file)) {
            return;
        }

        $root = rtrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $root), DIRECTORY_SEPARATOR);
        $fileNorm = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $file);
        if ($fileNorm === $root || strpos($fileNorm, $root . DIRECTORY_SEPARATOR) !== 0) {
            return;
        }

        @unlink($file);
    }

    private function remoteObjectPublicUrl(string $objectKey): ?string
    {
        $base = $this->publicBaseUrlForDisk();
        if (! $base) {
            return null;
        }

        $segments = array_map('rawurlencode', explode('/', str_replace('\\', '/', $objectKey)));

        return $base . '/' . implode('/', $segments);
    }

    /** Max items per request for product image uploads. Env CLOUD_MIGRATE_BATCH_MAX (default 100, max 500). */
    private function cloudMigrateProductBatchMax(): int
    {
        return max(1, min(500, (int) env('CLOUD_MIGRATE_BATCH_MAX', 100)));
    }

    /** Max items per request for TBM image uploads. */
    private function cloudMigrateTbmBatchMax(): int
    {
        return max(1, min(500, (int) env('CLOUD_MIGRATE_BATCH_MAX_TBM', 500)));
    }

    /** When local/TBM file is missing, clear DB reference instead of leaving a broken filename. */
    private function clearImageColumnWhenFileMissing(): bool
    {
        return filter_var(env('CLOUD_CLEAR_IMAGE_ON_MISSING_FILE', true), FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Upload raw bytes to cloud and set stc_product_image to the public URL.
     *
     * @return array{success:bool, message:string, url?:string}
     */
    private function pushProductBytesToCloudAndSaveUrl(int $productId, string $bytes, string $safeExt): array
    {
        $uniq = str_replace('.', '', uniqid('', true));
        $objectKey = 'products/' . $productId . '_' . date('YmdHis') . '_' . $uniq . '.' . $safeExt;

        try {
            $disk = $this->cloudDisk();
            $put = Storage::disk($disk)->put($objectKey, $bytes, 'private');
            if (! $put) {
                return ['success' => false, 'message' => 'Upload to cloud failed.'];
            }

            $publicUrl = $this->remoteObjectPublicUrl($objectKey);
            if (! $publicUrl) {
                return ['success' => false, 'message' => 'Could not build public URL. Set R2_PUBLIC_URL.'];
            }

            DB::table('stc_product')->where('stc_product_id', $productId)->update(['stc_product_image' => $publicUrl]);

            return ['success' => true, 'message' => 'Uploaded and database updated.', 'url' => $publicUrl];
        } catch (\Throwable $e) {
            report($e);

            return ['success' => false, 'message' => 'Cloud error: ' . $e->getMessage()];
        }
    }

    /**
     * Apply Images page filters (Products): image_kind all|local|cloud|empty_only, hide_empty 0|1.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     */
    private function applyProductImageListFilters($query, Request $request): void
    {
        $kind = (string) $request->get('image_kind', 'all');
        if (! in_array($kind, ['all', 'local', 'cloud', 'empty_only'], true)) {
            $kind = 'all';
        }
        $hideEmpty = $request->get('hide_empty', '0') === '1';

        if ($kind === 'empty_only') {
            $query->whereRaw("(TRIM(COALESCE(stc_product_image, '')) = '')");

            return;
        }

        if ($kind === 'local') {
            $query->whereRaw("(TRIM(COALESCE(stc_product_image, '')) <> '' AND LOWER(TRIM(stc_product_image)) NOT LIKE 'http://%' AND LOWER(TRIM(stc_product_image)) NOT LIKE 'https://%')");
        } elseif ($kind === 'cloud') {
            $query->whereRaw("(LOWER(TRIM(stc_product_image)) LIKE 'http://%' OR LOWER(TRIM(stc_product_image)) LIKE 'https://%')");
        }

        if ($hideEmpty) {
            $query->whereRaw("(TRIM(COALESCE(stc_product_image, '')) <> '')");
        }
    }

    /**
     * @param  \Illuminate\Database\Query\Builder  $query
     */
    private function applyTbmImageListFilters($query, Request $request): void
    {
        $kind = (string) $request->get('image_kind', 'all');
        if (! in_array($kind, ['all', 'local', 'cloud', 'empty_only'], true)) {
            $kind = 'all';
        }
        $hideEmpty = $request->get('hide_empty', '0') === '1';
        $col = 'IMG.stc_safetytbm_img_location';

        if ($kind === 'empty_only') {
            $query->whereRaw("(TRIM(COALESCE({$col}, '')) = '')");

            return;
        }

        if ($kind === 'local') {
            $query->whereRaw("(TRIM(COALESCE({$col}, '')) <> '' AND LOWER(TRIM({$col})) NOT LIKE 'http://%' AND LOWER(TRIM({$col})) NOT LIKE 'https://%')");
        } elseif ($kind === 'cloud') {
            $query->whereRaw("(LOWER(TRIM({$col})) LIKE 'http://%' OR LOWER(TRIM({$col})) LIKE 'https://%')");
        }

        if ($hideEmpty) {
            $query->whereRaw("(TRIM(COALESCE({$col}, '')) <> '')");
        }
    }

    /** Microseconds to sleep between items in a batch (shared hosting I/O friendly). 0 = no delay. */
    private function cloudMigrateBatchUsleep(): int
    {
        return max(0, (int) env('CLOUD_MIGRATE_BATCH_USLEEP_MICROSECONDS', 50000));
    }

    /**
     * JSON error payload or null if cloud migrate may proceed.
     *
     * @return array<string, mixed>|null
     */
    private function cloudMigrateRejectIfNotReady(): ?array
    {
        if (! $this->cloudUploadConfigured()) {
            return ['success' => false, 'message' => 'Cloud storage is not configured. Set R2_* variables in .env (see Images page help text).'];
        }

        if (! $this->flysystemS3AdapterAvailable()) {
            return ['success' => false, 'message' => 'S3/R2 PHP libraries are missing on this server. From the project root run: composer install --no-dev (installs league/flysystem-aws-s3-v3 and aws/aws-sdk-php). Deploy the full vendor folder if Composer is not available on the host.'];
        }

        if (! $this->publicBaseUrlForDisk()) {
            return ['success' => false, 'message' => 'Set R2_PUBLIC_URL in .env to your public bucket URL (required to store full image address in the database).'];
        }

        return null;
    }

    /**
     * @return array{success:bool, message:string, url?:string}
     */
    private function migrateSingleProductInternal(int $id): array
    {
        $row = DB::table('stc_product')->where('stc_product_id', $id)->first(['stc_product_id', 'stc_product_image']);
        if (! $row) {
            return ['success' => false, 'message' => 'Product not found.'];
        }

        $current = trim((string) ($row->stc_product_image ?? ''));
        if ($current === '') {
            return ['success' => false, 'message' => 'No image filename stored for this product.'];
        }

        if ($this->isRemoteUrl($current)) {
            return ['success' => false, 'message' => 'This row already points to a cloud URL.', 'url' => $current];
        }

        [$path, $isTmp] = $this->resolveReadableProductFile($current);
        if (! $path) {
            if ($this->clearImageColumnWhenFileMissing()) {
                DB::table('stc_product')->where('stc_product_id', $id)->update(['stc_product_image' => '']);

                return ['success' => true, 'message' => 'Image missing from disk (and fetch disabled or failed). Cleared stc_product_image. Re-upload a file from the Images page if needed.'];
            }

            return ['success' => false, 'message' => 'Image file not found on disk and could not be downloaded. Check LOCAL_PRODUCT_IMAGE_DIR or enable CLOUD_FETCH_IF_LOCAL_MISSING. Set CLOUD_CLEAR_IMAGE_ON_MISSING_FILE=true (default) to clear the column automatically.'];
        }

        $bytes = file_get_contents($path);
        if ($bytes === false) {
            return ['success' => false, 'message' => 'Could not read image bytes.'];
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $safeExt = preg_match('/^[a-z0-9]{1,8}$/', $ext) ? $ext : 'bin';

        $payload = $this->pushProductBytesToCloudAndSaveUrl($id, $bytes, $safeExt);

        if ($payload['success']) {
            if ($isTmp) {
                @unlink($path);
            } else {
                $this->deleteLocalUnderRoot($path, $this->defaultLocalProductImageDir());
            }
        }

        return $payload;
    }

    /**
     * @return array{success:bool, message:string, url?:string}
     */
    private function migrateSingleTbmInternal(int $tbmId, string $stored): array
    {
        $stored = trim($stored);
        if ($this->isRemoteUrl($stored)) {
            return ['success' => false, 'message' => 'This row already points to a cloud URL.', 'url' => $stored];
        }

        $imgRow = DB::table('stc_safetytbm_img')
            ->where('stc_safetytbm_img_tbmid', $tbmId)
            ->where('stc_safetytbm_img_location', $stored)
            ->first(['stc_safetytbm_img_tbmid', 'stc_safetytbm_img_location']);

        if (! $imgRow) {
            return ['success' => false, 'message' => 'TBM image record not found. Refresh the table and try again.'];
        }

        [$path, $isTmp] = $this->resolveReadableTbmFile($stored);
        if (! $path) {
            if ($this->clearImageColumnWhenFileMissing()) {
                DB::table('stc_safetytbm_img')
                    ->where('stc_safetytbm_img_tbmid', $tbmId)
                    ->where('stc_safetytbm_img_location', $stored)
                    ->update(['stc_safetytbm_img_location' => '']);

                return ['success' => true, 'message' => 'Image missing from disk (and fetch disabled or failed). Cleared this TBM image reference.'];
            }

            return ['success' => false, 'message' => 'Image file not found on disk and could not be downloaded. Check LOCAL_TBM_IMAGE_DIR or enable CLOUD_FETCH_IF_LOCAL_MISSING.'];
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $safeExt = preg_match('/^[a-z0-9]{1,8}$/', $ext) ? $ext : 'bin';
        $uniq = str_replace('.', '', uniqid('', true));
        $objectKey = 'tbm/' . $tbmId . '_' . date('YmdHis') . '_' . $uniq . '.' . $safeExt;

        try {
            $disk = $this->cloudDisk();
            $bytes = file_get_contents($path);
            if ($bytes === false) {
                return ['success' => false, 'message' => 'Could not read image bytes.'];
            }

            $put = Storage::disk($disk)->put($objectKey, $bytes, 'private');
            if (! $put) {
                return ['success' => false, 'message' => 'Upload to cloud failed.'];
            }

            $publicUrl = $this->remoteObjectPublicUrl($objectKey);
            if (! $publicUrl) {
                return ['success' => false, 'message' => 'Could not build public URL. Set R2_PUBLIC_URL.'];
            }

            DB::table('stc_safetytbm_img')
                ->where('stc_safetytbm_img_tbmid', $tbmId)
                ->where('stc_safetytbm_img_location', $stored)
                ->update(['stc_safetytbm_img_location' => $publicUrl]);

            if ($isTmp) {
                @unlink($path);
            } else {
                $this->deleteLocalUnderRoot($path, $this->defaultLocalTbmImageDir());
            }

            return ['success' => true, 'message' => 'Uploaded and database updated.', 'url' => $publicUrl];
        } catch (\Throwable $e) {
            report($e);

            return ['success' => false, 'message' => 'Cloud error: ' . $e->getMessage()];
        }
    }

    public function show()
    {
        $data['page_title'] = 'Images';
        $data['cloud_upload_ready'] = $this->cloudMigrateReady();
        $data['cloud_s3_adapter_missing'] = $this->cloudUploadConfigured() && ! $this->flysystemS3AdapterAvailable();
        $data['cloud_migrate_batch_max'] = $this->cloudMigrateProductBatchMax();
        $data['cloud_migrate_tbm_batch_max'] = $this->cloudMigrateTbmBatchMax();

        return view('pages.images', $data);
    }

    public function migrateProductToCloud(Request $request)
    {
        $reject = $this->cloudMigrateRejectIfNotReady();
        if ($reject !== null) {
            return response()->json($reject);
        }

        $request->validate([
            'product_id' => 'required|integer|min:1',
        ]);

        $payload = $this->migrateSingleProductInternal((int) $request->input('product_id'));

        return response()->json($payload);
    }

    public function migrateProductsToCloudBatch(Request $request)
    {
        $reject = $this->cloudMigrateRejectIfNotReady();
        if ($reject !== null) {
            return response()->json($reject);
        }

        $max = $this->cloudMigrateProductBatchMax();
        $request->validate([
            'product_ids' => 'required|array|min:1|max:' . $max,
            'product_ids.*' => 'integer|min:1',
        ]);

        $ids = array_values(array_unique(array_map('intval', $request->input('product_ids'))));
        if (count($ids) > $max) {
            return response()->json([
                'success' => false,
                'message' => 'Too many product IDs (maximum ' . $max . ' per request). Select fewer rows or set CLOUD_MIGRATE_BATCH_MAX in .env (up to 500).',
            ]);
        }

        @ini_set('max_execution_time', (string) max(120, (int) env('CLOUD_MIGRATE_BATCH_TIME_LIMIT', 300)));
        @set_time_limit(max(120, (int) env('CLOUD_MIGRATE_BATCH_TIME_LIMIT', 300)));

        $usleep = $this->cloudMigrateBatchUsleep();
        $results = [];
        $ok = 0;
        $fail = 0;
        $n = count($ids);
        foreach ($ids as $i => $id) {
            $r = $this->migrateSingleProductInternal($id);
            $results[] = [
                'product_id' => $id,
                'success' => $r['success'],
                'message' => $r['message'],
            ];
            if ($r['success']) {
                $ok++;
            } else {
                $fail++;
            }
            if ($i < $n - 1 && $usleep > 0) {
                usleep($usleep);
            }
        }

        return response()->json([
            'success' => $fail === 0,
            'batch_complete' => true,
            'total' => $n,
            'ok' => $ok,
            'failed' => $fail,
            'results' => $results,
            'message' => $ok . ' uploaded, ' . $fail . ' failed.',
        ]);
    }

    /**
     * Multipart upload: pair each checked product ID with one chosen image file (sorted IDs × files sorted by name).
     */
    public function uploadProductsDirectCloud(Request $request)
    {
        $reject = $this->cloudMigrateRejectIfNotReady();
        if ($reject !== null) {
            return response()->json($reject);
        }

        $max = $this->cloudMigrateProductBatchMax();
        $request->validate([
            'product_ids' => 'required|array|min:1|max:' . $max,
            'product_ids.*' => 'integer|min:1',
            'files' => 'required|array|min:1|max:' . $max,
            'files.*' => 'required|image|max:15360',
        ]);

        $ids = array_map('intval', $request->input('product_ids'));
        $files = $request->file('files');
        if (! is_array($files)) {
            return response()->json(['success' => false, 'message' => 'No files received.']);
        }

        if (count($ids) !== count($files)) {
            return response()->json([
                'success' => false,
                'message' => 'Product count and file count must match (got ' . count($ids) . ' products and ' . count($files) . ' files).',
            ]);
        }

        if (count($ids) !== count(array_unique($ids))) {
            return response()->json(['success' => false, 'message' => 'Duplicate product IDs are not allowed.']);
        }

        @ini_set('max_execution_time', (string) max(120, (int) env('CLOUD_MIGRATE_BATCH_TIME_LIMIT', 300)));
        @set_time_limit(max(120, (int) env('CLOUD_MIGRATE_BATCH_TIME_LIMIT', 300)));

        $usleep = $this->cloudMigrateBatchUsleep();
        $results = [];
        $ok = 0;
        $fail = 0;
        $n = count($ids);

        foreach ($ids as $i => $pid) {
            /** @var \Illuminate\Http\UploadedFile|null $file */
            $file = $files[$i];
            if (! $file || ! $file->isValid()) {
                $results[] = ['product_id' => $pid, 'success' => false, 'message' => 'Invalid upload for this row.'];
                $fail++;
                if ($i < $n - 1 && $usleep > 0) {
                    usleep($usleep);
                }

                continue;
            }

            if (! DB::table('stc_product')->where('stc_product_id', $pid)->exists()) {
                $results[] = ['product_id' => $pid, 'success' => false, 'message' => 'Product not found.'];
                $fail++;
                if ($i < $n - 1 && $usleep > 0) {
                    usleep($usleep);
                }

                continue;
            }

            $real = $file->getRealPath();
            $bytes = $real ? @file_get_contents($real) : false;
            if ($bytes === false || $bytes === '') {
                $results[] = ['product_id' => $pid, 'success' => false, 'message' => 'Could not read uploaded file.'];
                $fail++;
                if ($i < $n - 1 && $usleep > 0) {
                    usleep($usleep);
                }

                continue;
            }

            $ext = strtolower((string) $file->getClientOriginalExtension());
            if ($ext === '' && method_exists($file, 'guessExtension')) {
                $ext = strtolower((string) $file->guessExtension());
            }
            $safeExt = preg_match('/^[a-z0-9]{1,8}$/', $ext) ? $ext : 'jpg';

            $r = $this->pushProductBytesToCloudAndSaveUrl($pid, $bytes, $safeExt);
            $results[] = [
                'product_id' => $pid,
                'success' => $r['success'],
                'message' => $r['message'],
            ];
            if ($r['success']) {
                $ok++;
            } else {
                $fail++;
            }

            if ($i < $n - 1 && $usleep > 0) {
                usleep($usleep);
            }
        }

        return response()->json([
            'success' => $fail === 0,
            'batch_complete' => true,
            'total' => $n,
            'ok' => $ok,
            'failed' => $fail,
            'results' => $results,
            'message' => $ok . ' uploaded, ' . $fail . ' failed.',
        ]);
    }

    public function migrateTbmImageToCloud(Request $request)
    {
        $reject = $this->cloudMigrateRejectIfNotReady();
        if ($reject !== null) {
            return response()->json($reject);
        }

        $request->validate([
            'tbm_id' => 'required|integer|min:1',
            'img_location' => 'required|string|max:512',
        ]);

        $payload = $this->migrateSingleTbmInternal((int) $request->input('tbm_id'), (string) $request->input('img_location'));

        return response()->json($payload);
    }

    public function migrateTbmImagesToCloudBatch(Request $request)
    {
        $reject = $this->cloudMigrateRejectIfNotReady();
        if ($reject !== null) {
            return response()->json($reject);
        }

        $max = $this->cloudMigrateTbmBatchMax();
        $request->validate([
            'tbm_items' => 'required|array|min:1|max:' . $max,
            'tbm_items.*.tbm_id' => 'required|integer|min:1',
            'tbm_items.*.img_location' => 'required|string|max:512',
        ]);

        $items = $request->input('tbm_items');
        if (count($items) > $max) {
            return response()->json([
                'success' => false,
                'message' => 'Too many TBM rows (maximum ' . $max . ' per request). Increase CLOUD_MIGRATE_BATCH_MAX_TBM in .env (max 500).',
            ]);
        }

        @ini_set('max_execution_time', (string) max(120, (int) env('CLOUD_MIGRATE_BATCH_TIME_LIMIT', 300)));
        @set_time_limit(max(120, (int) env('CLOUD_MIGRATE_BATCH_TIME_LIMIT', 300)));

        $usleep = $this->cloudMigrateBatchUsleep();
        $results = [];
        $ok = 0;
        $fail = 0;
        $n = count($items);
        foreach ($items as $i => $row) {
            $tid = (int) ($row['tbm_id'] ?? 0);
            $loc = trim((string) ($row['img_location'] ?? ''));
            $r = $this->migrateSingleTbmInternal($tid, $loc);
            $results[] = [
                'tbm_id' => $tid,
                'success' => $r['success'],
                'message' => $r['message'],
            ];
            if ($r['success']) {
                $ok++;
            } else {
                $fail++;
            }
            if ($i < $n - 1 && $usleep > 0) {
                usleep($usleep);
            }
        }

        return response()->json([
            'success' => $fail === 0,
            'batch_complete' => true,
            'total' => $n,
            'ok' => $ok,
            'failed' => $fail,
            'results' => $results,
            'message' => $ok . ' uploaded, ' . $fail . ' failed.',
        ]);
    }

    public function productsList(Request $request)
    {
        $draw = (int) $request->get('draw');
        $start = (int) $request->get('start');
        $rowperpage = (int) $request->get('length');

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column'] ?? 0;
        $columnDataKey = $columnName_arr[$columnIndex]['data'] ?? 'product_id';
        $columnSortOrder = $order_arr[0]['dir'] ?? 'desc';
        $searchValue = $search_arr['value'] ?? '';

        $columnMap = [
            'bulk_select' => 'stc_product_id',
            'product_id' => 'stc_product_id',
            'product_name' => 'stc_product_name',
            'image_name' => 'stc_product_image',
        ];
        $orderColumn = $columnMap[$columnDataKey] ?? 'stc_product_id';
        if (! in_array($columnSortOrder, ['asc', 'desc'], true)) {
            $columnSortOrder = 'desc';
        }

        $totalRecords = DB::table('stc_product')->count();

        $query = DB::table('stc_product')
            ->select('stc_product_id', 'stc_product_name', 'stc_product_image');

        if ($searchValue !== '') {
            $like = '%' . $searchValue . '%';
            $query->where(function ($q) use ($like) {
                $q->where('stc_product_name', 'like', $like)
                    ->orWhere('stc_product_id', 'like', $like)
                    ->orWhere('stc_product_image', 'like', $like);
            });
        }

        $this->applyProductImageListFilters($query, $request);

        $totalRecordswithFilter = (clone $query)->count();

        $records = $query
            ->orderBy($orderColumn, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $base = $this->productImageBaseUrl();
        $cloudReady = $this->cloudMigrateReady();
        $data_arr = [];
        foreach ($records as $row) {
            $id = (int) $row->stc_product_id;
            $img = trim((string) ($row->stc_product_image ?? ''));
            $canBulkPick = $cloudReady;
            $canMigrateRow = $cloudReady && $img !== '' && ! $this->isRemoteUrl($img);

            $bulkSelect = '<span class="text-muted">—</span>';
            if ($canBulkPick) {
                $attrs = 'data-product-id="' . $id . '"';
                if ($img !== '' && $this->isRemoteUrl($img)) {
                    $attrs .= ' data-image-remote="1"';
                }
                $bulkSelect = '<input type="checkbox" class="js-product-row-select align-middle" ' . $attrs . ' title="Select for bulk upload">';
            }

            $action = '';
            if ($img !== '') {
                $href = $this->isRemoteUrl($img) ? $img : ($base . '/' . rawurlencode(basename(str_replace('\\', '/', $img))));
                $action = '<a href="' . e($href) . '" target="_blank" rel="noopener" class="btn btn-sm btn-info" title="Open image"><i class="fas fa-external-link-alt"></i></a>';
                if ($canMigrateRow) {
                    $action .= ' <button type="button" class="btn btn-sm btn-warning js-migrate-product-cloud" data-product-id="' . $id . '" title="Upload to Cloudflare R2"><i class="fas fa-cloud-upload-alt"></i></button>';
                }
            } else {
                $action = '<span class="text-muted">—</span>';
            }

            $data_arr[] = [
                'bulk_select' => $bulkSelect,
                'product_id' => '<span class="text-center d-block">' . $id . '</span>',
                'product_name' => e((string) $row->stc_product_name),
                'image_name' => $img !== '' ? e($img) : '<span class="text-muted">—</span>',
                'actionData' => $action,
            ];
        }

        return response()->json([
            'draw' => $draw,
            'iTotalRecords' => $totalRecords,
            'iTotalDisplayRecords' => $totalRecordswithFilter,
            'aaData' => $data_arr,
        ]);
    }

    public function tbmList(Request $request)
    {
        $draw = (int) $request->get('draw');
        $start = (int) $request->get('start');
        $rowperpage = (int) $request->get('length');

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column'] ?? 0;
        $columnDataKey = $columnName_arr[$columnIndex]['data'] ?? 'tbm_id';
        $columnSortOrder = $order_arr[0]['dir'] ?? 'desc';
        $searchValue = $search_arr['value'] ?? '';

        $columnMap = [
            'bulk_select' => 'T.stc_safetytbm_id',
            'tbm_id' => 'T.stc_safetytbm_id',
            'tbm_location' => 'tbm_location_sort',
            'img_name' => 'img_file',
        ];
        $orderColumn = $columnMap[$columnDataKey] ?? 'T.stc_safetytbm_id';
        if (! in_array($columnSortOrder, ['asc', 'desc'], true)) {
            $columnSortOrder = 'desc';
        }

        $locationExpr = DB::raw("TRIM(BOTH ' ' FROM COALESCE(NULLIF(T.stc_safetytbm_loc, ''), P.stc_cust_project_title, T.stc_safetytbm_place, '')) AS tbm_location_sort");

        $baseQuery = DB::table('stc_safetytbm_img as IMG')
            ->join('stc_safetytbm as T', 'IMG.stc_safetytbm_img_tbmid', '=', 'T.stc_safetytbm_id')
            ->leftJoin('stc_cust_project as P', 'T.stc_safetytbm_loc_id', '=', 'P.stc_cust_project_id')
            ->select(
                'T.stc_safetytbm_id',
                $locationExpr,
                'IMG.stc_safetytbm_img_location as img_file'
            );

        $totalRecords = DB::table('stc_safetytbm_img')->count();

        $query = clone $baseQuery;
        if ($searchValue !== '') {
            $like = '%' . $searchValue . '%';
            $query->where(function ($q) use ($like) {
                $q->where('T.stc_safetytbm_id', 'like', $like)
                    ->orWhere('T.stc_safetytbm_loc', 'like', $like)
                    ->orWhere('T.stc_safetytbm_place', 'like', $like)
                    ->orWhere('P.stc_cust_project_title', 'like', $like)
                    ->orWhere('IMG.stc_safetytbm_img_location', 'like', $like);
            });
        }

        $this->applyTbmImageListFilters($query, $request);

        $totalRecordswithFilter = (clone $query)->count();

        $records = $query
            ->orderBy($orderColumn, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $imgBase = $this->tbmImageBaseUrl();
        $cloudReady = $this->cloudMigrateReady();
        $data_arr = [];
        foreach ($records as $row) {
            $id = (int) $row->stc_safetytbm_id;
            $loc = trim((string) ($row->tbm_location_sort ?? ''));
            $imgFile = trim((string) ($row->img_file ?? ''));

            $imgCell = $imgFile !== '' ? e($imgFile) : '<span class="text-muted">—</span>';

            $btns = '';
            $bulkSelect = '<span class="text-muted">—</span>';
            $btns .= '<a href="' . e($this->tbmPrintUrl($id)) . '" target="_blank" rel="noopener" class="btn btn-sm btn-success mr-1" title="TBM print preview"><i class="fas fa-file-alt"></i></a>';
            if ($imgFile !== '') {
                $href = $this->isRemoteUrl($imgFile)
                    ? $imgFile
                    : ($imgBase . '/' . rawurlencode(basename(str_replace('\\', '/', $imgFile))));
                $btns .= '<a href="' . e($href) . '" target="_blank" rel="noopener" class="btn btn-sm btn-info mr-1" title="Open image"><i class="fas fa-image"></i></a>';
                if ($cloudReady && ! $this->isRemoteUrl($imgFile)) {
                    $bulkSelect = '<input type="checkbox" class="js-tbm-row-select align-middle" data-tbm-id="' . $id . '" data-img-location="' . e($imgFile) . '" title="Select for bulk upload">';
                    $btns .= '<button type="button" class="btn btn-sm btn-warning js-migrate-tbm-cloud" data-tbm-id="' . $id . '" data-img-location="' . e($imgFile) . '" title="Upload to Cloudflare R2"><i class="fas fa-cloud-upload-alt"></i></button>';
                }
            }

            $data_arr[] = [
                'bulk_select' => $bulkSelect,
                'tbm_id' => '<span class="text-center d-block">' . $id . '</span>',
                'tbm_location' => $loc !== '' ? e($loc) : '<span class="text-muted">—</span>',
                'img_name' => $imgCell,
                'actionData' => $btns !== '' ? $btns : '<span class="text-muted">—</span>',
            ];
        }

        return response()->json([
            'draw' => $draw,
            'iTotalRecords' => $totalRecords,
            'iTotalDisplayRecords' => $totalRecordswithFilter,
            'aaData' => $data_arr,
        ]);
    }
}
