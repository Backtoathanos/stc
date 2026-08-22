<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>STC Associates | {{!empty($page_title) ? $page_title : ''}}</title>
  @include('layouts.head')
  <style>
    #example1 thead, #req-items thead, #req-itemsdis thead, #req-combiner thead, #req-combinerreq thead, #req-itemlog thead, #req-recsup thead{
        background: white;
        position: sticky;
        top: 0;
    }
    .nav-tabs .nav-link { white-space: nowrap; }
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
            <h1 class="m-0">Requisitions, Items & Dispatched</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="#">Branch</a></li>
              <li class="breadcrumb-item active"><a href="#">{{!empty($page_title) ? $page_title : ''}}</a></li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="card card-primary card-outline">
          <div class="card-body">
            <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="requisitions-tab" data-toggle="pill" href="#requisitions" role="tab">Requisitions</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="requisitions-items-tab" data-toggle="pill" href="#requisitions-items" role="tab">Requisitions Items</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="requisitions-itemsdis-tab" data-toggle="pill" href="#requisitions-itemsdis" role="tab">Dispatched</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="combiner-tab" data-toggle="pill" href="#combiner" role="tab">Combiner</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="combinerreq-tab" data-toggle="pill" href="#combinerreq" role="tab">Combiner Links</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="itemlog-tab" data-toggle="pill" href="#itemlog" role="tab">Item Logs</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="recsup-tab" data-toggle="pill" href="#recsup" role="tab">Received</a>
              </li>
            </ul>
            <div class="tab-content" id="custom-content-below-tabContent">
              <div class="tab-pane fade active show" id="requisitions" role="tabpanel">
                <div class="row"><div class="col-12"><p>@include('layouts._message')</p></div></div>
                <div class="row">
                  <div class="col-lg-12">
                    <div class="card">
                      <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th class="text-center">Id</th>
                              <th class="text-center">SDL ID</th>
                              <th class="text-center">Project Name</th>
                              <th class="text-center">Created By (Supervisor Name)</th>
                              <th class="text-center">Status</th>
                              <th class="text-center">Approved By</th>
                              <th class="text-center">Created Date</th>
                              <th class="text-center">Action</th>
                            </tr>
                          </thead>
                          <tbody></tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="tab-pane" id="requisitions-items" role="tabpanel">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="card">
                      <div class="card-body">
                        <table id="req-items" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th class="text-center">Id</th>
                              <th class="text-center">Req ID</th>
                              <th class="text-center">Item Desc</th>
                              <th class="text-center">Unit</th>
                              <th class="text-center">Req Quantity</th>
                              <th class="text-center">Approved Quantity</th>
                              <th class="text-center">Final Quanity</th>
                              <th class="text-center">Priority</th>
                              <th class="text-center">Status</th>
                              <th class="text-center">Action</th>
                            </tr>
                          </thead>
                          <tbody></tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="tab-pane" id="requisitions-itemsdis" role="tabpanel">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="card">
                      <div class="card-body">
                        <table id="req-itemsdis" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th class="text-center">Id</th>
                              <th class="text-center">Req ID</th>
                              <th class="text-center">Req Item ID</th>
                              <th class="text-center">Product ID</th>
                              <th class="text-center">Purchase ID</th>
                              <th class="text-center">Quantity</th>
                              <th class="text-center">Dispatched Date</th>
                              <th class="text-center">Action</th>
                            </tr>
                          </thead>
                          <tbody></tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="tab-pane" id="combiner" role="tabpanel">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="card">
                      <div class="card-body">
                        <table id="req-combiner" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th class="text-center">Id</th>
                              <th class="text-center">Date</th>
                              <th class="text-center">Reference</th>
                              <th class="text-center">Agent ID</th>
                              <th class="text-center">Status</th>
                              <th class="text-center">Action</th>
                            </tr>
                          </thead>
                          <tbody></tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="tab-pane" id="combinerreq" role="tabpanel">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="card">
                      <div class="card-body">
                        <table id="req-combinerreq" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th class="text-center">Id</th>
                              <th class="text-center">Combiner ID</th>
                              <th class="text-center">Requisition ID</th>
                              <th class="text-center">Action</th>
                            </tr>
                          </thead>
                          <tbody></tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="tab-pane" id="itemlog" role="tabpanel">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="card">
                      <div class="card-body">
                        <table id="req-itemlog" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th class="text-center">Id</th>
                              <th class="text-center">Item ID</th>
                              <th class="text-center">Title</th>
                              <th class="text-center">Message</th>
                              <th class="text-center">Status</th>
                              <th class="text-center">Created By</th>
                              <th class="text-center">Created Date</th>
                              <th class="text-center">Action</th>
                            </tr>
                          </thead>
                          <tbody></tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="tab-pane" id="recsup" role="tabpanel">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="card">
                      <div class="card-body">
                        <table id="req-recsup" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th class="text-center">Id</th>
                              <th class="text-center">Date</th>
                              <th class="text-center">Req Item ID</th>
                              <th class="text-center">Quantity</th>
                              <th class="text-center">Action</th>
                            </tr>
                          </thead>
                          <tbody></tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  @include('layouts.footer')
  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>
