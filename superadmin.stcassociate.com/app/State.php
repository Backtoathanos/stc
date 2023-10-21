<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $table = 'stc_state';
    public $timestamps = false;
    protected $primaryKey = 'stc_state_id';
    protected $fillable = ['stc_state_name', 'stc_state_status'];

    static public function getState(){
        return State::select('stc_state.*')->orderBy('stc_state_name', 'asc')->get();
    }
}
