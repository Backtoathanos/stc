<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BranchSchoolCanteen extends Model
{
    protected $table = 'stc_school_canteen';
    public $timestamps = false;
    protected $primaryKey = 'stc_school_canteen_id';
    protected $fillable = [
        'stc_school_canteen_date',
        'stc_school_canteen_school',
        'stc_school_canteen_serve_type',
        'stc_school_canteen_serve_time',
        'stc_school_canteen_serve_quantity',
        'stc_school_canteen_remarks',
        'stc_school_canteen_created_by'
    ];
    

    static public function getSchool(){
        return BranchSchoolCanteen::select('stc_school_canteen.*','stc_school.*')->
                            join('stc_school','stc_school.stc_school_user_id','=','stc_school_canteen.stc_school_canteen_created_by')->
                            orderBy('stc_school_canteen_date', 'desc')->
                            get();
    }
    
    static public function getSingle($id){
        return BranchSchoolCanteen::find($id);
    }
}
