<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Skills;
use App\Enums\TaskTypes;

class Task extends Model
{
    protected $fillable = [
        'code',
        'level',
        'type',
        'min_quantity',
        'max_quantity',
        'skill',
        'rewarded_coins',
    ];

    protected $casts = [
        'code' => 'string',
        'level' => 'integer',
        'type' => TaskTypes::class,
        'min_quantity' => 'integer',
        'max_quantity' => 'integer',
        'skill' => Skills::class,
        'rewarded_coins' => 'integer',
    ];
}
