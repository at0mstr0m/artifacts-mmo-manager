<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Character;
use App\Models\Item;
use Illuminate\Support\Str;
use Lorisleiva\Actions\Concerns\AsAction;

class EvaluateFittingItemSlot
{
    use AsAction;

    private Character $character;
    private Item $item;

    /**
     * Determine if the item fits in any of the character's slots.
     *
     * returns
     *  1. null if the item does not fit in any slot
     *  2. false if the item fits in a slot but the slot is already occupied
     *  3. slot name if the item fits in a slot and the slot is empty
     */
    public function handle(Character $character, Item|string $item): null|false|string
    {
        $this->character = $character;
        $this->item = $item instanceof Item ? $item : Item::findByCode($item);

        $slotNames = $this->character->getSlotNames();

        // e.g. a "consumable_slot" does not exist
        if (! $slotNames->firstWhere(
            fn (string $slot) => Str::startsWith($slot, $this->item->type)
        )) {
            return null;
        }

        $slot = $this->item->type . '_slot';
        if ($slotNames->contains($slot)) {
            return $this->character->slotIsOccupied($slot) ? false : $slot;
        }

        $maxCount = $this->item->type === 'artifact' ? 3 : 2;

        for ($i = 1; $i <= $maxCount; ++$i) {
            $slot = $this->item->type . $i . '_slot';

            if ($slotNames->contains($slot)) {
                if ($this->character->slotIsOccupied($slot)) {
                    continue;
                }

                return $slot;
            }

            return null;
        }

        return false;
    }
}
