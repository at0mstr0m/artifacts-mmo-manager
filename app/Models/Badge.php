<?php

declare(strict_types=1);

namespace App\Models;

class Badge extends Model
{
    protected $fillable = [
        'code',
        'season',
        'description',
        'conditions'
    ];

    protected $casts = [
        'code' => 'string',
        'season' => 'integer',
        'description' => 'string',
        'conditions' => 'collection',
    ];
}
