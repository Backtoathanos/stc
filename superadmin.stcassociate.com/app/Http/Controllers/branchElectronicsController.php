<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BranchElectronics;

class branchElectronicsController extends Controller
{
    public function show(){
        $data['page_title']="Product";
        return view('pages.branchelectronics', $data);
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
        $totalRecords = BranchElectronics::select('count(*) as allcount')->count();
        $totalRecordswithFilter = BranchElectronics::select('count(*) as allcount')
            ->where('stc_product_name', 'like', '%' .$searchValue . '%')
            ->leftjoin('stc_product','stc_product.stc_product_id','=','stc_electronics_inventory.stc_electronics_inventory_item_id')
            ->count();

        // Fetch records
        $records = BranchElectronics::orderBy($columnName,$columnSortOrder)
        ->where('stc_product.stc_product_name', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_category.stc_cat_name', 'like', '%' .$searchValue . '%')
        ->orWhere('stc_product.stc_product_desc', 'like', '%' .$searchValue . '%')
        ->orWhere('stc_electronics_inventory.stc_electronics_inventory_item_qty', 'like', '%' .$searchValue . '%')
        ->leftjoin('stc_product','stc_product.stc_product_id','=','stc_electronics_inventory.stc_electronics_inventory_item_id')
        ->leftjoin('stc_category','stc_category.stc_cat_id','=','stc_product.stc_product_cat_id')
        ->leftjoin('stc_sub_category','stc_sub_category.stc_sub_cat_id','=','stc_product.stc_product_sub_cat_id')
        ->leftjoin('stc_rack','stc_rack.stc_rack_id','=','stc_product.stc_product_rack_id')
        ->leftjoin('stc_brand','stc_brand.stc_brand_id','=','stc_product.stc_product_brand_id')
        ->select('stc_product.*', 'stc_category.stc_cat_name', 'stc_sub_category.stc_sub_cat_name', 'stc_rack.stc_rack_name', 'stc_brand.stc_brand_title', 'stc_electronics_inventory.*')
        ->skip($start)
        ->take($rowperpage)
        ->get();

        $data_arr = array();
        
        foreach($records as $record){
            $id = $record->stc_electronics_inventory_id;
            $name = $record->stc_product_name;
            $cat_name = $record->stc_cat_name;
            $sub_cat_name = $record->stc_sub_cat_name;
            $rack_name = $record->stc_rack_name;
            $brand_name = $record->stc_brand_title;
            $stc_product_hsncode = $record->stc_product_hsncode;
            $stc_product_gst = $record->stc_product_gst;
            $stc_product_unit = $record->stc_product_unit;
            $inv_qty = $record->stc_electronics_inventory_item_qty;
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
            <a href="javascript:void(0)" class="btn btn-success btn-sm view-modal-btn" data-toggle="modal" data-target="#view-modal" id="'.$id.'" ><i class="fas fa-eye" title="Show"></i></a>
                <a href="javascript:void(0)" class="btn btn-primary btn-sm edit-modal-btn" data-toggle="modal" data-target="#edit-modal" id="'.$id.'" ><i class="fas fa-edit" title="Edit"></i></a>
                <a href="javascript:void(0)" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-modal" onclick=$("#delete_id").val("'.$id.'")><i class="fas fa-trash" title="Delete"></i></a>
            '; 

            $recordspurchase = BranchElectronics::where('stc_electronics_inventory.stc_electronics_inventory_id', '=', $id )
            ->leftjoin('stc_product_grn_items','stc_product_grn_items.stc_product_grn_items_product_id','=','stc_electronics_inventory.stc_electronics_inventory_item_id')
            ->sum('stc_product_grn_items.stc_product_grn_items_qty');
            $pqnty=$recordspurchase;
            
            $recordsinvent = BranchElectronics::where('stc_electronics_inventory.stc_electronics_inventory_id', '=', $id )
            ->leftjoin('stc_item_inventory','stc_item_inventory.stc_item_inventory_pd_id','=','stc_electronics_inventory.stc_electronics_inventory_item_id')
            ->sum('stc_item_inventory.stc_item_inventory_pd_qty');
            $invqty=$recordsinvent;

            $recordschallan = BranchElectronics::where('stc_electronics_inventory.stc_electronics_inventory_id', '=', $id )
            ->leftjoin('stc_sale_product_items','stc_sale_product_items.stc_sale_product_items_product_id','=','stc_electronics_inventory.stc_electronics_inventory_item_id')
            ->sum('stc_sale_product_items.stc_sale_product_items_product_qty');
            $challanqty=$recordschallan;

            $recordsechallan = BranchElectronics::where('stc_electronics_inventory.stc_electronics_inventory_id', '=', $id )
            ->leftjoin('stc_sale_product_silent_challan_items','stc_sale_product_silent_challan_items.stc_sale_product_silent_challan_items_product_id','=','stc_electronics_inventory.stc_electronics_inventory_item_id')
            ->sum('stc_sale_product_silent_challan_items.stc_sale_product_silent_challan_items_product_qty');
            $echallanqty=$recordsechallan;

            $color = (($invqty + $challanqty) == ($inv_qty + $echallanqty)) ? "Black" : "red";

            $data_arr[] = array(
                "stc_product_id" => $stc_product_id,
                "stc_product_name" => $stc_product_name,
                "stc_cat_name" => $stc_cat_name,
                "stc_rack_name" => $stc_rack_name,
                "stc_brand_title" => $stc_brand_title,
                "stc_product_hsncode" => $stc_product_hsncode,
                "stc_product_gst" => $stc_product_gst,
                "purchaseqty" => '<span style="color:'.$color.';">'.number_format($pqnty, 2).'</span>',
                "inventory" => '<span style="color:'.$color.';">'.number_format($invqty, 2).'</span>',
                "challanqty" => '<span style="color:'.$color.';">'.number_format($challanqty, 2).'</span>',
                "stc_electronics_inventory_item_qty" => '<span id="display-quantity'.$id.'">'.number_format($inv_qty, 2).'</span>',
                "echallanqty" => '<span style="color:'.$color.';">'.number_format($echallanqty, 2).'</span>',
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
    function update(Request $request){
        $update = [
            'stc_electronics_inventory_item_qty' => $request->qty
        ];
        $edit = BranchElectronics::where('stc_electronics_inventory_id', $request->id)->update($update);
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

    // get through ajax
    function getSP(Request $request){
        $id=$request->id;
        $recordspurchase = BranchElectronics::where('stc_electronics_inventory.stc_electronics_inventory_id', '=', $id )
        ->leftjoin('stc_product','stc_product.stc_product_id','=','stc_electronics_inventory.stc_electronics_inventory_item_id')
        ->leftjoin('stc_product_grn_items','stc_product_grn_items.stc_product_grn_items_product_id','=','stc_product.stc_product_id')
        ->leftjoin('stc_purchase_product','stc_purchase_product.stc_purchase_product_id','=','stc_product_grn_items.stc_product_grn_items_purchase_order_id')
        ->leftjoin('stc_merchant','stc_merchant.stc_merchant_id','=','stc_purchase_product.stc_purchase_product_merchant_id')
        ->select(
            'stc_product.stc_product_name', 
            'stc_product.stc_product_unit', 
            'stc_purchase_product.stc_purchase_product_id', 
            'stc_merchant.stc_merchant_name', 
            'stc_product_grn_items.stc_product_grn_items_qty', 
            'stc_electronics_inventory.stc_electronics_inventory_item_qty',
            'stc_electronics_inventory.stc_electronics_inventory_item_id'
        )
        ->get();
        $purchaserec='';
        $totalpquantity=0;
        $pcounter=0;
        $punit='';
        foreach($recordspurchase as $recordspurchaserow){
            $pcounter++;
            $totalpquantity+=$recordspurchaserow['stc_product_grn_items_qty'];
            $punit=$recordspurchaserow['stc_product_unit'];
            $purchaserec.="
                <tr>
                    <td>".$recordspurchaserow['stc_merchant_name']."</td>
                    <td class='text-right'>".number_format($recordspurchaserow['stc_product_grn_items_qty'], 2)."/".$recordspurchaserow['stc_product_unit']."</td>
                </tr>
            ";
        }
        if($pcounter==0){
            $purchaserec="
                <tr>
                    <td>Empty record.</td>
                </tr>
            ";
        }else{
            $purchaserec.="
                <tr>
                    <td><b>Total Purchased</b></td>
                    <td class='text-right'><b>".number_format($totalpquantity, 2)."/".$punit."</b></td>
                </tr>
            ";
        }

        $recordssale = BranchElectronics::where('stc_electronics_inventory.stc_electronics_inventory_id', '=', $id )
        ->leftjoin('stc_product','stc_product.stc_product_id','=','stc_electronics_inventory.stc_electronics_inventory_item_id')
        ->leftjoin('stc_sale_product_silent_challan_items','stc_sale_product_silent_challan_items.stc_sale_product_silent_challan_items_product_id','=','stc_product.stc_product_id')
        ->leftjoin('stc_sale_product_silent_challan','stc_sale_product_silent_challan.stc_sale_product_silent_challan_id','=','stc_sale_product_silent_challan_items.stc_sale_product_silent_challan_items_order_id')
        ->select(
            'stc_product.stc_product_name', 
            'stc_product.stc_product_unit', 
            'stc_sale_product_silent_challan.stc_sale_product_silent_challan_customer_name', 
            'stc_sale_product_silent_challan_items.stc_sale_product_silent_challan_items_product_qty'
        )
        ->get();
        $salerec='';
        $totalsquantity=0;
        $scounter=0;
        $sunit='';
        foreach($recordssale as $recordssalerow){
            $scounter++;
            $totalsquantity+=$recordssalerow['stc_sale_product_silent_challan_items_product_qty'];
            $sunit=$recordssalerow['stc_product_unit'];
            $salerec.="
                <tr>
                    <td>".$recordssalerow['stc_sale_product_silent_challan_customer_name']."</td>
                    <td class='text-right'>".number_format($recordssalerow['stc_sale_product_silent_challan_items_product_qty'], 2)."/".$recordssalerow['stc_product_unit']."</td>
                </tr>
            ";
        }
        if($scounter==0){
            $salerec="
                <tr>
                    <td>Empty record.</td>
                </tr>
            ";
        }else{
            $salerec.="
                <tr>
                    <td><b>Total Sold</b></td>
                    <td class='text-right'><b>".number_format($totalsquantity, 2)."/".$sunit."</b></td>
                </tr>
            ";
        }
        $data=array(
            'purchaserec' => $purchaserec,
            'salerec' => $salerec
        );
       return $data;
   } 

    // delete through ajax
    function delete(Request $request){
       $delete =  BranchElectronics::destroy($request->id);
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
