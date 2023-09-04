<?php

namespace App\Models;

use App\Scopes\UniversityScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Colleger extends Authenticatable
{
    use SoftDeletes, HasFactory, Notifiable;

    protected $table = 'Lm5_collegers';
    protected $dates = ['deleted_at'];

    protected $attributes = [
        'university_id' => UNIVERSITY_ID,
    ];

    protected $fillable = [
        'password', 'name', 'nim', 'avatar', 'status', 'online', 'university_id', 'remember_token', 'prodi_id',
        'religion_id', 'gender', 'birthdate', 'year'
    ];

    protected $hidden = [
        'password'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new UniversityScopes);
    }

    public function university()
    {
        return $this->belongsTo('App\Models\University')->withTrashed();
    }

    public function prodi()
    {
        return $this->belongsTo('App\Models\StudyProgramFull')->withTrashed();
    }

    public function religion()
    {
        return $this->belongsTo('App\Models\Religion')->withTrashed();
    }

    public function colleger_class()
    {
        return $this->hasMany('App\Models\CollegerClass');
    }

    public function absence()
    {
        return $this->hasMany('App\Models\Absence');
    }

    public function elearning_view()
    {
        return $this->hasMany('App\Models\ElearningView');
    }

    public function quiz_answer()
    {
        return $this->hasMany('App\Models\QuizAnswer');
    }

    public function quiz_absence()
    {
        return $this->hasMany('App\Models\QuizAbsence');
    }

    public function exam_answer()
    {
        return $this->hasMany('App\Models\ExamAnswer');
    }

    public function exam_absence()
    {
        return $this->hasMany('App\Models\ExamAbsence');
    }

    public function elearning_discussion()
    {
        return $this->hasMany('App\Models\ElearningDiscussion');
    }
}
