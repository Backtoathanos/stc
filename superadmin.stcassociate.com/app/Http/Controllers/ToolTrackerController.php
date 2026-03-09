<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StcTooldetails;
use App\StcTooldetailsTrack;

class ToolTrackerController extends Controller
{
    public function show(){
        $data['page_title'] = "Tool Tracker";
        return view('pages.tooltracker', $data);
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
        $columnName = $columnName_arr[$columnIndex]['data'] ?? 'id';
        $columnSortOrder = $order_arr[0]['dir'] ?? 'desc';
        $searchValue = $search_arr['value'] ?? '';

        $searchQuery = function($q) use ($searchValue) {
            $q->where('id', 'like', '%' . $searchValue . '%')
                ->orWhere('unique_id', 'like', '%' . $searchValue . '%')
                ->orWhere('itemdescription', 'like', '%' . $searchValue . '%')
                ->orWhere('machinesrno', 'like', '%' . $searchValue . '%')
                ->orWhere('make', 'like', '%' . $searchValue . '%')
                ->orWhere('tooltype', 'like', '%' . $searchValue . '%')
                ->orWhere('remarks', 'like', '%' . $searchValue . '%');
        };

        $sortColumnMap = ['status_label' => 'id', 'issuedby' => 'id', 'issueddate' => 'id', 'status_badge' => 'id', 'actionData' => 'id'];
        $orderColumn = $sortColumnMap[$columnName] ?? $columnName;

        $totalRecords = StcTooldetails::count();
        $totalRecordswithFilter = StcTooldetails::where($searchQuery)->count();

        $records = StcTooldetails::where($searchQuery)
            ->orderBy($orderColumn, $columnSortOrder)
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();
        $statusLabels = [0 => 'Issued', 1 => 'Accepted'];
        $statusColors = [0 => 'badge bg-warning', 1 => 'badge bg-success'];

        foreach ($records as $record) {
            $latestTrack = StcTooldetailsTrack::where('toolsdetails_id', $record->id)
                ->orderBy('created_date', 'desc')
                ->first();

            $statusLabel = $latestTrack ? ($statusLabels[$latestTrack->status] ?? $latestTrack->status) : 'N/A';
            $statusBadge = $latestTrack 
                ? '<span class="'.($statusColors[$latestTrack->status] ?? 'badge bg-secondary').'">'.$statusLabel.'</span>'
                : '<span class="badge bg-secondary">N/A</span>';

            $issuedby = $latestTrack ? $latestTrack->issuedby : '-';
            $issueddate = $latestTrack && $latestTrack->issueddate 
                ? date('d-m-Y', strtotime($latestTrack->issueddate)) 
                : '-';
            $createdDate = $record->created_date ? date('d-m-Y H:i', strtotime($record->created_date)) : '';

            $trackCount = StcTooldetailsTrack::where('toolsdetails_id', $record->id)->count();
            $trackCountBadge = $trackCount > 0 ? ' <span class="badge badge-light">'.$trackCount.'</span>' : '';

            $id = $record->id;
            $actionData = '
                <a href="javascript:void(0)" class="btn btn-primary btn-sm edit-tool-btn" data-id="'.$id.'" title="Edit Tool"><i class="fas fa-wrench"></i></a>
                <a href="javascript:void(0)" class="btn btn-info btn-sm view-track-btn" data-id="'.$id.'" data-unique-id="'.htmlspecialchars($record->unique_id ?? '').'" title="View Track History"><i class="fas fa-list"></i>'.$trackCountBadge.'</a>
                <a href="javascript:void(0)" class="btn btn-danger btn-sm delete-tool-btn" data-id="'.$id.'" title="Delete Tool"><i class="fas fa-trash"></i></a>
            ';

            $data_arr[] = array(
                'id' => $record->id,
                'unique_id' => $record->unique_id,
                'itemdescription' => $record->itemdescription,
                'machinesrno' => $record->machinesrno,
                'make' => $record->make,
                'tooltype' => $record->tooltype,
                'remarks' => $record->remarks,
                'status_label' => $statusLabel,
                'status_badge' => $statusBadge,
                'issuedby' => $issuedby,
                'issueddate' => $issueddate,
                'created_date' => $createdDate,
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
        $record = StcTooldetails::where('id', $request->id)->first();
        if($record){
            return response()->json(['success' => true, 'data' => $record]);
        } else {
            return response()->json(['success' => false, 'message' => 'Record not found!']);
        }
    }

    public function update(Request $request){
        $update = [
            'unique_id' => $request->unique_id ?? '',
            'itemdescription' => $request->itemdescription ?? '',
            'machinesrno' => $request->machinesrno ?? '',
            'make' => $request->make ?? '',
            'tooltype' => $request->tooltype ?? '',
            'remarks' => $request->remarks ?? '',
        ];
        $edit = StcTooldetails::where('id', $request->id)->update($update);
        if($edit){
            return response()->json(['success' => true, 'message' => 'Record updated successfully!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Record update failed!']);
        }
    }

    public function delete(Request $request){
        StcTooldetailsTrack::where('toolsdetails_id', $request->id)->delete();
        $delete = StcTooldetails::destroy($request->id);
        if($delete){
            return response()->json(['success' => true, 'message' => 'Tool and all tracking records deleted successfully!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Record deletion failed!']);
        }
    }

    public function listTrack(Request $request){
        $toolsdetailsId = $request->toolsdetails_id;
        $records = StcTooldetailsTrack::where('toolsdetails_id', $toolsdetailsId)
            ->orderBy('created_date', 'desc')
            ->get();

        $statusLabels = [0 => 'Issued', 1 => 'Accepted'];
        $data = [];
        foreach ($records as $r) {
            $data[] = [
                'id' => $r->id,
                'issuedby' => $r->issuedby ?? '-',
                'user_id' => $r->user_id ?? '-',
                'status' => $statusLabels[$r->status] ?? $r->status,
                'location' => $r->location ?? '-',
                'issueddate' => $r->issueddate ? date('d-m-Y', strtotime($r->issueddate)) : '-',
                'receivedby' => $r->receivedby ?? '-',
                'created_date' => $r->created_date ? date('d-m-Y H:i', strtotime($r->created_date)) : '-',
            ];
        }
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function getTrack(Request $request){
        $record = StcTooldetailsTrack::where('id', $request->id)->first();
        if($record){
            return response()->json(['success' => true, 'data' => $record]);
        } else {
            return response()->json(['success' => false, 'message' => 'Track record not found!']);
        }
    }

    public function updateTrack(Request $request){
        $update = [
            'issuedby' => $request->issuedby ?? '',
            'user_id' => $request->user_id ?? '',
            'status' => $request->status ?? 0,
            'location' => $request->location ?? '',
            'issueddate' => $request->issueddate ?? null,
            'receivedby' => $request->receivedby ?? '',
        ];
        $edit = StcTooldetailsTrack::where('id', $request->id)->update($update);
        if($edit){
            return response()->json(['success' => true, 'message' => 'Track record updated successfully!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Track record update failed!']);
        }
    }

    public function deleteTrack(Request $request){
        $delete = StcTooldetailsTrack::destroy($request->id);
        if($delete){
            return response()->json(['success' => true, 'message' => 'Track record deleted successfully!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Track record deletion failed!']);
        }
    }

    public function deleteTrackBulk(Request $request){
        $ids = $request->ids ?? [];
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        $ids = array_filter(array_map('intval', $ids));
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No records selected!']);
        }
        $deleted = StcTooldetailsTrack::whereIn('id', $ids)->delete();
        if($deleted > 0){
            return response()->json(['success' => true, 'message' => $deleted . ' track record(s) deleted successfully!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Track record deletion failed!']);
        }
    }
}
