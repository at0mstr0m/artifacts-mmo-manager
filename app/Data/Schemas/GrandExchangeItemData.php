<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;

class GrandExchangeItemData extends Data
{
    public function __construct(
        public string $code,
        public int $stock,
        public int $sellPrice,
        public int $buyPrice,
        public int $maxQuantity,
    ) {}
}
