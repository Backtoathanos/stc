<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'stc_product';
    public $timestamps = false;
    protected $primaryKey = 'stc_product_id';
    protected $fillable = [
        'stc_product_name', 
        'stc_product_desc', 
        'stc_product_cat_id', 
        'stc_product_sub_cat_id', 
        'stc_product_rack_id', 
        'stc_product_brand_id', 
        'stc_product_unit', 
        'stc_product_hsncode', 
        'stc_product_gst',
        'stc_product_avail',
        'stc_product_sale_percentage'
    ];
}
