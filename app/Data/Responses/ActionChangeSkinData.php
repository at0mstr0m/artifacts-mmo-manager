<?php

declare(strict_types=1);

namespace App\Data\Responses;

use App\Data\Data;
use App\Data\Schemas\CharacterData;
use App\Data\Schemas\CooldownData;
use App\Enums\CharacterSkins;

class ActionChangeSkinData extends Data
{
    /**
     * @param CooldownData $cooldown
     * @param CharacterData $character
     */
    public function __construct(
        public array|CooldownData $cooldown,
        public CharacterSkins|string $skin,
        public array|CharacterData $character,
    ) {
        $this->cooldown = CooldownData::from($cooldown);
        $this->skin = CharacterSkins::fromValue($skin);
        $this->character = CharacterData::from($character);
    }
}
