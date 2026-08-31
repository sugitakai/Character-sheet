<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkillLevel extends Model
{
    protected $fillable = [
        'skill_id',
        'level',
        'prerequisite',
        'effect'
    ];
}
