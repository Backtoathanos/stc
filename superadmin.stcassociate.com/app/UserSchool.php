<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSchool extends Model
{
    protected $table = 'stc_school';
    public $timestamps = false;
    protected $primaryKey = 'stc_school_user_id';
    

    static public function getAdmin(){
        return UserSchool::select('stc_school.*','stc_city.*','stc_state.*')->
                            join('stc_city','stc_city.stc_city_id','=','stc_school.stc_school_user_cityid')->
                            join('stc_state','stc_state.stc_state_id','=','stc_school.stc_school_user_stateid')->
                            orderBy('stc_school_user_id', 'desc')->
                            get();
    }
    
    static public function getSingle($id){
        return UserSchool::find($id);
    }
}
