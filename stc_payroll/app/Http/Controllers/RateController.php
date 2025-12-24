<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SiteRate;
use App\Site;
use App\Employee;
use App\Rate;
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
        // Query from employees -> rates -> sites
        // Group by site, category, basic, and da to show unique combinations
        $query = DB::table('employees')
            ->join('rates', 'employees.id', '=', 'rates.employee_id')
            ->join('sites', 'employees.site_id', '=', 'sites.id')
            ->select(
                'sites.id as site_id',
                'sites.name as site_name',
                'employees.Skill as category',
                'rates.basic',
                'rates.da',
                DB::raw('MIN(rates.id) as rate_id') // Get first rate_id for edit/delete
            )
            ->whereNotNull('employees.Skill')
            ->where('employees.Skill', '!=', '')
            ->whereNotNull('rates.basic')
            ->groupBy('sites.id', 'sites.name', 'employees.Skill', 'rates.basic', 'rates.da');

        // Search
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $query->where(function($q) use ($search) {
                $q->where('sites.name', 'like', "%{$search}%")
                  ->orWhere('employees.Skill', 'like', "%{$search}%")
                  ->orWhere('rates.basic', 'like', "%{$search}%")
                  ->orWhere('rates.da', 'like', "%{$search}%");
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
            } elseif ($orderBy === 'category') {
                $query->orderBy('employees.Skill', $orderDir);
            } elseif ($orderBy === 'basic') {
                $query->orderBy('rates.basic', $orderDir);
            } elseif ($orderBy === 'da') {
                $query->orderBy('rates.da', $orderDir);
            }
        } else {
            $query->orderBy('sites.name', 'asc')
                  ->orderBy('employees.Skill', 'asc')
                  ->orderBy('rates.basic', 'asc')
                  ->orderBy('rates.da', 'asc');
        }

        // Get total records count (before filtering) - count distinct combinations
        $totalRecordsBaseQuery = DB::table('employees')
            ->join('rates', 'employees.id', '=', 'rates.employee_id')
            ->join('sites', 'employees.site_id', '=', 'sites.id')
            ->select(
                'sites.id',
                'sites.name',
                'employees.Skill',
                'rates.basic',
                'rates.da'
            )
            ->whereNotNull('employees.Skill')
            ->where('employees.Skill', '!=', '')
            ->whereNotNull('rates.basic')
            ->groupBy('sites.id', 'sites.name', 'employees.Skill', 'rates.basic', 'rates.da');
        
        $totalRecords = DB::table(DB::raw("({$totalRecordsBaseQuery->toSql()}) as sub"))
            ->mergeBindings($totalRecordsBaseQuery)
            ->count();

        // Get filtered records count - use same grouping logic
        $filteredQuery = DB::table('employees')
            ->join('rates', 'employees.id', '=', 'rates.employee_id')
            ->join('sites', 'employees.site_id', '=', 'sites.id')
            ->select(
                'sites.id',
                'sites.name',
                'employees.Skill',
                'rates.basic',
                'rates.da'
            )
            ->whereNotNull('employees.Skill')
            ->where('employees.Skill', '!=', '')
            ->whereNotNull('rates.basic')
            ->groupBy('sites.id', 'sites.name', 'employees.Skill', 'rates.basic', 'rates.da');
        
        // Apply search filter if exists
        if ($request->has('search') && !empty($request->search['value'])) {
            $search = $request->search['value'];
            $filteredQuery->where(function($q) use ($search) {
                $q->where('sites.name', 'like', "%{$search}%")
                  ->orWhere('employees.Skill', 'like', "%{$search}%")
                  ->orWhere('rates.basic', 'like', "%{$search}%")
                  ->orWhere('rates.da', 'like', "%{$search}%");
            });
        }
        
        $filteredRecords = DB::table(DB::raw("({$filteredQuery->toSql()}) as sub"))
            ->mergeBindings($filteredQuery)
            ->count();

        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $rates = $query->skip($start)->take($length)->get();

        $data = [];
        foreach ($rates as $rate) {
            $data[] = [
                'id' => $rate->rate_id, // Use rate_id for edit/delete operations
                'site_name' => $rate->site_name,
                'category' => $rate->category ? strtoupper($rate->category) : '',
                'basic' => number_format($rate->basic, 2),
                'da' => number_format($rate->da ?? 0, 2),
                'site_id' => $rate->site_id
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
            'basic' => 'required|numeric|min:0',
            'da' => 'required|numeric|min:0',
        ]);

        try {
            $rate = Rate::findOrFail($id);
            $rate->update([
                'basic' => $validated['basic'],
                'da' => $validated['da']
            ]);
            
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
        // Get rate with employee and site info
        $rate = Rate::with(['employee.site'])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $rate->id,
                'site_id' => $rate->employee->site_id ?? null,
                'category' => $rate->employee->Skill ?? '',
                'basic' => $rate->basic,
                'da' => $rate->da,
                'employee_id' => $rate->employee_id
            ]
        ]);
    }

    public function destroy($id)
    {
        try {
            $rate = Rate::findOrFail($id);
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

