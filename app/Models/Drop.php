<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function monster(): BelongsTo
    {
        return $this->belongsTo(Monster::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
