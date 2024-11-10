<?php

declare(strict_types=1);

namespace App\Data\Responses;

use App\Data\Data;
use App\Data\Schemas\CharacterData;
use App\Data\Schemas\CooldownData;
use App\Data\Schemas\ItemData;
use App\Data\Schemas\SimpleItemData;
use App\Models\Item;
use App\Traits\UpdatesDeposit;
use Illuminate\Support\Collection;

class ActionDepositBankData extends Data
{
    use UpdatesDeposit;

    /**
     * @param CooldownData $cooldown
     * @param ItemData $item
     * @param Collection<SimpleItemData> $bank
     * @param CharacterData $character
     */
    public function __construct(
        public array|CooldownData $cooldown,
        public array|ItemData $item,
        public array|Collection $bank,
        public array|CharacterData $character,
    ) {
        $this->cooldown = CooldownData::from($cooldown);
        $this->item = CharacterData::from($item);
        $this->bank = SimpleItemData::collection($bank);
        $this->character = CharacterData::from($character);

        $this->updateDeposit();
    }

    protected function updateDeposit(): void
    {
        // reset all deposits to 0
        Item::getQuery()->update(['deposit' => 0]);
        // update deposits
        $this->bank->each(function (SimpleItemData $itemData) {
            $item = Item::firstWhere('code', $itemData->code);
            $item->update(['deposit' => $itemData->quantity]);
        });
    }
}
