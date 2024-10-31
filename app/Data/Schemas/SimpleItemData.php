<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use Spatie\LaravelData\Data;

class SimpleItemData extends Data
{
    public function __construct(
        public string $code,
        public int $quantity,
    ) {}
}
