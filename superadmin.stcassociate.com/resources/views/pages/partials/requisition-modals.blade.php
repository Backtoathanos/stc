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
