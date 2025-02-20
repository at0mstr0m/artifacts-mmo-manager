<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use App\Models\Badge;

class BadgeData extends Data
{
    public function __construct(
        public string $code,
        public ?int $season,
        public string $description,
        public array $conditions,
    ) {
        $this->createIfNotExists();
    }

    private function createIfNotExists(): void
    {
        Badge::firstOrCreate([
            'code' => $this->code,
        ], [
            'season' => $this->season,
            'description' => $this->description,
            'conditions' => $this->conditions,
        ]);
    }
}
