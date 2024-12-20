<?php

declare(strict_types=1);

namespace App\Jobs\CharacterJobs;

use App\Jobs\CharacterJob;
use App\Models\Map;
use App\Models\Resource;

class FarmResource extends CharacterJob
{
    private Resource $resource;

    public function __construct(
        protected int $characterId,
        protected int $resourceId,
    ) {
        $this->constructorArguments = compact('characterId', 'resourceId');
    }

    protected function handleCharacter(): void
    {
        $this->handleFullInventory();

        $this->ensureIsAtResourceLocation();

        $this->gatherResource();
    }

    private function handleFullInventory(): void
    {
        if (! $this->character->inventoryIsFull()) {
            return;
        }

        $this->log('Inventory is full');
        $this->dispatchWithComeback(new EmptyInventory($this->character->id));

        $this->end();
    }

    private function ensureIsAtResourceLocation(): void
    {
        $this->resource = Resource::find($this->resourceId);
        $location = $this->character->location;
        if (
            $location->content_type === 'resource'
            && $location->content_code === $this->resource->code
        ) {
            $this->log('Is currently at resource location');

            return;
        }

        /** @var Map */
        $resourceLocation = Map::firstWhere([
            ['content_type', 'resource'],
            ['content_code', $this->resource->code],
        ]);
        $moveData = $this->character->moveTo($resourceLocation);
        $this->log('Moving to resource location');
        $this->selfDispatch()->delay($moveData->cooldown->expiresAt);

        $this->end();
    }

    private function gatherResource(): void
    {
        $this->log('Gathering resource ' . $this->resource->name);

        $delay = $this->character->gather()->cooldown->expiresAt;
        $this->dispatchNextJob()?->delay($delay);
    }
}
