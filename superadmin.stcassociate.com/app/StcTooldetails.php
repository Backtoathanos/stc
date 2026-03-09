<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StcTooldetails extends Model
{
    protected $table = 'stc_tooldetails';
    public $timestamps = false;
    protected $primaryKey = 'id';

    public function trackRecords()
    {
        return $this->hasMany(StcTooldetailsTrack::class, 'toolsdetails_id', 'id');
    }
}
