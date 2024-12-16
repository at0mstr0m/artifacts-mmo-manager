<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AchievementTypes;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @property string $code
 * @property string $description
 * @property int $points
 * @property AchievementTypes $type
 * @property string|null $target
 * @property int $total
 * @property int $rewarded_gold
 * @property int|null $current
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property-read bool $is_completed
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Achievement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Achievement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Achievement query()
 *
 * @mixin \Eloquent
 */
class Achievement extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'points',
        'type',
        'target',
        'total',
        'rewarded_gold',
        'current',
        'completed_at',
    ];

    protected $casts = [
        'name' => 'string',
        'code' => 'string',
        'description' => 'string',
        'points' => 'integer',
        'type' => AchievementTypes::class,
        'target' => 'string',
        'total' => 'integer',
        'rewarded_gold' => 'integer',
        'current' => 'integer',
        'completed_at' => 'datetime',
    ];

    protected function isCompleted(): Attribute
    {
        return Attribute::get(fn (): bool => (bool) $this->completed_at);
    }
}
