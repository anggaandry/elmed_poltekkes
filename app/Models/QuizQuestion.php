<?php

namespace App\Models;

use App\Scopes\UniversityScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuizQuestion extends Model
{
    use SoftDeletes; 
    protected $table = 'Lm5_quiz_questions';
    protected $dates = ['deleted_at'];
    protected $attributes = [
        'university_id' => UNIVERSITY_ID,
    ];
    
    protected $fillable = [
        'university_id','sort','quiz_id','question_id','value'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new UniversityScopes);
    }

    public function university() {
        return $this->belongsTo('App\Models\University')->withTrashed();
    }

    public function quiz() {
        return $this->belongsTo('App\Models\Quiz')->withTrashed();
    }

    public function question() {
        return $this->belongsTo('App\Models\Question')->withTrashed();
    }
    
    public function quiz_answer() {
        return $this->hasMany('App\Models\QuizAnswer');
    }

   
}