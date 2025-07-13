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
 * @property FightResults $result
 * @property-read Character $character
 * @property-read \Illuminate\Database\Eloquent\Collection<int, FightLog> $logs
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
        'result',
        'drops'
    ];

    protected $casts = [
        'xp' => 'integer',
        'gold' => 'integer',
        'turns' => 'integer',
        'result' => FightResults::class,
        'drops' => 'array',
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
