<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchSite extends Model
{
    protected $table = 'stc_status_down_list';
    public $timestamps = false;
    protected $primaryKey = 'stc_status_down_list_id';
    

    static public function getSite(){
        return BranchSite::select('stc_status_down_list.*','stc_school.*')->
                            join('stc_cust_pro_supervisor','stc_cust_pro_supervisor.stc_cust_pro_supervisor_id','=','stc_status_down_list.stc_status_down_list_created_by')->
                            orderBy('stc_status_down_list_date', 'desc')->
                            get();
    }
    
    static public function getSingle($id){
        return BranchSite::find($id);
    }
}
