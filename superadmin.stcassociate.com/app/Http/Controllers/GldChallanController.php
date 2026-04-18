<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GldChallanController extends Controller
{
    private function baseQuery()
    {
        return DB::table('gld_challan as GC')
            ->leftJoin('stc_product as SP', 'GC.product_id', '=', 'SP.stc_product_id')
            ->leftJoin('gld_customer as GCU', 'GC.cust_id', '=', 'GCU.gld_customer_id')
            ->leftJoin('stc_trading_user as TU', 'GC.created_by', '=', 'TU.stc_trading_user_id')
            ->leftJoin('stc_trading_user as AG', 'GC.agent_id', '=', 'AG.stc_trading_user_id')
            ->leftJoin('stc_purchase_product_adhoc as PA', 'GC.adhoc_id', '=', 'PA.stc_purchase_product_adhoc_id')
            ->leftJoin('stc_product as SPA', 'PA.stc_purchase_product_adhoc_productid', '=', 'SPA.stc_product_id')
            ->leftJoin('stc_rack as RK', 'PA.stc_purchase_product_adhoc_rackid', '=', 'RK.stc_rack_id')
            ->leftJoin('stc_cust_super_requisition_list as REQ', 'GC.requisition_id', '=', 'REQ.stc_cust_super_requisition_list_id')
            ->leftJoin('stc_cust_project as PROJ', 'REQ.stc_cust_super_requisition_list_project_id', '=', 'PROJ.stc_cust_project_id')
            ->select(
                'GC.*',
                'SP.stc_product_name',
                'GCU.gld_customer_title',
                'TU.stc_trading_user_name as creator_name',
                'AG.stc_trading_user_name as agent_name',
                'PA.stc_purchase_product_adhoc_itemdesc as adhoc_itemdesc',
                'PA.stc_purchase_product_adhoc_productid as adhoc_line_product_id',
                'SPA.stc_product_name as adhoc_line_product_name',
                'RK.stc_rack_name as adhoc_rack_name',
                'REQ.stc_cust_super_requisition_list_sdlid as req_sdlid',
                'PROJ.stc_cust_project_title as project_title'
            );
    }

    public function show()
    {
        $data['page_title'] = 'GLD';
        $productNames = [];
        foreach (
            DB::table('stc_product')->orderBy('stc_product_name')->get(['stc_product_id', 'stc_product_name']) as $row
        ) {
            $productNames[(string) $row->stc_product_id] = $row->stc_product_name;
        }
        $data['gld_product_names'] = $productNames;
        $adhocLabels = [];
        foreach (
            DB::table('stc_purchase_product_adhoc')
                ->orderByDesc('stc_purchase_product_adhoc_id')
                ->limit(3000)
                ->get(['stc_purchase_product_adhoc_id', 'stc_purchase_product_adhoc_itemdesc']) as $a
        ) {
            $adhocLabels[(string) $a->stc_purchase_product_adhoc_id] =
                '#' . $a->stc_purchase_product_adhoc_id . ' — ' . Str::limit((string) ($a->stc_purchase_product_adhoc_itemdesc ?? ''), 80);
        }
        $data['gld_adhoc_labels'] = $adhocLabels;
        $data['customers'] = DB::table('gld_customer')
            ->orderBy('gld_customer_title')
            ->get(['gld_customer_id', 'gld_customer_title']);
        $data['requisitions'] = DB::table('stc_cust_super_requisition_list')
            ->orderByDesc('stc_cust_super_requisition_list_id')
            ->limit(3000)
            ->get(['stc_cust_super_requisition_list_id', 'stc_cust_super_requisition_list_sdlid']);
        $data['tradingUsers'] = DB::table('stc_trading_user')
            ->orderBy('stc_trading_user_name')
            ->get(['stc_trading_user_id', 'stc_trading_user_name']);

        $data['gld_filter_creators'] = DB::table('gld_challan as GC')
            ->leftJoin('stc_trading_user as TU', 'GC.created_by', '=', 'TU.stc_trading_user_id')
            ->whereNotNull('GC.created_by')
            ->where('GC.created_by', '!=', 0)
            ->select('GC.created_by', 'TU.stc_trading_user_name')
            ->distinct()
            ->orderBy('TU.stc_trading_user_name')
            ->orderBy('GC.created_by')
            ->get();

        $data['gld_filter_racks'] = DB::table('gld_challan as GC')
            ->join('stc_purchase_product_adhoc as PA', 'GC.adhoc_id', '=', 'PA.stc_purchase_product_adhoc_id')
            ->join('stc_rack as RK', 'PA.stc_purchase_product_adhoc_rackid', '=', 'RK.stc_rack_id')
            ->where('PA.stc_purchase_product_adhoc_rackid', '>', 0)
            ->select('RK.stc_rack_id', 'RK.stc_rack_name')
            ->distinct()
            ->orderBy('RK.stc_rack_name')
            ->get();

        return view('pages.gld', $data);
    }

    /**
     * Racks that appear on challan → adhoc lines, optionally limited to a creator.
     */
    public function filterRacks(Request $request)
    {
        $createdBy = $request->get('created_by');

        $q = DB::table('gld_challan as GC')
            ->join('stc_purchase_product_adhoc as PA', 'GC.adhoc_id', '=', 'PA.stc_purchase_product_adhoc_id')
            ->join('stc_rack as RK', 'PA.stc_purchase_product_adhoc_rackid', '=', 'RK.stc_rack_id')
            ->where('PA.stc_purchase_product_adhoc_rackid', '>', 0)
            ->select('RK.stc_rack_id', 'RK.stc_rack_name')
            ->distinct();

        if ($createdBy !== null && $createdBy !== '') {
            $q->where('GC.created_by', (int) $createdBy);
        }

        $rows = $q->orderBy('RK.stc_rack_name')->get();

        return response()->json(['success' => true, 'data' => $rows]);
    }

    public function list(Request $request)
    {
        $draw = (int) $request->get('draw', 1);
        $start = (int) $request->get('start', 0);
        $rowperpage = (int) ($request->get('length', 15) ?: 15);

        $columnIndex_arr = $request->get('order') ?? [];
        $columnName_arr = $request->get('columns') ?? [];
        $order_arr = $request->get('order') ?? [];
        $search_arr = $request->get('search') ?? [];

        $columnIndex = $columnIndex_arr[0]['column'] ?? 0;
        $columnName = $columnName_arr[$columnIndex]['data'] ?? 'id';
        $columnSortOrder = $order_arr[0]['dir'] ?? 'desc';
        $searchValue = $search_arr['value'] ?? '';

        $filterCreatedBy = $request->input('filter_created_by');
        $filterRackId = $request->input('filter_rack_id');

        $sortMap = [
            'id' => 'GC.id',
            'product_label' => 'SP.stc_product_name',
            'adhoc_label' => 'PA.stc_purchase_product_adhoc_itemdesc',
            'customer_label' => 'GCU.gld_customer_title',
            'requisition_label' => 'GC.requisition_id',
            'challan_number' => 'GC.challan_number',
            'bill_number' => 'GC.bill_number',
            'qty' => 'GC.qty',
            'rate' => 'GC.rate',
            'discount' => 'GC.discount',
            'paid_amount' => 'GC.paid_amount',
            'payment_status' => 'GC.payment_status',
            'agent_label' => 'AG.stc_trading_user_name',
            'status_badge' => 'GC.status',
            'created_date_fmt' => 'GC.created_date',
            'creator_label' => 'TU.stc_trading_user_name',
            'adhoc_product_rack' => 'SPA.stc_product_name',
        ];
        $orderColumn = $sortMap[$columnName] ?? 'GC.id';

        $makeFilteredQuery = function () use ($searchValue, $filterCreatedBy, $filterRackId) {
            $q = $this->applyGldListSearch($this->baseQuery(), $searchValue);
            if ($filterCreatedBy !== null && $filterCreatedBy !== '') {
                $q->where('GC.created_by', (int) $filterCreatedBy);
            }
            if ($filterRackId !== null && $filterRackId !== '') {
                $q->where('RK.stc_rack_id', (int) $filterRackId);
            }

            return $q;
        };

        $totalRecords = DB::table('gld_challan')->count();

        $totalRecordswithFilter = $makeFilteredQuery()->count();

        $records = $makeFilteredQuery()
            ->orderBy($orderColumn, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = [];
        foreach ($records as $r) {
            $id = $r->id;
            $productLabel = $r->stc_product_name
                ? e($r->stc_product_name) . ' <small class="text-muted">#' . (int) $r->product_id . '</small>'
                : '<span class="text-muted">#' . (int) $r->product_id . '</span>';
            $adhocLabel = $r->adhoc_itemdesc
                ? e($r->adhoc_itemdesc) . ' <small class="text-muted">#' . (int) $r->adhoc_id . '</small>'
                : '<span class="text-muted">#' . (int) $r->adhoc_id . '</span>';
            $customerLabel = $r->gld_customer_title
                ? e($r->gld_customer_title) . ' <small class="text-muted">#' . (int) $r->cust_id . '</small>'
                : '<span class="text-muted">#' . (int) $r->cust_id . '</span>';

            $reqParts = [];
            if (!empty($r->requisition_id)) {
                $reqParts[] = 'Req #' . (int) $r->requisition_id;
            }
            if (!empty($r->req_sdlid)) {
                $reqParts[] = 'SDL ' . e($r->req_sdlid);
            }
            if (!empty($r->project_title)) {
                $reqParts[] = e($r->project_title);
            }
            $requisitionLabel = count($reqParts) ? implode(' · ', $reqParts) : '<span class="text-muted">—</span>';

            $adhocProdName = $r->adhoc_line_product_name ?: ($r->adhoc_itemdesc ?: '');
            $adhocProdNameEsc = $adhocProdName !== '' ? e($adhocProdName) : '<span class="text-muted">—</span>';
            $adhocPid = isset($r->adhoc_line_product_id) ? (int) $r->adhoc_line_product_id : 0;
            $adhocAid = (int) $r->adhoc_id;
            $rackEsc = ! empty($r->adhoc_rack_name) ? e($r->adhoc_rack_name) : '<span class="text-muted">—</span>';
            $adhocProductRack = '<div class="gld-adhoc-cell text-left">'
                . '<div class="font-weight-bold">' . $adhocProdNameEsc . '</div>'
                . '<small class="text-muted d-block">Product ID: ' . $adhocPid . '</small>'
                . '<small class="text-muted d-block">Adhoc ID: ' . $adhocAid . '</small>'
                . '<small class="text-muted d-block">Rack: ' . $rackEsc . '</small>'
                . '</div>';

            $createdFmt = $r->created_date ? date('d-m-Y H:i', strtotime($r->created_date)) : '';
            $creatorLabel = $r->creator_name ? e($r->creator_name) : '<span class="text-muted">#' . (int) $r->created_by . '</span>';
            $agentLabel = $r->agent_name ? e($r->agent_name) : ($r->agent_id ? '<span class="text-muted">#' . (int) $r->agent_id . '</span>' : '—');

            $statusBadge = $this->statusBadgeHtml((int) $r->status);

            $actionData = '
                <div class="btn-group btn-group-sm">
                  <button type="button" class="btn btn-outline-secondary gld-view-btn" data-id="' . $id . '" title="View"><i class="fas fa-eye"></i></button>
                  <button type="button" class="btn btn-outline-primary gld-edit-btn" data-id="' . $id . '" title="Edit"><i class="fas fa-edit"></i></button>
                  <button type="button" class="btn btn-outline-danger gld-delete-btn" data-id="' . $id . '" title="Delete"><i class="fas fa-trash"></i></button>
                </div>';

            $data_arr[] = [
                'id' => $id,
                'product_label' => $productLabel,
                'adhoc_label' => $adhocLabel,
                'customer_label' => $customerLabel,
                'requisition_label' => $requisitionLabel,
                'challan_number' => $r->challan_number ?? '',
                'bill_number' => $r->bill_number ?? '',
                'adhoc_product_rack' => $adhocProductRack,
                'qty' => $r->qty !== null ? number_format((float) $r->qty, 2) : '',
                'rate' => $r->rate !== null ? number_format((float) $r->rate, 2) : '',
                'discount' => $r->discount !== null ? number_format((float) $r->discount, 2) : '',
                'paid_amount' => $r->paid_amount !== null ? number_format((float) $r->paid_amount, 2) : '',
                'payment_status' => e($r->payment_status ?? ''),
                'agent_label' => $agentLabel,
                'status_badge' => $statusBadge,
                'created_date_fmt' => $createdFmt,
                'creator_label' => $creatorLabel,
                'actionData' => $actionData,
            ];
        }

        return response()->json([
            'draw' => $draw,
            'iTotalRecords' => $totalRecords,
            'iTotalDisplayRecords' => $totalRecordswithFilter,
            'aaData' => $data_arr,
        ]);
    }

    public function get(Request $request)
    {
        $id = (int) $request->get('id', 0);
        if ($id < 1) {
            return response()->json(['success' => false, 'message' => 'Invalid id']);
        }

        $row = $this->baseQuery()->where('GC.id', $id)->first();
        if (!$row) {
            return response()->json(['success' => false, 'message' => 'Record not found']);
        }

        return response()->json(['success' => true, 'data' => $row]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
            'adhoc_id' => 'nullable|integer',
            'cust_id' => 'required|integer',
            'requisition_id' => 'nullable|integer',
            'challan_number' => 'nullable|string|max:190',
            'bill_number' => 'nullable|string|max:190',
            'qty' => 'required|numeric',
            'rate' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'paid_amount' => 'nullable|numeric',
            'payment_status' => 'nullable|string|max:190',
            'agent_id' => 'nullable|integer',
            'status' => 'nullable|integer',
            'created_by' => 'required|integer',
        ]);

        DB::table('gld_challan')->insert([
            'product_id' => (int) $request->product_id,
            'adhoc_id' => (int) ($request->adhoc_id ?? 0),
            'cust_id' => (int) $request->cust_id,
            'requisition_id' => (int) ($request->requisition_id ?? 0),
            'challan_number' => $request->challan_number ?? '',
            'bill_number' => $request->bill_number ?? '',
            'qty' => $request->qty,
            'rate' => $request->rate ?? 0,
            'discount' => $request->discount ?? 0,
            'paid_amount' => $request->paid_amount ?? 0,
            'payment_status' => $request->payment_status ?? '',
            'agent_id' => (int) ($request->agent_id ?? 0),
            'status' => (int) ($request->status ?? 0),
            'created_date' => now(),
            'created_by' => (int) $request->created_by,
        ]);

        return response()->json(['success' => true, 'message' => 'GLD challan created successfully.']);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'product_id' => 'required|integer',
            'adhoc_id' => 'nullable|integer',
            'cust_id' => 'required|integer',
            'requisition_id' => 'nullable|integer',
            'challan_number' => 'nullable|string|max:190',
            'bill_number' => 'nullable|string|max:190',
            'qty' => 'required|numeric',
            'rate' => 'nullable|numeric',
            'discount' => 'nullable|numeric',
            'paid_amount' => 'nullable|numeric',
            'payment_status' => 'nullable|string|max:190',
            'agent_id' => 'nullable|integer',
            'status' => 'nullable|integer',
        ]);

        $id = (int) $request->id;
        if (! DB::table('gld_challan')->where('id', $id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Record not found.']);
        }

        DB::table('gld_challan')->where('id', $id)->update([
            'product_id' => (int) $request->product_id,
            'adhoc_id' => (int) ($request->adhoc_id ?? 0),
            'cust_id' => (int) $request->cust_id,
            'requisition_id' => (int) ($request->requisition_id ?? 0),
            'challan_number' => $request->challan_number ?? '',
            'bill_number' => $request->bill_number ?? '',
            'qty' => $request->qty,
            'rate' => $request->rate ?? 0,
            'discount' => $request->discount ?? 0,
            'paid_amount' => $request->paid_amount ?? 0,
            'payment_status' => $request->payment_status ?? '',
            'agent_id' => (int) ($request->agent_id ?? 0),
            'status' => (int) ($request->status ?? 0),
        ]);

        return response()->json(['success' => true, 'message' => 'GLD challan updated successfully.']);
    }

    public function delete(Request $request)
    {
        $id = (int) $request->get('id', 0);
        if ($id < 1) {
            return response()->json(['success' => false, 'message' => 'Invalid id']);
        }

        $deleted = DB::table('gld_challan')->where('id', $id)->delete();
        if ($deleted) {
            return response()->json(['success' => true, 'message' => 'Record deleted successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Could not delete record.']);
    }

    /**
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Query\Builder
     */
    private function applyGldListSearch($query, string $searchValue)
    {
        if ($searchValue === '') {
            return $query;
        }
        $term = '%' . $searchValue . '%';

        return $query->where(function ($w) use ($term) {
            $w->where('GC.id', 'like', $term)
                ->orWhere('GC.product_id', 'like', $term)
                ->orWhere('GC.adhoc_id', 'like', $term)
                ->orWhere('GC.cust_id', 'like', $term)
                ->orWhere('GC.requisition_id', 'like', $term)
                ->orWhere('GC.challan_number', 'like', $term)
                ->orWhere('GC.bill_number', 'like', $term)
                ->orWhere('GC.qty', 'like', $term)
                ->orWhere('GC.rate', 'like', $term)
                ->orWhere('GC.discount', 'like', $term)
                ->orWhere('GC.paid_amount', 'like', $term)
                ->orWhere('GC.payment_status', 'like', $term)
                ->orWhere('GC.status', 'like', $term)
                ->orWhere('SP.stc_product_name', 'like', $term)
                ->orWhere('GCU.gld_customer_title', 'like', $term)
                ->orWhere('TU.stc_trading_user_name', 'like', $term)
                ->orWhere('AG.stc_trading_user_name', 'like', $term)
                ->orWhere('PA.stc_purchase_product_adhoc_itemdesc', 'like', $term)
                ->orWhere('SPA.stc_product_name', 'like', $term)
                ->orWhere('RK.stc_rack_name', 'like', $term)
                ->orWhere('PROJ.stc_cust_project_title', 'like', $term);
        });
    }

    private function statusBadgeHtml(int $status): string
    {
        $map = [
            0 => ['Pending / draft', 'secondary'],
            1 => ['Challan stage', 'info'],
            2 => ['Intermediate', 'warning'],
            3 => ['Billed / closed', 'success'],
        ];
        $pair = $map[$status] ?? [(string) $status, 'secondary'];

        $extra = $pair[1] === 'warning' ? ' text-dark' : '';

        return '<span class="badge bg-' . $pair[1] . $extra . '">' . e($pair[0]) . '</span>';
    }
}
