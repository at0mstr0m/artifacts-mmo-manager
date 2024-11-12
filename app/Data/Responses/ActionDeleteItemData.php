<?php

declare(strict_types=1);

namespace App\Data\Responses;

use App\Data\Data;
use App\Data\Schemas\CharacterData;
use App\Data\Schemas\CooldownData;
use App\Data\Schemas\SimpleItemData;

class ActionDeleteItemData extends Data
{
    /**
     * @param CooldownData $cooldown
     * @param SimpleItemData $item
     * @param CharacterData $character
     */
    public function __construct(
        public array|CooldownData $cooldown,
        public array|SimpleItemData $item,
        public array|CharacterData $character,
    ) {
        $this->cooldown = CooldownData::from($cooldown);
        $this->item = SimpleItemData::from($item);
        $this->character = CharacterData::from($character);
    }
}