@include('layouts.ajax_foot')

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

    function initDt(selector, ajaxUrl, columns, actionIndex){
      if ($.fn.DataTable.isDataTable(selector)) {
        $(selector).DataTable().destroy();
      }
      var table = $(selector).DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        lengthChange: true,
        autoWidth: false,
        ajax: ajaxUrl,
        columns: columns,
        columnDefs: [
          { targets: actionIndex, orderable: false, className: 'text-center' }
        ],
        dom: 'Bfrtip',
        buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
      });
      table.buttons().container().appendTo(selector + '_wrapper .col-md-6:eq(0)');
    }

    function reloadDt(selector){
      if ($.fn.DataTable.isDataTable(selector)) {
        $(selector).DataTable().ajax.reload(null, false);
      }
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

    function bindSave(btnClass, updateUrl, collectFn, tableSelector, modalSelector){
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
              reloadDt(tableSelector);
            }else{
              swalSuccess('error', response.message || 'Update failed');
            }
          },
          error: function(){ swalSuccess('error', 'Update failed'); }
        });
      });
    }

    function bindDelete(btnClass, idInput, deleteUrl, tableSelector){
      $('body').delegate(btnClass, 'click', function(e){
        e.preventDefault();
        $.ajax({
          type: 'get',
          data: { id: $(idInput).val() },
          url: deleteUrl,
          success: function(response){
            if(response.success == true){
              swalSuccess('success', 'Record deleted.');
              reloadDt(tableSelector);
              $(this).closest('.modal').modal('hide');
              $('.modal').modal('hide');
            }else{
              swalSuccess('error', response.message);
            }
          }
        });
      });
    }

    initDt('#example1', "{{ url('/branch/stc/requisitions/list') }}", [
      { data: 'stc_cust_super_requisition_list_id' },
      { data: 'stc_cust_super_requisition_list_sdlid' },
      { data: 'stc_cust_project_title' },
      { data: 'stc_cust_pro_supervisor_fullname' },
      {
        data: 'stc_cust_super_requisition_list_status',
        render: function(data){
          if(data=="1") return 'Process';
          if(data=="2") return 'Passed';
          if(data=="3") return 'Procurement';
          if(data=="4") return 'Completed';
          return data;
        },
        className: 'text-center'
      },
      { data: 'stc_cust_super_requisition_list_approved_by' },
      { data: 'stc_cust_super_requisition_list_date' },
      { data: 'actionData' }
    ], 7);

    initDt('#req-items', "{{ url('/branch/stc/requisitions/itemlist') }}", [
      { data: 'stc_cust_super_requisition_list_id' },
      { data: 'stc_cust_super_requisition_list_items_req_id' },
      { data: 'stc_cust_super_requisition_list_items_title' },
      { data: 'stc_cust_super_requisition_list_items_unit' },
      { data: 'stc_cust_super_requisition_list_items_reqqty' },
      { data: 'stc_cust_super_requisition_list_items_approved_qty' },
      { data: 'stc_cust_super_requisition_items_finalqty' },
      {
        data: 'stc_cust_super_requisition_items_priority',
        render: function(data){ return data=="1" ? 'Normal' : 'Urgent'; },
        className: 'text-center'
      },
      {
        data: 'stc_cust_super_requisition_list_items_status',
        render: function(data){ return data=="1" ? 'Allow' : 'Not Allowed'; },
        className: 'text-center'
      },
      { data: 'actionData' }
    ], 9);

    initDt('#req-itemsdis', "{{ url('/branch/stc/requisitions/itemdislist') }}", [
      { data: 'stc_cust_super_requisition_list_items_rec_id' },
      { data: 'stc_cust_super_requisition_list_items_rec_list_id' },
      { data: 'stc_cust_super_requisition_list_items_rec_list_item_id' },
      { data: 'stc_cust_super_requisition_list_items_rec_list_pd_id' },
      { data: 'stc_cust_super_requisition_list_items_rec_list_poaid' },
      { data: 'stc_cust_super_requisition_list_items_rec_recqty' },
      { data: 'stc_cust_super_requisition_list_items_rec_date' },
      { data: 'actionData' }
    ], 7);

    initDt('#req-combiner', "{{ url('/branch/stc/requisitions/combinerlist') }}", [
      { data: 'stc_requisition_combiner_id' },
      { data: 'stc_requisition_combiner_date' },
      { data: 'stc_requisition_combiner_refrence' },
      { data: 'stc_requisition_combiner_agent_id' },
      {
        data: 'stc_requisition_combiner_status',
        render: function(data){ return data=="1" ? 'Process' : 'Accepted'; },
        className: 'text-center'
      },
      { data: 'actionData' }
    ], 5);

    initDt('#req-combinerreq', "{{ url('/branch/stc/requisitions/combinerreqlist') }}", [
      { data: 'stc_requisition_combiner_req_id' },
      { data: 'stc_requisition_combiner_req_comb_id' },
      { data: 'stc_requisition_combiner_req_requisition_id' },
      { data: 'actionData' }
    ], 3);

    initDt('#req-itemlog', "{{ url('/branch/stc/requisitions/itemloglist') }}", [
      { data: 'id' },
      { data: 'item_id' },
      { data: 'title' },
      { data: 'message' },
      { data: 'status' },
      { data: 'created_by' },
      { data: 'created_date' },
      { data: 'actionData' }
    ], 7);

    initDt('#req-recsup', "{{ url('/branch/stc/requisitions/recsuplist') }}", [
      { data: 'stc_cust_super_requisition_rec_items_fr_supervisor_id' },
      { data: 'stc_cust_super_requisition_rec_items_fr_supervisor_date' },
      { data: 'stc_cust_super_requisition_rec_items_fr_supervisor_rqitemid' },
      { data: 'stc_cust_super_requisition_rec_items_fr_supervisor_rqitemqty' },
      { data: 'actionData' }
    ], 4);

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
    }, '#example1', '#edit-req-modal');
    bindDelete('.delete-btn', '#delete_id', "{{ url('/branch/stc/requisitions/delete') }}", '#example1');

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
    }, '#req-items', '#edit-req-item-modal');
    bindDelete('.delete-req-itembtn', '#deletereqitem_id', "{{ url('/branch/stc/requisitions/itemdelete') }}", '#req-items');

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
    }, '#req-itemsdis', '#edit-req-itemdis-modal');
    bindDelete('.delete-req-itemdispbtn', '#deletereqitemdis_id', "{{ url('/branch/stc/requisitions/itemdisdelete') }}", '#req-itemsdis');

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
    }, '#req-combiner', '#edit-combiner-modal');
    bindDelete('.delete-combiner-btn', '#deletecombiner_id', "{{ url('/branch/stc/requisitions/combinerdelete') }}", '#req-combiner');

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
    }, '#req-combinerreq', '#edit-combinerreq-modal');
    bindDelete('.delete-combinerreq-btn', '#deletecombinerreq_id', "{{ url('/branch/stc/requisitions/combinerreqdelete') }}", '#req-combinerreq');

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
    }, '#req-itemlog', '#edit-itemlog-modal');
    bindDelete('.delete-itemlog-btn', '#deleteitemlog_id', "{{ url('/branch/stc/requisitions/itemlogdelete') }}", '#req-itemlog');

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
    }, '#req-recsup', '#edit-recsup-modal');
    bindDelete('.delete-recsup-btn', '#deleterecsup_id', "{{ url('/branch/stc/requisitions/recsupdelete') }}", '#req-recsup');
  });
