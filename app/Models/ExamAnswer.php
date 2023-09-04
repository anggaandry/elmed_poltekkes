<?php

namespace App\Models;

use App\Scopes\UniversityScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamAnswer extends Model
{
    use SoftDeletes; 
    protected $table = 'Lm5_exam_answers';
    protected $dates = ['deleted_at'];
    protected $attributes = [
        'university_id' => UNIVERSITY_ID,
    ];
    
    protected $fillable = [
        'university_id','sort','exam_id','exam_question_id','exam_class_id','colleger_id','answer','file','score'
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

    public function exam_question() {
        return $this->belongsTo('App\Models\ExamQuestion')->withTrashed();
    }

    public function exam_class() {
        return $this->belongsTo('App\Models\ExamClass')->withTrashed();
    }

    public function colleger() {
        return $this->belongsTo('App\Models\Colleger')->withTrashed();
    }

}