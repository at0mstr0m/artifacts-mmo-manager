<?php

declare(strict_types=1);

namespace App\Data\Responses;

use App\Data\Data;
use App\Data\Schemas\CharacterData;
use App\Data\Schemas\CooldownData;
use App\Data\Schemas\MapData;

class ActionMoveData extends Data
{
    /**
     * @param CooldownData $cooldown
     * @param MapData $destination
     * @param CharacterData $character
     */
    public function __construct(
        public array|CooldownData $cooldown,
        public array|MapData $destination,
        public array|CharacterData $character,
    ) {
        $this->cooldown = CooldownData::from($cooldown);
        $this->destination = MapData::from($destination);
        $this->character = CharacterData::from($character);
    }
}
