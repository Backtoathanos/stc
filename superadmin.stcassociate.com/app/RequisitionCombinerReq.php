<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequisitionCombinerReq extends Model
{
    protected $table = 'stc_requisition_combiner_req';
    public $timestamps = false;
    protected $primaryKey = 'stc_requisition_combiner_req_id';
    protected $fillable = [
        'stc_requisition_combiner_req_comb_id',
        'stc_requisition_combiner_req_requisition_id',
    ];
}
