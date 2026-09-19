<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>STC Associates | {{!empty($page_title) ? $page_title : ''}}</title>
  @include('layouts.head')
  <style>
    .op-table thead { background: #fff; position: sticky; top: 0; }
    .op-card .card-header { font-weight: 600; }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  @include('layouts.nav')
  @include('layouts.aside')

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Requisition Operate</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('/branch/stc/requisitions') }}">Requisitions</a></li>
              <li class="breadcrumb-item active">Operate</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="card card-outline card-success">
          <div class="card-body">
            <form method="get" action="{{ url('/branch/stc/requisitions/operate') }}" class="form-inline mb-3">
              <label class="mr-2">Search by Req ID / project</label>
              <input type="text" name="q" class="form-control mr-2" value="{{ $q ?? '' }}" placeholder="e.g. 12123" style="min-width:240px;">
              <button type="submit" class="btn btn-success">Load</button>
              <a href="{{ url('/branch/stc/requisitions') }}" class="btn btn-default ml-2">Back to list</a>
            </form>

            @if(!empty($matches) && $matches->count())
              <div class="alert alert-info">Multiple requisitions found. Select one.</div>
              <table class="table table-bordered table-sm">
                <thead>
                  <tr>
                    <th>Req ID</th>
                    <th>Project</th>
                    <th>Supervisor</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($matches as $m)
                    <tr>
                      <td>{{ $m->stc_cust_super_requisition_list_id }}</td>
                      <td>{{ $m->stc_cust_project_title }}</td>
                      <td>{{ $m->stc_cust_pro_supervisor_fullname }}</td>
                      <td>{{ $m->stc_cust_super_requisition_list_status }}</td>
                      <td>{{ $m->stc_cust_super_requisition_list_date }}</td>
                      <td><a class="btn btn-success btn-sm" href="{{ url('/branch/stc/requisitions/operate/'.$m->stc_cust_super_requisition_list_id) }}">Open</a></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @elseif(!empty($notFound))
              <div class="alert alert-warning">No requisition found for that search.</div>
            @elseif(empty($req))
              <div class="alert alert-secondary mb-0">Enter a requisition ID or project name to load connected records.</div>
            @endif
          </div>
        </div>

        @if(!empty($req))
          @php
            $reqStatuses = [1=>'Process',2=>'Passed',3=>'Procurement',4=>'Completed'];
            $itemStatuses = [1=>'Allow',2=>'Not Allowed',3=>'Approved',5=>'Received',6=>'Rejected'];
            $canDelItems = $items->count() > 1;
            $canDelDispatch = $dispatches->count() > 1;
            $canDelCombiners = $combiners->count() > 1;
            $canDelLinks = $combinerLinks->count() > 1;
            $canDelReceived = $received->count() > 1;
            $editBtn = function($id, $cls, $modal){
              return '<a href="javascript:void(0)" class="btn btn-primary btn-sm '.$cls.'" data-toggle="modal" data-target="#'.$modal.'" id="'.$id.'" title="Edit"><i class="fas fa-edit"></i></a>';
            };
            $delBtn = function($id, $modal, $input){
              return ' <a href="javascript:void(0)" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#'.$modal.'" onclick="$(\'#'.$input.'\').val(\''.$id.'\')" title="Delete"><i class="fas fa-trash"></i></a>';
            };
          @endphp

          <div class="card card-primary op-card">
            <div class="card-header">Requisition details</div>
            <div class="card-body table-responsive p-0">
              <table class="table table-bordered table-striped mb-0">
                <thead>
                  <tr>
                    <th>Id</th>
                    <th>SDL ID</th>
                    <th>Project Name</th>
                    <th>Created By</th>
                    <th>Status</th>
                    <th>Approved By</th>
                    <th>Created Date</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{ $req->stc_cust_super_requisition_list_id }}</td>
                    <td>{{ $req->stc_cust_super_requisition_list_sdlid }}</td>
                    <td>{{ $project_title }}</td>
                    <td>{{ $supervisor_name }}</td>
                    <td>{{ $reqStatuses[$req->stc_cust_super_requisition_list_status] ?? $req->stc_cust_super_requisition_list_status }}</td>
                    <td>{{ $req->stc_cust_super_requisition_list_approved_by }}</td>
                    <td>{{ $req->stc_cust_super_requisition_list_date }}</td>
                    <td>{!! $editBtn($req->stc_cust_super_requisition_list_id, 'edit-req-btn', 'edit-req-modal') !!}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="card card-info op-card">
            <div class="card-header">Requisition items</div>
            <div class="card-body table-responsive p-0">
              <table class="table table-bordered table-striped table-sm op-table mb-0">
                <thead>
                  <tr>
                    <th>Id</th>
                    <th>Req ID</th>
                    <th>Item Desc</th>
                    <th>Unit</th>
                    <th>Req Qty</th>
                    <th>Approved Qty</th>
                    <th>Final Qty</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($items as $item)
                    <tr>
                      <td>{{ $item->stc_cust_super_requisition_list_id }}</td>
                      <td>{{ $item->stc_cust_super_requisition_list_items_req_id }}</td>
                      <td>{{ $item->stc_cust_super_requisition_list_items_title }}</td>
                      <td>{{ $item->stc_cust_super_requisition_list_items_unit }}</td>
                      <td>{{ $item->stc_cust_super_requisition_list_items_reqqty }}</td>
                      <td>{{ $item->stc_cust_super_requisition_list_items_approved_qty }}</td>
                      <td>{{ $item->stc_cust_super_requisition_items_finalqty }}</td>
                      <td>{{ $item->stc_cust_super_requisition_items_priority == 2 ? 'Urgent' : 'Normal' }}</td>
                      <td>{{ $itemStatuses[$item->stc_cust_super_requisition_list_items_status] ?? $item->stc_cust_super_requisition_list_items_status }}</td>
                      <td>
                        {!! $editBtn($item->stc_cust_super_requisition_list_id, 'edit-req-item-btn', 'edit-req-item-modal') !!}
                        @if($canDelItems){!! $delBtn($item->stc_cust_super_requisition_list_id, 'delete-modal-item', 'deletereqitem_id') !!}@endif
                      </td>
                    </tr>
                  @empty
                    <tr><td colspan="10" class="text-center text-muted">No items linked to this requisition.</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <div class="card card-warning op-card">
            <div class="card-header">Dispatched</div>
            <div class="card-body table-responsive p-0">
              <table class="table table-bordered table-striped table-sm op-table mb-0">
                <thead>
                  <tr>
                    <th>Id</th>
                    <th>Req ID</th>
                    <th>Req Item ID</th>
                    <th>Product ID</th>
                    <th>Purchase ID</th>
                    <th>Quantity</th>
                    <th>Dispatched Date</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($dispatches as $dis)
                    <tr>
                      <td>{{ $dis->stc_cust_super_requisition_list_items_rec_id }}</td>
                      <td>{{ $dis->stc_cust_super_requisition_list_items_rec_list_id }}</td>
                      <td>{{ $dis->stc_cust_super_requisition_list_items_rec_list_item_id }}</td>
                      <td>{{ $dis->stc_cust_super_requisition_list_items_rec_list_pd_id }}</td>
                      <td>{{ $dis->stc_cust_super_requisition_list_items_rec_list_poaid }}</td>
                      <td>{{ $dis->stc_cust_super_requisition_list_items_rec_recqty }}</td>
                      <td>{{ $dis->stc_cust_super_requisition_list_items_rec_date }}</td>
                      <td>
                        {!! $editBtn($dis->stc_cust_super_requisition_list_items_rec_id, 'edit-req-itemdis-btn', 'edit-req-itemdis-modal') !!}
                        @if($canDelDispatch){!! $delBtn($dis->stc_cust_super_requisition_list_items_rec_id, 'delete-modal-itemrec', 'deletereqitemdis_id') !!}@endif
                      </td>
                    </tr>
                  @empty
                    <tr><td colspan="8" class="text-center text-muted">No dispatched rows for this requisition.</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <div class="card card-secondary op-card">
            <div class="card-header">Combiner</div>
            <div class="card-body table-responsive p-0">
              <table class="table table-bordered table-striped table-sm op-table mb-0">
                <thead>
                  <tr>
                    <th>Id</th>
                    <th>Date</th>
                    <th>Reference</th>
                    <th>Agent ID</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($combiners as $comb)
                    <tr>
                      <td>{{ $comb->stc_requisition_combiner_id }}</td>
                      <td>{{ $comb->stc_requisition_combiner_date }}</td>
                      <td>{{ $comb->stc_requisition_combiner_refrence }}</td>
                      <td>{{ $comb->stc_requisition_combiner_agent_id }}</td>
                      <td>{{ $comb->stc_requisition_combiner_status == 2 ? 'Accepted' : 'Process' }}</td>
                      <td>
                        {!! $editBtn($comb->stc_requisition_combiner_id, 'edit-combiner-btn', 'edit-combiner-modal') !!}
                        @if($canDelCombiners){!! $delBtn($comb->stc_requisition_combiner_id, 'delete-modal-combiner', 'deletecombiner_id') !!}@endif
                      </td>
                    </tr>
                  @empty
                    <tr><td colspan="6" class="text-center text-muted">No combiner records linked to this requisition.</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <div class="card card-secondary op-card">
            <div class="card-header">Combiner links</div>
            <div class="card-body table-responsive p-0">
              <table class="table table-bordered table-striped table-sm op-table mb-0">
                <thead>
                  <tr>
                    <th>Id</th>
                    <th>Combiner ID</th>
                    <th>Requisition ID</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($combinerLinks as $link)
                    <tr>
                      <td>{{ $link->stc_requisition_combiner_req_id }}</td>
                      <td>{{ $link->stc_requisition_combiner_req_comb_id }}</td>
                      <td>{{ $link->stc_requisition_combiner_req_requisition_id }}</td>
                      <td>
                        {!! $editBtn($link->stc_requisition_combiner_req_id, 'edit-combinerreq-btn', 'edit-combinerreq-modal') !!}
                        @if($canDelLinks){!! $delBtn($link->stc_requisition_combiner_req_id, 'delete-modal-combinerreq', 'deletecombinerreq_id') !!}@endif
                      </td>
                    </tr>
                  @empty
                    <tr><td colspan="4" class="text-center text-muted">No combiner links for this requisition.</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <div class="card card-dark op-card">
            <div class="card-header">Item logs</div>
            <div class="card-body table-responsive p-0">
              <table class="table table-bordered table-striped table-sm op-table mb-0">
                <thead>
                  <tr>
                    <th>Id</th>
                    <th>Item ID</th>
                    <th>Title</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Created Date</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($logs as $log)
                    <tr>
                      <td>{{ $log->id }}</td>
                      <td>{{ $log->item_id }}</td>
                      <td>{{ $log->title }}</td>
                      <td>{{ $log->message }}</td>
                      <td>{{ $log->status }}</td>
                      <td>{{ $log->created_by }}</td>
                      <td>{{ $log->created_date }}</td>
                      <td>
                        {!! $editBtn($log->id, 'edit-itemlog-btn', 'edit-itemlog-modal') !!}
                        {!! $delBtn($log->id, 'delete-modal-itemlog', 'deleteitemlog_id') !!}
                      </td>
                    </tr>
                  @empty
                    <tr><td colspan="8" class="text-center text-muted">No logs for this requisition's items.</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <div class="card card-success op-card">
            <div class="card-header">Received</div>
            <div class="card-body table-responsive p-0">
              <table class="table table-bordered table-striped table-sm op-table mb-0">
                <thead>
                  <tr>
                    <th>Id</th>
                    <th>Date</th>
                    <th>Req Item ID</th>
                    <th>Quantity</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($received as $rec)
                    <tr>
                      <td>{{ $rec->stc_cust_super_requisition_rec_items_fr_supervisor_id }}</td>
                      <td>{{ $rec->stc_cust_super_requisition_rec_items_fr_supervisor_date }}</td>
                      <td>{{ $rec->stc_cust_super_requisition_rec_items_fr_supervisor_rqitemid }}</td>
                      <td>{{ $rec->stc_cust_super_requisition_rec_items_fr_supervisor_rqitemqty }}</td>
                      <td>
                        {!! $editBtn($rec->stc_cust_super_requisition_rec_items_fr_supervisor_id, 'edit-recsup-btn', 'edit-recsup-modal') !!}
                        @if($canDelReceived){!! $delBtn($rec->stc_cust_super_requisition_rec_items_fr_supervisor_id, 'delete-modal-recsup', 'deleterecsup_id') !!}@endif
                      </td>
                    </tr>
                  @empty
                    <tr><td colspan="5" class="text-center text-muted">No received rows for this requisition's items.</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        @endif
      </div>
    </section>
  </div>

  @include('layouts.footer')
  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>
