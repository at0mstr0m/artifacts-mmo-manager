<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $name
 * @property string|null $code
 * @property int|null $level
 * @property string|null $type
 * @property string|null $subtype
 * @property string|null $description
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Craft> $craft
 * @property-read int|null $craft_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Drop> $drops
 * @property-read int|null $drops_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Effect> $effects
 * @property-read int|null $effects_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item query()
 *
 * @mixin \Eloquent
 */
class Item extends Model
{
    protected $fillable = [
        'name',
        'code',
        'level',
        'type',
        'subtype',
        'description',
        'tradeable',
    ];

    protected $casts = [
        'name' => 'string',
        'code' => 'string',
        'level' => 'integer',
        'type' => 'string',
        'subtype' => 'string',
        'description' => 'string',
        'tradeable' => 'boolean',
    ];

    public function effects(): BelongsToMany
    {
        return $this->belongsToMany(Effect::class);
    }

    public function craft(): BelongsToMany
    {
        return $this->belongsToMany(Craft::class)->withPivot(['quantity']);
    }

    public function drops(): MorphMany
    {
        return $this->morphMany(Drop::class, 'source');
    }

    public function taskRewards(): HasMany
    {
        return $this->hasMany(TaskReward::class, 'code', 'code');
    }
}
