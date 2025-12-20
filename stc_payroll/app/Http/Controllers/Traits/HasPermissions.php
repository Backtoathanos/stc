<?php

namespace App\Http\Controllers\Traits;

trait HasPermissions
{
    protected function getPermissions($section, $page)
    {
        $user = auth()->user();
        return [
            'view' => $user ? $user->hasPermission($section . '.' . $page . '.view') : false,
            'edit' => $user ? $user->hasPermission($section . '.' . $page . '.edit') : false,
            'delete' => $user ? $user->hasPermission($section . '.' . $page . '.delete') : false,
        ];
    }
}

