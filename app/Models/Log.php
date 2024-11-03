<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
