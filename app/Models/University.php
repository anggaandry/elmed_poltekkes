<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class University extends Model
{
    use SoftDeletes; 
    protected $table = 'Lm5_universities';
    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'logo', 'name', 'address', 'lon','lat','type','email','phone'
    ];

    public function admin() {
        return $this->hasMany('App\Models\Admin');
    }

    public function log() {
        return $this->hasMany('App\Models\Log');
    }

    public function role() {
        return $this->hasMany('App\Models\Role');
    }

    public function colleger() {
        return $this->hasMany('App\Models\Colleger');
    }

    public function lecturer() {
        return $this->hasMany('App\Models\Lecturer');
    }

    public function major() {
        return $this->hasMany('App\Models\Major');
    }

    public function study_program() {
        return $this->hasMany('App\Models\StudyProgram');
    }

    public function study_program_category() {
        return $this->hasMany('App\Models\StudyProgramCategory');
    }
    
    public function study_program_full() {
        return $this->hasMany('App\Models\StudyProgramFull');
    }

    public function role_permission() {
        return $this->hasMany('App\Models\RolePermission');
    }

    public function lecturer_study_program() {
        return $this->hasMany('App\Models\LecturerStudyProgram');
    }

    public function subject() {
        return $this->hasMany('App\Models\Subject');
    }

    public function semester() {
        return $this->hasMany('App\Models\Semester');
    }

    public function colleger_class() {
        return $this->hasMany('App\Models\CollegerClass');
    }

    public function schedule() {
        return $this->hasMany('App\Models\Schedule');
    }

    public function absence() {
        return $this->hasMany('App\Models\Absence');
    }

    public function room() {
        return $this->hasMany('App\Models\Room');
    }

}