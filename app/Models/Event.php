<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @property string $code
 * @property int $map_id
 * @property string $previous_skin
 * @property int $duration
 * @property \Illuminate\Support\Carbon $expiration
 * @property \Illuminate\Support\Carbon $started_at
 * @property-read Map $map
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Event query()
 *
 * @mixin \Eloquent
 */
class Event extends Model
{
    protected $fillable = [
        'name',
        'code',
        'previous_skin',
        'duration',
        'expiration',
        'started_at',
    ];

    protected $casts = [
        'name' => 'string',
        'code' => 'string',
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
