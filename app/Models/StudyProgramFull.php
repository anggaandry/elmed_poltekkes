<?php

namespace App\Models;

use App\Scopes\UniversityScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudyProgramFull extends Model
{
    use SoftDeletes; 
    protected $table = 'Lm5_study_program_full';
    protected $dates = ['deleted_at'];
    protected $attributes = [
        'university_id' => UNIVERSITY_ID,
    ];
    
    protected $fillable = [
        'university_id','program_id','study_program_id','category_id'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new UniversityScopes);
    }

    public function university() {
        return $this->belongsTo('App\Models\University')->withTrashed();
    }

    public function lecturer_study_program() {
        return $this->hasMany('App\Models\LecturerStudyProgram');
    }

    public function program() {
        return $this->belongsTo('App\Models\Program')->withTrashed();
    }

    public function study_program() {
        return $this->belongsTo('App\Models\StudyProgram')->withTrashed();
    }

    public function category() {
        return $this->belongsTo('App\Models\StudyProgramCategory')->withTrashed();
    }

    public function colleger() {
        return $this->hasMany('App\Models\Colleger');
    }

    public function sks() {
        return $this->hasMany('App\Models\SKS');
    }
    
}