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

    public function show()
    {
        $data['page_title'] = 'Images';
        $data['cloud_upload_ready'] = $this->cloudMigrateReady();
        $data['cloud_s3_adapter_missing'] = $this->cloudUploadConfigured() && ! $this->flysystemS3AdapterAvailable();

        return view('pages.images', $data);
    }

    public function migrateProductToCloud(Request $request)
    {
        if (! $this->cloudUploadConfigured()) {
            return response()->json(['success' => false, 'message' => 'Cloud storage is not configured. Set R2_* variables in .env (see Images page help text).']);
        }

        if (! $this->flysystemS3AdapterAvailable()) {
            return response()->json([
                'success' => false,
                'message' => 'S3/R2 PHP libraries are missing on this server. From the project root run: composer install --no-dev (installs league/flysystem-aws-s3-v3 and aws/aws-sdk-php). Deploy the full vendor folder if Composer is not available on the host.',
            ]);
        }

        $pub = $this->publicBaseUrlForDisk();
        if (! $pub) {
            return response()->json(['success' => false, 'message' => 'Set R2_PUBLIC_URL in .env to your public bucket URL (required to store full image address in the database).']);
        }

        $request->validate([
            'product_id' => 'required|integer|min:1',
        ]);

        $id = (int) $request->input('product_id');
        $row = DB::table('stc_product')->where('stc_product_id', $id)->first(['stc_product_id', 'stc_product_image']);
        if (! $row) {
            return response()->json(['success' => false, 'message' => 'Product not found.']);
        }

        $current = trim((string) ($row->stc_product_image ?? ''));
        if ($current === '') {
            return response()->json(['success' => false, 'message' => 'No image filename stored for this product.']);
        }

        if ($this->isRemoteUrl($current)) {
            return response()->json(['success' => false, 'message' => 'This row already points to a cloud URL.', 'url' => $current]);
        }

        [$path, $isTmp] = $this->resolveReadableProductFile($current);
        if (! $path) {
            return response()->json(['success' => false, 'message' => 'Image file not found on disk and could not be downloaded. Check LOCAL_PRODUCT_IMAGE_DIR or enable CLOUD_FETCH_IF_LOCAL_MISSING.']);
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $safeExt = preg_match('/^[a-z0-9]{1,8}$/', $ext) ? $ext : 'bin';
        $objectKey = 'products/' . $id . '_' . date('YmdHis') . '.' . $safeExt;

        try {
            $disk = $this->cloudDisk();
            $bytes = file_get_contents($path);
            if ($bytes === false) {
                return response()->json(['success' => false, 'message' => 'Could not read image bytes.']);
            }

            // R2 rejects PutObject with bucket-owner ACL headers; use private objects — bucket public URL still serves them.
            $put = Storage::disk($disk)->put($objectKey, $bytes, 'private');
            if (! $put) {
                return response()->json(['success' => false, 'message' => 'Upload to cloud failed.']);
            }

            $publicUrl = $this->remoteObjectPublicUrl($objectKey);
            if (! $publicUrl) {
                return response()->json(['success' => false, 'message' => 'Could not build public URL. Set R2_PUBLIC_URL.']);
            }

            DB::table('stc_product')->where('stc_product_id', $id)->update(['stc_product_image' => $publicUrl]);

            if ($isTmp) {
                @unlink($path);
            } else {
                $this->deleteLocalUnderRoot($path, $this->defaultLocalProductImageDir());
            }

            return response()->json(['success' => true, 'message' => 'Uploaded and database updated.', 'url' => $publicUrl]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Cloud error: ' . $e->getMessage(),
            ]);
        }
    }

    public function migrateTbmImageToCloud(Request $request)
    {
        if (! $this->cloudUploadConfigured()) {
            return response()->json(['success' => false, 'message' => 'Cloud storage is not configured. Set R2_* variables in .env (see Images page help text).']);
        }

        if (! $this->flysystemS3AdapterAvailable()) {
            return response()->json([
                'success' => false,
                'message' => 'S3/R2 PHP libraries are missing on this server. From the project root run: composer install --no-dev (installs league/flysystem-aws-s3-v3 and aws/aws-sdk-php). Deploy the full vendor folder if Composer is not available on the host.',
            ]);
        }

        $pub = $this->publicBaseUrlForDisk();
        if (! $pub) {
            return response()->json(['success' => false, 'message' => 'Set R2_PUBLIC_URL in .env to your public bucket URL (required to store full image address in the database).']);
        }

        $request->validate([
            'tbm_id' => 'required|integer|min:1',
            'img_location' => 'required|string|max:512',
        ]);

        $tbmId = (int) $request->input('tbm_id');
        $stored = trim((string) $request->input('img_location'));

        if ($this->isRemoteUrl($stored)) {
            return response()->json(['success' => false, 'message' => 'This row already points to a cloud URL.', 'url' => $stored]);
        }

        $imgRow = DB::table('stc_safetytbm_img')
            ->where('stc_safetytbm_img_tbmid', $tbmId)
            ->where('stc_safetytbm_img_location', $stored)
            ->first(['stc_safetytbm_img_tbmid', 'stc_safetytbm_img_location']);

        if (! $imgRow) {
            return response()->json(['success' => false, 'message' => 'TBM image record not found. Refresh the table and try again.']);
        }

        [$path, $isTmp] = $this->resolveReadableTbmFile($stored);
        if (! $path) {
            return response()->json(['success' => false, 'message' => 'Image file not found on disk and could not be downloaded. Check LOCAL_TBM_IMAGE_DIR or enable CLOUD_FETCH_IF_LOCAL_MISSING.']);
        }

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $safeExt = preg_match('/^[a-z0-9]{1,8}$/', $ext) ? $ext : 'bin';
        $objectKey = 'tbm/' . $tbmId . '/' . $tbmId . '_' . date('YmdHis') . '.' . $safeExt;

        try {
            $disk = $this->cloudDisk();
            $bytes = file_get_contents($path);
            if ($bytes === false) {
                return response()->json(['success' => false, 'message' => 'Could not read image bytes.']);
            }

            $put = Storage::disk($disk)->put($objectKey, $bytes, 'private');
            if (! $put) {
                return response()->json(['success' => false, 'message' => 'Upload to cloud failed.']);
            }

            $publicUrl = $this->remoteObjectPublicUrl($objectKey);
            if (! $publicUrl) {
                return response()->json(['success' => false, 'message' => 'Could not build public URL. Set R2_PUBLIC_URL.']);
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

            return response()->json(['success' => true, 'message' => 'Uploaded and database updated.', 'url' => $publicUrl]);
        } catch (\Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Cloud error: ' . $e->getMessage(),
            ]);
        }
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
            $action = '';
            $bulkSelect = '<span class="text-muted">—</span>';
            if ($img !== '') {
                $href = $this->isRemoteUrl($img) ? $img : ($base . '/' . rawurlencode(basename(str_replace('\\', '/', $img))));
                $action = '<a href="' . e($href) . '" target="_blank" rel="noopener" class="btn btn-sm btn-info" title="Open image"><i class="fas fa-external-link-alt"></i></a>';
                if ($cloudReady && ! $this->isRemoteUrl($img)) {
                    $bulkSelect = '<input type="checkbox" class="js-product-row-select align-middle" data-product-id="' . $id . '" title="Select for bulk upload">';
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
