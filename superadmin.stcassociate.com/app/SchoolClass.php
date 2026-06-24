<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    protected $table      = 'stc_school_class';
    public    $timestamps = false;
    protected $primaryKey = 'stc_school_class_id';
}
