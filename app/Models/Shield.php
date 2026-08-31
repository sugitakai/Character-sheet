<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shield extends Model
{
    protected $fillable = [
        'name',
        'reading',
        'shield_type',
        'weight_type',
        'weight',
        'encumbrance_limit',
        'price',
        'attribute',
        'shield_modifier',
        'shield_value',
        'stealth',
        'stealth_modifier',
        'skill_modifier',
        'effect',
        'reference_page',
    ];
}
