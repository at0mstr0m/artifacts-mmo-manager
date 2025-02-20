<?php

declare(strict_types=1);

namespace App\Models;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $code
 * @property string $description
 * @property int|null $season
 * @property \Illuminate\Support\Collection<array-key, mixed> $conditions
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Badge query()
 *
 * @mixin \Eloquent
 */
class Badge extends Model
{
    protected $fillable = [
        'code',
        'season',
        'description',
        'conditions',
    ];

    protected $casts = [
        'code' => 'string',
        'season' => 'integer',
        'description' => 'string',
        'conditions' => 'collection',
    ];
}
