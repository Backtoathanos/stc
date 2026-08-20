<div class="row">
  <div class="col-md-12">
    <div class="form-group">
      <label for="{{ $prefix }}-name">Merchant name <span class="text-danger">*</span></label>
      <input type="text" class="form-control" id="{{ $prefix }}-name" placeholder="Enter merchant name">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label for="{{ $prefix }}-category">Category</label>
      <select class="form-control" id="{{ $prefix }}-category">
        <option value="">Select category</option>
        @foreach($categories as $cat)
          <option value="{{ $cat }}">{{ $cat }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label for="{{ $prefix }}-known_for">Specially known for</label>
      <input type="text" class="form-control" id="{{ $prefix }}-known_for" placeholder="e.g. electrical, hardware">
    </div>
  </div>
  <div class="col-md-12">
    <div class="form-group">
      <label for="{{ $prefix }}-address">Address <span class="text-danger">*</span></label>
      <textarea class="form-control" id="{{ $prefix }}-address" rows="2" placeholder="Enter address"></textarea>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label for="{{ $prefix }}-city_id">City <span class="text-danger">*</span></label>
      <select class="form-control merchant-select2" id="{{ $prefix }}-city_id" style="width:100%;">
        <option value="">Select city</option>
        @foreach($cities as $city)
          <option value="{{ $city->stc_city_id }}" @if($prefix === 'add' && (int)$default_city_id === (int)$city->stc_city_id) selected @endif>{{ $city->stc_city_name }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label for="{{ $prefix }}-state_id">State <span class="text-danger">*</span></label>
      <select class="form-control merchant-select2" id="{{ $prefix }}-state_id" style="width:100%;">
        <option value="">Select state</option>
        @foreach($states as $state)
          <option value="{{ $state->stc_state_id }}" @if($prefix === 'add' && (int)$default_state_id === (int)$state->stc_state_id) selected @endif>{{ $state->stc_state_name }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label for="{{ $prefix }}-contact_person">Contact person</label>
      <input type="text" class="form-control" id="{{ $prefix }}-contact_person" placeholder="Contact person">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label for="{{ $prefix }}-phone">Phone</label>
      <input type="text" class="form-control" id="{{ $prefix }}-phone" placeholder="Contact number">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label for="{{ $prefix }}-email">Email</label>
      <input type="email" class="form-control" id="{{ $prefix }}-email" placeholder="Email">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label for="{{ $prefix }}-image">Image URL / path</label>
      <input type="text" class="form-control" id="{{ $prefix }}-image" placeholder="https://… or relative path">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label for="{{ $prefix }}-gstin">GSTIN</label>
      <input type="text" class="form-control text-uppercase" id="{{ $prefix }}-gstin" placeholder="GSTIN" maxlength="30">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label for="{{ $prefix }}-pan">PAN</label>
      <input type="text" class="form-control text-uppercase" id="{{ $prefix }}-pan" placeholder="PAN" maxlength="20">
    </div>
  </div>
</div>
