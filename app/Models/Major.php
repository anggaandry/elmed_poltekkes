<?php

namespace App\Models;

use App\Scopes\UniversityScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Major extends Model
{
    use SoftDeletes; 
    protected $table = 'Lm5_majors';
    protected $dates = ['deleted_at'];
    protected $attributes = [
        'university_id' => UNIVERSITY_ID,
    ];
    
    protected $fillable = [
        'university_id','name'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new UniversityScopes);
    }

    public function university() {
        return $this->belongsTo('App\Models\University')->withTrashed();
    }

    public function role() {
        return $this->hasMany('App\Models\Role');
    }


    public function study_program_full() {
        return $this->hasMany('App\Models\StudyProgramFull');
    }
    

}