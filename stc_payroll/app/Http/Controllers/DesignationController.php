<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Designation;
use Illuminate\Support\Facades\DB;

class DesignationController extends Controller
{
    public function index()
    {
        return view('pages.master.designations', [
            'page_title' => 'Designations'
        ]);
    }

    public function list(Request $request)
    {
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
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:designations,name',
        ]);

        try {
            DB::beginTransaction();
            
            $designation = Designation::create([
                'name' => $request->name
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Designation created successfully',
                'data' => $designation
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
        $designation = Designation::find($id);
        
        if (!$designation) {
            return response()->json(['success' => false, 'message' => 'Designation not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $designation]);
    }

    public function update(Request $request, $id)
    {
        $designation = Designation::find($id);
        
        if (!$designation) {
            return response()->json(['success' => false, 'message' => 'Designation not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:designations,name,' . $id,
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

