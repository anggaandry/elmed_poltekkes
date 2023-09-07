<?php

namespace App\Models;

use App\Scopes\UniversityScopes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Admin extends Authenticatable
{
    use SoftDeletes, HasFactory, Notifiable;
  
    protected $table = 'Lm5_admins';
    protected $dates = ['deleted_at'];

    protected $attributes = [
        'university_id' => UNIVERSITY_ID,
    ];
    
    protected $fillable = [
        'password', 'name', 'nip', 'avatar','status','online','birthdate','email','phone','role_id','university_id','remember_token','lang'
    ];

    protected $hidden = [
        'password'
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

    public function log() {
        return $this->hasMany('App\Models\Log');
    }

}