{{-- Electronics --}}
<div class="modal fade" id="modal-electronics" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="form-electronics" method="post" action="">
        @csrf
        <div class="modal-header"><h5 class="modal-title" id="modal-electronics-title">Electronics user</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group"><label>Name</label><input type="text" class="form-control" name="name" id="el-name" required></div>
          <div class="form-group"><label>Email</label><input type="email" class="form-control" name="email" id="el-email" required></div>
          <div class="form-group"><label>Contact</label><input type="text" class="form-control" name="contact" id="el-contact" required></div>
          <div class="form-group"><label>Address</label><textarea class="form-control" name="address" id="el-address" required></textarea></div>
          <div class="form-group"><label>City</label><select class="form-control" name="city" id="el-city">@foreach($getRecordCity as $city)<option value="{{ $city->stc_city_id }}">{{ $city->stc_city_name }}</option>@endforeach</select></div>
          <div class="form-group"><label>State</label><select class="form-control" name="state" id="el-state">@foreach($getRecordState as $state)<option value="{{ $state->stc_state_id }}">{{ $state->stc_state_name }}</option>@endforeach</select></div>
          <div class="form-group"><label>Pincode</label><input type="number" class="form-control" name="pincode" id="el-pincode" required></div>
          <div class="form-group"><label>Password</label><input type="password" class="form-control" name="password" id="el-password" placeholder="Leave blank to keep (edit)"></div>
          <div class="form-group"><label>About user</label><input type="text" class="form-control" name="abtuser" id="el-abtuser"></div>
          <div class="form-group"><label>Role</label><select class="form-control" name="role" id="el-role"><option value="3">Admin</option><option value="2">Receptionist</option><option value="1">User</option></select></div>
          <div class="form-group"><label>Status</label><select class="form-control" name="status" id="el-status"><option value="1">Active</option><option value="0">In-active</option></select></div>
          <div class="alert alert-danger d-none" id="form-electronics-errors"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Trading --}}
<div class="modal fade" id="modal-trading" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="form-trading" method="post" action="">
        @csrf
        <div class="modal-header"><h5 class="modal-title" id="modal-trading-title">Trading user</h5>
          <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body">
          <div class="form-group"><label>Name</label><input type="text" class="form-control" name="name" id="tr-name" required></div>
          <div class="form-group"><label>Email</label><input type="email" class="form-control" name="email" id="tr-email" required></div>
          <div class="form-group"><label>Contact</label><input type="text" class="form-control" name="contact" id="tr-contact" required></div>
          <div class="form-group"><label>Address</label><textarea class="form-control" name="address" id="tr-address" required></textarea></div>
          <div class="form-group"><label>City</label><select class="form-control" name="city" id="tr-city">@foreach($getRecordCity as $city)<option value="{{ $city->stc_city_id }}">{{ $city->stc_city_name }}</option>@endforeach</select></div>
          <div class="form-group"><label>State</label><select class="form-control" name="state" id="tr-state">@foreach($getRecordState as $state)<option value="{{ $state->stc_state_id }}">{{ $state->stc_state_name }}</option>@endforeach</select></div>
          <div class="form-group suggestion-wrapper" id="trading-branch-wrap">
            <label>Branch / location</label>
            <input type="text" class="form-control suggestion-input" name="branch" id="tr-branch" list="trading-branch-datalist" autocomplete="off" placeholder="Type to search or enter branch">
            <datalist id="trading-branch-datalist">@foreach($tradingBranchLocations as $bloc)<option value="{{ $bloc }}"></option>@endforeach</datalist>
            <ul class="suggestion-list d-none"></ul>
          </div>
          <div class="form-group"><label>Pincode</label><input type="number" class="form-control" name="pincode" id="tr-pincode" required></div>
          <div class="form-group"><label>Password</label><input type="password" class="form-control" name="password" id="tr-password"></div>
          <div class="form-group"><label>Status</label><select class="form-control" name="status" id="tr-status"><option value="1">Active</option><option value="0">In-active</option></select></div>
          <div class="alert alert-danger d-none" id="form-trading-errors"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- Groceries --}}
