<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserManager extends Model
{
    protected $table = 'stc_agents';
    public $timestamps = false;
    protected $primaryKey = 'stc_agents_id';

    static public function getAdmin(){
        return UserManager::select('stc_agents.*','stc_city.*','stc_state.*')->
                            leftJoin('stc_city','stc_city.stc_city_id','=','stc_agents.stc_agents_city_id')->
                            leftJoin('stc_state','stc_state.stc_state_id','=','stc_agents.stc_agents_state_id')->
                            orderBy('stc_agents_id', 'desc')->
                            get();
    }
    
    static public function getSingle($id){
        return UserManager::find($id);
    }
}
