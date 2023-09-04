<?php

namespace App\Models;

use App\Scopes\UniversityScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RolePermission extends Model
{
    use SoftDeletes; 
    protected $table = 'Lm5_role_permissions';
    protected $dates = ['deleted_at'];

    protected $attributes = [
        'university_id' => UNIVERSITY_ID,
    ];
    
    protected $fillable = [
        'university_id','role_id','menu_id','view_access','add_access','edit_access','delete_access'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new UniversityScopes);
    }

    public function university() {
        return $this->belongsTo('App\Models\University')->withTrashed();
    }

    public function role() {
        return $this->belongsTo('App\Models\Role')->withTrashed();
    }

    public function menu() {
        return $this->belongsTo('App\Models\Menu')->withTrashed();
    }

}