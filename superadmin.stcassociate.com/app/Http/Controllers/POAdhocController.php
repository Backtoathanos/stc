<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\POAdhoc;

class POAdhocController extends Controller
{
    public function show(){
        $data['page_title'] = "PO Adhoc";
        $data['sources'] = POAdhoc::select('stc_purchase_product_adhoc_source')
            ->whereNotNull('stc_purchase_product_adhoc_source')
            ->where('stc_purchase_product_adhoc_source', '!=', '')
            ->distinct()
            ->orderBy('stc_purchase_product_adhoc_source')
            ->pluck('stc_purchase_product_adhoc_source');
        $data['destinations'] = POAdhoc::select('stc_purchase_product_adhoc_destination')
            ->whereNotNull('stc_purchase_product_adhoc_destination')
            ->where('stc_purchase_product_adhoc_destination', '!=', '')
            ->distinct()
            ->orderBy('stc_purchase_product_adhoc_destination')
            ->pluck('stc_purchase_product_adhoc_destination');
        $data['receivedByList'] = POAdhoc::select('stc_purchase_product_adhoc_recievedby')
            ->whereNotNull('stc_purchase_product_adhoc_recievedby')
            ->where('stc_purchase_product_adhoc_recievedby', '!=', '')
            ->distinct()
            ->orderBy('stc_purchase_product_adhoc_recievedby')
            ->pluck('stc_purchase_product_adhoc_recievedby');
        // Base URL for stc_vikings transfer challan preview (no trailing slash), e.g. https://example.com/stc/stc_vikings
        $data['stc_vikings_challan_base'] = rtrim((string) env('STC_VIKINGS_CHALLAN_BASE', ''), '/');
        return view('pages.poadhoc', $data);
    }

