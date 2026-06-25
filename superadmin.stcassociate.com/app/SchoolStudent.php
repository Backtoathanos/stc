<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolStudent extends Model
{
    protected $table      = 'stc_school_student';
    public    $timestamps = false;
    protected $primaryKey = 'stc_school_student_id';

    protected $fillable = [
        'stc_school_student_studid',
        'stc_school_student_firstname',
        'stc_school_student_lastname',
        'stc_school_student_dob',
        'stc_school_student_gender',
        'stc_school_student_bloodgroup',
        'stc_school_student_email',
        'stc_school_student_contact',
        'stc_school_student_address',
        'stc_school_student_religion',
        'stc_school_student_admissionno',
        'stc_school_student_admissiondate',
        'stc_school_student_admissionclass',
        'stc_school_student_classroomid',
        'stc_school_student_guardianname',
        'stc_school_student_remarks',
        'stc_school_student_status',
        'stc_school_student_createdate',
        'stc_school_student_createdby',
    ];
}
