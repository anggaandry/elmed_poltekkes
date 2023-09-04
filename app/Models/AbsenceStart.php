<?php

namespace App\Models;

use App\Scopes\UniversityScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AbsenceStart extends Model
{
    use SoftDeletes; 
    protected $table = 'Lm5_absence_starts';
    protected $dates = ['deleted_at'];
    protected $attributes = [
        'university_id' => UNIVERSITY_ID,
    ];
    
    protected $fillable = [
        'university_id','schedule_id','lecturer_id','session','date','start','end','moved','activity','active','move_reason','moved_from'
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

    public function absence_submit() {
        return $this->hasMany('App\Models\AbsenceSubmit');
    }

    public function absence() {
        return $this->hasMany('App\Models\Absence');
    }
}