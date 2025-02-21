<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\IdentifiableByCode;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @property string $code
 * @property int $level
 * @property int $hp
 * @property int $attack_fire
 * @property int $attack_earth
 * @property int $attack_water
 * @property int $attack_air
 * @property int $res_fire
 * @property int $res_earth
 * @property int $res_water
 * @property int $res_air
 * @property int $critical_strike
 * @property int $min_gold
 * @property int $max_gold
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Drop> $drops
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Effect> $effects
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Map> $locations
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Monster newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Monster newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Monster query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Monster searchByCode(string $search = '')
 *
 * @mixin \Eloquent
 */
class Monster extends Model
{
    use IdentifiableByCode;

    protected $fillable = [
        'name',
        'code',
        'level',
        'hp',
        'attack_fire',
        'attack_earth',
        'attack_water',
        'attack_air',
        'res_fire',
        'res_earth',
        'res_water',
        'res_air',
        'critical_strike',
        'min_gold',
        'max_gold',
    ];

    protected $casts = [
        'name' => 'string',
        'code' => 'string',
        'level' => 'integer',
        'hp' => 'integer',
        'attack_fire' => 'integer',
        'attack_earth' => 'integer',
        'attack_water' => 'integer',
        'attack_air' => 'integer',
        'res_fire' => 'integer',
        'res_earth' => 'integer',
        'res_water' => 'integer',
        'res_air' => 'integer',
        'critical_strike' => 'integer',
        'min_gold' => 'integer',
        'max_gold' => 'integer',
    ];

    public function drops(): MorphMany
    {
        return $this->morphMany(Drop::class, 'source');
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Map::class, 'content_code', 'code');
    }

    public function effects(): BelongsToMany
    {
        return $this->belongsToMany(Effect::class)
            ->withPivot('value');
    }
}
