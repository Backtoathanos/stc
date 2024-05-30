<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchSchoolFee extends Model
{
    protected $table = 'stc_school_fee';
    public $timestamps = false;
    protected $primaryKey = 'stc_school_fee_id';
    

    static public function getSchool(){
        return BranchSchoolFee::select('stc_school_fee.*','stc_school.*')->
                            join('stc_school','stc_school.stc_school_user_id','=','stc_school_fee.stc_school_fee_created_by')->
                            orderBy('stc_school_fee_date', 'desc')->
                            get();
    }
    
    static public function getSingle($id){
        return BranchSchoolFee::find($id);
    }
}
