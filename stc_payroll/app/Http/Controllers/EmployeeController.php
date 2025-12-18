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

class EmployeeController extends Controller
{
    public function index()
    {
        return view('pages.master.employees', [
            'page_title' => 'Employees',
            'sites' => Site::all(),
            'departments' => Department::all(),
            'designations' => Designation::all(),
            'gangs' => Gang::all()
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
            
            // Convert checkbox values to boolean
            $booleanFields = ['PfApplicable', 'EsicApplicable', 'PRFTax', 'AttendAllow', 'OtAppl', 'MrOtAppl', 'AllowAsPer', 'ReversePF', 'is_employee', 'is_supervisor', 'is_officeStaff'];
            foreach ($booleanFields as $field) {
                $data[$field] = $request->has($field) && $request->input($field) == '1';
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
            
            // Convert checkbox values to boolean
            $booleanFields = ['PfApplicable', 'EsicApplicable', 'PRFTax', 'AttendAllow', 'OtAppl', 'MrOtAppl', 'AllowAsPer', 'ReversePF', 'is_employee', 'is_supervisor', 'is_officeStaff'];
            foreach ($booleanFields as $field) {
                $data[$field] = $request->has($field) && ($request->input($field) == '1' || $request->input($field) === true);
            }
            
            $employee->update($data);
            
            // Update rate if provided
            if ($request->has('rate')) {
                $rate = Rate::updateOrCreate(
                    ['employee_id' => $employee->id],
                    $request->rate
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
}
