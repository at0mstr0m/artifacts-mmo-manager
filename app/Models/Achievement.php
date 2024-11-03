<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AchievementTypes;

class Achievement extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'points',
        'type',
        'target',
        'total',
    ];

    protected $casts = [
        'name' => 'string',
        'code' => 'string',
        'description' => 'string',
        'points' => 'integer',
        'type' => AchievementTypes::class,
        'target' => 'string',
        'total' => 'integer',
    ];
}
