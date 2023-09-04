<?php

namespace App\Models;

use App\Scopes\UniversityScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ElearningClass extends Model
{
    use SoftDeletes; 
    protected $table = 'Lm5_elearning_classes';
    protected $dates = ['deleted_at'];
    protected $attributes = [
        'university_id' => UNIVERSITY_ID,
    ];
    
    protected $fillable = [
        'university_id','elearning_id','class_id','start','end','note'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new UniversityScopes);
    }

    public function university() {
        return $this->belongsTo('App\Models\University')->withTrashed();
    }

    public function elearning() {
        return $this->belongsTo('App\Models\Elearning')->withTrashed();
    }

    public function class() {
        return $this->belongsTo('App\Models\Classes')->withTrashed();
    }

    public function elearning_discussion() {
        return $this->hasMany('App\Models\ElearningDiscussion');
    }

    public function elearning_quiz() {
        return $this->hasMany('App\Models\ElearningQuiz');
    }

    public function elearning_view() {
        return $this->hasMany('App\Models\ElearningView');
    }

}