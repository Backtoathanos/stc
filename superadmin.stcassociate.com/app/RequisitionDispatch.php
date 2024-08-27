<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequisitionDispatch extends Model
{
    protected $table = 'stc_cust_super_requisition_list_items_rec';
    public $timestamps = false;
    protected $primaryKey = 'stc_cust_super_requisition_list_items_rec_id';
}
