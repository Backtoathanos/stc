<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class requisition extends Model
{
    protected $table = 'stc_cust_super_requisition_list';
    public $timestamps = false;
    protected $primaryKey = 'stc_cust_super_requisition_list_id';
}