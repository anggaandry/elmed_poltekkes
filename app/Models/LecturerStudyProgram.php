<?php

namespace App\Models;

use App\Scopes\UniversityScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LecturerStudyProgram extends Model
{
    use SoftDeletes; 
    protected $table = 'Lm5_lecturer_study_programs';
    protected $dates = ['deleted_at'];

    protected $attributes = [
        'university_id' => UNIVERSITY_ID,
    ];
    
    protected $fillable = [
        'university_id','lecturer_id','prodi_id','status'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new UniversityScopes);
    }

    public function university() {
        return $this->belongsTo('App\Models\University')->withTrashed();
    }

    public function lecturer() {
        return $this->belongsTo('App\Models\Lecturer')->withTrashed();
    }

    public function prodi() {
        return $this->belongsTo('App\Models\StudyProgramFull')->withTrashed();
    }

}