<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use Illuminate\Support\Carbon;

class AnnouncementData extends Data
{
    public function __construct(
        public string $message,
        public Carbon|string $createdAt,
    ) {
        $this->createdAt = Carbon::parse($createdAt);
    }
}
