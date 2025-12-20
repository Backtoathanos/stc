<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayrollParameter extends Model
{
    protected $fillable = [
        'pf_percentage',
        'ppf_percentage',
        'ac_no_2_pf_percentage',
        'ac_22_pf_percentage',
        'epf_percentage',
        'if_percentage',
        'ac_21_pf_percentage',
        'esic_employee_percentage',
        'esic_employer_percentage',
        'esic_limit',
        'previous_month',
        'current_month',
        'sunday',
        'manday',
        'total_days',
        'holiday_percentage',
        'pf_limit',
        'bonus_start_date',
        'leave_start_date'
    ];
    
    protected $casts = [
        'pf_percentage' => 'decimal:2',
        'ppf_percentage' => 'decimal:2',
        'ac_no_2_pf_percentage' => 'decimal:2',
        'ac_22_pf_percentage' => 'decimal:2',
        'epf_percentage' => 'decimal:2',
        'if_percentage' => 'decimal:2',
        'ac_21_pf_percentage' => 'decimal:2',
        'esic_employee_percentage' => 'decimal:2',
        'esic_employer_percentage' => 'decimal:2',
        'esic_limit' => 'decimal:2',
        'previous_month' => 'date',
        'current_month' => 'date',
        'sunday' => 'integer',
        'manday' => 'integer',
        'total_days' => 'integer',
        'holiday_percentage' => 'decimal:2',
        'pf_limit' => 'decimal:2',
        'bonus_start_date' => 'date',
        'leave_start_date' => 'date',
    ];
}

