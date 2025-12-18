<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Gang;
use Illuminate\Support\Facades\DB;

class GangController extends Controller
{
    public function index()
    {
        return view('pages.master.gangs', [
            'page_title' => 'Gangs'
        ]);
    }

    public function list(Request $request)
    {
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
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:gangs,name',
        ]);

        try {
            DB::beginTransaction();
            
            $gang = Gang::create([
                'name' => $request->name
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Gang created successfully',
                'data' => $gang
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
        $gang = Gang::find($id);
        
        if (!$gang) {
            return response()->json(['success' => false, 'message' => 'Gang not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $gang]);
    }

    public function update(Request $request, $id)
    {
        $gang = Gang::find($id);
        
        if (!$gang) {
            return response()->json(['success' => false, 'message' => 'Gang not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:gangs,name,' . $id,
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

