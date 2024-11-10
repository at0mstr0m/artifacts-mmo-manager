<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
