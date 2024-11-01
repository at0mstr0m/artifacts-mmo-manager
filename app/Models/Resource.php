<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Skills;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Resource extends Model
{
    protected $fillable = [
        'name',
        'code',
        'level',
        'skill',
    ];

    protected $casts = [
        'name' => 'string',
        'code' => 'string',
        'level' => 'integer',
        'skill' => Skills::class,
    ];

    public function drops(): MorphMany
    {
        return $this->morphMany(Drop::class, 'source');
    }
}
