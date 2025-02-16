<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\MemberStatus;

/**
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $username
 * @property string|null $email
 * @property bool $is_subscribed
 * @property int|null $subscribed_until
 * @property MemberStatus $status
 * @property array<array-key, mixed>|null $badges
 * @property int $gems
 * @property int $achievements_points
 * @property bool $banned
 * @property string|null $ban_reason
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Account query()
 *
 * @mixin \Eloquent
 */
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
