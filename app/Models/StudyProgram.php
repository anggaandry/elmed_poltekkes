<?php

namespace App\Models;

use App\Scopes\UniversityScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudyProgram extends Model
{
    use SoftDeletes; 
    protected $table = 'Lm5_study_programs';
    protected $dates = ['deleted_at'];
    protected $attributes = [
        'university_id' => UNIVERSITY_ID,
    ];
    
    protected $fillable = [
        'university_id','major_id','name'
    ];

    public function university() {
        return $this->belongsTo('App\Models\University')->withTrashed();
    }

    protected static function booted()
    {
        static::addGlobalScope(new UniversityScopes);
    }

    public function major() {
        return $this->belongsTo('App\Models\Major')->withTrashed();
    }

    public function study_program_full() {
        return $this->hasMany('App\Models\StudyProgramFull');
    }

    
    

}