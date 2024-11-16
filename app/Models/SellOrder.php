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
 * @property string|null $seller
 * @property string|null $buyer
 * @property \Illuminate\Support\Carbon|null $placed_at
 * @property \Illuminate\Support\Carbon|null $sold_at
 * @property int $quantity
 * @property int $price
 * @property int $total_price
 * @property int|null $tax
 * @property-read Item $item
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellOrder newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellOrder newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SellOrder query()
 *
 * @mixin \Eloquent
 */
class SellOrder extends Model
{
    protected $fillable = [
        'identifier',
        'seller',
        'buyer',
        'quantity',
        'price',
        'total_price',
        'placed_at',
        'tax',
    ];

    protected $casts = [
        'identifier' => 'string',
        'seller' => 'string',
        'buyer' => 'string',
        'quantity' => 'integer',
        'price' => 'integer',
        'total_price' => 'integer',
        'placed_at' => 'datetime',
        'sold_at' => 'datetime',
        'tax' => 'integer',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
