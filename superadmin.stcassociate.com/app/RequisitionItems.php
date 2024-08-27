<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequisitionItems extends Model
{
    protected $table = 'stc_cust_super_requisition_list_items';
    public $timestamps = false;
    protected $primaryKey = 'stc_cust_super_requisition_list_id';
}
