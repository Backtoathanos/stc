<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTrading extends Model
{
    protected $table = 'stc_trading_user';
    public $timestamps = false;
    protected $primaryKey = 'stc_trading_user_id';
    

    static public function getAdmin(){
        return UserTrading::select('stc_trading_user.*','stc_city.*','stc_state.*')->
                            join('stc_city','stc_city.stc_city_id','=','stc_trading_user.stc_trading_user_city_id')->
                            join('stc_state','stc_state.stc_state_id','=','stc_trading_user.stc_trading_user_state_id')->
                            orderBy('stc_trading_user_id', 'desc')->
                            get();
    }
    
    static public function getSingle($id){
        return UserTrading::find($id);
    }
}