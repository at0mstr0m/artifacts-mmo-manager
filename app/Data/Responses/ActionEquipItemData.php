<?php

declare(strict_types=1);

namespace App\Data\Responses;

use App\Data\Data;
use App\Data\Schemas\CharacterData;
use App\Data\Schemas\CooldownData;
use App\Data\Schemas\ItemData;

class ActionEquipItemData extends Data
{
    /**
     * @param CooldownData $cooldown
     * @param ItemData $item
     * @param CharacterData $character
     */
    public function __construct(
        public string $slot,
        public array|CooldownData $cooldown,
        public array|ItemData $item,
        public array|CharacterData $character,
    ) {
        $this->cooldown = CooldownData::from($cooldown);
        $this->item = ItemData::from($item);
        $this->character = CharacterData::from($character);
    }
}
