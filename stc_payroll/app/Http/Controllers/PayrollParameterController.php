<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PayrollParameter;
use Illuminate\Support\Facades\DB;

class PayrollParameterController extends Controller
{
    public function index()
    {
        // Get the latest payroll parameter or create a new one with defaults
        $parameter = PayrollParameter::latest()->first();
        
        if (!$parameter) {
            // Create default parameter
            $parameter = PayrollParameter::create([
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
        
        return view('pages.settings.payroll-parameter', [
            'page_title' => 'Payroll Parameter',
            'parameter' => $parameter
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
            
            // Get existing parameter or create new
            $parameter = PayrollParameter::latest()->first();
            
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

