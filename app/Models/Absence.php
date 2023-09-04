<?php

namespace App\Models;

use App\Scopes\UniversityScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Absence extends Model
{
    use SoftDeletes; 
    protected $table = 'Lm5_absences';
    protected $dates = ['deleted_at'];
    protected $attributes = [
        'university_id' => UNIVERSITY_ID,
    ];
    
    protected $fillable = [
        'university_id','schedule_id','date','colleger_id','status','note',
        'start_id','record'
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

    public function colleger() {
        return $this->belongsTo('App\Models\Colleger')->withTrashed();
    }

    public function start() {
        return $this->belongsTo('App\Models\AbsenceStart')->withTrashed();
    }
}