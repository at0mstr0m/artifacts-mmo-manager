<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use App\Enums\CooldownReasons;
use Illuminate\Support\Carbon;

class CooldownData extends Data
{
    public Carbon $expiresAt;

    /**
     * @param Carbon $startedAt
     * @param CooldownReasons $reason
     */
    public function __construct(
        public int $totalSeconds,
        public int $remainingSeconds,
        public Carbon|string $startedAt,
        string $expiration,
        public CooldownReasons|string $reason,
    ) {
        $this->startedAt = Carbon::parse($startedAt);
        $this->expiresAt = Carbon::parse($expiration);
        $this->reason = CooldownReasons::fromValue($reason);
    }
}
