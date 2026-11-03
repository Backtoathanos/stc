<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReqItemCommunicate extends Model
{
    protected $table = 'stc_req_item_communicate';
    public $timestamps = false;
    protected $primaryKey = 'id';
    protected $fillable = [
        'req_item_id',
        'req_id',
        'message',
        'image_url',
        'issue_type',
        'username',
        'user_type',
        'created_by',
        'created_date',
        'status',
    ];
}
