<?php

namespace App\Models;

use App\Scopes\UniversityScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AbsenceSubmit extends Model
{
    use SoftDeletes; 
    protected $table = 'Lm5_absence_submits';
    protected $dates = ['deleted_at'];
    protected $attributes = [
        'university_id' => UNIVERSITY_ID,
    ];
    
    protected $fillable = [
        'university_id','schedule_id','start_id','lecturer_id','status','note'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new UniversityScopes);
    }

    public function university() {
        return $this->belongsTo('App\Models\University')->withTrashed();
    }

    public function schedule() {
        return $this->belongsTo('App\Models\Schedule')->withTrashed();
    }

    public function lecturer() {
        return $this->belongsTo('App\Models\Lecturer')->withTrashed();
    }

    public function start() {
        return $this->belongsTo('App\Models\AbsenceStart')->withTrashed();
    }
}