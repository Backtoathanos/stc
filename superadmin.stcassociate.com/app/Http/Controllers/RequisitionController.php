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
}
