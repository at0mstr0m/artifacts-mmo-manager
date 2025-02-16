<?php

declare(strict_types=1);

namespace App\Models;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @property int $value
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Item> $items
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Effect newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Effect newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Effect query()
 *
 * @mixin \Eloquent
 */
class Effect extends Model
{
    protected $fillable = [
        'name',
        'value',
    ];

    protected $casts = [
        'name' => 'string',
        'value' => 'integer',
    ];

    public function items()
    {
        return $this->belongsToMany(Item::class);
    }
}
