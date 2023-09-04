<?php

namespace App\Models;

use App\Scopes\UniversityScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuizClass extends Model
{
    use SoftDeletes; 
    protected $table = 'Lm5_quiz_classes';
    protected $dates = ['deleted_at'];
    protected $attributes = [
        'university_id' => UNIVERSITY_ID,
    ];
    
    protected $fillable = [
        'university_id','quiz_id','class_id','start','end','note','publish'
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

    public function class() {
        return $this->belongsTo('App\Models\Classes')->withTrashed();
    }

    public function quiz_answer() {
        return $this->hasMany('App\Models\QuizAnswer');
    }

    public function quiz_absence() {
        return $this->hasMany('App\Models\QuizAbsence');
    }

}