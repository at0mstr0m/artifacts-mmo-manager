<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\NpcTypes;

class Npc extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'type',
    ];

    protected $casts = [
        'name' => 'string',
        'code' => 'string',
        'description' => 'string',
        'type' => NpcTypes::class,
    ];
}
