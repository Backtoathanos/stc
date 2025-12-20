<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CalendarLeaveType extends Model
{
    protected $fillable = [
        'date',
        'leave_type',
        'description'
    ];
    
    protected $casts = [
        'date' => 'date',
    ];
}

