<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use App\BranchSchool;

class SchoolController extends Controller
{
    public function show(){
        $data['page_title']="School";
        // $data['getRecord'] = BranchSchool::getSchool();
        return view('pages.branchschool', $data);
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
        $totalRecords = BranchSchool::select('count(*) as allcount')->count();
        $totalRecordswithFilter = BranchSchool::select('count(*) as allcount')->where('stc_school_month_closing_value', 'like', '%' .$searchValue . '%')->count();

        // Fetch records
        $records = BranchSchool::orderBy($columnName,$columnSortOrder)
        ->where('stc_school_month_closing.stc_school_month_closing_value', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school.stc_school_user_fullName', 'like', '%' .$searchValue . '%')
        ->leftjoin('stc_school','stc_school.stc_school_user_id','=','stc_school_month_closing.stc_school_month_closing_created_by')
        ->select('stc_school_month_closing.*', 'stc_school.stc_school_user_fullName')
        ->skip($start)
        ->take($rowperpage)
        ->get();

        $data_arr = array();
        
        foreach($records as $record){
            $id = $record->stc_school_month_closing_id;
            $date = $record->stc_school_month_closing_date;
            $school = $record->stc_school_fee_which_school;
            $mclosingvalue = $record->stc_school_month_closing_value;
            $name = $record->stc_school_user_fullName;

            // $stc_product_id = '<span id="display-stc_product_id'.$id.'">'.$id.'</span>';
            $date = '<span id="display-date'.$id.'" value="'.date('Y-m-d', strtotime($date)).'">'.date('d-m-Y H:i:s', strtotime($date)).'</span>';
            $mclosingvalue = '<span id="display-value'.$id.'" value="'.$mclosingvalue.'">'.number_format($mclosingvalue, 2).'</span>';
            $actionData = '
                <a href="javascript:void(0)" class="btn btn-primary btn-sm edit-modal-btn" data-toggle="modal" data-target="#edit-modal" id="'.$id.'" ><i class="fas fa-edit" title="Edit"></i></a>
                <a href="javascript:void(0)" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-modal" onclick=$("#delete_id").val("'.$id.'")><i class="fas fa-trash" title="Delete"></i></a>
            '; 
            $data_arr[] = array(
                "stc_school_month_closing_id" => $id,
                "stc_school_month_closing_date" => $date,
                "stc_school_fee_which_school" => $school,
                "stc_school_month_closing_value" => $mclosingvalue,
                "stc_school_user_fullName" => $name,
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

    // update through ajax
    function update(Request $request){
        $datea=date("H:i:s");
        $date=$request->date;
        $date=$date.' '.$datea;
        $update = [
            'stc_school_month_closing_value' => $request->value,
            'stc_school_month_closing_date' => $date
        ];
        $edit = BranchSchool::where('stc_school_month_closing_id', $request->id)->update($update);
        if($edit){
            $response = [
                'status'=>'ok',
                'success'=>true,
                'message'=>'Record updated succesfully!'
            ];
            return $response;
        }else{
            $response = [
                'status'=>'ok',
                'success'=>false,
                'message'=>'Record updated failed!'
            ];
            return $response;
        }
    } 

    // delete through ajax
    function delete(Request $request){
        $delete =  BranchSchool::destroy($request->id);
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
