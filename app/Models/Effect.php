<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EffectSubTypes;
use App\Enums\EffectTypes;
use App\Traits\IdentifiableByCode;

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
    use IdentifiableByCode;

    protected $fillable = [
        'name',
        'code',
        'description',
        'type',
        'subtype',
    ];

    protected $casts = [
        'name' => 'string',
        'code' => 'string',
        'description' => 'string',
        'type' => EffectTypes::class,
        'subtype' => EffectSubTypes::class,
    ];

    public function items()
    {
        return $this->belongsToMany(Item::class)
            ->withPivot('value');
    }
}
