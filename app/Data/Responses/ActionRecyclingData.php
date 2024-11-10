<?php

declare(strict_types=1);

namespace App\Data\Responses;

use App\Data\Data;
use App\Data\Schemas\CharacterData;
use App\Data\Schemas\CooldownData;
use App\Data\Schemas\SimpleItemData;
use Illuminate\Support\Collection;

class ActionRecyclingData extends Data
{
    /**
     * @var Collection<SimpleItemData>
     */
    public Collection $items;

    /**
     * @param CooldownData $cooldown
     * @param CharacterData $character
     */
    public function __construct(
        public array|CooldownData $cooldown,
        public array|CharacterData $character,
        array $details,
    ) {
        $this->cooldown = CooldownData::from($cooldown);
        $this->character = CharacterData::from($character);
        $this->items = SimpleItemData::collection($details);
    }
}
