<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $code
 * @property int $min_quantity
 * @property int $max_quantity
 * @property float $rate
 * @property-read Item|null $item
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskReward newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskReward newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskReward query()
 *
 * @mixin \Eloquent
 */
class TaskReward extends Model
{
    protected $fillable = [
        'code',
        'min_quantity',
        'max_quantity',
        'rate',
    ];

    protected $casts = [
        'code' => 'string',
        'min_quantity' => 'integer',
        'max_quantity' => 'integer',
        'rate' => 'float',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'code', 'code');
    }
}
