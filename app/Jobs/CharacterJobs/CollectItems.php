<?php

declare(strict_types=1);

namespace App\Jobs\CharacterJobs;

use App\Data\Schemas\SimpleItemData;
use App\Jobs\CharacterJob;
use App\Models\Drop;
use App\Models\Item;
use App\Models\Monster;
use App\Models\Resource;
use Illuminate\Support\Collection;

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

    public function handleCharacter(): void
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

        $this->log('Must collect ' . $nextItem->name);

        /** @var Drop */
        $drop = $nextItem->drops()->orderBy('rate')->first();

        $job = match ($drop?->source_type) {
            Monster::class => new FightMonster(
                $this->characterId,
                $drop->source_id
            ),
            Resource::class => new GatherItem(
                $this->characterId,
                $nextItem->id,
                $itemData->quantity
            ),
            default => $this->fail('Cannot find Drop for Item ' . $nextItem->name),
        };

        $this->dispatchWithComeback($job);
    }
}
