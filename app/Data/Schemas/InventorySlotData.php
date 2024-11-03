<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;

class InventorySlotData extends Data
{
    public function __construct(
        public int $slot,
        public string $code,
        public int $quantity,
    ) {}
}
