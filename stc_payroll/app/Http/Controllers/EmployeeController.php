<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\Site;
use App\Department;
use App\Designation;
use App\Gang;
use App\Rate;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Dompdf\Dompdf;
use App\Http\Controllers\Traits\HasPermissions;
use App\Http\Controllers\Traits\CheckPermissions;

class EmployeeController extends Controller
{
    use HasPermissions, CheckPermissions;
    
    public function index()
    {
        $user = auth()->user();
        
        // Check if user has view permission (root user always has access)
        if (!$user || (!$user->hasPermission('master.employees.view') && $user->email !== 'root@stcassociate.com')) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to access this page'
                ], 403);
            }
            return redirect(route('home'))->with('error', 'You do not have permission to access this page');
        }
        
        return view('pages.master.employees', [
            'page_title' => 'Employees',
            'sites' => Site::all(),
            'departments' => Department::all(),
            'designations' => Designation::all(),
            'gangs' => Gang::all(),
            'permissions' => $this->getPermissions('master', 'employees')
        ]);
    }

    public function list(Request $request)
    {
        $query = Employee::with(['site', 'department', 'designation', 'gang']);

        // Search functionality
        if ($request->has('search') && $request->search['value']) {
            $search = $request->search['value'];
            $query->where(function($q) use ($search) {
                $q->where('EmpId', 'like', "%{$search}%")
                  ->orWhere('Name', 'like', "%{$search}%")
                  ->orWhere('Email', 'like', "%{$search}%")
                  ->orWhere('Mobile', 'like', "%{$search}%")
                  ->orWhereHas('site', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('department', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('designation', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by site
        if ($request->has('site_id') && $request->site_id) {
            $query->where('site_id', $request->site_id);
        }

        // Filter by department
        if ($request->has('department_id') && $request->department_id) {
            $query->where('department_id', $request->department_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('Status', $request->status);
        }

        // Get total count before pagination
        $totalRecords = $query->count();

        // Ordering
        if ($request->has('order') && is_array($request->order) && count($request->order) > 0) {
            $orderColumn = $request->order[0]['column'] ?? 0;
            $orderDir = $request->order[0]['dir'] ?? 'asc';
            $columns = ['EmpId', 'Name', 'Father', 'site_id', 'department_id', 'designation_id', 'gang_id', 'Email'];
            $orderBy = isset($columns[$orderColumn]) ? $columns[$orderColumn] : 'id';
            
            if ($orderBy == 'site_id') {
                $query->leftJoin('sites', 'employees.site_id', '=', 'sites.id')
                      ->orderBy('sites.name', $orderDir)
                      ->select('employees.*');
            } elseif ($orderBy == 'department_id') {
                $query->leftJoin('departments', 'employees.department_id', '=', 'departments.id')
                      ->orderBy('departments.name', $orderDir)
                      ->select('employees.*');
            } elseif ($orderBy == 'designation_id') {
                $query->leftJoin('designations', 'employees.designation_id', '=', 'designations.id')
                      ->orderBy('designations.name', $orderDir)
                      ->select('employees.*');
            } else {
                $query->orderBy($orderBy, $orderDir);
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $employees = $query->skip($start)->take($length)->get();

        $data = [];
        foreach ($employees as $employee) {
            $data[] = [
                'id' => $employee->id,
                'EmpId' => $employee->EmpId,
                'Name' => $employee->Name,
                'Father' => $employee->Father ?? 'N/A',
                'Gender' => $employee->Gender ?? 'N/A',
                'Dob' => $employee->Dob ? $employee->Dob->format('Y-m-d') : 'N/A',
                'Doj' => $employee->Doj ? $employee->Doj->format('Y-m-d') : 'N/A',
                'Doe' => $employee->Doe ? $employee->Doe->format('Y-m-d') : 'N/A',
                'Site' => $employee->site ? $employee->site->name : 'N/A',
                'Department' => $employee->department ? $employee->department->name : 'N/A',
                'Designation' => $employee->designation ? $employee->designation->name : 'N/A',
                'Gang' => $employee->gang ? $employee->gang->name : 'N/A',
                'MaritalStatus' => $employee->MaritalStatus ?? 'N/A',
                'Mobile' => $employee->Mobile ?? 'N/A',
                'Email' => $employee->Email ?? 'N/A',
                'Address' => $employee->Address ?? 'N/A',
                'Bank' => $employee->Bank ?? 'N/A',
                'Ac' => $employee->Ac ?? 'N/A',
                'Pan' => $employee->Pan ?? 'N/A',
                'Aadhar' => $employee->Aadhar ?? 'N/A',
                'Uan' => $employee->Uan ?? 'N/A',
                'Esic' => $employee->Esic ?? 'N/A',
                'Paymentmode' => $employee->Paymentmode ?? 'N/A',
                'Weekoff' => $employee->Weekoff ?? 'N/A',
                'Skill' => $employee->Skill ?? 'N/A',
                'Status' => $employee->Status,
                'actions' => $employee->id, // For actions column
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => Employee::count(),
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'site_id' => 'required|integer|exists:sites,id',
            'department_id' => 'required|integer|exists:departments,id',
            'designation_id' => 'required|integer|exists:designations,id',
            'gang_id' => 'required|integer|exists:gangs,id',
            'EmpId' => 'required|string',
            'Name' => 'required|string',
            'Email' => 'nullable|email',
            'Mobile' => 'nullable|string',
            'Status' => 'required|string',
        ]);

        try {
            DB::beginTransaction();
            
            $data = $request->all();
            
            // Convert ID fields to integers
            $data['site_id'] = (int) $data['site_id'];
            $data['department_id'] = (int) $data['department_id'];
            $data['designation_id'] = (int) $data['designation_id'];
            $data['gang_id'] = (int) $data['gang_id'];
            
            // Handle PF & ESIC Applicable (single dropdown)
            if ($request->has('PfEsicApplicable')) {
                $pfEsicValue = $request->input('PfEsicApplicable') == '1';
                $data['PfApplicable'] = $pfEsicValue;
                $data['EsicApplicable'] = $pfEsicValue;
                unset($data['PfEsicApplicable']); // Remove the temporary field
            }
            
            // Convert checkbox values to boolean
            $booleanFields = ['PRFTax', 'AttendAllow', 'OtAppl', 'MrOtAppl', 'AllowAsPer', 'ReversePF', 'is_employee', 'is_supervisor', 'is_officeStaff'];
            foreach ($booleanFields as $field) {
                $data[$field] = $request->has($field) && ($request->input($field) == '1' || $request->input($field) === true);
            }
            
            $employee = Employee::create($data);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Employee created successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating employee: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $employee = Employee::with(['site', 'department', 'designation', 'gang', 'rate'])->find($id);
        
        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Employee not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $employee]);
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);
        
        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Employee not found'], 404);
        }

        // Validate only fields that are being updated (for partial updates)
        $rules = [];
        if ($request->has('site_id') && $request->site_id != '') {
            $rules['site_id'] = 'required|integer|exists:sites,id';
        }
        if ($request->has('department_id') && $request->department_id != '') {
            $rules['department_id'] = 'required|integer|exists:departments,id';
        }
        if ($request->has('designation_id') && $request->designation_id != '') {
            $rules['designation_id'] = 'required|integer|exists:designations,id';
        }
        if ($request->has('gang_id') && $request->gang_id != '') {
            $rules['gang_id'] = 'required|integer|exists:gangs,id';
        }
        if ($request->has('EmpId')) {
            $rules['EmpId'] = 'required|string|unique:employees,EmpId,' . $id;
        }
        if ($request->has('Name')) {
            $rules['Name'] = 'required|string';
        }
        if ($request->has('Email')) {
            $rules['Email'] = 'nullable|email';
        }
        if ($request->has('Mobile')) {
            $rules['Mobile'] = 'nullable|string';
        }
        if ($request->has('Status')) {
            $rules['Status'] = 'required|string';
        }
        
        if (!empty($rules)) {
            $validated = $request->validate($rules);
        }

        try {
            DB::beginTransaction();
            
            $data = $request->all();
            
            // Convert ID fields to integers
            if (isset($data['site_id']) && $data['site_id'] != '') {
                $data['site_id'] = (int) $data['site_id'];
            }
            if (isset($data['department_id']) && $data['department_id'] != '') {
                $data['department_id'] = (int) $data['department_id'];
            }
            if (isset($data['designation_id']) && $data['designation_id'] != '') {
                $data['designation_id'] = (int) $data['designation_id'];
            }
            if (isset($data['gang_id']) && $data['gang_id'] != '') {
                $data['gang_id'] = (int) $data['gang_id'];
            }
            
            // Handle PF & ESIC Applicable (single dropdown)
            if ($request->has('PfEsicApplicable')) {
                $pfEsicValue = $request->input('PfEsicApplicable') == '1';
                $data['PfApplicable'] = $pfEsicValue;
                $data['EsicApplicable'] = $pfEsicValue;
                unset($data['PfEsicApplicable']); // Remove the temporary field
            }
            
            // Convert checkbox values to boolean
            $booleanFields = ['PRFTax', 'AttendAllow', 'OtAppl', 'MrOtAppl', 'AllowAsPer', 'ReversePF', 'is_employee', 'is_supervisor', 'is_officeStaff'];
            foreach ($booleanFields as $field) {
                if ($request->has($field)) {
                    $data[$field] = $request->input($field) == '1' || $request->input($field) === true;
                }
            }
            
            $employee->update($data);
            
            // Update rate if provided
            if ($request->has('rate')) {
                $rateData = $request->rate;
                
                // If rate data is provided, merge with existing rate to preserve other fields
                $existingRate = Rate::where('employee_id', $employee->id)->first();
                if ($existingRate && !empty($rateData)) {
                    // Merge existing rate with new data
                    $mergedData = array_merge($existingRate->toArray(), $rateData);
                    // Remove non-fillable fields
                    unset($mergedData['id'], $mergedData['employee_id'], $mergedData['created_at'], $mergedData['updated_at']);
                    $rateData = $mergedData;
                }
                
                // Convert empty strings to 0 for numeric fields
                $numericFields = ['ctc', 'basic', 'da', 'arate', 'otrate', 'hra', 'madical', 
                                 'ExgratiaRetention', 'LTARetention', 'LTA', 'CA', 'Fooding', 
                                 'Misc', 'CEA', 'WashingAllowance', 'ProfessionalPursuits', 
                                 'SpecialAllowance', 'IncomeTax', 'personalpay', 'petrol', 
                                 'mobile', 'incentive', 'fixedamt', 'netamt'];
                foreach ($numericFields as $field) {
                    if (isset($rateData[$field]) && $rateData[$field] === '') {
                        $rateData[$field] = 0;
                    }
                }
                
                $rate = Rate::updateOrCreate(
                    ['employee_id' => $employee->id],
                    $rateData
                );
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Employee updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating employee: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $employee = Employee::find($id);
        
        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'Employee not found'], 404);
        }

        try {
            DB::beginTransaction();
            
            // Delete rate first
            if ($employee->rate) {
                $employee->rate->delete();
            }
            
            // Delete employee
            $employee->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Employee deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error deleting employee: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function createSite(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $site = Site::create(['name' => $request->name]);
        $site->refresh(); // Refresh to ensure ID is available
        return response()->json([
            'success' => true, 
            'data' => [
                'id' => (string) $site->id,
                'name' => $site->name
            ]
        ]);
    }
    
    public function createDepartment(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $department = Department::create(['name' => $request->name]);
        $department->refresh(); // Refresh to ensure ID is available
        return response()->json([
            'success' => true, 
            'data' => [
                'id' => (string) $department->id,
                'name' => $department->name
            ]
        ]);
    }
    
    public function createDesignation(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $designation = Designation::create(['name' => $request->name]);
        $designation->refresh(); // Refresh to ensure ID is available
        return response()->json([
            'success' => true, 
            'data' => [
                'id' => (string) $designation->id,
                'name' => $designation->name
            ]
        ]);
    }
    
    public function createGang(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $gang = Gang::create(['name' => $request->name]);
        $gang->refresh(); // Refresh to ensure ID is available
        return response()->json([
            'success' => true, 
            'data' => [
                'id' => (string) $gang->id,
                'name' => $gang->name
            ]
        ]);
    }
    
    public function export(Request $request)
    {
        $format = $request->input('format', 'xlsx'); // Default to xlsx
        
        $query = Employee::with(['site', 'department', 'designation', 'gang', 'rate']);
        
        // Apply filters
        if ($request->has('site_id') && $request->site_id) {
            $query->where('site_id', $request->site_id);
        }
        if ($request->has('department_id') && $request->department_id) {
            $query->where('department_id', $request->department_id);
        }
        if ($request->has('status') && $request->status) {
            $query->where('Status', $request->status);
        }
        
        // Export selected IDs or all
        if ($request->has('ids') && is_array($request->ids) && count($request->ids) > 0) {
            $query->whereIn('id', $request->ids);
        }
        
        $employees = $query->get();
        
        // Helper function to format date
        $formatDate = function($date) {
            if (!$date) return '';
            if ($date instanceof \Carbon\Carbon) {
                return $date->format('d-m-Y H:i A');
            }
            if (is_string($date)) {
                try {
                    return \Carbon\Carbon::parse($date)->format('d-m-Y H:i A');
                } catch (\Exception $e) {
                    return $date;
                }
            }
            return '';
        };
        
        // Helper function to format boolean
        $formatBoolean = function($value) {
            return $value ? 'Yes' : 'No';
        };
        
        if ($format === 'pdf') {
            return $this->exportPDF($employees, $formatDate, $formatBoolean);
        } else {
            return $this->exportXLSX($employees, $formatDate, $formatBoolean);
        }
    }
    
    private function exportXLSX($employees, $formatDate, $formatBoolean)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Headers
        $headers = [
            'Emp ID', 'Name', 'Father', 'Gender', 'Date of Birth', 'Date of Joining', 'Date of Exit',
            'Safety Card Expiry', 'Site', 'Department', 'Designation', 'Gang', 'Marital Status',
            'Mobile', 'Email', 'Address', 'Bank', 'Branch', 'IFSC', 'Account Number', 'PAN', 'Aadhar',
            'UAN', 'ESIC', 'Emp Safety Card', 'Payment Mode', 'Weekoff', 'OT Type', 'OT Slave', 'Skill',
            'Status', 'PF Applicable', 'ESIC Applicable', 'PRF Tax', 'Attendance Allowance', 'OT Applicable',
            'MR OT Applicable', 'Allow As Per', 'Reverse PF', 'Is Employee', 'Is Supervisor', 'Is Office Staff',
            'CTC', 'Basic', 'DA', 'Attendance Rate', 'OT Rate', 'HRA', 'Medical', 'Exgratia Retention',
            'LTA Retention', 'LTA', 'CA', 'Fooding', 'Misc', 'CEA', 'Washing Allowance', 'Professional Pursuits',
            'Special Allowance', 'Income Tax', 'Personal Pay', 'Petrol', 'Mobile', 'Incentive', 'Fixed Amount',
            'Net Amount', 'Created At', 'Updated At'
        ];
        
        // Set headers
        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($column . '1', $header);
            $sheet->getStyle($column . '1')->getFont()->setBold(true);
            $column++;
        }
        
        // Auto-size columns
        foreach (range('A', $column) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Write data
        $row = 2;
        foreach ($employees as $employee) {
            $rate = $employee->rate;
            $column = 'A';
            
            $data = [
                $employee->EmpId ?? '',
                $employee->Name ?? '',
                $employee->Father ?? '',
                $employee->Gender ?? '',
                $formatDate($employee->Dob),
                $formatDate($employee->Doj),
                $formatDate($employee->Doe),
                $formatDate($employee->SafetyCardExpiry),
                $employee->site ? $employee->site->name : '',
                $employee->department ? $employee->department->name : '',
                $employee->designation ? $employee->designation->name : '',
                $employee->gang ? $employee->gang->name : '',
                $employee->MaritalStatus ?? '',
                $employee->Mobile ?? '',
                $employee->Email ?? '',
                $employee->Address ?? '',
                $employee->Bank ?? '',
                $employee->Branch ?? '',
                $employee->Ifsc ?? '',
                $employee->Ac ?? '',
                $employee->Pan ?? '',
                $employee->Aadhar ?? '',
                $employee->Uan ?? '',
                $employee->Esic ?? '',
                $employee->EmpSafetyCard ?? '',
                $employee->Paymentmode ?? '',
                $employee->Weekoff ?? '',
                $employee->Ottype ?? '',
                $employee->Otslave ?? '',
                $employee->Skill ?? '',
                $employee->Status ?? '',
                $formatBoolean($employee->PfApplicable ?? false),
                $formatBoolean($employee->EsicApplicable ?? false),
                $formatBoolean($employee->PRFTax ?? false),
                $formatBoolean($employee->AttendAllow ?? false),
                $formatBoolean($employee->OtAppl ?? false),
                $formatBoolean($employee->MrOtAppl ?? false),
                $formatBoolean($employee->AllowAsPer ?? false),
                $formatBoolean($employee->ReversePF ?? false),
                $formatBoolean($employee->is_employee ?? false),
                $formatBoolean($employee->is_supervisor ?? false),
                $formatBoolean($employee->is_officeStaff ?? false),
                $rate ? ($rate->ctc ?? '') : '',
                $rate ? ($rate->basic ?? '') : '',
                $rate ? ($rate->da ?? '') : '',
                $rate ? ($rate->arate ?? '') : '',
                $rate ? ($rate->otrate ?? '') : '',
                $rate ? ($rate->hra ?? '') : '',
                $rate ? ($rate->madical ?? '') : '',
                $rate ? ($rate->ExgratiaRetention ?? '') : '',
                $rate ? ($rate->LTARetention ?? '') : '',
                $rate ? ($rate->LTA ?? '') : '',
                $rate ? ($rate->CA ?? '') : '',
                $rate ? ($rate->Fooding ?? '') : '',
                $rate ? ($rate->Misc ?? '') : '',
                $rate ? ($rate->CEA ?? '') : '',
                $rate ? ($rate->WashingAllowance ?? '') : '',
                $rate ? ($rate->ProfessionalPursuits ?? '') : '',
                $rate ? ($rate->SpecialAllowance ?? '') : '',
                $rate ? ($rate->IncomeTax ?? '') : '',
                $rate ? ($rate->personalpay ?? '') : '',
                $rate ? ($rate->petrol ?? '') : '',
                $rate ? ($rate->mobile ?? '') : '',
                $rate ? ($rate->incentive ?? '') : '',
                $rate ? ($rate->fixedamt ?? '') : '',
                $rate ? ($rate->netamt ?? '') : '',
                $formatDate($employee->created_at),
                $formatDate($employee->updated_at)
            ];
            
            foreach ($data as $value) {
                $sheet->setCellValue($column . $row, $value);
                $column++;
            }
            $row++;
        }
        
        $filename = 'employees_export_' . date('Y-m-d_His') . '.xlsx';
        
        $writer = new Xlsx($spreadsheet);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }
    
    private function exportPDF($employees, $formatDate, $formatBoolean)
    {
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 15mm; }
        body { font-family: Arial, sans-serif; font-size: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; page-break-inside: auto; }
        th, td { border: 1px solid #ddd; padding: 4px; text-align: left; word-wrap: break-word; }
        th { background-color: #f2f2f2; font-weight: bold; font-size: 7px; }
        tr { page-break-inside: avoid; page-break-after: auto; }
        .header { text-align: center; margin-bottom: 10px; }
        .header h2 { margin: 5px 0; font-size: 14px; }
        .header p { margin: 2px 0; font-size: 9px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Employee Export Report</h2>
        <p>Generated on: ' . date('d-m-Y H:i A') . ' | Total Records: ' . $employees->count() . '</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Emp ID</th>
                <th>Name</th>
                <th>Father</th>
                <th>Gender</th>
                <th>DOB</th>
                <th>DOJ</th>
                <th>DOE</th>
                <th>Site</th>
                <th>Dept</th>
                <th>Designation</th>
                <th>Gang</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>Status</th>
                <th>PF App</th>
                <th>ESIC App</th>
                <th>CTC</th>
                <th>Basic</th>
                <th>DA</th>
                <th>ARate</th>
                <th>OT Rate</th>
            </tr>
        </thead>
        <tbody>';
        
        foreach ($employees as $employee) {
            $rate = $employee->rate;
            $html .= '<tr>
                <td>' . htmlspecialchars($employee->EmpId ?? '') . '</td>
                <td>' . htmlspecialchars($employee->Name ?? '') . '</td>
                <td>' . htmlspecialchars($employee->Father ?? '') . '</td>
                <td>' . htmlspecialchars($employee->Gender ?? '') . '</td>
                <td>' . htmlspecialchars($formatDate($employee->Dob)) . '</td>
                <td>' . htmlspecialchars($formatDate($employee->Doj)) . '</td>
                <td>' . htmlspecialchars($formatDate($employee->Doe)) . '</td>
                <td>' . htmlspecialchars($employee->site ? $employee->site->name : '') . '</td>
                <td>' . htmlspecialchars($employee->department ? $employee->department->name : '') . '</td>
                <td>' . htmlspecialchars($employee->designation ? $employee->designation->name : '') . '</td>
                <td>' . htmlspecialchars($employee->gang ? $employee->gang->name : '') . '</td>
                <td>' . htmlspecialchars($employee->Mobile ?? '') . '</td>
                <td>' . htmlspecialchars($employee->Email ?? '') . '</td>
                <td>' . htmlspecialchars($employee->Status ?? '') . '</td>
                <td>' . htmlspecialchars($formatBoolean($employee->PfApplicable ?? false)) . '</td>
                <td>' . htmlspecialchars($formatBoolean($employee->EsicApplicable ?? false)) . '</td>
                <td>' . htmlspecialchars($rate ? ($rate->ctc ?? '') : '') . '</td>
                <td>' . htmlspecialchars($rate ? ($rate->basic ?? '') : '') . '</td>
                <td>' . htmlspecialchars($rate ? ($rate->da ?? '') : '') . '</td>
                <td>' . htmlspecialchars($rate ? ($rate->arate ?? '') : '') . '</td>
                <td>' . htmlspecialchars($rate ? ($rate->otrate ?? '') : '') . '</td>
            </tr>';
        }
        
        $html .= '</tbody>
    </table>
</body>
</html>';
        
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        $filename = 'employees_export_' . date('Y-m-d_His') . '.pdf';
        
        return $dompdf->stream($filename);
    }
    
    public function exportSample(Request $request)
    {
        $type = $request->input('type', 'employee');
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        if ($type === 'employee') {
            // Employee sample headers
            $headers = [
                'EmpId', 'Name', 'Father', 'Gender', 'Dob', 'Doj', 'Doe', 'SafetyCardExpiry',
                'Site', 'Department', 'Designation', 'Gang', 'MaritalStatus', 'Mobile', 'Email', 'Address',
                'Bank', 'Branch', 'IFSC', 'Ac', 'Pan', 'Aadhar', 'Uan', 'Esic', 'EmpSafetyCard',
                'Paymentmode', 'Weekoff', 'Ottype', 'Otslave', 'Skill', 'Status',
                'PfApplicable', 'EsicApplicable', 'PRFTax', 'AttendAllow', 'OtAppl', 'MrOtAppl',
                'AllowAsPer', 'ReversePF', 'is_employee', 'is_supervisor', 'is_officeStaff'
            ];
            
            $sheet->setTitle('Employee Import Sample');
            
            // Set headers
            $column = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($column . '1', $header);
                $sheet->getStyle($column . '1')->getFont()->setBold(true);
                $column++;
            }
            
            // Add sample row
            $sampleRow = [
                'EMP001', 'John Doe', 'Father Name', 'Male', '01-01-1990', '01-01-2020', '', '31-12-2025',
                'Site Name', 'Department Name', 'Designation Name', 'Gang Name', 'Married', '1234567890', 'john@example.com', 'Address',
                'Bank Name', 'Branch Name', 'IFSC0001', '1234567890', 'ABCDE1234F', '123456789012', '123456789012', '1234567890', 'CARD123',
                'Cash', 'Sunday', 'Normal', 'Slave1', 'Skill1', 'Active',
                '1', '1', '0', '1', '1', '0', '1', '0', '1', '0', '0'
            ];
            
            $column = 'A';
            foreach ($sampleRow as $value) {
                $sheet->setCellValue($column . '2', $value);
                $column++;
            }
            
            $filename = 'employee_import_sample.xlsx';
        } else {
            // Rate sample headers
            $headers = [
                'Aadhar', 'ctc', 'basic', 'da', 'arate', 'otrate', 'hra', 'madical',
                'ExgratiaRetention', 'LTARetention', 'LTA', 'CA', 'Fooding', 'Misc', 'CEA',
                'WashingAllowance', 'ProfessionalPursuits', 'SpecialAllowance', 'IncomeTax',
                'personalpay', 'petrol', 'mobile', 'incentive', 'fixedamt', 'netamt'
            ];
            
            $sheet->setTitle('Rate Import Sample');
            
            // Set headers
            $column = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($column . '1', $header);
                $sheet->getStyle($column . '1')->getFont()->setBold(true);
                $column++;
            }
            
            // Add sample row
            $sampleRow = [
                '123456789012', '50000', '20000', '10000', '500', '750', '5000', '2000',
                '1000', '500', '2000', '1000', '1500', '500', '1000',
                '500', '1000', '2000', '5000', '1000', '2000', '1000', '5000', '10000', '45000'
            ];
            
            $column = 'A';
            foreach ($sampleRow as $value) {
                $sheet->setCellValue($column . '2', $value);
                $column++;
            }
            
            $filename = 'rate_import_sample.xlsx';
        }
        
        // Auto-size columns
        foreach (range('A', $column) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        $writer = new Xlsx($spreadsheet);
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
        exit;
    }
    
    public function importPreview(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240'
        ]);
        
        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            if (count($rows) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'File is empty or has no data rows'
                ], 400);
            }
            
            // Get headers from first row
            $headers = array_filter($rows[0]);
            $headersLower = array_map('strtolower', $headers);
            
            // Skip header row
            $dataRows = array_slice($rows, 1);
            
            $parsedData = [];
            
            foreach ($dataRows as $row) {
                if (empty(array_filter($row))) {
                    continue; // Skip empty rows
                }
                
                $rowData = [];
                foreach ($headersLower as $index => $header) {
                    $rowData[$header] = isset($row[$index]) ? $row[$index] : '';
                }
                $parsedData[] = $rowData;
            }
            
            return response()->json([
                'success' => true,
                'headers' => array_values($headers),
                'data' => $parsedData
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to parse file: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function import(Request $request)
    {
        if ($request->has('data') && is_array($request->data)) {
            // Import from preview data
            $dataRows = $request->data;
        } else {
            // Legacy: import from file
            $request->validate([
                'file' => 'required|mimes:xlsx,xls|max:10240'
            ]);
            
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            if (count($rows) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'File is empty or has no data rows'
                ], 400);
            }
            
            $headers = array_map('strtolower', array_filter($rows[0]));
            $dataRows = array_slice($rows, 1);
            
            // Convert to same format as preview
            $parsedData = [];
            foreach ($dataRows as $row) {
                if (empty(array_filter($row))) continue;
                $rowData = [];
                foreach ($headers as $index => $header) {
                    $rowData[$header] = isset($row[$index]) ? $row[$index] : '';
                }
                $parsedData[] = $rowData;
            }
            $dataRows = $parsedData;
        }
        
        try {
            $imported = 0;
            $errors = [];
            
            DB::beginTransaction();
            
            foreach ($dataRows as $index => $rowData) {
                try {
                    // Map Excel columns to database fields
                    $employeeData = [];
                    
                    // Basic fields
                    if (isset($rowData['empid'])) $employeeData['EmpId'] = $rowData['empid'];
                    if (isset($rowData['name'])) $employeeData['Name'] = $rowData['name'];
                    if (isset($rowData['father'])) $employeeData['Father'] = $rowData['father'];
                    if (isset($rowData['gender'])) $employeeData['Gender'] = $rowData['gender'];
                    if (isset($rowData['dob'])) {
                        try {
                            $employeeData['Dob'] = \Carbon\Carbon::parse($rowData['dob'])->format('Y-m-d');
                        } catch (\Exception $e) {
                            $employeeData['Dob'] = null;
                        }
                    }
                    if (isset($rowData['doj'])) {
                        try {
                            $employeeData['Doj'] = \Carbon\Carbon::parse($rowData['doj'])->format('Y-m-d');
                        } catch (\Exception $e) {
                            $employeeData['Doj'] = null;
                        }
                    }
                    if (isset($rowData['doe'])) {
                        try {
                            $employeeData['Doe'] = \Carbon\Carbon::parse($rowData['doe'])->format('Y-m-d');
                        } catch (\Exception $e) {
                            $employeeData['Doe'] = null;
                        }
                    }
                    
                    // Lookup IDs for Site, Department, Designation, Gang
                    if (isset($rowData['site']) && !empty($rowData['site'])) {
                        $site = Site::where('name', $rowData['site'])->first();
                        if ($site) {
                            $employeeData['site_id'] = $site->id;
                        }
                    }
                    if (isset($rowData['department']) && !empty($rowData['department'])) {
                        $dept = Department::where('name', $rowData['department'])->first();
                        if ($dept) {
                            $employeeData['department_id'] = $dept->id;
                        }
                    }
                    if (isset($rowData['designation']) && !empty($rowData['designation'])) {
                        $desig = Designation::where('name', $rowData['designation'])->first();
                        if ($desig) {
                            $employeeData['designation_id'] = $desig->id;
                        }
                    }
                    if (isset($rowData['gang']) && !empty($rowData['gang'])) {
                        $gang = Gang::where('name', $rowData['gang'])->first();
                        if ($gang) {
                            $employeeData['gang_id'] = $gang->id;
                        }
                    }
                    
                    // Other fields
                    $fieldMap = [
                        'maritalstatus' => 'MaritalStatus',
                        'mobile' => 'Mobile',
                        'email' => 'Email',
                        'address' => 'Address',
                        'bank' => 'Bank',
                        'branch' => 'Branch',
                        'ifsc' => 'Ifsc',
                        'ac' => 'Ac',
                        'pan' => 'Pan',
                        'aadhar' => 'Aadhar',
                        'uan' => 'Uan',
                        'esic' => 'Esic',
                        'empsafetycard' => 'EmpSafetyCard',
                        'paymentmode' => 'Paymentmode',
                        'weekoff' => 'Weekoff',
                        'ottype' => 'Ottype',
                        'otslave' => 'Otslave',
                        'skill' => 'Skill',
                        'status' => 'Status'
                    ];
                    
                    foreach ($fieldMap as $excelKey => $dbKey) {
                        if (isset($rowData[$excelKey])) {
                            $employeeData[$dbKey] = $rowData[$excelKey];
                        }
                    }
                    
                    // Boolean fields
                    $booleanFields = [
                        'pfapplicable' => 'PfApplicable',
                        'esicapplicable' => 'EsicApplicable',
                        'prftax' => 'PRFTax',
                        'attendallow' => 'AttendAllow',
                        'otappl' => 'OtAppl',
                        'mrotappl' => 'MrOtAppl',
                        'allowasper' => 'AllowAsPer',
                        'reversepf' => 'ReversePF',
                        'is_employee' => 'is_employee',
                        'is_supervisor' => 'is_supervisor',
                        'is_officestaff' => 'is_officeStaff'
                    ];
                    
                    foreach ($booleanFields as $excelKey => $dbKey) {
                        if (isset($rowData[$excelKey])) {
                            $value = $rowData[$excelKey];
                            $employeeData[$dbKey] = in_array(strtolower($value), ['1', 'yes', 'true', 'y']);
                        }
                    }
                    
                    // Check if employee exists by EmpId
                    if (isset($employeeData['EmpId'])) {
                        $existing = Employee::where('EmpId', $employeeData['EmpId'])->first();
                        if ($existing) {
                            $existing->update($employeeData);
                        } else {
                            Employee::create($employeeData);
                        }
                        $imported++;
                    }
                } catch (\Exception $e) {
                    $errors[] = 'Row ' . ($index + 1) . ': ' . $e->getMessage();
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "Successfully imported {$imported} employee(s)." . (count($errors) > 0 ? ' Some errors occurred: ' . implode(', ', $errors) : '')
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function importRatePreview(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:10240'
        ]);
        
        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            if (count($rows) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'File is empty or has no data rows'
                ], 400);
            }
            
            // Get headers from first row
            $headers = array_filter($rows[0]);
            $headersLower = array_map('strtolower', $headers);
            
            // Skip header row
            $dataRows = array_slice($rows, 1);
            
            $parsedData = [];
            
            foreach ($dataRows as $row) {
                if (empty(array_filter($row))) {
                    continue; // Skip empty rows
                }
                
                $rowData = [];
                foreach ($headersLower as $index => $header) {
                    $rowData[$header] = isset($row[$index]) ? $row[$index] : '';
                }
                
                // Look up employee name by Aadhar
                if (isset($rowData['aadhar']) && !empty($rowData['aadhar'])) {
                    $employee = Employee::where('Aadhar', $rowData['aadhar'])->first();
                    $rowData['employee_name'] = $employee ? $employee->Name : 'Not Found';
                } else {
                    $rowData['employee_name'] = '';
                }
                
                $parsedData[] = $rowData;
            }
            
            // Add "Employee Name" header after Aadhar if Aadhar exists
            $finalHeaders = array_values($headers);
            $aadharIndex = array_search('Aadhar', $finalHeaders);
            if ($aadharIndex !== false) {
                array_splice($finalHeaders, $aadharIndex + 1, 0, 'Employee Name');
            } else {
                $finalHeaders[] = 'Employee Name';
            }
            
            return response()->json([
                'success' => true,
                'headers' => $finalHeaders,
                'data' => $parsedData
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to parse file: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function importRate(Request $request)
    {
        if ($request->has('data') && is_array($request->data)) {
            // Import from preview data
            $dataRows = $request->data;
        } else {
            // Legacy: import from file
            $request->validate([
                'file' => 'required|mimes:xlsx,xls|max:10240'
            ]);
            
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getRealPath());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();
            
            if (count($rows) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'File is empty or has no data rows'
                ], 400);
            }
            
            $headers = array_map('strtolower', array_filter($rows[0]));
            $dataRows = array_slice($rows, 1);
            
            // Convert to same format as preview
            $parsedData = [];
            foreach ($dataRows as $row) {
                if (empty(array_filter($row))) continue;
                $rowData = [];
                foreach ($headers as $index => $header) {
                    $rowData[$header] = isset($row[$index]) ? $row[$index] : '';
                }
                $parsedData[] = $rowData;
            }
            $dataRows = $parsedData;
        }
        
        try {
            $imported = 0;
            $errors = [];
            
            DB::beginTransaction();
            
            foreach ($dataRows as $index => $rowData) {
                try {
                    if (!isset($rowData['aadhar']) || empty($rowData['aadhar'])) {
                        $errors[] = 'Row ' . ($index + 1) . ': Aadhar is required';
                        continue;
                    }
                    
                    $employee = Employee::where('Aadhar', $rowData['aadhar'])->first();
                    if (!$employee) {
                        $errors[] = 'Row ' . ($index + 1) . ': Employee with Aadhar ' . $rowData['aadhar'] . ' not found';
                        continue;
                    }
                    
                    $rateData = [];
                    
                    $rateFields = [
                        'ctc', 'basic', 'da', 'arate', 'otrate', 'hra', 'madical',
                        'exgratiaretention', 'ltaretention', 'lta', 'ca', 'fooding', 'misc', 'cea',
                        'washingallowance', 'professionalpursuits', 'specialallowance', 'incometax',
                        'personalpay', 'petrol', 'mobile', 'incentive', 'fixedamt', 'netamt'
                    ];
                    
                    foreach ($rateFields as $field) {
                        if (isset($rowData[$field]) && $rowData[$field] !== '') {
                            $rateData[$field] = is_numeric($rowData[$field]) ? $rowData[$field] : 0;
                        }
                    }
                    
                    // Update or create rate
                    $rate = Rate::where('employee_id', $employee->id)->first();
                    if ($rate) {
                        $rate->update($rateData);
                    } else {
                        $rateData['employee_id'] = $employee->id;
                        Rate::create($rateData);
                    }
                    
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = 'Row ' . ($index + 1) . ': ' . $e->getMessage();
                }
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "Successfully imported {$imported} rate(s)." . (count($errors) > 0 ? ' Some errors occurred: ' . implode(', ', $errors) : '')
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
