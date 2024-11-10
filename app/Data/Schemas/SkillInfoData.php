<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use Illuminate\Support\Collection;

class SkillInfoData extends Data
{
    /**
     * @param Collection<SimpleItemData> $items
     */
    public function __construct(
        public int $xp,
        public array|Collection $items,
    ) {
        $this->items = SimpleItemData::collection($items);
    }
}
