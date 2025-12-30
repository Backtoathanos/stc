<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CompanySelectionController extends Controller
{
    /**
     * Set the selected company in session
     */
    public function setCompany(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id'
        ]);
        
        $user = Auth::user();
        $company = Company::find($request->company_id);
        
        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Company not found'
            ], 404);
        }
        
        // Check if user has access to this company
        if ($user->email !== 'root@stcassociate.com') {
            // Check if user's company_id matches or user has permission
            if ($user->company_id != $company->id) {
                // Check if user has access via user-company relationship
                $hasAccess = $company->users()->where('users.id', $user->id)->exists();
                if (!$hasAccess) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You do not have access to this company'
                    ], 403);
                }
            }
        }
        
        // Set company in session
        Session::put('selected_company_id', $company->id);
        Session::put('selected_company_name', $company->name);
        
        return response()->json([
            'success' => true,
            'message' => 'Company selected successfully',
            'data' => [
                'id' => $company->id,
                'name' => $company->name
            ]
        ]);
    }
    
    /**
     * Get the currently selected company
     */
    public function getSelectedCompany()
    {
        $companyId = Session::get('selected_company_id');
        
        if (!$companyId) {
            return response()->json([
                'success' => false,
                'message' => 'No company selected'
            ], 404);
        }
        
        $company = Company::find($companyId);
        
        if (!$company) {
            Session::forget('selected_company_id');
            Session::forget('selected_company_name');
            return response()->json([
                'success' => false,
                'message' => 'Selected company not found'
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $company->id,
                'name' => $company->name,
                'code' => $company->code
            ]
        ]);
    }
    
    /**
     * Clear selected company from session
     */
    public function clearCompany()
    {
        Session::forget('selected_company_id');
        Session::forget('selected_company_name');
        
        return response()->json([
            'success' => true,
            'message' => 'Company selection cleared'
        ]);
    }
}

