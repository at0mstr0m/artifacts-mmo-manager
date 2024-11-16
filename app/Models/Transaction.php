<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $identifier
 * @property int $item_id
 * @property int $quantity
 * @property int $price
 * @property int $total_price
 * @property-read Item $item
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction query()
 *
 * @mixin \Eloquent
 */
class Transaction extends Model
{
    protected $fillable = [
        'identifier',
        'quantity',
        'price',
        'total_price',
    ];

    protected $casts = [
        'identifier' => 'string',
        'quantity' => 'integer',
        'price' => 'integer',
        'total_price' => 'integer',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
