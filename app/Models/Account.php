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
 * @property bool $is_member
 * @property int|null $member_expiration
 * @property MemberStatus $status
 * @property array<array-key, mixed>|null $badges
 * @property int $gems
 * @property int $achievements_points
 * @property bool $is_banned
 * @property string|null $ban_reason
 * @property array<array-key, mixed> $skins
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
        'is_member',
        'member_expiration',
        'status',
        'badges',
        'achievements_points',
        'is_banned',
        'ban_reason',
        'skins',
    ];

    protected $casts = [
        'username' => 'string',
        'email' => 'string',
        'is_member' => 'boolean',
        'member_expiration' => 'integer',
        'status' => MemberStatus::class,
        'badges' => 'array',
        'achievements_points' => 'integer',
        'is_banned' => 'boolean',
        'ban_reason' => 'string',
        'skins' => 'array',
    ];
}
