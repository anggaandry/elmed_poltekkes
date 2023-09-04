<?php

namespace App\Models;

use App\Scopes\UniversityScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuizAbsence extends Model
{
    use SoftDeletes; 
    protected $table = 'Lm5_quiz_absences';
    protected $dates = ['deleted_at'];
    protected $attributes = [
        'university_id' => UNIVERSITY_ID,
    ];
    
    protected $fillable = [
        'university_id','quiz_id','quiz_class_id','colleger_id'
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

    public function quiz_class() {
        return $this->belongsTo('App\Models\QuizClass')->withTrashed();
    }

    public function colleger() {
        return $this->belongsTo('App\Models\Colleger')->withTrashed();
    }

   
}