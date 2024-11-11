<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'identifier',
        'placed_at',
        'quantity',
        'price',
        'total_price',
    ];

    protected $casts = [
        'identifier' => 'string',
        'placed_at' => 'datetime',
        'quantity' => 'integer',
        'price' => 'integer',
        'total_price' => 'integer',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
