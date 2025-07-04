<?php

declare(strict_types=1);

namespace App\Data\Responses;

use App\Data\Data;
use App\Enums\MemberStatus;
use App\Models\Account;

class GetAccountDetailsData extends Data
{
    public bool $isMember;

    public bool $isBanned;

    /**
     * @param MemberStatus $status
     */
    public function __construct(
        public string $username,
        bool $member,
        public MemberStatus|string $status,
        public ?array $badges,
        public array $skins,
        public int $achievementsPoints,
        bool $banned,
        public string $banReason,
        public ?int $gems = null,
        public ?string $email = null,  // irrelevant
        public ?string $memberExpiration = null,
    ) {
        $this->isMember = $member;
        $this->isBanned = $banned;
        $this->status = MemberStatus::fromValue($status);

        Account::updateOrCreate([
            'username' => $this->username,
        ], [
            'is_member' => $this->isMember,
            'status' => $this->status,
            'member_expiration' => $this->memberExpiration,
            'badges' => $this->badges,
            'gems' => $this->gems,
            'achievements_points' => $this->achievementsPoints,
            'is_banned' => $this->isBanned,
            'ban_reason' => $this->banReason,
            'skins' => $this->skins,
        ]);
    }
}
