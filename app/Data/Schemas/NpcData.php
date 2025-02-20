<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use App\Enums\NpcTypes;
use App\Models\Npc;

class NpcData extends Data
{
    public function __construct(
        public string $name,
        public string $code,
        public string $description,
        public NpcTypes|string $type,
    ) {
        $this->type = NpcTypes::fromValue($this->type);
        $this->createIfNotExists();
    }

    private function createIfNotExists(): void
    {
        Npc::firstOrCreate([
            'code' => $this->code,
        ], [
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
        ]);
    }
}
