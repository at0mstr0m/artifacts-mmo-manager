<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Monster extends Model
{
    protected $fillable = [
        'name',
        'code',
        'level',
        'hp',
        'attack_fire',
        'attack_earth',
        'attack_water',
        'attack_air',
        'res_fire',
        'res_earth',
        'res_water',
        'res_air',
        'min_gold',
        'max_gold',
    ];

    protected $casts = [
        'name' => 'string',
        'code' => 'string',
        'level' => 'integer',
        'hp' => 'integer',
        'attack_fire' => 'integer',
        'attack_earth' => 'integer',
        'attack_water' => 'integer',
        'attack_air' => 'integer',
        'res_fire' => 'integer',
        'res_earth' => 'integer',
        'res_water' => 'integer',
        'res_air' => 'integer',
        'min_gold' => 'integer',
        'max_gold' => 'integer',
    ];

    public function drops(): HasMany
    {
        return $this->hasMany(Drop::class);
    }
}
