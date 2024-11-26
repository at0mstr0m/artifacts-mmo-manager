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
        protected int $count = 1,
    ) {
        $this->constructorArguments = compact(
            'characterId',
            'itemId',
            'count'
        );
    }

    protected function handleCharacter(): void
    {
        $this->checkHasDesiredQuantity();

        $this->craftItem();

        /*
         * Check here after crafting because inventory could have been be full
         * of raw materials.
         */
        $this->handleFullInventory();

        $this->collectRawMaterials();
    }

    private function checkHasDesiredQuantity(): void
    {
        if ($this->character->hasInInventory($this->itemId, $this->count)) {
            $this->log('Has all items in desired quantity ' . $this->count);
            $this->dispatchNextJob();

            $this->end();
        }

        $this->log('Not all items collected yet.');
    }

    private function handleFullInventory(): void
    {
        if (! $this->character->inventoryIsFull()) {
            return;
        }

        $this->log('Inventory is full');
        $this->dispatchNextJob();

        $this->end();
    }

    private function craftItem(): void
    {
        $this->item = Item::find($this->itemId);

        $hasRawMaterialToCraftOne = $this->item->craft->requiredItems->every(
            fn (Item $requiredItem): bool => $this->character->hasInInventory(
                $requiredItem->id,
                $requiredItem->pivot->quantity
            )
        );

        if (! $hasRawMaterialToCraftOne) {
            return;
        }

        $this->log("Has all required items to craft one {$this->item->name}");
        $this->dispatchWithComeback(
            new CraftItem($this->characterId, $this->itemId)
        );

        $this->end();
    }

    private function collectRawMaterials(): void
    {
        $this->log('Not all required items collected yet.');
        $items = $this->item->craft->requiredItems->map(
            fn (Item $requiredItem): SimpleItemData => new SimpleItemData(
                $requiredItem->code,
                $requiredItem->pivot->quantity,
            )
        );

        $this->dispatchWithComeback(
            new CollectItems($this->characterId, $items)
        );
    }
}
