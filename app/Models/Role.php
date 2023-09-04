<?php

namespace App\Models;

use App\Scopes\UniversityScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes; 
    protected $table = 'Lm5_roles';
    protected $dates = ['deleted_at'];
    protected $attributes = [
        'university_id' => UNIVERSITY_ID,
    ];
    
    protected $fillable = [
        'university_id','name','only_prodi','prodi_id','has_view','has_add','has_edit','has_delete'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new UniversityScopes);
    }

    public function university() {
        return $this->belongsTo('App\Models\University')->withTrashed();
    }

    public function prodi() {
        return $this->belongsTo('App\Models\StudyProgramFull')->withTrashed();
    }

    public function role_permission() {
        return $this->hasMany('App\Models\RolePermission');
    }

    public function admin() {
        return $this->hasMany('App\Models\Admin');
    }

}