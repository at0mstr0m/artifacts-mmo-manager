<?php

declare(strict_types=1);

namespace App\Models;


class Order extends Model
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

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
