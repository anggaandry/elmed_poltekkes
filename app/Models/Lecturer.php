<?php

namespace App\Models;

use App\Scopes\UniversityScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Lecturer extends Authenticatable
{
    use SoftDeletes, HasFactory, Notifiable;
  
    protected $table = 'Lm5_lecturers';
    protected $dates = ['deleted_at'];

    protected $attributes = [
        'university_id' => UNIVERSITY_ID,
    ];
    
    protected $fillable = [
        'password', 'name','gender','religion_id','front_title','back_title','birthdate',
        'identity','identity_number', 'avatar','status','online','university_id','remember_token','lang'
    ];

    protected $hidden = [
        'password'
    ];    

    protected static function booted()
    {
        static::addGlobalScope(new UniversityScopes);
    }

    public function university() {
        return $this->belongsTo('App\Models\University')->withTrashed();
    }

    public function religion() {
        return $this->belongsTo('App\Models\Religion')->withTrashed();
    }

    public function log() {
        return $this->hasMany('App\Models\Log');
    }

    public function lecturer_study_program() {
        return $this->hasMany('App\Models\LecturerStudyProgram');
    }

    public function schedule() {
        return $this->hasMany('App\Models\Schedule');
    }

    public function elearning() {
        return $this->hasMany('App\Models\Elearning');
    }

    public function quiz() {
        return $this->hasMany('App\Models\Quiz');
    }

    public function exam() {
        return $this->hasMany('App\Models\Exam');
    }

    public function question() {
        return $this->hasMany('App\Models\Question');
    }

    public function elearning_discussion() {
        return $this->hasMany('App\Models\ElearningDiscussion');
    }

    public function absence_start() {
        return $this->hasMany('App\Models\AbsenceStart');
    }

}