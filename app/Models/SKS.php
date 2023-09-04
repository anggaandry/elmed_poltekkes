<?php

namespace App\Models;

use App\Scopes\UniversityScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SKS extends Model
{
    use SoftDeletes; 
    protected $table = 'Lm5_sks';
    protected $dates = ['deleted_at'];
    protected $attributes = [
        'university_id' => UNIVERSITY_ID,
    ];
    
    protected $fillable = [
        'university_id','prodi_id','semester','subject_id','code','value','status'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new UniversityScopes);
    }

    public function university() {
        return $this->belongsTo('App\Models\University')->withTrashed();
    }

    public function prodi() {
        return $this->belongsTo('App\Models\StudyProgramFull')->withTrashed();
    }

    public function subject() {
        return $this->belongsTo('App\Models\Subject')->withTrashed();
    }

    public function schedule() {
        return $this->hasMany('App\Models\Schedule');
    }
    
    public function elearning() {
        return $this->hasMany('App\Models\Elearning');
    }

    public function quiz() {
        return $this->hasMany('App\Models\Quiz');
    }

    public function exam() {
        return $this->hasMany('App\Models\Exam');
    }

    public function question() {
        return $this->hasMany('App\Models\Question');
    }

    
}