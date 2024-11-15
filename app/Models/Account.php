<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MemberStatus;

class Account extends Model
{
    protected $fillable = [
        'username',
        'email',
        'is_subscribed',
        'subscribed_until',
        'status',
        'badges',
        'achievements_points',
        'banned',
        'ban_reason',
    ];

    protected $casts = [
        'username' => 'string',
        'email' => 'string',
        'is_subscribed' => 'boolean',
        'subscribed_until' => 'integer',
        'status' => MemberStatus::class,
        'badges' => 'array',
        'achievements_points' => 'integer',
        'banned' => 'boolean',
        'ban_reason' => 'string',
    ];
}
