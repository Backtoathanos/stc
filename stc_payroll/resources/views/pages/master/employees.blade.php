@extends('layouts.header')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Employees Management</h3>
    <div class="card-tools">
      <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addEmployeeModal">
        <i class="fas fa-plus"></i> Add Employee
      </button>
    </div>
  </div>
  <div class="card-body">
    <!-- Filters -->
    <div class="row mb-3">
      <div class="col-md-3">
        <select id="filterSite" class="form-control form-control-sm">
          <option value="">All Sites</option>
          @foreach($sites as $site)
            <option value="{{ $site->id }}">{{ $site->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <select id="filterDepartment" class="form-control form-control-sm">
          <option value="">All Departments</option>
          @foreach($departments as $dept)
            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <select id="filterStatus" class="form-control form-control-sm">
          <option value="">All Status</option>
          <option value="ACTIVE">Active</option>
          <option value="INACTIVE">Inactive</option>
        </select>
      </div>
      <div class="col-md-3">
        <button type="button" class="btn btn-secondary btn-sm btn-block" id="resetFilters">
          <i class="fas fa-redo"></i> Reset Filters
        </button>
      </div>
    </div>

    <!-- DataTable -->
    <div class="table-responsive">
    <table id="employeesTable" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>Emp ID</th>
          <th>Name</th>
          <th>Father</th>
          <th>Site</th>
          <th>Department</th>
          <th>Designation</th>
          <th>Gang</th>
          <th>Email</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="viewEmployeeModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">View Employee Details</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body" id="viewEmployeeContent">
        <!-- Content will be loaded via AJAX -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Employee</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="editEmployeeForm">
        <div class="modal-body">
          <input type="hidden" id="editEmployeeId" name="id">
          
          {{-- Basic Information Section --}}
          <div class="row mb-3">
            <div class="col-md-6">
              <div class="form-group">
                <label>Employee ID <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="editEmpId" name="EmpId" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="editName" name="Name" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Father Name</label>
                <input type="text" class="form-control" id="editFather" name="Father">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Gender</label>
                <select class="form-control" id="editGender" name="Gender">
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
                <select class="form-control" id="editMaritalStatus" name="MaritalStatus">
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
                <input type="date" class="form-control" id="editDob" name="Dob">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Date of Joining</label>
                <input type="date" class="form-control" id="editDoj" name="Doj">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Date of Exit</label>
                <input type="date" class="form-control" id="editDoe" name="Doe">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Safety Card Expiry</label>
                <input type="date" class="form-control" id="editSafetyCardExpiry" name="SafetyCardExpiry">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label>Address</label>
                <textarea class="form-control" id="editAddress" name="Address" rows="2"></textarea>
              </div>
            </div>
          </div>

          {{-- Tabs Navigation --}}
          <ul class="nav nav-tabs" id="editEmployeeTabs" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="edit-org-tab" data-toggle="tab" href="#edit-org" role="tab" aria-controls="edit-org" aria-selected="true">
                <i class="fas fa-building"></i> Organizational
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="edit-contact-tab" data-toggle="tab" href="#edit-contact" role="tab" aria-controls="edit-contact" aria-selected="false">
                <i class="fas fa-phone"></i> Contact
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="edit-banking-tab" data-toggle="tab" href="#edit-banking" role="tab" aria-controls="edit-banking" aria-selected="false">
                <i class="fas fa-university"></i> Banking
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="edit-identity-tab" data-toggle="tab" href="#edit-identity" role="tab" aria-controls="edit-identity" aria-selected="false">
                <i class="fas fa-id-card"></i> Identity & Documents
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="edit-employment-tab" data-toggle="tab" href="#edit-employment" role="tab" aria-controls="edit-employment" aria-selected="false">
                <i class="fas fa-briefcase"></i> Employment Settings
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="edit-settings-tab" data-toggle="tab" href="#edit-settings" role="tab" aria-controls="edit-settings" aria-selected="false">
                <i class="fas fa-cog"></i> Additional Settings
              </a>
            </li>
          </ul>

          {{-- Tabs Content --}}
          <div class="tab-content" id="editEmployeeTabsContent">
            {{-- Organizational Information Tab --}}
            <div class="tab-pane fade show active" id="edit-org" role="tabpanel" aria-labelledby="edit-org-tab">
              <div class="row mt-3">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Site <span class="text-danger">*</span></label>
                    <select class="form-control" id="editSiteId" name="site_id" required>
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
                    <select class="form-control" id="editDepartmentId" name="department_id" required>
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
                    <select class="form-control" id="editDesignationId" name="designation_id" required>
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
                    <select class="form-control" id="editGangId" name="gang_id" required>
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
                    <select class="form-control" id="editSkill" name="Skill">
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
                    <select class="form-control" id="editStatus" name="Status" required>
                      <option value="ACTIVE">Active</option>
                      <option value="INACTIVE">Inactive</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Image URL</label>
                    <input type="text" class="form-control" id="editImageurl" name="Imageurl">
                  </div>
                </div>
              </div>
            </div>

            {{-- Contact Information Tab --}}
            <div class="tab-pane fade" id="edit-contact" role="tabpanel" aria-labelledby="edit-contact-tab">
              <div class="row mt-3">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" id="editEmail" name="Email">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Mobile</label>
                    <input type="text" class="form-control" id="editMobile" name="Mobile">
                  </div>
                </div>
              </div>
            </div>

            {{-- Banking Information Tab --}}
            <div class="tab-pane fade" id="edit-banking" role="tabpanel" aria-labelledby="edit-banking-tab">
              <div class="row mt-3">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Bank</label>
                    <input type="text" class="form-control" id="editBank" name="Bank">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Branch</label>
                    <input type="text" class="form-control" id="editBranch" name="Branch">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>IFSC</label>
                    <input type="text" class="form-control" id="editIfsc" name="Ifsc">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Account Number</label>
                    <input type="text" class="form-control" id="editAc" name="Ac">
                  </div>
                </div>
              </div>
            </div>

            {{-- Identity & Documents Tab --}}
            <div class="tab-pane fade" id="edit-identity" role="tabpanel" aria-labelledby="edit-identity-tab">
              <div class="row mt-3">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>PAN</label>
                    <input type="text" class="form-control" id="editPan" name="Pan">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Aadhar</label>
                    <input type="text" class="form-control" id="editAadhar" name="Aadhar">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>UAN</label>
                    <input type="text" class="form-control" id="editUan" name="Uan">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>ESIC</label>
                    <input type="text" class="form-control" id="editEsic" name="Esic">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Employee Safety Card</label>
                    <input type="text" class="form-control" id="editEmpSafetyCard" name="EmpSafetyCard">
                  </div>
                </div>
              </div>
            </div>

            {{-- Employment Settings Tab --}}
            <div class="tab-pane fade" id="edit-employment" role="tabpanel" aria-labelledby="edit-employment-tab">
              <div class="row mt-3">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Payment Mode</label>
                    <select class="form-control" id="editPaymentmode" name="Paymentmode">
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
                    <select class="form-control" id="editWeekoff" name="Weekoff">
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
                    <select class="form-control" id="editOttype" name="Ottype">
                      <option value="">Select OT Type</option>
                      <option value="SINGLE">Single</option>
                      <option value="DOUBLE">Double</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>OT Slave</label>
                    <input type="text" class="form-control" id="editOtslave" name="Otslave">
                  </div>
                </div>
              </div>
            </div>

            {{-- Additional Settings Tab --}}
            <div class="tab-pane fade" id="edit-settings" role="tabpanel" aria-labelledby="edit-settings-tab">
              <div class="row mt-3">
                <div class="col-md-4">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="editPfApplicable" name="PfApplicable" value="1">
                    <label class="form-check-label" for="editPfApplicable">PF Applicable</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="editEsicApplicable" name="EsicApplicable" value="1">
                    <label class="form-check-label" for="editEsicApplicable">ESIC Applicable</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="editPRFTax" name="PRFTax" value="1">
                    <label class="form-check-label" for="editPRFTax">PRF Tax</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="editAttendAllow" name="AttendAllow" value="1">
                    <label class="form-check-label" for="editAttendAllow">Attendance Allowance</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="editOtAppl" name="OtAppl" value="1">
                    <label class="form-check-label" for="editOtAppl">OT Applicable</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="editMrOtAppl" name="MrOtAppl" value="1">
                    <label class="form-check-label" for="editMrOtAppl">MR OT Applicable</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="editAllowAsPer" name="AllowAsPer" value="1" checked>
                    <label class="form-check-label" for="editAllowAsPer">Allow As Per</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="editReversePF" name="ReversePF" value="1">
                    <label class="form-check-label" for="editReversePF">Reverse PF</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="editIsEmployee" name="is_employee" value="1">
                    <label class="form-check-label" for="editIsEmployee">Is Employee</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="editIsSupervisor" name="is_supervisor" value="1">
                    <label class="form-check-label" for="editIsSupervisor">Is Supervisor</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="editIsOfficeStaff" name="is_officeStaff" value="1">
                    <label class="form-check-label" for="editIsOfficeStaff">Is Office Staff</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Add Employee Modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Employee</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="addEmployeeForm">
        <div class="modal-body">
          {{-- Basic Information --}}
          <div class="row">
            <div class="col-12 mb-2"><h6 class="text-primary border-bottom pb-1">Basic Information</h6></div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Employee ID <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="addEmpId" name="EmpId" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="addName" name="Name" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Father Name</label>
                <input type="text" class="form-control" id="addFather" name="Father">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Gender</label>
                <select class="form-control" id="addGender" name="Gender">
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
                <select class="form-control" id="addMaritalStatus" name="MaritalStatus">
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
                <input type="date" class="form-control" id="addDob" name="Dob">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Date of Joining</label>
                <input type="date" class="form-control" id="addDoj" name="Doj">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Date of Exit</label>
                <input type="date" class="form-control" id="addDoe" name="Doe">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Safety Card Expiry</label>
                <input type="date" class="form-control" id="addSafetyCardExpiry" name="SafetyCardExpiry">
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label>Address</label>
                <textarea class="form-control" id="addAddress" name="Address" rows="2"></textarea>
              </div>
            </div>
          </div>

          {{-- Tabs Navigation --}}
          <ul class="nav nav-tabs" id="addEmployeeTabs" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="add-org-tab" data-toggle="tab" href="#add-org" role="tab" aria-controls="add-org" aria-selected="true">
                <i class="fas fa-building"></i> Organizational
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="add-contact-tab" data-toggle="tab" href="#add-contact" role="tab" aria-controls="add-contact" aria-selected="false">
                <i class="fas fa-phone"></i> Contact
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="add-banking-tab" data-toggle="tab" href="#add-banking" role="tab" aria-controls="add-banking" aria-selected="false">
                <i class="fas fa-university"></i> Banking
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="add-identity-tab" data-toggle="tab" href="#add-identity" role="tab" aria-controls="add-identity" aria-selected="false">
                <i class="fas fa-id-card"></i> Identity & Documents
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="add-employment-tab" data-toggle="tab" href="#add-employment" role="tab" aria-controls="add-employment" aria-selected="false">
                <i class="fas fa-briefcase"></i> Employment Settings
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="add-settings-tab" data-toggle="tab" href="#add-settings" role="tab" aria-controls="add-settings" aria-selected="false">
                <i class="fas fa-cog"></i> Additional Settings
              </a>
            </li>
          </ul>

          {{-- Tabs Content --}}
          <div class="tab-content" id="addEmployeeTabsContent">
            {{-- Organizational Information Tab --}}
            <div class="tab-pane fade show active" id="add-org" role="tabpanel" aria-labelledby="add-org-tab">
              <div class="row mt-3">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Site <span class="text-danger">*</span></label>
                    <select class="form-control" id="addSiteId" name="site_id" required>
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
                    <select class="form-control" id="addDepartmentId" name="department_id" required>
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
                    <select class="form-control" id="addDesignationId" name="designation_id" required>
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
                    <select class="form-control" id="addGangId" name="gang_id" required>
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
                    <select class="form-control" id="addSkill" name="Skill">
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
                    <select class="form-control" id="addStatus" name="Status" required>
                      <option value="ACTIVE">Active</option>
                      <option value="INACTIVE">Inactive</option>
                    </select>
                  </div>
                </div>
              </div>
            </div>

            {{-- Contact Information Tab --}}
            <div class="tab-pane fade" id="add-contact" role="tabpanel" aria-labelledby="add-contact-tab">
              <div class="row mt-3">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" id="addEmail" name="Email">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Mobile</label>
                    <input type="text" class="form-control" id="addMobile" name="Mobile">
                  </div>
                </div>
              </div>
            </div>

            {{-- Banking Information Tab --}}
            <div class="tab-pane fade" id="add-banking" role="tabpanel" aria-labelledby="add-banking-tab">
              <div class="row mt-3">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Bank</label>
                    <input type="text" class="form-control" id="addBank" name="Bank">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Branch</label>
                    <input type="text" class="form-control" id="addBranch" name="Branch">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>IFSC</label>
                    <input type="text" class="form-control" id="addIfsc" name="Ifsc">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Account Number</label>
                    <input type="text" class="form-control" id="addAc" name="Ac">
                  </div>
                </div>
              </div>
            </div>

            {{-- Identity & Documents Tab --}}
            <div class="tab-pane fade" id="add-identity" role="tabpanel" aria-labelledby="add-identity-tab">
              <div class="row mt-3">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>PAN</label>
                    <input type="text" class="form-control" id="addPan" name="Pan">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Aadhar</label>
                    <input type="text" class="form-control" id="addAadhar" name="Aadhar">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>UAN</label>
                    <input type="text" class="form-control" id="addUan" name="Uan">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>ESIC</label>
                    <input type="text" class="form-control" id="addEsic" name="Esic">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Employee Safety Card</label>
                    <input type="text" class="form-control" id="addEmpSafetyCard" name="EmpSafetyCard">
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Image URL</label>
                    <input type="text" class="form-control" id="addImageurl" name="Imageurl">
                  </div>
                </div>
              </div>
            </div>

            {{-- Employment Settings Tab --}}
            <div class="tab-pane fade" id="add-employment" role="tabpanel" aria-labelledby="add-employment-tab">
              <div class="row mt-3">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Payment Mode</label>
                    <select class="form-control" id="addPaymentmode" name="Paymentmode">
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
                    <select class="form-control" id="addWeekoff" name="Weekoff">
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
                    <select class="form-control" id="addOttype" name="Ottype">
                      <option value="">Select OT Type</option>
                      <option value="SINGLE">Single</option>
                      <option value="DOUBLE">Double</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>OT Slave</label>
                    <input type="text" class="form-control" id="addOtslave" name="Otslave">
                  </div>
                </div>
              </div>
            </div>

            {{-- Additional Settings Tab --}}
            <div class="tab-pane fade" id="add-settings" role="tabpanel" aria-labelledby="add-settings-tab">
              <div class="row mt-3">
                <div class="col-md-4">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="addPfApplicable" name="PfApplicable" value="1">
                    <label class="form-check-label" for="addPfApplicable">PF Applicable</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="addEsicApplicable" name="EsicApplicable" value="1">
                    <label class="form-check-label" for="addEsicApplicable">ESIC Applicable</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="addPRFTax" name="PRFTax" value="1">
                    <label class="form-check-label" for="addPRFTax">PRF Tax</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="addAttendAllow" name="AttendAllow" value="1">
                    <label class="form-check-label" for="addAttendAllow">Attendance Allowance</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="addOtAppl" name="OtAppl" value="1">
                    <label class="form-check-label" for="addOtAppl">OT Applicable</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="addMrOtAppl" name="MrOtAppl" value="1">
                    <label class="form-check-label" for="addMrOtAppl">MR OT Applicable</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="addAllowAsPer" name="AllowAsPer" value="1" checked>
                    <label class="form-check-label" for="addAllowAsPer">Allow As Per</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="addReversePF" name="ReversePF" value="1">
                    <label class="form-check-label" for="addReversePF">Reverse PF</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="addIsEmployee" name="is_employee" value="1">
                    <label class="form-check-label" for="addIsEmployee">Is Employee</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="addIsSupervisor" name="is_supervisor" value="1">
                    <label class="form-check-label" for="addIsSupervisor">Is Supervisor</label>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="addIsOfficeStaff" name="is_officeStaff" value="1">
                    <label class="form-check-label" for="addIsOfficeStaff">Is Office Staff</label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Add Employee</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    var table = $('#employeesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "/stc/stc_payroll/public/master/employees/list",
            type: 'GET',
            data: function(d) {
                d.site_id = $('#filterSite').val();
                d.department_id = $('#filterDepartment').val();
                d.status = $('#filterStatus').val();
            }
        },
        columns: [
            { data: 'EmpId', name: 'EmpId' },
            { data: 'Name', name: 'Name' },
            { data: 'Father', name: 'Father' },
            { data: 'Site', name: 'Site' },
            { data: 'Department', name: 'Department' },
            { data: 'Designation', name: 'Designation' },
            { data: 'Gang', name: 'Gang' },
            { data: 'Email', name: 'Email' },
            {
                data: 'actions',
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    var id = row.id || data;
                    return '<button class="btn btn-info btn-sm view-btn" data-id="' + id + '" title="View"><i class="fas fa-eye"></i></button> ' +
                           '<button class="btn btn-warning btn-sm edit-btn" data-id="' + id + '" title="Edit"><i class="fas fa-edit"></i></button> ' +
                           '<button class="btn btn-danger btn-sm delete-btn" data-id="' + id + '" title="Delete"><i class="fas fa-trash"></i></button>';
                }
            }
        ],
        order: [[1, 'asc']],
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
    });

    // Filter change events
    $('#filterSite, #filterDepartment, #filterStatus').on('change', function() {
        table.draw();
    });

    // Reset filters
    $('#resetFilters').on('click', function() {
        $('#filterSite, #filterDepartment, #filterStatus').val('');
        table.draw();
    });

    // View Employee
    $(document).on('click', '.view-btn', function() {
        var id = $(this).data('id');
        $.ajax({
            url: "/stc/stc_payroll/public/master/employees/show/" + id,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    var emp = response.data;
                    var formatDate = function(date) {
                        if (!date) return 'N/A';
                        return date.split('T')[0];
                    };
                    var formatBoolean = function(val) {
                        return val ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-secondary">No</span>';
                    };
                    var html = '<div class="row mb-3">' +
                        '<div class="col-12"><h5 class="border-bottom pb-2">Basic Information</h5></div>' +
                        '<div class="col-md-6 mb-3"><strong>ID:</strong><br><span class="text-muted">' + (emp.id || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Employee ID:</strong><br><span class="text-muted">' + (emp.EmpId || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Name:</strong><br><span class="text-muted">' + (emp.Name || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Father Name:</strong><br><span class="text-muted">' + (emp.Father || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Gender:</strong><br><span class="text-muted">' + (emp.Gender || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Marital Status:</strong><br><span class="text-muted">' + (emp.MaritalStatus || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Date of Birth:</strong><br><span class="text-muted">' + formatDate(emp.Dob) + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Date of Joining:</strong><br><span class="text-muted">' + formatDate(emp.Doj) + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Date of Exit:</strong><br><span class="text-muted">' + formatDate(emp.Doe) + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Safety Card Expiry:</strong><br><span class="text-muted">' + formatDate(emp.SafetyCardExpiry) + '</span></div>' +
                        '<div class="col-md-12 mb-3"><strong>Address:</strong><br><span class="text-muted">' + (emp.Address || 'N/A') + '</span></div>' +
                        '</div>' +
                        '<ul class="nav nav-tabs" id="viewEmployeeTabs" role="tablist">' +
                        '<li class="nav-item"><a class="nav-link active" id="view-org-tab" data-toggle="tab" href="#view-org" role="tab"><i class="fas fa-building"></i> Organizational</a></li>' +
                        '<li class="nav-item"><a class="nav-link" id="view-contact-tab" data-toggle="tab" href="#view-contact" role="tab"><i class="fas fa-phone"></i> Contact</a></li>' +
                        '<li class="nav-item"><a class="nav-link" id="view-banking-tab" data-toggle="tab" href="#view-banking" role="tab"><i class="fas fa-university"></i> Banking</a></li>' +
                        '<li class="nav-item"><a class="nav-link" id="view-identity-tab" data-toggle="tab" href="#view-identity" role="tab"><i class="fas fa-id-card"></i> Identity & Documents</a></li>' +
                        '<li class="nav-item"><a class="nav-link" id="view-employment-tab" data-toggle="tab" href="#view-employment" role="tab"><i class="fas fa-briefcase"></i> Employment Settings</a></li>' +
                        '<li class="nav-item"><a class="nav-link" id="view-settings-tab" data-toggle="tab" href="#view-settings" role="tab"><i class="fas fa-cog"></i> Additional Settings</a></li>' +
                        '</ul>' +
                        '<div class="tab-content" id="viewEmployeeTabsContent">' +
                        '<div class="tab-pane fade show active" id="view-org" role="tabpanel">' +
                        '<div class="row mt-3">' +
                        '<div class="col-md-6 mb-3"><strong>Site:</strong><br><span class="text-muted">' + (emp.site ? emp.site.name : 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Department:</strong><br><span class="text-muted">' + (emp.department ? emp.department.name : 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Designation:</strong><br><span class="text-muted">' + (emp.designation ? emp.designation.name : 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Gang:</strong><br><span class="text-muted">' + (emp.gang ? emp.gang.name : 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Skill:</strong><br><span class="text-muted">' + (emp.Skill || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Status:</strong><br><span class="badge badge-' + (emp.Status === 'ACTIVE' ? 'success' : 'danger') + '">' + emp.Status + '</span></div>' +
                        '</div></div>' +
                        '<div class="tab-pane fade" id="view-contact" role="tabpanel">' +
                        '<div class="row mt-3">' +
                        '<div class="col-md-6 mb-3"><strong>Email:</strong><br><span class="text-muted">' + (emp.Email || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Mobile:</strong><br><span class="text-muted">' + (emp.Mobile || 'N/A') + '</span></div>' +
                        '</div></div>' +
                        '<div class="tab-pane fade" id="view-banking" role="tabpanel">' +
                        '<div class="row mt-3">' +
                        '<div class="col-md-6 mb-3"><strong>Bank:</strong><br><span class="text-muted">' + (emp.Bank || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Branch:</strong><br><span class="text-muted">' + (emp.Branch || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>IFSC:</strong><br><span class="text-muted">' + (emp.Ifsc || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Account Number:</strong><br><span class="text-muted">' + (emp.Ac || 'N/A') + '</span></div>' +
                        '</div></div>' +
                        '<div class="tab-pane fade" id="view-identity" role="tabpanel">' +
                        '<div class="row mt-3">' +
                        '<div class="col-md-6 mb-3"><strong>PAN:</strong><br><span class="text-muted">' + (emp.Pan || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Aadhar:</strong><br><span class="text-muted">' + (emp.Aadhar || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>UAN:</strong><br><span class="text-muted">' + (emp.Uan || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>ESIC:</strong><br><span class="text-muted">' + (emp.Esic || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Employee Safety Card:</strong><br><span class="text-muted">' + (emp.EmpSafetyCard || 'N/A') + '</span></div>' +
                        '</div></div>' +
                        '<div class="tab-pane fade" id="view-employment" role="tabpanel">' +
                        '<div class="row mt-3">' +
                        '<div class="col-md-6 mb-3"><strong>Payment Mode:</strong><br><span class="text-muted">' + (emp.Paymentmode || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>Weekoff:</strong><br><span class="text-muted">' + (emp.Weekoff || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>OT Type:</strong><br><span class="text-muted">' + (emp.Ottype || 'N/A') + '</span></div>' +
                        '<div class="col-md-6 mb-3"><strong>OT Slave:</strong><br><span class="text-muted">' + (emp.Otslave || 'N/A') + '</span></div>' +
                        '</div></div>' +
                        '<div class="tab-pane fade" id="view-settings" role="tabpanel">' +
                        '<div class="row mt-3">' +
                        '<div class="col-md-6 mb-3"><strong>PF Applicable:</strong><br>' + formatBoolean(emp.PfApplicable) + '</div>' +
                        '<div class="col-md-6 mb-3"><strong>ESIC Applicable:</strong><br>' + formatBoolean(emp.EsicApplicable) + '</div>' +
                        '<div class="col-md-6 mb-3"><strong>PRF Tax:</strong><br>' + formatBoolean(emp.PRFTax) + '</div>' +
                        '<div class="col-md-6 mb-3"><strong>Attendance Allowance:</strong><br>' + formatBoolean(emp.AttendAllow) + '</div>' +
                        '<div class="col-md-6 mb-3"><strong>OT Applicable:</strong><br>' + formatBoolean(emp.OtAppl) + '</div>' +
                        '<div class="col-md-6 mb-3"><strong>MR OT Applicable:</strong><br>' + formatBoolean(emp.MrOtAppl) + '</div>' +
                        '<div class="col-md-6 mb-3"><strong>Allow As Per:</strong><br>' + formatBoolean(emp.AllowAsPer) + '</div>' +
                        '<div class="col-md-6 mb-3"><strong>Reverse PF:</strong><br>' + formatBoolean(emp.ReversePF) + '</div>' +
                        '<div class="col-md-6 mb-3"><strong>Is Employee:</strong><br>' + formatBoolean(emp.is_employee) + '</div>' +
                        '<div class="col-md-6 mb-3"><strong>Is Supervisor:</strong><br>' + formatBoolean(emp.is_supervisor) + '</div>' +
                        '<div class="col-md-6 mb-3"><strong>Is Office Staff:</strong><br>' + formatBoolean(emp.is_officeStaff) + '</div>' +
                        '</div></div></div>';
                    $('#viewEmployeeContent').html(html);
                    $('#viewEmployeeModal').modal('show');
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load employee details'
                });
            }
        });
    });

    // Edit Employee - Load data
    $(document).on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        
        // Set flag to prevent auto-save during data loading
        $('#editEmployeeForm').data('loading', true);
        
        $.ajax({
            url: "/stc/stc_payroll/public/master/employees/show/" + id,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    var emp = response.data;
                    var formatDate = function(date) {
                        if (!date) return '';
                        return date.split('T')[0];
                    };
                    $('#editEmployeeId').val(emp.id);
                    $('#editEmpId').val(emp.EmpId || '');
                    $('#editName').val(emp.Name || '');
                    $('#editFather').val(emp.Father || '');
                    $('#editGender').val(emp.Gender || '').trigger('change');
                    $('#editMaritalStatus').val(emp.MaritalStatus || '').trigger('change');
                    $('#editDob').val(formatDate(emp.Dob));
                    $('#editDoj').val(formatDate(emp.Doj));
                    $('#editDoe').val(formatDate(emp.Doe));
                    $('#editSafetyCardExpiry').val(formatDate(emp.SafetyCardExpiry));
                    $('#editSiteId').val(emp.site_id || '').trigger('change');
                    $('#editDepartmentId').val(emp.department_id || '').trigger('change');
                    $('#editDesignationId').val(emp.designation_id || '').trigger('change');
                    $('#editGangId').val(emp.gang_id || '').trigger('change');
                    $('#editPaymentmode').val(emp.Paymentmode || '').trigger('change');
                    $('#editWeekoff').val(emp.Weekoff || '').trigger('change');
                    $('#editOttype').val(emp.Ottype || '').trigger('change');
                    $('#editSkill').val(emp.Skill || '').trigger('change');
                    $('#editStatus').val(emp.Status || 'ACTIVE').trigger('change');
                    $('#editEmail').val(emp.Email || '');
                    $('#editMobile').val(emp.Mobile || '');
                    $('#editAddress').val(emp.Address || '');
                    $('#editBank').val(emp.Bank || '');
                    $('#editBranch').val(emp.Branch || '');
                    $('#editIfsc').val(emp.Ifsc || '');
                    $('#editAc').val(emp.Ac || '');
                    $('#editPan').val(emp.Pan || '');
                    $('#editAadhar').val(emp.Aadhar || '');
                    $('#editUan').val(emp.Uan || '');
                    $('#editEsic').val(emp.Esic || '');
                    $('#editEmpSafetyCard').val(emp.EmpSafetyCard || '');
                    $('#editImageurl').val(emp.Imageurl || '');
                    $('#editPaymentmode').val(emp.Paymentmode || '');
                    $('#editWeekoff').val(emp.Weekoff || '');
                    $('#editOttype').val(emp.Ottype || '');
                    $('#editOtslave').val(emp.Otslave || '');
                    $('#editPfApplicable').prop('checked', emp.PfApplicable || false);
                    $('#editEsicApplicable').prop('checked', emp.EsicApplicable || false);
                    $('#editPRFTax').prop('checked', emp.PRFTax || false);
                    $('#editAttendAllow').prop('checked', emp.AttendAllow || false);
                    $('#editOtAppl').prop('checked', emp.OtAppl || false);
                    $('#editMrOtAppl').prop('checked', emp.MrOtAppl || false);
                    $('#editAllowAsPer').prop('checked', emp.AllowAsPer !== undefined ? emp.AllowAsPer : true);
                    $('#editReversePF').prop('checked', emp.ReversePF || false);
                    $('#editIsEmployee').prop('checked', emp.is_employee || false);
                    $('#editIsSupervisor').prop('checked', emp.is_supervisor || false);
                    $('#editIsOfficeStaff').prop('checked', emp.is_officeStaff || false);
                    
                    // Update searchable dropdowns after a short delay to ensure they're initialized
                    setTimeout(function() {
                        // Update searchable dropdown inputs to show selected values
                        $('#editSiteId, #editDepartmentId, #editDesignationId, #editGangId, #editGender, #editMaritalStatus, #editPaymentmode, #editWeekoff, #editOttype, #editSkill, #editStatus').each(function() {
                            var $select = $(this);
                            var $input = $select.siblings('.searchable-dropdown').find('.searchable-input');
                            if ($input.length > 0 && $select.val()) {
                                var selectedText = $select.find('option:selected').text();
                                if (selectedText && selectedText !== 'Select Site' && selectedText !== 'Select Department' && selectedText !== 'Select Designation' && selectedText !== 'Select Gang' && selectedText !== 'Select Gender' && selectedText !== 'Select Marital Status' && selectedText !== 'Select Payment Mode' && selectedText !== 'Select Weekoff' && selectedText !== 'Select OT Type' && selectedText !== 'Select Skill' && selectedText !== 'Select Status') {
                                    $input.val(selectedText);
                                }
                            }
                        });
                        
                        // Remove loading flag after data is loaded
                        $('#editEmployeeForm').data('loading', false);
                    }, 100);
                    
                    $('#editEmployeeModal').modal('show');
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load employee data'
                });
            }
        });
    });

    // Prevent form submission
    $('#editEmployeeForm').on('submit', function(e) {
        e.preventDefault();
        return false;
    });
    
    // Setup auto-save for edit form
    var updateUrl = '/stc/stc_payroll/public/master/employees/' + ($('#editEmployeeId').val() || '');
    window.setupAutoSave('#editEmployeeForm', '/stc/stc_payroll/public/master/employees');
    
    // Override saveField callback to refresh table
    var originalSaveField = window.saveField;
    window.saveField = function(fieldName, fieldValue, fieldElement, updateUrl, onSuccess, onError) {
        var customOnSuccess = function(response) {
            if (typeof table !== 'undefined') {
                table.draw();
            }
            if (typeof onSuccess === 'function') {
                onSuccess(response);
            }
        };
        return originalSaveField(fieldName, fieldValue, fieldElement, updateUrl, customOnSuccess, onError);
    };

    // Add Employee
    $('#addEmployeeForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        
        // Handle checkboxes - add 0 for unchecked
        var checkboxes = ['PfApplicable', 'EsicApplicable', 'PRFTax', 'AttendAllow', 'OtAppl', 'MrOtAppl', 'AllowAsPer', 'ReversePF', 'is_employee', 'is_supervisor', 'is_officeStaff'];
        checkboxes.forEach(function(checkbox) {
            if (!$('#' + 'add' + checkbox.charAt(0).toUpperCase() + checkbox.slice(1)).is(':checked')) {
                formData += '&' + checkbox + '=0';
            }
        });
        
        $.ajax({
            url: "/stc/stc_payroll/public/master/employees",
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#addEmployeeModal').modal('hide');
                    $('#addEmployeeForm')[0].reset();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message || 'Employee added successfully',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    table.draw();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to add employee'
                    });
                }
            },
            error: function(xhr) {
                var message = 'Failed to add employee';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: message
                });
            }
        });
    });

    // Delete Employee
    $(document).on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/stc/stc_payroll/public/master/employees/" + id,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message || 'Employee deleted successfully',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            table.draw();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Failed to delete employee'
                            });
                        }
                    },
                    error: function(xhr) {
                        var message = 'Failed to delete employee';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: message
                        });
                    }
                });
            }
        });
    });
    
    // Initialize searchable dropdowns for master data (with "Add new" functionality)
    $(document).ready(function() {
        // Site dropdowns
        $('#editSiteId, #addSiteId').each(function() {
            window.initSearchableDropdown(this, '/stc/stc_payroll/public/master/sites/store');
        });
        
        // Department dropdowns
        $('#editDepartmentId, #addDepartmentId').each(function() {
            window.initSearchableDropdown(this, '/stc/stc_payroll/public/master/departments/store');
        });
        
        // Designation dropdowns
        $('#editDesignationId, #addDesignationId').each(function() {
            window.initSearchableDropdown(this, '/stc/stc_payroll/public/master/designations/store');
        });
        
        // Gang dropdowns
        $('#editGangId, #addGangId').each(function() {
            window.initSearchableDropdown(this, '/stc/stc_payroll/public/master/gangs/store');
        });
        
        // Other searchable dropdowns (Gender, Marital Status, Payment Mode, Weekoff, OT Type, Skill, Status)
        $('#editGender, #addGender, #editMaritalStatus, #addMaritalStatus, #editPaymentmode, #addPaymentmode, #editWeekoff, #addWeekoff, #editOttype, #addOttype, #editSkill, #addSkill, #editStatus, #addStatus').each(function() {
            window.initSearchableDropdown(this); // No createUrl for these
        });
    });
});
</script>
@endpush
