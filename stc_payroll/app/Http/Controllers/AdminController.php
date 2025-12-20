<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Permission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\HasPermissions;

class AdminController extends Controller
{
    use HasPermissions;
    
    /**
     * Check if user is root user
     */
    private function isRootUser($user)
    {
        return $user->email === 'root@stcassociate.com';
    }

    public function index()
    {
        $user = auth()->user();
        
        // Check if user has view permission (root user always has access)
        if (!$user || (!$user->hasPermission('admin.users.view') && !$this->isRootUser($user))) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to access this page'
                ], 403);
            }
            return redirect('/stc/stc_payroll/')->with('error', 'You do not have permission to access this page');
        }
        
        return view('pages.admin.users', [
            'page_title' => 'User Management',
            'permissions' => $this->getPermissions('admin', 'users')
        ]);
    }

    public function list(Request $request)
    {
        $user = auth()->user();
        
        // Check if user has view permission
        if (!$user || (!$user->hasPermission('admin.users.view') && !$this->isRootUser($user))) {
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => []
            ], 403);
        }
        
        $query = User::query();

        // Search functionality
        if ($request->has('search') && $request->search['value']) {
            $search = $request->search['value'];
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Get total count before pagination
        $totalRecords = $query->count();

        // Ordering
        if ($request->has('order') && is_array($request->order) && count($request->order) > 0) {
            $orderColumn = $request->order[0]['column'] ?? 0;
            $orderDir = $request->order[0]['dir'] ?? 'asc';
            $columns = ['id', 'name', 'email', 'created_at'];
            $orderBy = isset($columns[$orderColumn]) ? $columns[$orderColumn] : 'id';
            $query->orderBy($orderBy, $orderDir);
        } else {
            $query->orderBy('id', 'desc');
        }

        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $users = $query->skip($start)->take($length)->get();

        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'status' => $user->status ?? 'active',
                'created_at' => $user->created_at ? $user->created_at->format('d-m-Y H:i A') : '',
                'is_root' => $this->isRootUser($user),
            ];
        }

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => User::count(),
            'recordsFiltered' => $totalRecords,
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        
        // Check if user has edit permission
        if (!$user || (!$user->hasPermission('admin.users.edit') && !$this->isRootUser($user))) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to create users'
            ], 403);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        try {
            DB::beginTransaction();
            
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'status' => $request->input('status', 'active'),
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error creating user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $currentUser = auth()->user();
        
        // Check if user has view permission
        if (!$currentUser || (!$currentUser->hasPermission('admin.users.view') && !$this->isRootUser($currentUser))) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to view users'
            ], 403);
        }
        
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'status' => $user->status ?? 'active',
                'created_at' => $user->created_at ? $user->created_at->format('d-m-Y H:i A') : '',
                'is_root' => $this->isRootUser($user),
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $currentUser = auth()->user();
        
        // Check if user has edit permission
        if (!$currentUser || (!$currentUser->hasPermission('admin.users.edit') && !$this->isRootUser($currentUser))) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to edit users'
            ], 403);
        }
        
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        // Prevent modification of root user
        if ($this->isRootUser($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Root user cannot be modified'
            ], 403);
        }

        // Prevent users from modifying their own account
        if (auth()->id() == $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot modify your own account'
            ], 403);
        }

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'status' => 'required|in:active,inactive',
        ];

        // Only validate password if provided
        if ($request->has('password') && !empty($request->password)) {
            $rules['password'] = 'required|string|min:6';
        }

        $validated = $request->validate($rules);

        try {
            DB::beginTransaction();
            
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->status = $validated['status'];
            
            if (isset($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }
            
            $user->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $currentUser = auth()->user();
        
        // Check if user has delete permission
        if (!$currentUser || (!$currentUser->hasPermission('admin.users.delete') && !$this->isRootUser($currentUser))) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to delete users'
            ], 403);
        }
        
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        // Prevent deleting root user
        if ($this->isRootUser($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Root user cannot be deleted'
            ], 403);
        }

        // Prevent deleting the currently logged-in user
        if (auth()->id() == $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete your own account'
            ], 400);
        }

        try {
            DB::beginTransaction();
            
            $user->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error deleting user: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleStatus($id)
    {
        $currentUser = auth()->user();
        
        // Check if user has edit permission
        if (!$currentUser || (!$currentUser->hasPermission('admin.users.edit') && !$this->isRootUser($currentUser))) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to change user status'
            ], 403);
        }
        
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        // Prevent modifying root user status
        if ($this->isRootUser($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Root user status cannot be changed'
            ], 403);
        }

        // Prevent users from changing their own status
        if (auth()->id() == $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot change your own status'
            ], 403);
        }

        try {
            DB::beginTransaction();
            
            $user->status = $user->status === 'active' ? 'inactive' : 'active';
            $user->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'User status updated successfully',
                'data' => [
                    'status' => $user->status
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating user status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getUserPermissions($id)
    {
        $currentUser = auth()->user();
        
        // Check if user has edit permission
        if (!$currentUser || (!$currentUser->hasPermission('admin.users.edit') && !$this->isRootUser($currentUser))) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to manage user permissions'
            ], 403);
        }
        
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        // Get all permissions grouped by section and page
        $allPermissions = Permission::orderBy('section')->orderBy('page')->orderBy('operation')->get();
        
        $grouped = [];
        foreach ($allPermissions as $permission) {
            if (!isset($grouped[$permission->section])) {
                $grouped[$permission->section] = [];
            }
            if (!isset($grouped[$permission->section][$permission->page])) {
                $grouped[$permission->section][$permission->page] = [];
            }
            $grouped[$permission->section][$permission->page][] = [
                'id' => $permission->id,
                'operation' => $permission->operation,
                'slug' => $permission->slug,
            ];
        }

        // Get user's assigned permissions
        $userPermissions = $user->permissions()->pluck('permissions.id')->toArray();

        return response()->json([
            'success' => true,
            'data' => [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'permissions' => $grouped,
                'user_permissions' => $userPermissions
            ]
        ]);
    }

    public function savePermissions(Request $request, $id)
    {
        $currentUser = auth()->user();
        
        // Check if user has edit permission
        if (!$currentUser || (!$currentUser->hasPermission('admin.users.edit') && !$this->isRootUser($currentUser))) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have permission to manage user permissions'
            ], 403);
        }
        
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        // Prevent modifying root user permissions
        if ($this->isRootUser($user)) {
            return response()->json([
                'success' => false,
                'message' => 'Root user permissions cannot be modified'
            ], 403);
        }

        // Prevent users from modifying their own permissions
        if (auth()->id() == $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot modify your own permissions'
            ], 403);
        }

        try {
            DB::beginTransaction();
            
            $permissionIds = $request->input('permissions', []);
            
            // Sync user permissions
            $user->permissions()->sync($permissionIds);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Permissions updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating permissions: ' . $e->getMessage()
            ], 500);
        }
    }
}

