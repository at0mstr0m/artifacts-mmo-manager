<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FightResults;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $character_id
 * @property int $xp
 * @property int $gold
 * @property int $turns
 * @property int $monster_blocked_hits_fire
 * @property int $monster_blocked_hits_earth
 * @property int $monster_blocked_hits_water
 * @property int $monster_blocked_hits_air
 * @property int $monster_blocked_hits_total
 * @property int $player_blocked_hits_fire
 * @property int $player_blocked_hits_earth
 * @property int $player_blocked_hits_water
 * @property int $player_blocked_hits_air
 * @property int $player_blocked_hits_total
 * @property FightResults $result
 * @property-read Character $character
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FightLog> $logs
 * @property-read int|null $logs_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fight newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fight newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fight query()
 *
 * @mixin \Eloquent
 */
class Fight extends Model
{
    protected $fillable = [
        'xp',
        'gold',
        'turns',
        'monster_blocked_hits_fire',
        'monster_blocked_hits_earth',
        'monster_blocked_hits_water',
        'monster_blocked_hits_air',
        'monster_blocked_hits_total',
        'player_blocked_hits_fire',
        'player_blocked_hits_earth',
        'player_blocked_hits_water',
        'player_blocked_hits_air',
        'player_blocked_hits_total',
        'result',
    ];

    protected $casts = [
        'xp' => 'integer',
        'gold' => 'integer',
        'turns' => 'integer',
        'monster_blocked_hits_fire' => 'integer',
        'monster_blocked_hits_earth' => 'integer',
        'monster_blocked_hits_water' => 'integer',
        'monster_blocked_hits_air' => 'integer',
        'monster_blocked_hits_total' => 'integer',
        'player_blocked_hits_fire' => 'integer',
        'player_blocked_hits_earth' => 'integer',
        'player_blocked_hits_water' => 'integer',
        'player_blocked_hits_air' => 'integer',
        'player_blocked_hits_total' => 'integer',
        'result' => FightResults::class,
    ];

    public function character()
    {
        return $this->belongsTo(Character::class);
    }

    public function logs()
    {
        return $this->hasMany(FightLog::class);
    }
}
