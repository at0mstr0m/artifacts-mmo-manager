<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
