<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\City;
use App\Merchant;
use App\State;

class MerchantController extends Controller
{
    public function show()
    {
        $data['page_title'] = 'Merchant';
        $data['cities'] = City::orderBy('stc_city_name')->get(['stc_city_id', 'stc_city_name']);
        $data['states'] = State::orderBy('stc_state_name')->get(['stc_state_id', 'stc_state_name']);
        $data['categories'] = Merchant::categoryOptions();
        $data['default_city_id'] = 65;
        $data['default_state_id'] = 16;

        return view('pages.merchant', $data);
    }

    protected function orderColumnExpression(string $columnName): string
    {
        $map = [
            'stc_merchant_id' => 'stc_merchant.stc_merchant_id',
            'stc_merchant_name' => 'stc_merchant.stc_merchant_name',
            'stc_merchant_category' => 'stc_merchant.stc_merchant_category',
            'stc_merchant_city_id' => 'stc_city.stc_city_name',
            'stc_merchant_state_id' => 'stc_state.stc_state_name',
            'stc_merchant_contact_person' => 'stc_merchant.stc_merchant_contact_person',
            'stc_merchant_phone' => 'stc_merchant.stc_merchant_phone',
            'stc_merchant_gstin' => 'stc_merchant.stc_merchant_gstin',
            'actionData' => 'stc_merchant.stc_merchant_id',
        ];

        return $map[$columnName] ?? 'stc_merchant.stc_merchant_id';
    }

    protected function baseListQuery()
    {
        return Merchant::query()
            ->leftJoin('stc_city', 'stc_city.stc_city_id', '=', 'stc_merchant.stc_merchant_city_id')
            ->leftJoin('stc_state', 'stc_state.stc_state_id', '=', 'stc_merchant.stc_merchant_state_id');
    }

    protected function applySearch($query, string $searchValue)
    {
        if ($searchValue === '') {
            return $query;
        }

        $like = '%' . $searchValue . '%';

        return $query->where(function ($q) use ($like) {
            $q->where('stc_merchant.stc_merchant_name', 'like', $like)
                ->orWhere('stc_merchant.stc_merchant_address', 'like', $like)
                ->orWhere('stc_merchant.stc_merchant_contact_person', 'like', $like)
                ->orWhere('stc_merchant.stc_merchant_email', 'like', $like)
                ->orWhere('stc_merchant.stc_merchant_phone', 'like', $like)
                ->orWhere('stc_merchant.stc_merchant_pan', 'like', $like)
                ->orWhere('stc_merchant.stc_merchant_gstin', 'like', $like)
                ->orWhere('stc_merchant.stc_merchant_specially_known_for', 'like', $like)
                ->orWhere('stc_merchant.stc_merchant_category', 'like', $like)
                ->orWhere('stc_city.stc_city_name', 'like', $like)
                ->orWhere('stc_state.stc_state_name', 'like', $like);
        });
    }

    protected function e($v): string
    {
        return htmlspecialchars((string) ($v ?? ''), ENT_QUOTES, 'UTF-8');
    }

    protected function dash($v): string
    {
        $t = trim((string) ($v ?? ''));

        return $t === '' ? '—' : $this->e($t);
    }

    protected function normalizeMerchantFormInput(Request $request): void
    {
        foreach (['email', 'phone', 'pan', 'gstin', 'contact_person', 'known_for', 'category', 'image'] as $key) {
            if ($request->input($key) === null) {
                continue;
            }
            $v = trim((string) $request->input($key));
            $request->merge([$key => $v === '' ? null : $v]);
        }

        foreach (['name', 'address'] as $key) {
            if ($request->input($key) !== null) {
                $request->merge([$key => trim((string) $request->input($key))]);
            }
        }
    }

