<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'stc_brand';
    public $timestamps = false;
    protected $primaryKey = 'stc_brand_id';
    protected $fillable = ['stc_brand_title', 'stc_brand_status'];
}
