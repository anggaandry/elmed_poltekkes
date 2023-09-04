<?php

namespace App\Models;

use App\Scopes\UniversityScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ElearningView extends Model
{
    use SoftDeletes; 
    protected $table = 'Lm5_elearning_views';
    protected $dates = ['deleted_at'];
    protected $attributes = [
        'university_id' => UNIVERSITY_ID,
    ];
    
    protected $fillable = [
        'university_id','elearning_id','elearning_class_id','colleger_id'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new UniversityScopes);
    }

    public function university() {
        return $this->belongsTo('App\Models\University')->withTrashed();
    }

    public function elearning() {
        return $this->belongsTo('App\Models\Elearning')->withTrashed();
    }

    public function elearning_class() {
        return $this->belongsTo('App\Models\ElearningClass')->withTrashed();
    }

    public function colleger() {
        return $this->belongsTo('App\Models\Colleger')->withTrashed();
    }

   
}