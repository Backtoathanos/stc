<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Designation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\HasPermissions;
use App\Http\Controllers\Traits\CheckPermissions;

class DesignationController extends Controller
{
    use HasPermissions, CheckPermissions;
    
    public function index()
    {
        $user = auth()->user();
        
        // Check if user has view permission (root user always has access)
        if (!$user || (!$user->hasPermission('master.designations.view') && $user->email !== 'root@stcassociate.com')) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to access this page'
                ], 403);
            }
            return redirect(route('home'))->with('error', 'You do not have permission to access this page');
        }
        
        return view('pages.master.designations', [
            'page_title' => 'Designations',
            'permissions' => $this->getPermissions('master', 'designations')
        ]);
    }

    public function list(Request $request)
    {
        $permissionCheck = $this->checkPermission('master.designations.view', 'view');
        if ($permissionCheck) return $permissionCheck;
        
        $query = Designation::query();

        // Search functionality
        if ($request->has('search') && $request->search['value']) {
            $search = $request->search['value'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // Get total count before pagination
        $totalRecords = $query->count();

        // Ordering
        if ($request->has('order') && is_array($request->order) && count($request->order) > 0) {
            $orderColumn = $request->order[0]['column'] ?? 0;
            $orderDir = $request->order[0]['dir'] ?? 'asc';
            $columns = ['id', 'name'];
            $orderBy = isset($columns[$orderColumn]) ? $columns[$orderColumn] : 'id';
            $query->orderBy($orderBy, $orderDir);
        } else {
            $query->orderBy('id', 'desc');
        }

        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $designations = $query->skip($start)->take($length)->get();

        $data = [];
        foreach ($designations as $designation) {
            $data[] = [
                'id' => $designation->id,
                'name' => $designation->name,
                'created_at' => $designation->created_at ? $designation->created_at->format('Y-m-d H:i:s') : 'N/A',
                'updated_at' => $designation->updated_at ? $designation->updated_at->format('Y-m-d H:i:s') : 'N/A',
                'actions' => $designation->id, // For actions column
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => Designation::count(),
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $permissionCheck = $this->checkPermission('master.designations.edit', 'create');
        if ($permissionCheck) return $permissionCheck;
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:designations,name',
        ]);

        try {
            DB::beginTransaction();
            
            // Get the ID directly from database insert
            $id = DB::table('designations')->insertGetId([
                'name' => $request->name,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            DB::commit();
            
            // Get the created record
            $designation = Designation::find($id);
            
            return response()->json([
                'success' => true,
                'message' => 'Designation created successfully',
                'data' => [
                    'id' => (string) $designation->id,
                    'name' => $designation->name
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating designation: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $permissionCheck = $this->checkPermission('master.designations.view', 'view');
        if ($permissionCheck) return $permissionCheck;
        
        $designation = Designation::find($id);
        
        if (!$designation) {
            return response()->json(['success' => false, 'message' => 'Designation not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $designation]);
    }

    public function update(Request $request, $id)
    {
        $permissionCheck = $this->checkPermission('master.designations.edit', 'update');
        if ($permissionCheck) return $permissionCheck;
        
        $designation = Designation::find($id);
        
        if (!$designation) {
            return response()->json(['success' => false, 'message' => 'Designation not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:designations,name,' . $id . ',id',
        ]);

        try {
            DB::beginTransaction();
            
            $designation->update([
                'name' => $request->name
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Designation updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating designation: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $permissionCheck = $this->checkPermission('master.designations.delete', 'delete');
        if ($permissionCheck) return $permissionCheck;
        
        $designation = Designation::find($id);
        
        if (!$designation) {
            return response()->json(['success' => false, 'message' => 'Designation not found'], 404);
        }

        try {
            DB::beginTransaction();
            
            // Check if designation has employees
            if ($designation->employees()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete designation. It has associated employees.'
                ], 400);
            }
            
            $designation->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Designation deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error deleting designation: ' . $e->getMessage()
            ], 500);
        }
    }
}

