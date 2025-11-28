<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use App\Project;

class ProjectController extends Controller
{
    public function show(){
        $data['page_title']="Projects";
        return view('pages.projects', $data);
    }

    // show through ajax
    function list(Request $request){

        
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
        $totalRecords = Project::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Project::select('count(*) as allcount')->where('stc_cust_project_title', 'like', '%' .$searchValue . '%')->count();

        // Fetch records
        $records = Project::orderBy($columnName,$columnSortOrder)
        ->where('stc_cust_project.stc_cust_project_title', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_cust_project.stc_cust_project_refr', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_cust_project.stc_cust_project_address', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_customer.stc_customer_name', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_agents.stc_agents_name', 'like', '%' .$searchValue . '%')
        ->leftjoin('stc_customer','stc_customer.stc_customer_id','=','stc_cust_project.stc_cust_project_cust_id')
        ->leftjoin('stc_agents','stc_agents.stc_agents_id','=','stc_cust_project.stc_cust_project_createdby')
        ->leftjoin('stc_city','stc_city.stc_city_id','=','stc_cust_project.stc_cust_project_city_id')
        ->leftjoin('stc_state','stc_state.stc_state_id','=','stc_cust_project.stc_cust_project_state_id')
        ->select('stc_cust_project.*', 'stc_customer.stc_customer_name', 'stc_agents.stc_agents_name', 'stc_city.stc_city_name', 'stc_state.stc_state_name')
        ->skip($start)
        ->take($rowperpage)
        ->get();

        $data_arr = array();
        
        foreach($records as $record){
            $id = $record->stc_cust_project_id;
            $actionData = '
                <a href="javascript:void(0)" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-modal" onclick=$("#delete_id").val("'.$id.'")><i class="fas fa-trash" title="Delete"></i></a>
            '; 
            $data_arr[] = array(
                "stc_cust_project_id" => $record->stc_cust_project_id,
                "stc_customer_name" => $record->stc_customer_name,
                "stc_cust_project_title" => $record->stc_cust_project_title,
                "stc_cust_project_refr" => $record->stc_cust_project_refr,
                "stc_cust_project_address" => $record->stc_cust_project_address,
                "stc_city_name" => $record->stc_city_name,
                "stc_state_name" => $record->stc_state_name,
                "stc_cust_project_responsive_person" => $record->stc_cust_project_responsive_person,
                "stc_cust_project_supervis_qty" => $record->stc_cust_project_supervis_qty,
                "stc_cust_project_beg_date" => $record->stc_cust_project_beg_date,
                "stc_cust_project_end_date" => $record->stc_cust_project_end_date,
                "stc_cust_project_beg_budget" => $record->stc_cust_project_beg_budget,
                "stc_cust_project_status" => $record->stc_cust_project_status,
                "stc_cust_project_editable_mincount" => $record->stc_cust_project_editable_mincount,
                "stc_cust_project_editable_maxcount" => $record->stc_cust_project_editable_maxcount,
                "stc_agents_name" => $record->stc_agents_name,
                "stc_cust_project_date" => $record->stc_cust_project_date,
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

    // delete through ajax
    function delete(Request $request){
        $delete =  Project::destroy($request->id);
        if($delete){
            $response = [
                'status'=>'ok',
                'success'=>true,
                'message'=>'Record deleted succesfully!'
            ];
            return $response;
        }else{
            $response = [
                'status'=>'ok',
                'success'=>false,
                'message'=>'Record deleted failed!'
            ];
            return $response;
        }
    }

    // get collaborations list
    function collaborationsList(Request $request){
        try {
            $search = $request->get('search', '');
            $page = $request->get('page', 1);
            $sort = $request->get('sort', 'project_title');
            $sortDir = $request->get('sort_dir', 'asc');
            $perPage = $request->get('per_page', 25);
            
            // Validate per_page value
            $allowedPerPage = [25, 50, 100, 500];
            if(!in_array((int)$perPage, $allowedPerPage)) {
                $perPage = 25;
            }
            
            $offset = ($page - 1) * $perPage;

            $query = \DB::table('stc_cust_project_collaborate')
                ->leftJoin('stc_cust_project', 'stc_cust_project_collaborate.stc_cust_project_collaborate_projectid', '=', 'stc_cust_project.stc_cust_project_id')
                ->leftJoin('stc_agents as manager', 'stc_cust_project_collaborate.stc_cust_project_collaborate_managerid', '=', 'manager.stc_agents_id')
                ->leftJoin('stc_agents as collaborator', 'stc_cust_project_collaborate.stc_cust_project_collaborate_teamid', '=', 'collaborator.stc_agents_id')
                ->select(
                    'stc_cust_project_collaborate.stc_cust_project_collaborate_id',
                    'stc_cust_project.stc_cust_project_title',
                    'manager.stc_agents_name as manager_name',
                    'collaborator.stc_agents_name as collaborator_name',
                    'stc_cust_project_collaborate.stc_cust_project_collaborate_status',
                    'stc_cust_project_collaborate.stc_cust_project_collaborate_created_date'
                )
                ->where('stc_cust_project_collaborate.stc_cust_project_collaborate_status', '1');

            // Apply search filter
            if(!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('stc_cust_project.stc_cust_project_title', 'like', '%' . $search . '%')
                      ->orWhere('manager.stc_agents_name', 'like', '%' . $search . '%')
                      ->orWhere('collaborator.stc_agents_name', 'like', '%' . $search . '%');
                });
            }

            // Get total count
            $totalRecords = $query->count();

            // Map sort column to database column
            $sortColumns = [
                'project_title' => 'stc_cust_project.stc_cust_project_title',
                'manager_name' => 'manager.stc_agents_name',
                'collaborator_name' => 'collaborator.stc_agents_name',
                'status' => 'stc_cust_project_collaborate.stc_cust_project_collaborate_status',
                'created_date' => 'stc_cust_project_collaborate.stc_cust_project_collaborate_created_date'
            ];

            $sortColumn = isset($sortColumns[$sort]) ? $sortColumns[$sort] : 'stc_cust_project.stc_cust_project_title';
            $sortDirection = in_array(strtolower($sortDir), ['asc', 'desc']) ? strtolower($sortDir) : 'asc';

            // Apply pagination and ordering
            $collaborations = $query->orderBy($sortColumn, $sortDirection)
                ->offset($offset)
                ->limit($perPage)
                ->get();

            $data = [];
            foreach($collaborations as $collab) {
                $data[] = [
                    'stc_cust_project_collaborate_id' => $collab->stc_cust_project_collaborate_id,
                    'stc_cust_project_title' => $collab->stc_cust_project_title,
                    'manager_name' => $collab->manager_name,
                    'collaborator_name' => $collab->collaborator_name,
                    'stc_cust_project_collaborate_status' => $collab->stc_cust_project_collaborate_status,
                    'stc_cust_project_collaborate_created_date' => $collab->stc_cust_project_collaborate_created_date ? date('d-m-Y', strtotime($collab->stc_cust_project_collaborate_created_date)) : 'N/A'
                ];
            }

            $totalPages = ceil($totalRecords / $perPage);

            $response = [
                'success' => true,
                'data' => $data,
                'pagination' => [
                    'current_page' => (int)$page,
                    'per_page' => $perPage,
                    'total_records' => $totalRecords,
                    'total_pages' => $totalPages
                ]
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Error loading collaborations: ' . $e->getMessage()
            ];
            return response()->json($response);
        }
    }

    // remove collaborator
    function removeCollaborator(Request $request){
        try {
            $collabId = $request->id;
            $deleted = \DB::table('stc_cust_project_collaborate')
                ->where('stc_cust_project_collaborate_id', $collabId)
                ->update(['stc_cust_project_collaborate_status' => '0']);

            if($deleted) {
                $response = [
                    'success' => true,
                    'message' => 'Collaborator removed successfully!'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Collaborator not found or already removed!'
                ];
            }
            return response()->json($response);
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Error removing collaborator: ' . $e->getMessage()
            ];
            return response()->json($response);
        }
    }

    // bulk remove collaborators
    function bulkRemoveCollaborators(Request $request){
        try {
            $ids = $request->ids;
            
            if(empty($ids) || !is_array($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No collaborations selected!'
                ]);
            }

            $deleted = \DB::table('stc_cust_project_collaborate')
                ->whereIn('stc_cust_project_collaborate_id', $ids)
                ->update(['stc_cust_project_collaborate_status' => '0']);

            if($deleted > 0) {
                $response = [
                    'success' => true,
                    'message' => $deleted . ' collaboration(s) removed successfully!'
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => 'No collaborations were removed. They may have already been removed.'
                ];
            }
            return response()->json($response);
        } catch (\Exception $e) {
            $response = [
                'success' => false,
                'message' => 'Error removing collaborations: ' . $e->getMessage()
            ];
            return response()->json($response);
        }
    } 
}
