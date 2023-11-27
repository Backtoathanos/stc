<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchElectronics extends Model
{
    protected $table = 'stc_electronics_inventory';
    public $timestamps = false;
    protected $primaryKey = 'stc_electronics_inventory_id';
    protected $fillable = [
        'stc_electronics_inventory_item_id', 
        'stc_electronics_inventory_item_qty', 
        'stc_electronics_inventory_dp', 
        'stc_electronics_inventory_mrp', 
        'stc_electronics_inventory_condition', 
        'stc_electronics_inventory_sale_rate',
    ];
}
