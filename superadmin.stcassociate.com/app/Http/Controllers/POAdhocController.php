<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        // Total records
        $totalRecords = POAdhoc::select('count(*) as allcount')->count();
        $totalRecordswithFilter = POAdhoc::select('count(*) as allcount')
            ->where(function($query) use ($searchValue) {
                $query->where('stc_purchase_product_adhoc_id', 'like', '%' . $searchValue . '%')
                    ->orwhere('stc_purchase_product_adhoc_itemdesc', 'like', '%' . $searchValue . '%')
                    ->orwhere('stc_purchase_product_adhoc_qty', 'like', '%' . $searchValue . '%')
                    ->orwhere('stc_purchase_product_adhoc_rate', 'like', '%' . $searchValue . '%')
                    ->orwhere('stc_purchase_product_adhoc_status', 'like', '%' . $searchValue . '%');
            })
            ->count();

        // Fetch records
        $records = POAdhoc::orderBy($columnName, $columnSortOrder)
            ->where(function($query) use ($searchValue) {
                $query->where('stc_purchase_product_adhoc_id', 'like', '%' . $searchValue . '%')
                    ->orwhere('stc_purchase_product_adhoc_itemdesc', 'like', '%' . $searchValue . '%')
                    ->orwhere('stc_purchase_product_adhoc_qty', 'like', '%' . $searchValue . '%')
                    ->orwhere('stc_purchase_product_adhoc_rate', 'like', '%' . $searchValue . '%')
                    ->orwhere('stc_purchase_product_adhoc_status', 'like', '%' . $searchValue . '%');
            })
            ->skip($start)
            ->take($rowperpage)
            ->get();

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
                <a href="javascript:void(0)" class="btn btn-primary btn-sm edit-modal-btn" data-toggle="modal" data-target="#edit-modal" id="'.$id.'"><i class="fas fa-edit" title="Edit"></i></a>
                <a href="javascript:void(0)" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-modal" onclick=$("#delete_id").val("'.$id.'")><i class="fas fa-trash" title="Delete"></i></a>
            '; 

            $data_arr[] = array(
                'stc_purchase_product_adhoc_id' => $record->stc_purchase_product_adhoc_id,
                'stc_purchase_product_adhoc_productid' => $record->stc_purchase_product_adhoc_productid,
                'stc_purchase_product_adhoc_itemdesc' => $record->stc_purchase_product_adhoc_itemdesc,
                'stc_purchase_product_adhoc_qty' => $record->stc_purchase_product_adhoc_qty,
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
}

