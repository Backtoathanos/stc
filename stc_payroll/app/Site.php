<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    public $incrementing = false;
    protected $fillable = ['id', 'name'];
    
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
