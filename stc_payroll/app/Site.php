<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    public $incrementing = false;
    protected $fillable = ['id', 'company_id', 'name', 'under_contract', 'natureofwork', 'workorderno'];
    
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
