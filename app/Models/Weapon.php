<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Weapon extends Model
{
    protected $fillable = [
        'name',
        'reading',
        'weapon_type',
        'weight_type',
        'weight',
        'encumbrance_limit',
        'price',
        'attribute',
        'usage',
        'range',
        'hit_modifier',
        'base_damage',
        'job_modifier',
        'effect',
        'reference_page',
    ];
}
