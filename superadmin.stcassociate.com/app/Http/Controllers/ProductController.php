<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class ProductController extends Controller
{
    public function show(){
        $data['page_title']="Product";
        return view('pages.product', $data);
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
        $totalRecords = Product::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Product::select('count(*) as allcount')->where('stc_product_name', 'like', '%' .$searchValue . '%')->count();

        // Fetch records
        $records = Product::orderBy($columnName,$columnSortOrder)
        ->where('stc_product.stc_product_name', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_category.stc_cat_name', 'like', '%' .$searchValue . '%')
        ->orWhere('stc_product.stc_product_desc', 'like', '%' .$searchValue . '%')
        ->orWhere('stc_item_inventory.stc_item_inventory_pd_qty', 'like', '%' .$searchValue . '%')
        ->leftjoin('stc_item_inventory','stc_item_inventory.stc_item_inventory_pd_id','=','stc_product.stc_product_id')
        ->leftjoin('stc_category','stc_category.stc_cat_id','=','stc_product.stc_product_cat_id')
        ->leftjoin('stc_sub_category','stc_sub_category.stc_sub_cat_id','=','stc_product.stc_product_sub_cat_id')
        ->leftjoin('stc_rack','stc_rack.stc_rack_id','=','stc_product.stc_product_rack_id')
        ->leftjoin('stc_brand','stc_brand.stc_brand_id','=','stc_product.stc_product_brand_id')
        ->select('stc_product.*', 'stc_category.stc_cat_name', 'stc_sub_category.stc_sub_cat_name', 'stc_rack.stc_rack_name', 'stc_brand.stc_brand_title', 'stc_item_inventory.stc_item_inventory_pd_qty')
        ->skip($start)
        ->take($rowperpage)
        ->get();

        $data_arr = array();
        
        foreach($records as $record){
            $id = $record->stc_product_id;
            $name = $record->stc_product_name;
            $cat_name = $record->stc_cat_name;
            $sub_cat_name = $record->stc_sub_cat_name;
            $rack_name = $record->stc_rack_name;
            $brand_name = $record->stc_brand_title;
            $stc_product_hsncode = $record->stc_product_hsncode;
            $stc_product_gst = $record->stc_product_gst;
            $stc_product_unit = $record->stc_product_unit;
            $inv_qty = $record->stc_item_inventory_pd_qty;
            $status = $record->stc_product_avail==1 ? 'Active' : 'In-active';

            if($sub_cat_name=='OTHERS'){
                $sub_cat_name='';
            }
            
            $stc_product_id = '<span id="display-stc_product_id'.$id.'">'.$id.'</span>';
            $stc_product_name = '<span id="display-product_name'.$id.'">'.$sub_cat_name.' '.$name.'</span>';
            $stc_cat_name = '<span id="display-cat_name'.$id.'">'.$cat_name.'</span>';
            $stc_rack_name = '<span id="display-rack_name'.$id.'">'.$rack_name.'</span>';
            $stc_brand_title = '<span id="display-brand_title'.$id.'">'.$brand_name.'</span>';
            $stc_product_hsncode = '<span id="display-product_hsncode'.$id.'">'.$stc_product_hsncode.'</span>';
            $stc_product_gst = '<span id="display-product_gst'.$id.'">'.$stc_product_gst.'%</span>';
            $stc_product_unit = '<span id="display-product_unit'.$id.'">'.$stc_product_unit.'</span>';
            $stc_product_status = '<span id="display-product_status'.$id.'">'.$status.'</span>';
            $actionData = '
                <a href="javascript:void(0)" class="btn btn-primary btn-sm edit-modal-btn" data-toggle="modal" data-target="#edit-modal" id="'.$id.'" ><i class="fas fa-edit" title="Edit"></i></a>
                <a href="javascript:void(0)" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-modal" onclick=$("#delete_id").val("'.$id.'")><i class="fas fa-trash" title="Delete"></i></a>
            '; 
            $data_arr[] = array(
            "stc_product_id" => $stc_product_id,
            "stc_product_desc" => $stc_product_name,
            "stc_cat_name" => $stc_cat_name,
            "stc_rack_name" => $stc_rack_name,
            "stc_brand_title" => $stc_brand_title,
            "stc_product_hsncode" => $stc_product_hsncode,
            "stc_product_gst" => $stc_product_gst,
            "stc_item_inventory_pd_qty" => number_format($inv_qty, 2),
            "stc_product_unit" => $stc_product_unit,
            "stc_product_avail" => $stc_product_status,
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
    function create(Request $request){
        $validatedData = $request->validate([
          'name' => 'required|unique:stc_rack,stc_rack_name'
        ]);

        $insert = [
            'stc_rack_name' => $request->name,
            'stc_rack_status'=> $request->status,
        ];
        $create = Product::create($insert);
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
            'stc_rack_name' => $request->name,
            'stc_rack_status'=> $request->status,
        ];
        $edit = Product::where('stc_rack_id', $request->id)->update($update);
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
        $delete =  Product::destroy($request->id);
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
