<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Skills;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @property string $code
 * @property Skills $skill
 * @property int $level
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Drop> $drops
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Map> $maps
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Resource query()
 *
 * @mixin \Eloquent
 */
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

    public function maps(): HasMany
    {
        return $this->hasMany(Map::class, 'content_code', 'code');
    }
}