</script>

</body>
</html>

@php
  $deleteModals = [
    ['id' => 'delete-modal', 'input' => 'delete_id', 'btn' => 'delete-btn'],
    ['id' => 'delete-modal-item', 'input' => 'deletereqitem_id', 'btn' => 'delete-req-itembtn'],
    ['id' => 'delete-modal-itemrec', 'input' => 'deletereqitemdis_id', 'btn' => 'delete-req-itemdispbtn'],
    ['id' => 'delete-modal-combiner', 'input' => 'deletecombiner_id', 'btn' => 'delete-combiner-btn'],
    ['id' => 'delete-modal-combinerreq', 'input' => 'deletecombinerreq_id', 'btn' => 'delete-combinerreq-btn'],
    ['id' => 'delete-modal-itemlog', 'input' => 'deleteitemlog_id', 'btn' => 'delete-itemlog-btn'],
    ['id' => 'delete-modal-recsup', 'input' => 'deleterecsup_id', 'btn' => 'delete-recsup-btn'],
  ];
@endphp
@foreach($deleteModals as $dm)
<div class="modal fade" id="{{ $dm['id'] }}">
  <div class="modal-dialog">
    <div class="modal-content bg-danger">
      <div class="modal-header">
        <h4 class="modal-title">Delete Record</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete these Records?</p>
        <p class="text-warning"><small>This action cannot be undone.</small></p>
        <input type="hidden" id="{{ $dm['input'] }}">
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default close-btn" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-outline-light {{ $dm['btn'] }}">Delete</button>
      </div>
    </div>
  </div>
