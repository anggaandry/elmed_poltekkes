<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends Model
{
    use SoftDeletes; 
    protected $table = 'Lm5_programs';
    protected $dates = ['deleted_at'];
    
    protected $fillable = [
       'name'
    ];

    public function study_program_full() {
        return $this->hasMany('App\Models\StudyProgramFull');
    }

}