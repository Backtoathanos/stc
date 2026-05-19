<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Product;

class ProductController extends Controller
{
    /**
     * Detect optional FK from sub_category -> category (if present in DB).
     */
    protected function subCategoryCategoryColumn(): ?string
    {
        foreach (['stc_sub_cat_cat_id', 'stc_cat_id'] as $col) {
            if (Schema::hasColumn('stc_sub_category', $col)) {
                return $col;
            }
        }

        return null;
    }

    /**
     * Detect optional FK from brand -> category (if present in DB).
     */
    protected function brandCategoryColumn(): ?string
    {
        if (Schema::hasColumn('stc_brand', 'stc_brand_cat_id')) {
            return 'stc_brand_cat_id';
        }
        if (Schema::hasColumn('stc_brand', 'stc_cat_id')) {
            return 'stc_cat_id';
        }

        return null;
    }

    public function show()
    {
        $data['page_title'] = 'Product';

        return view('pages.product', $data);
    }

    /**
     * Select2 AJAX: full category list from stc_category (search filters by name).
     */
    public function optionsCategories(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $builder = DB::table('stc_category')->orderBy('stc_cat_name');
        if ($q !== '') {
            $builder->where('stc_cat_name', 'like', '%' . $q . '%');
        }

        $rows = $builder->limit(1000)->get(['stc_cat_id', 'stc_cat_name']);

        return response()->json([
            'results' => $rows->map(function ($r) {
                return ['id' => (string) $r->stc_cat_id, 'text' => $r->stc_cat_name];
            })->values()->all(),
        ]);
    }

    /**
     * Select2 AJAX: full subcategory list from stc_sub_category (optional category_id filter when FK exists).
     */
    public function optionsSubcategories(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $categoryId = $request->get('category_id');
        $col = $this->subCategoryCategoryColumn();

        $builder = DB::table('stc_sub_category')->orderBy('stc_sub_cat_name');

        if ($col !== null && $categoryId !== null && $categoryId !== '') {
            $builder->where($col, $categoryId);
        }

        if ($q !== '') {
            $builder->where('stc_sub_cat_name', 'like', '%' . $q . '%');
        }

        $rows = $builder->limit(1000)->get(['stc_sub_cat_id', 'stc_sub_cat_name']);

        return response()->json([
            'results' => $rows->map(function ($r) {
                return ['id' => (string) $r->stc_sub_cat_id, 'text' => $r->stc_sub_cat_name];
            })->values()->all(),
        ]);
    }

    /**
     * Select2 AJAX: racks (stc_rack).
     */
    public function optionsRacks(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $builder = DB::table('stc_rack')->orderBy('stc_rack_name');

        if ($q !== '') {
            $builder->where('stc_rack_name', 'like', '%' . $q . '%');
        }

        $rows = $builder->limit(1000)->get(['stc_rack_id', 'stc_rack_name']);

        return response()->json([
            'results' => $rows->map(function ($r) {
                return ['id' => (string) $r->stc_rack_id, 'text' => $r->stc_rack_name];
            })->values()->all(),
        ]);
    }

    /**
     * Select2 AJAX: brands (optional category_id filter when FK exists).
     */
    public function optionsBrands(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $categoryId = $request->get('category_id');
        $col = $this->brandCategoryColumn();

        $builder = DB::table('stc_brand')->orderBy('stc_brand_title');

        if ($col !== null && $categoryId !== null && $categoryId !== '') {
            $builder->where($col, $categoryId);
        }

        if ($q !== '') {
            $builder->where('stc_brand_title', 'like', '%' . $q . '%');
        }

        $rows = $builder->limit(1000)->get(['stc_brand_id', 'stc_brand_title']);

        return response()->json([
            'results' => $rows->map(function ($r) {
                return ['id' => (string) $r->stc_brand_id, 'text' => $r->stc_brand_title];
            })->values()->all(),
        ]);
    }

