<?php

declare(strict_types=1);

namespace App\Data\Responses;

use App\Data\Data;
use App\Data\Schemas\CharacterData;
use App\Data\Schemas\CooldownData;
use App\Data\Schemas\SimpleItemData;

class ActionTaskTradeData extends Data
{
    /**
     * @param CooldownData $cooldown
     * @param SimpleItemData $trade
     * @param CharacterData $character
     */
    public function __construct(
        public array|CooldownData $cooldown,
        public array|SimpleItemData $trade,
        public array|CharacterData $character,
    ) {
        $this->cooldown = CooldownData::from($cooldown);
        $this->trade = SimpleItemData::from($trade);
        $this->character = CharacterData::from($character);
    }
}
