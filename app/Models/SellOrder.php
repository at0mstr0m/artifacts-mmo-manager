<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