<div class="modal fade" id="modal-groceries" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="form-groceries" method="post" action="">
        @csrf
        <div class="modal-header"><h5 class="modal-title">Groceries user</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
        <div class="modal-body">
          <div class="form-group"><label>Name</label><input type="text" class="form-control" name="name" id="gr-name" required></div>
          <div class="form-group"><label>Email</label><input type="email" class="form-control" name="email" id="gr-email" required></div>
          <div class="form-group"><label>Contact</label><input type="text" class="form-control" name="contact" id="gr-contact" required></div>
          <div class="form-group"><label>Address</label><textarea class="form-control" name="address" id="gr-address" required></textarea></div>
          <div class="form-group"><label>City</label><select class="form-control" name="city" id="gr-city">@foreach($getRecordCity as $city)<option value="{{ $city->stc_city_id }}">{{ $city->stc_city_name }}</option>@endforeach</select></div>
          <div class="form-group"><label>State</label><select class="form-control" name="state" id="gr-state">@foreach($getRecordState as $state)<option value="{{ $state->stc_state_id }}">{{ $state->stc_state_name }}</option>@endforeach</select></div>
          <div class="form-group"><label>Pincode</label><input type="number" class="form-control" name="pincode" id="gr-pincode" required></div>
          <div class="form-group"><label>Password</label><input type="password" class="form-control" name="password" id="gr-password"></div>
          <div class="form-group"><label>Type</label><select class="form-control" name="usertype" id="gr-usertype"><option value="1">User</option><option value="0">To Ask</option></select></div>
          <div class="form-group"><label>Status</label><select class="form-control" name="status" id="gr-status"><option value="1">Active</option><option value="0">In-active</option></select></div>
          <div class="alert alert-danger d-none" id="form-groceries-errors"></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Save</button></div>
      </form>
    </div>
  </div>
</div>

{{-- Manager --}}
<div class="modal fade" id="modal-manager" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="form-manager" method="post" action="">
        @csrf
        <div class="modal-header"><h5 class="modal-title">Manager user</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
        <div class="modal-body">
          <div class="form-group"><label>Name</label><input type="text" class="form-control" name="name" id="mg-name" required></div>
          <div class="form-group"><label>Email</label><input type="email" class="form-control" name="email" id="mg-email" required></div>
          <div class="form-group"><label>Contact</label><input type="text" class="form-control" name="contact" id="mg-contact" required></div>
          <div class="form-group"><label>Address</label><textarea class="form-control" name="address" id="mg-address" required></textarea></div>
          <div class="form-group"><label>City</label><select class="form-control" name="city" id="mg-city">@foreach($getRecordCity as $city)<option value="{{ $city->stc_city_id }}">{{ $city->stc_city_name }}</option>@endforeach</select></div>
          <div class="form-group"><label>State</label><select class="form-control" name="state" id="mg-state">@foreach($getRecordState as $state)<option value="{{ $state->stc_state_id }}">{{ $state->stc_state_name }}</option>@endforeach</select></div>
          <div class="form-group"><label>Pincode</label><input type="text" class="form-control" name="pincode" id="mg-pincode" required></div>
          <div class="form-group"><label>User Id</label><input type="text" class="form-control" name="userid" id="mg-userid" required></div>
          <div class="form-group"><label>Password</label><input type="password" class="form-control" name="password" id="mg-password"></div>
          <div class="form-group"><label>Role</label><select class="form-control" name="role" id="mg-role"><option value="1">Manager</option><option value="2">Procurement</option><option value="3">SDL</option></select></div>
          <div class="form-group"><label>Status</label><select class="form-control" name="status" id="mg-status"><option value="1">Active</option><option value="0">In-active</option></select></div>
          <div class="alert alert-danger d-none" id="form-manager-errors"></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Save</button></div>
      </form>
    </div>
  </div>
</div>

{{-- School --}}
<div class="modal fade" id="modal-school" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="form-school" method="post" action="">
        @csrf
        <div class="modal-header"><h5 class="modal-title">School user</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
        <div class="modal-body">
          <div class="form-group"><label>Name</label><input type="text" class="form-control" name="name" id="sc-name" required></div>
          <div class="form-group"><label>Email</label><input type="email" class="form-control" name="email" id="sc-email" required></div>
          <div class="form-group"><label>Contact</label><input type="text" class="form-control" name="contact" id="sc-contact" required></div>
          <div class="form-group"><label>Address</label><textarea class="form-control" name="address" id="sc-address" required></textarea></div>
          <div class="form-group"><label>City</label><select class="form-control" name="city" id="sc-city">@foreach($getRecordCity as $city)<option value="{{ $city->stc_city_id }}">{{ $city->stc_city_name }}</option>@endforeach</select></div>
          <div class="form-group"><label>State</label><select class="form-control" name="state" id="sc-state">@foreach($getRecordState as $state)<option value="{{ $state->stc_state_id }}">{{ $state->stc_state_name }}</option>@endforeach</select></div>
          <div class="form-group"><label>Pincode</label><input type="number" class="form-control" name="pincode" id="sc-pincode" required></div>
          <div class="form-group"><label>Password</label><input type="password" class="form-control" name="password" id="sc-password"></div>
          <div class="form-group"><label>About user</label><input type="text" class="form-control" name="abtuser" id="sc-abtuser"></div>
          <div class="form-group"><label>Role</label><select class="form-control" name="role" id="sc-role"><option value="4">Teacher</option><option value="3">TIC</option><option value="2">Receptionist</option><option value="1">User</option></select></div>
          <div class="form-group"><label>Status</label><select class="form-control" name="status" id="sc-status"><option value="1">Active</option><option value="0">In-active</option></select></div>
          <div class="alert alert-danger d-none" id="form-school-errors"></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Save</button></div>
      </form>
    </div>
  </div>
