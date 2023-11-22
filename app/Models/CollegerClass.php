<?php

namespace App\Models;

use App\Scopes\UniversityScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CollegerClass extends Model
{
    use SoftDeletes;
    protected $table = 'Lm5_colleger_classes';
    protected $dates = ['deleted_at'];
    protected $attributes = [
        'university_id' => UNIVERSITY_ID,
    ];

    protected $fillable = [
        'university_id', 'class_id', 'colleger_id'
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
        return $this->belongsTo('App\Models\Classes', 'class_id')->withTrashed();
    }

    public function colleger()
    {
        return $this->belongsTo('App\Models\Colleger')->withTrashed();
    }
}
