@extends('layouts.header')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Employees Management</h3>
    <div class="card-tools">
      @if(($permissions['edit'] ?? false))
      <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addEmployeeModal">
        <i class="fas fa-plus"></i> Add Employee
      </button>
      @endif
      <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#importEmployeeModal">
        <i class="fas fa-file-import"></i> Import Employee
      </button>
      <button type="button" class="btn btn-warning btn-sm ml-2" data-toggle="modal" data-target="#importRateModal">
        <i class="fas fa-file-import"></i> Import Rate
      </button>
      <button type="button" class="btn btn-danger btn-sm ml-2" id="resetLeaveBalanceBtn" title="Reset leave balance to 22 for all employees (Only available in February, first 10 days)">
        <i class="fas fa-redo"></i> Reset Leave Balance
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
    
    <!-- Export Section -->
    <div class="row mb-3">
      <div class="col-md-12">
        <div class="btn-group">
          <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-file-export"></i> Export Selected
          </button>
          <div class="dropdown-menu">
            <a class="dropdown-item export-option" href="#" data-format="xlsx">
              <i class="fas fa-file-excel text-success"></i> Export as Excel (XLSX)
            </a>
            <a class="dropdown-item export-option" href="#" data-format="pdf">
              <i class="fas fa-file-pdf text-danger"></i> Export as PDF
            </a>
          </div>
        </div>
        <span class="ml-2 text-muted" id="selectedCount">0 selected</span>
      </div>
    </div>
  
    <!-- DataTable -->
    <div class="table-responsive">
    <table id="employeesTable" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th width="30">
            <input type="checkbox" id="selectAll" title="Select All">
          </th>
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
            <div class="col-md-6">
              <div class="form-group">
                <label>Aadhar <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="editAadhar" name="Aadhar" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Applicable for PF & ESIC <span class="text-danger">*</span></label>
                <select class="form-control" id="editPfEsicApplicable" name="PfEsicApplicable" required>
                  <option value="0">No</option>
                  <option value="1">Yes</option>
                </select>
              </div>
            </div>
            <div class="col-md-6" id="editUanWrapper" style="display: none;">
              <div class="form-group">
                <label>UAN</label>
                <input type="text" class="form-control" id="editUan" name="Uan">
              </div>
            </div>
            <div class="col-md-6" id="editEsicWrapper" style="display: none;">
              <div class="form-group">
                <label>ESIC</label>
                <input type="text" class="form-control" id="editEsic" name="Esic">
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
            <li class="nav-item">
              <a class="nav-link" id="edit-rate-tab" data-toggle="tab" href="#edit-rate" role="tab" aria-controls="edit-rate" aria-selected="false">
                <i class="fas fa-money-bill-wave"></i> Rate
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
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Leave Balance</label>
                    <input type="number" step="0.01" class="form-control" id="editLeaveBalance" name="leave_balance" placeholder="0.00">
                  </div>
                </div>
              </div>
            </div>

            {{-- Additional Settings Tab --}}
            <div class="tab-pane fade" id="edit-settings" role="tabpanel" aria-labelledby="edit-settings-tab">
              <div class="row mt-3">
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

            {{-- Rate Tab --}}
            <div class="tab-pane fade" id="edit-rate" role="tabpanel" aria-labelledby="edit-rate-tab">
              <div class="row mt-3">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>CTC</label>
                    <input type="number" step="0.01" class="form-control" id="editCtc" name="rate[ctc]" placeholder="0.00">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Basic</label>
                    <input type="number" step="0.01" class="form-control" id="editBasic" name="rate[basic]" placeholder="0.00">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>DA</label>
                    <input type="number" step="0.01" class="form-control" id="editDa" name="rate[da]" placeholder="0.00">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Attendance Rate</label>
                    <input type="number" step="0.01" class="form-control" id="editArate" name="rate[arate]" placeholder="0.00">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>OT Rate</label>
                    <input type="number" step="0.01" class="form-control" id="editOtrate" name="rate[otrate]" placeholder="0.00">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>HRA</label>
                    <input type="number" step="0.01" class="form-control" id="editHra" name="rate[hra]" placeholder="0.00">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Medical</label>
                    <input type="number" step="0.01" class="form-control" id="editMadical" name="rate[madical]" placeholder="0.00">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Exgratia Retention</label>
                    <input type="number" step="0.01" class="form-control" id="editExgratiaRetention" name="rate[ExgratiaRetention]" placeholder="0.00">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>LTA Retention</label>
                    <input type="number" step="0.01" class="form-control" id="editLTARetention" name="rate[LTARetention]" placeholder="0.00">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>LTA</label>
                    <input type="number" step="0.01" class="form-control" id="editLTA" name="rate[LTA]" placeholder="0.00">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>CA</label>
                    <input type="number" step="0.01" class="form-control" id="editCA" name="rate[CA]" placeholder="0.00">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Fooding</label>
                    <input type="number" step="0.01" class="form-control" id="editFooding" name="rate[Fooding]" placeholder="0.00">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Misc</label>
                    <input type="number" step="0.01" class="form-control" id="editMisc" name="rate[Misc]" placeholder="0.00">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>CEA</label>
                    <input type="number" step="0.01" class="form-control" id="editCEA" name="rate[CEA]" placeholder="0.00">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Washing Allowance</label>
                    <input type="number" step="0.01" class="form-control" id="editWashingAllowance" name="rate[WashingAllowance]" placeholder="0.00">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Professional Pursuits</label>
                    <input type="number" step="0.01" class="form-control" id="editProfessionalPursuits" name="rate[ProfessionalPursuits]" placeholder="0.00">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Special Allowance</label>
                    <input type="number" step="0.01" class="form-control" id="editSpecialAllowance" name="rate[SpecialAllowance]" placeholder="0.00">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Income Tax</label>
                    <input type="number" step="0.01" class="form-control" id="editIncomeTax" name="rate[IncomeTax]" placeholder="0.00">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Personal Pay</label>
                    <input type="number" step="0.01" class="form-control" id="editPersonalpay" name="rate[personalpay]" placeholder="0.00">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Petrol</label>
                    <input type="number" step="0.01" class="form-control" id="editPetrol" name="rate[petrol]" placeholder="0.00">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Mobile</label>
                    <input type="number" step="0.01" class="form-control" id="editRateMobile" name="rate[mobile]" placeholder="0.00">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Incentive</label>
                    <input type="number" step="0.01" class="form-control" id="editIncentive" name="rate[incentive]" placeholder="0.00">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Fixed Amount</label>
                    <input type="number" step="0.01" class="form-control" id="editFixedamt" name="rate[fixedamt]" placeholder="0.00">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Net Amount</label>
                    <input type="number" step="0.01" class="form-control" id="editNetamt" name="rate[netamt]" placeholder="0.00">
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
            <div class="col-md-6">
              <div class="form-group">
                <label>Aadhar <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="addAadhar" name="Aadhar" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Applicable for PF & ESIC <span class="text-danger">*</span></label>
                <select class="form-control" id="addPfEsicApplicable" name="PfEsicApplicable" required>
                  <option value="0">No</option>
                  <option value="1">Yes</option>
                </select>
              </div>
            </div>
            <div class="col-md-6" id="addUanWrapper" style="display: none;">
              <div class="form-group">
                <label>UAN</label>
                <input type="text" class="form-control" id="addUan" name="Uan">
              </div>
            </div>
            <div class="col-md-6" id="addEsicWrapper" style="display: none;">
              <div class="form-group">
                <label>ESIC</label>
                <input type="text" class="form-control" id="addEsic" name="Esic">
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
                      <option value="HIGH-SKILLED">High-Skilled</option>
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

