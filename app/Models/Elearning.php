<?php

namespace App\Models;

use App\Scopes\UniversityScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Elearning extends Model
{
    use SoftDeletes; 
    protected $table = 'Lm5_elearnings';
    protected $dates = ['deleted_at'];
    protected $attributes = [
        'university_id' => UNIVERSITY_ID,
    ];
    
    protected $fillable = [
        'university_id','sks_id','lecturer_id','image','name','video','description','file1','file2'
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

    public function elearning_class() {
        return $this->hasMany('App\Models\ElearningClass');
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