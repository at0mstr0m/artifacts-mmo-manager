<?php

declare(strict_types=1);

namespace App\Data\Responses;

use App\Data\Data;
use App\Enums\MemberStatus;
use App\Models\Account;

class GetAccountDetailsData extends Data
{
    public bool $isSubscribed;

    /**
     * @param MemberStatus $status
     */
    public function __construct(
        public string $username,
        bool $subscribed,
        public MemberStatus|string $status,
        public ?array $badges,
        public int $achievementsPoints,
        public bool $banned,
        public string $banReason,
        public ?int $gems = null,
        public int $subscribedUntil = 0,
        public ?string $email = null,  // irrelevant
    ) {
        $this->isSubscribed = $subscribed;
        $this->status = MemberStatus::fromValue($status);

        Account::updateOrCreate([
            'username' => $this->username,
        ], [
            'is_subscribed' => $this->isSubscribed,
            'status' => $this->status,
            'subscribed_until' => $this->subscribedUntil,
            'badges' => $this->badges,
            'gems' => $this->gems,
            'achievements_points' => $this->achievementsPoints,
            'banned' => $this->banned,
            'ban_reason' => $this->banReason,
        ]);
    }
}
