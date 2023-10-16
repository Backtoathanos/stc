<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGroceries extends Model
{
    protected $table = 'stc_groceries_user';
    public $timestamps = false;
    protected $primaryKey = 'stc_groceries_user_id';
    

    static public function getAdmin(){
        return UserGroceries::select('stc_groceries_user.*','stc_city.*','stc_state.*')->
                            join('stc_city','stc_city.stc_city_id','=','stc_groceries_user.stc_groceries_user_city_id')->
                            join('stc_state','stc_state.stc_state_id','=','stc_groceries_user.stc_groceries_user_state_id')->
                            orderBy('stc_groceries_user_id', 'desc')->
                            get();
    }
    
    static public function getSingle($id){
        return UserGroceries::find($id);
    }
}