</div>

{{-- Site --}}
<div class="modal fade" id="modal-site" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="form-site" method="post" action="">
        @csrf
        <div class="modal-header"><h5 class="modal-title">Site user</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
        <div class="modal-body">
          <div class="form-group"><label>Customer</label><select class="form-control" name="customer" id="si-customer"><option value="2">GLOBAL AC SYSTEM JSR PVT LTD</option><option value="3">SARA Enterprises</option></select></div>
          <div class="form-group"><label>Name</label><input type="text" class="form-control" name="name" id="si-name" required></div>
          <div class="form-group"><label>Email</label><input type="email" class="form-control" name="email" id="si-email" required></div>
          <div class="form-group"><label>Contact</label><input type="text" class="form-control" name="contact" id="si-contact" required></div>
          <div class="form-group"><label>Whatsapp</label><input type="text" class="form-control" name="whatsapp" id="si-whatsapp" required></div>
          <div class="form-group"><label>Address</label><textarea class="form-control" name="address" id="si-address" required></textarea></div>
          <div class="form-group"><label>City</label><select class="form-control" name="city" id="si-city">@foreach($getRecordCity as $city)<option value="{{ $city->stc_city_id }}">{{ $city->stc_city_name }}</option>@endforeach</select></div>
          <div class="form-group"><label>State</label><select class="form-control" name="state" id="si-state">@foreach($getRecordState as $state)<option value="{{ $state->stc_state_id }}">{{ $state->stc_state_name }}</option>@endforeach</select></div>
          <div class="form-group"><label>Pincode</label><input type="text" class="form-control" name="pincode" id="si-pincode" required></div>
          <div class="form-group"><label>Password</label><input type="password" class="form-control" name="password" id="si-password"></div>
          <div class="form-group"><label>Category</label><select class="form-control" name="category" id="si-category">
            <option>Coordinator</option><option>Electrician</option><option>Operator</option><option>Helper</option>
            <option>Safety Supervisor</option><option>Site Incharge</option><option>Supervisor</option><option>Technician</option>
          </select></div>
          <div class="form-group"><label>Status</label><select class="form-control" name="status" id="si-status"><option value="1">Active</option><option value="0">In-active</option></select></div>
          <div class="form-group"><label>Manager</label><select class="form-control" name="manager" id="si-manager">@foreach($getRecordManager as $m)<option value="{{ $m->stc_agents_id }}">{{ $m->stc_agents_name }}</option>@endforeach</select></div>
          <div class="alert alert-danger d-none" id="form-site-errors"></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Save</button></div>
      </form>
    </div>
  </div>
</div>

{{-- User admin (uadmin) --}}
<div class="modal fade" id="modal-uadmin" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form id="form-uadmin" method="post" action="">
        @csrf
        <div class="modal-header"><h5 class="modal-title">User administration</h5><button type="button" class="close" data-dismiss="modal"><span>&times;</span></button></div>
        <div class="modal-body">
          <div class="form-group"><label>Name</label><input type="text" class="form-control" name="name" id="ua-name" required></div>
          <div class="form-group"><label>Email</label><input type="email" class="form-control" name="email" id="ua-email" required></div>
          <div class="form-group"><label>Contact</label><input type="text" class="form-control" name="contact" id="ua-contact" required></div>
          <div class="form-group"><label>Contact 2</label><input type="text" class="form-control" name="contact2" id="ua-contact2" required></div>
          <div class="form-group"><label>Address</label><textarea class="form-control" name="address" id="ua-address" required></textarea></div>
          <div class="form-group"><label>City</label><select class="form-control" name="city" id="ua-city">@foreach($getRecordCity as $city)<option value="{{ $city->stc_city_id }}">{{ $city->stc_city_name }}</option>@endforeach</select></div>
          <div class="form-group"><label>State</label><select class="form-control" name="state" id="ua-state">@foreach($getRecordState as $state)<option value="{{ $state->stc_state_id }}">{{ $state->stc_state_name }}</option>@endforeach</select></div>
          <div class="form-group"><label>User Id</label><input type="text" class="form-control" name="userid" id="ua-userid" required></div>
          <div class="form-group"><label>Password</label><input type="password" class="form-control" name="password" id="ua-password"></div>
          <div class="form-group"><label>Role</label><select class="form-control" name="role" id="ua-role">
            <option value="1">User</option><option value="2">Report Access for school</option><option value="4">Accountant</option><option value="6">Boss</option><option value="9">Nausher</option>
          </select></div>
          <div class="form-group"><label>Status</label><select class="form-control" name="status" id="ua-status"><option value="1">Active</option><option value="0">In-active</option></select></div>
          <div class="alert alert-danger d-none" id="form-uadmin-errors"></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Save</button></div>
      </form>
    </div>
  </div>
</div>
