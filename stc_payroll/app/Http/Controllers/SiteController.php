<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Site;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\HasPermissions;
use App\Http\Controllers\Traits\CheckPermissions;

class SiteController extends Controller
{
    use HasPermissions, CheckPermissions;
    
    public function index()
    {
        $user = auth()->user();
        
        // Check if user has view permission (root user always has access)
        if (!$user || (!$user->hasPermission('master.sites.view') && $user->email !== 'root@stcassociate.com')) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to access this page'
                ], 403);
            }
            return redirect('/stc/stc_payroll/')->with('error', 'You do not have permission to access this page');
        }
        
        return view('pages.master.sites', [
            'page_title' => 'Sites',
            'permissions' => $this->getPermissions('master', 'sites')
        ]);
    }

    public function list(Request $request)
    {
        $permissionCheck = $this->checkPermission('master.sites.view', 'view');
        if ($permissionCheck) return $permissionCheck;
        
        $query = Site::query();

        // Search functionality
        if ($request->has('search') && $request->search['value']) {
            $search = $request->search['value'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhere('under_contract', 'like', "%{$search}%")
                  ->orWhere('natureofwork', 'like', "%{$search}%")
                  ->orWhere('workorderno', 'like', "%{$search}%");
            });
        }

        // Get total count before pagination
        $totalRecords = $query->count();

        // Ordering
        if ($request->has('order') && is_array($request->order) && count($request->order) > 0) {
            $orderColumn = $request->order[0]['column'] ?? 0;
            $orderDir = $request->order[0]['dir'] ?? 'asc';
            $columns = ['id', 'name', 'under_contract', 'natureofwork', 'workorderno'];
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
                'under_contract' => $site->under_contract ?? '',
                'natureofwork' => $site->natureofwork ?? '',
                'workorderno' => $site->workorderno ?? '',
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
        $permissionCheck = $this->checkPermission('master.sites.edit', 'create');
        if ($permissionCheck) return $permissionCheck;
        
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:sites,name',
        ]);

        try {
            DB::beginTransaction();
            
            // Get the ID directly from database insert
            $id = DB::table('sites')->insertGetId([
                'name' => $request->name,
                'under_contract' => $request->under_contract ?? null,
                'natureofwork' => $request->natureofwork ?? null,
                'workorderno' => $request->workorderno ?? null,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            DB::commit();
            
            // Get the created record
            $site = Site::find($id);
            
            return response()->json([
                'success' => true,
                'message' => 'Site created successfully',
                'data' => [
                    'id' => (string) $site->id,
                    'name' => $site->name
                ]
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
        $permissionCheck = $this->checkPermission('master.sites.view', 'view');
        if ($permissionCheck) return $permissionCheck;
        
        $site = Site::find($id);
        
        if (!$site) {
            return response()->json(['success' => false, 'message' => 'Site not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $site]);
    }

    public function update(Request $request, $id)
    {
        $permissionCheck = $this->checkPermission('master.sites.edit', 'update');
        if ($permissionCheck) return $permissionCheck;
        
        $site = Site::find($id);
        
        if (!$site) {
            return response()->json(['success' => false, 'message' => 'Site not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:sites,name,' . $id . ',id',
        ]);

        try {
            DB::beginTransaction();
            
            $site->update([
                'name' => $request->name,
                'under_contract' => $request->under_contract ?? null,
                'natureofwork' => $request->natureofwork ?? null,
                'workorderno' => $request->workorderno ?? null
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
        $permissionCheck = $this->checkPermission('master.sites.delete', 'delete');
        if ($permissionCheck) return $permissionCheck;
        
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
    
    public function getUnderContracts(Request $request)
    {
        $siteId = $request->input('site_id');
        
        // Get all distinct under_contract values from all sites
        $query = Site::whereNotNull('under_contract')
            ->where('under_contract', '!=', '');
        
        // If a specific site is selected, also include that site's values
        if ($siteId && $siteId !== 'all') {
            // Get all distinct values, but prioritize the selected site
            $underContracts = Site::whereNotNull('under_contract')
                ->where('under_contract', '!=', '')
                ->select('under_contract')
                ->distinct()
                ->pluck('under_contract')
                ->filter()
                ->unique()
                ->values();
        } else {
            // Get all distinct values from all sites
            $underContracts = Site::whereNotNull('under_contract')
                ->where('under_contract', '!=', '')
                ->select('under_contract')
                ->distinct()
                ->pluck('under_contract')
                ->filter()
                ->unique()
                ->values();
        }
        
        return response()->json([
            'success' => true,
            'data' => $underContracts
        ]);
    }
    
    public function getNatureOfWork(Request $request)
    {
        $siteId = $request->input('site_id');
        $underContract = $request->input('under_contract');
        
        if (!$siteId || $siteId === 'all') {
            return response()->json([
                'success' => true,
                'data' => []
            ]);
        }
        
        $query = Site::where('id', $siteId)
            ->whereNotNull('natureofwork')
            ->where('natureofwork', '!=', '');
        
        if ($underContract) {
            $query->where('under_contract', $underContract);
        }
        
        // Get distinct natureofwork values that match the criteria
        $natureOfWork = $query->select('natureofwork')
            ->distinct()
            ->pluck('natureofwork')
            ->filter()
            ->unique()
            ->values();
        
        return response()->json([
            'success' => true,
            'data' => $natureOfWork
        ]);
    }
    
    public function getWorkOrderNo(Request $request)
    {
        $siteId = $request->input('site_id');
        $underContract = $request->input('under_contract');
        $natureOfWork = $request->input('natureofwork');
        
        if (!$siteId || $siteId === 'all') {
            return response()->json([
                'success' => true,
                'data' => ''
            ]);
        }
        
        $query = Site::where('id', $siteId);
        
        if ($underContract) {
            $query->where('under_contract', $underContract);
        }
        
        if ($natureOfWork) {
            $query->where('natureofwork', $natureOfWork);
        }
        
        $site = $query->first();
        
        return response()->json([
            'success' => true,
            'data' => $site ? ($site->workorderno ?? '') : ''
        ]);
    }
    
    public function updateContractorDetails(Request $request)
    {
        $permissionCheck = $this->checkPermission('master.sites.edit', 'update');
        if ($permissionCheck) return $permissionCheck;
        
        $siteId = $request->input('site_id');
        $underContract = $request->input('under_contract');
        $natureOfWork = $request->input('natureofwork');
        $workOrderNo = $request->input('workorderno');
        
        if (!$siteId || $siteId === 'all') {
            return response()->json([
                'success' => false,
                'message' => 'Please select a site'
            ], 400);
        }
        
        $site = Site::find($siteId);
        
        if (!$site) {
            return response()->json([
                'success' => false,
                'message' => 'Site not found'
            ], 404);
        }
        
        try {
            DB::beginTransaction();
            
            $updateData = [];
            if ($underContract !== null) {
                $updateData['under_contract'] = $underContract;
            }
            if ($natureOfWork !== null) {
                $updateData['natureofwork'] = $natureOfWork;
            }
            if ($workOrderNo !== null) {
                $updateData['workorderno'] = $workOrderNo;
            }
            
            if (!empty($updateData)) {
                $site->update($updateData);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Contractor details updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating contractor details: ' . $e->getMessage()
            ], 500);
        }
    }
}

