<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gang extends Model
{
    public $incrementing = false;
    protected $fillable = ['id', 'name'];
    
    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
