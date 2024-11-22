<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\IdentifiableByCode;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @property string $code
 * @property int $level
 * @property string $type
 * @property string $subtype
 * @property string $description
 * @property bool $tradeable
 * @property int $deposited
 * @property-read Craft|null $craft
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Drop> $drops
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Effect> $effects
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SellOrder> $sellOrders
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TaskReward> $taskRewards
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Item searchByCode(string $search = '')
 *
 * @mixin \Eloquent
 */
class Item extends Model
{
    use IdentifiableByCode;

    protected $fillable = [
        'name',
        'code',
        'level',
        'type',
        'subtype',
        'description',
        'tradeable',
        'deposited',
    ];

    protected $casts = [
        'name' => 'string',
        'code' => 'string',
        'level' => 'integer',
        'type' => 'string',
        'subtype' => 'string',
        'description' => 'string',
        'tradeable' => 'boolean',
        'deposited' => 'integer',
    ];

    public function effects(): BelongsToMany
    {
        return $this->belongsToMany(Effect::class);
    }

    public function craft(): HasOne
    {
        return $this->hasOne(Craft::class);
    }

    public function drops(): HasMany
    {
        return $this->hasMany(Drop::class);
    }

    public function taskRewards(): HasMany
    {
        return $this->hasMany(TaskReward::class, 'code', 'code');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function sellOrders(): HasMany
    {
        return $this->hasMany(SellOrder::class);
    }
}
