<?php

declare(strict_types=1);

namespace App\Data\Responses;

use App\Data\Data;
use App\Data\Schemas\CharacterData;
use App\Data\Schemas\CooldownData;

class ActionRestData extends Data
{
    /**
     * @param CooldownData $cooldown
     * @param CharacterData $character
     */
    public function __construct(
        public array|CooldownData $cooldown,
        public int $hpRestored,
        public array|CharacterData $character,
    ) {
        $this->cooldown = CooldownData::from($cooldown);
        $this->character = CharacterData::from($character);
    }
}
