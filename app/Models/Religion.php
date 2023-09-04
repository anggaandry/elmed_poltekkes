<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Religion extends Model
{
    use SoftDeletes; 
    protected $table = 'Lm5_religions';
    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'name'
    ];

    public function lecturer() {
        return $this->hasMany('App\Models\Lecturer');
    }

    public function colleger() {
        return $this->hasMany('App\Models\Colleger');
    }
}