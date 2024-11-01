<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Skills;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property Skills $skill
 * @property int $level
 * @property int $quantity
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Item> $items
 * @property-read int|null $items_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Craft newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Craft newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Craft query()
 *
 * @mixin \Eloquent
 */
class Craft extends Model
{
    protected $fillable = [
        'skill',
        'level',
        'quantity',
    ];

    protected $casts = [
        'skill' => Skills::class,
        'level' => 'integer',
        'quantity' => 'integer',
    ];

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class)->withPivot(['quantity']);
    }
}
