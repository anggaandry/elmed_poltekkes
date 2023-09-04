<?php

namespace App\Models;

use App\Scopes\UniversityScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuizAnswer extends Model
{
    use SoftDeletes; 
    protected $table = 'Lm5_quiz_answers';
    protected $dates = ['deleted_at'];
    protected $attributes = [
        'university_id' => UNIVERSITY_ID,
    ];
    
    protected $fillable = [
        'university_id','sort','quiz_id','quiz_question_id','quiz_class_id','colleger_id','answer','file','score'
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

    public function quiz_question() {
        return $this->belongsTo('App\Models\QuizQuestion')->withTrashed();
    }

    public function quiz_class() {
        return $this->belongsTo('App\Models\QuizClass')->withTrashed();
    }

    public function colleger() {
        return $this->belongsTo('App\Models\Colleger')->withTrashed();
    }

}