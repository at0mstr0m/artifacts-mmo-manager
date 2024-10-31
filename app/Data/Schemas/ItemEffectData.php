<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;

class ItemEffectData extends Data
{
    public function __construct(
        public string $name,
        public int $value,
    ) {}
}
