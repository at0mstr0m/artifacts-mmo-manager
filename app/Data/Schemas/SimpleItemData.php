<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use App\Models\Item;

class SimpleItemData extends Data
{
    public function __construct(
        public string $code,
        public int $quantity,
    ) {}

    public function getModel(): Item
    {
        return Item::findByCode($this->code);
    }
}
