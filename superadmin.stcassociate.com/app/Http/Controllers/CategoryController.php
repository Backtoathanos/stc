<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
    public function show(){
        $data['page_title']="Category";
        return view('pages.category', $data);
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
        $totalRecords = Category::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Category::select('count(*) as allcount')->where('stc_cat_name', 'like', '%' .$searchValue . '%')->count();

        // Fetch records
        $records = Category::orderBy($columnName,$columnSortOrder)
        ->where('stc_category.stc_cat_name', 'like', '%' .$searchValue . '%')
        ->select('stc_category.*')
        ->skip($start)
        ->take($rowperpage)
        ->get();

        $data_arr = array();
        
        foreach($records as $record){
            $id = $record->stc_cat_id;
            $name = $record->stc_cat_name;
            $status = $record->stc_cat_status==1 ? 'Active' : 'In-active';
            
            $stc_cat_id = '<span id="display-'.$id.'">'.$id.'</span>';
            $stc_cat_name = '<span id="display-name'.$id.'">'.$name.'</span>';
            $stc_cat_status = '<span id="display-status'.$id.'">'.$status.'</span>';
            $actionData = '
                <a href="javascript:void(0)" class="btn btn-primary btn-sm edit-modal-btn" data-toggle="modal" data-target="#edit-modal" id="'.$id.'" ><i class="fas fa-edit" title="Edit"></i></a>
                <a href="javascript:void(0)" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-modal" onclick=$("#delete_id").val("'.$id.'")><i class="fas fa-trash" title="Delete"></i></a>
            '; 
            $data_arr[] = array(
            "stc_cat_id" => $stc_cat_id,
            "stc_cat_name" => $stc_cat_name,
            "stc_cat_status" => $stc_cat_status,
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
          'name' => 'required|unique:stc_category,stc_cat_name'
        ]);

        $insert = [
            'stc_cat_name' => $request->name,
            'stc_cat_status'=> $request->status,
        ];
        $create = Category::create($insert);
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
            'stc_cat_name' => $request->name,
            'stc_cat_status'=> $request->status,
        ];
        $edit = Category::where('stc_cat_id', $request->id)->update($update);
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
        $delete =  Category::destroy($request->id);
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
