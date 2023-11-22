<?php

namespace App\Models;

use App\Scopes\UniversityScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classes extends Model
{
    use SoftDeletes;
    protected $table = 'Lm5_classes';
    protected $dates = ['deleted_at'];
    protected $attributes = [
        'university_id' => UNIVERSITY_ID,
    ];

    protected $fillable = [
        'university_id', 'prodi_id', 'name', 'year', 'odd', 'semester'
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

    public function colleger_class()
    {
        return $this->hasMany('App\Models\CollegerClass', 'class_id');
    }

    public function schedule()
    {
        return $this->hasMany('App\Models\Schedule');
    }

    public function elearning_class()
    {
        return $this->hasMany('App\Models\ElearningClass');
    }

    public function quiz_class()
    {
        return $this->hasMany('App\Models\QuisClass');
    }
}
