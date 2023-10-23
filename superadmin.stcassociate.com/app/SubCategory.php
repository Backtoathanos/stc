<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'stc_sub_category';
    public $timestamps = false;
    protected $primaryKey = 'stc_sub_cat_id';
    protected $fillable = ['stc_sub_cat_name', 'stc_sub_cat_status'];
}
