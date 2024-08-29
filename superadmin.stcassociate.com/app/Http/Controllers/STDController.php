<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\STD;

class STDController extends Controller
{
    public function show(){
        $data['page_title'] = "Status Down List";
        return view('pages.std', $data);
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
        $totalRecords = STD::select('count(*) as allcount')->count();
        $totalRecordswithFilter = STD::select('count(*) as allcount')
            ->where('stc_status_down_list_id', 'like', '%' . $searchValue . '%')
            ->count();

        // Fetch records
        $records = STD::orderBy($columnName, $columnSortOrder)
            ->where('stc_status_down_list_id', 'like', '%' . $searchValue . '%')
            ->orwhere('stc_status_down_list_plocation', 'like', '%' . $searchValue . '%')
            ->orwhere('stc_cust_project_title', 'like', '%' . $searchValue . '%')
            ->orwhere('stc_status_down_list_plocation', 'like', '%' . $searchValue . '%')
            ->orwhere('stc_status_down_list_plocation', 'like', '%' . $searchValue . '%')
            ->orwhere('stc_status_down_list_plocation', 'like', '%' . $searchValue . '%')
            ->leftjoin('stc_cust_project','stc_cust_project_id','=','stc_status_down_list.stc_status_down_list_location')
            ->leftjoin('stc_customer_pump_details','stc_cpumpd_id','=','stc_status_down_list.stc_status_down_list_equipment_number')
            ->select('stc_status_down_list.*', 'stc_cust_project.stc_cust_project_title', 'stc_customer_pump_details.stc_cpumpd_equipment_number')
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();
        
        foreach ($records as $record) {
            $id = $record->stc_status_down_list_id;
            $actionData = '
                <a href="javascript:void(0)" class="btn btn-info btn-sm" data-toggle="modal" data-target="#edit-modal" onclick="editRecord('.$id.')"><i class="fas fa-edit" title="Edit"></i></a>
                <a href="javascript:void(0)" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-modal" onclick=$("#delete_id").val("'.$id.'")><i class="fas fa-trash" title="Delete"></i></a>
            '; 

            $data_arr[] = array(
                "stc_status_down_list_id" => $record->stc_status_down_list_id,
                "stc_status_down_list_date" => $record->stc_status_down_list_date,
                "stc_status_down_list_plocation" => $record->stc_status_down_list_plocation,
                "stc_status_down_list_location" => $record->stc_cust_project_title,
                "stc_status_down_list_equipment_number" => $record->stc_cpumpd_equipment_number,
                "stc_status_down_list_equipment_status" => $record->stc_status_down_list_equipment_status,
                "stc_status_down_list_manpower_req" => $record->stc_status_down_list_manpower_req,
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

    // Delete record
    public function delete(Request $request){
        $delete = STD::destroy($request->id);
        if($delete){
            return response()->json(['success' => true, 'message' => 'Record deleted successfully!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Record deletion failed!']);
        }
    } 
}
