<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Requisition;
use App\RequisitionItems;
use App\RequisitionDispatch;
use App\RequisitionCombiner;
use App\RequisitionCombinerReq;
use App\RequisitionItemLog;
use App\RequisitionReceived;
use App\ReqItemCommunicate;
use Illuminate\Support\Facades\DB;

class RequisitionController extends Controller
{
    public function show(){
        $data['page_title']="Requisitions";
        return view('pages.requisitions', $data);
    }

    private function dtParams(Request $request){
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length");
        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');
        $columnIndex = $columnIndex_arr[0]['column'] ?? 0;
        $columnName = $columnName_arr[$columnIndex]['data'] ?? '';
        $columnSortOrder = $order_arr[0]['dir'] ?? 'desc';
        $searchValue = $search_arr['value'] ?? '';
        return compact('draw', 'start', 'rowperpage', 'columnName', 'columnSortOrder', 'searchValue');
    }

    private function dtResponse($draw, $total, $filtered, $data){
        return json_encode([
            "draw" => intval($draw),
            "iTotalRecords" => $total,
            "iTotalDisplayRecords" => $filtered,
            "aaData" => $data
        ]);
    }

    private function actionHtml($id, $editBtn, $editModal, $deleteModal, $deleteInput){
        return '
            <a href="javascript:void(0)" class="btn btn-primary btn-sm '.$editBtn.'" data-toggle="modal" data-target="#'.$editModal.'" id="'.$id.'" title="Edit"><i class="fas fa-edit"></i></a>
            <a href="javascript:void(0)" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#'.$deleteModal.'" onclick=$("#'.$deleteInput.'").val("'.$id.'") title="Delete"><i class="fas fa-trash"></i></a>
        ';
    }

    private function jsonResult($ok, $message, $data = null){
        $out = ['status' => 'ok', 'success' => (bool)$ok, 'message' => $message];
        if($data !== null){
            $out['data'] = $data;
        }
        return response()->json($out);
    }

    private function saveOrFail($record, $payload, $notFoundMsg = 'Record not found!'){
        if(!$record){
            return $this->jsonResult(false, $notFoundMsg);
        }
        $record->fill($payload);
        $record->save();
        return $this->jsonResult(true, 'Record updated succesfully!');
    }

    // show through ajax
    function list(Request $request){
        $p = $this->dtParams($request);
        $columnName = $p['columnName'] === 'actionData' ? 'stc_cust_super_requisition_list_id' : $p['columnName'];

        $totalRecords = Requisition::select('count(*) as allcount')->count();
        $totalRecordswithFilter = Requisition::select('count(*) as allcount')
        ->where('stc_cust_super_requisition_list_id', 'like', '%' .$p['searchValue'] . '%')
        ->orwhere('stc_cust_project.stc_cust_project_title', 'like', '%' .$p['searchValue'] . '%')
        ->orwhere('stc_cust_pro_supervisor.stc_cust_pro_supervisor_fullname', 'like', '%' .$p['searchValue'] . '%')
        ->leftjoin('stc_cust_project','stc_cust_project.stc_cust_project_id','=','stc_cust_super_requisition_list.stc_cust_super_requisition_list_project_id')
        ->leftjoin('stc_cust_pro_supervisor','stc_cust_pro_supervisor.stc_cust_pro_supervisor_id','=','stc_cust_super_requisition_list.stc_cust_super_requisition_list_super_id')
        ->count();

        $records = Requisition::orderBy($columnName, $p['columnSortOrder'])
        ->where('stc_cust_super_requisition_list.stc_cust_super_requisition_list_id', 'like', '%' .$p['searchValue'] . '%')
        ->orwhere('stc_cust_project.stc_cust_project_title', 'like', '%' .$p['searchValue'] . '%')
        ->orwhere('stc_cust_pro_supervisor.stc_cust_pro_supervisor_fullname', 'like', '%' .$p['searchValue'] . '%')
        ->leftjoin('stc_cust_project','stc_cust_project.stc_cust_project_id','=','stc_cust_super_requisition_list.stc_cust_super_requisition_list_project_id')
        ->leftjoin('stc_cust_pro_supervisor','stc_cust_pro_supervisor.stc_cust_pro_supervisor_id','=','stc_cust_super_requisition_list.stc_cust_super_requisition_list_super_id')
        ->select('stc_cust_super_requisition_list.*', 'stc_cust_project.stc_cust_project_title', 'stc_cust_pro_supervisor.stc_cust_pro_supervisor_fullname')
        ->skip($p['start'])
        ->take($p['rowperpage'])
        ->get();

        $data_arr = array();
        foreach($records as $record){
            $id = $record->stc_cust_super_requisition_list_id;
            $data_arr[] = array(
                "stc_cust_super_requisition_list_id" => $record->stc_cust_super_requisition_list_id,
                "stc_cust_super_requisition_list_sdlid" => $record->stc_cust_super_requisition_list_sdlid,
                "stc_cust_project_title" => $record->stc_cust_project_title,
                "stc_cust_pro_supervisor_fullname" => $record->stc_cust_pro_supervisor_fullname,
                "stc_cust_super_requisition_list_status" => $record->stc_cust_super_requisition_list_status,
                "stc_cust_super_requisition_list_approved_by" => $record->stc_cust_super_requisition_list_approved_by,
                "stc_cust_super_requisition_list_date" => $record->stc_cust_super_requisition_list_date,
                "actionData" => $this->actionHtml($id, 'edit-req-btn', 'edit-req-modal', 'delete-modal', 'delete_id')
                    .' <a href="'.url('/branch/stc/requisitions/operate/'.$id).'" class="btn btn-success btn-sm" title="Operate connected records" target="_blank">Operate</a>'
            );
        }

        return $this->dtResponse($p['draw'], $totalRecords, $totalRecordswithFilter, $data_arr);
    }

