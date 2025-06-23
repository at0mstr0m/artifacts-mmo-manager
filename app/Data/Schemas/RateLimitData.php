<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;

class RateLimitData extends Data
{
    public function __construct(
        public string $type,
        public string $value,
    ) {}
}