    // Show through AJAX
    public function list(Request $request)
    {
        ## Read value
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        $t = 'stc_purchase_product_adhoc';

        $withProductCategory = function ($q) use ($t) {
            return $q->leftJoin('stc_product', $t . '.stc_purchase_product_adhoc_productid', '=', 'stc_product.stc_product_id')
                ->leftJoin('stc_category', 'stc_product.stc_product_cat_id', '=', 'stc_category.stc_cat_id')
                ->select($t . '.*');
        };

        $searchScope = function ($query) use ($searchValue, $t) {
            $query->where($t . '.stc_purchase_product_adhoc_id', 'like', '%' . $searchValue . '%')
                ->orWhere($t . '.stc_purchase_product_adhoc_itemdesc', 'like', '%' . $searchValue . '%')
                ->orWhere($t . '.stc_purchase_product_adhoc_qty', 'like', '%' . $searchValue . '%')
                ->orWhere($t . '.stc_purchase_product_adhoc_rate', 'like', '%' . $searchValue . '%')
                ->orWhere($t . '.stc_purchase_product_adhoc_status', 'like', '%' . $searchValue . '%')
                ->orWhere('stc_category.stc_cat_name', 'like', '%' . $searchValue . '%');
        };

        // Total records
        $totalRecords = POAdhoc::count();
        $totalRecordswithFilter = $withProductCategory(POAdhoc::query())
            ->where($searchScope)
            ->count();

        $orderColumn = $t . '.' . $columnName;
        if (in_array($columnName, ['actionData', 'stc_purchase_product_adhoc_status_badge', 'stc_purchase_product_adhoc_status_label', 'stc_shop_breakdown'], true)) {
            $orderColumn = $t . '.stc_purchase_product_adhoc_id';
        }

        // Fetch records
        $records = $withProductCategory(POAdhoc::query())
            ->where($searchScope)
            ->orderBy($orderColumn, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $adhocIds = $records->pluck('stc_purchase_product_adhoc_id')->filter()->unique()->values()->all();
        $shopsByAdhoc = collect();
        if (!empty($adhocIds)) {
            $shopsByAdhoc = DB::table('stc_shop')
                ->leftJoin('stc_rack', 'stc_shop.rack_id', '=', 'stc_rack.stc_rack_id')
                ->whereIn('stc_shop.adhoc_id', $adhocIds)
                ->orderBy('stc_shop.branch')
                ->orderBy('stc_shop.shopname')
                ->orderBy('stc_shop.id')
                ->select(
                    'stc_shop.id',
                    'stc_shop.branch',
                    'stc_shop.shopname',
                    'stc_shop.adhoc_id',
                    'stc_shop.qty',
                    'stc_shop.rack_id',
                    'stc_shop.remarks',
                    'stc_shop.created_date',
                    'stc_shop.created_by',
                    'stc_rack.stc_rack_name'
                )
                ->get()
                ->groupBy(function ($row) {
                    return (int) $row->adhoc_id;
                });
        }

        $data_arr = array();
        
        $statusLabels = [
            1 => 'Stock',
            2 => 'Dispatched',
            3 => 'Pending',
            4 => 'Approved',
            5 => 'Rejected'
        ];
        $statusColors = [
            1 => 'badge bg-primary',
            2 => 'badge bg-info',
            3 => 'badge bg-warning',
            4 => 'badge bg-success',
            5 => 'badge bg-danger'
        ];

        foreach ($records as $record) {
            $statusLabel = $statusLabels[$record->stc_purchase_product_adhoc_status] ?? $record->stc_purchase_product_adhoc_status;
            $statusBadge = '<span class="'.($statusColors[$record->stc_purchase_product_adhoc_status] ?? 'badge bg-secondary').'">'.$statusLabel.'</span>';
            $id = $record->stc_purchase_product_adhoc_id;
            $actionData = '
                <a href="javascript:void(0)" class="btn btn-info btn-sm shop-qty-modal-btn" data-adhoc-id="'.$id.'" data-toggle="modal" data-target="#shop-qty-modal" title="Qty by branch"><i class="fas fa-store"></i></a>
                <a href="javascript:void(0)" class="btn btn-primary btn-sm edit-modal-btn" data-toggle="modal" data-target="#edit-modal" id="'.$id.'"><i class="fas fa-edit" title="Edit"></i></a>
                <a href="javascript:void(0)" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-modal" onclick=$("#delete_id").val("'.$id.'")><i class="fas fa-trash" title="Delete"></i></a>
            ';

            $shopBreakdownHtml = self::formatStcShopBreakdownHtml($shopsByAdhoc->get((int) $id, collect()));

            $data_arr[] = array(
                'stc_purchase_product_adhoc_id' => $record->stc_purchase_product_adhoc_id,
                'stc_purchase_product_adhoc_productid' => $record->stc_purchase_product_adhoc_productid,
                'stc_purchase_product_adhoc_itemdesc' => $record->stc_purchase_product_adhoc_itemdesc,
                'stc_purchase_product_adhoc_qty' => $record->stc_purchase_product_adhoc_qty,
                'stc_shop_breakdown' => $shopBreakdownHtml,
                'stc_purchase_product_adhoc_prate' => $record->stc_purchase_product_adhoc_prate,
                'stc_purchase_product_adhoc_rate' => $record->stc_purchase_product_adhoc_rate,
                'stc_purchase_product_adhoc_unit' => $record->stc_purchase_product_adhoc_unit,
                'stc_purchase_product_adhoc_rackid' => $record->stc_purchase_product_adhoc_rackid,
                'stc_purchase_product_adhoc_condition' => $record->stc_purchase_product_adhoc_condition,
                'stc_purchase_product_adhoc_source' => $record->stc_purchase_product_adhoc_source,
                'stc_purchase_product_adhoc_destination' => $record->stc_purchase_product_adhoc_destination,
                'stc_purchase_product_adhoc_recievedby' => $record->stc_purchase_product_adhoc_recievedby,
                'stc_purchase_product_adhoc_cherrypickby' => $record->stc_purchase_product_adhoc_cherrypickby,
                'stc_purchase_product_adhoc_qtytodecrease' => $record->stc_purchase_product_adhoc_qtytodecrease,
                'stc_purchase_product_adhoc_status_label' => $statusLabel,
                'stc_purchase_product_adhoc_status_badge' => $statusBadge,
                'stc_purchase_product_adhoc_remarks' => $record->stc_purchase_product_adhoc_remarks,
                'stc_purchase_product_adhoc_created_by' => $record->stc_purchase_product_adhoc_created_by,
                'stc_purchase_product_adhoc_created_date' => $record->stc_purchase_product_adhoc_created_date,
                'stc_purchase_product_adhoc_updated_by' => $record->stc_purchase_product_adhoc_updated_by,
                'stc_purchase_product_adhoc_updated_date' => $record->stc_purchase_product_adhoc_updated_date,
                "actionData" => $actionData
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return json_encode($response);
    }

    // Get single record for edit
    public function get(Request $request){
        $record = POAdhoc::where('stc_purchase_product_adhoc_id', $request->id)->first();
        if($record){
            return response()->json(['success' => true, 'data' => $record]);
        } else {
            return response()->json(['success' => false, 'message' => 'Record not found!']);
        }
    }

    /** stc_shop rows + adhoc summary + shop name options for the modal. */
    public function shopsByAdhoc(Request $request)
    {
        $adhocId = (int) $request->get('adhoc_id', 0);
        if ($adhocId < 1) {
            return response()->json(['success' => false, 'message' => 'Invalid adhoc id']);
        }

        $adhoc = DB::table('stc_purchase_product_adhoc')
            ->leftJoin('stc_product', 'stc_purchase_product_adhoc.stc_purchase_product_adhoc_productid', '=', 'stc_product.stc_product_id')
            ->where('stc_purchase_product_adhoc.stc_purchase_product_adhoc_id', $adhocId)
            ->select(
                'stc_purchase_product_adhoc.stc_purchase_product_adhoc_id',
                'stc_purchase_product_adhoc.stc_purchase_product_adhoc_itemdesc',
                'stc_purchase_product_adhoc.stc_purchase_product_adhoc_qty',
                'stc_purchase_product_adhoc.stc_purchase_product_adhoc_unit',
                'stc_product.stc_product_name'
            )
            ->first();

        if (!$adhoc) {
            return response()->json(['success' => false, 'message' => 'PO adhoc line not found']);
        }

        $purchasedQty = (float) ($adhoc->stc_purchase_product_adhoc_qty ?? 0);
        $productLabel = trim((string) ($adhoc->stc_product_name ?? ''));
        if ($productLabel === '') {
            $productLabel = (string) ($adhoc->stc_purchase_product_adhoc_itemdesc ?? '');
        }

        $allocatedQty = (float) DB::table('stc_shop')
            ->where('adhoc_id', $adhocId)
            ->sum('qty');
        $balancedQty = $purchasedQty - $allocatedQty;

        $rows = DB::table('stc_shop')
            ->leftJoin('stc_rack', 'stc_shop.rack_id', '=', 'stc_rack.stc_rack_id')
            ->where('stc_shop.adhoc_id', $adhocId)
            ->orderBy('stc_shop.branch')
            ->orderBy('stc_shop.shopname')
            ->orderBy('stc_shop.id')
            ->select(
                'stc_shop.id',
                'stc_shop.branch',
                'stc_shop.shopname',
                'stc_shop.adhoc_id',
                'stc_shop.qty',
                'stc_shop.rack_id',
                'stc_shop.remarks',
                'stc_shop.created_date',
                'stc_shop.created_by',
                'stc_rack.stc_rack_name'
            )
            ->get();

        $shopNames = self::collectShopNameOptions();

        return response()->json([
            'success' => true,
            'adhoc' => [
                'id' => (int) $adhoc->stc_purchase_product_adhoc_id,
                'product_name' => $productLabel,
                'purchased_qty' => $purchasedQty,
                'unit' => (string) ($adhoc->stc_purchase_product_adhoc_unit ?? ''),
                'allocated_qty' => $allocatedQty,
                'balanced_qty' => $balancedQty,
            ],
            'shop_names' => $shopNames,
            'data' => $rows,
        ]);
    }

    /** Distinct shop / branch names for dropdowns (history + trading users). */
    private static function collectShopNameOptions(): array
    {
        $fromBranch = DB::table('stc_shop')
            ->whereNotNull('branch')
            ->where('branch', '!=', '')
            ->distinct()
            ->orderBy('branch')
            ->pluck('branch');

        $fromShops = DB::table('stc_shop')
            ->whereNotNull('shopname')
            ->where('shopname', '!=', '')
            ->distinct()
            ->orderBy('shopname')
            ->pluck('shopname');

        $fromTrading = DB::table('stc_trading_user')
            ->whereNotNull('stc_trading_user_location')
            ->where('stc_trading_user_location', '!=', '')
            ->distinct()
            ->orderBy('stc_trading_user_location')
            ->pluck('stc_trading_user_location');

        return $fromBranch->merge($fromShops)->merge($fromTrading)->unique()->sort()->values()->all();
    }

    /** Display label: prefer branch, fall back to shopname (legacy rows). */
    private static function stcShopBranchLabel($row): string
    {
        $b = trim((string) (is_object($row) ? ($row->branch ?? '') : ''));
        if ($b !== '') {
            return $b;
        }

        return trim((string) (is_object($row) ? ($row->shopname ?? '') : ''));
    }

    private static function adhocLinePurchasedQty(int $adhocId): float
    {
        $v = DB::table('stc_purchase_product_adhoc')
            ->where('stc_purchase_product_adhoc_id', $adhocId)
            ->value('stc_purchase_product_adhoc_qty');

        return (float) ($v ?? 0);
    }

    /** Sum of stc_shop.qty for this adhoc, optionally excluding one row (for updates). */
    private static function allocatedQtySumForAdhoc(int $adhocId, ?int $excludeStcShopId = null): float
    {
        $q = DB::table('stc_shop')->where('adhoc_id', $adhocId);
        if ($excludeStcShopId !== null) {
            $q->where('id', '!=', $excludeStcShopId);
        }

        return (float) $q->sum('qty');
    }

    /** Insert a stc_shop allocation for an adhoc line. */
    public function shopStore(Request $request)
    {
        $adhocId = (int) $request->input('adhoc_id', 0);
        $branch = trim((string) ($request->input('branch') ?? $request->input('shopname') ?? ''));
        $qtyRaw = $request->input('qty');
        $rackId = (int) $request->input('rack_id', 0);
        $remarks = trim((string) ($request->input('remarks') ?? ''));

        if ($adhocId < 1) {
            return response()->json(['success' => false, 'message' => 'Invalid PO adhoc id']);
        }
        if ($branch === '') {
            return response()->json(['success' => false, 'message' => 'Branch is required']);
        }

        $qty = ($qtyRaw === '' || $qtyRaw === null) ? 0.0 : (float) $qtyRaw;

        if (!POAdhoc::where('stc_purchase_product_adhoc_id', $adhocId)->exists()) {
            return response()->json(['success' => false, 'message' => 'PO adhoc line not found']);
        }

        $purchased = self::adhocLinePurchasedQty($adhocId);
        $allocated = self::allocatedQtySumForAdhoc($adhocId);
        $remaining = $purchased - $allocated;
        if ($qty > $remaining + 1e-6) {
            return response()->json([
                'success' => false,
                'message' => 'Quantity exceeds available balance. Purchased: ' . number_format($purchased, 2, '.', '')
                    . ', allocated: ' . number_format($allocated, 2, '.', '')
                    . ', remaining: ' . number_format(max(0, $remaining), 2, '.', '') . '.',
            ]);
        }

        DB::table('stc_shop')->insert([
            'branch' => $branch,
            'shopname' => $branch,
            'adhoc_id' => $adhocId,
            'qty' => $qty,
            'rack_id' => $rackId,
            'remarks' => $remarks,
            'created_date' => date('Y-m-d H:i:s'),
            'created_by' => auth()->user()->id ?? 0,
        ]);

        return response()->json(['success' => true, 'message' => 'Branch quantity saved']);
    }

    /** Update one stc_shop row (scoped to adhoc_id). */
    public function shopUpdate(Request $request)
    {
        $id = (int) $request->input('id', 0);
        $adhocId = (int) $request->input('adhoc_id', 0);
        $branch = trim((string) ($request->input('branch') ?? $request->input('shopname') ?? ''));
        $qtyRaw = $request->input('qty');
        $rackId = (int) $request->input('rack_id', 0);
        $remarks = trim((string) ($request->input('remarks') ?? ''));

        if ($id < 1 || $adhocId < 1) {
            return response()->json(['success' => false, 'message' => 'Invalid request']);
        }
        if ($branch === '') {
            return response()->json(['success' => false, 'message' => 'Branch is required']);
        }

        $qty = ($qtyRaw === '' || $qtyRaw === null) ? 0.0 : (float) $qtyRaw;

        $exists = DB::table('stc_shop')->where('id', $id)->where('adhoc_id', $adhocId)->exists();
        if (!$exists) {
            return response()->json(['success' => false, 'message' => 'Row not found']);
        }

        $purchased = self::adhocLinePurchasedQty($adhocId);
        $sumOthers = self::allocatedQtySumForAdhoc($adhocId, $id);
        if ($sumOthers + $qty > $purchased + 1e-6) {
            return response()->json([
                'success' => false,
                'message' => 'Updated quantity would exceed purchased quantity (' . number_format($purchased, 2, '.', '') . '). '
                    . 'Other shops total ' . number_format($sumOthers, 2, '.', '') . '; max for this row is '
                    . number_format(max(0, $purchased - $sumOthers), 2, '.', '') . '.',
            ]);
        }

        DB::table('stc_shop')
            ->where('id', $id)
            ->where('adhoc_id', $adhocId)
            ->update([
                'branch' => $branch,
                'shopname' => $branch,
                'qty' => $qty,
                'rack_id' => $rackId,
                'remarks' => $remarks,
            ]);

        return response()->json(['success' => true, 'message' => 'Branch row updated']);
    }

    /** Delete one stc_shop row (scoped to adhoc_id). */
    public function shopDestroy(Request $request)
    {
        $id = (int) $request->input('id', 0);
        $adhocId = (int) $request->input('adhoc_id', 0);

        if ($id < 1 || $adhocId < 1) {
            return response()->json(['success' => false, 'message' => 'Invalid request']);
        }

        $deleted = DB::table('stc_shop')
            ->where('id', $id)
            ->where('adhoc_id', $adhocId)
            ->delete();

        if ($deleted === 0) {
            return response()->json(['success' => false, 'message' => 'Row not found']);
        }

        return response()->json(['success' => true, 'message' => 'Shop row deleted']);
    }

    // Update record
    public function update(Request $request){
        // Helper function to convert empty string to 0 for integer fields
        $toInt = function($value) {
            return ($value === '' || $value === null) ? 0 : (int)$value;
        };
        
        // Helper function to convert empty string to 0 for float fields
        $toFloat = function($value) {
            return ($value === '' || $value === null) ? 0 : (float)$value;
        };
        
        $update = [
            'stc_purchase_product_adhoc_productid' => $toInt($request->productid),
            'stc_purchase_product_adhoc_itemdesc' => $request->itemdesc ?? '',
            'stc_purchase_product_adhoc_qty' => $toFloat($request->qty),
            'stc_purchase_product_adhoc_prate' => $toFloat($request->prate),
            'stc_purchase_product_adhoc_rate' => $toFloat($request->rate),
            'stc_purchase_product_adhoc_unit' => $request->unit ?? '',
            'stc_purchase_product_adhoc_rackid' => $toInt($request->rackid),
            'stc_purchase_product_adhoc_condition' => $request->condition ?? '',
            'stc_purchase_product_adhoc_source' => $request->source ?? '',
            'stc_purchase_product_adhoc_destination' => $request->destination ?? '',
            'stc_purchase_product_adhoc_recievedby' => $request->recievedby ?? '',
            'stc_purchase_product_adhoc_cherrypickby' => $toInt($request->cherrypickby),
            'stc_purchase_product_adhoc_qtytodecrease' => $toFloat($request->qtytodecrease),
            'stc_purchase_product_adhoc_status' => $request->status ?? '',
            'stc_purchase_product_adhoc_remarks' => $request->remarks ?? '',
            'stc_purchase_product_adhoc_updated_by' => auth()->user()->id ?? 0,
            'stc_purchase_product_adhoc_updated_date' => date('Y-m-d H:i:s'),
        ];
        $edit = POAdhoc::where('stc_purchase_product_adhoc_id', $request->id)->update($update);
        if($edit){
            return response()->json(['success' => true, 'message' => 'Record updated successfully!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Record update failed!']);
        }
    }

    // Delete record
    public function delete(Request $request){
        $delete = POAdhoc::destroy($request->id);
        if($delete){
            return response()->json(['success' => true, 'message' => 'Record deleted successfully!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Record deletion failed!']);
        }
    }

    /**
     * HTML summary of stc_shop rows for one PO adhoc (qty per shop / branch).
     */
    private static function formatStcShopBreakdownHtml($shopRows)
    {
        if ($shopRows === null || count($shopRows) === 0) {
            return '<span class="text-muted">—</span>';
        }

        $out = '';
        foreach ($shopRows as $s) {
            $branchLabel = htmlspecialchars(self::stcShopBranchLabel($s), ENT_QUOTES, 'UTF-8');
            $qty = number_format((float) ($s->qty ?? 0), 2, '.', '');
            $rackName = $s->stc_rack_name ?? null;
            $rack = htmlspecialchars(
                ($rackName !== null && $rackName !== '') ? (string) $rackName : ('Rack #' . ($s->rack_id ?? '')),
                ENT_QUOTES,
                'UTF-8'
            );
            $rem = htmlspecialchars((string) ($s->remarks ?? ''), ENT_QUOTES, 'UTF-8');
            $created = htmlspecialchars((string) ($s->created_date ?? ''), ENT_QUOTES, 'UTF-8');

            $out .= '<div class="mb-1 text-left small"><strong>' . $branchLabel . '</strong>: <span class="text-nowrap">' . $qty . '</span>';
            $out .= ' <span class="text-muted">(' . $rack . ')</span>';
            if ($rem !== '') {
                $out .= '<br><span class="text-secondary">' . $rem . '</span>';
            }
            if ($created !== '') {
                $out .= '<br><span class="text-muted" style="font-size:0.85em">' . $created . '</span>';
            }
            $out .= '</div>';
        }

        return $out;
    }
}
