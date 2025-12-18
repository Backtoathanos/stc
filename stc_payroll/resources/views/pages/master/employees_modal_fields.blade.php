{{-- This file contains reusable form fields for employee modals --}}
{{-- Basic Information Section --}}
<div class="row">
  <div class="col-12 mb-3"><h6 class="text-primary border-bottom pb-1">Basic Information</h6></div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Employee ID <span class="text-danger">*</span></label>
      <input type="text" class="form-control" id="{{ $prefix }}EmpId" name="EmpId" required>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Name <span class="text-danger">*</span></label>
      <input type="text" class="form-control" id="{{ $prefix }}Name" name="Name" required>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Father Name</label>
      <input type="text" class="form-control" id="{{ $prefix }}Father" name="Father">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Gender</label>
      <select class="form-control" id="{{ $prefix }}Gender" name="Gender">
        <option value="">Select Gender</option>
        <option value="MALE">Male</option>
        <option value="FEMALE">Female</option>
        <option value="OTHER">Other</option>
      </select>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Marital Status</label>
      <select class="form-control" id="{{ $prefix }}MaritalStatus" name="MaritalStatus">
        <option value="">Select Status</option>
        <option value="SINGLE">Single</option>
        <option value="MARRIED">Married</option>
        <option value="DIVORCED">Divorced</option>
      </select>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Date of Birth</label>
      <input type="date" class="form-control" id="{{ $prefix }}Dob" name="Dob">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Date of Joining</label>
      <input type="date" class="form-control" id="{{ $prefix }}Doj" name="Doj">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Date of Exit</label>
      <input type="date" class="form-control" id="{{ $prefix }}Doe" name="Doe">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Safety Card Expiry</label>
      <input type="date" class="form-control" id="{{ $prefix }}SafetyCardExpiry" name="SafetyCardExpiry">
    </div>
  </div>
  <div class="col-md-12">
    <div class="form-group">
      <label>Address</label>
      <textarea class="form-control" id="{{ $prefix }}Address" name="Address" rows="2"></textarea>
    </div>
  </div>
</div>

