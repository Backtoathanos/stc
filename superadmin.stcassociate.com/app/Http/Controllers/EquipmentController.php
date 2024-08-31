<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Equipment;

class EquipmentController extends Controller
{
    public function show(){
        $data['page_title'] = "Status Down List";
        return view('pages.equipment', $data);
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
        $totalRecords = Equipment::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Equipment::select('count(*) as allcount')
            ->where('id', 'like', '%' . $searchValue . '%')
            ->orwhere('equipment_name', 'like', '%' . $searchValue . '%')
            ->orwhere('area', 'like', '%' . $searchValue . '%')
            ->orwhere('model_no', 'like', '%' . $searchValue . '%')
            ->orwhere('slno', 'like', '%' . $searchValue . '%')
            ->orwhere('equipment_no', 'like', '%' . $searchValue . '%')
            ->orwhere('stc_cust_project_title', 'like', '%' . $searchValue . '%')
            ->leftjoin('stc_cust_project','stc_cust_project_id','=','equipment_details.location')
            ->leftjoin('stc_cust_pro_supervisor','stc_cust_pro_supervisor.stc_cust_pro_supervisor_id','=','equipment_details.created_by')
            ->select('equipment_details.*', 'stc_cust_project.stc_cust_project_title', 'stc_cust_pro_supervisor.stc_cust_pro_supervisor_fullname')
            ->count();

        // Fetch records
        $records = Equipment::orderBy($columnName, $columnSortOrder)
            ->where('id', 'like', '%' . $searchValue . '%')
            ->orwhere('equipment_name', 'like', '%' . $searchValue . '%')
            ->orwhere('area', 'like', '%' . $searchValue . '%')
            ->orwhere('model_no', 'like', '%' . $searchValue . '%')
            ->orwhere('slno', 'like', '%' . $searchValue . '%')
            ->orwhere('equipment_no', 'like', '%' . $searchValue . '%')
            ->orwhere('stc_cust_project_title', 'like', '%' . $searchValue . '%')
            ->leftjoin('stc_cust_project','stc_cust_project_id','=','equipment_details.location')
            ->leftjoin('stc_cust_pro_supervisor','stc_cust_pro_supervisor.stc_cust_pro_supervisor_id','=','equipment_details.created_by')
            ->leftjoin('stc_status_down_list_department','stc_status_down_list_department_id','=','equipment_details.department')
            ->select('equipment_details.*', 'stc_cust_project.stc_cust_project_title', 'stc_cust_pro_supervisor.stc_cust_pro_supervisor_fullname', 'stc_status_down_list_department.stc_status_down_list_department_dept')
            ->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();
        
        foreach ($records as $record) {
            $id = $record->id;
            $actionData = '
                <a href="javascript:void(0)" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-modal" onclick=$("#delete_id").val("'.$id.'")><i class="fas fa-trash" title="Delete"></i></a>
            '; 

            $data_arr[] = array(
                'id' => $record->id,
                'location' => $record->stc_cust_project_title,
                'stc_status_down_list_department_dept' => $record->stc_status_down_list_department_dept,
                'area' => $record->area,
                'model_no' => $record->model_no,
                'capacity' => $record->capacity,
                'equipment_name' => $record->equipment_name,
                'unit_no' => $record->unit_no,
                'stc_cust_pro_supervisor_fullname' => $record->stc_cust_pro_supervisor_fullname,
                'created_date' => $record->created_date,
                'ahu_filter_qty' => $record->ahu_filter_qty,
                'ahu_filter_size' => $record->ahu_filter_size,
                'ahu_filter_type' => $record->ahu_filter_type,
                'ahu_make_name' => $record->ahu_make_name,
                'ahu_v_belt_qty' => $record->ahu_v_belt_qty,
                'ahu_v_belt_size' => $record->ahu_v_belt_size,
                'bearing_size' => $record->bearing_size,
                'blower_bearing_size' => $record->blower_bearing_size,
                'blower_flywheel_size' => $record->blower_flywheel_size,
                'compressor_qty' => $record->compressor_qty,
                'control' => $record->control,
                'coupling_size' => $record->coupling_size,
                'coupling_type' => $record->coupling_type,
                'current_rating_max' => $record->current_rating_max,
                'delta_t' => $record->delta_t,
                'delta_p' => $record->delta_p,
                'each_of_capacity' => $record->each_of_capacity,
                'equipment_serial_no' => $record->equipment_serial_no,
                'fan_blade_qty' => $record->fan_blade_qty,
                'fan_blade_size' => $record->fan_blade_size,
                'filter_qty' => $record->filter_qty,
                'filter_size' => $record->filter_size,
                'header_size' => $record->header_size,
                'inlet_pressure' => $record->inlet_pressure,
                'inlet_temp' => $record->inlet_temp,
                'make_name' => $record->make_name,
                'max_fuse_rating' => $record->max_fuse_rating,
                'max_load' => $record->max_load,
                'min_fuse_rating' => $record->min_fuse_rating,
                'min_load' => $record->min_load,
                'motor_bearing_size' => $record->motor_bearing_size,
                'motor_capacity' => $record->motor_capacity,
                'motor_current_rating' => $record->motor_current_rating,
                'motor_make_name' => $record->motor_make_name,
                'motor_pulley_size' => $record->motor_pulley_size,
                'motor_rpm' => $record->motor_rpm,
                'motor_voltage_rating' => $record->motor_voltage_rating,
                'outlet_pressure' => $record->outlet_pressure,
                'outlet_temp' => $record->outlet_temp,
                'power_factor' => $record->power_factor,
                'pump_head' => $record->pump_head,
                'pump_make_name' => $record->pump_make_name,
                'refrigerant_type' => $record->refrigerant_type,
                'tyre_size' => $record->tyre_size,
                'v_belt_qty' => $record->v_belt_qty,
                'v_belt_size' => $record->v_belt_size,
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
        $delete = Equipment::destroy($request->id);
        if($delete){
            return response()->json(['success' => true, 'message' => 'Record deleted successfully!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Record deletion failed!']);
        }
    } 
}
