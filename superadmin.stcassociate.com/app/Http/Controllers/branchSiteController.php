<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BranchSite;

class branchSiteController extends Controller
{
    public function show(){
        $data['page_title']="Site User";
        return view('pages.branchsite', $data);
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
        $totalRecords = BranchSite::select('count(*) as allcount')->count();
        $totalRecordswithFilter = BranchSite::select('count(*) as allcount')
            ->count();

        // Fetch records
        $records = BranchSite::orderBy($columnName,$columnSortOrder)
        ->where('stc_status_down_list.stc_status_down_list_plocation', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_status_down_list.stc_status_down_list_sub_location', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_cust_pro_supervisor.stc_cust_pro_supervisor_fullname', 'like', '%' .$searchValue . '%')
        ->orwhere('stc_cust_project.stc_cust_project_title', 'like', '%' .$searchValue . '%')
        ->leftjoin('stc_cust_pro_supervisor','stc_cust_pro_supervisor.stc_cust_pro_supervisor_id','=','stc_status_down_list.stc_status_down_list_created_by')
        ->leftjoin('stc_cust_project','stc_cust_project.stc_cust_project_id','=','stc_status_down_list.stc_status_down_list_location')
        ->select('stc_status_down_list.*','stc_cust_pro_supervisor.stc_cust_pro_supervisor_id','stc_cust_pro_supervisor.stc_cust_pro_supervisor_fullname','stc_cust_project.stc_cust_project_id','stc_cust_project.stc_cust_project_title')
        ->skip($start)
        ->take($rowperpage)
        ->get();

        $data_arr = array();
        
        foreach($records as $record){
            $id = $record->stc_status_down_list_id;
            $date = $record->stc_status_down_list_date;
            $project = '<a href="javascript:void(0)" class="show-project-btn" data-toggle="modal" data-target="#show-project-modal"  id="'.$record->stc_cust_project_id.'">'.$record->stc_cust_project_title.'</a>';
            $plocation = $record->stc_status_down_list_plocation;
            $dept = $record->stc_status_down_list_sub_location;
            $createdbyname = $record->stc_cust_pro_supervisor_fullname;
            $jobtype = $record->stc_status_down_list_jobtype;
            $status = $record->stc_status_down_list_status==1 ? 'Down' : ($record->stc_status_down_list_status==2 ? 'Planning' : ($record->stc_status_down_list_status==3 ? 'Work-In-Progress' : ($record->stc_status_down_list_status==4 ? 'Work Done' : ($record->stc_status_down_list_status==5 ? 'Work-Complete' : 'Close'))));
            
            // $stc_product_id = '<span id="display-stc_product_id'.$id.'">'.$id.'</span>';
            // $stc_product_name = '<span id="display-product_name'.$id.'">'.$sub_cat_name.' '.$name.'</span>';
            // $stc_cat_name = '<span id="display-cat_name'.$id.'">'.$cat_name.'</span>';
            // $stc_rack_name = '<span id="display-rack_name'.$id.'">'.$rack_name.'</span>';
            // $stc_brand_title = '<span id="display-brand_title'.$id.'">'.$brand_name.'</span>';
            // $stc_product_hsncode = '<span id="display-product_hsncode'.$id.'">'.$stc_product_hsncode.'</span>';
            // $stc_product_gst = '<span id="display-product_gst'.$id.'">'.$stc_product_gst.'%</span>';
            // $stc_product_unit = '<span id="display-product_unit'.$id.'">'.$stc_product_unit.'</span>';
            // $stc_product_status = '<span id="display-product_status'.$id.'">'.$status.'</span>';

            $server = $_SERVER['SERVER_NAME']=="localhost" ? "http://localhost/stc" : "https://stcassociate.com";
            $user_id = $record->stc_cust_pro_supervisor_id;
            $user_type = "siteuser";
            
            $simple_string =$user_id;
            $ciphering = "AES-128-CTR";
            $iv_length = openssl_cipher_iv_length($ciphering);
            $options = 0;
            $encryption_iv = '1234567891011121';
            $encryption_key = "stc_associate_go";
            $encryption = openssl_encrypt($simple_string, $ciphering, $encryption_key, $options, $encryption_iv);
            
            $url = $server.'/usercredentials.php?user_id='.$encryption.'&user_type='.$user_type;
            $actionData = '
                <a href="javascript:void(0)" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#delete-modal" onclick=$("#delete_id").val("'.$id.'")><i class="fas fa-trash" title="Delete"></i></a>
                <a href="'.$url.'" target="_blank" class="btn btn-success btn-sm"><i class="fas fa-arrow-right" title="Go"></i></a>
            '; 

            $data_arr[] = array(
                "stc_status_down_list_id" => $id,
                "stc_status_down_list_date" => $date,
                "stc_cust_project_title" => $project,
                "stc_status_down_list_plocation" => $plocation,
                "stc_status_down_list_sub_location" => $dept,
                "stc_status_down_list_jobtype" => $jobtype,
                "stc_cust_pro_supervisor_fullname" => $createdbyname,
                "stc_status_down_list_status" => $status,
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

    // get through ajax
    function getusers(Request $request){
        $id=$request->id;
        $recordssiteusers = BranchSite::where('stc_status_down_list.stc_status_down_list_location', '=', $id )
        ->leftjoin('stc_cust_pro_attend_supervise','stc_cust_pro_attend_supervise.stc_cust_pro_attend_supervise_pro_id','=','stc_status_down_list.stc_status_down_list_location')
        ->leftjoin('stc_cust_pro_supervisor','stc_cust_pro_supervisor.stc_cust_pro_supervisor_id','=','stc_cust_pro_attend_supervise.stc_cust_pro_attend_supervise_super_id')
        ->select(
            'stc_cust_pro_supervisor.stc_cust_pro_supervisor_id', 
            'stc_cust_pro_supervisor.stc_cust_pro_supervisor_fullname', 
            'stc_cust_pro_supervisor.stc_cust_pro_supervisor_contact', 
            'stc_cust_pro_supervisor.stc_cust_pro_supervisor_category'
        )
        ->orderBy('stc_cust_pro_supervisor.stc_cust_pro_supervisor_fullname', 'asc')
        ->distinct()
        ->get();
        $slno=0;
        $siteusers='';
        foreach($recordssiteusers as $recordssiteusersrow){
            $slno++;
            $server = $_SERVER['SERVER_NAME']=="localhost" ? "http://localhost/stc" : "https://stcassociate.com";
            $user_id = $recordssiteusersrow->stc_cust_pro_supervisor_id;
            $user_type = "siteuser";
            
            $simple_string =$user_id;
            $ciphering = "AES-128-CTR";
            $iv_length = openssl_cipher_iv_length($ciphering);
            $options = 0;
            $encryption_iv = '1234567891011121';
            $encryption_key = "stc_associate_go";
            $encryption = openssl_encrypt($simple_string, $ciphering, $encryption_key, $options, $encryption_iv);
            
            $url = $server.'/usercredentials.php?user_id='.$encryption.'&user_type='.$user_type;
            $siteusers.='
                <tr>
                    <td>'.$slno.'</td>
                    <td>'.$recordssiteusersrow['stc_cust_pro_supervisor_fullname'].'</td>
                    <td>'.$recordssiteusersrow['stc_cust_pro_supervisor_contact'].'</td>
                    <td>'.$recordssiteusersrow['stc_cust_pro_supervisor_category'].'</td>
                    <td class="text-center"><a href="'.$url.'" target="_blank" class="btn btn-success btn-sm"><i class="fas fa-arrow-right" title="Go"></i></a></td>
                </tr>
            ';
        }
        $data=array(
            'siteusers' => $siteusers
        );
       return $data;
    }     

    // delete through ajax
    function delete(Request $request){
        $delete =  BranchSite::destroy($request->id);
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
