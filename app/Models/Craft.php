<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Skills;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $item_id
 * @property Skills $skill
 * @property int $level
 * @property int $quantity
 * @property-read Item $item
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Item> $requiredItems
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

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function requiredItems(): BelongsToMany
    {
        return $this->belongsToMany(
            Item::class,
            'craft_item',
            'craft_id',
            'item_code',
            'id',
            'code',
        )->withPivot(['quantity']);
    }
}
