<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use Spatie\LaravelData\Data;

class ItemEffectData extends Data
{
    public function __construct(
        public string $name,
        public int $value,
    ) {}
}
