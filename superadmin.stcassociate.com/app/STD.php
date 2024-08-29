<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class STD extends Model
{
    protected $table = 'stc_status_down_list';
    public $timestamps = false;
    protected $primaryKey = 'stc_status_down_list_id';
}
