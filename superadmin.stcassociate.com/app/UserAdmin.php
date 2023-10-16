<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAdmin extends Model
{ 
    protected $table = 'stc_user';
    public $timestamps = false;
    protected $primaryKey = 'stc_user_id';
    protected $hidden = ['stc_user_img'];

    static public function getAdmin(){
        return UserAdmin::select('stc_user.*','stc_city.*','stc_state.*')->
                            leftJoin('stc_city','stc_city.stc_city_id','=','stc_user.stc_user_city_id')->
                            leftJoin('stc_state','stc_state.stc_state_id','=','stc_user.stc_user_state_id')->
                            orderBy('stc_user_id', 'desc')->
                            get();
    }
    
    static public function getSingle($id){
        return UserAdmin::find($id);
    }
}