    function get(Request $request){
        $record = Requisition::where('stc_cust_super_requisition_list_id', $request->id)->first();
        return $record ? $this->jsonResult(true, 'ok', $record) : $this->jsonResult(false, 'Record not found!');
    }

    function update(Request $request){
        $record = Requisition::where('stc_cust_super_requisition_list_id', $request->id)->first();
        return $this->saveOrFail($record, [
            'stc_cust_super_requisition_list_date' => $request->date,
            'stc_cust_super_requisition_list_sdlid' => (int)$request->sdlid,
            'stc_cust_super_requisition_list_super_id' => (int)$request->super_id,
            'stc_cust_super_requisition_list_project_id' => (int)$request->project_id,
            'stc_cust_super_requisition_list_status' => (int)$request->status,
            'stc_cust_super_requisition_list_approved_by' => (int)$request->approved_by,
        ]);
    }

    function delete(Request $request){
        $delete = Requisition::destroy($request->id);
        return $delete
            ? $this->jsonResult(true, 'Record deleted succesfully!')
            : $this->jsonResult(false, 'Record deleted failed!');
    }

    function itemlist(Request $request){
        $p = $this->dtParams($request);
        $columnName = $p['columnName'] === 'actionData' ? 'stc_cust_super_requisition_list_id' : $p['columnName'];

        $totalRecords = RequisitionItems::select('count(*) as allcount')->count();
        $totalRecordswithFilter = RequisitionItems::select('count(*) as allcount')
        ->where('stc_cust_super_requisition_list_items_title', 'like', '%' .$p['searchValue'] . '%')
        ->orwhere('stc_cust_super_requisition_list_items.stc_cust_super_requisition_list_items_req_id', 'like', '%' .$p['searchValue'] . '%')
        ->orwhere('stc_cust_super_requisition_list_items.stc_cust_super_requisition_list_id', 'like', '%' .$p['searchValue'] . '%')
        ->count();

        $records = RequisitionItems::orderBy($columnName, $p['columnSortOrder'])
        ->where('stc_cust_super_requisition_list_items.stc_cust_super_requisition_list_items_title', 'like', '%' .$p['searchValue'] . '%')
        ->orwhere('stc_cust_super_requisition_list_items.stc_cust_super_requisition_list_items_req_id', 'like', '%' .$p['searchValue'] . '%')
        ->orwhere('stc_cust_super_requisition_list_items.stc_cust_super_requisition_list_id', 'like', '%' .$p['searchValue'] . '%')
        ->select('stc_cust_super_requisition_list_items.*')
        ->skip($p['start'])
        ->take($p['rowperpage'])
        ->get();

        $data_arr = array();
        foreach($records as $record){
            $id = $record->stc_cust_super_requisition_list_id;
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
                "actionData" => $this->actionHtml($id, 'edit-req-item-btn', 'edit-req-item-modal', 'delete-modal-item', 'deletereqitem_id')
            );
        }

