<?php

declare(strict_types=1);

namespace App\Data\Responses;

use App\Actions\UpdateBankDepositsAction;
use App\Data\Data;
use App\Data\Schemas\CharacterData;
use App\Data\Schemas\CooldownData;
use App\Data\Schemas\ItemData;
use App\Data\Schemas\SimpleItemData;
use Illuminate\Support\Collection;

class ActionDepositBankData extends Data
{
    /**
     * @param CooldownData $cooldown
     * @param Collection<SimpleItemData> $items
     * @param Collection<SimpleItemData> $bank
     * @param CharacterData $character
     */
    public function __construct(
        public array|CooldownData $cooldown,
        public array|Collection $items,
        public array|Collection $bank,
        public array|CharacterData $character,
    ) {
        $this->cooldown = CooldownData::from($cooldown);
        $this->items = SimpleItemData::collection($items);
        $this->bank = SimpleItemData::collection($bank);
        $this->character = CharacterData::from($character);

        $this->updateDeposit();
    }

    protected function updateDeposit(): void
    {
        UpdateBankDepositsAction::run($this->bank);
    }
}
