<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use App\Requisition;
use App\RequisitionItems;
use App\RequisitionDispatch;

class RequisitionController extends Controller
{
    public function show(){
        $data['page_title']="Requisitions";
        // $data['getRecord'] = BranchSchool::getSchool();
        return view('pages.requisitions', $data);
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
        $totalRecords = Requisition::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Requisition::select('count(*) as allcount')
        ->where('stc_cust_super_requisition_list_id', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_cust_project.stc_cust_project_title', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_cust_pro_supervisor.stc_cust_pro_supervisor_fullname', 'like', '%' .$searchValue . '%')
        ->leftjoin('stc_cust_project','stc_cust_project.stc_cust_project_id','=','stc_cust_super_requisition_list.stc_cust_super_requisition_list_project_id')
        ->leftjoin('stc_cust_pro_supervisor','stc_cust_pro_supervisor.stc_cust_pro_supervisor_id','=','stc_cust_super_requisition_list.stc_cust_super_requisition_list_super_id')
        ->count();

        // Fetch records
        $records = Requisition::orderBy($columnName,$columnSortOrder)
        ->where('stc_cust_super_requisition_list.stc_cust_super_requisition_list_id', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_cust_project.stc_cust_project_title', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_cust_pro_supervisor.stc_cust_pro_supervisor_fullname', 'like', '%' .$searchValue . '%')
        ->leftjoin('stc_cust_project','stc_cust_project.stc_cust_project_id','=','stc_cust_super_requisition_list.stc_cust_super_requisition_list_project_id')
        ->leftjoin('stc_cust_pro_supervisor','stc_cust_pro_supervisor.stc_cust_pro_supervisor_id','=','stc_cust_super_requisition_list.stc_cust_super_requisition_list_super_id')
        ->select('stc_cust_super_requisition_list.*', 'stc_cust_project.stc_cust_project_title', 'stc_cust_pro_supervisor.stc_cust_pro_supervisor_fullname')
        ->skip($start)
        ->take($rowperpage)
        ->get();

        $data_arr = array();
        
        foreach($records as $record){
            $id = $record->stc_cust_super_requisition_list_id;
            $actionData = '
                <a href="javascript:void(0)" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-modal" onclick=$("#delete_id").val("'.$id.'")><i class="fas fa-trash" title="Delete"></i></a>
            '; 
            $data_arr[] = array(
                "stc_cust_super_requisition_list_id" => $record->stc_cust_super_requisition_list_id,
                "stc_cust_super_requisition_list_sdlid" => $record->stc_cust_super_requisition_list_sdlid,
                "stc_cust_project_title" => $record->stc_cust_project_title,
                "stc_cust_pro_supervisor_fullname" => $record->stc_cust_pro_supervisor_fullname,
                "stc_cust_super_requisition_list_status" => $record->stc_cust_super_requisition_list_status,
                "stc_cust_super_requisition_list_approved_by" => $record->stc_cust_super_requisition_list_approved_by,
                "stc_cust_super_requisition_list_date" => $record->stc_cust_super_requisition_list_date,
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
        $delete =  Requisition::destroy($request->id);
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

    // show through ajax
    function itemlist(Request $request){

        
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
        $totalRecords = RequisitionItems::select('count(*) as allcount')->count();
        $totalRecordswithFilter = RequisitionItems::select('count(*) as allcount')
        ->where('stc_cust_super_requisition_list_items_title', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_cust_super_requisition_list_items.stc_cust_super_requisition_list_items_req_id', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_cust_super_requisition_list_items.stc_cust_super_requisition_list_id', 'like', '%' .$searchValue . '%')
        ->count();

        // Fetch records
        $records = RequisitionItems::orderBy($columnName,$columnSortOrder)
        ->where('stc_cust_super_requisition_list_items.stc_cust_super_requisition_list_items_title', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_cust_super_requisition_list_items.stc_cust_super_requisition_list_items_req_id', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_cust_super_requisition_list_items.stc_cust_super_requisition_list_id', 'like', '%' .$searchValue . '%')
        ->select('stc_cust_super_requisition_list_items.*')
        ->skip($start)
        ->take($rowperpage)
        ->get();

        $data_arr = array();
        
        foreach($records as $record){
            $id = $record->stc_cust_super_requisition_list_id;
            $actionData = '
                <a href="javascript:void(0)" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-modal-item" onclick=$("#deletereqitem_id").val("'.$id.'")><i class="fas fa-trash" title="Delete"></i></a>
            '; 
            $data_arr[] = array(
                "stc_cust_super_requisition_list_id" => $record->stc_cust_super_requisition_list_id,
                "stc_cust_super_requisition_list_items_req_id" => $record->stc_cust_super_requisition_list_items_req_id,
                "stc_cust_super_requisition_list_items_title" => $record->stc_cust_super_requisition_list_items_title,
                "stc_cust_super_requisition_list_items_unit" => $record->stc_cust_super_requisition_list_items_unit,
                "stc_cust_super_requisition_list_items_reqqty" => $record->stc_cust_super_requisition_list_items_reqqty,
                "stc_cust_super_requisition_list_items_approved_qty" => $record->stc_cust_super_requisition_list_items_approved_qty,
                "stc_cust_super_requisition_items_finalqty" => $record->stc_cust_super_requisition_items_finalqty,
                "stc_cust_super_requisition_items_priority" => $record->stc_cust_super_requisition_items_priority,
                "stc_cust_super_requisition_list_items_status" => $record->stc_cust_super_requisition_list_items_status,
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
    function itemdelete(Request $request){
        $delete =  RequisitionItems::destroy($request->id);
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

    // show through ajax
    function itemdislist(Request $request){

        
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
        $totalRecords = RequisitionDispatch::select('count(*) as allcount')->count();
        $totalRecordswithFilter = RequisitionDispatch::select('count(*) as allcount')
        ->where('stc_cust_super_requisition_list_items_rec_id', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_cust_super_requisition_list_items_rec.stc_cust_super_requisition_list_items_rec_list_id', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_cust_super_requisition_list_items_rec.stc_cust_super_requisition_list_items_rec_list_item_id', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_cust_super_requisition_list_items_rec.stc_cust_super_requisition_list_items_rec_list_pd_id', 'like', '%' .$searchValue . '%')
        ->count();

        // Fetch records
        $records = RequisitionDispatch::orderBy($columnName,$columnSortOrder)
        ->where('stc_cust_super_requisition_list_items_rec.stc_cust_super_requisition_list_items_rec_id', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_cust_super_requisition_list_items_rec.stc_cust_super_requisition_list_items_rec_list_id', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_cust_super_requisition_list_items_rec.stc_cust_super_requisition_list_items_rec_list_item_id', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_cust_super_requisition_list_items_rec.stc_cust_super_requisition_list_items_rec_list_pd_id', 'like', '%' .$searchValue . '%')
        ->select('stc_cust_super_requisition_list_items_rec.*')
        ->skip($start)
        ->take($rowperpage)
        ->get();

        $data_arr = array();
        
        foreach($records as $record){
            $id = $record->stc_cust_super_requisition_list_items_rec_id;
            $actionData = '
                <a href="javascript:void(0)" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-modal-itemrec" onclick=$("#deletereqitemdis_id").val("'.$id.'")><i class="fas fa-trash" title="Delete"></i></a>
            '; 
            $data_arr[] = array(
                "stc_cust_super_requisition_list_items_rec_id" => $record->stc_cust_super_requisition_list_items_rec_id,
                "stc_cust_super_requisition_list_items_rec_list_id" => $record->stc_cust_super_requisition_list_items_rec_list_id,
                "stc_cust_super_requisition_list_items_rec_list_item_id" => $record->stc_cust_super_requisition_list_items_rec_list_item_id,
                "stc_cust_super_requisition_list_items_rec_list_pd_id" => $record->stc_cust_super_requisition_list_items_rec_list_pd_id,
                "stc_cust_super_requisition_list_items_rec_list_poaid" => $record->stc_cust_super_requisition_list_items_rec_list_poaid,
                "stc_cust_super_requisition_list_items_rec_recqty" => $record->stc_cust_super_requisition_list_items_rec_recqty,
                "stc_cust_super_requisition_list_items_rec_date" => $record->stc_cust_super_requisition_list_items_rec_date,
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
    function itemdisdelete(Request $request){
        $delete =  RequisitionDispatch::destroy($request->id);
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
