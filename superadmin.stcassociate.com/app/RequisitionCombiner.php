<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequisitionCombiner extends Model
{
    protected $table = 'stc_requisition_combiner';
    public $timestamps = false;
    protected $primaryKey = 'stc_requisition_combiner_id';
    protected $fillable = [
        'stc_requisition_combiner_date',
        'stc_requisition_combiner_refrence',
        'stc_requisition_combiner_agent_id',
        'stc_requisition_combiner_status',
    ];
}
