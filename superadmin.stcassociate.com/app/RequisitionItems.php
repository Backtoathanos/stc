<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequisitionItems extends Model
{
    protected $table = 'stc_cust_super_requisition_list_items';
    public $timestamps = false;
    protected $primaryKey = 'stc_cust_super_requisition_list_id';
    protected $fillable = [
        'stc_cust_super_requisition_list_items_req_id',
        'stc_cust_super_requisition_list_items_title',
        'stc_cust_super_requisition_list_items_unit',
        'stc_cust_super_requisition_list_items_reqqty',
        'stc_cust_super_requisition_list_items_approved_qty',
        'stc_cust_super_requisition_items_finalqty',
        'stc_cust_super_requisition_list_items_acceptby',
        'stc_cust_super_requisition_items_type',
        'stc_cust_super_requisition_items_priority',
        'stc_cust_super_requisition_list_items_product_id',
        'stc_cust_super_requisition_list_items_status',
        'stc_cust_super_requisition_list_items_return_accepted',
    ];
}
