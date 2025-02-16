<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @property string $skin
 * @property int $x
 * @property int $y
 * @property string|null $x_y
 * @property string|null $content_type
 * @property string|null $content_code
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Event> $events
 * @property-read Monster|null $monster
 * @property-read \App\Models\Resource|null $resource
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Map newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Map newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Map query()
 *
 * @mixin \Eloquent
 */
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

    protected $appends = [
        'x_y',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class, 'content_code', 'code');
    }

    public function monster(): BelongsTo
    {
        return $this->belongsTo(Monster::class, 'content_code', 'code');
    }
}
