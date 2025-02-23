<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use App\Models\Effect;

class SimpleEffectData extends Data
{
    public function __construct(
        public string $code,
        public int $value,
    ) {}

    public function getModel()
    {
        return Effect::firstWhere('name', $this->code);
    }
}
