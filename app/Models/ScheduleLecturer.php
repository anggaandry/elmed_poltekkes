<?php

namespace App\Models;

use App\Scopes\UniversityScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScheduleLecturer extends Model
{
    use SoftDeletes;
    protected $table = 'Lm5_schedule_lecturers';
    protected $dates = ['deleted_at'];
    protected $attributes = [
        'university_id' => UNIVERSITY_ID,
    ];

    protected $fillable = [
        'university_id', 'schedule_id', 'lecturer_id', 'sls_id'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new UniversityScopes);
    }

    public function university()
    {
        return $this->belongsTo('App\Models\University')->withTrashed();
    }

    public function schedule()
    {
        return $this->belongsTo('App\Models\Schedule')->withTrashed();
    }

    public function lecturer()
    {
        return $this->belongsTo('App\Models\Lecturer')->withTrashed();
    }

    public function sls()
    {
        return $this->belongsTo('App\Models\ScheduleLecturerStatus')->withTrashed();
    }

    public function absence_submit()
    {
        return $this->hasMany(AbsenceSubmit::class, 'lecturer_id', 'lecturer_id');
    }

    public function one_absence_submit()
    {
        return $this->hasOne(AbsenceSubmit::class, 'lecturer_id', 'lecturer_id');
    }
}
