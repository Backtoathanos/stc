<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequisitionDispatch extends Model
{
    protected $table = 'stc_cust_super_requisition_list_items_rec';
    public $timestamps = false;
    protected $primaryKey = 'stc_cust_super_requisition_list_items_rec_id';
    protected $fillable = [
        'stc_cust_super_requisition_list_items_rec_list_id',
        'stc_cust_super_requisition_list_items_rec_list_item_id',
        'stc_cust_super_requisition_list_items_rec_list_pd_id',
        'stc_cust_super_requisition_list_items_rec_list_poaid',
        'stc_cust_super_requisition_list_items_rec_recqty',
        'stc_cust_super_requisition_list_items_rec_status',
        'stc_cust_super_requisition_list_items_rec_date',
    ];
}
