<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class requisition extends Model
{
    protected $table = 'stc_cust_super_requisition_list';
    public $timestamps = false;
    protected $primaryKey = 'stc_cust_super_requisition_list_id';
    

    static public function getSchool(){
        return requisition::select('stc_school_month_closing.*','stc_electronics_user.*')->
                            join('stc_electronics_user','stc_electronics_user.stc_electronics_user_id','=','stc_school_month_closing.stc_school_month_closing_created_by')->
                            orderBy('stc_school_month_closing_date', 'desc')->
                            get();
    }
    
    static public function getSingle($id){
        return requisition::find($id);
    }
}
