<?php

namespace App\Http\Controllers\Traits;

trait CheckPermissions
{
    /**
     * Check if user has permission for a specific operation
     */
    protected function checkPermission($permissionSlug, $operation = 'view')
    {
        $user = auth()->user();
        $isRoot = $user && $user->email === 'root@stcassociate.com';
        
        if (!$user || (!$user->hasPermission($permissionSlug) && !$isRoot)) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have permission to ' . $operation . ' this resource'
                ], 403);
            }
            return redirect(route('home'))->with('error', 'You do not have permission to ' . $operation . ' this resource');
        }
        
        return null; // Permission granted
    }
}


