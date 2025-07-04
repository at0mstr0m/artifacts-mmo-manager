<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ComparisonOperators;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemCondition extends Model
{
    protected $fillable = [
        'code',
        'operator',
        'value',
    ];

    protected $casts = [
        'code' => 'string',
        'operator' => ComparisonOperators::class,
        'value' => 'integer',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
