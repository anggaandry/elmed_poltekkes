<?php

namespace App\Models;

use App\Scopes\UniversityScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ElearningDiscussion extends Model
{
    use SoftDeletes; 
    protected $table = 'Lm5_elearning_discussions';
    protected $dates = ['deleted_at'];
    protected $attributes = [
        'university_id' => UNIVERSITY_ID,
    ];
    
    protected $fillable = [
        'university_id','discussion_id','elearning_id','elearning_class_id','comment','file','image','colleger_id','lecturer_id'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new UniversityScopes);
    }

    public function university() {
        return $this->belongsTo('App\Models\University')->withTrashed();
    }

    public function discussion() {
        return $this->belongsTo('App\Models\ElearningDiscussion')->withTrashed();
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

    public function lecturer() {
        return $this->belongsTo('App\Models\Lecturer')->withTrashed();
    }

    public function elearning_discussion() {
        return $this->hasMany('App\Models\ElearningDiscussion');
    }

}