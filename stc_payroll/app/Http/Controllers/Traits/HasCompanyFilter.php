<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\Session;

trait HasCompanyFilter
{
    /**
     * Get the selected company ID from session
     * Returns null if no company is selected
     */
    protected function getSelectedCompanyId()
    {
        return Session::get('selected_company_id');
    }
    
    /**
     * Check if a company is selected, redirect if not
     */
    protected function requireCompanySelection()
    {
        $companyId = $this->getSelectedCompanyId();
        
        if (!$companyId) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please select a company first',
                    'redirect' => route('home')
                ], 400);
            }
            return redirect(route('home'))->with('error', 'Please select a company first');
        }
        
        return null;
    }
    
    /**
     * Apply company filter to a query builder
     */
    protected function applyCompanyFilter($query, $companyIdColumn = 'company_id')
    {
        $companyId = $this->getSelectedCompanyId();
        
        if ($companyId) {
            $query->where($companyIdColumn, $companyId);
        }
        
        return $query;
    }
}

