<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\City;

class CityController extends Controller
{
    public function show(){
        $data['page_title']="City";
        return view('pages.city', $data);
    }

    // show through ajax
    function list(Request $request){
        // return City::all();

        
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
        $totalRecords = City::select('count(*) as allcount')->count();
        $totalRecordswithFilter = City::select('count(*) as allcount')->where('stc_city_name', 'like', '%' .$searchValue . '%')->count();

        // Fetch records
        $records = City::orderBy($columnName,$columnSortOrder)
        ->where('stc_city.stc_city_name', 'like', '%' .$searchValue . '%')
        ->select('stc_city.*')
        ->skip($start)
        ->take($rowperpage)
        ->get();

        $data_arr = array();
        
        foreach($records as $record){
            $id = $record->stc_city_id;
            $name = $record->stc_city_name;
            $status = $record->stc_city_status==1 ? 'Active' : 'In-active';
            
            $stc_city_id = '<span id="display-'.$id.'">'.$id.'</span>';
            $stc_city_name = '<span id="display-name'.$id.'">'.$name.'</span>';
            $stc_city_status = '<span id="display-status'.$id.'">'.$status.'</span>';
            $actionData = '
                <a href="javascript:void(0)" class="btn btn-primary btn-sm edit-modal-btn" data-toggle="modal" data-target="#edit-modal" id="'.$id.'" ><i class="fas fa-edit" title="Edit"></i></a>
                <a href="javascript:void(0)" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-modal" onclick=$("#delete_id").val("'.$id.'")><i class="fas fa-trash" title="Delete"></i></a>
            '; 
            $data_arr[] = array(
            "stc_city_id" => $stc_city_id,
            "stc_city_name" => $stc_city_name,
            "stc_city_status" => $stc_city_status,
            "actionData" => $actionData
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        echo json_encode($response);
        exit;
    }
    
    // update through ajax
    function create(Request $request){
        $validatedData = $request->validate([
          'name' => 'required|unique:stc_city,stc_city_name'
        ]);

        $insert = [
            'stc_city_name' => $request->name,
            'stc_city_status'=> $request->status,
        ];
        $create = City::create($insert);
        if($create){
            $response = [
                'status'=>'ok',
                'success'=>true,
                'message'=>'Record saved succesfully!'
            ];
            return $response;
        }else{
            $response = [
                'status'=>'ok',
                'success'=>false,
                'message'=>'Record saved failed!'
            ];
            return $response;
        }
    } 

    // update through ajax
    function update(Request $request){
        $update = [
            'stc_city_name' => $request->name,
            'stc_city_status'=> $request->status,
        ];
        $edit = City::where('stc_city_id', $request->id)->update($update);
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
        $delete =  City::destroy($request->id);
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
