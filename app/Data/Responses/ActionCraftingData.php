<?php

declare(strict_types=1);

namespace App\Data\Responses;

use App\Data\Data;
use App\Data\Schemas\CharacterData;
use App\Data\Schemas\CooldownData;
use App\Data\Schemas\SkillInfoData;

class ActionCraftingData extends Data
{
    /**
     * @param CooldownData $cooldown
     * @param SkillInfoData $details
     * @param CharacterData $character
     */
    public function __construct(
        public array|CooldownData $cooldown,
        public array|SkillInfoData $details,
        public array|CharacterData $character,
    ) {
        $this->cooldown = CooldownData::from($cooldown);
        $this->details = SkillInfoData::from($details);
        $this->character = CharacterData::from($character);
    }
}
