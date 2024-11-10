<?php

declare(strict_types=1);

namespace App\Models;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $fight_id
 * @property string $text
 * @property-read Fight $fight
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FightLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FightLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FightLog query()
 *
 * @mixin \Eloquent
 */
class FightLog extends Model
{
    protected $fillable = [
        'text',
    ];

    protected $casts = [
        'text' => 'string',
    ];

    public function fight()
    {
        return $this->belongsTo(Fight::class);
    }
}