<!-- Import Employee Modal -->
<div class="modal fade" id="importEmployeeModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl" role="document" style="max-width: 95%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Import Employee</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="importEmployeeForm" enctype="multipart/form-data">
        <div class="modal-body">
          <div id="employeeUploadSection">
            <div class="form-group">
              <label for="employeeFile">Select Excel File <span class="text-danger">*</span></label>
              <input type="file" class="form-control-file" id="employeeFile" name="file" accept=".xlsx,.xls" required>
              <small class="form-text text-muted">Please upload an Excel file (.xlsx or .xls format)</small>
            </div>
            <div class="alert alert-info">
              <i class="fas fa-info-circle"></i> Make sure your Excel file matches the sample format.
            </div>
          </div>
          
          <div id="employeePreviewSection" style="display: none;">
            <div class="alert alert-warning">
              <i class="fas fa-exclamation-triangle"></i> Please review the data below. You can remove rows you don't want to import.
            </div>
            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
              <table class="table table-bordered table-striped table-sm" id="employeePreviewTable">
                <thead class="thead-light" style="position: sticky; top: 0; z-index: 10;">
                  <tr id="employeePreviewHeaders"></tr>
                </thead>
                <tbody id="employeePreviewBody"></tbody>
              </table>
            </div>
            <div class="mt-2">
              <strong>Total Rows:</strong> <span id="employeeTotalRows">0</span> | 
              <strong>Rows to Import:</strong> <span id="employeeRowsToImport">0</span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-success" id="downloadEmployeeSample">
            <i class="fas fa-download"></i> Download Sample Excel
          </button>
          <button type="button" class="btn btn-info" id="employeeBackToUpload" style="display: none;">
            <i class="fas fa-arrow-left"></i> Back to Upload
          </button>
          <button type="submit" class="btn btn-primary" id="employeeUploadBtn">
            <i class="fas fa-upload"></i> Upload & Preview
          </button>
          <button type="button" class="btn btn-success" id="employeeSaveBtn" style="display: none;">
            <i class="fas fa-save"></i> Save & Import
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Import Rate Modal -->
<div class="modal fade" id="importRateModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-xl" role="document" style="max-width: 95%;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Import Rate</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="importRateForm" enctype="multipart/form-data">
        <div class="modal-body">
          <div id="rateUploadSection">
            <div class="form-group">
              <label for="rateFile">Select Excel File <span class="text-danger">*</span></label>
              <input type="file" class="form-control-file" id="rateFile" name="file" accept=".xlsx,.xls" required>
              <small class="form-text text-muted">Please upload an Excel file (.xlsx or .xls format)</small>
            </div>
            <div class="alert alert-info">
              <i class="fas fa-info-circle"></i> Make sure your Excel file matches the sample format.
            </div>
          </div>
          
          <div id="ratePreviewSection" style="display: none;">
            <div class="alert alert-warning">
              <i class="fas fa-exclamation-triangle"></i> Please review the data below. You can remove rows you don't want to import.
            </div>
            <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
              <table class="table table-bordered table-striped table-sm" id="ratePreviewTable">
                <thead class="thead-light" style="position: sticky; top: 0; z-index: 10;">
                  <tr id="ratePreviewHeaders"></tr>
                </thead>
                <tbody id="ratePreviewBody"></tbody>
              </table>
            </div>
            <div class="mt-2">
              <strong>Total Rows:</strong> <span id="rateTotalRows">0</span> | 
              <strong>Rows to Import:</strong> <span id="rateRowsToImport">0</span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-success" id="downloadRateSample">
            <i class="fas fa-download"></i> Download Sample Excel
          </button>
          <button type="button" class="btn btn-info" id="rateBackToUpload" style="display: none;">
            <i class="fas fa-arrow-left"></i> Back to Upload
          </button>
          <button type="submit" class="btn btn-primary" id="rateUploadBtn">
            <i class="fas fa-upload"></i> Upload & Preview
          </button>
          <button type="button" class="btn btn-success" id="rateSaveBtn" style="display: none;">
            <i class="fas fa-save"></i> Save & Import
          </button>
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
            url: window.appBaseUrl + "/master/employees/list",
            type: 'GET',
            data: function(d) {
                d.site_id = $('#filterSite').val();
                d.department_id = $('#filterDepartment').val();
                d.status = $('#filterStatus').val();
            }
        },
        columns: [
            {
                data: null,
                orderable: false,
                searchable: false,
                width: '30px',
                render: function(data, type, row) {
                    return '<input type="checkbox" class="row-checkbox" value="' + row.id + '">';
                }
            },
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
                           (@json($permissions['edit'] ?? false) ? '<button class="btn btn-warning btn-sm edit-btn" data-id="' + id + '" title="Edit"><i class="fas fa-edit"></i></button> ' : '<button class="btn btn-warning btn-sm" disabled title="No permission"><i class="fas fa-edit"></i></button> ') +
                           (@json($permissions['delete'] ?? false) ? '<button class="btn btn-danger btn-sm delete-btn" data-id="' + id + '" title="Delete"><i class="fas fa-trash"></i></button>' : '<button class="btn btn-danger btn-sm" disabled title="No permission"><i class="fas fa-trash"></i></button>');
                }
            }
        ],
        order: [[2, 'asc']], // Changed to column 2 (EmpId) since checkbox is now column 0
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]]
    });

    // Filter change events
    $('#filterSite, #filterDepartment, #filterStatus').on('change', function() {
        table.draw();
        // Uncheck all when filters change
        $('#selectAll').prop('checked', false);
        setTimeout(updateSelectedCount, 100);
    });

    // Reset filters
    $('#resetFilters').on('click', function() {
        $('#filterSite, #filterDepartment, #filterStatus').val('');
        table.draw();
        $('#selectAll').prop('checked', false);
        setTimeout(updateSelectedCount, 100);
    });
    
    // Select All checkbox
    $('#selectAll').on('change', function() {
        var isChecked = $(this).prop('checked');
        $('.row-checkbox').prop('checked', isChecked);
        updateSelectedCount();
    });
    
    // Individual row checkbox
    $(document).on('change', '.row-checkbox', function() {
        updateSelectedCount();
        // Update select all checkbox state
        var totalCheckboxes = $('.row-checkbox').length;
        var checkedCheckboxes = $('.row-checkbox:checked').length;
        $('#selectAll').prop('checked', totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes);
    });
    
    // Update selected count
    function updateSelectedCount() {
        var count = $('.row-checkbox:checked').length;
        $('#selectedCount').text(count + ' selected');
    }
    
    // Update count when table redraws
    table.on('draw', function() {
        updateSelectedCount();
        // Uncheck select all when table redraws
        $('#selectAll').prop('checked', false);
    });
    
    // Export dropdown options
    $(document).on('click', '.export-option', function(e) {
        e.preventDefault();
        var format = $(this).data('format');
        var selectedIds = [];
        $('.row-checkbox:checked').each(function() {
            selectedIds.push($(this).val());
        });
        
        if (selectedIds.length === 0) {
            // Export all records with warning
            Swal.fire({
                title: 'Export All Records?',
                text: 'No rows selected. All records from the database will be exported as ' + format.toUpperCase() + '. This may take a while if there are many records.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, export all',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    exportEmployees([], format);
                }
            });
        } else {
            // Export selected records
            exportEmployees(selectedIds, format);
        }
    });
    
    // Export function
    function exportEmployees(selectedIds, format) {
        var form = $('<form>', {
            'method': 'POST',
            'action': window.appBaseUrl + '/master/employees/export'
        });
        
        form.append($('<input>', {
            'type': 'hidden',
            'name': '_token',
            'value': $('meta[name="csrf-token"]').attr('content')
        }));
        
        form.append($('<input>', {
            'type': 'hidden',
            'name': 'format',
            'value': format
        }));
        
        if (selectedIds.length > 0) {
            selectedIds.forEach(function(id) {
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': 'ids[]',
                    'value': id
                }));
            });
        }
        
        // Add filters
        if ($('#filterSite').val()) {
            form.append($('<input>', {
                'type': 'hidden',
                'name': 'site_id',
                'value': $('#filterSite').val()
            }));
        }
        if ($('#filterDepartment').val()) {
            form.append($('<input>', {
                'type': 'hidden',
                'name': 'department_id',
                'value': $('#filterDepartment').val()
            }));
        }
        if ($('#filterStatus').val()) {
            form.append($('<input>', {
                'type': 'hidden',
                'name': 'status',
                'value': $('#filterStatus').val()
            }));
        }
        
        $('body').append(form);
        form.submit();
        form.remove();
    }

    // View Employee
    $(document).on('click', '.view-btn', function() {
        var id = $(this).data('id');
        $.ajax({
            url: window.appBaseUrl + "/master/employees/show/" + id,
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
                        '<li class="nav-item"><a class="nav-link" id="view-rate-tab" data-toggle="tab" href="#view-rate" role="tab"><i class="fas fa-money-bill-wave"></i> Rate</a></li>' +
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
                        '<div class="col-md-6 mb-3"><strong>Leave Balance:</strong><br><span class="text-muted">' + (emp.leave_balance !== null && emp.leave_balance !== undefined ? parseFloat(emp.leave_balance).toFixed(2) : '0.00') + '</span></div>' +
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
                        '</div></div>' +
                        '<div class="tab-pane fade" id="view-rate" role="tabpanel">' +
                        '<div class="row mt-3">' +
                        '<div class="col-md-4 mb-3"><strong>CTC:</strong><br><span class="text-muted">' + (emp.rate && emp.rate.ctc ? parseFloat(emp.rate.ctc).toFixed(2) : '0.00') + '</span></div>' +
                        '<div class="col-md-4 mb-3"><strong>Basic:</strong><br><span class="text-muted">' + (emp.rate && emp.rate.basic ? parseFloat(emp.rate.basic).toFixed(2) : '0.00') + '</span></div>' +
                        '<div class="col-md-4 mb-3"><strong>DA:</strong><br><span class="text-muted">' + (emp.rate && emp.rate.da ? parseFloat(emp.rate.da).toFixed(2) : '0.00') + '</span></div>' +
                        '<div class="col-md-4 mb-3"><strong>Attendance Rate:</strong><br><span class="text-muted">' + (emp.rate && emp.rate.arate ? parseFloat(emp.rate.arate).toFixed(2) : '0.00') + '</span></div>' +
                        '<div class="col-md-4 mb-3"><strong>OT Rate:</strong><br><span class="text-muted">' + (emp.rate && emp.rate.otrate ? parseFloat(emp.rate.otrate).toFixed(2) : '0.00') + '</span></div>' +
                        '<div class="col-md-4 mb-3"><strong>HRA:</strong><br><span class="text-muted">' + (emp.rate && emp.rate.hra ? parseFloat(emp.rate.hra).toFixed(2) : '0.00') + '</span></div>' +
                        '<div class="col-md-4 mb-3"><strong>Medical:</strong><br><span class="text-muted">' + (emp.rate && emp.rate.madical ? parseFloat(emp.rate.madical).toFixed(2) : '0.00') + '</span></div>' +
                        '<div class="col-md-4 mb-3"><strong>Exgratia Retention:</strong><br><span class="text-muted">' + (emp.rate && emp.rate.ExgratiaRetention ? parseFloat(emp.rate.ExgratiaRetention).toFixed(2) : '0.00') + '</span></div>' +
                        '<div class="col-md-4 mb-3"><strong>LTA Retention:</strong><br><span class="text-muted">' + (emp.rate && emp.rate.LTARetention ? parseFloat(emp.rate.LTARetention).toFixed(2) : '0.00') + '</span></div>' +
                        '<div class="col-md-4 mb-3"><strong>LTA:</strong><br><span class="text-muted">' + (emp.rate && emp.rate.LTA ? parseFloat(emp.rate.LTA).toFixed(2) : '0.00') + '</span></div>' +
                        '<div class="col-md-4 mb-3"><strong>CA:</strong><br><span class="text-muted">' + (emp.rate && emp.rate.CA ? parseFloat(emp.rate.CA).toFixed(2) : '0.00') + '</span></div>' +
                        '<div class="col-md-4 mb-3"><strong>Fooding:</strong><br><span class="text-muted">' + (emp.rate && emp.rate.Fooding ? parseFloat(emp.rate.Fooding).toFixed(2) : '0.00') + '</span></div>' +
                        '<div class="col-md-4 mb-3"><strong>Misc:</strong><br><span class="text-muted">' + (emp.rate && emp.rate.Misc ? parseFloat(emp.rate.Misc).toFixed(2) : '0.00') + '</span></div>' +
                        '<div class="col-md-4 mb-3"><strong>CEA:</strong><br><span class="text-muted">' + (emp.rate && emp.rate.CEA ? parseFloat(emp.rate.CEA).toFixed(2) : '0.00') + '</span></div>' +
                        '<div class="col-md-4 mb-3"><strong>Washing Allowance:</strong><br><span class="text-muted">' + (emp.rate && emp.rate.WashingAllowance ? parseFloat(emp.rate.WashingAllowance).toFixed(2) : '0.00') + '</span></div>' +
                        '<div class="col-md-4 mb-3"><strong>Professional Pursuits:</strong><br><span class="text-muted">' + (emp.rate && emp.rate.ProfessionalPursuits ? parseFloat(emp.rate.ProfessionalPursuits).toFixed(2) : '0.00') + '</span></div>' +
                        '<div class="col-md-4 mb-3"><strong>Special Allowance:</strong><br><span class="text-muted">' + (emp.rate && emp.rate.SpecialAllowance ? parseFloat(emp.rate.SpecialAllowance).toFixed(2) : '0.00') + '</span></div>' +
                        '<div class="col-md-4 mb-3"><strong>Income Tax:</strong><br><span class="text-muted">' + (emp.rate && emp.rate.IncomeTax ? parseFloat(emp.rate.IncomeTax).toFixed(2) : '0.00') + '</span></div>' +
                        '<div class="col-md-4 mb-3"><strong>Personal Pay:</strong><br><span class="text-muted">' + (emp.rate && emp.rate.personalpay ? parseFloat(emp.rate.personalpay).toFixed(2) : '0.00') + '</span></div>' +
                        '<div class="col-md-4 mb-3"><strong>Petrol:</strong><br><span class="text-muted">' + (emp.rate && emp.rate.petrol ? parseFloat(emp.rate.petrol).toFixed(2) : '0.00') + '</span></div>' +
                        '<div class="col-md-4 mb-3"><strong>Mobile:</strong><br><span class="text-muted">' + (emp.rate && emp.rate.mobile ? parseFloat(emp.rate.mobile).toFixed(2) : '0.00') + '</span></div>' +
                        '<div class="col-md-4 mb-3"><strong>Incentive:</strong><br><span class="text-muted">' + (emp.rate && emp.rate.incentive ? parseFloat(emp.rate.incentive).toFixed(2) : '0.00') + '</span></div>' +
                        '<div class="col-md-4 mb-3"><strong>Fixed Amount:</strong><br><span class="text-muted">' + (emp.rate && emp.rate.fixedamt ? parseFloat(emp.rate.fixedamt).toFixed(2) : '0.00') + '</span></div>' +
                        '<div class="col-md-4 mb-3"><strong>Net Amount:</strong><br><span class="text-muted">' + (emp.rate && emp.rate.netamt ? parseFloat(emp.rate.netamt).toFixed(2) : '0.00') + '</span></div>' +
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
            url: window.appBaseUrl + "/master/employees/show/" + id,
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
                    $('#editLeaveBalance').val(emp.leave_balance !== null && emp.leave_balance !== undefined ? parseFloat(emp.leave_balance).toFixed(2) : '0.00');
                    // Set PF & ESIC Applicable dropdown (show if either PF or ESIC is applicable)
                    var pfEsicApplicable = (emp.PfApplicable || emp.EsicApplicable) ? '1' : '0';
                    $('#editPfEsicApplicable').val(pfEsicApplicable).trigger('change');
                    $('#editPRFTax').prop('checked', emp.PRFTax || false);
                    $('#editAttendAllow').prop('checked', emp.AttendAllow || false);
                    $('#editOtAppl').prop('checked', emp.OtAppl || false);
                    $('#editMrOtAppl').prop('checked', emp.MrOtAppl || false);
                    $('#editAllowAsPer').prop('checked', emp.AllowAsPer !== undefined ? emp.AllowAsPer : true);
                    $('#editReversePF').prop('checked', emp.ReversePF || false);
                    $('#editIsEmployee').prop('checked', emp.is_employee || false);
                    $('#editIsSupervisor').prop('checked', emp.is_supervisor || false);
                    $('#editIsOfficeStaff').prop('checked', emp.is_officeStaff || false);
                    
                    // Load rate data if available
                    if (emp.rate) {
                        $('#editCtc').val(emp.rate.ctc || '');
                        $('#editBasic').val(emp.rate.basic || '');
                        $('#editDa').val(emp.rate.da || '');
                        $('#editArate').val(emp.rate.arate || '');
                        $('#editOtrate').val(emp.rate.otrate || '');
                        $('#editHra').val(emp.rate.hra || '');
                        $('#editMadical').val(emp.rate.madical || '');
                        $('#editExgratiaRetention').val(emp.rate.ExgratiaRetention || '');
                        $('#editLTARetention').val(emp.rate.LTARetention || '');
                        $('#editLTA').val(emp.rate.LTA || '');
                        $('#editCA').val(emp.rate.CA || '');
                        $('#editFooding').val(emp.rate.Fooding || '');
                        $('#editMisc').val(emp.rate.Misc || '');
                        $('#editCEA').val(emp.rate.CEA || '');
                        $('#editWashingAllowance').val(emp.rate.WashingAllowance || '');
                        $('#editProfessionalPursuits').val(emp.rate.ProfessionalPursuits || '');
                        $('#editSpecialAllowance').val(emp.rate.SpecialAllowance || '');
                        $('#editIncomeTax').val(emp.rate.IncomeTax || '');
                        $('#editPersonalpay').val(emp.rate.personalpay || '');
                        $('#editPetrol').val(emp.rate.petrol || '');
                        $('#editRateMobile').val(emp.rate.mobile || '');
                        $('#editIncentive').val(emp.rate.incentive || '');
                        $('#editFixedamt').val(emp.rate.fixedamt || '');
                        $('#editNetamt').val(emp.rate.netamt || '');
                    } else {
                        // Clear rate fields if no rate data
                        $('#editCtc, #editBasic, #editDa, #editArate, #editOtrate, #editHra, #editMadical, #editExgratiaRetention, #editLTARetention, #editLTA, #editCA, #editFooding, #editMisc, #editCEA, #editWashingAllowance, #editProfessionalPursuits, #editSpecialAllowance, #editIncomeTax, #editPersonalpay, #editPetrol, #editRateMobile, #editIncentive, #editFixedamt, #editNetamt').val('');
                    }
                    
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
    var updateUrl = window.appBaseUrl + '/master/employees/' + ($('#editEmployeeId').val() || '');
    window.setupAutoSave('#editEmployeeForm', window.appBaseUrl + '/master/employees');
    
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
            url: window.appBaseUrl + "/master/employees",
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
                    url: window.appBaseUrl + "/master/employees/" + id,
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
            window.initSearchableDropdown(this, window.appBaseUrl + '/master/sites/store');
        });
        
        // Department dropdowns
        $('#editDepartmentId, #addDepartmentId').each(function() {
            window.initSearchableDropdown(this, window.appBaseUrl + '/master/departments/store');
        });
        
        // Designation dropdowns
        $('#editDesignationId, #addDesignationId').each(function() {
            window.initSearchableDropdown(this, window.appBaseUrl + '/master/designations/store');
        });
        
        // Gang dropdowns
        $('#editGangId, #addGangId').each(function() {
            window.initSearchableDropdown(this, window.appBaseUrl + '/master/gangs/store');
        });
        
        // Other searchable dropdowns (Gender, Marital Status, Payment Mode, Weekoff, OT Type, Skill, Status)
        $('#editGender, #addGender, #editMaritalStatus, #addMaritalStatus, #editPaymentmode, #addPaymentmode, #editWeekoff, #addWeekoff, #editOttype, #addOttype, #editSkill, #addSkill, #editStatus, #addStatus').each(function() {
            window.initSearchableDropdown(this); // No createUrl for these
        });
    });
    
    // PF/ESIC Applicable Show/Hide Logic for Add Form
    $('#addPfEsicApplicable').on('change', function() {
        if ($(this).val() === '1') {
            $('#addUanWrapper').show();
            $('#addEsicWrapper').show();
        } else {
            $('#addUanWrapper').hide();
            $('#addEsicWrapper').hide();
            $('#addUan').val('');
            $('#addEsic').val('');
        }
    });
    
    // PF/ESIC Applicable Show/Hide Logic for Edit Form
    $('#editPfEsicApplicable').on('change', function() {
        if ($(this).val() === '1') {
            $('#editUanWrapper').show();
            $('#editEsicWrapper').show();
        } else {
            $('#editUanWrapper').hide();
            $('#editEsicWrapper').hide();
            $('#editUan').val('');
            $('#editEsic').val('');
        }
    });
    
    // Download Employee Sample Excel
    $('#downloadEmployeeSample').on('click', function() {
        window.location.href = window.appBaseUrl + '/master/employees/export-sample?type=employee';
    });
    
    // Download Rate Sample Excel
    $('#downloadRateSample').on('click', function() {
        window.location.href = window.appBaseUrl + '/master/employees/export-sample?type=rate';
    });
    
    var employeePreviewData = [];
    
    // Import Employee Form Submit - Preview
    $('#importEmployeeForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        formData.append('preview', '1');
        
        $.ajax({
            url: window.appBaseUrl + '/master/employees/import-preview',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#employeeUploadBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
            },
            success: function(response) {
                if (response.success) {
                    employeePreviewData = response.data;
                    displayEmployeePreview(response.data, response.headers);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to process file'
                    });
                }
            },
            error: function(xhr) {
                var message = 'Failed to process file';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: message
                });
            },
            complete: function() {
                $('#employeeUploadBtn').prop('disabled', false).html('<i class="fas fa-upload"></i> Upload & Preview');
            }
        });
    });
    
    // Display Employee Preview
    function displayEmployeePreview(data, headers) {
        // Show preview section, hide upload section
        $('#employeeUploadSection').hide();
        $('#employeePreviewSection').show();
        $('#employeeUploadBtn').hide();
        $('#employeeSaveBtn').show();
        $('#employeeBackToUpload').show();
        
        // Build headers
        var headerRow = '<th style="width: 80px;">Remove</th>';
        headers.forEach(function(header) {
            headerRow += '<th>' + header + '</th>';
        });
        $('#employeePreviewHeaders').html(headerRow);
        
        // Build rows
        var tbody = '';
        data.forEach(function(row, index) {
            tbody += '<tr data-index="' + index + '">';
            tbody += '<td><button type="button" class="btn btn-danger btn-sm remove-row" data-index="' + index + '"><i class="fas fa-times"></i></button></td>';
            headers.forEach(function(header) {
                var value = row[header.toLowerCase()] || '';
                tbody += '<td>' + (value !== null && value !== undefined ? value : '') + '</td>';
            });
            tbody += '</tr>';
        });
        $('#employeePreviewBody').html(tbody);
        
        // Update counts
        $('#employeeTotalRows').text(data.length);
        $('#employeeRowsToImport').text(data.length);
    }
    
    // Remove Employee Row
    $(document).on('click', '#employeePreviewTable .remove-row', function() {
        var index = $(this).data('index');
        $(this).closest('tr').remove();
        employeePreviewData = employeePreviewData.filter(function(item, i) {
            return i !== index;
        });
        // Re-index
        $('#employeePreviewTable tbody tr').each(function(i) {
            $(this).attr('data-index', i);
            $(this).find('.remove-row').attr('data-index', i);
        });
        $('#employeeRowsToImport').text(employeePreviewData.length);
    });
    
    // Back to Upload - Employee
    $('#employeeBackToUpload').on('click', function() {
        $('#employeeUploadSection').show();
        $('#employeePreviewSection').hide();
        $('#employeeUploadBtn').show();
        $('#employeeSaveBtn').hide();
        $('#employeeBackToUpload').hide();
        employeePreviewData = [];
    });
    
    // Save Employee Import
    $('#employeeSaveBtn').on('click', function() {
        if (employeePreviewData.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Data',
                text: 'Please select at least one row to import'
            });
            return;
        }
        
        $.ajax({
            url: window.appBaseUrl + '/master/employees/import',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                data: employeePreviewData
            },
            beforeSend: function() {
                $('#employeeSaveBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Importing...');
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message || 'Employees imported successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(function() {
                        $('#importEmployeeModal').modal('hide');
                        $('#importEmployeeForm')[0].reset();
                        $('#employeeUploadSection').show();
                        $('#employeePreviewSection').hide();
                        $('#employeeUploadBtn').show();
                        $('#employeeSaveBtn').hide();
                        $('#employeeBackToUpload').hide();
                        employeePreviewData = [];
                        table.ajax.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to import employees'
                    });
                }
            },
            error: function(xhr) {
                var message = 'Failed to import employees';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: message
                });
            },
            complete: function() {
                $('#employeeSaveBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Save & Import');
            }
        });
    });
    
    var ratePreviewData = [];
    
    // Import Rate Form Submit - Preview
    $('#importRateForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        formData.append('preview', '1');
        
        $.ajax({
            url: window.appBaseUrl + '/master/employees/import-rate-preview',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#rateUploadBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Processing...');
            },
            success: function(response) {
                if (response.success) {
                    ratePreviewData = response.data;
                    displayRatePreview(response.data, response.headers);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to process file'
                    });
                }
            },
            error: function(xhr) {
                var message = 'Failed to process file';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: message
                });
            },
            complete: function() {
                $('#rateUploadBtn').prop('disabled', false).html('<i class="fas fa-upload"></i> Upload & Preview');
            }
        });
    });
    
    // Display Rate Preview
    function displayRatePreview(data, headers) {
        // Show preview section, hide upload section
        $('#rateUploadSection').hide();
        $('#ratePreviewSection').show();
        $('#rateUploadBtn').hide();
        $('#rateSaveBtn').show();
        $('#rateBackToUpload').show();
        
        // Build headers
        var headerRow = '<th style="width: 80px;">Remove</th>';
        headers.forEach(function(header) {
            headerRow += '<th>' + header + '</th>';
        });
        $('#ratePreviewHeaders').html(headerRow);
        
        // Build rows
        var tbody = '';
        data.forEach(function(row, index) {
            tbody += '<tr data-index="' + index + '">';
            tbody += '<td><button type="button" class="btn btn-danger btn-sm remove-row" data-index="' + index + '"><i class="fas fa-times"></i></button></td>';
            headers.forEach(function(header) {
                var value = '';
                if (header === 'Employee Name') {
                    value = row['employee_name'] || '';
                } else {
                    value = row[header.toLowerCase()] || '';
                }
                // Highlight "Not Found" in red
                if (header === 'Employee Name' && value === 'Not Found') {
                    tbody += '<td style="color: red; font-weight: bold;">' + value + '</td>';
                } else {
                    tbody += '<td>' + (value !== null && value !== undefined ? value : '') + '</td>';
                }
            });
            tbody += '</tr>';
        });
        $('#ratePreviewBody').html(tbody);
        
        // Update counts
        $('#rateTotalRows').text(data.length);
        $('#rateRowsToImport').text(data.length);
    }
    
    // Remove Rate Row
    $(document).on('click', '#ratePreviewTable .remove-row', function() {
        var index = $(this).data('index');
        $(this).closest('tr').remove();
        ratePreviewData = ratePreviewData.filter(function(item, i) {
            return i !== index;
        });
        // Re-index
        $('#ratePreviewTable tbody tr').each(function(i) {
            $(this).attr('data-index', i);
            $(this).find('.remove-row').attr('data-index', i);
        });
        $('#rateRowsToImport').text(ratePreviewData.length);
    });
    
    // Back to Upload - Rate
    $('#rateBackToUpload').on('click', function() {
        $('#rateUploadSection').show();
        $('#ratePreviewSection').hide();
        $('#rateUploadBtn').show();
        $('#rateSaveBtn').hide();
        $('#rateBackToUpload').hide();
        ratePreviewData = [];
    });
    
    // Save Rate Import
    $('#rateSaveBtn').on('click', function() {
        if (ratePreviewData.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Data',
                text: 'Please select at least one row to import'
            });
            return;
        }
        
        $.ajax({
            url: window.appBaseUrl + '/master/employees/import-rate',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                data: ratePreviewData
            },
            beforeSend: function() {
                $('#rateSaveBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Importing...');
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message || 'Rates imported successfully!',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(function() {
                        $('#importRateModal').modal('hide');
                        $('#importRateForm')[0].reset();
                        $('#rateUploadSection').show();
                        $('#ratePreviewSection').hide();
                        $('#rateUploadBtn').show();
                        $('#rateSaveBtn').hide();
                        $('#rateBackToUpload').hide();
                        ratePreviewData = [];
                        table.ajax.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to import rates'
                    });
                }
            },
            error: function(xhr) {
                var message = 'Failed to import rates';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: message
                });
            },
            complete: function() {
                $('#rateSaveBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Save & Import');
            }
        });
    });

    // Reset Leave Balance Button - Validation and Handler
    function checkResetLeaveBalanceAvailability() {
        var now = new Date();
        var currentMonth = now.getMonth() + 1; // JavaScript months are 0-indexed (0-11)
        var currentDay = now.getDate();
        
        // Check if it's January (month 1) and within first 10 days
        var isAvailable = (currentMonth === 1 && currentDay <= 10);
        
        var $btn = $('#resetLeaveBalanceBtn');
        if (!isAvailable) {
            $btn.prop('disabled', true);
            $btn.css('opacity', '0.5');
            $btn.attr('title', 'Reset leave balance is only available in February, first 10 days of the month');
        } else {
            $btn.prop('disabled', false);
            $btn.css('opacity', '1');
            $btn.attr('title', 'Reset leave balance to 22 for all employees (Only available in February, first 10 days)');
        }
    }

    // Check availability on page load
    checkResetLeaveBalanceAvailability();

    // Reset Leave Balance Button Click Handler
    $('#resetLeaveBalanceBtn').on('click', function() {
        var now = new Date();
        var currentMonth = now.getMonth() + 1;
        var currentDay = now.getDate();
        
        // Double check validation
        if (currentMonth !== 1 || currentDay > 10) {
            Swal.fire({
                icon: 'error',
                title: 'Not Available',
                text: 'Reset leave balance is only available in February, first 10 days of the month.',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Show confirmation dialog
        Swal.fire({
            icon: 'warning',
            title: 'Reset Leave Balance?',
            html: '<p>This will reset <strong>leave balance to 22</strong> for <strong>ALL employees</strong>.</p><p class="text-danger"><strong>This action cannot be undone!</strong></p>',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, Reset All',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'Processing...',
                    html: 'Resetting leave balance for all employees. Please wait...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Make AJAX request
                $.ajax({
                    url: window.appBaseUrl + '/master/employees/reset-leave-balance',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message || 'Leave balance reset to 22 for all employees successfully.',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                // Reload table if it exists
                                if (typeof table !== 'undefined') {
                                    table.ajax.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Failed to reset leave balance'
                            });
                        }
                    },
                    error: function(xhr) {
                        var message = 'Failed to reset leave balance';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            html: message
                        });
                    }
                });
            }
        });
    });
});
</script>
@endpush
