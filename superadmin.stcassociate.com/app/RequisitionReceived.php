<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequisitionReceived extends Model
{
    protected $table = 'stc_cust_super_requisition_rec_items_fr_supervisor';
    public $timestamps = false;
    protected $primaryKey = 'stc_cust_super_requisition_rec_items_fr_supervisor_id';
    protected $fillable = [
        'stc_cust_super_requisition_rec_items_fr_supervisor_date',
        'stc_cust_super_requisition_rec_items_fr_supervisor_rqitemid',
        'stc_cust_super_requisition_rec_items_fr_supervisor_rqitemqty',
    ];
}
