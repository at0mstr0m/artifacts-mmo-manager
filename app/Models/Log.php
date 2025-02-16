<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $character_id
 * @property string $account
 * @property string $type
 * @property string $description
 * @property array<array-key, mixed> $content
 * @property \Illuminate\Support\Carbon|null $cooldown
 * @property \Illuminate\Support\Carbon $logged_at
 * @property-read Character|null $character
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Log newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Log newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Log query()
 *
 * @mixin \Eloquent
 */
class Log extends Model
{
    protected $fillable = [
        'account',
        'type',
        'description',
        'content',
        'cooldown',
        'logged_at',
    ];

    protected $casts = [
        'account' => 'string',
        'type' => 'string',
        'description' => 'string',
        'content' => 'array',
        'cooldown' => 'datetime',
        'logged_at' => 'datetime',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'content' => '{}',
    ];

    public function character(): BelongsTo
    {
        return $this->belongsTo(Character::class);
    }
}
