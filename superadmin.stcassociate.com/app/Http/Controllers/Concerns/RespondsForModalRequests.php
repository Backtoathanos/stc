<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\Request;

trait RespondsForModalRequests
{
    protected function modalSuccess(Request $request, string $redirectPath, string $message)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return redirect($redirectPath)->with('success', $message);
    }
}
