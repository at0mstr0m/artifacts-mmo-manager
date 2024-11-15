<?php

declare(strict_types=1);

namespace App\Data\Responses;

use App\Data\Data;
use App\Data\Schemas\CharacterData;
use App\Data\Schemas\CooldownData;
use App\Data\Schemas\SellOrderCanceledData;

class ActionTaskCancelData extends Data
{
    /**
     * @param CooldownData $cooldown
     * @param SellOrderCanceledData $order
     * @param CharacterData $character
     */
    public function __construct(
        public array|CooldownData $cooldown,
        public array|SellOrderCanceledData $order,
        public array|CharacterData $character,
    ) {
        $this->cooldown = CooldownData::from($cooldown);
        $this->order = SellOrderCanceledData::from($order);
        $this->character = CharacterData::from($character);
    }
}
