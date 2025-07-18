<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;

class DropData extends Data
{
    public function __construct(
        public string $code,
        public int $quantity,
    ) {}
}
