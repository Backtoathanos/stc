<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inventory;

class InventoryController extends Controller
{
    public function show(){
        $data['page_title']="Inventory";
        return view('pages.inventory', $data);
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
        $totalRecords = Inventory::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Inventory::select('count(*) as allcount')
        ->leftjoin('stc_product','stc_product.stc_product_id','=','stc_item_inventory.stc_item_inventory_pd_id')
        ->where('stc_product_name', 'like', '%' .$searchValue . '%')
        ->count();

        // Fetch records
        $records = Inventory::orderBy($columnName,$columnSortOrder)
        ->where('stc_product.stc_product_name', 'like', '%' .$searchValue . '%')
        ->orWhere('stc_product.stc_product_desc', 'like', '%' .$searchValue . '%')
        ->orWhere('stc_item_inventory.stc_item_inventory_pd_qty', 'like', '%' .$searchValue . '%')
        ->leftjoin('stc_product','stc_product.stc_product_id','=','stc_item_inventory.stc_item_inventory_pd_id')
        ->leftjoin('stc_category','stc_category.stc_cat_id','=','stc_product.stc_product_cat_id')
        ->leftjoin('stc_sub_category','stc_sub_category.stc_sub_cat_id','=','stc_product.stc_product_sub_cat_id')
        ->select('stc_item_inventory.*','stc_product.stc_product_name','stc_product.stc_product_id','stc_category.stc_cat_name','stc_sub_category.stc_sub_cat_name')
        ->skip($start)
        ->take($rowperpage)
        ->get();

        $data_arr = array();
        
        foreach($records as $record){
            $id = $record->stc_item_inventory_id;
            $stc_product_id = $record->stc_product_id;
            $name = $record->stc_product_name;
            $cat_name = $record->stc_cat_name;
            $inv_qty = $record->stc_item_inventory_pd_qty;
            $sub_cat_name = $record->stc_sub_cat_name.' ';
            if($sub_cat_name!='Others'){
                $sub_cat_name='';
            }
            $stc_product_id = '<span id="display-'.$id.'">'.$stc_product_id.'</span>';
            $stc_product_name = '<span id="display-name'.$id.'">'.$sub_cat_name.$name.'</span>';
            $stc_cat_name = '<span id="display-catname'.$id.'">'.$cat_name.'</span>';
            $stc_item_inventory_pd_qty = '<span id="display-quantity'.$id.'">'.number_format($inv_qty, 2).'</span>';
            $actionData = '
                <a href="javascript:void(0)" class="btn btn-primary btn-sm edit-modal-btn" data-toggle="modal" data-target="#edit-modal" id="'.$id.'" ><i class="fas fa-edit" title="Edit"></i></a>
                <a href="javascript:void(0)" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-modal" onclick=$("#delete_id").val("'.$id.'")><i class="fas fa-trash" title="Delete"></i></a>
            '; 
            $data_arr[] = array(
            "stc_product_id" => $stc_product_id,
            "stc_product_name" => $stc_product_name,
            "stc_cat_name" => $stc_cat_name,
            "stc_item_inventory_pd_qty" => $stc_item_inventory_pd_qty,
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
        $update = [
            'stc_item_inventory_pd_qty'=> $request->quantity,
        ];
        $edit = Inventory::where('stc_item_inventory_id', $request->id)->update($update);
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
        $delete =  Inventory::destroy($request->id);
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