</div>
@endforeach

<div class="modal fade" id="edit-req-modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Requisition</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="edit-req-id">
        <div class="row">
          <div class="col-md-6"><div class="form-group"><label>Date</label><input type="datetime-local" class="form-control" id="edit-req-date"></div></div>
          <div class="col-md-6"><div class="form-group"><label>SDL ID</label><input type="number" class="form-control" id="edit-req-sdlid"></div></div>
          <div class="col-md-6"><div class="form-group"><label>Supervisor ID</label><input type="number" class="form-control" id="edit-req-super-id"></div></div>
          <div class="col-md-6"><div class="form-group"><label>Project ID</label><input type="number" class="form-control" id="edit-req-project-id"></div></div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Status</label>
              <select class="form-control" id="edit-req-status">
                <option value="1">Process</option>
                <option value="2">Passed</option>
                <option value="3">Procurement</option>
                <option value="4">Completed</option>
              </select>
            </div>
          </div>
          <div class="col-md-6"><div class="form-group"><label>Approved By</label><input type="number" class="form-control" id="edit-req-approved-by"></div></div>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary save-req-btn">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="edit-req-item-modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Requisition Item</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="edit-item-id">
        <div class="row">
          <div class="col-md-6"><div class="form-group"><label>Req ID</label><input type="number" class="form-control" id="edit-item-req-id"></div></div>
          <div class="col-md-6"><div class="form-group"><label>Unit</label><input type="text" class="form-control" id="edit-item-unit"></div></div>
          <div class="col-md-12"><div class="form-group"><label>Item Description</label><textarea class="form-control" id="edit-item-title" rows="2"></textarea></div></div>
          <div class="col-md-4"><div class="form-group"><label>Req Qty</label><input type="number" step="0.01" class="form-control" id="edit-item-reqqty"></div></div>
          <div class="col-md-4"><div class="form-group"><label>Approved Qty</label><input type="number" step="0.01" class="form-control" id="edit-item-approved-qty"></div></div>
          <div class="col-md-4"><div class="form-group"><label>Final Qty</label><input type="number" step="0.01" class="form-control" id="edit-item-finalqty"></div></div>
          <div class="col-md-6"><div class="form-group"><label>Type</label><input type="text" class="form-control" id="edit-item-type"></div></div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Priority</label>
              <select class="form-control" id="edit-item-priority">
                <option value="1">Normal</option>
                <option value="2">Urgent</option>
              </select>
            </div>
          </div>
          <div class="col-md-4"><div class="form-group"><label>Product ID</label><input type="number" class="form-control" id="edit-item-product-id"></div></div>
          <div class="col-md-4"><div class="form-group"><label>Accept By</label><input type="number" class="form-control" id="edit-item-acceptby"></div></div>
          <div class="col-md-4">
            <div class="form-group">
              <label>Status</label>
              <select class="form-control" id="edit-item-status">
                <option value="1">Allow</option>
                <option value="2">Not Allowed</option>
                <option value="3">Approved</option>
                <option value="5">Received</option>
                <option value="6">Rejected</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Return Accepted</label>
              <select class="form-control" id="edit-item-return-accepted">
                <option value="0">No</option>
                <option value="1">Yes</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary save-req-item-btn">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="edit-req-itemdis-modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Dispatched Item</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="edit-dis-id">
        <div class="row">
          <div class="col-md-6"><div class="form-group"><label>Req ID</label><input type="number" class="form-control" id="edit-dis-list-id"></div></div>
          <div class="col-md-6"><div class="form-group"><label>Req Item ID</label><input type="number" class="form-control" id="edit-dis-list-item-id"></div></div>
          <div class="col-md-6"><div class="form-group"><label>Product ID</label><input type="number" class="form-control" id="edit-dis-pd-id"></div></div>
          <div class="col-md-6"><div class="form-group"><label>Purchase ID</label><input type="number" class="form-control" id="edit-dis-poaid"></div></div>
          <div class="col-md-4"><div class="form-group"><label>Quantity</label><input type="number" step="0.01" class="form-control" id="edit-dis-recqty"></div></div>
          <div class="col-md-4"><div class="form-group"><label>Status</label><input type="number" class="form-control" id="edit-dis-status"></div></div>
          <div class="col-md-4"><div class="form-group"><label>Dispatched Date</label><input type="datetime-local" class="form-control" id="edit-dis-date"></div></div>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary save-req-itemdis-btn">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="edit-combiner-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Combiner</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="edit-comb-id">
        <div class="form-group"><label>Date</label><input type="datetime-local" class="form-control" id="edit-comb-date"></div>
        <div class="form-group"><label>Reference</label><input type="text" class="form-control" id="edit-comb-refrence"></div>
        <div class="form-group"><label>Agent ID</label><input type="number" class="form-control" id="edit-comb-agent-id"></div>
        <div class="form-group">
          <label>Status</label>
          <select class="form-control" id="edit-comb-status">
            <option value="1">Process</option>
            <option value="2">Accepted</option>
          </select>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary save-combiner-btn">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="edit-combinerreq-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Combiner Link</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="edit-combreq-id">
        <div class="form-group"><label>Combiner ID</label><input type="number" class="form-control" id="edit-combreq-comb-id"></div>
        <div class="form-group"><label>Requisition ID</label><input type="number" class="form-control" id="edit-combreq-req-id"></div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary save-combinerreq-btn">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="edit-itemlog-modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Item Log</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="edit-log-id">
        <div class="row">
          <div class="col-md-6"><div class="form-group"><label>Item ID</label><input type="number" class="form-control" id="edit-log-item-id"></div></div>
          <div class="col-md-6"><div class="form-group"><label>Title</label><input type="text" class="form-control" id="edit-log-title"></div></div>
          <div class="col-md-12"><div class="form-group"><label>Message</label><textarea class="form-control" id="edit-log-message" rows="4"></textarea></div></div>
          <div class="col-md-4"><div class="form-group"><label>Status</label><input type="number" class="form-control" id="edit-log-status"></div></div>
          <div class="col-md-4"><div class="form-group"><label>Created By</label><input type="number" class="form-control" id="edit-log-created-by"></div></div>
          <div class="col-md-4"><div class="form-group"><label>Created Date</label><input type="datetime-local" class="form-control" id="edit-log-created-date"></div></div>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary save-itemlog-btn">Save</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="edit-recsup-modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit Received Item</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="edit-rec-id">
        <div class="form-group"><label>Date</label><input type="datetime-local" class="form-control" id="edit-rec-date"></div>
        <div class="form-group"><label>Req Item ID</label><input type="number" class="form-control" id="edit-rec-rqitemid"></div>
        <div class="form-group"><label>Quantity</label><input type="number" step="0.01" class="form-control" id="edit-rec-rqitemqty"></div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary save-recsup-btn">Save</button>
      </div>
    </div>
  </div>
</div>
