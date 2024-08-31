<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $table = 'equipment_details';
    public $timestamps = false;
    protected $primaryKey = 'id';
}
