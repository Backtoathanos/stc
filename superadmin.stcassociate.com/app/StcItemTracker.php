<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StcItemTracker extends Model
{
    protected $table = 'stc_item_tracker';
    public $timestamps = false;
    protected $primaryKey = 'stc_item_tracker_id';
}
