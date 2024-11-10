<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FightResults;

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