    protected function validationRules(?int $ignoreId = null): array
    {
        $nameUnique = 'unique:stc_merchant,stc_merchant_name';
        if ($ignoreId) {
            $nameUnique .= ',' . $ignoreId . ',stc_merchant_id';
        }

        return [
            'name' => 'required|string|max:255|' . $nameUnique,
            'address' => 'required|string|max:2000',
            'city_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    if ((int) $value === 0) {
                        return;
                    }
                    if (!City::where('stc_city_id', $value)->exists()) {
                        $fail('Selected city is invalid.');
                    }
                },
            ],
            'state_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    if ((int) $value === 0) {
                        return;
                    }
                    if (!State::where('stc_state_id', $value)->exists()) {
                        $fail('Selected state is invalid.');
                    }
                },
            ],
            'category' => 'nullable|string|max:50|in:' . implode(',', Merchant::categoryOptions()),
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'known_for' => 'nullable|string|max:255',
            'gstin' => 'nullable|string|max:30',
            'pan' => 'nullable|string|max:20',
            'image' => 'nullable|string|max:2048',
        ];
    }

    protected function payloadFromRequest(Request $request, bool $isCreate = false): array
    {
        $payload = [
            'stc_merchant_name' => $request->name,
            'stc_merchant_address' => $request->address,
            'stc_merchant_city_id' => (int) $request->city_id,
            'stc_merchant_state_id' => (int) $request->state_id,
            'stc_merchant_contact_person' => (string) ($request->contact_person ?? ''),
            'stc_merchant_email' => (string) ($request->email ?? ''),
            'stc_merchant_phone' => (string) ($request->phone ?? ''),
            'stc_merchant_pan' => strtoupper((string) ($request->pan ?? '')),
            'stc_merchant_gstin' => strtoupper((string) ($request->gstin ?? '')),
            'stc_merchant_specially_known_for' => (string) ($request->known_for ?? ''),
            'stc_merchant_category' => (string) ($request->category ?? ''),
            'stc_merchant_image' => $request->image !== null && $request->image !== '' ? $request->image : null,
        ];

        if ($isCreate) {
            $payload['stc_merchant_found_by'] = (int) (Auth::id() ?: 0);
        }

        return $payload;
    }

    protected function duplicateExists(Request $request, ?int $ignoreId = null): ?string
    {
        $pan = strtoupper(trim((string) $request->pan));
        $gstin = strtoupper(trim((string) $request->gstin));

        if ($pan !== '') {
            $q = Merchant::where('stc_merchant_pan', $pan);
            if ($ignoreId) {
                $q->where('stc_merchant_id', '!=', $ignoreId);
            }
            if ($q->exists()) {
                return 'A merchant with this PAN already exists.';
            }
        }

        if ($gstin !== '') {
            $q = Merchant::where('stc_merchant_gstin', $gstin);
            if ($ignoreId) {
                $q->where('stc_merchant_id', '!=', $ignoreId);
            }
            if ($q->exists()) {
                return 'A merchant with this GSTIN already exists.';
            }
        }

        return null;
    }

    public function list(Request $request)
    {
        $draw = $request->get('draw');
        $start = (int) $request->get('start', 0);
        $rowperpage = (int) $request->get('length', 10);

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column'] ?? 0;
        $columnName = $columnName_arr[$columnIndex]['data'] ?? 'stc_merchant_id';
        $columnSortOrder = $order_arr[0]['dir'] ?? 'desc';
        $searchValue = $search_arr['value'] ?? '';

        $orderExpr = $this->orderColumnExpression($columnName);
        $dir = $columnSortOrder === 'asc' ? 'asc' : 'desc';

        $totalRecords = Merchant::count();
        $totalRecordswithFilter = $this->applySearch($this->baseListQuery(), $searchValue)
            ->distinct()
            ->count('stc_merchant.stc_merchant_id');

        $records = $this->applySearch($this->baseListQuery(), $searchValue)
            ->select(
                'stc_merchant.*',
                'stc_city.stc_city_name',
                'stc_state.stc_state_name'
            )
            ->orderByRaw($orderExpr . ' ' . $dir)
            ->skip($start)
            ->take($rowperpage > 0 ? $rowperpage : 10)
            ->get();

        $data_arr = [];

        foreach ($records as $record) {
            $id = (int) $record->stc_merchant_id;
            $cityLabel = trim((string) ($record->stc_city_name ?? ''));
            $stateLabel = trim((string) ($record->stc_state_name ?? ''));
            $catLabel = trim((string) ($record->stc_merchant_category ?? ''));

            $editPayload = [
                'id' => $id,
                'name' => (string) ($record->stc_merchant_name ?? ''),
                'address' => (string) ($record->stc_merchant_address ?? ''),
                'city_id' => (int) ($record->stc_merchant_city_id ?? 0),
                'state_id' => (int) ($record->stc_merchant_state_id ?? 0),
                'category' => $catLabel,
                'contact_person' => (string) ($record->stc_merchant_contact_person ?? ''),
                'phone' => (string) ($record->stc_merchant_phone ?? ''),
                'email' => (string) ($record->stc_merchant_email ?? ''),
                'known_for' => (string) ($record->stc_merchant_specially_known_for ?? ''),
                'gstin' => (string) ($record->stc_merchant_gstin ?? ''),
                'pan' => (string) ($record->stc_merchant_pan ?? ''),
                'image' => (string) ($record->stc_merchant_image ?? ''),
            ];
            $payloadJson = htmlspecialchars(json_encode($editPayload), ENT_QUOTES, 'UTF-8');

            $actionData = '
                <a href="javascript:void(0)" class="btn btn-primary btn-sm edit-modal-btn" data-toggle="modal" data-target="#edit-modal" data-merchant="' . $payloadJson . '" id="' . $id . '"><i class="fas fa-edit" title="Edit"></i></a>
                <a href="javascript:void(0)" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-modal" onclick=$("#delete_id").val("' . $id . '")><i class="fas fa-trash" title="Delete"></i></a>
            ';

            $data_arr[] = [
                'stc_merchant_id' => '<span class="text-center d-block">' . $this->e($id) . '</span>',
                'stc_merchant_name' => '<span>' . $this->e($record->stc_merchant_name) . '</span>',
                'stc_merchant_category' => $catLabel !== ''
                    ? '<span class="badge badge-success">' . $this->e($catLabel) . '</span>'
                    : '<span class="text-muted">—</span>',
                'stc_merchant_city_id' => $this->dash($cityLabel),
                'stc_merchant_state_id' => $this->dash($stateLabel),
                'stc_merchant_contact_person' => $this->dash($record->stc_merchant_contact_person),
                'stc_merchant_phone' => $this->dash($record->stc_merchant_phone),
                'stc_merchant_gstin' => $this->dash($record->stc_merchant_gstin),
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

    public function create(Request $request)
    {
        $this->normalizeMerchantFormInput($request);
        $request->validate($this->validationRules());

        $dup = $this->duplicateExists($request);
        if ($dup) {
            return [
                'status' => 'ok',
                'success' => false,
                'message' => $dup,
            ];
        }

        $create = Merchant::create($this->payloadFromRequest($request, true));

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

    public function update(Request $request)
    {
        $this->normalizeMerchantFormInput($request);
        $request->validate(array_merge([
            'id' => 'required|integer|exists:stc_merchant,stc_merchant_id',
        ], $this->validationRules((int) $request->id)));

        $id = (int) $request->id;
        $dup = $this->duplicateExists($request, $id);
        if ($dup) {
            return [
                'status' => 'ok',
                'success' => false,
                'message' => $dup,
            ];
        }

        Merchant::where('stc_merchant_id', $id)->update($this->payloadFromRequest($request, false));

        return [
            'status' => 'ok',
            'success' => true,
            'message' => 'Record updated succesfully!',
        ];
    }

    public function delete(Request $request)
    {
        $delete = Merchant::destroy($request->id);
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

    protected function adhocSourceBaseQuery()
    {
        return DB::table('stc_purchase_product_adhoc')
            ->whereNotNull('stc_purchase_product_adhoc_source')
            ->whereRaw("TRIM(stc_purchase_product_adhoc_source) <> ''")
            ->whereRaw("NOT EXISTS (
                SELECT 1 FROM stc_merchant
                WHERE UPPER(TRIM(stc_merchant.stc_merchant_name))
                    = UPPER(TRIM(stc_purchase_product_adhoc.stc_purchase_product_adhoc_source))
            )");
    }

    protected $merchantNamesCache = null;

    protected function merchantNamesList(): array
    {
        if ($this->merchantNamesCache !== null) {
            return $this->merchantNamesCache;
        }

        $out = [];
        $names = Merchant::query()
            ->whereNotNull('stc_merchant_name')
            ->whereRaw("TRIM(stc_merchant_name) <> ''")
            ->pluck('stc_merchant_name');

        foreach ($names as $name) {
            $orig = trim((string) $name);
            if ($orig === '') {
                continue;
            }
            $out[] = [
                'name' => $orig,
                'norm' => $this->normalizeComparableName($orig),
            ];
        }

        return $this->merchantNamesCache = $out;
    }

    protected function normalizeComparableName(string $name): string
    {
        $n = strtoupper(trim($name));
        $n = str_replace(['&', '.', ',', '-', '/', '\\', "'", '"', '(', ')', '_'], ' ', $n);
        $n = preg_replace('/\b(PVT|PRIVATE|LIMITED|LTD|LLP|CO|COMPANY)\b/', ' ', $n);
        $n = preg_replace('/\s+/', ' ', $n);

        return trim((string) $n);
    }

    /**
     * Closest merchant master names for a free-text adhoc source.
     *
     * @return array<int, array{name: string, pct: int}>
     */
    protected function similarMerchantMatches(string $source, int $limit = 2): array
    {
        $srcNorm = $this->normalizeComparableName($source);
        if ($srcNorm === '' || strlen($srcNorm) < 4) {
            return [];
        }

        $srcLen = strlen($srcNorm);
        $scored = [];

        foreach ($this->merchantNamesList() as $m) {
            $merNorm = $m['norm'];
            if ($merNorm === '') {
                continue;
            }

            $pct = 0.0;
            similar_text($srcNorm, $merNorm, $pct);
            $merLen = strlen($merNorm);
            $lev = ($srcLen <= 255 && $merLen <= 255) ? levenshtein($srcNorm, $merNorm) : null;
            $contains = (strpos($merNorm, $srcNorm) !== false || strpos($srcNorm, $merNorm) !== false);
            $maxLen = max($srcLen, $merLen);
            $relLev = ($lev !== null && $maxLen > 0) ? ($lev / $maxLen) : 1;

            $ok = $pct >= 72
                || ($lev !== null && $lev <= 3 && abs($srcLen - $merLen) <= 8)
                || ($contains && $pct >= 55 && min($srcLen, $merLen) >= 6)
                || ($relLev <= 0.2 && $pct >= 60);

            if (!$ok) {
                continue;
            }

            $score = $pct;
            if ($lev !== null) {
                $score += max(0, 25 - $lev);
            }
            if ($contains) {
                $score += 12;
            }

            $scored[] = [
                'name' => $m['name'],
                'pct' => (int) round($pct),
                'score' => $score,
            ];
        }

        usort($scored, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        $top = [];
        $seen = [];
        foreach ($scored as $row) {
            $key = strtoupper(trim($row['name']));
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;
            $top[] = ['name' => $row['name'], 'pct' => $row['pct']];
            if (count($top) >= $limit) {
                break;
            }
        }

        return $top;
    }

    protected function similarMerchantHtml(string $source): array
    {
        $matches = $this->similarMerchantMatches($source);
        if ($matches === []) {
            return ['html' => '<span class="text-muted">—</span>', 'best' => ''];
        }

        $parts = [];
        foreach ($matches as $m) {
            $parts[] = '<div>'
                . $this->e($m['name'])
                . ' <small class="text-muted">(' . $this->e($m['pct']) . '%)</small>'
                . '</div>';
        }

        return [
            'html' => implode('', $parts),
            'best' => $matches[0]['name'],
        ];
    }

    protected function merchantNameKeys(): array
    {
        $keys = [];
        $names = Merchant::query()
            ->whereNotNull('stc_merchant_name')
            ->whereRaw("TRIM(stc_merchant_name) <> ''")
            ->pluck('stc_merchant_name');

        foreach ($names as $name) {
            $key = mb_strtoupper(trim((string) $name), 'UTF-8');
            if ($key !== '') {
                $keys[$key] = true;
            }
        }

        return $keys;
    }

    public function adhocSourcesList(Request $request)
    {
        $draw = $request->get('draw');
        $start = (int) $request->get('start', 0);
        $rowperpage = (int) $request->get('length', 25);
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column'] ?? 0;
        $columnName = $columnName_arr[$columnIndex]['data'] ?? 'source_name';
        $columnSortOrder = $order_arr[0]['dir'] ?? 'asc';
        $searchValue = trim((string) ($search_arr['value'] ?? ''));

        $orderMap = [
            'select' => 'source_name',
            'source_name' => 'source_name',
            'similar_merchant' => 'source_name',
            'usage_count' => 'usage_count',
            'actionData' => 'source_name',
        ];
        $orderExpr = $orderMap[$columnName] ?? 'source_name';
        $dir = $columnSortOrder === 'desc' ? 'desc' : 'asc';

        $totalRecords = (int) $this->adhocSourceBaseQuery()
            ->selectRaw('COUNT(DISTINCT TRIM(stc_purchase_product_adhoc_source)) as c')
            ->value('c');

        $filteredQuery = $this->adhocSourceBaseQuery();
        if ($searchValue !== '') {
            $filteredQuery->whereRaw('TRIM(stc_purchase_product_adhoc_source) LIKE ?', ['%' . $searchValue . '%']);
        }
        $totalRecordswithFilter = (int) $filteredQuery
            ->selectRaw('COUNT(DISTINCT TRIM(stc_purchase_product_adhoc_source)) as c')
            ->value('c');

        $records = $this->adhocSourceBaseQuery()
            ->selectRaw('TRIM(stc_purchase_product_adhoc_source) as source_name, COUNT(*) as usage_count')
            ->groupBy(DB::raw('TRIM(stc_purchase_product_adhoc_source)'));

        if ($searchValue !== '') {
            $records->whereRaw('TRIM(stc_purchase_product_adhoc_source) LIKE ?', ['%' . $searchValue . '%']);
        }

        $records = $records
            ->orderByRaw($orderExpr . ' ' . $dir)
            ->skip($start)
            ->take($rowperpage > 0 ? $rowperpage : 25)
            ->get();

        $data_arr = [];
        foreach ($records as $record) {
            $name = (string) $record->source_name;
            $count = (int) $record->usage_count;
            $similar = $this->similarMerchantHtml($name);
            $payload = htmlspecialchars(json_encode([
                'source' => $name,
                'count' => $count,
                'similar' => $similar['best'],
            ]), ENT_QUOTES, 'UTF-8');

            $data_arr[] = [
                'select' => '<input type="checkbox" class="adhoc-source-check" data-source="' . $payload . '">',
                'source_name' => '<span>' . $this->e($name) . '</span>',
                'similar_merchant' => $similar['html'],
                'usage_count' => '<span class="d-block text-center">' . $this->e($count) . '</span>',
                'actionData' => '<a href="javascript:void(0)" class="btn btn-primary btn-sm rename-source-btn" data-source="' . $payload . '"><i class="fas fa-edit" title="Rename"></i> Rename</a>',
            ];
        }

        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecordswithFilter,
            'data' => $data_arr,
        ]);
    }

    public function adhocSourceRename(Request $request)
    {
        $request->validate([
            'old_source' => 'nullable|string|max:500',
            'old_sources' => 'nullable|array',
            'old_sources.*' => 'string|max:500',
            'new_source' => 'required|string|max:500',
        ]);

        $olds = $request->input('old_sources', []);
        if (!is_array($olds)) {
            $olds = [];
        }
        if ($olds === [] && $request->filled('old_source')) {
            $olds = [$request->old_source];
        }

        $unique = [];
        foreach ($olds as $name) {
            $name = trim((string) $name);
            if ($name === '') {
                continue;
            }
            $unique[$name] = $name;
        }
        $olds = array_values($unique);
        $new = trim((string) $request->new_source);

        if ($olds === [] || $new === '') {
            return [
                'status' => 'ok',
                'success' => false,
                'message' => 'Source name cannot be empty.',
            ];
        }

        $merchantKeys = $this->merchantNameKeys();
        $skipped = [];
        $toUpdate = [];
        foreach ($olds as $name) {
            $key = mb_strtoupper($name, 'UTF-8');
            if (isset($merchantKeys[$key])) {
                $skipped[] = $name;
                continue;
            }
            if ($name === $new) {
                continue;
            }
            $toUpdate[] = $name;
        }

        if ($toUpdate === []) {
            return [
                'status' => 'ok',
                'success' => false,
                'message' => $skipped !== []
                    ? 'Skipped — selected name(s) already exist in merchant master.'
                    : 'New name is the same as the current name.',
            ];
        }

        $updated = 0;
        DB::transaction(function () use ($toUpdate, $new, &$updated) {
            foreach ($toUpdate as $old) {
                $updated += DB::table('stc_purchase_product_adhoc')
                    ->whereRaw('TRIM(stc_purchase_product_adhoc_source) = ?', [$old])
                    ->update(['stc_purchase_product_adhoc_source' => $new]);
            }
        });

        $fromLabel = count($toUpdate) === 1
            ? '"' . $toUpdate[0] . '"'
            : count($toUpdate) . ' source names';

        $message = $updated . ' record(s) renamed from ' . $fromLabel . ' to "' . $new . '".';
        if ($skipped !== []) {
            $message .= ' Skipped ' . count($skipped) . ' name(s) already in merchant master.';
        }

        return [
            'status' => 'ok',
            'success' => true,
            'message' => $message,
            'updated' => $updated,
            'skipped' => $skipped,
        ];
    }
}
