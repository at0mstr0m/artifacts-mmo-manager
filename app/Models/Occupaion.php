<?php

declare(strict_types=1);

namespace App\Models;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property array<array-key, mixed> $config
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Character> $characters
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occupaion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occupaion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Occupaion query()
 *
 * @mixin \Eloquent
 */
class Occupaion extends Model
{
    protected $fillable = ['config'];

    protected $casts = [
        'config' => 'array',
    ];

    public function characters()
    {
        return $this->hasMany(Character::class);
    }
}
