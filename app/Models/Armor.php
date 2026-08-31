<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Armor extends Model
{
    protected $fillable = [
        'name',
        'reading',
        'armor_type',
        'weight_type',
        'weight',
        'encumbrance_limit',
        'price',
        'attribute',
        'armor',
        'evasion_modifier',
        'movement_modifier',
        'stealth',
        'stealth_modifier',
        'skill_modifier',
        'effect',
        'reference_page',
    ];
}
