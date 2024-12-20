<?php

declare(strict_types=1);

namespace App\Jobs\CharacterJobs;

use App\Jobs\CharacterJob;
use App\Models\Item;
use App\Models\Map;
use Illuminate\Contracts\Database\Eloquent\Builder;

class GatherItem extends CharacterJob
{
    protected Item $item;

    public function __construct(
        protected int $characterId,
        protected int $itemId,
        protected int $count = 1,    // how many items to gather
    ) {
        $this->constructorArguments = compact(
            'characterId',
            'itemId',
            'count',
        );
    }

    protected function handleCharacter(): void
    {
        $this->handleFullInventory();

        $this->item = Item::find($this->itemId);

        $this->checkHasDesiredQuantity();

        $this->goToItemLocation();

        $this->gatherItem();
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

    private function checkHasDesiredQuantity(): void
    {
        $currentQuantity = $this->character->countInInventory($this->item);
        $this->log("Gathering {$currentQuantity}/{$this->count} units of {$this->item->name}");

        if ($currentQuantity >= $this->count) {
            $this->log('already have enough items');
            $this->dispatchNextJob();

            $this->end();
        }
    }

    private function goToItemLocation(): void
    {
        $currentLocation = $this->character->location;

        if (
            $currentLocation->content_type === 'resource'
            || $currentLocation->content_code === $this->item->code
        ) {
            $this->log('Is at resource location');

            return;
        }

        $this->log('Not at resource location');

        $itemLocation = Map::query()
            ->whereRelation('resource', fn (Builder $query) => $query
                ->whereRelation('drops', 'item_id', $this->item->id))
            ->inRandomOrder()
            ->first();

        if (! $itemLocation) {
            $this->fail("found no location to gather item {$this->item->name}");
        }

        $delay = $this
            ->character
            ->moveTo($itemLocation)
            ->cooldown
            ->expiresAt;

        $this->selfDispatch()->delay($delay);

        $this->end();
    }

    private function gatherItem(): void
    {
        $this->log("gathering item {$this->item->name}");
        $data = $this->character->gather();
        $delay = $data->cooldown->expiresAt;
        $count = $data
            ->details
            ->items
            ->where('code', $this->item->code)
            ->count();
        $this->log("gathered {$count} Units of {$this->item->name}");

        if ($this->character->refresh()->hasInInventory($this->item, $this->count)) {
            $this->log('all items gathered');
            $this->dispatchNextJob()?->delay($delay);
        } else {
            $this->log('not all items gathered yet');
            $this->selfDispatch()->delay($delay);
        }
    }
}