    /**
     * Column keys sent by DataTables -> real ORDER BY expressions (with table aliases).
     */
    protected function orderColumnExpression(string $columnName): string
    {
        $map = [
            'stc_product_id' => 'stc_product.stc_product_id',
            'stc_product_name' => 'stc_product.stc_product_name',
            'stc_product_desc' => 'stc_product.stc_product_desc',
            'stc_product_cat_id' => 'stc_product.stc_product_cat_id',
            'stc_product_sub_cat_id' => 'stc_product.stc_product_sub_cat_id',
            'stc_product_rack_id' => 'stc_product.stc_product_rack_id',
            'stc_product_brand_id' => 'stc_product.stc_product_brand_id',
            'stc_product_unit' => 'stc_product.stc_product_unit',
            'stc_product_hsncode' => 'stc_product.stc_product_hsncode',
            'stc_product_gst' => 'stc_product.stc_product_gst',
            'stc_product_avail' => 'stc_product.stc_product_avail',
            'stc_product_image' => 'stc_product.stc_product_image',
            'stc_product_sale_percentage' => 'stc_product.stc_product_sale_percentage',
            'actionData' => 'stc_product.stc_product_id',
        ];

        return $map[$columnName] ?? 'stc_product.stc_product_id';
    }

    protected function baseListQuery()
    {
        return Product::query()
            ->leftJoin('stc_category', 'stc_category.stc_cat_id', '=', 'stc_product.stc_product_cat_id')
            ->leftJoin('stc_sub_category', 'stc_sub_category.stc_sub_cat_id', '=', 'stc_product.stc_product_sub_cat_id')
            ->leftJoin('stc_rack', 'stc_rack.stc_rack_id', '=', 'stc_product.stc_product_rack_id')
            ->leftJoin('stc_brand', 'stc_brand.stc_brand_id', '=', 'stc_product.stc_product_brand_id');
    }

    protected function applySearch($query, string $searchValue)
    {
        if ($searchValue === '') {
            return $query;
        }

        return $query->where(function ($q) use ($searchValue) {
            $like = '%' . $searchValue . '%';
            $q->where('stc_product.stc_product_name', 'like', $like)
                ->orWhere('stc_category.stc_cat_name', 'like', $like)
                ->orWhere('stc_product.stc_product_desc', 'like', $like)
                ->orWhere('stc_product.stc_product_hsncode', 'like', $like)
                ->orWhere('stc_product.stc_product_image', 'like', $like)
                ->orWhere('stc_product.stc_product_unit', 'like', $like)
                ->orWhere('stc_rack.stc_rack_name', 'like', $like)
                ->orWhere('stc_brand.stc_brand_title', 'like', $like)
                ->orWhere('stc_sub_category.stc_sub_cat_name', 'like', $like)
                ->orWhere('stc_product.stc_product_sale_percentage', 'like', $like)
                ->orWhere('stc_product.stc_product_cat_id', 'like', $like)
                ->orWhere('stc_product.stc_product_sub_cat_id', 'like', $like)
                ->orWhere('stc_product.stc_product_rack_id', 'like', $like)
                ->orWhere('stc_product.stc_product_brand_id', 'like', $like);
        });
    }

    /**
     * Convert empty strings from the modal forms to null so nullable / numeric rules work.
     */
    protected function normalizeProductFormInput(Request $request): void
    {
        $nullableKeys = ['sub_cat_id', 'rack_id', 'brand_id', 'gst', 'sale_percentage', 'image', 'desc'];
        foreach ($nullableKeys as $key) {
            if ($request->input($key) === '') {
                $request->merge([$key => null]);
            }
        }

        // Legacy DB rows may use 0 as "no rack/brand"; treat like empty so nullable + exists rules pass.
        foreach (['rack_id', 'brand_id'] as $key) {
            $v = $request->input($key);
            if ($v === 0 || $v === '0') {
                $request->merge([$key => null]);
            }
        }
    }

    /**
     * Rack / brand FK columns are NOT NULL in DB — store 0 when no selection (validation still uses null).
     */
    protected function rackBrandColumnValue($value): int
    {
        if ($value === null || $value === '' || $value === 0 || $value === '0') {
            return 0;
        }

        return (int) $value;
    }

