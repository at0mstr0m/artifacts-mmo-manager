<?php

declare(strict_types=1);

namespace App\Data\Responses;

use App\Data\Data;
use App\Data\Schemas\CharacterData;
use App\Data\Schemas\CooldownData;

class ActionNpcSellItemData extends Data
{
    public int $price;

    /**
     * @param CooldownData $cooldown
     * @param CharacterData $character
     */
    public function __construct(
        public array|CooldownData $cooldown,
        public array|CharacterData $character,
        array $transaction,
    ) {
        $this->cooldown = CooldownData::from($cooldown);
        $this->character = CharacterData::from($character);
        $this->price = $transaction['price'];
    }
}
