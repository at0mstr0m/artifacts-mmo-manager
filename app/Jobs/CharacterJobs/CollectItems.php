<?php

declare(strict_types=1);

namespace App\Jobs\CharacterJobs;

use App\Models\Drop;
use App\Models\Item;
use App\Models\Monster;
use App\Models\Resource;
use App\Jobs\CharacterJob;
use Illuminate\Support\Collection;
use App\Data\Schemas\SimpleItemData;

class CollectItems extends CharacterJob
{
    /**
     * @param Collection<SimpleItemData> $items
     */
    public function __construct(
        int $characterId,
        protected Collection $items,
    ) {
        parent::__construct($characterId);
        $this->constructorArguments = compact('characterId', 'items');
    }

    protected function handleCharacter(): void
    {
        // check if all items are already collected
        $complete = $this->items->every(
            fn (SimpleItemData $item) => $this->character->hasInInventory($item)
        );

        if ($complete) {
            $this->log('All items are already collected');
            $this->dispatchNextJob();

            return;
        }

        $this->log('Not all Items collected yet.');

        /** @var SimpleItemData */
        $itemData = $this->items->firstWhere(
            fn (SimpleItemData $item) => ! $this->character->hasInInventory($item)
        );

        /** @var Item */
        $nextItem = $itemData->getModel();

        $this->log("Must collect {$itemData->quantity} units of {$nextItem->name}");
        $currentQuantity = $this->character->countInInventory($nextItem);
        $this->log("currently has {$currentQuantity} units");

        /** @var Drop */
        $drop = $nextItem->drops()->orderBy('rate')->first();

        $job = match ($drop?->source_type) {
            Monster::class => new FightMonsterDrop(
                $this->characterId,
                $drop->source_id,
                $itemData,
            ),
            Resource::class => new GatherItem(
                $this->characterId,
                $nextItem->id,
                $itemData->quantity,
            ),
            default => null,
        };

        if ($job) {
            $this->dispatchWithComeback($job);

            return;
        }

        $this->log("Cannot obtain {$nextItem->name} as drop from any source.");

        if ($nextItem->craft()->doesntExist()) {
            $this->fail(
                'Item '
                . '$nextItem->name'
                . ' can neither be obtained as drop from source nor be crafted'
            );
        }

        $this->log("Item {$nextItem->name} can be crafted");
        $this->dispatchWithComeback(
            new CollectRawMaterialsToCraft(
                $this->characterId,
                $nextItem->id,
                $itemData->quantity
            )
        );
    }
}
