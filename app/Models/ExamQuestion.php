<?php

namespace App\Models;

use App\Scopes\UniversityScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamQuestion extends Model
{
    use SoftDeletes; 
    protected $table = 'Lm5_exam_questions';
    protected $dates = ['deleted_at'];
    protected $attributes = [
        'university_id' => UNIVERSITY_ID,
    ];
    
    protected $fillable = [
        'university_id','sort','exam_id','question_id','value'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new UniversityScopes);
    }

    public function university() {
        return $this->belongsTo('App\Models\University')->withTrashed();
    }

    public function exam() {
        return $this->belongsTo('App\Models\Exam')->withTrashed();
    }

    public function question() {
        return $this->belongsTo('App\Models\Question')->withTrashed();
    }
    
    public function exam_answer() {
        return $this->hasMany('App\Models\ExamAnswer');
    }

   
}