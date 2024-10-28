<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int         $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int         $character_id
 * @property int         $slot
 * @property string      $code
 * @property int         $quantity
 * @property-read Character $character
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryItem query()
 *
 * @mixin \Eloquent
 */
class InventoryItem extends Model
{
    protected $fillable = [
        'slot',
        'code',
        'quantity',
    ];

    protected $casts = [
        'character_id' => 'integer',
        'slot' => 'integer',
        'code' => 'string',
        'quantity' => 'integer',
    ];

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }
}
