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
        protected int $quantity = 1,    // how many items to gather
        protected ?int $mapId = null,
    ) {
        $this->constructorArguments = compact(
            'characterId',
            'itemId',
            'quantity',
            'mapId',
        );
    }

    public function handleCharacter(): void
    {
        $this->item = Item::find($this->itemId);

        $this->log("Gathering {$this->quantity} units of {$this->item->name}");
        $currentQuantity = $this->character->countInInventory($this->item);
        $this->log("currently loaded with {$currentQuantity} units");

        if ($currentQuantity >= $this->quantity) {
            $this->log('already have enough items');
            $this->dispatchNextJob();

            return;
        }

        $itemLocation = Map::findOr($this->mapId, fn () => Map::query()
            ->whereRelation('resource', fn (Builder $query) => $query
                ->whereRelation('drops', 'item_id', $this->item->id))
            ->inRandomOrder()
            ->first());

        if (! $itemLocation) {
            throw new \Exception(
                "found no location to gather item {$this->item->name}",
                1
            );
        }

        if (! $this->mapId) {
            $this->mapId = $itemLocation->id;
            $this->constructorArguments['mapId'] = $this->mapId;
        }

        if ($this->character->isAt($itemLocation)) {
            $this->log("gathering item {$this->item->name}");
            $data = $this->character->gather();
            $delay = $data->cooldown->expiresAt;
            $count = $data->details
                ->items
                ->where('code', $this->item->code)
                ->count();
            $this->log("gathered {$count} Units of {$this->item->name}");
        } else {
            $this->log("moving to gathering location {$this->item->name}");
            $delay = $this
                ->character
                ->move($itemLocation)
                ->cooldown
                ->expiresAt;
        }

        $this->log("delaying for {$delay->diffInSeconds(now())} Seconds");

        if ($this->character->refresh()->hasInInventory($this->item, $this->quantity)) {
            $this->log('all items gathered');
            $this->dispatchNextJob()?->delay($delay);
        } else {
            $this->log('not all items gathered yet');
            $this->selfDispatch()->delay($delay);
        }
    }
}
