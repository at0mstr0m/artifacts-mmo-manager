<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
