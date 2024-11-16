<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;

class DropRateData extends Data
{
    public function __construct(
        public string $code,
        public int $rate,
        public int $minQuantity,
        public int $maxQuantity,
    ) {}
}
