<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $monster_id
 * @property int $item_id
 * @property int $rate
 * @property int $min_quantity
 * @property int $max_quantity
 * @property-read Item $item
 * @property-read Monster $monster
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Drop newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Drop newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Drop query()
 *
 * @mixin \Eloquent
 */
class Drop extends Model
{
    protected $fillable = [
        'rate',
        'min_quantity',
        'max_quantity',
    ];

    protected $casts = [
        'rate' => 'integer',
        'min_quantity' => 'integer',
        'max_quantity' => 'integer',
    ];

    public function source(): MorphTo
    {
        return $this->morphTo();
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
