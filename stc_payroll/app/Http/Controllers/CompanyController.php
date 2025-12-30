<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\HasPermissions;
use App\Http\Controllers\Traits\CheckPermissions;

class CompanyController extends Controller
{
    use HasPermissions, CheckPermissions;
    
    public function index()
    {
        $user = auth()->user();
        
        // Check if user has view permission (root user always has access)
        if (!$user || (!$user->hasPermission('master.companies.view') && $user->email !== 'root@stcassociate.com')) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to access this page'
                ], 403);
            }
            return redirect(route('home'))->with('error', 'You do not have permission to access this page');
        }
        
        return view('pages.master.companies', [
            'page_title' => 'Companies',
            'permissions' => $this->getPermissions('master', 'companies')
        ]);
    }

    public function list(Request $request)
    {
        $permissionCheck = $this->checkPermission('master.companies.view', 'view');
        if ($permissionCheck) return $permissionCheck;
        
        $query = Company::query();

        // Search functionality
        if ($request->has('search') && $request->search['value']) {
            $search = $request->search['value'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Get total count before pagination
        $totalRecords = $query->count();

        // Ordering
        if ($request->has('order') && is_array($request->order) && count($request->order) > 0) {
            $orderColumn = $request->order[0]['column'] ?? 0;
            $orderDir = $request->order[0]['dir'] ?? 'asc';
            $columns = ['id', 'name', 'code', 'email', 'phone', 'status'];
            $orderBy = isset($columns[$orderColumn]) ? $columns[$orderColumn] : 'id';
            $query->orderBy($orderBy, $orderDir);
        } else {
            $query->orderBy('id', 'desc');
        }

        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $companies = $query->skip($start)->take($length)->get();

        $data = [];
        foreach ($companies as $company) {
            $data[] = [
                'id' => $company->id,
                'name' => $company->name,
                'code' => $company->code ?? '',
                'email' => $company->email ?? '',
                'phone' => $company->phone ?? '',
                'address' => $company->address ?? '',
                'status' => $company->status,
                'created_at' => $company->created_at ? $company->created_at->format('Y-m-d H:i:s') : 'N/A',
                'updated_at' => $company->updated_at ? $company->updated_at->format('Y-m-d H:i:s') : 'N/A',
                'actions' => $company->id,
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => Company::count(),
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $permissionCheck = $this->checkPermission('master.companies.edit', 'create');
        if ($permissionCheck) return $permissionCheck;
        
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:companies,code',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'status' => 'required|in:ACTIVE,INACTIVE'
        ]);

        try {
            DB::beginTransaction();
            
            $company = Company::create([
                'name' => $request->name,
                'code' => $request->code,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'status' => $request->status ?? 'ACTIVE'
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Company created successfully',
                'data' => $company
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating company: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $permissionCheck = $this->checkPermission('master.companies.view', 'view');
        if ($permissionCheck) return $permissionCheck;
        
        $company = Company::find($id);
        
        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Company not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $company
        ]);
    }

    public function update(Request $request, $id)
    {
        $permissionCheck = $this->checkPermission('master.companies.edit', 'update');
        if ($permissionCheck) return $permissionCheck;
        
        $company = Company::find($id);
        
        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Company not found'
            ], 404);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:companies,code,' . $id,
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'status' => 'required|in:ACTIVE,INACTIVE'
        ]);

        try {
            DB::beginTransaction();
            
            $company->update([
                'name' => $request->name,
                'code' => $request->code,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'status' => $request->status
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Company updated successfully',
                'data' => $company
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating company: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $permissionCheck = $this->checkPermission('master.companies.edit', 'delete');
        if ($permissionCheck) return $permissionCheck;
        
        $company = Company::find($id);
        
        if (!$company) {
            return response()->json(['success' => false, 'message' => 'Company not found'], 404);
        }

        try {
            DB::beginTransaction();
            
            // Check if company has employees or other data
            $hasEmployees = $company->employees()->count() > 0;
            $hasUsers = $company->users()->count() > 0;
            
            if ($hasEmployees || $hasUsers) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete company. It has associated employees or users. Please remove them first.'
                ], 400);
            }
            
            $company->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Company deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error deleting company: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all companies for dropdown/select
     */
    public function getAll()
    {
        $companies = Company::where('status', 'ACTIVE')->orderBy('name')->get();
        return response()->json([
            'success' => true,
            'data' => $companies
        ]);
    }

    /**
     * Get companies accessible by current user
     */
    public function getUserCompanies()
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }
        
        // Root user can access all companies
        if ($user->email === 'root@stcassociate.com') {
            $companies = Company::where('status', 'ACTIVE')->orderBy('name')->get();
        } else {
            // Get companies where user has access (via company_id in users table)
            $companies = Company::where('status', 'ACTIVE')
                ->where(function($q) use ($user) {
                    $q->whereHas('users', function($subQ) use ($user) {
                        $subQ->where('users.id', $user->id);
                    })
                    ->orWhere('id', $user->company_id);
                })
                ->orderBy('name')
                ->get();
        }
        
        return response()->json([
            'success' => true,
            'data' => $companies
        ]);
    }
}
