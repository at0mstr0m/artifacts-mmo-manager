<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use App\Models\Item;
use App\Models\Npc;

class NpcItemData extends Data
{
    public function __construct(
        public string $code,
        public string $npc,
        public ?int $buyPrice,
        public ?int $sellPrice,
    ) {}

    public function getItem(): Item
    {
        return Item::findByCode($this->code);
    }

    public function getNpc(): Npc
    {
        return Npc::findByCode($this->npc);
    }
}
