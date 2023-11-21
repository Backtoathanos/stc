<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'stc_category';
    public $timestamps = false;
    protected $primaryKey = 'stc_cat_id';
    protected $fillable = ['stc_cat_name', 'stc_cat_status'];
}
