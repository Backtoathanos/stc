<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'stc_cust_project';
    public $timestamps = false;
    protected $primaryKey = 'stc_cust_project_id';
}
