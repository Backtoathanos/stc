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
}
