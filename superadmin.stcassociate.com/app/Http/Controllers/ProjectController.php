<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use App\Project;

class ProjectController extends Controller
{
    public function show(){
        $data['page_title']="Projects";
        // Get projects for dropdown
        $data['projects'] = Project::select('stc_cust_project_id', 'stc_cust_project_title')
            ->orderBy('stc_cust_project_title', 'asc')
            ->get();
        
        // Get customers that have projects
        $data['customers'] = \DB::table('stc_customer')
            ->join('stc_cust_project', 'stc_customer.stc_customer_id', '=', 'stc_cust_project.stc_cust_project_cust_id')
            ->select('stc_customer.stc_customer_id', 'stc_customer.stc_customer_name')
            ->distinct()
            ->orderBy('stc_customer.stc_customer_name', 'asc')
            ->get();
        
        // Get cities
        $data['cities'] = \DB::table('stc_city')
            ->select('stc_city_id', 'stc_city_name')
            ->orderBy('stc_city_name', 'asc')
            ->get();
        
        // Get states
        $data['states'] = \DB::table('stc_state')
            ->select('stc_state_id', 'stc_state_name')
            ->orderBy('stc_state_name', 'asc')
            ->get();
        
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
                <a href="javascript:void(0)" class="btn btn-warning btn-sm edit-project" data-id="'.$id.'" title="Edit"><i class="fas fa-edit"></i></a> 
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

    // Get single project for editing
    function getProject(Request $request){
        try {
            $id = $request->get('id');
            
            $project = Project::leftJoin('stc_customer','stc_customer.stc_customer_id','=','stc_cust_project.stc_cust_project_cust_id')
                ->leftJoin('stc_city','stc_city.stc_city_id','=','stc_cust_project.stc_cust_project_city_id')
                ->leftJoin('stc_state','stc_state.stc_state_id','=','stc_cust_project.stc_cust_project_state_id')
                ->select('stc_cust_project.*', 'stc_customer.stc_customer_name', 'stc_city.stc_city_name', 'stc_state.stc_state_name')
                ->where('stc_cust_project.stc_cust_project_id', $id)
                ->first();
            
            if($project) {
                return response()->json([
                    'success' => true,
                    'data' => $project
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Project not found'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading project: ' . $e->getMessage()
            ]);
        }
    }
    
    // Save project (add/edit)
    function saveProject(Request $request){
        try {
            $id = $request->get('id');
            $data = [
                'stc_cust_project_cust_id' => $request->get('customer_id'),
                'stc_cust_project_title' => $request->get('title'),
                'stc_cust_project_refr' => $request->get('reference_number'),
                'stc_cust_project_address' => $request->get('address'),
                'stc_cust_project_city_id' => $request->get('city_id'),
                'stc_cust_project_state_id' => $request->get('state_id'),
                'stc_cust_project_responsive_person' => $request->get('responsive_person'),
                'stc_cust_project_supervis_qty' => $request->get('supervisor_qty'),
                'stc_cust_project_beg_date' => $request->get('beg_date'),
                'stc_cust_project_end_date' => $request->get('end_date'),
                'stc_cust_project_beg_budget' => $request->get('beg_budget'),
                'stc_cust_project_status' => $request->get('status', '1'),
                'stc_cust_project_editable_mincount' => $request->get('editable_mincount'),
                'stc_cust_project_editable_maxcount' => $request->get('editable_maxcount')
            ];
            
            if($id) {
                // Update existing project
                $project = Project::find($id);
                if($project) {
                    $project->update($data);
                    return response()->json([
                        'success' => true,
                        'message' => 'Project updated successfully'
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Project not found'
                    ]);
                }
            } else {
                // Create new project
                $data['stc_cust_project_createdby'] = auth()->user()->id ?? 1;
                $data['stc_cust_project_date'] = date('Y-m-d H:i:s');
                Project::create($data);
                return response()->json([
                    'success' => true,
                    'message' => 'Project created successfully'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving project: ' . $e->getMessage()
            ]);
        }
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
    
    // Get departments list (DataTables format)
    function departmentsList(Request $request){
        try {
            ## Read value
            $draw = $request->get('draw');
            $start = $request->get("start");
            $rowperpage = $request->get("length"); // Rows display per page

            $columnIndex_arr = $request->get('order');
            $columnName_arr = $request->get('columns');
            $order_arr = $request->get('order');
            $search_arr = $request->get('search');

            $columnIndex = isset($columnIndex_arr[0]['column']) ? $columnIndex_arr[0]['column'] : 0;
            $columnName = isset($columnName_arr[$columnIndex]['data']) ? $columnName_arr[$columnIndex]['data'] : 'stc_status_down_list_department_id';
            $columnSortOrder = isset($order_arr[0]['dir']) ? $order_arr[0]['dir'] : 'desc';
            $searchValue = isset($search_arr['value']) ? $search_arr['value'] : '';
            
            // Map column names for sorting (if sorting by location_id column, sort by project title)
            $sortColumnMap = [
                'stc_status_down_list_department_loc_id' => 'stc_cust_project.stc_cust_project_title',
                'stc_status_down_list_department_id' => 'stc_status_down_list_department.stc_status_down_list_department_id',
                'stc_status_down_list_department_date' => 'stc_status_down_list_department.stc_status_down_list_department_date',
                'stc_status_down_list_department_location' => 'stc_status_down_list_department.stc_status_down_list_department_location',
                'stc_status_down_list_department_dept' => 'stc_status_down_list_department.stc_status_down_list_department_dept',
                'stc_status_down_list_department_status' => 'stc_status_down_list_department.stc_status_down_list_department_status',
                'stc_status_down_list_department_created_by' => 'stc_agents.stc_agents_name'
            ];
            
            $sortColumn = isset($sortColumnMap[$columnName]) ? $sortColumnMap[$columnName] : 'stc_status_down_list_department.stc_status_down_list_department_id';

            // Total records
            $totalRecords = \DB::table('stc_status_down_list_department')
                ->leftJoin('stc_cust_project', 'stc_status_down_list_department.stc_status_down_list_department_loc_id', '=', 'stc_cust_project.stc_cust_project_id')
                ->leftJoin('stc_agents', 'stc_status_down_list_department.stc_status_down_list_department_created_by', '=', 'stc_agents.stc_agents_id')
                ->count();
            
            // Total records with filter
            $totalRecordswithFilter = \DB::table('stc_status_down_list_department')
                ->leftJoin('stc_cust_project', 'stc_status_down_list_department.stc_status_down_list_department_loc_id', '=', 'stc_cust_project.stc_cust_project_id')
                ->leftJoin('stc_agents', 'stc_status_down_list_department.stc_status_down_list_department_created_by', '=', 'stc_agents.stc_agents_id')
                ->where(function($query) use ($searchValue) {
                    if(!empty($searchValue)) {
                        $query->where('stc_status_down_list_department.stc_status_down_list_department_id', 'like', '%' . $searchValue . '%')
                              ->orWhere('stc_status_down_list_department.stc_status_down_list_department_location', 'like', '%' . $searchValue . '%')
                              ->orWhere('stc_status_down_list_department.stc_status_down_list_department_dept', 'like', '%' . $searchValue . '%')
                              ->orWhere('stc_status_down_list_department.stc_status_down_list_department_created_by', 'like', '%' . $searchValue . '%')
                              ->orWhere('stc_cust_project.stc_cust_project_title', 'like', '%' . $searchValue . '%')
                              ->orWhere('stc_agents.stc_agents_name', 'like', '%' . $searchValue . '%');
                    }
                })
                ->count();

            // Fetch records
            $query = \DB::table('stc_status_down_list_department')
                ->leftJoin('stc_cust_project', 'stc_status_down_list_department.stc_status_down_list_department_loc_id', '=', 'stc_cust_project.stc_cust_project_id')
                ->leftJoin('stc_agents', 'stc_status_down_list_department.stc_status_down_list_department_created_by', '=', 'stc_agents.stc_agents_id')
                ->select(
                    'stc_status_down_list_department.stc_status_down_list_department_id',
                    'stc_status_down_list_department.stc_status_down_list_department_date',
                    'stc_status_down_list_department.stc_status_down_list_department_loc_id',
                    'stc_status_down_list_department.stc_status_down_list_department_location',
                    'stc_status_down_list_department.stc_status_down_list_department_dept',
                    'stc_status_down_list_department.stc_status_down_list_department_status',
                    'stc_status_down_list_department.stc_status_down_list_department_created_by',
                    'stc_cust_project.stc_cust_project_title',
                    'stc_agents.stc_agents_name'
                );

            if(!empty($searchValue)) {
                $query->where(function($q) use ($searchValue) {
                    $q->where('stc_status_down_list_department.stc_status_down_list_department_id', 'like', '%' . $searchValue . '%')
                      ->orWhere('stc_status_down_list_department.stc_status_down_list_department_location', 'like', '%' . $searchValue . '%')
                      ->orWhere('stc_status_down_list_department.stc_status_down_list_department_dept', 'like', '%' . $searchValue . '%')
                      ->orWhere('stc_status_down_list_department.stc_status_down_list_department_created_by', 'like', '%' . $searchValue . '%')
                      ->orWhere('stc_cust_project.stc_cust_project_title', 'like', '%' . $searchValue . '%')
                      ->orWhere('stc_agents.stc_agents_name', 'like', '%' . $searchValue . '%');
                });
            }

            $records = $query->orderBy($sortColumn, $columnSortOrder)
                ->skip($start)
                ->take($rowperpage)
                ->get();

            $data_arr = array();
            
            foreach($records as $record){
                $data_arr[] = array(
                    "stc_status_down_list_department_id" => $record->stc_status_down_list_department_id,
                    "stc_status_down_list_department_date" => $record->stc_status_down_list_department_date ? date('d-m-Y', strtotime($record->stc_status_down_list_department_date)) : '-',
                    "stc_status_down_list_department_loc_id" => $record->stc_cust_project_title ? $record->stc_cust_project_title : ($record->stc_status_down_list_department_loc_id ? $record->stc_status_down_list_department_loc_id : '-'),
                    "stc_status_down_list_department_location" => $record->stc_status_down_list_department_location,
                    "stc_status_down_list_department_dept" => $record->stc_status_down_list_department_dept,
                    "stc_status_down_list_department_status" => $record->stc_status_down_list_department_status,
                    "stc_status_down_list_department_created_by" => $record->stc_agents_name ? $record->stc_agents_name : ($record->stc_status_down_list_department_created_by ? $record->stc_status_down_list_department_created_by : '-')
                );
            }

            $response = array(
                "draw" => intval($draw),
                "iTotalRecords" => $totalRecords,
                "iTotalDisplayRecords" => $totalRecordswithFilter,
                "aaData" => $data_arr
            );

            return json_encode($response);
        } catch (\Exception $e) {
            return response()->json([
                'draw' => intval($request->get('draw', 1)),
                'iTotalRecords' => 0,
                'iTotalDisplayRecords' => 0,
                'aaData' => [],
                'error' => 'Error loading departments: ' . $e->getMessage()
            ]);
        }
    }
    
    // Get single department
    function getDepartment(Request $request){
        try {
            $id = $request->get('id');
            
            $department = \DB::table('stc_status_down_list_department')
                ->where('stc_status_down_list_department_id', $id)
                ->first();
            
            if($department) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'stc_status_down_list_department_id' => $department->stc_status_down_list_department_id,
                        'stc_status_down_list_department_date' => $department->stc_status_down_list_department_date,
                        'stc_status_down_list_department_loc_id' => $department->stc_status_down_list_department_loc_id,
                        'stc_status_down_list_department_location' => $department->stc_status_down_list_department_location,
                        'stc_status_down_list_department_dept' => $department->stc_status_down_list_department_dept,
                        'stc_status_down_list_department_status' => $department->stc_status_down_list_department_status,
                        'stc_status_down_list_department_created_by' => $department->stc_status_down_list_department_created_by
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Department not found'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error loading department: ' . $e->getMessage()
            ]);
        }
    }
    
    // Save department (add/edit)
    function saveDepartment(Request $request){
        try {
            $id = $request->get('id');
            $projectId = $request->get('project_id');
            $location = $request->get('location');
            $departmentName = $request->get('department_name');
            $status = $request->get('status', 1);
            
            if(empty($projectId) || empty($location) || empty($departmentName)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Project, Location, and Department Name are required'
                ]);
            }
            
            $data = [
                'stc_status_down_list_department_loc_id' => $projectId,
                'stc_status_down_list_department_location' => $location,
                'stc_status_down_list_department_dept' => $departmentName,
                'stc_status_down_list_department_status' => $status
            ];
            
            if(empty($id)) {
                // Add new
                $data['stc_status_down_list_department_date'] = date('Y-m-d');
                $data['stc_status_down_list_department_created_by'] = auth()->user()->id ?? 0;
                $id = \DB::table('stc_status_down_list_department')->insertGetId($data);
                $message = 'Department added successfully!';
            } else {
                // Update existing
                \DB::table('stc_status_down_list_department')
                    ->where('stc_status_down_list_department_id', $id)
                    ->update($data);
                $message = 'Department updated successfully!';
            }
            
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving department: ' . $e->getMessage()
            ]);
        }
    }
    
    // Delete department
    function deleteDepartment(Request $request){
        try {
            $id = $request->get('id');
            
            $deleted = \DB::table('stc_status_down_list_department')
                ->where('stc_status_down_list_department_id', $id)
                ->delete();
            
            if($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Department deleted successfully!'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Department not found!'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting department: ' . $e->getMessage()
            ]);
        }
    }
    
    // Bulk change status
    function bulkStatusDepartments(Request $request){
        try {
            $ids = $request->get('ids', []);
            $status = $request->get('status');
            
            if(empty($ids) || !is_array($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No departments selected'
                ]);
            }
            
            $updated = \DB::table('stc_status_down_list_department')
                ->whereIn('stc_status_down_list_department_id', $ids)
                ->update(['stc_status_down_list_department_status' => $status]);
            
            return response()->json([
                'success' => true,
                'message' => $updated . ' department(s) status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ]);
        }
    }
    
    // Bulk delete departments
    function bulkDeleteDepartments(Request $request){
        try {
            $ids = $request->get('ids', []);
            
            if(empty($ids) || !is_array($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No departments selected'
                ]);
            }
            
            $deleted = \DB::table('stc_status_down_list_department')
                ->whereIn('stc_status_down_list_department_id', $ids)
                ->delete();
            
            return response()->json([
                'success' => true,
                'message' => $deleted . ' department(s) deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting departments: ' . $e->getMessage()
            ]);
        }
    }
}
