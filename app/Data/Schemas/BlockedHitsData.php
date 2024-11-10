<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;

class BlockedHitsData extends Data
{
    public function __construct(
        public int $fire,
        public int $earth,
        public int $water,
        public int $air,
        public int $total,
    ) {}
}
