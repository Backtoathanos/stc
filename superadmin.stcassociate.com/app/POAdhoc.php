<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class POAdhoc extends Model
{
    protected $table = 'stc_purchase_product_adhoc';
    public $timestamps = false;
    protected $primaryKey = 'stc_purchase_product_adhoc_id';
}

