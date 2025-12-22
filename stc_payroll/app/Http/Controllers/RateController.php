<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SiteRate;
use App\Site;
use Illuminate\Support\Facades\DB;

class RateController extends Controller
{
    public function index()
    {
        return view('pages.settings.rate', [
            'page_title' => 'Rate',
            'sites' => Site::orderBy('name')->get()
        ]);
    }

    public function list(Request $request)
    {
        $query = SiteRate::with('site')
            ->select('site_rates.*', 'sites.name as site_name', 'sites.id as site_id_display')
            ->join('sites', 'site_rates.site_id', '=', 'sites.id');

        // Search
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function($q) use ($search) {
                $q->where('sites.name', 'like', "%{$search}%")
                  ->orWhere('site_rates.category', 'like', "%{$search}%")
                  ->orWhere('site_rates.basic', 'like', "%{$search}%")
                  ->orWhere('site_rates.da', 'like', "%{$search}%");
            });
        }

        // Ordering
        if ($request->has('order') && count($request->order) > 0) {
            $orderColumn = $request->order[0]['column'] ?? 0;
            $orderDir = $request->order[0]['dir'] ?? 'asc';
            $columns = ['site_name', 'category', 'basic', 'da'];
            $orderBy = $columns[$orderColumn] ?? 'site_name';
            
            if ($orderBy === 'site_name') {
                $query->orderBy('sites.name', $orderDir);
            } else {
                $query->orderBy('site_rates.' . $orderBy, $orderDir);
            }
        } else {
            $query->orderBy('sites.name', 'asc');
        }

        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $totalRecords = SiteRate::count();
        $filteredRecords = $query->count();
        
        $rates = $query->skip($start)->take($length)->get();

        $data = [];
        foreach ($rates as $rate) {
            $data[] = [
                'id' => $rate->id,
                'site_name' => $rate->site_name . ' (' . $rate->site_id_display . ')',
                'category' => $rate->category,
                'basic' => number_format($rate->basic, 2),
                'da' => number_format($rate->da, 2),
                'created_at' => $rate->created_at ? $rate->created_at->format('Y-m-d H:i:s') : 'N/A',
                'updated_at' => $rate->updated_at ? $rate->updated_at->format('Y-m-d H:i:s') : 'N/A',
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'site_id' => 'required|exists:sites,id',
            'category' => 'required|string|max:50|in:UN-SKILLED,SEMI-SKILLED,SKILLED,HIGH-SKILLED',
            'basic' => 'required|numeric|min:0',
            'da' => 'required|numeric|min:0',
        ]);

        // Check if rate already exists for this site-category combination
        $existing = SiteRate::where('site_id', $validated['site_id'])
            ->where('category', $validated['category'])
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Rate already exists for this site and category. Please use Edit to update.'
            ], 400);
        }

        try {
            $rate = SiteRate::create($validated);
            return response()->json([
                'success' => true,
                'message' => 'Rate created successfully',
                'data' => $rate
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating rate: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'site_id' => 'required|exists:sites,id',
            'category' => 'required|string|max:50|in:UN-SKILLED,SEMI-SKILLED,SKILLED,HIGH-SKILLED',
            'basic' => 'required|numeric|min:0',
            'da' => 'required|numeric|min:0',
        ]);

        $rate = SiteRate::findOrFail($id);

        // Check if another rate exists for this site-category combination
        $existing = SiteRate::where('site_id', $validated['site_id'])
            ->where('category', $validated['category'])
            ->where('id', '!=', $id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Rate already exists for this site and category.'
            ], 400);
        }

        try {
            $rate->update($validated);
            return response()->json([
                'success' => true,
                'message' => 'Rate updated successfully',
                'data' => $rate
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating rate: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $rate = SiteRate::with('site')->findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $rate
        ]);
    }

    public function destroy($id)
    {
        try {
            $rate = SiteRate::findOrFail($id);
            $rate->delete();
            return response()->json([
                'success' => true,
                'message' => 'Rate deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting rate: ' . $e->getMessage()
            ], 500);
        }
    }

    public function process($id)
    {
        // This method can be used for processing rates (e.g., applying to employees)
        // For now, just return success
        return response()->json([
            'success' => true,
            'message' => 'Rate processed successfully'
        ]);
    }
}

