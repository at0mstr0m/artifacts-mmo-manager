<?php

declare(strict_types=1);

namespace App\Data\Schemas;

use App\Data\Data;
use App\Enums\Skills;
use Illuminate\Support\Collection;

class CraftData extends Data
{
    /**
     * @param Collection<SimpleItemData> $items
     */
    public function __construct(
        public Skills|string $skill,
        public int $level,
        public array|Collection $items,
        public int $quantity,
    ) {
        $this->skill = Skills::fromValue($skill);
        $this->items = collect($items)
            ->snakeKeys()
            ->mapValuesInto(SimpleItemData::class);
    }
}
