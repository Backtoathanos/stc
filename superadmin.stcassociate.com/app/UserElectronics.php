<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserElectronics extends Model
{
    protected $table = 'stc_electronics_user';
    public $timestamps = false;
    protected $primaryKey = 'stc_electronics_user_id';
    

    static public function getAdmin(){
        return UserElectronics::select('stc_electronics_user.*','stc_city.*','stc_state.*')->
                            join('stc_city','stc_city.stc_city_id','=','stc_electronics_user.stc_electronics_user_cityid')->
                            join('stc_state','stc_state.stc_state_id','=','stc_electronics_user.stc_electronics_user_stateid')->
                            orderBy('stc_electronics_user_id', 'desc')->
                            get();
    }
    
    static public function getSingle($id){
        return UserElectronics::find($id);
    }
}
