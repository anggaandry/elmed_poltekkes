<?php

namespace App\Models;

use App\Scopes\UniversityScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use SoftDeletes;
    protected $table = 'Lm5_schedules';
    protected $dates = ['deleted_at'];
    protected $attributes = [
        'university_id' => UNIVERSITY_ID,
    ];

    protected $fillable = [
        'university_id', 'class_id', 'sks_id', 'day', 'start', 'end', 'lecturer1_id', 'lecturer2_id', 'room_id'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new UniversityScopes);
    }

    public function university()
    {
        return $this->belongsTo('App\Models\University')->withTrashed();
    }

    public function class()
    {
        return $this->belongsTo('App\Models\Classes')->withTrashed();
    }

    public function sks()
    {
        return $this->belongsTo('App\Models\SKS')->withTrashed();
    }

    public function lecturer1()
    {
        return $this->belongsTo('App\Models\Lecturer')->withTrashed();
    }

    public function lecturer2()
    {
        return $this->belongsTo('App\Models\Lecturer')->withTrashed();
    }

    public function room()
    {
        return $this->belongsTo('App\Models\Room')->withTrashed();
    }

    public function absence()
    {
        return $this->hasMany('App\Models\Absence');
    }

    public function absence_start()
    {
        return $this->hasMany('App\Models\AbsenceStart');
    }

    public function schedule_lecturer()
    {
        return $this->hasMany('App\Models\ScheduleLecturer');
    }
}
