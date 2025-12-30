<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    public $incrementing = false;
    protected $fillable = ['id', 'company_id', 'name'];
    
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
