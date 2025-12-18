<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Site;
use Illuminate\Support\Facades\DB;

class SiteController extends Controller
{
    public function index()
    {
        return view('pages.master.sites', [
            'page_title' => 'Sites'
        ]);
    }

    public function list(Request $request)
    {
        $query = Site::query();

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
        $sites = $query->skip($start)->take($length)->get();

        $data = [];
        foreach ($sites as $site) {
            $data[] = [
                'id' => $site->id,
                'name' => $site->name,
                'created_at' => $site->created_at ? $site->created_at->format('Y-m-d H:i:s') : 'N/A',
                'updated_at' => $site->updated_at ? $site->updated_at->format('Y-m-d H:i:s') : 'N/A',
                'actions' => $site->id, // For actions column
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => Site::count(),
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:sites,name',
        ]);

        try {
            DB::beginTransaction();
            
            $site = Site::create([
                'name' => $request->name
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Site created successfully',
                'data' => $site
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating site: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $site = Site::find($id);
        
        if (!$site) {
            return response()->json(['success' => false, 'message' => 'Site not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $site]);
    }

    public function update(Request $request, $id)
    {
        $site = Site::find($id);
        
        if (!$site) {
            return response()->json(['success' => false, 'message' => 'Site not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:sites,name,' . $id,
        ]);

        try {
            DB::beginTransaction();
            
            $site->update([
                'name' => $request->name
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Site updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating site: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $site = Site::find($id);
        
        if (!$site) {
            return response()->json(['success' => false, 'message' => 'Site not found'], 404);
        }

        try {
            DB::beginTransaction();
            
            // Check if site has employees
            if ($site->employees()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete site. It has associated employees.'
                ], 400);
            }
            
            $site->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Site deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error deleting site: ' . $e->getMessage()
            ], 500);
        }
    }
}

