<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ComparisonOperators;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $item_id
 * @property string $code
 * @property ComparisonOperators $operator
 * @property int $value
 * @property-read Item $item
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemCondition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemCondition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ItemCondition query()
 *
 * @mixin \Eloquent
 */
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
