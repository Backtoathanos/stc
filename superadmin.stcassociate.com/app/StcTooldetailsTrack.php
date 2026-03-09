<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StcTooldetailsTrack extends Model
{
    protected $table = 'stc_tooldetails_track';
    public $timestamps = false;
    protected $primaryKey = 'id';

    public function toolDetails()
    {
        return $this->belongsTo(StcTooldetails::class, 'toolsdetails_id', 'id');
    }
}
