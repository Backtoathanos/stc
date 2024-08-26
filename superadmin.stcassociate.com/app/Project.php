<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'stc_cust_project';
    public $timestamps = false;
    protected $primaryKey = 'stc_cust_project_id';
    

    static public function getProject(){
        return Project::select('stc_cust_project.*','stc_electronics_user.*')->
                            join('stc_electronics_user','stc_electronics_user.stc_electronics_user_id','=','stc_school_month_closing.stc_school_month_closing_created_by')->
                            orderBy('stc_school_month_closing_date', 'desc')->
                            get();
    }
    
    static public function getSingle($id){
        return Project::find($id);
    }
}
