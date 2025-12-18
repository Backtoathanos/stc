<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Department;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    public function index()
    {
        return view('pages.master.departments', [
            'page_title' => 'Departments'
        ]);
    }

    public function list(Request $request)
    {
        $query = Department::query();

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
        $departments = $query->skip($start)->take($length)->get();

        $data = [];
        foreach ($departments as $department) {
            $data[] = [
                'id' => $department->id,
                'name' => $department->name,
                'created_at' => $department->created_at ? $department->created_at->format('Y-m-d H:i:s') : 'N/A',
                'updated_at' => $department->updated_at ? $department->updated_at->format('Y-m-d H:i:s') : 'N/A',
                'actions' => $department->id, // For actions column
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => Department::count(),
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
        ]);

        try {
            DB::beginTransaction();
            
            $department = Department::create([
                'name' => $request->name
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Department created successfully',
                'data' => $department
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating department: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $department = Department::find($id);
        
        if (!$department) {
            return response()->json(['success' => false, 'message' => 'Department not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $department]);
    }

    public function update(Request $request, $id)
    {
        $department = Department::find($id);
        
        if (!$department) {
            return response()->json(['success' => false, 'message' => 'Department not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $id,
        ]);

        try {
            DB::beginTransaction();
            
            $department->update([
                'name' => $request->name
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Department updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating department: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $department = Department::find($id);
        
        if (!$department) {
            return response()->json(['success' => false, 'message' => 'Department not found'], 404);
        }

        try {
            DB::beginTransaction();
            
            // Check if department has employees
            if ($department->employees()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete department. It has associated employees.'
                ], 400);
            }
            
            $department->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Department deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error deleting department: ' . $e->getMessage()
            ], 500);
        }
    }
}

