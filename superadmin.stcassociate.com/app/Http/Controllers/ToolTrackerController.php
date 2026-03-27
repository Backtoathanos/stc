<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StcTooldetails;
use App\StcTooldetailsTrack;
use App\Project;

class ToolTrackerController extends Controller
{
    public function show(){
        $data['page_title'] = "Tool Tracker";
        $data['supervisors'] = \DB::table('stc_cust_pro_supervisor')
            ->where('stc_cust_pro_supervisor_status', 1)
            ->orderBy('stc_cust_pro_supervisor_fullname', 'asc')
            ->get(['stc_cust_pro_supervisor_id as id', 'stc_cust_pro_supervisor_fullname as name']);
        return view('pages.tooltracker', $data);
    }

    public function getProjectsByUser(Request $request){
        $userId = $request->user_id;
        if (!empty($userId)) {
            $projectIds = \DB::table('stc_cust_pro_attend_supervise')
                ->where('stc_cust_pro_attend_supervise_super_id', $userId)
                ->pluck('stc_cust_pro_attend_supervise_pro_id')
                ->toArray();
            if (!empty($projectIds)) {
                $projects = Project::select('stc_cust_project_id', 'stc_cust_project_title')
                    ->whereIn('stc_cust_project_id', $projectIds)
                    ->orderBy('stc_cust_project_title', 'asc')
                    ->get();
            } else {
                $projects = Project::select('stc_cust_project_id', 'stc_cust_project_title')
                    ->orderBy('stc_cust_project_title', 'asc')
                    ->get();
            }
        } else {
            $projects = Project::select('stc_cust_project_id', 'stc_cust_project_title')
                ->orderBy('stc_cust_project_title', 'asc')
                ->get();
        }
        return response()->json(['success' => true, 'data' => $projects]);
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

        foreach ($records as $record) {
            $latestTrack = StcTooldetailsTrack::where('toolsdetails_id', $record->id)
                ->orderBy('created_date', 'desc')
                ->first();

            $statusLabel = $latestTrack ? $this->trackStatusLabel($latestTrack->status) : 'N/A';
            $statusBadge = $latestTrack
                ? '<span class="'.$this->trackStatusBadgeClass($latestTrack->status).'">'.$statusLabel.'</span>'
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
        $records = StcTooldetailsTrack::where('stc_tooldetails_track.toolsdetails_id', $toolsdetailsId)
            ->leftJoin('stc_cust_pro_supervisor', 'stc_cust_pro_supervisor.stc_cust_pro_supervisor_id', '=', 'stc_tooldetails_track.user_id')
            ->leftJoin('stc_cust_project', 'stc_cust_project.stc_cust_project_id', '=', 'stc_tooldetails_track.project_id')
            ->select(
                'stc_tooldetails_track.*',
                'stc_cust_pro_supervisor.stc_cust_pro_supervisor_fullname as supervisor_name',
                'stc_cust_project.stc_cust_project_title as project_name'
            )
            ->orderBy('stc_tooldetails_track.created_date', 'desc')
            ->get();

        $data = [];
        foreach ($records as $r) {
            $data[] = [
                'id' => $r->id,
                'issuedby' => $r->issuedby ?? '-',
                'user_id' => $r->user_id ?? '-',
                'supervisor_name' => $r->supervisor_name ?? '-',
                'project_name' => $r->project_name ?? '-',
                'status' => $this->trackStatusLabel($r->status),
                'status_badge' => $this->trackStatusBadgeHtml($r->status),
                'location' => $r->location ?? '-',
                'issueddate' => $r->issueddate ? date('d-m-Y', strtotime($r->issueddate)) : '-',
                'receivedby' => $r->receivedby ?? '-',
                'created_date' => $r->created_date ? date('d-m-Y H:i', strtotime($r->created_date)) : '-',
            ];
        }
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function listTrackNoProject(Request $request){
        $records = StcTooldetailsTrack::where(function($q) {
                $q->whereNull('project_id')->orWhere('project_id', 0);
            })
            ->leftJoin('stc_cust_pro_supervisor', 'stc_cust_pro_supervisor.stc_cust_pro_supervisor_id', '=', 'stc_tooldetails_track.user_id')
            ->leftJoin('stc_cust_project', 'stc_cust_project.stc_cust_project_id', '=', 'stc_tooldetails_track.project_id')
            ->leftJoin('stc_tooldetails', 'stc_tooldetails.id', '=', 'stc_tooldetails_track.toolsdetails_id')
            ->select(
                'stc_tooldetails_track.*',
                'stc_cust_pro_supervisor.stc_cust_pro_supervisor_fullname as supervisor_name',
                'stc_cust_project.stc_cust_project_title as project_name',
                'stc_tooldetails.unique_id as tool_unique_id'
            )
            ->orderBy('stc_tooldetails_track.created_date', 'desc')
            ->get();

        $data = [];
        foreach ($records as $r) {
            $data[] = [
                'id' => $r->id,
                'toolsdetails_id' => $r->toolsdetails_id,
                'tool_unique_id' => $r->tool_unique_id ?? '-',
                'issuedby' => $r->issuedby ?? '-',
                'user_id' => $r->user_id ?? '-',
                'supervisor_name' => $r->supervisor_name ?? '-',
                'project_name' => $r->project_name ?? '-',
                'status' => $this->trackStatusLabel($r->status),
                'status_badge' => $this->trackStatusBadgeHtml($r->status),
                'location' => $r->location ?? '-',
                'issueddate' => $r->issueddate ? date('d-m-Y', strtotime($r->issueddate)) : '-',
                'receivedby' => $r->receivedby ?? '-',
                'created_date' => $r->created_date ? date('d-m-Y H:i', strtotime($r->created_date)) : '-',
            ];
        }
        return response()->json(['success' => true, 'data' => $data]);
    }

    public function getTrack(Request $request){
        $record = StcTooldetailsTrack::where('stc_tooldetails_track.id', $request->id)
            ->leftJoin('stc_cust_pro_supervisor', 'stc_cust_pro_supervisor.stc_cust_pro_supervisor_id', '=', 'stc_tooldetails_track.user_id')
            ->select('stc_tooldetails_track.*', 'stc_cust_pro_supervisor.stc_cust_pro_supervisor_fullname as supervisor_name')
            ->first();
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
            'project_id' => $request->project_id ?: null,
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

    /**
     * Track status: 0 = Pending, 1 = Received, 2 = Returned (aligned with stc_vikings / sub-agent tools flow).
     */
    private function trackStatusLabel($status)
    {
        $s = (int) $status;
        $map = [0 => 'Pending', 1 => 'Received', 2 => 'Returned'];
        return isset($map[$s]) ? $map[$s] : (string) $status;
    }

    private function trackStatusBadgeClass($status)
    {
        $s = (int) $status;
        $map = [0 => 'badge bg-secondary', 1 => 'badge bg-success', 2 => 'badge bg-warning text-dark'];
        return isset($map[$s]) ? $map[$s] : 'badge bg-secondary';
    }

    private function trackStatusBadgeHtml($status)
    {
        return '<span class="'.$this->trackStatusBadgeClass($status).'">'.$this->trackStatusLabel($status).'</span>';
    }
}
