<?php

namespace App\Models;

use App\Scopes\UniversityScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quiz extends Model
{
    use SoftDeletes; 
    protected $table = 'Lm5_quizzes';
    protected $dates = ['deleted_at'];
    protected $attributes = [
        'university_id' => UNIVERSITY_ID,
    ];
    
    protected $fillable = [
        'university_id','sks_id','lecturer_id','name','description'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new UniversityScopes);
    }

    public function university() {
        return $this->belongsTo('App\Models\University')->withTrashed();
    }

    public function sks() {
        return $this->belongsTo('App\Models\SKS')->withTrashed();
    }

    public function lecturer() {
        return $this->belongsTo('App\Models\Lecturer')->withTrashed();
    }

    public function elearning() {
        return $this->hasMany('App\Models\Elearning');
    }

    public function quiz_question() {
        return $this->hasMany('App\Models\QuizQuestion');
    }

    public function quiz_class() {
        return $this->hasMany('App\Models\QuizClass');
    }

    public function quiz_answer() {
        return $this->hasMany('App\Models\QuizAnswer');
    }

    public function quiz_absence() {
        return $this->hasMany('App\Models\QuizAbsence');
    }

}