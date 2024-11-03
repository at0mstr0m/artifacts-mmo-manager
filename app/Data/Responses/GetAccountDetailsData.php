<?php

declare(strict_types=1);

namespace App\Data\Responses;

use App\Data\Data;

class GetAccountDetailsData extends Data
{
    public function __construct(
        public string $username,
        public string $email,
        public bool $subscribed,
        public int $subscribedUntil,
        public bool $founder,
        public ?array $badges,
        public int $gems,
        public bool $banned,
        public string $banReason,
    ) {}
}
