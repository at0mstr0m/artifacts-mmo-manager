<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $code
 * @property int $stock
 * @property int $sell_price
 * @property int $buy_price
 * @property int $max_quantity
 * @property-read Item|null $item
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrandExchangeItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrandExchangeItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GrandExchangeItem query()
 *
 * @mixin \Eloquent
 */
class GrandExchangeItem extends Model
{
    protected $fillable = [
        'code',
        'stock',
        'sell_price',
        'buy_price',
        'max_quantity',
    ];

    protected $casts = [
        'code' => 'string',
        'stock' => 'integer',
        'sell_price' => 'integer',
        'buy_price' => 'integer',
        'max_quantity' => 'integer',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'code', 'code');
    }
}
