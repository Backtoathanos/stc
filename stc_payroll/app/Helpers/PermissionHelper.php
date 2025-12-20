<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    /**
     * Check if current user has permission
     */
    public static function hasPermission($slug)
    {
        if (!Auth::check()) {
            return false;
        }
        
        $user = Auth::user();
        return $user->hasPermission($slug);
    }
}

