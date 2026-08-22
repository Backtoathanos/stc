<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequisitionItemLog extends Model
{
    protected $table = 'stc_cust_super_requisition_list_items_log';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'item_id',
        'title',
        'message',
        'status',
        'created_date',
        'created_by',
    ];
}
