<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes; 
    protected $table = 'Lm5_menus';
    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'name','category','keyword'
    ];

    public function log() {
        return $this->hasMany('App\Models\Log');
    }

    public function role_permission() {
        return $this->hasMany('App\Models\RolePermission');
    }
}