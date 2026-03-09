<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StcItemTracker;

class PPETrackerController extends Controller
{
    public function show(){
        $data['page_title'] = "PPE Tracker";
        $data['ppeTypes'] = StcItemTracker::select('stc_item_tracker_toppe')
            ->whereNotNull('stc_item_tracker_toppe')
            ->where('stc_item_tracker_toppe', '!=', '')
            ->distinct()
            ->orderBy('stc_item_tracker_toppe')
            ->pluck('stc_item_tracker_toppe');
        $data['units'] = StcItemTracker::select('stc_item_tracker_unit')
            ->whereNotNull('stc_item_tracker_unit')
            ->where('stc_item_tracker_unit', '!=', '')
            ->distinct()
            ->orderBy('stc_item_tracker_unit')
            ->pluck('stc_item_tracker_unit');
        $data['userNames'] = StcItemTracker::select('stc_item_tracker_user_id')
            ->whereNotNull('stc_item_tracker_user_id')
            ->where('stc_item_tracker_user_id', '!=', '')
            ->distinct()
            ->orderBy('stc_item_tracker_user_id')
            ->pluck('stc_item_tracker_user_id');
        return view('pages.ppetracker', $data);
    }

    public function list(Request $request)
    {
        $draw = $request->get('draw', 1);
        $start = $request->get("start", 0);
        $rowperpage = $request->get("length", 10) ?: 10;

        $columnIndex_arr = $request->get('order') ?? [];
        $columnName_arr = $request->get('columns') ?? [];
        $order_arr = $request->get('order') ?? [];
        $search_arr = $request->get('search') ?? [];

        $columnIndex = $columnIndex_arr[0]['column'] ?? 0;
        $columnName = $columnName_arr[$columnIndex]['data'] ?? 'stc_item_tracker_id';
        $columnSortOrder = $order_arr[0]['dir'] ?? 'desc';
        $searchValue = $search_arr['value'] ?? '';

        $sortColumnMap = [
            'user_name' => 'stc_item_tracker.stc_item_tracker_user_id',
            'created_by_name' => 'stc_user.stc_user_name',
            'actionData' => 'stc_item_tracker.stc_item_tracker_id'
        ];
        $orderColumn = $sortColumnMap[$columnName] ?? 'stc_item_tracker.' . $columnName;

        $query = StcItemTracker::select('stc_item_tracker.*', 'stc_user.stc_user_name as created_by_name')
            ->leftJoin('stc_user', 'stc_user.stc_user_id', '=', 'stc_item_tracker.stc_item_tracker_createdby');

        $searchQuery = function($q) use ($searchValue) {
            $q->where('stc_item_tracker.stc_item_tracker_id', 'like', '%' . $searchValue . '%')
                ->orWhere('stc_item_tracker.stc_item_tracker_user_id', 'like', '%' . $searchValue . '%')
                ->orWhere('stc_item_tracker.stc_item_tracker_toppe', 'like', '%' . $searchValue . '%')
                ->orWhere('stc_item_tracker.stc_item_tracker_qty', 'like', '%' . $searchValue . '%')
                ->orWhere('stc_item_tracker.stc_item_tracker_unit', 'like', '%' . $searchValue . '%')
                ->orWhere('stc_item_tracker.stc_item_tracker_issuedate', 'like', '%' . $searchValue . '%')
                ->orWhere('stc_item_tracker.stc_item_tracker_validity', 'like', '%' . $searchValue . '%')
                ->orWhere('stc_item_tracker.stc_item_tracker_remarks', 'like', '%' . $searchValue . '%')
                ->orWhere('stc_user.stc_user_name', 'like', '%' . $searchValue . '%');
        };

        $totalRecords = StcItemTracker::count();
        $totalRecordswithFilter = StcItemTracker::select('stc_item_tracker.stc_item_tracker_id')
            ->leftJoin('stc_user', 'stc_user.stc_user_id', '=', 'stc_item_tracker.stc_item_tracker_createdby')
            ->where($searchQuery)
            ->count();

        $records = $query->where($searchQuery)
            ->orderBy($orderColumn, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();

        foreach ($records as $record) {
            $validity = $record->stc_item_tracker_validity == 1 ? '1 month' : $record->stc_item_tracker_validity . ' months';
            $issuedateFormatted = $record->stc_item_tracker_issuedate ? date('d-m-Y', strtotime($record->stc_item_tracker_issuedate)) : '';
            $id = $record->stc_item_tracker_id;
            $actionData = '
                <a href="javascript:void(0)" class="btn btn-primary btn-sm edit-modal-btn" data-toggle="modal" data-target="#edit-modal" id="'.$id.'"><i class="fas fa-edit" title="Edit"></i></a>
                <a href="javascript:void(0)" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-modal" onclick=$("#delete_id").val("'.$id.'")><i class="fas fa-trash" title="Delete"></i></a>
            ';

            $data_arr[] = array(
                'stc_item_tracker_id' => $record->stc_item_tracker_id,
                'stc_item_tracker_user_id' => $record->stc_item_tracker_user_id,
                'user_name' => $record->stc_item_tracker_user_id ?? '-',
                'stc_item_tracker_toppe' => $record->stc_item_tracker_toppe,
                'stc_item_tracker_qty' => $record->stc_item_tracker_qty,
                'stc_item_tracker_unit' => $record->stc_item_tracker_unit,
                'stc_item_tracker_issuedate' => $issuedateFormatted,
                'stc_item_tracker_validity' => $validity,
                'stc_item_tracker_remarks' => $record->stc_item_tracker_remarks,
                'stc_item_tracker_createdby' => $record->created_by_name ?? $record->stc_item_tracker_createdby,
                'stc_item_tracker_created_date' => $record->stc_item_tracker_created_date,
                "actionData" => $actionData
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return response()->json($response);
    }

    public function get(Request $request){
        $record = StcItemTracker::where('stc_item_tracker_id', $request->id)->first();
        if($record){
            return response()->json(['success' => true, 'data' => $record]);
        } else {
            return response()->json(['success' => false, 'message' => 'Record not found!']);
        }
    }

    public function update(Request $request){
        $toInt = function($value) {
            return ($value === '' || $value === null) ? 0 : (int)$value;
        };
        $toFloat = function($value) {
            return ($value === '' || $value === null) ? 0 : (float)$value;
        };

        $update = [
            'stc_item_tracker_user_id' => $request->user_name ?? '',
            'stc_item_tracker_toppe' => $request->toppe ?? '',
            'stc_item_tracker_qty' => $toFloat($request->qty),
            'stc_item_tracker_unit' => $request->unit ?? '',
            'stc_item_tracker_issuedate' => $request->issuedate ?? null,
            'stc_item_tracker_validity' => $toInt($request->validity),
            'stc_item_tracker_remarks' => $request->remarks ?? '',
        ];
        $edit = StcItemTracker::where('stc_item_tracker_id', $request->id)->update($update);
        if($edit){
            return response()->json(['success' => true, 'message' => 'Record updated successfully!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Record update failed!']);
        }
    }

    public function delete(Request $request){
        $delete = StcItemTracker::destroy($request->id);
        if($delete){
            return response()->json(['success' => true, 'message' => 'Record deleted successfully!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Record deletion failed!']);
        }
    }
}
