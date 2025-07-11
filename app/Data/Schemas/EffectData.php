<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use App\Enums\EffectSubTypes;
use App\Enums\EffectTypes;
use App\Models\Effect;

class EffectData extends Data
{
    public function __construct(
        public string $name,
        public string $code,
        public string $description,
        public EffectTypes|string $type,
        public EffectSubTypes|string $subtype,
    ) {
        $this->type = EffectTypes::fromValue($type);
        $this->subtype = EffectSubTypes::fromValue($subtype);

        $this->createIfNotExists();
    }

    private function createIfNotExists(): void
    {
        Effect::firstOrCreate([
            'code' => $this->code,
        ], [
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type->value,
            'subtype' => $this->subtype->value,
        ]);
    }
}
