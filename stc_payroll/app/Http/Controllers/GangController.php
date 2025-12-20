<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gang;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\HasPermissions;
use App\Http\Controllers\Traits\CheckPermissions;

class GangController extends Controller
{
    use HasPermissions, CheckPermissions;
    
    public function index()
    {
        $user = auth()->user();
        
        // Check if user has view permission (root user always has access)
        if (!$user || (!$user->hasPermission('master.gangs.view') && $user->email !== 'root@stcassociate.com')) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to access this page'
                ], 403);
            }
            return redirect('/stc/stc_payroll/')->with('error', 'You do not have permission to access this page');
        }
        
        return view('pages.master.gangs', [
            'page_title' => 'Gangs',
            'permissions' => $this->getPermissions('master', 'gangs')
        ]);
    }

    public function list(Request $request)
    {
        $permissionCheck = $this->checkPermission('master.gangs.view', 'view');
        if ($permissionCheck) return $permissionCheck;
        
        $query = Gang::query();

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
        $gangs = $query->skip($start)->take($length)->get();

        $data = [];
        foreach ($gangs as $gang) {
            $data[] = [
                'id' => $gang->id,
                'name' => $gang->name,
                'created_at' => $gang->created_at ? $gang->created_at->format('Y-m-d H:i:s') : 'N/A',
                'updated_at' => $gang->updated_at ? $gang->updated_at->format('Y-m-d H:i:s') : 'N/A',
                'actions' => $gang->id, // For actions column
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => Gang::count(),
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $permissionCheck = $this->checkPermission('master.gangs.edit', 'create');
        if ($permissionCheck) return $permissionCheck;
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:gangs,name',
        ]);

        try {
            DB::beginTransaction();
            
            // Get the ID directly from database insert
            $id = DB::table('gangs')->insertGetId([
                'name' => $request->name,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            DB::commit();
            
            // Get the created record
            $gang = Gang::find($id);
            
            return response()->json([
                'success' => true,
                'message' => 'Gang created successfully',
                'data' => [
                    'id' => (string) $gang->id,
                    'name' => $gang->name
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating gang: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $permissionCheck = $this->checkPermission('master.gangs.view', 'view');
        if ($permissionCheck) return $permissionCheck;
        
        $gang = Gang::find($id);
        
        if (!$gang) {
            return response()->json(['success' => false, 'message' => 'Gang not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $gang]);
    }

    public function update(Request $request, $id)
    {
        $permissionCheck = $this->checkPermission('master.gangs.edit', 'update');
        if ($permissionCheck) return $permissionCheck;
        
        $gang = Gang::find($id);
        
        if (!$gang) {
            return response()->json(['success' => false, 'message' => 'Gang not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:gangs,name,' . $id . ',id',
        ]);

        try {
            DB::beginTransaction();
            
            $gang->update([
                'name' => $request->name
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Gang updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating gang: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $gang = Gang::find($id);
        
        if (!$gang) {
            return response()->json(['success' => false, 'message' => 'Gang not found'], 404);
        }

        try {
            DB::beginTransaction();
            
            // Check if gang has employees
            if ($gang->employees()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete gang. It has associated employees.'
                ], 400);
            }
            
            $gang->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Gang deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error deleting gang: ' . $e->getMessage()
            ], 500);
        }
    }
}