    // show through ajax
    function list(Request $request)
    {
        $draw = $request->get('draw');
        $start = (int) $request->get('start', 0);
        $rowperpage = (int) $request->get('length', 10);

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column'] ?? 0;
        $columnName = $columnName_arr[$columnIndex]['data'] ?? 'stc_product_id';
        $columnSortOrder = $order_arr[0]['dir'] ?? 'desc';
        $searchValue = $search_arr['value'] ?? '';

        $orderExpr = $this->orderColumnExpression($columnName);

        $totalRecords = Product::count();

        $totalRecordswithFilter = $this->applySearch($this->baseListQuery(), $searchValue)
            ->distinct()
            ->count('stc_product.stc_product_id');

        $records = $this->applySearch($this->baseListQuery(), $searchValue)
            ->select(
                'stc_product.*',
                'stc_category.stc_cat_name',
                'stc_sub_category.stc_sub_cat_name',
                'stc_rack.stc_rack_name',
                'stc_brand.stc_brand_title'
            )
            ->orderByRaw($orderExpr . ' ' . ($columnSortOrder === 'asc' ? 'asc' : 'desc'))
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = [];

        foreach ($records as $record) {
            $id = $record->stc_product_id;
            $name = $record->stc_product_name;
            $desc = $record->stc_product_desc;
            $cat_name = $record->stc_cat_name;
            $sub_cat_name = $record->stc_sub_cat_name;
            $rack_name = $record->stc_rack_name;
            $brand_name = $record->stc_brand_title;
            $stc_product_hsncode = $record->stc_product_hsncode;
            $stc_product_gst = $record->stc_product_gst;
            $stc_product_unit = $record->stc_product_unit;
            $image = $record->stc_product_image;
            $salePct = $record->stc_product_sale_percentage;
            $status = $record->stc_product_avail == 1 ? 'Active' : 'In-active';

            if ($sub_cat_name == 'OTHERS') {
                $sub_cat_name = '';
            }

            $sub_cat_label = trim((string) $sub_cat_name);
            $brand_label = trim((string) ($brand_name ?? ''));
            $cat_label = trim((string) ($cat_name ?? ''));
            $rack_label = trim((string) ($rack_name ?? ''));

            $editPayload = [
                'id' => (int) $id,
                'name' => $name,
                'desc' => (string) ($desc ?? ''),
                'cat_id' => (int) ($record->stc_product_cat_id ?? 0),
                'cat_label' => $cat_label,
                'sub_cat_id' => $record->stc_product_sub_cat_id !== null ? (int) $record->stc_product_sub_cat_id : '',
                'sub_cat_label' => $sub_cat_label,
                'rack_id' => ($record->stc_product_rack_id !== null && (int) $record->stc_product_rack_id !== 0)
                    ? (int) $record->stc_product_rack_id
                    : '',
                'rack_label' => $rack_label,
                'brand_id' => ($record->stc_product_brand_id !== null && (int) $record->stc_product_brand_id !== 0)
                    ? (int) $record->stc_product_brand_id
                    : '',
                'brand_label' => $brand_label,
                'hsncode' => (string) ($record->stc_product_hsncode ?? ''),
                'gst' => $record->stc_product_gst !== null && $record->stc_product_gst !== ''
                    ? (float) $record->stc_product_gst
                    : '',
                'unit' => (string) ($record->stc_product_unit ?? ''),
                'status' => (int) $record->stc_product_avail,
                'image' => (string) ($image ?? ''),
                'sale_percentage' => $salePct !== null && $salePct !== ''
                    ? (float) $salePct
                    : '',
            ];
            $payloadJson = htmlspecialchars(json_encode($editPayload), ENT_QUOTES, 'UTF-8');

            $e = function ($v) {
                return htmlspecialchars((string) ($v ?? ''), ENT_QUOTES, 'UTF-8');
            };

            $imgRaw = (string) ($image ?? '');
            $imgHtml = '—';
            if ($imgRaw !== '') {
                $imgEsc = $e($imgRaw);
                if (preg_match('#^https?://#i', $imgRaw)) {
                    $imgHtml = '<a href="' . $imgEsc . '" target="_blank" rel="noopener">' . $e(Str::limit($imgRaw, 36)) . '</a>';
                } else {
                    $imgHtml = '<span class="text-muted small" title="' . $imgEsc . '">' . $e(Str::limit($imgRaw, 28)) . '</span>';
                }
            }

            $saleHtml = ($salePct !== null && $salePct !== '') ? $e($salePct) . '%' : '—';

            $stc_product_id_html = '<span class="text-center d-block">' . $e($id) . '</span>';
            $stc_product_name_html = '<span>' . $e($name) . '</span>';
            $stc_product_desc_html = '<span class="small">' . $e($desc) . '</span>';
            $catIdVal = $record->stc_product_cat_id;
            $catTitle = ($catIdVal !== null && $catIdVal !== '') ? ' title="' . $e('ID: ' . $catIdVal) . '"' : '';
            $stc_product_cat_id_html = '<span' . $catTitle . '>'
                . ($cat_label !== '' ? $e($cat_label) : '<span class="text-muted">—</span>')
                . '</span>';

            $subCatIdVal = $record->stc_product_sub_cat_id;
            $subCatTitle = ($subCatIdVal !== null && $subCatIdVal !== '') ? ' title="' . $e('ID: ' . $subCatIdVal) . '"' : '';
            $stc_product_sub_cat_id_html = '<span' . $subCatTitle . '>'
                . ($sub_cat_label !== '' ? $e($sub_cat_label) : '<span class="text-muted">—</span>')
                . '</span>';

            $rackIdVal = $record->stc_product_rack_id;
            $rackTitle = ($rackIdVal !== null && $rackIdVal !== '' && (int) $rackIdVal !== 0)
                ? ' title="' . $e('ID: ' . $rackIdVal) . '"'
                : '';
            $stc_product_rack_id_html = '<span' . $rackTitle . '>'
                . ($rack_label !== '' ? $e($rack_label) : '<span class="text-muted">—</span>')
                . '</span>';

            $brandIdVal = $record->stc_product_brand_id;
            $brandTitle = ($brandIdVal !== null && $brandIdVal !== '' && (int) $brandIdVal !== 0)
                ? ' title="' . $e('ID: ' . $brandIdVal) . '"'
                : '';
            $stc_product_brand_id_html = '<span' . $brandTitle . '>'
                . ($brand_label !== '' ? $e($brand_label) : '<span class="text-muted">—</span>')
                . '</span>';

            $stc_product_unit_html = '<span>' . $e($stc_product_unit) . '</span>';
            $stc_product_hsncode_html = '<span>' . $e($stc_product_hsncode) . '</span>';
            $stc_product_gst_html = '<span>' . $e($stc_product_gst) . '%</span>';
            $stc_product_avail_html = '<span>' . $e($status) . '</span>';
            $stc_product_image_html = $imgHtml;
            $stc_product_sale_percentage_html = '<span>' . $saleHtml . '</span>';

            $actionData = '
                <a href="javascript:void(0)" class="btn btn-primary btn-sm edit-modal-btn" data-toggle="modal" data-target="#edit-modal" data-product="' . $payloadJson . '" id="' . $id . '" ><i class="fas fa-edit" title="Edit"></i></a>
                <a href="javascript:void(0)" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-modal" onclick=$("#delete_id").val("' . $id . '")><i class="fas fa-trash" title="Delete"></i></a>
            ';
            $data_arr[] = [
                'stc_product_id' => $stc_product_id_html,
                'stc_product_name' => $stc_product_name_html,
                'stc_product_desc' => $stc_product_desc_html,
                'stc_product_cat_id' => $stc_product_cat_id_html,
                'stc_product_sub_cat_id' => $stc_product_sub_cat_id_html,
                'stc_product_rack_id' => $stc_product_rack_id_html,
                'stc_product_brand_id' => $stc_product_brand_id_html,
                'stc_product_unit' => $stc_product_unit_html,
                'stc_product_hsncode' => $stc_product_hsncode_html,
                'stc_product_gst' => $stc_product_gst_html,
                'stc_product_avail' => $stc_product_avail_html,
                'stc_product_image' => $stc_product_image_html,
                'stc_product_sale_percentage' => $stc_product_sale_percentage_html,
                'actionData' => $actionData,
            ];
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecordswithFilter,
            'data' => $data_arr,
        ]);
    }

    function create(Request $request)
    {
        $this->normalizeProductFormInput($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'desc' => 'nullable|string|max:2000',
            'cat_id' => 'required|integer|exists:stc_category,stc_cat_id',
            'sub_cat_id' => 'nullable|integer|exists:stc_sub_category,stc_sub_cat_id',
            'rack_id' => 'nullable|integer|exists:stc_rack,stc_rack_id',
            'brand_id' => 'nullable|integer|exists:stc_brand,stc_brand_id',
            'hsncode' => 'nullable|string|max:255',
            'gst' => 'nullable|numeric',
            'unit' => 'nullable|string|max:255',
            'status' => 'required|in:0,1',
            'image' => 'nullable|string|max:2048',
            'sale_percentage' => 'nullable|numeric',
        ]);

        $insert = [
            'stc_product_name' => $validated['name'],
            'stc_product_desc' => $validated['desc'] ?? $validated['name'],
            'stc_product_cat_id' => $validated['cat_id'],
            'stc_product_sub_cat_id' => $validated['sub_cat_id'] ?? null,
            'stc_product_rack_id' => $this->rackBrandColumnValue($validated['rack_id'] ?? null),
            'stc_product_brand_id' => $this->rackBrandColumnValue($validated['brand_id'] ?? null),
            'stc_product_unit' => $validated['unit'] ?? '',
            'stc_product_hsncode' => $validated['hsncode'] ?? '',
            'stc_product_gst' => $validated['gst'] ?? null,
            'stc_product_avail' => $validated['status'],
            'stc_product_image' => $validated['image'] ?? null,
            'stc_product_sale_percentage' => $validated['sale_percentage'] ?? null,
        ];

        $create = Product::create($insert);

        if ($create) {
            return [
                'status' => 'ok',
                'success' => true,
                'message' => 'Record saved succesfully!',
            ];
        }

        return [
            'status' => 'ok',
            'success' => false,
            'message' => 'Record saved failed!',
        ];
    }

    function update(Request $request)
    {
        $this->normalizeProductFormInput($request);

        $validated = $request->validate([
            'id' => 'required|integer|exists:stc_product,stc_product_id',
            'name' => 'required|string|max:255',
            'desc' => 'nullable|string|max:2000',
            'cat_id' => 'required|integer|exists:stc_category,stc_cat_id',
            'sub_cat_id' => 'nullable|integer|exists:stc_sub_category,stc_sub_cat_id',
            'rack_id' => 'nullable|integer|exists:stc_rack,stc_rack_id',
            'brand_id' => 'nullable|integer|exists:stc_brand,stc_brand_id',
            'hsncode' => 'nullable|string|max:255',
            'gst' => 'nullable|numeric',
            'unit' => 'nullable|string|max:255',
            'status' => 'required|in:0,1',
            'image' => 'nullable|string|max:2048',
            'sale_percentage' => 'nullable|numeric',
        ]);

        $update = [
            'stc_product_name' => $validated['name'],
            'stc_product_desc' => $validated['desc'] ?? $validated['name'],
            'stc_product_cat_id' => $validated['cat_id'],
            'stc_product_sub_cat_id' => $validated['sub_cat_id'] ?? null,
            'stc_product_rack_id' => $this->rackBrandColumnValue($validated['rack_id'] ?? null),
            'stc_product_brand_id' => $this->rackBrandColumnValue($validated['brand_id'] ?? null),
            'stc_product_unit' => $validated['unit'] ?? '',
            'stc_product_hsncode' => $validated['hsncode'] ?? '',
            'stc_product_gst' => $validated['gst'] ?? null,
            'stc_product_avail' => $validated['status'],
            'stc_product_image' => $validated['image'] ?? null,
            'stc_product_sale_percentage' => $validated['sale_percentage'] ?? null,
        ];

        Product::where('stc_product_id', $validated['id'])->update($update);

        return [
            'status' => 'ok',
            'success' => true,
            'message' => 'Record updated succesfully!',
        ];
    }

    function delete(Request $request)
    {
        $delete = Product::destroy($request->id);
        if ($delete) {
            return [
                'status' => 'ok',
                'success' => true,
                'message' => 'Record deleted succesfully!',
            ];
        }

        return [
            'status' => 'ok',
            'success' => false,
            'message' => 'Record deleted failed!',
        ];
    }
}
