<?php

declare(strict_types=1);

namespace App\Traits;

use App\Data\Schemas\SimpleItemData;
use App\Models\Item;
use Illuminate\Support\Collection;

/**
 * @property Collection<SimpleItemData> $bank
 */
trait UpdatesDeposit
{
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
