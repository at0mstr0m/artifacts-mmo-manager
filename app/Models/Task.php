<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Skills;
use App\Enums\TaskTypes;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $code
 * @property int $level
 * @property TaskTypes $type
 * @property int $min_quantity
 * @property int $max_quantity
 * @property Skills|null $skill
 * @property int $rewarded_coins
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Task query()
 *
 * @mixin \Eloquent
 */
class Task extends Model
{
    protected $fillable = [
        'code',
        'level',
        'type',
        'min_quantity',
        'max_quantity',
        'skill',
        'rewarded_coins',
    ];

    protected $casts = [
        'code' => 'string',
        'level' => 'integer',
        'type' => TaskTypes::class,
        'min_quantity' => 'integer',
        'max_quantity' => 'integer',
        'skill' => Skills::class,
        'rewarded_coins' => 'integer',
    ];
}
