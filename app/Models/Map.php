<?php

declare(strict_types=1);

namespace App\Models;

class Map extends Model
{
    protected $fillable = [
        'name',
        'skin',
        'x',
        'y',
        'content_type',
        'content_code',
    ];

    protected $casts = [
        'name' => 'string',
        'skin' => 'string',
        'x' => 'integer',
        'y' => 'integer',
        'content_type' => 'string',
        'content_code' => 'string',
    ];
}
