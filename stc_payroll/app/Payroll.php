<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'aadhar',
        'employee_name',
        'site_id',
        'month_year',
        'working_days',
        'present_days',
        'absent_days',
        'ot_hours',
        'category',
        'basic_rate',
        'da_rate',
        'basic_amount',
        'da_amount',
        'ot_amount',
        'gross_salary',
        'pf_employee',
        'esic_employee',
        'total_deductions',
        'net_salary'
    ];
    
    protected $casts = [
        'working_days' => 'integer',
        'present_days' => 'integer',
        'absent_days' => 'integer',
        'ot_hours' => 'decimal:2',
        'basic_rate' => 'decimal:2',
        'da_rate' => 'decimal:2',
        'basic_amount' => 'decimal:2',
        'da_amount' => 'decimal:2',
        'ot_amount' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'pf_employee' => 'decimal:2',
        'esic_employee' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];
    
    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}

