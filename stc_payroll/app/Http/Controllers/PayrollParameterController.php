<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PayrollParameter;
use App\Company;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\HasCompanyFilter;

class PayrollParameterController extends Controller
{
    use HasCompanyFilter;
    public function index()
    {
        // Get selected company ID
        $companyId = $this->getSelectedCompanyId();
        
        if (!$companyId) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please select a company first'
                ], 400);
            }
            return redirect(route('home'))->with('error', 'Please select a company first');
        }
        
        // Get payroll parameter for the selected company
        $parameter = PayrollParameter::where('company_id', $companyId)->first();
        
        if (!$parameter) {
            // Create default parameter for this company
            $parameter = PayrollParameter::create([
                'company_id' => $companyId,
                'pf_percentage' => 12.00,
                'ppf_percentage' => 3.67,
                'ac_no_2_pf_percentage' => 0.85,
                'ac_22_pf_percentage' => 0.01,
                'epf_percentage' => 8.33,
                'if_percentage' => 0.50,
                'ac_21_pf_percentage' => 0.50,
                'esic_employee_percentage' => 3.25,
                'esic_employer_percentage' => 0.75,
                'esic_limit' => 21000.00,
                'pf_limit' => 15000.00,
                'total_days' => 30,
                'holiday_percentage' => 1.00,
                'current_month' => now()->startOfMonth(),
                'previous_month' => now()->subMonth()->startOfMonth(),
            ]);
        }
        
        // Get company name for display
        $company = Company::find($companyId);
        
        return view('pages.settings.payroll-parameter', [
            'page_title' => 'Payroll Parameter',
            'parameter' => $parameter,
            'company' => $company
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pf_percentage' => 'required|numeric|min:0|max:100',
            'ppf_percentage' => 'required|numeric|min:0|max:100',
            'ac_no_2_pf_percentage' => 'required|numeric|min:0|max:100',
            'ac_22_pf_percentage' => 'required|numeric|min:0|max:100',
            'epf_percentage' => 'required|numeric|min:0|max:100',
            'if_percentage' => 'required|numeric|min:0|max:100',
            'ac_21_pf_percentage' => 'required|numeric|min:0|max:100',
            'esic_employee_percentage' => 'required|numeric|min:0|max:100',
            'esic_employer_percentage' => 'required|numeric|min:0|max:100',
            'esic_limit' => 'required|numeric|min:0',
            'previous_month' => 'nullable|date',
            'current_month' => 'nullable|date',
            'sunday' => 'required|integer|min:0',
            'manday' => 'required|integer|min:0',
            'total_days' => 'required|integer|min:1|max:31',
            'holiday_percentage' => 'required|numeric|min:0|max:100',
            'pf_limit' => 'required|numeric|min:0',
            'bonus_start_date' => 'nullable|date',
            'leave_start_date' => 'nullable|date',
        ]);

        try {
            DB::beginTransaction();
            
            // Get selected company ID
            $companyId = $this->getSelectedCompanyId();
            
            if (!$companyId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please select a company first'
                ], 400);
            }
            
            // Add company_id to validated data
            $validated['company_id'] = $companyId;
            
            // Get existing parameter for this company or create new
            $parameter = PayrollParameter::where('company_id', $companyId)->first();
            
            if ($parameter) {
                $parameter->update($validated);
            } else {
                $parameter = PayrollParameter::create($validated);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Payroll parameters saved successfully',
                'data' => $parameter
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error saving payroll parameters: ' . $e->getMessage()
            ], 500);
        }
    }
}

