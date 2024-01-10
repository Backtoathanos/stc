<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchSchool extends Model
{
    protected $table = 'stc_school_month_closing';
    public $timestamps = false;
    protected $primaryKey = 'stc_school_month_closing_id';
    

    static public function getSchool(){
        return BranchSchool::select('stc_school_month_closing.*','stc_school.*')->
                            join('stc_school','stc_school.stc_school_user_id','=','stc_school_month_closing.stc_school_month_closing_created_by')->
                            orderBy('stc_school_month_closing_date', 'desc')->
                            get();
    }
    
    static public function getSingle($id){
        return BranchSchool::find($id);
    }
}
