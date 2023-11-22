<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rack extends Model
{
    protected $table = 'stc_rack';
    public $timestamps = false;
    protected $primaryKey = 'stc_rack_id';
    protected $fillable = ['stc_rack_name', 'stc_rack_status'];
}
