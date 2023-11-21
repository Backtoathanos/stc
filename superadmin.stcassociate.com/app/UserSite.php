<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSite extends Model
{
    protected $table = 'stc_cust_pro_supervisor';
    public $timestamps = false;
    protected $primaryKey = 'stc_cust_pro_supervisor_id';

    static public function getAdmin(){
        return UserSite::select('stc_cust_pro_supervisor.*','stc_city.*','stc_state.*','stc_agents.stc_agents_name')->
                            leftJoin('stc_city','stc_city.stc_city_id','=','stc_cust_pro_supervisor.stc_cust_pro_supervisor_cityid')->
                            leftJoin('stc_state','stc_state.stc_state_id','=','stc_cust_pro_supervisor.stc_cust_pro_supervisor_state_id')->
                            leftJoin('stc_agents','stc_agents.stc_agents_id','=','stc_cust_pro_supervisor.stc_cust_pro_supervisor_created_by')->
                            orderBy('stc_cust_pro_supervisor_id', 'desc')->
                            get();
    }
    
    static public function getSingle($id){
        return UserSite::find($id);
    }
}