@include('layouts.ajax_foot')
@include('pages.partials.requisition-modals')

<script>
  $(document).ready(function() {
    $.ajaxSetup({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    function swalSuccess(icon, message){
      var Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
      });
      Toast.fire({ icon: icon, title: message });
    }
    function toDtLocal(val){
      if(!val){ return ''; }
      return String(val).replace(' ', 'T').substring(0, 16);
    }
    function fromDtLocal(val){
      if(!val){ return ''; }
      return val.length === 16 ? val.replace('T', ' ') + ':00' : val.replace('T', ' ');
    }
    function afterOk(){
      setTimeout(function(){ location.reload(); }, 400);
    }

    function bindEdit(btnClass, getUrl, fillFn){
      $('body').delegate(btnClass, 'click', function(){
        var id = $(this).attr('id');
        $.ajax({
          type: 'GET',
          url: getUrl,
          data: { id: id },
          success: function(response){
            if(response.success && response.data){
              fillFn(response.data);
            }else{
              swalSuccess('error', response.message || 'Failed to load record');
            }
          },
          error: function(){ swalSuccess('error', 'Failed to load record'); }
        });
      });
    }

    function bindSave(btnClass, updateUrl, collectFn, modalSelector){
      $('body').delegate(btnClass, 'click', function(){
        var data = collectFn();
        data._token = "{{ csrf_token() }}";
        $.ajax({
          type: 'POST',
          url: updateUrl,
          data: data,
          success: function(response){
            if(response.success){
              swalSuccess('success', response.message || 'Record updated.');
              $(modalSelector).modal('hide');
              afterOk();
            }else{
              swalSuccess('error', response.message || 'Update failed');
            }
          },
          error: function(){ swalSuccess('error', 'Update failed'); }
        });
      });
    }

    function bindDelete(btnClass, idInput, deleteUrl){
      $('body').delegate(btnClass, 'click', function(e){
        e.preventDefault();
        $.ajax({
          type: 'get',
          data: { id: $(idInput).val() },
          url: deleteUrl,
          success: function(response){
            if(response.success == true){
              swalSuccess('success', 'Record deleted.');
              $('.modal').modal('hide');
              afterOk();
            }else{
              swalSuccess('error', response.message);
            }
          }
        });
      });
    }

    bindEdit('.edit-req-btn', "{{ url('/branch/stc/requisitions/get') }}", function(r){
      $('#edit-req-id').val(r.stc_cust_super_requisition_list_id);
      $('#edit-req-date').val(toDtLocal(r.stc_cust_super_requisition_list_date));
      $('#edit-req-sdlid').val(r.stc_cust_super_requisition_list_sdlid);
      $('#edit-req-super-id').val(r.stc_cust_super_requisition_list_super_id);
      $('#edit-req-project-id').val(r.stc_cust_super_requisition_list_project_id);
      $('#edit-req-status').val(r.stc_cust_super_requisition_list_status);
      $('#edit-req-approved-by').val(r.stc_cust_super_requisition_list_approved_by);
    });
    bindSave('.save-req-btn', "{{ url('/branch/stc/requisitions/update') }}", function(){
      return {
        id: $('#edit-req-id').val(),
        date: fromDtLocal($('#edit-req-date').val()),
        sdlid: $('#edit-req-sdlid').val(),
        super_id: $('#edit-req-super-id').val(),
        project_id: $('#edit-req-project-id').val(),
        status: $('#edit-req-status').val(),
        approved_by: $('#edit-req-approved-by').val()
      };
    }, '#edit-req-modal');

    bindEdit('.edit-req-item-btn', "{{ url('/branch/stc/requisitions/itemget') }}", function(r){
      $('#edit-item-id').val(r.stc_cust_super_requisition_list_id);
      $('#edit-item-req-id').val(r.stc_cust_super_requisition_list_items_req_id);
      $('#edit-item-title').val(r.stc_cust_super_requisition_list_items_title);
      $('#edit-item-unit').val(r.stc_cust_super_requisition_list_items_unit);
      $('#edit-item-reqqty').val(r.stc_cust_super_requisition_list_items_reqqty);
      $('#edit-item-approved-qty').val(r.stc_cust_super_requisition_list_items_approved_qty);
      $('#edit-item-finalqty').val(r.stc_cust_super_requisition_items_finalqty);
      $('#edit-item-acceptby').val(r.stc_cust_super_requisition_list_items_acceptby);
      $('#edit-item-type').val(r.stc_cust_super_requisition_items_type);
      $('#edit-item-priority').val(r.stc_cust_super_requisition_items_priority);
      $('#edit-item-product-id').val(r.stc_cust_super_requisition_list_items_product_id);
      $('#edit-item-status').val(r.stc_cust_super_requisition_list_items_status);
      $('#edit-item-return-accepted').val(r.stc_cust_super_requisition_list_items_return_accepted);
    });
    bindSave('.save-req-item-btn', "{{ url('/branch/stc/requisitions/itemupdate') }}", function(){
      return {
        id: $('#edit-item-id').val(),
        req_id: $('#edit-item-req-id').val(),
        title: $('#edit-item-title').val(),
        unit: $('#edit-item-unit').val(),
        reqqty: $('#edit-item-reqqty').val(),
        approved_qty: $('#edit-item-approved-qty').val(),
        finalqty: $('#edit-item-finalqty').val(),
        acceptby: $('#edit-item-acceptby').val(),
        type: $('#edit-item-type').val(),
        priority: $('#edit-item-priority').val(),
        product_id: $('#edit-item-product-id').val(),
        status: $('#edit-item-status').val(),
        return_accepted: $('#edit-item-return-accepted').val()
      };
    }, '#edit-req-item-modal');
    bindDelete('.delete-req-itembtn', '#deletereqitem_id', "{{ url('/branch/stc/requisitions/itemdelete') }}");

    bindEdit('.edit-req-itemdis-btn', "{{ url('/branch/stc/requisitions/itemdisget') }}", function(r){
      $('#edit-dis-id').val(r.stc_cust_super_requisition_list_items_rec_id);
      $('#edit-dis-list-id').val(r.stc_cust_super_requisition_list_items_rec_list_id);
      $('#edit-dis-list-item-id').val(r.stc_cust_super_requisition_list_items_rec_list_item_id);
      $('#edit-dis-pd-id').val(r.stc_cust_super_requisition_list_items_rec_list_pd_id);
      $('#edit-dis-poaid').val(r.stc_cust_super_requisition_list_items_rec_list_poaid);
      $('#edit-dis-recqty').val(r.stc_cust_super_requisition_list_items_rec_recqty);
      $('#edit-dis-status').val(r.stc_cust_super_requisition_list_items_rec_status);
      $('#edit-dis-date').val(toDtLocal(r.stc_cust_super_requisition_list_items_rec_date));
    });
    bindSave('.save-req-itemdis-btn', "{{ url('/branch/stc/requisitions/itemdisupdate') }}", function(){
      return {
        id: $('#edit-dis-id').val(),
        list_id: $('#edit-dis-list-id').val(),
        list_item_id: $('#edit-dis-list-item-id').val(),
        pd_id: $('#edit-dis-pd-id').val(),
        poaid: $('#edit-dis-poaid').val(),
        recqty: $('#edit-dis-recqty').val(),
        status: $('#edit-dis-status').val(),
        date: fromDtLocal($('#edit-dis-date').val())
      };
    }, '#edit-req-itemdis-modal');
    bindDelete('.delete-req-itemdispbtn', '#deletereqitemdis_id', "{{ url('/branch/stc/requisitions/itemdisdelete') }}");

    bindEdit('.edit-combiner-btn', "{{ url('/branch/stc/requisitions/combinerget') }}", function(r){
      $('#edit-comb-id').val(r.stc_requisition_combiner_id);
      $('#edit-comb-date').val(toDtLocal(r.stc_requisition_combiner_date));
      $('#edit-comb-refrence').val(r.stc_requisition_combiner_refrence);
      $('#edit-comb-agent-id').val(r.stc_requisition_combiner_agent_id);
      $('#edit-comb-status').val(r.stc_requisition_combiner_status);
    });
    bindSave('.save-combiner-btn', "{{ url('/branch/stc/requisitions/combinerupdate') }}", function(){
      return {
        id: $('#edit-comb-id').val(),
        date: fromDtLocal($('#edit-comb-date').val()),
        refrence: $('#edit-comb-refrence').val(),
        agent_id: $('#edit-comb-agent-id').val(),
        status: $('#edit-comb-status').val()
      };
    }, '#edit-combiner-modal');
    bindDelete('.delete-combiner-btn', '#deletecombiner_id', "{{ url('/branch/stc/requisitions/combinerdelete') }}");

    bindEdit('.edit-combinerreq-btn', "{{ url('/branch/stc/requisitions/combinerreqget') }}", function(r){
      $('#edit-combreq-id').val(r.stc_requisition_combiner_req_id);
      $('#edit-combreq-comb-id').val(r.stc_requisition_combiner_req_comb_id);
      $('#edit-combreq-req-id').val(r.stc_requisition_combiner_req_requisition_id);
    });
    bindSave('.save-combinerreq-btn', "{{ url('/branch/stc/requisitions/combinerrequpdate') }}", function(){
      return {
        id: $('#edit-combreq-id').val(),
        comb_id: $('#edit-combreq-comb-id').val(),
        requisition_id: $('#edit-combreq-req-id').val()
      };
    }, '#edit-combinerreq-modal');
    bindDelete('.delete-combinerreq-btn', '#deletecombinerreq_id', "{{ url('/branch/stc/requisitions/combinerreqdelete') }}");

    bindEdit('.edit-itemlog-btn', "{{ url('/branch/stc/requisitions/itemlogget') }}", function(r){
      $('#edit-log-id').val(r.id);
      $('#edit-log-item-id').val(r.item_id);
      $('#edit-log-title').val(r.title);
      $('#edit-log-message').val(r.message);
      $('#edit-log-status').val(r.status);
      $('#edit-log-created-by').val(r.created_by);
      $('#edit-log-created-date').val(toDtLocal(r.created_date));
    });
    bindSave('.save-itemlog-btn', "{{ url('/branch/stc/requisitions/itemlogupdate') }}", function(){
      return {
        id: $('#edit-log-id').val(),
        item_id: $('#edit-log-item-id').val(),
        title: $('#edit-log-title').val(),
        message: $('#edit-log-message').val(),
        status: $('#edit-log-status').val(),
        created_by: $('#edit-log-created-by').val(),
        created_date: fromDtLocal($('#edit-log-created-date').val())
      };
    }, '#edit-itemlog-modal');
    bindDelete('.delete-itemlog-btn', '#deleteitemlog_id', "{{ url('/branch/stc/requisitions/itemlogdelete') }}");

    bindEdit('.edit-recsup-btn', "{{ url('/branch/stc/requisitions/recsupget') }}", function(r){
      $('#edit-rec-id').val(r.stc_cust_super_requisition_rec_items_fr_supervisor_id);
      $('#edit-rec-date').val(toDtLocal(r.stc_cust_super_requisition_rec_items_fr_supervisor_date));
      $('#edit-rec-rqitemid').val(r.stc_cust_super_requisition_rec_items_fr_supervisor_rqitemid);
      $('#edit-rec-rqitemqty').val(r.stc_cust_super_requisition_rec_items_fr_supervisor_rqitemqty);
    });
    bindSave('.save-recsup-btn', "{{ url('/branch/stc/requisitions/recsupupdate') }}", function(){
      return {
        id: $('#edit-rec-id').val(),
        date: fromDtLocal($('#edit-rec-date').val()),
        rqitemid: $('#edit-rec-rqitemid').val(),
        rqitemqty: $('#edit-rec-rqitemqty').val()
      };
    }, '#edit-recsup-modal');
    bindDelete('.delete-recsup-btn', '#deleterecsup_id', "{{ url('/branch/stc/requisitions/recsupdelete') }}");
  });
</script>
</body>
</html>
