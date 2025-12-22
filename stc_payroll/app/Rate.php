<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $table = 'rates';
    
    protected $fillable = [
        'employee_id',
        'ctc',
        'basic',
        'da',
        'arate',
        'otrate',
        'hra',
        'madical',
        'ExgratiaRetention',
        'LTARetention',
        'LTA',
        'CA',
        'Fooding',
        'Misc',
        'CEA',
        'WashingAllowance',
        'ProfessionalPursuits',
        'SpecialAllowance',
        'IncomeTax',
        'personalpay',
        'petrol',
        'mobile',
        'incentive',
        'fixedamt',
        'netamt'
    ];
    
    protected $casts = [
        'ctc' => 'decimal:2',
        'basic' => 'decimal:2',
        'da' => 'decimal:2',
        'arate' => 'decimal:2',
        'otrate' => 'decimal:2',
        'hra' => 'decimal:2',
        'madical' => 'decimal:2',
        'ExgratiaRetention' => 'decimal:2',
        'LTARetention' => 'decimal:2',
        'LTA' => 'decimal:2',
        'CA' => 'decimal:2',
        'Fooding' => 'decimal:2',
        'Misc' => 'decimal:2',
        'CEA' => 'decimal:2',
        'WashingAllowance' => 'decimal:2',
        'ProfessionalPursuits' => 'decimal:2',
        'SpecialAllowance' => 'decimal:2',
        'IncomeTax' => 'decimal:2',
        'personalpay' => 'decimal:2',
        'petrol' => 'decimal:2',
        'mobile' => 'decimal:2',
        'incentive' => 'decimal:2',
        'fixedamt' => 'decimal:2',
        'netamt' => 'decimal:2',
    ];
    
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
