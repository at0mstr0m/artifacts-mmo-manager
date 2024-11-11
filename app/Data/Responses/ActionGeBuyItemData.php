<?php

declare(strict_types=1);

namespace App\Data\Responses;

use App\Data\Data;
use App\Data\Schemas\CharacterData;
use App\Data\Schemas\CooldownData;
use App\Data\Schemas\SimpleItemData;
use App\Data\Schemas\TransactionData;
use Illuminate\Support\Collection;

class ActionGeBuyItemData extends Data
{
    /**
     * @var Collection<SimpleItemData>
     */
    public Collection $items;

    /**
     * @param CooldownData $cooldown
     * @param TransactionData $order
     * @param CharacterData $character
     */
    public function __construct(
        public array|CooldownData $cooldown,
        public array|TransactionData $order,
        public array|CharacterData $character,
    ) {
        $this->cooldown = CooldownData::from($cooldown);
        $this->order = TransactionData::from($order);
        $this->character = CharacterData::from($character);
    }
}
