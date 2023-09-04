<?php

namespace App\Models;

use App\Scopes\UniversityScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamClass extends Model
{
    use SoftDeletes; 
    protected $table = 'Lm5_exam_classes';
    protected $dates = ['deleted_at'];
    protected $attributes = [
        'university_id' => UNIVERSITY_ID,
    ];
    
    protected $fillable = [
        'university_id','exam_id','class_id','start','end','note','publish'
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

    public function class() {
        return $this->belongsTo('App\Models\Classes')->withTrashed();
    }

    public function exam_answer() {
        return $this->hasMany('App\Models\ExamAnswer');
    }

    public function exam_absence() {
        return $this->hasMany('App\Models\ExamAbsence');
    }

}