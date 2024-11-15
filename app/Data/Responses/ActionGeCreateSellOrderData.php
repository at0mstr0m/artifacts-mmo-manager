<?php

declare(strict_types=1);

namespace App\Data\Responses;

use App\Data\Data;
use App\Data\Schemas\CharacterData;
use App\Data\Schemas\CooldownData;
use App\Data\Schemas\OrderData;
use App\Data\Schemas\SellOrderCreatedData;
use App\Data\Schemas\TransactionData;

class ActionGeCreateSellOrderData extends Data
{
    /**
     * @param CooldownData $cooldown
     * @param OrderData $order
     * @param CharacterData $character
     */
    public function __construct(
        public array|CooldownData $cooldown,
        public array|TransactionData $order,
        public array|CharacterData $character,
    ) {
        $this->cooldown = CooldownData::from($cooldown);
        $this->order = SellOrderCreatedData::from($order);
        $this->character = CharacterData::from($character);
    }
}
