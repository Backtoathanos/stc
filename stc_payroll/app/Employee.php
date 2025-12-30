<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public $incrementing = false;
    protected $fillable = [
        'company_id', 'site_id', 'department_id', 'designation_id', 'gang_id',
        'Dob', 'Doj', 'Doe', 'SafetyCardExpiry', 'Imageurl', 'EmpId', 'Name',
        'Father', 'Gender', 'MaritalStatus', 'PfApplicable', 'Uan',
        'EsicApplicable', 'Esic', 'PRFTax', 'Mobile', 'Email', 'EmpSafetyCard',
        'Address', 'AttendAllow', 'OtAppl', 'MrOtAppl', 'AllowAsPer', 'ReversePF',
        'Bank', 'Branch', 'Ifsc', 'Ac', 'Aadhar', 'Pan', 'Otslave', 'Ottype',
        'Paymentmode', 'Weekoff', 'Skill', 'Status', 'leave_balance', 'is_employee', 'is_supervisor', 'is_officeStaff'
    ];
    
    protected $casts = [
        'PfApplicable' => 'boolean',
        'EsicApplicable' => 'boolean',
        'PRFTax' => 'boolean',
        'AttendAllow' => 'boolean',
        'OtAppl' => 'boolean',
        'MrOtAppl' => 'boolean',
        'AllowAsPer' => 'boolean',
        'ReversePF' => 'boolean',
        'is_employee' => 'boolean',
        'is_supervisor' => 'boolean',
        'is_officeStaff' => 'boolean',
        'leave_balance' => 'decimal:2',
        'Dob' => 'date',
        'Doj' => 'date',
        'Doe' => 'date',
        'SafetyCardExpiry' => 'date',
    ];
    
    public function site()
    {
        return $this->belongsTo(Site::class);
    }
    
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    
    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }
    
    public function gang()
    {
        return $this->belongsTo(Gang::class);
    }
    
    public function rate()
    {
        return $this->hasOne(Rate::class, 'employee_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
