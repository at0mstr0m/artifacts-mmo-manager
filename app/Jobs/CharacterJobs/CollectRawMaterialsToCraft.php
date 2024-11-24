<?php

declare(strict_types=1);

namespace App\Jobs\CharacterJobs;

use App\Data\Schemas\SimpleItemData;
use App\Jobs\CharacterJob;
use App\Models\Item;

class CollectRawMaterialsToCraft extends CharacterJob
{
    protected Item $item;

    public function __construct(
        protected int $characterId,
        protected int $itemId,
        protected int $quantity = 1,
    ) {
        $this->constructorArguments = compact(
            'characterId',
            'itemId',
            'quantity'
        );
    }

    protected function handleCharacter(): void
    {
        if (
            $this->character->hasInInventory($this->itemId, $this->quantity)
            || $this->character->hasEquipped($this->itemId)
        ) {
            $this->log('Has all items in desired quantity ' . $this->quantity);
            $this->dispatchNextJob();

            return;
        }

        $this->log('Not all items collected yet.');

        $this->item = Item::find($this->itemId);

        $rawMaterials = $this->item->craft->requiredItems;
        $hasAllRawMaterials = $rawMaterials->every(
            fn (Item $requiredItem): bool => $this->character->hasInInventory(
                $requiredItem->id,
                $requiredItem->pivot->quantity * $this->quantity
            )
        );

        if ($hasAllRawMaterials) {
            $this->log(
                'Has all required items to craft '
                . $this->quantity
                . ' '
                . $this->item->name
            );
            $this->dispatchWithComeback(
                new CraftItem($this->characterId, $this->itemId, $this->quantity)
            );

            return;
        }

        $this->log('Not all required items collected yet.');
        $items = $rawMaterials->map(
            fn (Item $requiredItem): SimpleItemData => SimpleItemData::from([
                'code' => $requiredItem->code,
                'quantity' => $requiredItem->pivot->quantity * $this->quantity,
            ])
        );

        $this->dispatchWithComeback(
            new CollectItems($this->characterId, $items)
        );
    }
}
