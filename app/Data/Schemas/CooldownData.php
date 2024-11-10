<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use App\Enums\CooldownReasons;
use Illuminate\Support\Carbon;

class CooldownData extends Data
{
    /**
     * @param Carbon $startedAt
     * @param Carbon $expiration
     * @param CooldownReasons $reason
     */
    public function __construct(
        public int $totalSeconds,
        public int $remainingSeconds,
        public Carbon|string $startedAt,
        public Carbon|string $expiration,
        public CooldownReasons|string $reason,
    ) {
        $this->startedAt = Carbon::parse($startedAt);
        $this->expiration = Carbon::parse($expiration);
        $this->reason = CooldownReasons::fromValue($reason);
    }
}
