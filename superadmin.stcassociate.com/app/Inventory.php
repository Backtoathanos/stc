<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'stc_item_inventory';
    public $timestamps = false;
    protected $primaryKey = 'stc_item_inventory_id';
    protected $fillable = ['stc_item_inventory_pd_id', 'stc_item_inventory_pd_qty'];
}
