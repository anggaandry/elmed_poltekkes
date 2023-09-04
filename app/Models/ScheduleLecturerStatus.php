<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScheduleLecturerStatus extends Model
{
    use SoftDeletes; 
    protected $table = 'Lm5_schedule_lecturer_statuses';
    protected $dates = ['deleted_at'];
    protected $attributes = [
        'university_id' => UNIVERSITY_ID,
    ];
    
    protected $fillable = [
        'name','bg'
    ]; 

    public function schedule_lecturer() {
        return $this->hasMany('App\Models\ScheduleLecturer');
    }
   
}