{{-- Organizational Information Section --}}
<div class="row mt-3">
  <div class="col-12 mb-3"><h6 class="text-primary border-bottom pb-1">Organizational Information</h6></div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Site <span class="text-danger">*</span></label>
      <select class="form-control" id="{{ $prefix }}SiteId" name="site_id" required>
        <option value="">Select Site</option>
        @foreach($sites as $site)
          <option value="{{ $site->id }}">{{ $site->name }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Department <span class="text-danger">*</span></label>
      <select class="form-control" id="{{ $prefix }}DepartmentId" name="department_id" required>
        <option value="">Select Department</option>
        @foreach($departments as $dept)
          <option value="{{ $dept->id }}">{{ $dept->name }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Designation <span class="text-danger">*</span></label>
      <select class="form-control" id="{{ $prefix }}DesignationId" name="designation_id" required>
        <option value="">Select Designation</option>
        @foreach($designations as $desig)
          <option value="{{ $desig->id }}">{{ $desig->name }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Gang <span class="text-danger">*</span></label>
      <select class="form-control" id="{{ $prefix }}GangId" name="gang_id" required>
        <option value="">Select Gang</option>
        @foreach($gangs as $gang)
          <option value="{{ $gang->id }}">{{ $gang->name }}</option>
        @endforeach
      </select>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Skill</label>
      <select class="form-control" id="{{ $prefix }}Skill" name="Skill">
        <option value="">Select Skill</option>
        <option value="SKILLED">Skilled</option>
        <option value="UN-SKILLED">Un-Skilled</option>
        <option value="SEMI-SKILLED">Semi-Skilled</option>
      </select>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Status <span class="text-danger">*</span></label>
      <select class="form-control" id="{{ $prefix }}Status" name="Status" required>
        <option value="ACTIVE">Active</option>
        <option value="INACTIVE">Inactive</option>
      </select>
    </div>
  </div>
</div>

{{-- Contact Information Section --}}
<div class="row mt-3">
  <div class="col-12 mb-3"><h6 class="text-primary border-bottom pb-1">Contact Information</h6></div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Email</label>
      <input type="email" class="form-control" id="{{ $prefix }}Email" name="Email">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Mobile</label>
      <input type="text" class="form-control" id="{{ $prefix }}Mobile" name="Mobile">
    </div>
  </div>
</div>

{{-- Banking Information Section --}}
<div class="row mt-3">
  <div class="col-12 mb-3"><h6 class="text-primary border-bottom pb-1">Banking Information</h6></div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Bank</label>
      <input type="text" class="form-control" id="{{ $prefix }}Bank" name="Bank">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Branch</label>
      <input type="text" class="form-control" id="{{ $prefix }}Branch" name="Branch">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>IFSC</label>
      <input type="text" class="form-control" id="{{ $prefix }}Ifsc" name="Ifsc">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Account Number</label>
      <input type="text" class="form-control" id="{{ $prefix }}Ac" name="Ac">
    </div>
  </div>
</div>

{{-- Identity & Documents Section --}}
<div class="row mt-3">
  <div class="col-12 mb-3"><h6 class="text-primary border-bottom pb-1">Identity & Documents</h6></div>
  <div class="col-md-6">
    <div class="form-group">
      <label>PAN</label>
      <input type="text" class="form-control" id="{{ $prefix }}Pan" name="Pan">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Aadhar</label>
      <input type="text" class="form-control" id="{{ $prefix }}Aadhar" name="Aadhar">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>UAN</label>
      <input type="text" class="form-control" id="{{ $prefix }}Uan" name="Uan">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>ESIC</label>
      <input type="text" class="form-control" id="{{ $prefix }}Esic" name="Esic">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Employee Safety Card</label>
      <input type="text" class="form-control" id="{{ $prefix }}EmpSafetyCard" name="EmpSafetyCard">
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Image URL</label>
      <input type="text" class="form-control" id="{{ $prefix }}Imageurl" name="Imageurl">
    </div>
  </div>
</div>

{{-- Employment Settings Section --}}
<div class="row mt-3">
  <div class="col-12 mb-3"><h6 class="text-primary border-bottom pb-1">Employment Settings</h6></div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Payment Mode</label>
      <select class="form-control" id="{{ $prefix }}Paymentmode" name="Paymentmode">
        <option value="">Select Payment Mode</option>
        <option value="SALARY">Salary</option>
        <option value="WAGES">Wages</option>
        <option value="CASH">Cash</option>
      </select>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>Weekoff</label>
      <select class="form-control" id="{{ $prefix }}Weekoff" name="Weekoff">
        <option value="">Select Weekoff</option>
        <option value="SUNDAY">Sunday</option>
        <option value="MONDAY">Monday</option>
        <option value="TUESDAY">Tuesday</option>
        <option value="WEDNESDAY">Wednesday</option>
        <option value="THURSDAY">Thursday</option>
        <option value="FRIDAY">Friday</option>
        <option value="SATURDAY">Saturday</option>
      </select>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>OT Type</label>
      <select class="form-control" id="{{ $prefix }}Ottype" name="Ottype">
        <option value="">Select OT Type</option>
        <option value="SINGLE">Single</option>
        <option value="DOUBLE">Double</option>
      </select>
    </div>
  </div>
  <div class="col-md-6">
    <div class="form-group">
      <label>OT Slave</label>
      <input type="text" class="form-control" id="{{ $prefix }}Otslave" name="Otslave">
    </div>
  </div>
</div>

{{-- Checkboxes Section --}}
<div class="row mt-3">
  <div class="col-12 mb-3"><h6 class="text-primary border-bottom pb-1">Additional Settings</h6></div>
  <div class="col-md-4">
    <div class="form-group">
      <div class="form-check">
        <input type="checkbox" class="form-check-input" id="{{ $prefix }}PfApplicable" name="PfApplicable" value="1">
        <label class="form-check-label" for="{{ $prefix }}PfApplicable">PF Applicable</label>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <div class="form-check">
        <input type="checkbox" class="form-check-input" id="{{ $prefix }}EsicApplicable" name="EsicApplicable" value="1">
        <label class="form-check-label" for="{{ $prefix }}EsicApplicable">ESIC Applicable</label>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <div class="form-check">
        <input type="checkbox" class="form-check-input" id="{{ $prefix }}PRFTax" name="PRFTax" value="1">
        <label class="form-check-label" for="{{ $prefix }}PRFTax">PRF Tax</label>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <div class="form-check">
        <input type="checkbox" class="form-check-input" id="{{ $prefix }}AttendAllow" name="AttendAllow" value="1">
        <label class="form-check-label" for="{{ $prefix }}AttendAllow">Attendance Allowance</label>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <div class="form-check">
        <input type="checkbox" class="form-check-input" id="{{ $prefix }}OtAppl" name="OtAppl" value="1">
        <label class="form-check-label" for="{{ $prefix }}OtAppl">OT Applicable</label>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <div class="form-check">
        <input type="checkbox" class="form-check-input" id="{{ $prefix }}MrOtAppl" name="MrOtAppl" value="1">
        <label class="form-check-label" for="{{ $prefix }}MrOtAppl">MR OT Applicable</label>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <div class="form-check">
        <input type="checkbox" class="form-check-input" id="{{ $prefix }}AllowAsPer" name="AllowAsPer" value="1" checked>
        <label class="form-check-label" for="{{ $prefix }}AllowAsPer">Allow As Per</label>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <div class="form-check">
        <input type="checkbox" class="form-check-input" id="{{ $prefix }}ReversePF" name="ReversePF" value="1">
        <label class="form-check-label" for="{{ $prefix }}ReversePF">Reverse PF</label>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <div class="form-check">
        <input type="checkbox" class="form-check-input" id="{{ $prefix }}is_employee" name="is_employee" value="1">
        <label class="form-check-label" for="{{ $prefix }}is_employee">Is Employee</label>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <div class="form-check">
        <input type="checkbox" class="form-check-input" id="{{ $prefix }}is_supervisor" name="is_supervisor" value="1">
        <label class="form-check-label" for="{{ $prefix }}is_supervisor">Is Supervisor</label>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="form-group">
      <div class="form-check">
        <input type="checkbox" class="form-check-input" id="{{ $prefix }}is_officeStaff" name="is_officeStaff" value="1">
        <label class="form-check-label" for="{{ $prefix }}is_officeStaff">Is Office Staff</label>
      </div>
    </div>
  </div>
</div>

