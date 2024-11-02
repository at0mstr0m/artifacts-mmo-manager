<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    protected $fillable = [
        'name',
        'previous_skin',
        'duration',
        'expiration',
        'started_at',
    ];

    protected $casts = [
        'name' => 'string',
        'previous_skin' => 'string',
        'duration' => 'integer',
        'expiration' => 'datetime',
        'started_at' => 'datetime',
    ];

    public function map(): BelongsTo
    {
        return $this->belongsTo(Map::class);
    }
}
