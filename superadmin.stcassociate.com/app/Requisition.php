<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class requisition extends Model
{
    protected $table = 'stc_cust_super_requisition_list';
    public $timestamps = false;
    protected $primaryKey = 'stc_cust_super_requisition_list_id';
    protected $fillable = [
        'stc_cust_super_requisition_list_date',
        'stc_cust_super_requisition_list_sdlid',
        'stc_cust_super_requisition_list_super_id',
        'stc_cust_super_requisition_list_project_id',
        'stc_cust_super_requisition_list_status',
        'stc_cust_super_requisition_list_approved_by',
    ];
}
