<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use App\BranchSchool;
use App\BranchSchoolFee;

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

    public function feeshow(){
        $data['page_title']="School";
        // $data['getRecord'] = BranchSchool::getSchool();
        return view('pages.branchschoolfee', $data);
    }
    
    // show through ajax
    function feelist(Request $request){

        
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
        $totalRecords = BranchSchoolFee::select('count(*) as allcount')->count();
        $totalRecordswithFilter = BranchSchoolFee::select('count(*) as allcount')->where('stc_school_fee_which_school', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_date', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school.stc_school_user_fullName', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_monthly_fee', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_admission_fee', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_book_charge', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_transportation', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_donation', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_dayboarding', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_neat', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_dstaffsal', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_teacherssal', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_vehiclefuel', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_vehiclemaintenance', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_electricity', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_canteen', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_others', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_cashback', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_expense', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_remarks', 'like', '%' .$searchValue . '%')
        ->leftjoin('stc_school','stc_school.stc_school_user_id','=','stc_school_fee.stc_school_fee_created_by')->count();

        // Fetch records
        $records = BranchSchoolFee::orderBy($columnName,$columnSortOrder)
        ->where('stc_school_fee.stc_school_fee_which_school', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school.stc_school_user_fullName', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_date', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_monthly_fee', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_admission_fee', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_book_charge', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_transportation', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_donation', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_dayboarding', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_neat', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_dstaffsal', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_teacherssal', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_vehiclefuel', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_vehiclemaintenance', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_electricity', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_canteen', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_others', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_cashback', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_expense', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_school_fee.stc_school_fee_remarks', 'like', '%' .$searchValue . '%')
        ->leftjoin('stc_school','stc_school.stc_school_user_id','=','stc_school_fee.stc_school_fee_created_by')
        ->select('stc_school_fee.*', 'stc_school.stc_school_user_fullName')
        ->skip($start)
        ->take($rowperpage)
        ->get();

        $data_arr = array();
        
        foreach($records as $record){
            $id = $record->stc_school_fee_id;
            $date = $record->stc_school_fee_date;
            $school = $record->stc_school_fee_which_school;
            $mclosingvalue = $record->stc_school_fee_monthly_fee;

            $stc_school_fee_id = $record->stc_school_fee_id;
            $stc_school_fee_date = $record->stc_school_fee_date;
            $stc_school_fee_which_school = $record->stc_school_fee_which_school;
            $stc_school_fee_monthly_fee = $record->stc_school_fee_monthly_fee;
            $stc_school_fee_admission_fee = $record->stc_school_fee_admission_fee;
            $stc_school_fee_book_charge = $record->stc_school_fee_book_charge;
            $stc_school_fee_transportation = $record->stc_school_fee_transportation;
            $stc_school_fee_donation = $record->stc_school_fee_donation;
            $stc_school_fee_dayboarding = $record->stc_school_fee_dayboarding;
            $stc_school_fee_neat = $record->stc_school_fee_neat;
            $stc_school_fee_dstaffsal = $record->stc_school_fee_dstaffsal;
            $stc_school_fee_teacherssal = $record->stc_school_fee_teacherssal;
            $stc_school_fee_vehiclefuel = $record->stc_school_fee_vehiclefuel;
            $stc_school_fee_vehiclemaintenance = $record->stc_school_fee_vehiclemaintenance;
            $stc_school_fee_electricity = $record->stc_school_fee_electricity;
            $stc_school_fee_canteen = $record->stc_school_fee_canteen;
            $stc_school_fee_others = $record->stc_school_fee_others;
            $stc_school_fee_cashback = $record->stc_school_fee_cashback;
            $stc_school_fee_expense = $record->stc_school_fee_expense;
            $stc_school_fee_remarks = $record->stc_school_fee_remarks;
            $name = $record->stc_school_user_fullName;

            // $stc_product_id = '<span id="display-stc_product_id'.$id.'">'.$id.'</span>';
            $date = '<span id="display-date'.$id.'" value="'.date('Y-m-d', strtotime($date)).'">'.date('d-m-Y H:i:s', strtotime($date)).'</span>';
            $mclosingvalue = '<span id="display-value'.$id.'" value="'.$mclosingvalue.'">'.number_format($mclosingvalue, 2).'</span>';
            $actionData = '
                <a href="javascript:void(0)" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-modal" onclick=$("#delete_id").val("'.$id.'")><i class="fas fa-trash" title="Delete"></i></a>
            '; 
            $data_arr[] = array(
                "stc_school_fee_id" => $id,
                "stc_school_fee_date" => $date,
                "stc_school_fee_which_school" => $stc_school_fee_which_school,
                "stc_school_fee_monthly_fee" => number_format($stc_school_fee_monthly_fee, 2),
                "stc_school_fee_admission_fee" => number_format($stc_school_fee_admission_fee, 2),
                "stc_school_fee_book_charge" => number_format($stc_school_fee_book_charge, 2),
                "stc_school_fee_transportation" => number_format($stc_school_fee_transportation, 2),
                "stc_school_fee_donation" => number_format($stc_school_fee_donation, 2),
                "stc_school_fee_dayboarding" => number_format($stc_school_fee_dayboarding, 2),
                "stc_school_fee_neat" => number_format($stc_school_fee_neat, 2),
                "stc_school_fee_dstaffsal" => number_format($stc_school_fee_dstaffsal, 2),
                "stc_school_fee_teacherssal" => number_format($stc_school_fee_teacherssal, 2),
                "stc_school_fee_vehiclefuel" => number_format($stc_school_fee_vehiclefuel, 2),
                "stc_school_fee_vehiclemaintenance" => number_format($stc_school_fee_vehiclemaintenance, 2),
                "stc_school_fee_electricity" => number_format($stc_school_fee_electricity, 2),
                "stc_school_fee_canteen" => number_format($stc_school_fee_canteen, 2),
                "stc_school_fee_others" => number_format($stc_school_fee_others, 2),
                "stc_school_fee_cashback" => number_format($stc_school_fee_cashback, 2),
                "stc_school_fee_expense" => number_format($stc_school_fee_expense, 2),
                "stc_school_fee_remarks" => $stc_school_fee_remarks,
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

    // delete through ajax
    function feedelete(Request $request){
        $delete =  BranchSchoolFee::destroy($request->id);
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
