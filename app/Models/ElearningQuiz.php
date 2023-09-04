<?php

namespace App\Models;

use App\Scopes\UniversityScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ElearningQuiz extends Model
{
    use SoftDeletes; 
    protected $table = 'Lm5_elearning_quizzes';
    protected $dates = ['deleted_at'];
    protected $attributes = [
        'university_id' => UNIVERSITY_ID,
    ];
    
    protected $fillable = [
        'university_id','elearning_id','quiz_id'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new UniversityScopes);
    }

    
    public function quiz() {
        return $this->belongsTo('App\Models\Quiz')->withTrashed();
    }

    public function elearning() {
        return $this->belongsTo('App\Models\Elearning')->withTrashed();
    }

     public function question() {
        return $this->hasMany('App\Models\Question');
    }

  
}