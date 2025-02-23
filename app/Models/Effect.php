<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EffectSubTypes;
use App\Enums\EffectTypes;
use App\Traits\IdentifiableByCode;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @property string $code
 * @property string $description
 * @property EffectTypes $type
 * @property EffectSubTypes $subtype
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Item> $items
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Monster> $monsters
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Effect newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Effect newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Effect query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Effect searchByCode(string $search = '')
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

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class)
            ->withPivot('value');
    }

    public function monsters(): BelongsToMany
    {
        return $this->belongsToMany(Monster::class)
            ->withPivot('value');
    }
}