        return $this->dtResponse($p['draw'], $totalRecords, $totalRecordswithFilter, $data_arr);
    }

    function itemget(Request $request){
        $record = RequisitionItems::where('stc_cust_super_requisition_list_id', $request->id)->first();
        return $record ? $this->jsonResult(true, 'ok', $record) : $this->jsonResult(false, 'Record not found!');
    }

    function itemupdate(Request $request){
        $record = RequisitionItems::where('stc_cust_super_requisition_list_id', $request->id)->first();
        return $this->saveOrFail($record, [
            'stc_cust_super_requisition_list_items_req_id' => (int)$request->req_id,
            'stc_cust_super_requisition_list_items_title' => $request->title,
            'stc_cust_super_requisition_list_items_unit' => $request->unit,
            'stc_cust_super_requisition_list_items_reqqty' => $request->reqqty,
            'stc_cust_super_requisition_list_items_approved_qty' => $request->approved_qty,
            'stc_cust_super_requisition_items_finalqty' => $request->finalqty,
            'stc_cust_super_requisition_list_items_acceptby' => (int)$request->acceptby,
            'stc_cust_super_requisition_items_type' => $request->type,
            'stc_cust_super_requisition_items_priority' => (int)$request->priority,
            'stc_cust_super_requisition_list_items_product_id' => (int)$request->product_id,
            'stc_cust_super_requisition_list_items_status' => (int)$request->status,
            'stc_cust_super_requisition_list_items_return_accepted' => (int)$request->return_accepted,
        ]);
    }

    function itemdelete(Request $request){
        $delete = RequisitionItems::destroy($request->id);
        return $delete
            ? $this->jsonResult(true, 'Record deleted succesfully!')
            : $this->jsonResult(false, 'Record deleted failed!');
    }

    function itemdislist(Request $request){
        $p = $this->dtParams($request);
        $columnName = $p['columnName'] === 'actionData' ? 'stc_cust_super_requisition_list_items_rec_id' : $p['columnName'];

        $totalRecords = RequisitionDispatch::select('count(*) as allcount')->count();
        $totalRecordswithFilter = RequisitionDispatch::select('count(*) as allcount')
        ->where('stc_cust_super_requisition_list_items_rec_id', 'like', '%' .$p['searchValue'] . '%')
        ->orwhere('stc_cust_super_requisition_list_items_rec.stc_cust_super_requisition_list_items_rec_list_id', 'like', '%' .$p['searchValue'] . '%')
        ->orwhere('stc_cust_super_requisition_list_items_rec.stc_cust_super_requisition_list_items_rec_list_item_id', 'like', '%' .$p['searchValue'] . '%')
        ->orwhere('stc_cust_super_requisition_list_items_rec.stc_cust_super_requisition_list_items_rec_list_pd_id', 'like', '%' .$p['searchValue'] . '%')
        ->count();

        $records = RequisitionDispatch::orderBy($columnName, $p['columnSortOrder'])
        ->where('stc_cust_super_requisition_list_items_rec.stc_cust_super_requisition_list_items_rec_id', 'like', '%' .$p['searchValue'] . '%')
        ->orwhere('stc_cust_super_requisition_list_items_rec.stc_cust_super_requisition_list_items_rec_list_id', 'like', '%' .$p['searchValue'] . '%')
        ->orwhere('stc_cust_super_requisition_list_items_rec.stc_cust_super_requisition_list_items_rec_list_item_id', 'like', '%' .$p['searchValue'] . '%')
        ->orwhere('stc_cust_super_requisition_list_items_rec.stc_cust_super_requisition_list_items_rec_list_pd_id', 'like', '%' .$p['searchValue'] . '%')
        ->select('stc_cust_super_requisition_list_items_rec.*')
        ->skip($p['start'])
        ->take($p['rowperpage'])
        ->get();

        $data_arr = array();
        foreach($records as $record){
            $id = $record->stc_cust_super_requisition_list_items_rec_id;
            $data_arr[] = array(
                "stc_cust_super_requisition_list_items_rec_id" => $record->stc_cust_super_requisition_list_items_rec_id,
                "stc_cust_super_requisition_list_items_rec_list_id" => $record->stc_cust_super_requisition_list_items_rec_list_id,
                "stc_cust_super_requisition_list_items_rec_list_item_id" => $record->stc_cust_super_requisition_list_items_rec_list_item_id,
                "stc_cust_super_requisition_list_items_rec_list_pd_id" => $record->stc_cust_super_requisition_list_items_rec_list_pd_id,
                "stc_cust_super_requisition_list_items_rec_list_poaid" => $record->stc_cust_super_requisition_list_items_rec_list_poaid,
                "stc_cust_super_requisition_list_items_rec_recqty" => $record->stc_cust_super_requisition_list_items_rec_recqty,
                "stc_cust_super_requisition_list_items_rec_date" => $record->stc_cust_super_requisition_list_items_rec_date,
                "actionData" => $this->actionHtml($id, 'edit-req-itemdis-btn', 'edit-req-itemdis-modal', 'delete-modal-itemrec', 'deletereqitemdis_id')
            );
        }

        return $this->dtResponse($p['draw'], $totalRecords, $totalRecordswithFilter, $data_arr);
    }

    function itemdisget(Request $request){
        $record = RequisitionDispatch::where('stc_cust_super_requisition_list_items_rec_id', $request->id)->first();
        return $record ? $this->jsonResult(true, 'ok', $record) : $this->jsonResult(false, 'Record not found!');
    }

    function itemdisupdate(Request $request){
        $record = RequisitionDispatch::where('stc_cust_super_requisition_list_items_rec_id', $request->id)->first();
        return $this->saveOrFail($record, [
            'stc_cust_super_requisition_list_items_rec_list_id' => (int)$request->list_id,
            'stc_cust_super_requisition_list_items_rec_list_item_id' => (int)$request->list_item_id,
            'stc_cust_super_requisition_list_items_rec_list_pd_id' => (int)$request->pd_id,
            'stc_cust_super_requisition_list_items_rec_list_poaid' => (int)$request->poaid,
            'stc_cust_super_requisition_list_items_rec_recqty' => $request->recqty,
            'stc_cust_super_requisition_list_items_rec_status' => (int)$request->status,
            'stc_cust_super_requisition_list_items_rec_date' => $request->date,
        ]);
    }

    function itemdisdelete(Request $request){
        $delete = RequisitionDispatch::destroy($request->id);
        return $delete
            ? $this->jsonResult(true, 'Record deleted succesfully!')
            : $this->jsonResult(false, 'Record deleted failed!');
    }

    function combinerlist(Request $request){
        $p = $this->dtParams($request);
        $columnName = in_array($p['columnName'], ['actionData', ''], true) ? 'stc_requisition_combiner_id' : $p['columnName'];

        $q = RequisitionCombiner::query();
        if($p['searchValue'] !== ''){
            $sv = '%'.$p['searchValue'].'%';
            $q->where(function($w) use ($sv){
                $w->where('stc_requisition_combiner_id', 'like', $sv)
                  ->orWhere('stc_requisition_combiner_refrence', 'like', $sv)
                  ->orWhere('stc_requisition_combiner_agent_id', 'like', $sv)
                  ->orWhere('stc_requisition_combiner_status', 'like', $sv);
            });
        }
        $totalRecords = RequisitionCombiner::count();
        $totalRecordswithFilter = (clone $q)->count();
        $records = $q->orderBy($columnName, $p['columnSortOrder'])
            ->skip($p['start'])->take($p['rowperpage'])->get();

        $data_arr = [];
        foreach($records as $record){
            $id = $record->stc_requisition_combiner_id;
            $data_arr[] = [
                "stc_requisition_combiner_id" => $id,
                "stc_requisition_combiner_date" => $record->stc_requisition_combiner_date,
                "stc_requisition_combiner_refrence" => $record->stc_requisition_combiner_refrence,
                "stc_requisition_combiner_agent_id" => $record->stc_requisition_combiner_agent_id,
                "stc_requisition_combiner_status" => $record->stc_requisition_combiner_status,
                "actionData" => $this->actionHtml($id, 'edit-combiner-btn', 'edit-combiner-modal', 'delete-modal-combiner', 'deletecombiner_id')
            ];
        }
        return $this->dtResponse($p['draw'], $totalRecords, $totalRecordswithFilter, $data_arr);
    }

    function combinerget(Request $request){
        $record = RequisitionCombiner::where('stc_requisition_combiner_id', $request->id)->first();
        return $record ? $this->jsonResult(true, 'ok', $record) : $this->jsonResult(false, 'Record not found!');
    }

    function combinerupdate(Request $request){
        $record = RequisitionCombiner::where('stc_requisition_combiner_id', $request->id)->first();
        return $this->saveOrFail($record, [
            'stc_requisition_combiner_date' => $request->date,
            'stc_requisition_combiner_refrence' => $request->refrence,
            'stc_requisition_combiner_agent_id' => (int)$request->agent_id,
            'stc_requisition_combiner_status' => (int)$request->status,
        ]);
    }

    function combinerdelete(Request $request){
        $id = (int)$request->id;
        RequisitionCombinerReq::where('stc_requisition_combiner_req_comb_id', $id)->delete();
        $delete = RequisitionCombiner::destroy($id);
        return $delete
            ? $this->jsonResult(true, 'Record deleted succesfully!')
            : $this->jsonResult(false, 'Record deleted failed!');
    }

    function combinerreqlist(Request $request){
        $p = $this->dtParams($request);
        $columnName = in_array($p['columnName'], ['actionData', ''], true) ? 'stc_requisition_combiner_req_id' : $p['columnName'];

        $q = RequisitionCombinerReq::query();
        if($p['searchValue'] !== ''){
            $sv = '%'.$p['searchValue'].'%';
            $q->where(function($w) use ($sv){
                $w->where('stc_requisition_combiner_req_id', 'like', $sv)
                  ->orWhere('stc_requisition_combiner_req_comb_id', 'like', $sv)
                  ->orWhere('stc_requisition_combiner_req_requisition_id', 'like', $sv);
            });
        }
        $totalRecords = RequisitionCombinerReq::count();
        $totalRecordswithFilter = (clone $q)->count();
        $records = $q->orderBy($columnName, $p['columnSortOrder'])
            ->skip($p['start'])->take($p['rowperpage'])->get();

        $data_arr = [];
        foreach($records as $record){
            $id = $record->stc_requisition_combiner_req_id;
            $data_arr[] = [
                "stc_requisition_combiner_req_id" => $id,
                "stc_requisition_combiner_req_comb_id" => $record->stc_requisition_combiner_req_comb_id,
                "stc_requisition_combiner_req_requisition_id" => $record->stc_requisition_combiner_req_requisition_id,
                "actionData" => $this->actionHtml($id, 'edit-combinerreq-btn', 'edit-combinerreq-modal', 'delete-modal-combinerreq', 'deletecombinerreq_id')
            ];
        }
        return $this->dtResponse($p['draw'], $totalRecords, $totalRecordswithFilter, $data_arr);
    }

    function combinerreqget(Request $request){
        $record = RequisitionCombinerReq::where('stc_requisition_combiner_req_id', $request->id)->first();
        return $record ? $this->jsonResult(true, 'ok', $record) : $this->jsonResult(false, 'Record not found!');
    }

    function combinerrequpdate(Request $request){
        $record = RequisitionCombinerReq::where('stc_requisition_combiner_req_id', $request->id)->first();
        return $this->saveOrFail($record, [
            'stc_requisition_combiner_req_comb_id' => (int)$request->comb_id,
            'stc_requisition_combiner_req_requisition_id' => (int)$request->requisition_id,
        ]);
    }

    function combinerreqdelete(Request $request){
        $delete = RequisitionCombinerReq::destroy($request->id);
        return $delete
            ? $this->jsonResult(true, 'Record deleted succesfully!')
            : $this->jsonResult(false, 'Record deleted failed!');
    }

    function itemloglist(Request $request){
        $p = $this->dtParams($request);
        $columnName = in_array($p['columnName'], ['actionData', ''], true) ? 'id' : $p['columnName'];

        $q = RequisitionItemLog::query();
        if($p['searchValue'] !== ''){
            $sv = '%'.$p['searchValue'].'%';
            $q->where(function($w) use ($sv){
                $w->where('id', 'like', $sv)
                  ->orWhere('item_id', 'like', $sv)
                  ->orWhere('title', 'like', $sv)
                  ->orWhere('message', 'like', $sv)
                  ->orWhere('created_by', 'like', $sv);
            });
        }
        $totalRecords = RequisitionItemLog::count();
        $totalRecordswithFilter = (clone $q)->count();
        $records = $q->orderBy($columnName, $p['columnSortOrder'])
            ->skip($p['start'])->take($p['rowperpage'])->get();

        $data_arr = [];
        foreach($records as $record){
            $id = $record->id;
            $message = $record->message;
            if(strlen(strip_tags((string)$message)) > 80){
                $message = substr(strip_tags((string)$message), 0, 80).'...';
            }
            $data_arr[] = [
                "id" => $id,
                "item_id" => $record->item_id,
                "title" => $record->title,
                "message" => $message,
                "status" => $record->status,
                "created_by" => $record->created_by,
                "created_date" => $record->created_date,
                "actionData" => $this->actionHtml($id, 'edit-itemlog-btn', 'edit-itemlog-modal', 'delete-modal-itemlog', 'deleteitemlog_id')
            ];
        }
        return $this->dtResponse($p['draw'], $totalRecords, $totalRecordswithFilter, $data_arr);
    }

    function itemlogget(Request $request){
        $record = RequisitionItemLog::where('id', $request->id)->first();
        return $record ? $this->jsonResult(true, 'ok', $record) : $this->jsonResult(false, 'Record not found!');
    }

    function itemlogupdate(Request $request){
        $record = RequisitionItemLog::where('id', $request->id)->first();
        return $this->saveOrFail($record, [
            'item_id' => (int)$request->item_id,
            'title' => $request->title,
            'message' => $request->message,
            'status' => (int)$request->status,
            'created_by' => (int)$request->created_by,
            'created_date' => $request->created_date,
        ]);
    }

    function itemlogdelete(Request $request){
        $delete = RequisitionItemLog::destroy($request->id);
        return $delete
            ? $this->jsonResult(true, 'Record deleted succesfully!')
            : $this->jsonResult(false, 'Record deleted failed!');
    }

    function recsuplist(Request $request){
        $p = $this->dtParams($request);
        $columnName = in_array($p['columnName'], ['actionData', ''], true)
            ? 'stc_cust_super_requisition_rec_items_fr_supervisor_id'
            : $p['columnName'];

        $q = RequisitionReceived::query();
        if($p['searchValue'] !== ''){
            $sv = '%'.$p['searchValue'].'%';
            $q->where(function($w) use ($sv){
                $w->where('stc_cust_super_requisition_rec_items_fr_supervisor_id', 'like', $sv)
                  ->orWhere('stc_cust_super_requisition_rec_items_fr_supervisor_rqitemid', 'like', $sv)
                  ->orWhere('stc_cust_super_requisition_rec_items_fr_supervisor_rqitemqty', 'like', $sv);
            });
        }
        $totalRecords = RequisitionReceived::count();
        $totalRecordswithFilter = (clone $q)->count();
        $records = $q->orderBy($columnName, $p['columnSortOrder'])
            ->skip($p['start'])->take($p['rowperpage'])->get();

        $data_arr = [];
        foreach($records as $record){
            $id = $record->stc_cust_super_requisition_rec_items_fr_supervisor_id;
            $data_arr[] = [
                "stc_cust_super_requisition_rec_items_fr_supervisor_id" => $id,
                "stc_cust_super_requisition_rec_items_fr_supervisor_date" => $record->stc_cust_super_requisition_rec_items_fr_supervisor_date,
                "stc_cust_super_requisition_rec_items_fr_supervisor_rqitemid" => $record->stc_cust_super_requisition_rec_items_fr_supervisor_rqitemid,
                "stc_cust_super_requisition_rec_items_fr_supervisor_rqitemqty" => $record->stc_cust_super_requisition_rec_items_fr_supervisor_rqitemqty,
                "actionData" => $this->actionHtml($id, 'edit-recsup-btn', 'edit-recsup-modal', 'delete-modal-recsup', 'deleterecsup_id')
            ];
        }
        return $this->dtResponse($p['draw'], $totalRecords, $totalRecordswithFilter, $data_arr);
    }

    function recsupget(Request $request){
        $record = RequisitionReceived::where('stc_cust_super_requisition_rec_items_fr_supervisor_id', $request->id)->first();
        return $record ? $this->jsonResult(true, 'ok', $record) : $this->jsonResult(false, 'Record not found!');
    }

    function recsupupdate(Request $request){
        $record = RequisitionReceived::where('stc_cust_super_requisition_rec_items_fr_supervisor_id', $request->id)->first();
        return $this->saveOrFail($record, [
            'stc_cust_super_requisition_rec_items_fr_supervisor_date' => $request->date,
            'stc_cust_super_requisition_rec_items_fr_supervisor_rqitemid' => (int)$request->rqitemid,
            'stc_cust_super_requisition_rec_items_fr_supervisor_rqitemqty' => $request->rqitemqty,
        ]);
    }

    function recsupdelete(Request $request){
        $delete = RequisitionReceived::destroy($request->id);
        return $delete
            ? $this->jsonResult(true, 'Record deleted succesfully!')
            : $this->jsonResult(false, 'Record deleted failed!');
    }

    private function issueLabel($type){
        if($type === 'wrong_material') return 'Wrong Material';
        if($type === 'fault_material') return 'Fault Material';
        return 'Message';
    }

    function commlist(Request $request){
        $p = $this->dtParams($request);
        $site = trim((string)$request->get('site', ''));
        $item = trim((string)$request->get('item', ''));
        $reqId = trim((string)$request->get('req_id', ''));
        $user = trim((string)$request->get('user', ''));
        $issue = trim((string)$request->get('issue_type', ''));

        $base = DB::table('stc_req_item_communicate as C')
            ->join(DB::raw('(
                SELECT req_item_id, MAX(id) AS max_id, COUNT(*) AS msg_count
                FROM stc_req_item_communicate
                WHERE status = 1
                GROUP BY req_item_id
            ) as L'), function($j){
                $j->on('L.req_item_id', '=', 'C.req_item_id')->on('L.max_id', '=', 'C.id');
            })
            ->leftJoin('stc_cust_super_requisition_list_items as I', 'I.stc_cust_super_requisition_list_id', '=', 'C.req_item_id')
            ->leftJoin('stc_cust_super_requisition_list as RL', 'RL.stc_cust_super_requisition_list_id', '=', 'C.req_id')
            ->leftJoin('stc_cust_project as P', 'P.stc_cust_project_id', '=', 'RL.stc_cust_super_requisition_list_project_id')
            ->where('C.status', 1);

        $applyFilters = function($q) use ($site, $item, $reqId, $user, $issue, $p){
            if($site !== ''){
                $q->where('P.stc_cust_project_title', 'like', '%'.$site.'%');
            }
            if($item !== ''){
                $q->where('I.stc_cust_super_requisition_list_items_title', 'like', '%'.$item.'%');
            }
            if($reqId !== ''){
                $q->where(function($w) use ($reqId){
                    $w->where('C.req_id', 'like', '%'.$reqId.'%')
                      ->orWhere('C.req_item_id', 'like', '%'.$reqId.'%');
                });
            }
            if($user !== ''){
                $q->where('C.username', 'like', '%'.$user.'%');
            }
            if($issue !== '' && $issue !== 'all'){
                $q->where('C.issue_type', $issue);
            }
            if($p['searchValue'] !== ''){
                $sv = $p['searchValue'];
                $q->where(function($w) use ($sv){
                    $w->where('P.stc_cust_project_title', 'like', '%'.$sv.'%')
                      ->orWhere('I.stc_cust_super_requisition_list_items_title', 'like', '%'.$sv.'%')
                      ->orWhere('C.message', 'like', '%'.$sv.'%')
                      ->orWhere('C.username', 'like', '%'.$sv.'%')
                      ->orWhere('C.req_id', 'like', '%'.$sv.'%')
                      ->orWhere('C.req_item_id', 'like', '%'.$sv.'%');
                });
            }
            return $q;
        };

        $totalRecords = (clone $base)->count();
        $filteredQ = $applyFilters(clone $base);
        $totalFiltered = (clone $filteredQ)->count();

        $sortMap = [
            'req_item_id' => 'C.req_item_id',
            'req_id' => 'C.req_id',
            'site_name' => 'P.stc_cust_project_title',
            'item_title' => 'I.stc_cust_super_requisition_list_items_title',
            'username' => 'C.username',
            'issue_type' => 'C.issue_type',
            'msg_count' => 'L.msg_count',
            'created_date' => 'C.created_date',
            'id' => 'C.id',
        ];
        $sortCol = $sortMap[$p['columnName']] ?? 'C.id';
        $sortDir = strtolower($p['columnSortOrder']) === 'asc' ? 'asc' : 'desc';

        $rows = $filteredQ
            ->select(
                'C.id',
                'C.req_item_id',
                'C.req_id',
                'C.message',
                'C.image_url',
                'C.issue_type',
                'C.username',
                'C.user_type',
                'C.created_date',
                'L.msg_count',
                'I.stc_cust_super_requisition_list_items_title as item_title',
                'P.stc_cust_project_title as site_name'
            )
            ->orderBy($sortCol, $sortDir)
            ->skip((int)$p['start'])
            ->take((int)$p['rowperpage'])
            ->get();

        $data = [];
        foreach($rows as $r){
            $preview = trim((string)$r->message);
            if($preview === '' && !empty($r->image_url)){
                $preview = 'Photo attached';
            }
            if(mb_strlen($preview) > 80){
                $preview = mb_substr($preview, 0, 80).'…';
            }
            $data[] = [
                'id' => (int)$r->id,
                'req_item_id' => (int)$r->req_item_id,
                'req_id' => (int)$r->req_id,
                'site_name' => (string)($r->site_name ?? '—'),
                'item_title' => (string)($r->item_title ?? '—'),
                'username' => (string)$r->username,
                'user_type' => (string)$r->user_type,
                'issue_type' => (string)$r->issue_type,
                'issue_label' => $this->issueLabel($r->issue_type),
                'message' => $preview,
                'msg_count' => (int)$r->msg_count,
                'created_date' => $r->created_date ? date('d-m-Y h:i A', strtotime($r->created_date)) : '',
                'actionData' => '<button type="button" class="btn btn-info btn-sm stc-comm-open-btn" data-req-item-id="'.(int)$r->req_item_id.'" data-req-id="'.(int)$r->req_id.'" data-item-title="'.e((string)($r->item_title ?? '')).'" data-site-name="'.e((string)($r->site_name ?? '')).'" title="Open chat"><i class="fas fa-comments"></i> Open</button> '
                    .'<button type="button" class="btn btn-danger btn-sm stc-comm-del-thread-btn" data-req-item-id="'.(int)$r->req_item_id.'" title="Delete whole chat"><i class="fas fa-trash"></i> Delete</button>',
            ];
        }

        return $this->dtResponse($p['draw'], $totalRecords, $totalFiltered, $data);
    }

    function commthread(Request $request){
        $reqItemId = (int)$request->get('req_item_id', 0);
        if($reqItemId <= 0){
            return $this->jsonResult(false, 'Invalid item.');
        }

        $meta = DB::table('stc_cust_super_requisition_list_items as I')
            ->leftJoin('stc_cust_super_requisition_list as RL', 'RL.stc_cust_super_requisition_list_id', '=', 'I.stc_cust_super_requisition_list_items_req_id')
            ->leftJoin('stc_cust_project as P', 'P.stc_cust_project_id', '=', 'RL.stc_cust_super_requisition_list_project_id')
            ->where('I.stc_cust_super_requisition_list_id', $reqItemId)
            ->select(
                'I.stc_cust_super_requisition_list_id as req_item_id',
                'I.stc_cust_super_requisition_list_items_req_id as req_id',
                'I.stc_cust_super_requisition_list_items_title as item_title',
                'P.stc_cust_project_title as site_name'
            )
            ->first();

        $messages = ReqItemCommunicate::where('req_item_id', $reqItemId)
            ->where('status', 1)
            ->orderBy('id', 'asc')
            ->get()
            ->map(function($m){
                return [
                    'id' => (int)$m->id,
                    'req_item_id' => (int)$m->req_item_id,
                    'req_id' => (int)$m->req_id,
                    'message' => (string)$m->message,
                    'image_url' => (string)$m->image_url,
                    'issue_type' => (string)$m->issue_type,
                    'issue_label' => $this->issueLabel($m->issue_type),
                    'username' => (string)$m->username,
                    'user_type' => (string)$m->user_type,
                    'created_date' => $m->created_date ? date('d-m-Y h:i A', strtotime($m->created_date)) : '',
                ];
            })
            ->values();

        return $this->jsonResult(true, 'ok', [
            'req_item_id' => $reqItemId,
            'req_id' => $meta ? (int)$meta->req_id : 0,
            'item_title' => $meta ? (string)$meta->item_title : '',
            'site_name' => $meta ? (string)$meta->site_name : '',
            'messages' => $messages,
        ]);
    }

    function commdelete(Request $request){
        $id = (int)$request->get('id', 0);
        if($id <= 0){
            return $this->jsonResult(false, 'Invalid message.');
        }
        $row = ReqItemCommunicate::where('id', $id)->where('status', 1)->first();
        if(!$row){
            return $this->jsonResult(false, 'Message not found.');
        }
        $row->status = 0;
        $row->save();
        return $this->jsonResult(true, 'Message removed.', [
            'id' => $id,
            'req_item_id' => (int)$row->req_item_id,
        ]);
    }

    function commdeletethread(Request $request){
        $reqItemId = (int)$request->get('req_item_id', 0);
        if($reqItemId <= 0){
            return $this->jsonResult(false, 'Invalid chat.');
        }
        $updated = ReqItemCommunicate::where('req_item_id', $reqItemId)
            ->where('status', 1)
            ->update(['status' => 0]);
        if($updated < 1){
            return $this->jsonResult(false, 'Chat not found or already deleted.');
        }
        return $this->jsonResult(true, 'Whole chat deleted.', [
            'req_item_id' => $reqItemId,
            'deleted' => (int)$updated,
        ]);
    }

    public function operate(Request $request, $id = null){
        $data = [
            'page_title' => 'Requisition Operate',
            'q' => trim((string)$request->get('q', '')),
            'req' => null,
            'project_title' => '',
            'supervisor_name' => '',
            'items' => collect(),
            'dispatches' => collect(),
            'combinerLinks' => collect(),
            'combiners' => collect(),
            'logs' => collect(),
            'received' => collect(),
            'matches' => collect(),
            'notFound' => false,
        ];

        $reqId = $id ? (int)$id : 0;
        $q = $data['q'];

        if(!$reqId && $q !== ''){
            if(ctype_digit($q)){
                $reqId = (int)$q;
            }else{
                $matches = $this->searchRequisitions($q);
                if($matches->count() === 1){
                    return redirect(url('/branch/stc/requisitions/operate/'.$matches[0]->stc_cust_super_requisition_list_id));
                }
                $data['matches'] = $matches;
                return view('pages.requisition-operate', $data);
            }
        }

        if($reqId){
            return view('pages.requisition-operate', $this->operatePayload($reqId, $q));
        }

        return view('pages.requisition-operate', $data);
    }

    private function searchRequisitions($q){
        return Requisition::leftjoin('stc_cust_project','stc_cust_project.stc_cust_project_id','=','stc_cust_super_requisition_list.stc_cust_super_requisition_list_project_id')
            ->leftjoin('stc_cust_pro_supervisor','stc_cust_pro_supervisor.stc_cust_pro_supervisor_id','=','stc_cust_super_requisition_list.stc_cust_super_requisition_list_super_id')
            ->where(function($w) use ($q){
                $w->where('stc_cust_project.stc_cust_project_title', 'like', '%'.$q.'%');
                if(ctype_digit((string)$q)){
                    $w->orWhere('stc_cust_super_requisition_list.stc_cust_super_requisition_list_id', (int)$q);
                }
            })
            ->select(
                'stc_cust_super_requisition_list.stc_cust_super_requisition_list_id',
                'stc_cust_super_requisition_list.stc_cust_super_requisition_list_date',
                'stc_cust_super_requisition_list.stc_cust_super_requisition_list_status',
                'stc_cust_project.stc_cust_project_title',
                'stc_cust_pro_supervisor.stc_cust_pro_supervisor_fullname'
            )
            ->orderBy('stc_cust_super_requisition_list.stc_cust_super_requisition_list_id', 'desc')
            ->limit(30)
            ->get();
    }

    private function operatePayload($reqId, $q = ''){
        $row = Requisition::leftjoin('stc_cust_project','stc_cust_project.stc_cust_project_id','=','stc_cust_super_requisition_list.stc_cust_super_requisition_list_project_id')
            ->leftjoin('stc_cust_pro_supervisor','stc_cust_pro_supervisor.stc_cust_pro_supervisor_id','=','stc_cust_super_requisition_list.stc_cust_super_requisition_list_super_id')
            ->where('stc_cust_super_requisition_list.stc_cust_super_requisition_list_id', $reqId)
            ->select('stc_cust_super_requisition_list.*', 'stc_cust_project.stc_cust_project_title', 'stc_cust_pro_supervisor.stc_cust_pro_supervisor_fullname')
            ->first();

        $data = [
            'page_title' => 'Requisition Operate',
            'q' => $q !== '' ? $q : (string)$reqId,
            'req' => $row,
            'project_title' => $row ? $row->stc_cust_project_title : '',
            'supervisor_name' => $row ? $row->stc_cust_pro_supervisor_fullname : '',
            'items' => collect(),
            'dispatches' => collect(),
            'combinerLinks' => collect(),
            'combiners' => collect(),
            'logs' => collect(),
            'received' => collect(),
            'matches' => collect(),
            'notFound' => !$row,
        ];

        if(!$row){
            if($q !== '' && !ctype_digit((string)$q)){
                $data['matches'] = $this->searchRequisitions($q);
            }
            return $data;
        }

        $items = RequisitionItems::where('stc_cust_super_requisition_list_items_req_id', $reqId)
            ->orderBy('stc_cust_super_requisition_list_id', 'asc')
            ->get();
        $itemIds = $items->pluck('stc_cust_super_requisition_list_id')->filter()->values();

        $data['items'] = $items;
        $data['dispatches'] = RequisitionDispatch::where('stc_cust_super_requisition_list_items_rec_list_id', $reqId)
            ->orderBy('stc_cust_super_requisition_list_items_rec_id', 'asc')
            ->get();
        $data['combinerLinks'] = RequisitionCombinerReq::where('stc_requisition_combiner_req_requisition_id', $reqId)
            ->orderBy('stc_requisition_combiner_req_id', 'asc')
            ->get();
        $combIds = $data['combinerLinks']->pluck('stc_requisition_combiner_req_comb_id')->unique()->filter()->values();
        $data['combiners'] = $combIds->isEmpty()
            ? collect()
            : RequisitionCombiner::whereIn('stc_requisition_combiner_id', $combIds)->get();
        $data['logs'] = $itemIds->isEmpty()
            ? collect()
            : RequisitionItemLog::whereIn('item_id', $itemIds)->orderBy('id', 'desc')->get();
        $data['received'] = $itemIds->isEmpty()
            ? collect()
            : RequisitionReceived::whereIn('stc_cust_super_requisition_rec_items_fr_supervisor_rqitemid', $itemIds)
                ->orderBy('stc_cust_super_requisition_rec_items_fr_supervisor_id', 'asc')
                ->get();

        return $data;
    }
}
